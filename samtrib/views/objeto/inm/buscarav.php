<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\data\Pagination;

$id = isset($id) ? $id : Yii::$app->request->post('idBusquedaAvanzada', 'busquedaAvanzadaComercio');
$txCod = isset($txCod) ? $txCod : Yii::$app->request->post('txCod', 'txCod');
$txNom = isset($txNom) ? $txNom : Yii::$app->request->post('txNom', 'txNom');
$selectorModal= isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', null);

echo Html::hiddenInput(null, null, ['id' => 'sort']);

Pjax::begin(['id' => 'pjaxGrillaBusquedaAvanzada' . $id, 'enableReplaceState' => false, 'enablePushState' => false]);


echo Html::hiddenInput('tipoObjeto', 1, ['id' => 'busquedaAvanzadaTipoObjeto' . $id]);

$opciones= [
		$valorOpcion('nombre') => 'Nombre',
		$valorOpcion('documento') => 'DNI/CUIT',
		$valorOpcion('nomenclatura') => 'Nomenclatura',
		$valorOpcion('partida_provincial') => 'Part. Prov'
		
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
				<?= Html::activeTextInput($model, 'nombre', ['id' => 'txNombre' . $id, 'class' => 'form-control criterio', 'maxlength' => 50, 'style' => 'width:100%;']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('documento') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('documento') ?>">
				<?= Html::activeTextInput($model, 'documento', ['id' => 'txDocumento' . $id, 'class' => 'form-control criterio', 'maxlength' => 8, 'style' => 'width:100px;', 'onkeypress' => 'justNumbers(event)']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('nomenclatura') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('nomenclatura') ?>">
				<table>
					<tr>
						<td>
							<label style="font-size:11px;"><?= $model->s1['nombre'] ?></label>
							<br>
							<?= Html::activeTextInput($model, 's1_valor', ['id' => 'txS1' . $id, 'class' => 'form-control criterio', 'maxlength' => $model->s1['max_largo'], 'style' => 'width:50px;']); ?>
						</td>
						<td width="5px"></td>
						<td>
							<label style="font-size:11px;"><?= $model->s2['nombre'] ?></label>
							<br>
							<?= Html::activeTextInput($model, 's2_valor', ['id' => 'txS2' . $id, 'class' => 'form-control criterio', 'maxlength' => $model->s2['max_largo'], 'style' => 'width:50px;']); ?>
						</td>
						<td width="5px"></td>
						<td>
							<label style="font-size:11px;"><?= $model->s3['nombre'] ?></label>
							<br>
							<?= Html::activeTextInput($model, 's3_valor', ['id' => 'txS3' . $id, 'class' => 'form-control criterio', 'maxlength' => $model->s3['max_largo'], 'style' => 'width:50px;']); ?>
						</td>
						<td width="5px"></td>
						<td>
							<label style="font-size:11px;"><?= $model->manzana['nombre'] ?></label>
							<br>
							<?= Html::activeTextInput($model, 'manzana_valor', ['id' => 'txManzana' . $id, 'class' => 'form-control criterio', 'maxlength' => $model->manzana['max_largo'], 'style' => 'width:50px;']); ?>
						</td>
					</tr>
				</table>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('partida_provincial') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('partida_provincial') ?>">
				<?= Html::activeTextInput($model, 'partida_provincial', ['class' => 'form-control criterio', 'id' => 'txPartidaProvincial' . $id, 'maxlength' => 8, 'style' => 'width:100px;']); ?>
			</td>
			
			<td width="15px"></td>
			
			<td>
				<?= Html::Button('Buscar',['class' => 'btn btn-primary', 'onClick' => 'buscar' . $id . '();'])?>
			</td>
		</tr>
	</table>
<!--
	<table border='0'>
		<tr>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nombre:', 'class' => 'check' . $id, 'data-target' => '#txNombre' . $id, 'value' => $valorOpcion('nombre'), 'uncheck' => null]); ?></td>
			<td width='5px'></td>
			<td><?= Html::activeTextInput($model, 'nombre', ['id' => 'txNombre' . $id, 'class' => 'form-control criterio', 'maxlength' => 50, 'style' => 'width:100%;', 'disabled' => ($model->opcion != $valorOpcion('nombre'))]); ?></td>
			<td width="20px"></td>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'DNI/CUIT:', 'class' => 'check' . $id, 'data-target' => '#txDocumento' . $id, 'value' => $valorOpcion('documento'), 'uncheck' => null, 'maxlength' => 13]); ?></td>
			<td width='5px'></td>
			<td><?= Html::activeTextInput($model, 'documento', ['id' => 'txDocumento' . $id, 'class' => 'form-control criterio', 'maxlength' => 8, 'style' => 'width:100px;', 'onkeypress' => 'justNumbers(event)', 'disabled' => ($model->opcion != $valorOpcion('documento'))]); ?></td>
		</tr>
	
		<tr>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nomenclatura:', 'class' => 'check' . $id, 'data-target' => "#txS1$id, #txS2$id, #txS3$id, #txManzana$id", 'value' => $valorOpcion('nomenclatura'), 'uncheck' => null]); ?></td>
			<td></td>
			<td>
				<table>
					<tr>
						<td>
							<label style="font-size:11px;"><?= $model->s1['nombre'] ?></label>
							<br>
							<?= Html::activeTextInput($model, 's1_valor', ['id' => 'txS1' . $id, 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nomenclatura')), 'maxlength' => $model->s1['max_largo'], 'style' => 'width:50px;']); ?>
						</td>
						<td width="5px"></td>
						<td>
							<label style="font-size:11px;"><?= $model->s2['nombre'] ?></label>
							<br>
							<?= Html::activeTextInput($model, 's2_valor', ['id' => 'txS2' . $id, 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nomenclatura')), 'maxlength' => $model->s2['max_largo'], 'style' => 'width:50px;']); ?>
						</td>
						<td width="5px"></td>
						<td>
							<label style="font-size:11px;"><?= $model->s3['nombre'] ?></label>
							<br>
							<?= Html::activeTextInput($model, 's3_valor', ['id' => 'txS3' . $id, 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nomenclatura')), 'maxlength' => $model->s3['max_largo'], 'style' => 'width:50px;']); ?>
						</td>
						<td width="5px"></td>
						<td>
							<label style="font-size:11px;"><?= $model->manzana['nombre'] ?></label>
							<br>
							<?= Html::activeTextInput($model, 'manzana_valor', ['id' => 'txManzana' . $id, 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nomenclatura')), 'maxlength' => $model->manzana['max_largo'], 'style' => 'width:50px;']); ?>
						</td>
					</tr>
				</table>
			</td>
		
			<td></td>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Part. Prov.:', 'class' => 'check' . $id, 'data-target' => '#txPartidaProvincial' . $id, 'value' => $valorOpcion('partida_provincial'), 'uncheck' => null]); ?>
			<td width='5px'></td>
			<td><?= Html::activeTextInput($model, 'partida_provincial', ['class' => 'form-control criterio', 'id' => 'txPartidaProvincial' . $id, 'maxlength' => 8, 'style' => 'width:100px;', 'disabled' => ($model->opcion != $valorOpcion('partida_provincial'))]); ?></td>
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
				'onclick' => 'clickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . $model['nombre'] . '");',
				'ondblclick' => 'dobleClickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . $model['nombre'] . '");'
			];
		},

		'columns' => 		
    	[
        	['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=> ['style'=>'width:60px', 'class' => 'grilla'], 'headerOptions' => ['class' => 'linkCabeceraBusquedaAvanzada' . $id]],
            ['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'width:220px', 'class' => 'grilla'], 'headerOptions' => ['class' => 'linkCabeceraBusquedaAvanzada' . $id]],
            ['attribute'=>'nc','label' => 'Nomencl.', 'contentOptions'=>['style'=>'width:80px', 'class' => 'grilla']],
            ['attribute'=>'dompar_dir','label' => 'Dom. Parc.', 'contentOptions'=>['style'=>'width:250px', 'class' => 'grilla']],
            ['attribute'=>'ndoc','label' => 'DNI/CUIT', 'contentOptions'=>['style'=>'width:80px', 'class' => 'grilla']],
            ['attribute'=>'est_nom','label' => 'Estado', 'contentOptions'=>['style'=>'width:60px', 'class' => 'grilla']]
         ],
    
		]);

	GridView::end();
	Pjax::end();
	?>
	
</div>

<script>
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
	if(isNaN(pagina) || pagina < 0) pagina= 0;
	
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