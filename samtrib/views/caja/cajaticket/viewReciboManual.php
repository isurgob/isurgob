<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;



/* 
 * Recibo:
 * 	$dia => Fecha Actual
 * 	$consulta => Valor para determinar si se dibuja como index (1), create (0), delete (2) o update(3)
 *  $model => Modelo de la BD.
 */
$title = 'Recibo Manual';
$this->params['breadcrumbs'][] = $title;


?>
<div class="liquida-view">

    <h1><?= Html::encode($title) ?></h1>

    <table border='0' width='100%'>
    <tr>
    	<td width='500px'>
    		<?php
    			
    			echo $this->render('_formReciboManual', [
    					'model'=>$model,
    					'consulta' => $consulta,
    					'id'=>$id,
    					'alert'=>$alert,
    					'm'=>$m,
    				]) ?>
    	</td>
    	<td align='right' valign='top' width="100px">
    		<?= $this->render('reciboManual_menu_derecho',[
								'consulta' => $consulta, 
								'id'=> $id,
								'baja'=>$baja,
								]); ?> 
    	</td>
    	<td width="200px"></td>
    </tr>
    </table>
</div>