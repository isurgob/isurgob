<?php

namespace app\controllers\config;

use Yii;
use app\models\config\RodadoVal;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;

/**
 * RodadovalController implements the CRUD actions for RodadoVal model.
 */
class RodadovalController extends Controller
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
     * Lists all RodadoVal models.
     * @return mixed
     */
    public function actionValor($m=0)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RodadoVal::find(),
        ]);

        return $this->render('valor', [
            'dataProvider' => $dataProvider,
            'mensaje' => $this->Mensajes($m)
        ]);
    }

    /**
     * Creates a new RodadoVal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RodadoVal();
		if(Yii::$app->request->isPost){
			
			if ($model->load(Yii::$app->request->post())) {
	        	if ($model->validate() && $model->CreateRodadoVal())
            		return $this->redirect(['valor', 'm' => 1]);
        		else
        			return $this->render('valoredit', ['model' => $model, 'consulta' => 0]);
	        } 
		}
		return $this->redirect(['valor']);
    }

    /**
     * Updates an existing RodadoVal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $anioval
     * @param integer $gru
     * @param integer $anio
     * @param string $pesodesde
     * @param string $pesohasta
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = new RodadoVal();
                        
        if ($model->load(Yii::$app->request->post())) {
                    
            if ($model->UpdateRodadoVal())
            	return $this->redirect(['valor', 'm' => 2]);
            
        }
		
		
		return $this->redirect(['valor']);
        
    }

    /**
     * Deletes an existing RodadoVal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $anioval
     * @param integer $gru
     * @param integer $anio
     * @param string $pesodesde
     * @param string $pesohasta
     * @return mixed
     */
    public function actionDelete($anioval, $gru, $anio, $pesodesde, $pesohasta)
    {
        $this->findModel($anioval, $gru, $anio, $pesodesde, $pesohasta)->delete();

        return $this->redirect(['valor','m'=>3]);
    }

    /**
     * Finds the RodadoVal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $anioval
     * @param integer $gru
     * @param integer $anio
     * @param string $pesodesde
     * @param string $pesohasta
     * @return RodadoVal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($anioval, $gru, $anio, $pesodesde, $pesohasta)
    {
        if (($model = RodadoVal::findOne(['anioval' => $anioval, 'gru' => $gru, 'anio' => $anio, 'pesodesde' => $pesodesde, 'pesohasta' => $pesohasta])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function Mensajes($id)
    {
    	switch ($id) {
    		case 1: 
    			$mensaje = 'Datos Grabados';
    			break;
			case 2: 
    			$mensaje = 'Los datos se modificaron correctamente';
    			break;
			case 3: 
    			$mensaje = 'Los datos se eliminaron correctamente';
    			break;
    		default: 
    			$mensaje = '';
    	}
    	
    	return $mensaje;  
    }
}
