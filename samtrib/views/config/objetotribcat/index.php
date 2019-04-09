<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Objeto Trib Cats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objeto-trib-cat-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo/a Objeto Trib Cat', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'trib_id',
            'cat',
            'nombre',
            'fchmod',
            'usrmod',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
