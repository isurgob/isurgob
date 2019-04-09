<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

use app\utils\db\utb;

$selectorModal = isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '');
?>


<div class="ddjjanual-agregar">

	<?php
	Pjax::begin(['id' => 'pjaxAgregarAnual', 'enableReplaceState' => false, 'enablePushState' => false]);
	$form = ActiveForm::begin(['id' => 'formAgregarAnual', 'fieldConfig' => ['template' => '{input}'], 'validateOnSubmit' => false, 'action' => BaseUrl::toRoute(['agregaranual'])]);
	
	 
	if(Yii::$app->request->isPost && !$model->hasErrors()){
		?>
		<script type="text/javascript">
		$(document).ready(function(){
			$("<?= $selectorModal; ?>").modal("hidde");
		});
		</script>
		<?php
	}
	
	echo Html::input('hidden', 'selectorModal', $selectorModal, ['id' => 'agregarSelectorModal']);
	echo $form->field($model, 'obj_id')->input('hidden', ['id' => 'agregarCodigoObjeto'])->label(false);
	?>
	<div>
		<table border="0">
			<tr>
				<td><b>A&ntilde;o:</td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'anio')->textInput(['style' => 'width:40px;', 'maxlength' => 4, 'id' => 'agregarAnio'])->label(false); ?></td>
			</tr>
			
			<tr>
				<td><b>Fecha presenta:</td>
				<td width="5px"></td>
				<td><?= DatePicker::widget(['model' => $model, 'attribute' => 'fchpresenta', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:70px;', 'maxlength' => 10, 'id' => 'agregarFecha']]); ?></td>
			</tr>
			
			<tr>
				<td><b>Base anual:</td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'base')->textInput(['style' => 'width:70px;', 'maxlength' => 10, 'id' => 'agregarBase'])->label(false); ?></td>
			</tr>
			
		</table>
	</div>
	
	<div style="margin-top:5px;" class="text-center">
		<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'agregar();']); ?>
		&nbsp;
		<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("' . $selectorModal . '").modal("hide");']); ?>
	</div>
	
	<?php
	ActiveForm::end();
	
	echo $form->errorSummary($model, ['style' => 'margin-top:5px;']);
	Pjax::end();
	?>
</div>

<script type="text/javascript">
function agregar(){	
	
	var consulta = parseInt($("<?= $selectorModal; ?>").data("consulta"));
	var modificar = false;
	
	if(!isNaN(consulta) && consulta === 3) modificar = true;
	
	
	$.pjax.reload({
		container: "#pjaxAgregarAnual",
		url: "<?= BaseUrl::toRoute(['agregaranual', 'obj_id' => $model->obj_id]); ?>",
		type: "POST",
		replace: false,
		push: false,
		data: {
			"selectorModal": "<?= $selectorModal; ?>",
			"modificar" : modificar,
			"DdjjAnual" : {
				"obj_id": $("#agregarCodigoObjeto").val(),
				"anio": $("#agregarAnio").val(),
				"fchpresenta": $("#agregarFecha").val(),
				"base": $("#agregarBase").val()
			}
		}
	});
}
	

$("<?= $selectorModal; ?>").on("show.bs.modal", function(){
	
	var consulta = parseInt($("<?= $selectorModal; ?>").data("consulta"));
	console.log(consulta);
	if(!isNaN(consulta) && consulta === 3){
		
		//$("#modificar").prop("disabled", false);
		$("#agregarAnio").addClass("solo-lectura");
		$("#agregarAnio").attr("tabindex", -1);
	}
	else {
		
		$("#agregarAnio").val("<?= intval(date('Y')) - 1; ?>");
		$("#agregarFecha").val("<?= date('d/m/Y'); ?>");
		$("#agregarBase").val("");
		
		$("#agregarAnio").removeClass("solo-lectura");
		$("#agregarAnio").attr("tabindex", 0);
	}
});

</script>