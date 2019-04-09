<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctace\CalcDesc */

$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Descuentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Modificar Descuento';

?>
<h1><?= Html::encode('Modificar Descuento') ?></h1>
		
	
	    <?= $this->render('_form', [
	        'model' => $model,
	        'consulta' => 3,
	    ]) ?>
