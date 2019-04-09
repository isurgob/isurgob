<div class='body' >
	<p class='tt'> Reporte de <?= $ib == 1 ? 'IIBB' : 'Comercio' ?> </p>
	<div class='tt14' style='margin-top:10px;'> <b>Identificación</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'>Nombre:</td><td><?= $datos[0]['num_nom'] ?></td>
				<td width='110px'>Unificado:</td><td><?= $datos[0]['objunifica'] ?></td>
				<td width='30px'>Estado:</td><td><b><?= $datos[0]['est_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Objeto:</td><td><?= $datos[0]['obj_id'] ?></td>
				<td>Nombre de Fantasía:</td><td><?= $datos[0]['nombre'] ?></td>
				<td></td><td></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Datos Principales</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='120px'>Situación IVA:</td><td><?= $datos[0]['iva_nom'] ?></td>
				<td width='120px'>CUIT:</td><td><?= $datos[0]['cuit'] ?></td>
				<td width='40px'>Ingr.Brutos:</td><td><?= $datos[0]['ib'] ?></td>
			</tr>
			<tr>
				<td>Tipo Liquidación:</td><td><?= $datos[0]['tipoliq_nom'] ?></td>
				<td>Organización Jurídica:</td><td><?= $datos[0]['orgjuri_nom'] ?></td>
				<td>Tipo:</td><td><?= $datos[0]['tipo_nom']  ?></td>
			</tr>
			<tr>
				<td>Cantidad Sucursales:</td><td><?= $datos[0]['cantsuc'] ?></td>
				<td>Sin Local Comercial:</td><td><?= ($datos[0]['sinlocal'] == 1 ? 'SI' : 'NO' ) ?></td>
				<td></td><td></td>
			</tr>
			<tr>
				<td>Distribuidor:</td><td><?= $datos[0]['distrib_nom'] ?></td>
				<td></td><td></td><td></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Fechas</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellpadding='4' cellspacing='0'>
			<tr>
				<td width='30px'>Alta:</td><td><?= $datos[0]['fchalta'] ?></td>
				<td width='30px'>Baja:</td><td><?= $datos[0]['fchbaja'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Domicilio</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='30px'>Parcelario:</td><td><?= $modelDomicilioParcelario->domicilio ?></td><td width='30px'>Barrio:</td><td><?= $datos[0]['barrpar_nom'] ?></td></tr>
			<tr><td width='30px'>Postal:</td><td colspan='2'><?= $modelDomicilioPostal->domicilio ?></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Titulares</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4' style='border-top:1px solid #000'>
			<tr class='border_bottom'><td><b>Num</b></td><td><b>Nombre</b></td><td><b>Tipo Doc.</b></td><td><b>Número</b></td><td><b>Vínculo</b></td><td><b>Porc</b></td></tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				echo "<tr>";
				echo "<td>".$sub1[$i]['num']."</td>";
				echo "<td>".$sub1[$i]['apenom']."</td>";
				echo "<td>".$sub1[$i]['tdoc']."</td>";
				echo "<td>".$sub1[$i]['ndoc']."</td>";
				echo "<td>".$sub1[$i]['tvinc_nom']."</td>";
				echo "<td>".$sub1[$i]['porc']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Rubros</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Suc.</b></td><td><b>Código</b></td><td><b>Descripción</b></td><td><b>Desde</b></td><td><b>Tipo</b></td><td><b>Estado</b></td></tr>
		<?php
			for ($i=0; $i<count($sub2); $i++){
				echo "<tr>";
				echo "<td>".$sub2[$i]['subcta']."</td>";
				echo "<td>".$sub2[$i]['rubro_id']."</td>";
				echo "<td>".$sub2[$i]['rubro_nom']."</td>";
				echo "<td>".$sub2[$i]['perdesde']."</td>";
				echo "<td>".$sub2[$i]['tipo_nom']."</td>";
				echo "<td>".$sub2[$i]['est_nom']."</td>";
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
	