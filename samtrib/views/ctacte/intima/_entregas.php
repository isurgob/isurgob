<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\BaseUrl;
?>

<?php
Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);

?>

<script type="text/javascript">
function borrarEntrega(fecha){
	
	$.pjax.reload({
		container : "<?= $pjaxErrorSummary ?>",
		url : "<?= Baseurl::toRoute(['deleteauxiliarseguimiento']) ?>",
		replace : false,
		push : false,
		type : "POST",
		data :  {
			"inti_id" : <?= $extras['model']->inti_id ?>,
			"opcion" : 1,
			"fecha" : fecha,
			"tab" : <?= $tab?>
		}
	});
}
</script>

<table border="0" width="100%">
	<tr>
		<td>
			<?=
				GridView::widget([
					'dataProvider' => $extras['dpEntregas'],
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [
						['label' => 'Fecha', 'attribute' => 'fecha', 'format' => ['date', 'php:d/m/Y'],'contentOptions'=>['style'=>'text-align:center','width'=>'30px']],
						['label' => 'Resultado', 'attribute' => 'resultado','contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						['label' => 'Descripción', 'attribute' => 'resultado_nom','contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						['label' => 'Distribuidor', 'attribute' => 'distrib_nom','contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						['label' => 'Modificación', 'attribute' => 'modif','contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						
						['class' => 'yii\grid\ActionColumn',
						'template' => '{deleteauxiliarseguimiento}',
						'contentOptions'=>['style'=>'text-align:center','width'=>'10px'],
						'buttons' => [
							'deleteauxiliarseguimiento' => function($url, $model, $key){

								
								return Html::button(
											'<span class="glyphicon glyphicon-trash"></span>', 
											[
											'style' => 'padding:0px; border-width:0px; background-color:white; color:#23527c;',
											'onclick' => 'borrarEntrega("' . $model['fecha'] . '")']
											);
							}
						]
						]
					]
				]);
			 ?>
		</td>
	</tr>
</table>

<?php
Pjax::end();
?>