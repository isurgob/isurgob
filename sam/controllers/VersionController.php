<?php

namespace app\controllers;

use Yii;
use app\models\ControlVersion;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RubrovigenciaController implements the CRUD actions for RubroVigencia model.
 */
class VersionController extends Controller
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

    /**
     * Lists all RubroVigencia models.
     * @return mixed
     */
    public function actionIndex()
    {
    	
        $dataProvider = new ActiveDataProvider([
            //'query' => ControlVersion::find()->where(['sis_id' =>Yii::$app->session['sis_id']]),
			'query' => ControlVersion::find()->where(['sis_id' => 10]),
        ]);
		
        return $this->render('//site/contrVersion', [
            'dataProvider' => $dataProvider,
        ]);
    }

    
    
}
