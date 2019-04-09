<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\models\config\CemFallTserv;
use \yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use app\utils\db\Fecha;
use yii\bootstrap\Alert;
use app\utils\db\utb;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Servicio de Fallecido';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

$model = isset($model) ? $model : new CemFallTserv();
if ($accion==null) $accion = 1;
?>
<div class="cem-fall-tserv-index">

    <h1 style="display:inline-block;"><?= Html::encode($title) ?></h1>

    <p style='float:right'>													  
        <?php
        if (utb::getExisteProceso(3240))
        	echo Html::Button('Nuevo', ['id' => 'btnNuevo','class' => 'btn btn-success pull-right','onclick' => 'btnNuevoCemfallTservClick()']) 
    	?>
    </p>    
    
    <?= GridView::widget([
    	'id' => 'GrillaCemFallTserv',
        'dataProvider' => $dataProvider,
        'summary' => false,
        'headerRowOptions' => ['class' => 'grilla'],
                'rowOptions' => function ($model,$key,$index,$grid){
                			return [
							   'onclick' => 'cargarControles("'.$model['cod'].'","'.
																$model['nombre'].'","'.
																$model['est_fin'].'",'.
																$model['pedir_obj_dest'].','.
																$model['pedir_dest'].',"'.
																$model['fchmod'].'","'.
																$model['usrmod_nom'].'");'  
				                   ];
        					
        					},	
        'columns' => [

			['attribute' => 'cod', 'header' => 'Cod', 'contentOptions' => ['style' => 'width:40px','class'=>'grilla']],
        	['attribute' => 'nombre', 'header' => 'Nombre', 'contentOptions' => ['style' => 'width:165px','class'=>'grilla']],
        	['attribute' => 'est_fin_nom', 'header' => 'Estado', 'contentOptions' => ['style' => 'width:165px','class'=>'grilla']],
           	['attribute' => 'pedir_obj_dest', 'header' => 'Obj.Destino', 'contentOptions' => ['style' => 'width:50px','class'=>'grilla']],
        	['attribute' => 'pedir_dest', 'header' => 'Destino', 'contentOptions' => ['style' => 'width:40px','class'=>'grilla']],

            ['class' => 'yii\grid\ActionColumn', 'contentOptions'=>['style'=>'width:50px;','align'=>'center','class'=>'grilla'], 'template' => (utb::getExisteProceso(3240) ? '{update}{delete}' : ''),
            
            
            	'buttons' => [   
            	
	    			'update' => function($url,$model,$key)
							{
								return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
								[
									'class' => 'bt-buscar-label',
									'style' => 'color:#337ab7;',
									'onclick' => 'btnModificarCemfallTservClick("'.$model['cod'].'","'.
																				   $model['nombre'].'","'.
																				   $model['est_fin'].'",'.
																				   $model['pedir_obj_dest'].','.
																				   $model['pedir_dest'].',"'.
																				   $model['fchmod'].'","'.
																				   $model['usrmod_nom'].'");'
									]			  
								);									
							},
	    							
   				'delete' => function($url,$model,$key)
	    				  	 	    {   
return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            				  	 			[
            				  	 					'class' => 'bt-buscar-label',
													'style' => 'color:#337ab7;',
							  						 'onclick' => 'btnEliminarCemFallTservClick("'.$model['cod'].'","'.
																								   $model['nombre'].'","'.
																								   $model['est_fin'].'",'.
																								   $model['pedir_obj_dest'].','.
																								   $model['pedir_dest'].',"'.
																								   $model['fchmod'].'","'.
																								   $model['usrmod_nom'].'");' 
											
														]);	            				            														
	    						    }	
	            ],
            
            ],
        ],
    ]); ?>
    
 </div>   

<?php $form = ActiveForm::begin(['action' => ['cemfalltservabm'], 'fieldConfig' => ['template' => "{label}{input}"]]);

	echo Html::input('hidden', 'accion',"", ['id'=>'accion']);
	echo Html::input('hidden', 'idDelete',"", ['id'=>'idDelete']);
?>

<div  class="form" style="padding:10px; margin-top:5px;">

	<table border='0'>
		<tr>
			<td>
				<div style='float:right;'><?= $form->field($model, 'cod',['options' => ['id' => 'cod']])->textInput(['maxlength' => 3,'style' => 'width:100px;','readOnly'=>'true'])->label('Código:') ?></div>
			</td>
			<td width='20px'></td>
			<td>   
				<?= $form->field($model, 'nombre',['options' => ['id' => 'nombre']])->textInput(['maxlength' => 35,'style' => 'width:350px;','disabled'=>'true'])->label('Nombre:') ?>
			</td>
		</tr>
		<tr>
			<td>  
				<?php
				$tipo =[0 => '<Todos>'] + utb::getAux('cem_fall_test','cod','nombre',0);																															 
				echo $form->field($model, 'est_fin',['options' => ['id' => 'est_fin']])->dropDownList($tipo, ['id' => 'cemfalltserv-est_fin','style' => 'width:100px;','disabled'=>'true'])->label('Estado final:'); 
				?>
			</td>
			<td width='20px'></td>
			<td>  
			    <?= $form->field($model, 'pedir_obj_dest', ['options' => ['style' => 'display:inline-block;']])->checkbox(['check'=> 1, 'uncheck' => 0,'disabled'=>'true']) ?>
			    &nbsp;&nbsp;
			    <?= $form->field($model, 'pedir_dest', ['options' => ['style' => 'display:inline-block;']])->checkbox(['check'=> 1, 'uncheck' => 0,'disabled'=>'true']) ?>
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td width='20px'></td>
			<td>
			<label id='label_fch_mod' style='float:left;margin-top:5px; margin-left:160px;'>Modif.</label><div id='fch_mod' style='float:right;'><?= $form->field($model, 'fchmod')->textInput(['style' => 'width:200px;','disabled'=>'true']) ?></div>
			</td>
		</tr>	
	</table> 
</div>

<div id='form_botones' style='display:none; margin-top:5px;' class="form-group"> 
		
    <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
         
	<?= Html::Button('Cancelar', ['id'=>'btnCancelar', 'class' => 'btn btn-primary', 'onclick' => 'btnCancelarCemfallTservClick()']) ?>
</div>

<div id='form_botones_delete' style='display:none; margin-top:5px;' class="form-group"> 
		
	<?= Html::submitButton('Eliminar', ['class' => 'btn btn-danger']) ?>
        
	<?= Html::Button('Cancelar', ['id'=>'btnCancelar', 'class' => 'btn btn-primary', 'onclick' => 'btnCancelarCemfallTservClick()']) ?>
</div>

<?php 
echo $form->errorSummary($model);
ActiveForm::end(); 

?>
		<div style="margin-top:5px;">
	    <?php
		//-------------------------seccion de mensajes-----------------------
		if(!empty($_GET['mensaje']) and $_GET['mensaje']!==''){
	
			switch ($_GET['mensaje'])
			{
					case 'create' : $mensaje = 'Datos grabados correctamente.'; break;
					case 'update' : $mensaje = 'Datos grabados correctamente.'; break;
					case 'delete' : $mensaje = 'Datos borrados correctamente.'; break;
					default : $mensaje = '';
			}
		}
	
		Alert::begin([
			'id' => 'MensajeInfoOB',
			'options' => [
			'class' => 'alert-success',
			'style' => $mensaje != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($mensaje != '') echo $mensaje;
		
		Alert::end();
		
		if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoOB').alert('close'); }, 5000)</script>"; 	
			
		//--------------------------seccion de errores------------------------

		
		
		if(isset($error) and $error !== '') {  
		echo '<div id="error-summary" class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';

				} 
		?>
		</div>
		
		<?php
			//echo "ACCION: ".$accion;
			if($accion==0){
		 ?>		
				<script>
				
					$('#form_botones').css('display','block');	
					$('#cemfalltserv-cod').prop('readOnly',false);
					$('#cemfalltserv-nombre').prop('disabled',false);
					$('#cemfalltserv-est_fin').removeAttr("disabled");
					$('#cemfalltserv-pedir_obj_dest').prop('disabled',false);
					$('#cemfalltserv-pedir_dest').prop('disabled',false);
					$('#fch_mod').css('display','none');
					$('#label_fch_mod').css('display','none');
					//$('#usr_mod_nom').css('display','none');
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
					$('#cemfalltserv-cod').prop('readOnly',true);	
					$('#cemfalltserv-nombre').prop('disabled',false);
					$('#cemfalltserv-est_fin').removeAttr("disabled");		
					$('#cemfalltserv-pedir_obj_dest').prop('disabled',false);
					$('#cemfalltserv-pedir_dest').prop('disabled',false);
					$('#fch_mod').css('display','none');
					$('#label_fch_mod').css('display','none');
					$('#accion').val(3);
										
					$('#btnNuevo').css("pointer-events", "none");
					$('#btnNuevo').css("opacity", 0.5);
					$('#GrillaCemFallTserv').css("pointer-events", "none");
					$('#GrillaCemFallTserv').css("color", "#ccc");	
					$('#GrillaCemFallTserv Button').css("color", "#ccc");
					$('#GrillaCemFallTserv a').css("color", "#ccc");
					
				</script>
					
		<?php }	?>

<script>			

	function cargarControles(cod, nombre, est_fin, pedir_obj_dest, pedir_dest, fchmod, usrmod_nom){		
		
		event.stopPropagation();
		$('.error-summary').css('display','none');
		$('#form_botones').css('display','none');	
		$('#form_botones_delete').css('display','none');		
		$('#cemfalltserv-cod').val(cod);
		$('#cemfalltserv-cod').prop('readOnly','true');
		$('#cemfalltserv-nombre').val(nombre);
		$('#cemfalltserv-nombre').prop('disabled','disabled');
		$('#cemfalltserv-est_fin').val(est_fin);
		$('#cemfalltserv-est_fin').prop('disabled','disabled');
		if(pedir_obj_dest==1){$('#cemfalltserv-pedir_obj_dest').prop("checked","checked");}else{$('#cemfalltserv-pedir_obj_dest').prop("checked","");}
		$('#cemfalltserv-pedir_obj_dest').prop('disabled','disabled');
		if(pedir_dest==1){$('#cemfalltserv-pedir_dest').prop("checked","checked");}else{$('#cemfalltserv-pedir_dest').prop("checked","");}
		$('#cemfalltserv-pedir_dest').prop('disabled','disabled');
		fchmod = fchmod.slice(0,10);
		modif = usrmod_nom + ' - ' + fchmod;
		$('#cemfalltserv-fchmod').val(modif);
		$('#cemfalltserv-usrmod_nom').val(usrmod_nom);
		$('#fch_mod').css('display','block');
		$('#label_fch_mod').css('display','block');
		$('#cemfalltserv-fchmod').prop('disabled','disabled');
		$('#cemfalltserv-usrmod_nom').prop('disabled','disabled');	
	}
	
	function btnNuevoCemfallTservClick(){
		
		$('.error-summary').css('display','none');
		$('#form_botones').css('display','block');	
		$('#form_botones_delete').css('display','none');	
		$('#cemfalltserv-cod').val('');
		$('#cemfalltserv-cod').prop('readOnly',false);
		$('#cemfalltserv-nombre').val('');
		$('#cemfalltserv-nombre').prop('disabled',false);
		$("#cemfalltserv-est_fin option:first-of-type").attr("selected", "selected");
		$('#cemfalltserv-est_fin').attr("disabled","disabled");
		$('#cemfalltserv-est_fin').removeAttr("disabled");
		$('#cemfalltserv-pedir_obj_dest').prop("checked","");	
		$('#cemfalltserv-pedir_obj_dest').prop('disabled',false);
		$('#cemfalltserv-pedir_dest').prop("checked","");
		$('#cemfalltserv-pedir_dest').prop('disabled',false);
		$('#fch_mod').css('display','none');
		$('#label_fch_mod').css('display','none');
		$('#accion').val(0);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaCemFallTserv').css("pointer-events", "none");
		$('#GrillaCemFallTserv').css("color", "#ccc");	
		$('#GrillaCemFallTserv Button').css("color", "#ccc");
		$('#GrillaCemFallTserv a').css("color", "#ccc");
	}
	
	function btnModificarCemfallTservClick(cod, nombre, est_fin, pedir_obj_dest, pedir_dest, fchmod, usrmod_nom){
		
		event.stopPropagation();
		$('.error-summary').css('display','none');
		$('#form_botones').css('display','block');
		$('#form_botones_delete').css('display','none');	
		$('#cemfalltserv-cod').prop('readOnly',true);	
		$('#cemfalltserv-nombre').prop('disabled',false);
		$('#cemfalltserv-est_fin').removeAttr("disabled");		
		$('#cemfalltserv-pedir_obj_dest').prop('disabled',false);
		$('#cemfalltserv-pedir_dest').prop('disabled',false);
		$('#fch_mod').css('display','none');
		$('#label_fch_mod').css('display','none');
		$('#accion').val(3);
		
		$('#cemfalltserv-cod').val(cod);
		$('#cemfalltserv-nombre').val(nombre);
		$('#cemfalltserv-est_fin').val(est_fin);
		if(pedir_obj_dest==1){$('#cemfalltserv-pedir_obj_dest').prop("checked","checked");}else{$('#cemfalltserv-pedir_obj_dest').prop("checked","");}
		if(pedir_dest==1){$('#cemfalltserv-pedir_dest').prop("checked","checked");}else{$('#cemfalltserv-pedir_dest').prop("checked","");}
		$('#cemfalltserv-fchmod').val(fchmod);
		$('#cemfalltserv-usrmod_nom').val(usrmod_nom);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaCemFallTserv').css("pointer-events", "none");
		$('#GrillaCemFallTserv').css("color", "#ccc");	
		$('#GrillaCemFallTserv Button').css("color", "#ccc");
		$('#GrillaCemFallTserv a').css("color", "#ccc");
	}
	
	
	function btnEliminarCemFallTservClick(cod, nombre, est_fin, pedir_obj_dest, pedir_dest, fchmod, usrmod_nom){
			
		event.stopPropagation();
		$('#idDelete').val(cod);
		$('.error-summary').css('display','none');
		$('#form_botones_delete').css('display','block');	
		$('#cemfalltserv-cod').val(cod);
		$('#cemfalltserv-cod').prop('readOnly','true');
		$('#cemfalltserv-nombre').val(nombre);
		$('#cemfalltserv-nombre').prop('disabled','disabled');
		$('#cemfalltserv-est_fin').val(est_fin);
		$('#cemfalltserv-est_fin').prop('disabled','disabled');
		if(pedir_obj_dest==1){$('#cemfalltserv-pedir_obj_dest').prop("checked","checked");}else{$('#cemfalltserv-pedir_obj_dest').prop("checked","");}
		$('#cemfalltserv-pedir_obj_dest').prop('disabled','disabled');
		if(pedir_dest==1){$('#cemfalltserv-pedir_dest').prop("checked","checked");}else{$('#cemfalltserv-pedir_dest').prop("checked","");}
		$('#cemfalltserv-pedir_dest').prop('disabled','disabled');
		fchmod = fchmod.slice(0,10);
		modif = fchmod + ' - ' + usrmod_nom;
		$('#cemfalltserv-fchmod').val(modif);
		$('#cemfalltserv-usrmod_nom').val(usrmod_nom);
		$('#fch_mod').css('display','block');
		$('#label_fch_mod').css('display','block');
		$('#cemfalltserv-fchmod').prop('disabled','disabled');
		$('#cemfalltserv-usrmod_nom').prop('disabled','disabled');
		$('#accion').val(2);
	}

	function btnCancelarCemfallTservClick(){
		
		$('.error-summary').css('display','none');
		$('#GrillaCemFallTserv').css("pointer-events", "all");
		$('#GrillaCemFallTserv').css("color", "#111111");
		$('#GrillaCemFallTserv a').css("color", "#337ab7");
		$('#GrillaCemFallTserv Button').css("color", "#337ab7");
		$('#btnNuevo').css("pointer-events", "all");
		$('#btnNuevo').css("opacity", 1 );
		
		
		$('#form_botones').css('display','none');
		$('#form_botones_delete').css('display','none');	
		$('#cemfalltserv-cod').val('');
		$('#cemfalltserv-cod').prop('readOnly',true);
		
		$('#cemfalltserv-nombre').val('');
		$('#cemfalltserv-nombre').prop('disabled',true);
		$('#cemfalltserv-est_fin').attr('disabled','disabled');	
		$('#cemfalltserv-pedir_obj_dest').prop('disabled',true);
		$('#cemfalltserv-pedir_dest').prop('disabled',true);
		$('#fch_mod').css('display','block');
		$('#label_fch_mod').css('display','block');
		$('#cemfalltserv-fchmod').val('');
		$('#cemfalltserv-usrmod_nom').val('');
		
		$('#cod').removeClass('has-error');
		$('#cod').removeClass('has-success');
		$('#nombre').removeClass('has-error');
		$('#nombre').removeClass('has-success');
		$('#est_fin').removeClass('has-error');
		$('#est_fin').removeClass('has-success');
	}	

</script>

