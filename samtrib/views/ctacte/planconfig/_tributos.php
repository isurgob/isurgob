<?php

use yii\helpers\Html;

use yii\widgets\ListView;
use yii\widgets\Pjax;
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
function clickFila($fila)
{
	if( $fila.hasClass("active") )
		$fila.removeClass("active");
	else $fila.addClass("active");
}

function dblClickFilaTributos($fila, $contenedor)
{
	
	$contenedor.find(".active").removeClass("active");
	$fila.addClass("active");
	
	moverFilasActivasTributos($contenedor);
}

function moverFilasActivasTributos($contenedor)
{
	var codigos = [];
	
	var codigoPlan = $("#planConfigFormCod").val();
	
	$contenedor.find("tr.active").each(function(){
		
		var codigo = $(this).data("key");
		codigos.push(codigo);
	});		
	
	
	
	if(codigos.length > 0)
		$.pjax.reload( { container : "#pjaxTributos", push : false, replace : false, type : "GET", data : { "trib_id" : codigos, "accion" : "switchTributo", "id" : codigoPlan } } );
	
}

function moverFilasTodasTributos(activos)
{
	var codigoPlan = $("#planConfigFormCod").val();
	
	$.pjax.reload( { container : "#pjaxTributos", push : false, replace : false, type : "GET", data : {"activos" : activos, "accion" : "switchTributoTodos", "id" : codigoPlan } } );
	
}
</script>

<div class="planConfigTributos">




	<?php
	Pjax::begin(['id' => 'pjaxTributos']);
	?>
	<div class="row">
		<div class="col-xs-5">
		
			
			<?php
			Pjax::begin(['enablePushState' => false, 'enableReplaceState' => false]);
			?>
			
			<h3><u>Tributos sin asignar</u></h3>
			<table class="table table-striped table-bordered lista" id="listaTributosSinAsignar">
			
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
							'dataProvider' => $extras['dpTributosSinAsignar'],
							'options' => ['tag' => false],
							'itemOptions' => ['tag' => 'tr', 'onclick' => 'clickFila( $(this) );', 'ondblclick' => 'dblClickFilaTributos($(this), $("#listaTributosSinAsignar"));'],
							'itemView' => function($model, $key, $index, $widget){
					
								$nombre = $model['nombre'];
					
								return '<td class="grilla"><span  class="glyphicon glyphicon-chevron-right"></span></td><td class="grilla">' . $nombre . '</td>';
							}  
							 ] );
			
			
			
			
						
			echo $lista->renderSection('{summary}');
			
			$lista->init();
			echo $lista->renderSection('{items}');
			
			
			?>
				</tbody>
			</table>
		
			<?php
			echo $lista->renderSection('{pager}');
			
			Pjax::end();
			?>
		
			
		</div>
		
		
		<div class="col-xs-2">
		
			<div class="row">
			
				<div class="col-xs-10 col-xs-offset-1">
				
					<h3>&nbsp;</h3>
					<ul class="list-unstyled" style="margin-top:17px;">
						<li>
							<button type="button" class="btn btn-default" onclick="moverFilasActivasTributos($('#listaTributosSinAsignar'));">
  								<span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span>
							</button>
						</li>
						
						<li>
							<button type="button" class="btn btn-default" onclick="moverFilasTodasTributos(false);">
  								<span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>
							</button>
						</li>
						
						<li>
							<br>
						</li>
						
						<li>
							<button type="button" class="btn btn-default" onclick="moverFilasActivasTributos($('#listaTributosAsignados'))">
  								<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span>
							</button>
						</li>
						
						<li>
							<button type="button" class="btn btn-default" onclick="moverFilasTodasTributos(true);">
  								<span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span>
							</button>
						</li>
					</ul>
				
				</div>
			
			</div>
		
		</div>
		
		
		<div class="col-xs-5">
		
			<?php
			Pjax::begin(['enablePushState' => false, 'enableReplaceState' => false]);
			?>
			
			<h3><u>Tributos asignados</u></h3>
			<table class="table table-striped table-bordered lista" id="listaTributosAsignados">
			
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
							'dataProvider' => $extras['dpTributosAsignados'],
							'options' => ['tag' => false],
							'itemOptions' => ['tag' => 'tr', 'onclick' => 'clickFila( $(this) );', 'ondblclick' => 'dblClickFilaTributos($(this), $("#listaTributosAsignados"));'],
							'itemView' => function($model, $key, $index, $widget){
					
					
								return '<td class="grilla"><span  class="glyphicon glyphicon-chevron-left"></span></td><td class="grilla">' . $model['nombre'] . '</td>';
							}  
							 ] );
			
			
			
			
						
			echo $lista->renderSection('{summary}');
			
			$lista->init();
			echo $lista->renderSection('{items}');
			
			
			
			
			?>
				</tbody>
			</table>
		
			<?php
			echo $lista->renderSection('{pager}');
			
			Pjax::end();
			?>		
		</div>
	</div>
	
	<?php
	Pjax::end();
	?>
</div>