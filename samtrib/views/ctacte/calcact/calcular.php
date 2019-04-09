<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\BaseUrl;
?>

<div class="row" style="padding:10px;">
	<div class="form-group">
		<label for="tributo" style="width:31%"> Tributo: </label>
		<?=
			Html::dropDownList( 'tributo', null, $arrayTributos, [
				'class' => 'form-control',
				'id' => 'tributo'
			]);
		?>
	</div>
	<div class="form-group">
		<label for="fchvencimiento" style="width:31%"> Vencimiento: </label>
		<?=
			DatePicker::widget([
				'id' => 'fchvencimiento',
				'name' => 'fchvencimiento',
				'dateFormat' => 'dd/MM/yyyy',
				'options' => [
					'class' => 'form-control',
					'style' => 'text-align: center'
				],
			]);
		?>
	</div>
	<div class="form-group">
		<label for="fchpago"  style="width:31%"> Pago: </label>
		<?=
			DatePicker::widget([
				'id' => 'fchpago',
				'name' => 'fchpago',
				'dateFormat' => 'dd/MM/yyyy',
				'options' => [
					'class' => 'form-control',
					'style' => 'text-align: center'
				],
			]);
		?>
	</div>
	<div class="form-group">
		<label for="nominal"  style="width:31%"> Nominal: </label>
		<?=
			Html::input( 'text', 'nominal', null, [ 'id' => 'nominal', 'class' => 'form-control', 'style' => 'text-align:right' ]);
		?>
	</div>	
	<div class="form-group" align='center'>
		<hr>
		<?php echo Html::button('Calcular', ['class' => 'btn btn-primary', 'onclick' => 'f_calcular()']); ?>
		<hr>
	</div>	
	<div class="form-group">
		<label for="montocalculado"  style="width:31%"> Monto Calculado: </label>
		<?=
			Html::input( 'text', 'montocalculado', null, [ 
				'id' => 'montocalculado', 
				'class' => 'form-control solo-lectura', 
				'style' => 'text-align:right', 
				'onkeypress' 	=> 'return justDecimal( $(this).val(), event )'
			]);
		?>
	</div>	
	
	<?php 
		echo Html::errorSummary( [], [ 'id' => 'errorCalcular', 'style' => 'margin-top:10px', 'class' => 'error-summary' ] ); 
	?>  
</div>

<script>

function f_calcular(){
	
	ocultarErrores( "#errorCalcular" );
	
	$.post(
		"<?= BaseUrl::to(['calcular']) ?>",
		{ "fchvenc": $("#fchvencimiento").val(), "fchpago": $("#fchpago").val(), "nominal": $("#nominal").val(), "tributo": $("#tributo").val() }
	).done(function(result) {
		 datos = $.parseJSON( result ); 
		 
		 if ( datos.error == "" )
			$("#montocalculado").val( datos.montocalculado );
		 else	
			mostrarErrores( [datos.error], "#errorCalcular" );
	});
}

</script>
