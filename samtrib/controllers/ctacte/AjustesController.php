<?php

namespace app\controllers\ctacte;

use Yii;
use yii\web\Controller;
use yii\web\Session;
use yii\data\ArrayDataProvider;

use app\models\ctacte\Ajustes;
use app\utils\db\utb;


/**
 * CalcDescController implements the CRUD actions for CalcDesc model.
 */
class AjustesController extends Controller
{

	const PARAM_CUENTAS = "ajustesCuentas";

	public function beforeAction($action){

		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		return true;
	}

	private function setCuenta($ajuste){

		$cuentas = $this->getCuentas();
		$c = ['cta_id' => $ajuste->cta_id, 'cta_nom' => $ajuste->cta_nom, 'debe' => $ajuste->debe, 'haber' => $ajuste->haber];
		array_push($cuentas, $c);

		$this->setCuentas($cuentas);
	}

	private function deleteCuenta($cta_id){

		$cuentas = $this->getCuentas();
		$quedan = [];

		foreach($cuentas as $c)
			if($c['cta_id'] != $cta_id) array_push($quedan, $c);

		$this->setCuentas($quedan);
	}

	private function getCuentas(){

		$session = new Session();
		$session->open();

		$cuentas = $session->get(self::PARAM_CUENTAS, []);

		$session->close();

		return $cuentas;
	}

	private function setCuentas($cuentas = []){

		$session = new Session();
		$session->open();

		$session->set(self::PARAM_CUENTAS, $cuentas);

		$session->close();
	}

	public function actionView($aju_id = '', $m = ''){

		$aju_id = intval($aju_id);
    	$m = intval($m);
    	$model = Ajustes::findOne(['aju_id' => $aju_id]);

    	if($model == null) $model = new Ajustes();

    	$this->setCuentas();
    	$dp = new ArrayDataProvider(['allModels' => $model->cuentas()]);
    	$extras = ['model' => $model, 'dpCuentas' => $dp, 'consulta' => 1];
    	$mensaje = $m == 1 ? 'Datos grabados correctamente' : null;

    	return $this->render('_form', ['extras' => $extras, 'mensaje' => $mensaje]);
	}

    public function actionCreate($aju_id = '', $m = null){

    	$m = intval($m);
    	if($m == 2){
    		$this->setCuentas();
    		return $this->redirect(['view', 'aju_id' => $aju_id, 'm' => 1]);
    	}

    	$aju_id = intval($aju_id);

    	$model = Ajustes::findOne(['aju_id' => $aju_id]);
    	$grabar = Yii::$app->request->post('grabar', null);

    	if($model == null) $model = new Ajustes();
		$model->setScenario('insert');

    	if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $grabar !== null){

			$model->detalleAjustes = $this->getCuentas();
			$res = $model->grabar();

			if($res) return $this->redirect(['create', 'aju_id' => $model->aju_id, 'm' => 2]);
    	}

    	$dp = new ArrayDataProvider(['allModels' => $this->getCuentas(), 'pagination' => false]);
    	$extras = ['model' => $model, 'dpCuentas' => $dp, 'consulta' => 0];

    	$mensaje = $m == 1 ? 'Datos grabados correctamente' : null;

    	return $this->render('_form', ['extras' => $extras, 'mensaje' => $mensaje]);
    }

    public function actionDelete( $aju_id ){

    	/*$aju_id = intval(Yii::$app->request->post('aju_id', 0));
    	$criterio = Yii::$app->request->post('txcriterio', '');
    	$descripcion = Yii::$app->request->post('txdescr', '');

    	$model = Ajustes::findOne(['aju_id' => $aju_id]);

    	if($model != null) $model->borrar();



    	return $this->render('list_res', ['cond' => $criterio, 'desc' => $descripcion]);*/
		
		$model = Ajustes::findOne(['aju_id' => $aju_id]);
		if($model != null) $model->borrar();
				
		return $this->redirect(['view', 'm' => 1]);
    }

    public function actionNuevacuenta(){


    	$model = new Ajustes();


    	if(Yii::$app->request->isPost){

    		$model->setScenario('cuenta');
    		if($model->load(Yii::$app->request->post()) && $model->validate()){

    			$this->setCuenta($model);
    		}
    	}


    	$extras = [];
    	$extras['model'] = $model;


    	return $this->render('_form_nueva_cuenta', ['extras' => $extras]);
    }

    public function actionDeletecuenta(){

    	$model = new Ajustes();

    	if(Yii::$app->request->isPost){

    		$cta_id = intval(Yii::$app->request->post('cta_id', 0));
			$consulta = intval(Yii::$app->request->post('consulta', 0));
    		$this->deleteCuenta($cta_id);
    	}

    	$extras = [];
    	$extras['model'] = $model;
    	$extras['dpCuentas'] = new ArrayDataProvider(['allModels' => $this->getCuentas(), 'pagination' => false]);
		$extras['consulta'] = $consulta;

    	return $this->render('_form', ['extras' => $extras]);
    }

    public function actionCargar(){

    	$model = new Ajustes();
    	$trib_id = intval(Yii::$app->request->get('trib_id', 0));
		$obj_id = trim(Yii::$app->request->get('obj_id', ''));
		$subcta = intval(Yii::$app->request->get('subcta', 0));
		$anio = intval(Yii::$app->request->get('anio', 0));
		$cuota = intval(Yii::$app->request->get('cuota', 0));

		$saldo = $model->getSaldo( $trib_id, $obj_id, $subcta, $anio, $cuota );

		$this->setCuentas( $model->cuentas() );

		$extras = [];
		$extras['model'] = $model;
		$extras['saldo'] = $saldo;
		$extras['dpCuentas'] = new ArrayDataProvider(['allModels' => $this->getCuentas(), 'pagination' => false]);
		$extras['consulta']= 0;

		return $this->render('_form', ['extras' => $extras]);
    }

    public function actionImprimir($id)
    {
    	$model = Ajustes::findOne(['aju_id' => $id]);
    	$sql = "Select to_char(Fecha, 'dd/MM/yyyy') Fecha, Operacion, Cta_Nom, Comprob, Debe, Haber From Sam.Uf_CtaCte_Det(" . $model->ctacte_id;
        $sql .= ") Where Operacion = 'Ajuste' and Comprob = " . $id . " and Est='A' Order by Cta_Id";

    	$cuentas = utb::ArrayGeneralCons($sql);

    	$pdf = Yii::$app->pdf;
		$pdf->content = $this->renderPartial('//reportes/ajustes',
      			['datos' => $model,'cuentas'=>$cuentas]);

        return $pdf->render();
    }
}
