<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;


$titulo = 'Nuevo texto';

switch($extras['consulta']){
	
	case 1: $titulo = 'Consulta texto'; break;
	case 2: $titulo = 'Eliminar texto'; break;
	case 3: $titulo = 'Modificar texto'; break;
}

$this->params['breadcrumbs'][] = ['label' => 'Configuración', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Definición de texto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $titulo;
?>
<script type="text/javascript">
function agregarVariable(variable){
	
	var posicionCursor = $("#detalle").prop("selectionStart");
	
	var textoAnterior = $("#detalle").val().substr(0, posicionCursor);
	
	var longitud = $("#detalle").val().length;
	var textoPosterior = $("#detalle").val().substr(posicionCursor, longitud);
	
	$("#detalle").val(textoAnterior + variable + " " + textoPosterior);
	colocarCursor(posicionCursor + variable.length + 1);
}

function colocarCursor(posicion){
	
	$("#detalle").focus();
	$("#detalle")[0].selectionStart = posicion;
	$("#detalle")[0].selectionEnd = posicion;

}

function cambiaUso(nuevo){
	
	nuevo = parseInt(nuevo);
	var anterior = parseInt($("#usoAnterior").val());
	
	if(isNaN(anterior)) anterior = -1;
	
	if(anterior > 0 && anterior !== nuevo && hayVariables($("#detalle").val())){
		$("#modalConfirmacion").modal("show");
		return;
	}
	
	$("#usoAnterior").val(nuevo);
	
	$.pjax.reload({
		container : "#pjaxVariables",
		push : false,
		replace : false,
		type : "GET",
		data : {
			"tuso" : nuevo
		}
	});
}

function hayVariables(texto){
	
	//detecta todas las palabras que comienzan con arroba
	var expresion = /@+\w*/g;
	return texto.search(expresion) > -1;
}

function cancelarEliminarVariables(){
	
	//se oculta el modal y se vuelve atras
	$("#modalConfirmacion").modal("hide");
	
	$("#uso").val($("#usoAnterior").val());
}

function confirmarEliminarVariables(){
	
	var expresion = /@+\w*/g;
	
	var detalle = $("#detalle").val();
	
	//se eliminan las variables del detalle
	while(hayVariables(detalle)) detalle = detalle.replace(expresion, '');
	
	$("#detalle").val(detalle);
	
	//se oculta el modal y se cargan las nuevas variables
	$("#modalConfirmacion").modal("hide");
	$("#usoAnterior").val($("#uso").val());
	cambiaUso($("#uso").val());
}
</script>

<?php
Modal::begin([
	'id' => 'modalConfirmacion',
	'size' => 'modal-sm',
	'header' => '<h1><b>Eliminar variables</b></h1>',
	'toggleButton' => false,
	'closeButton' => false,
	'header' => Html::button('&times;', ['class' => 'btn btn-sm btn-danger pull-right', 'onclick' => 'cancelarEliminarVariables()']),
	'footer' => '<div class="text-center">' . (Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'confirmarEliminarVariables();']) . '&nbsp;&nbsp;&nbsp;' . Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'cancelarEliminarVariables();']) . '</div>')
]);

echo '<label>Se han detectado variables en el detalle, ¿desea eliminarlas?.</label>';

Modal::end();
?>

<style rel="stylesheet">.asd td{border:1px solid;}</style>
<div class="texto-form">

	<?= Html::input('hidden', null, 0, ['id' => 'posicionCursor', 'value' => 0]) ?>

	<h1><?= $titulo . ' ' . $extras['model']->texto_id ?></h1>
	<div style="border-bottom:1px solid #DDDDDD; margin-bottom:10px;"></div>
	
    <?php 
    $form = ActiveForm::begin(['id' => 'formTexto', 'fieldConfig' => ['template' => '{label}{input}']]);
    
    echo Html::input('hidden', null, $extras['model']->tuso, ['id' => 'usoAnterior']); 
    ?>
    
    <div class="form" style="padding:10px;">
    	<table borde="0" width="100%">
    		<tr>
    			<td><label>Cod.:</label></td>
    			<td width="5px"></td>
    			<td><?= $form->field($extras['model'], 'texto_id')->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:50px;'])->label(false) ?></td>
    			<td width="10px"></td>
    			<td><label>Uso:</label></td>
    			<td width="5px"></td>
    			<td><?= $form->field($extras['model'], 'tuso')->dropDownList($extras['textosModificables'], ['style' => 'width:200px;', 'id' => 'uso', 'onchange' => 'cambiaUso($(this).val());', 'prompt' => ''])->label(false); ?></td>
    			<td width="10px"></td>
    			<td><label>Nombre:</label></td>
    			<td width="5px"></td>
    			<td><?= $form->field($extras['model'], 'nombre')->textInput(['id' => 'nombre', 'style' => 'width:300px;'])->label(false) ?></td>
    			<td width="45px"></td>
    		</tr>
    	</table>
    </div>

	<div class="form" style="padding:10px; margin-top:10px">
		<table border="0" width="100%">
			<tr>
				<td><?= $form->field($extras['model'], 'titulo', ['template' => '{label}{input}'])->textInput(['id' => 'titulo', 'style' => 'width:50%;', 'maxlength' => 50])->label('Título: ') ?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-right:5px; padding-left:5px;"><?= $form->field($extras['model'], 'detalle')->textarea(['style' => 'width:100%; height:100%; max-width:595px; max-height:400px;', 'rows' => 30, 'id' => 'detalle'])->label('Detalle'); ?></td>
				<td width="5px"></td>
				<td width="20%" valign="top">
					<label>Variables</label>
					<?php
					
					Pjax::begin(['id' => 'pjaxVariables', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'height:100%;']]);
					echo Html::listBox(null, null, $extras['variables'], 
					['class' => 'form-control', 'ondblclick' => 'agregarVariable($(this).find(":selected").text());', 'style' => 'height:100%; width:100%;', 'id' => 'variables', 'size' => count($extras['variables']),
					'disabled' => ($extras['consulta'] === 1 || $extras['consulta'] === 2)]);
					Pjax::end();
					?>
					
				</td>
			</tr>
		</table>
	</div>
	
	<div style="margin-top:10px;">
	<?php	
	if($extras['consulta'] !== 1){

		$clase = $extras['consulta'] === 2 ? 'btn btn-danger' : 'btn btn-success';
		echo Html::submitButton('Grabar', ['class' => $clase]);
		echo '&nbsp;&nbsp;&nbsp;';
		
		if($extras['consulta'] === 0)
			echo Html::a('Cancelar', ['index', 'tuso' => $extras['tuso']], ['class' => 'btn btn-primary']);
		else
			echo Html::a('Cancelar', ['view', 'id' => $extras['model']->texto_id, 'tuso' => $extras['tuso']], ['class' => 'btn btn-primary']);
		
	} else{
		echo Html::a('Nuevo', ['create'], ['class' => 'btn btn-success']);
		echo '&nbsp;&nbsp;&nbsp;';
		echo Html::a('Modificar', ['update', 'id' => $extras['model']->texto_id, 'tuso' => $extras['tuso']], ['class' => 'btn btn-primary']);
		echo '&nbsp;&nbsp;&nbsp;';
		echo Html::a('Borrar', ['delete', 'id' => $extras['model']->texto_id, 'tuso' => $extras['tuso']], ['class' => 'btn btn-danger']);
		echo '&nbsp;&nbsp;&nbsp;';
		echo Html::a('Volver', ['index', 'tuso' => $extras['tuso'], 'tuso' => $extras['tuso']], ['class' => 'btn btn-primary']);
	}
	?>
	</div>

    <?php     
    ActiveForm::end();
    
    echo $form->errorSummary($extras['model'], ['style' => 'margin-top:10px;']);
    
    if(isset($mensaje) && trim($mensaje) != ''){
    	
    	echo Alert::widget([
			'options' => [
				'class' => 'alert alert-success alert-dissmissible',
				'style' => 'margin-top:5px'
			],
			'id' => 'alertaMensaje',
    		'body' => $mensaje
    	]);
    } 
    ?>
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	<?php
	if($extras['consulta'] === 1 || $extras['consulta'] === 2){
	?>
	DesactivarFormPost("formTexto");
	<?php
	} else if($extras['consulta'] === 3){
	?>	
	
		DesactivarFormPost("formTexto");
		$("#nombre").prop("readonly", false);
		$("#titulo").prop("readonly", false);
		$("#detalle").prop("readonly", false);
		$("#variables").prop("disabled", false);
	<?php
	}
	?>
	
	<?php
	if($extras['model']->tuso != null){
	?>
	cambiaUso(<?= $extras['model']->tuso ?>);
	<?php
	}
	?>
	
	setTimeout(function(){$("#alertaMensaje").css("display", "none");}, 5000);
});
</script>
