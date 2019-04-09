<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcInteres */

$title = 'Modificar';
$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Definición de intereses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

$mensaje = isset($mensaje) ? $mensaje : null;
?>

<div class="calc-interes-update">

    <h1><?php echo Html::encode($title) ?></h1>

    <?php
    echo  $this->render('_form', [
        	'model' => $model,
        	'mensaje' => $mensaje,
        	'opcionesBoton' => ['class' => 'btn btn-success'],
        	'deshabilitar' => ['fchdesde', 'fchhasta']
    ]) ?>

</div>
