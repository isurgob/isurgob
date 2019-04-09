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

<div class="form-panel" style="padding-right:8px;padding-bottom: 8px">

<table width="100%" border="0">
	<tr>
		<td width="50px"><label>Nº DJ:</label></td>
		<td width="75px">
			<?=
				Html::input('text','txDJ',$model->dj_id,[
					'id'=>'ddjj-txDJ',
					'class'=>'form-control solo-lectura',
					'style'=>'width:95%;text-align:center',
					'tabIndex'=>'-1',
				]);
			?>
		</td>
		<td width="5px"></td>
		<td width="45px"><label>Tributo:</label></td>
		<td width="190px">
			<?=
				Html::input('text','txTributo',$model->trib_nom,[
					'id'=>'ddjj-txTributo',
					'class'=>'form-control solo-lectura',
					'style'=>'width:98%;text-align:left',
					'tabIndex'=> '-1',
				]);
			?>
		</td>
		<td width="10px"></td>
		<td><label>Estado:</label></td>
		<td colspan="7">
			<?=
				Html::input('text','txEstado',$model->estado,[
					'id'=>'ddjj-txEstado',
					'class' => ( $model->est == 'B' ? 'form-control baja' : 'form-control solo-lectura'),
					'style'=>'width:115px;text-align:center',
					'tabIndex' => '-1',
				]);
			?>
			<label> - </label>
			<?=
				Html::input('text','txOrden',$model->orden_nom,[
					'id'=>'ddjj-txOrden',
					'class'=>'form-control solo-lectura',
					'style'=>'width:80px;text-align:center',
					'tabIndex' => '-1',
				]);
			?>
		</td>
	</tr>

	<tr>
		<td width="40px"><label>Objeto:</label></td>
		<td>
			<?=
				Html::input('text','txObjetoID',$model->obj_id,[
					'id'=>'ddjj-txObjetoID',
					'class'=>'form-control solo-lectura',
					'style'=>'width:95%;text-align:center',
					'tabIndex' => '-1',
				]);
			?>
		</td>
		<td colspan="3">
			<?=
				Html::input('text','txObjetoNom',$model->obj_nom,[
					'id'=>'ddjj-txObjetoNom',
					'class'=>'form-control solo-lectura',
					'style'=>'width:98%;text-align:left',
					'tabIndex'=> '-1',
				]);
			?>
		</td>
		<td></td>
		<td><label>Nº IB:</label></td>
		<td>
			<?=
				Html::input('text','txIb',$model->ib,[
					'id'=>'ddjj-txIb',
					'class'=>'form-control solo-lectura',
					'style'=>'width:115px;text-align:center',
					'tabIndex'=> '-1',
				]);
			?>
	</tr>

	<tr>
		<td><label>Suc:</label></td>
		<td colspan="5">
			<?=
				Html::input('text','txSucursal',$model->subcta,[
					'id'=>'ddjj-txSucursal',
					'class'=>'form-control solo-lectura',
					'style'=>'width:15%;text-align:center',
					'tabIndex'=> '-1',
				]);
			?>
			<label>Cuit:</label>
			<?=
				Html::activeInput('text', $model, 'cuit', [
					'id'=>'ddjj_txCUIT',
					'class'=>'form-control solo-lectura',
					'style'=>'width:90px;text-align:center',
					'tabIndex' => '-1',
				]);
			?>
		</td>
		<td><label>Año:</label></td>
		<td>
			<?=
				Html::input('text','txAño',$model->anio,[
					'id'=>'ddjj-txAño',
					'class'=>'form-control solo-lectura',
					'style'=>'width:40px;text-align:center',
					'tabIndex' => '-1',
				]);
			?>
			&nbsp;&nbsp;
			<label>Cuota:</label>
			<?=
				Html::input('text','txCuota',$model->cuota,[
					'id'=>'ddjj-txCuota',
					'class'=>'form-control solo-lectura',
					'style'=>'width:40px;text-align:center',
					'tabIndex' => '-1',
				]);
			?>
		</td>
	</tr>

	<tr>
		<td><label>Fechas:</label></td>
		<td colspan="8">
			<label>Presentación:</label>
			<?=
				DatePicker::widget([
					'id' => 'ddjj-fchpresentacion',
					'name' => 'fchpresentacion',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => [
						'class' => 'form-control solo-lectura',
						'style' => 'width:80px;text-align:center',
						'tabIndex' => '-1',
					],
					'value' => ($model->fchpresenta != '' ? Fecha::usuarioToDatePicker($model->fchpresenta) : Fecha::usuarioToDatePicker($dia))
				]);
			?>
			&nbsp;&nbsp;
			<label>Vencimiento:</label>
			<?=
				DatePicker::widget([
					'id' => 'ddjj-fchvencimiento',
					'name' => 'fchvencimiento',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => [
						'class' => 'form-control solo-lectura',
						'style' => 'width:80px;text-align:center',
						'tabIndex' => '-1',
					],
					'value' => ($model->fchvenc != '' ? Fecha::usuarioToDatePicker($model->fchvenc) : Fecha::usuarioToDatePicker($dia))
				]);
			?>
		</td>
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
						['attribute'=>'nomen_nom','header' => 'Nomeclador', 'contentOptions'=>['style'=>'text-align:left','width'=>'80px']],
						['attribute'=>'rubro_id','header' => 'Rubro', 'contentOptions'=>['style'=>'text-align:left','width'=>'1%']],
						['attribute'=>'rubro_nom','header' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
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

<!-- INICIO Div Retenciones -->
<div class="form-panel" style="padding-right:8px">

<!-- INICIO Grillas Retenciones -->
<div id="ddjj_cargarRete_grillaRetenciones" class="div_grilla">

	<h3><strong>Retenciones</strong></h3>

	<?= GridView::widget([
			'id' => 'GrillaRetencionesDDJJ',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProviderRete,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'numero','header' => 'Núm.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'fecha','header' => 'Fecha', 'format' => ['date', 'php:d/m/Y'], 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'ag_rete','header' => 'Agente', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'ag_cuit','header' => 'AgR.CUIT', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'ag_nom_redu','header' => 'AgR.Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
					['attribute'=>'tcomprob','header' => 'T. Comprob.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'comprob','header' => 'Comprob', 'contentOptions'=>['style'=>'text-align:center','width'=>'1x']],
					['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
					['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
				],
		]);

		echo "<br>";

		if ( isset($dataProviderRete) ) {
			Modal::begin([
				'id' => 'Exportar',
				'header' => '<h2>Exportar Datos</h2>',
				'toggleButton' => [
					'label' => 'Exportar',
					'class' => 'btn btn-success',
				],
				'closeButton' => [
				  'label' => '<b>X</b>',
				  'class' => 'btn btn-danger btn-sm pull-right',
				]
			]);
			$descripcionExportarResumen= "Objeto: $model->obj_nom - $model->obj_id";
	        $descripcionExportarResumen .= ' Período: ' . $model->anio . '/' . $model->cuota;

			echo $this->render('//site/exportar',['titulo'=>'Listado de Retenciones','desc'=>$descripcionExportarResumen,'grilla'=>'Exportar']);

			Modal::end();
		}

		?>

</div>
</div>
<!-- FIN Div Retenciones -->

<!-- INICIO Div Izquierda -->
<div id="ddjj_info_divIzquierda" class="pull-left">

<!-- INICIO Div Liquidación -->
<div  id="ddjj_info_divLiquidacion" class="form-panel" style="padding-right:8px;padding-bottom: 8px;margin-right: 5px;width: 490px">

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
	// $arrayAnt = $dataProviderAnt->getModels();
	// $cuota12 = 0;
	// for ($i=0; $i<count($arrayAnt); $i++)
	// {
	// 	if ($arrayAnt[$i]['cuota'] == 12 and $cuota12 == 0) $cuota12 = 1;
	// }
	//
	// if ($cuota12 == 1) {
?>

<!-- INICIO Div Anticipos
<div id="ddjj_info_divAnticipos">
<h3><strong>Anticipos</strong></h3>

<!-- INICIO Grilla Anticipos
<div class="div_grilla">
	<?php
			// Pjax::begin();
			// 	echo GridView::widget([
			// 			'id' => 'GrillaInfoddjj',
			// 			'headerRowOptions' => ['class' => 'grilla'],
			// 			'rowOptions' => ['class' => 'grilla'],
			// 			'dataProvider' => $dataProviderAnt,
			// 			'summaryOptions' => ['class' => 'hidden'],
			// 			'columns' => [
			// 					['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
			// 					['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
			// 					['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
			//
			// 	        	],
			// 		]);
			// Pjax::end();
	?>

</div>
<!-- FIN Grilla Anticipos -->

</div>
<!-- FIN Div Anticipos -->

<?php //} ?>

</div>
<!-- FIN Div Izquierda -->

<!-- INICIO Div Derecha -->
<div id="ddjj_info_divDerecha" class="pull-right">

<div  id="ddjj_info_divResumen" class="form-panel" style="padding-right: 10px">
<h3><strong>Resumen</strong></h3>

<table style="margin-bottom:8px">
	<tr>
		<td width="40px"><label>Base:</label></td>
		<td>
			<?= Html::activeInput('text',$model, 'total_base', [
					'id'=>'ddjj_txBase',
					'class'=>'form-control solo-lectura',
					'style'=>'width:90px;text-align:right;background:#E6E6FA',
					'tabIndex' => '-1',
				]);
			?>
		</td>
	</tr>
	<tr>
		<td width="30px"><label>Monto:</label></td>
		<td>
			<?= Html::activeInput('text', $model, 'total_monto', [
					'id'=>'ddjj_txMonto',
					'class'=>'form-control solo-lectura',
					'style'=>'width:90px;text-align:right;background:#E6E6FA',
					'tabIndex' => '-1',
				]);
			?>
		</td>
	</tr>
</table>

</div>
</div>
<!-- Fin Div Derecha -->
<div class="clearfix"></div>



</div>


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

	var derecha = parseFloat( $( "#ddjj_info_divResumen" ).css( "height" ) ),
		izquierda = parseFloat( $( "#ddjj_info_divLiquidacion" ).css( "height" ) );

	if ( derecha > izquierda ){
		$( "#ddjj_info_divLiquidacion" ).css( "height", derecha );

	} else {
		$( "#ddjj_info_divResumen" ).css( "height", izquierda );
	}
}

$( document ).ready( function() {

	f_igualaAlturaDiv();
});
</script>
