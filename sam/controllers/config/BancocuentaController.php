<?php

namespace app\controllers\config;

use Yii;
use app\models\config\BancoCuenta;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * BancocuentaController implements the CRUD actions for BancoCuenta model.
 */
class BancocuentaController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get'],
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
     * Lists all BancoCuenta models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$count = Yii::$app->db->createCommand('select count(*) from fin.banco_cta')->queryScalar();
    	
    	$sql = "select *,'' modif from fin.banco_cta";
    	
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
             'totalCount' => (int)$count,
            'pagination'=> [
			'pageSize'=>5
			]
        ]);
	
		if(!isset($mensaje))$mensaje='';
		if(!isset($error))$error='';
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'mensaje' => $mensaje,
            'error' => $error
        ]);
    }

    /**
     * Displays a single BancoCuenta model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
     
     
     public function actionBancocuentaabm(){
        
     	$model = new BancoCuenta();
     	
     	if (isset($error) == null) $error = ''; 
                 	
		if ($model->load(Yii::$app->request->post())) {
			
			     if($_POST['accion']==0){
			     	
			     	$error = $model->NuevaCuentaBancaria();
			     	$mensaje = 'grabado';
			     	
			     }if($_POST['accion']==2){
			     	
			     	$error = $model->EliminarCuentaBancaria($model->bcocta_id);
			     	$mensaje = 'delete';
			     	
			     }else if($_POST['accion']==3){
			     	
					$error = $model->ModificarCuentaBancaria();
			     	$mensaje = 'grabado';     	
			     }
			     
				if($error == ""){
		
					return $this->redirect(['index','mensaje' => $mensaje]);
							
				}else{
					
					return $this->redirect(['index','model' => $model,'error' => $error]);
				}
		}else{
					
			  		return $this->redirect(['index','model' => $model,'error' => $error]);
			}
     }
 
     

    /**
     * Deletes an existing BancoCuenta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index','mensaje' => 'delete']);
    }

    /**
     * Finds the BancoCuenta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BancoCuenta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BancoCuenta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
