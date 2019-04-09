<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcMm */

$title = 'Nueva Tabla';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Resolucion', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

if (isset($error) == null) $error = '';
if (isset($mensaje) == null) $mensaje = '';
?>
    <?= $this->render('_form', [
        'model' => $model,
        'resol_id'=>$resol_id,
        'tabla_id'=>$tabla_id,
        'cantcol'=>$cantcol,
        'consulta'=>0,
        'error' => $error,
        'mensaje' => $mensaje
        
    ]) ?>

