<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

$title = 'Registro Pago Anterior';
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
    	<td width='600px'>
    		<?php

    			echo $this->render( '_formRegPagoAnt', [
    					'model'    => $model,
    					'consulta' => $consulta,
    					'id'       => $id,
    					'alert'    => $alert,
    					'm'        => $m,
    				]) ?>
    	</td>
    	<td align='right' valign='top' width="100px">
    		<?= $this->render('regPagoAnt_menu_derecho',[
								'consulta' => $consulta,
								'model'=>$model,
								'id'=>$id,
								//'baja'=>$baja,
								//'obj_id'=>$obj_id,
								]); ?>
    	</td>
    	<td width="200px"></td>
    </tr>
    </table>
</div>
