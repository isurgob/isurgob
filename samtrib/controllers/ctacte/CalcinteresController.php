<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\CalcInteres;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\db\Fecha;
/**
 * CalcInteresController implements the CRUD actions for CalcInteres model.
 */
class CalcInteresController extends Controller
{
	private function getDataProvider()
	{
		$count = Yii::$app->db->createCommand('Select count(*) From calc_interes')->queryScalar();
		
		return new SqlDataProvider([
            'sql' => "Select " .
            				"To_Char(i.FchDesde, 'DD/MM/YYYY') AS fchdesde, " .
            				"To_Char(i.FchHasta, 'DD/MM/YYYY') AS fchhasta, " .
            				"i.Indice AS indice, " .
            				"(u.nombre || ' - ' || To_Char(i.FchMod,'DD/MM/YYYY'))  AS modificacion " .
            			"From Calc_Interes i " . 
						"Left Join " .
							"Sam.Sis_Usuario u " .
						"on " .
							"i.UsrMod = u.Usr_Id " .
						"Order By " .
							"FchDesde Desc",
			'totalCount' => (int)$count,
			'pagination' => [
				'pageSize' => 20,
			],
        ]);
	}
	
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
    
     public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
    	
    	return true;
    } 

    /**
     * Lists all CalcInteres models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = $this->getDataProvider();
		$model = new CalcInteres();
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new CalcInteres model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	$model = new CalcInteres();
		
		if( $model->load( Yii::$app->request->post() ) )
		{
			if ( $model->validate() && $model->grabar() ) 
	        {
	            return $this->redirect(['index', 'tipoMensaje' => 'create']);
	        } 
	        
		}
	        
        return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing CalcInteres model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $fchdesde
     * @param string $fchhasta
     * @return mixed
     */
    public function actionUpdate()
    {	
    	$model = new CalcInteres();
    			
    	if(Yii::$app->request->isGet)
    	{
    		try
    		{
    			$model = $this->findModel(Yii::$app->request->get('fchdesde'), Yii::$app->request->get('fchhasta'));
    		}
    		catch(Exception $e)
    		{
    			return $this->actionIndex();
    		}
    		
    		if($model != null)
    		{
//    			$model->fchdesde = Fecha::bdToUsuario($model->fchdesde);
//    			$model->fchhasta = Fecha::bdtoUsuario($model->fchhasta);
    			    			
    			$this->render('update', ['model' => $model]);	
    		}
    		
    	}
		else
		if(Yii::$app->request->isPost){
			
			$fchdesde = Yii::$app->request->post("CalcInteres")['fchdesde'];
			$fchhasta = Yii::$app->request->post("CalcInteres")['fchhasta'];
			$indice = Yii::$app->request->post("CalcInteres")['indice'];			
			
			if($fchdesde == null || $fchhasta == null || $indice == null)
				return $this->render('update', ['model' => new CalcInteres(),'mensaje' => 'Uno o más datos no han sido ingresados']);
			
			$fchdesde = Fecha::usuarioToBD($fchdesde);
			$fchhasta = Fecha::usuarioToBD($fchhasta);
			
			$model= CalcInteres::findOne(['fchdesde' => $fchdesde, 'fchhasta' => $fchhasta]);

			if($model === null){
				
				$model= new CalcInteres();
				return $this->render('update', ['model' => $model, 'mensaje' => 'Ocurrió un error al intentar cargar el registro.']);
			}
				
			
			//se modifica el indice para que se grabe el nuevo
			$model->indice = $indice;
			
        	if ($model->grabar()) 
        		return $this->redirect(['index', 'tipoMensaje' =>'update']);
        	else {
        	                	
        		$error= $model->getError();
        	        	
            	return $this->render('update', [
                	'model' => $model,
                	'mensaje' => $error != null ? $error : "Ha ocurrido un error al intentar modificar los datos.",
            	]);
        	}
        
        }
        
        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing CalcInteres model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $fchdesde
     * @param string $fchhasta
     * @return mixed
     */
    public function actionDelete($fchdesde, $fchhasta, $ejecutar = null)
    {
    	$model = $this->findModel($fchdesde, $fchhasta);
        
        if($model != null)
        {
        	$model->fchdesde = Fecha::bdToUsuario($model->fchdesde);
        	$model->fchhasta = Fecha::bdToUsuario($model->fchhasta);
        	
        	
        }
        
        if(!$model->load(Yii::$app->request->post()))
        		return $this->render('delete', ['model' => $model]);	
        
        
        $res = $model->borrar();

		if($res)
			return $this->redirect(['index',
									'tipoMensaje' => 'delete'
								]);
		else
			return $this->render('delete', [
									'model' => $model,
									'mensaje' => $model->getError()
								]);
    }
    
    
    /**
     * Si no se provee el parametro <b>ejecutar</b>, se procede a mostrar la vista correspondiente al cálculo de interés.
     * Si se provee del parametro <b>ejecutar</b>, se procede a realizar el cálculo de interés y mostrar la vista correspondiente pasandole como parametro el monto obtenido del cálculo realizado.
     * 
     * El método debe ejecutarse por GET.
     * 
     * @param String $fchdesde Fecha de inicio del periodo.
     * @param String $fchhasta Fecha de finalización del periodo.
     * @param number $nominal Monto sobre el cual se deben calcular los intereses.
     * 
     * 
     */
    public function actionCalcular()
    {    	    	    	
    	$model = new CalcInteres();
    	$error = null;
    	$monto = null;
    	    	//en caso de que el $monto sea igual a -1, ha ocurrido un error que se obtiene de $model->getError()
    	    	
    	if(Yii::$app->request->isPost)
    	{
    		$fchvencimiento = Yii::$app->request->post('fchvencimiento');
    		$fchpago = Yii::$app->request->post('fchpago');
    		$nominal = Yii::$app->request->post('nominal');
    		$ejecutar = Yii::$app->request->post('ejecutar');
    		
    		if($fchvencimiento && $fchpago && $nominal && $ejecutar)
    			$monto = $model->calcularIntereses($fchvencimiento, $fchpago, $nominal);
    		else $error = 'Uno o más datos no han sido ingresados';
    		
    		
    		if(!$error)
    		{
    			if($monto == -1)
    				$error = $model->getError();
    			else if($monto == null)
    				$error = 'Ha ocurrido un error al intentar calcular el monto.';	
    		}	
    		
    		if($monto != null && $monto > -1)
    			return $this->render('calcular', ['monto' => $monto]);
    		else if($error != null)
    			return $this->render('calcular', ['error' => $error]);
    		else return  $this->render('calcular', ['error' => 'ocurrio un error y no se sabe cual']);		
    		
    	}    	

    	return $this->render('calcular');
    }
    
   

	/**
	 * 
	 * Se actualiza el minimo de interes actual y se procede a mostrar la vista que corresponde.
	 * 
	 */
	public function actionMinimo()
	{		
		$model = new CalcInteres();
		$error = null;
		
		
		if(Yii::$app->request->isGet)
		{
			$minimo = $model->obtenerMinimoInteres();
			return $this->render('minimo', ['minimo' => $minimo != -1 ? $minimo : 0]);
		}
		
		
		if(Yii::$app->request->isPost)
		{
			$monto = Yii::$app->request->post('monto');
			
			//no se ha provisto del parametro monto a traves de post
			if($monto == null)
				return $this->render('minimo', ['minimo' => $model->obtenerMinimoInteres()]);
				
			//el minimo se registra correctamente
			if($model->grabarMinimoInteres($monto))
				return $this->redirect(['index', 'tipoMensaje' => 'minimo']);
			
			
			$error = $model->getError();
			
			if($error != null)
				return $this->render('minimo', ['minimo' => $model->obtenerMinimoInteres(), 'mensaje' => $error]);
			else return $this->render('minimo'. ['minimo' => $model->obtenerMinimoInteres(), 'mensaje' => 'Ha ocurrido un error inesperado.']);
		}
		
		return $this->redirect(['index']);	
	}


    /**
     * Finds the CalcInteres model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $fchdesde
     * @param string $fchhasta
     * @return CalcInteres the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($fchdesde, $fchhasta)
    {
        if (($model = CalcInteres::findOne(['fchdesde' => $fchdesde, 'fchhasta' => $fchhasta])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}