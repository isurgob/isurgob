<?php

namespace app\controllers\objeto;

use Yii;
use app\models\objeto\Inm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii \ helpers \ ArrayHelper;
use app\utils\db\utb;
use yii\web\Session;
use app\models\objeto\Objeto;
use app\models\objeto\Domi;
use app\models\objeto\InmListado;
use app\controllers\objeto\ObjetoController;


/**
 * InmController implements the CRUD actions for Inm model.
 */
class InmController extends Controller
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

		//Obtengo el action
		$id = $action->getUniqueId();

		$session = new Session;
    	$session->open();

		if ($id == 'objeto/inm/view')
		{
	    	$session['arregloFrentes'] = [];
	    	$session['banderaFrentes'] = 0;
	    	$session['arregloMejoras'] = [];
	    	$session['banderaMejoras'] = 0;
	    	$session['arregloTitulares'] = [];
	    	$session['banderaTitulares'] = 0;

	    	$session['token']= 1;

		} else {

			$tokenAnterior= intval($session->get('token', -1));
			$token= -1;

			switch($id){

				case 'objeto/inm/create':
					$token= 0;
					break;

				case 'objeto/inm/delete':
				case 'objeto/inm/update':

					$token = $tokenAnterior;

					break;

				default:
					$token= $tokenAnterior;
			}

			$session->set('token', $token);

			if($tokenAnterior !== $token || $token === -1){

				$session['arregloFrentes'] = [];
		    	$session['banderaFrentes'] = 2;
		    	$session['arregloMejoras'] = [];
		    	$session['banderaMejoras'] = 2;
		    	$session['arregloTitulares'] = [];
		    	$session['banderaTitulares'] = 2;
			}
		}


		if ($id != 'objeto/inm/listado')
		{
			$session['cond'] = '';
			$session['descr'] = '';
		}

		$session->close();

		return true;
	}

    /**
     * Displays a single Inm model.
     * @param string $id
     * @param
     * @return mixed
     */
    public function actionView($id='', $m=0, $reiniciar = 0)
    {
    	// INICIO Crear arreglos en sesión

    	/* Cuando se invoque al módulo de inmueble, se creará un arreglo en sesión para titulares, frente y mejoras,
    	 * los cuales se cargarán con los datos presentes en la BD.
    	 * Los datos se modificarán en sesión y cuando se actualice, se grabarán en la BD.
    	 */
    	$session = new Session;
    	$session->open();

		if (!isset($session['arregloFrentes']))
		{
			$session['arregloFrentes'] = [];
			$session['banderaFrentes'] = 0; // Bandera utilizada para crear el arreglo de frentes
		}

    	if (!isset($session['arregloTitulares']))
    	{
    		$session['arregloTitulares'] = [];
    		$session['banderaTitulares'] = 0; // Bandera utilizada para crear el arreglo de titulares
    	}

    	if (!isset($session['arregloMejoras']))
    	{
    		$session['arregloMejoras'] = [];
    		$session['banderaMejoras'] = 0; // Bandera utilizada para crear el arreglo de mejoras
    	}

    	$session['domic'] = ''; 	//Seteo estas variables que me sirven para manipular los domicilios
		$session['banderaDomic'] = 0;

    	// FIN Crear arreglos en sesión


    	//Cuando se llama a la vista para mostrar los datos de un inmueble
    	if ($id !== '' and Inm::findOne($id) !== null){

    		$model = $this->findModel($id);
    		$modelObjeto = (new Objeto())->cargarObjeto($id);
    		$modelodomipar = (new Domi())->cargarDomi('INM', $id, 0);
	        $modelodomipost = (new Domi())->cargarDomi('OBJ', $id, 0);

			//Si la cantidad de los arreglos es 0, indica 	-> que el view se invoca por primera vez,
			//												-> que se eliminaron todos los elementos que estaban presentes.
			//Si la bandera para el arreglo es 0, indica	-> que el view se invoca por primera vez
			//Si la bandera para el arreglo es 1, indica	-> que se eliminaron todos los elementos que estaban presentes.
			//Si la bandera para el arreglo es 2, indica	-> que se hicieron modificaciones en un momento externo, y que se necesita actualizar la lista de inmuebles
			//por lo que hay que cargar los datos en los arreglos en sesión.
	    	if (($session['banderaFrentes'] == 0 and count($session['arregloFrentes']) == 0) || $reiniciar)
	    	{
	    		$model->obtenerFrentesDeBD($id);
	    		$session['arregloFrentes'] = $model->arrayFrente;
	    		$session['banderaFrentes'] = 1;
	    	}

	    	if ((($session['banderaTitulares'] == 0 and count($session['arregloTitulares']) == 0) || $session['banderaTitulares'] == 2) || $reiniciar)
	    	{
	    		$modelObjeto->obtenerTitularesDeBD($id);

	    		$session['arregloTitulares'] = $modelObjeto->arregloTitulares;
	    		$session['banderaTitulares'] = 1;

	    	}

	    	if (($session['banderaMejoras'] == 0 and count($session['arregloMejoras']) == 0) || $reiniciar)
	    	{
	    		$model->obtenerMejorasDeBD($id);

	    		$session['arregloMejoras'] = $model->arrayMejoras;
	    		$session['banderaMejoras'] = 1;

	    	}


    	} else {

    		$session['arregloFrentes'] = [];
    		$session['banderaFrentes'] = 0;

    		$session['arregloTitulares'] = [];
    		$session['banderaTitulares'] = 0;

    		$session['arregloMejoras'] = [];
    		$session['banderaMejoras'] = 0;

    		// Cuando se llama a la vista como index
    		// Creo los diferentes modelos que usará el módulo
	        $model = new Inm();
	        $modelObjeto = new Objeto();
	        $modelodomipar = new Domi();
	        $modelodomipost = new Domi();

	        //$m = 2;
    	}

    	$codLoc = utb::getCodLocalidad();

		//Cargo un arreglo con los valores para los en _form
		$arreglo = $model->arregloLabels();

		//Cargo un arreglo con los items para mejoras
		$arregloMejoras = $model->arregloMejoras();

		$session->close();

		// Fin Cargo un arreglo con los valores para los labels

    	$m = intval(Yii::$app->request->get('m', 0));
    	$mensaje = ObjetoController::mensajesGenerales($m);

    	if($mensaje === null) $mensaje = $this->Mensajes($m);

        return $this->render('view',[
        	'arregloLabels' => $arreglo,
        	'arregloMejoras' => $arregloMejoras,
        	'codigoLocalidad' => $codLoc,
        	'model' => $model,
        	'modelObjeto' => $modelObjeto,
        	'modelodomipar' => $modelodomipar,
			'modelodomipost' => $modelodomipost,
			'consulta' => 1,
			'mensaje' => $mensaje,
		]);
    }


    /**
     * Creates a new Inm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate( $m = 0)
    {
    	$codLoc = utb::getCodLocalidad();
    	$validaTitulares= '';

    	//Creo los diferentes modelos que usara el módulo
        $model = new Inm();
        $modelObjeto = new Objeto();
        $modelodomipar = new Domi();
        $modelodomipost = new Domi();

		// indico que es PH Madre poniendo estado = M al objeto
		if ( utb::samConfig()['inm_phmadre'] == 1 and $m == 1)
				$modelObjeto->est = 'M';

        $modelObjeto->autoinc = utb::getCampo("objeto_tipo","Cod=1","autoinc");
		$modelObjeto->letra = utb::getCampo("objeto_tipo","Cod=1","letra");

		//Cargo un arreglo con los valores para los labels
		$arreglo = $model->arregloLabels();

		//Cargo un arreglo con los items para mejoras
		$arregloMejoras = $model->arregloMejoras();

		// Crear la variable error en caso de que no esté creada
		$errores = [];

        if ($model->load(Yii::$app->request->post()) and $modelObjeto->load(Yii::$app->request->post()))
        {
        	if (!$model->hasErrors())
        	{
	        	// obtengo los array de los domicilios
	            if (isset($_POST['arrayDomiPar'])) $modelodomipar=unserialize(urldecode(stripslashes($_POST['arrayDomiPar'])));
	            if (isset($_POST['arrayDomiPost'])) $modelodomipost=unserialize(urldecode(stripslashes($_POST['arrayDomiPost'])));

	        	if($modelodomipar == null) $modelodomipar= new Domi();
	            if($modelodomipost == null) $modelodomipost= new Domi();

	        	//Seteo valores para que grabe en la BD
				$modelObjeto->obj_dato = utb::InmArmarNCGuiones($model->s1,$model->s2,$model->s3,$model->manz,$model->parc);
	        	$modelObjeto->tobj = 1;

	        	//Voy a buscar el código del titular principal
	        	$session = new Session;
	        	$session->open();

	        	$array = $session['arregloTitulares'];
	        	$arrayFrentes = $session['arregloFrentes']; //Obtengo el arreglo de frentes para validar contra el dom. Parcelario
	        	$keys = array_keys($array);
	        	$session->close();

	        	foreach($keys as $clave)
	        	{
	        		if ($array[$clave]['princ'] != '') $modelObjeto->num =$array[$clave]['num'];
	        	}

				//Seteo los domicilios
				$modelObjeto->domi_postal = $modelodomipost->domicilio;
				$model->domi_postal = $modelodomipost->domicilio;
				$model->domi_parcelario = $modelodomipar->domicilio;

				// valido inmueble y objeto
				//Le asigno el arreglo proveniente de los errores de objeto al ErrorSummary de inmueble
	            //$errores = array_merge($modelObjeto->validarConErrorModels(), $model->validarInmueble());
	            $errores = array_merge($modelObjeto->validarConErrorModels(), $model->validarInmueble( $m ));

	       		//Creo este if para que no se valide todo en el mismo momento, ya que aparecería la descripción
	       		//de muchos errores juntos
	        	if (count($errores) == 0)
	        	{
					//Inicio Validar que se hayan ingresado los domicilios
					if ($model->domi_parcelario == '' and $modelObjeto->est != 'M' )
						$errores[] = 'No ingresó un domicilio parcelario.';

					if ($model->domi_postal == '' and $modelObjeto->est != 'M' )
						$errores[] = 'No ingresó un domicilio postal.';
					// Fin Validar que se hayan ingresado los domicilios

					//Validar que algún frente ingresado se corresponda con el dom. Parcelario
					$bandera  = 0;
					foreach ($arrayFrentes as $frente)
					{
						if ($frente['calle_id'] == $modelodomipar->calle_id)
						{
							$bandera = 1;

						}
					}

					if ($bandera == 0 && (utb::samConfig()['inm_valida_nc'] == 1 && count($arrayFrentes) > 0))
						$errores[] = 'Ningún frente se corresponde con el Dom. Parcelario.';

					//Validar los titulares
					if($modelodomipar->nomcalle)
						$validaTitulares = $modelObjeto->validarTitulares();

					if($validaTitulares != '') $errores[] = $validaTitulares;

	        	}

				$model->addErrors($errores);

	            if (count($errores) == 0)
	    		{

	            	$transaction = Yii::$app->db->beginTransaction();

	            	try {
	    				// inserto objeto
	    				$error = $modelObjeto->grabar();

	    				if ($error != '') $errores[] = $error;

	    				// 	inserto inmuebles
	    				$model->obj_id = $modelObjeto->obj_id;
	            		$error = $model->grabar( $m );

	            	    if ($error != '') $errores[] = $error;

	            		// INICIO Insertar los domicilios
	            		if ($modelodomipar->domicilio !== null) $modelodomipar->obj_id = $modelObjeto->obj_id;
	            		if ($modelodomipost->domicilio !== null) $modelodomipost->obj_id = $modelObjeto->obj_id;

	            		$modelodomipar->torigen = 'INM';
	            		$modelodomipost->torigen = 'OBJ';

	            		if ($modelodomipost->domicilio !== null) $error = $modelodomipost->grabar();
	            		if ($error != '') $errores[] = $error;
	            		if ($modelodomipar->domicilio !== null) $error = $modelodomipar->grabar();
	            		if ($error != '') $errores[] = $error;
						// FIN Insertar los domicilios

						//INICIO Insertar los arreglos en memoria
						$error = $model->grabarMejoras(); //Guardo las mejoras
						if ($error != '') $errores[] = $error;
						$error= $modelObjeto->grabarTitulares(); //Guardo los titulares
						if ($error != '') $errores[] = $error;
						$error = $model->grabarFrentes(); //Guardo los frentes
						if ($error != '') $errores[] = $error;

						//FIN Insertar los arreglos en memoria

	    				if (count($errores) == 0)
	    				{
	    					$transaction->commit();

	    					//Ejecuto el método que calcula los valores computados
	    					$modelObjeto->GrabarComputados();

	    					return $this->redirect(['view', 'id' => $modelObjeto->obj_id, 'm' => '1']);

	    				} else {

	    					$transaction->rollBack();

	    					$model->addErrors($errores);

					        return $this->render('view',[
					        	'arregloLabels' => $arreglo,
					        	'arregloMejoras' => $arregloMejoras,
					        	'codigoLocalidad' => $codLoc,
					        	'model' => $model,
					        	'modelObjeto' => $modelObjeto,
					        	'modelodomipar' => $modelodomipar,
								'modelodomipost' => $modelodomipost,
					        	'consulta' => 0,
					        	]);
	    				}

					} catch(Exception $e) {
	    				$transaction->rollBack();
				    	//throw $e;
				    	$errores[] = $e;
				    	$model->addErrors($errores);

				    	return $this->redirect(['view', 'id' => $modelObjeto->obj_id]);
					}

	    		} else {

	    			$model->addErrors($errores);

			        return $this->render('view',[
			        	'arregloLabels' => $arreglo,
			        	'arregloMejoras' => $arregloMejoras,
			        	'codigoLocalidad' => $codLoc,
			        	'model' => $model,
			        	'modelObjeto' => $modelObjeto,
			        	'modelodomipar' => $modelodomipar,
						'modelodomipost' => $modelodomipost,
			        	'consulta' => 0,
			        	]);
	    		}

	    	}

    	} else {

		        return $this->render('view',[
		        	'arregloLabels' => $arreglo,
		        	'arregloMejoras' => $arregloMejoras,
		        	'codigoLocalidad' => $codLoc,
		        	'model' => $model,
		        	'modelObjeto' => $modelObjeto,
		        	'modelodomipar' => $modelodomipar,
					'modelodomipost' => $modelodomipost,
		        	'consulta' => 0,
		        	]);

		   	}

	}


    /**
     * Updates an existing Inm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id, $m)
    {

        $codLoc = utb::getCodLocalidad();

    	//Creo los diferentes modelos que usara el módulo
        $model = new Inm();
        $modelObjeto = new Objeto();
        $modelodomipar = new Domi();
        $modelodomipost = new Domi();

    	//Cargo un arreglo con los valores para los labels
		$arreglo = $model->arregloLabels();

		//Cargo un arreglo con los items para mejoras
		$arregloMejoras = $model->arregloMejoras();

    	//Cuando se llama a la vista para mostrar los datos de un inmueble
    	if ($id !== '' and Inm::findOne($id) !== null){
    		$model = $this->findModel($id);
    		$modelObjeto = (new Objeto())->cargarObjeto($id);
    		$modelodomipar = Domi::cargarDomi('INM', $id, 0);
	        $modelodomipost = Domi::cargarDomi('OBJ', $id, 0);
    	}

		if($modelodomipar == null) $modelodomipar= new Domi();
    	if($modelodomipost == null) $modelodomipost= new Domi();

		// Crear la variable error en caso de que no esté creada
		
		$errores = [];

        if ($model->load(Yii::$app->request->post()) and $modelObjeto->load(Yii::$app->request->post()))
        {
        	if (!$model->hasErrors())
        	{
	        	// obtengo los array de los domicilios
	            if (isset($_POST['arrayDomiPar'])) $modelodomipar=unserialize(urldecode(stripslashes($_POST['arrayDomiPar'])));
	            if (isset($_POST['arrayDomiPost'])) $modelodomipost=unserialize(urldecode(stripslashes($_POST['arrayDomiPost'])));

	            if($modelodomipar == null) $modelodomipar= new Domi();
	            if($modelodomipost == null) $modelodomipost= new Domi();

	        	//Seteo valores para que grabe en la BD
	        	$modelObjeto->obj_dato = utb::InmArmarNCGuiones($model->s1,$model->s2,$model->s3,$model->manz,$model->parc);
				$modelObjeto->tobj = 1;

	        	//Voy a buscar el código del titular principal
	        	$session = new Session;
	        	$session->open();

	        	$array = $session['arregloTitulares'];
	        	$arrayFrentes = $session['arregloFrentes'];
	        	$keys = array_keys($array);
	        	$session->close();

	        	foreach($keys as $clave)
	        	{
	        		if ($array[$clave]['princ'] != '') $modelObjeto->num =$array[$clave]['num'];
	        	}

				//Seteo los domicilios
				$modelObjeto->domi_postal = $modelodomipost->domicilio;
				$model->domi_postal = $modelodomipost->domicilio;
				$model->domi_parcelario = $modelodomipar->domicilio;

				// valido inmueble y objeto
				//Le asigno el arreglo proveniente de los errores de objeto al ErrorSummary de inmueble
	            $errores = array_merge($modelObjeto->validarConErrorModels(), $model->validarInmueble( $m ));

	       		//Creo este if para que no se valide todo en el mismo momento, ya que apareceria la descripción
	       		//de muchos errores todos juntos
	        	if (count($errores) == 0)
	        	{
		        	// Inicio Validar que se hayan ingresado los domicilios

					//Inicio Validar que se hayan ingresado los domicilios
					if ($model->domi_parcelario == '' and $modelObjeto->est != 'M')
						$errores[] = 'No ingresó un domicilio parcelario.';

					if ($model->domi_postal == '' and $modelObjeto->est != 'M')
						$errores[] = 'No ingresó un domicilio postal.';

					// Fin Validar que se hayan ingresado los domicilios

					//Validar que algún frente ingresado se corresponda con el dom. Parcelario
					$bandera  = 0;
					foreach ($arrayFrentes as $frente)
					{
						if ($frente['calle_id'] == $modelodomipar->calle_id)
						{
							$bandera = 1;
						}
					}

					if ($bandera == 0 && (utb::samConfig()['inm_valida_nc'] == 1 && count($arrayFrentes) > 0))
						$errores[] = 'Ningún frente se corresponde con el Dom. Parcelario';

					//Validar los titulares
					if ( $modelObjeto->est != 'M' ){
						$validaTitulares = $modelObjeto->validarTitulares();

						if($validaTitulares != '') $errores[] = $validaTitulares;
					}

	        	}

	        	$model->addErrors( $errores );

	            if (count($errores) == 0)
	    		{
	            	$transaction = Yii::$app->db->beginTransaction();

	            	try {

	    				// inserto objeto
	    				$error = $modelObjeto->grabar();

	    				if ($error != '') $error = $error;

	    				// 	inserto inmuebles
	    				$model->obj_id = $modelObjeto->obj_id;
	            		$error = $model->grabar( $m );

	            	    if ($error != '') $errores[] = $error;

	            		// INICIO Insertar los domicilios
	            		if ($modelodomipar->domicilio !== null) $modelodomipar->obj_id = $modelObjeto->obj_id;
	            		if ($modelodomipost->domicilio !== null) $modelodomipost->obj_id = $modelObjeto->obj_id;

	            		$modelodomipar->torigen = 'INM';
	            		$modelodomipost->torigen = 'OBJ';

	            		if ($modelodomipost->domicilio !== null) $error = $modelodomipost->grabar();
	            		if ($error != '') $errores[] = $error;
	            		if ($modelodomipar->domicilio !== null) $error = $modelodomipar->grabar();
	            		if ($error != '') $errores[] = $error;
						// FIN Insertar los domicilios

						//INICIO Insertar los arreglos en memoria
						$error = $model->grabarMejoras(); //Guardo las mejoras
						if ($error != '') $errores[] = $error;
						$error= $modelObjeto->grabarTitulares(); //Guardo los titulares
						if ($error != '') $errores[] = $error;
						$error = $model->grabarFrentes(); //Guardo los frentes
						if ($error != '') $errores[] = $error;

						//FIN Insertar los arreglos en memoria
	    				if (count($errores) == 0)
	    				{
	    					$transaction->commit();

	    					//Ejecuto el método que calcula los valores computados
	    					$modelObjeto->GrabarComputados();

	    					return $this->redirect(['view', 'id' => $modelObjeto->obj_id, 'm' => '1']);

	    				} else {

	    					$transaction->rollBack();

	    					$model->addErrors($errores);

					        return $this->render('view',[
					        	'arregloLabels' => $arreglo,
					        	'arregloMejoras' => $arregloMejoras,
					        	'codigoLocalidad' => $codLoc,
					        	'model' => $model,
					        	'modelObjeto' => $modelObjeto,
					        	'modelodomipar' => $modelodomipar,
								'modelodomipost' => $modelodomipost,
					        	'consulta' => 3,
					        	]);
	    				}

					} catch(Exception $e) {
	    				$transaction->rollBack();
				    	//throw $e;
				    	$errores[] = $e;
				    	$model->addErrors($errores);

				    	return $this->redirect(['view', 'id' => $modelObjeto->obj_id]);
					}

	    		} else {
	    			$model->addErrors($errores);

			        return $this->render('view',[
			        	'arregloLabels' => $arreglo,
			        	'arregloMejoras' => $arregloMejoras,
			        	'codigoLocalidad' => $codLoc,
			        	'model' => $model,
			        	'modelObjeto' => $modelObjeto,
			        	'modelodomipar' => $modelodomipar,
						'modelodomipost' => $modelodomipost,
			        	'consulta' => 3,
			        	]);
	    		}

	        }

		} else {

			//En el caso que el objeto esté eliminado
			if ($modelObjeto->est == 'B')
	       	{
         	   	return $this->render('view',[
		        	'arregloLabels' => $arreglo,
		        	'arregloMejoras' => $arregloMejoras,
		        	'codigoLocalidad' => $codLoc,
		        	'model' => $model,
		        	'modelObjeto' => $modelObjeto,
		        	'modelodomipar' => $modelodomipar,
					'modelodomipost' => $modelodomipost,
		        	'consulta' => 1,
		        	'id' => $id,
		        	'mensaje' => $this->Mensajes(14)]);

            } else {

            	//En el caso que el objeto no esté eliminado
            	return $this->render('view',[
		        	'arregloLabels' => $arreglo,
		        	'arregloMejoras' => $arregloMejoras,
		        	'codigoLocalidad' => $codLoc,
		        	'model' => $model,
		        	'modelObjeto' => $modelObjeto,
		        	'modelodomipar' => $modelodomipar,
					'modelodomipost' => $modelodomipost,
		        	'consulta' => 3,
		        	'id' => $id,
		        	]);
            }
	   	}

    }

    /**
     * Función que se ejecuta cuando se busca un Inmueble
     */
    public function actionBuscar()  {
    	$cond='';
		$objeto ='';
		$cant = 1;

		if (isset($_POST['buscar-txObjeto']) and $_POST['buscar-txObjeto']!=='') $objeto=$_POST['buscar-txObjeto'];
		if (isset($_POST['buscar-txPartProv']) and $_POST['buscar-txPartProv']!=='') $cond="parp=".$_POST['buscar-txPartProv'];
		if (isset($_POST['buscar-txPartOrigen']) and $_POST['buscar-txPartOrigen']!=='') $cond="parporigen=".$_POST['buscar-txPartOrigen'];
		if (isset($_POST['buscar-txNumPlano']) and $_POST['buscar-txNumPlano']!=='') $cond="plano=".$_POST['buscar-txNumPlano'];

		if ($objeto != '') {
			if (strlen($objeto) < 8)
				$objeto = utb::GetObjeto(1,(int)$objeto);

			$m = 0;
			if (utb::getNombObj("'".$objeto."'") == '') $m = 11;

			return $this->redirect(['view', 'id' => $objeto, 'm' => $m, 'reiniciar' => 1]);
		} else if ($cond != '') {
			// obtengo la cantidad de resultados de la búsqueda
			// si es un solo objeto redirecciono a la vista de ese objeto
			// sino abro el listado de resultados
			$cant = utb::getCampo("v_inm",$cond,"count(*)");

			if ($cant <= 1){

				$objeto = utb::getCampo("v_inm",$cond,"obj_id");

				if (strlen($objeto) < 8)
				$objeto = utb::GetObjeto(1,(int)$objeto);

				$m = 0;
				if (utb::getNombObj("'".$objeto."'") == '') $m = 11;

				return $this->redirect(['view', 'id' => $objeto, 'm' => $m]);

			} else {  // Si son mas de uno los resultados,tengo que cargar el listado
                $model = new InmListado();
                $model->part_prov_desde = Yii::$app->request->post('buscar-txPartProv', null);
                $model->part_prov_hasta = Yii::$app->request->post('buscar-txPartProv', null);
                $model->part_origen_desde = Yii::$app->request->post('buscar-txPartOrigen', null);
                $model->part_origen_hasta = Yii::$app->request->post('buscar-txPartOrigen', null);
                $model->plano_desde = Yii::$app->request->post('buscar-txNumPlano', null);
                $model->plano_hasta = Yii::$app->request->post('buscar-txNumPlano', null);

                $res = $model->buscar();
                $datos = ListadoinmController::datosResultado($model, $res);
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

		} else {

			return $this->redirect(['view', 'm' => '11']);
		}
    }

	public function actionIrphmadre ( $id ){

		$model = Inm::findOne( $id );

		$ph_madre = $model->obtenerPHMadre();

		if ( $ph_madre == '' )
			return $this->redirect([ 'view', 'm' => '15', 'id' => $id ]);
		else
			return $this->redirect([ 'view', 'id' => $ph_madre ]);



	}


    /**
     * Deletes an existing Inm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
	public function actionDelete($id, $accion, $tbaja=0, $obs="", $elimobjcondeuda=0)
    {
		//Cargo los modelos con el inmueble correspondiente
        $model = $this->findModel($id);
        $modelObjeto = (new Objeto())->cargarObjeto($id);
        $modelodomipost = Domi::cargarDomi('OBJ', $id, 0);
        $modelodomipar = Domi::cargarDomi('INM', $id, 0);


    	//Cargo un arreglo con los valores para los labels
		$arreglo = $model->arregloLabels();

		//Cargo un arreglo con los items para mejoras
		$arregloMejoras = $model->arregloMejoras();

       	$codLoc = utb::getCodLocalidad();

       	/**
       	 *
       	 * Acción = 1 => Se debe eliminar el inmueble.
       	 *
       	 * Acción = 0 => Se debe mostrar la información del objeto que se eliminará.
       	 *
       	 */
        if ($accion==1)
        {
        	$modelObjeto->tbaja = $tbaja;
        	$modelObjeto->elimobjcondeuda = $elimobjcondeuda;

        	$error = ($modelObjeto->borrarConErrorSummary($obs));

        	if ($error != '')
        	{
        		$model->addError($model->obj_id, $error);
        	}

        	//Si no se encontraron errores durante la eliminación del Objeto correspondiente
        	if (!$model->hasErrors())
        	{
        		return $this->redirect([
						'view',
						'id' => $modelObjeto->obj_id,
						'm' => '13',
						]);

        	} else {

        		return $this->render('view', [
	        		'arregloLabels' => $arreglo,
	        		'arregloMejoras' => $arregloMejoras,
		        	'codigoLocalidad' => $codLoc,
		        	'model' => $model,
		        	'modelObjeto' => $modelObjeto,
		        	'modelodomipar' => $modelodomipar,
					'modelodomipost' => $modelodomipost,
		        	'consulta' => 2,
		        	]);


        	}
        }
        else {
            if ($modelObjeto->est !== 'B')
            {
            	return $this->render('view', [
	        		'arregloLabels' => $arreglo,
	        		'arregloMejoras' => $arregloMejoras,
		        	'codigoLocalidad' => $codLoc,
		        	'model' => $model,
		        	'modelObjeto' => $modelObjeto,
		        	'modelodomipar' => $modelodomipar,
					'modelodomipost' => $modelodomipost,
		        	'consulta' => 2,
		        	]);
            }else {
            	return $this->redirect(['view', 'id' => $modelObjeto->obj_id, 'm' => '12']);
            }
        }
    }

    /**
     * Muestra la vista para calcular los coeficientes. Los cálculos se realizan en la vista.
     */
    public function actionCoef(){

    	return $this->render('coeficiente');
    }

    public function actionRevaluo(){

    	return $this->render('revaluar');
    }

	public function actionCertificadovaluacion($id)
	{
		$model = $this->findModel($id);
        $modelobjeto = (new Objeto())->cargarObjeto($id);

		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/inm_cert_val',['model' => $model,'modelobjeto'=>$modelobjeto]);

        return $pdf->render();
	}

    /**
     * Finds the Inm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Inm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Inm::findOne($id)) !== null) {
        	$modelDomi = new Domi;
        	$model->domi_postal = $modelDomi->getDomicilio('INM',$model->obj_id,0);
            $model->domi_parcelario = $modelDomi->getDomicilio('OBJ',$model->obj_id,0);

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function Mensajes($id)
	{

		$mensaje = '';

		switch ($id)
		{
			case 0:
				$mensaje = '';
				break;

			case 1:
				$mensaje = 'Datos Grabados.';
				break;

			case 11:
				$mensaje = 'El Inmueble no existe.';
				break;

			case 12:
				$mensaje = 'El Inmueble fue dado de baja con anterioridad.';
				break;

			case 13:
				$mensaje = 'El Inmueble se dió de baja correctamente.';
				break;

			case 14:
				$mensaje = 'El Inmueble se encuentra dado de baja.';
				break;

			case 15:
				$mensaje = 'El Inmueble no posee PH Madre.';
				break;
		}

		return $mensaje;

	}


	public function actionRestricciones($id)
	{
		/*
		 * Obtengo los datos que llegan por sesión en caso de intentar hacer un abm de restricción
		 */

		$session = Yii::$app->session;
		$session->open();

    	$error = $session->getFlash( 'restricciones_error', '', true );
    	$consulta = $session->getFlash( 'restricciones_consulta', 1, true );
    	$mensaje = $session->getFlash( 'restricciones_mensaje', 0, true );

		$session->close();

		$dataProvider = (new Inm())->obtenerRestricciones($id);

		return $this->render('restricciones',[
			'id' => $id,
			'dataProvider' => $dataProvider,
			'mensaje' => $this->Mensajes( $mensaje ),
			'error' => $error,
			'consulta' => $consulta,
		]);
	}


	public function actionRestriccionesabm($id)
    {
    	$dataprovider = Inm::obtenerRestricciones($id);

    	if (isset($_POST['txAccion']))
    	{
    		$error = '';	//Variable que almacenará los errores
    		$consulta = 1;
    		$mensaje = 0;
	    	$accion = $_POST['txAccion'];
	    	$orden = isset($_POST['txOrden']) ? $_POST['txOrden'] : 0;

    		if ($accion != 2)
    		{

	    		$tipo = $_POST['restriccion-tipoRestriccion'];
	    		$sup = $_POST['restriccion-sup'];
	    		$detalle = $_POST['restriccion-detalle'];
	    		$inscrip = $_POST['restriccion-inscrip'];

    		}

    		if ( $accion==0 ) $error = Inm::NuevaRestriccion($id, $tipo, $sup, $inscrip, $detalle);
    		if ( $accion==3 ) $error = Inm::ModificarRestriccion($id, $orden, $tipo, $sup, $inscrip, $detalle);
    		if ( $accion==2 ) $error = Inm::EliminarRestriccion($id, $orden);

    		if ($error !== "")
    		{
    			$consulta = 0;
    		} else
    		{
    			$mensaje = 1;
    		}

    		$session = Yii::$app->session;
    		$session->open();

    		$session->setFlash( 'restricciones_error', $error );
    		$session->setFlash( 'restricciones_consulta', $consulta );
    		$session->setFlash( 'restricciones_mensaje', $mensaje );

			$session->close();
    	}

    	return $this->redirect(['restricciones', 'id' => $id]);
    }

    public function actionImprimir($id)
    {
    	$sub1 = null;
    	$sub2 = null;
    	$sub3 = null;
    	$sub4 = null;
    	$array = (new Inm)->Imprimir($id,$sub1,$sub2,$sub3,$sub4);

    	$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/inm',['datos' => $array,'sub1'=>$sub1,'sub2'=>$sub2,'sub3'=>$sub3,'sub4'=>$sub4]);

        return $pdf->render();
    }

}
