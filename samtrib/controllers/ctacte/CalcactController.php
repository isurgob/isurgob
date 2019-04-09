<?php

namespace app\controllers\ctacte;

use Yii;
use yii\web\Controller;
use app\models\ctacte\CalcAct;
use app\utils\db\utb;


class CalcactController extends Controller
{
	public function beforeAction($action)
    {
    	/*$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }*/
    	
    	return true;
    } 

    public function actionIndex()
    {
        $model = new CalcAct();
		
		return $this->render('index', [
			'dpLista' => $model->dpListar(),
			'mensaje' => Yii::$app->session->getFlash( "mensaje", "" ),
			'arrayTributos' => utb::getAux( "trib", "trib_id", "nombre", 0, "est='A'" )
        ]);
    }
	
	public function actionDatos( $scenario = 'consultar', $fchdesde = null, $fchhasta = null )
	{
		$model = CalcAct::findOne([ 'fchdesde' => $fchdesde, 'fchhasta' => $fchhasta ]);
		
		if ( $model == null )
			$model = new CalcAct();
			
		$model->setScenario( $scenario );
	 
		if ( $model->load(Yii::$app->request->post()) &&  $model->validate() ) {
			if ( $model->Grabar() ){
				Yii::$app->session->setFlash( "mensaje", "Los datos se grabaron correctamente" );
				return $this->redirect(['index']);
			}	
		}
		
		if ( $model->hasErrors() ) {			
			return json_encode( $model->getErrors() );
		}
	 
		return $this->renderAjax('_form', [
			'model' => $model,
		]);
	}
	
	public function actionCalcular(){
	
		$tributo = intVal( Yii::$app->request->post( "tributo", 0 ) );
		$fchdvenc = Yii::$app->request->post( "fchvenc", "" );
		$fchpago = Yii::$app->request->post( "fchpago", "" );
		$nominal = floatVal( Yii::$app->request->post( "nominal", 0 ) );
		$montocalculado = 0;
		
		$error = CalcAct::Calcular( $tributo, $fchdvenc, $fchpago, $nominal, $montocalculado );
		
		return json_encode([ 'error' => $error, 'montocalculado' => $montocalculado ]);
		
	}

}