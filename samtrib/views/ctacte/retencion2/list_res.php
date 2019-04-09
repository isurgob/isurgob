<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;

$title= "Resultado";

$this->params['breadcrumbs'][]= ['label' => 'Retenciones', 'url' => ['index']];
$this->params['breadcrumbs'][]= ['label' => 'Listado', 'url' => ['listado']];
$this->params['breadcrumbs'][]= $title;
?>


<div>
	<h1><?= $title; ?></h1>
	<div class="separador-horizontal"></div>

	<div>
	<?= Html::textArea('desccripcion', $descripcion, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:100%;']); ?>


	<?php
	Pjax::begin(['id' => 'pjaxGrilla', 'enableReplaceState' => false, 'enablePushState' => false]);

	echo GridView::widget([

		'dataProvider' => $dataProvider,
		'headerRowOptions' => ['class' => 'grillaGrande'],
		'columns' => [

			['attribute' => 'ret_id', 'label' => 'ID', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'ag_rete', 'label' => 'Agente', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'anio', 'label' => 'Año', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'mes', 'label' => 'Mes', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'numero', 'label' => 'Núm.', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'fecha', 'label' => 'Fecha', 'format' => ['date', 'php:d/m/Y'], 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'lugar', 'label' => 'Lugar', 'contentOptions' => ['class' => 'grillaGrande']],
			['attribute' => 'base', 'label' => 'Base', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
			['attribute' => 'ali', 'label' => 'Alícuota', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
			['attribute' => 'monto', 'label' => 'Monto', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
			['attribute' => 'est', 'label' => 'Est.', 'contentOptions' => ['class' => 'grillaGrande']],
			['class' => '\yii\grid\ActionColumn', 'template' => '{view}&nbsp;{update}&nbsp;{delete}	'

				
			],
		]
		]);



	Pjax::end();
	?>
	</div>

</div>