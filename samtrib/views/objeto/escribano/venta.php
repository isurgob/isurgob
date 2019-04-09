<?php

use yii\helpers\Html;
use yii\grid\GridView;

?>

<div class="form-cuerpo">

    <h1 id='h1titulo'>Operaciones de Venta</h1>
	
	<div class="form" style='padding:10px; margin:5px 0px;width:100%'>
		<table border="0" width="100%">
			<tr>
				<td width="5%"> <label> Id: </label> </td>
				<td width="23%">
					<?= Html::input('text', 'Escribano[vta_id]', $model->vta_id, [
							'class' => 'form-control solo-lectura', 
							'id' => 'vta_id', 
							'style' => 'width:97%; text-align:center',
							'tabindx' => -1
						]); 
					?>
				</td>
				<td width="3%"> </td>
				<td width="11%"> </td>
				<td align='right' colspan='2'>
					<label> Estado: </label>
					<?= Html::input('text', 'Escribano[est_nom]', $model->est_nom, [
							'class' => 'form-control solo-lectura', 
							'id' => 'est_nom', 
							'style' => 'width:50%',
							'tabindx' => -1
						]); 
					?>
				</td>
			</tr>
			<tr>
				<td> <label> Inmueble: </label> </td>
				<td>
					<?= Html::input('text', 'Escribano[obj_id]', $model->obj_id, [
							'class' => 'form-control solo-lectura', 
							'id' => 'obj_id', 
							'style' => 'width:97%;text-align:center',
							'tabindx' => -1
						]); 
					?>
				</td>
				<td colspan="4" align='right'>
					<?= Html::input('text', 'Escribano[inm_nom]', $model->inm_nom, [
							'class' => 'form-control solo-lectura', 
							'id' => 'inm_nom', 
							'style' => 'width:99%',
							'tabindx' => -1
						]); 
					?>
				</td>
			</tr>
			<tr>
				<td> <label> Momeclatura: </label> </td>
				<td>
					<?= Html::input('text', 'Escribano[nc]', $model->nc, [
							'class' => 'form-control solo-lectura', 
							'id' => 'nc', 
							'style' => 'width:97%;text-align:center',
							'tabindx' => -1
						]); 
					?>
				</td>
				<td colspan="5" align="right">
					<label> Domicilio: </label>
					<?= Html::input('text', 'Escribano[domicilio]', $model->domicilio, [
							'class' => 'form-control solo-lectura', 
							'id' => 'domicilio', 
							'style' => 'width:85%',
							'tabindx' => -1
						]); 
					?>
				</td>
			</tr>
		</table>
	</div>
		
	<div class="clearfix pull-left"> <u> Comprador </u> </div>
	
	<div class="clearfix"></div>
	
	<?= GridView::widget([
			'id' => 'GrillaComprador',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dpComprador,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'cuit','header' => 'Cuit', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['width'=>'1px']],
					['attribute'=>'tnac_nom','header' => 'Nacionalidad', 'contentOptions'=>['width'=>'1px']],
					['attribute'=>'porc','header' => 'Porcentaje', 'contentOptions'=>['width'=>'1x', 'style' => 'align:right']],
					['attribute'=>'dompo','header' => 'Domicilio', 'contentOptions'=>['width'=>'1px']],
					
				],
		]);
	?>
	
	<div class="clearfix"> <br> </div>
	
	<div class="clearfix pull-left"> <u> Vendedor </u> </div>
	
	<div class="clearfix"></div>
	
	<?= GridView::widget([
			'id' => 'GrillaVendedor',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dpVendedor,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'cuit','header' => 'Cuit', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['width'=>'1px']],
					['attribute'=>'tnac_nom','header' => 'Nacionalidad', 'contentOptions'=>['width'=>'1px']],
					['attribute'=>'porc','header' => 'Porcentaje', 'contentOptions'=>['width'=>'1x', 'style' => 'align:right']],
					['attribute'=>'dompo','header' => 'Domicilio', 'contentOptions'=>['width'=>'1px']],
					
				],
		]);
	?>
		
		
	<div style="margin-top:10px;">

		<?=
			Html::a( 'Volver', [ 'index' ], [
				'id'    => 'venta_form_btCancelar',
				'class' => 'btn btn-primary',
			]);
		?>

	</div>
		
</div>
