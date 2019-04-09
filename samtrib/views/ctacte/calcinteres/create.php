<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcInteres */

$title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Definición de intereses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="calc-interes-create">

    <h1><?php echo Html::encode($title) ?></h1>
    
    <div class="separador-horizontal"></div>

    <?php
    echo $this->render('_form', [
        'model' => $model,
        'opcionesBoton' => ['class' => 'btn btn-success'],
        'mensaje' => isset($mensaje) != null ? $mensaje : null,
    ]) ?>
</div>
