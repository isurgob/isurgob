<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

use app\utils\db\utb;
use app\models\objeto\Cem;

Pjax::begin(['enableReplaceState' => 'false', 'enablePushState' => 'false', 'id' => 'pjaxFormSeguimiento']);

$form = ActiveForm::begin(['action' => ['updateseguimiento'],'id'=>'frmSeguimiento', 'options' => ['onsubmit' => 'modificarSeg();']]);

echo Html::input('hidden', 'txInti', $extras['modSeguimiento']['model']->inti_id, ['id' => 'txIntiFormSeguimiento']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td><label>Tomo: </label></td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txTomoFormSeguimiento', $extras['modSeguimiento']['tomo'], ['id' => 'txTomoFormSeguimiento', 'class' => 'form-control', 'maxlength'=>'10']); ?>
	</td>
</tr>

<tr>
	<td><label>Folio: </label></td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txFolio', $extras['modSeguimiento']['folio'], ['id' => 'txFolioFormSeguimiento', 'class' => 'form-control', 'maxlength'=>'10']); ?>
	</td>
</tr>

<tr>
	<td><label>Plazo: </label></td>
	<td width='5px'></td>
	<td>
		<?= DatePicker::widget(['name' => 'dtPlazo', 'dateFormat' => 'dd/MM/yyyy', 'value' => $extras['modSeguimiento']['plazo'], 'options' => ['id' => 'dtPlazoFormSeguimiento', 'class' => 'form-control', 'max-length' => '10']]); ?>
	</td>
</tr>

<tr>
	<td colspan='3'>
		<?php
		$model = $extras['modSeguimiento']['model'];
		echo $form->errorSummary($model) 
		?>
	</td>
</tr>

<tr>
	<td colspan='3'>
		<br><div id="errorModificarSeg" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
	
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>	
<tr>
	<td>
		<?= Html::button('Aceptar',['class' => 'btn btn-success', 'onclick' => 'modificarSeg()'])?>
	</td>
	<td width="15px"></td>
	<td>
		<?= Html::button('Cancelar',['class' => 'btn btn-primary', 'onclick' => '$("#opcEditar").modal("hide")'])?>
	</td>
</tr>
</table>

<?php ActiveForm::end(); 
Pjax::end();
?>

<script>
function modificarSeg(){
			
	var error = '';
	
	if ($('#txTomoFormSeguimiento').val()=='') error = 'Ingrese un Tomo';
	if ($('#txFolioFormSeguimiento').val()=='') error = 'Ingrese un Folio';
	if ($('#dtPlazoFormSeguimiento').val()=='') error = 'Elija un Plazo de Presentación';
	
	if (error==''){
		$.pjax.reload({
			container : "#pjaxFormSeguimiento",
			url : "<?= BaseUrl::toRoute(['updateseguimiento']) ?>",
			replace : false,
			push : false,
			type : "POST",
			data : {
				"txInti" : $("#txIntiFormSeguimiento").val(),
				"txTomo" : $("#txTomoFormSeguimiento").val(),
				"txFolio" : $("#txFolioFormSeguimiento").val(),
				"dtPlazo" : $("#dtPlazoFormSeguimiento").val()
			}
		});	
	}		
	else{
		$("#errorModificarSeg").html(error);
		$("#errorModificarSeg").css("display", "block");
	}	
}
</script>