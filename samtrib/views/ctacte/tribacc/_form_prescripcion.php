<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\grids\GridView;

use app\utils\db\utb;

$this->params['breadcrumbs'][] = ['label' => 'Prescripción'];
?>
<?php
$form = ActiveForm::begin(['fieldConfig' => ['template' => '{label}{input}'], 'validateOnSubmit' => false, 'id' => 'formPrescipcion']);

echo Html::input('hidden', 'listar', 'false');
echo Html::input('hidden', 'grabar', true);
echo Html::input('hidden', 'consulta', $consulta, ['id' => 'consulta']);


//campos no modificables pero requeridos
echo $form->field($model, 'orden')->input('hidden', ['class' => 'hidden'])->label(false);
?>
<div class="form-asignacion">
	<h1>Prescripci&oacute;n</h1>
	
	<div class="form" style="padding:10px;">
		<table border="0" width="100%" id="asd">
		
			<tr>
				<td><label>Acci&oacute;n: </label></td>
				<td></td>
				<td colspan="6">
				<?php
				$acciones = [1 => 'Marcar Prescriptos', 2 => 'Desmarcar Prescriptos'];
				echo $form->field($model, 'accion')->dropDownList($acciones, ['prompt' => '', 'style' => 'width:100%;', 'onchange' => 'cambiaAccion($(this).val());', 'style' => 'width:480px;'])-> label(false); 
				?>
				</td>
			</tr>
		
			<tr>
				<td width="60px"><label>Tributo: </label></td>
				<td width="5px"></td>
				<td colspan="6">
				<?php
				$condicion = "tobj <> 0 And est = 'A'";
					
				echo $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib', 'trib_id', 'nombre', 0, $condicion), ['onchange' => 'cambiaTributo($(this).val());', 'id' => 'dlTributo', 'prompt' => '', 'style' => 'width:480px;'])->label(false); 
				?>
				</td>
			</tr>
			
			<tr>
				<td colspan="3"><?= $form->field($model, 'todos')->checkbox(['label' => 'Todos los objetos', 'value' => 1, 'uncheck' => 0, 'onclick' => 'todosObjetos($(this).is(":checked"));'])->label(false); ?></td> 
			</tr>
			
			<tr>
				<td><label>Objeto: </label></td>
				<td></td>
				<td colspan="3">					
					<?php
					Pjax::begin(['id' => 'pjaxTObj', 'enableReplaceState' => false, 'enablePushState' => false]);
					Pjax::begin(['id' => 'pjaxObjeto', 'enableReplaceState' => false, 'enablePushState' => false]);
					
					$obj_id = trim(Yii::$app->request->get('obj_id_nuevo', ''));
					$trib_id = intval(Yii::$app->request->get('trib_id', 0));
					$tipoObjeto= 0;
					
					if($trib_id > 0) $tipoObjeto = utb::getTObjTrib($trib_id);
					
					if($obj_id != '' && $trib_id > 0){
						
						if(strlen($obj_id) < 8 && strlen($obj_id) > 0){

							
							$letra = utb::getCampo('objeto_tipo', "cod = $tipoObjeto", 'letra');

							$obj_id = $letra . str_pad($obj_id, 7, '0', STR_PAD_LEFT);
						}
					} else if($obj_id == '') $obj_id = $model->obj_id;
					
					$model->obj_id = $obj_id;
					$nombreObjeto = utb::getNombObj("'$model->obj_id'");
					
					echo $form->field($model, 'obj_id', ['options' => ['style' => 'display:inline-block;']])
					->textInput(['id' => 'codigoObjeto', 'maxlength' => 8, 'onchange' => 'cambiaObjeto($(this).val());', 'style' => 'width:70px;', 'disabled' => (in_array($consulta, [1, 2]) || $tipoObjeto <= 0)])->label(false); 
				
					
					echo Html::button('<i class="glyphicon glyphicon-search"></i>', 
									['class' => 'bt-buscar', 'id' => 'botonObjeto',
									'data-toggle' => 'modal', 'data-target' => '#modalBusquedaObjeto',
									'disabled' => (in_array($consulta, [1, 2]) || $tipoObjeto <= 0)
									]);
					
					
					echo Html::textInput(null, $nombreObjeto, ['style' => 'width:380px;', 'id' => 'nombreObjeto', 'class' => 'form-control solo-lectura']);
					
					Modal::begin([
						'id' => 'modalBusquedaObjeto',
						'header'=>'<h2>Búsqueda de objeto</h2>',
						'toggleButton' => false,
						
						'closeButton' => [
						  'label' => '<b>X</b>',
						  'class' => 'btn btn-danger btn-sm pull-right',
						],
						'size' => 'modal-lg'
					]);
					
					echo $this->render('//objeto/objetobuscarav', ['txCod' => 'codigoObjeto', 'txNom' => 'nombreObjeto', 'id' => 'lisOpTribacc', 'tobjeto' => $tipoObjeto, 'selectorModal' => '#modelBusquedaObjeto']);

					Modal::end();
					Pjax::end();
										
					
					$trib_id = intval(Yii::$app->request->get('trib_id', -1));
					$obj_id = trim(Yii::$app->request->get('obj_id', ''));
					
					
					
					if($trib_id > -1){
						
						?>
						<script>
						$(document).ready(function(){
						var usa = false;
						<?php
						
						//se habilita el campo subcta si usa subcta
						$usa = utb::getCampo('trib', "trib_id = $trib_id", 'uso_subcta');
						if($usa) echo 'usa = true;';
						
						$tobj = utb::getTObjTrib($trib_id);
						
						
						if($obj_id === null || trim($obj_id) == '' || utb::getTObj($obj_id) != $tobj){

						?>
						
						$("#codigoObjeto").val("");
						$("#nombreObjeto").val("");
						<?php	
						}
						?>
						});
						</script>
						<?php
					}
					
					Pjax::end();
					?>			
				</td>
				<td></td>
				<td><label>Cta: </label></td>
				<td><?= $form->field($model, 'subcta', ['options' => ['style' => 'margin-bottom:0px;']])->textInput(['style' => 'width:50px;', 'maxlength' => 2, 'id' => 'subcta', 'disabled' => true])->label(false); ?></td>
				<td width="100px"></td>
			</tr>
			
			<tr>
				<td><label>Período: </label></td>
				<td></td>
				<td width="150px">
					<label>Desde:</label>
					<?php
					echo $form->field($model, 'adesde', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 4])->label(false);
					echo $form->field($model, 'cdesde', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 3])->label(false);
					?>
				</td>
				<td width="5px"></td>
				<td width="350px">
					<label>Hasta:</label>
					<?php
					echo $form->field($model, 'ahasta', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 4, 'id' => 'ahasta'])->label(false);
					echo $form->field($model, 'chasta', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 3, 'id' => 'chasta'])->label(false);
					?>
				</td>
			</tr>
			
			<tr id="filaExpe">
				<td><label>Expediente: </label></td>
				<td></td>
				<td><?= $form->field($model, 'expe')->textInput(['maxlength' => 12, 'id' => 'expe'])->label(false); ?></td>
			</tr>
			
			<tr id="filaObs">
				<td valign="top"><label>Obs: </td>
				<td></td>
				<td colspan="6"><?= $form->field($model, 'obs')->textarea(['style' => 'width:480px; max-width:480px; height:75px; max-height:75px;', 'maxlength' => 250, 'id' => 'obs'])->label(false); ?></td>
			</tr>
		</table>
		
		<table border="0" width="760px">
		
		<tr>
			<td colspan='2'>
				<br><div id="error" style="display:none;" class="alert alert-danger alert-dismissable"></div>
			</td>
		</tr>	
		</table>
	</div>
</div>

<div style="margin-top:5px;">
<?php
/*
 * se dibujan los botones de grabar, cancelar y demas dependiendo del tipo de consulta
 */
 
 //almacena los botones que se van a dibujar
$botones = [];

$botonAceptar = Html::button('Grabar',['class' => 'btn btn-success', 'onclick' => '$("#formPrescipcion").submit();']);

array_push($botones, $botonAceptar);

foreach($botones as $b){
	
	echo $b;
	echo '&nbsp;';
}

?>
</div>
<?php
ActiveForm::end();

?>

<div style="margin-top:10px;">

<?php
echo $form->errorSummary($model);

if(isset($mensaje) && $mensaje != '')
	echo Alert::widget([
		'id' => 'alertaMensaje',
		'options' => ['class' => 'alert-success alert-dissmisible'],
		'body' => $mensaje
	]);
	
	?>
	<script type="text/javascript">
	setTimeout(function(){$("#alertaMensaje").fadeOut();}, 5000);
	</script>
	<?php
?>
</div>

<script type="text/javascript">
function cambiaObjeto(nuevo){
	
	$("#nombreObjeto").val("");
	var trib_id = $("#dlTributo").val();
	
	$.pjax.reload({
		container : "#pjaxObjeto",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"obj_id_nuevo" : nuevo,
			"trib_id" : trib_id
		}
	});
}

function cambiaTributo(nuevo){
	
	var obj_id = $("#codigoObjeto").val();
	
	$.pjax.reload({
		container : "#pjaxTObj",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"trib_id" : nuevo,
			"obj_id" : obj_id
		}
	});
}

function todosObjetos(marcado){
	
	$("#codigoObjeto").prop("disabled", marcado);
	$("#subcta").prop("disabled", marcado);
}

function cambiaAccion(nueva){
	
	nueva = parseInt(nueva);
	
	
	if(isNaN(nueva) || nueva !== 1){
		
		$("#filaExpe").css("visibility", "hidden");
		$("#filaObs").css("visibility", "hidden");
	} else {
		
		$("#filaExpe").css("visibility", "visible");
		$("#filaObs").css("visibility", "visible");
	}
}

$(document).ready(function(){
	
<?php
if($model->trib_id !== null && trim($model->trib_id) != '') echo "cambiaTributo($model->trib_id);";
?>
cambiaAccion(<?= $model->accion ?>);
});

$(document).on("pjax:error", function(xhr, textStatus, error, options){
	xhr.preventDefault();
	xhr.stopPropagation();
});
</script>