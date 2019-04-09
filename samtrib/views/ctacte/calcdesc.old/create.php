<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcDesc */

$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Descuentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Nuevo Descuento';
?>

<h1><?= Html::encode('Nuevo Descuento') ?></h1>
		
	    <?= $this->render('_form', [
	        'model' => $model,
	        'consulta' => 0,
	    ]) ?>
