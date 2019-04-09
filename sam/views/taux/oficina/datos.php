<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\controllers\taux\OficinaController;

/**
 * Forma que se utiliza para visualizar los datos de "Oficinas".
 *
 * Recibe:
 *
 *
 */

 $form = ActiveForm::begin([
     'id' => 'oficina_datos_form',
 ]);


 echo Html::input( 'hidden', 'txAction', $action );
?>

<!-- INICIO Div Datos -->
<div id="oficina_datos" class="form-panel" style="margin-top: 8px; margin-right: 0px; padding-top: 15px">

    <table border="0">
        <tr>
            <td><label>Oficina:</label></td>
            <td>
                <?=
                    Html::activeInput( 'text', $model, 'ofi_id', [
                        'id'        => 'oficina_txOfiID',
                        'class'     => 'form-control solo-lectura',
                        'style'     => 'width: 40px;text-align: center',
                        'tabIndex'  => '-1',
                    ]);
                ?>
            </td>
            <td width="10px"></td>
            <td><label>Nombre:</label></td>
            <td>
                <?=
                    Html::activeInput( 'text', $model, 'nombre', [
                        'id'        => 'oficina_txNombre',
                        'class'     => 'form-control' . ( in_array( $action, [0,3] ) ? '' : ' solo-lectura' ),
                        'style'     => 'width: 350px;text-align: left',
                        'tabIndex'  => ( in_array( $action, [0,3] ) ? '0' : '-1' ),
                    ]);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><label>Secretaria:</label></td>
            <td colspan="3">
                <?=
                    Html::activeDropDownList( $model, 'sec_id', $arraySecretaria, [
                        'id'        => 'oficina_dlSecretaria',
                        'class'     => 'form-control' . ( in_array( $action, [0,3] ) ? '' : ' solo-lectura' ),
                        'style'     => 'width: 99%;text-align: left',
                        'tabIndex'  => ( in_array( $action, [0,3] ) ? '0' : '-1' ),
                    ]);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><label>Responsable:</label></td>
            <td colspan="3">
                <?=
                    Html::activeInput( 'text', $model, 'resp', [
                        'id'        => 'oficina_txResponsable',
                        'class'     => 'form-control' . ( in_array( $action, [0,3] ) ? '' : ' solo-lectura' ),
                        'style'     => 'width: 99%;text-align: left',
                        'tabIndex'  => ( in_array( $action, [0,3] ) ? '0' : '-1' ),
                    ]);
                ?>
            </td>
        </tr>

        <tr>
            <td colspan="2"><label>Partida:</label></td>
            <td colspan="3">
                <?=
                    Html::activeInput( 'text', $model, 'part_formatoaux', [
                        'id'    => 'oficina_txPartID',
                        'class' => 'form-control' . ( in_array( $action, [ 1, 2 ] ) ? ' solo-lectura' : '' ),
                        'style' => 'width:20%; text-align: center',
                        'tabIndex'  => ( in_array( $action, [ 1, 2 ] ) ? '-1' : '0' ),
                        'onchange'  => 'f_cambiaPartida( $( this ).val() )',
                    ]);
                ?>

                <?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
                        'id'        => 'oficina_btPartida',
                        'class'     => 'bt-buscar',
                        'onclick'   => 'f_btPartida()',
                        'disabled'  => ( in_array( $action, [ 1, 2 ] ) ? true : false ),
                    ]);
                ?>

                <?=
                    Html::activeInput( 'text', $model, 'part_nom', [
                        'id'    => 'oficina_txPartNom',
                        'class' => 'form-control solo-lectura',
                        'style' => 'width:70.5%',
                        'tabIndex'  => '-1',
                    ]);
                ?>
            </td>
        </tr>
    </table>

</div>
<!-- FIN Div Datos -->

<?php ActiveForm::end(); ?>

<?php if( in_array( $action, [0, 2, 3] )) { ?>
<!-- INICIO Div Botones -->
<div id="div_botones">

    <?=
        Html::button( ( $action == 2 ? 'Eliminar' : 'Aceptar' ), [
            'class' => ( $action == 2 ? 'btn btn-danger' : 'btn btn-success' ),
            'onclick'   => 'f_grabarOficina()',
        ]);
    ?>

    &nbsp;&nbsp;

    <?=
        Html::a( 'Cancelar', ['taux/oficina/index'],[
            'class'     => 'btn btn-primary',
        ]);
    ?>
</div>
<!-- FIN Div Botones -->

<?php } ?>

<!-- INICIO Div Errores -->
<div id="div_datos_errorSummary" class="error-summary" style="display:none">
</div>
<!-- FIN Div Errores -->

<?php

//INICIO Pjax Cambia Partida
Pjax::begin([ 'id' => 'oficina_pjaxCambiaPartida', 'enableReplaceState' => false, 'enablePushState' => false ]);

    echo '<script>$( "#oficina_txPartID" ).val("' . $model->part_formatoaux . '");</script>';
    echo '<script>$( "#oficina_txPartNom" ).val("' . $model->part_nom . '");</script>';

    if ( $error != '' ){ //Si existen errores ?>
    <script>

    $( document ).ready(function() {

        mostrarErrores( ["<?= Html::encode( $error ); ?>"], "#oficina_errorSummary" );

    });

    </script>

    <?php
    }

Pjax::end();
//FIN Pjax Cambia Partida
?>

<!-- INICIO Modal Partidas -->
<?php

Pjax::begin(['id' => 'compra_form_pjaxModalPartida', 'enablePushState' => false, 'enableReplaceState' => false]);

    Modal::begin([
		'id' => 'oficina_modalBuscarPartida',
		'size' => 'modal-normal',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Partida</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		 ],
	]);

    $parametros = [
        'nropart'   => 'Código',
        'nombre'    => 'Nombre',
        'formatoaux'    => 'Formato',
    ];

    $sort = [
        'attributes' => [
            'formatoaux',
        ],
        'defaultOrder' => [
            'formatoaux' => SORT_ASC,
        ],
    ];

	echo $this->render('//taux/auxbuscavarios', [
            'parametros'     => $parametros,
            'idcamponombre'  => "oficina_txPartNom",
			'idcampocod'     => "oficina_txPartID",
			'idModal'        => "#oficina_modalBuscarPartida",
            'idAux'          => "partida",
			'campocod'       => 'formatoaux',
            'camponombre'    => 'nombre',
            'camposExtra'    => ['formato'],
			'tabla'          => 'fin.part',
            'criterio'       => 'caracter IN (2,3) AND anio = ' . $anioActual,
			'claseGrilla'	 => 'grilla',
			'cantmostrar'	 => '20',
            'sort'           => $sort,
		]);

    Modal::end();

Pjax::end();

?>
<!-- FIN Modal Partidas -->

<script>

function f_grabarOficina(){

    $( "#oficina_datos_form" ).submit();
}

function f_btPartida(){

    $( "#oficina_modalBuscarPartida" ).modal( "toggle" );
}

function f_cambiaPartida( part_id ){

    ocultarErrores( "#oficina_errorSummary" );

    $.pjax.reload({
        container   : "#oficina_pjaxCambiaPartida",
        type        : "GET",
        replace     : false,
        push        : false,
        data:{
            "partida"   : part_id,
        },
    });
}

</script>
