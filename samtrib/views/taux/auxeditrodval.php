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

$title = 'Valores de Rodado';
$this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares','url' => ['taux']];
$this->params['breadcrumbs'][] = $title;

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

if (isset($consulta) == null) $consulta = 1;
if (isset($_GET['mensaje']) == null) $_GET['mensaje'] = '';

?>

<script type='text/javascript'>

$(document).ready(function(){
	
	$('#btnBuscar').click(function(){
		
		if($('#anioval_buscador').val()==""){
			$('#error_buscador').css('display','block');
		}else{
			$('#error_buscador').css('display','none');
			$.pjax.reload({
							container:"#idGrid",
							 data:{
								anioval:$("#anioval_buscador").val(),
								gru:$("#gru_buscador").val(),
								anio:$("#anio_buscador").val()
								},
								method:"POST"
							});			
						}						
					})					
				})

</script>

<div class="site-auxedit">
	<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
    	<td align='right'>
    		<?php 
    			if ($model->accesoedita > 0) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxRodadoVal();']) 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    
    <?php  $form = ActiveForm::begin(['id' => 'form-rodado-val']); ?>
   <table border='0'>
	   <tr>
	   	<td width='110px'><label>Año de Valuación:</label></td>
	   	<td><?= Html::input('text', 'anioval_buscador', $anioval_buscador, ['class' => 'form-control','id'=>'anioval_buscador','maxlength'=> '4','style'=>'width:50px;']); ?></td>
	   	<td width='80px' align='right'><label>Categoria:</label></td>
	   	<td><?= Html::dropDownList('gru_buscador',$gru_buscador,utb::getAux('rodado_tcat','cod','nombre',0),['id'=>'gru_buscador','style'=>'width:120px;','class' => 'form-control']);?></td>
	   	<td width='60px' align='right'><label>Año:</label></td>
	   	<td><?= Html::input('text', 'anio_buscador', $anio_buscador, ['class' => 'form-control','id'=>'anio_buscador','maxlength'=> '4','style'=>'width:50px;']); ?></td>
	   </tr>
   </table> 

   	<table border='0'>
		<tr>
			<td align='right' width='479px' height='40px'>	        
			<?php     
				echo Html::Button('Buscar', ['class' => 'btn btn-primary', 'id' => 'btnBuscar']) 
	    	?>															                        
	       </td>
		</tr>
	</table>
	
    <?php
		//-------------------------seccion de error buscador-----------------------
	  
		echo '<div id="error_buscador" style="display:none;" class="error-summary"><ul>Debe ingresar el año de valuacion</ul></div>';
				
	 //--------------------------------------------------------------------	
   ?>
	
    <?php ActiveForm::end(); 
    	 	
    	Pjax::begin(['id' => 'idGrid']);// comienza bloque de grilla 
    	
    	echo "<script>$(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
		});</script>"; 	 
    	 	 
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $model->buscarValorDeMejora($_POST['anioval'],$_POST['gru'],$_POST['anio']),
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid) 
							{
								return [
									'onclick' => 'CargarControles('.$model['anioval'].','.
																	$model['gru'].','.
																	$model['anio'].','.
																	$model['pesodesde'].','.
																	$model['pesohasta'].','.
																	$model['valor'].',"'.
																	$model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'anioval','header' => 'Año Valor Normal ', 'contentOptions'=>['style'=>'width:30px;text-align:center;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=>'gru','header' => 'Grupo' ,'value' => function($data) { return (utb::getCampo('rodado_tcat','cod='.$data['gru'])); },
					'contentOptions'=>['style'=>'width:120px;text-align:left;font-size:12px;', 'class' => 'grilla']],           		
            		['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'width:70px;text-align:left;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=>'pesodesde','header' => 'Peso Desde' ,'contentOptions'=>['style'=>'width:140px;text-align:left;font-size:12px;', 'class' => 'grilla']],           		
            		['attribute'=>'pesohasta','header' => 'Peso Hasta', 'contentOptions'=>['style'=>'width:140px;text-align:left;font-size:12px;', 'class' => 'grilla']],
            		            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:50px;text-align:center;'],'template' => (($model->accesoedita > 0) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxRodadoVal('.$model['anioval'].','.
																								  $model['gru'].','.
																								  $model['anio'].','.
																								  $model['pesodesde'].','.
																								  $model['pesohasta'].','.
																								  $model['valor'].',"'.
																								  $model['modif'].'")'
													]				 			 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxRodadoVal('.$model['anioval'].','.
																								 $model['gru'].','.
																								 $model['anio'].','.
																								 $model['pesodesde'].','.
																								 $model['pesohasta'].','.
																								 $model['valor'].',"'.
																								 $model['modif'].'")' 
																]
			            									);
				            						}
							    			]
							    	   ],
							    	   
								],
							]);
		Pjax::end(); // fin bloque de la grilla					 	    

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmRodadoVal']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr height='35px'>
			<td width='105px'><label>Año de Valuacion: </label></td>
			<td>
				<?= Html::input('text', 'anioval', $anioval, ['class' => 'form-control','id'=>'anioval','maxlength'=> '4','style'=>'width:50px;', 'readonly' => true]); ?>
			</td>
			<td width='80px' align='right'><label>Categoria: </label></td>		
			<td>
				<?= Html::dropDownList('gru',$gru,utb::getAux('rodado_tcat','cod','nombre',0),['id'=>'gru','class' => 'form-control','style' => 'width:190px;','disabled' => true]);?>
			</td>
				<td width='60px' align='right'><label>Año: </label></td>
			<td>
				 <?= Html::input('text', 'anio',$anio,['id' => 'anio','class' => 'form-control','maxlength'=>'4' ,'style' => 'width:50px;', 'readonly' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr height='35px' >
			<td width='75px' align='left'><label>Peso Desde: </label></td>		
			<td>
			   <?= Html::input('text', 'pesodesde', $pesodesde, ['class' => 'form-control','id'=>'pesodesde','maxlength'=> '12','style'=>'width:100px;', 'readonly' => true]); ?>
			</td>
			<td width='90px' align='right'><label>Peso Hasta: </label></td>		
			<td width='80px'>
			  <?= Html::input('text', 'pesohasta',$pesohasta,['id' => 'pesohasta','class' => 'form-control','maxlength'=>'12' ,'style' => 'width:100px;', 'readonly' => true]); ?>
			</td>
			<td width='70px' align='right'><label>Valor: </label></td>
			<td>
				<?= Html::input('text', 'valor',$valor,['id' => 'valor','class' => 'form-control','maxlength'=>'12' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
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
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxRodadoVal()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxRodadoVal()']); 
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
					
					$('#anioval').prop('readOnly',false);
					$('#gru').prop('disabled',false);
					$('#anio').prop('readOnly',false);
					$('#pesodesde').prop('readOnly',false);
					$('#pesohasta').prop('readOnly',false);
					$('#valor').prop('disabled',false);
					
					$('#btnBuscar').css("pointer-events", "none");
					$('#btnBuscar').css("opacity", 0.5);
					$('#anioval_buscador').prop('readOnly',true);
					$('#gru_buscador').prop('disabled',true);
					$('#anio_buscador').prop('readOnly',true);						
											
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

					$('#anioval').prop('readOnly',true);
					$('#gru').prop('disabled',true);
					$('#anio').prop('readOnly',true);
					$('#pesodesde').prop('readOnly',true);
					$('#pesohasta').prop('readOnly',true);
					$('#valor').prop('disabled',false);
					
					$('#btnBuscar').css("pointer-events", "none");
					$('#btnBuscar').css("opacity", 0.5);
					$('#anioval_buscador').prop('readOnly',true);
					$('#gru_buscador').prop('disabled',true);
					$('#anio_buscador').prop('readOnly',true);	
										
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
			'id' => 'MensajeInfoVR',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoVR').alert('close');}, 5000)</script>";

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

	function CargarControles(anioval,gru,anio,pesodesde,pesohasta,valor,modif)
	{
			event.stopPropagation();
			$("#anioval").val(anioval);
			$("#gru").val(gru);
			$("#anio").val(anio);
			$("#pesodesde").val(pesodesde);
			$("#pesohasta").val(pesohasta);
			$("#valor").val(valor);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	
	}

	function btnNuevoAuxRodadoVal(){
		
			$('#txAccion').val(0);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
				
			$('#anioval').prop('readOnly',false);
			$('#gru').prop('disabled',false);
			$('#anio').prop('readOnly',false);
			$('#pesodesde').prop('readOnly',false);
			$('#pesohasta').prop('readOnly',false);
			$('#valor').prop('disabled',false);
					
			$("#anioval").val('');
			$("#gru option:first-of-type").attr("selected", "selected");
			$("#anio").val('');
			$("#pesodesde").val('');
			$("#pesohasta").val('');
			$("#valor").val('');
				
			$('#txModif').val('');
			$("#labelModif").css('display','none');
			$("#txModif").css('display','none');
			
			$('#btnBuscar').css("pointer-events", "none");
			$('#btnBuscar').css("opacity", 0.5);
			$('#anioval_buscador').prop('readOnly',true);
			$('#gru_buscador').prop('disabled',true);
			$('#anio_buscador').prop('readOnly',true);	
					
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}
	
	function btnModificarAuxRodadoVal(anioval,gru,anio,pesodesde,pesohasta,valor,modif){
		
			event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
			
			$('#anioval').prop('readOnly',true);
			$('#gru').prop('disabled',true);
			$('#anio').prop('readOnly',true);
			$('#pesodesde').prop('readOnly',true);
			$('#pesohasta').prop('readOnly',true);
			$('#valor').prop('disabled',false);
			
			$("#anioval").val(anioval);
			$("#gru").val(gru);
			$("#anio").val(anio);
			$("#pesodesde").val(pesodesde);
			$("#pesohasta").val(pesohasta);
			$("#valor").val(valor);
						
			$("#labelModif").css('display','none');
			$('#txModif').val('');
			$("#txModif").css('display','none');
			
			$('#btnBuscar').css("pointer-events", "none");
			$('#btnBuscar').css("opacity", 0.5);
			$('#anioval_buscador').prop('readOnly',true);
			$('#gru_buscador').prop('disabled',true);
			$('#anio_buscador').prop('readOnly',true);	
					
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}
	
	function btnEliminarAuxRodadoVal(anioval,gru,anio,pesodesde,pesohasta,valor,modif){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$("#anioval").val(anioval);
			$("#gru").val(gru);
			$("#anio").val(anio);
			$("#pesodesde").val(pesodesde);
			$("#pesohasta").val(pesohasta);
			$("#valor").val(valor);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
			
			$('#btnBuscar').css("pointer-events", "none");
			$('#btnBuscar').css("opacity", 0.5);
			$('#anioval_buscador').prop('readOnly',true);
			$('#gru_buscador').prop('disabled',true);
			$('#anio_buscador').prop('readOnly',true);	
						
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}

	function btnCancelarAuxRodadoVal(){
		
			$('.error-summary').css('display','none');
			$('#GrillaTablaAux').css("pointer-events", "all");
			$('#GrillaTablaAux').css("color", "#111111");
			$('#GrillaTablaAux a').css("color", "#337ab7");
			$('#GrillaTablaAux Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );
			
			$('#anioval').prop('readOnly',true);
			$('#gru').prop('disabled',true);
			$('#anio').prop('readOnly',true);
			$('#pesodesde').prop('readOnly',true);
			$('#pesohasta').prop('readOnly',true);
			$('#valor').prop('disabled',true);
			
			$("#anioval").val('');
			$("#gru option:first-of-type").attr("selected", "selected");
			$("#anio").val('');
			$("#pesodesde").val('');
			$("#pesohasta").val('');
			$("#valor").val('');
			
			$('#btnBuscar').css("pointer-events", "all");
			$('#btnBuscar').css("opacity", 1);
			$('#anioval_buscador').prop('readOnly',false);
			$('#gru_buscador').prop('disabled',false);
			$('#anio_buscador').prop('readOnly',false);	
					
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','none');
			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
	}


	function btGrabarClick(){
		
			err = "";
			
			if ($("#anioval").val()=="")
			{
				err += "<li>Ingrese un año de valor</li>";
			}
			if ($("#anio").val()=="")
			{
				err += "<li>Ingrese un año</li>";
			}
			if ($("#pesodesde").val()=="")
			{
				err += "<li>Ingrese un peso desde</li>";
			}
			if ($("#pesohasta").val()=="")
			{
				err += "<li>Ingrese un peso hasta</li>";
			}
			if ($("#valor").val()=="")
			{
				err += "<li>Ingrese un valor</li>";
			}
			if (isNaN($("#anioval").val()))
			{
				err += "<li>El campo año valor debe ser un numero</li>";
			}
			if (isNaN($("#anio").val()))
			{
				err += "<li>El campo año debe ser un numero</li>";
			}
			if (isNaN($("#pesodesde").val()))
			{
				err += "<li>El campo peso desde debe ser un numero</li>";
			}
			if (isNaN($("#pesohasta").val()))
			{
				err += "<li>El campo peso hasta debe ser un numero</li>";
			}
			if (isNaN($("#valor").val()))
			{
				err += "<li>El campo valor desde debe ser un numero</li>";
			}
			if ($("#pesodesde").val() < 0)
			{
				err += "<li>El campo peso desde debe ser mayor o igual a cero</li>";
			}
			if ($("#pesohasta").val() < 0)
			{
				err += "<li>El campo peso hasta debe ser mayor o igual a cero</li>";
			}
			if ($("#valor").val() < 0)
			{
				err += "<li>El campo valor debe ser mayor o igual a cero</li>";
			}
			if ($("#anioval").val() <= 0)
			{
				err += "<li>El campo año valor debe ser mayor a cero</li>";
			}
			if ($("#anio").val() <= 0)
			{
				err += "<li>El campo año debe ser mayor a cero</li>";
			}
			if ($("#pesodesde").val() > $("#pesohasta").val())
			{
				err += "<li>peso hasta debe ser mayor o igual a peso desde</li>";
			}

			if (err == "")
			{
				$('#gru').prop('disabled',false);
				$("#frmRodadoVal").submit();
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
