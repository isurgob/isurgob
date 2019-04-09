<?php

namespace app\controllers\config;

use Yii;
use app\models\config\Texto;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\utils\db\utb;

/**
 * TextoController implements the CRUD actions for Texto model.
 */
class TextoController extends Controller
{
	private $model;
	private $filtroUso;
	
	 public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
    	
    	return true;
    } 
	
	private function getExtras($id = 0){
		
		$this->model = new Texto();
		$variables = [];
		
		if($id > 0) $this->model = Texto::findOne($id);
		if($this->model == null) $this->model = new Texto();

		$this->filtroUso =  Yii::$app->request->get('tuso', '');
		
		$ret['tuso'] = $this->filtroUso;
		
		$tuso = intval(Yii::$app->request->get('tuso', -1));
		
		if($tuso > -1 && $this->model->tuso <= 0) $this->model->tuso = $tuso;

		$ret['model'] = $this->model;
		$ret['textosModificables'] = Texto::getModificables();
		$ret['variables'] = $this->model->getVariables($this->model->tuso);

		return $ret;
	}
	
    /**
     * Lists all Texto models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$tuso = intval(Yii::$app->request->get('tuso', 0));
    	$texto_id = intval(Yii::$app->request->get('texto', -1));
    	
    	$texto = $texto_id === -1 ? '' : Texto::getTexto($texto_id);
    	
    	$extras = [];
    	$extras['dpTextos'] = new ArrayDataProvider(['allModels' => Texto::getTextosTodos($tuso)]);
    	$extras['textosModificables'] = Texto::getModificables();
    	$extras['texto'] = $texto;
    	
    	return $this->render('index', ['extras' => $extras]);
    }

    /**
     * Displays a single Texto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $m = '')
    {
    	
    	$extras = $this->getExtras($id);
    	$extras['consulta'] = 1;
    	
    	$mensaje = $this->getMensaje(intval($m));
    	
    	
    	
    	return $this->render('_form', ['extras' => $extras, 'mensaje' => $mensaje]);
    }

    /**
     * Creates a new Texto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$extras = $this->getExtras(0);
    	$extras['consulta'] = 0;
    	
    	
    	if(Yii::$app->request->isPost){
    		
    		$this->model = new Texto();
    		$this->model->setScenario('insert');
    		
    		if($this->model->load(Yii::$app->request->post())){

    			$res = $this->model->grabar();
    			
    			if($res) return $this->redirect(['view', 'id' => $this->model->texto_id, 'm' => 1]);
    			
    			$extras['model'] = $this->model;
    		}
    	}
    	
    	return $this->render('_form', ['extras' => $extras]);
    }

    /**
     * Updates an existing Texto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	$extras = $this->getExtras($id);
    	$extras['consulta'] = 3;
    	
    	
    	if(Yii::$app->request->isPost){
    		
    		$this->model->setScenario('update');
    		
    		if($this->model->load(Yii::$app->request->post()) && !$this->model->hasErrors() && $this->model->validate()){
    			
    			$this->model = Texto::findOne($this->model->texto_id);
    			$this->model->setScenario('update');
    			$this->model->load(Yii::$app->request->post());
    			//return var_dump($this->model);
    			$res = $this->model->grabar();
    			
    			if($res) return $this->redirect(['view', 'id' => $this->model->texto_id, 'm' => 1]);
    			
    			$extras['model'] = $this->model;
    		}
    	}
    	
    	
    	
    	$extras['consulta'] = 3;
    	
    	return $this->render('_form', ['extras' => $extras]);
    }

    /**
     * Deletes an existing Texto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$extras = $this->getExtras($id);    	
    	
    	if(Yii::$app->request->isPost){
    		
    		$this->model->setScenario('delete');
    		
    		if($this->model->load(Yii::$app->request->post())){
    			
    			$this->model = Texto::findOne($this->model->texto_id);
    			
    			$res = $this->model->borrar();
    			
    			if($res) return $this->redirect(['view', 'id' => '', 'm' => 1]);
    			
    			$extras['model'] = $this->model;
    		}
    	}

    	$extras['consulta'] = 2;
    	
    	return $this->render('_form', ['extras' => $extras]);
    }
    
    private function getMensaje($m = 0){
    	$ret = '';
    	
    	switch($m){
    		case 1: return 'Datos grabados correctamente'; break;
    	}
    	
    	return $ret;
    }
}
