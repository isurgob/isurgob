<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;
use yii\grid\GridView;

use app\utils\db\utb;

$opcion = 'Nuevo';

if(isset($extras['consulta']) && $extras['consulta'] == 1)
	$opcion = 'Consulta ' . $extras['model']->aju_id;

$this->params['breadcrumbs'][] = ['label' => 'Ajustes', 'url' => ['//ctacte/listadoajuste/index']];
$this->params['breadcrumbs'][] = ['label' => $opcion];

?>

<div class="ajustes-form">

	<h1><b>Ajuste manual de cuenta corriente</b></h1>
	<div style="border-bottom:1px solid #DDDDDD; margin-bottom:10px;"></div>

	<?php
	if(isset($mensaje) && !empty($mensaje)){

		echo Alert::widget([
			'id' => 'mensaje',
			'options' => ['class' => 'alert alert-success'],
			'body' => $mensaje
		]);

		?>
		<script type="text/javascript">
		setTimeout(function(){$("#mensaje").fadeOut();}, 5000);
		</script>
		<?php
	}
	?>

<?php
$form = ActiveForm::begin(['fieldConfig' => ['template' => '{input}'], 'id' => 'formAjustes', 'method' => 'POST']);
?>
	<div class="form" style="padding:10px;">
	<?php

	echo Html::input('hidden', 'grabar', true);
	?>
	<table border="0" width="100%">
		<tr>
			<td><b>Tributo:</b></td>
			<td width="5px"></td>
			<td colspan="16">
			<?= $form->field($extras['model'], 'trib_id')
				->dropDownList(utb::getAux('v_trib', 'trib_id', 'nombre', 0, "est = 'A' And tobj <> 0 And tipo Not In (6,7)"), ['id' => 'dlTributo', 'onchange' => 'cambiaTributo($(this).val());', 'prompt' => '', 'style' => 'width:530px;'])
				->label(false);
			?>
			</td>
		</tr>

		<tr>
			<td><b>Objeto:</b></td>
			<td></td>
			<td><?= $form->field($extras['model'], 'objeto_tipo')->textInput(['class' => 'form-control solo-lectura', 'id' => 'txTipoObjeto', 'style' => 'width:80px;', 'disabled' => true]); ?></td>

			<td width="5px"></td>
			<td colspan="13">

			<?php

			echo $form->field($extras['model'], 'obj_id', ['options' => ['style' => 'display:inline-block;']])
			->textInput(['id' => 'txCodigoObjeto', 'onchange' => 'cambiaObjeto($(this).val());', 'style' => 'width:70px;', 'disabled' => $extras['model']->obj_id == '', 'maxlength' => 8])
			->label(false);


			Modal::begin([
				'id' => 'modalObjeto',
				'closeButton' => ['class' => 'btn btn-danger btn-sm pull-right'],
				'header' => '<h1>Búsqueda de objeto</h1>',
				'toggleButton' => [
            		'label' => '<i class="glyphicon glyphicon-search"></i>',
                	'class' => 'bt-buscar',
                	'disabled' => true,
                	'id' => 'botonObjeto'
                ],
            	'size' => 'modal-lg'
			]);

			Pjax::begin(['id' => 'pjaxBusquedaObjeto', 'enableReplaceState' => false, 'enablePushState' => false]);

			$tobj = intval(Yii::$app->request->get('to', 0));

			echo $this->render('//objeto/objetobuscarav', ['txCod' => 'txCodigoObjeto', 'txNom' => 'txNombreObjeto', 'tobjeto' => $tobj, 'id' => '1', 'selectorModal' => '#modalObjeto']);

			Pjax::end();
			Modal::end();

			echo $form->field($extras['model'], 'obj_nom', ['options' => ['style' => 'display:inline-block;']])->textInput(['id' => 'txNombreObjeto', 'tabindex' => -1, 'style' => 'width:333px;', 'class' => 'form-control solo-lectura'])->label(false);

			?>
			</td>
		</tr>

		<tr>
			<td><b>Subcta:</b></td>
			<td></td>
			<td><?= $form->field($extras['model'], 'subcta')->textInput(['style' => 'width:80px;', 'class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'subcta'])->label(false); ?></td>
			<td width="10px"></td>
			<td><b>Año:</b></td>
			<td width="5px"></td>
			<td><?= $form->field($extras['model'], 'anio')->textInput(['style' => 'width:50px;', 'id' => 'anio', 'maxlength' => 4])->label(false); ?></td>
			<td width="10px"></td>
			<td><b>Cuota:</b></td>
			<td width="5px"></td>
			<td><?= $form->field($extras['model'], 'cuota')->textInput(['style' => 'width:50px;', 'id' => 'cuota', 'maxlength' => 3])->label(false); ?></td>
			<td width="5px"></td>
			<td><?= Html::button('Cargar', ['class' => 'btn btn-primary', 'onclick' => 'cargar();', 'id' => 'botonCargar']); ?></td>
			<td width="10px"></td>
			<td><b>Saldo:</b></td>
			<td width="5px"></td>
			<td><?= Html::textInput(null, null, ['class' => 'form-control solo-lectura text-right', 'disabled' => true, 'id' => 'saldo', 'style' => 'width:80px;']) ?></td>
			<td width="130px"></td>
		</tr>

		<tr>
			<td><b>Expediente:</b></td>
			<td></td>
			<td colspan="16"><?= $form->field($extras['model'], 'expe')->textInput(['style' => 'width:230px;', 'id' => 'expediente', 'disabled' => true, 'maxlength' => 12])->label(false); ?></td>
		</tr>

		<tr>
			<td valign="top"><b>Obs.:</b></td>
			<td></td>
			<td colspan="16"><?= $form->field($extras['model'], 'obs')->textarea(['style' => 'width:540px; max-width:530px; max-height:72px;', 'id' => 'observaciones', 'disabled' => true, 'maxlength' => 100])->label(false); ?></td>
		</tr>
	</table>

	</div>
</div>

<div style="margin-top:5px;">
	<?= Html::submitButton('Grabar', ['class' => 'btn btn-success', 'id' => 'botonGrabar', 'disabled' => true]); ?>
	<?php if ($extras['consulta'] == 1) { ?>
		&nbsp;
		<?= Html::a('Imprimir', ['imprimir','id'=>$extras['model']->aju_id], ['target'=>'_black','class' => 'btn btn-success']) ?>
	<?php } ?>
	&nbsp;
	<?= Html::a('Cancelar', ['listado'], ['class' => 'btn btn-primary']) ?>
</div>

<?php
	ActiveForm::end();
	?>

<div>
	<table width="100%" border="0">
		<tr>
			<td align="left">
				<h3><b><u>Cuentas</u></b></h3>
			</td>
			<td>


				<?php
				Modal::begin([
						'id' => 'modalNuevaCuenta',
						'closeButton' => ['class' => 'btn btn-danger btn-sm pull-right'],
						'header' => '<h1>Nueva Cuenta</h1>',
						'toggleButton' => [
				    		'label' => 'Agregar',
				        	'class' => 'btn btn-success pull-right',
							'disabled' => true,
				        	'id' => 'botonNuevaCuenta'
				        ]
					]);


					Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);
					echo $this->render('_form_nueva_cuenta.php', ['extras' => $extras, 'selectorPjax' => '#pjaxGrillaCuentas', 'selectorModal' => '#modalNuevaCuenta']);
					Pjax::end();

				Modal::end();
				?>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<?php
				Pjax::begin(['id' => 'pjaxGrillaCuentas', 'enableReplaceState' => false, 'enablePushState' => false]);

				echo GridView::widget([
						'dataProvider' => $extras['dpCuentas'],
						'columns' => [

							['attribute' => 'cta_id', 'label' => 'Cód', 'headerOptions' => ['style' => 'width:10px;']],
							['attribute' => 'cta_nom', 'label' => 'Cuenta'],
							['attribute' => 'debe', 'label' => 'Debe', 'headerOptions' => ['style' => 'width:10px;']],
							['attribute' => 'haber', 'label' => 'Haber', 'headerOptions' => ['style' => 'width:10px;']],
							['class' => '\yii\grid\ActionColumn', 'template' => '{deletecuenta}', 'headerOptions' => ['style' => 'width:10px;'],
							'buttons' => [
								'deletecuenta' => function($url, $model){
									return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['onclick' => 'borrarCuenta(' . $model['cta_id'] . ')']);
								}
							]
							]
						]
					]);

				Pjax::end();
				 ?>
			</td>
		</tr>
	</table>
</div>

<?php
echo $form->errorSummary($extras['model'], ['id' => 'divErrores', 'style' => 'margin-top:5px;']);
?>

<?php
Pjax::begin(['id' => 'pjaxTributo', 'enableReplaceState' => false, 'enablePushState' => false]);

$trib_id = intval(Yii::$app->request->get('tributo', 0));

if($trib_id > 0){

	$datos = utb::getVariosCampos('v_trib', "trib_id = $trib_id", 'uso_subcta, tobj, tobj_nom');

	if($datos !== false){

		$datos['uso_subcta'] = intval($datos['uso_subcta']);
		?>

		<script type="text/javascript">
		$(document).ready(function(){

			$("#txCodigoObjeto").prop("disabled", false);
			$("#botonObjeto").prop("disabled", false);
			$("#txTipoObjeto").val("<?= $datos['tobj_nom'] ?>");
			$("#subcta").toggleClass("solo-lectura", <?= $datos['uso_subcta'] == 0 ? 'true' : 'false'; ?>);
			<?= $datos['uso_subcta'] == 0 ? '$("#subcta").val(0);' : '$("#subcta").val("");' ?>
			<?= $datos['uso_subcta'] == 1 ? '$("#subcta").removeAttr("tabindex");' : '$("#subcta").attr("tabindex", -1);' ?>


			$("#botonGrabar").prop("disabled", true);
			$("#botonNuevaCuenta").prop("disabled", true);
			$("#expediente").prop("disabled", true);
			$("#observaciones").prop("disabled", true);


			$.pjax.reload({
				container : "#pjaxBusquedaObjeto",
				type : "GET",
				replace : false,
				push : false,
				data : {
					"to" : "<?= $datos['tobj'] ?>"
				}
			});
		});
		</script>
		<?php
	}
}

echo Html::input('hidden', null, null);
Pjax::end();


Pjax::begin(['id' => 'pjaxObjeto', 'enableReplaceState' => false, 'enablePushState' => false]);

$trib_id = intval(Yii::$app->request->get('trib_id', 0));
$obj_id = trim(Yii::$app->request->get('obj_id', ''));

if($trib_id > 0 && strlen($obj_id) > 0 && strlen($obj_id) <= 8){

	$tobj = utb::getTObjTrib($trib_id);
	if(strlen($obj_id) < 8) $obj_id = utb::getObjeto($tobj, $obj_id);

	$nombre = utb::getNombObj("'$obj_id'");

	?>
	<script type="text/javascript">
	$(document).ready(function(){

		$("#txNombreObjeto").val("<?= $nombre !== false ? $nombre : '' ?>");
		$("#txCodigoObjeto").val("<?= $obj_id ?>");
		$("#saldo").val("");
		$("#botonGrabar").prop("disabled", true);
		$("#botonNuevaCuenta").prop("disabled", true);
		$("#expediente").prop("disabled", true);
		$("#observaciones").prop("disabled", true);

	});
	</script>
	<?php
}

Pjax::end();

Pjax::begin(['id' => 'pjaxCargar', 'enableReplaceState' => false, 'enablePushState' => false]);

?>

<script type="text/javascript">
$(document).ready(function(){
var errores = [];
<?php
if(isset($extras['saldo'])){

	?>
		$("#saldo").val("<?= $extras['saldo'] == 0 ? '0.00' : $extras['saldo']; ?>");
	<?php


	if($extras['model']->hasErrors()){

		$errores = $extras['model']->getErrors();
		foreach($errores as $clave => $arregloErrores){
			foreach($arregloErrores as $e){
		?>

		errores.push("<?= $e; ?>");

		<?php
		}
		}

		?>
		$("#botonGrabar").prop("disabled", true);
		$("#botonNuevaCuenta").prop("disabled", true);
		$("#expediente").prop("disabled", true);
		$("#observaciones").prop("disabled", true);

		mostrarErrores(errores);
		<?php
	} else if($extras['saldo'] != 0){

		?>
		$("#botonGrabar").prop("disabled", false);
		$("#botonNuevaCuenta").prop("disabled", false);
		$("#expediente").prop("disabled", false);
		$("#observaciones").prop("disabled", false);

		$("#expediente").focus();
		<?php
	}
}

?>
});
</script>
<?php




echo Html::input('hidden', null, null);
Pjax::end();
?>

<script>
function cambiaTributo(nuevo){

	nuevo = parseInt(nuevo);

	$("#txTipoObjeto").val("");
	$("#txCodigoObjeto").val("");
	$("#txNombreObjeto").val("");
	$("#txCodigoObjeto").prop("disabled", true);
	$("#botonObjeto").prop("disabled", true);

	if(isNaN(nuevo) || nuevo <= 0) return;

	$.pjax.reload({
		container : "#pjaxTributo",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"tributo" : nuevo
		}
	});
}

function cambiaObjeto(nuevo){

	var trib_id = parseInt($("#dlTributo").val());

	$("#txNombreObjeto").val("");

	if(isNaN(trib_id) || trib_id <= 0 || nuevo.length == 0) return;

	$.pjax.reload({
		container : "#pjaxObjeto",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"obj_id" : nuevo,
			"trib_id" : trib_id
		}
	});

	$("#pjaxObjeto").on("pjax:complete", function(){

		$("#anio").focus();
		$("#pjaxObjeto").off("pjax:complete");
	});
}

function borrarCuenta(cuenta){

	$.pjax.reload({
		container : "#pjaxGrillaCuentas",
		url : "<?= BaseUrl::toRoute(['deletecuenta']); ?>",
		type : "POST",
		replace : false,
		push : false,
		data : {
			"cta_id" : cuenta,
			"consulta" : "<?= $extras['consulta'] ?>"
		}
	});
}

function cargar(){

	var tributo, objeto, subcta, anio, cuota, errores, nombreObjeto;

	$("#divErrores ul").empty();
	$("#divErrores").addClass("hidden");

	tributo = parseInt($("#dlTributo").val());
	objeto = $("#txCodigoObjeto").val();
	nombreObjeto= $("#txNombreObjeto").val();
	subcta = parseInt($("#subcta").val());
	anio = parseInt($("#anio").val());
	cuota = parseInt($("#cuota").val());

	errores = [];

	if(isNaN(tributo) || tributo <= 0) errores.push("Elija el tributo.");
	if(objeto == "" || nombreObjeto == "") errores.push("Elija un objeto valido.");
	if(isNaN(subcta) || subcta <= -1) errores.push("Ingrese la subcuenta.");
	if(isNaN(anio) || anio <= 0) errores.push("Ingrese el año.");
	if(isNaN(cuota) || cuota <= 0) errores.push("Ingrese la cuota.");

	if(mostrarErrores(errores)) return;

	$.pjax.reload({
		container : "#pjaxCargar",
		url : "<?= BaseUrl::toRoute(['cargar']); ?>",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"trib_id"	: tributo,
			"obj_id" 	: objeto,
			"subcta" 	: subcta,
			"anio" 		: anio,
			"cuota" 	: cuota
		}
	});
}

function mostrarErrores(errores){

	if(errores.length == 0) return false;

	var $contenedor = $("#divErrores ul");
	var $e = null;

	$contenedor.empty();

	for(e in errores){

		$e = $("<li />");
		$e.text(errores[e]);
		$e.appendTo($contenedor);
	}


	$("#divErrores").css("display", "block");
	$("#divErrores").removeClass("hidden");
	return true;
}

$("#modalNuevaCuenta").on("hidden.bs.modal", function(){

	$.pjax.reload({
		container : "#pjaxGrillaCuentas",
		type : "GET",
		replace : false,
		push : false
	});
});

$("#modalObjeto").on("hidde.bs.modal", function(){
	$("#anio").focus();
});

<?php
if(isset($extras['consulta']) && $extras['consulta'] == 1){
?>
$(document).ready(function(){
	DesactivarForm("formAjustes");

	<?php
	if($extras['model']->aju_id > 0){
		?>
		cargar();

		$("#pjaxCargar").on("pjax:complete", function(){

			$("#botonGrabar").prop("disabled", true);
			$("#botonNuevaCuenta").prop("disabled", true);
			$("#expediente").prop("disabled", true);
			$("#observaciones").prop("disabled", true);

			$("#pjaxCargar").off("pjax:complete");
		});
		<?php
	}
	?>
});
<?php
}
?>
</script>
