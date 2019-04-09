<?php
$estad = Yii::$app->session['estad'];
$condicion = Yii::$app->session['cond'];
$sub1 = Yii::$app->session['sub1'];

?>
<div class='body' >
	<p class='tt'><?= $estad->nombre ?></p>
	<p class='cond12' align='center'><?= $condicion ?></p>
	<table width='80%' class='cond' cellpadding='3' cellspacing='0' align='center'>
		<tr><td colspan='3' class='tt18' align='center'><b>Recuento de Efectivo y Valores - Caja Rentas Generales </b></td></tr>
		<tr class='border_bottom'><td colspan='4' class='tt18' align='center'>&nbsp;</td></tr>
		<tr class='border_bottom'><td align='center'><b>Unidades</b></td><td align='center'><b>Cantidades</b></td><td align='right'><b>Importes</b></td><td width='50px'>&nbsp;</td></tr>
		<tr><td align='center'> $ 1000.00 </td><td align='center'><?= $sub1[0]['cant1000_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant1000_00'] * 1000,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 500.00 </td><td align='center'><?= $sub1[0]['cant500_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant500_00'] * 500,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 200.00 </td><td align='center'><?= $sub1[0]['cant200_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant200_00'] * 200,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 100.00 </td><td align='center'><?= $sub1[0]['cant100_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant100_00'] * 100,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 50.00 </td><td align='center'><?= $sub1[0]['cant050_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant050_00'] * 50,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 20.00 </td><td align='center'><?= $sub1[0]['cant020_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant020_00'] * 20,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 10.00 </td><td align='center'><?= $sub1[0]['cant010_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant010_00'] * 10,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 5.00 </td><td align='center'><?= $sub1[0]['cant005_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant005_00'] * 5,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 2.00 </td><td align='center'><?= $sub1[0]['cant002_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant002_00'] * 2,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 1.00 </td><td align='center'><?= $sub1[0]['cant001_00'] ?></td><td align='right'><?= number_format($sub1[0]['cant001_00'] * 1,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 0.50 </td><td align='center'><?= $sub1[0]['cant000_50'] ?></td><td align='right'><?= number_format($sub1[0]['cant000_50'] * 0.5,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 0.25 </td><td align='center'><?= $sub1[0]['cant000_25'] ?></td><td align='right'><?= number_format($sub1[0]['cant00_25'] * 0.25,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 0.10 </td><td align='center'><?= $sub1[0]['cant000_10'] ?></td><td align='right'><?= number_format($sub1[0]['cant000_10'] * 0.10,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 0.05 </td><td align='center'><?= $sub1[0]['cant000_05'] ?></td><td align='right'><?= number_format($sub1[0]['cant000_05'] * 0.05,2) ?></td><td>&nbsp;</td></tr>
		<tr><td align='center'> $ 0.01 </td><td align='center'><?= $sub1[0]['cant000_01'] ?></td><td align='right'><?= number_format($sub1[0]['cant000_01'] * 0.01,2) ?></td><td>&nbsp;</td></tr>
		<tr class='border_top'><td colspan='4'></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Efectivo:</b></td><td align='right'><?= $sub1[0]['val_ef'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Retiro Efectivo:</b></td><td align='right'><?= $sub1[0]['cant_retiro'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Cheque:</b></td><td align='right'><?= $sub1[0]['val_ch'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Tarjeta de Crédito:</b></td><td align='right'><?= $sub1[0]['val_tc'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Tarjeta de Débito:</b></td><td align='right'><?= $sub1[0]['val_td'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Depósito:</b></td><td align='right'><?= $sub1[0]['val_de'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Transferencia:</b></td><td align='right'><?= $sub1[0]['val_tr'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Nota de Crédito:</b></td><td align='right'><?= $sub1[0]['val_nc'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Bonos:</b></td><td align='right'><?= $sub1[0]['val_bo'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Cesión de Haberes:</b></td><td align='right'><?= $sub1[0]['val_ha'] ?></td><td></td></tr>
		<tr><td colspan='1'></td><td align='right'><b>Otros:</b></td><td align='right'><?= $sub1[0]['val_ot'] ?></td><td></td></tr>
		<tr class='border_top'><td colspan='1'></td><td align='right'><b>Recuento:</b></td><td align='right'><?= $sub1[0]['recuento'] ?></td><td></td></tr>
	</table>
	<table width='80%' class='cond' cellpadding='3' cellspacing='0' align='center'>
		<tr class='border_bottom'><td colspan='5' class='cond12'><b>&nbsp;</b></td></tr>
		<tr class='border_bottom'><td colspan='5' class='cond12'><b>Totales Ingresados</b></td></tr>
		<tr><td width='80px'><b>Efectivo:</b></td><td align='right' width='100px'><?= $sub1[0]['efectivo'] ?></td><td width='160px' align='right'><b>Total:</b></td><td align='right'><?= $sub1[0]['total'] ?></td><td width='50px'>&nbsp;</td></tr>
		<tr><td><b>Otros Valores:</b></td><td align='right'><?= $sub1[0]['otros'] ?></td><td align='right'><b>Sobrante/Faltante:</b></td><td align='right'><?= $sub1[0]['sobrante'] ?></td><td>&nbsp;</td></tr>
		<tr><td><b>Fondo/Cambio:</b></td><td align='right'><?= $sub1[0]['fondo'] ?></td><td></td><td></td><td>&nbsp;</td></tr>
	</table>
</div>	