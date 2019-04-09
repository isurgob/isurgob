<?php
$estad = Yii::$app->session['estad'];
$condicion = Yii::$app->session['cond'];
$sub1 = Yii::$app->session['sub1'];

?>
<div class='body'>
	<p class='tt'><?= $estad->nombre ?></p>
	<p class='cond12'><?= $condicion ?></p>
	<div class='divredon' style='padding:5px; margin-top:10px'>
		<table width='100%' class='cond' cellpadding='3' cellspacing='0'>
			<tr><td colspan='3' class='border_right'></td><td colspan='2' align='center' class='border_right_bottom'><b>Emitido</b></td><td colspan='4' align='center' class='border_right_bottom'><b>Pagado</b></td><td colspan='2' align='center' class='border_bottom'><b>Convenio</b></td></tr>
			<tr class='border_bottom'><td align='center'><b>Año</b></td><td align='center'><b>Cuota</b></td><td align='center' class='border_right'><b>Cant</b></td>
				<td align='center'><b>Cantidad</b></td><td align='right' class='border_right'><b>Monto</b></td>
				<td align='center'><b>Cantidad</b></td><td align='right'><b>Monto</b></td><td align='right'><b>Interes</b></td><td align='right' class='border_right'><b>Porcentaje</b></td>
				<td align='center'><b>Cantidad</b></td><td align='right'><b>Monto</b></td>
			</tr>
			<?php
			for ($i=0; $i<count($sub1); $i++)
			{
				echo "<tr><td align='center'>".$sub1[$i]['anio']."</td><td align='center'>".$sub1[$i]['cuota']."</td><td align='center' class='border_right'>".$sub1[$i]['cant']."</td>" .
						"<td align='center'>".$sub1[$i]['cantemi']."</td><td align='right' class='border_right'>".number_format($sub1[$i]['montoemi'],2)."</td>" .
						"<td align='center'>".$sub1[$i]['cantpago']."</td><td align='right'>".number_format($sub1[$i]['montopago'],2)."</td><td align='right'>".number_format($sub1[$i]['accpago'],2)."</td><td align='right' class='border_right'>".number_format($sub1[$i]['porcpago'],2)."</td>" .
						"<td align='center'>".$sub1[$i]['cantconv']."</td><td align='right'>".number_format($sub1[$i]['montoconv'],2)."</td>" .
					"</tr>";
			}
			?>
		</table>
	</div>
</div>	