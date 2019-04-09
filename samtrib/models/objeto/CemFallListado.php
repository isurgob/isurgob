<?php
namespace app\models\objeto;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;

class CemFallListado extends Listado{

	//Busqueda de Codigo
	public $codigo_desde;
	public $codigo_hasta;
	
	//Búsqueda de Objeto
	public $obj_id_desde;
	public $obj_id_hasta;

	//Estado
	public $est;
	
	//Documento
	public $documento;

	//Nombre Fallecido
	public $nombre_fallecido;
	
	//Nombre Titular
	public $nombre_titular;

	//Nacionalidad
	public $nacionalidad;

	//Estado Civil
	public $estadocivil;

	//Acta Difunción
	public $actadefuncion;

	//Sexo
	public $sexo;

	//Edad 
	public $edad_desde;
	public $edad_hasta;

	//Fecha Nacimiento
	public $fchnac_desde;
	public $fchnac_hasta;

	//Fecha Difunción
	public $fchdefuncion_desde;
	public $fchdefuncion_hasta;
	
	//Fecha Inhumación
	public $fchinh_desde;
	public $fchinh_hasta;
	
	public $empresa_funebre;
	
	public $procedencia;
	
	//Nomeclatura
	public $tipo;
	public $cuadro_id;
	public $cuerpo_id;
	public $piso;
	public $fila;
	public $nume;
	public $bis;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_cem_fall';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'codigo_desde', 'codigo_hasta', 'obj_id_desde', 'obj_id_hasta', 'est', 
				'nombre_fallecido', 'nombre_titular', 'actadefuncion', 'sexo', 'edad_desde', 'edad_hasta',
				'fchnac_desde', 'fchnac_hasta', 'fchdefuncion_desde', 'fchdefuncion_hasta',
				'fchinh_desde', 'fchinh_hasta', 'tipo', 'cuerpo_id', 'cuadro_id'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'documento', 'nacionalidad', 'estadocivil', 'empresa_funebre', 'procedencia',
				'piso', 'fila', 'nume', 'bis'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'codigo_desde', 'codigo_hasta', 'obj_id_desde', 'obj_id_hasta', 'est', 
				'nombre_fallecido', 'nombre_titular', 'actadefuncion', 'sexo', 'edad_desde', 'edad_hasta',
				'fchnac_desde', 'fchnac_hasta', 'fchdefuncion_desde', 'fchdefuncion_hasta',
				'fchinh_desde', 'fchinh_hasta',
				'documento', 'nacionalidad', 'estadocivil',
				'tipo', 'cuerpo_id', 'cuadro_id','piso', 'fila', 'nume', 'bis'
			]
		];
	}

	public function attributeLabels(){

		return [

			'obj_id_desde' 			=> 'Nº de objeto desde',
			'obj_id_hasta' 			=> 'Nº de objeto hasta',
			'est'					=> 'Estado',
			'nombre_titular'		=> 'Nombre del titular',
			'actadefuncion'  		=> 'Acta de Defunción',
			'fchnac_desde'  		=> 'Fecha Nacimiento Desde',
			'fchnac_hasta'  		=> 'Fecha Nacimiento Hasta',
			'fchdefuncion_desde'  	=> 'Fecha Defunción Desde',
			'fchdefuncion_hasta'  	=> 'Fecha Defunción Hasta',
			'fchinh_desde'  		=> 'Fecha Inhumación Desde',
			'fchinh_hasta'  		=> 'Fecha Inhumación Hasta',
			'estadocivil'			=> 'Estado Civil'

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

		$query = new Query();
	
		$sql = [
			'fall_id',
			'obj_id',
			'cua_id',
			'cue_id',
			'tipo_nom',
			'piso',
			'fila',
			"nume",
			'apenom',
			'est',
			'sexo',
			'fchinh'
		];

		$query	->select( $sql )
				->from( CemFallListado::tableName() )
				->filterWhere(['between', 'fall_id', $this->codigo_desde, $this->codigo_hasta])
				->andFilterWhere(['between', 'obj_id', $this->obj_id_desde, $this->obj_id_hasta])
				->andFilterWhere([ 'est' => $this->est ])
				->andFilterWhere([ 'ndoc' => $this->documento ])
				->andFilterWhere([ 'ilike', 'apenom', $this->nombre_fallecido ])
				->andFilterWhere([ 'ilike', 'resp_nom', $this->nombre_titular ])
				->andFilterWhere([ 'nacionalidad' => $this->nacionalidad ])
				->andFilterWhere([ 'estcivil' => $this->estadocivil ])
				->andFilterWhere([ 'actadef' => $this->actadefuncion ])
				->andFilterWhere([ 'sexo' => $this->sexo ])
				->andFilterWhere(['between', 'edad', $this->edad_desde, $this->edad_hasta])
				->andFilterWhere(['between', 'fchnac', $this->fchnac_desde, $this->fchnac_hasta])
				->andFilterWhere(['between', 'fchdef', $this->fchdefuncion_desde, $this->fchdefuncion_hasta])		
				->andFilterWhere(['between', 'fchinh', $this->fchinh_desde, $this->fchinh_hasta])
				->andFilterWhere([ 'emp_funebre' => $this->empresa_funebre ])
				->andFilterWhere([ 'procedencia' => $this->procedencia ])
				->andFilterWhere([ 'cua_id' => $this->cuadro_id ])
				->andFilterWhere([ 'cue_id' => $this->cuerpo_id ])
				->andFilterWhere([ 'tipo' => $this->tipo ])
				->andFilterWhere([ 'fila' => $this->fila ])
				->andFilterWhere([ 'piso' => $this->piso ])
				->andFilterWhere([ 'nume' => $this->nume ])
				->andFilterWhere([ 'bis' => $this->bis ])
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
						'fall_id',
						'obj_id',
						'cua_id',
						'cue_id',
						'tipo_nom',
						'piso',
						'fila',
						"nume",
						'apenom',
						'est',
						'sexo',
						'fchinh'
					],
					'defaultOrder' => [
						'obj_id' => SORT_ASC,
					]
				];
	}
	
	public function titulo(){
		return "Listado de Fallecidos";
	}

	public function permiso(){
		return 3230;
	}
}
?>
