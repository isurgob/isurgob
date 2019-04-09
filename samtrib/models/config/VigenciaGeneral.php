<?php

namespace app\models\config;
use yii\data\SqlDataProvider;

use Yii;

/**
 * This is the model class for table "rubro_general".
 *
 * @property string $nomen_id
 * @property integer $pi
 * @property integer $perdesde
 * @property integer $perhasta
 * @property integer $alicuota
 * @property integer $minimo
 * @property string $fchmod
 * @property integer $usrmod
 */
class VigenciaGeneral extends \yii\db\ActiveRecord
{
	
	public $aniodesde; 
	public $aniohasta;
	public $cuotadesde;
	public $cuotahasta;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rubro_general';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nomen_id','pi','perdesde','perhasta','alicuota','minimo'], 'required'],
            [['perdesde','perhasta','alicuota','minimo'], 'integer'],
			[['nomen_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nomen_id' => 'Nomeclador',
            'pi' => '',
            'perdesde' => 'Desde',
            'perhasta' => 'Hasta',
            'alicuota' => 'alicuota',
            'minimo' => 'Minimo',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico'
        ];
    }
    
    public function validar($nomen_id,$pi,$perdesde,$perhasta, $accion= 0){
    	
    	$error="";
    	if($accion==0){
		$sql = "SELECT count(*) FROM rubro_general WHERE nomen_id='".$nomen_id."' and pi=".$pi." and perdesde=".$perdesde." and perhasta=".$perhasta;
    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($cantidad > 0) $error .= "<li>El rubro general ingresado ya existe</li>";

    	}
    	return $error;
    }
    
    public function NuevaVigenciaGeneral($nomen_id,$aniodesde,$aniohasta,$cuotadesde,$cuotahasta,$alicuota,$minimo){
    	
    	$perdesde = ($aniodesde * 1000) + $cuotadesde;
		$perhasta = ($aniohasta * 1000) + $cuotahasta;

    	$validar = $this->validar($nomen_id,$this->pi,$perdesde,$perhasta);

		if($validar != '')return $validar;
		
			$sql = "INSERT INTO rubro_general VALUES('".$nomen_id."',".$this->pi.",".$perdesde.",".$perhasta.",";
			$sql .= $alicuota.",".$minimo.",current_timestamp,".Yii::$app->user->id.")";
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
    
   public function ModificarVigenciaGeneral($nomen_id,$aniodesde,$aniohasta,$cuotadesde,$cuotahasta,$alicuota,$minimo){

    	$perdesde = ($aniodesde * 1000) + $cuotadesde;
		$perhasta = ($aniohasta * 1000) + $cuotahasta;

    	$validar = $this->validar($nomen_id,$this->pi,$perdesde,$perhasta,3);

		if($validar != '')return $validar;
		
			$sql = "UPDATE rubro_general SET alicuota=".$alicuota.",minimo=".$minimo;
			$sql .= ",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
			$sql .= " WHERE nomen_id='".$nomen_id."' and pi=".$this->pi." and perdesde=".$perdesde." and perhasta=".$perhasta;
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
     
     public function eliminarRubro($nomen_id,$aniodesde,$aniohasta,$cuotadesde,$cuotahasta){
     	
     	$perdesde = ($aniodesde * 1000) + $cuotadesde;
		$perhasta = ($aniohasta * 1000) + $cuotahasta;
		
		$sql = "DELETE FROM rubro_general " .
			   "WHERE nomen_id='".$nomen_id."' and pi=".$this->pi." and perdesde=".$perdesde." and perhasta=".$perhasta;

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
    
}
