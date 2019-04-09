<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcMm */

$this->title = 'Consulta Calc Mm ' . ' ' . $model->fchdesde;
$this->params['breadcrumbs'][] = ['label' => 'Calc Mms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calc-mm-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'fchdesde' => $model->fchdesde, 'fchhasta' => $model->fchhasta], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'fchdesde' => $model->fchdesde, 'fchhasta' => $model->fchhasta], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea eliminar ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
		'consulta' => 1,
    ]) ?>    



</div>
