<?php
namespace app\models\objeto;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class RodadoListado extends Listado{

	//Búsqueda de Objeto
	public $obj_id_desde;
	public $obj_id_hasta;

	//Estado
	public $est;

	//Nombre
	public $nombre;

	//Nombre Titular
	public $nombre_tit;

	//Tipo de Liquidación
	public $tipoliq;

	//Tipo RNPA
	public $tipoRNPA;

	//Valor aforo
	public $valor_aforo_desde;
	public $valor_aforo_hasta;

	//Marca
	public $marca;

	//Categoría
	public $categoria;

	//Modelo
	public $modelo;

	//Año
	public $anio_desde;
	public $anio_hasta;

	//Dominio
	public $dominio;

	//Dominio anterior
	public $dominio_ant;

	//Número motor
	public $motor;

	//Número chasis
	public $chasis;

	//Cilindrada
	public $cilindrada;

	//Delegación
	public $delegacion;

	//Peso
	public $peso;

	//Color
	public $color;

	//Tipo Combustible
	public $tipo_combustible;

	//Uso
	public $uso;

	//Tipo de Distribución
	public $tipo_distribucion;

	//Conductor
	public $conductor;

	//Fecha Compra
	public $fecha_compra_desde;
	public $fecha_compra_hasta;

	//Vínculo con aforo
	public $vinculo_aforo;

	//Valuación en aforo
	public $valuacion_aforo;
	
	// Tipo de Formulario
	public $tipo_formulario;
	
	//Remitos
	public $remito;
	public $remito_anio;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_rodado';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'obj_id_desde', 'obj_id_hasta', 'nombre', 'num_nom', 'est', 'tipoliq', 'tipoRNPA', 'modelo', 'motor', 'chasis',
				'dominio', 'dominio_ant', 'conductor',

				'fecha_compra_desde',
				'fecha_compra_hasta',
				
				'tipo_formulario',
				'remito',
				'nombre_tit',
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'marca', 'categoria', 'anio_desde', 'anio_hasta', 'cilindrada', 'delegacion', 'tipo_combustible', 'uso',
				'tipo_distribucion', 'vinculo_aforo', 'valuacion_aforo', 'remito_anio'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'valor_aforo_desde', 'valor_aforo_hasta', 'peso',
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'obj_id_desde', 'obj_id_hasta', 'nombre', 'num_nom', 'est',  'tipoliq', 'tipoRNPA',
				'valor_aforo_desde', 'valor_aforo_hasta', 'marca', 'categoria', 'modelo', 'anio_desde',
				'anio_hasta', 'dominio', 'dominio_ant', 'motor', 'chasis', 'cilindrada', 'delegacion',
				'peso', 'tipo_combustible', 'uso', 'tipo_distribucion', 'conductor', 'fecha_compra_desde',
				'fecha_compra_hasta', 'vinculo_aforo', 'valuacion_aforo', 'tipo_formulario', 'remito', 'remito_anio',
				'nombre_tit'
			]
		];
	}

	public function attributeLabels(){

		return [

			'obj_id_desde' 		=> 'Nº de objeto desde',
			'obj_id_hasta' 		=> 'Nº de objeto hasta',
			'est'				=> 'Estado',
			'tipoliq'			=> 'Tipo de Liquidación',
			'vinculo_aforo'		=> 'Sin vínculo con aforo',
			'valuacion_aforo'	=> 'Sin valuación en aforo',
			'nombre_tit'		=> 'Nombre del Titular'

		];
	}

	public function beforeValidate(){

		//Obtener el número de Objeto
		if( $this->obj_id_desde != '' && $this->obj_id_hasta != '' ){

			$this->obj_id_desde = utb::getObjeto( 5, $this->obj_id_desde );
			$this->obj_id_hasta = utb::getObjeto( 5, $this->obj_id_hasta );
		}

		return true;
	}

	public function buscar(){

		$query					= new Query();
		$queryVinculoAforo		= null;
		$queryValuacionAforo	= null;

		

		if( $this->vinculo_aforo > 0 ){

			$queryVinculo = ( new Query() )->select( 'aforo_id' )
				->from( 'rodado_aforo' );

			$queryVinculoAforo	= ( new Query() )->select( 'obj_id' )
				->from( 'v_rodado' )
				->where([ 'not in', 'aforo_id', $queryVinculo ]);

		}

		if( $this->valuacion_aforo > 0 ){

			$sql = "SELECT obj_id FROM rodado r LEFT JOIN rodado_aforo_val v " .
					"on r.aforo_id=v.aforo_id and r.anio=v.anio" .
					" where v.aforo_id is null";

			$queryValuacionAforo = Yii::$app->db->createCommand( $sql )->queryAll();

		}

		$sql = [
			'obj_id',
			'dominio',
			'num_nom',
			'cat_nom',
			'marca_nom',
			'modelo_nom',
			'anio',
			'cilindrada',
			'est_nom'
		];

		$query	->select( $sql )
				->from( RodadoListado::tableName() )
				->filterWhere([ 'between', 'obj_id', $this->obj_id_desde, $this->obj_id_hasta ])
				->andFilterWhere([ 'ilike', 'nombre', $this->nombre ])
				->andFilterWhere([ 'ilike', 'num_nom', $this->nombre_tit ])
				->andFilterWhere([ 'est' => $this->est ])
				->andFilterWhere([ 'tliq' => $this->tipoliq ])
				->andFilterWhere([ 'aforo_tipo_nom' => $this->tipoRNPA ])
				->andFilterWhere([ 'between', 'aforo_valor', $this->valor_aforo_desde, $this->valor_aforo_hasta ])
				->andFilterWhere([ 'marca' => $this->marca ])
				->andFilterWhere([ 'cat' => $this->categoria ])
				->andFilterWhere([ 'ilike', 'modelo_nom', $this->modelo ])
				->andFilterWhere([ 'between', 'anio', $this->anio_desde, $this->anio_hasta])
				->andFilterWhere([ 'dominio' => $this->dominio ])
				->andFilterWhere([ 'dominioant' => $this->dominio_ant ])
				->andFilterWhere([ 'nromotor' => $this->motor ])
				->andFilterWhere([ 'nrochasis' => $this->chasis ])
				->andFilterWhere([ 'cilindrada' => $this->cilindrada ])
				->andFilterWhere([ 'deleg' => $this->delegacion ])
				->andFilterWhere([ 'peso' => $this->peso ])
				->andFilterWhere([ 'combustible' => $this->tipo_combustible ])
				->andFilterWhere([ 'uso' => $this->uso ])
				->andFilterWhere([ 'tdistrib' => $this->tipo_distribucion ])
				->andFilterWhere([ 'ilike', 'conductor_nom', $this->conductor ])
				->andfilterWhere(['between', 'fchcompra', $this->fecha_compra_desde, $this->fecha_compra_hasta])
				->andFilterWhere([ 'tform' => $this->tipo_formulario ])
				->andFilterWhere([ 'ilike', 'remito', $this->remito ])
				->andFilterWhere([ 'remito_anio' => $this->remito_anio ])
				->andFilterWhere([ 'obj_id' => $queryVinculoAforo ])
				->andFilterWhere([ 'obj_id' => $queryValuacionAforo ])

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
}
?>
