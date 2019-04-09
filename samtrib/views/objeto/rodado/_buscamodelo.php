<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

use app\utils\db\utb;
?>
<div>

	<script type="text/javascript">
	function cambiaMarcaBuscarModelo(nuevo){
		
		var modelo = $("#rodadoBuscaModeloElegido").val();
		
		$.pjax.reload({
			container : "#pjaxBuscaModeloGrilla",
			replace : false,
			push : false,
			type : "GET",
			data : {
				"marcaElegida" : nuevo,
				"modeloElegido" : modelo,
				"categoriaElegida": <?= $categoriaElegida; ?>
			}
		});
	}
	
	function filaBuscaModeloClick($fila, marca_id, modelo_id, modelo_nombre){
		
		$("#rodadoBuscaMarca").val(marca_id);
		$("#rodadoBuscaModelo").val(modelo_id);
		$("#rodadoBuscaModeloNombre").val(modelo_nombre);
		
		$("#grillaBusquedaModelo tr.success").removeClass("success");
		$fila.addClass("success");
	}
	
	function filaBuscaModeloDblClick($fila, marca_id, modelo_id, modelo_nombre){
		
		filaBuscaModeloClick($fila, marca_id, modelo_id, modelo_nombre);
		botonAceptarBuscaModeloClick();
	}
	
	function botonAceptarBuscaModeloClick(){
		
		var marca = $("#rodadoBuscaMarca").val();
		var modelo = $("#rodadoBuscaModelo").val();
		var nombreModelo = $("#rodadoBuscaModeloNombre").val();
		
		$.pjax.reload({
			container : "<?= $selectorPjax ?>",
			replace : false,
			push : false,
			type : "GET",
			data : {
				"marcaElegida" : marca,
				"modeloElegido" : modelo,
				"modeloElegidoNombre" : nombreModelo,
				"esModal" : true
			}
		});
	}
	</script>
	
	<div>
		
		<?= Html::input('hidden', null, null, ['id' => 'rodadoBuscaMarca']) ?>
		<?= Html::input('hidden', null, null, ['id' => 'rodadoBuscaModelo']) ?>
		<?= Html::input('hidden', null, null, ['id' => 'rodadoBuscaModeloNombre']) ?>
		
		<table border="0" width="100%">
			<tr>
				<td width="50px"><label>Marca: </label></td>
				<td><?= Html::dropDownList(null, $marcaElegida, utb::getAux('rodado_marca'), ['id' => 'rodadoBuscaMarcaElegida', 'class' => 'form-control', 'prompt' => '', 'onchange' => 'cambiaMarcaBuscarModelo($(this).val());']) ?></td>
				<td align="right"><?= Html::button('Buscar', ['class' => 'btn btn-primary', 'onclick' => 'cambiaMarcaBuscarModelo($("#rodadoBuscaMarcaElegida").val());']) ?></td>
			</tr>
			
			<tr>
				<td><label>Modelo: </label></td>
				<td><?= Html::textInput(null, null, ['id' => 'rodadoBuscaModeloElegido', 'class' => 'form-control']); ?></td>
			</tr>
		</table>
	</div>
	
	<div>
		<?php
		Pjax::begin(['id' => 'pjaxBuscaModeloGrilla', 'enableReplaceState' => false, 'enablePushState' => false]);
		
		
		$modelos = [];
		//var_dump($marcaElegida);
		$marcaElegida = intval(Yii::$app->request->get('marcaElegida', $marcaElegida));
		$modeloElegido = trim(Yii::$app->request->get('modeloElegido', ''));
		$categoriaElegida= Yii::$app->request->get('categoriaElegida', $categoriaElegida);
		
		$c= "o.marca = $marcaElegida And o.cat = c.cod And a.cod = $marcaElegida And upper(o.nombre) Like upper('%$modeloElegido%')";
		$condicion= $c . ($categoriaElegida > 0 ? " And o.cat = $categoriaElegida" : '');
		 
		$modelos = utb::getAuxVarios(['rodado_modelo As o', 'rodado_marca As a', 'rodado_tcat As c'], 
									['o.nombre', 'a.nombre', 'c.nombre', 'o.marca', 'o.cod'], 
									['o.nombre' => 'nombre', 'a.nombre' => 'marca', 'c.nombre' => 'categoria', 'o.marca' => 'marca_id', 'o.cod' => 'modelo_id'], 
									2, 
									$condicion);
		
		
		$dp = new ArrayDataProvider(['allModels' => $modelos]);
		?>
	
		<table border="0" width="100%">
			<tr>
				<td>
					<?php
						echo GridView::widget([
							'dataProvider' => $dp,
							'id' => 'grillaBusquedaModelo',
							'headerRowOptions' => ['class' => 'grillaGrande'],
							'rowOptions' => function($model, $key, $index, $grid){
							
								return [
									'onclick' => 'filaBuscaModeloClick($(this), ' . $model['marca_id'] . ', ' . $model['modelo_id'] . ', "' . $model['nombre'] . '");',
									'ondblclick' => 'filaBuscaModeloDblClick($(this), ' . $model['marca_id'] . ', ' . $model['modelo_id'] . ', "' . $model['nombre'] . '");'
								];	
							},
							
							'columns' => [
								
								['attribute' => 'marca', 'label' => 'Marca', 'contentOptions' => ['class' => 'grillaGrande']],
								['attribute' => 'nombre', 'label' => 'Modelo', 'contentOptions' => ['class' => 'grillaGrande']],
								['attribute' => 'categoria', 'label' => 'Categoría', 'contentOptions' => ['class' => 'grillaGrande']]
							]
							
						]);
					?>
				</td>
			</tr>
		</table>
		<?php
		Pjax::end();
		?>
	</div>
</div>
