<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\PlanConfig */


$title = 'Modificar plan ' . $model->cod;
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Configuración de convenios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="plan-config-update">

    <?= $this->render('_form', [
        'model' => $model,
        'consulta' => 3,
        'extras' => $extras,
        'titulo' => $title
    ]) ?>

</div>
