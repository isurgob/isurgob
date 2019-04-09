<?php
use yii\helpers\Html;
use app\utils\db\utb;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

?>
<!-- Encabezado 0 -->
<div style='overflow: hidden;margin-bottom:5px'>	
	<font class='tt14'><b>Declaración Jurada</b></font>&nbsp;&nbsp;
	<?php if ($emision[0]['dj_orden'] <> "0") { ?>
		<font class='cond'><b><?= $emision[0]['dj_orden_nom'] ?></b></font>
	<?php }else { ?>
		<font class='cond'><b>Original</b></font>
	<?php } ?>
	<div class='divredon desc' width='40%' style='float:left' height='70px'>
		<table border='0' width='100%' style='padding:0px 5px' class='cond'>
			<tr><td width='30px'>Objeto:&nbsp;</td><td><b><?=$emision[0]['obj_id'] ?></b></td><td align='right'>Cód.DJ:&nbsp;<b><?=$emision[0]['dj_id'] ?></b></td></tr>
			<tr><td>Domicilio:&nbsp;</td><td colspan='2'><b><?=$emision[0]['domi']?></b></td></tr>
			<tr><td>Titular:&nbsp;</td><td colspan='2'><b><?=$emision[0]['num']." - ".$emision[0]['num_nom']?></b></td></tr>
		</table>
	</div>
	<div style='float:left;margin-left:10px;margin-top:-20px' width='30%' class='desc'>
		<?= ($cuerpo == 1 ? 'Para Rentas Municipal' : 'Para el Contribuyente') ?>
		<br><br>
		<b><?= Yii::$app->param->muni_name ?></b><br>
		<?= Yii::$app->params->muni_domi ?><br>
		<?= 'Tel.: '.Yii::$app->param->muni_tel ?><br>
		<?= Yii::$app->params->muni_mail ?><br>
	</div>
	<div style='float:left;margin-left:10px;margin-top:-15px' width='25%'>
		<img src='<?= Yii::$app->param->logo_grande ?>' border='0' />
	</div>
</div>
<!-- Fin Encabezado -->

<!-- Datos Cuerpo -->
<div style='overflow: hidden;'>
	<!-- Izquierda -->
	<div style='float:left;width:50%;'>
		<div class='divredon desc' style='height:75px;'>
			<?php if ($emision[0]['tobj'] == 2){ ?> 
				<table border='0' width='100%' style='padding:0px 5px;height:110px' class='desc'>
					<tr><td>Cond.IVA:</td><td colspan='2'><b><?= $sub2[0]['iva_nom'] ?></b></td></tr>
					<tr><td>CUIT:</td><td><b><?= $sub2[0]['cuit'] ?></b></td><td width='50px'>Ing.Brutos:</td><td><b><?= $sub2[0]['ib'] ?></b></td></tr>
					<tr><td>Liquidación:</td><td><b><?= $sub2[0]['tipoliq_nom'] ?></b></td><td colspan='1'><b><?= ($sub2[0]['tipo_nom'] == 1 ? 'Sin Local Comercial' : '') ?></b></td></tr>
					<tr><td colspan='1'><b><?= ($sub2[0]['alquila'] == 1 ? 'Alquila Local' : '') ?></b></td><td colspan='1'><b><?= ($sub2[0]['pi'] == 1 ? 'Parque Industrial' : '') ?></b></td></tr>
				</table>
			<?php	} ?>
			<?php if ($emision[0]['tobj'] == 3){ ?> 
				<table border='0' width='100%' style='padding:0px 5px;height:110px' class='desc'>
					<tr><td>Tipo:</td><td><b><?= $sub2[0]['tipo_nom'] ?></b></td><td>Doc:</td><td><b><?= $sub2[0]['tdoc_nom'].' '.$sub2[0]['ndoc'] ?></b></td></tr>
					<tr><td>Cond.IVA:</td><td><b><?= $sub2[0]['iva_nom'] ?></b></td><td>CUIT/CUIL:</td><td><b><?= $sub2[0]['cuit'] ?></b></td></tr>
					<tr><td>Nº IB:</td><td colspan='2'><b><?= $sub2[0]['ib'] ?></b></td></tr>
					<tr><td>Dom.Legal:</td><td colspan='2'><b><?= $sub2[0]['domleg_dir'] ?></b></td></tr>					
				</table>
			<?php	} ?>
		</div>
		<div class='divredon desc' style='margin-top:5px' height='140px'>			
			<table border='0' width='100%' style='padding:0px 5px' class='desc'>
				<tr><td colspan='2'><b>Rubro</b></td><td><b>Base/Cant</b></td><td><b>Mín.</b></td><td><b>Alic.</b></td><td><b>Monto</b></td></tr>
				<?php 
				if (count($sub3) > 0) {	
					foreach ($sub3 as $dato){
						if ($emision[0]['ctacte_id'] == $dato['ctacte_id']){
				?>	
							<tr>
								<td><?= $dato['rubro_id'] ?></td>
								<td><?= substr($dato['rubro_nom'],0,40) ?></td>
								<td align='right'><?= ($dato['tcalculo'] != 4 ? $dato['base'] : $dato['cant'] )?></td>
								<td align='right'><?= $dato['minimo'] ?></td>
								<td><?= $dato['alicuota'] ?></td>
								<td align='right'><?= $dato['monto'] ?></td>
							</tr>
				<?php			
						}
					} 
				}	
				?>
				
			</table>
		</div>
		<div class='divredon desc' style='margin-top:5px;' height='70px'>		 
			<?php if ($cuerpo == 1){ ?>
				<table border='0' width='100%' class='desc' style='margin-top:50px'>
					<tr>
						<td class='border_top' style='margin:0px 30px' align='center'>Firma Contribuyente</td><td></td>
						<td class='border_top' style='margin:0px 30px' align='center'>Aclaración</td><td></td>
						<td class='border_top' style='margin:0px 30px' align='center'>Nro. Documento</td>
					</tr>
				</table>
			<?php }else {
				echo '<div style="padding:5px">'.$emision[0]['mensaje'].'</div>';
			  } ?>
		</div>
	</div>
	<!-- Derecha -->
	<div style='float:left;width:50%;margin-left:5px'>
		<!-- Datos Emi -->
		<div class='divredon desc' style='height:75px;'>
			<div align='center' class='cond'><b><?=$emision[0]['trib_nom']?></b></div>
			<hr style="color: #000; margin:1px" />
			<table class='desc' cellpadding='5' width='100%'>
				<tr>
					<td>
						<?= "Ref.: <b class='cond'>".$emision[0]['ctacte_id']."</b>" ?><br>
						<?= "Fecha Presentación: ".$emision[0]['fchemi'] ?><br>
						<?= "Fecha Impresión: ".date('d/m/Y') ?><br>
					</td>
					<td align='right'>
						<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
							<tr bgcolor='#ccc'><td>Año</td><td>Cuota</td></tr>
							<tr><td class='tt18' style='padding:2px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$emision[0]['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$emision[0]['cuota']?></b></td></tr>
						</table>
					</td>
				</tr>
			</table>	
		</div>
		<!-- Datos Liquidación -->
		<div class='divredon desc' style='margin-top:5px;padding:0px 5px' height='140px'>
			<?php
				$arrayliq = null;
								
				for($j=0; $j<count($sub1); $j++)
				{
					if ($sub1[$j]['ctacte_id'] == $emision[0]['ctacte_id']) $arrayliq[$j] = $sub1[$j];
				} 
				$totalitem = 0;
				if ($arrayliq != null) {
					foreach ($arrayliq as $valorItem){
						$totalitem += $valorItem["item_monto"];
					}
				}	
				$totalitem = number_format($totalitem,2,".","");
				
				echo GridView::widget([
					'id' => 'GrillaLiq',
					'dataProvider' => new ArrayDataProvider(['allModels' => $arrayliq]),
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard5'],
					'showFooter' => true,
					'columns' => [
								 	['attribute'=>'item_id','header' => 'Detalle'],
								 	['attribute'=>'item_nom','header' => '','contentOptions'=>['style'=>'width:200px']],
								 	['attribute'=>'item_monto','header' => 'Importe','footer' => $totalitem,
										'footerOptions' => ['style' => 'border-top:1px solid #000;text-align:right;font-weight:bold','class' => 'cond'], 
										'contentOptions'=>['style'=>'width:90px;text-align:right']],
								 ],
    			]);
    			
    			echo "Total: ".$totalitem."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cubierto: ".$emision[0]['nominalcub'];
    			if (isset($emision[0]['nominalcub']) and $emision[0]['nominalcub'] != 0) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Saldo: ".number_format($emision[0]['monto'] - $emision[0]['nominalcub'],2);
			?>
		</div>
		<!-- Vencimientos -->
		<div class='divredon desc' style='margin-top:5px;padding:0px 5px;' height='70px'>
			<table class='desc' style='text-align:center;' width='100%'>
				<tr bgcolor='#ddd'><td>Pago</td><td>Vencimiento</td><td>Importe</td></tr>
				<tr><td align='left'><b>1er.Venc.</b></td><td><b><?= $emision[0]['venc1'] ?></b></td><td align='right'><b><?= ($emision[0]['nominalcub'] == 0 ? $totalitem : number_format($totalitem - $emision[0]['nominalcub'],2) ) ?></b></td></tr>
				<?php if ($emision[0]['venc1'] != $emision[0]['venc2']) {?>
					<tr><td align='left'><b>2do.Venc.</b></td><td><b><?= $emision[0]['venc2'] ?></b></td><td align='right'><b><?= $emision[0]['montovenc2'] ?></b></td></tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>
<!-- Fin Datos Cuerpo -->