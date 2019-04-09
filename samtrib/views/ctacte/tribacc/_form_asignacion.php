<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\grids\GridView;

use app\utils\db\utb;


$this->params['breadcrumbs'][] = ['label' => 'Listado de asignaciones', 'url' => ['//ctacte/listadotribacc']];
$this->params['breadcrumbs'][] = ['label' => 'Asignaciones'];

?>
<?php
$form = ActiveForm::begin(['fieldConfig' => ['template' => '{label}{input}'], 'validateOnSubmit' => false, 'id' => 'formAsignacion']);

echo Html::input('hidden', 'listar', 'false');
echo Html::input('hidden', 'consulta', $consulta, ['id' => 'consulta']);
echo Html::input('hidden', 'grabar', true);


//campos no modificables pero requeridos
echo $form->field($model, 'orden')->input('hidden', ['class' => 'hidden'])->label(false);
?>
<div class="form-asignacion">
	<h1>Asignaciones</h1>

	<div class="form" style="padding:10px;">
		<table border="0" width="100%">
			<tr>
				<td width="60px"><label>Tributo: </label></td>
				<td width="5px"></td>
				<td colspan="6">
				<?php
				$condicion = 'tobj <> 0 And trib_id In (Select trib_id From item Where tipo In (2,3,4))';

				if($consulta !== 0)
					echo $form->field($model, 'trib_id')->input('hidden', ['class' => 'hidden'])->label(false);

				echo $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib', 'trib_id', 'nombre', 0, $condicion), ['onchange' => 'cambiaTributo($(this).val());', 'id' => 'dlTributo', 'prompt' => '', 'style' => 'width:500px;'])->label(false);
				?>
				</td>
			</tr>
			<tr>
				<td><label>Objeto: </label></td>
				<td></td>
				<td colspan="3">
					<?php
					Pjax::begin(['id' => 'pjaxTObj', 'enableReplaceState' => false, 'enablePushState' => false]);
					Pjax::begin(['id' => 'pjaxObjeto', 'enableReplaceState' => false, 'enablePushState' => false]);

					$obj_id = trim(Yii::$app->request->get('obj_id_nuevo', ''));
					$trib_id = intval(Yii::$app->request->get('trib_id', 0));
					$tipoObjeto = utb::getTObjTrib($model->trib_id);

					if($trib_id > 0) $tipoObjeto = utb::getTObjTrib($trib_id);

					if($obj_id != '' && $trib_id > 0){

						if(strlen($obj_id) < 8 && strlen($obj_id) > 0){

							$letra = utb::getCampo('objeto_tipo', "cod = $tipoObjeto", 'letra');

							$obj_id = $letra . str_pad($obj_id, 7, '0', STR_PAD_LEFT);
						}
					} else if($obj_id == '') $obj_id = $model->obj_id;

					$model->obj_id = $obj_id;
					$nombreObjeto = utb::getNombObj("'$model->obj_id'");

					echo $form->field($model, 'obj_id', ['options' => ['style' => 'display:inline-block;']])
					->textInput(['id' => 'codigoObjeto', 'onchange' => 'cambiaObjeto($(this).val());', 'style' => 'width:70px;', 'disabled' => ($consulta !== 0 || $tipoObjeto <= 0)])
					->label(false);


					echo Html::button('<i class="glyphicon glyphicon-search"></i>',
									['class' => 'bt-buscar',
									'data-toggle' => 'modal', 'data-target' => '#modalBusquedaObjeto',
									'disabled' => ($consulta !== 0 || $tipoObjeto <= 0),
									'id' => 'botonModalBusquedaObjeto'
									]);


					echo Html::textInput(null, $nombreObjeto, ['style' => 'width:400px;', 'id' => 'nombreObjeto', 'class' => 'form-control solo-lectura']);

					Modal::begin([
						'id' => 'modalBusquedaObjeto',
						'header'=>'<h2>Búsqueda de objeto</h2>',
						'toggleButton' => false,

						'closeButton' => [
						  'label' => '<b>X</b>',
						  'class' => 'btn btn-danger btn-sm pull-right',
						],
						'size' => 'modal-lg'
					]);

					echo $this->render('//objeto/objetobuscarav', ['idpx' => 'Busca', 'txCod' => 'codigoObjeto', 'txNom' => 'nombreObjeto', 'id' => 'lisOpTribacc', 'tobjeto' => $tipoObjeto, 'selectorModal' => '#modalBusquedaObjeto']);

					Modal::end();
					Pjax::end();


					if($trib_id > 0){

						?>
						<script>
						$(document).ready(function(){

						<?php

						//se habilita el campo subcta si usa subcta
						$usa = utb::getCampo('trib', "trib_id = $trib_id", 'uso_subcta');
						if($usa){
							?>
							$("#subcta").prop("disabled", false);
							<?php
						}

						if($obj_id === null || trim($obj_id) == '' || utb::getTObj($obj_id) != $tipoObjeto){

						?>

						$("#codigoObjeto").val("");
						$("#nombreObjeto").val("");
						<?php
						}
						?>
						});
						</script>
						<?php
					}

					echo Html::input('hidden', null, null);
					Pjax::end();
					?>
				</td>
				<td></td>
				<td><label>Cta: </label></td>
				<td><?= $form->field($model, 'subcta', ['options' => ['style' => 'margin-bottom:0px;']])->textInput(['style' => 'width:50px;', 'maxlength' => 2, 'readonly', 'id' => 'subcta', 'disabled' => true])->label(false); ?></td>
				<td width="100px"></td>
			</tr>


			<tr>
				<td><label>Ítem: </label></td>
				<td></td>
				<td colspan="6">
				<?php
				Pjax::begin(['id' => 'pjaxItem', 'enableReplaceState' => false, 'enablePushState' => false]);

				$items = $consulta === 0 ? [] : utb::getAux('item i, item_tipo t', 'i.item_id', "(i.nombre || ' (' || t.nombre || ')')", 0, "t.cod In (2, 3, 4) And i.trib_id = $model->trib_id And i.tipo = t.cod");;
				$trib_id = intval(Yii::$app->request->get('cod', 0));

				if($trib_id > 0){
					$items = utb::getAux('item i, item_tipo t', 'i.item_id', "(i.nombre || ' (' || t.nombre || ')')", 0, "t.cod In (2, 3, 4) And i.trib_id = $trib_id And i.tipo = t.cod");

					$usaSubcta = filter_var(utb::getCampo('trib', "trib_id = $trib_id", 'uso_subcta'), FILTER_VALIDATE_BOOLEAN);

					?>
					<script>
					$(document).ready(function(){
						$("#subcta").prop("readonly", <?= $usaSubcta ? 'false' : 'true'?>);
					});
					</script>
					<?php
				}else {
					?>
					<script>
					$(document).ready(function(){
						$("#subcta").prop("readonly", true);
					});
					</script>
					<?php
				}

				if($consulta !== 0)
					echo $form->field($model, 'item_id')->input('hidden', ['class' => 'hidden'])->label(false);

				echo $form->field($model, 'item_id')
				->dropDownList($items, ['prompt' => '', 'disabled' => ($trib_id <= 0 || $consulta !== 0), 'id' => 'dlItem', 'style' => 'width:500px;', 'onchange' => 'cambiaItem();'])
				->label(false);

				echo Html::input('hidden', null, null);
				Pjax::end();
				?>
				</td>
			</tr>

			<tr>
				<td><label>Período: </label></td>
				<td></td>
				<td width="150px">
					<label>Desde:</label>
					<?php
					echo $form->field($model, 'adesde', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 4, 'onchange' => 'cambiaItem()'])->label(false);
					echo $form->field($model, 'cdesde', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 3, 'onchange' => 'cambiaItem()'])->label(false);
					?>
				</td>
				<td width="5px"></td>
				<td width="350px">
					<label>Hasta:</label>
					<?php
					echo $form->field($model, 'ahasta', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 4, 'id' => 'ahasta', 'onchange' => 'cambiaItem()'])->label(false);
					echo $form->field($model, 'chasta', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 3, 'id' => 'chasta', 'onchange' => 'cambiaItem()'])->label(false);
					?>
				</td>
			</tr>

			<tr>
				<td><label>Parám: </label></td>
				<td>
					<?php
					Pjax::begin(['id' => 'pjaxParametros', 'enableReplaceState' => false, 'enablePushState' => false]);

					$item_id = intval(Yii::$app->request->get('item_id', 0));
					$trib_id = intval(Yii::$app->request->get('trib_id', 0));
					$perdesde = intval(Yii::$app->request->get('perdesde', 0));
					$perhasta = intval(Yii::$app->request->get('perhasta', 0));

					if($item_id > 0 && $trib_id > 0){
					//ver como scar la consulta de la vista o usar alguna funcion de utb
						$periodoActual = utb::PerActual($trib_id);
						$sql = "Select Case When position('Param1' In nombre) > 0 Then 1 Else 0 End As usaparam1," .
								" Case When position('Param2' In nombre) > 0 Then 1 Else 0 End As usaparam2" .
								" From item_tfcalculo Where cod In (Select tcalculo From item_vigencia Where " .
								"item_id = $item_id And $perdesde BETWEEN perdesde and perhasta And $perhasta BETWEEN perdesde and perhasta)";

						$datos = Yii::$app->db->createCommand($sql)->queryOne();
						
						if( count($datos) != 0 ){
							?>
							<script type="text/javascript">
							$(document).ready(function(){
								$("#param1").prop("disabled", !<?= ($datos['usaparam1'] ? 'true' : 'false') ?>);
								$("#param2").prop("disabled", !<?= ($datos['usaparam2'] ? 'true' : 'false') ?>);
							});
							</script>
							<?php
						}
					}

					Pjax::end();
					?>
				</td>
				<td>
					<label>Param1: </label>
					<?= $form->field($model, 'param1', ['options' => ['style' => 'display:inline-block; width:50px;', 'maxlength' => 6]])->textInput(['style' => 'width:50px;', 'id' => 'param1', 'disabled' => true])->label(false); ?>
				</td>
				<td></td>
				<td>
					<label>Param2: </label>
					<?= $form->field($model, 'param2', ['options' => ['style' => 'display:inline-block; width:50px;', 'maxlength' => 6]])->textInput(['style' => 'width:50px;', 'id' => 'param2', 'disabled' => true])->label(false); ?>
				</td>
			</tr>

			<tr>
				<td><label>Expediente: </label></td>
				<td></td>
				<td><?= $form->field($model, 'expe')->textInput(['maxlength' => 12, 'id' => 'expe'])->label(false); ?></td>
			</tr>

			<tr>
				<td valign="top"><label>Obs: </td>
				<td></td>
				<td colspan="6"><?= $form->field($model, 'obs')->textarea(['style' => 'width:500px; max-width:500px; height:75px; max-height:75px;', 'maxlength' => 250, 'id' => 'obs'])->label(false); ?></td>
			</tr>
		</table>

		<table border="0" width="760px">

		<tr>
			<td colspan='2'>
				<br><div id="error" style="display:none;" class="alert alert-danger alert-dismissable"></div>
			</td>
		</tr>
		</table>
	</div>
</div>

<div style="margin-top:5px;">
<?php
/*
 * se dibujan los botones de grabar, cancelar y demas dependiendo del tipo de consulta
 */

 //almacena los botones que se van a dibujar
$botones = [];


switch($consulta){

	case 0:
		$botonAceptar = Html::button('Grabar',['class' => 'btn btn-success', 'onclick' => '$("#formAsignacion").submit();']);
		$botonCancelar = Html::a('Cancelar', ['asig', 'listar' => false, 'c' => 1], ['class' => 'btn btn-primary']);
		$botonVolver = Html::a('Volver', ['asig', '//ctacte/listadotribacc' => true], ['class' => 'btn btn-primary pull-right']);

		array_push($botones, $botonAceptar, $botonCancelar, $botonVolver);
		break;

	case 3:
		$botonAceptar = Html::button('Grabar',['class' => 'btn btn-success', 'onclick' => '$("#formAsignacion").submit();']);
		$botonCancelar = Html::a('Cancelar',
								['asig', 'listar' => false, 'obj_id' => $model->obj_id, 'subcta' => $model->subcta, 'orden' => $model->orden,
								'trib_id' => $model->trib_id, 'item_id' => $model->item_id, 'perdesde' => $model->perdesde, 'c' => 1],
								['class' => 'btn btn-primary']);

		array_push($botones, $botonAceptar, $botonCancelar);
		break;

	case 1:

		$botonAceptar = Html::a('Nuevo', ['asig', 'listar' => false, 'c' => 0], ['class' => 'btn btn-success']);
		$botonModificar = Html::a('Modificar',
								['asig', 'listar' => false, 'obj_id' => $model->obj_id, 'subcta' => $model->subcta,
								'orden' => $model->orden, 'trib_id' => $model->trib_id, 'item_id' => $model->item_id, 'perdesde' => $model->perdesde, 'c' => 3],
								['class' => 'btn btn-primary', 'disabled' => ($model->trib_id <= 0)]);
		$botonEliminar = Html::a('Eliminar', ['asig', 'listar' => false, 'obj_id' => $model->obj_id, 'subcta' => $model->subcta,
								'orden' => $model->orden, 'trib_id' => $model->trib_id, 'item_id' => $model->item_id, 'perdesde' => $model->perdesde, 'c' => 2],
								['class' => 'btn btn-danger', 'disabled' => ($model->trib_id <= 0)]);

		$botonVolver = Html::a('Volver', ['asig', '//ctacte/listadotribacc' => true], ['class' => 'btn btn-primary pull-right']);

		Yii::$app->session['CondImpAsig'] = "obj_id='" . $model->obj_id . "' and ";
        Yii::$app->session['CondImpAsig'] .= "subcta=" . $model->subcta . " and ";
        Yii::$app->session['CondImpAsig'] .= "orden=" . $model->orden . " and ";
        Yii::$app->session['CondImpAsig'] .= "trib_id=" . $model->trib_id . " and ";
        Yii::$app->session['CondImpAsig'] .= "item_id=" . $model->item_id . " and ";
        Yii::$app->session['CondImpAsig'] .= "perdesde=" . $model->perdesde;

		$botonImprimir = Html::a('Imprimir', ['imprimirasig'], ['class' => 'btn btn-success','target'=>'_black', 'disabled' => ($model->trib_id <= 0)]);

		array_push($botones, $botonAceptar, $botonModificar, $botonEliminar, $botonVolver,$botonImprimir);
		break;

	case 2:
		$botonAceptar = Html::button('Eliminar',['class' => 'btn btn-danger', 'onclick' => '$("#formAsignacion").submit();']);
		$botonCancelar = Html::a('Cancelar',
								['asig', 'listar' => false, 'obj_id' => $model->obj_id, 'subcta' => $model->subcta, 'orden' => $model->orden,
								'trib_id' => $model->trib_id, 'item_id' => $model->item_id, 'perdesde' => $model->perdesde, 'c' => 1],
								['class' => 'btn btn-primary']);

		array_push($botones, $botonAceptar, $botonCancelar);
		break;
}

foreach($botones as $b){

	echo $b;
	echo '&nbsp;';
}

?>
</div>


<?php
ActiveForm::end();

?>

<div style="margin-top:10px;">

<?php
echo $form->errorSummary($model);

if(isset($mensaje) && $mensaje != '')
	echo Alert::widget([
		'id' => 'alertaMensaje',
		'options' => ['class' => 'alert-success alert-dissmisible'],
		'body' => $mensaje
	]);

	?>
	<script type="text/javascript">
	setTimeout(function(){$("#alertaMensaje").fadeOut();}, 5000);
	</script>
	<?php
?>
</div>

<script type="text/javascript">
function cambiaItem(){

	nuevo = $("#dlItem").val();
	var trib_id = $("#dlTributo").val();

	if(isNaN(nuevo) || nuevo <= 0){
		console.log("el nuevo item esta mal: " + nuevo);
		$("#param1").val("");
		$("#param2").val("");

		$("#param1").prop("disabled", true);
		$("#param2").prop("disabled", true);
		return;
	}

	$("#dlItem").val(nuevo);

	$.pjax.reload({
		container : "#pjaxParametros",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"item_id" : nuevo,
			"trib_id" : trib_id,
			"perdesde" : parseInt($("#tribacc-adesde").val()) * 1000 + parseInt($("#tribacc-cdesde").val()),
			"perhasta" : parseInt($("#ahasta").val()) * 1000 + parseInt($("#chasta").val())
		},
		timeout:10000
	});
}

function cambiaObjeto(nuevo){

	$("#nombreObjeto").val("");
	var trib_id = $("#dlTributo").val();

	$.pjax.reload({
		container : "#pjaxObjeto",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"obj_id_nuevo" : nuevo,
			"trib_id" : trib_id
		},
		timeout:10000
	});
}

function cambiaTributo(nuevo, item_id){

	nuevo = parseInt(nuevo);

	if(isNaN(nuevo) || nuevo <= 0){
		$("#dlItem").prop("disabled", true);
		$("#codigoObjeto").prop("disabled", true);
		$("#botonModalBusquedaObjeto").prop("disabled", true);
		return;
	}

	$.pjax.reload({
		container : "#pjaxItem",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"cod" : nuevo
		},
		timeout:10000
	});

	$("#pjaxItem").on("pjax:end", function(){

		var obj_id = $("#codigoObjeto").val();
		var trib_id= $("#dlTributo").val();

		$.pjax.reload({
			container : "#pjaxTObj",
			replace : false,
			push : false,
			type : "GET",
			data : {
				"trib_id" : nuevo,
				"obj_id_nuevo" : obj_id
			},
			timeout:10000
		});

		$("#pjaxItem").off("pjax:end");
	});

	if(item_id){

		$("#pjaxTObj").on("pjax:complete", function(){

			item_id= parseInt(item_id);

			if(!isNaN(item_id) && item_id > 0)
				cambiaItem();

			$("#pjaxTObj").off("pjax:complete");
		});
	}
}

$(document).ready(function(){


<?php
if($consulta === 3) {

	?>
	DesactivarFormPost("formAsignacion");

	$("#ahasta").prop("readonly", false);
	$("#chasta").prop("readonly", false);
	$("#param1").prop("readonly", false);
	$("#param2").prop("readonly", false);
	$("#expe").prop("readonly", false);
	$("#obs").prop("readonly", false);
	<?php
} else if($consulta !== 0){
	?>
	DesactivarFormPost("formAsignacion");
	<?php
}

if(in_array($consulta, [0, 3]) && $model->trib_id !== null && trim($model->trib_id) != ''){
	?>
	cambiaTributo("<?= $model->trib_id ?>", "<?= $model->item_id ?>");
	<?php
}
?>
});

$(document).on("pjax:error", function(xhr, textStatus, error, options){
	xhr.preventDefault();
	xhr.stopPropagation();
});
</script>
