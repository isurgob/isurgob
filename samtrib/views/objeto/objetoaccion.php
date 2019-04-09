<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;

$this->params['breadcrumbs'][] = ['label' => 'Objeto: '.$model->obj_id, 'url' => ['view', 'id' => $model->obj_id]];
$this->params['breadcrumbs'][] = 'Acciones sobre Objetos';
?>

<div class="persona-view">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1>Acciones sobre Objetos</h1></td>
    </tr>
	</table>
	
	<p>
		<label>Objeto: </label><input class='form-control' style='width:65px;background:#E6E6FA' disabled value='<?=$model->obj_id?>' />
		<label>Nombre: </label><input class='form-control' style='width:300px;background:#E6E6FA' disabled value='<?=$model->nombre?>' />
		<label>Estado Actual: </label><input class='form-control' style='width:50px;background:#E6E6FA' disabled value='<?=utb::getCampo("v_objeto","obj_id='".$model->obj_id."'","est_nom")?>' />
	</p>
	
	<div class="form" style='padding:15px'>
		<?php 
			$form = ActiveForm::begin(['id' => 'formObjetoAccion']);
			
			echo Html::input('hidden', 'obj_id', $model->obj_id);
			echo Html::input('hidden', 'txGrabar', 0, ['id'=>'txGrabar']);
		?>
		
		<table border='0'>
		<tr>
		<td><label>Tipo:&nbsp;</label></td>
		<td>
			<?= Html::dropDownList('dlTipo', null, utb::getAux('objeto_taccion','cod','nombre',0,'tobj='.$tobj." and estactual like '%".$est."%' and interno='N'"),
					 ['class' => 'form-control','id'=>'dlTipo', 'onchange' => 'cambiaTipo($(this).val());']); ?>
		</td>
		</tr>
		<tr>
		<td><label>Fecha:&nbsp;</label></td>
		<td>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fecha',
					'name' => 'fecha',
					'value' => date('Y/m/d'),
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>&nbsp;
		<label id='lbVenc'>Vencimiento:&nbsp;</label>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fchVenc',
					'name' => 'fchVenc',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
		</td>
		</tr>
		<tr>
		<td><?= Html::checkbox('ckDesde',false,['id'=>'ckDesde','label'=>'Desde:','onchange' => 'habilitarCampo($(this))', 'data-target' =>'#fechaDesde']) ?> </td>
		<td>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fechaDesde',
					'name' => 'fchDesde',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>&nbsp;
		<?= Html::checkbox('ckHasta',false,['id'=>'ckHasta','label'=>'Hasta:','onchange' => 'habilitarCampo($(this))', 'data-target' => '#fechaHasta']) ?> 
		<?= 
			DatePicker::widget(
				[
					'id' => 'fechaHasta',
					'name' => 'fchHasta',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
		</td>
		</tr>
		<tr>
		<td><label>Expediente: &nbsp;</label></td>
		<td>
			<?= Html::input('text', 'expe', null, ['class' => 'form-control','id'=>'expe','maxlength'=>'20','style'=>'width:200px']); ?>
		</td>
		</tr>
		<tr>
		<td valign='top'><label>Observación: &nbsp;</label></td>
		<td>
			<?= Html::textarea('obs', null, ['class' => 'form-control','id'=>'obs','maxlength'=>'500','style'=>'width:440px; height:100px; max-width:440px; max-height:150px;']); ?>
		</td>
		</tr>
			
		</table>
		
		<?php
		
			ActiveForm::end(); 
			
			if ($accion!=="hab") echo '<script>$("#lbVenc").css("visibility","hidden")</script>';
			if ($accion!=="hab") echo '<script>$("#fchVenc").css("visibility","hidden")</script>';
			
			Pjax::begin(['id' => 'pjaxCambiaTipo', 'enableReplaceState' => false, 'enablePushState' => false]); 
	
				$tipo = intval(Yii::$app->request->get('tipo', 0));
				$desdehasta = 0;
				
				if($tipo > 0)
					$desdehasta = utb::getCampo("objeto_taccion", "Cod = $tipo", "desdehasta");				
				
				echo '<script>$("#ckDesde").prop("disabled",'. $desdehasta . '!== 1)</script>';
				echo '<script>$("#ckHasta").prop("disabled",'. $desdehasta . '!== 1)</script>';
				
			Pjax::end();
		?>
	</div>
</div>

<div style="margin-top:5px;">
	<?= Html::button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'grabar();']); ?>
</div>

<?= $form->errorSummary($model, ['id' => 'errorSummary', 'style' => 'margin-top:5px;']); ?>

<script type="text/javascript">

function mostrarErrores(e){
	
	var $divError = $("#errorSummary"), $contenedorError = $("#errorSummary ul");
	
	$divError.css("display", "block");
	$contenedorError.empty();
	
	
	$.each(e, function(i, str){
		
		console.log(str);
		$error = $("<li />");
		$error.text(str);
		$error.appendTo($contenedorError);
	});
}

function grabar(){
	
	var error = [], est = "";
	
	if($("#dlTipo option:selected").text().trim() === "") error.push("Seleccione un Tipo de Acción");
	if($("#fecha").val() == '') error.push("Ingrese una fecha");
	if( $("#ckHasta").is(":checked") && $("#fchDesde").val() > $("#fchHasta").val() ) error.push("Rango de fecha mal ingresado");
	
	
	
	if (error.length === 0)	
		$("#formObjetoAccion").submit();
	else
		mostrarErrores(error);
}

function cambiaTipo(nuevo){
	
	$.pjax.reload({
		container : "#pjaxCambiaTipo",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"tipo" : nuevo
		}
	});
}

function habilitarCampo($check){
	
	$target = $($check.data("target"));
	
	$target.prop("disabled", !$check.is(":checked"));
}

$(document).ready(function(){
	cambiaTipo();
});
</script>