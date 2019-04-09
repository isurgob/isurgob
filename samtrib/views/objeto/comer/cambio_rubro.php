<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use yii\bootstrap\Modal;
use app\controllers\objeto\RubroController;

$this->params['breadcrumbs'][] = ['label' => 'Objeto ' . $model->obj_id, 'url' => ['view', 'id' => $model->obj_id ] ];

$taccion = isset($taccion) ? intval($taccion) : 13;

?>

<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>

<!-- INICIO Div Principal -->
<div id="comer_actionRubro_divPrincipal">

	<h1><?= $taccion == 13 ? 'Cambio de Rubro' : 'Anexo de Rubros' ?></h1>

	<?php $form = ActiveForm::begin([ 'id' => 'comer_actionRubro_form' ]); ?>

	<?= Html::input( 'hidden', 'txTAccion', $taccion ); ?>

	<!-- INICIO Div Datos actuales -->
	<div id="comer_actionRubro_divDatosActuales" class="form-panel">

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
				<td>
					<?=
						Html::label( $modelObjeto['nombre'], null, [
							'id'		=> 'comer_form_txObjNom',
							'style'		=> 'width:400px',
							'class'		=> 'form-control solo-lectura',
							'tabIndex'	=> '-1',
						]);
					?>
				</td>
			</tr>
		</table>

	</div>
	<!-- FIN Div Datos actuales -->

	<!-- INICIO Div Rubros Actuales -->
	<div id="comer_actionRubro_divRubrosActuales" class="form-panel">

		<h3><label>Rubros Actuales</label></h3>

		<?php Pjax::begin([ 'id' => 'comer_actionRubro_pjaxRubrosActuales', 'enableReplaceState' => false, 'enablePushState' => false ]); ?>

			<?=
				GridView::widget([
					'dataProvider' => $dpRubrosActuales,
					'rowOptions' => ['class' => 'grilla'],
					'headerRowOptions' => ['class' => 'grilla'],
					'summaryOptions'	=> [ 'class' => 'hidden' ],
					'columns' => [
						
						['attribute' => 'rubro_id', 'label' => 'Código', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center']],
						['attribute' => 'rubro_nom', 'label' => 'Nombre'],
						['attribute' => 'perdesde', 'label' => 'Desde', 'contentOptions' => [ 'style' => 'text-align: center']],
						['attribute' => 'perhasta', 'label' => 'Hasta', 'contentOptions' => [ 'style' => 'text-align: center']],
						['attribute' => 'tipo_nom', 'label' => 'Tipo', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center']],
						['attribute' => 'cant', 'label' => 'Cant',  'contentOptions' => [ 'style' => 'width: 1px; text-align: center']],
						['attribute' => 'est', 'label' => 'Est',  'contentOptions' => [ 'style' => 'width: 1px; text-align: center']],
						['attribute' => 'expe', 'label' => 'Expe', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center']],
						['attribute' => 'porc', 'label' => 'Porc'],
						['attribute' => 'modif', 'label' => 'Modificación', 'contentOptions' => [ 'style' => 'text-align: left']]
					]
				]);
			?>

		<?php Pjax::end(); ?>
	</div>
	<!-- FIN Div Rubros Actuales -->

	<!-- INICIO Div Rubros Nuevos -->
	<div id="comer_actionRubro_divRubroNuevos" class="form-panel">

		<?php Pjax::begin([ 'id' => 'comer_actionRubro_pjaxRubrosNuevos', 'enableReplaceState' => false, 'enablePushState' => false ]); ?>

			<div class="pull-left">
				<h3><label>Rubros Nuevos</label></h3>
			</div>

			<div class="pull-right">
				<?=
					Html::button('Nuevo', [
						'class' => 'btn btn-success',
						'onclick' => 'f_comerActionRubro_btNuevo();',
					]);
				?>
			</div>

			<div class="clearfix"></div>

			<?=
				GridView::widget([
					'dataProvider' => $dpRubros,
					'id' => 'comerActionRubroNuevosRubros',
					'rowOptions' => ['class' => 'grilla'],
					'headerRowOptions' => ['class' => 'grilla'],
					'summaryOptions'	=> [ 'class' => 'hidden' ],
					'columns' => [
						
						['attribute' => 'rubro_id', 'label' => 'Código', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center']],
						['attribute' => 'rubro_nom', 'label' => 'Nombre'],
						['attribute' => 'perdesde', 'label' => 'Desde'],
						['attribute' => 'perhasta', 'label' => 'Hasta'],
						['attribute' => 'tipo_nom', 'label' => 'Tipo'],
						['attribute' => 'cant', 'label' => 'Cant',  'contentOptions' => [ 'style' => 'width: 1px; text-align: center']],
						['attribute' => 'est', 'label' => 'Est',  'contentOptions' => [ 'style' => 'width: 1px; text-align: center']],
						['attribute' => 'expe', 'label' => 'Expe', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center']],
						['attribute' => 'porc', 'label' => 'Porc'],
						['attribute' => 'modif', 'label' => 'Modificación'],
						[
							'class' => 'yii\grid\ActionColumn',
							'template' => '{update}&nbsp;{delete}',
							'headerOptions' => ['style' => 'width:30px;'],
							'buttons' => [

								'update' => function($url, $model, $key) use ($dpRubrosActuales, $taccion) {

										$RubrosActuales = $dpRubrosActuales->getModels();
										$modf = true;
										
										foreach ( $RubrosActuales as $r){
											if ( $r['rubro_id'] == $model['rubro_id']) 
												$modf = false;
										}
										
										if ($modf or $taccion == 13)
											return Html::a( '<span class="glyphicon glyphicon-pencil"></span>', null, [
												'onclick' => 'f_comerActionRubro_ventanaModal( "' . $model['rubro_id'] . '",' . $model['nperdesde'] . ', 3 )',
												'class' => ( $model['est'] === 'B' ? 'hidden': '' )
											]);
									},

								'delete' => function( $url, $model, $key ) use ($dpRubrosActuales, $taccion){

										$RubrosActuales = $dpRubrosActuales->getModels();
										$elim = true;
										
										foreach ( $RubrosActuales as $r){
											if ( $r['rubro_id'] == $model['rubro_id']) 
												$elim = false;
										}
										
										if ($elim or $taccion == 13)
											return Html::a( '<span class="glyphicon glyphicon-trash"></span>', null, [
												'onclick' => 'f_comerActionRubro_ventanaModal( "' . $model['rubro_id'] . '",' . $model['nperdesde'] . ', 2 )',
												'class' => ( $model['est'] === 'B' ? 'hidden': '' )
											]);
									}
							]
						]
					]
				]);
			?>

			<?php

			/**
			 * Si $mostrarModalRubros, no se debe ocultar la ventana modal.
			 * Caso contario, se dbee ocultar la ventana modal.
			 */
			if( $mostrarModalRubros ){
			?>

				<script>

					var error = new Array();

					<?php foreach( $modelRubroTemporal->getErrors() as $error ){ ?>

						error.push( "<?= $error[0] ?>" );

					<?php } ?>

					mostrarErrores( error, "#<?= $idDivError ?>" );

				</script>

			<?php } else { ?>

				<script>
					$( document ).ready( function() {
						f_cerrarVentanaModal();
					});
				</script>

			<?php } ?>

		<?php Pjax::end(); ?>
	</div>
	<!-- FIN Div Rubros Nuevos -->

	<!-- INICIO Div Datos -->
	<div id="comer_actionRubro_divDatos" class="form-panel">

		<h3><label>Datos</label></h3>

		<table border="0">
			<tr>
				<td width="90px"><label>Expediente: </label></td>
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
				<td valign="top"><label>Observación: </label></td>
				<td colspan="5">
					<?=
						Html::activeTextarea( $model, 'obs', [
							'id'		=> 'comer_formDenominacion_txObs',
							'style'		=> 'width:400px; height: 60px; resize: none',
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
			Html::submitButton('Grabar', [
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
			'id'	=> 'comer_actionRubro_errorSummary',
			'style'	=> 'margin-top: 8px',
		]);
	?>
	<!-- FIN Div Errores -->

</div>
<!-- FIN Div Principal -->


<?php

	Pjax::begin(['id' => 'comer_actionRubro_pjaxModalRubro', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

		$id 			= Yii::$app->request->get( 'rubro_id', 0 );
		$perdesde		= str_replace( '-', '', Yii::$app->request->get( 'perdesde', 0 ) );
		$actionRubro	= Yii::$app->request->get( 'action_rubro', 1 );

		$titulo = "";

		switch( $actionRubro ){

			case 0 : $titulo = "Nuevo Rubro"; break;
			case 1 : $titulo = "Consultar Rubro"; break;
			case 2 : $titulo = "Eliminar Rubro"; break;
			case 3 : $titulo = "Modificar Rubro"; break;

		}

		Modal::begin([
			'id' => 'comer_actionRubro_modalRubro',
			'header'=>'<h2>' . $titulo . '</h2>',
			'toggleButton' => false,

			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			]
		]);

			echo RubroController::rubro( $id, $perdesde, $actionRubro, $arrayRubros, "comer_actionRubro_pjaxRubrosNuevos", "#comer_actionRubro_modalRubro", 2 );

		Modal::end();

	Pjax::end();
?>

<script>
function f_comerActionRubro_btNuevo(){

	f_comerActionRubro_ventanaModal( 0, 0, 0 );
}

function f_comerActionRubro_ventanaModal( rubro, perdesde, action ){

	$.pjax.reload({

		container	: "#comer_actionRubro_pjaxModalRubro",
		type		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 1000000,
		data:{
			"rubro_id"		: rubro,
			"perdesde"		: perdesde,
			"action_rubro"	: action,
		},
	});
}

$( "#comer_actionRubro_pjaxModalRubro" ).on( "pjax:end", function() {

	$( "#comer_actionRubro_modalRubro" ).modal( "show" );

});

function f_cerrarVentanaModal(){

	$( "#comer_actionRubro_modalRubro" ).modal( "hide" );

}

</script>
