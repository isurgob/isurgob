<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\ResolLocal;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\utils\db\utb;

class ResolocalController extends Controller
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

    public function actionCreate($resol_id)
    {
        $model = new ResolLocal();
        $model->resol_id= $resol_id;

		$model->setScenario('insert');
    	if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->grabar())
    		return $this->redirect(['//ctacte/resol/view', 'id' => $resol_id, 'm' => 1]);
	        
	        
		return $this->render('//ctacte/resol/resol_local/_form', ['model' => $model, 'resol_id' => $resol_id, 'consulta' => 0]);
    }

    public function actionUpdate($resol_id, $varlocal){

		$model = ResolLocal::findOne(['resol_id' => $resol_id, 'varlocal' => $varlocal]);
        
        if(Yii::$app->request->isPost && $model !== null){
        	
        	$model->setScenario('update');
        	
        	if($model->load(Yii::$app->request->post()) && $model->grabar())
        		return $this->redirect(['//ctacte/resol/view', 'id' => $model->resol_id, 'm' => 1]);
        }

		if($model === null){
			$model= new ResolLocal();
			$model->resol_id= $resol_id;
		}
		
       	return $this->render('//ctacte/resol/resol_local/_form', ['model' => $model, 'resol_id' => $resol_id, 'consulta' => 3]);
	}

    public function actionDelete($resol_id, $varlocal){
		
		
        $model = ResolLocal::findOne(['resol_id' => $resol_id, 'varlocal' => $varlocal]);
        
        if(Yii::$app->request->isPost && $model !== null && $model->borrar())
			return $this->redirect(['//ctacte/resol/view', 'id' => $model->resol_id, 'm' => 1]);
		
		if($model === null) $model= new ResolLocal();
		return $this->render('//ctacte/resol/resol_local/_form', ['model' => $model, 'resol_id' => $resol_id, 'consulta' => 2]);
	}
}