<?php

namespace app\controllers\config;

use Yii;
use app\models\config\Feriado;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;

/**
 * FeriadoController implements the CRUD actions for Feriado model.
 */
class FeriadoController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
     * Lists all Feriado models.
     * @return mixed
     */
    public function actionIndex()
    {
       $dataProvider = new ActiveDataProvider([
            'query' => Feriado::find()->where('extract(year from fecha)='.date('Y')),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,'consulta'=> 1,'fecha'=>null
        ]);
         
    }


    /**
     * Creates a new Feriado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $fecha = (isset($_POST['Fecha']) && $_POST['Fecha'] != null ? $_POST['Fecha'] : null);
        $model = Feriado::findOne($fecha);
     	Yii::$app->session['error'] = '';
        Yii::$app->session['msj'] = '';
        if ($model == null)
        {
	        $model = new Feriado();
	       
	        $model->fecha = (isset($_POST['Fecha']) && $_POST['Fecha'] != null ? $_POST['Fecha'] : null);
	        $model->detalle = (isset($_POST['txDetalle']) &&  $_POST['txDetalle'] !='' ? $_POST['txDetalle'] : '');
	        if ($model->fecha == null) Yii::$app->session['error'] .= '<li>Ingrese una Fecha</li>';
	        if (date('Y') != substr($model->fecha,-4,4)) Yii::$app->session['error'] .= '<li>Solo se permiten feriados del año actual</li>';
	        if ($model->detalle == '') Yii::$app->session['error'] .= '<li>Ingrese un Detalle</li>';
	    }else {
        	Yii::$app->session['error'] = 'Ya existe un Feriado esa Fecha';
        }    
        if (Yii::$app->session['error'] == '')  
        {
        	$model->usrmod = Yii::$app->user->id;
        	date_default_timezone_set('America/Argentina/Buenos_Aires');
        	$model->fchmod = date('d/m/Y h:i:s a');
        	
        	Yii::$app->session['error'] = ($model->save() == 0 ? 'Error al grabar en la base' : '');
        	if (Yii::$app->session['error'] == '') Yii::$app->session['msj'] = 'Datos Grabados'; 
        } 
        if (Yii::$app->session['error'] != '') 
        {
        	$dataProvider = new ActiveDataProvider([
            	'query' => Feriado::find()->where('extract(year from fecha)='.date('Y')),
        	]);
        	return $this->render('index',['dataProvider' => $dataProvider,'consulta'=>0,'fecha'=>$model->fecha]) ;
        }
   
        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Feriado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate()
    {
        Yii::$app->session['error'] = '';
        Yii::$app->session['msj'] = '';
        
	    $fecha = (isset($_POST['Fecha']) && $_POST['Fecha'] != null ? $_POST['Fecha'] : null);
		$detalle = (isset($_POST['txDetalle']) &&  $_POST['txDetalle'] !='' ? $_POST['txDetalle'] : '');
	    if ($detalle == '') Yii::$app->session['error'] .= '<li>Ingrese un Detalle</li>';
        
        if (Yii::$app->session['error'] == '')  
        {
        	$model = $this->findModel($fecha);
	    	$model->detalle = $detalle;
        	$model->usrmod = Yii::$app->user->id;
        	date_default_timezone_set('America/Argentina/Buenos_Aires');
        	$model->fchmod = date('d/m/Y h:i:s a');
        	        	
        	Yii::$app->session['error'] = ($model->save() == 0 ? 'Error al grabar en la base' : '');
        	if (Yii::$app->session['error'] == '') Yii::$app->session['msj'] = 'Datos Grabado'; 
        } 
        if (Yii::$app->session['error'] != '') 
        {
        	$dataProvider = new ActiveDataProvider([
            	'query' => Feriado::find()->where('extract(year from fecha)='.date('Y')),
        	]);
        	return $this->render('index',['dataProvider' => $dataProvider,'consulta'=>3,'fecha'=>$model->fecha]) ;
        }
   
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Feriado model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete()
    {
        $fecha = (isset($_POST['Fecha']) && $_POST['Fecha'] != null ? $_POST['Fecha'] : null);
        $this->findModel($fecha)->delete();
        Yii::$app->session['msj'] = 'Datos Grabado'; 

        return $this->redirect(['index']);
    }

    /**
     * Finds the Feriado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Feriado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feriado::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
