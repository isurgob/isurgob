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
			<tr class='border_bottom'><td><b>Tipo</b></td><td align='center'><b>Cantidad</b></td><td align='right'><b>Monto Anterior</b></td><td align='right'><b>Monto Nuevo</b></td><td align='right'><b>Diferencia</b></td></tr>
			<?php
			for ($i=0; $i<count($sub1); $i++)
			{
				echo "<tr><td>".$sub1[$i]['tipo']."</td><td align='center'>".$sub1[$i]['cant']."</td><td align='right'>".$sub1[$i]['montoviejo']."</td><td align='right'>".$sub1[$i]['montonuevo']."</td><td align='right'>".$sub1[$i]['dif']."</td></tr>";
			}
			?>
		</table>
	</div>
</div>	