<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

use app\utils\db\utb;

$title= 'Opciones';

$this->params['breadcrumbs'][]= ['label' => 'Retenciones', 'url' => ['index']];
$this->params['breadcrumbs'][]= 'Listado';
$this->params['breadcrumbs'][]= $title;
?>


<div>

	<h1><?= $title; ?></h1>
	<div class="separador-horizontal"></div>

	<div class="form" style="padding: 5px;">
		<table border="0">
			<tr>
				<td><?= Html::checkbox(null, false, ['class' => 'check', 'label' => 'ID', 'id' => 'checkId', 'data-target' => '#idDesde, #idHasta']); ?></td>
				<td width="5px"></td>
				<td><label>Desde</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'idDesde', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 8, 'style' => 'width:80px;']); ?></td>
				<td width="5px"></td>
				<td><label>Hasta</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'idHasta', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 8, 'style' => 'width:80px;']); ?></td>
			</tr>

			<tr>
				<td><?= Html::checkbox(null, false, ['class' => 'check', 'label' => 'Objeto', 'id' => 'checkObjeto', 'data-target' => '#codigoObjetoDesde, #codigoObjetoHasta']); ?></td>
				<td></td>
				<td><label>Desde</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'codigoObjetoDesde', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 8, 'style' => 'width:80px;']); ?></td>
				<td width="5px"></td>
				<td><label>Hasta</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'codigoObjetoHasta', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 8, 'style' => 'width:80px;']); ?></td>
			</tr>

			<tr>
				<td><?= Html::checkbox(null, false, ['class' => 'check', 'label' => 'Período', 'id' => 'checkPeriodo', 'data-target' => '#anio, #mes']); ?></td>
				<td></td>
				<td><label>A&ntilde;o</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'anio', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 4, 'style' => 'width:80px;']); ?></td>
				<td></td>
				<td><label>Mes</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'mes', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 3, 'style' => 'width:80px;']); ?></td>
			</tr>

			<tr>
				<td><?= Html::checkbox(null, false, ['class' => 'check', 'label' => 'Fecha', 'id' => 'checkFecha', 'data-target' => '#fechaDesde, #fechaHasta']); ?></td>
				<td></td>
				<td><label>Desde</label></td>
				<td><?= DatePicker::widget(['name' => 'fechaDesde', 'dateFormat' => 'php:d/m/Y', 'options' => ['id' => 'fechaDesde', 'class' => 'form-control', 'disabled' => true, 'maxlength' => 10, 'style' => 'width:80px;']]); ?></td>
				<td width="5px"></td>
				<td><label>Hasta</label></td>
				<td><?= DatePicker::widget(['name' => 'fechaHasta', 'dateFormat' => 'php:d/m/Y', 'options' => ['id' => 'fechaHasta', 'class' => 'form-control', 'disabled' => true, 'maxlength' => 10, 'style' => 'width:80px;']]); ?></td>
			</tr>

			<tr>
				<td><?= Html::checkbox(null, false, ['class' => 'check', 'label' => 'Base', 'id' => 'checkBase', 'data-target' => '#baseDesde, #baseHasta']); ?></td>
				<td></td>
				<td><label>Desde</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'baseDesde', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 8, 'style' => 'width:80px;']); ?></td>
				<td width="5px"></td>
				<td><label>Hasta</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'baseHasta', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 8, 'style' => 'width:80px;']); ?></td>
			</tr>

			<tr>
				<td><?= Html::checkbox(null, false, ['class' => 'check', 'label' => 'Monto', 'id' => 'checkMonto', 'data-target' => '#montoDesde, #montoHasta']); ?></td>
				<td></td>
				<td><label>Desde</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'montoDesde', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 8, 'style' => 'width:80px;']); ?></td>
				<td width="5px"></td>
				<td><label>Hasta</label></td>
				<td><?= Html::textInput(null, null, ['id' => 'montoHasta', 'disabled' => true, 'class' => 'form-control', 'maxlength' => 8, 'style' => 'width:80px;']); ?></td>
			</tr>

			<tr>
				<td><?= Html::checkbox(null, false, ['class' => 'check', 'label' => 'Agente', 'id' => 'checkAgente', 'data-target' => '#agente']); ?></td>
				<td></td>
				<td colspan="2"><?= Html::dropDownList(null, null, utb::getAux('v_persona', 'ag_rete', 'ag_rete', 0, "est = 'A' And ag_rete IS NOT NULL AND length(ag_rete) > 0"), ['id' => 'agente', 'disabled' => true, 'class' => 'form-control', 'prompt' => '', 'style' => 'width:118px;']); ?></td>
			</tr>

			<tr>
				<td><?= Html::checkbox(null, false, ['class' => 'check', 'label' => 'Estado', 'id' => 'checkEstado', 'data-target' => '#estado']); ?></td>
				<td></td>
				<td colspan="2"><?= Html::dropDownList(null, null, utb::getAux('ret_test'), ['id' => 'estado', 'disabled' => true, 'class' => 'form-control', 'prompt' => '', 'style' => 'width:118px;']); ?></td>
			</tr>
		</table>
	</div>

	<div style="margin-top: 5px;">
		<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'buscar();']) ?>
	</div>
</div>

<?php
$form= ActiveForm::begin(['id' => 'form']);

echo Html::hiddenInput('criterio', null, ['id' => 'criterio']);
echo Html::hiddenInput('descripcion', null, ['id' => 'descripcion']);

ActiveForm::end();
?>

<div class="error-summary" id="contenedorErrores" style="display:none; margin-top: 5px;"></div>

<script>
function armarPeticion(){

	var errores= [];
	var desde= hasta = "";
	var criterio= "";
	var descripcion= "";

	if($("#checkId").is(":checked")){

		desde= parseInt($("#idDesde").val());
		hasta= parseInt($("#idHasta").val());

		if(isNaN(desde) || isNaN(hasta))
			errores.push("Los IDs deben ser números.");
		else if(hasta < desde)
			errores.push("Rango de IDs mal ingresados.");
		else{

			criterio += "ret_id Between " + desde + " And " + hasta;
			descripcion += "-ID desde " + desde + " hasta " + hasta;
		}
	}

	if($("#checkObjeto").is(":checked")){

		desde= $("#codigoObjetoDesde").val();
		hasta= $("#codigoObjetoHasta").val();

		var desdeSinLetra= isNaN(parseInt(desde.substr(0, 1))) ? desde : parseInt(desde.substr(0, 1));
		var hastaSinLetra= isNaN(parseInt(hasta.substr(0, 1))) ? hasta : parseInt(hasta.substr(0, 1));

		if(isNaN(desdeSinLetra) || isNaN(hastaSinLetra) || desdeSinLetra <= 0 || hastaSinLetra <= 0)
			errores.push("Codigos de objeto mal ingresados");
		else if(hastaSinLetra < desdeSinLetra)
			errores.push("Rango de objetos mal ingresados");
		else{

			c= "obj_id Between '" + desde + "' And '" + hasta + "'";
			criterio += criterio != "" ? " And " + c : c;
			descripcion += "-Objeto desde " + desde + " hasta " + hasta;
		}
	}

	if($("#checkPeriodo").is(":checked")){

		anio= parseInt($("#anio").val());
		mes= parseInt($("#mes").val());

		if(isNaN(anio))
			errores.push("El año del período debe ser un número");

		if(isNaN(mes) || mes < 1 || mes > 12)
			errores.push("El mes del período debe ser un número entre 1 y 12.");

		if(!isNaN(anio) && !isNaN(mes)){

			c= "(anio = " + anio + " And mes = " + mes + ")";
			criterio += criterio != "" ? " And " + c : c;
			descripcion= "-Período " + anio + "/" + mes;
		}
	}

	if($("#checkFecha").is(":checked")){

		desde= $("#fechaDesde").val();
		hasta= $("#fechaHasta").val();

		if(desde == "" || hasta == "")
			errores.push("Complete el rango de fecha");
		else if(ValidarRangoFechaJs(desde, hasta) != 1)
			errores.push("Rango de fechas mal ingresado");
		else {

			c= "fecha Between " + desde + " And " + hasta;
			criterio += criterio != "" ? " And " + c : c;
			descripcion += "-Fecha entre " + desde + " y " + hasta;
		}
	}

	if($("#checkBase").is(":checked")){

		desde= parseFloat($("#baseDesde").val());
		hasta= parseFloat($("#baseHasta").val());

		if(isNaN(desde) || isNaN(hasta) || desde < 0 || hasta < 0)
			errores.push("Los valores de base deben ser números decimales mayores o iguales a cero");
		else if(hasta < desde)
			errores.push("Rango de valores de base mal ingresado");
		else{

			c= "base Between " + desde + " And " + hasta;
			criterio += criterio != "" ? " And " + c : c;
			descripcion += "-Base entre " + desde + " y " + hasta;
		}
	}

	if($("#checkMonto").is(":checked")){

		desde= parseFloat($("#montoDesde").val());
		hasta= parseFloat($("#montoHasta").val());

		if(isNaN(desde) || isNaN(hasta) || desde < 0 || hasta < 0)
			errores.push("Los valores de monto deben ser números decimales mayores o iguales a cero");
		else if(hasta < desde)
			errores.push("Rango de valores de monto mal ingresado");
		else{

			c= "monto Between " + desde + " And " + hasta;
			criterio += criterio != "" ? " And " + c : c;
			descripcion += "-Monto entre " + desde + " y " + hasta;
		}
	}

	if($("#checkAgente").is(":checked")){

		opcion= $("#agente").val();

		if(opcion == "")
			errores.push("Elija un agente");
		else{

			c= "ag_rete = '" + opcion + "'";
			criterio += criterio != "" ? " And " + c : c;
			descripcion += "-Agente= " + $("#agente option:selected").text();
		}
	}

	if($("#checkEstado").is(":checked")){

		opcion= $("#estado").val();

		if(opcion == "")
			errores.push("Elija un estado");
		else{

			c= "estado = '" + opcion + "'";
			criterio += criterio != "" ? " And " + c : c;
			descripcion += "-Estado= " + $("#estado option:selected").text();
		}
	}


	return {
		"errores": errores,
		"criterio": criterio,
		"descripcion": descripcion
	};
}

function buscar(){

	var consulta= armarPeticion();


	if(consulta.errores.length == 0 && consulta.criterio == "") consulta.errores.push("No se encontraton criterios de búsqueda");

	if(consulta.errores.length == 0){

		$("#criterio").val(consulta.criterio);
		$("#descripcion").val(consulta.descripcion);

		$("#form").submit();

	} else {

		mostrarErrores(consulta.errores, $("#contenedorErrores"));
	}
}

$(document).ready(function(){

	$(".check").click(function(){

		var targets= $(this).data("target").split(",");
		var actual= "";

		for(actual in targets){

			actual= targets[actual].trim();
			$(actual).prop("disabled", !$(this).is(":checked"));
		}

	});
});
</script>
