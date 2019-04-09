<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;
?>
<div class='body' >
	<p class='tt'> Resumen de Lote de Intimación </p>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='100px'><b>Lote:</b></td><td width='20px'><?= $datos[0]['lote_id'] ?></td>
				<td width='20px'><b>Fecha:</b></td><td><?= $datos[0]['fchinti'] ?></td>
				<td width='100px'><b>Tipo Intimación:</b></td><td><?= $datos[0]['tipo_nom'] ?></td>
			</tr>
			<tr>
				<td><b>Tipo Objeto:</b></td><td colspan='3'><?= $datos[0]['tobj_nom'] ?></td>
				<td><b>Tributo:</b></td><td><?= $datos[0]['trib_nom']  ?></td>
			</tr>
			<tr>
				<td><b>Generado por:</b></td><td colspan='3'><?= $datos[0]['usuiti_nom'] ?></td>
				<td><b>Aprobado por:</b></td><td><?= $datos[0]['usuaprob_nom']  ?></td>
			</tr>
			<tr>
				<td valign='top'><b>Detalle: </b></td><td colspan='5' valign='top'><?= $datos[0]['detalle']?></td>
			</tr>
		</table>
	</div>
	<table class='desc10' width='100%' cellspacing='0' cellpadding='4'>
		<tr class='border_bottom'><td><b>Objeto</b></td><td><b>Cta.</b></td><td><b>NUM</b></td><td><b>Nombre</b></td>
			<td><b>Domicilio</b></td><td><b>Estado</b></td><td><b>Total</b></td><td><b>Cód.Barra</b></td></tr>
	<?php
		for ($i=0; $i<count($datos); $i++){
			echo "<tr>";
			echo "<td>".$datos[$i]['obj_id']."</td>";
			echo "<td>".$datos[$i]['subcta']."</td>";
			echo "<td>".$datos[$i]['num']."</td>";
			echo "<td>".$datos[$i]['nombre']."</td>";
			echo "<td>".$datos[$i]['dompos_dir']."</td>";
			echo "<td>".$datos[$i]['est_nom']."</td>";
			echo "<td align='right'>".$datos[$i]['total']."</td>";
			echo "<td><barcode code=".$datos[$i]['codbarra']." type='C128C' size='0.7' /></td>";
			echo "</tr>";
		}
	?>
	</table>
	<hr style="color: #000; margin:1px" />
	<div class='cond' align='right'>
		<b>Cantidad de Objetos:&nbsp;<?= $datos[0]['cant'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Deuda Total:&nbsp;<?= $datos[0]['total'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
	</div>
</div>