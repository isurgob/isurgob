<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;
 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

use app\utils\db\Fecha;

$total = 0;
for ($i=0;$i<count($provider);$i++)
{
	$total += $provider[$i]['saldo'];
}

echo "<div class='body'>";
echo "<p class='tt'>".$titulo."</p>";
echo "<p class='cond'><u><b>Condici&oacute;n:</b></u>&nbsp;".$condicion."</p>";

Yii::$app->formatter->thousandSeparator= '';
?>
<div class='divredon desc'>

	<?php
	if($tobj == 1){
	?>
	<!-- Inmueble -->
	<div style='padding:5px;display:<?= ($tobj==1 ? 'block' : 'none')?>'>
		<table class='desc'><tr><td>
			<table border='0' class='divredon desc' style='text-align:center'>
				<tr><td>Part.Prov</td><td>Plano</td><td>Nomenclatura</td><td>Manzana</td><td>Parcela</td></tr>
				<tr><td colspan='5'><hr style="color: #000; margin:1px" /></td></tr>
				<tr><td><?=$subreporte['parp']?></td><td><?=$subreporte['plano']?></td><td><?=$subreporte['s1'].' '.$subreporte['s2'].' '.$subreporte['s3']?></td><td><?=$subreporte['manz']?></td><td><?=$subreporte['parc']?></td></tr>
			</table>
			</td><td>
			<table class='desc'>
				<tr><td><b>ZonaT:</b> </td><td><?=$subreporte['zonat_nom']?></td><td><b>Superficie Terreno:</b> </td><td><?= Yii::$app->formatter->asDecimal($subreporte['supt'], 4, []);?></td></tr>
				<tr><td><b>Zona OP :</b> </td><td colspan='2'><?=$subreporte['zonaop_nom']?></td></tr>
			</table>
		</td></tr></table>
	</div>
	<?php
	}
	?>
	
	<?php
	if($tobj == 2){
	?>
	<!-- Comercio -->
	<div style='padding:5px;display:<?= ($tobj==2 ? 'block' : 'none')?>'>
		<table class='desc'><tr><td>
			<table border='0' class='divredon desc' style='text-align:center'>
				<tr><td>Cond.IVA</td><td width="100px">CUIT</td><td>Ing.Brutos</td></tr>
				<tr><td colspan='5'><hr style="color: #000; margin:1px" /></td></tr>
				<tr><td><?=$subreporte['iva_nom']?></td><td><?=$subreporte['cuit']?></td><td><?=$subreporte['ib']?></td></tr>
			</table>
			</td><td>
			<table class='desc'>
				<tr><td><b>Habilitaci&oacute;n: </b></td><td><?= array_key_exists('fchhab', $subreporte) ? Fecha::BDToUsuario($subreporte['fchhab']) : null; ?></td></tr>
				<tr><td><b>Legajo : </b></td><td><?=$subreporte['legajo']?></td></tr>
			</table>
		</td></tr></table>
	</div>
	<?php
	}
	?>
	
	<?php
	if($tobj == 3){
	?>
	<!-- Persona -->
	<div style='padding:5px;display:<?= ($tobj==3 ? 'block' : 'none')?>'>
		<table class='desc'><tr><td>
			<table border='0' class='divredon desc' style='text-align:center'>
				<tr><td>Tipo de Documento y Número </td><td>Cond.IVA</td><td>CUIT/CUIL</td></tr>
				<tr><td colspan='5'><hr style="color: #000; margin:1px" /></td></tr>
				<tr><td><?=$subreporte['tdoc_nom'].' '.$subreporte['ndoc']?></td><td><?=$subreporte['iva_nom']?></td><td><?=$subreporte['cuit']?></td></tr>
			</table>
			</td><td>
			<table class='desc'>
				<tr><td><b>Nacionalidad: </b></td><td><?=$subreporte['nacionalidad_nom']?></td></tr>
				<tr><td><b>Domicilio Leg: </b></td><td><?=$subreporte['domleg_dir']?></td></tr>
			</table>
		</td></tr></table>
	</div>
	<?php
	}
	?>
	
	<?php
	if($tobj == 4){
	?>
	<!-- Cementerio -->
	<div style='padding:5px;display:<?= ($tobj==4 ? 'block' : 'none')?>'>
		<table class='desc'><tr><td>
			<table border='0' class='divredon desc' style='text-align:center'>
				<tr><td>Tipo </td><td>Cuadro</td><td>Cuerpo</td><td>Piso</td><td>Fila</td><td>Nume</td></tr>
				<tr><td colspan='6'><hr style="color: #000; margin:1px" /></td></tr>
				<tr><td><?=$subreporte['tipo_nom']?></td><td><?=$subreporte['cuadro_id']?></td><td><?=$subreporte['cuerpo_id']?></td><td><?=$subreporte['piso']?></td><td><?=$subreporte['fila']?></td><td><?=$subreporte['nume']?></td></tr>
			</table>
			</td><td>
			<table class='desc'>
				<tr><td><b>Fecha Venc: </b></td><td><?=$subreporte['fchvenc']?></td></tr>
				<tr><td><b>Cód. Anterior: </b></td><td><?=$subreporte['cod_ant']?></td></tr>
			</table>
		</td></tr></table>
	</div>
	<?php
	}
	?>
	
	<?php
	if($tobj == 5){
	?>
	<!-- Rodado -->
	<div style='padding:5px;display:<?= ($tobj==5 ? 'block' : 'none')?>'>
		<table class='desc'>
			<tr><td><b>Categoría: </b></td><td><?=$subreporte['cat_nom']?></td><td><b>Marca: </b></td><td><?=$subreporte['marca_nom']?></td></tr>
			<tr><td><b>Tipo: </b></td><td><?=$subreporte['tipo_nom']?></td><td><b>Modelo: </b></td><td><?=$subreporte['modelo_nom']?></td></tr>
			<tr><td><b>Dominio: </b></td><td><?=$subreporte['dominio']?>&nbsp;&nbsp;<b>Año:</b> <?=$subreporte['anio']?></td><td><b>Compra: </b></td><td><?=$subreporte['fchcompra']?></td></tr>
		</table>
	</div>
	<?php
	}
	?>
</div>
<?php
$provider = new ArrayDataProvider(['allModels' => $provider]);
$provider->setPagination(false);

echo GridView::widget([
		'id' => 'GrillaReporteList',
		'dataProvider' => $provider,
		'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
		'summaryOptions' => ['style' => 'display:none'],
		'tableOptions' => ['class' => 'GrillaHeard3'],
		'columns' => [

					['attribute'=>'tobj_nom','contentOptions'=>['style'=>'width:30px;'],'header' => 'Tipo'],
					['attribute'=>'obj_id','contentOptions'=>['style'=>'width:60px;'],'header' => 'Objeto'],
            		['attribute'=>'subcta','contentOptions'=>['style'=>'width:20px; text-align:center'],'header' => 'SCta'],
            		['attribute'=>'obj_dato','contentOptions'=>['style'=>'width:90px'],'header' => 'Dato'],
            		['attribute'=>'trib_nom','contentOptions'=>['style'=>'width:150px'],'header' => 'Tributo'],
            		['attribute'=>'saldo','contentOptions'=>['style'=>'width:120px;text-align:right'],'header' => 'Saldo', 'footer' => '<b>Total: '.$total.'</b>','footerOptions'=>['style'=>'border-top:1px solid;text-align:right']],
            		['attribute'=>'plan_id','contentOptions'=>['align'=>'center','style'=>'width:50px; text-align:center'],'header' => 'N°Conv.'],
            		['attribute'=>'judi','contentOptions'=>['align'=>'center','style'=>'width:30px; text-align:center'],'header' => 'Judi'],
            		['attribute'=>'falta_dj','contentOptions'=>['align'=>'center','style'=>'width:30px; text-align:center'],'header' => 'FaltaDJ'],
            		['attribute'=>'conv','contentOptions'=>['align'=>'center','style'=>'width:30px; text-align:center'],'header' => 'Conv'],
            		
    			],
		'showFooter' => true,
    ]);


 
echo "</div>";
?>
 	
