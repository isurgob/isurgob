<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcMm */
/* @var $form yii\widgets\ActiveForm */


?>
<div id="_form">

<?php 
	
	$form = ActiveForm::begin([
           'id'=>'form-calcmm',
	       'class' => 'form form-inline',
	       'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-xs-3\">{input}</div>",
                'labelOptions' => ['class' => 'col-xs-2 control-label'],
            ]
   ]);
    
    ?>
 
 <div class="form">  
<table>
	<tr>
	    <td width='80px'><label>Fecha desde:</label></td>
	    <td>	     
	   		<?= $form->field($model, 'fchdesde')->widget(DatePicker::classname(), [
	   				'dateFormat' => 'dd/MM/yyyy',
	   				'options' => [
	   					'class'=>'form-control',
	   					'style' => 'width: 80px;text-align: center',
	   				],
	   			])->label(false);?> 
	    
	    </td>
	    
		<td></td>
		<td width='80px'><label>Fecha hasta:</label></td>
		<td>
			<?= $form->field($model, 'fchhasta')->widget(DatePicker::classname(), [
					'dateFormat' => 'dd/MM/yyyy',
					'options' => [
						'class'=>'form-control',
						'style' => 'width: 80px;text-align: center',
					]
				])->label(false);
			?>
		</td>
	</tr>
	<tr>
		<td><label>Valor:</label></td>
		<td>    
			<?= $form->field($model, 'valor')->textInput([
					'disabled' => 'true',
					'style' => 'width: 80px; text-align:right',
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
				])->label( false );
			?>
		</td>
	</tr>
</table> 

</div>

<?php 
	
	if ( $consulta <> 1 )
	{ 
	
		if(isset($error) and $error !== '') {  
			echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
	} 
	
	?>
    <div class="form-group" style="margin-top: 8px; margin-bottom: 8px;">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']); ?>
    </div>

    <?php 
    
    	echo $form->errorSummary($model);
    
    }
    
    ActiveForm::end(); 

    	if ($consulta==1) {
    		echo "<script>DesactivarForm('form-inm');</script>";
    	}
    	
        if($consulta==1){
    	
    	echo '<script>$("#calcmm-fchdesde").prop("disabled",true);</script>';
    	echo '<script>$("#calcmm-fchhasta").prop("disabled",true);</script>';
    	echo '<script>$("#calcmm-valor").prop("disabled",true);</script>';
    	
        }
    
        if($consulta==2){
    	
    	echo '<script>$("#calcmm-fchdesde").prop("disabled",false);</script>';
    	echo '<script>$("#calcmm-fchhasta").prop("disabled",false);</script>';
    	echo '<script>$("#calcmm-valor").prop("disabled",false);</script>';
        }
        
        if($consulta==3){
    	
    	echo '<script>$("#calcmm-fchdesde").prop("disabled",true);</script>';
    	echo '<script>$("#calcmm-fchhasta").prop("disabled",true);</script>';
    	echo '<script>$("#calcmm-valor").prop("disabled",false);</script>';
        }
      
    ?>
    
</div>

