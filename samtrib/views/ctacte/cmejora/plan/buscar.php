<?php

use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\BaseUrl;

use app\utils\db\utb;
?>


<?php
Pjax::begin(['id' => 'pjaxBuscar', 'enableReplaceState' => false, 'enablePushState' => false, 'formSelector' => '#formBusquedaIndividual']);
$form = ActiveForm::begin(['id' => 'formBusquedaIndividual', 'options' => ['data-pjax' => 'true'], 'action' => ['buscar']]);

$selectorModal = isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '');

echo Html::input('hidden', 'selectorModal', $selectorModal);
?>

<div class="individual-buscar">

	<table width="100%" border="0">
		
		<tr>
			<td><?= Html::radio('tipoBusqueda', true, ['label' => 'Número de Plan:', 'onclick' => 'habilitarBuscar();', 'id' => 'ckCodigoPlan', 'value' => 'codigoPlan']) ?></td>
			<td width="5px"></td>
			<td><?= Html::textInput('buscarCodigoPlan', null, ['class' => 'form-control', 'id' => 'buscarCodigoPlan', 'style' => 'width:80px;', 'maxlength' => 8]); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::radio('tipoBusqueda', false, ['label' => 'Código objeto:', 'onclick' => 'habilitarBuscar();', 'id' => 'ckCodigoObjeto', 'value' => 'codigoObjeto']); ?></td>
			<td></td>
			<td><?= Html::textInput('buscarCodigoObjeto', null, ['class' => 'form-control', 'disabled' => true, 'id' => 'buscarCodigoObjeto', 'style' => 'width:80px;', 'maxlength' => 8]); ?></td>
		</tr>
	</table>
	<div class="text-center" style="margin-top:5px;">
		<?= Html::submitButton('Aceptar', ['class' => 'btn btn-success']); ?>
		&nbsp;
		<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("' . $selectorModal . '").modal("hide");']); ?>
	</div>
</div>

<?php
ActiveForm::end();
Pjax::end();
?>

<script type="text/javascript">
function habilitarBuscar(){
	
	$("#buscarCodigoPlan").prop("disabled", !$("#ckCodigoPlan").is(":checked"));
	$("#buscarCodigoObjeto").prop("disabled", !$("#ckCodigoObjeto").is(":checked"));
}
</script>