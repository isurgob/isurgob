<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$title= 'Importar Retenciones del Financiero';

$this->params['breadcrumbs'][]= 'Act. Económica';
$this->params['breadcrumbs'][]= $title;

?>

<div>

	<div class="pull-left">
		<h1><?= $title; ?></h1>
	</div>

	<div class="pull-right" style="margin-right: 15px; margin-top: 5px">
		<?php $form = ActiveForm::begin(); ?>
			<?=
				Html::submitButton( 'Importar' , [
					'id'	=> 'btImportar()',
					'class'	=> 'btn btn-success'
				]);
			?>
		<?php ActiveForm::end(); ?>	
	</div>

	<div class="clearfix"></div>	
	
	<div class="separador-horizontal"></div>
	
	<?php
		echo $form->errorSummary( $model, [
			'style'	=> 'margin-top: 8px;margin-right: 15px',
		]);
	?>

	<!-- INICIO Div Mensajes -->
	<div id="divMensaje" class="mensaje alert-success" style="display:none"></div>
	<!-- FIN Div Mensajes -->

	<?php if ( count( $dataProvider->getModels() ) > 0 ) { ?>
		
		<label> <u> Retenciones Importadas </u> </label>
		<?=
			GridView::widget([
				'dataProvider' => $dataProvider,
				'id' => 'grillaDatos',
				'headerRowOptions' => ['class' => 'grillaGrande'],
				'columns' => [
					['label' => 'Nº', 'attribute' => 'ret_id', 'contentOptions' => ['style' => 'widh:1%; text-align:center']],
					['label' => 'Agente', 'attribute' => 'ag_rete', 'contentOptions' => ['style' => 'widh:1%; text-align:center']],
					['label' => 'Nombre Agente', 'attribute' => 'ag_nom_redu'],
					['label' => 'Año', 'attribute' => 'anio', 'contentOptions' => ['style' => 'widh:1%; text-align:center']],
					['label' => 'Mes', 'attribute' => 'mes', 'contentOptions' => ['style' => 'widh:1%; text-align:center']],
					['label' => 'Periodo', 'attribute' => 'periodo', 'contentOptions' => ['style' => 'widh:1%; text-align:center']],
					['label' => 'Contribuyente', 'attribute' => 'obj_id', 'contentOptions' => ['style' => 'widh:1%; text-align:center']],
					['label' => 'Contribuyente Nombre', 'attribute' => 'nombre'],
					['label' => 'Fecha', 'attribute' => 'fecha', 'contentOptions' => ['style' => 'widh:1%; text-align:center']],
					['label' => 'Base', 'attribute' => 'base', 'contentOptions' => ['style' => 'widh:1%; text-align:right']],
					['label' => 'Alic.', 'attribute' => 'ali', 'contentOptions' => ['style' => 'widh:1%; text-align:right']],
					['label' => 'Monto', 'attribute' => 'monto', 'contentOptions' => ['style' => 'widh:1%; text-align:right']],
				]
			]);

		?>
	<?php } ?>	
	
	<?php if( $mensaje != '' ){ //Mensajes ?>
	<script>
		mostrarMensaje( "<?= $mensaje ?>", "#divMensaje" );
	</script>
<?php }?>

</div>	