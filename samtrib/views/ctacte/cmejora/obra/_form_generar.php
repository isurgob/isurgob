<?php

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\jui\DatePicker;

use app\utils\db\utb;

$selectorModal = isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '');

$codigoObra = isset($codigoObra) ? $codigoObra : $modelCuadra->obra_id;
$codigoCuadra = isset($codigoCuadra) ? $codigoCuadra : $modelCuadra->cuadra_id;
?>
<?php
Pjax::begin(['id' => 'pjaxGenerar', 'formSelector' => '#formGenerar', 'enableReplaceState' => false, 'enablePushState' => false]);
$form = ActiveForm::begin(['id' => 'formGenerar', 'validateOnSubmit' => false, 'options' => ['data-pjax' => 'true'], 'fieldConfig' => ['template' => '{input}']]);

echo Html::input('hidden', 'selectorModal', $selectorModal);
?>
<div class="individual-vencimiento">

	<table border="0" width="100%">
		
		<tr>
			<td align="left"><b>Obra:</b></td>
			<td width="5px"></td>
			<td><?= $form->field($modelCuadra, 'obra_id')
			->dropDownList(utb::getAux('mej_obra', 'obra_id', 'nombre',0,"est='A'"), ['prompt' => '', 'onchange' => 'cambiaObraGenerar($(this).val());', 'style' => 'width:200px;', 'id' => 'codigoObraGenerar'])
			->label(false); ?></td>
		</tr>
		<tr>
			<td align="left"><b>Cuadra:</b></td>
			<td width="5px"></td>
			<td><?php
			
			Pjax::begin(['id' => 'pjaxObraGenerar', 'enableReplaceState' => false, 'enablePushState' => false]);
			$codigoObra = intval(Yii::$app->request->get('obra', 0));
			$cuadras = [];
			
			if($codigoObra > 0)
				$cuadras = utb::getAux('v_mej_cuadra', 'cuadra_id', "calle_nom || ' - ' || ncm", 0, "obra_id = $codigoObra");
			
			$cantidad = count($cuadras);
			echo $form->field($modelCuadra, 'cuadra_id')->dropDownList($cuadras, ['prompt' => ($cantidad === 0 && $codigoObra <= 0 ? 'Seleccione una obra' : ''), 'disabled' => $cantidad === 0, 'style' => 'width:200px;', 'value' => $codigoCuadra, 'id' => 'codigoCuadraGenerar'])->label(false);
			
			Pjax::end();
			?>
			</td>
		</tr>
		
		<tr style="margin-right:5px;">
			<td align="right" colspan="3">
			<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'generar();']) ?>
			&nbsp;
			<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("' . $selectorModal . '").modal("hide");']); ?>
			</td>
		</tr>
	</table>
</div>

<?php
ActiveForm::end();
?>
<div style="margin-top:5px;">
<?php
echo $form->errorSummary($modelCuadra);
?>
</div>
<?php
Pjax::end();
?>

<script type="text/javascript">
function generar(){
	
	var obra = $("#codigoObraGenerar").val();
	var cuadra = $("#codigoCuadraGenerar").val();
	
	$.pjax.reload({
		
		container : "#pjaxGenerar",
		url : "<?= BaseUrl::toRoute(['generar']); ?>",
		type : "POST",
		replace : false,
		push : false,
		data : {
			"selectorModal" : "<?= $selectorModal; ?>",
			"MejoraCuadra" : {
				"obra_id" : obra,
				"cuadra_id" : cuadra
			}
		}
	});
}

function cambiaObraGenerar(nuevo){
	
	nuevo = parseInt(nuevo);
	
	if(isNaN(nuevo) || nuevo <= 0){
		
		$("#filtroCuadra").val("");
		$("#filtroCuadra").prop("disabled", true);
	}
	console.log("generando");
	$.pjax.reload({
		
		container : "#pjaxObraGenerar",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"obra" : nuevo
		}
	});
}

$(document).ready(function(){
	
	<?php
	if($codigoObra !== null && $codigoObra > 0){
		?>
		cambiaObra("<?= $codigoObra; ?>");
		<?php
	}
	?>
	
	
	$("<?= $selectorModal; ?>").on("show.bs.modal", function(){
		
		var codigoObra = parseInt($("<?= $selectorObra ?>").val());
		
		
			
			cambiaObraGenerar(codigoObra);
		
			$("#pjaxObraGenerar").on("pjax:complete", function(){
				
				$("#codigoObraGenerar").val(codigoObra);
				$("#codigoCuadraGenerar").val($("<?= $selectorCuadra; ?>").val());	
				$("#pjaxObraGenerar").off("pjax:complete");
			});	
		
	});
});
</script>