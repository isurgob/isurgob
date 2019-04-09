<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\CalcMulta;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;

/**
 * MultaController implements the CRUD actions for Multa model.
 */
class CalcMultaController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','get'],
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

    /**
     * Lists all Multa models.
     * @return mixed
     */
    public function actionIndex($m=0)
    {       
        $count = Yii::$app->db->createCommand('Select count(*) From calc_multa')->queryScalar();
                  
        $sql = 'Select * from v_calc_multa Order By Trib_id, PerDesde '; 
        
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key'=>'trib_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>5,
			],
        ]); 

        return $this->render('index', [
            'dataProvider' => $dataProvider, 'mensaje' => $this->Mensajes($m),
        ]);
    }

    /**
     * Displays a single Multa model.
     * @param integer $trib_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @param integer $tipo
     * @param string $montodesde
     * @param string $montohasta
     * @return mixed
     */
    public function actionView($trib_id, $perdesde, $perhasta, $tipo, $montodesde, $montohasta)
    {
        return $this->render('view', [
            'model' => $this->findModel($trib_id, $perdesde, $perhasta, $tipo, $montodesde, $montohasta),
        ]);
    }

    /**
     * Creates a new Multa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CalcMulta();

        if (isset($error) == null) $error = ''; 
        
        if ($model->load(Yii::$app->request->post())) {
       		
       		$error = $model->grabar(); 
       		if ($error == "") {
            	return $this->redirect(['index', 'm' => 1]);
        	} else {        	        	        	 
	            return $this->render('create', [
	                'model' => $model,
	                'error' => $error,
	            ]);
        	}
        } else {
	            return $this->render('create', [
	                'model' => $model,
	                'error' => $error,
	            ]);
        	
        }
    }

    /**
     * Updates an existing Multa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $trib_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @param integer $tipo
     * @param string $montodesde
     * @param string $montohasta
     * @return mixed
     */
    public function actionUpdate($trib_id, $perdesde, $perhasta, $tipo, $montodesde, $montohasta)
    {
        $model = $this->findModel($trib_id, $perdesde, $perhasta, $tipo, $montodesde, $montohasta);
		
		if (isset($error) == null) $error = ''; 
			
        if ($model->load(Yii::$app->request->post())) {
            $error = $model->grabar();
 
            if ($error == "") {
            	return $this->redirect(['index', 'm' => 1]);
            	
        	} else {        	        	        	 
	            return $this->render('update', [
	                'model' => $model,
	                'error' => $error,
	            ]);
        	}
        } else {
           	        	
            return $this->render('update', [
                'model' => $model,
                'error' => $error,
                
            ]);
        }
    }

    /**
     * Deletes an existing Multa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $trib_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @param integer $tipo
     * @param string $montodesde
     * @param string $montohasta
     * @return mixed
     */
    public function actionDelete($accion,$trib_id, $perdesde, $perhasta, $tipo, $montodesde, $montohasta)
    {
        $model = $this->findModel($trib_id, $perdesde, $perhasta, $tipo, $montodesde, $montohasta);

        if ($accion==1 && $model->borrar()) 
        {
        	return $this->redirect(['index', 'm' => 1]);
        }
        else {
            return $this->render('delete', [
                'model' => $model
            ]);
        }
       
    }
    
    /**
     * Finds the Multa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $trib_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @param integer $tipo
     * @param string $montodesde
     * @param string $montohasta
     * @return Multa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($trib_id, $perdesde, $perhasta, $tipo, $montodesde, $montohasta)
    {
        if (($model = CalcMulta::findOne(['trib_id' => $trib_id, 'perdesde' => $perdesde, 'perhasta' => $perhasta, 'tipo' => $tipo, 'montodesde' => $montodesde, 'montohasta' => $montohasta])) !== null) {
            $model->aniodesde = substr($model->perdesde,0,4);
        	$model->cuotadesde = substr($model->perdesde,4,3);
        	        	
        	$model->aniohasta = substr($model->perhasta,0,4);
        	$model->cuotahasta = substr($model->perhasta,4,3);
        	
        	$model->modif = utb::getFormatoModif($model->usrmod,$model->fchmod);
        	
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function Mensajes($id)
    {
    	switch ($id) {
    		case 1: 
    			$mensaje = 'Datos Grabados';
    			break;
    		default: 
    			$mensaje = '';
    	}
    	
    	return $mensaje;  
    }
}
