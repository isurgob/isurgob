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

$title = 'Medios de Pago';
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
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxCajaMdp();']) 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    
	<!-- -------------------- Inicio comboBox que filtra grilla por tipo de objeto ---------------  -->
	
    <?php  
    	 	 
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $tabla,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid) 
							{
								return [
									'onclick' => 'CargarControles('.$model['cod'].',"'.$model['nombre'].'","'.$model['tipo'].'",'.$model['cotiza'].',"'.$model['simbolo'].'",'.$model['habilitado'].')'
								];
							},
			'columns' => [
            		['attribute'=>'cod','header' => 'Cod', 'contentOptions'=>['style'=>'width:5%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'nombre','header' => 'Nombre','contentOptions'=>['id'=>'bco_ent_nom','style'=>'width:25%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=> 'tipo', 'header' => 'Tipo' , 'value' => function($data) { return (utb::getCampo("caja_tmdp","cod='".$data['tipo']."'")); }
					,'contentOptions'=>['style'=>'width:30%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'cotiza','header' => 'Cotizacion','contentOptions'=>['style'=>'width:10%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'simbolo','header' => 'Simbolo','contentOptions'=>['style'=>'width:10%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'habilitado','header' => 'Estado','value' => function($data) { if($data['habilitado']==1){return 'Habilitado';}else if($data['habilitado']==0){return 'Deshabilitado';} }
					,'contentOptions'=>['style'=>'width:10%;text-align:left;', 'class' => 'grilla']],
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:10%;text-align:center;'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxCajaMdp('.$model['cod'].',"'.$model['nombre'].'","'.$model['tipo'].'",'.$model['cotiza'].',"'.$model['simbolo'].'",'.$model['habilitado'].');'
													]				 
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxCajaMdp('.$model['cod'].',"'.$model['nombre'].'","'.$model['tipo'].'",'.$model['cotiza'].',"'.$model['simbolo'].'",'.$model['habilitado'].');'
													]
            									);
            						}
            			]
            	   ],
            	   
        	],
    	]);     

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmCajaMdp']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr height='35px'>
			<td width='54px'><label>Codigo: </label></td>
			<td>
				<?= Html::input('text', 'txCod', $cod, ['class' => 'form-control','id'=>'txCod', 'maxlength'=> '4','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
		
		<td align='center' width='65px'>
			<label>Nombre: </label>
		</td>
			<td>
			   <?= Html::input('text', 'txNombre',$nombre,['id' => 'txNombre','maxlength'=>'20','class' => 'form-control' ,'style' => 'width:272px;', 'disabled' => true ]); ?>
			</td>
			
		</tr>
		</table>
		<table border='0'>
		<tr height='35px'>
			<td><label>Medio de Pago: </label></td>
			<td>
				<?=  Html::dropDownList('tipo',$tipo,utb::getAux('caja_tmdp','cod','nombre',0),['id'=>'tipo','class' => 'form-control', 'disabled' => true]);?>
			</td>
			<td width='130px'><label style='float:right;'>Monto de Cotizacion: </label></td>
			<td>
				<?= Html::input('text', 'cotiza', $cotiza, ['class' => 'form-control','id'=>'cotiza','maxlength'=> '13','style'=>'width:100px', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr height='35px'>
			<td><label>Simbolo: </label></td>
			<td><?= Html::input('text','simbolo', $simbolo, ['class' => 'form-control','id'=>'simbolo','maxlength'=> '3','style'=>'width:55px', 'disabled' => true]); ?></td>
			<td width='90px' align='right'><label>Habilitado: </label></td>											           
			<td><?= Html::checkbox('habilitado',$habilitado,['class' => 'form-control','id'=>'habilitado','disabled' => true]); ?></td>
		</tr>
		</table>

	        <div class="form-group" id='form_botones' style='display:none'>
	
			<?php

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxCajaMdp()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxCajaMdp()']); 
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
					
					$('#txCod').prop('readOnly',false);				
					$('#txNombre').prop('disabled',false);	
					$('#tipo').prop('disabled',false);		
					$('#cotiza').prop('disabled',false);			
					$("#simbolo").prop('disabled',false);
					$("#habilitado").prop('disabled',false);
						
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
					$('#tipo').prop('disabled',false);
					$('#cotiza').prop('disabled',false);
					$('#simbolo').prop('disabled',false);
					$('#habilitado').prop('disabled',false);
					
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
			'id' => 'MensajeInfoAUXMDP',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoAUXMDP').alert('close');}, 5000)</script>";

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

	function CargarControles(cod,nombre,tipo,cotiza,simbolo,habilitado)
	{
		event.stopPropagation();
		$("#txCod").val(cod);
		$("#txNombre").val(nombre);
		$("#tipo").val(tipo);
		$("#cotiza").val(cotiza);
		$("#simbolo").val(simbolo);
		if(habilitado==1){$('#habilitado').prop('checked',true);}else{$('#habilitado').prop('checked',false);}
	}

	function btnNuevoAuxCajaMdp(){
		
		$('#txAccion').val(0);
		$('#form_botones').css('display','block');	
		$('#form_botones_delete').css('display','none');
				
		$('#txCod').val('');
		$('#txNombre').val('');
		$('#txNombre').prop('disabled',false);
		$("#tipo option:first-of-type").attr("selected", "selected");
		$('#tipo').prop('disabled',false);
		$('#cotiza').val('');
		$('#cotiza').prop('disabled',false);
		$('#simbolo').val('');
		$('#simbolo').prop('disabled',false);
		$('#habilitado').prop("checked","");	
		$('#habilitado').prop('disabled',false);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaTablaAux').css("pointer-events", "none");
		$('#GrillaTablaAux').css("color", "#ccc");	
		$('#GrillaTablaAux Button').css("color", "#ccc");
		$('#GrillaTablaAux a').css("color", "#ccc");
		
	}
	
	function btnModificarAuxCajaMdp(cod,nombre,tipo,cotiza,simbolo,habilitado){
		
		event.stopPropagation();
		$('#txAccion').val(3);
		$('#form_botones').css('display','block');	
		$('#form_botones_delete').css('display','none');
			
		$('#txCod').val(cod);
		$('#txNombre').val(nombre);
		$('#txNombre').prop('disabled',false);
		$('#tipo').val(tipo);
		$('#tipo').prop('disabled',false);
		$('#cotiza').val(cotiza);
		$('#cotiza').prop('disabled',false);
		$('#simbolo').val(simbolo);
		$('#simbolo').prop('disabled',false);
		if(habilitado==1){$('#habilitado').attr('checked',true);}else{$('#habilitado').attr('checked',false);}
		$("#habilitado").prop('disabled',false);
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaTablaAux').css("pointer-events", "none");
		$('#GrillaTablaAux').css("color", "#ccc");	
		$('#GrillaTablaAux Button').css("color", "#ccc");
		$('#GrillaTablaAux a').css("color", "#ccc");
		
	}
	
	function btnEliminarAuxCajaMdp(cod,nombre,tipo,cotiza,simbolo,habilitado){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$("#txCod").val(cod);
			$("#txNombre").val(nombre);
			$("#tipo").val(tipo);
			$("#cotiza").val(cotiza);
			$("#simbolo").val(simbolo);
			if(habilitado==1){$('#habilitado').prop("checked","checked");}else{$('#habilitado').prop("checked","");}
			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");	
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");
		
	}

	function btnCancelarAuxCajaMdp(){
		
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
			$("#tipo option:first-of-type").attr("selected", "selected");
			$('#tipo').attr("disabled","true");
			$('#cotiza').val('');
			$('#cotiza').prop('disabled',true);
			$('#simbolo').val('');
			$('#simbolo').prop('disabled',true);
			$('#habilitado').prop("checked","");	
			$('#habilitado').prop('disabled',true);

	}

	function btGrabarClick(){
		
	err = "";
	
	if ($("#cotiza").val()=="")
	{
		err += "<li>Ingrese un Monto de Cotizacion</li>";
	}
	if (isNaN($("#cotiza").val()))
	{
		err += "<li>El campo monto de cotizacion debe ser un numero</li>";
	}
	if ($("#txNombre").val()=="")
	{
		err += "<li>Ingrese un Nombre de Caja</li>";
	}
	if ($("#simbolo").val()=="")
	{
		err += "<li>Ingrese un Simbolo o una Breve Descripcion</li>";
	}
	
	if (err == "")
	{
		$("#frmCajaMdp").submit();
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
