<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;
?>
<div class='body' >
	<p class='tt'> CERTIFICADO DE DEUDA FISCAL </p>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr>
				<td width='80px'><b>Número:</b></td><td><b><?= $datos[0]['judi_id'] ?></b></td>
				<td width='120px'>Expediente:</td><td><b><?= $datos[0]['expe'] ?></b></td>
			</tr>
			<tr>
				<td><b>Carátula:</b></td><td><b><?= $datos[0]['caratula'] ?></b></td>
				<td><b>Objeto:</b></td><td><b><?= $datos[0]['obj_id'] ?></b></td>				
			</tr>
			<tr>
				<td>Responsable:</td><td><?= $datos[0]['num_nom'] ?></td>
				<td>Dato:</td><td><?= $datos[0]['obj_dato'] ?></td>				
			</tr>
			<tr>
				<td>Domicilio:</td><td><?= $datos[0]['dompos_dir'] ?></td>
				<td>Fecha Consolidación:</td><td><?= $datos[0]['fchalta'] ?></td>				
			</tr>
			<tr><td>Rubro:</td><td colspan='2'><?= $datos[0]['rubro_nom'] ?></td></tr>
		</table>
	</div>
	<div class='cond' style='margin-top:10px;'><?= $datos[0]['mensaje'] ?></div>
	<div class='divredon' style='padding:5px;margin-top:10px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr>
				<td width='90px'>Nominal:</td><td><?= $datos[0]['nominal'] ?></td>
				<td width='70px'>Accesorio:</td><td><?= $datos[0]['accesor'] ?></td>
				<td width='90px'>Multa:</td><td><?= $datos[0]['multa'] ?></td>
				<td>Multa Omisión:</td><td><?= $datos[0]['multa_omi'] ?></td>
			</tr>
			<tr>
				<td><b>Total:</b></td>
				<td colspan='7'>
					<b><?= ($datos[0]['nominal']+$datos[0]['accesor']+$datos[0]['multa']+$datos[0]['multa_omi']) ?></b>
				</td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Detalle de los Períodos incluidos:</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'>
				<td><b>Tributo</b></td><td><b>Objeto</b></td><td><b>Dato</b></td><td><b>Año</b></td><td><b>Cuota</b></td>
				<td><b>Nominal</b></td><td><b>Accesorio</b></td><td><b>Multa</b></td><td><b>Total</b></td>
			</tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				echo "<tr>";
				echo "<td>".$sub1[$i]['trib_nom']."</td>";
				echo "<td align:'center'>".$sub1[$i]['obj_id']."</td>";
				echo "<td align='center'>".$sub1[$i]['obj_dato']."</td>";
				echo "<td align='center'>".$sub1[$i]['anio']."</td>";
				echo "<td align='center'>".$sub1[$i]['cuota']."</td>";
				echo "<td align='right'>".$sub1[$i]['nominal']."</td>";
				echo "<td align='right'>".$sub1[$i]['accesor']."</td>";
				echo "<td align='right'>".$sub1[$i]['multa']."</td>";
				echo "<td align='right'>".$sub1[$i]['total']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='cond' width='100%' style='overflow:hidden;padding:5px;margin-top:50px'>
	<table width='80%' class='desc' align='center'>
		<tr>
			<td class='border_top' align='center'><?= $firma1 ?></td><td width='20%'></td>
			<td class='border_top' align='center'><?= $firma2 ?></td>
		</tr>
	</table>
</div>
</div>