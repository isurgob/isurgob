<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
use \yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Plan */

$this->params['breadcrumbs'][] = 'Convenios de Pago';

if (!isset($consulta)) $consulta = 1;
 
?>
<div class="site-index">

    <h1><?= Html::encode('Convenios de Pago') ?></h1>

    <table border='0' width='100%'>
    <tr>
    	<td width='671'>
    		<?php
    			if (isset($mensaje) == null) $mensaje = '';
    			Alert::begin([
    				'id' => 'AlertaPlan',
					'options' => [
        			'class' => (isset($_GET['m']) and $_GET['m'] == 1) ? 'alert-success' : 'alert-info',
        			'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
    				],
				]);

				if ($mensaje !== '') echo $mensaje;
				
				Alert::end();
				
				if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaPlan').alert('close'); }, 5000)</script>";
    			
    			echo $this->render('_form', [
        				'model' => $model,
        				'modeldomi' => $modeldomi,
        				'cuotas' => $cuotas,
        				'periodos' => $periodos,
        				'error' => $error,
        				'consulta' => $consulta,
    					]) ;	
    		?> 
    	</td>
    	<td align='right' valign='top'>
    		 <?php
    			echo $this->render('menu_derecho', [
    					'model' => $model,
    					'consulta' => $consulta,
    					'periodos' => $periodos,
    					]) ;
    		?> 	
    	</td>
    </tr>
    </table>
</div>
