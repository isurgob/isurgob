<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcInteres */

$title = 'Eliminar';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Definición de intereses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="calc-interes-update">

    <h1><?php echo Html::encode($title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
		'opcionesBoton' => ['class' => 'btn btn-danger'],
        'deshabilitar' => ['fchdesde', 'fchhasta', 'indice'],
        'mensaje' => isset($mensaje) ? $mensaje : null,
    ])
?>
</div>