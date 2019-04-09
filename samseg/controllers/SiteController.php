<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\utils\db\utb;
use app\models\ConvertForm;

use yii\data\SqlDataProvider;

/**
 * Site controller
 */
class SiteController extends Controller {
		
    /**
     * @inheritdoc
     */
	public function behaviors()
	{
	    return [
	        'access' => [
	            'class' => AccessControl::className(),
	            'only' => ['logout', 'signup', 'about'],
	            'rules' => [
	                [
	                    'actions' => ['login', 'signup', 'error','logout'],
	                    'allow' => true,
	                    'roles' => ['?'],
	                ],
	                [
	                    'actions' => ['about', 'index','logout'],
	                    'allow' => true,
	                    'roles' => ['@'],
	                ],
	            ],
	        ],
	        'verbs' => [
	            'class' => VerbFilter::className(),
	            'actions' => [
	                'logout' => ['get'],
	            ],
	        ],
	    ];
	}


	public function beforeAction($action) {
	    if (!parent::beforeAction($action)) {
	        return false;
	    }
	 
	    $operacion = str_replace("/", "-", Yii::$app->controller->route);
	 
	    $permitirSiempre = ['site-captcha', 'site-signup', 'site-index', 'site-error'];
	 
	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }
	 
		// Si el usuario no esta logueado o tiene la clave vacia no admito
		if (\Yii::$app->user->isGuest) {
			echo $this->render('nopermitido');
			return false;
		} else {
			//if (isset(Yii::$app->session['user_sinclave'])) {
				if (Yii::$app->session['user_sinclave'] == 1) {
					//echo $this->render('cbioclave');
					$this->redirect(['../sam/site/cbioclave']);
					return false;
				}
			//} else
			//	return false;
		}
		
	    /*if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('nopermitido');
	        return false;
	    }*/	    
	    
	    if ($operacion == 'site-auxedit'){
		    $t = (isset($_GET['t']) ? $_GET['t'] : 0);
		    		    
		    $procesotaux = utb::getCampo('sam.tabla_aux','cod='.$t,'accesocons');
		    	
		    if (!utb::getExisteProceso($procesotaux)){
		    	echo $this->render('nopermitido');
		    	return false;
		    }
	    }
	    	    
	    return true;
	}

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'download' => [
                'class' => 'yii\export2excel\DownloadAction',
            ],
        ];
    }


    public function actionIndex()
    {
        Yii::$app->session['sis_id'] = 1;
		return $this->render('index');
    } 
	
	 public function actionConfig()
    {
		return $this->render('config');
    }   
    

}
