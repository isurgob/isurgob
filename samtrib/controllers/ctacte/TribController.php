<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Trib;
use app\models\ctacte\Item;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;

/**
 * TribController implements the CRUD actions for Trib model.
 */
class TribController extends Controller
{

	/** integer - Contiene el codigo del tipo de tributo que se envia por GET. 0 si no se ha enviado nada */
	private $codigoTipoTributo;

	/** boolean - Determina si la request es de un formulario y se debe proceder a grabar. */
	private $grabar;

	/**
	 * Se inicializan las variables privadas
	 */
	public function beforeAction($action){

		$actionsPermitidos= ['ctacte/trib/usasubcta'];
		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion) && !in_array($action->getUniqueId(), $actionsPermitidos)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		$id= $action->getUniqueId();
		$this->codigoTipoTributo= 0;
		$this->grabar= false;

		switch($id){

			case 'ctacte/trib/create':
			case 'ctacte/trib/update':
			case 'ctacte/trib/delete':
			case 'ctacte/trib/activar':


			$this->codigoTipoTributo= intval(Yii::$app->request->get('codigoTipoTributo', 0));
			$this->grabar= filter_var(Yii::$app->request->post('grabar', false), FILTER_VALIDATE_BOOLEAN);
		}


		return true;
	}

	/**
	 * Se conforman los parametros a enviar a las vistas
	 *
	 * @param Trib $model - Modelo inicializado
	 * @param integer $consulta = 1 - Consulta actual
	 */
	private function extras($model, $consulta = 1){

		if($this->codigoTipoTributo > 0) $model->setTipoTributo($this->codigoTipoTributo);

		$model->tipo_descripcion= Trib::descripcionTipoTributo($model->tipo);

		$test= function($atributo, $opcional = true) use($model){
			return $model->esRequerido($atributo, $opcional);

		};

		$ret= ['model' => $model, 'consulta' => $consulta, 'requerido' => $test];

		return $ret;
	}

	/**
	 * Si la consulta viene por POST y se dio la orden de grabar, se procede a realizar las acciones correspondientes dependiendo de la consulta actual
	 *
	 * @param trib $model - modelo inicializado. Inicializado desde la base de datos en caso de que no sea un nuevo modelo
	 * @param integer $consulta - Consulta actual
	 *
	 * @return boolean - true si las operaciones concluyeron satisfactoriamente, false de lo contrario
	 *
	 */
	private function resolverPost($model, $consulta){

		if(Yii::$app->request->isPost && $this->grabar){

			$arreglo= Yii::$app->request->post();
			
			switch($consulta){

			case 0:
				$model->setScenario('insert');

				if($model->load($arreglo) && $model->setTipoTributo(intval($model->tipo)))
					return $model->grabar();

				break;

			case 2:
				$model->setScenario('delete');

				return $model->load($arreglo) && $model->borrar();

			case 3:
				$model->setScenario('update');

				if($model->load($arreglo) && $model->setTipoTributo(intval($model->tipo)))
					return $model->grabar();

				break;

			case 4:
				$model->setScenario('activar');

				return $model->load($arreglo) && $model->activar();

				break;	
			}
		}
		
		return false;
	}

	/**
	 * Muestra la pagina principal de los tributos
	 *
	 * @param $trib_id = 0 - Codigo de tributo a cargar y enviar como parametro a la vista
	 *
	 * @return string - Vista renderizada
	 */
    public function actionIndex($trib_id = 0, $m= 0)
    {

    	$trib_id = intval($trib_id);
    	$mensaje= $this->mensajes($m);

		$model = Trib::findOne(['trib_id' => $trib_id]);
		if($model === null) $model = new Trib();

		/**
		 * se aplican los filtros y se obtiene el arreglo con los datos
		 */

		$nombre= trim(Yii::$app->request->get('filtroNombre', ''));
		$tipoTributo= intval(Yii::$app->request->get('filtroTipoTributo', -1));
		$tipoObjeto= intval(Yii::$app->request->get('filtroTipoObjeto', 0));

		$dp = new ArrayDataProvider([
			'allModels' => $model->tributos($nombre, $tipoTributo, $tipoObjeto),
			'pagination' => false,
			'key' => 'trib_id',
			'sort' => [
				'attributes' => [
					'trib_id',
					'nombre_redu',
				],
				'defaultOrder'	=> [
					'nombre_redu' => SORT_ASC,
				],
			],
		]);
		/**
		 * fin de tributos filtrados
		 */

		//se obtienen los items del tributo seleccionado
		$dpItems= new ArrayDataProvider(['allModels' => $model->items(), 'pagination' => false]);

		//se obtienen los vencimientos del tributo seleccionado
		$dpVencimientos= new ArrayDataProvider(['allModels' => $model->vencimientos(), 'pagination' => false]);

		$extras = ['dpTributos' => $dp, 'model' => $model, 'dpItems' => $dpItems, 'dpVencimientos' => $dpVencimientos, 'mensaje' => $mensaje];
		return $this->render('index', ['extras' => $extras]);
    }


	/**
	 * muestra un tributo en modo consulta
	 *
	 * @param integer $id = 0 - Codigo del tributo a mostrar
	 * @param integer $m = 0 - Codigo del mensaje a mostrar
	 *
	 * @return string - Formulario renderizado en modo consulta
	 */
	public function actionView($id = 0, $m = 0){


		$model = Trib::findOne(['trib_id' => $id]);
		$m = intval($m);
		$mensaje = $this->mensajes($m);

		if($model == null) $model= new Trib();

		$extras= $this->extras($model);
		$extras['mensaje']= $mensaje;


		return $this->render('_form', $extras);
	}


    /**
     * Si la consulta es por get, muestra el formulario listo para crear un nuevo tributo.
     * Si la consulta viene por POST y se ha dado la orden de grabar, se procede a crear el nuevo tributo
     *
     * @return mixed - Si la consulta es por GET, se retorna el formulario. Si es por POST y se crea correctamente,
     * se redirige a 'view' para mostrar el nuevo tributo en modo consulta y con el codigo de mensaje $m = 1.
     * Si es por POST y NO se crea correctamente, se vuelve  a mostrar el formulario con los errores
     */
    public function actionCreate()
    {
		$model = new Trib();

		if($this->resolverPost($model, 0)) return $this->redirect(['view', 'id' => $model->trib_id, 'm' => 1]);

		$extras = $this->extras($model, 0);
		return $this->render('_form', $extras);
    }

    /**
     * Si la consulta es por get, muestra el formulario listo para modificar el tributo cargado.
     * Si la consulta viene por POST y se ha dado la orden de grabar, se procede a modificar el tributo.
     *
     * @param integer $id - Codigo del tributo a modificar.
     *
     * @return mixed - Si la consulta es por GET, se retorna el formulario. Si es por POST y se modifica correctamente,
     * se redirige a 'view' para mostrar el tributo modificado en modo consulta y con el codigo de mensaje $m = 1.
     * Si es por POST y NO se modifica correctamente, se vuelve  a mostrar el formulario con los errores
     */
    public function actionUpdate($id)
    {
		$model= Trib::findOne(['trib_id' => $id]);

        if($model !== null && $this->resolverPost($model, 3)) return $this->redirect(['index', 'm' => 1]);

		if($model === null) $model= new Trib();

		$extras= $this->extras($model, 3);
		return $this->render('_form', $extras);
    }

    /**
     * Si la consulta es por get, muestra el formulario listo para eliminar el tributo cargado.
     * Si la consulta viene por POST y se ha dado la orden de grabar, se procede a eliminar el tributo.
     *
     * @param integer $id - Codigo del tributo a eliminar.
     *
     * @return mixed - Si la consulta es por GET, se retorna el formulario. Si es por POST y se elimina correctamente,
     * se redirige a 'view' para mostrar el tributo eliminado en modo consulta y con el codigo de mensaje $m = 1.
     * Si es por POST y NO se elimina correctamente, se vuelve  a mostrar el formulario con los errores
     */
    public function actionDelete($id)
    {
    	$model= Trib::findOne(['trib_id' => $id]);

        if($model !== null && $this->resolverPost($model, 2)) return $this->redirect(['view', 'id' => $model->trib_id, 'm' => 1]);

		if($model === null) $model= new Trib();

		$extras= $this->extras($model, 2);
		return $this->render('_form', $extras);
    }

    public function actionActivar($id)
    {
		$model= Trib::findOne(['trib_id' => $id]);

        if($model !== null && $this->resolverPost($model, 4)) return $this->redirect(['index', 'm' => 1]);

		if($model === null) $model= new Trib();

		$extras= $this->extras($model, 4);
		return $this->render('_form', $extras);
    }

    /**
    * Determina si el tributo usa subcuenta o no
    *
    * @param int $id Código del tributo
    *
    * @return boolean Si usa subcuenta. En caso de que el tributo no exista o no se lo encuentre, se retorna false por defecto.
    */
    public function actionUsasubcta($id){

    	Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;

    	return Trib::usaSubCta($id);
    }

    public function actionImprimir($id)
    {
    	$sub1 = null;
    	$sub2 = null;

    	$datos = Trib::Imprimir($id,$sub1,$sub2);

    	$pdf = Yii::$app->pdf;
		$pdf->content = $this->renderPartial('//reportes/trib',['datos'=>$datos,'sub1'=>$sub1,'sub2'=>$sub2]);

        return $pdf->render();
    }

	/**
	 * Retorna el mensaje a mostrar a partir de un codigo
	 *
	 * @param integer $m - Codigo del mensaje a mostrar
	 *
	 * @return mixed - null si el codigo del mensaje no existe. Si existe, se retorna el mensaje a mostrar
	 */
	private function mensajes($m){

		switch($m){

			case 1: return 'Datos grabados correctamente';
		}

		return null;
	}
}
