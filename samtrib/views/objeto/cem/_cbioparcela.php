<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl;

?>

<div class="cem-fall-form">
	
	<?php $form = ActiveForm::begin(['id' => 'formCbioParcela']); ?>
	
		<?= Html::input( 'hidden', 'txFallecido', $fall_id, ['id' => 'txFallecido'] ); ?>
		
		<table width='100%'>
			<tr>
				<td width='5%'> <label>Cuadro:</label> </td>
				<td>
					<?=
						Html::activeDropDownList( $model, 'cua_id', $cuadros, [
							'class' 	=> 'form-control',
							'style'	=> 'width:95%',
							'onchange' => 'f_cambiarNC()'
						]);
					?>
				</td>
				<td width='5%'> <label>Cuerpo:</label> </td>
				<td>
					<?=
						Html::activeDropDownList( $model, 'cue_id', $cuerpos, [
							'class' 	=> 'form-control',
							'style'	=> 'width:95%',
							'onchange' => 'f_cambiarNC()'
						]);
					?>
				</td>
				<td width='2%'> <label>Tipo:</label> </td>
				<td>
					<?=
						Html::activeDropDownList( $model, 'tipo', $tipos, [
							'class' 	=> 'form-control',
							'style'	=> 'width:100%',
							'onchange' => 'f_cambiarNC()'
						]);
					?>
				</td>
			</tr>
			<tr>
				<td> <label>Fila:</label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'fila', [
							'class' 	=> 'form-control ' . ( $modelCuadro->fila == 1 ? "" : "solo-lectura" ),
							'style'		=> 'width:95%;',
							'maxlength' => 4,
							'tabindex'	=> ( $modelCuadro->fila == 1 ? "0" : "1" ),
							'onchange' => 'f_cambiarNC()'
						]);
					?>				
				</td>
				<td> <label>Nº:</label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'nume', [
							'class' 	=> 'form-control ' . ( $modelCuadro->nume == 1 ? "" : "solo-lectura" ),
							'style'		=> 'width:95%;',
							'maxlength' => 4,
							'tabindex'	=> ( $modelCuadro->nume == 1 ? "0" : "-1" ),
							'onchange' => 'f_cambiarNC()'
						]);
					?>				
				</td>
				<td> <label>Bis:</label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'bis', [
							'class' 	=> 'form-control ' . ( $modelCuadro->bis == 1 ? "" : "solo-lectura" ),
							'style'		=> 'width:100%;',
							'maxlength' => 1,
							'tabindex'	=> ( $modelCuadro->bis == 1 ? "0" : "-1" ),
							'onchange' => 'f_cambiarNC()'
						]);
					?>				
				</td>
			</tr>
			<tr>
				<td> <label>Objeto:</label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'obj_id', [
							'id'		=> 'txObjeto',
							'class' 	=> 'form-control',
							'style'		=> 'width:95%;',
							'maxlenght' => 8,
							'onchange'	=> 'f_cambiarObjeto()'
						]);
					?>				
				</td>
				<td> <label>Responsable:</label> </td>
				<td colspan='3'>
					<?=
						Html::input( 'text', 'txResponsable', $responsable, [
							'class' 	=> 'form-control solo-lectura',
							'style'		=> 'width:100%;',
							'tabindex' => -1
						]);
					?>				
				</td>
			</tr>
		</table>
	
	<?php
	ActiveForm::end();
	
	echo $form->errorSummary( $model,[
				'id'	=> 'form_errorSummaryCbioParcela',
				'style' => 'margin-top:8px; margin-right: 15px',
			]);
	?>
	<hr>
	<div id="idBotonesCbioParcela" style='margin-top:10px' align='center'>
		<?= 
			Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btnAceptarCbioParcela', 'onclick' => 'f_grabarCbioParcela();'])
		?>

		<?= 
			Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btnCancelarCbioParcela', 'onclick' => '$("' . $idModal . '").modal( "hide" )'])
		?>
	</div>
	
</div>

<script>

function f_cambiarNC(){

	$.ajax({
		url: "<?= BaseUrl::toRoute('obtenerobjeto');?>",
		type: 'POST',
		data: $("#formCbioParcela").serialize(),
		success: function(data) {
			datos = jQuery.parseJSON(data); 
			$("#txObjeto").val( datos.objeto );
			
			f_cambiarObjeto();
		}
	});
}

function f_cambiarObjeto(){

	$.pjax.reload({
		container	: "#pjaxCbioParcela",
		type 		: "POST",
		replace		: false,
		push		: false,
        timeout     : 100000,
		data:{
            "objeto"    	: $("#txObjeto").val()
		}
	});
}

function f_grabarCbioParcela(){
	
	$.ajax({
		url: "<?= BaseUrl::toRoute('grabarcbioparcela');?>",
		type: 'POST',
		data: { 'fall_id': $("#txFallecido").val(), 'obj_id': $("#txObjeto").val() },
		success: function(data) {
			datos = jQuery.parseJSON(data); 
			
			if ( datos.error !== "" )
				mostrarErrores( datos.error, "#form_errorSummaryCbioParcela" );
		}
	});
}

</script>