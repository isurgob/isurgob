<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\controllers\taux\MdpController;

/**
 * Forma que se utiliza para visualizar los datos de "Medios de Pago".
 *
 * Recibe:
 *
 *
 */

 $form = ActiveForm::begin([
     'id' => 'mdp_datos_form',
 ]);


 echo Html::input( 'hidden', 'txAction', $action );
?>

<!-- INICIO Div Datos -->
<div id="mdp_datos" class="form-panel" style="margin-top: 8px; margin-right: 0px; padding-top: 15px">

<div class="pull-left" style="width:400px">
<table border="0">
    <tr>
        <td><label>C&oacute;digo:</label></td>
        <td>
            <?=
                Html::activeInput( 'text', $model, 'mdp', [
                    'id'        => 'mdp_txCodigo',
                    'class'     => 'form-control solo-lectura',
                    'style'     => 'width: 40px;text-align: center',
                    'tabIndex'  => '-1',
                ]);
            ?>
        </td>
        <td colspan="2"><label>Nombre:</label></td>
        <td colspan="4">
            <?=
                Html::activeInput( 'text', $model, 'nombre', [
                    'id'        => 'mdp_txNombre',
                    'class'     => 'form-control' . ( in_array( $action, [0,3] ) ? '' : ' solo-lectura' ),
                    'style'     => 'width: 200px;text-align: left',
                    'tabIndex'  => ( in_array( $action, [0,3] ) ? '0' : '-1' ),
                ]);
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="2"><label>Medio de Pago:</label></td>
        <td colspan="6">
            <?=
                Html::activeDropDownList( $model, 'tipo', $listadoTMDP, [
                    'id'        => 'mdp_dlTMDP',
                    'class'     => 'form-control' . ( in_array( $action, [0,3] ) ? '' : ' solo-lectura' ),
                    'style'     => 'width: 99%;text-align: left',
                    'tabIndex'  => ( in_array( $action, [0,3] ) ? '0' : '-1' ),
                ]);
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="130px"><label>Monto de Cotización:</label></td>
        <td>
            <?=
                Html::activeInput( 'text', $model, 'cotiza', [
                    'id'        => 'mdp_txCotiza',
                    'class'     => 'form-control' . ( in_array( $action, [0,3] ) ? '' : ' solo-lectura' ),
                    'style'     => 'width: 60px;text-align: right',
                    'tabIndex'  => ( in_array( $action, [0,3] ) ? '0' : '-1' ),
                ]);
            ?>
        </td>
        <td width="10px"></td>
        <td><label>Símbolo:</label></td>
        <td>
            <?=
                Html::activeInput( 'text', $model, 'simbolo', [
                    'id'        => 'mdp_txSimbolo',
                    'class'     => 'form-control' . ( in_array( $action, [0,3] ) ? '' : ' solo-lectura' ),
                    'style'     => 'width: 40px;text-align: left',
                    'tabIndex'  => ( in_array( $action, [0,3] ) ? '0' : '-1' ),
                ]);
            ?>
        </td>
        <td width="10px"></td>
        <td>
            <?=
                Html::activeCheckbox( $model, 'habilitado', [
                    'uncheck' => '0',
                    'disabled'  => ( in_array( $action, [0,3] ) ? false : true ),
                ]);
            ?>
        </td>
    </tr>
</table>
</div>

<div class="pull-right"  style="width:300px">

    <div class="pull-left">
        <?=
            Html::activeCheckbox( $model, 'financia', [
                'id'        => 'mdp_ckFinancia',
                'disabled'  => ( in_array( $action, [0,3] ) ? false : true ),
                'uncheck'   => '0',
                'onchange'  => 'f_financia()',
            ]);
        ?>
    </div>
    <div class="pull-right">
        <?=
            Html::button( 'Nueva Cuota', [
                'id'    => 'mdp_btNuevaCuota',
                'class' => 'btn btn-success',
                'style' => ( intVal( $model->financia ) == 1 && in_array( $action, [ 0, 3 ] ) ? 'display:block' : 'display:none' ),
                'onclick'   => 'f_cuotas( 0, 0, 0 )',
            ]);
        ?>
    </div>

    <div class="clearfix"></div>

    <!-- INICIO Div Grilla Cuotas -->
    <div id="mdp_divGrillaCuotas" style="margin-top: 8px">

    <?php

        Pjax::begin([ 'id' => 'mdp_datos_pjaxCuotas', 'enablePushState' => false, 'enableReplaceState' => false ]);

            echo GridView::widget([
                'id' => 'mdp_grillaCuotas',
                'dataProvider' => $dataProviderCuotas,
                'headerRowOptions' => ['class' => 'grilla'],
                'summaryOptions' => ['class' => 'hidden'],
                'rowOptions' => function ( $model,$key,$index,$grid )
                            {
                                return [
                                    'class'     => 'grilla',
                                ];
                            },
                'columns' => [
                    ['attribute' => 'cuotas','label' => 'Cuotas', 'contentOptions' => [ 'style' => 'text-align:center']],
                    ['attribute' => 'rec','label' => 'Recargo', 'contentOptions' => ['style' => 'text-align:center']],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions'=>['style'=>'width:30px;text-align:center;'],
                        'template'  => ( in_array( $action, [ 0, 3 ] ) ? '{update}{delete}' : '' ),
                        'buttons'=>[
                            'update' => function($url,$model,$key)
                                    {
                                        return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
                                                    [
                                                        'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
                                                        'onclick' => 'f_cuotas(' . $model['cuotas'] . ', ' . $model['rec'] . ', 3 );',
                                                    ]
                                                );
                                    },
                            'delete' => function($url,$model,$key)
                                    {
                                        return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
                                                    [
                                                        'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
                                                        'onclick' => 'f_abmCuotas(' . $model['cuotas'] . ',' . $model['rec'] . ', 2 );',
                                                    ]
                                                );
                                    }
                        ]
                   ],

                ],
            ]);

            $error = $model->getErrors();

            if( count( $error ) > 0 ){

                echo '<script>var error = new Array();</script>';

                foreach( $error as $er ){

                    ?>

                    <script>

                        error.push( "<?= $er[0] ?>" );

                    </script>

                    <?php
                }

                echo '<script>mostrarErrores( error, "#div_datos_errorSummary" );</script>';
            }

        Pjax::end();

    ?>
    </div>
    <!-- FIN Div Grilla Cuotas -->

</div>

<div class="clearfix"></div>

</div>
<!-- FIN Div Datos -->

<?php ActiveForm::end(); ?>

<?php if( in_array( $action, [0, 2, 3] )) { ?>
<!-- INICIO Div Botones -->
<div id="div_botones">

    <?=
        Html::button( ( $action == 2 ? 'Eliminar' : 'Aceptar' ), [
            'class' => ( $action == 2 ? 'btn btn-danger' : 'btn btn-success' ),
            'onclick'   => 'f_aceptarMdp()',
        ]);
    ?>

    &nbsp;&nbsp;

    <?=
        Html::a( 'Cancelar', ['taux/mdp/index'],[
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

<!-- INICIO Ventana Modal Cuotas -->
<?php

Pjax::begin([ 'id' => 'mdp_datos_pjaxModalCuotas', 'enableReplaceState' => false, 'enablePushState' => false ]);

Modal::begin([
	'id' => 'mdp_modalCuotas',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Cuotas</h2>',
	'size' => 'modal-sm',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
]);

    echo MdpController::cuota( $cuota, $recargo, $actionCuota, "#mdp_modalCuotas" );

Modal::end();

Pjax::end();

?>

<!-- FIN Ventana Modal Cuotas -->
<script>

function f_financia(){

    var financia        = $( "#mdp_ckFinancia" ).is( ":checked" ),
        $btNuevaCuota   = $( "#mdp_btNuevaCuota" ),
        $grilla         = $( "#mdp_grillaCuotas" );

    if( financia ){

        $btNuevaCuota.css( "display", "block" );
        $grilla.css( "display", "block" );

    } else {

        $btNuevaCuota.css( "display", "none" );
        $grilla.css( "display", "none" );

    }
}

function f_cuotas( cuota, recargo, action ){

    ocultarErrores( "#div_datos_errorSummary" );

    $.pjax.reload({
        container   : "#mdp_datos_pjaxModalCuotas",
        type        : "GET",
        replace     : false,
        push        : false,
        data:{
            'cuota'         : cuota,
            'recargo'       : recargo,
            'actionCuota'   : action,
        },
    });
}

$( "#mdp_datos_pjaxModalCuotas" ).on( "pjax:end", function() {

    $( "#mdp_modalCuotas" ).modal( "show" );
});

function f_abmCuotas( cuota, recargo, action ){

    $.pjax.reload({
        container   : "#mdp_datos_pjaxCuotas",
        type        : "GET",
        replace     : false,
        push        : false,
        data:{
            'action'        : <?= $action ?>,
            'cuota'         : cuota,
            'recargo'       : recargo,
            'actionCuota'   : action,
        },
    });

}

function f_aceptarMdp(){

    $( "#mdp_datos_form" ).submit();
}

</script>
