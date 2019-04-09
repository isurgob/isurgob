<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\TribVenc;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\Fecha;
use app\utils\db\utb;

/**
 * TribvencController implements the CRUD actions for TribVenc model.
 */
class TribvencController extends Controller
{

    const CONST_MENSAJE         = 'const_mensaje';
    const CONST_MENSAJE_ERROR   = 'const_mensaje_error';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * Función que devuelve el dataprovider
     * "param"
     */
    public function getDataProvider()
    {
    }

     public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
		
		$permitirSiempre = ['ctacte-tribvenc-verificaraniovencanual'];

	    if (!utb::getExisteAccion($operacion) and !(in_array($operacion, $permitirSiempre))) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

    	return true;
    }


    /**
     * Lists all TribVenc models.
     * @return mixed
     */
    public function actionIndex()
    {

    	$model = new TribVenc();

        return $this->render( 'index', [

            'mensaje' => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
            
        ]);
    }

    /**
     * Creates a new TribVenc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new TribVenc();

       	if ( $model->load( Yii::$app->request->post() ) )
       	{

       		if ( $model->grabar() )
			{
                Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

    	        return $this->redirect([ 'index' ]);

        	} else
        	{
        		$model->fchvenc1 = Fecha::usuarioToBD( $model->fchvenc1 );
		    	$model->fchvenc2 = Fecha::usuarioToBD( $model->fchvenc2 );
		    	$model->fchvencanual = Fecha::usuarioToBD( $model->fchvencanual );
			}
      	 }

       	 return $this->render('create', [
 	       'model' => $model,

		 ]);

    }

    /**
     * Updates an existing TribVenc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $trib_id
     * @param integer $anio
     * @param integer $cuota
     * @return mixed
     */
    public function actionUpdate($trib_id, $anio, $cuota)
    {
        $model = $this->findModel($trib_id, $anio, $cuota);

		if ( $model->load( Yii::$app->request->post() ) ){

        	if ( $model->grabar() ) {

                Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

    	        return $this->redirect([ 'index' ]);

        	} else {

        		$model->fchvenc1     = Fecha::usuarioToBD( $model->fchvenc1 );
		    	$model->fchvenc2     = Fecha::usuarioToBD( $model->fchvenc2 );
		    	$model->fchvencanual = Fecha::usuarioToBD( $model->fchvencanual );

        	}
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing TribVenc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $trib_id
     * @param integer $anio
     * @param integer $cuota
     * @return mixed
     */
    public function actionDelete( $trib_id, $anio, $cuota, $accion )
    {
        $model = $this->findModel( $trib_id, $anio, $cuota );

        if( $accion == 1 ){

           	if ( $model->borrar() ){

                Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

    	        return $this->redirect([ 'index' ]);

			}
		}

		return $this->render('delete', [
			'model' => $model,
			]);

    }
	
	public function actionVerificaraniovencanual(){
	
		$fchvencanual = Yii::$app->request->post( 'fchvencanual', '' );
		$aniovenc = Fecha::getAnioFechaDB( $fchvencanual );
		
		return json_encode([ 'aniovenc' => $aniovenc ]);
	}

    private function getMensaje( $id = 0 ){

        switch( $id ){

            case 1:

                $title = 'Los datos se grabaron correctamente.';
                break;

            default:

                $title = '';
                break;

        }

        return $title;
    }

    /**
     * Finds the TribVenc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $trib_id
     * @param integer $anio
     * @param integer $cuota
     * @return TribVenc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($trib_id, $anio, $cuota)
    {
        if (($model = TribVenc::findOne(['trib_id' => $trib_id, 'anio' => $anio, 'cuota' => $cuota])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
