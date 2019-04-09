<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

use app\utils\db\utb;

$selectorModal = isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '');
$condicionTributo = 'tipo = 2 And tobj = 2 And dj_tribprinc = trib_id';
?>

<div class="ddjj-anual-generar">

	<?php
	Pjax::begin(['id' => 'pjaxGenerar', 'enableReplaceState' => false, 'enablePushState' => false, 'formSelector' => '#formGenerar']);
	$form = ActiveForm::begin(['id' => 'formGenerar', 'fieldConfig' => ['template' => '{input}'], 'validateOnSubmit' => false, 'action' => BaseUrl::toRoute(['generaranual', 'obj_id' => $model->obj_id])]);

	echo Html::input('hidden', 'selectorModal', $selectorModal);
	?>
	<div>
		<table border="0">
			<tr>
				<td><b>A&ntilde;o:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'anio')->textInput(['style' => 'width:40px;', 'value' => (intval(date('Y')) - 1), 'id' => 'generarAnio'])->label(false); ?></td>
			</tr>

			<tr>
				<td><b>Tributo:</b></td>
				<td></td>
				<td><?= $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib', 'trib_id', 'nombre_redu', 0, $condicionTributo), ['style' => 'width:220px;', 'id' => 'generarCodigoTributo'])->label(false); ?></td>
			</tr>

			<tr>
				<td colspan="3"><?= $form->field($model, 'sinanual')->checkbox(['label' => 'Sólo si no tiene DJ anual', 'value' => 1, 'uncheck' => 0, 'id' => 'generarSinAnual'])->label(false); ?></td>
			</tr>
		</table>
	</div>

	<div class="text-center">
		<?= Html::submitButton('Aceptar', ['class' => 'btn btn-success']); ?>
		&nbsp;
		<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("' . $selectorModal . '").modal("hide");']) ?>
	</div>

	<?php
	ActiveForm::end();

	echo $form->errorSummary($model);
	Pjax::end();
	?>
</div>

<script type="text/javascript">
function generar(){

	$$.pjax.reload({
		container : "#pjaxGenerar",
		type : "POST",
		replace : false,
		push : false,
		data : {
			"selectorModal" : "<?= $selectorModal; ?>",
			"DdjjAnual" : {
				"anio" : $("#generarAnio").val(),
				"trib_id" : $("#generarCodigoTributo").val(),
				"sinanual" : $("#generarSinAnual").val(),
				"obj_id" : "<?= $model->obj_id ?>"
			}
		}
	});
}


$("#formAgregarAnual").submit(function(e){
	
	e.preventDefault();
	$.pjax.defaults.push = false;
	$.pjax.submit(e, "#pjaxGenerar");
	$.pjax.defaults.push = true;

	return false;
});

$("#document").ready(function(){

	$("<?= $selectorModal ?>").on("show.bs.modal", function(){
		$("#generarSinAnual").prop("checked", false);
	});
});
</script>
