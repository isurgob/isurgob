<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

use app\utils\db\utb;
use app\utils\db\Fecha;

$this->params['breadcrumbs'][] = ['label' => 'Listado de excepciones', 'url' => ['//ctacte/listadotribacc', 'tipo' => 'excepcion']];
$this->params['breadcrumbs'][] = ['label' => 'Excepciones'];


//para que cargue el archivo javascript
echo GridView::widget([
	'dataProvider' => new ArrayDataProvider(['allModels' => []]),
	'options' => ['class' => 'hidden']
]);
?>
<?php
$form = ActiveForm::begin(['fieldConfig' => ['template' => '{label}{input}'], 'validateOnSubmit' => false, 'id' => 'formExcepcion']);

echo Html::input('hidden', 'listar', 'false');
echo Html::input('hidden', 'consulta', $consulta, ['id' => 'consulta']);
echo Html::input('hidden', 'grabar', 'true');
?>
<div class="form-inscripcion">
	<h1>Excepciones</h1>
	<style>#asd td{border:1px solid;}</style>
	<div class="form" style="padding:10px;">
		<table border="0" width="100%">
			<tr>
				<td width="80px"><label>Tributo: </label></td>
				<td width="5px"></td>
				<td colspan="6">
				<?php
				$condicion = "(tobj <> 0 or trib_id=1) and est = 'A' and (tipo in(1,2,3,4,5) or (tipo = 0 and trib_id not in(2,4,6,10)))";
				
				echo $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib', 'trib_id', 'nombre', 0, $condicion), ['onchange' => 'cambiaTributo($(this).val());', 'id' => 'dlTributo', 'prompt' => '', 'style' => 'width:500px;'])->label(false); 
				?>
				</td>
				<td colspan='2'>
					<?= $form->field($model, 'pornum')->checkbox(['label' => 'Por NUM', 'onclick' => 'cambiaTributo($("#dlTributo").val());','id' => 'porNum'])  ?>
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
					$porNum = intval(Yii::$app->request->get('porNum', 0));
					
					$tipoObjeto= 0;
					
					if($trib_id > 0) $tipoObjeto = (integer)utb::getTObjTrib($trib_id);
					if($porNum == 1) $tipoObjeto = 3;
					
					if($obj_id != '' && $trib_id > 0){
						
						if(strlen($obj_id) < 8 && strlen($obj_id) > 0){

							$letra = utb::getCampo('objeto_tipo', "cod = $tipoObjeto", 'letra');

							$obj_id = $letra . str_pad($obj_id, 7, '0', STR_PAD_LEFT);
						}
					} else if($obj_id == '') $obj_id = $model->obj_id;
					
					$model->obj_id = $obj_id;
					$nombreObjeto = utb::getNombObj("'$model->obj_id'");
					
					echo $form->field($model, 'obj_id', ['options' => ['style' => 'display:inline-block;']])
					->textInput(['id' => 'codigoObjeto', 'onchange' => 'cambiaObjeto($(this).val());', 'style' => 'width:70px;', 'disabled' => ($consulta !== 0 || $tipoObjeto <= 0)])->label(false); 
				
					
					echo Html::button('<i class="glyphicon glyphicon-search"></i>', 
									['class' => 'bt-buscar', 'id' => 'botonObjeto',
									'data-toggle' => 'modal', 'data-target' => '#modalBusquedaObjeto',
									'disabled' => ($consulta !== 0 || $tipoObjeto <= 0)
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
						
						if($obj_id === null || trim($obj_id == '') || utb::getTObj($obj_id) != $tipoObjeto){
						
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
				
				
				<td><label>Cta:</label></td>
				<td><?= $form->field($model, 'subcta', ['options' => ['style' => 'margin-bottom:0px;']])->textInput(['style' => 'width:50px;', 'maxlength' => 2, 'id' => 'subcta', 'disabled' => true])->label(false) ?></td>
			</tr>
			
			
			<tr>
				<td><label>Tipo: </label></td>
				<td></td>
				<td colspan="6">
				<?php
				Pjax::begin(['id'=>'PjaxTipo']);
					$trib_id = intval(Yii::$app->request->get('trib_id', $model->trib_id));
					$ttrib = utb::getTTrib($trib_id);
										
					$items = utb::getAux('ctacte_tcta', 'cod', 'nombre', 0, $ttrib == 2 ? 'cod In (2, 3, 4)' : 'cod In (2, 3)', 'cod', false, "Union Select 5 As cod, 'Liquidación' As nombre");	
					
					echo $form->field($model, 'tipo_id')->dropDownList($items, ['prompt' => '', 'id' => 'dlItem', 'style' => 'width:500px;'])->label(false);
				Pjax::end();
				?>
				</td>
				<td width="5px"></td>
				<td></td>
			</tr>
			
			<tr>
				<td><?php
				if($consulta !== 1 && $consulta !== 2)
					 echo $form->field($model, 'poranio')->checkbox(['label' => 'Por Año: ', 'onclick' => 'porAnioClick($(this));', 'value' => 1, 'uncheck' => 0, 'id' => 'porAnio']) 
				?>
				</td>
				<td></td>
				<td><label>A&ntilde;o:</label></td>
				<td>
					<?php
					echo $form->field($model, 'anio', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:80px;', 'maxlength' => 6, 'id' => 'anio','onchange'=>'FechaVenc($("#alVenc"));'])->label(false);
					?>
				</td>
				<td width="5px"></td>
				<td><label>Cuota:</label></td>
				<td>
					<?php
					echo $form->field($model, 'cuota', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 3, 'id' => 'cuota','onchange'=>'FechaVenc($("#alVenc"));'])->label(false);
					?>
				</td>
				<td width="170px"></td>
			</tr>
			
			<tr>
				<td><label>Fecha: </label></td>
				<td></td>
				<td><label><?= $form->field($model, 'alvenc')->checkbox(['label' => 'Al vencimiento Usar:', 'value' => 1, 'unckeck' => 0, 'id' => 'alVenc','onclick' => 'FechaVenc($(this))']); ?></label></td>
				<td>
					<?php
					if($consulta === 1 || $consulta === 2){
						
						$fecha = null;

						if($model->fchusar !== null && trim($model->fchusar) != '')
							$fecha = Fecha::bdToUsuario($model->fchusar);
						
						echo Html::textInput(null, $fecha, ['class' => 'form-control solo-lectura','style' => 'display:inline-block; width:80px;']);
					}
					else
						echo DatePicker::widget(['model' => $model, 'attribute' => 'fchusar', 'dateFormat' => 'dd/MM/yyyy', 
							'options' => ['class' => 'form-control', 'style' => 'display:inline-block; width:80px;', 'maxlength' => 10, 'id' => 'fechaAUsar']]);
						
					?>
				</td>
				<td></td>
				<td><label>L&iacute;mite: </label></td>
				<td>
					<?php
					if($consulta === 1 || $consulta === 2){
						
						$fecha = null;
						
						if($model->fchlimite !== null && trim($model->fchlimite) != '')
							$fecha = Fecha::bdToUsuario($model->fchlimite);
						
						echo Html::textInput(null, $fecha, ['class' => 'form-control solo-lectura','style' => 'display:inline-block; width:80px;']);
					}
					else
						echo DatePicker::widget(['model' => $model, 'attribute' => 'fchlimite', 'dateFormat' => 'dd/MM/yyyy', 
						'options' => ['class' => 'form-control', 'style' => 'display:inline-block; width:80px;', 'maxlength' => 10, 'id' => 'fechaLimite']]);
						
					?>
				</td>
				
			</tr>
			
			<tr>
				<td><label>Expediente: </label></td>
				<td></td>
				<td colspan="2"><?= $form->field($model, 'expe')->textInput(['id' => 'expe', 'maxlength' => 12, 'style' => 'width:100%;'])->label(false); ?></td>
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
		$botonAceptar = Html::button('Grabar',['class' => 'btn btn-success', 'onclick' => '$("#formExcepcion").submit();']);
		$botonCancelar = Html::a('Cancelar', ['excep', 'listar' => false, 'c' => 1], ['class' => 'btn btn-primary']);
		$botonVolver = Html::a('Volver', ['excep', 'listar' => true], ['class' => 'btn btn-primary pull-right']);
		
		array_push($botones, $botonAceptar, $botonCancelar, $botonVolver);
		break;
	
	case 3:
		$botonAceptar = Html::button('Grabar',['class' => 'btn btn-success', 'onclick' => '$("#formExcepcion").submit();']);
		$botonCancelar = Html::a('Cancelar', 
								['excep', 'listar' => false, 'excep_id' => $model->excep_id, 'c' => 1], 
								['class' => 'btn btn-primary']);
								
		array_push($botones, $botonAceptar, $botonCancelar);
		break;
		
	case 1:
		$botonAceptar = Html::a('Nuevo', ['excep', 'listar' => false, 'c' => 0], ['class' => 'btn btn-success']);
		$botonModificar = Html::a('Modificar', 
								['excep', 'listar' => false, 'excep_id' => $model->excep_id, 'c' => 3], 
								['class' => 'btn btn-primary', 'disabled' => ($model->trib_id <= 0)]);
		$botonEliminar = Html::a('Eliminar', ['excep', 'listar' => false, 'excep_id' => $model->excep_id, 'c' => 2], 
								['class' => 'btn btn-danger', 'disabled' => ($model->trib_id <= 0)]);
								
		$botonVolver = Html::a('Volver', ['excep', 'listar' => true], ['class' => 'btn btn-primary pull-right']);
		
		array_push($botones, $botonAceptar, $botonModificar, $botonEliminar, $botonVolver);
		break;
	
	case 2:
		$botonAceptar = Html::button('Eliminar',['class' => 'btn btn-danger', 'onclick' => '$("#formExcepcion").submit();']);
		$botonCancelar = Html::a('Cancelar', 
								['excep', 'listar' => false, 'excep_id' => $model->excep_id, 'c' => 1], 
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
$("#fechaAUsar").prop("readonly", $("#alVenc").is(":checked"));

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
			"trib_id" : trib_id,
			"porNum" : ($("#porNum").is(":checked") ? 1 : 0)
		},
		timeout : 20000
	});
	
}

function cambiaTributo(nuevo){
	
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
	
	var obj_id = $("#codigoObjeto").val();

	$.pjax.reload({
		container : "#pjaxTObj",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"trib_id" : nuevo,
			"obj_id_nuevo" : obj_id,
			"porNum" : ($("#porNum").is(":checked") ? 1 : 0)
		}
	});
	
	FechaVenc($("#alVenc"));
	
	$("#pjaxTObj").on("pjax:complete", function(){
		
		$.pjax.reload({
			container : "#PjaxTipo",
			replace : false,
			push : false,
			type : "GET",
			data : {
				"trib_id" : nuevo,
				"porNum" : ($("#porNum").is(":checked") ? 1 : 0)
			},
			timeout : 20000
		});
		
		$("#pjaxTObj").off("pjax:complete");
	});
}

function porAnioClick($check){
	
	var c = $check.is(":checked");
	
	$("#cuota").prop("readonly", c);
	$("#cuota").val("");
}

function FechaVenc($check)
{
	$("#fechaAUsar").prop("readonly", $check.is(":checked"));
	
	if ($check.is(":checked")){
		$("#fechaAUsar").css("pointer-events","none");
		$.post("<?= BaseUrl::toRoute(['vencimiento']) ?>", 
			{trib:$("#dlTributo").val(),anio:$("#anio").val(),cuota:$("#cuota").val(),obj:$("#codigoObjeto").val()},
			function(data){
				$("#fechaAUsar").val(data);
			}
		);
		
	}else {
		$("#fechaAUsar").css("pointer-events","all");
	}
}

$(document).ready(function(){
	

<?php
if($consulta === 3) {
?>
	DesactivarFormPost("formExcepcion");
	
	$("#dlItem").prop("readonly", false);
	$("#dlItem").prop("disabled", false);
	$("#subcta").prop("readonly", false);
	$("#cuota").prop("readonly", false);
	$("#anio").prop("readonly", false);
	$("#porAnio").prop("disabled", false);
	$("#porNum").prop("disabled", false);
	$("#fechaAUsar").prop("readonly", false);
	$("#fechaLimite").prop("readonly", false);
	$("#expe").prop("readonly", false);
	$("#obs").prop("readonly", false);
	$("#alVenc").prop("disabled", false);
	$("#alVenc").prop("readonly", false);
	<?php
} else if($consulta !== 0){
	
	?>
	DesactivarFormPost("formExcepcion");
	<?php
} else if ($consulta == 0){
?>
	$("#cuota").prop("readonly", $("#porAnio").is(":checked"));
<?php	
}

if(in_array($consulta, [0, 3]) && $model->trib_id !== null && trim($model->trib_id) != '') echo "cambiaTributo($model->trib_id)";
?>
});

$(document).on("pjax:error", function(xhr, textStatus, error, options){
	xhr.preventDefault();
	xhr.stopPropagation();
});

</script>