<?php
use yii\grid\GridView;

echo GridView::widget([
		'id' => 'GrillaAsig',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $modelObjeto->CargarAsignaciones($modelObjeto->obj_id),
		'columns' => [
                    
            ['attribute'=>'trib_nom','header' => 'Tributo'],
			['attribute'=>'trib_tipo_nom','header' => 'Tipo'],
			['attribute'=>'item_nom','header' => 'Nombre'],
			['attribute'=>'perdesde','header' => 'Desde'],
			['attribute'=>'perhasta','header' => 'Hasta'],
			['attribute'=>'param1','header' => 'Param1'],
			['attribute'=>'param2','header' => 'Param2'],
			['attribute'=>'modif','header' => 'Modif.'],
        ],
    ]); 
?>
