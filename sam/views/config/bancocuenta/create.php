<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\caja\BancoCuenta */

$this->title = 'Nuevo/a Banco Cuenta';
$this->params['breadcrumbs'][] = ['label' => 'Banco Cuentas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banco-cuenta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
       
    ]) ?>

</div>
