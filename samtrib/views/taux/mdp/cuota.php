<?php

use yii\helpers\Html;

/**
 * Forma que se dibuja como ventana modal para agregar o modificar cuota a un período de pago.
 *
 * Recibe:
 *
 *  + $cuota        => Número de cuota.
 *  + $recargo      => Valor decimal de recargo,
 *  + $actionCuota  => Action
 */

?>

<table>
    <tr>
        <td><label>Cuota:</label></td>
        <td>
            <?=
                Html::input( 'text', 'txCuota', $cuota, [
                    'id'    => 'mdp_datos_txCuota',
                    'class' => 'form-control' . ( $actionCuota == 3 ? ' solo-lectura' : '' ),
                    'style' => 'width:60px; text-align: center',
                    'tabIndex'  => ( $actionCuota == 3 ? '-1' : '0' ),
                ]);
            ?>
        </td>
        <td width="10px"></td>
        <td><label>Recargo:</label></td>
        <td>
            <?=
                Html::input( 'text', 'txCuota', $recargo, [
                    'id'    => 'mdp_datos_txRecargo',
                    'class' => 'form-control',
                    'style' => 'width:60px; text-align: center',
                ]);
            ?>
        </td>
    </tr>
</table>

<div class="text-center" style="margin-top: 8px">

    <?=
        Html::button( 'Aceptar', [
            'onclick'   => 'f_agregarCuota( )',
            'class'     => 'btn btn-success',
        ]);
    ?>

    &nbsp;&nbsp;

    <?=
        Html::button( 'Cancelar', [
            'onclick'   => 'f_cerrarModalCuotas()',
            'class'     => 'btn btn-primary',
        ]);
    ?>
</div>

<div id="mdp_datos_modalCuotas_errorSummary" class="error-summary" style="margin-top: 8px; display: none" >
</div>

<script>

function f_cerrarModalCuotas(){

    $( "<?= $idVentanaModal ?>" ).modal( "hide" );
}

function f_agregarCuota( ){

    var cuota   = $( "#mdp_datos_txCuota" ).val(),
        recargo = $( "#mdp_datos_txRecargo" ).val(),
        error   = new Array();

    if( ! ( parseInt( cuota ) > 0 ) ){
        error.push( "La cuota debe ser mayor a 0." );
    }

    if( error.length > 0 ){

        mostrarErrores( error, "#mdp_datos_modalCuotas_errorSummary" );

    } else {

        f_cerrarModalCuotas();
        f_abmCuotas( cuota, recargo, <?= $actionCuota ?> );

    }
}

</script>
