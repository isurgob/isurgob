<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>

<table border="0" width="600px">
	<tr>
		<td>
			<?php
				echo GridView::widget([
					'dataProvider' => $extras['dpServiciosFall'],
					'columns' => [
						['attribute' => 'fecha', 'label' => 'Fecha', 'format' => ['date', 'php:d/m/Y']],
						['attribute' => 'resp', 'label' => 'Resp'],
						['attribute' => 'tserv_nom', 'label' => 'Tipo'],
						['attribute' => 'obj_id_ori', 'label' => 'Obj. Origen'],
						['attribute' => 'obj_id_dest', 'label' => 'Obj. Dest.'],
						['attribute' => 'destino', 'label' => 'Destino'],
						
						['class' => 'yii\grid\ActionColumn',
						'buttons' => [
							'view' => function($url, $model){
								return null;
							},
							
							'update' => function($url, $model){
								return null;
							},
							
							'delete' => function($url, $model){
								return null;
							}
						]
						]
					]
				]);
			?>
		</td>
	</tr>
</table>