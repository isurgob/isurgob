<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

/**
 * Forma que se dibuja cuando se llega a Incumplimientos
 * Recibo:
 * 			=> $model -> Modelo
 * 			=> $dataProvider -> Datos para la grilla
 */
 
/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */


$form = ActiveForm::begin([
	'id'=>'frmUpdateLote',
	'action'=>['update'],
	]);

?>
<div class="intima-info">
<div class="form" style="padding-right:8px">
<table>
	<tr>
		<td width="55px"><label>Lote:</label></td>
		<td><?= Html::input('text','txLote',$id,['id'=>'intima-txLote','class'=>'form-control','style'=>'width:40px;text-align:center','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="40px"><label>Título:</label></td>
		<td><?= Html::input('text','txTitulo',$model->titulo,['id'=>'intima-txTitulo','class'=>'form-control','style'=>'width:300px','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="55px"><label>Estado:</label></td>
		<td><?= Html::input('text','txEstado',$model->est_nom,['id'=>'intima-txEstado','class'=>'form-control','style'=>'width:100px;text-align:center','readOnly'=>true]) ?></td>
	</tr>
</table>
<table>
	<tr>
		<td width="55px"><label>Tipo:</label></td>
		<td><?= Html::input('text','txTipo',$model->tipo_nom,['id'=>'intima-txTipo','class'=>'form-control','style'=>'width:245px','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="55px"><label>TObjeto:</label></td>
		<td><?= Html::input('text','txTObjeto',$model->tobj_nom,['id'=>'intima-txTObjeto','class'=>'form-control','style'=>'width:80px;text-align:center','readOnly'=>true]) ?></td>
		<td width="20px"></td>		
		<td width="55px"><label>Tributo:</label></td>
		<td><?= Html::input('text','txTributo',$model->trib_nom,['id'=>'intima-txTributo','class'=>'form-control','style'=>'width:100px;text-align:center','readOnly'=>true]) ?></td>
	</tr>
</table>
<table>
	<tr>
		<td rowspan="2" width="55px"><label>Detalle:</label></td>
		<td rowspan="2"><?= Html::textarea('txDetalle',$model->detalle,['id'=>'intima-txDetalle','class'=>'form-control','style'=>'height:60px;width:402px;max-width:402px;max-height:120px','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td><label>Cantidad:</label></td>
		<td><?= Html::input('text','txCantidad',$model->cant,['id'=>'intima-txCantidad','class'=>'form-control','style'=>'width:100px;text-align:center','readOnly'=>true,]) ?></td>
	</tr>
	<tr>
		<td width="20px"></td>
		<td width="55px"><label>Deuda:</label></td>
		<td><?= Html::input('text','txDeuda',$model->deuda,['id'=>'intima-txDeuda','class'=>'form-control','style'=>'width:100px;text-align:right','readOnly'=>true]) ?></td>
	</tr>
</table>
<table>
	<tr>
		<td width="55px"><label>Generó:</label></td>
		<td><?= Html::input('text','txGenero',$model->alta,['id'=>'intima-txGenero','class'=>'form-control','style'=>'width:140px','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="45px"><label>Aprob:</label></td>
		<td><?= Html::input('text','txAprob',$model->aprobacion,['id'=>'intima-txAprob','class'=>'form-control','style'=>'width:150px','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="45px"><label>Modif:</label></td>
		<td><?= Html::input('text','txModif',$model->modif,['id'=>'intima-txModif','class'=>'form-control','style'=>'width:155px','readOnly'=>true]) ?></td>
	</tr>
</table>
<table width="99%">
	<tr>
		<td width="55px"><label>Obs:</label></td>
		<td><?= Html::textarea('txObs',$model->obs,['id'=>'intima-txObs','class'=>'form-control','style'=>'width:100%;height:60px;max-width:580px;max-height:120px','readOnly'=>true]) ?></td>
	</tr>
</table>

<!-- INICIO Grilla -->
	<?php
		if ($id != '')
		{
			Pjax::begin();
				echo GridView::widget([
						'id' => 'GrillaInfoIntima',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dataProvider,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
								['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'num','header' => 'Num', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'obj_nom','header' => 'Nombre', 'contentOptions'=>['width'=>'250px']],
								['attribute'=>'dompos_dir','header' => 'Domi', 'contentOptions'=>['width'=>'150px']],
								['attribute'=>'plan_id','header' => 'Plan', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'periodos','header' => 'Per', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'total','header' => 'Deuda', 'contentOptions'=>['style'=>'text-align:right','width'=>'50px']],
								[
									'class' => 'yii\grid\ActionColumn',
									'contentOptions'=>['style'=>'width:30px'],
									'template' =>(($model->aprobacion !== null && !empty(trim($model->aprobacion))) ? '{viewseguimiento}&nbsp;{deleteobjeto}' : '{deleteobjeto}'),
									'buttons'=>[			
									
										'viewseguimiento' => function($url, $model, $key){
											
											if(isset($model['inti_id'])) $url = $url. '&inti_id=' . $model['inti_id'];
											return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['data-pjax' => 'false']);
										},
									
										'deleteobjeto' =>  function($url, $model, $key)
													{
														$url .= '&lote_id='.$model['lote_id'].'&obj_id='.$model['obj_id'];
														return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
													},
									]
								]							
				        	],
					]);
					
			Pjax::end(); 
			
			
		}
	?>	
		
<!-- FIN Grilla -->

<?php

		//INICIO Modal Modificar Lote
		Modal::begin([
				'id' => 'ModalModificarLote',
				'size' => 'modal-normal',
				'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:rgb(51, 122, 183)">Modificar Lote</h2>',
				'closeButton' => [
    				'label' => '<b>X</b>',
        			'class' => 'btn btn-danger btn-sm pull-right',
        			'id' => 'btCancelarModalElim'
    				],
			]);
							
			echo "<label>Observación</label>";			
			echo "<center>";
			echo Html::textarea('txObs',$model->obs,['id'=>'intima-txObs','class'=>'form-control','style'=>'width:100%;height:60px;max-width:100%;max-height:120px']);

			echo Html::Button('Grabar', ['class' => 'btn btn-success','onclick'=>'$("#frmUpdateLote").submit()']);
		
			echo "&nbsp;&nbsp;";
	 		echo Html::Button('Cancelar', ['id'=>'btModificarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalModificarLote").modal("hide")']);
	 		echo "</center>";
	 		
	 	Modal::end();
		//FIN Modal Modificar Lote
	
		//INICIO Modal eliminar Lote
		Modal::begin([
				'id' => 'ModalEliminarLote',
				'size' => 'modal-sm',
				'header' => '<h4><b>Confirmar Eliminación</b></h4>',
				'closeButton' => [
    				'label' => '<b>X</b>',
        			'class' => 'btn btn-danger btn-sm pull-right',
        			'id' => 'btCancelarModalElim'
    				],
			]);
										
			echo "<center>";
			echo "<p><label>¿Esta seguro que desea eliminar el lote?</label></p><br>";

			echo Html::a('Aceptar', ['borrar','id' => $id],['class' => 'btn btn-success',]);
		
			echo "&nbsp;&nbsp;";
	 		echo Html::Button('Cancelar', ['id'=>'btEliminarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalEliminarLote").modal("hide")']);
	 		echo "</center>";
	 		
	 	Modal::end();
		//FIN Modal eliminar Lote
		
		//INICIO Modal Aprobar Lote
		Modal::begin([
				'id' => 'ModalAprobarLote',
				'size' => 'modal-sm',
				'header' => '<h4><b>Confirmar Aprobación</b></h4>',
				'closeButton' => [
    				'label' => '<b>X</b>',
        			'class' => 'btn btn-danger btn-sm pull-right',
        			'id' => 'btCancelarModalElim'
    				],
			]);
										
			echo "<center>";
			echo "<p><label>¿Esta seguro que desea aprobar el lote?</label></p><br>";

			echo Html::a('Aceptar', ['aprobar','id' => $id],['class' => 'btn btn-success',]);
		
			echo "&nbsp;&nbsp;";
	 		echo Html::Button('Cancelar', ['id'=>'btAprobarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalAprobarLote").modal("hide")']);
	 		echo "</center>";
	 		
	 	Modal::end();
		//FIN Modal Aprobar Lote 
		
		//INICIO Modal Proceso Lote
		Modal::begin([
				'id' => 'ModalProcesoLote',
				'size' => 'modal-sm',
				'header' => '<h4><b>Confirmar Proceso Lote</b></h4>',
				'closeButton' => [
    				'label' => '<b>X</b>',
        			'class' => 'btn btn-danger btn-sm pull-right',
        			'id' => 'btCancelarModalElim'
    				],
			]);
										
			echo "<center>";
			echo "<p><label>¿Esta seguro que desea procesar el lote?</label></p><br>";

			echo Html::a('Aceptar', ['procesolote','id' => $id],['class' => 'btn btn-success',]);
		
			echo "&nbsp;&nbsp;";
	 		echo Html::Button('Cancelar', ['id'=>'btProcesoLoteCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalProcesoLote").modal("hide")']);
	 		echo "</center>";
	 		
	 	Modal::end();
		//FIN Modal Proceso Lote 
		
		
		echo '<br />';
		echo '<br />';
		
	

	ActiveForm::end();
?>

</div>
</div>
