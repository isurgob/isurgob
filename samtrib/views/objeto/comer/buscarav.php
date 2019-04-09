<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\Pjax;
use yii\widgets\MaskedInput;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\data\Pagination;

$id = isset($id) ? $id : Yii::$app->request->post('idBusquedaAvanzada', 'busquedaAvanzadaComercio');
$txCod = isset($txCod) ? $txCod : Yii::$app->request->post('txCod', 'txCod');
$txNom = isset($txNom) ? $txNom : Yii::$app->request->post('txNom', 'txNom');
$selectorModal= isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', null);

echo Html::hiddenInput(null, null, ['id' => 'sort']);
?>
<?php
Pjax::begin(['id' => 'pjaxGrillaBusquedaAvanzada' . $id, 'enableReplaceState' => false, 'enablePushState' => false]);

echo Html::hiddenInput('tipoObjeto', 2, ['id' => 'busquedaAvanzadaTipoObjeto' . $id]);

$opciones= [
		$valorOpcion('nombre_fantasia') => 'Nombre de Fantasía',
		$valorOpcion('cuit') => 'CUIT',
		$valorOpcion('nombre_responsable') => 'Nombre responsable',
		$valorOpcion('ingresos_brutos') => 'Ingresos brutos'
		
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
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('nombre_fantasia') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('nombre_fantasia') ?>">
				<?= Html::ActiveTextInput($model, 'nombre_fantasia', ['id' => 'txNombreFantasia' . $id, 'maxlength' => 50, 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('cuit') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('cuit') ?>">
				<?= MaskedInput::widget(['model' => $model, 'attribute' => 'cuit', 'mask' => '99-99999999-9', 
					'options' => ['class' => 'form-control criterio', 'maxlenght' => 13, 'id' => 'txCuit' . $id]]); ?>
			</td>

			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('nombre_responsable') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('nombre_responsable') ?>">
				<?= Html::activeTextInput($model, 'nombre_responsable', ['id' => 'txNombreResponsable' . $id, 'maxlength' => 50, 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('ingresos_brutos') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('ingresos_brutos') ?>">
				<?= Html::activeTextInput($model, 'ingresos_brutos', ['id' => 'txIngresosBrutos' . $id, 'maxlength' => 11, 'class' => 'form-control criterio']); ?>
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
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nombre de Fantasía:', 'class' => 'check' . $id, 'data-target' => '#txNombreFantasia' . $id, 'value' => $valorOpcion('nombre_fantasia'), 'uncheck' => null]); ?> </td> 
			<td></td>
			<td colspan="3"><?= Html::ActiveTextInput($model, 'nombre_fantasia', ['id' => 'txNombreFantasia' . $id, 'maxlength' => 50, 'disabled' => ($model->opcion != $valorOpcion('nombre_fantasia')), 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?></td>
			<td width="20px"></td>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'CUIT:', 'class' => 'check' . $id, 'data-target' => '#txCuit' . $id, 'value' => $valorOpcion('cuit'), 'unckeck' => null, 'maxlength' => 13]); ?></td>
			<td></td>
			<td><?= MaskedInput::widget(['model' => $model, 'attribute' => 'cuit', 'mask' => '99-99999999-9', 
			'options' => ['class' => 'form-control criterio', 'maxlenght' => 13, 'disabled' => ($model->opcion != $valorOpcion('cuit')), 'id' => 'txCuit' . $id]]); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nombre responsable:', 'class' => 'check' . $id, 'data-target' => '#txNombreResponsable' . $id, 'value' => $valorOpcion('nombre_responsable'), 'uncheck' => null]); ?></td>
			<td></td>
			<td colspan="3"><?= Html::activeTextInput($model, 'nombre_responsable', ['id' => 'txNombreResponsable' . $id, 'maxlength' => 50, 'disabled' => ($model->opcion != $valorOpcion('nombre_responsable')), 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?></td>
			<td></td>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Ingresos brutos:', 'class' => 'check' . $id, 'data-target' => '#txIngresosBrutos' . $id, 'value' => $valorOpcion('ingresos_brutos'), 'uncheck' => null]); ?></td>
			<td></td>
			<td><?= Html::activeTextInput($model, 'ingresos_brutos', ['id' => 'txIngresosBrutos' . $id, 'disabled' => ($model->opcion != $valorOpcion('ingresos_brutos')), 'maxlength' => 11, 'class' => 'form-control criterio']); ?></td>
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
				'onclick' => 'clickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . str_replace('"',"'",$model['nombre']). '");',
				'ondblclick' => 'dobleClickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . str_replace('"',"'",$model['nombre']) . '");'
			];
		},

		'columns' =>
    	[
        	['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'', 'class' => 'grilla'], 'headerOptions' => ['class' => 'linkCabeceraBusquedaAvanzada' . $id]],
            ['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'num_nom','header' => 'Responsable', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'est','header' => 'Estado', 'contentOptions'=>['style'=>'', 'class' => 'grilla']]
         ],
    
		]);
		
		
	
	
	GridView::end();
	Pjax::end();
	?>
	
</div>


<script type="text/javascript">
function clickFilaBusquedaAvanzada<?= $id; ?>(codigoObjeto, nombreObjeto){
	
	$("#<?= $txCod; ?>").val(codigoObjeto);
	$("#<?= $txNom; ?>").val(nombreObjeto);
}

function dobleClickFilaBusquedaAvanzada<?= $id; ?>(codigoObjeto, nombreObjeto){
	
	clickFilaBusquedaAvanzada<?= $id; ?>(codigoObjeto, nombreObjeto);
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
		
		"tipoObjeto": "<?= $tipoObjeto; ?>",
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