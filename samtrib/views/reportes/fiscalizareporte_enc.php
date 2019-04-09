<table class='cond' width='100%' cellspacing='0' cellpadding='4' style='border:1px solid #000'>
	<tr class='border'>
		<td width='30px'><b>Contribuyente:</b></td><td colspan='5'><?= $datos[0]['num_nom'] ?></td>
	</tr>
	<tr class='border'>
		<td><b>Denominación:</b></td><td colspan='5'><?= $datos[0]['obj_nom'] ?></td>
	</tr>
	<tr class='border'>
		<td><b>Domicilio:</b></td><td colspan='3'><?= $datos[0]['dompar_dir'] ?></td>
		<td width='70px'><b>Número:</b></td><td><?= $datos[0]['obj_id'] ?></td>
	</tr>
	<tr class='border'>
		<td><b>CUIT Nº:</b></td><td><?= $datos[0]['cuit'] ?></td>
		<td width='80px'><b>ISIB-CM Nº:</b></td><td><?= $datos[0]['ib'] ?></td>
		<td><b>Legajo Nº:</b></td><td><?= $datos[0]['legajo'] ?></td>
	</tr>
	<tr class='border'>
		<td><b>Actividad:</b></td><td colspan='3'><?= $datos[0]['actividad'] ?></td>
		<td><b>Incio:</b></td><td><?= $datos[0]['fchhab'] ?></td>
	</tr>
	<tr class='border'>
		<td><b>Fecha:</b></td><td colspan='3'><?= $datos[0]['fchalta'] ?></td>
		<td><b>Expe.N°:</b></td><td><?= $datos[0]['expe'] ?></td>
	</tr>
</table>