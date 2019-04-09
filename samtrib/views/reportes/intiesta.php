<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;
?>
<div class='body' >
	<p class='tt'> Estadística de Lote de Intimación</p>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'>
				<td width='120px'><b>Lote Número:</b></td><td width='20px'><b><?= $datos[0]['lote_id'] ?></b></td>
				<td width='100px'><b>Título:</b></td><td><b><?= $datos[0]['titulo'] ?></b></td>
			</tr>
			<tr>
				<td><b>Tipo de Intimación:</b></td><td><?= $datos[0]['tipo_nom'] ?></td>
				<td><b></b></td><td></td>
			</tr>
			<tr class='border_bottom'>
				<td><b>Tipo de Objeto:</b></td><td><?= $datos[0]['tobj_nom']  ?></td>
				<td><b>Tributo:</b></td><td><?= $datos[0]['trib_nom']  ?></td>
			</tr>
			<tr class='border_bottom'>
				<td><b>Cantidad de Objetos:</b></td><td><b><?= $datos[0]['cant']  ?></b></td>
				<td><b>Deuda Total:</b></td><td><b><?= $datos[0]['deuda']  ?></b></td>
			</tr>
			<tr class='border_bottom'>
				<td><b>Alta:</b></td><td><b><?= $datos[0]['alta']  ?></b></td>
				<td><b>Aprobación:</b></td><td><b><?= $datos[0]['aprobacion']  ?></b></td>
			</tr>
			<tr><td colspan='4'><b>Filtro Utilizado:</b></td></tr>
			<tr><td colspan='4'><?= $datos[0]['detalle']  ?></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Resultados</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Resultado</b></td><td><b>Cantidad</b></td><td><b>Monto</b></td></tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				echo "<tr>";
				echo "<td>".$sub1[$i]['nombre']."</td>";
				echo "<td>".$sub1[$i]['count']."</td>";
				echo "<td align='right'>".$sub1[$i]['monto']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Estados</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Estado</b></td><td><b>Cantidad</b></td><td><b>Monto</b></td></tr>
		<?php
			for ($i=0; $i<count($sub2); $i++){
				echo "<tr>";
				echo "<td>".$sub2[$i]['nombre']."</td>";
				echo "<td>".$sub2[$i]['count']."</td>";
				echo "<td align='right'>".$sub2[$i]['monto']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
</div>