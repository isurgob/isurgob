<?php
use yii\grid\GridView;

echo GridView::widget([
		'id' => 'GrillaAcciones',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $modelObjeto->CargarAcciones($modelObjeto->obj_id),
		'columns' => [
                    
            ['attribute'=>'taccion_nom','header' => 'Tipo'],
			['attribute'=>'fecha_format','header' => 'Fecha'],
			['attribute'=>'dato_ant','header' => 'Anterior'],
			['attribute'=>'dato_ins','header' => 'Inscripción'],
			['attribute'=>'expe','header' => 'Expediente'],
			['attribute'=>'modif','header' => 'Usuario'],
        ],
    ]); 
?>
