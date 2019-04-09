<?php
namespace app\models\caja;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class CajaTicketListado extends Listado{

	//Número de ticket
	public $ticket_desde;
	public $ticket_hasta;

	//Número de operación
	public $operacion_desde;
	public $operacion_hasta;

	//Fecha
	public $fecha_desde;
	public $fecha_hasta;

	//Caja
	public $caja;

	//Tributo
	public $tributo;

	//Objeto
	public $objeto_desde;
	public $objeto_hasta;
	public $objeto_tipo;

	//Contribuyente
	public $contribuyente;

	//Monto
	public $monto_desde;
	public $monto_hasta;

	//Estado
	public $estado;

	//Tesorería
	public $tesoreria;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_caja_ticket';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'fecha_desde', 'fecha_hasta', 'objeto_desde', 'objeto_hasta', 'objeto_tipo', 'contribuyente', 'estado',
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'ticket_desde', 'ticket_hasta', 'operacion_desde', 'operacion_hasta', 'caja', 'tributo', 'tesoreria',
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

				'ticket_desde', 'ticket_hasta', 'operacion_desde', 'operacion_hasta', 'fecha_desde', 'fecha_hasta',
				'caja', 'tributo', 'objeto_desde', 'objeto_hasta', 'objeto_tipo', 'contribuyente', 'monto_desde',
				'monto_hasta', 'estado', 'tesoreria',

			]
		];
	}

	public function buscar(){

		$query		      	= new Query();
		$queryFecha			= null;
		$queryObjeto 		= null;

		if( $this->fecha_desde != null ){

			$queryFecha	= ( new Query() )->select( 'ticket' )
				->from( CajaTicketListado::tableName() )
				->where( "fecha::date BETWEEN '$this->fecha_desde' AND '$this->fecha_hasta'" );

		}

		if( $this->objeto_desde != null ){

			$this->objeto_desde = utb::getObjeto( $this->objeto_tipo, $this->objeto_desde );
			$this->objeto_hasta	= utb::getObjeto( $this->objeto_tipo, $this->objeto_hasta );

			$queryObjeto = ( new Query() )->select( 'ticket' )
				->from( CajaTicketListado::tableName() )
				->filterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ]);
		}


		$sql = [

			'ticket',
			'opera',
			'fecha',
			'monto',
			'caja_id',
			'trib_nom',
			'obj_id',
			'substr(obj_nom,0,35) as obj_nom',
			'subcta',
			'anio',
			'cuota',
			'num',
			'est',
			'mdps'

		];

		$query	->select( $sql )
				->from( CajaTicketListado::tableName() )
				->filterWhere([ 'between', 'ticket', $this->ticket_desde, $this->ticket_hasta ])
				->filterWhere([ 'between', 'opera', $this->operacion_desde, $this->operacion_hasta ])
				->filterWhere([ 'ticket' => $queryFecha ])
				->filterWhere([ 'caja_id' => $this->caja ])
				->filterWhere([ 'trib_id' => $this->tributo ])
				->filterWhere([ 'ticket' => $queryObjeto ])
				->filterWhere([ 'ilike', 'num_nom', $this->contribuyente ])
				->filterWhere([ 'between', 'monto', $this->monto_desde, $this->monto_hasta ])
				->filterWhere([ 'est' => $this->estado ])
				->filterWhere([ 'teso_id' => $this->tesoreria ]);

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'ticket';
	}

    public function sort(){

        return [

            'attributes' => [

                'ticket',
				'opera',
				'fecha',
				'monto',

            ],

			'defaultOrder'	=> [
				'ticket' => SORT_ASC
			],
        ];
    }
}

?>
