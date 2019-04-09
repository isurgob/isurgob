<?php

namespace app\controllers\objeto;

use Yii;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Session;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

use app\models\objeto\Rodado;
use app\models\objeto\RodadoAforo;
use app\models\objeto\Objeto;
use app\models\objeto\Domi;
use app\models\config\RodadoVal;

use app\models\taux\tablaAux;

use app\controllers\objeto\ObjetoController;

use app\utils\db\utb;


/**
 * RodadoController implements the CRUD actions for Rodado model.
 */
class RodadoController extends Controller
{

	const RODADO_TOKEN = 'rodadoToken';
	const PARAM_CONSULTA = 'consulta';
	const PARAM_EXTRAS = 'extras';

	private $tab;
	private $consulta;
	private $modelObjeto;
	private $model;
	private $modelDomicilio;

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

    public function beforeAction($action){

    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
		
		$permitirSiempre = [ 'objeto-rodado-aforoexportar', 'objeto-rodado-aforoimprimir' ];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

    	$this->modelDomicilio = null;
    	$this->tab = intval(Yii::$app->request->post('tab', Yii::$app->request->get('tab', 1)));
    	$this->consulta = intval(Yii::$app->request->post(self::PARAM_CONSULTA, Yii::$app->request->get(self::PARAM_CONSULTA, 1)));
    	$codigo = Yii::$app->request->post('obj_id', Yii::$app->request->post('id', Yii::$app->request->get('id', '')));

    	$id = $action->getUniqueId();
    	$token = -1;

    	$cache = new Session();
    	$cache->open();

    	$tokenAnterior = $cache->get(self::RODADO_TOKEN, -2);

    	//se obtienen los datos del domicilio postal
		if(isset($_POST['arrayDomicilioPostal'])) $this->modelDomicilio =  unserialize( urldecode( stripslashes( $_POST['arrayDomicilioPostal'] ) ) );

    	switch($id){

    		case 'objeto/rodado/aforo': return true;

    		case 'objeto/rodado/view' :
    			$this->consulta = 1;
    			$token = 1;
    			break;

    		case 'objeto/rodado/create' :
    			$this->consulta = 0;
    			$token = 0;
    			break;

    		case 'objeto/rodado/update' :
    			$this->consulta = 3;
    			$token = 3;
    			break;

    		case 'objeto/rodado/delete' :
    			$this->consulta = 2;
    			$token = 2;
    			break;

    		default :
    			$token = -1;
    	}


    	$cache->set(self::RODADO_TOKEN, $token);
    	$cache->close();

    	//var_dump($token);


    	if($token !== $tokenAnterior || $token == -1){
    		$this->removerSession();
    		$this->cargarParametrosDefault($codigo, true);
    	} else $this->cargarParametrosDefault($codigo, false);


    	//return false;
    	return true;
    }

    private function removerSession(){

    	$session = new Session();
    	$session->open();

    	$session->set('arregloTitulares', []);
    	$session->remove('dataprovider');
    	//$session->remove(self::RODADO_TOKEN);

    	$session->close();
    }

    private function cargarParametrosDefault($obj_id = '', $tokenCambio = true){

    	$this->model = Rodado::findOne($obj_id);
		$this->modelObjeto = new Objeto();
    	//$this->modelDomicilio = null;

    	if($this->model == null)
			$this->model = new Rodado();
    	else {
    		$this->modelObjeto = $this->modelObjeto->cargarObjeto($this->model->obj_id);

    		//if($tokenCambio) $this->modelObjeto->obtenerTitularesDeBD($this->modelObjeto->obj_id);
			$this->modelObjeto->obtenerTitularesDeBD($this->modelObjeto->obj_id);

    		if($this->modelDomicilio == null)
    			$this->modelDomicilio = Domi::cargarDomi('OBJ', $this->modelObjeto->obj_id, 0);
    	}

    	//se le coloca el nombre del objeto igual al del titular principal en caso de que no se haya ingresado ningun nombre
    	if($this->modelObjeto->nombre === null || trim($this->modelObjeto->nombre)==''){

	    	$titularPrincipal = $this->modelObjeto->getTitularPrincipal();

	    	if(count($titularPrincipal) > 0 && array_key_exists('apenom', $titularPrincipal))
	    		$this->modelObjeto->nombre = $titularPrincipal['apenom'];
    	}
    }

    private function getExtras($id = ''){

    	$ret = [];

    	$ret['tab'] = $this->tab;
    	$ret[self::PARAM_CONSULTA] = $this->consulta;
    	$ret['modelObjeto'] = $this->modelObjeto;
    	$ret['model'] = $this->model;
    	$ret['modelDomicilio'] = $this->modelDomicilio;
        $ret['tieneMiscelaneas']= $this->model->obj_id != '' ? Objeto::ExisteMisc($this->model->obj_id) : false;

    	//var_dump($this->model->valor);
    	return $ret;
    }

    /**
     * Displays a single Rodado model.
     * @param string $id
     * @return mixed
     */
    public function actionView($obj_id = '', $m = '')
    {
    	$extras = $this->getExtras();

    	$m = intval(Yii::$app->request->get('m', 0));
    	$mensaje = ObjetoController::mensajesGenerales($m);

    	if($mensaje === null) $mensaje = $this->getMensaje($m);

        return $this->render('view', [
            'extras' => $extras,
            'mensaje' => $mensaje
        ]);
    }

    /**
     * Creates a new Rodado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$extras = $this->getExtras();

		if(Yii::$app->request->isPost){

			$this->model->scenario = 'insert';
			$arreglo = Yii::$app->request->post();

			if($this->model->load($arreglo) && $this->modelObjeto->load($arreglo)){

				$aux = utb::getVariosCampos('objeto_tipo', 'cod=5', 'autoinc, letra');
				$this->modelObjeto->autoinc = $aux['autoinc'];
				$this->modelObjeto->letra = $aux['letra'];

//				//controla que se ingrese un nombre
//				if($this->modelObjeto->nombre === null || empty(trim($this->modelObjeto->nombre))){
//					$this->modelObjeto->addError('nombre', 'Debe ingresar el Nombre');
//
//					$extras = $this->getExtras();
//					$this->modelObjeto->obj_id = '';
//					return $this->render('view', [self::PARAM_EXTRAS => $extras]);
//				}

				$this->model->domicilioPostal = $this->modelDomicilio;

				$res = $this->model->grabar($this->modelObjeto);

				if($res) return $this->redirect(['view', 'id' => $this->modelObjeto->obj_id, 'm' => 1]);
			}
		}

		return $this->render('view', ['extras' => $extras]);
    }

    /**
     * Updates an existing Rodado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate()
    {

    	if(Yii::$app->request->isPost){

    		$this->model->scenario = 'update';
    		$arreglo = Yii::$app->request->post();

    		if($this->modelObjeto->load($arreglo) && $this->model->load($arreglo)){


    			//controla que se ingrese un nombre
				if($this->modelObjeto->nombre === null || trim($this->modelObjeto->nombre) == ''){
					$this->modelObjeto->addError('nombre', 'Debe ingresar el Nombre del Rodado');

					$extras = $this->getExtras();
					return $this->render('view', [self::PARAM_EXTRAS => $extras]);
				}

    			$this->modelObjeto = $this->modelObjeto->cargarObjeto($this->modelObjeto->obj_id);
    			$this->model = Rodado::findOne($this->modelObjeto->obj_id);

    			$this->model->scenario = 'update';

    			$this->modelObjeto->load($arreglo);
    			$this->model->load($arreglo);

    			$this->model->domicilioPostal = $this->modelDomicilio;

    			$res = $this->model->grabar($this->modelObjeto);

    			if($res) return $this->redirect(['view', 'id' => $this->modelObjeto->obj_id, 'm' => 1]);
    		}
    	}

		$extras = $this->getExtras();

		return $this->render('view', ['extras' => $extras]);
    }

    /**
     * Deletes an existing Rodado model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete()
    {
		if(Yii::$app->request->isPost){

    		$this->model->scenario = 'delete';
    		$arreglo = Yii::$app->request->post();

    		if($this->model->load($arreglo) && $this->modelObjeto->load($arreglo)){

    			$this->model->scenario = 'delete';

    			if($this->model->borrar($this->modelObjeto)){
    				$this->removerSession();
    				return $this->redirect(['view', 'id' => $this->model->obj_id, 'm' => 1]);
    			}
    		}
    	}

		$extras = $this->getExtras();

		return $this->render('view', [
			self::PARAM_EXTRAS => $extras
		]);
    }

    /**
     *
     * @param boolean $fallecido Si se quiere listar los datos de fallecidos, de lo contrario se listan los datos de la cuenta de cementerio
     */
    public function actionListado(){

    	$cond = "";
    	$descr = "";

    	if (isset($_POST['txcriterio']) and $_POST['txcriterio'] != "") $cond = $_POST['txcriterio'];
    	if (isset($_POST['txdescr']) and $_POST['txdescr'] != "") $descr = $_POST['txdescr'];

    	$session = new Session;
		$session->open();


    	if ($cond == "")
    	{
	        return $this->render('list_op');
    	} else {

    		$session = new Session;
			$session->open();
			$session['order'] = '';
			$session['by'] = '';
			$session['cond'] = $cond;
			$session['descr'] = $descr;
			$session['tobj'] = isset($_POST['dlTObj']) ? $_POST['dlTObj'] : 0;
			$session->close();

    		return $this->redirect(['list_res']);
    	}
    }

	public function actionList_res()
    {

    		$session = new Session;
			$session->open();
	        $session['order'] = 'obj_id';

	        $desc = $session['descr'];
	        $cond = $session['cond'];

	        $session->close();

			return $this->render('list_res',
				[
				'desc' => $desc,
				'cond' => $cond
				]);
    }

    public function actionCambio($id, $taccion){


    	if(Yii::$app->request->isPost){

    		$arreglo = Yii::$app->request->post();
    		$this->model->scenario = 'cambio';

    		if($this->modelObjeto->load($arreglo) && $this->model->load($arreglo))
    			if($this->model->grabarCambio($this->modelObjeto))
    				return $this->redirect(['view', 'id' => $this->modelObjeto->obj_id, 'm' => 1]);
    	}

    	$this->model->taccion = $taccion;
    	$extras = $this->getExtras();

    	return $this->render('_cambio', ['extras' => $extras]);
    }

	/**
	 * Función que se utiliza para realizar el ABM de los aforos.
	 */
	public function actionAforo( $id = '', $c = 1, $m = 0, $anio = 0 ){

		if( $anio == 0 ){
			$anio = date( 'Y' );
		}

		$scenario 	= '';
		$mensaje 	= null;
		$res 		= false;
		$model 		= RodadoAforo::findOne([ 'aforo_id' => $id ]);

		if($model == null) $model = new RodadoAforo();

		$c = intval($c);
		$m = intval($m);

		if(Yii::$app->request->isGet && $c == 0) $model = new RodadoAforo();

		$model->anioSeleccionado = $anio;
		$model->obtenerValuaciones( $model->anioSeleccionado );

		if( Yii::$app->request->isPjax ){
			if( Yii::$app->request->get( '_pjax', '' ) == "#form_aforo_pjaxCambiaAnio" ){

				$anio = Yii::$app->request->get( 'anio', 0 );

				if( strlen( $anio ) == 4 ){
					return $this->redirect([ 'aforo', 'id' => $model->aforo_id, 'c' => 1, 'anio' => $anio ]);
				}

			}
		}
		if(Yii::$app->request->isPost){

			switch($c){

				case 0: $scenario = 'insert'; break;
				case 2: $scenario = 'delete'; break;
				case 3: $scenario = 'update'; break;
			}

			if($scenario != ''){

				$model->setScenario($scenario);

				if($model->load(Yii::$app->request->post())){

					$model->setScenario($scenario);

					if($c == 2 || $c == 3){

						$model = RodadoAforo::findOne(['aforo_id' => $model->aforo_id]);

						if($model == null){

							$model = new RodadoAforo();
							$model->addError($model->aforo_id, 'El aforo no existe');

						} else{
							$model->setScenario($scenario);
							$model->load(Yii::$app->request->post());
						}
					}

					if($c == 0 || $c == 3) $res = $model->grabar();
					else if($c == 2) $res = $model->borrar();

					if($res) return $this->redirect(['aforo', 'id' => $model->aforo_id, 'c' => 1, 'm' => 1, 'anio' => $model->anioSeleccionado]);
				}
			}
		}

		$model->anioSeleccionado = $anio;
		if($m > 0) $mensaje = ObjetoController::mensajesGenerales($m);

		$extras = [];
		$extras['consulta'] = $c;
		$extras['model'] = $model;
		$extras['mensaje'] = $mensaje;

		return $this->render('_form_aforo', $extras);
	}
	
	public function actionAforoexportar(){
		
		$fabrica	= intVal( Yii::$app->request->post('fabrica', 0) );
		$aforo		= Yii::$app->request->post('aforo', '');
		$marca_cod	= intVal( Yii::$app->request->post('marca_cod', 0) );
		$tipo_cod	= intVal( Yii::$app->request->post('tipo_cod', 0) );
		$modelo_cod	= intVal( Yii::$app->request->post('modelo_cod', 0) );
		$marca_nom	= Yii::$app->request->post('marca_nom', '');
		$tipo_nom	= Yii::$app->request->post('tipo_nom', '');
		$modelo_nom	= Yii::$app->request->post('modelo_nom', '');
		
		$dp = ( new tablaAux )->buscarModeloAforo($fabrica, $aforo, $marca_cod, $tipo_cod, $modelo_cod, $marca_nom, $tipo_nom, $modelo_nom);
		
		return json_encode([ 'datos' => $dp->getModels(), 'campos_desc' => 'Codigo, Origen, Fabrica, Marca, Tipo, Modelo, Desde, Hasta, Valor' ]);
	}
	
	public function actionAforoimprimir( $f, $a, $mc, $tc, $moc, $mn, $tm, $mon ){
		
		$dp = ( new tablaAux )->buscarModeloAforo($f, $a, $mc, $tc, $moc, $mn, $tm, $mon);
		
		$datos['dataProviderResultados'] = new ArraydataProvider(['allModels' => $dp->getModels(), 'pagination' => false]);
		
		$datos['descripcion'] = '';
		
		$pdf = Yii::$app->pdf;
      	$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/reportelistado', ['datos' => $datos, 'titulo' => 'Listado de Aforos' ]);

		return $pdf->render();
	}

	/**
     *
     * @param integer $_POST['opcion'] Opcion de busqueda:
     * 	1 = Busqueda por dominio
     * 	2 = Busqueda por codigo de objeto
     * 	3 = Busqueda por dni (TODO)
     */
    public function actionBuscar(){

    	$cond='';
		$objeto = null;
		$cant = false;

		$opcion = intval(Yii::$app->request->post('opcion', 0));
		$vista = 'v_rodado';

		switch($opcion){

			case 1 :
				$cond = "upper(dominio) = upper('" . Yii::$app->request->post('txDominio', '') . "')";
				break;

			case 2 :
				$objeto = Yii::$app->request->post('txCodigoObjeto', '');
				if (strlen($objeto) < 8) $objeto = utb::GetObjeto(5,(int)$objeto);

				$m = 0;
      			if ( utb::getNombObj( "'".$objeto."'" ) == '' ) $m = 11;

      			return $this->redirect(['view', 'id' => $objeto, 'm' => $m]);
				break;

			case 3 :

				$cond = "obj_id In (Select obj_id From v_objeto_tit Where ndoc = '" . strtoupper(Yii::$app->request->post('txDni', 0)) . "')";
				break;
				
			case 4 :

				$cond = "num_cuit = '" . str_replace("-","",strtoupper(Yii::$app->request->post('txCUIT', 0))) . "'";
				break;	

			default: return $this->render('buscar');
		}


		if($cond != '') $cant = (int) utb::getCampo($vista, $cond, 'count(obj_id)');

		if($cant !== false){

			switch($cant){

				case 0 : return $this->redirect(['view', 'id' => '', 'm' => 11]); break;
				case 1 : return $this->redirect( [ 'view', 'id' => utb::getCampo( $vista, $cond, 'obj_id' ) ] ); break;
				default :

					$session = new Session;
					$session->open();
					$session['order'] = '';
					$session['by'] = '';
					$session['cond'] = $cond;
					$session['tobj'] = 4;
					$session['desc'] = 'asc';
					$session->close();

					return $this->redirect([
								'list_res'
								]);
			}
		}

		return $this->redirect(['view', 'id' => '', 'm' => 11]);
	}

	public function actionImprimir($id)
    {
    	$sub1 = null;

    	$array = (new Rodado)->Imprimir($id,$sub1);
		
		if($array[0]['dompos_dir'] == null){
    		$domi = Domi::cargarDomi('OBJ', $id, 0); 
			$array[0]['dompos_dir'] = $domi->domicilio;
		}		

    	$pdf = Yii::$app->pdf;
		$pdf->content = $this->renderPartial('//reportes/rodado',['datos' => $array,'sub1'=>$sub1]);

        return $pdf->render();
    }

    public function actionImprimiraforo($id)
    {
    	$model = RodadoAforo::findOne(['aforo_id' => $id]);

    	$pdf = Yii::$app->pdf;
		$pdf->content = $this->renderPartial('//reportes/rodadoaforo',['datos' => $model]);

        return $pdf->render();
    }
	
	public function actionRevisiontecnica( $obj_id, $texto )
    {
    	$sub1 = null;

    	$array = (new Rodado)->RevisionTecnica($obj_id, $texto);

    	$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/rodadorevisiontecnica',['datos' => $array]);

        return $pdf->render();
    }
	
	private function getMensaje($m = 0){


		switch($m){

			case 11 : return 'No hay datos que coincidan con el criterio de búsqueda';
		}

		return null;
	}
}
