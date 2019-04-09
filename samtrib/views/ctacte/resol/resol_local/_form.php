<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use app\utils\db\utb;

$selectorModal= isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '');
$consulta = isset($consulta) ? $consulta : 1;
?>
   <?php  
   
   Pjax::begin(['id' => 'pjaxFormVariable', 'enableReplaceState' => false, 'enablePushState' => false]);
   $form = ActiveForm::begin(['fieldConfig' => ['template' => '{input}']]); ?>
			
			<table>	
				<tr>
					<td width='45px'><label>Nombre:</label></td>
					<td width="5px"></td>
					<td><?= $form->field($model, 'varlocal')->textInput(['id'=>'formVariableVarlocal','style' => 'width:100px;', 'readOnly'=>true, 'maxlength' => 15])->label(false); ?></td>
					<td width='45px' align='right'><label>Tipo:</label></td>
					<td width="5px"></td>
					<td><?= $form->field($model, 'tipo')->dropDownList(utb::getAux('var_tipo','cod','nombre','0'), ['id'=>'formVariableTipo','style' => 'width:200px;','disabled'=>true, 'prompt' => ''])->label(false); ?></td>
					<td width='45px' align='right'><label>Valor:</label></td>
					<td width="5px"></td>
					<td><?= $form->field($model, 'valor')->textInput(['id'=>'formVariableValor','style' => 'width:100px;', 'disabled'=>true, 'maxlength' => 20])->label(false); ?></td>
				</tr>
			</table>
			
			<div class="form-group" id='form_botoneslocal'>
				<?php
					if($consulta === 2)
						echo Html::button('Eliminar', ['class' => 'btn btn-danger', 'onclick' => 'eliminarVariable();']);
					else if($consulta !== 1)
						echo Html::button('Grabar', ['class' => 'btn btn-success','onclick'=>'grabarVariable();','data-pjax' => "0"]);
						
					echo '&nbsp;&nbsp;';
					echo Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("' . $selectorModal . '").modal("hide");']); 
		    	?>
	        </div>
	        
	        <script>
    			$('#formVariableVarlocal').prop('readonly', <?= $consulta === 0 ? 'false' : 'true' ?>);
    			$('#formVariableTipo').prop('disabled', <?= $consulta === 2 ? 'true' : 'false' ?>);
    			$('#formVariableValor').prop('disabled', <?= $consulta === 2 ? 'true' : 'false' ?>);
    		</script>
    <?php
     ActiveForm::end();
     
     echo $form->errorSummary($model);
      
     Pjax::end();
     ?>	

<script>
function grabarVariable(){

	<?php
	//se obtiene el action para insert o update
	$action= BaseUrl::toRoute(['//ctacte/resolocal/create', 'resol_id' => $resol_id]);
	
	if($consulta === 3) $action= BaseUrl::toRoute(['//ctacte/resolocal/update', 'resol_id' => $resol_id, 'varlocal' => $model->varlocal]);
	?>
	
	var datos= {"ResolLocal": {}, 'selectorModal': "<?= $selectorModal; ?>"};

	<?php
	//si se esta creando, tambien se envia el nombre de la variable
	if($consulta === 0){
		?>
		datos.ResolLocal.varlocal= $("#formVariableVarlocal").val();
		<?php
	}
	?>
	
	datos.ResolLocal.tipo= $("#formVariableTipo").val();
	datos.ResolLocal.valor= $("#formVariableValor").val();
	
	$.pjax.reload({
		container: "#pjaxFormVariable",
		url: "<?= $action; ?>",
		type: "POST",
		replace: false,
		push: false,
		data: datos
	});
}

function eliminarVariable(){
	
	$.pjax.reload({
		
		container: "#pjaxFormVariable",
		url: "<?= BaseUrl::toRoute(['//ctacte/resolocal/delete', 'resol_id' => $resol_id, 'varlocal' => $model->varlocal]); ?>",
		type: "POST",
		replace: false,
		push: false
	});
}
</script>
