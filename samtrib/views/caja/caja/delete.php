<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcDesc */

$title = 'Eliminar Caja';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => '/samtest/index.php?r=site/config'];
$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

?>
<div class="row">
	<div class="col-xs-8 col-xs-offset-2">
	
		<div class="calc-desc-delete">
		
		    <h1><?= Html::encode($title) ?></h1>
		
		    <?= $this->render('_form', [
		        'model' => $model,
		        'consulta' => 2, 
				'error' => $error,
			 ]) ?>
		</div>
	</div>

</div>
