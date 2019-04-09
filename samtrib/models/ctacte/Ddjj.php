<?php

namespace app\models\ctacte;

use Yii;
use yii\web\Session;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\db\Fecha;
use app\utils\helpers\DBException;
use app\models\ctacte\RetencionDetalle;

/**
 * This is the model class for table "ddjj".
 *
 * @property integer $dj_id
 * @property integer $dj_id_web
 * @property integer $ctacte_id
 * @property integer $trib_id
 * @property string $obj_id
 * @property integer $subcta
 * @property integer $fiscaliza
 * @property integer $anio
 * @property integer $cuota
 * @property integer $orden
 * @property integer $tipo
 * @property string $base
 * @property string $anual
 * @property string $anticipos
 * @property string $monto
 * @property string $multa
 * @property string $fchpresenta
 * @property string $est
 * @property string $error_act
 * @property string $fchmod
 * @property integer $usrmod
 *
 *
 * Datos para la retención
 * @property string $rete_cuit
 * @property string $rete_obj_id
 * @property string $rete_denominacion
 * @property integer $rete_lugar
 * @property string $rete_fecha
 * @property integer $rete_numero
 * @property integer $rete_tcomprob
 * @property integer $rete_comprob
 * @property double $rete_base
 * @property double $rete_ali
 * @property double $rete_monto
 * @property integer $rete_agente
 */

class Ddjj extends \yii\db\ActiveRecord
{

	public $trib_nom; 	//Nombre del tributo
	public $orden_nom;
	public $usa_subcta;	//Variable que determina si se utiliza subcuenta o no.
	public $obj_nom;	//Nombre del objeto
	public $fchvenc;	//Fecha de vencimiento
	public $total_base;
	public $total_monto;
	public $total_multa;
	public $estado;

	public $ib;
	public $cuit;

	//Variables para retención
	public $rete_cuit;
	public $rete_obj_id;
	public $rete_denominacion;
	public $rete_lugar;
	public $rete_fecha;
	public $rete_numero;
	public $rete_tcomprob;
	public $rete_comprob;
	public $rete_base;
	public $rete_ali;
	public $rete_monto;
	public $rete_agente;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ddjj';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dj_id_web', 'ctacte_id', 'trib_id', 'subcta', 'fiscaliza', 'anio', 'cuota', 'orden', 'tipo', 'usrmod','usa_subcta'], 'integer'],
            [['ctacte_id', 'trib_id', 'obj_id', 'anio', 'cuota', 'orden', 'tipo', 'anual', 'anticipos', 'monto', 'multa', 'fchpresenta', 'est', 'usrmod'], 'required'],
            [['base', 'anual', 'anticipos', 'monto', 'multa'], 'number'],
            [['fchpresenta', 'fchmod'], 'safe'],
            [['obj_id'], 'string', 'max' => 8],
            [['est'], 'string', 'max' => 1],
            [['error_act'], 'string', 'max' => 20],
			[['obj_nom', 'ib'],'string'],

			//Variables de retención
			[['rete_cuit', 'rete_obj_id', 'rete_denominacion', 'rete_fecha', 'orden_nom'],'string'],
			[['rete_lugar', 'rete_numero', 'rete_tcomprob', 'rete_comprob','rete_agente'],'integer'],
			[['rete_base', 'rete_ali', 'rete_monto'],'double'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dj_id' => 'Identificador de declaracion jurada',
            'dj_id_web' => 'Identificador de dj de web',
            'ctacte_id' => 'Identificador de cuenta corriente',
            'trib_id' => 'Codigo de tributo',
            'obj_id' => 'Codigo de objeto',
            'subcta' => 'Subcta',
            'fiscaliza' => 'Si es de fiscalizacion',
            'anio' => 'A�o',
            'cuota' => 'Cuota',
            'orden' => 'Numero de orden',
            'tipo' => 'Codigo de tipo de dj',
            'base' => 'Monto de base imponible',
            'anual' => 'Monto anual',
            'anticipos' => 'Monto de anticipos',
            'monto' => 'Monto',
            'multa' => 'Monto de multa',
            'fchpresenta' => 'Fecha de presentacion',
            'est' => 'Estado',
            'error_act' => 'Error de actualizacion',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

	public function __construct(){

		$this->usa_subcta = 0;
		$this->fchpresenta = date( 'd/m/Y' );

		$this->total_base 	= number_format( 0, 2, '.', '' );
		$this->total_monto 	= number_format( 0, 2, '.', '' );
	}

	public function afterFind(){

		$this->ib	= utb::getCampo( 'persona', "obj_id = '$this->obj_id'", 'ib' );
		$this->cuit 	= utb::getCampo( 'persona', "obj_id = '" . $this->obj_id ."'", "cuit" );
		if ($this->cuit <> "") $this->cuit     = substr($this->cuit,0,2) . "-" . substr($this->cuit,2,-1) . "-" . substr($this->cuit,-1,1);
	}

	/**
	 * Función que se utiliza para realizar el cambio de tributo.
	 * @param integer $id Identificador del tributo.
	 */
	public function setTributo( $id = 0 ){

		$this->trib_id = intVal( $id );

		$sql = "SELECT uso_subcta FROM trib WHERE trib_id = " . $this->trib_id;

		$this->usa_subcta = Yii::$app->db->createCommand( $sql )->queryScalar();

	}

	public static function permiteRetencionesSegunTributo( $trib_id ){

		//if( $trib_id == )
	}

	public static function getTributoDeclarativo(){

		$sql = 	"SELECT trib_id FROM trib WHERE tipo = 2";	// tipo 2 es "Declarativo"

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	/**
	 * Función que se utiliza para cargar los datos de la DJ.
	 */
	public function cargarDatosDJ( $verificaPeriodo, &$verificaAdeuda, &$verificaExistencia ){

		#Calcular el vencimiento
		$this->getFechaVenc();

		$this->orden = $this->getOrden();

		$this->setTributo( $this->trib_id );

		if( !$this->usa_subcta ){
			$this->subcta = 0;
		}

		//Si se valida que el contribuyente no tenga perídodos adeudados
		if( $verificaAdeuda ){

			#Verificar si no adeuda periodos
			$verificaAdeuda = $this->adeudaPeriodo();

		}

		if( $verificaAdeuda ){

			$verificaExistencia = 0;

		} else if( $verificaExistencia ){

			#Verificar si no adeuda periodos
			$verificaExistencia = $this->existePeriodo();

		}

		if( !$verificaExistencia ){

			if( $this->orden == 0 ){

				$this->orden_nom = 'Original';

			} else {

				$this->orden_nom = 'Rectif' . intVal( $this->orden );
			}

		}

	}

	/**
	 * Función que se utiliza para obtener los tributos.
	 */
	public static function getTributos(){

		$est = utb::getAux('trib','trib_id','nombre',0,"est = 'A' and trib_id in (select trib_id from sam.config_ddjj)");

		return $est;
	}

	/**
	 * Función que se utiliza para obtener el estado del objeto.
	 * @param string $obj_id Id de objeto.
	 */
	public static function getEstadoObjeto( $obj_id ){

		$est = utb::getCampo( 'objeto', "obj_id = '" . $obj_id . "'", "est" );

		return $est;
	}

	/**
	 * Función que se utiliza para validar que un objeto tenga rubros asociados.
	 */
	private function validarExistenciaRubros(){

		$sql =	"SELECT EXISTS(SELECT 1 FROM objeto_rubro r WHERE r.obj_id = '$this->obj_id') OR " .
				"EXISTS(SELECT 1 FROM objeto_rubro r INNER JOIN objeto o ON r.obj_id = o.obj_id WHERE o.tobj = 2 AND o.num = '$this->obj_id' )";

		//$sql =	"SELECT EXISTS( SELECT 1 FROM objeto_rubro WHERE obj_id = '$this->obj_id')";

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	/**
	 * Función que busca los datos de la BD para la lista avanzada de DJ.
	 * @param string $cond Condición de búsqueda
	 * @param string $orden Orden de la búsqueda
	 * @return dataProvider DataProvider con el resultado de la búsqueda
	 */
    public function buscaDatosListado( $cond, $orden = '' )
    {
    	$sql = "SELECT dj_id,trib_nom,obj_id,subcta,num_nom,est,anio,cuota,orden_nom,base,monto,multa,fchpresenta " .
    			"FROM v_dj WHERE " . $cond;

		return Yii::$app->db->createCommand( $sql )->queryAll();

    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_dj WHERE " . $cond)->queryScalar();

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'dj_id',
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>20,
			],
        ]);

        return $dataProvider;
    }

	/**
	 * Función que se utiliza para verificar si existe un vencimiento para el rubro y período ingresado.
	 * @param integer $trib_id Identificador del tributo.
	 * @param integer $anio Año ingresado.
	 * @param integer $cuota Cuota
	 * @return boolean true en caso de que exista el vencimiento. false de otro modo.
	 */
	private function existeVencimiento( $trib_id, $anio, $cuota ){

		$sql = 	"SELECT EXISTS( SELECT 1 FROM trib_venc WHERE trib_id = " . $trib_id . " AND anio = " . $anio . " AND cuota = " . $cuota . ")";

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	public function validarDatosAlCargar(){
		$this->subcta = (integer)$this->subcta;
		$sql = "select * from sam.uf_dj_validar($this->trib_id,'$this->obj_id',$this->subcta,$this->fiscaliza,$this->anio,$this->cuota)";
		$datos = Yii::$app->db->createCommand( $sql )->queryScalar();

		if ($datos != "") $this->addError( 'dj_id', $datos );

		if( $this->hasErrors() ){

			return false;
		}

		return true;
	}

	public function validarDatosAlCargar_Anterior(){

		#Validar que el objeto se encuentre activo
		$est = $this->getEstadoObjeto( $this->obj_id );

		if( $est != 'A' ){
			$this->addError( 'obj_id', 'El estado del objeto es incorrecto. Se encuentra dado de baja.' );
		}

		#Validar que el objeto tenga rubros asociados
		$existenRubros = $this->validarExistenciaRubros();

		if( !$existenRubros ){
			$this->addError( 'obj_id', 'El objeto ingresado no tiene rubros asociados.' );
		}

		#Validar que exista un vencimiento para el período ingresado
		$existeVencimiento = $this->existeVencimiento( $this->trib_id, $this->anio, $this->cuota );

		if( !$existeVencimiento ){
			$this->addError( 'dj_id', 'No existe vencimiento para el período ingresado.' );
		}

		#Validar que el período ingresado no sea mayor a la fecha actual
		if( $this->anio > date( 'Y' ) || ( $this->anio == date( 'Y' ) && $this->cuota > date( 'm' ) ) ){

			$this->addError( 'dj_id', 'El período ingresado es incorrecto.' );
		}

		#Verificar si se permiten ingresar DDJJ cuando haya DDJJ pendientes.
		if( !$this->getConfig_ddjj($this->trib_id)['perm_djfalta'] && $this->adeudaPeriodo() )
		{
			$this->addError( 'dj_id', 'Existen DDJJ faltantes.');
		}

		#Verificar si es convenio multilateral y puede presentar ddjj.
		if( !$this->getConfig_ddjj($this->trib_id)['cm_dj'] && $this->convenioMultilateral() )
		{
			$this->addError( 'dj_id', 'Es CM, no puede presentar DDJJ.');
		}

		#Verificar si es Acuerdo Interjurisdiccional y puede presentar ddjj.
		if( !$this->getConfig_ddjj($this->trib_id)['ai_dj'] && $this->acuerdoInterj() )
		{
			$this->addError( 'dj_id', 'Es AI, no puede presentar DDJJ.');
		}

		if( $this->hasErrors() ){

			return false;
		}

		return true;
	}

	/**
	Si es Convenio Multilateral
	*/
	private function convenioMultilateral()
	{
		$sql = "select tipoliq from persona where obj_id='" . $this->obj_id . "'";
		$cm = Yii::$app->db->createCommand($sql)->queryScalar();

		return $cm == "CM";
	}

	/**
	Si es AI
	*/
	private function acuerdoInterj()
	{
		$sql = "select tipoliq from persona where obj_id='" . $this->obj_id . "'";
		$ai = Yii::$app->db->createCommand($sql)->queryScalar();

		return $ai == "AI";
	}

	/**
	 * Función que obtiene la fecha de vencimiento al cargar una DDJJ
	 */
	private function getFechaVenc()
	{
		//Busco el vencimiento
        $sql = "Select coalesce(FchVenc1,'1900/01/01') From sam.Uf_Trib_Venc(";
        $sql .= $this->trib_id . "," . $this->anio . "," . $this->cuota . ",'" . $this->obj_id . "')";

		$fecha = Yii::$app->db->createCommand($sql)->queryScalar();

		if( $fecha == '' || $fecha == null ){

			$this->addError( 'fchvenc', 'No se encuentra definido el vencimiento para el período dado. ');
		}

        $this->fchvenc = Fecha::BDToUsuario( $fecha );

	}

	/**
	 * Función que se utiliza para determinar si se hay períodos en los que no se ingresó dj.
	 */
	private function adeudaPeriodo(){

		#Obtener el período que se debe declarar
		$per = $this->getPeriodoADeclarar();

		if( intVal( $per ) < intVal( ( ( $this->anio * 1000 ) + $this->cuota ) ) ){
			return true;
		}

		return false;

	}

	/**
	 * Función que verifica si no existe una DDJJ en la BD con el obj_id y periodo ingresado
	 * @return string Fecha de vencimiento calculada
	 */
	private function existePeriodo()
	{
		//Obtengo subcta
		//$subcta = utb::getCampo( 'trib','trib_id = ' . $trib,'uso_subcta');

		$sql = 	"SELECT exists (SELECT 1 FROM DDJJ WHERE Trib_id = " . $this->trib_id . " AND obj_id = '" . $this->obj_id . "'" .
				" AND subcta = " . intVal( $this->subcta ) . " AND anio = " . $this->anio . " AND cuota = " . $this->cuota . " AND est = 'A')";

		return Yii::$app->db->createCommand($sql)->queryScalar();

	}

	/**
	 * Función que se utiliza para verificar que el objeto que se desea cargar existe.
	 * @param integer $trib_id Identificador de tributo.
	 * @param string $obj_id Identificador de tipo de objeto.
	 */
	public function validarObjetoYCargar( $trib_id = 0, $obj_id = '' ){

		$tobj = utb::getTObjTrib( intVal( $trib_id ) );

		if( $obj_id != '' ){
			$obj_id = utb::getObjeto( $tobj, $obj_id );

			if( utb::verificarExistenciaObjeto( $tobj, "'" . $obj_id . "'" ) ){

				$this->obj_id = $obj_id;
			} else {

				$this->addError( 'obj_id', 'El objeto ingresado no existe.' );
			}
		}

	}

	/**
	 * Función que se utiliza para cargar los datos del período que se debe declarar.
	 */
	public function cargarPeriodoADeclarar(){

		$this->obj_nom 	= utb::getCampo( 'objeto', "obj_id = '" . $this->obj_id ."'", "nombre" );
		$this->ib 		= utb::getCampo( 'persona', "obj_id = '" . $this->obj_id ."'", "ib" );
		$this->cuit 	= utb::getCampo( 'persona', "obj_id = '" . $this->obj_id ."'", "cuit" );
		if ($this->cuit <> "") $this->cuit     = substr($this->cuit,0,2) . "-" . substr($this->cuit,2,-1) . "-" . substr($this->cuit,-1,1);

		$data = $this->getPeriodoADeclarar();

		$this->anio 	= substr( $data, 0, 4 );
		$this->cuota 	= intVal( substr( $data, 4, 3 ) );

	}

	/**
	 * Función que obtiene el siguiente período a declarar.
	 */
	private function getPeriodoADeclarar(){

		$sql = "select * from sam.uf_dj_periodo(" . $this->trib_id . ",'" . $this->obj_id . "')";
		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	/**
     * Función que se utiliza para generar la grilla con los rubros y
     * tributos del comercio seleccionado.
     */
    public function getRubrosAndTributos( $arrayCodigo = [], $arrayBase = [], $arrayCant = [] )
    {
		//Inicializar variables
		$base = 0;
		$cant = 0;

        //Genero el arreglo que mantendrá los datos
        $data = [];

        //Obtener el tipo de objeto
        $tobj = utb::getTObj( $this->obj_id );

		$sql = "SELECT $this->trib_id trib_id,nomen_nom,rubro_id as cod, rubro_nom as nombre,cant FROM sam.uf_objeto_rubro(".$this->trib_id.",'".$this->obj_id."',". intVal( $this->subcta ) ."," . $this->fiscaliza . ",".$this->anio.",".$this->cuota.")";

        $rubros = Yii::$app->db->createCommand( $sql )->queryAll();

        //Generar un arreglo formado por tributo y rubro
        foreach( $rubros as $rub )
        {
			$base = 0;
			$cant = $rub['cant'];

            /*
			 *	Base y Cantidad se obtienen del arreglo en caso de existir, sino se pasa 0
			 *	La posición del arreglo se obtiene según el arreglo $arrayCodigo que contiene los ID
			 */
			if ( count( $arrayCodigo ) > 0 && count( $arrayBase ) > 0 )
			{
				$clave = $rub['trib_id'].'-'.$rub['cod'];

				foreach ( $arrayCodigo as $valor => $array )
				{
					if ( $array == $clave )
					{
						//Obtener base y cantidad
						$base = $arrayBase[$valor];
						$cant = $arrayCant[$valor];
					}
				}
			}

            //Realizo el cálculo del rubro - Base y Cantidad se cargan con 0
    		$array = $this->calculaRubro($rub['cod'],$this->trib_id,$base,$cant,$this->anio,$this->cuota,$this->fchpresenta,$this->obj_id,$this->subcta);

            //Obtengo los datos de la consulta
            $formCalculo = $array['dataRubro']['tcalculo_nom'];	//Fórmula de Cálculo
            $tminimo = $array['dataRubro']['tminimo'];
            $alicuota = ($array['dataRubro']['alicuota']) * 100;
            $minimo = $array['dataRubro']['minimo'];
            $monto = $array['dataMonto']['montorubro'];
            $monto = number_format( round($monto * 100) / 100 , 2, '.', '' );

            $data[$this->trib_id.'-'.$rub['cod']] = [
                'trib_id' => $this->trib_id,
				'nomen_nom' => $rub['nomen_nom'],
                'rubro_id' => $rub['cod'],
                'rubro_nom' => $rub['nombre'],
                'formCalculo' => $array['dataRubro']['tcalculo_nom'],	//Fórmula de Cálculo
                'base' => number_format( $base, 2, '.', '' ),
                'cant' => $cant,
                'tminimo' => $array['dataRubro']['tminimo'],
                'alicuota' => ($array['dataRubro']['alicuota']) * 100,
                'minimo' => $array['dataRubro']['minimo'],
                'monto' => $array['dataMonto']['montorubro'],
                'monto' => number_format( round($monto * 100) / 100 , 2, '.', '' ),
            ];
        }

        return $data;

    }

	/**
	 * Función que realiza el cálculo de la DJ
	 * @param integer $djRub_id Identificador para DJ.
	 * @param integer $aplicaBonificacion Indica si se aplica bonificación al contribuyente.
	 * @param double $saldoAFavor Contiene el saldo a favor ingresado por el usuario.
	 * @param array $retenciones Contiene las retenciones seleccionadas por el usuario
	 */
	public function calcularDJ( $djRub_id, $rubros, &$arrayRetenciones, $aplicaBonificacion = 0, $saldoAFavor = 0, $retenciones = [] )
	{
		$stringRetenciones = '';

		if( count( $retenciones ) > 0 ){

			$stringRetenciones = ",Array[";

			$stringRetenciones .= implode( ",", $retenciones );

			$stringRetenciones .= "]";
		}

		if( count( $arrayRetenciones ) > 0 ){

			foreach( $arrayRetenciones as &$array ){

				$array['activo'] = 0;

				foreach( $retenciones as $rete ){

					if( $rete == $array['ret_id'] ){
						$array['activo'] = 1;
					}
				}
			}
		}

		//Borro todo lo que tengo en la tabla TEMP de la BD
		$this->borrarRubrosTemp( $djRub_id ); //Le paso el ID de la tabla TEMP que se está usando en esta sesión

		//Grabo todo lo que tengo en el arreglo temporal en la tabla TEMP
		$this->grabarRubrosTemp( $djRub_id, $rubros );


		try
		{
			$sql = "Select * From sam.uf_DJ_Calcular(" . $this->trib_id . ",'" . $this->obj_id . "'::text," . $this->fiscaliza . "," .
					$this->anio . "," . $this->cuota . "," . $this->getOrden() . "," . Fecha::usuarioToBD( $this->fchpresenta, 2 ) . "::date," .
					$djRub_id . "," . intVal( $aplicaBonificacion ). "," . $saldoAFavor . $stringRetenciones . ")";
			
			$resul = Yii::$app->db->createCommand($sql)->queryAll();
			
		} catch (\Exception $e)
		{
			$this->addError( 'dj_id', DBException::getMensaje( $e ) );

			return [];
		}
		
		
		return $resul;
	}

	public function obtenerMontosDJ( $arrayRubros, $arrayItems ){

		$baseTotal = 0;
		$montoTotal = 0;

		//Calculo Base
		if ( count( $arrayRubros ) > 0 )
		{
			foreach ($arrayRubros as $rubros)
			{
				$baseTotal += $rubros['base'];
			}
		}

		//Calculo Monto
		if ( count( $arrayItems ) > 0 )
		{
			foreach ($arrayItems as $items)
			{
				$montoTotal += $items['item_monto'];
			}
		}

		$this->total_base 	= number_format( $baseTotal, 2, '.', '' );
		$this->total_monto 	= number_format( $montoTotal, 2, '.', '' );
	}

	/**
	 * Función que se utiliza para obtener el siguiente ID de una DJ temporal.
	 */
	public static function getNextID()
	{
		return Yii::$app->db->createCommand( "SELECT nextval('temp.seq_ddjj_rubros')" )->queryScalar();
	}

	/**
	 * Función que se utiliza para devolver los datos de configuración de DDJJ.
	 */
	public function getConfig_ddjj($trib = 0){

		//return utb::samConfig_ddjj($this->trib_id);
		//return utb::samConfig_ddjj(74);
		return utb::samConfig_ddjj($trib);
	}

	public function getOrden()
	{
		//Busco el máximo orden correspondiente según parámetros
		$sql = "Select Coalesce(max(Orden),-1) From DDJJ Where Trib_id=" . $this->trib_id;
		$sql .= " and Obj_id='" . $this->obj_id . "' And Anio=" . $this->anio . " And Cuota=" . $this->cuota . " and Est<>'B'";

		return Yii::$app->db->createCommand($sql)->queryScalar() + 1;
	}

	/**
	 * Función que se encarga de eliminar todos los datos que haya en la tabla TEMP correspondientes
	 * a la DJ que se está armando.
	 * @param integer
	 */
	public function borrarRubrosTemp($id)
	{
		if ($id != '')
		{
			$sql = "DELETE FROM temp.ddjj_rubros WHERE djrub_id = " . $id;

			Yii::$app->db->createCommand($sql)->execute();
		}

	}

	/**
	 * Función que se encarga de grabar en la tabla TEMP de rubros los datos que se encuentran en memoria
	 * @param integer $djRub_id Identificador que se usa en la tabla TEMP
	 */
	public function grabarRubrosTemp( $djRub_id, $arreglo )
	{

		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			foreach ($arreglo as $array)
			{
				$sql = "Insert Into temp.DDJJ_Rubros Values (" . $djRub_id . ",'";
				$sql .= $array['rubro_id'] . "'," . $array['base'] . ",";
				$sql .= $array['cant'] . "," . $array['minimo'] . ",";
				$sql .= $array['alicuota'] . "," . $array['monto'] . ")";

				Yii::$app->db->createCommand($sql)->execute();

			}

		} catch (\Exception $e)
		{
			$transaction->rollback();

			$this->addError( 'dj_id', DBException::getMensaje( $e ) );

			return false;
		}

		$transaction->commit();

		return true;

	}

	/**
	 * Función que se utiliza para actualizar la grilla de retenciones.
	 * @param array $arrayAnterior Arreglo con las retenciones anteriores al agregar la nueva retención.
	 * @param array $arrayNuevo Arreglo con las retenciones posteriores al agregar la nueva retención.
	 */
	public static function actualizarListaRetenciones( $arrayAnterior, $arrayNuevo ){

		$claves = [];

		if( count( $arrayAnterior ) > 0 ){

			foreach( $arrayAnterior as $array ){

				if( $array['activo'] ){
					$claves[] = $array['ret_id'];
				}
			}

			foreach( $arrayNuevo as &$array ){

				if( in_array( $array['ret_id'], $claves ) ){
					$array['activo'] = 1;
				}
			}
		}

		return $arrayNuevo;
	}

	/**
	 * Función que se encarga de insertar los datos de la DJ en la BD
	 * @param integer $trib_id Identificador de tributo
	 * @param string $obj_id Identificador de objeto
	 * @param integer $subcta Sub cuenta
	 * @param integer $fiscaliza
	 * @param integer $anio Año
	 * @param integer $cuota Cuota
	 * @param integer $tipo
	 * @param string $fchpresenta Fecha de presentación
	 * @param integer $DJRub_id
	 */
	public function grabarDJ( $DJRub_id, $bonificacion, $saldoAFavor, $retenciones, &$dj_id )
	{

		$stringRetenciones = '';

		if( count( $retenciones ) > 0 ){

			$stringRetenciones = ",Array[";

			$stringRetenciones .= implode( ",", $retenciones );

			$stringRetenciones .= "]";
		}

        $sql = 	"Select sam.uf_DJ_Generar(" . $this->trib_id . ",'" . $this->obj_id . "'," . $this->subcta . "," . $this->fiscaliza . "," .
        		$this->anio . "," . $this->cuota . "," . $this->getOrden() . ",1,'" . Fecha::usuarioToBD( $this->fchpresenta ) . "',false," .
        		Yii::$app->user->id . "," . $DJRub_id . ",1," . intVal( $bonificacion ) . "," . $saldoAFavor . $stringRetenciones . ")";

		$transaction = Yii::$app->db->beginTransaction();

		$resultado = 0;

        try
        {
			//Verificar si existe una DJ para ese período. En caso afirmativo, se debe quitar la retención asociada a la misma.
			if( $this->existePeriodo($this->trib_id,$this->anio,$this->cuota,$this->obj_id,0) ){

				$this->quitarRetenciones( $this->getCtacteID( $this->trib_id, $this->obj_id, $this->anio, $this->cuota ) );

			}

        	$dj_id = Yii::$app->db->createCommand($sql)->queryScalar();

        } catch (\Exception $e)
        {
			$transaction->rollback();

			$this->addError( 'dj_id', DBException::getMensaje( $e ) );

        	return false;
        }

		$transaction->commit();

		return true;
	}

	/**
	 * Función que realiza el cálculo de alícuota, mínimo y monto cuando se necesita
	 * agregar un nuevo rubro.
	 * @param integer $rubro_id Id del rubro
	 * @param double $base
	 * @param integer $cant Cantidad
	 * @param integer $anio Año
	 * @param integer $cuota Cuota
	 * @param string $fchpresenta Fecha de presentación
	 * @param string $obj_id Id de objeto
	 * @param integer $subcta Sub cuenta
	 *
	 */
	public function calculaRubro($rubro_id,$trib_id,$base,$cant,$anio,$cuota,$fchpresenta,$obj_id,$subcta = 0)
	{
		if ( $subcta > 0 )
		{
			//Buscar PI
			$pi = $this->getTipoRubro($obj_id,$subcta);

		} else
		{
			$pi = 0;
		}

		//Sentencia para calcular el rubro
		$sql = "Select * From sam.Uf_DJ_Calcular_Rubro('" . $rubro_id . "'," . $trib_id . "," . $anio . "," . $cuota .
			   "," . $base . "," . $cant . "," . $pi . ",0,0,'" . $obj_id . "','" . $fchpresenta . "')";

		$dataRubro = Yii::$app->db->createCommand( $sql )->queryAll();

		$sql = "Select * From sam.Uf_DJ_Calcular_Monto('" . $obj_id . "'," . $subcta . ",'" . $rubro_id;
		$sql .= "'," . $anio . "," . $cuota . "," . floatVal( $dataRubro[0]['minimo'] );
		$sql .= "," . $dataRubro[0]['fijo'] . "," . $dataRubro[0]['tminimo'] . "," . $dataRubro[0]['tcalculo'];
		$sql .= "," . $cant . "," . $dataRubro[0]['monto'] . ")";

		$dataMonto = Yii::$app->db->createCommand($sql)->queryAll();

		$array = [
			'dataRubro' => $dataRubro[0],
			'dataMonto' => $dataMonto[0],
		];

		return $array;

	}

	/**
	 * Función que obtiene los datos de Rubros de una DDJJ
	 * @param integer $id Id de la DDJJ
	 * @return dataProvider Resultado
	 */
	public function getDatosGrillaRubros( $id = 0 )
	{

		$sql = "SELECT dj_id,nomen_id,nomen_nom,rubro_id,rubro_nom,tipo,tcalculo,tcalculo_nom,base,cant,tunidad,alicuota,minimo,monto,ctacte_id,est " .
				"FROM v_dj_rubro WHERE dj_id = " . $id;

		return Yii::$app->db->createCommand( $sql )->queryAll();

	}

	/**
	 * Función que obtiene los datos de Liquidación de una DDJJ
	 * @param integer $id Id de la DDJJ
	 * @return dataProvider Resultado
	 */
	public function getDatosGrillaLiq($id)
	{
		if ($id == '') $id = 0;

    	$sql = "SELECT dj_id,item_id,item_nom,item_monto,ctacte_id,est " .
    			"FROM v_dj_liq WHERE dj_id = " . $id;

        return Yii::$app->db->createCommand( $sql )->queryAll();
	}

	/**
	 * Función que obtiene los datos de Anticipo de una DDJJ
	 * @param integer $id Id de la DDJJ
	 * @return dataProvider Resultado
	 */
	public function getDatosGrillaAnt($id)
	{
		/*if ($id == '') $id = 0;

    	$sql = "SELECT dj_id,cuota,base,monto " .
    			"FROM v_dj WHERE dj_id = " . $id;

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'dj_id',

        ]);

        return $dataProvider;*/
	}




















	/**
	 * Función que se ejecuta cuando se cargan los datos del modelo.
	 * Carga los datos que se obtienen de la vista de DDJJ.
	 */
	public function obtenerDatosVistaDJ($id)
	{
		if ($id == '') $id = 0;

		$sql = "SELECT obj_nom,trib_nom,to_char(fchvenc,'dd/MM/yyyy') as fchvenc,to_char(fchpresenta,'dd/MM/yyyy') as fchpresenta,orden_nom " .
			"FROM v_dj WHERE dj_id = " . $id;

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		$this->trib_nom = $data[0]['trib_nom'];
		$this->obj_nom = $data[0]['obj_nom'];
		$this->fchvenc = $data[0]['fchvenc'];
		$this->fchpresenta = $data[0]['fchpresenta'];
		$this->orden_nom = $data[0]['orden_nom'];

		//Actualizar el valor de est
		if ($this->est == 'A')	$this->estado = 'Activa';
		if ($this->est == 'B')	$this->estado = 'Baja';
		if ($this->est == 'R')	$this->estado = 'Rectificada';

	}

	/**
	 * Función que se utiliza para obtener el ctacte_id de un período.
	 */
	public function getCtacteID( $trib_id, $obj_id, $anio, $cuota ){

		$sql = 	"SELECT ctacte_id FROM ddjj WHERE trib_id = " . $trib_id . " AND obj_id = '" . $obj_id . "' AND " .
				"(anio*1000+cuota) = (" . $anio . "*1000+" . $cuota . ")";

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	public function getTipoRubro($obj_id,$subcta)
	{
		return Yii::$app->db->createCommand("Select PI from comer_suc where Obj_id='" . $obj_id . "' and SubCta=" . $subcta)->queryScalar();
	}

	/**
	 * Función que se utiliza para realizar la impresión
	 */
	public function Imprimir( $id, &$sub1, &$sub2, &$sub3 )
    {
    	$ctacte_id = Yii::$app->db->createCommand("Select ctacte_id From ddjj where dj_id=".$id)->queryScalar();

    	$sql = "Select *,to_char(fchemi,'dd/mm/yyyy') fchemi,to_char(venc1,'dd/mm/yyyy') venc1,to_char(venc2,'dd/mm/yyyy') venc2 From V_Emision_Print Where CtaCte_Id=".$ctacte_id." and Dj_Id=".$id;
   		$array = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select * From V_DJ_Liq Where DJ_id=".$id;
   		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

   		if (count($array) > 0){
			if ($array[0]['tobj'] == 2){
				$sql = "Select *,to_char(fchhab,'dd/MM/yyyy') fchhab From V_Comer_suc Where Obj_id='".$array[0]['obj_id']."'";
				if ($array[0]['subcta'] != 0) $sql .= " and subcta=".$array[0]['subcta'];
			}else {
				$sql = "Select * From V_Persona Where Obj_id='".$array[0]['obj_id']."'";
			}

			$sub2 = Yii::$app->db->createCommand($sql)->queryAll();

			if ($array[0]['trib_tipo'] == 2){
				$sql = "Select * From V_DJ_Rubro Where DJ_id=".$id;
				$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
			}else {
				$sub3 = null;
			}
		}

   		return $array;
    }

	/**
	 * Función que se utiliza para obtener la base y el monto de una DJ.
	 * @param integer $dj_id Identificador de la DJ.
	 * @return array Arreglo con la base y el monto finales.
	 */
	public function getTotalesDJ( $dj_id )
	{
		//INICIO Calculo y Seteo los valores para base y monto
		$array = $this->getDatosGrillaRubros( $dj_id );

		$baseTotal = 0;
		$montoTotal = 0;

		if (count($array > 0))
		{
			//Calculo Base
			foreach ($array as $rubros)
			{
				$baseTotal += $rubros['base'];
			}

			//Calculo Monto
			$array = $this->getDatosGrillaLiq( $dj_id );
			foreach ($array as $rubros)
			{
				$montoTotal += $rubros['item_monto'];
			}
		}

		return [
			'base' => number_format( $baseTotal, 2, '.', '' ),
			'monto' => number_format( $montoTotal, 2, '.', '' ),
		];
	}

	/**
	 * Función que se utiliza para buscar las DJ de un objeto para comparativa
	 * @param integer $trib_id Identificador del tributo
	 * @param integer $obj_id Identificador de objeto
	 * @param string $est Estado de la DJ
	 * @param boolean $oficio
	 * @param integer $fiscaliza Variable que determina si se cargan las declaraciones juradas de fiscalización.
	 */
	public function buscarListObj($trib_id = 0,$obj_id,$est,$oficio = false,$fiscaliza = 0)
	{

		$sql = "Select dj_id,subcta,Anio,Cuota,Orden_nom,Fiscaliza,Est,Tipo_nom,Base,Monto,Multa,to_char(FchPresenta,'dd/MM/yyyy') as fchpresenta,EstCtaCte ";
		$sql .= "From V_DJ Where obj_id='" . $obj_id . "'";

		if( $trib_id != 0 )
			$sql .= " AND trib_id=" . $trib_id;

		if ($est != '0' && $est != '')
			$sql .= " AND est='" . $est . "'";

		//Buscar las DJ según tipo
		$sql .= " AND tipo IN (1";

		if ($oficio)
			$sql .= ",2";

		if ($fiscaliza == 1)
			$sql .= ",3";

		$sql .= ")";

        $sql .= " Order By Anio Desc, Cuota Desc, Orden Desc, Fiscaliza ";

        return Yii::$app->db->createCommand( $sql )->queryAll();

	}

	/**
	 * Función que se encarga de eliminar una DJ
	 */
	public function eliminarDJ()
	{
		if ( $this->est == 'B' ){

			return 1005;
		}

		if ( $this->est == 'A' )
		{
			$sql = "Select count(*) From CtaCte Where CtaCte_Id = " . $this->ctacte_id . " and Est = 'P'";

			$count = Yii::$app->db->createCommand($sql)->queryScalar();

			if ( $count > 0 ){	//La DJ está paga.

				return 1006;

			} else {

				$transaction = Yii::$app->db->beginTransaction();

				try{

					$sql = "Select sam.uf_DJ_Borrar(" . $this->dj_id . ")";

					$res = Yii::$app->db->createCommand($sql)->queryScalar();

					$this->quitarRetenciones( $this->ctacte_id );

					if ( $res == 'OK' ){

						$transaction->commit();
						return 1;

					} else {
						$transaction->rollback();
						return 3;
					}

				} catch(\Exception $e ){

					$transaction->rollback();
					return 3;
				}
			}
		} else
		{
			return 1007;
		}
	}

	//---------------------------------------RETENCIONES------------------------------------------------//

	/**
	 * Función que obtiene las retenciones asociadas a un objeto.
	 * @param integer $trib_id Identificador de tributo.
	 * @param string $obj_id Identificador del objeto.
	 * @param integer $anio Año
	 * @param integer $mes Mes
	 */
	public function getRetenciones( $trib_id, $obj_id, $anio, $mes )
	{
		$sql = "SELECT * FROM sam.uf_dj_retenciones(" . $trib_id . ",'" . $obj_id . "'," . $anio . "," . $mes . ")";
		
		return Yii::$app->db->createCommand( $sql )->queryAll();
	}

	/**
	 * Función que obtiene las retenciones asociadas a una DJ.
	 */
	public function getRetencionesAsociadasADJ( $dj_id = 0, &$sql = '')
	{

		//$sql = "select r.*,p.cuit from ddjj_ret d inner join ret_det r on d.ret_id=r.ret_id left join persona p on r.obj_id=p.obj_id where dj_id = " . $dj_id;
		$sql = "select r.ret_id, r.retdj_id, numero, fecha, ag_rete, ag_cuit, ag_nom_redu, tcomprob, comprob, base, monto
		   from ddjj_ret d inner join v_ret_det r on d.ret_id=r.ret_id
		   where dj_id = " . $dj_id . " order by ag_rete, fecha, numero ";

		return Yii::$app->db->createCommand( $sql )->queryAll();

	}

	/**
	 * Función que se utiliza para asociar las retencioens a una DJ.
	 * @param array Retenciones Arreglo con el ID de las retenciones.
	 * @param integer $dj_id Identificador de la DJ.
	 */
	public function grabarRetenciones( $retenciones = [], $dj_id ){

		$montoRetenciones = 0;

		//Obtener la suma de los montos de las retenciones seleccionadas.
		foreach( $retenciones as $dato )
		{
			$sql = "SELECT monto FROM v_ret_det WHERE ret_id = " . $dato;

			$montoRetenciones += floatVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		}

		//Obtener la suma de las retenciones cargadas para el ctacte_id
		$sql = "SELECT sum(monto) FROM ret_det WHERE ctacte_id = " . $this->ctacte_id;

		$montoRetenciones += floatVal( Yii::$app->db->createCommand( $sql )->queryScalar() );

		//Obtener el monto de la DJ.
		$montoDJ = floatVal( $this->getTotalesDJ( $dj_id )['monto'] );

		//return $montoRetenciones;
		if( $montoRetenciones > $montoDJ )
			return 2;

		$transaction = Yii::$app->db->beginTransaction();

		try{

			//Actualizar los datos en la tabla ret_det
			foreach( $retenciones as $dato ){

				$sql = 	"UPDATE ret_det SET fchaplic = CURRENT_TIMESTAMP, est = 'I', ctacte_id = " . $this->ctacte_id .
						" WHERE ret_id = " . $dato;

				Yii::$app->db->createCommand( $sql )->execute();
			}

			$transaction->commit();

		} catch( \Exception $e ){

			$transaction->rollback();

			return 3;
		}

		return 1;

	}

	/**
	 * Función que se utiliza para desvincular las retenciones a una DJ.
	 * @param integer $ctacte_id Identificador de cuenta corriente.
	 */
	public function quitarRetenciones( $ctacte_id ){

		$sql = "UPDATE ret_det SET fchaplic = null, est = 'P', ctacte_id = 0 WHERE ctacte_id = " . $ctacte_id;

		Yii::$app->db->createCommand( $sql )->execute();
	}

	public function EliminarDDJJFaltante($trib,$objeto,$subcta,$perdesde,$perhasta)
	{
		$sql = "Delete From CtaCte where ctacte_id in (";
		$sql .= "Select c.ctacte_id from ctacte c ";
		$sql .= "left join ddjj d on c.ctacte_id= d.ctacte_id ";
		$sql .= "left join comer co on c.obj_id=co.obj_id ";
		$sql .= "Where c.Trib_Id = " . $trib . " and c.Obj_Id = '" . $objeto . "' and c.subcta=" . $subcta;
		$sql .= " and c.Anio*1000+c.Cuota between " . $perdesde . " and " . $perhasta;
		$sql .= " and c.Est in('X','D') and c.nominalcub=0 and (d.dj_id is null or d.est='B'))";

		try{
			Yii::$app->db->createCommand( $sql )->execute();

		} catch( \Exception $e ){
			return DBException::getMensaje( $e );
		}

		return "";
	}

}
