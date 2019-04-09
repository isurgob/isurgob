<?php
use yii\helpers\Html;
use app\utils\db\utb;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$mostrar_nom_usr = utb::getCampo('sam.config', "1=1", 'repo_usu_nom');
?>

<!-- Datos Cuerpo 1 -->
<?php $cuerpo = 1; include('boletaddjj_cuerpo.php'); ?>

<!-- Datos Cuerpo 2 -->
<div style='overflow: hidden; margin-top:15px;border-top:1px solid #000000; padding-top:20px;'>
	<?php $cuerpo = 2; include('boletaddjj_cuerpo.php'); ?>
	<div class='desc' style='float:left;width:50%'>
		<b>SAM</b> &nbsp;Sistema de Administración Municipal - Módulo Tributario
	</div>
</div>

<!-- Talón -->
<div style='overflow: hidden; margin-top:5px;border-top:1px solid #000000;'>
	<div class='desc' style='float:left;width:30%'>
		<img src='<?= Yii::$app->param->logo_talon ?>' border='0' style='padding:5px 0px 0px 15px' />
		<div class='divredon desc' style='padding:5px; margin-top:5px;height:40px'>
			Objeto:&nbsp;<b><?= $emision[0]['obj_id'] ?></b><br>
			Titular:&nbsp;<b><?= $emision[0]['num']." - ".$emision[0]['num_nom'] ?></b><br>
		</div>
		<!-- Datos Emi -->
		<div class='divredon desc' style='margin-top:5px;height:70px'>
			<div align='center' class='cond'><b><?=$emision[0]['trib_nom']?></b></div>
			<hr style="color: #000; margin:1px" />
			<table class='desc' width='100%'><tr><td>
				<?= "Ref.: <b>".$emision[0]['ctacte_id']."</b>" ?>
				</td>
				<td style='margin-left:20px' align='right'>
				<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
					<tr bgcolor='#ccc'><td>Año</td><td>Cuota</td></tr>
					<tr><td class='tt18' style='padding:2px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$emision[0]['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$emision[0]['cuota']?></b></td></tr>
				</table>
			</td></tr></table>
		</div>
		<!-- Vencimientos -->
		<div class='divredon desc' style='margin-top:5px;padding:0px 5px;height:65px'>
			VENCIMIENTOS:
			<table class='desc' style='text-align:center;' width='100%'>
				<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
				<tr><td align='left'><b>1er.Venc. &nbsp;&nbsp; <?= $emision[0]['venc1'] ?></b></td><td align='right'><b><?= ($emision[0]['nominalcub'] == 0 ? $totalitem : number_format($totalitem - $emision[0]['nominalcub'],2) ) ?></b></td></tr>
				<?php if ($emision[0]['venc1'] != $emision[0]['venc2']) {?>
					<tr><td align='left'><b>2do.Venc. &nbsp;&nbsp; <?= $emision[0]['venc2'] ?></b></td><td align='right'><b><?= $emision[0]['montovenc2'] ?></b></td></tr>
				<?php } ?>
			</table>
		</div>
		<div align='right'> Para el Ente Recaudador</div>
	</div>

	<div style='float:left;width:69%;margin-left:5px;border-left:1px solid #000;'>
			<img src='<?= Yii::$app->param->logo_talon ?>' border='0' style='padding:5px 0px 0px 15px' />
			<div class='divredon desc' style='padding:5px; margin-top:5px; margin-left:5px;height:40px'>
				<table class='desc'>
					<tr><td>Objeto:&nbsp;</td><td colspan='4'><b><?= $emision[0]['obj_id'].' - '.$emision[0]['obj_nom'] ?></b></td></tr>
					<tr>
						<td>Domicilio:&nbsp;</td><td><b><?=$emision[0]['domi']?></b></td>
						<td width="20px" ></td>
						<td style='margin-left:30px'>Titular:&nbsp;</td><td><b><?=$emision[0]['num_nom']?></b></td>
					</tr>
				</table>
			</div>
			<div class='divredon desc' style='margin:5px;float:left;width:55%;height:70px'>
				<div align='center' class='cond'><b><?=$emision[0]['trib_nom']?></b></div>
				<hr style="color: #000; margin:1px" />
				<table class='desc' width='100%'>
					<tr>
						<td>
							<?= "Ref.: <b>".$emision[0]['ctacte_id']."</b>" ?><br>
							<?= "Fecha Presentación: ".$emision[0]['fchemi'] ?><br>
							<?= "Fecha Impresión: ".date('d/m/Y') ?>
						</td>
						<td  style='margin-left:20px' align='right'>
							<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
								<tr bgcolor='#ccc'><td>Año</td><td>Cuota</td></tr>
								<tr><td class='tt18' style='padding:2px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$emision[0]['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$emision[0]['cuota']?></b></td></tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<!-- Vencimientos -->
			<div class='divredon desc' style='padding:0px 5px;float:right;width:40%;height:70px'>
				VENCIMIENTOS:
				<table class='desc' style='text-align:center;' width='100%'>
					<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
					<tr><td align='left'><b>1er.Venc. &nbsp;&nbsp; <?= $emision[0]['venc1'] ?></b></td><td align='right'><b><?= ($emision[0]['nominalcub'] == 0 ? $totalitem : number_format($totalitem - $emision[0]['nominalcub'],2) ) ?></b></td></tr>
					<?php if ($emision[0]['venc1'] != $emision[0]['venc2']) {?>
						<tr><td align='left'><b>2do.Venc. &nbsp;&nbsp; <?= $emision[0]['venc2'] ?></b></td><td align='right'><b><?= $emision[0]['montovenc2'] ?></b></td></tr>
					<?php } ?>
				</table>
			</div>
			<div style='overflow: hidden; width:100%;margin-left:5px;height:65px'>
				<?php if ($emision[0]['est'] == 'P') { ?>
					<p class='tt14' align='center'><b>Comprobante Pago</b></p>
				<?php }else{ ?>
					<table class='desc' width='100%'>
						<tr><td align='center'><?=$emision[0]['codbarra']?></td></tr>
						<tr><td align='center'><barcode code="<?=$emision[0]['codbarra']?>" type="C128C" /></td></tr>
					</table>
				<?php } ?>
			</div>
			<table width='100%' class='desc'><tr><td><?= Yii::$app->user->id . ($mostrar_nom_usr ? " - " . utb::getCampo('sam.sis_usuario', "usr_id = " . Yii::$app->user->id, 'apenom') : '') ?></td><td align='right'>Para Ingresos Públicos</td></tr></table>
		</div>
</div>
<!-- Fin Talón -->
