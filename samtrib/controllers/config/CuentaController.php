<?php

namespace app\controllers\config;

use Yii;
use app\models\config\Cuenta;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use app\utils\db\utb;


/**
 * CuentaPartidaController implements the CRUD actions for CuentaPartida model.
 */
class CuentaController extends Controller
{


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

     public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

    	return true;
    }

    //////////////////////////////CUENTAS///////////////////////////////////////////////////

    /**
     * Función que muestra las opciones para búsqueda de "Cuentas"
     */
    public function actionIndexcuenta()
    {
		$m = 1;

		$alert = '';

        $model = new Cuenta(); 
		
        //Si se pudieron cargar los datos en el modelo
    	if( $model->load( Yii::$app->request->post() ) )
    	{ 
    		$model->cta_id_atras = intval( $model->cta_id_atras );

    		if ( $model->grabarCuenta() )
    		{
    			$alert = 'Los datos se grabaron correctamente.';
    		}
    	}

        return $this->render('_formCuenta', [
            'model' => $model,
            'm' => $m,
    		'alert' => $alert,
        ]);
    }

    /**
     * Función que se utiliza para realizar una baja lógica de una cuenta.
     * @param integer $id Identificador de la cuenta
     */
    public function actionEliminacuenta($id)
    {
    	$m = 1;
    	$alert = '';

		$model = new CuentaPartida();

		$arreglo = $model->grabarCuenta();

		if ($arreglo['return'] == 1)
		{
			$model = CuentaPartida::findOne($model->part_id);
		} else
		{
			$m = ($arreglo['return'] == 1 ? 1 : 2);
			$alert = $arreglo['mensaje'];
		}

    	//Genero el dataProvider para la grilla
		$dataProvider = new ArrayDataProvider([
							'allModels' => $model->getCuentas($model->part_id),
							'key' => 'part_id']);

    	return $this->render('viewPartida',[
    		'model' => $model,
    		'dataProvider' => $dataProvider,
    		'm' => $m,
    		'alert' => $alert,
    	]);
    }

    /**
     * Función que se utiliza para obtener un mensaje
     * según un código.
     * @param integer $id Código
     */
    public function getMensaje( $id )
    {
    	switch($id)
    	{
    		case 0:
    			$mensaje = '';
    			break;

    		case 1:
    			$mensaje = 'Los datos se grabaron correctamente.';
    			break;

    		case 2:
    			$mensaje = 'La partida no se pudo eliminar.';
    			break;
    	}

    	return $mensaje;
    }

    public function getM( $id )
    {
    	switch($id)
    	{
    		case 0:
    		case 1:
    			$m = 1;
    			break;

    		case 2:
    			$m = 2;
    			break;
    	}

    	return $m;
    }

    /**
     * Finds the CuentaPartida model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CuentaPartida the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CuentaPartida::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
