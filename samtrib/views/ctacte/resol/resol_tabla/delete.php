<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcMm */

$title = 'Eliminar Tabla';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Rubros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

if (isset($error) == null) $error = '';
if (isset($mensaje) == null) $mensaje = '';
?>
    <?= $this->render('_form', [
        'modelTabla' => $modelTabla,
        'TablaCol' => $TablaCol,
        'resol_id'=>$resol_id,
        'consulta' => 2,
        'error' => $error,
        'mensaje' => $mensaje      
    ]) ?>

