<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\Pjax;

use yii\bootstrap\Alert;

use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Configuración de convenios';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;
?>

<style type="text/css" rel="stylesheet">
.planConfigIndex .row{
	height : auto;
	min-height : 17px;
}

.planConfigIndex .row{
	margin-top : 10px;
}

.planConfigIndex .row:first-of-type{
	margin-top : 0px;
}

.planConfigIndex .form-control{
	width : 100%;
}

#planConfigIndexListaPlanes tr > td:first-of-type{
	text-align : center;
}
</style>


<div class="planConfigIndex">

	<?php

	?>
	<!-- titulo y botones -->
	
	<script type="text/javascript">
	function aplicarFiltros()
	{
		var trib = $("#filtroTributo").val();
		var nombre = $("#filtroNombre").val();
		var vigentes = $("#filtroSoloVigentes").is(":checked");
		
		$.pjax.reload({container : "#pjaxGrilla", method : "POST", data : {"trib_id" : trib, "nombre" : nombre, "vigentes" : vigentes} } );
	}
	
	function borrarFiltros()
	{
		$("#filtroTributo").val('');
		$("#filtroNombre").val('');
		$("#filtroSoloVigentes").attr("checked", "checked");
		
		$.pjax.reload({container : "#pjaxGrilla", method : "POST", data : {"trib_id" : "", "nombre" : "", "vigentes" : true}});
	}
	</script>
	
	
	<div class="row">
	
		<div class="col-xs-6">
			<h1><?php echo 'Configuración de convenios' ?></h1>
		</div>
		
		<div class="col-xs-6">
			<?php 
			if (utb::getExisteProceso(3351))
				echo Html::a('Nuevo', ['create'], ['class' => 'btn btn-success pull-right']) 
			?>
		</div>
		
		<div class="col-xs-12">
			<div style="border-bottom: 1px solid #DDD;">
			<!-- separador -->
			</div>
		</div>
	</div>
	<!-- fin titulo y botones -->

	<!-- filtros -->
	<div class="row">
		<div class="col-xs-4">
			<label for="filtroTributo">Tributo</label>
			<?php
			//lista todos los tributos					
			echo Html::dropDownList(
									'Tributo',
									null,
									utb::getAux('trib', 'trib_id', 'nombre', 0, 'trib_id Not In ' . utb::getTribEsp()),
									[
										'prompt' => '<todos>',
										'id' => 'filtroTributo',
										'class' => 'form-control',
										'encode' => 'false',
										'onchange' => 'aplicarFiltros();',
									]
			);
			?>
		</div>
		
		<div class="col-xs-4">
			<label for="filtroNombre">Nombre</label>
			<?php
				//filtra por nombre				
				echo Html::textInput(
								null,
								null, 
								[
								'class' => 'form-control',
								'id' => 'filtroNombre',
								'onchange' => 'aplicarFiltros();',
								]);
			?>
		</div>
		
		<div class="col-xs-2">
			<div class="checkbox">
    			<br>
    			
    			<?php
    				echo Html::checkbox(null, true, ['value' => 'true', 'id' => 'filtroSoloVigentes', 'checked' => 'checked', 'onchange' => 'aplicarFiltros();', 'label' => 'Sólo vigentes']);
    			?>
    			
  			</div>
		</div>
		
		<div class="col-xs-1">
			<br>
			<?= Html::button('Buscar', ['class' => 'btn btn-primary', 'onclick' => 'aplicarFiltros();']); ?>
		</div>
		
		<div class="col-xs-1">
			<br>
			<?php
			//boton para borrar los filtros
			echo Html::button(
								'<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', 
								[
									'class' => 'btn btn-default btn-sm',
									'title' => 'Borrar filtros',
									'onclick' => 'borrarFiltros();',	
								]);
			?>
		</div>
	</div>
	
	<?php
	
	

	?>
	<!-- fin filtros -->

	<div class="row">
		<div class="col-xs-12">
	
    		<?php
    		Pjax::begin(['id' => 'pjaxGrilla']);
    		
    		echo GridView::widget([
        		'dataProvider' => $dataProvider,
        		'id' => 'planConfigIndexListaPlanes',
        		'columns' => [

					['attribute' => 'cod', 'label' => 'Código'],
					['attribute' => 'nombre', 'label' => 'Nombre'],
					['attribute' => 'sistema', 'label' => 'Sistema'],
					['attribute' => 'vigenciadesde', 'label' => 'Vigencia desde'],
					['attribute' => 'vigenciahasta', 'label' => 'Vigencia hasta', 'content' => function($model){
						
						if($model['vigenciahasta'] == null) return '<span class="not-set">Sin límite</span>';
						
						return $model['vigenciahasta'];
					}],

        		    ['class' => 'yii\grid\ActionColumn','template'=> '{view}'.(utb::getExisteProceso(3351) ? '{update}{delete}' : ''),
		            'buttons' => [		           			
				           			'delete' => function($url, $model, $key)
		        		   						{
		           									return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
		           								}
				           		],//fin buttons
				     ],//fin de ActionColumn
		        ]//fin de columns,
		    ]);//fin del GridView
		    
		    Pjax::end();
		     ?>
		</div>
	</div>
	<!-- fin de la lista -->
	
	
	<?php
	$resultado = isset($_GET['a']) ? $_GET['a'] : '';
	$mensaje = null;
	  
	switch($resultado)
	{
		case 'create' :
		case 'update' : 
		case 'delete' : $mensaje = "Datos grabados."; break;
	}
  
	if($mensaje != null)
	{  	
		echo Alert::widget([
 			'options' => ['class' => 'alert-success alert-dissmisible', 'id' => 'alertPlanConfigForm'],
			'body' => $mensaje
		]);
	
		echo "<script>window.setTimeout(function() { $('#alertPlanConfigForm').alert('close'); }, 5000)</script>";
	}
?> 	
	
</div> <!-- fin de .planConfigIndex -->


<script type="text/javascript">
//aplica la funcion despues de 100 milisegundo.... si se saca esto, $.pjax.reload() da un error de que no encuentra el elemento $("#pjaxGrilla") y vuelve a recargar la pagina
setTimeout(aplicarFiltros, 100);
</script>
