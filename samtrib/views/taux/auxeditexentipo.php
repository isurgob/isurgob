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

$title = 'Tipo de Solicitud de Eximisión';
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
    			if ($model->accesoedita > 0) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxExenTipo();']) 
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
									'onclick' => 'CargarControles('.$model['texen_id'].',"'.
																	$model['nombre'].'","'.
																	$model['norma'].'",'.
																	$model['tobj'].','.
																	$model['item_id'].',"'.
																	$model['trenov'].'",'.
																	$model['sol_sitlab'].','.
																	$model['sol_cony'].','.
																	$model['sol_ingreso'].','.
																	$model['sol_benef'].','.
																	$model['val_propunica'].','.
																	$model['val_benefunaprop'].','.
																	$model['val_titcony'].','.
																	$model['val_persfisica'].','.
																	$model['val_persjuridica'].',"'.
																	$model['est'].'","'.
																	$model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'texen_id','header' => 'Cod', 'contentOptions'=>['style'=>'width:30px;text-align:center;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=>'nombre','header' => 'Nombre' ,'contentOptions'=>['style'=>'width:140px;text-align:left;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=> 'tobj', 'header' => 'Tipo de objeto', 'value' => function($data) { return (utb::getCampo('objeto_tipo','cod='.$data['tobj'])); }, 
					'contentOptions'=>['style'=>'width:140px;text-align:left;font-size:12px;', 'class' => 'grilla']],
            		['attribute'=> 'est', 'header' => 'Estado', 'contentOptions'=>['style'=>'width:140px;text-align:left;font-size:12px;', 'class' => 'grilla']], 
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:35px;text-align:center;'],'template' => (($model->accesoedita > 0) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxExenTipo('.$model['texen_id'].',"'.
															         						$model['nombre'].'","'.
																							$model['norma'].'",'.
																							$model['tobj'].','.
																							$model['item_id'].',"'.
																							$model['trenov'].'",'.
																							$model['sol_sitlab'].','.
																							$model['sol_cony'].','.
																							$model['sol_ingreso'].','.
																							$model['sol_benef'].','.
																							$model['val_propunica'].','.
																							$model['val_benefunaprop'].','.
																							$model['val_titcony'].','.
																							$model['val_persfisica'].','.
																							$model['val_persjuridica'].',"'.
																							$model['est'].'","'.
																							$model['modif'].'");'
													]				 			 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														 'onclick' => 'btnEliminarExenTipo('.$model['texen_id'].',"'.
																							$model['nombre'].'","'.
																							$model['norma'].'",'.
																							$model['tobj'].','.
																							$model['item_id'].',"'.
																							$model['trenov'].'",'.
																							$model['sol_sitlab'].','.
																							$model['sol_cony'].','.
																							$model['sol_ingreso'].','.
																							$model['sol_benef'].','.
																							$model['val_propunica'].','.
																							$model['val_benefunaprop'].','.
																							$model['val_titcony'].','.
																							$model['val_persfisica'].','.
																							$model['val_persjuridica'].',"'.
																							$model['est'].'","'.
																							$model['modif'].'");'
													]
            									);
            						}
            			]
            	   ],            	   
        	],
    	]); 	    

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmObjetoExenTipo']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr height='35px'>
			<td width='48px'><label>Codigo: </label></td>
			<td>
				<?= Html::input('text', 'txCod', $cod, ['class' => 'form-control','id'=>'txCod','maxlength'=> '10','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
			<td width='70px' align='right'><label>Estado: </label></td>			
			<td>
				<?= Html::input('text', 'est',$est,['id' => 'est','class' => 'form-control' ,'style' => 'width:25px;', 'readonly' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
			<tr>
				<td><label>Nombre:</label></td>
				<td><?= Html::input('text', 'txNombre',$nombre,['id' => 'txNombre','class' => 'form-control' ,'style' => 'width:400px;', 'maxlengh' => '70','disabled' => true ]); ?></td>
			</tr>
			<tr>
				<td><label>Norma:</label></td>
				<td><?= Html::input('text', 'norma',$norma,['id' => 'norma','class' => 'form-control' ,'style' => 'width:400px;', 'maxlengh' => '50','disabled' => true ]); ?></td>
			</tr>
		</table>
		
				<table border='0'>
			<td width='50px'><label>Entidad: </label></td>
			<td>					     
				<?= Html::input('text', 'item_id', $item_id, ['class' => 'form-control','id'=>'item_id' ,'onchange' => '$.pjax.reload({container:"#bancoEntidadBancaria",data:{bco_ent:this.value},method:"POST"})', 'maxlength'=> '4','style'=>'width:40px;', 'disabled' => true]); ?>
			</td>
		
		<td align='left'>
		
			
	 <!------------------------------------------ boton de búsqueda modal -------------------------------------->
			
			<?php
			Modal::begin([
                'id' => 'BuscarAuxItem',
				'toggleButton' => [
					'id' => 'botonBuscarItem',
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar',
                    'style' => 'float:left;margin-left:1px;margin-top:1px;'
                    
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right'
                ],
                 ]);                         
                                
            echo $this->render('//taux/auxbusca', [	'tabla' => 'item',
            										'campocod'=>'item_id',
            										'camponombre' => 'nombre', 
            										'boton_id'=>'BuscarAuxItem',
            										'idcampocod'=>'item_id',
            										'idcamponombre'=>'nombre_item'
            									]);

			Modal::end();
            ?>
         
            <!------------------------------ fin de boton de búsqueda modal ------------------------------- -->
		</td>
			<td width='335px'>
			   <?= Html::input('text', 'nombre_item',"",['id' => 'nombre_item','class' => 'form-control' ,'style' => 'width:330px;background-color:#E6E6FA;', 'readonly' => 'readonly' ]); ?>
			</td>
		</table>	
		
		<table border='0'>
			<tr height='35px'>
				<td width='90px'><label>Tipo de Objeto: </label></td>
				<td>
					<?=  Html::dropDownList('tobj',$tobj,utb::getAux('objeto_tipo','cod','nombre',0),['id'=>'tobj','class' => 'form-control', 'disabled' => true]);?>
				</td>
				<td width='90px' align='right'><label>Renovación:</label></td>
				<td>
					<?=  Html::dropDownList('trenov',$trenov,utb::getAux('exen_trenov','cod','nombre',0),['id'=>'trenov','class' => 'form-control', 'disabled' => true]);?>
				</td>
			</tr>
		</table>

		<br>
		<fieldset>
		<legend style='font-size:14px;'><label>Datos a Solicitar</label></legend>
			<table border='0'>
				<tr>
					<td width='100px'><label>Situación Laboral</label></td>
					<td><?= Html::checkbox('sol_sitlab',$sol_sitlab,['class' => 'form-control','id'=>'sol_sitlab','disabled' => true]); ?></td>
					<td width='100px' align='right'><label>Conyuge</label></td>
					<td><?= Html::checkbox('sol_cony',$sol_cony,['class' => 'form-control','id'=>'sol_cony','disabled' => true]); ?></td>
					<td width='100px' align='right'><label>Ingresos</label></td>
					<td><?= Html::checkbox('sol_ingreso',$sol_ingreso,['class' => 'form-control','id'=>'sol_ingreso','disabled' => true]); ?></td>
					<td width='100px' align='right'><label>Beneficios</label></td>
					<td><?= Html::checkbox('sol_benef',$sol_benef,['class' => 'form-control','id'=>'sol_benef','disabled' => true]); ?></td>
				</tr>
			</table>
		</fieldset>
		<br>
		<fieldset>
		<legend style='font-size:14px;'><label>Validaciones</label></legend>
			<table border='0'>	
				<tr>
					<td width='100px'><label>Propiedad Unica</label></td>
					<td><?= Html::checkbox('val_propunica',$val_propunica,['class' => 'form-control','id'=>'val_propunica','disabled' => true]); ?></td>
					<td width='180px' align='right'><label>Beneficia a una Propiedad</label></td>
					<td><?= Html::checkbox('val_benefunaprop',$val_benefunaprop,['class' => 'form-control','id'=>'val_benefunaprop','disabled' => true]); ?></td>
					<td width='200px' align='right'><label>Propiedad del Titular o conyuge</label></td>
					<td><?= Html::checkbox('val_titcony',$val_titcony,['class' => 'form-control','id'=>'val_titcony','disabled' => true]); ?></td>
				</tr>
				</table>
				<table border='0'>
				<tr>	
					<td width='145px'><label>Titular es Persona Fisica</label></td>
					<td><?= Html::checkbox('val_persfisica',$val_persfisica,['class' => 'form-control','id'=>'val_persfisica','disabled' => true]); ?></td>
					<td width='180px' align='right'><label>Titular es Persona Juridica</label></td>
					<td><?= Html::checkbox('val_persjuridica',$val_persjuridica,['class' => 'form-control','id'=>'val_persjuridica','disabled' => true]); ?></td>
				</tr>
			</table>
		</fieldset>
		<br>
		<table border='0' width='750px'>
			<tr>
				<td id='labelModif' align='right' width='600px;'><label>Modificacion:</label></td>
				<td><?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModif','style'=>'width:150px;background:#E6E6FA;float:right;', 'disabled' => true]); ?></td>
			</tr>
		</table>

	        <div class="form-group" id='form_botones' style='display:none'>
	
			<?php

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxExenTipo()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxExenTipo()']); 
	    	?>
	        </div>
    	</div>
    	<?php 
    	if($consulta==1 || $consulta==2){
       	 	echo '<script>$("#botonBuscarItem").prop("disabled",true);</script>'; 
    	}
		
			if($consulta==0){
			?>
			
				<script>
					$('#txAccion').val(0);
					$('#form_botones').css('display','block');	
					$('#form_botones_delete').css('display','none');		
					
					$('#txCod').prop('readOnly',true);
					$('#txNombre').prop('disabled',false);
					$('#norma').prop('disabled',false);
					$('#est').prop('disabled',false);
					$('#tobj').prop('disabled',false);
					$('#item_id').prop('disabled',false);
					$("#botonBuscarItem").prop('disabled',false);
					$('#trenov').prop('disabled',false);
					$(':checkbox').prop('disabled',false);
											
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
					$("#labelModif").css('display','none');
					$('#form_botones').css('display','block');	
					$('#form_botones_delete').css('display','none');					

					$('#txCod').prop('readOnly',true);
					$('#txNombre').prop('disabled',false);
					$('#norma').prop('disabled',false);
					$('#est').prop('disabled',false);
					$('#tobj').prop('disabled',false);
					$('#item_id').prop('disabled',false);
					$("#botonBuscarItem").prop('disabled',false);
					$('#trenov').prop('disabled',false);
					$(':checkbox').prop('disabled',false);
										
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
			'id' => 'MensajeInfoAUXT',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoAUXT').alert('close');}, 5000)</script>";

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
    		
		 //------------------------------------------------------------------------------------------------------------------------
    	
    	
	    	Pjax::begin(['id' => 'cargarNombreItem']);
			
			if(isset($_POST['consulta'])) $consulta=$_POST['consulta'];
			echo "<script>$('#txAccion').val(". $consulta .");</script>";
			
			if(isset($_POST['item'])){
	
				$valor = $model->getNombreItem($_POST['item']);
		
				echo "<script>actualizarNombreItem('". $valor ."');</script>";
				
			}
			
			Pjax::end();		
    	
    	//------------------------------------------------------------------------------------------------------------------------
    	
    	ActiveForm::end(); 
    	
    ?>    	

</div><!-- site-auxedit -->

<script>


	function actualizarNombreItem(dato){
                                                                                                
		var elementoNombreItem = document.getElementById('nombre_item');
		
		elementoNombreItem.value = dato;
	}


   function CargarControles(texen_id,
							nombre,
							norma,
							tobj,
							item_id,
							trenov,
							sol_sitlab,
							sol_cony,
							sol_ingreso,
							sol_benef,
							val_propunica,
							val_benefunaprop,
							val_titcony,
							val_persfisica,
							val_persjuridica,
							est,
							modif)
			{
			event.stopPropagation();
			$("#txCod").val(texen_id);
			$("#txNombre").val(nombre);
			$("#norma").val(norma);
			$("#est").val(est);
			$("#tobj").val(tobj);
			$("#item_id").val(item_id);
			$("#trenov").val(trenov);
			if(sol_sitlab==1){$('#sol_sitlab').prop('checked',true);}else{$('#sol_sitlab').prop('checked',false);}
			if(sol_cony==1){$('#sol_cony').prop('checked',true);}else{$('#sol_cony').prop('checked',false);}
			if(sol_ingreso==1){$('#sol_ingreso').prop('checked',true);}else{$('#sol_ingreso').prop('checked',false);}
			if(sol_benef==1){$('#sol_benef').prop('checked',true);}else{$('#sol_benef').prop('checked',false);}
			if(val_propunica==1){$('#val_propunica').prop('checked',true);}else{$('#val_propunica').prop('checked',false);}
			if(val_titcony==1){$('#val_titcony').prop('checked',true);}else{$('#val_titcony').prop('checked',false);}
			if(val_benefunaprop==1){$('#val_benefunaprop').prop('checked',true);}else{$('#val_benefunaprop').prop('checked',false);}
			if(val_persfisica==1){$('#val_persfisica').prop('checked',true);}else{$('#val_persfisica').prop('checked',false);}
			if(val_persjuridica==1){$('#val_persjuridica').prop('checked',true);}else{$('#val_persjuridica').prop('checked',false);}
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
			
			$.pjax.reload(
			{
				container:"#cargarNombreItem",
				data:{
						item:item_id,
						consulta:1
					},
				method:"POST"
			});
			
	}

	function btnNuevoAuxExenTipo(){
		
			$('#txAccion').val(0);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
				
			$('#txCod').val('');
			$('#txNombre').val('');
			$('#txNombre').prop('disabled',false);
			$('#nombre_Entidad_Bancaria').val('');
			$('#norma').val('');
			$('#norma').prop('disabled',false);
			$('#est').val('');
			$("#tobj option:first-of-type").attr("selected", "selected");
			$('#tobj').prop('disabled',false);
			$('#item_id').val('');
			$('#item_id').prop('disabled',false);
			$('#nombre_item').val('');
			$("#botonBuscarItem").prop('disabled',false);
			$("#trenov option:first-of-type").attr("selected", "selected");
			$('#trenov').prop('disabled',false);
			$(':checkbox').prop('disabled',false);
			$(':checkbox').prop("checked","");	
			
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
	
  function btnModificarAuxExenTipo(texen_id,
								nombre,
								norma,
								tobj,
								item_id,
								trenov,
								sol_sitlab,
								sol_cony,
								sol_ingreso,
								sol_benef,
								val_propunica,
								val_benefunaprop,
								val_titcony,
								val_persfisica,
								val_persjuridica,
								est,
								modif){
		
			event.stopPropagation();
			//$('#txAccion').val(3);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
			
			$('#txCod').val(texen_id);
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val(nombre);
			$('#txNombre').prop('disabled',false);
			$('#norma').val(norma);
			$('#norma').prop('disabled',false);
			$('#est').val(est);
			$("#tobj").val(tobj);
			$('#tobj').prop('disabled',false);
			$('#item_id').val(item_id);
			$('#item_id').prop('disabled',false);
			$("#botonBuscarItem").prop('disabled',false);
			$("#trenov").val(trenov);
			$('#trenov').prop('disabled',false);
			$(':checkbox').prop('disabled',false);
			if(sol_sitlab==1){$('#sol_sitlab').prop('checked',true);}else{$('#sol_sitlab').prop('checked',false);}	
			if(sol_cony==1){$('#sol_cony').prop('checked',true);}else{$('#sol_cony').prop('checked',false);}
			if(sol_ingreso==1){$('#sol_ingreso').prop('checked',true);}else{$('#sol_ingreso').prop('checked',false);}	
			if(sol_benef==1){$('#sol_benef').prop('checked',true);}else{$('#sol_benef').prop('checked',false);}
			if(val_propunica==1){$('#val_propunica').prop('checked',true);}else{$('#val_propunica').prop('checked',false);}
			if(val_titcony==1){$('#val_titcony').prop('checked',true);}else{$('#val_titcony').prop('checked',false);}
			if(val_benefunaprop==1){$('#val_benefunaprop').prop('checked',true);}else{$('#val_benefunaprop').prop('checked',false);}	
			if(val_persfisica==1){$('#val_persfisica').prop('checked',true);}else{$('#val_persfisica').prop('checked',false);}
			if(val_persjuridica==1){$('#val_persjuridica').prop('checked',true);}else{$('#val_persjuridica').prop('checked',false);}
			
			$.pjax.reload(
			{
				container:"#cargarNombreItem",
				data:{
						item:item_id,
						consulta:3
					},
				method:"POST"
			});		
					
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
	
	function btnEliminarExenTipo(texen_id,
								nombre,
								norma,
								tobj,
								item_id,
								trenov,
								sol_sitlab,
								sol_cony,
								sol_ingreso,
								sol_benef,
								val_propunica,
								val_benefunaprop,
								val_titcony,
								val_persfisica,
								val_persjuridica,
								est,
								modif){
			
			event.stopPropagation();
			//$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$('#txCod').val(texen_id);
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val(nombre);
			$('#txNombre').prop('disabled',true);
			$('#norma').val(norma);
			$('#norma').prop('disabled',true);
			$('#est').val(est);
			$('#est').prop('disabled',true);
			$("#tobj").val(tobj);
			$('#tobj').prop('disabled',true);
			$('#item_id').val(item_id);
			$('#item_id').prop('disabled',true);
			$("#botonBuscarItem").prop('disabled',true);
			$("#trenov").val(trenov);
			$('#trenov').prop('disabled',true);
			$(':checkbox').prop('disabled',true);
			if(sol_sitlab==1){$('#sol_sitlab').prop('checked',true);}else{$('#sol_sitlab').prop('checked',false);}	
			if(sol_cony==1){$('#sol_cony').prop('checked',true);}else{$('#sol_cony').prop('checked',false);}
			if(sol_ingreso==1){$('#sol_ingreso').prop('checked',true);}else{$('#sol_ingreso').prop('checked',false);}	
			if(sol_benef==1){$('#sol_benef').prop('checked',true);}else{$('#sol_benef').prop('checked',false);}
			if(val_propunica==1){$('#val_propunica').prop('checked',true);}else{$('#val_propunica').prop('checked',false);}
			if(val_titcony==1){$('#val_titcony').prop('checked',true);}else{$('#val_titcony').prop('checked',false);}
			if(val_benefunaprop==1){$('#val_benefunaprop').prop('checked',true);}else{$('#val_benefunaprop').prop('checked',false);}	
			if(val_persfisica==1){$('#val_persfisica').prop('checked',true);}else{$('#val_persfisica').prop('checked',false);}
			if(val_persjuridica==1){$('#val_persjuridica').prop('checked',true);}else{$('#val_persjuridica').prop('checked',false);}
											
			$.pjax.reload(
			{
				container:"#cargarNombreItem",
				data:{
						item:item_id,
						consulta:2
					},
				method:"POST"
			});			
									
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}

	function btnCancelarAuxExenTipo(){
		
			$('.error-summary').css('display','none');
			$('#GrillaTablaAux').css("pointer-events", "all");
			$('#GrillaTablaAux').css("color", "#111111");
			$('#GrillaTablaAux a').css("color", "#337ab7");
			$('#GrillaTablaAux Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','none');
					
			$('#txCod').val('');
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val('');
			$('#txNombre').prop('disabled',true);
			$('#norma').val('');
			$('#norma').prop('disabled',true);
			$('#est').val('');
			$('#est').prop('disabled',true);
			$("#tobj option:first-of-type").attr("selected", "selected");
			$('#tobj').prop('disabled',true);
			$('#item_id').val('');
			$('#item_id').prop('disabled',true);
			$('#nombre_item').val('');
			$("#botonBuscarItem").prop('disabled',true);
			$("#trenov option:first-of-type").attr("selected", "selected");
			$(':checkbox').prop('disabled',true);
			$(':checkbox').prop("checked","");
			
			$('#txModif').val('');
			$("#labelModif").css('display','block');
			$("#txModif").css('display','block');
			
	}

	function btGrabarClick(){
		
			err = "";
			
			if ($("#txNombre").val()=="")
			{
				err += "<li>Ingrese el Nombre del Tipo de Eximisíon</li>";
			}
			if ($("#norma").val()=="")
			{
				err += "<li>Ingrese una Norma</li>";
			}
			
			if (err == "")
			{
				$("#frmObjetoExenTipo").submit();
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
