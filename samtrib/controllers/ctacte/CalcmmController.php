<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\CalcMm;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;
use app\utils\db\Fecha;
/**
 * CalcmmController implements the CRUD actions for CalcMm model.
 */
class CalcmmController extends Controller
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
     * Lists all CalcMm models.
     * @return mixed
     */
    public function actionIndex( $mensaje = '', $m = 1 )
    {
    	$model = new CalcMm();
    	
    	$dataProvider = $model->obtenerDatos();
        
        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'mensaje' => $this->getMensaje( $mensaje ),
            'm' => $m,
        ]);
    }

    /**
     * Displays a single CalcMm model.
     * @param string $fchdesde
     * @param string $fchhasta
     * @return mixed
     */
     /*Muestra el juego de pesta�as (Datos) y (Calcular)*/
    /*public function actionView($fchdesde, $fchhasta)
    {

    	
        return $this->render('view', [
            'model' => $this->findModel($fchdesde, $fchhasta),
            
        ]);
    }*/

    /**
     * Creates a new CalcMm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CalcMm();
        
        if ($model->load(Yii::$app->request->post())) {
        
        	if ( $model->grabar() )
        	{
        		return $this->redirect(['index', 'mensaje' => 1, 'm' => 1 ]);
        		
        	} else 
        	{
        		//Acomodar las fechas
        		$model->fchdesde = Fecha::usuarioToDatePicker( $model->fchdesde );
        		$model->fchhasta = Fecha::usuarioToDatePicker( $model->fchhasta );
        		
        	}
        	
        }
         
     	return $this->render('create', [
			'model' => $model,
		]);
    }

    /**
     * Updates an existing CalcMm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $fchdesde
     * @param string $fchhasta
     * @return mixed
     */
    public function actionUpdate($fchdesde, $fchhasta)
    {
        
        $model = $this->findModel($fchdesde, $fchhasta);
        
        if ( $model->load( Yii::$app->request->post() ) ) 
        {
        	
        	if ( $model->grabar() )
        	{
           		return $this->redirect(['index', 'mensaje' => 1, 'm' => 1]);
           		
        	} else
        	{
        		//Acomodar las fechas
//        		$model->fchdesde = Fecha::usuarioToDatePicker( $model->fchdesde );
//        		$model->fchhasta = Fecha::usuarioToDatePicker( $model->fchhasta );
        	}
        }
         	
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CalcMm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $fchdesde
     * @param string $fchhasta
     * @return mixed
     */
    public function actionDelete($fchdesde, $fchhasta)
    {
        
        if ( $this->findModel($fchdesde, $fchhasta)->delete() )

        	return $this->redirect(['index','mensaje' => 1, 'm' => 2]);
        
        else
        	
        	return $this->redirect(['index','mensaje' => 2, 'm' => 2]);
    }
    
    /**
     * Función que se utiliza para obtener los mensajes en alertas.
     */
    private function getMensaje( $id )
    {
    	$men = '';
    	
    	switch ( $id )
    	{
    		case 1: 
    			
    			$men = 'Datos grabados correctamente.';
    			break;
    		
    		case 2:
    			
    			$men = 'Ocurrió un error al grabar los datos.';
    			break;
    		
    		default:
    			
    			$men = '';
    	}
    	
    	return $men;
    }

    /**
     * Finds the CalcMm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $fchdesde
     * @param string $fchhasta
     * @return CalcMm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($fchdesde, $fchhasta)
    {
        if (($model = CalcMm::findOne(['fchdesde' => $fchdesde, 'fchhasta' => $fchhasta])) !== null) {
        	
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
