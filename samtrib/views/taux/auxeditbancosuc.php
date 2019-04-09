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

$title = 'Banco - Sucursal';
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
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxBancoSuc();']) 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    
	<!-- -------------------- Inicio comboBox que filtra grilla por tipo de objeto ---------------  -->
	
    <?php  $form = ActiveForm::begin(['id' => 'form-bancoSuc-filtro']); ?>
    </br> 
    <table>
    <tr>
    <td><label>Filtrar por Tipo de Entidad Bancaria</label></td>    	
	<td>	
	<?=  Html::dropDownList('selectAuxBancoSuc',"",utb::getAux('banco_entidad','bco_ent','nombre',0),['id'=>'selectAuxBancoSuc','prompt'=>'Seleccionar...',
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
    	 	$criterio = "bco_ent=".$_POST['cod'];
    	 }
    	 echo "<script> $('.summary').css('display','none'); </script>";
    	 echo "<script> $('tr.grilla th').css('font-size',12); </script>";
    	 	 
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $model->buscarEntidadBancaria($criterio),
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid) 
							{
								return [
									'onclick' => 'CargarControles('.$model['bco_ent'].','.$model['bco_suc'].',"'.$model['nombre'].'","'.$model['domi'].'","'.$model['tel'].'","'.$model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'bco_ent','header' => 'Cod', 'contentOptions'=>['style'=>'width:5%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'bco_ent_nom','header' => 'Entidad',  'value' => function($data) { return (utb::getCampo('banco_entidad','bco_ent='.$data['bco_ent'])); }
            		,'contentOptions'=>['id'=>'bco_ent_nom','style'=>'width:32%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=> 'bco_suc', 'header' => 'Sucursal', 'contentOptions'=>['style'=>'width:8%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=> 'nombre', 'header' => 'Nombre', 'contentOptions'=>['style'=>'width:32%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=> 'tel', 'header' => 'Telefono', 'contentOptions'=>['style'=>'width:16%;text-align:left;', 'class' => 'grilla']],
            		
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:7%;text-align:center;', 'class' => 'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxBancoSuc('.$model['bco_ent'].','.$model['bco_suc'].',"'.$model['nombre'].'","'.$model['domi'].'","'.$model['tel'].'","'.$model['modif'].'");'
													]				 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxBancoSuc('.$model['bco_ent'].','.$model['bco_suc'].',"'.$model['nombre'].'","'.$model['domi'].'","'.$model['tel'].'","'.$model['modif'].'");'
													]
            									);
            						}
            			]
            	   ],
            	   
        	],
    	]); 

     Pjax::end(); // fin bloque de la grilla	    

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmBancoSuc']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table>
		<tr height='35px'>
			<td width='54px'><label>Entidad: </label></td>
			<td>
				<?= Html::input('text', 'bco_ent', $bco_ent, ['class' => 'form-control','id'=>'bco_ent' ,'onchange' => '$.pjax.reload({container:"#bancoEntidadBancaria",data:{bco_ent:this.value},method:"POST"})', 'maxlength'=> '4','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
		
		<td align='left'>
		
			
	 <!------------------------------------------ boton de búsqueda modal -------------------------------------->
			
			<?php
			Modal::begin([
                'id' => 'BuscarAuxBancoEntidad',
				'toggleButton' => [
					'id' => 'botonBuscarEntidad',
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar',
                    'style' => 'float:left;margin-left:1px;margin-top:1px;'
                    
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right'
                ],
                 ]);                         
                                
            echo $this->render('//taux/auxbusca', [	'tabla' => 'banco_entidad',
            										'campocod'=>'bco_ent',
            										'camponombre' => 'nombre', 
            										'boton_id'=>'BuscarAuxBancoEntidad',
            										'idcampocod'=>'bco_ent',
            										'idcamponombre'=>'nombre_Entidad_Bancaria'
            									]);

			Modal::end();
            ?>
         
            <!------------------------------ fin de boton de búsqueda modal ------------------------------- -->
		</td>
			<td>
			   <?= Html::input('text', 'nombre_Entidad_Bancaria',"",['id' => 'nombre_Entidad_Bancaria','class' => 'form-control' ,'style' => 'width:338px;background-color:#E6E6FA;', 'readonly' => 'readonly' ]); ?>
			</td>
			
		</tr>
		</table>
		<table>
		<tr height='35px'>
			<td><label>Sucursal: </label></td>
			<td>
				<?= Html::input('text', 'bco_suc', $bco_suc, ['class' => 'form-control','id'=>'bco_suc','maxlength'=> '4','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
			<td width='65px'><label style='float:right;'>Nombre: </label></td>
			<td>
				<?= Html::input('text', 'txNombre', $nombre, ['class' => 'form-control','id'=>'txNombre','maxlength'=> '20','style'=>'width:300px', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>
		<table>
		<tr height='35px'>
			<td><label>Domicilio: </label></td>
			<td><?= Html::input('text','domi', $domi, ['class' => 'form-control','id'=>'domi','style'=>'width:200px', 'disabled' => true]); ?></td>

		</tr>
		<tr height='40px'>
			<td><label>Telefono: </label></td>
			<td><?= Html::input('text','tel', $tel, ['class' => 'form-control','id'=>'tel','style'=>'width:200px', 'disabled' => true]); ?></td>
			<td width='500px'>
				<div  id='labelModif' style='float:left;margin-left:270px;margin-top:6px;'><label>Modificación: </label></div>
				<?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModif','style'=>'width:150px;background:#E6E6FA;float:right;', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>

	        <div class="form-group" id='form_botones' style='display:none'>
	
			<?php

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxBancoSuc()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxBancoSuc()']); 
	    	?>
	        </div>
    	</div>
    	<?php 
		
		if($consulta==1){
       	 	echo '<script>$("#botonBuscarEntidad").prop("disabled",true);</script>'; 
		}else if($consulta==0){
			?>
			
				<script>
					$('#txAccion').val(0);
					$('#form_botones').css('display','block');	
					$('#form_botones_delete').css('display','none');
					$('#botonBuscarEntidad').prop('disabled',false);					
					
					$('#bco_ent').prop('readOnly',false);				
					$('#bco_suc').prop('readOnly',false);
					$('#txNombre').prop('disabled',false);	
					$('#domi').prop('disabled',false);		
					$('#tel').prop('disabled',false);			
					$("#labelModif").css('display','none');
					$("#txModif").css('display','none');
					$('#selectAuxBancoSuc').prop('disabled',true);
						
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
					$('#botonBuscarEntidad').prop('disabled',false);					

					$('#bco_ent').prop('readOnly',true);
					$('#bco_suc').prop('readOnly',true);	
					$('#txNombre').prop('disabled',false);
					$('#domi').prop('disabled',false);
					$('#tel').prop('disabled',false);
					$("#txModif").css('display','none');
					$('#selectAuxBancoSuc').prop('disabled',true);
					
					$.pjax.reload(
					{
						container:"#bancoEntidadBancaria",
						data:{
								bco_ent:bco_ent,
								consulta:3
							},
						method:"POST"
					});
					
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
			'id' => 'MensajeInfoAUXBS',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoAUXBS').alert('close');}, 5000)</script>";

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
    	
    	
    	Pjax::begin(['id' => 'bancoEntidadBancaria']);
		
		if(isset($_POST['consulta'])) $consulta=$_POST['consulta'];
		echo "<script>$('#txAccion').val(". $consulta .");</script>";
		
		if(isset($_POST['bco_ent'])){

			$valor = $model->getNombreBancoEntidad($_POST['bco_ent']);
	
			echo "<script>actualizarNombreBancoEntidad('". $valor ."');</script>";
			
		}
		
		Pjax::end();		
    	
    	//------------------------------------------------------------------------------------------------------------------------					
    	
    	ActiveForm::end(); 
    	
    ?>
    	

</div><!-- site-auxedit -->

<script>

	function actualizarNombreBancoEntidad(dato){
                                                                                                
		var elementoNombreEntidadBancaria = document.getElementById('nombre_Entidad_Bancaria');
		
		elementoNombreEntidadBancaria.value = dato;
	}
	
	function enfocar(){
		
		document.getElementGetId('bancocuenta-bco_ent').focus();
	}

function CargarControles(bco_ent,bco_suc,nombre,domi,tel,modif)
{
	event.stopPropagation();
	$("#bco_ent").val(bco_ent);
	$("#bco_suc").val(bco_suc);
	$("#txNombre").val(nombre);
	$("#domi").val(domi);
	$("#tel").val(tel);
	$("#txModif").val(modif);
	$("#txModif").prop('display','block');
	$("#labelModif").css('display','block');
	
	$.pjax.reload(
	{
		container:"#bancoEntidadBancaria",
		data:{
				bco_ent:bco_ent,
				consulta:1
			},
		method:"POST"
	});
}

	function btnNuevoAuxBancoSuc(){
		
		$('#txAccion').val(0);
		$('#form_botones').css('display','block');	
		$('#form_botones_delete').css('display','none');
		$('#botonBuscarEntidad').prop('disabled',false);
		
		$('#bco_ent').val('');
		$('#bco_ent').prop('readOnly',false);
		$('#bco_suc').val('');
		$('#bco_suc').prop('readOnly',false);
		$('#nombre_Entidad_Bancaria').val('');
		$('#txNombre').val('');
		$('#txNombre').prop('disabled',false);
		$('#domi').val('');
		$('#domi').prop('disabled',false);
		$('#tel').val('');
		$('#tel').prop('disabled',false);
		$('#txModif').val('');
		$("#labelModif").css('display','none');
		$("#txModif").css('display','none');
		$('#selectAuxBancoSuc').prop('disabled',true);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaTablaAux').css("pointer-events", "none");
		$('#GrillaTablaAux').css("color", "#ccc");	
		$('#GrillaTablaAux Button').css("color", "#ccc");
		$('#GrillaTablaAux a').css("color", "#ccc");
		
	}
	
	function btnModificarAuxBancoSuc(bco_ent,bco_suc,nombre,domi,tel,modif){
		
		event.stopPropagation();
		$('#txAccion').val(3);
		$("#labelModif").css('display','none');
		$('#form_botones').css('display','block');	
		$('#form_botones_delete').css('display','none');
		$('#botonBuscarEntidad').prop('disabled',false);
		
		$('#bco_ent').val(bco_ent);
		$('#bco_ent').prop('readOnly',true);
		$('#bco_suc').val(bco_suc);
		$('#bco_suc').prop('readOnly',true);
		$('#txNombre').val(nombre);
		$('#txNombre').prop('disabled',false);
		$('#domi').val(domi);
		$('#domi').prop('disabled',false);
		$('#tel').val(tel);
		$('#tel').prop('disabled',false);
		$('#txModif').val('');
		$("#txModif").css('display','none');
		$('#selectAuxBancoSuc').prop('disabled',true);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaTablaAux').css("pointer-events", "none");
		$('#GrillaTablaAux').css("color", "#ccc");	
		$('#GrillaTablaAux Button').css("color", "#ccc");
		$('#GrillaTablaAux a').css("color", "#ccc");
		
		$.pjax.reload(
		{
			container:"#bancoEntidadBancaria",
			data:{
					bco_ent:bco_ent,
					consulta:3
					
				},
			method:"POST"
		});
		
	}
	
	function btnEliminarAuxBancoSuc(bco_ent,bco_suc,nombre,domi,tel,modif){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$("#bco_ent").val(bco_ent);
			$("#bco_suc").val(bco_suc);
			$("#txNombre").val(nombre);
			$("#domi").val(domi);
			$("#tel").val(tel);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
			$('#selectAuxBancoSuc').prop('disabled',true);
			
			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
			$.pjax.reload(
			{
			container:"#bancoEntidadBancaria",
			data:{
					bco_ent:bco_ent,
					consulta:2
					
				},
			method:"POST"
			});
		
	}

	function btnCancelarAuxBancoSuc(){
		
		$('.error-summary').css('display','none');
		$('#GrillaTablaAux').css("pointer-events", "all");
		$('#GrillaTablaAux').css("color", "#111111");
		$('#GrillaTablaAux a').css("color", "#337ab7");
		$('#GrillaTablaAux Button').css("color", "#337ab7");
		$('#btnNuevo').css("pointer-events", "all");
		$('#btnNuevo').css("opacity", 1 );
		$('#botonBuscarEntidad').prop('disabled',true);
		
		$('#form_botones').css('display','none');
		$('#form_botones_delete').css('display','none');
		$('#botonBuscarEntidad').prop('disabled',true);
		$('#bco_ent').val('');
		$('#bco_ent').prop('readOnly',true);
		$('#bco_suc').val('');
		$('#bco_suc').prop('readOnly',true);
		$('#nombre_Entidad_Bancaria').val('');
		$('#txNombre').val('');
		$('#txNombre').prop('disabled',true);
		$('#domi').val('');
		$('#domi').prop('disabled',true);
		$('#tel').val('');
		$('#tel').prop('disabled',true);
		$('#txModif').val('');
		$('#txModif').prop('disabled',true);
		$('#txModif').css('display','block');
		$("#labelModif").css('display','block');
		$('#selectAuxBancoSuc').prop('disabled',false);
		
		$('#bco_ent').removeClass('has-error');
		$('#bco_ent').removeClass('has-success');
		$('#bco_suc').removeClass('has-error');
		$('#bco_suc').removeClass('has-success');
		$('#txNombre').removeClass('has-error');
		$('#txNombre').removeClass('has-success');
		$('#domi').removeClass('has-error');
		$('#domi').removeClass('has-success');
		$('#tel').removeClass('has-error');
		$('#tel').removeClass('has-success');
	}


	function btGrabarClick(){
		
	err = "";
	
	if ($("#bco_suc").val()=="")
	{
		err += "<li>Ingrese un Código de Sucursal</li>";
	}
	if (isNaN($("#bco_ent").val()))
	{
		err += "<li>El campo codigo de entidad debe ser un numero</li>";
	}
	if (isNaN($("#bco_suc").val()))
	{
		err += "<li>El campo codigo de sucursal debe ser un numero</li>";
	}
	if ($("#txNombre").val()=="")
	{
		err += "<li>Ingrese un Nombre de Sucursal</li>";
	}
	if ($("#nombre_Entidad_Bancaria").val()=="")
	{
		err += "<li>Ingrese una Entidad Bancaria</li>";
	}
	
	if (err == "")
	{
		$("#frmBancoSuc").submit();
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

	$('#bco_ent').onchange(function(){
		
		
	})

	$('ul.pagination li a').click(function(){		
		var paginacion = $(this);
		paginacion.attr('href','http://server/samtest/index.php?r=site/auxedit&t=80&mensaje=&page='+paginacion.html());					
	})
		

 $('.summary').css('display','none');
 $('tr.grilla th').css('font-size',12);

</script>
