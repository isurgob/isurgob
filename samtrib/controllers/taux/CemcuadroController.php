<?php
/**
 * Controlador utilizado para la gestion de medios de pago.
 */

namespace app\controllers\taux;

use Yii;
use app\models\taux\CemCuadro;
use yii\web\Controller;
use app\utils\db\utb;
use app\utils\db\Fecha;

class CemcuadroController extends Controller {

    
    public function actionIndex(){

		return $this->render('index',[
					'cuadros' => CemCuadro::getCuadros(),
					'mensaje'   => Yii::$app->session->getFlash( "mensaje", "" )
				]
			);
    }
	
	public function viewCuadro( $cua_id, $scenario, $idModal, $pjaxActualizar ){

        $model = CemCuadro::findOne([ 'cua_id' => $cua_id ]);
		if ( $model == null )
			$model = new CemCuadro();
			
		$model->setScenario( $scenario );	
		
		if( Yii::$app->request->get( '_pjax', '' ) == "#pjaxGrillaCuerpos" ){
			$scenario = Yii::$app->request->get( 'cue_scenario', $scenario );
			$cuerpoAction = intVal(Yii::$app->request->get( 'cue_action', 0 ));
			$cuerpoId = Yii::$app->request->get( 'cue_id', '' );
			$cuerpoNombre = Yii::$app->request->get( 'cue_nombre', '' );
			$model->cuerpos = unserialize(urldecode(stripslashes($_GET['cue_cuerpos']))); // lo envío como array
			$model->grabarCuerpo( $cuerpoId, $cuerpoNombre, $cuerpoAction );
		}

        return $this->render( '_cuadro', [
            'model'             => $model,
			'tipos'				=> utb::getAux('cem_tipo', 'cod', 'nombre', 2),
            'scenario'          => $scenario,
            'idModal'           => $idModal,
            'idPjaxAActualizar' => $pjaxActualizar
        ]);
    }
	
	public function actionGrabarcuadro(){
	
		$model = new CemCuadro();
		$model->setScenario( Yii::$app->request->post( 'txScenario', "" ) );
		
		if( $model->load(Yii::$app->request->post()) and $model->validate() ){
			$model->cuerpos = unserialize(urldecode(stripslashes($_POST['arrayCuerpos']))); // lo envío como array
			
			if ( $model->Grabar() ){
				Yii::$app->session->setFlash( "mensaje", "Los datos se grabaron correctamente" );
                return $this->redirect([ 'index' ]);
			}
		}
		
		$devolver = [ 'error' => $model->getErrors() ];
		
		return json_encode($devolver); 
		
	}

}

?>
