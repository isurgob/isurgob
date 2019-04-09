<?php

namespace app\controllers\ctacte;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\models\ctacte\ResolTabla;
use app\utils\db\utb;

/**
 * ResolController implements the CRUD actions for Rubro model.
 */		  
class ResoltablaController extends Controller
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

    /**
     * Creates a new Rubro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     
    public function actionCreate($resol_id){
	
		$model= new ResolTabla();
		$model->setScenario('insert');
		
		if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->grabar())
			return $this->redirect(['//ctacte/resol/view', 'id' => $model->resol_id, 'm' => 1]);
		
		$columnas= $model->columnas;
		
		return $this->render('//ctacte/resol/resol_tabla/_form', ['model' => $model, 'resol_id' => $resol_id, 'columnas' => $columnas, 'consulta' => 0]);
	}

    /**
     * Updates an existing Rubro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
	public function actionUpdate($resol_id, $tabla_id){

		$model= ResolTabla::findOne(['resol_id' => $resol_id, 'tabla_id' => $tabla_id]);
		if($model !== null) $model->setScenario('update');
		
		if($model !== null && Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->grabar())
			return $this->redirect(['//ctacte/resol/view', 'id' => $model->resol_id, 'm' => 1]);
			
		if($model === null) $model= new ResolTabla();
		return $this->render('//ctacte/resol/resol_tabla/_form', ['model' => $model, 'resol_id' => $resol_id, 'columnas' => $model->columnas, 'consulta' => 3]);
	}

    /**
     * Deletes an existing Rubro model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     
    public function actionDelete($resol_id, $tabla_id){

		$model= ResolTabla::findOne(['resol_id' => $resol_id, 'tabla_id' => $tabla_id]);
		
		if($model !== null) $model->setScenario('delete');
		
		if(Yii::$app->request->isPost && $model !== null && $model->borrar())
			return $this->redirect(['//ctacte/resol/view', 'id' => $model->resol_id, 'm' => 1]);
		
		if($model === null) $model= new ResolTabla();
		return $this->render('//ctacte/resol/resol_tabla/_form', ['model' => $model, 'resol_id' => $resol_id, 'columnas' => $model->columnas, 'consulta' => 2]);
	}    
}
