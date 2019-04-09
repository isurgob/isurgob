<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
?>

<?php
Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);

?>

<script type="text/javascript">
function borrarEtapa(fecha){
	
	$.pjax.reload({
		container : "<?= $pjaxErrorSummary ?>",
		url : "<?= BaseUrl::toRoute(['deleteauxiliarseguimiento']) ?>",
		replace : false,
		push : false,
		type : "POST",
		data :  {
			"inti_id" : <?= $extras['model']->inti_id ?>,
			"opcion" : 2,
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
					'dataProvider' => $extras['dpEtapas'],
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [
						['label' => 'Fecha', 'attribute' => 'fecha', 'format' => ['date', 'php:d/m/Y']],
						['label' => 'Etapa', 'attribute' => 'etapa_nom'],
						['label' => 'Detalle', 'attribute' => 'detalle'],
						
						['class' => 'yii\grid\ActionColumn',
						'template' => '{deleteauxiliarseguimiento}',
						'buttons' => [
							'deleteauxiliarseguimiento' => function($url, $model, $key){
								return Html::button(
											'<span class="glyphicon glyphicon-trash"></span>', 
											[
											'style' => 'padding:0px; border-width:0px; background-color:white; color:#23527c;',
											'onclick' => 'borrarEtapa("' . $model['fecha'] . '")']
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