<?php
ini_set('memory_limit', '-1'); 
set_time_limit(0) ;
 
$estad = Yii::$app->session['estad'];
$condicion = Yii::$app->session['cond'];
$sub1 = Yii::$app->session['sub1'];
$sub2 = Yii::$app->session['sub2'];

?>
<div class='body' >
	<p class='tt'><?= $estad->nombre ?></p>
	<p class='cond12'><?= $condicion ?></p>
	<table width='100%' class='cond' cellpadding='3' cellspacing='0' >
		<tr class='border_bottom'><td><b>Ticket</b></td><td><b>Operación</b></td><td><b>Hora</b></td><td><b>Tributo</b></td><td><b>Objeto</b></td><td><b>Nombre</b></td><td><b>Año</b></td><td><b>Cuota</b></td><td><b>Monto</b></td><td><b><?= ($estad->tipo != 'CompUnaCta' && $estad->cod != 91 ? 'Est' : '') ?></b></td></tr>
		<?php
		$cant = count($sub1); $totalanula = 0; $totalcobrado=0; 
		for ($i=0; $i<count($sub1); $i++)
		{
			if ($estad->tipo == 'CompUnaCta' or $estad->cod == 91) {
				$totalcobrado += $sub1[$i]['monto'];	
			}else {
				if ($sub1[$i]['est'] == 'A') $totalcobrado += $sub1[$i]['monto'];
				if ($sub1[$i]['est'] == 'B') $totalanula += $sub1[$i]['monto'];	
			}
			echo "<tr ".(count($sub2) > 0 && $i>0 ? "class='border_top'" : "")."><td align='center'>".$sub1[$i]['ticket']."</td><td align='center'>".$sub1[$i]['opera']."</td><td align='center'>".$sub1[$i]['hora']."</td><td align='left'>".$sub1[$i]['trib_nom']."</td><td align='center'>".$sub1[$i]['obj_id']."</td><td align='left'>".$sub1[$i]['obj_nom']."</td><td align='center'>".$sub1[$i]['anio']."</td><td align='center'>".$sub1[$i]['cuota']."</td><td align='right'>".number_format($sub1[$i]['monto'],2)."</td><td align='center'>".$sub1[$i]['est']."</td></tr>";
			
			if ($sub2 != null){
				for ($j=0; $j<count($sub2); $j++){
					if ($sub1[$i]['ticket'] == $sub2[$j]['ticket'])
						echo "<tr><td colspan='3'></td><td colspan='3' class='desc8'>".$sub2[$j]['cta_id'].' - '.$sub2[$j]['cta_nom']."</td><td class='desc8'>".$sub2[$j]['monto']."</td></tr>";
				}
			}
		}
		?>
		<tr class='border_top'>
			<td></td>
			<td colspan='3'><b>Cant. de Comprobante: <?= $cant?></b></td>
			<td></td>
			<td><b><?= ($estad->tipo == 'CompUnaCta' || $estad->cod == 91 ? '' : 'Total Anulado: '.$totalanula) ?></b></td>
			<td></td>
			<td colspan='4'><b><?= ($estad->tipo == 'CompUnaCta' || $estad->cod == 91 ? 'Total: ' : 'Total Cobrado: ').$totalcobrado?></b></td>
		</tr>
	</table>
</div>	