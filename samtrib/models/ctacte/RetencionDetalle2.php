<?php

namespace app\models\ctacte;

use Yii;

use app\utils\db\utb;
use app\utils\db\Fecha;

use app\utils\helpers\DBException;

class RetencionDetalle extends \yii\db\ActiveRecord{

    //estado que puede tener un detalle
	const ESTADO_PENDIENTE= 'P';
	const ESTADO_BAJA= 'B';
	const ESTADO_IMPUTADO= 'I';
	const ESTADO_DEVUELTA= 'D';


     //CAMPOS DEL DETALLE para leer desde un archivo
    const COMIENZO_CUIT= 1;
    const LONGITUD_CUIT= 11;

    const COMIENZO_OBJETO= 12;
    const LONGITUD_OBJETO= 7;

    const COMIENZO_LUGAR= 19;
    const LONGITUD_LUGAR= 30;

    const COMIENZO_FECHA= 49;
    const LONGITUD_FECHA= 8;

    const COMIENZO_NUMERO= 57;
    const LONGITUD_NUMERO= 8;

    const COMIENZO_TIPO_COMPROBANTE= 65;
    const LONGITUD_TIPO_COMPROBANTE= 2;

    const COMIENZO_COMPROBANTE= 67;
    const LONGITUD_COMPROBANTE= 12;

    const COMIENZO_BASE= 79;
    const LONGITUD_BASE= 11;

    const COMIENZO_ALICUOTA= 90;
    const LONGITUD_ALICUOTA= 11;

    const COMIENZO_MONTO= 101;
    const LONGITUD_MONTO= 11;

	//nombre del objeto
	public $denominacion;

    //si es un registro temporal (guardado en memoria) o un registro de la base de datos
	public $temporal;

	public function __construct($retdj_id= ''){

		$this->fecha= date('Y/m/d');
		$this->est= self::ESTADO_PENDIENTE;
		$this->retdj_id = $retdj_id	;

		//Asignar lugar "Esquel" por defecto
		$this->lugar= $this->getLocalidad();

		//Asignar tipo "Factura"
		$this->tcomprob = 'FA';

		$this->temporal= true;
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_ret_det';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
		$ret= [];

		/**
		 * CAMPOR REQUERIDOS
		 */
//		$ret[]= [
//				'ret_id',
//				'required',
//				'on' => ['update', 'delete']
//				];

		$ret[]= [
				['retdj_id'],
				'required',
				'on' => ['insert']
				];

		$ret[]= [
				['obj_id', 'fecha', 'numero', 'comprob', 'base', 'ali', 'monto'],
				'required',
				'on' => ['insert', 'update', 'importar']
				];

		$ret[] = [
				'lugar',
				'required',
				'on' => ['insert', 'update']
				];

        $ret[]= [
                ['tcomprob'],
                'required',
                'on' => ['insert', 'update'],
                'message' => 'Elija un {attribute}'
                ];


		/**
		 * FIN CAMPOS REQUERIDOS
		 */

		/**
		 * RANGO Y TIPO DE DATOS
		 */
		$ret[]= [
				['ret_id'],
				'integer',
				'min' => 1,
				'on' => ['insert', 'update', 'delete']
				];

		$ret[]= [
				['base', 'monto'],
				'number',
				'min' => 0,
				'max' => pow(10, 14)-1,
				'on' => ['insert', 'update', 'importar']
				];

		$ret[]= [
				['numero'],
				'integer',
				'min' => 1,
				'on' => ['insert', 'update', 'importar']
				];

		$ret[]= [
				'fecha',
				'date',
				'format' => 'php:Y/m/d',
				'on' => ['insert', 'update', 'importar']
				];

		$ret[]= [
				'comprob',
				'string',
				'min' => 1,
				'max' => 12,
				'on' => ['insert', 'update', 'importar']
				];

		$ret[]= [
				'tcomprob',
				'string',
				'min' => 1,
				'max' => 2,
				'on' => ['insert', 'update', 'importar'],
				'message' => 'Elija un {attribute}'
				];

		$ret[]= [
				'lugar',
				'string',
				'max' => 30,
				'on' => ['insert', 'update', 'importar']
				];

		$ret[]= [
				'obj_id',
				'string',
				'min' => 8,
				'max' => 8,
				'on' => ['insert', 'update', 'importar']
				];

		$ret[]= [
				'ag_rete',
				'string',
				'min' => 1,
				'max' => 8,
				'on' => ['insert', 'update', 'importar']
				];

		/**
		 * FIN RANGO Y TIPO DE DATOS
		 */


		/**
		 * VALORES POR DEFECTO
		 */

		/**
		 * FIN VALORES POR DEFECTO
		 */

		/**
		 * EXISTENCIA
		 */
		$ret[]= [
				'obj_id',
				'validarObjeto',
				'on' => ['insert', 'update', 'importar']
				];

		/*
		$ret[]= [
				'lugar',
				'validarLugar',
				'on' => ['insert', 'update']
				];
		*/

		/**
		 * FIN EXISTENCIA
		 */

		return $ret;
    }

    public function scenarios(){
    	return [
    		'insert' => ['obj_id', 'numero', 'lugar', 'fecha', 'base', 'ali', 'monto', 'tcomprob', 'comprob','ag_rete'],
    		'update' => ['ret_id', 'obj_id', 'numero', 'lugar', 'fecha', 'base', 'ali', 'monto', 'tcomprob', 'comprob','ag_rete'],
    		'delete' => ['ret_id'],
            'importar' => ['obj_id', 'fecha', 'numero', 'fecha', 'base', 'ali', 'monto', 'lugar']
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        	'cuit' => 'CUIT',
            'obj_id' => 'Código de objeto',
            'denominacion' => 'Denominación',
            'lugar' => 'Lugar',
            'fecha' => 'Fecha',
            'numero' => 'Número',
            'tcomprob' => 'Tipo de comprobante',
            'comprob' => 'Número de comprobante',
            'base' => 'Base',
            'ali' => 'Alícuota',
            'monto' => 'Monto'

        ];
    }

    /**
     * Se ajustan las distintas fechas dependiendo del escenario y se cargan las variables booleanas que determinan los distintos datos que requiere el tipo de reclamo.
     */
    public function beforeValidate(){

    	if($this->fecha != null && $this->fecha != '' && $this->getScenario() != 'importar')
    		$this->fecha= Fecha::usuarioToBD($this->fecha);

    	return true;
    }

    public function afterFind(){

		$this->temporal= false;
        $this->denominacion= $this->nombre;



        $this->fecha= Fecha::bdToUsuario($this->fecha);
    }

    public function afterValidate(){

        if(!$this->hasErrors() || !array_key_exists('obj_id', $this->getErrors()))
            $this->cargarDatosObjeto($this->obj_id, true);
    }

    /**
    * Carga los campos relacionados con el objeto (obj_id, cuit y denominacion).
    *
    * @param string $dato Codigo del objeto o cuit.
    * @param boolean $esCodigoObjeto = true Si $dato es el codigo del objeto o el cuit.
    *
    * @return void
    */
    private function cargarDatosObjeto($dato, $esCodigoObjeto= true){

        if($dato === null || strlen($dato) === 0) return;

       $condicion= $esCodigoObjeto ? "upper(obj_id) = upper('$dato')" : "cuit = '$dato'";

       $datos= utb::getVariosCampos('v_comer', $condicion, 'obj_id, cuit, nombre');

        if($datos === false){

            if($esCodigoObjeto){

                $this->obj_id= $dato;
                $this->cuit= '';

            } else {

                $this->obj_id= '';
                $this->cuit= $dato;
            }

            $this->denominacion= '';
        }


        $this->obj_id= $datos['obj_id'];
        $this->denominacion= $datos['nombre'];
        $this->cuit= $datos['cuit'];
    }

    /*
    * Carga los datos del objeto en el modelo a partir del codigo del mismo. Si no es el tipo de objeto correcto, se carga el error en el modelo
    *
    * @param string $codigoObjeto Codigo del objeto
    *
    */
    public function setCodigoObjeto($codigoObjeto){

        if(strlen($codigoObjeto) < 8) $codigoObjeto= utb::getObjeto(2, $codigoObjeto);

        $esComercio= utb::getTObj($codigoObjeto);

        if(!$esComercio){
            $this->addError('obj_id', 'El objeto debe ser un comercio');
            return;
        }

        $this->cargarDatosObjeto($codigoObjeto, true);
    }

    /*
    *   Carga los datos del objeto en el modelo a partir de su cuit
    *
    *   @param string $cuit Cuit del objeto
    *   @param boolean $cargarObjeto = true Si se deben cargar los datos del objeto a partir del cuit provisto
    */
    public function setCuit($cuit, $cargarObjeto= true){

        if($cuit !== null) $cuit= str_replace('-', '', $cuit);

        if($cargarObjeto)
            $this->cargarDatosObjeto($cuit, false);
    }

    /**
     * Si es un registro nuevo, lo inserta. Si es un registro existente, lo modifica
     *
     *  @param int $retdj_id Codigo de declaracion jurada a la que esta ligada la retencion.
     *  @param int $anio Año del período de la declaracion jurada.
     *  @param int $mes Mes del período de la declaración jurada
     *
     *  @return boolean Si se ha grabado correctamente.
     */
    public function grabar($retdj_id, $anio, $mes){



    	$scenario= $this->isNewRecord ? 'insert' : 'update';
    	$this->setScenario($scenario);

			if( !$this->validate() ){
				return false;
			}

        if(!$this->fechaDentroPeriodo($this->fecha, $anio, $mes)){

            $this->addError('ret_id', "La fecha debe ser del período $anio/$mes");
            return false;
        }

    	if( $this->isNewRecord ){

    		$codigo= $this->proximoCodigo();
    		$this->retdj_id= $retdj_id;

    		$sql= "Insert Into ret_det(ret_id, retdj_id, obj_id, numero, lugar, fecha, base, ali, monto, fchaplic, ctacte_id, est, tcomprob, comprob, fchmod, usrmod, ag_rete)" .
    				" Values($codigo, $this->retdj_id, '$this->obj_id', $this->numero, '$this->lugar', '$this->fecha', $this->base, $this->ali, $this->monto, null, 0, '$this->est', '$this->tcomprob', '$this->comprob', current_timestamp, " . Yii::$app->user->id . ",'" . $this->ag_rete . "')";

    		try{
    			Yii::$app->db->createCommand($sql)->execute();
    		} catch(\Exception $e){

    			$this->addError('ret_id', DBException::getMensaje($e));
    			return false;
    		}

    		$this->ret_id= $codigo;

    	} else {

            $sql= "Update ret_det Set obj_id = '$this->obj_id', numero = $this->numero, lugar = '$this->lugar', fecha = '$this->fecha', base = $this->base, ali = $this->ali";
            $sql .= ", monto = $this->monto, est = 'P', tcomprob = '$this->tcomprob', comprob = $this->comprob, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id;
            $sql .=  " Where ret_id = $this->ret_id";

            try{
                Yii::$app->db->createCommand($sql)->execute();
            } catch(\Exception $e){

                $this->addError('ret_id', DBException::getMensaje($e));
                return false;
            }
    	}

    	return true;
    }


    /*
    */
    public function borrar(){

        if(in_array($this->est, [self::ESTADO_DEVUELTA, self::ESTADO_IMPUTADO])){
            $this->errorError('ret_id', 'El detalle ' . $this->ret_id . ' no puede ser eliminado');
            return false;
        }

    	$sql= "Update ret_det Set est = 'B' Where ret_id = $this->ret_id";
        Yii::$app->db->createCommand($sql)->execute();
        return true;
    }

	/*
    /*
    * Valida que el lugar guardado en el modelo sea valido

    public function validarLugar(){

    	//lugares que son validos
    	$lugares= RetencionDetalle::lugares();

    	if(!array_key_exists($this->lugar, $lugares)) $this->addError('lugar', 'El lugar debe ser una localidad de la provincia actual.');
    }
	*/

    /*
    * Valida que el objeto guardado en el modelo sea valido
    */
    public function validarObjeto(){

    	$datos= utb::getVariosCampos('objeto', "obj_id = '$this->obj_id'", 'obj_id, tobj');

    	if($datos === false){

    		$this->addError('obj_id', 'El objeto no existe');
    		return;
    	}

    	if($datos['tobj'] != 2) $this->addError('obj_id', 'El objeto debe ser un comercio');
    }

    public function existeNumero(){


    }

    /**
     * Determina si este registro es igual a $model
     *
     * @param RetencionDetalle $model Registro con el que se debe comparar
     * @param boolean $colocarError = false Si se debe colocar un mensaje de error en $model cuando los registros son iguales
     *
     * @return boolean Si este registro es igual a $model
     */
    public function esIgual($model, $colocarError = false){

    	$anio= substr($this->fecha, 0, 4);
    	$mes= substr($this->fecha, 5, 2);

    	$anioComparar= substr($model->fecha, 0, 4);
    	$mesComparar= substr($model->fecha, 5, 2);


    	if($this->retdj_id == $model->retdj_id && $this->numero == $model->numero){

    		if($colocarError) $model->addError('ret_id', 'Ya existe un detalle con el mismo número.');

    		return true;
    	}

    	return false;
    }

    /**
     * Busca los tipo de comprobantes disponible.
     *
     * @return Array Cada elemento del arreglo es un arreglo de la forma ['cod' => codigo, 'nombre' => nombre]
     */
    public static function tiposComprobantes(){

    	return utb::getAux('ret_tcomprob');
    }

    /*
     * Busca los lugares disponibles.
     *
     * @return Array Cada elemento del arreglo es un arreglo de la forma ['cod' => codigo, 'nombre' => nombre]

    public static function lugares(){

    	$localidadActual= utb::getCodLocalidad();
    	$provinciaActual= Yii::$app->db->createCommand("Select Coalesce(prov_id, 0) From domi_localidad Where loc_id = $localidadActual")->queryScalar();

    	return utb::getAux('domi_localidad', 'loc_id', 'nombre', 0, "prov_id = $provinciaActual");
    }
	*/

	/**
	 * Función que se utiliza para obtener los agentes.
	 */
	public function agentes(){

		return utb::getAux('v_persona', 'ag_rete', 'nombre', 0, "est = 'A' And ag_rete IS NOT NULL AND length(ag_rete) > 0","",true);
    }

    /**
     * Obtiene el proximo codigo disponible para insertar en la base de datos.
     *
     * @return int Codigo a insertar
     */
    private function proximoCodigo(){

    	$sql= "Select nextval('seq_ret_det_id'::regclass)";
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    private function fechaDentroPeriodo($fecha, $anio, $mes){


        $anioFecha= intval(substr($fecha, 0, 4));
        $mesFecha= intval(substr($fecha, 4, 2));

        return $anioFecha != $anio || $mesFecha != $mes;
    }

    /**
    * Carga un detalle a partir de una linea leida desde un archivo
    *
    * @param string $lineaLeida - Linea desde donde se deben extraer los datos del detalle.
    * @param int $offset = -1 - A partir desde que posicion se debe comenzar a leer.
    *
    * @return RetencionDetalle - Detalle de retención con los datos cargados y validados.
    */
    public static function cargarDesdeArchivo($lineaLeida, $offset= -1){

        $model= new RetencionDetalle();
        $model->setScenario('importar');

        $campo= substr($lineaLeida, self::COMIENZO_CUIT, self::LONGITUD_CUIT + $offset);
        $model->setCuit($campo, false);

        $campo= substr($lineaLeida, self::COMIENZO_OBJETO, self::LONGITUD_OBJETO + $offset);
        $model->setCodigoObjeto('C' . $campo);

        $campo= substr($lineaLeida, self::COMIENZO_LUGAR, self::LONGITUD_LUGAR + $offset);
        $model->lugar= $campo;

        $campo= substr($lineaLeida, self::COMIENZO_FECHA, self::LONGITUD_FECHA + $offset);
        $model->fecha= substr($campo, 0, 4) . '/' . substr($campo, 4, 2) . '/' . substr($campo, -2, 2);

        $campo= substr($lineaLeida, self::COMIENZO_NUMERO, self::LONGITUD_NUMERO + $offset);
        $model->numero= intval($campo);

        $campo= substr($lineaLeida, self::COMIENZO_BASE, self::LONGITUD_BASE + $offset);
        $model->base= floatval(intval($campo) /100);

        $campo= substr($lineaLeida, self::COMIENZO_ALICUOTA, self::LONGITUD_ALICUOTA + $offset);
        $model->ali= floatval(intval($campo) /100);

        $campo= substr($lineaLeida, self::COMIENZO_MONTO, self::LONGITUD_MONTO + $offset);
        $model->monto= floatval(intval($campo) /100);

        $model->validate();
        return $model;
    }

	/**
	 * Función que se utiliza para precargar los datos de una retención.
	 * @param stirng $obj_id
	 */
	public function cargarDatosRetencion( $obj_id ){

		$this->obj_id = $obj_id;
		$this->denominacion = utb::getCampo( 'objeto', "obj_id = '" . $obj_id . "'", 'nombre' );
		$this->cuit = utb::getCampo( 'comer', "obj_id = '" . $obj_id . "'", 'cuit' );
	}

	/**
	 * Función que retorna el código de localidad.
	 */
	private function getLocalidad(){

		$codigoLocalidad= utb::samMuni()['loc_id'];
		$nombreLocalidad= utb::getCampo('domi_localidad', "loc_id = $codigoLocalidad", 'nombre');

		return $nombreLocalidad !== false ? $nombreLocalidad : null;
	}

	public static function sugerenciaLugar($lugar = ''){

		$sql= "SELECT DISTINCT lugar FROM ret_det WHERE lugar LIKE '%$lugar%'";
		return yii::$app->db->createCommand($sql)->queryAll();
	}
}
