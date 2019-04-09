<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use app\utils\db\Fecha;
use yii\bootstrap\Alert;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\helpers\BaseUrl;

ini_set("display_errors", "on");
error_reporting(E_ALL);
/* @var $this yii\web\View */
/* @var $model app\models\config\RubroVigencia */
/* @var $form yii\widgets\ActiveForm */

$action = BaseUrl::to(['//config/rubrovigencia/create']);

if(isset($consulta))
{
	if($consulta == 2)
		$action = BaseUrl::to(['//config/rubrovigencia/delete']);
	
	if($consulta == 3)
		$action = BaseUrl::to(['//config/rubrovigencia/update']);
}

$selectorModal= isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '');
?>

	<?php Pjax::begin(['id' => 'pjaxFormRubroVigencia', 'enableReplaceState' => false, 'enablePushState' => false]);?>
	
	<?php $form = ActiveForm::begin(['action' => $action,'method'=>'POST', 'id'=>'formVigencia','enableClientValidation'=>true, 'fieldConfig' => ['template' => '{input}', 'options' => ['style' => 'display:inline-block;']]]); ?>
		
		<table border='0'>
			<tr>
				<td><label>Código:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'rubro_id')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'rubro_id', 'style' => 'width:100px;']); ?></td>
				<td width="10px"></td>
				<td colspan="3" align="right"><label>Desde:</label>
					<?= $form->field($model, 'adesde')->textInput(['id' => 'vigenciaAdesde', 'maxlength' => 4, 'style' => 'width:45px;', 'disabled' => $consulta === 3]); ?>
					<?= $form->field($model, 'cdesde')->textInput(['id' => 'vigenciaCdesde', 'maxlength' => 3, 'style' => 'width:32px;', 'disabled' => $consulta === 3]); ?>
				</td>
				<td width="10px"></td>
				<td colspan="3" align="right"><label>Hasta:</label>
					<?= $form->field($model, 'ahasta')->textInput(['id' => 'vigenciaAhasta', 'maxlength' => 4, 'style' => 'width:45px;']); ?>
					<?= $form->field($model, 'chasta')->textInput(['id' => 'vigenciaChasta', 'maxlength' => 3, 'style' => 'width:32px;']); ?>
				</td>
			</tr>

			<tr>
				<td colspan="6"><label>Tipo de Cálculo:</label>
				
				<?= $form->field($model, 'tcalculo')->dropDownList(utb::getAux('rubro_tfcalculo'), ['id' => 'vigenciaTcalculo', 'style' => 'width:137px;', 'prompt' => '']); ?>
				
				<td><label>Tipo de Mínimo:</label></td>
				<td></td>
				<td colspan="3"><?= $form->field($model, 'tminimo')->dropDownList(utb::getAux('rubro_tminimo'), ['id' => 'vigenciaTminimo', 'style' => 'width:160px;', 'prompt' => '']); ?></td>
			</tr>

			<tr>
				<td><label>Alícuota:</label></td>
				<td></td>
				<td><?= $form->field($model, 'alicuota')->textInput(['id' => 'vigenciaAlicuota', 'maxlength' => 9, 'style' => 'width:90px']) ?></td>
				<td></td>
				<td><label>Alícuota Atras:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'alicuota_atras')->textInput(['id' => 'vigenciaAlicuota_atras', 'maxlength' => 9, 'style' => 'width:90px;']); ?></td>
				<td></td>
				<td><label>Fijo:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'fijo')->textInput(['id' => 'vigenciaFijo', 'maxlength' => 12, 'style' => 'width:90px']); ?></td>
			</tr>

			<tr>
				<td><label>Mínimo:</label></td>
				<td></td>
				<td><?= $form->field($model, 'minimo')->textInput(['id' => 'vigenciaMinimo', 'maxlength' => 12, 'style' => 'width:90px;']);; ?></td>
				<td></td>
				<td><label>Mínimo temp.alta:</label></td>
				<td></td>
				<td><?= $form->field($model, 'minalta')->textInput(['id' => 'vigenciaMinalta', 'maxlength' => 12, 'style' => 'width:90px']); ?></td>
				<td></td>
				<td><label>Porcentaje:</label></td>
				<td></td>
				<td><?= $form->field($model, 'porc')->textInput(['id' => 'vigenciaPorc', 'maxlength' => 5, 'style' => 'width:55px;']); ?></td>
			</tr>
	
			<tr>
				<td colspan="5"><label>Cantidad Hasta:</label>
				<?= $form->field($model, 'canthasta')->textInput(['id' => 'vigenciaCanthasta', 'maxlength' => 10, 'style' => 'width:50px']); ?>
				</td>
			</tr>
		</table>
	
	<div class="form-group">
		<?php
		if($consulta === 2)
			echo Html::button('Eliminar', ['class' => 'btn btn-danger', 'onclick' => 'borrarVigencia();']);
		else if($consulta !== 1)
			echo Html::button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'grabar();']);
			
		echo '&nbsp;';
		echo Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("' . $selectorModal . '").modal("hide");']);
		?>
	</div>
	
	<?php
	ActiveForm::end();
	
	
	echo $form->errorSummary($model, ['id' => 'erroresVigencia']); 
	Pjax::end();
	
	
	?>

	<script>
	function mostrarErroresVigencia(errores){
		
		if(errores.length == 0){
			
			$("#erroresVigencia").css("display", "block");
			return;
		}
		
		$("#erroresVigencia").css("display", "none");
		
		var $padre= $("#erroresVigencia ul");
		$padre.empty();
		
		for(var e in errores){
			
			$item= $("<li />");
			$item.text(errores[e]);
			$item.appendTo($padre);
		}
		
	}
	
	function validarFormularioVigencia(){
		
		
		errores= new Array();
		
		//borrar la siguiente linea
		return errores;
			
		if($("#perdesde").val()=="") errores.push("Ingrese un Año de Cuota Desde");
		if($("#perhasta").val()=="") errores.push("Ingrese un Año de Cuota Hasta");
		if($("#cuotadesde").val()=="") errores.push("Ingrese una Cuota Desde");
		if($("#cuotadesde").val()=="") errores.push("Ingrese una Cuota Hasta");
		if($("#alucuota").val()=="") errores.push("Ingrese un valor en Alicuota");
		if($("#minimo").val()=="") errores.push("Ingrese un Valor Minimo");
		if($("#minalta").val()=="") errores.push("Ingrese un valor Minalata");
		if($("#fijo").val()=="") errores.push("Ingrese un valor en campo fijo");
		if($("#porc").val()=="") errores.push("Ingrese un valor de Porcentaje");
		if(isNaN($("#perdesde").val())) errores.push("El campo perdesde debe ser un numero");
		if(isNaN($("#perhasta").val())) errores.push("El campo perhasta debe ser un numero");
		if(isNaN($("#cuotadesde").val())) errores.push("El campo cuotadesde debe ser un numero");
		if(isNaN($("#cuotahasta").val())) errores.push("El campo cuotahasta debe ser un numero");
		if(isNaN($("#alicuota").val())) errores.push("El campo alicuota debe ser un numero");
		if(isNaN($("#minimo").val())) errores.push("El campo minimo debe ser un numero");
		if(isNaN($("#minalta").val())) errores.push("El campo minalta debe ser un numero");
		if(isNaN($("#fijo").val())) errores.push("El campo fijo debe ser un numero");
		if(isNaN($("#porc").val())) errores.push("El campo porc debe ser un numero");
		if($("#perdesde").val() > $("#perhasta").val()) errores.push("Perdesde no puede ser mayor a Perhasta");
		if($("#perdesde").val() < 0) errores.push("Perdesde debe ser mayor o igual a cero");
		if($("#perhasta").val() < 0) errores.push("Perhasta debe ser mayor o igual a cero");
		if($("#cuotadesde").val() < 0) errores.push("Cuotadesde debe ser mayor o igual a cero");
		if($("#cuotahasta").val() < 0) errores.push("Cuotahasta debe ser mayor o igual a cero");

		return errores;
	}
	
	function borrarVigencia(){
		
		$.pjax.reload({
			container: "#pjaxFormRubroVigencia",
			type: "POST",
			url: "<?= BaseUrl::toRoute(['//config/rubrovigencia/delete', 'id' => $model->rubro_id, 'perdesde' => $model->perdesde, 'perhasta' => $model->perhasta]); ?>",
			replace: false,
			push: false,
			data: {
				"selectorModal": "<?= $selectorModal; ?>"
			}
		});
	}
	
	function grabar(){
		
		var errores= validarFormularioVigencia();
		
		if(errores.length > 0)
			mostrarErroresVigencia(errores);
		else {
			
			var adesde= $("#vigenciaAdesde").val();
			var cdesde= $("#vigenciaCdesde").val();
			var ahasta= $("#vigenciaAhasta").val();
			var chasta= $("#vigenciaChasta").val();
			var tcalculo= $("#vigenciaTcalculo").val();
			var tminimo= $("#vigenciaTminimo").val();
			var alicuota= $("#vigenciaAlicuota").val();
			var alicuota_atras= $("#vigenciaAlicuota_atras").val();
			var minimo= $("#vigenciaMinimo").val();
			var minalta= $("#vigenciaMinalta").val();
			var fijo= $("#vigenciaFijo").val();
			var canthasta= $("#vigenciaCanthasta").val();
			var porc= $("#vigenciaPorc").val();			
			
			var urlTo= "";
			
			switch(<?= $consulta ?>){
				
				case 0: urlTo= "<?= BaseUrl::toRoute(['//config/rubrovigencia/create', 'id' => $model->rubro_id]); ?>"; break;
				case 2: urlTo= "<?= BaseUrl::toRoute(['//config/rubrovigencia/delete', 'id' => $model->rubro_id, 'perdesde' => $model->perdesde, 'perhasta' => $model->perhasta]); ?>"; break;
				case 3: urlTo= "<?= BaseUrl::toRoute(['//config/rubrovigencia/update', 'id' => $model->rubro_id, 'perdesde' => $model->perdesde, 'perhasta' => $model->perhasta]); ?>"; break;
			}
			
			$.pjax.reload({
				container: "#pjaxFormRubroVigencia",
				url: urlTo,
				type: "POST",
				replace: false,
				push: false,
				data: {
					"RubroVigencia": {
						"rubro_id": "<?= $model->rubro_id; ?>",
						"adesde": adesde,
						"cdesde": cdesde,
						"ahasta": ahasta,
						"chasta": chasta,
						"tcalculo": tcalculo,
						"tminimo": tminimo,
						"alicuota": alicuota,
						"alicuota_atras": alicuota_atras,
						"minimo": minimo,
						"minalta": minalta,
						"fijo": fijo,
						"canthasta": canthasta,
						"porc": porc
					}
				}
			});
		}
	}
	
	$(document).ready(function(){
		
		<?php
		if(in_array($consulta, [1, 2])){
		?>
		DesactivarFormPost("formVigencia");
		<?php
		}
		?>
	});
	</script>
	