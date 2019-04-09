<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscarPagocta']);
?>

<!-- Tabla para Nº de Pago a Cuenta -->
<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td width="80px"><label>Pago ID: </td>
		<td>
			<?= Html::input('text','txNum',null,['id'=>'pagoctaBuscar_txNum','class'=>'form-control','style'=>'width:100px;','onkeypress'=>'return justNumbers(event)','maxlength'=>'8']); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td>
			<br><div id="errorbuscapagocta" style="display:none;" class="alert alert-danger alert-dismissable"></div>
		</td>
	</tr>
</table>
<table style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">	
	<tr>
		<td>
			<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'id' => 'btBuscarPagoctaAceptar', 'onclick' => 'ControlesBuscar()'])?>
		</td>
	</tr>
</table>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscar()
{
	var error = '';
	var num = $("#pagoctaBuscar_txNum").val();
	
	if (num == '')
		error = 'Ingrese un número de compensación.';
	
	if (error=='')
	{
		$("#frmBuscarPagocta").submit();
	} else 
	{
		$("#errorbuscapagocta").html(error);
		$("#errorbuscapagocta").css("display", "block");
	}
}

</script>