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

$this->params['breadcrumbs'][] = ['label' => 'Listado de condonaciones', 'url' => ['//ctacte/listadotribacc', 'tipo' => 'condona']];
$this->params['breadcrumbs'][] = ['label' => 'Condoncación'];
?>
<?php
$form = ActiveForm::begin(['fieldConfig' => ['template' => '{label}{input}'], 'validateOnSubmit' => false, 'id' => 'formCondonacion']);

echo Html::input('hidden', 'listar', 'false');
echo Html::input('hidden', 'consulta', $consulta, ['id' => 'consulta']);
echo Html::input('hidden', 'grabar', 'true');
?>
<div class="form-inscripcion">
	<h1>Condonaci&oacute;n</h1>
	
	<div class="form" style="padding:10px;">
		<table border="0" width="100%">
			<tr>
				<td width="60px"><label>Tributo: </label></td>
				<td width="5px"></td>
				<td colspan="7">
				<?php
				$condicion = "est = 'A' And tobj <> 0";
				
				if($consulta === 2)
					echo $form->field($model, 'trib_id')->input('hidden')->label(false);
				
				echo $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib', 'trib_id', 'nombre', 0, $condicion), 
					['onchange' => 'cambiaTributo($(this).val());', 'id' => 'dlTributo', 'prompt' => '', 'style' => 'width:500px;'])
					->label(false); 
				?>
				</td>
			</tr>
			<tr>
				<td><label>Objeto: </label></td>
				<td></td>
				<td colspan="4">					
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
							$letra = utb::getCampo('objeto_tipo', "cod = $tipoObjeto", 'letra');

							$obj_id = $letra . str_pad($obj_id, 7, '0', STR_PAD_LEFT);
						}
					} else if($obj_id == '') $obj_id = $model->obj_id;
					
					$model->obj_id = $obj_id;
					$nombreObjeto = utb::getNombObj("'$model->obj_id'");
					
					echo $form->field($model, 'obj_id', ['options' => ['style' => 'display:inline-block;']])
					->textInput(['id' => 'codigoObjeto', 'onchange' => 'cambiaObjeto($(this).val());', 'style' => 'width:70px;', 'disabled' => (in_array($consulta, [1, 2]) || $tipoObjeto <= 0)])->label(false); 
				
					
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
					
					echo $this->render('//objeto/objetobuscarav', ['idpx' => 'Busca', 'txCod' => 'codigoObjeto', 'txNom' => 'nombreObjeto', 'id' => 'lisOpTribacc', 'selectorModal' => '#BuscaObjlistOpTribacc', 'tobjeto' => $tipoObjeto]);

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
					
					Pjax::end();
					?>			
				</td>
				<td></td>
				<td><label>Cta: </label></td>
				<td><?= $form->field($model, 'subcta', ['options' => ['style' => 'margin-bottom:0px;']])->textInput(['style' => 'width:50px;', 'maxlength' => 2, 'id' => 'subcta', 'disabled' => true])->label(false); ?></td>
				<td width="70px"></td>
			</tr>
			
			<tr>
				<td><label>Período: </label></td>
				<td></td>
				<td width="150px">
					<label>Desde:</label>
					<?php
					echo $form->field($model, 'adesde', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 4])->label(false);
					echo $form->field($model, 'cdesde', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 3])->label(false);
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
				<td><label>Expediente: </label></td>
				<td></td>
				<td><?= $form->field($model, 'expe')->textInput(['id' => 'expe', 'maxlength' => 12])->label(false); ?></td>
			</tr>
			
			<tr>
				<td valign="top"><label>Obs: </td>
				<td></td>
				<td colspan="7"><?= $form->field($model, 'obs')->textarea(['style' => 'width:500px; max-width:500px; height:75px; max-height:75px;', 'maxlength' => 250, 'id' => 'obs'])->label(false); ?></td>
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
		$botonAceptar = Html::button('Grabar',['class' => 'btn btn-success', 'onclick' => '$("#formCondonacion").submit();']);
		$botonCancelar = Html::a('Cancelar', ['condona', 'listar' => false, 'c' => 1], ['class' => 'btn btn-primary']);
		$botonVolver = Html::a('Volver', ['condona', 'listar' => true], ['class' => 'btn btn-primary pull-right']);
		
		array_push($botones, $botonAceptar, $botonCancelar, $botonVolver);
		break;
		
	case 1:
		$botonAceptar = Html::a('Nuevo', ['condona', 'listar' => false, 'c' => 0], ['class' => 'btn btn-success']);
		$botonEliminar = Html::a('Eliminar', ['condona', 'listar' => false, 'obj_id' => $model->obj_id, 'trib_id' => $model->trib_id, 'perdesde' => $model->perdesde, 'c' => 2], 
								['class' => 'btn btn-danger', 'disabled' => ($model->trib_id <= 0)]);
								
		$botonVolver = Html::a('Volver', ['condona', 'listar' => true], ['class' => 'btn btn-primary pull-right']);
		
		array_push($botones, $botonAceptar, $botonEliminar, $botonVolver);
		break;
	
	case 2:
		$botonAceptar = Html::button('Eliminar',['class' => 'btn btn-danger', 'onclick' => '$("#formCondonacion").submit();']);
		$botonCancelar = Html::a('Cancelar', 
								['condona', 'listar' => false, 'obj_id' => $model->obj_id, 'trib_id' => $model->trib_id, 'perdesde' => $model->perdesde, 'c' => 1], 
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
			"obj_id_nuevo" : obj_id
		}
	});
	
}

$(document).ready(function(){
	

<?php
if($consulta === 3) {
?>
	DesactivarFormPost("formCondonacion");
	
	$("#adesde").prop("readonly", false);
	$("#cdesde").prop("readonly", false);
	$("#ahasta").prop("readonly", false);
	$("#chasta").prop("readonly", false);
	$("#fchalta").prop("readonly", false);
	$("#base").prop("readonly", false);
	$("#expe").prop("readonly", false);
	$("#obs").prop("readonly", false);
	<?php
} else if($consulta !== 0){
	?>
	DesactivarFormPost("formCondonacion");
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