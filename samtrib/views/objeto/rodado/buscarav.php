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

$fechaHoy= date('Y/m/d');
echo Html::hiddenInput(null, null, ['id' => 'sort']);
?>
<?php
Pjax::begin(['id' => 'pjaxGrillaBusquedaAvanzada' . $id, 'enableReplaceState' => false, 'enablePushState' => false]);

echo Html::hiddenInput('tipoObjeto', 5, ['id' => 'busquedaAvanzadaTipoObjeto' . $id]);

$opciones= [
		$valorOpcion('nombre_responsable') => 'Nombre responsable',
		$valorOpcion('numero_motor') => 'Nº motor',
		$valorOpcion('dominio') => 'Dominio',
		$valorOpcion('numero_chasis') => 'Nº chasis',
		$valorOpcion('fecha_compra') => 'Fecha compra'
		
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
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('nombre_responsable') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('nombre_responable') ?>">
				<?= Html::activeTextInput($model, 'nombre_responsable', ['id' => 'txNombreResponsable' . $id, 'maxlength' => 50, 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('numero_motor') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('numero_motor') ?>">
				<?= Html::activeTextInput($model, 'numero_motor', ['id' => 'txNumeroMotor' . $id, 'maxlength' => 30, 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('dominio') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('dominio') ?>">
				<?= Html::activeTextInput($model, 'dominio', ['id' => 'txDominio' . $id, 'maxlength' => 9, 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('numero_chasis') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('numero_chasis') ?>">
				<?= Html::activeTextInput($model, 'numero_chasis', ['id' => 'txNumeroChasis' . $id, 'maxlength' => 30, 'style' => 'width:100%;', 'class' => 'form-control criterio']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('fecha_compra') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('fecha_compra') ?>">
				<label>Desde &nbsp;</label>
				<?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha_compra_desde', 'id' => 'txFechaCompraDesde' . $id, 'dateFormat' => 'dd/MM/yyyy', 'value' => $fechaHoy, 'options' => ['class' => 'form-control criterio', 'style' => 'width:70px;', 'maxlength' => 10]]); ?>
				<label>Hasta &nbsp;</label>
				<?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha_compra_hasta', 'id' => 'txFechaCompraHasta' . $id, 'dateFormat' => 'dd/MM/yyyy', 'value' => $fechaHoy, 'options' => ['class' => 'form-control criterio', 'style' => 'width:70px;', 'maxlength' => 10]]); ?>
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
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nombre responsable:', 'class' => 'check' . $id, 'data-target' => '#txNombreResponsable' . $id, 'uncheck' => null, 'value' => $valorOpcion('nombre_responsable')]); ?></td>
			<td></td>
			<td colspan="3"><?= Html::activeTextInput($model, 'nombre_responsable', ['id' => 'txNombreResponsable' . $id, 'maxlength' => 50, 'style' => 'width:100%;', 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nombre_responsable'))]); ?></td>
			<td width="20px"></td>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nº motor:', 'class' => 'check' . $id, 'data-target' => '#txNumeroMotor' . $id, 'uncheck' => null, 'value' => $valorOpcion('numero_motor')]); ?></td>
			<td></td>
			<td colspan="3"><?= Html::activeTextInput($model, 'numero_motor', ['id' => 'txNumeroMotor' . $id, 'maxlength' => 30, 'style' => 'width:100%;', 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('numero_motor'))]); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Dominio:', 'class' => 'check' . $id, 'data-target' => '#txDominio' . $id, 'uncheck' => null, 'value' => $valorOpcion('dominio')]); ?></td>
			<td></td>
			<td colspan="3"><?= Html::activeTextInput($model, 'dominio', ['id' => 'txDominio' . $id, 'maxlength' => 9, 'style' => 'width:100%;', 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('dominio'))]); ?></td>
			<td width="20px"></td>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nº chasis:', 'class' => 'check' . $id, 'data-target' => 'txNumeroChasis' . $id, 'uncheck' => null, 'value' => $valorOpcion('numero_chasis')]); ?></td>
			<td></td>
			<td colspan="3"><?= Html::activeTextInput($model, 'numero_chasis', ['id' => 'txNumeroChasis' . $id, 'maxlength' => 30, 'style' => 'width:100%;', 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('numero_chasis'))]); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Fecha compra:', 'class' => 'check' . $id, 'data-target' => '#txFechaCompraDesde' . $id .', #txFechaCompraHasta' . $id, 'uncheck' => null, 'value' => $valorOpcion('fecha_compra')]); ?></td>
			<td width='5px'></td>
			<td>
				<label>Desde &nbsp;</label>
				<?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha_compra_desde', 'id' => 'txFechaCompraDesde' . $id, 'dateFormat' => 'dd/MM/yyyy', 'value' => $fechaHoy, 'options' => ['class' => 'form-control criterio', 'style' => 'width:70px;', 'maxlength' => 10, 'disabled' => ($model->opcion != $valorOpcion('fecha_compra'))]]); ?>
			</td>
			<td></td>
			<td>
				<label>Hasta &nbsp;</label>
				<?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha_compra_hasta', 'id' => 'txFechaCompraHasta' . $id, 'dateFormat' => 'dd/MM/yyyy', 'value' => $fechaHoy, 'options' => ['class' => 'form-control criterio', 'style' => 'width:70px;', 'maxlength' => 10, 'disabled' => ($model->opcion != $valorOpcion('fecha_compra'))]]); ?>
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
				'onclick' => 'clickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . $model['nombre'] . '");',
				'ondblclick' => 'dobleClickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . $model['nombre'] . '");'
			];
		},

		'columns' => 		
    	[
        	['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'', 'class' => 'grilla'], 'headerOptions' => ['class' => 'linkCabeceraBusquedaAvanzada' . $id]],
            ['attribute'=>'num_nom','label' => 'Responsable', 'contentOptions'=>['style'=>'width:200px;', 'class' => 'grilla'], 'headerOptions' => ['class' => 'linkCabeceraBusquedaAvanzada' . $id]],
            ['attribute'=>'cat_nom','header' => 'Categoría', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'marca_nom','header' => 'Marca', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'modelo_nom','header' => 'Modelo', 'contentOptions'=>['style'=>'width:160px;', 'class' => 'grilla']],
            ['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'dominio','header' => 'Dominio', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'nromotor','header' => 'Nº Motor', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'nrochasis','header' => 'Nº Chasis', 'contentOptions'=>['style'=>'', 'class' => 'grilla']]
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