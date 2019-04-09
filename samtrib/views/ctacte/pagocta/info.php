<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;

/**
 * Forma que se dibuja cuando se llega a Pagocta
 * Recibo:
 * 			=> $model -> Modelo de Pagocta
 */
 
/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */
?>
<div class="pagocta_info">
<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 

			Pjax::begin(['id'=>'errorPagocta']);
			
			$mensaje = '';
			
			$mensaje = Yii::$app->request->post('mensaje','');
			$m = Yii::$app->request->post('m',2);
			
		
			if($mensaje != "")
			{ 
		
		    	Alert::begin([
		    		'id' => 'AlertaMensajePagocta',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
		    		],
				]);
		
				echo $mensaje;
						
				Alert::end();
				
				echo "<script>window.setTimeout(function() { $('#AlertaMensajePagocta').alert('close'); }, 5000)</script>";
			 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->
<div class="form-panel" style="padding-right:8px">

<table>
	<tr>
		<td width="50px"><label>Tributo:</label></td>
		<td width="210px"><?= Html::dropDownList('dlTrib',$model->trib_id,utb::getAux('trib','trib_id','nombre'),['id'=>'pagocta_nuevo_dlTrib','class'=>'form-control disabled','style'=>'width:100%;text-align:left','readOnly'=>true,'tabIndex'=>-1]) ?></td>
		<td width="15px"></td>
		<td width="45px"><label>Objeto:</label></td>
		<td><?= Html::input('text','txObjID',$model->obj_id,['id'=>'pagocta_txObjID','class'=>'form-control disabled','style'=>'width:70px;text-align:center','readOnly'=>true,'tabIndex'=>-1]) ?>
		<label> - </label>
		<?= Html::input('text','txObjNom',$model->obj_nom,['id'=>'pagocta_txObjNom','class'=>'form-control disabled','style'=>'width:220px','readOnly'=>true,'tabIndex'=>-1]) ?> </td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>SubCta:</label></td>
		<td width="40px"><?= Html::input('text','txSubcta',$model->subcta,['id'=>'pagocta_txSubcta','class'=>'form-control disabled','style'=>'width:100%;text-align:center','readOnly'=>true,'tabIndex'=>-1]) ?></td>
		<td width="15px"></td>
		<td width="30px"><label>Año:</label></td>
		<td width="40px"><?= Html::input('text','txAño',$model->anio,['id'=>'pagocta_txAño','class'=>'form-control disabled','style'=>'width:100%;text-align:left','readOnly'=>true,'tabIndex'=>-1]) ?></td>
		<td width="15px"></td>
		<td width="40px"><label>Cuota:</label></td>
		<td width="30px"><?= Html::input('text','txCuota',$model->cuota,['id'=>'pagocta_txCuota','class'=>'form-control disabled','style'=>'width:100%;text-align:center','readOnly'=>true,'tabIndex'=>-1]) ?></td>
		<td width="15px"></td>
		<td width="45px"><label>Estado:</label></td>
		<td><?= Html::input('text','txEst',$model->est_nom,['id'=>'pagocta_txEst','class'=>'form-control disabled','style'=>'width:70px;text-align:center','readOnly'=>true,'tabIndex'=>-1]); ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Monto:</label></td>
		<td width="75px"><?= Html::input('text','txMonto',$model->monto,['id'=>'pagocta_txMonto','class'=>'form-control disabled','style'=>'width:100%;text-align:right','readOnly'=>true,'tabIndex'=>-1]) ?></td>
		<td width="15px"></td>
		<td width="40px"><label>Límite:</label></td>
		<td width="80px"><?= Html::input('text','txLimite',$model->fchlimite,['id'=>'pagocta_txLimite','class'=>'form-control disabled','style'=>'width:100%;text-align:center','readOnly'=>true,'tabIndex'=>-1]) ?></td>
		<td width="15px"></td>
		<td width="80px"><label>Modificación:</label></td>
		<td width="265px"><?= Html::input('text','txModifica',$model->usrmod,['id'=>'pagocta_txModifica','class'=>'form-control disabled','style'=>'width:100%;text-align:left','readOnly'=>true,'tabIndex'=>-1]) ?></td>
	</tr>
</table>
</div>

<div class="form-panel" style="padding-right:8px">
<table>
	<tr>
		<td width="50px"><label>Obs:</label></td>
		<td><?= Html::textarea('txObs',$model->obs,['id'=>'pagocta_txObs','class'=>'form-control disabled','style'=>'width:570px;max-width:570px;height:40px;max-height:120px','readOnly'=>true,'tabIndex'=>-1]) ?> </td>
	</tr>
</table>
</div>	

<!-- INICIO Grilla Cuentas -->
<div class="form-panel" style="padding-right:15px;padding-bottom:10px">
<table>
	<tr>
		<td width="80px"><label>Cuentas:</label></td>
		<td width="400px">
			
			<?php
			
			Pjax::begin();
				echo GridView::widget([
					'id' => 'GrillaPagoctaCuentas',
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'dataProvider' => $dataProvider,
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [
							['attribute'=>'cta_id','header' => 'Cuenta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'cta_nom','header' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
							['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
			        	],
				]);
					
			Pjax::end();
			?>
		</td>
	</tr>
</table>
</div>
<!-- FIN Grilla Cuentas -->
