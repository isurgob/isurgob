<?php

namespace app\controllers\taux;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\models\taux\JudiHono;
use app\utils\db\Fecha;
use app\utils\db\utb;

class JudihonoController extends Controller{

    const CONST_MENSAJE         = 'const_mensaje';
    const CONST_MODEL           = 'const_model';
    const CONST_MODEL_ACTION    = 'const_model_action';
	
	public function actionIndex(){
		
		$mostrar_modal  = 0;
		$action = 1;
		$est = "" ;
		$supuesto = 0;
		$deuda_desde = 0;
		$deuda_hasta = 0;
		
		if( Yii::$app->request->isPjax ){

            $est     = Yii::$app->request->get( 'est', '' );
            $supuesto     = Yii::$app->request->get( 'supuesto', '' );
            $deuda_desde     = Yii::$app->request->get( 'deuda_desde', '' );
            $deuda_hasta     = Yii::$app->request->get( 'deuda_hasta', '' );
            $action = Yii::$app->request->get( 'action', 1 );
        }
		
		if( Yii::$app->request->isPost ){

            $model = new JudiHono();

            $action = Yii::$app->request->post( 'txAction', 1 );

            switch( $action ){

                case 0:

                    $model->setScenario( 'insert' );
                    break;

                case 2:

                    $model->setScenario( 'delete' );
                    break;

                case 3:

                    $model->setScenario( 'update' );
                    break;

            }
			
            if( $model->load( Yii::$app->request->post() ) && $model->validate() ){

                if( $model->grabar( $action ) ){

                    Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

                    return $this->redirect([ 'index' ]);

                } else {
					Yii::$app->session->setFlash( self::CONST_MODEL, $model );
                    Yii::$app->session->setFlash( self::CONST_MODEL_ACTION, $action );
					
                    $mostrar_modal = 1;
                }
            } else {
				Yii::$app->session->setFlash( self::CONST_MODEL, $model );
                Yii::$app->session->setFlash( self::CONST_MODEL_ACTION, $action );
                
				$mostrar_modal = 1;
            }
        }
				
		return $this->render( 'index',[
			'dpJudiHono'   => new ArrayDataProvider([
                'allModels' => JudiHono::find()->All(),
                'pagination'    => [
                    'pageSize'  => 100,
                ]
            ]),
			'mensaje' => '',
			'mostrar_modal' => $mostrar_modal,
			'action' => $action,
			'est' => $est,
			'supuesto' => $supuesto,
			'deuda_desde' => $deuda_desde,
			'deuda_hasta' => $deuda_hasta
		]);
    }
	
	 public function judihono( $est, $supuesto, $deuda_desde, $deuda_hasta, $action ){

        $model = JudihonoController::findModel( $est, $supuesto, $deuda_desde, $deuda_hasta );

        return $this->render( '_form', [

            'model'     => Yii::$app->session->getFlash( self::CONST_MODEL, $model ),
            'action'    => Yii::$app->session->getFlash( self::CONST_MODEL_ACTION, $action ),
			'arrayEstado' => utb::getAux('judi_test')
        ]);
    }
	
	private function findModel( $est = "", $supuesto=0, $deuda_desde=0, $deuda_hasta=0 ){

        $model = JudiHono::findOne( $est,$supuesto,$deuda_desde,$deuda_hasta );

        if( $model === null ){

            $model = new JudiHono();

        }

        return $model;
    }
}
