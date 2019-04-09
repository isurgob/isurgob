<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\utils\db\utb;

?>

<div class="site-auxedit">

    <?php  $form = ActiveForm::begin(['id'=>'formResolucion', 'validateOnSubmit' => false, 'fieldConfig' => ['template' => '{input}']]); ?>

		<div class="form" style='padding:5px;'>
		
			<table width='100%' border='0'>
				<tr>
					<td width='50px'><label>Código:</label></td>
					<td width="5px"></td>
					<td width='55px' align='left'><?= $form->field($model, 'resol_id')->textInput(['id'=>'resol_id','style' => 'width:50px;', 'class' => 'form-control solo-lectura'])->label(false); ?></td>
					<td align='right' width='60px'><label>Nombre:</label></td>
					<td width='250px'><?= $form->field($model, 'nombre')->textInput(['id'=>'nombre','style' => 'width:245px;'])->label(false); ?></td>
					<td width="10px"></td>
					<td width='60px'><label>Tributo:</label></td>
					<td><?= $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib','trib_id','nombre'), ['id'=>'trib_id','style' => 'width:260px;', 'prompt' => ''])->label(false); ?></td>
				</tr>
			
			
				<tr>
					<td><label>Vigencia:</label></td>
					<td></td>
					<td colspan="3">
					<div style='padding:5px;' class="form">
						<table border='0' width="100%">
							<tr>
								<td><label>Desde:</label></td>
								<td><?= $form->field($model, 'adesde')->textInput(['id' => 'anio_desde', 'style' => 'width:50px;', 'maxlength' => 4])->label(false); ?></td>
								<td><?= $form->field($model, 'cdesde')->textInput(['id' => 'cuota_desde', 'style' => 'width:40px;', 'maxlength' => 3])->label(false); ?></td>
								<td align='right' width='45px'><label>Hasta:</label></td>
								<td><?= $form->field($model, 'ahasta')->textInput(['id' => 'anio_desde', 'style' => 'width:50px;', 'maxlength' => 4])->label(false); ?></td>
								<td><?= $form->field($model, 'chasta')->textInput(['id' => 'cuota_desde', 'style' => 'width:40px;', 'maxlength' => 3])->label(false); ?></td>
								<td colspan="2" align='right'><?= $form->field($model, 'anual')->checkbox(['id'=>'anual','label' => 'Anual', 'uncheck' => 0, 'value' => 1])->label(false); ?></td>
							</tr>
						</table>
					</div>
					</td>
					<td></td>
					<td><label>Funci&oacute;n:</label></td>
					<td><?= $form->field($model, 'funcion')->textInput(['id'=>'funcion','style' => 'width:260px;'])->label(false); ?></td>
				</tr>
			
				<tr>
					<td><label>Filtro:</label></td>
					<td></td>
					<td colspan="7"><?= $form->field($model, 'filtro')->textInput(['id'=>'filtro','style' => 'width:695px;'])->label(false); ?></td>
				</tr>
				<tr>
					<td><label>Cant.Años:</label></td>
					<td></td>
					<td colspan="7">
						<?= $form->field($model, 'cant_anio')->textInput(['id'=>'cant_anio','style' => 'width:50px;', 'maxlength' => '2', 'onkeypress' => 'return justNumbers( event )'])->label(false); ?>
					</td>
				</tr>
				<tr>
					<td><label>Detalle:</label></td>
					<td></td>
					<td colspan="7">
						<?= $form->field($model, 'detalle')->textarea(['id'=>'detalle','style' => 'width:695px;resize:none', 'maxlength' => '250'])->label(false); ?>
					</td>
				</tr>
			</table>
	</div>
	
	<div class="form-group" style='margin-top:5px;'>
	<?php

		echo Html::submitButton('Grabar', ['class' => 'btn btn-success']); 
		echo "&nbsp;&nbsp;";
		echo Html::a('Cancelar', ['index'], ['class' => 'btn btn-primary']);
	?>
    </div>

	<?php
	ActiveForm::end(); 
	
	echo $form->errorSummary($model);
	?>
</div>