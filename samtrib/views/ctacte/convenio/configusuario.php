<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;

use yii\grid\column;
use yii\grid\GridView;

use yii\widgets\ListView;
use yii\widgets\Pjax;

use yii\bootstrap\Alert;

use app\utils\db\utb;

$title = 'Convenios por usuario';
$this->params['breadcrumbs'][]= ['label' => 'Configuraciones', 'url' => Yii::$app->request->baseUrl.'/index.php?r=site/config'];
$this->params['breadcrumbs'][] = $title;

?>

<style>
table.lista tbody > tr:hover,
table.lista tbody > tr.active{
	background-color : #9BCCE1;
	cursor : pointer;
}

table.lista tbody > tr > td:first-of-type{
	text-align : center;
}

table.lista tbody > tr > td:first-of-type *{
	visibility : hidden;
}

table.lista tbody > tr:hover > td:first-of-type *,
table.lista tbody > tr.active > td:first-of-type *{
	visibility : visible;
}

table.lista thead > tr > th:first-of-type{
	width : 20%;
}
</style>


<script type="text/javascript">
function esEditable(){
	
	var editable = parseInt($("#modoEdicion").val());
	
	return (!isNaN(editable) && editable === 1) ? true : false;
}

function clickFila($fila){
	
	if(!esEditable()) return;
	
	if( $fila.hasClass("active") )
		$fila.removeClass("active");
	else $fila.addClass("active");
}

function dblClickFila($fila, $contenedor, $destino){
	
	if(!esEditable()) return;
	
	$contenedor.find(".active").removeClass("active");
	$fila.addClass("active");
	
	moverFilasActivas($contenedor, $destino);
}

function moverFilasActivas($contenedor, $destino){
	
	if(!esEditable()) return;
	
	var codigos = [];
	
	$contenedor.find(".active").each(function(){
		
		$fila = $(this).detach();
		$fila.removeClass("active");
		$fila.appendTo($destino.find("tbody"));
		
		$span = $fila.find("td:first-of-type span");
		
		if($span.hasClass("glyphicon-chevron-right")){
			$span.removeClass("glyphicon-chevron-right");
			$span.addClass("glyphicon-chevron-left");
		}
		else {
			$span.removeClass("glyphicon-chevron-left");
			$span.addClass("glyphicon-chevron-right");
		}
	});	
}

function moverFilasTodas($contenedor, $destino){
	
	if(!esEditable()) return;
	
	$contenedor.find("tbody tr").addClass("active");
	moverFilasActivas($contenedor, $destino);	
}

function marcarUsuarioActivo(usuario, indice){
	
	$("#botonEditar").prop("disabled", false);
	elegida = $("#tablaUsuarios tr").get(indice + 1);
	
	if($(elegida).hasClass("active"))
		return;
	
	$filas = $("#tablaUsuarios tr");
	$filas.removeClass("active");
	
	$("#usuarioActivo").val(usuario);
	
	$( $(elegida) ).addClass("active");
	
	$.pjax.reload({container : "#pjaxPlanes", push : false, replace : false, type : "GET", data : {"accion" : "cambioUsuario", "usuario" : usuario} } );
}

function aplicarFiltros(){
	
	var grupo = grupo = $("#filtroGruposUsuario").val();
	var usuario = $("#usuarioActivo").val();
	
	$.pjax.reload({container : "#pjaxGrilla", push : false, replace : false, type : "GET", data : {"accion" : "filtros", "grupo" : grupo, "usuario" : usuario} } );
	
	$("#usuarioActivo").val('');
	$("#listaPlanesSinAsignar tbody").empty();
	$("#listaPlanesAsignados tbody").empty();
}

function guardarCambios(){
	
	if(!esEditable()) return;
	
	var codigos = [];
	var usuario = $("#usuarioActivo").val();
	
	$("#listaPlanesAsignados tbody tr").each(function(){
		
		var cod = $(this).data("key");
		codigos.push(cod);
	});
	
	$.pjax.reload({container : "#pjaxMensaje", push : false, replace : false, type : "POST", data : {"planes" : codigos, "usuario" : usuario}});
}

function editar(editar){

	$("#divBotones .sender").toggleClass("hidden", editar);
	$("#divBotones .edicion").toggleClass("hidden", !editar);
	
	$("#botonesAccion .boton").toggleClass("disabled", !editar);
	
	$("#modoEdicion").val(editar);
}
</script>

<input type="hidden" id="modoEdicion" value="0">
<div class="planConfigUsuario">

	<h1>Configuración de Convenios por usuario</h1>


	<input type="hidden" id="usuarioActivo" value="0">
	
	<div class="form" style="padding:10px;">
		<table width="100%">
			<tr>
				<!-- listado de usuarios -->
				<td>
					<table width="340px">
						<tr>
							<td width="70px"><strong>Grupo:</strong></td>
							<td width="270px">
							<?= Html::dropDownList(
									null, 
									null, 
									$extras['gruposUsuario'], 
									[
									'class' => 'form-control', 
									'id' => 'filtroGruposUsuario', 
									'onchange' => 'aplicarFiltros();'
									]
									) 
							?>
							</td>
							<td width="100px"></td>
						</tr>
						
						<tr>
							<td colspan="4">
								<?php
								Pjax::begin(['id' => 'pjaxGrilla', 'enablePushState' => false, 'enableReplaceState' => false]);
								
								echo GridView::widget([
									'dataProvider' => $extras['dpUsuarios'],
									'emptyText' => 'Sin resultados',
									'summaryOptions' => ['class' => 'hidden'],
									'tableOptions' => ['class' => 'table table-striped table-bordered lista', 'id' => 'tablaUsuarios'],
									'rowOptions' => function($model, $key, $index, $column){
										return ['onclick' => 'marcarUsuarioActivo(' . $key . ', ' . $index . ')', 'class' => 'grillaGrande'];
									},
									
									
									'columns' => [
										
										[
											'header' => '',
											'headerOptions' => ['style' => 'width:15px;', 'class' => 'grillaGrande'],
											'content' => function($model, $key, $index, $column){
												
												return '<span class="glyphicon glyphicon-chevron-right"></span>';
											},
										],
										
										[
											'header' => 'Cod',
											'headerOptions' => ['class' => 'grillaGrande'],
											'content' => function($model, $key, $index, $column){
												return $key;
											}
										],
										
										['attribute' => 'nombre', 'header' => 'Nombre', 'headerOptions' => ['class' => 'grillaGrande'], 'options' => ['style' => 'width:400px;']]
									]	
								]);
												
								Pjax::end();
								?>
							</td>
						</tr>
					</table>
				</td>
				<!-- fin listado de usuarios -->
			</tr>
		</table>
	</div>
	
	<div style="margin-top:5px;">
	<?php
		Pjax::begin(['id' => 'pjaxPlanes', 'enablePushState' => false, 'enableReplaceState' => false]);
	?>
	
		<table>
			<tr>
				
				
				<!-- listados de planes de configuracion no asignados -->
				<td width="340px" valign="top">
					
					<div class="form">
					
					<h4><strong>Configuraciones sin asignar</strong></h4>
					
					<table style="width:95%; margin-left:auto; margin-right:auto;" class="table table-striped table-bordered lista" id="listaPlanesSinAsignar">
					
						<thead>
							<th>
							</th>
							<th>
								Nombre
							</th
						</thead>
				
						<tbody>
						<?php			
						
							
						$lista = new ListView( [
									'dataProvider' => $extras['dpPlanesSinAsignar'],
									'options' => ['tag' => false],
									'summaryOptions' => ['class' => 'hidden'],
									'itemOptions' => [
													'tag' => 'tr', 
													'onclick' => 
																'clickFila( $(this) );', 
																'ondblclick' => 'dblClickFila($(this), $("#listaPlanesSinAsignar"), $("#listaPlanesAsignados"));'],
																
									'itemView' => function($model, $key, $index, $widget){
							
										$nombre = $model['nombre'];
							
										return '<td class="grilla">' .
												'<span  class="glyphicon glyphicon-chevron-right">' .
												'</span>' .
												'</td>' .
												'<td class="grilla">' . 
												$nombre . 
												'</td>';
									}  
								]);
						
						echo $lista->renderSection('{summary}');
						
						$lista->init();
						echo $lista->renderSection('{items}');
				
												
						?>
						</tbody>
					</table>
					
					</div>
				</td>
	
				<td width="10px"></td>
				<?php if (utb::getExisteProceso(3351)) { ?>
				<!-- botones de edicion -->
				<td width="20px" valign="top">
				
					<ul class="list-unstyled" style="margin-top:17px;" id="botonesAccion">
						<li>
							<button type="button" class="btn btn-default boton" onclick="moverFilasActivas($('#listaPlanesSinAsignar'), $('#listaPlanesAsignados'));">
								<span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span>
							</button>
						</li>
						
						<li>
							<button type="button" class="btn btn-default boton" onclick="moverFilasTodas($('#listaPlanesSinAsignar'), $('#listaPlanesAsignados'));">
								<span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>
							</button>
						</li>
						
						<li>
							<br>
						</li>
						
						<li>
							<button type="button" class="btn btn-default boton" onclick="moverFilasActivas($('#listaPlanesAsignados'), $('#listaPlanesSinAsignar'))">
								<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span>
							</button>
						</li>
						
						<li>
							<button type="button" class="btn btn-default boton" onclick="moverFilasTodas($('#listaPlanesAsignados'), $('#listaPlanesSinAsignar'));">
								<span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span>
							</button>
						</li>
					</ul>
				</td>
				
				<td width="10px"></td>
				<?php } ?>
				<!-- listado de planes de configuracion asignados -->
				<td width="340px" valign="top">
				
					<div class="form">
				
					<h4><strong>Configuraciones asignadas</strong></h4>
				
					<table style="width:95%; margin-left:auto; margin-right:auto;" class="table table-striped table-bordered lista" id="listaPlanesAsignados">
						
						<thead>
							<th>
							</th>
							<th>
								Nombre
							</th
						</thead>
					
						<tbody>
						<?php			
						
						
						$lista = new ListView( [
									'dataProvider' => $extras['dpPlanesAsignados'],
									'options' => ['tag' => false],
									'summaryOptions' => ['class' => 'hidden'],
									'itemOptions' => [
													'tag' => 'tr', 
													'onclick' => 
																'clickFila( $(this) );', 
																'ondblclick' => 'dblClickFila($(this), $("#listaPlanesAsignados"), $("#listaPlanesSinAsignar"));'],
																
									'itemView' => function($model, $key, $index, $widget){
							
										$nombre = $model['nombre'];
							
										return '<td class="grilla">' .
												'<span  class="glyphicon glyphicon-chevron-left">' .
												'</span>' .
												'</td>' .
												'<td class="grilla">' . 
												$nombre . 
												'</td>';
									}  
								]);
		
						echo $lista->renderSection('{summary}');
						
						$lista->init();
						echo $lista->renderSection('{items}');
						
											
						?>
						</tbody>
					</table>
					
					</div>
				<td>
				
			</tr>
		</table>
	
	
	<?php
	pjax::end();
	?>
	
	</div>
</div>

<div style="margin-top:5px;">
<?php
Pjax::begin(['id' => 'pjaxMensaje', 'enablePushState' => false, 'enableReplaceState' => false]);
	
	$mostrar = false;
	$clase = '';
	$mensaje = '';
	
	if(isset($extras['mensaje'])){
		$mostrar = true;
		$mensaje = $extras['mensaje'];
		$clase = 'alert alert-success alert-dissmisible';
	}
	else if(isset($extras['error'])){
		$mostrar = true;
		$mensaje = $extras['error'];
		$clase = 'alert alert-danger alert-dissmisible';
	}
	
	if($mostrar){
		
		echo Alert::widget([
 			'options' => ['class' => $clase, 'id' => 'alert'],
			'body' => $mensaje
		]);
	
		echo "<script>window.setTimeout(function() { $('#alert').alert('close'); }, 5000)</script>";
	}
	
Pjax::end();
?>
</div>


<?php if (utb::getExisteProceso(3351)) { ?>
<div style="margin-top:20px;" id="divBotones">
	<button type="button" class="btn btn-primary sender" onclick="editar(1)" disabled id="botonEditar">Editar</button>
	<button type="button" class="btn btn-success hidden edicion" onclick="guardarCambios()">Grabar</button>
	<button class="btn btn-primary hidden edicion" onclick="editar(0)">Cancelar</button>
</div>
<?php } ?>