<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
?>

<div class="tributos-vencimientos">
	<?php
	if (utb::getExisteProceso(3016)) 
		echo Html::a('Consultar', ['//ctacte/tribvenc/index', 'trib_id' => $model->trib_id], ['class' => 'btn btn-success pull-right', 'style' => 'margin-top:5px; margin-bottom:5px;']);
	
	echo GridView::widget([
		'dataProvider' => $dpVencimientos,
		'summary' => false,
		'headerRowOptions' => ['class' => 'grillaGrande'],
		'rowOptions' => ['class' => 'grillaGrande'],
		'columns' => [
			['attribute' => 'cuota', 'label' => 'Cuota'],
			['attribute' => 'fchvenc1', 'label' => 'Fecha 1', 'format' => ['date', 'php:d/m/Y']],
			['attribute' => 'fchvenc2', 'label' => 'Fecha 2', 'format' => ['date', 'php:d/m/Y']]
		]
	]);
	?>
</div>