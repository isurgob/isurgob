<?php
use yii\helpers\Html;
use app\utils\db\utb;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

?>
<div class='divredon desc' style='margin-top:5px;height:150px;overflow:hidden;'>
	<div class='cond' style='padding:0px 5px'><?= (isset($tituloseccion) ? $tituloseccion : '') ?></div>
	<hr style="color: #000; margin:1px" />
	<!-- Plan Período -->
	<div id='DivPlanSub3' class='desc8' style='padding:0px 5px;display:<?= ($emision[$i]['trib_id']==1 ? 'block' : 'none')?>'>
		<?php 
			if ($emision[$i]['trib_id']==1) echo nl2br(substr($sub3[0]['uf_plan_periodos'],0,903)); 
		?>
	</div>
	<!-- Titulares -->
	<div id='DivTitulares' style='padding:0px 5px;display:<?= ((in_array($emision[$i]['trib_id'],[1,4,23]) or in_array($emision[$i]['trib_tipo'],[3]) or ($emision[$i]['tobj']==1 and $emision[$i]['trib_tipo']==5)) ? 'none' : 'block')?>'>
		<?php
		
			echo GridView::widget([
					'id' => 'GrillaTit',
					'dataProvider' => new ArrayDataProvider(['allModels' => $sub3]),
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard4'],
					'showFooter' => true,
					'columns' => [
								 	['attribute'=>'num','header' =>'Código'],
									['attribute'=>'apenom','header' => 'Nombre','contentOptions'=>['style'=>'width:150px']],
									['attribute'=>'tvinc_nom','header' => 'Relac.'],
									['attribute'=>'porc','header' => 'Porc'],
									['attribute'=>'est','header' => 'Est'],
									['attribute'=>'princ','header' => ''],
								 ],
    			]);
		?>
	</div>
	<!-- Rubros -->
	<div id='DivRubroSub3' style='padding:0px 5px;display:<?= (in_array($emision[$i]['trib_id'],[23]) ? 'block' : 'none')?>'>
		<?php
			echo GridView::widget([
					'id' => 'GrillaRubro',
					'dataProvider' => new ArrayDataProvider(['allModels' => $sub3]),
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard2'],
					'showFooter' => true,
					'columns' => [
								 	['attribute'=>'rubro_id','header' => 'Rubro'],
									['attribute'=>'rubro_nom','header' => 'Nombre','contentOptions'=>['style'=>'width:200px'],
										'value' => function ($data){
														return substr($data['rubro_nom'],0,40);
													}
									],
								 	['attribute'=>'base','header' => 'Base/Cant','contentOptions'=>['style'=>'text-align:right'],
										'value' => function ($data){
														if ($data['tcalculo'] != 4)
															return $data['base'];
														else
															return $data['cant'];
													}
									],
								 	['attribute'=>'minimo','header' => 'Mín.','contentOptions'=>['style'=>'text-align:right']],
									['attribute'=>'alicuota','header' => 'Ali.','contentOptions'=>['style'=>'text-align:right']],
									['attribute'=>'monto','header' => 'Monto','contentOptions'=>['style'=>'text-align:right']],
								 ],
    			]);
		?>
	</div>
	<!-- Mejoras -->
	<div id='DivMejSub3' style='padding:0px 5px;display:<?= (($emision[$i]['trib_id']==1 or $emision[$i]['trib_id']==3 or $emision[$i]['trib_tipo']==3 or $emision[$i]['trib_tipo']==4 or $emision[$i]['tobj']!=1) ? 'none' : 'block')?>'>
		<?php
		/*echo GridView::widget([
					'id' => 'GrillaMej',
					'dataProvider' => new ArrayDataProvider(['allModels' => $sub3]),
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard2'],
					'showFooter' => true,
					'columns' => [
								 	['attribute'=>'pol','header' => 'Pol.'],
								 	['attribute'=>'nivel','header' => 'Nivel'],
								 	['attribute'=>'tdest_nom','header' => 'Destino'],
								 	['attribute'=>'tobra_nom','header' => 'Obra'],
								 	['attribute'=>'est_nom','header' => 'Estado'],
								 	['attribute'=>'cat','header' => 'Cat.'],
								 	['attribute'=>'supcub','header' => 'Sup.Cub.','contentOptions'=>['style'=>'text-align:right']],
								 	['attribute'=>'supsemi','header' => 'Sup.Semi.','contentOptions'=>['style'=>'text-align:right']],
								 	['attribute'=>'anio','header' => 'Año','contentOptions'=>['style'=>'text-alig:center']],
								 ],
    			]);*/
		?>
	</div>
	<!-- Liquidacióm -->
	<div id='DivLiqSub3' style='padding:0px 5px;display:<?= (($emision[$i]['trib_tipo']!=3 and $emision[$i]['trib_tipo']!=4) ? 'none' : 'block')?>'>
		<?php
		echo GridView::widget([
					'id' => 'GrillaLiq',
					'dataProvider' => new ArrayDataProvider(['allModels' => $sub3]),
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'summaryOptions' => ['style' => 'display:none'],
					'tableOptions' => ['class' => 'GrillaHeard4'],
					'showFooter' => true,
					'columns' => [
								 	['attribute'=>'item_id','header' => 'Item'],
								 	['attribute'=>'item_nom','header' => 'Detalle','contentOptions'=>['style'=>'width:200px;']],
								 	['attribute'=>'tdest_nom','header' => 'Destino','contentOptions'=>['style'=>'width:150px;']],
								 	['attribute'=>'item_monto','header' => 'Monto','contentOptions'=>['style'=>'width:80px;text-alig:right']],
								 ],
    			]);
		?>
	</div>
</div>