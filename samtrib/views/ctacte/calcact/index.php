<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\bootstrap\Tabs;

$title = 'Actualización de Deuda';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = $title;

?>

<div class="container-fluid">
	
	<div class="row">
		<div class="pull-left">
			<h1>
    			<?php echo Html::encode($title); ?>
    		</h1>
		</div>
		
		<div class="pull-right">	
			<?= Html::a('Nuevo', '#', [
					'id' => 'btNuevo',
					'class' => 'btn btn-success',
					'data-toggle' => 'modal',
					'data-target' => '#modalDatos',
					'data-url' => Url::to(['datos', 'scenario' => 'nuevo']),
					'data-pjax' => '0'
				]); 
			?>
		</div>
		
		<div class="clearfix"> </div>
		
		<hr style='margin:0px;'>
		
	</div>
	
	<div class="row">
		<!-- INICIO DIV Mensaje -->
		<div id="index_form_mensajes" class="alert-success mensaje" style="display:none; margin: 10px 0px"></div>
		<!-- FIN DIV Mensaje -->
	</div>	
	
	<div class="row" style="margin-top:20px;">
		<div class="pull-left">
			<?php  
		
				echo GridView::widget([
					'id' => 'grillaLista',
					'dataProvider' => $dpLista,
					'options' => ['class' => 'grilla'],
					'columns' => [
			
						['attribute' => 'fchdesde', 'label' => 'Fecha desde'],
						['attribute' => 'fchhasta', 'label' => 'Fecha hasta'],
						['attribute' => 'indice', 'label' => 'Índice', 'contentOptions' => ['style' => 'text-align:right'] ],
						['attribute' => 'modificacion', 'label' => 'Modificación'],
						
						['class' => '\yii\grid\ActionColumn', 'template' => '{update}{delete}',
								'buttons' => [
									'update' => function($url, $model){

										return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
													'id' => 'btModificar',
													'data-toggle' => 'modal',
													'data-target' => '#modalDatos',
													'data-url' => Url::to(['datos', 'scenario' => 'modificar', "fchdesde" => $model->fchdesde, "fchhasta" => $model->fchhasta]),
													'data-pjax' => '0'
												]); 

									},

									'delete' => function($url, $model){

										return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
													'id' => 'btEliminar',
													'data-toggle' => 'modal',
													'data-target' => '#modalDatos',
													'data-url' => Url::to(['datos', 'scenario' => 'eliminar', "fchdesde" => $model->fchdesde, "fchhasta" => $model->fchhasta]),
													'data-pjax' => '0'
												]); 

									}
								]
						]
			
					]//fin columns
				]);
				 
			?>
		</div>
		<div class="pull-right" style='width:50%'>
			<?php
        
    			Tabs::begin([
	    
	    			'items' => [
	    				['label' => 'Calcular', 'active' => true, 'content' => $this->render('calcular', [ 'arrayTributos' => $arrayTributos ])]
	    			],//fin items
	    	
	    			'itemOptions' => ['style' => 'border : 1px solid #DDD; border-radius : 0px 0px 8px 8px; padding : 15px; border-top : none;']
	    		]);
    
    			Tabs::end();
    
    		?>
		</div>	
		
		<div class="clearfix"> </div>
		
	</div>
    
</div>

<!-- INICIO Modal  -->
<?php
Modal::begin([
		'id' => 'modalDatos',
		'class' => 'container',
		'size' => 'modal-sm',
		'header' => "<h2 id='modalTitulo'><b> Actualización </b></h2>",
		'closeButton' => [
			'label' => '<b>X</b>',
			'class' => 'btn btn-danger btn-sm pull-right'
			],
	]);
	
Modal::end();
?>
<!-- FIN Modal -->

<script>

$( document ).ready(function() {
	<?php if ( $mensaje != "" ){ ?>
		mostrarMensaje( "<?= $mensaje; ?>", "#index_form_mensajes" );
	<?php } ?>	
});

$(document).on('click', '#btNuevo', (function() {
	$.get(
		$(this).data('url'),
		function (data) {
			$('.modal-body').html(data);
			$("#modalTitulo").html( "Nueva Actualización" );
			$('#modalDatos').modal();
		}
	);
}));

$(document).on('click', '#btModificar', (function() {
	$.get(
		$(this).data('url'),
		function (data) {
			$('.modal-body').html(data);
			$("#modalTitulo").html( "Modificar Actualización" );
			$('#modalDatos').modal();
		}
	);
}));

$(document).on('click', '#btEliminar', (function() {
	$.get(
		$(this).data('url'),
		function (data) {
			$('.modal-body').html(data);
			$("#modalTitulo").html( "Eliminar Actualización" );
			$('#modalDatos').modal();
		}
	);
}));

</script>