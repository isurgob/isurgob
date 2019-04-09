<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class RetencionListado extends Listado{

	//Número de referencia
	public $ret_id_desde;
	public $ret_id_hasta;

	//Objeto
    public $objeto_desde;
    public $objeto_hasta;
	public $objeto_tipo;
	//Cuit
    public $cuit;

	//Lugar
	public $lugar;

	//Periodo
	public $anio;
	Public $mes;
	public $periodo;

	//Fecha
	public $fecha_desde;
	public $fecha_hasta;

	//Base
	public $base_desde;
	public $base_hasta;

	//Monto
	public $monto_desde;
	public $monto_hasta;

	//Agente
	public $agente;

	//Estado
	public $estado;
	
	//Comprobantes
	public $comprobante;
	public $num;
	public $tcomprobante;	

	//Sin Objeto vinculado
	public $sin_objeto_vinculado;
	public $contribuyente;


	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_ret_det';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'objeto_desde', 'objeto_hasta', 'cuit', 'lugar', 'fecha_desde', 'fecha_hasta', 'agente', 'estado', 'sin_objeto_vinculado','contribuyente','comprobante','tcomprobante',
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'ret_id_desde', 'ret_id_hasta', 'anio', 'mes','objeto_tipo','num',
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'base_desde', 'base_hasta', 'monto_desde', 'monto_hasta'
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'objeto_desde', 'objeto_hasta','objeto_tipo', 'cuit', 'lugar', 'fecha_desde', 'fecha_hasta', 'agente', 'estado', 'sin_objeto_vinculado',
				'ret_id_desde', 'ret_id_hasta', 'anio', 'mes',
				'base_desde', 'base_hasta', 'monto_desde', 'monto_hasta','contribuyente','comprobante','tcomprobante','num',
			]
		];
	}

	public function attributeLabels(){

		return [
			'lugar' => 'Lugar',
			'sin_objeto_vinculado'	=> 'Sin Objetos Vinculados',

		];
	}
	public function beforeValidate(){

		//Obtener los números de Objeto
		if( $this->objeto_desde != null ){

			$this->objeto_desde = utb::getObjeto( $this->objeto_tipo, $this->objeto_desde );
			$this->objeto_hasta	= utb::getObjeto( $this->objeto_tipo, $this->objeto_hasta );
		}

		return true;
	}
	public function buscar(){

		$query		      	= new Query();
		$queryObjeto = null;
		$querySinObjeto		= null;

		

		if( $this->anio != null ){
			$this->periodo = $this->anio * 1000 + $this->mes;
		}

		if( $this->sin_objeto_vinculado != null and $this->sin_objeto_vinculado != 0 ){
			$querySinObjeto	= ( new Query() )->select( 'ret_id' )
				->from( 'v_ret_det' )
				->where( "obj_id=''" );

		}

		$sql = [
			'ret_id',
			'retdj_id',
			'ag_rete',
			'cuit',
			'anio',
			'mes',
			'obj_id',
			'numero',
			'comprob',
			'fecha',
			'lugar',
			'base',
			'ali',
			'monto',
			'est',
			
			
		];

		$query	->select( $sql )
				->from( RetencionListado::tableName() )
				->filterWhere([ 'between', 'ret_id', $this->ret_id_desde, $this->ret_id_hasta ])
				->andFilterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ])
				->andFilterWhere([ 'ilike','nombre', $this->contribuyente ])
				->andFilterWhere([ 'ilike', 'cuit', str_replace( '-', '', $this->cuit ) ])
				->andFilterWhere([ 'ilike', 'lugar', str_replace( '-', '', $this->lugar ) ])
				->andFilterWhere([ 'between', 'fecha', $this->fecha_desde, $this->fecha_hasta ])
				->andFilterWhere([ 'between', 'base', $this->base_desde, $this->base_hasta ])
				->andFilterWhere([ 'between', 'monto', $this->monto_desde, $this->monto_hasta ])
				->andFilterWhere([ 'periodo' => $this->periodo ])
				->andFilterWhere([ 'ag_rete' => $this->agente ])
				->andFilterWhere([ 'est' => $this->estado ])
				->andFilterWhere([ 'in', 'ret_id', $querySinObjeto ])
				->andFilterWhere([ 'ilike','comprob', $this->comprobante ])
				->andFilterWhere([ 'numero'=> $this->num ])
				->andFilterWhere([ 'ilike','tcomprob', $this->tcomprobante ])
				;
		return $query;
		return $query->createCommand()->queryAll();
	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'ret_id';
	}

    public function sort(){

        return [

            'attributes' => [

                'ret_id',
				'retdj_id',
				'ag_rete',
				'cuit',
				'anio',
				'mes',
				'obj_id',
				"numero",
				'comprob',
				"fecha",
				"lugar",
				"base",
				"ali",
				"monto",
				"est"
            ],

			'defaultOrder'	=> [
				'ret_id' => SORT_ASC
			],
        ];
    }

    public function titulo(){
		return "Listado de Retenciones";
	}

	public function permiso(){
		return 3500;
	}
}

?>
