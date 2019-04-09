<div class='body' >
	<p class='tt'> Reporte de Fallecido </p>
	<div class='tt14' style='margin-top:10px;'> <b>Identificación</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'>Código:</td><td><?= $datos[0]['fall_id'] ?></td>
				<td width='10px'>Nombre:</td><td><?= $datos[0]['apenom'] ?></td>
				<td width='30px'>Estado:</td><td><b><?= $datos[0]['est_nom'] ?></b></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Datos Personales</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='30px'>Documento:</td><td><?= $datos[0]['tdoc_nom'].' '.$datos[0]['ndoc'] ?></td>
				<td width='30px'>Sexo:</td><td><?= $datos[0]['sexo'] ?></td>
				<td width='30px'>Estado Civil:</td><td><?= $datos[0]['estcivil_nom'] ?></td>
			</tr>
			<tr>
				<td width='30px'>Domicilio Postal:</td><td colspan='2'><?= $datos[0]['domi'] ?></td>
				<td width='30px'>Nacionalidad:</td><td><?= $datos[0]['nacionalidad_nom'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Objeto</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellpadding='4' cellspacing='0'>
			<tr>
				<td width='30px'>Objeto:</td><td><?= $datos[0]['obj_id'] ?></td>
				<td width='30px'>Tipo:</td><td><?= $datos[0]['tipo_nom'] ?></td>
				<td>Cuadro: <?= $datos[0]['cuadro_id'] ?> Cuerpo: <?= $datos[0]['cuerpo_id'] ?> Fila: <?= $datos[0]['fila'] ?> Nume: <?= $datos[0]['nume'] ?> Piso: <?= $datos[0]['piso'] ?></td>
			</tr>
			<tr>
				<td width='30px'>Responsable:</td><td colspan='4'><?= $datos[0]['resp'].' '.$datos[0]['resp_nom'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Fechas</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellpadding='4' cellspacing='0'>
			<tr>
				<td width='30px'>Inhumación:</td><td><?= $datos[0]['fchinh'] ?></td>
				<td width='30px'>Defunción:</td><td><?= $datos[0]['fchdef'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Otros Datos</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellpadding='4' cellspacing='0'>
			<tr>
				<td width='30px'>Acta:</td><td><?= $datos[0]['actadef'] ?></td>
				<td width='30px'>Folio:</td><td><?= $datos[0]['foliodef'] ?></td>
				<td width='30px'>Procedencia:</td><td><?= $datos[0]['procedencia_nom'] ?></td>
			</tr>
			<tr>
				<td>Médico:</td><td><?= $datos[0]['med_nombre'] ?></td>
				<td></td><td></td>
				<td>Matrícula:</td><td><?= $datos[0]['med_matricula'] ?></td>
			</tr>
			<tr>
				<td>Empresa Fúnebre:</td><td><?= $datos[0]['emp_funebre'] ?></td>
				<td></td><td></td>
				<td></td><td></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Registros</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4' style='border-top:1px solid #000'>
			<tr class='border_bottom'><td><b>Orden</b></td><td><b>Fecha</b></td><td><b>Tipo Reg.</b></td><td><b>Origen</b></td><td><b>Destino</b></td><td><b>Localidad Destino</b></td></tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				echo "<tr>";
				echo "<td>".$sub1[$i]['orden']."</td>";
				echo "<td>".$sub1[$i]['fecha']."</td>";
				echo "<td>".$sub1[$i]['treg_nom']."</td>";
				echo "<td>".$sub1[$i]['origen']."</td>";
				echo "<td>".$sub1[$i]['destino']."</td>";
				echo "<td>".$sub1[$i]['loc_nom']."</td>";
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
	