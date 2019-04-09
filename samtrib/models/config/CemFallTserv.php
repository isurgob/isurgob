<?php

namespace app\models\config;

use Yii;

/**
 * This is the model class for table "cem_fall_tserv".
 *
 * @property string $cod
 * @property string $nombre
 * @property string $est_fin
 * @property integer $pedir_obj_dest
 * @property integer $pedir_dest
 * @property string $fchmod
 * @property integer $usrmod
 */
class CemFallTserv extends \yii\db\ActiveRecord
{
	
	public $usrmod_nom;
	public $modif;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cem_fall_tserv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod', 'nombre'], 'required'],
            [['pedir_obj_dest', 'pedir_dest', 'usrmod'], 'integer'],
            [['fchmod'], 'safe'],
            [['cod'], 'string', 'max' => 3],
            [['nombre'], 'string', 'max' => 35],
            [['est_fin'], 'string', 'max' => 3]
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
            'est_fin' => 'Estado final',
            'pedir_obj_dest' => 'objeto destino',
            'pedir_dest' => 'destino',
            'fchmod' => '',
            'usrmod' => '',
        ];
    }
    
    public function validar($accion){
    	
    	$error="";
    	
    	if($accion==0){
	    	if($this->cod==""){
	    		$error .= "<li>Campo codigo obligatorio<li>";	
	    	}else{
	    		$sql = "SELECT count(*) FROM cem_fall_tserv WHERE cod = '".$this->cod."'";
		    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
		    	
		    	if($cantidad > 0){
		    		$error .= "<li>El codigo ingresado ya existe</li>";	    		
		    	}
	    	}
	    	
		    $sql = "SELECT COUNT(*) FROM cem_fall_tserv WHERE cod='".$this->cod."'";
			$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
			
			if($cantidad > 0){
				$error .= "<li>Registro Repetido</li>";	
			}
			
	 	if($this->nombre==""){
    		$error .= "<li>Campo nombre obligatorio</li>";	
    	}else{
    		
    		$sql = "SELECT count(*) FROM cem_fall_tserv WHERE nombre = '".$this->nombre."'";
	    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
	    	
	    	if($cantidad > 0){
	    		$error .= "<li>El nombre ingresado ya existe</li>";	    		
	    		}
    		}		
    	}else if($accion==3){
    		
    		if($this->nombre==""){
    			$error .= "<li>Campo nombre obligatorio</li>";	
    		}else{
    		
	    		$sql = "SELECT nombre FROM cem_fall_tserv WHERE cod = '".$this->cod."'";
	    		$nombre = Yii::$app->db->createCommand($sql)->queryScalar();
    		
    		if($this->nombre!=$nombre){
    			
	    		$sql = "SELECT count(*) FROM cem_fall_tserv WHERE nombre = '".$this->nombre."'";
		    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
		    	if($cantidad > 0)$error .= "<li>El nombre ingresado ya existe</li>";
    			}
    		}
    	}

    	return $error;
    }
    
	public function NuevoTServ(){
		
			$validar = $this->validar(0);
			
			if($validar!='')return $validar;
		
			$cod = strtoupper($this->cod);
			
			$sql = "INSERT INTO cem_fall_tserv(cod,nombre,est_fin,pedir_obj_dest,pedir_dest,fchmod,usrmod)";
			$sql .= " VALUES('".$cod."','".$this->nombre."','".$this->est_fin."',".$this->pedir_obj_dest.",".$this->pedir_dest;
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
		return $validar;
	}
	
	public function ModificarTServ(){
		
		$validar = $this->validar(3);
			
		if($validar!='')return $validar;
		
    	$sql = "UPDATE cem_fall_tserv SET ";
		$sql .= "nombre='".$this->nombre."',est_fin='".$this->est_fin."',pedir_obj_dest=".$this->pedir_obj_dest.",pedir_dest=".$this->pedir_obj_dest;
		$sql .= ",fchmod = current_timestamp, usrmod=".Yii::$app->user->id;
		$sql .= " WHERE cod='".$this->cod."'";

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
	
	public function BorrarTServ($cod){

		$validar= '';
	    	$sql = "DELETE FROM cem_fall_tserv";
			$sql .= " WHERE cod='".$cod."'";
			try{					
				$cmd = Yii :: $app->db->createCommand($sql);
				$cmd->execute();
		 	}
		 	catch(\Exception $e){
		 		$validar = strstr($e->getMessage(), 'The SQL being', true);
				$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
		 		return "<li>".$validar."</li>";
		 	}
	 	
	 	return $validar;   
	}
    
    
}
