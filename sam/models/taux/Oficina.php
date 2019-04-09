<?php

namespace app\models\taux;

use Yii;

use app\utils\db\utbFin;
use app\utils\db\utb;

/**
 * This is the model class for table "sam.muni_oficina".
 *
 * @property integer $ofi_id
 * @property string $nombre
 * @property integer $resp
 * @property string $sec_id
 * @property string $part_id
 * @property string $fchmod
 * @property integer $usrmod
 */
class Oficina extends \yii\db\ActiveRecord{

    const CONST_CODIGO_TABLA = 133;

    public $ejercicio;
    public $part_formatoaux;
    public $part_nom;

    public static function tableName(){

        return 'sam.muni_oficina';
    }

    public function __construct(){

        $this->ejercicio = $this->getEjercicio();
    }

    public function rules(){

        $ret = [];

        /**
         * Campos requeridos
         */
        $ret[] = [
         [ 'ofi_id' ],
         'required',
         'on' => [ 'update', 'delete' ],
        ];

        $ret[] = [
         [ 'nombre', 'sec_id' ],
         'required',
         'on' => [ 'insert', 'update' ],
        ];

        /**
         * Tipos de datos
         */
        $ret[] = [
            [ 'ofi_id' ],
            'integer',
            'on' => [ 'update', 'delete' ],
        ];

        $ret[] = [
            [ 'nombre' ],
            'string',
            'max' => 35,
            'on' => [ 'insert', 'update' ],
        ];

        $ret[] = [
            [ 'resp' ],
            'string',
            'max' => 45,
            'on' => [ 'insert', 'update' ],
        ];

        $ret[] = [
            [ 'sec_id', 'part_id' ],
            'integer',
            'on' => [ 'insert', 'update' ],
        ];

        $ret[] = [
            [ 'part_formatoaux' ],
            'string',
            'on' => [ 'insert', 'update' ],
        ];

        /**
         * VALIDACIONES ESPECÍFICAS
         */
        $ret[] = [
            [ 'part_id' ],
            'validarNivelPartida',
            'on' => [ 'insert', 'update' ],
            'skipOnEmpty' => false,
            'skipOnError' => false,
        ];

        $ret[] = [
            [ 'ofi_id' ],
            'validarQueNoExistaUsuarioConOficina',
            'on' => [ 'delete' ],
            'skipOnEmpty' => false,
            'skipOnError' => false,
        ];

        return $ret;
    }

    public function scenarios(){

        return[

            'insert' => [

                'nombre', 'sec_id', 'resp', 'part_id', 'part_formatoaux'
            ],

            'delete' => [ 'ofi_id' ],

            'update' => [

                'ofi_id', 'nombre', 'sec_id', 'resp', 'part_id', 'part_formatoaux'
            ],

        ];
    }

    public function validarNivelPartida( $attribute, $params ){

        if( $this->permiteModificarPartida() && $this->$attribute != '' ){

            //Validar que la partida sea de último nivel
            $sqlNivel   = "SELECT ultimonivel FROM sam.fin_part_info(" . date( 'Y' ) . ",'" . $this->$attribute . "')";

            try{

                if( Yii::$app->db->createCommand( $sqlNivel )->queryScalar() == 'N' ){

                    $this->addError( $attribute, 'La partida debe ser de último nivel.' );

                }
            } catch( \Exception $e ){

                $this->addError( $attribute, 'La partida no existe.' );
            }

        }

    }

    public function validarQueNoExistaUsuarioConOficina( $attribute, $params ){

        $sql = "SELECT EXISTS( SELECT 1 FROM sam.sis_usuario WHERE oficina = $this->ofi_id )::integer";

        $existe = Yii::$app->db->createCommand( $sql )->queryScalar();

        if( $existe ){

            $this->addError( 'ofi_id', 'La oficina no se puede eliminar porque existen usuarios asignados a la misma.' );
        }
    }

    public function afterFind(){

        //Cargar datos de la partida
        $this->part_formatoaux  = $this->obtenerFormatoAuxPartida( $this->part_id );
        $this->part_nom         = $this->getNombrePartida( $this->part_id );

        $this->ejercicio = $this->getEjercicio();
    }

    public function beforeValidate(){

        if( $this->part_formatoaux != '' ){

            $part = $this->obtenerIdPartida( $this->part_formatoaux, date( 'Y' ) );

            $this->part_id = intVal( $part );
            $this->part_formatoaux = $this->obtenerFormatoAuxPartida( $part );

        } else {

            $this->part_id = '';

        }

        return true;
    }

    /**
     * Función que determina si el sistema seleccionado es el financiero.
     */
    public function sistemaFinanciero(){

        return Yii::$app->session->get( 'sis_id', 0 ) == 4;

    }

    /**
     * Función que se utiliza para obtener el año.
     */
    public function getEjercicio(){

        $anio = date ( 'Y' );

        if( $this->sistemaFinanciero() ){

            $anio = Yii::$app->session->get( 'fin.part_ejer', date( 'Y' ) );
        }

        return $anio;
    }

    /**
     * Función que se utiliza para obtener el ID de la partida.
     * @param integer $part_id Identificador de partida.
     */
    public function getNombrePartida( $part_id = 0 ){

        $sql =  "SELECT nombre FROM fin.part WHERE part_id = " . $part_id;

        return Yii::$app->db->createCommand( $sql )->queryScalar();
    }

    /**
     * Función que se utiliza para obtener el Id de una partida.
     */
    public function obtenerIdPartida( $dato, $anio = 0 ){

        if( $anio == 0 ){

            $anio = date( 'Y' );
        }

        if( strpos( $dato, '.' ) === false ){   //Según "nropart"
            $sql = "SELECT part_id FROM fin.part WHERE nropart = " . intVal( $dato ) . " AND anio = " . $anio;
        } else {
            $sql = "SELECT part_id FROM fin.part WHERE formatoaux = '" . $dato . "' AND anio = " . $anio;
        }

        return intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
    }

    /**
     * Función que se utiliza para obtener el formato auxiliar de una partida.
     */
    public function obtenerFormatoAuxPartida( $part_id ){

        $sql =  "SELECT formatoaux FROM fin.part WHERE part_id = " . $part_id;

        return Yii::$app->db->createCommand( $sql )->queryScalar();
    }

    /**
     * Función que se utiliza para saber si el usuario puede modificar los datos.
     */
    public static function permiteModificar(){

        $proceso = utb::getCampo( 'sam.tabla_aux', "cod = " . self::CONST_CODIGO_TABLA, 'accesoedita' );

        return utb::getExisteProceso( $proceso );
    }

    /**
     * Función que se utiliza para saber si el usuario puede modificar los datos.
     */
    public static function permiteModificarPartida(){

        return Oficina::sistemaFinanciero();
    }

    /**
     * Función que se utiliza para obtener un arreglo de secretarias.
     */
    public static function getSecretarias( $tipo ){

        return utb::getAux( 'sam.muni_sec', 'cod', 'nombre', $tipo );
    }

    /**
     * Función que se utiliza para obtener un arreglo de secretarias.
     */
    public static function getResponsables(){

        return utb::getAux( 'sam.sis_usuario', 'usr_id', 'nombre' );
    }

    /**
     * Función que se utiliza para obtener el listado de medios de pago.
     * @param integer $sec_id Identificador de secretaria. 0 por defecto.
     * @return array Arreglo con los medios de pago.
     */
    public static function getListado( $sec_id = 0 ){

        $sql =  "SELECT t.ofi_id, t.nombre, t.resp, t.sec_id, s.nombre as sec_nom, t.part_id, p.nombre as part_nom, " .
                "t.fchmod, t.usrmod, u.nombre || ' - ' || to_char(t.fchmod,'dd/mm/yyyy') as modif " .
                " FROM sam.muni_oficina t " .
                " INNER JOIN sam.sis_usuario u ON t.usrmod = u.usr_id " .
                " INNER JOIN sam.muni_sec s ON t.sec_id = s.cod " .
                " LEFT JOIN fin.part p ON t.part_id = p.part_id ";

        if( $sec_id != 0 ){

            $sql .= " WHERE t.sec_id = $sec_id ";
        }

        $sql .= " ORDER BY ofi_id ASC";

        return Yii::$app->db->createCommand( $sql )->queryAll();

    }

    public function ABMOficina( $action ){

        if( !$this->validate()){
            return false;
        }

        $res = false;

        switch( $action ){

            case 0:

                $res =  $this->insertar();
                break;

            case 2:

                $res =  $this->eliminar();
                break;

            case 3:

                $res =  $this->actualizar();
                break;
        }

        return $res;
    }

    /**
     * Función que se utiliza para obtener el siguiente ID de MDP.
     */
    private function getNextOfiID(){

        $sql = "SELECT COALESCE( MAX( ofi_id ), 0 )::integer + 1 FROM sam.muni_oficina";

        return Yii::$app->db->createCommand( $sql )->queryScalar();
    }

    private function insertar(){

        $transaction    = Yii::$app->db->beginTransaction();

        $oficina        = $this->getNextOfiID();

        $sql =  "INSERT INTO sam.muni_oficina( ofi_id, nombre, sec_id, resp, part_id, fchmod, usrmod ) VALUES (" .
                " $oficina, '$this->nombre', $this->sec_id, '$this->resp', " . intVal( $this->part_id ) . ", CURRENT_TIMESTAMP, " . Yii::$app->user->id . ")";

        try{

            Yii::$app->db->createCommand( $sql )->execute();

        } catch(\Exception $e ){

            $this->addError( 'ofi_id', DBException::getMensaje( $e ) );
            $transaction->rollback();
            return false;
        }

        $transaction->commit();
        return true;
    }

    /**
     * Función que se utiliza para actualizar un medio de pago.
     */
    private function eliminar(){

        $sql =  "DELETE FROM sam.muni_oficina  WHERE ofi_id = " . $this->ofi_id;

        $transaction = Yii::$app->db->beginTransaction();

        try{

            //Yii::$app->db->createCommand( $sql )->execute();

        }catch( \Exception $e ){

            $this->addError( 'mdp', DBException::getMensaje( $e ) );

            $transaction->rollback();

            return false;
        }

        $transaction->commit();

        return true;
    }

    /**
     * Función que se utiliza para actualizar un medio de pago.
     */
    private function actualizar(){

        $sql =  "UPDATE sam.muni_oficina SET nombre = '$this->nombre', sec_id = $this->sec_id, resp = '$this->resp', " .
                "part_id = " . intVal( $this->part_id ) . " WHERE ofi_id = " . $this->ofi_id;

        $transaction = Yii::$app->db->beginTransaction();

        try{

            Yii::$app->db->createCommand( $sql )->execute();

        }catch( \Exception $e ){

            $this->addError( 'mdp', DBException::getMensaje( $e ) );

            $transaction->rollback();

            return false;
        }

        $transaction->commit();

        return true;
    }

    /**
     * Función que se utiliza para agregar, modificar o eliminar cuotas a un medio de pago.
     */
    public function ABMCuotas( $cuota, $recargo, $action, &$arrayCuotas ){

        $array = $arrayCuotas;

        $existe = false;

        switch( $action ){

            case 0: //Insertar una cuota

                //Validar que no se encuentre ingresada la cuota
                foreach( $array as $ar ){
                    if( $ar['cuotas'] == $cuota ){
                        $existe = true;
                    }
                }

                if( $existe ){
                    $this->addError( 'mdp', 'La cuota ingresada ya existe.' );

                    return false;
                }

                $arrayTemporal = [
                    'cuotas' => $cuota,
                    'rec'    => $recargo,
                ];

                $array[] = $arrayTemporal;

                break;

            case 2: //Eliminar una cuota

                //Validar que se encuentre ingresada la cuota
                foreach( $array as $ar ){
                    if( $ar['cuotas'] == $cuota ){
                        $existe = true;
                    }
                }

                if( !$existe ){
                    $this->addError( 'mdp', 'La cuota ingresada no existe.' );

                    return false;
                }

                //Validar que se encuentre ingresada la cuota
                $arrayTemporal = [];

                foreach( $array as $ar ){
                    if( $ar['cuotas'] != $cuota ){
                        $arrayTemporal[] = $ar;
                    }
                }

                $array = $arrayTemporal;

                break;

            case 3: //Modificar el recargo de una cuota

                //Validar que se encuentre ingresada la cuota
                foreach( $array as $ar ){
                    if( $ar['cuotas'] == $cuota ){
                        $existe = true;
                    }
                }

                if( !$existe ){
                    $this->addError( 'mdp', 'La cuota ingresada no existe.' );

                    return false;
                }

                $arrayTemporal = $array;

                foreach( $arrayTemporal as &$ar ){

                    if( $ar['cuotas'] == $cuota ){
                        $ar['rec'] = $recargo;
                    }
                }

                $array = $arrayTemporal;

                break;
        }

        $arrayCuotas = $array;

        return true;
    }
}

?>
