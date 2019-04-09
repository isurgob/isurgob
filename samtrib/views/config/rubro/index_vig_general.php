<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\ActiveForm;
use app\models\config\VigenciaGeneral;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model= isset($model) ? $model : new VigenciaGeneral();
$accion= isset($accion) ? $accion : 1;


$title = 'Vigencia General';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Rubros', 'url' => ['//config/rubro/index']];
$this->params['breadcrumbs'][] = $title;					    

$anioDesde=substr($model->perdesde,0,-3);
$anioHasta=substr($model->perhasta,0,-3);
$cuotaDesde=substr($model->perdesde,4,3);
$cuotaHasta=substr($model->perhasta,4,3);

echo "<script> $('#aniodesde').val(".$anioDesde."); </script>";
echo "<script> $('#aniohasta').val(".$anioHasta."); </script>";
echo "<script> $('#cuotadesde').val(".$cuotaDesde."); </script>";
echo "<script> $('#cuotahasta').val(".$cuotaHasta."); </script>";
?>
	<table width='100%' border='0' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
		<tr>
			<td>
		    	<h1><?= Html::encode($title) ?></h1>
		    </td>
		    <td align='right'>
		    	<?php 
		    		if (utb::getExisteProceso(3031))
		    			echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevo();']) 
		    	?>
		    </td>
	    </tr>
	</table>
	
    <div class="rubro-index">
    <?php 
    
    echo GridView::widget([
    	'id' => 'GrillaTabla',
        'dataProvider' => $dataProvider,
		'rowOptions' => function ($model,$key,$index,$grid) 
						{
							return [
								'onclick' => 'CargarControles("'.$model['nomen_id'].'",'.
																$model['pi'].','.
																$model['perdesde'].','.
																$model['perhasta'].','.
																$model['alicuota'].','.
																$model['minimo'].',"'.
																$model['modif'].'")'
																];
															},
		'options' => ['style'=>'width:100%;margin-top:10px;'],			
        'columns' => [
            
			['attribute' => 'nomen_id', 'label' => 'Tributo', 'value' => function($data) { return (utb::getCampo('rubro_tnomen',"nomen_id='".$data['nomen_id']."'")); }
			,'options' => ['style' => 'width:35%']],
			['attribute' => 'pi', 'label' => 'Parque Industrial', 'value'=>function($data){if($data['pi']==0){return 'No';}else if($data['pi']==1){return 'Si';}} 
			,'options' => ['style' => 'width:15%']],
			['attribute' => 'perdesde', 'label' => 'Desde','value'=>function($data){$anioDesde=substr($data['perdesde'],0,-3);
																					$cuotaDesde=substr($data['perdesde'],4,3);
																					return $anioDesde." / ".$cuotaDesde;}
			,'options' => ['style' => 'width:20%']],
			['attribute' => 'perhasta', 'label' => 'Hasta','value'=>function($data){$anioHasta=substr($data['perhasta'],0,-3);
																					$cuotaHasta=substr($data['perhasta'],4,3);
																					return $anioHasta." / ".$cuotaHasta;}
			,'options' => ['style' => 'width:20%']],
            ['class' => 'yii\grid\ActionColumn', 'options'=>['style'=>'width:10%;'], 'template' => utb::getExisteProceso(3031) ? '{update}{delete}' : '',        
			'buttons'=>[
				'update' => function($url,$model,$key)
						{
							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
										[
											'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
											'onclick' => 'btnModificar("'.$model['nomen_id'].'",'.
																		 $model['pi'].','.
																		 $model['perdesde'].','.
																		 $model['perhasta'].','.
																		 $model['alicuota'].','.
																		 $model['minimo'].',"'.
																		 $model['modif'].'");'
										]				 
									);
						},
				'delete' => function($url,$model,$key)
						{
							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
										[
											'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
											'onclick' => 'btnEliminar("'.$model['nomen_id'].'",'.
																		$model['pi'].','.
																		$model['perdesde'].','.
																		$model['perhasta'].','.
																		$model['alicuota'].','.
																		$model['minimo'].',"'.
																		$model['modif'].'");'
																		]
																	);
															   }
														    ]
									    				],
											        ],
											    ]);												    
		?>
</div>

<div class="form" style='width:100%'>

	<?php $form = ActiveForm::begin(['action' => ['viggeneralabm'], 'fieldConfig' => ['template' => "{label}{input}"],'id'=>'frmVigGen']);
		echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 ?>	
		<table  border='0'>
			<tr>
			<td width='50px'><label>Nomeclador</label></td>
				<td>
					<?= Html::dropDownList('nomen_id',$model->nomen_id,utb::getAux('rubro_tnomen','nomen_id'),['id'=>'nomen_id','class' => 'form-control', 'style'=>'width:200px','disabled' => true]);?>
				</td>
			  <td height='35px' align='right' width='40px'>
							<?= $form->field($model, 'pi')->checkbox(['check'=> 1, 'uncheck' => 0, 'id'=>'pi','disabled'=>true]) ?>
						</td>
				<td><div><label>Parque Industrial</label></div></td>
		</table>
		<table border='0'>
			<tr>
				<td width='50px'><label>Desde:</label></td>
				<td width='83px'>
					<?= Html::input('text', 'perdesde',"",['class' => 'form-control','id'=>'aniodesde','maxlength'=> '4','style'=>'width:40px;','readonly'=>true]); ?>				  				
					<?= Html::input('text', 'cuotadesde',"", ['class' => 'form-control','id'=>'cuotadesde','maxlength'=> '10','style'=>'width:37px;float:right;margin-top:2px;margin-right:0px;','readonly'=>true]); ?>
				</td>
				<td width='55px' align='right'>
				<label>Hasta:</label></td>
				<td>
				    <?= Html::input('text', 'perhasta',"",['class' => 'form-control','id'=>'aniohasta','maxlength'=> '4','style'=>'width:40px;','readonly'=>true]); ?>					
					<?= Html::input('text', 'cuotahasta',"", ['class' => 'form-control','id'=>'cuotahasta','maxlength'=> '10','style'=>'width:37px;float:right;margin-top:2px;margin-right:50px;','readonly'=>true]); ?>
				</td>
			</tr>
			</table>
			<table border='0'>
			<tr>
			<td width='50px'>
				<label>Alicuota:</label>
			</td>
				<td width='80px'>
					<?= Html::input('text', 'alicuota',$model->alicuota, ['class' => 'form-control','id'=>'alicuota','style'=>'width:80px','disabled'=>true]); ?>
				</td>
			<td width='55px' align='right'>
				<label>Minimo:</label>
			</td>
				<td width='100px'>
					<?= Html::input('text', 'minimo',$model->minimo, ['class' => 'form-control','id'=>'minimo','style'=>'width:100px','disabled'=>true]); ?>
				</td>
			</tr>
		</table>
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

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btnGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelar()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>
	
			<?php
				echo Html::Button('Eliminar', ['class' => 'btn btn-danger','onclick'=>'btnGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelar()']); 
	    	?>
	        </div>		
		<?php
	 			
		 ActiveForm::end(); 
		 
		//------------------------------------------------Mensaje---------------------------------------------------------
		
		if(isset($_GET['mensaje']) and $_GET['mensaje'] != ''){
			
			switch ($_GET['mensaje'])
			{
					case 'grabado' : $_GET['mensaje'] = 'Datos Grabados.'; break;
					case 'delete' : $_GET['mensaje'] = 'Datos Borrados.'; break;
					default : $_GET['mensaje'] = '';
			}
			
		}
	
		if(isset($_GET['mensaje']) && $_GET['mensaje'] != ''){
		Alert::begin([
			'id' => 'MensajeInfoRUB',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		
		echo "<script>window.setTimeout(function() { $('#MensajeInfoRUB').alert('close');}, 5000)</script>";
		}
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
	?>
</div>

	<?php
	
	if($accion==0){
 	?>		
		<script>
		
			$('#form_botones').css('display','block');	
			$('#nomen_id').prop('disabled',false);
			$('#pi').prop('disabled',false);
			$('#aniodesde').prop('readOnly',false);
			$('#cuotadesde').prop('readOnly',false);
			$('#aniohasta').prop('readOnly',false);
			$('#cuotahasta').prop('readOnly',false);
			$('#alicuota').prop('disabled',false);
			$('#minimo').prop('disabled',false);
			$('#accion').val(0);
			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTabla').css("pointer-events", "none");
			$('#GrillaTabla').css("color", "#ccc");	
			$('#GrillaTabla Button').css("color", "#ccc");
			$('#GrillaTabla a').css("color", "#ccc");
		
		</script>
		
	<?php }else if($accion==2){ ?>
	
		<script>
		
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','block');
			$('#nomen_id').prop('disabled',true);	
			$('#pi').prop('disabled',true);
			$('#aniodesde').prop('readOnly',true);
			$('#cuotadesde').prop('readOnly',true);
			$('#aniohasta').prop('readOnly',true);
			$('#cuotahasta').prop('readOnly',true);
			$('#alicuota').prop('disabled',false);
			$('#minimo').prop('disabled',false);
			$('#accion').val(2);
			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTabla').css("pointer-events", "none");
			$('#GrillaTabla').css("color", "#ccc");	
			$('#GrillaTabla Button').css("color", "#ccc");
			$('#GrillaTabla a').css("color", "#ccc");
			
		</script>
			
	<?php }else if($accion==3){ ?>
		<script>
		
			$('#form_botones').css('display','block');	
			$('#nomen_id').prop('disabled',true);	
			$('#pi').prop('disabled',true);
			$('#aniodesde').prop('readOnly',true);
			$('#cuotadesde').prop('readOnly',true);
			$('#aniohasta').prop('readOnly',true);
			$('#cuotahasta').prop('readOnly',true);
			$('#alicuota').prop('disabled',true);
			$('#minimo').prop('disabled',true);
			$('#accion').val(3);
			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTabla').css("pointer-events", "none");
			$('#GrillaTabla').css("color", "#ccc");	
			$('#GrillaTabla Button').css("color", "#ccc");
			$('#GrillaTabla a').css("color", "#ccc");
			
		</script>
			
	<?php }	?>

<script>
		function CargarControles(nomen_id,pi,perdesde,perhasta,alicuota,minimo,modif){
			event.stopPropagation();
			perdesde=perdesde.toString();
			perhasta=perhasta.toString();
			
			aniodesde = perdesde.substring(0,4);
			cuotadesde = perdesde.substring(4,7);
						
			aniohasta = perhasta.substring(0,4);
			cuotahasta = perhasta.substring(4,7);
				
			$("#nomen_id").val(nomen_id);
			if(pi==1){$('#pi').prop('checked',true);}else{$('#pi').prop('checked',false);}
			$("#aniodesde").val(aniodesde);
			$("#cuotadesde").val(cuotadesde);
			$("#aniohasta").val(aniohasta);
			$("#cuotahasta").val(cuotahasta);
			$("#alicuota").val(alicuota);
			$("#minimo").val(minimo);
			
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	}
	
	function btnNuevo(){
		
		$('#txAccion').val(0);
		$('#form_botones').css('display','block');	
		$('#form_botones_delete').css('display','none');
				
		$("#nomen_id option:first-of-type").attr("selected", "selected");
		$('#nomen_id').prop('disabled',false);
		$('#pi').prop("checked","");	
		$('#pi').prop('disabled',false);
		$('#aniodesde').val('');
		$('#aniodesde').prop('readOnly',false);
		$('#cuotadesde').val('');
		$('#cuotadesde').prop('readOnly',false);
		$('#aniohasta').val('');
		$('#aniohasta').prop('readOnly',false);
		$('#cuotahasta').val('');
		$('#cuotahasta').prop('readOnly',false);
		$('#alicuota').val('');
		$('#alicuota').prop('disabled',false);
		$('#minimo').val('');
		$('#minimo').prop('disabled',false);
		
		$('#txModif').val('');
		$("#labelModif").css('display','none');
		$("#txModif").css('display','none');
		
		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaTabla').css("pointer-events", "none");
		$('#GrillaTabla').css("color", "#ccc");	
		$('#GrillaTabla Button').css("color", "#ccc");
		$('#GrillaTabla a').css("color", "#ccc");
	}
	
	function btnModificar(nomen_id,pi,perdesde,perhasta,alicuota,minimo,modif){
		
		event.stopPropagation();
		$('#txAccion').val(3);
		$('#form_botones').css('display','block');	
		$('#form_botones_delete').css('display','none');
		
		perdesde=perdesde.toString();
		perhasta=perhasta.toString();
		
		aniodesde = perdesde.substring(0,4);
		cuotadesde = perdesde.substring(4,7);
					
		aniohasta = perhasta.substring(0,4);
		cuotahasta = perhasta.substring(4,7);
		
		$('#nomen_id').val(nomen_id);
		if(pi==1){$('#pi').attr('checked',true);}else{$('#pi').attr('checked',false);}
		$("#aniodesde").val(aniodesde);
		$("#cuotadesde").val(cuotadesde);
		$("#aniohasta").val(aniohasta);
		$("#cuotahasta").val(cuotahasta);
		$("#alicuota").val(alicuota);
		$("#minimo").val(minimo);
		
		$('#nomen_id').prop('disabled',true);
		$("#pi").prop('disabled',true);			
		$('#aniodesde').prop('readOnly',true);
		$('#cuotaesde').prop('readOnly',true);
		$('#aniohasta').prop('readOnly',true);
		$('#cuotahasta').prop('readOnly',true);
		$('#alicuota').prop('disabled',false);
		$('#minimo').prop('disabled',false);
		
		$("#txModif").val(modif);
		$("#txModif").prop('display','block');
		$("#labelModif").css('display','block');

		$('#btnNuevo').css("pointer-events", "none");
		$('#btnNuevo').css("opacity", 0.5);
		$('#GrillaTabla').css("pointer-events", "none");
		$('#GrillaTabla').css("color", "#ccc");	
		$('#GrillaTabla Button').css("color", "#ccc");
		$('#GrillaTabla a').css("color", "#ccc");
		
	}
	
	function btnEliminar(nomen_id,pi,perdesde,perhasta,alicuota,minimo,modif){
			
			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
			
			perdesde=perdesde.toString();
			perhasta=perhasta.toString();
			
			aniodesde = perdesde.substring(0,4);
			cuotadesde = perdesde.substring(4,7);
						
			aniohasta = perhasta.substring(0,4);
			cuotahasta = perhasta.substring(4,7);
		
			$('#nomen_id').val(nomen_id);
			if(pi==1){$('#pi').attr('checked',true);}else{$('#pi').attr('checked',false);}
			$("#aniodesde").val(aniodesde);
			$("#cuotadesde").val(cuotadesde);
			$("#aniohasta").val(aniohasta);
			$("#cuotahasta").val(cuotahasta);
			$("#alicuota").val(alicuota);
			$("#minimo").val(minimo);
			
			$('#nomen_id').prop('disabled',true);
			$("#pi").prop('disabled',true);			
			$('#aniodesde').prop('readOnly',true);
			$('#cuotaesde').prop('readOnly',true);
			$('#aniohasta').prop('readOnly',true);
			$('#cuotahasta').prop('readOnly',true);
			$('#alicuota').prop('disabled',true);
			$('#minimo').prop('disabled',true);
			
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
			
			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTabla').css("pointer-events", "none");
			$('#GrillaTabla').css("color", "#ccc");	
			$('#GrillaTabla Button').css("color", "#ccc");
			$('#GrillaTabla a').css("color", "#ccc");
		
	}
	
	function btnCancelar(){
		
			$('.error-summary').css('display','none');
			$('#GrillaTabla').css("pointer-events", "all");
			$('#GrillaTabla').css("color", "#111111");
			$('#GrillaTabla a').css("color", "#337ab7");
			$('#GrillaTabla Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );
					
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','none');
			$("#nomen_id option:first-of-type").attr("selected", "selected");
			$('#nomen_id').attr("disabled","true");
			$('#pi').prop("checked","");	
			$('#pi').prop('disabled',true);
			$('#aniodesde').val('');
			$('#aniodesde').prop('readOnly',true);
			$('#cuotadesde').val('');
			$('#cuotadesde').prop('readOnly',true);
			$('#aniohasta').val('');
			$('#aniohasta').prop('readOnly',true);
			$('#cuotahasta').val('');
			$('#cuotahasta').prop('readOnly',true);
			$('#alicuota').val('');
			$('#alicuota').prop('disabled',true);
			$('#minimo').val('');
			$('#minimo').prop('disabled',true);
			
			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
	}
	
	function btnGrabarClick(){
			
			err = "";
			
			if ($("#aniodesde").val()=="")
			{
				err += "<li>Ingrese un año desde</li>";
			}
			if ($("#aniohasta").val()=="")
			{
				err += "<li>Ingrese un año hasta</li>";
			}
			if ($("#cuotadesde").val()=="")
			{
				err += "<li>Ingrese una cuota desde</li>";
			}
			if ($("#cuotahasta").val()=="")
			{
				err += "<li>Ingrese una cuota hasta</li>";
			}
			if ($("#alicuota").val()=="")
			{
				err += "<li>Ingrese un valor en alicuota</li>";
			}
			if ($("#minimo").val()=="")
			{
				err += "<li>Ingrese un valor en minimo</li>";
			}
			if (isNaN($("#aniodesde").val()))
			{
				err += "<li>El campo año desde debe ser un numero</li>";
			}
			if (isNaN($("#aniohasta").val()))
			{
				err += "<li>El campo año hasta debe ser un numero</li>";
			}
			if (isNaN($("#cuotadesde").val()))
			{
				err += "<li>El campo cuota desde debe ser un numero</li>";
			}
			if (isNaN($("#cuotahasta").val()))
			{
				err += "<li>El campo cuota hasta debe ser un numero</li>";
			}
			if (isNaN($("#alicuota").val()))
			{
				err += "<li>El campo alicuota desde debe ser un numero</li>";
			}
			if (isNaN($("#minimo").val()))
			{
				err += "<li>El campo minimo desde debe ser un numero</li>";
			}
			if ($("#aniodesde").val() <= 0)
			{
				err += "<li>El campo año desde debe ser mayor a cero</li>";
			}
			if ($("#aniohasta").val() <= 0)
			{
				err += "<li>El campo año hasta debe ser mayor a cero</li>";
			}
			if ($("#cuotadesde").val() < 0)
			{
				err += "<li>El campo cuota desde debe ser mayor o igual a cero</li>";
			}
			if ($("#cuotahasta").val() <= 0)
			{
				err += "<li>El campo cuota hasta debe ser mayor o igual a cero</li>";
			}
			if ($("#alicuota").val() < 0)
			{
				err += "<li>El campo alicuota debe ser mayor o igual a cero</li>";
			}
			if ($("#minimo").val() < 0)
			{
				err += "<li>El campo minimo debe ser mayor o igual a cero</li>";
			}
			if ($("#aniodesde").val() > $("#aniohasta").val())
			{
				err += "<li>año hasta debe ser mayor o igual a año desde</li>";
			}
			
			if (err == "")
			{	
				$('#nomen_id').prop('disabled',false);
				$('#pi').prop('disabled',false);
				$("#frmVigGen").submit();
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
