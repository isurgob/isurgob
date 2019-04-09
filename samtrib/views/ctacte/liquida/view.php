<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Liquida */

$this->params['breadcrumbs'][] = ['label' => 'Tributos'];
$this->params['breadcrumbs'][] = 'Liquidaciones Eventuales';

?>
<div class="liquida-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <table border='0' width='100%'>
    <tr>
    	<td width='690'>
    		<?php
				if( isset(Yii::$app->session['mensaje']) or isset(Yii::$app->session['info']) )
				{
				
					Alert::begin([
						'id' => 'AlertaLiq',
						'options' => [
						'class' => isset(Yii::$app->session['mensaje']) ? 'alert-success' : 'alert-info',
						],
					]);	
				
						echo (isset(Yii::$app->session['mensaje']) ? Yii::$app->session->getFlash('mensaje') : Yii::$app->session->getFlash('info'));
					
					Alert::end();
					
					echo "<script>window.setTimeout(function() { $('#AlertaLiq').alert('close'); }, 5000)</script>"; 
				
				}
    			
    			if (isset($consulta)==null) $consulta=1;
    			
    			echo $this->render('_form',[
						'arrayCtaCte' => $arrayCtaCte,
						'dataProviderItem' => $dataProviderItem,
						'consulta' => $consulta,
						'ttrib' => isset($ttrib) ? $ttrib : 0,
						'ucm' => isset($ucm) ? $ucm : 0,
						'uso_subcta' => isset($uso_subcta) ? $uso_subcta : 0,
						'ItemDef' => isset($ItemDef) ? $ItemDef : null,
						'param1' => isset($param1) ? $param1 : 0,
						'monto' => isset($monto) ? $monto : 0,
						'item_id' => isset($item_id) ? $item_id : '',
						'erroritem' => isset($erroritem) ? $erroritem : '',
						'valor_mm' => isset($valor_mm) ? $valor_mm : 0,
						'ItemUno' => isset($ItemUno) ? $ItemUno : null,
						'total' => isset($total) ? $total: 0,
						'error' => isset($error) ? $error : ''
					]) ;
			?>
    	</td>
    	<td align='right' valign='top'>
    		<?= $this->render('menu_derecho',['consulta' => $consulta, 'ctacte_id' => $arrayCtaCte['ctacte_id'],
							'obj_id' => $arrayCtaCte['obj_id']]) ?> 
    	</td>
    </tr>
    </table>
</div>
