<div class='body' >
	<p class='tt'> Operacion de Venta Nº <?= $model->vta_id ?> </p>
	<div class='divredon' style='margin-top:10px; padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10%'> <b> Fecha: </b> </td> <td> <?= $model->fecha ?> </td>
				<td width='10%'> <b> Estado: </b> </td> <td> <?= $model->est_nom ?> </td>
			</tr>
			<tr>
				<td> <b> Objeto: </b> </td> <td colspan='3'> <?= $model->obj_id . " - " . $model->inm_nom ?> </td>
			</tr>
			<tr>
				<td> <b> Escribano: </b> </td> <td colspan='3'> <?= $model->escribano . " - " . $model->escribano_nom ?> </td>
			</tr>
			<tr>
				<td> <b> NC: </b> </td> <td> <?= $model->nc ?> </td>
				<td> <b> Domicilio: </b> </td> <td> <?= $model->domicilio ?> </td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Comprador</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>CUIT</b></td><td><b>Nombre</b></td><td><b>Nacionalidad</b></td><td><b>Porcentaje</b></td><td><b>Domicilio</b></td>
			</tr>
			<?php 
			for ($i=0; $i<count($model->compradores);$i++){
				echo '<tr>';
				echo '<td>'.$model->compradores[$i]['cuit'].'</td>';
				echo '<td>'.$model->compradores[$i]['nombre'].'</td>';
				echo '<td>'.$model->compradores[$i]['tnac_nom'].'</td>';
				echo '<td>'.$model->compradores[$i]['porc'].'</td>';
				echo '<td>'.$model->compradores[$i]['dompo'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b><u>Vendedor</u></b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr class='border_bottom'>
				<td><b>CUIT</b></td><td><b>Nombre</b></td><td><b>Nacionalidad</b></td><td><b>Porcentaje</b></td><td><b>Domicilio</b></td>
			</tr>
			<?php 
			for ($i=0; $i<count($model->vendedores);$i++){
				echo '<tr>';
				echo '<td>'.$model->vendedores[$i]['cuit'].'</td>';
				echo '<td>'.$model->vendedores[$i]['nombre'].'</td>';
				echo '<td>'.$model->vendedores[$i]['tnac_nom'].'</td>';
				echo '<td>'.$model->vendedores[$i]['porc'].'</td>';
				echo '<td>'.$model->vendedores[$i]['dompo'].'</td>';
				echo '</tr>';
			}
			?>
		</table>
	</div>
</div>