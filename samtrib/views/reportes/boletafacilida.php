<?php
use yii\helpers\Html;
use app\utils\db\utb;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;

$mostrar_nom_usr = utb::getCampo('sam.config', "1=1", 'repo_usu_nom');

for ($i=0; $i<count($emision);$i++) // inicio bloque principal
{
	if ($emision[$i]['trib_id'] != 9) $munioficina = "DIRECCION DE INGRESOS PUBLICOS";
	
	$nominal = 0;
	$accesor = 0;
	$quita = 0;
	$multa = 0;
	$total = 0;
	
	switch ($emision[0]['tobj']) {
    	case 1: 
    		$objdato = "Nomenclatura: ";
    		break;
    	case 2: 
    		$objdato = "CUIT: ";
    		break;
    	case 3: 
    		$objdato = "Documento: ";
    		break;
    	case 4: 
    		$objdato = "Nomenclatura: ";
    		break;
    	case 5: 
    		$objdato = "Dominio: ";
    		break;
    	default: 
    		$objdato = "";
    }
    
    if ($emision[$i]['trib_id'] == 1)
    {
    	$aniodesc = "N°Conv.";	
    }elseif ($emision[$i]['trib_id'] == 2) 
    {
    	$aniodesc = "N°Conv.";
    } else {
    	$aniodesc = "N° Facilidad";
    }
    
    $x = 0;
    $per = null;
    $per2 = null;
	$mitadper = 48; //floor(count($sub1)/2) > 48 ? 48 : floor(count($sub1)/2);
    for ($j=0; $j<count($sub1);$j++)
    {
    	$nominal += $sub1[$j]['nominal'];
		$accesor += $sub1[$j]['accesor'];
		$quita += $sub1[$j]['quita'];
		$multa += $sub1[$j]['multa'];
		$total += $sub1[$j]['total'];
		
    	if ($j < $mitadper) // 26
    	{
    		$per[$j] = $sub1[$j];
    	}else {
    		$per2[$x] = $sub1[$j];
    		$x +=1;
    	}
    }
	$per = new ArrayDataProvider(['allModels' => $per]);
	$per->setPagination(false);
	if ($per2 != null)
	{
		$per2 = new ArrayDataProvider(['allModels' => $per2]);
		$per2->setPagination(false);
	} 
?>
	<!-- Encabezado -->
	<div style='overflow: hidden;margin-bottom:5px'>	
		<div style='float:left;' width='20%'>
			<img src='<?= Yii::$app->param->logo_grande ?>' border='0' width='150' />
		</div>
		<div style='float:left;margin-left:10px;' width='30%' class='desc'>
			<font class='tt14'><b><?= Yii::$app->param->muni_name ?></b></font><br>
			<b><?= $munioficina ?></b><br>
			<?= Yii::$app->param->muni_domi ?><br>
			<?= 'Tel.: '.Yii::$app->param->muni_tel ?><br>
			<?= Yii::$app->param->muni_mail ?><br>
		</div>
		<div class='divredon desc' width='48%' style='float:right;margin-left:10px'>
			<table border='0' style='padding:0px 5px' class='desc'>
				<tr><td>Objeto:&nbsp;</td><td><b><?=$emision[$i]['obj_id']?></b></td></tr>
				<tr><td>Titular:&nbsp;</td><td><b><?=$emision[$i]['num']." - ".$emision[$i]['num_nom']?></b></td></tr>
				<tr><td>Domicilio:&nbsp;</td><td><b><?=$emision[$i]['domi']?></b></td></tr>
				<tr><td>Localidad:&nbsp;</td><td><?=$emision[$i]['codpos']?></td></tr>
				<tr><td colspan='2'><?=$emision[$i]['domi_det']?></td></tr>
			</table>
			<hr style="color: #000; margin:1px" />
			<div style='padding:0px 5px'><b><?= $objdato.'&nbsp;'.$emision[$i]['obj_dato'] ?></b></div>
		</div>
	</div>
	<!-- Fin Encabezado -->
	
	<!-- Datos Cuerpo -->
	<div style='overflow: hidden;'>
		<!-- Datos Emi -->
		<div style='float:left;width:49%'>
			<div class='divredon desc' height='85px'>
				<div class='tt14' style='padding:0px 5px;text-align:center'><b><?= $emision[$i]['trib_nom'] ?></b></div>
				<div class='tt14' style='padding:0px 5px;text-align:center'><b>Facilidad</b></div>
				<table class='desc' width='100%'><tr><td>
					<?= "Fecha Emisión: ".$emision[$i]['fchalta'] ?><br>
					<?= "Fecha Impresión: ".date('d/m/Y') ?>
					</td>
					<td>
					<table class='cond' style='text-align:center;'>
						<tr bgcolor='#ccc'><td><?= $aniodesc ?></td></tr>
						<tr><td class='tt14' style='padding:2px 5px'><b><?=$emision[$i]['faci_id']?></b></td></tr>
					</table>
				</td></tr></table>	
			</div>
		</div>
		<!-- Vencimientos -->
		<div style='float:right;width:49%;'>
			<div class='divredon cond' style='padding:0px 5px;' height='85px'>
				<br>VENCIMIENTOS:<br><br>
				<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;' width='100%'>
					<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
					<tr><td style='border-right:1px solid #ccc;border-collapse:collapse;'><b>Venc.&nbsp;</b><?= $emision[$i]['fchvenc'] ?></td><td><b><?= $emision[$i]['monto'] ?></b></td></tr>
				</table>
			</div>
		</div>
		<div class='divredon desc' style='overflow:hidden;width:100%;margin-top:5px' height='440px'>
			<div style='float:left;width:47%;padding:5px'>
				<?php echo GridView::widget([
					'id' => 'GrillaPer',
					'dataProvider' => $per,
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard6'],
					'showFooter' => ($per2 == null ? true : false),
					'columns' => [
								 	['attribute'=>'periodo','header' => 'Periodo'],
								 	['attribute'=>'nominal','header' => 'Nominal','contentOptions'=>['style'=>'width:70px;text-align:right'],'footer'=>number_format($nominal,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'accesor','header' => 'Accesor','contentOptions'=>['style'=>'width:70px;text-align:right'],'footer'=>number_format($accesor,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'quita','header' => 'Quita','contentOptions'=>['style'=>'width:60px;text-align:right'],'footer'=>number_format($quita,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'multa','header' => 'Multa','contentOptions'=>['style'=>'width:60px;text-align:right'],'footer'=>number_format($multa,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'width:70px;text-align:right'],'footer'=>number_format($total,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'fchvenc','header' => 'Venc.','contentOptions'=>['style'=>'width:40px;text-align:center']],
								 	
								 ],
    			]); ?>
			</div>
			<div style='float:right;width:49%;padding:5px'>
			<?php 
			if ($per2 != null)
			{
				echo GridView::widget([
					'id' => 'GrillaPer2',
					'dataProvider' => $per2,
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard6'],
					'showFooter' => ($per2 != null ? true : false),
					'columns' => [
								 	['attribute'=>'periodo','header' => 'Periodo'],
								 	['attribute'=>'nominal','header' => 'Nominal','contentOptions'=>['style'=>'width:70px;text-align:right'],'footer'=>number_format($nominal,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'accesor','header' => 'Accesor','contentOptions'=>['style'=>'width:70px;text-align:right'],'footer'=>number_format($accesor,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'quita','header' => 'Quita','contentOptions'=>['style'=>'width:60px;text-align:right'],'footer'=>number_format($quita,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'multa','header' => 'Multa','contentOptions'=>['style'=>'width:60px;text-align:right'],'footer'=>number_format($multa,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'width:70px;text-align:right'],'footer'=>number_format($total,2),'footerOptions'=>['style'=>'border-top:1px solid;text-align:right;font-weight:bold']],
								 	['attribute'=>'fchvenc','header' => 'Venc.','contentOptions'=>['style'=>'width:40px;text-align:center']],
								 	
								 ],
    			]); 
			}
    		?>
			</div>
		</div>
		<p class='desc'> <b><?= $emision[$i]['mensaje'] ?></b> </p>
		<div class='desc' style='float:left;width:49%;margin-top:5px'>
			<b>SAM</b> &nbsp;Sistema de Administración Municipal - Módulo Tributario Web
		</div>
		<div style='float:right;width:45%;margin-left:20px;'>
			<table width='100%' class='desc'><tr><td><?= Yii::$app->param->muni_name?></td><td align='right'>Para el Contributente</td></tr></table>
		</div>
	</div>
	<!-- Fin Cuerpo -->
	
	<!-- Talón -->
	<div style='overflow: hidden; margin-top:5px;border-top:1px dashed #000000;position:fixed;bottom:0'>
		<div class='desc' style='float:left;width:30%'>
			<div class='tt14' style='text-align:center'><?= Yii::$app->param->muni_name?></div>
			<div class='divredon cond' style='padding:5px; margin-top:5px;height:55px'>
				Objeto:&nbsp;<b><?= $emision[$i]['obj_id'] ?></b><br>
				Titular:<br><b><?= $emision[$i]['num']." - ".$emision[$i]['num_nom'] ?></b><br>
			</div>
			<div class='divredon desc' style='margin-top:5px'>
				<div class='cond' style='padding:0px 5px;text-align:center'><b><?= $emision[$i]['trib_nom'] ?></b></div>
				<table class='desc' width='100%'><tr><td>
					<?= "Fecha Emisión: ".$emision[$i]['fchalta'] ?><br>
					<?= "Fecha Impresión: ".date('d/m/Y') ?>
					</td>
					<td>
					<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
						<tr bgcolor='#ccc'><td><?= $aniodesc ?></td></tr>
						<tr><td class='tt18' style='padding:2px 5px'><b><?=$emision[$i]['faci_id']?></b></td></tr>
					</table>
				</td></tr></table>	
			</div>
			<div class='divredon cond' style='padding:0px 5px 5px 5px;margin-top:5px;height:auto'>
				VENCIMIENTOS:
				<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;' width='100%'>
					<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
					<tr><td style='border-rigth:1px solid #ccc;border-collapse:collapse;'><b>Venc.&nbsp;</b><?= $emision[$i]['fchvenc'] ?></td><td><b><?= $emision[$i]['monto'] ?></b></td></tr>
				</table>
			</div>
			<div align='right' style='margin-top:5px'> Para el Ente Recaudador</div>
		</div>
		<div style='float:right;width:69%;margin-left:5px;border-left:1px dashed #000;'>
			<div class='tt14' style='text-align:center'><?= Yii::$app->param->muni_name?></div>
			<div class='divredon desc' style='padding:5px; margin-top:5px; margin-left:5px;height:55px'>
				<table class='cond'><tr>
					<td>Objeto:&nbsp;<b><?= $emision[$i]['obj_id'] ?></b></td>
					<td>
					<table border='0' style='padding:0px 5px' class='cond'>
						<tr><td>Titular:&nbsp;</td><td><?=$emision[$i]['num']." - ".$emision[$i]['num_nom']?></td></tr>
						<tr><td>Domicilio:&nbsp;</td><td><b><?=$emision[$i]['domi']?></b></td></tr>
						<tr><td>Localidad:&nbsp;</td><td><b><?=$emision[$i]['codpos']?></b></td></tr>
					</table>
					</td>
				</tr></table>
			</div>
			<div class='divredon desc' width='100%' style='margin:5px;float:left;width:55%;height:70px'>
				<div class='cond' style='padding:0px 5px;text-align:center'><b><?= $emision[$i]['trib_nom'] ?></b></div>
				<table class='desc' width='100%'><tr><td>
					<?= "Fecha Emisión: ".$emision[$i]['fchalta'] ?><br>
					<?= "Fecha Impresión: ".date('d/m/Y') ?>
					</td>
					<td align='right'>
					<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
						<tr bgcolor='#ccc'><td><?= $aniodesc ?></td></tr>
						<tr><td class='tt18' style='padding:2px 5px'><b><?=$emision[$i]['faci_id']?></b></td></tr>
					</table>
				</td></tr></table>	
			</div>
			<div class='divredon desc' style='padding:0px 5px;float:right;width:40%;height:70px'>
				VENCIMIENTOS:
				<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;' width='100%'>
					<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
					<tr><td style='border-right:1px solid #ccc;border-collapse:collapse;'><b>Venc.&nbsp;</b><?= $emision[$i]['fchvenc'] ?></td><td><b><?= $emision[$i]['monto'] ?></b></td></tr>
				</table>
			</div>
			<div style='overflow: hidden; width:100%;margin-left:5px;height:auto'>
				<table class='desc' width='100%'>
				<tr><td align='left'></td>
					<td align='center'><?=$emision[$i]['codbarra']?></td>
				</tr>
				<tr>
					<td align='left'>Cuota:</td>
					<td align='center'><barcode code="<?=$emision[$i]['codbarra']?>" type="C128C" /></td>
				</tr>
				</table>
			</div>
			<table width='100%' style="margin-top:15px" class='desc'><tr><td><?= Yii::$app->user->id . ($mostrar_nom_usr ? " - " . utb::getCampo('sam.sis_usuario', "usr_id = " . Yii::$app->user->id, 'apenom') : '') ?></td><td align='right'>Para la Municipalidad</td></tr></table>
		</div>
	</div>
	<!-- Fin Talón -->

	<?php if ($i < count($emision)-2) { ?>
	<!-- Salto de Página --> <div style="PAGE-BREAK-AFTER: always"></div> 
	<?php } ?>

<?php
} // fin bloque principal
?>