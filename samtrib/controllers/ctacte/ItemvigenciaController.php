<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\ItemVigencia;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Exception;
use yii\helpers\BaseUrl;
use app\utils\db\utb;

/**
 * ItemvigenciaController implements the CRUD actions for ItemVigencia model.
 */
class ItemvigenciaController extends Controller
{

	private $extras = [];
	private $grabar = true;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }


    public static function getDefaultExtras()
    {
    	return [
    			'montoCompNuevo' => '',
    			'paramCompNuevo1' => '',
    			'paramCompNuevo2' => '',
    			'paramCompNuevo3' => '',
    			'paramCompNuevo4' => ''
    			];
    }


	public function beforeAction($action)
	{
		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		$this->extras = ItemvigenciaController::getDefaultExtras();

		return true;
	}

	/**
	 * crea o modifica un valor asociado a la vigencia
	 */
	public function actionAsoc()
	{
		$model = new ItemVigencia();

		$model->scenario = 'select';
		$this->extras['consultaAsoc'] = 0;

		if(Yii::$app->request->isGet && $model->load(Yii::$app->request->get()))
		{
			$this->extras['consultaAsoc'] = Yii::$app->request->get('consulta', 0);
			return $this->render('//ctacte/itemvigencia/_asoc', ['model' => $model, 'extras' => $this->extras]);
		}

		if(Yii::$app->request->isPost)
		{

			$model->scenario = 'asoc';

			$model->load(Yii::$app->request->post() );
			$this->extras['consultaAsoc'] = Yii::$app->request->post('consulta', 0);

			$model = $model->buscarUno('asoc');

			if($model->hasErrors())
			{
				$this->extras['mensaje'] = 'Uno o mas valores ingresados son incorrectos';
				return $this->render('//ctacte/itemvigencia/_asoc', ['model' => $model, 'extras' => $this->extras]);
			}


			$res = $model->grabarValorAsoc();

			$this->extras['montoCompNuevo'] = $model->masoc;
			$this->extras['paramCompNuevo1'] = $model->p1asoc;
			$this->extras['paramCompNuevo2'] = $model->p2asoc;
			$this->extras['paramCompNuevo3'] = $model->p3asoc;
			$this->extras['paramCompNuevo4'] = $model->p4asoc;


			if($res != '')
				$this->extras['mensaje'] = $res;

			return $this->render('//ctacte/itemvigencia/_asoc', ['model' => $model, 'extras' => $this->extras]);
		}

		return $this->render('//ctacte/itemvigencia/_asoc', ['model' => $model, 'extras' => $this->extras]);
	}

	/**
	 * Elimina un valor asociado a la vigencia
	 */
	public function actionAsocdelete()
	{
		$model = new ItemVigencia();
		$this->extras['consultaAsoc'] = 0;

		if(Yii::$app->request->isPost)
		{
			$this->extras['consultaAsoc'] = Yii::$app->request->post('consulta', 0);
			$model->scenario = 'asoc';


			if(!$model->load(Yii::$app->request->post() ) )
				return $this->render('//ctacte/itemvigencia/_asoc', ['model' => $model, 'extras' => $this->extras]);


			$model = $model->buscarUno();


			if($model->eliminarValorAsoc() >= 0)
				$this->extras['mensaje'] = 'Datos grabados correctamente';
			else $this->extras['mensaje'] = 'No se ha podido eliminar el registro';

			return $this->render('//ctacte/itemvigencia/_asoc', ['model' => $model, 'extras' => $this->extras]);

		}



		return $this->render('//ctacte/itemvigencia/_asoc', ['model' => $model, 'extras' => $this->extras]);
	}

    /**
     * Creates a new ItemVigencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($selectorModal= '')
    {
        $model = new ItemVigencia();


		if(Yii::$app->request->isPost)
		{
			$model->scenario = 'insert';

			if(!$model->load(Yii::$app->request->post() ) || $model->hasErrors() || !$model->grabar() )
				return $this->render('//ctacte/itemvigencia/create', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);


			//el registro se ha creado y se redirecciona a update de item
			return $this->redirect(['//ctacte/item/update', 'id' => $model->item_id, 'a' => 'create']);
		}

		return $this->render('//ctacte/itemvigencia/create', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);
    }

    /**
     * Updates an existing ItemVigencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $item_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @return mixed
     */
    public function actionUpdate($selectorModal= '')
    {

    	$model = new ItemVigencia();

    	//GET
    	if(Yii::$app->request->isGet)
    	{
    		$model->scenario = 'select';

    		if(!$model->load(Yii::$app->request->get() ) )
    			return $this->render('//ctacte/itemvigencia/update', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);

    		$model = $model->buscarUno();

    		return $this->render('//ctacte/itemvigencia/update', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);
    	}

    	//POST
       	if(Yii::$app->request->isPost)
    	{

    		$model->scenario = 'select';

    		if(!$model->load(Yii::$app->request->post()))
    			return $this->render('//ctacte/itemvigencia/update', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);


//    		$arreglo = Yii::$app->request->post('ItemVigencia', []);
//    		$model->item_id = array_key_exists('item_id', $arreglo) ? $arreglo['item_id'] : 0;
//    		$model->adesde = array_key_exists('adesde', $arreglo) ? $arreglo['adesde'] : 0;
//    		$model->cdesde = array_key_exists('cdesde', $arreglo) ? $arreglo['cdesde'] : 0;


    		$model = $model->buscarUno();
    		//return var_dump($model);

    		$model->scenario = 'update';

    		if(!$model->load( Yii::$app->request->post() ) )
				return $this->render('//ctacte/itemvigencia/update', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal] );


    		if($model->hasErrors())
    			return $this->render('//ctacte/itemvigencia/update', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);

    		if(!$model->grabar())
    			return $this->render('//ctacte/itemvigencia/update', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal] );

    		//se ha modificado el registro y se redirecciona al index
    		return $this->redirect(['//ctacte/item/update', 'id' => $model->item_id, 'a' => 'update']);
    	}

    	return $this->render('//ctacte/itemvigencia/update', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);
    }

    /**
     * Deletes an existing ItemVigencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $item_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @return mixed
     */
    public function actionDelete($selectorModal= '')
    {
    	$model = new ItemVigencia();

    	//GET
    	if(Yii::$app->request->isGet)
    	{

    		$model->scenario = 'select';

    		if(!$model->load(Yii::$app->request->get()))
    			return $this->render('//ctacte/itemvigencia/delete', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);

    		$model = $model->buscarUno();

    		return $this->render('//ctacte/itemvigencia/delete', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);
    	}

    	if(Yii::$app->request->isPost)
    	{

    		$model->scenario = 'select';

    		if( !$model->load( Yii::$app->request->post() ) )
    			return $this->render('//ctacte/itemvigencia/delete', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);


    		if($model->borrar() > 0)
    			return $this->redirect(['//ctacte/item/update', 'id' => $model->item_id, 'a' => 'delete']);

    		return $this->render('//ctacte/itemvigencia/delete', ['model' => $model, 'extras' => $this->extras, 'selectorModal' => $selectorModal]);
    	}

    	return $this->render('delete', ['model' => $model, 'selectorModal' => $selectorModal]);
    }

    /**
     * Finds the ItemVigencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $item_id
     * @param integer $perdesde
     * @param integer $perhasta
     * @return ItemVigencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($item_id, $perdesde)
    {
        if (($model = ItemVigencia::findOne(['item_id' => $item_id, 'perdesde' => $perdesde]) ) !== null ) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
