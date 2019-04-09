<?php

namespace app\models\ctacte;

use Yii;
use app\utils\helpers\DBException;

/**
 * This is the model class for table "ctacte_libredeuda".
 *
 * @property integer $ldeuda_id
 * @property string $obj_id
 * @property string $fchemi
 * @property string $escribano
 * @property integer $texto_id
 * @property string $obs
 * @property string $est
 * @property string $fchbaja
 * @property integer $usrbaja
 * @property string $fchmod
 * @property integer $usrmod
 */
class Libredeuda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ctacte_libredeuda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['obj_id', 'fchemi', 'escribano', 'obs', 'usrmod'], 'required'],
            [['fchemi', 'fchbaja', 'fchmod'], 'safe'],
            [['texto_id', 'usrbaja', 'usrmod'], 'integer'],
            [['obj_id'], 'string', 'max' => 8],
            [['escribano'], 'string', 'max' => 40],
            [['obs'], 'string', 'max' => 500],
            [['est'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ldeuda_id' => 'Identificador de libre deuda',
            'obj_id' => 'Codigo de objeto',
            'fchemi' => 'Fecha de emision',
            'escribano' => 'Nombre del escribano',
            'texto_id' => 'Identificador del texto',
            'obs' => 'Observaciones',
            'est' => 'Estado',
            'fchbaja' => 'Fecha de baja',
            'usrbaja' => 'Usuario que dio de baja',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
    
   public function Imprimir($obj_id,$Escribano,$Texto,$Obs,$AnioDesde,$AnioHasta,$TObj,$trib_id,&$datos,&$sub,&$sub2)
   {
   		//verifico si no existe bloqueo para el objeto
        $sql = "Select EXISTS (SELECT 1 from ctacte_libredeuda_bloq Where obj_id='".$obj_id."' and est='A')";
        $res = Yii::$app->db->createCommand($sql)->queryScalar();
        
        if ( $res == 1 ) 
        	return "Existe un bloqueo para el Objeto. No podrá emitir libre deuda";
        
        //verifico si ya no fue grabada
        $sql = "Select ldeuda_id From ctacte_libredeuda Where obj_id='".$obj_id."' and FchEmi='".date('d/m/Y')."'";
        $LDeuda_id = Yii::$app->db->createCommand($sql)->queryScalar();
        
        if ( $LDeuda_id == 0 ){
            $sql = "Insert Into ctacte_libredeuda (obj_id,fchemi,escribano,texto_id,obs,est,fchbaja,usrbaja,fchmod,usrmod) Values ('".$obj_id."','";
            $sql .= date('d/m/Y')."','".$Escribano."',".$Texto.",'".$Obs."','A',";
            $sql .= "null,0,current_timestamp,".Yii::$app->user->id.")";
        }else {
            $sql = "Update ctacte_libredeuda Set Escribano='".$Escribano."', Texto_Id = ".$Texto;
            $sql .= ", Obs='".$Obs."', FchMod=current_timestamp,UsrMod=".Yii::$app->user->id;
            $sql .= " Where ldeuda_id=".$LDeuda_id;
   		}
   		
   		try {
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {
    		$error = DBException::getMensaje($e);
    		return $error;
    	}
   		
   		//Cargo los DataSet para el reporte
        $sql = "Select *,to_char(fchemi,'dd/mm/yyyy') fchemi,to_char(fchbaja,'dd/mm/yyyy') fchbaja From sam.uf_libredeuda ('".$obj_id."',".$AnioDesde.",".$AnioHasta.",".$TObj.",".$trib_id.")";
        $datos = Yii::$app->db->createCommand($sql)->queryAll();
        
        switch ($TObj) {
	    	case 1: 
	    		$sql = "Select * From v_inm Where obj_id='".$obj_id."'";
	    		break;
	    	case 2: 
	    		$sql = "Select * From v_comer Where obj_id='".$obj_id."'";
	    		break;
	    	case 3: 
	    		$sql = "Select * From v_persona Where obj_id='".$obj_id."'";
	    		break;
	    	case 4: 
	    		$sql = "Select * From v_cem Where obj_id='".$obj_id."'";
	    		break;
	    	case 5: 
	    		$sql = "Select * From v_rodado Where obj_id='".$obj_id."'";
	    		break;
	    }
	    
	    $sub = Yii::$app->db->createCommand($sql)->queryAll();
		
		if ( $TObj !== 3 ) {
			$sql = "select * from sam.uf_ctacte_ultimopago('$obj_id')";
			$sub2 = Yii::$app->db->createCommand($sql)->queryAll();
		}
	    
	    return "";
   }
   
   public function Borrar($id)
   {
   		$sql = "Update ctacte_libredeuda set est='B', UsrBaja= ".Yii::$app->user->id.", FchBaja= current_timestamp Where ldeuda_id=".$id;
   		$cmd = Yii :: $app->db->createCommand($sql);
		try {
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {
    		$error = DBException::getMensaje($e);
    		return $error;
    	}
    	return '';
   }
   
   public function LibreDeudaEsp($id,$acc,$quitar,$obs='')
   {
   		if ($quitar == 1){
            	$sql = "update ctacte_libredeuda_".$acc." set est='B',usrmod=".Yii::$app->user->id.",fchmod=current_timestamp where obj_id='".$id."'";
   		}else {
        	$sql = "Select count(*) From ctacte_libredeuda_".$acc." Where obj_id='".$id."'";
        	$cant = Yii::$app->db->createCommand($sql)->queryScalar();
        	
        	if ($cant == 0) {
        		$sql = "Insert Into ctacte_libredeuda_".$acc." Values ('".$id."','A'," . ($acc == "bloq" ? "'" . $obs ."'," : "") . " current_timestamp,".Yii::$app->user->id.")";
        	}	
        }    	
   		
   		try {
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {
    		$error = DBException::getMensaje($e);
    		return $error;
    	}
   		
   		return '';
   }
   
}
