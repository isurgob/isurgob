<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\controllers\taux\JudihonoController;

$title = 'Listado de Gastos y Honoriarios Judiciales';

$this->params['breadcrumbs'][] = [ 'label' => 'Tablas Auxiliares', 'url' => [ 'site/taux' ]];
$this->params['breadcrumbs'][]= $title;

?>


<!-- INICIO Div Principal -->
<div id="index_divPrincipal">

    <div class="pull-left">

        <h1><?= $title; ?></h1>

    </div>

    <div class="pull-right" style="margin-bottom: 8px">

        <?=
            Html::button( 'Nuevo', [
                'class'     => 'btn btn-success',
                'onclick'   => 'f_ABM( "", 0, 0, 0, 0 )',
            ]);
        ?>

    </div>

    <div class="clearfix"></div>

    <!-- INICIO Div Mensaje -->
    <div id="index_divMensaje" class="mensaje alert-success" style="display: none">
    </div>
    <!-- FIN Div Mensaje -->

    <!-- INICIO Div Datos -->
    <div id="index_divDatos">

        <!-- INICIO Div Grilla -->
        <div id="index_divGrilla">

            <?php
				Pjax::begin([ 'id' => 'index_pjaxGrilla', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);
					echo GridView::widget([
						'id' => 'GrillaJudiHono',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dpJudiHono,
						'summaryOptions' => ['class' => 'hidden'],
						'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
						'columns' => [
							['attribute'=>'est_nom','header' => 'Est.', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
							['attribute'=>'supuesto','header' => 'Supuesto', 'contentOptions'=>['width'=>'1%']],
							['attribute'=>'deuda_desde','header' => 'Deuda Desde', 'contentOptions'=>['style'=>'text-align:right']],
                            ['attribute'=>'deuda_hasta','header' => 'Deuda Hasta', 'contentOptions'=>['style'=>'text-align:right']],
                            ['attribute'=>'hono_min','header' => 'Mím.', 'contentOptions'=>['style'=>'text-align:right']],
                            ['attribute'=>'hono_porc','header' => 'Porc.', 'contentOptions'=>['style'=>'text-align:right']],
                            ['attribute'=>'gastos','header' => 'Gastos', 'contentOptions'=>['style'=>'text-align:right']],
							
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:60px;text-align:center'],
								'template' => '{view} {update} {delete}',
								'buttons'=>[

									'view' => function($url, $model, $key)
										{
											return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', null, [ 'onclick' => "f_ABM( '" . $model['est'] . "'," . $model['supuesto'] . "," . $model['deuda_desde'] . "," . $model['deuda_hasta'] . ", 1 )" ] );
										},

									'update' => function($url, $model, $key)
										{
											
												return Html::a('<span class="glyphicon glyphicon-pencil"></span>',  null, [ 'onclick' => "f_ABM( '" . $model['est'] . "'," . $model['supuesto'] . "," . $model['deuda_desde'] . "," . $model['deuda_hasta'] . ", 3 )" ] );
											

										},

									'delete' => function($url, $model, $key)
										{
											
												return Html::a('<span class="glyphicon glyphicon-trash"></span>',  null, [ 'onclick' => "f_ABM( '" . $model['est'] . "'," . $model['supuesto'] . "," . $model['deuda_desde'] . "," . $model['deuda_hasta'] . ", 2 )" ] );
											

										},
									
								]
							]
						],
					]);
					
					if( $mostrar_modal ){ ?>
                    <script>f_mostrarModal();</script>
                <?php }

            Pjax::end();
			?>

        </div>
        <!-- FIN Div Grilla -->

    </div>
</div>
<!-- FIN Div Principal -->

<?php if( $mensaje != '' ){ ?>
<script>mostrarMensaje( "<?= $mensaje ?>", "#index_divMensaje" );</script>
<?php } ?>

<?php

Pjax::begin([ 'id' => 'index_pjaxModal', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

    switch( $action ){

        case 0:

            $title = 'Nueva Honorario';
            break;

        case 1:

            $title = 'Consulta Honorario';
            break;

        case 2:

            $title = 'Eliminar Honorario';
            break;

        case 3:

            $title = 'Modificar Honorario';
            break;
    }

    Modal::begin([
            'id' => 'index_modal',
            'header' => "<h2>$title</h2>",
            'closeButton' => [
              'label' => '<b>X</b>',
              'class' => 'btn btn-danger btn-sm pull-right',
            ]
        ]);

            echo JudihonoController::judihono( $est, $supuesto, $deuda_desde, $deuda_hasta, $action );

    Modal::end();

Pjax::end();

?>

<script>

function f_ABM( est, supuesto, deuda_desde, deuda_hasta, action ){

    $.pjax.reload({
        container   : "#index_pjaxModal",
        type        : "GET",
        replace     : false,
        push        : false,
        timeout     : 100000,
        data:{
            "est"        : est,
            "supuesto"   : supuesto,
            "deuda_desde" : deuda_desde,
            "deuda_hasta" : deuda_hasta,
            "action"    : action,
        },
    });

}

function f_mostrarModal(){

    $( "#index_modal" ).modal( "show" );
}

$( "#index_pjaxModal" ).on( "pjax:end", function() {

    f_mostrarModal();

})

</script>