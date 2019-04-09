<?php

namespace app\models;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;

use Yii;

/**
 * This is the model class for table "rubro".
 *
 * @property integer $sis_id
 * @property string $version
 * @property string $origen

 * @property string $fchmod
 * @property integer $usrmod
 */
class ControlVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sam.version';
    }

    /**
     * @inheritdoc
     */


    /**
     * @inheritdoc
     */

    
    public function listarVersiones($sis_id){
    	
    	$sql = "select version,to_char(fchmod::date,'dd/mm/yyyy') as fecha, to_char(fchmod,'HH24:MI:SS') as hora 
				from sam.version where sis_id=".$sis_id;
		$sql .= "ORDER BY version DESC";		
    	
    	
    	$pag = "select count(*) from sam.version where sis_id=".$sis_id;
    	$count = Yii::$app->db->createCommand($pag)->queryScalar();
    	
    	$dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
			'pagination'=> [
			'pageSize'=>18,
			],
        ]);
        
        return $dataProvider;
    }
    
    public function buscarNovedad($sis_id,$version){
    	
    	$sql="select novedades from sam.version where sis_id=".$sis_id." and version='".$version."'";
    	$novedad  = Yii::$app->db->createCommand($sql)->queryScalar();

    	//$dataProvider = new SqlDataProvider([
            //'sql' => $sql
        //]);
        
        return $novedad;
    }
     
}
