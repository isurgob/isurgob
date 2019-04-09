<?php
$estad = Yii::$app->session['estad'];
$condicion = Yii::$app->session['cond'];
$sub1 = Yii::$app->session['sub1'];
$total = 0;
?>
<div class='body' >
	<p class='tt'><?= $estad->nombre ?></p>
	<p class='cond12'><?= $condicion ?></p>

	<table width='100%' class='cond' cellspacing='5'>
		<tr class='border_bottom'>
			<?php 
			if ($estad->tipo == 'Item') 
				echo "<td align='center'><b>Fecha</b></td><td align='center'><b>Orig</b></td>"; 
			?>
			<td><b>Item</b></td>
			<td align='right'><b>Monto</b></td></tr>
		<?php
			for ($i=0; $i<count($sub1); $i++)
			{
				$total += $sub1[$i]['monto'];
				echo "<tr>";
				if ($estad->tipo == 'Item') echo "<td align='center'>".$sub1[$i]['fecha']."</td><td>".$sub1[$i]['orig']."</td>";
				echo "<td>".$sub1[$i]['item_id'].' - '.$sub1[$i]['item_nom']."</td><td align='right'>".number_format($sub1[$i]['monto'],2)."</td></tr>";
			}
		?>
		<tr class='border_top'>
			<?php if ($estad->tipo == 'Item') echo "<td></td><td></td>"; ?>
			<td align='right'><b>Total:</b></td>
			<td align='right'><b><?= $total?></b></td>
		</tr>
	</table>
</div>	