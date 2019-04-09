<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Pagocta;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\web\Session;
use app\utils\db\utb;

	
/**
 * PagoctaController implements the CRUD actions for Pagocta model.
 */
class PagoctaController extends Controller
{
    public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
    	
    	$session = new Session;
    	$session->open();
    	
    	$id = $action->getUniqueId();
    	
    	if ($id != 'ctacte/pagocta/create')
    	{
    		//Reinicio el arreglo en sesion
    		$session['arregloCuentaPagocta'] = [];
    	}
    	
		if ($id != 'ctacte/pagocta/listado')
		{
			$session['cond'] = '';
			$session['descr'] = '';
		}
		
    	
    	$session->close();
    	return true;
    }

    /**
     * Displays a single Pagocta model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = '',$action = 1,$consulta = 1,$alert = '',$m = 0)
    {
    	/**
    	 * $consulta == 0 => Formulario para inserción de datos
    	 * $consulta == 1 => Formulario para inserción de datos y procesar
    	 * $consulta == 2 => Formulario para inserción de datos y calcular
    	 * $consulta == 3 => Formulario para inserción de datos y grabar 
    	 */
    	 
    	if (!isset($model)) $model = new Pagocta();
   
    	//Si m=1 y alert == '' => Crear mensaje
    	if ($m == 1 && $alert == '')
    	{
    		$alert = 'Los datos se grabaron correctamente.';
    	}
		
		//Creo el DataProvider para la grilla de cuentas vacío
    	$dataProvider = new ArrayDataProvider(['allModels' => []]);
    	
    	if ($id != '')
    	{
    		$model = $this->findModel($id); //Obtengo los datos del modelo
    		$dataProvider = $model->cargarDatosGrilla($id);
    		
    		if ($model->pago_id == '' || $model->pago_id == null)
    		{
    			$id = '';
    			$alert = 'No se encontró ningún Pago a Cuenta.';
				$m = 2;
				$consulta = 1;
    		}
    		
    	}
		
        return $this->render('view', [
        	'consulta'=>$consulta,
        	'action'=>$action,
            'model' => $model,
            'dataProvider' => $dataProvider,
            'id' => $id,
            'm'=>$m,
            'alert'=>$alert,

        ]);
    }

    /**
     * Creates a new Pagocta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	
    	$alert = '';
		$m = 1;
		    	
		$model = new Pagocta();
		
		//Obtengo los datos que viajan por POST en caso de que se grabe
		$model->trib_id = Yii::$app->request->post('dlTrib',0);
		$model->obj_id = Yii::$app->request->post('txObjID','');
		$model->obj_nom = Yii::$app->request->post('txObjNom','');
		$model->subcta = Yii::$app->request->post('txSubcta',0);
		$model->anio = Yii::$app->request->post('txAnio','');
		$model->cuota = Yii::$app->request->post('txCuota','');
		$model->monto = Yii::$app->request->post('txMonto','');
		$model->fchlimite = Yii::$app->request->post('txFchlimite','');
		$model->obs = Yii::$app->request->post('txObs','');
		
		if ($model->trib_id != 0)
		{
			$id = '';
			//Se enviaron datos y se graba
			$resultado = $model->grabarPagocta();
			
			if (is_array($resultado))
			{
				$id = $resultado['pago'];
				$alert = '';
			} else 
			{
				$alert = $resultado;
			}
			
			if ($alert == '')
				return $this->redirect(['view','m'=>1,'alert'=>'','id'=>$id]);
			
		}
   
       	$dataProvider = new ArrayDataProvider(['allModels' => []]);
       	
        return $this->render('_form', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'alert' => $alert,
            'm' => $m,
        ]);
	
        
    }

	/**
	 * Método que se encarga de dar de baja a un Pago
	 */
    public function actionDelete($id)
    {
		$model = new Pagocta(); //Obtengo los datos del modelo
		
		//Elimino el pago
		$alert = $model->eliminarPagoCta($id);
		if ($alert == '')
			$m = 1;
		else 
			$m = 2; 
			
		return $this->redirect(['view','m'=>$m,'alert'=>$alert,'id'=>$id]);
    }
    
    /**
     * Método que se encarga de dar de baja los pago vencidos
     */
    public function actionDeletevencidas()
    {
    	$model = new Pagocta(); //Obtengo los datos del modelo
		
		//Elimino el pago
		$alert = $model->eliminarPagoCtaVencida();
		if ($alert == '')
			$m = 1;
		else 
			$m = 2; 
			
		return $this->redirect(['view','m'=>$m,'alert'=>$alert]);
    }

	/**
	 * Función que se ejecuta cuando se busca un Pago a Cuenta
	 */
    public function actionBuscar()
    {
    	$pago_id = Yii::$app->request->post('txNum','');

		return $this->redirect(['view','id' => $pago_id, 'consulta' => 1]);
    	
    }
    
 /**
     * Función que se encarga de mostrar las opciones de búsqueda y mostrar los datos 
     */
    public function actionListado($reinicia = 0)
    {
    	$session = new Session;
		$session->open();
		
    	$cond = "";
    	$descr = "";
    	
    	if ($reinicia == 1)
    	{
    		$session['cond'] = '';
    		$session['descr'] = '';
    	}
    	
    	if (isset($_POST['txCriterio']) and $_POST['txCriterio'] != "") $cond = $_POST['txCriterio'];
    	if (isset($_POST['txDescripcion']) and $_POST['txDescripcion'] != "") $descr = $_POST['txDescripcion'];
    	
    	
		if (isset($session['cond']) && $session['cond'] !== '') $cond = $session['cond'];
		$session['cond'] = '';
		$session->close();
    	
    	if ($cond == "")
    	{
	    	    	
	        return $this->render('list_op',[
	        	]);
    	} else {
    		
    		$session = new Session;
			$session->open();
			$session['cond'] = $cond;
			$session['descr'] = $descr;
			$session['order'] = 'pago_id';
			$session->close();
    	        		
    		return $this->render('list_res');
    		
    	}
    }
    
    public function actionImprimir($id)
    {
    	$sub1 = null;
    	$array = (new Pagocta)->Imprimir($id,$sub1);
    	    	
    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '5px';
    	$pdf->marginBottom = '5px';
		$pdf->content = $this->renderPartial('//reportes/boletapagocta',['emision' => $array,'sub1'=>$sub1]);        
        $pdf->methods['SetHeader'] = '';
  		$pdf->methods['SetFooter'] = '';
  		$pdf->filename = 'BoletaPagoCta'; 
        return $pdf->render();	
    }

    /**
     * Finds the Pagocta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pagocta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pagocta::findOne($id)) !== null) {
        	$model->cargarDatos($id);
            return $model;
        } else {
            $model = new Pagocta();
            return $model;
        }
    }
}
