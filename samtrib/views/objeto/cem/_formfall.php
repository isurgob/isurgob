<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use yii\widgets\MaskedInput;
use \yii\widgets\Pjax;
use yii\web\Session;
use \yii\bootstrap\Modal;

use app\models\objeto\Domi;
use app\utils\db\Fecha;
?>

<div class="cem-fall-form">
	
	<?php
	
	
	$form = ActiveForm::begin(['id' => 'formcemfall', 'fieldConfig' => ['template' => '{label}{input}'], 'validateOnSubmit' => false]);
	?>
	
	<div class="form" style="padding-right:5px;padding-bottom:0px; margin-bottom:5px; width:640px;">
		
		<h3><label>Datos de la Cuenta</label></h3>
	
		<table border="0" width="640px">
			<tr>
				<td>
					<label>Objeto</label>
					<?= $form->field($model, 'obj_id')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:100px;'])->label('Objeto')->label(false) ?>
				</td>
				
				<td width="5px"></td>
				
				<td>
					<label>Tipo</label>
					<?= $form->field($modelCem, 'tipo')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:100px;'])->label(false) ?>
				</td>
				
				<td width="5px"></td>
				
				<td>
					<label>Cuadro</label>
					<?= $form->field($modelCem, 'cua_id')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1,'style' => 'width:50px;'])->label(false) ?>
				</td>
				
				<td width="5px"></td>
				
				<td>
					<label>Cuerpo</label>
					<?= $form->field($modelCem, 'cue_id')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:50px;'])->label(false) ?>
				</td>
				
				<td width="5px"></td>
				
				<td>
					<label>Piso</label>
					<?= $form->field($modelCem, 'piso')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1,'style' => 'width:50px;'])->label(false) ?>
				</td>
				
				<td width="5px"></td>
				
				<td>
					<label>Fila</label>
					<?= $form->field($modelCem, 'fila')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:50px;'])->label(false) ?>
				</td>
				
				<td width="5px"></td>
				
				<td>
					<label>Nume</label>
					<?= $form->field($modelCem, 'nume')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:50px;'])->label(false) ?>
				</td>
				
				<td width="5px"></td>
				
				<td>
					<label>Bis</label>
					<?= $form->field($modelCem, 'bis')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:50px;'])->label(false) ?>
				</td>
				<td width="50px"></td>
			</tr>
		</table>
	</div>
	
	<div class="form" style="padding:5px; width:640px;">
	
		<h3><label>Datos del Fallecido</label></h3>
		
		<table border="0" width="640px" class="asd">
			<tr>
				<td width="80px" align="left">
					<label>C&oacute;digo:</label>
					<?= $form->field($model, 'fall_id', ['options' => ['style' => 'width:80px; margin-right:0px;']])->textInput(['readonly' => true, 'style' => 'width:60px; background:#E6E6FA;'])->label(false); ?>
				</td>
				
				<td width="5px" align="left"></td>
				
				<td colspan="6" align="left">
					<label>Nombre:</label>
					<?= $form->field($model, 'apenom')->textInput(['style' => 'width:400px;', 'maxlength' => 50])->label(false); ?>
				</td>
				
				<td width="5px"></td>
				
				<td align="left" valign="top" colspan="3">
					<label>Estado:</label>
					<br>
					<?= Html::textInput(null, utb::getCampo('cem_fall_test', "cod = '$model->est'"), ['readonly' => true, 'class' => ($model->est == 'BA' ? 'form-control baja' : 'form-control solo-lectura'), 'style' => 'width:100px;text-align_center']); ?>
				</td>
			</tr>
			
			<tr>
				<td>
					<label>Tipo Doc.:</label>
				</td>
				
				<td></td>
				
				<td>
					<?= $form->field($model, 'tdoc')->dropDownList(utb::getAux('persona_tdoc'), ['prompt' => '', 'style' => 'width:120px;'])->label(false); ?>
				</td>
				
				<td width="10px"></td>
				
				<td>
					<label>Nº Doc.:</label>
				</td>
				<td>
					<?= $form->field($model, 'ndoc')->textInput(['style' => 'width:100px;', 'maxlength' => 9])->label(false); ?>
				</td>
				
				<td></td>
				
				
				<td width="40px">
					<label>Sexo:</label>
				</td>
				<td></td>
				<td>			
					<?= $form->field($model, 'sexo')->radioList(['M' => 'Masculino', 'F' => 'Femenino'])->label(false); ?>
				</td>
			</tr>
			
			<tr>
				<td>
					<label>Nacionalidad:</label>
				</td>
				
				<td></td>
				
				<td>
					<?= $form->field($model, 'nacionalidad')->dropDownList(utb::getAux('persona_tnac'), ['prompt' => '', 'style' => 'width:120px;'])->label(false); ?>
				</td>
				
				<td></td>
				
				<td>
					<label>Estado Civil:</label>
				</td>
				
				<td>
					<?= $form->field($model, 'estcivil')->dropDownList(utb::getAux('persona_testcivil'), ['prompt' => ''])->label(false); ?>
				</td>
			</tr>
			
			<tr>
				<td>
					<label>Domicilio:</label>
				</td>
				
				<td></td>
				
				<td colspan="4">
					<?= $form->field($model, 'domi')->textInput(['style' => 'width:315px;', 'maxlength' => 50])->label(false) ?>
				</td>
			</tr>
			
			<tr>
				<td>
					<label>Nacimiento:</label>
				</td>
				
				<td></td>
				
				<td valign="top">
					<?php
					
					if($consulta != 1 && $consulta != 2)
						echo DatePicker::widget(['model' => $model, 'attribute' => 'fchnac', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:80px;']]);
					else{
						if($model->fchnac != null && trim($model->fchnac) != '') 
							$model->fchnac = Fecha::bdToUsuario($model->fchnac);
						
						 echo $form->field($model, 'fchnac')->textInput(['style' => 'width:80px;', 'readonly' => true])->label(false);
					}
					
					?>
				</td>
				
				<td></td>
				
				<td>
					<label>Procedencia:</label>
				</td>
				<td colspan="4">
					<?= $form->field($model, 'procedencia')->dropDownList(utb::getAux('domi_localidad', 'loc_id'), ['prompt' => '', 'style' => 'width:195px;'])->label(false); ?>
				</td>
			</tr>
			
			<tr>
				<td colspan="2"></td>
				<td>
					<?= $form->field($model, 'indigente')->checkbox(['uncheck' => 0, 'value' => 1]); ?>
				</td>
				
				<td></td>
				
				<td>
					<label>Modificaci&oacute;n:</label>
				</td>
				
				<td colspan="4">
					
					<?php
						$modif = null;
						
						if($model->fchmod != null && $model->usrmod != null)
							$modif = utb::getFormatoModif($model->usrmod, $model->fchmod);
							
						echo Html::textInput(null, $modif, ['class' => 'form-control', 'readonly' => true, 'style' => 'background:#E6E6FA; width:195px;']);
					 ?>
				</td>
			</tr>
		</table>
	</div>
	
	<div style="width:640px;margin-top: 8px">
		<table border="0" width="640px">
			<tr>
				<td width="620px">
					<?= Tabs::widget([
						
						'items' => [
							['label' => 'Defunción', 'active' => true, 'content' => $this->render('_defuncion', ['model' => $model, 'form' => $form, 'consulta' => $consulta]), 'options' => ['class' => 'tabItem']],
							['label' => 'Servicios', 'active' => false, 'content' => $this->render('_serviciosfall', ['model' => $model, 'consulta' => $consulta, 'extras' => $extras]), 'options' => ['class' => 'tabItem']],
							['label' => 'Observaciones', 'active' => false, 'content' => $form->field($model, 'obs')->textArea(['style' => 'width:600px; max-width:600px; height:100px; max-height:150px;'])->label(false), 'options' => ['class' => 'tabItem']],
						]
						]); ?>
				</td>
			</tr>
		</table>
	</div>
	
<?php
if ($consulta !== 1){ 
 ?>  
	<div class="form-group" style='margin-top:10px'>
		<?php 
				
			Pjax::begin(['id' => 'btOpciones']);
			
			if ($consulta==2){   
				
				echo Html::Button('Eliminar', ['class' => 'btn btn-danger', 'id' => 'btEliminarAcep', 
							'data' => [
										'toggle' => 'modal',
        								'target' => '#ModalEmiminar',
   									],]);
				
				
				Modal::begin([
        				'id' => 'ModalEmiminar',
        				'size' => 'modal-sm',
        				'header' => '<h4><b>Confirmar Eliminación</b></h4>',
        				'closeButton' => [
            				'label' => '<b>X</b>',
                			'class' => 'btn btn-danger btn-sm pull-right',
                			'id' => 'btCancelarModalElim'
            				],
        			]);
        										
        			echo "<center>";
        			echo "<p><label>¿Esta seguro que desea eliminar ?</label></p><br>";
        
					echo Html::a('Aceptar', ['deletefall', 
										'id' => $model->fall_id,
										'accion'=>1, 
										], 
										[
            							'class' => 'btn btn-success',
            							'data' => [
                							'method' => 'post',
            							],
        				]);
        		
        			echo "&nbsp;&nbsp;";
			 		echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEliminarCanc','onClick' => '$("#ModalEmiminar, .window").modal("toggle");']);
			 		echo "</center>";
			 		
			 	Modal::end();
      		
     		 }else {   
				echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
			 }
			 
			 echo "&nbsp;&nbsp;";
			 echo Html::a('Cancelar', ['viewfall', 'id' => $model->fall_id], [
            			'class' => 'btn btn-primary',
        			]);
        	Pjax::end();
		?> 
	</div>
	
<?php
}
?>
	
	<?php
	
	echo $form->errorSummary($model);
	
	ActiveForm::end();
	?>
	
	<?php 
	
	if(isset($error) && is_array($error) && count($error) > 0)
	{  
		
		echo '<div class="error-summary" style="width:640px;">Por favor corrija los siguientes errores:<br/><ul>';
		//var_dump($error);
		foreach($error as $e)
			echo "<li>$e</li>";
		//var_dump($error);
		echo '</ul></div>';
		
		
	}  

	if ($consulta==1 || $consulta==2) 
    	{
    		echo "<script>";
			echo "DesactivarFormPost('formcemfall');";
			echo "</script>";
    	} 
    ?>
</div>