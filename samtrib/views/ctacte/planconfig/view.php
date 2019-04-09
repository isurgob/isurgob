<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\PlanConfig */

$title = 'Consulta plan de configuración';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Configuración de convenios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="plan-config-view">    

	<h1><b>Consulta plan <?= $model->cod; ?></b></h1>
    <?= $this->render('_form', [
        'model' => $model,
		'consulta' => 1,
        'extras' => $extras,
        'title' => $title
    ]) ?>    



</div>
