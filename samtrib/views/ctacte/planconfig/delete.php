<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\PlanConfig */

$title = 'Eliminar plan ' . $model->cod;
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Configuración de convenios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="plan-config-delete">

    <?php
    echo $this->render('_form', [
        'model' => $model,
        'consulta' => 2,
        'extras' => $extras,
        'titulo' => $title
    ]) ?>

</div>
