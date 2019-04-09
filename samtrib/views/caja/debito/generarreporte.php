<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use app\controllers\ExportarController;
use yii\bootstrap\Modal;
use \yii\widgets\Pjax;

$title = 'Generar Reportes Débitos';
$this->params['breadcrumbs'][] = $title;

?>
<div class="reporte-view">
	
	<h1><?= Html::encode($title) ?></h1>
	
	<hr>
	
	<?php $form = ActiveForm::begin([ 'id' => 'form_generar_reporte', /*'action' => BaseUrl::toRoute('generarreporteimprimir'), */ 'options' => [ 'target' => '_blank' ] ]); ?>
	
		<table width="100%">
			<tr>
				<td valign='top'>
					<div class='form' style='padding:5px;margin-right:10px;'>
						<label>Caja:</label>
						<?= Html::dropDownList('dlCaja', $caja, $arrayCaja,[
								'id'=>'dlCaja',
								'onchange' => 'f_selectCaja()',
								'class'=>'form-control',
								'style' => 'width:80%'
							]);
						?>
						<br>
						<label>Año:</label>
						<?= Html::input( 'text', 'txAnio', $anio, [
								'id' => 'txAnio',
								'class' => 'form-control',
								'style' => 'width:30%; text-align:center;',
								'onkeypress' => 'return justNumbers(event)',
								'maxlenght' => 4
							]);
						?>
					
						<label>Mes:</label>
						<?= Html::input( 'text', 'txMes', $mes, [
								'id' => 'txMes',
								'class' => 'form-control',
								'style' => 'width:30%;text-align:center;',
								'onkeypress' => 'return justNumbers(event)',
								'maxlenght' => 3
							]);
						?>
						<hr>
						<?= Html::button( 'Imprimir', [
								   'class' => 'btn btn-success',
								   'onclick'	=> 'f_Generar( 1 )',
							   ]);
						?>
						
						<?= Html::button( 'Exportar', [
								   'class' => 'btn btn-success',
								   'onclick'	=> 'f_Generar( 0 )',
							   ]);
						?>
						
					</div>
					
					<?= Html::errorSummary( $model, [
							'id' => 'errorSummary',
							'style'	=> 'margin-top: 8px;',
							'class' => "error-summary"
						]);
					?>
				</td>	
				<td>
					<!-- INICIO Grilla -->
					<?php
						echo GridView::widget([
								'id'	=> 'grillaCampo',
								'headerRowOptions' => ['class' => 'grilla cabecera'],
								'summaryOptions' => [ 'class' => 'hidden'],
								'rowOptions' => ['class' => 'grilla'],
								'dataProvider' => $dpCampos,
								'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
								'columns' =>  [
									['class' => '\yii\grid\CheckboxColumn', 'contentOptions' => ['style' => 'width:1%'], 'checkboxOptions' => ['class' => 'simple']],
									['attribute' => 'nombre', 'label' => 'Campo'],
									[

										'content'=> function($model, $key, $index, $column){

											return
												Html::input( 'text', 'txOrden' . $model['id'], $model['orden'], [
													'id'    => 'txOrden' . $model['id'],
													'class' => 'form-control',
													'style' => 'text-align:center;width:100%',
													'maxlength'     => '2',
													'onkeypress' => 'return justNumbers(event)'
												]);
										},

										'contentOptions' => [ 'style' => 'text-align:center; width:20%'],

										'label' => 'Orden',
									]
								]
							]);
					?>
					<!-- FIN Grilla -->
				</td>
			</tr>
		</table>	
	
	<?php ActiveForm::end(); ?>
	
</div>

<?php 
	Pjax::begin([ 'id' => 'pxExportar' ]);
		
		Modal::begin([
			'id' => 'modalExportar',
			'header'=>'<h2>Exportar Listado</h2>',
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			]
		]);
			
			echo ExportarController::exportar( 'Listado Débito', $descripcion, 'exportar', "{caja: $caja, anio: $anio, mes: $mes, seleccionado: '$seleccionado'}" );

		Modal::end();						

	Pjax::end()	;
?>

<script>

function f_Generar( imprimir ){

	var seleccionado = new Array();
	var keys = $('#grillaCampo').yiiGridView('getSelectedRows');
	var error = new Array();
	
	ocultarErrores( $("#errorSummary") );
	
	for ( var i=0; i < keys.length; i++ ){
		seleccionado.push({ "orden" : $("#txOrden" + keys[i]).val(), "campo" : keys[i] });
	}
	
	if (isNaN( $("#dlCaja").val() ) || $("#dlCaja").val() == 0 ) 
		error.push('Seleccione una Caja.');
		
	if (isNaN( $("#txAnio").val() ) || $("#txAnio").val() == 0 ) 
		error.push('Ingrese un Año.');	
		
	if (isNaN( $("#txMes").val() ) || $("#txMes").val() == 0 ) 
		error.push('Ingrese un Mes.');		
	
	if ( seleccionado.length == 0 )
		error.push('Seleccione al menos un campo a listar.');		
	
	if ( error.length == 0 )
		if ( imprimir == 1 )
			$("#form_generar_reporte").submit();
		else{
			
			$.pjax.reload({
	            container   : "#pxExportar",
	            type        : "POST",
	            replace     : false,
	            push        : false,
	            timeout     : 100000,
	            data:{
	                "dlCaja" : $("#dlCaja").val(),
	                "txAnio" : $("#txAnio").val(),
	                "txMes" : $("#txMes").val(),
	                "selection" : seleccionado
	            }
	        });

	        $( "#pxExportar" ).on( "pjax:end", function() {

			    $("#modalExportar").modal("show");

			});

		}
	else
		mostrarErrores( error, $("#errorSummary") );
	
}

</script>