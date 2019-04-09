<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\config\RodadoAforo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rodado-aforo-form">

    <?php $form = ActiveForm::begin(); ?>

<table border='0'><tr><td>    <?= $form->field($model, 'anioval')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'origen_id')->textInput(['maxlength' => 1,'style' => 'width:10px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'marca_id')->textInput(['maxlength' => 3,'style' => 'width:30px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'tipo_id')->textInput(['maxlength' => 3,'style' => 'width:30px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'modelo_id')->textInput(['maxlength' => 3,'style' => 'width:30px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'anio')->textInput(['style' => 'width:100px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'valor')->textInput(['style' => 'width:200px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'fchmod')->textInput(['style' => 'width:200px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'usrmod')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td></table>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Generar' : 'Grabar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
