<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Forma que se utiliza para realizar el cambio de denominación.
 * Recibe:
 *		+ $model		=> Modelo de comer.
 *		+ $modelObjeto	=> Modelo de objeto
 */
$this->params['breadcrumbs'][] = ['label' => 'Objeto ' . $model->obj_id, 'url' => ['view', 'id' => $model->obj_id ] ];

?>

<!-- INICIO Div Principal -->
<div id="comer_formDenominacion_divPrincipal" style="width:500px">

	<h1>Cambio de denominaci&oacute;n</h1>

	<!-- INICIO Div Datos actuales -->
	<div id="comer_formDenominacion_divDatosActuales" class="form-panel">

		<h3><label>Datos Actuales</label></h3>

		<table border="0">
			<tr>
				<td width="50px"><label>Objeto: </label></td>
				<td>
					<?=
						Html::activeInput( 'hidden', $model, 'obj_id' );
					?>
					<?=
						Html::label( $model['obj_id'], null, [
							'id'		=> 'comer_form_txObjID',
							'class'		=> 'form-control solo-lectura',
							'style'		=> 'width:80px;text-align: center',
							'tabIndex'	=> '-1',
						]);
					?>
				</td>
				<td width="10px"></td>
				<td><label>Nombre: </label></td>
				<td>
					<?=
						Html::label( $modelObjeto['nombre'], null, [
							'id'		=> 'comer_form_txObjNom',
							'style'		=> 'width:260px',
							'class'		=> 'form-control solo-lectura',
							'tabIndex'	=> '-1',
						]);
					?>
				</td>
			</tr>
		</table>

	</div>
	<!-- FIN Div Datos actuales -->

	<?php $form = ActiveForm::begin([ 'id' => 'comer_formDenominacion_form' ]); ?>

	<!-- INICIO Div Datos nuevos -->
	<div id="comer_formDenominacion_divDatosNuevos" class="form-panel">

		<h3><label>Datos Nuevos</label></h3>

		<table border="0" width="100%">
			<tr>
				<td width="50px"><label>Nombre:</label></td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'den_obj_nom', [
							'id'		=> 'comer_formDenominacion_txNombre',
							'style'		=> 'width:400px',
							'class'		=> 'form-control',
							'maxlength'	=> '50',
						]);
					?>
				</td>
				<td width="240px"></td>
			</tr>

			<tr>
				<td><label>Expe: </label></td>
				<td colspan="5">
					<?=
						Html::activeInput( 'text', $model, 'expe', [
							'id'		=> 'comer_formDenominacion_txExpe',
							'style'		=> 'width:200px',
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
							'id'		=> 'comer_formDenominacion_txObs',
							'style'		=> 'width:99%; height: 60px; resize: none',
							'class'		=> 'form-control',
							'maxlength'	=> '500',
						]);
					?>
				</td>
			</tr>
		</table>

	</div>
	<!-- FIN Div Datos nuevos -->

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
			'id'	=> 'comer_formDenominacion_errorSummary',
			'style'	=> 'margin-top: 8px',
		]);
	?>
	<!-- FIN Div Errores -->

</div>
<!-- FIN Div Principal -->

<script>
function f_btGrabar(){

	var error 	= new Array(),
		nombre	= $( "#comer_formDenominacion_txNombre" ).val(),
		expe	= $( "#comer_formDenominacion_txExpe" ).val(),
		obs		= $( "#comer_formDenominacion_txObs" ).val();

	if( nombre == '' ){
		error.push( "Ingrese una nueva denominación." );
	}

	if( expe == '' ){
		error.push( "Ingrese un expediente." );
	}

	if( obs == '' ){
		error.push( "Ingrese una observación." );
	}

	if( error.length == 0 ){

		$( "#comer_formDenominacion_form" ).submit();

	} else {

		mostrarErrores( error, "#comer_formDenominacion_errorSummary" );
	}

}
</script>
