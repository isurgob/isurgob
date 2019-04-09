<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Resol;
use app\models\ctacte\ResolLocal;
use app\models\ctacte\ResolTabla;
use app\models\ctacte\ResolTablaDato;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\utils\db\utb;


/**
 * ResolController implements the CRUD actions for Rubro model.
 */		  
class ResolController extends Controller
{
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
    
    /**
     * Carga el modelo ResolLocal con los datos de la variable solicitada.
     * 
     * @param int $resol_id - Codigo de la resolucion
     * 
     * @return ResolLocal - Variable cargada. Una variable nueva en caso de que no se haya solicitado ninguna o no exista.
     */
    private function resolverVariable($resol_id){
    	
    	$consulta= intval(Yii::$app->request->get('consultaVariable', -1));
    	$varlocal= trim(Yii::$app->request->get('varlocal', ''));
    	$ret= null;
    	
    	if(Yii::$app->request->isGet && $consulta > 0 && $varlocal != ''){
			
			$ret= ResolLocal::findOne(['resol_id' => $resol_id, 'varlocal' => $varlocal]); 
			
    	} 
    	
    	if($ret == null) $ret= new ResolLocal();
    	return $ret;
    }
    
    /**
     * Carga el modelo ResolTabla con los datos de la tabla solicitada.
     * 
     * @param int $resol_id - Codigo de la resolucion.
     * 
     * @return ResolTabla - Tabla cargada. Una tabla nueva en caso de que no se haya soliticado ninguna o no exista.
     */
    private function resolverTabla($resol_id){
    	
    	$consulta= intval(Yii::$app->request->get('consultaTabla', -1));
    	$tabla_id= intval(Yii::$app->request->get('tabla', -1));
    	$ret= null;
    	
    	if($consulta > 0 && $tabla_id > 0)
    		$ret= ResolTabla::findOne(['resol_id' => $resol_id, 'tabla_id' => $tabla_id]);
    		
    	if($ret == null) $ret= new ResolTabla();
    	
    	return $ret;
    }
    
    /**
     * Carga el modelo ResolTablaDato con los datos del dato solicitado.
     * 
     * @param int $tabla_id - Codigo de la tabla.
     * 
     * @return ResolTablaDato. Dato cargado. Un dato nuevo en caso de que no se haya solicitado ninguno o no exista.
     */
    private function resolverDato($tabla_id){
    	
    	$consulta= intval(Yii::$app->request->get('consultaDatos', -1));
    	$dato_id= intval(Yii::$app->request->get('dato', -1));
    	
    	$ret= null;
    	
    	if($consulta > -1 && $dato_id > -1)    		
    		$ret= ResolTablaDato::findOne(['tabla_id' => $tabla_id, 'dato_id' => $dato_id]);    	
    	
    	if($ret === null){
    		
    		$ret= new ResolTablaDato();
    		$ret->tabla_id= $tabla_id;
    	}
    	
    	return $ret;
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
     * Lists all Resoluciones models.
     * @return mixed
     */
    public function actionIndex($m=''){

		$mensaje= $this->mensajes(intval($m));
		$resoluciones= Resol::find()->all();
		$dataProvider= new ArrayDataProvider([
							'allModels' => $resoluciones, 
							'key' => 'resol_id',
							'totalCount' => count($resoluciones),
							'pagination' => ['pageSize' => 50]
							]);
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'mensaje'=>$mensaje
        ]);
    }

    /**
     * Displays a single Rubro model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id,$m='',$error=''){
    	
    	$mensaje= $this->mensajes(intval($m));
    	$model= Resol::findOne(['resol_id' => $id]);
    	if($model == null) $model= new Resol();
    	
    	$modelResolLocal= $this->resolverVariable($model->resol_id);
		$modelResolTabla= $this->resolverTabla($model->resol_id);
		$modelResolTablaDato= $this->resolverDato($modelResolTabla->tabla_id);

		//DataProvider con las variables de la resolucion
		$dpVariables= new ArrayDataProvider([
					'allModels' => $model->variables(),
					
					]);

		//DataProvider con las tablas de la resolucion
		$dpTablas= new ArrayDataProvider([
					'allModels' => $model->tablas()
					]);

		//Filtros solicitados de los datos
		$filtroAnio= intval(Yii::$app->request->get('filtroAnio', 0));
		$filtroCuota= intval(Yii::$app->request->get('filtroCuota', 0));
		
		//Data provider con los datos filtrados o no
		$dpDatos= new ArrayDataProvider([
					'allModels' => $modelResolTabla->datos($filtroAnio, $filtroCuota)
					]);
					
		$columnas= $modelResolTabla->columnas;
		

		return $this->render('form_resol', [
			'model' => $model,
			'mensaje'=>$mensaje,
			'key' => 'resol_id',
			'error'=>$error,
			'dpVariables' => $dpVariables,
			'dpTablas' => $dpTablas,
			'dpDatos' => $dpDatos,
			'modelResolLocal' => $modelResolLocal,
			'modelResolTabla' => $modelResolTabla,
			'modelResolTablaDato' => $modelResolTablaDato,
			'columnas' => $columnas
			]);
	}

    /**
     * Creates a new Rubro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     
    public function actionCreate(){

		$model = new Resol();
		$model->setScenario('insert');

		if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->grabar())			
			return $this->redirect(['index', 'm' => 1]);
	
		return $this->render('create', [
				'model' => $model
			]);
	}

    /**
     * Updates an existing Rubro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
	public function actionUpdate($id){

		$model= Resol::findOne(['resol_id' => $id]);
		
		if(Yii::$app->request->isPost && $model !== null){
			
			$model->setScenario('update');
			if($model->load(Yii::$app->request->post())  && $model->grabar())
				return $this->redirect(['index', 'm' => 1]);
		}
		
		if($model === null) $model= new Resol();
		
		return $this->render('update', ['model' => $model]);
	}
    
    public function actionImprimir($id)
    {
    	$datos = null;
    	$sub1 = null;
    	$sub2 = null;
    	$sub3 = null;
    	
    	$datos = Resol::Imprimir($id,$sub1,$sub2,$sub3);
    	
    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/resol',['datos'=>$datos,'sub1'=>$sub1,'sub2'=>$sub2,'sub3'=>$sub3]);        
        
        return $pdf->render();
    }
    		    
	protected function mensajes($m){
		switch($m){

			case 1: return 'Datos grabados correctamente';
		}

		return '';
	}
}
