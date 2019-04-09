<?php

namespace app\controllers\config;

use Yii;
use app\models\config\Config;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\utils\db\utb;

class ConfigController extends \yii\web\Controller
{

	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [

                ],
            ],
        ];
    }

	 public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

    	return true;
    }

    public function actionIndex($accion=1, $mensaje = '', $error='')
    {
    	$model =  new Config();

        $model=Config::find()->one();

		if(!isset($model))$model='';

		return $this->render('index', [
            'model' => $model,
            'mensaje' => $mensaje,
			'accion' => $accion
        ]);
    }

    public function actionModificarconfig(){

		$model = new Config();
     	$esPost= Yii::$app->request->isPost;

		if($esPost && $model->load(Yii::$app->request->post()) && $model->grabar())
     		return $this->redirect(['index', 'accion' => 1, 'mensaje' => 'update']);

        return $this->render('index', [
			'model'		=> $model,
			'accion'	=> ( $esPost ? 3 : 1 ),
			'mensaje'	=> '',
		]);

	}
}
