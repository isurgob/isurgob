<div class='body' >
	<p class='tt'>Convenio de Pago Anterior - Listado de Cuotas</p>
	<p class='cond12'><?= $condicion ?></p>
	<table class='cond' width='100%' cellpadding='3' cellspacing='0'>
		<tr class='border_bottom'>
			<td width='12%' align='center'><b>Cuota</b></td><td width='12%' align='center'><b>Vencimiento</b></td>
			<td width='12%' align='right'><b>Capital</b></td><td width='12%' align='right'><b>Financia</b></td>
			<td width='12%' align='right'><b>Total</b></td><td width='12%' align='center'><b>Est</b></td>
			<td width='12%' align='center'><b>Fecha Pago</b></td><td width='12%' align='center'><b>Boleta</b></td>
		</tr>
		<?php
		for ($i=0; $i<count($datos); $i++)
		{
			echo "<tr><td align='center'>".$datos[$i]['cuota_nom']."</td><td align='center'>".$datos[$i]['fchvenc']."</td>" .
					"<td align='right'>".number_format($datos[$i]['capital'],2)."</td>" .
					"<td align='right'>".number_format($datos[$i]['financia'],2)."</td>" .
					"<td align='right'>".number_format($datos[$i]['total'],2)."</td>" .
					"<td align='center'>".$datos[$i]['est']."</td>" .
					"<td align='center'>".$datos[$i]['fchpago']."</td>" .
					"<td align='center'>".$datos[$i]['boleta']."</td>" .
					"</tr>";
		}
		?>
	</table>
</div>