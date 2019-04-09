<?php

namespace app\controllers\config;

use Yii;
use app\models\config\ObjetoTipo;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\utils\db\utb;

/**
 * ObjetotipoController implements the CRUD actions for ObjetoTipo model.
 */
class ObjetotipoController extends Controller
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
     * Lists all ObjetoTipo models.
     * @return mixed
     */
    public function actionIndex(){
    	
    	$sql = "select objeto_tipo.cod,objeto_tipo.nombre,objeto_tipo.nombre_redu,objeto_tipo.campo_clave," .
    			"objeto_tipo.letra,objeto_tipo.autoinc,objeto_tipo.est,".
    			"(usr.nombre || ' - ' || to_char(objeto_tipo.fchmod,'dd/mm/yyyy')) as modif " .
    			"from objeto_tipo, sam.sis_usuario usr" .
    			" where objeto_tipo.usrmod = usr.usr_id and objeto_tipo.est != 'B'";
    			
    	$count = Yii::$app->db->createCommand("select count(*) from objeto_tipo, sam.sis_usuario usr where objeto_tipo.usrmod = usr.usr_id and objeto_tipo.est != 'B'")->queryScalar();		
    	
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
     * Displays a single ObjetoTipo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

	public function actionTipodeobjetoabm(){   

		$model = new ObjetoTipo();
     	if (isset($error) == null) $error = '';
     	$accion = $_POST['accion']; 
                	
		if ($model->load(Yii::$app->request->post())) {
		
		    if($accion==0){
		    	$error = $model->NuevoTipoDeObejeto();
		    	if($error==''){
			     	$mensaje = 'create';
			     	
			     	return $this->redirect(['index','mensaje' => $mensaje]);
		    	}else{
				 
						$count = Yii::$app->db->createCommand('select count(*) from objeto_tipo')->queryScalar();
				    	
				    	$sql = 'select * from objeto_tipo';
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
		    	$error = $model->EliminarTipoDeObjeto($_POST['idDelete']);
		  
		    	if($error==''){
			     	$mensaje = 'delete';
			     	  
			     	return $this->redirect(['index','mensaje' => $mensaje]);
		    	}else{
		    		
		    			$count = Yii::$app->db->createCommand('select count(*) from objeto_tipo')->queryScalar();
				    	
				    	$sql = 'select * from objeto_tipo';
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
		    	$error = $model->ModificarTipoDeObjeto();
		    	if($error==''){
			     	$mensaje = 'update';
			     	  
			     	return $this->redirect(['index','mensaje' => $mensaje]);
		    	}else{
		    		
		    			$count = Yii::$app->db->createCommand('select count(*) from objeto_tipo')->queryScalar();
				    	
				    	$sql = 'select * from objeto_tipo';
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
     * Creates a new ObjetoTipo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /**
     * Updates an existing ObjetoTipo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    /**
     * Deletes an existing ObjetoTipo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(){
        $model = new ObjetoTipo();
        if (isset($error) == null) $error = '';
        $error = $model->EliminarTipoDeObjeto($_GET['cod']);

        if($error == ""){
			return $this->redirect(['index','mensaje' => 'delete']);						
		}else{					
			return $this->redirect(['index','model' => $model,'error' => $error]);
		}
		
    }

    /**
     * Finds the ObjetoTipo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObjetoTipo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObjetoTipo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
