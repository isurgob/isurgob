<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Pagocta;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\web\Session;
use app\utils\db\utb;


/**
 * PagoctaController implements the CRUD actions for Pagocta model.
 */
class PagoctaController extends Controller
{
	const CONST_MENSAJE						= "pagocta_mensaje";
	const CONST_MENSAJE_ERROR				= "pagocta_mensaje_error";
	const CONST_PJAX_REINICIA_SESION 		= "#pagocta_pjaxReiniciaSesion";
	const CONST_PJAX_EDICION_CABECERA 		= "#pagocta_pjaxEdicionCabecera";
	const CONST_PJAX_GRILLA_CUENTAS			= "#pagocta_pjaxGrillaCuenta";
	const CONST_ARREGLO_CUENTAS				= "pagocta_arregloCuentas";

    public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

    	$session = new Session;
    	$session->open();

    	$id = $action->getUniqueId();

    	if ( $id != 'ctacte/pagocta/create')
    	{
    		//Reinicio el arreglo en sesion
    		$this->setArregloCuentas( [] );
    	}

		if ($id != 'ctacte/pagocta/listado')
		{
			$session['cond'] = '';
			$session['descr'] = '';
		}


    	$session->close();
    	return true;
    }

    /**
     * Displays a single Pagocta model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = '',$action = 1,$consulta = 1,$alert = '',$m = 0)
    {
    	/**
    	 * $consulta == 0 => Formulario para inserción de datos
    	 * $consulta == 1 => Formulario para inserción de datos y procesar
    	 * $consulta == 2 => Formulario para inserción de datos y calcular
    	 * $consulta == 3 => Formulario para inserción de datos y grabar
    	 */

    	if (!isset($model)) $model = new Pagocta();

    	//Si m=1 y alert == '' => Crear mensaje
    	if ($m == 1 && $alert == '')
    	{
    		$alert = 'Los datos se grabaron correctamente.';
    	}

		//Creo el DataProvider para la grilla de cuentas vacío
    	$dataProvider = new ArrayDataProvider(['allModels' => []]);

    	if ($id != '')
    	{
    		$model = $this->findModel($id); //Obtengo los datos del modelo
    		$dataProvider = $model->cargarDatosGrilla($id);

    		if ($model->pago_id == '' || $model->pago_id == null)
    		{
    			$id = '';
    			$alert = 'No se encontró ningún Pago a Cuenta.';
				$m = 2;
				$consulta = 1;
    		}

    	}

        return $this->render('view', [
        	'consulta'=>$consulta,
        	'action'=>$action,
            'model' => $model,
            'dataProvider' => $dataProvider,
            'id' => $id,
            'm'=>$m,
            'alert'=>$alert,

        ]);
    }

    /**
     * Creates a new Pagocta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		/*
		 * La variable existe determinará si es "Pago Parcial" o "Pago a Cuenta".
		 * $existe = 2 => "Pago a Cuenta".
		 * $existe = 1 => "Pago Parcial".
		 * $existe = 0 => ERROR.
		 */
		$existe						= 0;
		$habilitarAceptar 			= false;
		$mostrarModalLiquidacion	= false;

		$model = new Pagocta();

		if( Yii::$app->request->isPjax ){
			if( Yii::$app->request->isGet ){

				if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_PJAX_REINICIA_SESION ){
					Yii::$app->session->set( 'arregloCuentaPagocta', [] );
				}

				// Cuando se modifica el tributo, tipo de objeto u objeto
				if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_PJAX_EDICION_CABECERA ){

					//Obtener Datos que s eenvían por Pjax
					$model->load( Yii::$app->request->get() );

					$quitarObjeto	= Yii::$app->request->get( 'quitarObj', 0 );	//Variable que indica si se debe quitar el objeto

					/**
					 * La variable acción determina la acción a ejecutar:
					 * 	0 -> Cambia objeto o verifica existencia
					 *	1 -> Cargar
					 *	2 -> Grabar
					 */
					$accion			= Yii::$app->request->get( 'accion', 0 );

					//Obtener el tipo de objeto del tributo
					$model->trib_tobj 	= utb::getCampo( 'trib', 'trib_id = ' . $model->trib_id, 'tobj' );

					//Obtiene el tipo de tributo
					$model->ttrib 		= utb::getCampo( 'trib', 'trib_id = ' . $model->trib_id, 'tipo' );

					$usa_subcta 		= utb::getCampo( 'trib', 'trib_id = ' . $model->trib_id, 'uso_subcta' );

					if( !$model->usa_subcta ){
						$model->subcta = 0;
					}

					if( $quitarObjeto ){

						$model->obj_id 	= '';
						$model->obj_nom	= '';

						if( $model->trib_tobj != 0 ){
							$model->tobj = $model->trib_tobj;
						}

					} else {

						//Completa el nombre del objeto en caso de que no se ingrese completo
						if ( strlen($model->obj_id) < 8 && $model->obj_id != '')
						{
							$model->obj_id = utb::GetObjeto((int)$model->tobj,(int)$model->obj_id);
						}

						//Compara el tipo de objeto que se completó recién con el tipo de objeto calculado anteriormente
						//Si son iguales, obtiene el nombre del objeto.
						if ( utb::getTObj( $model->obj_id ) == $model->tobj )
						{
							$model->obj_nom = utb::getNombObj("'".$model->obj_id."'");

						} else
						{
							if( $model->obj_id != '' ){

								Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1001 );
							}

							$model->obj_id 	= '';
							$model->obj_nom	= '';
						}

					}

					if( $model->trib_id != 0 && $model->anio != 0 && $model->cuota != 0 && $model->obj_id != '' ){

						$existe = Pagocta::obtenerTipoPago( $model->trib_id, $model->obj_id, $model->anio, $model->cuota );

						if( $existe == 2 ){
							$model->monto = '';

						}

						if( $existe == 0 ){

							//ERROR => El período ingresado no existe
							Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1002 );
						}
					}

					switch( $accion ){

						case 0:	//Cambia Objeto

							//Reinicia el arreglo de cuentas
							$this->setArregloCuentas( [] );

							break;

						case 1:

							$existe = Pagocta::obtenerTipoPago( $model->trib_id, $model->obj_id, $model->anio, $model->cuota );

							if( $existe == 1 ){	//Pago Parcial

								$estCtacte = Pagocta::getEstadoCtaCte( $model->trib_id, $model->obj_id, $model->subcta, $model->anio, $model->cuota );

								if ( $estCtacte == 'T' || $estCtacte == 'D' ){

									//Obtengo la fecha de venc de la ctacte
									$fchvenc = Pagocta::getFechaVencimiento( $model->trib_id, $model->obj_id, $model->subcta, $model->anio, $model->cuota );

									//Cargar las cuentas
									$this->setArregloCuentas( Pagocta::cargarCuenta($model->trib_id,$model->obj_id,$model->anio,$model->cuota,$model->monto,$fchvenc,$model->fchlimite) );

									$habilitarAceptar = true;

								} else {
									//ERROR => El período ingresado no existe
									Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1003 );
								}

							} else {

								$habilitarAceptar = true;
							}

							break;

						case 2:

							$validaLiquidacion = Yii::$app->request->get( 'validaLiquidacion', 1 );

							if( $validaLiquidacion ){

								if( Pagocta::existeEmiDJ( $model->trib_id, $model->obj_id, $model->anio, $model->cuota ) ){

									$mostrarModalLiquidacion = true;

								}

							}

							if( !$mostrarModalLiquidacion ){

								$resultado = $model->grabarPagocta( $this->getArregloCuentas() );

								if( $resultado == 1 ){
										return $this->redirect([ 'view', 'm'=>1, 'alert'=>'', 'id' => $model->pago_id ]);
								} else {

									Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, $resultado );
								}

							}

							$habilitarAceptar = true;

							break;

					}

				}

				// Agrega o elimina una cuenta
				if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_PJAX_GRILLA_CUENTAS ){

					$cuenta_id 	= Yii::$app->request->get( 'cuenta_id', 0 );
					$cuenta_nom = Yii::$app->request->get( 'cuenta_nom', '' );
					$monto 		= Yii::$app->request->get( 'monto', 0 );
					$action 	= Yii::$app->request->get( 'action', 1 );

					$existe = 2;

					//Si se envian datos por POST desde la ventana modal
					if ( $cuenta_id != 0 )
					{
						$this->setArregloCuentas( Pagocta::abmCuentaAArreglo( $this->getArregloCuentas(), $action, $cuenta_id, $cuenta_nom, $monto ) );
					}
				}
			}
		}

        return $this->render('_form', [

			'existe'		=> $existe,
			'habilitarAceptar'	=> $habilitarAceptar,
			'mensaje'		=> $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
			'error'			=> $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, 0 ) ),

			'mostrarModalLiquidacion'	=> $mostrarModalLiquidacion,

			'arrayTributo'	=> Pagocta::getTributos( 'tipo IN (1,2,3,4) OR trib_id = 1 OR trib_id = 3' ),
			'arrayObjetos'	=> Pagocta::getTiposObjeto(),
			'montoTotal'	=> Pagocta::obtenerMontoArregloCuentas( $this->getArregloCuentas() ),

            'model' 		=> $model,

            'dataProvider' 	=> new ArrayDataProvider([
				'allModels'	=> $this->getArregloCuentas(),
			]),

        ]);


    }

	private function setArregloCuentas( $array ){

		return Yii::$app->session->set( self::CONST_ARREGLO_CUENTAS, $array );
	}

	private function getArregloCuentas(){

		return Yii::$app->session->get( self::CONST_ARREGLO_CUENTAS, [] );
	}

	/**
	 * Método que se encarga de dar de baja a un Pago
	 */
    public function actionDelete($id)
    {
		$model = new Pagocta(); //Obtengo los datos del modelo

		//Elimino el pago
		$alert = $model->eliminarPagoCta($id);
		if ($alert == '')
			$m = 1;
		else
			$m = 2;

		return $this->redirect(['view','m'=>$m,'alert'=>$alert,'id'=>$id]);
    }

    /**
     * Método que se encarga de dar de baja los pago vencidos
     */
    public function actionDeletevencidas()
    {
    	$model = new Pagocta(); //Obtengo los datos del modelo

		//Elimino el pago
		$alert = $model->eliminarPagoCtaVencida();
		if ($alert == '')
			$m = 1;
		else
			$m = 2;

		return $this->redirect(['view','m'=>$m,'alert'=>$alert]);
    }

	/**
	 * Función que se ejecuta cuando se busca un Pago a Cuenta
	 */
    public function actionBuscar()
    {
    	$pago_id = Yii::$app->request->post('txNum','');

		return $this->redirect(['view','id' => $pago_id, 'consulta' => 1]);

    }

 /**
     * Función que se encarga de mostrar las opciones de búsqueda y mostrar los datos
     */
    public function actionListado( $reinicia = 0 )
    {
    	$session = new Session;
		$session->open();

    	$cond = "";
    	$descr = "";

    	if ($reinicia == 1)
    	{
    		$session['cond'] = '';
    		$session['descr'] = '';
    	}

    	if (isset($_POST['txCriterio']) and $_POST['txCriterio'] != "") $cond = $_POST['txCriterio'];
    	if (isset($_POST['txDescripcion']) and $_POST['txDescripcion'] != "") $descr = $_POST['txDescripcion'];


		if (isset($session['cond']) && $session['cond'] !== '') $cond = $session['cond'];
		$session['cond'] = '';
		$session->close();

    	if ($cond == "")
    	{

	        return $this->render('list_op',[
	        	]);
    	} else {

    		$session = new Session;
			$session->open();
			$session['cond'] = $cond;
			$session['descr'] = $descr;
			$session['order'] = 'pago_id';
			$session->close();

    		return $this->render('list_res');

    	}
    }

    public function actionImprimir($id)
    {
    	$sub1 = null;
    	$array = (new Pagocta)->Imprimir($id,$sub1);

    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '5px';
    	$pdf->marginBottom = '5px';
		$pdf->content = $this->renderPartial('//reportes/boletapagocta',['emision' => $array,'sub1'=>$sub1]);
        $pdf->methods['SetHeader'] = '';
  		$pdf->methods['SetFooter'] = '';
  		$pdf->filename = 'BoletaPagoCta';
        return $pdf->render();
    }

	/**
	 * Función que se utiliza para retornar mensajes
	 */
	private function getMensaje( $id ){

		switch( $id ){

			case 1001:

				$title = 'El objeto ingresado no existe.';
				break;

			case 1002:

				$title = 'El Período ingresado no es válido.';
				break;

			case 1003:

				$title = 'El estado del período no permite un Pago Parcial.';
				break;

			case 1004:

				$title = 'No existen vencimientos del Tributo para el período ingresado.';
				break;

			case 1005:

				$title = 'No se puede realizar el pago. Existe Liquidación con Pago Anual.';
				break;

			case 1006:

				$title = 'El período está en Juicio o Convenio.';
				break;

			case 1007:

				$title = 'Ocurrió un erorr al grabar los datos.';
				break;

			case 1008:

				$title = 'Ingrese alguna cuenta.';
				break;

			default:

				$title = '';
				break;
		}

		return $title;
	}

    /**
     * Finds the Pagocta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pagocta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pagocta::findOne($id)) !== null) {
        	$model->cargarDatos($id);
            return $model;
        } else {
            $model = new Pagocta();
            return $model;
        }
    }
}
