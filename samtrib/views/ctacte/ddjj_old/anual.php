<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

$this->params['breadcrumbs'][] = ['label' => 'Comercio ' . $model->obj_id, 'url' => ['//objeto/comer/view', 'id' => $model->obj_id]];
$this->params['breadcrumbs'][] = 'Carga de DJ Anual';

?>
<style>
#grillaDeclaraciones table{
	width:auto;
}
</style>
<div class="ddjj-anual">

	<h1>Carga de DJ Anual</h1>
	<div style="border-bottom:1px solid #dddddd;"></div>

	<?php
	if(isset($mensaje) && $mensaje !== null && !empty(trim($mensaje))){

		echo Alert::widget([
			'id' => 'alertMensaje',
			'options' => ['class' => 'alert alert-success', 'style' => 'margin-top:5px;'],
			'body' => $mensaje
		]);

		?>
		<script type="text/javascript">
		$(document).ready(function(){
			setTimeout(function(){$("#alertMensaje").fadeOut();}, 5000);
		});
		</script>
		<?php
	}
	?>

	<div class="form" style="padding:5px; margin-top:10px;">
		<table width="100%" border="0">
			<tr>
				<td><b>Objeto:</b></td>
				<td width="5px"></td>
				<td><?= Html::textInput(null, $model->obj_id, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:70px;']); ?></td>
				<td width="10px"></td>
				<td><b>Dato:</b></td>
				<td width="5px"></td>
				<td><?= Html::textInput(null, $model->obj_dato, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:100px;']); ?></td>
				<td width="10px"></td>
				<td><b>Nombre:</b></td>
				<td width="5px"></td>
				<td colspan="2"><?= Html::textInput(null, $model->nombre, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:440px;']); ?></td>
			</tr>
		</table>

		<div class="text-right" style="margin-top:5px;">

			<?php
			//si no hay objeto cargado, se muestra el boton para generar. En caso contrario, se muestra el boton para agregar.
			if($model->obj_id === null || trim($model->obj_id) === '')
				echo Html::button('Generar', ['class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#modalGenerar']);
			else echo Html::button('Agregar', ['class' => 'btn btn-success', 'onclick' => 'mostrarDeclaracion(-1, 0);']);
			?>
		</div>

		<div style="margin-top:5px;">
			<?php
			Pjax::begin(['id' => 'pjaxGrillaDeclaraciones', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'width:50%; margin-left:auto; margin-right:auto;']]);

			echo GridView::widget([
				'dataProvider' => $dpAnual,
				'id' => 'grillaDeclaraciones',
				'summary' => false,
				'headerRowOptions' => ['class' => 'grillaGrande'],
				'rowOptions' => ['class' => 'grillaGrande'],
				'columns' => [
					['attribute' => 'anio', 'label' => 'Año', 'options' => ['style' => 'width:1%;']],
					['attribute' => 'fchpresenta', 'label' => 'Fecha', 'format' => ['date', 'php:d/m/Y'], 'options' => ['style' => 'width:1%;']],
					['attribute' => 'base', 'label' => 'Base', 'options' => ['style' => 'width:300px;'], 'contentOptions' => ['style' => 'text-align:right;']],
					['attribute' => 'auto', 'label' => 'Auto', 'options' => ['style' => 'width:1%;']],

					['class' => '\yii\grid\ActionColumn', 'template' => '{updateddjjanual}&nbsp;{deleteddjjanual}', 'options' => ['style' => 'width:40px;'],
					'buttons' => [

						'updateddjjanual' => function($url, $model){
							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', null, ['onclick' => 'mostrarDeclaracion(' . $model['anio'] . ', 3);']);
						},

						'deleteddjjanual' => function($url, $model){
							return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['onclick' => 'borrarDeclaracion(' . $model['anio'] . ');']);
						}
					]
					]
				]
			]);

			Pjax::end();
			?>
		</div>
	</div>
</div>

<?php

Pjax::begin(['id' => 'pjaxModalAgregar', 'enableReplaceState' => false, 'enablePushState' => false]);
Modal::begin([
	'id' => 'modalAgregar',
	'header' => '<h3>Agregar DDJJ anual</h3>',
	'toggleButton' => false,
	'closeButton' => ['label' => '&times;', 'class' => 'btn btn-danger btn-sm pull-right'],
	'options' => ['data-consulta' => 0],
	'size' => 'modal-sm'
]);

echo $this->render('_agregar_anual', ['model' => $model, 'selectorModal' => '#modalAgregar']);

Modal::end();
Pjax::end();
?>

<?php
Modal::begin([
	'id' => 'modalGenerar',
	'header' => '<h1>Generar DDJJ anual</h1>',
	'toggleButton' => false,
	'closeButton' => ['label' => '&times;', 'class' => 'btn btn-danger btn-sm pull-right'],
	'size' => 'modal-sm'
]);

echo $this->render('_generar_anual', ['model' => $model, 'selectorModal' => '#modalGenerar']);

Modal::end();
?>

<script type="text/javascript">
function borrarDeclaracion(anio){

	$.pjax.reload({
		container : "#pjaxGrillaDeclaraciones",
		url : "<?= BaseUrl::toRoute(['agregaranual', 'obj_id' => $model->obj_id]); ?>",
		type : "POST",
		replace : false,
		push : false,
		data : {
			"borrar" : true,
			"DdjjAnual" : {
				"obj_id" : "<?= $model->obj_id ?>",
				"anio" : anio
			}
		}
	});
}

function mostrarDeclaracion(anio, consulta){

	var datos = {};

	if(anio > 0) datos.anio = anio;

	$.pjax.reload({
		container:  "#pjaxModalAgregar",
		type : "GET",
		replace : false,
		push : false,
		data : datos
	});

	$("#pjaxModalAgregar").on("pjax:complete", function(){

		$("#modalAgregar").data("consulta", consulta);
		$("#modalAgregar").modal("show");

		$("#pjaxModalAgregar").off("pjax:complete");
	});
}

$("#modalAgregar").on("hidden.bs.modal", function(){

	$.pjax.reload({
		container: "#pjaxGrillaDeclaraciones",
		type: "GET",
		replace: false,
		push: false
	});
});
</script>
