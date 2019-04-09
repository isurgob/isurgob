<?php

namespace app\controllers\taux;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\models\taux\RubroGrupo;
use app\utils\db\Fecha;
use app\utils\db\utb;

class RubrogrupoController extends Controller{

    public function actionIndex(){
		
		$model = new RubroGrupo();
		$nomen_id = Yii::$app->request->post( 'nomen_id', '' );
		
		$dpRubroGrupo = $model->getGrupoRubro( $nomen_id );
		
		return $this->render( 'index', [
					'dpRubroGrupo' => $dpRubroGrupo,
					'mensaje' => Yii::$app->session->getFlash( 'mensaje', '' ),
					'nomecladores' => utb::getAux( "rubro_tnomen", "nomen_id", "nombre", 2 )
				]);
    }
	
	public function rubrogrupo( $cod, $nomen_id, $scenario ){
	
		$model = RubroGrupo::findOne([ 'cod' => $cod, 'nomen_id' => $nomen_id ]);	
		if ( $model == null ) $model = new RubroGrupo();
		$model->setScenario( $scenario );
		
		return Yii::$app->controller->renderPartial( '_form', [
            'model' => $model,
			'nomecladores' => utb::getAux( "rubro_tnomen", "nomen_id" )
        ]);
	}
	
	public function actionGrabarrubrogrupo(){
	
		$model = new RubroGrupo();
		$model->setScenario( Yii::$app->request->post( 'txScenario', "" ) );
		
		if( $model->load(Yii::$app->request->post()) and $model->validate() ){
			if ( $model->Grabar() ){
				Yii::$app->session->setFlash( "mensaje", "Los datos se grabaron correctamente" );
                return $this->redirect([ 'index' ]);
			}
		}
		
		$devolver = [ 'error' => $model->getErrors() ];
		
		return json_encode($devolver); 
		
	}
	
}
