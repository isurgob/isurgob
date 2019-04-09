<?php

namespace app\models\objeto;

use Yii;

use yii\data\ArrayDataProvider;
use yii\validators\EachValidator;
use yii\helpers\ArrayHelper;

use app\utils\db\utb;
use app\utils\db\Fecha;

class RodadoAforo extends \yii\db\ActiveRecord
{

	public $anioSeleccionado;	//Variable que contendrá el año que se desea buscar
	public $valores;
	public $existenRodados;

	public function __construct(){

		parent::__construct();

		$this->valores = array_fill(0, 20, 0);
		$this->existenRodados = false;
		$this->anioSeleccionado = date( 'Y' );

	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_rodado_aforo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $ret = [];

		/**
		 * CAMPOS REQUERIDOS
		 */
		$ret[] = [
				['valores', 'origen', 'tvehic', 'marca_nom', 'tipo_nom', 'modelo_nom', 'anioSeleccionado'],
				'required',
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'aforo_id',
				'required',
				'on' => ['insert', 'update', 'delete']
				];

		$ret[] = [
				'fabr',
				'required',
				'when' => function($model){return strlen($model->aforo_id) === 8;},
				'on' => 'insert'
				];

		$ret[] = [
				'tipo',
				'required',
				'when' => function($model){return strlen($model->aforo_id) === 7;},
				'on' => 'insert'
				];
		/**
		 * FIN CAMPOS REQUERIDOS
		 */

		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[] = [
				'valores',
				'validarValores',
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'aforo_id',
				'string',
				'max' => 8,
				'on' => ['update', 'delete']
				];

		$ret[] = [
				['origen', 'tvehic'],
				'string',
				'max' => 1,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'fabr',
				'string',
				'max' => 3,
				'min' => 3,
				'on' => ['insert']
				];

		$ret[] = [
				'tipo',
				'string',
				'min' => 2,
				'max' => 2,
				'on' => ['insert']
				];

		$ret[] = [
				'marca_nom',
				'string',
				'max' => 25,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'tipo_nom',
				'string',
				'max' => 35,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'modelo_nom',
				'string',
				'max' => 50,
				'on' => ['insert', 'update']
				];
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

        /**
         * VALORES POR DEFECTO
         */

        /**
         * FIN VALORES POR DEFECTO
         */

        /**
         * VARIOS
         */
		$ret[] = [
				'aforo_id',
				'existeAforo',
				'on' => 'insert'
				];

        return $ret;
    }

	public function scenarios(){
		return [
				'insert' => ['aforo_id', 'valores', 'anioSeleccionado', 'marca', 'tipo', 'modelo', 'origen', 'fabr', 'tvehic', 'marca_nom', 'tipo_nom', 'modelo_nom'],
				'update' => ['aforo_id', 'valores', 'anioSeleccionado', 'marca_nom', 'tipo_nom', 'modelo_nom', 'origen', 'tvehic'],
				'delete' => ['aforo_id']
			];
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

			'aforo_id' => 'ID',
			'fabr' => 'Fábrica',
			'marca' => 'Marca',
			'tipo' => 'Tipo',
			'modelo' => 'Modelo',
			'marca_nom' => 'Nombre de la marca',
			'tipo_nom' => 'Nombre del tipo',
			'modelo_nom' => 'Nombre del modelo',
			'origen' => 'Origen',
			'tvehic' => 'Tipo de vehiculo'
        ];
    }

    public function beforeValidate(){


    	return true;
    }

    public function afterValidate(){

    	if(!$this->aforo_id !== null){

    		if(strlen($this->aforo_id) === 7){

    			$this->fabr = substr($this->aforo_id, 0, 3);
    			$this->marca = substr($this->aforo_id, 3, 2);
    			$this->modelo = substr(5, 2);


    		} else if(strlen($this->aforo_id) === 8){

    			$this->marca = substr($this->aforo_id, 0, 3);
    			$this->tipo = substr($this->aforo_id, 3, 2);
    			$this->modelo = substr($this->aforo_id, 5, 3);

    		} else $this->addError($this->aforo_id, 'El código de aforo es incorrecto');
    	}
    }

    public function afterFind(){

    	$this->obtenerValuaciones( $this->anioSeleccionado );

		/*
		 * se termina de obtener los valores del aforo
		 */

		/*
		 * se determina si existen rodados que tienen asociado el aforo
		*/
		$sql = "Select Exists (Select 1 From rodado Where aforo_id = '$this->aforo_id')";
		$this->existenRodados = Yii::$app->db->createCommand($sql)->queryScalar();
    }

	public function obtenerValuaciones( $anio ){

		/*
    	 * Se obtienen los valores del aforo
    	 */
    	$sql = "Select anio, valor From rodado_aforo_val Where aforo_id = '$this->aforo_id' and anioval = $anio Order By anioval Desc Limit 20";

    	$vals = Yii::$app->db->createCommand($sql)->queryAll();
    	$valores = [];
		$anioaux = $anio;

    	for ($i=0; $i<20; $i++){
			$valores[] = ['anio' => $anioaux, 'valor' => 0];
			$anioaux = $anioaux - 1;
		}
		
		foreach ($vals as $val){
			$key = array_search($val['anio'], array_column($valores, 'anio'));
			$valores[$key]['valor'] = $val['valor'];
		
		}
		
		$this->valores = $valores;
	}

    public function grabar(){

    	$scenario = $this->isNewRecord ? 'insert' : 'update';
    	$this->setScenario($scenario);

    	if(!$this->validate()) return false;

    	$anio = intval(date('Y'));
    	$indice = 0;

    	$trans = Yii::$app->db->beginTransaction();

    	if($this->isNewRecord){

    		$sql = "Insert Into rodado_aforo(aforo_id, origen, fabr, marca, tipo, modelo, tvehic, marca_nom, tipo_nom, modelo_nom, fchmod, usrmod)" .
    			" Values(upper('$this->aforo_id'), upper('$this->origen'), upper('$this->fabr'), upper('$this->marca'), upper('$this->tipo')," .
    			"upper('$this->modelo'), upper('$this->tvehic'), upper('$this->marca_nom'), upper('$this->tipo_nom'), upper('$this->modelo_nom')" .
    			", current_timestamp, " . Yii::$app->user->id . ")";

    		$res = Yii::$app->db->createCommand($sql)->execute();

    		if(!$res){

    			$this->addError($this->aforo_id, 'Ocurrió un error al intentar grabar el registro');
    			$trans->rollBack();
    			return false;
    		}

    		//se insertan los valores de los años
    		foreach( $this->valores as $v ){

    			$sql = 	"Insert Into rodado_aforo_val (aforo_id, anio, anioval, valor, fchmod, usrmod) Values(" .
						"'$this->aforo_id', " . $v['anio'] . "," . $this->anioSeleccionado . "," . $v['valor'] . " , current_timestamp, " . Yii::$app->user->id . ")";
    			$res = Yii::$app->db->createCommand($sql)->execute();

    			if(!$res){

    				$this->addErrors($this->valores, 'Ocurrió un error al intentar guardar el valor para el año ' . $v['anio']);
    				$trans->rollBack();
    				return false;
    			}

    			$indice++;
    		}

    	} else{

    		//se eliminan los valores actuales
    		$sql = "Delete From rodado_aforo_val Where aforo_id = '$this->aforo_id' AND anioval = $this->anioSeleccionado";
    		Yii::$app->db->createCommand($sql)->execute();

			//se insertan los valores de los años
    		foreach( $this->valores as $v ){

    			$sql = 	"Insert Into rodado_aforo_val (aforo_id, anio, anioval, valor, fchmod, usrmod) Values(" .
						"'$this->aforo_id', " . $v['anio'] . "," . $this->anioSeleccionado . "," . $v['valor'] . " , current_timestamp, " . Yii::$app->user->id . ")";
    			$res = Yii::$app->db->createCommand($sql)->execute();

    			if(!$res){

    				$this->addErrors($this->valores, 'Ocurrió un error al intentar guardar el valor para el año ' . $v['anio']);
    				$trans->rollBack();
    				return false;
    			}

    			$indice++;
    		}


    		//se modifican los datos de la tabla principal
    		$sql = "Update rodado_aforo Set origen = upper('$this->origen'), tvehic = upper('$this->tvehic'), marca_nom = upper('$this->marca_nom'), tipo_nom = upper('$this->tipo_nom'), modelo_nom = upper('$this->modelo_nom') Where aforo_id = '$this->aforo_id'";
    		Yii::$app->db->createCommand($sql)->execute();
    	}


    	$trans->commit();
    	return true;
    }




    public function borrar(){

		$this->setScenario('delete');
		if(!$this->validate()) return false;

		$trans = Yii::$app->db->beginTransaction();

		//se desvinculan los rodado
		$sql = "Update rodado Set aforo_id = NULL Where aforo_id = '$this->aforo_id'";
		Yii::$app->db->createCommand($sql)->execute();

		//se borran los valores
		$sql = "Delete From rodado_aforo_val Where aforo_id = '$this->aforo_id'";
		$res = Yii::$app->db->createCommand($sql)->execute() > 0;

		if(!$res){
			$this->addError($this->aforo_id, 'Ocurrió un error al intentar borrar los valores del aforo');
			$trans->rollBack();
			return false;
		}

		//se elimina el aforo
		$sql = "Delete From rodado_aforo Where aforo_id = '$this->aforo_id'";
		$res = Yii::$app->db->createCommand($sql)->execute() > 0;

		if(!$res){
			$this->addError($this->aforo_id, 'No existe el aforo');
			$trans->rollBack();
			return false;
		}

		$trans->commit();
		return true;
    }

    public function validarValores(){

    	$hayValor = false;
    	$anio = intval(date('Y'));
    	$indice = 0;

    	foreach( $this->valores as $v ){

			$anio 	= intVal( $v['anio'] );
    		$valor 	= floatval( $v['valor'] );

    		if($valor != 0) $hayValor = true;

    		if($valor == null) $this->valores[$indice]['valor'] = 0;
    		else if($valor < 0) $this->addError($this->aforo_id, 'El valor del año ' . $anio . ' no puede ser negativo');


    		$indice++;
    	}
    }

    public function existeAforo(){

    	$sql = "Select Exists (Select 1 From rodado_aforo Where aforo_id = '$this->aforo_id')";
    	$res = Yii::$app->db->createCommand($sql)->queryScalar();

    	if($res) $this->addError($this->aforo_id, 'El código de aforo que se está intentando crear ya existe');
    }
}
