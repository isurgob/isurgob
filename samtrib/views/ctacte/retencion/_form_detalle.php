<?php

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\jui\DatePicker;
use yii\jui\AutoComplete;

?>

<?php
Pjax::begin(['id' => 'pjaxFormDetalleRetencion', 'enableReplaceState' => false, 'enablePushState' => false]);


Pjax::begin(['id' => 'pjaxObjeto', 'enableReplaceState' => false, 'enablePushState' => false]);
?>
<script type="text/javascript">
$(document).ready(function(){

	$("#codigoObjetoDetalle").val("<?= $model->obj_id; ?>");
	$("#denominacionDetalle").val("<?= $model->denominacion; ?>");
	$("#cuitDetalle").val("<?= $model->cuit; ?>");
});
</script>
<?php
Pjax::end();

?>
<div id="formDetalle">

	<table border="0">
		<tr>
			<td><label>CUIT:</label></td>
			<td width="5px"></td>
			<td><?= MaskedInput::widget(['model' => $model, 'attribute' => 'cuit', 'mask' => '99-99999999-9', 'options' => ['class' => 'form-control campo', 'id' => 'cuitDetalle', 'style' => 'width:100px;', 'onchange' => 'cambiaCuitDetalle($(this).val());']]); ?></td>
		</tr>

		<tr>
			<td><label>Objeto:</label></td>
			<td></td>
			<td><?= Html::activeInput('text', $model, 'obj_id', ['class' => 'form-control campo', 'style' => 'width:100px; text-transform:uppercase;',
			'onchange' => 'cambiaCodigoObjetoDetalle($(this).val());', 'id' => 'codigoObjetoDetalle']); ?></td>
			<td></td>
			<td><label>Denominación:</label></td>
			<td colspan="4"><?= Html::activeInput('text', $model, 'denominacion', ['class' => 'form-control solo-lectura campo', 'tabindex' => -1, 'id' => 'denominacionDetalle', 'style' => 'width:210px;']); ?></td>
		</tr>

		<tr>
			<td><label>Lugar:</label></td>
			<td></td>
			<td><?= Html::activeInput('text', $model, 'lugar',['maxlength' => 30, 'class' => 'form-control campo', 'id' => 'lugarDetalle']); ?>
				<?= null;//Html::activeDropDownList($model, 'lugar', $lugares, ['class' => 'form-control campo', 'style' => 'width:175px;', 'id' => 'lugarDetalle', 'prompt' => 'Seleccionar...']); ?></td>
			<td width="5px"></td>
			<td><label>Fecha:</label></td>
			<td><?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha', 'dateFormat' => 'php:d/m/Y', 'options' => ['class' => 'form-control campo', 'style' => 'width:80px;', 'id' => 'fechaDetalle']]); ?></td>
			<td width="5px"></td>
			<td><label>Nº:</label></td>
			<td><?= Html::activeInput('text', $model, 'numero', ['class' => 'form-control campo', 'style' => 'width:80px;', 'id' => 'numeroDetalle']); ?></td>
		</tr>

		<tr>
			<td colspan="4"><label>Comprobante:</label></td>
			<td><label>Tipo:</label></td>
			<td><?= Html::activeDropDownList($model, 'tcomprob', $tiposComprobantes, ['class' => 'form-control campo', 'style' => 'width:80px;', 'id' => 'tipoComprobanteDetalle', 'prompt' => 'Seleccionar...']); ?></td>
			<td></td>
			<td><label>Nº:</label></td>
			<td><?= Html::activeInput('text', $model, 'comprob', ['class' => 'form-control campo', 'style' => 'width:80px;', 'id' => 'numeroComprobanteDetalle']); ?></td>
		</tr>

		<tr>
			<td><label>Base:</label></td>
			<td></td>
			<td><?= Html::activeInput('text', $model, 'base', ['class' => 'form-control campo', 'id' => 'baseDetalle', 'style' => 'width:80px; text-align:right;', 'onchange' => 'actualizarMontoDetalle();']); ?></td>
			<td></td>
			<td><label>Al&iacute;cuota:</label></td>
			<td><?= Html::activeInput('text', $model, 'ali', ['class' => 'form-control campo', 'id' => 'alicuotaDetalle', 'style' => 'width:80px; text-align:right;', 'onchange' => 'actualizarMontoDetalle();']); ?></td>
			<td></td>
			<td><label>Monto:</label></td>
			<td><?= Html::activeInput('text', $model, 'monto', ['class' => 'form-control campo', 'id' => 'montoDetalle', 'style' => 'width:80px; text-align:right;']); ?></td>
		</tr>
	</table>
</div>

<div style="margin-top:5px;" class="text-center">
	<?php
	switch($consulta){

		case 0:
		case 3:
			echo Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => "grabarDetalle($consulta);"]);
			break;

		case 2:
			echo Html::button('Eliminar', ['class' => 'btn btn-danger', 'onclick' => "grabarDetalle($consulta);"]);
			break;
	}

	echo '&nbsp;&nbsp;&nbsp;';
	echo Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'cerrarModalDetalle();']);
	?>
</div>

<?= $form->errorSummary($model, ['id' => 'contenedorErroresDetalle', 'style' => 'margin-top:5px;']); ?>

<?php

if(isset($hayError) && $hayError == false){
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		cerrarModalDetalle();
	});
	</script>
	<?php
}

Pjax::end();
?>

<script type="text/javascript">
function grabarDetalle(){

	<?php
	$urlTo= "";

	switch($consulta){

		case 0: $urlTo= BaseUrl::toRoute(['createdetalle', 'id' => $model->retdj_id]); break;
		case 2: $urlTo= BaseUrl::toRoute(['deletedetalle', 'id' => $model->retdj_id, 'numero' => $model->numero]); break;
		case 3: $urlTo= BaseUrl::toRoute(['updatedetalle', 'id' => $model->retdj_id, 'numero' => $model->numero]); break;
	}
	?>

	var datos= obtenerDatosDetalle();
	var urlTo= "<?= $urlTo; ?>";




	$.pjax.reload({
		container: "#pjaxFormDetalleRetencion",
		url: urlTo,
		type: "POST",
		replace: false,
		push: false,
		data: {
			"RetencionDetalle": datos
		}
	});
}

function actualizarMontoDetalle(){

	var base= parseFloat($("#baseDetalle").val());
	var alicuota= parseFloat($("#alicuotaDetalle").val());
	var monto= 0;

	if(!isNaN(base) && !isNaN(alicuota) && base > 0 && alicuota > 0)
		monto= base * alicuota / 100;

	$("#montoDetalle").val(monto);
}

function cargarDatosObjeto(clave, valor){

	var datos= {};
	datos[clave]= valor;

	$.pjax.reload({
		container: "#pjaxObjeto",
		type:"GET",
		replace: false,
		push: false,
		data: datos
	});
}

function cambiaCuitDetalle(cuit){
	cargarDatosObjeto("cuit", cuit);
}

function cambiaCodigoObjetoDetalle(codigoObjeto){
	cargarDatosObjeto("obj_id", codigoObjeto);
}

function obtenerDatosDetalle(){

	var datos= {};

	datos.retdj_id= "<?= $model->retdj_id; ?>";
	datos.cuit= $("#cuitDetalle").val();
	datos.obj_id= $("#codigoObjetoDetalle").val();
	datos.denominacion= $("#denominacionDetalle").val();
	datos.lugar= $("#lugarDetalle").val();
	datos.fecha= $("#fechaDetalle").val();
	datos.numero= $("#numeroDetalle").val();
	datos.tcomprob= $("#tipoComprobanteDetalle").val();
	datos.comprob= $("#numeroComprobanteDetalle").val();
	datos.base= $("#baseDetalle").val();
	datos.ali= $("#alicuotaDetalle").val();
	datos.monto= $("#montoDetalle").val();

	return datos;
}

function cerrarModalDetalle(){

	$("<?= $selectorModal ?>").modal("hide");
}

function desactivarFormulario(){

	$("#formDetalle .campo").prop("disabled", true);
}

<?php
if($consulta == 2){
	?>
	desactivarFormulario();
	<?php
}
?>

$(document).ready(function(){

	$("<?= $selectorModal ?>").on("shown.bs.modal", function(){

		$("#contenedorErroresDetalle").css("display", "none");

		var $modal= $("<?= $selectorModal ?>");

		var anio= $modal.data("anio");
		var mes= $modal.data("mes");
	});

	//convierte el input en un elemento de autocompletado
	$("#lugarDetalle").autocomplete({
		source: "<?= BaseUrl::toRoute('//ctacte/retencion/sugerencialugar'); ?>"
	});

	$(".ui-autocomplete").css("z-index", "5000");
});
</script>
