<?php

namespace app\controllers\objeto;

use Yii;
use yii\web\Controller;
use app\models\objeto\ComerRubro;
use app\utils\db\utb;
use app\assets\AppAsset;

/**
 * PersonaController implements the CRUD actions for Persona model.
 */
class RubroController extends Controller{

    /**
     * Función que se utiliza para mostrar datos de rubros.
     * @param integer $id Identificador de rubro.
     * @param integer $perdesde Período desde del rubro que se selecciona.
     * @param integer $action Identificador de tipo de acción.
     * @param array $arregloRubros Arreglo con los rubros presentes en memoria
     * @param string $pjaxAActualizar Id del Pjax que se debe actualizar.
     * @param string $selectorModal Id de la ventana modal donde se dibuja la forma.
     * @param integer $tipoObjeto Indica el tipo de objeto para el que se dibuja la ventana modal.
     * @param boolean $dadosDeBaja Filtro que indica si se muestran solo los rubros "Dados de Baja".
     * @param boolean $soloVigentes Filtro que indica si se muestran solo los rubros "Vigentes".
     */
    public static function rubro( $id, $perdesde, $action = 1, $arregloRubros, $pjaxAActualizar = '', $selectorModal = '', $tipoObjeto = 3 ){

        $model = ComerRubro::getRubroSegunID( $arregloRubros, $id, $perdesde );

        return Yii::$app->controller->renderPartial( '//objeto/rubro/_form_rubro', [

            'model'     => $model,
            'action'    => $action,
            'pjaxAActualizar'   => $pjaxAActualizar,
            'selectorModal'     => $selectorModal,

            'arrayNomeclador' => ComerRubro::getNomecladores( $tipoObjeto ),

        ]);
    }

    /**
     * Función que se utiliza para obtener un arreglo de rubros segń.
     * @param integer $nomen_id Identificaodr de nomeclador.
     * @param string $term Caracteres de búsqueda.
     */
    public function actionSugerenciarubro( $nomen_id = 0, $term= '' ){

        /**
         * Se deben ingresar 3 o más letras para que se realice la búsqueda.
         */

        $ret= [];

        if( $term == '' || strlen( $term ) < 3 ){
            return json_encode( $ret );
        }

        $condicion = "upper(nombre) Like upper('%$term%')";

        if( $nomen_id != '' ) $condicion .= " And nomen_id = '$nomen_id'";

        $ret = utb::getAux( 'rubro', 'rubro_id', 'nombre', 0, $condicion );

        if( $ret === false ) $ret= [];

        return json_encode( $ret );
    }

    /**
     * Función que se utiliza para obtener el ID de un tributo según el nombre seleccionado.
     * @param string $nombre Nombre del tributo seleccionado.
     */
    public function actionCodigorubro( $nombre= '' ){

        $ret= utb::getCampo('rubro', "nombre = '$nombre'", 'rubro_id');

        return $ret;
    }

    /**
     * Función que se utiliza para obtener el nombre de un nomeclador.
     * @param integer  $rubro_id Identificadr de rubro.
     * @param integer $nomen_id Identificador de nomeclador.
     */
    public function actionNombrerubro( $rubro_id, $nomen_id) {
		
		$rubro_id = $nomen_id . str_pad( $rubro_id, 7, "0", STR_PAD_LEFT);
		
        $ret= utb::getCampo( 'rubro', "rubro_id = '$rubro_id'", "nombre" );

        $devolver = [ 'rubro_id' => $rubro_id, 'rubro_nom' => $ret ];
		
        return json_encode( $devolver );
    }

}

?>
