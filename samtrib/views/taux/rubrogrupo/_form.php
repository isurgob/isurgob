<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form= ActiveForm::begin([ 'id' => 'formRubroGrupo', 'action' => ['grabarrubrogrupo'] ]); ?>
		
	<?= Html::input( 'hidden', 'txScenario', $model->scenario, ['id' => 'txScenario'] ); ?>
	
	<table width='100%' id='datosRubroGrupo'>
		<tr>
			<td width='1%'><label> Código: </label></td>
			<td width='10%'>
				<?=
                    Html::activeInput( 'text', $model, 'cod', [
                        'class' 	=> 'form-control text-center ' . ( $model->scenario == 'insert' ? '' : 'solo-lectura' ),
                        'style'		=> 'width:90%; font-weight: bold',
                        'tabIndex'	=> ( $model->scenario == 'insert' ? '0' : '-1' ),
						'marlenght' => 2,
						'onKeyUp' 	=> "this.value=this.value.toUpperCase();"
                    ]);
                ?>
			</td>
			<td width='5%'><label> Nomeclador: </label></td>
			<td>
				<?=
    				Html::activeDropDownList( $model, 'nomen_id', $nomecladores, [
    					'class' 	=> 'form-control ' . ( $model->scenario == 'insert' ? '' : 'solo-lectura' ),
    					'style'	=> 'width:100%',
    					'tabIndex'	=> ( $model->scenario == 'insert' ? '0' : '-1' ),
    				]);
    			?>
			</td>
		</tr>
		<tr>	
			<td><label> Nombre: </label></td>
			<td colspan='3'>
				<?=
                    Html::activeInput( 'text', $model, 'nombre', [
                        'class' 	=> 'form-control ' . ( in_array($model->scenario, ['insert', 'update']) ? '' : 'solo-lectura' ),
                        'style'		=> 'width:100%;',
                        'tabIndex'	=> ( in_array($model->scenario, ['insert', 'update']) ? '0' : '-1' ),
						'marlenght' => 50
                    ]);
                ?>
			</td>
		</tr>
	</table>

<?php 
	ActiveForm::end();
	
	echo $form->errorSummary( $model,[
				'id'	=> 'form_errorSummary',
				'style' => 'margin-top:10px;',
			]);
?>	

<div id="idBotonesRubroGrupo" style='margin-top:10px'>
	<?php  
		if ( $model->scenario != "" )
			echo Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btnAceptarRubroGrupo', 'onclick' => 'f_grabarRubroGrupo();'])
	?>

	<?= 
		Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btnCancelarRubroGrupo', 'onclick' => '$("#index_modal").modal( "hide" )'])
	?>
</div>

<script>

function f_grabarRubroGrupo(){
	
	$.ajax({
		url: $("#formRubroGrupo").attr( "action" ),
		type: 'post',
		data: $("#formRubroGrupo").serialize(),
		success: function(data) {
			datos = jQuery.parseJSON(data); 
			
			if ( datos.error != "" )
				mostrarErrores( datos.error, "#form_errorSummary" );
		}
	});
	
}

</script>