<?php
use yii\grid\GridView;

echo GridView::widget([
		'id' => 'GrillaAcciones',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => function($model, $key, $index, $grid){
			return  ['class' => 'grilla', 'title' => $model['obs'], 'data-toggle' => 'tooltip'];	
		},
		'dataProvider' => $modelobjeto->CargarAcciones($modelobjeto->obj_id),
		'columns' => [
                    
            ['attribute'=>'taccion_nom','header' => 'Tipo', 'options' => ['style' => 'width:100px;']],
			['attribute'=>'fecha_format','header' => 'Fecha'],
			['attribute'=>'dato_ant','header' => 'Anterior', 'options' => ['style' => 'width:170px;']],
			['attribute'=>'dato_ins','header' => 'Inscripción'],
			['attribute'=>'expe','header' => 'Expediente'],
			['attribute'=>'modif','header' => 'Usuario', 'options' => ['style' => 'width:150px;']],
        ],
    ]); 
?>