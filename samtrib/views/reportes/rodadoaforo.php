<div class='body' >
	<p class='tt'> Rodado Aforo </p>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='60px'>ID:</td><td><b><?= $datos->aforo_id ?></b></td></tr>
			<tr>
				<td>Fábrica:</td>
				<td>
					<b><?= $datos->fabr?></b> &nbsp;
					Origen:&nbsp; <b><?= ($datos->origen == 'I' ? 'Internacional' : 'Nacional') ?></b> &nbsp;
					Tipo Vehic.:&nbsp; <b><?= $datos->tvehic?></b> &nbsp;
				</td>
			</tr>
			<tr><td>Marca:</td><td><b><?= $datos->marca ?> &nbsp; - &nbsp; <?= $datos->marca_nom ?></b></td></tr>
			<tr><td>Tipo:</td><td><b><?= $datos->tipo ?> &nbsp; - &nbsp; <?= $datos->tipo_nom ?></b></td></tr>
			<tr><td>Modelo:</td><td><b><?= $datos->modelo ?> &nbsp; - &nbsp; <?= $datos->modelo_nom ?></b></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Valores por Año</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width="100%" border="0">
			<?php
			$insertados = 0;
			foreach($datos->valores as $valor){
				if($insertados == 0) echo '<tr>';
				
				echo "<td><b>" . $valor['anio'] . ":</b></td> <td align='right'>" . $valor['valor'] . "</td>";
				
				$insertados++;
				
				if ($insertados < 5) echo "<td width='30px'></td>";
				
				if($insertados == 5){
					 echo '</tr>';
					 $insertados = 0;
				}
			}
			?>
		</table>
	</div>
	
</div>
 