<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscar']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td><?= Html::radio('buscar-rbObjeto',true,['id'=>'inm-buscar-rbObjeto','label'=>'Código de Objeto:','onchange' => 'ControlesBuscarInmueble("buscar-rbObjeto")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'buscar-txObjeto', null, ['class' => 'form-control','id'=>'inm-buscar-txObjeto','maxlength'=>'8','style'=>'width:100px']); ?>
	</td>
</tr>
<tr>
	<td><?= Html::radio('buscar-rbPartProv',false,['id'=>'inm-buscar-rbPartProv','label'=>'Partida Provincial:','onchange' => 'ControlesBuscarInmueble("buscar-rbPartProv")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'buscar-txPartProv', null, ['class' => 'form-control','id'=>'inm-buscar-txPartProv','onkeypress'=>'return justNumbers(event)','maxlength'=>'8','style'=>'width:100px','disabled'=>'true']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::radio('buscar-rbPartOrigen',false,['id'=>'inm-buscar-rbPartOrigen','label'=>'Partida Origen:','onchange' => 'ControlesBuscarInmueble("buscar-rbPartOrigen")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'buscar-txPartOrigen', null, ['class' => 'form-control','id'=>'inm-buscar-txPartOrigen','onkeypress'=>'return justNumbers(event)','maxlength'=>'10','style'=>'width:100px','disabled'=>'true']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::radio('buscar-rbNumPlano',false,['id'=>'inm-buscar-rbNumPlano','label'=>'Número de Plano:','onchange' => 'ControlesBuscarInmueble("buscar-rbNumPlano")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text','buscar-txNumPlano',null,['id' => 'inm-buscar-txNumPlano','class' => 'form-control','onkeypress'=>'return justNumbers(event)','style'=>'width:100px','disabled'=>'true']);?>
	</td>
</tr>
<tr>
	<td colspan='3'>
		<br><div id="errorbuscapers" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='3'>
		<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'ControlesBuscarInmueble("btAceptar");'])?>
	</td>
</tr>
</table>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscarInmueble(control)
{
	var checkObj,checkPartProv,checkNumPlano,checkCuentaOSM;
		
	if (control=="buscar-rbObjeto" || control=="buscar-rbPartProv" || control=="buscar-rbPartOrigen" || control=="buscar-rbNumPlano")
	{
		$("#inm-buscar-txObjeto").val("");
		$("#inm-buscar-txPartProv").val("");
		$("#inm-buscar-txPartOrigen").val("");
		$("#inm-buscar-txNumPlano").val("");

		
		checkObj = control=="buscar-rbObjeto" && $('input:radio[name=buscar-rbObjeto]:checked').val()==1 ? true : false;
		checkPartProv = control=="buscar-rbPartProv" && $('input:radio[name=buscar-rbPartProv]:checked').val() ==1 ? true : false; 
		checkPartOrigen = control=="buscar-rbPartOrigen" && $('input:radio[name=buscar-rbPartOrigen]:checked').val() ==1 ? true : false;
		checkNumPlano = control=="buscar-rbNumPlano" && $('input:radio[name=buscar-rbNumPlano]:checked').val() ==1 ? true : false;

					
		$("#inm-buscar-rbObjeto").prop("checked",checkObj);
		$("#inm-buscar-rbPartProv").prop("checked",checkPartProv);
		$("#inm-buscar-rbPartOrigen").prop("checked",checkPartOrigen);
		$("#inm-buscar-rbNumPlano").prop("checked",checkNumPlano);
	
			
		$("#inm-buscar-txObjeto").prop("disabled",!checkObj);
		$("#inm-buscar-txPartProv").prop("disabled",!checkPartProv);
		$("#inm-buscar-txPartOrigen").prop("disabled",!checkPartOrigen);
		$("#inm-buscar-txNumPlano").prop("disabled",!checkNumPlano);		
	}
	
	if (control=="btAceptar")
	{
		var error;
		error ='';
		
		if ($('input:radio[name=buscar-rbObjeto]:checked').val()==1 && $("#inm-buscar-txObjeto").val()=='') error = 'Ingrese un Objeto';
		if ($('input:radio[name=buscar-rbPartProv]:checked').val()==1 && $("#inm-buscar-txPartProv").val()=='') error = 'Ingrese una Partida Provincial';
		if ($('input:radio[name=buscar-rbPartOrigen]:checked').val()==1 && $("#inm-buscar-txPartOrigen").val()=='') error = 'Ingrese una Partida de Origen';
		if ($('input:radio[name=buscar-rbNumPlano]:checked').val()==1 && $("#inm-buscar-txNumPlano").val()=='') error = 'Ingrese un Número de Plano';

		
		if (error=='' && $("#inm-buscar-txObjeto").val()=='' && $("#inm-buscar-txPartProv").val()=='' && $("#inm-buscar-txPartOrigen").val()=='' && $("#inm-buscar-txNumPlano").val()=='') error = 'No se encontraron condiciones de búsqueda';
		
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