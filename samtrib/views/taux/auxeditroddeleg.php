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

$title = 'Delegación de Rodado';
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
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxRodadoDeleg();']) 
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
									'onclick' => 'CargarControles('.$model['cod'].',"'.
																	$model['nombre'].'","'.
																	$model['encargado'].'",'.
																	$model['prov_id'].',"'.
																	$model['localidad'].'","'.
																	$model['domi'].'",'.
																	$model['cp'].',"'.
																	$model['tel'].'","'.
																	$model['fax'].'","'.
																	$model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'cod','header' => 'Codigo', 'contentOptions'=>['style'=>'width:20px;text-align:center;font-size:12px;', 'class' => 'grilla']],          		
            		['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'width:130px;text-align:left;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=>'encargado','header' => 'Encargado' ,'contentOptions'=>['style'=>'width:170px;text-align:left;font-size:12px;', 'class' => 'grilla']],  
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:35px;text-align:center;'],'template' => (($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxRodadoDeleg('.$model['cod'].',"'.
																								   $model['nombre'].'","'.
																								   $model['encargado'].'",'.
																								   $model['prov_id'].',"'.
																								   $model['localidad'].'","'.
																								   $model['domi'].'",'.
																								   $model['cp'].',"'.
																								   $model['tel'].'","'.
																								   $model['fax'].'","'.
																								   $model['modif'].'");'
													]				 			 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxRodadoDeleg('.$model['cod'].',"'.
																							      $model['nombre'].'","'.
																							      $model['encargado'].'",'.
																							      $model['prov_id'].',"'.
																							      $model['localidad'].'","'.
																							      $model['domi'].'",'.
																							      $model['cp'].',"'.
																							      $model['tel'].'","'.
																							      $model['fax'].'","'.
																							      $model['modif'].'");'
													]
            									);
	            						}
				    			]
				    	   ],
				    	   
					],
				]); 	    

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmRodadoDeleg']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr>
			<td width='44px'><label>Codigo: </label></td>
			<td>
				<?= Html::input('text', 'txCod', $cod, ['class' => 'form-control','id'=>'txCod','maxlength'=> '10','style'=>'width:60px;', 'readonly' => true]); ?>
			</td>
			</tr>
			<tr>
			<td width='70px' align='left'><label>Nombre: </label></td>		
			<td>
			   <?= Html::input('text', 'txNombre', $nombre, ['class' => 'form-control','id'=>'txNombre','maxlength'=> '60','style'=>'width:400px;', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr>
			<td width='70px' align='left'><label>Encargado: </label></td>		
			<td width='80px'>
			  <?= Html::input('text', 'encargado',$encargado,['id' => 'encargado','class' => 'form-control','maxlength'=>'60' ,'style' => 'width:400px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr>
			<td align='left' width='70px'><label>Provincia: </label></td>
			<td>
				<?= Html::dropDownList('prov_id',$prov_id,utb::getAux('domi_provincia','prov_id','nombre',0),['id'=>'prov_id','class' => 'form-control', 'disabled' => true]);?>
			</td>
			</tr>
			<tr>
			<td align='left'><label>Localidad: </label></td>
			<td><?= Html::input('text', 'localidad',$localidad,['id' => 'localidad','class' => 'form-control','maxlength'=>'30' ,'style' => 'width:400px;', 'disabled' => true ]); ?></td>
		</tr>
		</table>

		<table border='0'>
		<tr>
			<td width='70px'><label>Domicilio: </label></td>
			<td>
				<?= Html::input('text', 'domi', $domi, ['class' => 'form-control','id'=>'domi','maxlength'=> '50','style'=>'width:297px;', 'disabled' => true]); ?>
			</td>
			<td width='40px' align='right'><label>C.P.: </label></td>		
			<td>
			   <?= Html::input('text', 'cp',$cp,['id' => 'cp','class' => 'form-control','maxlength'=>'10' ,'style' => 'width:60px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr>
			<td width='70px'><label>Telefono: </label></td>
			<td>
				<?= Html::input('text', 'tel',$tel,['id' => 'tel','class' => 'form-control','maxlength'=>'35' ,'style' => 'width:298px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		<tr>
			<td><label>Fax: </label></td>
			<td>
				<?= Html::input('text', 'fax',$fax,['id' => 'fax','class' => 'form-control','maxlength'=>'20' ,'style' => 'width:298px;', 'disabled' => true ]); ?>
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
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxRodadoDeleg()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxRodadoDeleg()']); 
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
					
					$('#txCod').prop('readOnly',true);
					$('#txNombre').prop('disabled',false);
					$('#encargado').prop('disabled',false);
					$('#prov_id').prop('disabled',false);
					$('#localidad').prop('disabled',false);
					$('#domi').prop('disabled',false);
					$('#cp').prop('disabled',false);
					$('#tel').prop('disabled',false);
					$('#fax').prop('disabled',false);
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

					$('#txCod').prop('readOnly',true);
					$('#txNombre').prop('disabled',false);
					$('#encargado').prop('disabled',false);
					$('#prov_id').prop('disabled',false);
					$('#localidad').prop('disabled',false);
					$('#domi').prop('disabled',false);
					$('#cp').prop('disabled',false);
					$('#tel').prop('disabled',false);
					$('#fax').prop('disabled',false);
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
	
	function CargarControles(cod,nombre,encargado,prov_id,localidad,domi,cp,tel,fax,modif)
	{
			event.stopPropagation();
			$("#txCod").val(cod);
			$("#txNombre").val(nombre);
			$("#encargado").val(encargado);
			$("#prov_id").val(prov_id);
			$("#localidad").val(localidad);
			$("#domi").val(domi);
			$("#cp").val(cp);
			$("#tel").val(tel);
			$("#fax").val(fax)
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	
	}

	function btnNuevoAuxRodadoDeleg(){
		
			$('#txAccion').val(0);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
				
			$('#txCod').prop('readOnly',true);
			$('#txNombre').prop('disabled',false);
			$('#encargado').prop('disabled',false);
			$('#prov_id').prop('disabled',false);
			$('#localidad').prop('disabled',false);
			$('#domi').prop('disabled',false);
			$('#cp').prop('disabled',false);
			$('#tel').prop('disabled',false);
			$('#fax').prop('disabled',false);
					
			$("#txCod").val('');
			$("#txNombre").val('');
			$("#encargado").val('');
			$("#prov_id option:first-of-type").attr("selected", "selected");
			$("#localidad").val('');
			$("#domi").val('');
			$("#cp").val('');
			$("#tel").val('');
			$("#fax").val('');
				
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
	
	function btnModificarAuxRodadoDeleg(cod,nombre,encargado,prov_id,localidad,domi,cp,tel,fax,modif){
		
			event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
			
			$('#txCod').prop('readOnly',true);
			$('#txNombre').prop('disabled',false);
			$('#encargado').prop('disabled',false);
			$('#prov_id').prop('disabled',false);
			$('#localidad').prop('disabled',false);
			$('#domi').prop('disabled',false);
			$('#cp').prop('disabled',false);
			$('#tel').prop('disabled',false);
			$('#fax').prop('disabled',false);
			
			$("#txCod").val(cod);
			$("#txNombre").val(nombre);
			$("#encargado").val(encargado);
			$("#prov_id").val(prov_id);
			$("#localidad").val(localidad);
			$("#domi").val(domi);
			$("#cp").val(cp);
			$("#tel").val(tel);
			$("#fax").val(fax)
			
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
	
	function btnEliminarAuxRodadoDeleg(cod,nombre,encargado,prov_id,localidad,domi,cp,tel,fax,modif){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$("#txCod").val(cod);
			$("#txNombre").val(nombre);
			$("#encargado").val(encargado);
			$("#prov_id").val(prov_id);
			$("#localidad").val(localidad);
			$("#domi").val(domi);
			$("#cp").val(cp);
			$("#tel").val(tel);
			$("#fax").val(fax)
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

	function btnCancelarAuxRodadoDeleg(){
		
			$('.error-summary').css('display','none');
			$('#GrillaTablaAux').css("pointer-events", "all");
			$('#GrillaTablaAux').css("color", "#111111");
			$('#GrillaTablaAux a').css("color", "#337ab7");
			$('#GrillaTablaAux Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );
					
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','none');

			$("#txCod").val('');
			$("#txNombre").val('');
			$("#encargado").val('');
			$("#prov_id option:first-of-type").attr("selected", "selected");
			$("#localidad").val('');
			$("#domi").val('');
			$("#cp").val('');
			$("#tel").val('');
			$("#fax").val('');
			
			$('#txCod').prop('readOnly',true);
			$('#txNombre').prop('disabled',true);
			$('#encargado').prop('disabled',true);
			$('#prov_id').prop('disabled',true);
			$('#localidad').prop('disabled',true);
			$('#domi').prop('disabled',true);
			$('#cp').prop('disabled',true);
			$('#tel').prop('disabled',true);
			$('#fax').prop('disabled',true);

			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
	}


	function btGrabarClick(){
		
			err = "";
			
			if ($("#txNombre").val()=="")
			{
				err += "<li>Ingrese un Nombre</li>";
			}
			if ($("#encargado").val()=="")
			{
				err += "<li>Ingrese un encargado</li>";
			}
			if ($("#domi").val()=="")
			{
				err += "<li>Ingrese un domicilio</li>";
			}

			if (err == "")
			{
				$("#frmRodadoDeleg").submit();
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
