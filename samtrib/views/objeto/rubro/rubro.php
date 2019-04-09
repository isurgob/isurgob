<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use app\utils\db\utb;
use app\controllers\objeto\RubroController;

/**
 * Forma que se dibuja para mostrar el listado de rubros y mostrar la ventana modal para agregar nuevos rubros.
 * Recibe:
 *
 *	+ $pjaxAActualizar
 *	+ $idDivError
 *	+ $tipoObjeto			=> Tipo de Objeto.
 *	+ $modelObjeto			=> Modelo de Objeto
 *	+ $mostrarModalRubros	=> Variable que indica si se debe mostrar la ventana modal de "Rubro".
 *	+ $modelRubroTemporal	=> Modelo de Rubro con el cual se debe cargar la ventana modal.
 *	+ $action 				=> Tip ode acción.,
 *	+ $dataProvider			=>	DataProvider con los rubros.
 *	+ $esModal 				=> Indica si la forma se dibuja como ventana modal.
 *
 *	+ $dadosDeBaja			=> Filtro "Dado de Baja".
 *	+ $soloVigentes			=> Filtro "Sólo Vigentes".
 */

$pjaxAActualizar	= isset( $pjaxAActualizar ) ? $pjaxAActualizar : 'rubro_pjaxGrilla';

$idDivError 	= isset( $idDivError ) ? $idDivError : 'rubro_form_errorSummary';
$esModal 		= isset( $esModal ) ? $esModal : false;
$tipoObjeto		= isset( $tipoObjeto ) ? $tipoObjeto : 3;	//Por defecto el tipo de objeto es "Persona"
$arrayRubros	= isset( $arrayRubros ) && count( $arrayRubros ) > 0 ? $arrayRubros : $dataProvider->getModels();

$mostrarModalRubros = isset( $mostrarModalRubros ) ? $mostrarModalRubros : 0;
$modelRubroTemporal	= isset( $modelRubroTemporal ) && count( $modelRubroTemporal ) > 0 ? $modelRubroTemporal : [];

$dadosDeBaja	= isset( $dadosDeBaja ) ? $dadosDeBaja : 0;
$soloVigentes	= isset( $soloVigentes ) ? $soloVigentes : 0;

$idModalRubro	= 'rubro_modalFormRubro';

?>

<style>

#<?= $idModalRubro ?> .modal-content {

	width		: 84% !important;
	margin-left	: 8%;
}

</style>

<!-- INICIO Div Principal -->
<div id="rubro_divPrincipal">

	<?php Pjax::begin([ 'id' => "$pjaxAActualizar", 'enablePushState' => false, 'enableReplaceState' => false ]); ?>

	<div class="pull-left">

		<?=
			Html::checkbox( 'ckBaja', $dadosDeBaja, [
				'id'		=> 'rubro_filtroBaja',
				'label'		=> 'Dados de baja',
				'onchange'	=> 'f_rubro_actualizaFiltro()',
			]);
		?>

		&nbsp;&nbsp;

		<?=
			Html::checkbox( 'ckVigente', $soloVigentes, [
				'id'		=> 'rubro_filtroVigente',
				'class'		=> 'hidden',
				//'label'		=> 'Solo vigentes',
				'onchange'	=> 'f_rubro_actualizaFiltro()',
			]);
		?>

	</div>

	<div class="pull-right">
		<?=
			Html::button( 'Nuevo', [
				'id'	=> 'rubro_btNuevo',
				'class'	=> 'btn btn-success' . ( in_array( $action, [ 1, 2 ] ) ? ' hidden' : '' ),
				'onclick'	=> 'f_rubro_btNuevo()',
			]);
		?>
	</div>

	<div class="clearfix"></div>

	<!-- INICIO Div Grilla Rubros -->
	<div id="rubro_divGrillaRubros">

		<?=
			GridView::widget([
				'dataProvider' => $dataProvider,
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'columns' => [

					['attribute' => 'nomen_nom', 'label' => 'Nomen', 'headerOptions' => ['style' => 'width:40px;']],
					['attribute' => 'rubro_id', 'label' => 'Rubro'],
					['attribute' => 'rubro_nom', 'label' => 'Nombre'],
					['attribute' => 'perdesde', 'label' => 'Desde', 'headerOptions' => ['style' => 'width:55px;']],
					['attribute' => 'perhasta', 'label' => 'Hasta', 'headerOptions' => ['style' => 'width:55px;']],
					['attribute' => 'cant', 'label' => 'Cant', 'options' => [ 'style' => 'text-align: center']],
					['attribute' => 'est', 'label' => 'Est'],
					['attribute' => 'tipo_nom', 'label' => ''],
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => ( ( in_array( $action, [0, 3] ) && utb::getExisteProceso( 3219 ) ) ? '{update}&nbsp;{delete}' : '' ),
						'headerOptions' => ['style' => 'width:30px;'],
						'buttons' => [

							'update' => function($url, $model, $key){

									return Html::a( '<span class="glyphicon glyphicon-pencil"></span>', null, [
										'onclick' => 'f_rubro_ventanaModal( "' . $model['rubro_id'] . '",' . $model['nperdesde'] . ', 3 )',
										'class' => ( $model['est'] === 'B' ? 'hidden': '' )
									]);
								},

							'delete' => function( $url, $model, $key ){

									return Html::a( '<span class="glyphicon glyphicon-trash"></span>', null, [
										'onclick' => 'f_rubro_ventanaModal( "' . $model['rubro_id'] . '",' . $model['nperdesde'] . ', 2 )',
										'class' => ( $model['est'] === 'B' ? 'hidden': '' )
									]);
								}
						]
					]
				]
			]);
		?>

	</div>
	<!-- INICIO Div Grilla Rubros -->

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
<!-- FIN Div Principal -->

<?php

Pjax::begin([ 'id' => 'rubro_pjaxModalRubro', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

	$id 			= Yii::$app->request->get( 'rubro_id', '' );
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
		'id' => $idModalRubro,
		'header'=>'<h2>' . $titulo . '</h2>',
		'toggleButton' => false,

		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		]
	]);

		echo RubroController::rubro( $id, $perdesde, $actionRubro, $arrayRubros, $pjaxAActualizar, "#" . $idModalRubro, $tipoObjeto );

	Modal::end();

Pjax::end();
?>

<script>

function f_rubro_actualizaFiltro(){

	var baja	= $( "#rubro_filtroBaja" ).is( ":checked" ) ? 1 : 0,
		vigente	= $( "#rubro_filtroVigente" ).is( ":checked" ) ? 1 : 0;

	$.pjax.reload({

		container	: "#<?= $pjaxAActualizar ?>",
		type		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data:{
			"filtroBaja"	: baja,
			"filtroVigente"	: vigente,
		},
	});
}
function f_rubro_btNuevo(){

	f_rubro_ventanaModal( '', 0, 0 );
}

function f_rubro_ventanaModal( rubro, perdesde, action ){

	$.pjax.reload({

		container	: "#rubro_pjaxModalRubro",
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

$( "#rubro_pjaxModalRubro" ).on( "pjax:end", function() {

	f_mostrarVentanaModal();
});

function f_mostrarVentanaModal(){

	$( "#<?= $idModalRubro ?>" ).modal( "show" );

}

function f_cerrarVentanaModal(){

	$( "#<?= $idModalRubro ?>" ).modal( "hide" );

}

</script>
