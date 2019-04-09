<?php
use yii\grid\GridView;
use app\utils\db\utb;

$usr_baja = utb::getCampo("objeto","obj_id='" . $datos[0]['obj_id'] . "'","usrbaja");
$usr_baja_nom = utb::getCampo("sam.sis_usuario","usr_id=" . $usr_baja);
?>
<div class='body' >
	<p class='tt'> Reporte de Persona </p>
	<div class='tt14' style='margin-top:10px;'> <b>Datos Persona</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='70px'>Objeto:</td><td colspan='3'><?= $datos[0]['obj_id'].' - '.$datos[0]['nombre'] ?></td>
				<td width='10px'>Estado:</td><td><b><?= $datos[0]['est_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Nº Inscrip.:</td><td><?= $datos[0]['inscrip'] ?></td>
				<td width='70px'>Tipo:</td><td><?= $datos[0]['tipo_nom'] ?></td>
				<td>Sexo:</td><td><?= utb::getCampo("persona_tsexo","cod='" . $datos[0]['sexo'] . "'") ?></td>
			</tr>
			<tr>
				<td>Tipo Doc.:</td><td><?= $datos[0]['tdoc_nom'] ?></td>
				<td>Nº Doc.:</td><td><?= $datos[0]['ndoc'] ?></td>
				<td>Nacimiento:</td><td><?= $datos[0]['fchnac']  ?></td>
			</tr>
			<tr>
				<td>Nacionalidad:</td><td><?= $datos[0]['nacionalidad_nom'] ?></td>
				<td>Estado Civil:</td><td><?= $datos[0]['estcivil_nom'] ?></td>
				<td>Clasificación:</td><td><?= $datos[0]['clasif_nom']  ?></td>
			</tr>
			<tr>
				<td>Situac. IVA:</td><td><?= $datos[0]['iva_nom'] ?></td>
				<td>CUIT/CUIL:</td><td><?= $datos[0]['cuit'] ?></td>
				<td>Ag. Rete:</td><td><?= $datos[0]['ag_rete']  ?></td>
			</tr>
			<tr>
				<td>Tipo Liq.:</td><td><?= $datos[0]['tipoliq_nom'] ?></td>
				<td>Teléfono:</td><td><?= $datos[0]['tel'] ?></td>
				<td>Celular:</td><td><?= $datos[0]['cel']  ?></td>
			</tr>
			<tr>
				<td>E-mail:</td><td colspan='5'><?= $datos[0]['mail'] ?></td>
			</tr>
			<tr>
				<td>Distribuidor:</td><td colspan='5'><?= $datos[0]['distrib_nom'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Datos Ingresos Brutos</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='70px'>Nº IB:</td><td><?= $datos[0]['ib'] ?></td>
				<td width='120px'>Org.Juri.:</td><td><?= $datos[0]['orgjuri_nom'] ?></td>
				<td width='60px'>Tipo Liq.:</td><td><?= $datos[0]['tipoliq_nom'] ?></td>
			</tr>
			<tr>
				<td>Contador:</td><td><?= utb::getCampo( 'objeto', "obj_id IN ( SELECT obj_id FROM sam.usuarioweb WHERE usr_id = ".$datos[0]['contador'].")", 'nombre') ?></td>
				<td>Agente de Retención:</td><td colspan='3'><?= $datos[0]['ag_rete'] ?></td>
			</tr>
			<tr>
				<td>Fecha Alta:</td><td><?= $datos[0]['fchalta_ib'] ?></td>
				<td>Fecha Baja:</td><td><?= $datos[0]['fchbaja_ib'] ?></td>
				<td>Estado:</td><td><?= $datos[0]['est_ib_nom'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Fechas</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='30px'>Alta:</td><td><?= $datos[0]['usralta_nom'] . " - " . $datos[0]['fchalta'] ?></td>
				<td width='30px'>Baja:</td><td><?= $usralta_nom == "" ? "" : $usralta_nom . " - " . $datos[0]['fchbaja'] ?></td>
				<td width='30px'>Modificacion:</td><td><?= $datos[0]['usrmod_nom'] . " - " . $datos[0]['fchmod'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Domicilio</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='30px'>Postal:</td><td><?= $datos[0]['dompos_dir'] ?></td></tr>
			<tr><td width='30px'>Legal:</td><td><?= $datos[0]['domleg_dir'] ?></td></tr>
			<tr><td width='30px'>Residencia:</td><td><?= $datos[0]['domres_dir'] ?></td></tr>
		</table>
	</div>
	<?php if ($model->rubros != null) { ?>
		<div class='tt14' style='margin-top:10px;'> <b>Rubros</b> </div>
		<div class='divredon' style='padding:5px'>
			<table class='cond' width='100%' cellspacing='4'>
				<tr class='border_bottom'>
					<td><b>Nomen</b></td>
					<td><b>Rubro</b></td>
					<td><b>Nombre</b></td>
					<td><b>Desde</b></td>
					<td><b>Hasta</b></td>
					<td><b>Cant</b></td>
					<td><b>Estado</b></td>
				</tr>
				<?php
					foreach ($model->rubros as $r){
						if ( $r['est'] == 'A' ){
							echo "<tr>";
							echo "<td>" . $r['nomen_nom'] . "</td>";
							echo "<td>" . $r['rubro_id'] . "</td>";
							echo "<td>" . $r['rubro_nom'] . "</td>";
							echo "<td>" . $r['perdesde'] . "</td>";
							echo "<td>" . $r['perhasta'] . "</td>";
							echo "<td>" . $r['cant'] . "</td>";
							echo "<td>" . $r['est_nom'] . "</td>";
							echo "</tr>";
						}
					}
				?>
			</table>
		</div>
	<?php } ?>

	<?php
		$asignaciones = $modelobjeto->CargarAsignaciones($modelobjeto->obj_id)->getModels();

		if ($asignaciones != null) {
	?>
		<div class='tt14' style='margin-top:10px;'> <b>Asignaciones de Items</b> </div>
		<div class='divredon' style='padding:5px'>
			<table class='cond' width='100%' cellspacing='4'>
				<tr class='border_bottom'>
					<td><b>Tributo</b></td>
					<td><b>Tipo</b></td>
					<td><b>Nombre</b></td>
					<td><b>Desde</b></td>
					<td><b>Hasta</b></td>
					<td><b>Param1</b></td>
					<td><b>Param2</b></td>
					<td><b>Modif</b></td>
				</tr>
				<?php
					foreach ($asignaciones as $a){
						echo "<tr>";
						echo "<td>" . $a['trib_nom'] . "</td>";
						echo "<td>" . $a['trib_tipo_nom'] . "</td>";
						echo "<td>" . $a['item_nom'] . "</td>";
						echo "<td>" . $a['perdesde'] . "</td>";
						echo "<td>" . $a['perhasta'] . "</td>";
						echo "<td>" . $a['param1'] . "</td>";
						echo "<td>" . $a['param2'] . "</td>";
						echo "<td>" . $a['modif'] . "</td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
	<?php } ?>

	<?php
		$acciones = $modelobjeto->CargarAcciones($modelobjeto->obj_id)->getModels();

		if ($acciones != null) {
	?>
		<div class='tt14' style='margin-top:10px;'> <b>Acciones</b> </div>
		<div class='divredon' style='padding:5px'>
			<table class='cond' width='100%' cellspacing='4'>
				<tr class='border_bottom'>
					<td><b>Tipo</b></td>
					<td><b>Fecha</b></td>
					<td><b>Anterior</b></td>
					<td><b>Inscripción</b></td>
					<td><b>Expediente</b></td>
					<td><b>Usuario</b></td>
				</tr>
				<?php
					foreach ($acciones as $a){
						echo "<tr>";
						echo "<td>" . $a['taccion_nom'] . "</td>";
						echo "<td>" . $a['fecha_format'] . "</td>";
						echo "<td>" . $a['dato_ant'] . "</td>";
						echo "<td>" . $a['dato_ins'] . "</td>";
						echo "<td>" . $a['expe'] . "</td>";
						echo "<td>" . $a['modif'] . "</td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
	<?php } ?>

	<?php
		$inscrip = $modelobjeto->getDPTributos()->getModels();

		if ($inscrip != null) {
	?>
		<div class='tt14' style='margin-top:10px;'> <b>Inscripciones</b> </div>
		<div class='divredon' style='padding:5px'>
			<table class='cond' width='100%' cellspacing='4'>
				<tr class='border_bottom'>
					<td><b>Tributo</b></td>
					<td><b>Desde</b></td>
					<td><b>Hasta</b></td>
					<td><b>Categoría</b></td>
					<td><b>Alta</b></td>
					<td><b>Base</b></td>
					<td><b>Cant.</b></td>
					<td><b>Sup.</b></td>
				</tr>
				<?php
					foreach ($inscrip as $i){
						echo "<tr>";
						echo "<td>" . $i['trib_nom_redu'] . "</td>";
						echo "<td>" . $i['per_desde'] . "</td>";
						echo "<td>" . $i['per_hasta'] . "</td>";
						echo "<td>" . $i['cat_nom'] . "</td>";
						echo "<td>" . $i['fchalta'] . "</td>";
						echo "<td>" . $i['base'] . "</td>";
						echo "<td>" . $i['cant'] . "</td>";
						echo "<td>" . $i['sup'] . "</td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
	<?php } ?>

	<?php if ($vinculos != null) {?>
		<div class='tt14' style='margin-top:10px;'> <b>Vínculos</b> </div>
		<div class='divredon' style='padding:5px'>
			<table class='cond' width='100%' cellspacing='4'>
				<tr class='border_bottom'>
					<td><b>Tipo Objeto</b></td>
					<td><b>Objeto</b></td>
					<td><b>Nombre</b></td>
					<td><b>Vínculo</b></td>
					<td><b>Porc</b></td>
					<td><b>Estado</b></td>
				</tr>
				<?php
					foreach ($vinculos as $d){
						echo "<tr>";
						echo "<td>" . $d['tobj_nom'] . "</td>";
						echo "<td>" . $d['obj_id'] . "</td>";
						echo "<td>" . $d['nombre'] . "</td>";
						echo "<td>" . $d['tvinc_nom'] . "</td>";
						echo "<td>" . $d['porc'] . "</td>";
						echo "<td>" . $d['est'] . "</td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
	<?php } ?>

	<div class='tt14' style='margin-top:10px;'> <b>Observación</b> </div>
	<div class='divredon' style='padding:5px'> <?= $datos[0]['obs'] ?> </div>

</div>
