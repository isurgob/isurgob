<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

$title = 'Cheque Cartera';
$this->params['breadcrumbs'][] = $title;

/* 
 * Recibo:
 * 	$dia => Fecha Actual
 * 	$consulta => Valor para determinar si se dibuja como index (1), create (0), delete (2) o update(3)
 *  $model => Modelo de la BD.
 */


?>
<div class="liquida-view">

    <h1><?= Html::encode($title) ?></h1>

    <table border='0' width='100%'>
    <tr>
    	<td width='440px'>
    		<?php
    			
    			echo $this->render('_formChequeCartera', [
    					'model'=>$model,
    					'consulta' => $consulta,
    					'id'=>$id,
    					'alert'=>$alert,
    					'm'=>$m,
    				]) ?>
    	</td>
    	<td align='right' valign='top' width="100px">
    		<?= $this->render('chequeCartera_menu_derecho',[
								'consulta' => $consulta, 
								'model'=>$model,
								'id'=>$id,
								]); ?> 
    	</td>
    	<td width="200px"></td>
    </tr>
    </table>
</div>