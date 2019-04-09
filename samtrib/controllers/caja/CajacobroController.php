<?php

namespace app\controllers\caja;

use Yii;
use app\models\caja\CajaCobro;
use app\models\estad\Caja;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use yii\web\Session;
use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * CajaController implements the CRUD actions for Caja model.
 */
class CajacobroController extends Controller
{

	const MENSAJE_ERROR = 'cajaCobro_error';
	const CODIGO_ERROR = 'cajaCobro_codigoError';

    public function beforeAction($action){

		//Si no existe id de usuario, se redirige al login.
    	if ( Yii::$app->user->id == '' )
    		return $this->redirect('index');

		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		return true;
	}

    /**
     * Dibuja el formulario.
     */
    public function actionView( $id = '', $consulta = 1, $i = 0 )
    {

    	/**
    	 * $consulta == 0 => Formulario para inserción de datos
    	 * $consulta == 1 => Formulario para inserción de datos y procesar
    	 * $consulta == 2 => Formulario para inserción de datos y calcular
    	 * $consulta == 3 => Formulario para inserción de datos y grabar
    	 */

    	$session = new Session;
    	$session->open();

    	//El parámetro $i == 1 indica que se deben reiniciar las sesiones.
    	if ( $i == 1 )
    	{
    		$session['cajaCobro_estadoCaja'] = 'C';
    		$session['cajaCobro_idCaja'] = '';
    		$session['cajaCobro_fechaCaja'] = '';
    		$session['cajaCobro_estado_estado'] = 'C';

    		$model = new Cajacobro();
    		return $this->redirect(['view']);

    	}

		$model = new Cajacobro();

    	$model->caja_estado = $session['cajaCobro_estadoCaja'];
    	$model->caja_caja_id = $session['cajaCobro_idCaja'];
		$model->caja_fecha = $session['cajaCobro_fechaCaja'];

		//Creo el DataProvider para la grilla de cuentas vacío
    	//$dataProvider = new ArrayDataProvider(['allModels' => []]);

    	if ( $id != '' )
    	{
    		$model = $this->findModel( $id ); //Obtengo los datos del modelo

    		if ($model->pago_id == '' || $model->pago_id == null)
    		{
    			$id = '';
    			Yii::$app->session->setFlash(self::MENSAJE_ERROR , 'No se encontró ningún Pago a Cuenta.' );
				Yii::$app->session->setFlash(self::CODIGO_ERROR , 2);
				$consulta = 1;
    		}

    	}

		$session['modelCajaCobro'] = urlencode(serialize($model));
		$session->close();

        echo $this->view( $id, $consulta, $model );
    }

	public function view( $id = '', $consulta = 1, $model ){

		return $this->render('view', [
        	'consulta'=>$consulta,
            'id' => $id,
            'model' => $model,
            'm'=> Yii::$app->session->getFlash(self::CODIGO_ERROR , 1),
            'alert'=> Yii::$app->session->getFlash(self::MENSAJE_ERROR , ''),
			'mediosPago' => $model->getMediosDePagoHabilitados( Yii::$app->session->get( 'cajaCobro_idCaja' , 0 ) ),

        ]);

	}

    /**
     * Función que se ejecuta para realizar la apertura, reapertura o cierre de una caja.
     * @param integer $caja_id Identificador de la caja.
     * @param string $clave Password
     * @param string $fecha Fecha de la caja
     * @param string $action Identifica la accion que se lleva a cabo en la caja. A. Apertura - R. Reapertura - C. Cierre
     */
    public function actionApertura()
    {
    	$model = new CajaCobro();

    	//Obtengo los datos que me llegan cuando se realiza la apertura de la caja.
    	$accion = Yii::$app->request->post('txAccion','');
		$clave = Yii::$app->request->post('txClaveID','');
		$caja_id = Yii::$app->request->post('txCaja',0);
		$fecha = Yii::$app->request->post('txFecha','');

		if ($accion != '' && $clave != '' && $caja_id != 0 && $fecha != '')
		{
			$result = $model->apertura($caja_id,$clave,$fecha,0,$accion);

			if ($result['return'] == 0)
				$result['return'] = 2;

			Yii::$app->session->setFlash( self::MENSAJE_ERROR, $result['mensaje'] );
			Yii::$app->session->setFlash( self::CODIGO_ERROR, $result['return'] );

	    	$this->redirect(['view', 'i' => ( $accion == 'C' ? '1' : '0')]);
		}

    }

    public function actionComprobante()
    {
    	$model = new CajaCobro();

    	$session = new Session;

    	$session->open();

    	$session['condListadoCobranza'] = "caja_id = " . $model->caja_caja_id . " AND fecha = " . Fecha::usuarioToBD( $model->caja_fecha, 1 );
		$session['descrListadoCobranza'] = 'El usuario de caja es ' . utb::getCampo('sam.sis_usuario','usr_id = ' . Yii::$app->user->id,'nombre') . ' - Fecha de Caja ' . $model->caja_fecha;
		$session['returnCajaCobro'] = 1;

		$session->close();

    	return $this->redirect(['caja/cajaticket/listado']);
    }

    /**
     * Función que se utiliza para grabar un arqueo
     */
    public function actionGrabararqueo()
    {
    	$model = new Cajacobro();

	 	$model->arqueo_billete1000 = Yii::$app->request->post('tx1000',0);
		$model->arqueo_billete500 = Yii::$app->request->post('tx500',0);
		$model->arqueo_billete200 = Yii::$app->request->post('tx200',0);
		$model->arqueo_billete100 = Yii::$app->request->post('tx100',0);
		$model->arqueo_billete050 = Yii::$app->request->post('tx50',0);
		$model->arqueo_billete020 = Yii::$app->request->post('tx20',0);
		$model->arqueo_billete010 = Yii::$app->request->post('tx10',0);
		$model->arqueo_billete005 = Yii::$app->request->post('tx5',0);
		$model->arqueo_billete002 = Yii::$app->request->post('tx2',0);
		$model->arqueo_moneda100 = Yii::$app->request->post('tx1',0);
		$model->arqueo_moneda050 = Yii::$app->request->post('tx050',0);
		$model->arqueo_moneda025 = Yii::$app->request->post('tx025',0);
		$model->arqueo_moneda010 = Yii::$app->request->post('tx010',0);
		$model->arqueo_moneda005 = Yii::$app->request->post('tx005',0);
		$model->arqueo_moneda001 = Yii::$app->request->post('tx001',0);

		$result = $model->arqueoNuevo();

		if ($result['return'] == 0)
				$result['return'] = 2;

		//Seteo los valores para mensajes
		Yii::$app->session->setFlash( self::MENSAJE_ERROR, $result['mensaje'] );
		Yii::$app->session->setFlash( self::CODIGO_ERROR, $result['return'] );
		//Seteo variable para imprimir arqueo
		Yii::$app->session->setFlash( 'imparqueo_caja', $model->caja_caja_id );
		Yii::$app->session->setFlash( 'imparqueo_fecha', $model->caja_fecha );

		$this->redirect(['view']);

    }
	
	public function actionImprimirarqueo($caja,$fecha)
	{
		$teso = utb::getCampo("caja","caja_id=".$caja,"teso_id");
		if ($teso == '') $teso = 0;
		
		$estad = Caja::findOne(23);
		$estad->ImprimirMdpArqueo($teso,$caja,$fecha);
		
		Yii::$app->session['estad'] = $estad;
		
		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
      	$pdf->content = $this->renderPartial('//reportes/cajamdparqueo');        
         
        return $pdf->render();
		
	}

    public function actionImprimirmdpe()
    {
    	$estad = Caja::findOne(22);

    	$total = 0;
    	$estad->ImprimirMdpEspecial(1,'2015/10/08',6,'2015/10/08','2015/10/08',0,$total);

		if ($total <= 1000)
		{
			$format = 'A4-P';
			return $this->redirect(['//site/pdflist','format'=>$format]);
		}
    }

    public function actionMdpespecial(){

    	$model= Yii::$app->session->get('modelCajaCobro', urlencode(serialize(new CajaCobro())));

    	$model= unserialize(urldecode($model));
    	return $this->render('listamdpe', ['model' => $model]);
    }

    /**
     * Finds the Caja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Caja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Caja::findOne($id)) !== null) {

        	if ($model->teso_id == null)
        	{
        		$model->teso_id = 0;

        	}

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
