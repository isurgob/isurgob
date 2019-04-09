<script>
/* Función que se ejecuta cuando la caja está cerrada */

function cajaCerrada()
{
	$("#div_cajacobro").css("display","block");
	$("#div_superior").css("display","none");
	$("#div_cobrar").css("display","none");
	$("#div_ingreso").css("display","none");
	$("#div_egreso").css("display","none");
	$("#div_inferior").css("display","none");
	$("#div_inferior_final").css("display","none");

}

function cajaActiva()
{
	$("#div_cajacobro").css("display","block");
	$("#div_superior").css("display","block");
	$("#div_cobrar").css("display","none");
	$("#div_ingreso").css("display","none");
	$("#div_egreso").css("display","none");
	$("#div_inferior").css("display","none");
	$("#div_inferior_final").css("display","none");

}

function cajaConTicket()
{
	$("#div_cajacobro").css("display","block");
	$("#div_superior").css("display","block");
	$("#div_cobrar").css("display","block");
	$("#div_ingreso").css("display","none");
	$("#div_egreso").css("display","none");
	$("#div_inferior").css("display","block");
	$("#div_inferior_final").css("display","none");
	$("#cajaCobro_codBarra").val("");
}

function cajaIMDP()
{

	cajaConTicket();

	$("#cajaCobro_codBarra").attr("disabled",true);

	$("#div_superior").attr('class','form-panel disabled');

	$("#cajaCobro_btCobrar").attr("disabled",true);
	$("#div_cobrar").attr('class','form-panel disabled');

	$("#div_ingreso").css("display","block");
	$("#div_inferior_final").css("display","none");

}

function cajaEMDP()
{
	cajaIMDP();

	$("#cajaCobro_codBarra").attr("disabled",true);

	$("#div_superior").attr('class','form-panel disabled');

	$("#cajaCobro_btCobrar").attr("disabled",true);
	$("#div_cobrar").attr('class','form-panel disabled');

	$("#cajaCobro_dlIMdp").attr("disabled",true);
	$("#cajaCobro_txTotalIngreso").attr("disabled",true);
	$("#btAgregarIMdp").attr("disabled",true);
	$("#btAceptarIMdp").attr("disabled",true);
	$("#div_ingreso").attr('class','form-panel disabled');

	$("#div_egreso").css("display","block");

	$("#div_inferior").css("display","none");
	$("#div_inferior_final").css("display","block");

}

function cajaInferior()
{
	cajaIMDP();

	$("#cajaCobro_codBarra").attr("disabled",true);

	$("#div_superior").attr('class','form-panel disabled');

	$("#cajaCobro_btCobrar").attr("disabled",true);
	$("#div_cobrar").attr('class','form-panel disabled');

	$("#cajaCobro_dlIMdp").attr("disabled",true);
	$("#cajaCobro_txTotalIngreso").attr("disabled",true);
	$("#btAgregarIMdp").attr("disabled",true);
	$("#btAceptarIMdp").attr("disabled",true);
	$("#div_ingreso").attr('class','form-panel disabled');

	$("#div_inferior").css("display","none");
	$("#div_inferior_final").css("display","block");

  	document.getElementById("cajaCobro_btAgregarEMdp").focus();


}

function totalCobro(e,ticket)
{
	var keynum = window.event ? window.event.keyCode : e.which;

    if ( keynum == 13 || keynum == 0 )
	{
		var redondeo = 0;
		if ($("#cajaPrueba_ckAplicaRed").is(":checked"))
			redondeo = 1;

		$("#cajaCobro_codBarra").val("");

		//Ocultar mensaje de error
		$( "#caja_errorSummary" ).css( "display", "none" );

		$.pjax.reload({container:"#PjaxAgregaTicket",method:"POST",data:{ticket:ticket,redondeo:redondeo}});
	}


	return true;

}

function cerrarModalCajaDetalle()
{
	$("#ModalCajaDetalle").modal("hide");

	$( "#cajaCobro_codBarra" ).focus();
}

function cajaDetalleBtAceptar()
{
	$.pjax.reload({container:"#PjaxNuevoTicket",method:"POST",data:{ejecuta:1}});

	cerrarModalCajaDetalle();

}

function btCobrar()
{
	cajaIMDP();

	$("#PjaxBtCobrar").on("pjax:end", function()
	{

			$.pjax.reload({container:"#PjaxMenuDerecho",method:"POST"});

			$("#PjaxMenuDerecho").on("pjax:end", function()
			{

				$.pjax.reload({container:"#PjaxGrillaIMdp",method:"POST"});
				$("#PjaxMenuDerecho").off("pjax:end");
			});

		$("#PjaxBtCobrar").off("pjax:end");
	});


}

function nuevoIMdp()
{
	var tipoIMDP = 1,
		medioIMDP = isNaN( parseInt( $("#cajaCobro_dlIMdp").val() ) ) ? 0 : parseInt( $("#cajaCobro_dlIMdp").val() ),
		cantIMDP = $("#cajaCobro_txTotalIngreso").val(),
		error = new Array();

	if ( medioIMDP == 0 )
		error.push( "Ingrese un Medio de Pago." );

	if ( cantIMDP == '' || cantIMDP == 0 )
		error.push( "Ingrese un monto." );


	if ( error.length == 0 )
	{
		//Ocultar div errores
		$( "#caja_errorSummary" ).css( "display", "none" );

		$.pjax.reload({
			container:"#PjaxMedioPago",
			method:"POST",
			data:
			{
				tipoMDP:tipoIMDP,
				medioMDP:medioIMDP,
				cantMDP:cantIMDP,
			},
		});

	} else
	{
		mostrarErrores( error, "#caja_errorSummary" );
	}
}

function nuevoEMdp()
{
	var tipoEMDP = 2,
		medioEMDP = isNaN( parseInt( $("#cajaCobro_dlEMdp").val() ) ) ? 0 : parseInt( $("#cajaCobro_dlEMdp").val() ),
		cantEMDP = $("#cajaCobro_txTotalEgreso").val(),
		error = new Array();

	if ( medioEMDP == 0 )
		error.push( "Ingrese un Medio de Pago." );

	if ( cantEMDP == '' || cantEMDP == 0 )
		error.push( "Ingrese un monto." );

	if ( error.length == 0 )
	{
		//Ocultar div errores
		$( "#caja_errorSummary" ).css( "display", "none" );

		$.pjax.reload({
			container:"#PjaxMedioPago",
			method:"POST",
			data:{
				tipoMDP:tipoEMDP,
				medioMDP:medioEMDP,
				cantMDP:cantEMDP,
			}});
	} else
	{
		mostrarErrores( error, "#caja_errorSummary" );
	}
}

function muestraSellado()
{
	$.pjax.reload("#PjaxSellado");

	$("#PjaxSellado").on("pjax:end",function(){

		$("#ModalSellado").modal("show");

		$("#PjaxSellado").off("pjax:end");

	});
}

function muestraEMdp()
{
	cajaEMDP();

	$.pjax.reload({container:"#PjaxMenuDerecho",method:"POST"});

	$("#PjaxMenuDerecho").on("pjax:end", function(){

		$.pjax.reload({container:"#PjaxGrillaEMdp",method:"POST"});

		$("#PjaxMenuDerecho").off("pjax:end");
	});
}

function muestraInferior()
{
	cajaInferior();

	$.pjax.reload({container:"#PjaxMenuDerecho",method:"POST"});

	$("#PjaxMenuDerecho").on("pjax:end", function(){

		$.pjax.reload({container:"#PjaxGrillaEMdp",method:"POST"});

		$("#PjaxGrillaEMdp").on("pjax:end", function(){

			$(document).ready(function()
			{
			  	document.getElementById("cajaCobro_btAgregarEMdp").focus();
			});

			$("#PjaxGrillaEMdp").off("pjax:end");
		});

		$("#PjaxMenuDerecho").off("pjax:end");
	});

}

function cajaCobro_btCancelar()
{
	//Ocultar div errores
	$( "#caja_errorSummary" ).css( "display", "none" );

	$.pjax.reload({
		container:"#reiniciaModelo",
		method:"POST",
		data:{
			reiniciaModelo:1,
		},
	});
}

</script>
