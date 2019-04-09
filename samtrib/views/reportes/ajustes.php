<div class='body' >
	<p class='tt'> Ajuste manual de cuenta corriente </p>
	<div class='divredon' style='margin-top:10px; padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='10px'>Ajuste:</td><td><b><?= $datos->aju_id ?></b></td></tr>
			<tr><td width='10px'>Tributo:</td><td><b><?= $datos->trib_nom ?></b></td></tr>
			<tr><td width='10px'>Objeto:</td><td><b><?= $datos->obj_id . ' - ' .$datos->obj_nom ?></b></td></tr>
			<tr>
				<td width='10px'>Subcta:</td>
				<td>
					<b><?= $datos->subcta ?></b> &nbsp;
					Año:&nbsp;<b><?= $datos->anio ?></b> &nbsp;
					Cuota:&nbsp;<b><?= $datos->cuota ?></b> &nbsp;
				</td>
			</tr>
			<tr><td width='10px'>Expediente:</td><td><b><?= $datos->expe ?></b></td></tr>
			<tr><td width='10px'>Observación:</td><td><b><?= $datos->obs ?></b></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Cuentas</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>Fecha</b></td><td><b>Nombre</b></td><td><b>Debe</b></td><td><b>Haber</b></td>
			</tr>
			<?php 
			for ($i=0; $i<count($cuentas);$i++){
				echo '<tr>';
				echo '<td>'.$cuentas[$i]['fecha'].'</td>';
				echo '<td>'.$cuentas[$i]['cta_nom'].'</td>';
				echo '<td>'.$cuentas[$i]['debe'].'</td>';
				echo '<td>'.$cuentas[$i]['haber'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
</div>