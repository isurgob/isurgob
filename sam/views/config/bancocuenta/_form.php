<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\caja\BancoCuenta */
/* @var $form yii\widgets\ActiveForm */

?>

<script type="text/javascript">
	
	function actualizarNombreBancoEntidad(dato){
		                                                                                                   
		var elementoNombreEntidadBancaria = document.getElementById('nombre_Entidad_Bancaria');
		
		elementoNombreEntidadBancaria.value = dato;
	}

	function actualizarNombreSucursal(dato){
		                                                             
		var elementoNombreSucursal = document.getElementById('nombre_Sucursal');
		
		elementoNombreSucursal.value = dato;
	}
	
	function enfocar(){
		
		document.getElementGetId('bancocuenta-bco_ent').focus();
	}
	
</script>

<div class="form" style="margin:5px 0px;">

    <?php                                         
    $form = ActiveForm::begin(['action' => ['bancocuentaabm'], 'id'=>'frmBcoCta','fieldConfig' => ['template' => "{label}{input}"]]);
    
    if (isset($consulta)==null) $consulta = 1;
    
    echo Html::input('hidden', 'accion', $consulta, ['id'=>'accion']);
   
    ?>
    
<table border='0'>
	<tr>
		<td width='225px'>    
			<?= $form->field($model, 'bcocta_id')->textInput(['style' => 'width:100px;margin-top:10px;']) ?>
		</td>
		<td>
			<label> Estado: </label>
			<?= Html::input('text', 'txEst', ($model->est == 'A' ? 'Activo' : 'Baja'), ['class' => 'form-control solo-lectura','id'=>'txEst','style'=>'width:100px']); ?>
		</td>
	</tr>
	<tr>
		<td colspan='2'>    
			<?= $form->field($model, 'titular')->textInput(['maxlength' => 50,'style' => 'width:321px;']) ?>
		</td>
	</tr>
	<tr>
		<td colspan='2' >    
			<?= $form->field($model, 'cbu')->textInput(['maxlength' => 22,'style' => 'width:200px;']) ?>
		</td>
	</tr>
	<tr>
		<td>
			
			<?php 
			
			$tipo = utb::getAux('fin.banco_tcta','cod','nombre',0);
			echo $form->field($model, 'tipo')->dropDownList($tipo, ['id' => 'bancocuenta-tipo', 'style' => 'width:100px;'])->label('Tipo de Cuenta') 
			
			?> 
			
		</td>
		<td>    
			
			<?php 
			
			$tmoneda = utb::getAux('fin.banco_tmoneda','cod','nombre',0);
			echo $form->field($model, 'tmoneda')->dropDownList($tmoneda, ['id' => 'bancocuenta-tmoneda', 'style' => 'width:100px;'])->label('Tipo de Moneda') 
			
			?> 
			
		</td>
	</tr>
	<tr>
		<td>     
			<?php 
			
			$tuso = utb::getAux('fin.banco_tuso','cod','nombre',0);
			echo $form->field($model, 'tuso')->dropDownList($tuso, ['id' => 'bancocuenta-tuso', 'style' => 'width:100px;'])->label('Uso de Cuenta') 
			
			?> 
		</td>
		<td>	
			<?= $form->field($model, 'ultcheque')->textInput(['maxlength' => 4,'style' => 'width:80px;']) ?>
		</td>
	</tr>
	
</table>
    
	<div class="form-group" id='botones' style='display:none'>
        <?php         
 	      	echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary','onclick'=>'btnCancelar();']);
        ?>	
    </div>
    <div class="form-group" id='botones_delete' style='display:none'>    
 		<?php
        	echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger']);
        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary','onclick'=>'btnCancelar();']);
        ?>
	</div>  
    
    <?php
	
		//-------------------------seccion de error-----------------------
		
		 	Pjax::begin(['id' => 'divError']);	
		 
				if(isset($_GET['error']) and $_GET['error'] != '') {  
					echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_GET['error'] . '</ul></div>';
					}
			Pjax::end();		
				
		 //------------------------------------------------------------------------------------------------------------------------	
    
    
    if($consulta==0){
    	
    	echo '<script>$("#botones").css("display","block");</script>';
    	echo '<script>$("#botones_delete").css("display","none");</script>';                
    }
    if($consulta==1){
    	
    	echo '<script>$("#bancocuenta-bcocta_id").prop("readOnly",true);</script>';
		echo '<script>$("#bancocuenta-titular").prop("readOnly",true);</script>';
    	echo '<script>$("#bancocuenta-cbu").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-tipo").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-tmoneda").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-tuso").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-ultcheque").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-fchmod").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-usrmod").prop("disabled",true);</script>';
    	
    }
    if($consulta==2){
    	
    	echo '<script>$("#botones").css("display","none");</script>';
    	echo '<script>$("#botones_delete").css("display","block");</script>';
    	echo '<script>$("#bancocuenta-bcocta_id").prop("readOnly",true);</script>';
    	echo '<script>$("#bancocuenta-titular").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-cbu").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-tipo").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-tmoneda").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-tuso").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-fchmod").prop("disabled",true);</script>';
    	echo '<script>$("#bancocuenta-usrmod").prop("disabled",true);</script>';
    	
    }
	if($consulta==3){
		
	   echo '<script>$("#botones").css("display","block");</script>';
       echo '<script>$("#botones_delete").css("display","none");</script>';		    	
	   echo '<script>$("#bancocuenta-bcocta_id").prop("readOnly",true);</script>';               
    }
    
     ?>

    <?php ActiveForm::end(); 
    
     if ($consulta==1) {
    		echo "<script>DesactivarForm('form-inm');</script>";
      }
    
    ?>
    
</div>
	<script>
		$('#BuscarAuxBancoEntidad').on('hide.bs.modal', function () {
		  $.pjax.reload({container:"#buscarsucursal",data:{bco_ent:$("#bancocuenta-bco_ent").val()},method:"POST"})
		})
		
		function btnCancelar(){
			$('#divError').css('display','none');
			$("#botones").css("display","none");
			$("#botones_delete").css("display","none");
			$("#bancocuenta-bcocta_id").prop("readOnly",true);
    		$("#bancocuenta-titular").prop("disabled",true);
    		$("#bancocuenta-cbu").prop("disabled",true);
    		$("#bancocuenta-tipo").prop("disabled",true);
    		$("#bancocuenta-tmoneda").prop("disabled",true);
    		$("#bancocuenta-tuso").prop("disabled",true);
    		$("#bancocuenta-fchmod").prop("disabled",true);
    		$("#bancocuenta-usrmod").prop("disabled",true);
    		$("#bancocuenta-ultcheque").prop("disabled",true);
        	
        	$("#bancocuenta-bcocta_id").val('');
    		$("#bancocuenta-titular").val('');
    		$("#bancocuenta-cbu").val('');
    		$("#bancocuenta-tipo").val('');
    		$("#bancocuenta-tmoneda").val('');
			$("#bancocuenta-interna").val('');
			$("#bancocuenta-ultcheque").val('');
			$("#bancocuenta-txEst").val('');
    		$("#bancocuenta-fchmod").val('');
    		$("#bancocuenta-usrmod").val('');
    		
		}
		
	</script>
