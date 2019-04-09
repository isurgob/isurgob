<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\TribVenc */

$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Vencimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Nuevo Vencimiento';
?>

<h1><?= Html::encode('Nuevo Vencimiento') ?></h1>
	
    <?= $this->render('_form', [
        'model' => $model,
        'consulta' => 0,
    ]) ?>
