<?php
use yii\grid\GridView;
use yii\helpers\Html;
use \yii\widgets\Pjax;

echo Html::checkbox('asig_solo_activo', false, [ 
								'id' => 'asig_solo_activo', 
								'label'=>'Solo Activos'
							]);

Pjax::begin([ 'id' => 'pjaxAsig' ]);
	echo GridView::widget([
		'id' => 'GrillaAsig',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $modelobjeto->CargarAsignaciones($modelobjeto->obj_id, intVal(Yii::$app->request->post( 'asig_solo_activo', '0' ))),
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
Pjax::end();
?>

<script>

$('input[type=checkbox][name=asig_solo_activo]').change(function() {

	$.pjax.reload({
		container	:"#pjaxAsig",
		type 		: "POST",
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data:{
			"asig_solo_activo" : (this.checked ? 1 : 0)
		},
	});
});

</script>
