<?php

namespace app\controllers\objeto;

use Yii;
use app\models\objeto\Cem;
use app\models\objeto\CemFall;
use app\models\objeto\Objeto;
use app\models\objeto\Domi;
use app\models\objeto\CemListado;
use app\models\taux\CemCuadro;

use app\controllers\objeto\ObjetoController;

use yii\data\ArrayDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Session;

use yii\filters\VerbFilter;

use app\utils\db\utb;
use yii\data\ActiveDataProvider;
/**
 * CemController implements the CRUD actions for Cem model.
 */
class CemController extends Controller
{
	private $model = null;
	private $modelObjeto = null;
	private $modelDomicilio = null;
	private $modelFallecido = null;
	
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


	private function getExtras($fallecidos, $alquileres){
    						
    	$extras['dpFallecidos'] = new ArrayDataProvider([
    							'allModels' => $fallecidos,
    							'totalCount' => count($fallecidos),
    							'pagination' => false
    						]);
    						
    	
		$extras['dpAlquileres'] = new ArrayDataProvider([
							'allModels' => $alquileres,
							'totalCount' => count($alquileres),
							'pagination' => false
						]);	
    	
    						
    	return $extras;	
	}
	
	private function getExtrasFall($model){
		
		$servicios = CemFall::getServicios($model->fall_id);;
		
		$extras['dpServiciosFall'] = new ArrayDataProvider([
									'allModels' => $servicios,
									'totalCount' => count($servicios),
									'pagination' => false
								]); 
		
		
		return $extras;
	}
	
	private function borrarSession(){
		
		$session = Yii::$app->session;
		$session->open();
		
		$session['arregloTitulares'] = [];
		
		$session->remove('banderaTitulares');
		//$session->remove('action');
		$session->remove('codigo');
		$session->remove('nombre');
		$session->remove('porc');
		$session->remove('relac');
		$session->remove('tvinc');
		$session->remove('princ');
		$session->remove('est');
		$session->remove('BD');
		
		$session->close();
	}
	
	public function beforeAction($action){
		
		$operacion = str_replace("/", "-", Yii::$app->controller->route);
		
		$permitirSiempre = ['objeto-cem-obtenerobjeto', 'objeto-cem-grabarcbioparcela'];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
	    
		if(!parent::beforeAction($action))
			return false;
			
		$this->model = new Cem();
		$this->modelObjeto = new Objeto();
		$this->modelDomicilio = new Domi();
		$this->modelFallecido = new CemFall();
			
		$session = Yii::$app->session;
		$session->open();
		
		//se obtienen los datos del domicilio postal
		if(isset($_POST['arrayDomicilioPostal'])) $this->modelDomicilio =  unserialize( urldecode( stripslashes( $_POST['arrayDomicilioPostal'] ) ) );
		
		$token = -1;
		$tokenAnterior = $session->get('token', null);
		
		switch($action->getUniqueId()){
			
			case 'objeto/cem/create' : 
				$token = 0;
				$session->set('action', 1); 
				break;
				
			case 'objeto/cem/update' : 
				$token = 3;
				$session->set('action', 2); 
				break;
				
			case 'objeto/cem/delete' :
				$token = 2;
				$session->set('action', 3);
				break;
				
			case 'objeto/cem/alquiler':
				$session->close();
				return true;
			
			default : $token = -1; break;
		}
		
		$session->set('token', $token);
		
		if($tokenAnterior === null || $token === -1 || $tokenAnterior != $token){

			$session->close();
			$this->borrarSession();
			return true;
		}
		
		$session->close();
		return true;
	}

    /**
     * Displays a single Cem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id = '', $m = '')
    {    	
    	$fallecidos = [];
    	$alquileres = [];
    	
    	$m = intval(Yii::$app->request->get('m', 0));
    	
    	if($id != ''){
    		
    		$this->model = Cem::findOne($id);
    		
    		if($this->model != null){
    			
	    		$m = $m === 2 ? $m = '' : $m;
	    		
	    		$this->modelObjeto = (new Objeto)->cargarObjeto($id);
	    		$this->modelDomicilio = $this->modelDomicilio->cargarDomi('OBJ', $this->model->obj_id, 0);
	    		$this->modelObjeto->obtenerTitularesDeBD($id);
	    		$fallecidos = $this->model->getFallecidos($id);
	    		$alquileres = $this->model->getAlquileres($id);
    		}
    	} 
    		
    	if($this->model == null){
    		$this->model = new Cem();
    		$this->modelObjeto = new Objeto();
    		$this->modelDomicilio = new Domi();
    	} 
    	
    	$extras = $this->getExtras($fallecidos, $alquileres);
    	
    	$mensaje = ObjetoController::mensajesGenerales($m);
    	if($mensaje === null) $mensaje = $this->getMensaje($m);
    	
        return $this->render('view', [
            'model' => $this->model,
            'modelObjeto' => $this->modelObjeto,
            'modelDomicilio' => $this->modelDomicilio,            
            'extras' => $extras,
            'mensaje' => $mensaje
        ]);
    }
    
    public function actionViewfall($id = '', $m = ''){
    	
    	if($id != ''){
    		
    		$this->modelFallecido = CemFall::findOne($id);
    		
    		if($this->modelFallecido != null){
    			    			
    			if($this->modelFallecido->obj_id != null && trim($this->modelFallecido->obj_id) != ''){
    				$this->model = Cem::findOne($this->modelFallecido->obj_id);
    				$this->modelObjeto = (new Objeto())->cargarObjeto($this->modelFallecido->obj_id);
    			} else {
    				$this->model = new Cem();
    				$this->modelObjeto = new Objeto();
    			}
    		}
    		else {
    		
    			$this->modelFallecido = new CemFall();
    			$this->model = new Cem();
    			$this->modelObjeto = new Objeto();	
    		}
    	}
    	
    	if($this->model == null) $this->model = new Cem();
    	
    	$extras = $this->getExtrasFall($this->modelFallecido);
    	
    	$m = intval(Yii::$app->request->get('m', 0));
    	$mensaje = ObjetoController::mensajesGenerales($m);
    	
    	if($mensaje === null) $mensaje = $this->getMensaje($m);
    	 
    	return $this->render('viewfall', 
    						[
    						'model' => $this->modelFallecido,
    						'modelCem' => $this->model,
    						'modelObjeto' => $this->modelObjeto,
    						'consulta' => 1,
    						'mensaje' => $mensaje,
    						'extras' => $extras
    						]);
    }

    /**
     * Creates a new Cem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {		
		$extras = $this->getExtras([], []);
		
		$this->modelObjeto->autoinc = utb::getCampo("objeto_tipo","Cod=4","autoinc");
		$this->modelObjeto->letra = utb::getCampo("objeto_tipo","Cod=4","letra");
		
		if(Yii::$app->request->isPost){
			
			$errorObjeto = [];
			$errorModel = [];
			$errores = [];
			
			//al parecer no se valida, solamente se cargan los datos pero da siempre true
			$this->modelObjeto->load(Yii::$app->request->post());
			
			//4 corresponde a cementerio
			$this->modelObjeto->tobj = 4;

			//asignacion del domicilio postal
			$this->modelObjeto->domi_postal = $this->modelDomicilio->domicilio;
				
			//arreglo de errores en el modelo de objeto
			$session = new Session;
        	$session->open();
			$errorObjeto = $this->modelObjeto->validarConErrorModels( count($session->get('arregloTitulares', [])) );				
			$session->close();
			
			//scenario a validar
			$this->model->scenario = 'insert';
			
			//se carga y valida contra el escenario actual el modelo Cem. En caso de algun error, se le asigna a $errorModel los strings de los errores
			if(!$this->model->load(Yii::$app->request->post()))
				foreach($this->model->getErrors() as $clave => $valor)
					$errorModel = array_merge($errorModel, array_values($valor));
			
			//contiene todos los errores como strings, de objeto y del modelo Cem
			$errores = array_merge($errorObjeto, $errorModel);
			
			//hay errores de iniciacion, se procede a mostrar la vista con los errores
			if(count($errores) > 0){
				
				return $this->render('view', [
		        	'model' => $this->model,
		        	'modelObjeto' => $this->modelObjeto,
		        	'modelDomicilio' => $this->modelDomicilio,
		        	'extras' => $extras,
		        	'error' => $errores,
		        	'consulta' => 0
        		]);
			}
			
			
			//se le asigna el domicilio al modelo
			$this->model->domicilio = $this->modelDomicilio->domicilio;
			
			//por el momento no hay errores, se valida que se haya ingresado domicilio postal
			$session = new Session;
        	$session->open();
			if ( count($session->get('arregloTitulares', [])) and  $this->model->domicilio == null || $this->model->domicilio == '')
				$errores[] = 'No se ingresó el domicilio postal.';
			$session->close();	
			
			/*
			 * SE PASARON LAS VALIDACIONES DE OBJETO, CEM Y DOMICILIO
			 */
			
			//Voy a buscar el código del titular principal
        	$session = new Session;
        	$session->open();
        	
        	$array = $session->get('arregloTitulares', []);
        	$keys = array_keys($array);
        	
        	
        	foreach($keys as $clave)
        		if ($array[$clave]['princ'] != '') 
        			$this->modelObjeto->num =$array[$clave]['num'];
        		        	
        	
        	//se validan los titulares
        	if ( count($session->get('arregloTitulares', [])) > 0 ){
				$validarTitulares = $this->modelObjeto->validarTitulares();
				
				if($validarTitulares != '') $errores[] = $validarTitulares;
			}	
        	$session->close();
			
			
			//no hay errores, se procede a grabar
			if(count($errores) == 0){				
				
				$transaction = Yii::$app->db->beginTransaction();
	            
            	try {
    				// inserto objeto
    				$error = $this->modelObjeto->grabar();
    				
    				if ($error != '') $errores[] = $error;
    			
    				// 	inserto cementerio
    				$this->model->obj_id = $this->modelObjeto->obj_id;

            		
            		if(!$this->model->grabar()){
            			
            			$transaction->rollback();
            			
            			if($this->model->hasErrors())
	            			foreach($this->model->getErrors() as $clave => $valor)
								$errores = array_merge($errores, array_values($valor));
            			
            			
            			return $this->render('view', [
				        	'model' => $this->model,
				        	'modelObjeto' => $this->modelObjeto,
				        	'modelDomicilio' => $this->modelDomicilio,
				        	'extras' => $extras,
				        	'error' => $errores,
				        	'consulta' => 0
				        ]);
            		};
            		//se termina de insertar cementerio
            	    
            		// INICIO Insertar los domicilios
            		if ($this->modelDomicilio->domicilio !== null){
            			$this->modelDomicilio->obj_id = $this->modelObjeto->obj_id;
            			
            			$error = $this->modelDomicilio->grabar();
            			
            			if($error != '') $errores[] = $error;
            		} 
					// FIN Insertar los domicilios

					//INICIO insertar titulares
					$error = $this->modelObjeto->grabarTitulares();
					
					if($error != '') $errores[] = $error;
					//FIN insertar titulares

    				if (count($errores) == 0){
    					
    					$transaction->commit();
    					
    					return $this->redirect(['view', 
				        	'id' => $this->model->obj_id,
				        	'm' => 1
				        ]);
    				}
    							
    				else {
    					
    					$transaction->rollBack();
    					
    					return $this->render('view', [
				        	'model' => $this->model,
				        	'modelObjeto' => $this->modelObjeto,
				        	'modelDomicilio' => $this->modelDomicilio,
				        	'extras' => $extras,
				        	'error' => $errores,
				        	'consulta' => 0
				        	]);
    				}

				} catch(Exception $e) {
    				$transaction->rollBack();
			    	//throw $e;
			    	$errores[] = $e;
			    	$model->addErrors($errores);
			    	
			    	return $this->redirect(['view', 'id' => $modelObjeto->obj_id, 'consulta' => 1, 'm' => 0]);
				}								
			}
			else{
				
				return $this->render('view', [
			        	'model' => $this->model,
			        	'modelObjeto' => $this->modelObjeto,
			        	'modelDomicilio' => $this->modelDomicilio,
			        	'extras' => $extras,
			        	'error' => $errores,
			        	'consulta' => 0
			        ]);
		    }
		}
		
		
        return $this->render('view', [
        	'model' => $this->model,
        	'modelObjeto' => $this->modelObjeto,
        	'modelDomicilio' => $this->modelDomicilio,
        	'extras' => $extras,
        	'consulta' => 0
        ]);
    }
    
    public function actionCreatefall(){
    	
    	if(Yii::$app->request->isPost){
    		
    		$this->modelFallecido->scenario = 'insert';
    		
    		//el modelo se carga con los datos necesarios que vienen por post
    		if( $this->modelFallecido->load( Yii::$app->request->post() ) ){
    			
    			$res = $this->modelFallecido->grabar();
    			
    			if($res) return $this->redirect(['viewfall', 'id' => $this->modelFallecido->fall_id]);
    		}
    	}
    	
    	$extras = $this->getExtrasFall($this->modelFallecido);
    	
    	return $this->render('viewfall', 
    						[
							'model' => $this->modelFallecido,
							'modelObjeto' => $this->modelObjeto,
							'modelCem' => $this->model,
							'consulta' => 0,
							'extras' => $extras
							]);
    }

    /**
     * Updates an existing Cem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
		$this->modelObjeto = (new Objeto())->cargarObjeto($id);
		
		//se obtienen los datos del domicilio postal
		if(isset($_POST['arrayDomicilioPostal'])) 
			$this->modelDomicilio =  unserialize( urldecode( stripslashes( $_POST['arrayDomicilioPostal'] ) ) );
		else
			$this->modelDomicilio = Domi::cargarDomi('OBJ', $id, 0);
			
		if($this->modelDomicilio == null)
			$this->modelDomicilio = new Domi();
		
		
		
		$fallecidos = $this->model->getFallecidos($id);
		$alquileres = $this->model->getAlquileres($id);
		
		$session = new Session;
		$session->open();
		
		$titulares = $session->get('arregloTitulares', null);
		
		if($titulares == null){
			$this->modelObjeto->obtenerTitularesDeBD($id);
			$session->set('arregloTitulares', $this->modelObjeto->arregloTitulares);
		}
		
		
		$session->close();
		
		$errores =  [];
		
		if(Yii::$app->request->isPost){
			
			$this->model->scenario = 'update';
			
			if($this->model->load(Yii::$app->request->post()) && $this->modelObjeto->load(Yii::$app->request->post())){
				
				//tipo de objeto 4 para cementerio
				$this->modelObjeto->tobj = 4;
				
				//Voy a buscar el código del titular principal
	        	$session = new Session;
	        	$session->open();
	        	
	        	$array = $session['arregloTitulares'];
	        	$keys = array_keys($array);
	        	$session->close();
	        	
	        	foreach($keys as $clave)
	        		if ($array[$clave]['princ'] != '') $this->modelObjeto->num =$array[$clave]['num'];
	        	
	        	
	        	//Seteo los domicilios
				$this->modelObjeto->domi_postal = $this->modelDomicilio->domicilio;
				$this->model->domicilio = $this->modelDomicilio->domicilio;
				
				$error = $this->modelObjeto->validarConErrorModels( count($array) > 0 );
				
				//si el modelo de objeto tiene error o el modelo de cementerio no pasa la validacion, se dibuja todo con los errores
				//primero la validacion para que se ejecute si o si
				if(count($error) > 0){
					
					$errores = $error;
					
					foreach($this->model->getErrors() as $clave => $valor)
						$errores = array_merge($errores, array_values($valor));
						
						
					return $this->render('view', [
						'model' => $this->model,
						'modelObjeto' => $this->modelObjeto,
						'modelDomicilio' => $this->modelDomicilio,
						'consulta' => 3,
						'error' => $errores,
						'extras' => $this->getExtras($fallecidos, $alquileres)
					]);
				}
				
				/*
				 * los modelos no tienen errores
				 */
				
				//Inicio Validar que se hayan ingresado los domicilios					
				if ($this->model->domicilio == '')
					$errores[] = 'No se ingresó el domicilio postal.';
					
				//se validan los titulares
				$validaTitulares = $this->modelObjeto->validarTitulares();
					
				if($validaTitulares != '') $errores[] = $validaTitulares;
				
				//hay errores, se redirige
				if(count($errores) > 0){
					
					return $this->render('view', [
						'model' => $this->model,
						'modelObjeto' => $this->modelObjeto,
						'modelDomicilio' => $this->modelDomicilio,
						'consulta' => 3,
						'error' => $errores,
						'extras' => $this->getExtras($fallecidos, $alquileres)
					]);
				}
				
				
				//se procede a grabar todo ya que no hay errores
				$transaction = Yii::$app->db->beginTransaction();
	            
            	try {
    				// inserto objeto
    				$error = $this->modelObjeto->grabar();
    				
    				if ($error != '') $errores[] = $error;
    			
    				// 	inserto cementerios
    				$this->model->obj_id = $this->modelObjeto->obj_id;
            		
            		if(!$this->model->grabar()){
            			
            			$transaction->rollback();
            			
            			if($this->model->hasErrors())
	            			foreach($this->model->getErrors() as $clave => $valor)
								$errores = array_merge($errores, array_values($valor));
            			
            			
            			$extras = $this->getExtras($fallecidos, $alquileres);
            			
            			return $this->render('view', [
				        	'model' => $this->model,
				        	'modelObjeto' => $this->modelObjeto,
				        	'modelDomicilio' => $this->modelDomicilio,
				        	'extras' => $extras,
				        	'error' => $errores,
				        	'consulta' => 3
				        ]);
            		};
            		
            	    if ($error != '') $errores[] = $error;
            	    
            		// INICIO Insertar los domicilios
            		if ($this->modelDomicilio->domicilio !== null){
            			
            			$this->modelDomicilio->obj_id = $this->modelObjeto->obj_id;
            			$error = $this->modelDomicilio->grabar();
            			if ($error != '') $errores[] = $error;
            		}
					// FIN Insertar los domicilios
					
					//INICIO Insertar los arreglos en memoria
					$error= $this->modelObjeto->grabarTitulares(); //Guardo los titulares
					if ($error != '') $errores[] = $error;					
					//FIN Insertar los arreglos en memoria

    				if (count($errores) == 0)
    				{
    					$transaction->commit();	

    					return $this->redirect(['view', 'id' => $this->modelObjeto->obj_id, 'consulta' => '1', 'm' => 1]);
    					
    				} else {
    					
    					$transaction->rollBack();
    					
    					$model->addErrors($errores);
    					
				        return $this->render('view',[
				        	'model' => $this->model,
				        	'modelObjeto' => $this->modelObjeto,
				        	'modelDomicilio' => $this->modelDomicilio,
				        	'consulta' => 3,
				        	'error' => $errores,
				        	'extras' => $this->getExtras($fallecidos, $alquileres)
				        	]);
    				}

				} catch(Exception $e) {
    				$transaction->rollBack();
			    	//throw $e;
			    	$errores[] = $e;
			    	
			    	return $this->redirect(['view', 'id' => $modelObjeto->obj_id, 'consulta' => 1]);
				}
			}
		}
		
		$extras = $this->getExtras($fallecidos, $alquileres);
		
		if($this->modelObjeto->est == 'B'){
			return $this->render('view', [
				'model' => $this->model,
				'modelObjeto' => $this->modelObjeto,
				'modelDomicilio' => $this->modelDomicilio,
				'extras' => $extras,
				'consulta' => 1 
			]);
		}
		
		
		return $this->render('view', [
			'model' => $this->model,
			'modelObjeto' => $this->modelObjeto,
			'modelDomicilio' => $this->modelDomicilio,
			'extras' => $extras,
			'consulta' => 3 
		]);
    }
    
    public function actionUpdatefall($id){
    	
    	$this->modelFallecido = CemFall::findOne($id);
    	
    	if($this->modelFallecido != null){
    		
    		if($this->modelFallecido->obj_id != null && trim($this->modelFallecido->obj_id) != ''){
	    		$this->model = Cem::findOne($this->modelFallecido->obj_id);
	    		$this->modelObjeto = (new Objeto())->cargarObjeto($this->modelFallecido->obj_id);
    		}
    	}
    	else $this->modelFallecido = new CemFall();
    	
    	
    	if(Yii::$app->request->isPost){
    		
    		$this->modelFallecido->scenario = 'update';
    		
    		//se determina que se puedan cargar los datos
    		if($this->modelFallecido->load(Yii::$app->request->post())){
    			
    			//se carga desde la base de datos el fallecido
    			$this->modelFallecido = CemFall::findOne($this->modelFallecido->fall_id);
    			    			
    			if($this->modelFallecido != null){
    				
    				//se carga con los datos que llegan desde post
    				$this->modelFallecido->scenario = 'update';
    				$this->modelFallecido->load(Yii::$app->request->post());
    				
    				$res = $this->modelFallecido->grabar();
    				
    				if($res)
    					return $this->redirect([
    										'viewfall',
    										'id' => $this->modelFallecido->fall_id,
    										'm' => 16
     										]);
    			}
    		}
    	}
    	
    	$extras = $this->getExtrasFall($this->modelFallecido);
    	
    	return $this->render('viewfall',
    						[
    						'model' => $this->modelFallecido,
    						'modelCem' => $this->model,
    						'modelObjeto' => $this->modelObjeto,
    						'consulta' => 3,
    						'extras' => $extras
    						]
    						);
    }

    /**
     * Deletes an existing Cem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id, $accion, $elimobjcondeuda = "", $tbaja = 0, $obs = "")
    {
        $this->model = $this->findModel($id);
		$this->modelObjeto = (new Objeto())->cargarObjeto($id);
		$this->modelDomicilio = Domi::cargarDomi('OBJ', $id, 0);
		
		if($this->modelDomicilio == null) $this->modelDomicilio = new Domi();
		
		$this->modelObjeto->obtenerTitularesDeBD($id);
		$fallecidos = $this->model->getFallecidos($id);
		$alquileres = $this->model->getAlquileres($id);
		
		$session = new Session;
		$session->open();
		
		$session->set('arregloTitulares', $this->modelObjeto->arregloTitulares);
		
		$session->close();
		
		$extras = $this->getExtras($fallecidos, $alquileres);
		
		if($this->modelObjeto->est != 'D'){
			return $this->render('view', [
				'model' => $this->model,
				'modelObjeto' => $this->modelObjeto,
				'modelDomicilio' => $this->modelDomicilio,
				'extras' => $extras,
				'consulta' => 1 
			]);
		}
		
		if($accion == 1){
		
			$this->modelObjeto->tbaja = $tbaja;
        	$this->modelObjeto->elimobjcondeuda = $elimobjcondeuda;
        	
        	$error = $this->modelObjeto->borrarConErrorSummary($obs);
        	$errores = [];
        	
        	if ($error != '')
        		$errores[] = $error;
        	
        	
        	//Si no se encontraron errores durante la eliminación del Objeto correspondiente
        	if (count($errores) == 0){
        		return $this->redirect([
						'view',
						'id' => $this->model->obj_id,
						'm' => 11
						]);
						
        	} else {
        		
        		return $this->render('view', [
		        	'model' => $this->model,
		        	'modelObjeto' => $this->modelObjeto,
		        	'modelDomicilio' => $this->modelDomicilio,
		        	'error' => $errores,
		        	'extras' => $extras,
		        	'consulta' => 2,
		        	]);


        	}
		}
		
		
		if($this->modelObjeto->est == 'B'){
			return $this->render('view', [
				'model' => $this->model,
				'modelObjeto' => $this->modelObjeto,
				'modelDomicilio' => $this->modelDomicilio,
				'extras' => $extras,
				'consulta' => 1 
			]);
		}
		
		
		return $this->render('view', [
			'model' => $this->model,
			'modelObjeto' => $this->modelObjeto,
			'modelDomicilio' => $this->modelDomicilio,
			'extras' => $extras,
			'consulta' => 2 
		]);
    }
    
    public function actionDeletefall($id, $accion = 0){
    	
    	$this->modelFallecido = CemFall::findOne($id);
    	
    	if($this->modelFallecido != null){
    		
    		if($this->modelFallecido->obj_id != null && trim($this->modelFallecido->obj_id) != ''){
	    		$this->model = Cem::findOne($this->modelFallecido->obj_id);
	    		$this->modelObjeto = (new Objeto())->cargarObjeto($this->modelFallecido->obj_id);
    		}
    	}
    	else $this->modelFallecido = new CemFall();
    	
    	
    	if($accion == 1){
    		
    		$this->modelFallecido->scenario = 'delete';
		
			$res = $this->modelFallecido->borrar();
			
			if($res)
				return $this->redirect(['viewfall',
										'id' => $this->modelFallecido->fall_id,
										'm' => 15
										]);	
    	}
    	
    	$extras = $this->getExtrasFall($this->modelFallecido);
    	
    	return $this->render('viewfall',
    						[
    						'model' => $this->modelFallecido,
    						'modelCem' => $this->model,
    						'modelObjeto' => $this->modelObjeto,
    						'consulta' => 2,
    						'extras' => $extras
    						]
    						);
    }
    
    public function actionTraslado($id = '', $isDestino = false){
    	
    	$modelDestino = new Cem();
    	
    	$metodo = Yii::$app->request->isPost ? 'post' : 'get';
    	
    		
    	if($id != '') $this->model = Cem::findOne($id);
    		
    	$idObjeto = Yii::$app->request->$metodo('idObjeto', 0);
    	if($idObjeto != ''){
    		
    		if(strlen($idObjeto) < 8) $idObjeto = utb::GetObjeto(4,(int)$idObjeto);
    		
    		if(!$isDestino)
    			$this->model = Cem::findOne($idObjeto);
    		else $modelDestino = Cem::findOne($idObjeto);
    	}
    	
    	if($this->model == null) $this->model = new Cem();
    	if($modelDestino == null){
    		$modelDestino = new Cem();
    		$modelDestino->obj_id = $idObjeto;
    	} 
    	
    	$accion = Yii::$app->request->$metodo('taccion', 0);
    	
    	if(intval($accion) == 1){
    		
    		$this->model->scenario = 'traslado';
    		$modelDestino->scenario = 'select';
    		
    		//se cargan los 2 modelos, los datos del cementerio destino vienen en el arreglo CemDestino
    		if($this->model->load(Yii::$app->request->$metodo()) && $modelDestino->load(Yii::$app->request->$metodo(), 'CemDestino')){
    			
    			if(strlen($modelDestino->obj_id) < 8) $modelDestino->obj_id =  utb::getObjeto(4, (int)$modelDestino->obj_id);
    			
    			$modelDestino = Cem::findOne($modelDestino->obj_id);
    			
    			if($modelDestino != null){
    					
	    			if(strlen($this->model->obj_id) < 8) $this->model->obj_id =  utb::getObjeto(4, (int)$this->model->obj_id);
	    			
	    			$res = $this->model->trasladarCuenta($modelDestino->obj_id);

	    			if($res)
	    				return $this->redirect(['view',
	    										'id' => $this->model->obj_id,
	    										'm' => 17
	    										]);
    			}
    			else {
    				$modelDestino = new Cem();
    				$modelDestino->obj_id = Yii::$app->request->$metodo('CemDestino', [])['obj_id'];
    				$modelDestino->addError($modelDestino->obj_id, "La cuenta de cementerio $modelDestino->obj_id no existe");
    			}
    		}    		
    	}
    	
    	return $this->render('traslado', ['model' => $this->model, 'modelDestino' => $modelDestino]);
    }
    
    /**
     * 
     * @param integer $_POST['opcion'] Opcion de busqueda:
     * 	1 = Busqueda por obj_id (codigo del objeto)
     * 	2 = Busqueda por nomeclatura
     * 	3 = Busqueda por cod_ant (codigo anterior)
     */
    public function actionBuscar(){
    	
    	$cond='';
		$objeto = null;
		$cant = false;
		
		$opcion = Yii::$app->request->post('opcion', 0);
		
		switch($opcion){
			
			case 1 :
				$objeto = Yii::$app->request->post('txObjeto', '');
				if (strlen($objeto) < 8) $objeto = utb::GetObjeto(4,(int)$objeto);
				
				$m = 0;
      			if ( utb::getNombObj( "'".$objeto."'" ) == '' ) $m = 13;
      			return $this->redirect(['view', 'id' => $objeto, 'm' => $m]);
				break;
				
			case 2 :
				
				$tipo = strtoupper(Yii::$app->request->post('dlTipo', ''));
				
				if($tipo == ''){
					
					$cuadro = Yii::$app->request->post('dlCuadro', null);
					$cuerpo = Yii::$app->request->post('dlCuerpo', null);
					$piso = Yii::$app->request->post('txPiso', null);
					$fila = Yii::$app->request->post('txFila', null);
					$nume = Yii::$app->request->post('txNume', null);
					$bis = Yii::$app->request->post('txBIS', null);
					
					if($cuadro != null) $cond = "cua_id = '$cuadro'";
					if($cuerpo != null) $cond .= $cond == '' ? "cue_id = '$cuerpo'" : " And cue_id = '$cuerpo'";
					if($piso != null) $cond .= $cond == '' ? "piso = $piso" : " And piso = $piso";
					if($fila != null) $cond .= $cond == '' ? "fila = '$fila'" : " And fila = '$fila'";
					if($nume != null) $cond .= $cond == '' ? "nume = '$nume'" : " And nume = '$nume'";
					if($bis != null) $cond .= $cond == '' ? "bis = '$bis'" : " And bis = '$bis'";
				}
				else $cond = "tipo = '$tipo'";
				
				break;
				
			case 3 :
				$cond = "cod_ant = '" . strtoupper(Yii::$app->request->post('txCodAnt', '')) . "'";
				break;
		}
		
		
		if($cond != '') $cant = (int) utb::getCampo('v_cem', $cond, 'count(*)');
		
		if($cant !== false){
			
			switch($cant){
				
				case 0 : return $this->redirect(['view', 'id' => '', 'm' => 14]); break;
				case 1 : return $this->redirect( [ 'view', 'id' => utb::getObjeto( 4, (int) utb::getCampo( 'v_cem', $cond, 'obj_id' ) ) ] ); break;
				default :
					
				$model = new CemListado();
                $model->tipo = Yii::$app->request->post('dlTipo', null);
				$model->cuadro_id = Yii::$app->request->post('dlCuadro', null);
                /*$model->cuerpo_id = Yii::$app->request->post('dlCuerpo', null);
                $model->piso = Yii::$app->request->post('txPiso', null);
                $model->fila = Yii::$app->request->post('txFila', null);
                $model->nume = Yii::$app->request->post('txNume', null);*/
               

                $res = $model->buscar();
                $datos = ListadocemController::datosResultado($model, $res);
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
			}
		}
		
		return $this->redirect(['view', 'id' => '', 'm' => 14]);
	}

	 /**
     * 
     * @param integer $_POST['opcion'] Opcion de busqueda:
     * 	1 = Busqueda por fall_id (codigo de fallecido)
     * 	2 = Busqueda por ndoc (numero de documento)
     * 	3 = Busqueda por apenom (appelido y nombre)
     */
	public function actionBuscarfall(){
		
		$cond='';
		$objeto = null;
		$cant = false;
		
		$opcion = Yii::$app->request->post('opcion', 0);
		
		switch($opcion){
			
			case 1 :
				$m = 0;
				$codigo = Yii::$app->request->post('txCodigo', '');
				
				$codigo = utb::getCampo('cem_fall', "fall_id = $codigo", 'fall_id');
				
				if($codigo === false){
					$m = 14;
					$codigo = '';
				}
					
      			return $this->redirect(['viewfall', 'id' => $codigo, 'm' => $m]);
				break;
				
			case 2 :
				
				$cond = "ndoc = " . Yii::$app->request->post('txDocumento', 0);
				
				break;
				
			case 3 :
				$cond = "apenom Like '" . strtoupper(trim(Yii::$app->request->post('txNombre', ''))) . "%'";
				break;
		}
		
		
		if($cond != '') $cant = (int) utb::getCampo('v_cem_fall', $cond, 'count(*)');
		
		if($cant !== false){
			
			switch($cant){
				
				case 0 : return $this->redirect(['viewfall', 'id' => '', 'm' => 14]); break;
				case 1 : return $this->redirect( [ 'viewfall', 'id' => utb::getCampo('cem_fall', $cond, 'fall_id') ] ); break;
				default :
					
					return $this->redirect([
								'//objeto/listadocemfall/index'
								]);
			}
		}
		
		return $this->redirect(['viewfall', 'id' => '', 'm' => 14]);
	}
	
	public function actionServicios($id = ''){
		
		$extras = [];
		$objIdDestino = '';
		
		if($id !== null && $id != ''){
			$this->modelFallecido = CemFall::findOne($id);
			
			if($this->modelFallecido == null)
				$this->modelFallecido = new CemFall();
			else {
			
				$extras = $this->getExtrasFall($this->modelFallecido);
			}
		}
		
		if(Yii::$app->request->isPost){
			
			$objIdDestino = trim(Yii::$app->request->post('objIdDestino', ''));

			$this->modelFallecido->setScenario('servicio');
			if($this->modelFallecido->load(Yii::$app->request->post())){
				
				$res = $this->modelFallecido->grabarServicio($objIdDestino);
				
				if($res) return $this->redirect(['viewfall', 'id' => $this->modelFallecido->fall_id, 'm' => 1]);
			}
		}
		
		$modelOrigen = Cem::findOne($this->modelFallecido->obj_id);
		if($modelOrigen == null) $modelOrigen = new Cem();
		
		$modelDestino = Cem::findOne($objIdDestino);
		if($modelDestino == null) $modelDestino = new Cem();
		
		return $this->render('_form_servicios_fall',
							[
							'model' => $this->modelFallecido,
							'modelOrigen' => $modelOrigen,
							'modelDestino' => $modelDestino,
							'extras' => $extras
							]
							);
	}
	
	public function actionAlquiler($id){
		
		$model= Cem::findOne(['obj_id' => $id]);
		$modelObjeto= Objeto::findOne(['obj_id' => $id]);
		
		if($model !== null && $modelObjeto !== null){
			
			$model->cargarAlquiler();
			
			$model->setScenario('alquiler');
			if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->grabarAlquiler())
				return $this->redirect(['view', 'id' => $model->obj_id, 'm' => 1]);
		}
		
		if($model === null) $model=  new Cem();
		if($modelObjeto === null) $modelObjeto= new Objeto();
		return $this->render('alquiler', ['model' => $model, 'modelObjeto' => $modelObjeto]);
	}
		
    
    private function getMensaje($id = 0){
    	
    	switch($id){
    		
    		case 11 : return 'La cuenta de cementerio se dio de baja correctamente';
    		//case 12 : return 'Los datos de la cuenta de cementerio fueron actualizados correctamente';
    		case 13 : return 'No existe la cuenta de cementerio';
    		case 14 : return 'No se encontraron resultados para el criterio de búsqueda';
    		case 15 : return 'El fallecido se dio de baja correctamente';
    		case 16 : return 'Los datos del fallecido han sido actualizados correctamente';
    		case 17 : return 'El traslado de la cuenta corriente se ha realizado con exito';
    		default : return '';
    	}
    }
    
    public function actionImprimir($id)
    {
    	$sub1 = null;
    	$sub2 = null;
    	$sub3 = null;
    	$array = (new Cem)->Imprimir($id,$sub1,$sub2,$sub3);
    	    	
    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '30px'; 
		$pdf->content = $this->renderPartial('//reportes/cem',['datos' => $array,'sub1'=>$sub1,'sub2'=>$sub2,'sub3'=>$sub3]);        
        
        return $pdf->render();	
    }
    
    public function actionImprimirfall($id)
    {
    	$sub1 = null;
    	$array = (new CemFall)->Imprimir($id,$sub1);
    	    	
    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '30px'; 
		$pdf->content = $this->renderPartial('//reportes/cemfall',['datos' => $array,'sub1'=>$sub1]);        
        
        return $pdf->render();	
    }
	
	public function viewCbioparcela( $fall_id, $obj_id, $idModal, $idPjax ){

        $obj_id = Yii::$app->request->post( 'objeto', $obj_id );
		
		if ( strlen($obj_id) < 8 ) $obj_id = utb::getObjeto(4, $obj_id);
		if ( utb::getTObj( $obj_id ) != 4 ) $obj_id = '';
		
		$model = Cem::findOne([ 'obj_id' => $obj_id ]);
		if ( $model == null )
			$model = new Cem();
			
		$modelCuadro = CemCuadro::findOne([ 'cua_id' => $model->cua_id ]);
		if ( $modelCuadro == null )
			$modelCuadro = new CemCuadro();
			
		if 	( $modelCuadro->tipo != '' )	
			$tipos = utb::getAux('cem_tipo', 'cod', 'nombre', 0, "cod='" . $modelCuadro->tipo . "'");
		else 
			$tipos = utb::getAux('cem_tipo', 'cod', 'nombre', 2);
		
		return Yii::$app->controller->renderPartial( '_cbioparcela', [
            'model'             => $model,
			'modelCuadro'		=> $modelCuadro,
			'responsable'		=> $model->getNombreResponsable(),
			'fall_id'			=> $fall_id,
			'cuadros'			=> utb::getAux('cem_cuadro', 'cua_id', 'nombre'),
			'cuerpos'			=> utb::getAux('cem_cuerpo', 'cue_id', 'nombre', 1, "cua_id='" . $model->cua_id . "'"),
			'tipos'				=> $tipos,
            'idModal'           => $idModal,
			'idPjax'			=> $idPjax
        ]);
    }
	
	public function actionObtenerobjeto(){
	
		$model = new Cem();
		$model->setScenario( "cbioparcela" );
		$model->load(Yii::$app->request->post());
		
		$modelBuscar = Cem::findOne([
							'cua_id' => $model->cua_id,
							'cue_id' => $model->cue_id == "0" ? "" : $model->cue_id,
							'tipo' => $model->tipo,
							'fila' => $model->fila,
							'nume' => $model->nume,
							'bis' => $model->bis
						]);
		
		if ( $modelBuscar == null ){
			$devolver = [ 'objeto' => '' ];
		}else {
			$devolver = [ 'objeto' => $modelBuscar->obj_id ];
		}
		
		return json_encode($devolver); 
	}
	
	public function actionGrabarcbioparcela(){
	
		$model = new CemFall();
		$model->setScenario( "cbioparcelagrabar" );
		
		$model->obj_id = Yii::$app->request->post( 'obj_id', null );
		$model->fall_id = Yii::$app->request->post( 'fall_id', null );
		
		if( $model->validate() and $model->GrabarCbioParcela() )
			return $this->redirect([ 'viewfall', 'id' => $model->fall_id ]);
		
		$devolver = [ 'error' => $model->getErrors() ];
		
		return json_encode($devolver); 
		
	}

    /**
     * Finds the Cem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Cem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public static function findModel($id)
    {
        if (($model = Cem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
