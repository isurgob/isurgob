<?php

namespace app\controllers\objeto;

use Yii;
use app\models\objeto\Escribano;
use yii\web\Controller;
use yii\data\ArrayDataProvider;	
use app\utils\db\utb;

/**
 * InmController implements the CRUD actions for Inm model.
 */
class EscribanoController extends Controller
{

    public function beforeAction($action)
	{
		$operacion = str_replace("/", "-", Yii::$app->controller->route);
		
		$permitirSiempre = ['objeto-escribano-completarobjeto'];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		return true;
	}

    public function actionIndex(){
		
		$model = new Escribano();
		
		$model->est = Yii::$app->request->get( 'estado', $model->est );
		$model->fecha_desde = Yii::$app->request->get( 'fecha_desde', $model->fecha_desde );
		$model->fecha_hasta = Yii::$app->request->get( 'fecha_hasta', $model->fecha_hasta );	
		$model->obj_id = Yii::$app->request->get( 'objeto', $model->obj_id );	
		$model->escribano = Yii::$app->request->get( 'escribano', $model->escribano );	
		
		return $this->render('//objeto/escribano/index',[
					'model' => $model,
					'estados' => $model->getEstados(),
					'escribanos' => $model->getEscribanos(),
					'dataProviderVentas' => new ArrayDataProvider([
												'allModels' => $model->buscar(),
												'pagination' => false,
												'sort' => [
													'attributes' => [
														'vta_id',
														'fecha',
														'objeto',
													],
													'defaultOrder' => [
														'vta_id' => SORT_ASC,
													]
												],
											])	
				]);
	}
	
	public function actionVenta ( $id ){
	
		$model = Escribano::findOne( $id );
		if ( $model == null )
			$model = new Escribano();
			
		return $this->render( '//objeto/escribano/venta', [
					'model' => $model, 
					'action' => 1,
					'dpComprador' => new ArrayDataProvider([
											'allModels' => $model->compradores,
											'pagination' => false,
										]),
					'dpVendedor' => new ArrayDataProvider([
											'allModels' => $model->vendedores,
											'pagination' => false,
										])	
					
				] );	
	
	}
	
	public function actionInformar ( $id ){
	
		$model = new Escribano();
		
		if ( $model->Informar( $id) ){
			Yii::$app->session->setFlash( "mensaje", "Los datos se grabaron correctamente" );

            return $this->redirect([ 'index' ]);
		}else 
			Yii::$app->session->setFlash( "error_cons", $model->errors );
	}
	
	public function actionCompletarobjeto(){
	
		$obj_id = Yii::$app->request->post( 'obj_id', 0 );
		
		if ( $obj_id !== "") $obj_id = utb::getObjeto(1, $obj_id);
		
		$devolver = [
				'obj_id' => $obj_id,
				'nombre' => utb::getNombObj($obj_id, false),
			];
			
		echo json_encode($devolver);
	}
	
	public function actionImprimir($e, $fd, $fh, $o, $es) {
		
		$model = new Escribano();
		
		$model->est = $e;
		$model->fecha_desde = $fd;
		$model->fecha_hasta = $fh;	
		$model->obj_id = $o;
		$model->escribano = $es;
		
		$model->Imprimir();
		
		return $this->redirect([ '//site/pdflist' ]);
	}
	
	public function actionImprimirventa( $id ){
	
		$model = Escribano::findOne( $id );
		if ( $model == null )
			$model = new Escribano();
		
		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/escribano_venta',['model' => $model]);

        return $pdf->render();	
		
	}

}
