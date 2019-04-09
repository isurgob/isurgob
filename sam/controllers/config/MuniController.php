<?php

namespace app\controllers\config;

use Yii;
use app\models\config\Muni;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use yii\web\UploadedFile;

class MuniController extends \yii\web\Controller
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
	
    public function actionIndex( $mensaje = '' )
    {
        
        if ( $mensaje == 'a' )
        	$mensaje = 'Los datos se modificaron correctamente.';
        	
        $model = Muni::find()->one();
        
		return $this->render('index', [
            'model' => $model,
			'action' => 1,
			'mensaje' => $mensaje,
        ]);
    }
     
    public function actionUpdate()
    {	
		
		$model = Muni::find()->one();
     	
     	if ( $model->load(Yii::$app->request->post()) )
     	{
     		$model->logo = UploadedFile::getInstance( $model, 'logo' );
     		$model->logo_grande = UploadedFile::getInstance( $model, 'logo_grande' );
     		$model->logo_talon = UploadedFile::getInstance( $model, 'logo_talon' );
        
     		if ( $model->grabar() )
     			return $this->redirect(['index', 'mensaje' => 'a']);
     		
     	} else 
     	{
     		$model = Muni::find()->one();
     	}
        
        return $this->render('index', [
        	'model' => $model,
        	'action' => 3,
        	'mensaje' => '',
        ]);

	}
	
	public function actionImagen($logo){
		
		$model= new Muni();
		
		$imagen= $model->getImagen($logo);
		
		if($imagen !== null) header('Content-type: image/jpeg');
		else Yii::$app->response->setStatusCode(404, 'Imagen no disponible');
		
		echo $imagen;
	}  
}
		
		
