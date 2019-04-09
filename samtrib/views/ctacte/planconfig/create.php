<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\PlanConfig */

$title = 'Nuevo plan';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Configuración de convenios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="plan-config-create">

    <?php
    echo $this->render('_form', [
        'model' => $model,
        'consulta' => 0,
        'extras' => $extras,
        'titulo' => $title
    ]) ?>

</div>
