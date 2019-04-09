<!-- Encabezado -->
<div style='overflow: hidden;margin-bottom:5px'>
	<div style='float:left;' width='25%'>
		<img src='<?= Yii::$app->request->baseUrl.'/images/logo_muni_grande.jpg'?>' border='0' />
	</div>
	<div style='float:left;margin-left:10px;margin-top:-20px' width='30%' class='desc'>
		<font class='tt14'><b><?= Yii::$app->params['MUNI_NAME'] ?></b></font><br><br>
		<b><?= ($datos[0]['trib_id'] != 9 ? 'DIRECCION DE INGRESOS PUBLICOS<br>' : '') ?></b>
		<?= Yii::$app->params['MUNI_DOMI'] ?><br>
		<?= 'Tel.: '.Yii::$app->params['MUNI_TEL'] ?><br>
		<?= Yii::$app->params['MUNI_MAIL'] ?><br>
		<table border='0' class='cond' style='margin-top:10px'>
			<tr><td></td><td align='center'><?=$datos[0]['codbarra']?></td></tr>
			<tr><td>Cod.Barra</td><td align='center'><barcode code="<?=$datos[0]['codbarra']?>" type="C128C" /></td></tr>
		</table>
	</div>
	<div align='right' class='desc'>R.N.P.S.P.:&nbsp; <?=$texto4?></div>
	<div class='divredon desc' width='40%' style='float:right;margin-left:10px'>
		<table border='0' width='100%' style='padding:0px 5px' class='desc'>
			<tr><td width='30px'>Objeto:&nbsp;</td><td><b><?=$datos[0]['obj_id'].' '.$datos[0]['obj_nom'] ?></b></td></tr>
			<tr><td>Domicilio:&nbsp;</td><td><b><?=$datos[0]['dompos_dir']?></b></td></tr>
			<tr><td></td><td><b><?=$datos[0]['dompos_det']?></b></td></tr>
			<tr><td>Localidad:&nbsp;</td><td><b><?=$datos[0]['codpos']?></b></td></tr>
			<tr><td>Titular:&nbsp;</td><td><b><?=$datos[0]['num']." - ".$datos[0]['num_nom']?></b></td></tr>
			<tr><td><b><?=($datos[0]['trib_id']==1 or $datos[0]['cuit']=='' ? 'Responsable Plan:' : 'CUIL/CUIT:')?> &nbsp;</b></td>
				<td><b><?=($datos[0]['trib_id']==1 or $datos[0]['cuit']=='' ? $datos[0]['resp'] : $datos[0]['cuit'])?> </b></td>
			</tr>
		</table>
	</div>
	<div style='overflow: hidden;margin-top:5px;width:100%'>
		<table border='0' class='cond' >
			<tr><td>Fecha Impresión:</td><td><?=date('d/m/Y')?>&nbsp;&nbsp;&nbsp; </td>
				<td>Broche:</td><td><?=$datos[0]['distrib_nom']?></td>
			</tr>
			<tr><td>Fecha Alta Comercio:</td><td><?=$datos[0]['fchalta']?>&nbsp;&nbsp;&nbsp;</td>
				<td>Rubro:</td><td><?=$datos[0]['rubro_nom']?></td>
			</tr>
		</table>
	</div>
	<div class='divredon cond' width='100%' style='margin-top:5px;overflow:hidden;padding:5px'>
		Identificador:&nbsp;<b><?=$datos[0]['inti_id']?></b>&nbsp;&nbsp;&nbsp;&nbsp;
		Lote Nº:&nbsp;<b><?=$datos[0]['lote_id']?></b>&nbsp;&nbsp;&nbsp;&nbsp;
		<b>Tributo:</b>&nbsp;<?=$datos[0]['trib_nom']?>&nbsp;&nbsp;&nbsp;&nbsp;
		Fecha Calculo Deuda:&nbsp;<?=$datos[0]['fchinte']?>
	</div>
	<div class='tt14' width='100%' style='margin-top:5px;overflow:hidden;padding:5px'><b><?= $criterio ?></b></div>
</div>
<!-- Fin Encabezado -->

<!-- Cuerpo -->
<div class='cond' width='100%' style='margin-top:5px;overflow:hidden;padding:5px;border-top:1px solid #000;border-bottom:1px solid #000;'>
	<?= $datos[0]['mensaje'] ?>
</div>
<div class='divredon cond' width='100%' style='margin-top:5px;overflow:hidden;padding:5px'>
	Cantidad Períodos:&nbsp;<?= $datos[0]['periodos'] ?>&nbsp;&nbsp;&nbsp;&nbsp;
	Nominal:&nbsp;<?= $datos[0]['nominal'] ?>&nbsp;&nbsp;&nbsp;&nbsp;
	Accesorios:&nbsp;<?= $datos[0]['accesor'] ?>&nbsp;&nbsp;&nbsp;&nbsp;
	Multa:&nbsp;<?= $datos[0]['multa'] ?>&nbsp;&nbsp;&nbsp;&nbsp;
	<b>Total:&nbsp;<?= $datos[0]['total'] ?></b>
</div>
<?php 

if (!(substr($texto3,1,1) == 'S' or substr($texto3,5,1) == 'S' or substr($texto3,6,1) == 'S')){
	//muestro subinforme 1
?>
	<br>
	<font class='cond'><b>Resumen:</b></font>
	<div class='cond' width='100%' style='overflow:hidden;padding:5px;border-top:1px solid #000;border-bottom:1px solid #000;'>
		<table class='desc'>
			<tr class='border_bottom'>
				<td><b>Objeto</b></td><td><b>Dato</b></td><td><b>Tributo</b></td><td><b>Año</b></td>
				<td><b><?= ($datos[0]['trib_id'] == 1 or $datos[0]['trib_id'] == 3 ? 'Cuotas Vencidas' : '01 02 03 04 05 06 07 08 09 10 11 12') ?></b></td>
				<td><b>Monto</b></td><td><b>Accesorio</b></td><td><b>Multa</b></td><td><b>Total</b></td>
			</tr>
			<?php 
				$monto = 0; $accesor = 0; $multa = 0; $total = 0;
				for ($i=0; $i<count($sub1);$i++) {
					echo "<tr>";
					echo "<td>".$sub1[$i]['obj_id']."</td>";
					echo "<td>".$sub1[$i]['obj_dato']."</td>";
					echo "<td>".$sub1[$i]['trib_nom']."</td>";
					echo "<td>".$sub1[$i]['anio']."</td>";
					echo "<td>".$sub1[$i]['detalle']."</td>";
					echo "<td>".$sub1[$i]['monto']."</td>";
					echo "<td>".$sub1[$i]['accesor']."</td>";
					echo "<td>".$sub1[$i]['multa']."</td>";
					echo "<td>".$sub1[$i]['total']."</td>";
					echo "</tr>";
					
					$monto += $sub1[$i]['monto']; $accesor += $sub1[$i]['accesor']; $multa += $sub1[$i]['multa']; $total += $sub1[$i]['total'];	
				}
			?>
			<tr class='border_top'>
				<td align='right' colspan='5'><b>Total:</b></td>
				<td><b><?= $monto ?></b></td><td><b><?= $accesor ?></b></td><td><b><?= $multa ?></b></td><td><b><?= $total ?></b></td>
			</tr>
		</table>
	</div>
<?php
}elseif (!(substr($texto3,1,1) == 'S' or substr($texto3,6,1) == 'S' or substr($texto3,5,1) == 'N')){
	// muestra subinforme 2
?>
	<br>
	<font class='cond'><b>Resumen:</b></font>
	<div class='cond' width='100%' style='overflow:hidden;padding:5px;border-top:1px solid #000;border-bottom:1px solid #000;'>
		<table class='desc'>
			<tr class='border_bottom'>
				<td><b>Objeto</b></td><td><b>Dato</b></td><td><b>Tributo</b></td>
				<td><b>Monto</b></td><td><b>Accesorio</b></td><td><b>Multa</b></td><td><b>Total</b></td>
			</tr>
			<?php 
				$monto = 0; $accesor = 0; $multa = 0; $total = 0;
				for ($i=0; $i<count($sub2);$i++) {
					echo "<tr>";
					echo "<td>".$sub2[$i]['obj_id']."</td>";
					echo "<td>".$sub2[$i]['obj_dato']."</td>";
					echo "<td>".$sub2[$i]['trib_nom']."</td>";
					echo "<td>".$sub2[$i]['monto']."</td>";
					echo "<td>".$sub2[$i]['accesor']."</td>";
					echo "<td>".$sub2[$i]['multa']."</td>";
					echo "<td>".$sub2[$i]['total']."</td>";
					echo "</tr>";
					
					$monto += $sub1[$i]['monto']; $accesor += $sub1[$i]['accesor']; $multa += $sub1[$i]['multa']; $total += $sub1[$i]['total'];	
				}
			?>
			<tr class='border_top'>
				<td align='right' colspan='3'><b>Total:</b></td>
				<td><b><?= $monto ?></b></td><td><b><?= $accesor ?></b></td><td><b><?= $multa ?></b></td><td><b><?= $total ?></b></td>
			</tr>
		</table>
	</div>
<?php	
}elseif (!(substr($texto3,1,1) == 'S' or substr($texto3,5,1) == 'S' or substr($texto3,6,1) == 'N')){
	// muestra subinforme 3		
?>
	<br>
	<font class='cond'><b>Resumen:</b></font>
	<div class='cond' width='100%' style='overflow:hidden;padding:5px;border-top:1px solid #000;border-bottom:1px solid #000;'>
		<table class='desc'>
			<tr class='border_bottom'>
				<td><b>Objeto</b></td><td><b>Cta</b></td><td><b>Tributo</b></td><td><b>Año</b></td>
				<td><b>Cuota</b></td><td><b>Total</b></td><td><b>UF</b></td><td><b>Expediente</b></td><td><b>Observaciones</b></td>
			</tr>
			<?php 
				for ($i=0; $i<count($sub3);$i++) {
					echo "<tr>";
					echo "<td>".$sub3[$i]['obj_id']."</td>";
					echo "<td>".$sub3[$i]['subcta']."</td>";
					echo "<td>".$sub3[$i]['trib_nom']."</td>";
					echo "<td>".$sub3[$i]['anio']."</td>";
					echo "<td>".$sub3[$i]['cuota']."</td>";
					echo "<td>".$sub3[$i]['total']."</td>";
					echo "<td>".$sub3[$i]['ucm']."</td>";
					echo "<td>".$sub3[$i]['expe']."</td>";
					echo "<td>".$sub3[$i]['obs']."</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>
<?php	
}
?>
<div class='cond' width='100%' style='overflow:hidden;padding:5px;margin-top:50px'>
	<table width='80%' class='desc' align='center'>
		<tr>
			<td class='border_top' align='center'><?= $texto1 ?></td><td width='20%'></td>
			<td class='border_top' align='center'><?= $texto2 ?></td>
		</tr>
	</table>
</div>
<!-- Fin Cuerpo -->

<!-- Talon -->
<div class='cond' width='100%' style='overflow:hidden;padding:5px;margin-top:10px;border-top:1px dashed #000'>
	<div class='divredon cond' width='100%' style='overflow:hidden;padding:5px;margin-top:5px'>
		Objeto:&nbsp;<?= '<b>'.$datos[0]['obj_id'].'</b> '.$datos[0]['obj_nom'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Tiular:&nbsp;<?= $datos[0]['num'].' '.$datos[0]['num_nom'] ?>
	</div>
	<br><b><u>Recibo:</u></b>
	<table width='70%' class='cond'>
		<tr>
			<td>Fecha: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  / &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <br>
			    Hora: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <td width='5%'></td>
			<td class='desc'>Cod.Barra</td>
			<td align='center'><?=$datos[0]['codbarra']?><br><barcode code="<?=$datos[0]['codbarra']?>" type="C128C" /></td>
		</tr>
	</table>
	<br>
	<table width='100%' class='cond' align='center'>
		<tr>
			<td width='10%'>Domicilio:</td><td width='60%' class='border_bottom'> </td> <td width='5%' ></td> <td></td>
		</tr>
		<tr>
			<td></td><td></td><td></td><td align='center' width='30%' class='border_top'>Firma Notificador</td>
		</tr>
	</table>
	<table width='100%' class='desc' align='center' style='margin-top:60px'>
		<tr>
			<td class='border_top' align='center'>Firma</td><td width='20px'></td>
			<td class='border_top' align='center'>Aclaración</td><td width='20px'></td>
			<td class='border_top' align='center'>D.N.I</td><td width='20px'></td>
			<td class='border_top' align='center'>Caracter</td><td width='20px'></td>
			<td class='border_top' align='center'>Teléfono</td>
		</tr>
	</table>
</div>
<!-- Fin Talon -->