<?php

use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\BaseUrl;

use app\utils\db\utb;
?>
<?= Html::input('hidden', null, null, ['id' => 'buscarObraSeleccionada']); ?>
<div class="obra-buscar">

	<table width="100%" border="0">
		<tr>
			<td><b>Nombre:</b></td>
			<td width="5px"></td>
			<td><?= Html::textInput(null, null, ['class' => 'form-control', 'id' => 'buscarFiltroNombre', 'onchange' => 'busquedaFiltrar();', 'style' => 'width:320px;']); ?></td>
			<td width="10px"></td>
			<td><b>Tipo:</b></td>
			<td width="5px"></td>
			<td><?= Html::dropDownList(null, null, utb::getAux('mej_tobra'), ['class' => 'form-control', 'id' => 'buscarFiltroTipo', 'onchange' => 'busquedaFiltrar();', 'prompt' => '', 'style' => 'width:150px;']); ?></td>
		</tr>
		<tr>
			<td colspan="7">
				<?php
				
				Pjax::begin(['id' => 'pjaxBuscarGrilla', 'enableReplaceState' => false, 'enablePushState' => false]);
				
				echo GridView::widget([
					'dataProvider' => $dpObras,
					'id' => 'grillaBuscarObra',
					'summary' => false,
					'headerRowOptions' => ['class' => 'grillaGrande'],
					'rowOptions' => function($model){
					
						return [
								'onclick' => 'clickFilaBuscar($(this), "' . $model['obra_id'] . '");',
								'ondblclick' => 'buscar(' . $model['obra_id'] . ')',
								'class' => 'grillaGrande'
								];	
					},
					
					'columns' => [
						['attribute' => 'obra_id', 'label' => 'Cód',],
						['attribute' => 'nombre', 'label' => 'Nombre'],
						['attribute' => 'tobra_nom', 'label' => 'Tipo'],
						['attribute' => 'est_nom', 'label' => 'Estado']
					]
				]);
				
				Pjax::end();
				 ?>
			</td>
		</tr>
	</table>
	
	<div class="text-center" style="margin-top:5px;">
		<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'buscar($("#buscarObraSeleccionada").val())', 'disabled' => true, 'id' => 'botonAceptarBuscar']); ?>
		&nbsp;
		<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("' . $selectorModal . '").modal("hide");']); ?>
	</div>
</div>

<?php
Pjax::begin(['id' => 'pjaxBuscar', 'enableReplaceState' => false, 'enablePushState' => false]);


echo Html::input('hidden', null, null);
Pjax::end();
?>

<script type="text/javascript">
function busquedaFiltrar(){
	
	
	var nombre = $("#buscarFiltroNombre").val();
	var tipo = $("#buscarFiltroTipo").val();
	
	$.pjax.reload({
		
		container : "#pjaxBuscarGrilla",
		url : "<?= BaseUrl::toRoute(['buscar']) ?>",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"nombre" : nombre,
			"tipo" : tipo
		}
	});
}

function clickFilaBuscar($fila, codigoObra){
	
	$("#grillaBuscarObra tr").removeClass("success");
	$fila.addClass("success");
	
	$("#buscarObraSeleccionada").val(codigoObra);
	$("#botonAceptarBuscar").prop("disabled", false);
}

function buscar(codigo){
	
	codigo = parseInt(codigo);
	
	if(isNaN(codigo) || codigo <= 0) return;
	
	$.pjax.reload({
		container : "#pjaxBuscar",
		url : "<?= BaseUrl::toRoute(['buscar']) ?>",
		type : "POST",
		replace : false,
		push : false,
		data : {
			"codigoObra" : codigo
		}
	});
}
</script>