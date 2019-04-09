<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\Fecha;

use app\utils\db\utb;

Pjax::begin(['enableReplaceState' => false, 'enablePushState' => 'false', 'id' => 'pjaxNuevaEspera']);

$obj_id = $extras['modEspera']['model']->obj_id == null ? 0 : $extras['modEspera']['model']->obj_id;

$form = ActiveForm::begin(['action' => ['createespera'],'id'=>'frmNuevaEspera', 'options' => ['pjax' => 'true']]);

echo Html::input('hidden', 'inti_id', $extras['modEspera']['model']->inti_id, ['id' => 'txIntiFormEspera']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>

<tr>
	<td width="50px"><label>Tributo: </label></td>
	<td colspan="6">
		<?= Html::dropDownList(
							'dlTributo', 
							$extras['modEspera']['trib_id'], 
							utb::getAux('trib', 'trib_id', 'nombre', 0, "est = 'A' And trib_id Not In " . utb::getTribEsp() . " And tobj = " . utb::getTObj($obj_id)),
							['id' => 'dlTributoFormEspera', 'class' => 'form-control', 'prompt' => ''] 
							) ?>
	</td>
</tr>
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
	<tr>
		<td width="50px"><label>Desde: </label></td>
		<td>
			<?= DatePicker::widget(['name' => 'dtFecha', 'dateFormat' => 'dd/MM/yyyy', 'value' => Fecha::usuarioToDatePicker($extras['modEspera']['fecha']), 'options' => ['id' => 'dtFechaFormEspera', 'class' => 'form-control', 'max-length' => '10', 'style' => 'width:80px;']]); ?>
		</td>
		<td width="20px"></td>
		<td width='45px'><label>Hasta: </label></td>
		<td>
			<?= DatePicker::widget(['name' => 'dtFechaHasta', 'dateFormat' => 'dd/MM/yyyy', 'value' => $extras['modEspera']['fechaHasta'], 'options' => ['id' => 'dtFechaHastaFormEspera', 'class' => 'form-control', 'max-length' => '10', 'style' => 'width:80px;']]); ?>
		</td>
	</tr>
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
	<tr>
		<td width="50px"><label>Desde:</label></td>
		<td><?= Html::textInput('txAdesde', $extras['modEspera']['adesde'], ['id' => 'txAdesdeFormEspera', 'class' => 'form-control', 'maxlength' => 4, 'style' => 'width:40px;']) ?></td>
		<td><?= Html::textInput('txCdesde', $extras['modEspera']['cdesde'], ['id' => 'txCdesdeFormEspera', 'class' => 'form-control', 'maxlength' => 3, 'style' => 'width:40px;']) ?></td>
		<td width="20px"></td>
		<td width='45px'><label>Hasta: </label></td>
		<td><?= Html::textInput('txAhasta', $extras['modEspera']['ahasta'], ['id' => 'txAhastaFormEspera', 'class' => 'form-control', 'maxlength' => 4, 'style' => 'width:40px;']) ?></td>
		<td><?= Html::textInput('txChasta', $extras['modEspera']['chasta'], ['id' => 'txChastaFormEspera', 'class' => 'form-control', 'maxlength' => 3, 'style' => 'width:40px;']) ?></td>
</tr>
<table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td width="50px"><label>Obs: </label></td>
	<td width="425px">
		<?php		
		echo Html::textarea(
							'txObs', 
							$extras['modEspera']['obs'], 
							['id' => 'txObsFormEspera', 'class' =>'form-control', 'style' => 'width:365px;max-width:365px;height:60px;max-height:120px']
							); 
		?>
	</td>
</tr>
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
	<tr>
		<td colspan='3'>
			<?php
			$model = $extras['modEspera']['model'];
			echo $form->errorSummary($model) 
			?>
		</td>
	</tr>
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td colspan='3'>
		<br><div id="errorNuevaEspera" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>	
<tr>
	<td colspan='3'>
		<?= Html::button('Aceptar',['class' => 'btn btn-success', 'onclick' => 'nuevaEspera()'])?>
	</td>
	<td width="15px"></td>
	<td>
		<?= Html::button('Cancelar',['class' => 'btn btn-primary', 'onclick' => '$("#opcEspera").modal("hide")'])?>
	</td>
</tr>
</table>

<?php 

ActiveForm::end(); 
Pjax::end();
?>

<script>
function nuevaEspera(){
			
	var error = '';
	
	if($("#dlTributoFormEspera option:selected").text() == '') error += "<li>Elija un Tributo</li>";
	if ($('#dtFechaFormEspera').val()=='') error += '<li>Elija una Fecha Desde</li>';			
	if ($('#dtFechaHastaFormEspera').val()=='') error += '<li>Elija una Fecha Hasta</li>';
	if ($('#txAdesdeFormEspera').val() == '') error += '<li>Ingrese un Año Desde</li>';
	if ($('#txCdesdeFormEspera').val() == '') error += '<li>Ingrese una Cuota Desde</li>';
	if ($('#txAhastaFormEspera').val() == '') error += '<li>Ingrese un Año Hasta</li>';
	if ($('#txChastaFormEspera').val() == '') error += '<li>Ingrese una Cuota Hasta</li>';
	
	if (error==''){
		$.pjax.reload({
			container : "#pjaxNuevaEspera",
			url : "<?= BaseUrl::toRoute(['createespera']) ?>",
			replace : false,
			push : false,
			type : "POST",
			data : {
				"inti_id" : $("#txIntiFormEspera").val(),
				'dlTrib' : $("#dlTributoFormEspera").val(),
				"dtFecha" : $("#dtFechaFormEspera").val(),
				"dtFechaHasta" : $("#dtFechaHastaFormEspera").val(),
				"txAdesde" : $("#txAdesdeFormEspera").val(),
				"txCdesde" : $("#txCdesdeFormEspera").val(),
				"txAhasta" : $("#txAhastaFormEspera").val(),
				"txChasta" : $("#txChastaFormEspera").val(),
				"txObs" : $("#txObsFormEspera").val()
			}
		});
	}
	else{
		$("#errorNuevaEspera").html(error);
		$("#errorNuevaEspera").css("display", "block");
	}		
}
</script>
