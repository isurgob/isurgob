<?php 
use app\utils\db\utb;
?>
<div class='body' >
	<p class='tt'> <?= $texto . ' - ' . $datos[0]['tobj_nom'] ?></p>
	<?php if ($trib_id != 0){ ?>
		<p class='tt14'><u><b>Libre de Deuda sólo para <?= utb::getCampo("trib","trib_id=".$trib_id) ?> </b></u></p>
	<?php } ?>	
	<div class='divredon' style='margin-top:10px; padding:5px'>
		<table class='cond' width='100%' cellspacing='4' style='border-bottom:2px solid #000'>
			<tr>
				<td width='10px'>Objeto:</td><td><b><?= $datos[0]['obj_id'].' - '.$datos[0]['obj_nom'] ?></b></td>
				<td width='150px'>Número de Certificado:</td><td><b><?= $datos[0]['lib_id'] ?></b></td>
			</tr>
			<tr>
				<td>Titular:</td><td><?= $datos[0]['num'].' - '.$datos[0]['num_nom'] ?></td>
				<td>Fecha Emisión:</td><td><b><?= $datos[0]['fchemi'] ?></b></td>
			</tr>
			<tr>
				<td>Domicilio:</td><td><?= $datos[0]['dompos_dir'] ?></td>
				<?php if ($datos[0]['est_obj'] == 'B') { ?>
					<td>Fecha Baja:</td><td><b><?= $datos[0]['fchbaja'] ?></b></td>
				<?php } else {?>
					<td></td><td></td>
				<?php } ?>
			</tr>
		</table>
		<?php 
		if ($sub != null and count($sub)>0) {
			if ($datos[0]['tobj'] == 1){
		?>
			<table  style='margin-top:10px;' width='100%'><tr><td valign='top' width='45%'>
				<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000;'>
					<tr class='border_bottom'>
						<td><b>ParProv</b></td><td><b>Plano</b></td><td><b>Nomeclatura</b></td><td><b>UF</b></td>
					</tr>
					<tr>
						<td><?= $sub[0]['parp'] ?></td><td><?= $sub[0]['plano'] ?></td><td><?= $sub[0]['nc_guiones'] ?></td><td><?= $sub[0]['uf'] ?></td>
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
			if ($datos[0]['tobj'] == 2){
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
			if ($datos[0]['tobj'] == 3){
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
			if ($datos[0]['tobj'] == 4){
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
			if ($datos[0]['tobj'] == 5){
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
					<b>Modelo:</b>&nbsp; <?= ($sub[0]['tliq'] == 1 ? $sub[0]['aforo_modelo'] : $sub[0]['modelo_nom']) ?>
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
	
	<?php if ( $sub2 !== null ){ ?>
		<div class='cond' style='margin-top:20px'>
			<p class='tt14'><u><b> Ultimos Periodos Pagos </b></u></p>
			<table class='cond' cellspacing='0' cellpadding='4' width='100%'>
				<tr class='border_bottom'>
					<td><b>Tributo</b></td><td><b>Periodo</b></td><td><b>Descripción Pago</b></td>
				</tr>
				<?php 
					foreach ( $sub2 as $p){
						echo "<tr>";
						echo "<td valign='top'>" . $p['trib_nom'] . "</td>";
						echo "<td valign='top' align='center'>" . $p['anio'] . "/" . $p['cuota'] . "</td>";
						echo "<td valign='top'>" . $p['pago_desc'] . "</td>";
						echo "</tr>";
					}
				?>
			</table>	
		</div>
	<?php } ?>
	
	<div class='cond' style='margin-top:20px'>
		<p class='tt14'><u><b>Detalle de la Deuda <?= ($datos[0]['tobj'] == 3 ? 'de la ' : 'del ').$datos[0]['tobj_nom'] ?> </b></u></p>
		<?php if ($datos[0]['sindeudas'] == 0) { ?>
			<table class='cond' cellspacing='0' cellpadding='4' width='80%'>
				<tr class='border_bottom'>
					<td><b>Tributo</b></td><td><b>Objeto</b></td><td><b>Períodos</b></td><td align='right'><b>Total</b></td>
				</tr>
				<?php 
					$d_deuda = explode(str_repeat('_', 100),$datos[0]['d_deuda']);
					for ($i=0; $i<count($d_deuda)-1; $i++){
						$str = substr($d_deuda[$i],16,-14);
						if (substr(trim($str),0,1) != '') $ar=explode(substr(trim($str),0,1),$str);
						for ($j=1; $j<count($ar); $j++){
							if ($j == 1){
								echo "<tr>";
								echo "<td valign='top'>".substr($d_deuda[$i],0,16)."</td>";
							}else {
								echo "<tr><td></td>";
							}
							
							echo '<td>'.substr(trim($str),0,1).substr($ar[$j],0,11).'</td>';
							echo '<td>'.substr($ar[$j],11).'</td>';
							
							if ($j == 1){
								echo "<td valign='top' align='right'>".substr($d_deuda[$i],-14)."</td>";
								echo "</tr>";
							}else {
								echo "<td></td></tr>";
							}	
						}
					}
				?>
				<tr class='border_top'><td colspan='3' align='right'><b>Total:</b></td><td align='right'><b><?= $datos[0]['d_total'] ?></b></td></tr>
			</table>
		<?php }else {
			echo "No se registran deudas en concepto de tasas y/o contribuciones y/o derechos sobre ".($datos[0]['tobj'] == 3 ? 'de la ' : 'del ').$datos[0]['tobj_nom'];
		} ?>
	</div>
	<div class='cond' style='margin-top:10px'>
		<?php if ($datos[0]['j_deuda'] != '') {?>
			<p class='tt14'><u><b>Deuda en Juicio</b></u></p>
			<table class='cond' cellspacing='0' cellpadding='4' width='80%'>
				<tr class='border_bottom'>
					<td><b>Tributo</b></td><td><b>Objeto</b></td><td><b>Períodos</b></td><td align='right'><b>Total</b></td>
				</tr>
				<?php 
					$j_deuda = explode(str_repeat('_', 100),$datos[0]['j_deuda']);
					for ($i=0; $i<count($j_deuda)-1; $i++){
						$str = substr($j_deuda[$i],16,-14);
						if (substr(trim($str),0,1) != '') $ar=explode(substr(trim($str),0,1),$str);
						for ($j=1; $j<count($ar); $j++){
							if ($j == 1){
								echo "<tr>";
								echo "<td valign='top'>".substr($j_deuda[$i],0,16)."</td>";
							}else {
								echo "<tr><td></td>";
							}
							
							echo '<td>'.substr(trim($str),0,1).substr($ar[$j],0,11).'</td>';
							echo '<td>'.substr($ar[$j],11).'</td>';
							
							if ($j == 1){
								echo "<td valign='top' align='right'>".substr($j_deuda[$i],-14)."</td>";
								echo "</tr>";
							}else {
								echo "<td></td></tr>";
							}	
						}
					}
				?>
				<tr class='border_top'><td colspan='3' align='right'><b>Total:</b></td><td align='right'><b><?= $datos[0]['j_total'] ?></b></td></tr>
			</table>
		<?php } ?>
	</div>
	<div class='cond' style='margin-top:10px'>
		<?php if ($datos[0]['x_deuda'] != '') {?>
			<p class='tt14'><u><b>Períodos sin Presentación de DDJJ</b></u></p>
			<table class='cond' cellspacing='0' cellpadding='4' width='80%'>
				<tr class='border_bottom'>
					<td><b>Tributo</b></td><td><b>Objeto</b></td><td><b>Períodos</b></td>
				</tr>
				<?php 
					$x_deuda = explode(str_repeat('_', 100),$datos[0]['x_deuda']);
					
					for ($i=0; $i<count($x_deuda)-1; $i++){
						if ($i == count($x_deuda)-1) 
							$str = substr($x_deuda[$i],16);
						else 	
							$str = substr($x_deuda[$i],16,-13);
						
						if (substr(trim($str),0,1) != '') $ar=explode(substr(trim($str),0,1),$str);
						
						for ($j=1; $j<count($ar); $j++){
							if ($j == 1){
								echo "<tr>";
								echo "<td valign='top'>".substr($x_deuda[$i],0,16)."</td>";
							}else {
								echo "<tr><td></td>";
							}
							
							echo '<td>'.substr(trim($str),0,1).substr($ar[$j],0,11).'</td>';
							//echo '<td>'.substr($ar[$j],11).'</td>';
							
							if ($j == 1){
								echo "<td valign='top' >".substr($x_deuda[$i],-13)."</td>";
								//echo "<td valign='top' align='right'></td>";
								echo "</tr>";
							}else {
								echo "<td></td></tr>";
							}	
						}
					}
				?>
			</table>
		<?php } ?>
	</div>
	<div class='cond' style='margin-top:10px'>
		<?php if ($datos[0]['deudaplanes'] != '') {?>
			<p class='tt14'><u><b>Convenios de Pago Vigentes:</b></u></p>
			<table class='cond' cellspacing='0' cellpadding='4' width='100%' style='font-size:10px'>
				<tr class='border_bottom'>
					<td><b>Plan</b></td><td><b>Objeto</b></td><td><b>FchAlta</b></td>
					<td><b>F.Pago</b></td><td align='right'><b>Deuda</b></td><td align='right'><b>Pagado</b></td>
					<td align='center'><b>Cuo</b></td><td align='center'><b>CVenc</b></td><td align='center'><b>CNoVenc</b></td>
					<td align='right'><b>Saldo</b></td><td align='right'><b>Acces</b></td><td align='right'><b>Total</b></td>
				</tr>
				<?php 
					$deudaplanes = explode(str_repeat('_', 100),$datos[0]['deudaplanes']);
					$cont = 1;
					for ($i=0; $i<=floor(strlen($datos[0]['deudaplanes']) / 110); $i++){
						$strplan =  explode(" ", $datos[0]['deudaplanes']);
						for ($i=0; $i<count($strplan); $i++){
							if ($strplan[$i] != "") {
								if ($i == 0) echo "<tr>";
								$aling = "align='left'";
								if ($cont == 5 or $cont == 6 or $cont == 10 or $cont == 11 or $cont == 12) $aling = "align='right'";
								if ($cont == 7 or $cont == 8 or $cont == 9) $aling = "align='center'";
								
								if ($cont == 12) {
									echo "<td ". $aling ." >" . substr($strplan[$i],0,strpos($strplan[$i],".")+3) . "</td>";
									echo "</tr><tr>";
									
									if (trim(substr($strplan[$i],strpos($strplan[$i],".")+3,7)) == 'Mejora')
										$texto = 'Mejora ' . substr($strplan[$i],15);
									else 
										$texto = substr($strplan[$i],strpos($strplan[$i],".")+3);
									
									echo "<td align='left' >" . $texto . "</td>";
									
									$cont = 1;
								}else {	
									if (trim(substr($strplan[$i],0,6)) == 'Mejora')
										echo "<td ". $aling ." >Mejora " . substr($strplan[$i],6) . "</td>";
									else 	
										echo "<td ". $aling ." >" . $strplan[$i] . "</td>";
								}	
								
								$cont += 1;
							}	
						}
					}
				?>
				<tr class='border_top'><td colspan='11' align='right'><b>Total Deuda Convenio:</b></td><td align='right'><b><?= $datos[0]['totalplanes'] ?></b></td></tr>
			</table>
		<?php } ?>
	</div>
	<div class='cond' style='margin-top:10px'>
		<?php if ($datos[0]['deudamejoras'] != '') {?>
			<p class='tt14'><u><b>Contribución por Mejoras</b></u></p>
			<table class='cond' cellspacing='0' cellpadding='4' width='100%'>
				<tr class='border_bottom'>
					<td><b>Nro</b></td><td><b>Alta</b></td><td><b>Tipo Obra</b></td>
					<td><b>Nombre Obra</b></td><td><b>Monto</b></td><td><b>Saldo</b></td>
					<td><b>Accesor</b></td><td align='right'><b>Total</b></td>
				</tr>
				<?php 
					$deudamejoras = explode(str_repeat('_', 100),$datos[0]['deudamejoras']);
					for ($i=0; $i<count($deudamejoras); $i++){
						echo "<tr>";
						echo "<td>".substr($deudamejoras[$i],0,8)."</td>";
						echo "<td>".substr($deudamejoras[$i],8,11)."</td>";
						echo "<td>".substr($deudamejoras[$i],19,15)."</td>";
						echo "<td>".substr($deudamejoras[$i],34,30)."</td>";
						echo "<td>".substr($deudamejoras[$i],64,11)."</td>";
						echo "<td>".substr($deudamejoras[$i],75,11)."</td>";
						echo "<td>".substr($deudamejoras[$i],85,11)."</td>";
						echo "<td>".substr($deudamejoras[$i],95)."</td>";
						echo "</tr>";
					}
				?>
				<tr class='border_top'><td colspan='7' align='right'><b>Total Deuda Mejoras:</b></td><td align='right'><b><?= $datos[0]['totalmejoras'] ?></b></td></tr>
			</table>
		<?php } ?>
	</div>
	<div class='cond' style='margin-top:10px'>
		<?php if ($datos[0]['deudaotras'] != '') {?>
			<p class='tt14'><u><b>Deuda por Vencer</b></u></p>
			<table class='cond' cellspacing='0' cellpadding='4' width='100%'>
				<tr class='border_bottom'>
					<td><b>Tributo</b></td><td><b>Objeto</b></td><td><b>FchEmisión</b></td>
					<td><b>FchVenc</b></td><td align='right'><b>Monto</b></td>
				</tr>
				<?php 
					$deudaotras = explode(str_repeat('_', 100),$datos[0]['deudaotras']);
					for ($i=0; $i<count($deudaotras); $i++){
						echo "<tr>";
						echo "<td>".substr($deudaotras[$i],0,25)."</td>";
						echo "<td>".substr($deudaotras[$i],25,15)."</td>";
						echo "<td>".substr($deudaotras[$i],40,15)."</td>";
						echo "<td>".substr($deudaotras[$i],55,15)."</td>";
						echo "<td>".substr($deudaotras[$i],70,15)."</td>";
						echo "</tr>";
					}
				?>
				<tr class='border_top'><td colspan='4' align='right'><b>Total Otras Deudas:</b></td><td align='right'><b><?= $datos[0]['totalotras'] ?></b></td></tr>
			</table>
		<?php } ?>
	</div>
	<div class='cond' style='margin-top:10px'>
		<?php if ($datos[0]['proxvenc'] != '' and $proxvenc != 'N') {?>
			<p class='tt14'><u><b>Próximos Vencimientos</b></u></p>
			<table class='cond' cellspacing='0' cellpadding='4' width='90%'>
				<tr class='border_bottom'>
					<td><b>Tributo</b></td><td><b>Obj/Conv</b></td><td><b>Período</b></td>
					<td><b>FchVenc</b></td>
				</tr>
				<?php 
					$proxvenc = explode(str_repeat('_', 100),$datos[0]['proxvenc']);
					for ($i=0; $i<count($proxvenc); $i++){
						echo "<tr>";
						echo "<td>".substr($proxvenc[$i],0,23)."</td>";
						echo "<td>".substr($proxvenc[$i],23,10)."</td>";
						echo "<td>".substr($proxvenc[$i],33,9)."</td>";
						echo "<td>".substr($proxvenc[$i],42,10)."</td>";
						echo "</tr>";
					}
				?>
			</table>
		<?php } ?>
	</div>
	<p class='cond' style='margin-top:20px'>
		<font class='tt14'><u><b><?= $datos[0]['titulo'] ?></b></u></font><br>
		<?= $datos[0]['detalle'] ?>
	</p>
	<?php if ($datos[0]['est_obj'] == 'B') { ?>
		<p class='tt14' style='margin-top:20px'><b>El objeto fue dado de baja el <?= $datos[0]['fchbaja'] ?></b></p>
	<?php } ?>
	<p class='tt14' style='margin-top:20px'><b><?= $datos[0]['mensaje'] ?></b></p>
	<table width='100%' class='cond' cellspacing='0' cellpadding='4'>
		<tr><td width='90px'><?= ($datos[0]['obs'] == '' ? '' : 'Observación:') ?> </td><td><?= $datos[0]['obs'] ?></td><td></td></tr>
		<tr><td><?= ($datos[0]['escribano'] == '' ? '' : 'Escribano:') ?> </td><td><?= $datos[0]['escribano'] ?></td><td></td></tr>
		<tr><td></td><td></td><td width='250px' align='center' <?= ($firma == '' ? '' : "class='border_top'") ?> ><font class='desc8'><?= $firma ?></font></td></tr>
	</table>
</div>