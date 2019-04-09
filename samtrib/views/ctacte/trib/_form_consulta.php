<?php

ini_set("display_errors", "on");
error_reporting(E_ALL);

use yii\widgets\ActiveForm;
?>

<div class="tributo-form-consulta">

<style>.asd td{border:1px solid;}</style>
	<?php
	$form = ActiveForm::begin(['fieldConfig' => ['template' => '{input}'], 'id' => 'formConsulta']);
	?>
	<table width="100%" border="0">
		<tr>
			<td><b>Cód:</b></td>
			<td></td>
			<td><?= $form->field($model, 'trib_id')->textInput(['style' => 'width:50px;text-align: center']); ?></td>
			<td></td>
			<td align="right"><b>Nombre:</b></td>
			<td colspan="3"><?= $form->field($model, 'nombre', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:243px;']); ?></td>

		</tr>

		<tr>
			<td><b>Reducido:</b></td>
			<td></td>
			<td colspan="3"><?= $form->field($model, 'nombre_redu')->textInput(['style' => 'width:150px;']); ?></td>
			<td width="100px"></td>
			<td><b>H. Bank:</b></td>

			<td><?= $form->field($model, 'nombre_reduhbank')->textInput(['style' => 'width:80px;']); ?></td>
			<td></td>
		</tr>

		<tr>
			<td><b>Tipo de trib.:</b></td>
			<td></td>
			<td colspan="3"><?= $form->field($model, 'tipo_nom')->textInput(['style' => 'width:150px;']); ?></td>
			<td></td>
			<td><b>Tipo obj.:</b></td>
			<td><?= $form->field($model, 'tobj_nom')->textInput(['style' => 'width:80px;']); ?></td>
			<td></td>
		</tr>

		<tr>
			<td><b>Valor UF:</b></td>
			<td></td>
			<td> <?= $form->field($model, 'ucm')->textInput(['style' => 'width:60px;margin-right:5px;']); ?> </td>
			<td colspan='2'> <b>Tasa Recargo:</b> </td>
			<td> <?= $form->field($model, 'calc_rec_tasa')->textInput(['style' => 'width:50px;']); ?> </td>
			<td><b>Quita Faci:</b></td>
			<td><?= $form->field($model, 'quitafaci')->textInput(['style' => 'width:80px;']); ?></td>
			<td></td>
		</tr>

		<tr>
			<td><b>Modificación:</b></td>
			<td></td>
			<td colspan="3"><?= $form->field($model, 'modif')->textInput(['style' => 'width:100%;']); ?></td>
			<td></td>
			<td><b>Estado:</b></td>

			<td><?= $form->field($model, 'est_nom')->textInput(['style' => 'width:80px;']); ?></td>
		</tr>

		<tr>
			<td width="100px"></td>
			<td width="5px"></td>
			<td width="50px"></td>
			<td width="10px"></td>
			<td width="80px"></td>
			<td width="5px"></td>
			<td width="90px"></td>
			<td width="130px"></td>

		</tr>
	</table>
	<?php
	ActiveForm::end();
	?>
</div>

<script type="text/javascript">
$(document).ready(function(){

	$("#formConsulta input").addClass("solo-lectura");
	$("#formConsulta input").attr("tabindex", -1);
});
</script>
