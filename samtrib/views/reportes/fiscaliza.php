<div class='body' >
	<p class='tt'> Reporte de Fiscalización </p>
	<div class='divredon' style='margin-top:10px; padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'>Código:</td><td><b><?= $datos[0]['fisca_id'] ?></b></td>
				<td width='10px'>Expediente:</td><td><b><?= $datos[0]['expe'] ?></b></td>
				<td width='10px'>Comercio:</td><td><b><?= $datos[0]['obj_id'] . ' - ' .$datos[0]['obj_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Estado:</td><td coslpan='3'><b><?= $datos[0]['est_nom'] ?></b></td>
				<td>Inspector:</td><td><b><?= $datos[0]['inspector_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Fecha Alta:</td><td><b><?= $datos[0]['fchalta'] ?></b></td>
				<td>Fecha Baja:</td><td><b><?= $datos[0]['fchbaja'] ?></b></td>
				<td>Modificación:</td><td><b><?= $datos[0]['modif'] ?></b></td>
			</tr>
			<tr><td>Observación:</td><td coslpan='5'><b><?= $datos[0]['obs'] ?></b></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Rubros</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>Suc</b></td><td><b>Tributo</b></td><td><b>Rubro</b></td><td><b>Descripción</b></td>
				<td><b>Desde</b></td><td><b>Cant.</b></td><td><b>Est.</b></td>
			</tr>
			<?php 
			for ($i=0; $i<count($sub1);$i++){
				echo '<tr>';
				echo '<td>'.$sub1[$i]['subcta'].'</td>';
				echo '<td>'.$sub1[$i]['trib_nom'].'</td>';
				echo '<td>'.$sub1[$i]['rubro_id'].'</td>';
				echo '<td>'.$sub1[$i]['rubro_nom'].'</td>';
				echo '<td>'.$sub1[$i]['perdesde'].'</td>';
				echo '<td>'.$sub1[$i]['cant'].'</td>';
				echo '<td>'.$sub1[$i]['est_nom'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
</div>