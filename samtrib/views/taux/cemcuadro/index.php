
<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use app\controllers\taux\CemcuadroController;

$title = 'Cuadros y Cuerpos';

$this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares','url' => ['//site/taux']];
$this->params['breadcrumbs'][] = 'Cementerio';
$this->params['breadcrumbs'][] = $title;

?>

<div class="pull-left"> <h1> <?= $title ?> </h1> </div>

<div class="pull-right"> 
	<?= 
		Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'f_abmCuadro( "", "nuevo" );'])
	?>
</div>

<div class="clearfix"></div>

<div class="separador-horizontal"></div>

<!-- INICIO Div Mensaje -->
<div id="form_divMensaje" class="mensaje alert-success" style="margin: 10px 0px; display: none"></div>

<?= GridView::Widget([
	'dataProvider' => $cuadros,
	'id' => 'grillaCuadros',
	'headerRowOptions' => [ 'class' => 'grilla' ],
	'rowOptions' => ['class' => 'grilla'],
	'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
	'summary' => '',
	'columns' => [
		[ 'attribute' => 'cua_id', 'label' => 'Cuadro', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ],
		[ 'attribute' => 'nombre', 'label' => 'Nombre' ],
		[ 'attribute' => 'tipo_nom', 'label' => 'Tipo'],
		[ 'attribute' => 'piso', 'label' => 'Piso', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ],
		[ 'attribute' => 'fila', 'label' => 'Fila', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ],
		[ 'attribute' => 'nume', 'label' => 'Nume.', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ],
		[ 'attribute' => 'bis', 'label' => 'BIS', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ],
		[ 'attribute' => 'modif', 'label' => 'Modificación'],
		
		[
			'class' => 'yii\grid\ActionColumn',
			'contentOptions'=>['style'=>'width:7%;text-align:center'],
			'template' => '{view}{update}{delete}',
			'buttons'=>[

				'view' => function($url, $model, $key)
				{
					return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
						'class'=>'bt-buscar-label',
						'onclick' => 'f_abmCuadro( "' . $model['cua_id'] . '", "" );',
						'style' => 'color:#337ab7'
					]);
				},
				
				'update' => function($url, $model, $key)
				{
					return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
						'class'=>'bt-buscar-label',
						'onclick' => 'f_abmCuadro( "' . $model['cua_id'] . '", "modificar" );',
						'style' => 'color:#337ab7'
					]);
				},
				
				'delete' => function($url, $model, $key)
				{
					return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
						'class'=>'bt-buscar-label',
						'onclick' => 'f_abmCuadro( "' . $model['cua_id'] . '", "eliminar" );',
						'style' => 'color:#337ab7'
					]);
				},
			]
		]
	],
]);
?>

<?php

    Pjax::begin([ 'id' => 'pjaxModal', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

        $cua_id = Yii::$app->request->get( 'cua_id', "" );
		$scenario = Yii::$app->request->get( 'scenario', "" );
		
		if ( $scenario == "nuevo" )
			$titulo = 'Nuevo Cuadro';
		elseif ( $scenario == "eliminar" )
			$titulo = 'Eliminar Cuadro';	
		elseif ( $scenario == "modificar" )
			$titulo = 'Modificar Cuadro';		
		else	
			$titulo = 'Consutar Cuadro';		
		
		Modal::begin([
                'id' => 'ModalCuadro',
                'class' => 'container',
                'size' => 'modal-normal',
                'header' => '<h2><b>' . $titulo . '</b></h2>',
                'closeButton' => [
                    'label' => '<b>X</b>',
                    'class' => 'btn btn-danger btn-sm pull-right'
                    ],
            ]);

            echo CemcuadroController::viewCuadro( $cua_id, $scenario, "#ModalCuadro", "#pjaxGrillas" );

        Modal::end();

    Pjax::end();
?>

<?php if ( $mensaje != '' ) { //Si existen mensajes ?>
	<script>

	$( document ).ready(function() {

		mostrarMensaje( "<?= $mensaje; ?>", "#form_divMensaje" );

	});

	</script>
<?php }  ?>


<script>

function f_abmCuadro( cua_id, scenario ){

	$.pjax.reload({
		container	: "#pjaxModal",
		type 		: "GET",
		replace		: false,
		push		: false,
        timeout     : 100000,
		data:{
            "cua_id"    : cua_id,
			"scenario"	: scenario,
		},
	});
	
	$( "#pjaxModal" ).on( "pjax:end", function() {

		$( "#ModalCuadro" ).modal( "show" );
	});
}

</script>