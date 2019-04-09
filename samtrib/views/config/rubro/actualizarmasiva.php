<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\helpers\BaseUrl;

$title = 'Actualización Masiva';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Rubros', 'url' => ['config/rubro/index']];
$this->params['breadcrumbs'][] = $title;

?>

<div>	
	
	<h1><?= Html::encode($title) ?></h1>
	
	<!-- INICIO Mensajes -->
	<div id="ddjj_mensajes" class="alert-success mensaje" style="display:none">

	</div>
	<!-- FIN Mensajes -->
	
	<div class="pull-left" style='width:50%;border-right:1px solid #ddd;padding-right:10px;'>
		<div class="form" style='padding:5px 10px;margin-bottom:10px;'>
			<table width='100%' >
				<tr>
					<td width='27%'> <label> Nomeclador: </label> </td>
					<td colspan='3'>
						<?=
							Html::dropDownList( 'nomen_id', null, $nomecladores, [
								'id'=>'dlNomeclador',
								'style' => 'width:100%',
								'class' => 'form-control'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td>
						<?= 
							Html::radio( 'rbOpcion', true, [
								'label' => 'Todos',
								'value' => 0
							]);
						?>
					</td>
				</tr>
				<tr>
					<td>
						<?= 
							Html::radio( 'rbOpcion', false, [
								'label' => 'Grupo:',
								'value' => 1
							]);
						?>
					</td>
					<td colspan='3'>
						<?=
							Html::dropDownList( 'grupo', null, $grupos, [
								'id'=>'dlGrupo',
								'style' => 'width:100%',
								'class' => 'form-control solo-lectura'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td>
						<?= 
							Html::radio( 'rbOpcion', false, [
								'label' => 'Rubro desde:',
								'value' => 2
							]);
						?>
					</td>
					<td>
						<?= Html::input('text', 'rubro_desde', null, [
								'id'=>'txRubroDesde',
								'class'=>'form-control solo-lectura',
								'style'=>'width:90%;text-align:center;'
							]);
						?>
					</td>
					<td width='5%' > <label> hasta: </label> </td>
					<td align='right'>
						<?= Html::input('text', 'rubro_hasta', null, [
								'id'=>'txRubroHasta',
								'class'=>'form-control solo-lectura',
								'style'=>'width:90%;text-align:center;'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td colspan='4' align='center' >
						<hr>
						<?= Html::button('Buscar', [ 'id' => 'btBuscar', 'class' => 'btn btn-success' ]); ?>
					</td>
				</tr>
			</table>
		</div>
		
		<?php 
			
			Pjax::begin([ 'id' => 'pjaxGrillaRubro' ]);

				echo Html::errorSummary( $model, [
					'id' => 'filtro_errorSummary',
					'style' => 'margin-top: 8px;',
					'class' => "error-summary"
				]);
				
				echo GridView::widget([
						'id' => 'GrillaRubros',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dpRubros,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
								['class' => '\yii\grid\CheckboxColumn', 'checkboxOptions' => ['checked' => false ]],
								['attribute' => 'rubro_id', 'header' => 'Rubro', 'contentOptions' => [ 'width' => '1%' ] ],
								['attribute' => 'nombre', 'header' => 'Nombre' ],
								['attribute' => 'tcalculo_nom', 'header' => 'TCálculo' ],
								['attribute' => 'tminimo_nom', 'header' => 'TMínimo' ]
							],
					]);

			Pjax::end();
		?>
			
	</div>
	<div class="pull-right" style='width:49%'>
		<div class="form" style='padding:5px 10px;margin-bottom:10px;'>
			<label> Nueva Vigencia desde: </label>
			<?= Html::input('text', 'nueva_vigencia_anio_desde', null, [
					'id'=>'txNuevaVigenciaAnioDesde',
					'class'=>'form-control',
					'maxlength'=> 4,
					'onkeypress'=>'return justNumbers(event)',
					'style'=>'width:12%;text-align:center;'
				]);
			?>
			<?= Html::input('text', 'nueva_vigencia_mes_desde', null, [
					'id'=>'txNuevaVigenciaMesDesde',
					'class'=>'form-control',
					'maxlength'=> 3,
					'onkeypress'=>'return justNumbers(event)',
					'style'=>'width:10%;text-align:center;'
				]);
			?>
			
			<label> hasta: </label>
			<?= Html::input('text', 'nueva_vigencia_anio_hasta', null, [
					'id'=>'txNuevaVigenciaAnioHasta',
					'class'=>'form-control',
					'maxlength'=> 4,
					'onkeypress'=>'return justNumbers(event)',
					'style'=>'width:12%;text-align:center;'
				]);
			?>
			<?= Html::input('text', 'nueva_vigencia_mes_hasta', null, [
					'id'=>'txNuevaVigenciaMesHasta',
					'class'=>'form-control',
					'maxlength'=> 3,
					'onkeypress'=>'return justNumbers(event)',
					'style'=>'width:10%;text-align:center;'
				]);
			?>
			<?= Html::checkbox('cortar_vigencia', false, [ 
					'id' => 'ckCortarVigencia', 
					'label'=>'Cortar Vigencia si se superponen',
					
				]);
			?>
			<br>
			<?= Html::checkbox('eliminar_vigencia', false, [ 
					'id' => 'ckEliminarVigencia', 
					'label'=>'Sólo Eliminar Vigencia',
					'disabled' => true
				]);
			?>
		</div>
		
		<?= 
			Html::radio( 'rbOpcionNuevaFormula', true, [
				'label' => 'Tomar referencia',
				'value' => 0,
				'id' => 'rbOpcionNuevaFormula'
			]);
		?>
		<div id='divTomarReferencia' class="form" style='padding:5px 10px;margin-bottom:10px;'>
			<table width='100%'>
				<tr>
					<td> <label>Período:</label> </td>
					<td>
						<?= Html::input('text', 'referencia_anio', null, [
								'id'=>'txReferenciaAnio',
								'class'=>'form-control',
								'maxlength'=> 4,
								'onkeypress'=>'return justNumbers(event)',
								'style'=>'width:15%;text-align:center;'
							]);
						?>
						<?= Html::input('text', 'referencia_mes', null, [
								'id'=>'txReferenciaMes',
								'class'=>'form-control',
								'maxlength'=> 3,
								'onkeypress'=>'return justNumbers(event)',
								'style'=>'width:15%;text-align:center;'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td><label>Porc.S/Fijo:</label></td>
					<td>
						<?= Html::input('text', 'porc_fijo', null, [
								'id'=>'txPorcFijo',
								'class'=>'form-control',
								'maxlength'=> 4,
								'onkeypress' => 'return justDecimal( $( this ).val(), event )',
								'style'=>'width:32%;text-align:center;'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td><label>Porc.S/Mínimo:</label></td>
					<td>
						<?= Html::input('text', 'porc_minimo', null, [
								'id'=>'txPorcMinimo',
								'class'=>'form-control',
								'maxlength'=> 4,
								'onkeypress' => 'return justDecimal( $( this ).val(), event )',
								'style'=>'width:32%;text-align:center;'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<?= Html::checkbox('con_decimales', false, [ 
								'id' => 'ckConDecimales', 
								'label'=>'Con Decimales'
							]);
						?>
					</td>
				</tr>
			</table>
		</div>
		
		<?= 
			Html::radio( 'rbOpcionNuevaFormula', false, [
				'label' => 'Definir Nueva Vigencia',
				'value' => 1,
				'id' => 'rbOpcionNuevaFormula'
			]);
		?>
		<div id='divNuevaFormula' class="form" style='padding:5px 10px;margin-bottom:10px;'>
			<table width='100%'>
				<tr>
					<td width='5%'> <label>Fórmula:</label> </td>
					<td>
						<?=
							Html::dropDownList( 'dlFormula', null, $formulas, [
								'style' => 'width:100%',
								'class' => 'form-control solo-lectura',
								'id'=>'dlFormula'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td><label>Mínimo:</label></td>
					<td>
						<?=
							Html::dropDownList( 'dlMinimo', null, $minimos, [
								'style' => 'width:100%',
								'class' => 'form-control solo-lectura',
								'id'=>'dlMinimo'
							]);
						?>
					</td>
				</tr>
			</table>	
			<table width='100%'>
				<tr>
					<td><label>Alíc.</label></td>
					<td><label>Mínimo</label></td>
					<td><label>Mín.T.A.</label></td>
					<td><label id='lbFijo'>Fijo</label></td>
					<td><label>Cant.H.</label></td>
					<td><label>Porc.</label></td>
				</tr>
				<tr>
					<td>
						<?= Html::input('text', 'txAlic', null, [
								'id'=>'txAlic',
								'class' => 'form-control solo-lectura',
								'maxlength'=> 4,
								'onkeypress' => 'return justDecimal( $( this ).val(), event )',
								'style'=>'width:95%;text-align:right;'
							]);
						?>
					</td>
					<td>
						<?= Html::input('text', 'txMinimo', null, [
								'id'=>'txMinimo',
								'class' => 'form-control solo-lectura',
								'maxlength'=> 4,
								'onkeypress' => 'return justDecimal( $( this ).val(), event )',
								'style'=>'width:95%;text-align:right;'
							]);
						?>
					</td>
					<td>
						<?= Html::input('text', 'txMinimoTA', null, [
								'id'=>'txMinimoTA',
								'class' => 'form-control solo-lectura',
								'maxlength'=> 4,
								'onkeypress' => 'return justDecimal( $( this ).val(), event )',
								'style'=>'width:95%;text-align:right;'
							]);
						?>
					</td>
					<td>
						<?= Html::input('text', 'txFijo', null, [
								'id'=>'txFijo',
								'class' => 'form-control solo-lectura',
								'maxlength'=> 4,
								'onkeypress' => 'return justDecimal( $( this ).val(), event )',
								'style'=>'width:95%;text-align:right;'
							]);
						?>
					</td>
					<td>
						<?= Html::input('text', 'txCantH', null, [
								'id'=>'txCantH',
								'class' => 'form-control solo-lectura',
								'maxlength'=> 4,
								'onkeypress' => 'return justDecimal( $( this ).val(), event )',
								'style'=>'width:95%;text-align:right;'
							]);
						?>
					</td>
					<td>
						<?= Html::input('text', 'txPorc', null, [
								'id'=>'txPorc',
								'class' => 'form-control solo-lectura',
								'maxlength'=> 4,
								'onkeypress' => 'return justDecimal( $( this ).val(), event )',
								'style'=>'width:100%;text-align:right;'
							]);
						?>
					</td>
				</tr>
			</table>
		</div>
		
		<?= 
			Html::errorSummary( $model, [
				'id' => 'datos_errorSummary',
				'style' => 'margin-top: 8px;',
				'class' => "error-summary"
			]);
		?>
		<?=
			Html::button('Aceptar',[
				'id'=>'btAceptar',
				'class' => 'btn btn-success',
				'onclick' => 'f_grabar()'
			]);
		?>
		<?=
			Html::a('Cancelar', ['index'], [
				'id'=>'btCancelar',
				'class' => 'btn btn-primary'
			]);
		?>
	</div>
	
</div>

<?php if ( $mensaje != '' ){ //Si existen mensajes ?>

	<script>

		$( document ).ready(function() {
			mostrarMensaje( "<?= $mensaje; ?>", "#ddjj_mensajes" );
		});

	</script>

<?php } ?>

<script>

$(document).ready(function() {

    $('input[type=radio][name=rbOpcion]').change(function() {
        if ( this.value == 1 ) // grupo 
			$( "#dlGrupo" ).removeClass( "solo-lectura" );
		else 
			$( "#dlGrupo" ).addClass( "solo-lectura" );
			
		if ( this.value == 2 ){ // rubros
			$( "#txRubroDesde" ).removeClass( "solo-lectura" );
			$( "#txRubroHasta" ).removeClass( "solo-lectura" );
		}else {
			$( "#txRubroDesde" ).addClass( "solo-lectura" );	
			$( "#txRubroHasta" ).addClass( "solo-lectura" );	
		}	
		
    });
	
	$('#txRubroDesde').change(function() {
		var nomen_id = $( "#dlNomeclador" ).val();
		var rubroDesde = pad("0" + this.value, 7);
		
		if ( $( "#txRubroDesde" ).val().length < 8 ){ 
			$( "#txRubroDesde" ).val("");
			$( "#txRubroDesde" ).val( nomen_id + rubroDesde );
		}	
	});
	
	$('#txRubroHasta').change(function() {
		var nomen_id = $( "#dlNomeclador" ).val();
		var rubroHasta = pad("0" + this.value, 7);
		
		if ( $( "#txRubroHasta" ).val().length < 8 ){ 
			$( "#txRubroHasta" ).val("");
			$( "#txRubroHasta" ).val( nomen_id + rubroHasta );
		}	
	});
	
	$('#dlNomeclador').change(function() {
				
		$( "#txRubroDesde" ).val( "" );
		$( "#txRubroHasta" ).val( "" );
	});
	
	$('#btBuscar').click(function() {
				
		var opcion = $('input:radio[name=rbOpcion]:checked').val();
		var rubroDesde = $( "#txRubroDesde" ).val();
		var rubroHasta = $( "#txRubroHasta" ).val();
		
		ocultarErrores( "#filtro_errorSummary" );
		
		if ( opcion == 2 && ( rubroDesde == '' || rubroHasta == '' ) )
			mostrarErrores( [ 'Complete los Rangos de Rubros' ], "#filtro_errorSummary" );
		else {
			
			$.pjax.reload({
				container	:"#pjaxGrillaRubro",
				type 		: "POST",
				replace		: false,
				push		: false,
				timeout 	: 100000,
				data:{
					"nomen_id"		: $("#dlNomeclador").val(),
					"grupo"			: ( opcion == 1 ? $("#dlGrupo").val() : '' ),
					"rubro_desde"	: ( opcion == 2 ? rubroDesde : '' ),
					"rubro_hasta"	: ( opcion == 2 ? rubroHasta : '' )
				},
			});
		}	
		
	});
	
	$('input[type=checkbox][name=cortar_vigencia]').change(function() {
        
		$("#ckEliminarVigencia").attr( 'checked', false);
		$("#ckEliminarVigencia").attr( "disabled", (this.checked ? false : true) );
    });
	
	$('input[type=radio][name=rbOpcionNuevaFormula]').change(function() {
        
		if ( this.value == 0 ){
			$( "#divTomarReferencia input" ).removeClass( "solo-lectura" );
			$( "input[type=checkbox][name=con_decimales]" ).attr( "disabled", false );
			$( "#divNuevaFormula input" ).addClass( "solo-lectura" );
			$( "#divNuevaFormula select" ).addClass( "solo-lectura" );
		}
		if ( this.value == 1){
			$( "#divTomarReferencia input" ).addClass( "solo-lectura" );
			$( "input[type=checkbox][name=con_decimales]" ).attr( "disabled", true );
			$( "#divNuevaFormula input" ).removeClass( "solo-lectura" );
			$( "#divNuevaFormula select" ).removeClass( "solo-lectura" );
			
			$( "#dlMinimo" ).change();
		}		
    });
	
	$( "#dlMinimo" ).change(function() {
        
		var tminimo = this.value;	
		
		if ( tminimo == 3 || tminimo == 4 || tminimo == 6 )
			$( "#txCantH" ).removeClass( "solo-lectura" );
		else 
			$( "#txCantH" ).addClass( "solo-lectura" );
			
		$( "#txCantH" ).val( "" );
		
		if ( tminimo == 1 )
			$( "#txMinimo" ).addClass( "solo-lectura" );
		else 	
			$( "#txMinimo" ).removeClass( "solo-lectura" );
			
		if ( tminimo == 5 || tminimo == 6 ){
			$( "#txMinimoTA" ).removeClass( "solo-lectura" );
			$( "#lbFijo" ).text( "Porc." );
		}else {
			$( "#txMinimoTA" ).addClass( "solo-lectura" );
			$( "#txMinimoTA" ).val( "" );
			$( "#lbFijo" ).text( "Fijo" );
		}
    });
	
	$( ".pull-left" ).height( $( ".pull-right" ).height() ) ;
	
});

function f_grabar(){

	ocultarErrores( "#datos_errorSummary" );
	
	$.post( "<?= BaseUrl::toRoute('actualizarmasivagrabar');?>", 
		{
			nomeclador: $("#dlNomeclador").val(),
			nuevaVigenciaAnioDesde: $("#txNuevaVigenciaAnioDesde").val(), 
			nuevaVigenciaMesDesde: $("#txNuevaVigenciaMesDesde").val(), 
			nuevaVigenciaAnioHasta: $("#txNuevaVigenciaAnioHasta").val(), 
			nuevaVigenciaMesHasta: $("#txNuevaVigenciaMesHasta").val(), 
			cortarVigencia: $('#ckCortarVigencia').is(':checked'), 
			eliminarVigencia: $('#ckEliminarVigencia').is(':checked'), 
			nuevaFormula: $('input:radio[name=rbOpcionNuevaFormula]:checked').val(),
			referenciaAnio: $("#txReferenciaAnio").val(), 
			referenciaMes: $("#txReferenciaMes").val(), 
			porcFijo: $("#txPorcFijo").val(), 
			porcMinimo: $("#txPorcMinimo").val(), 
			conDecimales: $('#ckConDecimales').is(':checked'), 
			tFormula: $("#dlFormula").val(), 
			tMinimo: $("#dlMinimo").val(), 
			alicuota: $("#txAlic").val(), 
			minimo: $("#txMinimo").val(), 
			minimoTA: $("#txMinimoTA").val(), 
			fijo: $("#txFijo").val(), 
			cantH: $("#txCantH").val(), 
			porcentaje: $("#txPorc").val(),
			rubrosselct: $('#GrillaRubros').yiiGridView('getSelectedRows')
		}, 
		function( data ){
			datos = jQuery.parseJSON(data); 
			
			if ( datos.error != '' ){
				mostrarErrores( datos.error, "#datos_errorSummary" );
			}
		}
	);
	
}

</script>