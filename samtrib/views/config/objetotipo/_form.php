<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;
use app\models\config\ObjetoTipo;

/* @var $this yii\web\View */
/* @var $model app\models\config\ObjetoTipo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="objeto-tipo-form">
																																							
    <?php $form = ActiveForm::begin(['action' => ['tipodeobjetoabm'], 'id'=>'form-objetotipo','class' => 'form form-inline','fieldConfig' => ['template' => "{label}{input}"]]); 
    
    echo Html::input('hidden', 'accion', $consulta, ['id'=>'accion']);
    ?>

	<table border='0'>
		<tr>
			<td align='right'> 
				<?= $form->field($model, 'cod')->textInput(['maxlength' => 2,'style' => 'width:40px;']) ?>
			</td>
			<td width='20px'></td>
			<td width='500px'>    
				<?= $form->field($model, 'nombre')->textInput(['maxlength' => 25,'style' => 'width:250px;']) ?>
			</td>
		</tr>
		<tr>
			<td align='right'>   
				<?= $form->field($model, 'nombre_redu')->textInput(['maxlength' => 3,'style' => 'width:40px;']) ?>
			</td>
			<td width='20px'></td>
			<td>
				 
				<?= $form->field($model, 'campo_clave')->textInput(['maxlength' => 15,'style' => 'width:120px;']) ?>
				
				<div style='float:right;margin-top:-33px;margin-right:202px;'><?= $form->field($model, 'est')->textInput(['maxlength' => 1,'style' => 'width:25px;','disabled' => 'true']) ?></div>
				
			</td>
		</tr>
		<tr>
			<td align='right'>    
				<?= $form->field($model, 'letra')->textInput(['maxlength' => 1,'style' => 'width:40px;']) ?>
			</td>
			<td width='20px'></td>
			<td>    
				<?= $form->field($model, 'autoinc')->checkbox(['check'=> 1, 'uncheck' => 0]) ?>
				<div id='modif' style='float:right;margin-top:-25px;'><?= $form->field($model, 'modif')->textInput(['maxlength' => 1,'style' => 'width:150px;']) ?></div>
			</td>
		</tr>

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
        	
        	//echo '<script>$("#objetotipo-cod").prop("readOnly",true);</script>';
        	echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'location.reload();']);
			//echo '<script>$("#objetotipo-modif").prop("disabled",true);</script>'; 
        }
     ?>
	</div>  
	 
	<?php
	if($consulta==1){
    	
    	echo '<script>$("#objetotipo-cod").prop("readOnly",true);</script>';
    	echo '<script>$("#objetotipo-nombre").prop("disabled",true);</script>';
    	echo '<script>$("#objetotipo-nombre_redu").prop("disabled",true);</script>';
    	echo '<script>$("#objetotipo-campo_clave").prop("disabled",true);</script>';
    	echo '<script>$("#objetotipo-letra").prop("disabled",true);</script>';
    	echo '<script>$("#objetotipo-autoinc").prop("disabled",true);</script>'; 
    	echo '<script>$("#objetotipo-modif").prop("disabled",true);</script>';                  
    }	 
	?> 
	        
	</div>
	
	<?php 

		//-------------------------seccion de mensajes-----------------------
		
		//echo "Mensaje: ".$_GET['mensaje']."<br>";
		//echo "Consulta: ".$consulta."<br>";
		if(!empty($_GET['mensaje']) || $_GET['mensaje']!=''){
	
			switch ($_GET['mensaje'])
			{
					case 'create' : $mensaje = 'Datos Grabados.'; break;
					case 'update' : $mensaje = 'Datos Grabados.'; break;
					case 'delete' : $mensaje = 'Datos Borrados.'; break;
					default : $mensaje = '';
			}
		}
	
		Alert::begin([
			'id' => 'MensajeInfoTO',
			'options' => [
			'class' => 'alert-info',
			'style' => $mensaje != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($mensaje != '') echo $mensaje;
		
		Alert::end();
		
		if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoTO').alert('close'); }, 5000)</script>";

		//--------------------------seccion de errores------------------------
		
		echo $form->errorSummary($model);
		
		if(isset($_GET['error']) and $_GET['error'] !== '') {  
		echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_GET['error'] . '</ul></div>';
		
	    } 

	ActiveForm::end(); ?>
	
	</div>