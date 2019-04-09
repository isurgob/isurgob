<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Feriados - '.date('Y');
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => Yii::$app->param->sis_url.'site/config'];
$this->params['breadcrumbs'][] = $title;

?>
<div class="calc-feriado-index" style='overflow:hidden'>

    <table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:5px'>
	    <tr>
	    	<td><h1><?= Html::encode($title) ?></h1></td>
	    </tr>
    </table>
    
    <?php
    if (isset(Yii::$app->session['msj']) == null) Yii::$app->session['msj'] = '';
	Alert::begin([
		'id' => 'AlertaFeriado',
		'options' => [
		'class' => 'alert-success',
		'style' => 'width: 710px;' . (Yii::$app->session['msj'] !== '' ? 'display:block' : 'display:none')
		],
	]);

	if (Yii::$app->session['msj'] !== '') echo Yii::$app->session['msj'];
	
	Alert::end();
	
	if (Yii::$app->session['msj'] !== '') echo "<script>window.setTimeout(function() { $('#AlertaFeriado').alert('close'); }, 5000)</script>";
	Yii::$app->session['msj'] = null;
	
    ?>
    
    <?= $this->render('calendario',['feriados'=>$dataProvider->getModels()]); ?>
    
    <?= $this->render('_form',['consulta'=>$consulta,'fecha'=>$fecha]); ?>
    

</div>

