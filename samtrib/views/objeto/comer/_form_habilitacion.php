<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/**
 * Forma que se dibuja para realizar la habilitación de un comercio.
 * Recibe:
 *		+ $model		=> Modelo de comer.
 *		+ $modelObjeto	=> Modelo de objeto
 */

$this->params['breadcrumbs'][] = ['label' => 'Objeto: ' . $model->obj_id, 'url' => ['view', 'id' => $model->obj_id]];

?>

<!-- INICIO Div Principal -->
<div id="comer_habilitacion_divPrincipal" style="width:400px">

	<h1>Habilitaci&oacute;n de Actividad Económica <?= $model->obj_id ?></h1>

	<?php
		$form = ActiveForm::begin([
					'id' => 'comer_formHabilitacion_form',
				]);
	?>

	<!-- INICIO Div Datos -->
	<div id="comer_formHabilitacion_divDatos" class="form-panel" style="margin-right: 8px" >

	<table border="0">
		<tr>
			<td width="90px"><label>Objeto: </label></td>
			<td>
				<?=
					Html::label( $model['obj_id'], null, [
						'id'		=> 'comer_formHabilitacion_txObjID',
						'class'		=> 'form-control solo-lectura',
						'style'		=> 'width:80px;text-align: center',
						'tabIndex'	=> '-1',
					]);
				?>
			</td>
			<td width="40px"></td>
			<td><label>Legajo: </label></td>
			<td>
				<?=
					Html::label( $model['legajo'], null, [
						'id'		=> 'comer_formHabilitacion_txLegajo',
						'style'		=> 'width:80px;text-align: center',
						'class'		=> 'form-control solo-lectura',
						'tabIndex'	=> '-1',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Nombre: </label></td>
			<td colspan="5">
				<?=
					Html::label( $modelObjeto['nombre'], null, [
						'id'		=> 'comer_formHabilitacion_txObjNom',
						'style'		=> 'width:99%',
						'class'		=> 'form-control solo-lectura',
						'tabIndex'	=> '-1',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Fecha actual: </label></td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'fchhab', [
						'id'		=> 'comer_formHabilitacion_txFchhab',
						'format' 	=> 'php:d/m/y',
						'style'		=> 'width:80px;text-align: center',
						'class'		=> 'form-control solo-lectura',
						'tabIndex'	=> '-1',
					]);
				?>
			</td>
			<td></td>
			<td align="right"><label>Vencimiento: </label></td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'fchvenchab', [
						'id'		=> 'comer_formHabilitacion_txFchvenchab',
						'style'		=> 'width:80px;text-align: center',
						'class'		=> 'form-control solo-lectura',
						'tabIndex'	=> '-1',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Fecha nueva:</label></td>
			<td>
				<?=
					DatePicker::widget([
						'model' => $model,
						'attribute' => 'hab_fchhab',
						'dateFormat' => 'php:d/m/Y',
						'options' => [
							'id'		=> 'comer_formHabilitacion_txNuevaFchhab',
							'class'		=> 'form-control',
							'style' => 'width:80px;text-align: center',
						],
					]);
				?>
			</td>
			<td></td>
			<td align="right"><label>Vencimiento: </label></td>
			<td>
				<?=
					DatePicker::widget([
						'model' => $model,
						'attribute' => 'hab_fchvenchab',
						'dateFormat' => 'php:d/m/Y',
						'options' => [
							'id'		=> 'comer_formHabilitacion_txNuevaFchvenchab',
							'class'		=> 'form-control',
							'style' => 'width:80px;text-align: center',
						],
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Expe: </label></td>
			<td colspan="5">
				<?=
					Html::activeInput( 'text', $model, 'expe', [
						'id'		=> 'comer_formHabilitacion_txExpe',
						'style'		=> 'width:99%',
						'class'		=> 'form-control',
						'maxlength'	=> '20',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td valign="top"><label>Obs: </label></td>
			<td colspan="5">
				<?=
					Html::activeTextarea( $model, 'obs', [
						'id'		=> 'comer_formHabilitacion_txObs',
						'style'		=> 'width:99%; height: 60px; resize: none',
						'class'		=> 'form-control',
						'maxlength'	=> '500',
					]);
				?>
			</td>
		</tr>

	</table>

	</div>
	<!-- FIN Div Datos -->

	<!-- INICIO Div Botones -->
	<div style="margin-top:5px;">
	<?=
		Html::button('Grabar', [
			'onclick'	=> 'f_btGrabar()',
			'class' => 'btn btn-success',
		]);
	?>
	&nbsp;&nbsp;
	<?= Html::a('Cancelar', ['view', 'id' => $model->obj_id], ['class' => 'btn btn-primary']) ?>
	</div>
	<!-- FIN Div Botones -->

	<?php ActiveForm::end(); ?>

	<!-- INICIO Div Errores -->
	<?php
		echo $form->errorSummary( $model, [
			'id'	=> 'comer_formHabilitacion_errorSummary',
			'style'	=> 'margin-top: 8px',
		]);
	?>
	<!-- FIN Div Errores -->

</div>
<!-- FIN Div Principal -->

<script>
function f_btGrabar(){

	var error 	= new Array(),
		fechaNueva				= $( "#comer_formHabilitacion_txNuevaFchhab" ).val(),
		fechaNuevaVencimiento	= $( "#comer_formHabilitacion_txNuevaFchvenchab" ).val(),
		expe					= $( "#comer_formHabilitacion_txExpe" ).val(),
		obs						= $( "#comer_formHabilitacion_txObs" ).val();

	if( fechaNueva == '' ){
		error.push( "Ingrese una nueva fecha." );
	}

	if( fechaNuevaVencimiento == '' ){
		error.push( "Ingrese una nueva fecha de vencimiento." );
	}

	if( expe == '' ){
		error.push( "Ingrese un expediente." );
	}

	if( error.length == 0 ){

		$( "#comer_formHabilitacion_form" ).submit();

	} else {

		mostrarErrores( error, "#comer_formHabilitacion_errorSummary" );
	}

}
</script>
