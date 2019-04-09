<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

/**
 * Forma que se utiliza para el ingreso de domicilio en Comercio.
 */
?>


<!-- INICIO Div Domicilios -->
<div id="comer_form_divDomicilios">

	<table border="0">
		<tr>
			<td width="75px"><label>Parcelario:</label></td>
			<td>
				<?=
					Html::button( '<i class="glyphicon glyphicon-search"></i>', [
						'id'		=> 'comer_form_btDomicilioParcelario',
						'class' 	=> 'bt-buscar',
						'disabled' 	=> in_array( $action, [ 1, 2 ] ),
						'onclick'	=> 'f_btDomicilioParcelario()',
					]);
				?>
			</td>
			<td>
				<?=
					Html::input( 'text', 'txDomicilioParcelario', $modelDomicilioParcelario->domicilio, [
						'id'	=> 'comer_form_txDomicilioParcelario',
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width:435px',
						'tabIndex'	=> '-1',
					]);
				?>
			</td>
			<td>
				<?=
					Html::button( 'Copiar', [
						'class'		=> 'btn btn-success' . ( in_array( $action, [ 1, 2 ] ) ? ' disabled' : '' ),
						'onclick'	=> 'f_copiarDomicilio()',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Postal:</label></td>
			<td>
				<?=
					Html::button( '<i class="glyphicon glyphicon-search"></i>', [
						'id'		=> 'comer_form_btDomicilioPostal',
						'class' 	=> 'bt-buscar',
						'disabled' 	=> in_array( $action, [ 1, 2 ] ),
						'onclick'	=> 'f_btDomicilioPostal()',
					]);
				?>
			</td>
			<td colspan="2">
				<?=
					Html::input( 'text', 'txDomicilioPostal', $modelDomicilioPostal->domicilio, [
						'id'	=> 'comer_form_txDomicilioPostal',
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width:500px',
						'tabIndex'	=> '-1',
					]);
				?>
			</td>
		</tr>
	</table>

	<input type="hidden" name="arrayDomicilioParcelario" id="arrayDomicilioParcelario" value="<?= urlencode( serialize( $modelDomicilioParcelario ) ); ?>">
	<input type="hidden" name="arrayDomicilioPostal" id="arrayDomicilioPostal" value="<?= urlencode( serialize( $modelDomicilioPostal ) ); ?>">

</div>
<!-- FIN Div Domicilios -->

<?php

Pjax::begin([ 'id' => 'comer_form_pjaxCopiarDomicilio', 'enablePushState' => false, 'enableReplaceState' => false ]);

	//INICIO Ventana Modal Domicilio Postal
	Modal::begin([
		'id' => 'BuscaDomiP',
		'header' => '<h2>Búsqueda de Domicilio</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right'
		]
	]);

	echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelDomicilioPostal, 'tor' => 'OBJ']);

	Modal::end();
	//INICIO Ventana Modal Domicilio Postal

	//INICIO Ventana Modal Domicilio Parcelario
	Modal::begin([
		'id' => 'BuscaDomiC',
		'header' => '<h2>Búsqueda de Domicilio</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right'
		]
	]);

	echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelDomicilioParcelario, 'tor' => 'COM']);

	Modal::end();
	//INICIO Ventana Modal Domicilio Parcelario

Pjax::end();

?>

<script>

//INICIO Cargar Domicilio Parcelario
function f_btDomicilioParcelario(){

	$( "#BuscaDomiC" ).modal( "show" );
}
//FIN Cargar Domicilio Parcelario

//INICIO Cargar Domicilio Parcelario
function f_btDomicilioPostal(){

	$( "#BuscaDomiP" ).modal( "show" );
}
//FIN Cargar Domicilio Parcelario


function f_copiarDomicilio(){

	if ($("#comer_form_txDomicilioParcelario").val() !== "")
	{
		$("#comer_form_txDomicilioPostal").val( $("#comer_form_txDomicilioParcelario" ).val());

		$("#arrayDomicilioPostal").val( $("#arrayDomicilioParcelario" ).val() );

	}

	$.pjax.reload({
		container	: "#comer_form_pjaxCopiarDomicilio",
		type		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data:{
			"arrayDomicilioParcelario"	: $("#arrayDomicilioParcelario").val(),
		},
	});
}

</script>
