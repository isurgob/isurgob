<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\jui\DatePicker;


use app\utils\db\Fecha;
use app\utils\db\utb;


$title = 'Ver Resolución';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Resolución', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

echo Html::hiddenInput(null, null, ['id' => 'tablaActual']);
?>
<style type="text/css">
	.asterisco{
		color:red;
		float:left;
		margin-left:100px;
	}
</style>

<div class="site-auxedit">
	
	<h2 style="display:inline-block;"><label><?php echo "Resolución: ".Html::encode($model->nombre); ?></label></h2>
	<?= Html::a('Modificar', BaseUrl::toRoute(['//ctacte/resol/update', 'id' => $model->resol_id]), ['class' => 'btn btn-primary pull-right']); ?>
    <div class="separador-horizontal"></div>

	<div class="site-auxedit">

    <?php  $form = ActiveForm::begin(['id'=>'formResolucion', 'validateOnSubmit' => false, 'fieldConfig' => ['template' => '{input}']]); ?>

	<div class="form" style='padding:5px;'>
	
		<table width='100%' border='0'>
			<tr>
				<td width='50px'><label>Código:</label></td>
				<td width="5px"></td>
				<td width='55px' align='left'><?= $form->field($model, 'resol_id')->textInput(['id'=>'resol_id','style' => 'width:50px;','readonly'=>true])->label(false); ?></td>
				<td align='right' width='60px'><label>Nombre:</label></td>
				<td width='250px'><?= $form->field($model, 'nombre')->textInput(['id'=>'nombre','style' => 'width:245px;', 'disabled' => true])->label(false); ?></td>
				<td width="10px"></td>
				<td width='60px'><label>Tributo:</label></td>
				<td><?= $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib','trib_id','nombre'), ['id'=>'trib_id','style' => 'width:260px;', 'prompt' => '', 'disabled' => true])->label(false); ?></td>
			</tr>
		
		
			<tr>
				<td><label>Vigencia:</label></td>
				<td></td>
				<td colspan="3">
				<div style='padding:5px;' class="form">
					<table border='0' width="100%">
						<tr>
							<td><label>Desde:</label></td>
							<td><?= $form->field($model, 'adesde')->textInput(['id' => 'anio_desde', 'style' => 'width:50px;', 'maxlength' => 4, 'disabled' => true])->label(false); ?></td>
							<td><?= $form->field($model, 'cdesde')->textInput(['id' => 'cuota_desde', 'style' => 'width:40px;', 'maxlength' => 3, 'disabled' => true])->label(false); ?></td>
							<td align='right' width='45px'><label>Hasta:</label></td>
							<td><?= $form->field($model, 'ahasta')->textInput(['id' => 'anio_desde', 'style' => 'width:50px;', 'maxlength' => 4, 'disabled' => true])->label(false); ?></td>
							<td><?= $form->field($model, 'chasta')->textInput(['id' => 'cuota_desde', 'style' => 'width:40px;', 'maxlength' => 3, 'disabled' => true])->label(false); ?></td>
							<td colspan="2" align='right'><?= $form->field($model, 'anual')->checkbox(['id'=>'anual','label' => 'Anual', 'uncheck' => 0, 'value' => 1, 'disabled' => true])->label(false); ?></td>
						</tr>
					</table>
				</div>
				</td>
				<td></td>
				<td><label>Funci&oacute;n:</label></td>
				<td><?= $form->field($model, 'funcion')->textInput(['id'=>'funcion','style' => 'width:260px;', 'disabled' => true])->label(false); ?></td>
			</tr>
		
			<tr>
				<td><label>Filtro:</label></td>
				<td></td>
				<td colspan="7"><?= $form->field($model, 'filtro')->textInput(['id'=>'filtro','style' => 'width:695px;', 'disabled' => true])->label(false); ?></td>
			</tr>
			
			<tr>
				<td><label>Cant.Años:</label></td>
				<td></td>
				<td colspan="7">
					<?= $form->field($model, 'cant_anio')->textInput(['id'=>'cant_anio','style' => 'width:50px;', 'disabled' => true])->label(false); ?>
				</td>
			</tr>
			<tr>
				<td><label>Detalle:</label></td>
				<td></td>
				<td colspan="7">
					<?= $form->field($model, 'detalle')->textarea(['id'=>'detalle','style' => 'width:695px;resize:none', 'disabled' => true])->label(false); ?>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="form-group" id='form_botones' style='display:none; margin-top:5px;'>
	<?php

		echo Html::submitButton('Grabar', ['class' => 'btn btn-success']); 
		echo "&nbsp;&nbsp;";
		echo Html::a('Cancelar', ['index'], ['class' => 'btn btn-primary']);
	?>
    </div>

	<?php
	ActiveForm::end(); 
	
	echo $form->errorSummary($model);
	?>
	</div>
</div>

<div style="margin-top:20px;">
	<h3 style="display:inline-block"><label>Variables</label></h3>
	<?php echo Html::button('Nueva', ['class' => 'btn btn-success pull-right', 'id' => 'btnNuevo',
			'onclick' => 'mostrarModalVariables(0);']) 
	?>
</div>

<div class="separador-horizontal"></div>
			
		
		<?php
		echo GridView::widget([
			'id' => 'GrillaVariables',
     	    'dataProvider' => $dpVariables,
			'headerRowOptions' => ['class' => 'grilla'],
			'summary' => false,
			'columns' => [
            		['attribute'=>'varlocal','label' => 'Variable','contentOptions'=>['style'=>'width:30%;text-align:left;','class' => 'grilla']],
					['attribute'=>'tipo','label' => 'Tipo' , 'value'=>function($data){ return utb::getCampo('var_tipo','cod='.$data['tipo']);},
					'contentOptions'=>['style'=>'width:30%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'valor','label' => 'Valor' ,'contentOptions'=>['style'=>'width:30%;text-align:left;', 'class' => 'grilla']],           		  
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:10%;text-align:center;','class'=>'grilla'],'template' => '{update} {delete}',
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
			        					return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
						  	 			[
					  	 					'class' => 'bt-buscar-label',
											'style' => 'color:#337ab7;',
											'onclick' => 'mostrarModalVariables(3, "' . $model['varlocal'] . '");'						
										]);	
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
						  	 			[
					  	 					'class' => 'bt-buscar-label',
											'style' => 'color:#337ab7;',
											'onclick' => 'mostrarModalVariables(2, "' . $model['varlocal'] . '");'						
										]);	
									}
						]
					],
			],
		]); 																			    
	?>

<div style="margin-top:10px;">		
	<table width='100%'>
		<tr>
			<td width='30%' valign='top'>
			
			
				<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
					<tr>
						<td><h3><label>Tablas</label></h3></td>
						<td align='right'>
							<?php echo Html::button('Nueva', ['class' => 'btn btn-success', 'id' => 'btnNuevo',
								'onclick' => 'mostrarModalTabla(0);']) 
							?> 	
						</td>
					</tr>
					<tr>
						<td colspan='2'></td>
					</tr>
				</table>
			
			
				<?php				
				echo GridView::widget([
					'id' => 'GrillaTablas',
			 	    'dataProvider' => $dpTablas,
					'headerRowOptions' => ['class' => 'grillaGrande'],
					'summary' => false,
					'rowOptions' => function ($model,$key,$index,$grid) 
									{
										return [
											'onclick' => 'cargarTabla(1, ' . $model['tabla_id'] . ');'
										];
									},
					'columns' => [
			        		['attribute'=>'tabla_id','label' => 'Cód','contentOptions'=>['style'=>'width:15%;text-align:left;','class' => 'grilla']],
							['attribute'=>'nombre','label' => 'Nombre' ,'contentOptions'=>['style'=>'width:62%;text-align:left;', 'class' => 'grilla']],          		  
			        		 
			        		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:20%;text-align:center;','class'=>'grilla'],'template' => '{view} {update} {delete}',
			        			
			        			//en los siguientes botones. "event.stopPropagation();" evita que se ejecute la funcion que carga la tabla al hacer click en la fila y asi no se anulan los pjax
		            			'buttons'=>[
		            				'view' => function($url, $model, $key){
		            				
		            					return Html::button('<span class="glyphicon glyphicon-eye-open"></span>',
		            										['onclick' => 'event.stopPropagation(); mostrarModalTabla(1, ' . $model['tabla_id'] . ')',
		            										'class' => 'bt-buscar-label',
		            										'style' => 'color:#337ab7'
		            										]);	
		            				},
		            				
									'update' => function($url,$model,$key)
		            						{
		            							//solamente se deja modificar si no tiene datos
		            							if(!$model->tieneDatos){
						        					return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
									  	 			[
								  	 					'class' => 'bt-buscar-label',
														'style' => 'color:#337ab7;',
														'onclick' => 'event.stopPropagation(); mostrarModalTabla(3, ' . $model['tabla_id'] . ');'					
													]);	
												}
		            						},
		            				'delete' => function($url,$model,$key)
		            						{
		            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
								  	 			[
							  	 					'class' => 'bt-buscar-label',
													'style' => 'color:#337ab7;',
													'onclick' => 'event.stopPropagation(); mostrarModalTabla(2, ' . $model['tabla_id'] . ');'						
												]);	
											}
								]
									],
								],
							]); 																							    
				?>
			</td>
			<td width='3%'></td>
			<td width='67%' valign='top'>
			
				<?php
				Pjax::begin(['id' => 'pjaxDatosTabla', 'enableReplaceState' => false, 'enablePushState' => false]);// comienza bloque de grilla
				?>
				<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
					<tr>
						<td><h3><label>Datos</label></h3></td>
						<td align='right'>
							<?php
							 echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'botonNuevoDato', 'onclick' => 'mostrarModalDatos(0)', 'disabled' => $modelResolTabla->tabla_id <= 0]);
							?> 								
						</td>
					</tr>
					<tr>
						<td colspan='2'></td>
					</tr>
				</table>
			
				<?php
				$habilitar= $modelResolTabla->tabla_id > 0 && $modelResolTabla->tieneDatos;
				?>
				<div>
					<table>
						<tr>
							<td><label>Filtro:</label></td>
							<td><?= Html::textInput(null,"",['id' => 'filtroAnio','class' => 'form-control' ,'style' => 'width:50px;', 'maxlength' => '4', 'disabled' => !$habilitar]); ?> </td>
							<td><?= Html::textInput(null,"",['id' => 'filtroCuota','class' => 'form-control' ,'style' => 'width:50px;', 'maxlength' => '3', 'disabled' => !$habilitar]); ?></td>
							<td width='65px;' align='right'><?= Html::button('Buscar', ['class' => 'btn btn-primary', 'id' => 'btnBuscar','onclick' => 'filtrarDatos("' . $modelResolTabla->tabla_id . '");', 'disabled' => !$habilitar]); ?></td>
						</tr>
					</table>
				</div>
				
				<div style="margin-top:5px;">			
				<?php
				
				$columnasMostrar= [];
				
				foreach($columnas as $modelColumna)
					if($modelColumna->aplicable)
						$columnasMostrar[]= ['attribute'=> $modelColumna->param,'label' => $modelColumna->nombre, 
											'value' => function($data) use($modelColumna){return $data[$modelColumna->param];},
											'headerOptions'=>['style'=>'width:50px;text-align:center;'], 
											'contentOptions' => ['style' => 'text-align:right;']];
						
				$cantidad= count($columnasMostrar);
							
				if($cantidad < 6)
					$columnasMostrar= array_merge($columnasMostrar, array_fill($cantidad, 6 - $cantidad, ['class' => 'yii\grid\Column', 'content' => function(){return '';}, 'headerOptions' => ['style' => 'width:50px;']]));
				
				echo GridView::widget([
					'id' => 'GrillaTablaDatos',
			 	    'dataProvider' => $dpDatos,
			 	    'summary' => false,
					'headerRowOptions' => ['class' => 'grillaGrande'],
					'rowOptions' => ['class' => 'grillaGrande'],
					'columns' => 
			        		
			        		array_merge([
			        		['attribute'=>'perdesde','label' => 'Desde','value'=>function($data){return substr($data['perdesde'], 0, 4) . ' - ' . substr($data['perdesde'], 4, 3);}
							,'headerOptions'=>['style'=>'width:70px;text-align:center;'], 'contentOptions' => ['style' => 'text-align:center;']],
							['attribute'=>'perhasta','label' => 'Hasta' ,'value'=>function($data){return substr($data['perhasta'], 0, 4) . ' - ' . substr($data['perhasta'], 4, 3);}
							,'headerOptions'=>['style'=>'width:70px;text-align:center;'], 'contentOptions' => ['style' => 'text-align:center;']]
							],
							
							$columnasMostrar,
							            		  
			        		[ 
			        		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:40px;text-align:center;','class'=>'grilla'],'template' => '{update} {delete}',
			            			'buttons'=>[
										'update' => function($url,$model,$key)
			            						{
						        					return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
									  	 			[
								  	 					'class' => 'bt-buscar-label',
														'style' => 'color:#337ab7;',
														'onclick' => 'mostrarModalDatos(3, ' . $model['dato_id'] . ');'						
													]);	
			            						},
			            				'delete' => function($url,$model,$key)
			            						{
			            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
									  	 			[
								  	 					'class' => 'bt-buscar-label',
														'style' => 'color:#337ab7;',
														'onclick' => 'mostrarModalDatos(2, ' . $model['dato_id'] . ');'						
													]);	
												}
											]
										]
							]
							)
				]);
				?>
				</div>
				
				<?php
				Pjax::end(); // fin bloque de la grilla
				?>
			</td>
		</tr>
	</table>
</div>

<div style="margin-top:5px;">
<?php
if(isset($mensaje) && $mensaje != ''){
	
	echo Alert::widget([
		'id' => 'alertMensaje',
		'options' => ['class' => 'alert alert-success alert-dissmissible'],
		'body' => $mensaje
	]);
	
	?>
	<script type="text/javascript">
	setTimeout(function(){$("#alertMensaje").fadeOut();}, 5000);
	</script>
	<?php
}
?>
</div>

</div>

<!-- VENTANA MODAL VARIABLES -->
<?php
	Pjax::begin(['id' => 'pjaxModalVariables', 'enablePushState' => false, 'enableReplaceState' => false]);
	$accion= intval(Yii::$app->request->get('consultaVariable', -1));
	$titulo= '';
	
	switch($accion){
		case 0: $titulo= 'Nueva variable'; break;
		case 2: $titulo= 'Eliminar variable'; break;
		case 3: $titulo= 'Modificar variable'; break;
	}
	
	Modal::begin([
		'header' => "<h1>$titulo</h1>",
		'toggleButton' => ['class' => 'hidden','data-pjax' => "0"],
		'id' => 'modalVariables',
		'closeButton' => [
			'label' => '<b>&times;</b>',
			'class' => 'btn btn-danger btn-sm pull-right',
		],
	]);
	
			
	echo $this->render('resol_local/_form', ['model' => $modelResolLocal, 'resol_id' => $model->resol_id, 'consulta' => $accion, 'selectorModal' => '#modalVariables']);
	
		 
	Modal::end();
	Pjax::end();
?>
<!-- VENTANA MODAL TABLAS -->
<?php
	Pjax::begin(['id' => 'pjaxModalTablas', 'enablePushState' => false, 'enableReplaceState' => false]);
				   
		
			$accion= intval(Yii::$app->request->get('consultaTabla', -1));
			$titulo= 'Nueva tabla';
			
			switch($accion){
				case 2: $titulo= 'Eliminar tabla'; break;
				case 3: $titulo= 'Modificar tabla'; break;
			}
			
			Modal::begin([
				'header' => '<h1>' . $titulo . '</h1>',
				'footer' => null,
				'toggleButton' => false,
				'id' => 'modalTablas',
				'closeButton' => [
					'label' => '<b>&times;</b>',
					'class' => 'btn btn-danger btn-sm pull-right',
				],
			]);

			echo $this->render('//ctacte/resol/resol_tabla/_form', ['model' => $modelResolTabla, 'columnas'=>$columnas, 'resol_id'=>$model->resol_id, 'consulta'=>$accion, 'selectorModal' => '#modalTablas']);
			 
			Modal::end();
?>

<?php				
	Pjax::end();
?>

<!-- VENTANA MODAL DATOS -->
<?php
	Pjax::begin(['id' => 'pjaxModalDatos', 'enablePushState' => false, 'enableReplaceState' => false]);

		$accion= intval(Yii::$app->request->get('consultaDatos', 1));
		$titulo= 'Consulta dato';
		
		switch($accion){
			case 0: $titulo= 'Nuevo dato'; break;	
			case 2: $titulo= 'Eliminar dato'; break;
			case 3: $titulo= 'Modificar dato'; break;
		}
		
		Modal::begin([
			'header' => '<h1>' . $titulo . '</h1>',
			'footer' => null,
			'toggleButton' => ['class' => 'hidden'],
			'id' => 'modalDatos',
			'closeButton' => [
				'label' => '<b>&times;</b>',
				'class' => 'btn btn-danger btn-sm pull-right',
			],
		]);
		
		echo $this->render('//ctacte/resol/resol_tabla_dato/_form', ['model' => $modelResolTablaDato, 'modelTabla' => $modelResolTabla, 'consulta'=>$accion, 'selectorModal' => '#modalDatos']);
		 
		Modal::end();
	
	Pjax::end();
?>
<script>
function mostrarModalVariables(consulta, variable){
	
	var datos= {
		"consultaVariable": consulta
	};
	
	if(consulta != 0) datos.varlocal= variable;
	
	$.pjax.reload({
		container: "#pjaxModalVariables",
		type: "GET",
		replace: false,
		push: false,
		data: datos
	});
	
	$("#pjaxModalVariables").on("pjax:complete", function(){
		
		$("#modalVariables").modal("show");
		$("#pjaxModalVariables").off("pjax:complete");
	});
}

function mostrarModalTabla(consulta, tabla){
	
	var datos= {
		"consultaTabla": consulta,
	};
	
	if(consulta != 0) datos.tabla= tabla;
		
	$.pjax.reload({
		container: "#pjaxModalTablas",
		type: "GET",
		replace: false,
		push: false,
		data: datos
	});
	
	$("#pjaxModalTablas").on("pjax:complete", function(){
		
		$("#modalTablas").modal("show");
		$("#pjaxModalTablas").off("pjax:complete");
	});
}

function mostrarModalDatos(consulta, codigo){
	
	var datos= {
		"consultaTabla": 1,
		"tabla": $("#tablaActual").val(),
		"consultaDatos": consulta
		};
	
	if(consulta != 0) datos.dato= codigo
	
	$.pjax.reload({
		container: "#pjaxModalDatos",
		type: "GET",
		replace: false,
		push: false,
		data: datos
	});
	
	$("#pjaxModalDatos").on("pjax:complete", function(){

		$("#modalDatos").modal("show");
		$("#pjaxModalDatos").off("pjax:complete");		
	});
}

function cargarTabla(consulta, codigo){
	
	var datos= {"consultaTabla": consulta};
	
	if(consulta != 0) datos.tabla= codigo;
	
	$.pjax.reload({
		container: "#pjaxDatosTabla",
		type: "GET",
		replace: false,
		push: false,
		data: datos
	});
	
	$("#tablaActual").val(codigo);
	$("#botonNuevoDato").prop("disabled", false);
}

function filtrarDatos(tabla){
	
	var anio= $("#filtroAnio").val();
	var cuota= $("#filtroCuota").val();
	
	$.pjax.reload({
		
		container: "#pjaxDatosTabla",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"consultaTabla": 1,
			"tabla": tabla,
			"filtroAnio": anio,
			"filtroCuota": cuota
		}
	});
	
	$("#pjaxDatosTabla").on("pjax:complete", function(){
		
		$("#filtroAnio").val(anio);
		$("#filtroCuota").val(cuota);
		
		$("#pjaxDatosTabla").off("pjax:complete");
	});
}
</script>
