<?php
/**
 * Controlador utilizado para la gestion de oficinas.
 */

namespace app\controllers\taux;

use Yii;
use app\models\taux\Oficina;
use yii\web\Controller;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\data\ArrayDataProvider;

class OficinaController extends Controller {

    const CONST_MENSAJE             = 'const_oficina_mensaje';
    const CONST_MENSAJE_ERROR       = 'const_oficina_mensaje_error';
    const CONST_OFICINA_PJAXDATOS   = '#oficina_pjaxDatos';
    const CONST_OFICINA_PJAXPARTIDA = "#oficina_pjaxCambiaPartida";
    const CONST_OFICINA_PJAXGRILLA  = "#oficina_pjaxGrillaOficinas";

    public function actionView( $id = 0 ){

        return $this->view( $id );
    }

    public function actionIndex(){

        return $this->redirect(['view']);
    }

    /**
     * Función que se utiliza para dibujar la forma.
     */
    private function view( $oficina ){

        $action = 1;
        $sec_id = 0;

        $model  = $this->findModel( $oficina );

        if( Yii::$app->request->isPjax ){

            if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_OFICINA_PJAXDATOS ){

                $oficina    = Yii::$app->request->get( 'oficina', 0 );
                $action     = Yii::$app->request->get( 'action', 1 );

                $model = $this->findModel( $oficina );

            }

            if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_OFICINA_PJAXGRILLA ){

                $sec_id    = Yii::$app->request->get( 'sec_id', 0 );

            }

        }

        if( Yii::$app->request->isPost ){

            $action = Yii::$app->request->post( 'txAction', 1 );

            switch( $action ){

                case 0:

                    $model->setScenario( 'insert' );
                    break;

                case 2:

                    $model->setScenario( 'delete' );
                    break;

                case 3:

                    $model->setScenario( 'update' );
                    break;
            }

            //Grabar los datos
            if( $model->load( Yii::$app->request->post() ) ){

                if( $model->ABMOficina( $action ) ){

                    Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

                    return $this->redirect([ 'view', 'id' => $model->ofi_id ]);
                }

            }
        }

        $dataProvider = new ArrayDataProvider([

            'models' => Oficina::getListado( $sec_id ),
            'key' => 'ofi_id',

        ]);

        return $this->render( '_form', [

            'mensaje'           => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),

            'model'             => $model,
            'action'            => $action,

            'arraySecretaria'   => Oficina::getSecretarias( 2 ),
            'permiteModificar'  => Oficina::permiteModificar(),

            'dataProvider'      => $dataProvider,

        ]);

    }

    public function datos( $model = [], $action = 1 ){

        if( Yii::$app->request->isPjax ){

            if( Yii::$app->request->isGet ){

                # Cuando se cambia la partida
                if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_OFICINA_PJAXPARTIDA ){

                    $model->part_formatoaux = Yii::$app->request->get( 'partida', '' );

                    if( $model->part_formatoaux != '' ){

                        $model->part_id         = $model->obtenerIdPartida( $model->part_formatoaux );
                        $model->part_formatoaux = $model->obtenerFormatoAuxPartida( $model->part_id );

                    } else {

                        $model->part_formatoaux = '';
                        $model->part_nom        = '';

                    }

                    if( $model->part_formatoaux == '' ){
                        Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1001 );
                    }

                    $model->part_nom     = $model->getNombrePartida( $model->part_id );
                }
            }
        }

        return $this->render( 'datos', [

            'model'         => $model,
            'action'        => $action,

            'error'         => OficinaController::getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, 0 ) ),

            'arraySecretaria'   => Oficina::getSecretarias( 0 ),
            'arrayResponsables' => Oficina::getResponsables(),

            'anioActual'        => date( 'Y' ),

            'permiteModificarPartida'  => Oficina::permiteModificarPartida(),

        ]);
    }

    private function findModel( $id = 1 )
    {

        $model = Oficina::findOne( intval( $id ) );

        if( $model == null )
            $model = new Oficina();

        return $model;

    }

    private function getMensaje( $id = 0 ){

        $title = '';

        switch( $id ){

            case 0:

                $title = '';
                break;

            case 1:

                $title = 'Los datos se grabaron correctamente.';
                break;

            case 1001:

                $title = 'La partida ingresada no existe.';
                break;
        }

        return $title;
    }

}

?>
