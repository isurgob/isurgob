<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Comp;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use yii\data\ArrayDataProvider;
use app\utils\db\utb;

/**
 * CompController implements the CRUD actions for comp model.
 */
class CompController extends Controller
{

    public function beforeAction($action)
	{
		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		//Obtengo el action
		$id = $action->getUniqueId();

		$session = new Session;
    	$session->open();

		if ( $id != 'ctacte/comp/listado' )
		{
			$session['cond'] = '';
			$session['descr'] = '';
		}

		$session->close();

		return true;
	}

    /**
     * Displays a single comp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = '',$action = 1,$consulta = 1,$alert = '',$m = 0)
    {
    	/**
    	 * $consulta == 0 => Formulario para inserción de datos
    	 * $consulta == 1 => Formulario para inserción de datos y procesar
    	 * $consulta == 2 => Formulario para inserción de datos y calcular
    	 * $consulta == 3 => Formulario para inserción de datos y grabar
    	 */

    	if (!isset($model)) $model = new Comp();

    	//Si m=1 y alert == '' => Crear mensaje
    	if ($m == 1 && $alert == '')
    	{
    		$alert = 'Los datos se grabaron correctamente.';
    	}

    	if ($m == 2 && $alert != '')
    	{
    		$alert = 'Hubo un error al grabar los datos.';
    	}

    	//Creo los DataProvider para las grillas vacios
    	$dataProviderOrigen = new ArrayDataProvider(['allModels' => []]);
		$dataProviderDestino = new ArrayDataProvider(['allModels' => []]);

    	if ($id != '')
    	{
    		$model = $this->findModel($id); //Obtengo los datos del modelo
    		$dataProviderOrigen = $model->cargarDatosOrigen( $id ); 	//Obtengo los datos para la grilla de origen
    		$dataProviderDestino = $model->cargarDatosDestino( $id );	//Obtengo los datos para la grilla de destino

    		if ($model->comp_id == '' || $model->comp_id == null)
    		{
    			$id = '';
    			$alert = 'No se encontró ninguna compensación.';
				$m = 2;
				$consulta = 1;
    		}
    	}

        return $this->render('view', [
        	'consulta'=>$consulta,
        	'action'=>$action,
            'model' => $model,
            'dataProviderOrigen'=>$dataProviderOrigen,
            'dataProviderDestino'=>$dataProviderDestino,
            'id' => $id,
            'm'=>$m,
            'alert'=>$alert,

        ]);
    }

    /**
     * Creates a new comp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = '',$consulta = 0)
    {
        $model = new comp();

    	//Creo los DataProvider para las grillas vacios
    	$dataProviderOrigen = new ArrayDataProvider(['allModels' => []]);
		$dataProviderDestino = new ArrayDataProvider(['allModels' => []]);

		if ( $id != '' )
    	{
    		$model = $this->findModel( $id ); //Obtengo los datos del modelo
    		$dataProviderOrigen = $model->cargarDatosOrigen( $id ); 	//Obtengo los datos para la grilla de origen
    		$dataProviderDestino = $model->cargarDatosDestino( $id );	//Obtengo los datos para la grilla de destino

    	}

        return $this->render('_form', [
                'model' => $model,
                'consulta' => $consulta,
                'dataProviderOrigen' => $dataProviderOrigen,
                'dataProviderDestino' => $dataProviderDestino,
            ]);
    }

    /**
     * Updates an existing comp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->comp_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing comp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$model = new Comp();

    	if ($id != '')
    	{
    		$model = $this->findModel($id);
    	}

		$result = $model->borrarComp($id);

		if ($result == '')
		{
			$m = 1;
			$alert = '';
		} else
		{
			$m = 2;
			$alert = $result;
		}

        return $this->redirect(['view','id'=>$id,'m'=>$m,'alert'=>$alert]);
    }

    /**
     * Función que se ejecuta cuando se busca una Compensación.
     */
    public function actionBuscar()
    {
    	$comp_id = Yii::$app->request->post('txNum','');

    	if ($comp_id != '')
    	{
    		return $this->redirect(['view','id' => $comp_id, 'consulta' => 1]);
    	}
    }

    public function actionImprimir($id)
    {
    	$sub1 = null;
    	$sub2 = null;
    	$array = (new Comp)->Imprimir($id,$sub1,$sub2);

    	$pdf = Yii::$app->pdf;
		$pdf->content = $this->renderPartial('//reportes/comp',['datos' => $array,'sub1'=>$sub1,'sub2'=>$sub2]);

        return $pdf->render();
    }

    /**
     * Finds the comp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return comp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = comp::findOne($id)) !== null) {
        	$model->cargarDatos($id);
            return $model;
        } else {
            $model = new Comp();
            return $model;
        }
    }
}
