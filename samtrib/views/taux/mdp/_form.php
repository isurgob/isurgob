<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\controllers\taux\MdpController;

/**
 * Form que se utiliza para gestionar los medios de pago.
 *
 * Recibe:
 *          + $model       => Modelo de caja_mdp.
 *          + $action       => Identificador del tipo de acción.
 *          + $dataProvider => Arreglo con los medios de pago.
 */

 $title = 'Medios de Pago';
 $this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares','url' => ['site/taux']];
 $this->params['breadcrumbs'][] = $title;

?>

<!-- INICIO Div Principal -->
<div id="mdp_divPrincipal">

<div class="pull-left">
    <h1><?= Html::encode($title) ?></h1>
</div>

<div class="pull-right">
    <?php
        if( $permiteModificar ){

            echo Html::button( 'Nuevo', [
                'class' => 'btn btn-success',
                'id'    => 'mdp_btNuevo',
                'onclick' => 'f_mdp( 0, 0 );',
            ]);

        }

    ?>
</div>

<div class="clearfix separador-horizontal">
</div>

<!-- INICIO DIv Mensajes -->
<div id="mdp_mensajes" class="mensaje alert-success" style="display:none">
</div>
<!-- FIN DIv Mensajes -->

<!--INICIO Div Grilla -->
<div id="mdp_divGrilla">

    <?php

        Pjax::begin();

            echo GridView::widget([
                'id' => 'mdp_grillaMDP',
                'dataProvider' => $dataProvider,
                'headerRowOptions' => ['class' => 'grilla'],
                'summaryOptions' => ['class' => 'hidden'],
                //'formatter' => [ 'class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                'rowOptions' => function ( $model,$key,$index,$grid )
                            {
                                return [
                                    'onclick'   => 'f_mdp(' . $model['mdp'] . ', 1 );',
                                    'class'     => 'grilla',
                                ];
                            },
                'columns' => [
                    ['attribute' => 'mdp','label' => 'Cod', 'contentOptions' => [ 'style' => 'text-align:center']],
                    ['attribute' => 'nombre','label' => 'Nombre'],
                    ['attribute' => 'tipo_nom', 'label' => 'Tipo'],
                    ['attribute' => 'cotiza', 'label' => 'Cotización','contentOptions' => ['style' => 'text-align: right']],
                    ['attribute' => 'simbolo', 'label' => 'Símbolo'],
                    ['attribute' => 'habilitado', 'label' => 'Estado'],
                    ['attribute' => 'financia', 'label' => 'Financia','contentOptions' => ['style' => 'width:20px;text-align:center']],

                    ['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:20px;text-align:center;'],'template' => ( $permiteModificar ? '{update} {delete}' : ''),
                        'buttons'=>[
                            'update' => function($url,$model,$key)
                                    {
                                        return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
                                                    [
                                                        'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
                                                        'onclick' => 'event.stopPropagation();f_mdp(' . $model['mdp'] . ', 3 );',
                                                    ]
                                                );
                                    },
                            'delete' => function($url,$model,$key)
                                    {
                                        return false;
                                    }
                        ]
                   ],

                ],
            ]);

        Pjax::end();

    ?>

</div>
<!-- FIN Div Grilla -->

<!-- INICIO Pjax Datos -->
<?php

    Pjax::begin([ 'id' => 'mdp_pjaxDatos', 'enableReplaceState' => false, 'enablePushState' => false ]);

        echo MdpController::datos( $model , $action, $arrayCuotas );

    Pjax::end();

?>
<!-- FIN Pjax Datos -->

<!-- INICIO Div Errores -->
<?=
    Html::errorSummary( $model, [
        'id'    => 'mdp_errorSummary',
        'class' => 'error-summary',
        'style' => 'margin-top: 8px',
    ]);
?>
<!-- FIN Div Errores -->

</div>
<!-- FIN Div Principal -->

<?php if ( $mensaje != '' ) { //Si existen mensajes ?>
<script>

$( document ).ready(function() {

    mostrarMensaje( "<?= $mensaje; ?>", "#mdp_mensajes" );

});

</script>

<?php } ?>

<script>

function f_mdp( id, action ){

    ocultarErrores( "#mdp_errorSummary" );

    if( action == 2 || action == 3 ){

        $( "#mdp_divGrilla" ).addClass( "read-only" );
    }

    $.pjax.reload({
        container   : "#mdp_pjaxDatos",
        type        : "GET",
        replace     : false,
        push        : false,
        data:{
            "mdp"   : id,
            "action": action,
        },
    });
}

$( document ).ready(function(){

    var action = <?= $action ?>;

    if( action == 2 || action == 3 ){

        $( "#mdp_divGrilla" ).addClass( "read-only" );
    }
});

</script>
