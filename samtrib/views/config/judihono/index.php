<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Judi Honos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="judi-hono-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo/a Judi Hono', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'instancia',
            'supuesto',
            'deuda_desde',
            'deuda_hasta',
            'hono_min',
            // 'hono_porc',
            // 'gastos',
            // 'fchmod',
            // 'usrmod',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
