<?php
use yii\grid\GridView;
use \yii\widgets\Pjax;

Pjax::begin();

echo GridView::widget([
		'id' => 'GrilaRecibo',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $model->CargarRecibo(),
		'columns' => [
                    
            ['attribute'=>'recibo','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Recibo'],
			['attribute'=>'fecha','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Fecha'],
			['attribute'=>'acta','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Acta'],
			['attribute'=>'area_nom','header' => 'Área'],
			['attribute'=>'monto','contentOptions'=>['align'=>'right', 'style'=>'width:50px'],'header' => 'Monto'],

        ],
    ]); 
    
Pjax::end();
?>
