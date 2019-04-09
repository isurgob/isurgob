<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\Pjax;
use yii\widgets\MaskedInput;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\data\Pagination;

use yii\jui\DatePicker;

$id = isset($id) ? $id : Yii::$app->request->post('idBusquedaAvanzada', 'busquedaAvanzadaComercio');
$txCod = isset($txCod) ? $txCod : Yii::$app->request->post('txCod', 'txCod');
$txNom = isset($txNom) ? $txNom : Yii::$app->request->post('txNom', 'txNom');
$selectorModal= isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', null);

echo Html::hiddenInput(null, null, ['id' => 'sort']);
?>
<?php
Pjax::begin(['id' => 'pjaxGrillaBusquedaAvanzada' . $id, 'enableReplaceState' => false, 'enablePushState' => false]);

echo Html::hiddenInput('tipoObjeto', 3, ['id' => 'busquedaAvanzadaTipoObjeto' . $id]);

$opciones= [
		$valorOpcion('nombre') => 'Nombre',
		$valorOpcion('documento') => 'Doc./Cuit',
		$valorOpcion('fecha_alta') => 'Fecha alta'
		
		];
		
if($model->opcion == null || !array_key_exists($model->opcion, $opciones))
	$model->opcion= key($opciones);
?>

<div id="busquedaAvanzada<?= $id; ?>">

	<table border="0">
		<tr>
			<td>Criterio: </td>
			<td><?= Html::activeDropDownList($model, 'opcion', $opciones, ['class' => 'form-control', 'onchange' => 'cambiaOpcion' . $id . '($(this).val());', 'id' => 'dlOpcion' . $id]); ?></td>
			<td width="15px"></td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('nombre') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('nombre') ?>">
				<?= Html::activeTextInput($model, 'nombre', ['id' => 'txNombre' . $id, 'maxlength' => 50, 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('documento') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('documento') ?>">
				<?= Html::activeTextInput($model, 'documento', ['id' => 'txDocumento' . $id, 'maxlength' => 11, 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('fecha_alta') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('fecha_alta') ?>">
				<label>Desde &nbsp;</label>
				<?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha_alta_desde', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['id' => 'txFechaAltaDesde' . $id, 'class' => 'form-control criterio', 'style' => 'width:80px;', 'maxlength' => 10]]); ?>
				<label>Hasta &nbsp;</label>
				<?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha_alta_hasta', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['id' => 'txFechaAltaHasta' . $id, 'class' => 'form-control criterio', 'style' => 'width:80px;', 'maxlength' => 10]]); ?>
			</td>
			
			<td width="15px"></td>
			
			<td>
				<?= Html::Button('Buscar',['class' => 'btn btn-primary', 'onClick' => 'buscar' . $id . '();'])?>
			</td>
		</tr>
	</table>

<!--
	<table border="0">		
		<tr>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nombre:', 'class' => 'check' . $id, 'data-target' => '#txNombre' . $id, 'uncheck' => null, 'value' => $valorOpcion('nombre')]); ?></td>
			<td width='5px'></td>
			<td><?= Html::activeTextInput($model, 'nombre', ['id' => 'txNombre' . $id, 'maxlength' => 50, 'style' => 'width:100%;', 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nombre'))]); ?></td>
			</td>
			<td width="20px"></td>
			<td valign="top"><?= Html::activeRadio($model, 'opcion', ['label' => 'Doc./Cuit:', 'class' => 'check' . $id, 'data-target' => '#txDocumento' . $id, 'uncheck' => null, 'value' => $valorOpcion('documento')]); ?></td>
			<td width='5px'></td>
			<td><?= Html::activeTextInput($model, 'documento', ['id' => 'txDocumento' . $id, 'maxlength' => 11, 'style' => 'width:100%;', 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('documento'))]); ?></td>
		</tr>
		<tr>
			<td valign="top"><?= Html::activeRadio($model, 'opcion', ['label' => 'Fecha alta:', 'class' => 'check' . $id, 'data-target' => '#txFechaAltaDesde' . $id .', #txFechaAltaHasta' . $id, 'uncheck' => null, 'value' => $valorOpcion('fecha_alta')]); ?></td>
			<td width='5px'></td>
			<td>
				<label>Desde &nbsp;</label>
				<?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha_alta_desde', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['id' => 'txFechaAltaDesde' . $id, 'class' => 'form-control criterio', 'style' => 'width:80px;', 'maxlength' => 10, 'disabled' => ($model->opcion != $valorOpcion('fecha_alta'))]]); ?>
				<label>Hasta &nbsp;</label>
				<?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha_alta_hasta', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['id' => 'txFechaAltaHasta' . $id, 'class' => 'form-control criterio', 'style' => 'width:80px;', 'maxlength' => 10, 'disabled' => ($model->opcion != $valorOpcion('fecha_alta'))]]); ?>
			</td>
		</tr>
	</table>
-->
</div>

<div class="error-summary hidden" id="contenedorErroresBusquedaAvanzada<?= $id; ?>" style="margin-top:5px;">
	<ul></ul>
</div>

<div style="margin-top:5px;">
	<?php
	Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);
	$paginador= new LinkPager([
		'pagination' => new Pagination(['totalCount' => count($dataProviderDatos->getModels())]),
		'linkOptions' => [
			'onclick' => 'buscar($(this).data("page"));'
		]
	]);
	
	$grilla= GridView::Begin([
		'id' => 'grillaBusquedaAvanzada' . $id,
		'dataProvider' => $dataProviderDatos,
		
		'pager' => [
			
			'pagination' => new Pagination(['totalCount' => count($dataProviderDatos->getModels())]),
			'linkOptions' => [
				'registerLinkTags' => false,
				'class' => 'linkPaginadorBusquedaAvanzada' . $id 
			]
		],
		
		'headerRowOptions' => ['class' => 'grilla'],
		
		'rowOptions' => function($model) use($id){
			return [
				'onclick' => 'clickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . $model['nombre'] . '", $(this));',
				'ondblclick' => 'dobleClickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . $model['nombre'] . '", $(this));'
			];
		},

		'columns' => 		
    	[
        	['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'width:70px', 'class' => 'grilla'], 'headerOptions' => ['class' => 'linkCabeceraBusquedaAvanzada' . $id]],
            ['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'width:200px', 'class' => 'grilla'], 'headerOptions' => ['class' => 'linkCabeceraBusquedaAvanzada' . $id]],
            ['attribute'=>'documento','header' => 'DNI/Cuit', 'contentOptions'=>['style'=>'width:80px', 'class' => 'grilla']],
            ['attribute'=>'dompos_dir','header' => 'Domicilio Postal', 'contentOptions'=>['style'=>'width:250px', 'class' => 'grilla']]
         ],
    
		]);
		
		
	
	
	GridView::end();
	Pjax::end();
	?>
	
</div>


<script type="text/javascript">
function clickFilaBusquedaAvanzada<?= $id; ?>(codigoObjeto, nombreObjeto, $fila){
	
	$("#<?= $txCod; ?>").val(codigoObjeto);
	$("#<?= $txNom; ?>").val(nombreObjeto);
	
	$("#grillaBusquedaAvanzada<?= $id; ?> tr.success").removeClass("success");
	$fila.addClass("success");
}

function dobleClickFilaBusquedaAvanzada<?= $id; ?>(codigoObjeto, nombreObjeto, $fila){
	
	clickFilaBusquedaAvanzada<?= $id; ?>(codigoObjeto, nombreObjeto, $fila);
	$("<?= $selectorModal ?>").modal("hide");
}

function buscar<?= $id; ?>(pagina, url){
	
	pagina= parseInt(pagina);
	if(isNaN(pagina) || pagina <= 0) pagina= 0;
	
	var urlTo= "<?= BaseUrl::toRoute(['//objeto/busquedaavanzada/buscar', 'tipoObjeto' => $tipoObjeto, 'id' => $id, 'txCod' => $txCod, 'txNom' => $txNom]); ?>" + "&page=" + (pagina + 1).toString();
	var sort= $("#sort").val();
	if(sort != '') urlTo += "&sort=" + sort;
	if(url) urlTo = url;
	
	var datos= {
		
		"idBusquedaAvanzada": "<?= $id; ?>",
		"txCod": "<?= $txCod; ?>",
		"txNom": "<?= $txNom; ?>",
		"selectorModal": "<?= $selectorModal; ?>"
	};
	
	var opcion= $("#dlOpcion<?= $id; ?>").attr("name");
	datos[opcion]= $("#dlOpcion<?= $id; ?>").val();
	
	var $criterios= $("#busquedaAvanzada<?= $id; ?> .opcionBusquedaAvanzada<?= $id; ?>:not(.hidden)").find(".criterio");
//	var $checks= $("#busquedaAvanzada<?= $id; ?>").find(".check<?= $id; ?>:checked");
	
	$criterios.each(function(){
		datos[$(this).attr("name")]= $(this).val();
	});
	
//	$checks.each(function(){
//		datos[$(this).attr("name")]= $(this).val();
//	});
	
	datos.tipoObjeto= $("#busquedaAvanzadaTipoObjeto<?= $id; ?>").val();	
	
	$.pjax.reload({
		container: '#pjaxGrillaBusquedaAvanzada<?= $id; ?>',
		url: urlTo,
		type: "POST",
		replace: false,
		push: false,
		data: datos,
		timeout: 10000
	});
}

function mostrarErroresBusquedaAvanzada<?= $id ?>(errores){
	
	$contenedor= $("#contenedorErroresBusquedaAvanzada<?= $id; ?>");
	$lista= $("#contenedorErroresBusquedaAvanzada<?= $id; ?> ul");
	$lista.empty();
	
	if(errores.length == 0){
		
		$contenedor.addClass("hidden");
		return;
	}
	
	$contenedor.removeClass("hidden");
	for(var i in errores){
		
		$li= $("<li />");
		$li.text(errores[i]);
		$li.appendTo($lista);
	}
}

function cambiaOpcion<?= $id; ?>(nuevo){
	
	$opciones= $("#busquedaAvanzada<?= $id; ?> .opcionBusquedaAvanzada<?= $id ?>");
	
	$opciones.addClass("hidden");
	
	$opciones.each(function(){
		
		if($(this).data("opcion") == nuevo)
			$(this).removeClass("hidden");
	});
}

//$(".check<?= $id; ?>").click(function(){
//	
//	$("#busquedaAvanzada<?= $id; ?>").find(".criterio").prop("disabled", true);
//	
//	var targets = $(this).data("target").split(",");
//	checked = $(this).is(":checked");
//	
//	targets.forEach(function(el){
//		$(el.trim()).prop("disabled", !checked);
//	});	
//});


$(document).ready(function(){
	
	var errores= new Array();
	<?php
	if($model->hasErrors()){
		
		$errores= $model->getErrors();
		
		foreach($errores as $atributo => $arregloErrores){

			foreach($arregloErrores as $error){
			
			?>
			errores.push("<?= $error; ?>");
			<?php
			}
		}
	}
	?>
	
	
	if(errores.length > 0) mostrarErroresBusquedaAvanzada<?= $id; ?>(errores);
	else $("#contenedorErroresBusquedaAvanzada<?= $id; ?>").addClass("hidden");
	
	

	$(".linkPaginadorBusquedaAvanzada<?= $id; ?>").each(function(){
		
		$(this).attr("href", "#");
		$(this).click(function(e){
			
			e.preventDefault();
			e.stopPropagation();
			
			buscar<?= $id; ?>($(this).data("page"));
		});
	});
	
	$(".linkCabeceraBusquedaAvanzada<?= $id ?> a").click(function(e){

		e.preventDefault();
		e.stopPropagation();
		
		var sort= $(this).data("sort");
		$("#sort").val(sort);
		
		if(sort != null && sort != '')
			buscar<?= $id; ?>(0, $(this).attr("href"), sort);
		
	});
});
</script>



<?php
Pjax::end();
?>