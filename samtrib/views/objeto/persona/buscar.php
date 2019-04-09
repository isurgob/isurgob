<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscar']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td><?= Html::radio('rbObjeto',true,['id'=>'rbObjeto','label'=>'Código de Objeto:','onchange' => 'ControlesBuscarPersona("rbObjeto")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txObjeto', null, ['class' => 'form-control','id'=>'txObjeto','maxlength'=>'8','style'=>'width:100px']); ?>
	</td>
</tr>
<tr>
	<td><?= Html::radio('rbDoc',false,['id'=>'rbDoc','label'=>'Documento:','onchange' => 'ControlesBuscarPersona("rbDoc")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txDoc', null, ['class' => 'form-control','id'=>'txDoc','maxlength'=>'8','style'=>'width:100px','disabled'=>'true']); ?>
	</td>
</tr>
<tr>
	<td><?= Html::radio('rbCuit',false,['id'=>'rbCuit','label'=>'CUIT/CUIL:','onchange' => 'ControlesBuscarPersona("rbCuit")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= MaskedInput::widget(['name' => 'txCuit','id' => 'txCuit','mask' => '99-99999999-9','options' => ['class' => 'form-control','style'=>'width:100px','disabled'=>'true']]);?>
	</td>
</tr>
<tr>
	<td colspan='3'>
		<br><div id="errorbuscapers" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='3'>
		<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'ControlesBuscarPersona("btAceptar");'])?>
	</td>
</tr>
</table>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscarPersona(control)
{
	var checkObj,checkDoc,checkCuit;
		
	if (control=="rbObjeto" || control=="rbDoc" || control=="rbCuit")
	{
		$("#txObjeto").val("");
		$("#txCuit").val("");
		$("#txDoc").val("");
		
		checkObj = control=="rbObjeto" && $('input:radio[name=rbObjeto]:checked').val()==1 ? true : false;
		checkDoc = control=="rbDoc" && $('input:radio[name=rbDoc]:checked').val() ==1 ? true : false; 
		checkCuit = control=="rbCuit" && $('input:radio[name=rbCuit]:checked').val() ==1 ? true : false;
						
		$("#txObjeto").prop("disabled",!checkObj);
		$("#txCuit").prop("disabled",!checkCuit);
		$("#txDoc").prop("disabled",!checkDoc);
		
		$("#rbObjeto").prop("checked",checkObj);
		$("#rbDoc").prop("checked",checkDoc);
		$("#rbCuit").prop("checked",checkCuit);
	}
	
	if (control=="btAceptar")
	{
		var error;
		error ='';
		
		if ($('input:radio[name=rbObjeto]:checked').val()==1 && $("#txObjeto").val()=='') error = 'Ingrese un Objeto';
		if ($('input:radio[name=rbDoc]:checked').val()==1 && $("#txDoc").val()=='') error = 'Ingrese un Documento';
		if ($('input:radio[name=rbCuit]:checked').val()==1 && $("#txCuit").val()=='') error = 'Ingrese un CUIT/CUIL';
		//if ($('input:radio[name=rbCuit]:checked').val()==1 && ValidarCUIT($("#txCuit").val()) == 0) error = 'Ingrese un CUIT/CUIL válido';
		if (error=='' && $("#txObjeto").val()=='' && $("#txDoc").val()=='' && $("#txCuit").val()=='') error = 'No se encontraron condiciones de b�squeda';
		
		if (error=='')
		{
			$("#frmBuscar").submit();
		}else {
			$("#errorbuscapers").html(error);
			$("#errorbuscapers").css("display", "block");
		}
	}
	
}
</script>