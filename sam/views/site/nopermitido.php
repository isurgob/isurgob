<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

//$this->title = $name;
?>
<div class="site-error">

    <h1><? //Html::encode($name) ?></h1>

    <div class="alert alert-danger">
        <?= 'No permitido'; ?>
    </div>

    <p>
        No tiene acceso para ejecutar esta acci&oacute;n. Cont&aacute;ctese con el Administrador de Sistemas.
    </p>

</div>
