<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;

use yii\widgets\ActiveForm;

use yii\jui\DatePicker;

use yii\bootstrap\ActiveField;
use yii\bootstrap\Tabs;

use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\PlanConfig */
/* @var $form yii\widgets\ActiveForm */
/* @var $dpCuentas yii\data\SqlDataProvider */


$templateInline = '<div style="display:inline-block; width:29%;">{label}</div><div style="display:inline-block; width:59%;">{input}</div>';

$templatePorc = '<div style="display:inline-block; width:100%;" class="pull-right">' .
					'<div class="input-group">' .
						'{input}' .
						'<span class="input-group-addon">%</span>' .
					'</div>' .
				'</div>';
				


?>

<style type="text/css" rel="stylesheet">
.planConfigForm .row{
	height : auto;
	min-height : 5px;
}

.planConfigForm .row{
	margin-top : 10px;
}

.planConfigForm .row{
	margin-top : 0px;
}

.planConfigForm .row:first-of-type{
	margin-top : 0px;
}

.planConfigForm .form-control{
	width : 100%;
}

.planConfigForm fieldset{
	padding : 10px;
}

.planConfigForm fieldset legend{
	margin-bottom : 5px;	
}

.planConfigForm .form{
	padding-bottom : 3px;
	padding-left : 15px;
	padding-right : 15px;
	padding-top : 3px;
	margin-top : 2px;
}

.planConfigForm h5{
	text-align : center;
	margin-top : 0px;
	margin-bottom:3px;
}
</style>




<div class="planConfigForm">
	 
    <?php 
    $form = ActiveForm::begin(['id' => 'planConfigForm', 'fieldConfig' => ['template' => '{label}{input}' ], 'validateOnSubmit' => false ] );
    
    
    
    if($consulta != 1)
    {
    	?>
    	<div class="row">
    		<div class="col-xs-12">
    			<h1 style="display:inline-block; margin: 6px 0px 4px 0px;"> <?php echo $titulo; ?> </h1>
    		</div>
    	</div>
    	<?php	
    }
    
     
     
    ?>
    
    	<!-- primera fila -->
    	<div class="form" style="padding:3px 15px;">
    		<table border="0" width="100%">
    			<tr>
    				<td>
    					<?php
						echo $form->field($model, 'cod')->textInput(['readonly' => 'readonly', 'id' => 'planConfigFormCodigo', 'style' => 'width:40px;']);
						?>
    				</td>
    				<td width="5px"></td>
    				<td>
    					<?php
						echo $form->field($model, 'nombre')->textInput(['style' => 'width:250px;', 'id' => 'planConfigFormNombre']);
						?>
    				</td>
    				<td width="5px"></td>
    				<td>
    					<?php
						echo $form->field($model, 'sistema')->dropDownList( $extras['sistemas'], ['class' => 'form-control', 'prompt' => 'Seleccionar...', 'style' => 'width:120px;', 'id' => 'planConfigFormSistema'] );
						?>
    				</td>
    				<td width="5px"></td>
    				<td>
    					<?= $form->field($model, 'texto_id', ['template' => '<div style="display:inline-block;margin-right:5px;">{label}</div><div style="display:inline-block;">{input}</div>'])
    					->dropDownList($extras['texto'], ['prompt' => 'Seleccionar...', 'id' => 'planConfigFormTexto']); ?>
    				</td>
    			</tr>
    		</table>
			<div class="row">
				<div class="col-xs-2">
					
				</div>
			
				<div class="col-xs-5">
					
				</div>
			
				<div class="col-xs-3">
					
				</div>
				
				<div class="col-xs-2">
					
				</div>
			</div>
		</div>
		<!-- fin de la primera fila -->
		
		
		<!-- segunda fila -->
		<div class="form" style="padding:3px 15px; margin-top:3px;">
		
			
			
			<div class="row">
				<div class="col-xs-12">
					<h4 style="display:inline-block; margin:0px; margin-top:4px;"><label>Restricciones</label></h4>
				
					<div class="pull-right">
				
					<?php
					echo $form->field($model, 'sinplan', ['options' => ['style' => 'display:inline-block;'] ] )->checkbox(['uncheck' => 0]);
					?>
										
					<label style="margin-left:15px;">Vigencia:</label>
					
					<?php
					echo $form->field(
								$model, 
								'vigenciadesde', 
								['options' => [
										'style' => 'display:inline-block; width:25%;'
											] 
								] )
								->widget(
									DatePicker::classname(), 
									[
										'dateFormat' => 'dd/MM/yyyy', 
										'options' => [
													'class' => 'form-control', 
													'maxlenght' => 10, 
													'style' => 'width:70%;', 
													'id' => 'planConfigFormVigenciaDesde'
													]
									]
								);
					?>
					
					<?php
					echo $form->field(
									$model, 
									'vigenciahasta', 
									['options' => [
											'style' => 'display:inline-block; width:25%;'
												] 
									] 
									)
									->widget(
										DatePicker::classname(), 
										[
											'dateFormat' => 'dd/MM/yyyy', 
											'options' => [
														'class' => 'form-control', 
														'placeholder' => 'Sin límite', 
														'maxlenght' => 10, 
														'style' => 'width:70%;',
														'id' => 'planConfigFormVigenciaHasta'
														]
										]
									);
					?>
					</div>
					
				</div>	
				
			</div>
		</div>
		<!-- fin segunda fila -->
		
		<!-- tercer fila -->
			<div class="row">
				<!-- Periodo al dia -->
				<div class="col-xs-4">
					
					<div class="form" id="aldia"  style="padding:3px 15px;">
						
						
						<h5><label>Per&iacute;odo al d&iacute;a</label></h5>
											
						<div>	
						<?php
							
							
							$opciones = ['data-target' => '#aldia', 'class' => 'checkSinLimite', 'value' => 0, 'label' => 'Sin límite', 'uncheck' => 1, 'id' => 'planConfigFormAldia'];
								
							if($model->aldia != null && $model->aldia == 0)
								$opciones = array_merge($opciones, ['checked' => 'checked']);
							
							
							//echo Html::input('hidden', 'PlanConfig[aldia]', 1);						
							echo $form->field($model, 'aldia', ['template' => '{input}{label}'])->checkbox($opciones);//->input('checkbox', $opciones);
						?>
						</div>
					
						<div class="row">
							<div class="col-xs-12">
							
								<label style="display:inline-block; width:22%;">Desde:</label>
							
								<div style="display:inline-block; width:40%;">
									<?php
										echo $form->field($model, 'aldiadesdea', ['template' => '{label}{input}'] )->textInput(['maxlength' => 4, 'style' => 'width:60%; text-align:right;', 'id' => 'planConfigFormAldiadesdea'])->label('Año');
						
									?>
								</div>
								
								<div style="display:inline-block; width:35%;">
								<?php
									echo $form->field($model, 'aldiadesdec', ['template' => '{label}{input}'] )->textInput(['maxlength' => 3, 'style' => 'width:60%; text-align:right;', 'id' => 'planConfigFormAldiadesdec'])->label('Per');
								
								?>
								</div>
							</div>
						</div>						
						
						<div class="row">
							<div class="col-xs-12">
							
								<label style="display:inline-block; width:22%;">Hasta:</label>
							
								<div style="display:inline-block; width:40%;">
									<?php
										echo $form->field($model, 'aldiahastaa', ['template' => '{label}{input}'] )->textInput(['maxlength' => 4, 'style' => 'width:60%; text-align:right;', 'id' => 'planConfigFormAldiahastaa'])->label('Año');
									
									?>
								</div>
								
								<div style="display:inline-block; width:35%;">
								<?php
									echo $form->field($model, 'aldiahastac', ['template' => '{label}{input}'] )->textInput(['maxlength' => 3, 'style' => 'width:60%; text-align:right;', 'id' => 'planConfigFormAldiahastac'])->label('Per');
								
								?>
								</div>
							</div>
						</div>						
					</div>
				</div>
				<!-- fin periodo al dia -->
				
				
				<!-- Aplicable a posiciones -->
				<div class="col-xs-4">
					
					
					<div class="form" id="aplica"  style="padding:3px 15px;">	
						<h5><label>Aplicable a posiciones</label></h5>
					
						<div>	
						<?php
							
							$opciones = ['data-target' => '#aplica', 'class' => 'checkSinLimite', 'value' => 0, 'label' => 'Sin límite', 'uncheck' => 1, 'id' => 'planConfigFormAplica'];
								
							if($model->aplica != null && $model->aplica == 0)
								$opciones = array_merge($opciones, ['checked' => 'checked']);
							
							
							//echo Html::input('hidden', 'PlanConfig[aplica]', 1);						
							echo $form->field($model, 'aplica', ['template' => '{input}{label}'])->checkbox($opciones);
						
						?>
						</div>
						
						
						<div class="row">
							<div class="col-xs-12">
							
								<label style="display:inline-block; width:22%;">Desde:</label>
							
								<div style="display:inline-block; width:40%;">
									<?php
										echo $form->field($model, 'aplicadesdea', ['template' => '{label}{input}'] )->textInput(['maxlength' => 4, 'style' => 'width:60%; text-align:right;', 'id' => 'planConfigFormAplicadesdea'])->label('Año');
									
									?>
								</div>
								
								<div style="display:inline-block; width:35%;">
								<?php
									echo $form->field($model, 'aplicadesdec', ['template' => '{label}{input}'] )->textInput(['maxlength' => 3, 'style' => 'width:60%; text-align:right;', 'id' => 'planConfigFormAplicadesdec'])->label('Per');
								
								?>
								</div>
							</div>
						</div>						
						
						<div class="row">
							<div class="col-xs-12">
							
								<label style="display:inline-block; width:22%;">Hasta:</label>
							
								<div style="display:inline-block; width:40%;">
									<?php
										echo $form->field($model, 'aplicahastaa', ['template' => '{label}{input}'] )->textInput(['maxlength' => 4, 'style' => 'width:60%; text-align:right;', 'id' => 'planConfigFormAplicahastaa'])->label('Año');
									
									?>
								</div>
								
								<div style="display:inline-block; width:35%;">
								<?php
									echo $form->field($model, 'aplicahastac', ['template' => '{label}{input}'] )->textInput(['maxlength' => 3, 'style' => 'width:60%; text-align:right;', 'id' => 'planConfigFormAplicahastac'])->label('Per');
								
								?>
								</div>
							</div>
						</div>	
					
					</div>
				</div>
				<!-- fin aplicable a posiciones -->
				
				
				
				<!-- deuda nominal -->
				<div class="col-xs-4">
					
					
						
					<div class="form" id="deuda"  style="padding:3px 15px;">
					
						<h5><label>Deuda nominal</label></h5>
					
						<div>	
						<?php
							
							$opciones = ['data-target' => '#deuda', 'class' => 'checkSinLimite', 'value' => 0, 'label' => 'Sin límite', 'uncheck' => 1, 'id' => 'planConfigFormDeuda'];
								
							if($model->deuda != null && $model->deuda == 0)
								$opciones = array_merge($opciones, ['checked' => 'checked']);
							
							
							//echo Html::input('hidden', 'PlanConfig[deuda]', 1);						
							echo $form->field($model, 'deuda', ['template' => '{input}{label}'])->checkbox($opciones);
						
						?>
						</div>
										
						<div class="row">
							<div class="col-xs-12">
								<?php
									echo $form->field($model, 'mindeuda', ['template' => $templateInline])->textInput(['style' => 'width:50%; text-align:right;', 'maxlength' => 7, 'id' => 'planConfigFormMinDeuda'])->label('Mínimo');
								?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-xs-12">
								<?php
									echo $form->field($model, 'maxdeuda', ['template' => $templateInline])->textInput(['style' => 'width:50%; text-align:right;', 'maxlength' => 7, 'id' => 'planConfigFormMaxDeuda'])->label('Máximo');
								?>
							</div>
						</div>
					</div>
					
				</div>
				<!-- fin deauda nominal -->
			</div>
		
		<!-- fin tercer fila -->
		
		
		<!-- cuarta fila -->
			<div class="row">
			
				<!-- Cantidad de cuotas -->
				<div class="col-xs-4">
					
					<div class="form" id="cantidadCuota"  style="padding:3px 15px; height:107px;">
						
						
						<h5><label>Cantidad de cuotas</label></h5>
						
						<script type="text/javascript">
							function checkClickCantCuotas($check){
								
								var id = "#" + $check.attr("id");
								var checked = $check.is(":checked");
								var valor = $check.val();
								var $input = $("#planConfigFormCantCuotas");
								
								if(checked)
								{
									$input.val(valor);
									$("#cantidadCuota").find("input:not('.check')").attr("disabled", "disabled");
									$($check.data("ina")).removeAttr("checked");
								}
								else{
									$input.val(1);
									$("#cantidadCuota").find("input:not('.check')").removeAttr("disabled");
								}
							}
						</script>
						
						
						<div>	
						<?php
							$valor = $model->cantcuotas != null ? $model->cantcuotas : 1;
							
							echo $form->field($model, 'cantcuotas', ['options' => ['style' => 'display:none;']])->input('hidden', ['id' => 'planConfigFormCantCuotas', 'class' => 'check'])->label('');;
						
							
							$opc = [];
							$sinLimite = ['data-ina' => '#planConfigFormCheckPeriodosCantCuotas', 'class' => 'check', 'onclick' => 'checkClickCantCuotas($(this))', 'label' => 'Sin límite', 'value' => 0, 'id' => 'planConfigFormCheckSinLimiteCantCuotas', 'style' => ' text-align:right;'];
							
							$periodo = ['data-ina' => '#planConfigFormCheckSinLimiteCantCuotas', 'class' => 'check', 'onclick' => 'checkClickCantCuotas($(this))', 'label' => 's/períodos', 'value' => 2, 'style' => 'margin-left:5px; text-align:right;', 'id' => 'planConfigFormCheckPeriodosCantCuotas'];
							
							//echo $form->field($model, 'cantcuotas')->checkbox($opciones);//->input('checkbox', $opciones);
							echo Html::checkbox(null, $model->cantcuotas == 0, $sinLimite);
							echo Html::checkbox(null, $model->cantcuotas == 2, $periodo);
							
						?>
						
						<?php
							//echo $form->field($model, );
						?>
						</div>
										
						<div class="row">
							<div class="col-xs-12">
								<?php
									echo $form->field($model, 'mincantcuo', ['template' => $templateInline, 'options' => ['style' => 'margin-bottom:0px;']] )->textInput(['style' => 'width:50%; text-align:right;', 'maxlength' => 3, 'id' => 'planConfigFormMinCantCuo'])->label('Mínimo');
								?>
							</div>
						</div>
					
						<div class="row">
							<div class="col-xs-12">
								<?php									
									echo $form->field($model, 'maxcantcuo', ['template' => $templateInline, 'options' => ['style' => 'margin-bottom:0px;']] )->textInput(['style' => 'width:50%; text-align:right;', 'maxlength' => 3, 'id' => 'planConfigFormMaxCantCuo'])->label('Máximo');
								?>
							</div>
						</div>
						
						
					</div>
				</div>
				<!-- fin cantidad de cuotas -->
				
				
				<!-- Monto de cuotas -->
				<div class="col-xs-4">
					
					
					<div class="form" id="montoCuota"  style="padding:3px 15px;">	
						<h5><label>Monto de cuotas</label></h5>
						
						<div>	
						<?php

							$opciones = ['data-target' => '#montoCuota', 'class' => 'checkSinLimite', 'value' => 0, 'label' => 'Sin límite', 'uncheck' => 1, 'id' => 'planConfigFormImporteCuo'];
								
							if($model->importecuota != null && $model->importecuota == 0)
								$opciones = array_merge($opciones, ['checked' => 'checked']);
							
							
							//echo Html::input('hidden', 'PlanConfig[importecuota]', 1);						
							echo $form->field($model, 'importecuota', ['template' => '{input}{label}'])->checkbox($opciones);//->input('checkbox', $opciones);
						
						?>
						</div>
										
						<div class="row">
							<div class="col-xs-12">
								<?php
									echo $form->field($model, 'minmontocuo', ['template' => $templateInline, 'options' => ['style' => 'margin-bottom:0px;'] ] )->textInput(['style' => 'width:50%; text-align:right;', 'maxlength' => 5, 'id' => 'planConfigFormMinMontoCuo'])->label('Mínimo');
								?>
							</div>
						</div>
					
						
						<div class="row">
							<div class="col-xs-12">
								<?php
									echo $form->field($model, 'maxmontocuo', ['template' => $templateInline, 'options' => ['style' => 'margin-bottom:0px;']] )->textInput(['style' => 'width:50%; text-align:right;', 'maxlength' => 5, 'id' => 'planConfigFormMaxMontoCuo'])->label('Máximo');
								?>
							</div>
						</div>
					
					</div>
				</div>
				<!-- fin monto de cuotas -->
				
				
				
				<!-- quita -->
				<div class="col-xs-4">
					
					
						
					<div class="form"  style="padding:3px 15px; height:107px;">
					
						<h5><label>Quita</label></h5>
										
						<div class="row">
							<div class="col-xs-6">
								<label>Nominal</label>
							</div>
							<div class="col-xs-6">
								<?php
									echo $form->field($model, 'descnominal', ['template' => $templatePorc])->textInput(['placeholder' => '0.00', 'style' => 'margin:0px; text-align:right;', 'maxlength' => 5, 'id' => 'planConfigFormDescNominal'])->label(false);
								?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-xs-6">
								<label>Accesorio</label>
							</div>
							<div class="col-xs-6">
								<?php
									echo $form->field($model, 'descinteres', ['template' => $templatePorc])->textInput(['placeholder' => '0.00', 'style' => 'margin:0px; text-align:right;', 'maxlength' => 5, 'id' => 'planConfigFormDesInteres'])->label(false);
								?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-xs-6">
								<label>Multa</label>
							</div>
							<div class="col-xs-6">
								<?php
									echo $form->field($model, 'descmulta', ['template' => $templatePorc])->textInput(['placeholder' => '0.00', 'style' => 'margin:0px; text-align:right;', 'maxlength' => 5, 'id' => 'planConfigFormDescMulta'])->label(false);
								?>
							</div>
						</div>
					</div>
					
				</div>
				<!-- fin quita -->
				
			</div>
			<!-- fin cuarta fila -->
			
			<?php
				$templateOtrosDatos = '<div style="display:inline-block; width:39%;">{label}</div><div style="display:inline-block; width:58%;">{input}</div>';
			?>
			<!-- quinta fila -->
			<div class="row">
				<!-- Otros datos -->
				<div class="col-xs-4">
					
						
					<div class="form"  style="padding:3px 15px;">
					
						<h5><label>Otros datos</label></h5>
										
						<div class="row">
							<div class="col-xs-12">
								<?php
									
										
									echo $form->field($model, 'diavenc', ['template' => $templateOtrosDatos])->textInput(['maxlength' => 2, 'style' => 'margin:0px; width:50%; text-align:right;', 'id' => 'planConfigFormDiaVenc']);
								?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-xs-12">
								<?php
									echo $form->field($model, 'sellado', ['template' => $templateOtrosDatos])->textInput(['placeholder' => '0.00', 'style' => 'margin:0px; width:50%; text-align:right;', 'maxlength' => 6, 'id' => 'planConfigFormSellado']);
								?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-xs-12">
								<?php
									echo $form->field($model, 'multa', ['template' => $templateOtrosDatos])->textInput(['placeholder' => '0.00', 'style' => 'margin:0px; width:50%; text-align:right;', 'maxlength' => 6, 'id' => 'planConfigFormMultaDecae']);
								?>
							</div>
						</div>
					</div>
					
				</div>
				<!-- fin otros datos -->
				
				
				
				<!-- anticipo -->
				<div class="col-xs-4">
					
						
					<div class="form"  style="padding:3px 15px; height:111px;">
					
						<h5><label>Anticipo</label></h5>
										
						<div class="row">
							<div class="col-xs-12">
								
								<?php
									
									$opciones = ['label' => 'Con anticipo', 
												'style' => 'display:inline-block; margin:0px;',
												'id' => 'planConfigFormCheckAnticipo', 
												'uncheck' => 0,
												'value' => 1,
												
												'onclick' => 'if($(this).is(":checked")) {' .
												
												'var inputAnticipo = $("#planConfigFormAnticipo");' .
												'var checkAnticipoCuota = $("#planConfigFormAnticipoCuota");' .
												'var labelAnticipoCuota = $("#planConfigFormLabelAnticipoCuota");' .
												
												'inputAnticipo.css("visibility", "visible");' .
																								
												'checkAnticipoCuota.css("visibility", "visible");' .
												'labelAnticipoCuota.css("visibility", "visible");' .
												'}' .
												'else{' .
												
												'var inputAnticipo = $("#planConfigFormAnticipo");' .
												'var checkAnticipoCuota = $("#planConfigFormAnticipoCuota");' .
												'var labelAnticipoCuota = $("#planConfigFormLabelAnticipoCuota");' .
												
												'inputAnticipo.css("visibility", "hidden");' .
																								
												'checkAnticipoCuota.css("visibility", "hidden");' .
												'labelAnticipoCuota.css("visibility", "hidden");' .
												'}'];
												
									
									echo $form->field($model, 'conanticipo', ['template' => '{input}', 'options' => ['style' => 'display:inline-block;']])->checkbox($opciones);
																		
									$opciones = ['class' => 'form-control', 'id' => 'planConfigFormAnticipo', 'style' => 'width:39%; visibility:hidden;', 'maxlength' => 3];									
								
									echo $form->field($model, 'anticipo', ['template' => '{input}', 'options' => ['style' => 'display:inline-block; width:58%; max-width:auto; margin:0px; text-align:right;']])->textInput($opciones);
								?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-xs-12">
								<?php
								
									$opciones = ['style' => 'visibility:hidden;', 'id' => 'planConfigFormLabelAnticipoCuota'];
										
											
										
									echo $form->field($model, 'anticipocuota')->checkbox(['value' => 1, 'uncheck' => 0, 'labelOptions' => $opciones, 'id' => 'planConfigFormAnticipoCuota', 'style' => 'visibility:hidden;']);
								?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-xs-12">
								<?php
									echo $form->field($model, 'anticipomanual')->checkbox(['id' => 'planConfigFormAnticipoManual', 'uncheck' => 0, 'value' => 1]);
								?>
							</div>
						</div>
					</div>
					
				</div>
				<!-- fin anticipo -->
				
				
				<!-- Tasa financiacion -->
				<div class="col-xs-4">
					
						
					<div class="form"  style="padding:3px 15px; height:111px;">
					
						<h5><label>Tasa financiaci&oacute;n</label></h5>
										
						<div class="row">
							<div class="col-xs-12">
								<?php
									$input = Html::textInput('', null, ['class' => 'form-control']);
								
									echo $form->field(
													$model, 
													'tactiva', 
													[ 'options' =>  ['id' => 'planConfigFormTactiva', 'template' => '{input}'] ] 
													)->radioList([
																	0 => 'Fijo', 
																	1 => 's/Tasa activa'
																], 
																[	
																	'separator' => '',
																	'unselect' => null, 
																	'item' => function($index, $label, $name, $checked, $value) use($model, $form){
																			
																						$onclickRadio = '$("#planConfigFormTactiva .input-group:not(.planConfigFormTactivaItem'. $index .')").css("visibility", "hidden");' .
																										'$("#planConfigFormTactiva .input-group.planConfigFormTactivaItem' . $index . '").css("visibility", "visible");';
																										
																																					
																						$radio = Html::radio($name, $checked, [
																																'label' => $label, 
																																'value' => $value, 
																																'onclick' => $onclickRadio, 
																																'uncheck' => null, 
																																'id' => 'planConfigFormTactivaRadio' . $index,
																																'style' => 'margin-top:0px !important;'
																																]);
																																					
																						$propiedad = $value == 0 ? 'interes' : 'tactivaporc';
																						
																						$inputVal = $value == 0 ? $model->interes : $model->tactivaporc;
																						
																						
																						$input = $form->field($model, $propiedad, ['template' => '{input}'])->textInput(['class' => 'form-control', 'id' => 'planConfigFormTactivaItem' . $index, 'style' => 'margin:0px; text-align:right;', 'maxlength' => 4]);
																						
																						$concatenar = '';
																						
																						if($model->tactiva == $value)
																							$concatenar = '<script type="text/javascript">setTimeout(function(){$("#planConfigFormTactivaRadio' . $index . '").click(); }, 1)</script>';
										
																						return '<div class="row">' .
																								'	<div class="col-xs-7">' .
																								 
																									$radio  . 
																								
																								'</div>' .
																								'<div class="col-xs-5" style="padding-left:0px;">' .
																								
																									'<div class="input-group planConfigFormTactivaItem' . $index . '">' . 
																										$input .
																										'<span class="input-group-addon">%</span>' .
																									'</div>' .
																								
																								'</div>' .
																								'</div>' . 
																								
																								$concatenar;
																						
																						}
																]);
								?>
							</div>
						</div>
					</div>
					
				</div>
				<!-- fin otros datos -->
			</div>
		<!-- fin quinta fila -->
		
		<!-- sexta fila -->
		<div class="row">			
			<div class="col-xs-6 col-xs-offset-6">
				<?php
					$mod = '';
				
					if($model->usrmod  != null && $model->fchmod != null)
						$mod = utb::getFormatoModif($model->usrmod, $model->fchmod);
						
					echo '<label class="pull-right">Modificación: ' . 
							Html::textInput(null, $mod, ['style' => 'background-color:#E6E6FA; width : auto;', 'class' => 'form-control', 'disabled' => 'disabled']) . 
							'</label>';
				?>
			</div>
		</div>
		<!-- fin sexta fila -->
		
		<!-- septima fila -->
		<div class="row">
			<div class="col-xs-12">
				<?php
					Tabs::begin([
						'items' => [
							['label' => 'Cuentas ingreso', 'active' => true, 'content' => $this->render('_cuentasingreso', ['model' => $model, 'extras' => $extras, 'form' => $form, 'consulta' => $consulta ] ), 'options' => ['style' => 'border : 1px solid #DDD; border-radius : 0px 0px 8px 8px; padding : 3px 15px; border-top : none;']],
							['label' => 'Tributos', 'active' => false, 'content' => $this->render('_tributos', ['model' => $model, 'extras' => $extras ] ) ],
							['label' => 'Usuarios', 'active' => false, 'content' => $this->render('_usuarios', ['model' => $model, 'extras' => $extras] ) ]
						],
						
						'itemOptions' => ['style' => 'border : 1px solid #DDD; border-radius : 0px 0px 8px 8px; padding : 15px; border-top : none;']
					]);
					
					Tabs::end();
				?>
			</div>
		</div>
		<!-- fin septima fila -->
	
	<div class="form-group" style="margin-top:10px;">
	    <?php
					
			if ($consulta == 0 || $consulta == 3)
			{
				echo Html::submitButton($model->isNewRecord ? 'Grabar' : 'Grabar', ['class' => 'btn btn-success', 'id' => 'itemFormBoton']);
				echo "&nbsp;&nbsp;";
				echo Html::a('Cancelar', BaseUrl::toRoute('index'), ['class' => 'btn btn-primary', 'id' => 'planConfigFormCancelar']);	        		
			} else if($consulta == 2) {	        		  
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'id' => 'itemFormBoton']);
				echo "&nbsp;&nbsp;";
				echo Html::a('Cancelar', BaseUrl::toRoute('index'), ['class' => 'btn btn-primary', 'id' => 'planConfigFormCancelar']);
			} else if($consulta == 1){
				echo Html::a('Modificar', ['update', 'id' => $model->cod], ['class' => 'btn btn-primary']);
			}
		?>
	</div>
	
		
    <?php 
    
    ActiveForm::end(); 
    
    echo $form->errorSummary($model, ['id' => 'errorSummary', 'style' => 'margin-top:10px;']);
    ?>
    
    
    
    <div class="row" style="margin-top:10px;">
    	<div class="col-xs-12">
    		<div  id="errorPlanConfig" style="display:none;" class="error-summary">
    			<p>Por favor corrija los siguientes errores:</p>
    			<ul>
    			</ul>
    		</div>
    	</div>
    </div>
    
<script type="text/javascript">
$(".form .checkSinLimite").each(function(e){
	
	$(this).click(function(e){
		
		$formTarget = $( $(this).data("target") );
		
		if( $(this).is(":checked") )
		{
			$(this).attr("checked", "checked")
			
			$input = $formTarget.find("input:not('.checkSinLimite')");
			
			$input.attr("disabled", "disabled");
			
		}
		else
		{
			$(this).removeAttr("checked");
			
			$input = $formTarget.find("input:not('.checkSinLimite')");
			
			$input.removeAttr("disabled");
		}
			
			
	});
});

if( $("#planConfigFormCheckPeriodosCantCuotas").is(":checked") || $("#planConfigFormCheckSinLimiteCantCuotas").is(":checked") )
{
	$("#planConfigFormMinCantCuo").attr("disabled", "disabled");
	$("#planConfigFormMaxCantCuo").attr("disabled", "disabled");
}



$(".form checkbox:not(.checkSinLimite)").each(function(){
	if($(this).is(":checked"))
		$this.click();
});

$(".form .checkSinLimite").each(function(){
	if( $(this).is(":checked") )
		$( $(this).data("target") ).find("input:not('.checkSinLimite')").attr("disabled", "disabled");
});
</script> 
    
<script type="text/javascript">
<?php

//se desactiva el formulario
if($consulta == 1 || $consulta == 2){
?>
DesactivarFormPost("planConfigForm");
$("#planConfigFormVigenciaDesde").attr("disabled", "disabled");
$("#planConfigFormVigenciaHasta").attr("disabled", "disabled");
<?php	
}
?>

$("#planConfigFormCodigo").removeAttr("disabled");

$("#planConfigFormSubmit").removeAttr("disabled");
$("#planConfigFormSubmit").removeAttr("readonly");

if($("#planConfigFormCheckAnticipo").is(":checked"))
{
	$("#planConfigFormAnticipo").css('visibility', 'visible');
	
	$("#planConfigFormAnticipoCuota").css('visibility', 'visible');
	$("#planConfigFormLabelAnticipoCuota").css('visibility', 'visible');
}




$("#planConfigForm").submit(function(e)
{
	error = '';
	
	//nombre	
	if($("#planConfigFormNombre").val() == '') error += '<li>Ingrese un nombre</li>';
	
	//sistema
	if($("#planConfigFormSistema").val() == '') error += '<li>Seleccione un sistema</li>';
	
		
	//texto
	if($("#planConfigFormTexto").val() == '') error += '<li>Seleccione un texto</li>';
	
	//vigenciadesde
	if($("#planConfigVigenciaDesde").val() == '') error += '<li>Elija una fecha de período desde</li>';
	
	//aldia
	if(!$("#planConfigFormAldia").is(":checked")){
		
		var aldiaDesdeA= parseInt($("#planConfigFormAldiadesdea").val());
		var aldiaDesdeC= parseInt($("#planConfigFormAldiadesdec").val());
		var aldiaHastaA= parseInt($("#planConfigFormAldiahastaa").val());
		var aldiaHastaC= parseInt($("#planConfigFormAldiahastac").val());
		
		if($("#planConfigFormAldiadesdea").val() == '') error += '<li>Período al día año desde no puede estar vacío</li>';
		else if(isNaN(aldiaDesdeA) || aldiaDesdeA <= 0) error += '<li>Período al dia año desde no debe ser menor a 0</li>';
		
		if($("#planConfigFormAldiadesdec").val() == '') error += '<li>Período al día período desde no puede estar vacío</li>';
		else if(isNaN(aldiaDesdeC) || aldiaDesdeC <= 0) error += '<li>Período al dia período desde no debe ser menor a 0</li>';
		
		if($("#planConfigFormAldiahastaa").val() == '') error += '<li>Período al día año hasta no puede estar vacío</li>';
		else if(isNaN(aldiaHastaA) || aldiaHastaA <= 0) error += '<li>Período al dia año hasta no debe ser menor a 0</li>';
		
		if($("#planConfigFormAldiahastac").val() == '') error += '<li>Período al día período hasta no puede estar vacío</li>';
		else if(isNaN(aldiaHastaC) || aldiaHastaC <= 0) error += '<li>Período al dia período hasta no debe ser menor a 0</li>';
		
		
	}
	
	//aplica
	if(!$("#planConfigFormAplica").is(":checked")){
		
		var aplicaDesdeA= parseInt($("#planConfigFormAplicadesdea").val());
		var aplicaDesdeC= parseInt($("#planConfigFormAplicadesdec").val());
		var aplicaHastaA= parseInt($("#planConfigFormAplicahastaa").val());
		var aplicaHastaC= parseInt($("#planConfigFormAplicahastac").val());
		
		if($("#planConfigFormAplicadesdea").val() == '') error += '<li>Período aplica año desde no puede estar vacío</li>';
		else if(isNaN(aplicaDesdeA) || aplicaDesdeA <= 0) error += '<li>Período aplica año desde no debe ser menor a 0</li>';
		
		if($("#planConfigFormAplicadesdec").val() == '') error += '<li>Período aplica período desde no puede estar vacío</li>';
		else if(isNaN(aplicaDesdeA) || aplicaDesdeA <= 0) error += '<li>Período aplica año desde no debe ser menor a 0</li>';
		
		if($("#planConfigFormAplicahastaa").val() == '') error += '<li>Período aplica año hasta no puede estar vacío</li>';
		else if(isNaN(aplicaDesdeA) || aplicaDesdeA <= 0) error += '<li>Período aplica año desde no debe ser menor a 0</li>';
		
		if($("#planConfigFormAplicahastac").val() == '') error += '<li>Período aplica período hasta no puede estar vacío</li>';
		else if(isNaN(aplicaDesdeA) || aplicaDesdeA <= 0) error += '<li>Período aplica año desde no debe ser menor a 0</li>';
	}
	
	//deuda nominal
	if(!$("#planConfigFormDeuda").is(":checked")){
		
		var deudaMin= parseFloat($("#planConfigFormMinDeuda").val());
		var deudaMax= parseFloat($("#planConfigFormMinDeuda").val());
		
		if($("#planConfigFormMinDeuda").val() == '') error += '<li>Deuda nominal mínimo no puede estar vacío</li>';
		else if(isNaN(deudaMin) || deudaMin <= 0) error += '<li>Deuda nominal mínimo no debe ser menor a 0</li>';
		
		if($("#planConfigFormMaxDeuda").val() == '') error += '<li>Deuda nominal máximo no puede estar vacío</li>';
		else if(isNaN(deudaMax) || deudaMax <= 0) error += '<li>Deuda nominal máximo no debe ser menor a 0</li>';
	}
	
	//cantcuotas
	if(!$("#planConfigFormCheckPeriodosCantCuotas").is(":checked") && !$("#planConfigFormCheckSinLimiteCantCuotas").is(":checked")){
		
		var cantidadCuotasMin= parseFloat($("#planConfigFormMinCantCuo").val());
		var cantidadCuotasMax= parseFloat($("#planConfigFormMinCantCuo").val());
		
		if($("#planConfigFormMinCantCuo").val() == '') error += '<li>Cantidad de cuotas mínimo no puede estar vacío</li>';
		else if(isNaN(cantidadCuotasMin) || cantidadCuotasMin <= 0) error += '<li>Cantidad de cuotas mínimo no debe ser menor a 0</li>';
		
		if($("#planConfigFormMaxCantCuo").val() == '') error += '<li>Cantidad de cuotas máximo no puede estar vacío</li>';
		else if(isNaN(cantidadCuotasMax) || cantidadCuotasMax <= 0) error += '<li>Cantidad de cuotas máximo no debe ser menor a 0</li>';
	}
	
	//importecuo
	if(!$("#planConfigFormImporteCuo").is(":checked")){
		
		var importeCuotasMin= parseFloat($("#planConfigFormMinMontoCuo").val());
		var importeCuotasMax= parseFloat($("#planConfigFormMinMontoCuo").val());
		
		if($("#planConfigFormMinMontoCuo").val() == '') error += '<li>Monto cuota mínimo no puede estar vacío</li>';
		else if(isNaN(importeCuotasMin) || importeCuotasMin <= 0) error += '<li>Monto cuota mínimo no debe ser menor a 0</li>';
		
		if($("#planConfigFormMaxMontoCuo").val() == '') error += '<li>Monto cuota máximo no puede estar vacío</li>';
		else if(isNaN(importeCuotasMax) || importeCuotasMax <= 0) error += '<li>Monto cuota máximo no debe ser menor a 0</li>';
	}
	
	//cuenta principal
	if($("#cuentaPrincipal").val() == '') error += '<li>La cuenta a utilizar en el plan es obligatoria</li>';
	
	//cuenta recargo
	if($("#cuentarecargo").val() == '') error += '<li>La cuenta recargo a utilizar en el plan es obligatoria</li>';
	
	//cuenta sellado
	var sellado = $("#planConfigFormSellado").val();
	var ctaSellado = $("#cuentasellado").val();
	var nombreSellado = $("#nombreCuentasellado").val();
	
	if((isNaN(parseFloat(sellado)) || parseFloat(sellado) < 0) && (sellado.trim() != ''))
		error += '<li>El valor de sellado debe ser un número mayor a 0</li>';
	else if(parseFloat(sellado) > 0)
		{
			if(isNaN(parseFloat(ctaSellado)) || parseFloat(ctaSellado) <= 0)
				error += '<li>La cuenta de sellado no existe</li>';
			else if(parseFloat(ctaSellado) > 0 && nombreSellado.trim() == '')
				error += '<li>La cuenta de sellado  existe</li>';
		}
	
	
	
	//cuenta multa
	var multa = $("#planConfigFormMultaDecae").val();
	var ctaMulta = $("#cuentamulta").val();
	var nombreMulta = $("#nombreCuentamulta").val();
	
	if((isNaN(parseFloat(multa)) || parseFloat(multa) < 0) && (multa.trim() != 0))
		error += '<li>El valor de multa decae debe ser un número mayor a 0</li>';
	else if(parseFloat(multa) > 0)
		{
			if(isNaN(parseFloat(ctaMulta)) || parseFloat(ctaMulta) <= 0)
				error += '<li>La cuenta de multa no existe</li>';
			else if(parseFloat(ctaMulta) > 0 && nombreMulta.trim() == '')
				error += '<li>La cuenta de multa  existe</li>';
		}
	
	
//	if(!isNaN(parseFloat(multa)) && parseFloat(multa) > 0 && !isNaN(parseInt(ctaMulta)) && parseInt(ctaMulta) <= 0) error += '<li>Si tiene multa por decaimiento, La cuenta de multa es obligatoria</li>';
//	else if(nombreMulta.trim() == '' && parseFloat(ctaMulta) > 0) error += '<li>La cuenta de multa no existe</li>';
		
	//tributos
	if($("#listaTributosAsignados tbody tr").size() == 0) error += '<li>No ha ingresado ningún tributo</li>';
	
	//anticipo
	if($("#planConfigFormCheckAnticipo").is(":checked"))
	{
		var anticipo = $("#planConfigFormAnticipo").val();
		
		if(isNaN(parseFloat(anticipo)) || parseFloat(anticipo) <= 0)
			error += '<li>Si es con anticipo, debe indicar el porcentaje del mismo</li>';
	}
		
	if (error == '')
	{
		return true;
	}else {
		
		$("#errorSummary").addClass("hidden");
		
		$("#errorPlanConfig ul").html(error);
		$("#errorPlanConfig").css("display", "block");
		
		return false;
	}
}
);
</script>
        	
</div><!-- fin de .planConfigForm -->