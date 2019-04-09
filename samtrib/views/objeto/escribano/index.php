<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\jui\DatePicker;
use yii\bootstrap\Modal;
use yii\helpers\BaseUrl;

?>
<div class="form-cuerpo">

    <h1 id='h1titulo'>Operaciones de Venta</h1>
	
	<?php 
		if( isset(Yii::$app->session['mensaje']) )
		{
		
			Alert::begin([
				'id' => 'MensajeEscrib',
				'options' => [
				'class' => 'alert-success',
				],
			]);	
	
				echo Yii::$app->session->getFlash('mensaje');
			
			Alert::end();
			
			echo "<script>window.setTimeout(function() { $('#MensajeEscrib').alert('close'); }, 5000)</script>"; 
		
		}
	
	?>
	
    <table border='0' width="100%">
		<tr>
			<td valign="top">    		
				<div class="form" style='padding:10px; margin:5px 0px;width:100%'>
					<table border='0' width="100%">
						<tr>
							<td width='5%'> <label> Estado: </label> </td>
							<td> 
								<?= Html::dropDownList('dlEstado', $model->est, $estados, 
									['id' => 'dlEstado', 'class' => 'form-control', 'style' => 'width:97%']); 
								?>
							</td>
							<td width='5%'> <label> Fecha: </label> </td>
							<td colspan='3' > 
								<?= DatePicker::widget([
										'name' => 'fechaDesde', 
										'value' => $model->fecha_desde,
										'dateFormat' => 'php:d/m/Y', 
										'options' => [
											'id' => 'fechaDesde', 
											'class' => 'form-control', 
											'maxlength' => 10, 
											'style' => 'width:20%;'
										]
									]); 
								?>
								<label> hasta: </label>
								<?= DatePicker::widget([
										'name' => 'fechaHasta', 
										'value' => $model->fecha_hasta,
										'dateFormat' => 'php:d/m/Y', 
										'options' => [
											'id' => 'fechaHasta', 
											'class' => 'form-control', 
											'maxlength' => 10, 
											'style' => 'width:20%;'
										]
									]); 
								?>
							</td>	
						</tr>
						<tr>
							<td> <label> Escribano: </label> </td>
							<td> 
								<?= Html::dropDownList('dlEscribano', $model->escribano, $escribanos, 
									['id' => 'dlEscribano', 'class' => 'form-control', 'style' => 'width:97%']); 
								?>
							</td>
							<td> <label> Inmueble: </label> </td>
							<td width='12%'>
								<?= Html::input('text', 'codigoObjeto', $model->obj_id, [
										'class' => 'form-control', 
										'id' => 'codigoObjeto', 
										'style' => 'width:95%', 'maxlength'=>'8', 
										'onchange'=>'cambiaObjeto()'
									]); 
								?>
							</td>
							<td width='1%'>
								<!-- boton de b�squeda modal -->
								<?php
								Modal::begin([
									'id' => 'modalBusquedaAvanzadaObjeto',
									'header' => '<h2>Búsqueda de Objeto</h2>',
									'toggleButton' => [
										'label' => '<i class="glyphicon glyphicon-search"></i>',
										'class' => 'bt-buscar'
									],
									'closeButton' => [
										'label' => '<b>&times;</b>',
										'class' => 'btn btn-danger btn-sm pull-right',
									],
									'size' => 'modal-lg',
								]);
								
								echo $this->render('//objeto/objetobuscarav',[
										'id' => 'esc', 
										'txCod' => 'codigoObjeto', 
										'txNom' => 'nombreObjeto', 
										'tobjeto' => 1, 
										'selectorModal' => '#modalBusquedaAvanzadaObjeto'
									]);
																
								Modal::end();
								?>
							</td>
							<td>
								<?= Html::input('text', 'nombreObjeto', $model->inm_nom,[
										'class' => 'form-control solo-lectura',
										'id'=>'nombreObjeto',
										'style'=>'width:100%',
										'disabled'=>'true'
									]); 
								?>
							</td>
						</tr>
						<tr>
							<td colspan='6'> <hr style='margin:10px 0px'> </td>
						</tr>	
						<tr>
							<td colspan='6'> 
								<?= Html::button('<b>Buscar</b>', ['class' => 'btn btn-primary', 'onclick' => 'Buscar()']) ?>
								<?= Html::a('<b>Imprimir</b>', null, ['class' => 'btn btn-success','target' => '_black', 'onclick' => 'Imprimir()']) ?>
							</td>
						</tr>	
					</table>
				</div>
			</td>
		</tr>
		
		<tr>
			<td>
				<?php 
					Pjax::begin([ 'id' => 'PjaxGrilla', 'enableReplaceState' => false, 'enablePushState' => false ]); 
					
					if (isset(Yii::$app->session['error_cons']))
						echo "<div class='error-summary'>" . Yii::$app->session->getFlash('error_cons') . "</div>";
				?>
					<?= GridView::widget([
							'id' => 'GrillaVentas',
							'headerRowOptions' => ['class' => 'grilla'],
							'rowOptions' => ['class' => 'grilla'],
							'dataProvider' => $dataProviderVentas,
							'summaryOptions' => ['class' => 'hidden'],
							'columns' => [
									['attribute'=>'vta_id','header' => 'Venta', 'contentOptions'=>['style'=>'text-align:center','width'=>'1%']],
									['attribute'=>'fecha','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center','width'=>'1%']],
									['attribute'=>'escribano','header' => 'Escribano', 'contentOptions'=>['style'=>'text-align:center','width'=>'1%']],
									['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'1%']],
									['attribute'=>'nc','header' => 'NC', 'contentOptions'=>['style'=>'text-align:center','width'=>'20%']],
									['attribute'=>'domicilio','header' => 'Domicilio', 'contentOptions'=>['width'=>'35%']],
									['attribute'=>'est_nom','header' => 'Estado', 'contentOptions'=>['width'=>'15%']],
									
									[
										'class' => 'yii\grid\ActionColumn',
										'contentOptions'=>['style'=>'width:8%;'],
										'template' => '{view} {imprimir} {informar} ',
										'buttons' => [

											'view' =>   function ( $url, $model, $key )
														{
															return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
																['venta', 'id' => $model['vta_id']], 
																[
																	'class'=>'bt-buscar-label',
																	'style'=>'color:#337ab7'
																]
															);
														},

											'imprimir' => function( $url, $model, $key )
														{
															return Html::a('<span class="glyphicon glyphicon-print"></span>', 
																['imprimirventa', 'id' => $model['vta_id']], 
																[
																	'class'=>'bt-buscar-label',
																	'style'=>'color:#337ab7',
																	'target' => '_black',
																	'data-pjax' => "0"
																]
															);
														},

											'informar' => function( $url, $model, $key )
														{
															if ( $model['est'] == 'P' )
																return Html::a('<span class="glyphicon glyphicon-info-sign"></span>', 
																	['informar', 'id' => $model['vta_id']], 
																	[
																		'class'=>'bt-buscar-label',
																		'style'=>'color:#337ab7'
																	]
																);
														},
														
											'aprobar' => function( $url, $model, $key )
														{
															if ( $model['est'] == 'P' )
																return Html::a('<span class="glyphicon glyphicon-ok"></span>', 
																	['edicion', 'id' => $model['vta_id'], 'action' => 4], 
																	[
																		'class'=>'bt-buscar-label',
																		'style'=>'color:#337ab7'
																	]
																);
														},			
										]
									]
								],
						]);
					?>
				<?php Pjax::end(); ?>
			</td>
		</tr>	
	</table>
</div>

<script>

function Buscar(){

	$.pjax.reload({
        container: '#PjaxGrilla',
        type: 'GET',
        replace: false,
        push: false,
        data : {
            "estado": $("#dlEstado").val(),
			"fecha_desde": $("#fechaDesde").val(),
			"fecha_hasta": $("#fechaHasta").val(),
			"objeto": $("#codigoObjeto").val(),
			"escribano": $("#dlEscribano").val(),
        },
    });

}

function cambiaObjeto(){
	
	$.post( "<?= BaseUrl::toRoute('completarobjeto');?>", { 
		obj_id: $("#codigoObjeto").val()
	} )
	.done(function( data ) {
		var response = jQuery.parseJSON(data);
		$("#codigoObjeto").val(response.obj_id);
		$("#nombreObjeto").val(response.nombre);
	});

}

function Imprimir(){
	
	url = "<?= BaseUrl::toRoute('imprimir') ?>&e="  + $("#dlEstado").val() 
													+ "&fd=" + $("#fechaDesde").val() 
													+ "&fh=" + $("#fechaHasta").val() 
													+ "&o=" + $("#codigoObjeto").val()
													+ "&es=" + $("#dlEscribano").val();
	window.open(url,'_blank');

}

</script>