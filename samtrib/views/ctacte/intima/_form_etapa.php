<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;

Pjax::begin(['enableReplaceState' => false, 'enablePushState' => 'false', 'id' => 'pjaxnuevaEtapa']);
$form = ActiveForm::begin(['action' => ['createetapa'],'id'=>'frmNuevaEtapa', 'options' => ['pjax' => 'true']]);

echo Html::input('hidden', 'inti_id', $extras['modEtapa']['model']->inti_id, ['id' => 'txIntiFormEtapa']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>

<tr>
	<td width="50px"><label>Fecha: </label></td>
	<td width='5px'></td>
	<td>
		<?= DatePicker::widget(['name' => 'dtFecha', 'dateFormat' => 'dd/MM/yyyy', 'value' => Fecha::usuarioToDatePicker($extras['modEtapa']['fecha']), 'options' => ['id' => 'ftFechaFormEtapa', 'class' => 'form-control', 'max-length' => '10', 'style' => 'width:80px;']]); ?>
	</td>
</tr>
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td width="50px"><label>Etapa: </label></td>
	<td width='5px'></td>
	<td>
		<?= Html::dropDownList('dlEtapa', $extras['modEtapa']['etapa'], utb::getAux('intima_tetapa', 'cod', 'nombre', 0, 'genauto = 0'), ['id' => 'dlEtapaFormEtapa', 'class' =>'form-control', 'prompt' => '', 'style' => 'width:190px;']) ?>
	</td>
</tr>
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td width="50px"><label>Detalle: </label></td>
	<td width='5px'></td>
	<td>
		<?php		
		echo Html::textarea(
							'txDetalle', 
							$extras['modEtapa']['detalle'], 
							['id' => 'txDetalleFormEtapa', 'class' =>'form-control', 'style' => 'width:190px;max-width:190px;height:60px;max-height:120px']
							); 
		?>
	</td>
</tr>
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td colspan='3'>
		<?php
		$model = $extras['modEtapa']['model'];
		echo $form->errorSummary($model) 
		?>
	</td>
</tr>
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td colspan='3'>
		<br><div id="errorNuevaEtapa" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td>
		<?= Html::button('Aceptar',['class' => 'btn btn-success', 'onclick' => 'nuevaEtapa()'])?>
	</td>
	<td width="15px"></td>
	<td>
		<?= Html::button('Cancelar',['class' => 'btn btn-primary', 'onclick' => '$("#opcEtapa").modal("hide")'])?>
	</td>
</tr>
</table>

<?php 

ActiveForm::end(); 
Pjax::end();
?>

<script type="text/javascript">
function nuevaEtapa(){
			
	var error = '';
	
	if ($('#ftFechaFormEtapa').val()=='') error += '<li>Elija una Fecha</li>';			
	if ($('#dlEtapaFormEtapa option:selected').text()=='') error += '<li>Elija una Etapa</li>';
	if ($('#txDetalleFormEtapa').val() == '') error += '<li>Ingrese un Detalle</li>';
	
	if (error==''){
		$.pjax.reload({
			container : "#pjaxnuevaEtapa",
			url : "<?= BaseUrl::toRoute(['createetapa']) ?>",
			replace : false,
			push : false,
			type : "POST",
			data : {
				"inti_id" : $("#txIntiFormEtapa").val(),
				"dlEtapa" : $("#dlEtapaFormEtapa").val(),
				"txDetalle" : $("#txDetalleFormEtapa").val(),
				"dtFecha" : $("#ftFechaFormEtapa").val()	
			}
		});
	}
	else{
		$("#errorNuevaEtapa").html(error);
		$("#errorNuevaEtapa").css("display", "block");
	}		
}
</script>
