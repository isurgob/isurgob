<?php
$estad = Yii::$app->session['estad'];
$condicion = Yii::$app->session['cond'];
$sub1 = Yii::$app->session['sub1'];

?>
<div class='body' >
	<p class='tt'><?= $estad->nombre ?></p>
	<p class='cond12'><?= $condicion ?></p>
	<div class='divredon'>
		<table width='100%' class='cond' cellspacing='5'>
			<tr class='border_bottom'><td><b>Clasificación</b></td><td align='center'><b>Cantidad</b></td><td align='right'><b>Monto</b></td><td align='center'><b>Cant.Pago</b></td><td align='right'><b>Monto Pago</b></td></tr>
			<?php
			for ($i=0; $i<count($sub1); $i++)
			{
				echo "<tr><td>".$sub1[$i]['clasif']."</td><td align='center'>".$sub1[$i]['cant']."</td><td align='right'>".$sub1[$i]['monto']."</td><td align='center'>".$sub1[$i]['cant_pago']."</td><td align='right'>".$sub1[$i]['monto_pago']."</td></tr>";
			}
			?>
		</table>
	</div>
</div>	