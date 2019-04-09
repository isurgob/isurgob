<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcMulta */

$this->title = 'Eliminar Multa: ' . ' ' . $model->trib_id;
$this->params['breadcrumbs'][] = ['label' => 'Multas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Eliminar';
?>
<div class="multa-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="form">
    	<?= $this->render('_form', [
        	'model' => $model,'consulta' => 2
    	]) ?>
    	<br>
	</div>
</div>
