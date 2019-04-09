<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use app\utils\db\utb;



$form = ActiveForm::begin(['fieldConfig' => ['template' => '{label}<br>{input}']]);

?>

<script type="text/javascript">
function cambiaObjeto(obj_id, idContenedor, esDestino){
	console.log(idContenedor);
	$.pjax.reload({
		container : idContenedor,
		replace : false,
		push : false,
		type : "GET",
		data : {
			"idObjeto" : obj_id,
			"isDestino" : esDestino
		}
	});
}
</script>

<h1>Traslado de cuenta corriente de Cementerio</h1>

<div class="form">
	<?php 
		Pjax::begin(['id' => 'pjaxOrigen', 'enableReplaceState' => false, 'enablePushState' => false]);
	?>
	<table border="0" width="780px">
		<tr>
			<td width="190px">
				<br>
				<?= $form->field($model, 'obj_id', ['template' => '{label}{input}', 'options' => ['style' => 'width:auto; display:inline-block']])
					->textInput(['id' => 'objidOrigen', 'style' => 'width:80px;', 'maxlength' => 7, 'onchange' => 'cambiaObjeto($(this).val(), "#pjaxOrigen", false)'])->label('Objeto: ') ?>
			
				<?php					
				Pjax::begin(['id' => 'BuscaObj', 'options' => ['style' => 'width:50px; display:inline-block;']]);
					$tobjeto = 0;
		  		  		          			
					Modal::begin([
		        		'id' => 'BuscaObjcc',
						'toggleButton' => [
		            		'label' => '<i class="glyphicon glyphicon-search"></i>',
		                	'class' => 'bt-buscar'
		            	],
		            	'closeButton' => [
		            		'label' => '<b>X</b>',
		                	'class' => 'btn btn-danger btn-sm pull-right',
		            	],
		            	'size' => 'modal-lg',
		        	]);
		          
		          echo $this->render('//objeto/objetobuscarav', ['txCod' => 'objidOrigen', 'txNom' => 'noExiste', 'cantidad' => 20, 'id' => 'cc', 'tobjeto' => '4']);
		           
		        	Modal::end();
		        Pjax::end(); 
        	?>
			</td>
			
			<td width="5px"></td>
			
			<?php
				if($model->tipo != null && trim($model->tipo) != '')
					$model->tipo = utb::getCampo('v_cem', "tipo = '$model->tipo'", 'tipo_nom');
			?>
			
			<td width="200px"><?= $form->field($model, 'tipo', ['options' => ['style' => 'width:200px']])->textInput(['style' => 'width:200px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Tipo') ?></td>
			<td width="5px"></td>
			<td width="50px"><?= $form->field($model, 'cuadro_id', ['options' => ['style' => 'width:50px']])->textInput(['style' => 'width:50px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Cuadro') ?></td>
			<td width="5px"></td>
			<td width="50px"><?= $form->field($model, 'cuerpo_id', ['options' => ['style' => 'width:50px']])->textInput(['style' => 'width:50px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Cuerpo') ?></td>
			<td width="5px"></td>
			<td width="40px"><?= $form->field($model, 'fila', ['options' => ['style' => 'width:40px']])->textInput(['style' => 'width:40px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Fila') ?></td>
			<td width="5px"></td>
			<td width="40px"><?= $form->field($model, 'nume', ['options' => ['style' => 'width:40px']])->textInput(['style' => 'width:40px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Nume') ?></td>
			<td width="5px"></td>
			<td width="40px"><?= $form->field($model, 'piso', ['options' => ['style' => 'width:40px']])->textInput(['style' => 'width:40px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Piso') ?></td>
			<td width="166px"></td>	
		</tr>
		
		<tr>
			<td>
				<label>Desde: </label>
				<?= $form->field($model, 'adesde', ['template' => '{input}', 'options' => ['style' => 'display:inline-block']])->textInput(['style' => 'width:40px'])->label(false); ?>
				<?= $form->field($model, 'cdesde', ['template' => '{input}', 'options' => ['style' => 'display:inline-block']])->textInput(['style' => 'width:30px'])->label(false) ?>  			
			</td>
			
			<td></td>
			
			<td>
				<label>Hasta: </label>
				<?= $form->field($model, 'ahasta', ['template' => '{input}', 'options' => ['style' => 'display:inline-block']])->textInput(['style' => 'width:40px'])->label(false); ?>
				<?= $form->field($model, 'chasta', ['template' => '{input}', 'options' => ['style' => 'display:inline-block']])->textInput(['style' => 'width:30px'])->label(false) ?>
			</td>
		</tr>
	</table>
	<?php
		Pjax::end();
	?>
</div>

<div class="form" style="margin-top:5px;">
	<?php
		Pjax::begin(['id' => 'pjaxDestino', 'enableReplaceState' => false, 'enablePushState' =>false]);
	?>
	<table border="0" width="780px"">
		<tr>
			<td width="190px">
				<br>
				<?= $form->field($modelDestino, 'obj_id', ['template' => '{label}{input}', 'options' => ['style' => 'width:auto; display:inline-block']])
					->textInput(['id' => 'objidDestino', 'style' => 'width:80px;', 'name' => 'CemDestino[obj_id]', 'maxlength' => 7, 'onchange' => 'cambiaObjeto($(this).val(), "#pjaxDestino", true)'])->label('Objeto: ') 
			?>
			
				<?php					
				Pjax::begin(['id' => 'BuscaObj2', 'options' => ['style' => 'width:50px; display:inline-block;']]);
					$tobjeto = 0;
		  		  		          			
					Modal::begin([
		        		'id' => 'BuscaObjcc2',
						'toggleButton' => [
		            		'label' => '<i class="glyphicon glyphicon-search"></i>',
		                	'class' => 'bt-buscar'
		            	],
		            	'closeButton' => [
		            		'label' => '<b>X</b>',
		                	'class' => 'btn btn-danger btn-sm pull-right',
		            	],
		            	'size' => 'modal-lg',
		        	]);
		          
		          echo $this->render('//objeto/objetobuscarav', ['txCod' => 'objidDetino', 'txNom' => 'noExiste', 'cantidad' => 20, 'id' => 'cc2', 'tobjeto' => '4']);
		           
		        	Modal::end();
		        Pjax::end(); 
        	?>
			</td>
			
			<td width="5px"></td>
			
			<?php
				if($modelDestino->tipo != null && trim($modelDestino->tipo) != '')
					$modelDestino->tipo = utb::getCampo('v_cem', "tipo = '$modelDestino->tipo'", 'tipo_nom');
			?>
			
			<td width="200px"><?= $form->field($modelDestino, 'tipo', ['options' => ['style' => 'width:200px']])->textInput(['style' => 'width:200px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Tipo') ?></td>
			<td width="5px"></td>
			<td width="50px"><?= $form->field($modelDestino, 'cuadro_id', ['options' => ['style' => 'width:50px']])->textInput(['style' => 'width:50px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Cuadro') ?></td>
			<td width="5px"></td>
			<td width="50px"><?= $form->field($modelDestino, 'cuerpo_id', ['options' => ['style' => 'width:50px']])->textInput(['style' => 'width:50px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Cuerpo') ?></td>
			<td width="5px"></td>
			<td width="40px"><?= $form->field($modelDestino, 'fila', ['options' => ['style' => 'width:40px']])->textInput(['style' => 'width:40px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Fila') ?></td>
			<td width="5px"></td>
			<td width="40px"><?= $form->field($modelDestino, 'nume', ['options' => ['style' => 'width:40px']])->textInput(['style' => 'width:40px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Nume') ?></td>
			<td width="5px"></td>
			<td width="40px"><?= $form->field($modelDestino, 'piso', ['options' => ['style' => 'width:40px']])->textInput(['style' => 'width:40px;', 'class' => 'solo-lectura form-control', 'tabindex' => -1])->label('Piso') ?></td>
			<td width="166px"></td>	
		</tr>
		</tr>
	</table>
	<?php
		Pjax::end();
	?>
</div>

<div style="margin-top:15px">
	<table align="center" width="780px">
		<tr>
			<td>
				<?php
					echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
					echo Html::a('Cancelar', ['view', 'id' => $model->obj_id], ['class' => 'btn btn-primary', 'style' => 'margin-left:5px;']);
					
					//determina que se debe ejecutar la accion de trasladar la cuenta corriente
					echo Html::input('hidden', 'taccion', 1);
				?>
			</td>
		</tr>
	</table>
</div>

<?php
ActiveForm::end();

echo $form->errorSummary([$model, $modelDestino]);
?>