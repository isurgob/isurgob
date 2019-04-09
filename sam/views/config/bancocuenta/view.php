<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\caja\BancoCuenta */

$this->title = 'Consulta Banco Cuenta ' . ' ' . $model->bcocta_id;
$this->params['breadcrumbs'][] = ['label' => 'Banco Cuentas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banco-cuenta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->bcocta_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->bcocta_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea eliminar ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bcocta_id',
            'bco_ent',
            'bco_suc',
            'bco_tcta',
            'bco_cta',
            'titular',
            'fchmod',
            'usrmod',
        ],
    ]) ?>

</div>
