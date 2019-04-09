<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\web\Session;
use yii\data\ArrayDataProvider;



$dp = null;
$cargarTributos = false;

if(isset($dpTributos))
	$dp = $dpTributos;
else{
	if(isset($modelObjeto))
		$dp = $modelObjeto->getDPTributos();
	else if(isset($id))
		$cargarTributos = true;
}

?>

<?php
Pjax::begin(['id' => 'objetoTributos', 'enablePushState' => false, 'enableReplaceState' => false]);
?>
<table border="0" width="600px">
	<tr>
		<td align="left" width="600px">
			<?php
			
				if($dp == null) $dp = new ArrayDataProvider(['allModels' => []]);
				
				
				echo GridView::widget([
					'dataProvider' => $dp,
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'columns' => [
					
						['attribute' => 'trib_nom_redu', 'label' => 'Tributo'],
						['attribute' => 'per_desde', 'label' => 'Desde'],
						['attribute' => 'per_hasta', 'label' => 'Hasta'],
						['attribute' => 'cat_nom', 'label' => 'Categoría'],
						['attribute' => 'fchalta', 'label' => 'F. Alta', 'format' => ['date', 'php:d/m/Y']],
						['attribute' => 'base', 'label' => 'Base'],
						['attribute' => 'cant', 'label' => 'Cant.'],
						['attribute' => 'sup', 'label' => 'Sup.'],
						
						['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:30px; margin:1px 5px'],'template' => '{printcred} {printconst}' ,
							'buttons'=>[
								'printcred' => function($url,$model,$key)
											{
												return Html::a('<span class="glyphicon glyphicon-print"></span>',['//ctacte/tribacc/credhabil','obj_id' => $model['obj_id'], 'trib_id' => $model['trib_id'], 'perdesde' => $model['perdesde']],
														['style' => 'font-size:8px','target' => '_black', 'data-pjax' => 0,'title' => 'Imprimir Credencial']
													);
												
											},
								'printconst' => function($url,$model,$key)
											{
												return Html::a('<span class="glyphicon glyphicon-print"></span>',['//ctacte/tribacc/constancia','obj_id' => $model['obj_id'], 'trib_id' => $model['trib_id'], 'perdesde' => $model['perdesde']],
														['style' => 'font-size:8px','target' => '_black', 'data-pjax' => 0,'title' => 'Imprimir Constancia']
												);
											}
								]
								
						 ],
					],
				]);
			?>
		</td>
	</tr>
</table>
<?php
Pjax::end();
?>