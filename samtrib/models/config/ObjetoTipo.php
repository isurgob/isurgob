<?php

namespace app\models\config;

use Yii;

/**
 * This is the model class for table "objeto_tipo".
 *
 * @property integer $cod
 * @property string $nombre
 * @property string $nombre_redu
 * @property string $campo_clave
 * @property string $letra
 * @property integer $autoinc
 * @property string $est
 * @property string $fchmod
 * @property integer $usrmod
 */
class ObjetoTipo extends \yii\db\ActiveRecord
{
	public $modif;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'objeto_tipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod', 'nombre_redu', 'nombre','letra'], 'required'],
            [['cod', 'autoinc', 'usrmod'], 'integer'],
            [['fchmod'], 'safe'],
            [['nombre'], 'string', 'max' => 25],
            [['nombre_redu'], 'string', 'max' => 3],
            [['campo_clave'], 'string', 'max' => 15],
            [['letra', 'est'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod' => 'Codigo',
            'nombre' => 'Nombre',
            'nombre_redu' => 'Nombre reducido',
            'campo_clave' => 'Campo clave',
            'letra' => 'Letra que lo identifica',
            'autoinc' => 'Autoincremental',
            'est' => 'Estado',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
    
    
    public function validar($accion){	
    	
    	$error = "";
    	
    	if ($accion == 0){
	    	$sql = "SELECT count(*) FROM objeto_tipo WHERE cod=".$this->cod;
	    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
	    	
	    	if($cantidad > 0){
	    		$error .= "<li>El codigo ingresado ya existe</li>";
	        }	
    		
	    	$sql = "SELECT count(*) FROM objeto_tipo WHERE nombre = '".$this->nombre."'";	
	    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
	    		
	    	if($cantidad > 0){	
	    		$error .= "<li>El nombre ingresado ya existe</li>";
	    	}
    	
	    	$sql = "SELECT count(*) FROM objeto_tipo WHERE nombre_redu = '".$this->nombre_redu."'";	
	    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
	    		
	    	if($cantidad > 0){		    		
	    		$error .= "<li>El nombre reducido ingresado ya existe</li>";
	    	}
    	
	    	$sql = "SELECT count(*) FROM objeto_tipo WHERE letra = '".$this->letra."'";	
	    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
	    		
	    	if($cantidad > 0){
	    		$error .= "<li>La letra ingresada ya existe</li>";
	    	}
    	   
    	}else if($accion==3){
    		$sql = "SELECT cod, nombre, nombre_redu, letra FROM objeto_tipo WHERE cod = ".$this->cod;
    		$resultado = Yii::$app->db->createCommand($sql)->queryAll();
    		$sql = "SELECT cod, nombre, nombre_redu, letra FROM objeto_tipo";
    		$objetos = Yii::$app->db->createCommand($sql)->queryAll();
    		
    		for($i=0; $i < count($objetos); $i++ ){
    			
    			if($resultado['0']['cod'] != $objetos[$i]['cod']){
    				
    				if($resultado['0']['nombre'] == $objetos[$i]['nombre']){
    					$error .= "<li>El nombre ingresado ya existe</li>";
    				}
    				
    				if($resultado['0']['nombre_redu'] == $objetos[$i]['nombre_redu']){
    					$error .= "<li>El nombre reducido ingresado ya existe</li>";
    				}
    				
    				if($resultado['0']['letra'] == $objetos[$i]['letra']){
    					$error .= "<li>La letra ingresada ya existe</li>";
    				}
    			}	
    		}
    	}
    	
    	return $error;
 	 }
    
    
    public function NuevoTipoDeObejeto(){
		if ($this->cod=="") $this->cod=0;
		if ($this->nombre=="") $this->nombre=0;
		if ($this->nombre_redu==""){ 
			
			$this->nombre_redu=0;
		}else{
			$this->nombre_redu = strtoupper($this->nombre_redu);	
		}
		if ($this->letra=="") $this->letra=0;
		
		$accion = 0;
		$validar = $this->validar($accion);
		
		if($validar != "")return $validar;
		
		$sql = "SELECT COUNT(*) FROM objeto_tipo WHERE cod=".$this->cod;
		$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
			
		if($cantidad > 0){
			$validar .= "<li>Tipo de Objeto Repetido</li>";
			
		}else{
			$sql = "INSERT INTO objeto_tipo VALUES(".$this->cod.",'".$this->nombre."','".$this->nombre_redu."','".$this->campo_clave;
			$sql .= "','".$this->letra."',".$this->autoinc.",'A',current_timestamp,".Yii::$app->user->id.")";
		try{						
			$cmd = Yii::$app->db->createCommand($sql);
			$rowCount = $cmd->execute();	
		 }
		 catch(\Exception $e){	 			
			$validar = strstr($e->getMessage(), 'The SQL being', true);
			$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
	 		return "<li>".$validar."</li>";
	 		}	
					
		  if ($rowCount > 0){
			return $validar;
		  } else {
			$validar .= "<li>Ocurrio un error al intentar grabar en la BD.</li>";
			}
		}
		
		return $validar;
     }
    
    
    
    public function ModificarTipoDeObjeto(){	
    	if ($this->cod=="") $this->cod=0;
    	if ($this->nombre=="") $this->nombre=0;
    	if ($this->nombre_redu=="") $this->nombre_redu=0;
    	if ($this->campo_clave=="") $this->campo_clave=0;
    	if ($this->letra=="") $this->letra=0;
    	if ($this->est=="") $this->est="A";
    	if ($this->autoinc=="") $this->autoinc=0;
		
		$accion = 3;
		$validar = $this->validar($accion);
		if($validar != ""){
			return $validar;
		}else{
	    	$sql = "UPDATE objeto_tipo SET ";
			$sql .= "nombre='".$this->nombre."', nombre_redu='".$this->nombre_redu."', campo_clave='".$this->campo_clave;
			$sql .= "',letra='".strtoupper($this->letra)."',est='".$this->est."', autoinc=".$this->autoinc.",fchmod = current_timestamp, usrmod=".Yii::$app->user->id;
			$sql .= " WHERE cod=".$this->cod;
			try{
				$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();
		 	}
		 	catch(\Exception $e){	
			 		$validar = strstr($e->getMessage(), 'The SQL being', true);
					$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
			 		return "<li>".$validar."</li>";
		 	}
		 	
		 	if ($rowCount > 0) {
				return $validar;
			} else {
				return "<li>Ocurrio un error al intentar actualizar los datos en la BD.</li>";
			}
		}
		
		return $validar;
     }
         
                         
     public function EliminarTipoDeObjeto($id){     	         		  	
    	$sql = "UPDATE objeto_tipo SET ";
		$sql .= "est='B',fchmod = current_timestamp, usrmod=".Yii::$app->user->id;
		$sql .= " WHERE cod=".$id;
	
		try{						
			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
	 	}
	 	catch(\Exception $e){
	 			
	 		$validar = strstr($e->getMessage(), 'The SQL being', true);
			$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));		
	 		return "<li>".$sql."</li>";
	 	}	
     }
    
    
    
}
