<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rodado Aforos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rodado-aforo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo/a Rodado Aforo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'anioval',
            'origen_id',
            'marca_id',
            'tipo_id',
            'modelo_id',
            // 'anio',
            // 'valor',
            // 'fchmod',
            // 'usrmod',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
