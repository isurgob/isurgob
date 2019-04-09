<?php
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * Grilla que muestra datos de períodos de una intimación.
 * 
 * Se dibuja en:
 * 		+ Seguimiento de intimación
 */

Pjax::begin(['enablePushState' => false, 'enableReplaceState' => false]);
?>
<table width="100%">
	<tr>
		<td>
			<?=
				GridView::widget([
					'dataProvider' => $extras['dpPeriodos'],
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [
					
						['label' => 'Trib', 'attribute' => 'trib_nom', 'contentOptions'=>['style'=>'text-align:left','width'=>'30px']],
						['label' => 'Objeto', 'attribute' => 'obj_id', 'contentOptions'=>['style'=>'text-align:left','width'=>'30px']],
						['label' => 'Año', 'attribute' => 'anio', 'contentOptions'=>['style'=>'text-align:left','width'=>'30px']],
						['label' => 'Cuota', 'attribute' => 'cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'30px']],
						['label' => 'Nominal', 'attribute' => 'nominal' ,'contentOptions'=>['style'=>'text-align:right','width'=>'30px']],
						['label' => 'Accesor', 'attribute' => 'accesor' ,'contentOptions'=>['style'=>'text-align:right','width'=>'30px']],
						['label' => 'Multa', 'attribute' => 'multa' ,'contentOptions'=>['style'=>'text-align:right','width'=>'30px']],
						['label' => 'Total', 'attribute' => 'total' ,'contentOptions'=>['style'=>'text-align:right','width'=>'30px']],
						['label' => 'Est', 'attribute' => 'est' ,'contentOptions'=>['style'=>'text-align:center','width'=>'30px']],
						
					]
				]);
			 ?>
		</td>
	</tr>
</table>
<?php
Pjax::end();
?>
