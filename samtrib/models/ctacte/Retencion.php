<?php

namespace app\models\ctacte;

 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;

use Yii;

use app\models\ctacte\RetencionDetalle;

use app\utils\db\utb;
use app\utils\db\Fecha;
use app\utils\helpers\DBException;
use yii\data\ArrayDataProvider;


class Retencion extends \yii\db\ActiveRecord{

    //distintos estados que puede tener el registro
    const ESTADO_APROBADA   = 'A';
    const ESTADO_BAJA       = 'B';
    const ESTADO_PENDIENTE  = 'P';

    /*
     * DATOS PARA LA LECTURA DEL ARCHIVO DE TEXTO
     */

    const IDENTIFICADOR_CABECERA    = "CAB";
    const IDENTIFICADOR_PIE         = "PIE";

    //largos totales
    const LARGO_CABECERA    = 28;
    const LARGO_DETALLE     = 74;
    const LARGO_PIE         = 20;

    const OFFSET_LINEA = -1;

    //CAMPOS DE LA CABECERA
    const COMIENZO_ID   = 1;
    const LONGITUD_ID   = 3;

    const COMIENZO_AGENTE   = 4;
    const LONGITUD_AGENTE   = 8;

    const COMIENZO_CUIT = 13;
    const LONGITUD_CUIT = 11;

    const COMIENZO_ANIO = 23;
    const LONGITUA_ANIO = 4;

    const COMIENZO_MES  = 27;
    const LONGITUD_MES  = 2;

    //CAMPOS DEL PIE
    const COMIENZO_ID_PIE= 1;
    const LONGITUD_ID_PIE= 3;

    const COMIENZO_FILAS= 4;
    const LONGITUD_FILAS= 6;

    const COMIENZO_TOTAL= 10;
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

	public function __construct( $ag_rete = '' ){

		$this->ag_rete= $ag_rete;
		$this->retdj_id= 0;
		$this->fchpresenta = date('Y/m/d');
		$this->puedeEliminar= true;
        $this->puedeModificar= true;
        $this->necesitaAprobarse= false;
        $this->archivo= null;
        $this->cantidadDetallesProvistos= 0;
        $this->totalProvisto= 0;
        $this->detalles= [];

		//se obtiene el cuit y la denominacion de la persona
        if( $this->ag_rete != '' ){

            $datos= utb::getVariosCampos('v_persona', "ag_rete = '$this->ag_rete'", 'nombre, cuit');

            if( $datos !== false ){
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
                'on' => ['update', 'delete', 'aprobar' ]
                ];

		$ret[]= [
				['ag_rete', 'anio', 'mes', 'fchpresenta'],
				'required',
				'on' => ['insert', 'update']
				];

        $ret[]= [
                'archivo',
                'required',
                'on' => [ 'importar' ]
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
                'on' => ['update', 'delete', 'aprobar' ]
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
				'on' => [ 'insert' ]
				];

        $ret[] = [
            'detalles',
            'validarDetalles',
            'on' => [ 'insert', 'update' ],
            'skipOnEmpty' => false,
            'skipOnError' => false,
        ];

        $ret[] = [
            [ 'retdj_id', 'cuit', 'denominacion', 'anio', 'mes', 'fchpresenta', 'ag_rete' ],
            'required',
            'on' => [ 'importar' ],
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

		return $ret;
    }

    public function scenarios(){
    	return [
    		'insert' => [
                'ag_rete', 'cuit', 'denominacion', 'anio', 'mes', 'fchpresenta', 'detalles',
            ],

    		'update' => [
                'retdj_id', 'ag_rete', 'cuit', 'denominacion',  'retdj_id', 'anio', 'mes', 'fchpresenta', 'detalles',
            ],

            'delete' => ['retdj_id'],

            'aprobar' => ['retdj_id'],

            'importar' => [ 'retdj_id', 'cuit', 'denominacion', 'anio', 'mes', 'fchpresenta', 'ag_rete','archivo' ]
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


    	// if($this->fchpresenta != null && $this->fchpresenta != '' && $this->getScenario() != 'importar')
        // 	$this->fchpresenta= Fecha::usuarioToBD($this->fchpresenta);

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
	
	public function validarDetalles( $attribute, $params ){

        if( count( $this->$attribute ) == 0 ){

            $this->addError( $attribute, 'Debe ingresar al menos una retención.' );
        }
    }

    /**
     * Función que se utiliza para verificar que no exista una DJ para el período ingresado.
     */
    public static function verificarExistenciaDJ( $ag_rete, $anio, $mes ){

        $sql =  "SELECT EXISTS( SELECT 1 FROM ret WHERE anio = $anio AND mes = $mes AND ag_rete = '$ag_rete')";

        return Yii::$app->db->createCommand( $sql )->queryScalar();

    }

    /**
     * Si es un registro nuevo, lo inserta. Si es un registro existente, lo modifica
     */
    public function grabar(  ){

        //$this->setDetalles($detalles);

    	$scenario = $this->isNewRecord ? 'insert' : 'update';
    	$this->setScenario($scenario);

    	if(!$this->validate()) return false;

    	$trans = Yii::$app->db->beginTransaction();

    	$this->monto   = $this->calcularMonto( $this->detalles );
    	$this->cant    = count($this->detalles);

    	if($this->isNewRecord){

    		$codigo= $this->proximoCodigo();

    		$sql= "Insert Into ret(retdj_id, ag_rete, anio, mes, cant, monto, fchpresenta, est, fchmod, usrmod)" .
    				" Values($codigo, '$this->ag_rete', $this->anio, $this->mes, $this->cant, $this->monto, '$this->fchpresenta', '" . self::ESTADO_PENDIENTE . "', current_timestamp," . Yii::$app->user->id . " )";

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

    public function aprobar( &$ctacte_id = 0 ){

        if( $this->est != self::ESTADO_PENDIENTE ){

            $this->addError('retdj_id', 'Solo los registros con estado pendiente pueden ser aprobados');
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try{
            $sql = "Update ret Set est = 'A' Where retdj_id = $this->retdj_id";
            $sql = Yii::$app->db->createCommand($sql)->execute();

            $sql = "SELECT * FROM sam.uf_ret_gen_comprob( $this->retdj_id )";

            $ctacte_id = Yii::$app->db->createCommand( $sql )->queryScalar();

        } catch(\Exception $e){

            $this->addError( 'retdj_id', DBException::getMensaje( $e ) );

            $transaction->rollback();

            return false;
        }

        $transaction->commit();

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

        $this->eliminarDetalles( $retdj_id );

        foreach($this->detalles as $detalle){

            if( !$detalle->desdeBD ){
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

    	foreach( $detalles as $d )
    		$monto += $d->monto;

    	return number_format( $monto, 2, '.', '' );
    }

    /**
    * Asigna los detalles al modelo
    *
    * @param Array Detalles que se deben asignar. Cada elemento del arreglo debe ser de tipo RetencionDetalle.
    *
    * @return void
    */
    public function setDetalles( $detalles = [] ){

        $this->monto    = $this->calcularMonto($detalles);
        $this->cant     = count($detalles);

        $this->detalles = $detalles;
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
			
		Yii::$app->session['titulo'] = "Listado de Retenciones";
        Yii::$app->session['condicion'] = Yii::$app->session['descripcion']; 
		Yii::$app->session['sql'] = $sql;
		Yii::$app->session['proceso_asig'] = 3500;
		Yii::$app->session['columns'] = [
			['attribute' => 'ret_id', 'label' => 'ID', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'retdj_id', 'label' => 'RetDJ', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'ag_rete', 'label' => 'Agente', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'anio', 'label' => 'Año', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'mes', 'label' => 'Mes', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'numero', 'label' => 'Núm.', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'fecha', 'label' => 'Fecha', 'format' => ['date', 'php:d/m/Y'], 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'lugar', 'label' => 'Lugar', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'base', 'label' => 'Base', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
			['attribute' => 'ali', 'label' => 'Alícuota', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
			['attribute' => 'monto', 'label' => 'Monto', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
			['attribute' => 'est', 'label' => 'Est.', 'contentOptions' => ['class' => 'grillaGrande']]
        ];	

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

        return strlen($linea) == self::LARGO_CABECERA && substr($linea, 0, self::LONGITUD_ID) == self::IDENTIFICADOR_CABECERA;
    }

    private function esDetalle($linea){

        return strlen($linea) == self::LARGO_DETALLE;
    }

    private function esPie( $linea ){

        return strlen($linea) == self::LARGO_PIE && substr($linea, 0, self::LONGITUD_ID_PIE ) == self::IDENTIFICADOR_PIE;
    }

    private function validarCabecera($linea){

        $campo= substr( $linea, self::COMIENZO_AGENTE + self::OFFSET_LINEA, self::LONGITUD_AGENTE); //Validar código de agente

        if( !$this->ag_rete == $campo ){
            $this->addError( 'ag_rete', 'No coincide el código de agente de retención.' );
        }

        $campo= substr( $linea, self::COMIENZO_CUIT + self::OFFSET_LINEA, self::LONGITUD_CUIT); //Validar CUIT de agente
        if( !$this->cuit == $campo ){
            $this->addError( 'cuit', 'No coincide el CUIT de agente de retención.' );
        }

        $campo= substr( $linea, self::COMIENZO_ANIO + self::OFFSET_LINEA, self::LONGITUA_ANIO); //Validar año de DJ
        if( ( intVal( $this->anio ) - intVal( $campo ) ) != 0 ){
            $this->addError( 'anio', 'No coincide el año a declarar.' );
        }

        $campo = substr( $linea, self::COMIENZO_MES + self::OFFSET_LINEA, self::LONGITUD_MES); //Validar mes de DJ
        if( ( intVal( $this->mes ) - intVal( $campo ) ) != 0 ){
            $this->addError( 'mes', 'No coincide el mes a declarar.' );
        }

    }

    private function leerDetalle( $linea ){

        $model = RetencionDetalle::cargarDesdeArchivo( $linea, $this->ag_rete, $this->anio, $this->mes );

        if( $model->hasErrors() ){

            $this->addErrors( $model->getErrors() );
            return false;
        }

        if( $this->ABMDetalleAlArreglo( $this->detalles, $model, 0 ) != 0 ){

            $this->addError( 'retdj_id', 'La retención ' . $model['numero'] . ' ya se encuentra ingresada.' );
            return false;
        }

        $this->totalProvisto += $model->monto;

        return true;
    }

    private function leerPie($linea){

        $campo= substr($linea, self::COMIENZO_FILAS + self::OFFSET_LINEA, self::LONGITUD_FILAS );
        $this->cantidadDetallesProvistos= intval($campo);

        $campo= substr($linea, self::COMIENZO_TOTAL + self::OFFSET_LINEA, self::LONGITUD_TOTAL );

        $this->monto= floatval(intval($campo) /100);

        return true;
    }

    /**
     * Función que se utiliza para cargar un archivo importado.
     */
    public function importarArchivo(){

        /**
         * El archivo puede tener 2 formas:
         *
         *  Tipo 1 - Incluir cabecera y pie.
         *  Tipo 2 - No incluir ni cabecera ni pie.
         */
        $this->setScenario( 'importar' );

        if( !$this->validate() ){

            return false;
        }

        $this->cant = 0;
        $pieLeido   = false;
        $archivo    = fopen($this->archivo->tempName, "r"); //Abrir el archivo
        $linea      = trim(fgets($archivo));    //Leer la primera línea! Debe ser la cabecera o un detalle
        $tipoArch   = 0;

        if( $linea === false ){

            $this->addError( 'retdj_id', 'El formato del archivo es incorrecto.' );
            fclose( $archivo );
            return false;

        } else {

            /**
             * Validar que la primera linea sea una cabecera o un detalle
             */
            if( !$this->esCabecera( $linea ) && !$this->esDetalle( $linea ) ){

                $this->addError('retdj_id', 'El formato de la cabecera es incorrecto.' );
                fclose( $archivo );
                return false;
            }

            if( $this->esCabecera( $linea ) ){

                $tipoArch = 1;

                //Validar que los datos de la cabecera sean correctos
                $this->validarCabecera( $linea );

            } else if( $this->esDetalle( $linea ) ){

                $tipoArch = 2;

                //Se debe leer la primera linea|
                $this->leerDetalle( $linea );
            }

            if( $this->hasErrors() ){

                fclose( $archivo );
                return false;
            }

            do{

                $linea = fgets( $archivo );

                if( $linea !== false ){

                    $linea = trim( $linea );

                    $esPie      = $this->esPie( $linea );
                    $esDetalle  = $this->esDetalle( $linea );

                    if( !$esPie && !$esDetalle ){   //si no es pie ni es detalle

                        /**
                         * Si el tipo de Archivo es 1, se debe comparar si la linea no podria corresponder a un pie
                         */
                        if( $tipoArch == 1 && !$pieLeido && fgets( $archivo ) === false ){

                            $this->addError('retdj_id', 'El formato del pie es incorrecto.' );

                        } else {

                            $this->addError('retdj_id', 'El formato del detalle ' . ($this->cant + 1) . ' es incorrecto.' );
                        }

                        break;
                    }

                    if( $tipoArch == 1 && $esPie ){

                        $pieLeido= true;

                        if( !$this->leerPie( $linea ) ){

                            $this->addError('retdj_id', 'Error al intentar leer el pie.' . $this->monto );
                        }
                        break;

                    } else if( $esDetalle && !$this->leerDetalle( $linea ) ){

                        $this->addError('retdj_id', 'Error en el detalle ' . ( $this->cant + 1 ) . '.' );
                        break;

                    }

                    $this->cant++;
                }

            } while( $linea !== false );

            fclose( $archivo );

            if( !$this->hasErrors() && $this->cant === 0 ){

                $this->addError( 'retdj_id', 'El archivo no contiene detalles.' );

            } else if( $tipoArch == 1 ){

                if( $this->cant != $this->cantidadDetallesProvistos ){

                    $this->addError( 'retdj_id', 'La cantidad de detalles esperados no coincide con la cantidad leida.' );

                } else if( $this->totalProvisto != $this->monto ){

                    $this->addError( 'retdj_id', 'El monto provisto no coincide con la sumatoria de los montos de los detalles.' );
                }
            }

            return !$this->hasErrors();

        }

    }


    /********** FUNCIONES UTILIZADAS DESDE EL SISTEMA WEB ********************************/

    /**
     * Función que se utiliza para obtener una retención arreglo de retenciones
     */
    public static function obtenerDetalleDeArreglo( $arrayRetenciones = [], $numero = 0 ){

        $retornar = [];

        if( count( $arrayRetenciones ) > 0 ){

            foreach( $arrayRetenciones as $array ){

                if( $array['numero'] == $numero ){

                    $retornar = $array;
                }
            }
        }

        return $retornar;
    }
	
	public function CargarDatosAExportar(){
		Yii::$app->session['titulo'] = "Retenciones Practicadas";
        Yii::$app->session['condicion'] = " <br>-Agente= $this->ag_rete " . 
										  "	<br>-CUIT= $this->cuit " .
										  "	<br>-Denominación= $this->denominacion " .
										  "	<br>-Periodo= $this->anio/$this->mes " .
										  "	<br>-Presentación= " . date('d/m/Y',strtotime($this->fchpresenta)) .
										  "	<br>-Estado= $this->est_nom"; 
		Yii::$app->session['sql'] = "select * from v_ret_det where retdj_id = $this->retdj_id and retdj_id > 0";
		Yii::$app->session['proceso_asig'] = 3500;
		Yii::$app->session['columns'] = [
			['label' => 'CUIT', 'attribute' => 'cuit'],
			['label' => 'Nombre', 'attribute' => 'nombre'],
			['label' => 'Fecha', 'attribute' => 'fecha'],
			['label' => 'Nº', 'attribute' => 'numero'],
			['label' => 'T. comprob.', 'attribute' => 'tcomprob'],
			['label' => 'Comprob.', 'attribute' => 'comprob', 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:center']],
			['label' => 'Base', 'attribute' => 'base', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right']],
			['label' => 'Alícuota', 'attribute' => 'ali', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right']],
			['label' => 'Monto', 'attribute' => 'monto', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right']],
			['label' => 'Est.', 'attribute' => 'est', 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:center']],
        ];
	}

    /**
     * Función que se utiliza para agregar retenciones al arregl de retenciones.
     */
    public function ABMDetalleAlArreglo( &$arrayRetenciones = [], $modelRete, $action = 0 ){

        switch( $action ){

            case 0:

                $existe = 0;

                //Validar que no se encuentre ingresada la retención
                if( count( $arrayRetenciones ) > 0 ){

                    foreach( $arrayRetenciones as $array ){

                        if( $array['numero'] == $modelRete['numero'] ){

                            $existe = 1;

                        }
                    }
                }

                if( $existe == 1 ){

                    return 1002;

                } else {

                    $modelRete->fecha = Fecha::usuarioToBD( $modelRete->fecha );

                    $modelRete['est'] = 'A';
                    $arrayRetenciones[] = $modelRete;

                    $file = fopen( "uploads/temp/rete" . Yii::$app->user->id . ".bin", "a" );
                    fwrite( $file, implode( ';;', $modelRete->toArray() ) );
                    fclose( $file );
                }

                break;

            case 2:

                if( count( $arrayRetenciones ) > 0 ){

                    $id = 0;

                    $arregloTemporal = [];

                    foreach( $arrayRetenciones as $array ){

                        if( $array['numero'] != $modelRete['numero'] ){

                            $arregloTemporal[] = $array;
                        }
                    }

                    $arrayRetenciones = $arregloTemporal;
                }

                break;

            case 3:

                if( count( $arrayRetenciones ) > 0 ){

                    $id = 0;

                    foreach( $arrayRetenciones as &$array ){

                        if( $array['numero'] == $modelRete['numero'] ){

                            $modelRete->fecha = Fecha::usuarioToBD( $modelRete->fecha );

                            $modelRete['est'] = 'A';
                            $array = $modelRete;
                        }
                    }
                }

                break;

        }

        return 0;

    }
	
	public function RetencionesImportadas(){
	
		$sql = "select * from v_ret_det where retdj_id=0 and tcomprob='MO' and fchmod::date = current_date and usrmod = " . Yii::$app->user->id; 
		$datos = Yii::$app->db->createCommand($sql)->queryAll();
		
		$dataProvider = new ArrayDataProvider([
							'allModels' => $datos,
							'pagination' => [
								'pageSize' => count($datos),
							]
						]);	
						
		return $dataProvider;				
	}
	
	public function ImportarReteFinanciero(){
	
		try{
			
			$sql = "select * from sam.fin_rete_exp_trib(" . Yii::$app->user->id . ")";
            Yii::$app->db->createCommand($sql)->execute();

        } catch( \Exception $e ){
            $this->addError( 'ret_id', DBException::getMensaje( $e ) );
            return false;
        }

        return true;

	}

    public function getPendientes( $criterio = '' ){

        if ( $criterio != '' ){
            $sql = "select * from v_ret_det where est='P' and $criterio";
            $datos = Yii::$app->db->createCommand($sql)->queryAll();    
        }else 
            $datos = null;
        

        $dataProvider = new ArrayDataProvider([
                'allModels' => $datos,
                'key' => 'ret_id',
                'pagination' => [
                    'pageSize' => count($datos),
                ],
            ]);

        return $dataProvider;
    }

    public function criterioPendiente( $datos ){

        $this->clearErrors();

        $condicion = '';

        if ( $datos['limpiar'] == 0 ){ // paso limiar = 1 cuando se preciona en volver

            /* valido objeto si se envían datos de filtro */
            if ( ($datos['obj_desde'] != '' and $datos['obj_hasta'] == '') or ($datos['obj_desde'] == '' and $datos['obj_hasta'] != '') )
                $this->addError( 'ret_id', 'Completa Rango Objeto' );

            if ( $datos['obj_desde'] != '' and $datos['obj_hasta'] != '' ){
                $objDesdeSinLetra = intVal(substr($datos['obj_desde'], 1));
                $objHastaSinLetra = intVal(substr($datos['obj_hasta'], 1));

                if ( $objDesdeSinLetra > $objHastaSinLetra )
                    $this->addError( 'ret_id', 'Rango de Objeto mal ingresado' );
                else
                    $condicion .= ($condicion == '' ? '' : ' and ') . " obj_id between '" . $datos['obj_desde'] . "' and '" . $datos['obj_hasta'] . "'";
            }
            /* fin validación de objeto */

            /* valido periodo */
            $perDesde = intVal($datos['perAnioDesde']) * 1000 + intVal($datos['perMesDesde']);
            $perHasta = intVal($datos['perAnioHasta']) * 1000 + intVal($datos['perMesHasta']);

            if ( $perDesde != 0 or $perHasta != 0 ){
                if ( $perDesde != 0 and $perHasta == 0 )
                    $this->addError( 'ret_id', 'Complete Periodo Hasta' );    
                elseif ( $perDesde == 0 and $perHasta != 0 )
                    $this->addError( 'ret_id', 'Complete Periodo Desde' );    
                elseif ( $perDesde > $perHasta )
                    $this->addError( 'ret_id', 'Rango de periodo mal ingresado' );    
                else
                    $condicion .= ($condicion == '' ? '' : ' and ') . " anio*1000+mes between $perDesde and $perHasta";
            }    

            /* fin valido periodo */

            /* valido fechas */
            if ( $datos['fechaDesde'] != '' or $datos['fechaHasta'] != '' ){
                if ( $datos['fechaDesde'] == '' and $datos['fechaHasta'] != '' )
                    $this->addError( 'ret_id', 'Complete rango de Fechas.' );
                elseif ( $datos['fechaDesde'] != '' and $datos['fechaHasta'] == '' )
                    $this->addError( 'ret_id', 'Complete rango de Fechas.' );
                elseif ( !Fecha::isFecha($datos['fechaDesde']) )
                    $this->addError( 'ret_id', 'Fecha desde incorrecta' );
                elseif ( !Fecha::isFecha($datos['fechaHasta']) )
                    $this->addError( 'ret_id', 'Fecha hasta incorrecta' );
                elseif ( Fecha::menor($datos['fechaDesde'], $datos['fechaHasta']) === false )
                    $this->addError( 'ret_id', 'Rango de Fechas incorrecta' );
                elseif ( $datos['fechaDesde'] != '' and $datos['fechaHasta'] != '' )
                    $condicion .= ($condicion == '' ? '' : ' and ') . " fecha between '" . $datos['fechaDesde'] . "' and '" . $datos['fechaHasta'] . "'";
            }    
            /* fin valido fechas */

            /* valido monto */
            if ( floatVal($datos['montoDesde']) > floatVal($datos['montoHasta']) )
                $this->addError( 'ret_id', 'Rango de Monto incorrecto' );

            if ( $datos['montoDesde'] != '' and $datos['montoHasta'] != '' )
                $condicion .= ($condicion == '' ? '' : ' and ') . " monto between " . $datos['montoDesde'] . " and " . $datos['montoHasta'];
            /* fin valido monto */

            /* agente de retencion */
            if ( $datos['agente'] != '' )
                $condicion .= ($condicion == '' ? '' : ' and ') . " ag_rete = '" . $datos['agente'] . "'";
        }    
        
        return $condicion;
    }

    public function marcarAplicada( $rete ){

        if ( $rete == '' ){
			$this->addError( 'ret_id', 'Seleccione al menos una retención' );
            return false;
		}
		
		try{
            
            $sql = "update ret_det set est='O' where ret_id in ( $rete )";
            Yii::$app->db->createCommand($sql)->execute();

        } catch( \Exception $e ){
            $this->addError( 'ret_id', $e->getMessage() );
            return false;
        }

        return true;

    }

}
