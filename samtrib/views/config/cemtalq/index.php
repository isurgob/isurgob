<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;
use yii\jui\DatePicker;

use app\utils\db\utb;
use app\utils\db\Fecha;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Configuración de Alquileres';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = $title;


echo Html::hiddenInput(null, null, ['id' => 'consultaActual']);
?>

<script type='text/javascript'>

$(document).ready(function(){
	$('#tipo').change(function(e){
		
		if($('#tipo').val()=='DE'){
			$('#cuadrodesde').prop('disabled',true);
			$('#cuadrohasta').prop('disabled',true);
			$("#cuadrodesde option:first-of-type").attr("selected", "selected");
			$("#cuadrohasta option:first-of-type").attr("selected", "selected");
		}else if($('#tipo').val()!='DE'){
			$('#cuadrodesde').prop('disabled',false);
			$('#cuadrohasta').prop('disabled',false);				
		}
	});

  $("#desde,#hasta").datepicker();
})
</script>

<div class="cem-talq-index">

    <h1><?= Html::encode($title) ?></h1>

    <p align='right'  width='100%' style='border-bottom:1px solid #ddd;margin-top:-30px;'>
        <?= Html::button('Nuevo', ['id' => 'btnNuevo','class' => 'btn btn-success', 'onclick' => 'cargarControles(0, "","","",0,0,0,0,"",0,"","","","");','style'=>'margin-bottom:10px;']) ?>
    </p>

    <?= GridView::widget([
    	'id' => 'GrillaTablaCemTalq',
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => function ($model,$key,$index,$grid) 
				{
					return [
						'onclick' => 'cargarControles(1, '.$model['cod'].',"'.
										Fecha::bdToUsuario($model['desde']).'","'.
										Fecha::bdToUsuario($model['hasta']).'","'.
										$model['tipo'].'","'.
										$model['cuadrodesde'].'","'.
										$model['cuadrohasta'].'","'.
										$model['cuerpo_id'].'","'.
										$model['fila'].'",'.
										$model['cat'].','.
										$model['supdesde'].','.
										$model['suphasta'].','.
										$model['duracion'].',"'.
										$model['modif'].'")'
										];
									},
       		 'columns' => [        
            
            ['attribute' => 'cod', 'header' => 'Codigo', 'contentOptions' => ['style' => 'width:5%','align'=>'center','class'=>'grilla']],
            ['attribute' => 'tipo_nombre', 'header' => 'Tipo', 'contentOptions' => ['style' => 'width:30%','class'=>'grilla']],
        	['attribute' => 'cuadro_desde_nombre', 'header' => 'Cuadro Desde', 'contentOptions' => ['style' => 'width:30%','class'=>'grilla']],
        	['attribute' => 'cuadro_hasta_nombre', 'header' => 'Cuadro Hasta', 'contentOptions' => ['style' => 'width:30%','class'=>'grilla']],

            ['class' => 'yii\grid\ActionColumn', 'contentOptions'=>['style'=>'width:5%','align'=>'center','class'=>'grilla'], 'template' => '{update}&nbsp;{delete}',
            
            
            	'buttons' => [   
            	
	    			'update' => function($url,$model,$key)
							{
								return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
								[
									'class' => 'bt-buscar-label',
									'style' => 'color:#337ab7;',
									'onclick' => 'cargarControles(3, '.$model['cod'].',"'.
																		Fecha::bdToUsuario($model['desde']).'","'.
																		Fecha::bdToUsuario($model['hasta']).'","'.
																		$model['tipo'].'","'.
																		$model['cuadrodesde'].'","'.
																		$model['cuadrohasta'].'","'.
																		$model['cuerpo_id'].'","'.
																		$model['fila'].'",'.
																		$model['cat'].','.
																		$model['supdesde'].','.
																		$model['suphasta'].','.
																		$model['duracion'].',"'.
																		$model['modif'].'");'  
																		]	
																	);									
																},									    							
	    			'delete' => function($url,$model,$key)
				  	 	    {   

								return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            				  	 			[
            				  	 					'class' => 'bt-buscar-label',
													'style' => 'color:#337ab7;',
													'onclick' => 'cargarControles(2, '.$model['cod'].',"'.
																					   Fecha::bdToUsuario($model['desde']).'","'.
																					   Fecha::bdToUsuario($model['hasta']).'","'.
																					   $model['tipo'].'","'.
																					   $model['cuadrodesde'].'","'.
																					   $model['cuadrohasta'].'","'.
																					   $model['cuerpo_id'].'","'.
																					   $model['fila'].'",'.
																					   $model['cat'].','.
																					   $model['supdesde'].','.
																					   $model['suphasta'].','.
																					   $model['duracion'].',"'.
																					   $model['modif'].'");'						
																						]);	            														
																	   				 }	
													            				],
												            				],
																        ],
																    ]); ?>
</div>

<?php
Pjax::begin(['id' => 'pjaxGrabar', 'enableReplaceState' => false, 'enablePushState' => false]);
?>

<script type="text/javascript">
$(document).ready(function(){
	habilitarCampos(<?= $consulta ?>);
});
</script>

<div class="form" style="padding:10px; margin-top:10px;">

	<?php $form = ActiveForm::begin(['action' => ['cemalqabm'], 'fieldConfig' => ['template' => "{input}"],'id'=>'formulario', 'validateOnSubmit' => false]);
	
		echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
		echo Html::input('hidden', 'idDelete',"", ['id'=>'idDelete']);
	 ?>
	<table border='0'>
		<tr>
			<td width="100px"><label>Código:</label></td>
			<td width='130px' align='left'>
				<?= $form->field($model, 'cod')->textInput(['id'=>'formCodigo','style' => 'width:50px;','maxlength' => 10,'class' => 'form-control solo-lectura', 'tabindex' => -1]) ?>
			</td>
			<td width='90px'><label>Tipo:</label></td>
			<td width='130px' align='left'>
				<?php
					$tipoForm = utb::getAux('cem_tipo','cod','nombre',3);																																							
					echo $form->field($model, 'tipo')->dropDownList($tipoForm,['id' => 'formTipo','style' => 'width:120px;','disabled'=>true]);	
				 ?>
			</td>
			<td width="70px"><label>Desde:</label></td>
			<td width='120px' align='left'>
				<?= 
					$form->field($model, 'desde')->widget(DatePicker::classname(), ['dateFormat' => 'dd/MM/yyyy','options' => ['class'=>'form-control', 'id' => 'formDesde', 'style' => 'width:70px;', 'disabled' => true, 'maxlength' => 10]]);
				?>
			</td>
			<td><label>Hasta:</label></td>
			<td width='80px' align='left'>
				<?=
				$form->field($model, 'hasta')->widget(DatePicker::className(), ['dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'id' => 'formHasta', 'style' => 'width:70px;', 'disabled' => true, 'maxlength' => 10]]); 
				?> 
			</td>
		</tr>
		<tr>
			<td><label>Cuadro Desde:</label></td>
			<td>    
				<?php
					$tipoForm = utb::getAux('cem_cuadro','cuadro_id','nombre',1);																																							
					echo $form->field($model, 'cuadrodesde')->dropDownList($tipoForm,['id' => 'formCuadroDesde','style' => 'width:120px;','disabled'=>true]);	
				 ?>
			</td>
			<td><label>Cuadro Hasta:</label></td>
			<td>    
				<?php
					$tipoForm = utb::getAux('cem_cuadro','cuadro_id','nombre',1);																																							
					echo $form->field($model, 'cuadrohasta')->dropDownList($tipoForm,['id' => 'formCuadroHasta','style' => 'width:120px;','disabled'=>true]);	
				 ?>
			</td>
			<td><label>Cuerpo:</label></td>
			<td>
				<?php
					$tipoForm = utb::getAux('cem_cuerpo','cuerpo_id','nombre',1);																																							
					echo $form->field($model, 'cuerpo_id')->dropDownList($tipoForm,['id' => 'formCuerpo','style' => 'width:100px;','disabled'=>true]);	
				 ?>
			</td>
			<td><label>Fila:</label></td>
			<td>   
				<?= $form->field($model, 'fila')->textInput(['id'=>'formFila','maxlength' => 3,'style' => 'width:40px;','disabled'=>true]) ?>
			</td>
		</tr>
		<tr>
			<td><label>Categoria:</label></td>
			<td>
				<?php
					$tipoForm = utb::getAux('cem_tcat','cat','nombre',1);																																							
					echo $form->field($model, 'cat')->dropDownList($tipoForm,['id' => 'formCategoria','style' => 'width:120px;','disabled'=>true]);	
				 ?>
			</td>
			<td><label>Sup. Desde:</label></td>
			<td> 
				<?= $form->field($model, 'supdesde')->textInput(['id'=>'formSuperficieDesde','style' => 'width:50px;','maxlength' => 5,'disabled'=>true]) ?>
			</td>
			<td><label>Sup. Hasta:</label></td>
			<td>
				<?= $form->field($model, 'suphasta')->textInput(['id'=>'formSuperficieHasta','style' => 'width:50px;','maxlength' => 5,'disabled'=>true]) ?>
			</td>
			<td><label>Duración:</label></td>
			<td>
				<?= $form->field($model, 'duracion')->textInput(['id'=>'formDuracion','style' => 'width:40px;','maxlength' => 3,'disabled'=>true]) ?>
			</td>
		</tr>
	</table>
	<table border='0'>
		<tr>
			<td id='labelModif' width='605px' align='right'><label>Modificación:</label></td>
			<td>
				<?= $form->field($model, 'modif')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'formModificacion']); ?>
			</td>
		</tr>
	</table> 
</div>		
		
		
		
		
<div class="form-group hidden" style='margin-top:5px;' id= "formBotones">		
	<?php	
		echo Html::button('Grabar', ['class' => 'btn btn-success', 'id' => 'botonGrabar','onclick'=>'grabar()']);
    	echo "&nbsp;&nbsp;";
		echo Html::button('Cancelar', ['class' => 'btn btn-primary','onclick'=>'limpiarFormulario();']);		
	?>	    
</div>
	
<div style="margin-top:5px;">
	<?php

		//-------------------------seccion de mensajes-----------------------
		if(isset($mensaje) && $mensaje != ''){
			
		Alert::begin([
			
			'options' => [
			'class' => 'alert-success',
			'id' => 'alertMensaje' 
			],
		]);	
		echo $mensaje;
		Alert::end();
		
		echo '<script>setTimeout(function() { $("#alertMensaje").fadeOut(); }, 5000);</script>'; 	
		}
      ?>
</div>

<?php
ActiveForm::end();

echo $form->errorSummary($model, ['id' => 'formErrores']); 
?>

<?php
Pjax::end();
?>

<script>	
function grabar(){
	
	var consulta, urlTo, datos, cargarDatosAdicionales;
	
	var consulta= parseInt($("#consultaActual").val());
	
	if(isNaN(consulta)) return;
	
	datos= {};
	datos.CemTalq= {};
	datos.CemTalq.cod= $("#formCodigo").val();
	datos.consulta= consulta;
	cargarDatosAdicionales= false;
	
	switch(consulta){
		
		case 0:
			
			urlTo= "<?= BaseUrl::toRoute(['index']); ?>";
			cargarDatosAdicionales= true;
			break;
			
		case 2:
		
			urlTo= "<?= BaseUrl::toRoute(['index']); ?>" + "&id=" + datos.CemTalq.cod;
			cargarDatosAdicionales= false;
			break;
			
		case 3:
		
			urlTo= "<?= BaseUrl::toRoute(['index']); ?>" + "&id=" + datos.CemTalq.cod;
			cargarDatosAdicionales= true;
			break;
			
		default: return;
	}
	
	if(cargarDatosAdicionales){
		
		datos.CemTalq.tipo= $("#formTipo").val();
		datos.CemTalq.desde= $("#formDesde").val();
		datos.CemTalq.hasta= $("#formHasta").val();
		datos.CemTalq.cuadrodesde= $("#formCuadroDesde").val();
		datos.CemTalq.cuadrohasta= $("#formCuadroHasta").val();
		datos.CemTalq.cuerpo_id= $("#formCuerpo").val();
		datos.CemTalq.fila= $("#formFila").val();
		datos.CemTalq.cat= $("#formCategoria").val();
		datos.CemTalq.supdesde= $("#formSuperficieDesde").val();
		datos.CemTalq.suphasta= $("#formSuperficieHasta").val();
		datos.CemTalq.duracion= $("#formDuracion").val();
		datos.CemTalq.modif= $("#formModificacion").val();
	}
	
	$.pjax.reload({
		container: "#pjaxGrabar",
		url: urlTo,
		type: "POST",
		replace: false,
		push: false,
		data: datos
	});
	
}

function habilitarCampos(consulta){
	
	$("#formTipo").prop("disabled", consulta === 2 || consulta === 1);
	$("#formDesde").prop("disabled", consulta === 2 || consulta === 1);
	$("#formHasta").prop("disabled", consulta === 2 || consulta === 1);
	$("#formCuadroDesde").prop("disabled", consulta === 2 || consulta === 1);
	$("#formCuadroHasta").prop("disabled", consulta === 2 || consulta === 1);
	$("#formCuerpo").prop("disabled", consulta === 2 || consulta === 1);
	$("#formFila").prop("disabled", consulta === 2 || consulta === 1);
	$("#formCategoria").prop("disabled", consulta === 2 || consulta === 1);
	$("#formSuperficieDesde").prop("disabled", consulta === 2 || consulta === 1);
	$("#formSuperficieHasta").prop("disabled", consulta === 2 || consulta === 1);
	$("#formDuracion").prop("disabled", consulta === 2 || consulta === 1);
	
	$("#formBotones").toggleClass("hidden", consulta === 1);
	$("#botonGrabar").toggleClass("btn-success", consulta !== 2);
	$("#botonGrabar").toggleClass("btn-danger", consulta === 2);
	
	$("#botonGrabar").text(consulta === 2 ? "Eliminar" : "Grabar");
}

function limpiarFormulario(){
	
	$("#formCodigo").val("");
	$("#formTipo").val("0");
	$("#formDesde").val("");
	$("#formHasta").val("");
	$("#formCuadroDesde").val("0");
	$("#formCuadroHasta").val("0");
	$("#formCuerpo").val("0");
	$("#formFila").val("");
	$("#formCategoria").val("");
	$("#formSuperficieDesde").val("");
	$("#formSuperficieHasta").val("");
	$("#formDuracion").val("");
	$("#formModificacion").val("");
	
	$("#formErrores").css("display", "none");
	$("#formErrores ul").empty();
	
	$("#formulario .has-error").removeClass("has-error");
	
	habilitarCampos(1);
}

function cargarControles(consulta, cod,desde,hasta,tipo,cuadrodesde,cuadrohasta,cuerpo_id,fila,cat,supdesde,suphasta,duracion,modif){

	$("#consultaActual").val(consulta);
	limpiarFormulario();
	habilitarCampos(consulta);
	
	$('#formCodigo').val(cod);				
	$('#formCuadroDesde').val(cuadrodesde === "" ? "0" : cuadrodesde);
	$('#formCuadroHasta').val(cuadrohasta === "" ? "0" : cuadrohasta);
	$('#formTipo').val(tipo);
	$('#formDesde').val(desde);
	$('#formHasta').val(hasta);
	$('#formCuerpo').val(cuerpo_id === "" ? "0" : cuerpo_id);
	$('#formFila').val(fila);
	$('#formCategoria').val(cat === "" ? "0" : cat);
	$('#formSuperficieDesde').val(supdesde);
	$('#formSuperficieHasta').val(suphasta);
	$('#formDuracion').val(duracion);
	$("#formModificacion").val(modif);
	
	event.stopPropagation();
}
</script>