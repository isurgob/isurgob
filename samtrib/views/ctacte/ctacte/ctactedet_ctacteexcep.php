<?php
use yii\grid\GridView;

	echo GridView::widget([
		'dataProvider' => $dataProviderCtaCteExcepcion,
		'id' => 'GrillaCtacteExcep',
		'summary' => false,
		'headerRowOptions' => ['class' => 'grilla'],
		'columns' => [

				['attribute'=>'tipo_nom','contentOptions'=>['style'=>'width:100px;','class' => 'grilla'],'header' => 'Tipo'],
				['attribute'=>'fchusar', 'format' => ['date', 'php:d/m/Y'], 'contentOptions'=>['style'=>'width:60px;text-align:center','class' => 'grilla'],'header' => 'Fecha Usar'],
           		['attribute'=>'fchlimite', 'format' => ['date', 'php:d/m/Y'], 'contentOptions'=>['style'=>'width:60px; text-align:center','class' => 'grilla'],'header' => 'Fecha Limite'],
           		['attribute'=>'obs','contentOptions'=>['style'=>'width:200px;','class' => 'grilla'],'header' => 'Obs'],
           		['attribute'=>'fchmod', 'format' => ['date', 'php:d/m/Y'], 'contentOptions'=>['style'=>'width:60px;text-align:center','class' => 'grilla'],'header' => 'Fecha'],
           		['attribute'=>'usrmod_nom','contentOptions'=>['style'=>'width:90px;','class' => 'grilla'],'header' => 'Usuario'],
    		]
    		
    	]); 
    		
?>    		