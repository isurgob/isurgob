<?php

namespace app\controllers\caja;

use Yii;
use app\models\caja\Debito;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\Fecha;
use app\utils\db\utb;

/**
 * DebitoController implements the CRUD actions for DebitoAdhe model.
 */
class DebitoController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
    	
    	//$idAnterior = $id;
    	$id = $action->getUniqueId();

    	return true;
    } 

    /**
     * Displays a single DebitoAdhe model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = '',$action = 1,$consulta = 1,$alert = '',$m = 0,$obj='')
    {
    	/**
    	 * $consulta == 0 => Formulario para inserción de datos
    	 * $consulta == 1 => Formulario para inserción de datos y procesar
    	 * $consulta == 2 => Formulario para inserción de datos y calcular
    	 * $consulta == 3 => Formulario para inserción de datos y grabar 
    	 */
    	 
    	if (!isset($model)) $model = new Debito();
   
    	//Si m=1 y alert == '' => Crear mensaje
    	if ($m == 1 && $alert == '')
    	{
    		$alert = 'Los datos se grabaron correctamente';
    	}
    	
    	if ($m == 2 && $alert != '')
    	{
    		$alert = 'Hubo un error al grabar los datos.';
    	}
    	
    	if ($id != '')
    	{
    		$model = $this->findModel($id); //Obtengo los datos del modelo
    	}
		
		if ($obj != '')
    	{
    		$model = Debito::findOne(['obj_id' => $obj]); //Obtengo los datos del modelo
			if ($model == null) {
				$model = new Debito();
				$model->obj_id = $obj;
			}	
    	}
    	
        return $this->render('view', [
        	'consulta'=>$consulta,
        	'action'=>$action,
            'model' => $model,
            'id' => $id,
            'm'=>$m,
            'alert'=>$alert,

        ]);
    }
    
    /**
     * Función que se ejecuta para mostrar las liquidaciones
     */
    public function actionLiquida()
    {
    	return $this->render('liquida');
    }
    
    /**
     * Función que se ejecuta para el alta de una adhesión
     * @param integer $id Identificador de la adhesión
     * @param integer $consulta Identificador del modo en que se deben mostrar los formularios
     * @param integer $action Identificador del tipo de acción
     */
    public function actionAdhesion($id='',$consulta=1,$action=0)
    {
    	if (!isset($model)) $model = new Debito(); //Creo el modelo
    	
    	$result = '';
    	
    	if ($id != '' && $action == 2)
    	{
    		  //Eliminar la Adhesión
	  	  	  $result = $model->eliminarAdhesion($id);
		  	  
		  	  echo $result;
		  	  if ($result == '')	//En caso de que el insert, delete o update sea correcto
		  	  	return $this->redirect(['view','alert' => '', 'm' => 1]);
    	}	
    	
    	//Si me llegan datos por POST, los cargo en el modelo
    	if (isset($_POST['txObjetoID']) && $_POST['txObjetoID'] != '')
    	{
		      $model->adhesion_id = $_POST['txNumero'];
		      $model->adhesion_est = $_POST['txEst'];
			  $model->adhesion_estNom = $_POST['txEstado'];
			  if ($model->adhesion_est == '')
			  	$model->adhesion_estNom = '';
			  $model->adhesion_tobj = $_POST['dlTObj'];
			  $model->adhesion_obj_id = $_POST['txObjetoID'];
			  $model->adhesion_obj_nom = $_POST['txObjetoNom'];
			  $model->adhesion_subcta = $_POST['txCta'];
			  $model->adhesion_trib_id = $_POST['dlTrib'];
			  $model->adhesion_texto = $_POST['dlTexto'];
			  $model->adhesion_tdistrib = $_POST['dlTDistrib'];
			  $model->adhesion_distrib = isset($_POST['dlDistrib']) ? $_POST['dlDistrib'] : 0;
			  $model->adhesion_plan_anioDesde = $_POST['txPlanAnioDesde'];
			  $model->adhesion_plan_cuotaDesde = $_POST['txPlanCuotaDesde'];
			  $model->adhesion_plan_anioHasta = $_POST['txPlanAnioHasta'];
			  $model->adhesion_plan_cuotaHasta = $_POST['txPlanCuotaHasta'];
			  $model->adhesion_usrmod = $_POST['txModif'];
			  $model->adhesion_obs = $_POST['txObs'];
			  $model->adhesion_responsable_tdoc = $_POST['dlTDoc'];
			  $model->adhesion_responsable_ndoc = $_POST['txNDoc'];
			  $model->adhesion_responsable_sexo = $_POST['dlSexo'];
			  $model->adhesion_responsable_nombre = $_POST['txNombreResp'];
			  $model->adhesion_pago_caja_tipo = $_POST['txTCaja'];
			  $model->adhesion_pago_caja_id = $_POST['dlDebito'];
			  $model->adhesion_pago_sucID = $_POST['txSucID'];
			  $model->adhesion_pago_sucNom = $_POST['txSucNom'];
			  $model->adhesion_pago_tipo = isset($_POST['dlTipoSuc']) ? $_POST['dlTipoSuc'] : 0;
			  $model->adhesion_pago_numCta = $_POST['txNumCta'];
			  $model->adhesion_pago_cbu = $_POST['txCbu'];
			  $model->adhesion_pago_numTarjeta = $_POST['txNTarjeta'];
		      $model->adhesion_pago_templeado = $_POST['dlTEmpleado'];
		  	  $model->adhesion_pago_area = $_POST['txArea'];
		  	  $model->adhesion_pago_legajo = $_POST['txLegajo'];
		  	  
		  	  $cons = $_POST['txConsulta'];

		  	  if ($cons == 0) //Insertar nueva Adhesión
		  	  {
		  	  		$result = $model->nuevaAdhesion();
		  	  }
		  	  
		  	  if ($cons == 2) //Eliminar la Adhesión
		  	  {
		  	  		$result = $model->eliminarAdhesion();
		  	  }
		  	  
		  	  if ($cons == 3) //Modificar la Adhesión
		  	  {
		  	  		$result = $model->modificarAdhesion();
		  	  }
		  	  
		  	  if ($result == '')	//En caso de que el insert, delete o update sea correcto
		  	  	return $this->redirect(['view','alert' => '', 'm' => 1]);
    	}
    	
    	/**
    	 * $consulta == 0 => Formulario para inserción de datos
    	 * $consulta == 1 => Formulario para inserción de datos y procesar
    	 * $consulta == 2 => Formulario para inserción de datos y calcular
    	 * $consulta == 3 => Formulario para inserción de datos y grabar 
    	 */
    	
    	
    	if ($id != '') 
    	{
    		$model->cargarAdhesion($id);	//Si $id es distinto a vacío, cargo los datos de la adhesión
    	}	
    	
    	 
    	return $this->render('_formAdhesion',[
									'model'=> $model,
									'consulta'=>$consulta,
									'mensj' => $result,
							]);
    }
    
    /**
     * Función que se ejecuta para consultar liquidaciones
     * @param integer $id
     * @param integer $consulta
     */
    public function actionLiquidacion($id='',$consulta=1)
    {
    	$model = new Debito();
    	return $this->render('liquidaciones',['model' => $model]);
    }
    
    public function actionDebitoCons($id)
    {
    	$liq = (new Debito)->BuscarLiq($id);
    	return $this->render('debitocons',['liq' => $liq]);
    }
    
    public function actionImprimiradhe($id)
    {
    	$sub1 = array();
    	$montoactual = 0;
    	$datos = (new Debito)->ImprimirAdhe($id,$sub1,$montoactual);
    	
    	$pdf = Yii::$app->pdf;
    	$pdf->content = $this->renderPartial('//reportes/debitoadhe',['datos' => $datos,'sub1'=>$sub1,'montoactual' => $montoactual]);        
        
        return $pdf->render();
    }
	
	public function actionCertificado() {
	
		$adhe = Yii::$app->request->get( 'adhe_id', 0 );
		$texto = Yii::$app->request->get( 'texto', 0 );
		
		$datos = Debito::getCertificado( $adhe, $texto );
		
		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '50px';
    	$pdf->content = $this->renderPartial('//reportes/texto_general', ['datos' => $datos]);        
        
        return $pdf->render();
	
	}
	
	public function actionGenerarreporte(){
	
		$model = new Debito();
		
		$dpCampos = $model->camposGenerarReporte();
		
		$caja = Yii::$app->request->post( 'dlCaja', 0 );
		$anio = Yii::$app->request->post( 'txAnio', 0 );
		$mes = Yii::$app->request->post( 'txMes', 0 );
		$seleccionado = Yii::$app->request->post( 'selection', [] );

        $descripcion = 'Caja: ' . utb::getCampo('caja', 'caja_id=' . $caja) . " - Año: $anio - Mes: $mes";
		
		if ( Yii::$app->request->ispost and !Yii::$app->request->ispjax ){
            
			$seleccionado = $this->obtenerSeleccionado( $_POST ); 
            
            $resultado = $model->GenerarReporte( $caja, $anio, $mes, $seleccionado );
            
            $pdf = Yii::$app->pdf;
            $pdf->marginTop = '50px';
            $pdf->content = $this->renderPartial('//reportes/generarreporte', [ 'datos' => $resultado, 'desc' => $dpCampos->getModels() ]);        
            
            return $pdf->render();
			
        }
		
		return $this->render( 'generarreporte', [
			'model' => $model,
			'arrayCaja' => utb::getAux('caja','caja_id','nombre',3,"tipo IN (3,4,5) AND est='A'"),
			'dpCampos' => $dpCampos,
			
			'caja' => $caja,
			'anio' => $anio,
			'mes' => $mes,
			'seleccionado' => json_encode($seleccionado),
			'descripcion' => $descripcion
		]);
	}
	
	private function obtenerSeleccionado( $post ){
		$select = $post['selection'];
		$seleccionados = [];
		
		foreach ( $select as $s ){
			$seleccionados[] = ['orden' => $post['txOrden'.$s], 'campo' => $s ];
		}
		
		return $seleccionados;
	}
	
	public function actionExportar(){
		
		$caja = Yii::$app->request->post( 'caja', 0 );
		$anio = Yii::$app->request->post( 'anio', 0 );
		$mes = Yii::$app->request->post( 'mes', 0 );
		$seleccionado = json_decode( $_POST['seleccionado'], true );
		
		$model = new Debito();
		
		$dpCampos = $model->camposGenerarReporte();
		
		$resultado = $model->GenerarReporte( $caja, $anio, $mes, $seleccionado );
		$campos_desc = $model->detalleCamposGenerarReporte( $seleccionado, $dpCampos->getModels() );
		
		return json_encode([ 'datos' => $resultado, 'campos_desc' => $campos_desc ]);
	}
	
	public function actionGenerarreporteimprimir(){
	
		print_r($_POST);
	}
        
    /**
     * Finds the DebitoAdhe model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DebitoAdhe the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Debito::findOne($id)) !== null) {
        	$model->cargarValoresPorDefectoAdhesion();
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
}
