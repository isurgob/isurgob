<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;

/**
 * Forma que se dibuja cuando se llega a Facilidades de Pago
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
	'id'=>'frmFacilidadPago',
	'action'=>['view'],
	]);

?>
<style>

div > div {
	
	padding-bottom: 4px;
}

</style>
<div class="facilida-info">
<div class="form-panel" style="padding-right:8px">
<h3><strong>Datos de la Facilidad</strong></h3>
<table width="100%">
	<tr>
		<td width="90px"><label>Código:</label></td>
		<td><?= Html::input('text','txCodigo',$model->faci_id,['id'=>'facilida-txCodigo','class'=>'form-control disabled','style'=>'width:60px;text-align:center','readOnly'=>true,'tabIndex' => -1]) ?></td>
		<td width="20px"></td>
		<td width="50px"><label>Tributo:</label></td>
		<td><?= Html::input('text','txTributo',$model->trib_nom,['id'=>'facilida-txTributo','class'=>'form-control disabled','style'=>'width:200px;text-align:left','readOnly'=>true,'tabIndex' => -1]) ?></td>
		<td align="right"><label>Estado:</label><?= Html::input('text','txEstado',$model->est_nom,['id'=>'facilida-txEstado','class'=>($model->est == 2 ? 'form-control baja' : 'form-control solo-lectura'),'style'=>'width:100px;text-align:center','readOnly'=>true,'tabIndex' => -1]) ?></td>		
	</tr>
</table>	
	
<table>
	<tr>
		<td width="90px"><label>Tipo de Objeto:</label></td>
		<td><?= Html::input('text','txTObjeto',$model->tobj_nom,['id'=>'facilida-txTObjeto','class'=>'form-control disabled','style'=>'width:140px;text-align:left','readOnly'=>true,'tabIndex' => -1]) ?></td>
		<td width="35px"></td>		
		<td width="40px"><label>Objeto:</label></td>
		<td><?= Html::input('text','txObjetoID',$model->obj_id,['id'=>'facilida-txObjetoID','class'=>'form-control disabled','style'=>'width:80px;text-align:center','readOnly'=>true,'tabIndex' => -1]) ?></td>
		<td><?= Html::input('text','txObjetoNom',$model->obj_nom,['id'=>'facilida-txObjetoNom','class'=>'form-control disabled','style'=>'width:240px;text-align:left','readOnly'=>true,'tabIndex' => -1]) ?></td>	
	</tr>
</table>

<table>
	<tr>
		<td width="90px"><label>Contribuyente:</label></td>
		<td>
			<?= Html::input('text','txContribID',$model->num,[
					'id'=>'facilida-txContribID',
					'class'=>'form-control disabled',
					'style'=>'width:80px;text-align:center',
					'readOnly'=>true,
					'tabIndex' => -1,
				]);
			?>
		</td>
		<td>
			<?= Html::input('text','txContribNom',$model->num_nom,[
					'id'=>'facilida-txContribNom',
					'class'=>'form-control disabled',
					'style'=>'width:457px;text-align:left',
					'readOnly'=>true,
					'tabIndex' => -1,
				]);
			?>
		</td>	
	</tr>
</table>			

<table>
	<tr>
		<td width="90px"><label>Fechas:</label></td>
		<td width="60px"><label>Venc.:</label></td>
		<td width="80px"><?=  DatePicker::widget([	'id' => 'facilida-fchvencimiento',
													'name' => 'fchvencimiento',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => ['class' => 'form-control disabled disabled', 'style' => 'width:80px', 'readOnly'=>true,'tabIndex' => -1],
													'value' => ($model->fchvenc != '' ? Fecha::usuarioToDatePicker($model->fchvenc) : '')
												]);	?>
		</td>
		<td width="35px"></td>
		<td width="55px"><label>Consolidación:</label></td>
		<td width="80px"><?=  DatePicker::widget([	'id' => 'facilida-fchconsolidacion',
													'name' => 'fchconsolidacion',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => ['class' => 'form-control disabled', 'style' => 'width:80px', 'readOnly'=>true,'tabIndex' => -1],
													'value' => ($model->fchconsolida != '' ? Fecha::usuarioToDatePicker($model->fchconsolida) : '')
												]);	?>
		</td>
		<td width="47px"></td>		
		<td width="55px"><label>Imputación:</label></td>
		<td width="80px"><?=  DatePicker::widget(['id' => 	'facilida-fchimputacion',
															'name' => 'fchimputacion',
															'dateFormat' => 'dd/MM/yyyy',
															'options' => ['class' => 'form-control disabled', 'style' => 'width:84px', 'readOnly'=>true,'tabIndex' => -1],
															'value' => ($model->fchimputa != '' ? Fecha::usuarioToDatePicker($model->fchimputa) : '')
												]);	?>
		</td>		
	</tr>
</table>

<table width="100%">
	<tr>
	
		<td width="90px"><label>Baja:</label></td>
		<td><?= Html::input('text','txBaja',$model->baja,['id'=>'facilida-txBaja','class'=>'form-control disabled','style'=>'width:200px','readOnly'=>true,'tabIndex' => -1]) ?></td>
		<td width="20px"></td>
		<td align="right"><label>Modificación:</label><?= Html::input('text','txModif',$model->modif,['id'=>'facilida-txModif','class'=>'form-control disabled','style'=>'width:200px','readOnly'=>true,'tabIndex' => -1]) ?></td>
	</tr>
</table>
</div>


<div class="form-panel" style="padding-right:8px">
<h3><strong>Detalle de Deuda</strong></h3>
<table width="100%">
	<tr>
		<td width="45px"><label>Nominal:</label></td>
		<td><?= Html::input('text','txNominal',$model->nominal,['id'=>'facilida-txNominal','class'=>'form-control disabled','style'=>'width:80px;text-align:right','readOnly'=>true,'tabIndex' => -1]) ?></td>
		<td width="5px"></td>
		<td width="45px"><label>Accesorios:</label></td>
		<td><?= Html::input('text','txAccesorios',$model->accesor,['id'=>'facilida-txAccesorios','class'=>'form-control disabled','style'=>'width:80px;text-align:right','readOnly'=>true,'tabIndex' => -1]) ?></td>
		<td width="5px"></td>
		<td><label>Quita:</label><?= Html::input('text','txQuita',$model->quita,['id'=>'facilida-txQuita','class'=>'form-control disabled','style'=>'width:60px;text-align:right','readOnly'=>true,'tabIndex' => -1]) ?></td>
		<td width="5px"></td>
		<td><label>Multa:</label><?= Html::input('text','txMulta',$model->multa,['id'=>'facilida-txMulta','class'=>'form-control disabled','style'=>'width:60px;text-align:right','readOnly'=>true,'tabIndex' => -1]) ?></td>
		<td width="5px"></td>
		<td align="right"><label>Total:</label><?= Html::input('text','txTotal',$model->monto,['id'=>'facilida-txTotal','class'=>'form-control disabled','style'=>'width:80px;text-align:right','readOnly'=>true,'tabIndex' => -1]) ?></td>
	</tr>
</table>
</div>


<div class="form-panel" style="padding-right:8px">
<h3><strong>Observación</strong></h3>
<table width="99%">
	<tr>
		<td><?= Html::textarea('txObs',$model->obs,['id'=>'facilida-txObs','class'=>'form-control','style'=>'width:620px;height:60px;max-width:620px;max-height:120px','readOnly'=>true,'tabIndex' => -1]) ?></td>
	</tr>
</table>
</div>

<div class="form-panel" style="padding-right:8px">
<h3><strong>Períodos</strong></h3>

<!-- INICIO Grilla -->
	<?php
			Pjax::begin();
				echo GridView::widget([
						'id' => 'GrillaInfofacilida',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dataProvider,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
								['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'subcta','header' => 'SubCta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'nominal','header' => 'Nominal', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								['attribute'=>'accesor','header' => 'Accesor', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								['attribute'=>'quita','header' => 'Quita', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								['attribute'=>'multa','header' => 'Multa', 'contentOptions'=>['style'=>'text-align:right','width'=>'50px']],
								['attribute'=>'total','header' => 'Total', 'contentOptions'=>['style'=>'text-align:right','width'=>'50px']],							
				        	],
					]);
					
			Pjax::end(); 
			
	?>	
		
<!-- FIN Grilla -->

<?php

	ActiveForm::end();
?>

</div>
</div>
