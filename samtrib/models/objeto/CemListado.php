<?php
namespace app\models\objeto;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;

class CemListado extends Listado{

	//Búsqueda de Objeto
	public $obj_id_desde;
	public $obj_id_hasta;

	//Estado
	public $est;
	
	//Codigo Anterior
	public $cod_ant;

	//Nombre Titular
	public $nombre_titular;

	//Delegacion
	public $delegacion;

	//Categoria
	public $categoria;

	//Exenta
	public $exenta;

	//Distribuidor
	public $distribuidor;

	//Fecha Ingreso 
	public $fchingreso_desde;
	public $fchingreso_hasta;

	//Fecha Vencimiento
	public $fchvenc_desde;
	public $fchvenc_hasta;

	//Fecha Modificacion
	public $fchmodif_desde;
	public $fchmodif_hasta;
	
	//Tipo 
	public $tipo;
	
	//Config de Cuadros
	public $cuadro_id;
	public $cuerpo_id;
	public $piso;
	public $fila;
	public $nume;
	public $bis;
	
	public $fall_ndoc;
	public $fall_nombre;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_cem';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'obj_id_desde', 'obj_id_hasta', 'nombre_titular', 'est', 'exenta',
				'fchingreso_desde', 'fchingreso_hasta', 'fchvenc_desde', 'fchvenc_hasta',
				'fchmodif_desde', 'fchmodif_hasta', 'cod_ant','tipo','cuadro_id', 'cuerpo_id', 'fall_nombre'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'delegacion', 'categoria', 'distribuidor', 'fall_ndoc', 'piso', 'fila', 'nume', 'bis'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'obj_id_desde', 'obj_id_hasta', 'nombre_titular', 'est', 'exenta',
				'fchingreso_desde', 'fchingreso_hasta', 'fchvenc_desde', 'fchvenc_hasta',
				'fchmodif_desde', 'fchmodif_hasta','delegacion', 'categoria', 'distribuidor', 
				'cod_ant','tipo','cuadro_id', 'cuerpo_id', 'piso', 'fila', 'nume', 'bis', 'fall_ndoc', 'fall_nombre'
			]
		];
	}

	public function attributeLabels(){

		return [

			'obj_id_desde' 		=> 'Nº de objeto desde',
			'obj_id_hasta' 		=> 'Nº de objeto hasta',
			'est'				=> 'Estado',
			'nombre_titular'	=> 'Nombre del titular',
			'fchingreso_desde'  => 'Fecha ingreso desde',
			'fchingreso_hasta'  => 'Fecha ingreso hasta',
			'fchvenc_desde'  	=> 'Fecha vencimiento desde',
			'fchvenc_hasta'  	=> 'Fecha vencimiento hasta',
			'fchmodif_desde'	=> 'Fecha modificación desde',
			'fchmodif_hasta'	=> 'Fecha modificación hasta',
			
			'fall_ndoc'			=> 'Documento Fallecido',
			'fall_nombre'		=> 'Nombre Fallecido'

		];
	}

	public function beforeValidate(){

		//Obtener el número de Objeto
		if( $this->obj_id_desde != '' && $this->obj_id_hasta != '' ){

			$this->obj_id_desde = utb::getObjeto( 4, $this->obj_id_desde );
			$this->obj_id_hasta = utb::getObjeto( 4, $this->obj_id_hasta );
		}

		return true;
	}

	public function buscar(){

		$query	= new Query();
		$queryFallNDoc = null;
		$queryFallNombre = null;

		if ( $this->fall_ndoc != '' ){
		
			$queryFallNDoc = new Query();
			
			$queryFallNDoc 	->select([ 'obj_id' ])
							->from( 'cem_fall' )
							->filterWhere([ 'ndoc' => $this->fall_ndoc ]);
		}
		
		if ( $this->fall_nombre != '' ){
		
			$queryFallNombre = new Query();
			
			$queryFallNombre->select([ 'obj_id' ])
							->from( 'cem_fall' )
							->filterWhere([ 'ilike', 'apenom', $this->fall_nombre ]);
		}

		$sql = [
			'obj_id',
			'cod_ant',
			'nombre',
			'fallecidos',
			'cua_id',
			'cue_id',
			'tipo',
			'piso',
			'fila',
			"nume",
			'cat',
			'fchingreso',
			'fchvenc',
			'est'
		];

		$query	->select( $sql )
				->from( CemListado::tableName() )
				->filterWhere(['between', 'obj_id', $this->obj_id_desde, $this->obj_id_hasta])
				->andFilterWhere([ 'est' => $this->est ])
				->andFilterWhere([ 'cod_ant' => $this->cod_ant ])
				->andFilterWhere([ 'ilike', 'num_nom', $this->nombre_titular ])
				->andFilterWhere([ 'deleg' => $this->delegacion ])
				->andFilterWhere([ 'cat' => $this->categoria ])
				->andFilterWhere([ 'exenta' => $this->exenta ])
				->andFilterWhere([ 'distrib' => $this->distribuidor ])
				->andfilterWhere(['between', 'fchingreso', $this->fchingreso_desde, $this->fchingreso_hasta])
				->andfilterWhere(['between', 'fchvenc', $this->fchvenc_desde, $this->fchvenc_hasta])
				->andfilterWhere(['between', 'fchmod', $this->fchmodif_desde, $this->fchmodif_hasta])
				->andFilterWhere([ 'tipo' => $this->tipo ])
				->andFilterWhere(['cua_id'=> $this->cuadro_id ])
				->andFilterWhere(['cue_id'=> $this->cuerpo_id ])
				->andFilterWhere(['piso'=> $this->piso ])
				->andFilterWhere(['fila'=> $this->fila ])
				->andFilterWhere(['nume'=> $this->nume ])
				->andFilterWhere(['bis'=> $this->bis ])
				->andFilterWhere([ 'in', 'obj_id', $queryFallNDoc ])
				->andFilterWhere([ 'in', 'obj_id', $queryFallNombre ])
				;

		return $query;
	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'obj_id';
	}
	
	public function sort(){
		return [
					'attributes' => [
						'obj_id',
						'cod_ant',
						'nombre',
						'cua_id',
						'cue_id',
						'tipo',
						'piso',
						'fila',
						"nume",
						'cat',
						'fchingreso',
						'fchvenc',
						'est'
					],
					'defaultOrder' => [
						'obj_id' => SORT_ASC,
					]
				];
	}
	
	public function titulo(){
		return "Listado de Cementario";
	}

	public function permiso(){
		return 3230;
	}
}
?>
