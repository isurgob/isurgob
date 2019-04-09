<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\caja\Caja */

$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Consulta Caja ' . ' ' . $model->caja_id;;
?>
<div class="caja-view">

    <h1><?= Html::encode('Consulta Caja ' . ' ' . $model->caja_id;) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->caja_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->caja_id], [
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
