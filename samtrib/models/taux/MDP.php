<?php

/**
 * Modelo para la tabla auxiliar MDP (Medios de Pago).
 */

namespace app\models\taux;
use yii\data\ArrayDataProvider;
use app\utils\db\utb;
use app\utils\helpers\DBException;
use Yii;

/**
 * Modelo para la tabla "caja_mdp".
 *
 * @property integer mdp
 * @property string nombre
 * @property string tipo
 * @property decimal cotiza
 * @property string simbolo
 * @property integer habilitado
 * @property integer financia
 */

class MDP extends \yii\db\ActiveRecord {

    const CONST_CODIGO_TABLA = 85;

    public static function tableName(){

        return 'caja_mdp';
    }

    public function rules(){

        $ret = [];

        $ret[] = [['mdp','habilitado','financia'],'integer',];

        $ret[] = [['cotiza'],'number'];

        $ret[] = [['nombre','tipo','simbolo'],'string'];

        $ret[] = [['nombre'],'string', 'max' => '30' ];

        return $ret;

    }


    /**
     * Función que se utiliza para saber si el usuario puede modificar los datos.
     */
    public static function permiteModificar(){

        $proceso = utb::getCampo( 'sam.tabla_aux', "cod = " . self::CONST_CODIGO_TABLA, 'accesoedita' );

        return utb::getExisteProceso( $proceso );
    }

    /**
     * Función que se utiliza para obtener el listado de medios de pago.
     * @return array Arreglo con los medios de pago.
     */
    public static function getListado(){

        $sql =  "SELECT m.mdp, m.nombre, m.tipo, t.nombre as tipo_nom, m.cotiza, m.simbolo, CASE m.habilitado WHEN 0 THEN 'Deshabilitado' ELSE 'Habilitado' END as habilitado, m.financia " .
                "FROM caja_mdp m " .
                " JOIN caja_tmdp t ON m.tipo = t.cod " .
                "ORDER BY nombre ASC";

        return Yii::$app->db->createCommand( $sql )->queryAll();

    }

    public static function getListadoTiposMDP(){

        return utb::getAux( 'caja_tmdp', 'cod', 'nombre', 1 );
    }

    public function getListadoCuotas( $mdp ){

        $sql = "SELECT cuotas, rec * 100 as rec FROM caja_mdp_cuota WHERE mdp = " . $mdp;

        return Yii::$app->db->createCommand( $sql )->queryAll();
    }

    public function ABMMdp( $action, $arrayCuotas ){

        $res = false;

        switch( $action ){

            case 0:

                $res =  $this->insertar( $arrayCuotas );
                break;

            case 3:

                $res =  $this->actualizar( $arrayCuotas );
                break;
        }

        return $res;
    }

    /**
     * Función que se utiliza para grabar las cuotas en la BD.
     * @param integer $mdp Identificador del medio de
     */
    private function grabarCuotas( $mdp, $arrayCuotas, $financia ){

        $sql = "DELETE FROM caja_mdp_cuota WHERE mdp = " . $mdp;

        try{

            Yii::$app->db->createCommand( $sql )->execute();

            if( count( $arrayCuotas ) > 0 && $financia ){

                foreach( $arrayCuotas as $ar ){

                    $sql = "INSERT INTO caja_mdp_cuota ( mdp, cuotas, rec ) VALUES (" . $mdp . "," . $ar['cuotas'] . ", (" . floatVal( $ar['rec'] ) / 100  . ") )";

                    Yii::$app->db->createCommand( $sql )->execute();
                }
            }

        } catch(\Exception $e){

            $this->addError( 'mdp', 'Ocurrió un error al grabar las cuotas.' );
            return false;
        }

        return true;
    }

    /**
     * Función que se utiliza para obtener el siguiente ID de MDP.
     */
    private function getNextMDP(){

        $sql = "SELECT MAX( mdp ) + 1 FROM caja_mdp";

        return Yii::$app->db->createCommand( $sql )->queryScalar();
    }

    private function insertar( $arrayCuotas ){

        if( !$this->validate()){
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        $this->mdp = $this->getNextMDP();

        $sql =  "INSERT INTO caja_mdp ( mdp, nombre, tipo, cotiza, simbolo, habilitado, financia ) VALUES (" .
                $this->mdp . ",'" . $this->nombre . "','" . $this->tipo . "'," . $this->cotiza . "," .
                "'" . $this->simbolo . "'," . $this->habilitado . "," . $this->financia . ")";

        try{

            Yii::$app->db->createCommand( $sql )->execute();

            if( !$this->grabarCuotas( $this->mdp, $arrayCuotas, $this->financia ) ){

                $transaction->rollback();
                return false;
            }

        } catch(\Exception $e ){

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
    private function actualizar( $arrayCuotas ){

        if( !$this->validate()){
            return false;
        }

        $sql =  "UPDATE caja_mdp SET nombre = '" . $this->nombre . "', tipo ='" . $this->tipo . "', cotiza =" . $this->cotiza . "," .
                "habilitado = " . $this->habilitado . ",financia =" . $this->financia . ",simbolo = '" . $this->simbolo . "' WHERE mdp = " . $this->mdp;

        $transaction = Yii::$app->db->beginTransaction();

        try{

            Yii::$app->db->createCommand( $sql )->execute();

            if( !$this->grabarCuotas( $this->mdp, $arrayCuotas, $this->financia ) ){

                $transaction->rollback();

                return false;
            }

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
