<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form= ActiveForm::begin([ 'id' => 'formRubroNomeclador' ]); ?>
		
	<table width='100%' id='datosRubroNomeclador'>
		<tr>
			<td width='1%'><label> Código: </label></td>
			<td width='10%'>
				<?=
                    Html::activeInput( 'text', $model, 'nomen_id', [
                        'class' 	=> 'form-control text-center solo-lectura',
                        'style'		=> 'width:90%; font-weight: bold'
                    ]);
                ?>
			</td>
			<td width='5%'><label> Tipo Objeto: </label></td>
			<td>
				<?=
    				Html::activeDropDownList( $model, 'tobj', $tipoObjeto, [
    					'class' 	=> 'form-control  solo-lectura',
    					'style'	=> 'width:100%'
    				]);
    			?>
			</td>
		</tr>
		<tr>	
			<td><label> Nombre: </label></td>
			<td colspan='3'>
				<?=
                    Html::activeInput( 'text', $model, 'nombre', [
                        'class' 	=> 'form-control  solo-lectura',
                        'style'		=> 'width:100%;'
                    ]);
                ?>
			</td>
		</tr>
		<tr>	
			<td><label> Desde: </label></td>
			<td>
				<?=
                    Html::activeInput( 'text', $model, 'perdesde', [
                        'class' 	=> 'form-control  solo-lectura',
                        'style'		=> 'width:90%;'
                    ]);
                ?>
			</td>
			<td><label> Hasta: </label></td>
			<td>
				<?=
                    Html::activeInput( 'text', $model, 'perhasta', [
                        'class' 	=> 'form-control  solo-lectura',
                        'style'		=> 'width:100%;'
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

<div id="idBotonesRubroNomeclador" style='margin-top:10px'>
	<?= 
		Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btnCancelarRubroNomeclador', 'onclick' => '$("#index_modal").modal( "hide" )'])
	?>
</div>
