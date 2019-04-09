<?php
use yii\helpers\Html;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;

?>
<!-- Encabezado -->
<div style='overflow: hidden;margin-bottom:5px'>	
	<div style='float:left;' width='25%'>
		<img src='<?= Yii::$app->request->baseUrl.'/images/logo_muni_grande.jpg'?>' border='0' />
	</div>
	<div style='float:left;margin-left:10px;' width='30%' class='desc'>
		<font class='tt14'><b><?= Yii::$app->params['MUNI_NAME'] ?></b></font><br><br>
		<?= Yii::$app->params['MUNI_DOMI'] ?><br>
		<?= 'Tel.: '.Yii::$app->params['MUNI_TEL'] ?><br>
		<?= Yii::$app->params['MUNI_MAIL'] ?><br>
	</div>
	<div class='divredon desc' width='40%' style='float:right;margin-left:10px'>
		<table border='0' style='padding:0px 5px' class='desc'>
			<tr><td>Objeto:&nbsp;</td><td><b><?=$datos[0]['obj_id']?></b></td></tr>
			<tr><td>Domicilio:&nbsp;</td><td><b><?=$datos[0]['domi']?></b></td></tr>
			<tr><td>Titular:&nbsp;</td><td><b><?=$datos[0]['num']." - ".$datos[0]['num_nom']?></b></td></tr>
		</table>
	</div>
</div>
<!-- Fin Encabezado -->

<!-- Datos Cuerpo -->
<div style='overflow: hidden;'>
	<div class='divredon'>
		<table class='cond' width='100%'>
			<tr>
				<td>Tributo:&nbsp; <b><?=$datos[0]['trib_nom']?></b></td>
				<td width='100px'>Fecha Impresión:</td><td><b><?=date('d/m/Y')?></b></td>
			</tr>
			<tr>
				<td>Intimado en Lote Número: &nbsp; <b><?=$datos[0]['lote_id']?></b></td>
				<td>Fecha Intimación: </td><td> <b><?=$datos[0]['fchinti']?></b></td>
			</tr>
		</table>	
	</div>
	<div class='divredon' style='margin-top:5px'>
		<table class='cond' width='100%'>
			<tr>
				<td width='120px'>Cantidad de Períodos:</td><td><b><?=$datos[0]['periodos']?></b></td>
				<td width='50px'>Nominal:</td><td><b><?=$datos[0]['nominal']?></b></td>
				<td width='50px'>Accesorio:</td><td><b><?=$datos[0]['accesor']?></b></td>
				<td width='30px'>Multa:</td><td><b><?=$datos[0]['multa']?></b></td>
			</tr>
			<tr>
				<td colspan='4'>Total Deuda: &nbsp;<b> <?=$datos[0]['total']?></b></td>
			</tr>
		</table>	
	</div>
	
	<div style='margin-top:10px' class='tt14'><u><b>Seguimientos de Entregas</b></u></div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' cellspacing='0' cellpadding='4' width='100%'>
			<tr class='border_bottom'>
				<td><b>Fecha</b></td><td><b>Resultado de Entrega</b></td><td><b>Distribuidor</b></td>
			</tr>
			<?php 
				for ($i=0; $i<count($sub1); $i++){
					echo "<tr>";
					echo "<td>".$sub1[$i]['fecha']."</td>";
					echo "<td>".$sub1[$i]['resultado_nom']."</td>";
					echo "<td>".$sub1[$i]['distrib_nom']."</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>
	
	<div style='margin-top:10px' class='tt14'><u><b>Seguimientos de Etapas</b></u></div>
	<div class='divredon' style='padding:5px'>
		<table class='cond' cellspacing='0' cellpadding='4' width='100%'>
			<tr class='border_bottom'>
				<td><b>Fecha</b></td><td><b>Etapa</b></td><td><b>Detalle</b></td><td><b>Generado</b></td>
			</tr>
			<?php 
				for ($i=0; $i<count($sub2); $i++){
					echo "<tr>";
					echo "<td>".$sub2[$i]['fecha']."</td>";
					echo "<td>".$sub2[$i]['etapa_nom']."</td>";
					echo "<td>".$sub2[$i]['detalle']."</td>";
					echo "<td>".($sub2[$i]['genauto'] == 1 ? 'Automático' : 'por Usuario')."</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>
</div>
<!-- Fin Cuerpo -->
