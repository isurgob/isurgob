<?php

use yii\helpers\Html;
use yii\grid\GridView;
?>

<table boder="0" width="600px">
	<tr>
		<td width="620px">
			<?php
			
				echo GridView::widget([
					'dataProvider' => $extras['dpFallecidos'],
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'columns' => [
						['attribute' => 'fall_id', 'label' => 'Código'],
						['attribute' => 'apenom', 'label' => 'Apellido y Nombre'],
						['attribute' => 'tdoc_nom', 'label' => 'TDoc'],
						['attribute' => 'ndoc', 'label' => 'NDoc'],
						['attribute' => 'fchdef', 'label' => 'Defunción'],
						['attribute' => 'est_nom', 'label' => 'Estado'],
						
						['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:1px'],'template' => '{view}',
							'buttons'=>[
								'view' => function($url,$model,$key) use ($consulta)
										  {
											if ($consulta == 1)
												return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['//objeto/cem/viewfall','id' => $model['fall_id']], ['data-pjax'=>'false']);
										  }
								]
						],
					]
				]);
			?>
		</td>
	</tr>
</table>