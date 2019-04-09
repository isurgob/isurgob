<?php

namespace app\controllers\caja;

use Yii;
use app\models\caja\CajaEstado;
use app\models\caja\CajaTicket;
use app\models\caja\CajaOpera;
use app\models\caja\CajaPrueba;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\Fecha;
use yii\web\Session;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;

/**
 * CajaTicketController implements the CRUD actions for CajaTicket model.
 */
class CajaticketController extends Controller
{
	const EDITA_TICKET = 'edita_ticket';
	const ARREGLO_TICKET = 'arreglo_ticket';

	#Constante para "Pagos Anteriores"
	const CONST_PAGOANT_PJAX_CAMBIAOBJETO	= '#pagoant_pjax_actualizaTObjeto';

	public $model;

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

		return true;
		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

	    // si no es consulta de recibomanual, verifico que se tenga los permisos de edición
	    if (isset($_POST['recibomanual-consulta']) and $_POST['recibomanual-consulta'] != 1){
	    	if (!utb::getExisteProceso(3497)) {
		        echo $this->render('//site/nopermitido');
		        return false;
		    }
	    }

		$this->model = new CajaTicket();

		$id = $action->getUniqueId();

		$session = new Session;
		$session->open();

		$tokenAnterior= intval( $session->get( 'token', -1 ) );
		$token= -1;

		switch ( $id )
		{
			case 'caja/cajaticket/recibomanual_list':

				return true;

			case 'caja/cajaticket/viewrecibomanual':

				$session->set( 'arregloRecibosManual', [] );
				$session->set( 'token', 1 );

				break;

			case 'caja/cajaticket/createrecibomanual':


				$token = 0;

				break;

			case 'caja/cajaticket/updaterecibomanual':
			case 'caja/cajaticket/deleterecibomanual':

				$token = 2;

				break;

			default:

				$token = 3;
		}

			$session->set('token', $token);

			if( $tokenAnterior !== $token || $token === -1)
			{

				$session->set( 'arregloRecibosManual', [] );

				$iden = Yii::$app->request->get( 'id', -1 );

				if ( $iden != -1 )
				{
					if ( $id == 'caja/cajaticket/createrecibomanual')
					{
						$model = new CajaTicket();
						$model->cargarReciboManual( $iden );

					} else
					{

						$this->model->cargarReciboManual( $iden );
					}
				}
			}

	    return true;
	}

    public function actionSupervision()
    {
    	return $this->redirect(['apertcie']);

    }

    /**
     * Función que permite mostrar los tickets
     * @param integer $id Identificador de la operación
     * @param integer $m Código de mensaje
     * @param boolean $rei Si es true, se vaciaran las variables en sesión.
     * @param integer $opera Indica si se accede a "Ticket" desde "Opera"
     * @param integer $oid ID de la operación.
	 * @param integer $list Se llega desde listado.
     */
    public function actionTicket( $id = '', $m = 0, $rei = 1, $opera = 0, $oid = 0, $lista = 0 )
    {
		//Elimino variables en sesión que son usadas para modificar el ticket
		Yii::$app->session->remove( self::ARREGLO_TICKET );

    	//La función ConsultaPermitida determina si se puede realizar la búsqueda del Ticket
    	$model = new CajaTicket();

    	if ($id !== '' && CajaTicket::findOne( $id ) !== null)
    	{
    		if ($model->consultaPermitida('T',$id))
    		{
    			$model = $this->findModelTicket($id);

    			//Transformo el formato de la fecha y hora
    			$model->fecha = Fecha::bdToUsuario($model->fecha);
				$model->hora = date('h:i:s', strtotime($model->hora) );
    		}
    		else
    			$m = 1;
    	} else {
    		if ($id == '')
    			$m = 0;
    		else
    			$m = 1;
    	}

    	//Si el estado del objeto es 'B', se debe cargar el motivo de la baja
    	if ($model->est == 'B')
    		$model->get_AnulaMotivo('T', $model->ticket);

    	$session = new Session;
    	$session->open();

    	if ( $rei )
    	{ //Vacío las variables
    		$session['condListadoCobranza'] = '';
    		$session['descrListadoCobranza'] = '';
    		$session['returnCajaCobro'] = '';
    	}

    	//Si la variable lista es 1 quiere decir que se llega a esta acción desde
    	//el listado de ticket, por lo que hay que dibujar un botón que permita volver al listado
		if ( $lista == 1 )
			$botonVolver = 1;
		else
			$botonVolver = 0;

		//Si la variable opera es 1, el botón de vovler debe redireccionar a "Opera"
		if ( $opera == 1 )
			$botonVolver = 2;

    	$session->close();

        return $this->render('_formTicket', [
            'model' => $model,
            'consulta' => 1,
            'alerta' => $m,
            'botonVolver' => $botonVolver,
            'oid' => $oid,
			'edita' => 0,	// Variable que indica si se modifica el Ticket. 0 -> No se modifica
        ]);
    }

    /**
     * Función que se ejecuta al pulsar el Botón de Buscar
     */
    public function actionBuscar()
    {
    	$id = '';

		//Variable que indica si se debe editar el Ticket
		if ( Yii::$app->request->post( 'editaTicket', 0 ) )
		{
			return $this->redirect(['editaTicket','id' => $id]);
		}

    	//Redirigir a las vistas de ticket
    	if (isset($_POST['ticket-id']))
    	{
    		$id = $_POST['ticket-id'];
    		return $this->redirect(['ticket','id'=>$id]);
    	}

    	//Redirigir a las vistas de operación
    	if (isset($_POST['opera-id']))
    	{
    		$id = $_POST['opera-id'];
    		return $this->redirect(['opera','id'=>$id]);
    	}

    }

	/**
	 * Función que permite mostrar los tickets y editarlos
	 * @param integer $id Identificador de la operación
	 * @param integer $m Código de mensaje
	 * @param boolean $rei Si es true, se vaciaran las variables en sesión.
	 * @param integer $opera Indica si se accede a "Ticket" desde "Opera"
	 * @param integer $oid ID de la operación
	 */
	public function actionEditaticket( $id )
	{
		$grabar = Yii::$app->request->post( 'txGrabar', 0 );

		//La función ConsultaPermitida determina si se puede realizar la búsqueda del Ticket
		$model = new CajaTicket();

		if ($id !== '' && CajaTicket::findOne( $id ) !== null)
		{
			if ($model->consultaPermitida('T',$id))
			{
				$model = $this->findModelTicket($id);

				//Transformo el formato de la fecha
				$model->fecha = Fecha::bdToUsuario($model->fecha);
			}
		}

		//Si el estado del Ticket es 'B', no se debe permitir la modificación
		if ( $model->est == 'B' )
		{
			return $this->redirect([ 'ticket', 'id' => $model->ticket ]);
		}

		$arreglo_sesion = Yii::$app->session->get( self::ARREGLO_TICKET );

		if ( $grabar )
		{	//Se realizaron las modificaciones sobre el Ticket y se procede a Grabar

			if ( $model->grabarTicketActualizado( $arreglo_sesion ) )
			{
				return $this->redirect([ 'ticket', 'id' => $model->ticket ]);
			}
		}

		//Cargar datos del arreglo en sesión en caso de que no se encuentren cargados
		if( !isset( $arreglo_sesion ) )
		{
			Yii::$app->session->set( self::ARREGLO_TICKET, $model->CargarDetalle() );
		}

		//Si el estado del objeto es 'B', se debe cargar el motivo de la baja
		if ($model->est == 'B')
			$model->get_AnulaMotivo('T', $model->ticket);

		return $this->render('_formTicket', [
			'model' => $model,
			'consulta' => 1,
			'alerta' => 0,
			'botonVolver' => 0,
			'oid' => 0,
			'edita' => 1,	// Variable que indica si se modifica el Ticket
		]);
	}

    /**
     * Función que permite mostrar las operaciones.
     * @param integer $id Identificador de la operación.
     * @param integer $m Código de mensaje.
     * @param boolean $rei Si es true, se vaciarán las variables en sesión.
	 *lista identific si viene desde el listado
     */
    public function actionOpera($id = '', $m = 0, $rei = 1, $lista=0)
    {
    	//La función ConsultaPermitida determina si se puede realizar la búsqueda de la Operación
    	$model = new CajaOpera();

    	if ($id !== '' and CajaOpera::findOne($id) !== null)
    	{
    		if ($model->consultaPermitida('O',$id))
    		{
    			$model = $this->findModelOpera($id);

    			//Transformo el formato de la fecha
    			$model->fecha = Fecha::bdToUsuario($model->fecha);
    		}
    		else
    			$m = 1;
    	} else {
    		if ($id == '')
    			$m = 0;
    		else
    			$m = 1;
    	}

    	$session = new Session;
    	$session->open();

    	if ($rei)
    	{
    		//Vacío las variables
    		$session['condListadoCobranza'] = '';
    		$session['descrListadoCobranza'] = '';
    		$session['returnCajaCobro'] = '';
    	}

    	//Si la variable lista es 1 quiere decir que se llega a esta acción desde
    	//el listado de ticket, por lo que hay que dibujar un botón que permita volver al listado
		if ($lista==1)
			$botonVolverListado = 1;
		else
			$botonVolverListado = 0;

    	$session->close();
        return $this->render('_formOpera', [
            'model' => $model,
            'consulta' => 1,
            'alerta' => $m,
            'botonVolverListado' => $botonVolverListado,
        ]);
    }

    /**
     *
     */
    public function actionPrueba()
    {

    		$model = new CajaPrueba();
	    	$m = '';
	    	$cod = '';
	    	$grilla = 0;

    	if(isset($_POST['cajaPrueba-codBarra'])){

    		$cod = trim($_POST['cajaPrueba-codBarra']);

    		$m = $model->obtenerDatos( $cod );

    		$model->fechaPago = $_POST['cajaPrueba-fchpago'];

    		if ($m == '')
    			$grilla = 1;

    	}

    	return $this->render( '_formPrueba', [

            'model' 	=> $model,
            'consulta' 	=> 1,
            'alerta' 	=> $m,
            'grilla' 	=> $grilla,

        ]);
    }

    /**
     * Función que se ejecuta al ingresar a Listado Cobros
     * @param integer $reiniciar Si vale 1, indica que se deben resetear las sesiones
     */
    public function actionListado($rei = 0)
    {
    	//Verifico que no vengan ticket ni opera, ya que estos datos se envian cuando el
    	//usuario hace click en la grilla
    	if (isset($_POST['opera']))
			$this->redirect(['opera', 'id' => $_POST['opera'], 'rei' => 0]);

		else if (isset($_POST['ticket']))
			$this->redirect(['ticket', 'id' => $_POST['ticket'], 'rei' => 0]);


		if ((!isset($_POST['ticket'])) && (!isset($_POST['opera'])))
		{
	    	$session = new Session;
			$session->open();

	    	if ($rei == 1)
	    	{
	    		$session['condListadoCobranza'] = '';
				$session['descrListadoCobranza'] = '';
				$session['returnCajaCobro'] = '';
	    	}

	    	$cond = "";
	    	$descr = "";

	    	if (isset($_POST['txCriterio']) && $_POST['txCriterio'] != "") $cond = $_POST['txCriterio'];
	    	if (isset($_POST['txDescripcion']) && $_POST['txDescripcion'] != "") $descr = $_POST['txDescripcion'];


			if (isset($session['condListadoCobranza']) && $session['condListadoCobranza'] !== '') $cond = $session['condListadoCobranza'];
			if (isset($session['descrListadoCobranza']) && $session['descrListadoCobranza'] !== '') $descr = $session['descrListadoCobranza'];

	    	if ($cond == "")
	    	{

		   		return $this->redirect(['//ctacte/listadocobrocticket/index']);

	    	} else {

				$session['condListadoCobranza'] = $cond;
				$session['descrListadoCobranza'] = $descr;

	    		return $this->redirect(['//ctacte/listadocobrocticket/index']);

	    	}

	    	$session->close();
		}
    }

    public function actionList_op()
    {
      	$session = new Session;
		$session->open();
		$session['condListadoCobranza'] = '';
		$session['descrListadoCobranza'] =
		$session->close();

		return $this->render('list_op');
    }


    /**
     *
     */
    public function actionApertcie()
    {
    	$m = 2;
    	$codigos = [];
    	$tipo = 2;
    	$fecha = '';
    	$teso_id = 2;
    	$error = [];
    	$mensaje = "";
    	$dia = Fecha::getDiaActual();

    	$model = new CajaTicket();

		//Llegan codigos, tipo, tesorería y fecha
		if(isset($_POST['apertcie-tipo'])) $tipo = $_POST['apertcie-tipo'];
		if(isset($_POST['apertcie-tesoreria'])) $teso_id = $_POST['apertcie-tesoreria'];
		if(isset($_POST['apertcie-fchpago'])) $fecha = $_POST['apertcie-fchpago'];
		if(isset($_POST['listadoCobranza-ckTicket'])) $codigos = $_POST['listadoCobranza-ckTicket'];

		//En caso de que se haya seleccionado algún check de la lista
		if ( count( $codigos ) > 0 )
		{
			if ( $tipo == 0 ) //Apertura de cajas
			{
				foreach ( $codigos as $codi )
				{
					try
					{
						//En el caso de que la fecha ingresada sea menor a la fecha actual
						if( !Fecha::menor( $dia, $fecha ) )
						{
							$errores = $model->getIngresos( $codi, $fecha ); //Verifico si existen ingresos

							if ( $errores == '' ) //Si no existen ingresos, intento abrir la caja
								$errores = $model->aperturaSupervisor( $codi, $fecha );

							if ( $errores != '' )	//Si se producen errores al abrir la caja
								$mensaje = $errores;
								$m = 2;

						} else //En el caso de que la fecha ingresada sea la del día actual
						{
							$errores = $model->aperturaSupervisor($codi,$fecha);
							if ($errores != '') $error[] = $errores;
						}

					} catch (\Exception $e)
					{
						$mensaje = 'No se pudieron abrir las cajas.';
						$m = 2;
						//break 2;
						break ;

					}
				}

				if ( count($error) == 0 && $mensaje == '' )
				{
					$mensaje = 'Las cajas se abrieron correctamente.';
					$m = 1;
				}
			}

			if ( $tipo == 1 ) //Cierre de cajas
			{
				foreach ($codigos as $codi)
				{
					try{

						$errores = $model->cierreSupervisor($codi,$fecha);
						if ($errores != '') $error[] = $errores;

					} catch (\Exception $e)
					{
						$mensaje = 'No se pudieron cerrar las cajas.';
						$m = 3;
					}
				}

				if (count($error) == 0 && $m != 3)
				{
					$mensaje = 'Las cajas se cerraron correctamente.';
					$m = 1;
				}
			}

		}

    	return $this->render('apert_cie', [
    					'model' => $model,
    					'm'=> $m,
    					'mensaje' => $mensaje,
    					'error' => $error,
    					'dia' => $dia,

    				]);
    }

    /**
     * Función que se ejecuta al consultar el estado de una caja
     */
    public function actionEstadocaja()
    {
    	$model = new CajaEstado();
    	$dia = date('d') . '/' . date('m') . '/' . date('Y');
    	$m = 0;
    	$mensaje = '';
    	$error = [];

    	if ((isset($_POST['estadoCaja-codCaja']) && $_POST['estadoCaja-codCaja'] != '') && (isset($_POST['estadoCaja-estadoNom']) && $_POST['estadoCaja-estadoNom'] != ''))
    	{

    		$caja = $_POST['estadoCaja-codCaja'];
    		$model->obtenerDatos($caja);

    		$est = $model->est;

	    	switch($est)
			{
				case 0:
					$mensaje = $model->anularCierreSup($model->caja_id,$model->fecha);
					$m = 2;
					if ($mensaje == '')
					{
						$mensaje = 'El cierre del supervisor se anuló correctamente.';
						$m = 1;
					}
					break;

				case 1:
					$mensaje = $model->anularApertura($model->caja_id,$model->fecha);
					$m = 2;
					if ($mensaje == '')
					{
						$mensaje = 'La apertura se anuló correctamente.';
						$m = 1;
					}
					break;

				case 3:
					$mensaje = $model->anularCierre($model->caja_id,$model->fecha);
					$m = 2;
					if ($mensaje == '')
					{
						$mensaje = 'El cierre se anuló correctamente.';
						$m = 1;
					}
					break;
			}

    	}

    	$model = new CajaEstado();

    	return $this->render('_formCajaEstado', [
    					'model' => $model,
    					'dia' => $dia,
    					'm' => $m,
    					'error' => $error,
    					'mensaje' => $mensaje,

    				]);
    }


    //-------------------------------------ANULACIÓN-----------------------------------------------//


    public function actionAnula()
    {
    	$model = new CajaTicket();
    	$dia = date('d') . '/' . date('m') . '/' . date('Y');
    	$m = 0;
    	$mensaje = '';
    	$error = [];
    	$anula = 0;
    	$tipo = "";

    	$model = new CajaTicket();

		//Llegan codigos, tipo
			// $tipo = 1 => Rechazar
			// $tipo = 2 => Confirmar
		if(isset($_POST['confRech'])) $anula = $_POST['confRech'];
		if(isset($_POST['listaAnulacion'])) $codigos = $_POST['listaAnulacion'];

		//Código que se ejecuta para rechazar las anulaciones
		if ($anula == 1)
		{
			$m = 1;
			$mensaje = "Las anulaciones se rechazaron correctamente.";

			foreach ($codigos as $codi)
			{

				$arr = explode('-',$codi);

				try
				{
					//$arr[0] va a contener el ID del comprobante
					//substr($arr[1],0,1) obtengo el tipo de comprobante
					//		T => Ticket
					//		O => Opera

					$errores = $model->anulaRechaza(substr($arr[1],0,1),$arr[0]);
					if ($errores != '')
					{
						$m = 2;
						$mensaje = $errores;
					}



				} catch (\Exception $e)
				{
					$mensaje = 'No se pudieron rechazar las anulaciones.';
					$m = 2;

				}

			}

		}

		//Código que se ejecuta para confirmar las anulaciones
		if ($anula == 2)
		{

			$m = 1;
			$mensaje = "Las anulaciones se confirmaron correctamente.";

			foreach ($codigos as $codi)
			{

				$arr = explode('-',$codi);

				try
				{
					//$arr[0] va a contener el ID del comprobante
					//substr($arr[1],0,1) obtengo el tipo de comprobante
					//		T => Ticket
					//		O => Opera

					$errores = $model->anulaConfirma(substr($arr[1],0,1),$arr[0]);
					if ($errores != '')
					{
						$m = 2;
						$mensaje = $errores;
					}


				} catch (\Exception $e)
				{
					$mensaje = 'No se pudieron confirmar las anulaciones.';
					$m = 2;

				}

			}

		}


    	$supervisor = $model->getNombreUsuario();

    	return $this->render('anulaCobro', [
    					'model' => $model,
    					'dia' => $dia,
    					'm' => $m,
    					'error' => $error,
    					'mensaje' => $mensaje,
    					'supervisor' => $supervisor,

    				]);
    }

    //-------------------------------------RECIBO MANUAL-----------------------------------------------//

    /**
     * Función que se utiliza para el manejo de "Recibo Manual".
     * @param integer $reiniciar Identifica si se deben eliminar los arreglos en memoria.
     * @param integer $baja Identifica si el recibo que se muestra se encuentra dado de baja
     */
    public function actionViewrecibomanual($id = "",$accion = 0,$baja = 0,$m = '', $alert = '')
    {

    	/**
		 * @param $consulta es una variable que:
		 * 		=> $consulta == 1 => El formulario se dibuja en el index
		 * 		=> $consulta == 0 => El formulario se dibuja en el create
		 * 		=> $consulta == 3 => El formulario se dibuja en el update
		 * 		=> $consulta == 2 => El formulario se dibuja en el delete
		 */

     	/**
		 * @param $accion es una variable que:
		 * 		=> $accion == 0 => El formulario se dibuja en el index, create y update
		 * 		=> $accion == 2 => El formulario se dibuja en el delete
		 * 		=> $accion == 3 => Se activa el recibo manual
		 * 		=> $accion == 4 => Se graba o actualiza el "recibo manual"
		 */

		 $this->model = new CajaTicket();

		 //Entra si se activa el recibo manual
		 if ($accion == 3 && $id != '')
		 {
		 	$this->model->activarReciboManual($id);

		 	if ( !$this->model->hasErrors() )
		 	{
		 		$m = 1;
		 		$alert = 1;

		 	}

		 }

		 //Entra si se cargan los datos
		 if ( $id != '' )
		 {
		 	$this->model->cargarReciboManual( $id );

		 	# Verificar el estado del recibo
		 	$baja = $this->model->estadoBajaRecibo( $id );
		 }

    	return $this->render('viewReciboManual', [
					'model'=>$this->model,
					'consulta' => 1,
					'accion'=>$accion,
					'id'=>$id,
					'baja'=>$baja,
					'm'=>$m,
					'alert'=>$alert == 1 ? 'Los datos se grabaron correctamente' : $alert,
				]);
    }

    public function actionCreaterecibomanual( $reiniciar = 0 )
    {

		 //INICIO Obtener Variables
		 $id = Yii::$app->request->post('txID',-1);

		 $model = new CajaTicket();

		 if ( $model->load( Yii::$app->request->post() ) )
		 {

		 	//Grabo los datos
	 		$result = $model->grabarReciboManual(0,0,$model->obs);

	 		if ($result['return'] != 0)
	 		{
	 			$id = $result['return'];
	 		}

	 		//En caso de que no se hayan encontrado errores
		 	if ( !$model->hasErrors() )
		 		return $this->redirect(['viewrecibomanual','m'=>1, 'alert'=>1,'id'=>$id]);
		 }


    	return $this->render('viewReciboManual', [
					'model' => $model,
					'consulta'=> 0,
					'id' => 0,
					'baja'=> 0,
					'm'=> 1,
					'alert'=> '',
				]);
    }

    public function actionUpdaterecibomanual( $id )
    {

 		 //INICIO Obtener Variables
		 $id = Yii::$app->request->post('txID',$id);

		 if ( $this->model->load( Yii::$app->request->post() ) )
		 {

		 	//Grabo los datos
	 		$result = $this->model->grabarReciboManual($id,4,$this->model->obs);

	 		if ($result['return'] != 0)
	 		{
	 			$id = $result['return'];
	 		}

	 		//En caso de que no se hayan encontrado errores
		 	if ( !$this->model->hasErrors() )
		 		return $this->redirect(['viewrecibomanual','m'=>1, 'alert'=>1,'id'=>$id]);
		 }

    	 return $this->render('viewReciboManual', [
					'model' => $this->model,
					'consulta'=> 3,
					'id' => $id,
					'baja'=> 0,
					'm'=> 1,
					'alert'=> '',
				]);
    }

    public function actionDeleterecibomanual( $id, $action = 1 )
    {

		 //Entra si se elimina el recibo manual
		 if ($action == 2 && $id != '')
		 {
		 	$this->model->borrarReciboManual($id);

		 	if ( !$this->model->hasErrors() )
		 	{
		 		return $this->redirect(['viewrecibomanual','reiniciar'=>1, 'm'=>1, 'alert'=>1, 'id' => $id]);
		 	}

		 }

    	 return $this->render('viewReciboManual', [
					'model'=>$this->model,
					'consulta'=>2,
					'id'=>$id,
					'baja'=>0,
					'm'=>$m,
					'alert'=>'',
				]);
    }

    public function actionRecibomanual_list()
    {

	    $cond = Yii::$app->request->post('txCriterio','');
	    $descr = Yii::$app->request->post('txDescripcion','');
	    $tipo = Yii::$app->request->post('rbFiltro','');

    	$session = new Session;
    	$session->open();
    	$session['arregloRecibosManual'] = [];
		$session['banderaRecibosManual'] = 0;
		$session->set('rm_cond', $cond);
		$session->set('rm_descr',$descr);
		$session->set('rm_tipo', $tipo);
		$session->close();

    	/*La variable $tipo almacenará si es recibo o comprobante
    	 *		'R' => Recibo
    	 *		'C' => Comprobante
    	 */

    	if ( $cond != '' )
    	{

    		return $this->redirect(['recibomanualresult']);
    	}

    	return $this->render('reciboManual_list_op');


    }

    public function actionRecibomanualresult()
    {

    	$session = new Session;
    	$session->open();
    	$cond = $session->get('rm_cond','');
    	$descr = $session->get('rm_descr','');
    	$tipo = $session->get('rm_tipo','');

    	$model = new CajaTicket();
		$dataProvider = new ArrayDataProvider([
			'allModels' => $model->buscarReciboManual($cond, $tipo),
			'key' => 'ctacte_id' ]);

    	return $this->render('reciboManual_list_res',[
    								'cond'=>$cond,
    								'descr'=>$descr,
    								'tipo'=>$tipo,
    								'dataProvider' => $dataProvider,
    		]);
    }


    //-------------------------------------REGISTRO PAGO ANTERIOR-----------------------------------------------//

    /**
     * Función que carga registros de pagos anteriores.
     */
    public function actionPagoant($consulta = 1,$id = "",$m = '', $alert = '')
    {
		/**
		 * @param $consulta es una variable que:
		 * 		=> $consulta == 1 => El formulario se dibuja en el index
		 * 		=> $consulta == 0 => El formulario se dibuja en el create
		 * 		=> $consulta == 3 => El formulario se dibuja en el update
		 * 		=> $consulta == 2 => El formulario se dibuja en el delete
		 */

     	/**
		 * @param $accion es una variable que:
		 * 		=> $accion == 0 => El formulario se dibuja en el index, create y update
		 * 		=> $accion == 1 => Create
		 * 		=> $accion == 2 => El formulario se dibuja en el delete
		 * 		=> $accion == 3 => Se activa el recibo manual
		 */

    	$model = new CajaTicket();

    	$consulta = Yii::$app->request->post( 'txConsulta', $consulta );

    	if ( $id != '' )
			$model->cargarPagosOld($id);

		if( Yii::$app->request->isPjax ){
			if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_PAGOANT_PJAX_CAMBIAOBJETO ){

				$model->pagoant_tributo		= Yii::$app->request->get( 'tributo', 0 );
				$model->pagoant_obj_id		= Yii::$app->request->get( 'codigoObjeto', '' );
				$model->pagoant_obj_nom    	= '';
				$model->pagoant_obj_tobj	= 0;

				//En caso de que se haya seleccionado un tributo
				if ( $model->pagoant_tributo != 0 )
				{
					//Obtener el tipo de tributo
					$model->pagoant_obj_tobj = utb::getTObjTrib( $model->pagoant_tributo );

					if ( $model->pagoant_obj_tobj == "" )
						$model->pagoant_obj_tobj = 0;

                    if( ! ( $model->pagoant_obj_tobj == 0 ) ){

                        //Para actualizar el código de objeto

    					if ( $model->pagoant_obj_id != "" && strlen( $model->pagoant_obj_id ) < 8 )
    					{
    						$model->pagoant_obj_id = utb::GetObjeto( (int) $model->pagoant_obj_tobj, $model->pagoant_obj_id );

    					}

    					//Verificar la existencia del objeto
    					if ( utb::verificarExistenciaObjeto( (int)$model->pagoant_obj_tobj, "'" . $model->pagoant_obj_id . "'" ) == 0 )
    						$model->pagoant_obj_id = "";

    					//Obtengo el nombre del objeto
    					$model->pagoant_obj_nom = utb::getNombObj("'".$model->pagoant_obj_id."'");

                    }

				}

			}
		}

    	if ( $model->load( Yii::$app->request->post() ) )
    	{

			if ( $consulta == 0 )
			{
				//Grabo
    			$res = $model->registrarPagoOld();

			} else if ( $consulta == 2 )
			{
				$res = $model->borrarPagoOld();
			}

    		if ( !$model->hasErrors() )
		 	{
		 		$m = 1;
		 		$alert = 1;
		 		$consulta = 1;

		 	}

    	}

	   	return $this->render('viewRegPagoAnt',[
			'model' 	=> $model,
			'consulta'	=> $consulta,
			'id'		=> $id,
			'muestraSucursal'	=> $model->getIngresaSucursal( $model->pagoant_tributo ),
			'm'			=> $m,
			'alert' => $alert === 1 ? 'Los datos se grabaron correctamente' : $alert,

		]);
    }

    /**
     * Función que se utiliza para eliminar un pago
     */
    public function actionDeletepagoant( $id = '' )
    {
    	/**
		 * @param $consulta es una variable que:
		 * 		=> $consulta == 1 => El formulario se dibuja en el index
		 * 		=> $consulta == 0 => El formulario se dibuja en el create
		 * 		=> $consulta == 3 => El formulario se dibuja en el update
		 * 		=> $consulta == 2 => El formulario se dibuja en el delete
		 */

     	/**
		 * @param $accion es una variable que:
		 * 		=> $accion == 0 => El formulario se dibuja en el index, create y update
		 * 		=> $accion == 1 => Create
		 * 		=> $accion == 2 => El formulario se dibuja en el delete
		 * 		=> $accion == 3 => Se activa el recibo manual
		 */

    	$model = new CajaTicket();

    	if ( $id != '' )
			$model->cargarPagosOld($id);

		if ( $model->borrarPagoOld() ){

	 		$m = 1;
	 		$alert = 'Los datos se grabaron correctamente.';
	 		$consulta = 1;

	 		$model = new CajaTicket();

			return $this->redirect([ 'pagoant', 'm' => $m, 'alert' => $alert ]);

	 	} else {

	 		$m = 2;
	 		$alert = "";
	 		$consulta = 1;
	 	}

	   	return $this->render('viewRegPagoAnt',[

			'model' 	=> $model,
			'consulta' 	=> $consulta,
			'id'		=> $id,
			'm'			=> 1,
			'alert' 	=> $alert,

		]);
    }

    /**
     * Función que carga el lisado para registro de pagos anteriores
     */
    public function actionListregpagoant()
    {

    	$model = new CajaTicket();

    	return $this->render('regPagoAnt',[
    			'model'=>$model,
    			'tributo' => 0,
    			'objeto' => ''
    		]);
    }

    //-------------------------------------CHEQUE CARTERA-----------------------------------------------//

    public function actionChequecartera($consulta = 1,$id = "",$accion = 0,$baja = 0,$obs = '',$tipo = 0, $m = '')
    {
    	/**
		 * @param $consulta es una variable que:
		 * 		=> $consulta == 1 => El formulario se dibuja en el index
		 * 		=> $consulta == 0 => El formulario se dibuja en el create
		 * 		=> $consulta == 3 => El formulario se dibuja en el update
		 * 		=> $consulta == 2 => El formulario se dibuja en el delete
		 */

     	/**
		 * @param $accion es una variable que:
		 * 		=> $accion == 0 => El formulario se dibuja en el index, create y update
		 * 		=> $accion == 1 => Create
		 * 		=> $accion == 2 => El formulario se dibuja en el delete
		 * 		=> $accion == 3 => Se activa el recibo manual
		 */

    	$alert = '';

    	if (!isset($model))
    		$model = new CajaTicket();

    	if ($id != '')
    		$model->cargarChequeCartera($id);

    	//Cuando se crea o modifica un cheque cartera
    	$plan = Yii::$app->request->post( 'formChequeCartera_txPlan1', 0 );

    	if ( $plan != 0 )
    	{
    		 $id = Yii::$app->request->post('formChequeCartera_txCartID','');

    		 //Cargo los datos en el modelo
			 $model->convenio1 = Yii::$app->request->post('formChequeCartera_txPlan1','');
			 $model->convenio2 = Yii::$app->request->post('formChequeCartera_txPlan2','');
			 $model->banco = Yii::$app->request->post('formChequeCartera_txBancoID','');
			 $model->sucursal = Yii::$app->request->post('formChequeCartera_txSucID','');
			 $model->cuenta = Yii::$app->request->post('formChequeCartera_txCuenta','');
			 $model->titular = Yii::$app->request->post('formChequeCartera_txTitular','');
			 $model->cheque = Yii::$app->request->post('formChequeCartera_txCheque','');
			 $model->fechaCobro = Yii::$app->request->post('formChequeCartera_txFechaCobro','');
			 $model->monto = Yii::$app->request->post('formChequeCartera_txMonto','');
			 $model->cart_id = Yii::$app->request->post('formChequeCartera_txCartID','');
			 $model->bancoNom = Yii::$app->request->post('formChequeCartera_txBancoNom','');
			 $model->sucursalNom = Yii::$app->request->post('formChequeCartera_txSucNom','');

			//Grabo =>	cart_id == 0 => nuevoChequeCartera
			//			cart_id != 0 => modificarChequeCartera
			if ($model->cart_id == 0)
    			$model->nuevoChequeCartera();
    		else
    			$model->modificarChequeCartera();

    		if ( $model->hasErrors() )
		 	{
		 		$m = 2; //Para indicar que se trata de un error.
		 		$consulta = 0;

		 	} else
		 	{
		 		$model->cargarChequeCartera($id);

		 		$m = 1;
		 		$alert = 1;
				$consulta = 1;
		 	}
    	}

	 	//Eliminar elemento
    	if ($accion == 2 && $id != '')
    	{
    		$alert = $model->eliminarChequeCartera();

    		if ($alert != '')
		 	{
		 		$m = 2; //Para indicar que se trata de un error.

		 	} else
		 	{
		 		$m = 1;
		 		$alert = 1;

				$model = new CajaTicket();

		 	}

		 	$consulta = 1;
    	}


    	return $this->render('viewChequeCartera',[
			'model'=>$model,
			'consulta'=>$consulta,
			'accion'=>$accion,
			'id'=>$id,
			'm'=>$m,
			'alert'=>$alert === 1 ? 'Los datos se grabaron correctamente.' : $alert,

		]);



    }

    /*public function actionListchequecartera()
    {

    	$model = new CajaTicket();

    	return $this->redirect(['//caja/cajaticket/chequecartera']);
    }
*/
    public function actionImprimir($id)
    {
    	$sub1 = array();
    	$emision = CajaTicket::ImprimirReciboManual($id,$sub1);

    	$pdf = Yii::$app->pdf;
		$pdf->marginTop = 3;
		$pdf->marginBottom = 3;
		$pdf->marginLeft = 3;
		$pdf->marginRight = 3;
  		$pdf->content = $this->renderPartial('//reportes/boletarecibo',
  									[
										'emision' => $emision,
										'sub1' => $sub1
									]);
  		$pdf->methods['SetHeader'] = '';
  		$pdf->methods['SetFooter'] = '';
  		$pdf->filename = 'Boleta Recibo';
  		return $pdf->render();


  		
    }

	/**
	 * Función que se utiliza para obtener un arreglo de nombres de cuentas.
	 * @param string $term Caracteres de búsqueda.
	 */
	public function actionSugerenciacuenta( $term = '' ){

		/**
		 * Se deben ingresar 3 o más letras para que se realice la búsqueda.
		 */

		$ret = [];

		if( $term == '' || strlen( $term ) < 3 ){
			return json_encode( $ret );
		}

		$condicion = "nombre_redu iLike '%$term%'";

		$ret = utb::getAux( 'cuenta', 'cta_id', 'nombre_redu', 0, $condicion );

		if( $ret === false ) $ret = [];

		return json_encode( $ret );
	}

	/**
	 * Función que se utiliza para obtener el código de una cuenta según el nombre de cuenta ingresado.
	 * @param string $nombre Nombre de cuenta seleccionado.
	 */
	public function actionCodigocuenta( $nombre = '' ){

		$ret = utb::getCampo( 'cuenta', "nombre_redu = '$nombre'", 'cta_id');

		return $ret;
	}

    /**
     * Finds the CajaTicket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CajaTicket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelTicket($id)
    {
        if ( ($model = CajaTicket::findOne( $id ) ) !== null ) {
        	$model->obtenerDatosTicket();
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

        /**
     * Finds the CajaOpera model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CajaOpera the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelOpera($id)
    {
		 if (($model = CajaOpera::findOne($id)) !== null) {
        	$model->obtenerDatosOpera();
            return $model;
       	} else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
