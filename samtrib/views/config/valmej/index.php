<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\controllers\config\ValmejController;

$title = 'Valores de Mejora';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

?>
<div class="val-mej-index">

    <div id="val_mej_divMensajes" class="alert-success mensaje" style="display:none"></div>
	
	<div class="pull-left">
		<h1><?= Html::encode($title) ?></h1>
	</div>	

    <div class="pull-right">
        <?= Html::Button('Nuevo', ['id'=>'btnNuevo','class' => 'btn btn-success','onclick'=> "f_abmValMej('insert', '', '', 0)"]) ?>
    </div>
	
	<div class="clearfix"></div>
	
	<hr style='margin:5px 0px'>
	
	<label> Año: </label>
	<?= Html::input('text', 'filtroAnio', $model->aniodesde, [
			'class' => 'form-control text-center', 
			'id' => 'filtroAnio', 
			'style' => 'width:10%', 
			'maxlength' => '4'
		]); 
	?>
	
	<?= Html::button('Buscar', [
			'class' => 'btn btn-primary', 
			'id' => 'btBuscar',
			'onclick' => 'f_Buscar();'
		]);
	?>
	
	<hr style='margin:5px 0px'>

    <?php 
	
	Pjax::begin(['id' => 'PjaxGrillaValMej']);
	
		echo GridView::widget([
			'id'=>'GrillaValMej',
			'dataProvider' => $dataProvider,
			'summary' => false,
			'headerRowOptions' => ['class' => 'grilla'],	
			'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
			'columns' => [

				['attribute' => 'cat', 'header' => 'Categoria', 'contentOptions' => ['style' => 'width:60px','class'=>'grilla']],
				['attribute' => 'cat_nom', 'header' => 'Descripción', 'contentOptions' => ['style' => 'width:200px','class'=>'grilla']],
				['attribute' => 'form_nom', 'header' => 'Tipo Formularo', 'contentOptions' => ['style' => 'width:250px','class'=>'grilla']],
				['attribute' => 'perdesde_str', 'header' => 'Desde', 'contentOptions' => ['style' => 'width:80px','class'=>'grilla']],
				['attribute' => 'perhasta_str', 'header' => 'Hasta', 'contentOptions' => ['style' => 'width:80px','class'=>'grilla']],
				['attribute' => 'valor', 'header' => 'Valor', 'contentOptions' => ['style' => 'width:70px;text-align:right','class'=>'grilla']],

				['class' => 'yii\grid\ActionColumn', 'contentOptions'=>['style'=>'width:50px;','align'=>'center','class'=>'grilla'], 'template' => '{view}{update}{delete}',
				
				
					'buttons' => [   
					
						'view' => function($url,$model,$key)
								{
									return Html::Button('<span class="glyphicon glyphicon-eye-open"></span>', [
										'class' => 'bt-buscar-label',
										'style' => 'color:#337ab7;',
										'onclick' => 'f_abmValMej("consulta","'.
																		$model['cat'].'","'.
																		$model['form'].'",'.
																		$model['perdesde'].');'  
									]);									
								},
								
						'update' => function($url,$model,$key)
								{
									return Html::Button('<span class="glyphicon glyphicon-pencil"></span>', [
										'class' => 'bt-buscar-label',
										'style' => 'color:#337ab7;',
										'onclick' => 'f_abmValMej("update","'.
																		$model['cat'].'","'.
																		$model['form'].'",'.
																		$model['perdesde'].');'  
									]);									
								},
										
						'delete' => function($url,$model,$key)
								{   

									return Html::Button('<span class="glyphicon glyphicon-trash"></span>',[
											'class' => 'bt-buscar-label',
											'style' => 'color:#337ab7;',
											'onclick' => 'f_abmValMej("delete","'.
															$model['cat'].'","'.
															$model['form'].'",'.
															$model['perdesde'].');'  
				
									]);	            														
								}	
					],
							
				],
			],
		]); 
		
		if ( $mensaje != '' ) { //Si existen mensajes 
		?>
			<script>

			$( document ).ready(function() {

				mostrarMensaje( "<?= $mensaje; ?>", "#val_mej_divMensajes" );

			});

			</script>

		<?php 
		} 	
		
	Pjax::end();
	?>	

</div>

<?php
	pjax::begin([ 'id' => 'PjaxModal' ]);
		
		$scenario = Yii::$app->request->get( 'scenario', '' );
		$cat = Yii::$app->request->get( 'cat', '' );
		$form = Yii::$app->request->get( 'form', '' );
		$perdesde = Yii::$app->request->get( 'perdesde', 0 );
		$titulo = "Consulta de Valores de Mejora";
		
		if ($scenario == 'insert')
			$titulo = "Nuevo Valor de Mejora";
			
		if ($scenario == 'update')
			$titulo = "Modificar Valor de Mejora";	
			
		if ($scenario == 'delete')
			$titulo = "Eliminar Valor de Mejora";	
		
		Modal::begin([
			'id' => 'ModalValMej', 
			'header' => '<h2>' . $titulo . '</h2>',
			//'size' => 'modal-sm',
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			]
		]);
				
			echo ValmejController::FormularioValMej( $scenario, $cat, $form, $perdesde );
		
		Modal::end();
		
	pjax::end();		
?>

<script>

function f_Buscar(){

	$.pjax.reload({
		container: "#PjaxGrillaValMej",
		method: "POST",
		replace: false,
		push: false,
		data: {
			"filtroAnio": $("#filtroAnio").val()
		}
	});
}

function f_abmValMej( scenario, cat, form, perdesde ) {
	
	$.pjax.reload({
		container   : "#PjaxModal",
		type        : "GET",
		replace     : false,
		push        : false,
		data:{
			"scenario"	: scenario,
			"cat"		: cat,
			"form"		: form,
			"perdesde"	: perdesde
		},
	});
	
	$( "#PjaxModal" ).on( "pjax:end", function() {

		$("#ModalValMej").modal();

	});

}

</script>