<?php

namespace app\models\ctacte;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;

use Yii;

/**
 * This is the model class for table "resol_tabla".
 *
 * @property integer $tabla_id
 * @property integer $dato_id
 * @property integer $perdesde
 * @property integer $perhasta
 * @property string $paramstr
 * @property integer $param1
 * @property integer $param2
 * @property integer $param3
 * @property integer $param4
 * @property integer $param5


 */
class ResolTablaDato extends \yii\db\ActiveRecord
{
	/**
	 * Almacenan los nombres que se le han asignado a los parametros.
	 * Cada una de las variables debe comenzar con el nombre del parametro en la base de datos y finalizar con 'Str'.
	 */
    public $paramstrStr;
    public $param1Str;
    public $param2Str;
    public $param3Str;
    public $param4Str;
    public $param5Str;
    
    public $adesde;
    public $cdesde;
    public $ahasta;
    public $chasta;
    
    private $usaParamStr;
    
    
    public function __construct(){
    	
    	parent::__construct();
    	
    	$this->ahasta= 9999;
    	$this->chasta= 999;
    	$this->param1= 0;
    	$this->param2= 0;
    	$this->param3= 0;
    	$this->param4= 0;
    	$this->param5= 0;
    	
    	$this->usaParamStr= true;
    }
     
    public static function tableName()
    {
        return 'resol_tabla_dato';
    }

    /**
     * @inheritdoc
     */
    public function rules(){

		$ret= [];

		/**
		 * CAMPOS REQUERIDOS
		 */
		$ret[]= [
				['tabla_id', 'adesde', 'cdesde', 'ahasta', 'chasta'],
				'required',
				'on' => ['insert', 'update', 'delete']
				];
				
		$ret[]= [
				'paramstr',
				'required',
				'when' => function($model){return $this->usaParamStr;},
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'dato_id',
				'required',
				'on' => ['update', 'delete']
				];
				
		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[]= [
				'tabla_id',
				'integer',
				'min' => 1,
				'on' => ['insert', 'update', 'delete']
				];
				
		$ret[]= [
				['adesde', 'ahasta'],
				'integer',
				'min' => 1900,
				'max' => 9999,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				['cdesde', 'chasta'],
				'integer',
				'min' => 1,
				'max' => 999,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'paramstr',
				'string',
				'max' => 5,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				['param1', 'param2', 'param3', 'param4', 'param5'],
				'number',
				'min' => 0,
				'max' => 99999,
				'on' => ['insert', 'update']
				];
		
		/**
		 * VALORES POR DEFECTO
		 */
		$ret[]= [
				'ahasta',
				'default',
				'value' => 9999,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'chasta',
				'default',
				'value' => 999,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				['param1', 'param2', 'param3', 'param4', 'param5'],
				'default',
				'value' => 0,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'paramstr',
				'default',
				'value' => '',
				'when' => function($model){return !$this->usaParamStr;},
				'on' => ['insert', 'update']
				];
		
		return $ret;
	}
	
	public function scenarios(){
		
		return [
				'insert' => ['tabla_id', 'adesde', 'cdesde', 'ahasta', 'chasta', 'paramstr', 'param1', 'param2', 'param3', 'param4', 'param5'],
				'update' => ['tabla_id', 'dato_id', 'adesde', 'cdesde', 'ahasta', 'chasta', 'paramstr', 'param1', 'param2', 'param3', 'param4', 'param5'],
				'delete' => ['tabla_id', 'dato_id']
		];
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tabla_id' => 'Código de tabla',
            'dato_id' => 'Código del dato',
            'perdesde' => 'Período desde',
            'perhasta' => 'Período hasta',
            'paramstr'=> $this->nombreParametro('paramstr'),
            'param1'=> $this->nombreParametro('param1'),
            'param2'=> $this->nombreParametro('param2'),
            'param3'=> $this->nombreParametro('param3'),
            'param4'=> $this->nombreParametro('param4'),
            'param5'=> $this->nombreParametro('param5')
        ];
    }
    
    public function beforeValidate(){
    	
    	if(in_array($this->getScenario(), ['insert', 'update'])){
    		
    		$sql= "Select uso_paramstr From resol_tabla Where tabla_id = $this->tabla_id";
    		$this->usaParamStr= Yii::$app->db->createCommand($sql)->queryScalar();
    	}
    	
    	return true;
    }
    
    public function afterValidate(){
    	
    	if(!$this->hasErrors()){
    		
    		$this->perdesde= $this->adesde * 1000 + $this->cdesde;
    		$this->perhasta = $this->ahasta * 1000 + $this->chasta;
    	}
    }
    
    public function afterFind(){

		$this->adesde= intval($this->perdesde / 1000);
		$this->cdesde= $this->perdesde % 1000;
		
		$this->ahasta= intval($this->perhasta / 1000);
		$this->chasta= $this->perhasta % 1000;
    }
    
    public function grabar(){

    	$scenario= $this->isNewRecord ? 'insert' : 'update';
    	$this->setScenario($scenario);
    	
    	if(!$this->validate()) return false;
    	
    	$trans= Yii::$app->db->beginTransaction();
    	$sql= "";
    	
    	if($this->isNewRecord){
    		
    		//codigo
    		$sql= "Select Coalesce(Max(dato_id), 0) + 1 From resol_tabla_dato Where tabla_id = $this->tabla_id";
    		$codigo= Yii::$app->db->createCommand($sql)->queryScalar();
    		
    		$sql= "Insert Into resol_tabla_dato(tabla_id, dato_id, perdesde, perhasta, paramstr, param1, param2, param3, param4, param5, fchmod, usrmod)" .
    				" Values($this->tabla_id, $codigo, $this->perdesde, $this->perhasta, '$this->paramstr'," .
    				" $this->param1, $this->param2, $this->param3, $this->param4, $this->param5, current_timestamp, " . Yii::$app->user->id . ")";
    		
    		$this->dato_id= $codigo;
    		
    	} else {
    		
    		$sql= "Update resol_tabla_dato set paramstr = '$this->paramstr', param1 = $this->param1, param2 = $this->param2, param3 = $this->param3, param4 = $this->param4," .
    				" param5 = $this->param5, perdesde = $this->perdesde, perhasta = $this->perhasta, fchmod = current_timestamp, usrmod= " . Yii::$app->user->id . " Where tabla_id = $this->tabla_id" .
    				" And dato_id = $this->dato_id";
    	}
    	
    	$res= Yii::$app->db->createCommand($sql)->execute() > 0;
    		
		if(!$res){
			$this->addError($this->tabla_id, 'Ocurrió un error al intentar realizar la acción');
			$trans->rollBack();
			return false;
		}
    	
    	$trans->commit();
    	return true;
    }
    
    public function borrar(){
    	
    	$this->setScenario('delete');
    	if(!$this->validate()) return false;
    	
    	$sql= "Delete From resol_tabla_dato Where tabla_id = $this->tabla_id And dato_id = $this->dato_id";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	return true;
    }
    
    /**
     * Carga las variables correspondientes con los nombre de los parametros
     * 
     * @param Array - Cada elemento del arreglo debe ser un model ResolTablaCol.
     */
    public function cargarNombreParametros($columnas){
    	    	
    	foreach($columnas as $col){
    		
    		$parametro= $col->param . 'Str';
    		
    		if($this->hasProperty($parametro))
    			$this->$parametro= $col->nombre;
    	}
    }
    
    /**
     * Obtiene el nombre que se le ha asignado al parametro.
     * 
     * @param string $parametro - Parametro del cual se quiere obtener el nombre.
     * 
     * @return string - Nombre que se le ha asignado al parametro.
     */
    private function nombreParametro($parametro){
    	
    	$p= $parametro . 'Str';
    	return $this->hasProperty($p) ? $this->$p : '';
    }
}
