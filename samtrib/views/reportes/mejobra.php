
<div class='body' >
	<p class='tt'> Obra de Mejora </p>
	
	<div class='divredon' style='margin-top:10px; padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'> <b> Código: </b> </td> <td> <?= $model->obra_id ?> </td>
				<td width='80px'> <b> Tipo: </b> </td> <td colspan='3'> <?= $model->tobra_nom ?> </td>
				<td width='10px'> <b> Estado: </b> </td> <td> <?= $model->est_nom ?> </td>
			</tr>
			<tr>
				<td> <b> Nombre: </b> </td> <td colspan='3'> <?= $model->nombre ?> </td>
				<td width='70px'> <b> Decreto: </b> </td> <td colspan='3'> <?= $model->decreto ?> </td>
			</tr>
			<tr>
				<td> <b> Calculo: </b> </td> <td> <?= $model->tcalculo_nom ?> </td>
				<td> <b> Total Frente: </b> </td> <td> <?= $model->totalfrente ?> </td>
				<td> <b> Total Sup.: </b> </td> <td> <?= $model->totalsupafec ?> </td>
			</tr>
			<tr>
				<td> <b> Valor Total: </b> </td> <td> <?= $model->valortotal ?> </td>
				<td> <b> Valor Metro: </b> </td> <td> <?= $model->valormetro ?> </td>
				<td> <b> Fijo: </b> </td> <td> <?= $model->fijo ?> </td>
				<td> <b> Bonificación: </b> </td> <td> <?= $model->bonifobra ?> </td>
			</tr>
			<tr>
				<td> <b> Cuenta: </b> </td> <td colspan='3'> <?= $model->cta_nom ?> </td>
				<td> <b> Texto: </b> </td> <td colspan='3'> <?= $model->texto_nom ?> </td>
			</tr>
			<tr>
				<td> <b> Inicio: </b> </td> <td> <?= date( 'd/m/Y', strtotime($model->fchini) ) ?> </td>
				<td> <b> Fin: </b> </td> <td> <?= date( 'd/m/Y', strtotime($model->fchfin) ) ?> </td>
				<td> <b> Modificación: </b> </td> <td colspan='3'> <?= $model->modif ?> </td>
			</tr>
			<tr>
				<td> <b> Observación: </b> </td> <td colspan='7'> <?= $model->obs ?> </td>
			</tr>
		</table>
	</div>
	
	<div class='tt14' style='margin-top:10px;'> <b><u>Cuadras</u></b> </div>
	
	<div class='divredon' style='margin-top:10px; padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>NCM</b></td><td><b>Calle</b></td><td><b>Calle Nombre</b></td>
			</tr>
			<?php 
			foreach ( $model->cuadras as $c ){
				echo '<tr>';
				echo '<td>'.$c['ncm'].'</td>';
				echo '<td>'.$c['calle_id'].'</td>';
				echo '<td>'.$c['calle_nom'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
	
</div>