<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\jui\DatePicker;

use yii\bootstrap\Alert;


use app\utils\db\Fecha;
/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcInteres */
/* @var $form yii\widgets\ActiveForm */

$model = isset($model) ? $model : null;
$mensaje = isset($mensaje) ? $mensaje : null;

$deshabilitar = isset($deshabilitar) ? $deshabilitar : [];
$opcionesBoton = isset($opcionesBoton) ? $opcionesBoton : [];

?>

<style type="text/css">
.calc-interes-form div.row{
	height: auto;
	min-height: 17px;
}

.form-control{
	width : 100%;
}
</style>

<div class="calc-interes-form">


	<div class="row">
		<div class="col-xs-12">
		
			<?php    
     		$form = ActiveForm::begin([
							'id'=>'form-calcinteres',
							'class' => 'form form-inline',
						
							'fieldConfig' => [
        								'template' => "{label}\n<div class=\"col-xs-3\">{input}</div>",
        								'labelOptions' => ['class' => 'col-xs-2 control-label'],
    									]
							]); 
						
			$param = Yii::$app->params;					
			?>
		
			<div class="row" style="text-align:center;">
				<div class="col-xs-2">
				
					<?php
					
					$opciones = [];
					
					echo Html::activeLabel($model, 'fchdesde');
					
					if(in_array('fchdesde', $deshabilitar))
					{
						echo $form->field($model, 'fchdesde', ['template' => '{input}'])->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1])->label(false);
						$opciones = ['style' => 'visibility:hidden;', 'disabled' => 'true'];
					}
										
					echo DatePicker::widget([
											'model' => $model, 
											'attribute' => 'fchdesde',
											'dateFormat' => 'dd/MM/yyyy',
											'options' => array_merge($opciones, array('class' => 'form-control')) 
											]);
					?>
				</div>
			
				<div class="col-xs-2">
				
					<?php
					
					$opciones = [];
					
					echo Html::activeLabel($model, 'fchhasta');
					
					if(in_array('fchhasta', $deshabilitar))
					{
						echo $form->field($model, 'fchhasta', ['template' => '{input}'])->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1])->label(false);
						$opciones = ['style' => 'visibility:hidden;', 'disabled' => true];
					}
					
					echo DatePicker::widget([
													'model' => $model, 
													'attribute' => 'fchhasta',
													 'dateFormat' => 'dd/MM/yyyy',
													'options' => array_merge($opciones, array('class' => 'form-control'))
													]);
					?>
				</div>
			
				<div class="col-xs-2">
					<?php
					echo Html::activeLabel($model, 'indice');				
					$opciones = in_array('indice', $deshabilitar) ? ['disabled' => 'disabled'] : array();
					echo Html::input('text', 'CalcInteres[indice]', $model->indice, array_merge($opciones, array('id' => 'indice', 'class' => 'form-control')));
					?>
				</div>
			
				<div class="col-xs-2">
					<label style="visibility:hidden;">Ejecutar acci&oacute;n</label>
					<?php
						if(!isset($ocultarBoton) || $ocultarBoton == false)
							
							echo Html::submitButton('Grabar', [
								'class' => 'btn btn-success',									
								'style' => 'float:right;width:100%;']);
					?>
				</div>
						
			</div>
		</div>
	</div>
	
	<?php 
		echo $form->errorSummary( $model, [
				
				'id' => 'calc_interes_errorSummary',
				'style' => 'margin-top: 8px',
			
			]); ?>
	

    	<?php 
			
    		ActiveForm::end();      	
    	?>   

</div>