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
    	$aniodesc = "N° Conv.";	
    }elseif ($emision[$i]['trib_id'] == 2) 
    {
    	$aniodesc = "N° Facilidad";
    } else {
    	$aniodesc = "Año";
    }
    
    $x = 0;
    $per = null;
    $per2 = null;
    for ($j=0; $j<count($sub1);$j++)
    {
    	if ($j < 30)
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
	
	$codbarra = '';
	if (strlen($emision[$i]['codbarra']) == 34) {  
   		$codbarra = substr($emision[$i]['codbarra'], 1, 32);
	}else {
	    if (strlen($emision[$i]['codbarra']) == 39) {  
	        $codbarra = substr($emision[$i]['codbarra'], 1, 37);
	    }else {
	        $codbarra = substr($emision[$i]['codbarra'], 1, strlen($emision[$i]['codbarra'])-2);
	    }
	}
?>
	<!-- Encabezado -->
	<div style='overflow: hidden;margin-bottom:5px'>	
		<div style='float:left;' width='20%'>
			<img src='<?= Yii::$app->request->baseUrl.'/images/logo_muni_grande.jpg'?>' border='0' width='150' />
		</div>
		<div style='float:left;margin-left:10px;' width='29%' class='desc'>
			<font class='tt14'><b><?= Yii::$app->param->muni_name ?></b></font><br><br>
			<b><?= $munioficina ?></b><br>
			<?= Yii::$app->param->muni_domi ?><br>
			<?= 'Tel.: '.Yii::$app->param->muni_tel ?><br>
			<?= Yii::$app->param->muni_mail ?><br>
		</div>
		<div class='divredon cond' height='60px' width='46%' style='float:right;margin-left:10px;padding:10px;'>
			<?= $emision[$i]['mensaje'] ?>
		</div>
	</div>
	<!-- Fin Encabezado -->
	
	<!-- Datos Cuerpo -->
	<div style='overflow: hidden;'>
		<!-- Datos Emi -->
		<div style='float:left;width:49%'>
			<div class='divredon desc' height='100px'>
				<div class='tt18' style='padding:0px 5px;text-align:center'><b><?= $emision[$i]['trib_nom'] ?></b></div>
				<table class='desc10' width='98%' align='center'><tr><td>
					<?= "Ref.: <b>".$emision[$i]['ctacte_id'].'</b>' ?><br>
					<?= "Fecha Emisión: ".$emision[$i]['fchemi'] ?><br>
					<?= "Fecha Impresión: ".date('d/m/Y') ?>
					</td>
					<td align='right'>
					<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
						<tr bgcolor='#ccc'><td><?= $aniodesc ?></td></tr>
						<tr><td class='tt18' style='padding:2px 5px'><b><?=$emision[$i]['anio']?></b></td></tr>
					</table>
				</td></tr></table>	
			</div>
		</div>
		<!-- Vencimientos -->
		<div style='float:right;width:49%;'>
			<div class='divredon cond' style='padding:0px 5px;' height='100px'>
				<br>VENCIMIENTOS:<br><br>
				<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;' width='100%'>
					<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
					<tr>
						<td style='border-right:1px solid #ccc;border-collapse:collapse;'><b>Venc.&nbsp;<?= $emision[$i]['venc1'] ?></b></td>
						<td><b><?= $emision[$i]['monto'] ?></b></td>
					</tr>
				</table>
			</div>
		</div>
		<div class='divredon desc' style='overflow:hidden;width:100%;margin-top:5px' height='650px'>
			<div style='float:left;width:48%;padding:5px'>
				<?php echo GridView::widget([
					'id' => 'GrillaPer',
					'dataProvider' => $per,
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard2','style'=>'font-size:12px;'],
					'showFooter' => ($per2 == null ? true : false),
					'columns' => [
								 	['attribute'=>'recibo','header' => 'Recibo'],
								 	['attribute'=>'fecha','header' => 'Fecha','contentOptions'=>['style'=>'width:70px;text-align:center']],
								 	['attribute'=>'acta','header' => 'Acta','contentOptions'=>['style'=>'width:50px;text-align:center']],
								 	['attribute'=>'area_nom','header' => 'Area','contentOptions'=>['style'=>'width:150px;text-align:left']],
								 	['attribute'=>'monto','header' => 'Monto','contentOptions'=>['style'=>'width:70px;text-align:right']],
								 									 	
								 ],
    			]); ?>
			</div>
			<div style='float:right;width:48%;padding:5px'>
			<?php 
			if ($per2 != null)
			{
				echo GridView::widget([
					'id' => 'GrillaPer2',
					'dataProvider' => $per2,
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard2'],
					'showFooter' => ($per2 != null ? true : false),
					'columns' => [
								 	['attribute'=>'recibo','header' => 'Recibo'],
								 	['attribute'=>'fecha','header' => 'Fecha','contentOptions'=>['style'=>'width:70px;text-align:center']],
								 	['attribute'=>'acta','header' => 'Acta','contentOptions'=>['style'=>'width:50px;text-align:center']],
								 	['attribute'=>'area_nom','header' => 'Area','contentOptions'=>['style'=>'width:150px;text-align:left']],
								 	['attribute'=>'monto','header' => 'Monto','contentOptions'=>['style'=>'width:70px;text-align:right']],
								 	
								 ],
    			]); 
			}
    		?>
			</div>
		</div>
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
			<div class='divredon desc' style='margin-top:5px;height:75px'>
				<div class='cond' style='padding:0px 5px;text-align:center'><b><?= $emision[$i]['trib_nom'] ?></b></div>
				<table class='desc' width='100%'><tr><td>
					<?= "Ref.: <b>".$emision[$i]['ctacte_id'].'</b>' ?><br>
					<?= "Fecha Emisión: ".$emision[$i]['fchemi'] ?><br>
					<?= "Fecha Impresión: ".date('d/m/Y') ?>
					</td>
					<td>
					<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
						<tr bgcolor='#ccc'><td><?= $aniodesc ?></td></tr>
						<tr><td class='tt18' style='padding:2px 5px'><b><?=$emision[$i]['anio']?></b></td></tr>
					</table>
				</td></tr></table>	
			</div>
			<div class='divredon cond' style='padding:0px 5px;margin-top:5px;height:70px'>
				VENCIMIENTOS:
				<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;' width='100%'>
					<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
					<tr><td style='border-rigth:1px solid #ccc;border-collapse:collapse;'><b>Venc.&nbsp;</b><?= $emision[$i]['venc1'] ?></td><td><b><?= $emision[$i]['monto'] ?></b></td></tr>
				</table>
			</div>
			<div align='right' style='margin-top:42px'> Para el Ente Recaudador</div>
		</div>
		<div style='float:right;width:69%;margin-left:5px;border-left:1px dashed #000;'>
			<div class='tt14' style='text-align:center'><?= Yii::$app->param->muni_name ?></div>
			<div class='divredon desc' width='100%' style='margin:5px;float:left;width:55%;height:75px'>
				<div class='cond' style='padding:0px 5px;text-align:center'><b><?= $emision[$i]['trib_nom'] ?></b></div>
				<table class='desc' width='100%'><tr><td>
					<?= "Ref.: <b>".$emision[$i]['ctacte_id'].'</b>' ?><br>
					<?= "Fecha Emisión: ".$emision[$i]['fchemi'] ?><br>
					<?= "Fecha Impresión: ".date('d/m/Y') ?>
					</td>
					<td align='right'>
					<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;'>
						<tr bgcolor='#ccc'><td><?= $aniodesc ?></td></tr>
						<tr><td class='tt18' style='padding:2px 5px'><b><?=$emision[$i]['anio']?></b></td></tr>
					</table>
				</td></tr></table>	
			</div>
			<div class='divredon desc' style='padding:0px 5px;float:right;width:40%;height:75px'>
				VENCIMIENTOS:
				<table class='cond' style='text-align:center;border:1px solid #ccc;border-collapse:collapse;' width='100%'>
					<tr bgcolor='#ddd'><td>Vencimiento</td><td>Importe</td></tr>
					<tr><td style='border-right:1px solid #ccc;border-collapse:collapse;'><b>Venc.&nbsp;</b><?= $emision[$i]['venc1'] ?></td><td><b><?= $emision[$i]['monto'] ?></b></td></tr>
				</table>
			</div>
			<div style='overflow: hidden; width:100%;margin-left:5px;height:110px'>
				<table class='desc' width='100%'>
				<tr><td align='left'></td>
					<td align='center'><?=$codbarra?></td>
				</tr>
				<tr>
					<td align='left'>Cuota:</td>
					<td align='center'><barcode code="<?=$codbarra?>" type="C128C" /></td>
				</tr>
				</table>
			</div>
			<table width='100%' class='desc'><tr><td><?= Yii::$app->user->id . ($mostrar_nom_usr ? " - " . utb::getCampo('sam.sis_usuario', "usr_id = " . Yii::$app->user->id, 'apenom') : '') ?></td><td align='right'>Para la Municipalidad</td></tr></table>
		</div>
	</div>
	<!-- Fin Talón -->

	<?php if ($i < count($emision)-2) { ?>
	<!-- Salto de Página --> <div style="PAGE-BREAK-AFTER: always"></div> 
	<?php } ?>

<?php
} // fin bloque principal
?>