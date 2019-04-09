<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use \yii\widgets\Pjax;

$title = 'Vínculos ';
$this->params['breadcrumbs'][] = ['label' => 'Objeto: '.$id, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = $title;

$estado= utb::getCampo('v_objeto', "obj_id= '$id'", 'est_nom');
if($estado === false) $estado= null;

Pjax::begin(['id' => 'PjaxDatos']);
?>
<div class="persona-view">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
    </tr>
	</table>
	
	<p>
		<label>Objeto: </label><input class='form-control solo-lectura' tabindex="-1" style='width:65px' disabled value='<?=$id?>' />
		<label>Nombre: </label><input class='form-control solo-lectura' tabindex="-1" style='width:300px;' disabled value='<?=utb::getNombObj("'".$id."'")?>' />
		<label>Estado: </label><input class="form-control solo-lectura" tabindex="-1" style="width:140px;" disabled value="<?= $estado; ?>">
		
		<?=
			Html::checkbox( 'ckBaja', $baja, [
				'id'		=> 'ckBaja',
				'label'		=> 'Ver dados de baja',
				'onchange'	=> 'verBajasChange()',
			]);
		?>
	</p>
	<?php
		echo GridView::widget([
			'id' => 'GrillaVinc',
			'dataProvider' => $dataprovider,
			'rowOptions' => ['class' => 'grilla'],
		    'headerRowOptions' => ['class' => 'grilla'],
			'columns' => [
            		['attribute'=>'tobj_nom','header' => 'Tipo Objeto', 'options'=>['style'=>'width:80px']],
            		['attribute'=>'obj_id','header' => 'Objeto', 'options'=>['style'=>'width:50px']],
            		['attribute'=>'nombre','header' => 'Nombre', 'options'=>['style'=>'width:250px']],
            		['attribute'=>'tvinc_nom','header' => 'Vínculo', 'options'=>['style'=>'width:100px']],
            		['attribute'=>'porc','header' => 'Porc', 'options'=>['style'=>'width:80px']],
            		['attribute'=>'est','header' => 'Estado', 'options'=>['style'=>'width:80px']],
            		
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:45px'],'template' => '{view}',
            		'buttons'=>[
						'view' => function($url,$model,$key)
            						{
            							return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['view', 'id' => $model['obj_id']]);	
            						}
            			]
            		],
            		           		            		
            	],
    	]); 
    	
	?>
</div>

<?php 
Pjax::end();
?>

<script>
function verBajasChange(){
	$.pjax.reload({

		container	: "#PjaxDatos",
		type		: "Post",
		replace		: false,
		push		: false,
		timeout 	: 1000000,
		data:{
			"baja" : $("#ckBaja").is(":checked")
		},
	});
}
</script>