<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\web\Response;
use yii\db\Query;
use app\utils\db\utb;

use yii\data\ActiveDataProvider;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{
	private $extras = [];


	public function beforeAction($action){

		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!in_array($operacion, ['ctacte-item-sugerenciaitem', 'ctacte-item-codigoitem'])){
			if (!utb::getExisteAccion($operacion)) {
				echo $this->render('//site/nopermitido');
				return false;
			}
		}
		
		if(!parent::beforeAction($action))
			return false;


		switch( $action->getUniqueId() )
		{
			case 'ctacte/item/create':
			case 'ctacte/item/update':
			case 'ctacte/item/delete':

				$nombreCuentaFiltro = '';
				$idCuentaBuscar = null;
				$model = new Item();
				$page = 0;

				if(Yii::$app->request->isGet)
				{
					$nombreCuentaFiltro = Yii::$app->request->get('nombreCuenta', '');
					$idCuentaBuscar = Yii::$app->request->get('cuenta', null);
					$page = Yii::$app->request->get('page', 0);
				}
				else if(Yii::$app->request->isPost)
				{
					$nombreCuentaFiltro = Yii::$app->request->post('nombreCuenta', '');
					$idCuentaBuscar = Yii::$app->request->post('cuenta', null);
				}



				$this->extras['cuentaNombre'] = '';

				if($idCuentaBuscar != null)
					$this->extras['cuentaNombre'] = $model->getNombreCuenta($idCuentaBuscar);


				$this->extras['dpCuentas'] = $model->getDPCuentas($nombreCuentaFiltro, $page);
				$this->extras['nombreCuentaFiltro'] = $nombreCuentaFiltro;


				break;
		}

		//nombre de cuenta
		$accion = Yii::$app->request->post('nombre');

		if($accion != null)
			return true;


		return true;
	}


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

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {

    	$sqlCount = new Query();

    	$sqlCount->select('count(item_id)');
    	$sqlCount->from('v_item');

    	$sql = new Query();

    	$sql->select(['item_id', 'nombre', 'trib_nom_redu']);
    	$sql->from('v_item');


    	$trib_id = null;
    	$nombre = null;
		$cuenta = null;
    	$cond = "";

    	//pagina a mostrar. La primera vez que se ingresa no se pasa el parametro
    	$pagina = isset($_GET['page']) ? $_GET['page'] : 0;

    	if(Yii::$app->request->isGet)
    	{
    		$trib_id = Yii::$app->request->get('trib_id');
			$nombre = Yii::$app->request->get('nombre','');
			$cuenta = Yii::$app->request->get('cuenta','');
    	}
    	else if(Yii::$app->request->isPost)
    	{
    		$trib_id = Yii::$app->request->post('trib_id');
			$nombre = Yii::$app->request->post('nombre','');
			$cuenta = Yii::$app->request->post('cuenta','');
    	}


    	$condicionNombre= $nombre != null ? $nombre : null;
		$condicionCuenta= $cuenta != null ? $cuenta : null;
					
		$sql->andFilterWhere(['trib_id' => $trib_id == 0 ? null : $trib_id]);
		$sql->andFilterWhere(['like', 'upper(nombre)', strtoupper($condicionNombre)]);
		$sql->andFilterWhere(['like', 'upper(cta_nom)', strtoupper($condicionCuenta)]);

		$sql->orderBy([
			'item_id' => SORT_ASC,
		]);

    	$sqlCount->where( $sql->where );


    	$count = $sqlCount->scalar();


        $dataProvider = new ActiveDataProvider([
        	'query' => $sql,
        	'totalCount' => $count,
        	'key' => 'item_id',
        	'pagination' => [
        		'pageSize' => 40,
        		'params' => ['page' => $pagina, 'per-page' => 13, 'trib_id' => $trib_id]
        	],
        	'sort' => [
        		'attributes' => [
        			'item_id',
        			'nombre' => ['default' => SORT_ASC],
        			'trib_nom_redu'
        		]
        	]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();

		if(Yii::$app->request->isGet)
			return $this->render('create', ['model' => $model, 'extras' => $this->extras]);


		$model->scenario = 'insert';

		if( $model->load( Yii::$app->request->post() ) && $model->validate() )
			return $model->grabar() ? $this->redirect(['index', 'a' => 'create']) : $this->render('create', ['model' => $model, 'extras' => $this->extras]);


		return $this->render('create', ['model' => $model, 'extras' => $this->extras]);
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = new Item();

		$model->scenario = 'select';

		if(Yii::$app->request->isGet)
		{
			$model->item_id = Yii::$app->request->get('id');

			$model = $model->buscarUno();

			return $this->render('update', ['model' => $model, 'extras' => $this->extras]);
		}


		if(Yii::$app->request->isPost)
		{

			if(!$model->load(Yii::$app->request->post()))
				return $this->render('update', ['model' => $model, 'extras' => $this->extras]);


			$model = $model->buscarUno();

			if($model->hasErrors())
				return $this->render('update', ['model' => $model, 'extras' => $this->extras]);


			$model->scenario = 'update';

			if(!$model->load(Yii::$app->request->post()))
				return $this->render('update', ['model' => $model, 'extras' => $this->extras]);


			if(!$model->validate())
				return $this->render('update', ['model' => $model, 'extras' => $this->extras]);

			if($model->grabar())
				return $this->redirect(['index', 'a' => 'update']);

			return $this->render('update', ['model' => $model, 'extras' => $this->extras]);
		}


		return $this->render('update', ['model' => $model, 'extras' => $this->extras]);
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
    	$model = new Item();

    	if(Yii::$app->request->isGet)
    	{
    		$model->item_id = Yii::$app->request->get('id');

    		$model = $model->buscarUno();


    		return $this->render('delete', ['model' => $model, 'extras' => $this->extras]);
    	}


        if(Yii::$app->request->isPost)
        {

        	$model->item_id = Yii::$app->request->post('Item')['item_id'];

        	$model = $model->buscarUno();

        	if($model->hasErrors())
        		return $this->render('delete', ['model' => $model, 'extras' => $this->extras]);


        	if($model->borrar())
        		return $this->redirect(['index', 'a' => 'delete']);

        	return $this->render('delete', ['mensaje' => $error, 'model' => $model, 'extras' => $this->extras] );
        }


		return $this->redirect(['index']);
    }
	
	/**
    * Busca los items que coincidan con $term
    *
    */
    public function actionSugerenciaitem($term = ''){


    	$ret= [];

    	if($term == '') return json_encode($ret);

    	$ret= utb::getAux('cuenta', 'nombre', 'nombre', 0, "upper(nombre) Like upper('%$term%')");

    	if($ret === false) $ret= [];
    	return json_encode($ret);
    }

    /**
    * Busca el codigo de item que coincide con $nombre
    */
    public function actionCodigoitem($nombre= ""){

    	$ret= utb::getCampo('cuenta', "nombre = '$nombre'", 'cta_id');
    	return $ret;
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
