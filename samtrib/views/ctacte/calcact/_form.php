<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;


$form = ActiveForm::begin([
    'id' => 'form-calcact',
    'enableAjaxValidation' => true,
    'enableClientScript' => true,
    'enableClientValidation' => true,
]); 

?>	

	<div class="form-group">
		<label for="fchdesde" style="width:30%">Fecha Desde:</label>
		<?=
			DatePicker::widget([
				'model' => $model,
				'attribute' => 'fchdesde',
				'dateFormat' => 'dd/MM/yyyy',
				'options' => [
					'class' => 'form-control' . ( $model->scenario == "nuevo" ? "" : " solo-lectura" ),
					'style' => 'width:50%;text-align: center',
					'id' => 'fchdesde'

				],
			]);
		?>
	</div>		
	
	<div class="form-group">
		<label for="fchhasta" style="width:30%">Fecha Hasta:</label>
		<?=
			DatePicker::widget([
				'model' => $model,
				'attribute' => 'fchhasta',
				'dateFormat' => 'dd/MM/yyyy',
				'options' => [
					'class' => 'form-control' . ( $model->scenario == "nuevo" ? "" : " solo-lectura" ),
					'style' => 'width:50%;text-align: center',
					'id' => 'fchhasta'

				],
			]);
		?>
	</div>	

	<div class="form-group">
		<label for="indice" style="width:30%">Indice:</label>
		<?=
			Html::activeInput('text', $model, 'indice', [
				'class' 		=> 'form-control text-right' . ( $model->scenario == "eliminar" ? " solo-lectura" : "" ),
				'maxlength' => '12',
				'onkeypress' 	=> 'return justDecimal( $(this).val(), event )',
				'style' 		=> 'width:50%;'
			]);
		?>
	</div>	
	
	<hr style='margin:10px 0px;'>
	
	<div class="form-group" align="center">
		<?php
			echo Html::submitButton( 'Grabar', [
				'class' => 'btn btn-success'
			]);
    	?>
		
		<?php
			echo Html::button( 'Cancelar', [
				'class' => 'btn btn-primary',
				'onclick'   => '$("#modalDatos").modal("hide")',
			]);
    	?>
	</div>
	
<?php 
	echo $form->errorSummary( $model, [ 'id' => 'errorModal', 'style' => 'margin-top:10px' ] ); 

ActiveForm::end();      	
?>   

<script>

$("#form-calcact").on("beforeSubmit", function(e) {
	
	var form = $(this);
	$.post(
		form.attr("action"),
		form.serialize()
	).done(function(result) {
		 datos = $.parseJSON( result ); 
		 mostrarErrores( datos, "#errorModal" );
	});
	
	return false;
});

</script>