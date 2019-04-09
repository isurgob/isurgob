<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Resoltablacol;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\web\Response;
use yii\db\Query;
use app\utils\db\utb;

/**
 * ResolController implements the CRUD actions for Rubro model.
 */		  
class ResoltablacolController extends Controller
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
     * Displays a single Rubro model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('form_resol', [
            'modelResol' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Rubro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     
   /* public function actionCreate()
    {
        $model = new Resolocal();

       	if(Yii::$app->request->isGet)
    	{
    		$model->rubro_id = Yii::$app->request->get('resol_id');
    		
				return $this->redirect(['//ctacte/resol/resol_local/create', 'resol_id' => $model->rubro_id]);
    	}
    }*/

    /**
     * Updates an existing Rubro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
   /* public function actionUpdate($rubro_id,$m=0)
    {
        $model = new Rubro();
		$model = $this->findModel($rubro_id);
        if (isset($_POST['rubro_id'])) {
			if (isset($error) == null) $error = '';
		    $error = $model->ModificarRubro($_POST['rubro_id'],$_POST['nombre'],$_POST['trib_id'],$_POST['grupo'],$_POST['tunidad']);
		    
		    if($error==''){
				$model = $this->findModel($rubro_id);			     	
				return $this->redirect(['index','m'=>1]);
				
		    }else{
					$model = $this->findModel($rubro_id);
					 return $this->render('update', [
				            'error' => $error,
				            'model' => $model
				        ]);
		    	}
        }else {
            return $this->render('update', [
                'model' => $model,
                'mensaje'=>$m	              
            ]);
        }
    }*/

    /**
     * Deletes an existing Rubro model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     
   /* public function actionDelete($rubro_id)
    {
    	$model = new Rubro();
    	    	
    	if(Yii::$app->request->isGet)
    	{
    		$model->rubro_id = Yii::$app->request->get('rubro_id');
    		$model = $this->findModel(Yii::$app->request->get('rubro_id'));
    		
    		return $this->render('delete', [
                'model' => $model,    
            ]);
    	}
      
        if(Yii::$app->request->isPost)
        {        	
			if (isset($error) == null) $error = '';
		    $error = $model->eliminarRubro(Yii::$app->request->post('rubro_id'));
		    
	    	if($error==''){
	    		$model = Rubro::findOne(Yii::$app->request->post('rubro_id'));
				//echo "1";
	    		//exit();		     	
			    return $this->redirect(['index','m'=>2]);
	    	}else{
	    		//echo "2";
	    		//exit();
				$model = $this->findModel(Yii::$app->request->post('rubro_id'));
				return $this->render('delete', [
			            'error' => $error,
			            'model' => $model
			        ]);
	    	}
		    
        }   
    }*/


    /**
     * Finds the Rubro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rubro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Resoltablacol::findOne($id)) !== null) {
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
