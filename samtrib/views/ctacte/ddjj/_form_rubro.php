<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use yii\jui\AutoComplete;

/**
 * Forma que se dibuja para el ABM de rubros en:
 *		-> Comercio ( o habilitaciones );
 *		-> Persona
 *
 * Recibe:
 *
 *	+ $model			-> Modelo de Rubro ( ComerRubro ).
 *	+ $action			-> Tipo de acción a ejecutarse.
 *		- 0 => Create
 *		- 1 => View
 *		- 2 => Delete
 *		- 3 => Update
 *	+ $esModal			-> Indica si la forma se dibuja como forma modal.
 *	+ $selectorModal	-> Nombre de la ventana modal desde la que se dibuja.
 *	+ $pjaxAActualizar	-> Pjax que se debe actualizar.
 */

?>

<!-- INICIO Div Principal -->
<div id="rubro_form_divPrincipal">

	<?php
		$form = ActiveForm::begin([
			'id'	=> 'rubro_form',
		]);
	?>

	<?= Html::input( 'hidden', 'txAction', $action ); ?>

	<!-- INICIO Div Datos -->
	<div>
		<table border="0">
			<tr>
				<td><label>Tributo:</label></td>
				<td colspan="6">
					<?=
						Html::activeDropDownList( $model, 'trib_id', $arrayTributos, [
							'id'		=> 'rubro_form_dlTributo',
							'class'		=> 'form-control' . ( $action != 0 ? ' solo-lectura' : '' ),
							'style'		=> 'width: 99%',
							'onchange'	=> 'f_rubro_cambiaTributo( $( this ).val(), false )',
							'tabIndex'	=> (  $action != 0 ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>

			<tr>
				<td><label>Rubro:</label></td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'rubro_id', [
							'id'		=> 'rubro_form_txRubroID',
							'class'		=> 'form-control' . (  $action != 0  ? ' solo-lectura' : '' ),
							'style'		=> 'width:80px; text-align: center',
							'tabIndex'	=> (  $action != 0  ? '-1' : '0' ),
							'onchange'	=> 'f_rubro_cambiaIDRubro( $( this ).val() )',
						]);
					?>
				</td>
				<td colspan="5">
					<?=
						Html::input('text', 'txRubroNom', $model->rubro_nom, [
							'id'		=> 'rubro_form_txRubroNom',
							'class'		=> 'form-control' . (  $action != 0  ? ' solo-lectura' : '' ),
							'style'		=> 'width: 300px;',
							'tabIndex'	=> (  $action != 0  ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>
				<td><label>Cantidad:</label></td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'cant', [
							'id'		=> 'rubro_form_txCantidad',
							'class'		=> 'form-control' . ( in_array( $action, [ 1, 2 ] ) ? ' solo-lectura' : '' ),
							'style'		=> 'width: 40px; text-align: center',
							'onkeypress'	=> 'return justNumbers( event )',
							'tabIndex'	=> ( in_array( $action, [ 1, 2 ] ) ? '-1' : '0' ),
						]);
					?>
				</td>
				<td><label>Desde:</label></td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'adesde', [
							'id'		=> 'rubro_form_txAnioDesde',
							'class'		=> 'form-control' . ( in_array( $action, [ 1, 2 ] ) ? ' solo-lectura' : '' ),
							'style'		=> 'width: 40px; text-align: center',
							'onkeypress'	=> 'return justNumbers( event )',
							'maxlength'	=> '4',
							'tabIndex'	=> ( in_array( $action, [ 1, 2 ] ) ? '-1' : '0' ),
						]);
					?>
					<?=
						Html::activeInput( 'text', $model, 'cdesde', [
							'id'		=> 'rubro_form_txCuotaDesde',
							'class'		=> 'form-control' . ( in_array( $action, [ 1, 2 ] ) ? ' solo-lectura' : '' ),
							'style'		=> 'width: 40px; text-align: center',
							'onkeypress'	=> 'return justNumbers( event )',
							'maxlength'	=> '3',
							'tabIndex'	=> ( in_array( $action, [ 1, 2 ] ) ? '-1' : '0' ),
						]);
					?>
				</td>
				<td width="20px"></td>
				<td><label>Hasta:</label></td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'ahasta', [
							'id'		=> 'rubro_form_txAnioHasta',
							'class'		=> 'form-control' . ( in_array( $action, [ 1, 2 ] ) ? ' solo-lectura' : '' ),
							'style'		=> 'width: 40px; text-align: center',
							'onkeypress'	=> 'return justNumbers( event )',
							'maxlength'	=> '4',
							'tabIndex'	=> ( in_array( $action, [ 1, 2 ] ) ? '-1' : '0' ),
						]);
					?>
					<?=
						Html::activeInput( 'text', $model, 'chasta', [
							'id'		=> 'rubro_form_txCuotaHasta',
							'class'		=> 'form-control' . ( in_array( $action, [ 1, 2 ] ) ? ' solo-lectura' : '' ),
							'style'		=> 'width: 40px; text-align: center',
							'onkeypress'	=> 'return justNumbers( event )',
							'maxlength'	=> '3',
							'tabIndex'	=> ( in_array( $action, [ 1, 2 ] ) ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>

			</tr>
				<td><label>Porc.:</label></td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'porc', [
							'id'		=> 'rubro_form_txPorcentaje',
							'class'		=> 'form-control' . ( in_array( $action, [ 1, 2 ] ) ? ' solo-lectura' : '' ),
							'style'		=> 'width: 60px; text-align: center',
							'onkeypress'	=> 'return justDecimal( $( this ).val(), event )',
							'maxlength'	=> '5',
							'tabIndex'	=> ( in_array( $action, [ 1, 2 ] ) ? '-1' : '0' ),
						]);
					?>
				</td>
				<td><label>Expe:</label></td>
				<td colspan="1">
					<?=
						Html::activeInput( 'text', $model, 'expe', [
							'id'		=> 'rubro_form_txExpediente',
							'class'		=> 'form-control' . ( in_array( $action, [ 1, 2 ] ) ? ' solo-lectura' : '' ),
							'style'		=> 'width: 120px; text-align: center',
							'maxlength'	=> '12',
							'tabIndex'	=> ( in_array( $action, [ 1, 2 ] ) ? '-1' : '0' ),
						]);
					?>
				</td>
				<td></td>
				<td colspan="2">
					<?=
						Html::activeCheckbox( $model, 'esPrincipal', [
							'id'		=> 'rubro_form_ckEsPrincipal',
							'value'		=> 1,
							'uncheck'	=> 2,
							'disabled'	=> in_array( $action,  [ 1, 2 ] ),
						]);
					?>
				</td>
			</tr>

			<tr>
				<td><label>Obs:</label></td>
				<td colspan="6">
					<?=
						Html::activeTextarea( $model, 'obs', [
							'id'		=> 'rubro_form_txObservacion',
							'class'		=> 'form-control' . ( in_array( $action, [ 1, 2 ] ) ? ' solo-lectura' : '' ),
							'style'		=> 'width: 99%; height: 60px; resize: none;',
							'maxlength'	=> '12',
							'tabIndex'	=> ( in_array( $action, [ 1, 2 ] ) ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>
		</table>
	</div>
	<!-- FIN Div Datos -->

	<?php ActiveForm::end(); ?>

	<!-- INICIO Div Botones -->
	<div id="rubro_form_divBotones">
		<?=
			Html::button( 'Grabar', [

				'class'	=> ( $action == 2 ? 'btn btn-danger' : 'btn btn-success' ),
				'onclick'	=> 'f_rubro_btAceptar()',
			]);
		?>

		&nbsp;&nbsp;

		<?=
			Html::button( 'Cancelar', [

				'class'	=> 'btn btn-primary',
				'onclick'	=> 'f_rubro_btCancelar()',
			]);
		?>
	</div>
	<!-- FIN Div Botones -->

	<!--INICIO Div Mensajes Error -->
	<?= $form->errorSummary( $model, [
		'id'	=> 'rubro_form_errorSummary',
		'style'	=> 'margin-right: 15px; margin-top: 8px',
	]); ?>
	<!--FIN Div Mensajes Error -->

</div>
<!-- FIN Div Principal -->

<script>

function f_rubro_cambiaTributo( trib_id, borrarDatos ){

	ocultarErrores( "#rubro_form_errorSummary" );

	if( borrarDatos || trib_id == 0 ){
		$( "#rubro_form_txRubroID" ).val( '' );
		$( "#rubro_form_txRubroNom" ).val( '' );
	}

	$( "#rubro_form_txRubroID" ).toggleClass( 'solo-lectura', trib_id == 0 || <?= $action ?> != 0 );
	$( "#rubro_form_txRubroNom" ).toggleClass( 'solo-lectura', trib_id == 0 || <?= $action ?> != 0 );

	//se actualiza la URL del autocomplete
	$("#rubro_form_txRubroNom").autocomplete({
		source: "<?= BaseUrl::toRoute('//objeto/rubro/sugerenciarubro'); ?>&trib_id=" + trib_id,
	});

}

function f_rubro_cambiaIDRubro( rubro_id ){

	ocultarErrores( "#rubro_form_errorSummary" );

	var trib_id = $("#rubro_form_dlTributo option:selected").val();

	$.get("<?= BaseUrl::toRoute('//objeto/rubro/nombrerubro'); ?>&rubro_id=" + rubro_id + "&trib_id=" + trib_id)
	.success( function( data ){

		if( data == '' ){
			mostrarErrores( ["El tributo ingresado no es válido."], "#rubro_form_errorSummary" );
			$("#rubro_form_txRubroID").val( "" );
			$("#rubro_form_txRubroID").focus();
		} else {
			$("#rubro_form_txRubroNom").val( data );
		}

	});
}

function f_rubro_btCancelar(){

	$( "<?= $selectorModal ?>" ).modal( "hide" );
}

function f_rubro_btAceptar(){

	var trib_id		= $( "#rubro_form_dlTributo option:selected" ).val(),
		rubro_id	= $( "#rubro_form_txRubroID" ).val(),
		rubro_nom	= $( "#rubro_form_txRubroNom" ).val(),
		adesde		= $( "#rubro_form_txAnioDesde" ).val(),
		cdesde		= $( "#rubro_form_txCuotaDesde" ).val(),
		ahasta		= $( "#rubro_form_txAnioHasta" ).val(),
		chasta		= $( "#rubro_form_txCuotaHasta" ).val(),
		cant		= $( "#rubro_form_txCantidad" ).val(),
		porc		= $( "#rubro_form_txPorcentaje" ).val(),
		esPrincipal	= $( "#rubro_form_ckEsPrincipal" ).is( ":checked" ) ? 1 : 2,
		expe		= $( "#rubro_form_txExpediente" ).val(),
		obs			= $( "#rubro_form_txObservacion" ).val(),

		//Se obtienen de "Rubro"
		baja		= $( "#rubro_filtroBaja" ).is( ":checked" ) ? 1 : 0,
		vigente		= $( "#rubro_filtroVigente" ).is( ":checked" ) ? 1 : 0;

		error 		= new Array();

	$.pjax.reload({
		container	: "#<?= $pjaxAActualizar ?>",
		type		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 1000000,
		data:{
			"filtroBaja"			: baja,
			"filtroVigente"			: vigente,
			"txAction"				: <?= $action ?>,
			"ComerRubro[trib_id]"	: trib_id,
			"ComerRubro[rubro_id]"	: rubro_id,
			"ComerRubro[rubro_nom]" : rubro_nom,
			"ComerRubro[adesde]"	: adesde,
			"ComerRubro[cdesde]"	: cdesde,
			"ComerRubro[ahasta]"	: ahasta,
			"ComerRubro[chasta]"	: chasta,
			"ComerRubro[cant]"		: cant,
			"ComerRubro[porc]"		: porc,
			"ComerRubro[tipo]"		: esPrincipal,
			"ComerRubro[expe]"		: expe,
			"ComerRubro[obs]"		: obs,
		},
	});

}

$( document ).ready( function() {

	var trib_id = $("#rubro_form_dlTributo option:selected").val();

	//convierte el input en un elemento de autocompletado
	$("#rubro_form_txRubroNom").autocomplete({
		source: "<?= BaseUrl::toRoute('//objeto/rubro/sugerenciarubro'); ?>",
		select: function(event, ui){	//Cuando se selecciona un elemento del autocomplete

			var nombre = ui.item.value;

			$.get("<?= BaseUrl::toRoute('//objeto/rubro/codigorubro');?>&nombre=" + nombre)
			.success(function( data ){
				$("#rubro_form_txRubroID").val( data );
			});
		}
	});

	//habilita que se vea el listado de autocompletado en el modal
	$(".ui-autocomplete").css("z-index", "5000");

	f_rubro_cambiaTributo( trib_id, false );

})
</script>
