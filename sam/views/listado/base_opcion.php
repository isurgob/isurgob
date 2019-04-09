<?php

	use yii\widgets\ActiveForm;
	use yii\widgets\MaskedInput;
	use yii\helpers\Html;
	use yii\jui\DatePicker;
	use yii\bootstrap\Modal;
	use yii\helpers\BaseUrl;
	use yii\bootstrap\Alert;

	$validacionesJavascript	= '';
	$id						= 0;
	$modales				= [];


?>

<style>

.form-group {
    margin-bottom: 0px;
}

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
 * Genera una columna con el contenido dado. Opcionalmente se puede establecer la cantidad de columnas que debe ocupar.
 *
 * @param string $contenido - Contenido que debe contener la columna.
 * @param integer $cant_columnas = 2 - Cantidad de columnas que debe ocupar.
 */
 function columna( $contenido, $cant_columnas = 2 ){

	$ret	= "<div class='col-xs-$cant_columnas'>";
	$ret 	.= $contenido;
	$ret	.= "</div>";

	return $ret;
 }

 /**
  * Genera una columna con el contenido dado. Opcionalmente se puede establecer el ancho y la cantidad de columnas que debe ocupar
  *
  * @param string $contenido - Contenido que debe contener la columna.
  * @param float $ancho = null. Ancho que debe ocupar la columna. NULL si debe ser automatico.
  * @param integer $colspan = 1 - Cantidad de columnas que debe ocupar.
  */
  function columnaConLabel( $label, $contenido, $cant_columnas = 2 ){

 	$ret	= "<div class='col-xs-$cant_columnas'>";
 	$ret 	.= "<label>$label</label>" . $contenido;
 	$ret	.= "</div>";

 	return $ret;
  }

/**
 * Función que se utiliza para generar una opción de búsqueda.
 */
function opcion( $dato, $cant_columnas = 2 ){

	return columna( $dato, $cant_columnas );
}

/**
 * Función que se utiliza para mostrar el nombre de una opción de búsqueda.
 */
function label( $dato, $cant_columnas = 1 ){

	return columna( "<label style='margin: 6px 0px 2px 0px'>$dato</label>", $cant_columnas );
}

/**
* Genera un checkbox que se encarga de habilitar y deshabilitar los campos pasados en $target.
* Si $target es una cadena vacia, no se habilita o deshabilita ningun campo.
*
* @param string $label - Label a colocar al checkbox.
* @param string $idCheck - Id para el checkbox.
* @param string $target = '' - Selectores, separados por coma, para los cuales se crea el checkbox.
*/
function check( $label, $idCheck, $target= '' ){

	return Html::checkBox( null, false, [
		'label' => $label,
		'class' => 'check',
		'data-target' => $target,
		'id' => "check" . $idCheck,
	]);
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
 *		- label: Nombre de la opción de búsqueda.
 *
 * @return string - Checbox y campos a insertar en la vista.
 */
 function rango( $form, $model, $datos, $id ){

	$ret		= [];
	$idCheck	= $id;

	$idDesde	= '#' . $datos['desde'];
	$idHasta	= '#' . $datos['hasta'];

	if( !isset( $datos['caracteres'] ) ){
		$datos['caracteres'] = 4;
	}

	$targets	= "$idDesde, $idHasta";

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
	$vista .= label( 'Desde:' );
	$vista .= columna( $form->field($model, $datos['desde'])->textInput(['id' => $datos['desde'], 'disabled' => true, 'style' => 'width:98%;', 'maxlength' => $datos['caracteres'] ]), 2 );
	$vista .= label( 'Hasta:' );
	$vista .= columna( $form->field($model, $datos['hasta'])->textInput(['id' => $datos['hasta'], 'disabled' => true, 'style' => 'width:98%;', 'maxlength' => $datos['caracteres'] ]), 2);
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
 * Genera una rango de valores enteros.
 *
 * @param ActiveForm $form - Formulario al cual se debe asociar los campos.
 * @param Model $model - Modelo que contiene los campos 'desde' y 'hasta'.
 * @param Array $datos - Debe contener las claves:
 *		- desde: Atributo de $model que corresponde al inicio del rango.
 *		- hasta: Atributo de $model que corresponde al final del rango.
 *		- label: Nombre de la opción de búsqueda.
 *
 * @return string - Checbox y campos a insertar en la vista.
 */
 function rangoNumero( $form, $model, $datos, $id ){

	$ret		= [];
	$idCheck	= $id;

	$idDesde	= '#' . $datos['desde'];
	$idHasta	= '#' . $datos['hasta'];

	$targets	= "$idDesde, $idHasta";

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ), isset($datos['col_label']) ? $datos['col_label'] : 2 );
	$vista .= label( 'Desde:' );
	$vista .= columna( $form->field($model, $datos['desde'])->textInput(['id' => $datos['desde'], 'disabled' => true, 'style' => 'width:98%;', 'onkeypress' => 'return justDecimal( $( this ).val(), event )']), 2 );
	$vista .= label( 'Hasta:' );
	$vista .= columna( $form->field($model, $datos['hasta'])->textInput(['id' => $datos['hasta'], 'disabled' => true, 'style' => 'width:98%;', 'onkeypress' => 'return justDecimal( $( this ).val(), event )']), 2 );
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
*		- label: Nombre de la opción de búsqueda.
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
	$vista .= columna( $form->field($model, $datos['desde'])->textInput(['id' => $datos['desde'], 'disabled' => true, 'style' => 'width:98%;']), 2 );
	$vista .= label( 'Hasta:' );
	$vista .= columna( $form->field($model, $datos['hasta'])->textInput(['id' => $datos['hasta'], 'disabled' => true, 'style' => 'width:98%;']), 2);
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
*		- label: Nombre de la opción de búsqueda.
*
* @return string - Checbox y campos a insertar en la vista.
*/
function rangoYLista( $form, $model, $datos, $id ){

	$ret		= [];
	$idCheck	= $id;

	$idDesde	= '#' . $datos['desde'];
	$idHasta	= '#' . $datos['hasta'];
	$idCampo	= '#' . $datos['atributoLista'];

	$targets	= "$idCampo , $idDesde , $idHasta";

	/**
	 * Verificar que se hayan pasado la cantidad de columnas para la lista.
	 */
	if( !isset( $datos['listaColumnas'] ) ){
		$datos['listaColumnas'] = 2;
	}

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ), isset($datos['col_label']) ? $datos['col_label'] : 2 );
	$vista .= label( 'Desde:' );
	$vista .= columna( $form->field($model, $datos['desde'])->textInput(['id' => $datos['desde'], 'disabled' => true, 'style' => 'width:98%;']), 2 );
	$vista .= label( 'Hasta:' );
	$vista .= columna( $form->field($model, $datos['hasta'])->textInput(['id' => $datos['hasta'], 'disabled' => true, 'style' => 'width:98%;']), 2 );

	$vista .= label( $datos['listaLabel'] );
	$vista .= columna( $form->field($model, $datos['atributoLista'])->dropDownList( $datos['elementos'], ['id' => $datos['atributoLista'], 'disabled' => true, 'style' => 'width:99%;']), $datos['listaColumnas'] );

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
* Genera una rango de fechas.
*
* @param ActiveForm $form - Formulario al cual se debe asociar los campos.
* @param Model $model - Modelo que contiene los campos 'desde' y 'hasta'.
* @param Array $datos - Debe contener las claves:
*		- desde: Atributo de $model que corresponde al inicio del rango.
*		- hasta: Atributo de $model que corresponde al final del rango.
*		- tipoObjeto: Identifica el tipo de objeto.
*		- label: Nombre de la opción de búsqueda.
*
* @return string - Checbox y campos a insertar en la vista.
*/
function rangoFecha( $form, $model, $datos, $id ){

	$ret		= [];
	$idCheck	= $id;

	$idDesde	= '#' . $datos['desde'];
	$idHasta	= '#' . $datos['hasta'];

	$targets	= "$idDesde, $idHasta";

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ), isset($datos['col_label']) ? $datos['col_label'] : 2 );
	$vista .= label( 'Desde:' );
	$vista .= columna(
		DatePicker::widget([
			'model' => $model,
			'attribute' => $datos['desde'],
			'dateFormat' => 'dd/MM/yyyy',
			'options' => [
				'class' => 'form-control text-center',
				'id' => $datos['desde'],
				'disabled' => true,
				'style' => 'width:98%;',
			],
		]),
		2
	);
	$vista .= label( 'Hasta:' );
	$vista .= columna(
		DatePicker::widget([
			'model' => $model,
			'attribute' => $datos['hasta'],
			'dateFormat' => 'dd/MM/yyyy',
			'options' => [
				'class' => 'form-control text-center',
				'id' => $datos['hasta'],
				'disabled' => true,
				'style' => 'width:98%;',
				]]),
			2
		);
	$vista .= "</div>";

	$validacion= 'if($("#check' . $idCheck . '").is(":checked")){';
	$validacion .= 'if($("' . $idDesde . '").val() == "" || $("' . $idHasta . '").val() == "") error.push("Complete el rango de ' . $datos['label'] . '.");';
	$validacion .= ' else if(ValidarRangoFechaJs($("' . $idDesde . '").val(), $("' . $idHasta . '").val()) == 1) error.push("Rango de ' . $datos['label'] . ' incorrecto.");';
	$validacion .= '}';

	$ret['vista']		= $vista;
	$ret['validacion']	= $validacion;

	return $ret;

}

/**
 * Genera una input para realizar la búsqueda de un objeto.
 *
 * @param ActiveForm $form - Formulario al cual se debe asociar los campos.
 * @param Model $model - Modelo que contiene el campo que se busca.
 * @param Array $datos - Debe contener las claves:
 *		- atributo: Atributo de $model que corresponde al objeto.
 *		- tipoObjeto: Identifica el tipo de objeto.
 *		- label: Nombre de la opción de búsqueda.
 *
 * @return string - Checbox y campos a insertar en la vista.
 */
 function busquedaObjeto( $form, $model, $datos, $id ){

	$ret		= [];
	$idCheck	= $id;

	$idCampo	= '#' . $datos['atributo'];

	$targets	= "$idCampo";

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
	$vista .= columna( $form->field($model, $datos['atributo'])->textinput(['id' => $datos['atributo'],'disabled' => true,'style' => 'width:99%;', 'maxlength' => 8, ]), 2 );
	$vista .= "</div>";

	$validacion= 'if($("#check' . $id . '").is(":checked")){';
 	$validacion .= 'if($("' . $idCampo . '").val() == "") error.push("Ingrese un ' . $datos['label'] . '.");';
 	$validacion .= '}';

	$ret['vista']		= $vista;
	$ret['validacion']	= $validacion;

	return $ret;
 }

/**
 * Genera una lista.
 *
 * @param ActiveForm $form - Formulario al cual se debe asociar los campos.
 * @param Model $model - Modelo que contiene el campo que se busca.
 * @param Array $datos - Debe contener las claves:
 *		- atributo: Atributo de $model que corresponde al listado.
 *		- elementos: Arreglo con las opciones a mostrar en el combo.
 *		- label: Nombre de la opción de búsqueda.
 *
 * @return string - Checbox y campos a insertar en la vista.
 */
 function dibujaLista( $form, $model, $datos, $id, $columnas = 6 ){

	$ret		= [];
	$idCheck	= $id;
	
	$sinCheck	= isset($datos['sinCheck']) ? $datos['sinCheck'] : 0;

	$idCampo	= '#' . $datos['atributo'];

	$targets	= "$idCampo";

	$vista	= "<div class='row'>";
	if ( $sinCheck == 0 ) // indica que se muestra con check 
		$vista .= opcion( check( $datos['label'], $idCheck, $targets ), isset($datos['col_label']) ? $datos['col_label'] : 2 );
	else // se muestra solo el label 
		$vista .= label( $datos['label'], 2 );
		
	$vista .= columna( $form->field($model, $datos['atributo'])->dropDownList( $datos['elementos'], ['id' => $datos['atributo'], 'disabled' => ( $sinCheck == 0 ? true : false), 'style' => 'width:99%;']), $columnas );
	$vista .= "</div>";

	$validacion= 'if($("#check' . $id . '").is(":checked")){';
 	$validacion .= 'if($("' . $idCampo . ' option:selected").text() == "") error.push("Seleccione un elemento de la lista ' . $datos['label'] . '.");';
 	$validacion .= '}';

	$ret['vista']		= $vista;
	$ret['validacion']	= $validacion;

	return $ret;

 }

 /**
  *
  */
  function campos( $form, $model, $datos, $id ){

 	$ret		= [];
 	$idCheck	= $id;

	$targets 	= '';

	for( $i = 1; $i <= $datos['cantidadCampos']; $i++ ){

		$targets    .= "#" . $datos["campo$i"] . ",";
	}

 	$vista	= "<div class='row'>";
 	$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );

	for( $i = 1; $i <= $datos['cantidadCampos']; $i++ ){

		$vista .= columnaConLabel( $datos["label$i"], $form->field($model, $datos['campo' . $i])->textinput(['id' => $datos['campo' . $i],'disabled' => true,'style' => 'width:99%;', 'maxlength' => 8, ]), 1 );

	}

 	$vista .= "</div>";


	$validacion = 'ingresaUno = 0;';
	$validacion .= 'if($("#check' . $idCheck . '").is(":checked")){';

	for( $i = 1; $i <= $datos['cantidadCampos']; $i++ ){

		$validacion .= 'if($("' . "#" . $datos['campo' . $i] . '").val() != "" ){ ingresaUno = 1; }';
	}

	$validacion .= 'if( ingresaUno == 0 ){ error.push("Debe ingresar al menos un campo en ' . $datos['label'] . '.");}';
	$validacion .= '}';

 	$ret['vista']		= $vista;
 	$ret['validacion']	= $validacion;

 	return $ret;
  }
  
  function listasYcamposVinculados( $form, $model, $datos, $id ){
	
	$ret		= [];
 	$idCheck	= $id;

	$targets 	= '';

	for( $i = 1; $i <= $datos['cantidadCampos']; $i++ ){

		$targets    .= "#" . $datos["campo$i"] . ",";
	}

 	$vista	= "<div class='row'>";
 	$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );

	for( $i = 1; $i <= $datos['cantidadCampos']; $i++ ){
		
		$columnas = isset($datos["columnas$i"]) ? $datos["columnas$i"] : 6;
		
		if ( isset($datos["elementos$i"]) )
			$vista .= columnaConLabel( $datos["label$i"], $form->field($model, $datos['campo' . $i])->dropDownList( $datos["elementos$i"], ['id' => $datos['campo' . $i], 'disabled' => true, 'style' => 'width:99%;']), $columnas );
		else 
			$vista .= columnaConLabel( $datos["label$i"], $form->field($model, $datos['campo' . $i])->textinput(['id' => $datos['campo' . $i],'disabled' => true,'style' => 'width:99%;', 'maxlength' => 8, ]), $columnas );

	}

 	$vista .= "</div>";


	$validacion = 'ingresaUno = 0;';
	$validacion .= 'if($("#check' . $idCheck . '").is(":checked")){';

	for( $i = 1; $i <= $datos['cantidadCampos']; $i++ ){

		$validacion .= 'if($("' . "#" . $datos['campo' . $i] . '").val() != "" ){ ingresaUno = 1; }';
	}

	$validacion .= 'if( ingresaUno == 0 ){ error.push("Debe ingresar al menos un campo en ' . $datos['label'] . '.");}';
	$validacion .= '}';

 	$ret['vista']		= $vista;
 	$ret['validacion']	= $validacion;

 	return $ret;
  }

  function periodo( $form, $model, $datos, $id ){

	$ret		= [];
   	$idCheck	= $id;

	$idADesde= '#' . $datos['adesde'];
	$idAHasta= '#' . $datos['ahasta'];
	$idCDesde= '#' . $datos['cdesde'];
	$idCHasta= '#' . $datos['chasta'];

	$targets= "$idADesde, $idAHasta, $idCDesde, $idCHasta";

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );

	$desde = '<div>';
	$desde .= Html::activeInput( 'text', $model, $datos['adesde'], [
		'id' 		=> $datos['adesde'],
		'class'		=> 'form-control text-center',
		'disabled'	=> true,
		'style' 	=> 'width:60%; display: inline',
		'maxlength' => 4,
	]);
	$desde .= Html::activeInput( 'text', $model, $datos['cdesde'], [
		'id' 		=> $datos['cdesde'],
		'class'		=> 'form-control text-center',
		'disabled'	=> true,
		'style' 	=> 'width:35%; display: inline',
		'maxlength' => 3,
	]);
	$desde .= '</div>';

	$hasta = '<div>';
	$hasta .= Html::activeInput( 'text', $model, $datos['ahasta'], [
		'id' 		=> $datos['ahasta'],
		'class'		=> 'form-control text-center',
		'disabled'	=> true,
		'style' 	=> 'width:60%; display: inline',
		'maxlength' => 4,
	]);
	$hasta .= Html::activeInput( 'text', $model, $datos['chasta'], [
		'id' 		=> $datos['chasta'],
		'class'		=> 'form-control text-center',
		'disabled'	=> true,
		'style' 	=> 'width:35%; display: inline',
		'maxlength' => 3,
	]);
	$hasta .= '</div>';

	$vista .= label( 'Desde:' );
	$vista .= columna( $desde, 2 );
	$vista .= label( 'Hasta:' );
	$vista .= columna( $hasta, 2 );

	$vista .= "</div>";

	$validacion = 'if($("#check' . $idCheck . '").is(":checked")){';
	$validacion .= 'if($("' . $idADesde . '").val() == "" || $("' . $idAHasta . '").val() == "" || $("' . $idCDesde . '").val() == "" || $("' . $idCHasta . '").val() == "" ){ error.push("Complete los rangos de ' . $datos['label'] . '.");';
	$validacion .= '} else {  ';
	$validacion .= 'if( $("' . $idADesde . '").val().length < 4 ) error.push( "Ingrese valor de año desde correcto.");';
	$validacion .= 'if( $("' . $idAHasta . '").val().length < 4 ) error.push( "Ingrese valor de año hasta correcto.");';
	$validacion .= 'if( parseInt( ( $("' . $idAHasta . '").val() * 1000 ) + $("' . $idCHasta . '").val() ) < parseInt( ( $("' . $idADesde . '").val() * 1000 ) + $("' . $idCDesde . '").val() ) ) error.push("Rango de ' . $datos['label'] . ' mal ingresado.");';
	$validacion .= '}';
	$validacion .= '}';

    $ret['vista']		= $vista;
    $ret['validacion']	= $validacion;

  	return $ret;
  }

  function periodoSimple( $form, $model, $datos, $id ){

	$ret		= [];
   	$idCheck	= $id;

	$idAnio 	= '#' . $datos['anio'];
	$idCuota	= '#' . $datos['cuota'];

	$targets= "$idAnio, $idCuota";

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
	$vista .= label( 'Año:' );
	$vista .= columna( $form->field($model, $datos['anio'])->textInput([ 'id' => $datos['anio'], 'disabled' => true, 'style' => 'width:40%;', 'maxlength' => 4 ]), 2 );
	$vista .= label( 'Cuota:' );
	$vista .= columna( $form->field($model, $datos['cuota'])->textInput([ 'id' => $datos['cuota'], 'disabled' => true, 'style' => 'width:99%;', 'maxlength' => 3 ]), 1 );
	$vista .= "</div>";

	$validacion = 'if($("#check' . $idCheck . '").is(":checked")){';
	$validacion .= 'if($("' . $idAnio . '").val() == "" || $("' . $idCuota . '").val() == "" ) error.push( "Complete los rangos de ' . $datos['label'] . '.");';
	$validacion .= '}';

    $ret['vista']		= $vista;
    $ret['validacion']	= $validacion;

  	return $ret;
  }


 /**
  * Genera una lista detamaño mediano.
  *
  * @param ActiveForm $form - Formulario al cual se debe asociar los campos.
  * @param Model $model - Modelo que contiene el campo que se busca.
  * @param Array $datos - Debe contener las claves:
  *		- atributo: Atributo de $model que corresponde al listado.
  *		- elementos: Arreglo con las opciones a mostrar en el combo.
  *		- label: Nombre de la opción de búsqueda.
  *
  * @return string - Checbox y campos a insertar en la vista.
  */
  function listachica( $form, $model, $datos, $id ){

	  return dibujaLista( $form, $model, $datos, $id, 3 );
  }

  /**
   * Genera una lista detamaño mediano.
   *
   * @param ActiveForm $form - Formulario al cual se debe asociar los campos.
   * @param Model $model - Modelo que contiene el campo que se busca.
   * @param Array $datos - Debe contener las claves:
   *		- atributo: Atributo de $model que corresponde al listado.
   *		- elementos: Arreglo con las opciones a mostrar en el combo.
   *		- label: Nombre de la opción de búsqueda.
   *
   * @return string - Checbox y campos a insertar en la vista.
   */
   function lista( $form, $model, $datos, $id ){

	 return dibujaLista( $form, $model, $datos, $id, 6 );
   }

  /**
   * Genera una lista detamaño mediano.
   *
   * @param ActiveForm $form - Formulario al cual se debe asociar los campos.
   * @param Model $model - Modelo que contiene el campo que se busca.
   * @param Array $datos - Debe contener las claves:
   *		- atributo: Atributo de $model que corresponde al listado.
   *		- elementos: Arreglo con las opciones a mostrar en el combo.
   *		- label: Nombre de la opción de búsqueda.
   *
   * @return string - Checbox y campos a insertar en la vista.
   */
   function listagrande( $form, $model, $datos, $id ){

 	  return dibujaLista( $form, $model, $datos, $id, 9 );
   }

   /**
	* Genera un input para realizar una búsqueda de texto.
	*
	* @param ActiveForm $form - Formulario al cual se debe asociar los campos.
	* @param Model $model - Modelo que contiene el campo que se busca.
	* @param Array $datos - Debe contener las claves:
	*		- atributo: Atributo de $model que corresponde al listado.
	*		- label: Nombre de la opción de búsqueda.
	*		- columnas: Indica en cuantas columnas se dibuja el input. 4 por defecto.
	*		- caracteres: Indica el máximo de caracteres a ingresar. 40 por defecto.
	*
	* @return string - Checbox y campos a insertar en la vista.
	*/
	function texto($form, $model, $datos, $id){

		$ret		= [];
		$idCheck	= $id;

		$idCampo	= '#' . $datos['atributo'];

		$targets	= "$idCampo";

		/**
		 * Verificar que se hayan pasado la cantidad de columnas y la cantidad de caracteres.
		 */
		if( !isset( $datos['columnas'] ) ){
			$datos['columnas'] = 4;
		}

		if( !isset( $datos['caracteres'] ) ){
			$datos['caracteres'] = 40;
		}

		$vista	= "<div class='row'>";
		$vista .= opcion( check( $datos['label'], $idCheck, $targets ), isset($datos['col_label']) ? $datos['col_label'] : 2 );

		$vista .= columna(
			$form->field( $model, $datos['atributo'] )->textInput([
				'id' => $datos['atributo'],
				'disabled' => true,
				'style' => 'width:99%;text-transform: uppercase',
				'maxlength' => $datos['caracteres']]
			),
			$datos['columnas']
		);

		$vista .= "</div>";

		$validacion= 'if($("#check' . $id . '").is(":checked")){';
	 	$validacion .= 'if($("' . $idCampo . '").val() == "") error.push("Ingrese ' . $datos['label'] . '");';
	 	$validacion .= '}';

		$ret['vista']		= $vista;
		$ret['validacion']	= $validacion;

		return $ret;

	}

	function numero($form, $model, $datos, $id){

		$ret		= [];
		$idCheck	= $id;

		$idCampo	= '#' . $datos['atributo'];

		$targets	= "$idCampo";

		/**
		 * Verificar que se hayan pasado la cantidad de columnas y la cantidad de caracteres.
		 */
		if( !isset( $datos['columnas'] ) ){
			$datos['columnas'] = 4;
		}

		if( !isset( $datos['caracteres'] ) ){
			$datos['caracteres'] = 40;
		}

		$vista	= "<div class='row'>";
		$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
		$vista .= columna(
			$form->field( $model, $datos['atributo'] )->textInput([
				'id' 			=> $datos['atributo'],
				'disabled'		=> true,
				'style' 		=> 'width:99%;',
				'maxlength' 	=> $datos['caracteres'],
				'onkeypress'	=> 'return justNumbers( event )',
			]),
			$datos['columnas']
		);
		$vista .= "</div>";

		$validacion= 'if($("#check' . $id . '").is(":checked")){';
	 	$validacion .= 'if($("' . $idCampo . '").val() == "") error.push("Ingrese ' . $datos['label'] . '");';
	 	$validacion .= '}';

		$ret['vista']		= $vista;
		$ret['validacion']	= $validacion;

		return $ret;

	}

	function decimal($form, $model, $datos, $id){

		$ret		= [];
		$idCheck	= $id;

		$idCampo	= '#' . $datos['atributo'];

		$targets	= "$idCampo";

		/**
		 * Verificar que se hayan pasado la cantidad de columnas y la cantidad de caracteres.
		 */
		if( !isset( $datos['columnas'] ) ){
			$datos['columnas'] = 4;
		}

		if( !isset( $datos['caracteres'] ) ){
			$datos['caracteres'] = 40;
		}

		$vista	= "<div class='row'>";
		$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
		$vista .= columna(
			$form->field( $model, $datos['atributo'] )->textInput([
				'id' 			=> $datos['atributo'],
				'disabled'		=> true,
				'style' 		=> 'width:99%;',
				'maxlength' 	=> $datos['caracteres'],
				'onkeypress'	=> 'return justDecimal( $( this ).val(), event )',
			]),
			$datos['columnas']
		);
		$vista .= "</div>";

		$validacion= 'if($("#check' . $id . '").is(":checked")){';
	 	$validacion .= 'if($("' . $idCampo . '").val() == "") error.push("Ingrese ' . $datos['label'] . '");';
	 	$validacion .= '}';

		$ret['vista']		= $vista;
		$ret['validacion']	= $validacion;

		return $ret;

	}

	/**
   * Genera un input para realizar una búsqueda de texto.
   *
   * @param ActiveForm $form - Formulario al cual se debe asociar los campos.
   * @param Model $model - Modelo que contiene el campo que se busca.
   * @param Array $datos - Debe contener las claves:
   *		- atributo: Atributo de $model que corresponde al listado.
   *		- label: Nombre de la opción de búsqueda.
   *		- columnas: Indica en cuantas columnas se dibuja el input. 4 por defecto.
   *		- caracteres: Indica el máximo de caracteres a ingresar. 40 por defecto.
   *
   * @return string - Checbox y campos a insertar en la vista.
   */
   function textoConRango( $form, $model, $datos, $id ){

	   $ret		= [];
	   $idCheck	= $id;

	   $idCampo	= '#' . $datos['atributo'];
	   $idDesde	= '#' . $datos['rangoDesde'];
	   $idHasta	= '#' . $datos['rangoHasta'];

	   $targets	= "$idCampo , $idDesde , $idHasta";

	   /**
		* Verificar que se hayan pasado la cantidad de columnas y la cantidad de caracteres.
		*/
	   if( !isset( $datos['columnas'] ) ){
		   $datos['columnas'] = 4;
	   }

	   if( !isset( $datos['caracteres'] ) ){
		   $datos['caracteres'] = 40;
	   }

	   if( !isset( $datos['rangoColumnas'] ) ){
		   $datos['rangoColumnas'] = 1;
	   }

	   $vista	= "<div class='row'>";
	   $vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
	   $vista .= columna( $form->field($model, $datos['atributo'])->textInput(['id' => $datos['atributo'], 'disabled' => true, 'style' => 'width:99%;', 'maxlength' => $datos['caracteres']]), $datos['columnas'] );

	   $vista .= label( $datos['rangoLabel'] );
	   $vista .= columna( $form->field($model, $datos['rangoDesde'])->textInput(['id' => $datos['rangoDesde'], 'disabled' => true, 'style' => 'width:98%;', 'onkeypress' => 'return justNumbers( event )']), $datos['rangoColumnas'] );
	   $vista .= columna( $form->field($model, $datos['rangoHasta'])->textInput(['id' => $datos['rangoHasta'], 'disabled' => true, 'style' => 'width:98%;', 'onkeypress' => 'return justNumbers( event )']), $datos['rangoColumnas'] );
	   $vista .= "</div>";

	   $validacion= 'if($("#check' . $id . '").is(":checked")){';
	   $validacion .= 'if($("' . $idCampo . '").val() == "") error.push("Ingrese ' . $datos['label'] . '");';
	   $validacion .= 'if($("' . $idDesde . '").val() == "" || $("' . $idHasta . '").val() == "") error.push("Complete los rango de ' . $datos['label'] . '.");';
	   $validacion .= ' else ';
	   $validacion .= 'if( parseFloat( $("' . $idHasta . '").val() ) < parseFloat( $("'. $idDesde . '").val() )) error.push("Rango de ' . $datos['label'] . ' mal ingresado.");';
	   $validacion .= '}';

	   $ret['vista']		= $vista;
	   $ret['validacion']	= $validacion;

	   return $ret;

   }

	function radio( $form, $model, $datos, $id ){
	
		$ret		= [];
 	   	$idCheck	= $id;
	
 	   	$idCampo	= '#' . $datos['atributo'];
	
 	   	$targets	= "$idCampo";
	
	
		/**
		 * Verificar que se hayan pasado la cantidad de columnas y la cantidad de caracteres.
		 */
		if( !isset( $datos['columnas'] ) ){
			$datos['columnas'] = 4;
		}
	
		$vista	= "<div class='row'>";
		$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
		//$vista .= columna( $form->field( $model, $datos['atributo'] )->radioList( $datos[ 'elementos' ], ['id' => $datos['atributo'], 'disabled' => true ]), $datos['rangoColumnas'] );
		$vista .= columna( $form->field( $model, $datos['atributo'] )->radioList( $datos[ 'elementos' ], [ 'itemOptions' => [ 'id' => $datos['atributo'], 'disabled' => true ], ]), $datos['columnas'] );
		$vista .= "</div>";
	
		$validacion = '';
		// $validacion= 'if($("#check' . $id . '").is(":checked")){';
		// 	$validacion .= 'if($("' . $idCampo . '").val() == "") error.push("Ingrese ' . $datos['label'] . '");';
		// 	$validacion .= '}';
	
		$ret['vista']		= $vista;
		$ret['validacion']	= $validacion;
	
		return $ret;
	}
	
	
function listaConCampo($form, $model, $datos, $id){

	$ret=[];
	$idLista= '#' . $datos['atributoLista'];
	$idCampo= '#' . $datos['atributoCampo'];

	$targets= $idLista . ', ' . $idCampo;
	
	$vista	= "<div class='row'>";
    $vista .= opcion( check( $datos['label'], $id, $targets ) );
    $vista .= label($datos['labelLista']);
	$vista .= columna($form->field($model, $datos['atributoLista'])->dropDownList($datos['elementosLista'], ['id' => $datos['atributoLista'], 'prompt' => '', 'disabled' => true]), 2);

    $vista .= label($datos['labelCampo']);
	$vista .= columna($form->field($model, $datos['atributoCampo'])->textInput(['id' => $datos['atributoCampo'], 'maxlength' => 20, 'disabled' => true]));
	$vista .= "</div>";
	
	$validacion= 'if($("#check' . $id . '").is(":checked")){';
	$validacion .= 'if($("' . $idLista . ' option:selected").text() == "") error.push("Seleccione un elemento de la lista ' . $datos['label'] . '.");';
	$validacion .= 'if($("' . $idCampo . ' ").val() == "") error.push("Ingrese un valor para ' . $datos['labelCampo'] . '.");';
	$validacion .= '}';

	$ret['vista']= $vista;
	$ret['validacion']= $validacion;

	return $ret;

}

	function mascara( $model, $datos, $id ){

		$ret		= [];
 	   	$idCheck	= $id;

 	   	$idCampo	= '#' . $datos['atributo'];

 	   	$targets	= "$idCampo";


		/**
		 * Verificar que se hayan pasado la cantidad de columnas y la cantidad de caracteres.
		 */
		if( !isset( $datos['columnas'] ) ){
			$datos['columnas'] = 4;
		}

		$vista	= "<div class='row'>";
		$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
		$vista .= columna( MaskedInput::widget(['model' => $model, 'attribute' => $datos['atributo'], 'mask' => $datos['mascara'], 'options' => ['id' => $datos['atributo'], 'disabled' => true, 'class' => 'form-control', 'style' => 'width:99%;']]), $datos['columnas'] );
		$vista .= "</div>";

		$validacion= 'if($("#check' . $id . '").is(":checked")){';
	 	$validacion .= 'if($("' . $idCampo . '").val() == "") error.push("Ingrese ' . $datos['label'] . '");';
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
function checkOpcion($form, $model, $datos, $id){

	/**
	 * Verificar que se hayan pasado la cantidad de columnas y la cantidad de caracteres.
	 */
	if( !isset( $datos['columnas'] ) ){
		$datos['columnas'] = 2;
	}
	
	$vista	= "<div class='row'>";
	$vista .= columna( $form->field($model, $datos['atributo'])->checkbox([ 'id' => $datos['atributo'], 'class' => 'check' ]), $datos['columnas'] );
	$vista .= "</div>";

	$ret['vista']= $vista;
	$ret['validacion']= '';

	return $ret;

}

function busquedaTablaAuxiliar($form, $model, $datos, $id, $contexto){

	$ret		= [];
	$idCheck	= $id;

	$idCampo	= '#' . $datos['codigo'];

	$idCodigo	= $datos['codigo'];
	$idBoton	= 'botonBusquedaTablaAuxiliar' . $datos['codigo'];
	$idNombre	= 'nombreBusquedaTablaAuxiliar' . $datos['codigo'];
	$idModal	= 'modal' . $datos['codigo'];

	$targets	= '#' . $idCodigo . ', #' . $idBoton;

	$onClickBoton= 'mostrarBusquedaReducida()';


	$campos = $form->field( $model, $datos['codigo'], ['options' => ['style' => 'display:inline-block']])->textInput([
		'id' 		=> $idCodigo,
		'class' 	=> 'form-control text-center',
		'disabled' 	=> true,
		'style' 	=> 'width:80px;',
		'tabIndex'	=> -1,
		'onchange' 	=> 'btCodigoBuscarAuxClick("' . $datos['busqueda']['tabla'] . '","' . $datos['busqueda']['campoCodigo'] . '="+$(this).val(),"' . $idNombre . '","' . $datos['busqueda']['campoNombre'] . '")'
	]);

	$campos .= Html::button('<span class="glyphicon glyphicon-search"></span>', ['class' => 'bt-buscar', 'id' => $idBoton, 'data-toggle' => 'modal', 'data-target' => '#' . $idModal, 'disabled' => true]);

	$campos .= $form->field($model, $datos['nombre'], ['options' => ['style' => 'display:inline-block']])->textInput([
		'id' 		=> $idNombre,
		'class' 	=> 'form-control solo-lectura',
		'style' 	=> 'width:390px',
		'tabindex' => -1,
	]);

	$vista	= "<div class='row'>";
	$vista .= opcion( check( $datos['label'], $idCheck, $targets ) );
	$vista .= columna( $campos, 8 );
	$vista .= "</div>";

	$validacion= 'if($("#check' . $id . '").is(":checked")){';
	$validacion .= 'if($("' . $idCampo . '").val() == "") error.push("Ingrese ' . $datos['label'] . '");';
	$validacion .= '}';

	$ret['vista']= $vista;
	$ret['validacion']= $validacion;

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
												$contexto,
												isset($busqueda['camposextra']) ? $busqueda['camposextra'] : ''
												);

	return $ret;
}


function agregarBusquedaTablaAuxiliar($titulo, $idModal, $tabla, $campoCodigo, $campoNombre, $condicion, $idCampoCodigo, $idCampoNombre, $idBoton, $contexto, $camposextra){


	return [

		'id' => $idModal,
		'header' => '<h2>Búsqueda de ' . $titulo . '</h2>',
		'closeButton' => ['label' => '&times;', 'class' => 'btn btn-danger btn-sm pull-right'],
		'vista' => $contexto->render('//taux/auxbusca', [
										'idAux' => $idCampoCodigo,
										'tabla' => $tabla,
										'campocod' => $campoCodigo,
										'camponombre' => $campoNombre,
										'camposextra' => $camposextra,
										'boton_id' => $idBoton,
										'idcampocod' => $idCampoCodigo,
										'idcamponombre' => $idCampoNombre,
										'criterio' => $condicion,
										'claseGrilla'    => 'grilla',
										'cantmostrar'    => '25',
										'idModal' => $idModal
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
	if ( isset( Yii::$app->session['mensajeListado'] ) ) {
		 
		 
		Alert::begin([
			'id' => 'AlertaListado',
			'options' => [
			'class' => 'alert-info'
			],
		]);

			echo Yii::$app->session->getFlash( 'mensajeListado' );
		
		Alert::end();
	
		echo "<script>window.setTimeout(function() { $('#AlertaListado').alert('close'); }, 5000)</script>";
	}
?>

<?php

	$form = ActiveForm::begin([
		'fieldConfig' 		=> ['template' => '{input}'],
		'validateOnSubmit' 	=> false,
		'id' 				=> 'formListado',
	]);
?>
<div class="pull-left" style='width:<?=( $menu_derecho == null ? '100' : '85' )?>%'>
	<div class="form" style="padding:5px;">

		<table border="0">
		<?php
		
		foreach($campos as $datos){

			$tipo= $datos['tipo'];
			$a= null;

			switch( $tipo ){

				case 'checkOpcion'		: $a = checkOpcion($form, $model, $datos, $id++); break;

				case 'rango'			: $a = rango($form, $model, $datos, $id++); break;
				case 'rangoNumero'		: $a = rangoNumero($form, $model, $datos, $id++); break;
				case 'rangoObjeto'		: $a = rangoObjeto($form, $model, $datos, $id++); break;
				case 'rangoYLista'		: $a = rangoYLista($form, $model, $datos, $id++); break;
				case 'rangoFecha'		: $a = rangoFecha($form, $model, $datos, $id++); break;

				case 'listachica'		: $a = listachica($form, $model, $datos, $id++); break;
				case 'lista'			: $a = lista($form, $model, $datos, $id++); break;
				case 'listagrande'		: $a = listagrande($form, $model, $datos, $id++); break;

				case 'busquedaObjeto'	: $a = busquedaObjeto($form, $model, $datos, $id++); break;
				case 'campos'			: $a = campos($form, $model, $datos, $id++); break;
				
				case 'listasYcamposVinculados' : $a = listasYcamposVinculados($form, $model, $datos, $id++); break;

				case 'numero'			: $a= numero($form, $model, $datos, $id++); break;
				case 'decimal'			: $a= decimal($form, $model, $datos, $id++); break;
				case 'texto'			: $a= texto($form, $model, $datos, $id++); break;
				case 'textoConRango'	: $a= textoConRango($form, $model, $datos, $id++); break;
				case 'mascara'			: $a= mascara($model, $datos, $id++); break;
				case 'radio'			: $a= radio($form, $model, $datos, $id++); break;

				case 'listaConCampo'	: $a= listaConCampo($form, $model, $datos, $id++); break;

				//case 'check': $a= datosCheck($form, $model, $datos, $id++); break;
				case 'periodo'			: $a= periodo($form, $model, $datos, $id++ ); break;
				case 'periodoSimple'	: $a= periodoSimple($form, $model, $datos, $id++ ); break;
				case 'avanzada': $a= busquedaAvanzada($form, $model, $datos, $id++); break;
				case 'tablaAuxiliar': $a= busquedaTablaAuxiliar($form, $model, $datos, $id++, $this);
									  array_push($modales, $a['modal']);
									  break;
			}

			if($a != null){
				echo $a['vista'];
				$validacionesJavascript .= $a['validacion'];
			}

		}
		?>
			
		</table>
		
	</div>
</div>

<?php if ( $menu_derecho != null ){ ?>
	<div class="pull-right" style='width:15%'>
		<?php echo $this->render($menu_derecho); ?>
	</div>
<?php } ?>


<div class="clearfix"></div>	

<div style="margin-top:5px;">
	<button type="submit" class="btn btn-success">Aceptar</button>
	<?php 
		if ( $botones != null ){
			foreach ( $botones as $b ){
				echo html::a( $b['label'], [ isset($b['href']) ? $b['href'] : null], [ 
							'class' => isset($b['class']) ? $b['class'] : 'btn btn-success', 
							'id' => isset($b['id']) ? $b['id'] : 'botton', 
							'style' => isset($b['style']) ? $b['style'] : 'margin-left:5px' 
						]
					);
			}
		}
	?>
</div>

<?php ActiveForm::end(); ?>

<div style="margin-top:5px;">
	<?=
		$form->errorSummary( $model, [
			'id' => 'contenedorErrores',
		]);
	?>
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

function btCodigoBuscarAuxClick(tabla, condicion, controlNombre, campoNombre) {
	
	$.post("<?= BaseUrl::toRoute(['obtenernombre']); ?>",  { 'campoNombre': campoNombre, 'tabla': tabla, 'condicion': condicion }
	).success(function(data){
		datos = jQuery.parseJSON(data); 
		$('#' + controlNombre).val(datos.nombre);
	});

}

function validarFormulario(){

	var error= new Array();
	var contenedorErrores= $("#contenedorErrores");

	//se comprueba que se haya checkeado algun campo
	var $checks= $( "#formListado .check:checked" );

	if( $checks.length == 0 ){
		error.push("No se encontraron criterios de búsqueda" );
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
	
	// activo los controles que se habian seleccionado para la busqueda.
	// para cuando se presiona volver
	$(".check").each(function(){
		
		if ($(this).data("target") != undefined){
			targets= $(this).data("target").split(",");
		
			for(var i in targets){
				<?php 
				foreach ($model->scenarios()["listadoBuscar"] as $attr => $val){
					if ($model->$val != "") {
				?>
						if ( targets[i].trim() == "#<?=$val?>"){
							$(this).prop("checked",true);
						}	
				<?php 	
					}
				?>
					if ( targets[i].trim() == "#<?=$val?>"  )
						$("#<?=$val?>").prop("disabled", !$(this).is(":checked"));
				<?php				
				}
				?>
			}
		}		
	});

	$("#formListado").submit(validarFormulario);
});

</script>
