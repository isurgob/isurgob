<?php

ini_set("display_errors", "on");
error_reporting(E_ALL);

use yii\helpers\Html;
use yii\grid\GridView;
?>

<div class="tirbuto-items">
	<?php
	echo Html::a('Consultar', ['//ctacte/item/index', 'trib_id' => $model->trib_id], ['class' => 'btn btn-success pull-right', 'style' => 'margin-top:5px; margin-bottom:5px;']);
	
	echo GridView::widget([
		'dataProvider' => $dpItems,
		'summary' => false,
		'headerRowOptions' => ['class' => 'grillaGrande'],
		'rowOptions' => ['class' => 'grillaGrande'],
		'columns' => [
			['attribute' => 'item_id', 'label' => 'Código', 'options' => ['style' => 'width:1%;']],
			['attribute' => 'nombre', 'label' => 'Nombre']
		]
	]);
	?>
</div>