<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class LiquidaListado extends Listado{

	//Número de referencia
	public $numero_desde;
	public $numero_hasta;

    //Tributo
    public $tributo;

	//Estado
	public $est;

    //Ítem
    public $item;
    public $item_nombre;

    //Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;

	//Contribuyente
	public $contribuyente_desde;
	public $contribuyente_hasta;

	//Fecha Emisión
	public $fecha_emision_desde;
	public $fecha_emision_hasta;

	//Fecha Vencimiento
	public $fecha_venc_desde;
	public $fecha_venc_hasta;

	//Fecha Pago
	public $fecha_pago_desde;
	public $fecha_pago_hasta;
	
	//Periodos
	public $anio_desde;
	public $anio_hasta;
	public $cuota_desde;
	public $cuota_hasta;
	
	// Monto
	public $monto_desde;
	public $monto_hasta;
	
	// Estado
	public $estado;
	
	//Expediente
	public $expediente;
	
	//Usuario
	public $usuario;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_emision';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'item_nombre', 'fecha_emision_desde', 'fecha_emision_hasta', 'fecha_venc_desde', 'fecha_venc_hasta',
				'fecha_pago_desde', 'fecha_pago_hasta', 'estado', 'expediente','objeto_desde', 'objeto_hasta'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'numero_desde', 'numero_hasta', 'tributo', 'item', 'objeto_tipo',
				'contribuyente_desde', 'contribuyente_hasta', 'anio_desde', 'anio_hasta', 'cuota_desde', 'cuota_hasta', 'usuario'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'monto_desde', 'monto_hasta'
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'numero_desde', 'numero_hasta', 'tributo', 'item', 'item_nombre', 'objeto_desde', 'objeto_hasta',
                'objeto_tipo', 'contribuyente_desde', 'contribuyente_hasta', 'fecha_emision_desde', 'fecha_emision_hasta',
				'fecha_venc_desde', 'fecha_venc_hasta', 'fecha_pago_desde', 'fecha_pago_hasta', 'estado', 'expediente',
				'anio_desde', 'anio_hasta', 'cuota_desde', 'cuota_hasta', 'monto_desde', 'monto_hasta', 'usuario'
			]
		];
	}

	public function attributeLabels(){

		return [

			'numero_desde' 		=> 'Nº de referencia desde',
			'numero_hasta' 		=> 'Nº de referencia hasta',

		];
	}

	public function beforeValidate(){

		return true;
	}

	public function buscar(){

		$query		      	= new Query();
		$queryItem        	= null;
		$queryObjeto 		= null;
		$queryContribuyente	= null;
		$queryFechaEmision	= null;
		$queryFechaVenc		= null;
		$queryFechaPago		= null;
		$queryPeriodo 		= null;

		if( $this->item != '' ){

			$queryItem	= ( new Query() )->select( 'ctacte_id' )
				->from( 'ctacte_liq' )
				->where([ 'item_id' => $this->item ]);

		}

		if( $this->objeto_desde != null ){

			$this->objeto_desde = utb::getObjeto( $this->objeto_tipo, $this->objeto_desde );
			$this->objeto_hasta	= utb::getObjeto( $this->objeto_tipo, $this->objeto_hasta );

			$queryObjeto = ( new Query() )->select( 'ctacte_id' )
				->from( LiquidaListado::tableName() )
				->filterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ]);
		}

		if( $this->contribuyente_desde != null ){

			$this->contribuyente_desde	= utb::getObjeto( 3, $this->contribuyente_desde );
			$this->contribuyente_hasta	= utb::getObjeto( 3, $this->contribuyente_hasta );

			$queryContribuyente = ( new Query() )->select( 'ctacte_id' )
				->from( LiquidaListado::tableName() )
				->filterWhere([ 'between', 'num', $this->contribuyente_desde, $this->contribuyente_hasta ]);
		}

		if( $this->fecha_emision_desde != null ){

			$queryFechaEmision	= ( new Query() )->select( 'ctacte_id' )
				->from( LiquidaListado::tableName() )
				->where( "fchemi::date BETWEEN '$this->fecha_emision_desde' AND '$this->fecha_emision_hasta'" );

		}

		if( $this->fecha_venc_desde != null ){

			$queryFechaVenc	= ( new Query() )->select( 'ctacte_id' )
				->from( LiquidaListado::tableName() )
				->where( "venc2::date BETWEEN '$this->fecha_venc_desde' AND '$this->fecha_venc_hasta'" );

		}

		if( $this->fecha_pago_desde != null ){

			$queryFechaPago	= ( new Query() )->select( 'ctacte_id' )
				->from( LiquidaListado::tableName() )
				->where( "fchpago::date BETWEEN '$this->fecha_pago_desde' AND '$this->fecha_pago_hasta'" );

		}
		
		if ( intVal($this->anio_desde * 1000 + $this->cuota_desde != 0) ){
		
			$queryPeriodo	= ( new Query() )->select( 'ctacte_id' )
				->from( LiquidaListado::tableName() )
				->where( "anio*1000+cuota BETWEEN $this->anio_desde*1000+$this->cuota_desde AND $this->anio_hasta*1000+$this->cuota_hasta" );
		}

		$sql = [
            'ctacte_id',
            'trib_id',
            'trib_nom_red',
            'obj_id',
            'substr(obj_nom,0,35) as obj_nom',
            'anio',
            'cuota',
            'monto',
            "to_char(venc2, 'dd/MM/yyyy') as venc2",
            'est',
            'est_nom',
            'modif',
		];

		$query	->select( $sql )
				->from( LiquidaListado::tableName() )
				->filterWhere([ 'between', 'ctacte_id', $this->numero_desde, $this->numero_hasta ])
				->andFilterWhere([ 'IN', 'trib_tipo', [ 3, 4 ] ])
                ->andFilterWhere([ 'trib_id' => $this->tributo ])
                ->andFilterWhere([ 'ctacte_id' => $queryItem ])
				->andFilterWhere([ 'ctacte_id' => $queryObjeto ])
				->andFilterWhere([ 'ctacte_id' => $queryContribuyente ])
				->andFilterWhere([ 'ctacte_id' => $queryFechaEmision ])
				->andFilterWhere([ 'ctacte_id' => $queryFechaVenc ])
				->andFilterWhere([ 'ctacte_id' => $queryFechaPago ])
				->andFilterWhere([ 'ctacte_id' => $queryPeriodo ])
				->andFilterWhere([ 'between', 'monto', $this->monto_desde, $this->monto_hasta ])
				->andFilterWhere([ 'est' => $this->estado ])
				->andFilterWhere([ 'expe' => $this->expediente ])
				->andFilterWhere([ 'ilike', 'modif', $this->usuario ])
			;

		 		//->orderBy( 'ctacte_id' );

        return $query;
		
	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'ctacte_id';
	}

    public function sort(){

        return [
            'attributes' => [
                'ctacte_id' => [ SORT_ASC ],
                'obj_id' => [ SORT_ASC ],
            ],
        ];
    }
}

?>
