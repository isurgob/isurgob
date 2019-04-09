<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\config\JudiHono */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="judi-hono-form">

    <?php $form = ActiveForm::begin(); ?>

<table border='0'><tr><td>    <?= $form->field($model, 'instancia')->textInput(['maxlength' => 1,'style' => 'width:10px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'supuesto')->textInput(['style' => 'width:100px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'deuda_desde')->textInput(['style' => 'width:200px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'deuda_hasta')->textInput(['style' => 'width:200px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'hono_min')->textInput(['style' => 'width:200px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'hono_porc')->textInput(['style' => 'width:200px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'gastos')->textInput(['style' => 'width:200px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'fchmod')->textInput(['style' => 'width:200px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'usrmod')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td></table>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Generar' : 'Grabar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
