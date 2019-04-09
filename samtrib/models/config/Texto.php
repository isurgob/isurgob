<?php

namespace app\models\config;

use Yii;

use yii\helpers\ArrayHelper;

use app\utils\db\utb;

/**
 * This is the model class for table "texto".
 *
 * @property integer $texto_id
 * @property integer $tuso
 * @property string $nombre
 * @property string $titulo
 * @property string $detalle
 * @property string $fchmod
 * @property integer $usrmod
 */
class Texto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'texto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
		$ret = [];
		
		/**
		 * VALORES REQUERIDOS
		 */
		$ret[] = [
				'tuso',
				'required',
				'on' => ['insert']
				];
		 
		$ret[] = [
				['nombre', 'titulo', 'detalle'],
				'required',
				'on' => ['insert', 'update']
				];
		
		$ret[] = [
				'texto_id',
				'required',
				'on' => ['update', 'delete'],
				'message' => 'Elija un texto'
				];
		
		/**
		 * FIN VALORES REQUERIDOS
		 */

		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[] = [
				'nombre',
				'string',
				'max' => 20,
				'on' => ['insert', 'update']
				];
				
		$ret[] = [
				'titulo',
				'string',
				'max' => 50,
				'on' => ['insert', 'update']
				];
				
		$ret[] = [
				'detalle',
				'string',
				'max' => 4000,
				'on' => ['insert', 'update']
				];
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */
		
		$ret[] = [
				'detalle',
				'validarVariables',
				'on' => ['insert', 'update']
				];
		
		return $ret;
    }

	public function scenarios(){
		
		return [
			'insert' => ['nombre', 'titulo', 'detalle', 'tuso'],
			'update' => ['texto_id', 'nombre', 'titulo', 'detalle', 'tuso'],
			'delete' => ['texto_id']
		];
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        	'nombre' => 'Nombre',
        	'titulo' => 'Título',
        	'detalle' => 'Detalle',
        	'tuso' => 'Tipo de uso',
        	'texto_id' => 'Código del texto'
        ];
    }
    
    public function grabar(){
    	
    	$res = false;
    	$scenario = $this->isNewRecord ? 'insert' : 'update';
    	$this->setScenario($scenario);
    	
    	if(!$this->validate()) return false;
    	
    	if($this->isNewRecord){
    		
    		$sql = "Select Coalesce(Max(texto_id), 0) + 1 From texto";
    		$codigo = Yii::$app->db->createCommand($sql)->queryScalar();
    		
    		$sql = "Insert Into texto(texto_id, tuso, nombre, titulo, detalle, fchmod, usrmod)" .
    				" Values($codigo, $this->tuso, '$this->nombre', '$this->titulo', '$this->detalle', current_timestamp, " . Yii::$app->user->id . ")";
    				
    		$res = Yii::$app->db->createCommand($sql)->execute() > 0;
    		
    		if(!$res) $this->addError($this->nombre, 'Ocurrio un error al intentar grabar los datos');
    		else {
    			
    			$sql = "Select Max(texto_id) From texto";
    			$this->texto_id = Yii::$app->db->createCommand($sql)->queryScalar();
    		}
    		
    	} else{
    		
    		$sql = "Update texto Set nombre = '$this->nombre', titulo = '$this->titulo', detalle = '$this->detalle', fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where texto_id = $this->texto_id";
    		
			$res = Yii::$app->db->createCommand($sql)->execute() > 0;
    		
    		if(!$res) $this->addError($this->nombre, 'Ocurrio un error al intentar grabar los datos');
    	}
    	
    	return $res;
    }
    
    public function borrar(){
    	
    	$sql = "Delete From texto Where texto_id = $this->texto_id";
    	return Yii::$app->db->createCommand($sql)->execute() > 0;
    }
    
    public function validarVariables(){
    	
    	$variables = [];
    	
    	//se obtienen las palabras que comienzan con arroba y se guardan en $variables[0]
    	preg_match_all('/@[[:alpha:]]*/ ', $this->detalle, $variables);
    	
    	if(count($variables[0]) > 0){
    		$variables[0] = array_unique($variables[0]);//se eliminan los elementos repetidos
    		    		
			//se encuentran cuales son las variables que no corresponden y se muestra el mensaje
			$sql = "Select ('@' || variablenombre) As variablenombre From texto_var Where tuso = $this->tuso";
			$vars = Yii::$app->db->createCommand($sql)->queryAll();
			
			if(count($vars) > 0){
				
				$vars = ArrayHelper::map($vars, 'variablenombre', function($datos){return $datos['variablenombre'];});
				$vars = array_keys($vars);
				
				$vars = array_diff($variables[0], $vars);
									
			} else $vars = $variables[0];
			
			if ( count($vars) > 0 )
				$this->addError($this->detalle, 'Las siguientes variables no existen para el tipo de uso: ' . implode(', ', $vars));

    	}
    	
		return true;
    }
    
    public function getVariables($tuso){
    	
    	$sql = "Select variablenombre From texto_var Where tuso = $tuso Order By variablenombre";
    	return utb::getAux('texto_var', 'variablenombre', "('@' || variablenombre)", 0, "tuso = $tuso");
    }
    
    public static function getTextosTodos($uso){
    	
    	$sql = "Select texto_id, nombre, tuso, titulo From texto Where tuso = $uso";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    public static function getTexto($texto_id){
    	
    	return utb::getCampo('texto', "texto_id = $texto_id", 'detalle');
    }
    
    public static function getModificables(){
    	
    	$sql = "Select cod, nombre From texto_tuso u Left Join sam.sis_usuario_proceso p On u.proceso = p.pro_id" .
    			" Where p.usr_id = " . Yii::$app->user->id . " Order By nombre";
    	
    	$res = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	if($res === false || count($res) == 0) return [];
    	
    	return ArrayHelper::map($res, 'cod', function($arreglo){return $arreglo['nombre'];});
    }
}
