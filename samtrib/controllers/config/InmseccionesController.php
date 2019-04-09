<?php

namespace app\controllers\config;

use Yii;
use app\models\config\InmSecciones;
use yii\web\Controller;
use app\utils\db\utb;



class InmseccionesController extends Controller
{
    
    public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
		
		$permitirSiempre = ['config-inmsecciones-obteners2'];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }
		
		if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
    	
    	return true;
    } 
	
	public function actionIndex(){
	
		$model = new InmSecciones();
		
		$model->s1 = Yii::$app->request->post( 's1', "" );
		$model->s2 = Yii::$app->request->post( 's2', 0 ); // asigno 0 para que no liste los s3 hasta que no se seleccione s2
		$model->s3 = Yii::$app->request->post( 's3', "" );

		$scenario = Yii::$app->request->post( 'scenario', "" );
		
		$arrayS1 = $model->getArrayS1();
		$dataProviderS2 = $model->dataProviderS2();
		$dataProviderS3 = $model->dataProviderS3();

		return $this->render('index', [
            'model' => $model,
			'arrayS1' => $arrayS1,
			'dataProviderS2' => $dataProviderS2,
			'dataProviderS3' => $dataProviderS3,
			'scenario' => $scenario,
			'mensaje'   => Yii::$app->session->getFlash( "mensaje", "" )
        ]);
	}
	
	public function viewSecciones( $s1, $s2, $s3, $scenario, $idModal, $pjaxActualizar ){

        $model = InmSecciones::findModel( $scenario, $s1, $s2, $s3 );
		$model->setScenario( $scenario );	
		
		return $this->render( '_secciones', [
            'model'             => $model,
			'arrayS1'			=> $model->getArrayS1(),
			'arrayS2'			=> $model->getArrayS2(),
            'scenario'          => $scenario,
            'idModal'           => $idModal,
            'idPjaxAActualizar' => $pjaxActualizar
        ]);
    }

    public function actionGrabarseccion(){
	
		$scenario = Yii::$app->request->post( 'txScenario', "" );
		$model = InmSecciones::findModel( $scenario, "", "", "" );
		$model->setScenario( $scenario );
		
		if( $model->load(Yii::$app->request->post()) and $model->validate() ){
			
			if ( $model->Grabar() ){
				Yii::$app->session->setFlash( "mensaje", "Los datos se grabaron correctamente" );
                return $this->redirect([ 'index' ]);
			}
		}
		
		$devolver = [ 'error' => $model->getErrors() ];
		
		return json_encode($devolver); 
		
	}
	
	public function actionObteners2(){
	
		$model = new InmSecciones( "inm_s2" );
		$model->s1 = Yii::$app->request->post( 's1', "" );
		
		return json_encode($model->getArrayS2()); 
		
	}
	
}
