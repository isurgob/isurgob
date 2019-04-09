<?php
$estad = Yii::$app->session['estad'];
$condicion = Yii::$app->session['cond'];
$sub1 = Yii::$app->session['sub1'];
$sub2 = Yii::$app->session['sub2'];
$sub3 = Yii::$app->session['sub3'];
?>
<div class='body' >
	<p class='tt'><?= $estad->nombre ?></p>
	<p class='cond12'><?= $condicion ?></p>
	<div class='divredon'>
		<table width='100%' class='cond' cellspacing='5'>
			<tr class='border_bottom'><td><b>Totales Liquidados</b></td><td><b>Estado Cuenta Corriente</b></td></tr>
			<tr>
				<td>
					<table class='desc' cellpadding='3' cellspacing='0' style='border:1px solid #000;margin:0px 10px 10px 10px'>
						<tr class='border_bottom'><td><b>Item</b></td><td><b>Cant</b></td><td><b>Monto</b></td></tr>
						<?php
						for ($i=0; $i<count($sub1); $i++)
						{
							echo "<tr><td>".$sub1[$i]['item_id'].' '.$sub1[$i]['item_nom']."</td><td align='center'>".$sub1[$i]['cant']."</td><td align='right'>".number_format($sub1[$i]['monto'],2)."</td></tr>";
						}
						?>
					</table>			
				</td>
				<td valign='top'>
					<table class='desc' cellpadding='3' cellspacing='0' style='border:1px solid #000;margin:0px 10px 10px 10px'>
						<tr class='border_bottom'><td><b>Estado</b></td><td><b>Cant</b></td><td><b>Monto</b></td></tr>
						<?php
						for ($i=0; $i<count($sub2); $i++)
						{
							echo "<tr><td>".$sub2[$i]['est'].'  '.$sub2[$i]['est_nom']."</td><td align='right'>".$sub2[$i]['cant']."</td><td align='right'>".number_format($sub2[$i]['monto'],2)."</td></tr>";
						}
						?>
					</table>			
				</td>
			</tr>
		</table>
	</div>
	<div class='divredon' style='margin-top:10px'>
		<table width='100%' class='cond' cellpadding='3' cellspacing='0'>
			<tr><td><b>Evolución</b></td></tr>
			<tr><td>
				<table style='border:1px solid #000;margin:0px 10px 10px 10px' class='desc' cellpadding='3' cellspacing='0'>
					<tr><td colspan='3' class='border_right'></td><td colspan='2' align='center' class='border_right'><b>Informe General por Período</b></td><td colspan='4' align='center'><b>Pagado</b></td></tr>
					<tr class='border_bottom'><td><b>Año</b></td><td><b>Cuota</b></td><td class='border_right'><b>Cant</b></td><td align='center'><b>Cantidad</b></td><td class='border_right' align='right'><b>Monto</b></td><td><b>Cantidad</b></td><td align='right'><b>Monto</b></td><td><b>Accesorio</b></td><td><b>Porcentaje</b></td></tr>
					<?php
					for ($i=0; $i<count($sub3); $i++)
					{
						echo "<tr><td align='center'>".$sub3[$i]['anio'].'</td><td align="center">'.$sub3[$i]['cuota']."</td><td align='center' class='border_right'>".$sub3[$i]['cant']."</td>" .
								"<td align='center'>".$sub3[$i]['cant']."</td><td align='right' class='border_right'>".number_format($sub3[$i]['montoemi'],2)."</td>" .
								"<td align='center'>".$sub3[$i]['cantpago']."</td><td align='right'>".number_format($sub3[$i]['montopago'],2)."</td><td align='right'>".number_format($sub3[$i]['accpago'],2)."</td><td align='right'>".number_format($sub3[$i]['porcpago'],2)."</td></tr>";
					}
					?>
				</table>
			</td></tr>
		</table>
	</div>
	<?php
		//if ($grafico != '') echo "<br><br><img src=".$grafico.">";
	?>
</div>	