<?php

use yii\helpers\Html;
use app\utils\db\utb;
use \yii\widgets\Pjax;
?>
<style>
#opcAdelanta .modal-content{
	width:445px !important;
}
</style>
<?php
$cuota = 0;
$capital = 0;

Pjax::begin(['id' => 'DivAdelanta']);
	if (isset($_POST['cantctas']))
	{
		$cantctas = $_POST['cantctas'];
		$quita = $_POST['quitafinc'];
		$resp = '';
		
		if ($mejoras == false) $resp = $model->AdelantaPlan($cantctas, 0, $cuota, $capital,$quita);
		
		if ($resp == 'OK')
		{
			echo "<script>";
			echo "$('#txAdelantaImpTotal').val('".$capital."');";
			echo "$('#btAceptarAdelanta').attr('disabled',false);";
			echo "</script>";
		}else {
			echo "<script>";
			echo '$.pjax.reload({container:"#DivError",data:{err:"<li>'.trim($resp).'</li>"},method:"POST"})';
			echo "</script>";
		}
	}
Pjax::end();

Pjax::begin(['id' => 'DivAdelantaAcepta']);
	if (isset($_POST['cantctas']))
	{
		$cantctas = $_POST['cantctas'];
		$quita = $_POST['quitafinc'];
		$resp = '';
				
		if ($mejoras == false) $resp = $model->AdelantaPlan($cantctas, 1, $cuota, $capital,$quita);
		
		if ($resp != 'OK')
		{
			echo "<script>";
			echo '$.pjax.reload({container:"#DivError",data:{err:"<li>'.trim($resp).'</li>"},method:"POST"})';
			echo "</script>";
		}else {
			echo "<script>";
			echo "$('#txAdelantaImpTotal').val('".$capital."');";
			echo "$('#btAceptarAdelanta').attr('disabled',true);";
			echo "$('#btImprimirAdelanta').attr('disabled',false);";
			echo "</script>";
		}
	}
Pjax::end();
?>
<div class="site-index" style="overflow:hidden;" >
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px; width:410px; '>
		<label><u>Datos del Convenio</u></label>
		<table border='0' style='font-size:12px'>
		<tr>
			<td>
				<label>Plan:</label>
				<?= Html::input('text', 'txAdelantaPlan', $model->plan_id, ['class' => 'form-control','id'=>'txAdelantaPlan', 'disabled'=>'true','style'=>'width:60px; background:#E6E6FA;text-align:center']); ?></td>
			</td>
			<td>
				<label>&nbsp;Objeto: </label>
				<?= Html::input('text', 'txAdelantaObj', $model->obj_id, ['class' => 'form-control','id'=>'txAdelantaObj', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;']); ?>
			</td>
		</tr>
		<tr>
			<td><label>Contribuyente: </label></td>
			<td>
				<?= Html::input('text', 'txAdelantaObjNom', utb::getCampo('objeto',"obj_id='".($model->obj_id =='' ? 0 : $model->obj_id)."'"), ['class' => 'form-control','id'=>'txAdelantaObjNom', 'disabled'=>'true','style'=>'width:290px; background:#E6E6FA;']); ?>
			</td>
		</tr>
		</table>
	</div>
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px;float:left; margin-right:5px; width:205px; height:120px'>
		<label><u>Resumen de Cuotas</u></label><br>
		<table>
		<tr>
			<td colspan='3'><label>Cantidad</label></td>
			<td><?= Html::input('text', 'txAdelantaCtasCant', $model->cuotas, ['class' => 'form-control','id'=>'txAdelantaCtasCant', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?></td>
		</tr>
		<tr>
			<td><label>Pagado</label></td>
			<td>
				<?= Html::input('text', 'txAdelantaCtasPagas', $model->cuotaspagas, ['class' => 'form-control','id'=>'txAdelantaCtasPagas', 'disabled'=>'true','style'=>'width:50px; background:#E6E6FA;text-align:right']); ?>
			</td>
			<td width='13px' align='center'>$</td>
			<td><?= Html::input('text', 'txAdelantaPagado', $model->pagado, ['class' => 'form-control','id'=>'txAdelantaPagado', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?></td>
		</tr>
		<tr>
			<td><label style='width:43px'>Saldo</label></td>
			<td>
				<?= Html::input('text', 'txAdelantaCtasFalta', $model->cuotasfalta, ['class' => 'form-control','id'=>'txAdelantaCtasFalta', 'disabled'=>'true','style'=>'width:50px; background:#E6E6FA;text-align:right']); ?>
			</td>
			<td width='13px' align='center'>$</td>
			<td><?= Html::input('text', 'txAdelantaSaldo', $model->saldo, ['class' => 'form-control','id'=>'txAdelantaSaldo', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?></td>
		</tr>	
		</table>
	</div>
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px;float:left; width:200px; height:120px'>
		<label><u>Cuotas Adelantar</u></label><br>
		<table>
		<tr>
			<td colspan='2'>
				<?=Html::checkbox('ckAdelantaQuitaFin',false,['id'=>'ckAdelantaQuitaFin', 'label'=>'Quitar Financiación', 'onclick' => 'txAdelantaCantCtasChange()'])?>
			</td>
		</tr>
		<tr>
			<td><label>Cantidad Cuotas</label></td>
			<td><?= Html::input('text', 'txAdelantaCantCtas', null, [
						'class' => 'form-control',
						'id'=>'txAdelantaCantCtas',
						'style'=>'width:80px; text-align:right',
						'onkeypress' => 'return justNumbers( event )',
						'maxlength' => 3, 
						'onchange' => 'txAdelantaCantCtasChange()']); ?></td>
		</tr>
		<tr>
			<td><label>Importe Total</label></td>
			<td><?= Html::input('text', 'txAdelantaImpTotal', null, ['class' => 'form-control','id'=>'txAdelantaImpTotal', 'disabled'=>'true', 'style'=>'width:80px; background:#E6E6FA; text-align:right']); ?></td>
		</tr>
		<tr>
			<td colspan='2'>
				<label><i>Máxima de Cuotas permitidas:&nbsp; <?=$cantctasperm?></i></label>
			</td>
		</tr>
		</table>
	</div>
	<?php
		Pjax::begin(['id' => 'DivError']);
			$error = (isset($_POST['err']) ? $_POST['err'] : '');
			
			if ($error != '') echo '<div class="error-summary" style="overflow:hidden;font-size:12px; margin: 8px 0px 8px 0px">' .
										'Por favor corrija los siguientes errores:<br/><br><ul>' . $error . '</ul>' .
									'</div>';
		Pjax::end();
		
		echo Html::Button('Aceptar', ['id' => 'btAceptarAdelanta', 'class' => 'btn btn-success', 'disabled' => 'true', 'onclick' => 'btAceptarAdelantaClick()']);
		echo "&nbsp;&nbsp;";
		echo Html::a('Imprimir', ['imprimircomprobante', 'id' => $model->plan_id,'hasta'=>'999'], ['id' => 'btImprimirAdelanta','target' => '_black', 'data-pjax' => "0", 'class' => 'btn btn-success', 'disabled' => 'true']);
		echo "&nbsp;&nbsp;";
		echo Html::Button('Cancelar', ['class' => 'btn btn-primary','onclick' => '$("#opcAdelanta, .window").modal("toggle");']);
    ?>
</div>

<script>
function txAdelantaCantCtasChange()
{
	if ($('#txAdelantaCantCtas').val() > 0)
	{
		if ($('input:checkbox[name=ckAdelantaQuitaFin]:checked').val() == 1)
		{
			quita = 1;
		}else {
			quita = 0;
		}
		
		$.pjax.reload(
		{
			container:"#DivAdelanta",
			data:{
					cantctas:$("#txAdelantaCantCtas").val(),
					quitafinc:quita
				},
			method:"POST"
		});
	}
}

function btAceptarAdelantaClick()
{
	if ($('#txAdelantaCantCtas').val() > 0)
	{
		if ($('input:checkbox[name=ckAdelantaQuitaFin]:checked').val() == 1)
		{
			quita = 1;
		}else {
			quita = 0;
		}
		
		$.pjax.reload(
		{
			container:"#DivAdelantaAcepta",
			data:{
					cantctas:$("#txAdelantaCantCtas").val(),
					quitafinc:quita
				},
			method:"POST"
		});
	}else {
		$.pjax.reload({container:"#DivError",data:{err:"<li>Debe ingresar la cantidad de cuotas a adelantar</li>"},method:"POST"})
	}
}
</script>