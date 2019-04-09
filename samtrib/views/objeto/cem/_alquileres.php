<?php
use yii\helpers\Html;
use yii\grid\GridView;

?>

<table border="0" width="600px">
	<tr>
		<td>
			<?= Html::checkbox('incluir_bajas', null, ['label' => 'Incluir Bajas']) ?>
		</td>
		
		<td></td>
	</tr>
	
	<tr>
	
		<td colspan="2">
			<?php
				echo GridView::widget([
									'dataProvider' => $extras['dpAlquileres'],
									'headerRowOptions' => ['class' => 'grilla'],
									'rowOptions' => ['class' => 'grilla'],
									'columns' => [
									
										['attribute' => 'alq_id', 'label' => 'Cod'],
										['attribute' => 'resp_nom', 'label' => 'Responsable'],
										['attribute' => 'titulo', 'label' => 'Título'],
										['attribute' => 'fchalq', 'format' => ['date', 'php:d/m/Y'], 'label' => 'Alquiler'],
										['attribute' => 'fchini', 'format' => ['date', 'php:d/m/Y'], 'label' => 'Inicio'],
										['attribute' => 'final', 'format' => ['date', 'php:d/m/Y'], 'label' => 'Final'],
										['attribute' => 'est_nom', 'label' => 'Estado']
									
									]
					]);
			?>
		</td>
	</tr>
</table>
