<?php

namespace app\controllers\ctacte;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\Session;
use yii\data\ArrayDataProvider;

use app\models\ctacte\Retencion;
use app\models\ctacte\RetencionDetalle;

use app\utils\db\utb;

/**
 * ReclamoController implements the CRUD actions for Reclamo model.
 */
class RetencionController extends Controller{

	const MENSAJE= 'retencionMensaje';
	const DETALLES= 'retencionDetalles';
	const TOKEN= 'tokenRetencion';

	private $modelDetalle;
	private $consultaDetalle;
	private $cambioVista;

	public function beforeAction($action)
    {
//    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
//
//	    if (!utb::getExisteAccion($operacion)) {
//	        echo $this->render('//site/nopermitido');
//	        return false;
//	    }

    	$this->cambioVista= false;

    	$this->consultaDetalle= intval(Yii::$app->request->get('consultaDetalle', 0));
    	$numeroDetalleRetencion= intval(Yii::$app->request->get('numeroDetalle', 0));

    	$this->modelDetalle= $this->getDetalle($numeroDetalleRetencion);//RetencionDetall::findOne(['ret_id' => $codigoDetalleRetencion]);
//    	if($this->modelDetalle == null) $this->modelDetalle= new RetencionDetalle();

    	$idAction= $action->getUniqueId();
    	$token= -1;
    	$tokenAnterior= Yii::$app->session->get(self::TOKEN, -1);

    	$prefijo= 'ctacte/retencion/';
    	switch($idAction){

    		case $prefijo . 'index':
    			$token= 5;
    			break;

    		case $prefijo . 'view':
    			$token= 1;
    			break;

    		case $prefijo . 'create':
    			$token= 0;
    			break;

    		case $prefijo . 'update':
    			$token= 2;
    			break;

    		case $prefijo . 'delete':
    			$token= 3;
    			break;

    		default:
    		$token= $tokenAnterior;
    	}


    	$this->cambioVista= $token === -1 || $token !== $tokenAnterior;
    	Yii::$app->session->set(self::TOKEN, $token);

    	return true;
    }

    /**
    *
    */
    private function removerSession(){

    	Yii::$app->session->set(self::DETALLES, []);
    }

    /**
    *
    */
    private function cargarDetallesModelo($model){

    	$this->setDetalles(RetencionDetalle::find()->where(['retdj_id' => $model->retdj_id])->andWhere(['NOT', ['retdj_id' => 0] ])->all());
    }

    /**
    * Carga los parametros que espera la vista 'view/ctacte/retencion/_form.php'.
    *
    * @param string $id Codigo del agente de retencion
    * @param int $consulta = 0 Codigo de consulta actual:
    *	0: Create;
    *	1: View;
    *	2: Delete;
    *	3: Update;
    *
    * @return Array Arreglo con los datos a enviar a la vista 'views/ctacte/retencion/_form.php'
    */
    private function extras($id= 0, $consulta = 0){

    	$model= Retencion::findOne(['retdj_id' => $id]);
    	if($model == null) $model= new Retencion( $id );

    	if($this->cambioVista){
    		$this->cargarDetallesModelo($model);
    	}

    	$model->setDetalles($this->getDetalles());

    	$dp= new ArrayDataProvider(['allModels' => $model->detalles]);

    	$ret= [];

    	$ret['model']= $model;
    	$ret['consultaDetalle']= $this->consultaDetalle;
    	$ret['consulta']= $consulta;
    	$ret['dataProviderDetalleRetenciones']= $dp;
    	$ret['extrasDetalle']= $this->extrasDetalle($id);

    	return $ret;
    }

    /**
    * Carga los parametros que espera la vista 'view/ctacte/retencion/_form_detalle.php'.
    *
    * @param int $retdj_id = 0 Codigo de retencion a la que estan ligados los detalles a cargar.
    *
    * @return Array Arreglo con los datos que espera la vista.
    */
    private function extrasDetalle($retdj_id= 0){

    	$tiposComprobantes= RetencionDetalle::tiposComprobantes();
		//$lugaresDetalle= RetencionDetalle::lugares();

    	$this->modelDetalle->retdj_id= $retdj_id;

    	$ret= [];

    	$this->cargarDatosObjetoDetalle();
    	$ret['model']= $this->modelDetalle;

    	$ret['tiposComprobantes']= $tiposComprobantes;
    	//$ret['lugares']= $lugaresDetalle;
    	$ret['consulta']= $this->consultaDetalle;

    	return $ret;
    }

    /**
    *
    */
    private function cargarDatosObjetoDetalle(){

    	$codigoObjetoBuscar= strtoupper(Yii::$app->request->get('obj_id', $this->modelDetalle->obj_id));

    	if($codigoObjetoBuscar !== null && $codigoObjetoBuscar !== ''){
    		$this->modelDetalle->setCodigoObjeto($codigoObjetoBuscar);
    	} else{

    		$cuitBuscar= Yii::$app->request->get('cuit', $this->modelDetalle->cuit);
    		$this->modelDetalle->setCuit($cuitBuscar);
    	}

    }

	/**
	 * Coloca el detalle en session siempre y cuando el modelo no se repita
	 *
	 * @param RetencionDetalle Modelo a ser guardado
	 *
	 * return boolean Si se ha guardado o no el detalle
	 */
	private function setDetalle($model, $nuevo= true){

		$actuales= $this->getDetalles();
		$nuevos= [];
		$reemplazado= false;

		foreach($actuales as $detalle){


			if($detalle->esIgual($model, true)){

				$reemplazado= true;

				if($nuevo) return false;
				else {

					if($model->est != RetencionDetalle::ESTADO_PENDIENTE){

						$nuevo->addError('ret_id', 'Solo los detalles con estado PENDIENTE pueden ser modificados');
						return false;
					}

					$model->temporal= $detalle->temporal;
					$detalle= $model;
				}
			}

			array_push($nuevos, $detalle);
		}

		if(!$reemplazado) array_push($nuevos, $model);

		$this->setDetalles($nuevos);
		return true;
	}

	/**
	 *
	 */
	private function setDetalles($arregloModelos = []){

		Yii::$app->session->set(self::DETALLES, $arregloModelos);
	}

	/**
	 *
	 */
	private function getDetalle( $numero ){

		$actuales= $this->getDetalles();

		foreach($actuales as $detalle)
			if($detalle->numero == $numero)
				return $detalle;

		return new RetencionDetalle(0);
	}

	/**
	 *
	 */
	private function getDetalles(){

		return Yii::$app->session->get(self::DETALLES, []);
	}

	private function borrarDetalle($model){

		$actuales= $this->getDetalles();
		$nuevos= [];

		foreach($actuales as $detalle){

			if($detalle->esIgual($model)){
				if($detalle->temporal) break;
				else $detalle->borrar();
			}

			array_push($nuevos, $detalle);
		}

		$this->setDetalles($nuevos);
	}

	/**
	 *
	 */
	public function actionIndex($id= 0, $soloActivas= false, $anio= 0){

		$mensaje= Yii::$app->session->get(self::MENSAJE, '');

		$model= new Retencion($id);

		$soloActivas= filter_var($soloActivas, FILTER_VALIDATE_BOOLEAN);
		$estado= $soloActivas ? 'A' : null;
		$anio= intval($anio);

		if($anio <= 0) $anio= null;

		$models= Retencion::find()->where(['ag_rete' => $id])->andFilterWhere(['est' => $estado])->andFilterWhere(['anio' => $anio])->all();

		$dp= new ArrayDataProvider([
			'allModels' => $models,
			'key' => 'retdj_id'
		]);

		//return var_dump($models);
		$agentes= Retencion::agentes();

		return $this->render('index', ['model' => $model, 'dataProviderDDJJPresentadas' => $dp, 'agentesExistentes' => $agentes, 'mensaje' => $mensaje]);
	}

	/**
	* @param int $id Codigo de la retencion.
	*/
	public function actionView($id){

		$model= Retencion::findOne(['retdj_id' => $id]);

		if(Yii::$app->request->isPost && $model != null && $model->aprobar()){

			$this->removerSession();
			Yii::$app->session->set(self::MENSAJE, 'Declaración jurada aprobada.');

			return $this->redirect(['index', 'id' => $model->ag_rete]);
		}

		return $this->render('_form', $this->extras($id, 1));
	}


	public function actionCreate($id, $importar= false){

		$importar= filter_var($importar, FILTER_VALIDATE_BOOLEAN);
		$grabar= filter_var(Yii::$app->request->post('grabar', false), FILTER_VALIDATE_BOOLEAN);

		if($importar && !$grabar && Yii::$app->request->isPost){

			$this->removerSession();
			$extras= $this->extras(0, 0);
			if(Yii::$app->request->isPost){

				$model= new Retencion();
				$model->setScenario('importar');
				$model->archivo= UploadedFile::getInstance($model, 'archivo');

				$model->importarArchivo();

				$extras['model']= $model;
				$extras['dataProviderDetalleRetenciones']= new ArrayDataProvider(['allModels' => $model->detalles]);

				$this->setDetalles($model->detalles);
				$extras['extrasDetalle']= $this->extrasDetalle($model->retdj_id);


				return $this->render('_form', $extras);
			}
		}



		$model= new Retencion($id);
		$model->setScenario('insert');
//		$this->setDetalles();
		//return var_dump($_POST);
		if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $grabar){

			$detalles= $this->getDetalles();
			//return var_dump($_POST);
			if($model->grabar($detalles)){

				$this->removerSession();

				Yii::$app->session->setFlash(self::MENSAJE, 'Datos grabados correctamente.');
				return $this->redirect(['index', 'id' => $model->ag_rete]);
			}
		}

		//$model->setDetalles($this->getDetalles());

		$extras= $this->extras($id, 0);
		$extras['model']= $model;

		return $this->render('_form', $extras);
	}

	public function actionUpdate($id){

		$model= Retencion::findOne(['retdj_id' => $id]);

		if($model != null){

			$model->setScenario('update');
			if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->grabar($this->getDetalles())){

				$this->removerSession();

				Yii::$app->session->setFlash(self::MENSAJE, 'Datos grabados correctamente.');
				return $this->redirect(['index', 'id' => $model->ag_rete]);
			}

			//return var_dump($model->getErrors());
		}


		$extras= $this->extras($id, 3);
		return $this->render('_form', $extras);
	}

	/**
	* TODO ver si se puede eliminar una declaracion jurada
	*/
	public function actionDelete($id){

		$model= Retencion::findOne(['retdj_id' => $id]);
		if(Yii::$app->request->isPost && $model != null && $model->borrar()){

			$this->removerSession();

			Yii::$app->session->setFlash(self::MENSAJE, 'Datos grabados correctamente.');
			return $this->redirect(['index', 'id' => $model->ag_rete]);
		}


		if($model == null) $model= new Retencion();

		$extras= $this->extras($id, 2);
		return $this->render('_form', $extras);

	}

	/**
	* Coloca el detalle en session
	*/
	public function actionCreatedetalle($id){

		$id= intval($id);

		$this->modelDetalle= new RetencionDetalle($id);
		$this->modelDetalle->setScenario('insert');

		$hayError= null;

		if(Yii::$app->request->isPost && $this->modelDetalle->load(Yii::$app->request->post()) && $this->modelDetalle->validate())
			$hayError= !$this->setDetalle($this->modelDetalle, true);

		$extras= $this->extras($id);
		$extras['extrasDetalle'] += ['hayError' => $hayError];

		return $this->render('_form', $extras);
	}

	/**
	* Modifica el detalle en session
	*/
	public function actionUpdatedetalle($id, $numero){

		$id= intval($id);
		$hayError= null;

		$this->modelDetalle= $this->getDetalle($numero);

		if($this->modelDetalle != null){

			$this->modelDetalle->setScenario('update');

			if(Yii::$app->request->isPost && $this->modelDetalle->load(Yii::$app->request->post()) && $this->modelDetalle->validate())
				$hayError= !$this->setDetalle($this->modelDetalle, false);
		}

		if($this->modelDetalle == null){
			$this->modelDetalle= new RetencionDetalle($id);
			$this->modelDetalle->addError('ret_id', 'El detalle no existe');
		}

		$extras= $this->extras($id);
		$extras['extrasDetalle'] += ['hayError' => $hayError];

		return $this->render('_form', $extras);
	}

	/**
	* Elimina el detalle de la session
	*/
	public function actionDeletedetalle($id, $numero){

		$id= intval($id);

		$detalle= $this->getDetalle($numero);
		$this->borrarDetalle($detalle);
		$extras= $this->extras($id);

		$extras['extrasDetalle'] += ['hayError' => false];

		return $this->render('_form', $extras);
	}


	public function actionListado(){

		if(Yii::$app->request->isPost){

			$criterio= trim(Yii::$app->request->post('criterio'));
			$descripcion= trim(Yii::$app->request->post('descripcion'));

			Yii::$app->session->set('criterio', $criterio);
			Yii::$app->session->set('descripcion', $descripcion);

			return $this->redirect(['list_res']);
		}


		return $this->render('list_op');
	}

	public function actionList_res(){

		$criterio= Yii::$app->session->get('criterio', null);
		$descripcion= Yii::$app->session->get('descripcion', null);

		$dp= new ArrayDataProvider([
			'allModels' => Retencion::buscar($criterio),
			'key' => 'retdj_id',
			'pagination' => [
				'pageSize' => 40
				],

			'sort' => [
				'attributes' => [
					'ret_id',
					'ag_rete',
					'anio',
					'obj_id',
					'fecha',
					'est',
					'lugar'
				]
			]
			]);

		return $this->render('list_res', ['criterio' => $criterio, 'descripcion' => $descripcion, 'dataProvider' => $dp]);
	}

	public function actionSugerencialugar($term = ''){

		Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;

		return RetencionDetalle::sugerenciaLugar($term);
	}
}
