<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\bootstrap\Modal;


use app\utils\db\utb;
use app\utils\db\Fecha;


$this->params['breadcrumbs'][]= ['label' => "Objeto $model->obj_id", 'url' => ['/objeto/cem/view', 'id' => $model->obj_id]];
$this->params['breadcrumbs'][]= 'Alquiler';
?>
<div class="cem-alquiler">

	<h1>Alquiler de cementerio</h1>
	<div class="separador-horizontal"></div>
	
	<?php
	$form= ActiveForm::begin(['fieldConfig' => ['template' => '{input}', 'options' => ['style' => 'display:inline-block;']], 'validateOnSubmit' => false, 'id' => 'formAlquiler']);
	?>
		
	<div class="form" style="padding:5px;">
	
		<h3><u><b>Datos del objeto</b></u></h3>
	
		<table border="0" width="100%">
			<tr>
				<td align="left">
					<?= $form->field($model, 'obj_id', ['template' => '{label}<br>{input}'])->textInput(['readonly' => true, 'style' => 'width:80px; background:#E6E6FA;'])->label('Objeto'); ?>				
				</td>
				<td width="5px"></td>
				<td align="left">
					<?= $form->field($modelObjeto, 'nombre', ['template' => '{label}<br>{input}'])->textInput(['maxlength' => 50, 'style' => 'width:590px'])->label('Nombre') ?>
				</td>
				<td width="5px"></td>
				<td align="left">
					<?= $form->field($modelObjeto, 'est_nom', ['template' => '{label}<br>{input}'])->textInput(['maxlength' => 20, 'readonly' => true, 'style' => 'width:75px;','class' => ($modelObjeto->est == 'B' ? 'form-control baja' : 'form-control solo-lectura')])->label('Estado') ?>
				</td>
			</tr>
		</table>	
	</div>
	
	<div class="form" style="padding:5px;">
	
		<h3><u><b>Datos de la parcela</b></u></h3>
		
		<table width="100%" border="0">
			<tr>
				<td><b>Tipo:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'tipo_nom')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1]); ?></td>
				<td width="10px">
				<td><b>Cuadro:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'cuadro_nom')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:100px;']); ?></td>
				<td width="10px">
				<td><b>Cuerpo:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'cuerpo_nom')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:80px;']); ?></td>
				<td width="10px">
				<td><b>Fila:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'fila')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:40px;']); ?></td>
				<td width="10px">
				<td><b>Nume:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'nume')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:40px;']); ?></td>
				<td width="10px">
				<td><b>Piso:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'piso')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:40px;']); ?></td>
			</tr>
		</table>		
	</div>
	
	<div class="form" style="padding:5px; margin-top:5px;">
		<table width="100%" border="0">
			<tr>
				<td><b>Responsable:</b></td>
				<td width="5px"></td>
				<td colspan="10">
					<?php
					echo $form->field($model, 'alquiler_codigo_responsable')->textInput(['onchange' => 'cambiaCodigoResponsable($(this).val());', 'id' => 'codigoResponsable', 'style' => 'width:80px;', 'maxlength' => 8]);
					
					Modal::begin([
						'id' => 'BuscaObjPersona',
						'header' => '<h1>Búsqueda de persona</h1>',
						'toggleButton' => ['label' => '<span class="glyphicon glyphicon-search"></span>', 'class' => 'bt-buscar'],
						'closeButton' => ['label' => '&times;', 'onclick' => '$("#BuscaObjPersona").modal("hide");', 'class' => 'btn btn-danger pull-right'],
						'size' => 'modal-lg'
					]);
					
					echo $this->render('//objeto/objetobuscarav', ['txCod' => 'codigoResponsable', 'txNom' => 'nombreResponsable', 'id' => 'Persona', 'tobjeto' => 3, 'selectorModal' => '#BuscaObjPersona']);
					
					
					Modal::end();
					
					echo $form->field($model, 'alquiler_nombre_responsable')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'nombreResponsable', 'style' => 'width:565px;']);
					?>
				</td>
			</tr>
			
			<tr>
				<td><b>T&iacute;tulo:</b></td>
				<td></td>
				<td><?= $form->field($model, 'alquiler_titulo')->textInput(['style' => 'width:200px;', 'maxlength' => 10]); ?></td>
				<td width="10px"></td>
				<td><b>Superficie:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'alquiler_superficie')->textInput(['style' => 'width:70px;', 'maxlength' => 8]); ?></td>
				<td width="10px"></td>
				<td><b>Fecha alquiler:</b></td>
				<td width="5px"></td>
				<td><?= DatePicker::widget(['model' => $model, 'attribute' => 'alquiler_fecha', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control solo-lectura', 'style' => 'width:70px;', 'tabindex' => -1, 'maxlength' => 10]]); ?></td>
				<td width="100px"></td>
			</tr>
			
			<tr>
				<td><b>Fecha inicio:</b></td>
				<td></td>
				<td><?= DatePicker::widget(['model' => $model, 'attribute' => 'alquiler_fecha_inicio', 'dateFormat' => 'dd/MM/yyyy', 
				'options' => ['class' => 'form-control', 'style' => 'width:70px;', 'maxlength' => 10, 'onchange' => 'actualizarFechaFin();', 'id' => 'fechaInicio']]);?></td>
				<td></td>
				<td><b>Duraci&oacute;n:</b></td>
				<td></td>
				<td><?= $form->field($model, 'alquiler_duracion')->textInput(['style' => 'width:70px;', 'maxlength' => 3, 'onchange' => 'actualizarFechaFin();', 'id' => 'duracion']); ?> <b>a&ntilde;o/s</b></td>
				<td></td>
				<td><b>Fecha fin:</b></td>
				<td></td>
				<td><?php
				
				if($model->alquiler_fecha_fin != null && $model->alquiler_fecha_fin != '')
					$model->alquiler_fecha_fin= Fecha::bdToUsuario($model->alquiler_fecha_fin);
				
				echo $form->field($model, 'alquiler_fecha_fin')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:70px;', 'maxlength' => 10, 'id' => 'fechaFin'])->label(false);?>
				<td></td>
			</tr>
		</table>
	</div>
	
	<div class="form" style="padding:5px; margin-top:5px;">
	
		<h3><u><b>Texto para contrato</b></u></h3>
		
		<?php
		
		$textos= utb::getAux('texto', 'texto_id', 'nombre', 0, 'tuso = 11');
		
		echo $form->field($model, 'alquiler_texto')->dropDownList($textos, ['disabled' => count($textos) === 0, 'style' => 'width:200px;', 'prompt' => '']); 
		?>
	</div>
	
	<div style="margin-top:5px;">
		<?= Html::submitButton('Grabar', ['class' => 'btn btn-success']); ?>
		&nbsp;
		<?= Html::a('Cancelar', ['view', 'id' => $model->obj_id], ['class' => 'btn btn-primary']); ?>
	</div>
	
	<?php
	ActiveForm::end();
	
	echo $form->errorSummary($model, ['style' => 'margin-top:5px;']);
	?>
</div>

<?php
Pjax::begin(['id' => 'pjaxResponsable', 'enableReplaceState' => false, 'enablePushState' => false]);

$codigoResponsable= trim(Yii::$app->request->get('codigoResponsable', ''));

if($codigoResponsable != ''){
	
	$codigoCompleto= utb::getObjeto(3, $codigoResponsable);
	$nombre= utb::getCampo('objeto', "obj_id = '$codigoCompleto'", 'nombre');
	
	if($nombre !== false){
		?>
		<script type="text/javascript">
		$(document).ready(function(){
			$("#codigoResponsable").val("<?= $codigoCompleto; ?>");
			$("#nombreResponsable").val("<?= $nombre; ?>");
		});
		</script>
		<?php
	}
}

echo Html::input('hidden', null, null);
Pjax::end();
?>


<script type="text/javascript">
function cambiaCodigoResponsable(nuevo){
	
	if(nuevo.length === 0){
		
		$("#nombreResponsable").val("");
		return;
	}
	
	$.pjax.reload({
		
		container: "#pjaxResponsable",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"codigoResponsable": nuevo
		}
	});
}

function actualizarFechaFin(){
	
	var inicio= $("#fechaInicio").val();
	
	inicio= inicio.substring(6, 10) + "/" + inicio.substring(3, 5) + "/" + inicio.substring(0, 2);
	
	var inicio= new Date(inicio);
	var duracion= parseInt($("#duracion").val());
	
	if(inicio && !isNaN(duracion) && duracion > 0){
		
		anio= inicio.getFullYear() + duracion;		
		fin= inicio.getDate() + "/" + (inicio.getMonth() + 1) + "/" + anio;
		$("#fechaFin").val(fin);
	}
}

$(document).ready(function(){
	
	<?php
	if(isset($model->codigo_alquiler) && $model->codigo_alquiler > 0){
		?>
		DesactivarForm('formAlquiler');
		<?php
	}
	?>
});
</script>