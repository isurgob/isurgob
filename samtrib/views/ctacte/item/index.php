<?php

use yii\helpers\Html;
use yii\grid\GridView;


use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\BaseUrl;

use app\models\ctacte\Item;
use app\utils\db\utb;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Items';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;
?>

<style type="text/css">

.item-index table,
.item-index table td{
	width : auto;
}

.item-index div.row{
	height : auto;
	min-height : 17px;
}

.form-control{
	width : auto;
	display : inline-block;
}

#grilla table tbody tr:hover{
	cursor : pointer;
	background-color : #CCCCCC;
}

</style>


<div class="item-index">

	<div class="row" >
		<div class="col-xs-12">
			<div style="border-bottom:1px solid #DDD; padding-bottom:15px;">
    			<h1 style="display:inline;margin-right:5px;"><?php echo 'Items' ?></h1>

    			<?php 
				if (utb::getExisteProceso(3011))
    				echo Html::a('Nuevo item', ['create'], ['class' => 'btn btn-success pull-right', 'id' => 'botonNuevoItem']) 
				?>
    		</div>
		</div>
	</div>
	
	<div class="row" style="margin-top:5px;"><!-- filtros -->		
	
		<div class="col-xs-6">
			<div class="row">
				
				<?php
				
				Pjax::begin();
				
				$form = ActiveForm::begin( ['options' => ['data-pjax' => true] ] );
				?>
						
				<input type="text" class="hidden" id="tribIdActual" value="0" >
				<input type="text" class="hidden" id="itemNombreActual" value="" >
				
				<script type="text/javascript">
				function borrarFiltros()
				{
					$("#tribIdActual").val("");
					$("#itemNombreActual").val("");
					$("#selectTributoFiltro").val("");
					$("#inputNombreFiltro").val("");
				}
				</script>
				
				<div class="col-xs-8" style="margin-top:5px;">
					<label style='width:50px'>Tributo</label>
					<?php					
					echo Html::dropDownList(
											'Tributo',
											Yii::$app->request->get('trib_id', null), 
											utb::getAux('trib', 'trib_id', 'nombre', 2, "est = 'A'"), 
											[
											'class' => 'form-control',
											'encode' => false,
											'style' => 'width:75%;',
											'id' => 'selectTributoFiltro',
											'onchange' => ' $.pjax.reload({container: "#grilla", data : {"trib_id" : $(this).val(), "nombre" : $("#txFiltroNombre").val(), "cuenta" : $("#txFiltroCuenta").val()}, method : "POST"}); ' .
															'$("#tribIdActual").val($(this).val());' .
															'$("#itemNombreActual").val("");' .
															'$("#inputNombreFiltro").val("");'
											]);
					?>
                    <label style='width:50px'>Nombre</label>
					<?php					
					echo Html::input('text','txFiltroNombre', Yii::$app->request->get('item_nom', ''), [
											'class' => 'form-control',
											'encode' => false,
											'style' => 'width:75%;',
											'id' => 'txFiltroNombre',
											'onkeyup' => ' $.pjax.reload({container: "#grilla", data : {"trib_id" : $("#selectTributoFiltro").val(), "nombre" : $(this).val(), "cuenta" : $("#txFiltroCuenta").val(),"focus":"t"}, method : "POST"}); ' .
															'$("#tribIdActual").val($(this).val());' .
															'$("#itemNombreActual").val("");' .
															'$("#inputNombreFiltro").val("");'
										]);
					?>
					<label style='width:50px'>Cuenta</label>
					<?php					
					echo Html::input('text','txFiltroCuenta', Yii::$app->request->get('cuenta_nom', ''), [
											'class' => 'form-control',
											'encode' => false,
											'style' => 'width:75%;',
											'id' => 'txFiltroCuenta',
											'onkeyup' => ' $.pjax.reload({container: "#grilla", data : {"trib_id" : $("#selectTributoFiltro").val(), "nombre" : $(this).val(), "cuenta" : $("#txFiltroCuenta").val(),"focus":"c"}, method : "POST"}); ' .
															'$("#tribIdActual").val($(this).val());' .
															'$("#itemNombreActual").val("");' .
															'$("#inputNombreFiltro").val("");'
										]);
					?>
				</div>				
				
				<?php
					ActiveForm::end();
					
					Pjax::end();
					?>
						
				</div>
			<!-- fin filtros -->
		

			<div class="row">
		    	<div class="col-xs-12">
		    		
		    	<?php 
		    		
		    	Pjax::begin(['id' => 'grilla']);
		    		
		
		    		
		    			echo GridView::widget([
		    				'tableOptions' => ['style' => 'width:100%;', 'class' => 'table table-striped table-bordered'],
				       		'dataProvider' => $dataProvider,
				       		'headerRowOptions' => ['class' => 'grillaGrande'],
				       		'rowOptions' => function($model, $key, $index, $grid){
				       											return ['onclick' => '$.pjax.reload( {container : "#tabs", data : {"item_id" : ' . $key . '} , method : "POST" })', 'class' => 'grillaGrande' ]; 
				       											},
		        			'columns' => [
		
								['attribute' => 'item_id', 'label' => 'Cód.', 'options' => ['style' => 'width:10%', 'class' => 'grillaGrande']],
								['attribute' => 'nombre', 'label' => 'Nombre', 'options' => ['style' => 'width:55%', 'class' => 'grillaGrande']],
		
				           		['class' => 'yii\grid\ActionColumn', 'options' => ['style' => 'width:10%', 'class' => 'grillaGrande'],'template' => (utb::getExisteProceso(3011) ? '{update}&nbsp;{delete}' : ''),
				           		'buttons' => [

				           			'update' => function($url, $model, $key)
				           						{
				           							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
				           						},
				           						
				           			'delete' => function($url, $model, $key)
				           						{
				           							return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
				           						}
				           		],
				           		],		           		
		        			],
		        			
		        			'options' => ['data-pjax' => true],
		    			]); 
		    	
		    			?>
		    			<script type="text/javascript">
		    			$(document).ready(function(){
		    				$("#botonNuevoItem").attr("href", "<?= BaseUrl::toRoute(['create', 'trib' => Yii::$app->request->post('trib_id', -1)]); ?>");
							<?php if(isset($_POST['focus']) and $_POST['focus']=='t'){ ?> 
								$("#txFiltroNombre").focus();
							<?php } ?>	
							<?php if(isset($_POST['focus']) and $_POST['focus']=='c'){ ?> 
								$("#txFiltroCuenta").focus();
							<?php } ?>
		    			});
		    			</script>
		    			<?php
		    	
		    	Pjax::end();
		    	?>
		    	
				</div>
		
			</div>
		</div>		
	
		<div class="col-xs-6">
		
	    		<?php
	    		
	    		Pjax::begin(['id' => 'tabs']);    		
	    		
	    		$model = new Item();
	    		
	    		$item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
	    		$model->item_id = $item_id;
	    		
	    		$model = $model->buscarUno();
	    			
	    		
	    		echo Tabs::widget([
	    			'items' =>  [
	    						
	    						['label' => 'Datos', 'active' => true, 'content' => $this->render('_formindex', 
	    																							['model' => $model,
	    																							'consulta' => 1,
	    																							'item_id' => $item_id
																									])]
	    						],
	    						
	    			'itemOptions' => ['style' => 'border : 1px solid #DDD; border-radius : 0px 0px 8px 8px; padding : 15px; border-top : none;']
	    						
	    						
				]);
	    		    		
	    		Pjax::end();    		
	    		?>
	    
		</div>
	</div>
	
	<?php
	if(isset($_GET['a']))
	{
	
		$mensaje = "";
		
			switch($_GET['a'])
			{
			case 'create' : 
			case 'update' : 
			case 'delete' : $mensaje = "Datos grabados."; break;
		 
			default : $mensaje = "La acción se ha completado con exito."; break;
			}
	
		
	?>
	
	<div class="row">
		<div class="col-xs-12">
		<?php
		echo Alert::widget([
			'options' => ['class' => 'alert-success alert-dissmisible', 'id' => 'alertItemIndex'],
			'body' => $mensaje
		]);
		?>
		</div>
	</div>
	
	<?php
	
	echo "<script>window.setTimeout(function() { $('#alertItemIndex').alert('close'); }, 5000)</script>";
	}
	?>
</div>

<script>
$(document).ready(function(){
	
	$.pjax.reload({
		container: "#grilla", 
		data : {
			"trib_id" : $("#selectTributoFiltro").val(), 
			"nombre" : ""}, 
		method : "POST"
	});
	
	$("#tribIdActual").val($(this).val());	
	$("#itemNombreActual").val("");
	$("#inputNombreFiltro").val("");
});
</script>
