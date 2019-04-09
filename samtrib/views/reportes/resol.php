<div class='body' >
	<p class='tt'> Reporte de Resolución </p>
	<div class='divredon' style='margin-top:10px; padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'>Id:</td><td><b><?= $datos[0]['resol_id'] ?></b></td>
				<td width='10px'>Nombre:</td><td><b><?= $datos[0]['nombre'] ?></b></td>
				<td width='10px'>Tributo:</td><td><b><?= $datos[0]['trib_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Vigencia:</td><td colspan='3'>desde: <b><?= $datos[0]['per_desde'] ?></b> hasta: <b><?= $datos[0]['per_hasta'] ?></b></td>
				<td>Función:</td><td><b><?= $datos[0]['funcion'] ?></b></td>
			</tr>
			<tr><td>Filtro:</td><td colspan='6'><b><?= $datos[0]['filtro'] ?></b></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Tablas</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>Nombre</b></td><td width='80px' align='center'><b>Cant.Col.</b></td><td width='80px' align='center'><b>Cant.Col.Fijas</b></td>
			</tr>
			<?php 
			for ($i=0; $i<count($sub1);$i++){
				echo '<tr>';
				echo '<td>'.$sub1[$i]['nombre'].'</td>';
				echo '<td align="center">'.$sub1[$i]['cantcol'].'</td>';
				echo '<td align="center">'.$sub1[$i]['cantcolfijas'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Columnas</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>Nombre Tabla</b></td><td><b>Nombre Columna</b></td>
			</tr>
			<?php 
			for ($i=0; $i<count($sub2);$i++){
				echo '<tr>';
				echo '<td>'.$sub2[$i]['tabla'].'</td>';
				echo '<td>'.$sub2[$i]['columna'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Datos</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>Nombre Tabla</b></td><td><b>Desde</b></td><td><b>Hasta</b></td>
				<td><b>Parámetro 1</b></td><td><b>Parámetro 2</b></td><td><b>Parámetro 3</b></td><td><b>Parámetro 4</b></td><td><b>Parámetro 5</b></td>
			</tr>
			<?php 
			for ($i=0; $i<count($sub3);$i++){
				echo '<tr>';
				echo '<td>'.$sub3[$i]['nombre'].'</td>';
				echo '<td>'.$sub3[$i]['perdesde'].'</td>';
				echo '<td>'.$sub3[$i]['perhasta'].'</td>';
				echo '<td>'.$sub3[$i]['param1'].'</td>';
				echo '<td>'.$sub3[$i]['param2'].'</td>';
				echo '<td>'.$sub3[$i]['param3'].'</td>';
				echo '<td>'.$sub3[$i]['param4'].'</td>';
				echo '<td>'.$sub3[$i]['param5'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
</div>