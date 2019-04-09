<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcMm */

if (isset($error) == null) $error = '';
if (isset($mensaje) == null) $mensaje = '';
?>

    <?= $this->render('form_resol', [
        'model' => $model,
        'consulta'=>3,
        'error' => $error,
        'm' => $mensaje
    ]) ?>

