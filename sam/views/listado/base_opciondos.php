<?php

	use yii\widgets\ActiveForm;
	use yii\widgets\MaskedInput;
	use yii\helpers\Html;
	use yii\jui\DatePicker;
	use yii\bootstrap\Modal;

	$validacionesJavascript	= '';
	$id						= 0;
	$modales				= [];


?>

<style>


</style>
<?php

/**
 * Funciones qie dibujan los campos
 */

 //function columna()

/**
*
* FUNCIONES QUE DIBUJAN LOS CAMPOS
*/



/**
* Genera una columna con el contenido dado. Opcionalmente se puede establecer el ancho y la cantidad de columnas que debe ocupar
*
* @param string $contenido - Contenido que debe contener la columna.
* @param float $ancho = null. Ancho que debe ocupar la columna. NULL si debe ser automatico.
* @param integer $colspan = 1 - Cantidad de columnas que debe ocupar.
*/
function columna( $contenido, $cant_columnas = 2 ){

	$ret	= "<div class='col-xs-$cant_columnas'>";
	$ret 	.= $contenido;
	$ret	.= "</div>";

	// $ret= '<td';
	// if($ancho != null) $ret .= ' width="' . $ancho .'px"';
	// $ret .= ' colspan=' . $colspan . '>';
	// $ret .= $contenido;
	// $ret .= '</td>';

	return $ret;
}

function opcion( $dato ){

	return columna( $dato, 2 );
}

function label( $dato ){

	return columna( "<label style='margin: 6px 0px 2px 0px'>$dato</label>", 1 );
}

/**
* Genera una rango de valores.
*
* @param ActiveForm $form - Formulario al cual se debe asociar los campos.
* @param Model $model - Modelo que contiene los campos 'desde' y 'hasta'.
* @param Array $datos - Debe contener las claves:
*		- desde: Atributo de $model que corresponde al inicio del rango.
*		- hasta: Atributo de $model que corresponde al final del rango.
*
* @return string - Checbox y campos a insertar en la vista.
*/
function rango( $form, $model, $datos, $id ){

	$ret		= [];
	$idCheck	= $id;

	$idDesde	= '#' . $datos['desde'];
	$idHasta	= '#' . $datos['hasta'];

	$targets	= "$idDesde, $idHasta";

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
	$vista .= label( 'Desde:' );
	$vista .= columna( $form->field($model, $datos['desde'])->textInput(['id' => $datos['desde'], 'disabled' => true, 'style' => 'width:95%;']), 2 );
	$vista .= label( 'Hasta:' );
	$vista .= columna( $form->field($model, $datos['hasta'])->textInput(['id' => $datos['hasta'], 'disabled' => true, 'style' => 'width:95%;']), 2);
	$vista .= "</div>";


	$validacion	= 'if($("#check' . $idCheck . '").is(":checked")){';
	$validacion .= 'if($("' . $idDesde . '").val() == "" || $("' . $idHasta . '").val() == "") error.push("Complete los rango de ' . $datos['label'] . '.");';
	$validacion .= ' else ';
	$validacion .= 'if( parseFloat( $("' . $idHasta . '").val() ) < parseFloat( $("'. $idDesde . '").val() )) error.push("Rango de ' . $datos['label'] . ' mal ingresado.");';
	$validacion .= '}';

	$ret['vista']		= $vista;
	$ret['validacion']	= $validacion;

	return $ret;
}

/**
* Genera una rango de valores.
*
* @param ActiveForm $form - Formulario al cual se debe asociar los campos.
* @param Model $model - Modelo que contiene los campos 'desde' y 'hasta'.
* @param Array $datos - Debe contener las claves:
*		- desde: Atributo de $model que corresponde al inicio del rango.
*		- hasta: Atributo de $model que corresponde al final del rango.
*		- tipoObjeto: Identifica el tipo de objeto.
*
* @return string - Checbox y campos a insertar en la vista.
*/
function rangoObjeto( $form, $model, $datos, $id ){

	$ret		= [];
	$idCheck	= $id;

	$idDesde	= '#' . $datos['desde'];
	$idHasta	= '#' . $datos['hasta'];

	$targets	= "$idDesde, $idHasta";

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
	$vista .= label( 'Desde:' );
	$vista .= columna( $form->field($model, $datos['desde'])->textInput(['id' => $datos['desde'], 'disabled' => true, 'style' => 'width:95%;']), 2 );
	$vista .= label( 'Hasta:' );
	$vista .= columna( $form->field($model, $datos['hasta'])->textInput(['id' => $datos['hasta'], 'disabled' => true, 'style' => 'width:95%;']), 2);
	$vista .= "</div>";


	$validacion	= 'if($("#check' . $idCheck . '").is(":checked")){';
	$validacion .= 'if($("' . $idDesde . '").val() == "" || $("' . $idHasta . '").val() == "") error.push("Complete los rango de ' . $datos['label'] . '.");';
	$validacion .= ' else ';
	$validacion .= 'if( parseFloat( $("' . $idHasta . '").val() ) < parseFloat( $("'. $idDesde . '").val() )) error.push("Rango de ' . $datos['label'] . ' mal ingresado.");';
	$validacion .= '}';

	$ret['vista']		= $vista;
	$ret['validacion']	= $validacion;

	return $ret;
}

/**
* Genera un checkbox que se encarga de habilitar y deshabilitar los campos pasados en $target.
* Si $target es una cadena vacia, no se habilita o deshabilita ningun campo.
*
* @param string $label - Label a colocar al checkbox.
* @param string $idCheck - Id para el checkbox.
* @param string $target = '' - Selectores, separados por coma, para los cuales se crea el checkbox.
*/
function check($label, $idCheck, $target= '' ){

	return Html::checkBox( null, false, [
		'label' => $label,
		'class' => 'check',
		'data-target' => $target,
		'id' => "check" . $idCheck,
	]);
}

/**
* Genera un checkbox que se encarga de habilitar y deshabilitar los campos pasados en $target.
* Si $target es una cadena vacia, no se habilita o deshabilita ningun campo.
*
* @param string $label - Label a colocar al checkbox.
* @param string $idCheck - Id para el checkbox.
* @param string $target = '' - Selectores, separados por coma, para los cuales se crea el checkbox.
*/
function checkOpcion($form, $model, $datos, $id){

	$vista= columna($form->field($model, $datos['atributo'])->checkbox(['id' => $datos['atributo'],]));

	$ret['vista']= $vista;
	$ret['validacion']= '';

	return $ret;

}

/**
 * Genera la búsqueda por objeto
 *
 *
 */
 function objeto( $form, $model, $datos, $id ){

 // 	$ret		= [];
 // 	$idCheck	= $id;
	//
	// $idTObj	= '#' . $datos['tobj'];
 // 	$idDesde= '#' . $datos['desde'];
 // 	$idHasta= '#' . $datos['hasta'];
	//
 // 	$targets= "$idDesde, $idHasta";
	//
 // 	$vista= columna(check($datos['label'], $idCheck, $targets));
 // 	$vista .= columna('', 10);
 // 	$vista .= columna('Desde');
 // 	$vista .= columna('', 5);
 // 	$vista .= columna($form->field($model, $datos['desde'])->textInput(['id' => $datos['desde'], 'disabled' => true, 'style' => 'width:80px;']));
 // 	$vista .= columna('', 10);
 // 	$vista .= columna('Hasta');
 // 	$vista .= columna('', 5);
 // 	$vista .= columna($form->field($model, $datos['hasta'])->textInput(['id' => $datos['hasta'], 'disabled' => true, 'style' => 'width:80px;']));
	//
 // 	$validacion= 'if($("#check' . $idCheck . '").is(":checked")){';
 // 	$validacion .= 'if($("' . $idDesde . '").val() == "" || $("' . $idHasta . '").val() == "") error.push("Complete los rango de ' . $datos['label'] . '.");';
 // 	$validacion .= ' else ';
 // 	$validacion .= 'if( parseFloat( $("' . $idHasta . '").val() ) < parseFloat( $("'. $idDesde . '").val() )) error.push("Rango de ' . $datos['label'] . ' mal ingresado.");';
 // 	$validacion .= '}';
	//
 // 	$ret['vista']= $vista;
 // 	$ret['validacion']= $validacion;
	//
 // 	return $ret;
 }



function numero($form, $model, $datos, $id){

	$ret= [];

	$idCampo= '#' . $datos['atributo'];

	$vista= columna(check($datos['label'], $id, $idCampo));
	$vista .= columna('', 10);
	$vista .= columna(
		$form->field($model, $datos['atributo'])->textInput([
			'id' => $datos['atributo'],
			'disabled' => true,
			'maxlength' => $datos['longitud'],
			'onkeypress'	=> 'return justNumbers( event )',
			'style'	=> "width: " . $datos['longitud']* 20 . "px",
		]),
		null,
		7
	);

	$validacion= 'if($("#check' . $id . '").is(":checked") && $("' . $idCampo . '").val() == ""){';
	$validacion .= ' error.push("Ingrese un valor para ' . $datos['label'] . '.");';
	$validacion .= '}';

	$ret['vista']= $vista;
	$ret['validacion']= $validacion;

	return $ret;
}

function texto($form, $model, $datos, $id){

	$ret= [];

	$idCampo= '#' . $datos['atributo'];

	$vista= columna(check($datos['label'], $id, $idCampo));
	$vista .= columna('', 10);
	$vista .= columna($form->field($model, $datos['atributo'])->textInput(['id' => $datos['atributo'], 'disabled' => true, 'maxlength' => $datos['longitud'], 'style' => 'width:99%;']), null, 7);

	$validacion= 'if($("#check' . $id . '").is(":checked") && $("' . $idCampo . '").val() == ""){';
	$validacion .= ' error.push("Ingrese un valor para ' . $datos['label'] . '.");';
	$validacion .= '}';

	$ret['vista']= $vista;
	$ret['validacion']= $validacion;

	return $ret;
}

function mascara($model, $datos, $id){

	$ret= [];
	$idCampo= '#' . $datos['atributo'];

	$vista= columna(check($datos['label'], $id, $idCampo));
	$vista .= columna('', 10);
	$vista .= columna(
				MaskedInput::widget(['model' => $model, 'attribute' => $datos['atributo'], 'mask' => $datos['mascara'], 'options' => ['id' => $datos['atributo'], 'disabled' => true, 'class' => 'form-control', 'style' => 'width:99%;']]),
				null,
				7);

	$validacion= 'if($("#check' . $id .'").is(":checked") && $("' . $idCampo .'").val() == ""){';
	$validacion .= ' error.push("Ingrese un valor para ' . $datos['label'] . '.");';
	$validacion .= '}';

	$ret['vista']= $vista;
	$ret['validacion']= $validacion;

	return $ret;
}

function lista($form, $model, $datos, $id){

	$ret=[];
	$idCampo= '#' . $datos['atributo'];

	$vista= columna(check($datos['label'], $id, $idCampo));
	$vista .= columna('', 10);
	$vista .= columna($form->field($model, $datos['atributo'])->dropDownList($datos['elementos'], ['id' => $datos['atributo'], 'prompt' => '', 'disabled' => true, 'style' => 'width:99%;']), null, 7);

	$validacion= 'if($("#check' . $id . '").is(":checked")){';
	$validacion .= 'if($("' . $idCampo . ' option:selected").text() == "") error.push("Seleccione un elemento de la lista ' . $datos['label'] . '.");';
	$validacion .= '}';

	$ret['vista']= $vista;
	$ret['validacion']= $validacion;
	return $ret;
}

function listaConCampo($form, $model, $datos, $id){

	$ret=[];
	$idLista= '#' . $datos['atributoLista'];
	$idCampo= '#' . $datos['atributoCampo'];

	$targets= $idLista . ', ' . $idCampo;

	$vista= columna(check($datos['label'], $id, $targets));
	$vista .= columna('', 10);

	$vista .= columna($datos['labelLista']);
	$vista .= columna($form->field($model, $datos['atributoLista'])->dropDownList($datos['elementosLista'], ['id' => $datos['atributoLista'], 'prompt' => '', 'disabled' => true, 'style' => 'width:99%;']), null, 7);

	$vista .= columna('', 10);

	$vista .= columna($datos['labelCampo']);
	$vista .= columna('', 5);
	$vista .= columna($form->field($model, $datos['atributoCampo'])->textInput(['id' => $datos['atributoCampo'], 'maxlength' => 20, 'disabled' => true]));

	$validacion= 'if($("#check' . $id . '").is(":checked")){';
	$validacion .= 'if($("' . $idLista . ' option:selected").text() == "") error.push("Seleccione un elemento de la lista ' . $datos['label'] . '.");';
	$validacion .= 'if($("' . $idCampo . ' ").val() == "") error.push("Ingrese un valor para ' . $datos['labelCampo'] . '.");';
	$validacion .= '}';

	$ret['vista']= $vista;
	$ret['validacion']= $validacion;

	return $ret;

}

function rangoFecha($model, $datos, $id){

	$ret= [];

	$idDesde= '#' . $datos['desde'];
	$idHasta= '#' . $datos['hasta'];
	$targets= $idDesde . ', ' . $idHasta;

	$vista= columna(check($datos['label'], $id, $targets));
	$vista .= columna('', 10);
	$vista .= columna('Desde');
	$vista .= columna('', 5);
	$vista .= columna(DatePicker::widget(['model' => $model, 'attribute' => $datos['desde'], 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'id' => $datos['desde'], 'disabled' => true, 'style' => 'width:80px;']]));
	$vista .= columna('', 10);
	$vista .= columna('Hasta');
	$vista .= columna('', 5);
	$vista .= columna(DatePicker::widget(['model' => $model, 'attribute' => $datos['hasta'], 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'id' => $datos['hasta'], 'disabled' => true, 'style' => 'width:80px;']]));

	$validacion= 'if($("#check' . $id . '").is(":checked")){';
	$validacion .= 'if($("' . $idDesde . '").val() == "" || $("' . $idHasta . '").val() == "") error.push("Complete el rango de ' . $datos['label'] . '.");';
	$validacion .= ' else if(ValidarRangoFechaJs($("' . $idDesde . '").val(), $("' . $idHasta . '").val()) == 1) error.push("Rango de ' . $datos['label'] . ' incorrecto.");';
	$validacion .= '}';

	$ret['vista']= $vista;
	$ret['validacion']= $validacion;
	return $ret;
}

function datosCheck($form, $model, $datos, $id){

	$ret= [];

	$vista= columna($form->field($model, $datos['atributo'])->checkBox(['id' => $id,  'class' => 'check', 'uncheck' => 0 ]));

	$ret['vista']= $vista;
	$ret['validacion']= '';

	return $ret;
}

//TODO
function periodo($form, $model, $datos){

	$ret= [];

	$idADesde= '#' . $datos['adesde'];
	$idAHasta= '#' . $datos['ahasta'];
	$idCDesde= '#' . $datos['cdesde'];
	$idCHasta= '#' . $datos['chasta'];

	$targets= "$idAdesde, $idAHasta, $idCDesde, $idCHasta";

	$vista= columna(check($datos['label'], $targets));
	$vista .= columna('', 10);
	$vista .= columna('Desde');
	$vista .= columna('', 5);
	$vista .= columna($form->field($model, $datos['adesde'])->textInput(['id' => $datos['adesde'], 'maxlength' => 4, 'disabled' => true]));
	$vista .= columna($form->field($model, $datos['cdesde'])->textInput(['id' => $datos['cdesde'], 'maxlength' => 3, 'disabled' => true]));
	$vista .= columna('', 10);
	$vista .= columna('Hasta');
	$vista .= columna('', 5);
	$vista .= columna($form->field($model, $datos['ahasta'])->textInput(['id' => $datos['ahasta'], 'maxlength' => 4, 'disabled' => true]));
	$vista .= columna($form->field($model, $datos['chasta'])->textInput(['id' => $datos['chasta'], 'maxlength' => 3, 'disabled' => true]));

	$validacion= '';

	$ret['vista']= $vista;
	$ret['validacion']= $validacion;
	return $ret;
}

//TODO
function busquedaAvanzada($form, $model, $datos){

	$idCodigo= '#' . $datos['codigo'];
	$idNombre= '#' . $datos['nombre'];

	$targets= "$idCodigo, $idNombre";

	$ret = columna(check($datos['label'], $targets));

	return $ret;
}

function busquedaTablaAuxiliar($form, $model, $datos, $id, $contexto){

	$ret= [];

	$idCodigo= $datos['codigo'];
	$idBoton= 'botonBusquedaTablaAuxiliar' . $datos['codigo'];
	$idNombre= 'nombreBusquedaTablaAuxiliar' . $datos['codigo'];
	$idModal= 'modal' . $datos['codigo'];

	$targets= '#' . $idCodigo . ', #' . $idBoton;

	$onClickBoton= 'mostrarBusquedaReducida()';

	$campos = $form->field($model, $datos['codigo'], ['options' => ['style' => 'display:inline-block']])->textInput(['id' => $idCodigo, 'disabled' => true, 'style' => 'width:50px;']);
	$campos .= Html::button('<span class="glyphicon glyphicon-search"></span>', ['class' => 'bt-buscar', 'id' => $idBoton, 'data-toggle' => 'modal', 'data-target' => '#' . $idModal, 'disabled' => true]);
	$campos .= $form->field($model, $datos['nombre'], ['options' => ['style' => 'display:inline-block']])->textInput(['id' => $idNombre, 'class' => 'form-control solo-lectura', 'style' => 'width:200px', 'tabindex' => -1]);

	$vista= columna(check($datos['label'], $id, $targets));
	$vista .= columna('', 10);
	$vista .= columna($campos, 300, 9);


	$ret['vista']= $vista;
	$ret['validacion']= '';

	$busqueda= $datos['busqueda'];
	$busqueda['condicion']= ($busqueda['condicion'] != null && $busqueda['condicion'] != '') ? $busqueda['condicion'] : null;

	$ret['modal']= agregarBusquedaTablaAuxiliar(
												$datos['label'],
												$idModal,
												$busqueda['tabla'],
												$busqueda['campoCodigo'],
												$busqueda['campoNombre'],
												$busqueda['condicion'],
												$idCodigo,
												$idNombre,
												$idBoton,
												$contexto
												);

	return $ret;
}


function agregarBusquedaTablaAuxiliar($titulo, $idModal, $tabla, $campoCodigo, $campoNombre, $condicion, $idCampoCodigo, $idCampoNombre, $idBoton, $contexto){


	return [

		'id' => $idModal,
		'header' => '<h2>Búsqueda de ' . $titulo . '</h2>',
		'closeButton' => ['label' => '&times;', 'class' => 'btn btn-danger btn-sm pull-right'],
		'vista' => $contexto->render('//taux/auxbusca', [
										'tabla' => $tabla,
										'campocod' => $campoCodigo,
										'camponom' => $campoNombre,
										'boton_id' => $idBoton,
										'idcampocod' => $idCampoCodigo,
										'idcamponombre' => $idCampoNombre,
										'criterio' => $condicion
										])

	];
}
?>

<?php
foreach($breadcrumbs as $dato)
	$this->params['breadcrumbs'][]= $dato;
?>

<h1>Listado: Opciones</h1>

<?php

	$form = ActiveForm::begin([
		'fieldConfig' 		=> ['template' => '{input}'],
		'validateOnSubmit' 	=> false,
		'id' 				=> 'formListado',
	]);
?>

<div class="form" style="padding:5px;">

	<table border="0">
	<?php

	foreach($campos as $datos){

		echo '<tr>';

		$tipo= $datos['tipo'];
		$a= null;

		switch( $tipo ){
			case 'rango': 		$a= rango($form, $model, $datos, $id++); break;
			case 'rangoObjeto': $a= rangoObjeto($form, $model, $datos, $id++); break;
			case 'numero': $a= numero($form, $model, $datos, $id++); break;
			case 'texto': $a= texto($form, $model, $datos, $id++); break;
			case 'mascara': $a= mascara($model, $datos, $id++); break;
			case 'lista': $a= lista($form, $model, $datos, $id++); break;
			case 'listaConCampo': $a= listaConCampo($form, $model, $datos, $id++); break;
			case 'rangoFecha': $a= rangoFecha($model, $datos, $id++); break;
			case 'check': $a= datosCheck($form, $model, $datos, $id++); break;
			case 'periodo': $a= periodo($form, $model, $datos, $id++); break;
			case 'avanzada': $a= busquedaAvanzada($form, $model, $datos, $id++); break;
			case 'tablaAuxiliar': $a= busquedaTablaAuxiliar($form, $model, $datos, $id++, $this);
								  array_push($modales, $a['modal']);
								  break;
		}

		if($a != null){
			echo $a['vista'];
			$validacionesJavascript .= $a['validacion'];
		}

		echo '</tr>';
	}
	?>
	</table>
</div>

<div style="margin-top:5px;">
	<button type="submit" class="btn btn-success">Aceptar</button>
</div>

<?php
ActiveForm::end();
?>

<div style="margin-top:5px;">
<?= $form->errorSummary($model, ['id' => 'contenedorErrores']); ?>
</div>



<div id="contenedorModales">

	<?php
	foreach ($modales as $modal){

		Modal::begin([
			'id' => $modal['id'],
			'header' => $modal['header'],
			'closeButton' => $modal['closeButton']
			]);

		echo $modal['vista'];

		Modal::end();
	}
	?>
</div>

<script type="text/javascript">
function validarFormulario(){

	var error= new Array();
	var contenedorErrores= $("#contenedorErrores");

	//se comprueba que se haya checkeado algun campo
	var $checks= $( "#formListado .check:checked" );

	if( $checks.length == 0 ){
		error.push("No se encontraron criterios de búsqueda");
		mostrarErrores(error, contenedorErrores);
		return false;
	}


	<?= $validacionesJavascript; ?>

	if(error.length == 0)
		return true;
	else {

		mostrarErrores(error, contenedorErrores);
		return false;
	}
}



$(document).ready(function(){

	$(".check").click(function(){

		var targets= $(this).data("target").split(",");
		var habilitar= $(this).is(":checked");

		for(var i in targets)
			$(targets[i].trim()).prop("disabled", !habilitar);

	});

	$("#formListado").submit(validarFormulario);
});
</script>
