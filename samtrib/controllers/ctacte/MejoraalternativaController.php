<?php

namespace app\controllers\ctacte;

use Yii;
use yii\web\Controller;
use app\models\ctacte\MejoraPlan;
use app\models\ctacte\MejoraAlternativa;
use app\models\ctacte\MejoraObra;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;


class MejoraalternativaController extends Controller
{

	public function beforeAction($action){
		
		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
		
		return true;
	}
	
	public function actionIndex( $plan = 0 ){
	
		$modelPlan = MejoraPlan::findOne([ 'plan_id' => $plan ]);
		if ( $modelPlan == null ) $modelPlan = new MejoraPlan();
		
		$model = MejoraAlternativa::findOne([ 'plan_id' => $plan ]);
		if ($model == null ) $model = new MejoraAlternativa();
		
		$model->plan_id = $plan;
		$model->addErrors( Yii::$app->session->getFlash( 'array_error', [] ) );
		
		if( Yii::$app->request->isPost ){
		
			$model->setScenario( 'generar' );
			
			if( $model->load( Yii::$app->request->post() ) ){
					
				if ( $model->validate() and $model->Grabar() ) {
					Yii::$app->session->setFlash( 'mensaje', 'Los datos se grabaron correctamente' );
				
					return $this->redirect(['index', 'plan' => $model->plan_id ]);
				}	
				
			}
		
		}
		
		return $this->render('//ctacte/cmejora/alternativa/index', [
					'modelPlan' => $modelPlan,
					'model' => $model,
					
					'mensaje' => Yii::$app->session->getFlash( 'mensaje', '' )
				]);	
				
	}
	
	public function actionBorrar ( $plan ){
	
		$model = new MejoraAlternativa();
		
		if ( $model->Borrar( $plan ) ){
			
			Yii::$app->session->setFlash( 'mensaje', 'La Alternativa fue eliminada' );
			return $this->redirect(['//ctacte/mejoraplan/index', 'id' => $plan ]);
			
		}else {
		
			Yii::$app->session->setFlash( 'array_error', $model->getErrors() );
			return $this->redirect(['index', 'plan' => $plan ]);
		
		}
	
	}
	
	public function actionImprimir( $plan )
    {
    	$modelPlan = MejoraPlan::findOne([ 'plan_id' => $plan ]);
		$modelAlternativa = MejoraAlternativa::findOne([ 'plan_id' => $plan ]);
		$modelObra = MejoraObra::findOne([ 'obra_id' => $modelPlan->obra_id ]);
    	$texto = $modelObra->textoObra( $plan );
		
    	$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
    	$pdf->content = $this->renderPartial('//reportes/mejalternativa',[
				'alternativa' => $modelAlternativa, 
				'texto' => $texto,
				'plan' => $modelPlan,
				'obra' => $modelObra
			]);
    	
    	return $pdf->render();
    }
}
