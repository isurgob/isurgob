<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Alert;

/**
 * Forma que se dibuja cuando se llega a apremio desde Pago
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

?>
<style>
#ModalEtapaMas .modal-content{
	width:350px !important;
}

.form-panel {
	padding-bottom: 8px;
}
</style>

<div class="apremio-info">
<div class="form-panel" style="padding-right:8px">
<h3><strong>Datos del Apremio Judicial</strong></h3>
<table>
	<tr>
		<td width="60px"><label>Judi Nº</label></td>
		<td width="60px"><?= Html::input('text','txJudiID',$model->judi_id,['id'=>'formApremio-txJudiID','class'=>'form-control','style'=>'width:60px;text-align:center','readOnly'=>true]) ?></td>
		<td width="30px"></td>
		<td width="70px"><label>Expediente</label></td>
		<td width="60px"><?= Html::input('text','txExpe',$model->expe,['id'=>'formApremio-txExpe','class'=>'form-control','style'=>'width:165px','readOnly'=>true]) ?></td>
		<td width="10px"></td>
		<td><label>Objeto</label></td>
		<td><?= Html::input('text','txObjetoID',$model->obj_id,['id'=>'formApremio-txObjetoID','class'=>'form-control','style'=>'width:95px','readOnly'=>true]); ?></td>
	</tr>
	<tr>
		<td width="60px"><label>Carátula</label></td>
		<td colspan="4"><?= Html::input('text','txCaratula',$model->caratula,['id'=>'apremio-txCaratula','class'=>'form-control','style'=>'width:100%;text-align:left','readOnly'=>true]) ?></td>
		<td width="10px"></td>		
		<td width="30px"><label>Estado:</label></td>
		<td colspan="2"><?= Html::input('text','txEstNom',$model->est_nom,['id'=>'apremio-txEstNom','class'=>'form-control','style'=>'width:100%;text-align:center','readOnly'=>true]) ?></td>	
	</tr>
</table>		

<table>
	<tr>
		<td width="50px"><label>Alta:</label></td>
		<td width="80px"><?=  DatePicker::widget([	'id' => 'apremio-fchalta',
													'name' => 'fchalta',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => [
														'class' => 'form-control',
														'style' => 'width:70px',
														'readOnly'=>true,
														'disabled' => true,
													],
													'value' => ($model->fchalta != '' ? Fecha::usuarioToDatePicker($model->fchalta) : '')
												]);	?>
		</td>
		<td width="25px"></td>
		<td width="65px"><label>Apremio:</label></td>
		<td width="80px"><?=  DatePicker::widget([	'id' => 'apremio-fchapremio',
													'name' => 'fchapremio',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => [
														'class' => 'form-control',
														'style' => 'width:70px',
														'readOnly'=>true,
														'disabled' => true,
													],
													'value' => ($model->fchapremio != '' ? Fecha::usuarioToDatePicker($model->fchapremio) : '')
												]);	?>
		</td>
		<td width="25px"></td>		
		<td width="75px"><label>Procurador:</label></td>
		<td width="80px"><?=  DatePicker::widget(['id' => 	'apremio-fchprocurador',
															'name' => 'fchprocurador',
															'dateFormat' => 'dd/MM/yyyy',
															'options' => [
																'class' => 'form-control',
																'style' => 'width:70px',
																'readOnly'=>true,
																'disabled' => true,
															],
															'value' => ($model->fchprocurador != '' ? Fecha::usuarioToDatePicker($model->fchprocurador) : '')
												]);	?>
		</td>
		<td width="25px"></td>
		<td width="65px"><label>Juicio:</label></td>
		<td width="80px"><?=  DatePicker::widget([	'id' => 'apremio-fchjuicio',
													'name' => 'fchjuicio',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => [
														'class' => 'form-control',
														'style' => 'width:70px',
														'readOnly'=>true,
														'disabled' => true,
													],
													'value' => ($model->fchjuicio != '' ? Fecha::usuarioToDatePicker($model->fchjuicio) : '')
												]);	?>
		</td>
		<td width="25px"></td>		
		<td width="50px"><label>Baja:</label></td>
		<td width="80px"><?=  DatePicker::widget(['id' => 	'apremio-fchbaja',
															'name' => 'fchbaja',
															'dateFormat' => 'dd/MM/yyyy',
															'options' => [
																'class' => 'form-control',
																'style' => 'width:70px',
																'readOnly'=>true,
																'disabled' => true,
															],
															'value' => ($model->fchbaja != '' ? Fecha::usuarioToDatePicker($model->fchbaja) : '')
												]);	?>
		</td>		
	</tr>
</table>

<table width="100%">
	<tr>
		<td width="115px"><label>Devolución-Fecha:</label></td>
		<td width="80px"><?=  DatePicker::widget(['id' => 	'apremio-fchdev',
															'name' => 'fchdev',
															'dateFormat' => 'dd/MM/yyyy',
															'options' => [
																'class' => 'form-control',
																'style' => 'width:70px',
																'readOnly'=>true,
																'disabled' => true,
															],
															'value' => ($model->fchdev != '' ? Fecha::usuarioToDatePicker($model->fchdev) : '')
												]);	?>
		</td>	
		<td width="25px"></td>
		<td width="50px" align="right"><label>Motivo:</label></td>
		<td align="right"><?= Html::input('text','txMotivo_dev_nom',$model->motivo_dev_nom,['id'=>'apremio-txMotivo_dev_nom','class'=>'form-control','style'=>'width:350px','readOnly'=>true]) ?></td>
	</tr>
</table>

<table width="100%">
	<tr>
		<td width="90px"><label>Procurador:</label></td>
		<td colspan="4"><?= Html::input('text','txProcurador_nom',$model->procurador_nom,['id'=>'apremio-txProcurador_nom','class'=>'form-control','style'=>'width:100%','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td  width="60px"><label>Juzgado:</label></td>
		<td><?= Html::input('text','txJuzgado',$model->juzgado_nom,['id'=>'apremio-txJuzgado','class'=>'form-control','style'=>'width:200px','readOnly'=>true]) ?></td>
	</tr>
	
	<tr>
		<td width="90px"><label>Honorarios:</label></td>
		<td><?= Html::input('text','txHonorarios',$model->hono_jud,['id'=>'apremio-txHonorarios','class'=>'form-control','style'=>'width:80px;text-align:right','readOnly'=>true]) ?></td>
		<td width="30px"></td>
		<td  width="60px"><label>Gastos:</label></td>
		<td><?= Html::input('text','txGastos',$model->gasto_jud,['id'=>'apremio-txGastos','class'=>'form-control','style'=>'width:80px;text-align:right','readOnly'=>true]) ?></td>
		<td width="30px"></td>
		<td  width="60px"><label>Plan:</label></td>
		<td><?= Html::input('text','txPlan',$model->plan_id,['id'=>'apremio-txPlan','class'=>'form-control','style'=>'width:80px','readOnly'=>true]) ?></td>
	</tr>
</table>
</div>


<div class="form-panel" style="padding-right:8px">
<h3><strong>Detalle de Deuda</strong></h3>
<table>
	<tr>
		<td width="60px"><label>Desde:</label>
		<?= Html::input('text','txDesde',$model->perdesde,['id'=>'apremio-txDesde','class'=>'form-control','style'=>'width:100%;text-align:center','readOnly'=>true]) ?></td>
		<td width="5px"></td>
		<td width="60px"><label>Hasta:</label>
		<?= Html::input('text','txHasta',$model->perhasta,['id'=>'apremio-txHasta','class'=>'form-control','style'=>'width:100%;text-align:center','readOnly'=>true]) ?></td>
		<td width="30px"></td>
		<td width="75px"><label>Nominal:</label>
		<?= Html::input('text','txNominal',$model->nominal,['id'=>'apremio-txNominal','class'=>'form-control','style'=>'width:100%;text-align:right','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="75px"><label>Accesorios:</label>
		<?= Html::input('text','txAccesorios',$model->accesor,['id'=>'apremio-txAccesorios','class'=>'form-control','style'=>'width:100%;text-align:right','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="75px"><label>Multa:</label>
		<?= Html::input('text','txMulta',$model->multa,['id'=>'apremio-txMulta','class'=>'form-control','style'=>'width:100%;text-align:right','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="75px"><label>Multa Omis.:</label>
		<?= Html::input('text','txMultaOmis',$model->multa_omi,['id'=>'apremio-txMultaOmis','class'=>'form-control','style'=>'width:100%;text-align:right','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="75px"><label>Total:</label>
		<?= Html::input('text','txTotal',$model->monto,['id'=>'apremio-txTotal','class'=>'form-control','style'=>'width:100%;text-align:right','readOnly'=>true]) ?></td>
	</tr>
</table>
</div>

<div class="form-panel" style="padding-right:8px">
<h3><strong>Observación</strong></h3>
<table width="99%">
	<tr>
		<td><?= Html::textarea('txObs',$model->obs,['id'=>'facilida-txObs','class'=>'form-control','style'=>'width:620px;height:60px;max-width:620px;max-height:120px','readOnly'=>true]) ?></td>
	</tr>
</table>
</div>

<div class="form-panel" style="padding-right:8px">
<h3><strong>Etapas</strong></h3>

<!-- INICIO Grilla -->
<div class="div_grilla">
	<?php
			Pjax::begin();
				echo GridView::widget([
						'id' => 'GrillaInfoApremioEtapas',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dataProviderEtapa,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
								['attribute'=>'fecha','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'etapa_nom','header' => 'Etapa', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
								['attribute'=>'detalle','header' => 'Detalle', 'contentOptions'=>['style'=>'text-align:left','width'=>'250px']],
								['attribute'=>'hono_jud','header' => 'Honorarios', 'contentOptions'=>['style'=>'text-align:right','width'=>'70px']],
								['attribute'=>'gasto_jud','header' => 'Gastos', 'contentOptions'=>['style'=>'text-align:right','width'=>'70px']],
								['attribute'=>'modif','header' => 'Modificación', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],						
				        	],
					]);
					
			Pjax::end(); 
	?>	
</div>		
<!-- FIN Grilla -->

</div>

<div class="form-panel" style="padding-right:8px">
<h3><strong>Períodos</strong></h3>

<!-- INICIO Grilla -->
<div class="div_grilla">
	<?php
			Pjax::begin();
				echo GridView::widget([
						'id' => 'GrillaInfoApremioPeriodos',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dataProviderPeriodo,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
								['attribute'=>'trib_nom','header' => 'Trib', 'contentOptions'=>['style'=>'text-align:center','width'=>'120px']],
								['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'ctacte_id','header' => 'Cta.', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'estant','header' => 'Est.', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'nominal','header' => 'Nominal', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								['attribute'=>'accesor','header' => 'Accesor', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								['attribute'=>'multa','header' => 'Multa', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								['attribute'=>'total','header' => 'Total', 'contentOptions'=>['style'=>'text-align:right','width'=>'5px']],							
				        	],
					]);
					
			Pjax::end(); 
	?>	
</div>		
<!-- FIN Grilla -->


</div>
</div>


<?php

	//INICIO Modal Modificar Obs Apremio
	Modal::begin([
			'id' => 'ModalModificarObsApremio',
			'size' => 'modal-sm',
			'header' => '<h4><b>Modificar Obs. Apremio</b></h4>',
			'closeButton' => [
				'label' => '<b>X</b>',
    			'class' => 'btn btn-danger btn-sm pull-right',
    			'id' => 'btCancelarModalElim'
				],
		]);
		
		

		$form = ActiveForm::begin([
			'id'=>'formApremio',
			'action'=>['updateobs'],
			]);
		
		?>
			
		<?= Html::input('hidden','txJudiIDObs',$model->judi_id); ?>
									
		<div class="text-center">
			
			<?= Html::textarea('txObs',$model->obs,['id'=>'apremio-txObs','class'=>'form-control','style'=>'width:254px;height:60px;max-width:254px;max-height:120px']); ?>
			
			<?= Html::Button('Grabar', ['class' => 'btn btn-success','onclick'=>'aceptarEditar()']); ?>
			
			&nbsp;&nbsp;
			
			<?= Html::Button('Cancelar', ['id'=>'btModificarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalModificarObsApremio").modal("hide")']); ?>
			
		</div>
		
		<div id="apremio_editar_errorSummary" class="error-summary text-center" style="display:none;margin-top: 8px;0">
			
			<ul>
			</ul>
			
		</div>
		
		<script>
		function aceptarEditar()
		{
			var obs = $("#apremio-txObs").val(),
				error = new Array();
			
			if ( obs == '' )
				error.push( "Ingrese una observación." );
			
			if ( error.length == 0 )
				$("#formApremio").submit();
			else
				mostrarErrores( error, "#apremio_editar_errorSummary" );
				
		}
			
			
		</script>
		<?php
 		
 		ActiveForm::end();
 		
 	Modal::end();
	//FIN Modal Modificar Obs Apremio
	
	
	$etapa = 0;
	//INICIO Modal Etapa +
	Modal::begin([
			'id' => 'ModalEtapaMas',
			'size' => 'modal-sm',
			'header' => '<h4><b>Agregar Etapa</b></h4>',
			'closeButton' => [
				'label' => '<b>X</b>',
    			'class' => 'btn btn-danger btn-sm pull-right',
    			'id' => 'btCancelarModalElim'
				],
		]);
		
		$form = ActiveForm::begin([
			'id'=>'formEtapaMas',
			'action'=>['etapamas'],
			]);
		
		//Paso el ID
		echo Html::input('hidden','txJudiIDEtapaMas',$model->judi_id);
		?>
		<table>
			<tr>
				<td width="70px"><label>Etapa</label></td>
				<td><?= Html::dropDownList('dlEtapa',$etapa,utb::getAux('judi_tetapa','cod','nombre', 0,"position('".$model->est."' in est_ini)>0"),
											[	'id' => 'formApremio-dlEtapa', 
												'class' =>'form-control',
												'style' => 'width:250px;',
												'onchange' => '$.pjax.reload({container:"#formModalEtapaMas",method:"POST",data:{etapa:$(this).val()}})',
												]);?></td>
			<tr>
			</tr>
				<td width="70px"><label>Fecha</label></td>
				<td><?=  DatePicker::widget(['id' => 	'formApremio-fch',
															'name' => 'fch',
															'dateFormat' => 'dd/MM/yyyy',
															'options' => [
																'class' => 'form-control',
																'style' => 'width:70px',
																'readOnly'=>true,
															],
															'value' => Fecha::usuarioToDatePicker($dia),
												]);	?></td>
			<tr>
		</table>
		
		<?php
		
			echo '<script>$(document).ready(function(){$.pjax.reload({container:"#formModalEtapaMas",method:"POST",data:{etapa:$("#formApremio-dlEtapa").val()}});})</script>';
			
			Pjax::begin(['id'=>'formModalEtapaMas']);
					
			//Obtengo el valor para etapa
			$etapa = Yii::$app->request->post('etapa',0);
			
			//Obtengo de la BD, según etapa, si se deben habilitar o deshabilitar los parámetros
			$arreglo = $model->getParametrosEtapa($etapa);

		?>
				
		<table>
			</tr>
				<td width="70px"><label>Proc.</label></td>
				<td>
					<?= Html::dropDownList('dlProcurador',null,utb::getAux('sam.sis_usuario','usr_id','apenom', 0, 'abogado=1'),[
						'id' => 'formApremio-dlProcurador',
						'class' =>'form-control',
						'style' => 'width:250px;','disabled'=>($arreglo['procurador'] == true ? false : true )]);?></td>
			<tr>
			</tr>
				<td width="70px"><label>Juz.</label></td>
				<td><?= Html::dropDownList('dlJuzgado',null,utb::getAux('judi_juzgado','cod','nombre', 0),['id' => 'formApremio-dlJuzgado', 'class' =>'form-control', 'style' => 'width:250px;','disabled'=>($arreglo['procurador'] == true ? false : true )]);?></td>
			<tr>
			</tr>
				<td width="70px"><label>Motivo</label></td>
				<td><?= Html::dropDownList('dlMotivo',null,utb::getAux('judi_tdev','cod','nombre', 0),['id' => 'formApremio-dlMotivo', 'class' =>'form-control', 'style' => 'width:250px;','disabled'=>($arreglo['motivo'] == true ? false : true )]);?></td>
			<tr>
			</tr>
				<td width="70px"><label>Gastos</label></td>
				<td><?= Html::input('text','txGastos',null,['id'=>'apremio-txGastos','class'=>'form-control','style'=>'width:80px;text-align:right','onkeypress'=>'return justDecimal($(this).val(),event)','disabled'=>($arreglo['honorarios'] == true ? false : true )]) ?></td>
			<tr>
			</tr>
				<td width="70px"><label>Honorarios</label></td>
				<td><?= Html::input('text','txHonorarios',null,['id'=>'apremio-txHonorarios','class'=>'form-control','style'=>'width:80px;text-align:right','onkeypress'=>'return justDecimal($(this).val(),event)','disabled'=>($arreglo['honorarios'] == true ? false : true )]) ?></td>
			</tr>
			<tr>
				<td width="70px"><label>Detalle</label></td>
				<td><?= Html::textarea('txDetalle',null,['id'=>'apremio-txDetalle','class'=>'form-control','style'=>'width:250px;height:90px;max-width:250px;max-height:120px;max-length:250;text-align:left']) ?></td>
			</tr>
		</table>
		<table width="100%">
			<tr>
				<td><center>
					<?= Html::Button('Grabar', ['class' => 'btn btn-success','onclick'=>'btGrabarEtapaMas()']); ?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?= Html::Button('Cancelar', ['id'=>'btModificarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalEtapaMas").modal("hide")']); ?>
				</center></td>
			</tr>
		</table>
		
		<?php							
	
		Pjax::end(); 
		
		?>
		
		<div id="apremio_etapaMas_errorSummary" class="error-summary" style="display:none; margin-top: 8px; margin-right: 15">
			
			<ul>
			</ul>
		
		</div>
		
		<?php
	ActiveForm::end();
		
 	Modal::end();
	//FIN Modal Etapa +
	
	
	
	//INICIO Modal Etapa -
	Modal::begin([
			'id' => 'ModalEtapaMenos',
			'size' => 'modal-sm',
			'header' => '<h4><b>Eliminar Etapa</b></h4>',
			'closeButton' => [
				'label' => '<b>X</b>',
    			'class' => 'btn btn-danger btn-sm pull-right',
    			'id' => 'btCancelarModalElim'
				],
		]);
									
		$form = ActiveForm::begin([
			'id'=>'formEtapaMenos',
			'action'=>['etapamenos'],
			]);
			
		//Paso el ID
		echo Html::input('hidden','txJudiIDEtapaMenos',$model->judi_id);
		
		?>
		
		<table>
			<tr>
				<td><center><label>¿Está seguro que desea eliminar la última etapa?</label></center></td>
			<tr>
		</table>
		<br />
		<table width="100%">
			<tr>
				<td><center>
					<?= Html::Button('Grabar', ['class' => 'btn btn-success','onclick'=>'$("#formEtapaMenos").submit()']); ?>
					&nbsp;&nbsp;
					<?= Html::Button('Cancelar', ['id'=>'btModificarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalEtapaMenos").modal("hide")']); ?>
				</center></td>
			</tr>
		</table>
		
		<?php
 		
	ActiveForm::end();
 		
 	Modal::end();
	//FIN Modal Etapa -
	
?>

<script>

function btGrabarEtapaMas()
{
	var etapa = $("#formApremio-dlEtapa").val(),
		fecha = $("#formApremio-fch").val(),
		error = new Array();
	
	if ( etapa == '' || etapa == 0 || etapa == null)
		error.push( "Ingrese una etapa." );
		
	if ( fecha == '' )
		error.push( "Ingrese una fecha." );
	
	if ( fecha.length < 10 )
		error.push( "Ingrese una fecha válida." );
		
	if ( error.length == 0 )
		$("#formEtapaMas").submit();
	else
		mostrarErrores( error, "#apremio_etapaMas_errorSummary" );
}
</script>
