<?php

namespace app\models\config;

use Yii;

/**
 * This is the model class for table "val_mej".
 *
 * @property string $cat
 * @property string $form
 * @property integer $perdesde
 * @property integer $perhasta
 * @property string $valor
 */
class ValCoefMej extends \yii\db\ActiveRecord
{
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'val_coefmej';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat', 'est', 'ant', 'coef'], 'required', 'on' => ['insert','update'] ],
			[['cat', 'est', 'ant'], 'required', 'on' => ['delete'] ],
            [['est', 'ant'], 'integer'],
            [['coef'], 'number'],
            [['cat'], 'string', 'max' => 2],
			
			[
				'cat',
				'existeCoef',
				'on' => ['insert']
			]
        ];
    }
	
	 public function existeCoef()
    {    	    		
    	$sql = "select count(*) from val_coefmej where cat='$this->cat' and est=$this->est and ant=$this->ant";
		$cant = Yii::$app->db->createCommand($sql)->queryScalar();
    	if($cant > 0)
    		$this->addError($this->cat, 'El registro ya existe');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat' => 'Categoria',
            'est' => 'Estado',
            'ant' => 'Antiguedad',
            'coef' => 'Coeficiente'
        ];
    }
	
	public function scenarios()
    {
    	return [
    		'insert' => ['cat', 'est', 'ant', 'coef'],
    		'update' => ['cat', 'est', 'ant', 'coef'],
    		'delete' => ['cat', 'est', 'ant']
    	];
    }
    
    public function Grabar(){
    	
    	$sql = "";
		if ($this->scenario == 'insert')
			$sql = "insert into val_coefmej values('$this->cat',$this->est,$this->ant,$this->coef)";
		elseif ($this->scenario == 'update')
			$sql = "update val_coefmej set coef=$this->coef where cat='$this->cat' and est=$this->est and ant=$this->ant";
		elseif ($this->scenario == 'delete')	
			$sql = "delete from val_coefmej where cat='$this->cat' and est=$this->est and ant=$this->ant";
		
		try{						
			Yii::$app->db->createCommand($sql)->execute();
		}
		catch(\Exception $e){
			$this->addError($this->cat, $e->getMessage());
		}
    }
    
}
