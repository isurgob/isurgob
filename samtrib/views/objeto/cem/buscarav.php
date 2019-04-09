<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\Pjax;
use yii\widgets\MaskedInput;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\data\Pagination;

use yii\jui\DatePicker;
use app\utils\db\utb;

$id = isset($id) ? $id : Yii::$app->request->post('idBusquedaAvanzada', 'busquedaAvanzadaComercio');
$txCod = isset($txCod) ? $txCod : Yii::$app->request->post('txCod', 'txCod');
$txNom = isset($txNom) ? $txNom : Yii::$app->request->post('txNom', 'txNom');
$selectorModal= isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', null);

echo Html::hiddenInput(null, null, ['id' => 'sort']);
?>
<?php
Pjax::begin(['id' => 'pjaxGrillaBusquedaAvanzada' . $id, 'enableReplaceState' => false, 'enablePushState' => false]);

echo Html::hiddenInput('tipoObjeto', 4, ['id' => 'busquedaAvanzadaTipoObjeto' . $id]);

$opciones= [
		$valorOpcion('nomenclatura') => 'Nomenclatura',
		$valorOpcion('nombre_fallecido') => 'Nombre fallecido',
		$valorOpcion('nombre_responsable') => 'Nombre responsable',
		
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
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('nomenclatura') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('nomenclatura') ?>">
				<table border="0">
					<td>
						<label>Tipo:</label>
						<?= Html::activeDropDownList($model, 'tipo', utb::getAux('cem_tipo'), ['id' => 'dlTipo' . $id, 'class' => 'form-control criterio', 'prompt' => '']); ?>
					</td>
					<td width="5px"></td>
					<td>
						<label>Cuadro:</label>
						<?= Html::activeDropDownList($model, 'cuadro', utb::getAux('cem_cuadro', 'cuadro_id'), ['id' => 'dlCuadro' . $id, 'class' => 'form-control criterio', 'prompt' => '', 'onchange' => "cambiaCuadro$id($(this).val());"]); ?>
					</td>
					<td width="5px"></td>
					<td>
						<label>Cuerpo:</label>
						<?= Html::activeDropDownList($model, 'cuerpo', utb::getAux('cem_cuerpo', 'cuerpo_id'), ['id' => 'dlCuerpo' . $id, 'class' => 'form-control criterio', 'prompt' => '']); ?>
					</td>
					<td width="5px"></td>
					<td width="100px">
						<label>Fila:</label>
						<?= Html::activeTextInput($model, 'fila', ['id' => 'txFila' . $id, 'maxlength' => 3, 'style' => 'width:46px;', 'class' => 'form-control criterio']); ?>
					</td>
					<td width="5px"></td>
					<td width="100px">
						<label>Nume:</label>
						<?= Html::activeTextInput($model, 'nume', ['id' => 'txNume' . $id, 'maxlength' => 3, 'style' => 'width:46px;', 'class' => 'form-control criterio']); ?>
					</td>
				</table>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('nombre_fallecido') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('nombre_fallecido') ?>">
				<?= Html::activeTextInput($model, 'nombre_fallecido', ['id' => 'txNombreFallecido' . $id, 'maxlength' => 50, 'class' => 'form-control criterio']); ?>
			</td>
			
			<td class="opcionBusquedaAvanzada<?= $id; ?> <?= $model->opcion != $valorOpcion('nombre_responsable') ? 'hidden' : null ?>" data-opcion="<?= $valorOpcion('nombre_responsable') ?>">
				<?= Html::activeTextInput($model, 'nombre_responsable', ['id' => 'txNombreResponsable' . $id, 'maxlength' => 50, 'class' => 'form-control criterio']); ?>
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
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nomenclatura:', 'class' => 'check' . $id, 'data-target' => '#dlTipo' . $id . ', #dlCuadro' . $id . ', #dlCuerpo' . $id . ', #txFila' . $id . ', #txNume' . $id, 'uncheck' => null, 'value' => $valorOpcion('nomenclatura')]); ?></td>
			<td></td>
			
			<td>
				<label>Tipo:</label>
				<?= Html::activeDropDownList($model, 'tipo', utb::getAux('cem_tipo'), ['id' => 'dlTipo' . $id, 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nomenclatura')), 'prompt' => '']); ?>
			</td>
			<td width="5px"></td>
			<td>
				<label>Cuadro:</label>
				<?= Html::activeDropDownList($model, 'cuadro', utb::getAux('cem_cuadro', 'cuadro_id'), ['id' => 'dlCuadro' . $id, 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nomenclatura')), 'prompt' => '', 'onchange' => "cambiaCuadro$id($(this).val());"]); ?>
			</td>
			<td width="5px"></td>
			<td>
				<label>Cuerpo:</label>
				<?= Html::activeDropDownList($model, 'cuerpo', utb::getAux('cem_cuerpo', 'cuerpo_id'), ['id' => 'dlCuerpo' . $id, 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nomenclatura')), 'prompt' => '']); ?>
			</td>
			<td width="5px"></td>
			<td width="100px">
				<label>Fila:</label>
				<?= Html::activeTextInput($model, 'fila', ['id' => 'txFila' . $id, 'maxlength' => 3, 'style' => 'width:46px;', 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nomenclatura'))]); ?>
			</td>
			<td width="5px"></td>
			<td width="100px">
				<label>Nume:</label>
				<?= Html::activeTextInput($model, 'nume', ['id' => 'txNume' . $id, 'maxlength' => 3, 'style' => 'width:46px;', 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nomenclatura'))]); ?>
			</td>
		</tr>
		
		<tr>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nombre fallecido:', 'class' => 'check' . $id, 'data-target' => '#txNombreFallecido' . $id, 'uncheck' => null, 'value' => $valorOpcion('nombre_fallecido')]); ?></td>
			<td></td>
			<td><?= Html::activeTextInput($model, 'nombre_fallecido', ['id' => 'txNombreFallecido' . $id, 'maxlength' => 50, 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nombre_fallecido'))]); ?></td>
		</tr>
		<tr>
			<td><?= Html::activeRadio($model, 'opcion', ['label' => 'Nombre responsable:', 'class' => 'check' . $id, 'data-target' => '#txNombreResponsable' . $id, 'unecheck' => null, 'value' => $valorOpcion('nombre_responsable')]); ?></td>
			<td></td>
			<td><?= Html::activeTextInput($model, 'nombre_responsable', ['id' => 'txNombreResponsable' . $id, 'maxlength' => 50, 'class' => 'form-control criterio', 'disabled' => ($model->opcion != $valorOpcion('nombre_responsable'))]); ?>
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
		
		'rowOptions' => function($model) use($id){
			return [
				'onclick' => 'clickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . $model['nombre'] . '");',
				'ondblclick' => 'dobleClickFilaBusquedaAvanzada' . $id . '("' . $model['obj_id'] . '", "' . $model['nombre'] . '");',
				'class' => 'grilla'
			];
		},

		'headerRowOptions' => ['class' => 'grilla'],

		'columns' => 		
    	[
        	['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'', 'class' => 'grilla'], 'headerOptions' => ['class' => 'linkCabeceraBusquedaAvanzada' . $id]],
            ['attribute'=>'cod_ant','header' => 'Cod. Ant.', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'cuit','header' => 'Cuit', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'cuadro_id','header' => 'Cuadro', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'cuerpo_id','header' => 'Cuerpo', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'tipo_nom','header' => 'Tipo', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'fila','header' => 'Fila', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'nume','header' => 'Nume', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'piso','header' => 'Piso', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'cat','header' => 'Cat', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'fchingreso','header' => 'Ingreso', 'format' => ['date', 'dd/MM/yyyy'], 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'fchvenc','header' => 'Vencim.', 'format' => ['date', 'dd/MM/yyyy'], 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
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
		
		"idBusquedaAvanzada": "<?= $id; ?>",
		"txCod": "<?= $txCod; ?>",
		"txNom": "<?= $txNom; ?>",
		"selectorModal": "<?= $selectorModal; ?>",
		
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