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

$title = 'Tipos de Texto';
$this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares','url' => ['taux']];
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
    			if ($model->accesoedita > 0) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxJuzgadoTexto();']) 
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
									'onclick' => 'CargarControles("'.$model['accion'].'",'.
																	 $model['tipo_fallo'].','.
																	 $model['origen'].','.
																	 $model['texto_id'].','.
																	 $model['texto_id_pie'].',"'.
																	 $model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'accion','header' => 'Accion', 'contentOptions'=>['style'=>'width:30px;text-align:center;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=>'tipo_fallo','header' => 'Tipo de Fallo', 'value' => function($data) { return (utb::getCampo('juz_tfallo','cod='.$data['tipo_fallo'])); }
            		,'contentOptions'=>['style'=>'width:250px;text-align:left;font-size:12px;', 'class' => 'grilla']],           		
            		['attribute'=>'origen','header' => 'Origen', 'contentOptions'=>['style'=>'width:30px;text-align:center;font-size:12px;', 'class' => 'grilla']],
            		            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:35px;text-align:center;'],'template' => (($model->accesoedita > 0) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxJuzgadoTexto("'.$model['accion'].'",'.
																								     $model['tipo_fallo'].','.
																								     $model['origen'].','.
																								     $model['texto_id'].','.
																								     $model['texto_id_pie'].',"'.
																								     $model['modif'].'")'
													]				 			 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxJuzgadoTexto("'.$model['accion'].'",'.
																								    $model['tipo_fallo'].','.
																								    $model['origen'].','.
																								    $model['texto_id'].','.
																								    $model['texto_id_pie'].',"'.
																								    $model['modif'].'")'
																]
			            									);
				            						}
							    			]
							    	   ],
							    	   
								],
							]); 	    

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmJuzgadoTexto']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr height='35px'>
			<td width='44px'><label>Accion: </label></td>
			<td>
				<?= Html::input('text', 'accion', $accion, ['class' => 'form-control','id'=>'accion','maxlength'=> '20','style'=>'width:200px;', 'readonly' => true]); ?>
			</td>
			<td width='100px' align='right'><label>Tipo de Fallo: </label></td>		
			<td>
			<?=  Html::dropDownList('tipo_fallo',$tipo_fallo,utb::getAux('juz_tfallo','cod','nombre','0'),['id'=>'tipo_fallo','class' => 'form-control', 'disabled' => true]);?>
			</td>
				<td width='60px' align='right'><label>Origen: </label></td>
			<td>
				 <?= Html::input('text', 'origen',$origen,['id' => 'origen','class' => 'form-control','maxlength'=>'1' ,'style' => 'width:40px;', 'readonly' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr height='35px' >

			<td width='40px' align='left'><label>Texto: </label></td>		
			<td width='80px'>
			    <?=  Html::dropDownList('texto_id',$texto_id,utb::getAux('texto','texto_id','nombre','0'),['id'=>'texto_id','class' => 'form-control', 'disabled' => true]);?>
			</td>
			<td width='100px' align='right'><label>Pie de Texto: </label></td>
			<td>
				<?=  Html::dropDownList('texto_id_pie',$texto_id_pie,utb::getAux('texto','texto_id','nombre','0'),['id'=>'texto_id_pie','class' => 'form-control', 'disabled' => true]);?>
			</td>
		</tr>
		</table>
		
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
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxJuzgadoTexto()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxJuzgadoTexto()']); 
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
					
					$('#accion').prop('readOnly',false);
					$('#tipo_fallo').prop('readOnly',false);
					$('#origen').prop('readOnly',false);
					$('#texto_id').prop('disabled',false);
					$('#texto_id_pie').prop('disabled',false);
											
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

					$('#accion').prop('readOnly',true);
					$('#tipo_fallo').prop('readOnly',true);
					$('#origen').prop('readOnly',true);
					$('#texto_id').prop('disabled',false);
					$('#texto_id_pie').prop('disabled',false);
										
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
			'id' => 'MensajeInfoJT',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoJT').alert('close');}, 5000)</script>";

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

	function CargarControles(accion,tipo_fallo,origen,texto_id,texto_id_pie,modif)
	{
			event.stopPropagation();
			$("#accion").val(accion);
			$("#tipo_fallo").val(tipo_fallo);
			$("#origen").val(origen);
			$("#texto_id").val(texto_id);
			$("#texto_id_pie").val(texto_id_pie);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	
	}

	function btnNuevoAuxJuzgadoTexto(){
		
			$('#txAccion').val(0);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
				
			$('#accion').prop('readOnly',false);
			$('#tipo_fallo').prop('disabled',false);
			$('#origen').prop('readOnly',false);
			$('#texto_id').prop('disabled',false);
			$('#texto_id_pie').prop('disabled',false);
					
			$("#accion").val('');
			$("#origen").val('');
			$("#tipo_fallo option:first-of-type").attr("selected", "selected");
			$("#texto_id option:first-of-type").attr("selected", "selected");
			$("#texto_id_pie option:first-of-type").attr("selected", "selected");
				
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
	
	function btnModificarAuxJuzgadoTexto(accion,tipo_fallo,origen,texto_id,texto_id_pie,modif){
		
			event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
			
			$('#accion').prop('readOnly',true);
			$('#tipo_fallo').prop('disabled',true);
			$('#origen').prop('readOnly',true);
			$('#texto_id').prop('disabled',false);
			$('#texto_id_pie').prop('disabled',false);
					
			$("#accion").val(accion);
			$("#tipo_fallo").val(tipo_fallo);
			$("#origen").val(origen);
			$("#texto_id").val(texto_id);
			$("#texto_id_pie").val(texto_id_pie);
						
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
	
	function btnEliminarAuxJuzgadoTexto(accion,tipo_fallo,origen,texto_id,texto_id_pie,modif){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$("#accion").val(accion);
			$("#tipo_fallo").val(tipo_fallo);
			$("#origen").val(origen);
			$("#texto_id").val(texto_id);
			$("#texto_id_pie").val(texto_id_pie);
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

	function btnCancelarAuxJuzgadoTexto(){
		
			$('.error-summary').css('display','none');
			$('#GrillaTablaAux').css("pointer-events", "all");
			$('#GrillaTablaAux').css("color", "#111111");
			$('#GrillaTablaAux a').css("color", "#337ab7");
			$('#GrillaTablaAux Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );
			
			$('#accion').prop('readOnly',true);
			$('#tipo_fallo').prop('disabled',true);
			$('#origen').prop('readOnly',true);
			$('#texto_id').prop('disabled',true);
			$('#texto_id_pie').prop('disabled',true);
			
			$("#accion").val('');
			$("#origen").val('');
			$("#tipo_fallo option:first-of-type").attr("selected", "selected");
			$("#texto_id option:first-of-type").attr("selected", "selected");
			$("#texto_id_pie option:first-of-type").attr("selected", "selected");
					
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','none');
			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
	}


	function btGrabarClick(){
		
			err = "";
			
			if ($("#accion").val()=="")
			{
				err += "<li>Ingrese una accion</li>";
			}
			if ($("#origen").val()=="")
			{
				err += "<li>Ingrese un codido de origen</li>";
			}
			if (isNaN($("#origen").val()))
			{
				err += "<li>El campo origen debe ser un numero</li>";
			}

			if (err == "")
			{
				$('#tipo_fallo').prop('disabled',false);
				$("#frmJuzgadoTexto").submit();
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
