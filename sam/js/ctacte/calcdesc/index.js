$(document).ready(function(){
	
	cargarTributosUsados();
	
	$("#selectTrib").change(function(){
		cambiaTributo($(this).val());
	});
	
	$("#tablaDatos thead td[data-orden]").click(function(){
	
		cargarModelos($("#selectTrib").val(), $(this).data("orden"), "asc");
	});
	
	$(".loader").each(function(){
	
		$(this).load($(this).data("target"));
	});
});

function cargarTributosUsados(){
	
	$.get(rutas.cargarTributosUsados, {}, function(data){
				
		for(var tributo in data){
			
			$opcion= $("<option />");
			$opcion.val(tributo);
			$opcion.text(data[tributo]);
			
			$opcion.appendTo($("#selectTrib"));
		}
		
		var tributo= parseInt($("#selectTrib option:selected").val());
		if(isNaN(tributo) || tributo < 0) tributo= 0;
		
		cambiaTributo(tributo);
	});
}

function cambiaTributo(nuevo){
	
	cargarModelos(nuevo, "desc_id", "asc");
}

function cargarModelos(tributo, campoOrden, orden){

	limpiarTabla();
	$("#tablaDatos").data("tributo", tributo);
	
	$.get(rutas.cargarModelos, {"tributo": tributo, "campoOrden": campoOrden, "orden": orden}, function(data){
		
		for(var descuento in data)
			agregarDescuento(data[descuento]);
	});
}

function agregarDescuento(descuento){

	$padre= $("#tablaDatos tbody");
	$template= $("#tablaDatos tr.template").clone();

	for(var campo in descuento)
		$template.find("[data-match='" + campo + "']").text(descuento[campo]);
	
	$template.find(".modificar").attr("href", rutas.modificar + "&id=" + descuento.desc_id);
	$template.find(".eliminar").attr("href", rutas.eliminar + "&id=" + descuento.desc_id + "&accion=2");
	
	$template.appendTo($padre);
	$template.click(function(){
	
		$("#tablaDatos tr.success").removeClass("success");
		cargarDatos(descuento.desc_id);
		$(this).addClass("success");
	});
	
	$template.removeClass("template");
}

function limpiarTabla(){

	$tabla= $("#tablaDatos tbody");
	$template= $tabla.find("tr.template").clone();
	$tabla.empty();
	$template.appendTo($tabla);
}

function cargarDatos(codigoDescuento){

	$("#descuentoTabDatos").load(rutas.datos + "&codigoDescuento=" + codigoDescuento);
	$("#descuentoTabCalcular").load(rutas.calcular + "&codigoDescuento=" + codigoDescuento);
}