<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Fiscaliza;
use app\models\objeto\ComerRubro;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;
use yii\web\Session;

/**
 * FiscalizaController implements the CRUD actions for Fiscaliza model.
 */
class FiscalizaController extends Controller
{
	
	public function beforeAction($action)
	{
		$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
		
		
		//Obtengo el action
		$id = $action->getUniqueId();
		
		$session = new Session;
    	$session->open();
	    	
		if ( $id == '/ctacte/fiscaliza/view' )
		{
	    	$session->set( 'arregloRubros', [] );
	    	$session->set( 'banderaRubros', 0 );
		}
		
		$session->close();
		
		return true;
	}

    /**
     * Función que se ejecuta para mostrar los datos de una fiscalización o para dar de alta o modificar una.
     * 
     * @param integer $elarr = 1 => Se reinician las variables en sesión.
     */
    public function actionView($id = 0, $consulta = 1, $men = 0, $m = 1, $elarr = 0, $reiniciar = 0)
    {
    	/*
    	 * $reiniciar = 1 => Se reinician los arreglos en memoria
    	 */
    	$session = new Session;
    	
    	if ($elarr == 1)
    	{
    		Yii::$app->session->remove('arregloRubros');
    	}
    	
    	$model = Fiscaliza::findOne($id);
    	
    	if ($model == null)
    	{
    		if ($id != '')
    		{
    			$men = 2;
	    		$m = 2;
    		}
    		
    		$session = new Session;
    		Yii::$app->session->remove('arregloRubros');
    		$session->close();
    		
    		$model = new Fiscaliza();
	    		
    	} else 
    	{
    		if (!isset($session['arregloRubros']))
	    	{
	    		$session['arregloRubros'] = [];
	    		$session['banderaRubros'] = 0; // Bandera utilizada para crear el arreglo de mejoras
	    	}
    		$model->obtenerRubros();
    				
			$session = new Session();
			$session->open();
			
			if (($session['banderaRubros'] == 0 && count($session['arregloRubros']) == 0) || $reiniciar)
	    	{
	    		$model->obtenerRubros();
	    		
	    		$session['arregloRubros'] = $model->arrayRubros;	  
	    		$session['banderaRubros'] = 1;
	    		
	    	}
	    
    	}

    	$session->close();

        return $this->render('view', [
            'model' => $model,
            'consulta' => $consulta,
            'alert' => $this->getMensaje($men),
            'm' => $m,
        ]);
    }
    
	/**
     * Función que se ejecuta cuando se realiza la búsqueda de una fiscalización.
     */
    public function actionBuscar()
    {
    	$fisca_id = Yii::$app->request->post('fiscalizacion_id',0);
    	$comer_id = Yii::$app->request->post('fiscalizacion_obj_id','');
    	
    	//Si se ingresó un Id de fiscalización
    	if ($fisca_id != 0)
    	{	 
    		return $this->redirect(['view','id'=>$fisca_id, 'reiniciar' => 1]); 
    		   		
    	} else if ($comer_id != '')	//Si se ingresó un código de comercio
    	{
    		
    		$model = new fiscaliza();
    		//Verificar que exista al menos una fiscalización para el objeto ingresado
    		$existe = $model->existeFiscalizacionObjeto($comer_id);
    		
    		if ($existe == 1)	//Si existe, se debe comprobar la cantidad de las mismas
    		{
    			$cant = $model->existenFiscalizacionesObjeto($comer_id);
    			
    			if ($cant == 1)	//Existen 2 o más fiscalizaciones para el comercio
    			{
    				$session = new Session;
					$session->open();
					$session['cond'] = "obj_id='".$comer_id."'";
					$session['descr'] = "Objeto: ".$comer_id;
					$session->close();
				
					return $this->redirect([
								'list_res'
								]);
								
    			} else	//Si solo existe una fiscalización para el comercio
    			{
    				//Obtengo el id de la fiscalización
    				$id = utb::getCampo('fiscaliza',"obj_id='".$comer_id."'",'fisca_id');
    				
    				return $this->redirect(['view', 'id' => $id]);
    			}	
    		}
    		
    		return $this->redirect(['view', 'men' => 2, 'm' => 2]);
    	
    	} else	//No se ingresaron datos
    	{
    		return $this->redirect(['view']);
    	}
    }
    
    /**
     * Método utilizado para dar de alta una fiscalización.
     */
    public function actionCreate()
    {
    	
    	//Variables por defecto
    	$alert = '';
    	$m = 1;
    	
    	$model = new Fiscaliza();
    	$modelRubro = new ComerRubro();
    	
    	/*
    	 * Si se puede cargar el modelo por medio de post, se deen grabar los datos.
    	 */
    	if ( $model->load( Yii::$app->request->post() ) ) 
    	{
    		$model->setIsNewRecord(true);

    		$res = $model->grabarFiscalizacion();

    		if ($res['return'] == 1)
    			return $this->redirect(['view', 'id' => $res['id'], 'men' => 1]);
    		else
    		{
    			$alert = $res['mensaje'];
    			$m = 2;
    		}
    	} else 
    	{
    		$session = new Session;
	    	$session->open();
		    	
	    	$session->set( 'arregloRubros', [] );
	    	$session->set( 'banderaRubros', 0 );
			
			$session->close();
    	}
    		
		return $this->render('view', [
            'model' => $model,
            'modelRubro' => $modelRubro,
            'consulta' => 0,
            'alert' => $alert,
            'm' => $m,
        ]);
        
    }

    /**
     * Updates an existing Fiscaliza model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $alert = '', $m = 1)
    {
    	
        $model = $this->findModel($id);
        $modelRubro = new ComerRubro();
	    	
        if ($model->load(Yii::$app->request->post())) 
        {
        	$model->setIsNewRecord(false);

    		$res = $model->grabarFiscalizacion();

    		if ($res['return'] == 1)
    			return $this->redirect(['view', 'id' => $model->fisca_id,'men' => 1]);
    		else
    		{
    			$alert = $res['mensaje'];
    			$m = 2;
    		}
    		
        } 
        	
        return $this->render('view', [
            'model' => $model,
            'modelRubro' => $modelRubro,
            'consulta' => 3,
            'alert' => $alert,
            'm' => $m,
        ]);
    }

    /**
     * Deletes an existing Fiscaliza model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $alert = '', $m = 1)
    {
    	
    	$consulta = 2;
    	
        $model = $this->findModel($id);
        $modelRubro = new ComerRubro();

        if ($model->load(Yii::$app->request->post())) 
        {
        	
        	$res = $model->eliminarFiscalizacion();

    		if ($res['return'] == 1)
    			return $this->redirect(['view', 'men' => 1]);
    		else
    		{
    			$alert = $res['mensaje'];
    			$m = 2;
    			$consulta = 1;
    		}
        }

        return $this->render('view', [
            'model' => $model,
            'modelRubro' => $modelRubro,
            'consulta' => $consulta,
            'alert' => $alert,
            'm' => $m,
        ]);
    }
    
    /**
     * Función que se ejecuta cuando se muestran las opciones de listado
     * y cuando se obtienen los parámetros de busca.
     */
    public function actionListado(){
    	
    	$cond = "";
    	$descr = "";
    	
    	if (isset($_POST['txCriterio']) and $_POST['txCriterio'] != "") $cond = $_POST['txCriterio'];
    	if (isset($_POST['txDescripcion']) and $_POST['txDescripcion'] != "") $descr = $_POST['txDescripcion'];
    	
    	$session = new Session;
		$session->open();
    	
    	if ($cond == "")
    	{	    			    		    	    	
	        return $this->render('list_op');
    	} else 
    	{
			$session['order'] = '';
			$session['by'] = '';
			$session['cond'] = $cond;
			$session['descr'] = $descr;
    	        		
    		return $this->redirect(['list_res']);
    	}
    	
    	$session->close();
    }
    
    /**
     * Función que muestra el resultado de la búsqueda
     */
	public function actionList_res()
    {
    		$session = new Session;
			$session->open();
	        $session['order'] = 'fisca_id';
	        
	        $descr = $session['descr'];
	        $cond = $session['cond'];	 
	        
	        $session->close();
		
			return $this->render('list_res',
				[
				'descr' => $descr,
				'cond' => $cond
				]);
    }
    
    public function actionImprimir($id)
    {
    	$sub1 = null;
    	$datos = Fiscaliza::Imprimir($id,$sub1);
    		    		    	    	
    	$pdf = Yii::$app->pdf;
		$pdf->content = $this->renderPartial('//reportes/fiscaliza',['datos' => $datos,'sub1'=>$sub1]);        
        
        return $pdf->render();
    }
    
    public function actionImprimirformulario()
    {
    	$reporte = (isset($_POST['arrayReport']) ? $_POST['arrayReport'] : '');
    	$fisc = (isset($_POST['txFisc']) ? $_POST['txFisc'] : 0);
    	
    	$sub = null;
    	$datos = Fiscaliza::ImprimirReportes($fisc,$reporte,$sub);
    	
    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '30px';
    	$pdf->content = $this->renderPartial('//reportes/fiscalizareporte',['datos' => $datos,'sub'=>$sub]);
    	return $pdf->render();
    }
    
    /**
     * Función que se utiliza para obtener mensajes dependiendo del código ingresado.
     * @param integer $men Código de mensaje
     * @return string Mensaje.
     */
    private function getMensaje($men)
    {
    	switch ($men)
    	{
    		case 0:
    			
    			$alert = '';
    			break;	
    			
    		case 1:
    		
    			$alert = 'Los datos se grabaron correctamente.';
    			break;
    			
    		case 2:
    			
    			$alert = 'No se encontraron fiscalizaciones.';
    			break;
    	}
    	
    	return $alert;
    }

    /**
     * Finds the Fiscaliza model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fiscaliza the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fiscaliza::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
