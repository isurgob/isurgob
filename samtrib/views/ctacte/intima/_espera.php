<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\BaseUrl;
?>

<script type="text/javascript">
function borrarEspera(inti_id, fecha, adesde, cdesde, trib_id){
	
	$.pjax.reload({
		container : "<?= $pjaxErrorSummary ?>",
		url : "<?= Baseurl::toRoute(['deleteauxiliarseguimiento']) ?>",
		replace : false,
		push : false,
		type : "POST",
		data :  {
			"inti_id" : <?= $extras['model']->inti_id ?>,
			"opcion" : 3,
			"trib_id" : trib_id,
			"fecha" : fecha,
			"adesde" : adesde,
			"cdesde" : cdesde,
			"tab" : <?= $tab?>
		}
	});
}
</script>

<?php
Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);
?>

<table border="0" width="100%">
	<tr>
		<td>
			<?=
				GridView::widget([
					'dataProvider' => $extras['dpEspera'],
					'columns' => [
						['label' => 'Trib', 'attribute' => 'trib_nom'],
						['label' => 'Periodo Desde', 'attribute' => 'perdesde'],
						['label' => 'Periodo Hasta', 'attribute' => 'perhasta'],
						['label' => 'Desde', 'attribute' => 'fchdesde', 'format' => ['date', 'php:d/m/Y']],
						['label' => 'Hasta', 'attribute' => 'fchhasta', 'format' => ['date', 'php:d/m/Y']],
						['label' => 'Obs', 'attribute' => 'obs'],
						
						['class' => 'yii\grid\ActionColumn',
						'template' => '{deleteauxiliarseguimiento}',
						'buttons' => [
							'deleteauxiliarseguimiento' => function($url, $model, $key){
								
								$perdesde = $model['perdesde'];
								
								$adesde = intval($perdesde / 1000);
								$cdesde = $perdesde % 1000;
								
								return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['onclick' => 'borrarEspera(0, "' . $model['fchdesde'] . '", ' . $adesde . ', ' . $cdesde . ', ' . $model['trib_id'] . ')']);
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