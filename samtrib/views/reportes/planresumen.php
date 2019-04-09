<?php 
$objdato = "";
$nro = "";
if ($datos['obj_tipo'] == 1 or $datos['obj_tipo'] == 4)
{
    $objdato = "Nomenclatura: ";
}else if ($datos['obj_tipo'] == 2)
{
    $objdato = "CUIT: ";
}else if ($datos['obj_tipo'] == 3) 
{
    $objdato = "Documento: ";
}else if ($datos['obj_tipo'] == 5 or $datos['obj_tipo'] == 6)
{ 
    $objdato = "Dominio";
}

if ($datos['tpago'] == 4)
{
	$nro = "Nro. Tarjeta: "; 
}else if ($datos['tpago'] == 2)
{
	$nro = "Legajo: "; 
}else if ($datos['tpago'] == 6)
{
	$nro = "Cargo/Area: ";	
}else if ($datos['tpago'] == 5)
{
	$nro = "Nro.Cuenta: ";
} 

$apagar = $datos['financia']+$datos['sellado']+$datos['total'];
    
?>
<div class='body' >
	<p class='tt'>Convenio - Resumen</p>
	<div class='divredon'>
		<table class='cond' width='100%' cellspacing='8'>
		<tr>
			<td><b>Nro.Convenio:</b></td><td><?= $datos['plan_id']?></td><td width='110px'><b>Estado:</b></td><td><?= $datos['est_nom']?></td><td><b>Origen:</b></td><td><?= $datos['origen_nom']?></td>
		</tr>
		<tr>
			<td><b>Objeto:</b></td><td colspan='3'><?= $datos['obj_id'].' - '.$datos['obj_nom'] ?></td><td><b><?= $objdato ?></b></td><td><?= $datos['obj_dato']?></td>
		</tr>
		<tr>
			<td><b>Tipo de Convenio:</b></td><td colspan='5'><?= $datos['tplan'].' - '.$datos['tplan_nom'] ?></td>
		</tr>
		<tr>
			<td><b>Contribuyente:</b></td><td colspan='5'><?= $datos['num'].' - '.$datos['num_nom'] ?></td>
		</tr>
		<tr>
			<td><b>Responsable:</b></td><td colspan='3'><?= $datos['resp'] ?></td><td><b>Documento: </b></td><td><?= $datos['respndoc']?></td>
		</tr>
		<tr>
			<td><b>Forma de Pago:</b></td><td><?= $datos['tpago_nom']?></td><td><b>Caja:</b></td><td colspan='3'><?= $datos['caja_nom']?></td>
		</tr>
		<tr>
			<td><b>T.Empleado:</b></td><td><?= $datos['temple_nom']?></td><td><b>T.Emp.Area:</b></td><td><?= $datos['temple_area']?></td><td><b>Sucursal:</b></td><td><?= $datos['bco_suc_nom']?></td>
		</tr>
		<tr>
			<td><b>Tipo de Cuenta:</b></td><td colspan='3'><?= $datos['bco_tcta_nom'] ?></td><td><b><?= $nro ?></b></td><td><?= $datos['tpago_num']?></td>
		</tr>
		<tr>
			<td><b>Cant.Cuotas:</b></td><td><?= $datos['cuotas']?></td><td><b>Monto de Cuota:</b></td><td><?= $datos['montocuo']?></td><td><b>Plan Anterior:</b></td><td><?= $datos['planant']?></td>
		</tr>
		</table>
	</div>
	<div class='divredon' style='margin-top: 5px'>
		<table class='cond' cellspacing='8'>
		<tr><td colspan='6'><u><b>DEUDA</b></u></td></tr>
		<tr>
			<td><b>Nominal:</b></td><td align='right' ><?= $datos['nominal']?></td><td width='50px'></td><td><b>Financiación:</b></td><td align='right'><?= $datos['financia']?></td><td width='50px'></td><td><b>Anticipo:</b></td><td align='right'><?= $datos['anticipo']?></td>
		</tr>
		<tr>
			<td><b>Accesorio:</b></td><td align='right' ><?= $datos['accesor']?></td><td width='50px'></td><td><b>Sellado:</b></td><td align='right'><?= $datos['sellado']?></td>
		</tr>
		<tr>
			<td><b>Multa:</b></td><td align='right' ><?= $datos['multa']?></td>
		</tr>
		<tr><td colspan='2'><hr style="color: #000; margin:1px;" noshade="noshade" size="5" /></td><td width='50px'></td><td colspan='2'><hr style="color: #000; margin:1px;" noshade="noshade" size="5" /></td></tr>
		<tr>
			<td><b>Total Deuda:</b></td><td align='right' ><?= $datos['total']?></td><td width='50px'></td><td><b>A Pagar:</b></td><td align='right' ><?= $apagar ?></td>
		</tr>
		</table>
	</div>
	<div class='divredon' style='margin-top: 5px'>
		<table class='cond' cellspacing='8'>
		<tr><td colspan='6'><u><b>FECHAS</b></u></td></tr>
		<tr>
			<td><b>Alta:</b></td><td><?= $datos['fchalta']?></td><td width='50px'></td><td><b>Baja:</b></td><td align='right'><?= $datos['fchabaja']?></td>
		</tr>
		<tr>
			<td><b>Imputación:</b></td><td><?= $datos['fchimputa']?></td><td width='50px'></td><td><b>Decaimiento:</b></td><td><?= $datos['fchdecae']?></td>
		</tr>
		</table>
	</div>
	<div class='divredon' style='margin-top: 5px'>
		<table class='cond' cellspacing='8'>
		<tr><td><u><b>OBSERVACION</b></u></td></tr>
		<tr><td><?=$datos['obs']?></td></tr>
		</table>
	</div>
</div>