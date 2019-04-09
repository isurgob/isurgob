<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\ctacte\ItemVigencia;
use app\controllers\ctacte\ItemvigenciaController;
use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Item */
/* @var $form yii\widgets\ActiveForm */

$template = '{label}{input}';
$templateParametro = '{label}{input}';

$formulas = utb::getAux('item_tfcalculo');
$comparacion = ['=' => '=', '<' => '<', '>' => '>', '<=' => '<=', '>=' => '>=', '<>' => '<>'];
?>

<style type="text/css">
.item-form div.row{
	height : auto;
	min-height : 17px;
}

.form-control{
	width : 100%;
}
</style>

<?php
$form = ActiveForm::begin(['id' => 'form-item', 'errorCssClass' => '', 'fieldConfig' => ['template' => $template]]);
?>

<div class="item-form">
	<table border="0" width="100%">
		<tr>
			<td><label>C&oacute;digo</label></td>
			<td width="5px"></td>
			<td><?= $form->field($model, 'item_id')->textInput(['class'  => 'form-control', 'style' => 'width:50px;'])->label(false); ?></td>
			<td width="80px"></td>
		</tr>
		<tr>
			<td><label>Nombre</label></td>
			<td></td>
			<td><?= $form->field($model, 'nombre')->textInput(['class' => 'form-control', 'style' => 'width:300px;'])->label(false); ?></td>
		</tr>
		<tr>
			<td><label>Tributo</label></td>
			<td></td>
			<td><?= Html::textInput(null, utb::getCampo("trib","trib_id=".($model->trib_id == '' ? 0 : $model->trib_id),"nombre"), ['class' => 'form-control', 'style' => 'width:300px;']); ?></td>
		</tr>
		<tr>
			<td><label>Cuenta</label></td>
			<td></td>
			<td><?= Html::textInput(null, $model->cta_id != null ? $model->getNombreCuenta($model->cta_id) : null, ['class' => 'form-control', 'style' => 'width:300px;']); ?></td>
		</tr>
		
		<tr>
			<td colspan="4">
				<?php
				echo $form->field($model, 'obs')->textArea(['class' => 'form-control', 'rows' => 5, 'style' => 'max-height:100%; height:100px; max-width:100%; width:99%;']);
				?>	
			</td>
		</tr>
	</table>

	
   
    <?php 
		
    	
    	
    	if ($consulta==1 or $consulta==2) 
    	{
    		echo "<script>";
			echo "DesactivarForm('form-item');";
			echo "</script>";
    	}
    	
    	$model = new ItemVigencia();

		if(isset($item_id))
		{
			$model->item_id = $item_id;
			$model = $model->buscarActual();
		}
		
		
    	//echo $this->render('//ctacte/itemvigencia/_form', ['consulta' => 1, 'model' => $model, 'extras' => ItemvigenciaController::getDefaultExtras()]);       	
    ?>
    
    <div class="row">
    	<div class="col-xs-12">
    		<h3 style="margin-top:0px;"><label><u>Vigencia actual</u></label></h3>
    	</div>
    	
    	<div class="col-xs-12">
			<label for="itemVigenciaFormula">F&oacute;rmula de C&aacute;lculo</label>
			<?php
						
			$seleccion = $model->tcalculo != null ? $model->tcalculo : 0;
			$f = null;
			
			if(array_key_exists($model->tcalculo, $formulas)) $f = $formulas[$model->tcalculo];
			
			echo Html::textInput(null, $f, ['class' => 'form-control', 'disabled' => true, 'style' => 'width:100%;']);
			?>
		</div>
    	
		<div class="col-xs-6">
			
			<div class="row">
				<div class="col-xs-3"><label>Desde</label></div>
				<div class="col-xs-4" style="padding-right:1px;">
				
				
				<?php
				echo $form->field($model, 'adesde', ['template' => '{input}', 'options' => ['style' => 'display:inline-block;']])->textInput(['id' => 'itemVigenciadesde', 'maxlength' => 4, 'disabled' => true])->label(false); 
				?>
				</div>
				
				<div class="col-xs-3" style="padding-left:1px;">
				
				<?php
				echo $form->field($model, 'cdesde', ['template' => '{input}'])->textInput(['id' => 'itemVigenciaCuotadesde', 'maxlength' => 3, 'disabled' => true])->label('');			
				?>
				</div>
			</div>
		</div>
		
		<div class="col-xs-6">
		
			<div class="row">
				<div class="col-xs-3"><label>Hasta</label></div>
				<div class="col-xs-4" style="padding-right:1px;">
					<?php
						echo $form->field($model, 'ahasta', ['template' => '{input}', 'options' => ['style' => 'display:inline-block;']])->textInput(['id' => 'itemVigenciahasta', 'maxlength' => 4, 'disabled' => true])->label(false); 
					?>
				</div>
			
				<div class="col-xs-3" style="padding-left:1px;">
					<?php
						echo $form->field($model, 'chasta', ['template' => '{input}'])->textInput(['id' => 'itemVigenciaCuotahasta', 'maxlength' => 3, 'disabled' => true])->label(''); 
					?>
				</div>
			</div>	
		</div>
	</div>
	
	<div class="row" style="margin-top:10px;">

		<div class="col-xs-5">
			
					<div class="row itemVigenciaMinimo">
						<div class="col-xs-4"><label>M&iacute;nimo</label></div>
						<div class="col-xs-8">
							
							<?php 
								echo $form->field($model, 'minimo', ['template' => $templateParametro])->textInput([
																													'id' => 'itemVigenciaMinimo',
																													'class' => 'form-control', 
																													'maxlength' => 7,
																													'disabled' => true
																													])->label(false);
							?>	
						</div>
					</div>	
					
			
			
			
				<?php
				if($model->tcalculo != null && array_key_exists($model->tcalculo, $formulas) && stripos( $formulas[$model->tcalculo] , 'monto') !== false)
				{
				?>
				
			
					<div class="row itemVigenciaMonto">
						<div class="col-xs-4"><label>Monto</label></div>
						<div class="col-xs-8">
							
							<?php
							echo $form->field($model, 'monto', ['template' => $templateParametro])->textInput(['id' => 'itemVigenciaMonto', 'class' => 'form-control', 'maxlength' => 7, 'disabled' => true])->label(false);
							?>
							
						</div>
					</div>
			
				<?php
				}
				?>
			
			
			
			
				<?php
				if($model->tcalculo != null && array_key_exists($model->tcalculo, $formulas) && strpos(strtolower( $formulas[$model->tcalculo] ), 'porc') !== false)
				{
				?>
			
					<div class="row itemVigenciaPorcentaje">
						<div class="col-xs-4"><label>Porc.</label></div>
						<div class="col-xs-8">
							<?php
							echo $form->field($model, 'porc', ['template' => $templateParametro])->textInput(['id' => 'itemVigenciaPorcentaje', 'class' => 'form-control', 'maxlength' => 7, 'disabled' => true])->label(false);
								
							?>
						</div>
					</div>
			
				<?php
				}
				?>
			
			
		</div>
		
		
		
		<!-- parametros -->
		<div class="col-xs-7">
			
			
				<?php
				if($model->tcalculo != null && array_key_exists($model->tcalculo, $formulas) && (stripos( $formulas[$model->tcalculo] , 'param1') !== false || stripos( $formulas[$model->tcalculo] , 'tabla') !== false))
				{		
				?>
				<!-- Parametro 1 -->
				<div class="row">	
					<div class="col-xs-12">
					
						<div class="row itemVigenciaParam1">
							<div class="col-xs-5" style="padding-right:0px;"><label>Parametro 1</label></div>
							<div class="col-xs-5" style="padding-left:0px;">
							
								<?php
								echo $form->field($model, 'paramnombre1', ['template' => $templateParametro])->textInput(['id' => 'itemVigenciaParam1', 'class' => 'form-control', 'maxlength' => 15, 'disabled' => true])->label(false);
								?>
							</div>
							
							<div class="col-xs-2 <?= stripos( $formulas[$model->tcalculo], 'tabla' ) !== false ? '' : 'hidden' ?>" style="padding-left:0px;">
								<br>
								<?php
								echo $form->field($model, 'paramcomp1', ['template' => '{input}'])->dropDownList($comparacion, ['prompt' => '', 'id' => 'itemVigenciaComp1', 'disabled' => true]);
								?>
							</div>
						</div>
					
					</div>
				
				</div>
				<?php
				}
				?>
				
			
		
			
				<?php
				if($model->tcalculo != null && array_key_exists($model->tcalculo, $formulas) && (stripos($formulas[$model->tcalculo] , 'param2') !== false || stripos( $formulas[$model->tcalculo] , 'tabla') !== false))
				{		
				?>
				<!-- Parametro 2 -->
				<div class="row">	
					<div class="col-xs-12">
					
						<div class="row itemVigenciaParam2">
							<div class="col-xs-5" style="padding-right:0px;"><label>Parametro 2</label></div>
							<div class="col-xs-5" style="padding-left:0px;">
							
								<?php
								echo $form->field($model, 'paramnombre2', ['template' => $templateParametro])->textInput(['id' => 'itemVigenciaParam2', 'class' => 'form-control', 'maxlength' => 15, 'disabled' => true]);
								?>
							</div>
							
							<div class="col-xs-2 <?= stripos( $formulas[$model->tcalculo], 'tabla' ) !== false ? '' : 'hidden' ?>" style="padding-left:0px;">
								<br>
								<?php
								echo $form->field($model, 'paramcomp2', ['template' => '{input}'])->dropDownList($comparacion, ['prompt' => '', 'id' => 'itemVigenciaComp2', 'disabled' => true]);
								?>
							</div>
						</div>
					
					</div>
				</div>
				<?php
				}
				?>
			
			
			
			
				<?php
				if($model->tcalculo != null && array_key_exists($model->tcalculo, $formulas) && (stripos( $formulas[$model->tcalculo] , 'param3') !== false || stripos( $formulas[$model->tcalculo] , 'tabla') !== false))
				{		
				?>
				<!-- Parametro 3 -->
				<div class="row">
					<div class="col-xs-12">
						<div class="row itemVigenciaParam3">
							<div class="col-xs-5" style="padding-right:0px;"><label>Parametro 3</label></div>
							<div class="col-xs-5" style="padding-left:0px;">
							
								<?php
								echo $form->field($model, 'paramnombre3', ['template' => $templateParametro])->textInput(['id' => 'itemVigenciaParam3', 'class' => 'form-control', 'maxlength' => 15, 'disabled' => true]);
								?>
							</div>
							
							<div class="col-xs-2 <?= stripos( $formulas[$model->tcalculo], 'tabla' ) !== false ? '' : 'hidden' ?>" style="padding-left:0px;">
								<br>
								<?php
								echo $form->field($model, 'paramcomp3', ['template' => '{input}'])->dropDownList($comparacion, ['prompt' => '', 'id' => 'itemVigenciaComp3', 'disabled' => true]);
								?>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			
			
			
			
			
				<?php
				if($model->tcalculo != null && array_key_exists($model->tcalculo, $formulas) && (stripos( $formulas[$model->tcalculo] , 'param4') !== false || stripos( $formulas[$model->tcalculo] , 'tabla') !== false))
				{		
				?>
				<!-- Parametro 4 -->
				<div class="row">
					<div class="col-xs-12">
						<div class="row itemVigenciaParam4">
							<div class="col-xs-5" style="padding-right:0px;"><label>Parametro 4</label></div>
							<div class="col-xs-5" style="padding-left:0px;">
							
								<?php
								echo $form->field($model, 'paramnombre4', ['template' => $templateParametro])->textInput(['id' => 'itemVigenciaParam4', 'class' => 'form-control', 'maxlength' => 15, 'disabled' => true]);
								?>
							</div>
							
							<div class="col-xs-2 <?= stripos( $formulas[$model->tcalculo], 'tabla' ) !== false ? '' : 'hidden' ?>" style="padding-left:0px;">
								<br>
								<?php
								echo $form->field($model, 'paramcomp4', ['template' => '{input}'])->dropDownList($comparacion, ['prompt' => '', 'id' => 'itemVigenciaComp4', 'disabled' => true]);
								?>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			
			
			
		</div>
		<!-- fin parametros -->
	
	</div>
    
    <div class="row">
    	<div class="col-xs-12">
			<label>Modificaci&oacute;n: <?php 
			
			$modificacion = null;
			
			if($model->usrmod != null && $model->fchmod != null) $modificacion = utb::getFormatoModif($model->usrmod, $model->fchmod);
			
			echo Html::textInput(null, $modificacion, ['style' => 'background-color:#E6E6FA; width : auto;', 'class' => 'form-control']); ?></label>
		</div>
    </div>
</div>

<?php
ActiveForm::end();
?>
