<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl;
use \yii\widgets\Pjax;


use app\utils\db\utb;


$selectorModal= isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '#modalTablas');
$consulta = isset($consulta) ? $consulta : 1;

$comp = array(
				"=" => "=",
				"<>" => "<>",
				">" => ">",
				"<" => "<",
				">=" => ">=",
				"<=" => "<="
			);

$action = BaseUrl::to(['//ctacte/resoltabla/create']);

if(isset($consulta))
{
	if($consulta == 2)
		$action = BaseUrl::to(['//ctacte/resoltabla/delete']);
	
	if($consulta == 3)
		$action = BaseUrl::to(['//ctacte/resoltabla/update']);
}
?>

<div>

   <?php  
	Pjax::begin(['id' => 'pjaxFormTabla', 'enableReplaceState' => false, 'enablePushState' => false]);
	
	$form = ActiveForm::begin(['action'=>$action,'method'=>'POST','id'=>'formTabla', 'fieldConfig' => ['template' => '{input}']]); 
	?>
			<table width='80%' border='0'>
				<tr>
					<td><label>Codigo:</label></td>
					<td width="5px"></td>
					<td><?= $form->field($model, 'tabla_id')->textInput(['style' => 'width:57px;', 'class' => 'form-control solo-lectura', 'tabindex' => -1])->label(false); ?></td>
					<td width="10px"></td>
					<td><label>Nombre:</label></td>
					<td colspan="2"><?= $form->field($model, 'nombre')->textInput(['id'=>'formTablaNombre','style' => 'width:200px;', 'class' => 'form-control', 'maxlength' => 25])->label(false); ?></td>
				</tr>
				<tr>
					<td><label>Columnas:</label></td>
					<td></td>
					<td><?= $form->field($model, 'cantcol')->input('number', ['min' => 1, 'max' => 6, 'maxlength' => 1, 'onchange' => 'cambiaCantidadColumnas($(this).val());', 'id' => 'formTablaCantidadColumnas']); ?></td>
					<td></td>
					<td width="60px"><label>Col. Fijas:</label></td>
					<td><?= $form->field($model, 'cantcolfijas')->input('number', ['min' => 0, 'max' => $model->cantcol - 1, 'maxlength' => 1, 'id' => 'formTablaCantidadColumnasFijas']); ?></td>
					<td><?= $form->field($model, 'uso_paramstr')->checkbox(['label' => 'Usar paramstr', 'value' => 1, 'uncheck' => 0, 'id' => 'formTablaUsoparamstr', 'onclick' => 'usarParamStr($(this).is(":checked"));']); ?></td>
				</tr>
			</table>
			
			<table border="0" style="margin-top:10px; width:auto;" class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center"><label>Parámetro</label></th>
						
						<th class="text-center"><label>Nombre</label></th>
						
						<th class="text-center"><label>Comp.</label></th>
					</tr>
				</thead>
				<tbody>
					
					<?php
					$actual= 0;
					foreach($columnas as $clave => $valor){
						
						$habilitar= $model->cantcol >= $actual;
						if($actual === 0 && !$model->uso_paramstr) $habilitar= false;

						?>
						<tr class="filaDato" data-posicion="<?= $actual ?>">
							<td><label>
							<?= Html::checkbox('ResolTabla[columnas][' . $clave . '][aplicable]', true, ['value' => 1, 'uncheck' => 0, 'disabled' => !$habilitar, 'class' => 'hidden', 'data-campo' => 'aplicable']); ?>
							<?= $clave ?>
							</label>
							</td>
							
							<td><?= Html::textInput('ResolTabla[columnas][' . $clave . '][nombre]', $valor['nombre'], 
													['class' => 'form-control', 'maxlength' => 15, 'style' => 'width:130px;', 'data-campo' => 'nombre', 'disabled' => !$habilitar, 'maxlength' => 15]); ?></td>
							
							<td><?= Html::dropDownList('ResolTabla[columnas][' . $clave . '][compara]', $valor['compara'], $comp, 
									['class' => 'form-control', 'style' => 'width:50px;', 'prompt' => '', 'data-campo' => 'compara', 'disabled' => !$habilitar]); ?></td>
						</tr>
						<?php
						
						$actual++;
					}
						
					?>
				</tbody>
			</table>
			
			<div class="form-group">
				<?php
				if($consulta === 2){
					echo '<div style="color:red;">ADVERTENCIA: Si elimina la tabla se eliminaran todos los datos asociados a esta</div>';
					echo Html::button('Eliminar', ['class' => 'btn btn-danger', 'onclick' => 'eliminarTabla();']);
					
				}
				else if($consulta !== 1) echo Html::button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'grabarTabla();']);
				
				echo '&nbsp;&nbsp;';
				echo Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("' . $selectorModal . '").modal("hide")']);
				?>
	        </div>
	
    <?php
	ActiveForm::end(); 

	echo $form->errorSummary($model);
	Pjax::end();
	?>
   </div>

   
<script type="text/javascript">
function usarParamStr(usar){
	
	$cantidadColumnas= $("#formTablaCantidadColumnas");
	$columnasFijas= $("#formTablaCantidadColumnasFijas");
	$filaParamStr= $('tr.filaDato[data-posicion="0"]');
	
	cambiaCantidadColumnas($cantidadColumnas.val());
	
	if(usar){
		
		$cantidadColumnas.attr("max", 6);
		$columnasFijas.attr("max", 5);

		$filaParamStr.find("input").prop("disabled", false);
		$filaParamStr.find("select").prop("disabled", false);

	} else {
		
		$cantidadColumnas.attr("max", 5);
		$columnasFijas.attr("max", 4);
		$filaParamStr.find("input").prop("disabled", true);
		$filaParamStr.find("select").prop("disabled", true);
	}
}

function cambiaCantidadColumnas(cantidad){
	
	var $fijas= $("#formTablaCantidadColumnasFijas");
	var usar= $("#formTablaUsoparamstr").is(":checked");
	
	var modificador= usar ? 0 : 1;

	$fijas.attr("max", cantidad - 1);
	
	$("tr.filaDato").each(function(){
		
		var posicion= parseInt($(this).data("posicion"));
		
		$(this).find("input").prop("disabled", posicion > cantidad - 1 + modificador);
		$(this).find("select").prop("disabled", posicion > cantidad - 1 + modificador);
		
		if(posicion === 0 && !usar){
			$(this).find("input").prop("disabled", true);
			$(this).find("select").prop("disabled", true);
		}
		
	});
}

function grabarTabla(){
	
	var urlTo= "<?= $consulta === 0 ? BaseUrl::toRoute(['//ctacte/resoltabla/create', 'resol_id' => $resol_id]) : 
		($consulta === 3 ? BaseUrl::toRoute(['//ctacte/resoltabla/update', 'resol_id' => $model->resol_id, 'tabla_id' => $model->tabla_id]) : null) ?>";
	
	var datos= {"ResolTabla": {}};
	
	<?php
	if($consulta === 3){
		?>
		datos.ResolTabla.tabla_id= <?= $model->tabla_id; ?>;
		<?php
	}
	?>
	
	datos.ResolTabla.resol_id= "<?= $resol_id; ?>";
	datos.ResolTabla.nombre= $("#formTablaNombre").val();
	datos.ResolTabla.cantcol= $("#formTablaCantidadColumnas").val();
	datos.ResolTabla.cantcolfijas= $("#formTablaCantidadColumnasFijas").val();
	datos.ResolTabla.uso_paramstr= $("#formTablaUsoparamstr").is(":checked") ? 1 : 0;
	
	datos.selectorModal= "<?= $selectorModal; ?>";
	
	$("tr.filaDato").each(function(){
		
		$campoNombre= $(this).find("[data-campo='nombre']:not([disabled])");
		$campoCompara= $(this).find("[data-campo='compara']:not([disabled])");
		$campoAplicable= $(this).find("[data-campo='aplicable']:not([disabled])");
		
		datos[$campoNombre.attr("name")]= $campoNombre.val();
		datos[$campoCompara.attr("name")]= $campoCompara.val();
		datos[$campoAplicable.attr("name")]= $campoAplicable.val();
	});
	
	$.pjax.reload({
		container: "#pjaxFormTabla",
		url: urlTo,
		type: "POST",
		replace: false,
		push: false,
		data: datos
	});
}

function eliminarTabla(){
	
	$.pjax.reload({
		container: "#pjaxFormTabla",
		url: "<?= BaseUrl::toRoute(['//ctacte/resoltabla/delete', 'resol_id' => $model->resol_id, 'tabla_id' => $model->tabla_id]); ?>",
		type: "POST",
		replace: false,
		push: false,
		data: {
			"resol_id": "<?= $model->resol_id; ?>",
			"tabla_id": "<?= $model->tabla_id; ?>"
		}
	});
}

$(document).ready(function(){
	
	<?php
	if(in_array($consulta, [1, 2])){
		?>
		DesactivarFormPost("formTabla");
		<?php
	} else {
		?>
		usarParamStr(<?=$model->uso_paramstr ?>);
		<?php
	}
	?>
	
	
});
</script>