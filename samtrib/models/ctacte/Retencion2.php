<?php

namespace app\models\ctacte;

use Yii;

use app\models\ctacte\RetencionDetalle;

use app\utils\db\utb;
use app\utils\db\Fecha;


class Retencion extends \yii\db\ActiveRecord{

    //distintos estados que puede tener el registro
    const ESTADO_APROBADA= 'A';
    const ESTADO_BAJA= 'B';
    const ESTADO_PENDIENTE= 'P';


    /*
    *
    * DATOS PARA LA LECTURA DEL ARCHIVO DE TEXTO
    */

    const IDENTIFICADOR_CABECERA= "CAB";
    const IDENTIFICADOR_PIE= "PIE";

    //largos totales
    const LARGO_CABECERA= 28;
    const LARGO_DETALLE= 111;
    const LARGO_PIE= 17;

    const OFFSET_LINEA= -1;

    //CAMPOS DE LA CABECERA
    const COMIENZO_ID= 1;
    const LONGITUD_ID= 3;

    const COMIENZO_AGENTE= 4;
    const LONGITUD_AGENTE= 8;

    const COMIENZO_CUIT= 13;
    const LONGITUD_CUIT= 11;

    const COMIENZO_ANIO= 23;
    const LONGITUA_ANIO= 4;

    const COMIENZO_MES= 27;
    const LONGITUD_MES= 2;


    //CAMPOS DEL PIE
    const COMIENZO_ID_PIE= 1;
    const LONGITUD_ID_PIE= 3;

    const COMIENZO_FILAS= 4;
    const LONGITUD_FILAS= 3;

    const COMIENZO_TOTAL= 7;
    const LONGITUD_TOTAL= 11;
    /*
    *
    * FIN DATOS PARA LA LECTURA DEL ARCHIVO DE TEXTO
    */


    private $cantidadDetallesProvistos;
    private $totalProvisto;




	public $denominacion;

	public $detalles;

	public $est_nom;

    public $puedeEliminar;
    public $puedeModificar;
    public $necesitaAprobarse;

    public $archivo;

	public function __construct($ag_rete= ''){

		$this->ag_rete= $ag_rete;
		$this->retdj_id= 0;
		$this->fchpresenta= date('Y/m/d');
		$this->puedeEliminar= true;
        $this->puedeModificar= true;
        $this->necesitaAprobarse= false;
        $this->archivo= null;
        $this->cantidadDetallesProvistos= 0;
        $this->totalProvisto= 0;
        $this->detalles= [];

		//se obtiene el cuit y la denominacion de la persona
        if($this->ag_rete != ''){

            $datos= utb::getVariosCampos('v_persona', "ag_rete = '$this->ag_rete'", 'nombre, cuit');

            if($datos !== false){
                $this->cuit= $datos['cuit'];
                $this->denominacion= $datos['nombre'];
            }
        }
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_ret';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
		$ret= [];

		/**
		 * CAMPOS REQUERIDOS
		 */
        $ret[]= [
                'retdj_id',
                'required',
                'on' => ['update', 'delete']
                ];

		$ret[]= [
				['ag_rete', 'anio', 'mes', 'fchpresenta'],
				'required',
				'on' => ['insert', 'update']
				];

        $ret[]= [
                'archivo',
                'required',
                'on' => ['importar']
                ];
		/**
		 * FIN CAMPOS REQUERIDOS
		 */

        /**
         * TIPO Y RANGO DE DATOS
         */
		$ret[]= [
                'retdj_id',
                'integer',
                'min' => 1,
                'on' => ['update', 'delete']
                ];

        $ret[]= [
                'ag_rete',
                'string',
                'max' => 8,
                'on' => ['insert', 'update']
                ];

        $ret[]= [
				'anio',
				'integer',
				'min' => 1900,
				'max' => 9999,
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'mes',
				'integer',
				'min' => 1,
				'max' => 12,
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'fchpresenta',
				'date',
				'format' => 'php:Y/m/d',
				'on' => ['insert', 'update']
				];

        $ret[]= [
                'archivo',
                'file',
                'extensions' => 'txt',
                'on' => ['importar']
                ];

		/**
		 * FIN TIPO Y RANGO DE DATOS
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



		/**
		 * FIN EXISTENCIA
		 */

//		$ret[]= [
//				'ret_id',
//				'validarDetalles'
//				];
		return $ret;
    }

    public function scenarios(){
    	return [
    		'insert' => ['ag_rete', 'cuit', 'denominacion', 'anio', 'mes', 'fchpresenta'],
    		'update' => ['retdj_id', 'ag_rete', 'cuit', 'denominacion',  'retdj_id', 'anio', 'mes', 'fchpresenta'],
    		'delete' => ['retdj_id'],
            'importar' => ['archivoImportar']
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'anio' => 'Año',
            'mes' => 'Mes',
            'fchpresenta' => 'Fecha de presentación',
            'ag_rete' => 'Código de agente de rentnción',
            'cant' => 'Cantidad',
            'monto' => 'Monto'

        ];
    }

    /**
     * Se ajustan las distintas fechas dependiendo del escenario y se cargan las variables booleanas que determinan los distintos datos que requiere el tipo de reclamo.
     */
    public function beforeValidate(){


    	if($this->fchpresenta != null && $this->fchpresenta != '' && $this->getScenario() != 'importar')
        	$this->fchpresenta= Fecha::usuarioToBD($this->fchpresenta);

    	return true;
    }


    public function afterFind(){

        $this->denominacion= $this->nombre;
        $this->cuit= str_replace('-', '', $this->cuit);
		$this->est_nom = utb::getCampo("ret_test","cod='" . $this->est . "'", 'nombre');

        //se determina si se puede eliminar
        $sql= "Select Exists (Select 1 From v_ret_det Where retdj_id = $this->retdj_id And est In ('I', 'D'))";
        $this->puedeEliminar= $this->est == self::ESTADO_PENDIENTE && !Yii::$app->db->createCommand($sql)->queryScalar();
        $this->puedeModificar= $this->est == self::ESTADO_PENDIENTE;
        $this->necesitaAprobarse= $this->est == self::ESTADO_PENDIENTE;
    }

    /**
     * Si es un registro nuevo, lo inserta. Si es un registro existente, lo modifica
     */
    public function grabar($detalles){

        $this->setDetalles($detalles);

    	$scenario= $this->isNewRecord ? 'insert' : 'update';
    	$this->setScenario($scenario);

    	if(!$this->validate()) return false;

    	$trans= Yii::$app->db->beginTransaction();

    	$this->monto= $this->calcularMonto($this->detalles);
    	$this->cant= count($this->detalles);

    	if($this->isNewRecord){

    		$codigo= $this->proximoCodigo();

    		$sql= "Insert Into ret(retdj_id, ag_rete, anio, mes, cant, monto, fchpresenta, est, fchmod, usrmod)" .
    				" Values($codigo, '$this->ag_rete', $this->anio, $this->mes, $this->cant, $this->monto, '$this->fchpresenta', '" . self::ESTADO_PENDIENTE . "', current_timestamp, " . Yii::$app->user->id . ")";

    		$res= Yii::$app->db->createCommand($sql)->execute() > 0;

    		if(!$res){

    			$this->addError('retdj_id', 'Ocurrió un error al intentar realizar la operación');
    			$trans->rollBack();
    			return false;
    		}

            if(!$this->grabarDetalles($codigo, $this->anio, $this->mes)){

                $trans->rollBack();
                return false;
            }

            $this->retdj_id= $codigo;

    	} else {

    		$sql= "Update ret Set cant = $this->cant, monto = $this->monto, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id;
            $sql .= " Where retdj_id = $this->retdj_id";

            $res= Yii::$app->db->createCommand($sql)->execute() > 0;

            if(!$res){

                $this->addError('retdj_id', 'Ocurrió un error al intentar realizar la operación');
                $trans->rollBack();
                return false;
            }

            if(!$this->grabarDetalles($this->retdj_id, $this->anio, $this->mes)){

                $trans->rollBack();
                return false;
            }
    	}

    	$trans->commit();
    	return true;
    }

    public function aprobar(){

        if($this->est != self::ESTADO_PENDIENTE){

            $this->addError('retdj_id', 'Solo los registros con estado pendiente pueden ser aprobados');
            return false;
        }

        $sql= "Update ret Set est = 'A' Where retdj_id = $this->retdj_id";
        $sql= Yii::$app->db->createCommand($sql)->execute();
        return true;
    }

    public function borrar(){

        if(!$this->puedeEliminar){
            $this->addError('retdj_id', 'La declaración jurada no puede ser borrada.');
            return false;
        }

        $trans  = Yii::$app->db->beginTransaction();

        try{

            $this->eliminarDetalles( $this->retdj_id );

            $sql    = "DELETE FROM ret WHERE retdj_id = $this->retdj_id";
            Yii::$app->db->createCommand($sql)->execute();

        } catch( \Exception $e ){
            $this->addError( 'retdj_id', 'Ocurrió un error al eliminar la DJ.' );
            return false;
        }

        $trans->commit();

        return true;
    }

    /**
     * Función que se utiliza para eliminar las retenciones
     */
    private function eliminarDetalles( $retdj_id ){

        //Eliminar los detalles en la BD
        $sql = "DELETE FROM ret_det WHERE retdj_id = $retdj_id";

        Yii::$app->db->createCommand( $sql )->execute();

    }

    private function grabarDetalles($retdj_id, $anio, $mes){

        $actual= 1;
        $numerosGrabados= [];//numeros grabados

        //Se eliminan las retenciones de la BD.
        $this->eliminarDetalles( $retdj_id );

        foreach($this->detalles as $detalle){


            if($detalle->temporal){
                //se valida que no exista un detalle con el mismo numero para el agente de retencion
                $sql= "Select Exists (Select 1 From ret As r, ret_det As rd Where r.retdj_id = rd.retdj_id And r.ag_rete = '$this->ag_rete' And rd.numero = $detalle->numero)";
                $existe= Yii::$app->db->createCommand($sql)->queryScalar();

                if($existe || in_array($detalle->numero, $numerosGrabados)){
                    $this->addError('retdj_id', 'Ya existe un detalle con el número "' . $detalle->numero . '" para el agente de retención "' . $this->ag_rete . '"');
                    return false;
                }
            }


            if(!$detalle->grabar($retdj_id, $anio, $mes)){
				//$this->addError('retdj_id', $detalle->getErrors()['ret_id'][0]);
                $this->addError('retdj_id', 'Ocurrió un error al intentar grabar el detalle ' . $actual);
                return false;
            }

            array_push($numerosGrabados, $detalle->numero);
            $actual++;
        }

        return true;
    }

    /**
    * Obtiene los agentes de retencion existentes.
    *
    * @return Array Agentes de retencion que estan disponibles. Cada elemento es un arreglo del tipo ['codigo' => codigo, 'nombre' => 'nombre']
    */
    public static function agentes(){

    	return utb::getAux('v_persona', 'ag_rete', 'ag_rete', 0, "est = 'A' And ag_rete IS NOT NULL AND length(ag_rete) > 0");
    }

    /**
    * Obtiene los detalles de la declaracion jurada.
    *
    * @param int $retdj_id = 0 codigo de declaracion jurada.
    *
    * @return Array Detalles de la declaracion jurada. Cada elemento del arreglo es un objeto de tipo RetencionDetalle.
    */
    public function detalle($retdj_id= 0){

    	return RetencionDetalle::find(['ret_id' => $retdj_id])->all();
    }

    /**
    * Calcula el monto total de los detalles.
    *
    * @param Array Cada elemento debe ser un objeto de tipo RetencionDetalle.
    *
    * @return double Sumatoria de los montos.
    */
    private function calcularMonto($detalles= []){

    	$monto= 0;

    	foreach($detalles as $d)
    		$monto += $d->monto;

    	return $monto;
    }

    /**
    * Asigna los detalles al modelo
    *
    * @param Array Detalles que se deben asignar. Cada elemento del arreglo debe ser de tipo RetencionDetalle.
    *
    * @return void
    */
    public function setDetalles($detalles = []){

        $this->monto = $this->calcularMonto($detalles);
        $this->cant= count($detalles);

        $this->detalles= $detalles;
    }

    /**
    * Obtiene el proximo codigo disponible a insertar en la base de datos.
    *
    * @return int Codigo disponible a insertar.
    */
    private function proximoCodigo(){

    	$sql= "Select nextval('seq_ret_id'::regclass)";
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public static function buscar($criterio = ''){


        $sql= "Select ret_id, retdj_id, ag_rete, anio, mes, obj_id, numero, fecha, lugar, base, ali, monto, est From v_ret_det";

        if($criterio != '')
            $sql .= " Where $criterio";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /*
    * Carga los datos del objeto en el modelo a partir de su cuit
    *
    * @param string $cuit Cuit del objeto
    */
    private function setCuit($cuit){

        if($cuit !== null) $cuit= str_replace('-', '', $cuit);

        $this->cuit= $cuit;

        $datos= utb::getVariosCampos('v_persona', "cuit = '$cuit'", 'obj_id, nombre');

        if($datos !== false){
//            $this->obj_id= $datos['obj_id'];
            $this->denominacion= $datos['nombre'];
        } else $this->addError('cuit', $cuit);

    }

    private function esCabecera($linea){

        return strlen($linea) == self::LARGO_CABECERA && substr($linea, 0, self::LONGITUD_ID + self::OFFSET_LINEA) == self::IDENTIFICADOR_CABECERA;
    }

    private function esDetalle($linea){
        return strlen($linea) == self::LARGO_DETALLE;
    }

    private function esPie($linea){
        return strlen($linea) == self::LARGO_PIE && substr($linea, 0, self::LONGITUD_ID_PIE + self::OFFSET_LINEA) == self::IDENTIFICADOR_PIE;
    }

    private function leerCabecera($linea){

        //el identificador de cabecera es correcto
        $campo= substr($linea, self::COMIENZO_AGENTE, self::LONGITUD_AGENTE + self::OFFSET_LINEA);
        $this->ag_rete= $campo;

        $campo= substr($linea, self::COMIENZO_CUIT, self::LONGITUD_CUIT + self::OFFSET_LINEA);
        $this->setCuit($campo);

        $campo= substr($linea, self::COMIENZO_ANIO, self::LONGITUA_ANIO + self::OFFSET_LINEA);
        $this->anio= intval($campo);

        $campo= substr($linea, self::COMIENZO_MES, self::LONGITUD_MES + self::OFFSET_LINEA);
        $this->mes= intval($campo);

        return true;
    }

    private function leerDetalle($linea){

        $model= RetencionDetalle::cargarDesdeArchivo($linea);

        if($model->hasErrors()){

            $this->addError('retdj_id', $model->getErrors()[key($model->getErrors())][0]);
            return false;
        }

        array_push($this->detalles, $model);
        $this->totalProvisto += $model->monto;

        return true;
    }

    private function leerPie($linea){

        $campo= substr($linea, self::COMIENZO_FILAS, self::LONGITUD_FILAS + self::OFFSET_LINEA);
        $this->cantidadDetallesProvistos= intval($campo);

        $campo= substr($linea, self::COMIENZO_TOTAL, self::LONGITUD_TOTAL + self::OFFSET_LINEA);
        $total= intVal($campo);

        $this->monto= floatval($total / 100);

        return true;
    }

    public function importarArchivo(){

        $this->setScenario('importar');
        if(!$this->validate()) return false;

        $this->cant= 0;
        $pieLeido= false;
        $archivo= fopen($this->archivo->tempName, "r");
        $linea= trim(fgets($archivo));

        if($linea === false || !$this->esCabecera($linea)){

            $this->addError('retdj_id', 'El formato de la cabecera es incorrecto');
            fclose($archivo);
            return false;

        } else $this->leerCabecera($linea);


        do{

            $linea= fgets($archivo);
            //$this->addError('retdj_id', 'linea leida ' . $linea);

            if($linea !== false){

                $linea= trim($linea);

                $esPie= $this->esPie($linea);
                $esDetalle= $this->esDetalle($linea);


                if(!$esPie && !$esDetalle){

                    //$this->addError('retdj_id', $linea);
                    //break;
                    if(!$pieLeido && fgets($archivo) === false)
                        $this->addError('retdj_id', 'El formato del pie es incorrecto');
                    else $this->addError('retdj_id', 'El formato del detalle ' . ($this->cant + 1) . ' es incorrecto');

                    break;
                }

                if($esPie){

                    $pieLeido= true;

                    if(!$this->leerPie($linea)){
                        $this->addError('retdj_id', 'Error al intentar leer el pie');
                    }
                    break;

                } else if($esDetalle && !$this->leerDetalle($linea)){

                    $this->addError('retdj_id', 'Error al intentar leer el detalle ' . ($this->cant + 1));
                    break;

                }

                $this->cant++;
            }


        } while($linea !== false);

        fclose($archivo);

        if(!$this->hasErrors() && $this->cant === 0)
            $this->addError('retdj_id', 'El archivo no contiene detalles');
        else if($this->cant != $this->cantidadDetallesProvistos)
            $this->addError('retdj_id', 'La cantidad de detalles esperados no coincide con la cantidad leida');
        else if($this->totalProvisto != $this->monto)
            $this->addError('retdj_id', 'El monto provisto no coincide con la sumatoria de los montos de los detalles');


        return $this->hasErrors();
    }
}
