<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;


class Frentistas extends \yii\db\ActiveRecord{	



	public function __construct(){
		
		parent::__construct();
		
		
	}

	public static function tableName(){
		
		return 'v_mej_plan';
	}
	
	public function rules(){
		
		$ret = [];	
		return $ret;
	}
	
	public function scenarios(){
		
		return [];
	}
	
	public function attributeLabels(){
		
		return [];
	}
}
