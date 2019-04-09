<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\models\ctacte\Plan;
use app\models\ctacte\PlanConfig;
use app\models\objeto\Domi;
use yii\grid\GridView;
use app\utils\db\Fecha;

$this->params['breadcrumbs'][] = ['label' => 'Operaciones'];
$this->params['breadcrumbs'][] = ['label' => 'Convenios de Pago','url' => ['plan']];
$this->params['breadcrumbs'][] = ['label' => 'Nuevo Convenio'];

$cond = "";
	
Pjax::begin(['id' => 'DivTipoPlan']);
	
	$tobj = 1;
	
	if (isset($_POST['tobj']) || $model->obj_id != "")
	{
		$tobj = isset($_POST['tobj']) ? $_POST['tobj'] : utb::getTObj($model->obj_id);
		$cond = "cod in (select tplan from plan_config_usuario where usr_id=".Yii::$app->user->id.")";
		$cond .= " and cod in (select TPlan from v_plan_tobj where cod=".$tobj.")";
		$cond .= " and (VigenciaHasta is null or to_char(VigenciaHasta,'YYYY/mm/dd')>='".date('Y/m/d')."')";
		
		$items = utb::getAux("plan_config",'cod','nombre',0,$cond);
		
		echo '<script>$("#dlTipo").empty();$("#dlTipo").val("");</script>';						
		if (count($items) > 0) {
			foreach ($items as $key => $value){
				$options="<option value='".$key."'>".$value."</option>";
				echo '<script>$("#dlTipo").append("'.$options.'");</script>';
			}
		}   
	}

	Modal::begin([
    	'id' => 'BuscaObjplan',
    	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
       	'closeButton' => [
       		'label' => '<b>X</b>',
           	'class' => 'btn btn-danger btn-sm pull-right',
       	],
       	'size' => 'modal-lg',
    ]);
      
	    echo $this->render('//objeto/objetobuscarav',[
				'id' => 'cc', 'txCod' => 'txObj_Id', 'txNom' => 'txObj_Nom', 'tobjeto' => $tobj, 'selectorModal' => '#BuscaObjplan'
	    	]);
			
		echo "<script>";
		echo "$('#BuscaObjplan').on('hidden.bs.modal', function () { txObj_IdChange();});";
		echo "</script>";		
       
    Modal::end();
Pjax::end();

Pjax::begin(['id' => 'DivSelectTipo']);

	$tipo = Yii::$app->request->post( 'tipo', 0);
	if ($tipo == "") $tipo  = 0;
	$anticipomanual = utb::getCampo("plan_config","cod=".$tipo,"anticipomanual");
	$sellado = utb::getCampo("plan_config","cod=".$tipo,"sellado");
	echo "<script>$('#txAnticipo').attr('readonly',".($anticipomanual == 0 ? 'true' : 'false').")</script>";
	echo "<script>$('#txSellado').val('".$sellado."')</script>";
	
	$Tipo = new PlanConfig();
    $Tipo->cod = $tipo;
    $TipoPlan = $Tipo->buscarUno();
    echo '<script>$("#txTasaInteres").val("'.$TipoPlan['interes'].'")</script>';
	echo '<script>$("#arrayPlanConfig").val("'.urlencode(serialize($TipoPlan)).'")</script>';
    
Pjax::end();

Pjax::begin(['id' => 'CargarModeloDomi']);

    if (isset($_POST['tor']))
    {
    	if ($modeldomi == null) $modeldomi = new Domi;
    	
    	$modeldomi->torigen = 'PLA';
 		$modeldomi->obj_id = '';
 		$modeldomi->id = '';
 		$modeldomi->prov_id = isset($_POST['prov_id']) ? $_POST['prov_id'] : 0;
		$modeldomi->loc_id = isset($_POST['loc_id']) ? $_POST['loc_id'] : 0;
 		$modeldomi->cp = isset($_POST['cp']) ? $_POST['cp'] : "";
 		$modeldomi->barr_id = isset($_POST['barr_id']) ? $_POST['barr_id'] : 0;
 		$modeldomi->calle_id = isset($_POST['calle_id']) ? $_POST['calle_id'] : 0;
 		$modeldomi->nomcalle = isset($_POST['nomcalle']) ? $_POST['nomcalle'] : "";
 		$modeldomi->puerta = isset($_POST['puerta']) ? $_POST['puerta'] : "";
 		$modeldomi->det = isset($_POST['det']) ? $_POST['det'] : "";
 		$modeldomi->piso = isset($_POST['piso']) ? $_POST['piso'] : "";
 		$modeldomi->dpto = isset($_POST['dpto']) ? $_POST['dpto'] : "";
 	 	
// 		$modeldomi->domicilio = $modeldomi->nomcalle.' '.$modeldomi->puerta.' '.$modeldomi->det.($modeldomi->piso != '' ? ' Piso: '.$modeldomi->piso : '');
//		$modeldomi->domicilio .= ($modeldomi->dpto !='' ? ' Dpto: '.$modeldomi->dpto.' - ' : '').utb::getCampo("domi_localidad","loc_id=".$modeldomi->loc_id,"nombre");
//		$modeldomi->domicilio .= ' - '.utb::getCampo("domi_provincia","prov_id=".$modeldomi->prov_id,"nombre");
 	
 		echo '<script>$("#txResDomi").val("'.$modeldomi->armarDescripcion().'")</script>'; 
 		echo '<script>$("#arrayDomi").val("'.urlencode(serialize($modeldomi)).'")</script>';
    }
Pjax::end();
    
?>

<div class="site-index">
	<h1><?= Html::encode('Nuevo Convenio') ?></h1>
    <?php 
    	$form = ActiveForm::begin(['action' => ['plannuevograbar'],'id' => 'formPlanNuevo',
    								'fieldConfig' => [
        										'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
        										],
        						]);
    	    	    	
    	$param = Yii::$app->params;	
    	
    	echo '<input type="text" name="arrayDomi" id="arrayDomi" value="'.urlencode(serialize($modeldomi)).'" style="display:none">';
    ?>
    
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
		<table border='0'>
		<tr>
			<td width="90px"><label>Objeto:</label></td>
			<td>
			<?= Html::dropDownList('dlTObjeto',utb::getTObj($model->obj_id), utb::getAux('objeto_tipo','cod','nombre',0,"est='A'"), 
    					['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlTObjeto', 
							'onchange' => 'SelectTObjeto();']); ?>
							
			<?= Html::input('text', 'txObj_Id', $model->obj_id, [
					'class' => 'form-control',
					'id'=>'txObj_Id',
					'style'=>'width:85px',
					'maxlength'=>'8',
					'readonly' => ($model->obj_id != ''),
					'onchange'=>'txObj_IdChange()',
				]);
			?>
			</td>
			<td width="5px"></td>
			<td>
			<!-- INICIO Botón Búsqueda Objeto Modal -->			
            <?= Html::button('<i class="glyphicon glyphicon-search"></i>',[  
                   	'class' => 'bt-buscar',
					'disabled' => true,
					'id' => 'BuscaObjplanBt',
					'onclick' => '$("#BuscaObjplan").modal("show")',
					
				]);
			?>
            <!-- Fin Botón Búsqueda Objeto Modal -->
            </td>
            <td width="5px"></td>
            <td>
			<?= Html::input('text', 'txObj_Nom',utb::getNombObj("'".$model->obj_id."'"), ['class' => 'form-control','id'=>'txObj_Nom','style'=>'width:340px;background:#E6E6FA;','disabled'=>'true']); ?>
			</td>
		</tr>
		<tr>
			<td ><label>Tipo:</label></td>
			<td width="200px">
				<?php 
				echo Html::dropDownList('dlTipo',$model->tplan, utb::getAux('Plan_Config','cod','nombre',0,$cond), 
    					['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlTipo', 
    						'style' => 'width:99%',
							'onchange' => 'SelectTipo();']); 
				?>
			</td>
			<td></td>
			<td>
				<?php
				
				Modal::begin([
                	'id' => 'ModifParam',
					'header' => '<h2>Modificar Parámetros</h2>',
					'toggleButton' => [
                    	'label' => '<i class="glyphicon glyphicon-pencil"></i>',
                    	'class' => 'bt-buscar',
						'id' => 'ModifParamBt',
						'disabled' => true
                	],
                	'closeButton' => [
                  	'label' => '<b>X</b>',
                  	'class' => 'btn btn-danger btn-sm pull-right',
                	],
            	]);
          
					echo $this->render('planmodifparam');
           
            	Modal::end();
            	
            	echo '<input type="text" name="arrayPlanConfig" id="arrayPlanConfig" value="" style="display:none">';
            	?>
            </td>
            <td></td>
            <td colspan='3'><label class="control-label">Alta:</label>
				<?= 
				DatePicker::widget(
					[
						'id' => 'fchalta',
						'name' => 'fchalta',
						'dateFormat' => 'dd/MM/yyyy',
						'value' => ($model->fchalta == '' ? Fecha::bdToUsuario(date('d/m/Y')) : Fecha::bdToUsuario($model->fchalta)),
						'options' => ['class' => 'form-control'.(utb::getExisteProceso(3353) == 1 ? '' : ' solo-lectura'),
									'style' => 'width:105px;text-align:center',
									'readonly'=>(utb::getExisteProceso(3353) == 1 ? false : true),
									'tabindex'=>(utb::getExisteProceso(3353) == 1 ? '' : '-1'),
									'onchange' => '$.pjax.reload({container:"#DivPeriodo",data:{tplan:$("#dlTipo").val(),orig:$("#dlOrigen").val()},method:"POST"})'],
					]
				);
				?>
			</td>
		</tr>
		<tr>
			<td><label>Contribuyente: </label></td>
			<td colspan='6'>
				<?= Html::input('text', 'txContrib', $model->num, ['class' => 'form-control','id'=>'txContrib', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;']); ?>
				<?= Html::input('text', 'txContribNom', utb::getNombObj("'".$model->num."'"), ['class' => 'form-control','id'=>'txContribNom', 'disabled'=>'true','style'=>'width:350px; background:#E6E6FA;']); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr><td colspan='6'><label><u>Datos del Responsable</u></label></td></tr>
		<tr>
			<td><label>Documento:</label></td>
			<td>
				<?= Html::dropDownList('dlResTDoc', $model->resptdoc, utb::getAux('persona_tdoc'), [
						'prompt'=>'Seleccionar...',
						'class' => 'form-control',
						'id'=>'dlResTDoc',
						'style' => 'width:98%'
					]);
				?>
    		</td>
    		<td colspan="2">
    			<?= Html::input('text', 'txResNDoc', $model->respndoc, ['class' => 'form-control','id'=>'txResNDoc', 'maxlength'=>'11','style'=>'width:80px;']); ?>
    		</td>
		</tr>
		<tr>
			<td><label>Domicilio:</label></td>
			<td colspan='5'>
				<?php
				Modal::begin([
        			'id' => 'BuscaDomiPLA',
					'header' => '<h2>Domicilio Contribuyente</h2>',
					'toggleButton' => [
            			'label' => '<i class="glyphicon glyphicon-search"></i>',
                		'class' => 'bt-buscar',
                		'id' => 'btBuscarDomi'
            			],
            		'closeButton' => [
            			'label' => '<b>X</b>',
                		'class' => 'btn btn-danger btn-sm pull-right',
            			],
        			]);
          
        			echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modeldomi, 'tor' => 'PLA']);
           
       			Modal::end();
        		echo Html::input('text', 'txResDomi', $modeldomi->armarDescripcion(), ['class' => 'form-control','id'=>'txResDomi', 'disabled'=>'true','style'=>'width:395px; background:#E6E6FA;']); 
        		?>
			</td>
		</tr>
		<tr>
			<td><label>Nombre:</label></td>
			<td colspan='3'>
    			<?= Html::input('text', 'txResNombre', $model->resp, ['class' => 'form-control','id'=>'txResNombre', 'maxlength'=>'45','style'=>'width:98%;']); ?>
    		</td>
    		<td><label>Teléfono:</label></td>
    		<td>   		
        		<?= Html::input('text', 'txResTel', $model->resptel, ['class' => 'form-control','id'=>'txResTel', 'maxlength'=>'15','style'=>'width:105px;']); ?>
			</td>
		</tr>
		<tr>
			<td><label>Origen:</label></td>
			<td>
				<?= Html::dropDownList('dlOrigen', $model->origen, utb::getAux('plan_torigen','cod','nombre',0,'cod<4'), [
						'class' => 'form-control',
						'id'=>'dlOrigen',
						'style' => 'width:98%',
    					'onchange' => 'dlOrigenSelect()']); ?>
    		</td>
    		<td><label>Judi Nº:</label></td>
    		<td>
    			<?= Html::input('text', 'txJudiNum', $model->judi_id, [
    					'class' => 'form-control',
    					'id'=>'txJudiNum', 
						'disabled' => ($model->origen != 3 ? true : false), 
						'maxlength'=>'6',
						'style'=>'width:96%;',
						'onchange' => '$.pjax.reload("#DivProcesar")', # DivProcesar se encuentra en el archivo plannuevoperiodo.php - Tab de período
					]);
				?>
    		</td>
		</tr>
		<tr>
			<td><label>Tipo Distribución:</label></td>
    		<td>
    			<?= Html::dropDownList('dlTDistrib', $model->tdistrib, utb::getAux('objeto_tdistrib'), 
    				['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlTDistrib']); ?>
			</td>
			<td><label>Distribuidor:</label></td>
    		<td>
    			<?= Html::dropDownList('dlDistrib', $model->distrib, utb::getAux('Sam.Sis_Usuario','usr_id','apenom',0,'Distrib=1'), 
    				['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlDistrib']); ?>
			</td>
		</tr>
		<tr>
			<td><label>Forma de Pago:</label></td>
			<td>
				<?= Html::dropDownList('dlTPago', 1, utb::getAux('plan_tpago'), 
    					['class' => 'form-control', 'id'=>'dlTPago',  
							'onchange' => 'SelectTPago();']); ?>
			</td>
			<td><label>Caja:</label></td>
			<td>
				<?= Html::dropDownList('dlCaja', $model->caja_id, utb::getAux('caja','caja_id','nombre',0,"Tipo in (3,4,5) and est='A'"), 
    					['prompt'=>'Seleccionar...',
    					 'class' => 'form-control',
    					 'style' => 'margin-right: 2px',
    					 'id'=>'dlCaja',  
    						'disabled' => ($model->tpago != 3 ? true : false),
							'onchange' => 'SelectCaja();']); ?>
			</td>
		</tr>
		<tr>
			<td colspan='6'>
				<?php 
				
				Pjax::begin(['id' => 'DivDatosCaja']);
				
					$caja = (isset($_POST['caja']) ? $_POST['caja'] : ($model->caja_id == '' ? 0 : $model->caja_id));
					$tcaja = utb::getCampo('caja','caja_id='.$caja,'tipo');
					$tcaja = ( is_numeric( $tcaja ) ? $tcaja : 0 );
					$bco_ent = utb::getCampo('caja','caja_id='.$caja,'Ext_Bco_Ent');
					$bco_suc = ($model->bco_suc == '' ? 0 : $model->bco_suc);
					
				?>
				<div id='DivCHab' style='display:none'>
					<label>Emp.:</label>
					<?= Html::dropDownList('dlEmp', $model->temple, utb::getAux('plan_temple','cod','nombre',0,"caja_id=".$caja), 
    						['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlEmp']); ?>
					<label>Area</label>
					<?= Html::input('text', 'txArea', $model->temple_area, ['class' => 'form-control','id'=>'txArea', 
							'maxlength'=>'20', 'style'=>'width:100px;']); ?>
					<label>Legajo</label>
					<?= Html::input('text', 'txLegajo', $model->tpago_nro, ['class' => 'form-control','id'=>'txLegajo', 
							 'maxlength'=>'22', 'style'=>'width:100px;']); ?>
				</div>
				<div id='DivTC' style='display:none'>
					<label>Nº de Tarjeta</label>
					<?= Html::input('text', 'txNroTarj', $model->tpago_nro, ['class' => 'form-control','id'=>'txNroTarj', 
							'maxlength'=>'22', 'style'=>'width:80px;']); ?>
				</div>
				<div id='DivDebito' style='display:none'>
					<label>Suc.</label>
					<?= Html::input('text', 'txSuc', $bco_suc, ['class' => 'form-control','id'=>'txSuc',
							'onChange' => '$.pjax.reload({container:"#DivNomSuc",data:{suc:this.value,ent:'.$bco_ent.'},method:"POST"});',
							'maxlength'=>'4', 'style'=>'width:30px;']); ?>
					<?php
						Modal::begin([
        					'id' => 'BuscaSuc',
							'toggleButton' => [
            				'label' => '<i class="glyphicon glyphicon-search"></i>',
                			'class' => 'bt-buscar',
                			'id' => 'btBuscarSuc'
            				],
            				'closeButton' => [
            				'label' => '<b>X</b>',
                			'class' => 'btn btn-danger btn-sm pull-right',
            				],
            				'size' => 'modal-lg',
        				]);
          
        				echo $this->render('//taux/auxbusca', [
							'tabla' => 'banco', 'campocod' => 'bco_suc',
							'idcampocod'=>'txSuc',
            				'idcamponombre'=>'txSuc_Nom',
            				'boton_id' => 'BuscaSuc',
            				'cantmostrar' => 10,
            				'criterio' => 'bco_ent='.($bco_ent == '' ? 0 : $bco_ent)
							]);
           
        				Modal::end();
        			?>
        			<?= Html::input('text', 'txSuc_Nom', utb::getCampo('banco',"bco_ent=".($bco_ent =='' ? 0 : $bco_ent)." and bco_suc=".($bco_suc =='' ? 0 : $bco_suc)), 
        					['class' => 'form-control','id'=>'txSuc_Nom', 
								'style'=>'width:170px; background:#E6E6FA;']);
        			?>
        			<label>Tipo:</label>
        			<?= Html::dropDownList('dlTCta', $model->bco_tcta, utb::getAux('banco_tcuenta'), 
    					['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlTCta']); ?>
    				<label>Nº Cta./CBU</label>
    				<?= Html::input('text', 'txNCta', $model->tpago_nro, ['class' => 'form-control','id'=>'txNCta', 
							'maxlength'=>'22','style'=>'width:200px;']); ?>
				</div>
				<?php 
				
					if ($tcaja == 3)
					{
						echo "<script>";
						echo "$('#DivDebito').css('display','block');";
						if (isset($_POST['caja']))
						{
							echo "$('#dlTCta').val('');";
							echo "$('#txSuc').val('');";
							echo "$('#txSuc_Nom').val('');";
							echo "$('#txNCta').val('');";	
						}
						echo "</script>";
					}
					
					if ($tcaja == 4)
					{
						echo "<script>";
						echo "$('#DivTC').css('display','block');";
						if (isset($_POST['caja'])) echo "$('#txNroTarj').val('');";
						echo "</script>";
					}
					
					if ($tcaja == 5)
					{
						echo "<script>";
						echo "$('#DivCHab').css('display','block');";
						if (isset($_POST['caja']))
						{
							echo "$('#dlEmp').val('');";
							echo "$('#txArea').val('');";
							echo "$('#txLegajo').val('');";	
						}
						echo "</script>";
					}
					
					if ( $caja != 0 )
						echo '<script>$.pjax.reload({container:"#DivBtn",data:{tcaja:"'.$tcaja.'"},method:"POST"});</script>';
						
				Pjax::end(); 
				
				Pjax::begin(['id' => 'DivNomSuc']);
        			$suc = 0;
        			$ent = 0;
        			if (isset($_POST['suc'])) $suc = $_POST['suc'];
        			if (isset($_POST['ent'])) $ent = $_POST['ent'];
        				
        			if (isset($_POST['suc'])) echo "<script>$('#txSuc_Nom').val('".utb::getCampo('banco',"bco_ent=".$ent." and bco_suc=".$suc)."')</script>";
				Pjax::end(); 
				?>
			</td>
		</tr>
		</table>
	</div>
	
	<?php
		
	Pjax::begin(['id' => 'DivPeriodo']);
	
		echo "<script>";
		echo '$("#btCalcular").css("display","inline");';
		echo '$("#btAceptar").css("display","none");';
		echo '$("#btImpCtas").css("display","none");';
		echo '$("#btImpDetCuota").css("display","none");';
		echo "</script>";
		
		$tplan = (!isset($_POST['tplan']) or $_POST['tplan']=='' ? 0 : $_POST['tplan']);
		$origen = (!isset($_POST['orig']) or $_POST['orig'] =='' ? 0 : $_POST['orig']);
			
		// muestro tab de datos
		echo Tabs :: widget ([ 
	 		'id' => 'TabPlan',
			'items' => [ 
	 			['label' => 'Períodos', 
	 			'content' =>  $this->render('plannuevoperiodo',['origen' => $origen]),
	 			'options' => ['class'=>'tabItem']
	 			],
	 			['label' => 'Deuda/Cuota' , 
	 			'content' => $this->render('plannuevodeudacuota',['tplan' => $tplan]),
	 			'options' => ['class'=>'tabItem'],
	 			],
	 			['label' => 'Observaciones' , 
	 			'content' => Html::checkbox('ckIncObs',false,['id'=>'ckIncObs', 
								'label'=>'Incluir Observación en el texto del contrato'])."<br>". 
	 						 Html::textarea('txObs', null, 
	 							['class' => 'form-control','id'=>'txObs', 'maxlength'=>'500',
									'style'=>'width:740px; height:100px; resize:none']),
	 			'options' => ['class'=>'tabItem'],
	 			]
	 		]
		]);  
		
	Pjax::end();	
	
	?>
	
	<div style="margin-top: 8px">
	
	<?= Html::Button('Imprimir Cuotas', ['class' => 'btn btn-success', 'id' => 'btImpCtas', 'style' => 'display:none;margin-right: 8px','onclick'=>'btImpCtasClick()']); ?>
	<?= Html::Button('Detalle Cuotas', ['class' => 'btn btn-success', 'id' => 'btImpDetCuota', 'style' => 'display:none;margin-right: 8px','onclick'=>'$("#ModalDetCuotas").modal();']); ?>
	<?= Html::Button('Calcular', ['class' => 'btn btn-primary', 'id' => 'btCalcular', 'style' => 'margin-right: 8px', 'onclick' => 'btCalcularClick()']); ?>
	<?= Html::Button('Grabar', ['class' => 'btn btn-success', 'id' => 'btAceptar', 'style' => 'display:none;margin-right: 8px', 'onclick' => 'btAceptarClick()']); ?>
	<?= Html::a('Cancelar', ['plan'], ['class' => 'btn btn-primary']); ?>
	
	</div>
	
	<?php
	
		ActiveForm::end();
	
	?>
	
	<div id="plannuevo_errorSummary" class="error-summary" style="display:none;margin-top: 8px" class="alert alert-danger alert-dismissable"></div>
	
	<?php
	
	if(isset($error) && $error !== '')
	{  
		if (is_array($error)){
			echo '<script>';
			echo 'var error = new Array();';
			foreach ( $error as $array )
			{
				echo 'error.push("'.$error.'");';
			}
			echo 'mostrarErrores( error, "#plannuevo_errorSummary");';
			echo '</script>';
		}else {
			$pos = strpos($error, 'CONTEXT:');
			
			if ($pos !== false) {
				$error = strstr($error, 'CONTEXT:', true);
			}
			$error = str_replace('"',"",$error);
			$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
			$reemplazar=array("", "", "", "");
			$error=str_ireplace($buscar,$reemplazar,$error);
			echo '<script>';
			echo 'mostrarErrores(["'.trim($error).'"], "#plannuevo_errorSummary");';
			echo '</script>';
		}
		?>
		<script>
			$(function() {
				SelectTipo();
				dlOrigenSelect();
			}
		</script>
		
		<?php
		
		$error = '';
	}
	?>
</div>
<?php

# INICIO Pjax que se ejecuta al actualizar el ID de objeto
Pjax::begin(['id' => 'DivObjNom']);

	$objeto_id = Yii::$app->request->post( 'obj_id', $model->obj_id);
	$tobj = Yii::$app->request->post( 'tobj', $objeto_id != '' ? utb::getTObj($objeto_id) : 0);
		
	if (strlen($objeto_id) == 8){
		if ($tobj != utb::getTObj($objeto_id)) $tobj = utb::getTObj($objeto_id);
	}
	
	//Cargar el objeto
	if (strlen($objeto_id) < 8)
	{
		if ($tobj != '' and $tobj != 0)
		{
			$objeto_id = utb::GetObjeto($tobj,(int)$objeto_id);
		}
	}
	
	//Verificar que exista el objeto obtenido
	if (utb::verificarExistenciaObjeto($tobj,"'".$objeto_id."'") == 0)
		$objeto_id = '';
	
	//Obtener el nombre del objeto	
	$objeto_nom = utb::getNombObj("'".$objeto_id."'");
	
	//Obtener el contribuyente
	$num = utb::getCampo("objeto","obj_id='".$objeto_id."'","num");	
	$num_nom = utb::getNombObj("'".$num."'");
	
	//Obtener el distribuidor
	$distrib = utb::getCampo("objeto","obj_id='".$objeto_id."'","distrib");
	$tdistrib = utb::getCampo("objeto","obj_id='".$objeto_id."'","tdistrib");
		
	echo '<script>$("#txObj_Id").val("'.$objeto_id.'")</script>';
	echo '<script>$("#txObj_Nom").val("'.$objeto_nom.'")</script>';
	echo '<script>$("#dlTObjeto").val("'.utb::getTObj($objeto_id).'")</script>';
	echo '<script>$("#txContrib").val("'.$num.'")</script>';
	echo '<script>$("#txContribNom").val("'.$num_nom.'")</script>';
	echo '<script>$("#dlDistrib").val("'.$distrib.'")</script>';
	echo '<script>$("#dlTDistrib").val("'.$tdistrib.'")</script>';
	
	$tdoc = 0;
	$ndoc = 0;
	$tel = '';
	$domi = '';
	$respnom = '';
	
	(new Plan)->GetPersona($num,$tdoc,$ndoc,$tel,$domi,$respnom);
	$modeldomiresp = Domi::cargarDomi('OBJ', $num,0);
	
	if ($respnom != '')
	{
		echo '<script>$("#dlResTDoc").val("'.$tdoc.'")</script>';
		echo '<script>$("#txResNDoc").val("'.$ndoc.'")</script>';
		echo '<script>$("#txResNombre").val("'.$respnom.'")</script>';
		echo '<script>$("#txResTel").val("'.$tel.'")</script>';
		echo '<script>$("#txResDomi").val("'.$domi.'")</script>';
		echo '<script>$("#arrayDomi").val("'.urlencode(serialize($modeldomiresp)).'")</script>';
	}
					
Pjax::end();
# FIN Pjax que se ejecuta al actualizar el ID de objeto

Pjax::begin(['id'=>'DivImpCtas']);
	
	if (isset($_POST['ImpPre']))
	{
		$fchcons = (isset($_POST['fchcons']) ? $_POST['fchcons'] : '');
		$objeto = (isset($_POST['obj_id']) ? $_POST['obj_id'] : "");
		$tplan = (isset($_POST['tplan']) ? $_POST['tplan'] : 0);
		$resp = (isset($_POST['rest']) ? $_POST['resp'] : "");
		
		$criterio = "<br><font size='3'><b>Preliminar de Convenio de Pago<b></font> " . 
            "<br>Fecha Consolidación: " .$fchcons. " - Tipo Plan: ". utb::getCampo("plan_config", "cod=" . $tplan,"nombre") .
            "<br>Objeto: " . $objeto . 
				" - Dato: " . utb::getCampo("objeto", "obj_id='" . $objeto . "'","obj_dato") .
            	" - Responsable: " . $resp;
		Yii::$app->session['PrelimPerCond'] = $criterio;
		
		$url = Yii::$app->request->baseUrl."/index.php?r=ctacte/convenio/imprimirpreliminarperidos";
		echo "<script>window.open('".$url."','_blank');</script>";
	}
Pjax::end();

Modal::begin([
	'id' => 'ModalDetCuotas',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Detalles de Cuotas de Convenio</h2>',
	'closeButton' => [
          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ],
	]);

	Pjax::begin(['id'=>'DivModalDetCuota']);
		
		if (Yii::$app->session['PrelimPer'] != null){
			echo  GridView::widget([
				'dataProvider' => utb::DataProviderGeneralCons(Yii::$app->session['PrelimPer']),
				'id' => 'GrillaModalDetCuota',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'columns' => [
					['attribute'=>'cuota_nom','header' => 'Cuota','contentOptions'=>['style'=>'width:60px;text-align:center']],
		    		['attribute'=>'capital','header' => 'Capital','contentOptions'=>['style'=>'width:90px;text-align:right'],'format'=>['decimal',2]],
		    		['attribute'=>'financia','header' => 'Financia','contentOptions'=>['style'=>'width:90px;text-align:right']],
		    		['attribute'=>'sellado','header' => 'Sellado','contentOptions'=>['style'=>'width:90px;text-align:right']],
		    		['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'width:90px;text-align:right'],'format'=>['decimal',2]],
		    		['attribute'=>'fchvenc','header' => 'Vencimiento','contentOptions'=>['style'=>'width:80px;text-align:center']],
		    		['attribute'=>'est','header' => 'Est','contentOptions'=>['style'=>'width:50px;text-align:center']],
			   	],
			]);
			
			echo "<div class='form' style='padding:5px;margin-top:5px'>";
			echo "<label><u>Resumen</u></label><br><br>";
			echo "<label>Capital:</label>";
			echo Html::input('text', 'txDetCtaCapital', null, ['class' => 'form-control','id'=>'txDetCtaCapital', 'readonly'=>'true',
						'style'=>'width:70px; background:#E6E6FA;text-align:right']);
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Financiación:</label>";
			echo Html::input('text', 'txDetCtaFinan', null, ['class' => 'form-control','id'=>'txDetCtaFinan', 'readonly'=>'true',
						'style'=>'width:70px; background:#E6E6FA;text-align:right']);
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Sellado:</label>";
			echo Html::input('text', 'txDetCtaSell', null, ['class' => 'form-control','id'=>'txDetCtaSellado', 'readonly'=>'true',
						'style'=>'width:70px; background:#E6E6FA;text-align:right']);
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Total:</label>";
			echo Html::input('text', 'txDetCtaTotal', null, ['class' => 'form-control','id'=>'txDetCtaTotal', 'readonly'=>'true',
						'style'=>'width:70px; background:#E6E6FA;text-align:right;']);
			echo "</div>";
			
			echo '<script>' .
					'$("#txDetCtaCapital").val($("#txSubTotal").val());
					$("#txDetCtaFinan").val($("#txFinanc").val());
					$("#txDetCtaSell").val($("#txSellado").val());
					$("#txDetCtaTotal").val($("#txAPagar").val());' .
				'</script>';
		}
	Pjax::end();
	
Modal::end();

?>
<script>

function SelectTObjeto()
{
	$("#txObj_Id").val("");
	$("#txObj_Nom").val("");
	
	$("#BuscaObjplanBt").attr("disabled",true);
	$("#txObj_Id").attr("disabled",true);
	
	if ( $("#dlTObjeto").val() != "" && $("#dlTObjeto").val() != null )
	{
		$("#BuscaObjplanBt").removeAttr("disabled");
		$("#txObj_Id").removeAttr("disabled");
		
		$.pjax.reload({
			container:"#DivTipoPlan",
			replace: false,
			push: false,
			data:{
				tobj:$("#dlTObjeto").val(),
			},
			method:"POST"
		});	
	}
	
	$("#DivTipoPlan").on("pjax:end",function() {
		
		if ( $("#dlTipo").val() != "" )
		{
			$("#ModifParamBt").removeAttr("disabled");
			
			$.pjax.reload({
				container:"#DivSelectTipo",
				replace: false,
				push: false,
				data:{tipo:$("#dlTipo").val()},
				method:"POST"
			});	
		}
		
		$("#DivTipoPlan").off("pjax:end");
	});
}

function txObj_IdChange()
{
	$("#txObj_Nom").val("");
	$("#txContrib").val("");
	$("#txContribNom").val("");
	$("#dlResTDoc").val("");
	$("#txResNDoc").val("");
	$("#txResDomi").val("");
	$("#txResNombre").val("");
	$("#txResTel").val("");
	
	$.pjax.reload({
		container:"#DivObjNom",
		data:{
			tobj:$("#dlTObjeto").val(),
			obj_id:$("#txObj_Id").val(),
		},
		method:"POST",
	});	
	
	$("#DivObjNom").on("pjax:end", function() {
						
		//SelectTipo();
		$.pjax.reload({
			container:"#DivTipoPlan",
			replace: false,
			push: false,
			data:{
				tobj:$("#dlTObjeto").val(),
			},
			method:"POST"
		});	
		
		$("#DivObjNom").off("pjax:end");
						
	});
}

function SelectTipo()
{
	$("#ModifParamBt").attr("disabled",true);
	
	$.pjax.reload({
		container:"#DivPeriodo",
		data:{
			tplan:$("#dlTipo").val(),
			orig:$("#dlOrigen").val(),
		},
		method:"POST"
	});	
	
	$("#DivPeriodo").on("pjax:end", function() {
		
		if ($("#dlTipo").val() != "")
		{
			$("#ModifParamBt").removeAttr("disabled");
			
			$.pjax.reload({
				container:"#DivSelectTipo",
				data:{tipo:$("#dlTipo").val()},
				method:"POST"
			});	
		}
		
		$("#DivPeriodo").off("pjax:end");
	});
	
		
}

function dlOrigenSelect()
{
	$("#txJudiNum").attr("disabled",($("#dlOrigen").val()!=3));
	$("#ckIncPerJui").attr("disabled",($("#dlOrigen").val()!=3));
	$("#ckJudiDeuda").attr("disabled",($("#dlOrigen").val()!=3));
	$("#ckJudiId").attr("disabled",($("#dlOrigen").val()!=3));
	$("#dlIntJud").attr("disabled",($("#dlOrigen").val()!=3));
	$("#txJudiInteres").attr("disabled",($("#dlOrigen").val()!=3));
	$("#txJudiNum").attr("disabled",($("#dlOrigen").val()!=3));	
}

function SelectTPago()
{
	$('#dlCaja').val("");
	$('#dlCaja').attr('disabled',($('#dlTPago').val() != 3))
}

function SelectCaja()
{
	$.pjax.reload(
		{
			container:"#DivDatosCaja",
			data:{
					caja:$("#dlCaja").val()
				},
			method:"POST"
		});
}

function Validar()
{
	error = new Array();
	
	if ($("#dlTipo").val() == ""){
		error.push( "Seleccione el Tipo de Convenio" );
	}
    if ($("#txObj_Id").val() == "")
    {
    	error.push( "El Objeto es incorrecto" );
    }
    if ($("#dlTPago").val() == "")
    {
    	error.push( "La Forma de Pago es obligatoria" );
    }
    if ($("#dlTPago").val() == 3 && $("#dlCaja").val() == "")
    {
    	error.push( "La Caja es obligatoria según Forma de Pago" );
    }    
    if ($("#dlOrigen").val() == "")
    {
    	error.push( "Debe seleccionar El Tipo de Origen" );
    }    
    if ($("#txContrib").val() == "")
    {
    	error.push( "El Contribuyente es incorrecto" );
    }    
    if ($("#txResNombre").val() == "")
    {
    	error.push( "Ingrese un Responsable" );
    }    
    if ($("#dlResTDoc").val() == "")    
    {
    	error.push( "Seleccione el Tipo de Documento del Responsable" );
    }    
    if ($("#txResNDoc").val() == "")
    {
    	error.push( "Ingrese el Número de Documento del Responsable" );
    }    
    if ($("#txResDomi").val() == "")
    {
    	error.push( "Ingrese el Domicilio del Responsable" );
    }    
    var periodos = $('#GrillaDetalleDeuda').yiiGridView('getSelectedRows');   
    
    if (periodos.length <= 0)
    {
    	error.push( "Debe generar la Deuda" );
    } 
    
	if (!$("#txAnticipo").attr("readonly") && $("#txAnticipo").val() < 1)
    {
    	error.push( "Debe ingresar el Anticipo" );
    }    
    if ($('#DivDebito').css('display') == 'block')
    {
    	/*if ($("#txSuc_Nom").val() == "")
    	{
    		error.push( "Debe ingresar una Sucursal válida" );
    	}*/
    	if ($("#dlTCta").val() == "")
    	{
    		error.push( "Debe seleccionar el Tipo de Cuenta" );
    	}
    	if ($("#txNCta").val() == "")
    	{
    		error.push( "Debe ingresar el Número de Cuenta" );
    	}
    }
    if ($('#DivTC').css('display') == 'block')
    {
    	if ($("#txNroTarj").val() == "")
    	{
    		error.push( "Debe ingresar el Nº Tarjeta de Crédito" );
    	}
    }
    if ($("#dlTPago").val() == 5)
    {
    	if ($("#dlEmp").val() == "")
    	{
    		error.push( "Debe seleccionar el Tipo de Empleado" );
    	}
    	if ($("#txArea").val() == "")
    	{
    		error.push( "Debe ingresar el Area del Empleado" );
    	}
    }    
    
    if ( error.length > 0 )
    {
    	mostrarErrores( error, "#plannuevo_errorSummary" );
    } 
        
	return error;
}

function btCalcularClick()
{
	ocultarErrores ("#plannuevo_errorSummary");
	if ( Validar() != "") return "";
	var periodos = $('#GrillaDetalleDeuda').yiiGridView('getSelectedRows');
		
	$.pjax.reload({
		container:"#DivCalcularDeuda",
		data:{
			nominal:$("#txNominal").val(),
			accesor:$("#txAccesor").val(),
			multa:$("#txMulta").val(),
			totaldeuda:$("#txSubTotal").val(),
			cantper:periodos.length,
			cantcuotas:$("#txCantCuota").val(),
			fchcons:$("#fchalta").val(),
			tplan:$("#dlTipo").val(),
			obj_id:$("#txObj_Id").val(),
			anticipo:$("#txAnticipo").val(),
			arrayper:$("#arrayPeriodo").val(),
			periodoscheck:periodos,
			interestasa:$("#txTasaInteres").val()
		},
		method:"POST"
	});	
	
	$("#btCalcular").css("display","none");
	$("#btAceptar").css("display","inline");
	$("#btImpCtas").css("display","inline");
	$("#btImpDetCuota").css("display","inline");
}

function btAceptarClick()
{
	ocultarErrores ("#plannuevo_errorSummary");
	if (Validar() != "") return "";
	
	if ($("#txValorPorCuota").val() == 0 && $("#txAnticipo").val() > 0)
	{
		mostrarErrores( ["No puede dar de alta Plan sin monto de cuota"], "#plannuevo_errorSummary" );
		return "";
	}
            
    $("#formPlanNuevo").submit();
}

function btImpCtasClick()
{
	$.pjax.reload({
		container:"#DivImpCtas",
		data:{
			ImpPre:1,
			fchcons:$("#fchalta").val(),
			tplan:$("#dlTipo").val(),
			obj_id:$("#txObj_Id").val(),
			resp:$("#txResNombre").val()
		},
		method:"POST"
	});	
}

</script>
