<div class='body' >
	<p class='tt'> Informe del Rodado </p>
	<div class='tt14' style='margin-top:10px;'> <b>Identificación</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='70px'>Objeto:</td><td><b><?= $datos[0]['obj_id'].' - '.$datos[0]['nombre'] ?></b></td>
				<td width='80px'></td><td></td>
				<td width='50px'>Estado:</td><td><b><?= $datos[0]['est_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Dominio:</td><td><b><?= $datos[0]['dominio'] ?></b></td>
				<td>Dominio Ant.:</td><td><b><?= $datos[0]['dominioant'] ?></b></td>
				<td>Categoría:</td><td><b><?= $datos[0]['cat_nom']  ?></b></td>
			</tr>
			<tr>
				<td>Tipo de Alta:</td><td><b><?= $datos[0]['talta_nom'] ?></b></td>
				<td>Periodo Inicial:</td><td><b><?= $datos[0]['per_ini'] ?></b></td>
				<td></td><td></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:10px;'> <b>Características</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='80px'>Año:</td><td><b><?= $datos[0]['anio'] ?></b></td>
				<td width='110px'></td><td></td>
				<td width='110px'>Tipo de Liquidación:</td><td><b><?= $datos[0]['tliq_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Marca:</td><td><b><?= $datos[0]['marca'] ?></b></td>
				<td></td><td></td>
				<td>Modelo:</td><td><b><?= $datos[0]['modelo_nom']  ?></b></td>
			</tr>
			<tr>
				<td>Marca Motor:</td><td><b><?= $datos[0]['marcamotor_nom'] ?></b></td>
				<td></td><td></td>
				<td>Nº Motor:</td><td><b><?= $datos[0]['nromotor']  ?></b></td>
			</tr>
			<tr>
				<td>Marca Chasis:</td><td><b><?= $datos[0]['marcachasis_nom'] ?></b></td>
				<td></td><td></td>
				<td>Nº Chasis:</td><td><b><?= $datos[0]['nrochasis']  ?></b></td>
			</tr>
			<tr>
				<td>Peso:</td><td><b><?= $datos[0]['peso'] ?></b></td>
				<td>Cilindrada:</td><td><b><?= $datos[0]['cilindrada'] ?></b></td>
				<td>Color:</td><td><b><?= $datos[0]['color']  ?></b></td>
			</tr>
			<tr>
				<td>Delegación:</td><td><b><?= $datos[0]['deleg_nom'] ?></b></td>
				<td></td><td></td>
				<td>Combustible:</td><td><b><?= $datos[0]['combustible_nom']  ?></b></td>
			</tr>
			<tr>
				<td>Uso:</td><td><b><?= $datos[0]['uso_nom'] ?></b></td>
				<td></td><td></td>
				<td>Conductor:</td><td><b><?= $datos[0]['conductor_nom']  ?></b></td>
			</tr>
			<tr>
				<td>Valor Fiscal:</td><td><b><?= $datos[0]['aforo_valor'] ?></b></td>
				<td></td><td></td>
				<td></td><td></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Fechas</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='90px'>Fecha Compra:</td><td><b><?= $datos[0]['fchcompra'] ?></b></td>
				<td width='70px'>Fecha Alta:</td><td><b><?= $datos[0]['fchalta'] ?></b></td>
				<td width='70px'>Fecha Baja:</td><td><b><?= $datos[0]['fchbaja'] ?></b></td>
			</tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Domicilio</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr><td width='30px'>Postal:</td><td><b><?= $datos[0]['dompos_dir'] ?></b></td></tr>
		</table>
	</div>
	<div class='tt14' style='margin-top:20px;'> <b>Titulares</b> </div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' width='100%' cellspacing='0' cellpadding='4' style='border-top:1px solid #000'>
			<tr class='border_bottom'><td><b>Num</b></td><td><b>Nombre</b></td><td><b>Tipo Doc.</b></td><td><b>Número</b></td><td><b>Vínculo</b></td><td><b>Porc</b></td></tr>
		<?php
			for ($i=0; $i<count($sub1); $i++){
				if ($sub1[$i]['est'] == 'A'){
					echo "<tr>";
					echo "<td>".$sub1[$i]['num']."</td>";
					echo "<td>".$sub1[$i]['apenom']."</td>";
					echo "<td>".$sub1[$i]['tdoc']."</td>";
					echo "<td>".$sub1[$i]['ndoc']."</td>";
					echo "<td>".$sub1[$i]['tvinc_nom']."</td>";
					echo "<td>".$sub1[$i]['porc']."</td>";
					echo "</tr>";
				}	
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