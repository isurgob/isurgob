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
use app\utils\db\Fecha;

$this->params['breadcrumbs'][] = ['label' => 'Listado de inscripciones', 'url' => ['//ctacte/listadotribacc', 'tipo' => 'inscripcion']];
$this->params['breadcrumbs'][] = ['label' => 'Inscripción a tributos'];
?>
<?php
$form = ActiveForm::begin(['fieldConfig' => ['template' => '{label}{input}'], 'validateOnSubmit' => false, 'id' => 'formInscripcion']);

echo Html::input('hidden', 'listar', 'false');
echo Html::input('hidden', 'consulta', $consulta, ['id' => 'consulta']);
echo Html::input('hidden', 'grabar', 'true');
?>
<div class="form-inscripcion">
	<h1>Inscripci&oacute;n a tributos</h1>
	<style>#asd td{border:1px solid;}</style>
	<div class="form" style="padding:10px;">
		<table border="0" width="100%">
			<tr>
				<td width="60px"><label>Tributo: </label></td>
				<td width="5px"></td>
				<td colspan="5">
				<?php
				$condicion = "tobj <> 0 And est = 'A' And inscrip_req = 1";

				echo $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib', 'trib_id', 'nombre', 0, $condicion), ['onchange' => 'cambiaTributo($(this).val());', 'id' => 'dlTributo', 'prompt' => '', 'style' => 'width:500px;'])->label(false);
				?>
				</td>
			</tr>
			<tr>
				<td><label>Objeto: </label></td>
				<td></td>
				<td colspan="6">
					<?php
					Pjax::begin(['id' => 'pjaxTObj', 'enableReplaceState' => false, 'enablePushState' => false]);
					Pjax::begin(['id' => 'pjaxObjeto', 'enableReplaceState' => false, 'enablePushState' => false]);

					$obj_id = trim(Yii::$app->request->get('obj_id_nuevo', ''));
					$trib_id = intval(Yii::$app->request->get('trib_id', 0));
					$tipoObjeto= 0;

					if($trib_id > 0)
						$tipoObjeto = utb::getTObjTrib($trib_id);

					if($obj_id != '' && $trib_id > 0){

						if(strlen($obj_id) < 8 && strlen($obj_id) > 0){

							$tipoObjeto = utb::getTObjTrib($trib_id);
							$letra = utb::getCampo('objeto_tipo', "cod = $tipoObjeto", 'letra');

							$obj_id = $letra . str_pad($obj_id, 7, '0', STR_PAD_LEFT);
						}
					} else if($obj_id == '') $obj_id = $model->obj_id;

					$model->obj_id = $obj_id;
					$nombreObjeto = utb::getNombObj("'$model->obj_id'");

					echo $form->field($model, 'obj_id', ['options' => ['style' => 'display:inline-block;']])
					->textInput(['id' => 'codigoObjeto', 'onchange' => 'cambiaObjeto($(this).val());', 'style' => 'width:70px;', 'readonly' => (in_array($consulta, [1, 2, 3]) || $tipoObjeto <= 0)])->label(false);


					echo Html::button('<i class="glyphicon glyphicon-search"></i>',
									['class' => 'bt-buscar', 'id' => 'botonObjeto',
									'data-toggle' => 'modal', 'data-target' => '#BuscaObjlistOpTribacc',
									'disabled' => (in_array($consulta, [1, 2]) || $tipoObjeto <= 0)
									]);


					echo Html::textInput(null, $nombreObjeto, ['style' => 'width:400px;', 'id' => 'nombreObjeto', 'class' => 'form-control solo-lectura']);

					Modal::begin([
						'id' => 'BuscaObjlistOpTribacc',
						'header'=>'<h2>Búsqueda de objeto</h2>',
						'toggleButton' => false,

						'closeButton' => [
						  'label' => '<b>X</b>',
						  'class' => 'btn btn-danger btn-sm pull-right',
						],
						'size' => 'modal-lg'
					]);

					echo $this->render('//objeto/objetobuscarav', ['idpx' => 'Busca', 'txCod' => 'codigoObjeto', 'txNom' => 'nombreObjeto', 'id' => 'lisOpTribacc', 'tobjeto' => $tipoObjeto]);

					Modal::end();
					Pjax::end();


					if($trib_id > 0){

						?>
						<script>
						$(document).ready(function(){

						<?php
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

					Pjax::end();
					?>
				</td>


			</tr>


			<tr>
				<td><label>Categoría: </label></td>
				<td></td>
				<td colspan="5">
				<?php
				Pjax::begin(['id' => 'pjaxItem', 'enableReplaceState' => false, 'enablePushState' => false]);

				$items = [];
				$trib_id = Yii::$app->request->get('cod', -1);

				if($trib_id > 0) $items = utb::getAux('objeto_trib_cat', 'cat', 'nombre', 0, 'trib_id = ' . $trib_id);

				echo
					$form->field($model, 'cat')->dropDownList($items, [
						'prompt' => '',
						'disabled' => ($trib_id <= 0 || ($consulta === 1 || $consulta === 2)),
						'style' => 'width:500px;',
						'id' => 'dlItem',
						'onchange'	=> 'f_cambiaCategoria()',
					])->label(false);

				Pjax::end();


				?>
				</td>
			</tr>

			<tr>
				<td valign="top"><label>Descripción: </td>
				<td></td>
				<td colspan="6">
				<?php
				Pjax::begin(['id' => 'pjaxCategoria', 'enableReplaceState' => false, 'enablePushState' => false]);

					echo
						Html::textarea( 'txDescripcion', utb::getCampo( 'objeto_trib_cat', "cat = '" . Yii::$app->request->get( 'cat_categoria', '0' ) . "'" ), [
							'style' => 'width:500px; max-width:500px; height:65px; max-height:65px;',
							'maxlength' => 250,
							'id' => 'descripcion',
							'class'	=> 'form-control solo-lectura',
							'tabIndex'	=> '-1',
						]);

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
					echo $form->field($model, 'adesde', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 4, 'id' => 'adesde'])->label(false);
					echo $form->field($model, 'cdesde', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 3, 'id' => 'cdesde'])->label(false);
					?>
				</td>
				<td width="5px"></td>
				<td width="350px">
					<label>Hasta:</label>
					<?php
					echo $form->field($model, 'ahasta', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 4, 'id' => 'ahasta'])->label(false);
					echo $form->field($model, 'chasta', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 3, 'id' => 'chasta'])->label(false);
					?>
				</td>
			</tr>

			<tr>
				<td><label>Fecha: </label></td>
				<td></td>
				<td>
					<label>Alta: </label>
					<?php
					if($consulta === 1 || $consulta === 2){

						$alta = null;

						if($model->fchalta !== null && trim($model->fchalta) != '')
							$alta = Fecha::bdToUsuario($model->fchalta);

						echo Html::textInput(null, $alta, ['class' => 'form-control solo-lectura','style' => 'display:inline-block; width:80px;']);
					}
					else
						echo DatePicker::widget(['model' => $model, 'attribute' => 'fchalta', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'display:inline-block; width:80px;', 'maxlength' => 10, 'id' => 'fchalta']]);

					?>
				</td>
				<td></td>
				<td>
					<label>Base: </label>
					<?= $form->field($model, 'base', ['options' => ['style' => 'display:inline-block; width:50px;', 'maxlength' => 12]])->textInput(['style' => 'width:50px;', 'id' => 'base'])->label(false); ?>
				</td>

			</tr>

			<tr>
				<td><label>Expediente: </label></td>
				<td></td>
				<td><?= $form->field($model, 'expe')->textInput(['id' => 'expe', 'maxlength' => 12])->label(false); ?></td>
			</tr>

			<tr>
				<td valign="top"><label>Obs: </td>
				<td></td>
				<td colspan="6"><?= $form->field($model, 'obs')->textarea(['style' => 'width:500px; max-width:500px; height:75px; max-height:75px;', 'maxlength' => 250, 'id' => 'obs'])->label(false); ?></td>
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
		$botonAceptar = Html::button('Grabar',['class' => 'btn btn-success', 'onclick' => '$("#formInscripcion").submit();']);
		$botonCancelar = Html::a('Cancelar', ['inscrip', 'listar' => false, 'c' => 1], ['class' => 'btn btn-primary']);
		$botonVolver = Html::a('Volver', ['inscrip', 'listar' => true], ['class' => 'btn btn-primary pull-right']);

		array_push($botones, $botonAceptar, $botonCancelar);
		break;

	case 3:
		$botonAceptar = Html::button('Grabar',['class' => 'btn btn-success', 'onclick' => '$("#formInscripcion").submit();']);
		$botonCancelar = Html::a('Cancelar',
								['inscrip', 'listar' => false, 'obj_id' => $model->obj_id, 'subcta' => $model->subcta, 'orden' => $model->orden,
								'trib_id' => $model->trib_id, 'item_id' => $model->item_id, 'perdesde' => $model->perdesde, 'c' => 1],
								['class' => 'btn btn-primary']);

		array_push($botones, $botonAceptar, $botonCancelar);
		break;

	case 1:
		$botonAceptar = Html::a('Nuevo', ['inscrip', 'listar' => false, 'c' => 0], ['class' => 'btn btn-success']);
		$botonModificar = Html::a('Modificar',
								['inscrip', 'listar' => false, 'obj_id' => $model->obj_id, 'subcta' => $model->subcta,
								'orden' => $model->orden, 'trib_id' => $model->trib_id, 'item_id' => $model->item_id, 'perdesde' => $model->perdesde, 'c' => 3],
								['class' => 'btn btn-primary', 'disabled' => ($model->trib_id <= 0)]);
		$botonEliminar = Html::a('Eliminar', ['inscrip', 'listar' => false, 'obj_id' => $model->obj_id, 'subcta' => $model->subcta,
								'orden' => $model->orden, 'trib_id' => $model->trib_id, 'item_id' => $model->item_id, 'perdesde' => $model->perdesde, 'c' => 2],
								['class' => 'btn btn-danger', 'disabled' => ($model->trib_id <= 0)]);
		$botonImpCred = Html::a('Imprimir Credencial', ['credhabil','obj_id' => $model->obj_id, 'trib_id' => $model->trib_id, 'perdesde' => $model->perdesde],
								['class' => 'btn btn-success', 'target' => '_black', 'disabled' => ($model->trib_id <= 0)]);						
		$botonImp = Html::a('Imprimir Constancia', ['constancia','obj_id' => $model->obj_id, 'trib_id' => $model->trib_id, 'perdesde' => $model->perdesde],
								['class' => 'btn btn-success', 'target' => '_black', 'disabled' => ($model->trib_id <= 0)]);												

		$botonVolver = Html::a('Volver', ['inscrip', 'listar' => true], ['class' => 'btn btn-primary pull-right']);

		array_push($botones, $botonAceptar, $botonModificar, $botonEliminar, $botonImpCred, $botonImp, $botonVolver);
		break;

	case 2:
		$botonAceptar = Html::button('Eliminar',['class' => 'btn btn-danger', 'onclick' => '$("#formInscripcion").submit();']);
		$botonCancelar = Html::a('Cancelar',
								['inscrip', 'listar' => false, 'obj_id' => $model->obj_id, 'subcta' => $model->subcta, 'orden' => $model->orden,
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

function f_cambiaCategoria(){

	$.pjax.reload({

		container	: "#pjaxCategoria",
		type		: "GET",
		replace		: false,
		push		: false,
		data:{
			"cat_categoria"	: $( "#dlItem option:selected" ).val(),
		},
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
		}
	});
}

function cambiaTributo(nuevo){

	$( "#descripcion" ).val( "" );

	nuevo = parseInt(nuevo);

	if(isNaN(nuevo)){
		$("#dlItem").prop("disabled", true);
		$("#ckItem").prop("disabled", true);
		$("#codigoObjeto").prop("disabled", true);
		$("#botonObjeto").prop("disabled", true);
		return;
	}

	$("#ckItem").click();
	$("#ckItem").prop("disabled", false);

	$.pjax.reload({
		container : "#pjaxItem",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"cod" : nuevo
		}
	});

	$("#pjaxItem").on("pjax:end", function(){

		var obj_id = $("#codigoObjeto").val();

		$.pjax.reload({
			container : "#pjaxTObj",
			replace : false,
			push : false,
			type : "GET",
			data : {
				"trib_id" : nuevo,
				"obj_id_nuevo" : obj_id
			}
		});

		$("#dlItem").val("<?= $model->cat ?>");
		$("#pjaxItem").off("pjax:end");
	});
}

$(document).ready(function(){


<?php
if($consulta === 0) {
?>
	$("#adesde").val(<?= date('Y') ?>);
	$("#cdesde").val(<?= date('m') ?>);
	$("#ahasta").val(<?= date('Y') + 1?>);
	$("#chasta").val(<?= date('m')?>);
<?php 	
}
if($consulta === 3) {
?>
	DesactivarFormPost("formInscripcion");

	$("#codigoObjeto").prop("readonly", true);
	$("#adesde").prop("readonly", true);
	$("#cdesde").prop("readonly", true);
	$("#ahasta").prop("readonly", false);
	$("#chasta").prop("readonly", false);
	$("#fchalta").prop("readonly", false);
	$("#base").prop("readonly", false);
	$("#expe").prop("readonly", false);
	$("#obs").prop("readonly", false);
	<?php
} else if($consulta !== 0){
	?>
	DesactivarFormPost("formInscripcion");
	<?php
}

if($model->trib_id !== null && trim($model->trib_id) != '') echo "cambiaTributo($model->trib_id)";
?>
});

$(document).on("pjax:error", function(xhr, textStatus, error, options){
	xhr.preventDefault();
	xhr.stopPropagation();
});
</script>
