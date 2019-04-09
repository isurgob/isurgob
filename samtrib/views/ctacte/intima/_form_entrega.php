<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\Fecha;

use app\utils\db\utb;
use app\models\objeto\Cem;

Pjax::begin(['enableReplaceState' => false, 'enablePushState' => 'false', 'id' => 'pjaxNuevaEntrega']);
$form = ActiveForm::begin(['action' => ['createentrega'],'id'=>'frmNuevaEntrega', 'options' => ['onsubmit' => 'nuevaEntrega();', 'pjax' => 'true']]);

echo Html::input('hidden', 'txInti', $extras['modEntrega']['model']->inti_id, ['id' => 'txIntiFormEntrega']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>

<tr>
	<td><label>Fecha: </label></td>
	<td width='5px'></td>
	<td>
		<?= DatePicker::widget(['name' => 'dtFecha', 'dateFormat' => 'dd/MM/yyyy', 'value' => Fecha::usuarioToDatePicker($extras['modEntrega']['fecha']), 'options' => ['id' => 'dtFechaFormEntrega', 'class' => 'form-control', 'max-length' => '10', 'style' => 'width:80px;']]); ?>
	</td>
</tr>

<tr>
	<td><label>Resultado: </label></td>
	<td width='5px'></td>
	<td>
		<?= Html::dropDownList('dlResultado', $extras['modEntrega']['resultado'], utb::getAux('intima_tresult'), ['id' => 'dlResultadoFormEntrega', 'class' =>'form-control', 'prompt' => '<Seleccionar>', 'style' => 'width:100%;']) ?>
	</td>
</tr>

<tr>
	<td><label>Distribuidor: </label></td>
	<td width='5px'></td>
	<td>
		<?php		
		$distribuidor = $extras['modEntrega']['distribuidor'];
		echo Html::dropDownList(
							'dlDistribuidor', 
							$distribuidor,
							utb::getAux('sam.sis_usuario', 'usr_id', 'nombre', 0, 'distrib=1'), 
							['id' => 'dlDistribuidorFormEntrega', 'class' =>'form-control', 'prompt' => '<Seleccionar>', 'style' => 'width:100%;']
							); 
		?>
	</td>
</tr>
		
</table>

<div class="text-center" style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>	

		<?= Html::button('Aceptar',['class' => 'btn btn-success', 'onclick' => 'nuevaEntrega()'])?>
		&nbsp;
		<?= Html::button('Cancelar',['class' => 'btn btn-primary', 'onclick' => '$("#opcEntrega").modal("hide")'])?>
</div>

<div style="margin-top:5px; color:#000;font-family:'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:12px">
<?php
$model = $extras['modEntrega']['model'];
echo $form->errorSummary($model) 
?>
</div>

<div id="errorNuevaEntrega" style="display:none; margin-top:5px;" class="alert alert-danger alert-dismissable"></div>


<?php 

ActiveForm::end(); 
Pjax::end();
?>

<script>
function nuevaEntrega(){
			
	var error = '';
	
	if ($('#dtFechaFormEntrega').val()=='') error += '<li>Elija una Fecha</li>';			
	if ($('#dlResultadoFormEntrega option:selected').text()=='') error += '<li>Elija un Resultado</li>';
	if ($('#dlDistribuidorFormEntrega option:selected').text()=='') error += '<li>Elija un Distribuidor</li>';
	
	if (error==''){
		$.pjax.reload({
			container : "#pjaxNuevaEntrega",
			url : "<?= BaseUrl::toRoute(['createentrega']) ?>",
			replace : false,
			push : false,
			type : "POST",
			data : {
				"inti_id" : $("#txIntiFormEntrega").val(),
				"dlResultado" : $("#dlResultadoFormEntrega").val(),
				"dlDistribuidor" : $("#dlDistribuidorFormEntrega").val(),
				"dtFecha" : $("#dtFechaFormEntrega").val(),
				
			}
		});
	}
	else{
		$("#errorNuevaEntrega").html(error);
		$("#errorNuevaEntrega").css("display", "block");
	}		
}
</script>
