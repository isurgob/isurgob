<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Liquida;
use app\models\ctacte\Ctacte;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\utils\db\utb;

/**
 * LiquidaController implements the CRUD actions for Liquida model.
 */
class LiquidaController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','get'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action){
    
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
	    
	    return true;
    }

  
    /**
     * Displays a single Liquida model.
     * @param integer $ctacte_id
     * @return mixed
     */
    public function actionView($id=0)
    {
        unset(Yii::$app->session['liq']);  
		
		$arrayCtaCte = Ctacte::findOne(['ctacte_id' => $id]);
		if ($arrayCtaCte==null) $arrayCtaCte = new CtaCte;
		
		$arrayCtaCte['est'] = utb::getCampo("ctacte","ctacte_id=".$id,"est");
		
		if (($arrayCtaCte == null || (count($arrayCtaCte)==0 and $id !=0)) && $id > 0) Yii::$app->session->setFlash('info', 'No se encontraron resultados para la búsqueda.' );
		
		$dataProviderItem = Liquida::BuscarItem($id);
		$total= (new Liquida)->GetTotalItems(0,$id);
		
		return $this->render('view',[
				'arrayCtaCte' => $arrayCtaCte,
				'dataProviderItem' => $dataProviderItem,
				'consulta' => 1,
				'total' => $total
			]);
    }

    /**
     * Creates a new Liquida model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	$model= new Liquida();
		$error = "";
		
		$ttrib = 0;
		$ucm = 0;
		$uso_subcta = 0;
		$param1 = '';
		$monto = 0;
		$item_id = 0;
		$erroritem = '';
		$valor_mm = 0;
		$total = 0;
		
		$arrayCtaCte = new CtaCte;
		$dataProviderItem = $model->BuscarItem(0);
		if (!isset(Yii::$app->session['liq'])) Yii::$app->session['liq'] = $model->GetLiqId();
		
		$arrayCtaCte['anio'] = date('Y');
		$arrayCtaCte['cuota'] = 0;
		$arrayCtaCte['fchvenc'] = date('d/m/Y');
		
		$ItemDef = $model->CargarItem(0,0,0,0);
		$ItemUno = $model->ConsultarItemUno(0,0,0);
		
		if (isset($_POST['txCtaCte_Id'])) 
        {
            $arrayCtaCte['trib_id'] = (isset($_POST['dlTributo']) ? $_POST['dlTributo'] : 0);
            $arrayCtaCte['obj_id'] = (isset($_POST['txObj_Id']) ? $_POST['txObj_Id'] : '');
            $arrayCtaCte['subcta'] = (isset($_POST['txSubCta']) && $_POST['txSubCta'] != '' ? $_POST['txSubCta'] : 0);
            $arrayCtaCte['anio'] = (isset($_POST['txAnio']) ? $_POST['txAnio'] : 0);
            $arrayCtaCte['cuota'] = (isset($_POST['txCuota']) ? $_POST['txCuota'] : 0);
            $arrayCtaCte['fchvenc'] = (isset($_POST['fchvenc']) ? $_POST['fchvenc'] : '');
            $arrayCtaCte['expe'] = (isset($_POST['txExpe']) ? $_POST['txExpe'] : '');
            $arrayCtaCte['obs'] = (isset($_POST['txObs']) ? $_POST['txObs'] : '');
            $ucm = $model->getUCM($arrayCtaCte['trib_id']);//(isset($_POST['opUCM']) ? $_POST['opUCM'] : 1);

            $ctacte = 0;
            $error = $model->GrabarLiquida($ctacte,"nuevo",Yii::$app->session['liq'],$arrayCtaCte['trib_id'],$arrayCtaCte['obj_id'],$arrayCtaCte['subcta'],$arrayCtaCte['anio'],$arrayCtaCte['cuota'],$arrayCtaCte['fchvenc'],$arrayCtaCte['expe'],$arrayCtaCte['obs'],$ucm == '' ? 0 : $ucm);
			
			if ($error == ""){
            	Yii::$app->session->setFlash('mensaje', 'Los datos se grabaron correctamente.' );
				return $this->redirect(['view', 'id' => $ctacte]);
			}else {
				$dataProviderItem = $model->ConsultarItem(Yii::$app->session['liq'],$arrayCtaCte['fchvenc'],1);
				$total= $model->GetTotalItems(Yii::$app->session['liq'],$ctacte) * $model->CalcularMM($arrayCtaCte['fchvenc'],$arrayCtaCte['trib_id']);
				$ucm = $model->GetUCM($arrayCtaCte['trib_id']);	
			}	
			
		}
		
		if (isset($_POST['pjTribSelec']))
		{
			$model->BorrarItemLiquida(Yii::$app->session['liq']);
			
			$arrayCtaCte['trib_id'] = $_POST['trib_id'];
				
			$ttrib = utb::getTTrib($arrayCtaCte['trib_id']);
			$arrayCtaCte['tobj'] = utb::getTObjTrib($arrayCtaCte['trib_id']); 
			if ($ttrib == 3 and $arrayCtaCte['tobj'] == 1) $arrayCtaCte['fchvenc'] = date('d/m/Y');
			
				
			//se cambia el valor del radio de ucm
			$ucm = $model->GetUCM($arrayCtaCte['trib_id']);	
			$uso_subcta = utb::getCampo("trib","trib_id=".$arrayCtaCte['trib_id'],"uso_subcta");
			if ($uso_subcta == 1) 
				$arrayCtaCte['subcta'] = "";
			else
				$arrayCtaCte['subcta'] = 0;
		}
		
		if (isset($_POST['pjObjNom']))
		{
			$trib = $_POST['trib_id'];
			$obj_id = $_POST['obj_id'];
			if (strlen($obj_id) < 8) $obj_id = utb::GetObjeto((int)utb::GetTObjTrib($trib),(int)$obj_id);
			if (utb::getTObj($obj_id) == utb::getTObjTrib($trib))
				$arrayCtaCte['obj_id'] = $obj_id;
			else
				$arrayCtaCte['obj_id'] = "";
			$arrayCtaCte['subcta'] = ($_POST['subcta'] == '' ? 0 : $_POST['subcta']);
			$arrayCtaCte['trib_id'] = $trib;
		}
		if (isset($_POST['pjGetPeriodo']))
		{
			$trib = $_POST['trib'];
    		$obj = $_POST['obj'];
    		$subcta = $_POST['subcta'];
    		$arrayCtaCte['anio'] = $_POST['anio'];
			$arrayCtaCte['fchvenc'] = $_POST['venc'];
			$ttrib = utb::getTTrib($trib);
    		
    		$arrayCtaCte['cuota'] = $model->GetPeriodo($trib,$obj,$subcta,$arrayCtaCte['anio']); 
			
			if ($trib == 4 and $arrayCtaCte['cuota'] !==0) 
			{ 
				$arrayCtaCte['fchvenc'] = $model->GetFechaVenc($trib,$arrayCtaCte['anio'],$arrayCtaCte['cuota']);	
			}
		}
		if (isset($_POST['pjBtnItem']))
		{
			$arrayCtaCte['trib_id'] = $_POST['trib'];
			$arrayCtaCte['anio'] = $_POST['anio'];
			$arrayCtaCte['cuota'] = $_POST['cuota'];
			$arrayCtaCte['obj_id'] = Yii::$app->request->post('obj', '');
			$arrayCtaCte['subcta'] = Yii::$app->request->post('subcta', 0);
			$item_id = intval(Yii::$app->request->post('item', 0));
			
			$ItemDef = $model->CargarItem($item_id,$arrayCtaCte['trib_id'],$arrayCtaCte['anio'],$arrayCtaCte['cuota']);
			
			if ($_POST['opera'] == 'Modif' or $_POST['opera'] == 'Elim'){
				$ItemUno = $model->ConsultarItemUno(Yii::$app->session['liq'],$_POST['orden'],$_POST['venc']);
				$param1 = $ItemUno['param1'];
				$monto = $ItemUno['monto'];
				$valor_mm = $model->CalcularMM($_POST['venc'],$arrayCtaCte['trib_id']);
			}	
			
			if (count($ItemDef) > 0){
				if ($ItemDef['tcalculo'] == 12){
					$param1 = $model->VarSistema($array['paramnombre1']);
				}else if ($ItemDef['tcalculo'] == 15){
					$param1 = $model->GetTotalItems_Temp(Yii::$app->session['liq']);
				}
				
				if ($ItemDef['paramnombre1'] == "" && $ItemDef['paramnombre2'] == "" && $ItemDef['paramnombre3'] == "" && $ItemDef['paramnombre4'] == "") 
					$monto = $model->CalcularItem($erroritem,$item_id,$arrayCtaCte['anio'],$arrayCtaCte['anio'],
													$ItemDef['paramcomp1'],$ItemDef['paramcomp2'],$ItemDef['paramcomp3'],$ItemDef['paramcomp4']);
			}										
		}
		if (isset($_POST['pjCalcular']))
		{
			$monto = $model->CalcularItem($erroritem,$_POST['item'],$_POST['anio'],$_POST['cuota'],$_POST['param1'],$_POST['param2'],$_POST['param3'],$_POST['param4']);
			$valor_mm = $model->CalcularMM($_POST['venc'],$_POST['trib']);
			
			if ($_POST['accion'] == "aceptar")
			{
				if ($_POST['opera'] == "Agrega") $erroritem = $model->NuevoItem(Yii::$app->session['liq'],$_POST['item'],$_POST['param1'],$_POST['param2'],$_POST['param3'],$_POST['param4'],$monto,$_POST['trib'],$_POST['anio'],$_POST['cuota'],$_POST['obj'],$_POST['subcta']);
				if ($_POST['opera'] == "Modif") $erroritem = $model->ModificarItem(Yii::$app->session['liq'],$_POST['item'],$_POST['orden'],$_POST['param1'],$_POST['param2'],$_POST['param3'],$_POST['param4'],$monto,$_POST['trib'],$_POST['anio'],$_POST['cuota'],$_POST['obj'],$_POST['subcta']);
				if ($_POST['opera'] == "Elim") $erroritem = $model->BorrarItem(Yii::$app->session['liq'],$_POST['item'],$_POST['orden'],$_POST['trib'],$_POST['anio'],$_POST['cuota'],$_POST['obj'],$_POST['subcta']);
			}
		}
		if (isset($_POST['pjActGrilla']))
		{
			$dataProviderItem = $model->ConsultarItem(Yii::$app->session['liq'],isset($_POST['venc']) ? $_POST['venc'] : '',1);
			$total= $model->GetTotalItems(Yii::$app->session['liq'],$_POST['ctacte']) * $model->CalcularMM($_POST['venc'],$_POST['trib']);
			$ucm = $model->GetUCM($_POST['trib']);	
		}
				
		return $this->render('view',[
				'arrayCtaCte' => $arrayCtaCte,
				'dataProviderItem' => $dataProviderItem,
				'consulta' => 0,
				'ttrib' => $ttrib,
				'ucm' => $ucm,
				'uso_subcta' => $uso_subcta,
				'ItemDef' => $ItemDef,
				'param1' => $param1,
				'monto' => $monto,
				'item_id' => $item_id,
				'erroritem' => $erroritem,
				'valor_mm' => $valor_mm,
				'total' => $total,
				'ItemUno' => $ItemUno,
				'error' => $error
			]);
    }

    /**
     * Updates an existing Liquida model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $ctacte_id
     * @param integer $orden
     * @param integer $item_id
     * @return mixed
     */
    public function actionUpdate($ctacte, $menu=0)
    {
    	$model= new Liquida();
		$arrayCtaCte = Ctacte::findOne($ctacte);
	    if($arrayCtaCte == null) $arrayCtaCte= new CtaCte();
		
		$param1 = '';
		$monto = 0;
		$item_id = 0;
		$erroritem = '';
		$valor_mm = 0;
		$total = 0;
		$error = "";
		
		if (!isset(Yii::$app->session['liq'])) Yii::$app->session['liq'] = $model->GetLiqId();
		if (!Yii::$app->request->isPost) Liquida::CargarItemLiquida($ctacte, Yii::$app->session['liq']);
		$dataProviderItem = $model->ConsultarItem(Yii::$app->session['liq'],$arrayCtaCte->fchvenc);
		
		$arrayCtaCte['est'] = utb::getCampo("ctacte","ctacte_id=".$ctacte,"est");
		$total= $model->GetTotalItems(Yii::$app->session['liq'],$ctacte) * $model->CalcularMM($arrayCtaCte->fchvenc,$arrayCtaCte->trib_id);
		
		$ItemDef = $model->CargarItem(0,0,0,0);
		$ItemUno = $model->ConsultarItemUno(0,0,0);
		
		if (isset($_POST['txCtaCte_Id'])) 
        {
            $arrayCtaCte['trib_id'] = (isset($_POST['dlTributo']) ? $_POST['dlTributo'] : 0);
            $arrayCtaCte['obj_id'] = (isset($_POST['txObj_Id']) ? $_POST['txObj_Id'] : '');
            $arrayCtaCte['subcta'] = (isset($_POST['txSubCta']) && $_POST['txSubCta'] != '' ? $_POST['txSubCta'] : 0);
            $arrayCtaCte['anio'] = (isset($_POST['txAnio']) ? $_POST['txAnio'] : 0);
            $arrayCtaCte['cuota'] = (isset($_POST['txCuota']) ? $_POST['txCuota'] : 0);
            $arrayCtaCte['fchvenc'] = (isset($_POST['fchvenc']) ? $_POST['fchvenc'] : '');
            $arrayCtaCte['expe'] = (isset($_POST['txExpe']) ? $_POST['txExpe'] : '');
            $arrayCtaCte['obs'] = (isset($_POST['txObs']) ? $_POST['txObs'] : '');
            $ucm = $model->getUCM($arrayCtaCte['trib_id']);//(isset($_POST['opUCM']) ? $_POST['opUCM'] : 1);

            $ctacte = 0;
            $error = $model->GrabarLiquida($ctacte,"modif",Yii::$app->session['liq'],$arrayCtaCte['trib_id'],$arrayCtaCte['obj_id'],$arrayCtaCte['subcta'],$arrayCtaCte['anio'],$arrayCtaCte['cuota'],$arrayCtaCte['fchvenc'],$arrayCtaCte['expe'],$arrayCtaCte['obs'],$ucm == '' ? 0 : $ucm);
			
			if ($error == ""){
            	Yii::$app->session->setFlash('mensaje', 'Los datos se grabaron correctamente.' );
				return $this->redirect(['view', 'id' => $ctacte]);
			}else {
				$dataProviderItem = $model->ConsultarItem(Yii::$app->session['liq'],$arrayCtaCte['fchvenc'],1);
				$total= $model->GetTotalItems(Yii::$app->session['liq'],$ctacte) * $model->CalcularMM($arrayCtaCte['fchvenc'],$arrayCtaCte['trib_id']);
				$ucm = $model->GetUCM($arrayCtaCte['trib_id']);	
			}	
			
		}
		
		if (isset($_POST['pjBtnItem']))
		{
			$arrayCtaCte['trib_id'] = $_POST['trib'];
			$arrayCtaCte['anio'] = $_POST['anio'];
			$arrayCtaCte['cuota'] = $_POST['cuota'];
			$arrayCtaCte['obj_id'] = Yii::$app->request->post('obj', '');
			$arrayCtaCte['subcta'] = Yii::$app->request->post('subcta', 0);
			$item_id = intval(Yii::$app->request->post('item', 0));
			
			$ItemDef = $model->CargarItem($item_id,$arrayCtaCte['trib_id'],$arrayCtaCte['anio'],$arrayCtaCte['cuota']);
			
			if ($_POST['opera'] == 'Modif' or $_POST['opera'] == 'Elim'){
				$ItemUno = $model->ConsultarItemUno(Yii::$app->session['liq'],$_POST['orden'],$_POST['venc']);
				$param1 = $ItemUno['param1'];
				$monto = $ItemUno['monto'];
				$valor_mm = $model->CalcularMM($_POST['venc'],$arrayCtaCte['trib_id']);
			}	
			
			if (count($ItemDef) > 0){
				if ($ItemDef['tcalculo'] == 12){
					$param1 = $model->VarSistema($array['paramnombre1']);
				}else if ($ItemDef['tcalculo'] == 15){
					$param1 = $model->GetTotalItems_Temp(Yii::$app->session['liq']);
				}
				
				if ($ItemDef['paramnombre1'] == "" && $ItemDef['paramnombre2'] == "" && $ItemDef['paramnombre3'] == "" && $ItemDef['paramnombre4'] == "") 
					$monto = $model->CalcularItem($erroritem,$item_id,$arrayCtaCte['anio'],$arrayCtaCte['anio'],
													$ItemDef['paramcomp1'],$ItemDef['paramcomp2'],$ItemDef['paramcomp3'],$ItemDef['paramcomp4']);
			}										
		}
		if (isset($_POST['pjCalcular']))
		{
			$monto = $model->CalcularItem($erroritem,$_POST['item'],$_POST['anio'],$_POST['cuota'],$_POST['param1'],$_POST['param2'],$_POST['param3'],$_POST['param4']);
			$valor_mm = $model->CalcularMM($_POST['venc'],$_POST['trib']);
			
			if ($_POST['accion'] == "aceptar")
			{
				if ($_POST['opera'] == "Agrega") $erroritem = $model->NuevoItem(Yii::$app->session['liq'],$_POST['item'],$_POST['param1'],$_POST['param2'],$_POST['param3'],$_POST['param4'],$monto,$_POST['trib'],$_POST['anio'],$_POST['cuota'],$_POST['obj'],$_POST['subcta']);
				if ($_POST['opera'] == "Modif") $erroritem = $model->ModificarItem(Yii::$app->session['liq'],$_POST['item'],$_POST['orden'],$_POST['param1'],$_POST['param2'],$_POST['param3'],$_POST['param4'],$monto,$_POST['trib'],$_POST['anio'],$_POST['cuota'],$_POST['obj'],$_POST['subcta']);
				if ($_POST['opera'] == "Elim") $erroritem = $model->BorrarItem(Yii::$app->session['liq'],$_POST['item'],$_POST['orden'],$_POST['trib'],$_POST['anio'],$_POST['cuota'],$_POST['obj'],$_POST['subcta']);
			}
		}
		if (isset($_POST['pjActGrilla']))
		{
			$dataProviderItem = $model->ConsultarItem(Yii::$app->session['liq'],isset($_POST['venc']) ? $_POST['venc'] : '',0);
			$total= $model->GetTotalItems(Yii::$app->session['liq'],$_POST['ctacte']) * $model->CalcularMM($_POST['venc'],$_POST['trib']);
			$ucm = $model->GetUCM($_POST['trib']);	
		}
		
		return $this->render('view',[
				'arrayCtaCte' => $arrayCtaCte,
				'dataProviderItem' => $dataProviderItem,
				'consulta' => 3,
				'ItemDef' => $ItemDef,
				'param1' => $param1,
				'monto' => $monto,
				'item_id' => $item_id,
				'erroritem' => $erroritem,
				'valor_mm' => $valor_mm,
				'total' => $total,
				'ItemUno' => $ItemUno,
				'error' => $error
			]);
    }

    /**
     * Deletes an existing Liquida model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $ctacte_id
     * @param integer $orden
     * @param integer $item_id
     * @return mixed
     */
    public function actionDelete($id, $accion,$motivo='')
    {
        $arrayCtaCte = Ctacte::findOne($id);
        if($arrayCtaCte == null) $arrayCtaCte= new CtaCte();
        
        $arrayCtaCte['est'] = utb::getCampo("ctacte","ctacte_id=".$id,"est");
        
        $dataProviderItem = Liquida::BuscarItem($id);
		
		$total= (new Liquida)->GetTotalItems(0,$id);
        
        if ($accion == 1)
        {
        	$error = '';
        	
        	if ($arrayCtaCte['est'] == 'P' or $arrayCtaCte['est'] == 'X' or $arrayCtaCte['est'] == 'E')
        	{
        		$error = "<li>No podrá eliminar la liquidación. Verifique el estado.</li>";
        	}else {
        		
        		if (Liquida::BorrarLiquida($id, $motivo))
        		{
        			return $this->redirect(['view', 'id' => $id, 'm' => 2]);
        		}else{
        			$error = "<li>No se pudo eliminar la liquidación.</li>";
        		}
        	}
	
        	if ($error !== "")
        	{	
        		return $this->render('view', [
            		'arrayCtaCte' => $arrayCtaCte,
            		'dataProviderItem' => $dataProviderItem,
            		'consulta' => 2,
            		'error' => $error,
					'total'=>$total
        		]);
        	}
        	
        }else {
        	return $this->render('view', [
            	'arrayCtaCte' => $arrayCtaCte,
            	'dataProviderItem' => $dataProviderItem,
            	'consulta' => 2,
				'total'=>$total
        	]);
        }
    }
    
    public function actionCancelar($ctacte)
    {
    	Liquida::BorrarItemLiquida(isset(Yii::$app->session['liq']) ? Yii::$app->session['liq'] : 0);
    	
    	return $this->redirect(['view', 'id' => $ctacte]);
    }
    
    public function actionBuscar()
    {
    	if (isset($_POST['txCtaCte'])) 
    	{
    		return $this->redirect(['view', 'id' => $_POST['txCtaCte']]);
    	}
    }
    
    public function actionList_op()
    {    	 
    	$cond='';
    	$condimp = '';
		$desc ='';
		
		if (isset($_POST['txcriterio']) and $_POST['txcriterio']!=='') $cond=$_POST['txcriterio'];
		if (isset($_POST['txcriterioimp']) and $_POST['txcriterioimp']!=='') $condimp=$_POST['txcriterioimp'];
		if (isset($_POST['txdescr']) and $_POST['txdescr']!=='') $desc=$_POST['txdescr'];
		if (isset($_POST['txeventual']) and $_POST['txeventual']!=='') $eventual=$_POST['txeventual'];
		
		if ($cond == '' and $condimp == '')
		{
			return $this->render('list_op');	
		}else {
			$session = new Session;
			$session->open();
			$session['order'] = '';
			$session['by'] = '';	
			$session['cond'] = $cond;
			$session['condimp'] = $condimp;
			$session['imp'] = isset($_POST['ckCantImp']) ? $_POST['ckCantImp'] : 0;
			$session['tobj'] = isset($_POST['dlTObj']) ? $_POST['dlTObj'] : 0;
			$session['desc'] = $desc;
			$session->close();
			
			return $this->redirect([
							'list_res', 'e' => $eventual
							]);
		}
    }
    
    public function actionList_res($e)
    {    	 
    	$session = new Session;
    	$session->open();
    	    	
    	return $this->render('list_res',['desc' => $session['desc'], 'eventual' => $e]);
    	    	
    	$session->close();
    }
    
    /**
     * Finds the Liquida model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $ctacte_id
     * @param integer $orden
     * @param integer $item_id
     * @return Liquida the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ctacte_id, $orden, $item_id)
    {
        if (($model = Liquida::findOne(['ctacte_id' => $ctacte_id, 'orden' => $orden, 'item_id' => $item_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionImprimircomprobante($id,$sem=0)
    {
    	$error = "";
    	$id = intval($id);
    	if($id < 0) $id= 0;
    	
    	$emision = array();
    	$sub1 = array();
    	$sub2 = array();
    	$sub3 = array();
    	$sub4 = array();
    	$error = Liquida::ImprimirComprobante($id,$emision,$sub1,$sub2,$sub3,$sub4,$sem);
    	$session = new Session;
    	$session->open();
   		$session['error'] = $error;
   		$session->close();
   		
    	if ($error == "")
    	{
    		$domi = '';
    		$tel = '';
    		$mail = '';
    		
    		Liquida::GetTributoDomi($emision[0]['trib_id'],$domi,$tel,$mail);
    		
    		$pdf = Yii::$app->pdf;
    		$pdf->marginTop = '5px';
    		$pdf->marginBottom = '5px';
    		$pdf->marginLeft = '8px';
    		$pdf->marginRight = '8px';
      		$pdf->content = $this->renderPartial('//reportes/boleta'.($sem == 1 ? 'sem' : ''),
      									[
											'emision' => $emision,
											'sub1' => $sub1,
											'sub2' => $sub2,
											'sub3' => $sub3,
											'sub4' => $sub4,
											'domicilio' => $domi,
											'telefono' => $tel,
											'mail' => $mail,
											'ucm' => (Liquida::GetUCM($emision[0]['trib_id']) > 0 ? Liquida::GetTextoUCM() : '')
										]);     
      		$pdf->methods['SetHeader'] = '';
      		$pdf->methods['SetFooter'] = '';
      		$pdf->filename = 'Boleta'; 
      		return $pdf->render();
      		
    	}else {
   			return $this->redirect(['view', 'id' => $id]);
   		}
    }
    
   protected function Mensajes($id)
    {
    	switch ($id) {
    		case 1: 
    			$mensaje = 'Datos Grabados';
    			break;
    		case 2: 
    			$mensaje = 'Se eliminó la Liquidación';
    			break;
    		default: 
    			$mensaje = '';
    	}
    	
    	return $mensaje;  
    }
}
