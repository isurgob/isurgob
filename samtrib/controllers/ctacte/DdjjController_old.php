<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Ddjj;
use app\models\ctacte\DdjjAnual;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\utils\db\utb;
use app\models\ctacte\RetencionDetalle;
use app\models\ctacte\Retencion;

/**
 * DdjjController implements the CRUD actions for Ddjj model.
 */
class DdjjController extends Controller
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
			return true;
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

	    // si alta de ddjj
	    if (isset($_GET['consulta']) and $_GET['consulta']==0 and !utb::getExisteProceso(3331)) {
			echo $this->render('//site/nopermitido');
        	return false;
		}

    	$id = $action->getUniqueId();

    	if ( $id != 'ctacte/ddjj/view' )
    	{
    		$session = new Session;
    		$session->open();

    		$session['DDJJArregloRubros'] = []; //Arreglo de Rubros
    		$session['DDJJNextDJRubro_id'] = ''; //Identificador para la tabla TEMP de rubros
    		$session['DDJJArregloItems'] = [];
    		$session['DDJJBaseTotal'] = '';
    		$session['DDJJMontoTotal'] = '';
    		$session['DDJJMultaTotal'] = '';

    		$session->close();
    	}

    	return true;
    }

    /**
     * Método que se ejecuta al llegar a DDJJ y que se encarga de setear variables en sesión.
     */
    public function actionIndex()
    {
    	/**
    	 * La variables en sesión "FiscalizaActiva" mantendrá el valor para $fiscaliza, de manera de no andar pasando la misma por GET.
    	 */
    	$session = new Session;
    	$session->open();
    	$session->set('FiscalizaActiva',0);
    	$session->close();

    	return $this->redirect(['view']);
    }

   /**
    * Función que se ejecuta para mostrar los datos de una DDJJ o para ingresar una nueva DDJJ
    * @param integer $id Identificador de la DDJJ
    * @param integer $consulta Variable que identifica la acción que se debe realizar
    * @param integer $action
    * @param string $objeto Cuando se va al formulario de alta, si se viene de consultar una DDJJ, se pasa el ID del objeto.
    */
    public function actionView( $id = '',$consulta = 1,$action = 0,$alert = '',$m = 0,$obj_id = '',$objeto = '' )
    {
    	/**
    	 * $action == 0 => Formulario para inserción de datos
    	 * $action == 1 => Formulario para inserción de datos y procesar
    	 * $action == 2 => Formulario para inserción de datos y calcular
    	 * $action == 3 => Formulario para inserción de datos y grabar
    	 */

         $model = $this->findModel( $id ); //Obtengo los datos del modelo

    	$session = new Session;
    	$session->open();

    	//Creo el arreglo para rubros en sesión en caso de que no exista.
    	if ( $session['DDJJArregloRubros'] == null )
    		$session['DDJJArregloRubros'] = [];

    	//Creo el arreglo para ítem en sesión en caso de que no exista.
    	if ( $session['DDJJArregloItems'] == null )
    	 	 $session['DDJJArregloItems'] = [];

        //Grabar los datos de la DJ
        if( Yii::$app->request->isPost ){

            if (isset($_POST["txObjetoID"]) && $_POST['txObjetoID'] != '')
    	    {
    			$trib = intval(Yii::$app->request->post('dlTrib', -1));
    			$objeto = $_POST['txObjetoID'];
    			$subcta = utb::getCampo('trib','trib_id = ' . $trib,'uso_subcta');
    			$anio = $_POST['txAño'];
    			$cuota = $_POST['txCuota'];
    			$tipo = $_POST['dlTipo'];
    			$fchpresenta = $_POST['fchpresentacion'];
    			$DJRub_id = $session['DDJJNextDJRubro_id'];
    			$fiscaliza_post = $_POST['txFiscaliza'];

    			$resultado = ($trib > 0 ? $model->grabarDJ($trib,$objeto,$subcta,$fiscaliza_post,$anio,$cuota,$tipo,$fchpresenta,$DJRub_id) : false );

    			if ( $resultado != '' )
    			{
    				$session['DDJJNextDJRubro_id'] = '';
    				$session['DDJJArregloRubros'] = [];
    				$session['DDJJArregloItems'] = [];

    				$m = 1;
    				$alert = '';

    				$id = $resultado;

    				//Redirigir a la pantalla inicial
    				return $this->redirect(['rete','dj_id' => $id]);

    			} else
    			{
    				//Error
    				$m = 2;
    				$alert = "La DJ no se grabó.";

    			}
    	    }
        }


    	//Si m = 1 y alert == '' => Crear mensaje
    	if ($m == 1 && $alert == '')
    	{
    		$alert = 'Los datos se grabaron correctamente';
    	}

    	//$dia = date('d') . '/' . date('m') . '/' . date('Y');

    	//En caso de que se llegue a la vista de DDJJ desde un módulo aparte, se debe realizar la búsqueda por $obj_id
    	if ( $obj_id != '' )
    	{
    		$id = $model->getIDFromObjeto( $obj_id );
    	}

		if ( $id != '' )
		{

			//Si no se encuentra ningún dato
			if ( $model->dj_id == '' || $model->dj_id == null)
			{
				$alert = 'No se encontró ninguna DDJJ.';
				$m = 2;
			}
		}

    	if ($m == 7)
    	{
    		$m = 2;
    		$alert = 'No se encontraron DDJJ.';
    	}

    	if ($objeto != '')
    		$model->obj_id = $objeto;

        echo $this->view( $id, $consulta, $action, $alert, $m, $model );

    }

    private function view( $id = '',$consulta = 1,$action = 0,$alert = '',$m = 0, $model){

        if( $model == [] )
            $model = new Ddjj();

        $dataProviderRubros = $model->getDatosGrillaRubros( $id );
    	$dataProviderLiq = $model->getDatosGrillaLiq( $id );
    	$dataProviderAnt = $model->getDatosGrillaAnt( $id );
		$dataProviderRete = new ArrayDataProvider([
			'allModels' => $model->getRetencionesAsociadasADJ( intval( $model->ctacte_id ) ),
		]);

        return $this->render('view', [
        	'consulta'=>$consulta,
        	'action'=>$action,
            'model' => $model,
            'id' => $id,
            'est' => $model->est,
            'dia'=> date('d/m/Y'),
            'm'=>$m,
            'alert'=>$alert,
            'config_ddjj'   => Ddjj::getConfig_ddjj(),
            'dataProviderRubros'=>$dataProviderRubros,
            'dataProviderLiq'=>$dataProviderLiq,
            'dataProviderAnt'=>$dataProviderAnt,
			'dataProviderRete'=>$dataProviderRete,

        ]);
    }

    public function actionCompara()
    {
		$model = new Ddjj();

    	return $this->render('compara',['model' => $model]);
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
    		$session = new Session;
			$session->open();
			$session['order'] = '';
			$session['by'] = '';
			$session['cond'] = $cond;
			$session['descr'] = $descr;
			$session->close();

    		return $this->redirect(['list_res']);
    	}
    }

    /**
     * Función que muestra el resultado de la búsqueda
     */
	public function actionList_res()
    {

    		$session = new Session;
			$session->open();
	        $session['order'] = 'dj_id';

	        $descr = $session['descr'];
	        $cond = $session['cond'];

	        $session->close();

			return $this->render('list_res',
				[
				'descr' => $descr,
				'cond' => $cond
				]);
    }

	/**
     * Función que se ejecuta cuando se realiza la búsqueda de una DDJJ
     */
    public function actionBuscar()
    {

    	$id = Yii::$app->request->post('txNumDDJJ','');

    	if ($id != '')
    	{
    		return $this->redirect(['view','id'=>$id]);

    	} else if (isset($_POST['txObj_Id']))
    	{

    		$cont = utb::getCampo('v_dj',"obj_id='".$_POST['txObj_Id']."'",'count(*)');

    		if ($cont > 1){
    			$session = new Session;
				$session->open();
				$session['cond'] = "obj_id='".$_POST['txObj_Id']."'";
				$session['descr'] = "Objeto: ".$_POST['txObj_Id'];
				$session->close();

				return $this->redirect([
							'list_res'
							]);
    		} else if($cont == 1)
    		{
    			$plan = utb::getCampo('v_dj',"obj_id='".$_POST['txObj_Id']."'",'dj_id');
    			return $this->redirect(['view', 'id' => $plan]);
    		}
    	}

    	return $this->redirect(['view', 'm' => 7]);

    }

    /**
     * Función que se ejecuta para realizar la baja de una DJ
     * @param integer $id Identificador de la DJ
     */
    public function actionBorrar($id)
    {
    	$model = new Ddjj();
    	$model = $this->findModel($id);

    	$res = $model->eliminarDJ(); //Elimino la DJ

    	if ($res == '')
    		return $this->redirect(['view', 'id' => $id, 'm' => 1, 'alert' => '']);
    	else
    		return $this->redirect(['view', 'id' => $id, 'm' => 2, 'alert' => $res]);
    }

    public function actionImprimir($id)
    {
    	$sub1 = null;
    	$sub2 = null;
    	$sub3 = null;
    	$array = (new Ddjj)->Imprimir($id,$sub1,$sub2,$sub3);

    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '5px';
    	$pdf->marginBottom = '5px';
    	$pdf->marginLeft = '7px';
    	$pdf->marginRight = '7px';
		$pdf->content = $this->renderPartial('//reportes/boletaddjj',['emision' => $array,'sub1'=>$sub1,'sub2'=>$sub2,'sub3'=>$sub3]);
        $pdf->methods['SetHeader'] = '';
  		$pdf->methods['SetFooter'] = '';
  		$pdf->filename = 'BoletaDDJJ';
        return $pdf->render();
    }

    public function actionListadj($id,$fs = 0){

		$session = new Session;
		$session->open();

		$session->set('FiscalizaActiva',$fs);

		$session->close();

        return $this->render('listaDJ', ['obj_id' => $id, 'externo' => true]);
    }

    /**
     * Carga el modelo con la ultima declaracion jurada anual para el objeto dado y un data provider con todas las declaraciones juradas anuales.
     * Si se pasa el año como parametro, el modelo se carga con la DDJJ de ese año
     */
    public function actionDdjjanual($obj_id = '', $anio = 0){

    	$obj_id = trim($obj_id);
    	$anio = intval($anio);
    	$m = intval(Yii::$app->request->get('m', -1));
    	$mensaje = $m === 1 ? 'Datos grabados correctamente' : null;

    	$model = new DdjjAnual();

    	if($anio > 0)
    		$model = DdjjAnual::findOne(['obj_id' => $obj_id, 'anio' => $anio]);
    	else $model = DdjjAnual::findOne(['obj_id' => $obj_id]);


    	$dp = new ArrayDataProvider(['allModels' => []]);

    	if($model !== null) $dp = new ArrayDataProvider(['allModels' => DdjjAnual::findAll(['obj_id' => $obj_id]), 'pagination' => false, 'key' => 'anio']);
    	else{

    		$model = new DdjjAnual();

    		$model->obj_id= $obj_id;

    		$datosObjeto= utb::getVariosCampos('objeto', "obj_id = '$obj_id'", 'obj_dato, nombre');
    		if($datosObjeto !== false){
    			$model->obj_dato= $datosObjeto['obj_dato'];
    			$model->nombre= $datosObjeto['nombre'];
    		}
    	}

    	return $this->render('anual', ['model' => $model, 'dpAnual' => $dp, 'mensaje' => $mensaje]);
    }


    /**
     * Agrega una declaración jurada anual para el objeto dado
     */
    public function actionAgregaranual( $obj_id = ''){

    	$model = new DdjjAnual();

    	if(Yii::$app->request->isPost){

    		$modificar = filter_var(Yii::$app->request->post('modificar', false), FILTER_VALIDATE_BOOLEAN);
			$borrar = filter_var(Yii::$app->request->post('borrar', false), FILTER_VALIDATE_BOOLEAN);

			if(!$borrar){

				$model->setScenario('agregar');
				if($model->load(Yii::$app->request->post())){

	    			$res = false;
	    			$grabar = true;

	    			if($modificar){

	    				$model = DdjjAnual::findOne(['obj_id' => $model->obj_id, 'anio' => $model->anio]);

	    				if($model === null){
	    					$model = new DdjjAnual();
	    					$model->setScenario('agregar');
	    					$model->load(Yii::$app->request->post());
	    					$model->addError($model->obj_id, 'No hay declaración jurada correspondiente al año ' . $model->anio . ' para modificar');
	    					$grabar = false;

	    				} else{
	    					$model->setScenario('agregar');
	    					$model->load(Yii::$app->request->post());
	    				}
	    			}

	    			if($model !== null && $grabar){
	    				$res = $model->agregar($modificar);

	    				if($res) return $this->redirect(['ddjjanual', 'obj_id' => $model->obj_id, 'm' => 1]);
	    			}
	    		}
			} else {

				$model->setScenario('delete');
				if($model->load(Yii::$app->request->post())){

					$model = DdjjAnual::findOne(['obj_id' => $model->obj_id, 'anio' => $model->anio]);

					if($model !== null){

						$res = $model->borrar();

						if($res) return $this->redirect(['ddjjanual', 'obj_id' => $model->obj_id, 'm' => 1]);

					} else{
						$model = new DdjjAnual();
						$model->addError($model->obj_id, 'La declaración jurada anual no existe');
					}
				}
			}
    	}

    	if($model === null){
    		$model = new DdjjAnual();
    		$model->obj_id = $obj_id;
    	}

    	return $this->render('_agregar_anual', ['model' => $model]);
    }

    /**
     * Genera automáticamente las declaraciones juradas anuales
     * Si se provee un codigo de objeto, se lo utiliza para redirifir cuando la operacion se realiza con exito
     */
    public function actionGeneraranual($obj_id = ''){


    	$model = DdjjAnual::findOne(['obj_id' => $obj_id]);
    	if($model === null) $model = new DdjjAnual();

    	if( Yii::$app->request->isPost ){

    		$model->setScenario('generar');
    		if($model->load(Yii::$app->request->post())){

    			$res = $model->generar();

    			if($res) return $this->redirect(['ddjjanual', 'obj_id' => $model->obj_id, 'm' => 1]);
    		}
    	}

    	return $this->render('_generar_anual', ['model' => $model]);
    }

	/**
	 * Función que se utiliza para cargar las retenciones a una DDJJ.
	 * @param integer $id Identificador de la DJ.
     * @param integer $action Identificador del tipo de acción.
	 */
	public function cargarrete( $id = 0, $action = 1 )
	{
		$model = DdjjController::findModel( $id ); //Obtengo los datos del modelo

        $dataProviderRete = new ArrayDataProvider([
			'allModels' => $model->getRetenciones( $model->obj_id, $model->anio, $model->cuota ),
			'key' => 'ret_id',
		]);

        $dataProviderReteCargadas = new ArrayDataProvider([
			'allModels' => $model->getRetencionesAsociadasADJ( intval( $model->ctacte_id ) ),
		]);

		$error = '';

		$totales = $model->getTotalesDJ( $id );

		return $this->render('rete', [
			'model' => $model,
            'action'    => $action,
            'config_ddjj'   => Ddjj::getConfig_ddjj(),
            'dataProviderReteCargadas'  => $dataProviderReteCargadas,
			'dataProviderRete' => $dataProviderRete,
			'totales' => $totales,
			'error' => $error,
		]);
	}

    /**
     * Función que se utiliza para agregar retenciones.
     * @param stirng $obj_id Identificador del objeto al cual se le agregarán las retenciones.
     */
    public function nuevaRete( $dj_id, $obj_id ){

        $model = new RetencionDetalle();
        $model->cargarDatosRetencion( $obj_id );

        return $this->render('agregarRete',[
            'dj_id'     => $dj_id,
            'model'     => $model,
            'consulta'  => 0,
            'agentes'   => RetencionDetalle::agentes(),
            'tiposComprobantes' => RetencionDetalle::tiposComprobantes(),
        ]);
    }

    /**
     * Función que se utiliza para grabar nuevas retenciones y las retenciones seleccionadas.
     */
    public function actionRete( $dj_id = 0, $m = 2 ){

        $model = $this->findModel( $dj_id );
        $modelRete = new RetencionDetalle();
        $modelRete->setScenario( 'insert' );
        $alert = '';

        $anio   = utb::GetCampo( 'ddjj', 'dj_id = ' . $dj_id, 'anio' );
        $cuota  = utb::GetCampo( 'ddjj', 'dj_id = ' . $dj_id, 'cuota' );

        if( Yii::$app->request->isPost ){

            if( $modelRete->load( Yii::$app->request->post() )){

                if( $modelRete->grabar( 0, $anio, $cuota ) ){

                    return $this->redirect(['rete', 'dj_id' => $dj_id, 'm' => 1 ]);

                } else {

                    $model->addErrors( $modelRete->getErrors() );
                    $alert = 'Ocurrió un error al grabar la retención.';
                }
            }

            $retenciones = Yii::$app->request->post( 'DDJJ_rete', [] );
            $dj_id = Yii::$app->request->post( 'dj_id', 0 );

            $model = $this->findModel( $dj_id );

            if( count( $retenciones ) > 0 ){

                $res = $model->grabarRetenciones( $retenciones, $dj_id );

                if( $res == 1 )
                {
                    return $this->redirect(['view', 'id' => intVal( $dj_id ), 'm' => 1]);
                }

                $alert = $this->getMensaje( $res );
            }

        }

        echo $this->view( $dj_id, 4, 4, $alert, $m, $model );
    }

    /**
     * Finds the Ddjj model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ddjj the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id = 0 )
    {
        if (( $model = Ddjj::findOne( intval( $id ) ) ) !== null)
        {
        	$model->obtenerDatosVistaDJ( $id );

        } else {
            $model = new Ddjj();
        }

        return $model;

    }

	private function getMensaje( $id )
	{
		switch( $id ){

			case 2:

				$title = 'El monto acumulado de las retenciones seleccionadas es mayor al monto de la DJ.';
				break;

			case 3:

				$title = 'Ocurrió un error al grabar los datos.';
				break;

			default:

				$title = '';
		}

		return $title;
	}
}
