<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;

class MejoraTCalculo extends \yii\db\ActiveRecord{


	public function __construct(){

	}

	public static function tableName(){

		return 'mej_tcalculo';
	}

	public function rules(){

		
	}

	public function scenarios(){

		return [
			
		];
	}

	public function attributeLabels(){

		return [
			
		];
	}

	public function beforeValidate(){

		return true;
	}

	public function afterValidate(){

		
	}

	public function afterFind(){

		
	}

}
