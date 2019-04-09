<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\config\CemTalq */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cem-talq-form">

	<?php $form = ActiveForm::begin(); ?>
	
		<table border='1'>
			<tr>
				<td>
					<?= $form->field($model, 'cod')->textInput(['style' => 'width:100px;']) ?>
				</td>
				<td width='20px'></td>
				<td>    
					<?= $form->field($model, 'desde')->textInput(['style' => 'width:200px;']) ?>
				</td>
			</tr>
			<tr>
				<td>    
					<?= $form->field($model, 'hasta')->textInput(['style' => 'width:200px;']) ?>
				</td>
				<td width='20px'></td>
				<td>    
					<?= $form->field($model, 'tipo')->textInput(['maxlength' => 2,'style' => 'width:20px;']) ?>
				</td>
			</tr>
			<tr>
				<td>
					<?= $form->field($model, 'cuadrodesde')->textInput(['maxlength' => 3,'style' => 'width:30px;']) ?>
				</td>
				<td width='20px'></td>
				<td>
					<?= $form->field($model, 'cuadrohasta')->textInput(['maxlength' => 3,'style' => 'width:30px;']) ?>
				</td>
			</tr>
			<tr>
				<td>
					<?= $form->field($model, 'cuerpo_id')->textInput(['maxlength' => 3,'style' => 'width:30px;']) ?>
				</td>
				<td width='20px'></td>
				<td>   
					<?= $form->field($model, 'fila')->textInput(['maxlength' => 3,'style' => 'width:30px;']) ?>
				</td>
			</tr>
			<tr>
				<td>
					<?= $form->field($model, 'cat')->textInput(['style' => 'width:100px;']) ?>
				</td>
				<td width='20px'></td>
				<td> 
					<?= $form->field($model, 'supdesde')->textInput(['style' => 'width:100px;']) ?>
				</td>
			</tr>
			<tr>
				<td>
					<?= $form->field($model, 'suphasta')->textInput(['style' => 'width:100px;']) ?>
				</td>
				<td width='20px'></td>
				<td>
					<?= $form->field($model, 'duracion')->textInput(['style' => 'width:100px;']) ?>
				</td>
			</tr>
			<tr>
				<td>
					<?= $form->field($model, 'fchmod')->textInput(['style' => 'width:200px;']) ?>
				</td>
				<td width='20px'></td>
				<td> 
					<?= $form->field($model, 'usrmod')->textInput(['style' => 'width:100px;']) ?>
				</td>
			</tr>
		</table>
		<div class="form-group">	   
			<?= Html::submitButton($model->isNewRecord ? 'Generar' : 'Grabar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	
	<?php ActiveForm::end(); ?>

</div>
