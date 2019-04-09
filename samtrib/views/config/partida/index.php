<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Partidas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partida-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo/a Partida', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'part_id',
            'grupo',
            'subgrupo',
            'rubro',
            'cuenta',
            // 'subcuenta',
            // 'conc',
            // 'subconc',
            // 'formato',
            // 'formatoaux',
            // 'nombre',
            // 'padre',
            // 'nivel',
            // 'bcocta_id',
            // 'est',
            // 'fchmod',
            // 'usrmod',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
