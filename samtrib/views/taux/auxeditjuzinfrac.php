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

$title = 'Tipos de Infracciones';
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
    			if ($model->accesoedita > 0) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxJuzTInfrac();']) 
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
									'onclick' => 'CargarControles('.$model['infrac_id'].','.
																	$model['origen'].',"'.
																	$model['norma'].'",'.
																	$model['art'].',"'.
																	$model['inc'].'","'.
																	$model['nombre'].'",'.
																	$model['item_id'].',"'.
																	$model['est'].'",'.
																	$model['multa_min'].','.
																	$model['multa_max'].',"'.
																	$model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'cod','header' => 'Codigo', 'contentOptions'=>['style'=>'width:30px;text-align:center;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=>'nombre','header' => 'Nombre' ,'contentOptions'=>['style'=>'width:250px;text-align:left;font-size:12px;', 'class' => 'grilla']],           		
            		['attribute'=>'est','header' => 'Estado', 'contentOptions'=>['style'=>'width:30px;text-align:center;font-size:12px;', 'class' => 'grilla']],
            		            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:35px;text-align:center;'],'template' => (($model->accesoedita > 0) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxJuzTInfrac('.$model['infrac_id'].','.
																								  $model['origen'].',"'.
																								  $model['norma'].'",'.
																								  $model['art'].',"'.
																								  $model['inc'].'","'.
																								  $model['nombre'].'",'.
																								  $model['item_id'].',"'.
																								  $model['est'].'",'.
																								  $model['multa_min'].','.
																								  $model['multa_max'].',"'.
																								  $model['modif'].'")'
													]				 			 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxJuzTInfrac('.$model['infrac_id'].','.
																								 $model['origen'].',"'.
																								 $model['norma'].'",'.
																								 $model['art'].',"'.
																								 $model['inc'].'","'.
																								 $model['nombre'].'",'.
																								 $model['item_id'].',"'.
																								 $model['est'].'",'.
																								 $model['multa_min'].','.
																								 $model['multa_max'].',"'.
																								 $model['modif'].'")'
																]
			            									);
				            						}
							    			]
							    	   ],
							    	   
								],
							]); 	    

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmJuzgadoTInfrac']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr height='35px'>
			<td width='44px'><label>Codigo: </label></td>
			<td>
				<?= Html::input('text', 'txCod', $cod, ['class' => 'form-control','id'=>'txCod','maxlength'=> '10','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
			<td width='60px' align='right'><label>Nombre: </label></td>		
			<td>
			<?= Html::input('text', 'txNombre',$nombre,['id' => 'txNombre','class' => 'form-control','maxlength'=>'50' ,'style' => 'width:350px;', 'disabled' => true ]); ?>
			</td>
				<td width='60px' align='right'><label>Estado: </label></td>
			<td>
				 <?= Html::input('text', 'est',$est,['id' => 'est','class' => 'form-control','maxlength'=>'1' ,'style' => 'width:40px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr height='35px' >
			<td width='70px' align='right'><label>Cod. Origen: </label></td>		
			<td>
			   <?= Html::input('text', 'origen', $origen, ['class' => 'form-control','id'=>'origen','maxlength'=> '10','style'=>'width:40px;', 'disabled' => true]); ?>
			</td>
			<td width='70px' align='right'><label>Norma: </label></td>		
			<td width='80px'>
			  <?= Html::input('text', 'norma',$norma,['id' => 'norma','class' => 'form-control','maxlength'=>'10' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
			</td>
			<td width='70px' align='right'><label>Articulo: </label></td>
			<td>
				<?= Html::input('text', 'art',$art,['id' => 'art','class' => 'form-control','maxlength'=>'13' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		</table>

		<table border='0'>
		<tr height='35px'>
			<td width='40px'><label>Inciso: </label></td>
			<td>
				<?= Html::input('text', 'inc', $inc, ['class' => 'form-control','id'=>'inc','maxlength'=> '6','style'=>'width:100px;', 'disabled' => true]); ?>
			</td>
			 <td width='70px' align='right'><label>Item: </label></td>
			<td>
				<?=  Html::dropDownList('item_id',$item_id,utb::getAux('item','item_id','nombre','0'),['id'=>'item_id','class' => 'form-control', 'disabled' => true]);?>
			</td>
		</tr>
		</table>

		<table border='0'>
		<tr>
			<td width='80px'><label>Multa Minima: </label></td>
			<td>
				 <?= Html::input('text', 'multa_min',$multa_min,['id' => 'multa_min','class' => 'form-control','maxlength'=>'13' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
			</td>
			<td width='100px' align='right'><label>Multa Maxima: </label></td>
			<td>
				 <?= Html::input('text', 'multa_max',$multa_max,['id' => 'multa_max','class' => 'form-control','maxlength'=>'13' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
			</td>
		</tr>
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
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxJuzTInfrac()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxJuzTInfrac()']); 
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
					
					$('#txCod').prop('readOnly',false);
					$('#origen').prop('disabled',false);
					$('#norma').prop('disabled',false);
					$('#art').prop('disabled',false);
					$('#inc').prop('disabled',false);
					$('#txNombre').prop('disabled',false);
					$('#item_id').prop('disabled',false);
					$('#est').prop('disabled',false);
					$('#multa_min').prop('disabled',false);
					$('#multa_max').prop('disabled',false);
											
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

					$('#txCod').prop('readOnly',true);
					$('#origen').prop('disabled',false);
					$('#norma').prop('disabled',false);
					$('#art').prop('disabled',false);
					$('#inc').prop('disabled',false);
					$('#txNombre').prop('disabled',false);
					$('#item_id').prop('disabled',false);
					$('#est').prop('disabled',false);
					$('#multa_min').prop('disabled',false);
					$('#multa_max').prop('disabled',false);
										
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
			'id' => 'MensajeInfoJTI',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoJTI').alert('close');}, 5000)</script>";

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

	function CargarControles(infrac_id,origen,norma,art,inc,nombre,item_id,est,multa_min,multa_max,modif)
	{
			event.stopPropagation();
			$("#txCod").val(infrac_id);
			$("#origen").val(origen);
			$("#norma").val(norma);
			$("#art").val(art);
			$("#inc").val(inc);
			$("#txNombre").val(nombre);
			$("#item_id").val(item_id);
			$("#est").val(est);
			$("#inc").val(inc);
			$("#multa_min").val(multa_min);
			$("#multa_max").val(multa_max);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	
	}

	function btnNuevoAuxJuzTInfrac(){
		
			$('#txAccion').val(0);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
				
			$('#txCod').prop('readOnly',true);
			$('#origen').prop('disabled',false);
			$('#norma').prop('disabled',false);
			$('#art').prop('disabled',false);
			$('#inc').prop('disabled',false);
			$('#txNombre').prop('disabled',false);
			$('#item_id').prop('disabled',false);
			$('#est').prop('disabled',true);
			$('#multa_min').prop('disabled',false);
			$('#multa_max').prop('disabled',false);
					
			$("#txCod").val('');
			$("#item_id option:first-of-type").attr("selected", "selected");
			$("#origen").val('');
			$("#norma").val('');
			$("#art").val('');
			$("#inc").val('');
			$("#txNombre").val('');
			$("#est").val('');
			$("#multa_min").val('');
			$("#multa_max").val('');
				
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
	
	function btnModificarAuxJuzTInfrac(infrac_id,origen,norma,art,inc,nombre,item_id,est,multa_min,multa_max,modif){
		
			event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
			
			$('#txCod').prop('readOnly',true);
			$('#origen').prop('disabled',false);
			$('#norma').prop('disabled',false);
			$('#art').prop('disabled',false);
			$('#inc').prop('disabled',false);
			$('#txNombre').prop('disabled',false);
			$('#item_id').prop('disabled',false);
			$('#est').prop('disabled',true);
			$('#multa_min').prop('disabled',false);
			$('#multa_max').prop('disabled',false);
			
			$("#txCod").val(infrac_id);
			$("#origen").val(origen);
			$("#norma").val(norma);
			$("#art").val(art);
			$("#inc").val(inc);
			$("#txNombre").val(nombre);
			$("#item_id").val(item_id);
			$("#est").val(est);
			$("#inc").val(inc);
			$("#multa_min").val(multa_min);
			$("#multa_max").val(multa_max);
						
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
	
	function btnEliminarAuxJuzTInfrac(infrac_id,origen,norma,art,inc,nombre,item_id,est,multa_min,multa_max,modif){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$("#txCod").val(infrac_id);
			$("#origen").val(origen);
			$("#norma").val(norma);
			$("#art").val(art);
			$("#inc").val(inc);
			$("#txNombre").val(nombre);
			$("#item_id").val(item_id);
			$("#est").val(est);
			$("#inc").val(inc);
			$("#multa_min").val(multa_min);
			$("#multa_max").val(multa_max);
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

	function btnCancelarAuxJuzTInfrac(){
		
			$('.error-summary').css('display','none');
			$('#GrillaTablaAux').css("pointer-events", "all");
			$('#GrillaTablaAux').css("color", "#111111");
			$('#GrillaTablaAux a').css("color", "#337ab7");
			$('#GrillaTablaAux Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );
			
			$('#txCod').prop('readOnly',true);
			$('#origen').prop('disabled',true);
			$('#norma').prop('disabled',true);
			$('#art').prop('disabled',true);
			$('#inc').prop('disabled',true);
			$('#txNombre').prop('disabled',true);
			$('#item_id').prop('disabled',true);
			$('#est').prop('disabled',true);
			$('#multa_min').prop('disabled',true);
			$('#multa_max').prop('disabled',true);
			
			$("#txCod").val('');
			$("#item_id option:first-of-type").attr("selected", "selected");
			$("#origen").val('');
			$("#norma").val('');
			$("#art").val('');
			$("#inc").val('');
			$("#txNombre").val('');
			$("#est").val('');
			$("#multa_min").val('');
			$("#multa_max").val('');
					
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','none');
			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
	}


	function btGrabarClick(){
		
			err = "";
			
			if ($("#txNombre").val()=="")
			{
				err += "<li>Ingrese un nombre</li>";
			}
			if ($("#origen").val()=="")
			{
				err += "<li>Ingrese un codido de origen</li>";
			}
			if ($("#norma").val()=="")
			{
				err += "<li>Ingrese una norma</li>";
			}
			if ($("#art").val()=="")
			{
				err += "<li>Ingrese un articulo</li>";
			}
			if ($("#inc").val()=="")
			{
				err += "<li>Ingrese un inciso</li>";
			}
			if ($("#multa_min").val()=="")
			{
				err += "<li>Ingrese una multa minima</li>";
			}
			if ($("#multa_max").val()=="")
			{
				err += "<li>Ingrese una multa maxima</li>";
			}
			if (isNaN($("#origen").val()))
			{
				err += "<li>El campo origen debe ser un numero</li>";
			}
			if (isNaN($("#art").val()))
			{
				err += "<li>El campo articulo debe ser un numero</li>";
			}
			if (isNaN($("#multa_min").val()))
			{
				err += "<li>El campo multa minima debe ser un numero</li>";
			}
			if (isNaN($("#multa_max").val()))
			{
				err += "<li>El campo multa maxima debe ser un numero</li>";
			}
			if ($("#multa_min").val() < 0)
			{
				err += "<li>El campo multa minima debe ser mayor o igual a cero</li>";
			}
			if ($("#multa_max").val() < 0)
			{
				err += "<li>El campo multa maxima debe ser mayor o igual a cero</li>";
			}
			if ($("#multa_min").val() > $("#multa_max").val())
			{
				err += "<li>multa maxima debe ser mayor o igual a multa minima</li>";
			}

			if (err == "")
			{
				$("#frmJuzgadoTInfrac").submit();
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
