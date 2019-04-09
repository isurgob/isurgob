<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscarCompensacion']);
?>

<!-- Tabla para Nº de Compensación -->
<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td width="50px"><label>Código: </td>
		<td>
			<?= Html::input('text','txNum',null,['id'=>'compensacionBuscar_txNum','class'=>'form-control','style'=>'width:100px;','onkeypress'=>'return justNumbers(event)','maxlength'=>'8']); ?>
		</td>
	</tr>
</table>
<div class="text-center" style="margin-top: 8px;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'id' => 'btBuscarCompensacionAceptar', 'onclick' => 'ControlesBuscar()']); ?>
	&nbsp;&nbsp;
	<?= Html::Button('Cancelar',['class' => 'btn btn-primary', 'id' => 'btBuscarCompensacionCancelar', 'onclick' => '$("#CompensacionBuscar").modal("hide")']); ?>
</div>

<table style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td>
			<br><div id="errorbuscacompensacion" style="display:none;" class="alert alert-danger alert-dismissable"></div>
		</td>
	</tr>
</table>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscar()
{
	var error = '';
	var num = $("#compensacionBuscar_txNum").val();
	
	if (num == '')
		error = 'Ingrese un número de compensación.';
	
	if (error=='')
	{
		$("#frmBuscarCompensacion").submit();
	} else 
	{
		$("#errorbuscacompensacion").html(error);
		$("#errorbuscacompensacion").css("display", "block");
	}
}

</script>