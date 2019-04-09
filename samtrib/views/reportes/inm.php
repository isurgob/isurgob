<div class='body' >
	<p class='tt'> Reporte de Inmuebles </p>
	<div class='tt14'> <b>Identificación</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'>Objeto:</td><td><?= $datos[0]['obj_id'].' - '.$datos[0]['nombre'] ?></td>
				<td width='50px'></td><td></td>
				<td width='110px'>Estado:</td><td><b><?= $datos[0]['est_nom'] ?></b></td>
			</tr>
			<tr>
				<td width='10px'>Partida Prov.:</td><td><?= $datos[0]['parp'] ?></td>
				<td width='10px'>Plano:</td><td><?= $datos[0]['plano'] ?></td>
				<td width='10px'>Partida Prov.Origen:</td><td><?= $datos[0]['parporigen']  ?></td>
			</tr>
			<tr>
				<td><b>Nomeclatura</b></td><td colspan='1'><?= $datos[0]['nc_guiones']  ?></td>
				<td><b>NC Ant.</b></td><td><?= $datos[0]['nc_ant']  ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Características</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='80px'>Régimen:</td><td><?= $datos[0]['regimen_nom'] ?></td>
				<td width='110px'>Unidad Funcional:</td><td><?= $datos[0]['uf'] ?></td>
				<td width='100px'>Pocentaje Unidad:</td><td><?= $datos[0]['porc_uf'] ?></td>
			</tr>
			<tr>
				<td>Urb.Sub.:</td><td><?= $datos[0]['urbsub_nom'] ?></td>
				<td>Tipo Inmueble:</td><td><?= $datos[0]['tinm_nom'] ?></td>
				<td>Titularidad:</td><td><?= $datos[0]['titularidad_nom']  ?></td>
			</tr>
			<tr>
				<td>Barrio:</td><td><?= $datos[0]['barr_nom'] ?></td>
				<td>Distribuidor:</td><td><?= $datos[0]['distrib'] ?></td>
				<td>Tipo Distribución:</td><td><?= $datos[0]['tdistrib']  ?></td>
			</tr>
			<tr>
				<td>Uso:</td><td><?= $datos[0]['uso_nom'] ?></td>
				<td>Sistema:</td><td><?= $datos[0]['sis_nom'] ?></td>
				<td>Inhibición:</td><td><?= $datos[0]['inhib_nom']  ?></td>
			</tr>
			<tr>
				<td>Plan Vivienda:</td><td><?= $datos[0]['planviv_nom'] ?></td>
				<td>Objeto SuperP:</td><td><?= $datos[0]['objeto_superp'] ?></td>
				<td>Año Mensura:</td><td><?= $datos[0]['anio_mensura']  ?></td>
			</tr>
			<tr>
				<td>Bien Familiar:</td><td><?= $datos[0]['bienflia'] ?></td>
				<td>Patrimonio Cultural:</td><td><?= $datos[0]['patrimonio'] ?></td>
				<td>Comprador:</td><td><?= $datos[0]['comprador']  ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Valuación</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellpadding='4' cellspacing='0'>
			<tr class='border_bottom'>
				<td width='30px'><b>Superficie:</b></td>
				<td width='30px'>Terreno:</td><td><?= $datos[0]['supt'] ?></td>
				<td width='30px'>Mejoras:</td><td><?= $datos[0]['supm'] ?></td>
				<td width='20px'>Pasillo:</td><td><?= $datos[0]['supt_pasillo'] ?></td>
				<td width='30px'>Mts.Frente:</td><td><?= $datos[0]['frente'] ?></td>
				<td width='80px'>Valor Básico:</td><td><?= $datos[0]['valbas'] ?></td>
			</tr>
			<tr class='border_bottom'>
				<td><b>Avalúo:</b></td>
				<td>Terreno:</td><td><?= $datos[0]['avalt'] ?></td>
				<td>Mejoras:</td><td><?= $datos[0]['avalm'] ?></td>
				<td>Total:</td><td><?= $datos[0]['avalt'] + $datos[0]['avalm'] ?></td>
				<td>Coef.Ajuste:</td><td><?= $datos[0]['coef'] ?></td>
				<td>Servicio:</td><td><?= $datos[0]['serv_nom'] ?></td>
			</tr>
			<tr>
				<td><b>Zonas:</b></td>
				<td>Tributaria:</td><td><?= $datos[0]['zonat_nom'] ?></td>
				<td>Valuatoria:</td><td><?= $datos[0]['zonav_nom'] ?></td>
				<td colspan='8'>Obras Privadas:<?= $datos[0]['zonaop_nom'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Domicilio</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='30px'>Parcelario:</td><td><?= $datos[0]['dompar_dir'] ?></td><td width='30px'>Barrio:</td><td><?= $datos[0]['barr_nom'] ?></td></tr>
			<tr><td width='30px'>Postal:</td><td colspan='2'><?= $datos[0]['dompos_dir'] ?></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Titulares</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='30px'>Tomo:</td><td><?= $datos[0]['tomo'] ?></td>
				<td width='30px'>Folio:</td><td><?= $datos[0]['folio'] ?></td>
				<td width='30px'>Finca:</td><td><?= $datos[0]['finca'] ?></td>
				<td width='30px'>Matrícula:</td><td><?= $datos[0]['matric'] ?></td>
				<td width='30px'>Año:</td><td><?= $datos[0]['fchmatric'] ?></td>
			</tr>
		</table>
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
	<div class='tt14' style='margin-top:10px;'> <b>Frentes</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Calle</b></td><td><b>Nombre</b></td><td><b>Medida</b></td></tr>
		<?php
			for ($i=0; $i<count($sub3); $i++){
				echo "<tr>";
				echo "<td>".$sub3[$i]['calle_id']."</td>";
				echo "<td>".$sub3[$i]['calle_nom']."</td>";
				echo "<td>".$sub3[$i]['medida']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Mejoras</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Pol</b></td><td><b>Nivel</b></td><td><b>Año</b></td><td><b>Destino</b></td><td><b>Obra</b></td><td><b>Estado</b></td><td><b>Sup.Cub.</b></td><td><b>Sup.Semi.</b></td><td><b>Cat.</b></td></tr>
		<?php
			for ($i=0; $i<count($sub2); $i++){
				echo "<tr>";
				echo "<td>".$sub2[$i]['pol']."</td>";
				echo "<td>".$sub2[$i]['nivel']."</td>";
				echo "<td>".$sub2[$i]['anio']."</td>";
				echo "<td>".$sub2[$i]['tdest_nom']."</td>";
				echo "<td>".$sub2[$i]['tobra_nom']."</td>";
				echo "<td>".$sub2[$i]['est_nom']."</td>";
				echo "<td>".$sub2[$i]['supcub']."</td>";
				echo "<td>".$sub2[$i]['supsemi']."</td>";
				echo "<td>".$sub2[$i]['cat']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>O.S.M.</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>SubCta</b></td><td><b>Cuenta</b></td><td><b>Inicio</b></td><td><b>Tipo Liquidación</b></td><td><b>Tipo Medidor</b></td><td><b>Nº Medidor</b></td></tr>
		<?php
			for ($i=0; $i<count($sub4); $i++){
				echo "<tr>";
				echo "<td>".$sub4[$i]['subcta']."</td>";
				echo "<td>".$sub4[$i]['ctaosm']."</td>";
				echo "<td>".$sub4[$i]['fchinicio']."</td>";
				echo "<td>".$sub4[$i]['tliq_nom']."</td>";
				echo "<td>".$sub4[$i]['tipomedidor_nom']."</td>";
				echo "<td>".$sub4[$i]['ummedidor']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Observaciones</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td><?= $datos[0]['obs'] ?></td></tr>
		</table>
	</div>
</div>