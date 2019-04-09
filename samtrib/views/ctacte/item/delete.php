<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Item */

$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Eliminar';

$mensaje = isset($mensaje) ? $mensaje : null;
?>
<div class="item-delete row">
	<div class="col-xs-12">

    <h1><?php echo Html::encode('Eliminar item: ' . $model->item_id) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'consulta' => 2,
        'mensaje' => $mensaje,
        'extras' => $extras,
    ]) ?>
	</div>
</div>
