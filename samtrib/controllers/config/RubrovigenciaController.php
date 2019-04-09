<?php

namespace app\controllers\config;

use Yii;
use app\models\config\RubroVigencia;
use app\models\config\Rubro;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;

ini_set("display_errors", "on");
error_reporting(E_ALL);
/**
 * RubrovigenciaController implements the CRUD actions for RubroVigencia model.
 */
class RubrovigenciaController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
     * Lists all RubroVigencia models.
     * @return mixed
     */
    public function actionIndex($m=0,$model='',$error='')
    {
    	
        $dataProvider = new ActiveDataProvider([
            'query' => RubroVigencia::find(),
        ]);
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RubroVigencia model.
     * @param integer $rubro_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @return mixed
     */
    public function actionView($rubro_id, $perdesde, $perhasta)
    {
        return $this->render('view', [
            'model' => $this->findModel($rubro_id, $perdesde, $perhasta),
        ]);
    }

    /**
     * Creates a new RubroVigencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
		$modelVigencia= new RubroVigencia();
        $modelVigencia->rubro_id= $id;
        
        $consulta= 0;
        
        if(Yii::$app->request->isPost){
        	
        	$modelVigencia->setScenario('insert');
        	if($modelVigencia->load(Yii::$app->request->post()) && $modelVigencia->grabar())
        		return $this->redirect(['//config/rubro/update', 'id' => $modelVigencia->rubro_id]);
        }

        return $this->render('_form', ['model' => $modelVigencia, 'consulta' => $consulta]);
	}

    /**
     * Updates an existing RubroVigencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $rubro_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @return mixed
     */
    public function actionUpdate($id, $perdesde, $perhasta){

		$model= RubroVigencia::findOne(['rubro_id' => $id, 'perdesde' => $perdesde, 'perhasta' => $perhasta]);
		
		if(Yii::$app->request->isPost && $model !== null){
			
			$model->setScenario('update');
			if($model->load(Yii::$app->request->post()) && $model->grabar())
				return $this->redirect(['//config/rubro/update', 'id' => $model->rubro_id, 'm' => 1]);
		}
		
		if($model === null) $model= new RubroVigencia();
		return $this->render('_form', ['model' => $model, 'consulta' => 3]);
    }

    /**
     * Deletes an existing RubroVigencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $rubro_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @return mixed
     */
    public function actionDelete($id, $perdesde, $perhasta){
		
		$model= RubroVigencia::findOne(['rubro_id' => $id, 'perdesde' => $perdesde, 'perhasta' => $perhasta]);
		
		if(Yii::$app->request->isPost && $model !== null){
			
			$model->setScenario('delete');
			
			if($model->borrar())
				return $this->redirect(['//config/rubro/update', 'id' => $model->rubro_id, 'm' => 1]);
		}
		
		if($model === null) $model= new RubroVigencia();
		return $this->render('_form', ['model' => $model, 'consulta' => 2]);
    }

    /**
     * Finds the RubroVigencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $rubro_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @return RubroVigencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($rubro_id, $perdesde, $perhasta)
    {
        if (($model = RubroVigencia::findOne(['rubro_id' => $rubro_id, 'perdesde' => $perdesde, 'perhasta' => $perhasta])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    protected function Mensajes($id)
    {
    	switch ($id) {
    		case 1: 
    			$mensaje = 'Datos Grabados.';
    			break;
    		case 2: 
    			$mensaje = 'Datos Borrados.';
    			break;
    		default: 
    			$mensaje = '';
    	}
    	
    	return $mensaje;  
    }
    
    
}
