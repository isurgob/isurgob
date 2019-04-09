<?php

namespace app\models\taux;

use Yii;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\data\ArrayDataProvider;


class RubroNomeclador extends \yii\db\ActiveRecord{

    public static function tableName(){

        return 'rubro_tnomen';
    }

    
    public function getNomecladores(){
	
		$sql = "select n.nomen_id, n.tobj, t.nombre tobj_nom, n.nombre, n.perdesde,n.perhasta from rubro_tnomen n 
				inner join objeto_tipo t on n.tobj=t.cod";
				
		$datos = Yii::$app->db->createCommand( $sql )->queryAll();
		
		$dpDatos = new ArrayDataProvider([ 'allModels' => $datos, 'pagination' => false ]);
		
		return $dpDatos;
		
	}
    
}
