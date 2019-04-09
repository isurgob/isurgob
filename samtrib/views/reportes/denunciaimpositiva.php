<div class='body' >
	<p class='tt'> Denuncia Impositiva <?= $modelObjeto->obj_id ?> </p>
	<div class='tt14' style='margin-top:10px;'> <b><u>Datos Actuales</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond'>
			<tr>
				<td><b> Objeto: </b> </td> <td> <?= $modelObjeto->obj_id ?> </td>
				<td width='20px'> </td>
				<td><b> Nombre: </b> </td> <td> <?= $modelObjeto->nombre ?> </td>
				<td width='20px'> </td>
				<td><b> Dato: </b> </td> <td> <?= $modelObjeto->obj_dato ?> </td>
				<td width='20px'> </td>
				<td><b> Estado: </b> </td> <td> <?= $modelObjeto->est_nom ?> </td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Titulares Actuales</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%'>
			<tr class='border_bottom'>
				<td><b> Código </b> </td>
				<td><b> Apellido y Nombre </b> </td>
				<td><b> TDoc</b> </td> 
				<td><b> NDoc </b> </td>
				<td><b> Relac </b> </td>
				<td><b> Porc </b> </td>
				<td><b> Est </b> </td>
			</tr>
			<?php  
				foreach ($modelObjeto->arregloTitulares as $titulares)
				{
					echo "<tr>";
					echo "<td>" . $titulares['num'] . "</td>";
					echo "<td>" . $titulares['apenom'] . "</td>";
					echo "<td>" . $titulares['tdoc'] . "</td>";
					echo "<td>" . $titulares['ndoc'] . "</td>";
					echo "<td>" . $titulares['tvinc_nom'] . "</td>";
					echo "<td>" . $titulares['porc'] . "</td>";
					echo "<td>" . $titulares['est'] . "</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>
	<div class='cond' style='margin-top:10px;'> <b>Domicilio Postal:</b> <?= $modelObjeto->domi_postal ?> </div>
</div>