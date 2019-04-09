<?php

namespace app\controllers\config;

use Yii;
use app\models\config\Config_ddjj;
use app\models\ctacte\Ddjj;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use app\utils\db\utb;

class Config_ddjjController extends \yii\web\Controller{

	const CONST_MENSAJE 	= 'config_ddjj_const_mensaje';

	public function behaviors(){

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [

                ],
            ],
        ];
    }

    public function actionIndex( $id = 0 ){

		return $this->view( $id, 1 );
    }

	public function actionCreate( $id = 0 ){

		return $this->view( 0, 0 );
    }

    public function actionUpdate( $id = 0 ){

		return $this->view( $id, 3 );

	}

	public function view( $id, $action  ){

		if( Yii::$app->request->isPjax ){

			if( Yii::$app->request->get( '_pjax', '' ) == '#config_ddjj_pjaxDatos' ){

				$id = Yii::$app->request->get( 'trib_id', 0 );
			}
		}

		$model 		= $this->findModel( $id );

		if ( $model->load( Yii::$app->request->post() ) ){

			$action == Yii::$app->request->post( 'txAction', 1 );

     		if ( $model->grabar( $action ) ){

				Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

				return $this->redirect([ 'index', 'id' => $model->trib_id ]);
			}

     	}

		$dpTributos = new ArrayDataProvider([
			'allModels' => Config_ddjj::getTributos(),
		]);

		if( $action == 0 ){

			$tributos = Config_ddjj::getTributosNuevo();

		} else{

			$tributos = Config_ddjj::getTributosModificar();
		}

		return $this->render('index', [

            'model' 	=> $model,

			'dpTributos'	=> $dpTributos,
			'tributos'		=> $tributos,

			'itemMulta'	=> Config_ddjj::getItemMulta(),
			'itemBasico'	=> Config_ddjj::getItemBasico(),
			'itemRete'	=> Config_ddjj::getItemRetencion(),
			'itemBonif'	=> Config_ddjj::getItemBonif(),
			'itemSaldo'	=> Config_ddjj::getItemSaldo(),

			'action' 	=> $action,
			'mensaje' 	=> $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),

        ]);
	}

	public function findModel( $id = 0 ){

		$model = Config_ddjj::findOne( $id );

		if( $model == null ){

			$model = new Config_ddjj();

		}

		return $model;
	}

	public function getMensaje( $id ){

		switch( $id ){

			case 1:

				$title = 'Los datos se grabaron correctamente.';
				break;

			default:

				$title = '';
				break;
		}

		return $title;

	}
}
