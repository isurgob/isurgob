<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\BaseUrl;

$editarS1 = in_array($scenario, ['nuevos1', 'nuevos2', 'nuevos3']);
$editarS2 = in_array($scenario, ['nuevos2', 'nuevos3']);
$editarS3 = in_array($scenario, ['nuevos3']);

?>

<?php $form= ActiveForm::begin(['id' => 'formSeccion', 'action' => ['grabarseccion'] ]); ?>
		
	<?= Html::input( 'hidden', 'txScenario', $scenario, ['id' => 'txScenario'] ); ?>

	<table width='100%'>
		<tr>
			<td width='1%'><label>S1:</label></td>
			<td>
				<?php if ( !in_array($scenario, ['nuevos2', 'nuevos3']) ) { ?>
					<?= Html::activeInput('text', $model, 's1', [
							'class' => 'form-control text-center ' . ( $editarS1 ? "" : "solo-lectura" ), 
							'id' => 'txS1', 
							'style' => 'width:95%;', 
							'maxlength' => '3'
						]); 
					?>	
				<?php } else { ?>
					<?=
						Html::activeDropDownList( $model, 's1', $arrayS1, [
							'class' 	=> 'form-control ' . ( $editarS1 ? "" : "solo-lectura" ),
							'style'	=> 'width:95%',
							'id' => 'txS1', 
							'prompt' => '',
							'tabIndex'	=> ( $editarS1 ? "0" : "-1" ),
							'onchange' => 'f_cambiarEdicionS1()',
							'data-pjax' => 0
						]);
					?>
				<?php } ?>
			</td>
		</tr>
		<?php if ( isset($model->s2) ) { ?>
			<tr>
				<td width='1%'><label>S2:</label></td>
				<td>
					<?php if ( $scenario != 'nuevos3' ) { ?>
						<?= Html::activeInput('text', $model, 's2', [
								'class' => 'form-control text-center ' . ( $editarS2 ? "" : "solo-lectura" ), 
								'id' => 'txS2', 
								'style' => 'width:95%;', 
								'maxlength' => '3'
							]); 
						?>
					<?php } else { ?>
						<?php Pjax::begin([ 'id' => 'pjaxCambiaS2' ]); ?>
							<?=
								Html::activeDropDownList( $model, 's2', $arrayS2, [
									'class' 	=> 'form-control',
									'style'	=> 'width:95%',
									'id' => 'txS2', 
									'prompt' => ''
								]);
							?>
						<?php pjax::end(); ?>	
					<?php } ?>	
				</td>
			</tr>
		<?php } ?>	
		<?php if ( isset($model->s3) ) { ?>
			<tr>
				<td width='1%'><label>S3:</label></td>
				<td>
					<?= Html::activeInput('text', $model, 's3', [
							'class' => 'form-control text-center ' . ( $editarS3 ? "" : "solo-lectura" ), 
							'id' => 'txS3', 
							'style' => 'width:95%;', 
							'maxlength' => '3'
						]); 
					?>	
				</td>
			</tr>
		<?php } ?>		
	</table>

<?php 
	ActiveForm::end();

	echo $form->errorSummary( $model,[
				'id'	=> 'form_errorSummary',
				'style' => 'margin-top:8px; margin-right: 15px',
			]);
?>		

<hr>

<div id="idBotonesSecciones" style='margin-top:10px' align="center">
	<?php  
		if ( $model->scenario != "" )
			echo Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btnAceptarCuadro', 'onclick' => 'f_grabarSeccion();'])
	?>

	<?= 
		Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btnCancelarCuadro', 'onclick' => '$("' . $idModal . '").modal( "hide" )'])
	?>
</div>


<script type="text/javascript">
	
function f_grabarSeccion(){
	
	var s1 = $("#txS1").val();
		
	ocultarErrores( "#form_errorSummary" );	
	
	$.ajax({
		url: $("#formSeccion").attr( "action" ),
		type: 'post',
		data: $("#formSeccion").serialize(),
		success: function(data) {
			datos = jQuery.parseJSON(data); 
			
			if ( datos.error == "" ){
				
				alert("dd")
			
			}else	
				mostrarErrores( datos.error, "#form_errorSummary" );
		}
	});
}

function f_cambiarEdicionS1(){

	$.post( "<?= BaseUrl::toRoute('obteners2');?>", { "s1": $("#txS1").val() } 
	).success(function(data){
		datos = jQuery.parseJSON(data); 
				
		$("#txS2").find('option').remove();
		
		$("#txS2").append('<option value=""></option>');
		for(e in datos){
			$("#txS2").append('<option value="' + datos[e] + '">' + datos[e] + '</option>');
		}
		
	});
}

</script>