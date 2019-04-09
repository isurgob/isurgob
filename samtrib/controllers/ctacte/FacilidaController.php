<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Facilida;
use app\models\ctacte\FacilidaListado;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\utils\db\utb;

/**
 * FacilidaController implements the CRUD actions for Facilida model.
 */
class FacilidaController extends Controller
{

    /**
     * Lists all Facilida models.
     * @return mixed
     */
   /* public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Facilida::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/


     public function beforeAction($action){

		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

	    if (isset($_GET['consulta']) and $_GET['consulta'] == 0){
		    if (!utb::getExisteProceso(3441)) {
		        echo $this->render('//site/nopermitido');
		        return false;
		    }
	    }

		return true;
	}

    /**
     * Displays a single Facilida model.
     * @param integer $id
     * @return mixed
     */
    public function actionView( $id = '',$consulta = 1,$action = 0,$alert = '',$m = 0 )
    {

    	/**
    	 * $action == 0 => Formulario para inserción de datos
    	 * $action == 1 => Formulario para inserción de datos y procesar
    	 * $action == 2 => Formulario para inserción de datos y calcular
    	 * $action == 3 => Formulario para inserción de datos y grabar
    	 */

    	$session = new Session;
    	$session->open();

    	if(!isset($session['facilida-banderaDatos']))
    		$session['facilida-banderaDatos'] = 1;

    	if ($session['facilida-banderaDatos'] == 1)
    	{
    		$session['facilida-banderaDatos'] = 0;
    		$session['arregloPeriodos'] = null;
    	}

    	//Si m=1 y alert == '' => Crear mensaje
    	if ($m == 1 && $alert == '')
    	{
    		$alert = 'Los datos se grabaron correctamente';
    	}

    	$dia = date('d') . '/' . date('m') . '/' . date('Y');

    	if (!isset($model)) $model = new Facilida();

    	$dataProvider = $model->getDatosGrilla($id);

    	if ($id != '')
    	{
    		$model = $this->findModel($id); //Obtengo los datos del modelo

    		//Si no se encuentra ningún dato
    		if ($model->faci_id == '' || $model->faci_id == null)
    		{
    			$alert = 'No se encontró ninguna facilidad.';
				$m = 2;
    		}

    	}

    	if ($m == 7)
    	{
    		$m = 2;
    		$alert = 'No se encontraron facilidades.';
    	}


        return $this->render('view', [
        	'consulta'=>$consulta,
        	'action'=>$action,
            'model' => $model,
            'id' => $id,
            'est' => $model->est,
            'dia'=>$dia,
            'm'=>$m,
            'alert'=>$alert,
            'dataProvider'=>$dataProvider,
        ]);
    }

	/**
	 * Función que se ejecuta para realizar la activación de una facilidad
	 */
	public function actionActivar($id)
	{

   		$model = $this->findModel($id);

   		if ($model->est != 2)
   		{
   			$alert = 'La facilidad no está borrada.';
			$m = 2;

   		} else
   		{
   			$alert = $model->activarFacilidad($id);

   			if ($alert == '')
   			 	$m = 1;
   			else
   				$m = 2;
   		}

		return $this->redirect(['view','id'=>$id,'alert'=>$alert,'m'=>$m]);

	}

	/**
	 * Función que se ejecuta para realizar la baja de una facilidad
	 */
    public function actionBaja($id)
	{

   		$model = $this->findModel($id);

   		if ($model->est != 1)
   		{
   			$alert = 'El estado de la Facilidad es incorrecto.';
			$m = 2;

   		} else
   		{
   			$alert = $model->borrarFacilidad($id);

   			if ($alert == '')
   			 	$m = 1;
   			else
   				$m = 2;
   		}

		return $this->redirect(['view','id'=>$id,'alert'=>$alert,'m'=>$m]);
	}

	/**
	 * Función que da de baja una facilida
	 */
	public function actionBajavencida()
	{
		$model = new Facilida();

		$alert = $model->borrarFacilidadesVencidas();

		if ($alert == '')
   		 	$m = 1;
   		else
   			$m = 2;

		return $this->redirect(['view','alert'=>$alert,'m'=>$m]);
	}

    /**
     * Función que se ejecuta para imprimir un comprobante
     */
    public function actionImprimir($id)
    {
    	$error = "";
    	$texto = "";
    	$id = ($id != "" ? $id : 0);

    	$emision = array();
    	$sub1 = array();

    	$error = Facilida::ImprimirComprobante($id,$emision,$sub1,$texto);

    	if ($error == "")
    	{
    		$pdf = Yii::$app->pdf;
    		$pdf->marginLeft = 3;
    		$pdf->marginRight = 3;
			$pdf->marginTop = 3;
			$pdf->marginBottom = 3;
      		$pdf->content = $this->renderPartial('//reportes/boletafacilida',
      									[
											'emision' => $emision,
											'sub1' => $sub1,
											'texto1' => $texto
										]);
      		$pdf->methods['SetHeader'] = '';
      		$pdf->methods['SetFooter'] = '';
      		$pdf->filename = 'Boleta Facilida';

      		return $pdf->render();

    	}else {
   			return $this->redirect(['view', 'id' => $id, 'alert'=>$error,'m' => 2]);
   		}
    }

    /**
     * Función que se ejecuta cuando se realiza la búsqueda de una facilidad de pago
     */
    public function actionBuscar()
    {

    	if (isset($_POST['txCodigoFacilida']))
    	{
    		$id = $_POST['txCodigoFacilida'];

    		return $this->redirect(['view','id'=>$id]);

    	} else if (isset($_POST['txObj_Id']))
    	{
    		$cont = utb::getCampo('v_facilida',"obj_id='".$_POST['txObj_Id']."'",'count(*)');

    		if ($cont > 1){
    			$model = new FacilidaListado();
                $model->objeto_desde = Yii::$app->request->post('txObj_Id', null);
                $model->objeto_hasta = Yii::$app->request->post('txObj_Id', null);
				$model->objeto_tipo = utb::getTObj( $model->objeto_desde );

                $res = $model->buscar();
                $datos = ListadofacilidaController::datosResultado($model, $res);
                $dataProviderResultados = new ActiveDataProvider([
                    'query' => $res,
                    'key' 		=> $model->pk(),
                    'pagination' => ['pageSize' => 60,],
                    'sort'	=> $model->sort(),
                ]);
                return $this->render('//listado/base_resultado', [
        									'breadcrumbs' => $datos['breadcrumbs'],
        									'descripcion' => '',
        									'model' => $model,
        									'dataProviderResultados' => $dataProviderResultados,
        									'columnas' => $datos['columnas'],
        									'urlOpciones' => $datos['urlOpciones'],
        								]);
    		} else if($cont == 1)
    		{
    			$plan = utb::getCampo('v_facilida',"obj_id='".$_POST['txObj_Id']."'",'faci_id');
    			return $this->redirect(['view', 'id' => $plan]);
    		}
    	}

    	return $this->redirect(['view', 'm' => 7]);

    }

    /**
     * Finds the Facilida model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Facilida the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Facilida::findOne($id)) !== null) {
        	$model->cargarDatos($id);
            return $model;
        } else {
        	$model = new Facilida();
        	return $model;
        }
    }
}
