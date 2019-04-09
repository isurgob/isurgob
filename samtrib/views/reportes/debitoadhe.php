<?php
switch (substr($datos[0]['obj_id'],0,1)) {
	case 'I':
	case 'E':
		$obj_datos = 'Nomeclatura';
		break;
	case 'C':
		$obj_datos = 'Cuit';
		break;
	case 'P':
		$obj_datos = 'Documento';
		break;
	case 'R':
		$obj_datos = 'Dominio';
		break;
	default:
		$obj_datos = '';
}

$tpdato1nom = '';
$tpdato1 = '';
$tpdato2nom = '';
$tpdato2 = '';
$tpdato3nom = '';
$tpdato3 = '';
$tpdato4nom = '';
$tpdato4 = '';

if ($datos[0]['caja_tipo'] == 3){
	$tpdato1nom = 'Sucursal';
	$tpdato1 = $datos[0]['bco_suc_nom'];
	$tpdato2nom = 'Tipo de Cuenta';
	$tpdato2 = $datos[0]['bco_tcta_nom'];
	$tpdato3nom = 'Nro.Cuenta';
	$tpdato3 = $datos[0]['tpago_nro'];
}elseif ($datos[0]['caja_tipo'] == 4){
	$tpdato1nom = 'Nro.Tarjeta';
	$tpdato1 = $datos[0]['tpago_nro'];
}elseif ($datos[0]['caja_tipo'] == 5){
	$tpdato1nom = 'Empleado';
	$tpdato1 = $datos[0]['temple_nom'];
	$tpdato2nom = 'Area';
	$tpdato2 = $datos[0]['temple_area'];
	$tpdato3nom = 'Legajo';
	$tpdato3 = $datos[0]['tpago_nro'];
}

$tpdato4nom = 'CBU';
$tpdato4 = $datos[0]['cbu'];

?>
<div class='body' >
	<br><br><br>
	<p class='tt'> Débito Automático - Adhesión </p>
	<div class='tt14' style='margin-top:10px;'> <b>Adhesión</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='100px'><b>Nro. Adhesión:</b></td><td><?= $datos[0]['adh_id'] ?></td>
				<td width='100px'><b>Estado:</b></td><td><?= ($datos[0]['est'] == 'A' ? 'Activo' : 'Eliminado') ?></td>
			</tr>
			<tr>
				<td><b>Objeto:</b></td><td colspan='2'><?= $datos[0]['obj_id'].' - '.$datos[0]['obj_nom'] ?></td>
			</tr>
			<tr>
				<td><b><?= $obj_datos ?>:</b></td><td colspan='2'><?= $datos[0]['obj_dato'] ?></td>
			</tr>
			<tr>
				<td><b>Domicilio Parc.:</b></td><td colspan='2'><?= $datos[0]['dompar_dir'] ?></td>
			</tr>
			<tr>
				<td><b>Tributo:</b></td><td><?= $datos[0]['trib_nom'] ?></td>
				<td><b>Periodo desde:</b></td>
				<td>
					<?= substr($datos[0]['perdesde'],0,4).' - '.substr($datos[0]['perdesde'],-3) ?>
					<b>hasta</b> <?= substr($datos[0]['perhasta'],0,4).' - '.substr($datos[0]['perhasta'],-3) ?>
				</td>
			</tr>
			<tr>
				<td><b>Fecha Alta:</b></td><td><?= $datos[0]['fchalta'] ?></td>
				<td><b>Fecha Baja:</b></td><td><?= $datos[0]['fchbaja'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Responsable</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='30px'><b>Documento:</b></td><td><?= $datos[0]['resptdoc'].' - '.$datos[0]['respndoc'] ?></td>
				<td width='30px'><b>Sexo:</b></td><td><?= $datos[0]['respsexo'] ?></td>
			</tr>
			<tr>
				<td><b>Nombre:</b></td><td colspan='2'><?= $datos[0]['resp'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Forma Pago</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td colspan='4'><b><?= $datos[0]['caja_nom'] ?></b></td></tr>
			<tr>
				<td width='100px'><b><?= $tpdato1nom ?>:</b></td><td><?= $tpdato1 ?></td>
				<td width='100px'><b><?= $tpdato2nom ?>:</b></td><td><?= $tpdato2 ?></td>
			</tr>
			<tr>
				<td><b><?= $tpdato3nom ?>:</b></td><td><?= $tpdato3 ?></td>
				<td><b><?= $tpdato4nom ?>:</b></td><td><?= $tpdato4 ?></td>
			</tr>
		</table>
	</div>
	<p class='cond'><b><?= $datos[0]['texto_descr'] ?></b></p>
	<p class='cond'><b>
		El importe del período vigente es de <?= $montoactual ?> el cual podrá modificarse en función de las Ordenanzas Impositivas y/o
		cambios en las características del Objeto.
	</b></p>
	<div class='tt14' style='margin-top:20px;'> <b>Liquidaciones</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'>
				<td><b>Tributo</b></td><td><b>Año</b></td><td><b>Mes</b></td><td><b>Cta</b></td><td><b>Período</b></td>
				<td><b>Monto</b></td><td><b>Monto Deb.</b></td><td><b>Venc.</b></td><td><b>Fecha Deb.</b></td>
				<td><b>Estado</b></td><td><b>Rechazo</b></td>
			</tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				echo "<tr>";
				echo "<td>".$sub1[$i]['trib_nom']."</td>";
				echo "<td>".$sub1[$i]['anio']."</td>";
				echo "<td>".$sub1[$i]['mes']."</td>";
				echo "<td>".$sub1[$i]['subcta']."</td>";
				echo "<td>".$sub1[$i]['periodo']."</td>";
				echo "<td>".$sub1[$i]['monto']."</td>";
				echo "<td>".$sub1[$i]['montodeb']."</td>";
				echo "<td>".$sub1[$i]['fchvenc']."</td>";
				echo "<td>".$sub1[$i]['fchdeb']."</td>";
				echo "<td>".$sub1[$i]['est']."</td>";
				echo "<td>".$sub1[$i]['rechazo']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
</div>
