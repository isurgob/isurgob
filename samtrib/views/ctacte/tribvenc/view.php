<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\TribVenc */

$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Vencimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Consulta Trib Venc ' . ' ' . $model->trib_id;
?>
<div class="trib-venc-view">

    <h1><?= Html::encode('Consulta Trib Venc ' . ' ' . $model->trib_id) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'trib_id' => $model->trib_id, 'anio' => $model->anio, 'cuota' => $model->cuota], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'trib_id' => $model->trib_id, 'anio' => $model->anio, 'cuota' => $model->cuota], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea eliminar ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,'consulta' => 1,
    ]) ?>    



</div>
