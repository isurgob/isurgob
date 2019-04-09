<?php use app\utils\db\utb; ?>
<div class='body' >
	<p class='tt'> Reporte de Tributo </p>
	<div class='divredon' style='margin-top:10px; padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'>Código:</td><td colspan='3'><b><?= $datos[0]['trib_id'] ?></b></td>
				<td width='10px'>Estado:</td><td><b><?= ($datos[0]['est'] == 'A' ? 'Activo' : 'Baja') ?></b></td>
			</tr>
			<tr>
				<td>Nombre:</td><td><b><?= $datos[0]['nombre'] ?></b></td>
				<td width='10px'>Reducido:</td><td><b><?= $datos[0]['nombre_redu'] ?></b></td>
				<td>H.Bank:</td><td><b><?= $datos[0]['nombre_reduhbank'] ?></b></td>
			</tr>
		</table>
		<hr style="color: #000; margin:1px" />
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='80px'>Tipo de Trib.:</td><td><b><?= $datos[0]['tipo_nom'] ?></b></td>
				<td width='60px'>Tipo Objeto:</td><td><b><?= $datos[0]['tobj_nom'] ?></b></td>
				<td width='80px'>Forma Pago:</td><td><b><?= $datos[0]['tpago_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Texto:</td><td><b><?= $datos[0]['texto_nom'] ?></b></td>
				<td>DJ.Trib.Princ.:</td><td><b><?= $datos[0]['dj_tribprinc'] ?></b></td>
				<td>Valor UCM:</td><td><b><?= $datos[0]['ucm_nom'] ?></b></td>
			</tr>
		</table>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='120px'>Porc. quita facilidad:</td><td><b><?= $datos[0]['quitafaci'] ?></b></td></tr>
		</table>
		<hr style="color: #000; margin:1px" />
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='90px'>Calcula Interés:</td><td><b><?= $datos[0]['calc_interes_nom'] ?></b></td>
				<td width='140px'>Genera Estado Cta.Cte.:</td><td><b><?= $datos[0]['genestcta_nom'] ?></b></td>
				<td width='120px'>Permite compensar:</td><td><b><?= $datos[0]['compensa_nom'] ?></b></td>
				<td width='110px'>Usar domic.general:</td><td><b><?= ($datos[0]['bol_domimuni']==1 ? 'SI' : 'NO') ?></b></td>
			</tr>
			<tr>
				<td>Usa subcuenta:</td><td><b><?= ($datos[0]['uso_subcta'] == 1 ? 'SI' : 'NO') ?></b></td>
				<td>Usa módulos municipales:</td><td><b><?= ($datos[0]['uso_mm'] == 1 ? 'SI' : 'NO') ?></b></td>
				<td>Inscripción requerida:</td><td><b><?= ($datos[0]['inscrip_req'] == 1 ? 'SI' : 'NO') ?></b></td>
				<td>Inscripción auto.:</td><td><b><?= ($datos[0]['inscrip_auto'] == 1 ? 'SI' : 'NO') ?></b></td>
			</tr>
		</table>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='140px'>Inscripción incompatible:</td><td><b><?= utb::getCampo('item','item_id='.$datos[0]['inscrip_incomp']) ?></b></td>
			</tr>
		</table>
		<hr style="color: #000; margin:1px" />
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='100px'>Domicilio:</td><td><b><?= $datos[0]['bol_domi'] ?></b></td>
				<td width='100px'>Tel.:</td><td><b><?= $datos[0]['bol_tel'] ?></b></td>
				<td width='100px'>Mail:</td><td><b><?= $datos[0]['bol_mail'] ?></b></td>
			</tr>
			<tr>
				<td>Tasa Recargo:</td><td><b><?= $datos[0]['calc_rec_tasa'] ?></b></td>
				<td colspan='4'>Recargo 2º Vencimiento:<b>&nbsp; <?= $datos[0]['rec_venc2'] ?></b></td>
			</tr>
			<tr><td>Cuenta Recargo:</td><td colspan='5'><b><?= $datos[0]['cta_nom_rec'] ?></b></td></tr>
			<tr><td>Cuenta Redondeo:</td><td colspan='5'><b><?= $datos[0]['cta_nom_redon'] ?></b></td></tr>
			<tr><td>Años de prescrip.:</td><td colspan='5'><b><?= $datos[0]['prescrip'] ?></b></td></tr>
			</tr>
		</table>
		<hr style="color: #000; margin:1px" />
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='60px'>Oficina:</td><td><b><?= $datos[0]['oficina_nom'] ?></b></td></tr>
			<tr><td colspan='2'><b><?= $datos[0]['tipo_descripcion'] ?></b></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Items</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>Código</b></td><td><b>Nombre</b></td>
			</tr>
			<?php 
			for ($i=0; $i<count($sub1);$i++){
				echo '<tr>';
				echo '<td>'.$sub1[$i]['item_id'].'</td>';
				echo '<td>'.$sub1[$i]['nombre'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Vencimientos</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>Cuota</b></td><td><b>Fecha 1</b></td><td><b>Fecha 2</b></td>
			</tr>
			<?php 
			for ($i=0; $i<count($sub2);$i++){
				echo '<tr>';
				echo '<td>'.$sub2[$i]['cuota'].'</td>';
				echo '<td>'.$sub2[$i]['fchvenc1'].'</td>';
				echo '<td>'.$sub2[$i]['fchvenc2'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
</div>