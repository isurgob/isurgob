<?php 
use app\utils\db\utb;

$num = utb::getCampo("objeto","obj_id='" . $model->obj_id . "'","num");
$foto =	file_exists("//var/www/html/samtrib/images/persona/" . $num. '_Foto.png') ? "//var/www/html/samtrib/images/persona/" . $num. '_Foto.png' : ""; 

?>

<div class='body' >
	<div style='width:100%; border:1px solid'>
		<div class='cond' style='width:48%; border-right:1px solid;float:left;padding:5px'>
			<b>ARGENTINA - CHUBUT - <?= Yii::$app->param->muni_name ?></b> <br><br>
			<div style='width:100px; height:100px; border:1px solid;float:left;'>
				<?php if ($foto != "") { ?>
					<img src="<?= $foto ?>" width='100px' height='100px'>
				<?php }	?>
			</div>	
			<div style='width:auto; height:0px; border:1px solid;float:right;margin-left:5px; padding:5px;10px'>
				<?php
					echo "<b>";
					echo utb::getCampo("trib","trib_id=" . $model->trib_id);
					echo "<br>";
					echo "Nº: " . utb::getCampo("persona","obj_id='" . $num . "'", "ndoc");
					echo "</b>";
					echo "<br><br>"; 
					echo "<b> CATEGORIA " . $model->cat . "</b>";
					echo "<br><br>"; 
					echo "<font class='desc'>" . utb::getCampo("objeto_trib_cat","cat='" . str_pad($model->cat, 2, "0", STR_PAD_LEFT) . "' and trib_id=" . $model->trib_id, "nombre") . ": ";
					echo $model->obs . "</font>";
				?>	
			</div>
			<div style="clear:both"></div>
			<br>
			<font class="cond12"><b><?= utb::getCampo("objeto","obj_id='" . $num . "'", "nombre") ?></b></font> <br>
			<font class="cond">Documento: &nbsp;&nbsp;&nbsp;&nbsp; <?= utb::getCampo("v_persona","obj_id='" . $num . "'", "tdoc_nom") ?>&nbsp; - &nbsp;<?= utb::getCampo("v_persona","obj_id='" . $num . "'", "ndoc") ?></font> <br>
			<font class="cond">F.Nacimiento: <?= date('d/m/Y',strtotime(utb::getCampo("persona","obj_id='" . $num . "'", "fchnac"))) ?></font> <br>
			<font class="cond">Dirección: &nbsp;&nbsp;&nbsp;&nbsp; <?= utb::getCampo("v_persona","obj_id='" . $num . "'", "dompos_dir") ?></font> <br>
		</div>
		<div class='cond' style='width:49%;float:right;padding:5px 5px 5px 0px'>
			<div style='margin-left:50px'>
				<b>
					Habilitación Vence: <?= $model->ahasta . "/" . $model->chasta ?> <br>
					Contribuyente: <?= $model->obj_id ?> 
				</b>
			</div>	
			<div style='margin-top:50px'>
				<?= $model->obs ?>
			</div>	
		</div>
	</div>
</div>