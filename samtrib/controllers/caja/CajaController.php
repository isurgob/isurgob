<?php

namespace app\controllers\caja;

use Yii;
use app\models\caja\Caja;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;

/**
 * CajaController implements the CRUD actions for Caja model.
 */
class CajaController extends Controller
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
     * Lists all Caja models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model          = new Caja();
        $tesoreria      = '';
        $soloactivas    = '';
        $tesorerias     = Caja::obtenerTesoreriasSegunUsuario( Yii::$app->user->id );

        if( Yii::$app->request->isPjax ){

            if( Yii::$app->request->get( '_pjax', '' ) == '#idGrilla' ){

                $tesoreria    = Yii::$app->request->get('tesoreria', '');
                $soloactivas  = Yii::$app->request->get( 'soloactivas', '');
            }

        }

        return $this->render('index',[

            'tesorerias'    => $tesorerias,

            'dpCajas'   => new ArrayDataProvider([
                'allModels' => $model->buscaCaja( $tesoreria, $soloactivas ),
                'key' => 'caja_id',
                'sort' => [
                    'attributes' => [ 'caja_id', 'nombre' ],
                ],
            ]),
        ]);
    }

    /**
     * Displays a single Caja model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Caja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Caja();

        if (isset($error) == null) $error = '';

        if ($model->load(Yii::$app->request->post()))
        {

            if ( $model->grabar() ){

                return $this->redirect(['index',
        		      'a'=>'create',
        		]);

            } else {

                $model->asignarMediosDePagoDeUsuario(); //Función que genera el array de medios de pago

       		}

    	}

		return $this->render('create', [
            'model' => $model,
            'error' => $model->getError(),
        ]);

    }

    /**
     * Updates an existing Caja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate( $id )
    {
        $model = $this->findModel( $id );

        if ($model->load(Yii::$app->request->post()))
        {
            if ($model->grabar()){

                return $this->redirect(['index',
            		'a' => 'update'
            	]);

            } else {

                $model->asignarMediosDePagoDeUsuario(); //Función que genera el array de medios de pago

        	}
        }

		return $this->render('update', [
            'model' => $model,
            'error' => $model->getError(),
    	]);

    }

    /**
     * Deletes an existing Caja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $accion)
    {
    	 $model = $this->findModel($id);

		if($accion == 1)
        {
           	if ($model->borrar())
			{
				return $this->redirect(['index',
				'a' => 'delete'
				]);

			} else {

				return $this->render('delete', [
					'model' => $model,
					'error' => $model->getError(),
					]);
			}

		} else {

			return $this->render('delete', [
				'model' => $model,
				'error' => $model->getError(),
				]);
		}

    }
	
	

    /**
     * Finds the Caja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Caja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Caja::findOne($id)) !== null) {

        	if ($model->teso_id == null)
        		$model->teso_id = 0;

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
