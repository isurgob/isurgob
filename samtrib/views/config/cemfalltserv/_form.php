<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\config\CemFallTServ */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cem-fall-tserv-form">

<?php $form = ActiveForm::begin(); ?>

<table border='0'>
	<tr>
		<td>
			<?= $form->field($model, 'cod')->textInput(['maxlength' => 3,'style' => 'width:30px;'])->label('Código:') ?>
		</td>
		<td width='20px'></td>
		<td>   
			<?= $form->field($model, 'nombre')->textInput(['maxlength' => 35,'style' => 'width:350px;'])->label('Nombre:') ?>
		</td>
	</tr>
	<tr>
		<td>  
			<?= $form->field($model, 'est_fin')->textInput(['maxlength' => 3,'style' => 'width:30px;'])->label('Estado final:') ?>
		</td>
		<td width='20px'></td>
		<td>  
			<?= $form->field($model, 'pedir_obj_dest')->textInput(['style' => 'width:100px;']) ?>
		</td>
	</tr>
	<tr>
		<td>
			<?= $form->field($model, 'pedir_dest')->textInput(['style' => 'width:100px;']) ?>
		</td>
		<td width='20px'></td>
		<td>  
			<?= $form->field($model, 'fchmod')->textInput(['style' => 'width:200px;']) ?>
		</td>
	</tr>
	<tr>
		<td>  
			<?= $form->field($model, 'usrmod')->textInput(['style' => 'width:100px;']) ?>
		</td>
		<td width='20px'></td>
</table> 
<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? 'Generar' : 'Grabar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>
