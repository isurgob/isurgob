<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vigencia';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-vigencia-index">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <p>
        <?php echo Html::a('Nuevo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_id',
            'perdesde',
            'perhasta',
            'tcalculo',
            'monto',
            'porc',
            'minimo',
            'paramnombre1',
            'paramnombre2',
            'paramnombre3',
            'paramnombre4',
            'paramcomp1',
            'paramcomp2',
            'paramcomp3',
            'paramcomp4',
            'fchmod',
            'usrmod',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
