<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class FacilidaListado extends Listado{

	//Número de referencia
	public $numero_desde;
	public $numero_hasta;

	//Tributo
	public $tributo;

    //Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;

	//Estado
	public $estado;

	//Conotribuyente
	public $contribuyente;

	//Monto Deuda
	public $deuda_desde;
	public $deuda_hasta;

	//Fecha Alta
	public $fecha_alta_desde;
	public $fecha_alta_hasta;

	//Fecha Vencimiento
	public $fecha_venc_desde;
	public $fecha_venc_hasta;

	//Fecha Imputación
	public $fecha_imputa_desde;
	public $fecha_imputa_hasta;

	//Fecha Baja
	public $fecha_baja_desde;
	public $fecha_baja_hasta;

	//No se dan de baja automáticamente
	public $baja_automatica;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_facilida';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'objeto_desde', 'objeto_hasta', 'objeto_tipo', 'contribuyente', 'fecha_alta_desde', 'fecha_alta_hasta',
				'fecha_venc_desde', 'fecha_venc_hasta', 'fecha_imputa_desde', 'fecha_imputa_hasta', 'fecha_baja_desde',
				'fecha_baja_hasta',
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'numero_desde', 'numero_hasta', 'tributo', 'estado', 'baja_automatica',
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'deuda_desde', 'deuda_hasta',
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'numero_desde', 'numero_hasta', 'tributo', 'objeto_desde', 'objeto_hasta', 'objeto_tipo', 'estado',
				'contribuyente', 'deuda_desde', 'deuda_hasta', 'fecha_alta_desde', 'fecha_alta_hasta',
				'fecha_venc_desde', 'fecha_venc_hasta', 'fecha_imputa_desde', 'fecha_imputa_hasta', 'fecha_baja_desde',
				'fecha_baja_hasta', 'baja_automatica',
			]
		];
	}

	public function attributeLabels(){

		return [

			'baja_automatica'	=> 'No se dan de baja automáticamente',

		];
	}

	public function buscar(){

		$query		      	= new Query();
		$queryObjeto 		= null;
		$queryFechaAlta		= null;
		$queryFechaVenc 	= null;
		$queryFechaImputa 	= null;
		$queryFechaBaja 	= null;
		$queryBajaAuto		= null;

		if( $this->objeto_desde != null ){

			$this->objeto_desde = utb::getObjeto( $this->objeto_tipo, $this->objeto_desde );
			$this->objeto_hasta	= utb::getObjeto( $this->objeto_tipo, $this->objeto_hasta );

			$queryObjeto = ( new Query() )->select( 'faci_id' )
				->from( 'v_facilida' )
				->filterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ]);
		}

		if( $this->fecha_alta_desde != null ){

			$queryFechaAlta	= ( new Query() )->select( 'faci_id' )
				->from( 'v_facilida' )
				->where( "fchalta::date BETWEEN '$this->fecha_alta_desde' AND '$this->fecha_alta_hasta'" );

		}

		if( $this->fecha_venc_desde != null ){

			$queryFechaVenc	= ( new Query() )->select( 'faci_id' )
				->from( 'v_facilida' )
				->where( "fchvenc::date BETWEEN '$this->fecha_venc_desde' AND '$this->fecha_venc_hasta'" );

		}

		if( $this->fecha_imputa_desde != null ){

			$queryFechaImputa	= ( new Query() )->select( 'faci_id' )
				->from( 'v_facilida' )
				->where( "fchimputa::date BETWEEN '$this->fecha_imputa_desde' AND '$this->fecha_imputa_hasta'" );

		}

		if( $this->fecha_baja_desde != null ){

			$queryFechaBaja	= ( new Query() )->select( 'faci_id' )
				->from( 'v_facilida' )
				->where( "fchbaja::date BETWEEN '$this->fecha_baja_desde' AND '$this->fecha_baja_hasta'" );

		}

		if( $this->baja_automatica > 0 ){

			$queryBajaAuto	= ( new Query() )->select( 'faci_id' )
				->from( 'v_facilida' )
				->where( "baja_auto = 0" );
		}

		$sql = [
			'faci_id',
			'obj_id',
			'trib_nomredu',
			'num_nom',
			'total',
			'est_nom',
			"fchalta",
			"fchvenc",
			"fchimputa",
		];

		$query	->select( $sql )
				->from( FacilidaListado::tableName() )
				->filterWhere([ 'between', 'faci_id', $this->numero_desde, $this->numero_hasta ])
				->andFilterWhere([ 'trib_id' => $this->tributo ])
				->andFilterWhere([ 'faci_id' => $queryObjeto ])
				->andFilterWhere([ 'est' => $this->estado ])
				->andFilterWhere([ 'ilike', 'obj_nom', $this->contribuyente ])
				->andFilterWhere([ 'between', 'total', $this->deuda_desde, $this->deuda_hasta ])
				->andFilterWhere([ 'faci_id' => $queryFechaAlta ])
				->andFilterWhere([ 'faci_id' => $queryFechaVenc ])
				->andFilterWhere([ 'faci_id' => $queryFechaImputa ])
				->andFilterWhere([ 'faci_id' => $queryFechaBaja ])
				->andFilterWhere([ 'faci_id' => $queryBajaAuto ]);

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'faci_id';
	}

    public function sort(){

        return [

            'attributes' => [

                'faci_id',
                'obj_id',
            ],

			'defaultOrder'	=> [
				'faci_id' => SORT_ASC
			],
        ];
    }
}

?>
