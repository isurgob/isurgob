<?php

namespace app\models\config;
use yii\data\SqlDataProvider;

use Yii;

/**
 * This is the model class for table "objeto_tbaja".
 *
 * @property integer $cod
 * @property integer $tobj
 * @property string $nombre
 * @property string $fchmod
 * @property integer $usrmod
 */
class ObjetoTbaja extends \yii\db\ActiveRecord
{
	public $modif;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'objeto_tbaja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tobj', 'nombre'], 'required'],
            [['cod', 'tobj', 'usrmod'], 'integer'],
            [['fchmod'], 'safe'],
            [['nombre'], 'string', 'max' => 70]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod' => '',
            'tobj' => '',
            'nombre' => 'Nombre',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Usuario que modifico',
            'modif' => 'Modif',
        ];
    }    
    
    public function validar($accion){
    	if ($this->cod=="") $this->cod=0;
    	if ($this->tobj=="") $this->tobj=0;
    	if ($this->nombre=="") $this->nombre=0;
    	
    	$error = "";
    	
    	if ($accion == 0){
	    	$sql = "SELECT count(*) FROM objeto_tbaja WHERE upper(nombre)=upper('".$this->nombre."')";
	    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
	    	
	    	if($cantidad > 0){
	    		$error .= "<li>El nombre ingresado ya existe</li>";
	        }	
    	}
    	
    	if($accion==3){
			$sql = "SELECT upper(nombre) FROM objeto_tbaja WHERE cod=".$this->cod;
	    	$nombre = Yii::$app->db->createCommand($sql)->queryScalar();
	    	
	    	if(strtoupper($this->nombre) != $nombre){
				$sql = "SELECT count(*) FROM objeto_tbaja WHERE upper(nombre)=upper('".$this->nombre."')";
	    		$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
	    		
	    		if($cantidad > 0){
	    			$error .= "<li>El nombre ingresado ya existe</li>";
	    		}	    		
	    	}	    		
    	}
    	return $error;
	}
    
    
    public function NuevaTbaja(){
	 	if ($this->tobj=="") $this->tobj=0;
    	if ($this->nombre=="") $this->nombre=0;
		$accion = 0;
		$validar = $this->validar($accion);
		
		if($validar != "")return $validar;
		
		$sql = "SELECT COUNT(*) FROM objeto_tbaja WHERE cod=".$this->cod;
		$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
		
		if($cantidad > 0){
			$validar .= "<li>Objeto Repetido</li>";
			//$validar = 5 ;
		}else{
			$cod = $this->obtenerCodAutoincremental();
			$sql = "INSERT INTO objeto_tbaja(cod,tobj,nombre,fchmod,usrmod) VALUES(".$cod.",".$this->tobj.",'".$this->nombre."'";
			$sql .= ",current_timestamp,".Yii::$app->user->id.")";
			
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
    
    
    public function ModificarTbaja(){
		if ($this->cod=="") $this->cod=0;
		if ($this->tobj=="") $this->tobj=0;
    	if ($this->nombre=="") $this->nombre=0;
    	    			
		$accion = 3;
		$validar = $this->validar($accion);
		
		if($validar != "")return $validar;
		
    	$sql = "UPDATE objeto_tbaja SET ";
		$sql .= "tobj=".$this->tobj.", nombre='".$this->nombre."'";
		$sql .= ",fchmod = current_timestamp, usrmod=".Yii::$app->user->id;
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
		}else {
			return "<li>Ocurrio un error al intentar actualizar los datos en la BD.</li>";
		}
		
		return $validar;
 	}
    
    
    public function BorrarTbaja($cod){
    	
    	$validar= '';
		$sql = "SELECT count(*) FROM objeto WHERE tbaja=".$cod;
		$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
    		
		if($cantidad > 0){
			$validar .= "<li>No se puede eliminar el codigo esta siendo utilizado</li>";
		}else{
	    	$sql = "DELETE FROM objeto_tbaja";
			$sql .= " WHERE cod=".$cod;
			try{					
				$cmd = Yii :: $app->db->createCommand($sql);
				$cmd->execute();
		 	}
		 	catch(\Exception $e){
		 		$validar = strstr($e->getMessage(), 'The SQL being', true);
				$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
		 		return "<li>".$validar."</li>";
		 	}
		}
	 	
	 	return $validar;    	
    }
    
    public function obtenerCodAutoincremental(){
    	$sql = "select MAX(cod) from objeto_tbaja";
    	$valor = Yii::$app->db->createCommand($sql)->queryScalar() + 1; 	
    	return $valor;	
    }
    
    public function buscarObjetoTbaja($cond){   	
    	$count = Yii::$app->db->createCommand('Select count(*) From objeto_tbaja '.($cond!=="" ? " where ".$cond : ""))->queryScalar(); 
        $sql = 'Select * from objeto_tbaja ';
        
        if ($cond !== "") $sql = $sql.' where '.$cond;
         
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'cod',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>5,
			],
        ]); 
        return $dataProvider;
    }
}
