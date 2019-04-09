<?php

namespace app\models\ctacte;

use Yii;

ini_set("display_errors", "on");
error_reporting(E_ALL);

class ResolLocal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
     
    public static function tableName()
    {
        return 'resol_local';
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
				['resol_id', 'varlocal'],
				'required',
				'on' => ['insert', 'update', 'delete']
				];
		
		$ret[]= [
				'tipo',
				'required',
				'on' => ['insert', 'update'],
				'message' => 'Elija un tipo'
				];
				
		$ret[]= [
				'valor',
				'required',
				'on' => ['insert', 'update']
				];
		
		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[]= [
				'resol_id',
				'integer',
				'min' => 1,
				'on' => ['insert', 'update', 'delete']
				];
				
		$ret[]= [
				'varlocal',
				'string',
				'max' => 15,
				'min' => 1,
				'on' => ['insert', 'update', 'delete']
				];
				
		$ret[]= [
				'tipo',
				'integer',
				'min' => 1,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'valor',
				'string',
				'max' => 20,
				'on' => ['insert', 'update']
				];
		
		$ret[]= [
				'varlocal',
				'validarNombre',
				'on' => ['insert', 'update']
				];
		
		return $ret;
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'resol_id' => 'Código de resolución',
            'varlocal' => 'Nombre',
            'tipo' => 'Tipo',
            'valor' => 'Valor',

        ];
    }
    
    public function scenarios(){
    	
    	return [
    			'insert' => ['resol_id', 'varlocal', 'tipo', 'valor'],
    			'update' => ['resol_id', 'varlocal', 'tipo', 'valor'],
    			'delete' => ['resol_id', 'varlocal']
    			];
    }
    
    
    
    public function grabar(){
    	
    	$scenario= $this->isNewRecord ? 'insert' : 'update';
    	$this->setScenario($scenario);
    	
    	if(!$this->validate()) return false;
    	
    	$sql= "";
    	$trans= Yii::$app->db->beginTransaction();
    	
    	if($this->isNewRecord)
    		$sql= "Insert Into resol_local(resol_id, varlocal, tipo, valor) Values($this->resol_id, '$this->varlocal', $this->tipo, '$this->valor')";
    	else
    		$sql= "Update resol_local Set tipo = $this->tipo, valor = '$this->valor' Where resol_id = $this->resol_id And varlocal = '$this->varlocal'";
    	
    	$res= Yii::$app->db->createCommand($sql)->execute() > 0;
    	
    	if(!$res){
    		
    		$this->addError($this->varlocal, 'Ocurrió un error al intentar realizar la acción');
    		$trans->rollBack();
    		return false;
    	}
    	
    	$trans->commit();
    	return true;
    }
    
    public function borrar(){
    	
    	$this->setScenario('delete');
    	if(!$this->validate()) return false;
    	
    	$sql= "Delete From resol_local Where resol_id = $this->resol_id And varlocal = '$this->varlocal'";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	return true;
    }
    
    /**
     * Valida el nombre de la variable
     */
    public function validarNombre(){
    	
    	$sql= "Select Exists (Select 1 From resol_local Where resol_id = $this->resol_id And varlocal = '$this->varlocal')";
    	$existe= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($this->isNewRecord && $existe) $this->addError($this->varlocal, 'Ya existe una variable con el mismo nombre para la resolución');
    	else if(!$this->isNewRecord && !$existe) $this->addError($this->varlocal, 'No existe la variable a modificar');
    }    
}
