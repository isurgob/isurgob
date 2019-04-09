<?php

namespace app\models\config;

use Yii;
use app\utils\db\utb;

/**
 * This is the model class for table "banco_cuenta".
 *
 * @property integer $bcocta_id
 * @property integer $bco_ent
 * @property integer $bco_suc
 * @property integer $bco_tcta
 * @property string $bco_cta
 * @property string $titular
 * @property string $fchmod
 * @property integer $usrmod
 */
class BancoCuenta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fin.banco_cta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bcocta_id', 'titular'], 'required'],
            [['bcocta_id', 'ultcheque'], 'integer'],
            [['titular', 'tipo', 'tmoneda', 'tuso'], 'string', 'max' => 50],
			[['est'], 'string', 'max' => 1],
			[['cbu'], 'string', 'max' => 22]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bcocta_id' => 'Código: ',
            'titular' => 'Titular: ',
			'cbu' => 'CBU:',
            'tipo' => 'Tipo de Cuenta',
            'tmoneda' => 'Tipo de Moneda',
            'tuso' => 'Es Interna',
            'ultcheque' => 'Ultimo cheque',
			'est' => 'Estado',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Código de usuario que modifico',
        ];
    }
    
    public function validar(){
    	
    	if ($this->tipo=="") $this->tipo=0;
    	if ($this->tmoneda=="") $this->tmoneda=0;
		if ($this->ultcheque=="") $this->ultcheque=0;
    	
    	$error = "";
    	
    	//if ($this->cbu <> "" and utb::ValidarCBU($this->cbu) != "") $error .= $this->cbu;
    	
    	return $error;
    	
    }
    
    public function NuevaCuentaBancaria(){
    	
    	if ($this->bcocta_id=="") $this->bcocta_id=0;
    	if ($this->titular=="") $this->titular=0;
			
			$validar = $this->validar();
			
			if($validar != "")return $validar;
			
			$sql = "SELECT COUNT(*) FROM fin.banco_cta WHERE bcocta_id=".$this->bcocta_id;
			
			$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
			
			if($cantidad > 0){
				
				$validar .= "<li>Cuenta Bancaria Repetida</li>";
			
			}else{
			
			
			$sql = "INSERT INTO fin.banco_cta VALUES(".$this->bcocta_id.",'".$this->titular."','".$this->cbu."','".$this->tipo;
			$sql .= "','".$this->tmoneda."','".$this->tuso."',".$this->ultcheque.",'A',current_timestamp,".Yii::$app->user->id.")";
			
			
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
    
    
    public function ModificarCuentaBancaria(){
    	
    			if ($this->bcocta_id=="") $this->bcocta_id=0;
    			if ($this->titular=="") $this->titular=0;
    			
    			$validar = $this->validar();
    			
    			if($validar != "")return $validar;
    	
		    	$sql = "UPDATE fin.banco_cta SET ";
    			$sql .= "titular='".$this->titular."', cbu='".$this->cbu."', tipo='".$this->tipo;
    			$sql .= "',tmoneda='".$this->tmoneda."',tuso='".$this->tuso."',ultcheque=".$this->ultcheque.",fchmod = current_timestamp, usrmod=".Yii::$app->user->id;
    			$sql .= " WHERE bcocta_id=".$this->bcocta_id;
    	
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
				
				return $validar;	   	
    }
    
    public function EliminarCuentaBancaria($idBcoCta){
    	
			$sql = "DELETE FROM fin.banco_cta ";
			$sql .= "WHERE bcocta_id=".$idBcoCta;
	
			try{						
				$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();
		 	}
		 	catch(\Exception $e){
		 			
		 		$validar = strstr($e->getMessage(), 'The SQL being', true);
				$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
		 		return "<li>".$validar."</li>";
		 	}
    }
    
                    
    public function getNombreBancoEntidad($idBancoEntidad) {
		
			$cmd = "";
			
			if ($idBancoEntidad != ""){
			
				$sql = "SELECT nombre FROM banco_entidad WHERE bco_ent =" . $idBancoEntidad;
				$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();
			
			}
			
			return $cmd;	
	}
	
	
	public function getNombreSucursal($idSucursal,$idBancoEntidad) {
		
		$cmd = "";
		
		if ($idSucursal != ""){
		
			$sql = "SELECT nombre FROM banco WHERE bco_suc =" . $idSucursal ." and bco_ent =".$idBancoEntidad;
			$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();
		
		}
		
		return $cmd;	
	}
	
}
