<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;


$selectorModal = isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '');
?>

<div class="ajustes-nueva-cuenta">

	<div>
		<?php
		Pjax::begin(['formSelector' => '#formNuevaCuenta', 'id' => 'pjaxFormNuevaCuenta', 'enableReplaceState' => false, 'enablePushState' => false]);
		
		if(Yii::$app->request->isPost && !$extras['model']->hasErrors()){
			?>
			<script type="text/javascript">
			$(document).ready(function(){
				cerrarModal();
			});
			</script>
			<?php
		}
		
		
		$form = ActiveForm::begin(['fieldConfig' => ['template' => '{input}'], 'id' => 'formNuevaCuenta', 'action' => BaseUrl::toRoute(['nuevacuenta']), 'options' => ['data-pjax' => 'true']]);

		echo Html::input('hidden', 'selectorModal', $selectorModal);
		?>
		<table border="0" width="100%">
		
			<tr>
				<td><b>Cuenta:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($extras['model'], 'cta_id', ['options' => ['style' => 'width:50px;']])->textInput(['id' => 'txCodigoCuenta', 'style' => 'width:50px', 'maxlength' => 4])->label(false); ?></td>
				<td><?= Html::button('<i class="glyphicon glyphicon-search"></i>', ['onclick' => '$("#busquedaCuenta").toggleClass("hidden")', 'class' => 'bt-buscar']); ?></td>
				<td colspan="4"><?= $form->field($extras['model'], 'cta_nom', ['options' => ['style' => 'margin-bottom:0;']])->textInput(['id' => 'txNombreCuenta', 'style' => 'width:400px', 'class' => 'form-control solo-lectura', 'tabindex' => -1])->label(false) ?></td>
			</tr>
			
			
			<tr>
				<td><b>Debe:</b></td>
				<td></td>
				<td><?= $form->field($extras['model'], 'debe', ['options' => ['style' => 'width:50px;']])->textInput(['id' => 'debe', 'style' => 'width:50px;', 'maxlength' => 8])->label(false) ?></td>
				<td width="10px"></td>
				<td><b>Haber</b></td>
				<td width="5px"></td>
				<td><?= $form->field($extras['model'], 'haber')->textInput(['id' => 'haber', 'style' => 'width:50px;', 'maxlength' => 8])->label(false); ?></td>
				<td width="300px"></td>
			</tr>
			
			<tr>
				<td colspan="3"><?= Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'grabarCuenta();']); ?></td>
				<td></td>
				<td colspan="3"><?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'cerrarModal();']) ?></td>
			</tr>
		</table>
		
		<?php
		ActiveForm::end();
		
		echo $form->errorSummary($extras['model'], ['id' => 'erroresNuevaCuenta']);
		
		Pjax::end();
		?>
	</div>
	
	
	
	<div id="busquedaCuenta" class="hidden form" style="padding:5px; margin-top:5px;">
	
		<h3><b>B&uacute;squeda de cuenta</b></h3>
	<?php
	echo $this->render('//config/cuentapartida/buscarav', ['id' => '2', 'selectorDiv' => '#busquedaCuenta', 'selectorCodigo' => '#txCodigoCuenta', 'selectorNombre' => '#txNombreCuenta', 'condicion' => 'tcta = 1']);
	?>
	</div>
</div>

<script type="text/javascript">
function cerrarModal(){
	$("<?= $selectorModal; ?>").modal("hide");
}

function grabarCuenta(){
	
	var datos = {
		
		"selectorModal" : "<?= $selectorModal ?>",
		"Ajustes" :{
			
			"cta_id" : $("#txCodigoCuenta").val(),
			"cta_nom" : $("#txNombreCuenta").val(),
			"debe" : $("#debe").val(),
			"haber" : $("#haber").val()
		}		
	};

	$.pjax.reload({
		container : "#pjaxFormNuevaCuenta",
		url : "<?= BaseUrl::toRoute(['nuevacuenta']) ?>",
		type : "POST",
		replace : false,
		push : false,
		data : datos
	});
}

$("<?= $selectorModal ?>").on("show.bs.modal", function(){
	
	$("#erroresNuevaCuenta").css("display", "none");
	$("#erroresNuevaCuenta ul").empty();
	
	$("<?= $selectorModal ?>").off("show.bs.modal");
});
</script>