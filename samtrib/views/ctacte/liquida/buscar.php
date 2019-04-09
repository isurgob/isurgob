<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscar']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td><label>Referencia: <label></td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txCtaCte', null, ['class' => 'form-control','id'=>'txCtaCte','maxlength'=>'10','style'=>'width:100px','onkeypress' => "return justNumbers(event);"]); ?>
	</td>
</tr>
<tr>
	<td colspan='3'>
		<br><div id="errorbuscaliq" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='3'>
		<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'ControlesBuscarLiq("btAceptar");'])?>
	</td>
</tr>
</table>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscarLiq(control)
{
	if (control=="btAceptar")
	{
		var error;
		error ='';
		
		if ($("#txCtaCte").val()=='') error = 'Ingrese un Nº de Referencia';
		
		if (error=='')
		{
			$("#frmBuscar").submit();
		}else {
			$("#errorbuscaliq").html(error);
			$("#errorbuscaliq").css("display", "block");
		}
	}
	
}
</script>