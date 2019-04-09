<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\MaskedInput;
use yii\grid\GridView;

$title= 'Agente de retenciones';

$this->params['breadcrumbs'][]= $title;

?>

<div>

	<h1><?= $title; ?></h1>
	<div class="separador-horizontal"></div>

	<div>
		<label>Agente:</label>
		<?= Html::dropDownList(null, $model->ag_rete, $agentesExistentes, ['class' => 'form-control', 'onchange' => 'cambiaAgente();', 'id' => 'codigoAgente', 'prompt' => 'Seleccionar...', 'style' => 'width:100px;']); ?>

		<?php
		Pjax::begin(['id' => 'pjaxDatosAgenteRetencion', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);
		?>
		<label>CUIT:</label>
		<?= MaskedInput::widget(['model' => $model, 'attribute' => 'cuit', 'mask' => '99-99999999-9', 'options' => ['class' => 'form-control solo-lectura', 'disabled' => true, 'id' => 'agenteCuit', 'style' => 'width:100px; text-align:center;']]); ?>

		<label>Denominación:</label>
		<?= Html::textInput(null, $model->denominacion, ['class' => 'form-control solo-lectura', 'id' => 'agenteDenominacion', 'style' => 'width:395px;']); ?>

		<?php
		Pjax::end();
		?>
	</div>

	<div class="separador-horizontal"></div>

	<div>
		<table width="100%" border="0">
			<tr>
				<td><h3><u>DJ Presentadas</u></h3></td>
				<td width="100px"></td>
				<td>
					<label>A&ntilde;o</label>
					<?= Html::input('number', null, null, ['class' => 'form-control', 'id' => 'filtroAnio', 'onchange' => 'filtrar();','style' => 'width:70px;', 'max' => 9999, 'min' => 1900]); ?>
				</td>

				<td width="150px"></td>
				<td align="right"><?= Html::a('Nueva DJ', ['create'], ['class' => 'btn btn-success disabled', 'id' => 'botonNuevaDJ']); ?></td>
				<td align="right"><?php

				$form= ActiveForm::begin(['id' => 'formImportar', 'method' => 'POST', 'action' => ['create', 'id' => 0, 'importar' => true], 'options' => ['enctype' => 'multipart/form-data']]);
				echo $form->field($model, 'archivo', ['options' => ['class' => 'hidden']])->fileInput(['id' => 'archivoImportar', 'onchange' => '$("#formImportar").submit();']);
				echo Html::button('Importar', ['onclick' => 'mostrarImportar();', 'class' => 'btn btn-success']);
				ActiveForm::end();
				?></td>
			</tr>
		</table>
	</div>

	<div>
	<?php

	Pjax::begin(['id' => 'pjaxGrilla', 'enableReplaceState' => false, 'enablePushState' => false]);

	echo GridView::widget([
				'dataProvider' => $dataProviderDDJJPresentadas,
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'columns' => [
					['label' => 'ID', 'attribute' => 'retdj_id', 'headerOptions' => ['style' => 'width:1%;'], 'contentOptions' => ['style' => 'text-align:right;']],
					['label' => 'Año', 'attribute' => 'anio', 'headerOptions' => ['style' => 'width:1%;'], 'contentOptions' => ['style' => 'text-align:right;']],
					['label' => 'Mes', 'attribute' => 'mes', 'headerOptions' => ['style' => 'width:1%;'], 'contentOptions' => ['style' => 'text-align:right;']],
					['label' => 'Cantidad', 'attribute' => 'cant', 'headerOptions' => ['style' => 'width:1%;'], 'contentOptions' => ['style' => 'text-align:right;']],
					['label' => 'Monto', 'attribute' => 'monto', 'format' => ['decimal', 2], 'contentOptions' => ['style' => 'text-align:right;'], 'headerOptions' => ['style' => 'width:3%;']],
					['label' => 'Presentación', 'attribute' => 'fchpresenta', 'format' => ['date', 'php:d/m/Y'], 'headerOptions' => ['style' => 'width:1%;']],
					['label' => 'Estado', 'attribute' => 'est_nom', 'headerOptions' => ['style' => 'width:1%;']],
					['label' => 'Modificación', 'attribute' => 'modif', 'headerOptions' => ['style' => 'width:auto;']],

					['class' => '\yii\grid\ActionColumn', 'template' => ('{view} {update} {delete}'), 'headerOptions' => ['style' => 'width:60px;'],
					'buttons' => [
						'view' => function($url, $model){

							return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
						},

						'update' => function($url, $model){

							if($model['puedeModificar'])
								return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
						},

						'delete' => function($url, $model){

							if($model['puedeEliminar'])
								return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model['retdj_id']]);
						}
					]
					]
				]
			]);

	Pjax::end();
	?>

	</div>
</div>

<script type="text/javascript">
function cambiaAgente(){

	$("#agenteCuit").val("");
	$("#agenteDenominacion").val("");
	habilitarNuevo();

	$("#botonNuevaDJ").attr("href", "<?= BaseUrl::toRoute(['create']); ?>&id=" + $("#codigoAgente").val());

	filtrar();
}


function filtrar(){

	var agente= $("#codigoAgente").val();

	habilitarNuevo()

	if(agente != "" ){

		$.pjax.reload({
			container: "#pjaxDatosAgenteRetencion",
			type: "GET",
			replace: false,
			push: false,
			data: {
				id: agente
			}
		});

		$("#pjaxDatosAgenteRetencion").on("pjax:complete", function(){

			if($("#agenteCuit").val() != "" && $("#agenteDenominacion").val() != ""){
				$("#botonNuevaDJ").toggleClass("disabled", false);
			}

			var anio= $("#filtroAnio").val();

			var agente= $("#codigoAgente").val();

			$.pjax.reload({
				container: "#pjaxGrilla",
				type: "GET",
				replace: false,
				push: false,
				data: {
					"id": agente,
					"anio": anio
				}
			});


			$("#pjaxDatosAgenteRetencion").off("pjax:complete");
		});
	}
}

function habilitarNuevo(){

	var habilitar= $("#agenteCuit").val() != "" && $("#agenteDenominacion").val() != "";

	$("#botonNuevaDJ").toggleClass("disabled", !habilitar);
}

function mostrarImportar(){

	$("#archivoImportar").click();
}

$(document).ready(function(){
	cambiaAgente();
});
</script>
