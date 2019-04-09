<?php

use yii\helpers\Html;
use app\utils\db\utb;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$mostrar_nom_usr = utb::getCampo('sam.config', "1=1", 'repo_usu_nom');

$cantcuotasanio = utb::getCampo('ctacte',"trib_id=".$emision[0]['trib_id']." and obj_id='".$emision[0]['obj_id'].".' and anio=".$emision[0]['anio'],"count(*)");

$DesSem = (($emision[0]['monto']*3) - $emision[0]['montosem']) / ($emision[0]['monto']*3 );
if ($DesSem  <= 0 ){
	$DesSem = "";
}else {
	$DesSem = "Descuento " . number_format($DesSem,2) . "%";
}
$DesSem = "";
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
			<div class='cond' style='padding:0px 5px'><?= utb::getTObjNom($emision[$i]['obj_id']).": <b>".$emision[$i]['obj_id']." - Cta.:".$emision[$i]['subcta']."</b>"; ?></div>
			<hr style="color: #000; margin:1px" />
			<table border='0' style='padding:0px 5px' class='cond'>
				<tr><td>Titular:&nbsp;</td><td><b><?=$emision[$i]['num']." - ".$emision[$i]['num_nom']?></b></td></tr>
				<tr><td>Domicilio:&nbsp;</td><td><b><?=$emision[$i]['domi']?></b></td></tr>
				<tr><td>Localidad:&nbsp;</td><td><b><?=$emision[$i]['codpos']?></b></td></tr>
				<tr><td colspan='2'><b><?=$emision[$i]['domi_det']?></b></td></tr>
				<tr><td colspan='2'> <b> <?= $emision[$i]['mensaje_debito'] ?> </b></td></tr>
			</table>
			<hr style="color: #000; margin:1px" />
			<div style='padding:0px 5px'><?php if ($emision[$i]['distrib'] != 0) echo $emision[$i]['distrib']." - <b>".$emision[$i]['distrib_nom'],"</b>";?></div>
		</div>
		<div style='float:left;margin-left:10px;padding-top:2%' width='30%' class='desc10'>
			<b><?= Yii::$app->param->muni_name ?></b><br>
			<?= Yii::$app->param->muni_domi ?><br>
			<?= 'Tel.: '.Yii::$app->param->muni_tel ?><br>
			<?= Yii::$app->param->muni_mail ?><br>
			Código Pago Electrónico: <b>0<?=$emision[$i]['codigolink']?></b>
		</div>
		<div style='float:left;margin-left:10px' width='25%'>
			<img src='<?= Yii::$app->param->logo_grande; ?>' border='0' />
		</div>
	</div>
	<!-- Fin Encabezado -->

	<!-- Datos Cuerpo -->
	<div style='overflow: hidden;'>
		<!-- Izquierda -->
		<div style='float:left;width:50%'>
			<!-- Datos Generales -->
			<?php include('boleta_datos_generales.php'); ?>
		</div>
		<!-- Derecha -->
		<div style='float:left;width:50%;margin-left:5px'>
			<!-- Datos Liquidación -->
			<div class='divredon desc' style='padding:0px 5px;' height='215px'>
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
		</div>
		<table width='100%' class='desc'>
			<tr>
				<td width='51%'><b>SAM</b> &nbsp;Sistema de Administración Municipal - Módulo Tributario</td>
				<td><?= Yii::$app->param->muni_name?></td><td align='right'>Para el Contribuyente</td>
			</tr>
		</table>
	</div>
	<!-- Fin Datos Cuerpo -->
	<?php if (($emision[$i]['codbarraanual'] != '' and $emision[$i]['montoanual'] != 0) or ($emision[$i]['codbarrasem'] != '' and $emision[$i]['montosem'] != 0)) {?>
	<!-- Anual Semestral -->
	<div style='overflow:hidden; margin-top:2px;border-top:1px dashed #000000;width:100%;' height='110px'>
		<?php if ($emision[$i]['codbarraanual'] != '' and $emision[$i]['montoanual'] != 0) {?>
			<div class='desc' style='float:left;width:50%; border-right:1px dashed #000;padding-top:5px;'>
				Objeto:&nbsp;<b><?= $emision[$i]['obj_id'] ?></b> &nbsp; Subcta.:&nbsp;<b><?= $emision[$i]['subcta'] ?></b> &nbsp; Titular:<b>&nbsp;<?= $emision[$i]['num']." - ".substr($emision[$i]['num_nom'],0,30) ?></b><br>
				<!-- Vencimientos -->
				<div class='divredon desc' style='margin-top:2px;padding:0px 5px;background:#ccc;float:left;width:70%' height='37px'>
					<table class='desc10' style='text-align:center;' width='100%'>
						<tr bgcolor='#ddd'><td>Vencimiento</td><td>Desc.</td><td>Importe</td></tr>
						<tr><td><b><?= date('d/m/Y',strtotime($emision[$i]['vencanual'])) ?></b></td><td><b><?= $emision[$i]['descanual'] ?></b></td><td align='right'><b><?= $emision[$i]['montoanual'] ?></b></td></tr>
					</table>
				</div>
				<div class='divredon cond12' style='padding:2px 5px;float:right;width:20%;margin-right:5px;text-align:center'>
					<b>Pago <br> Anual</b>
				</div>
				<div style='overflow: hidden;width:100%;margin-top:5px;text-align:center;clear:both;'>
					<?=$emision[$i]['codbarraanual']?> <br>
					<barcode code="<?=$emision[$i]['codbarraanual']?>" type="C128C" size="0.9"  />
				</div>
			</div>
		<?php } ?>
		<?php if ($emision[$i]['codbarrasem'] != '' and $emision[$i]['montosem'] != 0) {?>
			<div class='desc' style='float:right;margin-left:5px;padding-top:2px;width:49%'>
				Objeto:&nbsp;<b><?= $emision[$i]['obj_id'] ?></b> &nbsp; Subcta.:&nbsp;<b><?= $emision[$i]['subcta'] ?></b> &nbsp; Titular:&nbsp;<b><?= $emision[$i]['num']." - ".substr($emision[$i]['num_nom'],0,30) ?></b><br>
				<!-- Vencimientos -->
				<div class='divredon desc' style='margin-top:2px;padding:0px 5px;background:#ccc;float:left;width:72%' height='37px'>
					<table class='desc10' style='text-align:center;' width='100%'>
						<tr bgcolor='#ddd'><td>Vencimiento</td><td><?=$DesSem?></td><td>Importe</td></tr>
						<tr><td><b><?= date('d/m/Y',strtotime($emision[$i]['vencsem'])) ?></b></td><td><b><?= $emision[$i]['descsem'] ?></b></td><td align='right'><b><?= $emision[$i]['montosem'] ?></b></td></tr>
					</table>
				</div>
				<div class='divredon cond12' style='padding:2px 5px;float:right;width:20%;text-align:center'>
					<b>Pago <br><?= $cantcuotasanio==6 ? 'Semestre' : 'Trimestre' ?> </b>
				</div>
				<div style='overflow: hidden;width:100%;margin-top:5px;text-align:center;clear:both;'>
					<?=$emision[$i]['codbarrasem']?> <br>
					<barcode code="<?=$emision[$i]['codbarrasem']?>" type="C128C" size="0.9"  />
				</div>
			</div>
		<?php } ?>
	</div>
	<!-- Fin Anual/Semestral -->
	<?php } else {?>
		<div height='110px'></div>
	<?php } ?>

	<!-- Cuotas -->
	<?php foreach ($sub3 as $cuota){
		if ($cuota['trib_id'] == $emision[$i]['trib_id']) {
	?>
		<div style='overflow:hidden; margin-top:2px;border-top:1px dashed #000000;width:100%; padding-top:5px'>
			<div style='float:left;width:33%; border-right:1px dashed #000;'>
				<div class='divredon desc' style="margin-right:5px;" height='170px'>
					<div align='center' class='desc10' style='padding:1px 0px'><b><?=$emision[$i]['trib_nom']?></b></div>
					<hr style="color: #000; margin:1px" />
					<div class='divredon desc' style="margin:1px 2px;padding:1px 3px">
						Objeto:&nbsp;<b><?= $emision[$i]['obj_id'] ?> </b> &nbsp; Subcta.:&nbsp;<b><?= $emision[$i]['subcta'] ?></b> <br> <?= $emision[$i]['num']." - ".$emision[$i]['num_nom'] ?></b>
						<br><b> <?= $emision[$i]['mensaje_debito'] ?> </b>
					</div>
					<table class='desc' cellpadding='5' width='100%'>
						<tr>
							<td><?= "Ref.: <b>".$cuota['ctacte_id']."</b>" ?></td>
							<td align='right'>
								<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
									<tr bgcolor='#ccc'><td>Año</td><td>Cuota</td></tr>
									<tr><td class='tt18' style='padding:1px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$cuota['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$cuota['cuota']?></b></td></tr>
								</table>
							</td>
						</tr>
					</table>
					<table class='desc10' style='text-align:center;margin:0px 3px 0px 3px;' width='100%'>
						<tr bgcolor='#ddd'><td>Opciones</td><td>Vencimiento</td><td>Importe</td></tr>
						<tr><td align='left'><b>Venc.</b></td><td><b><?= date('d/m/Y',strtotime($cuota['venc1'])) ?></b></td><td align='right'><b><?= $cuota['monto'] ?></b></td></tr>
						<?php if ($cuota['venc1'] != $cuota['venc2']) {?>
							<tr><td align='left'><b>2do.Venc.</b></td><td><b><?= date('d/m/Y',strtotime($cuota['venc2'])) ?></b></td><td align='right'><b><?= $cuota['montovenc2'] ?></b></td></tr>
						<?php } ?>
					</table>
				</div>
				<div class='desc' align='right' style='margin-right:5px'>Para el Ente Recaudador</div>
			</div>
			<div class='divredon desc' style='float:right;margin-left:5px;' height='170px'>
				<div align='center' class='cond12' style='padding:1px 0px'><b><?=$emision[$i]['trib_nom']?></b></div>
				<hr style="color: #000; margin:1px" />
				<div class='divredon desc' style="margin:1px 2px;padding:1px 3px">
					Objeto:&nbsp;<b><?= $emision[$i]['obj_id'] ?></b> &nbsp; Subcta.:&nbsp;<b><?= $emision[$i]['subcta'] ?></b> Titular:&nbsp;<b><?= $emision[$i]['num']." - ".$emision[$i]['num_nom'] ?></b> <br>
					Domicilio:&nbsp;<b><?= $emision[$i]['domi'] ?></b>
					<br><b> <?= $emision[$i]['mensaje_debito'] ?> </b>
				</div>
				<table class='desc' cellpadding='5' width='100%'>
					<tr>
						<td>
							<table class='desc10' style='text-align:center;' width='100%'>
								<tr bgcolor='#ddd'><td>Opciones</td><td>Vencimiento</td><td>Desc.</td><td>Importe</td></tr>
								<tr><td align='left'><b>Venc.</b></td><td><b><?= date('d/m/Y',strtotime($cuota['venc1'])) ?></b></td><td><?=$cuota['descmonto1']?></td><td align='right'><b><?= $cuota['monto'] ?></b></td></tr>
								<?php if ($cuota['venc1'] != $cuota['venc2']) {?>
									<tr><td align='left'><b>2do.Venc.</b></td><td><b><?= date('d/m/Y',strtotime($cuota['venc2'])) ?></b></td><td><?=$cuota['descmonto2']?></td><td align='right'><b><?= $cuota['montovenc2'] ?></b></td></tr>
								<?php } ?>
							</table>
						</td>
						<td align='right'>
							<table class='desc' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
								<tr bgcolor='#ccc'><td>Año</td><td>Cuota</td></tr>
								<tr><td class='tt18' style='padding:1px 5px;border-right:1px solid #ccc;border-collapse:collapse;'><b><?=$cuota['anio']?></b></td><td class='tt18' style='padding:2px 5px'><b><?=$cuota['cuota']?></b></td></tr>
							</table>
						</td>
					</tr>
				</table>
				<table class='desc10' style='text-align:center;margin:0px 3px;' width='100%'>
					<tr>
						<td><?= "Ref.: <b>".$cuota['ctacte_id']."</b>" ?></td>
						<td>
							<?=$cuota['codbarra']?> <br>
							<barcode code="<?=$cuota['codbarra']?>" type="C128C" size="0.9"  />
						</td>
					</tr>
				</table>
			</div>
			<div class='desc' align='right'>Para la Municipalidad</div>
		</div>
	<?php
			}
		}
	?>
	<!-- Fin Cuotas -->

	<?php if ($i < count($emision)-1) { ?>
	<!-- Salto de Página --> <div style="PAGE-BREAK-AFTER: always"></div>
	<?php } ?>

<?php
} // fin bloque principal
?>
