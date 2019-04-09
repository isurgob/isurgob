<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\controllers\taux\RubronomecladorController;

$title = 'Nomeclador de Rubro';

$this->params['breadcrumbs'][] = [ 'label' => 'Tablas Auxiliares', 'url' => [ 'site/taux' ]];
$this->params['breadcrumbs'][]= $title;

?>


<!-- INICIO Div Principal -->
<div id="index_divPrincipal">

	<h1><?= $title; ?></h1>
	
    <!-- INICIO Div Datos -->
    <div id="index_divDatos">

        <!-- INICIO Div Grilla -->
        <div id="index_divGrilla">

            <?php
				Pjax::begin([ 'id' => 'index_pjaxGrilla', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);
					echo GridView::widget([
						'id' => 'GrillaRubroGrupo',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dpNomecladores,
						'summaryOptions' => ['class' => 'hidden'],
						'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
						'columns' => [
							['attribute'=>'nomen_id', 'header' => 'Código', 'contentOptions'=>['style'=>'text-align:left','width'=>'1%']],
							['attribute'=>'tobj_nom', 'header' => 'Tipo Objeto', 'contentOptions'=>['style'=>'text-align:left','width'=>'1%']],
							['attribute'=>'nombre', 'header' => 'Nombre'],
							['attribute'=>'perdesde', 'header' => 'Desde', 'contentOptions'=>['style'=>'text-align:center','width'=>'1%']],
							['attribute'=>'perhasta', 'header' => 'Hasta', 'contentOptions'=>['style'=>'text-align:center','width'=>'1%']],
                            							
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:1%;text-align:center'],
								'template' => '{view}',
								'buttons'=>[

									'view' => function($url, $model, $key)
										{
											return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', null, [ 'onclick' => "f_ABM( '" . $model['nomen_id'] . "'," . $model['tobj'] . ")" ] );
										}
								]
							]
						],
					]);

				Pjax::end();
			?>

        </div>
        <!-- FIN Div Grilla -->

    </div>
</div>
<!-- FIN Div Principal -->

<?php

Pjax::begin([ 'id' => 'index_pjaxModal', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

    $nomen_id = Yii::$app->request->post( 'nomen_id', '' );
	$tobj = intVal(Yii::$app->request->post( 'tobj', 0 ));
	
	Modal::begin([
            'id' => 'index_modal',
            'header' => "<h2>Consulta de Nomeclador</h2>",
            'closeButton' => [
              'label' => '<b>X</b>',
              'class' => 'btn btn-danger btn-sm pull-right',
            ]
        ]);

            echo RubronomecladorController::nomeclador( $nomen_id, $tobj );

    Modal::end();

Pjax::end();

?>

<script>

function f_ABM( nomen_id, tobj ){

    $.pjax.reload({
        container   : "#index_pjaxModal",
        type        : "POST",
        replace     : false,
        push        : false,
        timeout     : 100000,
        data:{
            "nomen_id"   : nomen_id,
            "tobj" : tobj
        },
    });
	
	$( "#index_pjaxModal" ).on( "pjax:end", function() {

		$("#index_modal").modal("show");

	})

}

</script>