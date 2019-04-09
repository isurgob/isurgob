<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\models\config\ObjetoTipo;
use \yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use app\utils\db\Fecha;
use yii\bootstrap\Alert;
use app\utils\db\utb;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Tipos de Objeto';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

$model = isset($model) ? $model : new ObjetoTipo();
if (isset($accion) == null) $accion = 1;

?>
<div class="objeto-tipo-index">

    <h1><?= Html::encode($title) ?></h1>

    <p style='float:right;margin-top:-20px;'>
        <?php 
        	if (utb::getExisteProceso(3061))
        		echo Html::Button('Nuevo',['id'=>'btnNuevo','class' => 'btn btn-success','onclick'=>'btnNuevoObjetoTipoClick()','style' => 'visibility:hidden;']) 
    	?>
    </p>

    <?= GridView::widget([
    'id' => 'GrillaObjetoTipo',
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['class' => 'grilla'],
        'rowOptions' => function ($model,$key,$index,$grid){	
		        						 return [
											    'onclick' => 'cargarControles('.
											    $model['cod'].',"'.
											    $model['nombre'].'","'.
											    $model['nombre_redu'].'","'.
											    $model['campo_clave'].'","'.
											    $model['letra'].'","'.
											    $model['est'].'",'.
											    $model['autoinc'].',"'.
											    $model['modif'].'");'  
						                   ];
        					
        					},
        'columns' => [
			['attribute' => 'cod', 'header' => 'Codigo', 'contentOptions' => ['style' => 'width:7%;text-align:center;','class'=>'grilla']],
			['attribute' => 'nombre', 'header' => 'Nombre', 'contentOptions' => ['style' => 'width:30%','class'=>'grilla']],
            ['attribute' => 'nombre_redu', 'header' => 'Nombre Reducido', 'contentOptions' => ['style' => 'width:10%;','class'=>'grilla']],
            ['attribute' => 'letra', 'header' => 'Letra', 'contentOptions' => ['style' => 'width:9%;text-align:center;','class'=>'grilla']],
            ['attribute' => 'est', 'header' => 'Estado', 'contentOptions' => ['style' => 'width:9%;text-align:center;','class'=>'grilla']],
            ['attribute' => 'autoinc', 'header' => 'Autoincremental','value' => function($data) {if($data['autoinc']==1){return 'Autoincremental';}else if($data['autoinc']==0){return 'No Autoincremental';} }  
			,'contentOptions' => ['style' => 'width:15%;text-align:left;','class'=>'grilla']],
            ['attribute' => 'campo_clave', 'header' => 'Campo Clave', 'contentOptions' => ['style' => 'width:13%;text-align:left;','class'=>'grilla']],

            ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['style' => 'width:7%;','align'=>'center','class'=>'grilla'], 'template' => (utb::getExisteProceso(3061) ? '{update}{delete}' : ''),
            'buttons' => [   
            				'update' => function($url,$model,$key)
            							{
            								return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            								[
            									'class' => 'bt-buscar-label',
            									'style' => 'color:#337ab7;visibility:hidden;',
            									'onclick' => 'btnModificarObjetoTipoClick('.
													    $model['cod'].',"'.
													    $model['nombre'].'","'.
													    $model['nombre_redu'].'","'.
													    $model['campo_clave'].'","'.
													    $model['letra'].'","'.
													    $model['est'].'",'.
													    $model['autoinc'].',"'.
													    $model['modif'].'");'  
											]											
            							);	
            						},
            							
            				'delete' => function($url,$model,$key)
            						{      	 
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            								[
													'class' => 'bt-buscar-label',
													'style' => 'color:#337ab7;visibility:hidden;',
												    'onclick' => 'btnEliminarObjetoTipoClick('.
												    $model['cod'].',"'.
												    $model['nombre'].'","'.
												    $model['nombre_redu'].'","'.
												    $model['campo_clave'].'","'.
												    $model['letra'].'","'.
												    $model['est'].'",'.
												    $model['autoinc'].',"'.
												    $model['modif'].'");'  
											]);												
            		
            						}
            			            ],
            			         ],
                             ] ]); ?>                                 
 				</div>
                             
<div class="form" style='padding:15px 5px; margin-bottom:5px'>
																																							
    <?php $form = ActiveForm::begin(['action' => ['tipodeobjetoabm'], 'fieldConfig' => ['template' => "{label}{input}"]]); 
    
    echo Html::input('hidden', 'accion', $accion, ['id'=>'accion']);
    echo Html::input('hidden', 'idDelete',"", ['id'=>'idDelete']);
    ?>

	<table border='0'>
		<tr>
			<td align='right'> 
				<?= $form->field($model, 'cod',['options' => ['id' => 'codObjetoTipo']])->textInput(['maxlength' => 2,'style' => 'width:40px;','readOnly'=>'true']) ?>
			</td>
			<td width='20px'></td>
			<td width='500px'>    
				<?= $form->field($model, 'nombre',['options' => ['id' => 'nomObjetoTipo']])->textInput(['maxlength' => 25,'style' => 'width:250px;','disabled'=>'true']) ?>
			</td>
		</tr>
		<tr>
			<td align='right'>   
				<?= $form->field($model, 'nombre_redu',['options' => ['id' => 'reduObjetoTipo']])->textInput(['maxlength' => 3,'style' => 'width:40px;','disabled'=>'true']) ?>
			</td>
			<td width='20px'></td>
			<td>
				 
				<?= $form->field($model, 'campo_clave',['options' => ['id' => 'claveObjetoTipo']])->textInput(['maxlength' => 15,'style' => 'width:120px;','disabled'=>'true']) ?>
				
				<div style='float:right;margin-top:-28px;margin-right:202px;'><?= $form->field($model, 'est')->textInput(['maxlength' => 1,'style' => 'width:25px;','disabled' => 'true']) ?></div>
				
			</td>
		</tr>
		<tr>
			<td align='right'>    
				<?= $form->field($model, 'letra',['options' => ['id' => 'letraObjetoTipo']])->textInput(['maxlength' => 1,'style' => 'width:40px;','disabled'=>'true']) ?>
			</td>
			<td width='20px'></td>
			<td>    
				<?= $form->field($model, 'autoinc')->checkbox(['check'=> 1, 'uncheck' => 0,'disabled'=>'true']) ?>
				<div id='modif_label' style='float:right;margin-top:-25px;'><?= $form->field($model, 'modif')->textInput(['maxlength' => 1,'style' => 'width:150px;','disabled'=>'true']) ?></div>
			</td>
		</tr>

	</table>    
	 
		<div id='form_botones' style='display:none' class="form-group"> 
		
	        <?php echo Html::submitButton('Grabar', ['class' => 'btn btn-success', 'id' => 'objetoTipoSubmit']); ?>
			<?php echo Html::Button('Cancelar', ['id'=>'btnCancelar','class' => 'btn btn-primary','onclick'=>'btnCancelarObjetoTipoClick()']); ?>
			
		</div>
		<div id='form_botones_delete' style='display:none' class="form-group"> 
		
	        <?php echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'id' => 'objetoTipoSubmit']); ?>
			<?php echo Html::Button('Cancelar', ['id'=>'btnCancelar','class' => 'btn btn-primary','onclick'=>'btnCancelarObjetoTipoClick()']); ?>
			
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
			
		//--------------------------seccion de errores------------------------

		
		
		if(isset($error) and $error !== '') {  
		echo '<div id="error-summary" class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';

				} 
		?>

<?php
	
	if($accion==0){
 ?>		
		<script>
		
			$('#form_botones').css('display','block');	
			$('#objetotipo-cod').prop('readOnly',false);
			$('#objetotipo-nombre').prop('disabled',false);
			$('#objetotipo-nombre_redu').prop('disabled',false);
			$('#objetotipo-camp_clave').prop('disabled',false);
			$('#objetotipo-letra').prop('disabled',false);
			$('#objetotipo-autoinc').prop('disabled','disabled');
			$('#objetotipo-modif').css('display','none');
			$('#modif_label').css('display','none');
			$('#accion').val(0);
			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaObjetoTipo').css("pointer-events", "none");
			$('#GrillaObjetoTipo').css("color", "#ccc");	
			$('#GrillaObjetoTipo Button').css("color", "#ccc");
			$('#GrillaObjetoTipo a').css("color", "#ccc");
		
		</script>
		
<?php	}else if($accion==2){ ?>
	
		<script>
		
			$('#form_botones').css('display','none');	
			$('#objetotipo-cod').prop('readOnly',true);
			$('#objetotipo-nombre').prop('disabled',true);
			$('#objetotipo-nombre_redu').prop('disabled',true);
			$('#objetotipo-camp_clave').prop('disabled',true);
			$('#objetotipo-letra').prop('disabled',true);
			$('#objetotipo-autoinc').prop('disabled','disabled');
			$('#objetotipo-modif').css('display','none');
			$('#modif_label').css('display','none');
			$('#accion').val(0);
			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaObjetoTipo').css("pointer-events", "none");
			$('#GrillaObjetoTipo').css("color", "#ccc");	
			$('#GrillaObjetoTipo Button').css("color", "#ccc");
			$('#GrillaObjetoTipo a').css("color", "#ccc");
			
		</script>
			
<?php }else if($accion==3){ ?>
		<script>
		
			$('#form_botones').css('display','block');	
			$('#objetotipo-cod').prop('readOnly',true);
			$('#objetotipo-nombre').prop('disabled',false);
			$('#objetotipo-nombre_redu').prop('disabled',false);
			$('#objetotipo-camp_clave').prop('disabled',false);
			$('#objetotipo-letra').prop('disabled',false);
			$('#objetotipo-autoinc').prop('disabled','disabled');
			$('#objetotipo-modif').css('display','none');
			$('#modif_label').css('display','none');
			$('#accion').val(3);
			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaObjetoTipo').css("pointer-events", "none");
			$('#GrillaObjetoTipo').css("color", "#ccc");	
			$('#GrillaObjetoTipo Button').css("color", "#ccc");
			$('#GrillaObjetoTipo a').css("color", "#ccc");
			
		</script>
			
<?php }	?>




<script>

	function cargarControles(cod, nombre, nombre_redu, campo_clave, letra, est, autoinc,modif){	
			
		event.stopPropagation();
		$('.error-summary').css('display','none');
		$('#form_botones').css('display','none');		
		$('#objetotipo-cod').val(cod);
		$('#objetotipo-cod').prop('readOnly','true');
		$('#objetotipo-nombre').val(nombre);
		$('#objetotipo-nombre').prop('disabled','disabled');
		$('#objetotipo-nombre_redu').val(nombre_redu);
		$('#objetotipo-nombre_redu').prop('disabled','disabled');
		$('#objetotipo-campo_clave').val(campo_clave);
		$('#objetotipo-campo_clave').prop('disabled','disabled');
		$('#objetotipo-letra').val(letra);
		$('#objetotipo-letra').prop('disabled','disabled');
		$('#objetotipo-est').val(est);
		if(autoinc==1){$('#objetotipo-autoinc').prop("checked","checked");}else{$('#objetotipo-autoinc').prop("checked","");}
		$('#objetotipo-autoinc').prop('disabled','disabled');
		$('#objetotipo-modif').val(modif);
		$('#objetotipo-modif').prop('disabled','disabled');
	}
	
	
	
		function btnNuevoObjetoTipoClick(){
		
		$('.error-summary').css('display','none');
		$('#form_botones').css('display','block');	
		$('#form_botones_delete').css('display','none');
		$('#objetotipo-cod').val('');
		$('#objetotipo-cod').prop('readOnly',false);
		$('#objetotipo-nombre').val('');
		$('#objetotipo-nombre').prop('disabled',false);
		$('#objetotipo-nombre_redu').val('');
		$('#objetotipo-nombre_redu').prop('disabled',false);
		$('#objetotipo-campo_clave').val('');
		$('#objetotipo-campo_clave').prop('disabled',false);
		$('#objetotipo-letra').val('');
		$('#objetotipo-letra').prop('disabled',false);
		$('#objetotipo-est').val('');
		$('#objetotipo-autoinc').prop("checked","");
		$('#objetotipo-autoinc').prop('disabled',false);
		$('#objetotipo-modif').css('display','none');
		$('#modif_label').css('display','none');
		$('#accion').val(0);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaObjetoTipo').css("pointer-events", "none");
		$('#GrillaObjetoTipo').css("color", "#ccc");	
		$('#GrillaObjetoTipo Button').css("color", "#ccc");
		$('#GrillaObjetoTipo a').css("color", "#ccc");
	}
	
	function btnModificarObjetoTipoClick(cod, nombre, nombre_redu, campo_clave, letra, est, autoinc,modif){
		
		event.stopPropagation();
		$('.error-summary').css('display','none');
		$('#form_botones').css('display','block');
		$('#form_botones_delete').css('display','none');	
		$('#objetotipo-cod').val(cod);
		$('#objetotipo-cod').prop('readOnly',true);
		$('#objetotipo-nombre').val(nombre);
		$('#objetotipo-nombre').prop('disabled',false);
		$('#objetotipo-nombre_redu').val(nombre_redu);
		$('#objetotipo-nombre_redu').prop('disabled',false);
		$('#objetotipo-campo_clave').val(campo_clave);
		$('#objetotipo-campo_clave').prop('disabled',false);
		$('#objetotipo-letra').val(letra);
		$('#objetotipo-letra').prop('disabled',false);
		$('#objetotipo-est').val(est);
		if(autoinc==1){$('#objetotipo-autoinc').prop("checked","checked");}else{$('#objetotipo-autoinc').prop("checked","");}
		$('#objetotipo-autoinc').prop('disabled',false);
		$('#objetotipo-modif').css('display','none');
		$('#modif_label').css('display','none');
		$('#accion').val(3);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaObjetoTipo').css("pointer-events", "none");
		$('#GrillaObjetoTipo').css("color", "#ccc");	
		$('#GrillaObjetoTipo Button').css("color", "#ccc");
		$('#GrillaObjetoTipo a').css("color", "#ccc");
	}
	
	
	
		function btnEliminarObjetoTipoClick(cod, nombre, nombre_redu, campo_clave, letra, est, autoinc,modif){
			
		event.stopPropagation();
		$('.error-summary').css('display','none');
		$('#form_botones_delete').css('display','block');
		$('#form_botones').css('display','none');	
		$('#objetotipo-cod').val(cod);
		$('#objetotipo-cod').prop('readOnly',true);
		$('#objetotipo-nombre').val(nombre);
		$('#objetotipo-nombre').prop('disabled',true);
		$('#objetotipo-nombre_redu').val(nombre_redu);
		$('#objetotipo-nombre_redu').prop('disabled',true);
		$('#objetotipo-campo_clave').val(campo_clave);
		$('#objetotipo-campo_clave').prop('disabled',true);
		$('#objetotipo-letra').val(letra);
		$('#objetotipo-letra').prop('disabled',true);
		$('#objetotipo-est').val(est);
		if(autoinc==1){$('#objetotipo-autoinc').prop("checked","checked");}else{$('#objetotipo-autoinc').prop("checked","");}
		$('#objetotipo-autoinc').prop('disabled',true);
		$('#objetotipo-modif').css('display','none');
		$('#modif_label').css('display','none');

		  
		$('#accion').val(2);
		$('#idDelete').val(cod);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaObjetoTipo').css("pointer-events", "none");
		$('#GrillaObjetoTipo').css("color", "#ccc");	
		$('#GrillaObjetoTipo Button').css("color", "#ccc");
		$('#GrillaObjetoTipo a').css("color", "#ccc");
	}
	
	
	
	function btnCancelarObjetoTipoClick(){
		
		$('.error-summary').css('display','none');
		$('#GrillaObjetoTipo').css("pointer-events", "all");
		$('#GrillaObjetoTipo').css("color", "#111111");
		$('#GrillaObjetoTipo a').css("color", "#337ab7");
		$('#GrillaObjetoTipo Button').css("color", "#337ab7");
		$('#btnNuevo').css("pointer-events", "all");
		$('#btnNuevo').css("opacity", 1 );
		
		
		$('#form_botones').css('display','none');
		$('#form_botones_delete').css('display','none');
		$('#objetotipo-cod').val('');
		$('#objetotipo-cod').prop('disabled',true);
		$('#objetotipo-nombre').val('');
		$('#objetotipo-nombre').prop('disabled',true);
		$('#objetotipo-nombre_redu').val('');
		$('#objetotipo-nombre_redu').prop('disabled',true);
		$('#objetotipo-campo_clave').val('');
		$('#objetotipo-campo_clave').prop('disabled',true);
		$('#objetotipo-letra').val('');
		$('#objetotipo-letra').prop('disabled',true);
		$('#objetotipo-est').val('');
		$('#objetotipo-autoinc').prop('disabled',false);
		
		$('#codObjetoTipo').removeClass('has-error');
		$('#codObjetoTipo').removeClass('has-success');
		$('#nomObjetoTipo').removeClass('has-error');
		$('#nomObjetoTipo').removeClass('has-success');
		$('#reduObjetoTipo').removeClass('has-error');
		$('#reduObjetoTipo').removeClass('has-success');
		$('#letraObjetoTipo').removeClass('has-error');
		$('#letraObjetoTipo').removeClass('has-success');
		$('#claveObjetoTipo').removeClass('has-success');

	}

</script>

