<?php

namespace app\controllers\config;

use Yii;
use app\models\config\Inmnom;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\utils\db\utb;

class InmnomController extends \yii\web\Controller
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
        	
        $model = new Inmnom();
        
		return $this->render('index', [
            'model' => $model,
			'action' => 1,
			'mensaje' => $mensaje,
        ]);
    }
     
    public function actionUpdate()
    {	
		
		$model = new Inmnom();
     	
     	if ( $model->load(Yii::$app->request->post()) )
     	{
     		if ( $model->grabar() )
     			return $this->redirect(['index', 'mensaje' => 'a']);
     		
     	} else 
     	{
     		$model = new Inmnom();
     	}
        
        return $this->render('index', [
        	'model' => $model,
        	'accion' => 3,
			'action' => 3,
        ]);

			}  
		}
