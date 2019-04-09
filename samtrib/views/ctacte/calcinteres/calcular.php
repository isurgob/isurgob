<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\widgets\Pjax;
use yii\helpers\BaseUrl;

use app\models\ctacte\CalcInteres;


?>

<style type="text/css">
#calcinteres-form-calcular div.row{
	height : auto;
	min-height : 17px;
}
</style>

<?php

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcInteres */

/* @var $monto app\controllers\ctacte\CalcInteresController Monto calculado cuando la ejecución fue un exito.*/

/* @var $error app\controllers\ctacte\CalcInteresController Mensaje de error a mostrar en caso de que se haya producido.*/
/* @var $fchvencimiento app\controllers\ctacte\CalcInteresController Valor de la fecha desde anteriormente ingresada en caso de que se haya producido une error. */
/* @var $fchpago app\controllers\ctacte\CalcInteresController Valor de la fecha hasta anteriormente ingresada en caso de que se haya producido un error. */
/* @var $nominal app\controller\ctacte\CalcInteresController Valor del nominal anteriormente ingresado en caso de que se haya producido un error. */


//se declaran e inicializan con null las variables
$model = new CalcInteres();
$error = isset($error) ? $error : null;


?>

<style type="text/css">
#formCalcularInteres div.row{
	height : auto;
	min-height: 17px !important;
}

.form-control{
	width : 100%;
}
</style>

<?php

?>

<div class="row">
	<div class="col-xs-12">
				
		<div class="row" style="text-align:center;">
					
			<div class="col-xs-3 form-group required">
				<label class="control-label" for="fchvencimiento">Fecha de vencimiento</label>
					
				<?php 
					echo DatePicker::widget(
											[
											'id' => 'fchvencimiento',
											'dateFormat' => 'dd/MM/yyyy',
											'options' => ['class' => 'form-control']
											]
											);
				?>
						
				<div class="help-block"></div>
			</div>
			
			<div class="col-xs-3 form-group required">
				<label class="control-label" for="fchpago">Fecha de pago</label>
					
				<?php 
					echo DatePicker::widget(
											[
											'id' => 'fchpago',
											'dateFormat' => 'dd/MM/yyyy',
											'options' => ['class' => 'form-control']
											]
											);
				?>



				<div class="help-block"></div>
			</div>
								
			<div class="col-xs-2 form-group required">
				<label class="control-label" for="nominal">Nominal</label>
				<input type="text" id="nominal" class="form-control">
				
				<div class="help-block"></div>
			</div>
			<?php
			Pjax::begin(['id' => 'calcinteres-calcular']);
			?>
			<div class="col-xs-2 form-group">
				<label></label>
				<?php				
				
				
				 echo Html::button('Calcular',
				 					[
									'class' => 'btn btn-md btn-primary', 
									'value' => 'ejecutar', 
									'style' => 'width:100%;',
									'data-pjax' => 'true',
									'onclick' => '$.pjax.reload({push : "false", url : "' . BaseUrl::to(['calcular']) . '", container : "#calcinteres-calcular", data : {"fchvencimiento" : $("#fchvencimiento").val(), "fchpago" : $("#fchpago").val(), "nominal" : $("#nominal").val(), "ejecutar" : "ejecutar"  }, type : "POST" } );'
									]);
									
									
				
				 ?>
			</div>
			
			
			<div class="col-xs-2 form-group" id="calcinteres-calcular-framento">
				<label for="monto">Monto calculado</label>
				
				<?php
				
				 echo Html::input('text', null, isset($monto) ? $monto : 0, ['disabled' => 'disabled', 'id' => 'monto', 'class' => 'form-control']);
				 
				 
				 if($error != null)
				{
		 		?>
					<script type="text/javascript">
					$("#calcinteres-calcular-error").removeClass("hidden");
					$("#calcinteres-calcular-error p").text("<?php echo $error ?>");
					</script>
				 <?php
				 }
				 
				 
				 
				 ?>
			</div>
			
		<?php
		Pjax::end();
		?>
		</div>
		
	</div>
</div>

<div class="row hidden" id="calcinteres-calcular-error">			
	<div class="col-xs-12">
		<div class="alert alert-dismissible alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		
			<p>
			</p>
		</div>
	</div>
</div>