<?php

namespace app\controllers\objeto;

use Yii;
use app\models\objeto\Persona;
use app\models\objeto\Domi;
use app\models\objeto\Objeto;
use app\models\objeto\ComerRubro;
use app\models\objeto\ConsultaWeb;
use app\models\objeto\Comer;
use app\models\objeto\PersonaListado;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;
use yii\web\Session;
use yii\data\ArrayDataProvider;
use yii\web\UploadedFile;
use app\utils\db\Fecha;

/**
 * PersonaController implements the CRUD actions for Persona model.
 */
class PersonaController extends Controller
{

    const CONST_MENSAJE                 = 'persona_mensaje';
    const CONST_MENSAJE_ERROR           = 'persona_mensaje_error';
    const CONST_LAST_ID                 = 'persona_last_id';
    const CONST_PERSONA_ARRAY_RUBROS    = 'persona_array_rubros';

    private $cargarArregloRubrosDesdeBD = false;

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
	    $operacion = str_replace( "/", "-", Yii::$app->controller->route );

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

        $id = $action->getUniqueId();

        if( $id != Yii::$app->session->getFlash( self::CONST_LAST_ID, '' ) ){

            Yii::$app->session->remove( self::CONST_PERSONA_ARRAY_RUBROS );
            $this->cargarArregloRubrosDesdeBD = true;
        }

        Yii::$app->session->setFlash( self::CONST_LAST_ID, $id );

        return true;

	}

    /**
     * Displays a single Persona model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id='', $m=0, $m_text='',$ajuste=0)
    {
        $model = new Persona();
        $modelobjeto = new Objeto();
        $modelodomipost = new Domi();
        $modelodomileg = new Domi();
        $modelodomires = new Domi();

		if ($id !== '' and Persona::findOne($id) !== null)
        {
        	$model = $this->findModel($id);
        	$modelobjeto = $modelobjeto->cargarObjeto($id);
        	$modelodomipost = Domi::cargarDomi('OBJ', $model->obj_id, 0);
        	$modelodomileg = Domi::cargarDomi('PLE', $model->obj_id, 0);
        	$modelodomires = Domi::cargarDomi('PRE', $model->obj_id, 0);

			/**
			 * Según el parámetro ib_modo en la configuración del sistema:
			 *      'A' -> Automático: el número de ingresos brutos lo establece el sistema.
			 *      'C' -> CUIT: el número de ingresos brutos es igual al número de CUIT ( Debe existir el CUIT ).
			 *      'M' -> Manual: el número de ingresos brutos lo ingresa el usuario ( validar que no se repita ).
			 */
			$config = utb::getCampo( 'sam.config', '', 'ib_modo' );

			switch( $config ){

				case 'C':

					$model->ib = $model->cuit;
			}
        }

       if ($modelodomipost == null) $modelodomipost = new Domi();
       if ($modelodomileg == null) $modelodomileg = new Domi();
       if ($modelodomires == null) $modelodomires = new Domi();

       $m = intval(Yii::$app->request->get('m', 0));
       $mensaje = ObjetoController::mensajesGenerales($m);

       if($mensaje === null) $mensaje = $this->Mensajes( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, $m) );

	    return $this->render('view', [
           	'model' => $model, 'modelobjeto' => $modelobjeto,
			'modelodomipost' => $modelodomipost, 'modelodomileg' => $modelodomileg, 'modelodomires' => $modelodomires,
			'mensaje' => $mensaje,
			'dadosDeBaja' => intVal( Yii::$app->request->get( 'filtroBaja', 0 ) )
        ]);
    }

    /**
     * Creates a new Persona model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Persona();
        $modelobjeto = new Objeto();

		$modelobjeto->autoinc = utb::getCampo("objeto_tipo","Cod=3","autoinc");
		$modelobjeto->letra = utb::getCampo("objeto_tipo","Cod=3","letra");

        $modelodomipost = new Domi();
        $modelodomileg = new Domi();
        $modelodomires = new Domi();

        if (isset($error) == null) $error = '';

        $model->setScenario( 'persona' );
        if ($model->load(Yii::$app->request->post()) and $modelobjeto->load(Yii::$app->request->post()))
        {
            $model->cuit = str_replace('-','',$model->cuit);

            // obtengo los array de los domicilios
            if (isset($_POST['arrayDomiPost'])) $modelodomipost=unserialize(urldecode(stripslashes($_POST['arrayDomiPost'])));
            if (isset($_POST['arrayDomiLeg'])) $modelodomileg=unserialize(urldecode(stripslashes($_POST['arrayDomiLeg'])));
            if (isset($_POST['arrayDomiRes'])) $modelodomires=unserialize(urldecode(stripslashes($_POST['arrayDomiRes'])));

            if ($modelodomileg->torigen != 'PLE') $modelodomileg->torigen = 'PLE';
            if ($modelodomires->torigen != 'PRE') $modelodomires->torigen = 'PRE';

            // cargo los datos de las direcciones correspondientes
    		$modelobjeto->domi_postal = $modelodomipost->domicilio;
    		$model->domi_legal = $modelodomileg->domicilio;
    		$model->domi_res = $modelodomires->domicilio;

            // cargo los datos de objeto
            $modelobjeto->obj_dato=$model->ndoc;
            $modelobjeto->tobj=3;

            // valido personas y objeto
            $error .= $modelobjeto->validar();
            $error .= $model->validar();

            if ($error == "")
    		{
            	$transaction = Yii::$app->db->beginTransaction();

            	try {
    				// inserto objeto
    				$error .= $modelobjeto->grabar();

    				// 	inserto persona
    				$model->obj_id = $modelobjeto->obj_id;
            		$error .= $model->grabar();

            		// inserto los domicilios
            		if ($modelodomipost->domicilio !== null) $modelodomipost->obj_id = $modelobjeto->obj_id;
            		if ($modelodomileg->domicilio !== null) $modelodomileg->obj_id = $modelobjeto->obj_id;
            		if ($modelodomires->domicilio !== null) $modelodomires->obj_id = $modelobjeto->obj_id;

            		if ($modelodomipost->domicilio !== null) $error .= $modelodomipost->grabar();
            		if ($modelodomileg->domicilio !== null) $error .= $modelodomileg->grabar();
            		if ($modelodomires->domicilio !== null) $error .= $modelodomires->grabar();

    				if ($error == "")
    				{
    					$transaction->commit();
    				}

				} catch(\Exception $e) {
    				$transaction->rollBack();
			    	//throw $e;
			    	$error = $e;
				}
    		}

            if ($error == "")
            {
            	return $this->redirect(['view', 'id' => $modelobjeto->obj_id, 'm' => 1]);
            } else {
            	return $this->render('view', [
           			'model' => $model, 'modelobjeto' => $modelobjeto,
					'modelodomipost' => $modelodomipost, 'modelodomileg' => $modelodomileg, 'modelodomires' => $modelodomires,
					'consulta' => 0,'error' => $error,'dadosDeBaja' => intVal( Yii::$app->request->get( 'filtroBaja', 0 ) )
           			]);
            }
        } else {
            return $this->render('view', [
           		'model' => $model, 'modelobjeto' => $modelobjeto,
				'modelodomipost' => $modelodomipost, 'modelodomileg' => $modelodomileg, 'modelodomires' => $modelodomires,
				'consulta' => 0,'dadosDeBaja' => intVal( Yii::$app->request->get( 'filtroBaja', 0 ) )
        		]);
        }
    }

    /**
     * Updates an existing Persona model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id,$ajuste=0)
    {
        $model = $this->findModel($id);
        $modelobjeto = (new Objeto)->cargarObjeto($id);
        $modelodomipost = Domi::cargarDomi('OBJ', $id, 0);
        $modelodomileg = Domi::cargarDomi('PLE', $id, 0);
       	$modelodomires = Domi::cargarDomi('PRE', $id, 0);

       	if ($modelodomileg == null) $modelodomileg = new Domi();
       	if ($modelodomires == null) $modelodomires = new Domi();

       	if (isset($error) == null) $error = '';

        $model->setScenario( 'persona' );
        if ($model->load(Yii::$app->request->post()) and $modelobjeto->load(Yii::$app->request->post()))
        {
        	$model->cuit = str_replace('-','',$model->cuit);

            // obtengo los array de los domicilios
            if (isset($_POST['arrayDomiPost'])) $modelodomipost=unserialize(urldecode(stripslashes($_POST['arrayDomiPost'])));
            if (isset($_POST['arrayDomiLeg'])) $modelodomileg=unserialize(urldecode(stripslashes($_POST['arrayDomiLeg'])));
            if (isset($_POST['arrayDomiRes'])) $modelodomires=unserialize(urldecode(stripslashes($_POST['arrayDomiRes'])));

            if ($modelodomileg->torigen != 'PLE') $modelodomileg->torigen = 'PLE';
            if ($modelodomires->torigen != 'PRE') $modelodomires->torigen = 'PRE';

            // cargo los datos de las direcciones correspondientes
    		$modelobjeto->domi_postal = $modelodomipost->domicilio;
    		$model->domi_legal = $modelodomileg->domicilio;
    		$model->domi_res = $modelodomires->domicilio;

            // cargo los datos de objeto
            $modelobjeto->obj_dato=$model->ndoc;

            // valido personas y objeto
            $error .= $modelobjeto->validar();
            $error .= $model->validar();

            if ($error == "")
    		{
            	$transaction = Yii::$app->db->beginTransaction();

            	try {
    				// inserto objeto
    				$error .= $modelobjeto->grabar();

    				// 	inserto persona
    				$model->obj_id = $modelobjeto->obj_id;
            		$error .= $model->grabar();

            		// inserto los domicilios
            		if ($modelodomipost->domicilio !== null) $modelodomipost->obj_id = $modelobjeto->obj_id;
            		if ($modelodomileg->domicilio !== null) $modelodomileg->obj_id = $modelobjeto->obj_id;
            		if ($modelodomires->domicilio !== null) $modelodomires->obj_id = $modelobjeto->obj_id;

            		if ($modelodomipost->domicilio !== null) $error .= $modelodomipost->grabar();
            		if ($modelodomileg->domicilio !== null) $error .= $modelodomileg->grabar();
            		if ($modelodomires->domicilio !== null) $error .= $modelodomires->grabar();

					if ($ajuste != 0) $model->GrabarAjustes($ajuste);

    				if ($error == "")
    				{
    					$transaction->commit();
    				}else {
    					$transaction->rollBack();
    				}

				} catch(\Exception $e) {
    				$transaction->rollBack();
			    	//throw $e;
			    	$error = $e;
				}
    		}
    		if ($error == "")
            {
            	return $this->redirect(['view', 'id' => $modelobjeto->obj_id, 'm' => 1]);
            } else {
            	return $this->render('view', [
           			'model' => $model, 'modelobjeto' => $modelobjeto,
					'modelodomipost' => $modelodomipost, 'modelodomileg' => $modelodomileg, 'modelodomires' => $modelodomires,
					'consulta' => 3,'error' => $error,'dadosDeBaja' => intVal( Yii::$app->request->get( 'filtroBaja', 0 ) )
           			]);
            }
        }else {
            if ($modelobjeto->est !== 'B')
            {
            	return $this->render('view', [
           			'model' => $model, 'modelobjeto' => $modelobjeto,
					'modelodomipost' => $modelodomipost, 'modelodomileg' => $modelodomileg, 'modelodomires' => $modelodomires,
					'consulta' => 3,'dadosDeBaja' => intVal( Yii::$app->request->get( 'filtroBaja', 0 ) )
        			]);
            } else {
            	return $this->redirect(['view', 'id' => $modelobjeto->obj_id, 'm' => 11]);
            }
        }
    }

    /**
     * Deletes an existing Persona model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id, $accion,$tbaja=0, $obs="", $elimobjcondeuda=0)
    {
        $error = "";
        $model = $this->findModel($id);
        $modelobjeto = (new Objeto)->cargarObjeto($id);
        $modelodomipost = Domi::cargarDomi('OBJ', $id, 0);
        $modelodomileg = Domi::cargarDomi('PLE', $id, 0);
       	$modelodomires = Domi::cargarDomi('PRE', $id, 0);

       	if (isset($error)==null) $error = '';

        if ($accion==1)
        {
        	$modelobjeto->tbaja = $tbaja;
        	$modelobjeto->elimobjcondeuda = $elimobjcondeuda;

        	$error .= $modelobjeto->borrar($obs);

        	if ($error == "")
        	{
        		return $this->redirect(['view', 'id' => $modelobjeto->obj_id, 'm' => 14]);
        	}else{
        		return $this->render('view', [
           			'model' => $model, 'modelobjeto' => $modelobjeto,
					'modelodomipost' => $modelodomipost, 'modelodomileg' => $modelodomileg, 'modelodomires' => $modelodomires,
					'consulta' => 2, 'error' => $error,'dadosDeBaja' => intVal( Yii::$app->request->get( 'filtroBaja', 0 ) )
        			]);
        	}
        }
        else {
            if ($modelobjeto->est !== 'B')
            {
            	return $this->render('view', [
           			'model' => $model, 'modelobjeto' => $modelobjeto,
					'modelodomipost' => $modelodomipost, 'modelodomileg' => $modelodomileg, 'modelodomires' => $modelodomires,
					'consulta' => 2,'dadosDeBaja' => intVal( Yii::$app->request->get( 'filtroBaja', 0 ) )
        			]);
            }else {
            	return $this->redirect(['view', 'id' => $modelobjeto->obj_id, 'm' => 11]);
            }
        }
    }


    public function actionReemplaza($list = 1,$m=0)
    {
    	$error = Yii::$app->session['error'];
    	Yii::$app->session['error'] = '';

    	if ($list == 1) return $this->render('reemplazalist',['mensaje' => $this->Mensajes($m),'error'=>$error]);

    	if (isset($_POST['txAccion']) and $_POST['txAccion']==1)
    	{
    		$error = '';
    		if (isset($_POST['txObjetoOrigen']) and $_POST['txObjetoOrigen']!=='') $obj_origen=$_POST['txObjetoOrigen'];
    		if (isset($_POST['txObjetoDestino']) and $_POST['txObjetoDestino']!=='') $obj_destino=$_POST['txObjetoDestino'];

    		$error = Persona::PersonaReemplaza($obj_origen, $obj_destino);

    		if ($error == '')
    		{
    			//return $this->render('reemplazalist', ['mensaje' => $this->Mensajes(3)]);
    			return $this->redirect(['reemplaza', 'm' => 12]);
    		}else {
    			return $this->render('reemplaza', ['error' => $error]);
    		}

    	}else {
    		return $this->render('reemplaza');
    	}
    }

    public function actionReemplazaanula($oldnum)
    {
    	if ($oldnum != '')
    	{
    		$error = '';
    		$error = Persona::PersonaReemplazaAnula($oldnum);
    		Yii::$app->session['error'] = $error;
    		if ($error == '')
    		{
    			return $this->redirect(['reemplaza', 'm' => 15]);
    		}else {
    			return $this->redirect(['reemplaza']);
    		}

    	}else {
    		return $this->redirect(['reemplaza']);
    	}
    }

    public function actionBuscar()
    {
        $cond='';
		$objeto ='';
		$cant = 1;

		if (isset($_POST['txObjeto']) and $_POST['txObjeto']!=='') $objeto=$_POST['txObjeto'];
		if (isset($_POST['txDoc']) and $_POST['txDoc']!=='') $cond="ndoc=".$_POST['txDoc'];
		if (isset($_POST['txCuit']) and $_POST['txCuit']!=='') $cond="cuit='".str_replace('-','',$_POST['txCuit'])."'";

		if ($cond!=='' or $objeto!=='')
		{
			// obtengo la cantidad de resultados de la búsqueda
			// si es un solo objeto redirecciono a la vista de ese objeto
			// sino abro el listado de resultados
			if ($cond!=='') $cant = utb::getCampo("v_persona",$cond,"count(*)");

			if ($cant <= 1 or $objeto!=='')
			{
				if ($cond!=='') $objeto = utb::getCampo("v_persona",$cond,"obj_id");

				if (strlen($objeto) < 8) $objeto = utb::GetObjeto(3,(int)$objeto);

      			$m = 0;
      			if (utb::getNombObj("'".$objeto."'") == '') $m = 13;

      			return $this->redirect(['view', 'id' => $objeto, 'm' => $m]);
			}else {  // Si son mas de uno los resultados,tengo que cargar el listado
                $model = new PersonaListado();
                $model->ndoc = Yii::$app->request->post('txDoc', null);
                $model->cuit = Yii::$app->request->post('txCuit', null);

                $res = $model->buscar();
                $datos = ListadopersonaController::datosResultado($model, $res);
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

    }

    public function actionImprimir($id,$vinc=0,$comp=0)
    {
    	$array = (new Persona)->Imprimir($id);
        $model = $this->findModel($id);
        $modelobjeto = (new Objeto)->cargarObjeto($id);

		$vinculos = null;
		if ($vinc == 1){
			$vinculos = Objeto::CargarVinculos($id)->getModels();
		}

    	$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
		if ($comp == 0)
			$pdf->content = $this->renderPartial('//reportes/persona',['datos' => $array,'vinculos' => $vinculos]);
		else
			$pdf->content = $this->renderPartial('//reportes/personacompleto',['datos' => $array,'vinculos' => $vinculos,'model' => $model,'modelobjeto' => $modelobjeto]);

        return $pdf->render();
    }

	public function actionConsultaweb($m=0)
	{
		if ($m == 1)
			$msn = "Datos Grabados";
		else
			$msn = "";


		$fechadesde = isset($_POST['fechadesde']) ? $_POST['fechadesde'] : '';
		$fechahasta = isset($_POST['fechahasta']) ? $_POST['fechahasta'] : '';
		$estado = isset($_POST['estado']) ? $_POST['estado'] : '';
		$tema = isset($_POST['tema']) ? $_POST['tema'] : '';
		$documento = isset($_POST['documento']) ? $_POST['documento'] : '';

		$cond = '';
		$error = '';

		// validar fechas
		if (($fechadesde != '' and $fechahasta =='') or ($fechadesde == '' and $fechahasta != '')){
			$error = 'Complete rango de fechas a buscar';
		}
		if ($fechadesde != '' and $fechahasta !=''){
			if (strtotime($fechahasta) > strtotime($fechadesde)) $error = 'Rango de fechas mal ingresado';
		}

		// armar condición si no hay erores
		if ($error == '') {
			if ($fechadesde != '' and $fechahasta !=''){
				if ($cond <> '') $cond .= ' and ';
				$cond .= " fecha::date BETWEEN '" . $fechadesde . "' and '". $fechahasta . "'";
			}
			if ($estado != ''){
				if ($cond <> '') $cond .= ' and ';
				$cond .= "est='" . $estado . "'";
			}
			if ($tema != ''){
				if ($cond <> '') $cond .= ' and ';
				$cond .= "tema='" . $tema . "'";
			}
			if ($documento != ''){
				if ($cond <> '') $cond .= ' and ';
				$cond .= "ndoc=" . $documento;
			}
		}

		return $this->render('consultasweb',[
			'dataProvider' => new ArrayDataProvider(['allModels'=> $cond != '' ? (new ConsultaWeb)->ConsultasWeb($cond) : null]),
			'model' => null,
			'alert' => $msn,
			'm' => $m,
			'error' => $error
		]);
	}

	public function actionConsultawebedit($id,$consulta = 1)
	{
		$model = new ConsultaWeb();

		if (Yii::$app->request->isAjax and $model->load(Yii::$app->request->post())){
			if ($consulta == 3 and $model->respuesta == "")
				return "Ingrese la Respuesta";
			else
				$resp = $model->Grabar($consulta);

			if ($resp != "")
				return $resp;
			else
				return $this->redirect(['consultaweb','m'=>1]);

		}elseif (($model = ConsultaWeb::findOne($id)) !== null) {
			return $this->render('consultaswebedit',['model' => $model,'consulta'=>$consulta]);
		}else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	public function actionAjusteweb($m=0)
	{
		if ($m == 1)
			$msn = "Datos Grabados";
		else
			$msn = "";


		$fechadesde = isset($_POST['fechadesde']) ? $_POST['fechadesde'] : '';
		$fechahasta = isset($_POST['fechahasta']) ? $_POST['fechahasta'] : '';
		$estado = isset($_POST['estado']) ? $_POST['estado'] : '';

		$cond = '';
		$error = '';

		// validar fechas
		if (($fechadesde != '' and $fechahasta =='') or ($fechadesde == '' and $fechahasta != '')){
			$error = 'Complete rango de fechas a buscar';
		}
		if ($fechadesde != '' and $fechahasta !=''){
			if (strtotime($fechahasta) > strtotime($fechadesde)) $error = 'Rango de fechas mal ingresado';
		}

		// armar condición si no hay erores
		if ($error == '') {
			if ($fechadesde != '' and $fechahasta !=''){
				if ($cond <> '') $cond .= ' and ';
				$cond .= " fecha::date BETWEEN '" . $fechadesde . "' and '". $fechahasta . "'";
			}
			if ($estado != ''){
				if ($cond <> '') $cond .= ' and ';
				$cond .= "est='" . $estado . "'";
			}
		}

		return $this->render('ajustesweb',[
			'dataProvider' => new ArrayDataProvider(['allModels'=> $cond != '' ? (new ConsultaWeb)->AjustesWeb($cond) : null]),
			'model' => null,
			'alert' => $msn,
			'm' => $m,
			'error' => $error
		]);
	}

	public function actionAdjuntos($id)
	{
		$error = "";

		if (isset($_POST['txId'])){
			$imgfoto = UploadedFile::getInstanceByName('ImgFoto');
			$imgdoc = UploadedFile::getInstanceByName('ImgDoc');
			$imgmonot = UploadedFile::getInstanceByName('ImgMonotributo');

			if ($imgfoto == null and $imgdoc == null and $imgmonot == null) $error .= "<li>No se adjunto ningún documento</li>";

			if ($error == "") $error = Persona::GuardarDocumento($id,$imgfoto,$imgdoc,$imgmonot);
		}

		$doc = Persona::CargarDocumento($id);
		return $this->render('documentosadjuntos',['doc' => $doc,'error' => $error]);
	}

    /**
     * Función que se utiliza para dar de alta a una persona a "Ingresos Brutos".
     * @param string $id Identificador de persona.
     * @param integer $action Identificador del tipo de acción.
     */
    public function actionIb( $id, $action = 1 ){

        /**
         * Verificar que la persona esté inscripta en IB. De lo contrario, se visualiza el alta a IB.
         */

        $model          = $this->findModel( $id );
        $modelObjeto    = ( new Objeto() )->cargarObjeto( $id );
        $modelodomileg  = Domi::cargarDomi( 'PLE', $id, 0 );

        /**
         * Según el parámetro ib_modo en la configuración del sistema:
         *      'A' -> Automático: el número de ingresos brutos lo establece el sistema.
         *      'C' -> CUIT: el número de ingresos brutos es igual al número de CUIT ( Debe existir el CUIT ).
         *      'M' -> Manual: el número de ingresos brutos lo ingresa el usuario ( validar que no se repita ).
         */
        $config = utb::getCampo( 'sam.config', '', 'ib_modo' );

        switch( $config ){

            case 'C':

                $model->ib = $model->cuit;
        }

        //Si la persona no tiene CUIT, no se puede inscribir en IB.
        if( $model->cuit == '' ){

            Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1002 );

            return $this->redirect([ 'view', 'id' => $id ]);
        }

        $modelRubroTemporal         = [];       //Almacenará el modelo de rubro que se modifica.
        $mostrarModalRubros         = 0;    //Indica si se debe mostrar la ventana modal de rubros.

        if( $this->cargarArregloRubrosDesdeBD ){

            //Cargar los rubros en memoria
            $this->setRubros( ComerRubro::getRubros( $id ) );

        }

        if( !$model->verificarInscripcionIB() ){

            $action = 0;    //Inscripción a IB
        }

        if( Yii::$app->request->isPjax ){

            if( Yii::$app->request->get( '_pjax', '' ) == "#persona_ib_form_pjaxCambiaContador" ){

                $obj_id = Yii::$app->request->get( 'obj_id', '' );

                $model->contador = utb::getObjeto( 1, $obj_id );

                //Verificar que el objeto exista
                if( utb::verificarExistenciaObjeto( 1, "'" . $model->contador . "'" ) ){

                    $model->contador_nom = utb::getNombObj( "'" . $model->contador . "'" );
                } else {
                    $model->inmcontadorueble = '';
                    $model->contador_nom = '';
                }
            }

            if( Yii::$app->request->get( '_pjax', '' ) == "#persona_rubro_pjaxGrilla" ){

                $nuevoRubro = new ComerRubro();
                $txAction = Yii::$app->request->get( 'txAction', 1 );

                switch( $txAction ){

                    case 0: $nuevoRubro->setScenario( 'insert' ); break;
                    case 2: $nuevoRubro->setScenario( 'delete' ); break;
                    case 3: $nuevoRubro->setScenario( 'update' ); break;
                }

                if( $nuevoRubro->load( Yii::$app->request->get() ) ){

                    $nuevoRubro->obj_id     = $modelObjeto->obj_id;
                    $rubros                 = $this->getRubros();

                    if( ComerRubro::ABMRubrosEnArreglo( $rubros, $nuevoRubro, $txAction ) ){

                        $this->setRubros( $rubros );

                    } else {
                        $modelRubroTemporal = $nuevoRubro;
                        $mostrarModalRubros = 1;

                    }
                }
            }
        }

        if( Yii::$app->request->isPost ){

            $action = intVal( Yii::$app->request->post( 'txActionFormIB', 1 ) );

            switch( $action ){

                case 0: $model->setScenario( 'insertIB' ); break;
                case 2: $model->setScenario( 'deleteIB' ); break;
                case 3: $model->setScenario( 'updateIB' ); break;
				case 4: $model->setScenario( 'activarIB' ); break;
            }

            if( $model->load( Yii::$app->request->post() ) ){

                // Obtener el array de domicilio legal
                if ( isset( $_POST['arrayDomiLeg'] ) ){

                    $modelodomileg = unserialize( urldecode( stripslashes( $_POST['arrayDomiLeg'] ) ) );
                }

                $modelodomileg->torigen = 'PLE';

                // cargo los datos de las direcciones correspondientes
        		$model->domi_legal = $modelodomileg->domicilio;

                $error = '';

                if( $modelodomileg->domicilio !== null ){

                    $error = $modelodomileg->grabar();
                }

                if( $error == '' ){ //Se grabó bien el domicilio Legal

                    if ($action == 4){
						$res = $model->activarInscripcion();
					}else {
						$model->rubros = $this->getRubros();

						$res = $model->grabarIB( $action );
					}

        			if( $res ){

                        Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

        				return $this->redirect(['ib', 'id' => $model->obj_id]);
        			}

                } else {

                    Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1000 );

                }

    		}
        }

        //La fecha se formatea asi para que se visualice correctamente en el DatePicker
        $model->fchalta_ib = Fecha::usuarioToDatePicker( $model->fchalta_ib );
        $model->fchbaja_ib = Fecha::usuarioToDatePicker( $model->fchbaja_ib );

        return $this->render( 'viewIB', [

            'mensaje'       => $this->Mensajes( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
            'error'         => $this->Mensajes( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, 0 ) ),

            'configIB'      => $config,
            'model'         => $model,
            'modelObjeto'   => $modelObjeto,
            'modelodomileg' => $modelodomileg,
            'consulta'      => $action,

            'permiteModificarDomiLeg'   =>  utb::getExisteProceso( 3603 ),

            //Especiales
            'modelRubroTemporal'        => $modelRubroTemporal,
            'mostrarModalRubros'        => $mostrarModalRubros,

            'dpRubros'      => new ArrayDataProvider([
                'allModels' => $this->getRubros(),
            ]),

            'organizacion'  => Persona::getOrganizacionesJuridicas(),
            'liquidacion'   => Persona::getTipoLiquidaciones(),
			'tbajaib'   => Persona::getTipoBajaIB(),
        ]);
    }


    /**
     * Función que se utiliza para activar una inscripción a "Ingresos Brutos".
     * @param string $id Identificador del tipo de acción.
     */
    public function actionExentoib( $id ){

        if( ( new Persona() )->exento( $id ) ){

            Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

        } else {

            Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1003 );
        }

        return $this->redirect([ 'ib', 'id' => $id]);
    }

    /**
     * Función que se utiliza para obtener un arreglo de nombres de contadores.
     * @param string $term Caracteres de búsqueda.
     */
    public function actionSugerenciacontador( $term = '' ){

        /**
         * Se deben ingresar 3 o más letras para que se realice la búsqueda.
         */

        $ret = [];

        if( $term == '' || strlen( $term ) < 3 ){
            return json_encode( $ret );
        }

        $condicion = "tobj = 3 AND nombre iLike '%$term%' AND obj_id IN ( SELECT obj_id FROM sam.usuarioweb WHERE acc_dj = 'S')";

        $ret = utb::getAux( 'objeto', 'obj_id', 'nombre', 0, $condicion );

        if( $ret === false ) $ret = [];

        return json_encode( $ret );
    }

    /**
     * Función que se utiliza para obtener el código contador según el nombre de contador ingresado..
     * @param string $nombre Nombre del contador seleccionado.
     */
    public function actionCodigocontador( $nombre = '' ){

        $sql = "SELECT obj_id FROM objeto WHERE nombre = '$nombre' AND tobj = 3";

        $ret = utb::getCampo( 'sam.usuarioweb', "obj_id IN (" . $sql . ")", 'usr_id');

        return $ret;
    }

	public function actionConstanciaib($id)
    {
    	$model = Persona::findOne($id);
		$domicilioPostal = (new Domi())->cargarDomi('OBJ', $id, 0);
		$domicilioFiscal = (new Domi())->cargarDomi('FIS', $id, 0);
		$model->rubros = ComerRubro::findAll(['obj_id' => $id, 'est' => 'A']);

		$config = utb::getCampo( 'sam.config', '', 'ib_modo' );

		switch( $config ){

			case 'C':

				$model->ib = $model->cuit;
		}

    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/constanciaib',['model' => $model,'domipost' => $domicilioPostal->domicilio,'domifiscal' => $domicilioFiscal->domicilio]);

        return $pdf->render();
    }

    /**
     * Función que se utiliza para actualizar el arreglo en memoria para "Rubros".
     * @param array $rubros Arreglo de rubros
     */
    public function setRubros( $rubros = [] ){

        Yii::$app->session->set( self::CONST_PERSONA_ARRAY_RUBROS, $rubros );
    }

    /**
     * Funcion que se utiliza para obtener el arreglo en memoria de "Rubros".
     */
    public function getRubros(){

        return Yii::$app->session->get( self::CONST_PERSONA_ARRAY_RUBROS, [] );
    }

    /**
     * Finds the Persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Persona::findOne($id)) !== null) {
            $modelDomi = new Domi;
          	$model->domi_legal = $modelDomi->getDomicilio('PLE',$model->obj_id,0);
            $model->domi_res = $modelDomi->getDomicilio('PRE',$model->obj_id,0);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function Mensajes($id)
    {
    	switch ($id) {

            case 1:

                $mensaje = 'Los datos se grabaron correctamente.';
                break;

            case 2:

                $mensaje = 'La inscripción se habilitó correctamente.';
                break;

    		case 11:
    			$mensaje = 'La Persona fue dada de baja con anterioridad.';
    			break;
    		case 12:
    			$mensaje = 'La Persona fue reemplazada con éxito.';
    			break;
    		case 13:
    			$mensaje = 'No existe la Persona.';
    			break;
    		case 14:
    			$mensaje = 'La persona se dio de baja correctamente.';
    			break;
    		case 15:
    			$mensaje = 'Se anuló el reemplazo de personas correctamente.';
    			break;

            case 1001:

                $mensaje = 'Ocurrió un error al intentar activar la inscripción a Ingresos Brutos.';
                break;

            case 1002:

                $mensaje = 'Para la inscripción de Ingresos Brutos, la persona debe tener CUIT.';
                break;

            case 1003:

                $mensaje = 'Ocurrió un error al intentar poner exenta la inscripción a Ingresos Brutos.';
                break;

    		default:
    			$mensaje = '';
    	}

    	return $mensaje;
    }
}
