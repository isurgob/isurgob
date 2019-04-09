<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Item */

$title = 'Nuevo item';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

$mensaje = isset($mensaje) ? $mensaje : null;
?>



<div class="item-create row">
	<div class="col-xs-12">

    <h1><?php echo Html::encode($title) ?></h1>

    <?php
    echo $this->render('_form', [
        'model' => $model,
        'consulta' => 0,
        'mensaje' => $mensaje,
        'extras' => $extras,
    ]) ?>
	</div>
</div>
