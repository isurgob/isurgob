<div class='body' >
	<p class='tt'> Estadísticas Expedientes de Juzgado </p>
	<p class='cond'> Fecha de Expediente desde <?=$fchdesde?> hasta <?=$fchhasta?> </p>
	
	<table border="0" class="cond" width="100%">
	<tr class="border_bottom">
		<td></td><td><b>Infracción</b></td><td><b>Norma</b></td><td><b>Artículo</b></td><td><b>Inciso</b></td><td><b>Cantidad</b></td>
	</tr>
	<tr>
		<td><b>Origen:</b></td><td><b><?=$datos[0]["origendescr"]?></b></td><td colspan="4"></td>
	</tr>
	<?php 
		$totalcant = 0;
		foreach ($datos as $key => $valor){ 
			$totalcant += $valor["cant"];
	?>
	<tr>
		<td></td><td><?=$valor["infracdescr"]?></td><td><?=$valor["norma"]?></td><td align="right"><?=$valor["art"]?></td><td><?=$valor["inc"]?></td><td align="right"><?=$valor["cant"]?></td>
	</tr>
	<?php } ?>
	<tr>
		<td></td><td></td><td></td><td></td><td align="right" class="border_top_solid"><b>Total: </b></td><td class="border_top_solid" align="right"><b><?=$totalcant?></b></td>
	</tr>
	</table>
	
</div>