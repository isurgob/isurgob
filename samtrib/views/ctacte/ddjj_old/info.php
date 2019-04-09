<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\controllers\ctacte\DdjjController;

/**
 * Forma que se dibuja cuando se llega a Declaraciones Juradas (DDJJ)
 * Recibo:
 * 			=> $model -> Modelo de Ddjj
 * 			=> $dataProviderRubros -> Datos para la grilla de rubros
 *  		=> $dataProviderLiq	-> Datos para la grilla de liquidación
 * 			=> $dataProviderAnt -> Datos para la grilla de anicipo
 */

/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */

?>
<style>

.div_grilla {

	margin-bottom: 10px;
}
</style>

<div class="ddjj-info">
<div class="form-panel" style="padding-right:8px">
<table width="100%">
	<tr>
		<td width="40px"><label>DJ:</label></td>
		<td><?= Html::input('text','txDJ',$model->dj_id,['id'=>'ddjj-txDJ','class'=>'form-control','style'=>'width:50px;text-align:center','readOnly'=>true]) ?></td>
		<td width="15px"></td>
		<td width="50px"><label>Tributo:</label></td>
		<td><?= Html::input('text','txTributo',$model->trib_nom,['id'=>'ddjj-txTributo','class'=>'form-control','style'=>'width:212px;text-align:left','readOnly'=>true]) ?></td>
		<td align="right"><label>Estado:</label>
		<?= Html::input('text','txEstado',$model->estado,['id'=>'ddjj-txEstado','class'=>($model->est == 'B' ? 'form-control baja' : 'form-control solo-lectura'),'style'=>'width:80px;text-align:center','readOnly'=>true]) ?>
		<label> - </label>
		<?= Html::input('text','txOrden',$model->orden_nom,['id'=>'ddjj-txOrden','class'=>'form-control','style'=>'width:80px;text-align:center','readOnly'=>true]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="40px"><label>Objeto:</label></td>
		<td><?= Html::input('text','txObjetoID',$model->obj_id,['id'=>'ddjj-txObjetoID','class'=>'form-control','style'=>'width:80px;text-align:center','readOnly'=>true]) ?></td>
		<td><?= Html::input('text','txObjetoNom',$model->obj_nom,['id'=>'ddjj-txObjetoNom','class'=>'form-control','style'=>'width:250px;text-align:left','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="30px"><label>Suc:</label></td>
		<td><?= Html::input('text','txSucursal',null,['id'=>'ddjj-txSucursal','class'=>'form-control','style'=>'width:30px;text-align:center','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="30px"><label>Año:</label></td>
		<td><?= Html::input('text','txAño',$model->anio,['id'=>'ddjj-txAño','class'=>'form-control','style'=>'width:40px;text-align:center','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="30px"><label>Cuota:</label></td>
		<td><?= Html::input('text','txCuota',$model->cuota,['id'=>'ddjj-txCuota','class'=>'form-control','style'=>'width:40px;text-align:center','readOnly'=>true]) ?></td>
	</tr>
</table>

<table border="0">
	<tr>
		<td width="60px"><label>Presentación:</label></td>
		<td width="80px"><?=  DatePicker::widget([	'id' => 'ddjj-fchpresentacion',
													'name' => 'fchpresentacion',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => ['class' => 'form-control', 'style' => 'width:80px;text-align:center', 'readOnly'=>true,'tabindex'=>'-1'],
													'value' => ($model->fchpresenta != '' ? Fecha::usuarioToDatePicker($model->fchpresenta) : Fecha::usuarioToDatePicker($dia))
												]);	?>
		</td>
		<td width="57px"></td>
		<td width="55px"><label>Vencimiento:</label></td>
		<td width="80px"><?= DatePicker::widget([	'id' => 'ddjj-fchvencimiento',
													'name' => 'fchvencimiento',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => ['class' => 'form-control', 'style' => 'width:80px;text-align:center', 'readOnly'=>true,'tabindex'=>'-1'],
													'value' => ($model->fchvenc != '' ? Fecha::usuarioToDatePicker($model->fchvenc) : Fecha::usuarioToDatePicker($dia))
												]);	?>
		</td>
		<td width="40px"></td>
		<td width="45px"><label>Tipo:</label></td>
		<td width="78px"><?= Html::input('text','txTipo',$model->tipo_nom,['id'=>'ddjj-txTipo','class'=>'form-control','style'=>'width:78px;text-align:center','readOnly'=>true]) ?></td>
	</tr>
</table>
</div>

<!-- INICIO Grilla Rubros -->
<div class="form-panel" style="padding-right:8px">
<table>
	<tr>
		<td><h3><strong>Rubros Declarados</strong></h3></td>
	</tr>
</table>
<div class="div_grilla">
<?php

	Pjax::begin();
		echo GridView::widget([
				'id' => 'GrillaRubrosddjj',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProviderRubros,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'trib_nom','header' => 'Trib', 'contentOptions'=>['style'=>'text-align:left','width'=>'55px']],
						['attribute'=>'rubro_nom','header' => 'Rubro', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
						['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
						['attribute'=>'cant','header' => 'Cant', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
						['attribute'=>'alicuota','header' => 'Ali', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
						['attribute'=>'minimo','header' => 'Mín', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
						['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
		        	],
			]);

	Pjax::end();
?>
</div>
</div>
<!-- FIN Grilla Rubros -->

<!-- INICIO Div Izquierda -->
<div id="ddjj_info_divIzquierda" class="pull-left form-panel" style="width:480px">

<!-- INICIO Div Liquidación -->
<div id="ddjj_info_divLiquidacion">

<h3><strong>Liquidación</strong></h3>

<!-- INICIO Grilla Liquidación -->
<div class="div_grilla">
	<?php
			Pjax::begin();
				echo GridView::widget([
						'id' => 'GrillaInfoddjj',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dataProviderLiq,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
								['attribute'=>'item_id','header' => 'Ítem', 'contentOptions'=>['style'=>'text-align:center','width'=>'30px']],
								['attribute'=>'item_nom','header' => 'Descrip.', 'contentOptions'=>['style'=>'text-align:left','width'=>'250px']],
								['attribute'=>'item_monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'60px']],
				        	],
					]);

			Pjax::end();

	?>
</div>
<!-- FIN Grilla Liquidación -->

</div>
<!-- FIN Div Liquidación -->

<?php
	$arrayAnt = $dataProviderAnt->getModels();
	$cuota12 = 0;
	for ($i=0; $i<count($arrayAnt); $i++)
	{
		if ($arrayAnt[$i]['cuota'] == 12 and $cuota12 == 0) $cuota12 = 1;
	}

	if ($cuota12 == 1) {
?>

<!-- INICIO Div Anticipos -->
<div id="ddjj_info_divAnticipos">
<h3><strong>Anticipos</strong></h3>

<!-- INICIO Grilla Anticipos -->
<div class="div_grilla">
	<?php
			Pjax::begin();
				echo GridView::widget([
						'id' => 'GrillaInfoddjj',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dataProviderAnt,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
								['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],

				        	],
					]);
			Pjax::end();
	?>

</div>
<!-- FIN Grilla Anticipos -->

</div>
<!-- FIN Div Anticipos -->

<?php } ?>

</div>
<!-- FIN Div Izquierda -->

<!-- INICIO Div Derecha -->
<div id="ddjj_info_divDerecha" class="pull-right form-panel">

		<?php

			//INICIO Calculo y Seteo los valores para base y monto
			$array = $dataProviderRubros->getModels();

			$baseTotal = 0;
			$montoTotal = 0;

			if (count($array > 0))
			{
				//Calculo Base
				foreach ($array as $rubros)
				{
					$baseTotal += $rubros['base'];
				}

				//Calculo Monto
				$array = $dataProviderLiq->getModels();
				foreach ($array as $rubros)
				{
					$montoTotal += $rubros['item_monto'];
				}
			}
			//FIN Calculo y Seteo los valores para base y monto

		?>
<h3><strong>Resumen</strong></h3>

<table style="margin-bottom:8px">
	<tr>
		<td width="40px"><label>Base:</label></td>
		<td><?= Html::input('text','txBase',number_format($baseTotal,2,'.',''),['id'=>'ddjj-txBase','class'=>'form-control','style'=>'width:80px;text-align:right;background:#E6E6FA','readOnly'=>true]) ?></td>
	</tr>
	<tr>
		<td width="30px"><label>Monto:</label></td>
		<td><?= Html::input('text','txMonto',number_format($montoTotal,2,'.',''),['id'=>'ddjj-txMonto','class'=>'form-control','style'=>'width:80px;text-align:right;background:#E6E6FA','readOnly'=>true]) ?></td>
	</tr>
	<tr>
		<td width="30px"><label>Multa:</label></td>
		<td><?= Html::input('text','txMulta',number_format(0,2,'.',''),['id'=>'ddjj-txMulta','class'=>'form-control','style'=>'width:80px;text-align:right;background:#E6E6FA','readOnly'=>true]) ?></td>
	</tr>
</table>

</div>
<!-- Fin Div Derecha -->
<div class="clearfix"></div>

<!-- INICIO Div Retenciones -->

<?= DdjjController::cargarrete( $model->dj_id, $consulta ); ?>

</div>
<!-- FIN Div Retenciones -->

<?php

	//INICIO Bloque código para eliminar una DJ
	Pjax::begin();

		//INICIO Modal eliminar DDJJ
		Modal::begin([
				'id' => 'ModalEliminarDDJJ',
				'size' => 'modal-sm',
				'header' => '<h4><b>Confirmar Eliminación</b></h4>',
				'closeButton' => [
    				'label' => '<b>X</b>',
        			'class' => 'btn btn-danger btn-sm pull-right',
        			'id' => 'btCancelarModalElim'
    				],
			]);

			echo "<center>";
			echo "<p><label>¿Esta seguro que desea eliminar la DJ?</label></p><br>";

			echo Html::a('Aceptar', ['borrar','id' => $model->dj_id],['class' => 'btn btn-success',]);

			echo "&nbsp;&nbsp;";
	 		echo Html::Button('Cancelar', ['id'=>'btEliminarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalEliminarDDJJ").modal("hide")']);
	 		echo "</center>";

	 	Modal::end();
		//FIN Modal eliminar DDJJ

	Pjax::end();
	//FIN Bloque código para eliminar una DJ

?>

</div>

<script>

function f_igualaAlturaDiv(){

	var derecha = parseFloat( $( "#ddjj_info_divDerecha" ).css( "height" ) ),
		izquierda = parseFloat( $( "#ddjj_info_divIzquierda" ).css( "height" ) );

	if ( derecha > izquierda ){
		$( "#ddjj_info_divIzquierda" ).css( "height", derecha );

	} else {
		$( "#ddjj_info_divDerecha" ).css( "height", izquierda );
	}
}

$( document ).ready( function() {

	f_igualaAlturaDiv();
});
</script>
