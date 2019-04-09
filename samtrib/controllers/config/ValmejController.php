<?php

namespace app\controllers\config;

use Yii;
use app\models\config\ValMej;
use yii\web\Controller;
use app\utils\db\utb;


/**
 * VajmejController implements the CRUD actions for ValMej model.
 */
class ValmejController extends Controller
{
    
    public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
		
		if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
    	
    	return true;
    } 
	
	public function actionIndex(){
	
		$model = new ValMej();
		$cond = '';
		
		$model->aniodesde = intVal( Yii::$app->request->post( 'filtroAnio', date('Y') ) );
		if ( $model->aniodesde > 0 )
			$cond .= "substr(perdesde::text,0,5)::integer = $model->aniodesde";
		
		$dataProvider = $model->listadoValMej( $cond );
		
		return $this->render('index', [
            'model' => $model,
			'dataProvider' => $dataProvider,
			'mensaje' => Yii::$app->session->getFlash( 'mensaje', '' )
        ]);
	}
	
	public function FormularioValMej( $scenario, $cat, $form, $perdesde ){
	
		$model = ValMej::findOne([ 'cat' => $cat, 'form' => $form, 'perdesde' => $perdesde ]);
		if ( $model == null )
			$model = new ValMej();
			
		$model->setScenario( $scenario );
		
		return $this->render('_form', [
				'model'	=> $model,
				'existe_inm_mej_tcat' => ValMej::getExisteInmMejTCat()
			]);
	}
	
	public function actionValmejabm(){   
	
		$model = new ValMej();
		$model->scenario = Yii::$app->request->post( 'scenario', "" );
				
		if( $model->load(Yii::$app->request->post()) and $model->validate() and $model->Grabar() ){
			$devolver = [ 'error' => "" ];
			Yii::$app->session->setFlash( 'mensaje', 'Los datos se grabaron correctamente.' );
		}else {
			$devolver = [ 'error' => $model->getErrors() ];
		}
		
		return json_encode($devolver); 
	}

}
