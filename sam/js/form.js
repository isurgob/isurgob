/* funcion para desactivar los controles del formulario */

function DesactivarForm(fr)
{
	var frm = document.getElementById(fr);
	for (i=0;i<frm.elements.length;i++)
	{
		frm.elements[i].readOnly = true;
		frm.elements[i].disabled = true;
	}
}

function ActivarForm(fr)
{
	var frm = document.getElementById(fr);
	for (i=0;i<frm.elements.length;i++)
	{
		frm.elements[i].readOnly = false;
		frm.elements[i].disabled = false;
	}
}

function ValidarCUIT(cuit)
{
	cuit = cuit.replace(/[-_]/gi,"");
	if (cuit.length < 11) return 0;
	base = "54327654321";
	total = 0;
	for (i=0;i<10;i++)
	{
		total = total + parseInt(cuit.substring(i, i+1))*parseInt(base.substring(i, i+1));
	}
	if (total%11 == 0)
	{
		return 1;
	}else{
		return 0;
	}
}

function DesactivarFormPost(fr)
{
	var frm = document.getElementById(fr);
	for (i=0;i<frm.elements.length;i++)
	{
		var el = frm.elements[i];

		if(el.name == "_csrf")
			continue;

		if(el.nodeName === "SELECT")
		{
			el.readOnly = true;
			el.disabled = true;
		}
		else if(el.type === 'checkbox' || el.type === 'radio')
		{
			el.readOnly = true;
			el.disabled = true;
		}
		else el.readOnly = true;
	}
}

function ConsultaGrilla(trib,campo,or)
{
	if (or=='asc'){
		or='desc';
	}else if (or=='desc'){
		or='asc';
	}else {
		or='asc';
	}

	campo = campo+' '+or;

	$.pjax.reload({container:'#idGrid',data:{tr:trib,orden:campo,or:or, cargar:true},method:'POST'});
}


/* Funci�n que sirve para actualizar el nombre de cuenta (ctacte/calcDesc) */

	function actualizaNombreCuenta(dato){

		var elementoIdCuenta = document.getElementById('cuenta_id');
		var elementoNombreCuenta = document.getElementById('cuenta_nombre');

		elementoNombreCuenta.value = dato;

}


function ValidarRangoFechaJs(desde,hasta)
{
	fecha_des = desde.split('/');
	fecha_hast = hasta.split('/');
	if (fecha_des[0].length < 2) fecha_des[0] = '0'+fecha_des[0];
	if (fecha_des[1].length < 2) fecha_des[1] = '0'+fecha_des[1];
	if (fecha_hast[0].length < 2) fecha_hast[0] = '0'+fecha_hast[0];
	if (fecha_hast[1].length < 2) fecha_hast[1] = '0'+fecha_hast[1];

	if(fecha_des[2] > fecha_hast[2]) return 1;
	if (fecha_des[1] > fecha_hast[1] && fecha_des[2] == fecha_hast[2]) return 1;
	if ((fecha_des[0] > fecha_hast[0]) && (fecha_des[1] == fecha_hast[1]) && fecha_des[2] == fecha_hast[2]) return 1;

	return 0;
}

function isDate(string)
{ //string estará en formato dd/mm/yyyy (dí­as < 32 y meses < 13)
  var ExpReg = /^([0][1-9]|[12][0-9]|3[01])(\/|-)([0][1-9]|[1][0-2])\2(\d{4})$/;
  return (ExpReg.test(string));
}

function validarFecha( fecha ){

	if ( !isDate(fecha) )
		return false;
	
	fechaCompleta = fecha.split("/");
	
	dia = fechaCompleta[0];
	mes = fechaCompleta[1];
	anio = fechaCompleta[2];

	if (dia < 1 || dia > 31) {
		return false;
	}
	if (mes < 1 || mes > 12) { 
		return false;
	}
	if ((mes==4 || mes==6 || mes==9 || mes==11) && dia==31) {
		return false;
	}
	if (mes == 2) { // bisiesto
		var bisiesto = (anio % 4 == 0 && (anio % 100 != 0 || anio % 400 == 0));
		if (dia > 29 || (dia==29 && !bisiesto)) {
			return false;
		}
	}
	
	return true;
}

function justNumbers( e )
{
	var keynum = window.event ? window.event.keyCode : e.which;

	if ( keynum == 0 )
		return true;

	if ( keynum == 8 )
		return true;

	return /\d/.test(String.fromCharCode(keynum));
}

function justNumbersAndDots( e )
{
	var keynum = window.event ? window.event.keyCode : e.which;

	if ( keynum == 0 )
		return true;

	if ( keynum == 8 )
		return true;

	if ( keynum == 46 )
		return true;

	return /\d/.test(String.fromCharCode(keynum));
}

function justNumbersAndComa( e )
{
	var keynum = window.event ? window.event.keyCode : e.which;

	if ( keynum == 0 )
		return true;

	if ( keynum == 8 )
		return true;
	
	if ( keynum == 44 )
		return true;	

	return /\d/.test(String.fromCharCode(keynum));
}

function justNumbersAndComaAndMenos( e )
{
	var keynum = window.event ? window.event.keyCode : e.which;

	if ( keynum == 0 )
		return true;

	if ( keynum == 8 )
		return true;
	
	if ( keynum == 44 )
		return true;
	
	if ( keynum == 46 )
		return true;	

	return /\d/.test(String.fromCharCode(keynum));
}

function justNumbersAndStr(e)
{
	var keynum = window.event ? window.event.keyCode : e.which;

	if ( keynum == 0 )
		return true;
	// Si la tecla que se presiona el "Retroceso" o "Tab"
	if ( keynum == 8 || keynum == 9 )
	event.returnValue = true;

	return /^[a-zA-Z0-9]+$/.test(String.fromCharCode(keynum));

}

/*
 * Funci�n que valida que el valor ingresado sea un numero entero o enter
 */
function justDecimalAndEnter(e)
{
	var keynum = window.event ? window.event.keyCode : e.which;

	if ( keynum == 0 )
		return true;

	// Si la tecla que se presiona el "Retroceso" o "Tab"
	if (keynum == 8 || keynum == 9)
		event.returnValue = true;

	return /\d/.test(String.fromCharCode(keynum));
}

/*
 * Función que acepta los valors numéricos, punto o signo menos
 */
function justDecimalAndMenos( cadena, e )
{
	var keynum = window.event ? window.event.keyCode : e.which;
	var posPunto = cadena.indexOf('.');
	var posMenos = cadena.indexOf('-');

	if ( keynum == 0 )
		return true;

	if (posPunto != -1 && keynum == 46)
		return false;

	if (posMenos != -1 && keynum == 45)
		return false;

	if (keynum == 8 || keynum == 45 || keynum == 46)
		return true;

	return /\d/.test(String.fromCharCode(keynum));
}

/*
 * Funci�n que valida que el valor ingresado sea un numero decimal
 * decimal es la cantidad de decimales aceptados. Si no se especifica, decimal = 2.
 */
function justDecimal(cadena, e, decimal)
{
	var keynum = window.event ? window.event.keyCode : e.which;
	var posPunto = cadena.indexOf('.');
	var longitud = cadena.length;

	if ( keynum == 0 )
		return true;

	if (posPunto != -1 && keynum == 46)
		return false;

	if (keynum == 8 || keynum == 46)
		return true;

	return /\d/.test(String.fromCharCode(keynum));
}

/**
*	Limpia el contenedor de errores y lo llena con los nuevos errores
*
*	@param errores Array - Arreglo con los mensajes de error a mostrar.
*	@param selectorContenedor String - Selector CSS para obtener el contenedor de errores
*/
function mostrarErrores(errores, selectorContenedor){

	$contenedor= $(selectorContenedor);
	$contenedor.empty();

	$texto= $("<p />");
	$texto.text("Por favor corrija los siguientes errores:");
	$texto.appendTo($contenedor);

	$lista= $("<ul />");
	$lista.appendTo($contenedor);

	for(e in errores){

		$el= $("<li />");
		$el.text(errores[e]);
		$el.appendTo($lista);
	}

	$contenedor.css("display", "block");
}

/**
 * Limpia el contenedor de errores y lo oculta
 */
 function ocultarErrores( selectorContenedor ){

	$contenedor= $(selectorContenedor);
 	$contenedor.empty();
	$contenedor.css("display", "none");
 }

/**
 * Funci�n que se utiliza para validar la hora.
 * @return integer 1. Hora V�lida - 0. Hora no v�lida
 */
function validarHora( hora )
{
	var patron = /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;

    if ( patron.test( hora ) )
    {
    	return 1;

    } else
    {
    	return 0;
    }
}

function marcarFilaGrilla(selectorGrilla, $fila){

	$(selectorGrilla).find("tr.success").removeClass("success");
	$fila.addClass("success");
}

/*
 *  Función que se encarga de validar un año.
 *  @param integer anio Año a validar.
 *  @param integer menor Cantidad de años menos al año actual.
 *  @param integer mayor Cantidad de años mas al año actual.
*/
function valida_anio ( anio, menor, mayor )
{
    var anio_actual = new Date().getFullYear();

    if ( anio.length < 4 )
        return false;

    if ( parseInt( anio ) < parseInt( anio_actual - menor ) )
        return false;

    if ( parseInt( anio ) > parseInt( anio_actual + mayor ) )
        return false;

    return true;

}

/**
 *  Función que se utiliza para mostrar mensajes.
 */
function mostrarMensaje(mensaje, selectorContenedor){

	$contenedor= $(selectorContenedor);
	$contenedor.empty();

	$lista= $("<ul />");
	$lista.appendTo($contenedor);

	$men= $("<li />");
	$men.text(mensaje);
	$men.appendTo($lista);

	$contenedor.css("display", "block");

    window.setTimeout(function() { $(selectorContenedor).css( "display", "none" ); }, 5000 );
}

/*
* agrega ceros a la izquierda del numero si no se ha llegado al ancho establecido en width.
*
* @param number El numero al que se le deben agregar ceros.
* @param width Cantidad de caracteres que debe tener el numero nuevo.
*/
function pad( number, width )
{
  width -= number.toString().length;
  if ( width > 0 )
  {
    return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
  }
  return number + ""; // always return a string
}

/*
* Verifica que se ingrese una lista de numeros y coma 
*/
function listaNumericaConComa( e, lista, largoNumero )
{
	var keynum = window.event ? window.event.keyCode : e.which;
	var array = lista.split(',');
	var ultimaPos = 0;
	var ultimoNum = lista;
		
	if ( array.length > 1 ){
		ultimaPos = array.length - 1;
		ultimoNum = array[ultimaPos];
	}
	
	if ( ultimoNum.length <= largoNumero && ultimoNum.length > 0 ){
		
		if ( keynum == 44 ) 
			return true;
		else if ( ultimoNum.length == largoNumero )
			return false;
	}
		
	return /\d/.test(String.fromCharCode(keynum));
}

function borrarUltimaComa( lista, control ){
	
	if ( lista.slice(lista.length - 1) == ',' )
		lista = lista.substring(0, lista.length - 1);
		
	$( "#"+control ).val(lista);
}