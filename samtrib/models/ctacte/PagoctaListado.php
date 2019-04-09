<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class PagoctaListado extends Listado{

	//Número de referencia
	public $numero_desde;
	public $numero_hasta;

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
	public $fecha_limite_desde;
	public $fecha_limite_hasta;

	//Fecha Pago
	public $fecha_pago_desde;
	public $fecha_pago_hasta;

	//Fecha Pago
	public $fecha_alta_desde;
	public $fecha_alta_hasta;

	//Estado
	public $estado;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_ctacte_pagocta';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'objeto_desde', 'objeto_hasta', 'objeto_tipo', 'contribuyente', 'fecha_alta_desde', 'fecha_alta_hasta',
				'fecha_limite_desde', 'fecha_limite_hasta', 'fecha_pago_desde', 'fecha_pago_hasta', 'estado',
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'numero_desde', 'numero_hasta', 'tributo', 'baja_automatica',
				'anio', 'cuota',
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

				'numero_desde', 'numero_hasta', 'tributo', 'objeto_desde', 'objeto_hasta', 'objeto_tipo', 'estado',
				'monto_desde', 'monto_hasta', 'anio', 'cuota', 'fecha_limite_desde', 'fecha_limite_hasta',
				'fecha_pago_desde', 'fecha_pago_hasta', 'fecha_alta_desde', 'fecha_alta_hasta'

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
		$queryPeriodo 		= null;
		
		if( $this->objeto_desde != null ){

			$this->objeto_desde = utb::getObjeto( $this->objeto_tipo, $this->objeto_desde );
			$this->objeto_hasta	= utb::getObjeto( $this->objeto_tipo, $this->objeto_hasta );

			$queryObjeto = ( new Query() )->select( 'pago_id' )
				->from( PagoctaListado::tableName() )
				->filterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ]);
		}

		if( $this->anio != null ){

			$periodo = $this->anio * 1000 + $this->cuota;

			$queryPeriodo = ( new Query() )->select( 'pago_id' )
				->from( PagoctaListado::tableName() )
				->where( "anio*1000+cuota = $periodo" );
		}

		$sql = [

			'pago_id',
			'trib_nom',
			'obj_id',
			'substr(obj_nom,0,35) as obj_nom',
			'subcta',
			'anio',
			'cuota',
			'monto',
			'fchlimite',
			'fchpago',
			'est'

		];

		$query	->select( $sql )
				->from( PagoctaListado::tableName() )
				->filterWhere([ 'between', 'pago_id', $this->numero_desde, $this->numero_hasta ])
				->andFilterWhere([ 'pago_id' => $queryObjeto ])
				->andFilterWhere([ 'between', 'monto', $this->monto_desde, $this->monto_hasta ])
				->andFilterWhere([ 'trib_id' => $this->tributo ])
				->andFilterWhere([ 'pago_id' => $queryPeriodo ])
				->andFilterWhere([ 'between', 'fchlimite', $this->fecha_limite_desde, $this->fecha_limite_hasta ])
				->andFilterWhere([ 'between', 'fchpago', $this->fecha_pago_desde, $this->fecha_pago_hasta ])
				->andFilterWhere([ 'between', 'fchmod', $this->fecha_alta_desde, $this->fecha_alta_hasta ])
				->andFilterWhere([ 'est' => $this->estado ]);

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'pago_id';
	}

    public function sort(){

        return [

            'attributes' => [

                'pago_id',
                'obj_id',
            ],

			'defaultOrder'	=> [
				'pago_id' => SORT_ASC
			],
        ];
    }
}

?>
