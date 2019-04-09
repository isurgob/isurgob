<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

use app\utils\db\utb;
use app\utils\helpers\DBException;


$this->params['breadcrumbs'][] = ['url' => ['viewfall', 'id' => $model->fall_id], 'label' => 'Fallecido ' . $model->fall_id];
$this->params['breadcrumbs'][] = 'Servicios de Fallecidos';
?>

<script type="text/javascript">
function buscarObjeto(esOrigen, obj_id){
	
	$.pjax.reload({
		container : "#pjaxObjeto",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"obj_id" : obj_id,
			"esOrigen" : esOrigen
		}
	});
}

function cambiaTipoServicio(nuevo){
	
	$.pjax.reload({
		container : "#pjaxTipoServicio",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"tserv" : nuevo
		}
	});
}
</script>

<?php
$form = ActiveForm::begin(['fieldConfig' => ['template' => '{input}']]);
?>
<div class="cem-fall-serv">

	<h1>Servicios de fallecidos</h1>
	
	<div class="form" style="padding:5px;">
		<table border="0" width="100%">
			<tr>
				<td><label>Tipo de servicio:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'tserv')->dropDownList(utb::getAux('cem_fall_tserv'), ['onchange' => 'cambiaTipoServicio($(this).val());'])->label(false) ?></td>
				<td width="500px"></td>
			</tr>
		</table>
	</div>

	<div class="form" style="padding:5px; margin-top:5px;">
		<h3><label>Datos del fallecido</label></h3>
		
		<table border="0" width="100%">
			<tr>
				<td><label>C&oacute;digo:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'fall_id')->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:80px;'])->label(false) ?></td>
				<td width="10px"></td>
				<td><label>Apellido y Nombre:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'apenom')->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:400px;'])->label(false) ?></td>
			<tr>
		</table>
	</div>
	
	<div class="form" style="padding:5px; margin-top:5px;">
		<h3><label>Datos del servicio</label></h3>
		
		<table border="0" width="100%">
			<tr>
				<td><label>Fecha Reg.:</label></td>
				<td width="5px"></td>
				<td><?= DatePicker::widget(['model' => $model, 'attribute' => 'fecha', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:80px;']]) ?></td>
				<td width="10px"></td>
				<td><label>Acta</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'acta')->textInput(['style' => 'width:80px;'])->label(false) ?></td>
				<td width="400px"></td>
			</tr>
			
			<tr>
				<td><label>Responsable:</label></td>
				<td></td>
				<td colspan="6">
				<?php
					
					echo $form->field($model, 'resp', ['options' => ['style' => 'width:80px; display:inline-block;']])->textInput(['id' => 'codigoResponsable', 'style' => 'width:80px;'])->label(false);
					echo '&nbsp;&nbsp;';
					
					Modal::begin([
		                'id' => 'modalBuscaPersona',
						'header' => '<h2>Búsqueda de Persona</h2>',
						'toggleButton' => [
		                    'label' => '<i class="glyphicon glyphicon-search"></i>',
		                    'class' => 'bt-buscar'
		                ],
		                'closeButton' => [
		                  'label' => '<b>X</b>',
		                  'class' => 'btn btn-danger btn-sm pull-right'
		                ],
		                'size' => 'modal-lg'
		            ]);
		            
		            echo $this->render('//objeto/objetobuscarav', ['txCod' => 'codigoResponsable', 'txNom' => 'nombreResponsable', 'id' => 'persona', 'tobjeto' => 3, 'selectorModal' => '#modalBuscaPersona']);
		            
		            Modal::end();
					
					
					echo Html::textInput(null, null, ['id' => 'nombreResponsable', 'class' => 'form-control solo-lectura', 'style' => 'width:500px;']);
				?>
				</td>
			</tr>
		</table>
	</div>
	
	<?php
	$opciones = ['template' => '{label}<br/>{input}'];
	?>
	
	<?php
	Pjax::begin(['id' => 'pjaxObjeto', 'enableReplaceState' => false, 'enablePushState' => false]);
	
	$esOrigen = Yii::$app->request->get('esOrigen', null);
	
	if($esOrigen !== null) {
	
		$esOrigen = filter_var($esOrigen, FILTER_VALIDATE_BOOLEAN);
		$obj_id = trim(Yii::$app->request->get('obj_id', ''));
		
		if(strlen($obj_id) < 8) $obj_id = utb::getObjeto('4', $obj_id);
		
		if(strlen($obj_id) == 8){
			
			$datos = utb::getVariosCampos('v_cem', "obj_id = '$obj_id'", 'tipo_nom, cuadro_id, cuerpo_id, fila, piso, nume');			
			
			if($datos !== false){
				
				if($esOrigen){
					
					$modelOrigen->obj_id = $obj_id;
					$modelOrigen->tipo = $datos['tipo_nom'];
					$modelOrigen->cuadro_id = $datos['cuadro_id'];
					$modelOrigen->cuerpo_id = $datos['cuerpo_id'];
					$modelOrigen->fila = $datos['fila'];
					$modelOrigen->piso = $datos['piso'];
					$modelOrigen->nume = $datos['nume'];
					
				} else {

					$modelDestino->obj_id = $obj_id;
					$modelDestino->tipo = $datos['tipo_nom'];
					$modelDestino->cuadro_id = $datos['cuadro_id'];
					$modelDestino->cuerpo_id = $datos['cuerpo_id'];
					$modelDestino->fila = $datos['fila'];
					$modelDestino->piso = $datos['piso'];
					$modelDestino->nume = $datos['nume'];
				}
			}
		}
	}
	?>
	
	<style>.asd td{border:1px solid;}</style>
	<div class="form" style="padding:5px; margin-top:5px;">
		<h3><label>Objeto origen</label></h3>
	
		<table border="0" width="100%">
			<tr>
				<td width="126px">
					<br/>
					<?= Html::textInput(null, $modelOrigen->obj_id, ['class' => 'form-control solo-lectura', 'style' => 'width:80px;']); ?>
				</td>
				<td width="5px"></td>
				<td>
					<label>Tipo</label>
					<br/>
					<?= Html::textInput(null, $modelOrigen->tipo, ['class' => 'form-control solo-lectura', 'style' => 'width:100%;']); ?>
				</td>
				<td width="5px"></td>
				<td>
					<label>Cuadro</label>
					<br/>
					<?= Html::textInput(null, $modelOrigen->cuadro, ['class' => 'form-control solo-lectura', 'style' => 'width:80px;']); ?>
				</td>
				<td width="5px"></td>
				<td>
					<label>Cuerpo</label>
					<br/>
				<?= Html::textInput(null, $modelOrigen->cuerpo_id, ['class' => 'form-control solo-lectura', 'style' => 'width:80px;']); ?>
				</td>
				<td width="5px"></td>
				<td>
					<label>Piso</label>
					<br/>
					<?= Html::textInput(null, $modelOrigen->piso, ['class' => 'form-control solo-lectura', 'style' => 'width:40px;']); ?>
				</td>
				<td width="5px"></td>
				<td>
					<label>Fila</label>
					<br/>
					<?= Html::textInput(null, $modelOrigen->fila, ['class' => 'form-control solo-lectura', 'style' => 'width:40px;']); ?>
				</td>
				<td width="5px"></td>
				<td>
					<label>Nume</label>
					<br/>
					<?= Html::textInput(null, $modelOrigen->nume, ['class' => 'form-control solo-lectura', 'style' => 'width:40px;']); ?>
				</td>
				<td width="100px"></td>
			</tr>
		</table>
	</div>
	
	<div class="form" style="padding:5px; margin-top:5px;">
		<h3><label>Objeto destino</label></h3>
	
		<table border="0" width="100%">
			<tr>
				<td>
				<?php
					echo $form->field($modelDestino, 'obj_id', $opciones + ['options' => ['style' => 'width:80px; display:inline-block;']])
					->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:80px;', 'maxlength' => 8, 'onchange' => 'buscarObjeto(false, $(this).val());', 'name' => 'objIdDestino', 'id' => 'objetoDestino'])
					->label(false);
					echo '&nbsp;&nbsp;';					
					
					Modal::begin([
		                'id' => 'modalBuscaCementerio',
						'header' => '<h2>Búsqueda de Cuenta de Cementerio</h2>',
						'toggleButton' => [
		                    'label' => '<i class="glyphicon glyphicon-search"></i>',
		                    'id' => 'botonModalCuentaCementerio',
		                    'class' => 'bt-buscar',
		                    'disabled' => true
		                ],
		                'closeButton' => [
		                  'label' => '<b>X</b>',
		                  'class' => 'btn btn-danger btn-sm pull-right',
		                ],
		                'size' => 'modal-lg'
		            ]);
		            
		            echo $this->render('//objeto/objetobuscarav', ['txCod' => 'objetoDestino', 'txNom' => '', 'id' => 'cementerio', 'tobjeto' => 4, 'selectorModal' => '#modalBuscaCementerio']);
		            
		            Modal::end(); 
				?>
				</td>
				<td width="5px"></td>
				<td><?= $form->field($modelDestino, 'tipo', $opciones)->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:100%;'])->label('Tipo') ?></td>
				<td width="5px"></td>
				<td><?= $form->field($modelDestino, 'cuadro_id', $opciones)->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:80px;'])->label('Cuadro') ?></td>
				<td width="5px"></td>
				<td><?= $form->field($modelDestino, 'cuerpo_id', $opciones)->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:80px;'])->label('Cuerpo'); ?></td>
				<td width="5px"></td>
				<td><?= $form->field($modelDestino, 'piso', $opciones)->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:40px;'])->label('Piso'); ?></td>
				<td width="5px"></td>
				<td><?= $form->field($modelDestino, 'fila', $opciones)->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:40px;'])->label('Fila'); ?></td>
				<td width="5px"></td>
				<td><?= $form->field($modelDestino, 'nume', $opciones)->textInput(['class' => 'form-control solo-lectura', 'style' => 'width:40px;'])->label('Nume'); ?></td>
				<td width="100px"></td>
			</tr>
		</table>
	</div>
	<?php
	Pjax::end();
	?>
	
	<div class="form" style="padding:5px; margin-top:5px;">
		<h3><label>Destino</label></h3>
		<table border="0" width="100%">
			<tr>
				<td><?= $form->field($model, 'destino')->textarea(['rows' => 3, 'style' => 'width:100%;', 'class' => 'form-control solo-lectura', 'id' => 'destino'])->label(false) ?></td>
			</tr>
		</table>
	</div>
	
	<div class="form" style="padding:5px; margin-top:5px;">
		<h3><label>Observaciones</label></h3>
		<table border="0" width="100%">
			<tr>
				<td><?= $form->field($model, 'obs')->textarea(['style' => 'width:100%; max-width:770px; max-height:50px; height:50px;', 'class' => 'form-control'])->label(false) ?></td>
			</tr>
		</table>
	</div>
	
	<div style="padding:5px; margin-top:5px;">
		<?= Html::submitButton('Grabar', ['class' => 'btn btn-success']); ?>
		&nbsp;
		<?= Html::a('cancelar', ['viewfall', 'id' => $model->fall_id], ['class' => 'btn btn-primary']) ?>
	</div>
</div>


<?php
Pjax::begin(['id' => 'pjaxTipoServicio', 'enableReplaceState' => false, 'enablePushState' => false]);
$tserv = trim(Yii::$app->request->get('tserv', $model->tserv));

if($tserv != ''){
	
	$datos = utb::getVariosCampos('cem_fall_tserv', "cod = '$tserv'", 'pedir_obj_dest, pedir_dest');
	
	if($datos !== false){
		$pedirObjeto = filter_var($datos['pedir_obj_dest'], FILTER_VALIDATE_BOOLEAN);
		$pedirDestino = filter_var($datos['pedir_dest'], FILTER_VALIDATE_BOOLEAN);
		
		?>
		<script type="text/javascript">
		$("#objetoDestino").toggleClass("solo-lectura", <?= $pedirObjeto ? 'false' : 'true'; ?>);
		$("#destino").toggleClass("solo-lectura", <?= $pedirDestino ? 'false' : 'true'; ?>);
		$("#botonModalCuentaCementerio").prop("disabled", <?= $pedirObjeto ? 'false' : 'true' ?>);
		</script>
		<?php
	}
}
Pjax::end();
?>

<?php
ActiveForm::end();
echo $form->errorSummary($model);
?>

<script type="text/javascript">
$(document).ready(function(){
<?php
if($modelOrigen->obj_id !== null && trim($modelOrigen->obj_id) != ''){
?>
buscarObjeto(true, "<?= $modelOrigen->obj_id ?>");
<?php
}

if($modelDestino->obj_id !== null && trim($modelDestino->obj_id) != ''){
?>
buscarObjeto(false, "<?= $modelDestino->obj_id ?>");
<?php
}
?>




$("#modalBuscaCementerio").on("", function(){
	
	buscarObjeto(false, $("#codigoObjeto").val());
});
});

</script>