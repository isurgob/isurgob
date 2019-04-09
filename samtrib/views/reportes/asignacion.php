<?php 
use app\utils\db\utb;

if ($datos[0]['item_tipo'] == 2)
	$titulo = "Recargo de ". utb::getTObjNom($sub[0]['obj_id']);
else 	
	$titulo = "Exención de ". utb::getTObjNom($sub[0]['obj_id']);
?>
<div class='body' >
	<p class='tt'> <?= $titulo ?> </p>
	<div class='tt14' style='margin-top:10px;'> <b><u>Datos del Objeto</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<?php 
		if ($sub != null and count($sub)>0) {
			if ($tobj == 1){
		?>
			<table  style='margin-top:10px;' width='100%'><tr><td valign='top' width='45%'>
				<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000;'>
					<tr class='border_bottom'>
						<td><b>ParProv</b></td><td><b>Plano</b></td><td><b>Nomeclatura</b></td>
					</tr>
					<tr>
						<td><?= $sub[0]['parp'] ?></td><td><?= $sub[0]['plano'] ?></td><td><?= $sub[0]['nc_guiones'] ?></td>
					</tr>
				</table>
				<div class='cond' style='margin-top:5px'>
					<b>Domicilio Parcelario:</b>&nbsp; <?= $sub[0]['dompar_dir'] ?><br>
					<b>Comprador:</b>&nbsp;  <?= $sub[0]['comprador'] ?>
				</div>
			</td>
			<td valign='top'>
				<table class='cond' cellspacing='0' cellpadding='4'>
					<tr><td><b>Sup.Terreno:</b></td><td><?= $sub[0]['supt'] ?></td><td><b>Avalúo:</b></td><td><?= $sub[0]['avlat_mm'] ?></td></tr>
					<tr><td><b>Zona Tributo:</b></td><td><?= $sub[0]['zonat_nom'] ?></td><td><b>Zona Obras Priv.:</b></td><td><?= $sub[0]['zonaop_nom'] ?></td></tr>
					<tr><td><b>Servicio:</b></td><td colspan='2'><?= $sub[0]['serv_nom'] ?></td></tr>
				</table>
			</td>
			</tr></table>
		<?php 
			}
			if ($tobj == 2){
		?>
			<table  style='margin-top:10px;' width='100%'><tr><td valign='top' width='55%'>
				<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000;'>
					<tr class='border_bottom'>
						<td width='20%'><b>Legajo</b></td><td width='30%'><b>CUIT</b></td><td><b>Ingresos Brutos</b></td>
					</tr>
					<tr>
						<td><?= $sub[0]['legajo'] ?></td><td><?= $sub[0]['cuit'] ?></td><td><?= $sub[0]['ib'] ?></td>
					</tr>
				</table>
				<div class='cond' style='margin-top:5px'>
					<b>Domicilio Parcelario:</b>&nbsp; <?= $sub[0]['dompar_dir'] ?><br>
					<b>Rubro Principal:</b>&nbsp; <?= $sub[0]['rubro_nom'] ?>
				</div>
			</td>
			<td valign='top'>
				<table class='cond' cellspacing='0' cellpadding='4'>
					<tr><td><b>Tipo:</b></td><td><?= $sub[0]['tipo_nom'] ?></td></tr>
					<tr><td><b>Cond.ante IVA:</b></td><td><?= $sub[0]['iva_nom'] ?></td></tr>
				</table>
			</td>
			</tr></table>
		<?php 
			}
			if ($tobj == 3){
		?>
			<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000;margin-top:10px;'>
				<tr class='border_bottom'>
					<td><b>Documento</b></td><td><b>CUIT/CUIL</b></td><td><b>Cond. ante IVA</b></td>
				</tr>
				<tr>
					<td><?= $sub[0]['tdoc_nom'].': '.$sub[0]['ndoc'] ?></td><td><?= $sub[0]['cuit'] ?></td><td><?= $sub[0]['iva_nom'] ?></td>
				</tr>
			</table>
			<div class='cond' style='margin-top:5px'>
				<b>Nacionalidad:</b>&nbsp; <?= $sub[0]['nacionalidad_nom'] ?><br>
				<b>Dom. Legal:</b>&nbsp; <?= $sub[0]['domleg_dir'] ?>
			</div>
		<?php 
			}
			if ($tobj == 4){
		?>
			<table  style='margin-top:10px;'><tr><td valign='top'>
				<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000;'>
					<tr class='border_bottom'>
						<td><b>Tipo</b></td><td><b>Cuadro</b></td><td><b>Cuerpo</b></td>
						<td><b>Piso</b></td><td><b>Fila</b></td><td><b>Nume</b></td><td><b>Cod.Ant</b></td>
					</tr>
					<tr>
						<td><?= $sub[0]['tip_nom'] ?></td><td><?= $sub[0]['cuadro_id'] ?></td><td><?= $sub[0]['cuerpo_id'] ?></td>
						<td><?= $sub[0]['piso'] ?></td><td><?= $sub[0]['fila'] ?></td><td><?= $sub[0]['nume'] ?></td><td><?= $sub[0]['cod_ant'] ?></td>
					</tr>
				</table>
			</td>
			<td valign='top'>
				<table class='cond' cellspacing='0' cellpadding='4'>
					<tr>
						<td><b>Ingreso</b></td><td><?= $sub[0]['fchingreso'] ?></td><td><b>Categoría</b></td><td><?= $sub[0]['cat'] ?></td>
					</tr>
					<tr>	
						<td><b>Vencim.</b></td><td><?= $sub[0]['fchvenc'] ?></td><td><b>Delegación</b></td><td><?= $sub[0]['deleg_nom'] ?></td>
					</tr>
				</table>
			</td>
			</tr></table>
		<?php 
			}
			if ($tobj == 5){
		?>
			<table  style='margin-top:10px;'><tr><td valign='top'>
				<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000;'>
					<tr class='border_bottom'>
						<td><b>Dominio</b></td><td><b>PerIni</b></td><td><b>Año</b></td>
						<td><b>Uso</b></td><td><b>Peso</b></td><td><b>Cilindrada</b></td>
					</tr>
					<tr>
						<td><?= $sub[0]['dominio'] ?></td><td><?= $sub[0]['per_ini'] ?></td><td><?= $sub[0]['anio'] ?></td>
						<td><?= $sub[0]['uso_nom'] ?></td><td><?= $sub[0]['peso'] ?></td><td><?= $sub[0]['cilindrada'] ?></td>
					</tr>
				</table>
				<div class='cond' style='margin-top:5px'>
					<b>Marca:</b>&nbsp; <?= $sub[0]['marca_nom'] ?><br>
					<b>Modelo:</b>&nbsp; <?= ($sub[0]['tliq'] == 1 ? $sub[0]['modelo_aforo'] : $sub[0]['modelo_nom']) ?>
				</div>
			</td>
			<td valign='top'>
				<table class='cond' cellspacing='0' cellpadding='4'>
					<tr><td><b>Categoría:</b></td><td><?= $sub[0]['cat_nom'] ?></td></tr>
					<tr><td><b>Motor:</b></td><td><?= $sub[0]['nromotor'] ?></td></tr>
					<tr><td><b>Chasis:</b></td><td><?= $sub[0]['nrochasis'] ?></td></tr>
					<tr><td><b>Valor:</b></td><td><?= $sub[0]['aforo_valor'] ?></td></tr>
				</table>
			</td>
			</tr></table>
		<?php } } ?>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Información de la Asignación</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' cellspacing='0' cellpadding='4'>
			<tr><td>Tributo:</td><td><b><?= $datos[0]['trib_nom'] ?></b></td></tr>
			<tr><td>Item:</td><td><b><?= $datos[0]['item_nom'] ?></b></td></tr>
			<tr><td>Períodos:</td>
				<td>
					Desde:&nbsp;<b><?= substr($datos[0]['perdesde'],0,4).'/'.substr($datos[0]['perdesde'],4) ?></b> &nbsp; 
					Hasta:&nbsp;<b><?= substr($datos[0]['perhasta'],0,4).'/'.substr($datos[0]['perhasta'],4) ?></b>
				</td>
			</tr>
			<tr><td>Parámetros:</td>
				<td>
					<?= $datos[0]['parm1_nom'] ?>: &nbsp;<b><?= $datos[0]['parm1'] ?></b> &nbsp;
					<?= $datos[0]['parm2_nom'] ?>: &nbsp;<b><?= $datos[0]['parm2'] ?></b>
				</td>
			</tr>
			<tr><td>Expediente:</td><td><b><?= $datos[0]['expe'] ?></b></td></tr>
			<tr><td>Observación:</td><td><b><?= $datos[0]['obs'] ?></b></td></tr>
		</table>
	</div>
</div>