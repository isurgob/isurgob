<?php

namespace app\controllers\config;

use Yii;
use app\models\config\ObjetoTbaja;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;

/**
 * ObjetotbajaController implements the CRUD actions for ObjetoTbaja model.
 */
class ObjetotbajaController extends Controller
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
     * Lists all ObjetoTbaja models.
     * @return mixed
     */
    public function actionIndex(){
  
    	$count = Yii::$app->db->createCommand('select count(*) from objeto_tbaja')->queryScalar();
    	$sql = "SELECT objeto_tbaja.cod, objeto_tbaja.tobj, objeto_tbaja.nombre, objeto_tbaja.fchmod, objeto_tbaja.usrmod" .
		    	" FROM objeto_tbaja";
    	
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
            'pagination'=> [
			'pageSize'=>5,
			],
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
     * Displays a single ObjetoTbaja model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


	public function actionObjetotbajaabm(){


		$model = new ObjetoTbaja();
     	if (isset($error) == null) $error = '';
     	$accion = $_POST['accion']; 
                	
		if ($model->load(Yii::$app->request->post())) {
		
		    if($accion==0){
		    	$error = $model->NuevaTbaja();
		    	if($error==''){
			     	$mensaje = 'create';
			     	
			     	return $this->redirect(['index','mensaje' => $mensaje]);
		    	}else{
				 
						$count = Yii::$app->db->createCommand('select count(*) from objeto_tbaja')->queryScalar();
				    	
				    	$sql = 'select * from objeto_tbaja';
				        $dataProvider = new SqlDataProvider([
				            'sql' => $sql,
				        ]);
				
						if(!isset($mensaje))$mensaje='';
						if(!isset($error))$error='';
						if(!isset($accion))$accion='';
						return $this->render('index', [
				            'dataProvider' => $dataProvider,
				            'error' => $error,
				            'mensaje' => $mensaje,
				            'accion' => $accion,
				            'model' => $model
				        ]);

		    	}
		    }else if($accion==2){
		    	$error = $model->BorrarTbaja($_POST['idDelete']);
		  
		    	if($error==''){
			     	$mensaje = 'delete';
			     	  
			     	return $this->redirect(['index','mensaje' => $mensaje]);
		    	}else{
		    		
		    			$count = Yii::$app->db->createCommand('select count(*) from objeto_tbaja')->queryScalar();
				    	
				    	$sql = 'select * from objeto_tbaja';
				        $dataProvider = new SqlDataProvider([
				            'sql' => $sql,
				        ]);
				
						if(!isset($mensaje))$mensaje='';
						if(!isset($error))$error='';
						if(!isset($accion))$accion='';
						return $this->render('index', [
				            'dataProvider' => $dataProvider,
				            'error' => $error,
				            'mensaje' => $mensaje,
				            'accion' => $accion,
				            'model' => $model
				        ]);
		    	   }   	
		    } else if($accion==3){
		    	$error = $model->ModificarTbaja();
		    	if($error==''){
			     	$mensaje = 'update';
			     	  
			     	return $this->redirect(['index','mensaje' => $mensaje]);
		    	}else{
		    		
		    			$count = Yii::$app->db->createCommand('select count(*) from objeto_tbaja')->queryScalar();
				    	
				    	$sql = 'select * from objeto_tbaja';
				        $dataProvider = new SqlDataProvider([
				            'sql' => $sql,
				        ]);
				
						if(!isset($mensaje))$mensaje='';
						if(!isset($error))$error='';
						if(!isset($accion))$accion='';
						return $this->render('index', [
				            'dataProvider' => $dataProvider,
				            'error' => $error,
				            'mensaje' => $mensaje,
				            'accion' => $accion,
				            'model' => $model
				        ]);
		    	   }   	
		    }

		 }else{		
			 return $this->redirect(['index','model' => $model]);
			}


	}	



    /**
     * Creates a new ObjetoTbaja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new ObjetoTbaja();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cod]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Updates an existing ObjetoTbaja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cod]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Deletes an existing ObjetoTbaja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(){
    	$model = new ObjetoTbaja();
    	if (isset($error) == null) $error = '';
        $error = $model->BorrarTbaja($_GET['cod']); 

		if($error == ""){
			return $this->redirect(['index','mensaje' => 'delete']);	
		}else{
			return $this->redirect(['index','model' => $model,'error' => $error]);
		}
    }

    /**
     * Finds the ObjetoTbaja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObjetoTbaja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObjetoTbaja::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
