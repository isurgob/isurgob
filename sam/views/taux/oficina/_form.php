<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\controllers\taux\OficinaController;

/**
 * Form que se utiliza para gestionar las oficinas.
 */

 $title = 'Oficinas';
 $this->params['breadcrumbs'][]= ['label' => 'Configuraciones', 'url' => Yii::$app->request->baseUrl.'/index.php?r=site/config'];
 $this->params['breadcrumbs'][] = $title;

?>

<!-- INICIO Div Principal -->
<div id="oficina_divPrincipal">

<div class="pull-left">
    <h1><?= Html::encode($title) ?></h1>
</div>

<div class="pull-right">
    <?php
        if( $permiteModificar ){

            echo Html::button( 'Nuevo', [
                'class' => 'btn btn-success',
                'id'    => 'oficina_btNuevo',
                'onclick' => 'f_oficina( 0, 0 );',
            ]);

        }

    ?>
</div>

<div class="clearfix separador-horizontal">
</div>

<!-- INICIO DIv Mensajes -->
<div id="oficina_mensajes" class="mensaje alert-success" style="display:none">
</div>
<!-- FIN DIv Mensajes -->

<div id="oficina_divSecretaria" style="margin: 8px 0px 8px 0px">
    <table>
        <tr>
            <td><label>Secretaría:<label></td>
            <td>
                <?=
                    Html::dropDownList( 'dlSecretaria', 0, $arraySecretaria, [
                        'id'        => 'form_dlSecretaria',
                        'class'     => 'form-control',
                        'onchange'  => 'f_cambiaSecretaria( $( this ).val() )',
                    ]);
                ?>
            </td>
        </tr>
    </table>
</div>

<!--INICIO Div Grilla -->
<div id="oficina_divGrilla">

    <?php

        Pjax::begin([ 'id' => 'oficina_pjaxGrillaOficinas', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

            echo GridView::widget([
                'id' => 'oficina_grillaOficinas',
                'dataProvider' => $dataProvider,
                'headerRowOptions' => ['class' => 'grilla'],
                'summaryOptions' => ['class' => 'hidden'],
                //'formatter' => [ 'class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                'rowOptions' => function ( $model,$key,$index,$grid )
                            {
                                return [
                                    'onclick'   => 'f_oficina(' . $model['ofi_id'] . ', 1 );',
                                    'class'     => 'grilla',
                                ];
                            },
                'columns' => [
					['attribute'=>'ofi_id','label' => 'Cod', 'contentOptions'=>['style'=>'width:30px;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'nombre','label' => 'Nombre' ,'contentOptions'=>['style'=>'width:200px;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'resp','header' => 'Responsable', 'contentOptions'=>['style'=>'width:200px;text-align:left;', 'class' => 'grilla']],
					['attribute'=>'sec_nom','header' => 'Secretaría', 'contentOptions'=>['style'=>'width:200px;text-align:left;', 'class' => 'grilla']],

                    ['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:40px;text-align:center;'],'template' => ( $permiteModificar ? '{update} {delete}' : ''),
                        'buttons'=>[
                            'update' => function($url,$model,$key)
                                    {
                                        return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
                                                    [
                                                        'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
                                                        'onclick' => 'event.stopPropagation();f_oficina(' . $model['ofi_id'] . ', 3 );',
                                                    ]
                                                );
                                    },
                            'delete' => function($url,$model,$key)
							{
								return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
											[
												'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
												'onclick' => 'event.stopPropagation();f_oficina(' . $model['ofi_id'] . ', 2 );',
											]
										);
							},
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

    Pjax::begin([ 'id' => 'oficina_pjaxDatos', 'enableReplaceState' => false, 'enablePushState' => false ]);

        echo OficinaController::datos( $model , $action );

    Pjax::end();

?>
<!-- FIN Pjax Datos -->

<!-- INICIO Div Errores -->
<?=
    Html::errorSummary( $model, [
        'id'    => 'oficina_errorSummary',
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

    mostrarMensaje( "<?= $mensaje; ?>", "#oficina_mensajes" );

});

</script>

<?php } ?>

<script>

function f_cambiaSecretaria( sec_id ){

    $.pjax.reload({
        container   : "#oficina_pjaxGrillaOficinas",
        type        : "GET",
        replace     : false,
        push        : false,
        data:{
            "sec_id"   : sec_id,
        },
    });
}

function f_oficina( id, action ){

    ocultarErrores( "#oficina_errorSummary" );

    if( action == 2 || action == 3 ){

        $( "#oficina_divGrilla" ).addClass( "read-only" );
        $( "#oficina_divSecretaria select" ).addClass( "solo-lectura" );
    }

    $.pjax.reload({
        container   : "#oficina_pjaxDatos",
        type        : "GET",
        replace     : false,
        push        : false,
        data:{
            "oficina"   : id,
            "action": action,
        },
    });
}

$( document ).ready(function(){

    var action = <?= $action ?>;

    if( action == 2 || action == 3 ){

        $( "#oficina_divGrilla" ).addClass( "read-only" );
        $( "#oficina_divSecretaria select" ).addClass( "solo-lectura" );
    }
});

</script>
