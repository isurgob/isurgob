<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\controllers\ctacte\DdjjController;

/**
 * Forma que se dibuja para mostrar las retenciones asociadas a una DJ.
 * Recibo:
 *			=> $dataProviderReteCargadas	-> Listado de retenciones cargadas.
 */

?>
<style>

.div_grilla {

	margin-bottom: 10px;
}
</style>

<!-- INICIO Div Principal -->
<div class="ddjj_cargarRete">

<?php
ActiveForm::begin([
	'id' => 'DDJJ_rete_form',
	'action'	=> ['rete'],
]);
?>



</div>
<!-- FIN Grillas Retenciones -->



<?php ActiveForm::end(); ?>


</div>
<!-- FIN Div Principal -->



<script>

function f_cambiaCheck(){

    var $checks = $( "#GrillaRetencionesNuevasDDJJ input:checkbox:checked" );

    $( "#ddjj_cargarRete_errorSummary" ).css( "display", "none" );

    if( $checks.length == 0 ){
		$( "#btAgregarRetenciones" ).attr( "disabled", false );
        $( "#ddjj_cargarRete_btAceptar" ).attr( "disabled", true );
    } else {
		$( "#btAgregarRetenciones" ).attr( "disabled", true );
        $( "#ddjj_cargarRete_btAceptar" ).attr( "disabled", false );
    }
}

function f_agregarRetencion(){

	$( "#modalNuevaRetencion" ).modal( "show" );
}

function f_btAceptar()
{
    var error = new Array(),
        rete = new Array(),
        $checks = $( "#GrillaRetencionesNuevasDDJJ input:checkbox:checked" );

    $checks.each(function() {
        rete.push( $(this).attr( "id" ) );
    });

    $.pjax.reload({
        container: "#PjaxCargarRete",
        type: "GET",
        replace: false,
        push: false,
        data:{
            "retenciones": rete,
        },
    });

}

$( document ).ready( function() {

    f_cambiaCheck();
})
</script>
