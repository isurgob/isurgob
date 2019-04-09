<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use app\models\ctacte\PlanConfig;
?>
<style>
#ModifParam .modal-content{
	width:450px !important;
}
</style>

<?php 

Pjax::begin(['id' => 'ActParam']);
	$TipoPlan = (isset($_POST['arrayPlanConfig']) ? unserialize(urldecode(stripslashes($_POST['arrayPlanConfig']))) : 0);
	if ($TipoPlan == null) $TipoPlan = new PlanConfig;
	$TipoPlan->descnominal = (isset($_POST['descnominal']) ? $_POST['descnominal'] : $TipoPlan->descnominal);
	$TipoPlan->descinteres = (isset($_POST['descinteres']) ? $_POST['descinteres'] : $TipoPlan->descinteres);
	$TipoPlan->descmulta = (isset($_POST['descmulta']) ? $_POST['descmulta'] : $TipoPlan->descmulta);
	$TipoPlan->interes = (isset($_POST['interes']) ? $_POST['interes'] : $TipoPlan->interes);
	
	echo '<script>$("#arrayPlanConfig").val("'.urlencode(serialize($TipoPlan)).'")</script>';
	
	?>
	
	<script>
		$("#ActParam").on("pjax:end", function() {
							
			btProcesarClick();
			$("#ModifParam").modal("toggle");
			$("#ActParam").off("pjax:end");
							
		});
	</script>
	
	<?php

Pjax::end();

Pjax::begin(['id' => 'DatosParamConfig']);
	
	$TipoPlan = (isset($_POST['arrayPlanConfig']) ? unserialize(urldecode(stripslashes($_POST['arrayPlanConfig']))) : 0);
	
	if ($TipoPlan == null) 
		$TipoPlan = new PlanConfig;
?>

<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px; '>
<table>
	<tr>
		<td><label>Quita Nominal</label></td>
		<td>
			<?= Html::input('text', 'txDescNominal', $TipoPlan->descnominal, [
					'class' => 'form-control',
					'id'=>'txDescNominal',
					'maxlength' => '8',
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
					'style'=>'width:70px; text-align:right',
				]);
			?>
			<label>%</label>
		</td>
		<td width='5'></td>
		<td><label>Quita Accesorios</label></td>
		<td>
			<?= Html::input('text', 'txDescAccesor', $TipoPlan->descinteres, [
					'class' => 'form-control',
					'id'=>'txDescAccesor',
					'maxlength' => '8',
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
					'style'=>'width:70px; text-align:right',
				]);
			?>
			<label>%</label>
		</td>
	</tr>
	<tr>
		<td><label>Quita Multa</label></td>
		<td>
			<?= Html::input('text', 'txDescMulta', $TipoPlan->descmulta, [
					'class' => 'form-control',
					'id'=>'txDescMulta',
					'maxlength' => '8',
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
					'style'=>'width:70px; text-align:right',
				]);
			?>
			<label>%</label>
		</td>
		<td width='10'></td>
		<td><label>Tasa de Interés</label></td>
		<td>
			<?= Html::input('text', 'txTasaInteres', $TipoPlan->interes, [
					'class' => 'form-control',
					'id'=>'txTasaInteres',
					'maxlength' => '8',
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
					'style'=>'width:70px; text-align:right',
				]);
			?>
			<label>%</label>
		</td>
	</tr>
</table>
</div>

<div class="text-center">
	<?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btAceptarModifParam','onclick' => 'f_btAceptar()']); ?>
	
	&nbsp;&nbsp;
	
	<?= Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btCancelarModifParam', 'onclick' => '$("#ModifParam").modal("toggle")']); ?>
</div>

<?php 

	Pjax::end(); 

?>

<script>
function f_btAceptar()
{
	$.pjax.reload({
		container:"#ActParam",
		type: "POST",
		data:{
			descnominal:$("#txDescNominal").val(),
			descinteres:$("#txDescAccesor").val(),
			descmulta:$("#txDescMulta").val(),
			interes:$("#txTasaInteres").val(),
		},
	});
}

$('#ModifParam').on('show.bs.modal', function () {
	$.pjax.reload({container:"#DatosParamConfig",data:{arrayPlanConfig:$("#arrayPlanConfig").val()},method:"POST"});
})
</script>