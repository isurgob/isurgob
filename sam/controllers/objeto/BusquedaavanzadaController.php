<?php

namespace app\controllers\objeto;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\BaseUrl;

use app\models\objeto\avanzada\ComerBusquedaAvanzada;
use app\models\objeto\avanzada\InmuebleBusquedaAvanzada;
use app\models\objeto\avanzada\RodadoBusquedaAvanzada;
use app\models\objeto\avanzada\PersonaBusquedaAvanzada;
use app\models\objeto\avanzada\CementerioBusquedaAvanzada;
use app\models\objeto\avanzada\DumbBusquedaAvanzada;

class BusquedaavanzadaController extends Controller{

	const TIPO_INMUEBLE= 1;
	const TIPO_COMERCIO= 2;
	const TIPO_PERSONA= 3;
	const TIPO_CEMENTERIO= 4;
	const TIPO_RODADO= 5;

	private $tipoObjeto;
	private $vista;
	private $model;
	private $funcionValorOpcion;


	public function __construct(){

		parent::__construct('busquedaavanzada', Yii::$app);
	}

	private function resolverTipoObjeto($tipoObjeto){

		switch($tipoObjeto){

			case self::TIPO_COMERCIO: $this->model= new ComerBusquedaAvanzada(); $this->vista= '//objeto/comer/buscarav'; break;
			case self::TIPO_INMUEBLE: $this->model= new InmuebleBusquedaAvanzada(); $this->vista= '//objeto/inm/buscarav'; break;
			case self::TIPO_PERSONA: $this->model= new PersonaBusquedaAvanzada(); $this->vista= '//objeto/persona/buscarav'; break;
			case self::TIPO_CEMENTERIO: $this->model= new CementerioBusquedaAvanzada(); $this->vista= '//objeto/cem/buscarav'; break;
			case self::TIPO_RODADO: $this->model= new RodadoBusquedaAvanzada(); $this->vista= '//objeto/rodado/buscarav'; break;
			default: $this->model= new DumbBusquedaAvanzada(); $this->vista= ''; break;
		}

		$model= $this->model;
		$this->funcionValorOpcion= function($opcionElegida) use($model){
			return $model->obtenerValorOpcion($opcionElegida);
		};
	}

	public function beforeAction($action){

		$this->vista= '';
		$this->tipoObjeto= intval(Yii::$app->request->post('tipoObjeto', 0));
		$this->funcionValorOpcion= function($valor){};



		$this->resolverTipoObjeto($this->tipoObjeto);
		return true;
	}

	public function actionBuscar(){

		$dataProviderResultado= new ArrayDataProvider(['allModels' => []]);

		if($this->model->load(Yii::$app->request->post())){
			$result = $this->model->buscar();

			$dataProviderResultado= new ArrayDataProvider([
										'allModels' => is_array($result) ? $result : [],
										'sort' => [
											'attributes' => $this->model->ordenamientoVisual(),
											'defaultOrder' => ['obj_id' => SORT_ASC],
											'route' => BaseUrl::to('//objeto/busquedaavanzada/buscar')
										]
									]);
		}

		return $this->renderPartial($this->vista, ['model' => $this->model, 'dataProviderDatos' => $dataProviderResultado, 'valorOpcion' => $this->funcionValorOpcion, 'tipoObjeto' => $this->tipoObjeto]);
	}

	public function dibujar($tipoObjeto, $id, $txCod, $txNom, $selectorModal = null){


		$this->resolverTipoObjeto( $tipoObjeto );

		if($this->vista == '' || $this->model == null) return "";

		$dataProviderResultado= new ArrayDataProvider(['allModels' => []]);

		return $this->renderPartial($this->vista,
			['model' => $this->model,
			'dataProviderDatos' => $dataProviderResultado,
			'valorOpcion' => $this->funcionValorOpcion,
			'tipoObjeto' => $tipoObjeto,
			'id' => $id,
			'txCod' => $txCod,
			'txNom' => $txNom,
			'selectorModal' => $selectorModal
			]);
	}
}
?>
