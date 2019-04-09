<?php

namespace app\controllers\config;

use Yii;
use app\models\config\CuentaPartida;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use app\utils\db\utb;


/**
 * CuentaPartidaController implements the CRUD actions for CuentaPartida model.
 */
class CuentaPartidaController extends Controller
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

    /**
     * Lists all CuentaPartida models.
     * También se utiliza para eliminar una partida => $id != 0 y $elim = 1
     * @return mixed
     */
    public function actionIndex( $men = 0, $id = 0, $elim = 0 )
    {
    	$model = new CuentaPartida();

    	if ( $id != 0 && $elim == 1 )
		{
	    	if ( $model->eliminarPartida( $id ) )
	    		return $this->redirect(['index', 'men' => 1]);
//	    	else
//	    		return $this->redirect(['index', 'men' => 2]);
		}

        return $this->render('_form', [
            'model' => $model,
            'm' => $this->getM($men),
            'alert' => $this->getMensaje($men),
        ]);
    }

    /**
     * Función que muestra los datos de una partida seleccionada
     * @param integer $id Partida presupuestaria.
     */
    public function actionViewpartida( $id = 0 )
    {
		$model = CuentaPartida::findOne( $id );
		$mensaje = '';

		//Si se pudieron cargar los datos en el modelo
    	if( $model->load( Yii::$app->request->post() ) )
    	{
    		if ( $model->grabarCuenta() )
    		{
    			$model = CuentaPartida::findOne( $model->part_id );
    			$mensaje = 'Los datos se grabaron correctamente.';
    		}
    	}

    	//var_dump( $_POST );

    	//Generar el dataProvider para la grilla
		$dataProvider = new ArrayDataProvider([
							'allModels' => $model->getCuentas($model->part_id),
							'key' => 'part_id']);

    	return $this->render('viewPartida',[
    		'model' => $model,
    		'dataProvider' => $dataProvider,
    		'mensaje' => $mensaje,
    	]);
    }

    /**
     * Función que se utiliza para insertar una nueva "Partida Presupuestaria".
     * @param integer $id Identificador de la partida presupuestaria
     *
     */
    public function actionInsert($id)
    {
    	$model = new CuentaPartida();

    	//Si se pudieron cargar los datos en el modelo
    	if($model->load(Yii::$app->request->post()))
    	{
    		if ( $model->grabarPartida() )
    		{
    			return $this->redirect(['index','men' => 1]);
    		} else
    		{
    			//Seteo como ID el identificador del padre
    			$id = $model->padre;
    		}
    	}

		$modelPadre = CuentaPartida::findOne($id);

    	if ($modelPadre != null)
    	{
    		$model->nivel = $modelPadre->nivel + 1;
    		$model->grupo = $modelPadre->grupo;
    		$model->subgrupo = $modelPadre->subgrupo;
    		$model->rubro = $modelPadre->rubro;
    		$model->cuenta = $modelPadre->cuenta;
    		$model->subcuenta = $modelPadre->subcuenta;
    		$model->subconc = $modelPadre->subconc;
    		$model->conc = $modelPadre->conc;
    		$model->padre = $modelPadre->part_id;

    	}

    	return $this->render('editaPartida',[
    		'model' => $model,
    		'id' => $id,
    		'consulta' => 0,
    	]);

    }

    /**
     * Updates an existing CuentaPartida model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

    	$model = new CuentaPartida();
    	//Si se pudieron cargar los datos en el modelo
    	if($model->load(Yii::$app->request->post()))
    	{
    		if ( $model->grabarPartida() )
    		{
    			return $this->redirect(['index','men' => 1]);

    		} else
    		{
    			//Seteo el ID
    			$id = $model->part_id;
    		}
    	}

		$model = CuentaPartida::findOne($id);

    	return $this->render('editaPartida',[
    		'model' => $model,
    		'id' => $id,
    		'consulta' => 3,
    	]);
    }

    //////////////////////////////CUENTAS///////////////////////////////////////////////////

    /**
     * Función que muestra las opciones para búsqueda de "Cuentas"
     */
    public function actionIndexcuenta()
    {
		$m = 1;

		$alert = '';

        $model = new CuentaPartida();

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
