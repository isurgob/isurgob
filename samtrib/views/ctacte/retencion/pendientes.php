<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\jui\DatePicker;
use app\controllers\ctacte\RetencionController;
use yii\bootstrap\Modal;

$title= 'Retenciones Pendientes';

$this->params['breadcrumbs'][]= $title;

?>

<div>

	<h1><?= $title; ?></h1>

	<!-- INICIO Div Mensajes -->
	<div id="divMensaje" class="mensaje alert-success" style="display:none">
	</div>
	<!-- FIN Div Mensajes -->

	<div class="separador-horizontal"></div>

	<table width="100%" id='filtros'> 
		<tr> 
			<td width="11%"> <label>Objeto desde: </label> </td>
			<td>
				<?= Html::textInput('txObjetoDesde', null, [
						'class' => 'form-control', 
						'id' => 'txObjetoDesde', 
						'style' => 'width:95%;', 
						'maxlength' => '8',
						'onchange' => 'obtenerObjeto( "txObjetoDesde" )'
					]); 
				?>
			</td>
			<td> <label>hasta: </label> </td>
			<td>
				<?= Html::textInput('txObjetoHasta', null, [
						'class' => 'form-control', 
						'id' => 'txObjetoHasta', 
						'style' => 'width:95%;', 
						'maxlength' => '8',
						'onchange' => 'obtenerObjeto( "txObjetoHasta" )'
					]); 
				?>
			</td>
			<td width="3%"> </td>
			<td width="13%"> <label>Periodo desde: </label> </td>
			<td>
				<?= Html::textInput('txPeriodoAnioDesde', null, [
						'class' => 'form-control', 
						'id' => 'txPeriodoAnioDesde', 
						'style' => 'width:50%;', 
						'maxlength' => '4', 
						'onkeypress' => 'return justNumbers( event )'
					]); 
				?>
				<?= Html::textInput('txPeriodoMesDesde', null, [
						'class' => 'form-control', 
						'id' => 'txPeriodoMesDesde', 
						'style' => 'width:42%;', 
						'maxlength' => '3',
						'onkeypress'	=> 'return justNumbers( event )'
					]); 
				?>
			</td>
			<td> <label>hasta: </label> </td>
			<td>
				<?= Html::textInput('txPeriodoAnioHasta', null, ['class' => 'form-control', 'id' => 'txPeriodoAnioHasta', 'style' => 'width:50%;', 'maxlength' => '4']); ?>
				<?= Html::textInput('txPeriodoMesHasta', null, ['class' => 'form-control', 'id' => 'txPeriodoMesHasta', 'style' => 'width:42%;', 'maxlength' => '3']); ?>
			</td>
		</tr>
		<tr>
			<td> <label>Fecha desde: </label> </td>
			<td>
				<?=
					DatePicker::widget([
						'name' => 'txFechaDesde',
						'value' => date( 'd/m/Y' ),
						'dateFormat' => 'd/M/y',
						'options' => [
							'class'		=> 'form-control',
							'id' 		=> 'txFechaDesde',
							'style' 	=> 'width:95%; text-align:center;'
						],
					]);
				?>
			</td>
			<td> <label>hasta: </label> </td>
			<td>
				<?=
					DatePicker::widget([
						'name' => 'txFechaHasta',
						'value' => date( 'd/m/Y' ),
						'dateFormat' => 'd/M/y',
						'options' => [
							'class'		=> 'form-control',
							'id' 		=> 'txFechaHasta',
							'style' 	=> 'width:95%; text-align:center;'
						],
					]);
				?>
			</td>
			<td></td>
			<td> <label>Monto desde:</label></td>
			<td>
				<?= Html::textInput('txMontoDesde', null, [
						'class' => 'form-control', 
						'id' => 'txMontoDesde', 
						'style' => 'width:95%;', 
						'maxlength' => '9',
						'onkeypress' => 'return justDecimal( $(this).val(), event )'
					]); 
				?>
			</td>
			<td> <label>hasta:</label></td>
			<td>
				<?= Html::textInput('txMontoHasta', null, [
						'class' => 'form-control', 
						'id' => 'txMontoHasta', 
						'style' => 'width:95%;', 
						'maxlength' => '9',
						'onkeypress' => 'return justDecimal( $(this).val(), event )'
					]); 
				?>
			</td>
		</tr>
		<tr>
			<td> <label>Agente:</label></td>
			<td colspan="3">
				<?= Html::dropDownList('dlAgente', null, $agentesExistentes, [
						'class' => 'form-control', 
						'id' => 'dlAgente', 
						'style' => 'width:100%;'
					]); 
				?>
			</td>
			<td></td>
			<td colspan="4">
				<?= Html::button( 'Buscar', [
						'id' => 'btBuscar',
						'class' => 'btn btn-primary',
						'onclick'  =>'f_Buscar( 0 )'
					]); 
				?>
				<?= Html::button( 'Volver', [
						'id' => 'btVolver',
						'class' => 'btn btn-primary',
						'disabled' => true,
						'onclick' => 'f_Buscar( 1 )'
					]); 
				?>
				<?= Html::button( 'Marcar como Aplicada', [
						'id' => 'btMarcar',
						'class' => 'btn btn-success',
						'disabled' => true,
						'onclick' => 'f_Marcar()'
					]); 
				?>
			</td>
		</tr>
	</table>

	<div class="separador-horizontal" style="margin:10px 0px"></div>

	<?php

	Pjax::begin(['id' => 'pjaxGrilla', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

		echo Html::errorSummary( $model, [
			'id' => 'form_errorSummary',
			'style'	=> 'margin-top: 8px;',
			'class' => "error-summary"
		]);

		echo GridView::widget([
				'id' => 'grillaPendientes',
				'dataProvider' => $dataProvider,
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'columns' => [
						['class' => '\yii\grid\CheckboxColumn'],
						['attribute' => 'ret_id', 'label' => 'ID', 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'retdj_id', 'label' => 'RetDJ', 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'ag_rete', 'label' => 'Agente', 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'cuit', 'label' => 'CUIT', 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'anio', 'label' => 'Año', 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'mes', 'label' => 'Mes', 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'numero', 'label' => 'Núm.', 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'comprob', 'label' => 'Comprobante', 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'fecha', 'label' => 'Fecha', 'format' => ['date', 'php:d/m/Y'], 'contentOptions' => ['class' => 'grillaGrande']],
						['attribute' => 'base', 'label' => 'Base', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
						['attribute' => 'ali', 'label' => 'Alícuota', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
						['attribute' => 'monto', 'label' => 'Monto', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
						['attribute' => 'est', 'label' => 'Est.', 'contentOptions' => ['class' => 'grillaGrande']],
						
						[
							'class' => 'yii\grid\ActionColumn',
							'contentOptions'=>['style'=>'width:2%', 'align' => 'center'],
							'template' => '{view}',
							'buttons' => [

								'view' => function( $url, $model, $key )
											{
												return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
													'class'=>'bt-buscar-label',
													'style'=>'color:#337ab7',
													'onclick' => "f_verRetencion( " . $model['ret_id'] . " );event.stopPropagation();",
													'data-pjax' => 0 
												]);
											},
							]
						]
					]
			]);

	Pjax::end();
	?>

	</div>

</div>	

<?php

    Pjax::begin([ 'id' => 'pjaxModal', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);
		
		$rete_id = Yii::$app->request->post( 'rete_id', 0 );
		
        Modal::begin([
                'id' => 'ModalRete',
                'class' => 'container',
                //'size' => 'modal-sm',
                'header' => '<h2><b>Consultar Retención</b></h2>',
                'closeButton' => [
                    'label' => '<b>X</b>',
                    'class' => 'btn btn-danger btn-sm pull-right'
                    ],
            ]);
			
           echo RetencionController::verRetencion( $rete_id, '#ModalRete' );

        Modal::end();

    Pjax::end();
?>

<?php if ( $mensaje != '' ) { //Si existen mensajes ?>
	<script>

	$( document ).ready(function() {

		mostrarMensaje( "<?= $mensaje; ?>", "#divMensaje" );

	});

	</script>
<?php }  ?>

<script type="text/javascript">
	
	function obtenerObjeto( valor ){
		$.post( "<?= BaseUrl::toRoute('obtenerobjeto');?>", { "objeto": $("#"+valor).val() } 
		).success(function(data){
			datos = jQuery.parseJSON(data); 
					
			$("#"+valor).val(datos.objeto)
			
		});
	}

	function f_Buscar( limpiar ){

		$.pjax.reload({
			container	: "#pjaxGrilla",
			type		: "POST",
			replace		: false,
			push		: false,
			timeout 	: 100000,
			data: {
				"obj_desde"	: $("#txObjetoDesde").val(),
				"obj_hasta"	: $("#txObjetoHasta").val(),
				"perAnioDesde" : $("#txPeriodoAnioDesde").val(),
				"perMesDesde" : $("#txPeriodoMesDesde").val(),
				"perAnioHasta" : $("#txPeriodoAnioHasta").val(),
				"perMesHasta" : $("#txPeriodoMesHasta").val(),
				"fechaDesde" : $("#txFechaDesde").val(),
				"fechaHasta" : $("#txFechaHasta").val(),
				"montoDesde" : $("#txMontoDesde").val(),
				"montoHasta" : $("#txMontoHasta").val(),
				"agente" :  $("#dlAgente").val(),
				"limpiar" : limpiar
			},
		});

		$("#pjaxGrilla").on("pjax:complete", function(){

			$("#filtros *").attr("disabled", (limpiar == 0));
			$("#btVolver").attr("disabled", (limpiar == 1));
			$("#btMarcar").attr("disabled", (limpiar == 1));

			$("#pjaxGrilla").off("pjax:complete");
		});
	}

	function f_Marcar(){
		var keys = $('#grillaPendientes').yiiGridView('getSelectedRows');
		
		$.pjax.reload({
			container	: "#pjaxGrilla",
			type		: "POST",
			replace		: false,
			push		: false,
			timeout 	: 100000,
			data: {
				"grabar" : 1,
				"rete"	: keys.toString()
			},
		});
	}
	
	function f_verRetencion( id ){
		
		$.pjax.reload({
			container	: "#pjaxModal",
			type		: "POST",
			replace		: false,
			push		: false,
			timeout 	: 100000,
			data: {
				"rete_id"	: id
			},
		});
		
		$("#pjaxModal").on("pjax:complete", function(){

			$("#ModalRete").modal("show");

			$("#pjaxModal").off("pjax:complete");
		});
	}

</script>