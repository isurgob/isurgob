<?php

namespace app\controllers\config;

use Yii;
use app\models\config\VigenciaGeneral;
//use app\models\config\Rubro;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;
/**
 * RubrovigenciaController implements the CRUD actions for RubroVigencia model.
 */
class VigenciageneralController extends Controller
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
     * Lists all Vigenciageneral models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$sql = "SELECT rubro_general.nomen_id ,rubro_general.pi,rubro_general.perdesde,rubro_general.perhasta,rubro_general.alicuota,rubro_general.minimo" .
	   ",usr.nombre || ' - ' || to_char(rubro_general.fchmod,'dd/mm/yyyy') as modif ".
	   "FROM rubro_general,sam.sis_usuario usr " .
	   "WHERE rubro_general.usrmod = usr.usr_id";
    	
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);

        return $this->render('//config/rubro/index_vig_general', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VigenciaGeneral model.
     * @param string $nomen_id
     * @param smallint $perdesde
     * @param integer $perdesde
     * @param integer $perhasta
     * @return mixed
     */
    public function actionView($nomen_id,$pi,$perdesde, $perhasta)
    {
        return $this->render('view', [
            'model' => $this->findModel($nomen_id,$pi,$perdesde, $perhasta),
        ]);
    }

     public function actionViggeneralabm(){   

		$model = new VigenciaGeneral();
     	if (isset($error) == null) $error = '';
     	$accion = $_POST['txAccion']; 
                	
		if ($model->load(Yii::$app->request->post())) {

		    if($accion==0){

		    	$error = $model->NuevaVigenciaGeneral($_POST['nomen_id'],$_POST['perdesde'],$_POST['perhasta'],$_POST['cuotadesde'],$_POST['cuotahasta'],$_POST['alicuota'],$_POST['minimo']);
		    	if($error==''){

			     	$mensaje = 'grabado';			     	
			     	return $this->redirect(['index','mensaje' => $mensaje]);
		    	}else{

						$count = Yii::$app->db->createCommand('select count(*) from rubro_general')->queryScalar();
				    	
				    	$sql = "select *, '' modif from rubro_general";
				        $dataProvider = new SqlDataProvider([
				            'sql' => $sql,
							'totalCount' => (int)$count,
				            'pagination'=> [
							'pageSize'=>5
							]
				        ]);
				
						if(!isset($error))$error='';
						if(!isset($accion))$accion='';
						return $this->render('//config/rubro/index_vig_general', [
				            'dataProvider' => $dataProvider,
				            'error' => $error,
				            'accion' => $accion,
				            'model' => $model
				        ]);

		    	}
		    }else if($accion==2){
		    	$error = $model->eliminarRubro($_POST['nomen_id'],$_POST['perdesde'],$_POST['perhasta'],$_POST['cuotadesde'],$_POST['cuotahasta']);
		  
		    	if($error==''){
			     	$mensaje = 'delete';
			     	  
			     	return $this->redirect(['index','mensaje' => $mensaje]);
		    	}else{
		    		
		    			$count = Yii::$app->db->createCommand('select count(*) from rubro_general')->queryScalar();
				    	
				    	$sql = "select *, '' modif from rubro_general";
				        $dataProvider = new SqlDataProvider([
				            'sql' => $sql,
				            'totalCount' => (int)$count,
				            'pagination'=> [
							'pageSize'=>5
							]
				        ]);
				
						if(!isset($error))$error='';
						if(!isset($accion))$accion='';
						return $this->render('//config/rubro/index_vig_general', [
				            'dataProvider' => $dataProvider,
				            'error' => $error,
				            'accion' => $accion,
				            'model' => $model
				        ]);
		    	   }   	
		    } else if($accion==3){
		    	$error = $model->ModificarVigenciaGeneral($_POST['nomen_id'],$_POST['perdesde'],$_POST['perhasta'],$_POST['cuotadesde'],$_POST['cuotahasta'],$_POST['alicuota'],$_POST['minimo']);
		    	if($error==''){
			     	$mensaje = 'grabado';
			     	  
			     	return $this->redirect(['index','mensaje' => $mensaje]);
		    	}else{
		    		
		    			$count = Yii::$app->db->createCommand('select count(*) from rubro_general')->queryScalar();
				    	
				    	$sql = "select *, '' modif from rubro_general";
				        $dataProvider = new SqlDataProvider([
				            'sql' => $sql,
				            'totalCount' => (int)$count,
				            'pagination'=> [
							'pageSize'=>5
							]
				        ]);
				
						if(!isset($error))$error='';
						if(!isset($accion))$accion='';
						return $this->render('//config/rubro/index_vig_general', [
				            'dataProvider' => $dataProvider,
				            'error' => $error,
				            'accion' => $accion,
				            'model' => $model
				        ]);
		    	   }   	
		    }
		 }else{	
			 return $this->redirect(['index','model' => $model]);
			}
     }
    
    public function actionIndex_vig_general(){

    	 return $this->redirect(['index']);
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
    protected function findModel($nomen_id,$pi,$perdesde, $perhasta)
    {
        if (($model = RubroVigencia::findOne(['nomen_id' => $trib_id, 'pi'=>$pi,'perdesde' => $perdesde, 'perhasta' => $perhasta])) !== null) {
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
