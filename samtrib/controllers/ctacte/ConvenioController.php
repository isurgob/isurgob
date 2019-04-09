<?php

namespace app\controllers\ctacte;

use Yii;

use yii\web\Controller;
use yii\web\Session;
use app\models\objeto\Domi;
use app\utils\db\utb;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;

use yii\filters\VerbFilter;

use app\utils\helpers\DBException;
use app\models\ctacte\Plan;
use app\models\ctacte\PlanConfigUsuario;
use app\models\ctacte\PlanConfigDecae;
use app\models\ctacte\PlanConfig;


/**
 * ItemController implements the CRUD actions for Item model.
 */
class ConvenioController extends Controller
{

	private $extras = [];

	public function beforeAction($action){

		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		return true;
	}

	public function actionConfigdecae($tplan = null, $origen = null, $tpago = null, $caja_id = null)
	{
		$model = new PlanConfigDecae();

		if($tplan != null && $origen != null && $tpago != null && $caja_id != null)
			$model = PlanConfigDecae::findOne($tplan, $origen, $tpago, $caja_id);

		$consulta = Yii::$app->request->post('txAccion', 1);

		if($consulta != 1)
		{
			$cargado = false;

			switch($consulta)
			{
				case '0' :
						$model->scenario = 'insert';


						if( $model->load( Yii::$app->request->post() ) )
							$cargado = true;

						break;

				case '3' :
						$model->scenario = 'update';

						if(!$model->load(Yii::$app->request->post())){
							$cargado = false;
							break;
						}

						$model = $model->buscarUno();
						$model->scenario = 'update';

						$model->load(Yii::$app->request->post());

						$cargado = true;
						break;

				case '2' :
						$model->scenario = 'delete';

						$cargado = $model->load(Yii::$app->request->post());
						break;
			}

			if($cargado)
			{

				$res = false;

				if($consulta == '0' || $consulta == '3')
					$res = $model->grabar();
				else if($consulta == '2')
					$res = $model->borrar();

				if($res)
				{
					$this->extras['mensaje'] = 'Datos grabados correctamente';
					$this->extras['consulta'] = 1;
				}
				else $this->extras['consulta'] = $consulta;

			}
		}

		//carga del DataProvider
		$count = Yii::$app->db->createCommand("Select count(*) From v_plan_config_decaer")->queryScalar();

		$sql = 'Select tplan, tplan_nom, origen, origen_nom, tpago, tpago_nom, caja_id, caja_nom, cant_atras, cant_cons, modif From V_Plan_Config_Decaer Order By tplan_nom, origen_nom, tpago_nom, caja_nom';

		$this->extras['dataProvider'] = new SqlDataProvider([
			'sql' => $sql,
			'totalCount' => $count,

			'pagination' => false
		]);
		//fin carga del DataProvider

		return $this->render('configdecae', ['model' => $model, 'extras' => $this->extras, 'consulta' => $consulta]);
	}

	public function actionConfigusr($accion = 'index'){

		$model = new PlanConfigUsuario();
		$arreglo = 'get';

		if(Yii::$app->request->isPost)
			$arreglo = 'post';

		$filtroGrupo = 0;

		$cargarUsuarios = true;
		$usuarios = [];
		$planesAsignados = [];
		$planesSinAsignar = [];

		//grupos de usuarios
		$gruposUsuario = $model->getGruposUsuario();

		//usuario al que se le estan modificando los planes
		$usuarioActivo = Yii::$app->request->$arreglo('usuario', -1);

		if(Yii::$app->request->isGet){

			switch($accion){

				//cambia el usuario activo
				case 'cambioUsuario' :

							$usuarioActivo = Yii::$app->request->get('usuario', -1);

							break;

				//aplica los filtros a la lista de usuarios
				case 'filtros' :

							$filtroGrupo = Yii::$app->request->get('grupo', 0);

							//si no se establece un grupo de usuarios para filtrar, se elige el primer grupo del arreglo de grupos de usuarios
							if($filtroGrupo == 0)
								$filtroGrupo = key($gruposUsuario);

							$usuarios = $model->getUsuarios($filtroGrupo);

							$cargarUsuarios = false;
							break;
			}
		}
		else if(Yii::$app->request->isPost){

			$nuevosPlanes = Yii::$app->request->post('planes', []);

			$model->usr_id = $usuarioActivo;
			$model->nuevos = $nuevosPlanes;

			if(!$model->grabar())
				$this->extras['error'] = 'Ocurrio un error al intentar grabar los registros';
			else
				$this->extras['mensaje'] = $this->Mensajes(1);
		}

		if($usuarioActivo > 0){

			$planesAsignados = $model->getPlanesAsignados($usuarioActivo);
			$planesSinAsignar = $model->getPlanes($usuarioActivo);
		}


		if($cargarUsuarios)
			$usuarios = $model->getUsuarios(key($gruposUsuario));


		$this->extras['gruposUsuario'] = $gruposUsuario;
		$this->extras['dpUsuarios'] = new ArrayDataProvider(['allModels' => $usuarios, 'key' => 'usr_id']);
		$this->extras['dpPlanesSinAsignar'] = new ArrayDataProvider(['allModels' => $planesSinAsignar, 'key' => 'cod', 'pagination' => false]);
		$this->extras['dpPlanesAsignados'] = new ArrayDataProvider(['allModels' => $planesAsignados, 'key' => 'cod', 'pagination' => false]);

		return $this->render('configusuario', ['extras' => $this->extras]);
	}

	public function actionPlan( $id = 0, $consulta = 1, $m = 0 )
	{
		$error ='';
		$model = Plan::findOne($id);

		if ( $id != 0 && $model == null )
			Yii::$app->session->setFlash('mensaje', 'El Convenio ingresado no existe.' );

		if ($model == null) $model = new Plan;
		$modeldomi = Domi::cargarDomi('PLA', $model->obj_id,($model->plan_id == '' ? 0 : $model->plan_id));
		$model->capital = $model->nominal + $model->accesor + $model->multa;
		$model->CuotasPagas($id);
		$model->CuotasFalta($id);
		$cuotas = $model->CargarCuotas($id);
		$periodos = $model->CargarPeriodos($id);



		$session = new Session;
		$session->open();
		$error .= $session['error'];
		$session['error'] = '';
		$session->close();

		return $this->render('plan',['model' => $model,'modeldomi' => $modeldomi, 'cuotas' => $cuotas,'periodos' => $periodos,
					'consulta' => $consulta, 'error' => $error, 'mensaje' => isset(Yii::$app->session['mensaje']) ? Yii::$app->session->getFlash('mensaje') : $this->Mensajes($m)]);

	}

	public function actionBuscarplan()
  	{
      	if (isset($_POST['txNroConv']))
    	{
    		return $this->redirect(['plan', 'id' => $_POST['txNroConv']]);
		}else if (isset($_POST['txNroConvAnt']))
    	{
    		$plan = Plan::findOne(['planant' => $_POST['txNroConvAnt']]);
			if ($plan == null) $plan = new Plan();
			return $this->redirect(['plan', 'id' => $plan->plan_id]);
    	}else if (isset($_POST['txObj_Id'])){
    		$cont = utb::getCampo('plan',"obj_id='".$_POST['txObj_Id']."'",'count(*)');

    		if ($cont > 1){
    			$session = new Session;
				$session->open();
				$session['order'] = '';
				$session['by'] = '';
				$session['cond'] = "obj_id='".$_POST['txObj_Id']."'";
				$session['desc'] = "Objeto: ".$_POST['txObj_Id'];
				$session->close();

				return $this->redirect([
							'list_res'
							]);
    		}else if($cont == 1){
    			$plan = utb::getCampo('plan',"obj_id='".$_POST['txObj_Id']."'",'plan_id');
    			return $this->redirect(['plan', 'id' => $plan]);
    		}
    	}
    	return $this->redirect(['plan', 'm' => 7]);
   	}

   	public function actionPlannuevo($o = '')
   	{
   		$modeldomi = new Domi;
   		$model = new Plan;
		$model->obj_id= $o;

   		return $this->render('plannuevo',['modeldomi' =>$modeldomi, 'model' => $model]);
   	}

   	public function actionPlannuevoant()
   	{
   		$modeldomi = new Domi;
   		$model = new Plan;
   		return $this->render('plannuevoant',['modeldomi' =>$modeldomi, 'model' => $model]);
   	}

   	public function actionPlannuevograbar()
   	{
   		$model = new Plan;
   		$modeldomiplan = new Domi;

   		if (isset($_POST['arrayDomi'])) $modeldomiplan=unserialize(urldecode(stripslashes($_POST['arrayDomi'])));
   		$modeldomiplan->domicilio = (isset($_POST['txResDomi']) ? $_POST['txResDomi'] : $modeldomiplan->domicilio);

   		$model->tplan = (isset($_POST['dlTipo']) ? $_POST['dlTipo'] : 0);
   		$model->obj_id = (isset($_POST['txObj_Id']) ? $_POST['txObj_Id'] : "");
		$model->num = (isset($_POST['txContrib']) ? $_POST['txContrib'] : "");
   		$model->resp = (isset($_POST['txResNombre']) ? $_POST['txResNombre'] : "");
   		$model->resptdoc = (isset($_POST['dlResTDoc']) ? $_POST['dlResTDoc'] : 0);
   		$model->respndoc = (isset($_POST['txResNDoc']) ? $_POST['txResNDoc'] : 0);
   		$model->resptel = (isset($_POST['txResTel']) ? $_POST['txResTel'] : "");
   		$model->nominal = (isset($_POST['txNominal']) ? $_POST['txNominal'] : 0);
   		$model->accesor = (isset($_POST['txAccesor']) ? $_POST['txAccesor'] : 0);
   		$model->multa = (isset($_POST['txMulta']) ? $_POST['txMulta'] : 0);
   		$model->financia = (isset($_POST['txFinancia']) ? $_POST['txFinanca'] : 0);
   		$model->sellado = (isset($_POST['txSellado']) && $_POST['txSellado'] != '' ? $_POST['txSellado'] : 0);
   		$model->anticipo = (isset($_POST['txAnticipo']) ? $_POST['txAnticipo'] : 0);
        $model->origen = (isset($_POST['dlOrigen']) ? $_POST['dlOrigen'] : 0);
        $model->tpago = (isset($_POST['dlTPago']) ? $_POST['dlTPago'] : 0);
        $model->caja_id = (isset($_POST['dlCaja']) ? $_POST['dlCaja'] : 0);
        $tcaja = utb::getCampo('caja','caja_id='.$model->caja_id,'tipo');
   		$temple = (isset($_POST['dlEmp']) ? $_POST['dlEmp'] : 0);
   		$model->temple = (($temple == '' or $tcaja != 5) ? 0 : $temple);
        $model->bco_suc = (isset($_POST['txSuc']) ? $_POST['txSuc'] : 0);
        if ($tcaja == 5) $model->temple_area = (isset($_POST['txArea']) ? $_POST['txArea'] : "");
   		if ($tcaja == 3) $model->bco_suc = (isset($_POST['txSuc']) ? $_POST['txSuc'] : 0);
   		$model->bco_tcta = (isset($_POST['dlTCta']) && $_POST['dlTCta'] != "" ? $_POST['dlTCta'] : 0);
   		if ($tcaja == 3) $model->tpago_nro = (isset($_POST['txNCta']) ? $_POST['txNCta'] : $model->tpago_nro);
   		if ($tcaja == 4) $model->tpago_nro = (isset($_POST['txNroTarj']) ? $_POST['txNroTarj'] : $model->tpago_nro);
   		if ($tcaja == 5) $model->tpago_nro = (isset($_POST['txLegajo']) ? $_POST['txLegajo'] : $model->tpago_nro);
        $model->cuotas = (isset($_POST['txCantCuota']) ? $_POST['txCantCuota'] : 0);
        $model->montocuo = (isset($_POST['txValorPorCuota']) ? $_POST['txValorPorCuota'] : 0);

        $TipoPlan = (isset($_POST['arrayPlanConfig']) ? unserialize(urldecode(stripslashes($_POST['arrayPlanConfig']))) : 0);
        if ($TipoPlan == null)
        {
        	$Tipo = new PlanConfig();
    		$Tipo->cod = $model->tplan;
   			$TipoPlan = $Tipo->buscarUno();
        }

        $model->descnominal = ($TipoPlan->descnominal == "" ? 0 : $TipoPlan->descnominal);
        $model->descinteres = ($TipoPlan->descinteres == "" ? 0 : $TipoPlan->descinteres);
        $model->descmulta = ($TipoPlan->descmulta == "" ? 0 : $TipoPlan->descmulta);
        $model->interes = 0;
        $model->obs = (isset($_POST['txObs']) ? $_POST['txObs'] : '');
        $model->est = 1;
        $model->fchalta = date('d/m/Y');
        $model->fchconsolida = (isset($_POST['fchalta']) ? $_POST['fchalta'] : "");
        $model->planant = "";
        $model->tdistrib = (isset($_POST['dlTDistrib']) ? $_POST['dlTDistrib'] : 0);
		$model->distrib = (isset($_POST['dlDistrib']) ? $_POST['dlDistrib'] : 0);
        $model->judi_id = (isset($_POST['txJudiNum']) ? $_POST['txJudiNum'] : 0);

	   	$error = '';

   		$transaction = Yii::$app->db->beginTransaction();
   		try {
   			$error .= $model->Grabar();

   			$modeldomiplan->obj_id = $model->obj_id;
   			$modeldomiplan->id = $model->plan_id;
   			$modeldomiplan->torigen = 'PLA';
   			$error .= $modeldomiplan->grabar();

   			$cantper = 0;
   			if (isset($_POST['arrayPeriodo']))
   			{
   				$arrayper=unserialize(urldecode(stripslashes($_POST['arrayPeriodo'])));
   				$error .= $model->grabarperiodo($model->judi_id,$model->plan_id,$arrayper);
   				$cantper = count($arrayper);
   			}
   			$capital = (isset($_POST['txSubTotal']) ? $_POST['txSubTotal'] : 0);

   			$error .= $model->grabarcuotas($capital,$cantper,$model->cuotas,$model->fchconsolida,$model->tplan,$model->obj_id,
   							$model->plan_id,$model->anticipo,$TipoPlan->interes);


   			if ($model->origen == 3) $error .= $model->ActualizarPlaJudi($model->plan_id,$model->judi_id);

			if (trim($error) == "")
   			{
   				$transaction->commit();
   			}
 		} catch(\Exception $e) {
   			$transaction->rollBack();
	    	//throw $e;
	    	$error .= DBException::getMensaje($e);

   			return $this->render('plannuevo',
   						['modeldomi' =>$modeldomiplan, 'model' => $model,
							'error' => $error]);
		}

   		if (trim($error) == '')
   		{
   			return $this->redirect(['plan', 'id' => $model->plan_id, 'm' => 1]);
   		}

   	}

   	public function actionPlannuevoantgrabar()
   	{
   		$model = new Plan;
   		$modeldomiplan = new Domi;

   		if (isset($_POST['arrayDomi'])) $modeldomiplan=unserialize(urldecode(stripslashes($_POST['arrayDomi'])));
   		$modeldomiplan->domicilio = (isset($_POST['txResDomi']) ? $_POST['txResDomi'] : $modeldomiplan->domicilio);

   		$model->tplan = 0;
   		$model->obj_id = (isset($_POST['txObj_Id']) ? $_POST['txObj_Id'] : "");
   		$model->num = (isset($_POST['txContrib']) ? $_POST['txContrib'] : "");
   		$model->resp = (isset($_POST['txResNombre']) ? $_POST['txResNombre'] : "");
   		$model->resptdoc = (isset($_POST['dlResTDoc']) ? $_POST['dlResTDoc'] : 0);
   		$model->respndoc = (isset($_POST['txResNDoc']) ? $_POST['txResNDoc'] : 0);
   		$model->resptel = (isset($_POST['txResTel']) ? $_POST['txResTel'] : "");
   		$model->nominal = (isset($_POST['txNominal']) ? $_POST['txNominal'] : 0);
   		$model->accesor = (isset($_POST['txAccesor']) ? $_POST['txAccesor'] : 0);
   		$model->multa = (isset($_POST['txMulta']) ? $_POST['txMulta'] : 0);
   		$model->financia = 0;
   		$model->sellado = 0;
   		$model->anticipo = 0;
        $model->origen = (isset($_POST['dlOrigen']) ? $_POST['dlOrigen'] : 0);
        $model->tpago = (isset($_POST['dlTPago']) ? $_POST['dlTPago'] : 0);
        $model->caja_id = (isset($_POST['dlCaja']) ? $_POST['dlCaja'] : 0);
        $tcaja = utb::getCampo('caja','caja_id='.$model->caja_id,'tipo');
   		$temple = (isset($_POST['dlEmp']) ? $_POST['dlEmp'] : 0);
   		$model->temple = (($temple == '' or $tcaja != 5) ? 0 : $temple);
        $model->bco_suc = (isset($_POST['txSuc']) ? $_POST['txSuc'] : 0);
        if ($tcaja == 5) $model->temple_area = (isset($_POST['txArea']) ? $_POST['txArea'] : "");
   		if ($tcaja == 3) $model->bco_suc = (isset($_POST['txSuc']) ? $_POST['txSuc'] : 0);
   		$model->bco_tcta = (isset($_POST['dlTCta']) && $_POST['dlTCta'] != "" ? $_POST['dlTCta'] : 0);
   		if ($tcaja == 3) $model->tpago_nro = (isset($_POST['txNCta']) ? $_POST['txNCta'] : $model->tpago_nro);
   		if ($tcaja == 4) $model->tpago_nro = (isset($_POST['txNroTarj']) ? $_POST['txNroTarj'] : $model->tpago_nro);
   		if ($tcaja == 5) $model->tpago_nro = (isset($_POST['txLegajo']) ? $_POST['txLegajo'] : $model->tpago_nro);
        $model->cuotas = (isset($_POST['txCantCuota']) ? $_POST['txCantCuota'] : 0);
        $model->montocuo = 0;

        $TipoPlan = (isset($_POST['arrayPlanConfig']) ? unserialize(urldecode(stripslashes($_POST['arrayPlanConfig']))) : 0);
        $CuotasPlan = (isset($_POST['arrayCuotas']) ? unserialize(urldecode(stripslashes($_POST['arrayCuotas']))) : 0);
        if ($TipoPlan == null)
        {
        	$Tipo = new PlanConfig();
    		$Tipo->cod = $model->tplan;
   			$TipoPlan = $Tipo->buscarUno();
        }

        $quitaacc = (isset($_POST['txQuitaAcc']) ? $_POST['txQuitaAcc'] : 0);
   		$quitanom = (isset($_POST['txQuitaNom']) ? $_POST['txQuitaNom'] : 0);
   		$model->descnominal = ($quitanom != 0 ? $quitanom : ($TipoPlan->descnominal == '' ? 0 : $TipoPlan->descnominal));
        $model->descinteres = ($quitaacc != 0 ? $quitaacc : ($TipoPlan->descinteres == '' ? 0 : $TipoPlan->descinteres));
        $model->descmulta = ($TipoPlan->descmulta == "" ? 0 : $TipoPlan->descmulta);
        $model->interes = 0;
        $model->obs = (isset($_POST['txObs']) ? $_POST['txObs'] : '');
        $model->est = 1;
        $model->fchalta = date('d/m/Y');
        $model->fchconsolida = (isset($_POST['fchalta']) ? $_POST['fchalta'] : "");
        $model->planant = "";
        $model->distrib = (isset($_POST['dlDistrib']) ? $_POST['dlDistrib'] : 0);
        $model->judi_id = (isset($_POST['txJudiNum']) ? $_POST['txJudiNum'] : 0);

	   	$error = '';

   		$transaction = Yii::$app->db->beginTransaction();

		$totalapagar = 0;

   		try {
   			$error .= $model->Grabar();

   			$modeldomiplan->obj_id = $model->obj_id;
   			$modeldomiplan->id = $model->plan_id;
   			$modeldomiplan->torigen = 'PLA';
   			$error .= $modeldomiplan->grabar();

   			$cantper = 0;
   			if (isset($_POST['arrayPeriodo']))
   			{
   				$arrayper=unserialize(urldecode(stripslashes($_POST['arrayPeriodo'])));
   				$error .= $model->grabarperiodo($model->judi_id,$model->plan_id,$arrayper);
   				$cantper = count($arrayper);
   			}

   			$totalapagar = (isset($_POST['txTotalDeuda']) ? $_POST['txTotalDeuda'] : 0);

   			$error .= $model->grabarcuotasant($CuotasPlan,$model->plan_id,$model->obj_id,$totalapagar,$model->descnominal);


   			if ($model->origen == 3) $error .= $model->ActualizarPlaJudi($model->plan_id,$model->judi_id);

			if (trim($error) == "")
   			{
   				$transaction->commit();
   			}
 		} catch(\Exception $e) {
   			$transaction->rollBack();
	    	//throw $e;

	    	$error .= DBException::getMensaje($e);

   			return $this->render('plannuevoant',
   						['modeldomi' =>$modeldomiplan, 'model' => $model,'CuotasPlan' => $CuotasPlan,'totalapagar'=>$totalapagar,
							'cantcuotas'=>$model->cuotas, 'error' => $error]);
		}

   		if (trim($error) == '')
   		{
   			return $this->redirect(['plan', 'id' => $model->plan_id, 'm' => 1]);
   		}

   	}

   	public function actionBorrarplan($id)
   	{
   		$model = Plan::findOne($id);

   		$error = '';
   		$error = $model->BorrarPlan();

   		$session = new Session;
    	$session->open();
   		$session['error'] = $error;
   		//$session->close();

   		if ($error == '')
   		{
   			return $this->redirect(['plan', 'id' => $id, 'm' => 2]);
   		}else {
   			return $this->redirect(['plan', 'id' => $id]);
   		}
   	}

   	public function actionImputarplan($id)
   	{
   		$model = Plan::findOne($id);

   		$error = '';
   		$error = $model->ImputarPlan();

   		$session = new Session;
    	$session->open();
   		$session['error'] = $error;
   		//$session->close();

   		if ($error == '')
   		{
   			return $this->redirect(['plan', 'id' => $id, 'm' => 3]);
   		}else {
   			return $this->redirect(['plan', 'id' => $id]);
   		}
   	}

   	public function actionDecaerplan($id)
   	{
   		$model = Plan::findOne($id);

   		$error = '';
   		$error = $model->DecaerPlan();

   		$session = new Session;
    	$session->open();
   		$session['error'] = $error;
   		//$session->close();

   		if ($error == '')
   		{
   			return $this->redirect(['plan', 'id' => $id, 'm' => 4]);
   		}else {
   			return $this->redirect(['plan', 'id' => $id]);
   		}
   	}

   	public function actionAnulaimputadecaeplan($id)
   	{
   		$model = Plan::findOne($id);

   		$error = '';
   		$error = $model->AnulaImputaDecaePlan();

   		$session = new Session;
    	$session->open();
   		$session['error'] = $error;
   		//$session->close();

   		if ($error == '')
   		{
   			return $this->redirect(['plan', 'id' => $id, 'm' => 5]);
   		}else {
   			return $this->redirect(['plan', 'id' => $id]);
   		}
   	}

   	public function actionModificarplan($id)
   	{
   		$model = Plan::findOne($id);
   		$modeldomiplan = Domi::cargarDomi('PLA', $model->obj_id,($model->plan_id == '' ? 0 : $model->plan_id));

   		if (Yii::$app->request->post() != null)
   		{
   			if (isset($_POST['arrayDomi'])) $modeldomiplan=unserialize(urldecode(stripslashes($_POST['arrayDomi'])));

   			$model->tpago = (isset($_POST['dlTPago']) ? $_POST['dlTPago'] : $model->tpago);
   			if ($model->tpago == 3)
   			{
   				$model->caja_id = (isset($_POST['dlCaja']) ? $_POST['dlCaja'] : 0);
   				$tcaja = utb::getCampo('caja','caja_id='.$model->caja_id,'tipo');
   				$temple = (isset($_POST['dlEmp']) ? $_POST['dlEmp'] : 0);
   				$model->temple = (($temple == '' or $tcaja != 5) ? 0 : $temple);
   				if ($tcaja == 5) $model->temple_area = (isset($_POST['txArea']) ? $_POST['txArea'] : "");
   				if ($tcaja == 3) $model->bco_suc = (isset($_POST['txSuc']) ? $_POST['txSuc'] : 0);
   				$model->bco_tcta = (isset($_POST['dlTCta']) ? $_POST['dlTCta'] : 0);
   				if ($tcaja == 3) $model->tpago_nro = (isset($_POST['txNCta']) ? $_POST['txNCta'] : $model->tpago_nro);
   				if ($tcaja == 4) $model->tpago_nro = (isset($_POST['txNroTarj']) ? $_POST['txNroTarj'] : $model->tpago_nro);
   				if ($tcaja == 5) $model->tpago_nro = (isset($_POST['txLegajo']) ? $_POST['txLegajo'] : $model->tpago_nro);
   			}else {
   				$model->caja_id = 0;
   				$model->temple = 0;
   				$model->temple_area = "";
   				$model->bco_suc = 0;
   				$model->bco_tcta = 0;
   				$model->tpago_nro = "";
   			}

   			$model->resptdoc = (isset($_POST['dlResTDoc']) ? $_POST['dlResTDoc'] : $model->resptdoc);
   			$model->respndoc = (isset($_POST['txResNDoc']) ? $_POST['txResNDoc'] : $model->respndoc);
   			$model->resp = (isset($_POST['txResNombre']) ? $_POST['txResNombre'] : $model->resp);
   			$model->distrib = (isset($_POST['dlDistrib']) ? $_POST['dlDistrib'] : $model->distrib);
   			$modeldomiplan->domicilio = (isset($_POST['txResDomi']) ? $_POST['txResDomi'] : $modeldomiplan->domicilio);
   			$model->resptel = (isset($_POST['txResTel']) ? $_POST['txResTel'] : $model->resptel);

   			if ($model->resptdoc == '') $model->resptdoc = 0;
   			if ($model->respndoc == '') $model->respndoc = 0;
   			if ($model->distrib == '') $model->distrib = 0;

   			$error = '';

   			$transaction = Yii::$app->db->beginTransaction();
   			try {
   				$error .= $modeldomiplan->grabar();

   				$error .= $model->GrabarModifPlan();

   				if (trim($error) == "")
    			{
    				$transaction->commit();
    			}
   			} catch(\Exception $e) {
    			$transaction->rollBack();
			    //throw $e;
			    $error .= $e->getMessage();
			}

   			$session = new Session;
    		$session->open();
   			$session['error'] = $error;
   		//	$session->close();

   			if (trim($error) == '')
   			{
   				return $this->redirect(['plan', 'id' => $id, 'm' => 1]);
   			}else {
   				return $this->redirect(['plan', 'id' => $id, 'consulta' => 3]);
   			}

   		}else {
   			return $this->redirect(['plan', 'id' => $id, 'consulta' => 3]);
   		}
   	}

   	public function actionEliminaradelantacuota($id,$cuota)
   	{
   		$model = Plan::findOne($id);

   		$error = '';
   		$error = $model->EliminarAdelantaCuota($cuota);

   		$session = new Session;
    	$session->open();
   		$session['error'] = $error;
   		//$session->close();

   		if ($error == '')
   		{
   			return $this->redirect(['plan', 'id' => $id, 'm' => 6]);
   		}else {
   			return $this->redirect(['plan', 'id' => $id]);
   		}
   	}

   public function actionList_op()
    {
    	$cond='';
    	$desc ='';

		if (isset($_POST['txcriterio']) and $_POST['txcriterio']!=='') $cond=$_POST['txcriterio'];
		if (isset($_POST['txdescr']) and $_POST['txdescr']!=='') $desc=$_POST['txdescr'];

		if ($cond == '')
		{
			return $this->render('list_op');
		}else {
			$session = new Session;
			$session->open();
			$session['order'] = '';
			$session['by'] = '';
			$session['cond'] = $cond;
			$session['desc'] = $desc;
			$session->close();

			return $this->redirect([
							'list_res'
							]);
		}
    }

   public function actionList_res()
    {
    	$session = new Session;
    	$session->open();

    	return $this->render('list_res',['desc' => $session['desc']]);

    	$session->close();
    }

    public function actionImprimircontrato($id)
    {
    	Yii::$app->session['PlanContrato'] = null;
    	$error = "";
    	$datos = Plan::ImprimirContrato($id,$error);
    	$model = Plan::findOne($id);

    	//if (count($datos) <= 0) $error .= "<li>No hay Contrato para Imprimir</li>";
   		if (!isset($datos['detalle']) or $datos['detalle'] == "" or count($datos) <= 0) $error .= "<li>No hay Contrato para Imprimir</li>";
   		if ($model->est != '1') $error .= "<li>El Convenio debe estar vigente</li>";

   		Yii::$app->session['error'] = $error;

    	if ($error == '')
    	{
    		Yii::$app->session['PlanContrato'] = $datos;
    		$url = Yii::$app->request->baseUrl."/index.php?r=ctacte/convenio/imprimircontratopdf";
		 	echo "<script>window.open('".$url."','_blank');</script>";
		 	echo "<script>history.go(-1);</script>";
    	}else {
   			return $this->redirect(['plan', 'id' => $id]);
   		}
    }

    public function actionImprimircontratopdf()
    {
    	$datos = Yii::$app->session['PlanContrato'];
    	Yii::$app->session['PlanContrato'] = null;

    	$pdf = Yii::$app->pdf;
  		$pdf->content = $this->renderPartial('//reportes/contrato',
  							['condicion' => Yii::$app->param->muni_name,'usuario' => Yii::$app->user->id, 'datos' => $datos]);
    	return $pdf->render();
    }

    public function actionImprimircomprobantevalida($id,$desde=0,$hasta=0,$ext=0)
    {
    	$error = "";
    	$id = ($id != "" ? $id : 0);
    	$cuotadesde = (isset($_POST['txCtaDesde']) ? $_POST['txCtaDesde'] : $desde);
    	$cuotahasta = (isset($_POST['txCtaHasta']) ? $_POST['txCtaHasta'] : $hasta);

    	$error = Plan::ImprimirComprobanteValida($id, $cuotadesde,$cuotahasta);
    	$session = new Session;
    	$session->open();
   		$session['error'] = $error;
   		$session->close();

    	if ($error == "")
    	{
    		 if ($ext == 0) {
    		 	$url = Yii::$app->request->baseUrl."/index.php?r=ctacte/convenio/imprimircomprobante&id=".$id."&desde=".$cuotadesde."&hasta=".$cuotahasta;
    		 	echo "<script>window.open('".$url."','_blank');</script>";
    		 	echo "<script>history.go(-1);</script>";
    		 }else {
    		 	return $this->redirect(['imprimircomprobante', 'id' => $id,'desde'=>$cuotadesde,'hasta'=>$cuotahasta]);
    		 }
    	}else {
   			return $this->redirect(['plan', 'id' => $id]);
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
    	Plan::ImprimirComprobante($id, $desde,$hasta,$emision,$sub1,$sub2,$sub3,$sub4);

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

    public function actionImprimirresumen($id)
    {
    	$datos = Plan::ImprimirResumen($id,'');
    	$pdf = Yii::$app->pdf;
      	$pdf->content = $this->renderPartial('//reportes/planresumen',['datos' => $datos]);
        return $pdf->render();
    }

    public function actionImprimirpreliminarperidos()
    {
    	Yii::$app->session['titulo'] = "Convenio de Pago - Listado de Cuotas";
		Yii::$app->session['condicion'] = Yii::$app->session['PrelimPerCond'];
		Yii::$app->session['PrelimPerCond'] = null;
		Yii::$app->session['sql'] = Yii::$app->session['PrelimPer'];
		Yii::$app->session['proceso_asig'] = 3340;
		Yii::$app->session['columns'] = [
			['attribute'=>'cuota_nom','header' => 'Cuota','contentOptions'=>['style'=>'width:60px;text-align:center']],
    		['attribute'=>'capital','header' => 'Capital','contentOptions'=>['style'=>'width:90px;text-align:right']],
    		['attribute'=>'financia','header' => 'Financia','contentOptions'=>['style'=>'width:90px;text-align:right']],
    		['attribute'=>'sellado','header' => 'Sellado','contentOptions'=>['style'=>'width:90px;text-align:right']],
    		['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'width:90px;text-align:right']],
    		['attribute'=>'fchvenc','header' => 'Vencimiento','contentOptions'=>['style'=>'width:80px;text-align:center']],
    		['attribute'=>'est','header' => 'Est','contentOptions'=>['style'=>'width:50px;text-align:center']],

        ];

    	return $this->redirect(['//site/pdflist']);
    }

    public function actionImprimircuotasplanant()
    {
    	$pdf = Yii::$app->pdf;
      	$pdf->content = $this->renderPartial('//reportes/planantcuotas',[
				'datos' => Yii::$app->session['CuotasPlanAnt'],'condicion' => Yii::$app->session['CuotasPlanAntDesc']]);
      	Yii::$app->session['CuotasPlanAnt'] = null;
        return $pdf->render();
    }

	public function actionEditarvenccuota()
    {
		$ctacte_id = Yii::$app->request->post( '_cc', 0 );
		$plan_id = Yii::$app->request->post( '_p', 0 );
		$cuota = Yii::$app->request->post( '_c', 0 );
		$fchvenc = Yii::$app->request->post( '_fv', "" );

		$error = (new Plan)->ModificarVencCuota($ctacte_id,$fchvenc);

		if ($error == "")
			return $this->redirect(['plan', 'id' => $plan_id, 'm' => 1]);
		else
			return $error;
    }

   protected function Mensajes($id)
    {
    	switch ($id) {
    		case 1:
    			$mensaje = 'Datos Grabados';
    			break;
    		case 2:
    			$mensaje = 'El Convenio fue dado de baja';
    			break;
    		case 3:
    			$mensaje = 'El Convenio fue imputado';
    			break;
    		case 4:
    			$mensaje = 'El Convenio fue decaido';
    			break;
    		case 5:
    			$mensaje = 'Se anuló la imputación/decaimiento del Convenio';
    			break;
    		case 6:
    			$mensaje = 'Se eliminó el adelantamiento de Cuotas';
    		case 7:
    			$mensaje = 'No se encontraron resultados para la búsqueda';
    		default:
    			$mensaje = '';
    	}

    	return $mensaje;
    }

}
