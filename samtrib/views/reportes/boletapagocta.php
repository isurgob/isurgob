<?php
use yii\helpers\Html;
use app\utils\db\utb;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$mostrar_nom_usr = utb::getCampo('sam.config', "1=1", 'repo_usu_nom');
?>
<!-- Encabezado -->
<div style='overflow: hidden;margin-bottom:5px'>
	<div style='float:left;' width='22%'>
		<img src='<?= Yii::$app->param->logo_grande; ?>' border='0' />
	</div>
	<div style='float:left;margin-left:10px;margin-top:-20px' width='26%' class='desc'>
		<font class='tt14'><b><?= Yii::$app->param->muni_name ?></b></font><br><br>
		<b><?= ($emision[0]['trib_id'] != 9 ? 'DIRECCION DE INGRESOS PUBLICOS<br>' : '') ?></b>
		<?= Yii::$app->param->muni_domi ?><br>
		<?= 'Tel.: '.Yii::$app->param->muni_tel ?><br>
		<?= Yii::$app->params->muni_mail ?><br>
	</div>
	<div class='divredon desc' width='50%' style='float:left;margin-left:10px'>
		<table border='0' width='100%' style='padding:0px 5px' class='cond'>
			<tr><td width='30px'>Objeto:&nbsp;</td><td><b><?=$emision[0]['obj_id'] ?></b></td><td align='right'>Cta:&nbsp;<b><?=$emision[0]['subcta'] ?></b></td></tr>
			<tr><td>Titular:&nbsp;</td><td colspan='2'><b><?=$emision[0]['num']." - ".$emision[0]['num_nom']?></b></td></tr>
			<tr><td>Domicilio:&nbsp;</td><td colspan='2'><b><?=$emision[0]['domi']?></b></td></tr>
			<tr><td>Localidad:&nbsp;</td><td colspan='2'><?=$emision[0]['codpos']?></td></tr>
		</table>
	</div>
</div>
<!-- Fin Encabezado -->

<!-- Cuerpo -->
<div style='overflow: hidden;margin-top:5px;'>
	<!-- Izquierda -->
	<div style='float:left;width:50%'>
		<!-- Datos Emi -->
		<div class='divredon desc' style='height:95px;'>
			<div align='center' class='tt14'><b><?=$emision[0]['trib_nom']?></b></div>
			<div align='center' class='cond'><b>Pago a Cuenta</b></div>
			<table class='desc' cellpadding='5' width='100%'>
				<tr>
					<td>
						<?= "Ref.: <b>P".$emision[0]['pago_id']."</b>" ?><br>
						<?= "Fecha Impresión: ".date('d/m/Y') ?><br>
					</td>
					<td align='right'>
						<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
							<tr bgcolor='#ccc'>
								<td>
								<?php 
									if ($emision[0]['trib_id'] == 1){
										echo 'Nº Conv.';
									}elseif ($emision[0]['trib_id'] == 2){
										echo 'Nº Facilidad';
									}else {
										echo 'Año';
									}	
								?>
								</td>
								<td>Cuota</td>
							</tr>
							<tr><td class='tt18' style='padding:2px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$emision[0]['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$emision[0]['cuota']?></b></td></tr>
						</table>
					</td>
				</tr>
			</table>	
		</div>
	</div>
	<!-- Derecha -->
	<div style='float:left;width:50%;margin-left:5px;'>
		<!-- Vencimientos -->
		<div class='divredon desc' style='padding:0px 5px;' height='95px'>
			VENCIMIENTO:
			<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;' width='100%'>
				<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
				<tr><td align='center' style='border:1px solid #ccc;border-collapse:collapse;'><b><?= $emision[0]['fchlimite'] ?><b></td><td align='right'><b><?= $emision[0]['monto'] ?>&nbsp;&nbsp;&nbsp; </b></td></tr>
			</table>
		</div>
	</div>
</div>	
<!-- Fin Cuerpo -->

<!-- Cuentas -->
<div class='divredon desc' style='overflow:hidden;margin-top:5px;padding:5px;height:500px'>
	<table class='cond' style='text-align:center;' width='80%' align='center'>
		<tr class='border_bottom'><td width='100px'><b>Cuenta</b></td><td align='left'><b>Nombre</b></td><td align='right'><b>Monto</b></td></tr>
		<?php 
			$monto = 0;
			for ($i=0; $i<count($sub1); $i++){
				$monto += $sub1[$i]['monto'];
				echo "<tr><td align='center'>".$sub1[$i]['cta_id']."</td><td align='left'>".$sub1[$i]['cta_nom']."</td><td align='right'>".$sub1[$i]['monto']."</td></tr>";
			}
		?>
		<tr class='border_top'><td></td><td></td><td align='right'><b><?= number_format($monto,2) ?></b></td></tr>
	</table>
</div>
<!-- Fin Cuentas -->

<div class='desc' style='overflow:hidden;margin-top:5px;padding:5px'>
	<?= $emision[0]['obs'] ?>
</div>

<table width='100%' class='desc'>
	<tr>
		<td><b>SAM</b> &nbsp;Sistema de Administración Municipal - Módulo Tributario</td>
		<td align='center'><?= Yii::$app->param->muni_name ?></td>
		<td align='right'>Para el Contribuyente</td>
	</tr>
</table>

<!-- Talón -->
<div style='overflow: hidden; margin-top:5px;border-top:1px dashed #000000;position:fixed;bottom:0'>
	<div class='desc' style='float:left;width:30%'>
		<div class='tt14'><b><?= Yii::$app->param->muni_name ?></b></div>
		<div class='divredon desc' style='padding:5px; margin-top:5px;height:50px'>
			Objeto:&nbsp;<b><?= $emision[0]['obj_id'] ?></b>&nbsp;&nbsp; Cta:&nbsp; <?= $emision[0]['subcta'] ?> <br>
			Titular: <br> <b><?= $emision[0]['num']." - ".$emision[0]['num_nom'] ?></b><br>
		</div>
		<!-- Datos Emi -->
		<div class='divredon desc' style='margin-top:5px;height:90px'>
			<div align='center' class='cond'><b><?=$emision[0]['trib_nom']?> <br> Pago a Cuenta/Parcial</b></div>
			<hr style="color: #000; margin:1px" />
			<table class='desc'><tr><td>
				<?= "Ref.: <b>P".$emision[0]['pago_id']."</b>" ?><br>
				<?= "Fecha Impresión:<b>".date('d/m/Y')."</b>" ?>
				</td>
				<td style='margin-left:10px'>
				<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
					<tr bgcolor='#ccc'>
						<td>
						<?php 
							if ($emision[0]['trib_id'] == 1){
								echo 'Nº Conv.';
							}elseif ($emision[0]['trib_id'] == 2){
								echo 'Nº Facilidad';
							}else {
								echo 'Año';
							}	
						?>
						</td>
						<td>Cuota</td></tr>
					<tr><td class='tt18' style='padding:2px 4px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$emision[0]['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$emision[0]['cuota']?></b></td></tr>
				</table>
			</td></tr></table>	
		</div>
		<!-- Vencimientos -->
		<div class='divredon desc' style='margin-top:5px;padding:0px 5px;height:65px'>
			VENCIMIENTO:
			<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;' width='100%'>
				<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
				<tr><td align='center' style='border-right:1px solid #ccc;border-collapse:collapse;'><b><?= $emision[0]['fchlimite'] ?></b></td><td align='right'><?= $emision[0]['monto'] ?></td></tr>
			</table>
		</div>
		<div align='right'> Para el Ente Recaudador</div>
	</div>
	
	<div style='float:left;width:69%;margin-left:5px;border-left:1px dashed #000;'>
		<div class='tt14' style='margin-left:5px'><b><?= Yii::$app->param->muni_name ?></b></div>
		<div class='divredon desc' style='padding:5px; margin-top:5px; margin-left:5px;height:50px'>
			<table class='desc'>
				<tr>
					<td>Objeto:&nbsp;</td><td><b><?= $emision[0]['obj_id'] ?></b></td>
					<td>Titular:&nbsp;</td><td><b><?=$emision[0]['num'].' - '.$emision[0]['num_nom']?></b></td>
				</tr>
				<tr>
					<td>Cta:&nbsp;</td><td><b><?=$emision[0]['subcta']?></b></td>
					<td>Domicilio:&nbsp;</td><td><b><?=$emision[0]['domi']?></b></td>
				</tr>
				<tr>
					<td></td><td></td>
					<td>Localidad:&nbsp;</td><td><b><?=$emision[0]['codpos']?></b></td>
				</tr>
			</table>
		</div>
		<div class='divredon desc' style='margin:5px;float:left;width:55%;height:90px'>
			<div align='center' class='cond'><b><?=$emision[0]['trib_nom']?> <br> Pago a Cuenta/Parcial</b></div>
			<hr style="color: #000; margin:1px" />
			<table class='desc'>
				<tr>
					<td>
						<?= "Ref.: <b>P".$emision[0]['pago_id']."</b>" ?><br>
						<?= "Fecha Impresión: ".date('d/m/Y') ?>
					</td>
					<td  style='margin-left:20px'>
						<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
							<tr bgcolor='#ccc'>
								<td>
								<?php 
									if ($emision[0]['trib_id'] == 1){
										echo 'Nº Conv.';
									}elseif ($emision[0]['trib_id'] == 2){
										echo 'Nº Facilidad';
									}else {
										echo 'Año';
									}	
								?>
								</td>
								<td>Cuota</td>
							</tr>
							<tr><td class='tt18' style='padding:2px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$emision[0]['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$emision[0]['cuota']?></b></td></tr>
						</table>
					</td>
				</tr>
			</table>	
		</div>
		<!-- Vencimientos -->
		<div class='divredon desc' style='padding:0px 5px;float:right;width:40%;height:90px'>
			VENCIMIENTO:
			<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;' width='100%'>
				<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
				<tr><td align='center' style='border-right:1px solid #ccc;border-collapse:collapse;'><b><?= $emision[0]['fchlimite'] ?></b></td><td align='right'><?= $emision[0]['monto'] ?></td></tr>
			</table>
		</div>
		<div style='overflow: hidden; width:100%;margin-left:5px;height:65px'>
			<table class='desc' width='100%'>
				<tr><td align='center'><?=$emision[0]['codbarra']?></td></tr>
				<tr><td align='center'><barcode code="<?=$emision[0]['codbarra']?>" type="C128C" /></td></tr>
			</table>
		</div>
		<table width='100%' class='desc'><tr><td><?= Yii::$app->user->id . ($mostrar_nom_usr ? " - " . utb::getCampo('sam.sis_usuario', "usr_id = " . Yii::$app->user->id, 'apenom') : '') ?></td><td align='right'><?= ($emision[0]['trib_id'] != 9 ? 'DIRECCION DE INGRESOS PUBLICOS<br>' : '') ?></td></tr></table>
	</div>
</div>
<!-- Fin Talón -->