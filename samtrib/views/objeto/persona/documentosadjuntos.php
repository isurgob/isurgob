<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\helpers\BaseUrl;

$this->params['breadcrumbs'][] = ['label' => 'Personas','url'=>['view','id'=>$doc['id']]];
$this->params['breadcrumbs'][] = 'Documentos Adjuntos';
?>
<?php 
$form = ActiveForm::begin([
				'id'=>'frmAdjunto',
				'options' => [
						'enctype' => 'multipart/form-data',
					],
				'fieldConfig' => ['template' => '{input}']
			]);	
			
			echo "<label>Contribuyente:</label>";
			echo Html::input('text', 'txId', $doc['id'], [
					'id' => 'txId',
					'class' => 'form-control solo-lectura',
					'style' => 'width:90px;text-align:center',
					'tabindex' => -1
				]);
				
			echo Html::input('text', 'txNombre', $doc['nombre'], [
					'id' => 'txNombre',
					'class' => 'form-control solo-lectura',
					'style' => 'width:400px;text-align:left',
					'tabindex' => -1
				]);	
	?>
	<div class="form" id='DivDatos' style="padding:10px;margin-top:20px;">
	
	<table border="0" width="100%">	
		<tr>
			<td valign='top' class="text-center imagen" >
				<p align='left'><label><u>Foto:</u></label></p>
				<div style='max-width:300px;float:left'>
					<img src="<?= $doc['foto'] ?>" class="img img-responsive img-thumbnail logo" id="representacionFoto" alt="Foto">
					<?= Html::fileInput('ImgFoto',$doc['foto'],[
							'id' => 'ImgFoto',
							'class' => 'imageLoader hidden', 'data-target' => '#representacionFoto'
						]);
					?>
				</div>
				<div style='width:30px; height: 50px;float:left'>
					<?= Html::button('<span class="glyphicon glyphicon-search"></span>', [
							'class' => 'bt-buscar pull-right',
							'onclick' => 'cargarFoto($("#ImgFoto"));',
						]);
					?>
				</div>
			</td>
			<td valign='top' class="text-center imagen" >
				<p align='left'><label><u>Fotocopia Documento:</u></label></p>
				<div style='max-width:300px;float:left'>
					<img src="<?= $doc['documento'] ?>" class="img img-responsive img-thumbnail logo" id="representacionDocumento" alt="Documento">
					<?= Html::fileInput('ImgDoc',$doc['documento'],[
							'id' => 'ImgDoc',
							'class' => 'imageLoader hidden', 'data-target' => '#representacionDocumento'
						]);
					?>
				</div>
				<div style='width:30px; height: 50px;float:left'>
					<?= Html::button('<span class="glyphicon glyphicon-search"></span>', [
							'class' => 'bt-buscar pull-right',
							'onclick' => 'cargarFoto($("#ImgDoc"));',
						]);
					?>
				</div>
			</td>
			<td valign='top' class="text-center imagen" >
				<p align='left'><label><u>Formulario Inscripción Monotributo:</u></label></p>
				<div style='max-width:300px;float:left'>
					<img src="<?= $doc['monotributo'] ?>" class="img img-responsive img-thumbnail logo" id="representacionMonotributo" alt="Inscripción Monotributo">
					<?= Html::fileInput('ImgMonotributo',$doc['monotributo'],[
							'id' => 'ImgMonotributo',
							'class' => 'imageLoader hidden', 'data-target' => '#representacionMonotributo'
						]);
					?>
				</div>
				<div style='width:30px; height: 50px;float:left'>
					<?= Html::button('<span class="glyphicon glyphicon-search"></span>', [
							'class' => 'bt-buscar pull-right',
							'onclick' => 'cargarFoto($("#ImgMonotributo"));',
						]);
					?>
				</div>
			</td>
		</tr>
	</table>
	</div>
	
	<?php if ($error != ""){ ?>
		<div id="errorSummaryModel" class="error-summary" style="margin-top:10px;"><p>Por favor corrija los siguientes errores:</p><ul><?=$error?></ul></div>
	<?php } ?>
	
	<div id='DivBotones' style="margin-top:10px;">
	<?php
		echo Html::submitButton('Aceptar', ['class' => 'btn btn-success','id'=>'btAceptar']); 
		echo '&nbsp;&nbsp;';
		echo Html::a('Cancelar', ['view','id' => $doc['id']], ['class' => 'btn btn-primary','id'=>'btCancelar']);
	?>
	</div>
<?php

ActiveForm::end();

//echo $form->errorSummary($modelAnexo, ['style' => 'margin-top:10px;']);

?>

<script>

// -------------------- Funciones --------------------//
function cargarFoto($target){
	$target.click();
}
// --------------------------------------------------//

// ------------------- Eventos -------------------//

$(document).ready(function()
{
	$(".imageLoader").change(function(){
		
		$(".imageLoader").removeClass("active");
		$(this).addClass("active");
		var input = $(this)[0];
      	var file = input.files[0];
      	
		// load file into preview pane
		var reader = new FileReader();

		reader.onload = function(e){
			$imagen= $($(".imageLoader.active").data("target"));
			$imagen.attr('src', e.target.result);
     	};
     	
     	reader.readAsDataURL(file);
	});
	
	$(".logo").on("error", function(){
		$(this).attr("src", "https://placehold.it/150x100.png?text=Imagen%20no%20disponible.");
		$(this).attr("alt", "Imagen no disponible.");
	});
});
// ------------------- Fin Eventos -------------------//
</script>