<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\models\config\ObjetoTbaja;
use \yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use app\utils\db\Fecha;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Motivos de baja';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

$accion= isset($accion) ? $accion : 1;
$model = isset($model) ? $model : new ObjetoTbaja();

$criterio = "";
?>
<div class="objeto-tbaja-index">

    <h1><?= Html::encode($title) ?></h1>

    <p style='float:right;margin-top:-30px;'>												 
        <?php 
        	if (utb::getExisteProceso(3065))
        		echo Html::Button('Nuevo',['id'=>'btnNuevo','class' => 'btn btn-success','onclick'=> 'btnNuevoObjetoTbajaClick()']) 
    	?>
    </p>
    
    <hr></hr>

	<!-- -------------------- Inicio comboBox que filtra grilla por tipo de objeto ---------------  -->
	
    <?php  $form = ActiveForm::begin(['id' => 'form-objetoTbaja-filtro']);
    	    	
	echo $form->field($model, 'cod')->dropDownList(utb::getAux('objeto_tipo','cod','nombre',0,"est = 'A'"),
			['id'=>'selectObjeto_tipo', 'style'=>'width:150px','onchange'=>'$.pjax.reload({container:"#idGrid",data:{cod:this.value},method:"POST"})'])->label('Filtrar por Tipo de Objeto'); 
			
	ActiveForm::end();
	
   // --------------------------------------- Fin del comboBox --------------------------------------------
    
    
    Pjax::begin(['id' => 'idGrid']);// comienza bloque de grilla
        	   
    	 $criterio = "";
             	    	     	  
    	 if (isset($_POST['cod']) and $_POST['cod'] !== null and $_POST['cod'] !=='') 
    	 {
    	 	$criterio = "tobj=".$_POST['cod'];
    	 }
    ?>    
 
    <?=
    GridView::widget([
    	'id' => 'GrillaObjetoTbaja',
        'dataProvider' => $model->buscarObjetoTbaja($criterio),
        'headerRowOptions' => ['class' => 'grilla'],
        'rowOptions' => function ($model,$key,$index,$grid){
        						return [
							    'onclick' => 'cargarControles('.
															    $model['cod'].',"'.
															    $model['nombre'].'",'.
															    $model['tobj'].',"'.
															    $model['fchmod'].'","'.
															    $model['usrmod'].'");'  
				                   ];
        					},
        'columns' => [
        
        ['attribute' => 'cod', 'header' => 'Codigo', 'contentOptions' => ['style' => 'width:30px','class'=>'grilla']],
        ['attribute' => 'nombre', 'header' => 'Nombre', 'contentOptions' => ['style' => 'width:400px','class'=>'grilla']],
        ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['style' => 'width:35px;','class'=>'grilla'], 'template' => (utb::getExisteProceso(3065) ? '{update}{delete}' : ''),
         'buttons' => [   
            				'update' => function($url,$model,$key)
            							{
            								return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            								[
            									'class' => 'bt-buscar-label',
            									'style' => 'color:#337ab7;',
												'onclick' => 'btnModificarObjetoTbajaClick('.
																							$model['cod'].',"'.
																							$model['nombre'].'",'.
																							$model['tobj'].');'  
            								]	
										);									
            						},
            							
            				'delete' => function($url,$model,$key)
            				  	 	    {   
            				  	 	    	//$url = $url."&cod=".$model['cod']; 
            				  	 			return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            				  	 			[
            				  	 					'class' => 'bt-buscar-label',
													'style' => 'color:#337ab7;',
													'onclick' => 'btnEliminarObjetoTbajaClick('.															    
															    $model['cod'].',"'.
															    $model['nombre'].'",'.
															    $model['tobj'].');' 
						
											]);	            														
            						    }	
            			            ],
            			         ],        
                        ]]); ?>
                
     <?php 
     Pjax::end(); // fin bloque de la grilla	    
	  ?>                         
  
</div>


	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
																					 
    		<?php $form = ActiveForm::begin(['id' => 'form-objetoTbaja','action' => ['objetotbajaabm'], 'fieldConfig' => ['template' => "{label}{input}"]]);
    		
    			  echo Html::input('hidden', 'accion', "", ['id'=>'accion']);
    			  echo Html::input('hidden', 'idDelete',"", ['id'=>'idDelete']);
    		 ?>

	<table border='0'>
		<tr>
			<td width='45px'><label>Codigo</label></td>
			<td width='50px'> <?=$form->field($model, 'cod',['options' => ['id' => 'codTbaja']])->textInput(['style' => 'width:50px;', 'readOnly' => 'true']) ?> </td>
			<td width='90px' align='right'><label>Tipo de Objeto</label></td>
			<td> 
			<?php
				echo $form->field($model, 'tobj',['options' => ['id' => 'tobjTbaja']])->dropDownList(utb::getAux('objeto_tipo','cod','nombre',0,"est = 'A'"),
				['style' => 'width:100px;','disabled'=>'disabled']); 
				 ?>
			</td>
		</tr>
	</table>
	<table>
		<tr>
			<td width='730px' colspan='4'>    
				<?= $form->field($model, 'nombre',['options' => ['id' => 'nombreTbaja']])->textInput(['maxlength' => 70,'style' => 'width:680px;','disabled'=>'disabled']) ?>
			</td>

		</tr>
	</table>
	<table border='0'>
		<tr>
			<td width='727px'>
			<div id='modif' style='float:right;'><?= $form->field($model, 'modif')->textInput(['style' => 'width:150px;','disabled'=>'disabled']) ?></div>
			</td>
		</tr>				
	</table>    
	
	<div id='form_botones' style='display:none' class="form-group">
	 
	 <?php
        echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
 		echo "&nbsp;&nbsp;";
		echo Html::Button('Cancelar', ['class' => 'btn btn-primary','onclick'=>'btnCancelarObjetoTbajaClick()']);
		
     ?>
	</div>  
	
	<div id='form_botones_delete' style='display:none' class="form-group">
	<?php
        echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'id' => 'objetoTipoSubmit']); 
        echo "&nbsp;&nbsp;";
		echo Html::Button('Cancelar', ['class' => 'btn btn-primary','onclick'=>'btnCancelarObjetoTbajaClick()']);
	 ?>
	</div>
   		<?php 
   		echo $form->errorSummary($model);
   		ActiveForm::end(); ?>

	</div>
	
	
		    <?php
		//-------------------------seccion de mensajes-----------------------
		if(!empty($_GET['mensaje']) and $_GET['mensaje']!==''){
	
			switch ($_GET['mensaje'])
			{
					case 'create' : $mensaje = 'Datos Grabados.'; break;
					case 'update' : $mensaje = 'Datos Grabados.'; break;
					case 'delete' : $mensaje = 'Datos Borrados.'; break;
					default : $mensaje = '';
			}
		}
	
		Alert::begin([
			'id' => 'MensajeInfoOB',
			'options' => [
			'class' => 'alert-info',
			'style' => $mensaje != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($mensaje != '') echo $mensaje;
		
		Alert::end();
		
		if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoOB').alert('close'); }, 5000)</script>"; 	
		
		?>
			
		
		<?php
		
		//--------------------------seccion de errores------------------------
		
		if(isset($error) and $error !== '') {  
		echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
			} 
			?>
		
		<?php
			//echo "ACCION: ".$accion;
			if($accion==0 || $accion==3){
		 ?>		
				<script>
				
			        $('#form_botones').css('display','block');	
					$('#objetotbaja-cod').prop('readOnly',true);
					$('#objetotbaja-cod').val('');
					$('#objetotbaja-nombre').prop('disabled',false);
					$('#objetotbaja-tobj').removeAttr("disabled");
					$('#modif').css('display','none');
					$('#accion').val(0);
					
					$('#btnNuevo').css("pointer-events", "none");
					$('#btnNuevo').css("opacity", 0.5);
					$('#GrillaCemFallTserv').css("pointer-events", "none");
					$('#GrillaCemFallTserv').css("color", "#ccc");	
					$('#GrillaCemFallTserv Button').css("color", "#ccc");
					$('#GrillaCemFallTserv a').css("color", "#ccc");

				</script>
				
		<?php	}else if($accion==3){ ?>
			
				<script>
			
			        $('#form_botones').css('display','block');	
					$('#objetotbaja-cod').prop('readOnly',true);
					$('#objetotbaja-nombre').prop('disabled',false);
					$('#objetotbaja-tobj').removeAttr("disabled");
					$('#modif').css('display','none');
					$('#accion').val(3);
					
					$('#btnNuevo').css("pointer-events", "none");
					$('#btnNuevo').css("opacity", 0.5);
					$('#GrillaCemFallTserv').css("pointer-events", "none");
					$('#GrillaCemFallTserv').css("color", "#ccc");	
					$('#GrillaCemFallTserv Button').css("color", "#ccc");
					$('#GrillaCemFallTserv a').css("color", "#ccc");
				
				</script>
					
		<?php }else if($accion==2){?>
			
				<script>
			
			        $('#form_botones_delete').css('display','block');	
					$('#objetotbaja-cod').prop('readOnly',true);
					$('#objetotbaja-nombre').prop('disabled',true);
					$('#objetotbaja-tobj').prop('disabled','disabled');
					$('#modif').css('display','none');
					$('#accion').val(2);
					
					$('#btnNuevo').css("pointer-events", "none");
					$('#btnNuevo').css("opacity", 0.5);
					$('#GrillaCemFallTserv').css("pointer-events", "none");
					$('#GrillaCemFallTserv').css("color", "#ccc");	
					$('#GrillaCemFallTserv Button').css("color", "#ccc");
					$('#GrillaCemFallTserv a').css("color", "#ccc");
				
				</script>
			
			<?php } ?>

<script>			

	function cargarControles(cod, nombre, tobj, fchmod ,usrmod){	
			
		event.stopPropagation();
		$('#selectObjeto_tipo').prop('disabled',false);
		$('#form_botones').css('display','none');		
		$('#objetotbaja-cod').val(cod);
		$('#objetotbaja-nombre').val(nombre);
		$('#objetotbaja-nombre').prop('disabled','disabled');
		$('#objetotbaja-tobj').val(tobj);
		$('#objetotbaja-tobj').prop('disabled','disabled');
		fchmod = fchmod.slice(0,10);
		modif = usrmod + ' - ' + fchmod;
		$('#objetotbaja-modif').val(modif);
		$('#objetotbaja-modif').prop('disabled','disabled');
		$('#modif').css('display','block');
	
	}
	
	function btnNuevoObjetoTbajaClick(){
		
		$('.error-summary').css('display','none');
		$('#selectObjeto_tipo').prop('disabled','disabled');
		$('#form_botones_delete').css('display','none');
		$('#form_botones').css('display','block');	
		$('#objetotbaja-cod').val('');
		$('#objetotbaja-nombre').val('');
		$('#objetotbaja-nombre').prop('disabled',false);
		$("#objetotbaja-tobj option:first-of-type").attr("selected", "selected");
		$('#objetotbaja-tobj').removeAttr("disabled");	
		$('#modif').css('display','none');
		$('#accion').val(0);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaObjetoTbaja').css("pointer-events", "none");
		$('#GrillaObjetoTbaja').css("color", "#ccc");	
		$('#GrillaObjetoTbaja Button').css("color", "#ccc");
		$('#GrillaObjetoTbaja a').css("color", "#ccc");
	}
	         
	function btnModificarObjetoTbajaClick(cod, nombre, tobj){
		
		event.stopPropagation();
		$('.error-summary').css('display','none');
		$('#selectObjeto_tipo').prop('disabled','disabled');
		$('#form_botones_delete').css('display','none');
		$('#form_botones').css('display','block');	
		$('#objetotbaja-cod').val(cod);
		$('#objetotbaja-nombre').val(nombre);
		$('#objetotbaja-nombre').prop('disabled',false);
		$('#objetotbaja-tobj').val(tobj);
		$('#objetotbaja-tobj').removeAttr("disabled");	
		$('#modif').css('display','none');
		$('#accion').val(3);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaObjetoTbaja').css("pointer-events", "none");
		$('#GrillaObjetoTbaja').css("color", "#ccc");	
		$('#GrillaObjetoTbaja Button').css("color", "#ccc");
		$('#GrillaObjetoTbaja a').css("color", "#ccc");
	}
	
	
	function btnEliminarObjetoTbajaClick(cod, nombre, tobj){
		
		event.stopPropagation();
		$('#selectObjeto_tipo').prop('disabled','disabled');
		$('#form_botones_delete').css('display','none');
		$('#form_botones_delete').css('display','block');	
		$('#objetotbaja-cod').val(cod);
		//$('#objetotbaja-cod').prop('readOnly',true);
		$('#objetotbaja-nombre').val(nombre);
		$('#objetotbaja-nombre').prop('disabled',true);
		$('#objetotbaja-tobj').val(tobj);
	    $('#objetotbaja-tobj').prop('disabled',true);
	    $('#modif').css('display','none');
		
		$('#accion').val(2);
		$('#idDelete').val(cod);		
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaObjetoTbaja').css("pointer-events", "none");
		$('#GrillaObjetoTbaja').css("color", "#ccc");	
		$('#GrillaObjetoTbaja Button').css("color", "#ccc");
		$('#GrillaObjetoTbaja a').css("color", "#ccc");
	}
	
	
	function btnCancelarObjetoTbajaClick(){

		$('.error-summary').css('display','none');
		$('#selectObjeto_tipo').prop('disabled',false);
		$('#GrillaObjetoTbaja').css("pointer-events", "all");
		$('#GrillaObjetoTbaja').css("color", "#111111");
		$('#GrillaObjetoTbaja a').css("color", "#337ab7");
		$('#GrillaObjetoTbaja Button').css("color", "#337ab7");
		$('#btnNuevo').css("pointer-events", "all");
		$('#btnNuevo').css("opacity", 1 );
		
		$('#objetotbaja-tobj').prop('disabled',false);
		$('#modif').css('display','block');
		$('#form_botones').css('display','none');
		$('#form_botones_delete').css('display','none');
		$('#objetotbaja-cod').val('');
		
		$('#nombreTbaja').removeClass('has-error');
		$('#nombreTbaja').removeClass('has-success')
		$('#codTbaja').removeClass('has-success');
		$('#tobjTbaja').removeClass('has-success');
		$('#tobjTbaja').removeClass('has-error');
		$('#objetotbaja-nombre').val('');
		$('#objetotbaja-nombre').prop('disabled',true);
		$('#objetotbaja-tobj').attr('disabled','disabled');	
		$('#objetotbaja-modif').val('');
	}	

</script>
		
