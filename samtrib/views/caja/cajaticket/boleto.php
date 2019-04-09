<?php
use yii\grid\GridView;
use \yii\widgets\Pjax;

Pjax::begin();

echo GridView::widget([
		'id' => 'GrilaBoleto',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $model->CargarBoleto(),
		'columns' => [
                    
            ['attribute'=>'item_id','header' => 'Cod'],
			['attribute'=>'item_nom','header' => 'Ítem'],
			['attribute'=>'cant','header' => 'Cant'],
			['attribute'=>'monto','contentOptions'=>['align'=>'right'],'header' => 'Monto'],

        ],
    ]); 
    
Pjax::end();
?>
