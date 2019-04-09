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

$title = 'Obras Part. - Profesionales';
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
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxObrasPrivProf();']) 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    
<table width='100%' border='0'>
  <tr>  
    <td valign='top'>
	<?php    	 	 
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $tabla,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid) 
							{
								return [
									'onclick' => 'CargarControles('.$model['prof_id'].',"'.
																	$model['nombre'].'",'.
																	$model['ttitu1'].',"'.
																	$model['ttitu1_matric'].'","'.
																	$model['ttitu1_facu'].'",'.
																	$model['ttitu2'].',"'.
																	$model['ttitu2_matric'].'","'.
																	$model['ttitu2_facu'].'","'.
																	$model['num'].'",'.
																	$model['tdoc'].','.
																	$model['ndoc'].',"'.
																	$model['dom_part'].'","'.
																	$model['tel_part'].'","'.
																	$model['dom_prof'].'","'.
																	$model['tel_prof'].'","'.
																	$model['mail'].'","'.																			
																	$model['carnet_gestor'].'",'.
																	$model['matric_muni'].',"'.
																	$model['cuit'].'","'.
																	$model['expe'].'","'.
																	$model['es_cons'].'","'.
																	$model['es_empre'].'","'.
																	$model['contacto'].'","'.
																	$model['est'].'","'.														
																	$model['obs'].'","'.
																	$model['fchbaja'].'","'.
																	$model['motivo_baja'].'","'.
																	$model['fchultpago'].'",'.
																	$model['anioultpago'].',"'.
																	$model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'prof_id','header' => 'Cod', 'contentOptions'=>['style'=>'width:10%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'nombre','header' => 'Nombre' ,'contentOptions'=>['style'=>'width:70%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'est','header' => 'Estado' ,'contentOptions'=>['style'=>'width:9%;text-align:left;', 'class' => 'grilla']], 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:11%;text-align:center;','class'=>'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxObrasPrivProf('.$model['prof_id'].',"'.
																									 $model['nombre'].'",'.
																									 $model['ttitu1'].',"'.
																									 $model['ttitu1_matric'].'","'.
																									 $model['ttitu1_facu'].'",'.
																									 $model['ttitu2'].',"'.
																									 $model['ttitu2_matric'].'","'.
																									 $model['ttitu2_facu'].'","'.
																									 $model['num'].'",'.
																									 $model['tdoc'].','.
																									 $model['ndoc'].',"'.
																									 $model['dom_part'].'","'.
																									 $model['tel_part'].'","'.
																									 $model['dom_prof'].'","'.
																									 $model['tel_prof'].'","'.
																									 $model['mail'].'","'.																			
																									 $model['carnet_gestor'].'",'.
																									 $model['matric_muni'].',"'.
																									 $model['cuit'].'","'.
																									 $model['expe'].'","'.
																									 $model['es_cons'].'","'.
																									 $model['es_empre'].'","'.
																									 $model['contacto'].'","'.
																									 $model['est'].'","'.														
																									 $model['obs'].'","'.
																									 $model['fchbaja'].'","'.
																									 $model['motivo_baja'].'","'.
																									 $model['fchultpago'].'",'.
																									 $model['anioultpago'].',"'.
																									 $model['modif'].'");'
																								]				 			 
									            									);
									            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														 'onclick' => 'btnEliminarAuxObrasPrivProf('.$model['prof_id'].',"'.
																									 $model['nombre'].'",'.
																									 $model['ttitu1'].',"'.
																									 $model['ttitu1_matric'].'","'.
																									 $model['ttitu1_facu'].'",'.
																									 $model['ttitu2'].',"'.
																									 $model['ttitu2_matric'].'","'.
																									 $model['ttitu2_facu'].'","'.
																									 $model['num'].'",'.
																									 $model['tdoc'].','.
																									 $model['ndoc'].',"'.
																									 $model['dom_part'].'","'.
																									 $model['tel_part'].'","'.
																									 $model['dom_prof'].'","'.
																									 $model['tel_prof'].'","'.
																									 $model['mail'].'","'.																			
																									 $model['carnet_gestor'].'",'.
																									 $model['matric_muni'].',"'.
																									 $model['cuit'].'","'.
																									 $model['expe'].'","'.
																									 $model['es_cons'].'","'.
																									 $model['es_empre'].'","'.
																									 $model['contacto'].'","'.
																									 $model['est'].'","'.														
																									 $model['obs'].'","'.
																									 $model['fchbaja'].'","'.
																									 $model['motivo_baja'].'","'.
																									 $model['fchultpago'].'",'.
																									 $model['anioultpago'].',"'.
																									 $model['modif'].'");'
																						]
									            									);
									            						}
									            			]
									            	   ],            	   
									        	],
									    	]); 	
	?>			
					    	    
		</td>
		<td width='63%'>
	<?php
	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmObrasPrivProf']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);
	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px; margin-top:15px;margin-left:5px;'>
		<table border='0'>
		<tr height='33px'>   
			<td><label>Codigo: </label></td>
			<td><?= Html::input('text', 'txCod', $cod, ['class' => 'form-control','id'=>'txCod','maxlength'=> '10','style'=>'width:65px;', 'readonly' => true]); ?></td>
			<td width='100px' align='right'><label>Contribuyente:</label></td>
				<td>
				   <?= Html::input('text', 'num',$num,['id' => 'num','class' => 'form-control', 'onchange' => '$.pjax.reload({container:"#cargarNomContrib",data:{id_contrib:this.value},method:"POST"})','style' => 'width:140px', 'maxlength' => '20','disabled' => true ]); ?>
				</td>
			<td align='left'>
				 <!------------------------------------------ boton de búsqueda modal -------------------------------------->
				
				<?php
				Modal::begin([
	                'id' => 'BuscarAuxContrib',
					'toggleButton' => [
						'id' => 'botonBuscarContrib',
	                    'label' => '<i class="glyphicon glyphicon-search"></i>',
	                    'class' => 'bt-buscar',
	                    'style' => 'float:left;margin-left:1px;margin-top:1px;'
	                    
	                ],
	                'closeButton' => [
	                  'label' => '<b>X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right'
	                ],
	                 ]);                         
	                                
	            echo $this->render('//taux/auxbusca', [	'tabla' => 'objeto',
	            										'campocod'=>'num',
	            										'camponombre' => 'nombre', 
	            										'boton_id'=>'BuscarAuxBancoEntidad',
	            										'idcampocod'=>'num'
	            									  ]);
	
				Modal::end();
	            ?>
	         
	            <!------------------------------ fin de boton de búsqueda modal ------------------------------- -->
			</td>
			<td  width='60px' align='right'><label>Estado: </label></td>			
			<td><?= Html::input('text', 'est',$est,['id' => 'est','class' => 'form-control' ,'style' => 'width:25px;', 'maxlength' => '1','disabled' => true ]); ?></td>
		</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>			
				<td width='115px'><label>Tipo de Documento:</label></td>
				<td ><?= Html::dropDownList('tdoc',$tdoc,utb::getAux('persona_tdoc','cod','nombre','0'),['id'=>'tdoc','class' => 'form-control', 'style' => 'width:150px','disabled' => true]);?></td>
				<td width='99px' align='right'><label>Nº Documento:</label></td>
				<td><?= Html::input('text', 'ndoc',$ndoc,['id' => 'ndoc','class' => 'form-control' ,'style' => 'width:100px;', 'maxlength' => '20','disabled' => true ]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td><label>Nombre:</label></td>
				<td><?= Html::input('text', 'txNombre',$nombre,['id' => 'txNombre','class' => 'form-control' ,'style' => 'width:418px;', 'maxlength' => '50','disabled' => true ]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td><label>Domicilio Particular:</label></td>
				<td><?= Html::input('text', 'dom_part',$dom_part,['id' => 'dom_part','class' => 'form-control' ,'style' => 'width:343px;', 'maxlength' => '80','disabled' => true ]); ?></td>
			</tr>
			<tr height='33px'>
				<td><label>Telefono Particular:</label></td>
				<td><?= Html::input('text', 'tel_part',$tel_part,['id' => 'tel_part','class' => 'form-control' ,'style' => 'width:343px;', 'maxlength' => '25','disabled' => true ]); ?></td>
			</tr>
			<tr height='33px'>
				<td><label>Domicilio Profesional:</label></td>
				<td><?= Html::input('text', 'dom_prof',$dom_prof,['id' => 'dom_prof','class' => 'form-control' ,'style' => 'width:343px;', 'maxlength' => '80','disabled' => true ]); ?></td>
			</tr>
			<tr height='33px'>
				<td><label>Telefono Profesional:</label></td>
				<td><?= Html::input('text', 'tel_prof',$tel_prof,['id' => 'tel_prof','class' => 'form-control' ,'style' => 'width:343px;', 'maxlength' => '25','disabled' => true ]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td width='50px'><label>Titulo 1:</label></td>
				<td><?= Html::dropDownList('ttitu1',$ttitu1,utb::getAux('op_tprofesion','cod','nombre','0'),['id'=>'ttitu1','style' => 'width:177px;','class' => 'form-control', 'disabled' => true]);?></td>
				<td width='65px' align='right'><label>Titulo 2:</label></td>
				<td><?= Html::dropDownList('ttitu2',$ttitu2,utb::getAux('op_tprofesion','cod','nombre','0'),['id'=>'ttitu2','style' => 'width:172px;','class' => 'form-control', 'disabled' => true]);?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td width='70px'><label>Matricula 1:</label></td>
				<td><?= Html::input('text', 'ttitu1_matric',$ttitu1_matric,['id' => 'ttitu1_matric','class' => 'form-control' ,'style' => 'width:157px;', 'maxlength' => '10','disabled' => true ]); ?></td>
				<td width='85px' align='right'><label>Matricula 2:</label></td>
				<td><?= Html::input('text', 'ttitu2_matric',$ttitu2_matric,['id' => 'ttitu2_matric','class' => 'form-control' ,'style' => 'width:152px;', 'maxlength' => '10','disabled' => true ]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td width='70px'><label>Facultad 1:</label></td>
				<td><?= Html::input('text', 'ttitu1_facu',$ttitu1_facu,['id' => 'ttitu1_facu','class' => 'form-control' ,'style' => 'width:157px;', 'maxlength' => '20','disabled' => true ]); ?></td>
				<td width='80px' align='right'><label>Facultad 2:</label></td>
				<td><?= Html::input('text', 'ttitu2_facu',$ttitu2_facu,['id' => 'ttitu2_facu','class' => 'form-control' ,'style' => 'width:157px;', 'maxlength' => '20','disabled' => true ]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td><label>e-Mail:</label></td>
				<td><?= Html::input('text', 'mail',$mail,['id' => 'mail','class' => 'form-control' ,'style' => 'width:429px;', 'maxlength' => '50','disabled' => true ]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td width='85px'><label>Carnet Gestor:</label></td>
				<td><?= Html::input('text', 'carnet_gestor',$carnet_gestor,['id' => 'carnet_gestor','class' => 'form-control' ,'style' => 'width:150px;', 'maxlength' => '10','disabled' => true ]); ?></td>
				<td width='120px' align='right'><label>Matricula Municipal:</label></td>
				<td><?= Html::input('text', 'matric_muni',$matric_muni,['id' => 'matric_muni','class' => 'form-control' ,'style' => 'width:109px;', 'maxlength' => '20','disabled' => true ]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td width='65px'><label>Expediente:</label></td>
				<td><?= Html::input('text', 'expe',$expe,['id' => 'expe','class' => 'form-control' ,'style' => 'width:156px;', 'maxlength' => '12','disabled' => true ]); ?></td>
				<td width='55px' align='right'><label>C.U.I.T.:</label></td>
				<td><?= Html::input('text', 'cuit',$cuit,['id' => 'cuit','class' => 'form-control' ,'style' => 'width:186px;', 'maxlength' => '11','disabled' => true ]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td>
				<div class="form">
					<table border='0' style='margin-left:-10px;'>
						<tr>
							<td width='75px'><label>Constructor:</label></td>
							<td><?= Html::checkbox('es_cons',$es_cons,['class' => 'form-control','id'=>'es_cons','disabled' => true]); ?></td>
							<td width='80px' align='right'><label>Empresa:</label></td>
							<td><?= Html::checkbox('es_empre',$es_empre,['class' => 'form-control','id'=>'es_empre','disabled' => true]); ?></td>
						</tr>
					</table>
				</div>
				</td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td align='right'><label>Contacto:</label></td>
				<td><?= Html::input('text', 'contacto',$contacto,['id' => 'contacto','class' => 'form-control' ,'style' => 'width:412px;', 'maxlength' => '50','disabled' => true ]); ?></td>
			</tr>
		</table>
		
		<table border='0' width='100%'>
			<tr height='33px'>
			<td><label>Observaciones:</label></td>
			</tr>
			<tr>																						
				<td><?= Html::textarea('obs',$obs,['id' => 'obs','class' => 'form-control' ,'rows'=>'6','style'=>'width:98%;max-width:465px;max-height:85px;','maxlength' => '225','disabled'=>true]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td><label>Fecha de Baja:</label></td>
				<td><?php 
					echo DatePicker::widget([
								    'value' => $fchbaja,
								    'name' => 'fchbaja',
								    'dateFormat' => 'dd/MM/yyyy',
								    'options' => ['class'=>'form-control', 'disabled' => true, 'id' => 'fchbaja','style' => 'width:100px;']
								]);
				 ?></td>
				<td width='90px' align='right'><label>Ultimo Pago:</label></td>
				<td><?php
				echo DatePicker::widget([
								    'value' => $fchultpago,
								    'name' => 'fchultpago',
								    'dateFormat' => 'dd/MM/yyyy',
								    'options' => ['class'=>'form-control', 'disabled' => true, 'id' => 'fchultpago','style' => 'width:100px;']
								]);
				 ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td align='right'><label>Año Ultimo Pago:</label></td>
				<td><?= Html::input('text', 'anioultpago',$anioultpago,['id' => 'anioultpago','class' => 'form-control' ,'style' => 'width:85px;', 'maxlengh' => '50','disabled' => true ]); ?></td>		
			</tr>
		</table>
		
		<table border='0' width='100%'>
			<tr height='33px'>
				<td><label>Motivo de Baja:</label></td>
			</tr>
			<tr>
				<td><?= Html::textarea('motivo_baja',$motivo_baja,['id' => 'motivo_baja','class' => 'form-control' ,'rows'=>'6','style'=>'width:98%;max-width:465px;max-height:85px;','maxlength' => '100','disabled'=>true]); ?></td>
			</tr>
		</table>
		
		<table border='0'>
			<tr height='33px'>
				<td id='labelModif' align='right' width='320px'><label>Modificacion:</label></td>
				<td><?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModif','style'=>'width:150px;background:#E6E6FA;float:right;', 'disabled' => true]); ?></td>
			</tr>
		</table>

	        <div class="form-group" id='form_botones' style='display:none;margin-top:-20px;'>
	
			<?php

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxObrasPrivProf()']); 
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none;margin-top:-20px;'>
	
			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxObrasPrivProf()']); 
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
					$('#ttitu1').prop('disabled',false);
					$('#ttitu1_matric').prop('disabled',false);
					$('#ttitu1_facu').prop('disabled',false);
					$('#ttitu2').prop('disabled',false);
					$('#ttitu2_matric').prop('disabled',false);
					$('#ttitu2_facu').prop('disabled',false);
					$('#num').prop('disabled',false);
					$('#botonBuscarContrib').prop('disabled',false);
					$("#tdoc").prop('disabled',false);
					$('#ndoc').prop('disabled',false);
					$('#dom_part').prop('disabled',false);
					$('#tel_part').prop('disabled',false);
					$('#dom_prof').prop('disabled',false);
					$('#tel_prof').prop('disabled',false);
					$("#mail").prop('disabled',false);
					$('#carnet_gestor').prop('disabled',false);
					$('#matric_muni').prop('disabled',false);
					$("#cuit").prop('disabled',false);
					$('#expe').prop('disabled',false);
					$(':checkbox').prop('disabled',false);
					$('#contacto').prop('disabled',false);
					$('#est').prop('disabled',true);
					$('#obs').prop('disabled',false);
					$('#fchbaja').prop('disabled',false);
					$("#motivo_baja").prop('disabled',false);
					$('#fchultpago').prop('disabled',false);
					$('#anioultpago').prop('disabled',false);
											
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
					$('#ttitu1').prop('disabled',false);
					$('#ttitu1_matric').prop('disabled',false);
					$('#ttitu1_facu').prop('disabled',false);
					$('#ttitu2').prop('disabled',false);
					$('#ttitu2_matric').prop('disabled',false);
					$('#ttitu2_facu').prop('disabled',false);
					$('#num').prop('disabled',false);
					$('#botonBuscarContrib').prop('disabled',false);
					$("#tdoc").prop('disabled',false);
					$('#ndoc').prop('disabled',false);
					$('#dom_part').prop('disabled',false);
					$('#tel_part').prop('disabled',false);
					$('#dom_prof').prop('disabled',false);
					$('#tel_prof').prop('disabled',false);
					$("#mail").prop('disabled',false);
					$('#carnet_gestor').prop('disabled',false);
					$('#matric_muni').prop('disabled',false);
					$("#cuit").prop('disabled',false);
					$('#expe').prop('disabled',false);
					$(':checkbox').prop('disabled',false);
					$('#contacto').prop('disabled',false);
					$('#est').prop('disabled',true);
					$('#obs').prop('disabled',false);
					$('#fchbaja').prop('disabled',false);
					$("#motivo_baja").prop('disabled',false);
					$('#fchultpago').prop('disabled',false);
					$('#anioultpago').prop('disabled',false);
										
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
			'id' => 'MensajeInfoTP',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];
		
		Alert::end();
		
		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoTP').alert('close');}, 5000)</script>";

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
    </td> 
  </tr>     	
</table>
</div><!-- site-auxedit -->

<script>

   function CargarControles(prof_id,nombre,ttitu1,ttitu1_matric,ttitu1_facu,ttitu2,ttitu2_matric,ttitu2_facu,num,tdoc,ndoc,dom_part,tel_part,
   dom_prof,tel_prof,mail,carnet_gestor,matric_muni,cuit,expe,es_cons,es_empre,contacto,est,obs,fchbaja,motivo_baja,fchultpago,anioultpago,modif)
			{
				
			event.stopPropagation();
			
		    var baja = new Date(fchbaja);
		    if(baja.getDate()){ 
				if(baja.getDate() < 10) {var dia = "0" +  baja.getDate();}else{var dia = baja.getDate();}
				if(baja.getMonth() < 10) {var mes = "0" + (baja.getMonth()+1);}else{var mes = (baja.getMonth()+1);}
				fchbaja =  dia + "-" + mes + "-" + baja.getFullYear();
			}else{
				fchbaja='';	
			}
			
			var ultpago = new Date(fchultpago);
			if(ultpago.getDate()){ 
				if(ultpago.getDate() < 10){ var dia2 = "0" +  ultpago.getDate();}else{var dia2 = ultpago.getDate();}
				if(ultpago.getMonth() < 10){ var mes2 = "0" + (ultpago.getMonth()+1);}else{var mes2 = (ultpago.getMonth()+1);}
				fchultpago =  dia2 + "-" + mes2 + "-" + ultpago.getFullYear();
			}else{
				fchultpago='';	
			}
			$('#txAccion').val(1);
			$("#txCod").val(prof_id);
			$("#txNombre").val(nombre);
			$("#ttitu1").val(ttitu1);
			$("#ttitu1_matric").val(ttitu1_matric);
			$("#ttitu1_facu").val(ttitu1_facu);
			$("#ttitu2").val(ttitu2);
			$("#ttitu2_matric").val(ttitu2_matric);
			$("#ttitu2_facu").val(ttitu2_facu);
			$("#num").val(num);
			$('#botonBuscarContrib').prop('disabled',true);
			$("#tdoc").val(tdoc);
			$("#ndoc").val(ndoc);
			$("#dom_part").val(dom_part);
			$("#tel_part").val(tel_part);
			$("#dom_prof").val(dom_prof);
			$("#tel_prof").val(tel_prof);
			$("#mail").val(mail);
			$("#carnet_gestor").val(carnet_gestor);
			$("#matric_muni").val(matric_muni);
			$("#cuit").val(cuit);
			$("#expe").val(expe);
			if(es_cons==1){$('#es_cons').prop('checked',true);}else{$('#es_cons').prop('checked',false);}
			if(es_empre==1){$('#es_empre').prop('checked',true);}else{$('#es_empre').prop('checked',false);}
			$("#contacto").val(contacto);
			$("#est").val(est);
			$("#obs").val(obs);
			$("#fchbaja").val(fchbaja);
			$("#motivo_baja").val(motivo_baja);
			$("#fchultpago").val(fchultpago);
			$("#anioultpago").val(anioultpago);

			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
			
			
	}

	function btnNuevoAuxObrasPrivProf(){
		
			$('#txAccion').val(0);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
				
			$("#txCod").val('');
			$("#txNombre").val('');
			$("#ttitu1 option:first-of-type").attr("selected", "selected");
			$("#ttitu1_matric").val('');
			$("#ttitu1_facu").val('');
			$("#ttitu2 option:first-of-type").attr("selected", "selected");
			$("#ttitu2_matric").val('');
			$("#ttitu2_facu").val('');
			$("#num").val('');
			$("#tdoc option:first-of-type").attr("selected", "selected");
			$("#ndoc").val('');
			$("#dom_part").val('');
			$("#tel_part").val('');
			$("#dom_prof").val('');
			$("#tel_prof").val('');
			$("#mail").val('');
			$("#carnet_gestor").val('');
			$("#matric_muni").val('');
			$("#cuit").val('');
			$("#expe").val('');
			$(':checkbox').prop('disabled',false);
			$(':checkbox').prop("checked","");
			$("#contacto").val('');
			$("#est").val('');
			$("#obs").val('');
			$("#fchbaja").val('');
			$("#motivo_baja").val('');
			$("#fchultpago").val('');
			$("#anioultpago").val('');	
			
			$('#txCod').prop('readOnly',true);
			$('#txNombre').prop('disabled',false);
			$('#ttitu1').prop('disabled',false);
			$('#ttitu1_matric').prop('disabled',false);
			$('#ttitu1_facu').prop('disabled',false);
			$('#ttitu2').prop('disabled',false);
			$('#ttitu2_matric').prop('disabled',false);
			$('#ttitu2_facu').prop('disabled',false);
			$('#num').prop('disabled',false);
			$('#botonBuscarContrib').prop('disabled',false);
			$('#tdoc').prop('disabled',false);
			$('#ndoc').prop('disabled',false);
			$('#dom_part').prop('disabled',false);
			$('#tel_part').prop('disabled',false);
			$('#dom_prof').prop('disabled',false);
			$('#tel_prof').prop('disabled',false);
			$("#mail").prop('disabled',false);
			$('#carnet_gestor').prop('disabled',false);
			$('#matric_muni').prop('disabled',false);
			$("#cuit").prop('disabled',false);
			$('#expe').prop('disabled',false);
			$('#contacto').prop('disabled',false);
			$('#est').prop('disabled',true);
			$('#obs').prop('disabled',false);
			$('#fchbaja').prop('disabled',false);
			$("#motivo_baja").prop('disabled',false);
			$('#fchultpago').prop('disabled',false);
			$('#anioultpago').prop('disabled',false);
				
			
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
	
  function btnModificarAuxObrasPrivProf(prof_id,nombre,ttitu1,ttitu1_matric,ttitu1_facu,ttitu2,ttitu2_matric,ttitu2_facu,num,tdoc,ndoc,dom_part,tel_part,
   dom_prof,tel_prof,mail,carnet_gestor,matric_muni,cuit,expe,es_cons,es_empre,contacto,est,obs,fchbaja,motivo_baja,fchultpago,anioultpago,modif){
		
			event.stopPropagation();
			
			var baja = new Date(fchbaja);
		    if(baja.getDate()){ 
				if(baja.getDate() < 10) {var dia = "0" +  baja.getDate();}else{var dia = baja.getDate();}
				if(baja.getMonth() < 10) {var mes = "0" + (baja.getMonth()+1);}else{var mes = (baja.getMonth()+1);}
				fchbaja =  dia + "-" + mes + "-" + baja.getFullYear();
			}else{
				fchbaja='';	
			}
			
			var ultpago = new Date(fchultpago);
			if(ultpago.getDate()){ 
				if(ultpago.getDate() < 10){ var dia2 = "0" +  ultpago.getDate();}else{var dia2 = ultpago.getDate();}
				if(ultpago.getMonth() < 10){ var mes2 = "0" + (ultpago.getMonth()+1);}else{var mes2 = (ultpago.getMonth()+1);}
				fchultpago =  dia2 + "-" + mes2 + "-" + ultpago.getFullYear();
			}else{
				fchultpago='';	
			}
			
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');	
			$('#form_botones_delete').css('display','none');
			
			$("#txCod").val(prof_id);
			$("#txNombre").val(nombre);
			$("#ttitu1").val(ttitu1);
			$("#ttitu1_matric").val(ttitu1_matric);
			$("#ttitu1_facu").val(ttitu1_facu);
			$("#ttitu2").val(ttitu2);
			$("#ttitu2_matric").val(ttitu2_matric);
			$("#ttitu2_facu").val(ttitu2_facu);
			$("#num").val(num);
			$("#tdoc").val(tdoc);
			$("#ndoc").val(ndoc);
			$("#dom_part").val(dom_part);
			$("#tel_part").val(tel_part);
			$("#dom_prof").val(dom_prof);
			$("#tel_prof").val(tel_prof);
			$("#mail").val(mail);
			$("#carnet_gestor").val(carnet_gestor);
			$("#matric_muni").val(matric_muni);
			$("#cuit").val(cuit);
			$("#expe").val(expe);
			$(':checkbox').prop('disabled',false);
			if(es_cons==1){$('#es_cons').prop('checked',true);}else{$('#es_cons').prop('checked',false);}
			if(es_empre==1){$('#es_empre').prop('checked',true);}else{$('#es_empre').prop('checked',false);}
			$("#contacto").val(contacto);
			$("#est").val(est);
			$("#obs").val(obs);
			$("#fchbaja").val(fchbaja);
			$("#motivo_baja").val(motivo_baja);
			$("#fchultpago").val(fchultpago);
			$("#anioultpago").val(anioultpago);
			
			$('#txCod').prop('readOnly',true);
			$('#txNombre').prop('disabled',false);
			$('#ttitu1').prop('disabled',false);
			$('#ttitu1_matric').prop('disabled',false);
			$('#ttitu1_facu').prop('disabled',false);
			$('#ttitu2').prop('disabled',false);
			$('#ttitu2_matric').prop('disabled',false);
			$('#ttitu2_facu').prop('disabled',false);
			$('#num').prop('disabled',false);
			$('#botonBuscarContrib').prop('disabled',false);
			$("#tdoc").prop('disabled',false);
			$('#ndoc').prop('disabled',false);
			$('#dom_part').prop('disabled',false);
			$('#tel_part').prop('disabled',false);
			$('#dom_prof').prop('disabled',false);
			$('#tel_prof').prop('disabled',false);
			$("#mail").prop('disabled',false);
			$('#carnet_gestor').prop('disabled',false);
			$('#matric_muni').prop('disabled',false);
			$("#cuit").prop('disabled',false);
			$('#expe').prop('disabled',false);
			$('#contacto').prop('disabled',false);
			$('#est').prop('disabled',true);
			$('#obs').prop('disabled',false);
			$('#fchbaja').prop('disabled',false);
			$("#motivo_baja").prop('disabled',false);
			$('#fchultpago').prop('disabled',false);
			$('#anioultpago').prop('disabled',false);	
					
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
	
	function btnEliminarAuxObrasPrivProf(prof_id,nombre,ttitu1,ttitu1_matric,ttitu1_facu,ttitu2,ttitu2_matric,ttitu2_facu,num,tdoc,ndoc,dom_part,tel_part,
    dom_prof,tel_prof,mail,carnet_gestor,matric_muni,cuit,expe,es_cons,es_empre,contacto,est,obs,fchbaja,motivo_baja,fchultpago,anioultpago,modif){
			
			event.stopPropagation();
			
			var baja = new Date(fchbaja);
		    if(baja.getDate()){ 
				if(baja.getDate() < 10) {var dia = "0" +  baja.getDate();}else{var dia = baja.getDate();}
				if(baja.getMonth() < 10) {var mes = "0" + (baja.getMonth()+1);}else{var mes = (baja.getMonth()+1);}
				fchbaja =  dia + "-" + mes + "-" + baja.getFullYear();
			}else{
				fchbaja='';	
			}
			
			var ultpago = new Date(fchultpago);
			if(ultpago.getDate()){ 
				if(ultpago.getDate() < 10){ var dia2 = "0" +  ultpago.getDate();}else{var dia2 = ultpago.getDate();}
				if(ultpago.getMonth() < 10){ var mes2 = "0" + (ultpago.getMonth()+1);}else{var mes2 = (ultpago.getMonth()+1);}
				fchultpago =  dia2 + "-" + mes2 + "-" + ultpago.getFullYear();
			}else{
				fchultpago='';	
			}
			
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');	
			$('#form_botones_delete').css('display','block');
		
			$("#txCod").val(prof_id);
			$("#txNombre").val(nombre);
			$("#ttitu1").val(ttitu1);
			$("#ttitu1_matric").val(ttitu1_matric);
			$("#ttitu1_facu").val(ttitu1_facu);
			$("#ttitu2").val(ttitu2);
			$("#ttitu2_matric").val(ttitu2_matric);
			$("#ttitu2_facu").val(ttitu2_facu);
			$("#num").val(num);
			$("#tdoc").val(tdoc);
			$("#ndoc").val(ndoc);
			$("#dom_part").val(dom_part);
			$("#tel_part").val(tel_part);
			$("#dom_prof").val(dom_prof);
			$("#tel_prof").val(tel_prof);
			$("#mail").val(mail);
			$("#carnet_gestor").val(carnet_gestor);
			$("#matric_muni").val(matric_muni);
			$("#cuit").val(cuit);
			$("#expe").val(expe);
			$(':checkbox').prop('disabled',true);
			if(es_cons==1){$('#es_cons').prop('checked',true);}else{$('#es_cons').prop('checked',false);}
			if(es_empre==1){$('#es_empre').prop('checked',true);}else{$('#es_empre').prop('checked',false);}
			$("#contacto").val(contacto);
			$("#est").val(est);
			$("#obs").val(obs);
			$("#fchbaja").val(fchbaja);
			$("#motivo_baja").val(motivo_baja);
			$("#fchultpago").val(fchultpago);
			$("#anioultpago").val(anioultpago);
			
			$('#txCod').prop('readOnly',true);
			$('#txNombre').prop('disabled',true);
			$('#ttitu1').prop('disabled',true);
			$('#ttitu1_matric').prop('disabled',true);
			$('#ttitu1_facu').prop('disabled',true);
			$('#ttitu2').prop('disabled',true);
			$('#ttitu2_matric').prop('disabled',true);
			$('#ttitu2_facu').prop('disabled',true);
			$('#num').prop('disabled',true);
			$('#botonBuscarContrib').prop('disabled',true);
			$("#tdoc").prop('disabled',true);
			$('#ndoc').prop('disabled',true);
			$('#dom_part').prop('disabled',true);
			$('#tel_part').prop('disabled',true);
			$('#dom_prof').prop('disabled',true);
			$('#tel_prof').prop('disabled',true);
			$("#mail").prop('disabled',true);
			$('#carnet_gestor').prop('disabled',true);
			$('#matric_muni').prop('disabled',true);
			$("#cuit").prop('disabled',true);
			$('#expe').prop('disabled',true);
			$('#contacto').prop('disabled',true);
			$('#est').prop('disabled',true);
			$('#obs').prop('disabled',true);
			$('#fchbaja').prop('disabled',true);
			$("#motivo_baja").prop('disabled',true);
			$('#fchultpago').prop('disabled',true);
			$('#anioultpago').prop('disabled',true);			
									
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

	function btnCancelarAuxObrasPrivProf(){
		
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
			$("#ttitu1 option:first-of-type").attr("selected", "selected");
			$("#ttitu1_matric").val('');
			$("#ttitu1_facu").val('');
			$("#ttitu2 option:first-of-type").attr("selected", "selected");
			$("#ttitu2_matric").val('');
			$("#ttitu2_facu").val('');
			$("#num").val('');
			$("#tdoc option:first-of-type").attr("selected", "selected");
			$("#ndoc").val('');
			$("#dom_part").val('');
			$("#tel_part").val('');
			$("#dom_prof").val('');
			$("#tel_prof").val('');
			$("#mail").val('');
			$("#carnet_gestor").val('');
			$("#matric_muni").val('');
			$("#cuit").val('');
			$("#expe").val('');
			$(':checkbox').prop('disabled',true);
			$(':checkbox').prop("checked","");
			$("#contacto").val('');
			$("#est").val('');
			$("#obs").val('');
			$("#fchbaja").val('');
			$("#motivo_baja").val('');
			$("#fchultpago").val('');
			$("#anioultpago").val('');
			
			$('#txCod').prop('readOnly',true);
			$('#txNombre').prop('disabled',true);
			$('#ttitu1').prop('disabled',true);
			$('#ttitu1_matric').prop('disabled',true);
			$('#ttitu1_facu').prop('disabled',true);
			$('#ttitu2').prop('disabled',true);
			$('#ttitu2_matric').prop('disabled',true);
			$('#ttitu2_facu').prop('disabled',true);
			$('#num').prop('disabled',true);
			$('#botonBuscarContrib').prop('disabled',true);
			$("#tdoc").prop('disabled',true);
			$('#ndoc').prop('disabled',true);
			$('#dom_part').prop('disabled',true);
			$('#tel_part').prop('disabled',true);
			$('#dom_prof').prop('disabled',true);
			$('#tel_prof').prop('disabled',true);
			$("#mail").prop('disabled',true);
			$('#carnet_gestor').prop('disabled',true);
			$('#matric_muni').prop('disabled',true);
			$("#cuit").prop('disabled',true);
			$('#expe').prop('disabled',true);
			$('#contacto').prop('disabled',true);
			$('#est').prop('disabled',true);
			$('#obs').prop('disabled',true);
			$('#fchbaja').prop('disabled',true);
			$("#motivo_baja").prop('disabled',true);
			$('#fchultpago').prop('disabled',true);
			$('#anioultpago').prop('disabled',true);	
			
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
			if ($("#ttitu1_matric").val()=="" && $("#ttitu2_matric").val()=="")
			{
				err += "<li>Ingrese un codigo de matricula</li>";
			}
			if ($("#ttitu1_facu").val()=="" && $("#ttitu2_facu").val()=="")
			{
				err += "<li>Ingrese una facultad</li>";
			}
			if ($("#num").val()=="")
			{
				err += "<li>Ingrese un contribuyente</li>";
			}
			if ($("#ndoc").val()=="")
			{
				err += "<li>Ingrese un documento</li>";
			}
			if ($("#dom_part").val()=="")
			{
				err += "<li>Ingrese un domicilio particular</li>";
			}
			if ($("#tel_part").val()=="")
			{
				err += "<li>Ingrese un telefono particular</li>";
			}
			if ($("#dom_prof").val()=="")
			{
				err += "<li>Ingrese un domicilio profesional</li>";
			}
			if ($("#tel_prof").val()=="")
			{
				err += "<li>Ingrese un telefono profesional</li>";
			}
			if ($("#mail").val()=="")
			{
				err += "<li>Ingrese un mail</li>";
			}
			if ($("#contacto").val()=="")
			{
				err += "<li>Ingrese un contacto</li>";
			}
			if ($("#motivo_baja").val()=="")
			{
				err += "<li>Ingrese un motivo de baja</li>";
			}
			
			if (err == "")
			{
				$("#frmObrasPrivProf").submit();
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
