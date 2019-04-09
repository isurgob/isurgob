<?php

use yii\grid\GridView;

use yii\data\SqlDataProvider;

use yii\helpers\Html;
use yii\helpers\BaseUrl;

use yii\widgets\Pjax;

use yii\bootstrap\Modal;

$consulta = isset($consulta) ? $consulta : 0;

$botonesEdicion = [
	'view' => function(){return null;},
	'update' => function(){return null;},
	'delete' => function(){return null;}
];

//consulta para modificar. Se muestran los botones
if($consulta == 3)
{
	$botonesEdicion = [
            	
            	'view' => function(){return  null;},
            	'update' => function($url, $model, $key){
            		//$url = BaseUrl::to(['//ctacte/itemvigencia/update']) . '&item_id='. $model['item_id'] . '&perdesde=' . $model['perdesde'] . '&perhasta=' . $model['perhasta'];
            		return Html::a('<span class="glyphicon glyphicon-pencil"></span>', null, ['data-toggle' => 'modal', 'data-target' => '#modalFormVigencia']);
            	},
            	'delete' => function($url, $model, $key){
            		$url = BaseUrl::to(['//ctacte/itemvigencia/delete']) . '&item_id=' . $model['item_id'] . '&perdesde=' . $model['perdesde'] . '&perhasta=' . $model['perhasta'];
            		return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
            	}
            ];
}

echo GridView::widget([
    	    'dataProvider' => $dataProvider,
    	    'columns' => [

			['attribute' => 'item_id', 'label' => 'Cód.'],            
			['attribute' => 'perdesde', 'label' => 'Desde'],
			['attribute' => 'perhasta', 'label' => 'Hasta'],
			['attribute' => 'tcalculo', 'label' => 'Cálculo'],
			['attribute' => 'monto', 'label' => 'Monto'],
			['attribute' => 'porc', 'label' => 'Porcentaje'],
			['attribute' => 'minimo', 'label' => 'Mínimo'],
			['attribute' => 'paramnombre1', 'label' => 'Param. 1'],
			['attribute' => 'paramnombre2', 'label' => 'Param. 2'],
			['attribute' => 'modificacion', 'label' => 'Modificación'],

            ['class' => 'yii\grid\ActionColumn',
            
            'buttons' => $botonesEdicion
            ],
        ],
    ] );
?>





