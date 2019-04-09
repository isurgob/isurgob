<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Judi;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\utils\db\utb;

/**
 * JudiController implements the CRUD actions for judi model.
 */
class JudiController extends Controller
{
    public function beforeAction($action){
		
		$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
	    
		return true;
	}
    
    /**
     * Displays a single apremio model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = '',$consulta = 1,$action = 0,$alert = '',$m = 0)
    {
    	
    	/**
    	 * $action == 0 => Formulario para inserción de datos
    	 * $action == 1 => Formulario para inserción de datos y procesar
    	 * $action == 2 => Formulario para inserción de datos y calcular
    	 * $action == 3 => Formulario para inserción de datos y grabar 
    	 */
    	 
    	$session = new Session;
    	$session->open();
    	
    	if(!isset($session['apremio-banderaDatos']))
    		$session['apremio-banderaDatos'] = 1;
    		
    	if ($session['apremio-banderaDatos'] == 1)
    	{
    		$session['apremio-banderaDatos'] = 0;
    		$session['arregloPeriodosApremio'] = null;
    	} 
    	
    	//Si m=1 y alert == '' => Crear mensaje
    	if ($m == 1 && $alert == '')
    	{
    		$alert = 'Los datos se grabaron correctamente';
    	}
    	
    	$dia = date('d') . '/' . date('m') . '/' . date('Y');
    	
    	if (!isset($model)) $model = new Judi();
    	
    	$dataProviderPeriodo = $model->getDatosGrillaPeriodo($id);
    	$dataProviderEtapa = $model->getDatosGrillaEtapa($id);
    	
    	if ($id != '')
    	{
    		$model = $this->findModel($id);
    		
    		//Si no se encuentra ningún dato
    		if ($model->judi_id == '' || $model->judi_id == null)
    		{
    			$alert = 'No se encontró ningún apremio.';
				$m = 2;
    		}
    	}	
    		
    	$session->close();
    	
    	if (Yii::$app->session['error_imp'] != ''){
    		$alert = Yii::$app->session['error_imp'];
    		Yii::$app->session['error_imp'] = '';
    		$m = 2;	
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
            'dataProviderPeriodo' => $dataProviderPeriodo,
    		'dataProviderEtapa' => $dataProviderEtapa,
        ]);
    }

	
	/**
	 * Función que se encarga del alta de un apremio
	 */
	public function actionCreate()
	{
		
		$model = new Judi();
		
		$model->nuevo_obj_tipo = $_POST['dlObjeto'];
		$model->nuevo_obj_id = $_POST['txObjetoID'];
		$model->nuevo_obj_nom = $_POST['txObjetoNom'];
		$model->nuevo_reparticion = $_POST['dlReparticion'];
		$model->nuevo_numero = $_POST['txNro'];
		$model->nuevo_anio = $_POST['txAnio'];
		
		$model->nuevo_fchconsolida = $_POST['fchconsolida'];
		$model->nuevo_desdeanio = $_POST['txPeriodoDesdeAnio'];
		$model->nuevo_desdecuota = $_POST['txPeriodoDesdeCuota'];
		$model->nuevo_hastaanio = $_POST['txPeriodoHastaAnio'];
		$model->nuevo_hastacuota = $_POST['txPeriodoHastaCuota'];
		$model->nuevo_nominal = $_POST['txNominal'];
		$model->nuevo_accesor = $_POST['txAccesor'];
		$model->nuevo_multa = $_POST['txMulta'];
		$model->nuevo_multa_omis = $_POST['txMultaOmis'];
		$model->nuevo_total = $_POST['txTotal'];
		$model->nuevo_obs = $_POST['txObs'];

		if ($model->grabar() == 0)
		{
			return $this->redirect(['view','model'=>$model]);
		} else 
		{
			return $this->redirect(['view','id'=>$model->nuevo_judi_id,'consulta'=>1, 'alert' => '', 'm'=>1]);
		}
	}
	
	/**
	 * Función que se encarga de llamar al método updateObs para
	 * actualizar la observación de un apremio.
	 * @param integer $judi_id Identificador del apremio.
	 * @param string $obs Observación del apremio en cuestión.
	 */
	public function actionUpdateobs()
	{
		$id = $_POST['txJudiIDObs'];
		$obs = $_POST['txObs'];
		
		$model = new Judi();
		
		$model->updateObs($id,$obs);
		
		if ($alert != '')
			$m = 2;
		else 
			$m = 1;
		
		$this->redirect(['view','id'=>$id,'consulta'=>1, 'alert' => $alert, 'm'=>$m]);
	}
    
    /**
     * Función que se encarga de agregar una nueva etapa al apremio
     */
    public function actionEtapamas()
    {
    	//Obtengo los datos
    	$judi_id = Yii::$app->request->post('txJudiIDEtapaMas',0);
    	
    	$etapa = Yii::$app->request->post('dlEtapa',0);
    	
    	$fecha = Yii::$app->request->post('fch','');
    	
    	$procurador = Yii::$app->request->post('dlProcurador',0);
    	
    	$juzgado = Yii::$app->request->post('dlJuzgado',0);
    	
    	$motivo = Yii::$app->request->post('dlMotivo',0);
    	
    	$hono_jud = Yii::$app->request->post('txHonorarios',0);
    	
    	$gasto_jud = Yii::$app->request->post('txGastos',0);
    	
    	$detalle = Yii::$app->request->post('txDetalle','');
    	
    	$model = new Judi();
    	$alert = $model->agregarEtapa($judi_id,$etapa,$fecha,$procurador,$juzgado,$motivo,$hono_jud,$gasto_jud,$detalle);
    		
    	if ($alert != 'OK')
			$m = 2;
		else 
		{
			$m = 1;
			$alert = '';
		}
			
		$this->redirect(['view','id'=>$judi_id,'consulta'=>1, 'alert' => $alert, 'm'=>$m]);
    }
    
    /**
     * Función que se encarga de eliminar la última etapa de un apremio
     */
    public function actionEtapamenos()
    {
    	//Obtengo los datos
    	$judi_id = 0;
    	if (isset ($_POST['txJudiIDEtapaMenos']))
    		$judi_id = $_POST['txJudiIDEtapaMenos'];
    	
    	$model = new Judi();
    	$alert = $model->eliminarEtapa( $judi_id );	
    	
    	if ($alert != 'OK')
			$m = 2;
		else 
		{
			$m = 1;
			$alert = '';
		}
			
		$this->redirect(['view','id'=>$judi_id,'consulta'=>1, 'alert' => $alert, 'm'=>$m]);
    }
    
    /**
     * Función que se ejecuta para imprimir un comprobante
     */
    public function actionImprimir()
    {
    	return $this->redirect(['view']);
    }
    
    /**
     * Función que se ejecuta cuando se realiza la búsqueda de una apremiod de pago
     */
    public function actionBuscar()
    {
    	if (isset($_POST['txBuscar']))
    		$id = $_POST['txBuscar'];
    		    		
    	$this->redirect(['view','id'=>$id]);
    }
    
     public function actionImprimirexpe($id)
    {
    	$sub1 = null;
    	$array = (new Judi)->ImprimirExpe($id,$sub1);
    	    	
    	$pdf = Yii::$app->pdf;
		$pdf->content = $this->renderPartial('//reportes/judiexpe',['datos' => $array,'sub1'=>$sub1]);        
        
        return $pdf->render();	
    }
    
     public function actionImprimircertif()
    {
    	$id = (isset($_POST['txImpCertLote']) ? $_POST['txImpCertLote'] : 0);
    	$texto = (isset($_POST['dlImpCertTexto']) ? $_POST['dlImpCertTexto'] : 0);
    	$firma1 = (isset($_POST['dlImpCertFirma1']) ? $_POST['dlImpCertFirma1'] : 0);
    	$firma2 = (isset($_POST['dlImpCertFirma2']) ? $_POST['dlImpCertFirma2'] : 0);
    	$est = utb::getCampo('judi','judi_id='.$id,'est');
    	$error = '';
    	if ($id ==  0) $error = 'No hay una Planilla cargada';
    	if ($est ==  'B') $error = 'La Planilla se encuentra dada de baja';
    	
    	Yii::$app->session['error_imp'] = $error;
    	
    	if ($error == ''){
    		$sub1 = null;
	    	$array = (new Judi)->ImprimirExpe($id,$texto,$sub1);
	    	$firma1_des = utb::getCampo('Intima_Firma','firma_id='.$firma1).'<br>'.utb::getCampo('Intima_Firma','firma_id='.$firma1,'cargo');
	    	$firma1_des = utb::getCampo('Intima_Firma','firma_id='.$firma2).'<br>'.utb::getCampo('Intima_Firma','firma_id='.$firma2,'cargo');
	    	    	
	    	$pdf = Yii::$app->pdf;
			$pdf->content = $this->renderPartial('//reportes/judi',[
						'datos' => $array,'sub1'=>$sub1,'firma1' => $firma1_des,'firma2' => $firma2_des
					]);        
	        
	        return $pdf->render();	
    	}else {
    		$this->redirect(['view','id'=>$id]);
    	}
    	
    		
    }

    /**
     * Finds the judi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return judi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = judi::findOne($id)) !== null) {
          	$model->cargarDatos($id);
            return $model;
        } else {
        	$model = new Judi();
        	return $model;
        }
    }
}
