<?php

use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

use app\utils\helpers\DBException;
$this->params['breadcrumbs'][] = ['label' => 'Revaluar inmuebles'];
?>

<div class="inm-revaluo">
	<h1><b>Revaluar inmuebles</b></h1>
	<div style="border-bottom:1px solid #DDDDDD; margin-bottom:5px;"></div>
	
	
		<div class="form center-block" style="padding:5px; margin-top:5px; width:200px;">
		
			<h3 class="text-center"><b>Per&iacute;odo desde:</b></h3>
	
			<table border="0" width="100%">
				<tr>
					<td><b>A&ntilde;o: </b></td>
					<td width="5px"></td>
					<td><?= Html::textInput(null, date('Y'), ['class' => 'form-control', 'style' => 'width:50px;', 'maxlength' => 4, 'id' => 'desde']); ?></td>
					<td width="10px"></td>
					<td><b>Cuota:</b></td>
					<td width="5px"></td>
					<td><?= Html::textInput(null, intval(date('m')) / 2, ['class' => 'form-control', 'style' => 'width:50px;', 'maxlength' => 2, 'id' => 'hasta']) ?></td>
				</tr>
				<tr>
					<td colspan="3" align="right"><?= Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'revaluar();']) ?></td>
					<td></td>
					<td colspan="3" align="left"><?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'cancelar();']) ?></td>
				</tr>
			</table>
		</div>
		
		<div class="error-summary center-block hidden" style="width:300px; margin-top:10px; margin-left:auto; margin-right:auto;">
			<ul></ul>
		</div>
		
		<?php
		
		 echo Alert::widget([
		 	'id' => 'mensajeExito',
		 	'options' => ['class' => 'alert alert-success hidden', 'style' => 'width:300px; margin-top:10px; margin-left:auto; margin-right:auto;'],
		 	'body' => '<b>Datos grabados correctamente.</b>'
		 ]);
		 ?>
		
		
		<?php
		Modal::begin([
			'id' => 'modalRevaluar',
			'header' => '<h1>Revaluar todos los inmuebles</h1>',
			'closeButton' => [
				'class' => 'btn btn-danger btn-small pull-right',
				'onclick' => '$("#modalRevaluar").modal("hide")'
			],
			'footer' => Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'ejecutar(false);']) . '&nbsp;&nbsp;' . Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("#modalRevaluar").modal("hide");']),
			'size' => 'modal-sm'
			 
		]);
		
		?>
		<b>¿Está seguro de revaluar todos los inmuebles?</b>
		
		<?php
		Modal::end();
		?>
		
		
		
		<?php
		Modal::begin([
			'id' => 'modalConfirmacion',
			'header' => '<h1>Revaluar todos los inmuebles</h1>',
			'closeButton' => [
				'class' => 'btn btn-danger btn-small pull-right',
				'onclick' => '$("#modalRevaluar").modal("hide")'
			],
			'footer' => Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'ejecutar(true);']) . '&nbsp;&nbsp;' . Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("#modalConfirmacion").modal("hide");$("#modalRevaluar").modal("hide");']),
			'size' => 'modal-sm'
			 
		]);
		
		?>
		<p><b>Existen valuaciones para períodos posteriores al indicado. <br> Se eliminarán los mismos al procesar.</b></p>
		<p class="text-center"><b>¿Desea proceder de todos modos?</b></p>
		
		<?php
		Modal::end();
		?>
	</div>
</div>

<?php
Pjax::begin(['id' => 'pjaxRevaluar', 'enableReplaceState' => false, 'enablePushState' => false]);

$calcular = Yii::$app->request->post('calcular', null);
$confirmado = filter_var(Yii::$app->request->post('confirmado', false), FILTER_VALIDATE_BOOLEAN);
$desde = intval(Yii::$app->request->post('desde', 0));
$hasta = intval(Yii::$app->request->post('hasta', 0));
$desdeActual = intval(date('Y'));

$errores = [];

if($calcular !== null){
	

	if($desde < $desdeActual || $desde > $desdeActual + 1) array_push($errores, "El año debe ser el actual o el siguiente");
	if($hasta < 1 || $hasta > 12) array_push($errores, "La cuota debe estar entre 1 y 12");
	
	if(count($errores) == 0){
		
		$perdesde = $desde * 1000 + $hasta;
		$sql = "Select Exists (Select 1 From inm_avaluo Where perdesde > $perdesde)";
		
		$existen = yii::$app->db->createCommand($sql)->queryScalar();
		
		if($existen && !$confirmado){
			?>
			<script type="text/javascript">
			$(document).ready(function(){
				pedirConfirmacion();
			});
			</script>
			<?php
		} else {
			
			try{
			
				$sql = "Select sam.uf_inm_avaluo_masivo($perdesde)";
				Yii::$app->db->createCommand( $sql )->execute();
				
		?>
				<script type="text/javascript">
				$(document).ready(function(){
					$("#modalConfirmacion").modal("hide");
					$("#modalRevaluar").modal("hide");
					$("#mensajeExito").removeClass("hidden");
					
					setTimeout(function(){$("mensajeExito").addClass("hidden");}, 5000);
				});
				</script>
		<?php	
				
			} catch (\Exception $e){
				?>
				<script type="text/javascript">
				$(document).ready(function(){
					
					errores = [];
					errores.push("<?= DBException::getMensaje($e) ?>");
					mostrarErrores(errores);
				});
				</script>
				<?php
			}
		}
		
	} else{
		var_dump("hay errores");
		?>
		<script type="text/javascript">
		$(document).ready(function(){
			
		errores = [];
		
		<?php
		foreach($errores as $e){
		?>
		errores.push("<?= $e ?>");
		<?php	
		}
		?>
		
		mostrarErrores(errores);
		$("#modalRevaluar").modal("hide");
		});
		</script>
		<?php
}
}
echo Html::input('hidden', null, null);
Pjax::end();
?>

<script type="text/javascript">
function pedirConfirmacion(){
	
	$("#modalConfirmacion").modal("show");
}

function validar(){
	
	errores = [];
	
	var desde = parseInt($("#desde").val());
	var hasta = parseInt($("#hasta").val());
	var desdeActual = new Date().getFullYear();
	
	if(isNaN(desde) || desde < desdeActual || desde > desdeActual + 1) errores.push("El año debe ser el actual o el siguiente.");
	if(isNaN(hasta) || hasta < 1 || hasta > 12) errores.push("La cuota debe estar entre 1 y 12.");
	
	
	if(errores.length > 0){
		console.log("no paso las validaciones");
		mostrarErrores(errores);
		
		return false;
	}
	
	return true;
}

function mostrarErrores(errores){
	
	$(".error-summary").removeClass("hidden");
	var $contenedor = $(".error-summary ul");
	var $el = null;
	$contenedor.empty();
	
	for(e in errores){
		
		$el = $("<li />");
		$el.text(errores[e]);
		$el.appendTo($contenedor);
	}
}

function revaluar(){
	
	$(".error-summary").addClass("hidden");
	if(!validar()) return;
	
	$("#modalRevaluar").modal("show");
}


function ejecutar(confirmado){
	
	if(!validar()) return;
	
	var desde = hasta = 0;
	
	desde = parseInt($("#desde").val());
	hasta = parseInt($("#hasta").val());	
	
	$.pjax.reload({
		container : "#pjaxRevaluar",
		type : "POST",
		replace : false,
		push : false,
		data : {
			
			"desde" : desde,
			"hasta" : hasta,
			"calcular" : true,
			"confirmado" : confirmado
		}
	});
}

function cancelar(){
	
	$("#desde").val("");
	$("#hasta").val("");
}
</script>