<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use app\models\ctacte\RetencionDetalle;
use app\controllers\ctacte\DdjjController;

/**
 * Forma que se dibuja cuando se quiere dar de Alta una DJ.
 * Recibo:
 * 			=> $model -> Modelo
 * 			=> $dataProvider -> Datos para la grilla
 *			=> $consulta es una variable que:
 *			 		=> $consulta == 1 => El formulario se dibuja en el index
 *			  		=> $consulta == 0 => El formulario se dibuja en el create
 *			  		=> $consulta == 3 => El formulario se dibuja en el update
 *			  		=> $consulta == 2 => El formulario se dibuja en el delete
 *
 *			=> $saldo				-> Saldo calculado. Por defecto es 0.
 *			=> $aplicaBonificacion	-> Variable que indica si se aplica bonificación.
 */

?>

<style>
.form-panel {
	padding-right: 8px;
	margin-right:0px;
}

</style>

<div class="clearfix"></div>

<!-- INICIO Div DJ -->
<div id="ddjj_form_dj" style="margin-right: 5px;">

	<!-- INICIO Mensajes -->
	<div id="ddjj_mensajes" class="alert-success mensaje" style="display:none">

	</div>
	<!-- FIN Mensajes -->

<?php $form = ActiveForm::begin([ 'id' => 'ddjj_form' , 'action' => ['create']]); ?>

<?php Pjax::begin([ 'id' => 'ddjj_pjaxCargarDatos', 'enablePushState' => false, 'enableReplaceState' => false ]); ?>

<?=
	Html::activeInput( "hidden", $model, 'fiscaliza', [
		'id' => 'ddjj_txFiscaliza',
	]);
?>

<?php Pjax::begin([ 'id' => 'ddjj_pjaxCambiaTributo', 'enablePushState' => false, 'enableReplaceState' => false ]); ?>

<div id="ddjj_form_cabecera" class="form-panel" style="padding: 8px;margin-right: 0px">

<table border="0">
	<tr>
		<td width="55px"><label>Tributo:</label></td>
		<td colspan="7">

			<?= Html::hiddenInput('dlTrib', $model->trib_id, ['id' => 'ddjj_trib']); ?>
			<?=
				Html::activeDropDownList( $model, 'trib_id', $tributos, [
					'id'=>'ddjj_dlTrib',
					'style' => 'width:98%',
					'class' => 'form-control',
					'onchange'=>'f_cambiaTributoObjeto( )',
				]);
			?>
		</td>
		<td width="10px"></td>
		<td width="40px"><label>Orden:</label></td>
		<td colspan="6" width="120px !important">
			<?= Html::activeInput('hidden', $model, 'orden', ['id' => 'ddjj_txOrden']); ?>
			<?=
				Html::activeInput('text',$model, 'orden_nom',[
					'id'=>'ddjj_txOrdenNom',
					'class'=>'form-control solo-lectura',
					'style'=>'width:99%;text-align:center',
					'tabIndex'=> '-1',
				]);
			?>
		</td>
	</tr>

	<tr>
		<td width="50px"><label>Objeto:</label></td>
		<td colspan="1">
			<?=
				Html::activeInput('text', $model, 'obj_id',[
					'id'		=>'ddjj_txObjetoID',
					'class'		=>'form-control ' . ( $permiteModificarObj && intVal( $model->trib_id ) != 0 ? '' : ' solo-lectura' ),
					'style'		=>'width:80px;text-align:center',
					'onchange'	=> 'f_cambiaTributoObjeto( )',
					'tabIndex'	=> ( $permiteModificarObj && intVal( $model->trib_id ) != 0 ? '0' : '-1' ),
				]);
			?>
		</td>
		<td>

			<?php
			//INICIO Modal Busca Objeto
			Modal::begin([
				'id' => 'ddjj_form_buscaObj',
				'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
				'size' => 'modal-lg',
				'toggleButton' => [
					'id' 		=> 'ddjj_btBusquedaObjeto',
					'label' 	=> '<i class="glyphicon glyphicon-search"></i>',
					'class' 	=> 'bt-buscar',
					'style'		=> ( $permiteModificarObj && intVal( $model->trib_id ) != 0 ? 'display:block' : 'display:none' ),
				],
				'closeButton' => [
				  'label' => '<b>X</b>',
				  'class' => 'btn btn-danger btn-sm pull-right',
				],
			]);

				echo $this->render('//objeto/objetobuscarav',[
						'idpx' => 'buscaDDJJ',
						'id' => 'ddjjaltaBuscar',
						'txCod' => 'ddjj_txObjetoID',
						'txNom' => 'ddjj_txObjetoNom',
						'selectorModal' => '#ddjj_form_buscaObj',
					]);

			Modal::end();
			//FIN Modal Busca Objeto

			?>

		</td>
		<td colspan="5">
			<?=
				Html::activeInput('text',$model, 'obj_nom',[
					'id'=>'ddjj_txObjetoNom',
					'class'=>'form-control solo-lectura',
					'style'=>'width:280px;text-align:left',
					'tabIndex'=>'-1',
				]);
			?>
		</td>
		<td></td>
		<td><label>Nº IB:</label></td>
		<td colspan="2">
			<?=
				Html::input( 'text', 'txIb', $model->ib, [
					'id'		=>'ddjj_txIb',
					'class'		=>'form-control solo-lectura',
					'style'		=> 'width:99%;text-align:center',
					'tabIndex' 	=> '-1',
				]);
			?>
		</td>
	</tr>

	<tr>
		<td width="40px"><label>Sucursal:</label></td>
		<td colspan="3">
			<?=
				Html::activeInput('text', $model, 'subcta', [
					'id'=>'ddjj_txSucursal',
					'class'=>'form-control' . ( $model->usa_subcta ? '' : ' solo-lectura' ),
					'style'=>'width:80px;text-align:center',
					'tabIndex' => ( $model->usa_subcta ? '0' : '-1' ),
				]);
			?>
		
			<label>Cuit:</label>
			<?=
				Html::activeInput('text', $model, 'cuit', [
					'id'=>'ddjj_txCUIT',
					'class'=>'form-control solo-lectura',
					'style'=>'width:90px;text-align:center',
					'tabIndex' => '-1',
				]);
			?>
		</td>
		<td width="10px"></td>
		<td>
			<label>Año:</label>
			<?=
				Html::activeInput('text',$model, 'anio',[
					'id'=>'ddjj_txAnio',
					'class'=>'form-control',
					'style'=>'width:42px;text-align:center;margin-right: 10px',
					'onkeypress'=>'return justNumbers(event)',
					'maxlength'=> 4,
				]) ?>
			<label>Cuota:</label>
			<?=
				Html::activeInput('text',$model, 'cuota',[
					'id'=>'ddjj_txCuota',
					'class'=>'form-control',
					'style'=>'width:35px;text-align:center',
					'onkeypress'=>'return justNumbers(event)',
					'maxlength'=>2,
				]);
			?>
		</td>
		<td colspan="3"></td>
		<td><label>Tipo:</label></td>
		<td colspan="3">
			<?=
				Html::activeDropDownList( $model, 'tipo', $tiposDDJJ, [
					'style' => 'width:99%',
					'class' => 'form-control',
					'id'=>'ddjj_dlTipo',
					'onchange' => '$("#ddjj_tipo").val($(this).val());'
				]);
			?>
		</td>
	</tr>

	<tr>
		<td><label>Fechas:</label></td>
		<td colspan="2"><label>Presentación:</label></td>
		<td>
			<?= DatePicker::widget([
					'model'	=> $model,
					'attribute'	=> 'fchpresenta',
					'dateFormat' => 'php:d/m/Y',
					'options' => [
						'class' => 'form-control'.(utb::getExisteProceso(3333) == 1 ? '' : ' solo-lectura'),
						'style' => 'width:80px;text-align:center',
						'tabindex'=>(utb::getExisteProceso(3333) == 1 ? '0' : '-1'),
					],
				]);
			?>
		</td>
		<td></td>
		<td>
			<label>Vencimiento:</label>
			<?= DatePicker::widget([
					'model'	=> $model,
					'attribute'	=> 'fchvenc',
					'id' => 'ddjj_fchvencimiento',
					'dateFormat' => 'php:m/d/Y',
					'options' => [
						'class' => 'form-control solo-lectura',
						'tabIndex'=>'-1',
						'style' => 'width:80px;text-align: center'],
				]);
			?>
		</td>
		<td align="right" colspan="5">
			<?=
				Html::button('<span class="glyphicon glyphicon-play"></span>',[
					'id'=>'btCargar1',
					'class' => 'btn btn-success',
					'onclick'=> 'f_cargarDatos()',
				]);
			?>
		</td>
	</tr>
</table>

</div>

<?php

if( $model->hasErrors() && Yii::$app->request->get( '_pjax', '' ) == '#ddjj_pjaxCambiaTributo' ){

	echo "<script>var error = new Array();</script>";

	foreach( $model->getErrors() as $err ){
		echo "<script>error.push( '" . $err[0] . "' );</script>";
	}

	?>

	<script>

	$( document ).ready(function() {
		mostrarErrores( error, "#ddjj_errorSummary" );
	});

	</script>

	<?php

}
?>

<?php Pjax::end(); ?>

<div id="ddjj_divDatos" style="display:none">

<?php Pjax::begin(['id' => 'ddjj_pjaxCalcularDJ', 'enablePushState' => false, 'enableReplaceState' => false ]); ?>

<!-- INICIO Div Grillas -->
<div id="ddjj_divGrillas">

	<!-- INICIO Grilla Rubros -->
	<div class="form-panel" style="padding-right:8px;padding-bottom: 8px;padding-top: 8px">

		<div class="pull-left">
			<h3><label>Rubros</label></h3>
		</div>

		<div class="clearfix"></div>

		<?php Pjax::begin(); ?>

		<div class="div_grilla" style="margin-top: 8px;">

			<?= GridView::widget([
				'id' => 'GrillaRubrosddjj',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProvRubros,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'nomen_nom','header' => 'Nomeclador', 'contentOptions'=>['style'=>'text-align:left','width'=>'80px']],
						['attribute'=>'rubro_id','header' => 'Rubro', 'contentOptions'=>['style'=>'text-align:left','width'=>'30px']],
						['attribute'=>'rubro_nom','header' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
						['attribute'=>'formCalculo','header' => 'Fórmula', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						// Base
						['content'=> function($model, $key, $index, $column) {
							return Html::input('text','DDJJ_base',$model["base"],
									[
										'id' => $model["trib_id"]."-".$model["rubro_id"],
										'style' => 'width:70px;margin:0px;text-align:right',
										'class' => 'form-control',
										'maxlength' => 12,
										'onkeypress' => 'return justDecimal( $(this).val(), event)',
										'onchange' => 'f_inhabilitarPresentarDJ()',

									]);
							},
						'label' => 'Base',
						'contentOptions'=>['style'=>'width:60px;text-align:center'],
						],
						//Cantidad
						['content'=> function($model, $key, $index, $column) {

							$tminimo = $model['tminimo'];

							//Dependiendo de tminimo se puede modificar la cantidad
							if ( $tminimo == 3 || $tminimo == 4 || $tminimo == 8 )
							{
								return Html::input('text','DDJJ_cant',$model["cant"],
										[
											'id' => $model["trib_id"]."-".$model["rubro_id"],
											'style' => 'width:40px;margin:0px;text-align:center',
											'class' => 'form-control',

										]);
							} else
							{
								return Html::input('text','DDJJ_cant',$model["cant"],
										[
											'id' => $model["trib_id"]."-".$model["rubro_id"],
											'style' => 'width:40px;margin:0px;text-align:center',
											'class' => 'form-control solo-lectura',
											'tabIndex' => '-1',

										]);
							}

						},
						'label' => 'Cantidad',
						'contentOptions'=>['style'=>'width:40px;text-align:center'],
						],
						['attribute'=>'alicuota','header' => 'Ali', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
						['attribute'=>'minimo','header' => 'Mín', 'contentOptions'=>['style'=>'text-align:right','width'=>'40px']],
						['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'40px']],

					],
				]);
			?>

		</div>

		<?php Pjax::end(); ?>

	</div>
	<!-- FIN Grilla Rubros -->

	<?php if( $permiteRetenciones and $config_ddjj['itemrete'] > 0 ){ ?>

		<!-- INICIO Grilla Retenciones -->
		<div id="ddjj_form_retenciones" class="form-panel" style="padding-right:8px;padding-bottom: 8px;padding-top: 15px">

			<div class="pull-left">
		        <h3><label>Retenciones</label></h3>
		    </div>

		    <div class="pull-right">
		        <?php
					if( $config_ddjj['perm_retemanual']) {
						echo Html::button('Agregar Rete. Manual',[
			                'class' => 'btn btn-success',
			                'id'		=> 'btAgregarRetenciones',
			    			'onclick'	=> 'f_agregarRetencion()',
			            ]);
					}
		        ?>
		    </div>

		    <div class="clearfix"></div>

		<?php Pjax::begin([ 'id' => 'PjaxCargarRete', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]); ?>

		<div class="div_grilla" style="margin-top: 8px;">

			<?=
				GridView::widget([
					'id' => 'GrillaRetencionesNuevasDDJJ',
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'dataProvider' => $dataProviderRete,
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [
							// Cargar
							['content'=> function($model, $key, $index, $column) {
								return Html::checkbox('DDJJ_rete[]',$model['activo'],
										[
											'id' => $model['ret_id'],
											'class' => 'check-rete',
											'style' => 'width:20px;padding: 0px;margin: 0px;',
											'onchange' => 'f_inhabilitarPresentarDJ()',
											'disabled' => !$model['puedeeliminar'],
											'value'		=> $model['ret_id'],
										]);
								},
								'header' => Html::checkBox('selection_all', false,
								[
									'id' => 'ddjj_ckRetencionesTodas',
									'onchange'=>'cambiarChecksGrillaRetenciones()',
								]),
							'contentOptions'=>['style'=>'width:20px;text-align:center'],
							'headerOptions' => ['style' => 'width:20px;text-align: center'],
							],
							['attribute'=>'numero','header' => 'Núm.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
							['attribute'=>'fecha','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px'],'format' => [ 'date', 'php:d/m/Y' ],],
							['attribute'=>'ag_rete','header' => 'AgRet', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
							['attribute'=>'ag_cuit','header' => 'AgR.CUIT', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
							['attribute'=>'ag_nom_redu','header' => 'AgR.Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
							['attribute'=>'tcomprob','header' => 'Tipo', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
							['attribute'=>'comprob','header' => 'Comprob', 'contentOptions'=>['style'=>'text-align:center','width'=>'1x']],
							['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
							['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
							[
		                        'class' => 'yii\grid\ActionColumn',
		                        'contentOptions'=> ['style'=>'width:50px; text-align: center',],
		                        'template' => '{delete}',
		                        'buttons'=>[

		                            'delete' => function($url, $model, $key)
		                                        {
													if( $model['puedeeliminar'] ){
														return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['onclick' => 'event.preventDefault(); f_eliminarRetencion(' . $model['ret_id'] . ' )']);
													}

		                                        },
		                        ]
		                    ],
						],
				]);
			?>

			<?php

			if( $model->hasErrors() && in_array(Yii::$app->request->get( '_pjax', '' ), ['#PjaxCargarRete', '#ddjj_pjaxCalcularDJ']) ){

				echo "<script>var error = new Array();</script>";

				foreach( $model->getErrors() as $err ){
					echo "<script>error.push( '" . $err[0] . "' );</script>";
				}

				?>

				<script>

				$( document ).ready(function() {
				    mostrarErrores( error, "#ddjj_errorSummary" );
				});

				</script>

				<?php

			}
			?>

			<?php if ( $mensaje != '' ){ //Si existen mensajes ?>

			<script>

			$( document ).ready(function() {
			    mostrarMensaje( "<?= $mensaje; ?>", "#ddjj_mensajes" );
			});

			</script>

			<?php } ?>

			<?php if ( $error != '' ){ //Si existen errores ?>

			<script>

			$( document ).ready(function() {
			    mostrarErrores( ["<?= $error; ?>"], "#ddjj_errorSummary" );
			});

			</script>

			<?php } ?>

		</div>

		<?php Pjax::end(); ?>

		</div>
		<!-- FIN Grilla Retenciones -->

	<?php } ?>

	<!-- INICIO Div Calcular -->
	<div id="ddjj_divCalcular">

		<div class="pull-left">

			<!-- INICIO Grilla Liquidación -->
			<div  id="ddjj_divCalcular_left" class="form-panel" style="padding-right:8px;padding-bottom: 8px;margin-right: 5px;width: 500px">

				<div class="pull-left">
					<h3><label>Liquidaciones</label></h3>
				</div>

				<div class="clearfix"></div>

				<?php
					Pjax::begin();

						echo GridView::widget([
								'id' => 'GrillaLiqddjj',
								'headerRowOptions' => ['class' => 'grilla'],
								'rowOptions' => ['class' => 'grilla'],
								'dataProvider' => $dataProviderLiq,
								'summaryOptions' => ['class' => 'hidden'],
								'columns' => [
										['attribute'=>'item_id','header' => 'Ítem', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
										['attribute'=>'item_nom','header' => 'Descrip.', 'contentOptions'=>['style'=>'text-align:left','width'=>'250px']],
										['attribute'=>'item_monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
						        	],
							]);

					Pjax::end();
				?>
			</div>
			<!-- FIN Grilla Liquidación -->

			<!-- INICIO Grilla Anticipos -->
			<!-- <div class="form-panel grillaInfoDDJJ" style="padding-right:8px;display:none">


				< ?php

						Pjax::begin();
							echo GridView::widget([
									'id' => 'GrillaInfoddjj',
									'headerRowOptions' => ['class' => 'grilla'],
									'rowOptions' => ['class' => 'grilla'],
									'dataProvider' => $dataProviderAnt,
									'summaryOptions' => ['class' => 'hidden'],
									'columns' => [
											['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
											['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
											['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],

							        	],
								]);
						Pjax::end();

				? >
			</div> -->
		</div>

		<div class="pull-right">

			<div id="ddjj_divCalcular_right" class="form-panel" style="width:160px;padding-bottom: 5px; padding-top: 15px">
			<table>
				<tr>
					<td colspan="2">
						<?php if( $config_ddjj['perm_bonif']) { ?>

							<?=
								Html::checkbox( 'ckBonificacion', $aplicaBonificacion, [
									'id'	=> 'ddjj_ckBonificacion',
									'label' => 'Aplicar Bonificación',
									'onclick'	=> 'f_inhabilitarPresentarDJ()',
									'uncheck'	=> 0,
								]);
							?>

							<?php } ?>
					</td>
				</tr>

				<?php if( $config_ddjj['perm_saldo']) { ?>
				<tr>
					<td width="40px"><label>Saldo:</label></td>
					<td>
						<?= Html::input('text','txSaldo',number_format( $saldoAFavor, 2, '.', ''),[
								'id'=>'ddjj_txSaldoFavor',
								'class'=>'form-control',
								'style'=>'width:90px;text-align:right;',
								'onkeypress' => 'return justDecimal( $( this ).val(), event )',
								'onchange'	=> 'f_inhabilitarPresentarDJ()',
							]);
						?>
						<font size='1' color='#FF0000'> Período Anterior </font>
					</td>
				</tr>
				<?php } ?>

				<tr>
					<td width="40px"><label>Base:</label></td>
					<td>
						<?= Html::activeInput('text',$model, 'total_base', [
								'id'=>'ddjj_txBase',
								'class'=>'form-control solo-lectura',
								'style'=>'width:90px;text-align:right;background:#E6E6FA',
								'tabIndex' => '-1',
							]);
						?>
					</td>
				</tr>
				<tr>
					<td width="30px"><label>Monto:</label></td>
					<td>
						<?= Html::activeInput('text',$model, 'total_monto', [
								'id'=>'ddjj_txMonto',
								'class'=>'form-control solo-lectura',
								'style'=>'width:90px;text-align:right;background:#E6E6FA',
								'tabIndex' => '-1',
							]);
						?>
					</td>
				</tr>

			</table>

			</div>

		</div>

		<div class="clearfix"></div>

	</div>
	<!-- FIN Div Calcular -->

</div>
<!-- FIN Div Grillas -->

	<?php

		if( $habilitarPresentar == true ){
	?>
			<script>
				$( document ).ready(function() {
					f_habilitarPresentarDJ();
				});
			</script>
	<?php
		}
	?>

<?php Pjax::end(); ?>

</div>
<!-- FIN Div Datos -->

<!-- INICIO Div Botones -->
<div id="ddjj_divBotones">

	<div id="ddjj_divBotonesHide" class="pull-left"  style="margin-right: 10px; display: none">
		<?= Html::button('Calcular',['id' => 'btDDJJCalcular','class' => 'btn btn-success','onclick'=>'f_calcularDJ()']); ?>
		&nbsp;&nbsp;
		<?=
			Html::button('Presentar DJ',[
				'id' => 'ddjj_btPresentarDJ',
				'class' => 'btn btn-success disabled',
				//'onclick'	=> 'f_enviarFormulario()',
			]);
		?>
	</div>

	&nbsp;&nbsp;

	<div id="ddjj_divBotonesShow" class="pull-left">

		<?php

			if( !$permiteModificarObj ){

				echo Html::a('Cancelar',['listadj', 'id' => $model->obj_id ],[
					'class' => 'btn btn-primary',
					'data-pjax' => '0',
				]);

			} else {

				echo Html::a('Cancelar',['view'],[
					'class' => 'btn btn-primary',
					'data-pjax' => '0',
				]);
			}

		?>

	</div>

	<div class="clearfix"></div>

</div>
<!-- FIN Div Botones -->

<?php

echo $form->errorSummary( $model, [
		'id' => 'ddjj_errorSummary',
		'style' => 'margin-top: 8px; margin-right: 0px',
	]);

if( !$model->hasErrors() ){

	if( $datosCargados ){

		echo '<script>f_deshabilitarCabecera();</script>';
	}

	if( $verificaAdeuda ){

		echo '<script>$( "#ModalAdeudaDJ" ).modal( "show" );</script>';
	}

	if( $verificaExistencia ){

		echo '<script>$( "#ModalRectificativaDJ" ).modal( "show" );</script>';
	}
}

?>

<?php Pjax::end(); ?>

<?php ActiveForm::end(); ?>

<!-- INICIO Ventana Modal "Nueva Retención" -->
 <?php

 	Pjax::begin(['id' => 'ddjj_PjaxModalRete', 'enablePushState' => false, 'enableReplaceState' => false ]);

		Modal::begin([
			'id' => 'modalNuevaRetencion',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Nueva Retención</h2>',
			'size' => 'modal-normal',
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			],
		]);

		echo DdjjController::nuevaRete( $obj_id, "#PjaxCargarRete", "#modalNuevaRetencion" );

		Modal::end();

	Pjax::end();

	?>
	<!-- FIN Ventana Modal "Nueva Retención" -->

</div>

<?php
	//INICIO Modal Adeuda DDJJ
	Modal::begin([
		'id' => 'ModalAdeudaDJ',
		'size' => 'modal-sm',
		'header' => '<h4><b>Períodos Adeudados</b></h4>',
		'closeButton' => [
			'label' => '<b>X</b>',
			'class' => 'btn btn-danger btn-sm pull-right',
			'id' => 'btCancelarModalElim'
			],
	]);

	?>

	<center>
	<p><label>Existen períodos para los cuales no se ingresó DJ.<br />¿Desea continuar?</label></p><br/>

	<?= Html::button('Aceptar',['class' => 'btn btn-success','onclick'=>'f_aceptaPeriodoAdeudado()']); ?>
	&nbsp;&nbsp;
	<?= Html::button('Cancelar',['class' => 'btn btn-primary','onclick'=>'f_cerrarModalAdeudaDJ()']); ?>
	</center>

<?php Modal::end(); //FIN Modal Adeuda DDJJ ?>

<?php
	//INICIO Modal Confirmación Rectificar DDJJ
	Modal::begin([
		'id' => 'ModalRectificativaDJ',
		'size' => 'modal-sm',
		'header' => '<h4><b>Rectificativa</b></h4>',
		'closeButton' => [
			'label' => '<b>X</b>',
			'class' => 'btn btn-danger btn-sm pull-right',
			'id' => 'btCancelarModalElim'
			],
	]);

	?>

	<center>
	<p><label>Ya existe una DDJJ para el Período.<br />¿Desea generar una DDJJ Rectificativa?</label></p><br/>

	<?= Html::button('Aceptar',['class' => 'btn btn-success','onclick'=>'f_aceptaPeriodoExistente()']); ?>
	&nbsp;&nbsp;
	<?= Html::button('Cancelar',['class' => 'btn btn-primary','onclick'=>'f_cerrarModalRectificativaDJ()']); ?>
	</center>

<?php Modal::end();	//FIN Modal Confirmación Rectificar DDJJ ?>

<script>
function f_buscarObjeto(){

	ocultarErrores( "#ddjj_errorSummary" );

	$( "#ddjj_form_buscaObj" ).modal( "show" );
}

function f_cambiaTributoObjeto(  ){

	var trib = $( "#ddjj_dlTrib" ).val();

	ocultarErrores( "#ddjj_errorSummary" );
	$("#ddjj_trib").val( trib );

	$.pjax.reload({
		container	:"#ddjj_pjaxCambiaTributo",
		type 		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data:{
			"trib_id"	: trib,
			"objeto_id"	: $( "#ddjj_txObjetoID" ).val(),
		},
	});

}

function f_deshabilitarCabecera(){

	$( "#ddjj_form_cabecera input" ).addClass( "solo-lectura" );
	$( "#ddjj_form_cabecera select" ).addClass( "solo-lectura" );
	$( "#ddjj_btBusquedaObjeto" ).addClass( "disabled" );
	$( "#ddjj_btBusquedaObjeto" ).removeAttr( "onclick" );
	$( "#btCargar1" ).addClass( "disabled" );

	$( "#ddjj_form_cabecera" ).css( "background-color", "#FAFAFA" );

	$( "#ddjj_divDatos" ).css( "display", "block" );
	$( "#ddjj_divBotonesHide" ).css( "display", "block" );
}

//Función que se utiliza al presionar el botón '>'
function f_cargarDatos(){

	f_cargar( "#ddjj_pjaxCargarDatos", 1, 1, [], [], [], [], 0 );
}

//Función que se utiliza para calcular la DJ
function f_calcularDJ(){

	var base 	= new Array(),
		cant 	= new Array(),
		codigo 	= new Array(),
		rete	= new Array(),
		error 	= new Array();

	//Ocultar div de errores
	ocultarErrores( "#ddjj_errorSummary" );

	$("input[name=DDJJ_base").each(function(){

		if ( $(this).val() != '' && $(this).val() != null )
			base.push( $(this).val() );
		else
			error.push( "Base no puede ser vacío." );

		codigo.push( $(this).attr("id") );
    });

	$("input[name=DDJJ_cant").each(function(){

		if ( $(this).val() != '' && $(this).val() != null )
			cant.push( $(this).val() );
		else
			error.push( "Cantidad no puede ser vacío." );

    });

	$( "#GrillaRetencionesNuevasDDJJ input:checkbox.check-rete" ).each(function() {

		if( $( this ).is( ":checked" ) ){
			rete.push( $ ( this ).val() );
		}
	});

	if ( error.length == 0 )
	{

		//Paso los arreglos de código, base y cantidad a la función cargar
		f_cargar( "#ddjj_pjaxCalcularDJ", 0, 0, codigo, base, cant, rete, 1);

	} else
	{
		mostrarErrores( error, "#ddjj_errorSummary" );

		f_inhabilitarPresentarDJ();
	}
}

function f_inhabilitarPresentarDJ(){

	$( "#ddjj_btPresentarDJ" ).addClass( "disabled" );
	$( "#ddjj_btPresentarDJ" ).removeAttr( "onclick" );
}

function f_habilitarPresentarDJ(){

	$( "#ddjj_btPresentarDJ" ).removeClass( "disabled" );
	$( "#ddjj_btPresentarDJ" ).attr( "onclick", "f_enviarFormulario()" );
}

//Función que se utiliza al aceptar que existen períodos adeudados
function f_aceptaPeriodoAdeudado(){

	f_cerrarModalAdeudaDJ();

	f_cargar( "#ddjj_pjaxCargarDatos", 0, 1, [], [], [], [], 0 );

}

function f_cerrarModalAdeudaDJ(){

	$( "#ModalAdeudaDJ" ).modal( "hide" );
}

//Función que se utiliza al aceptar un dj rectificativa
function f_aceptaPeriodoExistente(){

	f_cerrarModalRectificativaDJ();

	f_cargar( "#ddjj_pjaxCargarDatos", 0, 0, [], [], [], [], 0 );

}

function f_cerrarModalRectificativaDJ(){

	$( "#ModalRectificativaDJ" ).modal( "hide" );
}

function f_cargar( pjax_id, verificaAdeuda, verificaExistencia, codigo, base, cant, rete, calculaDDJJ )
{
	var trib 				= $( "#ddjj_dlTrib" ).val(),//isNaN( $( "#ddjj_dlTrib" ).val() ) ? 0 : $( "#ddjj_dlTrib" ).val(),
		objeto_id 			= $( "#ddjj_txObjetoID").val(),
		objeto_nom 			= $( "#ddjj_txObjetoNom").val(),
		ib 					= $( "#ddjj_txIb").val(),
		$subcta				= $( "#ddjj_txSucursal" ),
		subcta				= isNaN( $( "#ddjj_txSucursal" ).val() ) ? 0 : $( "#ddjj_txSucursal" ).val(),
		anio 				= $( "#ddjj_txAnio").val(),
		cuota 				= $( "#ddjj_txCuota").val(),
		fchpresentacion 	= $( "#ddjj-fchpresenta").val(),
		fchvencimiento 		= String($("#ddjj-fchvenc").val()),
		tipo				= $( "#ddjj_dlTipo" ).val(),
		fiscaliza			= $( "#ddjj_txFiscaliza" ).val(),
		aplicaBonificacion 	= $( "#ddjj_ckBonificacion" ).is( ":checked" ) ? 1 : 0 ,
		saldoAFavor			= $( "#ddjj_txSaldoFavor" ).val(),
		error 				= new Array(),
		datos 				= {};

	ocultarErrores( "#ddjj_errorSummary" );

	if( trib == null ){
		error.push( "Ingrese un tributo." );
	} else {

		if( objeto_id == '' ){
			error.push( "Ingrese un objeto." );
		}

	}

	if (anio == '')
		error.push( "Ingrese un año." );

	if (cuota == '')
		error.push( "Ingrese una cuota." );

	if( !$subcta.hasClass( "solo-lectura" ) ){
		if( subcta <= 0 ){
			error.push( "Ingrese una sucursal." );
		}
	}

	if (error != '')
		mostrarErrores( error, "#ddjj_errorSummary" );
	else
	{
		$.pjax.reload({
			container 	: pjax_id,
			type		: "GET",
			replace		: false,
			push		: false,
			timeout 	: 100000,
			data : {
				"Ddjj[trib_id]"			: trib,
				"Ddjj[obj_id]"			: objeto_id,
				"Ddjj[obj_nom]"			: objeto_nom,
				"Ddjj[ib]"				: ib,
				"Ddjj[subcta]"			: subcta,
				"Ddjj[anio]"			: anio,
				"Ddjj[cuota]"			: cuota,
				"Ddjj[fchpresenta]"		: fchpresentacion,
				"Ddjj[fchvenc]"			: fchvencimiento,
				"Ddjj[tipo]"			: tipo,
				"Ddjj[fiscaliza]"		: fiscaliza,
				"verificaAdeuda"		: verificaAdeuda,
				"verificaExistencia"	: verificaExistencia,
				"codigo"				: codigo,
				"base"					: base,
				"cant"					: cant,
				"calculaDDJJ"			: calculaDDJJ,
				"aplicaBonificacion" 	: aplicaBonificacion,
				"saldoAFavor"			: saldoAFavor,
				"rete"					: rete,
			},
		});

	}
}

function f_ajustaAlturaCalcular(){

	var $left  = $( "#ddjj_divCalcular_left" ),
		$right = $( "#ddjj_divCalcular_right" );

	if( parseFloat( $left.css( "height" ) ) > parseFloat( $right.css( "height" ) ) ){

		$right.css( "height", parseFloat( $left.css( "height" ) ) );

	} else {

		$left.css( "height", parseFloat( $right.css( "height" ) ) );
	}
}


function f_agregarRetencion(){

	ocultarErrores( "#ddjj_errorSummary" );

	$.pjax.reload({
		container	:"#ddjj_PjaxModalRete",
		type 		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data:{
			"obj_id"	: $( "#ddjj_txObjetoID" ).val(),
		},
	});

}

$( "#ddjj_PjaxModalRete" ).on( "pjax:end", function() {

	$( "#modalNuevaRetencion" ).modal( "show" );
});

function f_eliminarRetencion( rete_id ){

	var obj_id 	= $( "#ddjj_txObjetoID" ).val(),
		trib_id	= $( "#ddjj_trib" ).val(),
		anio	= $( "#ddjj_txAnio" ).val(),
		cuota	= $( "#ddjj_txCuota" ).val();

	ocultarErrores( "#ddjj_errorSummary" );

	$.pjax.reload({
		container	:"#PjaxCargarRete",
		type 		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data:{
			"rete_id"	: rete_id,
			"obj_id"	: obj_id,
			"trib_id"	: trib_id,
			"anio"		: anio,
			"cuota"		: cuota,
			"eliminar"	: 1,
		},
	});

}

$( "#PjaxABMRete" ).on( "pjax:end", function() {

	$.pjax.reload( "#PjaxCargarRete" );
});

$( document ).ready( function() {

	f_ajustaAlturaCalcular();

	f_cambiaTributoObjeto();
});

$( document ).on( "pjax:end", function() {

	f_ajustaAlturaCalcular();

});

function f_enviarFormulario(){

	$( "#ddjj_form" ).submit();
}

function cambiarChecksGrillaRetenciones()
{

	f_inhabilitarPresentarDJ();

	var checks = $('#GrillaRetencionesNuevasDDJJ input[type="checkbox"]');

	if ( $("#ddjj_ckRetencionesTodas").is(":checked"))
	{
		checks.each(function() {

			checks.prop('checked','checked');	

		});
	} else
	{
		checks.each(function() {

			checks.prop('checked','');	

		});
	}
}

</script>
