<?php

namespace app\models\config;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;

use Yii;

/**
 * This is the model class for table "rodado_val".
 *
 * @property integer $anioval
 * @property integer $gru
 * @property integer $anio
 * @property string $pesodesde
 * @property string $pesohasta
 * @property string $valor
 * @property string $fchmod
 * @property integer $usrmod
 */
class RodadoVal extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rodado_val';
    }

    /**
     * @inheritdoc
     */
    public function rules(){

        return [
            [['anioval', 'gru', 'anio', 'pesodesde', 'pesohasta','valor'], 'required'],
            [['anioval', 'gru', 'anio','usrmod'], 'integer'],
            [['pesodesde', 'pesohasta', 'valor'], 'number'],
            [['usrmod'],'default', 'value'=>Yii::$app->user->id],
            ['pesodesde', 'compare', 'compareAttribute'=>'pesohasta', 'operator'=>'<=', 'message'=>'Rango de Peso incorrecto'],
            /*['anioval','validarClaveDuplicada','on' => ['create']],
            ['anioval','validarSuperposicion','on' => ['create']]*/
        ];
    }
    
    /*public function scenarios()
    {
        return [
        	'create' => ['anioval', 'gru', 'anio', 'pesodesde', 'pesohasta','valor','usrmod','fchmod'],
        	'update' => ['anioval', 'gru', 'anio', 'pesodesde', 'pesohasta','valor','usrmod','fchmod']
        ];
    }
    
    public function validarClaveDuplicada(){
    	
    	if (RodadoVal::findOne(['anioval' => $this->anioval, 'gru' => $this->gru, 'anio' => $this->anio, 'pesodesde' => $this->pesodesde, 'pesohasta' => $this->pesohasta]) !== null) 
    		$this->addError($this->anioval, 'El valor de rodado ya existe.');
    	
    }
    
    public function validarSuperposicion(){
    	
    	$sql = 'select count(*) from rodado_val where (('.$this->pesodesde.' BETWEEN pesodesde and pesohasta)';
		$sql .= ' or ('.$this->pesohasta.' between pesodesde and pesohasta)) and anioval='.$this->anioval.' and gru='.$this->gru.' and anio='.$this->anio;
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();
    		
    	if ($count > 0) 
    		$this->addError($this->anioval, 'Existe superposición con el rango ingresado.');
    	
    }*/

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'anioval' => 'Anio Valuatorio',
            'gru' => 'Grupo',
            'anio' => 'Anio',
            'pesodesde' => 'Peso desde',
            'pesohasta' => 'Peso hasta',
            'valor' => 'Valor',
            'fchmod' => 'Fchmod',
            'usrmod' => 'Usrmod',
        ];
    }
    
   public function buscarRodadoVal($anio_val,$cat,$anio){
    	
 		if($anio_val==""){
			$anio_val = "anioval!=0";
 		}else{
 			$anio_val = "anioval=".$anio_val;
 		}
 		
 		if($cat==""){
 			$cat = "gru!=0";
 		}else{
 			$gru = Yii::$app->db->createCommand('select gru from rodado_tcat where cod='.$cat)->queryScalar();	
 			$cat = "gru=".$gru;
 		}
 		
 		if($anio==""){
			$anio = "anio!=0";
 		}else{
 			$anio = "anio=".$anio;
 		}
 		
    	$sql = "SELECT rodado_val.anioval ,rodado_val.gru,rodado_val.anio,rodado_val.pesodesde,rodado_val.pesohasta,rodado_val.valor" .
    		   ",usr.nombre || ' - ' || to_char(rodado_val.fchmod,'dd/mm/yyyy') as fchmod ".
    		   "FROM rodado_val,sam.sis_usuario usr " .
    		   "WHERE rodado_val.usrmod = usr.usr_id AND ".$anio_val." AND ".$anio." AND ".$cat;
 				
 		$pag = "SELECT count(*) ".
    		   "FROM rodado_val,sam.sis_usuario usr " .
    		   "WHERE rodado_val.usrmod = usr.usr_id AND ".$anio_val." AND ".$anio." AND ".$cat;
    		   
		$count = Yii::$app->db->createCommand($pag)->queryScalar();		
		
		$dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
            'pagination'=> [
			'pageSize'=>(int)$count
			]
		]);
        return $dataProvider;

  	
    }
    
    public function CreateRodadoVal()
    {
    	if (RodadoVal::findOne(['anioval' => $this->anioval, 'gru' => $this->gru, 'anio' => $this->anio, 'pesodesde' => $this->pesodesde, 'pesohasta' => $this->pesohasta]) !== null){ 
    		$this->addError($this->anioval, 'El valor de rodado ya existe.');
    		return false;
    	}
    		
		$sql = 'select count(*) from rodado_val where (('.$this->pesodesde.' BETWEEN pesodesde and pesohasta)';
		$sql .= ' or ('.$this->pesohasta.' between pesodesde and pesohasta)) and anioval='.$this->anioval.' and gru='.$this->gru.' and anio='.$this->anio;
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();
    		
    	if ($count > 0){ 
    		$this->addError($this->anioval, 'Existe superposición con el rango ingresado.');
    		return false;
    	}
    		
    	$sql = 'insert into rodado_val values('.$this->anioval.','.$this->gru.','.$this->anio.','.$this->pesodesde.','.$this->pesohasta.','.$this->valor.',current_timestamp,'.Yii::$app->user->id.')';
    	
    	return Yii::$app->db->createCommand($sql)->execute() > 0;
    }
    
    public function UpdateRodadoVal()
    {
    	$sql = 'update rodado_val set valor='.$this->valor.',usrmod='.Yii::$app->user->id.',fchmod=current_timestamp';
    	$sql .=' where anioval='.$this->anioval.' and anio='.$this->anio.' and gru='.$this->gru.' and pesodesde='.$this->pesodesde.' and pesohasta='.$this->pesohasta;
    	
    	return Yii::$app->db->createCommand($sql)->execute() > 0;
    }
    
}
