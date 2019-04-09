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

$title = 'Categoría Inscripción a Tributo';
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
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxTribInscripCat();']) 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
   
    <!-- -------------------- Inicio comboBox que filtra grilla por tipo de objeto ---------------  -->
	
    <?php  $form = ActiveForm::begin(['id' => 'form-trib-filtro']); ?>
    </br> 
    <table>
    <tr>
    <td><label>Tributo</label></td>    	
	<td>	
	<?=  Html::dropDownList('selectTributo',"",utb::getAux('trib','trib_id','nombre',0),['id'=>'selectTributo','prompt'=>'Seleccionar...',
				'onchange'=>'$.pjax.reload({container:"#idGrid",data:{cod:this.value},method:"POST"})','class' => 'form-control']);?>
	</td>			 
	</tr>
	</table>
	<?php		
	ActiveForm::end();
	?>
	</br>
	<?php
	
   // --------------------------------------- Fin del comboBox --------------------------------------------

		Pjax::begin(['id' => 'idGrid']);// comienza bloque de grilla
		
		 $criterio = "";
             	    	     	  
    	 if (isset($_POST['cod']) and $_POST['cod'] !== null and $_POST['cod'] !=='') 
    	 {
    	 	$criterio = "trib_id=".$_POST['cod'];
    	 }
		    	 	 
		echo "<script> $('.summary').css('display','none'); </script>";
    	echo "<script> $('tr.grilla th').css('font-size',12); </script>";   	 	 
		    	 	 
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $model->buscarTrubuto($criterio),
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid) 
							{
								return [
									'onclick' => 'CargarControles('.$model['trib_id'].',"'.
																	$model['cat'].'","'.
																	$model['nombre'].'","'.
																	$model['modif'].'")'
								];
							},
			'columns' => [
					['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'width:75%;text-align:left;', 'class' => 'grilla']],          		
            		['attribute'=>'cat','header' => 'Categoria', 'contentOptions'=>['style'=>'width:15%;text-align:center;', 'class' => 'grilla']],
            		            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:10px;text-align:center;','class'=>'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxTribInscripCat('.$model['trib_id'].',"'.
																								    $model['cat'].'","'.
																								    $model['nombre'].'","'.
																								    $model['modif'].'")'
													]				 			 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxTribInscripCat('.$model['trib_id'].',"'.
																								   $model['cat'].'","'.
																								   $model['nombre'].'","'.
																								   $model['modif'].'")'
																]
			            									);
				            						}
							    			]
							    	   ],
							    	   
								],
							]); 	    
		Pjax::end(); // fin bloque de la grilla
		
	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmTribInscripCat']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr height='35px'>
			<td width='44px'><label>Tributo: </label></td>
			<td>
				<?= Html::dropDownList('trib_id',$trib_id,utb::getAux('trib','trib_id','nombre','0'),['id'=>'trib_id','class' => 'form-control', 'disabled' => true]);?>
			</td>
			<td width='100px' align='right'><label>Categoria: </label></td>		
			<td>
				<?= Html::input('text', 'cat', $cat, ['class' => 'form-control','id'=>'cat','maxlength'=> '2','style'=>'width:200px;', 'readonly' => true]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr height='35px' >

			<td width='40px' align='left'><label>Nombre: </label></td>		
			<td width='80px'>
			    <?= Html::input('text', 'txNombre', $nombre, ['class' => 'form-control','id'=>'txNombre','maxlength'=> '35','style'=>'width:200px;', 'disabled' => true]); ?>
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
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxTribInscripCat()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";													 	
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxTribInscripCat()']); 
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
					
					$('#trib_id').prop('disabled',false);
					$('#cat').prop('readOnly',false);
					$('#txNombre').prop('disabled',false);
											
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

					$('#trib_id').prop('disabled',true);
					$('#cat').prop('readOnly',true);
					$('#txNombre').prop('disabled',false);
										
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
			'id' => 'MensajeInfoOTC',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoOTC').alert('close');}, 5000)</script>";

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
	
	function CargarControles(trib_id,cat,nombre,modif)
	{
			event.stopPropagation();
			$("#trib_id").val(trib_id);
			$("#cat").val(cat);
			$("#txNombre").val(nombre);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	
	}

	function btnNuevoAuxTribInscripCat(){
		
			$('#txAccion').val(0);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
				
			$('#trib_id').prop('disabled',false);
			$('#cat').prop('readOnly',false);
			$('#txNombre').prop('disabled',false);
					
			$("#trib_id option:first-of-type").attr("selected", "selected");		
			$("#cat").val('');
			$("#txNombre").val('');
				
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
	
	function btnModificarAuxTribInscripCat(trib_id,cat,nombre,modif){
		
			event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
			
			$('#trib_id').prop('disabled',true);
			$('#cat').prop('readOnly',true);
			$('#txNombre').prop('disabled',false);
					
			$("#trib_id").val(trib_id);
			$("#cat").val(cat);
			$("#txNombre").val(nombre);
						
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
	
	function btnEliminarAuxTribInscripCat(trib_id,cat,nombre,modif){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$("#trib_id").val(trib_id);
			$("#cat").val(cat);
			$("#txNombre").val(nombre);
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

	function btnCancelarAuxTribInscripCat(){
		
			$('.error-summary').css('display','none');
			$('#GrillaTablaAux').css("pointer-events", "all");
			$('#GrillaTablaAux').css("color", "#111111");
			$('#GrillaTablaAux a').css("color", "#337ab7");
			$('#GrillaTablaAux Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );
			
			$('#trib_id').prop('disabled',true);
			$('#cat').prop('readOnly',true);
			$('#txNombre').prop('disabled',true);
			
			$("#trib_id option:first-of-type").attr("selected", "selected");
			$("#cat").val('');
			$("#txNombre").val('');
					
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','none');
			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
	}


	function btGrabarClick(){
		
			err = "";
			
			if ($("#cat").val()=="")
			{
				err += "<li>Ingrese una categoria</li>";
			}
			if ($("#txNombre").val()=="")
			{
				err += "<li>Ingrese un nombre</li>";
			}

			if (err == "")
			{
				$('#trib_id').prop('disabled',false);
				$("#frmTribInscripCat").submit();
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
