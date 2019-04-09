<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\config\ObjetoTribCat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="objeto-trib-cat-form">

    <?php $form = ActiveForm::begin(); ?>

<table border='0'><tr><td>    <?= $form->field($model, 'trib_id')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'cat')->textInput(['maxlength' => 1,'style' => 'width:10px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'nombre')->textInput(['maxlength' => 10,'style' => 'width:100px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'fchmod')->textInput(['style' => 'width:200px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'usrmod')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td></table>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Generar' : 'Grabar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
