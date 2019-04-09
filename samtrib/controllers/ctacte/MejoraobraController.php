<?php

namespace app\controllers\ctacte;

use Yii;
use yii\web\Controller;
use app\models\ctacte\MejoraObra;
use app\models\ctacte\MejoraTCalculo;
use app\models\ctacte\MejoraCuadra;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\models\ctacte\ListadoCMejoraObra;
use app\controllers\ctacte\ListadocmejoraobraController;


class MejoraobraController extends Controller
{

	public function beforeAction($action){
		
		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    $permitirSiempre = ['ctacte-mejoraobra-listado', 'ctacte-mejoraobra-datostcalculo'];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
		
		return true;
	}
	
	public function actionListado(){
		$model = new ListadoCMejoraObra();
		$model->numero_desde = 0;
		$model->numero_hasta = 9999;
		$res = $model->buscar();
		
		$datos = ListadocmejoraobraController::datosResultado($model, $res);
		
		$dataProviderResultados = new ActiveDataProvider([
		    'query' => $res,
		    //'params' => [':status' => 1],
			'key' 		=> $model->pk(),
		    'pagination' => [
		        'pageSize' => 60,
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
	
	public function actionIndex( $consulta = 1, $id = 0 ){
	
		$model = MejoraObra::findOne( $id );
		if ( $model == null ) $model = new MejoraObra();
		
		$modelCuadra = new MejoraCuadra();
		
		$cuadra_id = Yii::$app->request->get( 'CuadraId', 0 );
		$model->cuadras = isset($_GET['listaCuadra']) ? unserialize(urldecode(stripslashes($_GET['listaCuadra']))) : $model->cuadras;
		
		$cuadra = $model->getCuadra($cuadra_id);
		
		if( Yii::$app->request->isPost ){
			
			switch( $consulta ){

                case 0:

                    $model->setScenario( 'nuevo' );
                    break;

                case 2:

                    $model->setScenario( 'eliminar' );
                    break;

                case 3:

                    $model->setScenario( 'modificar' );
                    break;
            }
			
			if( $model->load( Yii::$app->request->post() ) ){
					
				$model->cuadras = unserialize(urldecode(stripslashes($_POST['listaCuadra'])));
				
				if ( $model->validate() and $model->Grabar() ) {
					Yii::$app->session->setFlash( 'mensaje', 'Los datos se grabaron correctamente' );
				
					return $this->redirect(['index', 'id' => $model->obra_id ]);
				}	
				
			}
			
		}
		
		if( Yii::$app->request->get( '_pjax', '' ) == "#PjaxGrillaCuadra" ){
			$CuadraAction = Yii::$app->request->get( 'CuadraAction', 1 );	
			$CuadraId = Yii::$app->request->get( 'CuadraId', 0 );	
			$CuadraS1 = Yii::$app->request->get( 'CuadraS1', "" );	
			$CuadraS2 = Yii::$app->request->get( 'CuadraS2', "" );	
			$CuadraS3 = Yii::$app->request->get( 'CuadraS3', "" );	
			$CuadraManz = Yii::$app->request->get( 'CuadraManz', "" );	
			$CuadraCalleId = Yii::$app->request->get( 'CuadraCalleId', 0 );	
			$CuadraCalleNom = Yii::$app->request->get( 'CuadraCalleNom', "" );	
			$CuadraObs = Yii::$app->request->get( 'CuadraObs', "" );	
						
			$model->cuadras = unserialize(urldecode(stripslashes($_GET['listaCuadra'])));

			$model->abmCuadra($CuadraAction, $CuadraId, $CuadraS1, $CuadraS2,$CuadraS3,$CuadraManz,$CuadraCalleId,$CuadraCalleNom,$CuadraCalleNom,$CuadraObs);
			
		}
				
		return $this->render('//ctacte/cmejora/obra/view', [
					'model' => $model, 
					'consulta' => $consulta,
					'dataProviderCuadras' => new ArrayDataProvider([
												'allModels' => $model->cuadras,
												'pagination' => ['pagesize' => count($model->cuadras)]
											]),
					'mensaje' => Yii::$app->session->getFlash( 'mensaje', '' ) 	,
					'cuadra' => $cuadra,
					'modelCuadra' => $modelCuadra,
					'dpObras' => new ArrayDataProvider([
									'allModels' => MejoraObra::todas($model->nombre, $model->tobra),
									'key' => 'obra_id'
								])
				]);
	
	}
	
	public function actionDatostcalculo( $id )
	{
    	$id = intVal($id);
		
		$model = MejoraTCalculo::findOne( ['cod' => $id] );
		if ( $model == null ) $model = new MejoraTCalculo();
		
		$devolver = [
    			'ped_valormetro' => intVal($model->ped_valormetro),
    			'ped_valortotal' => intVal($model->ped_valortotal),
				'ped_fijo' => intVal($model->ped_fijo),
				'ped_frente' => intVal($model->ped_frente),
				'ped_supafec' => intVal($model->ped_supafec)
    		];
    	return json_encode($devolver); 
    }
	
	public function actionBuscar(){
		
		if(Yii::$app->request->isPost){
		
			$codigo = intval(Yii::$app->request->post('codigoObra', 0));
			
			return $this->redirect(['index', 'id' => $codigo]);	
		}
		
		$nombre = trim(Yii::$app->request->get('nombre', ''));
		$tipo = trim(Yii::$app->request->get('tipo', ''));
		
		$model = new MejoraObra();
				
		$dp = new ArrayDataProvider([
				'allModels' => MejoraObra::todas($nombre, $tipo),
				'key' => 'obra_id'
				]);
				
		return $this->render('//ctacte/cmejora/obra/buscar', ['dpObras' => $dp, 'model' => $model]);
	}
	
	public function actionImprimir( $id ) {
	
		$model = MejoraObra::findOne( $id );
		if ( $model == null ) $model = new MejoraObra();
		
		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/mejobra', [ 'model' => $model ]);

        return $pdf->render();
	
	}
	
	public function actionFrentistas($id = 0, $m = 0){
		
		$id = intval($id);
		$m = intval($m);
		$obra = $cuadra = 0;
		
		$model = MejoraObra::findOne(['obra_id' => $id]);
		if($model == null) $model = new MejoraObra();
		
		$filtrar = filter_var(Yii::$app->request->get("filtrar", false), FILTER_VALIDATE_BOOLEAN);
		
		$filtroCuadra = Yii::$app->request->get('cuadra', 0);
		$filtroObra = ($model->obra_id > 0 ? $model->obra_id : Yii::$app->request->get('obra', 0));
		
		$dpFrentistas = new ArrayDataProvider(['allModels' => $model->frentistas(), 'pagination' => ['pageSize' => 50]]);
		
		if($filtrar){
			
			$obra = intval(Yii::$app->request->get('obra', 0));
			$cuadra = Yii::$app->request->get('cuadra', '');
			if($cuadra != '') $cuadra = intval($cuadra);
			else $cuadra = null;
			
			$model->obra_id = $obra;
			
			$dpFrentistas = new ArrayDataProvider(['allModels' => $model->frentistas($cuadra), 'pagination' => ['pageSize' => 50]]);
			$filtroCuadra = $cuadra;
		}
		
		$mensaje = $this->mensajes($m);

		return $this->render('//ctacte/cmejora/obra/frentistas', [
					'model' => $model, 
					'modelCuadra' => new MejoraCuadra(),
					'filtroObra' => $filtroObra, 
					'filtroCuadra' => $filtroCuadra, 
					'dpFrentistas' => $dpFrentistas, 
					'mensaje' => $mensaje
				]);
	}
	
	public function actionGenerar(){
		
		$model = new MejoraObra();
		$modelCuadra = new MejoraCuadra();

		$modelCuadra->setScenario('generar');
		if(Yii::$app->request->isPost && $modelCuadra->load(Yii::$app->request->post())){
			
			
			$res = $modelCuadra->generar();
			
			if($res) return $this->redirect(['frentistas', 'obra' => $modelCuadra->obra_id, 'cuadra' => $modelCuadra->cuadra_id, 'm' => 5]);
		}
		
		return $this->render('//ctacte/cmejora/obra/_form_generar', ['modelCuadra' => $modelCuadra]);
	}
	
	public function actionImprimirexportarfrente( $obra, $cuadra, $exportar=0 ){
	
		$obra = intVal($obra);
		$cuadra = intVal($cuadra);
		
		$model = MejoraObra::findOne(['obra_id' => $obra]);
		if($model == null) $model = new MejoraObra();
		
		$modelCuadra = MejoraCuadra::findOne(['cuadra_id' => $cuadra]);
		if($modelCuadra == null) $modelCuadra = new MejoraCuadra();
		
		Yii::$app->session['proceso_asig'] = 3391;
		
		Yii::$app->session['titulo'] = 'Listado de Frentistas de Obras';
		Yii::$app->session['condicion'] =  ( $obra != 0 ? "<br> - Obra: $obra - " . $model->nombre : "" ) . 
											( $cuadra != 0 ? "<br> - Cuadra: $cuadra - " . $modelCuadra->calle_nom . " - " . $modelCuadra->ncm : "" ) ;
											
		Yii::$app->session['sql'] = "select plan_id, obj_id, obj_nom, monto, fchalta, fchbaja, fchdesaf, est_nom from v_mej_plan " . 
									" where obra_id = $obra " . ( $cuadra != 0 ? " and cuadra_id=$cuadra " : "" );									
									
		Yii::$app->session['columns'] = [
							['attribute' => 'plan_id', 'label' => 'Plan'],
							['attribute' => 'obj_id', 'label' => 'Frentista'],
							['attribute' => 'obj_nom', 'label' => 'Nombre'],
							['attribute' => 'monto', 'label' => 'Monto'],
							['attribute' => 'fchalta', 'label' => 'Alta', 'format' => ['date', 'php:d/m/Y']],
							['attribute' => 'fchbaja', 'label' => 'Baja', 'format' => ['date', 'php:d/m/Y']],
							['attribute' => 'fchdesaf', 'label' => 'Desaf.', 'format' => ['date', 'php:d/m/Y']],
							['attribute' => 'est_nom', 'label' => 'Est.']
						];
						
		if ( $exportar == 0 )
			return $this->redirect(['//site/pdflist']);			
		else
			return "";
		
	}
	
	private function mensajes($m){
		
		switch($m){
			
			case 1: return 'Datos grabados correctamente.';
			case 2: return 'Vencimiento actualizado correctamente';
			case 3: return 'La liquidación del plan ha sido pasada a cuenta corriente correctamente';
			case 4: return 'La liquidación del plan ha sido desafectada correctamente';
			case 5: return 'Frentistas generados correctamente';
			case 6: return 'Item agregado correctamente';
			
			
			case 10: return 'No hay resultados que coincidan con el criterio de busqueda';
		}
		
		return null;
	}

}
