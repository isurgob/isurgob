<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\caja\Caja */

$title = 'Nueva Caja';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => '/samtest/index.php?r=site/config'];
$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="row">
	<!-- 
				***** Centrado ********
		<div class="col-xs-8 col-xs-offset-2"> 
	-->
	<div class="col-xs-8">
		
			<div class="caja-create">
		
				<h1><?= Html::encode($title) ?></h1>
			    <?= $this->render('_form', [
			        'model' => $model,
			        'consulta'=>0,
					'error' => $error,
			    ]) ?>
			
		</div>
	</div>
</div>
