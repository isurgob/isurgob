<div class='body' >
	<p class='tt'> Liquidación de Mejoras - Resumen </p>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'><b>Número:</b></td><td><b><?= $datos[0]['plan_id'] ?></b></td>
				<td width='50px'><b>Estado:</b></td><td><?= $datos[0]['est_nom'] ?></td>
			</tr>
			<tr>
				<td><b>Objeto:</b></td><td colspan='3'><?= $datos[0]['obj_id'].' - '.$datos[0]['obj_nom'] ?></td>
			</tr>
			<tr>
				<td><b>Domicilio:</b></td><td colspan='3'><?= $datos[0]['dompar'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Datos de la Obra</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='90px'><b>Tipo de Obra:</b></td><td colspan='3'><?= $datos[0]['tobra'].' - '.$datos[0]['tobra_nom']  ?></td>
			</tr>
			<tr>
				<td><b>Obra:</b></td><td colspan='3'><?= $datos[0]['obra_id'].' - '.$datos[0]['obra_nom'] ?></td>
				<td width='10px'><b>Cuadra:</b></td><td colspan='3'><?= $datos[0]['cuadra_id'].' - '.$datos[0]['cuadra_nom'] ?></td>
			</tr>
			<tr>
				<td><b>F. Cálculo:</b></td><td><?= $datos[0]['tcalculo_nom'] ?></td>
			</tr>
			<tr>
				<td><b>Valor Mts.:</b></td><td><?= $datos[0]['valormetro'] ?></td>
				<td width='10px'><b>Total:</b></td><td><?= $datos[0]['valortotal'] ?></td>
				<td><b>Bonificación:</b></td><td><?= $datos[0]['bonifobra'] ?></td>
				<td width='10px'><b>Fijo:</b></td><td><?= $datos[0]['fijo'] ?></td>
			</tr>
			<tr>
				<td><b>Frente:</b></td><td><?= $datos[0]['frente'] ?></td>
				<td><b>Sup.Afec.:</b></td><td><?= $datos[0]['supafec'] ?></td>
				<td><b>Coef.:</b></td><td><?= $datos[0]['coef'] ?></td>
				<td><b>Monto:</b></td><td><?= $datos[0]['monto'] ?></td>
			</tr>
			<tr>
				<td><b>Item:</b></td><td><?= $datos[0]['item_nom'] ?></td>
				<td><b>Valor:</b></td><td><?= $datos[0]['item_monto'] ?></td>
				<td><b>Total:</b></td><td><?= $datos[0]['total'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Fechas</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellpadding='4' cellspacing='0'>
			<tr>
				<td width='10px'><b>Alta:</b></td><td><?= $datos[0]['fchalta'] ?></td>
				<td width='10px'><b>Baja:</b></td><td><?= $datos[0]['fchbaja'] ?></td>
				<td width='10px'><b>Desafecta:</b></td><td><?= $datos[0]['fchdesaf'] ?></td>
				<td width='10px'><b>Modificación:</b></td><td><?= $datos[0]['modif'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Observaciones</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td><?= $datos[0]['obs'] ?></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Financiación</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellpadding='4' cellspacing='0'>
			<tr>
				<td width='65px'><b>Tipo Plan:</b></td><td><?= $datos[0]['tplan_nom'] ?></td>
			</tr>
			<tr>
				<td><b>Anticipo:</b></td><td><?= $datos[0]['anticipo'] ?></td>
				<td width='10px'><b>Cuotas:</b></td><td><?= $datos[0]['cuotas'] ?></td>
				<td width='90px'><b>Financiación:</b></td><td><?= $datos[0]['financia'] ?></td>
			</tr>
			<tr>
				<td><b>Sellado:</b></td><td><?= $datos[0]['sellado'] ?></td>
				<td><b>Descuento:</b></td><td><?= $datos[0]['desnominal'] ?></td>
				<td><b>Monto Cuota:</b></td><td><?= $datos[0]['montocuo'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Cuotas</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='90%' cellspacing='0' cellpadding='4' align='center'>
			<tr class='border_bottom'>
				<td><b>Cuota</b></td>
				<td align='right'><b>Monto</b></td> 
				<td align='center'><b>Vencimiento</b></td> 
				<td align='center'><b>Estado</b></td> 
				<td align='center'><b>Pago</b></td> 
				<td align='center'><b>Caja</b></td> 
			</tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				echo "<tr>";
				echo "<td>" . $sub1[$i]['cuota_nom'] . "</td>";
				echo "<td align='right'>".$sub1[$i]['total']."</td>";
				echo "<td align='center'>".$sub1[$i]['venc']."</td>";
				echo "<td align='center'>".$sub1[$i]['est']."</td>";
				echo "<td align='center'>".$sub1[$i]['fchpago']."</td>";
				echo "<td align='center'>".$sub1[$i]['caja_id']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
</div>
	