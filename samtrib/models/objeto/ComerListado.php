<?php
namespace app\models\objeto;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class ComerListado extends Listado{

	//Búsqueda de Objeto
	public $obj_id_desde;
	public $obj_id_hasta;

	//Estado
	public $est;

	//Nombre
	public $nombre;

	//Nombre Titular
	public $nombre_titular;

	//Domicilio Parcelario
	public $dompar_dir;

	//Zona
	public $zona;

	//Legajo
	public $legajo;

	//CUIT
	public $cuit;

	//Ingresos brutos
	public $ib;

	//IVA
	public $iva;

	//Tipo
	public $tipo;

	//Tipo de Liquidación
	public $tipoliq;

	//Contador
	public $contador;

	//Rubro
	public $rubro;

	//Grupo Rubro
	public $rubro_grupo;

	//Distribuidor
	public $distribuidor;

	//Tipo Distribución
	public $tipo_distribucion;

	//Rubro vigente
	public $rubro_vigente;

	//Fecha de Habilitación
	public $fecha_habilitacion_desde;
	public $fecha_habilitacion_hasta;

	//Fecha de vencimiento de habilitación
	public $fecha_venc_habilitacion_desde;
	public $fecha_venc_habilitacion_hasta;

	//Fecha Alta
	public $fecha_alta_desde;
	public $fecha_alta_hasta;

	//Fecha Baja
	public $fecha_baja_desde;
	public $fecha_baja_hasta;

	//Promo industrial
	public $promo_industrial;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_comer';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'obj_id_desde', 'obj_id_hasta', 'nombre', 'nombre_titular', 'dompar_dir', 'est', 'legajo', 'cuit', 'ib', 'tipoliq',
				'rubro', 'rubro_grupo', 'fecha_habilitacion_desde', 'fecha_habilitacion_hasta', 'fecha_venc_habilitacion_desde',
				'fecha_venc_habilitacion_hasta', 'fecha_alta_desde', 'fecha_alta_hasta', 'fecha_baja_desde',
				'fecha_baja_hasta'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'zona', 'iva', 'tipo', 'contador', 'distrib', 'tipo_distribucion', 'promo_industrial'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'obj_id_desde', 'obj_id_hasta', 'est', 'nombre', 'nombre_titular', 'dompar_dir', 'zona', 'legajo', 'cuit', 'ib',
				'iva', 'tipo', 'tipoliq', 'contador', 'rubro', 'rubro_grupo', 'distrib', 'tipo_distribucion',
				'fecha_habilitacion_desde', 'fecha_habilitacion_hasta', 'fecha_venc_habilitacion_desde',
				'fecha_venc_habilitacion_hasta', 'fecha_alta_desde', 'fecha_alta_hasta', 'fecha_baja_desde',
				'fecha_baja_hasta', 'promo_industrial'
			]
		];
	}

	public function attributeLabels(){

		return [

			'obj_id_desde' 		=> 'Nº de objeto desde',
			'obj_id_hasta' 		=> 'Nº de objeto hasta',
			'est'				=> 'Estado',
			'nombre'			=> 'Nombre de fantasía',
			'nombre_titular'	=> 'Nombre del titular',
			'dompar_dir'		=> 'Domicilio Parcelario',
			'zona'				=> 'Zona',
			'legajo'			=> 'Legajo',
			'cuit'				=> 'CUIT',
			'ib'				=> 'Ingresos Brutos',
			'contador'			=> 'Contador',


		];
	}

	public function beforeValidate(){

		//Obtener el número de Objeto
		if( $this->obj_id_desde != '' && $this->obj_id_hasta != '' ){

			$this->obj_id_desde = utb::getObjeto( 2, $this->obj_id_desde );
			$this->obj_id_hasta = utb::getObjeto( 2, $this->obj_id_hasta );
		}

		return true;
	}

	public function buscar(){

		$query						= new Query();
		$queryRubrog 				= new Query();
		$queryRubro					= null;
		$queryGrupoRubro			= null;

		if( $this->rubro != null ){

			$queryRubro = ( new Query() )->select( 'obj_id' )
				->from( 'objeto_rubro' )
				->filterWhere([ 'rubro_id' => $this->rubro ]);

		}

		if( $this->rubro_grupo != null ){

			$queryRubrog = ( new Query() )->select( 'rubro_id' )
				->from( 'rubro' )
				->filterWhere([ 'grupo' => $this->rubro_grupo ]);

			$queryGrupoRubro	= new Query();

			$queryGrupoRubro->select( 'obj_id' )
				->from( 'objeto_rubro' )
				->filterWhere([ 'in', 'rubro_id', $queryRubrog ]);

		}

		$sql = [
			'obj_id',
			'LEFT( nombre, 25 ) nombre_redu',
			'num_nom',
			'cuit',
			'ib',
			'legajo',
			'dompar_dir',
			'dompos_dir',
			'tel',
			'rubro_nom',
			'fchhab',
			'fchvenchab',
			'fchalta',
			'fchbaja',
			'obs',
			'est_nom'
		];

		$query	->select( $sql )
				->from( ComerListado::tableName() )
				->filterWhere(['between', 'obj_id', $this->obj_id_desde, $this->obj_id_hasta])
				->andFilterWhere([ 'est' => $this->est ])
				->andFilterWhere([ 'ilike', 'nombre', $this->nombre ])
				->andFilterWhere([ 'ilike', 'num_nom', $this->nombre_titular ])
				->andFilterWhere([ 'ilike', 'dompar_dir', $this->dompar_dir ])
				->andFilterWhere([ 'zona' => $this->zona ])
				->andfilterWhere([ 'ilike', 'legajo', $this->legajo ])
				->andfilterWhere([ 'ilike', 'cuit', str_replace( '-', '', $this->cuit ) ])
				->andfilterWhere([ 'ilike', 'ib', $this->ib ])
				->andfilterWhere([ 'iva' => $this->iva ])
				->andFilterWhere([ 'tipo' => $this->tipo ])
				->andFilterWhere([ 'tipoliq' => $this->tipoliq ])
				->andfilterWhere([ 'contador' => $this->contador ])
				->andFilterWhere([ 'in', 'obj_id', $queryRubro ])
				->andFilterWhere([ 'in', 'obj_id', $queryGrupoRubro ])
				->andFilterWhere([ 'distrib' => $this->distrib ])
				->andFilterWhere([ 'tdistrib' => $this->tipo_distribucion ])
				->andfilterWhere(['between', 'fchhab', $this->fecha_habilitacion_desde, $this->fecha_habilitacion_hasta])
				->andfilterWhere(['between', 'fchvenchab', $this->fecha_venc_habilitacion_desde, $this->fecha_venc_habilitacion_hasta])
				->andfilterWhere(['between', 'fchalta', $this->fecha_alta_desde, $this->fecha_alta_hasta])
				->andfilterWhere(['between', 'fchbaja', $this->fecha_baja_desde, $this->fecha_baja_hasta])
				->andFilterWhere([ 'pi' => $this->promo_industrial ]);

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
						'num_nom',
						'legajo',
						'cuit',
						'ib',
						'dompar_dir',
						'supcub',
						'est_nom'
					],
					'defaultOrder' => [
						'obj_id' => SORT_ASC,
					]
				];
	}

	public function titulo(){
		return "Listado de Actividad Económica";
	}

	public function permiso(){
		return 3200;
	}
}
?>
