<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\utils\db\utb;

class AjaxController extends Controller {

    /**
     * Forma que se utiliza para
     *
     * Siempre retorna un array.
     */

   /**
    * Función que se utiliza para obtener el código y nombre de objeto.
    * @param integer $tobj Tipo de objeto.
    * @param string $obj_id Identificador de objeto.
    *
    * @return JSON {"obj_id": código de objeto, "obj_nom": nombre de objeto}
    */
    public function actionObjeto( $tobj = 3, $obj_id = '' ){

        Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;

        $error  = [];
        $datos  = null;

		if( $obj_id != '' ){
			$obj_id = utb::getObjeto( $tobj, $obj_id );

			if( !utb::verificarExistenciaObjeto( $tobj, "'" . $obj_id . "'" ) ){

				$obj_id = '';

            } else {

                $nombre = utb::getCampo( 'objeto', "obj_id = '" . $obj_id ."'", "nombre" );

            }
			
			$torigen = 'OBJ';
			
			if ( $tobj == 1 ) $torigen = 'INM';
			$domi = utb::getCampo( 'v_domi', "obj_id = '" . $obj_id ."' and torigen='" . $torigen . "'", "direccion || ' - ' || locprov" );

            $datos = [
                'obj_id'    => $obj_id,
                'obj_nom'   => $nombre,
				'domi'		=> $domi
            ];

		}

        return $datos;

    }



    /*************************** SISTEMA FINANCIERO ***************************************/

    /**
     *
     * Obtiene el codigo y el nombre del material a partir de su codigo ó de su nombre
     *
     * @param int $codigo= '' Codigo del material a buscar.
     * @param string $nombre= '' Nombre del material a buscar. Debe ser provisto en caso de que no se provea el codigo.
     *
     * @return JSON {"mat_id": codigo del material, "nombre": nombre del material}
     */
    public function actionMaterial( $codigo = 0, $mat_nom = '' ){

        Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;

        $tabla              = 'fin.mat';
        $camposRequeridos   = 'mat_id, nombre, costo';
        $codigo             = intval( $codigo );
        $datos              = false;

        if( $codigo > 0 )
            $datos= utb::getVariosCampos($tabla, "mat_id = $codigo", $camposRequeridos);
        else if($nombre != '')
            $datos= utb::getVariosCampos($tabla, "nombre = '$nombre'", $camposRequeridos);

        if($datos !== false)
            return $datos;

        return null;
    }

    /**
     *
     * Obtiene el codigo y el nombre de proveedor a partir de su codigo ó de su nombre
     *
     * @param int $codigo= '' Codigo del proveedor a buscar.
     * @param string $nombre= '' Nombre del proveedor a buscar. Debe ser provisto en caso de que no se provea el codigo.
     *
     * @return JSON {"prov_id": codigo del proveedor, "nombre": nombre del proveedor}
     */
    public function actionProveedor( $codigo = 0, $nombre = '' ){

        Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;

        $tabla              = 'fin.proveedor';
        $camposRequeridos   = 'prov_id, obj_id, nombre';
        $codigo             = intval( $codigo );
        $datos              = false;

        if( $codigo > 0 )
            $datos= utb::getVariosCampos($tabla, "prov_id = $codigo", $camposRequeridos);
        else if($nombre != '')
            $datos= utb::getVariosCampos($tabla, "nombre = '$nombre'", $camposRequeridos);

        if($datos !== false)
            return $datos;

        return null;
    }

    /**
     * Función que se utiliza para obtener un arreglo de nombres de proveedores.
     * @param string $term Caracteres de búsqueda.
     */
    public function actionSugerenciaproveedor( $term = '' ){

        /**
         * Se deben ingresar 3 o más letras para que se realice la búsqueda.
         */

        $ret = [];

        if( $term == '' || strlen( $term ) < 3 ){
            return json_encode( $ret );
        }

        $condicion = "upper(nombre) Like upper('%$term%')";

        $ret = utb::getAux( 'fin.proveedor', 'prov_id', 'nombre', 0, $condicion );

        if( $ret === false ) $ret = [];

        return json_encode( $ret );
    }

    /**
     * Función que obtiene los datos de una persona.
     * @param string $obj_id Código de la persona.
     */
    public function actionPersona( $obj_id = '' ){

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $tabla              = 'v_persona';
        $camposRequeridos   = 'obj_id, nombre, tipo, cuit, iva, mail, tel, cel';
        $codigo             = utb::getObjeto( 3, $obj_id );
        $datos              = false;

        if( $codigo != '' ){
            $datos= utb::getVariosCampos($tabla, "obj_id = '$codigo' and est='A'", $camposRequeridos);
        }

        if( $datos !== false ){
            return $datos;
        }

        return null;
    }

    /**
     * Función que obtiene los datos de un rubro.
     * @param integer $codigo Código del rubro.
     * @param string $nombre Nombre del rubro.
     */
    public function actionRubro( $codigo = 0, $nombre = '' ){

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $tabla              = 'fin.proveedor_trubro';
        $camposRequeridos   = 'cod, nombre';
        $codigo             = intval( $codigo );
        $datos              = false;

        if( $codigo > 0 ){
            $datos= utb::getVariosCampos( $tabla, "cod = $codigo", $camposRequeridos );
        } else if( $nombre != '' ){
            $datos= utb::getVariosCampos( $tabla, "nombre = '$nombre'", $camposRequeridos );
        }

        if( $datos !== false ){
            return $datos;
        }

        return null;
    }

    /**
     * Función que se utiliza para obtener un arreglo de nombres de rubros.
     * @param string $term Caracteres de búsqueda.
     */
    public function actionSugerenciarubro( $term = '' ){

        /**
         * Se deben ingresar 3 o más letras para que se realice la búsqueda.
         */

        $ret = [];

        if( $term == '' || strlen( $term ) < 3 ){
            return json_encode( $ret );
        }

        $condicion = "upper(nombre) Like upper('%$term%')";

        $ret = utb::getAux( 'fin.proveedor_trubro', 'cod', 'nombre', 0, $condicion );

        if( $ret === false ) $ret = [];

        return json_encode( $ret );
    }

}
?>
