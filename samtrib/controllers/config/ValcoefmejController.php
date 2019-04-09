<?php

namespace app\controllers\config;

use Yii;
use app\models\config\ValCoefMej;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;

/**
 * VajmejController implements the CRUD actions for ValMej model.
 */
class ValcoefmejController extends Controller
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
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
		
		return true;
    } 

    /**
     * Lists all ValMej models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$accion = isset($_GET['accion']) ? $_GET['accion'] : 1;
		$cat = isset($_GET['cat']) ? $_GET['cat'] : "";
		$est = isset($_GET['est']) ? $_GET['est'] : 1;
		$ant = isset($_GET['ant']) ? $_GET['ant'] : 1;
		
		$model = $this->findModel($cat,$est,$ant);
    	
        return $this->render('index', [
            'dataProvider' => $this->Coeficientes(),
			'model' => $model,
			'accion' => $accion
        ]);   
    }
	
	public function actionValcoefmejabm()
	{
		$model = new ValCoefMej();
		$accion = Yii::$app->request->post('txAccion',1);
		
		if ($accion == 0)
			$model->scenario = 'insert';
		if ($accion == 3)
			$model->scenario = 'update';	
		if ($accion == 2)
			$model->scenario = 'delete';	
		
		if( $model->load( Yii::$app->request->post() ) && $model->validate() )
			$model->Grabar();
		
		if($model->hasErrors())
			return $this->render('index', [
				'dataProvider' => $this->Coeficientes(),
				'model' => $model,
				'accion' => $accion
			]);   
		else {
			Yii::$app->session->setFlash('mensaje', 'Los datos se grabaron correctamente.' );
			$this->redirect(['index']) ;
		}	
	}
	
	private function Coeficientes()
	{
		$sql = ValCoefMej::find();
		return new ActiveDataProvider([
            'query' => $sql,
            'pagination'=> [
				'pageSize'=>10,
			],
        ]);
	}

    
    /**
     * Finds the ValCoefMej model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $cat
     * @param integer $est
     * @param integer $ant
     * @return ValCoefMej the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cat, $est, $ant)
    {
        if (($model = ValCoefMej::findOne(['cat' => $cat, 'est' => $est, 'ant' => $ant])) !== null) {
            return $model;
        } else {
            return new ValCoefMej();
        }
    }
}
