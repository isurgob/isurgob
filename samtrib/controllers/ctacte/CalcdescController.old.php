<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\CalcDesc;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\bootstrap\Nav;
use app\utils\db\utb;

/**
 * CalcDescController implements the CRUD actions for CalcDesc model.
 */
class CalcDescController extends Controller
{

	
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','get'],
                    ]

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


	public function getDataProvider(){
		
		$sql = "select d.desc_id, d.trib_id, d.anual,d.aplicavenc, d.pagodesde, d.pagohasta,";
		$sql .= "(substr(cast(perhasta as varchar), 1, 4) || '-') || substr(cast(perhasta as varchar),5, 3) as perhasta,";
		$sql .= "(substr(cast(perdesde as varchar), 1, 4) || '-') ||substr(cast(perdesde as varchar), 5, 3) as perdesde,";
        $sql .= "substr(cast(perdesde as varchar), 1, 4) as aniodesde,";
		$sql .= "substr(cast(perdesde as varchar), 5, 2) as cuotadesde, ";
        $sql .= "substr(cast(perhasta as varchar), 1, 4) as aniohasta,";
		$sql .= "substr(cast(perhasta as varchar), 5, 2) as cuotahasta, ";
        $sql .= "montodesde, montohasta, verificadeuda, existedeuda, verificadebito, verificaexen,";
        $sql .= "desc1, desc2, d.cta_id, c.nombre as cta_nom,";
        $sql .= "case when verificadeuda=0 then '' when existedeuda=0 then 'buen pagador' when existedeuda=1 then 'todo pago' when existedeuda=2 then 'con deuda' else '' end as existedeudadescr,";
        $sql .= "u.nombre || ' - ' || to_char(d.fchmod,'dd/mm/yyyy') as modif";
        
        $sql2 = " from calc_desc d left join trib t on d.trib_id = t.trib_id";
        $sql2 .= " left join sam.sis_usuario u on d.usrmod = u.usr_id ";
        $sql2 .= " left join cuenta c on c.cta_id = d.cta_id ";
        
         
        $count = Yii::$app->db->createCommand('Select count(*) ' . $sql2)->queryScalar();
		
		return new SqlDataProvider([
		
			'sql' => $sql . $sql2,
            'key'=>'desc_id',
            'totalCount' => (int)$count,
			'pagination'=> [
			'pageSize' => 5,
			],
		]);
			
	}
    
    /**
     * Lists all CalcDesc models.
     * @return mixed
     */
    public function actionIndex( $men = 0 )
    {
    	
 		$dataProvider = $this->getDataProvider();

        return $this->render('index', [
        	'mensaje' => $this->getMensaje( $men ),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new CalcDesc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {	
        $model = new CalcDesc();
        
        if (isset($error) == null) $error = ''; 
        
       if ($model->load(Yii::$app->request->post())) 
       {
       		if ($model->grabar())
       		{
            	return $this->redirect(['index',
							'men' => 1]);
        	} 
       }        		        	        	        	 

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CalcDesc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id); 

        if ($model->load(Yii::$app->request->post()))
        {        
    		if ($model->grabar())
    		{
            	return $this->redirect(['index',
					'men' => 1]);
       		} 
        }

	    return $this->render('update', [
	        'model' => $model,
		]);
    }

    /**
     * Deletes an existing CalcDesc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $desc_id
     * @param integer $accion Variable que en caso de ser 0, no ejecuta nada en la BD, y si es 1, elimina el dato referenciado por $desc_id
     * @return mixed
     */
    public function actionDelete($id, $accion)
    {

    	$model = $this->findModel($id);
    	
		if($accion == 1 and $model->borrar())
		{	
			return $this->redirect(['index',
					'men' => 1]);			
	
		} else {
			
			return $this->render('delete', [
				'model' => $model,
				]);
		}
    }
    
    private function getMensaje( $men )
    {
    	switch( $men )
    	{
    		case 1:
    			
    			$mensaje = 'Los datos se grabaron correctamente.';
    			break;
    		
    		default:
    			
    			$mensaje = '';
    	}
    	
    	return $mensaje;
    }

    /**
     * Finds the CalcDesc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CalcDesc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CalcDesc::findOne($id)) !== null) {
        	
        	$model->aniodesde = substr($model->perdesde,0,4);
        	$model->cuotadesde = substr($model->perdesde,4,3);
        	        	
        	$model->aniohasta = substr($model->perhasta,0,4);
        	$model->cuotahasta = substr($model->perhasta,4,3);
			
			$model->modif = utb::getFormatoModif($model->usrmod,$model->fchmod);
        	
            return $model;
            
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
