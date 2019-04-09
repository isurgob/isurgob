<?php

use yii\helpers\Html;
use app\utils\db\Fecha;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcMm */

$title = 'Eliminar Vigencia ';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Rubros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

if (isset($error) == null) $error = '';
if (isset($mensaje) == null) $mensaje = '';
?>

    <?= $this->render('_form', [
        'model' => $model,
        'consulta' => 2,
        'error' => $error,
    ]) ?>
