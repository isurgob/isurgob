<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use app\models\ctacte\Plan;
use app\utils\db\utb;

Pjax::begin(['id' => 'DivCalcularDeuda']);

$nominal = (isset($_POST['nominal']) ? $_POST['nominal'] : 0);
$accesor = (isset($_POST['accesor']) ? $_POST['accesor'] : 0);
$multa = (isset($_POST['multa']) ? $_POST['multa'] : 0);
$totaldeuda = (isset($_POST['totaldeuda']) ? $_POST['totaldeuda'] : 0);
$cantper = (isset($_POST['cantper']) ? $_POST['cantper'] : 0);
$cantcuotas = (isset($_POST['cantcuotas']) ? $_POST['cantcuotas'] : 1);
$fchcons = (isset($_POST['fchcons']) ? $_POST['fchcons'] : '');
$tplan = (isset($_POST['tplan']) ? $_POST['tplan'] : $tplan);
$obj_id = (isset($_POST['obj_id']) ? $_POST['obj_id'] : "");
$anticipo = (isset($_POST['anticipo']) ? $_POST['anticipo'] : 0);
$arrayper = (isset($_POST['arrayper']) ? unserialize(urldecode(stripslashes($_POST['arrayper']))) : null);
$periodoscheck = (isset($_POST['periodoscheck']) ? $_POST['periodoscheck'] : '');
$interestasa = (isset($_POST['interestasa']) ? $_POST['interestasa'] : 0);

$montocuota = 0;
$financia = 0;

$anticipomanual = utb::getCampo("plan_config","cod=".$tplan,"anticipomanual");

 if ($cantper != count($arrayper))
 {
 	$nominal = 0;
    $accesor = 0;
    $multa = 0;
    $totaldeuda = 0;
	
	for ($j=0; $j<count($arrayper); $j++)
    {
    	$arrayper[$j]['marca'] = " ";
    }
	
	for ($i=0; $i<$cantper; $i++)
	{
		$nominal += $arrayper[$periodoscheck[$i]]['nominal'];
        $accesor += $arrayper[$periodoscheck[$i]]['accesor'];
        $multa += $arrayper[$periodoscheck[$i]]['multa'];
        $arrayper[$periodoscheck[$i]]['marca'] = "X";
	}	
	$totaldeuda += $nominal + $accesor + $multa;
	
	echo '<script>$("#arrayPeriodo").val("'.urlencode(serialize($arrayper)).'")</script>';
 }
 
$error = (new Plan)->CalcularPlan($totaldeuda,$cantper,$cantcuotas,$fchcons,$tplan,$obj_id,$anticipo,$montocuota,$financia,$interestasa);

if ( $error == "" || !isset($_POST['nominal']) )
{
	echo '<script>';
	echo '$("#errorplannuevo").html("");';
	echo '$("#errorplannuevo").css("display", "none");';
	echo '</script>';
}else {
	if (is_array($error)){
		echo '<script>';
		echo 'var error = new Array();';
		foreach ( $error as $array )
		{
			echo 'error.push("'.$error.'");';
		}
		echo 'mostrarErrores( error, "#plannuevo_errorSummary");';
		echo '</script>';
	}else {
		echo '<script>';
		echo 'mostrarErrores(["'.$error.'"], "#plannuevo_errorSummary");';
		echo '</script>';
	}	
}

# Formateado de números

$nominal = number_format( $nominal, 2, '.', '' );
$accesor = number_format( $accesor, 2, '.', '' );
$multa = number_format( $multa, 2, '.', '' );
$totaldeuda = number_format( $totaldeuda, 2, '.', '' );
$financia = number_format( $financia, 2, '.', '' );
$anticipo = number_format( $anticipo, 2, '.', '' );
$montocuota = number_format( $montocuota, 2, '.', '' );

?>
<div style="overflow:hidden">
<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px; float:left'>
	<label><u>Detalle de Deuda</u></label><br>
	<table style="margin:0px 70px">
	<tr>
		<td><label>Nominal</label></td>
		<td>
			<?= Html::input('text', 'txNominal', $nominal, ['class' => 'form-control','id'=>'txNominal', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		</td>
	</tr>
	<tr>	
		<td><label>Accesor</label></td>
		<td>
			<?= Html::input('text', 'txAccesor', $accesor, ['class' => 'form-control','id'=>'txAccesor', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		</td>
	</tr>
	<tr>
		<td><label>Multa</label></td>
		<td>
			<?= Html::input('text', 'txMulta', $multa, ['class' => 'form-control','id'=>'txMulta', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		<td>
	</tr>
	<tr>
		<td colspan='2'><hr style="color: #ddd; margin:1px" /></td>
	</tr>
	<tr>
		<td><label>SubTotal</label></td>
		<td>
			<?= Html::input('text', 'txSubTotal', $totaldeuda, ['class' => 'form-control','id'=>'txSubTotal', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		</td>
	</tr>
	<tr>
		<td><label>Financiación</label></td>
		<td>
			<?= Html::input('text', 'txFinanc', $financia, ['class' => 'form-control','id'=>'txFinanc', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		</td>
	</tr>
	<tr>
		<td><label style='width:55px'>Sellado</label></td>
		<td>
			<?= Html::input('text', 'txSellado', null, ['class' => 'form-control','id'=>'txSellado', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		</td>
	</tr>
	<tr>
		<td colspan='2'><hr style="color: #ddd; margin:1px" /></td>
	</tr>
	<tr>
		<td><label>Total a Pagar</label></td>
		<td>
			<?= Html::input('text', 'txAPagar', number_format( $totaldeuda + $financia, 2, '.', '' ), ['class' => 'form-control','id'=>'txAPagar', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		</td>
	</tr>
	</table>
</div>
<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px;margin-left:10px;float:left;height:241px'>
	<label><u>Resumen de Cuotas</u></label><br>
	<table style="margin:0px 70px">
	<tr>
		<td><label>Anticipo:</label></td>
		<td>
			<?= Html::input('text', 'txAnticipo', $anticipo, ['class' => 'form-control','id'=>'txAnticipo', 'maxlength' => '10',
				'style'=>'width:70px; text-align:right','onchange'=>'VolverCalcuar()','readonly' => ($anticipomanual == 0)]); ?><br>
		</td>
	</tr>
	<tr>
		<td><label>Cantidad:</label></td>
		<td>
			<?= Html::input('text', 'txCantCuota', $cantcuotas, ['class' => 'form-control','id'=>'txCantCuota', 'maxlength' => '3','style'=>'width:70px; text-align:right','onchange'=>'VolverCalcuar()']); ?><br>
		</td>
	</tr>
	<tr>
		<td><label>Valor por Cuota</label></td>
		<td>
			<?= Html::input('text', 'txValorPorCuota', $montocuota, ['class' => 'form-control','id'=>'txValorPorCuota', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		</td>
	</tr>
	</table>
</div>
</div>

<?php 
	if (isset($_POST['nominal'])) echo "<script> CalcuarModal() </script>";
Pjax::end(); 
?>

<script>
function VolverCalcuar()
{
	$("#btCalcular").css("display","inline");
	$("#btAceptar").css("display","none");
	$("#btImpCtas").css("display","none");
	$("#btImpDetCuota").css("display","none");
}

function CalcuarModal()
{	
	$.pjax.reload({
		container:"#DivModalDetCuota",
		method:"POST"
	});
	
	$("#txDetCtaCapital").val($("#txSubTotal").val());
	$("#txDetCtaFinan").val($("#txFinanc").val());
	$("#txDetCtaSell").val($("#txSellado").val());
	$("#txDetCtaTotal").val($("#txAPagar").val());
}
</script>