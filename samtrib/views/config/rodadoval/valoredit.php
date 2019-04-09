<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\utils\db\utb;


$action = '';
if ($consulta == 0) $action = BaseUrl::toRoute(['create']);
if ($consulta == 3) $action = BaseUrl::toRoute(['update']);

Pjax::begin(['formSelector' => '#form-rodado-valor', 'id' => 'pjaxFormRodadoValor', 'enableReplaceState' => false, 'enablePushState' => false]);
$form = ActiveForm::begin(['id' => 'form-rodado-valor','action' => $action, 'validateOnSubmit' => false,
							'fieldConfig' => [
        										'template' => '<td>{label}</td><td width="5px"></td><td>{input}</td>',
        										],
        										
        					//'options' => ['onSubmit' => 'grabar()']
						]);
	
	$param = Yii::$app->params;
	
?>        						
	<div class="form" style='padding:5px; margin-top:5px;'>
		<table border='0'>
			<tr>
				<?=  $form->field($model, 'anioval')->textInput(['style' => 'width:40px;','onkeypress'=>'return justNumbers(event);','maxlength'=>'4', 'id' => 'formAnioValuatorio'])->label('Año valuatorio:'); ?>
			</tr>
			<tr>
				<?= $form->field($model, 'gru')->dropDownList(utb::getAux('rodado_tcat','gru',"(nombre || ' (Grupo:' || gru::varchar || ')')"), ['id' => 'formGrupo', 'style' => 'width:150px;'])->label('Grupo:'); ?>
			</tr>
			<tr>
				<?=  $form->field($model, 'anio')->textInput(['style' => 'width:40px;','onkeypress'=>'return justNumbers(event);','maxlength'=>'4', 'id' => 'formAnio'])->label('Año:'); ?>
			</tr>
			<tr>
				<?=  $form->field($model, 'pesodesde')->textInput(['style' => 'width:80px;text-align:right','onkeypress'=>'return justDecimal(this.value,event,2);','maxlength'=>'8', 'id' => 'formPesoDesde'])->label('Peso desde:'); ?>
			</tr>
			<tr>
				<?=  $form->field($model, 'pesohasta')->textInput(['style' => 'width:80px;text-align:right','onkeypress'=>'return justDecimal(this.value,event,2);','maxlength'=>'8', 'id' => 'formPesoHasta'])->label('Peso hasta:'); ?>
			</tr>
			<tr>
				<?=  $form->field($model, 'valor')->textInput(['style' => 'width:80px;text-align:right','onkeypress'=>'return justDecimal(this.value,event,2);','maxlength'=>'8', 'id' => 'formValor'])->label('Valor:'); ?>
			</tr>
		</table>
	</div>
	
	<div style='padding:5px; margin-top:5px;'>
		<?= Html::button('Grabar', ['class' => 'btn btn-success','id'=>'btGrabarRodadoValor', 'onclick' => 'grabar();']); ?>
	</div>
<?php

ActiveForm::end();

echo $form->errorSummary($model);

if ($consulta == 1 or $consulta == 3) // si es consulta o modificar
{
	?>
	<script>
	$("#anioValuatorio").attr("readonly",true);
	$("#Grupo").attr("readonly",true);
	$("#anio").attr("readonly",true);
	$("#pesoDesde").attr("readonly",true);
	$("#pesoHasta").attr("readonly",true);
	<?php if ($consulta == 1) echo '$("#valor").attr("readonly",true);';
	if ($consulta == 1) echo '$("#btGrabarRodadoValor").css("display","none");';?>
	</script>
	<?php
}
 
Pjax::end();         						

?>

<script>
function grabar(){
	
	var datos= {};
	
	var interno= {
		
		"anioval": $("#formAnioValuatorio").val(),
		"gru": $("#formGrupo").val(),
		"anio": $("#formAnio").val(),
		"pesodesde": $("#formPesoDesde").val(),
		"pesohasta": $("#formPesoHasta").val(),
		"valor": $("#formValor").val()
	};
	
	datos.RodadoVal= interno;
	
	$.pjax.reload({
		container: "#pjaxFormRodadoValor",
		url: "<?= $action; ?>",
		type: "POST",
		replace: false,
		push: false,
		data: datos
	});
}
</script>
