<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;
use app\utils\db\utb;
use app\utils\db\Fecha;

$title= 'Nuevo agente de retención';

switch($consulta){

	case 1: $title= 'Consulta de DJ Nº ' . $model->retdj_id; break;
	case 2: $title= 'Eliminar DJ Nº ' . $model->retdj_id; break;
	case 3: $title= 'Modificar DJ Nº ' . $model->retdj_id; break;
}


$this->params['breadcrumbs'][]= ['label' => 'Agente de retenciones', 'url' => ['index', 'id' => $model->ag_rete]];
$this->params['breadcrumbs'][]= $title;
?>

<div>
	<h1><?= $title; ?></h1>
	<div class="separador-horizontal"></div>

	<?php
	$form= ActiveForm::begin([
		'id' => 'formRetencion',
		'validateOnSubmit' =>false,
		'fieldConfig' => ['template' => '{input}']
	]);

	echo Html::hiddenInput('grabar', true);
	?>
	<table border="0">
		<tr>
			<td><label>Agente:</label></td>
			<td colspan="2"><?= $form->field($model, 'ag_rete')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:75px;']); ?></td>
			<td width="5px"></td>
			<td><label>CUIT:</label></td>
			<td><?= MaskedInput::widget(['model' => $model, 'attribute' => 'cuit', 'mask' => '99-99999999-9', 'options' => ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'agenteCuit', 'style' => 'width:100px;']]); ?></td>
			<td width="5px"></td>
			<td><label>Denominación:</label></td>
			<td colspan="7"><?= $form->field($model, 'denominacion')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:377px;']); ?></td>
		</tr>

		<tr>
			<td><label>Per&iacute;odo:</label></td>
			<td><?= $form->field($model, 'anio')->textInput(['id' => 'anio', 'style' => 'width:45px;', 'onchange' => 'cambiaPeriodo();', 'readonly' => $model->cant > 0]); ?></td>
			<td><?= $form->field($model, 'mes')->textInput(['id' => 'mes', 'style' => 'width:30px;', 'onchange' => 'cambiaPeriodo();', 'readonly' => $model->cant > 0]); ?></td>
			<td></td>
			<td><label>Presentaci&oacute;n:</label></td>
			<td>
			<?= Html::activeInput('hidden', $model, 'fchpresenta', ['value' => Fecha::bdToUsuario($model->fchpresenta), 'id' => 'fechaOculto']); ?>
			<?= DatePicker::widget(['model' => $model, 'attribute' => 'fchpresenta', 'dateFormat' => 'php:d/m/Y', 'options' => ['class' => 'form-control', 'id' => 'fecha', 'style' => 'width:100px; text-align:center;', 'onchange' => '$("#fechaOculto").val($(this).val());', 'disabled' => $model->cant > 0]]); ?></td>
			<td></td>
			<td><label>Cantidad:</label></td>
			<td><?= $form->field($model, 'cant')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'cantidad', 'style' => 'width:80px; text-align:right;']); ?></td>
			<td width="5px"></td>
			<td><label>Monto:</label></td>
			<td><?= $form->field($model, 'monto')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'monto', 'style' => 'width:80px; text-align:right;','value' =>  ($model->monto !='' ? Yii::$app->formatter->asDecimal($model->monto) : '' )]); ?></td>
			<td width="5px"></td>
			<td><label>Estado:</label></td>
			<td><?= $form->field($model, 'est_nom')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:120px;']); ?></td>
		</tr>
	</table>

	<div class="separador-horizontal"></div>

	<div>
		<h3 style="display:inline-block"><u>Retenciones Practicadas:</u></h3>

		<?php
		if(in_array($consulta, [0, 3]))
			echo Html::button('Nuevo', ['class' => 'btn btn-success pull-right' . ($model->anio < 1900 || $model->mes <= 0 ? ' disabled' : ''), 'id' => 'botonNuevoDetalle', 'onclick' => 'mostrarModalDetalleRetencion(0);']);
		?>
	</div>

	<?php
	Pjax::begin(['id' => 'pjaxGrillaDetalleRetencion', 'enableReplaceState' => false, 'enablePushState' =>  false]);
		echo GridView::widget([
			'dataProvider' => $dataProviderDetalleRetenciones,
			'id' => 'grillaDetalles',
			'headerRowOptions' => ['class' => 'grillaGrande'],
			'columns' => [
				['label' => 'CUIT', 'attribute' => 'cuit', 'contentOptions' => ['class' => 'grillaGrande'], 'content' => function($model){return substr($model['cuit'], 0, 2) . '-' . substr($model['cuit'], 2, 8) . '-' . substr($model['cuit'], 10, 1);}],
				['label' => 'Objeto', 'attribute' => 'obj_id', 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'Nombre', 'attribute' => 'denominacion', 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'Lugar', 'attribute' => 'lugar', 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'Fecha', 'attribute' => 'fecha', 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'Nº', 'attribute' => 'numero', 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'T. comprob.', 'attribute' => 'tcomprob','value' => function ($model) {
																						return utb::getCampo("ret_tcomprob","cod='" . trim($model->tcomprob) . "'");
																				}, 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'Comprob.', 'attribute' => 'comprob', 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'Base', 'attribute' => 'base', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right']],
				['label' => 'Alícuota', 'attribute' => 'ali', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right']],
				['label' => 'Monto', 'attribute' => 'monto', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right']],
				['label' => 'Est.', 'attribute' => 'est', 'contentOptions' => ['class' => 'grillaGrande']],

				['class' => '\yii\grid\ActionColumn', 'template' => (in_array($consulta, [0, 3]) ? '{update} {delete}' : ''), 'contentOptions' => ['class' => 'grillaGrande'],
				'buttons' => [

					'update' => function($url, $model){

						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', null, ['onclick' => 'event.stopPropagation(); mostrarModalDetalleRetencion(3, ' . $model['numero'] . ');']);
					},

					'delete' => function($url, $model){

						return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['onclick' => 'event.stopPropagation(); mostrarModalDetalleRetencion(2, ' . $model['numero'] . ');']);
					}
				]
				]
			]
		]);

	Pjax::end();
	?>

	<div style="margin-top:5px;">

		<?php
		if($consulta == 0){

			echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
			echo '&nbsp;&nbsp;&nbsp;';

			echo Html::a('Cancelar', ['index', 'id' => $model->ag_rete], ['class' => 'btn btn-primary']);

		} else if($consulta == 3){

			if($model->puedeModificar){

				echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
				echo '&nbsp;&nbsp;&nbsp;';

			} else{
				Alert::begin([
					'options' => ['class' => 'alert alert-info alert-dissmissible']
				]);

				echo 'El registro no se puede modificar.';
				Alert::end();
			}

			echo Html::a('Cancelar', ['index', 'id' => $model->ag_rete], ['class' => 'btn btn-primary']);

		} else if($consulta == 2){

			if($model->puedeEliminar){

				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger']);
				echo '&nbsp;&nbsp;&nbsp;';
			} else{
				Alert::begin([
					'options' => ['class' => 'alert alert-info alert-dissmissible']
				]);

				echo 'El registro no se puede eliminar.';

				Alert::end();
			}

			echo Html::a('Cancelar', ['index', 'id' => $model->ag_rete], ['class' => 'btn btn-primary']);
		} else if($consulta == 1){

			if($model->necesitaAprobarse){

				echo Html::submitButton('Aprobar', ['class' => 'btn btn-success']);
				echo '&nbsp;&nbsp;&nbsp;';
			}

			echo Html::a('Volver', ['index', 'id' => $model->ag_rete], ['class' => 'btn btn-primary']);

		} else {

			echo Html::a('Volver', ['index', 'id' => $model->ag_rete], ['class' => 'btn btn-primary']);
		}
		?>
	</div>

	<?php
	ActiveForm::end();
	?>
</div>

<?= $form->errorSummary($model, ['style' => 'margin-top:5px;']) ?>

<?php
Modal::begin([
	'id' => 'modalDetalle',
	'header' => '<h3><b id="tituloModal">Nuevo detalle</b></h3>',
	'closeButton' => [
		'label' => '<b>&times;</b>',
		'class' => 'btn btn-sm btn-danger pull-right'
	]
]);
Pjax::begin(['id' => 'pjaxModalDetalleRetencion', 'enableReplaceState' => false, 'enablePushState' => false]);
echo $this->render('_form_detalle', $extrasDetalle + ['selectorPjax' => '#pjaxGrillaDetalleRetencion', 'selectorModal' => '#modalDetalle', 'form' => $form]);
Pjax::end();

Modal::end();



if(isset($mensaje) && $mensaje != ''){

	echo Alert::begin([
		'options' => [
			'id' => 'alertMensaje',
			'class' => 'alert alert-success alert-dissmissible'
		]
		]);

	Alert::end();

	?>
	<script type="text/javascript">
	$(document).ready(function(){
		setTimeout($("#alertMensaje").fadeOut(), 5000);
	});
	</script>
	<?php
}
?>


<script type="text/javascript">
function mostrarModalDetalleRetencion(consulta, numero){

	var datos= {};
	var titulo= "Nuevo detalle";

	datos.id= "<?= $model->retdj_id ?>";
	datos.consultaDetalle= consulta;
	datos.numeroDetalle= numero;
	urlTo= "";

	switch(consulta){

		case 0: titulo= "Nuevo detalle"; break;
		case 2: titulo= "Eliminar detalle"; break;
		case 3: titulo= "Modificar detalle"; break;
	}

	$("#tituloModal").text(titulo);

	$.pjax.reload({
		container: "#pjaxModalDetalleRetencion",
		type: "GET",
		replace: false,
		push: false,
		data: datos
	});

	$("#pjaxModalDetalleRetencion").on("pjax:complete", function(){

		$("#modalDetalle").modal("show");
		$("#tituloDetalle").val(titulo);

		$("#pjaxModalDetalleRetencion").off("pjax:complete");
	});
}

function cambiaPeriodo(){

	var anio= parseInt($("#anio").val());
	var mes= parseInt($("#mes").val());

	var habilitarBotonNuevoDetalle= !isNaN(anio) && !isNaN(mes) && anio > 1900 && mes > 0;

	$("#botonNuevoDetalle").toggleClass("disabled", !habilitarBotonNuevoDetalle);

	$("#modalDetalle").data("anio", anio);
	$("#modalDetalle").data("mes", mes);
}

function habilitarCampos(){

	$filas= $("#grillaDetalles tbody tr");
	if($filas.length > 0 && $filas.find(".empty").length == 0){
		DesactivarFormPost("formRetencion");

		$("#fecha").attr("disabled", true);
	}
	else {
		$("#anio").removeAttr("readonly");
		$("#mes").removeAttr("readonly");
		$("#fecha").removeAttr("disabled");
		$("#fecha").removeAttr("readonly");
	}
}

$(document).ready(function(){

	<?php
	if($consulta != 0){
		?>
		$(document).ready(function(){
			DesactivarFormPost("formRetencion");

			$("#fecha").attr("disabled", true);
		});
		<?php
	}
	?>

	$.pjax.defaults.timeout= 10000;
	cambiaPeriodo();


	$("#modalDetalle").on("hidden.bs.modal", function(){

		$.pjax.reload({
			container: "#pjaxGrillaDetalleRetencion",
			type: "GET",
			replace: false,
			push: false
		});
	});


	$("#pjaxGrillaDetalleRetencion").on("pjax:complete", function(){

		habilitarCampos();
	});

});

</script>
