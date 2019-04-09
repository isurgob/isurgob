<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\jui\DatePicker;

?>

<style type="text/css">

.multiselect {
    width:150px;
    height:130px;
    border:solid 1px #c0c0c0;
    overflow:auto;
}
 
.multiselect label {
    display:block;
}
 
.multiselect-on {
    color:#ffffff;
    background-color:#000099;
}

</style>

<div class="form" style="padding:10px;">
																				
	<?php
	
	$form = ActiveForm::begin(['id' => 'form-objetoTaccion','fieldConfig' => ['template' => "{input}"]]);
	
	echo Html::input('hidden', 'scenario', $model->scenario, ['id'=>'scenario']);
	
	?>
		
	<table border='0' width="100%">
		<thead>
			<tr>
				<th width="100px"></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td width="25%">
				<label>Código:</label>
			</td>
			<td width='180px'>
				<?= Html::activeInput('text', $model, 'cod', [
						'class' => 'form-control text-center solo-lectura', 
						'id' => 'txCod', 
						'style' => 'width:100%;'
					]); 
				?>
			</td>
		</tr>
		<tr>
			<td>
				<label>Tipo Objeto:</label>
			</td>
			<td width='180px'>
				<?=
					Html::activeDropDownList( $model, 'tobj', utb::getAux('objeto_tipo','cod','nombre',2), [
						'class' 	=> 'form-control ' . ( in_array($model->scenario, ['nuevo', 'modifica']) ? "" : 'solo-lectura' ),
						'style'	=> 'width:100%',
						'id' => 'dlTObj',
						'onchange' => 'select_dlTObj()'
					]);
				?>
			</td>
		</tr>
		<tr>
			<td>
				<label>Nombre:</label>
			</td>
			<td width='180px'>
				<?= Html::activeInput('text', $model, 'nombre', [
						'class' 	=> 'form-control ' . ( in_array($model->scenario, ['nuevo', 'modifica']) ? "" : 'solo-lectura' ),
						'id' => 'txNombre', 
						'style' => 'width:100%;'
					]); 
				?>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<label>Estado Actual:</label>
				<label style='margin-top:118px;'>Nuevo Estado:</label>
			</td>
			<td width='180px'>
				<?php      
				
				Pjax::begin(['id' => 'cargarListaCheck']);
				
					$i = 0;
	
					echo "<div class='multiselect'>";
						
					foreach( $listaEstado as $clave => $valor ){
																													
						echo "<label><input type='checkbox' " . ( in_array($model->scenario, ['nuevo', 'modifica']) ? '' : 'disabled=true' ) . 
							 " class='listcheck' id='".$i."' name='".$i."' value='".$clave."' onclick='validarCheck(this.id,this.value)' />".$valor."</label>";
					 		
					 	if( substr_count( $model->estactual, $clave ) > 0 )
					 	{
					 			
							echo "<script>$('#".$i."').prop('checked', true);</script>";
					
						}
	
						$i = $i+1;					 				 		
					 	
					}
	
					echo "</div>"; 
	
					echo Html::activeDropDownList( $model, 'estnuevo', $listaEstado, [
							'style' => 'width:100%;', 
							'class' 	=> 'form-control ' . ( in_array($model->scenario, ['nuevo', 'modifica']) ? "" : 'solo-lectura' )
					]);	
					
				Pjax::end();
				
			?>	
			</td>
		</tr>
		<tr>
			<td>
				<?= Html::activeCheckbox($model, 'interno', [
						'label' => 'Interna', 
						'value' => 'S', 
						'uncheck' => 'N',
						'disabled' 	=> ( in_array($model->scenario, ['nuevo', 'modifica']) ? false : true )
					]); 
				?>
			</td>
			
			<td>
				<?= Html::activeCheckbox($model, 'desdehasta', [
						'check'=> 1, 
						'uncheck' => 0,
						'disabled' 	=> ( in_array($model->scenario, ['nuevo', 'modifica']) ? false : true )
					]); 
				?>	
			</td>
		</tr>
		<tr>
			<td>
				<label>Modificación:</label>
			</td>
			<td width='180px'>
				<?= Html::activeInput('text', $model, 'modif', [
						'class' => 'form-control solo-lectura', 
						'id' => 'txNombre', 
						'style' => 'width:100%;'
					]); 
				?>
			</td>
		</tr>
		</tbody>			
	</table>
	
	<?= Html::activeInput('hidden', $model, 'estactual', ['id'=>'estactual']) ?>

	<hr>

	<div id='form_botones' style='display:<?= ( in_array($model->scenario, ['nuevo', 'modifica', 'elimina']) ? 'block' : 'none' ) ?>;margin-bottom: 8px' class="form-group"> 
    	
	    	<?php echo Html::submitButton('Grabar', ['class' => 'btn btn-success' ]); ?>
	    	<?php echo "&nbsp;&nbsp;"; ?>										
			<?php echo Html::Button('Cancelar', ['class' => 'btn btn-primary','onclick'=>'cambiaObjeto( 0, "consulta", "" )']); ?>
		
	</div>

	<?php 	
	
	echo $form->errorSummary( $model );

	ActiveForm::end(); 
	
?>
</div>

<script type="text/javascript">
	
	function select_dlTObj(){
		$.pjax.reload({
	        container: '#cargarListaCheck',
	        type: 'POST',
	        replace: false,
	        push: false,
	        data : {
	            "tobj": $("#dlTObj").val(),
	            "scenario": $("#scenario").val()
	        }
	    });
	}

	function validarCheck(id,value){

		var consul = "<?php echo $model->scenario ?>";
					
		if(consul == 'modifica'){
			
			if (document.getElementById(id).checked==true){

				var str1 = $("#estactual").val();
				str1 += value;
				$("#estactual").val(str1);
								
			}else if (document.getElementById(id).checked==false){
				
				var str1 = $("#estactual").val();
				str1 = str1.replace(value, "");
				$("#estactual").val(str1);
			}
			
		}else if(consul == 'nuevo'){

			if (document.getElementById(id).checked==true){

				var str1 = $("#estactual").val();
				str1 += value;
				$("#estactual").val(str1);
														
			}else if (document.getElementById(id).checked==false){
		
				var str1 = $("#estactual").val();
				str1 = str1.replace(value, "");
				$("#estactual").val(str1);
			}	
		}					
	}

</script>