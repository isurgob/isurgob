<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\MaskedInput;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;

/**
 * Función que se dibuja como forma modal y se utiliza para agregar retenciones en "cargarRete.php".
 * Recibe:
 *  + $dj_id        -> Identificador de DJ.
 *  + $model        -> Modelo de DDJJ.
 *
 */

?>

<!-- INICIO Div Principal -->
<div id="usuarioweb_agregarRete_divPrincipal">

    <div id="formDetalle">

    <?=
        Html::input( 'hidden', 'txAction', $action, [
            'id' => 'usuarioweb_agregarRete_txAction',
        ]);
    ?>

    <?=
        Html::input( 'hidden', 'hidden', $model->desdeBD, [
            'id' => 'usuarioweb_agregarRete_txDesdeBD',
        ]);
    ?>

 	<table border="0">
        <tr>
            <td width="50px"><label>Agente:</label></td>
            <td>
                <?=
                    Html::label( $modelRete->ag_rete, null, [
                        'id'    => 'usuarioweb_agregarRete_agenteRetencion',
                        'class' => 'form-control solo-lectura',
                        'style' => 'width:80px;text-align: center',
                    ]);
                ?>
            </td>
            <td>
                <?=
                    Html::label( $modelRete->denominacion, null, [
                        'class' => 'form-control solo-lectura',
                        'style' => 'width:300px;text-align: left',
                    ]);
                ?>
            </td>
        </tr>

        <tr>
            <td><label>Período:</label></td>
            <td>
                <?=
                    Html::label( $anio, null, [
                        'id'    => 'usuarioweb_agregarRete_txAnio',
                        'class' => 'form-control solo-lectura',
                        'style' => 'width:40px;text-align: center',
                    ]);
                ?>
                <?=
                    Html::label( $mes, null, [
                        'id'    => 'usuarioweb_agregarRete_txMes',
                        'class' => 'form-control solo-lectura',
                        'style' => 'width:35px;text-align: center',
                    ]);
                ?>
            </td>
    </table>

    <div class="separador-horizontal" style="margin: 8px 0px"></div>

    <?=
        Html::input( 'hidden', 'txAgRete', $model->ag_rete, [
            'id'    => 'usuarioweb_agregarRete_agRete',
        ]);
    ?>

    <?php Pjax::begin([ 'id' => 'usuarioweb_agregarRete_pjaxCambiaDatos', 'enablePushState' => false, 'enableReplaceState' => false, 'timeout' => 100000 ]); ?>

    <table>
 		<tr>
 			<td width="50px"><label>CUIT:</label></td>
 			<td>
                <?=
                    MaskedInput::widget([
                        'model' => $model,
                        'attribute' => 'cuit',
                        'mask' => '99-99999999-9',
                        'options' => [
                            'class' => 'form-control' . ( $action == 0 ? '' : ' solo-lectura' ),
                            'id'    => 'usuarioweb_agregarRete_usuarioweb_agregarRete_cuitDetalle',
                            'style' => 'width:100px;text-align: center',
                            'tabIndex'  => ( $action == 0 ? '0' : '-1' ),
                            'onchange'  => 'f_cambiaDatos( $( this ).val() )',
                        ],
                    ]);
                ?>
            </td>
 		</tr>

 		<tr>
 			<td><label>Objeto:</label></td>
 			<td>
                <?=
                    Html::activeInput('text', $model, 'obj_id', [
                        'class' => 'form-control' . ( $action == 0 ? '' : ' solo-lectura' ),
                        'style' => 'width:100px; text-align: center; text-transform:uppercase;',
             			'id'    => 'usuarioweb_agregarRete_codigoObjetoDetalle',
                        'tabIndex'  => ( $action == 0 ? '0' : '-1' ),
                        'onchange'  => 'f_cambiaDatos( $( this ).val() )',
                    ]);
                ?>
            </td>
 			<td width="45px"></td>
 			<td><label>Denominación:</label></td>
 			<td colspan="4">
                <?=
                    Html::activeInput('text', $model, 'nombre', [
                        'class' => 'form-control solo-lectura',
                        'tabindex' => -1,
                        'id' => 'usuarioweb_agregarRete_nombreDetalle',
                        'style' => 'width:210px;',
                        'tabIndex' => '-1',
                    ]);
                ?>
            </td>
 		</tr>
    </table>

    <?php Pjax::end(); ?>

    <table>

 		<tr>
 			<td width="50px"><label>Lugar:</label></td>
 			<td></td>
 			<td>
                <?=
                    Html::activeInput( 'text', $model, 'lugar', [
                        'class'     => 'form-control' . ( $action == 2 ? ' solo-lectura' : '' ),
                        'style'     => 'width:175px;',
                        'id'        => 'usuarioweb_agregarRete_lugarDetalle',
                        'maxlength' => 30,
                        'tabindex'  => ( $action == 2 ? '-1' : '0' ),
                    ]);
                ?>
            </td>
 			<td width="5px"></td>
 			<td><label>Fecha:</label></td>
 			<td>
                <?=
                    DatePicker::widget([
                        'model'         => $model,
                        'attribute'     => 'fecha',
                        'dateFormat'    => 'dd/MM/yyyy',
                        'options'       => [
                            'class'     => 'form-control' . ( $action == 2 ? ' solo-lectura' : '' ),
                            'style'     => 'width:80px;text-align:center',
                            'id'        => 'usuarioweb_agregarRete_fechaDetalle',
                            'tabindex'  => ( $action == 2 ? '-1' : '0' ),
                        ],
                    ]);
                ?>
            </td>
 			<td width="5px"></td>
 			<td><label>Nº:</label></td>
 			<td>
                <?=
                    Html::activeInput('text', $model, 'numero', [
                        'class'         => 'form-control' . ( $action == 0 ? '' : ' solo-lectura' ),
                        'style'         => 'width:80px;',
                        'id'            => 'usuarioweb_agregarRete_numeroDetalle',
                        'onkeypress'    => 'return justNumbers( event )',
                        'tabindex'      => ( $action == 2 ? '-1' : '0' ),
                    ]);
                ?>
            </td>
 		</tr>

 		<tr>
 			<td colspan="4"><label>Comprobante:</label></td>
 			<td><label>Tipo:</label></td>
 			<td>
                <?=
                    Html::activeDropDownList($model, 'tcomprob', $tiposComprobantes, [
                        'class'     => 'form-control' . ( $action == 2 ? ' solo-lectura' : '' ),
                        'style'     => 'width:80px;',
                        'id'        => 'usuarioweb_agregarRete_tipoComprobanteDetalle',
                        'prompt'    => 'Seleccionar...',
                        'tabindex'  => ( $action == 2 ? '-1' : '0' ),
                    ]);
                ?>
            </td>
 			<td></td>
 			<td><label>Nº:</label></td>
 			<td>
                <?=
                    Html::activeInput('text', $model, 'comprob', [
                        'class'     => 'form-control' . ( $action == 2 ? ' solo-lectura' : '' ),
                        'style'     => 'width:80px;',
                        'id'        => 'numeroComprobanteDetalle',
                        'tabindex'  => ( $action == 2 ? '-1' : '0' ),
                    ]);
                ?>
            </td>
 		</tr>

 		<tr>
 			<td><label>Base:</label></td>
 			<td></td>
 			<td>
                <?=
                    Html::activeInput('text', $model, 'base', [
                        'class'         => 'form-control' . ( $action == 2 ? ' solo-lectura' : '' ),
                        'id'            => 'usuarioweb_agregarRete_baseDetalle',
                        'style'         => 'width:80px; text-align:right;',
                        'onkeypress'    => 'return justDecimalAndMenos( $( this ).val(), event )',
                        'onchange'      => 'actualizarusuarioweb_agregarRete_montoDetalle();',
                        'tabindex'      => ( $action == 2 ? '-1' : '0' ),
                    ]);
                ?>
            </td>
 			<td></td>
 			<td><label>Al&iacute;cuota:</label></td>
 			<td>
                <?=
                    Html::activeInput('text', $model, 'ali', [
                        'class'         => 'form-control' . ( $action == 2 ? ' solo-lectura' : '' ),
                        'id'            => 'usuarioweb_agregarRete_alicuotaDetalle',
                        'style'         => 'width:80px; text-align:right;',
                        'onkeypress'    => 'return justDecimal( $( this ).val(), event )',
                        'onchange'      => 'actualizarusuarioweb_agregarRete_montoDetalle();',
                        'tabindex'      => ( $action == 2 ? '-1' : '0' ),
                    ]);
                ?>
            </td>
 			<td></td>
 			<td><label>Monto:</label></td>
 			<td>
                <?=
                Html::activeInput('text', $model, 'monto', [
                    'class'         => 'form-control' . ( $action == 2 ? ' solo-lectura' : '' ),
                    'id'            => 'usuarioweb_agregarRete_montoDetalle',
                    'style'         => 'width:80px; text-align:right;',
                    'onkeypress'    => 'return justDecimalAndMenos( $( this ).val(), event )',
                    'tabindex'      => ( $action == 2 ? '-1' : '0' ),
                ]);
            ?>
        </td>
 		</tr>
 	</table>

     </div>

    <div style="margin-top:5px;" class="text-center">

       <?= Html::button('Aceptar', ['class' => ( $action == 2 ? 'btn btn-danger' : 'btn btn-success' ), 'onclick' => "f_grabarDetalle();"]); ?>

       &nbsp;&nbsp;&nbsp;

       <?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'f_cerrarVentanaModal()']); ?>
    </div>

    <div id="agregarRete_errorSummary" class="error-summary" style="margin-top: 8px; display: none">
    </div>

</div>
<!-- FIN Div Principal -->

<?php if( $model->hasErrors() ){ //Si existen errores ?>

    <script>var error = new Array();</script>

    <?php foreach( $model->getErrors() as $error ){ ?>

        <script>error.push( "<?= $error[0] ?>" );</script>

    <?php } ?>

    <script>mostrarErrores( error, "#agregarRete_errorSummary" );</script>

<?php } ?>

 <script>

 function f_cambiaDatos( dato ){

    $.pjax.reload({
        container   : "#usuarioweb_agregarRete_pjaxCambiaDatos",
        type        : "GET",
        replace     : false,
        push        : false,
        data:{
            "dato"      : dato,
        },
    });

 }

 function f_cerrarVentanaModal(){

     $( "<?= $idModal ?>" ).modal( "hide" );

 }

 function f_grabarDetalle(){

     var error  = new Array(),
         agente  = $( "#usuarioweb_agregarRete_agRete" ).val(),
         anio    = $( "#usuarioweb_agregarRete_txAnio" ).html(),
         mes     = $( "#usuarioweb_agregarRete_txMes" ).html(),
         obj_id  = $( "#usuarioweb_agregarRete_codigoObjetoDetalle" ).val(),
         nombre  = $( "#usuarioweb_agregarRete_nombreDetalle" ).val(),
         lugar   = $( "#usuarioweb_agregarRete_lugarDetalle" ).val(),
         fecha   = $( "#usuarioweb_agregarRete_fechaDetalle" ).val(),
         cuit    = $( "#usuarioweb_agregarRete_usuarioweb_agregarRete_cuitDetalle" ).val(),
         desdeBD = $( "#usuarioweb_agregarRete_txDesdeBD" ).val(),
         array_fecha = new Array(),
         numero  = isNaN( parseInt( $( "#usuarioweb_agregarRete_numeroDetalle" ).val() ) ) ? 0 : parseInt( $( "#usuarioweb_agregarRete_numeroDetalle" ).val() ),
         tipo    = $( "#usuarioweb_agregarRete_tipoComprobanteDetalle" ).val(),
         numeroC = isNaN( parseInt( $( "#numeroComprobanteDetalle" ).val() ) ) ? 0 : parseInt( $( "#numeroComprobanteDetalle" ).val() ),
         base    = isNaN( parseFloat( $( "#usuarioweb_agregarRete_baseDetalle" ).val() ) ) ? 0 : parseFloat( $( "#usuarioweb_agregarRete_baseDetalle" ).val() ),
         alicuota= isNaN( parseFloat( $( "#usuarioweb_agregarRete_alicuotaDetalle" ).val() ) ) ? 0 : parseFloat( $( "#usuarioweb_agregarRete_alicuotaDetalle" ).val() ),
         monto   = isNaN( parseFloat( $( "#usuarioweb_agregarRete_montoDetalle" ).val() ) ) ? 0 : parseFloat( $( "#usuarioweb_agregarRete_montoDetalle" ).val() ),
         retencion  = {},
         action     = $( "#usuarioweb_agregarRete_txAction" ).val();

     ocultarErrores( "#agregarRete_errorSummary" );

     if( cuit == '' || obj_id == '' ){
         error.push( "Ingrese un contribuyente." );
     }

     if( fecha == '' ){
         error.push( "Ingrese una fecha." );
     } else {

         array_fecha = fecha.split("/");

         if ( ( parseInt( "<?= $anio ?>" ) != parseInt( array_fecha[2] ) ) ||  parseInt( array_fecha[1] ) !=  parseInt( "<?= $mes ?>")  ){

             error.push( "La fecha ingresada no corresponde al período que se está declarando." );

         }
     }

     if( parseInt( numero ) == 0 ){
         error.push( "Ingrese un número." );
     }

     if( tipo == '' ){
         error.push( "Ingrese un tipo." );
     }

     if( parseInt( numeroC ) == 0 ){
         error.push( "Ingrese un número de comprobante." );
     }

     if( parseFloat( base ) == 0 ){
         error.push( "Ingrese una base." );
     }

     if( parseFloat( alicuota ) == 0 ){
         error.push( "Ingrese una alícuota." );
     }

     if( parseFloat( monto ) == 0 ){
         error.push( "Ingrese un monto." );
     }

     if( error.length > 0 ){

         mostrarErrores( error, "#agregarRete_errorSummary" );

      } else {

            f_cerrarVentanaModal();

            $.pjax.reload({
                container   : "<?= $pjaxAActualizar ?>",
                type        : "GET",
                replace     : false,
                push        : false,
                timeout     : 100000,
                data:{
                    "RetencionDetalle[ag_rete]"   : agente,
                    "RetencionDetalle[anio]"      : anio,
                    "RetencionDetalle[mes]"       : mes,
                    "RetencionDetalle[obj_id]"    : obj_id,
                    "RetencionDetalle[nombre]"    : nombre,
                    "RetencionDetalle[numero]"    : numero,
                    "RetencionDetalle[lugar]"     : lugar,
                    "RetencionDetalle[fecha]"     : fecha,
                    "RetencionDetalle[tcomprob]"  : tipo,
                    "RetencionDetalle[comprob]"   : numeroC,
                    "RetencionDetalle[base]"      : base,
                    "RetencionDetalle[ali]"       : alicuota,
                    "RetencionDetalle[monto]"     : monto,
                    "RetencionDetalle[cuit]"      : cuit,
                    "RetencionDetalle[desdeBD]"   : desdeBD,

                    "txAction"		              : action,

                },
            });
      }
  }

 function actualizarusuarioweb_agregarRete_montoDetalle(){

 	var base      = isNaN( parseFloat($("#usuarioweb_agregarRete_baseDetalle").val() ) ) ? 0 : parseFloat( $("#usuarioweb_agregarRete_baseDetalle").val());
 	var alicuota  = isNaN( parseFloat($("#usuarioweb_agregarRete_alicuotaDetalle").val() ) ) ? 0 : parseFloat($("#usuarioweb_agregarRete_alicuotaDetalle").val());
 	var monto     = 0;

 	if( !isNaN(base) && !isNaN(alicuota) && /*base > 0 &&*/ alicuota > 0 )
 		monto = base * alicuota / 100;

 	$("#usuarioweb_agregarRete_montoDetalle").val( monto.toFixed( 2 ) );
    $("#usuarioweb_agregarRete_baseDetalle").val( base.toFixed( 2 ) );
    $("#usuarioweb_agregarRete_alicuotaDetalle").val( alicuota.toFixed( 2 ) );
 }

 $( document ).ready(function() {
     actualizarusuarioweb_agregarRete_montoDetalle();
 });
 </script>
