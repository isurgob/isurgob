<?php

use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\web\Session;


/**
 * PARAMETROS ESPERADOS
 * 
 * selectorCodigo String - Selector CSS del elemento donde se debe colocar el codigo de la cuenta
 * selectorNombre String - Selector CSS del elemento donde se debe colocar el nombre de la cuenta
 * id String - Codigo para hacer la vista unica
 * 
 * selectorModal String - Selector CSS del modal que se debe cerrar luego de que se haya elegido la cuenta
 * selectorDiv String - En caso de que no se haya pasado el parametro 'selectorModal', se usará para agregar la clase 'hidden' al elemento que corresponde el selector
 * 
 * condicion String - Condicion where que se debe pasar a la consulta de filtro
 */

$id = isset($id) ? $id : 'default;'
?>


<div class="cuentaBusquedaAv<?= $id; ?>">

	<table border="0">
	
		<tr>
			<td><?= Html::radio('rb' . $id, false, ['label' => 'Código', 'id' => 'ckCodigo' . $id, 'class' => 'check', 'data-target' => '#txCodigo' . $id]); ?></td>
			<td width="5px"></td>
			<td><?= Html::textInput(null, null, ['class' => 'form-control', 'disabled' => true, 'id' => 'txCodigo' . $id, 'style' => 'width:80px;', 'maxlength' => 4]); ?></td>
			<td width="10px"></td>
			<td><?= Html::radio('rb' . $id, false, ['label' => 'Nombre', 'id' => 'ckNombre' . $id, 'class' => 'check', 'data-target' => '#txNombre' . $id]); ?></td>
			<td width="5px"></td>
			<td><?= Html::textInput(null, null, ['class' => 'form-control', 'disabled' => true, 'id' => 'txNombre' . $id, 'style' => 'width:80px;', 'maxlength' => 20]); ?></td>
			<td width="5px"></td>
			<td><?= Html::radio('rb' . $id, false, ['label' => 'Tipo de Cuenta', 'id' => 'ckTipoCuenta' . $id, 'class' => 'check', 'data-target' => '#txTipoCuenta' . $id]); ?></td>
			<td width="5px"></td>
			<td><?= Html::textInput(null, null, ['class' => 'form-control', 'disabled' => true, 'id' => 'txTipoCuenta' . $id, 'style' => 'width:80px;', 'maxlength' => 40]); ?></td>
		</tr>

		
		<tr>
			<td colspan="11">
			<?= Html::radio('rb' . $id, false, ['label' => 'Código Partida Presupuestaria', 'id' => 'ckCodigoPartidaPresupuestaria' . $id, 'class' => 'check', 'data-target' => '#txCodigoPartidaPresupuestaria' . $id]); ?>
			<?= Html::textInput(null, null, ['class' => 'form-control', 'disabled' => true, 'id' => 'txCodigoPartidaPresupuestaria' . $id, 'style' => 'width:300px;', 'maxlength' => 40]); ?>
			</td>
		</tr>
		
		<tr>
			<td colspan="11">
			<?= Html::radio('rb' . $id, false, ['label' => 'Formato Partida Presupuestaria', 'id' => 'ckFormatoPartidaPresupuestaria' . $id, 'class' => 'check', 'data-target' => '#txFormatoPartidaPresupuestaria' . $id]); ?>
			<?= Html::textInput(null, null, ['class' => 'form-control', 'disabled' => true, 'id' => 'txFormatoPartidaPresupuestaria' . $id, 'style' => 'width:300px;', 'maxlength' => 40]); ?>
			</td>
		</tr>
		
		<tr>
			<td><?= Html::button('Buscar', ['class' => 'btn btn-primary', 'onclick' => 'buscarCuenta' . $id . '();']); ?></td>			
		</tr>
	</table>
	
	<div class="error-summary hidden">
		<span></span>
	</div>
	
	<div style="margin-top:10px;">
		<?php
		
		Pjax::begin(['id' => 'pjaxBusquedaCuenta' . $id, 'enableReplaceState' => false, 'enablePushState' => false]);
		
		$models = [];
		
		$session = new Session();
		$session->open();
		
		if(Yii::$app->request->isPost){
			
			$criterio = trim(Yii::$app->request->post('criterio', ''));
			
			if(!empty($criterio)) $session->set('criterioCuenta', $criterio);				
			
		}
		
		$criterio = $session->get('criterioCuenta', '');
		
		if(!empty($criterio)){
		
			$sql = "Select * From v_cuenta Where $criterio";
			$models = Yii::$app->db->createCommand($sql)->queryAll();	
		}

		$dpCuentas = new ArrayDataProvider([
						'allModels' => $models,
						'pagination' => [
							'pageSize' => 10
						]
					]);
					
		$session->close();
		
		echo GridView::widget([
			'dataProvider' => $dpCuentas,
			'rowOptions' => function($model) use($id){
				return [
					'onclick' => 'clickFila' . $id . '("' . $model['cta_id'] . '", "' . $model['nombre_redu'] . '");',
					'ondblclick' => 'dblClickFila' . $id . '("' . $model['cta_id'] . '", "' . $model['nombre_redu'] . '");',
					'class' => 'grillaGrande'
				];
			},
			'columns' => [
				['attribute' => 'cta_id', 'label' => 'Cód', 'headerOptions' => ['style' => 'width:10px;', 'class' => 'grillaGrande']],
				['attribute' => 'nombre_redu', 'label' => 'Nombre', 'headerOptions' => ['style' => 'width:150px;', 'class' => 'grillaGrande']],
				['attribute' => 'part_id', 'label' => 'Part.', 'headerOptions' => ['style' => 'width:10px;', 'class' => 'grillaGrande']],
				['attribute' => 'formato_aux', 'label' => 'Formato', 'headerOptions' => ['style' => 'width:10px;', 'class' => 'grillaGrande']],
				['attribute' => 'part_nom', 'label' => 'Partida Presup.', 'headerOptions' => ['style' => 'width:150px;', 'class' => 'grillaGrande']],
				['attribute' => 'tcta_nom', 'label' => 'Tipo Cuenta', 'headerOptions' => ['style' => 'width:10px;', 'class' => 'grillaGrande']]
			]
		]);
		
		Pjax::end();
		?>
	</div>
</div>


<script type="text/javascript">
function clickFila<?= $id; ?>(codigoCuenta, nombreCuenta){
	
	$("<?= $selectorCodigo; ?>").val(codigoCuenta);
	$("<?= $selectorNombre; ?>").val(nombreCuenta);
}

function dblClickFila<?= $id; ?>(codigoCuenta, nombreCuenta){
	
	clickFila<?= $id; ?>(codigoCuenta, nombreCuenta);
	
	<?php
	if(isset($selectorModal)){
	?>
	$("<?= $selectorModal ?>").modal("hide");
	<?php
	} else if(isset($selectorDiv)){
	?>
	$("<?= $selectorDiv; ?>").addClass("hidden");
	<?php
	}
	?>
}

function mostrarError<?= $id; ?>(error){
	
	$contenedor = $(".cuentaBusquedaAv<?= $id; ?> .error-summary span");
	
	$(".cuentaBusquedaAv<?= $id; ?> .error-summary").removeClass("hidden");
	$contenedor.empty();
	
	$contenedor.text(error);
}

function validar<?= $id; ?>(){
	
	$checkeado = $(".cuentaBusquedaAv<?= $id; ?> .check:checked");
	var error = "";
	
	switch($checkeado.attr("id")){
		
		case "ckCodigo<?= $id; ?>": if($("#txCodigo<?= $id; ?>").val() == "") error = "Ingrese el código"; break; 			
		case "ckNombre<?= $id; ?>": if($("#txNombre<?= $id; ?>").val() == "") error = "Ingrese el nombre"; break;
		case "ckTipoCuenta<?= $id; ?>": if($("#txTipoCuenta<?= $id; ?>").val() == "") error = "Ingrese el tipo de cuenta"; break;
		case "ckArbol<?= $id; ?>": if($("#txArbol<?= $id; ?>").val() == "") error = "Elija un elemento del árbol"; break;
		case "ckCodigoPartidaPresupuestaria<?= $id; ?>": if($("#txCodigoPartidaPresupuestaria<?= $id; ?>").val() == "") error = "Ingrese el código de la partida presupuestaria"; break;
		case "ckFormatoPartidaPresupuestaria<?= $id; ?>": if($("#txFormatoPartidaPresupuestaria<?= $id; ?>").val() == "") error = "Ingrese el formato de la partida preupuestaria"; break;
		default : error = "No se encontraron criterios de búsqueda";
	}

	if(error != ""){
		mostrarError<?= $id; ?>(error);
		return false;
	}
	
	return true;
}

function conformarDatos<?= $id; ?>(){
	
	$checkeado = $(".cuentaBusquedaAv<?= $id; ?> .check:checked");
	
	switch($checkeado.attr("id")){
		
		case "ckCodigo<?= $id; ?>": return	"cta_id = " + $("#txCodigo<?= $id; ?>").val();
		case "ckNombre<?= $id; ?>": return	"nombre ilike '%" + $("#txNombre<?= $id; ?>").val() + "%'";
		case "ckTipoCuenta<?= $id; ?>": return	"tcta_nom ilike '%" + $("#txTipoCuenta<?= $id; ?>").val() + "%'";
		case "ckCodigoPartidaPresupuestaria<?= $id; ?>": return "part_id = " + $("#txCodigoPartidaPresupuestaria<?= $id; ?>").val();
		case "ckFormatoPartidaPresupuestaria<?= $id; ?>": return "formato_aux ilike '%" + $("#txFormatoPartidaPresupuestaria<?= $id; ?>").val() + "%'";
	}
	
	return "";
}

function buscarCuenta<?= $id; ?>(){
	
	var criterio = "";
	
	$(".cuentaBusquedaAv .error-summary").addClass("hidden");
	criterio = conformarDatos<?= $id; ?>();
	
	if(!validar<?= $id; ?>() || criterio == "") return;

	<?php
	if(isset($condicion) && !empty($condicion))
		echo 'criterio = "' . $condicion . ' And " + criterio;';
	?>
	
	$.pjax.reload({
		container : "#pjaxBusquedaCuenta<?= $id ?>",
		type : "POST",
		replace : false,
		push : false,
		data : {
			"criterio" : criterio
		}
	});
	
}

$(".check").click(function(){
	
	$(".cuentaBusquedaAv<?= $id; ?> input").prop("disabled", true);
	$(".cuentaBusquedaAv<?= $id; ?> input.check").prop("disabled", false);
	
	var targets = $(this).data("target").split(",");
	checked = $(this).is(":checked");
	
	targets.forEach(function(el){
		$(el.trim()).prop("disabled", !checked);
	});	
});
</script>