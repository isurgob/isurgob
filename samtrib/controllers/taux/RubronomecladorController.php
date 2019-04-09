<?php

namespace app\controllers\taux;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\models\taux\RubroNomeclador;
use app\utils\db\Fecha;
use app\utils\db\utb;

class RubronomecladorController extends Controller{

    public function actionIndex(){
		
		$model = new RubroNomeclador();
		
		$dpNomecladores = $model->getNomecladores();
		
		return $this->render( 'index', [
					'dpNomecladores' => $dpNomecladores
				]);
    }
	
	public function nomeclador( $nomen_id, $tobj ){
	
		$model = RubroNomeclador::findOne([ 'nomen_id' => $nomen_id, 'tobj' => $tobj ]);	
		if ( $model == null ) $model = new RubroNomeclador();
				
		return Yii::$app->controller->renderPartial( '_form', [
            'model' => $model,
			'tipoObjeto' => utb::getAux( "objeto_tipo" )
        ]);
	}
	
}
