<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use app\utils\db\utb;

/* @var $this yii\web\View */

$title = 'Consultas Web';
if ($consulta == 2) $title = 'Eliminar Consulta';
if ($consulta == 3) $title = 'Responder Consulta';

$this->params['breadcrumbs'][] = ['label' => 'Consultas Web', 'url' => Yii::$app->param->sis_url.'objeto/persona/consultaweb'];
$this->params['breadcrumbs'][] = $title;

?>
<div class="intima-view">
	<h1><?= Html::encode($title) ?></h1>
	<div class="form-panel">
	<?php
		$form = ActiveForm::begin(['id'=>'frmCons']);
	?>	
		
		<table width='65%' cellpadding='5' cellpadding='5' align="center">
			<tr>
				<td width="52%" >
					<label>Id:</label>
					<?=
						Html::input('text','ConsultaWeb[cons_id]',$model->cons_id,[
							'id'=>'cons_id',
							'class'=>'form-control solo-lectura',
							'style'=>'width:40px;text-align:center'
						]);
					?>
				</td>
				<td>
					<label>Estado:</label>
					<?=
						Html::input('text','ConsultaWeb[est]',utb::getCampo("sam.cons_test","cod='" . $model->est . "'"),[
							'id'=>'est',
							'class'=>'form-control solo-lectura',
							'style'=>'width:100px;text-align:left'
						]);
					?>
				</td>
			</tr>
			<tr><td colspan="2"> <hr/> </td></tr>
		</table>
		<table width='65%' cellpadding='5' cellpadding='5' align="center">
			<tr>
				<td><label>Documento:</label></td>
				<td>
					<?= 
						Html::dropDownList('ConsultaWeb[tdoc]', $model->tdoc == '' ? 3 : $model->tdoc, utb::getAux("persona_tdoc"), [
							'class' => 'form-control','id'=>'tdoc'
						]); 
					?>
					<?=
						Html::input('text','ConsultaWeb[ndoc]',$model->ndoc,[
							'id'=>'ndoc',
							'class'=>'form-control',
							'style'=>'width:100px;text-align:right',
							'maxlength' => '10'
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Nombre:</label></td>
				<td>
					<?=
						Html::input('text','ConsultaWeb[nombre]',$model->nombre,[
							'id'=>'nombre',
							'class'=>'form-control',
							'style'=>'width:205px;text-align:left',
							'maxlength' => '15'
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Domicilio:</label></td>
				<td>
					<?=
						Html::input('text','ConsultaWeb[domi]',$model->domi,[
							'id'=>'domi',
							'class'=>'form-control',
							'style'=>'width:400px;text-align:left',
							'maxlength' => '40'
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Localidad:</label></td>
				<td>
					<?=
						Html::input('text','ConsultaWeb[loc]',$model->loc,[
							'id'=>'loc',
							'class'=>'form-control',
							'style'=>'width:400px;text-align:left',
							'maxlength' => '20'
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Mail:</label></td>
				<td>
					<?=
						Html::input('text','ConsultaWeb[mail]',$model->mail,[
							'id'=>'mail',
							'class'=>'form-control',
							'style'=>'width:400px;text-align:left',
							'maxlength' => '50'
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Tel&eacute;fono:</label></td>
				<td>
					<?=
						Html::input('text','ConsultaWeb[tel]',$model->tel,[
							'id'=>'tel',
							'class'=>'form-control',
							'style'=>'width:170px;text-align:left',
							'maxlength' => '20'
						]);
					?>
					<label>Celular:</label>
					<?=
						Html::input('text','ConsultaWeb[cel]',$model->cel,[
							'id'=>'cel',
							'class'=>'form-control',
							'style'=>'width:175px;text-align:left',
							'maxlength'=>'20'
						]);
					?>
				</td>
			</tr>
			<tr><td colspan="2"><hr/></td></tr>
			<tr>
				<td><label>Tema:</label></td>
				<td>
					<?= 
						Html::dropDownList('ConsultaWeb[tema]', $model->tema, utb::getAux("sam.cons_tema"), [
							'class' => 'form-control','id'=>'tema'
						]); 
					?>
				</td>
			</tr>
			<tr>
				<td valign="top"><label>Detalle:</label></td>
				<td>
					<?= 
						Html::textarea('ConsultaWeb[detalle]', $model->detalle, [
							'class' => 'form-control',
							'id'=>'detalle',
							'maxlength'=>'500',
							'style'=>'width:400px;height:100px; max-height:150px; resize:none'
						]); 
					?>
				</td>
			</tr>
			<tr>
				<td valign="top"><label>Respuesta:</label></td>
				<td>
					<?= 
						Html::textarea('ConsultaWeb[respuesta]', $model->respuesta, [
							'class' => 'form-control',
							'id'=>'respuesta',
							'maxlength'=>'500',
							'style'=>'width:400px;height:100px; max-height:150px; resize:none'
						]); 
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align='right'>
					<label>Modificación:</label>
					<?=
						Html::input('text','ConsultaWeb[usrmod]',utb::getFormatoModif($model->usrmod,$model->fchmod),[
							'id'=>'modif',
							'class'=>'form-control solo-lectura',
							'style'=>'width:170px;text-align:left'
						]);
					?>
				</td>
			</tr>
			<tr><td colspan="2"><hr/></td></tr>
			<tr>
				<td colspan="2" style="padding:10px 0px">
					<?php 
						if ($consulta != 1){
							echo Html::Button($consulta == 2 ? 'Eliminar' : 'Aceptar', ['class' => $consulta == 2 ? 'btn btn-danger' : 'btn btn-success', 'id' => 'btAceptarCons']); 
							echo "&nbsp;&nbsp;";
							echo Html::a('Cancelar', ['consultaweb'], ['class' => 'btn btn-primary', 'id' => 'btCancelarCons']);
						}
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div class="error-summary" style="margin:10px 0px; display:none">Por favor corrija los siguientes errores:<br/><ul></ul></div>
				</td>
			</tr>
		</table>
		<?php ActiveForm::end();  ?>
	</div>	
	
</div>

<script>

$("#frmCons").css('pointer-events','none');
<?php if ($consulta == 3 or $consulta == 2) { // si es modificación o eliminación
	if ($consulta == 3) { // si es modificación, habilito la respuesta solamente 
?>	
	$("#respuesta").css('pointer-events','all');
<?php } ?>
	$("#btAceptarCons").css('pointer-events','all');
	$("#btCancelarCons").css('pointer-events','all');
<?php } ?>

$("#btAceptarCons").click(function(){
	var url = $("#frmCons").attr("action");
	
	$.ajax({
           type: "POST",
           url: url,
           data: $("#frmCons").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               $(".error-summary").css("display","block");
			   $(".error-summary").html(data); // Mostrar la respuestas del script PHP.
           }
         });

    return false; // Evitar ejecutar el submit del formulario.
});
</script>