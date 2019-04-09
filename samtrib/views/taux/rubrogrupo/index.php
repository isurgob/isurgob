<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\controllers\taux\RubrogrupoController;

$title = 'Rubro Grupo';

$this->params['breadcrumbs'][] = [ 'label' => 'Tablas Auxiliares', 'url' => [ 'site/taux' ]];
$this->params['breadcrumbs'][]= $title;

?>


<!-- INICIO Div Principal -->
<div id="index_divPrincipal">

	<h1><?= $title; ?></h1>
	
    <div class="pull-left">
		<label> Nomeclador: </label>
        <?php

			echo Html::dropDownList('filtroNomeclador', null, $nomecladores , [
				'id' => 'filtroNomeclador',
				'class' => 'form-control',
				'onchange' => 'filtroNomecladorSelect()'
			]);
		?>

    </div>

    <div class="pull-right" style="margin-bottom: 8px">

        <?=
            Html::button( 'Nuevo', [
                'class'     => 'btn btn-success',
                'onclick'   => 'f_ABM( "", "", "insert" )',
            ]);
        ?>

    </div>

    <div class="clearfix"></div>
	
	<!-- INICIO Div Mensaje -->
    <div id="index_divMensaje" class="mensaje alert-success" style="display: none"></div>
    <!-- FIN Div Mensaje -->

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
						'dataProvider' => $dpRubroGrupo,
						'summaryOptions' => ['class' => 'hidden'],
						'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
						'columns' => [
							['attribute'=>'cod', 'header' => 'Código', 'contentOptions'=>['style'=>'text-align:left','width'=>'1%']],
							['attribute'=>'nomen_nom', 'header' => 'Nomeclador', 'contentOptions'=>['style'=>'text-align:left','width'=>'1%']],
							['attribute'=>'nombre', 'header' => 'Nombre'],
                            							
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:5%;text-align:center'],
								'template' => '{update} {delete}',
								'buttons'=>[

									'update' => function($url, $model, $key)
										{
											return Html::a('<span class="glyphicon glyphicon-pencil"></span>', null, [ 'onclick' => "f_ABM( '" . $model['cod'] . "','" . $model['nomen_id'] . "', 'update')" ] );
										},

									'delete' => function($url, $model, $key)
										{
											
											return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, [ 'onclick' => "f_ABM( '" . $model['cod'] . "','" . $model['nomen_id'] . "', 'delete')" ] );
											

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

<?php if( $mensaje != '' ){ ?>
<script>mostrarMensaje( "<?= $mensaje ?>", "#index_divMensaje" );</script>
<?php } ?>

<?php

Pjax::begin([ 'id' => 'index_pjaxModal', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

    $cod = Yii::$app->request->post( 'cod', '' );
	$nomen_id = Yii::$app->request->post( 'nomen_id', '' );
	$scenario = Yii::$app->request->post( 'scenario', '' );
	
	$title = '';
	
	switch( $scenario ){

		case 'insert':  //Insert

			$title = 'Nuevo Rubro Grupo';

			break;

		case 'update': //UPDATE

			$title = 'Modificar Rubro Grupo';

			break;

		case 'delete': //Delete

			$title = 'Eliminar Rubro Grupo';

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

            echo RubrogrupoController::rubrogrupo( $cod, $nomen_id, $scenario );

    Modal::end();

Pjax::end();

?>

<script>

function f_ABM( cod, nomen_id, scenario ){

    $.pjax.reload({
        container   : "#index_pjaxModal",
        type        : "POST",
        replace     : false,
        push        : false,
        timeout     : 100000,
        data:{
            "cod"        : cod,
            "nomen_id"   : nomen_id,
            "scenario" : scenario
        },
    });
	
	$( "#index_pjaxModal" ).on( "pjax:end", function() {

		$("#index_modal").modal("show");

	})

}

function filtroNomecladorSelect(){

	$.pjax.reload({
        container   : "#index_pjaxGrilla",
        type        : "POST",
        replace     : false,
        push        : false,
        timeout     : 100000,
        data:{
            "nomen_id"   : $("#filtroNomeclador").val()
        },
    });
}
</script>