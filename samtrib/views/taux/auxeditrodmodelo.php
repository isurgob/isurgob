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
//session_start();
$title = 'Modelos de Rodado';
$this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares','url' => ['taux']];
$this->params['breadcrumbs'][] = $title;

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

if (isset($consulta) == null) $consulta = 1;
if (isset($_GET['mensaje']) == null) $_GET['mensaje'] = '';
?>

<script type="text/javascript">

</script>

<div class="site-auxedit">
	<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
    	<td align='right'>
    		<?php 
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxRodadoModelo();']) 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    
	
		<!-- -------------------- Inicio comboBox que filtra grilla por tipo de objeto ---------------  -->
	
	    <?php  $form = ActiveForm::begin(['id' => 'form-modelo-filtro']); ?>
		    </br> 
			    <table border='0'>
					 <tr height='35px'>
					    <td><label>Modelo:</label></td>    	
						<td>	
							<?= Html::input('text', 'buscarModelo',"",['id' => 'buscarModelo','class' => 'form-control','maxlength'=>'50' ,'style' => 'width:280px;', 'disabled' => false ]); ?>
						</td>
						<td align='right' width='60px'><label>Marca:</label></td>    	
						<td>	
							<?= Html::input('text', 'buscarMarca',"",['id' => 'buscarMarca','class' => 'form-control','maxlength'=>'50' ,'style' => 'width:280px;', 'disabled' => false ]); ?>
						</td>		 
					</tr>
					<tr>
						<td align='right' colspan='4'>
							<?= Html::button('Buscar', ['class' => 'btn btn-primary', 'id' => 'btnBuscarModelo', 'onclick' => '$.pjax.reload({container:"#idGrid",data:{modelo:$("#buscarModelo").val(),marca:$("#buscarMarca").val(),bendera:1},method:"POST"})']); ?>
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
		
		 if(isset($_POST['bendera'])){
		 	$_SESSION['modelo'] = $_POST['modelo'];
		 	$_SESSION['marca'] = $_POST['marca'];		 	
		 }else {
			$_SESSION['modelo'] = '';
		 	$_SESSION['marca'] = '';		 	
		 }
    	 
    	echo "<script> $(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
		}); </script>"; 
             	    	     	  
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $model->buscarModelo($_SESSION['modelo'],$_SESSION['marca']),
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid) 
							{
								return [
									'onclick' => 'CargarControles('.$model['cod'].',"'.$model['nombre'].'",'.$model['marca'].','.$model['cat'].',"'.$model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'cod','header' => 'Cod', 'contentOptions'=>['style'=>'width:30px;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'nombre','header' => 'Nombre' ,'contentOptions'=>['style'=>'width:250px;text-align:left;', 'class' => 'grilla']],           		
            		['attribute'=>'marca','header' => 'Marca' ,'value' => function($data) { return (utb::getCampo('rodado_marca','cod='.$data['marca'])); } ,
            		'contentOptions'=>['style'=>'width:250px;text-align:left;', 'class' => 'grilla']],  
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:35px;text-align:center;','class'=>'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxRodadoModelo('.$model['cod'].',"'.$model['nombre'].'",'.$model['marca'].','.$model['cat'].',"'.$model['modif'].'");'
													]	              			 			 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxRodadoModelo('.$model['cod'].',"'.$model['nombre'].'",'.$model['marca'].','.$model['cat'].',"'.$model['modif'].'");'
													]
            									);
		            						}
						    			]
						    	   ],					    	   
								],
							]); 	    
		Pjax::end(); // fin bloque de la grilla	

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmRodadoModelo']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr height='35px'>
			<td width='57px'><label>Codigo: </label></td>
			<td>
				<?= Html::input('text', 'txCod', $cod, ['class' => 'form-control','id'=>'txCod','maxlength'=> '10','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
			<td width='70px' align='center'><label>Nombre: </label></td>		
			<td>
			   <?= Html::input('text', 'txNombre',$nombre,['id' => 'txNombre','class' => 'form-control','maxlength'=>'50' ,'style' => 'width:408px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr height='35px'>
			<td width='57px'><label>Marca: </label></td>
			<td>	
				<?= Html::input('text', 'marca', $marca,['class' => 'form-control','id' => 'marca','onchange' => '$.pjax.reload({
																												container:"#cargarNombreMarca", 
																												data:{
																													idMarca:this.value
																													},
																												method:"POST"
																												});','maxlength'=>'50','style' => 'width:50px;', 'disabled' => true ]); ?>																																												
			</td>																				   		
		<td align='left'>		
			
	 <!-- ---------------------------------------- boton de búsqueda modal ------------------------------------ -->
			
			<?php
			Modal::begin([
                'id' => 'BuscarAuxModelo',
				'toggleButton' => [
					'id' => 'botonBuscarModelo',
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar',
                    'style' => 'float:left;margin-left:1px;margin-top:1px;',
                    'disabled' => true
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right'
                ]
                 ]);                         
                                
            echo $this->render('//taux/auxbusca', [	'tabla' => 'rodado_marca',
            										'campocod'=>'cod',
            										'camponombre' => 'nombre', 
            										'boton_id'=>'BuscarAuxModelo',
            										'idcampocod'=>'marca',
            										'idcamponombre'=>'nombre_marca'
            									]);

			Modal::end();
            ?>
         
            <!-- ---------------------------- fin de boton de búsqueda modal ------------------------------- -->
			</td>
			<td>
			   <?= Html::input('text', 'nombre_marca',"",['id' => 'nombre_marca','class' => 'form-control' ,'style' => 'width:441px;background-color:#E6E6FA;', 'disabled' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr>
			<td><label>Categoria:</label></td>
			<td><?= Html::dropDownList('cat',$cat,utb::getAux('rodado_tcat','cod','nombre','0'),['id'=>'cat','class' => 'form-control', 'style' => 'width:200px;','disabled' => true]);?></td>
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
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxRodadoModelo()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxRodadoModelo()']); 
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
					$('#marca').prop('disabled',false);
					$("#cat option:first-of-type").attr("selected", "selected");
					$('#cat').prop('disabled',false);
					$("#labelModif").css('display','none');
					$("#txModif").css('display','none');
					
					$('#botonBuscarModelo').prop('disabled',false);						
					$('#buscarModelo').prop('disabled',true);							
					$('#btnBuscarModelo').css("pointer-events", "none");
					$('#btnBuscarModelo').css("opacity", 0.5);
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
					$('#marca').prop('disabled',false);
					$("#cat option:first-of-type").attr("selected", "selected");
					$('#cat').prop('disabled',false);	
					$('#txModif').val('');
					$("#labelModif").css('display','none');
					$("#txModif").css('display','none');
					
					$('#botonBuscarModelo').prop('disabled',false);					
					$('#buscarModelo').prop('disabled',true);							
					$('#btnBuscarModelo').css("pointer-events", "none");
					$('#btnBuscarModelo').css("opacity", 0.5);					
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

		//-------------------------seccion de error------------------------------------------------------------------------------
		 
		 	Pjax::begin(['id' => 'divError']);	
		 		
				if(isset($_POST['error']) and $_POST['error'] != '') {  
					echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_POST['error'] . '</ul></div>';
					}

				if(isset($error) and $error != '') {  
				echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
				}

			Pjax::end();
		 
		 //-----------------------------------------------------------------------------------------------------------------------		
		 
		 	Pjax::begin(['id' => 'cargarNombreMarca']);	
		 		if(isset($_POST['idMarca'])){
		 			
					$nombreMarca = $model->getNombreMarca($_POST['idMarca']);
					echo "<script> actualizarNombreMarca('".$nombreMarca."'); </script>";
		 		}
		 		
			Pjax::end();		
				
		 //------------------------------------------------------------------------------------------------------------------------	
		 	
    	ActiveForm::end(); 
    	
    ?>    	

</div><!-- site-auxedit -->

<script>

	function actualizarNombreMarca(dato){
        alert(dato);                                                                                        
		var elementoNombreMarca = document.getElementById('nombre_marca');
		
		elementoNombreMarca.value = dato;
	}

	function CargarControles(cod,nombre,marca,cat,modif)
	{
			event.stopPropagation();
			$("#txCod").val(cod);
			$("#txNombre").val(nombre);
			$("#marca").val(marca);
			$("#cat").val(cat);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	
	}

	function btnNuevoAuxRodadoModelo(){
		
			$('#txAccion').val(0);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
				
			$('#txCod').val('');
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val('');
			$('#txNombre').prop('disabled',false);
			$('#marca').val('');
			$('#marca').prop('disabled',false);
			$("#cat option:first-of-type").attr("selected", "selected");
			$('#cat').prop('disabled',false);	
			$('#txModif').val('');
			$("#labelModif").css('display','none');
			$("#txModif").css('display','none');
			
			$('#botonBuscarModelo').prop('disabled',false);
			$('#buscarModelo').prop('disabled',true);							
			$('#btnBuscarModelo').css("pointer-events", "none");
			$('#btnBuscarModelo').css("opacity", 0.5);		
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}
	
	function btnModificarAuxRodadoModelo(cod,nombre,marca,cat,modif){
		
			event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
			
			$('#txCod').val(cod);
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val(nombre);
			$('#txNombre').prop('disabled',false);
			$("#marca").val(marca);
			$('#marca').prop('disabled',false);
			$('#cat').val(cat);
			$('#cat').prop('disabled',false);
			$("#labelModif").css('display','none');
			$('#txModif').val('');
			$("#txModif").css('display','none');
			
			$('#botonBuscarModelo').prop('disabled',false);
			$('#buscarModelo').prop('disabled',true);							
			$('#btnBuscarModelo').css("pointer-events", "none");
			$('#btnBuscarModelo').css("opacity", 0.5);		
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}
	
	function btnEliminarAuxRodadoModelo(cod,nombre,marca,cat,modif){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$('#txCod').val(cod);
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val(nombre);
			$('#txNombre').prop('disabled',true);
			$("#marca").val(marca);
			$('#marca').prop('disabled',true);
			$('#cat').val(cat);
			$('#cat').prop('disabled',true);
			$("#labelModif").css('display','none');
			$('#txModif').val('');
			$("#txModif").css('display','none');
			
			$('#botonBuscarModelo').prop('disabled',true);
			$('#buscarModelo').prop('disabled',true);							
			$('#btnBuscarModelo').css("pointer-events", "none");
			$('#btnBuscarModelo').css("opacity", 0.5);			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}

	function btnCancelarAuxRodadoModelo(){
		
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
			$("#marca").val('');
			$('#marca').prop('disabled',true);
			$("#nombremarca").val('');
			$("#cat option:first-of-type").attr("selected", "selected");
			$('#cat').prop('disabled',true);
			$("#labelModif").css('display','none');
			$('#txModif').val('');
			$("#txModif").css('display','none');
			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
			
			$('#botonBuscarModelo').prop('disabled',true);
			$('#buscarModelo').val('');
			$('#buscarModelo').prop('disabled',false);							
			$('#btnBuscarModelo').css("pointer-events", "all");
			$('#btnBuscarModelo').css("opacity", 1);
	}


	function btGrabarClick(){
		
			err = "";
			
			if ($("#txNombre").val()=="")
			{
				err += "<li>Ingrese un Nombre</li>";
			}
			if ($("#marca").val()=="")
			{
				err += "<li>Ingrese una Marca</li>";
			}
			
			if (err == "")
			{
				$("#frmRodadoModelo").submit();										
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