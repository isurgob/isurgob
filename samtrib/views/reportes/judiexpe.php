<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;
 
 /*$sql = "select min(c.anio*1000+c.cuota) desde,max(c.anio*1000+c.cuota) hasta";
 $sql .= " from judi_periodo j inner join ctacte c on j.ctacte_id = c.ctacte_id where j.judi_id=".$datos[0]['judi_id'];
 $periodos = Yii::$app->db->createCommand($sql)->queryAll();
 $perdesde = $periodos[0]['desde'];
 $perhasta = $periodos[0]['hasta'];*/
?>
<div class='body' >
	<p class='tt'> Expediente de Apremio Judicial</p>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr>
				<td width='80px'><b>Apremio N°:</b></td><td width='20px' class='tt14'><b><?= $datos[0]['judi_id'] ?></b></td>
				<td width='60px'>Expediente:</td><td><b><?= $datos[0]['expe'] ?></b></td>
				<td width='50px'>Estado:</td><td><b><?= $datos[0]['est_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Objeto:</td><td><b><?= $datos[0]['obj_id'] ?></b></td>
				<td>Carátula:</td><td colspan='3'><b><?= $datos[0]['caratula'] ?></b></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Períodos y Deuda</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr>
				<td width='90px'>Períodos Desde:</td><td><?= substr($datos[0]['perdesde'],0,4).'/'.substr($datos[0]['perdesde'],-3) ?></td>
				<td width='70px'>Nominal:</td><td width='50px' align='right'><?= $datos[0]['nominal'] ?></td>
				<td width='50px'></td>
				<td width='90px'>Multa:</td><td><?= $datos[0]['multa'] ?></td>
				<td></td><td></td>
			</tr>
			<tr>
				<td>Períodos Hasta:</td><td><?= substr($datos[0]['perhasta'],0,4).'/'.substr($datos[0]['perhasta'],-3) ?></td>
				<td>Accesorio:</td><td align='right'><?= $datos[0]['accesor'] ?></td>
				<td width='50px'></td>
				<td>Multa Omisión:</td><td><?= $datos[0]['multa_omi'] ?></td>
				<td width='50px'>Total:</td><td><b><?= $datos[0]['nominal'] + $datos[0]['multa'] + $datos[0]['accesor'] ?></b></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Fechas</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr>
				<td width='40px'>Alta:</td><td><?= $datos[0]['fchalta'] ?></td>
				<td width='60px'>Apremio:</td><td><?= $datos[0]['fchapremio'] ?></td>
				<td width='60px'>Abogado:</td><td><?= $datos[0]['fchprocurador'] ?></td>
			</tr>
			<tr>
				<td>Jucio:</td><td><?= $datos[0]['fchjuicio'] ?></td>
				<td>Devolución:</td><td><?= $datos[0]['fchdev'] ?></td>
				<td>Baja:</td><td><?= $datos[0]['fchbaja'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Otros Datos</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr>
				<td width='70px'>Procurador:</td><td width='20px'><?= $datos[0]['procurador_nom'] ?></td>
				<td width='60px'>Juzgado:</td><td><?= $datos[0]['juzgado_nom'] ?></td>
				<td width='100px'>Número de Plan:</td><td><?= $datos[0]['plan_id'] ?></td>
			</tr>
		</table>
	</div>
	<?php if ($datos[0]['obs'] != '') { ?>
		<div class='tt14' style='margin-top:10px;'> <b>Observación</b> </div>
		<div class='divredon' style='padding:5px'> <?= $datos[0]['obs'] ?> </div>
	<?php } ?>
	<div class='tt14' style='margin-top:10px;'> <b>Etapas</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Fecha</b></td><td><b>Etapa</b></td><td><b>Detalle</b></td></tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				echo "<tr>";
				echo "<td>".$sub1[$i]['fecha']."</td>";
				echo "<td>".$sub1[$i]['etapa_nom']."</td>";
				echo "<td>".$sub1[$i]['detalle']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Liquidación</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='50%' cellspacing='0' cellpadding='4'>
			<tr>
				<td width='40px'>Fecha:</td><td width='150px'><b><?= date('d/m/Y') ?></b></td>
				<td width='50px'>Honorarios:</td><td align='right'><b><?= $datos[0]['hono_jud'] ?></b></td>
			</tr>
			<tr>
				<td></td><td></td>
				<td class='border_bottom'>Gastos:</td><td class='border_bottom' align='right'><b><?= $datos[0]['gasto_jud'] ?></b></td>
			</tr>
			<tr>
				<td></td><td></td>
				<td><b>Total:</b></td><td align='right'><b><?= ($datos[0]['hono_jud'] + $datos[0]['gasto_jud']) ?></b></td>
			</tr>
		</table>
	</div>
</div>