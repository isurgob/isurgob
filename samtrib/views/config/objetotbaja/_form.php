<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\config\ObjetoTbaja */
/* @var $form yii\widgets\ActiveForm */
?>

	<div class="objeto-tbaja-form">
																					 
    		<?php $form = ActiveForm::begin(['id' => 'form-objetoTbaja','action' => ['objetotbajaabm'], 'fieldConfig' => ['template' => "{label}{input}"]]);
    		
    			  echo Html::input('hidden', 'accion', $consulta, ['id'=>'accion']);
    		 ?>

	<table border='0'>
		<tr>
			<td width='110px'> <?=$form->field($model, 'cod')->textInput(['style' => 'width:50px;', 'readOnly' => 'true']) ?> </td>
			<td> <?= $form->field($model, 'tobj')->textInput(['style' => 'width:50px;']) ?> </td>
		</tr>
		<tr>
			<td width='730px' colspan='2'>    
				<?= $form->field($model, 'nombre')->textInput(['maxlength' => 70,'style' => 'width:680px;']) ?>
			</td>

		</tr>
		<tr>
			<td width='180px' colspan='2'>
				<div id='modif' style='float:right;'><?= $form->field($model, 'modif')->textInput(['style' => 'width:150px;']) ?></div>
			</td>
			
	</table>    
	
	<div class="form-group">
	 
	 <?php
	 
	    if($consulta==0){
	    	
        	echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'location.reload();']);
    		echo '<script>$("#modif").css("display","none");</script>';
    		
        	
        }
        
        if($consulta==3){
        	
        	echo '<script>$("#objetotbaja-cod").prop("readOnly",true);</script>';
        	echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'location.reload();']);
			echo '<script>$("#objetotbaja-fchmod").prop("disabled",true);</script>';
    		echo '<script>$("#objetotbaja-usrmod").prop("disabled",true);</script>';
    		echo '<script>$("#objetotbaja-modif").prop("disabled",true);</script>'; 
        }
     ?>
	</div>  
	 
	<?php
	if($consulta==1){
    	
     	echo '<script>$("#objetotbaja-nombre").prop("disabled",true);</script>';
    	echo '<script>$("#objetotbaja-tobj").prop("disabled",true);</script>';
    	echo '<script>$("#objetotbaja-fchmod").prop("disabled",true);</script>';
    	echo '<script>$("#objetotbaja-usrmod").prop("disabled",true);</script>';
    	echo '<script>$("#objetotbaja-modif").prop("disabled",true);</script>';  
    	  	               
    }	 
	?> 

   		<?php ActiveForm::end(); ?>

	</div>
