<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\bootstrap\Modal;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\helpers\Url;
use app\controllers\ctacte\RetencionController;

$title = 'Nueva DJ de agente de retención';

switch($action){

	case 1: $title= 'Consulta de DJ Nº ' . $model->retdj_id; break;
	case 2: $title= 'Eliminar DJ Nº ' . $model->retdj_id; break;
	case 3: $title= 'Modificar DJ Nº ' . $model->retdj_id; break;
}

?>

<!-- INICIO Div Principal -->
<div id="usuarioweb_retencion_form_divPrincipal">

	<div class="pull-left">
		<h1><?= $title; ?></h1>
	</div>

	<div class="pull-right" style="margin-right: 15px; margin-top: 5px">
		<?=
			Html::a('Imprimir', ['//ctacte/liquida/imprimircomprobante','id' => $model->ctacte_id],[
				'data-pjax'=>'0','target'=>'_blank',
				'class' => 'btn btn-success'
			]);
		?>
		
		<?php 
			Modal::begin([
				'id' => 'Exportar',
				'header' => '<h2>Exportar Datos</h2>',
				'toggleButton' => [
					'label' => 'Exportar',
					'class' => 'btn btn-success',
				],
				'closeButton' => [
				  'label' => '<b>X</b>',
				  'class' => 'btn btn-danger btn-sm pull-right',
				]
			]);

				echo $this->render('//site/exportar',['titulo'=>'Retenciones Practicadas','desc' => Yii::$app->session['condicion'],'grilla'=>'Exportar']);

			Modal::end();
		?>
	</div>

	<div class="clearfix"></div>

	<?php if( $action == 2 ){ ?>
		<div class="mensaje alert-warning" style="margin-right: 15px;  padding: 8px">
			<h3><strong>Al eliminar una Declaración Jurada, se eliminan también las retenciones asociadas a ésta.</strong></h3>
		</div>
	<?php } ?>

	<?php Pjax::begin([ 'id' => 'usuarioweb_retencion_form_pjaxDatos', 'enablePushState' => false, 'enableReplaceState' => false, 'timeout' => 100000 ]); ?>

	<?php

		$datosFormulario = [];

		if( $action == 0 ){

			$datosFormulario = [
				'id'		=> 'formRetencion',
				'action'	=> [
					'create', 'idusr' => $model->ag_rete, 'importar' => 0,
				],
			];
		} else {
			$datosFormulario = [
				'id'		=> 'formRetencion',
			];
		}

		$form = ActiveForm::begin( $datosFormulario );
	?>

	<!-- INICIO Div Cabecera -->
	<div class="form-panel">

	<table border="0">
		<tr>
			<td><label>Agente:</label></td>
			<td>
				<?=
					Html::activeInput( 'hidden', $model, 'ag_rete' );
				?>
				<?=
					Html::label( $model->ag_rete, null, [
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width: 80px;text-align:center',
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label>CUIT:</label></td>
			<td>
				<?=
					MaskedInput::widget([
						'model' => $model,
						'attribute' => 'cuit',
						'mask' => '99-99999999-9',
						'options' => [
							'class' => 'form-control solo-lectura',
							'tabindex' => -1,
							'id' => 'agenteCuit',
							'style' => 'width:100px;text-align: center; font-weight: bold',
						],
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label>Denominación:</label></td>
			<td colspan="2">
				<?=
					Html::label( $model->denominacion, null, [
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width: 280px;text-align:left',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Período:</label></td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'anio', [
						'id'	=> 'usuarioweb_retencion_form_txAnio',
						'class'	=> 'form-control' . ( $action == 0 && !$mostrarDivDatos ? '' : ' solo-lectura' ),
						'style'	=> 'width:40px;text-align:center',
						'maxlength'		=> '4',
						'onkeypress'	=> 'return justNumbers( event )',
						//'onchange'		=> 'cambiaPeriodo()',
						'tabIndex'		=> ( $action == 0 && !$mostrarDivDatos ? '0' : '-1' ),
					]);
				?>
				<?=
					Html::activeInput( 'text', $model, 'mes', [
						'id'	=> 'usuarioweb_retencion_form_txMes',
						'class'	=> 'form-control' . ( $action == 0 && !$mostrarDivDatos ? '' : ' solo-lectura' ),
						'style'	=> 'width:35px;text-align:center',
						'maxlength'		=> '3',
						'onkeypress'	=> 'return justNumbers( event )',
						//'onchange'		=> 'cambiaPeriodo()',
						'tabIndex'		=> ( $action == 0 && !$mostrarDivDatos ? '0' : '-1' ),
					]);
				?>
			</td>
			<td></td>
			<td><label>Presentación:</label></td>
			<td>
				<?=
					DatePicker::widget([
						'value' => $model->fchpresenta,
						'dateFormat' => 'dd/MM/Y',
						'options' => [
							'class'		=> 'form-control solo-lectura',
							'id' 		=> 'usuarioweb_retencion_form_txFecha',
							'style' 	=> 'width:100px; text-align:center;',
							'onchange' 	=> '$("#fechaOculto").val($(this).val());',
							'tabIndex'	=> '-1',
						],
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label>Estado:</label></td>
			<td>
				<?=
					Html::label( $model->est_nom, null, [
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width: 120px;text-align:left',
					]);
				?>
			</td>
			<td align="right">
				<?=
					Html::button( 'Generar' , [
						'id'	=> 'usuarioweb_retencion_form_btGenerar',
						'class'	=> 'btn btn-success' . ( $action == 0 && !$mostrarDivDatos ? '' : ' hidden' ),
						'onclick'	=> 'f_generar()',
					]);
				?>
			</td>
		</tr>

	</table>

	</div>
	<!-- FIN Div Cabecera -->

	<?php ActiveForm::end(); ?>

	<!-- INICIO Div Datos -->
	<div id="usuarioweb_retencion_form_divDatos" class="form-panel"<?= $mostrarDivDatos ? "" : " hidden" ?> style="padding: 15px">

	<div class="pull-left">
		<h3 style="display:inline-block"><u>Retenciones Practicadas:</u></h3>
	</div>

	<div class="pull-right">

		<?=
			Html::button( 'Importar', [
				'class'		=> 'btn btn-success' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' hidden' ),
				'style'		=> 'margin-right: 5px',
				'onclick'	=> 'mostrarImportar();'
			]);
		?>

		<?=
			Html::a( '<span class="glyphicon glyphicon-question-sign"></span>', Url::to( '@web/pdf/archivoRetenciones.pdf', true ), [
				'title'		=> 'Formato de archivo para cargar retenciones',
				'class'		=> ( in_array( $action, [ 0, 3 ] ) ? '' : ' hidden' ),
				'style'		=> 'margin-right: 30px',
				'data-pjax'	=> '0',
				'target'	=> '_black',
			]);
		?>

		<?=
			Html::button( 'Nuevo', [
				'class'		=> 'btn btn-success' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' hidden' ),
				'onclick'	=> 'f_manejoRetenciones( 0, 0 )',
			]);
		?>
	</div>

	<div class="clearfix"></div>

	<?php Pjax::begin(['id' => 'usuarioweb_retencion_form_pjaxRetencion', 'enableReplaceState' => false, 'enablePushState' =>  false, 'timeout' => 100000 ]); ?>

	<!--INICIO Grilla -->
	<?=
		GridView::widget([
			'dataProvider' => $dataProviderDetalleRetenciones,
			'id' => 'grillaDetalles',
			'headerRowOptions' => ['class' => 'grillaGrande'],
			'columns' => [
				['label' => 'CUIT', 'attribute' => 'cuit', 'contentOptions' => ['class' => 'grillaGrande'], 'content' => function($model){return substr($model['cuit'], 0, 2) . '-' . substr($model['cuit'], 2, 8) . '-' . substr($model['cuit'], 10, 1);}],
				['label' => 'Nombre', 'attribute' => 'nombre', 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'Fecha', 'attribute' => 'fecha', 'contentOptions' => ['class' => 'grillaGrande'], 'format' => [ 'date', 'php:d/m/Y' ]],
				['label' => 'Nº', 'attribute' => 'numero', 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'T. comprob.', 'attribute' => 'tcomprob','value' => function ($model) {
																						return utb::getCampo("ret_tcomprob","cod='" . trim($model->tcomprob) . "'");
																				}, 'contentOptions' => ['class' => 'grillaGrande']],
				['label' => 'Comprob.', 'attribute' => 'comprob', 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:center']],
				['label' => 'Base', 'attribute' => 'base', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right']],
				['label' => 'Alícuota', 'attribute' => 'ali', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right']],
				['label' => 'Monto', 'attribute' => 'monto', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right']],
				['label' => 'Est.', 'attribute' => 'est', 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:center']],

				['class' => '\yii\grid\ActionColumn', 'template' => (in_array($action, [0, 3]) ? '{update} {delete}' : ''), 'contentOptions' => ['class' => 'grillaGrande'],
				'buttons' => [

					'update' => function($url, $model){

						if( $model['est'] == 'A' ){
							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', null, ['onclick' => 'event.stopPropagation(); f_manejoRetenciones(' . $model['numero'] . ', 3 );']);
						}

					},

					'delete' => function($url, $model){

						if( $model['est'] == 'A' ){
							return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['onclick' => 'event.stopPropagation(); f_manejoRetenciones(' . $model['numero'] . ', 2 );']);
						}

					}
				]
				]
			]
		]);

	?>
	<!-- FIN Grilla -->

	<div class="pull-right" style="margin-top: 8px">

		<table>
			<tr>
				<td><label>Cantidad:</label></td>
				<td>
					<?=
						Html::input( 'text', 'txCantidad', $model->cant, [
							'id'	=> 'usuarioweb_retencion_form_txCantidad',
							'class'	=> 'form-control solo-lectura',
							'style'	=> 'width:60px;text-align:center',
							'tabIndex'		=> '-1',
						]);
					?>
				</td>
				<td width="20px"></td>
				<td><label>Monto:</label></td>
				<td>
					<?=
						Html::input( 'text', 'txMonto', $model->monto, [
							'id'	=> 'usuarioweb_retencion_form_txMonto',
							'class'	=> 'form-control solo-lectura',
							'style'	=> 'width:100px;text-align:right',
							'tabIndex'		=> '-1',
						]);
					?>
				</td>
			</tr>
		</table>

	</div>

	<div class="clearfix"></div>

	<?php if( $dibujarModal ){	//Verificar si no se debe dibujar el modal en caso de error. ?>

		<script>
		f_manejoRetenciones( 0, 0 );
		</script>

	<?php } ?>

	<?php if( $error != '' ){ ?>

		<script>
			mostrarErrores( ["<?= $error ?>"], "#usuarioweb_retencion_form_errorSummary" );
		</script>

	<?php } ?>

	<?php if( $model->hasErrors() ){ ?>
		<script>var error = new Array();</script>
	<?php  foreach( $model->getErrors() as $array ){ ?>
		<script>error.push( "<?= $array[0] ?>" );</script>
	<?php } ?>
		<script>mostrarErrores( error, "#usuarioweb_retencion_form_errorSummary" );</script>
	<?php } ?>

	<?php Pjax::end(); ?>

	</div>
	<!-- FIN Div Datos -->

	<!-- INICIO Div Botones -->
	<div>
		<?=
			Html::button( ( $action == 1 ? 'Presentar' : 'Grabar' ), [
				'id'	=> 'usuarioweb_retencion_form_btGrabar',
				'class'	=> ( $action == 2 ? 'btn btn-danger' : 'btn btn-success' ) . ( $mostrarDivDatos ? '' : ' disabled' ) . ( $action == 1 && !$model->necesitaAprobarse ? ' hidden' : '' ),
				'style'	=> 'margin-right: 15px',
				'onclick'	=> 'f_grabarDJ()',
			]);
		?>

		<?=
			Html::a( ( $action == 1 ? 'Volver' : 'Cancelar' ), [ 'index', 'id' => $model->ag_rete ], [
				'id'	=> 'usuarioweb_retencion_form_btCancelar',
				'class'	=> 'btn btn-primary',
			]);
		?>

	</div>
	<!-- FIN Div Botones -->

	<?php if( $error != '' ){ ?>

		<script>
			mostrarErrores( ["<?= $error ?>"], "#usuarioweb_retencion_form_errorSummary" );
		</script>

	<?php } ?>

	<?php
		$formArchivo = ActiveForm::begin([
			'id' => 'retencion_formImportar',
			'method' => 'POST',
			'action' => [
				'create',
				'idusr'		=> $model->ag_rete,
				'importar' 	=> 1,
			],
			'options' => [
				'enctype' => 'multipart/form-data',
			],
		]);

			echo Html::activeInput( 'hidden', $model, 'ag_rete' );
			echo Html::activeInput( 'hidden', $model, 'cuit' );
			echo Html::activeInput( 'hidden', $model, 'denominacion' );
			echo Html::activeInput( 'hidden', $model, 'anio' );
			echo Html::activeInput( 'hidden', $model, 'mes' );
			echo Html::activeInput( 'hidden', $model, 'fchpresenta' );
			echo Html::activeFileInput( $model, 'archivo', [
				'class'	=> 'hidden',
				'id'	=> 'archivoImportar',
				'onchange'	=> '$("#retencion_formImportar").submit();'
			]);

			echo Html::input( 'hidden', '_pjax', "#retencion_form_pjaxArchivo" );

		ActiveForm::end();

	?>

	<?php Pjax::end(); ?>

	<!-- INICIO Div Errores -->
	<?php

		echo $form->errorSummary( $model, [
			'id' => 'usuarioweb_retencion_form_errorSummary',
			'style'	=> 'margin-top: 8px;margin-right: 15px',
		]);
	?>
	<!-- FIN Div Errores -->

</div>
<!-- FIN Div Principal -->

</div>

<!-- INICIO Ventana Modal "Nueva Retención" -->
 <?php

 	Pjax::begin(['id' => 'usuarioweb_retencion_form_PjaxModalRete', 'enablePushState' => false, 'enableReplaceState' => false, 'timeout' => 100000 ]);

		switch( intVal( $rete_action ) ){

			case 0:

				$title = 'Nueva Retención';
				break;

			case 1:

				$title = 'Consulta Retención';
				break;

			case 2:

				$title = 'Eliminar Retención';
				break;

			case 3:

				$title = 'Modificar Retención';
				break;

			default:

				$title = '';
				break;

		}

		Modal::begin([
			'id' => 'modalABMRetencion',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">' . $title . '</h2>',
			'size' => 'modal-normal',
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			],
		]);

		echo RetencionController::ABMRete( $rete_id, $model->ag_rete, $rete_action, $model->anio, $model->mes, "#usuarioweb_retencion_form_pjaxRetencion", "#modalABMRetencion" );

		Modal::end();

	Pjax::end();

?>
<!-- FIN Ventana Modal "Nueva Retención" -->

<script type="text/javascript">

function f_generar(){

	var anio		= isNaN( $( "#usuarioweb_retencion_form_txAnio" ).val() ) ? 0 : $( "#usuarioweb_retencion_form_txAnio" ).val(),
		mes			= isNaN( $( "#usuarioweb_retencion_form_txMes" ).val() ) ? 0 : $( "#usuarioweb_retencion_form_txMes" ).val(),
		presenta	= $( "#usuarioweb_retencion_form_txFecha" ).val(),
		error 		= new Array();

	ocultarErrores( "#usuarioweb_retencion_form_errorSummary" );

	if( anio == 0 ){

		error.push( "Ingrese un año." );
	}

	if( mes == 0 ){

		error.push( "Ingrese un mes." );
	}

	if( presenta == '' ){

		error.push( "Ingrese una fecha de presentación." );
	}

	if( error.length == 0 ){

		$.pjax.reload({
			container	: "#usuarioweb_retencion_form_pjaxDatos",
			type		: "GET",
			replace		: false,
			push		: false,
			data:{
				"anio"		: anio,
				"mes"		: mes,
				"presenta"	: presenta,
			}
		});

	} else {
		mostrarErrores( error, "#usuarioweb_retencion_form_errorSummary" );
	}
}

function f_manejoRetenciones( ret_id, action ){

	var anio 	= $( "#usuarioweb_retencion_form_txAnio" ).val(),
		mes 	= $( "#usuarioweb_retencion_form_txMes" ).val();

	ocultarErrores( "#usuarioweb_retencion_form_errorSummary" );

	$.pjax.reload({
		container	:"#usuarioweb_retencion_form_PjaxModalRete",
		type 		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data:{
			"rete_id"		: ret_id,
			"rete_action"	: action,
			"anio"			: anio,
			"mes"			: mes,
		},
	});

}

$( "#usuarioweb_retencion_form_PjaxModalRete" ).on( "pjax:end", function() {

	$( "#modalABMRetencion" ).modal( "show" );
});

function f_grabarDJ(){

	$( "#formRetencion" ).submit();
}

function mostrarImportar(){

	$("#archivoImportar").click();
}

</script>
