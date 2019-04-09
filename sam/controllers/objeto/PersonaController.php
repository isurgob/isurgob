<?php

namespace app\controllers\objeto;

use Yii;
use yii\web\Controller;
use app\models\objeto\PersonaAltaRapida;


class PersonaController extends Controller{

	public function actionAltarapida(){
		
		$model = new PersonaAltaRapida();

   		if ( $model->load(Yii::$app->request->post()) and $model->validate() ){
			
			$model->Grabar();
		}
		
		$devolver = [
    			'error'	=> $model->getErrors(),
				'objeto' => $model->obj_id
    		];
			
    	return json_encode($devolver); 
	}
	
}
?>
