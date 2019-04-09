<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Libredeuda;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;

/**
 * MultaController implements the CRUD actions for Multa model.
 */
class LibredeudaController extends Controller
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
	 * Función que se utiliza para mostrar las diferentes formas.
	 * 
	 */
    public function actionIndex( $m = 0, $c = 0, $op = '', $acc = '' )
    {       
        
		$error = Yii::$app->session['error'];
        
        Yii::$app->session['error'] = '';
        
        if ($op != 'N') Yii::$app->session['LDeudaNuevo'] = null;
        
        if ($c==0){
        	Yii::$app->session['cond'] = '';
			Yii::$app->session['desc'] = '';
        }
        
        return $this->render('index',['error' => $error,'mensaje' => $this->Mensajes($m),'op'=>$op,'accion'=>$acc]);
    }
    
    public function actionImprimir()
    {
    	$obj_id = (isset($_POST['txObj_Id']) ? $_POST['txObj_Id'] : '');
    	$Escribano = (isset($_POST['txEsc']) ? $_POST['txEsc'] : '');
    	$Texto = (isset($_POST['dlTexto']) ? $_POST['dlTexto'] : 0);
    	$Obs = (isset($_POST['txObs']) ? $_POST['txObs'] : '');
    	$AnioDesde = (isset($_POST['txAnioDesde']) ? $_POST['txAnioDesde'] : 0);
    	$AnioHasta = (isset($_POST['txAnioHasta']) ? $_POST['txAnioHasta'] : date('Y'));
    	$TObj = (isset($_POST['dlTObjeto']) ? $_POST['dlTObjeto'] : 0);
		$trib_id = (isset($_POST['dlTributo']) && $TObj == 3 ? $_POST['dlTributo'] : 0);
    	$firma = (isset($_POST['dlFirma']) ? $_POST['dlFirma'] : 0);
    	$datos = null;
    	$sub = null;
		$sub2 = null;
    	$error = '';
    	
    	$error = (new LibreDeuda)->Imprimir($obj_id,$Escribano,$Texto,$Obs,$AnioDesde,$AnioHasta,$TObj,$trib_id,$datos,$sub,$sub2);
    	Yii::$app->session['error'] = $error;
    	
    	if ( $error == '' )
    	{
    		if ($datos[0]['sindeudas'] == 0) {
    			$texto = utb::getCampo('sam.config','1=1','titulo_libredeuda');
    		}else {
    			$texto = utb::getCampo('sam.config','1=1','titulo2_libredeuda');
    		}
    		if ($firma > 0){
    			$firma_desc = utb::getCampo('intima_firma','firma_id='.$firma);
    			$firma_desc .= '<br>'.utb::getCampo('intima_firma','firma_id='.$firma,'cargo');
    			$firma_desc .= '<br>'.utb::getCampo('intima_firma','firma_id='.$firma,'area');
    		}
    		$prox_venc = utb::getCampo('sam.config','1=1','ProxVenc_LibreDeuda');
    		
    		$pdf = Yii::$app->pdf;
    		$pdf->marginTop = '40px';
    		$pdf->marginFooter = '2px';
    		$pdf->content = $this->renderPartial('//reportes/libredeuda',
	      			['datos' => $datos,'sub' => $sub,'sub2' => $sub2, 'texto'=>$texto,'firma'=>$firma_desc,'proxvenc'=>$prox_venc,'trib_id' => $trib_id]);
	                
	        return $pdf->render();	
    	} else 
    	{
			Yii::$app->session['LDeudaNuevo'] = [
					'obj' =>$obj_id,
					'escrib' => $Escribano,
					'texto' => $Texto,
					'obs' => $Obs,
					'aniodesde' => $AnioDesde,
					'aniohasta' => $AnioHasta,
					'tobj' => $TObj,
					'firma' => $firma
				];
				
    		return $this->redirect(['index','op'=>'N']);
    	}
    	
    }
    
    public function actionList_op()
    {    	 
    	$cond='';
    	$desc ='';
		
		if (isset($_POST['txcriterioList']) and $_POST['txcriterioList']!=='') $cond=$_POST['txcriterioList'];
		if (isset($_POST['txdescrList']) and $_POST['txdescrList']!=='') $desc=$_POST['txdescrList'];
		
		Yii::$app->session['cond'] = $cond;
		Yii::$app->session['desc'] = $desc;
		
		return $this->render('list_res');
    }
    
    public function actionDelete($id)
    {
    	$error = (new LibreDeuda)->Borrar($id);
    	Yii::$app->session['error'] = $error;
    	return $this->redirect(['index','m'=>($error=='' ? 2 : 0)]);
    }
    
    public function actionAccion( $acc = '' )
    {
    	$error = '';
    	$accion = (isset($_POST['txAccion']) ? $_POST['txAccion'] : $acc);
    	$objquitar = (isset($_POST['txObjQuitar']) ? $_POST['txObjQuitar'] : ''); 
     	if ($objquitar != '')
     		$obj = $objquitar;
    	else 
    		$obj = (isset($_POST['txObj_IdAcc']) ? $_POST['txObj_IdAcc'] : '');
      	$quitar = (isset($_POST['txQuitar']) ? $_POST['txQuitar'] : '');
		$obs = (isset($_POST['txObsAcc']) ? $_POST['txObsAcc'] : '');
    	
    	if ($obj != '') $error = (new LibreDeuda)->LibreDeudaEsp($obj,$accion,$quitar,$obs);
    	Yii::$app->session['error'] = $error;
    	
    	$m = ($error == '' && $obj != '' ? 1 : 0);

    	return $this->redirect(['index','op'=>'A','acc'=>$accion,'m'=>$m]);
    }
    
    protected function Mensajes($id)
    {
    	switch ($id) {
    		case 1: 
    			$mensaje = 'Datos Grabados.';
    			break;
    		case 2: 
    			$mensaje = 'Libre Deuda Eliminado.';
    			break;
    		default: 
    			$mensaje = '';
    	}
    	
    	return $mensaje;  
    }
}
