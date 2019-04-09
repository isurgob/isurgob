<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class CompListado extends Listado{

	//Número de referencia
	public $numero_desde;
	public $numero_hasta;

	//Expediente
	public $expediente;

	//Tipo
	public $tipo;

	//Tributo
	public $tributo;

    //Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;



	//Monto
	public $monto_desde;
	public $monto_hasta;

	//Periodo
	public $anio;
	public $cuota;

	//Fecha Límite
	public $fecha_aplica_desde;
	public $fecha_aplica_hasta;

	//Fecha Pago
	public $fecha_baja_desde;
	public $fecha_baja_hasta;

	//Fecha Pago
	public $fecha_alta_desde;
	public $fecha_alta_hasta;

	//Estado
	public $estado;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_comp';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'expediente', 'objeto_desde', 'objeto_hasta', 'objeto_tipo', 'estado', 'fecha_alta_desde',
				'fecha_alta_hasta', 'fecha_aplica_desde', 'fecha_aplica_hasta', 'fecha_baja_desde', 'fecha_baja_hasta',
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'numero_desde', 'numero_hasta', 'tipo', 'tributo',
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'monto_desde', 'monto_hasta',
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [

				'numero_desde', 'numero_hasta', 'expediente', 'tipo', 'tributo', 'objeto_desde', 'objeto_hasta',
				'objeto_tipo', 'estado', 'fecha_alta_desde', 'fecha_alta_hasta', 'fecha_aplica_desde', 'fecha_aplica_hasta',
				'fecha_baja_desde', 'fecha_baja_hasta',
				'monto_desde', 'monto_hasta',

			]
		];
	}

	public function buscar(){

		$query		      	= new Query();
		$queryTributo 		= null;
		$queryObjeto 		= null;
		$queryFechaAlta 	= null;
		$queryFechaAplica	= null;
		$queryFechaBaja 	= null;

		if( $this->tributo != null ){

			$queryTributo = ( new Query() )->select( 'comp_id' )
				->from( CompListado::tableName() )
				->where( "trib_ori = $this->tributo OR trib_dest = $this->tributo" );
		}

		if( $this->objeto_desde != null ){

			$this->objeto_desde = utb::getObjeto( $this->objeto_tipo, $this->objeto_desde );
			$this->objeto_hasta	= utb::getObjeto( $this->objeto_tipo, $this->objeto_hasta );

			$queryObjeto = ( new Query() )->select( 'comp_id' )
				->from( CompListado::tableName() )
				->filterWhere([ 'between', 'obj_ori', $this->objeto_desde, $this->objeto_hasta ])
				->orFilterWhere([ 'between', 'obj_dest', $this->objeto_desde, $this->objeto_hasta ]);
		}

		if( $this->fecha_alta_desde != null ){

			$queryFechaAlta	= ( new Query() )->select( 'comp_id' )
				->from( CompListado::tableName() )
				->where( "fchalta::date BETWEEN '$this->fecha_alta_desde' AND '$this->fecha_alta_hasta'" );

		}

		if( $this->fecha_aplica_desde != null ){

			$queryFechaAplica	= ( new Query() )->select( 'comp_id' )
				->from( CompListado::tableName() )
				->where( "fchaplic::date BETWEEN '$this->fecha_aplica_desde' AND '$this->fecha_aplica_hasta'" );

		}

		if( $this->fecha_baja_desde != null ){

			$queryFechaBaja	= ( new Query() )->select( 'comp_id' )
				->from( CompListado::tableName() )
				->where( "fchbaja::date BETWEEN '$this->fecha_baja_desde' AND '$this->fecha_baja_hasta'" );

		}

		$sql = [

			'comp_id',
			'tipo_nom',
			'trib_ori_nom',
			'obj_ori',
			'trib_dest_nom',
			'obj_dest',
			'monto',
			'monto_aplic',
			'saldo',
			'est_nom',

		];

		$query	->select( $sql )
				->from( CompListado::tableName() )
				->filterWhere([ 'between', 'comp_id', $this->numero_desde, $this->numero_hasta ])
				->filterWhere([ 'expe' => $this->expediente ])
				->filterWhere([ 'tipo' => $this->tipo ])
				->filterWhere([ 'comp_id' => $queryTributo ])
				->filterWhere([ 'comp_id' => $queryObjeto ])
				->filterWhere([ 'est' => $this->estado ])
				->filterWhere([ 'between', 'monto', $this->monto_desde, $this->monto_hasta ])
				->filterWhere([ 'comp_id' => $queryFechaAlta ])
				->filterWhere([ 'comp_id' => $queryFechaAplica ])
				->filterWhere([ 'comp_id' => $queryFechaBaja ]);

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'comp_id';
	}

    public function sort(){

        return [

            'attributes' => [

                'comp_id',
            ],

			'defaultOrder'	=> [
				'comp_id' => SORT_ASC
			],
        ];
    }
}

?>
