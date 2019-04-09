<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\db\utb;

?>

<?php $form = ActiveForm::begin([ 'id' => 'form_ABMValMej', 'action' => ['valmejabm'] ]);?>
	
	<?=
		Html::input( 'hidden', 'scenario', $model->scenario, [
			'id'    => 'formaValMej_Scenario'
		]);
	?>
	<table border='0' width='100%'>
		<tr>
			<td><label>Categoría:</label></td>
			<td>
				<?php 
					if ( $existe_inm_mej_tcat ) {
						echo Html::activeDropDownList($model, 'cat', utb::getAux('inm_mej_tcat'), [
							'id'=>'valmej-cat', 
							'class'=>'form-control', 
							'style'=>'width:95%', 
							'readonly' => ( $model->scenario != 'insert' ? true : false )
						]);
					}else { 
						echo Html::activeInput('text', $model, 'cat', [
							'class' => 'form-control text-center'  . ( $model->scenario != 'insert' ? ' solo-lectura' : '' ), 
							'id' => 'valmej-cat', 
							'style' => 'width:95%;text-transform:uppercase', 
							'maxlength' => '2'
						]); 
					} 
				?>	
			</td>
			<td><label>Formulario:</label></td>
			<td>
				<?= Html::activeDropDownList($model, 'form', utb::getAux('inm_mej_tform','cod','nombre_redu',1), [
						'id'=>'valmej-form', 
						'class'=>'form-control', 
						'style'=>'width:100%', 
						'readonly' => ( $model->scenario != 'insert' ? true : false )
					]);
				?>	
			</td>
		</tr>
		<tr>
			<td><label>Desde:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'aniodesde', [
						'class' => 'form-control text-center'  . ( $model->scenario != 'insert' ? ' solo-lectura' : '' ), 
						'id' => 'valmej-aniodesde', 
						'style' => 'width:20%;', 
						'maxlength' => '4',
						'onkeypress' => 'return justNumbers( $(this).val() )',
					]); 
				?>	
				<?= Html::activeInput('text', $model, 'cuotadesde', [
						'class' => 'form-control text-center'  . ( $model->scenario != 'insert' ? ' solo-lectura' : '' ), 
						'id' => 'valmej-cuotadesde', 
						'style' => 'width:15%;', 
						'maxlength' => '3',
						'onkeypress' => 'return justNumbers( $(this).val() )',
					]); 
				?>	
			</td>	
			<td><label>Hasta:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'aniohasta', [
						'class' => 'form-control text-center'  . ( in_array($model->scenario, ['delete', 'consulta']) ? ' solo-lectura' : '' ), 
						'id' => 'valmej-aniohasta', 
						'style' => 'width:20%;', 
						'maxlength' => '4',
						'onkeypress' => 'return justNumbers( $(this).val() )',
					]); 
				?>	
				<?= Html::activeInput('text', $model, 'cuotahasta', [
						'class' => 'form-control text-center'  . ( in_array($model->scenario, ['delete', 'consulta']) ? ' solo-lectura' : '' ), 
						'id' => 'valmej-cuotahasta', 
						'style' => 'width:17%;', 
						'maxlength' => '3',
						'onkeypress' => 'return justNumbers( $(this).val() )',
					]); 
				?>	
			</td>	
		</tr>
		<tr>
			<td><label>Valor:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'valor', [
						'class' => 'form-control text-right'  . ( in_array($model->scenario, ['delete', 'consulta']) ? ' solo-lectura' : '' ), 
						'id' => 'valmej-valor', 
						'style' => 'width:95%;', 
						'maxlength' => '10',
						'onkeypress' => 'return justDecimal( $(this).val(), event )',
					]); 
				?>	
			</td>	
		</tr>
	</table>		
	
	<div class="separador-horizontal" style="margin: 8px 0px 8px 0px"></div>
		
	<div id='DivBotonesABMValMej' style="margin-top:10px;">
	<?php
		if ( $model->scenario != 'consulta' ) {
			echo Html::button('Aceptar', ['class' => 'btn btn-success','id'=>'btAceptarABMValMej', 'onclick' => 'grabarValMej()']); 
			echo '&nbsp;&nbsp;'; 
			echo Html::button('Cancelar', ['class' => 'btn btn-primary','id'=>'btCancelarABMValMej', 'onclick' => '$("#ModalValMej").modal("hide")']);
		}	
	?>
	</div>

<?php ActiveForm::end(); ?>

<?= $form->errorSummary($model, ['id' => 'errorABMValMej', 'style' => 'margin-top:10px;']);	?>


<script>

function grabarValMej() {

	//do your own request an handle the results
	$.ajax({
		url: $("#form_ABMValMej").attr( "action" ),
		type: 'post',
		data: $("#form_ABMValMej").serialize(),
		success: function(data) {
			datos = jQuery.parseJSON(data); 
			
			if ( datos.error == "" ){
				
				f_Buscar();
				$("#ModalValMej").modal("hide");
			
			}else	
				mostrarErrores( datos.error, "#errorABMValMej" );
		}
	});

};

</script>