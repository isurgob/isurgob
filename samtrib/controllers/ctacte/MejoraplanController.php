<?php

namespace app\controllers\ctacte;

use Yii;
use yii\web\Controller;
use app\models\ctacte\MejoraTCalculo;
use app\models\ctacte\MejoraPlan;
use app\models\ctacte\MejoraObra;
use app\models\ctacte\PlanConfig;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\models\ctacte\ListadoCMejoraPlan;
use app\controllers\ctacte\ListadocmejoraplanController;


class MejoraplanController extends Controller
{

	public function beforeAction($action){

		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    $permitirSiempre = ['ctacte-mejoraplan-datosobra', 'ctacte-mejoraplan-datostplan', 'ctacte-mejoraplan-calcularfinancia', 'ctacte-mejoraplan-calculartotal'];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		if ( isset($_GET['consulta']) ){

			if ( in_array($_GET['consulta'], [0, 2, 3, 6]) and !utb::getExisteProceso(3392) )
				return false;

			if ( $_GET['consulta'] == 4  and !utb::getExisteProceso(3393) )
				return false;

			if ( $_GET['consulta'] == 5  and !utb::getExisteProceso(3395) )
				return false;

		}

		return true;
	}

	public function actionIndex( $consulta = 1, $id = 0 ){

		$model = MejoraPlan::findOne( ['plan_id' => $id] );
		if ( $model == null ) $model = new MejoraPlan();
		$modelTCalculo = MejoraTCalculo::findOne( ['cod' => intVal($model->tcalculo)] );
		if ( $modelTCalculo == null ) $modelTCalculo = new MejoraTCalculo();

		$cuotas = $model->getCuotas();

		if( Yii::$app->request->isPost ){

			switch( $consulta ){

                case 0:

                    $model->setScenario( 'insert' );
                    break;

                case 2:

                    $model->setScenario( 'delete' );
                    break;

                case 3:

                    $model->setScenario( 'update' );
                    break;

				case 4:

                    $model->setScenario( 'actacte' );
                    break;

				case 5:

                    $model->setScenario( 'desafectar' );
                    break;

				case 6:

                    $model->setScenario( 'vencimiento' );
                    break;
            }

			if( $model->load( Yii::$app->request->post() ) ){

				$cuotas = isset($_POST['arrayCuotas']) ? unserialize(urldecode(stripslashes($_POST['arrayCuotas']))) : $cuotas;

				if ( $model->validate() and $model->Grabar() ) {
					Yii::$app->session->setFlash( 'mensaje', 'Los datos se grabaron correctamente' );

					return $this->redirect(['index', 'id' => $model->plan_id ]);
				}

			}

		}

		if( Yii::$app->request->post( '_pjax', '' ) == "#PjaxGrillaCuotas" ){
			$cuotas = json_decode( $_POST['cuotas'] );
		}

		$model->obra_id = Yii::$app->request->get( 'obra', $model->obra_id );

		return $this->render('//ctacte/cmejora/plan/view', [
					'model' => $model,
					'modelTCalculo' => $modelTCalculo,
					'dataProviderCuotas' => new ArrayDataProvider([
												'allModels' => $cuotas,
												'pagination' => ['pagesize' => count($cuotas)]
											]),
					'consulta' => $consulta,
					'mensaje' => Yii::$app->session->getFlash( 'mensaje', '' )
				]);

	}

	public function actionBuscar(){

		$tipoBusqueda = trim(Yii::$app->request->post('tipoBusqueda', ''));

		switch($tipoBusqueda){

			//busqueda por codigo de plan
			case 'codigoPlan':

				$plan_id = intval(Yii::$app->request->post('buscarCodigoPlan', 0));
				$existe = false;

				if($plan_id > 0) $existe = utb::getCampo('mej_plan', "plan_id = $plan_id", "plan_id") == $plan_id;

				if($existe) return $this->redirect(['index', 'id' => $plan_id]);
				else {
					Yii::$app->session->setFlash( 'mensaje', 'No hay resultados que coincidan con el criterio de busqueda' );
					return $this->redirect(['index']);
				}
				break;

			case 'codigoObjeto':


				$codigoObjeto = trim(Yii::$app->request->post('buscarCodigoObjeto'));

				if(strlen($codigoObjeto) < 8) $codigoObjeto = utb::getObjeto(1, $codigoObjeto);

				$modelos = MejoraPlan::findAll(['obj_id' => $codigoObjeto]);

				if($modelos == null){
					Yii::$app->session->setFlash( 'mensaje', 'No hay resultados que coincidan con el criterio de busqueda' );
					return $this->redirect(['index', 'm' => 10]);
				}

				$cantidad = count($modelos);

				if($cantidad == 1) return $this->redirect(['index', 'id' => $modelos[0]->plan_id]);
				else
					return $this->redirect(['listado', 'codigoObjeto' => $codigoObjeto]);

				break;
		}

		return $this->render('//ctacte/cmejora/plan/buscar');

	}

	public function actionDatosobra( $obra )
	{
    	$obra = intVal($obra);

		$modelObra = MejoraObra::findOne([ 'obra_id' => $obra ]);
		if ( $modelObra == null ) $modelObra = new MejoraObra();

		$modelTCalculo = MejoraTCalculo::findOne([ 'cod' => intVal($modelObra->tcalculo) ]);
		if ( $modelTCalculo == null ) $modelTCalculo = new MejoraTCalculo();

		$devolver = [
    			'tcalculo'			=> intVal($modelObra->tcalculo),
				'tcalculo_nombre'	=> $modelTCalculo->nombre,
				'ped_frente'		=> $modelTCalculo->ped_frente,
				'ped_supafec'		=> $modelTCalculo->ped_supafec,
				'ped_coef'			=> $modelTCalculo->ped_coef,
				'ped_monto'			=> $modelTCalculo->ped_monto,
				'valormetro'		=> $modelObra->valormetro,
				'valortotal'		=> $modelObra->valortotal,
				'bonifobra'			=> $modelObra->bonifobra,
				'fijo'				=> $modelObra->fijo
    		];
    	return json_encode($devolver);
    }

	public function actionDatostplan( $tplan )
	{
    	$tplan = intVal($tplan);

		$model = PlanConfig::findOne([ 'cod' => $tplan ]);
		if ( $model == null ) $model = new PlanConfig();

		$devolver = [
    			'anticipomanual'	=> intVal($model->anticipomanual)
    		];
    	return json_encode($devolver);
    }

	public function actionCalcularfinancia(){

		$total = floatVal(Yii::$app->request->post('total', 0));
		$cuotas = intVal(Yii::$app->request->post('cuotas', 0));
		$venc = Yii::$app->request->post('venc', "");
		$tplan = intVal(Yii::$app->request->post('tplan', 0));
		$obj_id = Yii::$app->request->post('obj_id', "");
		$anticipo = floatVal(Yii::$app->request->post('anticipo', 0));

		$devolver = MejoraPlan::CalcularFinancia( $total, $cuotas, $venc, $tplan, $obj_id, $anticipo );

		return json_encode($devolver);
	}

	public function actionCalculartotal(){

		$objeto = trim(Yii::$app->request->post('objeto', ""));
		$obra = intVal(Yii::$app->request->post('obra', 0));
		$frente = floatVal(Yii::$app->request->post('frente', 0));
		$supafec = floatVal(Yii::$app->request->post('supafec', 0));
		$coef = floatVal(Yii::$app->request->post('coef', 0));
		$monto = floatVal(Yii::$app->request->post('monto', 0));

		$total = MejoraPlan::CalculaTotal( $objeto, $obra, $frente, $supafec, $coef, $monto );

		$devolver = [
    			'total'	=> floatVal($total)
    		];
    	return json_encode($devolver);

	}

	public function actionImprimircuota ( $plan ) {

		$cuotas = isset($_POST['imprimir_cuotas']) ? unserialize(urldecode(stripslashes($_POST['imprimir_cuotas']))) : [];

		$capital = 0;
		$financia = 0;
		$total = 0;
		$sellado = 0;

		foreach ($cuotas as $c){
			$capital += $c->capital;
			$financia += $c->financia;
			$total += $c->total;
			$sellado += $c->sellado;
		}

		if ($cuotas) {
			$datos['dataProviderResultados'] = new ArrayDataProvider([
													'allModels' => $cuotas,
													'pagination' => ['pagesize' => count($cuotas)]
												]);

			$datos['columnas'] = [
					[ 'attribute' => 'cuota_nom', 'label' => 'Cuota'],
					[ 'attribute' => 'capital', 'label' => 'Capital', 'footer' => "<hr style='margin:0px'><b>" . $capital . "</b>", 'contentOptions' => ['style' => 'text-align:right'] ],
					[ 'attribute' => 'financia', 'label' => 'Financia', 'footer' => "<hr style='margin:0px'><b>" . $financia . "</b>", 'contentOptions' => ['style' => 'text-align:right'] ],
					[ 'attribute' => 'total', 'label' => 'Total', 'footer' => "<hr style='margin:0px'><b>" . $total . "</b>", 'contentOptions' => ['style' => 'text-align:right'] ],
					[ 'attribute' => 'venc', 'label' => 'Vencimiento'],
					[ 'attribute' => 'sellado', 'label' => 'Sellado', 'footer' => "<hr style='margin:0px'><b>" . $sellado . "</b>", 'contentOptions' => ['style' => 'text-align:right'] ],
				];

			$titulo = "Preliminar de Cuotas - Plan Mejora $plan ";
			$datos['descripcion'] = "";

			$pdf = Yii::$app->pdf;
			if (strtoupper($format) != 'A4-P') $pdf->format = strtoupper($format);
			$pdf->marginTop = '30px';
			$pdf->content = $this->renderPartial('//reportes/reportelistado', ['datos' => $datos, 'titulo' => $titulo ]);

			return $pdf->render();
		}else
			return false;

	}

	public function actionImprimircontrato($id)
    {
    	Yii::$app->session['PlanContrato'] = null;
    	$error = "";
    	$datos = MejoraPlan::ImprimirContrato($id,$error);
    	$model = MejoraPlan::findOne(['plan_id' => $id]);

    	if ($model->est != 'L') $error .= "<li>El Estado de la Liquidaci�n es incorrecto</li>";

   		if ($error == '')
    	{
    		Yii::$app->session['PlanContrato'] = $datos;
    		$url = Yii::$app->request->baseUrl."/index.php?r=ctacte/mejoraplan/imprimircontratopdf";
		 	echo "<script>window.open('".$url."','_blank');</script>";
		 	echo "<script>history.go(-1);</script>";
    	}else {
   			Yii::$app->session->setFlash( 'mensaje', $error );
			return $this->redirect(['index', 'id' => $id]);
   		}
    }

	public function actionImprimircontratopdf()
    {
    	$datos = Yii::$app->session['PlanContrato'];
    	Yii::$app->session['PlanContrato'] = null;

    	$pdf = Yii::$app->pdf;
  		$pdf->content = $this->renderPartial('//reportes/contrato',
  							['condicion' => Yii::$app->params['MUNI_NAME'],'usuario' => Yii::$app->user->id, 'datos' => $datos]);
    	return $pdf->render();
    }

	public function actionImprimirresumen($id)
    {
    	$sub1 = null;
    	$datos = MejoraPlan::ImprimirResumen($id,$sub1);

    	$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
    	$pdf->content = $this->renderPartial('//reportes/mejplanresumen',['datos'=>$datos,'sub1'=>$sub1]);

    	return $pdf->render();
    }

	public function actionImprimircomprobantevalida($id,$desde=0,$hasta=0,$ext=0)
    {
    	$error = "";
    	$id = ($id != "" ? $id : 0);
    	$cuotadesde = (isset($_POST['txCtaDesde']) ? $_POST['txCtaDesde'] : $desde);
    	$cuotahasta = (isset($_POST['txCtaHasta']) ? $_POST['txCtaHasta'] : $hasta);

    	$error = MejoraPlan::ImprimirComprobanteValida($id, $cuotadesde,$cuotahasta);

    	if ($error == "")
    	{
    		 if ($ext == 0) {
    		 	$url = Yii::$app->request->baseUrl."/index.php?r=ctacte/mejoraplan/imprimircomprobante&id=".$id."&desde=".$cuotadesde."&hasta=".$cuotahasta;
    		 	echo "<script>window.open('".$url."','_blank');</script>";
    		 	echo "<script>history.go(-1);</script>";
    		 }else {
    		 	return $this->redirect(['imprimircomprobante', 'id' => $id,'desde'=>$cuotadesde,'hasta'=>$cuotahasta]);
    		 }
    	}else {
   			Yii::$app->session->setFlash( 'mensaje', $error );
			return $this->redirect(['index', 'id' => $id]);
   		}
    }

    public function actionImprimircomprobante($id,$desde=0,$hasta=0)
    {
    	$id = ($id != "" ? $id : 0);

    	$emision = array();
    	$sub1 = array();
    	$sub2 = array();
    	$sub3 = array();
    	$sub4 = array();
    	MejoraPlan::ImprimirComprobante($id, $desde,$hasta,$emision,$sub1,$sub2,$sub3,$sub4);

    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '5px';
    	$pdf->marginBottom = '5px';
  		$pdf->content = $this->renderPartial('//reportes/boleta',
  									[
										'emision' => $emision,
										'sub1' => $sub1,
										'sub2' => $sub2,
										'sub3' => $sub3,
										'sub4' => $sub4,
										'vencdesc' => 'Venc.',
										'tituloseccion' => 'PERIODOS INCLUIDOS:'
									]);
  		$pdf->methods['SetHeader'] = '';
  		$pdf->methods['SetFooter'] = '';
  		$pdf->filename = 'Boleta';
  		return $pdf->render();
    }

	public function actionListado($codigoObjeto){

		$model = new ListadoCMejoraPlan();
		$model->objeto_id_desde = $codigoObjeto;
		$model->objeto_id_hasta = $codigoObjeto;
		$res = $model->buscar();

		$datos = ListadocmejoraplanController::datosResultado($model, $res);

		$dataProviderResultados = new ActiveDataProvider([
			'query' => $res,
			//'params' => [':status' => 1],
			'key' 		=> $model->pk(),
			'pagination' => [
				'pageSize' => 60000,
			],
			'sort'	=> $model->sort(),
		]);

		return $this->render('//listado/base_resultado', [
									'breadcrumbs' => $datos['breadcrumbs'],
									'descripcion' => '',
									'model' => $model,
									'dataProviderResultados' => $dataProviderResultados,
									'columnas' => $datos['columnas'],
									'urlOpciones' => $datos['urlOpciones'],
									'nuevo' => $datos['nuevo'],
								]);
	}

}
