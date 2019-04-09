<?php

use yii\helpers\Html;
use app\utils\db\utb;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$mostrar_nom_usr = utb::getCampo('sam.config', "1=1", 'repo_usu_nom');

if ($emision[0]['trib_tipo'] == 3 or $emision[0]['trib_tipo'] == 4)
{
	$tituloseccion = "DETALLE LIQUIDACION:";
	$vencdesc = "Venc.";
}else if ($emision[0]['trib_id'] == 1){
	$tituloseccion = "PERIODOS INCLUIDOS:";
	$vencdesc = "Venc.";
}else if ($emision[0]['trib_id'] == 2){
	$tituloseccion = "";
	$vencdesc = "Venc.";
}else {
	switch ($emision[0]['tobj']) {
    	case 1:
    		if ($emision[0]['trib_id'] != 28)
    		{
    			$tituloseccion = "TITULARES:";
    		}else {
    			$tituloseccion = "OBRAS SANITARIAS:";
    		}

    		break;
    	case 2 or 7:
    		if ($emision[0]['trib_id'] == 23)
				$tituloseccion = "RUBROS:";
			else 	
				$tituloseccion = "TITULARES:";
    		
			break;
    	default:
    		$tituloseccion = "";
    }
    if ($emision[0]['trib_id'] == 1 or $emision[0]['trib_id'] == 2 or $emision[0]['venc1'] == $emision[0]['venc2'])
    {
    	$vencdesc = "Venc.";
    } else {
    	$vencdesc = "1er. Venc.";
    }
}

for ($i=0; $i<count($emision);$i++) // inicio bloque principal
{
?>
	<!-- Encabezado -->
	<div style='overflow: hidden;margin-bottom:5px'>
		<div class='divredon desc' width='40%' style='float:left'>
			<div class='cond' style='padding:0px 5px'><?= utb::getTObjNom($emision[$i]['obj_id']).": <b>".$emision[$i]['obj_id'].($emision[$i]['subcta'] !== 0 ? " - Cta.:".$emision[$i]['subcta'] : "")."</b>"; ?></div>
			<hr style="color: #000; margin:1px" />
			<table border='0' style='padding:0px 5px' class='cond'>
				<tr><td>Titular:&nbsp;</td><td><b><?= $emision[$i]['num']." - ".$emision[$i]['num_nom'] ?></b></td></tr>
				<tr><td>Domicilio:&nbsp;</td><td><b><?=$emision[$i]['domi']?></b></td></tr>
				<tr><td>Localidad:&nbsp;</td><td><b><?=$emision[$i]['codpos']?></b></td></tr>
				<tr><td colspan='2'><b><?=$emision[$i]['domi_det']?></b></td></tr>
			</table>
			<hr style="color: #000; margin:1px" />
			<div style='padding:0px 5px'><?php if ($emision[$i]['distrib'] != 0) echo $emision[$i]['distrib']." - <b>".$emision[$i]['distrib_nom'],"</b>";?></div>
		</div>
		<div style='float:left;margin-left:10px;padding-top:2%' width='30%' class='desc10'>
			<b><?= Yii::$app->param->muni_name ?></b><br>
			<?= Yii::$app->param->muni_domi ?><br>
			<?= 'Tel.: '.Yii::$app->param->muni_tel ?><br>
			<?= Yii::$app->param->muni_mail ?><br>
			<?= Yii::$app->param->muni_skype == '' ? '' : 'Skype: '.Yii::$app->param->muni_skype ?>
		</div>
		<div style='float:left;margin-left:10px' width='25%'>
			<p align='right'>
				<img src='<?= Yii::$app->param->logo_grande; ?>' border='0' />
			</p>	
		</div>
	</div>
	<!-- Fin Encabezado -->
	
	<div style="clear: both"></div>

	<!-- Datos Cuerpo -->
	<div style='overflow: hidden;'>
		<!-- Izquierda -->
		<div style='float:left;width:50%'>
			<!-- Datos Generales -->
			<?php include('boleta_datos_generales.php'); ?>
			<!-- Sub Informe 3 -->
			<?php include('boleta_subinforme3.php'); ?>
			<div class='desc' style='margin-top:5px;border:1px solid rgb(0, 143, 165);border-radius: 5px; background:rgb(0, 143, 165);color:#ffffff;display:'.<?= ($emision[$i]['trib_calc_rec']!=0 ? 'block' : 'none')?>>
				<div style='padding:2px 3px'> PASADA LA FECHA DE VENCIMIENTO EL IMPORTE SUFRIRÁ RECARGOS POR MORA</div>
			</div>
			<div class='desc' style='margin-top:5px;' height='100px'>
				<?php
					if ($emision[$i]['distrib'] == 1) echo "Adherido al Débito Automático &nbsp;&nbsp;&nbsp;";
					if (($emision[$i]['trib_tipo'] == 3 or $emision[$i]['trib_tipo'] == 9) and $emision[$i]['expe'] != '') echo "Expe/Comprob:&nbsp;".$emision[$i]['expe']."<br>";
				?>
				<b><?=$emision[$i]['titulo_msj']?></b><br>
				<?=$emision[$i]['mensaje']?>
			</div>
		</div>
		<!-- Derecha -->
		<div style='float:left;width:50%;margin-left:5px'>
			<!-- Datos Emi -->
			<div class='divredon desc'>
				<div align='center' class='cond12'><b><?=$emision[$i]['trib_nom']?></b></div>
				<hr style="color: #000; margin:1px" />
				<table class='desc' cellpadding='5' width='100%'><tr><td>
					<?= "Ref.: <b>".$emision[$i]['ctacte_id']."</b>" ?><br>
					<?= "Fecha Emisión: ".$emision[$i]['fchemi'] ?><br>
					<?php
						if ($emision[$i]['trib_tipo'] == 1 or in_array($emision[$i]['trib_id'], [1, 3]))
						{
							echo "CODIGO DE PAGO ELECTRONICO: <font class='cond'> <b>". "0" . $emision[$i]['codigolink']."</b></font>";
						}
					?><br>
					<b> <?= $emision[$i]['mensaje_debito'] ?> </b>
					</td>
					<td align='right'>
					<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
						<tr bgcolor='#ccc'><td>
						  <?php if (in_array($emision[$i]['trib_id'], [1,3])) 
									echo "Nº Conv.";
								else 
									echo "Año";
						  ?>		
							</td><td>Cuota</td></tr>
						<tr><td class='tt18' style='padding:2px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$emision[$i]['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$emision[$i]['cuota']?></b></td></tr>
					</table>
				</td></tr></table>
			</div>
			<!-- Datos Liquidación -->
			<div class='divredon desc' style='margin-top:5px;padding:0px 5px;' height='200px'>
			<font class='cond12'>LIQUIDACION:</font>
			<?php
				$arrayliq = null;

				for($j=0; $j<count($sub1); $j++)
				{
					if ($sub1[$j]['ctacte_id'] == $emision[$i]['ctacte_id']) $arrayliq[$j] = $sub1[$j];
				}
				echo GridView::widget([
					'id' => 'GrillaLiq',
					'dataProvider' => new ArrayDataProvider(['allModels' => $arrayliq]),
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard2'],
					'showFooter' => true,
					'columns' => [
								 	['attribute'=>'item_nom','header' => 'Detalle','contentOptions'=>['style'=>'width:250px;font-size:10px;'],'headerOptions'=>['style'=>'text-align:left']],
								 	['attribute'=>'item_monto','header' => 'Importe','footer' => $emision[$i]['monto'],
										'footerOptions' => ['style' => 'border-top:1px solid #000;text-align:right;font-weight:bold','class' => 'cond'],
										'contentOptions'=>['style'=>'width:90px;text-align:right;font-size:10px']],
								 ],
    			]);

    			if (isset($emision[$i]['nominalcub']) and $emision[$i]['nominalcub'] != 0) echo "Cubierto: ".$emision[$i]['nominalcub'];
    			if (isset($emision[$i]['nominalcub']) and $emision[$i]['nominalcub'] != 0) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Saldo: ".($emision[$i]['monto'] - $emision[$i]['nominalcub']);
			?>
			</div>
			<!-- Vencimientos -->
			<div class='divredon desc' style='margin-top:5px;padding:0px 5px;' height='70px'>
				<font class='cond12'>VENCIMIENTOS:</font>
				<table class='desc10' style='text-align:center;' width='100%'>
					<tr bgcolor='#ddd'><td>Opciones</td><td>Vencimiento</td><td>Desc.</td><td><?= (isset($ucm) && $ucm != '' ? $ucm : 'Importe') ?></td></tr>
					<tr><td align='left'><b><?= (isset($vencdesc) ? $vencdesc : '') ?></b></td><td><b><?= $emision[$i]['venc1'] ?></b></td><td></td><td align='right'><b><?= $emision[$i]['monto'] ?></b></td></tr>
					<?php if ($emision[$i]['venc1'] != $emision[$i]['venc2']) {?>
						<tr><td align='left'><b>2do.Venc.</b></td><td><b><?= $emision[$i]['venc2'] ?></b></td><td></td><td align='right'><b><?= $emision[$i]['montovenc2'] ?></b></td></tr>
					<?php
					}
					 if ($emision[$i]['codbarra'] != '' and $emision[$i]['montoanual'] != 0) {?>
					 	<tr><td align='left'><b>Anual</b></td><td><b><?= date('d/m/Y', strtotime($emision[$i]['vencanual'])) ?></b></td><td><b><?= $emision[$i]['descanual'] ?></b></td><td align='right'><b><?= $emision[$i]['montoanual'] ?></b></td></tr>
					<?php } ?>
				</table>
			</div>
			<!-- Estado de Cuenta -->
			<div class='divredon desc' style='margin-top:5px;padding:0px 5px;' height='190px'>
				<div style='display:<?= ($emision[$i]['genestcta']==0 ? 'none' : 'block')?>'>
				<font class='cond12'>ESTADO DE CUENTA AL:&nbsp;<?= date('d/m/Y'); ?></font>
				<table cellspacing='0' class='desc' style='text-align:center;' width='100%'>
					<?php if ($emision[$i]['genestcta'] == 1) {?>
					<tr bgcolor='#ccc'><td><b>Año</b></td><td><b>01</b></td><td><b>02</b></td><td><b>03</b></td><td><b>04</b></td><td><b>05</b></td><td><b>06</b></td><td></td></tr>
					<?php }else if ($emision[$i]['genestcta'] == 2) {?>
					<tr bgcolor='#ccc'><td><b>Año</b></td><td colspan='6'><b>Cuotas Vencida</b></td></tr>
					<?php
						}
						$totalestcta = 0;

						for($j=0; $j<count($sub4); $j++)
						{
							if ($sub4[$j]['obj_id'] == $emision[$i]['obj_id'] and $sub4[$j]['trib_id'] == $emision[$i]['trib_id'])
							{
								$totalestcta += $sub4[$j]['total'];
								echo '<tr>';
								echo '<td>'.$sub4[$j]['anio']."</td>";
								if ($emision[$i]['genestcta'] == 1) {
									echo "<td>".substr($sub4[$j]['detalle'],0,1)."</td>";
									echo "<td>".substr($sub4[$j]['detalle'],3,1)."</td>";
									echo "<td>".substr($sub4[$j]['detalle'],6,1)."</td>";
									echo "<td>".substr($sub4[$j]['detalle'],9,1)."</td>";
									echo "<td>".substr($sub4[$j]['detalle'],12,1)."</td>";
									echo "<td>".substr($sub4[$j]['detalle'],15,1)."</td>";
								}else {
									echo "<td colspan='6' align='left'>".$sub4[$j]['detalle']."</td>";
								}
								echo "<td align='right'>".$sub4[$j]['total']."</td>";
								echo '</tr>';
							}
						}
					?>
					<tr class='border_top'><td colspan='7' align='right'><b>Total:</b>&nbsp;</td><td align='right'><b><?= $totalestcta ?></b></td></tr>
				</table>
				</div>
			</div>
		</div>
		<div class='desc' style='float:left;width:50%'>
			<b>SAM</b> &nbsp;Sistema de Administración Municipal - Módulo Tributario
		</div>
		<div style='float:left;width:50%;margin-left:5px'>
			<table width='100%' class='desc'><tr><td><?= Yii::$app->param->muni_name?></td><td align='right'>Para el Contribuyente</td></tr></table>
		</div>
	</div>
	<!-- Fin Datos Cuerpo -->

	<!-- Talón -->
	<div style='overflow:hidden; margin-top:5px;border-top:1px dashed #000000; height:320px; position:fixed;bottom:0'>
		<div class='desc' style='float:left;width:30%'>
			<img src='<?= Yii::$app->param->logo_talon ?>' border='0' style='padding:5px 0px 0px 15px' />
			<div class='divredon desc10' style='padding:5px; margin-top:5px;height:55px'>
				Objeto:&nbsp;<b><?= $emision[$i]['obj_id'] ?></b>
				<?php if ($emision[$i]['subcta'] != 0) echo "  - Subcta.: <b>".$emision[$i]['subcta']."</b>" ?> <br>
				Titular:<br><b><?= $emision[$i]['num']." - ".$emision[$i]['num_nom'] ?></b><br>
			</div>
			<!-- Datos Emi -->
			<div class='divredon desc' style='margin-top:5px;height:80px'>
				<div align='center' class='cond'><b><?=$emision[$i]['trib_nom']?></b></div>
				<hr style="color: #000; margin:1px" />
				<table class='desc' cellpadding='5' width='100%'><tr><td>
					<?= "Ref.: <b>".$emision[$i]['ctacte_id']."</b>" ?> 
					</td>
					<td align='right'>
					<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
						<tr bgcolor='#ccc'><td>
						  <?php if (in_array($emision[$i]['trib_id'],[1,3])) 
									echo "Nº Conv.";
								else 
									echo "Año";
						  ?>		
						</td><td>Cuota</td></tr>
						<tr><td class='tt18' style='padding:2px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$emision[$i]['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$emision[$i]['cuota']?></b></td></tr>
					</table>
				</td></tr>
				</table>
				&nbsp;<b> <?= $emision[$i]['mensaje_debito'] ?> </b>
			</div>
			<!-- Vencimientos -->
			<div class='divredon desc' style='margin-top:5px;padding:0px 5px;height:80px'>
				VENCIMIENTOS:
				<table class='desc10' style='text-align:center;' width='100%'>
					<tr bgcolor='#ddd'><td>Opciones</td><td>Vencimiento</td><td><?= (isset($ucm) && $ucm!='' ? $ucm : 'Importe') ?></td></tr>
					<tr><td align='left'><b><?= (isset($vencdesc) ? $vencdesc : '') ?></b></td><td><b><?= $emision[$i]['venc1'] ?></b></td><td align='right'><b><?= $emision[$i]['monto'] ?></b></td></tr>
					<?php if ($emision[$i]['venc1'] != $emision[$i]['venc2']) {?>
						<tr><td align='left'><b>2do.Venc.</b></td><td><b><?= $emision[$i]['venc2'] ?></b></td><td align='right'><b><?= $emision[$i]['montovenc2'] ?></b></td></tr>
					<?php
					}
					 if ($emision[$i]['codbarra'] != '' and $emision[$i]['montoanual'] != 0) {?>
					 	<tr><td align='left'><b>Anual</b></td><td><b><?= date('d/m/Y', strtotime($emision[$i]['vencanual'])) ?></b></td><td align='right'><b><?= $emision[$i]['montoanual'] ?></b></td></tr>
					<?php } ?>
				</table>
			</div>
			<div align='right' style='margin-top:28px'> Para el Ente Recaudador</div>
		</div>
		<div style='float:left;width:69%;margin-left:5px;border-left:1px dashed #000;height:310px'>
			<img src='<?= Yii::$app->param->logo_talon ?>' border='0' style='padding:5px 0px 0px 15px' />
			<div class='divredon desc' style='padding:5px; margin-top:5px; margin-left:5px;height:55px'>
				<table class='desc10'><tr>
					<td>Objeto:&nbsp;<b><?= $emision[$i]['obj_id'] ?></b><br>
					<?php
					if ($emision[$i]['subcta'] != 0) echo "Subcta.: <b>".$emision[$i]['subcta'] . "</b>";
					?>
					</td>
					<td>
					<table border='0' style='padding:0px 5px;margin-left:30px' class='desc10'>
						<tr><td>Titular:&nbsp;</td><td><b><?=$emision[$i]['num']." - ".$emision[$i]['num_nom']?></b></td></tr>
						<tr><td>Domicilio:&nbsp;</td><td><b><?=$emision[$i]['domi']?></b></td></tr>
						<tr><td>Localidad:&nbsp;</td><td><b><?=$emision[$i]['codpos']?></b></td></tr>
					</table>
					</td>
				</tr></table>
			</div>
			<div class='divredon desc' style='margin:5px;float:left;width:55%;height:80px'>
				<div align='center' class='cond'><b><?=$emision[$i]['trib_nom']?></b></div>
				<hr style="color: #000; margin:1px" />
				<table class='desc' cellpadding='5' width='100%'><tr><td>
					<?= "Ref.: <b>".$emision[$i]['ctacte_id']."</b>" ?><br>
					<?= "Fecha Emisión: ".$emision[$i]['fchemi'] ?><br>
					<?= "Fecha Impresión: ".date('d/m/Y') ?><br>
					<?= ($emision[$i]['nominalcub'] != 0 ? 'Cubierto: <b>'.$emision[$i]['nominalcub'].'</b>' : '') ?> <br>
					</td>
					<td align='right'>
					<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
						<tr bgcolor='#ccc'><td>
						  <?php if (in_array($emision[$i]['trib_id'],[1,3])) 
									echo "Nº Conv.";
								else 
									echo "Año";
						  ?>		
						</td><td>Cuota</td></tr>
						<tr><td class='tt18' style='padding:2px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$emision[$i]['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$emision[$i]['cuota']?></b></td></tr>
					</table>
				</td></tr></table>
				&nbsp; <b> <?= $emision[$i]['mensaje_debito'] ?> </b>
			</div>
			<!-- Vencimientos -->
			<div class='divredon desc' style='padding:0px 5px;float:right;width:40%;height:80px'>
				VENCIMIENTOS:
				<table class='desc10' style='text-align:center;' width='100%'>
					<tr bgcolor='#ddd'><td>Opciones</td><td>Vencimiento</td><td><?= (isset($ucm) && $ucm!='' ? $ucm : 'Importe') ?></td></tr>
					<tr><td align='left'><b><?= (isset($vencdesc) ? $vencdesc : '') ?></b></td><td><b><?= $emision[$i]['venc1'] ?></b></td><td align='right'><?= $emision[$i]['monto'] ?></td></tr>
					<?php if ($emision[$i]['venc1'] != $emision[$i]['venc2']) {?>
						<tr><td align='left'><b>2do.Venc.</b></td><td><b><?= $emision[$i]['venc2'] ?></b></td><td align='right'><b><?= $emision[$i]['montovenc2'] ?></b></td></tr>
					<?php
					}
					 if ($emision[$i]['codbarra'] != '' and $emision[$i]['montoanual'] != 0) {?>
					 	<tr><td align='left'><b>Anual</b></td><td><b><?= date('d/m/Y', strtotime($emision[$i]['vencanual'])) ?></b></td><td align='right'><b><?= $emision[$i]['montoanual'] ?></b></td></tr>
					<?php } ?>
				</table>
			</div>
			<div style='overflow: hidden; width:100%;margin-left:5px;height:110px'>
				<?php if ($emision[$i]['est'] == 'P') { ?>
					<p class='tt14' align='center'><b>Comprobante Pago</b></p>
				<?php }else{ ?>
					<table class='desc' width='100%'>
					<tr><td align='left'></td>
						<td align='center'><?=$emision[$i]['codbarra']?></td>
					</tr>
					<tr>
						<td align='left'>Cuota:</td>
						<td align='center'><barcode code="<?=$emision[$i]['codbarra']?>" type="C128C" /></td>
					</tr>
					<?php if ($emision[$i]['codbarraanual'] != '') {?>
					<tr><td align='left'></td>
						<td align='center'><?=$emision[$i]['codbarraanual']?></td>
					</tr>
					<tr>
						<td align='left'>Anual:</td>
						<td align='center'><barcode code="<?=$emision[$i]['codbarraanual']?>" type="C128C" /></td>
					</tr>
					<?php } ?>
					</table>
				<?php } ?>
			</div>
			<table width='100%' class='desc'><tr><td><?= Yii::$app->user->id . ($mostrar_nom_usr ? " - " . utb::getCampo('sam.sis_usuario', "usr_id = " . Yii::$app->user->id, 'apenom') : '') ?></td><td align='right'>Para la Municipalidad</td></tr></table>
		</div>
	</div>
	<!-- Fin Talón -->

	<?php if ($i < count($emision)-1) { ?>
	<!-- Salto de Página --> <div style="PAGE-BREAK-AFTER: always"></div>
	<?php } ?>

<?php
} // fin bloque principal
?>
