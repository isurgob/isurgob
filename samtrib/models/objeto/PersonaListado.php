<?php
namespace app\models\objeto;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class PersonaListado extends Listado{

	//Búsqueda de Objeto
	public $obj_id_desde;
	public $obj_id_hasta;

	//Nombre
	public $nombre;

	//Estado
	public $est;

	//Estado IB
	public $est_ib;

	//CUIT
	public $cuit;

	//Ingresos Brutos
	public $ib;

	//Tipo de Liquidación
	public $tipoliq;

	//Contador
	public $contador;

	//Grupo Rubro
	public $rubro_grupo;

	//Teléfono
	public $telefono;

	//Calle
	public $calle;

	//Número de inscripción
	public $inscrip;

	//Número de documento
	public $ndoc;

	public $nacionalidad;
	public $estadoCivil;
	public $iva;
	public $clasificacion;
	public $fecha_nacimiento_desde;
	public $fecha_nacimiento_hasta;
	public $fecha_alta_desde;
	public $fecha_alta_hasta;
	public $fecha_modif_desde;
	public $fecha_modif_hasta;
	public $fecha_baja_desde;
	public $fecha_baja_hasta;
	public $fecha_alta_ib_desde;
	public $fecha_alta_ib_hasta;
	public $fecha_baja_ib_desde;
	public $fecha_baja_ib_hasta;

	public $imponibles_desde;
	public $imponibles_hasta;
	public $imponibles_tipo_objeto;

	public $nombre_fantasia;
	public $tbaja_ib;

	//Retenciones
	public $agrete;
	public $rete_manual;
	
	

	public $tipo;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_persona';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'obj_id_desde', 'obj_id_hasta', 'nombre', 'est', 'est_ib', 'cuit', 'ib', 'tipoliq', 'contador', 'rubro_grupo',
				'telefono', 'calle', 'fecha_nacimiento_desde', 'fecha_nacimiento_hasta', 'fecha_alta_desde',
				'fecha_alta_hasta', 'fecha_modif_desde', 'fecha_modif_hasta', 'fecha_baja_desde', 'fecha_baja_hasta',
				'fecha_alta_ib_desde', 'fecha_alta_ib_hasta', 'fecha_baja_ib_desde', 'fecha_baja_ib_hasta', 'nombre_fantasia', 'tbaja_ib'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'inscrip', 'ndoc', 'nacionalidad', 'estadoCivil', 'iva', 'clasificacion',
				'imponibles_desde', 'imponibles_hasta', 'imponibles_tipo_objeto', 'agrete','tipo','rete_manual'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'obj_id_desde', 'obj_id_hasta', 'nombre', 'est','est_ib', 'cuit', 'ib', 'tipoliq', 'contador', 'rubro_grupo',
				'telefono', 'calle', 'inscrip', 'ndoc', 'nacionalidad', 'estadoCivil', 'iva', 'clasificacion',
				'fecha_nacimiento_desde', 'fecha_nacimiento_hasta', 'fecha_alta_desde', 'fecha_alta_hasta',
				'fecha_modif_desde', 'fecha_modif_hasta', 'fecha_baja_desde', 'fecha_baja_hasta', 'fecha_alta_ib_desde',
				'fecha_alta_ib_hasta', 'fecha_baja_ib_desde', 'fecha_baja_ib_hasta', 'imponibles_desde', 'imponibles_hasta',
				'imponibles_tipo_objeto', 'agrete', 'nombre_fantasia', 'tbaja_ib','tipo','rete_manual'
			]
		];
	}

	public function attributeLabels(){

		return [

			'obj_id_desde' 		=> 'Nº de objeto desde',
			'obj_id_hasta' 		=> 'Nº de objeto hasta',
			'est'				=> 'Estado',
			'est_ib'			=> 'Estado IB',
			'cuit'				=> 'CUIT',
			'ib'				=> 'Ingresos Brutos',
			'tipoliq'			=> 'Tipo de Liquidación',
			'contador'			=> 'Contador',
			'rubro_grupo'		=> 'Grupo de rubro',
			'telefono'			=> 'Teléfono',
			'calle'				=> 'Calle',
			'inscrip'			=> 'Número de inscripción',
			'ndoc'				=> 'Número de documento',
			'agrete'			=> 'Agente de retención',
			'nombre_fantasia'   => 'Nombre de Fantasía',
			'tbaja_ib'          => 'Tipo Baja IB',
			'rete_manual'	=> 'Con Retención Manual Habilitada'

		];
	}

	public function beforeValidate(){

		//Obtener el número de Objeto
		if( $this->obj_id_desde != '' && $this->obj_id_hasta != '' ){

			$this->obj_id_desde = utb::getObjeto( 3, $this->obj_id_desde );
			$this->obj_id_hasta = utb::getObjeto( 3, $this->obj_id_hasta );
		}

		return true;
	}

	public function buscar(){

		$query					= new Query();
		$queryRubro 			= new Query();
		$queryGrupoRubro		= null;
		$queryImponibles		= null;
		$queryAgenteRetencion	= null;
		$queryAgenteRetencionM	= null;

		if( $this->rubro_grupo != '' ){

			$queryRubro->select( 'rubro_id' )
				->from( 'rubro' )
				->filterWhere([ 'grupo' => $this->rubro_grupo ]);

			$queryGrupoRubro	= new Query();

			$queryGrupoRubro->select( 'obj_id' )
				->from( 'objeto_rubro' )
				->filterWhere([ 'in', 'rubro_id', $queryRubro ]);
		}

		if( $this->imponibles_desde != '' ){

			$queryImponibles	= new Query();

			$sql = 	"select op.num from objeto o inner join objeto_persona op on o.obj_id=op.obj_id " .
					"where o.est='A' and o.tobj= $this->imponibles_tipo_objeto group by op.num having " .
					"count(*) BETWEEN $this->imponibles_desde AND $this->imponibles_hasta";

			$queryImponibles = Yii::$app->db->createCommand( $sql )->queryAll();
		}

		if( $this->agrete > 0 ){

			$queryAgenteRetencion	= new Query();

			$queryAgenteRetencion->select( 'obj_id' )
				->from( 'v_persona' )
				->where( "(ag_rete IS NOT NULL And length(ag_rete) > 0)" );
		}
		
		if( $this->rete_manual > 0 ){

			$queryAgenteRetencionM	= new Query();

			$queryAgenteRetencionM->select( 'obj_id' )
				->from( 'v_persona' )
				->where( "ag_rete_manual=1" );
		}

		$sql = [
			'obj_id',
			'LEFT( nombre, 25 ) nombre',
			'est_nom',
			'est_ib',
			"CASE WHEN cuit IS NULL OR trim(both ' ' from cuit) = '' THEN ndoc::text ELSE (substr(cuit, 1, 2) || '-' || substr(cuit, 3, 8) || '-' || substr(cuit, 11, 1)) END as documento",
			"fchnac",
			'LEFT( dompos_dir, 40 ) dompos_dir',
			'nacionalidad_nom',
		];

		$query	->select( $sql )
				->from( PersonaListado::tableName() )
				->filterWhere(['between', 'obj_id', $this->obj_id_desde, $this->obj_id_hasta])
				->andFilterWhere([ 'ilike', 'nombre', $this->nombre ])
				->andFilterWhere([ 'est' => $this->est ])
				->andFilterWhere([ 'est_ib' => $this->est_ib ])
				->andFilterWhere([ 'ilike', 'cuit', str_replace( '-', '', $this->cuit ) ])
				->andFilterWhere([ 'ilike', 'ib', $this->ib ])
				->andFilterWhere([ 'tipoliq' => $this->tipoliq ])
				->andFilterWhere([ 'contador' => $this->contador ])
				->andFilterWhere([ 'in', 'obj_id', $queryGrupoRubro ])
				->andFilterWhere([ 'ilike', 'tel', $this->telefono, false ])
				->andFilterWhere([ 'ilike', 'domleg_dir', $this->calle ])
				->andFilterWhere([ 'inscrip' => $this->inscrip ])
				->andFilterWhere([ 'ndoc' => $this->ndoc ])
				->andFilterWhere([ 'nacionalidad' => $this->nacionalidad ])
				->andFilterWhere([ 'estcivil' => $this->estadoCivil ])
				->andFilterWhere([ 'iva' => $this->iva ])
				->andFilterWhere([ 'tipo' => $this->tipo ])
				->andFilterWhere([ 'clasif' => $this->clasificacion ])
				->andfilterWhere(['between', 'fchnac', $this->fecha_nacimiento_desde, $this->fecha_nacimiento_hasta])
				->andfilterWhere(['between', 'fchalta', $this->fecha_alta_desde, $this->fecha_alta_hasta])
				->andfilterWhere(['between', 'fchmodif', $this->fecha_modif_desde, $this->fecha_modif_hasta])
				->andfilterWhere(['between', 'fchbaja', $this->fecha_baja_desde, $this->fecha_baja_hasta])
				->andfilterWhere(['between', 'fchalta_ib', $this->fecha_alta_ib_desde, $this->fecha_alta_ib_hasta])
				->andfilterWhere(['between', 'fchbaja_ib', $this->fecha_baja_ib_desde, $this->fecha_baja_ib_hasta])
				->andFilterWhere([ 'num' => $queryImponibles ])
				->andFilterWhere([ 'in', 'obj_id', $queryAgenteRetencion ])
				->andFilterWhere([ 'in', 'obj_id',$queryAgenteRetencionM ])
				->andFilterWhere([ 'ilike', 'nombre_fantasia', $this->nombre_fantasia ])
				->andFilterWhere([ 'tbaja_ib' => $this->tbaja_ib ]);

		 		//->orderBy( 'obj_id' );

		return $query;
		return $query->createCommand()->queryAll();
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
						'nombre',
						'est_nom',
						'documento',
						'dompos_dir',
						'fchnac',
						'nacionalidad_nom'
					],
					'defaultOrder' => [
						'obj_id' => SORT_ASC,
					]
				];
	}

	public function titulo(){
		return "Listado de Personas";
	}

	public function permiso(){
		return 3220;
	}
}
?>
