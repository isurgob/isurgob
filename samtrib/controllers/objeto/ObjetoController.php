<?php

namespace app\controllers\objeto;
use yii\data\ArrayDataProvider;

use Yii;

use app\models\objeto\Persona;
use app\models\objeto\Inm;
use app\models\objeto\Comer;
use app\models\objeto\Rodado;
use app\models\objeto\Cem;
use app\models\objeto\Domi;
use app\models\objeto\Objeto;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\web\Session;


class ObjetoController extends \yii\web\Controller
{

    public function beforeAction($action)
	{
	    $operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if($operacion == 'objeto-objeto-denunciaimpositiva' or $operacion == 'objeto-objeto-imprimirdenunciaimpositiva') return true;
	    if (!in_array($operacion, ['objeto-objeto-view', 'objeto-objeto-sugerenciacalle', 'objeto-objeto-codigocalle', 'objeto-objeto-nombrecalle'])){
	    	if (!utb::getExisteAccion($operacion)) {
		        echo $this->render('//site/nopermitido');
		        return false;
		    }

		    // verifico permisos según estado
		    if ($operacion == 'objeto-objeto-estado'){
			    $tobj = utb::getTObj($_GET['id']);
			    $permitido = false;

			    if ($_GET['taccion'] == 3 or $_GET['taccion'] == 4){ // si es unificar o desunificar verifico que tenga permiso para esa accion según el tipo de objeto
			    	if ($tobj == 1) $permitido = utb::getExisteProceso(3082);
			    	if ($tobj == 2) $permitido = utb::getExisteProceso(3212);
			    	if ($tobj == 4) $permitido = utb::getExisteProceso(3241);
			    }
			    if ($_GET['taccion'] == 6){ // si es exento verifico que tenga permiso para esa accion según el tipo de objeto
			    	if ($tobj == 1) $permitido = utb::getExisteProceso(3083);
			    	if ($tobj == 2) $permitido = utb::getExisteProceso(3213);
			    	if ($tobj == 3) $permitido = utb::getExisteProceso(3228);
			    	if ($tobj == 4) $permitido = utb::getExisteProceso(3242);
			    	if ($tobj == 5) $permitido = utb::getExisteProceso(3288);
			    }

			    if (!$permitido){
			    	echo $this->render('//site/nopermitido');
			    	return false;
			    }
		    }

		    // si es consulta de miscelanea o vinculo verifico permiso según tipo de objeto
		    if ($operacion == 'objeto-objeto-miscelaneas' or $operacion == 'objeto-objeto-vinculos'){
		    	$tobj = utb::getTObj($_GET['id']);
		    	$permitido = false;
		    	if ($tobj == 1) $permitido = utb::getExisteProceso(3070);
		    	if ($tobj == 2) $permitido = utb::getExisteProceso(3200);
		    	if ($tobj == 3) $permitido = utb::getExisteProceso(3220);
		    	if ($tobj == 4) $permitido = utb::getExisteProceso(3230);
		    	if ($tobj == 5) $permitido = utb::getExisteProceso(3285);

		    	if (!$permitido){
			    	echo $this->render('//site/nopermitido');
			    	return false;
			    }
		    }

		    // si es abm de miscelanea, o activar verifico permiso según tipo de objeto
		    if ($operacion == 'objeto-objeto-miscelaneasabm' or $operacion == 'objeto-objeto-activar'){
		    	$tobj = utb::getTObj($_GET['id']);
		    	$permitido = false;
		    	if ($tobj == 1) $permitido = utb::getExisteProceso(3071);
		    	if ($tobj == 2) $permitido = utb::getExisteProceso(3202);
		    	if ($tobj == 3) $permitido = utb::getExisteProceso(3222);
		    	if ($tobj == 4) $permitido = utb::getExisteProceso(3232);
		    	if ($tobj == 5) $permitido = utb::getExisteProceso(3282);

		    	if (!$permitido){
			    	echo $this->render('//site/nopermitido');
			    	return false;
			    }
		    }

		    // si es historico verifico permiso según tipo de objeto
		    if ($operacion == 'objeto-objeto-historico'){
		    	$tobj = utb::getTObj($_GET['id']);
		    	$permitido = false;
		    	if ($tobj == 1) $permitido = utb::getExisteProceso(3077);
		    	if ($tobj == 2) $permitido = utb::getExisteProceso(3208);
		    	if ($tobj == 3) $permitido = utb::getExisteProceso(3604);
		    	if ($tobj == 4) $permitido = utb::getExisteProceso(3237);
		    	if ($tobj == 5) $permitido = utb::getExisteProceso(3284);

		    	if (!$permitido){
			    	echo $this->render('//site/nopermitido');
			    	return false;
			    }
		    }

		    // si es otras acciones verifico permiso según tipo de objeto
		    if ($operacion == 'objeto-objeto-accion'){
		    	$tobj = utb::getTObj($_GET['id']);
		    	$permitido = false;
		    	if ($tobj == 1) $permitido = utb::getExisteProceso(3105);
		    	if ($tobj == 2) $permitido = utb::getExisteProceso(3218);
		    	if ($tobj == 3) $permitido = utb::getExisteProceso(3601);
		    	if ($tobj == 4) $permitido = utb::getExisteProceso(3245);
		    	if ($tobj == 5) $permitido = utb::getExisteProceso(3290);

		    	if (!$permitido){
			    	echo $this->render('//site/nopermitido');
			    	return false;
			    }
		    }

		    // si es cambio de domicilio verifico permiso según tipo de objeto
		    if ($operacion == 'objeto-objeto-cambiodomi'){
		    	$tobj = utb::getTObj($_GET['id']);
		    	$permitido = false;

		    	if ($tobj == 1 and $_GET['taccion'] == 9) $permitido = utb::getExisteProceso(3076); //Cambio de domicilio Parcelario Inmueble
		    	if ($tobj == 1 and $_GET['taccion'] == 7) $permitido = utb::getExisteProceso(3075); //Cambio de domicilio Postal Inmueble
		    	if ($tobj == 2 and $_GET['taccion'] == 32) $permitido = utb::getExisteProceso(3202); //Cambio de domicilio Parcelario Comercio
		    	if ($tobj == 2 and $_GET['taccion'] == 7) $permitido = utb::getExisteProceso(3205); //Cambio de domicilio Parcelario Comercio
		    	if ($tobj == 3 and $_GET['taccion'] == 8) $permitido = utb::getExisteProceso(3603); //Cambio de domicilio Legal Persona
		    	if ($tobj == 3 and $_GET['taccion'] == 7) $permitido = utb::getExisteProceso(3602); //Cambio de domicilio Postal Persona
		    	if ($tobj == 4 and $_GET['taccion'] == 7) $permitido = utb::getExisteProceso(3243); //Cambio de domicilio Postal Cementerio
		    	if ($tobj == 5 and $_GET['taccion'] == 7) $permitido = utb::getExisteProceso(3289); //Cambio de domicilio Postal Rodado

		    	if (!$permitido){
			    	echo $this->render('//site/nopermitido');
			    	return false;
			    }
		    }

		    // si es transferencia verifico permiso según tipo de objeto
		    if ($operacion == 'objeto-objeto-transferencia'){
		    	$tobj = utb::getTObj($_GET['id']);
		    	$permitido = false;

		    	if ($tobj == 1) $permitido = utb::getExisteProceso(3104);
		    	if ($tobj == 2) $permitido = utb::getExisteProceso(3217);
		    	if ($tobj == 4) $permitido = utb::getExisteProceso(3244);
		    	if ($tobj == 5) $permitido = utb::getExisteProceso(3287);

		    	if (!$permitido){
			    	echo $this->render('//site/nopermitido');
			    	return false;
			    }
		    }

	    }

	    return true;
	}

    public function actionView($id, $m_text = '', $m = '')
    {
    	$tobj = utb::GetTObj($id);
    	$view = '';


    	switch($tobj){

    		case 1 : $view = 'objeto/inm/view'; break;
    		case 2 : $view = 'objeto/comer/view'; break;
    		case 3 : $view = 'objeto/persona/view'; break;
    		case 4 : $view = 'objeto/cem/view'; break;
    		case 5 : $view = 'objeto/rodado/view'; break;
    	}

    	if($view != '')
    		return $this->redirect([$view, 'id' => $id, 'm' => $m]);

    	echo "<script>history.go(-1)</script>";
    }

    public function actionMiscelaneas($id)
    {
    	if ($id == "") echo "<script>history.go(-1)</script>";

    	$dataprovider = Objeto::CargarMisc( $id );

    	if (isset($_POST['txAccion']))
    	{
    		$error = "";
    		$accion = $_POST['txAccion'];
    		$titulo = $_POST['txTitulo'];
    		$detalle = $_POST['txDetalle'];
    		$orden = isset($_POST['txOrden']) ? $_POST['txOrden'] : 0;

    		if ($accion==0) $error = Objeto::NuevaMisc($id, $titulo, $detalle);
    		if ($accion==3) $error = Objeto::ModificarMisc($id, $orden, $titulo, $detalle);

    		if ($error !== "")
    		{
    			return $this->render('//objeto/objetomisc',
    				[
						'dataprovider' => $dataprovider,
						'id' => $id, 'error' => $error, 'consulta' => 0
					]);
    		}else{
    			return $this->render('//objeto/objetomisc',
    					['dataprovider' => $dataprovider, 'id' => $id, 'mensaje' => $this->mensajes(1)]);
    		}
    	}

    	return $this->render('//objeto/objetomisc', ['dataprovider' => $dataprovider, 'id' => $id]);
    }

//    public function actionMiscelaneasabm($id)
//    {
//    	$dataprovider = Objeto::CargarMisc($id);
//
//    	if (isset($_POST['txAccion']))
//    	{
//    		$error = "";
//    		$accion = $_POST['txAccion'];
//    		$titulo = $_POST['txTitulo'];
//    		$detalle = $_POST['txDetalle'];
//    		$orden = isset($_POST['txOrden']) ? $_POST['txOrden'] : 0;
//
//    		if ($accion==0) $error = Objeto::NuevaMisc($id, $titulo, $detalle);
//    		if ($accion==3) $error = Objeto::ModificarMisc($id, $orden, $titulo, $detalle);
//
//    		if ($error !== "")
//    		{
//    			return $this->render('//objeto/objetomisc',
//    				[
//						'dataprovider' => $dataprovider,
//						'id' => $id, 'error' => $error, 'consulta' => 0
//					]);
//    		}else{
//    			return $this->render('//objeto/objetomisc',
//    					['dataprovider' => $dataprovider, 'id' => $id, 'mensaje' => $this->mensajes(1)]);
//    		}
//    	}
//
//    	return $this->redirect(['miscelaneas', 'id' => $id]);
//    }

    public function actionActivar($id)
    {
        $model = $this->ModelTObj($id);
        $modelobjeto = (new Objeto())->cargarObjeto($id);
        $modelodomipost = Domi::cargarDomi('OBJ', $id, 0);
        $modelodomileg = Domi::cargarDomi('PLE', $id, 0);
       	$modelodomires = Domi::cargarDomi('PRE', $id, 0);

        $error = "";
        $error .= $modelobjeto->activarObjeto();

        if ($error == "")
        {
        	return $this->redirect([$this->DirecUrl($id).'view', 'id' => $modelobjeto->obj_id, 'm' => 2]);

        }else{

        	return $this->render($this->DirecUrl($id).'view', [
        		'model' => $model, 'modelobjeto' => $modelobjeto,
				'modelodomipost' => $modelodomipost, 'modelodomileg' => $modelodomileg, 'modelodomires' => $modelodomires,
				'consulta' => 1, 'error' => $error
        	]);
        }
    }

    public function actionEstado($id, $taccion, $accion=0)
    {
    	/**
		 * $taccion = 6 => Exento
		 * $taccion = 3 => Unifica
		 * $taccoin = 4 => Desunifica
		 */

        $modelobjeto = (new Objeto)->cargarObjeto($id);

        if ($accion == 1)
        {
        	$error = "";
        	$obj_unif = "";
        	$expe = "";
        	$obs = "";

        	if (isset($_POST['expe']) and $_POST['expe']!=='') $expe=$_POST['expe'];
			if (isset($_POST['obs']) and $_POST['obs']!=='') $obs=$_POST['obs'];
			if (isset($_POST['obj_unif']) and $_POST['obj_unif']!=='') $obj_unif=$_POST['obj_unif'];

        	$error .= $modelobjeto->NuevaAccion($taccion,date("d/m/Y"),"","",$expe,"","",$obs,$obj_unif);

        	if ($error == "")
        	{
        		return $this->redirect(['view', 'id' => $modelobjeto->obj_id, 'm' => 1]);
        	}else{
        		return $this->render('//objeto/objetoestado', ['taccion' => $taccion, 'id' => $id,'error' => $error]);
        	}

        } else {
        	return $this->render('//objeto/objetoestado', ['taccion' => $taccion, 'id' => $id]);
        }
    }

    public function actionAccion($id, $accion= '')
    {
    	$model = Objeto::findOne($id);

    	if($model === null) $model = new Objeto();

        $tobj = utb::GetTObj($id);
        $est = utb::getCampo("v_objeto","obj_id='$id'","est");
        $cant = utb::getCampo("objeto_taccion",'tobj='.$tobj." and estactual like '%$est%' and interno='N'","count(*)");

        if ($cant == 0) return $this->redirect([$this->DirecUrl($id).'view', 'id' => $id, 'm' => 3]);

        if(Yii::$app->request->isPost){

			$fecha = trim(Yii::$app->request->post('fecha', ''));

			if($fecha == '' || strlen($fecha) < 10) $model->addError('obj_id', 'Ingrese una fecha');
			else{

				$fecha = Fecha::usuarioToBD($fecha);
				$taccion = Yii::$app->request->post('dlTipo', -1);
				$fchVenc = trim(Yii::$app->request->post('fchVenc', null));
				$fchDesde = trim(Yii::$app->request->post('fchDesde', null));
				$fchHasta = trim(Yii::$app->request->post('fchHasta', null));
				$expe = trim(Yii::$app->request->post('expe', ''));
				$obs = trim(Yii::$app->request->post('obs', ''));

				$res = $model->NuevaAccion($taccion, $fecha, $fchDesde, $fchHasta, $expe, '', '', $obs, '', $fchVenc);

				if($res == '') return $this->redirect(['view', 'id' => $model->obj_id, 'm' => 1]);

				$model->addError('obj_id', $res);
			}
        }

        return $this->render('//objeto/objetoaccion', ['model' => $model, 'accion' => $accion, 'tobj' => $tobj, 'est' => $est]);
    }

    public function actionVinculos($id)
    {
    	$baja = filter_var(Yii::$app->request->post('baja', false), FILTER_VALIDATE_BOOLEAN);;
		$dataprovider = Objeto::CargarVinculos($id,$baja);

    	return $this->render('//objeto/objetovinculos', ['dataprovider' => $dataprovider, 'id' => $id,'baja' => $baja]);
    }

    public function actionHistorico($id)
    {

    	$dataproviderdom = new ArrayDataProvider([]);
    	$dataprovidertit = new ArrayDataProvider([]);
    	$dataprovideraval = new ArrayDataProvider([]);
    	$dataproviderfall = new ArrayDataProvider([]);

    	$tipo = utb::getTObj( $id );

    	$dataproviderdom = Objeto::CargarHistorico($id, 'DOM');

    	if ( $tipo == 1 ) // Inmueble
    	{
	    	$dataprovidertit = Objeto::CargarHistorico($id, 'TIT');
	    	$dataprovideraval = Objeto::CargarHistorico($id, 'AVAL');
    	}

	    if ( $tipo != 3 )
	    {
	    	$dataprovidertit = Objeto::CargarHistorico($id, 'TIT');
	    }

	    if ( $tipo == 4 )	//Fallecido
	    {
	    	$dataproviderfall = Objeto::CargarHistorico($id, 'FALL');
	    }


    	return $this->render('//objeto/objetohistorico',
    				['dataproviderdom' => $dataproviderdom, 'dataprovidertit' => $dataprovidertit,
						'dataprovideraval' => $dataprovideraval, 'dataproviderfall' => $dataproviderfall, 'id' => $id]);
    }

    /**
     * Función que gestiona las modificaciones de los domicilios
     */
    public function actionCambiodomi($id, $taccion)
    {
    	$modelodomipar = Domi::cargarDomi('INM', $id, 0);
        $modelodomipost = Domi::cargarDomi('OBJ', $id, 0);
        $modelObjeto = (new Objeto())->cargarObjeto($id);

        $session = new Session();

        if (!isset($session['domic']))
		{
			$session['domic'] = '';
			$session['banderaDomic'] = 0; // Bandera utilizada para la variable en sesión.
		}

		if (isset($session['banderaDomic']) && $session['banderaDomic'] == 1)
		{
			$session['domic'] = '';
			$session['banderaDomic'] = 0;
		}


    	if ($taccion == 9) //Cambio de domicilio Parcelario
    	{
    		if ($session['domic'] == '') $session['domic'] = $modelodomipar;
    		return $this->render('//objeto/objetocambiodomicilio', ['domi' => $modelodomipar, 'taccion' => $taccion, 'modelObjeto' => $modelObjeto, 'domiAnt' => $modelodomipar->domicilio, 'tor' => 'INM']);
    	}

    	if ($taccion == 7) //Cambio de domicilio Postal
    	{
    		if ($session['domic'] == '') $session['domic'] = $modelodomipost;
    		return $this->render('//objeto/objetocambiodomicilio', ['domi' => $modelodomipost, 'taccion' => $taccion, 'modelObjeto' => $modelObjeto, 'domiAnt' => $modelodomipost->domicilio, 'tor' => 'OBJ']);
    	}

    	if($taccion == 32){//cambio de domicilio parcelario para comercio

    		$modelodomipar = Domi::cargarDomi('COM', $id, 0);
			if ($modelodomipar == null) $modelodomipar = new Domi();

    		if($session['domic'] == '') $session['domic'] = $modelodomipar;
    		return $this->render('//objeto/objetocambiodomicilio', ['domi' => $modelodomipar, 'taccion' => $taccion, 'modelObjeto' => $modelObjeto,
					'domiAnt' => $modelodomipar->domicilio, 'tor' => 'COM']);
    	}

    	$session->close();
    }

    /**
     * Función que permite gestionar las transferencias
     * @param string $id Identificador del inmueble seleccionado
     */
    public function actionTransferencia($id, $taccion, $b = null)
    {
    	$model = null;
    	$modelObjeto = (new Objeto())->cargarObjeto($id);
    	$modelDomicilio = (new Domi())->cargarDomi('OBJ', $modelObjeto->obj_id, 0);
    	$tobj;
    	$letra = '';

    	$session = new Session;
    	$session->open();

		//El parametro $b establece que se deben borrar los datos de session al tratarse de un nuevo acceso a la vista
		if($b !== null){
    		$session->set('arregloTitularesTransferencia', []);
    		$session->set('arregloTitularesDenunciaImpositiva', []);
    		$session->set('arregloTitulares', []);

    		$session->close();

    		return $this->redirect(['transferencia', 'id' => $id, 'taccion' => $taccion]);
    	}

		//se obtienen los titulares actuales
		$modelObjeto->obtenerTitularesDeBD($id);

		//se establece la bandera para indicar que se esta realizando una transferencia
   		$session->set('banderaTitularesTransferencia', 1);
   		$session->set('banderaTitularesDenunciaImpositiva', 0);

		//se obtienen los titulares de transferencia guardados en session
    	$modelObjeto->arregloTitularesTransferencia = $session->get('arregloTitularesTransferencia', []);

    	$session->close();

    	//si se cargo el Objeto, se obtiene la letra a partir de obj_id y se determina que modelo cargar
    	if($modelObjeto->obj_id !== null && trim($modelObjeto->obj_id)!=''){

    		$letra = strtoupper(substr($modelObjeto->obj_id, 0, 1));
    		$tobj = intval(utb::getTObj($modelObjeto->obj_id));
    		$esperado = '';

    		switch($letra){

    			case 'I':
    				$model = Inm::findOne($id);
    				if($model == null) $model = new Inm();
    				break;

    			case 'C':
    				$model = Comer::findOne($id);
    				if($model == null) $model = new Comer();
    				break;

    			case 'P':
    				$model = Persona::findOne($id);
    				if($model == null) $model = new Persona();
    				break;

    			case 'E':
    				$model = Cem::findOne($id);
    				if($model == null) $model = new Cem();
    				break;

    			case 'R':
    				$model = Rodado::findOne($id);
    				if($model == null) $model = new Rodado();
    				break;
    		}
    	}

    	//si la consulta viene por post y se obtiene el parametro 'grabar' con un valor distinto a -1
    	if(Yii::$app->request->isPost && intval(Yii::$app->request->post('grabar', -1)) !== -1)
    	{

    		$grabar = true;

    		if(!$model->load(Yii::$app->request->post()) || !$modelObjeto->load(Yii::$app->request->post())) $grabar = false;

    		//se carga el nuevo domicilio. Si no se ingresa nada se graba el mismo que antes
    		if(isset($_POST['arrayDomicilio'])){

    			$modelDomicilio =  unserialize( urldecode( stripslashes( $_POST['arrayDomicilio'] ) ) );

                if( $modelDomicilio->validate() ){

                    //se graba el domicilio
        			$res = $modelDomicilio->grabar();

        			if($res != ''){
        				$grabar = false;
        				$modelObjeto->addError('obj_id', $res);
        			}

                } else {
                    $grabar = false;
                    $modelObjeto->addError('obj_id', 'Ocurrió un error al intentar grabar el domicilio.');
                }

    		}

    		$tobj = intval($modelObjeto->tobj);
    		$extras = [];

    		switch($tobj){

    			//inmueble
    			case 1:

    				$extras['tmatric'] = $model->tmatric;//Yii::$app->request->post('tmatric', '');
    				$extras['matric'] = $model->matric;//Yii::$app->request->post('matric', '');
    				$extras['fchmatric'] = $model->fchmatric;//Yii::$app->request->post('fchmatric', null);
    				$extras['anio'] = $model->anio;//Yii::$app->request->post('anio', 0);

    				break;

    			//comercio
    			case 2:
    			//persona
    			case 3:
    			//cementerio
    			case 4:
    			//rodado
    			case 5:
    		}

    		if($grabar){

    			$expe = trim(Yii::$app->request->post('expe', ''));
    			$obs = trim(Yii::$app->request->post('obs', ''));

    			$session = new Session();
    			$session->open();

    			$nuevos = $session->get('arregloTitularesTransferencia', []);

    			$res = $modelObjeto->cambiarTitulares($taccion, $nuevos, $expe, $obs, $extras);

				//no hay error
    			if($res == ''){

    				$session->set('arregloTitulares', $session->get('arregloTitularesTransferencia', []));
    				$session->set('arregloTitularesTransferencia', []);

    				$session->close();

    				return $this->redirect(['view', 'id' => $modelObjeto->obj_id, 'm' => '1']);
    			}

    			$session->close();

    			//el error de grabar devuelto por el modelo se almacena en el modelo de objeto
    			$modelObjeto->addError('obj_id', $res);
    		}

    	}

        return $this->render('//objeto/objetotransferencia',
        					['model' => $model,
							'modelDomicilio' => $modelDomicilio,
							'modelObjeto' => $modelObjeto,
							'taccion' => $taccion,
							'letra' => $letra,
							'tobj' => $tobj,
							'consulta' => 0,
							'expe' => '',
							'obs' => '',
							'poseeDeuda' => $modelObjeto->poseeDeuda($modelObjeto->obj_id)
							]);
    }


    public function actionDenunciaimpositiva($id, $b= null){

    	$modelObjeto= (new Objeto())->cargarObjeto($id);
    	$modelDomicilio= (new Domi())->cargarDomi('OBJ', $modelObjeto->obj_id, 0);
    	$poseeDeuda= false;
    	$grabar= false;

    	$session = new Session;
    	$session->open();

		//El parametro $b establece que se deben borrar los datos de session al tratarse de un nuevo acceso a la vista
		if($b !== null){
    		$session->set('arregloTitularesTransferencia', []);
    		$session->set('arregloTitularesDenunciaImpositiva', []);
    		$session->set('arregloTitulares', []);

    		$session->close();

    		return $this->redirect(['denunciaimpositiva', 'id' => $id]);
    	}

		//se obtienen los titulares actuales
		$modelObjeto->obtenerTitularesDeBD($id);
		$modelObjeto->arregloTitularesDenunciaImpositiva= $session->get('arregloTitularesDenunciaImpositiva', []);

		/*
		Descomentar si se quiere que se precarguen los nuevos titulares con los titulares actuales
		if(count($modelObjeto->arregloTitularesDenunciaImpositiva) == 0){
			$modelObjeto->arregloTitularesDenunciaImpositiva= $modelObjeto->arregloTitulares;
			$session->set('arregloTitularesDenunciaImpositiva', $modelObjeto->arregloTitularesDenunciaImpositiva);
		}
		*/

		//se establece la bandera para indicar que se esta realizando una transferencia
   		$session->set('banderaTitularesTransferencia', 0);
   		$session->set('banderaTitularesDenunciaImpositiva', 1);

		//se obtienen los titulares de transferencia guardados en session
    	$modelObjeto->arregloTitularesDenunciaImpositiva = $session->get('arregloTitularesDenunciaImpositiva', []);

    	$session->close();

    	//si la consulta viene por post y se obtiene el parametro 'grabar' con un valor distinto a -1
    	if(Yii::$app->request->isPost && intval(Yii::$app->request->post('grabar', -1)) !== -1){

    		$grabar = true;
    		$arreglo= Yii::$app->request->post();

    		if(!$modelObjeto->load($arreglo)) $grabar = false;

    		//se carga el nuevo domicilio. Si no se ingresa nada se graba el mismo que antes
    		if(isset($_POST['arrayDomicilio'])){

    			$modelDomicilio =  unserialize( urldecode( stripslashes( $_POST['arrayDomicilio'] ) ) );

    			//se graba el domicilio
    			$res = $modelDomicilio->grabar();

    			if($res != ''){
    				$grabar = false;
    				$modelObjeto->addError('obj_id', $res);
    			}
    		}

    		$tobj = intval($modelObjeto->tobj);

    		if($grabar){

    			$session = new Session();
    			$session->open();

    			$nuevos = $session->get('arregloTitularesDenunciaImpositiva', []);

    			$res = $modelObjeto->grabarDenunciaImpositiva();

				//no hay error
    			if($res == ''){

    				$session->set('arregloTitulares', $session->get('arregloTitularesDenunciaImpositiva', []));
    				$session->set('arregloTitularesDenunciaImpositiva', []);

    				$session->close();

    				return $this->redirect(['view', 'id' => $modelObjeto->obj_id, 'm' => '1']);
    			}

    			$session->close();

    			//el error de grabar devuelto por el modelo se almacena en el modelo de objeto
    			$modelObjeto->addError('obj_id', $res);
    		}

    	}

    	return $this->render('//objeto/objetodenunciaimpositiva',
    					[
    					'modelObjeto' => $modelObjeto,
    					'modelDomicilio' => $modelDomicilio,
    					'poseeDeuda' => $poseeDeuda
    					]);
    }

	public function actionImprimirdenunciaimpositiva($id)
    {
    	$modelObjeto= (new Objeto())->cargarObjeto($id);
    	$modelDomicilio= (new Domi())->cargarDomi('OBJ', $modelObjeto->obj_id, 0);

		$session = new Session;
    	$session->open();

		//se obtienen los titulares actuales
		$modelObjeto->obtenerTitularesDeBD($id);
		$modelObjeto->arregloTitularesDenunciaImpositiva= $session->get('arregloTitularesDenunciaImpositiva', []);

		$session->close();

    	$pdf = Yii::$app->pdf;
		$pdf->marginTop = "30px";
		$pdf->content = $this->renderPartial('//reportes/denunciaimpositiva',['modelObjeto' => $modelObjeto]);

        return $pdf->render();
    }

    /**
    * Busca las calles que coincidan con $term
    *
    */
    public function actionSugerenciacalle($term = ''){


    	$ret= [];

    	if($term == '') return json_encode($ret);

    	$ret= utb::getAux('domi_calle', 'nombre', 'nombre', 0, "upper(nombre) Like upper('%$term%')");

    	if($ret === false) $ret= [];
    	return json_encode($ret);
    }

    /**
    * Busca el codigo de la calle que coincide con $nombre
    */
    public function actionCodigocalle($nombre= ""){

    	$ret= utb::getCampo('domi_calle', "nombre = '$nombre'", 'calle_id');
    	return $ret;
    }
	
	public function actionNombrecalle($cod = 0){

    	$ret= utb::getCampo('domi_calle', "calle_id = $cod", 'nombre');
    	return $ret;
    }

	public function actionCertificadobaja($id)
	{
		$model = $this->ModelTObj($id);
        $modelobjeto = (new Objeto())->cargarObjeto($id);

		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/objeto_cert_baja',['model' => $model,'modelobjeto'=>$modelobjeto]);

        return $pdf->render();
	}



   protected function mensajes($id)
    {
    	switch ($id) {
    		case 1:
    			$mensaje = 'Datos grabados correctamente';
    			break;
    		case 2:
    			$mensaje = 'Se ha vuelto a Activar el Objeto.';
    			break;
    		case 3:
    			$mensaje = 'No existen Otras Acciones para el Estado Actual del Objeto.';
    			break;
    		default:
    			$mensaje = '';
    	}

    	return $mensaje;
    }

		protected function DirecUrl($id)
	{
		$tobj = utb::GetTObj($id);

		if($tobj == 2) return 'objeto/comer/';

    	if ($tobj == 3)
    	{
    		return 'objeto/persona/';
    	}

		if ($tobj == 1)
			return 'objeto/inm/';

		if ($tobj == 4)
			return 'objeto/cem/';
	}

	protected function ModelTObj($id)
	{
		$tobj = utb::GetTObj($id);

    	if ($tobj == 1)
    	{
    		return InmController::findModel($id);
    	}
		if ($tobj == 2)
    	{
    		return Comer::findOne($id);
    	}
		if ($tobj == 3)
    	{
    		return PersonaController::findModel($id);
    	}
		if ($tobj == 4)
    	{
    		return Cem::findOne($id);
    	}
		if ($tobj == 5)
    	{
    		return Rodado::findOne($id);
    	}
	}

	/**
	 * Mensajes para cualquier tipo de objeto
	 * Los codigos van del 1 al 10
	 *
	 * @param integer $codigo - Codigo del mensaje
	 *
	 * @return string = null - Mensaje a mostrar al usuario. null se retorna si no existe mensaje para el codigo provisto
	 */
	public static function mensajesGenerales($codigo){

		switch($codigo){

			case 1: return 'Datos grabados correctamente.';
			case 2: return 'Se ha vuelto a Activar el Objeto.';
			case 3: return 'No existen Otras Acciones para el Estado Actual del Objeto.';
		}

		return null;
	}
}
