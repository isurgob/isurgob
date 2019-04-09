<?php 
use app\utils\db\utb;

$num = utb::getCampo("objeto","obj_id='" . $model->obj_id . "'","num");
$foto =	file_exists("//var/www/html/samtrib/images/persona/" . $num. '_Foto.png') ? "//var/www/html/samtrib/images/persona/" . $num. '_Foto.png' : ""; 

?>

<div class='body' >
	<div style='width:100%;'>
		<p class='tt' align='center'><b>DECLARACION JURADA DE DATOS</b></p>
		<div class='cond' style='width:100%; padding:5px'>
			<div style='width:100px; height:100px; border:1px solid;float:left;'>
				<?php if ($foto != "") { ?>
					<img src="<?= $foto ?>" width='100px' height='100px'>
				<?php }	?>
			</div>	
			<div style='width:auto; height:0px; float:left;margin-left:50px;'>
				<table class='cond'>
					<tr>
						<td valign='top'><b>Categoría:</b></td>
						<td>
							CATEGORIA <?=utb::getCampo("objeto_trib_cat","cat='" . str_pad($model->cat, 2, "0", STR_PAD_LEFT) . "' and trib_id=" . $model->trib_id, "nombre") ?> 
							<?= utb::getCampo("objeto_trib_cat","cat='" . str_pad($model->cat, 2, "0", STR_PAD_LEFT) . "' and trib_id=" . $model->trib_id, "nombre") ?> 
							<?= $model->obs ?>
						</td>
					</tr>
					<tr>
						<td><b>Desde:</b></td><td><?= $model->adesde . "/" . $model->cdesde ?></td>
					</tr>
					<tr>
						<td><b>Hasta:</b></td><td><?= $model->ahasta . "/" . $model->chasta ?></td>
					</tr>
					<tr><td></td><td></td></tr>
					<tr>
						<td><b>Contribuyente:</b></td><td><?= $num ?></td>
					</tr>
					<tr valign='top'>
						<td><b>Nombre:</b></td><td><?= utb::getCampo("objeto","obj_id='" . $num . "'", "nombre") ?></td>
					</tr>
					<tr>
						<td><b>DNI:</b></td><td><?= utb::getCampo("persona","obj_id='" . $num . "'", "ndoc") ?></td>
					</tr>
					<tr valign='top'>
						<td><b>Nacionalidad:</b></td><td><?= utb::getCampo("v_persona","obj_id='" . $num . "'", "nacionalidad_nom") ?></td>
					</tr>
					<tr>
						<td><b>F.Nacimiento:</b></td><td><?= date('d/m/Y',strtotime(utb::getCampo("persona","obj_id='" . $num . "'", "fchnac"))) ?></td>
					</tr>
					<tr><td></td><td></td></tr>
					<tr>
						<td valign='top'><b>Domicilio:</b></td><td><?= utb::getCampo("v_persona","obj_id='" . $num . "'", "dompos_dir") ?></td>
					</tr>
				</table>
			</div>
		</div>
		
		<br><br><br><br><br><br><br>
		
		<div class='desc' style='text-align:center;width:200px;float:left;border-top:1px solid #000'>
			Funcionario Acuante
		</div>
		<div class='desc' style='text-align:center;width:200px;float:right;border-top:1px solid #000'>
			<?= utb::getCampo("objeto","obj_id='" . $num . "'", "nombre") ?> <br>
			<?= date("d/m/y") ?>
		</div>
	</div>
</div>