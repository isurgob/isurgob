<?php

namespace app\controllers\config;

use Yii;
use app\models\config\Rubro;
use app\models\config\RubroVigencia;
use app\models\config\VigenciaGeneral;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\utils\db\utb;

/**
 * RubroController implements the CRUD actions for Rubro model.
 */
class RubroController extends Controller
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
    
    public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
		
		$permitirSiempre = ['config-rubro-exportar', 'config-rubro-imprimir'];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
    	
    	return true;
    }
    
    private function extras($model, $consulta = 1){
    	
    	$modelVigencia= new RubroVigencia();
    	$modelVigencia->rubro_id= $model->rubro_id;
    	
    	$cargarVigencia= filter_var(Yii::$app->request->get('cargarVigencia', false), FILTER_VALIDATE_BOOLEAN);
    	$accion= intval(Yii::$app->request->get('accion', 0));
    	
    	if($cargarVigencia && $accion > 0){
    		
    		$perdesde= intval(Yii::$app->request->get('perdesde', 0));
    		$perhasta= intval(Yii::$app->request->get('perhasta', 0));
    		
    		$modelVigencia= $model->vigencia($perdesde, $perhasta);
    	}
    	
    	$ret= [];
    	
    	$ret['model']= $model;
    	$ret['modelVigencia']= $modelVigencia;
    	$ret['consulta']= $consulta;
    	$ret['dpVigencias']= new ArraydataProvider(['allModels' => $model->vigencias(), 'pagination' => false]);
    	
    	return $ret;
    } 
	
	public function actionExportar(){
	
		$filtroNomec= trim(Yii::$app->request->post('fm', ''));
		$filtroNombre= trim(Yii::$app->request->post('fn', ''));
		$filtroGrupo= trim(Yii::$app->request->post('fg', ''));
		$filtroCodigo= trim(Yii::$app->request->post('fc', ''));
		
		$rubros = Rubro::todos( $filtroNomec, $filtroNombre, $filtroGrupo, $filtroCodigo );
		
		return json_encode([ 'datos' => $rubros, 'campos_desc' => 'Rubro, Nombre, Grupo, TCalculo, TMinimo, Fijo Alicuota, Minimo' ]);
	}
	
	public function actionImprimir( $fm, $fn, $fg, $fc){
	
		$rubros = Rubro::todos( $fm, $fn, $fg, $fc );
		
		$cond = '';
		
		if ( $fm !== 0)
			$cond .= "-Nomeclador: " . utb::getCampo("rubro_tnomen","nomen_id='".$fm."'");
		
		if ( $fn !== '')
			$cond .= "<br>-Nombre contiene '$fn'";
		
		if ( $fg !== '')
			$cond .= "<br>-Grupo " . utb::getCampo("rubro_grupo","cod='".$fg."'");
		
		if ( $fc !== '')
			$cond .= "<br>-Código $fc";
		
		$datos['dataProviderResultados'] = new ArraydataProvider(['allModels' => $rubros, 'pagination' => false]);
		
		$datos['descripcion'] = $cond;
		
		$pdf = Yii::$app->pdf;
      	$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/reportelistado', ['datos' => $datos, 'titulo' => 'Listado de Rubros' ]);

		return $pdf->render();
	}

    /**
     * Lists all Rubro models.
     * @return mixed
     */
    public function actionIndex($id = 0)
    {    	
    	$model= Rubro::findOne(['rubro_id' => $id]);
    	if($model === null) $model= new Rubro();
    	
    	$modelVigencia= $model->vigencia();
    	if($modelVigencia === null) $modelVigencia= new RubroVigencia();

		//filtros
		$filtroNomec= trim(Yii::$app->request->get('fm', ''));
		$filtroNombre= trim(Yii::$app->request->get('fn', ''));
		$filtroGrupo= trim(Yii::$app->request->get('fg', ''));
		$filtroCodigo= trim(Yii::$app->request->get('fc', ''));
		
		$rubros= Rubro::todos($filtroNomec, $filtroNombre, $filtroGrupo,$filtroCodigo);
    	$cantidad= count($rubros);
    	
    	$dpRubros= new ArrayDataProvider([
    		'allModels' => $rubros,
    		'key' => 'rubro_id',
    		'pagination' => [
    			'pageSize' => 50
    		],
    		'sort' => [
    			'attributes' => ['nombre','rubro_id'],
    			'defaultOrder' => ['nombre' => SORT_ASC]
    		]
    	]);

    	return $this->render('index', [
					'dpRubros' => $dpRubros, 
					'model' => $model, 
					'modelVigencia' => $modelVigencia,
					'filtroNomec' => $filtroNomec,
					'filtroNombre' => $filtroNombre,
					'filtroGrupo' => $filtroGrupo,
					'filtroCodigo' => $filtroCodigo
		]);
    }

    /**
     * Displays a single Rubro model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $m = 0){
    	
    	$m= intval($m);
    	
    	$model= Rubro::findOne(['rubro_id' => $id]);
    	if($model === null) $model= new Rubro();
    	
    	$extras= $this->extras($model, 1);
    	$extras['mensaje']= $this->mensajes($m);
    	
    	return $this->render('_form', $extras);
    }

    /**
     * Creates a new Rubro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     
    public function actionCreate(){
    	
        $model = new Rubro();

		if(Yii::$app->request->isPost){
			
			$model->setScenario('insert');
			if($model->load(Yii::$app->request->post()) && $model->grabar())
				return $this->redirect(['view', 'id' => $model->rubro_id, 'm' => 1]);
		}
		
        
        $extras= $this->extras($model, 0);
        return $this->render('_form', $extras);
    }

    /**
     * Updates an existing Rubro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $m = 0){
    	
    	$m= intval($m);
    	$model= Rubro::findOne(['rubro_id' => $id]);
    	
    	if(Yii::$app->request->isPost && $model !== null){
    		
    		$model->setScenario('update');
    		if($model->load(Yii::$app->request->post()) && $model->grabar())
    			return $this->redirect(['view', 'id' => $model->rubro_id, 'm' => 1]);
    	}
    	
    	if($model === null) $model= new Rubro();
    	$extras= $this->extras($model, 3);
    	$extras['mensaje']= $this->mensajes($m);
    	
    	return $this->render('_form', $extras);
    }

    /**
     * Deletes an existing Rubro model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id){
    	
    	$model= Rubro::findOne(['rubro_id' => $id]);
    	
    	if(Yii::$app->request->isPost && $model !== null){
    		
    		$model->setScenario('delete');
    		if($model->load(Yii::$app->request->post()) && $model->borrar())
    			return $this->redirect(['view', 'id' => $model->rubro_id, 'm' => 1]);
    	}
    	
    	if($model === null) $model= new Rubro();
    	$extras= $this->extras($model, 2);
    	
    	return $this->render('_form', $extras);
    }
    
    public function actionIndex_vig_general($m=0){
    	
        $dataProvider = new ActiveDataProvider([
            'query' => VigenciaGeneral::find(),
        ]);
		
        return $this->render('index_vig_general', [
            'dataProvider' => $dataProvider,
        ]);
    }

	 public function actionVig_general(){
    		//echo "1";
    		//exit();
    	    return $this->redirect(['index_vig_general']);
    }
	
	public function actionActualizarmasiva(){
	
		$model = new RubroVigencia();
		$nomen_id = Yii::$app->request->post( 'nomen_id', '0' );
		$grupo = Yii::$app->request->post( 'grupo', '0' );
		$rubro_desde = Yii::$app->request->post( 'rubro_desde', '0' );
		$rubro_hasta = Yii::$app->request->post( 'rubro_hasta', '0' );
		
		$dpRubros = $model->getRubrosVigencia( $nomen_id, $grupo, $rubro_desde, $rubro_hasta );
		
		return $this->render( 'actualizarmasiva', [
					'nomecladores' => utb::getAux( "rubro_tnomen", "nomen_id" ),
					'formulas' => utb::getAux( "rubro_tfcalculo" ),
					'minimos' => utb::getAux( "rubro_tminimo" ),
					'grupos' => utb::getAux('rubro_grupo'),
					'dpRubros' => $dpRubros,
					'model' => $model,
					'mensaje' => Yii::$app->session->getFlash( 'mensaje', '' ),
				]);
	}
	
	public function actionActualizarmasivagrabar(){
	
		$model = new RubroVigencia();
		
		if ( $model->validarVigenciaMasiva( $_POST ) and $model->grabarVigenciaMasiva( $_POST ) ){
			Yii::$app->session->setFlash( 'mensaje', "Los datos se grabaron correctamente" );
			return $this->redirect(['actualizarmasiva']);
		}else 
			$devolver = ['error' => $model->getErrors()];
		
		return json_encode($devolver);
	}

    private function mensajes($m){
    	
    	switch ($m) {
    		case 1: return 'Datos grabados correctamente'; 
    	}
    	
    	return null;
    }
    
}
