<?php

namespace app\models\ctacte;

use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Session;
use app\utils\db\Fecha;
use app\utils\db\utb;

/**
 * This is the model class for table "ctacte_pagocta".
 *
 * @property integer $pago_id
 * @property integer $trib_id
 * @property string $obj_id
 * @property integer $subcta
 * @property integer $anio
 * @property integer $cuota
 * @property string $monto
 * @property string $est
 * @property string $obs
 * @property string $fchlimite
 * @property string $fchpago
 * @property string $fchmod
 * @property integer $usrmod
 *
 *
 *
 * @property string $obj_nom
 */
class Pagocta extends \yii\db\ActiveRecord
{
	public $obj_nom;
	public $est_nom;
	public $ttrib;
	public $trib_tobj;
	public $tobj;
	public $usa_subcta;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ctacte_pagocta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trib_id', 'obj_id', 'subcta', 'anio', 'cuota', 'monto', 'est', 'obs', 'fchlimite'], 'required'],
            [['trib_id', 'subcta', 'anio', 'cuota', 'usa_subcta', 'ttrib', 'trib_tobj', 'tobj'], 'integer'],
            [['monto'], 'number'],
            [['fchlimite', 'fchpago'], 'safe'],
            [['obj_id','est_nom'], 'string', 'max' => 8],
            [['est'], 'string', 'max' => 1],
            [['obs'], 'string', 'max' => 250],
			[['obj_nom'], 'string' ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pago_id' => 'Código de pago',
            'trib_id' => 'Código de tributo',
            'obj_id' => 'Código de objeto',
            'subcta' => 'Código de subcuenta',
            'anio' => 'Año',
            'cuota' => 'Cuota',
            'monto' => 'Monto',
            'est' => 'Estado',
            'obs' => 'Observación',
            'fchlimite' => 'Fecha límite',
            'fchpago' => 'Fecha de pago',
            'fchmod' => 'Fecha de modificación',
            'usrmod' => 'Código de usuario que modificó',
        ];
    }

    /**
     * Función que se encarga de obtener losd atos extras para el modelo
     */
    public function cargarDatos($id)
    {
    	$sql = "SELECT obj_nom,to_char(fchlimite,'dd/MM/yyyy') as fchlimite, " .
    			"usrmod_nom || ' - ' || to_char(fchmod,'dd/mm/yyyy') as usrmod " .
    			"FROM v_ctacte_pagocta " .
    			"WHERE pago_id = " . $id;

    	$data = Yii::$app->db->createCommand($sql)->queryAll();

    	$this->est_nom = utb::getCampo('ctacte_test',"cod = '" . $this->est . "'", 'nombre' );

    	$this->obj_nom = $data[0]['obj_nom'];
    	$this->fchlimite = $data[0]['fchlimite'];
    	$this->usrmod = $data[0]['usrmod'];

    }

    /**
     * Función que se encarga de obtener los datos para la grilla
     */
    public function cargarDatosGrilla($id)
    {
    	$sql = "SELECT cta_id,cta_nom,monto " .
				"FROM v_ctacte_pagocta_det " .
				"WHERE pago_id = " . $id . " " .
				"ORDER BY cta_id,monto";

 		$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
        ]);

    	return $dataProvider;
    }

	/**
	 * Función quese utiliza para obtener los tributos.
	 * @param string $cond Condición de búsqueda.
	 */
	public static function getTributos( $cond ){

		return utb::getAux( 'trib', 'trib_id', 'nombre', 1, $cond );
	}

	public static function getTiposObjeto(){

		return utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0 );
	}

	/**
	 * Función que se utiliza para agregar un elemento al arreglo de cuentas.
	 */
	public static function abmCuentaAArreglo( $arrayCuentas, $action, $cuenta_id, $cuenta_nom, $monto ){

		$arreglo = $arrayCuentas;

		switch( $action ){

			case 0:	//Agregar una cuenta

				//Crea un arreglo temporal
				$arregloTemporal = [
						'cta_id' 	=> $cuenta_id,
						'cta_nom' 	=> $cuenta_nom,
						'monto'		=> number_format( $monto, 2, '.', '' ),
				];

				$array = [];

				$array[ $cuenta_id ] = $arregloTemporal;

				//Elimina el elemento que intenta agregar si ya se encuentra en el arreglo
				unset( $arreglo[ $cuenta_id ] );

				$arreglo = $array;


				break;

			case 2:	//Eliminar una cuenta

				//Elimina el elemento que intenta agregar si ya se encuentra en el arreglo
				unset( $arreglo[ $cuenta_id ] );

				break;

		}

		return $arreglo;
	}

	/**
	 * Función que se utiliza para obtener el monto total de las cuentas.
	 * @param array $arregloCuentas Arreglo de cuentas.
	 * @return double Monto total de cuentas.
	 */
	public static function obtenerMontoArregloCuentas( $arregloCuentas ){

		$monto = 0;

		if( count( $arregloCuentas ) > 0 ){

			foreach ( $arregloCuentas as $ar )
			{
				$monto += $ar['monto'];
			}

		}

		return $monto;

	}

	/**
	 * Función que se utiliza para determinar el tipo de pago según los datos ingresados.
	 * @param
	 * @param
	 * @param
	 * @param
	 * @return integer Valor que indica el tipo de pago.
	 *		0 -> No existe
	 *		1 -> Pago Parcial
	 *		2 -> Pago a Cuenta
	 */
	public static function obtenerTipoPago( $trib_id, $obj_id, $anio, $cuota ){

		$existe = 0;

		$ttrib = utb::getCampo( 'trib', 'trib_id = ' . $trib_id, 'tipo' );

		//Si tipo es emisión, declarativo o periódico => Pago a Cuenta o Pago Parcial
		if( in_array( $ttrib, [ 1, 2, 4 ] ) ){

			//Verificar que el período ingresado exista en la tabla de cuenta corriente.
			$res = Pagocta::validarPeriodoCtaCte( $trib_id, $obj_id, $anio, $cuota );

			if( !$res ){
				$existe = 2;	//Pago a Cuenta
			} else {

				//Verificar que exista el período en la tabla de Vencimientos
				$res = Pagocta::validarPeriodoVenc( $trib_id, $anio, $cuota );

				if( $res ){
					$existe = 1;	//Pago Parcial
				}
			}

		} else {

			//Verificar que el período ingresado exista en la tabla de cuenta corriente
			$res = Pagocta::validarPeriodoCtaCte( $trib_id, $obj_id, $anio, $cuota );

			if( $res ){
				$existe = 1;	//Pago Parcial
			}
		}

		return $existe;

	}

	public static function getEstadoCtaCte( $trib, $obj_id, $subcta, $anio, $cuota ){

		//Ctacte.est IN ('T','D')
		$cond = "trib_id = " . $trib . " AND obj_id = '" . $obj_id . "' AND subcta = " . $subcta . " AND anio = " . $anio . " AND cuota = " . $cuota;

		return utb::getCampo('ctacte',$cond,'est');

	}

	public static function getFechaVencimiento( $trib, $obj_id, $subcta, $anio, $cuota ){

		//Ctacte.est IN ('T','D')
		$cond = "trib_id = " . $trib . " AND obj_id = '" . $obj_id . "' AND subcta = " . $subcta . " AND anio = " . $anio . " AND cuota = " . $cuota;

		return utb::getCampo('ctacte',$cond,"to_char(fchvenc,'dd/MM/yyyy') as fchvenc");

	}

    public function consultaCuenta( $trib_id = 0 )
    {
    	$sql = "select distinct c.cta_id,c.nombre_redu	" .
    			"FROM item i inner join cuenta c on i.cta_id=c.cta_id " .
    			"where trib_id = ". intVal( $trib_id ) .
    			" order by c.cta_id,c.nombre_redu";

    	try{
			$cmd = Yii::$app->db->createCommand($sql);
			$res = $cmd->queryAll();

			$arreglo = ArrayHelper::map($res, 'cta_id', 'nombre_redu');
		}
		catch(Exception $e){
			$arreglo = [];
		}

		return $arreglo;
    }

    /**
     * Función que verifica si un período dado existe en la tabla trib_venc
     * @param integer $trib_id Identificador de tributo
     * @param integer $anio Año
     * @param integer $cuota Cuota
     */
    public static function validarPeriodoVenc($trib_id,$anio,$cuota)
    {
    	$sql = "SELECT exists (SELECT 1 FROM trib_venc WHERE trib_id = " . $trib_id . " AND anio = " . $anio . " AND cuota = " . $cuota . ")";

    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    /**
     * Función que verifica si un período dado existe en la tabla ctacte
     * @param integer $trib_id Identificador de tributo
     * @param string $obj_id Id de objeto
     * @param integer $anio Año
     * @param integer $cuota Cuota
     */
    public function validarPeriodoCtaCte($trib_id,$obj_id,$anio,$cuota)
    {
        $sql = "SELECT exists (SELECT 1 FROM ctacte WHERE trib_id = " . $trib_id . " and obj_id='" . $obj_id . "' AND anio = " . $anio . " AND cuota = " . $cuota . ")";

    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    /**
     * Función
     * @param integer $trib_id Identificador de tributo
     * @param string $obj_id Identificador de objeto
     * @param integer $anio Año
     * @param integer $cuota Cuota
     * @param double $montoParcial Monto Parcial
     * @param string $fchvenc Fecha de Vencimiento
     * @param string $fchcaja Fecha de Caja
     */
    public static function cargarCuenta($trib_id,$obj_id,$anio,$cuota,$montoParcial,$fchvenc,$fchcaja)
    {
		$sql = "SELECT ctacte_id FROM ctacte WHERE trib_id=" . $trib_id . " and obj_id='" . $obj_id . "' and anio=" . $anio . " and cuota=" . $cuota;

    	$ctacte_id = Yii::$app->db->createCommand( $sql )->queryScalar();

    	$sql = "select 0,cta_id,cta_nom,monto From sam.Uf_CtaCte_PagoParcial(" . $ctacte_id . ",";
        $sql .= floatVal( $montoParcial ) . ",'" . Fecha::usuarioToBD($fchvenc) . "','" . Fecha::usuarioToBD($fchcaja) . "')";

		return Yii::$app->db->createCommand( $sql )->queryAll();

    }

    /**
     * Función que se utiliza para comprobrar si existe pagoAnual
     * @param integer $trib_id Identificador de tributo
     * @param string $obj_id Identificador de objeto
     * @param integer $anio Año
     */
    private function existePagoAnual($trib_id,$obj_id,$anio)
    {
    	$sql = "SELECT exists (SELECT 1 from ctacte WHERE trib_id = " . $trib_id . " and obj_id='" . $obj_id . "' AND anio = " . $anio . " AND cuota = 0 AND est = 'P')";

		return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    /**
     * Función que se utiliza para comprobrar si existe Período Judicial
     * @param integer $trib_id Identificador de tributo
     * @param string $obj_id Identificador de objeto
     * @param integer $anio Año
     * @param integer $cuota Cuota
     */
    private function existePerJudConv($trib_id,$obj_id,$anio,$cuota)
    {
    	$sql = "SELECT exists (SELECT 1 from ctacte WHERE trib_id = " . $trib_id . " and obj_id='" . $obj_id . "' AND anio = " . $anio . " AND cuota = " . $cuota . " AND est IN ('J','C'))";

		return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    /**
     * Función que se utiliza para comprobrar si existe Emisión DJ
     * @param integer $trib_id Identificador de tributo
     * @param string $obj_id Identificador de objeto
     * @param integer $anio Año
     * @param integer $cuota Cuota
     */
    public static function existeEmiDJ($trib_id,$obj_id,$anio,$cuota)
    {
    	$ttrib = utb::getTTrib($trib_id);

    	$sql = "SELECT exists (SELECT 1 from ctacte WHERE trib_id = " . $trib_id . " and obj_id='" . $obj_id . "' AND anio = " . $anio . " AND cuota = " . $cuota . " AND est <> 'X')";

		return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    /**
     * Función que se utiliza para grabar un Pago.
     */
     public function grabarPagocta( $arregloCuentas = [] )
     {
     	$ttrib = utb::getTTrib($this->trib_id);

     	if ($this->subcta == '')
     		$this->subcta = 0;

     	if ($ttrib != 3)
     	{
     		if ($this->trib_id != 1 && $this->trib_id != 3 && Pagocta::validarPeriodoVenc($this->trib_id,$this->anio,$this->cuota) == 0)
     			return 1004;

     		if ($this->trib_id != 1 && $this->trib_id != 3 && $this->existePagoAnual($this->trib_id,$this->obj_id,$this->cuota) == 1)
     			return 1005;

     	}

     	if ($this->existePerJudConv($this->trib_id,$this->obj_id,$this->anio,$this->cuota) == 1)
     		return 1006;

		//Validar que exista algún elemento en la grilla
		if( floatVal( Pagocta::obtenerMontoArregloCuentas( $arregloCuentas ) ) == 0 ){
			return 1008;
		}

		$transaction = Yii::$app->db->beginTransaction();

     	try {

     		$this->pago_id = Yii::$app->db->createCommand("select nextval('seq_ctacte_pagocta')")->queryScalar();

			$vtotal = 0;

			foreach ( $arregloCuentas as $array )
			{
				$sql = "insert into ctacte_pagocta_det values(" . $this->pago_id . "," . $array["cta_id"] . "," . $array["monto"] . ")";
                Yii::$app->db->createCommand($sql)->execute();
				$vtotal += $array["monto"];
			}

			if (!is_numeric($this->monto))
				$this->monto = $vtotal;

     		$sql = "INSERT INTO ctacte_pagocta values(" . $this->pago_id . "," . $this->trib_id . ",'" . $this->obj_id . "',";
            $sql .= $this->subcta . "," . $this->anio . "," . $this->cuota . "," . $this->monto . ",'D','";
            $sql .= $this->obs . "','" . Fecha::usuarioToBD($this->fchlimite) . "','" . Fecha::getDiaActual() . "'";
            $sql .= ",current_timestamp," . Yii::$app->user->id . ")";

			Yii::$app->db->createCommand($sql)->execute();

			$transaction->commit();

			return true;

     	} catch (Exception $e)
     	{
			$transaction->rollback();

     	}

		return 1007;

     }

     /**
      * Función que se ejecuta para dar de baja un pago de la BD
      */
     public function eliminarPagoCta($id)
     {

     	$est = Yii::$app->db->createCommand("SELECT est FROM ctacte_pagocta WHERE pago_id = " . $id)->queryScalar();

     	if ($est != 'D')
     	{
     		return "Ocurrió un error al dar de baja el pago.";
     	} else
     	{
     		Yii::$app->db->createCommand("UPDATE ctacte_pagocta set est = 'B' WHERE pago_id = " . $id)->queryScalar();
     	}

     	return "";

     }

     /**
      * Función que se ejecuta para dar de baja los pagos vencidos en la BD
      */
     public function eliminarPagoCtaVencida()
     {
     	if (utb::getExisteProceso(3333) == 1)
     	{
     		Yii::$app->db->createCommand("UPDATE ctacte_pagocta set est = 'B' WHERE fchlimite > current_date")->queryScalar();
     	} else
     	{
     		return "No posee los privilegios para realizar esta acción.";
     	}

     	return "";

     }

 	/**
	 * Función que busca los datos de la BD para la lista avanzada de Pagocta.
	 * @param string $cond Condición de búsqueda
	 * @param string $orden Orden de la búsqueda
	 * @return dataProvider DataProvider con el resultado de la búsqueda
	 */
    public function buscaDatosListado($cond,$orden)
    {
    	$sql = "SELECT pago_id,trib_nom,obj_id,substr(obj_nom,0,35) as obj_nom,subcta,anio,cuota,monto,to_char(fchlimite,'dd/MM/yyyy') as fchlimite,to_char(fchpago,'dd/MM/yyyy') as fchpago,est " .
    			"FROM v_ctacte_pagocta WHERE " . $cond . " ORDER BY " . $orden;

    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_ctacte_pagocta WHERE " . $cond)->queryScalar();

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'pago_id',
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>50,
			],
        ]);

        return $dataProvider;
    }

    public function Imprimir($id,&$sub1)
    {
    	$sql = "select *,to_char(fchlimite,'dd/mm/yyyy') fchlimite from v_ctacte_pagocta_print where pago_id=".$id;
   		$array = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "select * from v_ctacte_pagocta_det where pago_id=".$id;
   		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

   		return $array;
    }
}
