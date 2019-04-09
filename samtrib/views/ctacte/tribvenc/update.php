<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\TribVenc */

$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Vencimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Actualizar';
?>

<style type="text/css">
.row{
	height : auto;
	min-height : 17px;
}

</style>
	
<h1><?= Html::encode('Modificar Vencimiento: ' . ' ' . $model->trib_id) ?></h1>

	
	    <?= $this->render('_form', [
	        'model' => $model,
	        'consulta' => 3,
	    ]) ?>
