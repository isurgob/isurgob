<?php
use yii\grid\GridView;

	echo GridView::widget([
		'dataProvider' => $dataProviderLiquidacion,
		'id' => 'GrillaLiq',
		'summary' => false,
		'headerRowOptions' => ['class' => 'grilla'],
		'columns' => [

				['attribute'=>'item_id','contentOptions'=>['style'=>'width:40px;','class' => 'grilla'],'header' => 'Cod'],
				['attribute'=>'item_nom','contentOptions'=>['style'=>'width:150px;','class' => 'grilla'],'header' => 'Item'],
           		['attribute'=>'item_monto', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:60px; text-align:right','class' => 'grilla'],'header' => 'Monto'],
           		['attribute'=>'detalle','contentOptions'=>['style'=>'width:200px;','class' => 'grilla'],'header' => 'Detalle'],
    		]
    		
    	]); 
    		
?>    		