<?php
use yii\grid\GridView;
use app\utils\db\utb;
?>
<div class='body' >
	<p class='tt'> Reporte de Persona </p>
	<div class='tt14' style='margin-top:10px;'> <b>Identificación</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'>Objeto:</td><td><?= $datos[0]['obj_id'].' - '.$datos[0]['nombre'] ?></td>
				<td width='10px'>Estado:</td><td><b><?= $datos[0]['est_nom'] ?></b></td>
			</tr>
			<tr>
				<td width='10px'>Tipo:</td><td><?= $datos[0]['tipo_nom'] ?></td>
				<td width='10px'>Documento:</td><td><?= $datos[0]['tdoc_nom'].' - '.$datos[0]['ndoc']  ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Datos Personales</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='100px'>Fecha Nacimiento:</td><td><?= $datos[0]['fchnac'] ?></td>
				<td width='120px'>Sexo:</td><td><?= utb::getCampo("persona_tsexo","cod='" . $datos[0]['sexo'] . "'") ?></td>
				<td width='10px'>Teléfono:</td><td><?= $datos[0]['tel'] ?></td>
			</tr>
			<tr>
				<td>e-mail:</td><td><?= $datos[0]['mail'] ?></td>
				<td>Agente de Retención:</td><td><?= $datos[0]['ag_rete'] ?></td>
				<td>C.U.I.T.:</td><td><?= $datos[0]['cuit'] ?></td>
			</tr>
			<tr>
				<td>Estado Civil:</td><td><?= $datos[0]['estcivil_nom'] ?></td>
				<td>Situación IVA:</td><td><?= $datos[0]['iva_nom'] ?></td>
				<td>Nacionalidad:</td><td><?= $datos[0]['nacionalidad_nom'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Domicilio</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='30px'>Postal:</td><td><?= $datos[0]['dompos_dir'] ?></td></tr>
			<tr><td width='30px'>Legal:</td><td><?= $datos[0]['domleg_dir'] ?></td></tr>
			<tr><td width='30px'>Residencia:</td><td><?= $datos[0]['domres_dir'] ?></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Otros Datos</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='30px'>Clasificación:</td><td><?= $datos[0]['clasif_nom'] ?></td>
				<td width='30px'>Distribuidor:</td><td><?= $datos[0]['distrib_nom'] ?></td>
			</tr>
		</table>
	</div>
	<?php if ($vinculos != null) {?>
		<div class='tt14' style='margin-top:20px;'> <b>Vínculos</b> </div>
		<div class='divredon' style='padding:5px'>
			<table class='cond' width='100%' cellspacing='4'>
				<tr class='border_bottom'>
					<td><b>Tipo Objeto</b></td>
					<td><b>Objeto</b></td>
					<td><b>Nombre</b></td>
					<td><b>Dato</b></td>
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
						echo "<td>" . $d['obj_dato'] . "</td>";
						echo "<td>" . $d['tvinc_nom'] . "</td>";
						echo "<td>" . $d['porc'] . "</td>";
						echo "<td>" . $d['est'] . "</td>";
						echo "</tr>";
					}
				?>
			</table>
		</div>
	<?php } ?>	
</div>