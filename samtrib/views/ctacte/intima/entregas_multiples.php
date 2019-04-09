<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

$this->params['breadcrumbs'][] = ['label' => 'Gestión de Incumplimiento', 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Entregas múltiples';

?>

<script type="text/javascript">
//carga los datos en la tabla
function cargarDatos(){
	
	var lote_id = $("#loteId").val();
	var sinResultados = $("#sinResultados").is(":checked"); 
	
	$.pjax.reload({
		container : "#pjaxGrilla",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"lote_id" : lote_id,
			"sinResultadosAnteriores" : sinResultados
		}
	});
}

//le coloca o quita la selecciona a todos los checkbox de la tabla
function seleccionarTodos(){
	$("#grilla .check").prop("checked", $("#checkTodos").is(":checked"));
}
</script>

<div class="entregasMultiples">
	<h1>Entregas m&uacute;ltiples</h1>



	<!-- modal para grabar los datos -->

	<?php 
	Modal::begin([
				'id' => 'modalGrabar',
				'header' => '<h1>Grabar entregas</h1>',
				'toggleButton' => false,
		        'closeButton' => [
		            		'label' => '<b>X</b>',
		                	'class' => 'btn btn-danger btn-sm pull-right',
		            	]
				]); 
	
	echo $this->render('_entregas_modal', 
						[
						'extras' => $extras,
						'selectorModal' => '#modalGrabar', 
						'selectorMensajeExito' => '#alertEntregas', 
						'selectorChecks' => '#grilla .check:checked', 
						'selectorLote' => '#idLote'
						]
					);
	
	Modal::end();
	?>

	<!-- fin de modal para grabar los datos -->



	<div class="form" style="padding:15px;">
		<table border="0" width="100%">
			<tr>
				<td width="100px">
					<label>Lote: </label>
					<?= Html::textInput(null, Yii::$app->request->get('lote_id', null), ['id' => 'loteId', 'class' => 'form-control', 'style' => 'width:40px;']) ?>
				</td>
				
				<td width="240px">
					<?= Html::checkbox(null, Yii::$app->request->get('sinResultadosAnteriores', true), ['id' => 'sinResultados', 'label' => 'Sólo sin Resultados anteriores']); ?>
				</td>
				
				<td width="70px">
					<?= Html::button('Cargar', ['class' => 'btn btn-primary', 'onclick' => 'cargarDatos();']) ?>
				</td>
				
				<td width="70px">
					<?= Html::button('Grabar', ['class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-target' => '#modalGrabar',]) ?>
				</td>
				
				<td width="270px"></td>
			</tr>
		</table>
		
		<table border="0" width="100%">
			<tr>
				<td colspan="5">
					<?php
					
					Pjax::begin(['id' => 'PjaxErrorEntregasMultiples']);
					
					$mensaje = Yii::$app->request->get('mensajeError',$mensaje);
									
					$clase = (isset($mensaje) && !empty($mensaje)) ? 'alert-danger hidden' : 'alert-success hidden';
											
					Alert::begin([
						'options' => [
						'id' => 'alertEntregas',
						'class' => $clase, 
	    				],
					]);
	
					echo (isset($mensaje) && !empty($mensaje)) ? $mensaje : 'Datos grabados correctamente.';
					
					Alert::end();
				
					if(isset($mensaje) && !empty($mensaje))
					{
						?>
						<script type="text/javascript">
						$("#alertEntregas").removeClass("hidden");
						
						setTimeout(function(){$("#alertEntregas").addClass("hidden")}, 5000);
						</script>
						<?php
					}
					
					Pjax::end();
					?>
				</td>
			</tr>
		</table>
			
		<?php
		Pjax::begin(['id' => 'pjaxGrilla', 'enableReplaceState' => false, 'enablePushState' => false]);
		?>
		
		<table border="0" width="100%" style="margin-top:20px;">
			<tr>
				<td colspan="5">
					<?php
							
					echo Html::input('hidden', null, Yii::$app->request->get('lote_id', 0), ['id' => 'idLote']);
					
					if ($mensaje != '')
					{
						echo '<script>$.pjax.reload({container:"#PjaxErrorEntregasMultiples",method:"GET",replace:false,push:false,data:{mensajeError:"'.$mensaje.'"}});</script>';
					}
					
					echo GridView::widget([
						'dataProvider' => $extras['dpEntregasMultiples'],
						'id' => 'grilla',
						'headerRowOptions' => ['class' => 'grillaGrande'],
						'rowOptions' => ['class' => 'grillaGrande'],
						'columns' => [
						
							//columna con el checkbox y el obj_id
							['attribute' => 'obj_id', 
							'label' => '',
							'content' => function($model, $key, $index, $column){
								
								return Html::checkbox(null, false, ['value' => $key, 'label' => $model['obj_id'], 'class' => 'check']);
							},
							'header' => Html::checkbox(null, false, ['label' => 'Objeto', 'onclick' => 'seleccionarTodos()', 'id' => 'checkTodos']),
							'contentOptions' => ['style' => 'width:100px;']
							],
							['attribute' => 'obj_nom', 'label' => 'Nombre', 'contentOptions' => ['style' => 'width:320px']],
							['attribute' => 'dompos_dir', 'label' => 'Domicilio', 'contentOptions' => ['style' => 'width:430px;']]
						]
					]);
					
					?>
				</td>
			</tr>
		</table>
		<?php
		Pjax::end();
		?>
	</div>
</div>