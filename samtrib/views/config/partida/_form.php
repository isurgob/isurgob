<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\config\Partida */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partida-form">

    <?php $form = ActiveForm::begin(); ?>

<table border='0'><tr><td>    <?= $form->field($model, 'grupo')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'subgrupo')->textInput(['style' => 'width:100px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'rubro')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'cuenta')->textInput(['style' => 'width:100px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'subcuenta')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'conc')->textInput(['style' => 'width:100px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'subconc')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'formato')->textInput(['maxlength' => 50,'style' => 'width:500px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'formatoaux')->textInput(['maxlength' => 50,'style' => 'width:500px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'nombre')->textInput(['maxlength' => 50,'style' => 'width:500px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'padre')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'nivel')->textInput(['style' => 'width:100px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'bcocta_id')->textInput(['style' => 'width:100px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'est')->textInput(['maxlength' => 1,'style' => 'width:10px;']) ?></td></tr><tr><td>    <?= $form->field($model, 'fchmod')->textInput(['style' => 'width:200px;']) ?></td><td width='20px'></td><td>    <?= $form->field($model, 'usrmod')->textInput(['style' => 'width:100px;']) ?></td></tr></table>    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Generar' : 'Grabar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
