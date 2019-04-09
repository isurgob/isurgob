<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;

use app\utils\db\utb;
use app\models\objeto\Cem;

$form = ActiveForm::begin(['action' => ['buscarfall'],'id'=>'frmBuscar']);


echo Html::input('hidden', 'opcion', 1, ['id' => 'cemBuscarOpcion']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td><?= Html::radio('cemBuscar', true, ['id'=>'rbCodigoFallecido','label'=>'Código de Fallecido:','onchange' => 'ControlesBuscarCem("rbCodigo")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txCodigo', null, ['disabled' => false,'class' => 'form-control','id'=>'txCodigo','maxlength'=>'7','style'=>'width:100px']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::radio('cemBuscar', false, ['id'=>'rbDocumento','label'=>'Número de Documento:', 'onchange' => 'ControlesBuscarCem("rbDocumento")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txDocumento', null, ['disabled' => true,'class' => 'form-control','id'=>'txDocumento','maxlength'=>'8','style'=>'width:100px']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::radio('cemBuscar', false, ['id'=>'rbNombre','label'=>'Apellido y Nombre:','onchange' => 'ControlesBuscarCem("rbNombre")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txNombre', null, ['disabled' => true,'class' => 'form-control','id'=>'txNombre','maxlength'=>'50','style'=>'width:100px']); ?>
	</td>
</tr>	

<tr>
	<td colspan='3'>
		<br><div id="errorbuscacem" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='3'>
		<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'ControlesBuscarCem("btAceptar");'])?>
	</td>
</tr>
</table>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscarCem(control){
	
	var checkObj;
	
	switch(control){
		
		case "rbCodigo" :
		
			$("#txCodigo").val("");
			$("#txCodigo").prop("disabled", false);
			$("#txDocumento").prop("disabled", true);
			$("#txNombre").prop("disabled", true);
			
			$("#cemBuscarOpcion").val(1);
			
			break;
			
		case "rbDocumento" : 
		
			$("#txDocumento").val("");
			$("#txDocumento").prop("disabled", false);
			$("#txCodigo").prop("disabled", true);
			$("#txNombre").prop("disabled", true);
			
			$("#cemBuscarOpcion").val(2);
			
			break;
			
		case "rbNombre" :
			
			$("#txNombre").val("");
			$("#txNombre").prop("disabled", false);
			$("#txDocumento").prop("disabled", true);
			$("#txCodigo").prop("disabled", true);
			
			$("#cemBuscarOpcion").val(3);
			
			break;
			
		case "btAceptar" :
		
			var error;
			error ='';
			
			if ($('#rbCodigo').is(':checked') && $("#txCodigo").val()=='') error = 'Ingrese un Código de Fallecido';			
			if ($('#rbDocumento').is(':checked') && $("#txDocumento").val()=='') error = 'Ingrese un Número de Documento';
			if ($('#rbNombre').is(':checked') && $("#txNombre").val()=='') error = 'Ingrese Apellido y Nombre';
			
			if (
				error=='' && 
				$("#txCodigo").val() == '' && 
				$("#txDocumento").val() == '' &&
				$("#txNombre").val() == ''
				
				) error = 'No se encontraron condiciones de b�squeda';			
			
			if (error=='')
			{
				$("#frmBuscar").submit();
			}else {
				$("#errorbuscacem").html(error);
				$("#errorbuscacem").css("display", "block");
			}
			
			break;
		
	}
}
</script>