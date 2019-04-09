<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\caja\BancoCuenta */

$this->title = 'Modificacion Banco Cuenta: ' . ' ' . $model->bcocta_id;
$this->params['breadcrumbs'][] = ['label' => 'Banco Cuentas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bcocta_id, 'url' => ['view', 'id' => $model->bcocta_id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="banco-cuenta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        
    ]) ?>

</div>
