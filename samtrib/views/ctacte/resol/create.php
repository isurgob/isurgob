<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcMm */

$title = 'Nueva Resolución';
$this->params['breadcrumbs'][] = ['label' => 'Configuración', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Resolución', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

if (isset($error) == null) $error = '';
if (isset($mensaje) == null) $mensaje = '';
?>

<h2><label><?= $title; ?></label></h2>
<div class="separador-horizontal"></div>


<?= $this->render('_form', [
    'model' => $model,
    'consulta'=>0,
    'mensaje' => $mensaje        
]) ?>