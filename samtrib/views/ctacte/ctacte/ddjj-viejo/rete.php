<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\controllers\ctacte\DdjjController;

/**
 * Forma que se dibuja para mostrar las retenciones asociadas a una DJ.
 * Recibo:
 *			=> $dataProviderReteCargadas	-> Listado de retenciones cargadas.
 */

?>
<style>

.div_grilla {

	margin-bottom: 10px;
}
</style>

<!-- INICIO Div Principal -->
<div class="ddjj_cargarRete">

<?php
ActiveForm::begin([
	'id' => 'DDJJ_rete_form',
	'action'	=> ['rete'],
]);
?>
<div class="form-panel" style="padding-right:8px">

<!-- INICIO Grillas Retenciones -->
<div id="ddjj_cargarRete_grillaRetenciones" style="padding-right:8px">

	<h3><strong>Retenciones:</strong></h3>

	<?= GridView::widget([
			'id' => 'GrillaRetencionesDDJJ',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProviderReteCargadas,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'numero','header' => 'Núm.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'fecha','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'lugar','header' => 'Lugar', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
					['attribute'=>'tcomprob','header' => 'T. Comprob.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'comprob','header' => 'Comprob', 'contentOptions'=>['style'=>'text-align:center','width'=>'1x']],
					['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
					['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
				],
		]);
		?>
</div>
</div>





























<?php if( $action == 4 ){ ?>

<div id="retenciones_nuevas" style="margin-top: 8px">

<div class="pull-left">
    <h3><strong>Retenciones Nuevas</strong></h3>
</div>

<!-- INICIO Botón "Nueva Retención" -->
<div class="pull-right">
	<?=
		Html::button('Agregar Retenciones',[
			'id'		=> 'btAgregarRetenciones',
			'class'		=> 'btn btn-success',
			'onclick'	=> 'f_agregarRetencion()',
		]);
	?>
</div>
<!-- FIN Botón "Nueva Retención" -->

<div class="div_grilla">
<?php

	echo Html::input( 'hidden', 'dj_id', $model->dj_id );


	Pjax::begin([ 'id' => 'PjaxCargarRete', 'enableReplaceState' => false, 'enablePushState' => false ]);

		echo GridView::widget([
				'id' => 'GrillaRetencionesNuevasDDJJ',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProviderRete,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
                        // Cargar
                        ['content'=> function($model, $key, $index, $column) {
                            return Html::checkbox('DDJJ_rete[]',0,
                                    [
                                        'id' => $model['ret_id'],
                                        'style' => 'width:20px;padding: 0px;margin: 0px;',
                                        'onchange' => 'f_cambiaCheck()',
										'value'		=> $model['ret_id'],
                                    ]);
                            },
                        'label' => 'Cargar',
                        'contentOptions'=>['style'=>'width:20px;text-align:center'],
                        'headerOptions' => ['style' => 'width:30px'],
                        ],
						['attribute'=>'numero','header' => 'Núm.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'fecha','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
                        ['attribute'=>'lugar','header' => 'Lugar', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
						['attribute'=>'tcomprob','header' => 'Tipo', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'comprob','header' => 'Comprob', 'contentOptions'=>['style'=>'text-align:center','width'=>'1x']],
						['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
						['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
		        	],
			]);

	Pjax::end();


?>
</div>

</div>

<?php } ?>
</div>
<!-- FIN Grillas Retenciones -->

<?php if( $action == 4 ){   //Cuando se cargan las retenciones ?>
<!-- INICIO Div Botones -->
<div id="ddjj_cargarRete_divBotones">
	<?=
	Html::submitButton('Aceptar', [
		'id' => 'ddjj_cargarRete_btAceptar',
		'class' => 'btn btn-success',

	]);
	?>

    &nbsp;&nbsp;

    <?=
        Html::a('Cancelar', ['view','id' => $model->dj_id], [
            'class' => 'btn btn-primary',

        ]);
    ?>
</div>
<!-- FIN Div Botones -->

<?php } ?>

<?php ActiveForm::end(); ?>

<!-- INICIO Div Errores -->
<div id="ddjj_cargarRete_errorSummary" class="error-summary" style="display:none;margin-top: 8px">
</div>
<!-- FIN Div Errores -->

</div>
<!-- FIN Div Principal -->

<!-- INICIO Ventana Modal "Nueva Retención" -->
<?php

Modal::begin([
    'id' => 'modalNuevaRetencion',
    'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Nueva Retención</h2>',
    'size' => 'modal-normal',
    'closeButton' => [
      'label' => '<b>X</b>',
      'class' => 'btn btn-danger btn-sm pull-right',
    ],
]);

echo DdjjController::nuevaRete( $model->dj_id, $model->obj_id );

Modal::end();

?>
<!-- FIN Ventana Modal "Nueva Retención" -->

<script>

function f_cambiaCheck(){

    var $checks = $( "#GrillaRetencionesNuevasDDJJ input:checkbox:checked" );

    $( "#ddjj_cargarRete_errorSummary" ).css( "display", "none" );

    if( $checks.length == 0 ){
		$( "#btAgregarRetenciones" ).attr( "disabled", false );
        $( "#ddjj_cargarRete_btAceptar" ).attr( "disabled", true );
    } else {
		$( "#btAgregarRetenciones" ).attr( "disabled", true );
        $( "#ddjj_cargarRete_btAceptar" ).attr( "disabled", false );
    }
}

function f_agregarRetencion(){

	$( "#modalNuevaRetencion" ).modal( "show" );
}

function f_btAceptar()
{
    var error = new Array(),
        rete = new Array(),
        $checks = $( "#GrillaRetencionesNuevasDDJJ input:checkbox:checked" );

    $checks.each(function() {
        rete.push( $(this).attr( "id" ) );
    });

    $.pjax.reload({
        container: "#PjaxCargarRete",
        type: "GET",
        replace: false,
        push: false,
        data:{
            "retenciones": rete,
        },
    });

}

$( document ).ready( function() {

    f_cambiaCheck();
})
</script>
