<?php

namespace app\models\caja;

use Yii;
use yii\data\SqlDataProvider;
use app\utils\db\Fecha;
use yii\web\Session;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\utils\helpers\DBException;

/**
 * This is the model class for table "v_caja_ticket".
 *
 * @property integer $ticket
 * @property integer $opera
 * @property integer $caja_id
 * @property integer $caja_nom
 * @property string $fecha
 * @property string $hora
 * @property string $teso_id
 * @property string $teso_nom
 * @property integer $ctacte_id
 * @property integer $trib_id
 * @property integer $trib_nom
 * @property integer $trib_tipo
 * @property string $tobj_nom
 * @property string $obj_id
 * @property string $obj_nom
 * @property integer $subcta
 * @property integer $anio
 * @property integer $cuota
 * @property integer $faci_id
 * @property string $num
 * @property string $num_nom
 * @property string $monto
 * @property string $monto_valida
 * @property string $ctacte_id
 * @property string $faci_id
 * @property string $est
 * @property string $obs
 * @property string $fchmod
 * @property integer $usrmod
 *
 */
class CajaTicket extends \yii\db\ActiveRecord
{

	public $teso_nom;
	public $num_nom;
	public $trib_nom;
	public $trib_id;
    public $trib_tipo;
    public $motivo_baja = '';

	/* Arreglo para MDP */
	public $mdps;


	/* Variables para Registro Pago ant*/

	public $pagoant_pago_id = 0;
	public $pagoant_anio = '';
	public $pagoant_cuotadesde = '';
	public $pagoant_cuotahasta = '';
	public $pagoant_fecha = '';
	public $pagoant_tributo = 0;
	public $pagoant_obj_nom = '';
	public $pagoant_obj_id = '';
	public $pagoant_comprob = '';
	public $pagoant_modif = '';
	public $pagoant_obs = '';
	public $pagoant_obj_tobj = 0;
	public $pagoant_suc = 0;


	/* Variables para cheque cartera */

	public $convenio1;
	public $convenio2;
	public $banco;
	public $bancoNom;
	public $sucursal;
	public $sucursalNom;
	public $cuenta;
	public $titular;
	public $cheque;
	public $fechaCobro;
	//public $monto;
	public $cart_id = 0;
	public $cheque_cartera_est;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'caja_ticket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opera', 'caja_id', 'fecha', 'ctacte_id', 'trib_id', 'obj_id', 'anio', 'cuota', 'monto', 'monto_valida', 'est'], 'required'],
            [['opera', 'caja_id', 'ctacte_id', 'trib_id', 'subcta', 'anio', 'cuota', 'faci_id', 'pagoant_pago_id', 'pagoant_anio', 'pagoant_cuotadesde','pagoant_cuotahasta','pagoant_tributo','pagoant_obj_tobj', 'pagoant_suc' ], 'integer'],
            [['fecha', 'hora'], 'safe'],
            [['monto', 'monto_valida', 'trib_tipo', 'teso_id,ctacte_id,faci_id'], 'number'],
            [['obj_id', 'num'], 'string', 'max' => 8],
            [['est'], 'string', 'max' => 1],
            [[
				'obs', 'teso_nom', 'num_nom', 'trib_nom', 'caja_nom', 'tobj_nom', 'obj_nom', 'pagoant_fecha', 'pagoant_obj_nom', 'pagoant_obj_id', 'pagoant_comprob',
				'pagoant_obs','pagoant_modif',

			 ], 'string', 'max' => 100,
			],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ticket' => 'Numero de ticket',
            'opera' => 'Codigo de operacion',
            'caja_id' => 'Codigo de caja',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'ctacte_id' => 'Identificador de cuenta corriente',
            'trib_id' => 'Codigo de tributo',
            'obj_id' => 'Codigo de objeto',
            'subcta' => 'Subcuenta',
            'anio' => 'Año',
            'cuota' => 'Cuota',
            'faci_id' => 'Faci ID',
            'num' => 'Codigo de num responsable',
            'monto' => 'Monto',
            'monto_valida' => 'Monto de validación',
            'est' => 'Estado',
            'obs' => 'Obs.',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

    public function __construct()
    {
    	$this->pagoant_fecha = Fecha::usuarioToDatePicker( Fecha::getDiaActual() );
    }

    /**
     * Función que obtiene los datos de Tickets de la BD
     */
    public function obtenerDatosTicket()
    {
    	$arreglo = Yii::$app->db->createCommand( "SELECT * FROM v_caja_ticket WHERE ticket = '" . $this->ticket . "'")->queryAll();
		$this->mdps = Yii::$app->db->createCommand( "select v.mdps from  v_caja_ticket v where v.ticket='" . $this->ticket . "'")->queryScalar();

    	$this->teso_nom 	= $arreglo[0]['teso_nom'];
    	$this->num 			= $arreglo[0]['num'];
    	$this->num_nom 		= $arreglo[0]['num_nom'];
    	$this->trib_nom 	= $arreglo[0]['trib_nom'];
    	$this->trib_id 		= $arreglo[0]['trib_id'];
    	$this->trib_tipo 	= $arreglo[0]['trib_tipo'];

    }

    /**
     * Función que carga el detalle de un ticket
     */
    public function CargarDetalle()
    {
    	$id = ($this->ticket != '' ? $this->ticket : 0);

        $sql = "SELECT * FROM v_caja_ticket_det WHERE ticket = '" . $id . "'" ;

		$res = Yii::$app->db->createCommand( $sql )->queryAll();

		$array = ArrayHelper::map($res, 'cta_id', function($model){ return [
					'cta_id' => $model['cta_id'],
					'cta_nom' => $model['cta_nom'],
					'monto' => $model['monto'],
				];});

		return $array;

    }

	/**
	 * Función que se encarga de grabar la actualización de las cuentas en los tickets.
	 * @param array $arreglo_sesion Arreglo con los datos de las cuentas.
	 */
	public function grabarTicketActualizado( $arreglo_sesion )
	{
		$error = [];

		 //Obtener el monto actual del Ticket
		 $sql = "SELECT monto FROM caja_ticket WHERE ticket = " . $this->ticket;
		 $monto = floatVal( Yii::$app->db->createCommand( $sql )->queryScalar() );

		 //Calcular la suma total de las cuentas.
		 $monto_actual= 0;

		 if ( count($arreglo_sesion) > 0 )
		 {
			 //Sumo los montos
			 foreach( $arreglo_sesion as $array )
			 	$monto_actual += floatVal( $array['monto'] );

		 } else
		 {
		 	$error[] = 'Ocurrió un error al obtener los datos del Ticket.';

			$this->addErrors( $error );

			return false;
		 }

		 //Verificar que los montos coincidan
		 if ( ( $monto *100 ) - ( $monto_actual * 100 ) != 0 )
		 {
		 	$error[] = 'El monto de Ticket y el monto actual no coinciden.';

			$this->addErrors( $error );

			return false;
		 }

		 $transaction = Yii::$app->db->beginTransaction();

		 try {

			 //Eliminar los datos en caja_ticket_cuenta
			 $sqlDelete = "DELETE FROM caja_ticket_det WHERE ticket = " . $this->ticket;

			 Yii::$app->db->createCommand( $sqlDelete )->execute();

			 //Insertar los datos presentes en sesión
			 foreach ( $arreglo_sesion as $array )
			 {
				 $sql = "INSERT INTO caja_ticket_det VALUES ( " . $this->ticket . "," . $array['cta_id'] . "," . $array['monto'] . ")";

				 Yii::$app->db->createCommand( $sql )->execute();
			 }

		 } catch (\Exception $e)
		 {
			 $transaction->rollback();

			 $error[] = DBException::getMensaje( $e );

			 $this->addErrors( $error );

 			 return false;

		 }

		 $transaction->commit();

		 return true;

	}

   /**
     * Función que carga el boleto de un ticket
     */
    public function CargarBoleto()
    {
    	$id = ($this->ticket != '' ? $this->ticket : 0);

        $sql = "SELECT * FROM v_caja_ticket_item WHERE ticket = '" . $id . "' ORDER BY ticket" ;

        $count = Yii::$app->db->createCommand("SELECT count(*) FROM v_caja_ticket_item WHERE ticket = '" . $id . "'")->queryScalar();

    	$dataProvider = new SqlDataProvider([
			 	'sql' => $sql,
	            'key' => 'ticket',
				'totalCount' => (int)$count,
				'pagination' =>
					['pageSize' => 10,
					],
	        ]);

	    return $dataProvider;


    }

       /**
     * Función que carga el recibo de un ticket
     */
    public function CargarRecibo()
    {
    	$id = ($this->ticket != '' ? $this->ticket : 0);

        $sql = "SELECT ticket,recibo,to_char(fecha::date,'dd/mm/yyyy') as fecha,acta,item_nom,area_nom,monto FROM v_caja_ticket_rec WHERE ticket = '" . $id. "'" ;

        $count = Yii::$app->db->createCommand("SELECT count(*) FROM v_caja_ticket_rec WHERE ticket = '" . $id . "'")->queryScalar();

    	$dataProvider = new SqlDataProvider([
			 	'sql' => $sql,
	            'key' => 'ticket',
				'totalCount' => (int)$count,
				'pagination' =>
					['pageSize' => 5,
					],
	        ]);

	    return $dataProvider;


    }

    public function consultaPermitida($opera, $comprob)
    {
    	if ($comprob != '')
    	{
			$sql = "SELECT count(*) FROM caja_ticket t ";
			$sql .= "LEFT JOIN caja c ON t.caja_id = c.caja_id ";

			if ($opera == "T")
			    $sql .= "WHERE ticket = '" . $comprob . "'";
			else if ($opera == 'O')
			    $sql .= "WHERE opera = " . $comprob;

			$sql .= " AND c.teso_id IN (SELECT teso_id FROM sam.sis_usuario_tesoreria WHERE usr_id= " . Yii::$app->user->id . ")";

			return Yii::$app->db->createCommand($sql)->queryScalar() > 0;
    	}
    }

    /**
     * Función que devuelve el motivo de una anulación solicitada
     * @param string $tipo Tipo de anulación
     * @param integer $comprob Número de comprobante
     * @return string Descripción del motivo
     */
    public function get_AnulaMotivo($tipo, $comprob)
    {
    	$sql = "SELECT motivo FROM caja_anulado WHERE tipo='" . $tipo . "' AND comprob=" . $comprob;

        $this->motivo_baja = Yii::$app->db->createCommand($sql)->queryScalar();

    }

    /**
     * Función que busca los datos de Ticket para el listado
     */
    public function buscartTicket($cond = "", $orden = "" )
    {
    	$sql = "SELECT ticket,opera,to_char(fecha,'dd/mm/yyyy') as fecha,hora,monto,caja_id,trib_id,trib_nom,obj_id,obj_nom,subcta,anio,cuota,num,est FROM v_caja_ticket ";
    	$sql2 = "";
    	$sql3 = "";

    	($cond != "" ? $sql2 = 'WHERE ' . $cond : "");
    	($orden != "" ? $sql3 .= ' ORDER BY ' . $orden : ' ORDER BY ticket ASC');

    	$count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM v_caja_ticket ' . ($sql2 != "" ? $sql2 : ''))->queryScalar();

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql . $sql2 . $sql3,
            'key' => 'ticket',
			'totalCount' => (int)$count,
			'pagination' =>
				['pageSize' => (int)$count,
				],
			'sort' => [
				'attributes' => [
					'ticket',
					'opera',
					'fecha',
                    'monto',
				],
                'defaultOrder' => [
                    'fecha' => SORT_DESC,
                ]
			],
        ]);

        return $dataProvider;
    }

    /**
     * Función que busca las cajas para abrir de la BD
     */
    public function CargarDetalleAperturaCaja($teso_id)
    {
    	$sql = "SELECT c.caja_id, c.nombre, u.apenom as cajero " .
    			"FROM caja c LEFT JOIN sam.sis_usuario u ON c.usr_id = u.usr_id " .
    			"WHERE c.est='A' AND tipo <> 2 AND teso_id = " . $teso_id .
    			" AND (sup1 = " . Yii::$app->user->id." OR sup2= " . Yii::$app->user->id." OR sup3 = " . Yii::$app->user->id." OR sup4 = " . Yii::$app->user->id.") " .
    			"AND c.caja_id NOT IN (SELECT caja_id FROM caja_estado WHERE est <> 0) ";
    			//"AND c.caja_id NOT IN (SELECT caja_id FROM caja_estado WHERE est = 0 and fchciesup IS NOT NULL)";

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
            'key' => 'caja_id',

        ]);

        return $dataProvider;
    }


    /**
     * Función que busca las cajas para cerrar de la BD
     */
    public function CargarDetalleCierreCaja($teso_id, $fecha)
    {
    	$sql = "SELECT c.caja_id, c.nombre, u.apenom as cajero " .
    			"FROM caja c LEFT JOIN caja_estado e ON c.caja_id = e.caja_id " .
    			"LEFT JOIN sam.sis_usuario u ON c.usr_id = u.usr_id " .
    			"WHERE teso_id = " . $teso_id .
				" AND (sup1 = " . Yii::$app->user->id." OR sup2= " . Yii::$app->user->id." OR sup3 = " . Yii::$app->user->id." OR sup4 = " . Yii::$app->user->id.")" .
    			" AND e.est = 3 AND fecha = " . Fecha::usuarioToBD( $fecha, 1 );

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
            'key' => 'caja_id',

        ]);

        return $dataProvider;
    }

    /**
     * Función que obtiene la cantidad de ingresos posteriores a una fecha
     * @param integer $caja_id Código de la Caja
     * @param string $fecha Fecha para verificar
     */
    public function getIngresos($caja_id,$fecha)
    {
        //Recupero los usuarios que tengan el Permiso para apertura o anulación de operaciones de Caja
        $sql = "SELECT COUNT(*) FROM caja_ticket WHERE caja_id = " . $caja_id . " AND fecha >= '" . Fecha::usuarioToBD($fecha) . "'";
        $cant = Yii::$app->db->createCommand($sql)->execute();

        if ($cant = 0)
            return "";
        else
            return "No se permite abrir la caja " . $caja_id . ". Existen ingresos posteriores a la fecha.";

    }

    /**
     * Función que permite la apertura de una caja por el supervisor en una fecha dada.
     * antes de abrir la caja verifica que ya no este abierta, o que el estado sea "cerrada por el supervisor",
     * que la tesorería este activa y que el usuario logueado tenga permiso.
     * @param integer $caja_id Código de caja
     * @param string $fecha Fecha de apertura
     * @return string Devuelve "" si se pudo abrir, mensaje de error en caso negativo.
     */
    public function aperturaSupervisor($caja_id, $fecha)
    {

    	$sql = "INSERT INTO caja_estado VALUES (" . $caja_id . ",'" . Fecha::usuarioToBD($fecha) . "',";
        $sql .= Yii::$app->user->id . ",current_timestamp,null,null,null,null,null,null,1)";

        $cant = Yii::$app->db->createCommand($sql)->execute();

        if ($cant > 0)
            return "";
        else
            return "No se pudo abrir la caja " . $caja_id . ".";
    }

    /**
     *
     * Función que permite el cierre de caja por el supervisor.
     * Antes de efectuar el cierre, verifica que la caja haya sido cerrada por el cajero, que el supervisor logueado tenga
     * permisos para el cierre y que no existan anulaciones pendientes.
     * @param integer $caja_id Código de caja
     * @param string $fecha Fecha de cierre
     * @return string Devuelve "" si se pudo abrir, mensaje de error en caso negativo.
     */
    public function cierreSupervisor($caja_id, $fecha)
    {

    	//Valido q no exista en caja_estado con est <> 3
        $sql = "SELECT count(*) FROM caja_estado ";
        $sql .= "WHERE caja_id =" . $caja_id . " AND fecha = '" . Fecha::usuarioToBD($fecha) . "' AND est <> 3";

        $cant = Yii::$app->db->createCommand($sql)->queryScalar();

        if ($cant > 0)
            return "La caja " . $caja_id . " no puede ser cerrada por supervisor.";

    	/*
        else if not existeproc(tacc.caja_apertura)
            return "el supervisor no tiene el permiso";
		*/

        $sqla = "SELECT count(*) AS cant FROM caja_anulado a ";
		$sqla .= "Inner join caja_ticket o ";
        $sqla .= "On ((a.tipo='O' AND a.comprob=o.opera) or (a.tipo='T' and a.comprob=o.ticket))";
        $sqla .= "WHERE o.caja_id = " . $caja_id . " AND sup is null and o.est<>'B'";

        $ct = Yii::$app->db->createCommand($sqla)->queryScalar();

        if ($ct > 0)
            return "La caja " . $caja_id . " no se puede cerrar. Existen anulaciones pendientes.";
        else
        {
            $sql = "update caja_estado set est = 0, ciesup = " . Yii::$app->user->id . ", fchciesup = current_timestamp";
            $sql .= " where caja_id = " . $caja_id . " and fecha = '" . Fecha::usuarioToBD($fecha) . "'";

			$cant = Yii::$app->db->createCommand($sql)->execute();

            if ($cant > 0)
                return "";
            else
                return "La caja " . $caja_id . "no se pudo cerrar.";
        }
    }

    //-------------------------------------ANULACIÓN-----------------------------------------------//

    public function getPedidosAnulacion()
    {
    	$sql = "Select * From sam.uf_caja_anula_pendiente(" . Yii::$app->user->id . ")";

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
            //'key' => 'caja_id',

        ]);

        return $dataProvider;

    }

    /**
     * Confirma la anulación, tanto de ticket como de operación
 	 * @param string $tipo Tipo de Anulación (T/O)
     * @param integer $comp N° de Comprobante
     */
    public function anulaConfirma($tipo,$comp)
    {
    	$sql = "SELECT sam.uf_caja_anula('" . $tipo . "'," . $comp . "," . Yii::$app->user->id . ")";

    	try
    	{
    		Yii::$app->db->createCommand($sql)->execute();

    	} catch (\Exception $e)
    	{
    		return "No se pudieron confirmar las anulaciones.";
    	}

    	return "";

    }


    /**
     * Rechaza el pedido de anulación, tanto de ticket como de operación
     * @param string $tipo Tipo de Anulación (T/O)
     * @param integer $comp N° de Comprobante
     */
    public function anulaRechaza($tipo,$comp)
    {
    	$sql = "SELECT sam.uf_caja_anula_rechaza('" . $tipo . "'," . $comp . "," . Yii::$app->user->id . ")";

    	try
    	{
    		Yii::$app->db->createCommand($sql)->execute();

    	} catch (\Exception $e)
    	{
    		return "No se puedieron rechazar las anulaciones.";
    	}

    	return "";

    }

    /**
     * Función que retorna el nombre del usuario
     * @return string Nombre de usuario
     */
    public function getNombreUsuario()
    {
    	$sql = "select apenom from sam.sis_usuario where usr_id = " . Yii::$app->user->id;

    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }

	//-------------------------------------RECIBO MANUAL-----------------------------------------------//

	/**
	 * Función que retorna en forma de DataProvider los recibos que se fueron cargando.
	 * @param array $recibos Arreglo con los datos de los recibos.
	 * @return dataProvider Información de Recibos.
	 */
	public function obtenerRecibos($recibos)
	{
	    $dataProvider = new ArrayDataProvider([
			 	'models' => $recibos,
	            'key' => 'recibo',
				'totalCount' => count($recibos),
				'pagination' =>
					['pageSize' => 3,
					],
	        ]);

	    return $dataProvider;

	}

	/**
	 * Función que carga los recibos en un arreglo en memoria
	 * @param integer $id Identiicador de la ctacte
	 */
	public function cargarReciboManual($id)
	{
		$sql = "SELECT r.recibo, r.ctacte_id, to_char(r.fecha,'dd/mm/yyyy') as fecha, r.acta, r.item_id, r.area, r.monto, a.nombre as area_nom " .
				"FROM ctacte_rec r left join sam.muni_oficina a on r.area=a.ofi_id where r.ctacte_id=" . $id;
		//$sql = "SELECT r.* FROM v_recibo r left join sam.muni_oficina a on r.area=a.ofi_id where r.ctacte_id=" . $id;
        $sqlMonto = "Select sum (monto) from ctacte_rec where ctacte_id=" . $id;

		$this->obs = Yii::$app->db->createCommand("SELECT obs FROM ctacte WHERE ctacte_id = " . $id )->queryScalar();
    	$cmd = Yii :: $app->db->createCommand($sql);

    	$res = $cmd->queryAll();

    	$arreglo = ArrayHelper::map($res, 'recibo', function($model){ return [
    												 'recibo' => $model['recibo'],
    												 'ctacte_id' => $model['ctacte_id'],
												     'fecha' => $model['fecha'],
												     'acta' => $model['acta'],
												     'item_id' => $model['item_id'],
												     'area' => $model['area'],
												     'monto' => $model['monto'],
												     'area_nom' => $model['area_nom'],

    										];});

    	$session = new Session;
    	$session->open();
    	$session['arregloRecibosManual'] = $arreglo;
    	$session->close();

	}

	public function grabarReciboManual($id,$tipo,$obs)
	{
		//Obtengo en $arreglo el arreglo de los recibos en sesión.
		$session = new Session;
    	$session->open();
    	$arreglo = $session['arregloRecibosManual'];
    	$session->close();

    	$error = [];

		$dia = Fecha::usuarioToBD(Fecha::getDiaActual());

		if (count($arreglo) > 0)
		{
			$transaction = Yii::$app->db->beginTransaction();

	    	try
	    	{
	    		//En caso de que se actualice
				if ($tipo == 3)
				{
		        	//Si es una modificación, elimino los datos para volver a insertarlos

		            $sql = "Delete from ctacte_rec where ctacte_id=" . $id;
		            Yii::$app->db->createCommand($sql)->execute();

		            $sql = "Delete from ctacte where ctacte_id=" . $id;
		            Yii::$app->db->createCommand($sql)->execute();

		            $sql = "Delete from ctacte_det where ctacte_id=" . $id;
		            Yii::$app->db->createCommand($sql)->execute();
				}

				$obj_id = trim($this->getObjeto());

		    	//Obtengo un nuevo id de liquidación
		    	$liq_id = (Yii::$app->db->createCommand("Select nextval('temp.seq_ctacte_liq_manual')")->QueryScalar()) + 1;

		    	//inserto todos los recibos en la tabla temp de recibos
		    	foreach($arreglo as $array)
		    	{
		            $sql = "Insert into temp.ctacte_liq_rec values(" . $liq_id . "," . $array['recibo'] . ",'" . Fecha::usuarioToBD($array['fecha']);
		            $sql .= "','" . $array['acta'] . "'," . $array['item_id'] . "," . $array['area'] . "," . $array['monto'] . ")";
		            Yii::$app->db->createCommand($sql)->execute();

		    	}

		    	//Selecciono los montos agrupados por item_id
		        $sql = "Select item_id, sum(monto) as monto ";
		        $sql .= "From temp.ctacte_liq_rec ";
		        $sql .= "Where liq_id= " . $liq_id;
		        $sql .= " Group by liq_id, item_id";

		        $aux = Yii::$app->db->createCommand($sql)->queryAll();

		    	//Inserto en tabla temp de ctacte_liq_manual
		    	foreach ($aux as $datos)
		    	{
		            $sql = "Select coalesce(max(orden),0)+1 from temp.ctacte_liq_manual where Liq_Id = " . $liq_id;

	          		$orden = Yii::$app->db->createCommand($sql)->queryScalar();

		            $sql = "Insert into temp.ctacte_liq_manual values(" . $liq_id . "," . $orden . "," . $datos['item_id'];
		            $sql .= ",'','','',''," . $datos['monto'] . ")";

					Yii::$app->db->createCommand($sql)->execute();

		    	}

		    	//Ejecuto sam.uf_emision
		        $sql = "Select sam.uf_emision(12," . date('Y') . ",0,'".$obj_id."',0,";
		        $sql .= Yii::$app->user->id . "," . $liq_id . ",1,'" . Fecha::usuarioToBD($dia) . "','','" . $obs . "',0)";

	        	$ctacte_id = Yii::$app->db->createCommand($sql)->queryScalar();

	        	//Inserto en la tabla ctacte_rec a partir de la temporal
		        $sql = "Insert into ctacte_rec ";
		        $sql .= "Select " . $ctacte_id . ", recibo, fecha, acta, item_id, area, monto ";
		        $sql .= "From temp.ctacte_liq_rec Where liq_id=" . $liq_id;

		        $count = Yii::$app->db->createCommand($sql)->execute();

				if (!$count > 0)
				{
					$transaction->rollback();

					$arreglo = [
						'return' => 0,
					];

					$error [] = 'Ocurrió un error al grabar los datos.';

				} else
				{
					 //Elimino los datos de temp
			        $sql = "Delete From temp.ctacte_liq_rec Where liq_id=" . $liq_id;
			        $count = Yii::$app->db->createCommand($sql)->execute();

					if ($count > 0)
					{
						$transaction->commit();

						$arreglo = [
							'return' => $ctacte_id,
						];

					} else
					{
						$transaction->rollback();

						$arreglo = [
							'return' => 0,
						];

						$error[] = 'Ocurrió un error al grabar los datos.';

					}
				}

	    	}
	    	catch (\Exception $e)
	    	{
	    		$transaction->rollback();

	    		$arreglo = [
					'return' => 0,
				];

				$error[] = DBException::getMensaje( $e );

	    	}

		} else
		{
			$arreglo = [
					'return' => 0,
				];

			$error[] = 'Debe haber al menos un recibo.';
		}

		$this->addErrors( $error );

		return $arreglo;

	}

	public function borrarReciboManual($id)
	{
		$error = [];

		//Selecciono el estado del recibo antes de la eliminación
		$estado = Yii::$app->db->createCommand("SELECT est FROM ctacte WHERE ctacte_id =" . $id)->queryScalar();

		if ($estado == 'P') //Si el estado es 'P'(Pagado), no se puede eliminar el recibo.
		{
			$error[] = 'No se puede dar de baja un recibo pagado.';
		} else
		{
			try
			{
				$sql = "Update ctacte Set est= 'B' where ctacte_id=" . $id;

				$count = Yii::$app->db->createCommand($sql)->execute();

				if ($count == 0)
					$error[] = 'Ocurrió un error al dar de baja el recibo manual.';

			} catch ( \Exception $e )
			{
				$error[] = DBException::getMensaje( $e );
			}

		}

		$this->addErrors( $error );

	}

	/**
	 * Función que se utiliza para obtener el estado de un recibo.
	 * @param integer $id Identificador del recibo.
	 * @return integer 0. El estado del recibo <> 'B' - 1. El estado del reibo = 'B'
	 */
	public function estadoBajaRecibo( $id )
	{
		$sql = "SELECT EXISTS ( SELECT 1 FROM ctacte WHERE ctacte_id = " . $id . " AND est = 'B' )";

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	public function activarReciboManual($id)
	{
		$error = [];
		//Selecciono el estado del recibo antes de la activación
		$estado = Yii::$app->db->createCommand("SELECT est FROM ctacte WHERE ctacte_id =" . $id)->queryScalar();

		if ($estado != 'B') //Si el estado no es 'B'(Baja), el recibo ya se encuentra activo.
		{
			$error[] = 'El inmueble ya se encuentra activo.';
		}

		try
		{
			$sql = "Update ctacte Set est= 'D' where ctacte_id=" . $id;

			$count = Yii::$app->db->createCommand($sql)->execute();

		} catch ( \Exception $e )
		{
			$error[] = DBException::getMensaje( $e );
		}

		$this->addErrors( $error );
	}

	/**
	 * Función que retorn en forma de DataPovider el resultado de la búsqueda del listado
	 * @param string $cond Condición de búsqueda.
	 * @param string $tipo Tipo de búsqueda. Por recibo o por comprobante
	 * @return dataProvider Resultado de la búsqueda.
	 */
	public function buscarReciboManual($cond, $tipo)
	{
    	// Si se busca por recibo
    	if ($tipo == 'R')
    	{
    		$sql = "Select ctacte_id,est,recibo,to_char(fecha,'dd/mm/yyyy') as fecha,acta,item_nom,area_nom,monto,ticket,obj_id From V_Recibo r ";
            $sql .= "Where " . $cond;
            $sql .= " Order by r.ctacte_id";

    	} else	// Si se busca por comprobante
        {
            $sql = "Select r.ctacte_id, c.est, ticket, c.obj_id, count(r.*) as cant, sum(r.monto) as total, c.obs ";
            $sql .= "from ctacte_rec r ";
            $sql .= "left join ctacte c on r.ctacte_id = c.ctacte_id ";
            $sql .= "left join caja_ticket t on r.ctacte_id= t.ctacte_id ";
            $sql .= "Where " . $cond;
            $sql .= " group by r.ctacte_id, c.est, ticket, c.obj_id, c.obs";
            $sql .= " Order by r.ctacte_id";
        }

    	return Yii::$app->db->createCommand( $sql )->queryAll();

	}

	public function ImprimirReciboManual($id,&$sub1)
	{
		$sql = "Select distinct *,to_char(venc1,'dd/mm/yyyy') venc1,to_char(fchemi,'dd/mm/yyyy') fchemi From V_Emision_Print Where CtaCte_id=" .$id;
		$emision = Yii::$app->db->createCommand($sql)->queryAll();

		$sql = "Select Obs From CtaCte Where CtaCte_Id = " .$id;
        $emision[0]["Mensaje"] = Yii::$app->db->createCommand($sql)->queryScalar();

        $sql = "Select r.*,to_char(fecha,'dd/mm/yyyy') fecha, a.nombre as area_nom From ctacte_rec r left join sam.muni_oficina a on r.area = a.ofi_id ";
        $sql .= "Where r.CtaCte_id=" . $id . " order by recibo";

        $sub1 = Yii::$app->db->createCommand($sql)->queryAll();

        return $emision;
	}

	//-----------------------------------REGISTRO PAGOS ANTERIORES-------------------------------------------------------//

	/**
	 * Función que se utiliza para verificar si se debe ingresar una sucursal.
	 * @param integer $trib Identificador de tributo
	 */
	public function getIngresaSucursal( $trib ){

		$sql = "SELECT uso_subcta FROM trib WHERE trib_id = " . $trib;

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	/**
	 * Función que lista los pagos anteriores
	 * @param string $cond Condición para la búsqueda
	 */
	public function listarPagosOld($cond = '')
	{
        $sql = "select c.pago_id, c.obj_id, c.trib_id, c.anio, c.cuota, to_char(c.fchpago, 'dd/MM/yy') as fchpago, c.comprob, c.obs, c.fchmod, c.usrmod,t.nombre as trib_nom,o.nombre as obj_nom,";
        $sql .= "(u.nombre::text || ' - '::text) || to_char(c.fchmod, 'DD/MM/YYYY'::text) AS modif ";
        $sql .= "from caja_pagoold c inner join objeto o on o.obj_id = c.obj_id ";
        $sql .= "inner join Trib t on t.trib_id = c.trib_id LEFT JOIN sam.sis_usuario u ON c.usrmod = u.usr_id";

		if ($cond !== '')
			$sql .= " WHERE " . $cond;
		$sql .= " ORDER BY c.fchpago";


    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
            'key' => 'pago_id',
        ]);

        return $dataProvider;
	}

	public function cargarPagosOld( $id )
	{
		$sql = "select c.pago_id, c.obj_id, c.trib_id, c.anio, c.cuota, to_char(c.fchpago, 'dd/MM/yy') as fchpago, c.comprob, c.obs, c.fchmod, c.usrmod,t.nombre as trib_nom,o.nombre as obj_nom, o.tobj,";
        $sql .= "(u.nombre::text || ' - '::text) || to_char(c.fchmod, 'DD/MM/YYYY'::text) AS modif ";
        $sql .= "from caja_pagoold c inner join objeto o on o.obj_id = c.obj_id ";
        $sql .= " inner join Trib t on t.trib_id = c.trib_id LEFT JOIN sam.sis_usuario u ON c.usrmod = u.usr_id";
        $sql .= " WHERE c.pago_id=".$id;

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $this->pagoant_pago_id = $data[0]['pago_id'];
		$this->pagoant_anio = $data[0]['anio'];
		$this->pagoant_cuotadesde = $data[0]['cuota'];
		$this->pagoant_fecha = $data[0]['fchpago'];
		$this->pagoant_fecha = Fecha::usuarioToDatePicker($this->pagoant_fecha);
		$this->pagoant_tributo = $data[0]['trib_id'];
		$this->pagoant_obj_nom = $data[0]['obj_nom'];
		$this->pagoant_obj_id = $data[0]['obj_id'];
		$this->pagoant_obj_tobj = $data[0]['tobj'];
		$this->pagoant_comprob = $data[0]['comprob'];
		$this->pagoant_modif = $data[0]['modif'];
		$this->pagoant_obs = $data[0]['obs'];

		return $data;

	}

	/**
	 * Función que carga un nuevo registro en Pago Old
	 * @param string $obj_id
	 * @param integer $trib_id
	 * @param integer $anio
	 * @param integer $cuota
	 * @param string $fchpago
	 * @param integer $comprob
	 * @param string $obs
	 */
	function registrarPagoOld()
	{
		$error = [];

	 	try
	 	{
	        if ( $this->pagoant_tributo == 1 && $this->pagoant_obj_id == '' )	//Se ingresa un convenio de pago.
	        {
	        	$sql = "SELECT obj_id FROM ctacte where trib_id = 1 and anio = ". $this->pagoant_anio;

	            $this->pagoant_obj_id = Yii::$app->db->createCommand($sql)->queryScalar();

				if ( $this->pagoant_obj_id == '' )
					$error[] = "Convenio Incorrecto";
	        }

			if( $this->getIngresaSucursal( $this->pagoant_tributo ) ){
				if( $this->pagoant_suc == 0 || $this->pagoant_suc == '' ){
					$error[]	= 'Ingrese una sucursal válida.';
				}
			}

	        if ( count( $error ) == 0 )
	        {
	        	for ($cuota=$this->pagoant_cuotadesde; $cuota <= $this->pagoant_cuotahasta; $cuota ++) {

					$sql = "SELECT * FROM sam.uf_caja_pagoold(" . $this->pagoant_tributo . ",'" . $this->pagoant_obj_id . "', 0," .
					$this->pagoant_anio . "," . $cuota . "," . Fecha::usuarioToBD( $this->pagoant_fecha, 1 ) . "," .
					intVal( $this->pagoant_comprob ) . ",'" . $this->pagoant_obs . "'," . Yii::$app->user->id . ",1)";

					$count = Yii::$app->db->createCommand($sql)->execute();
				}
	        }

	 	} catch (\Exception $e)
	 	{
	 		$error[] = DBException::getMensaje($e);

			//Eliminar el ID de objeto en caso de que se trate de un convenio
			if ( $this->pagoant_tributo == 1 ){
				$this->pagoant_obj_id = '';
			}
	 	}

	 	$this->addErrors( $error );

	}

	/**
	 * Función que borra un registro de Pago Old
	 */
	public function borrarPagoOld()
	{
		$ctacte_id = 0;

		if ( $this->pagoant_pago_id != '' ){

			//Verificar que el usuario que elimina sea el mismo que dió de alta.
			$sql = "SELECT usrmod from caja_pagoold where pago_id = " . $this->pagoant_pago_id;
			$usrmod = Yii::$app->db->createCommand($sql)->queryScalar();

			if ( $usrmod !== Yii::$app->user->id )
			{
				$this->addError( 'ctacte_id', 'Solo el Usuario que registró el Pago puede darlo de baja.' );

			}

			if( $this->hasErrors() ){

				return false;
			}

			$transaction = Yii::$app->db->beginTransaction();

			try{

				$sql = "DELETE FROM caja_pagoold WHERE pago_id = $this->pagoant_pago_id";

	            Yii::$app->db->createCommand( $sql )->execute();

	            $sql = "Select c.ctacte_id From ctacte c where c.trib_id= $this->pagoant_tributo and c.obj_id ='$this->pagoant_obj_id'" .
						" and c.anio = $this->pagoant_anio and c.cuota = $this->pagoant_cuotadesde";

	            $ctacte_id = Yii::$app->db->createCommand($sql)->queryScalar();

	            $sql = "update ctacte_det set est = 'B' where ctacte_id = " . $ctacte_id . " and topera = 3 and est = 'A'";
	            Yii::$app->db->createCommand($sql)->execute();

	            $sql = "select sam.uf_ctacte_ajuste(" . $ctacte_id . ")";
	            Yii::$app->db->createCommand($sql)->execute();

	            $sql = "update ctacte set fchpago = null where ctacte_id = $ctacte_id";
				Yii::$app->db->createCommand($sql)->execute();

			} catch (\Exception $e)
			{
				$transaction->rollback();

				$this->addError( 'ctacte_id', DBException::getMensaje( $e ) );

				return false;
			}

			if ( !$this->hasErrors() ){

				$transaction->commit();
			}

		} else {

			$this->addError( 'ctacte_id', 'No se seleccionó ningún elemento' );

			return false;
		}

		return true;
	}

	//-----------------------------------CHEQUE CARTERA-------------------------------------------------------//

	/*public function listarChequeCartera($cond)
	{
		$sql = "SELECT cart_id,nrocheque,monto,bco_ent,bco_suc,bco_cta,titular,plan_id,plan_id2,est,to_char(fchalta,'dd/MM/yyyy') as fchalta,to_char(fchcobro,'dd/MM/yyyy') as fchcobro,bco_ent_nom" .
				" FROM v_caja_cheque_cartera WHERE " . $cond . " ORDER BY cart_id";

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
            'key' => 'cart_id',
        ]);

        return $dataProvider;

	}*/

	/**
	 * Función que permite el ingreso de un nuevo cheque cartera.
	 * @return integer 0. En caso de error - 1. En caso de success
	 */
	public function nuevoChequeCartera()
	{
		$this->addErrors( $this->validarChequeCartera() );

		if ( $this->hasErrors() )
			return 0;

		$error = [];

		$sql = "select count(*) from caja_cheque_cartera where nrocheque='" . $this->cheque . "' and bco_ent= " . $this->banco;

		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		if ( $count > 0 )
		{
			$error[] = 'Ya existe un cheque en cartera con el mismo número y entidad bancaria';

		} else
		{
			$sql = "select count(*) from plan where plan_id in (" . $this->convenio1 . "," . $this->convenio2 . ") and est=1";

			$count = Yii::$app->db->createCommand($sql)->queryScalar();

			if ($count == 0)
			{
				$error[] = 'No existe un plan vigente con el número ingresado';

			} else
			{
				$this->cart_id = Yii::$app->db->createCommand("select nextval('seq_caja_cheque_cartera')")->queryScalar();

		        $sql = "insert into caja_cheque_cartera values(" . $this->cart_id . ",'" . $this->cheque . "'," . $this->monto . ",";
		        $sql .= $this->banco . "," . $this->sucursal . ",'" . $this->cuenta . "','" . $this->titular . "'," . $this->convenio1 . "," . $this->convenio2 . ",'C',";
		        $sql .= "current_date,'" . Fecha::usuarioToBD($this->fechaCobro) . "',current_timestamp," . Yii::$app->user->id . ")";

		        try
		        {
		        	$count = Yii::$app->db->createCommand($sql)->execute();

		        } catch (\Exception $e)
		        {
		        	$error[] = DBException::getMensaje($e);
		        }
			}
		}

        $this->addErrors( $error );

        return 1;

	}

	/**
	 * Función que modifica un cheque en cartera
	 */
	public function modificarChequeCartera()
	{
		$this->addErrors( $this->validarChequeCartera() );

		if ( $this->hasErrors() )
			return 0;

		$error = [];

		$sql = "select count(*) from caja_cheque_cartera where nrocheque='" . $this->cheque . "' and bco_ent= " . $this->banco . " and cart_id <> " . $this->cart_id;

        $count = Yii::$app->db->createCommand($sql)->queryScalar();

        if ( count > 0 )
        {
			$error[] = 'Ya existe un cheque en cartera con el mismo número y entidad bancaria';

        } else
        {
        	$sql = "update caja_cheque_cartera set nrocheque='" . $this->cheque . "',Monto=" . $this->monto . ",";
	        $sql .= "bco_ent= " . $this->banco . ",bco_suc=" . $this->sucursal . ",bco_cta='" . $this->cuenta . "',";
	        $sql .= "titular=UPPER('" . $this->titular . "'),plan_id= " . $this->convenio1 . ",plan_id2= " . $this->convenio2 . ",fchcobro='" . Fecha::usuarioToBD($this->fechaCobro) . "',";
	        $sql .= "FchMod=current_timestamp,UsrMod=" . Yii::$app->user->id;
	        $sql .= " where cart_id=" . $this->cart_id;

	        try
	        {
	        	$count = Yii::$app->db->createCommand($sql)->execute();

	        } catch (\Exception $e)
	        {
	        	$error[] = DBException::getMensaje($e);
	        }
        }

        $this->addErrors( $error );

        return 1;
	}

	/**
	 * Función que elimina un cheque en cartera
	 */
	public function eliminarChequeCartera()
	{

        $sql = "Update caja_cheque_cartera set est= 'B' where cart_id=" . $this->cart_id;

        try
        {
        	$count = Yii::$app->db->createCommand($sql)->execute();

			return '';

        } catch (\Exception $e)
        {
        	return DBException::getMensaje($e);
        }
	}

	/**
	 * Función que se utiliza para cargar los datos de Cheque Cartera en el modelo
	 */
	public function cargarChequeCartera($id)
	{
		$sql = "SELECT b.cart_id,b.nrocheque,b.monto,b.bco_ent,b.bco_suc,b.bco_cta,b.titular,b.plan_id,b.plan_id2,b.est,b.fchalta,b.fchcobro,b.bco_ent_nom,c.nombre as bco_suc_nom" .
				" FROM v_caja_cheque_cartera b, banco c WHERE cart_id="  .$id . " AND b.bco_ent = c.bco_ent AND b.bco_suc = c.bco_suc";

        $data = Yii::$app->db->createCommand($sql)->queryAll();


		 $this->convenio1 = $data[0]['plan_id'];
		 $this->convenio2 = $data[0]['plan_id2'];
		 $this->banco = $data[0]['bco_ent'];
		 $this->bancoNom = $data[0]['bco_ent_nom'];
		 $this->sucursal = $data[0]['bco_suc'];
		 $this->sucursalNom = $data[0]['bco_suc_nom'];
		 $this->cuenta = $data[0]['bco_cta'];
		 $this->titular = $data[0]['titular'];
		 $this->cheque = $data[0]['nrocheque'];
		 $this->fechaCobro = Fecha::bdToUsuario( $data[0]['fchcobro'] );
		 $this->monto = $data[0]['monto'];
		 $this->cart_id = $data[0]['cart_id'];
		 $this->cheque_cartera_est = $data[0]['est'];

	}

	/**
	 * Función que valida los datos que ingresarán a la Base de Datos
	 */
	public function validarChequeCartera()
	{

		$error = [];

		if ($this->convenio2 == '')
			$this->convenio2 = 0;

		$sql = "select exists (select 1 from plan where plan_id= " . $this->convenio1 . " and est = 1 and tpago = 2)";

		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		if ($count == 0)
			$error[] = 'Ingrese un plan válido.';


		if ($this->convenio2 != 0)
		{
			$sql = "select exists (select 1 from plan where plan_id= " . $this->convenio2 . " and est = 1 and tpago = 2)";

			$count = Yii::$app->db->createCommand($sql)->queryScalar();

			if ($count == 0)
				$error[] = 'Ingrese un plan2 válido.';

		}

		return $error;
	}
	
		
	//-----------------------------------AUXILIARES-------------------------------------------------------//

	public function getObjeto()
	{
		// Obtengo el número de Objeto - Correlativo x Año
        $objeto = Yii::$app->db->createCommand("Select coalesce(substring(max(Obj_Id),2),'0') From ctacte Where Trib_Id=12 and Anio=" . date('Y'))->queryScalar();

        $obj_id = intval($objeto) + 1;
        $objeto = "_" . sprintf("%'.07d\n", $obj_id);

		return (string)$objeto;
	}

}
