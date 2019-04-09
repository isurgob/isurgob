<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\ResolTabla;
use app\models\ctacte\ResolTablaDato;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\utils\db\utb;
/**
 * ResolController implements the CRUD actions for Rubro model.
 */		  
class ResoltabladatoController extends Controller
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

    public function actionCreate($resol_id, $tabla_id){

		$model = new ResolTablaDato();
		$modelTabla= ResolTabla::findOne(['resol_id' => $resol_id, 'tabla_id' => $tabla_id]);
		
		if($modelTabla === null) $modelTabla= ResolTabla();
		$model->cargarNombreParametros($modelTabla->columnas);
		
		$model->tabla_id= $tabla_id;
		$model->setScenario('insert');
		
    	if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){

    		$model->cargarNombreParametros($modelTabla->columnas);
    		if($model->grabar()) return $this->redirect(['//ctacte/resol/view', 'id' => $resol_id, 'm' => 1]);
    	}

		return $this->render('//ctacte/resol/resol_tabla_dato/_form', ['model' => $model, 'modelTabla' => $modelTabla, 'resol_id' => $resol_id, 'tabla_id' => $tabla_id, 'consulta' => 0]);
	}

    public function actionUpdate($resol_id, $tabla_id, $dato_id){

		$model= ResolTablaDato::findOne(['tabla_id' => $tabla_id, 'dato_id' => $dato_id]);
		$modelTabla= ResolTabla::findOne(['resol_id' => $resol_id, 'tabla_id' => $tabla_id]);
		
		if($modelTabla === null) $modelTabla= new ResolTabla();
		
		if(Yii::$app->request->isPost && $model !== null){
			
			$model->setScenario('update');
			if($model->load(Yii::$app->request->post())){
				
				$model->cargarNombreParametros($modelTabla->columnas);
				
				if($model->grabar()) return $this->redirect(['//ctacte/resol/view', 'id' => $resol_id]);
			}
				
		}
		
		if($model === null) $model= new ResolTablaDato();
		$model->cargarNombreParametros($modelTabla->columnas);		
		
		return $this->render('//ctacte/resol/resol_tabla_dato/_form', ['model' => $model, 'modelTabla' => $modelTabla, 'resol_id' => $resol_id, 'tabla_id' => $tabla_id, 'consulta' => 3]);
	}
    
    public function actionDelete($resol_id, $tabla_id, $dato_id){
	
		$model= ResolTablaDato::findOne(['tabla_id' => $tabla_id, 'dato_id' => $dato_id]);
		$modelTabla= ResolTabla::findOne(['resol_id' => $resol_id, 'tabla_id' => $tabla_id]);
		
		if(Yii::$app->request->isPost && $model !== null && $model->borrar())
				return $this->redirect(['//ctacte/resol/view', 'id' => $resol_id, 'm' => 1]);
		
		if($model === null) $model= new ResolTablaDato();
		if($modelTabla === null) $modelTabla= new ResolTabla();
		$model->cargarNombreParametros($modelTabla->columnas);
		
		return $this->render('//ctacte/resol/resol_tabla_dato/_form', ['model' => $model, 'modelTabla' => $modelTabla, 'resol_id' => $resol_id, 'tabla_id' => $tabla_id, 'consulta' => 2]);
	}
}
