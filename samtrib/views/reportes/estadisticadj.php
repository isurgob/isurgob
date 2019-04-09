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
			<tr><td colspan='3' class='border_right'></td><td colspan='3' align='center' class='border_right_bottom'><b>Período 1</b></td><td colspan='3' align='center' class='border_right_bottom'><b>Período 2</b></td><td colspan='3' align='center' class='border_bottom'><b>Período 3</b></td></tr>
			<tr class='border_bottom'><td><b>Objeto</b></td><td><b>Legajo</b></td><td class='border_right'><b>Nombre</b></td>
				<td align='center'><b>Base</b></td><td align='center'><b>Monto</b></td><td align='center' class='border_right'><b>Est</b></td>
				<td align='center'><b>Base</b></td><td align='center'><b>Monto</b></td><td align='center' class='border_right'><b>Est</b></td>
				<td align='center'><b>Base</b></td><td align='center'><b>Monto</b></td><td align='center'><b>Est</b></td>
			</tr>
			<?php
			for ($i=0; $i<count($sub1); $i++)
			{
				echo "<tr><td>".$sub1[$i]['obj_id']."</td><td>".$sub1[$i]['legajo']."</td><td>".$sub1[$i]['nombre']."</td>" .
						"<td align='right'>".$sub1[$i]['base1']."</td><td align='right'>".$sub1[$i]['monto1']."</td><td align='center'>".$sub1[$i]['est1']."</td>" .
						"<td align='right'>".$sub1[$i]['base2']."</td><td align='right'>".$sub1[$i]['monto2']."</td><td align='center'>".$sub1[$i]['est2']."</td>" .
						"<td align='right'>".$sub1[$i]['base2']."</td><td align='right'>".$sub1[$i]['monto3']."</td><td align='center'>".$sub1[$i]['est3']."</td>" .
					"</tr>";
			}
			?>
		</table>
	</div>
</div>	