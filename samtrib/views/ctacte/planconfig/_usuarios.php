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

function dblClickFila($fila, $contenedor)
{
	
	$contenedor.find(".active").removeClass("active");
	$fila.addClass("active");
	
	moverFilasActivasUsuarios($contenedor);
}

function moverFilasActivasUsuarios($contenedor)
{
	var codigos = [];
	
	var codigoPlan = $("#planConfigFormCod").val();
	
	$contenedor.find(".active").each(function(){
		
		var codigo = $(this).data("key");
		codigos.push(codigo);
	});		
	
	
	
	if(codigos.length > 0)
		$.pjax.reload( { container : "#pjaxUsuarios", push : false, replace : false, type : "GET", data : { "usr_id" : codigos, "accion" : "switchUsuario", "id" : codigoPlan } } );
	
}

function moverFilasTodasUsuarios(activos)
{
	var codigoPlan = $("#planConfigFormCod").val();
	
	$.pjax.reload( { container : "#pjaxUsuarios", push : false, replace : false, type : "GET", data : {"activos" : activos, "accion" : "switchUsuarioTodos", "id" : codigoPlan } } );
	
}

function asignarPorGrupoUsuarios(grupo)
{
	var codigoPlan = $("#planConfigFormCod").val();
	
	$.pjax.reload( { container : "#pjaxUsuarios", push : false, replace : false, type : "GET", data : { "grupo" : grupo, "accion" : "switchUsuarioPorGrupo", id : codigoPlan } } );
}
</script>

<div class="planConfigUsuarios">

	<div class="row">
		<div class="col-xs-8">
			
			<?php
			echo Html::checkbox(null, false, ['id' => 'usuariosElegirGrupo', 'label' => 'Asignar usuario por grupo']);
			?>
		
		
		
			<?php
			echo Html::dropDownList(null, null, $extras['gruposUsuario'], ['class' => 'form-control', 'id' => 'usuariosGrupoElegido', 'style' => 'width:auto;', 'disabled' => 'disabled']);
			?>
			
			<button type="button" id="planConfigUsuarioAsignarPorGrupo" class="btn btn-success" disabled="disabled">
  				<span class="glyphicon glyphicon-play" aria-hidden="true"></span>
			</button>
			
			
			
			<script type="text/javascript">
			
			$("#planConfigUsuarioAsignarPorGrupo").click(function(){
				
				var grupo = $("#usuariosGrupoElegido").val();
				
				asignarPorGrupoUsuarios(grupo);
			});
			
			//habilita y deshabilita la lista de grupos de usuarios
			$("#usuariosElegirGrupo").click(function(e){
				
				if( $(this).is(":checked") )
				{
					$("#usuariosGrupoElegido").removeAttr("disabled");
					$("#planConfigUsuarioAsignarPorGrupo").removeAttr("disabled");	
				}
				else
				{
					$("#usuariosGrupoElegido").attr("disabled", "disabled");
					$("#planConfigUsuarioAsignarPorGrupo").attr("disabled", "disabled");
				} 
			});
			
			</script>
		</div>
	</div>


	<?php
	Pjax::begin(['id' => 'pjaxUsuarios', 'enablePushState' => false, 'enableReplaceState' => false]);
	?>
	<div class="row">
		<div class="col-xs-5">
		
			<h3><u>Usuario sin asignar</u></h3>
			<?php
			Pjax::begin(['enablePushState' => false, 'enableReplaceState' => false]);
			?>
			
			<table class="table table-striped table-bordered lista" id="listaUsuariosSinAsignar">
			
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
							'dataProvider' => $extras['dpUsuariosSinAsignar'],
							'options' => ['tag' => false],
							'itemOptions' => ['tag' => 'tr', 'onclick' => 'clickFila( $(this) );', 'ondblclick' => 'dblClickFila($(this), $("#listaUsuariosSinAsignar"));'],
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
							<button type="button" class="btn btn-default" onclick="moverFilasActivasUsuarios($('#listaUsuariosSinAsignar'));">
  								<span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span>
							</button>
						</li>
						
						<li>
							<button type="button" class="btn btn-default" onclick="moverFilasTodasUsuarios(false);">
  								<span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>
							</button>
						</li>
						
						<li>
							<br>
						</li>
						
						<li>
							<button type="button" class="btn btn-default" onclick="moverFilasActivasUsuarios($('#listaUsuariosAsignados'))">
  								<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span>
							</button>
						</li>
						
						<li>
							<button type="button" class="btn btn-default" onclick="moverFilasTodasUsuarios(true);">
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
			
			<h3><u>Usuario asignados</u></h3>
			<table class="table table-striped table-bordered lista" id="listaUsuariosAsignados">
			
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
							'dataProvider' => $extras['dpUsuariosAsignados'],
							'options' => ['tag' => false],
							'itemOptions' => ['tag' => 'tr', 'onclick' => 'clickFila( $(this) );', 'ondblclick' => 'dblClickFila($(this), $("#listaUsuariosAsignados"));'],
							'itemView' => function($model, $key, $index, $widget){
					
							$nombre = $model['nombre'];
					
							return '<td class="grilla"><span  class="glyphicon glyphicon-chevron-left"></span></td><td class="grilla">' . $nombre . '</td>';
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