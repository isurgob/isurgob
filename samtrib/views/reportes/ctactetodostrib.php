<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;
 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

if (!isset($tipo)) $tipo='';

echo "<div class='body'>";
echo "<p class='tt'>".$titulo."</p>";
echo "<p class='cond'><u><b>Condici&oacute;n:</b></u>&nbsp;".$condicion."</p>";

$totalnom = 0;
$totalnomcub = 0;
$totalmulta = 0;
$totalacc = 0;
$totalpag = 0;
$totalsaldo = 0;

$subtotalnom = 0;
$subtotalnomcub = 0;
$subtotalmulta = 0;
$subtotalacc = 0;
$subtotalpag = 0;
$subtotalsaldo = 0;

$cantC = 0;
$cantJ = 0;
$cantX = 0;

$trib = 0;
$j=0;
$obj = '';

$cantidadRegistros= count($provider);

for ($i = 0; $i < $cantidadRegistros; $i++)
{
	$subtotalnom += $provider[$i]['nominal'];
	$subtotalnomcub += $provider[$i]['nominalcub'];
	$subtotalmulta += $provider[$i]['multa'];
	$subtotalacc += $provider[$i]['accesor'];
	$subtotalpag += $provider[$i]['pagado'];
	$subtotalsaldo += $provider[$i]['saldo'];
	
	$totalnom += $provider[$i]['nominal'];
	$totalnomcub += $provider[$i]['nominalcub'];
	$totalmulta += $provider[$i]['multa'];
	$totalacc += $provider[$i]['accesor'];
	$totalpag += $provider[$i]['pagado'];
	$totalsaldo += $provider[$i]['saldo'];
	
	switch($provider[$i]['est']){
		
		case 'C': $cantC++; break;
		case 'J': $cantJ++; break;
		case 'D':
		case 'X': $cantX++; break;
	}
	
	$trib= $provider[$i]['trib_id'];
	$obj= $provider[$i]['obj_id'];
	
	$datos[$j] = $provider[$i];
	$j++;
	
	if ($cantidadRegistros > $i && ($obj != $provider[$i+1]['obj_id'] || $trib != $provider[$i+1]['trib_id'])){
		
		$datos2 = new ArrayDataProvider(['allModels' => $datos]);
		$datos2->setPagination(false);
		$j=0;
		$datos = null;
		
		echo "<p class='cond' style='padding:10px 0px -50px 0px;border-top:1px solid'><b>Tributo:&nbsp;</b>".$provider[$i]['trib_nom']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Objeto:&nbsp;</b>".$provider[$i]['obj_id']."</p>";
		
		echo GridView::widget([
			'id' => 'GrillaReporteList',
			'dataProvider' => $datos2,
			'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
			'summaryOptions' => ['style' => 'display:none'],
			'tableOptions' => ['class' => 'GrillaHeard3'],
			'columns' => [

				['attribute'=>'anio','label' => 'Año','contentOptions'=>['style'=>'width:40px;text-align:center']],
    			['attribute'=>'cuota','label' => 'Cuota','contentOptions'=>['style'=>'width:40px;text-align:center']],
    			['attribute'=>'est','label' => 'Est','contentOptions'=>['style'=>'width:40px;text-align:center']],
    			['attribute'=>'nominal', 'format' => ['decimal', 2],'label' => 'Nominal','contentOptions'=>['style'=>'width:80px;text-align:right']],
    			['attribute'=>'nominalcub', 'format' => ['decimal', 2],'label' => 'Nominal Cub.','contentOptions'=>['style'=>'width:80px;text-align:right']],
    			['attribute'=>'multa','label' => 'Multa','contentOptions'=>['style'=>'width:80px;text-align:right']],
    			['attribute'=>'accesor', 'format' => ['decimal', 2], 'label' => 'Interés','contentOptions'=>['style'=>'width:80px;text-align:right']],
    			['attribute'=>'pagado', 'format' => ['decimal', 2],'label' => 'Pagado','contentOptions'=>['style'=>'width:80px;text-align:right']],
    			['attribute'=>'saldo', 'format' => ['decimal', 2], 'label' => 'Saldo','contentOptions'=>['style'=>'width:80px;text-align:right']],
    			['attribute'=>'fchemi', 'format' => ['date', 'php:d/m/Y'],'label' => 'Fch.Emi','contentOptions'=>['style'=>'width:70px;']],
    			['attribute'=>'fchvenc', 'format' => ['date', 'php:d/m/Y'],'label' => 'Fch.Venc','contentOptions'=>['style'=>'width:70px;']],
    			['attribute'=>'fchpago', 'format' => ['date', 'php:d/m/Y'],'label' => ($tipo=='impagos' ? '' : 'Fch.Pago'),'contentOptions'=>['style'=>'width:70px']],
    		
    			],
		]);
		
 
?>
<table class='desc'>
	<tr>
		<td width='40px'></td><td width='40px'></td><td width='40px'></td>
		<td width='80px' class='border_top_solid' align='right' style='padding:0px 4px'><b><?= Yii::$app->formatter->asDecimal($subtotalnom, 2); ?></b></td>
		<td width='80px' class='border_top_solid' align='right' style='padding:0px 4px'><b><?= Yii::$app->formatter->asDecimal($subtotalnomcub, 2); ?></b></td>
		<td width='80px' class='border_top_solid' align='right' style='padding:0px 4px'><b><?= Yii::$app->formatter->asDecimal($subtotalmulta, 2); ?></b></td>
		<td width='80px' class='border_top_solid' align='right' style='padding:0px 4px'><b><?= Yii::$app->formatter->asDecimal($subtotalacc, 2); ?></b></td>
		<td width='80px' class='border_top_solid' align='right' style='padding:0px 4px'><b><?= Yii::$app->formatter->asDecimal($subtotalpag, 2); ?></b></td>
		<td width='80px' class='border_top_solid' align='right' style='padding:0px 4px'><b><?= Yii::$app->formatter->asDecimal($subtotalsaldo, 2); ?></b></td>
		<td width='70px'></td><td width='70px'></td><td width='70px'></td>
	</tr>
</table>
<table class='divredon desc' style='margin-top:20px;padding:5px'><tr>
	<td><b>Totales de Períodos según Estado:</b>&nbsp;</td><td><i>Convenio:</i></td><td><b><?=$cantC?></b></td>
	<td><i>Juicio:</i></td><td><b><?=$cantJ?></b></td><td><i><?= ($tipo=='impagos' ? 'Deuda:' : 'Sin Emisión / DJ:') ?></i></td><td><b><?=$cantX?></b></td>
</tr></table>
<?php    		
    		
		$subtotalnom = 0;
		$subtotalnomcub = 0;
		$subtotalmulta = 0;
		$subtotalacc = 0;
		$subtotalpag = 0;
		$subtotalsaldo = 0;
		$cantC = 0;
		$cantJ = 0;
		$cantX = 0;
	}


}
?>
<table class='divredon desc' style='margin-top:50px;padding:5px'><tr>
	<td><b>Totales:</b>&nbsp;</td><td><i>Nominal:</i></td><td><b><?=$totalnom?></b></td><td><i>Nominal Cub.:</i></td><td><b><?=$totalnomcub?></b></td><td><i>Multa:</i></td><td><b><?=$totalmulta?></b></td>
	<td><i>Accesor:</i></td><td><b><?=$totalacc?></b></td><td><i>Pagado:</i></td><td><b><?=$totalpag?></b></td><td><i>Saldo:</i></td><td><b><?=$totalsaldo?></b></td>
</tr></table>
<?php   
echo "</div>";
?>
 	
