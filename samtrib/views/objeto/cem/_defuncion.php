<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

use app\utils\db\utb;
use app\utils\db\Fecha;
?>

<table border="0" width="600px">
	<tr>
		<td><label>Acta: </label></td>
		<td>
			<?= $form->field($model, 'actadef')->textInput(['style' => 'width:80px;', 'maxlength' => 15])->label(false) ?>
		</td>
		
		<td width="5px"></td>
		
		<td><label>Folio: </label></td>
		<td>
			<?= $form->field($model, 'foliodef')->textInput(['style' => 'width:80px;', 'maxlength' => 10])->label(false) ?>
		</td>
		
		<td width="5px"></td>
		
		<td><label>Fecha: </label></td>
		<td valign="top">
			<?php
			if($consulta != 1 && $consulta != 2)
				echo DatePicker::widget(['model' => $model, 'attribute' => 'fchdef', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:80px;']]);
			else{				
				if($model->fchdef != null && trim($model->fchdef) != '') 
					$model->fchdef = Fecha::bdToUsuario($model->fchdef);
				
				 echo $form->field($model, 'fchdef')->textInput(['style' => 'width:80px', 'readonly' => true])->label(false);
			}
			?>
			
		</td>
		
		<td width="5px"></td>
		
		<td><label>Inhumaci&oacute;n: </label></td>
		<td valign="top">
			<?php
			if($consulta != 1 && $consulta != 2)
				echo DatePicker::widget(['model' => $model, 'attribute' => 'fchinh', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:80px;']]);
			else{
				if($model->fchinh != null && trim($model->fchinh) != '') 
					$model->fchinh = Fecha::bdToUsuario($model->fchinh);
					
				 echo $form->field($model, 'fchinh')->textInput(['style' => 'width:80px;', 'readonly' => true])->label(false);
			}
			?>
			
		</td>
	</tr>
	
	<tr>
		<td><label>M&eacute;dico: </label></td>
		<td colspan="7">
			<?= $form->field($model, 'med_nombre')->textInput(['style' => 'width:100%;', 'maxlength' => 35])->label(false) ?>
		</td>
		
		<td></td>
		
		<td><label>Matr&iacute;cula: </label></td>
		<td>
			<?= $form->field($model, 'med_matricula')->textInput(['style' => 'width:80px;', 'maxlength' => 10])->label(false) ?>
		</td>
	</tr>
	
	<tr>
		<td><label>Causa muerte: </label></td>
		<td colspan="4">
			<?= $form->field($model, 'causamuerte')->dropDownList(utb::getAux('cem_tcausa'), ['style' => 'width:100%', 'prompt' => ''])->label(false) ?>
		</td>
	</tr>
	
	<tr>		
		<td><label>Empresa F&uacute;nebre: </label></td>
		<td colspan="4">
			<?= $form->field($model, 'emp_funebre')->dropDownList(utb::getAux('cem_tfunebre'), ['style' => 'width:100%', 'prompt' => ''])->label(false) ?>
		</td>
	</tr>
</table>