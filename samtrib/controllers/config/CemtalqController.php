<?php

namespace app\controllers\config;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\utils\db\utb;
use app\models\config\CemTalq;
/**
 * CemtalqController implements the CRUD actions for CemTalq model.
 */
class CemtalqController extends Controller
{
	
	private $consulta;
	
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
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

		$arreglo= Yii::$app->request->isPost ? 'post' : 'get';
		$this->consulta= intval(Yii::$app->request->$arreglo('consulta', 1));

    	return true;
    }
    
    private function resolverPost($model){
    	
    	if(Yii::$app->request->isPost){
    		
    		$scenario= '';
    		switch($this->consulta){
    			
    			case 0:
    				$scenario= 'insert';
    				break;
    				
    			case 2:
    				$scenario= 'delete';
    				break;
    				
    			case 3: 
    				$scenario= 'update';
    				break;
    				
    			default: return false;
    		}
    		
    		$model->setScenario($scenario);
    		if($model->load(Yii::$app->request->post()))
    			return $this->consulta === 2 ? $model->borrar() : $model->grabar();    		
    	}
    	
    	return false;
    }

    /**
     * Lists all CemTalq models.
     * @return mixed
     */
    public function actionIndex($id = 0, $m = '')
    {
        $mensaje= $this->mensaje(intval($m));
        $model= CemTalq::findOne(['cod' => $id]);
        
        if($model === null) $model= new CemTalq();
    	
    	if($this->resolverPost($model)) return $this->redirect(['index', 'm' => 1]);
    	
    	
    	$modelos= $model->todos();
 
 		$dataProvider= new ArrayDataProvider([
 						'allModels' => $modelos,
 						'totalCount' => count($modelos),
 						'pagination' => [
 							'pageSize' => 10
 						]
 						]);


		if(!isset($mensaje))$mensaje='';
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'mensaje' => $mensaje,
            'model' => $model,
            'consulta' => $this->consulta
        ]);
    }

	private function mensaje($codigo){

		switch($codigo){
			case 1: return 'Datos grabados correctamente';

		}

		return '';
	}
}
