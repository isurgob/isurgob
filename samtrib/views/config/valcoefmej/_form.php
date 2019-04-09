<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $model app\models\config\ValMej */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="val-mej-form">

    <?php $form = ActiveForm::begin(['action' => ['valmejabm'], 'fieldConfig' => ['template' => "{label}{input}"]]);
    
    echo Html::input('hidden', 'accion', $consulta, ['id'=>'accion']);
    
    echo "CONSULTA: ".$consulta."<br>";
    
    if($consulta==1 || $consulta==3){

		$fechadesde = strval($model->perdesde);
		$fechadesde = substr($fechadesde,0,4);
		
		$cuotadesde = strval($model->perdesde);
		$cuotadesde = substr($cuotadesde,4,3);
		
		$fechahasta = strval($model->perhasta);
		$fechahasta = substr($fechahasta,0,4);
		
		$cuotahasta = strval($model->perhasta);
		$cuotahasta = substr($cuotahasta,4,7);

	    echo "<script> document.getElementById('valmej-fechadesde').value=".$fechadesde."; </script>";
	    echo "<script> document.getElementById('valmej-fechahasta').value=".$fechahasta."; </script>";
	    echo "<script> document.getElementById('valmej-cuotadesde').value=".$cuotadesde."; </script>";
	    echo "<script> document.getElementById('valmej-cuotahasta').value=".$cuotahasta."; </script>";
    }
    ?>

		<table border='0'>
			<tr>
				<td>
					<div style="float:left;margin-left:13px;"><?= $form->field($model, 'cat')->textInput(['maxlength' => 2,'style' => 'width:35px;']) ?></div>
				</td>
				<td width='20px'></td>
				<td>
				<?php	
					$tipoForm = utb::getAux('inm_mej_tform','cod','nombre_redu',0);
					if($consulta==0){																															 
						echo $form->field($model, 'form')->dropDownList($tipoForm,['style' => 'width:150px;']);
					}else{
						echo $form->field($model, 'form')->dropDownList($tipoForm,['style' => 'width:150px;','readOnly' => 'true']);	
					}
					
					 ?>
				</td>
			</tr>
			<tr>
				<td>
					<?= $form->field($model, 'fechadesde')->textInput(['style' => 'width:100px;'])->label('Fecha desde') ?>
				</td>
				<td width='20px'></td>
				<td>
					<div style="float:left;margin-left:37px;"><?= $form->field($model, 'fechahasta')->textInput(['style' => 'width:100px;']) ?></div>
				</td>
			</tr>
			
			<tr>
				<td>
					<?= $form->field($model, 'cuotadesde')->textInput(['style' => 'width:100px;']) ?>
				</td>
				<td width='20px'></td>
				<td>
					<div style="float:left;margin-left:37px;"><?= $form->field($model, 'cuotahasta')->textInput(['style' => 'width:100px;']) ?></div>
				</td>
			</tr>
			
			<tr>
				<td>
					<div style="float:right;"><?= $form->field($model, 'valor')->textInput(['style' => 'width:100px;']) ?></div>
				</td>
				<td width='20px'></td>
			</tr>	
		</table>
		
	<div class="form-group">
			
			
		<?php
		if($consulta==0){
			
			echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
	    	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'location.reload();']);
			} 
		?>
	  
	    <?php if($consulta==3){ 
	    	
	    	echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
	    	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'location.reload();']);
	    ?>
	    	
		<script>
	    	
			$("#valmej-cat").prop("readOnly",true);
			$("#valmej-fechadesde").prop("readOnly",true);
			$("#valmej-cuotadesde").prop("readOnly",true);
	    	
    	</script>
	    	
	   <?php }   ?>
	    
		</div>
		
		<?php 
		
		
		
			//if ($consulta<>1){ 
			
			echo $form->errorSummary($model);
		 
			if(isset($_GET['error']) and $error !== '') {  
				echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_GET['error'] . '</ul></div>';
				} 
		
		if($consulta==1){ ?>
			
			<script>
			
	    	$("#valmej-cat").prop("readOnly",true);
	    	$("#valmej-fechadesde").prop("disabled",true);
	    	$("#valmej-fechahasta").prop("disabled",true);
	     	$("#valmej-cuotadesde").prop("disabled",true);
	     	$("#valmej-cuotahasta").prop("disabled",true);
	    	$("#valmej-valor").prop("disabled",true);
			   	   	               
			</script>	   	   	               
			   	   	               
   		<?php } ?> 
			
			
	
	
	<?php ActiveForm::end(); ?>

</div>

