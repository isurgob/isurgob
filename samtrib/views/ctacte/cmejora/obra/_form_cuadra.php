<?php 

use yii\helpers\Html;
use yii\helpers\BaseUrl;

?>

<table width='100%' >
	<tr>
		<td> <label> Cod.: </label> </td>
		<td>
			<?=
				Html::input( 'text', 'cuadra_id', $cuadra['cuadra_id'], [
					'id' => 'cuadra_id',
					'class' => 'form-control solo-lectura',
					'style' => 'width:95%',
					'tabIndex' => '-1'
				]);
			?>
		</td>
		<td> <label> Obra: </label> </td>
		<td align='right'>
			<?=
				Html::input( 'text', 'cuadra_obra_id', $cuadra['obra_id'], [
					'id' => 'cuadra_obra_id',
					'class' => 'form-control solo-lectura',
					'style' => 'width:15%',
					'tabIndex' => '-1'
				]);
			?>
			<?=
				Html::input( 'text', 'cuadra_obra_nom', $cuadra['obra_nom'], [
					'id' => 'cuadra_obra_nom',
					'class' => 'form-control solo-lectura',
					'style' => 'width:80%',
					'tabIndex' => '-1'
				]);
			?>
		</td>
	</tr>
	<tr>
		<td> <label> <?= $modelCuadra->str_s1 ?>: </label> </td>
		<td colspan='3'>
			<?=
				Html::input( 'text', 'cuadra_s1', $cuadra['s1'], [
					'id' => 'cuadra_s1',
					'class' => 'form-control'. ( in_array($CuadraAction, [1,2,3]) ? ' solo-lectura' : '' ),
					'style' => 'width:15%;text-align: center',
					'tabIndex' => ( in_array($CuadraAction, [1,2,3]) ? '-1' : '' ),
					'maxlength' => $modelCuadra->max_length_s1
				]);
			?>
			<label> <?= $modelCuadra->str_s2 ?>: </label>
			<?=
				Html::input( 'text', 'cuadra_s2', $cuadra['s2'], [
					'id' => 'cuadra_s2',
					'class' => 'form-control'. ( in_array($CuadraAction, [1,2,3]) ? ' solo-lectura' : '' ),
					'style' => 'width:15%;text-align: center',
					'tabIndex' => ( in_array($CuadraAction, [1,2,3]) ? '-1' : '' ),
					'maxlength' => $modelCuadra->max_length_s2
				]);
			?>
			<label> <?= $modelCuadra->str_s3 ?>: </label>
			<?=
				Html::input( 'text', 'cuadra_s3', $cuadra['s3'], [
					'id' => 'cuadra_s3',
					'class' => 'form-control'. ( in_array($CuadraAction, [1,2,3]) ? ' solo-lectura' : '' ),
					'style' => 'width:15%;text-align: center',
					'tabIndex' => ( in_array($CuadraAction, [1,2,3]) ? '-1' : '' ),
					'maxlength' => $modelCuadra->max_length_s3
				]);
			?>
			<label> Manz.: </label>
			<?=
				Html::input( 'text', 'cuadra_manz', $cuadra['manz'], [
					'id' => 'cuadra_manz',
					'class' => 'form-control'. ( in_array($CuadraAction, [1,2,3]) ? ' solo-lectura' : '' ),
					'style' => 'width:15%;text-align: center',
					'tabIndex' => ( in_array($CuadraAction, [1,2,3]) ? '-1' : '' ),
					'maxlength' => $modelCuadra->max_length_manzana
				]);
			?>
		</td>
	</tr>	
	<tr>
		<td> <label> Calle: </label> </td>
		<td>
			<?=
				Html::input( 'text', 'cuadra_calle_id', $cuadra['calle_id'], [
					'id' => 'cuadra_calle_id',
					'class' => 'form-control'. ( in_array($CuadraAction, [1,2,3]) ? ' solo-lectura' : '' ),
					'style' => 'width:95%;text-align: center',
					'tabIndex' => ( in_array($CuadraAction, [1,2,3]) ? '-1' : '' ),
					'onchange' => 'cambiaCodigoCuadra($(this).val());',
				]);
			?>
		</td>
		<td colspan='2'	>
			<?=
				Html::input( 'text', 'cuadra_calle_nom', $cuadra['calle_nom'], [
					'id' => 'cuadra_calle_nom',
					'class' => 'form-control'. ( in_array($CuadraAction, [1,2,3]) ? ' solo-lectura' : '' ),
					'style' => 'width:100%;',
					'tabIndex' => ( in_array($CuadraAction, [1,2,3]) ? '-1' : '' ),
				]);
			?>
		</td>
	</tr>
	<tr>
		<td> <label> Observación: </label> </td>
		<td colspan='3'>
			<?=
				Html::textarea( 'cuadra_obs', $cuadra['obs'], [
					'class'	=> 'form-control' . ( in_array( $CuadraAction, [1,2] ) ? ' solo-lectura' : '' ),
					'style'	=> 'width:100%;resize:none',
					'tabIndex' => ( in_array( $CuadraAction, [1,2] ) ? '-1' : '0' ),
					'spellcheck' => true,
					'maxlength' => 200
				]);
			?>
		</td>
	</tr>
	<tr> <td colspan="4"> <hr> </td> </tr>
	<tr>
		<td colspan="4">
			<?=
				Html::button( ( $CuadraAction == 2 ? 'Eliminar' : 'Aceptar' ), [
					'id'    => 'cuadra_btAceptar',
					'class' => ( $CuadraAction == 2 ? 'btn btn-danger' : 'btn btn-success' ),
					'onclick' => 'f_abmCuadraGrabar()'
				]);
			?>

			<?=
				Html::button( 'Cancelar', [
					'id'    => 'cuadra_btCancelar',
					'class' => 'btn btn-primary',
					'onclick' => '$( "#ModalCuadra" ).modal( "hide" )'
				]);
			?>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<div id="cuadra_errorSummary" class="error-summary" style="display:none;margin-top: 8px;">

 			</div>
		</td>
	</tr>
</table>

<script>

function f_abmCuadraGrabar(){

	var s1 = $("#cuadra_s1").val();
	var s2 = $("#cuadra_s2").val();
	var s3 = $("#cuadra_s3").val();
	var manz = $("#cuadra_manz").val();
	var calle_id = ( isNaN( parseInt( $( "#cuadra_calle_id" ).val() ) ) ? 0 : parseInt( $( "#cuadra_calle_id" ).val() ) );
	var calle_nom = $("#cuadra_calle_nom").val();
	var error = new Array();

	if (s1 == "")
		error.push( "Ingrese <?= $modelCuadra->str_s1 ?>" );
	if (s2 == "")
		error.push( "Ingrese <?= $modelCuadra->str_s2 ?>" );
	if (s3 == "")
		error.push( "Ingrese <?= $modelCuadra->str_s3 ?>" );
	if (manz == "")
		error.push( "Ingrese Manzana" );	
	if (calle_id == 0 || calle_nom == "")
		error.push( "Ingrese una calle." );

	if ( error.length > 0 )
        mostrarErrores( error, "#cuadra_errorSummary" );
    else {
        $.pjax.reload({
	        container: '#PjaxGrillaCuadra',
	        type: 'GET',
	        replace: false,
	        push: false,
	        data : {
	            "CuadraAction": "<?= $CuadraAction ?>",
	            "CuadraId": $("#cuadra_id").val(),
				"CuadraS1": s1,
	            "CuadraS2": s2,
				"CuadraS3": s3,
				"CuadraManz": manz,
				"CuadraCalleId": calle_id,
				"CuadraCalleNom": calle_nom,
				"CuadraObs": $("#cuadra_obs").val(),
				"listaCuadra": $("#listaCuadra").val()
	        }
	    });
    }

}

function cambiaCodigoCuadra( cod ) {

	$.get("<?= BaseUrl::toRoute('//objeto/objeto/nombrecalle');?>&cod=" + cod)
		.success(function(data){
			$("#cuadra_calle_nom").val(data);
	});

}

$(document).ready(function(){
	
	 $( "#PjaxGrillaCuadra" ).on( "pjax:end", function() {

        $( "#ModalCuadra" ).modal( "hide" );

    });
	
	//convierte el input en un elemento de autocompletado
	$("#cuadra_calle_nom").autocomplete({
		source: "<?= BaseUrl::toRoute('//objeto/objeto/sugerenciacalle'); ?>",
		select: function(event, ui){

			//console.log();
			var nombre= ui.item.value;

			$.get("<?= BaseUrl::toRoute('//objeto/objeto/codigocalle');?>&nombre=" + nombre)
			.success(function(data){
				$("#cuadra_calle_id").val(data);
			});
		}
	});

	//habilita que se vea el listado de autocompletado en el modal
	$(".ui-autocomplete").css("z-index", "5000");
	
});

</script>