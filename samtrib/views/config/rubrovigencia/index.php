<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Rubro Vigencias';
$this->params['breadcrumbs'][] = $title;
?>
<div class="rubro-vigencia-index">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('Nuevo/a Rubro Vigencia', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rubro_id',
            'perdesde',
            'perhasta',
            'tcalculo',
            'tminimo',
            // 'alicuota',
            // 'alicuota_atras',
            // 'minimo',
            // 'minalta',
            // 'fijo',
            // 'canthasta',
            // 'porc',
            // 'fchmod',
            // 'usrmod',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
