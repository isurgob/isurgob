<div class='body' >
	<p class='tt'> Reporte de Cementerio </p>
	<div class='tt14' style='margin-top:10px;'> <b>Identificación</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='100px'>Objeto:</td><td colspan='2'><?= $datos[0]['obj_id'].' - '.$datos[0]['nombre'] ?></td>
				<td width='30px'>Estado:</td><td><b><?= $datos[0]['est_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Tipo:</td><td><?= $datos[0]['tipo_nom'] ?></td>
				<td>
					Cuadro: <?= $datos[0]['cua_id'] ?> 
					Cuerpo: <?= $datos[0]['cue_id'] ?>  
					Fila: <?= $datos[0]['fila'] ?> 
					Nume: <?= $datos[0]['nume'] ?> 
					Piso: <?= $datos[0]['piso'] ?> 
					Bis: <?= $datos[0]['bis'] ?>
				</td>
				<td colspan='2'></td>
			</tr>
			<tr>
				<td>Código Anterior:</td><td><?= $datos[0]['cod_ant'] ?></td>
				<td></td>
				<td></td><td></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Domicilio</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='30px'>Postal:</td><td colspan='2'><?= $datos[0]['dompos_dir'] ?></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Fechas</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellpadding='4' cellspacing='0'>
			<tr>
				<td width='30px'>Alta:</td><td><?= $datos[0]['fchalta'] ?></td>
				<td width='30px'>Ingreso:</td><td><?= $datos[0]['fchingreso'] ?></td>
				<td width='30px'>Vencimiento:</td><td><?= $datos[0]['fchvenc'] ?></td>
				<td width='30px'>Baja:</td><td><?= $datos[0]['fchbaja'] ?></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Otros Datos</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellpadding='4' cellspacing='0'>
			<tr>
				<td width='30px'>Categoría:</td><td><?= $datos[0]['cat'] ?></td>
				<td width='30px'>Delegación:</td><td><?= $datos[0]['deleg_nom'] ?></td>
				<td width='30px'>Exenta:</td><td><?= $datos[0]['exenta_nom'] ?></td>
				<td width='30px'>Edicto:</td><td><?= $datos[0]['edicto'] ?></td>
			</tr>
			<tr>
				<td>Tomo:</td><td><?= $datos[0]['tomo'] ?></td>
				<td>Folio:</td><td><?= $datos[0]['folio'] ?></td>
				<td>Fecha Compra:</td><td><?= $datos[0]['fchcompra'] ?></td>
				<td></td><td></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Titulares</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4' style='border-top:1px solid #000'>
			<tr class='border_bottom'><td><b>Num</b></td><td><b>Nombre</b></td><td><b>Tipo Doc.</b></td><td><b>Número</b></td><td><b>Vínculo</b></td><td><b>Porc</b></td></tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				echo "<tr>";
				echo "<td>".$sub1[$i]['num']."</td>";
				echo "<td>".$sub1[$i]['apenom']."</td>";
				echo "<td>".$sub1[$i]['tdoc']."</td>";
				echo "<td>".$sub1[$i]['ndoc']."</td>";
				echo "<td>".$sub1[$i]['tvinc_nom']."</td>";
				echo "<td>".$sub1[$i]['porc']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Fallecidos</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Nombre</b></td><td><b>Estado</b></td><td><b>Documento</b></td><td><b>Sexo</b></td><td><b>Inhumación</b></td><td><b>Defunción</b></td></tr>
		<?php
			for ($i=0; $i<count($sub2); $i++){
				echo "<tr>";
				echo "<td>".$sub2[$i]['apenom']."</td>";
				echo "<td>".$sub2[$i]['est_nom']."</td>";
				echo "<td>".$sub2[$i]['tdoc_nom'].' '.$sub2[$i]['ndoc']."</td>";
				echo "<td>".$sub2[$i]['sexo']."</td>";
				echo "<td>".$sub2[$i]['fchinh']."</td>";
				echo "<td>".$sub2[$i]['fchdef']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Arrendamiento</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4'>
			<tr class='border_bottom'><td><b>Título</b></td><td><b>Fecha</b></td><td><b>Inicio</b></td><td><b>Finalización</b></td>
				<td><b>Estado</b></td><td><b>Responsable</b></td>
			</tr>
		<?php
			for ($i=0; $i<count($sub3); $i++){
				echo "<tr>";
				echo "<td>".$sub3[$i]['titulo']."</td>";
				echo "<td>".$sub3[$i]['fchalq']."</td>";
				echo "<td>".$sub3[$i]['fchini']."</td>";
				echo "<td>".$sub3[$i]['fchfin']."</td>";
				echo "<td>".$sub3[$i]['est_nom']."</td>";
				echo "<td>".$sub3[$i]['resp_nom']."</td>";
				echo "</tr>";
			}
		?>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Observaciones</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td><?= $datos[0]['obs'] ?></td></tr>
		</table>
	</div>
</div>
	