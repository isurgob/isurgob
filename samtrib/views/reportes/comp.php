<div class='body' >
	<p class='tt'> Compensación </p>
	<div class='tt14' style='margin-top:10px;'> <b>Datos Principales</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='30px'><b>Código:</b></td><td><?= $datos[0]['comp_id'] ?></td>
				<td width='90px'><b>Expediente:</b></td><td><?= $datos[0]['expe'] ?></td>
				<td width='30px'><b>Estado:</b></td><td><?= $datos[0]['est_nom'] ?></td>
			</tr>
			<tr>
				<td><b>Tipo:</b></td><td><?= $datos[0]['tipo_nom'] ?></td>
				<td><b>Fecha Alta:</b></td><td><?= $datos[0]['fchalta'] ?></td>
				<td><b>Consolidación:</b></td><td><?= $datos[0]['fchconsolida']  ?></td>
			</tr>
			<tr>
				<td colspan='3'><b>Origen: &nbsp;&nbsp;Tributo: </b> &nbsp;&nbsp; <?= $datos[0]['trib_ori_nom'].($datos[0]['trib_ori'] == 1 or $datos[0]['trib_ori'] == 2 ? 'Nro.Conv.:' : 'Objeto:')  ?></td>
				<td><?= $datos[0]['obj_ori'].' - '.$datos[0]['obj_ori_nom'] ?></td>
			</tr>
			<tr>
				<td colspan='3'><b>Destino: &nbsp;&nbsp;Tributo: </b> &nbsp;&nbsp; <?= $datos[0]['trib_dest_nom'].($datos[0]['trib_dest'] == 1 or $datos[0]['trib_dest'] == 2 ? 'Nro.Conv.:' : 'Objeto:')  ?></td>
				<td><?= $datos[0]['obj_dest'].' - '.$datos[0]['obj_dest_nom'] ?></td>
			</tr>
			<tr>
				<td><b>Monto:</b></td><td><?= $datos[0]['monto']  ?></td>
				<td><b>Monto Aplic.:</b></td><td><?= $datos[0]['monto_aplic']  ?></td>
			</tr>
		</table>
	</div>
	<?php if ($datos[0]['tipo'] == 3 or $datos[0]['tipo'] == 4) { ?>
	<div class='tt14' style='margin-top:20px;'> <b>Origen</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Cta.Cte</b></td><td><b>Objeto</b></td><td><b>SubCta</b></td><td><b>Tributo</b></td><td><b>Año</b></td><td><b>Cuota</b></td><td><b>Saldo</b></td><td><b>Saldo Cub.</b></td></tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				echo "<tr>";
				echo "<td>".$sub1[$i]['ctacte_id']."</td>";
				echo "<td>".$sub1[$i]['obj_id']."</td>";
				echo "<td>".$sub1[$i]['subcta']."</td>";
				echo "<td>".$sub1[$i]['trib_nom']."</td>";
				echo "<td>".$sub1[$i]['anio']."</td>";
				echo "<td>".$sub1[$i]['cuota']."</td>";
				echo "<td>".$sub1[$i]['saldo']."</td>";
				echo "<td>".$sub1[$i]['saldo_cub']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<?php } ?>
	<div class='tt14' style='margin-top:20px;'> <b>Destino</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Cta.Cte</b></td><td><b>Objeto</b></td><td><b>SubCta</b></td><td><b>Tributo</b></td><td><b>Año</b></td><td><b>Cuota</b></td><td><b>Monto Aplic.</b></td><td><b>Fecha</b></td></tr>
		<?php
			for ($i=0; $i<count($sub2); $i++){
				echo "<tr>";
				echo "<td>".$sub2[$i]['ctacte_id']."</td>";
				echo "<td>".$sub2[$i]['obj_id']."</td>";
				echo "<td>".$sub2[$i]['subcta']."</td>";
				echo "<td>".$sub2[$i]['trib_nom']."</td>";
				echo "<td>".$sub2[$i]['anio']."</td>";
				echo "<td>".$sub2[$i]['cuota']."</td>";
				echo "<td>".$sub2[$i]['montoaplic']."</td>";
				echo "<td>".$sub2[$i]['fecha']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Observaciones</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td><?= $datos[0]['obs'] ?></td></tr>
		</table>
	</div>
</div>