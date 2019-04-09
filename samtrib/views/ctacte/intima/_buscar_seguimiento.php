<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;

use app\utils\db\utb;
use app\models\objeto\Cem;

$form = ActiveForm::begin(['action' => ['buscarseguimiento'],'id'=>'frmBuscar']);

Pjax::begin(['id' => 'pjaxBuscarSeguimiento', 'enableReplaceState' => false, 'enablePushState' => false]);

$lote = isset($_GET['lote']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td><?= Html::radio('opcion', !$lote, ['id'=>'rbInti','label'=>'Intimación:','onchange' => 'controlesBuscarSeg("rbInti")', 'value' => 1])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txInti', null, ['disabled' => $lote,'class' => 'form-control','id'=>'txInti','maxlength'=>'7','style'=>'width:100px']); ?>
	</td>
</tr>

<tr>
	<td colspan="3"><?= Html::radio('opcion', $lote, ['id'=>'rbLote','label'=>'Lote + Objeto:', 'onchange' => 'controlesBuscarSeg("rbLote")', 'value' => 2])?> </td>
<tr>

<tr>
	<td></td>
	<td colspan="2">
		<table border="0" width="100%">
			<tr>
				<td><label>Lote:</label></td>
				<td><?= Html::input('text', 'txLote', Yii::$app->request->get("lote", null), ['disabled' => !$lote,'class' => 'form-control','id'=>'txLote','maxlength'=>'4','style'=>'width:100px', 'onchange' => 'cambiaLote($(this).val());']); ?></td>
			</tr>
		
			<tr>
				<td><label>Tipo Objeto:</label></td>
				<td><?= Html::dropDownList('dlTipo', utb::getCampo('intima_lote', 'lote_id = ' . Yii::$app->request->get('lote', 0), 'tobj'), utb::getAux('objeto_tipo'), ['disabled' => true, 'class' => 'form-control', 'id' => 'dlTipo', 'prompt' => '', 'style' => 'width:100%']); ?></td>
			</tr>
			
			<tr>
				<td><label>Código Objeto:</label></td>
				<td><?= Html::input('text', 'txObjeto', null, ['disabled' => !$lote,'class' => 'form-control','id'=>'txObjeto','maxlength'=>'8','style'=>'width:100px']); ?></td>
			</tr>
		</table>
	</td>
</tr>

<tr>
	<td colspan='3'>
		<br><div id="errorBuscaSeg" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='3'>
		<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'controlesBuscarSeg("btAceptar");'])?>
	</td>
</tr>
</table>

<?php 
Pjax::end();
ActiveForm::end(); ?>


<script>
function cambiaLote(lote){
	
	$.pjax.reload({
		container : "#pjaxBuscarSeguimiento",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"lote" : lote,
		}
	});
}

function controlesBuscarSeg(control){
	
	var checkObj;
	
	if(control !=="btAceptar"){
		
		$("#txInti").prop("disabled", !$("#rbInti").is(":checked"));
		$("#txObjeto").prop("disabled", !$("#rbLote").is(":checked"));
		$("#txLote").prop("disabled", !$("#rbLote").is(":checked"));
		
		$("#txInti").val("");
		$("#dlTipo").val("");
		$("#txObjeto").val("");
		$("#txLote").val("");
		
		//$("#segBuscarOpcion").val($("#rbInti").is(":Checked") ? 1 : 2);
	}
	else {
		
		var error;
		error ='';
		
		if ($('#rbInti').is(':checked') && $("#txInti").val()=='') error = 'Ingrese un Código de Intimación';			
		if ($('#rbLote').is(':checked') && $("#txLote").val()=='') error = 'Ingrese un Número de Lote';
		if ($('#rbLote').is(':checked') && $("#txObjeto").val()=='') error = 'Ingrese un Código de Objeto';
		if ($('#rbLote').is(':checked') && $("#dlTipo").val()=='') error = 'Elija un Tipo de Objeto';		
		
		if (
			error=='' && 
			$("#txInti").val() == '' && 
			$("#txObjeto").val() == '' &&
			$("#txLote").val() == '' && 
			$("#dlTipo option:selected").text() == ''
			
			) error = 'No se encontraron condiciones de búsqueda';			
		
		if (error=='')
		{
			$("#frmBuscar").submit();
		}else {
			$("#errorBuscaSeg").html(error);
			$("#errorBuscaSeg").css("display", "block");
		}
	}
}
</script>