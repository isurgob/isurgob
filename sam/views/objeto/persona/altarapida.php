<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\MaskedInput;
use app\utils\db\utb;
use yii\helpers\BaseUrl;

$domi->loc_id	= utb::samMuni()['loc_id'];
$domi->prov_id	= utb::getCampo("domi_localidad", "loc_id=" . $domi->loc_id, "prov_id");
$domi->pais_id	= utb::getCampo("domi_provincia", "prov_id=" . $domi->prov_id, "pais_id");

?>

<div class='form' style='padding:5px 10px;' >
	
	<table width='100%'>
		<tr>
			<td> <label> Nombre: </label> </td>
			<td colspan='7'>
				<?=
					Html::activeInput( 'text', $model, 'nombre', [
						'id'    => 'persona_Nombre',
						'class' => 'form-control',
						'style' => 'width: 100%; text-transform: uppercase',
						'maxlength' => 50
					]);
				?>
			</td>
		</tr>
		<tr>
			<td> <label> Tipo: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeDropDownList( $model, 'tipo', $tiposPersona, [
						'id'    => 'persona_Tipo',
						'class' => 'form-control',
						'style' => 'width: 98% '
					]);
				?>
			</td>
			<td> <label> Sexo: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeDropDownList( $model, 'sexo', utb::getAux('persona_tsexo'), [
						'id'    => 'persona_Sexo',
						'class' => 'form-control',
						'style' => 'width: 100% '
					]);
				?>
			</td>
		</tr>
		<tr>
			<td> <label> Tipo Doc.: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeDropDownList( $model, 'tdoc', utb::getAux('persona_tdoc'), [
						'id'    => 'persona_TDoc',
						'class' => 'form-control',
						'style' => 'width: 98% '
					]);
				?>
			</td>
			<td> <label> Nro. Doc.: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeInput( 'text', $model, 'ndoc', [
						'id'    => 'persona_NDoc',
						'class' => 'form-control',
						'style' => 'width: 100%;text-align:center',
						'maxlength' => 8,
						'onkeypress'    => 'return justNumbers( $( this ).val() )',
					]);
				?>
			</td>
		</tr>
		<tr>
			<td> <label> Sit.IVA: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeDropDownList( $model, 'iva', $tiposIVA, [
						'id'    => 'persona_TIVA',
						'class' => 'form-control',
						'style' => 'width: 98% ',
						'onchange' => 'f_selectTIva()'
					]);
				?>
			</td>
			<td> <label id='lbCuitDesc' > CUIL: </label> </td>
			<td colspan='3'>
				<?=
					MaskedInput::widget([
						'model'     => $model,
						'attribute' => 'cuit',
						'mask'      => '99-99999999-9',
						'options'   => [
							'id'    => 'persona_Cuit',
							'class' => 'form-control',
							'style' => 'width: 100%; text-align: center'
						]
					]);
				?>
			</td>
		</tr>
		<tr>
			<td> <label> Teléfono: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeInput( 'text', $model, 'tel', [
						'id'    => 'persona_Tel',
						'class' => 'form-control',
						'style' => 'width: 98%;',
						'maxlength' => 15
					]);
				?>
			</td>
			<td> <label> Celuar: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeInput( 'text', $model, 'cel', [
						'id'    => 'persona_Cel',
						'class' => 'form-control',
						'style' => 'width: 100%;',
						'maxlength' => 15
					]);
				?>
			</td>
		</tr>
		<tr>
			<td> <label> Mail: </label> </td>
			<td colspan='7'>
				<?=
					Html::activeInput( 'text', $model, 'mail', [
						'id'    => 'persona_Mail',
						'class' => 'form-control',
						'style' => 'width: 100%;',
						'maxlength' => 40
					]);
				?>
			</td>
		</tr>
		
		<tr> <td colspan='8'> <hr> </td> </tr>
		
		<tr>
			<td> <label> Provincia: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeDropDownList( $domi, 'prov_id', utb::getAux('domi_provincia', 'prov_id', 'nombre', 0, 'pais_id=' . $domi->pais_id), [
						'id'    => 'domi_provincia',
						'class' => 'form-control',
						'style' => 'width: 98% ',
						'onchange' => 'cambioProvincia()'
					]);
				?>
			</td>
			<td> <label> Localidad: </label> </td>
			<td colspan='3'>
				<?php 
					Pjax::begin([ 'id' => 'pjaxLocalidad' ]);
						$domi->prov_id = Yii::$app->request->post( 'prov_id', $domi->prov_id );
						
						echo Html::activeDropDownList( $domi, 'loc_id', utb::getAux('domi_localidad', 'loc_id', 'nombre', 0, 'prov_id=' . $domi->prov_id), [
							'id'    => 'domi_localidad',
							'class' => 'form-control',
							'style' => 'width: 100% '
						]);
					Pjax::end();	
				?>
			</td>
		</tr>
		<tr>
			<td> <label> Calle: </label> </td>
			<td>
				<?= Html::activeInput( 'text', $domi, 'calle_id', [
						'class' => 'form-control',
						'id'=>'domi_calle_id', 
						'onkeypress'=>'return justNumbers(event);', 
						'maxlength' => 3, 
						'style'=>'width:95%',
						'onchange' => 'cambioCalle()'
						
					]); 
				?>
			</td>
			<td colspan='6'>	
				<?= Html::activeInput( 'text', $domi, 'nomcalle', [
						'class' => 'form-control',
						'id' => 'domi_calle_nom',
						'style'=>'width:100%;'
					]); 
				?>
			</td>
		</tr>
		<tr>
			<td> <label> Puerta: </label> </td>
			<td>
				<?= Html::activeInput( 'text', $domi, 'puerta', [
						'class' => 'form-control',
						'id'=>'domi_puerta', 
						'onkeypress'=>'return justNumbers(event);', 
						'maxlength' => 4, 
						'style'=>'width:95%',
						
					]); 
				?>
			</td>
			<td> <label> C.P.: </label> </td>
			<td>
				<?= Html::activeInput( 'text', $domi, 'cp', [
						'class' => 'form-control',
						'id'=>'domi_cp', 
						'maxlength' => 8, 
						'style'=>'width:95%',
						
					]); 
				?>
			</td>
			<td> <label> Piso: </label> </td>
			<td>
				<?= Html::activeInput( 'text', $domi, 'piso', [
						'class' => 'form-control',
						'id'=>'domi_piso', 
						'maxlength' => 5, 
						'style'=>'width:95%',
						
					]); 
				?>
			</td>
			<td> <label> Dpto./Casa: </label> </td>
			<td>
				<?= Html::activeInput( 'text', $domi, 'dpto', [
						'class' => 'form-control',
						'id'=>'domi_dpto', 
						'maxlength' => 5, 
						'style'=>'width:100%',
						
					]); 
				?>
			</td>
		</tr>
	</table>
	
</div>

<!-- INICIO DIV Botones -->
<div id="persona_form_divBotones" style='margin-top:10px;' align='center'>

	<?=
		Html::button( 'Aceptar' , [
			'id'    => 'persona_btAceptar',
			'class' => 'btn btn-success',
			'style' => 'margin-right: 20px',
			'onclick' => 'f_personaGrabar()'
		]);
	?>

	<?=
		Html::button( 'Cancelar', [
			'id'    => 'persona_btCancelar',
			'class' => 'btn btn-primary',
			'onclick' => 'cerrarModal()'
		]);
	?>

</div>
<!-- INICIO DIV Botones -->
	
<div id="persona_errorSummary" class="error-summary" style="margin-top: 8px; display:none"></div>

<script>

function cambioProvincia(){
	
	var prov_id = $("#domi_provincia").val();
	
	$.pjax.reload({
        container   : "#pjaxLocalidad",
        type        : "POST",
        replace     : false,
        push        : false,
        timeout     : 100000,
        data:{
            "prov_id"  : prov_id
        },
    });

}

function cambioCalle(){

	var calle = $("#domi_calle_id").val();
	
	$.get("<?= BaseUrl::toRoute('//objeto/objeto/nombrecalle');?>&cod=" + calle)
	.success(function(data){
		$("#domi_calle_nom").val(data);
	});

}

function f_personaGrabar(){

	var nombre = $("#persona_Nombre").val();
	var tipo = $("#persona_Tipo").val();
	var sexo = $("#persona_Sexo").val();
	var tdoc = $("#persona_TDoc").val();
	var ndoc = $("#persona_NDoc").val();
	var tiva = $("#persona_TIVA").val();
	var cuit = $("#persona_Cuit").val();
	var tel = $("#persona_Tel").val();
	var cel = $("#persona_Cel").val();
	var mail = $("#persona_Mail").val();
	var prov = $("#domi_provincia").val();
	var loc = $("#domi_localidad").val();
	var calle_id = $("#domi_calle_id").val();
	var calle_nom = $("#domi_calle_nom").val();
	var puerta = $("#domi_puerta").val();
	var cp = $("#domi_cp").val();
	var piso = $("#domi_piso").val();
	var dpto = $("#domi_dpto").val();
	
	$.post( "<?= BaseUrl::toRoute('//objeto/persona/altarapida');?>", { 
			"PersonaAltaRapida[nombre]": nombre,
			"PersonaAltaRapida[tipo]": tipo,
			"PersonaAltaRapida[sexo]": sexo,
			"PersonaAltaRapida[tdoc]": tdoc,
			"PersonaAltaRapida[ndoc]": ndoc,
			"PersonaAltaRapida[iva]": tiva,
			"PersonaAltaRapida[cuit]": cuit,
			"PersonaAltaRapida[tel]": tel,
			"PersonaAltaRapida[cel]": cel,
			"PersonaAltaRapida[mail]": mail,
			"PersonaAltaRapida[prov_id]": prov,
			"PersonaAltaRapida[loc_id]": loc,
			"PersonaAltaRapida[calle_id]": calle_id,
			"PersonaAltaRapida[calle_nom]": calle_nom,
			"PersonaAltaRapida[puerta]": puerta,
			"PersonaAltaRapida[cp]": cp,
			"PersonaAltaRapida[piso]": piso,
			"PersonaAltaRapida[dpto]": dpto
		} 
	).success(function(data){
		datos = jQuery.parseJSON(data); 
		
		if ( datos.error == "" ) {
			ocultarErrores( "#persona_errorSummary" );
			$("#<?= $campoPersona ?>").val( datos.objeto );
			cerrarModal();
			
		}else {
			mostrarErrores( datos.error, "#persona_errorSummary" );
		}
		
	});
	
}

function cerrarModal(){

	$("#<?= $nombreModal ?>").modal( "hide" );
	
}

$( '#<?= $nombreModal ?>' ).on('show.bs.modal', function (e) {
	
	ocultarErrores( "#persona_errorSummary" );
})

$(document).ready(function(){
		
	$("#domi_calle_nom").autocomplete({
		source: "<?= BaseUrl::toRoute('//objeto/objeto/sugerenciacalle'); ?>",
		select: function(event, ui){

			//console.log();
			var nombre= ui.item.value;

			$.get("<?= BaseUrl::toRoute('//objeto/objeto/codigocalle');?>&nombre=" + nombre)
			.success(function(data){
				$("#domi_calle_id").val(data);
			});
		}
	});

	//habilita que se vea el listado de autocompletado en el modal
	$(".ui-autocomplete").css("z-index", "5000");
});

function f_selectTIva(){

	var iva = $("#persona_TIVA").val();
	
	if ( iva == 1 )
		$("#lbCuitDesc").text("CUIL:");
	else	
		$("#lbCuitDesc").text("CUIT:");
}

</script>