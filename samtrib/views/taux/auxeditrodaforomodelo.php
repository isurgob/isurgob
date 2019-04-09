<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\taux\tablaAux;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\web\Session;

$title = 'Tipos de Modelos de Aforo';
$this->params['breadcrumbs'][] = ['label' => 'Configuración','url' => ['//site/config']];
$this->params['breadcrumbs'][] = $title;

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

if (isset($consulta) == null) $consulta = 1;
if (isset($_GET['mensaje']) == null) $_GET['mensaje'] = '';

?>
<div class="site-auxedit">
	<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
    	<td align='right'>
    		<?php 
    			if ($model->accesoedita > 0) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxRodadoAforoModelo();']) 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    
	<?php
    	 	 
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $tabla,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid) 
							{
								return [
									'onclick' => 'CargarControles("'.$model['origen_id'].'","'.
																	 $model['marca_id'].'","'.
																	 $model['tipo_id'].'","'.
																	 $model['modelo_id'].'","'.
																	 $model['marca_nom'].'","'.
																	 $model['tipo_nom'].'","'.
																	 $model['modelo_nom'].'","'.
																	 $model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'origen','header' => 'Origen', 'contentOptions'=>['style'=>'width:30px;text-align:center;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=>'marca_id','header' => 'Marca' ,'value' => function($data) { return (utb::getCampo('judi_tsupuesto','cod='.$data['supuesto'])); },
					'contentOptions'=>['style'=>'width:250px;text-align:left;font-size:12px;', 'class' => 'grilla']],           		
            		['attribute'=>'tipo_id','header' => 'Tipo', 'contentOptions'=>['style'=>'width:30px;text-align:center;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=>'modelo_id','header' => 'Modelo' ,'contentOptions'=>['style'=>'width:50px;text-align:center;font-size:12px;', 'class' => 'grilla']],  
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:35px;text-align:center;'],'template' => (($model->accesoedita > 0) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxRodadoAforoModelo("'.$model['origen_id'].'","'.
																								 $model['marca_id'].'","'.
																								 $model['tipo_id'].'","'.
																								 $model['modelo_id'].'","'.
																								 $model['marca_nom'].'","'.
																								 $model['tipo_nom'].'","'.
																								 $model['modelo_nom'].'","'.
																								 $model['modif'].'");'
													]				 			 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxRodadoAforoModelo("'.$model['origen_id'].'","'.
																							    $model['marca_id'].'","'.
																							    $model['tipo_id'].'","'.
																							    $model['modelo_id'].'","'.
																							    $model['marca_nom'].'","'.
																							    $model['tipo_nom'].'","'.
																							    $model['modelo_nom'].'","'.
																							    $model['modif'].'");'
																							]
										            									);
											            						}
														    			]
														    	   ],
														    	   
															],
														]); 	    

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmRodadoAforoModelo']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr height='35px'>
				<td>Origen:</td>
				<td>											   
					<input type='radio' id='importado' name='origen_id' value='I' disabled='disabled' checked ><div style='float:right;margin-right:211px;'>Importado</div>
				</td>
				<td>
					<input type='radio' id='nacional' name='origen_id' value='N' disabled='disabled' ><div style='float:right;margin-right:172px;'>Nacional</div>
				</td>
				<td width='80px' align='left'><label>Marca: </label></td>		
				<td width='80px'>
			  	<?= Html::input('text', 'marca_id',$marca_id,['id' => 'marca_id','class' => 'form-control','maxlength'=>'3' ,'style' => 'width:100px;', 'readonly' => true ]); ?>
					</td>
				<td width='100px' align='right'><label>Tipo: </label></td>
				<td>
				<?= Html::input('text', 'tipo_id',$tipo_id,['id' => 'tipo_id','class' => 'form-control','maxlength'=>'3' ,'style' => 'width:100px;', 'readonly' => true ]); ?>
			</td>
				<td width='100px' align='right'><label>Modelo: </label></td>
				<td>
				<?= Html::input('text', 'modelo_id',$modelo_id,['id' => 'modelo_id','class' => 'form-control','maxlength'=>'3' ,'style' => 'width:100px;', 'readonly' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr height='35px' >
			<td width='80px' align='left'><label>Nombre Marca: </label></td>		
			<td width='80px'>
			  <?= Html::input('text', 'marca_nom',$marca_nom,['id' => 'marca_nom','class' => 'form-control','maxlength'=>'13' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		<tr>
			<td width='100px' align='right'><label>Nombre Tipo: </label></td>
			<td>
				<?= Html::input('text', 'tipo_nom',$tipo_nom,['id' => 'tipo_nom','class' => 'form-control','maxlength'=>'13' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
		</td>
		</tr>
		<tr>
			<td width='100px' align='right'><label>Nombre Modelo: </label></td>
			<td>
				<?= Html::input('text', 'modelo_nom',$modelo_nom,['id' => 'modelo_nom','class' => 'form-control','maxlength'=>'13' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
		</td>
		</tr>
		</table>
	
		</tabla>
		<table border='0'>
		<tr>
			<td id='labelModif' width='600px' align='right'><label>Modificación:</label></td>
			<td>
				<?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModif','style'=>'width:150px;background:#E6E6FA;float:right;', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>

	        <div class="form-group" id='form_botones' style='display:none'>
	
			<?php

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxRodadoAforoModelo()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxRodadoAforoModelo()']); 
	    	?>
	        </div>
    	</div>
    	<?php 
		
			if($consulta==0){
			?>
			
				<script>
					$('#txAccion').val(0);
					$('#form_botones').css('display','block');	
					$('#form_botones_delete').css('display','none');		
					
					$('#importado').prop('disabled',false);
					$('#nacional').prop('disabled',false);
					$('#marca_id').prop('readOnly',false);
					$('#tipo_id').prop('readOnly',false);
					$('#modelo_id').prop('readOnly',false);
					$('#marca_nom').prop('disabled',false);
					$('#tipo_nom').prop('disabled',false);
					$('#modelo_nom').prop('disabled',false);
					$("#labelModif").css('display','none');
					$("#txModif").css('display','none');
											
					$('#btnNuevo').css("pointer-events", "none");
					$('#btnNuevo').css("opacity", 0.5);
					$('#GrillaTablaAux').css("pointer-events", "none");
					$('#GrillaTablaAux').css("color", "#ccc");	
					$('#GrillaTablaAux Button').css("color", "#ccc");
					$('#GrillaTablaAux a').css("color", "#ccc");
				
				</script>
			
			<?php
		}else if($consulta==3){
			?>	
			<script>
					$('#txAccion').val(3);
					$('#form_botones').css('display','block');	
					$('#form_botones_delete').css('display','none');					

					$('#importado').prop('disabled',false);
					$('#nacional').prop('disabled',false);
					$('#marca_id').prop('readOnly',false);
					$('#tipo_id').prop('readOnly',false);
					$('#modelo_id').prop('readOnly',false);
					$('#marca_nom').prop('disabled',false);
					$('#tipo_nom').prop('disabled',false);
					$('#modelo_nom').prop('disabled',false);
					$("#labelModif").css('display','none');
					$("#txModif").css('display','none');
										
					$('#btnNuevo').css("pointer-events", "none");
					$('#btnNuevo').css("opacity", 0.5);
					$('#GrillaTablaAux').css("pointer-events", "none");
					$('#GrillaTablaAux').css("color", "#ccc");	
					$('#GrillaTablaAux Button').css("color", "#ccc");
					$('#GrillaTablaAux a').css("color", "#ccc");
			
			</script>
			<?php
		}
		//--------------------------Mensaje-------------------------------

		
		if(isset($_GET['mensaje']) and $_GET['mensaje'] != ''){
			
			switch ($_GET['mensaje'])
			{
					case 'grabado' : $_GET['mensaje'] = 'Datos Grabados.'; break;
					case 'delete' : $_GET['mensaje'] = 'Datos Borrados.'; break;
					default : $_GET['mensaje'] = '';
			}
			
		}
	
		Alert::begin([
			'id' => 'MensajeInfoJH',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoJH').alert('close');}, 5000)</script>";

		//-------------------------seccion de error-----------------------
		
		 	Pjax::begin(['id' => 'divError']);	
		 
				if(isset($_POST['error']) and $_POST['error'] != '') {  
					echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_POST['error'] . '</ul></div>';
					}

				if(isset($error) and $error != '') {  
				echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
				}

			Pjax::end();		
				
		 //------------------------------------------------------------------------------------------------------------------------				
    	
    	ActiveForm::end(); 
    	
    ?>    	

</div><!-- site-auxedit -->

<script>
	
	function CargarControles(origen_id,marca_id,tipo_id,modelo_id,marca_nom,tipo_nom,modelo_nom,modif)
	{
			event.stopPropagation();
			if(origen_id=='I'){$('#importado').prop('checked',true);}else{$('#nacional').prop('checked',true);}
			$("#marca_id").val(marca_id);
			$("#tipo_id").val(tipo_id);
			$("#modelo_id").val(modelo_id);
			$("#marca_nom").val(marca_nom);
			$("#tipo_nom").val(tipo_nom);
			$("#modelo_nom").val(modelo_nom);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	
	}

	function btnNuevoRodadoAforoModelo(){
		
			$('#txAccion').val(0);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
				
			$('#importado').prop('disabled',false);
			$('#nacional').prop('disabled',false);
			$('#marca_id').prop('readOnly',false);
			$('#tipo_id').prop('readOnly',false);
			$('#modelo_id').prop('readOnly',false);
			$('#marca_nom').prop('disabled',false);
			$('#tipo_nom').prop('disabled',false);
			$('#modelo_nom').prop('disabled',false);
					
			$("#marca_id").val('');
			$("#tipo_id").val('');
			$("#modelo_id").val('');
			$("#marca_nom").val('');
			$("#tipo_nom").val('');
			$("#modelo_nom").val('');
				
			$('#txModif').val('');
			$("#labelModif").css('display','none');
			$("#txModif").css('display','none');
					
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}
	
	function btnModificarAuxRodadoAforoModelo(origen_id,marca_id,tipo_id,modelo_id,marca_nom,tipo_nom,modelo_nom,modif){
		
			event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
			
			if(origen_id=='I'){$('#importado').prop('checked',true);}else{$('#nacional').prop('checked',true);}
			$("#marca_id").val(marca_id);
			$("#tipo_id").val(tipo_id);
			$("#modelo_id").val(modelo_id);
			$("#marca_nom").val(marca_nom);
			$("#tipo_nom").val(tipo_nom);
			$("#modelo_nom").val(modelo_nom);
			
			$('#importado').prop('disabled',false);
			$('#nacional').prop('disabled',false);
			$('#marca_id').prop('readOnly',false);
			$('#tipo_id').prop('readOnly',false);
			$('#modelo_id').prop('readOnly',false);
			$('#marca_nom').prop('disabled',false);
			$('#tipo_nom').prop('disabled',false);
			$('#modelo_nom').prop('disabled',false);
			
			$("#labelModif").css('display','none');
			$('#txModif').val('');
			$("#txModif").css('display','none');
					
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}
	
	function btnEliminarAuxRodadoAforoModelo(origen_id,marca_id,tipo_id,modelo_id,marca_nom,tipo_nom,modelo_nom,modif){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			if(origen_id=='I'){$('#importado').prop('checked',true);}else{$('#nacional').prop('checked',true);}
			$("#marca_id").val(marca_id);
			$("#tipo_id").val(tipo_id);
			$("#modelo_id").val(modelo_id);
			$("#marca_nom").val(marca_nom);
			$("#tipo_nom").val(tipo_nom);
			$("#modelo_nom").val(modelo_nom);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
						
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}

	function btnCancelarAuxRodadoAforoModelo(){
		
			$('.error-summary').css('display','none');
			$('#GrillaTablaAux').css("pointer-events", "all");
			$('#GrillaTablaAux').css("color", "#111111");
			$('#GrillaTablaAux a').css("color", "#337ab7");
			$('#GrillaTablaAux Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );
			
			$("#marca_id").val('');
			$("#tipo_id").val('');
			$("#modelo_id").val('');
			$("#marca_nom").val('');
			$("#tipo_nom").val('');
			$("#modelo_nom").val('');
			
			$('#importado').prop('disabled',true);
			$('#nacional').prop('disabled',true);
			$('#marca_id').prop('readOnly',true);
			$('#tipo_id').prop('readOnly',true);
			$('#modelo_id').prop('readOnly',true);
			$('#marca_nom').prop('disabled',true);
			$('#tipo_nom').prop('disabled',false);
			$('#modelo_nom').prop('disabled',true);
					
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','none');

			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
	}


	function btGrabarClick(){
		
			err = "";
			
			/*if ($("#est").val()=="")
			{
				err += "<li>Ingrese un Estado</li>";
			}
			if ($("#deuda_desde").val()=="")
			{
				err += "<li>Ingrese un monto de deuda desde</li>";
			}
			if ($("#deuda_hasta").val()=="")
			{
				err += "<li>Ingrese un monto de deuda hasta</li>";
			}
			if ($("#hono_min").val()=="")
			{
				err += "<li>Ingrese un honorario minimo</li>";
			}
			if ($("#hono_porc").val()=="")
			{
				err += "<li>Ingrese un porcensaje para el calculo de honorarios</li>";
			}
			if ($("#gastos").val()=="")
			{
				err += "<li>Ingrese un gasto judicial</li>";
			}
			if (!(isNaN($("#est").val())))
			{
				err += "<li>El campo estado debe ser un caracter</li>";
			}
			if (isNaN($("#deuda_desde").val()))
			{
				err += "<li>El campo deuda desde debe ser un numero</li>";
			}
			if (isNaN($("#deuda_hasta").val()))
			{
				err += "<li>El campo deuda_hasta debe ser un numero</li>";
			}
			if (isNaN($("#hono_min").val()))
			{
				err += "<li>El campo honorarios minimos debe ser un numero</li>";
			}
			if (isNaN($("#hono_porc").val()))
			{
				err += "<li>El campo porcentaje para el calculo de honorarios debe ser un numero</li>";
			}
			if (isNaN($("#gastos").val()))
			{
				err += "<li>El campo gastos debe ser un numero</li>";
			}
			if ($("#deuda_desde").val() < 0)
			{
				err += "<li>El campo deuda desde debe ser mayor o igual a cero</li>";
			}
			if ($("#deuda_hasta").val() < 0)
			{
				err += "<li>El campo deuda hasta debe ser mayor o igual a cero</li>";
			}
			if ($("#deuda_desde").val() > $("#deuda_hasta").val())
			{
				err += "<li>Deuda desde debe ser menor o igual a deuda hasta</li>";
			}
			if ($("#hono_min").val() < 0)
			{
				err += "<li>El campo honoraios minimos debe ser mayor a cero</li>";
			}
			if ($("#hono_porc").val() < 0 || $("#hono_porc").val() > 100)
			{
				err += "<li>El campo porcensatel para el calculo de honoraios debe ser mayor a cero y menor a 100</li>";
			}
			if ($("#gastos").val() < 0)
			{
				err += "<li>El campo gastos judiciales debe ser mayor o igual a cero</li>";
			}*/

			if (err == "")
			{
				$('#supuesto').prop('disabled',false);
				$("#frmRodadoAforoModelo").submit();
			}else {
				$.pjax.reload(
				{
					container:"#divError",
					data:{
							error:err
						},
					method:"POST"
				});
			}
	}
		
	$(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
	});

</script>
