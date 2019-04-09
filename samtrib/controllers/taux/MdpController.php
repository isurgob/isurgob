<?php
/**
 * Controlador utilizado para la gestion de medios de pago.
 */

namespace app\controllers\taux;

use Yii;
use app\models\taux\MDP;
use yii\web\Controller;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\data\ArrayDataProvider;

class MdpController extends Controller {

    const CONST_MENSAJE         = 'const_mdp_mensaje';
    const CONST_MDP_PJAXDATOS   = '#mdp_pjaxDatos';
    const CONST_MDP_PJAXCUOTAS  = '#mdp_datos_pjaxCuotas';
    const CONST_MDP_PJAXMODALCUOTAS = '#mdp_datos_pjaxModalCuotas';
    const CONST_ARRAY_CUOTAS    = 'mdp_datos_arrayCuotas';

    public function actionView( $mdp = 0 ){

        return $this->view( $mdp );
    }

    public function actionIndex(){

        return $this->redirect(['view']);
    }

    /**
     * Función que se utiliza para dibujar la forma.
     */
    private function view( $mdp ){

        $action = 1;

        $model = $this->findModel( $mdp );
        $arrayCuotas = MDP::getListadoCuotas( $mdp );

        if( Yii::$app->request->isPjax ){

            if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_MDP_PJAXDATOS ){

                $mdp    = Yii::$app->request->get( 'mdp', 0 );
                $action = Yii::$app->request->get( 'action', 1 );

                $model = $this->findModel( $mdp );

                //Seteo el arreglo de cuotas
                $arrayCuotas = MDP::getListadoCuotas( $mdp );

                Yii::$app->session->set( self::CONST_ARRAY_CUOTAS, $arrayCuotas );
            }
        }

        if( Yii::$app->request->isPost ){

            //Grabar los datos
            if( $model->load( Yii::$app->request->post() ) ){

                $action = Yii::$app->request->post( 'txAction', 1 );
                $mdp    = $model->mdp;

                $arrayCuotas = Yii::$app->session->get( self::CONST_ARRAY_CUOTAS, [] );

                if( $model->ABMMdp( $action, $arrayCuotas ) ){

                    Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

                    return $this->redirect(['view', 'mdp' => $model->mdp ]);
                }

            }
        }

        $dataProvider = new ArrayDataProvider([

            'models' => MDP::getListado(),
            'key' => 'mdp',

        ]);

        return $this->render( '_form', [

            'mensaje'           => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),

            'model'             => $model,
            'action'            => $action,

            'permiteModificar'  => MDP::permiteModificar(),
            'dataProvider'      => $dataProvider,

            'arrayCuotas'       => $arrayCuotas,
        ]);

    }

    public function datos( $model = [], $action = 1, $arrayCuotas = [] ){

        $cuota = '';
        $actionCuota = 1;
        $recargo = 0;

        //Cuando se ejecuta un Pjax
        if( Yii::$app->request->isPjax ){

            //Cuando se agrega, modifica o elimina una cuota.
            if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_MDP_PJAXCUOTAS ){

                $cuota          = Yii::$app->request->get( 'cuota', 0 );
                $recargo        = Yii::$app->request->get( 'recargo', 0 );
                $action         = Yii::$app->request->get( 'action', 1 );
                $actionCuota    = Yii::$app->request->get( 'actionCuota', 1 );

                $arrayCuotas = Yii::$app->session->get( self::CONST_ARRAY_CUOTAS, [] );
                Yii::$app->session->set( self::CONST_ARRAY_CUOTAS, $arrayCuotas );

                //Insertar, modificar o eliminar una cuota
                if( $model->ABMCuotas( $cuota, $recargo, $actionCuota, $arrayCuotas ) ){

                    Yii::$app->session->set( self::CONST_ARRAY_CUOTAS, $arrayCuotas );

                }

            }

            //Cuando se muestra la ventana modal.
            if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_MDP_PJAXMODALCUOTAS ){

                $cuota          = Yii::$app->request->get( 'cuota', '' );
                $actionCuota    = Yii::$app->request->get( 'actionCuota', '' );
                $recargo        = Yii::$app->request->get( 'recargo', 0 );
            }
        }

        $dataProviderCuotas = new ArrayDataProvider([
            'models' => $arrayCuotas,
            'key' => 'cuotas',
        ]);

        return Yii::$app->controller->renderPartial( 'datos', [

            'model'         => $model,
            'action'        => $action,
            'cuota'         => $cuota,
            'actionCuota'   => $actionCuota,
            'recargo'       => $recargo,
            'listadoTMDP'   => MDP::getListadoTiposMDP(),
            'dataProviderCuotas'    => $dataProviderCuotas,
        ]);
    }

    protected static function findModel( $id = 1 )
    {

        $model = MDP::findOne( intval( $id ) );

        if( $model == null )
            $model = new MDP();

        return $model;

    }

    /**
     * Función que se encarga de mostrar los datos de una cuota.
     * @param integer $cuota Número de cuota.
     * @param double $recargo Valor del recargo.
     * @param string $pjaxAActualizar Identificador del pjax que se debe actualizar
     */
    public function cuota( $cuota, $recargo, $actionCuota, $idVentanaModal ){

        return Yii::$app->controller->renderPartial( 'cuota', [
            'cuota'             => $cuota,
            'recargo'           => $recargo,
            'actionCuota'       => $actionCuota,
            'idVentanaModal'    => $idVentanaModal,
        ]);

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
        }

        return $title;
    }

}

?>
