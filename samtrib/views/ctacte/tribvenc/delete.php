<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\TribVenc */

$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Vencimiento', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Eliminar Vencimiento';
?>

<style type="text/css">
.row{
	height : auto;
	min-height : 17px;
}

</style>
	
<h1><?= Html::encode('Eliminar Vencimiento') ?></h1>

	    <?= $this->render('_form', [
	        'model' => $model,
	        'consulta' => 2, 
	    ])
	?>
