<?php

namespace app\controllers\ctacte;

use Yii;


use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\data\Sort;

use yii\db\Query;
use yii\db\Expression;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Session;

use yii\filters\VerbFilter;

use app\models\ctacte\Tribacc;
use app\utils\db\utb;
use app\models\config\Texto;


class TribaccController extends Controller {

	private $tipo;
	private $consulta;
	private $mensaje;
	private $grabar;

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

    private function resolverPost($model, $condicion = []){

    	$res = false;
    	if(!$this->grabar) return $model;

    	switch($this->consulta){

    		case 0:
    			$model->setScenario('insert');

    			if($model->load(Yii::$app->request->post()) && !$model->hasErrors())
    				$res = $model->grabar();

    			break;

    		case 2:
    			$model->setScenario('delete');

    			if($model->load(Yii::$app->request->post()) && !$model->hasErrors()){

    				//prescipcion y djfaltantes no se cargan los datos desde la tabla
    				if($model->tipoAcc !== Tribacc::TIPO_PRESCRIPCION && $model->tipoAcc !== Tribacc::TIPO_DJ_FALTANTE)
    					$model = $model->buscarUno($model->tipoAcc, $condicion);

    				$res = $model->borrar();
    			}

    			break;

    		case 3:
    			$model->setScenario('update');

    			if($model->load(Yii::$app->request->post()) && !$model->hasErrors()){

    				//prescipcion y djfaltantes no se cargan los datos desde la tabla
    				if($model->tipoAcc !== Tribacc::TIPO_PRESCRIPCION && $model->tipoAcc !== Tribacc::TIPO_DJ_FALTANTE)
    					$model = $model->buscarUno($model->tipoAcc, $condicion);

    				$model->load(Yii::$app->request->post());

    				$res = $model->grabar();
    			}

    			break;
    	}

    	return $model;
    }

	public function beforeAction($action){

		$operacion = str_replace("/", "-", Yii::$app->controller->route);

		$permitirSiempre = ['ctacte-tribacc-vencimiento','ctacte-tribacc-credhabil','ctacte-tribacc-constancia'];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		$this->tipo = Yii::$app->request->post('tipo', Yii::$app->request->get('tipo', 'null'));
		$this->consulta = intval(Yii::$app->request->post('consulta', Yii::$app->request->get('c', -1)));
		$this->mensaje = $this->getMensaje(intval(Yii::$app->request->get('m', 0)));
		$this->grabar = filter_var(Yii::$app->request->post('grabar', false), FILTER_VALIDATE_BOOLEAN);

		return true;
	}

	public function actionListado(){

		

		 return $this->redirect(['//ctacte/listadotribacc/index', ['tipo' => 'asig']]);


    	
	}

	public function actionAsig($listar = true, $obj_id = '', $subcta = '', $orden = '', $trib_id = '', $item_id = '', $perdesde = '', $c = 1){

		$tipo = Tribacc::TIPO_ASIGNACION;
		$model = new Tribacc($tipo);
		$condicion = ['obj_id' => "'$obj_id'", 'subcta' => $subcta, 'orden' => $orden, 'trib_id' => $trib_id, 'item_id' => $item_id, 'perdesde' => $perdesde];


		if(Yii::$app->request->isPost && $this->grabar){

			$model = $this->resolverPost($model, $condicion);

			if(!$model->hasErrors()){

				if($this->consulta !== 3 && $this->consulta !== 0)
					return $this->redirect(['asig', 'listar' => false, 'c' => 1, 'm' => 1]);
				else return $this->redirect([
								'asig', 'listar' => false,
								'obj_id' => $model->obj_id, 'subcta' => $model->subcta,
								'orden' => $model->orden, 'trib_id' => $model->trib_id,
								'item_id' => $model->item_id, 'perdesde' => $model->perdesde,
								'c' => 1, 'm' => 1
							]);

				}
		} else{

			if($this->consulta !== 0){

				if($this->consulta === -1 || filter_var($listar, FILTER_VALIDATE_BOOLEAN)) return $this->actionListado($tipo);

				if($obj_id != '' && strlen($subcta) > 0 && strlen($orden) > 0 && $trib_id != '' && strlen($item_id) > 0 && strlen($perdesde) > 0)
					$model = Tribacc::buscarUno($tipo, $condicion);
			}
		}

		return $this->render('_form_asignacion', ['model' => $model, 'consulta' => $this->consulta, 'mensaje' => $this->mensaje]);
	}

	/**
	 *
	 */
	public function actionExcep($listar = true, $excep_id = ''){

		$tipo = Tribacc::TIPO_EXCEPCION;
		$model = new Tribacc($tipo);
		$condicion = ['excep_id' => intval($excep_id)];

		if(Yii::$app->request->isPost && $this->grabar){

			$model = $this->resolverPost($model, $condicion);

			if(!$model->hasErrors()){

				if($this->consulta !== 3 && $this->consulta !== 0)
					return $this->redirect(['excep', 'listar' => false, 'c' => 1, 'm' => 1]);
				else return $this->redirect([
								'excep', 'listar' => $model->pornum == 1 ? true : false,
								'excep_id' => $model->excep_id,
								'c' => 1, 'm' => 1
							]);
			}
		} else{

			if($this->consulta !== 0){

				if($this->consulta === -1 || filter_var($listar, FILTER_VALIDATE_BOOLEAN)) return $this->actionListado($tipo);

				if(strlen($excep_id) > 0)
					$model = Tribacc::buscarUno($tipo, $condicion);
			}
		}

		return $this->render('_form_excepcion', ['model' => $model, 'consulta' => $this->consulta, 'mensaje' => $this->mensaje]);
	}

	/**
	 *
	 */
	public function actionInscrip($listar = true, $obj_id = '', $trib_id = '', $perdesde = ''){

		$tipo = Tribacc::TIPO_INSCRIPCION;
		$model = new Tribacc($tipo);
		$condicion = ['obj_id' => "'$obj_id'", 'trib_id' => $trib_id, 'perdesde' => $perdesde];

		if(Yii::$app->request->isPost && $this->grabar){

			$model = $this->resolverPost($model, $condicion);

			if(!$model->hasErrors()){

				if($this->consulta !== 3 && $this->consulta !== 0)
					return $this->redirect(['inscrip', 'listar' => false, 'c' => 1, 'm' => 1]);
				else
				 return $this->redirect([
								'inscrip',
								'listar' => false,
								'obj_id' => $model->obj_id,
								'trib_id' => $model->trib_id,
								'perdesde' => $model->perdesde,
								'c' => 1,
								'm' => 1
							]);
			}

		} else{

			if($this->consulta !== 0){

				if($this->consulta === -1 || filter_var($listar, FILTER_VALIDATE_BOOLEAN)) return $this->actionListado($tipo);

				if($obj_id != '' && strlen($trib_id) > 0 && strlen($perdesde) > 0)
					$model = Tribacc::buscarUno($tipo, $condicion);
			}else {
				$texto = Texto::findOne(['tuso' => 18]);
				if ($texto !== null)
					$model->obs = $texto->detalle;
			}
		}

		return $this->render('_form_inscripcion', ['model' => $model, 'consulta' => $this->consulta, 'mensaje' => $this->mensaje]);
	}

	public function actionCondona($listar = true, $obj_id = '', $trib_id = '', $perdesde = ''){

 		$tipo = Tribacc::TIPO_CONDONACION;
		$model = new Tribacc($tipo);
		$condicion = ['obj_id' => "'$obj_id'", 'trib_id' => $trib_id, 'perdesde' => $perdesde, 'tipo' => 2];

		if(Yii::$app->request->isPost && $this->grabar){

			$res = false;
			$modelRes = null;

			//condonacion no se puede modificar, solamente se crea o elimina
			if($this->consulta === 0 || $this->consulta === 2)
				$model = $this->resolverPost($model, $condicion);

			if(!$model->hasErrors()){

				if($this->consulta !== 3 && $this->consulta !== 0)
					return $this->redirect(['condona', 'listar' => false, 'c' => 0, 'm' => 1]);
				else
				 return $this->redirect([
								'condona',
								'listar' => false,
								'obj_id' => $model->obj_id,
								'trib_id' => $model->trib_id,
								'perdesde' => $model->perdesde,
								'c' => 1,
								'm' => 1
							]);
			}

		} else{

			if($this->consulta !== 0){

				if($this->consulta === -1 || filter_var($listar, FILTER_VALIDATE_BOOLEAN)) return $this->actionListado($tipo);

				if($obj_id != '' && strlen($trib_id) > 0 && strlen($perdesde) > 0)
					$model = Tribacc::buscarUno($tipo, $condicion);
			}
		}

		return $this->render('_form_condonacion', ['model' => $model, 'consulta' => $this->consulta, 'mensaje' => $this->mensaje]);
	}

	public function actionPrescrip(){

		$tipo = Tribacc::TIPO_PRESCRIPCION;
		$model = new Tribacc($tipo);
		$accion= 0;

		if(Yii::$app->request->isPost && $this->grabar){

			$accion = intval(Yii::$app->request->post('Tribacc')['accion'], 0);

			if($accion == 1) $this->consulta = 0;
			else if($accion == 2) $this->consulta = 0;
			else{
				$model->addError('accion', 'Elija una acción');
				$model->setScenario('insert');
				$model->load(Yii::$app->request->post());
				$model->validate();
				$this->consulta= -1;
				return $this->render('_form_prescripcion', ['model' => $model, 'mensaje' => $this->mensaje, 'consulta' => $this->consulta]);
			}

			$model = $this->resolverPost($model);
			if(!$model->hasErrors()) return $this->redirect(['prescrip', 'm' => 1]);
		}

		return $this->render('_form_prescripcion', ['model' => $model, 'mensaje' => $this->mensaje, 'consulta' => $this->consulta]);
	}

	public function actionDjfalt(){

		$tipo = Tribacc::TIPO_DJ_FALTANTE;
		$model = new Tribacc($tipo);
		$accion= 0;

		if(Yii::$app->request->isPost && $this->grabar){

			$accion = intval(Yii::$app->request->post('Tribacc')['accion'], 0);

			if($accion == 3) $this->consulta = 0;
			else if($accion == 4) $this->consulta = 2;
			else{
				$model->addError('accion', 'Elija una acción');
				$model->setScenario('insert');
				$model->load(Yii::$app->request->post());
				$model->validate();
				$this->consulta= -1;
				return $this->render('_form_dj_faltantes', ['model' => $model, 'mensaje' => $this->mensaje, 'consulta' => $this->consulta]);
			}

			$model = $this->resolverPost($model);
			if(!$model->hasErrors()) return $this->redirect(['djfalt', 'm' => 1]);
		}

		return $this->render('_form_dj_faltantes', ['model' => $model, 'mensaje' => $this->mensaje, 'consulta' => $this->consulta]);
	}

	public function actionImprimirasig()
	{
		$sub = null;
		$tobj = 0;
		$datos = (new Tribacc(Tribacc::TIPO_ASIGNACION))->ImprimirAsig($sub,$tobj);

		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
		$pdf->marginFooter = '2px';
		$pdf->content = $this->renderPartial('//reportes/asignacion',
      			['datos' => $datos,'sub' => $sub,'tobj'=>$tobj]);

        return $pdf->render();
	}

	public function actionVencimiento()
	{
		$trib = isset($_POST['trib']) ? $_POST['trib'] : 0;
		$anio = isset($_POST['anio']) ? $_POST['anio'] : 0;
		$cuota = isset($_POST['cuota']) ? $_POST['cuota'] : 0;
		$objeto = isset($_POST['obj']) ? $_POST['obj'] : '';

		$venc = Tribacc::obtenerVencimiento($trib,$anio,$cuota,$objeto);

		return $venc;
	}

	public function actionCredhabil($obj_id, $trib_id, $perdesde)
	{
		$tipo = Tribacc::TIPO_INSCRIPCION;
		$model = new Tribacc($tipo);
		$condicion = ['obj_id' => "'$obj_id'", 'trib_id' => $trib_id, 'perdesde' => $perdesde];

		if($obj_id != '' && strlen($trib_id) > 0 && strlen($perdesde) > 0){
			$model = Tribacc::buscarUno($tipo, $condicion);

			$pdf = Yii::$app->pdf;
			$pdf->methods["SetHeader"] = '';
			$pdf->methods["SetFooter"] = '';
			$pdf->content = $this->renderPartial('//reportes/personacredhabil',
					['model' => $model]);

			return $pdf->render();
		}
	}

	public function actionConstancia($obj_id, $trib_id, $perdesde)
	{
		$tipo = Tribacc::TIPO_INSCRIPCION;
		$model = new Tribacc($tipo);
		$condicion = ['obj_id' => "'$obj_id'", 'trib_id' => $trib_id, 'perdesde' => $perdesde];

		if($obj_id != '' && strlen($trib_id) > 0 && strlen($perdesde) > 0){
			$model = Tribacc::buscarUno($tipo, $condicion);

			$pdf = Yii::$app->pdf;
			$pdf->marginTop = '40px';
			$pdf->marginFooter = '2px';
			$pdf->content = $this->renderPartial('//reportes/personaconstanciahabil',
					['model' => $model]);

			return $pdf->render();
		}
	}


	private function getMensaje($m){

		switch($m){

			case 1: return "Datos grabados correctamente";

			default: return '';
		}
	}
}
?>
