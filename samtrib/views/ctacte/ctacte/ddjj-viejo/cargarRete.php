<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;

/**
 * Forma que se dibuja pra cargar retenciones a una DJ.
 * Recibo:
 * 			=> $model -> Modelo de Ddjj
 * 			=> $dataProviderRete -> Listado de retenciones.
 *          => $totales -> Arreglo con los totales de base y monto.
 *          => $error   -> Variable que contendrá los mensajes de error.
 */

 $title = 'Cargar Retenciones';


?>
<style>

.div_grilla {

	margin-bottom: 10px;
}
</style>

<!-- INICIO Div Principal -->
<div class="ddjj_cargarRete">

<h1><?= Html::encode($title) ?></h1>

<div class="form-panel" style="padding-right:8px">
<table>
	<tr>
		<td width="40px"><label>DJ:</label></td>
		<td><?= Html::input('text','txDJ',$model->dj_id,['id'=>'ddjj_cargarRete_txDJ','class'=>'form-control solo-lectura','style'=>'width:50px;text-align:center','tabIndex' => '-1']) ?></td>
		<td width="50px"><label>Tributo:</label></td>
		<td><?= Html::input('text','txTributo',$model->trib_nom,['id'=>'ddjj_cargarRete_txTributo','class'=>'form-control solo-lectura','style'=>'width:200px;text-align:left','tabIndex' => '-1']) ?></td>
        <td width="20px"></td>
        <td colspan="1" width="45px"><label>Estado:</label></td>
        <td colspan="6">
            <?= Html::input('text','txEstado',$model->estado,['id'=>'ddjj_cargarRete_txEstado','class'=>($model->est == 'B' ? 'form-control baja' : 'form-control solo-lectura'),'style'=>'width:80px;text-align:center','tabIndex' => '-1']) ?>
		    <label> - </label>
            <?= Html::input('text','txOrden',$model->orden_nom,['id'=>'ddjj_cargarRete_txOrden','class'=>'form-control solo-lectura','style'=>'width:80px;text-align:center','tabIndex' => '-1']) ?>
        </td>
	</tr>

	<tr>
		<td width="40px"><label>Objeto:</label></td>
		<td><?= Html::input('text','txObjetoID',$model->obj_id,['id'=>'ddjj_cargarRete_txObjetoID','class'=>'form-control solo-lectura','style'=>'width:80px;text-align:center','tabIndex' => '-1']) ?></td>
		<td colspan="2"><?= Html::input('text','txObjetoNom',$model->obj_nom,['id'=>'ddjj_cargarRete_txObjetoNom','class'=>'form-control solo-lectura','style'=>'width:250px;text-align:left','tabIndex' => '-1']) ?></td>
		<td></td>
		<td width="30px"><label>Suc:</label></td>
		<td><?= Html::input('text','txSucursal',null,['id'=>'ddjj_cargarRete_txSucursal','class'=>'form-control solo-lectura','style'=>'width:30px;text-align:center','tabIndex' => '-1']) ?></td>
		<td width="20px"></td>
		<td width="30px"><label>Año:</label></td>
		<td><?= Html::input('text','txAño',$model->anio,['id'=>'ddjj_cargarRete_txAño','class'=>'form-control solo-lectura','style'=>'width:40px;text-align:center','tabIndex' => '-1']) ?></td>
		<td width="20px"></td>
		<td width="30px"><label>Cuota:</label></td>
		<td><?= Html::input('text','txCuota',$model->cuota,['id'=>'ddjj_cargarRete_txCuota','class'=>'form-control solo-lectura','style'=>'width:40px;text-align:center','tabIndex' => '-1']) ?></td>
	</tr>
</table>

<table border="0">
	<tr>
		<td width="60px"><label>Presentación:</label></td>
		<td width="80px"><?=  DatePicker::widget([	'id' => 'ddjj_cargarRete_fchpresentacion',
													'name' => 'fchpresentacion',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => ['class' => 'form-control solo-lectura', 'style' => 'width:80px;text-align:center','tabindex'=>'-1'],
													'value' => ($model->fchpresenta != '' ? Fecha::usuarioToDatePicker($model->fchpresenta) : Fecha::usuarioToDatePicker($dia))
												]);	?>
		</td>
		<td width="57px"></td>
		<td width="55px"><label>Vencimiento:</label></td>
		<td width="80px"><?= DatePicker::widget([	'id' => 'ddjj_cargarRete_fchvencimiento',
													'name' => 'fchvencimiento',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => ['class' => 'form-control solo-lectura', 'style' => 'width:80px;text-align:center','tabindex'=>'-1'],
													'value' => ($model->fchvenc != '' ? Fecha::usuarioToDatePicker($model->fchvenc) : Fecha::usuarioToDatePicker($dia))
												]);	?>
		</td>
		<td width="20px"></td>
		<td width="45px"><label>Tipo:</label></td>
		<td width="78px">
            <?=
                Html::input('text','txTipo',$model->tipo_nom,[
                    'id'=>'ddjj_cargarRete_txTipo',
                    'class'=>'form-control solo-lectura',
                    'style'=>'width:78px;text-align:center',
                    'tabIndex' => '-1',
                ]);
            ?>
        </td>
	</tr>
</table>

<div class="pull-right">
<table>
    <tr>
        <td><label>Base:</label></td>
        <td>
            <?=
                Html::input('text','txBase',$totales['base'],[
                    'id'=>'ddjj_cargarRete_txBase',
                    'class'=>'form-control solo-lectura',
                    'style'=>'width:80px;text-align:right',
                    'tabIndex' => '-1',
                ]);
            ?>
        </td>
        <td width="20px"></td>
        <td><label>Monto:</label></td>
        <td>
            <?=
                Html::input('text','txMonto',$totales['monto'],[
                    'id'=>'ddjj_cargarRete_txMonto',
                    'class'=>'form-control solo-lectura',
                    'style'=>'width:80px;text-align:right',
                    'tabIndex' => '-1',
                ]);
            ?>
        </td>
    </tr>
</table>
</div>

<div class="clearfix"></div>
<div class="separador-horizontal" style="margin-top: 6px"></div>

<!-- INICIO Grilla Retenciones -->
<div id="ddjj_cargarRete_grillaRetenciones" style="padding-right:8px">
<table>
	<tr>
		<td><h3><strong>Retenciones</strong></h3></td>
	</tr>
</table>
<div class="div_grilla">
<?php

	Pjax::begin();
		echo GridView::widget([
				'id' => 'GrillaRetencionesDDJJ',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProviderRete,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
                        // Base
                        ['content'=> function($model, $key, $index, $column) {
                            return Html::checkbox('DDJJ_base',0,
                                    [
                                        'id' => $model['ret_id'],
                                        'style' => 'width:20px;padding: 0px;margin: 0px;',
                                        'onchange' => 'f_cambiaCheck()',
                                    ]);
                            },
                        'label' => 'Cargar',
                        'contentOptions'=>['style'=>'width:20px;text-align:center'],
                        'headerOptions' => ['style' => 'width:30px'],
                        ],
						['attribute'=>'numero','header' => 'Núm.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'fecha','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:left','width'=>'1px']],
                        ['attribute'=>'lugar','header' => 'Lugar', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
						['attribute'=>'tcomprob','header' => 'Tipo', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'comprob','header' => 'Comprob', 'contentOptions'=>['style'=>'text-align:center','width'=>'1x']],
						['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
						['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
		        	],
			]);

	Pjax::end();

    Pjax::begin(['id' => 'PjaxCargarRete', 'enableReplaceState' => false, 'enablePushState' => false]);

        if( $error != '' )
        {
            ?>
            <script>

                $( document ).ready(function() {

                    mostrarErrores(["<?= $error ?>"], "#ddjj_cargarRete_errorSummary" );

                });

            </script>

            <?php
        }
    Pjax::end();
?>
</div>
</div>
<!-- FIN Grilla Rubros -->

</div>

<!-- INICIO Div Botones -->
<div id="ddjj_cargarRete_divBotones">
    <?=
        Html::button('Aceptar', [
            'id' => 'ddjj_cargarRete_btAceptar',
            'class' => 'btn btn-success',
            'onclick' => 'f_btAceptar()',
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

<!-- INICIO Div Errores -->
<div id="ddjj_cargarRete_errorSummary" class="error-summary" style="display:none;margin-top: 8px">
</div>
<!-- FIN Div Errores -->
</div>
<!-- FIN Div Principal -->

<script>

function f_cambiaCheck(){

    var $checks = $( "#GrillaRetencionesDDJJ input:checkbox:checked" );

    $( "#ddjj_cargarRete_errorSummary" ).css( "display", "none" );

    if( $checks.length == 0 ){
        $( "#ddjj_cargarRete_btAceptar" ).attr( "disabled", true );
    } else {
        $( "#ddjj_cargarRete_btAceptar" ).attr( "disabled", false );
    }
}

function f_btAceptar()
{
    var error = new Array(),
        rete = new Array(),
        $checks = $( "#GrillaRetencionesDDJJ input:checkbox:checked" );

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
