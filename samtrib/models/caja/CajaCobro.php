<?php

namespace app\models\caja;
use yii\helpers\ArrayHelper;
use yii\data\SqlDataProvider;
use app\models\User;
use app\utils\helpers\DBException;
use yii\base\Model;
use app\utils\db\Fecha;
use yii\web\Session;
use yii\data\ArrayDataProvider;
use app\utils\db\utb;

use Yii;

/**
 * Éstas son las variables que se usarán para el modelo de CajaCobro
 *
 * 		** DataProviders **
 * @param dataProvider $dataProviderTicket Mantendrá los datos de los tickets.
 * @param dataProvider $dataProviderDetalle Mantendrá los datos de los detalles.
 * @param dataProvider $dataProviderDetalleTemp Mantendrá los datos de los detalles de forma temporal.
 * @param dataProvider $dataProviderItem Mantendrá los datos de los ítems.
 * @param dataProvider $dataProviderItemTemp Mantendrá los datos de los ítems temporalmente.
 * @param dataProvider $dataProviderIMdp Mantendrá los datos de los Ingresos de Medios de Pago.
 * @param dataProvider $dataProviderEMdp Mantendrá los datos de los Egresos de Medios de Pago .
 *
 * 		** Variables de Caja **
 * @param integer $caja_caja_id Identificador de la Caja.
 * @param string $caja_fecha Fecha de apertura de la Caja.
 * @param string $caja_estado Estado de la caja
 * @param string $caja_estado_estado Estado de la caja usado para agregar tickets
 *
 * 		** Variables de Operación **
 * @param integer $opera_opera_id Identificador de Operación actual.
 * @param double $opera_montototal Monto total de Operación (Sumatoria de Tickets).
 * @param double $opera_entregado Monto entregado de Operación (Sumatoria de MDP entregados).
 * @param double $opera_vuelto Monto vuelto de Operación (Sumatoria de MDP devuelto).
 * @param double $opera_sobrante Monto sobrante de Operación (Entregado - Vuelto).
 * @param integer $opera_cant Cantidad de Tickets en la operación actual.
 * @param integer $opera_ctacte_boleto_recibo Próximos ctacte_id a utilizar en caso de Boleto o Sellado (inicialmente es -1 y se va decrementando de a uno).
 *
 * 		** Variables de Ticket **
 * @param integer $ticket_ctacte_id Identificador de la CtaCte.
 * @param integer $ticket_ticket Identificador de Ticket.
 * @param integer $ticket_trib_id Identificador de Tributo.
 * @param string $ticket_trib_nom Nombre del Tributo.
 * @param string $ticket_trib_tipo Tipo de Tributo.
 * @param string $ticket_obj_id Identificador de Objeto.
 * @param string $ticket_tobj Tipo de Objeto.
 * @param integer $ticket_subcta Número de subcta.
 * @param integer $ticket_anio Año.
 * @param integer $ticket_cuota Cuota.
 * @param integer $ticket_faci_id Identificador de la Facilidad.
 * @param integer $ticket_num Identificador de Contribyente.
 * @param integer $ticket_num_nom Nombre de Contribyente.
 * @param double $ticket_monto Monto de Ticket.
 * @param string $ticket_fchvenc Fecha de Vencimiento de Ticket.
 * @param string $ticket_obs Observación de Ticket.
 * @param double $ticket_montoboleto Suma de los ítems de Boleto.
 *
 * 		** Variables de Arqueo **
 * @param double $arqueo_efectivo
 * @param double $arqueo_cheque
 * @param double $arqueo_tarjetacredito
 * @param double $arqueo_tarjetadebito
 * @param double $arqueo_deposito
 * @param double $arqueo_transferencia
 * @param double $arqueo_notacredito
 * @param double $arqueo_bonos
 * @param double $arqueo_haberes
 * @param double $arqueo_otros
 * @param double $arqueo_otrosvalores
 * @param double $arqueo_billete1000
 * @param double $arqueo_billete500
 * @param double $arqueo_billete200
 * @param double $arqueo_billete100
 * @param double $arqueo_billete050
 * @param double $arqueo_billete020
 * @param double $arqueo_billete010
 * @param double $arqueo_billete005
 * @param double $arqueo_billete002
 * @param double $arqueo_moneda100
 * @param double $arqueo_moneda050
 * @param double $arqueo_moneda025
 * @param double $arqueo_moneda010
 * @param double $arqueo_moneda005
 * @param double $arqueo_moneda001
 * @param double $arqueo_recuento
 * @param double $arqueo_total_efectivo
 * @param double $arqueo_total_otros
 * @param double $arqueo_total_fondo
 * @param double $arqueo_total_total
 * @param double $arqueo_total_sobrante
 * @param double $arqueo_cant_retiro
 *
 */

class CajaCobro extends \yii\db\ActiveRecord
{
	/*
	 * INICIO Declaración de las variables y dataProvider que se usarán.
	 */

	 //Declaración de DataProviders
	 public $dataProviderTicket;
	 public $dataProviderDetalle;
	 public $dataProviderDetalleTemp;
	 public $dataProviderItem;
	 public $dataProviderItemTemp;
	 public $dataProviderIMdp;
	 public $dataProviderEMdp;

	 //Declaración de variables de Caja
	 public $caja_caja_id;
	 public $caja_fecha;
	 public $caja_estado;
	 public $caja_estado_estado;
	 public $caja_posicion;
	 /**
	  * $caja_posicion es una variable que almacenará en qué estado se encuentra la caja en todo momento.
	  * 	'C' 	=> Caja Cerrada.
	  * 	'A1'	=> Abierta sin ningún Ticket o Sellado/Boleto ingresado.
	  * 	'A2'	=> Abierta con algún Ticket ingresado.
	  * 	'A3'	=> Abierta ingresando medios de pago. (Ingreso).
	  *		'A4'	=> Abierta ingresando medios de pago. (Egreso).
	  */

	 //Declaración de variables de Operación
	 public $opera_opera_id;
	 public $opera_montototal;
	 public $opera_entregado;
	 public $opera_vuelto;
	 public $opera_sobrante;
	 public $opera_cant;
	 public $opera_ctacte_boleto_recibo;
	 public $opera_imdp_id;
	 public $opera_emdp_id;

	 //Declaración de variables de Ticket
	 public $ticket_ctacte_id;
	 public $ticket_ticket;
	 public $ticket_trib_id;
	 public $ticket_trib_nom;
	 public $ticket_trib_tipo;
	 public $ticket_obj_id;
	 public $ticket_tobj;
	 public $ticket_subcta;
	 public $ticket_anio;
	 public $ticket_cuota;
	 public $ticket_faci_id;
	 public $ticket_num;
	 public $ticket_num_nom;
	 public $ticket_monto;
	 public $ticket_fchvenc;
	 public $ticket_obs;
	 public $ticket_montoboleto;

	 //Declaro variables que se utilizarán para el arqueo
	 public $arqueo_efectivo;
	 public $arqueo_cheque;
	 public $arqueo_tarjetacredito;
	 public $arqueo_tarjetadebito;
	 public $arqueo_deposito;
	 public $arqueo_transferencia;
	 public $arqueo_notacredito;
	 public $arqueo_bonos;
	 public $arqueo_haberes;
	 public $arqueo_otros;
	 public $arqueo_otrosvalores;
	 public $arqueo_billete1000;
	 public $arqueo_billete500;
	 public $arqueo_billete200;
	 public $arqueo_billete100;
	 public $arqueo_billete050;
	 public $arqueo_billete020;
	 public $arqueo_billete010;
	 public $arqueo_billete005;
	 public $arqueo_billete002;
	 public $arqueo_moneda100;
	 public $arqueo_moneda050;
	 public $arqueo_moneda025;
	 public $arqueo_moneda010;
	 public $arqueo_moneda005;
	 public $arqueo_moneda001;
	 public $arqueo_recuento;
	 public $arqueo_total_efectivo;
	 public $arqueo_total_otros;
	 public $arqueo_total_fondo;
	 public $arqueo_total_total;
	 public $arqueo_total_sobrante;
	 public $arqueo_cant_retiro;

	/*
	 * FIN Declaración de las variables y dataProvider que se usarán.
	 */

	/**
	 * Función que se utiliza para modificar el constructor por defecto.
	 */
	public function __construct()
	{
		$session = new Session;
		$session->open();
		$modelo =  unserialize(urldecode(stripslashes($session['modelCajaCobro'])));

		$this->caja_estado = Yii::$app->session->get('cajaCobro_estadoCaja','C');
    	$this->caja_caja_id = Yii::$app->session->get('cajaCobro_idCaja','');
		$this->caja_fecha = Yii::$app->session->get('cajaCobro_fechaCaja','');

		if ($this->caja_caja_id == '')
		{

			$this->caja_estado = 'C';	//Por defecto pongo el estado de la Caja en "C => Cerrada".
			$this->caja_posicion = 'C';
			$this->dataProviderTicket = [];
			$this->dataProviderDetalle = [];
			$this->dataProviderDetalleTemp = [];
			$this->dataProviderItem = [];
			$this->dataProviderItemTemp = [];
			$this->dataProviderIMdp = [];
			$this->dataProviderEMdp = [];
			$this->reiniciaVariablesArqueo();

		} else
		{

			$this->caja_estado = $modelo->caja_estado;
			$this->caja_estado_estado = $modelo->caja_estado_estado;
			$this->caja_posicion = $modelo->caja_posicion;
			$this->dataProviderTicket = $modelo->dataProviderTicket;
			$this->dataProviderDetalle = $modelo->dataProviderDetalle;
			$this->dataProviderDetalleTemp = $modelo->dataProviderDetalleTemp;
			$this->dataProviderItem = $modelo->dataProviderItem;
			$this->dataProviderItemTemp = $modelo->dataProviderItemTemp;
			$this->dataProviderIMdp = $modelo->dataProviderIMdp;
			$this->dataProviderEMdp = $modelo->dataProviderEMdp;

			 $this->opera_opera_id = $modelo->opera_opera_id;
			 $this->opera_montototal = $modelo->opera_montototal;
			 $this->opera_entregado = $modelo->opera_entregado;
			 $this->opera_vuelto = $modelo->opera_vuelto;
			 $this->opera_sobrante = $modelo->opera_sobrante;
			 $this->opera_cant = $modelo->opera_cant;
			 $this->opera_ctacte_boleto_recibo = $modelo->opera_ctacte_boleto_recibo;

			 $this->ticket_ctacte_id = $modelo->ticket_ctacte_id;
			 $this->ticket_ticket = $modelo->ticket_ticket;
			 $this->ticket_trib_id = $modelo->ticket_trib_id;
			 $this->ticket_trib_nom = $modelo->ticket_trib_nom;
			 $this->ticket_trib_tipo = $modelo->ticket_trib_tipo;
			 $this->ticket_obj_id = $modelo->ticket_obj_id;
			 $this->ticket_tobj = $modelo->ticket_tobj;
			 $this->ticket_subcta = $modelo->ticket_subcta;
			 $this->ticket_anio = $modelo->ticket_anio;
			 $this->ticket_cuota = $modelo->ticket_cuota;
			 $this->ticket_faci_id = $modelo->ticket_faci_id;
			 $this->ticket_num = $modelo->ticket_num;
			 $this->ticket_num_nom = $modelo->ticket_num_nom;
			 $this->ticket_monto = $modelo->ticket_monto;
			 $this->ticket_fchvenc = $modelo->ticket_fchvenc;
			 $this->ticket_obs = $modelo->ticket_obs;
			 $this->ticket_montoboleto = $modelo->ticket_montoboleto;
			 $this->opera_imdp_id = $modelo->opera_imdp_id;
	 		 $this->opera_emdp_id = $modelo->opera_emdp_id;

 		 	 $this->arqueo_efectivo = $modelo->arqueo_efectivo;
			 $this->arqueo_cheque = $modelo->arqueo_cheque;
			 $this->arqueo_tarjetacredito = $modelo->arqueo_tarjetacredito;
			 $this->arqueo_tarjetadebito = $modelo->arqueo_tarjetadebito;
			 $this->arqueo_deposito = $modelo->arqueo_deposito;
			 $this->arqueo_transferencia = $modelo->arqueo_transferencia;
			 $this->arqueo_notacredito = $modelo->arqueo_notacredito;
			 $this->arqueo_bonos = $modelo->arqueo_bonos;
			 $this->arqueo_haberes = $modelo->arqueo_haberes;
			 $this->arqueo_otros = $modelo->arqueo_otros;
			 $this->arqueo_otrosvalores = $modelo->arqueo_otrosvalores;
			 $this->arqueo_billete1000 = $modelo->arqueo_billete1000;
			 $this->arqueo_billete500 = $modelo->arqueo_billete500;
			 $this->arqueo_billete200 = $modelo->arqueo_billete200;
			 $this->arqueo_billete100 = $modelo->arqueo_billete100;
			 $this->arqueo_billete050 = $modelo->arqueo_billete050;
			 $this->arqueo_billete020 = $modelo->arqueo_billete020;
			 $this->arqueo_billete010 = $modelo->arqueo_billete010;
			 $this->arqueo_billete005 = $modelo->arqueo_billete005;
			 $this->arqueo_billete002 = $modelo->arqueo_billete002;
			 $this->arqueo_moneda100 = $modelo->arqueo_moneda100;
			 $this->arqueo_moneda050 = $modelo->arqueo_moneda050;
			 $this->arqueo_moneda025 = $modelo->arqueo_moneda025;
			 $this->arqueo_moneda010 = $modelo->arqueo_moneda010;
			 $this->arqueo_moneda005 = $modelo->arqueo_moneda005;
			 $this->arqueo_moneda001 = $modelo->arqueo_moneda001;
			 $this->arqueo_recuento = $modelo->arqueo_recuento;
			 $this->arqueo_total_efectivo = $modelo->arqueo_total_efectivo;
			 $this->arqueo_total_otros = $modelo->arqueo_total_otros;
			 $this->arqueo_total_fondo = $modelo->arqueo_total_fondo;
			 $this->arqueo_total_total = $modelo->arqueo_total_total;
			 $this->arqueo_total_sobrante = $modelo->arqueo_total_sobrante;
			 $this->arqueo_cant_retiro = $modelo->arqueo_cant_retiro;

		}
		$session->close();

	}

	public function __destruct()
	{
		$session = new Session;
		$session->open();
		$session['modelCajaCobro'] = urlencode(serialize($this));
		$session->close();
	}

	/**
	 * Función que se encarga de reiniciar los valores del modelo.
	 */
	public function reiniciaModelo()
	{
		$this->caja_estado_estado = $this->caja_estado;	//Por defecto pongo el estado de la Caja en "C => Cerrada".
		$this->caja_posicion = 'A1';
		$this->dataProviderTicket = [];
		$this->dataProviderDetalle = [];
		$this->dataProviderDetalleTemp = [];
		$this->dataProviderItem = [];
		$this->dataProviderItemTemp = [];
		$this->dataProviderIMdp = [];
		$this->dataProviderEMdp = [];

		 $this->opera_opera_id = '';
		 $this->opera_montototal = '';
		 $this->opera_entregado = '';
		 $this->opera_vuelto = '';
		 $this->opera_sobrante = '';
		 $this->opera_cant = '';
		 $this->opera_ctacte_boleto_recibo = '';

		 $this->ticket_ctacte_id = '';
		 $this->ticket_ticket = '';
		 $this->ticket_trib_id = '';
		 $this->ticket_trib_nom = '';
		 $this->ticket_trib_tipo = '';
		 $this->ticket_obj_id = '';
		 $this->ticket_tobj = '';
		 $this->ticket_subcta = '';
		 $this->ticket_anio = '';
		 $this->ticket_cuota = '';
		 $this->ticket_faci_id = '';
		 $this->ticket_num = '';
		 $this->ticket_num_nom = '';
		 $this->ticket_monto = '';
		 $this->ticket_fchvenc = '';
		 $this->ticket_obs = '';
		 $this->ticket_montoboleto = 0;
		 $this->opera_imdp_id = '';
	 	 $this->opera_emdp_id = '';


	 	 $this->reiniciaVariablesArqueo();

		$session = new Session;
		$session->open();
		$session['modelCajaCobro'] = urlencode(serialize($this));
		$session->close();

	}

	public function reiniciaVariablesArqueo()
	{
		 $this->arqueo_efectivo = '0.00';
		 $this->arqueo_cheque = '0.00';
		 $this->arqueo_tarjetacredito = '0.00';
		 $this->arqueo_tarjetadebito = '0.00';
		 $this->arqueo_deposito = 0.0;
		 $this->arqueo_transferencia = '0.00';
		 $this->arqueo_notacredito = '0.00';
		 $this->arqueo_bonos = '0.00';
		 $this->arqueo_haberes = '0.00';
		 $this->arqueo_otros = '0.00';
		 $this->arqueo_otrosvalores = '0.00';
	 	 $this->arqueo_billete1000 = 0;
		 $this->arqueo_billete500 = 0;
		 $this->arqueo_billete200 = 0;
		 $this->arqueo_billete100 = 0;
		 $this->arqueo_billete050 = 0;
		 $this->arqueo_billete020 = 0;
		 $this->arqueo_billete010 = 0;
		 $this->arqueo_billete005 = 0;
		 $this->arqueo_billete002 = 0;
		 $this->arqueo_moneda100 = 0;
		 $this->arqueo_moneda050 = 0;
		 $this->arqueo_moneda025 = 0;
		 $this->arqueo_moneda010 = 0;
		 $this->arqueo_moneda005 = 0;
		 $this->arqueo_moneda001 = 0;
	 	 $this->arqueo_recuento = 0;
		 $this->arqueo_total_efectivo = '0.00';
		 $this->arqueo_total_otros = '0.00';
		 $this->arqueo_total_fondo = '0.00';
		 $this->arqueo_total_total = '0.00';
		 $this->arqueo_total_sobrante = '0.00';
		 $this->arqueo_cant_retiro = '0.00';
	}

	private function limpiarVariableTicket()
	{
		 $this->ticket_ctacte_id = '';
		 $this->ticket_ticket = '';
		 $this->ticket_trib_id = '';
		 $this->ticket_trib_nom = '';
		 $this->ticket_trib_tipo = '';
		 $this->ticket_obj_id = '';
		 $this->ticket_tobj = '';
		 $this->ticket_subcta = '';
		 $this->ticket_anio = '';
		 $this->ticket_cuota = '';
		 $this->ticket_faci_id = '';
		 $this->ticket_num = '';
		 $this->ticket_num_nom = '';
		 $this->ticket_monto = '';
		 $this->ticket_fchvenc = '';
		 $this->ticket_obs = '';
		 $this->ticket_montoboleto = 0;
	}

	/**
	 * Función que se utiliza para obtener los medios de pago habilitados para una caja.
	 * @param integer $caja_id Identificador de la caja.
	 */
	public function getMediosDePagoHabilitados( $caja_id ){

		$sql = 	"SELECT mdp, nombre FROM caja_mdp WHERE habilitado = 1 AND mdp IN ( " .
					"SELECT mdp FROM caja_caja_mdp WHERE caja_id = " . intVal( $caja_id ) . ")";

		try{
			$res = Yii::$app->db->createCommand($sql)->queryAll();

			$arreglo = ArrayHelper::map($res, 'mdp', 'nombre');
			asort($arreglo, SORT_STRING);
		}
		catch(Exception $e){
			$arreglo = [];
		}

		return $arreglo;
    }

	/**
	 * Función que se encarga de gestionar la apertura, reapertura o cierre de una caja y carga las variables de caja.
	 * @param integer $caja_id Identificador de la Caja.
	 * @param string $fecha Fecha de la caja.
	 * @param integer $externa Indica si es una caja interna o externa.
	 * @param string $accion Tipo de acción: A. Apertura - R. Reapertura - S. Sobrantes
	 * @return integer Devuelve 1 si pudo hacer la apertura, 0 si hubo algún error.
	 */
	public function apertura($caja_id,$clave,$fecha,$externa,$accion)
	{

		if ($externa == 0) $externa = 'false';
		if ($externa == 1) $externa = 'true';

		$arreglo = [];

		//Validar la clave ingresada por el usuario
		$result = $this->validarClave($clave);

		if ($result != '')
		{

			$arreglo = ['return' => 0,
						'mensaje' => 'La clave ingresada no es correcta.'];
		} else
		{
			//Validar que la caja sea destino = 0
			$sql = "SELECT EXISTS (SELECT 1 FROM caja WHERE destino = 0 AND caja_id = " . $caja_id . " AND usr_id = " . Yii::$app->user->id . ")";

			if (!Yii::$app->db->createCommand($sql)->queryAll())	//No se puede abrir la caja
			{
				$arreglo = ['return' => 0,
						'mensaje' => 'La caja no se puede abrir. Destino incorrecto'];

			} else
			{
				try
				{

					$sql = "SELECT sam.uf_caja_apertura(" . $caja_id . ",'" .Fecha::usuarioToBD( $fecha ) . "'," . Yii::$app->user->id . "," . $externa . ",'" . $accion . "')";

					Yii::$app->db->createCommand($sql)->execute();

					//Si no se produce ninguna excepción, se retorna un entero (1) con un string vacío ('').
					$arreglo = ['return' => 1,
								'mensaje' => ''];

					//Carga el id de caja y la fecha
					$this->caja_caja_id = $caja_id;
			 		$this->caja_fecha = $fecha;

			 		//Se mdifica el estado de la caja a "A. Abierta" en caso de apertura y reapertura y a "C. Cerrada" en caso de cierre
			 		$this->caja_estado = 'A';

			 		if ($accion == 'C')
			 			$this->caja_estado = 'C';

			 		//Asignar a caja_estado_estado el valor de caja_estado
			 		$this->caja_estado_estado = $this->caja_estado;

			 		//Asignar a caja_posicion la posición 'A1'
			 		$this->caja_posicion = 'A1';

			 		//Modifico el estado de la caja en sesión
			 		$session = new Session;
			 		$session->open();
			 		$session['cajaCobro_estadoCaja'] = $this->caja_estado;
			 		$session['cajaCobro_idCaja'] = $this->caja_caja_id;
			 		$session['cajaCobro_fechaCaja'] = $this->caja_fecha;
			 		$session['cajaCobro_estado_estado'] = $this->caja_estado;
			 		$session->close();

				} catch (\Exception $e)
				{
					//Si se produce un error, se retorna un entero (0) con el mensaje de error producido.
					$arreglo = ['return' => 0,
								'mensaje' => DBException::getMensaje($e)];
				}
			}

		}

		return $arreglo;

	}


	/**
	 * Función que se utiliza para validar la clave del usuario.
	 */
	private function validarClave($clave)
	{
		$user = User::findIdentity(Yii::$app->user->id);

        if (!$user || !$user->validatePassword($clave))
            return 'Clave Incorrecta.';
        else
        	return '';
	}

	/**
	 * Función que realiza el pedido de anulación de un Ticket.
	 * @param integer $ticket_id Nº de Ticket.
	 * @param string $motivo Motivo de la anulación.
	 */
	public function anulaTicket( $ticket_id, $motivo)
	{
		//Validar que existe el Ticket
		$sql = "SELECT EXISTS (SELECT 1 FROM caja_ticket WHERE ticket = " . $ticket_id . " AND est='A')";

		$existe = Yii::$app->db->createCommand( $sql )->queryScalar();

		if ( $existe )
		{
			 try
	        {
	        	$sql = "Select sam.uf_caja_anula_ticket_pedido(" . $ticket_id . "," . Yii::$app->user->id . ",'" . $motivo . "')";

	        	Yii::$app->db->createCommand($sql)->execute();

	        	$arreglo = [
					'return' => 1,
					'mensaje' => '',
				];

	        } catch (\Exception $e)
	        {
	        	//Si se produce un error, se retorna un entero (0) con el mensaje de error producido.
				$arreglo = [
					'return' => 0,
					'mensaje' => DBException::getMensaje($e)
				];
	        }
		} else
		{
			//Si no s eencuentra el ticket, se retorna un entero (0) con el mensaje.
			$arreglo = ['return' => 0,
						'mensaje' => "El Ticket ingresado no existe o ya se encuentra dado de baja."];
		}

		return $arreglo;
	}

	// /**
	//  * Función que realiza el pedido de Anulación de una Operación.
	//  * @param integer $opera_id Nº de Operación.
	//  * @param string $motivo Motivo de la anulación.
	//  */
	public function anulaOperacion($opera_id,$motivo)
	{
		//Validar que existe la Operación
		$sql = "SELECT EXISTS (SELECT 1 FROM caja_opera WHERE opera = " . $opera_id . " AND est='A')";

		$existe = Yii::$app->db->createCommand($sql)->queryScalar();

		if ($existe == 1)
		{
			 try
	        {
	        	$sql = "Select sam.uf_caja_anula_opera_pedido(" . $opera_id . "," . Yii::$app->user->id . ",'" . $motivo . "')";

	        	Yii::$app->db->createCommand($sql)->execute();

	        	$arreglo = ['return' => 1,
					'mensaje' => ''];

				return $arreglo;

	        } catch (\Exception $e)
	        {
	        	//Si se produce un error, se retorna un entero (0) con el mensaje de error producido.
				$arreglo = ['return' => 0,
							'mensaje' => DBException::getMensaje($e)];

				return $arreglo;
	        }
		} else
		{
			//Si no s eencuentra el ticket, se retorna un entero (0) con el mensaje.
			$arreglo = ['return' => 0,
						'mensaje' => "La operación ingresada no existe o ya se encuentra dada de baja."];

			return $arreglo;
		}

       $arreglo = ['return' => 0,
					'mensaje' => 'La operación no se pudo realizar.'];

		return $arreglo;

	}

	/**
	 * Función que crea una nueva Operación.
	 * 		=> Inicializa las variables.
	 */
	public function nuevaOpera()
	{
		//Establece estado = "opera"
		$this->caja_estado_estado = 'P';
		$session = new Session;
 		$session->open();
 		$session['cajaCobro_estado_estado'] = $this->caja_estado_estado;
 		$session->close();

		//Inicialzación de variables de Operación
		$this->opera_opera_id = 0;
		$this->opera_montototal = 0;
		$this->opera_entregado = 0;
		$this->opera_vuelto = 0;
		$this->opera_sobrante = 0;
		$this->opera_cant = 0;
		$this->opera_ctacte_boleto_recibo = 0;
		$this->opera_imdp_id = 0;
	 	$this->opera_emdp_id = 0;

		//Inicialización de DataSets
		$this->dataProviderTicket = [];
		$this->dataProviderDetalle = [];
		$this->dataProviderDetalleTemp = [];
		$this->dataProviderItem = [];
		$this->dataProviderItemTemp = [];
		$this->dataProviderIMdp = [];
		$this->dataProviderEMdp = [];

	}

	/**
	 * Realiza la previa del ticket a partir del código de barra o ctacte_id ingresado.
	 * @param string $ticket Ticket
	 */
	public function cargarTicket($ticket,$redondeo = 0)
	{
		//Obtengo los datos de la CtaCte
		$sql = "SELECT *,to_char(fchvenc,'dd/MM/yyyy') as fchvenc FROM sam.uf_caja('" . $ticket . "',0)";

		//Creo un arreglo que mantendrá los datos
		$data = [];

		try
		{
			$data = Yii::$app->db->createCommand($sql)->queryAll();

		} catch (\Exception $e)
		{
           //Si se produce un error, se retorna un entero (0) con el mensaje de error producido.
			$arreglo = ['return' => 0,
						'mensaje' => DBException::getMensaje($e)];

			return $arreglo;

		}

		//Verificar si se encontraron datos y si el monto recuperado es correcto

		if (count($data) == 0)
		{
			//Si se produce un error, se retorna un entero (0) con el mensaje de error producido.
			$arreglo = ['return' => 0,
						'mensaje' => 'Datos Incorrectos'];

			return $arreglo;
		}

//		//Si el estado de la Caja es "A. Activa" (Indica que no se ha ingresado ningún Ticket hasta el momento),
//		//se genera una nueva operación.
//		if ($this->caja_estado_estado == 'A')
//			$this->nuevaOpera();

		//Verificar si ya fue ingresado
		//Compara lo obtenido de la BD con lo existente en el dataProvider de Ticket

		$re = $this->dataProviderTicket;

		foreach($re as $array)
		{

			if (($data[0]['trib_id'] == 10 and $data[0]['anio'] == $array['anio']) || ($data[0]['faci_id'] > 0 and $data[0]['faci_id'] == $array['faci_id']) || ($data[0]['faci_id'] == 0 and $data[0]['trib_id'] !=10 and $data[0]['ctacte_id'] == $array['ctacte_id']))	//Si el Ticket ya se encuentra ingresado
			{
				//Si se produce un error, se retorna un entero (0) con el mensaje de error producido.
				$arreglo = ['return' => 0,
							'mensaje' => 'El Ticket ya fue ingresado.'];

				return $arreglo;
			}

		}

		//Cargo las propiedades
		$this->ticket_ctacte_id = $data[0]['ctacte_id'];
		$this->ticket_ticket = 0;
		$this->ticket_trib_id = $data[0]['trib_id'];
		$this->ticket_trib_nom = $data[0]['trib_nom'];
		$this->ticket_trib_tipo = 0;
		$this->ticket_obj_id = $data[0]['obj_id'];
		$this->ticket_tobj = utb::getTObjNom($this->ticket_obj_id);
		$this->ticket_subcta = $data[0]['subcta'];
		$this->ticket_anio = $data[0]['anio'];
		$this->ticket_cuota = $data[0]['cuota'];
		$this->ticket_faci_id = $data[0]['faci_id'];
		$this->ticket_num = ($data[0]['num'] == null ? "" : $data[0]['num']);
		$this->ticket_num_nom = utb::getNombObj("'".$this->ticket_num."'");
		$this->ticket_fchvenc = $data[0]['fchvenc'];
		$this->ticket_obs = '';
		//$this->ticket_montoboleto = $data[0][''];

		$anual = $data[0]['anual'];
		$faci = ($this->ticket_faci_id == 0 ? 0 : 1);

		//Verificar en Config que acción seguir para los casos de adhesión a débito.
		//0: No controlar, 1: Controlar e Informar, 2: Controlar y Bloquear cobro.
		$config = utb::samConfig();

		if ($config['cajaverifdebito'] > 0)	//Si hay que controlar, se verifica si existe adhesión para el período
		{
			if ($this->ticket_trib_id != 1)	//Si el tributo no es convenio
			{
				$sql = "SELECT COUNT(*) FROM debito_adhe WHERE trib_id=" . $this->ticket_trib_id . " AND obj_id='" . $this->ticket_obj_id . "' AND " .
						"subcta=" . $this->ticket_subcta . " AND " . (($this->ticket_anio * 1000) + $this->ticket_cuota) . " BETWEEN perdesde AND perhasta AND est='A'";
			}

			else
				$sql = "SELECT COUNT(*) FROM plan p WHERE p.plan_id=" . $this->ticket_anio . " AND p.tpago=3";

			$debitoAdhe = Yii::$app->db->createCommand($sql)->execute() > 0;
		}

		$pagoCta = ($this->ticket_trib_id == 10 ? 1 : 0);

		//$this->caja_fecha = date('d') . '/' . date('m') . '/' . date('Y');//Es solo de pueba. Se debe borrar la línea.

		if ($pagoCta == 1)
		{
			$sql = "Select " . $this->ticket_anio . " ctacte_id,cta_Id,Cta_Nom,tCta,Sum(Monto) as Monto ";
            $sql .= "From sam.Uf_Caja_Det(" . $this->ticket_anio . ",'" . Fecha::usuarioToBD($this->ticket_fchvenc) . "','";
            $sql .= Fecha::usuarioToBD($this->caja_fecha) . "'," . $anual . ",0," . $pagoCta . "," . $faci . ") c ";
            $sql .= "Group By cta_id,cta_nom,tCta ";
            $sql .= "Order By tcta, cta_id";

            $ctacte = $this->ticket_anio;

		} else if ($faci == 1)
		{
			$sql = "Select " . $this->ticket_faci_id . " ctacte_id,cta_Id,Cta_Nom,tCta,sum   (Monto) as Monto ";
            $sql .= "From sam.Uf_Caja_Det(" . $this->ticket_faci_id . ",'" . Fecha::usuarioToBD($this->ticket_fchvenc) . "','";
            $sql .= Fecha::usuarioToBD($this->caja_fecha) . "'," . $anual . ",0," . $pagoCta . "," . $faci . ") c ";
            $sql .= "Group By cta_id,cta_nom,tCta ";
            $sql .= "Order By tcta, cta_id";

            $ctacte = $this->ticket_faci_id;

		} else
		{
			$sql = "Select " . $this->ticket_ctacte_id . " ctacte_id,cta_id,Cta_Nom,tCta,Sum(Monto) as Monto ";
            $sql .= "From sam.Uf_Caja_Det(" . $this->ticket_ctacte_id . ",'" . Fecha::usuarioToBD($this->ticket_fchvenc) . "','";
            $sql .= Fecha::usuarioToBD($this->caja_fecha) . "'," . $anual . ",0," . $pagoCta . "," . $faci . "," . $redondeo .") c ";
            $sql .= "Group By cta_id,cta_nom,tCta ";
            $sql .= "Order By tcta desc, cta_id";

            $ctacte = $this->ticket_ctacte_id;
		}

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		$monto = 0;

		foreach($data as $array)
		{
			$monto += floatval($array['monto']);
		}

		$this->ticket_monto = number_format($monto,2,'.','');	//Asigno monto

		//Cargo el DataProvider con los datos temporales
		$this->dataProviderDetalleTemp = $data;

		if ($this->ticket_monto < 0 && $this->ticket_trib_tipo != 3 && $this->ticket_trib_tipo != 4)
		{
			$arreglo = ['return' => 0,
						'mensaje' => 'Monto de Ticket Negativo.'];

			return $arreglo;
		}

		$arreglo = ['return' => 1,
						'mensaje' => ''];

		return $arreglo;

	}

	/**
	 * Función que se encarga de crear un nuevo Ticket en el DataProvider de Ticket.
	 * A partir de las variables y del DaraProviderDetalle, carga información del Ticket.
	 * Se debe invocar luego de cargarTicket ó Sellado ó ... (cuenta) ...
	 */
	public function nuevoTicket()
	{

//		//Si el estado de la Caja es "A. Activa" (Indica que no se ha ingresado ningún Ticket hasta el momento),
//		//se genera una nueva operación.
//		if ($this->caja_estado_estado == 'A')
//			$this->nuevaOpera(); 	//Es necesario cuando se ingresa recibo/sellado sin un ticket previo

		$data = [
			'ctacte_id' => $this->ticket_ctacte_id,
			'ticket' => $this->ticket_ticket,
			'trib_id' => $this->ticket_trib_id,
			'trib_nom' => $this->ticket_trib_nom,
			'obj_id' => $this->ticket_obj_id,
			'subcta' => $this->ticket_subcta,
			'anio' => $this->ticket_anio,
			'cuota' => $this->ticket_cuota,
			'faci_id' => $this->ticket_faci_id,
			'num' => $this->ticket_num,
			'monto' => $this->ticket_monto,
			'fchvenc' => $this->ticket_fchvenc,
			'externa' => 0,
			'fecha' => $this->caja_fecha,
			'obs' => $this->ticket_obs,
		];

		$this->opera_montototal += floatval($this->ticket_monto);
		$this->opera_montototal = number_format($this->opera_montototal,2,'.','');

		//Obtengo el arreglo actual en el DataProviderTicket
		$arregloTicket = $this->dataProviderTicket;

		//Compruebo que el ticket que quiero ingresar no se encuentre ya ingresado
		foreach($arregloTicket as $a)
			{
				If (($this->ticket_trib_id == 10 and $this->ticket_anio == $a['anio']) || ($this->ticket_faci_id > 0 and $this->ticket_faci_id == $a['faci_id']) || ($this->ticket_faci_id = 0 and $this->ticket_trib_id != 10))
				{
					$arreglo = ['return' => 0,
						'mensaje' => 'El Ticket ya se ingresó con anterioridad.'];

					return $arreglo;
				}
			}

		//$arregloTicket[$this->ticket_ctacte_id] = $data;
		$arregloTicket[] = $data;

		//Cargo el DataProvider con los datos temporales
		$this->dataProviderTicket = $arregloTicket;


		//Inserto en DataProviderDetalle a partir de DataProviderDetalleTemp

		$arregloDetalleTemp = $this->dataProviderDetalleTemp;

		if (count($arregloDetalleTemp) > 0)
		{
			$arregloDetalle = $this->dataProviderDetalle;

			$arregloDetalle = array_merge_recursive($arregloDetalle,$arregloDetalleTemp);

			$this->dataProviderDetalle = $arregloDetalle;

			$this->dataProviderDetalleTemp = [];

		}

		//Inserto en DataProviderItem

		$itemTemp = $this->dataProviderItemTemp;

		if (count($itemTemp) > 0)
		{
			//Le asigno a ItemTemp el ID de ctacte, de manera de poder eliminarlo en caso de ser necesario
			foreach($itemTemp as &$array)
			{
				$array['ctacte_id'] = $this->ticket_ctacte_id;
			}

			$arregloItem = $this->dataProviderItem;

			if (count($arregloItem) > 0)
				$arregloItem = $itemTemp + $arregloItem;
			else
				$arregloItem = $itemTemp;


			$this->dataProviderItem = $arregloItem;

			$this->dataProviderItemTemp = [];

		}

		//Sumo 1 a la cantidad en $this->opera_cant
		$this->opera_cant++;

		//Asignar a caja_posicion la posición 'A2'
 		$this->caja_posicion = 'A2';

		$this->limpiarVariableTicket();

		$arreglo = ['return' => 1,
						'mensaje' => ''];

		return $arreglo;

	}

	/**
	 * Función que se encarga de borrar un Ticket del DataProvider de Ticket.
	 */
	public function borraTicket($id = 0,$faci_id = 0,$anio = 0)
	{
		//Obtengo el arreglo actual en el DataProviderTicket
		$arregloTicket = $this->dataProviderTicket;

		//return $arregloTicket;
		if ($id != 0 or $faci_id != 0 or $anio != 0)
		{
		   foreach ($arregloTicket as $clave=>$value)
		   {
				if ($id != 0 && $value['ctacte_id'] == $id) {
					$array = $arregloTicket[$clave];
					unset($arregloTicket[$clave]);
					break;
				}else if ($faci_id != 0 && $value['faci_id'] == $faci_id) {
					$array = $arregloTicket[$clave];
					unset($arregloTicket[$clave]);
					break;
				}else if ($anio != 0 && $value['anio'] == $anio){
					$array = $arregloTicket[$clave];
					unset($arregloTicket[$clave]);
					break;
				}
		   }

			//Obtengo el Ticket que será eliminado
			//$array = $arregloTicket[$id];

			//Elimino el Ticket
			//unset($arregloTicket[$id]);

			//Descuento de monto total
			$this->opera_montototal = number_format($this->opera_montototal - floatval($array['monto']),2,'.','');
		}

		$this->dataProviderTicket = $arregloTicket;

		//INICIO Eliminar Datos del ticket de DataProviderDetalle
		$arregloDetalle = $this->dataProviderDetalle;


		if ($id > 0)	//Si se quiere eliminar un ticket
		{
			$arregloDetalleAux = [];

			foreach ($arregloDetalle as $array)
			{
				if ($array['ctacte_id'] != $id)
					$arregloDetalleAux[] = $array;
			}

			$this->dataProviderDetalle = $arregloDetalleAux;

		} else //Si se quiere eliminar un boleto o sellado
		{

			/*
			 * Para eliminar los datos de un "Boleto" o "Sellado" del dataProviderDetalle, se debe:
			 * 		-> obtener de dataProviderItem el/los ítem/s que contenga/n $id.
			 * 		-> obtener el tipo de cuenta de el/los ítem/s.
			 * 		-> restar el monto de ítem del dataProviderDetalle cuta cta_id = el tipo de cuenta del ítem.
			 * 		-> Si el monto en el dataProviderDetalle == 0, eliminar ese elemento.
			 *
			 * A la hora de guardar los datos, guardar los detalles que tengan monto mayor a 0.
			 */

			 $arregloitem = $this->dataProviderItem;

			 $arregloitemaux = [];

			 //Obtengo del dataProviderItem los ítems que contengan el $id ingresado como parámetro
			 foreach ($arregloitem as $array)
			 {
			 	if ($array['ctacte_id'] == $id)
			 		$arregloitemaux[] = $array;
			 }

			 //Obtengo el dataProviderDetalle
		 	$arregloDetalle = $this->dataProviderDetalle;

			 //Por cada elemento en $arregloitemaux, obtener el tipo de cuenta, restar de dataProviderDetalle y verificar monto de dataProviderDetalle
			 foreach ($arregloitemaux as $array)
			 {
			 	$cta_id = Yii::$app->db->createCommand("SELECT cta_id FROM Item WHERE item_id=" . $array['item_id'])->queryScalar();



			 	//Recorrer dataProviderDetalle y restar $array['monto'] cuando $cta_id == dataProviderDetalle['cta_id']
			 	foreach ($arregloDetalle as &$arrayDetalle)
			 	{
			 		if ($arrayDetalle['cta_id'] == $cta_id)
			 		{
			 			$arrayDetalle['monto'] -= number_format(floatval($array['monto']),2,'.','');

			 			$arrayDetalle['monto'] = number_format(floatval($arrayDetalle['monto']),2,'.','');
//			 			if (intval($arrayDetalle['monto']) == 0)
//			 			{
//			 				unset($this->dataProviderDetalle[''])
//			 			}

			 		}
			 	}

			 }

			 $this->dataProviderDetalle = $arregloDetalle;

		}
		//FIN Eliminar Datos del ticket de DataProviderDetalle

		//INICIO Eliminar Datos del ticket de DataProviderItem
		$arregloItem = $this->dataProviderItem;
		$arregloItemAux = [];

		foreach ($arregloItem as $array)
		{
			if ($array['ctacte_id'] != $id)
				$arregloItemAux[$array['item_id']] = $array;
		}

		//Resto 1 a la cantidad en $this->opera_cant
		$this->opera_cant--;

		$this->dataProviderItem = $arregloItemAux;
		//FIN Eliminar Datos del ticket de DataProviderItem
	}

	/**
	 * Función que se encarga de calcular el monto de un ítem de boleto, a partir de la cantidad.
	 * @param integer $item_id Nº de Ítem.
	 * @param integer $param1
	 * @param integer $param2
	 * @param integer $param3
	 * @param integer $param4
	 */
	public function calculaItem($item_id, $param1 = 0, $param2 = 0, $param3 = 0, $param4 = 0)
	{
        $sql = 	"Select sam.uf_Item_Calcular (" . $item_id . "," . Fecha::getAnio( Fecha::usuarioToBD( $this->caja_fecha ) ) . ","
        		. Fecha::getMes( Fecha::usuarioToBD( $this->caja_fecha ) ) . "," . $param1 . "," . $param2 . "," . $param3 . "," . $param4 . ")";

        $total = Yii::$app->db->createCommand($sql)->queryScalar();

        return $total;
	}

	/**
	 * Función que se encarga de agregar un Ítem en el dataProvider de Ítem Temporal.
	 * @param integer $trib_id Nº de Tributo.
	 * @param integer $item_id Nº de Ítem.
	 * @param integer $cant Cantidad.
	 * @param double $monto Monto.
	 */
	public function agregoItem($trib_id,$item_id,$cant,$monto)
	{
		//Se recupera el período actual en función del tributo y periodicidad.
		$peractual = utb::peractual($trib_id);

		//Se recuperan los valores según vigencia actual
        $sql = "Select i.Nombre, v.Monto as pu ";
        $sql .= "From Item i Inner Join Item_Vigencia v On i.Item_Id = v.Item_Id ";
        $sql .= "Where i.Item_Id = " . $item_id . " and " . $peractual . " between v.PerDesde and v.PerHasta";

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		if (count($data) == 0)
		{
			$arreglo = ['return' => 0,
						'mensaje' => 'Error interno con Ítem.'];

			return $arreglo;
		}

		//INICIO Validar que el boleto que se quiere ingresar no se encuentre en el dataProviderItemTemp
		//ni en el dataProviderItem.

		//Se obtiene el arreglo de Ítems Temporal y de Ítems.
		$arregloitemtemp = $this->dataProviderItemTemp;
		$arregloitem = $this->dataProviderItem;

		if (count($arregloitemtemp) > 0)	//Comprobar que el nuevo elemento no exista en el data Provider de ítem
		{
			foreach ($arregloitemtemp as $array)
			{
				if ($array['item_id'] == $item_id)
				{
					$arreglo = [
						'return' => 0,
						'mensaje' => 'El Ítem ya fue ingresado.'];

					return $arreglo;
				}
			}
		}

		if (count($arregloitem) > 0)	//Comprobar que el nuevo elemento no exista en el data Provider de ítem
		{
			foreach ($arregloitem as $array)
			{
				if ($array['item_id'] == $item_id)
				{
					$arreglo = [
						'return' => 0,
						'mensaje' => 'El Ítem ya fue ingresado.'];

					return $arreglo;
				}
			}
		}
		//FIN Validar que el boleto que se quiere ingresar no se encuentre en los dataProvider de Ítem.

		//Se crea el nuevo elemento para el Data Provider
		$nuevoArreglo[$item_id] = [
			'item_id' => $item_id,
			'item_nom' => $data[0]['nombre'],
			'cant' => $cant,
			'pu' => number_format($data[0]['pu'],2,'.',''),
			'monto' => number_format($monto,2,'.',''),
		];

		//Sumo el total del monto de boleto
		$this->ticket_montoboleto +=  number_format($monto,2,'.','');
		$this->ticket_montoboleto = number_format($this->ticket_montoboleto,2,'.','');

		//Agrego el nuevo elemento al Data Provider temporal de ítem.
		if (count($arregloitemtemp) > 0)
		{
			$arregloitemtemp = $arregloitemtemp + $nuevoArreglo;
		} else
		{
			$arregloitemtemp = $nuevoArreglo;
		}

		$this->dataProviderItemTemp = $arregloitemtemp;

		$arreglo = ['return' => 1,
						'mensaje' => ''];

		return $arreglo;

	}

	/**
	 * Función que se encarga de eliminar un Ítem del dataProvider de Ítem.
	 * @param integer $item_id Identificador de Ítem.
	 */
	public function quitoItem($item_id)
	{

		$arregloItem = $this->dataProviderItemTemp;

		$encontro = 0;

		foreach ($arregloItem as $array)
		{
			//Si el ID ingresado se corresponde con el ID de algún elemento del dataProvider
			if ($array['item_id'] == $item_id)
			{
				//Descuento el monto del elemento del monto Total.
				$this->ticket_montoboleto -= floatval($array['monto']);

				$encontro = 1;
			}
		}

		if ($encontro == 1)
		{
			//Elimino el elemento
			unset($arregloItem[$item_id]);
		}

		//Asigno el arreglo resultante al dataProviderItemTemp
		$this->dataProviderItemTemp = $arregloItem;

	}

	/**
	 * Función que agrega un Sello como Ticket.
	 * @param integer $trib_id Nº de Tributo.
	 * @param integer $item_id Nº de Ítem.
	 * @param double $monto Monto del Sello.
	 * @param string $obs Observaciones.
	 */
	public function nuevoSellado($trib_id,$item_id,$monto,$obs = '')
	{

		if ($item_id != 0)
		{
			$this->dataProviderItemTemp = [];
			$this->agregoItem($trib_id,$item_id,0,$monto);
		}

		//Los ID de sellado son números negativos secuenciales
		$this->opera_ctacte_boleto_recibo -= 1;
		$this->ticket_ctacte_id = $this->opera_ctacte_boleto_recibo;
		$this->ticket_trib_id = $trib_id;

		$this->ticket_trib_nom = Yii::$app->db->createCommand("SELECT nombre_redu FROM trib WHERE trib_id=" . $trib_id)->queryScalar();
		$this->ticket_obj_id = '';
		$this->ticket_subcta = 0;
		$this->ticket_anio = date('Y',strtotime($this->caja_fecha));
		$this->ticket_cuota = 0;
		$this->ticket_num = "";
		$this->ticket_faci_id = 0;
		$this->ticket_num_nom = '';
		$this->ticket_monto = $monto;
		$this->ticket_obs = $obs;
		$this->ticket_fchvenc = $this->caja_fecha;

		//Obtener dataProviderItemTemp
		$arregloItemTemp = $this->dataProviderItemTemp;

		//Obtener dataProviderDetalle
		$arregloDetalle = $this->dataProviderDetalle;

		//Se recorre dataProviderItemTemp y se insertan los datos en dataProviderDetalleTemp
		foreach ($arregloItemTemp as $array)
		{
			$item_id = $array['item_id'];
			$cta_id = Yii::$app->db->createCommand("SELECT cta_id FROM Item WHERE item_id=" . $item_id)->queryScalar();

			$existe = 0; //Variable que se utilizará para saber si la cuenta existía en el DataProvider detalle

			//Corrobar existencia de cuenta. Si existe, se agrega el monto; de lo contrario, se genera.
			if (count($arregloDetalle) > 0)
			{
				foreach ($arregloDetalle as &$arrayDetalle)
				{
					if ($arrayDetalle['cta_id'] == $cta_id)
					{
						$arrayDetalle['monto'] += number_format(floatval($array['monto']),2,'.','');
						$arrayDetalle['monto'] = number_format(floatval($arrayDetalle['monto']),2,'.','');
						$existe = 1;
					}

				}
			}

			if ($existe == 0)	//Si no existía en el DataProviderDetalle
			{
				$data = [
					'ctacte_id' => $this->ticket_ctacte_id,
					'cta_id' => $cta_id,
					'cta_nom' => Yii::$app->db->createCommand("SELECT nombre_redu FROM cuenta WHERE cta_id = ". $cta_id)->queryScalar(),
					'tcta' => Yii::$app->db->createCommand("SELECT tcta FROM cuenta WHERE cta_id = ". $cta_id)->queryScalar(),
					'monto' => number_format(floatval($array['monto']),2,'.',''),
				];

				$arregloDetalle[] = $data;
			}

		}

		$this->dataProviderDetalle = $arregloDetalle;
		$this->ticket_montoboleto = 0;
	}

    /**
     * Función que devuelve un arreglo con los valores para los parámetros de sellado
     */
    public function arregloSellado($item_id)
    {

		$sql = 'SELECT paramnombre1 as par1, paramnombre2 as par2, paramnombre3 as par3, paramnombre4 as par4 FROM item_vigencia WHERE item_id = ' . $item_id;

    	$data = Yii :: $app->db->createCommand($sql)->queryAll();

    	return $data[0];

    }

    /**
     * Función que se utiliza para obtener el listado de cheques cartera
     * @param integer $plan Id de plan
     */
    public function getChequeCartera( $plan )
    {
    	$sql = "select * from v_caja_cheque_cartera WHERE est = 'C' AND plan_id = " . $plan;
        $sql .= " Order by cart_id";

    	$data = Yii :: $app->db->createCommand( $sql )->queryAll();

    	return $data;
    }

    /**
     * Función que comprueba si un medio de pago es especial o no
     * @param integer $mdp Medio de Pago
     * @return integer 0. Medio de pago común - 1. Medio de Pago especial
     */
    public function getTipoMDP($mdp)
    {
    	$data = Yii::$app->db->createCommand("Select tipo From Caja_Mdp Where mdp = " . $mdp)->queryScalar();

    	return $data;

    }

    /**
     * Función que agrega un "Medio de Pago" en el dtaProvider correspondiente.
     * @param integer $tipo. 1. IMDP --- 2. EMDP
     * @param integer $mdp Código del Medio de Pago
     * @param integer $cant Cantidad
     */
    public function agregoMdp($tipo,$mdp,$cant,$comprobante = '')
    {

    	$data = Yii::$app->db->createCommand("Select * From Caja_Mdp Where mdp = " . $mdp)->queryAll();


    	if (count($data) > 0)
    	{
    		$arreglomdp = $data[0];

    		if ($arreglomdp['tipo'] != 'EF' && $arreglomdp['tipo'] != 'BO' && $arreglomdp['tipo'] != 'CA')
    		{
    			$arreglo = [
	    			'return' => 0,
	    			'mensaje' => 'El medio de pago es especial.',
	    		];

				return $arreglo;
    		}
    	} else
    	{
    		$arreglo = [
    			'return' => 0,
    			'mensaje' => 'No se encontraron datos',
    		];

    		return $arreglo;

    	}

    	$arreglomdp = $data[0];

    	//Preparo el arreglo.
    	//Le asigno un ID que permitirá eliminar el arreglo
    	$array = [
    		'id' => ($tipo == 1 ? $this->opera_imdp_id++ +1  : $this->opera_emdp_id++ + 1),
    		'mdp' => $mdp,
    		'nombre' => $arreglomdp['nombre'],
    		'cant' => $cant,
    		'cotiza' => floatval($arreglomdp['cotiza']),
    		'monto' => number_format(floatval($cant * $arreglomdp['cotiza']),2,'.',''),
    		'comprob' => $comprobante,
    		'bcoent' => 0,
    		'bcosuc' => 0,
    		'bcocta' => '',
    		'bconom' => '',
    		'titular' => '',
    		'tcta' => 0,
    		'fchcobro' => $this->caja_fecha,
    	];

    	if ($tipo == 1)	//Ingreso Medio de Pago
    	{
    		$this->dataProviderIMdp[$array['id']] = $array;
    		$this->opera_entregado += number_format(floatval($array['monto']),2,'.','');
    		$this->opera_entregado = number_format(floatval($this->opera_entregado),2,'.','');
    	}
    	else if ($tipo == 2) //Egreso Medio de Pago
    	{

    		//Validar que el monto no sea mayor al sobrante
    		if ($array['monto'] > $this->opera_sobrante)
    		{
    			$arreglo = [
	    			'return' => 0,
	    			'mensaje' => 'El monto ingresado es mayor al sobrante disponible.',
	    		];

				return $arreglo;
    		}

    		$this->dataProviderEMdp[$array['id']] = $array;
    		$this->opera_vuelto += number_format(floatval($array['monto']),2,'.','');
    		$this->opera_vuelto = number_format(floatval($this->opera_vuelto),2,'.','');
    	}

    	//Calculo sobrante
    	$this->opera_sobrante = ($this->opera_entregado - $this->opera_montototal) - $this->opera_vuelto;
    	$this->opera_sobrante = number_format(floatval($this->opera_sobrante),2,'.','');

    	$arreglo = [
    			'return' => 1,
    			'mensaje' => '',
    		];

		return $arreglo;
    }

    /**
     * Función que agrega un "Medio de Pago Especial" en el dtaProvider correspondiente.
     * Se calcula a partir de la cotización.
     * Se considera especial a los tipos distintos de EF (Efectivo), BO (Bono) o CA (Cheque Cartera)
     * @param integer $tipo. 1. IMDP --- 2. EMDP
     * @param integer $mdp Código del Medio de Pago
     * @param integer $cant Cantidad
     * @param integer $comprob Nº de comprobante
     * @param integer $bco_num, Código de Entidad Bancaria
     * @param integer $bco_suc Código de Sucursal Bancaria
     * @param integer $bco_cta Número Cuenta
     * @param string $titular Titular de la Cuenta Bancaria
     * @param integer $tcta Tipo de la Cuenta Bancaria
     * @param string $fchcobro Fecha de cobro (en caso de cheque)
     *
     */
    public function agregoMDPEspecial($tipo,$mdp,$cant,$comprob,$bco_num,$bco_suc = 1,$bco_cta,$titular,$tcta,$fchcobro)
    {
    	$bco_nom = utb::getCampo('banco','bco_ent = ' . $bco_num . ' AND bco_suc = ' . $bco_suc,'nombre');

    	$data = Yii::$app->db->createCommand("Select * From Caja_Mdp Where mdp = " . $mdp)->queryAll();

    	$arreglomdp = $data[0];

    	if ($fchcobro == '')
    		$fchcobro = $this->caja_fecha;

    	//Preparo el arreglo.
    	//Le asigno un ID que permitirá eliminar el arreglo
    	$array = [
    		'id' => ($tipo == 1 ? $this->opera_imdp_id++ +1  : $this->opera_emdp_id++ + 1),
    		'mdp' => $mdp,
    		'nombre' => $arreglomdp['nombre'],
    		'cant' =>  number_format($cant,2,'.',''),
    		'cotiza' => floatval($arreglomdp['cotiza']),
    		'monto' => number_format(floatval($cant * $arreglomdp['cotiza']),2,'.',''),
    		'comprob' => $comprob,
    		'bcoent' => $bco_num,
    		'bcosuc' => $bco_suc,
    		'bcocta' => $bco_cta,
    		'bconom' => $bco_nom,
    		'titular' => $titular,
    		'tcta' => $tcta,
    		'fchcobro' => $fchcobro,
    	];

    	if ($tipo == 1)	//Ingreso Medio de Pago
    	{
    		$this->dataProviderIMdp[$array['id']] = $array;
    		$this->opera_entregado += number_format(floatval($array['monto']),2,'.','');
    		$this->opera_entregado = number_format(floatval($this->opera_entregado),2,'.','');
    	}
    	else if ($tipo == 2) //Egreso Medio de Pago
    	{

    		//Validar que el monto no sea mayor al sobrante
    		if ($array['monto'] > $this->opera_sobrante)
    		{
    			$arreglo = [
	    			'return' => 0,
	    			'mensaje' => 'El monto ingresado es mayor al sobrante disponible.',
	    		];

				return $arreglo;
    		}

    		$this->dataProviderEMdp[$array['id']] = $array;
    		$this->opera_vuelto += number_format(floatval($array['monto']),2,'.','');
    		$this->opera_vuelto = number_format(floatval($this->opera_vuelto),2,'.','');
    	}

    	//Calculo sobrante
    	$this->opera_sobrante = ($this->opera_entregado - $this->opera_montototal) - $this->opera_vuelto;
    	$this->opera_sobrante = number_format(floatval($this->opera_sobrante),2,'.','');

    	$arreglo = [
    			'return' => 1,
    			'mensaje' => '',
    		];

		return $arreglo;

    }

    /**
     * Función que elimina un medio de pago.
     * @param integer $tipo. 1. IMDP --- 2. EMDP
     * @param integer $id Id del arreglo a eliminar.
     */
    public function eliminaMDP($tipo,$id)
    {
    	if ($tipo == 1)
    	{
    		$arregloimdp = $this->dataProviderIMdp;

    		$this->opera_entregado -= number_format(floatval($arregloimdp[$id]['monto']),2,'.','');
    		$this->opera_entregado = number_format(floatval($this->opera_entregado),2,'.','');

    		unset($arregloimdp[$id]);

    		$this->dataProviderIMdp = $arregloimdp;

    	} else if ($tipo == 2)
    	{
    		$arregloemdp = $this->dataProviderEMdp;

    		$this->opera_vuelto -= number_format(floatval($arregloemdp[$id]['monto']),2,'.','');
    		$this->opera_vuelto = number_format(floatval($this->opera_vuelto),2,'.','');

    		unset($arregloemdp[$id]);

    		$this->dataProviderEMdp = $arregloemdp;
    	}

    	//Calculo sobrante
    	$this->opera_sobrante = ($this->opera_entregado - $this->opera_montototal) - $this->opera_vuelto;
    	$this->opera_sobrante = number_format(floatval($this->opera_sobrante),2,'.','');

    	$arreglo = [
    			'return' => 1,
    			'mensaje' => '',
    		];

		return $arreglo;
    }

    /**
     * Función que se encarga de validar que el monto de IMDP sea mayor al total del cobro
     */
    public function validarIMdp()
    {
    	$arregloImdp = $this->dataProviderIMdp;

    	//Validar que se hayan ingresado medios de pago.
    	if (count($arregloImdp) == 0)
    	{
    		$arreglo = [
    			'return' => 0,
    			'mensaje' => 'Ingrese medios de pago.',
    		];

			return $arreglo;
    	}

    	//Validar que la suma de los medios de pago ingresados sea mayor al costo de la operación.
    	if (floatval($this->opera_montototal) > floatval($this->opera_entregado))
    	{
    		$arreglo = [
    			'return' => 0,
    			'mensaje' => 'El total ingresado es menor al costo de la operación.',
    		];

			return $arreglo;
    	}

    	$arreglo = [
    			'return' => 1,
    			'mensaje' => '',
    		];

		return $arreglo;
    }

    /**
     * Obtiene el Id de Operación a usar a partir de la secuencia.
     * @return Nº de Opera Nuevo.
     */
    private function getOpera()
    {
    	$opera_id = Yii::$app->db->createCommand("Select nextval('seq_caja_opera')")->queryScalar();

    	return $opera_id;
    }

    /**
     * Obtiene el siguiente id de objeto
     */
    private function getObjetoSellado($trib_id)
	{
		//Obtengo el número de Objeto - Correlativo x Año (Sellados y Boleto)
		$obj_id = Yii::$app->db->createCommand("Select coalesce(max(Obj_Id),'_0000000') From Caja_Ticket Where Trib_Id=" . $trib_id . " and Anio=" . date('Y'))->queryScalar();
        $obj_id = intval($obj_id) + 1;
        $objeto = "_" . sprintf("%'.07d\n", $obj_id);

		return (string)$objeto;
	}

	/**
	 * Función que Obtiene el número de ticket a usar.
	 * @return Nº de Ticket nuevo
	 */
    private function getTicket()
	{
		$ticket = Yii::$app->db->createCommand("Select nextval('seq_caja_ticket')")->queryScalar();

		return $ticket;
	}

    /**
     * Función que acepta la operación completa.
     * Guarda el Ticket, el Detalle, los Ítems y los MDP.
     * Además llama a un proceso de BD para que genere el detalle de ctacte y realice acciones especiales.
     * Al final inicializa una nueva operación.
     * @param integer $lote_id Caja Externa: Nº de Lote
     * @param string $fchrecep Caja Externa: Fecha de recepción de comprobantes
     * @param double montoIngresado Caja Exeterna: Monto ingresado por el agente externo
     */
    public function aceptarOperacion($lote_id = 0,$fchrecep = 'null',$montoIngresado = 0)
    {

    	//Inicializo variables
    	$comision = 0;
    	$comision_bco = 0;
    	$deposito = 0;
    	$fchproc = 'null';
    	$cantlote = 0;

    	//Valido si lo entregado coincide con el vuelto
    	if ($this->opera_entregado - $this->opera_vuelto != $this->opera_montototal)
    	{
    		$arreglo = [
    			'return' => 0,
    			'mensaje' => 'Los Montos Recibidos, Vuelto y Monto Total no se corresponden.',
    		];

			return $arreglo;
    	}

    	$transaction = Yii::$app->db->beginTransaction();

    	try
    	{
    		//Genero un nuevo ID de Operación.
	    	if ($this->opera_opera_id == 0 || $this->opera_opera_id == '')
	    		$this->opera_opera_id = $this->getOpera();

	    	//Elimino la operación
	    	$sql =	"Delete from Caja_Opera where Opera = " . $this->opera_opera_id;
	    	Yii::$app->db->createCommand($sql)->execute();

	    	//Guardo la operación
		    $sql = "Insert into caja_opera values ( ";
	        $sql .= $this->opera_opera_id . "," . $this->caja_caja_id . ",'" . Fecha::usuarioToBD($this->caja_fecha) . "','" . $lote_id . "',";
	        $sql .= intval($this->opera_cant) . "," . floatval($this->opera_montototal) . "," . $montoIngresado . ",";
	        $sql .= ($comision + $comision_bco) . "," . $deposito . "," . $fchrecep . "," . $fchproc;
	        $sql .= ",0,'A',current_timestamp," . Yii::$app->user->id . "," . $cantlote . ")";

	        $count = Yii::$app->db->createCommand($sql)->execute();

	        //Guardo los tickets, el detalle y los ítems
	        $arregloticket = $this->dataProviderTicket;

	        foreach ($arregloticket as &$array)
	        {
	        	$trib_id = $array['trib_id'];

	        	if ($array['obj_id'] == '')
	        		$array['obj_id'] = trim($this->getObjetoSellado($trib_id));

	        	$ctacte_id = ($array['ctacte_id'] < 0 ? 0 : $array['ctacte_id']);

	        	//Verifico que el Ticket no esté ingresado
	        	$sql = "Select count(*) from Caja_Ticket where Opera = " . $this->opera_opera_id . " and CtaCte_Id=" . $ctacte_id;

	        	$count = Yii::$app->db->createCommand($sql)->queryScalar();

	        	//Si no se ingresó el Ticket
	        	if ($count == 0)
	        	{
	        		$ticket = $this->getTicket();

	        		//Si es anual inserto la emision en CtaCte
	        		if ($array['cuota'] == 0 && $ctacte_id > 0 && $trib_id != 1 && $trib_id != 2 && $trib_id != 10 && $trib_id != 12)
	        		{
	        			$sql = "Select sam.Uf_CtaCte_Anual(" . $ctacte_id . "," . Yii::$app->user->id . ",'" . Fecha::usuarioToBD($this->caja_fecha) . "'," . $this->caja_caja_id . ")";
	        			$ctacte_id = Yii::$app->db->createCommand($sql)->queryScalar();
	        		}

	        		$sql = "Insert Into Caja_Ticket Values (" . $ticket . "," . $this->opera_opera_id . ",";
	                $sql .= $this->caja_caja_id . ",'" . Fecha::usuarioToBD($array['fecha']) . "',current_time," . $ctacte_id . "," . $trib_id . ",'";
	                $sql .= trim($array['obj_id']) . "'," . $array['subcta'] . "," . $array['anio'] . "," . $array['cuota'] . "," . $array['faci_id'] . ",'" . $array['num'] . "',";
	                $sql .= floatval($array['monto']) . ",0,'A','" . $array['obs'] . "',current_timestamp," . Yii::$app->user->id . ");";

	                Yii::$app->db->createCommand($sql)->execute();

	                //Inserto los ítems
	                $arregloitem = $this->dataProviderItem;

	                foreach ($arregloitem as $arrayitem)
	                {
	                	if (($array['trib_id'] == 10 and $array['anio'] == $arrayitem['ctacte_id']) || ($array['faci_id'] > 0 and $array['faci_id'] == $arrayitem['ctacte_id']) || ($array['ctacte_id'] == $arrayitem['ctacte_id']))
	                	{
	                		$sql = "Insert Into Caja_Ticket_Item Values (" . $ticket . "," . $arrayitem['item_id'] . ",";
	                        $sql .= intval($arrayitem['cant']) . "," . floatval($arrayitem['monto']) . ")";

	                        Yii::$app->db->createCommand($sql)->execute();
	                	}
	                }

	                //Inserto el detalle
	                $arreglodetalle = $this->dataProviderDetalle;

	                foreach ($arreglodetalle as $arraydetalle)
	                {
	                	if (($array['trib_id'] == 10 and $array['anio'] == $arraydetalle['ctacte_id']) || ($array['faci_id'] > 0 and $array['faci_id'] == $arraydetalle['ctacte_id']) || ($array['ctacte_id'] == $arraydetalle['ctacte_id']))
	                	{
	                		//Si ctacte_id < 0, verificar que monto > 0
	                		if ($arraydetalle['ctacte_id'] > 0 || ($arraydetalle['ctacte_id'] < 0 && $arraydetalle['monto'] > 0))
	                		{
	                			$sql = "Insert Into Caja_Ticket_Det Values (" . $ticket . "," . $arraydetalle['cta_id'] . ",";

								switch ($arraydetalle['tcta'])
								{
									case 1:
									case 3:
									case 4:	//Nominal, Interés, Multa

										$sql .= floatval($arraydetalle['monto']) . ")";

										break;

									case 2:

										if ($arraydetalle['monto'] < 0)
											$sql .= floatval($arraydetalle['monto']) . ")";
										else
											$sql .= - floatval($arraydetalle['monto']) . ")";

										break;
								}

								Yii::$app->db->createCommand($sql)->execute();
	                		}
	                	}
	                }

	        	}
	        }

	        //Acepto Operación (Genero ctacte_det y realizo operaciones especiales)
	        Yii::$app->db->createCommand("Select sam.uf_caja_opera_acepta (" . $this->opera_opera_id . ")")->execute();

	        //Grabo los medios de pago
	        $this->grabaMDP();

	         /**
	         * 'Actualizo el estado del cheque en cartera si corresponde
	        If mCart_id <> 0 Then
	            $sql = "Update caja_cheque_cartera set est='P' where cart_id=" . mCart_id
	            CnnExeProc($sql)
	        End If
	         */

    	}
    	catch (\Exception $e)
    	{
    		$transaction->rollback();

    		//Si se produce un error, se retorna un entero (0) con el mensaje de error producido.
			$arreglo = ['return' => 0,
						'mensaje' => DBException::getMensaje($e)];

			return $arreglo;
    	}

    	$transaction->commit();

    	//Genero una nueva operación
    	$this->nuevaOpera();

		$arreglo = [
    			'return' => 1,
    			'mensaje' => '',
    		];

		return $arreglo;

    }

    /**
     * Gurada los MDP de ingreso y egreso
     */
    private function grabaMDP()
    {
    	$cant = 0;
    	$monto = 0;

    	//INGRESO de Medio de Pago
    	$arregloimdp = $this->dataProviderIMdp;

    	foreach ($arregloimdp as $array)
    	{
    		$sql = "Insert Into Caja_Opera_Mdp (Opera, Mdp, Cant, Cotiza, Monto, Comprob, Bco_Ent, Bco_Suc, Bco_Cta, Titular, tcta, fchcobro, FchMod, UsrMod) ";
            $sql .= "Values (" . $this->opera_opera_id . "," . $array['mdp'] . ",";
            $sql .= intval($array['cant']) . "," . floatval($array['cotiza']) . ",";
            $sql .= floatval($array['monto']) . ",'" . $array['comprob'] . "',";
            $sql .= $array['bcoent'] . "," . $array['bcosuc'] . ",'" . $array['bcocta'] . "','";
            $sql .= $array['titular'] . "'," . intVal( $array['tcta'] ) . ",'" . $array['fchcobro'] . "','";
            $sql .= Fecha::usuarioToBD( $this->caja_fecha ) . "'," . Yii::$app->user->id . ")";

            Yii::$app->db->createCommand($sql)->execute();
    	}

    	//EGRESO de Medio de Pago
    	$arregloemdp = $this->dataProviderEMdp;

    	foreach ($arregloemdp as $array)
    	{
    		$cant -= $array['cant'];
    		$monto -= $array['monto'];

    		$sql = "Insert Into Caja_Opera_Mdp (Opera, Mdp, Cant, Cotiza, Monto, Comprob, Bco_Ent, Bco_Suc, Bco_Cta, Titular, tcta, fchcobro, FchMod, UsrMod) ";
            $sql .= "Values (" . $this->opera_opera_id . "," . $array['mdp'] . ",";
            $sql .= intval($cant) . "," . floatval($array['cotiza']) . ",";
            $sql .= floatval($monto) . ",'" . $array['comprob'] . "',";
            $sql .= $array['bcoent'] . "," . $array['bcosuc'] . ",'" . $array['bcocta'] . "','";
            $sql .= $array['titular'] . "'," . $array['tcta'] . ",'" . $array['fchcobro'] . "','";
            $sql .= Fecha::usuarioToBD($this->caja_fecha) . "'," . Yii::$app->user->id . ")";

            Yii::$app->db->createCommand($sql)->execute();
    	}
    }

    /**
     * Función que genera un listado con los medios de pago especiales que se
     * utilizaron en una caja en una fecha determinada.
     * @param integer $caja_id Identificador de la caja.
     * @param string $fecha Fecha que se desea buscar.
     * @return dataProvider Resultado de la búsqueda.
     */
    function getMDPE($caja_id = 0,$fecha = '')
    {
    	$dataProvider = new ArrayDataProvider(['allModels' => []]);

    	if ($caja_id != 0 && $fecha != '')
    	{
    		$sql = "Select opera, mdp, mdp_nom, mdp_tipo as tipo, comprob, bco_ent_nom as banco, titular, to_char(fchcobro,'dd/MM/yyyy') as fchcobro, cant, cotiza, monto " .
    				" From V_Caja_Opera_Mdp " .
    				" Where Caja_id = " . $caja_id . " and Fecha = " . Fecha::usuarioToBD( $fecha, 1 ) . " and mdp_tipo<>'EF' and Est='A' " .
					" Order By Mdp";

			$data = Yii::$app->db->createCommand($sql)->queryAll();

    		$dataProvider = new ArrayDataProvider(['allModels' => $data]);
    	}

    	return $dataProvider;
    }

	/**
	 * Función que obtiene un arreglo con lo cobrado en una caja y fecha determinado.
	 * @param integer $caja_id Identificador de la caja.
	 * @param string $fecha Fecha que se desea buscar.
	 */
	public function getArregloMDPCobrado($caja_id,$fecha)
	{
        $sql = "Select t.tipo, coalesce(sum(m.Monto),0) as monto ";
        $sql .= "From Caja_Opera o Inner Join Caja_Opera_Mdp m on m.Opera=o.Opera ";
        $sql .= "Inner Join Caja_Mdp t on m.mdp=t.mdp ";
        $sql .= "Where o.Caja_Id=" . $caja_id . " and o.Fecha = " . Fecha::usuarioToBD( $fecha, 1) . " and o.Est='A' ";
        $sql .= "group by t.tipo ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        return $data;
	}

	/**
	 * Función que obtiene el total cobrado en una caja y fecha determinado.
	 * @param integer $caja_id Identificador de la caja.
	 * @param string $fecha Fecha que se desea buscar.
	 */
	public function getMDPCobrado($caja_id,$fecha)
	{

		$this->reiniciaVariablesArqueo();
		$data = $this->getArregloMDPCobrado($caja_id,$fecha);

		foreach ($data as $array)
		{
			if ($array['tipo'] == 'EF')	$this->arqueo_total_efectivo += floatval($array['monto']);
			if ($array['tipo'] == 'CH')	$this->arqueo_cheque += floatval($array['monto']);
			if ($array['tipo'] == 'TC')	$this->arqueo_tarjetacredito += floatval($array['monto']);
			if ($array['tipo'] == 'TD')	$this->arqueo_tarjetadebito += floatval($array['monto']);
			if ($array['tipo'] == 'DE')	$this->arqueo_deposito += floatval($array['monto']);
			if ($array['tipo'] == 'TR')	$this->arqueo_transferencia += floatval($array['monto']);
			if ($array['tipo'] == 'NC')	$this->arqueo_notacredito += floatval($array['monto']);
			if ($array['tipo'] == 'BO')	$this->arqueo_bonos += floatval($array['monto']);
			if ($array['tipo'] == 'HA')	$this->arqueo_haberes += floatval($array['monto']);
			if ($array['tipo'] == 'OT')	$this->arqueo_otros += floatval($array['monto']);

			if ($array['tipo'] != 'EF')  $this->arqueo_total_otros += floatval($array['monto']);

		}

		$this->arqueo_total_efectivo = number_format(floatval($this->arqueo_total_efectivo),2,'.','');
		$this->arqueo_cheque = number_format(floatval($this->arqueo_cheque),2,'.','');
		$this->arqueo_tarjetacredito = number_format(floatval($this->arqueo_tarjetacredito),2,'.','');
		$this->arqueo_tarjetadebito = number_format(floatval($this->arqueo_tarjetadebito),2,'.','');
		$this->arqueo_deposito = number_format(floatval($this->arqueo_deposito),2,'.','');
		$this->arqueo_transferencia = number_format(floatval($this->arqueo_transferencia),2,'.','');
		$this->arqueo_notacredito = number_format(floatval($this->arqueo_notacredito),2,'.','');
		$this->arqueo_bonos = number_format(floatval($this->arqueo_bonos),2,'.','');
		$this->arqueo_haberes = number_format(floatval($this->arqueo_haberes),2,'.','');
		$this->arqueo_otros = number_format(floatval($this->arqueo_otros),2,'.','');
		$this->arqueo_cant_retiro = number_format(floatval($this->arqueo_cant_retiro),2,'.','');

		$this->calcularTotales();

	}

	/**
	 * Función que se utiliza para realizar el cálculo de los montos Totales de Arqueo
	 */
	public function calcularTotales()
	{
		$this->arqueo_efectivo = $this->arqueo_efectivo + $this->arqueo_cant_retiro;
		//Calculo Arqueo
		$this->arqueo_recuento = $this->arqueo_efectivo + $this->arqueo_cheque + $this->arqueo_tarjetacredito + $this->arqueo_tarjetadebito + $this->arqueo_deposito +
		$this->arqueo_transferencia + $this->arqueo_notacredito + $this->arqueo_bonos + $this->arqueo_haberes;

		$this->arqueo_recuento = number_format($this->arqueo_recuento,2,'.','');

		//Calculo Total
		$this->arqueo_total_total = $this->arqueo_total_efectivo + $this->arqueo_total_otros + $this->arqueo_total_fondo;
		$this->arqueo_total_total = number_format($this->arqueo_total_total,2,'.','');

		//Calculo Sobrante/Falta
		$this->arqueo_total_sobrante = $this->arqueo_recuento - $this->arqueo_total_total;
		$this->arqueo_total_sobrante = number_format($this->arqueo_total_sobrante,2,'.','');
	}

	/**
	 * Función que devuelve un arreglo conteniendo el arqueo de una cajay una fecha determinada
	 */
	public function getArqueo($caja_id,$fecha)
	{

        $sql = "Select * from Caja_Arqueo Where Caja_id=" . $caja_id . " and Fecha='" . Fecha::usuarioToBD($fecha) . "'";

		$data = Yii::$app->db->createCommand($sql)->queryAll();

        $array = $data[0];

        $this->arqueo_efectivo = number_format(floatval($array['val_ef']),2,'.','');
		$this->arqueo_cheque = number_format(floatval($array['val_ch']),2,'.','');
		$this->arqueo_tarjetacredito = number_format(floatval($array['val_tc']),2,'.','');
		$this->arqueo_tarjetadebito = number_format(floatval($array['val_td']),2,'.','');
		$this->arqueo_deposito = number_format(floatval($array['val_de']),2,'.','');
		$this->arqueo_transferencia = number_format(floatval($array['val_tr']),2,'.','');
		$this->arqueo_notacredito = number_format(floatval($array['val_nc']),2,'.','');
		$this->arqueo_bonos = number_format(floatval($array['val_bo']),2,'.','');
		$this->arqueo_haberes = number_format(floatval($array['val_ha']),2,'.','');
		$this->arqueo_otros = number_format(floatval($array['val_ot']),2,'.','');

		$this->arqueo_recuento = number_format(floatval($array['recuento']),2,'.','');
		$this->arqueo_total_efectivo = number_format(floatval($array['efectivo']),2,'.','');
		$this->arqueo_total_otros = number_format(floatval($array['otros']),2,'.','');
		$this->arqueo_total_fondo = number_format(floatval($array['fondo']),2,'.','');
		$this->arqueo_total_total = number_format(floatval($array['total']),2,'.','');
		$this->arqueo_total_sobrante = number_format(floatval($array['sobrante']),2,'.','');
		$this->arqueo_cant_retiro = number_format(floatval($array['cant_retiro']),2,'.','');

    	$this->arqueo_billete1000 = $array['cant1000_00'];
		$this->arqueo_billete500 = $array['cant500_00'];
		$this->arqueo_billete200 = $array['cant200_00'];
		$this->arqueo_billete100 = $array['cant100_00'];
		$this->arqueo_billete050 = $array['cant050_00'];
		$this->arqueo_billete020 = $array['cant020_00'];
		$this->arqueo_billete010 = $array['cant010_00'];
		$this->arqueo_billete005 = $array['cant005_00'];
		$this->arqueo_billete002 = $array['cant002_00'];
		$this->arqueo_moneda100 = $array['cant001_00'];
		$this->arqueo_moneda050 = $array['cant000_50'];
		$this->arqueo_moneda025 = $array['cant000_25'];
		$this->arqueo_moneda010 = $array['cant000_10'];
		$this->arqueo_moneda005 = $array['cant000_05'];
		$this->arqueo_moneda001 = $array['cant000_01'];

        return $data;
	}

	/**
	 * Función que corrobora si no se ingresó un arqueo para la caja y la fecha ingresada
	 */
	public function verificarArqueo($caja_id,$fecha)
	{
		$sql = "SELECT EXISTS (Select 1 from Caja_Arqueo Where Caja_id=" . $caja_id . " and Fecha='" . Fecha::usuarioToBD($fecha) . "')";

		return Yii::$app->db->createCommand($sql)->queryScalar();

	}

	/**
	 * Función que inserta un registro del arqueo de una caja y fecha determinada.
	 * @param integer $caja_id Identificador de la caja.
	 * @param string $fecha Fecha
 	 * @param double $arqueo_billete1000 Cantidad de billetes de $1000
	 * @param double $arqueo_billete500 Cantidad de billetes de $500
	 * @param double $arqueo_billete200 Cantidad de billetes de $200
	 * @param double $arqueo_billete100 Cantidad de billetes de $100
	 * @param double $arqueo_billete050 Cantidad de billetes de $50
	 * @param double $arqueo_billete020 Cantidad de billetes de $20
	 * @param double $arqueo_billete010 Cantidad de billetes de $10
	 * @param double $arqueo_billete005 Cantidad de billetes de $5
	 * @param double $arqueo_billete002 Cantidad de billetes de $2
	 * @param double $arqueo_moneda100 Cantidad de monedas de $1
	 * @param double $arqueo_moneda050 Cantidad de monedas de 50c
	 * @param double $arqueo_moneda025 Cantidad de monedas de 25c
	 * @param double $arqueo_moneda010 Cantidad de monedas de 10c
	 * @param double $arqueo_moneda005 Cantidad de monedas de 5c
	 * @param double $arqueo_moneda001 Cantidad de monedas de 1c
	 * @param double $arqueo_efectivo Cantidad en efectivo
	 * @param double $arqueo_cheque Cantidad en cheque
	 * @param double $arqueo_tarjetacredito Cantidad en tarjea de crédito
	 * @param double $arqueo_tarjetadebito Cantidad en tarjea de débito
	 * @param double $arqueo_deposito Cantidad en depósito
	 * @param double $arqueo_transferencia Cantidad en transferencia
	 * @param double $arqueo_notacredito Cantidad en nota de crédito
	 * @param double $arqueo_bonos Cantidad en bonos
	 * @param double $arqueo_haberes Cantidad en haberes
	 * @param double $arqueo_otros Cantidad en otros
	 * @param double $arqueo_total_recuento Cantidad en recuento
	 * @param double $arqueo_total_efectivo Cantidad en efectivo
	 * @param double $arqueo_total_otros Cantidad en otros
	 * @param double $arqueo_total_fondo Cantidad en fondo
	 * @param double $arqueo_total_total Cantidad en total
	 * @param double $arqueo_total_sobrante Cantidad en sobrante
	 */
	public function arqueoNuevo()
	{
		//Verificar existencia de un arqueo con el Nº de Caja y Fecha de la caja actual
		$sql = "SELECT EXISTS (SELECT 1 FROM caja_arqueo WHERE caja_id = " . $this->caja_caja_id . " AND fecha = '" . Fecha::usuarioToBD($this->caja_fecha) . "')";

		$res = Yii::$app->db->createCommand($sql)->queryScalar();

		if ($res == 0)	//Existe el arqueo
		{
			$sql = "Insert into Caja_Arqueo Values (" . $this->caja_caja_id . ",'" . Fecha::usuarioToBD($this->caja_fecha) . "',";
            $sql .= $this->arqueo_billete1000 . "," . $this->arqueo_billete500 . "," . $this->arqueo_billete200 . ",";
			$sql .= $this->arqueo_billete100 . "," . $this->arqueo_billete050 . "," . $this->arqueo_billete020 . "," . $this->arqueo_billete010 . "," . $this->arqueo_billete005 . "," . $this->arqueo_billete002 . ",";
            $sql .= $this->arqueo_moneda100 . "," . $this->arqueo_moneda050 . "," . $this->arqueo_moneda025 . "," . $this->arqueo_moneda010 . "," . $this->arqueo_moneda005 . "," . $this->arqueo_moneda001 . ",";
            $sql .= $this->arqueo_efectivo . "," . $this->arqueo_cheque . "," . $this->arqueo_tarjetacredito . "," . $this->arqueo_tarjetadebito . "," . $this->arqueo_deposito . ",";
            $sql .= $this->arqueo_transferencia . "," . $this->arqueo_notacredito . "," . $this->arqueo_bonos . "," . $this->arqueo_haberes . "," . $this->arqueo_otros . ",";
            $sql .= $this->arqueo_recuento . "," . $this->arqueo_total_efectivo . "," . $this->arqueo_total_otros . ",";
            $sql .= $this->arqueo_total_fondo . "," . $this->arqueo_total_total . "," . $this->arqueo_total_sobrante . "," . $this->arqueo_cant_retiro . ",";
            $sql .= "current_timestamp, " . Yii::$app->user->id . ") ";
		} else
		{
		    $sql = "Update Caja_Arqueo Set ";
            $sql .= "Cant1000_00=" . $this->arqueo_billete1000 . ",Cant500_00=" . $this->arqueo_billete500 . ",Cant200_00=" . $this->arqueo_billete200;
			$sql .= ",Cant100_00=" . $this->arqueo_billete100 . ",Cant050_00=" . $this->arqueo_billete050 . ",Cant020_00=" . $this->arqueo_billete020;
            $sql .= ",Cant010_00=" . $this->arqueo_billete010 . ",Cant005_00=" . $this->arqueo_billete005 . ",Cant002_00=" . $this->arqueo_billete002;
            $sql .= ",Cant001_00=" . $this->arqueo_moneda100 . ",Cant000_50=" . $this->arqueo_moneda050 . ",Cant000_25=" . $this->arqueo_moneda025;
            $sql .= ",Cant000_10=" . $this->arqueo_moneda010 . ",Cant000_05=" . $this->arqueo_moneda005 . ",Cant000_01=" . $this->arqueo_moneda001;
            $sql .= ",val_ef=" . $this->arqueo_efectivo . ",val_ch=" . $this->arqueo_cheque;
            $sql .= ",val_tc=" . $this->arqueo_tarjetacredito . ",val_td=" . $this->arqueo_tarjetadebito;
            $sql .= ",val_de=" . $this->arqueo_deposito . ",val_tr=" . $this->arqueo_transferencia;
            $sql .= ",val_nc=" . $this->arqueo_notacredito . ",val_bo=" . $this->arqueo_bonos;
            $sql .= ",val_ha=" . $this->arqueo_haberes . ",val_ot=" . $this->arqueo_otros;
            $sql .= ",Recuento=" . $this->arqueo_recuento . ",Efectivo=" . $this->arqueo_total_efectivo . ",Otros=" . $this->arqueo_total_otros;
            $sql .= ",Fondo=" . $this->arqueo_total_fondo . ",Total=" . $this->arqueo_total_total . ",Sobrante=" . $this->arqueo_total_sobrante;
			$sql .= ",cant_retiro=" . $this->arqueo_cant_retiro;
            $sql .= ",FchMod=current_timestamp,UsrMod=" . Yii::$app->user->id;
            $sql .= " Where Caja_Id= " . $this->caja_caja_id . " and Fecha= '" . Fecha::usuarioToBD($this->caja_fecha) . "'";
		}

		try
		{
			$data = Yii::$app->db->createCommand($sql)->execute();

			$arreglo = ['return' => 1,
						'mensaje' => 'El arqueo se realizó correctamente.'];

		} catch (\Excepton $e)
		{
			//Si se produce un error, se retorna un entero (0) con el mensaje de error producido.
			$arreglo = ['return' => 0,
						'mensaje' => DBException::getMensaje($e)];
		}

		return $arreglo;

	}
}
