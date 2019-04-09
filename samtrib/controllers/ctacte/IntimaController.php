<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Intima;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * IntimaController implements the CRUD actions for Intima model.
 */
class IntimaController extends Controller
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
	
	public function beforeAction($action){
		
		$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
	    
		return true;
	}
	
	/**
	 * Crea un arreglo con los datos a utilizar por las vistas del seguimiento individual
	 * 
	 * @param Intima $model = null - Si no se provee, el arreglo se completa con datos por defecto.
	 * Si se provee, se recupera la siguiente informacion
	 * 
	 * 	- Datos del seguimiento individual
	 * 	- Periodos
	 * 	- Entregas
	 * 	- Etapas
	 * 	- Espera
	 */
	private function getExtrasDefaultSeguimiento($model = null){
		
		if($model == null)
		{
			$model = new Intima();
			$model->inti_id = 0;
		} 
		
		$extras['datos'] = Intima::getSeguimiento($model->inti_id);
    	$extras['hayDatos'] = $extras['datos'] != false && $extras['datos']['inti_id'] !== null;
    	$extras['dpPeriodos'] = new ArrayDataProvider([
    						'allModels' => Intima::getPeriodos($model->inti_id),
    						'pagination' => ['pageSize' => 50]
    						]);
    	
    	$extras['dpEntregas'] = new ArrayDataProvider([
    						'allModels' => Intima::getEntregasSeguimiento($model->inti_id),
    						'pagination' => ['pageSize' => 50]
    						]);
    						
    	$extras['dpEtapas'] = new ArrayDataProvider([
    						'allModels' => Intima::getEtapas($model->lote_id, $model->obj_id),
    						'pagination' => ['pageSize' => 50]
    						]);
    						
    	$extras['dpEspera'] = new ArrayDataProvider([
    						'allModels' => Intima::getEsperas($model->obj_id),
    						'pagination' => ['pageSize' => 50]
    						]);
    						
    	//extras por defecto. Para que no den error las vistas al no encontrarlos
        $extras['modSeguimiento']['model'] = $model;
    	$extras['modSeguimiento']['tomo'] = $model->tomo;
    	$extras['modSeguimiento']['folio'] = $model->folio;
    	$extras['modSeguimiento']['plazo'] = $model->fchplazo != null ? $model->fchplazo : date('d/m/Y');
    	
    	//entrega
    	$extras['modEntrega']['model'] = $model;
    	$extras['modEntrega']['fecha'] = date('d/m/Y');
    	$extras['modEntrega']['resultado'] = null;
    	$extras['modEntrega']['distribuidor'] = null;
    	
    	//etapa
    	$extras['modEtapa']['model'] = $model;
    	$extras['modEtapa']['fecha'] = date('d/m/Y');
    	$extras['modEtapa']['etapa'] = null;
    	$extras['modEtapa']['detalle'] = null;
    	
    	//espera
    	$extras['modEspera']['model'] = $model;
    	$extras['modEspera']['trib_id'] = null;
    	$extras['modEspera']['adesde'] = null;
    	$extras['modEspera']['cdesde'] = null;
    	$extras['modEspera']['ahasta'] = null;
    	$extras['modEspera']['chasta'] = null;
    	$extras['modEspera']['fecha'] = date('d/m/Y');
    	$extras['modEspera']['fechaHasta'] = null;
    	$extras['modEspera']['obs'] = null;
    	
    	$extras['model'] = $model;
			
		return $extras;
	}

    /**
     * Displays a single Intima model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = '', $consulta = 1, $alert = '', $m = 1)
    {
        /**
         * $m = 1 => alert-success
         * $m = 2 => alert-danger
         */
       
        $model = new Intima();
        $dataProvider = new ArrayDataProvider(['allModels' => []]);
        
        if ($id != '')
        {
        	$model = $this->findModel($id);
        }
        
        if ($model == null)
        {
        	$alert = 10;
        	$m = 2;
        	$consulta = 1;
        	$id = '';
        	$model = new Intima();
        } else 
        {
        	$dataProvider = $model->cargarDatosGrilla($id); //Se pasa como parámetro el id del lote
        }
        
        return $this->render('view',[
        			'id'=>$id,
        			'model'=>$model,
        			'dataProvider'=>$dataProvider,
        			'consulta'=>$consulta,
        			'alert'=>$this->getMensaje($alert),
        			'm'=>$m
        			]);
    }
    
    /**
     * Muestra los datos de un seguimiento
     * 
     * El seguimiento se intentará buscar por código de seguimiento. Si el código de seguimiento no es provisto, se intenta por código de objeto y código de lote
     * 
     * @param int $_GET['inti_id'] = 0 - código del seguimiento
     * @param string $_GET['obj_id'] = '' - código del objeto
     * @param int $_GET['lote_id'] = 0 - código del lote
     * @param int $_GET['m'] = 0 - código del mensaje a mostrar a traves de $this->getMensaje($m)
     */
    public function actionViewseguimiento($m = 0)
    {
    	
    	$model = null;

    	$inti_id = Yii::$app->request->get('inti_id', 0);
    	$obj_id = Yii::$app->request->get('obj_id', '');
    	$lote_id = Yii::$app->request->get('lote_id', 0);
    	
    	if(intval($inti_id) > 0)
    		$model = Intima::findOne($inti_id);
    	else if(!empty($obj_id) && strlen($obj_id) == 8 && intval($lote_id) > 0)
    		$model = Intima::findOne(['obj_id' => $obj_id, 'lote_id' => $lote_id]);
    		
    	if($model == null)
    	{
    		$model = new Intima();
    		$model->obj_id = '';
    		$model->inti_id = 0;
    		$model->lote_id = 0;
    	} 
    	
    	$extras = $this->getExtrasDefaultSeguimiento($model);
    	
    	return $this->render('seguimiento', ['extras' => $extras, 'mensaje' => $this->getMensaje(intval($m))]);
    }
    
    /**
     * Crea una nueva entrega
     * 
     * Si la consulta viene por POST, se procede a crear la nueva entrega. Cualquier otra consulta muestra el formulario para crear una nueva entrega
     * 
     * @param int $_POST['inti_id'] - Codigo de intimacion
     * @param string $_POST['dtFecha'] - Fecha en que se produce la entrega en formato 'dd/mm/yyyy'
     * @param int $_POST['dlResultado'] - Codigo del resultado de la entrega
     * @param int $_POST['dlDistribuidor'] - Codigo del distribuidor que realizo la entrega
     */
	public function actionCreateentrega(){
	
		$model = new Intima();
		
		$fecha = date('d/m/Y');
		$resultado = 0;
		$distribuidor = 0;
		
	
		if(Yii::$app->request->isPost){

			$model = Intima::findOne(Yii::$app->request->post('inti_id', 0));
			
			if($model == null) $model = new Intima();
					
			$fecha = Yii::$app->request->post('dtFecha', '');
			$resultado = Yii::$app->request->post('dlResultado', 0);
			$distribuidor = Yii::$app->request->post('dlDistribuidor', 0);
			 
			$res = $model->nuevaEntrega($fecha, $resultado, $distribuidor);
			
			if($res)
				return $this->redirect(['viewseguimiento', 'inti_id' => $model->inti_id, 'm' => 3]);
		}
		
		$extras = $this->getExtrasDefaultSeguimiento($model);
		
		$extras['modEntrega']['fecha'] = Fecha::usuarioToDatePicker($fecha);
		$extras['modEntrega']['resultado'] = $resultado;
		$extras['modEntrega']['distribuidor'] = $distribuidor;
		
		return $this->render('_form_entrega', ['extras' => $extras]);
	}
	
	/**
	 * Crea una nueva etapa
	 * 
	 * Si la consulta viene por POST, se procede a crear la nueva etapa. Cualquier otra consulta muestra el formulario para crear una etapa
	 * 
	 * @param int $_POST['inti_id'] - Codigo de intimacion
	 * @param string $_POST['dtFecha'] - Fecha de la etapa en formato 'dd/mm/yyyy'
	 * @param int $_POST['dlEtapa'] - Etapa
	 * @param string $_POST['txDetalle'] - Detalle
	 */
	public function actionCreateetapa(){
		
		$model = new Intima();
		$extras['modEtapa']['model'] = $model;
		
		$fecha = date('d/m/Y');
		$etapa = null;
		$detalle = null;
		
		if(Yii::$app->request->isPost){
			
			$model = Intima::findOne(Yii::$app->request->post('inti_id', 0));
			
			if($model == null) $model = new Intima();
			
			$fecha = Yii::$app->request->post('dtFecha', '');
			$etapa = Yii::$app->request->post('dlEtapa', -1);
			$detalle = Yii::$app->request->post('txDetalle', '');
			
			$res = $model->nuevaEtapa($fecha, $etapa, $detalle);
			
			if($res) return $this->redirect(['viewseguimiento', 'inti_id' => $model->inti_id, 'm' => 3]);
			
			
		}
		
		$extras = $this->getExtrasDefaultSeguimiento($model);
		
		$extras['modEtapa']['fecha'] = $fecha;
		$extras['modEtapa']['etapa'] = $etapa;
		$extras['modEtapa']['detalle'] = $detalle;
		
		return $this->render('_form_etapa', ['extras' => $extras]);	
	}
	
	/**
	 * Crea una nueva espera
	 * 
	 * Si la consulta viene por POST, se procede a crear la nueva espera. Cualquier otra consulta muestra el formulario para crear una espera
	 * 
	 * @param int $_POST['inti_id'] - Codigo de intimacion
	 * @param int $_POST['dlTrib'] - Codigo del tributo
	 * @param int $_POST['txAdesde'] - Año del periodo desde
	 * @param int $_POST['txCdesde'] - Cuota del periodo desde
	 * @param int $_POST['txAhasta'] - Año del periodo hasta
	 * @param int $_POST['txChasta'] - Cuota del periodo hasta
	 * @param string $_POST['dtFecha'] - Fecha desde de la espera en formato 'dd/mm/yyyy'
	 * @param string $_POST['dtFechaHasta'] - Fecha hasta de la espera en formato 'dd/mm/yyyy'
	 * @param string $_POST['txObs'] - Observaciones
	 */
	public function actionCreateespera(){
		
		$model = new Intima();
		
		$trib_id = null;
		$adesde = null;
		$cdesde = null;
		$ahasta = null;
		$chasta = null;
		$fecha = date('d/m/Y');
		$fechaHasta = null;
		$obs = null;
		
		
		if(Yii::$app->request->isPost){
			
			$model = Intima::findOne(Yii::$app->request->post('inti_id', 0));
			
			if($model == null) $model = new Intima();
			
			$trib_id = Yii::$app->request->post('dlTrib', -1);
			$adesde = Yii::$app->request->post('txAdesde', -1);
			$cdesde = Yii::$app->request->post('txCdesde', -1);
			$ahasta = Yii::$app->request->post('txAhasta', -1);
			$chasta = Yii::$app->request->post('txChasta', -1);
			$fecha = Yii::$app->request->post('dtFecha', '');
			$fechaHasta = Yii::$app->request->post('dtFechaHasta', null);
			$obs = Yii::$app->request->post('txObs', '');
			
			$res = $model->nuevaEspera($trib_id, $adesde, $cdesde, $ahasta, $chasta, $fecha, $fechaHasta, $obs);
			
			if($res) return $this->redirect(['viewseguimiento', 'inti_id' => $model->inti_id, 'm' => 3]);
		}
		
		$extras = $this->getExtrasDefaultSeguimiento($model);
		
		$extras['modEspera']['trib_id'] = $trib_id;
		$extras['modEspera']['adesde'] = $adesde;
		$extras['modEspera']['cdesde'] = $cdesde;
		$extras['modEspera']['ahasta'] = $ahasta;
		$extras['modEspera']['chasta'] = $chasta;
		$extras['modEspera']['fecha'] = $fecha;
		$extras['modEspera']['fechaHasta'] = $fechaHasta;
		$extras['modEspera']['obs'] = $obs;
		
		return $this->render('_form_espera',['extras' => $extras]);
	}
	
    /**
     * Updates an existing Intima model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
    	$id = $_POST['txLote'];
    	$obs = $_POST['txObs']; 
		$model = $this->findModel($id);
		
		$alert = $model->updateObs($id,$obs);
		
        if ($alert != 1)
			$m = 2;
		else 
			$m = 1;
		
		$this->redirect(['view','id'=>$id,'consulta'=>1, 'alert' => $alert, 'm'=>$m]);
    }
    
    /**
     * 
     */
    public function actionUpdateseguimiento(){
    	
    	$tomo = null;
    	$folio = null;
    	$plazo = date('d/m/Y');
    	
    	if(Yii::$app->request->isPost){
    		
    		$model = Intima::findOne( Yii::$app->request->post('txInti', 0) );
    		
    		if($model == null) $model = new Intima();
    		
    		$tomo = Yii::$app->request->post('txTomo', '');
    		$folio = Yii::$app->request->post('txFolio', '');
    		$plazo = Yii::$app->request->post('dtPlazo', null);	    	
    		
    		$res = $model->modificarSeguimiento($tomo, $folio, $plazo);
    		
    		if($res)
    			return $this->redirect([
									'viewseguimiento',
									'inti_id' => $model->inti_id
									]);
    	}
    	
    	$extras = $this->getExtrasDefaultSeguimiento($model);
    	
    	$extras['modSeguimiento']['tomo'] = $tomo;
    	$extras['modSeguimiento']['folio'] = $folio;
    	$extras['modSeguimiento']['plazo'] = Fecha::usuarioToDatePicker($plazo);
    	
    	return $this->render('_form_seguimiento', [ 'extras' => $extras ]);
    }

    /**
     * Deletes an existing Intima model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBorrar($id)
    {
        $model = $this->findModel($id);

		$alert = $model->borrarLote($id);
        if ($alert != 1)
			$m = 2;
		else 
			$m = 1;
		
		$this->redirect(['view','id'=>$id,'consulta'=>1, 'alert' => $alert, 'm'=>$m]);
    }
    
    /**
     * 
     */
	public function actionDeleteauxiliarseguimiento(){
	
		$model = new Intima();
	
		if(Yii::$app->request->isPost){
			
			$opcion = intval(Yii::$app->request->post('opcion', 0));
			$res = false;
			$model = Intima::findOne(Yii::$app->request->post('inti_id', 0));
			
			if($model == null){
				$model = new Intima();
				$model->inti_id = 0;
				$model->obj_id = '';
				$model->lote_id = 0;
			}
			
			$fecha = Yii::$app->request->post('fecha', '');
			
			switch($opcion){
				case 1:										
					$res = $model->borrarAuxiliarSeguimiento($opcion, $fecha);
					break;
					
				case 2:
					$res = $model->borrarAuxiliarSeguimiento($opcion, $fecha);
					break;
					
				case 3:
					$trib_id = Yii::$app->request->post('trib_id', 0);
					$adesde = Yii::$app->request->post('adesde', 0);
					$cdesde = Yii::$app->request->post('cdesde', 0);
				
					$res = $model->borrarAuxiliarSeguimiento($opcion, $fecha, $trib_id, $adesde, $cdesde);
					break;
			}
			
			if($res) return $this->redirect(['viewseguimiento', 'inti_id' => $model->inti_id, 'm' => 3]);
		}
    	
    	$extras = $this->getExtrasDefaultSeguimiento($model);
		
		return $this->render('seguimiento', ['extras' => $extras]);
	}
	
    /**
     * Función que se ejecuta para realizar la aprobación de un Lote
     */
    public function actionAprobar($id)
    {
        $model = $this->findModel($id);

		$alert = $model->aprobarLote($id);
        if ($alert == 2)
			$m = 2;
		else 
			$m = 1;
		
		$this->redirect(['view','id'=>$id,'consulta'=>1, 'alert' => $alert, 'm'=>$m]);
    }
    
    /**
     * Función que se ejecuta para procesar un lote
     */
    public function actionProcesolote($id)
    {
    	$model = $this->findModel($id);

		$alert = $model->procesoLote($id);
        if ($alert != 1)
			$m = 2;
		else 
			$m = 1;
		
		$this->redirect(['view','id'=>$id,'consulta'=>1, 'alert' => $alert, 'm'=>$m]);
    }
    
    /**
     * Función que se ejecuta para eliminar un objeo de un lote
     */
    public function actionDeleteobjeto($id,$lote_id,$obj_id)
    {
    	$model = $this->findModel($lote_id);

		$alert = $model->deleteObjeto($lote_id,$obj_id);
        if ($alert != 1)
			$m = 2;
		else 
			$m = 1;
		
		$this->redirect(['view','id'=>$lote_id,'consulta'=>1, 'alert' => $alert, 'm'=>$m]);
    }
    
    /**
     * Función que se ejcuta cuando se busca un lote
     */
    public function actionBuscar()
    {
    	if (isset($_POST['txID']))
    		$id = $_POST['txID'];
    		
    	$this->redirect(['view','id'=>$id]);
    }
    
  /**
     * Busca un seguimiento individual y redirige a 'viewseguimiento'
     * 
     * @param integer $_POST['opcion'] Opcion de busqueda:
     * 	1 = Busqueda por inti_id (codigo de intimacion)
     * 	2 = Busqueda por lote_id + obj_id (codigo de lote y codigo de objeto)
     */
    public function actionBuscarseguimiento(){
    	
    	$inti_i = null;
    	$obj_id = null;
    	$lote_id = null;
    	$id = 0;
		
		$opcion = intval(Yii::$app->request->post('opcion', 0));

		switch($opcion){
			
			case 1 :
				$inti_id = intval(Yii::$app->request->post('txInti', 0));
				
				$id = intval(utb::getCampo('v_intima', "inti_id = $inti_id", 'inti_id'));
				
				break;
				
			case 2 :

				$obj_id = Yii::$app->request->post('txObjeto', '');
				$lote_id = intval(Yii::$app->request->post('txLote', 0));
				$tobj = utb::getCampo('intima_lote', 'lote_id = ' . $lote_id, 'tobj');
				if(strlen($obj_id) < 8) $obj_id = utb::getObjeto($tobj, $obj_id);
				
				$id = intval(utb::getCampo('v_intima', "obj_id = '$obj_id' and lote_id = $lote_id", 'inti_id'));

				break;		
		}
		
		return $this->redirect(['viewseguimiento', 'inti_id' => $id]);
	}
    
    /**
     * 
     */
    public function actionEntregas($lote_id = '', $sinResultadosAnteriores = true){
    	
    	$model = new Intima();
    	$mensaje = '';
    	$extras['error'] = [];
    	$extras['dpEntregasMultiples'] = new ArrayDataProvider([
													'allModels' => $model->getEntregas()
																]);
    	
    	if(Yii::$app->request->isGet)
    	{
			
			//se ha obtenido un id por GET
	    	if($lote_id !== null && !empty($lote_id))
	    	{
	    		
	    		//$model = Intima::findOne($lote_id);
	    			
    			$entregas = $model->getEntregas($lote_id, $sinResultadosAnteriores);
    			
    			if($entregas === false)
    			{
    				$mensaje = 'El lote no está aprobado';
    				$entregas = [];
    			}
    			
    			$extras['dpEntregasMultiples'] = new ArrayDataProvider([
													'allModels' => $entregas,
													'key' => 'inti_id',
													'pagination' => [
																	'pageSize' => 50
																	],
													'sort' => [
														'attributes' => [
																'obj_id',
																'obj_nom',
																'dompos_dir' => ['default' => SORT_ASC]
																],
																
														'defaultOrder' => ['dompos_dir' => SORT_ASC]
															]

													]);
	    	}    		
    	} else if(Yii::$app->request->isPost){
    		//si viene por post indica que se solicita guardar los cambios realizados
    		
    		$error = [];
    		
    		//almacena los codigos que se deben cambiar
    		$lote_id = Yii::$app->request->post('lote_id', 0);
    		$codigos = Yii::$app->request->post('codigos', []);
    		$fecha = Yii::$app->request->post('fecha', null);
    		$resultado = Yii::$app->request->post('resultado', null);
    		$distribuidor = Yii::$app->request->post('distribuidor', null);
    		
    		if($lote_id == 0) $extras['error'][] = 'Elija un lote';
    		if(count($codigos) == 0) $extras['error'][] = 'Elija al menos un elemento de la tabla';
    		if($fecha == null) $extras['error'][] = 'Ingrese una fecha';
    		if($resultado == null) $extras['error'][] = 'Elija un resultado';
    		if($distribuidor == null) $extras['error'][] = 'Elija un distribuidor';
    		
    		if(Intima::findOne(['lote_id' => $lote_id]) == null) $extras['error'] = 'El lote no existe';
    			
    		//hay errores, se vuelve a mostrar el formulario de confirmacion
    		if(count($extras['error']) > 0)
    			return $this->render('entregas_multiples', [
    									'model' => new Intima(),
										'extras' => $extras
										]);
										
			
			/*
			 * no hay errores, se procede a grabar
			 */
			 $model = new Intima();
			 $res = $model->grabarEntregasMultiples($lote_id, $fecha, $resultado, $distribuidor, $codigos);
			 
			 //no se grabo, se procede a mostrar el mensaje de error
			if(!$res) $extras['error'] = ['Ocurrio un error al intentar realizar las modificaciones'];
				
			return $this->render('entregas_multiples',
								[
								'model' => $model,
								'extras' => $extras
								]
								);
    	}

    	if($model == null) $model = new Intima();

    	return $this->render('entregas_multiples', [
    						'model' => $model,
    						'extras' => $extras,
    						'mensaje' => $mensaje
    						]);
    }
    
    /**
     * Función que se utiliza para generar mensajes
     */
    public function getMensaje($id)
    {
    	switch($id)
    	{
    		case 1:
    			return 'Los datos se modificaron correctamente.';
    			break;
    			
    		case 2:
    			return 'Los datos no se pudieron grabar.';
    			break;
    			
    		case 3 : return 'Datos grabados correctamente';
    			
    		case 10:
    			return 'El lote seleccionado no existe.';
    			break;
    			
    		case 11:
    			return 'El lote ya se encuentra aprobado.';
    			break;
    			
    		case 12:
    			return 'El lote no se encuentra aprobado.';
    			break;
    		    			
    		case 20 : return '';
    			
    		default:
    			return '';
    	}
    	
    }
    
    public function actionImprimirinti($id,$previo = 0)
    {
    	if ($previo == 1) return $this->render('imprime',['id'=>$id]);
    	
    	$lote_id = (isset($_POST['txImpLote']) ? $_POST['txImpLote'] : 0);
    	$texto_id = (isset($_POST['dlImpTexto']) ? $_POST['dlImpTexto'] : 0);
    	$objetos = (isset($_POST['txObjSelec']) ? $_POST['txObjSelec'] : '');
    	$orden = (isset($_POST['dlImpOrden']) ? $_POST['dlImpOrden'] : 0);
    	$sinmonto = (isset($_POST['ckImpSinMonto']) ? $_POST['ckImpSinMonto'] : 0);
    	$agrupado = (isset($_POST['ckImpAgrup']) ? $_POST['ckImpAgrup'] : 0);
    	$porcuota = (isset($_POST['ckImpCuo']) ? $_POST['ckImpCuo'] : 0);
    	$sinresumen = (isset($_POST['ckImpSinRes']) ? $_POST['ckImpSinRes'] : 0);
    	$sinperiodo = (isset($_POST['ckImpSinCanPer']) ? $_POST['ckImpSinCanPer'] : 0);
    	$sinfirma = (isset($_POST['ckImpSinFirma']) ? $_POST['ckImpSinFirma'] : 0);
    	$sinrecibo = (isset($_POST['ckImpSinRec']) ? $_POST['ckImpSinRec'] : 0);
    	$caracter = (isset($_POST['dlImpCaract']) ? $_POST['dlImpCaract'] : 0);
    	$emp = (isset($_POST['dlImpEmp']) ? $_POST['dlImpEmp'] : 0);
    	$firma1 = (isset($_POST['dlImpFirma1']) ? $_POST['dlImpFirma1'] : 0);
    	$firma1_img = (isset($_POST['ckImpImg1']) ? $_POST['ckImpImg1'] : 0);
    	$firma2 = (isset($_POST['dlImpFirma2']) ? $_POST['dlImpFirma2'] : 0);
    	$firma2_img = (isset($_POST['ckImpImg2']) ? $_POST['ckImpImg2'] : 0);
    	$sub1 = null;
    	$sub2 = null;
    	$sub3 = null;
    	
    	$criterio = utb::getCampo('Intima_TCaracter','cod='.$caracter);
    	$texto1 = utb::getCampo('Intima_Firma','firma_id='.$firma1).'<br>'.utb::getCampo('Intima_Firma','firma_id='.$firma1,'cargo'); 
    	
    	$ocultar = ($sinmonto == 1 ? "S" : "N");
        $ocultar .= ($sinresumen == 1 ? "S" : "N");
        $ocultar .= ($sinperiodo == 1 ? "S" : "N");
        $ocultar .= ($sinfirma == 1 ? "S" : "N");
        $ocultar .= ($sinrecibo == 1 ? "S" : "N");
        $ocultar .= ($agrupado == 1 ? "S" : "N");
        $ocultar .= ($porcuota == 1 ? "S" : "N");
        if ($firma1_img == 1) {
            //$img1 = utb::getCampo('firma',"imagen is not null and firma_id=".$firma,'imagen');
        }else {
            $ocultar .= "S";
        }
        if ($firma2 > 0){
    		$texto2 = utb::getCampo('Intima_Firma','firma_id='.$firma2).'<br>'.utb::getCampo('Intima_Firma','firma_id='.$firma2,'cargo');
    		// obtener imagen de la firma
    		if ($firma2_img == 1) {
	            //$img2 = objInti.GetImagen(cbFirma1.SelectedItem.cod)
	        }else {
	            $ocultar .= "S";
	        }
    	}
    	
    	$array = (new Intima)->Imprimir($lote_id,$texto_id,$objetos,$orden,$sinmonto,$agrupado,$porcuota,$sub1,$sub2,$sub3);
    	    	
    	$pdf = Yii::$app->pdf;
    	$pdf->methods['SetHeader'] = '';
      	$pdf->methods['SetFooter'] = '';
		$pdf->content = $this->renderPartial('//reportes/inti',[
					'datos' => $array,'sub1'=>$sub1,'sub2'=>$sub2,'sub3'=>$sub3,'criterio'=>$criterio,
					'texto1'=>$texto1,'texto2'=>$texto2,'texto3'=>$ocultar,'texto4'=>utb::getCampo('Intima_Emp_Postal',"cod='".$emp."'"),
					'firma1'=>$img1,'firma2'=>$img2
				]);        
        
        return $pdf->render();	
             
    }
    
    public function actionImprimirresu($id)
    {
    	$array = (new Intima)->ImprimirResu($id);
    	    	
    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '30px';
    	$pdf->marginLeft = '10px';
    	$pdf->marginRight = '10px';
		$pdf->content = $this->renderPartial('//reportes/intiresu',['datos' => $array]);        
        
        return $pdf->render();	
    }
    
    public function actionImprimiresta($id)
    {
    	$sub1 = null;
    	$sub2 = null;
    	$array = (new Intima)->ImprimirResu($id,$sub1,$sub2);
    	    	
    	$pdf = Yii::$app->pdf;
    	$pdf->content = $this->renderPartial('//reportes/intiesta',['datos' => $array,'sub1'=>$sub1,'sub2'=>$sub2]);        
        
        return $pdf->render();	
    }
    
    public function actionImprimirseg($id,$obj_id)
    {
    	$sub1 = null;
    	$sub2 = null;
    	$array = (new Intima)->ImprimirSeg($id,$obj_id,$sub1,$sub2);
    	
    	$pdf = Yii::$app->pdf;
		$pdf->methods['SetHeader'] = '';
      	$pdf->methods['SetFooter'] = '';
		$pdf->content = $this->renderPartial('//reportes/intiseg',['datos' => $array,'sub1'=>$sub1,'sub2'=>$sub2]);        
        
        return $pdf->render();
    }

    /**
     * Finds the Intima model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Intima the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
    	$model = new Intima();
    	$res = $model->cargarDatosIntima($id);
        
        if ($res == 1)
        	return $model;
        else 
        	return null;
        	
    }
}
