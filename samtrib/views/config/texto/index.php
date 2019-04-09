<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$titulo = 'Definición de textos';
$this->params['breadcrumbs'][] = ['label' => 'Configuración', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = $titulo;

$tuso = intval(Yii::$app->request->get('tuso', 0));
?>
<script type="text/javascript">
function cambiaUso(nuevo){
	
	$.pjax.reload({
		container : "#pjaxTextos",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"tuso" : nuevo
		}
	});
}

function cargarTexto(id){
	
	$.pjax.reload({
		container : "#pjaxDetalle",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"texto" : id
		}
	});
}
</script>

<div class="texto-index">
	<?php
	Pjax::begin(['id' => 'pjaxTextos', 'enableReplaceState' => false, 'enablePushState' => false]);
	?>
   <h1><?= $titulo; ?></h1>
   <div style="border-bottom:1px solid #DDDDDD; margin-bottom:10px;"></div>
	
	<table border="0" width="100%">
		<tr>
			<td><label>Uso</label></td>
			<td width="5px"></td>
			<td>
				<?= Html::dropDownList(null, $tuso, utb::getAux('texto_tuso'), ['class' => 'form-control', 'prompt' => '', 'onchange' => 'cambiaUso($(this).val());', 'style' => 'width:100%;']); ?>
			</td>
			
			<td width="400px"></td>
			<td align="right">
			<?php 
				if (utb::getExisteProceso(utb::getCampo('texto_tuso','cod='.$tuso,'proceso')))
					echo Html::a('Nuevo', ['create', 'tuso' => $tuso], ['class' => 'btn btn-success']); 
			?>
			</td>
		</tr>
		
		<tr>
			<td colspan="5">
				<?php
				
				
				$textosModificables = $extras['textosModificables'];
				
				echo GridView::widget([
					'dataProvider' => $extras['dpTextos'],
					'rowOptions' => function($model){
						
						return [
							'onclick' => 'cargarTexto(' . $model['texto_id'] .')'
						];
					},
					'columns' => [
						['attribute' => 'texto_id', 'label' => 'Cod.', 'options' => ['style' => 'width:10px;']],
						['attribute' => 'nombre', 'label' => 'Nombre'],
						['attribute' => 'titulo', 'label' => 'Título', 'options' => ['style' => 'width:350px;']],
						['class' => '\yii\grid\Column', 'options' => ['style' => 'width:50px;'], 'content' => function ($model, $key, $index, $column) use($textosModificables, $tuso){
							
							$ret = '';
							
							if(array_key_exists($model['tuso'], $textosModificables)){
								
								$ret .= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model['texto_id'], 'tuso' => $tuso]);
								$ret .= '&nbsp;';
								$ret .= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model['texto_id'], 'tuso' => $tuso]);
							}
								

							return $ret;
						}]
					]
				]); 
				
				
				?>
			</td>
		</tr>
		
		<tr>
			<td colspan="5">
				<?php
				Pjax::begin(['id' => 'pjaxDetalle', 'enableReplaceState' => false, 'enablePushState' => false]);
				
				$texto = isset($extras['texto']) ? $extras['texto'] : '';
				
				echo Html::textarea(null, $texto, ['class' => 'form-control', 'style' => 'width:780px; max-width:780px; height:540px; max-height:540px;', 'rows' => 40, 'readonly' => true]);
				
				Pjax::end();
				?>
			</td>
		</tr>
	</table>
	<?php
	Pjax::end();
	?>
</div>
