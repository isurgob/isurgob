<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\MaskedInput;
use yii\widgets\ActiveForm;

/**
 * Función que se dibuja como forma modal y se utiliza para agregar retenciones en "cargarRete.php".
 * Recibe:
 *  + $dj_id        -> Identificador de DJ.
 *  + $model        -> Modelo de DDJJ.
 *
 */

 //Pjax::begin(['id' => 'pjaxFormDetalleRetencion', 'enableReplaceState' => false, 'enablePushState' => false]);

ActiveForm::begin([
    'id'    => 'agregarRete_form',
    'action'    => ['rete', 'dj_id' => $dj_id],
]);

 ?>
 <div id="formDetalle">

 	<table border="0">
 		<tr>
 			<td><label>CUIT:</label></td>
 			<td width="5px"></td>
 			<td>
                <?=
                    MaskedInput::widget([
                        'model' => $model,
                        'attribute' => 'cuit',
                        'mask' => '99-99999999-9',
                        'options' => [
                            'class' => 'form-control solo-lectura',
                            'id' => 'cuitDetalle',
                            'style' => 'width:100px;',
                            'tabIndex'  => '-1',
                        ],
                    ]);
                ?>
            </td>
 		</tr>

 		<tr>
 			<td><label>Objeto:</label></td>
 			<td></td>
 			<td>
                <?=
                    Html::activeInput('text', $model, 'obj_id', [
                        'class' => 'form-control solo-lectura',
                        'style' => 'width:100px; text-transform:uppercase;',
             			'id' => 'codigoObjetoDetalle',
                        'tabIndex'  => '-1',
                    ]);
                ?>
            </td>
 			<td></td>
 			<td><label>Denominación:</label></td>
 			<td colspan="4">
                <?=
                    Html::activeInput('text', $model, 'denominacion', [
                        'class' => 'form-control solo-lectura',
                        'tabindex' => -1,
                        'id' => 'denominacionDetalle',
                        'style' => 'width:210px;',
                        'tabIndex' => '-1',
                    ]);
                ?>
            </td>
 		</tr>

        <tr>
            <td><label>Agente:</label></td>
            <td></td>
            <td colspan="8">
                <?=
                    Html::activeDropDownList($model, 'ag_rete', $agentes, [
                        'class' => 'form-control',
                        'style' => 'width:100%;',
                        'id' => 'agenteRetencion',
                        'prompt' => 'Seleccionar...',
                    ]);
                ?>
            </td>
        </tr>

 		<tr>
 			<td><label>Lugar:</label></td>
 			<td></td>
 			<td>
                <?=
                    Html::activeInput( 'text', $model, 'lugar', [
                        'class' => 'form-control',
                        'style' => 'width:175px;',
                        'id' => 'lugarDetalle',
                        'maxlength' => 30,
                    ]);
                ?>
            </td>
 			<td width="5px"></td>
 			<td><label>Fecha:</label></td>
 			<td>
                <?=
                    DatePicker::widget([
                        'model' => $model,
                        'attribute' => 'fecha',
                        'dateFormat' => 'php:d/m/Y',
                        'options' => [
                            'class' => 'form-control',
                            'style' => 'width:80px;',
                            'id' => 'fechaDetalle',
                        ],
                    ]);
                ?>
            </td>
 			<td width="5px"></td>
 			<td><label>Nº:</label></td>
 			<td>
                <?=
                    Html::activeInput('text', $model, 'numero', [
                        'class' => 'form-control',
                        'style' => 'width:80px;',
                        'id' => 'numeroDetalle',
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
                        'class' => 'form-control',
                        'style' => 'width:80px;',
                        'id' => 'tipoComprobanteDetalle',
                        'prompt' => 'Seleccionar...',
                    ]);
                ?>
            </td>
 			<td></td>
 			<td><label>Nº:</label></td>
 			<td>
                <?=
                    Html::activeInput('text', $model, 'comprob', [
                        'class' => 'form-control',
                        'style' => 'width:80px;',
                        'id' => 'numeroComprobanteDetalle',
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
                        'class' => 'form-control',
                        'id' => 'baseDetalle',
                        'style' => 'width:80px; text-align:right;',
                        'onkeypress'    => 'return justDecimal( $( this ).val(), event )',
                        'onchange' => 'actualizarMontoDetalle();',
                    ]);
                ?>
            </td>
 			<td></td>
 			<td><label>Al&iacute;cuota:</label></td>
 			<td>
                <?=
                    Html::activeInput('text', $model, 'ali', [
                        'class' => 'form-control',
                        'id' => 'alicuotaDetalle',
                        'style' => 'width:80px; text-align:right;',
                        'onkeypress'    => 'return justNumbers( event )',
                        'onchange' => 'actualizarMontoDetalle();',
                    ]);
                ?>
            </td>
 			<td></td>
 			<td><label>Monto:</label></td>
 			<td>
                <?=
                Html::activeInput('text', $model, 'monto', [
                    'class' => 'form-control',
                    'id' => 'montoDetalle',
                    'style' => 'width:80px; text-align:right;',
                ]);
            ?>
        </td>
 		</tr>
 	</table>
 </div>

 <div style="margin-top:5px;" class="text-center">

 	<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => "f_grabarDetalle();"]); ?>

 	&nbsp;&nbsp;&nbsp;

    <?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'cerrarModalDetalle();']); ?>
 </div>

 <div class="error-summary" style="margin-top: 8px; display: none" id="agregarRete_errorSummary">
 </div>

 <?php ActiveForm::end(); ?>

 <script>

 function f_grabarDetalle(){

     var error  = new Array(),
        agente  = $( "#agenteRetencion" ).val(),
        lugar   = $( "#lugarDetalle" ).val(),
        fecha   = $( "#fechaDetalle" ).val(),
        numero  = isNaN( parseInt( $( "#numeroDetalle" ).val() ) ) ? 0 : parseInt( $( "#numeroDetalle" ).val() ),
        tipo    = $( "#tipoComprobanteDetalle" ).val(),
        numeroC = isNaN( parseInt( $( "#numeroComprobanteDetalle" ).val() ) ) ? 0 : parseInt( $( "#numeroComprobanteDetalle" ).val() ),
        base    = isNaN( parseFloat( $( "#baseDetalle" ).val() ) ) ? 0 : parseFloat( $( "#baseDetalle" ).val() ),
        alicuota= isNaN( parseFloat( $( "#alicuotaDetalle" ).val() ) ) ? 0 : parseFloat( $( "#alicuotaDetalle" ).val() ),
        monto   = isNaN( parseFloat( $( "#montoDetalle" ).val() ) ) ? 0 : parseFloat( $( "#montoDetalle" ).val() );


    if( agente == '' ){
        error.push( "Ingrese un agente." );
    }

    if( agente == '' ){
        error.push( "Ingrese un lugar." );
    }

    if( fecha == '' ){
        error.push( "Ingrese una fecha." );
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

        $( "#agregarRete_form" ).submit();

     }

 }

 function actualizarMontoDetalle(){

 	var base = isNaN( parseFloat($("#baseDetalle").val() ) ) ? 0 : parseFloat( $("#baseDetalle").val());
 	var alicuota = isNaN( parseInt($("#alicuotaDetalle").val() ) ) ? 0 : parseInt($("#alicuotaDetalle").val());
 	var monto = 0;

 	if(!isNaN(base) && !isNaN(alicuota) && base > 0 && alicuota > 0 )
 		monto = base * alicuota / 100;

 	$("#montoDetalle").val( monto.toFixed( 2 ) );
    $("#baseDetalle").val( base.toFixed( 2 ) );
    $("#alicuotaDetalle").val( alicuota.toFixed( 0 ) );
 }

 $( document ).ready(function() {
     actualizarMontoDetalle();
 });
 </script>
