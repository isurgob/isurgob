<?php
namespace app\models\objeto;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class InmListado extends Listado{

	//Búsqueda de Objeto
	public $obj_id_desde;
	public $obj_id_hasta;

	//Responsable
	public $responsable;

	//Estado
	public $est;

	//Matrícula
	public $matricula;

	//Partida provincial
	public $part_prov_desde;
	public $part_prov_hasta;

	//Partida de origen
	public $part_origen_desde;
	public $part_origen_hasta;

	//Plano
	public $plano_desde;
	public $plano_hasta;

	//Nomenclatura
	public $s1;
	public $s2;
	public $s3;
	public $manz;
	public $parc;
	public $uf;

	//Nomenclatura Anterior
	public $nc_ant;

	//Nombre
	public $nombre;

	//Comprador
	public $comprador;

	//Domicilios
	public $dom_parcel;
	public $dom_parcel_puerta_desde;
	public $dom_parcel_puerta_hasta;
	public $dom_postal;
	public $dom_postal_puerta_desde;
	public $dom_postal_puerta_hasta;
	public $frente_calle;

	//Búsquedas en listados
	public $regimen;
	public $tipo;
	public $titularidad;
	public $uso;
	public $urb_sub;
	public $barrio;
	public $zona_trib;
	public $zona_val;
	public $zona_op;
	public $distribuidor;
	public $tipo_distribucion;
	public $servicio;
	public $patrimonio;
	public $usuario;

	//Sobre el terreno
	public $sup_terreno_desde;
	public $sup_terreno_hasta;
	public $aval_terreno_desde;
	public $aval_terreno_hasta;
	public $sup_mejora_desde;
	public $sup_mejora_hasta;

	public $fechaAlta_desde;
	public $fechaAlta_hasta;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_inm';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'obj_id_desde', 'obj_id_hasta', 'responsable', 'matricula', 'nombre', 'comprador', 'regimen', 'tipo',
				'titularidad', 'uso', 'urb_sub', 'barrio', 'zona_trib', 'zona_val', 'zona_op', 'distribuidor',
				'tipo_distribucion', 'agua', 'cloaca', 'gas', 'alum', 'pav', 'patrimonio', 'usuario', 'fechaAlta_desde', 'fechaAlta_hasta', 'uf','nc_ant'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[ 'est', 's1', 's2','s3', 'manz', 'parc' ],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'part_prov_desde', 'part_prov_hasta', 'part_origen_desde', 'part_origen_hasta', 'plano_desde', 'plano_hasta',
		 	],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[ 'sup_terreno_desde', 'sup_terreno_hasta', 'aval_terreno_desde', 'aval_terreno_hasta', 'sup_mejora_desde', 'sup_mejora_hasta' ],
			'number',
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'obj_id_desde', 'obj_id_hasta', 'est', 'responsable', 'matricula', 'part_prov_desde', 'part_prov_hasta',
				'part_origen_desde', 'part_origen_hasta', 'plano_desde', 'plano_hasta', 'nombre', 'comprador',
				'regimen', 'tipo', 'titularidad', 'uso', 'urb_sub', 'barrio', 'zona_trib', 'zona_val', 'zona_op',
				'distribuidor', 'tipo_distribucion', 'agua', 'cloaca', 'gas', 'alum', 'pav', 'patrimonio', 'usuario', 'sup_terreno_desde',
				'sup_terreno_hasta', 'aval_terreno_desde', 'aval_terreno_hasta', 'sup_mejora_desde', 'sup_mejora_hasta',
				'fechaAlta_desde', 'fechaAlta_hasta', 's1', 's2','s3', 'manz', 'parc', 'uf','nc_ant'
			]
		];
	}

	public function attributeLabels(){

		return [

			'obj_id_desde' 		=> 'Nº de Objeto desde',
			'obj_id_hasta' 		=> 'Nº de Objeto hasta',
			'est'				=> 'estado',
			'responsable'		=> 'responsable',
			'matricula'			=> 'matrícula',
			'part_prov_desde' 	=> 'Nº de partida provincial desde',
			'part_prov_hasta' 	=> 'Nº de partida provincial hasta',
			'part_origen_desde'	=> 'Nº de partida origen desde',
			'part_origen_hasta'	=> 'Nº de partida origen hasta',
			'plano_desde'		=> 'Nº de plano desde',
			'plano_hasta'		=> 'Nº de plano hasta',
			'nombre'			=> 'nombre',
			'comprador'			=> 'comprador',
			'nc_ant'  			=> 'NC Anterior',
			'agua'				=> 'Agua',
			'cloaca'			=> 'Cloaca',
			'gas'				=> 'Gas',
			'alum'				=> 'Alumbrado',
			'pav'				=> 'Tipo de Pavimento'
		];
	}

	public function beforeValidate(){

		//Obtener el número de Objeto
		if( $this->obj_id_desde != '' && $this->obj_id_hasta != '' ){

			$this->obj_id_desde = utb::getObjeto( 1, $this->obj_id_desde );
			$this->obj_id_hasta = utb::getObjeto( 1, $this->obj_id_hasta );
		}

		//Obtener el responsable
		if( $this->responsable != '' ){

			$this->responsable = utb::getObjeto( 3, $this->responsable );
		}

		return true;
	}

	public function buscar(){

		$query				= new Query();
		$queryResponsable	= new Query();
		$queryZonaOP 		= new Query();
		$queryZonaTrib 		= new Query();
		$queryAgua	 		= new Query();
		$queryCloaca 		= new Query();
		$queryGas	 		= new Query();
		$queryAlum	 		= new Query();
		$queryPav	 		= new Query();

		$queryResponsable->select( 'obj_id' )
			->from( 'objeto_persona' )
			->filterWhere([ 'num' => $this->responsable ]);

		$query	->select( 'obj_id, parp, parporigen, nombre, dompar_dir, nc_guiones, est_nom, regimen, uf,nc_ant' )
				->from(InmListado::tableName())
				->filterWhere(['between', 'obj_id', $this->obj_id_desde, $this->obj_id_hasta])
				->andFilterWhere(['est' => $this->est ])
				->andFilterWhere(['in', 'obj_id', $queryResponsable ])
				->andFilterWhere(['like', 'matric', $this->matricula ])
				->andFilterWhere(['between', 'parp', $this->part_prov_desde, $this->part_prov_hasta ])
				->andFilterWhere(['between', 'parporigen', $this->part_origen_desde, $this->part_origen_hasta ])
				->andFilterWhere(['between', 'plano', $this->plano_desde, $this->plano_hasta ])
				->andFilterWhere([ 'like', 's1', "%$this->s1", false ])
				->andFilterWhere([ 'like', 's2', "%$this->s2", false ])
				->andFilterWhere([ 'like', 's3', "%$this->s3", false ])
				->andFilterWhere([ 'like', 'manz', "%$this->manz", false ])
				->andFilterWhere([ 'like', 'parc', "%$this->parc", false ])
				->andFilterWhere([ 'uf' => $this->uf ])
				->andFilterWhere(['ilike', 'nombre', $this->nombre ])
				->andFilterWhere(['like', 'comprador', $this->comprador ])
				->andFilterWhere(['regimen' => $this->regimen ])
				->andFilterWhere(['tinm' => $this->tipo ])
				->andFilterWhere(['titularidad' => $this->titularidad ])
				->andFilterWhere(['uso' => $this->uso ])
				->andFilterWhere(['urbsub' => $this->urb_sub ])
				->andFilterWhere(['barr_id' => $this->barrio ])
				->andFilterWhere(['zonat' => $this->zona_trib ])
				->andFilterWhere(['zonav' => $this->zona_val ])
				->andFilterWhere(['zonaop' => $this->zona_op ])
				->andFilterWhere(['distrib' => $this->distribuidor ])
				->andFilterWhere(['tdistrib' => $this->tipo_distribucion ])
				->andFilterWhere(['agua' => $this->agua ])
				->andFilterWhere(['cloaca' => $this->cloaca ])
				->andFilterWhere(['gas' => $this->gas ])
				->andFilterWhere(['alum' => $this->alum ])
				->andFilterWhere(['pav' => $this->pav ])
				->andFilterWhere(['regimen' => $this->regimen ])
				->andFilterWhere(['usuario' => $this->usuario ])
				->andFilterWhere(['between', 'avalt', $this->sup_terreno_desde, $this->sup_terreno_hasta ])
				->andFilterWhere(['between', 'supt', $this->aval_terreno_desde, $this->aval_terreno_hasta ])
				->andFilterWhere(['between', 'supm', $this->sup_mejora_desde, $this->sup_mejora_hasta ])
				->andfilterWhere(['between', 'fchalta', $this->fechaAlta_desde, $this->fechaAlta_hasta])
				->andFilterWhere(['nc_ant' => $this->nc_ant ])
		 		->orderBy( 'obj_id' );

		return $query;
		return $query->createCommand()->queryAll();
	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'obj_id';
	}

	public function titulo(){
		return "Listado de Inmuebles";
	}

	public function permiso(){
		return 3070;
	}
}
?>
