<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Saldoneg;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;

/**
 * SaldonegController implements the CRUD actions for saldoneg model.
 */
class SaldonegController extends Controller
{
    
    public function beforeAction($action)
	{
		$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
//	    if (!utb::getExisteAccion($operacion)) {
//	        echo $this->render('//site/nopermitido');
//	        return false;
//	    }
		
		return true;
	}

    /**
     * Displays a single rete model.
     * @param integer $id
     * @return mixed
     */
    public function actionView( )
    {
    			
        return $this->render('_form', [

        ]);
    }

   
    public function actionImprimir($id)
    {
    	$sub1 = null;
    	$sub2 = null;
    	$array = (new Rete)->Imprimir($id,$sub1,$sub2);
    	    	
    	$pdf = Yii::$app->pdf;
		$pdf->content = $this->renderPartial('//reportes/rete',['datos' => $array,'sub1'=>$sub1,'sub2'=>$sub2]);        
        
        return $pdf->render();	
    }

    /**
     * Finds the rete model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return rete the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = rete::findOne($id)) !== null) {
        	$model->cargarDatos($id);
            return $model;
        } else {
            $model = new Rete();
            return $model;
        }
    }
}
