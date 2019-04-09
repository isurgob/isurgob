<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;


use app\models\ctacte\PlanConfigDecae;
use app\utils\db\utb;


$title = 'Decaimiento de Convenios';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => BaseUrl::toRoute(['//site/config'])];
$this->params['breadcrumbs'][] = $title;

$model = isset($model) ? $model : new PlanConfigDecae();


/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

    	
$consulta = isset($extras['consulta']) ? $extras['consulta'] : 1;

?>
<div class="site-auxedit">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1>Configuración de <?= $title; ?></h1></td>
    	<td align='right'>
    		<?php 
    			if (utb::getExisteProceso(3351))
	    			echo Html::button(
						'Nuevo', 
						[
						'class' => 'btn btn-success', 
						'id' => 'btNuevo', 
						'onclick' => 'limpiarCampos();activarControles(0);'
						]); 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    <?php
    	
    	Pjax::begin(['id' => 'pjaxConfigDecaeForm', 'enablePushState' => false, 'enableReplaceState' => false, 'formSelector' => '#configDecaerForm']);
    
		echo GridView::widget([
			'id' => 'GrillaPlanConfigDecae',
			'dataProvider' => $extras['dataProvider'],
			'headerRowOptions' => ['class' => 'grillaGrande'],
			'rowOptions' => function ($model,$key,$index,$grid){ 
				return [
					'onclick' => 'cargarControles(' . 
									$model['tplan'] . ', ' . 
									$model['origen'] . ', ' . 
									$model['tpago'] . ', ' . 
									$model['caja_id'] . ', ' .
									$model['cant_atras'] . ', ' .
									$model['cant_cons'] . ', ' .
									"'" . $model['modif'] . "'" .
									');',
					'class' => 'grillaGrande'
				];
			},
			'columns' => [
				
				['attribute' => 'tplan_nom', 'header' => 'Tipo de Convenio', 'contentOptions' => ['style' => 'width:162px;']],
				['attribute' => 'origen_nom', 'header' => 'Origen', 'contentOptions' => ['style' => 'width:110px;']],
				['attribute' => 'tpago_nom', 'header' => 'Forma de Pago', 'contentOptions' => ['style' => 'width:130px;']],
				['attribute' => 'caja_nom', 'header' => 'Caja', 'contentOptions' => ['style' => 'width:75px;']],
				['attribute' => 'cant_atras', 'header' => 'Atrasadas', 'contentOptions' => ['style' => 'width:60px;']],
				['attribute' => 'cant_cons', 'header' => 'Consecutivas', 'contentOptions' => ['style' => 'width:60px;']],
            		 
            	[
				'class' => 'yii\grid\ActionColumn',
				'contentOptions'=>['style'=>'width:40px; padding:1px 10px'],
				'template' => (utb::getExisteProceso(3351) ? '{update} {delete}' : ''),
            	'buttons'=>[
					'update' => function($url,$model,$key){
            		
            			return Html::Button(
							'<span class="glyphicon glyphicon-pencil"></span>',
							
							[
        					'class' => 'bt-buscar-label', 
							'style' => 'color:#337ab7;font-size:9px',
							'data-pjax' => 0,			
							'onclick' => 'cargarControles(' .
											$model['tplan'] . ', ' .
											$model['origen'] . ', ' .
											$model['tpago'] . ', ' .
											$model['caja_id'] . ', ' .
											$model['cant_atras'] . ', ' .
											$model['cant_cons'] . ', ' .
											"'" . $model['modif'] . "'" .
											');' .
											
											'activarControles(3);'
							]
            			);
            		},
           
            						
            		'delete' => function($url,$model,$key){
            			
            			return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            								
							[
							'class' => 'bt-buscar-label', 
							'style' => 'color:#337ab7;font-size:9px',
							'data-pjax' => 0,
							'onclick' => 'activarControles(2);' .
							
							'cargarControles(' .
								$model['tplan'] . ', ' .
								$model['origen'] . ', ' .
								$model['tpago'] . ', ' .
								$model['caja_id'] . ', ' .
								$model['cant_atras'] . ', ' .
								$model['cant_cons'] . ', ' .
								"'" . $model['modif'] . "'" .
							');' 											
							]
            			);
            		}
            	]
            ],
            	   
        	],
        	'pager' => [
    				'options' => ['style' => 'padding:0px;margin:-18px 0px 0px 0px; font-size:8px', 'class' => 'pagination']
    			]
    	]); 
    	
//    	Pjax::end();  	
	 	
	 	
	 	$form = ActiveForm::begin([
					'id' => 'configDecaerForm', 
					'action' => BaseUrl::toRoute('ctacte/convenio/configdecae'),  
					'fieldConfig' => ['template' => '{input}']
				]);
	 	
	 	echo Html::input('hidden', 'txAccion', $consulta, ['id'=>'txAccion']);
	 ?>
			<div class="form" id="form" style='padding:15px 5px; margin-top:5px'>
				<table border='0'>
					<tr>
						<td><label>Tipo: </label></td>
						
						<td>
							<?php
							$tplan = [0 => '<Todos>'] + utb::getAux('plan_config');
							echo $form->field($model, 'tplan', ['options' => ['id' => 'configDecaerContenedorTPlan']])
							->dropDownList($tplan, ['id' => 'configDecaerTPlan', 'prompt' => '', 'onchange' => '$("#configDecaerHidTPlan").val($(this).val());', 'style' => 'width:100%;'])
							->label(''); 
							
							echo $form->field($model, 'tplan')->input('hidden', ['id' => 'configDecaerHidTPlan']);
							?>
						</td>
						<td width='10px'></td>
						
						<td><label>Origen: </label></td>
						<td>
							<?php
							$origen = [0 => '<Todos>'] + utb::getAux('plan_torigen','cod','nombre',0,(utb::getExisteProceso(3342) ? 'Cod <=3' : 'Cod <=2'));
							echo $form->field($model, 'origen', ['options' => ['id' => 'configDecaerContenedorOrigen']])
							->dropDownList($origen, ['id' => 'configDecaerOrigen', 'prompt' => '', 'onchange' => '$("#configDecaerHidOrigen").val($(this).val());', 'style' => 'width:100%;'])
							->label('');
							
							echo $form->field($model, 'origen')->input('hidden', ['id' => 'configDecaerHidOrigen']);
							 ?> 
						</td>
					</tr>
				
					<tr>
						<td><label>Forma de pago: </label></td>
						<td width="210px">
							<?php
							$tpago = [0 => '<Todos>'] + utb::getAux('plan_tpago');
							echo $form->field($model, 'tpago', ['options' => ['id' => 'configDecaerContenedorTPago']])
							->dropDownList(
									$tpago, 
									[
									'id' => 'configDecaerTPago',
									'prompt' => '',
									'style' => 'width:100%',
									'onchange' => 'if($(this).val() == 3) {' .
										'$("#configDecaerCaja").removeAttr("readonly");' .
										'$("#configDecaerCaja").removeAttr("disabled");' .	
									'} else' .
									'{' . 
									'$("#configDecaerCaja").attr("readonly", "readonly");' .
									'$("#configDecaerCaja").attr("disabled", "disabled");' .
									'$("#configDecaerCaja").val("" )' .
									'}' .
									'$("#configDecaerHidTPago").val($(this).val());'
									]
							)
							->label('');
																					
							 
							echo $form->field($model, 'tpago')->input('hidden', ['id' => 'configDecaerHidTPago']);														
							?>
						</td>
						<td width='10px'></td>
						
						<td><label>Caja: </label></td>
						<td>
							<?php
							$cajas = [0 => '<Todos>'] + utb::getAux('caja', 'caja_id', 'nombre', 0, 'tipo = 3');
							
							
							echo $form->field($model, 'caja_id', ['options' => ['id' => 'configDecaerContenedorCaja']])
							->dropDownList($cajas, ['prompt' => '', 'id' => 'configDecaerCaja', 'onchange' => '$("#configDecaerHidCaja").val($(this).val());', 'style' => 'width:100%;'])
							->label('');
							
							echo $form->field($model, 'caja_id')->input('hidden', ['id' => 'configDecaerHidCaja']);
							?> 
						</td>
					</tr>
				</table>
				
				<table>
					<tr>
						<td><label>Cant. cuotas atrasadas: </label></td>
						<td>
							<?= $form->field($model, 'cant_atras', ['options' => ['id' => 'configDecaerContenedorCantAtras']])
							->textInput(['id' => 'configDecaerCantAtras', 'maxlength' => 2, 'style' => 'width:40px;'])->label('') ?>
						</td>
					</tr>
				
					<tr>
						<td><label>Cant. cuotas consecutivas: </label></td>
						<td>
							<?= $form->field($model, 'cant_cons', ['options' => ['id' => 'configDecaerContenedorCantCons']])
							->textInput(['id' => 'configDecaerCantCons', 'maxlength' => 2, 'style' => 'width:40px;'])->label('') ?>
						</td>
					</tr>
				
				</table>	
				
				<table align="right">
					<tr>
						<td colspan='5' align='right'>
							
							<label>Modificación: </label>
							<?php
							
							$modif = null;
							
							if($model->fchmod != null && $model->usrmod != null)
								$modif = utb::getFormatoModif($model->usrmod, $model->fchmod);
							
							echo Html::input('text', null, $modif, ['class' => 'form-control','id'=>'txModif','style'=>'width:150px;background:#E6E6FA']); ?>
						</td>
					</tr>
				</table>
		    </div>
		    
		     <div class="form-group" style="margin-top:5px;">
		            
			   	<?php 
									 
				echo Html::submitButton('Grabar', ['class' => 'btn btn-success', 'id' => 'configDecaerSubmit', 'onclick' => 'comprobarTodos()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'configDecaerCancelar', 'onclick' => 'activarControles(1); limpiarCampos();']); 
							
			    ?>
	        </div>
    <?php     	
    	ActiveForm::end();
    	
    	echo $form->errorSummary($model, ['id' => 'configDecaerError']);    	 
    
	    if(isset($extras['mensaje']))
		{  	
			echo Alert::widget([
	 			'options' => ['class' => 'alert-success alert-dissmisible', 'id' => 'alertConfigDecaerForm'],
				'body' => $extras['mensaje']
			]);
		
			echo "<script>window.setTimeout(function() { $('#alertConfigDecaerForm').alert('close'); }, 5000)</script>";
		}
    	
    	if(isset($consulta))
    		echo '<script>activarControles(' . $consulta . ')</script>';
    	
       	Pjax::end(); //#pjaxConfigDecaerForm
       	
       	
              	
       	
       	echo Alert::widget([
	 			'options' => ['class' => 'alert-info alert-dissmisible hidden', 'id' => 'alertInfoTodos'],
				'body' => 'Al crear esta configuración, no se tendrán en cuenta las demás configuraciones.'
		]);
    ?>
</div>

<script type="text/javascript">
function limpiarCampos(){
	$("#configDecaerTPlan option:first-of-type").attr("selected", "selected");
	$("#configDecaerOrigen option:first-of-type").attr("selected", "selected");
	$("#configDecaerTPago option:first-of-type").attr("selected", "selected");
	$("#configDecaerCaja option:first-of-type").attr("selected", "selected");
	$("#configDecaerCantAtras").val('');
	$("#configDecaerCantCons").val('');
	
	//campos ocultos
	$("#configDecaerHidTPlan").val('');
	$("#configDecaerHidOrigen").val('');
	$("#configDecaerHidTPago").val('');
	$("#configDecaerHidCaja").val('');
	
	
	
	$("#configDecaerError").addClass("hidden");
	
	
	$("#configDecaerContenedorTPlan").removeClass("has-error");
	$("#configDecaerContenedorOrigen").removeClass("has-error");
	$("#configDecaerContenedorTPago").removeClass("has-error");
	$("#configDecaerContenedorCaja").removeClass("has-error");
	$("#configDecaerContenedorCantAtras").removeClass("has-error");
	$("#configDecaerContenedorCantCons").removeClass("has-error");
	
	$("#configDecaerHidTPlan").removeClass("has-error");
	$("#configDecaerHidOrigen").removeClass("has-error");
	$("#configDecaerHidTPago").removeClass("has-error");
	$("#configDecaerHidCaja").removeClass("has-error");
	
	$("#configDecaerSubmit").removeClass("hidden");
	$("#configDecaerCancelar").removeClass("hidden");
	
	$("#txModif").val('');
}

function activarControles(accion){	
	
	$("#txAccion").val(accion);
	//limpiarCampos();
	
	switch(accion){
		case 0 :
			
			$("#configDecaerTPlan").removeAttr("disabled");
			$("#configDecaerOrigen").removeAttr("disabled");
			$("#configDecaerTPago").removeAttr("disabled");
			
			if($("#configDecaerTPago").val() != '3')
				$("#configDecaerCaja").attr("disabled", "disabled");
			else $("#configDecaerCaja").removeAttr("disabled");
			
			$("#configDecaerCantAtras").removeAttr("disabled");
			$("#configDecaerCantCons").removeAttr("disabled");
			
			break;
		
		//modificar
		case 3 :		
		
			$("#configDecaerTPlan").attr("disabled", "disabled");
			$("#configDecaerOrigen").attr("disabled", "disabled");
			$("#configDecaerTPago").attr("disabled", "disabled");
			$("#configDecaerCaja").attr("disabled", "disabled");
			$("#configDecaerCantAtras").removeAttr("disabled");
			$("#configDecaerCantCons").removeAttr("disabled");
			
			break;
		
		//consulta
		case 1 :
		
			$("#configDecaerTPlan").attr("disabled", "disabled");
			$("#configDecaerOrigen").attr("disabled", "disabled");
			$("#configDecaerTPago").attr("disabled", "disabled");
			$("#configDecaerCaja").attr("disabled", "disabled");
			$("#configDecaerCantAtras").attr("disabled", "disabled");
			$("#configDecaerCantCons").attr("disabled", "disabled");
			
			break;
		
		//delete
		case 2 :
			
			$("#configDecaerTPlan").attr("disabled", "disabled");
			$("#configDecaerOrigen").attr("disabled", "disabled");
			$("#configDecaerTPago").attr("disabled", "disabled");
			$("#configDecaerCaja").attr("disabled", "disabled");
			$("#configDecaerCantAtras").attr("disabled", "disabled");
			$("#configDecaerCantCons").attr("disabled", "disabled");
				
			break;
	}
	
		
	if (accion !== 1)
	{
		$("#GrillaPlanConfigDecae").css("pointer-events", "none");	
		$("#GrillaPlanConfigDecae Button").css("color", "#ccc");
		
		$("#configDecaerSubmit").show();
		$("#configDecaerCancelar").show();
		
		if(accion == 2){
			$("#configDecaerSubmit").removeClass("btn-success");
			$("#configDecaerSubmit").addClass("btn-danger");
		}
		else {
			$("#configDecaerSubmit").removeClass("btn-danger");
			$("#configDecaerSubmit").addClass("btn-success");
		}
			
		
		$("#btNuevo").attr("disabled", "disabled");
	}else {
		$("#GrillaPlanConfigDecae").css("pointer-events", "all");
		$("#GrillaPlanConfigDecae Button").css("color", "#337ab7");
		
		$("#configDecaerSubmit").hide();
		$("#configDecaerCancelar").hide();
		
		$("#btNuevo").removeAttr("disabled");
	}
	
	$('html, body').animate({scrollTop: $("#form").height()}, 1000); //mueve escroll a la parte de los datos
}

function cargarControles(tplan, origen, tpago, caja, cantAtras, cantCons, modificacion){		
	
	$("#configDecaerTPlan").val(tplan);
	$("#configDecaerHidTPlan").val(tplan);
	
	$("#configDecaerOrigen").val(origen);
	$("#configDecaerHidOrigen").val(origen);
	
	$("#configDecaerTPago").val(tpago);
	$("#configDecaerHidTPago").val(tpago);
	
	$("#configDecaerCaja").val(caja);
	$("#configDecaerHidCaja").val(caja);
	
	$("#configDecaerCantAtras").val(cantAtras);
	$("#configDecaerCantCons").val(cantCons);
	$("#txModif").val(modificacion);
}

function comprobarTodos(){
	
	var tplan = parseInt($("#configDecaerTPlan").val());
	var origen = parseInt($("#configDecaerOrigen").val());
	var tpago = parseInt($("#configDecaerTPago").val());
	var caja = parseInt($("#configDecaerCaja").val());
	
	var accion = parseInt($("#txAccion").val());
	 
	if(
		!isNaN(tplan) &&
		!isNaN(origen) &&
		!isNaN(tpago) &&
		(isNaN(caja) || caja == 0) &&
		 tplan == 0 &&
		 origen == 0 &&
		 tpago == 0 &&
		 (isNaN(accion) || accion != 2)
	){
		$("#alertInfoTodos").removeClass("hidden");
		
		setTimeout(function() { $('#alertInfoTodos').addClass("hidden"); }, 5000);
	}
	
}

$(document).ready(function(){activarControles(<?= $consulta?>);});
</script>