<?php

namespace app\models\ctacte;

use Yii;

use app\utils\db\utb;
use app\utils\db\Fecha;

use app\utils\helpers\DBException;

class RetencionDetalle extends \yii\db\ActiveRecord{

	//estado que puede tener un detalle
	const ESTADO_PENDIENTE	= 'P';
	const ESTADO_BAJA		= 'B';
	const ESTADO_IMPUTADO	= 'I';
	const ESTADO_DEVUELTA	= 'D';


	 //CAMPOS DEL DETALLE para leer desde un archivo
	const COMIENZO_CUIT	= 1;
	const LONGITUD_CUIT	= 11;

	// const COMIENZO_OBJETO= 12;
	// const LONGITUD_OBJETO= 7;
	//
	// const COMIENZO_LUGAR= 19;
	// const LONGITUD_LUGAR= 30;

	const COMIENZO_FECHA	= 12;
	const LONGITUD_FECHA	= 8;

	const COMIENZO_NUMERO	= 20;
	const LONGITUD_NUMERO	= 8;

	const COMIENZO_TIPO_COMPROBANTE	= 28;
	const LONGITUD_TIPO_COMPROBANTE	= 2;

	const COMIENZO_COMPROBANTE	= 30;
	const LONGITUD_COMPROBANTE	= 12;

	const COMIENZO_BASE	= 42;
	const LONGITUD_BASE	= 11;

	const COMIENZO_ALICUOTA	= 53;
	const LONGITUD_ALICUOTA	= 11;

	const COMIENZO_MONTO	= 64;
	const LONGITUD_MONTO	= 11;

	public $ag_rete_nom;

	public $desdeBD = 0;
	
	public $est_nom;
	
	public $tcomprob_nom;

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_ret_det';
    }

	public function rules(){

		$ret = [];

		$ret[] = [
			[ 'ret_id', 'retdj_id', 'numero', 'ctacte_id', 'usrmod', ],
			'integer',
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
			[ 'base', 'ali', 'monto' ],
			'number',
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
			[ 'est' ],
			'string',
			'min' => 1,
			'max' => 1,
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
			[ 'tcomprob' ],
			'string',
			'min' => 2,
			'max' => 2,
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
			[ 'obj_id' ],
			'string',
			'min' => 8,
			'max' => 8,
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
			[ 'ag_rete' ],
			'string',
			'min' => 3,
			'max' => 8,
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
			[ 'comprob' ],
			'string',
			'min' => 1,
			'max' => 12,
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
			[ 'lugar' ],
			'string',
			'min' => 0,
			'max' => 30,
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
			[ 'fecha', 'fchmod', 'cuit', 'nombre' ],
			'string',
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
			[ 'obj_id', 'numero', 'lugar', 'fecha', 'tcomprob', 'comprob', 'base', 'ali', 'monto', 'ag_rete', 'anio', 'mes' ],
			'required',
			'on' => [ 'insert', 'update' ],
		];

		$ret[]= [
			[ 'ag_rete', 'anio', 'mes', /* 'obj_id', */ 'fecha', 'numero', 'tcomprob', 'comprob', 'base', 'ali', 'monto'],
			'required',
			'on' => [ 'importar' ]
		];

		/**
		 * VALIDACIONES ESPECÍFICAS
		 */
		 $ret[] = [
			 'numero',
			 'verificarExistenciaRetencion',
			 'on' => [ 'insert', 'importar' ],
			 'skipOnError' => false,
			 'skipOnEmpty' => false,
		 ];

		 $ret[] = [
 			'fecha',
 			'validarFecha',
 			'on' => [ 'importar' ],
 			'skipOnError' => false,
 			'skipOnEmpty' => false,
 		];
		
		$ret[] = [
 			'est',
 			'validarEstado',
 			'on' => [ 'insert' ],
 			'skipOnError' => false,
 			'skipOnEmpty' => false,
 		];

		return $ret;
	}

	public function scenarios(){

		return[

			'insert' => [
				'ret_id', 'retdj_id', 'numero', 'base', 'ali', 'monto', 'ctacte_id', 'usrmod', 'est', 'anio', 'mes', 'desdeBD',
				'fecha', 'fchmod', 'lugar', 'tcomprob', 'comprob', 'obj_id', 'ag_rete', 'cuit', 'nombre',
			],

			'delete' => [
				'comprob', 'numero',
			],

			'update' => [
				'ret_id', 'retdj_id', 'numero', 'base', 'ali', 'monto', 'ctacte_id', 'usrmod', 'est', 'anio', 'mes', 'desdeBD',
				'fecha', 'fchmod', 'lugar', 'tcomprob', 'comprob', 'obj_id', 'ag_rete', 'cuit', 'nombre'
			],

			'importar' => [
				'ag_rete', 'anio', 'mes', 'obj_id', 'fecha', 'numero', 'fecha', 'base', 'ali', 'monto', 'lugar', 'comprob', 'tcomprob',
			],
		];

	}

	public function __construct( $ag_rete = '' ){

		if( $ag_rete != '' ){

			$this->ag_rete = $ag_rete;

		}

		$this->est = 'P';
		$this->est_nom = utb::getCampo('ret_test', "cod='" . $this->est . "'");

		//Asignar lugar por defecto
		$this->lugar= $this->getLocalidad();

		//Asignar la fecha actual
		$this->fecha = date( 'Y/m/d' );

	}

	public function afterFind(){

		$this->desdeBD = 1;	//Indica que el dato se extrajo desde la BD.
		$this->est_nom = utb::getCampo('ret_test', "cod='" . $this->est . "'");
		$this->tcomprob_nom = utb::getCampo('ret_tcomprob', "cod='" . $this->tcomprob . "'");
		
	}
	
	/**
	 * Función que se utiliza para precargar los datos de una retención.
	 * @param stirng $obj_id
	 */
	public function cargarDatosRetencion( $obj_id ){

		$this->obj_id 	= $obj_id;
		$this->nombre 	= utb::getCampo( 'objeto', "obj_id = '" . $obj_id . "'", 'nombre' );
		$this->cuit 	= utb::getCampo( 'persona', "obj_id = '" . $obj_id . "'", 'cuit' );
	}

	/**
	 * Función que se utiliza para validar que la fecha ingresada sea correcta.
	 */
	public function validarFecha( $attribute, $params ){

		$fecha = explode( '/', $this->fecha );

		if( ( $fecha[1] != $this->mes ) ||
			( $fecha[2] != $this->anio ) ){

			$this->addError( 'fecha', 'La fecha de la retención ' . $this->numero . ' es incorrecta. No corresponde al período que es declara.' );

		}
	}
	
	/**
	 * Función que se utiliza para validar que la retencion a agregar esta Activa.
	 */
	public function validarEstado( $attribute, $params ){

		if( $this->est != 'P' ){

			$this->addError( 'est', 'Solo se pueden agregar Retenciones Pendiente.' );

		}
	}


	/**
	 * Función que se utiliza para verificar la existencia de una retención.
	 */
	public function verificarExistenciaRetencion( $attribute, $params ){

		$sql = "SELECT EXISTS( SELECT 1 FROM v_ret_det WHERE ag_rete = '$this->ag_rete' AND anio = $this->anio AND numero = '$this->numero' and obj_id='$this->obj_id')";

		if( Yii::$app->db->createCommand( $sql )->queryScalar() ){

			$this->addError( $attribute, 'La retención ya se encuentra ingresada.' );
		}

	}

	/**
	 * Función que retorna el código de localidad.
	 */
	private function getLocalidad(){

		$codigoLocalidad= utb::samMuni()['loc_id'];
		$nombreLocalidad= utb::getCampo('domi_localidad', "loc_id = $codigoLocalidad", 'nombre');

		return $nombreLocalidad !== false ? $nombreLocalidad : null;
	}

	/**
	 * Función que se utiliza para obtener los agentes de retención.
	 */
	public function agentes(){

		return utb::getAux('v_persona', 'ag_rete', 'nombre', 0, "est = 'A' And ag_rete IS NOT NULL AND length(ag_rete) > 0 and ag_rete_manual=1","",true);
    }

	/**
     * Busca los tipo de comprobantes disponible.
     *
     * @return Array Cada elemento del arreglo es un arreglo de la forma ['cod' => codigo, 'nombre' => nombre]
     */
    public static function tiposComprobantes(){

    	return utb::getAux('ret_tcomprob');
    }

	/**
	 * Función que se utiliza para cargar los datos del objeto.
	 */
	public function cambiaDato( $dato = '' ){

		$this->cuit 	= '';
		$this->obj_id 	= '';
		$this->nombre	= '';

		if( $dato == '' ){

			return false;
		}

		$dato = str_replace('-', '', $dato);

		$obj_id = utb::getObjeto( 3, $dato );

		//Existe el objeto
		if( utb::verificarExistenciaObjeto( 3, "'" . $obj_id . "'" ) ){

			$sql = "SELECT cuit, obj_id, nombre FROM v_persona WHERE obj_id = '$obj_id'";

			$datos = Yii::$app->db->createCommand( $sql )->queryOne();

			$this->cuit		= $datos[ 'cuit' ];
			$this->obj_id	= $datos[ 'obj_id' ];
			$this->nombre	= $datos[ 'nombre' ];

		} else {

			//Verificar que exista un objeto con el cuit ingresado
			$sql = "SELECT EXISTS( SELECT 1 FROM persona WHERE cuit = '$dato' )";

			if( Yii::$app->db->createCommand( $sql )->queryScalar() ){

				//Seleccionar datos
				$sql = "SELECT cuit, obj_id, nombre FROM v_persona WHERE cuit = '$dato' ";

				$datos = Yii::$app->db->createCommand( $sql )->queryOne();

				$this->cuit		= $datos[ 'cuit' ];
				$this->obj_id	= $datos[ 'obj_id' ];
				$this->nombre	= $datos[ 'nombre' ];

			}
		}

	}

	public function getNextIdRetencion(){

		//$sql = "SELECT coalesce( MAX( ret_id ), 0 ) + 1 FROM ret_det";
		$sql = "Select nextval('ret_det_ret_id_seq')";

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	/**
	 * Función que se utiliza para grabar las retenciones.
	 */
	public function grabar( $retdj_id = 0 ){

		$nextID	= $this->getNextIdRetencion();

		$sql = 	"INSERT INTO ret_det ( ret_id, retdj_id, obj_id, numero, lugar, fecha, tcomprob, comprob, base, ali, monto, ctacte_id, est, fchmod, usrmod, ag_rete ) VALUES (" .
				"$nextID, $retdj_id, '$this->obj_id',$this->numero,'$this->lugar'," . Fecha::usuarioToBD( $this->fecha, 1 ) . ",'$this->tcomprob',$this->comprob," .
				"$this->base,$this->ali,$this->monto,0,'$this->est',current_timestamp,0,'$this->ag_rete')";

		return Yii::$app->db->createCommand( $sql )->execute() > 0;

	}

	/**
	 * Función que se utiliza para eliminar una retención.
	 */
	public static function eliminarRetencion( $rete_id ){

		if( RetencionDetalle::verificarRetencionAlEliminar( $rete_id ) ){

			return false;
		}

		//$sql = "UPDATE ret_det SET est = 'B' WHERE ret_id = " . $rete_id;
		$sql = "DELETE FROM ret_det WHERE ret_id = " . $rete_id;

		Yii::$app->db->createCommand( $sql )->execute();

		return true;
	}

	public function verificarRetencionAlEliminar( $rete_id ){

		$sql = "SELECT EXISTS( SELECT 1 FROM ret_det WHERE ret_id = " . $rete_id . " AND ( ctacte_id <> 0 OR retdj_id <> 0 ) )";

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	/**
	* Carga un detalle a partir de una linea leida desde un archivo
	*
	* @param string $lineaLeida - Linea desde donde se deben extraer los datos del detalle.
	* @param int $offset = -1 - A partir desde que posicion se debe comenzar a leer.
	*
	* @return RetencionDetalle - Detalle de retención con los datos cargados y validados.
	*/
	public static function cargarDesdeArchivo( $lineaLeida, $ag_rete, $anio, $mes ){

		$offset = -1;

		$model	= new RetencionDetalle();
		$model->setScenario('importar');

		$model->ag_rete = $ag_rete;
		$model->anio	= $anio;
		$model->mes		= $mes;

		$campo= substr($lineaLeida, self::COMIENZO_CUIT + $offset, self::LONGITUD_CUIT);
		$model->setCuit($campo, true);

		// $campo= substr($lineaLeida, self::COMIENZO_OBJETO + $offset, self::LONGITUD_OBJETO);
		// $model->setCodigoObjeto('P' . $campo);
		//
		// $campo= substr($lineaLeida, self::COMIENZO_LUGAR + $offset, self::LONGITUD_LUGAR );
		// $model->lugar= trim( $campo );

		$campo= substr($lineaLeida, self::COMIENZO_FECHA + $offset, self::LONGITUD_FECHA );
		$model->fecha = substr($campo, -2, 2) . '/' . substr($campo, 4, 2) . '/' . substr($campo, 0, 4);

		$campo= substr($lineaLeida, self::COMIENZO_NUMERO + $offset, self::LONGITUD_NUMERO );
		$model->numero= intval($campo);

		$campo= substr($lineaLeida, self::COMIENZO_TIPO_COMPROBANTE + $offset, self::LONGITUD_TIPO_COMPROBANTE );
		$model->tcomprob = $campo;

		$campo= substr($lineaLeida, self::COMIENZO_COMPROBANTE + $offset, self::LONGITUD_COMPROBANTE );
		$model->comprob= intval($campo);

		$campo= substr($lineaLeida, self::COMIENZO_BASE + $offset, self::LONGITUD_BASE );
		$model->base= floatval(intval($campo) /100);

		$campo= substr($lineaLeida, self::COMIENZO_ALICUOTA + $offset, self::LONGITUD_ALICUOTA );
		$model->ali= floatval(intval($campo) /100);

		$campo= substr($lineaLeida, self::COMIENZO_MONTO + $offset, self::LONGITUD_MONTO );
		$model->monto= floatval(intval($campo) /100);

		$model->validate();

		$model->cargarDatosRetencion( $model->obj_id );

		return $model;
	}

	/*
	*   Carga los datos del objeto en el modelo a partir de su cuit
	*
	*   @param string $cuit Cuit del objeto
	*   @param boolean $cargarObjeto = true Si se deben cargar los datos del objeto a partir del cuit provisto
	*/
	public function setCuit( $cuit, $cargarObjeto= true){

		if($cuit !== null) $cuit = str_replace('-', '', $cuit);

		if($cargarObjeto)
			$this->cargarDatosObjeto($cuit, false);
	}

	/*
	* Carga los datos del objeto en el modelo a partir del codigo del mismo. Si no es el tipo de objeto correcto, se carga el error en el modelo
	*
	* @param string $codigoObjeto Codigo del objeto
	*
	*/
	public function setCodigoObjeto( $codigoObjeto ){

		if( utb::verificarExistenciaObjeto( 3, "'" . $codigoObjeto . "'" ) ){
			$this->cargarDatosObjeto($codigoObjeto, true);
		} else {
			$this->addError('obj_id', 'El objeto ingresado no existe.' );
		}

	}

    /**
    * Carga los campos relacionados con el objeto (obj_id, cuit y nombre).
    *
    * @param string $dato Codigo del objeto o cuit.
    * @param boolean $esCodigoObjeto = true Si $dato es el codigo del objeto o el cuit.
    *
    * @return void
    */
    private function cargarDatosObjeto( $dato, $esCodigoObjeto= true){

		if($dato === null || strlen($dato) === 0) return;

		$condicion= $esCodigoObjeto ? "upper(obj_id) = upper('$dato')" : "cuit = '$dato'";

		$datos= utb::getVariosCampos( 'v_persona', $condicion, 'obj_id, cuit, nombre');

		if($datos === false){

			if( $esCodigoObjeto ){

				$this->obj_id= $dato;
				$this->cuit= '';

			} else {

				$this->obj_id= '';
				$this->cuit= $dato;
			}

		}

		$this->obj_id		= $datos['obj_id'];
		$this->nombre		= $datos['nombre'];
		$this->cuit 		= $datos['cuit'];
	}
	
}
