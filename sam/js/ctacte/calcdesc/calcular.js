function calcular(){
	
	var error = new Array(),
		datos = {},
		obj_id = $("#calcularCodigoObjeto").val(),
	 	anio = $("#anio").val(),
	 	cuota = $("#cuota").val(),
	 	monto = $("#monto").val(),
	 	fchpago = $("#fchpago").val(),
	 	trib_id = $("#calcularCodigoTributo").val();
	
	if ( trib_id == '' || trib_id == null )
		error.push( "Seleccione un tributo de la grilla." );
	
	if ( obj_id == '' || obj_id == null )
		error.push( "Ingrese un objeto." );
	
	if ( anio == '' || cuota == '' || anio == 0 || cuota == 0 )
		error.push( "Ingrese un período." );
		
	if ( fchpago == '' || fchpago == null )
		error.push( "Ingrese una fecha." );
		
	if ( error.length == 0 )
	{
		//Ocultar los mensajes de error
		$("#descuentoCalcular_errorSummary").css("display","none");
		
		$.get(rutasCalcular.calcular,
				{
					"calcular": true,
					"codigoTributo": trib_id,
					"codigoObjeto": obj_id,
					"anio": anio,
					"cuota": cuota,
					"monto": monto,
					"fecha": fchpago
				},
				function(data){
					$("#total").val(data);
				}
		);
	
	} else
	{
		mostrarErrores( error, "#descuentoCalcular_errorSummary" );
	}		 	
		
}


function cambiaObjeto(){
	
	var nuevo= $("#calcularCodigoObjeto").val();
	var tributo= $("#calcularCodigoTributo").val();
	
	$.get(rutasCalcular.nombreObjeto, {"codigoObjeto": nuevo, "tributo": tributo}, function(data){
		
		$("#calcularCodigoObjeto").val(data.codigo);
		$("#calcularNombreObjeto").val(data.nombre);
	});
}