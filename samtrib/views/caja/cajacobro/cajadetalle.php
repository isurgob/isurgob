<?php

/**
 * Vista que se mostrará como Ventana Modal cuando se ingrese un Código en "cajacobro".
 */
 
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;
use yii\web\Session;
use yii\data\ArrayDataProvider;


Pjax::begin(['id'=>'PjaxModalCajaDetalle']);

?>
 	
 	<!-- INICIO DIV Caja Detalle -->
 	<div id="cajadetalle">
 	
 	<div class="form-panel">
 	
 	<h3><strong>Datos del Ticket</strong></h3>
 	
 	<table>
 		<tr>
 			<td width="85px"><label>Tipo Objeto:</label></td>
 			<td><?= Html::input('text','txTObj',$model->ticket_tobj,['id'=>'cajaDetalle_txTObj','class'=>'form-control solo-lectura disabled','style'=>'width:120px']) ?></td>
 			<td width="10px"></td>
 			<td><label>Objeto:</label></td>
 			<td><?= Html::input('text','txObjID',$model->ticket_obj_id,['id'=>'cajaDetalle_txObjID','class'=>'form-control solo-lectura disabled','style'=>'width:70px;text-align:center']) ?></td>
 			<td><?= Html::input('text','txNum',$model->ticket_subcta,['id'=>'cajaDetalle_txNum','class'=>'form-control solo-lectura disabled','style'=>'width:25px;text-align:center']) ?></td>
 		</tr>
 	</table>
 	
 	<table>
 		<tr>
 			<td width="85px"><label>Contribuyente:</label></td>
 			<td><?= Html::input('text','txContribID',$model->ticket_num,['id'=>'cajaDetalle_txContribID','class'=>'form-control solo-lectura disabled','style'=>'width:70px;text-align:center']) ?></td>
 			<td><?= Html::input('text','txContribNom',$model->ticket_num_nom,['id'=>'cajaDetalle_txContribNom','class'=>'form-control solo-lectura disabled','style'=>'width:200px;text-align:left']) ?></td>
 		</tr>
 		<tr>
 			<td><label>Tributo:</label></td>
 			<td><?= Html::input('text','txTribID',$model->ticket_trib_id,['id'=>'cajaDetalle_txTribID','class'=>'form-control solo-lectura disabled','style'=>'width:70px;text-align:center']) ?></td>
 			<td><?= Html::input('text','txTribNom',$model->ticket_trib_nom,['id'=>'cajaDetalle_txTribNom','class'=>'form-control solo-lectura disabled','style'=>'width:200px;text-align:left']) ?></td>
 		</tr>
 	</table>
 	
  	<table>
 		<tr>
 			<td><label>Año:</label></td>
 			<td><?= Html::input('text','txAnio',$model->ticket_anio,['id'=>'cajaDetalle_txAnio','class'=>'form-control solo-lectura disabled','style'=>'width:50px;text-align:center']) ?></td>
 			<td width="20px"></td>
 			<td><label>Cuota:</label></td>
 			<td><?= Html::input('text','txCuota',$model->ticket_cuota,['id'=>'cajaDetalle_txCuota','class'=>'form-control solo-lectura disabled','style'=>'width:40px;text-align:center']) ?></td>
 			<td width="30px"></td>
 			<td><label>Fech.Venc.:</label></td>
 			<td><?= Html::input('text','txFchvenc',$model->ticket_fchvenc,['id'=>'cajaDetalle_txFchvenc','class'=>'form-control solo-lectura disabled','style'=>'width:80px;text-align:center']) ?></td>
 		</tr>
 	</table>	
 	
 	</div>
 	
 	<!-- INICIO DIV Grilla-->
 	<div class="form-panel" style="padding-right:10px">
 	<div style="padding-right:10px;width:370px">
 	<?php
 	
	 	echo GridView::widget([
					'id' => 'GrillaTicketDetalle',
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'dataProvider' => new ArrayDataProvider(['allModels' => $model->dataProviderDetalleTemp,'sort' => ['attributes' => ['cta_id','cta_nom','monto'],
    ],]),
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [
							['attribute'=>'cta_id','header' => 'Nº Cta', 'contentOptions'=>['style'=>'text-align:left','width'=>'10px']],
							['attribute'=>'cta_nom','header' => 'Cuenta', 'contentOptions'=>['style'=>'text-align:left','width'=>'250px']],
							['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'10px']],
							
			        	],
				]);
 		
 	?>
 	
 	<table width="100%" >
 		<tr>
 			<td align="right">
 				<label>Total</label>
 				<?= Html::input('text','txMonto',$model->ticket_monto,['id'=>'cajaDetalle_txMonto','class'=>'form-control solo-lectura disabled','style'=>'width:80px;text-align:right']) ?>
 			</td>
 		</tr>
 	</table>
 	</div>
 	</div>
 	<!-- FIN DIV Grilla-->
 	
 	<!-- INICIO DIV Botones -->
 	<div>
 	<table>
 		<tr>
 			<td><?= Html::button('Aceptar',
 					[	'id'=>'cajaDetalle_btAceptar',
						'class'=>'btn btn-primary',
						'onclick'=>($model->ticket_monto > 0 || $model->ticket_trib_tipo == 3 || $model->ticket_trib_tipo == 4 ? 'cajaDetalleBtAceptar()' : '')]); ?></td>
 			<td width="20px"></td>
 			<td><?= Html::button('Cancelar',['id'=>'cajaDetalle_btCancelar','class'=>'btn btn-danger','onclick'=>'cerrarModalCajaDetalle()']); ?></td>
 		</tr>
 	</table>
 	</div>
 	<!-- FIN DIV Botones -->
 	
 	</div>
 	<!-- FIN DIV Caja Detalle -->
 	
 	<?php
 	
 		Pjax::end();
 	?>
 	<script>
		$(document).ready(function()
		{
	        $("#ModalCajaDetalle").on('shown.bs.modal', function ()
	        {
	            document.getElementById("cajaDetalle_btAceptar").focus();
		    });
		});
 	
 	</script>