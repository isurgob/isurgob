<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Alert;
use app\utils\db\utb;

$this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares', 'url' => Yii::$app->param->sis_url.'site/taux'];
$this->params['breadcrumbs'][] = 'Países, Provincias y Localidades';
?>
<style>
.oculto{
	visibility:hidden;
}
</style>

<div class="aux-localidad">
	<h1>Pa&iacute;ses, Provincias y Localidades</h1>
	<div style="height:5px; border-bottom:1px solid #DDDDDD"></div>


	<?php
	//se guardan los datos anteriores a presionar nuevo o modificar. Se usa para volver a cargar los datos en caso de que se haga click en cancelar
	echo Html::input('hidden', null, null, ['id' => 'codigoPaisAnterior']);
	echo Html::input('hidden', null, null, ['id' => 'codigoProvinciaAnterior']);
	echo Html::input('hidden', null, null, ['id' => 'codigoLocalidadAnterior']);

	echo Html::input('hidden', null, null, ['id' => 'nombrePaisAnterior']);
	echo Html::input('hidden', null, null, ['id' => 'nombreProvinciaAnterior']);
	echo Html::input('hidden', null, null, ['id' => 'nombreLocalidadAnterior']);
	echo Html::input('hidden', null, null, ['id' => 'codigoPostalAnterior']);

	echo Html::input('hidden', null, null, ['id' => 'tipoAnterior']);

	?>

	<div style="margin-top:5px">
	<?php
	if (utb::getExisteProceso(1031)) {
		echo ButtonDropdown::widget([
			'label' => 'Paises',
			'id' => 'dropDownPaises',
			'dropdown' => [
				'items' => [
					['label' => 'Nuevo', 'url' => '#', 'linkOptions' => ['onclick' => 'activarFormulario("pais", 0)', 'class' => 'nuevo']],
					['label' => 'Modificar', 'url' => '#', 'linkOptions' => ['onclick' => 'activarFormulario("pais", 3)', 'class' => 'modificar']],
					['label' => 'Eliminar', 'url' => '#', 'linkOptions' => ['onclick' => 'activarFormulario("pais", 2)', 'class' => 'eliminar']]
				]
			],
			'options' => [
				'class' => 'btn btn-primary'
			]
			]);
		echo '&nbsp;&nbsp;&nbsp;';
		echo ButtonDropdown::widget([
			'label' => 'Provincias',
			'id' => 'dropDownProvincias',
			'dropdown' => [
				'items' => [
					['label' => 'Nueva', 'url' => '#', 'linkOptions' => ['onclick' => 'activarFormulario("provincia", 0)', 'class' => 'nuevo']],
					['label' => 'Modificar', 'url' => '#', 'linkOptions' => ['onclick' => 'activarFormulario("provincia", 3)', 'class' => 'modificar']],
					['label' => 'Eliminar', 'url' => '#', 'linkOptions' => ['onclick' => 'activarFormulario("provincia", 2)', 'class' => 'eliminar']]
				]
			],
			'options' => [
				'class' => 'btn btn-primary'
			]
		]);

		echo '&nbsp;&nbsp;&nbsp;';
		echo ButtonDropdown::widget([
			'label' => 'Localidades',
			'id' => 'dropDownLocalidades',
			'dropdown' => [

				'items' => [
					['label' => 'Nueva', 'url' => '#', 'linkOptions' => ['onclick' => 'activarFormulario("localidad", 0)', 'class' => 'nuevo']],
					['label' => 'Modificar', 'url' => '#', 'linkOptions' => ['onclick' => 'activarFormulario("localidad", 3)', 'class' => 'modificar']],
					['label' => 'Eliminar', 'url' => '#', 'linkOptions' => ['onclick' => 'activarFormulario("localidad", 2)', 'class' => 'eliminar']]
				]
			],
			'options' => [
				'class' => 'btn btn-primary'
			]
		]);

		echo '&nbsp;&nbsp;&nbsp;';
		echo Html::button('Buscar', ['class' => 'btn btn-primary', 'onclick' => 'cambiarBuscar();']);
	}
	?>
	</div>

	<?php
	Pjax::begin(['id' => 'pjaxTodo', 'enableReplaceState' => false, 'enablePushState' => false]);
	?>
	<style rel="stylesheet">#asd td{border: 1px solid;}</style>
	<table border="0" width="100%">
		<tr>
			<td>
				<table border="0" width="100%">
					<tr>
						<td colspan="3">
							<div class="form" style="padding:5px; margin-top:5px;">
							<h3><label>Datos seleccionados</label></h3>

							<?php
							Pjax::begin(['id' => 'pjaxForm', 'enableReplaceState' => false, 'enablePushState' => false]);
							$form = ActiveForm::begin(['id' => 'formLocalidad', 'fieldConfig' => ['template' => '{input}']]);


							echo Html::input('hidden', 'consulta', $consulta, ['id' => 'consulta']);
							echo Html::input('hidden', 'tipo', $extras['tipo'], ['id' => 'tipo']);

							echo $form->field($extras['model'], 'pais_id')->input('hidden', ['id' => 'codigoPais'])->label(false);
							echo $form->field($extras['model'], 'prov_id')->input('hidden', ['id' => 'codigoProvincia'])->label(false);
							echo $form->field($extras['model'], 'loc_id')->input('hidden', ['id' => 'codigoLocalidad'])->label(false);

							$clasePais = $claseProvincia = $claseLocalidad = 'form-control';
							$grabado = isset($grabado) ? $grabado : false;
							$mensaje = isset($mensaje) ? $mensaje : '';

							//se habilitan los botones de edicion
							if($grabado){
								?>
								<script type="text/javascript">
								$(document).ready(function(){
									$("#dropDownPaises").prop("disabled", false);
									$("#dropDownProvincias").prop("disabled", false);
									$("#dropDownLocalidades").prop("disabled", false);
								});
								</script>
								<?php
							}

							switch($extras['tipo']){

								case 'pais':

									$claseProvincia = $claseLocalidad = 'form-control solo-lectura';

									if($consulta === 1 || $consulta === 2) $clasePais = 'form-control solo-lectura';

									//en caso de que se hayan grabado los datos, se vuelven a cargar todas las grillas con el filtro del pais
									if($grabado){
										?>
										<script type="text/javascript">
										$(document).ready(function(){

											$.pjax.reload({
												container : "#pjaxPais",
												replace :false,
												push : false,
												type : "GET",
												data : {
													"tipo" : "pais"
												}
											});

											$("#pjaxPais").on("pjax:complete", function(){

												$.pjax.reload({
													container : "#pjaxProvincia",
													replace : false,
													push : false,
													type : "GET",
													data : {
														"tipo" : "provincia",
														"codigoPais" : "<?= $extras['model']->pais_id ?>"
													}
												});

												$("#pjaxProvincia").on("pjax:complete", function(){

													<?php
													if(isset($extras['consulta']) and $extras['consulta'] === 2){
														?>
														cargarDatos("pais", $("#dlCodigoPais").val());
														<?php
													} else{
														?>
														cargarDatos("pais", "<?= $extras['model']->pais_id; ?>");
														<?php
													}
													?>

													$("#pjaxProvincia").off("pjax:complete");
												});


												$("#pjaxPais").off("pjax:complete");
											});
										});
										</script>
										<?php
									}

									break;

								case 'provincia':

									$clasePais = 'form-control solo-lectura';
									$claseLocalidad = 'form-control solo-lectura';

									if($consulta === 1 || $consulta === 2) $claseProvincia = 'form-control solo-lectura';

									if($grabado){
										?>
										<script type="text/javascript">
										$(document).ready(function(){

											$.pjax.reload({
												container : "#pjaxProvincia",
												replace : false,
												push : false,
												type : "GET",
												data : {
													"tipo" : "provincia",
													"codigoPais" : "<?= $extras['model']->pais_id ?>"
												}
											});

											$("#pjaxProvincia").on("pjax:complete", function(){

												$.pjax.reload({
													container : "#pjaxLocalidad",
													replace :false,
													push : false,
													type : "GET",
													data : {
														"tipo" : "localidad",
														"codigoPais" : "<?= $extras['model']->pais_id ?>",
														"codigoProvincia" : "<?= $extras['model']->prov_id ?>"
													}
												});

												$("#pjaxProvincia").off("pjax:complete");
											});
										});
										</script>
										<?php
									}

									break;

								case 'localidad':

									$clasePais = $claseProvincia = 'form-control solo-lectura';

									if($consulta === 1 || $consulta === 2) $claseLocalidad = 'form-control solo-lectura';

									if($grabado){
										?>
										<script type="text/javascript">
										$.pjax.reload({
											container : "#pjaxLocalidad",
											replace : false,
											push : false,
											type : "GET",
											data : {
												"tipo" : "localidad",
												"codigoPais" : "<?= $extras['model']->pais_id ?>",
												"codigoProvincia" : "<?= $extras['model']->prov_id ?>"
											}
										});
										</script>
										<?php
									}

									break;

								default:

									$clasePais = $claseProvincia = $claseLocalidad = 'form-control solo-lectura';
									break;
							}
							?>

							<table>
								<tr>
									<td><label>Pa&iacute;s</label></td>
									<td width="5px"></td>
									<td><?= $form->field($extras['model'], 'nombre_pais')->textInput(['style' => 'width:150px;', 'maxlength' => 20, 'class' => $clasePais, 'id' => 'nombrePais'])->label(false) ?></td>
									<td width="10px"></td>
									<td><label>Provincia</label></td>
									<td width="5px"></td>
									<td><?= $form->field($extras['model'], 'nombre_prov')->textInput(['style' => 'width:150px;', 'maxlength' => 30, 'class' => $claseProvincia, 'id' => 'nombreProvincia'])->label(false) ?></td>
									<td width="10px"></td>
									<td><label>Localidad</label></td>
									<td width="5px"></td>
									<td><?= $form->field($extras['model'], 'nombre_loc')->textInput(['style' => 'width:150px;', 'maxlength' => 40, 'class' => $claseLocalidad, 'id' => 'nombreLocalidad'])->label(false) ?></td>
									<td width="10px"></td>
									<td><label>Cod. Pos.</label></td>
									<td width="5px"></td>
									<td><?= $form->field($extras['model'], 'cp')->textInput(['style' => 'width:50px;', 'maxlength' => 4, 'class' => $claseLocalidad, 'id' => 'codigoPostal'])->label(false) ?></td>
								</tr>
							</table>

							<?php
							$claseBoton = $consulta === 2 ? 'btn btn-danger' : ($consulta === 1 ? 'hidden btn btn-success' : 'btn btn-success');
							$claseCancelar = $consulta === 1 ? 'hidden btn btn-primary' : 'btn btn-primary';

							echo Html::button('Grabar', ['class' => $claseBoton, 'id' => 'botonGrabar', 'onclick' => 'grabar();']);
							echo '&nbsp;&nbsp;&nbsp;';
							echo Html::button('Cancelar', ['class' => $claseCancelar, 'id' => 'botonCancelar', 'onclick' => 'cancelar();']);


							ActiveForm::end();

							?>
							<div style="margin-top:5px;">
							<?php

							if($grabado && isset($mensaje) && !empty(trim($mensaje))){

								echo Alert::widget([
									'body' => $mensaje,
									'options' => ['class' => 'alert alert-success alert-dissmissible'],
									'id' => 'alertMensaje'
								]);

								?>
								<script type="text/javascript">
								setTimeout(function(){$("#alertMensaje").fadeOut();}, 5000);
								</script>
								<?php
							}


							echo $form->errorSummary($extras['model']);
							?>
							</div>
							<?php

							Pjax::end();
							?>
							</div>

							<div class="hidden form" id="divBuscar" style="padding:5px; margin-bottom:5px; margin-top:5px;">
								<table width="100%" border="0" class="asd">

									<tr>
										<td><b>Pa&iacute;s:</b></td>
										<td width="5px"></td>
										<td><?= Html::textInput(null, $extras['filtroPais'], ['class' => 'form-control', 'id' => 'buscarNombrePais', 'style' => 'width:150px;', 'maxlength' => 20]); ?></td>
										<td width="10px"></td>
										<td><b>Provincia:</b></td>
										<td width="5px"></td>
										<td><?= Html::textInput(null, $extras['filtroProvincia'], ['class' => 'form-control', 'id' => 'buscarNombreProvincia', 'style' => 'width:150px;', 'maxlength' => 30]); ?></td>
										<td width="10px"></td>
										<td><b>Localidad:</b></td>
										<td width="5px"></td>
										<td><?= Html::textInput(null, $extras['filtroLocalidad'], ['class' => 'form-control', 'id' => 'buscarNombreLocalidad', 'style' => 'width:150px;', 'maxlength' => 40]); ?></td>
										<td width="10px"></td>
										<td><b>CP.:</b></td>
										<td width="5px"></td>
										<td><?= Html::textInput(null, $extras['filtroCodigoPostal'], ['class' => 'form-control', 'id' => 'buscarCodigoPostal', 'style' => 'width:50px;', 'maxlength' => 4]); ?></td>
										<td width="10px"></td>
										<td><?= Html::button('Aceptar', ['class' => 'btn btn-primary', 'onclick' => 'buscar();']); ?></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>

					<tr class="lineaBuscador hidden">
						<td colspan="3">
							<?php
							Pjax::begin(['id' => 'pjaxBuscador', 'enableReplaceState' => false, 'enablePushState' => false]);

							echo GridView::widget([
								'dataProvider' => $extras['dpBuscador'],
								'columns' => [

									['attribute' => 'nombre_pais', 'label' => 'País', 'options' => ['style' => 'width:1%;']],
									['attribute' => 'nombre_provincia', 'label' => 'Provincia', 'options' => ['style' => 'width:200px;']],
									['attribute' => 'loc_id', 'label' => 'Código'],
									['attribute' => 'nombre_localidad', 'label' => 'Localidad'],
									['attribute' => 'codigo_postal', 'label' => 'Código postal', 'options' => ['style' => 'width:1%;']],
									['class' => '\yii\grid\Column', 'options' => ['style' => 'width:1%;'],
									'content' => function($model){
										return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', null, ['onclick' => 'cargarDatos("localidad", "' . $model['pais_id'] . '", "' . $model['prov_id'] . '", "' . $model['loc_id'] . '", "' . $model['codigo_postal'] . '")']);
									}
									]
								]
							]);

							Pjax::end();
							?>
						</td>
					</tr>

					<tr class="lineaDatos">
						<td colspan="3 valign="top">

							<?php
							Pjax::begin(['id' => 'pjaxPais', 'enableReplaceState' => false, 'enablePushState' => false]);

							echo '<label>País: &nbsp;</label>';

							echo Html::dropDownList(null, $extras['model']->pais_id, $extras['paises'], ['onchange' => 'cargarDatos("pais", $(this).val());', 'class' => 'form-control', 'prompt' => '', 'id' => 'dlCodigoPais']);

							Pjax::end();
							 ?>
						</td>
					</tr>

					<tr class="lineaDatos">
						<td valign="top" style="padding-right:10px; margin-right:auto; margin-left:auto;" width="40%">
							<?php
							Pjax::begin(['id' => 'pjaxProvincia', 'enableReplaceState' => false, 'enablePushState' => false]);

							echo '<h4><label>Provincia</label></h4>';
							echo GridView::widget([
								'dataProvider' => $extras['dpProvincia'],
								'summary' => false,
								'showOnEmpty' => true,
								'showHeader' => true,
								'headerRowOptions' => ['class' => 'grilla'],
								'rowOptions' => function ($model, $key, $index, $grid){

									return [
										'onclick' => 'cargarDatos("provincia", ' . $model['pais_id'] . ', ' . $model['prov_id'] . ');',
										'class' => 'grilla'
									];
								},
								'columns' => [
									['attribute' => 'prov_id', 'label' => 'Cod', 'options' => ['style' => 'width:10px;']],
									['attribute' => 'nombre', 'label' => 'Nombre']
								]
							]);

							Pjax::end();
							?>
						</td>


						<td valign="top" width="40%" valign="top"  style="padding-left:10px; margin-right:auto; margin-left:auto;">
							<?php
							Pjax::begin(['id' => 'pjaxLocalidad', 'enableReplaceState' => false, 'enablePushState' => false]);

							echo '<h4><label>Localidad</label></h4>';
							echo GridView::widget([
								'dataProvider' => $extras['dpLocalidad'],
								'summary' => false,
								'showOnEmpty' => true,
								'showHeader' => true,
								'headerRowOptions' => ['class' => 'grilla'],
								'rowOptions' => function ($model, $key, $index, $grid){

									return [
										'onclick' => 'cargarDatos("localidad", 0, ' . $model['prov_id'] . ', ' . $model['loc_id'] . ');',
										'class' => 'grilla'
									];
								},
								'columns' => [
									['attribute' => 'loc_id', 'label' => 'Cod', 'options' => ['style' => 'width:10px;']],
									['attribute' => 'nombre', 'label' => 'Nombre'],
									['attribute' => 'cp', 'label' => 'Cod. Pos.', 'options' => ['style' => 'width:10px;']]
								]
							]);

							Pjax::end();
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<?php
	Pjax::end();
	?>

	<script type="text/javascript">
	function cargarDatos(tipo, codigoPais, codigoProvincia, codigoLocalidad){
		console.log(tipo);
		console.log(codigoPais);
		var cons = parseInt($("#consulta").val());
		var seleccionado = true;

		if(isNaN(cons)) cons = 1;

		if(tipo == "pais" && codigoPais <= 0){
			seleccionado = false;

			$("#nombrePais").val("");
			$("#nombreProvincia").val("");
			$("#nombreLocalidad").val("");
			$("#codigoPostal").val("");

		} else if(tipo == "provincia" && codigoPais <= 0 && codigoProvincia <= 0){
			seleccionado = false;

			$("#nombreProvincia").val("");
			$("#nombreLocalidad").val("");
			$("#codigoPostal").val("");

		} else if(tipo == "localidad" && codigoProvincia <= 0 && codigoLocalidad <= 0){
			seleccionado = false;

			$("#nombreLocalidad").val("");
			$("#codigoPostal").val("");
		}



		if(!seleccionado){

			$("#dropDownLocalidades").prop("disabled", false);
			$("#dropDownProvincias").prop("disabled", false);
			$("#dropDownPaises").prop("disabled", false);

			$("#botonCancelar").toggleClass("hidden", true);
			$("#botonGrabar").toggleClass("hidden", true);

			return;
		}

		if(tipo == "localidad") codigoPais = $("#codigoPais").val();

		$.pjax.reload({
			container : "#pjaxTodo",
			replace : false,
			push : false,
			type : "GET",
			data : {
				"consulta" : 1,
				"tipo" : tipo,
				"codigoPais" : codigoPais,
				"codigoProvincia" : codigoProvincia,
				"codigoLocalidad" : codigoLocalidad
			}
		});

		$("#pjaxTodo").on("pjax:end", function(){

			if(tipo != ""){

				$("#dropDownLocalidades").prop("disabled", false);
				$("#dropDownProvincias").prop("disabled", false);
				$("#dropDownPaises").prop("disabled", false);
			} else{

				$("#dropDownPaises").prop("disabled", true);
				$("#dropDownProvincias").prop("disabled", true);
				$("#dropDownLocalidades").prop("disabled", true);
			}

			$("#divBuscar").addClass("hidden");
			$(".lineaBuscador").addClass("hidden");
			$(".lineaDatos").removeClass("hidden");

			$("#pjaxTodo").off("pjax:end");
		});
	}

	function activarFormulario(tipo, consulta){

		var modificado = habilitar = false;

		//se guardan los datos que se estan listando actualmente para cargarlos si hace click en cancelar
		$("#nombrePaisAnterior").val($("#nombrePais").val());
		$("#nombreProvinciaAnterior").val($("#nombreProvincia").val());
		$("#nombreLocalidadAnterior").val($("#nombreLocalidad").val());

		$("#codigoPaisAnterior").val($("#codigoPais").val());
		$("#codigoProvinciaAnterior").val($("#codigoProvincia").val());
		$("#codigoLocalidadAnterior").val($("#codigoLocalidad").val());

		$("#codigoPostalAnterior").val($("#codigoPostal").val());

		$("#tipoAnterior").val($("#tipo").val());
		//se termina de guardar los datos actuales


		switch(tipo){

			case "pais":

				if(consulta == 0){
					$("#nombrePais").val("");
					$("#codigoPais").val("");
				}

				habilitar = consulta == 0 || $("#nombrePais").val() != "";

				$("#nombrePais").toggleClass("solo-lectura", (!habilitar || consulta == 2));
				$("#nombreProvincia").addClass("solo-lectura");
				$("#nombreLocalidad").addClass("solo-lectura");
				$("#codigoPostal").addClass("solo-lectura");

				modificado = true;

			case "provincia":

				if(consulta == 0){
					$("#nombreProvincia").val("");
					$("#codigoProvincia").val("");
				}


				if(!modificado){

					habilitar = (consulta == 0 && $("#nombrePais").val() != "") || $("#nombreProvincia").val() != "";

					$("#nombreProvincia").toggleClass("solo-lectura", (!habilitar || consulta == 2));
					$("#nombrePais").addClass("solo-lectura");
					$("#nombreLocalidad").addClass("solo-lectura");
					$("#codigoPostal").addClass("solo-lectura");

					modificado = true;
				}

			case "localidad":

				if(consulta == 0){
					$("#nombreLocalidad").val("");
					$("#codigoLocalidad").val("");
					$("#codigoPostal").val("");
				}

				if(!modificado){

					habilitar = (consulta == 0 && $("#nombrePais").val() != "" && $("#nombreProvincia").val() != "")|| ($("#nombreLocalidad").val() != "" && $("#codigoPostal").val() != "");

					$("#nombreProvincia").addClass("solo-lectura");
					$("#nombrePais").addClass("solo-lectura");
					$("#nombreLocalidad").toggleClass("solo-lectura", (!habilitar || consulta == 2));
					$("#codigoPostal").toggleClass("solo-lectura", (!habilitar || consulta == 2));
				}
		}

		$("#tipo").val(tipo);
		$("#consulta").val(consulta);

		$("#botonGrabar").addClass("btn");
		$("#botonGrabar").toggleClass("btn-success", consulta !== 2);
		$("#botonGrabar").toggleClass("btn-danger", consulta === 2);

		$("#botonCancelar").toggleClass("hidden", !habilitar);
		$("#botonGrabar").toggleClass("hidden", !habilitar);

		$("#dropDownLocalidades").prop("disabled", habilitar);
		$("#dropDownProvincias").prop("disabled", habilitar);
		$("#dropDownPaises").prop("disabled", habilitar);
		console.log(habilitar);
	}

	function grabar(){

		var datos = {};

		var consulta = parseInt($("#consulta").val());
		var tipo = $("#tipo").val();
		datos.pais_id = $("#codigoPais").val();
		datos.prov_id = $("#codigoProvincia").val();
		datos.loc_id = $("#codigoLocalidad").val();
		datos.nombre_pais = $("#nombrePais").val();
		datos.nombre_prov = $("#nombreProvincia").val();
		datos.nombre_loc = $("#nombreLocalidad").val();
		datos.cp = $("#codigoPostal").val();

		if(isNaN(consulta)){
			console.log("la consulta no es un entero");
			return;
		}

		$.pjax.reload({
			container : "#pjaxForm",
			replace : false,
			push : false,
			type : "POST",
			data : {
				"consulta" : consulta,
				"tipo" : tipo,
				"Localidad" : datos
			}
		});
	}

	//se vuelven a cargar los datos que se estaban listando anteriormente y se cambia el tipo de consulta a 1
	function cancelar(){

		$("#tipo").val($("#tipoAnterior").val());

		$("#nombrePais").val($("#nombrePaisAnterior").val());
		$("#nombreProvincia").val($("#nombreProvinciaAnterior").val());
		$("#nombreLocalidad").val($("#nombreLocalidadAnterior").val());

		$("#codigoPais").val($("#codigoPaisAnterior").val());
		$("#codigoProvincia").val($("#codigoProvinciaAnterior").val());
		$("#codigoLocalidad").val($("#codigoLocalidadAnterior").val());

		$("#codigoPostal").val($("#codigoPostalAnterior").val());
		$("#consulta").val(1);

		$("#nombrePais").addClass("solo-lectura");
		$("#nombreProvincia").addClass("solo-lectura");
		$("#nombreLocalidad").addClass("solo-lectura");
		$("#codigoPostal").addClass("solo-lectura");

		$("#botonGrabar").addClass("hidden");
		$("#botonCancelar").addClass("hidden");

		$("#dropDownLocalidades").prop("disabled", false);
		$("#dropDownProvincias").prop("disabled", false);
		$("#dropDownPaises").prop("disabled", false);
	}

	//busca datos dependiendo del pais, provincia o localidad ingresadas
	function buscar(){

		var nombrePais= $("#buscarNombrePais").val();
		var nombreProvincia= $("#buscarNombreProvincia").val();
		var nombreLocalidad= $("#buscarNombreLocalidad").val();
		var codigoPostal= parseInt($("#buscarCodigoPostal").val());

		if(nombrePais === "" && nombreProvincia === "" && nombreLocalidad === "" && (isNaN(codigoPostal) ||codigoPostal <= 0)) return;

		$.pjax.reload({
			container: "#pjaxBuscador",
			type: "GET",
			replace: false,
			push: false,
			data: {
				"buscar": true,
				"buscarNombrePais": nombrePais,
				"buscarNombreProvincia": nombreProvincia,
				"buscarNombreLocalidad": nombreLocalidad,
				"buscarCodigoPostal": codigoPostal
			}
		});
	}

	function cambiarBuscar(){

		$("#divBuscar").toggleClass("hidden");
		$(".lineaBuscador").toggleClass("hidden");
		$(".lineaDatos").toggleClass("hidden");
	}


		$(document).ready(function(){

			cargarDatos("pais", $("#codigoPais").val());
		});


	</script>
</div>
