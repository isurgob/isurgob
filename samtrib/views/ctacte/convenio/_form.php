<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use \yii\widgets\Pjax;
use yii\web\Session;
use \yii\bootstrap\Modal;
use app\models\ctacte\Plan;
use yii\grid\GridView;
use yii\helpers\BaseUrl;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Plan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-index">
    <?php 
    	$form = ActiveForm::begin(['action' => ['modificarplan', 'id' => $model->plan_id],'id' => 'formPlan',
    								'fieldConfig' => [
        										'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
        										],
        						]);
    	    	    	
    	$param = Yii::$app->params;	
    	
    	Pjax::begin(['id' => 'CargarModeloDomi']);
    		if (isset($_POST['tor']))
    		{
    			$modeldomi->torigen = 'PLA';
 				$modeldomi->obj_id = $model->obj_id;
 				$modeldomi->id = $model->plan_id;
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
 	 	
 				$modeldomi->domicilio = $modeldomi->nomcalle.' '.$modeldomi->puerta.' '.$modeldomi->det.($modeldomi->piso != '' ? ' Piso: '.$modeldomi->piso : '');
				$modeldomi->domicilio .= ($modeldomi->dpto !='' ? ' Dpto: '.$modeldomi->dpto.' - ' : '').utb::getCampo("domi_localidad","loc_id=".$modeldomi->loc_id,"nombre");
				$modeldomi->domicilio .= ' - '.utb::getCampo("domi_provincia","prov_id=".$modeldomi->prov_id,"nombre");
 	
 				echo '<script>$("#txResDomi").val("'.$modeldomi->domicilio.'")</script>'; 
 				echo '<script>$("#arrayDomi").val("'.urlencode(serialize($modeldomi)).'")</script>';
    		}
    	Pjax::end();
    	
    	echo '<input type="text" name="arrayDomi" id="arrayDomi" value="'.urlencode(serialize($modeldomi)).'" style="display:none">';
    ?>
    
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
		<label><u>Datos del Convenio</u></label>
		
		<table border='0'>
		<tr>
			<?= $form->field($model, 'plan_id',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['disabled'=>true,'style' => 'width:85px; background:#E6E6FA;']) ?>
			<?= $form->field($model, 'tplan',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['disabled'=>true,'style' => 'width:40px; background:#E6E6FA;']) ?>
			<td valign='bottom'><?= Html::input('text', 'txTPlan_Nom', utb::getCampo('plan_config','cod='.($model->tplan =='' ? 0 : $model->tplan)), ['class' => 'form-control','id'=>'txTPlan_Nom', 'disabled'=>'true','style'=>'width:330px; background:#E6E6FA;']); ?></td>
			<?= $form->field($model, 'planant',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['disabled'=>true,'style' => 'width:100px;background:#E6E6FA;']) ?>
			<td>
				<label>Estado: </label><br>
				<?= Html::input('text', 'txEst_Nom', utb::getCampo('plan_test','cod='.($model->est =='' ? 0 : $model->est)), ['class' => ($model->est == 2 ? 'form-control baja' : 'form-control solo-lectura'),'id'=>'txEst_Nom', 'disabled'=>'true','style'=>'width:80px; background:#E6E6FA;']); ?>
			</td>
		</tr>
		<tr>
			<td colspan='5'>
				<label>Origen: </label>
				<?= Html::input('text', 'txOrigen_Nom', utb::getCampo('plan_torigen','cod='.($model->origen =='' ? 0 : $model->origen)), ['class' => 'form-control','id'=>'txOrigen_Nom', 'disabled'=>'true','style'=>'width:90px; background:#E6E6FA;']); ?>
				<label>Objeto: </label>
				<?= Html::input('text', 'txObj_id', $model->obj_id, ['class' => 'form-control','id'=>'txObj_id', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;']); ?>
				<label>Contribuyente: </label>
				<?= Html::input('text', 'txContri_Nom', utb::getCampo('objeto',"obj_id='".($model->obj_id =='' ? 0 : $model->obj_id)."'"), ['class' => 'form-control','id'=>'txContri_Nom', 'disabled'=>'true','style'=>'width:295px; background:#E6E6FA;']); ?>
			</td>
		</tr>
		<tr>
			<td><label>Forma de Pago</label></td>
			<td colspan='4'>
				<?= Html::dropDownList('dlTPago', $model->tpago, utb::getAux('plan_tpago'), 
    					['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlTPago', 'disabled'=>'true', 
							'onchange' => 'SelectTPago();']); ?>
				<label>Caja:</label>
				<?= Html::dropDownList('dlCaja', $model->caja_id, utb::getAux('caja','caja_id','nombre',0,"Tipo > 2 and est='A'"), 
    					['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlCaja',  'disabled'=>'true',
							'onchange' => 'SelectCaja();']); ?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan='4'>
				<?php 
				Pjax::begin(['id' => 'DivDatosCaja']);
					$caja = (isset($_POST['caja']) ? $_POST['caja'] : $model->caja_id);
					if ($caja == '') $caja = 0;
					$tcaja = utb::getCampo('caja','caja_id='.$caja,'tipo');
					$bco_ent = utb::getCampo('caja','caja_id='.$caja,'Ext_Bco_Ent');
				?>
				<div id='DivCHab' style='display:none'>
					<label>Emp.:</label>
					<?= Html::dropDownList('dlEmp', $model->temple, utb::getAux('plan_temple','cod','nombre',0,"caja_id=".$caja), 
    						['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlEmp',  
								'disabled'=>($consulta == 3 ? false : true) ]); ?>
					<label>Area</label>
					<?= Html::input('text', 'txArea', $model->temple_area, ['class' => 'form-control','id'=>'txArea', 
							'disabled'=>($consulta == 3 ? false : true), 'maxlength'=>'20', 'style'=>'width:100px;']); ?>
					<label>Legajo</label>
					<?= Html::input('text', 'txLegajo', $model->tpago_nro, ['class' => 'form-control','id'=>'txLegajo', 
							'disabled'=> ($consulta == 3 ? false : true), 'maxlength'=>'22', 'style'=>'width:100px;']); ?>
				</div>
				<div id='DivTC' style='display:none'>
					<label>Nº de Tarjeta</label>
					<?= Html::input('text', 'txNroTarj', $model->tpago_nro, ['class' => 'form-control','id'=>'txNroTarj', 
							'disabled'=> ($consulta == 3 ? false : true), 'maxlength'=>'22', 'style'=>'width:80px;']); ?>
				</div>
				<div id='DivDebito' style='display:none'>
					<label>Suc.</label>
					<?= Html::input('text', 'txSuc', $model->bco_suc, ['class' => 'form-control','id'=>'txSuc', 
							'disabled'=> ($consulta == 3 ? false : true), 
							'onChange' => '$.pjax.reload({container:"#DivNomSuc",data:{suc:this.value,ent:'.$bco_ent.'},method:"POST"});',
							'maxlength'=>'4', 'style'=>'width:30px;']); ?>
					<?php
						Modal::begin([
        					'id' => 'BuscaSuc',
							'toggleButton' => [
            				'label' => '<i class="glyphicon glyphicon-search"></i>',
                			'class' => 'bt-buscar',
                			'id' => 'btBuscarSuc',
                			'disabled' => ($consulta == 3 ? false : true)
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
        			<?= Html::input('text', 'txSuc_Nom', utb::getCampo('banco',"bco_ent=".($bco_ent =='' ? 0 : $bco_ent)." and bco_suc=".($model->bco_suc =='' ? 0 : $model->bco_suc)), 
        					['class' => 'form-control','id'=>'txSuc_Nom', 'disabled'=>($consulta == 3 ? false : true),
								'style'=>'width:170px; background:#E6E6FA;']);
        			?>
        			<label>Tipo:</label>
        			<?= Html::dropDownList('dlTCta', $model->bco_tcta, utb::getAux('banco_tcuenta'), 
    					['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlTCta','disabled'=>($consulta == 3 ? false : true)]); ?><br>
    				<label>Cta./CBU</label>
    				<?= Html::input('text', 'txNCta', $model->tpago_nro, ['class' => 'form-control','id'=>'txNCta', 
							'disabled'=>($consulta == 3 ? false : true),'maxlength'=>'22','style'=>'width:100px;']); ?>
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
		<tr><td colspan='5'><label><u>Responsable</u></label></td></tr>
		<tr>
			<td colspan='5'>
				<div id='DivResp'>
					<label>Doc:</label>
					<?= Html::dropDownList('dlResTDoc', $model->resptdoc, utb::getAux('persona_tdoc'), 
    					['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlResTDoc','disabled'=>'true','style'=>'width:80px']); ?>
    				<?= Html::input('text', 'txResNDoc', $model->respndoc, ['class' => 'form-control','id'=>'txResNDoc', 'disabled'=>'true','maxlength'=>'11','style'=>'width:80px;']); ?>
    				<label>Nombre:</label>
    				<?= Html::input('text', 'txResNombre', $model->resp, ['class' => 'form-control','id'=>'txResNombre', 'disabled'=>'true','maxlength'=>'45','style'=>'width:225px;']); ?>
    				<label>Distrib:</label>
    				<?= Html::dropDownList('dlDistrib', $model->distrib, utb::getAux('Sam.Sis_Usuario','usr_id','apenom',0,'Distrib=1'), 
    					['prompt'=>'Seleccionar...','class' => 'form-control', 'id'=>'dlDistrib','disabled'=>'true','style'=>'width:100px;float:right']); ?>
				
					<label>Domicilio</label>
					<?php
						Modal::begin([
        					'id' => 'BuscaDomi',
							'toggleButton' => [
            				'label' => '<i class="glyphicon glyphicon-search"></i>',
                			'class' => 'bt-buscar',
                			'id' => 'btBuscarDomi',
                			'disabled' => true
            				],
            				'closeButton' => [
            				'label' => '<b>X</b>',
                			'class' => 'btn btn-danger btn-sm pull-right',
            				],
            				'size' => 'modal-lg',
        				]);
          
        				echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modeldomi, 'tor' => 'PLA']);
           
       					Modal::end();
        			?>
        			<?= Html::input('text', 'txResDomi', (isset($modeldomi->domicilio) ? $modeldomi->domicilio : null), ['class' => 'form-control','id'=>'txResDomi', 'disabled'=>'true','style'=>'width:395px; background:#E6E6FA;']); ?>
        			<label>Teléfono:</label>
        			<?= Html::input('text', 'txResTel', $model->resptel, ['class' => 'form-control','id'=>'txResTel', 'disabled'=>'true','maxlength'=>'15','style'=>'width:100px;float:right']); ?>
				</div>
			</td>
		</tr>
		</table>
		<table width='100%'>
		<tr><td colspan='6'><label><u>Fechas</u></label></td></tr>
		<tr>
			<td>
				<label>Alta</label><br>
				<?= Html::input('text', 'txFchAlta', utb::getFormatoModif($model->usralta,$model->fchalta), ['class' => 'form-control','id'=>'txFchAlta', 'disabled'=>'true','style'=>'width:170px; background:#E6E6FA;']); ?>
			</td>
			<td>	
				<label>Modificación</label><br>
				<?= Html::input('text', 'txFchModif', utb::getFormatoModif($model->usrmod,$model->fchmod), ['class' => 'form-control','id'=>'txFchModif', 'disabled'=>'true','style'=>'width:170px; background:#E6E6FA;']); ?>
			</td>
			<td>
				<label>Consolida</label><br>
				<?= Html::input('text', 'txFchCons', ($model->fchconsolida != '' ? date_format(date_create($model->fchconsolida),'d/m/Y') : null), ['class' => 'form-control','id'=>'txFchCons', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;']); ?>
			</td>
			<td>	
				<label>Decae</label><br>
				<?= Html::input('text', 'txFchDecae', ($model->fchdecae != '' ? date_format(date_create($model->fchdecae),'d/m/Y') : null), ['class' => 'form-control','id'=>'txFchDecae', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;']); ?>
			</td>
			<td>	
				<label>Imputa</label><br>
				<?= Html::input('text', 'txFchImp', ($model->fchimputa != '' ? date_format(date_create($model->fchimputa),'d/m/Y') : null), ['class' => 'form-control','id'=>'txFchImp', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;']); ?>
			</td>
			<td>	
				<label>Baja</label><br>
				<?= Html::input('text', 'txFchBaja',($model->fchbaja != '' ?  date_format(date_create($model->fchbaja),'d/m/Y') : null), ['class' => 'form-control','id'=>'txFchBaja', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;float:right']); ?>
			</td>
		</tr>
		</table>
	</div>
	
	<div class="form" style='padding:5px 10px; margin-bottom:5px; float:left;'>
		<label><u>Detalle de Deuda</u></label><br>
		<table>
		<tr>
			<td>
				<label style='width:49px'>Nominal</label>
				<?= Html::input('text', 'txNominal', $model->nominal, ['class' => 'form-control','id'=>'txNominal', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
				<label style='width:49px'>Accesor</label>
				<?= Html::input('text', 'txAccesor', $model->accesor, ['class' => 'form-control','id'=>'txAccesor', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
				<label style='width:49px'>Multa</label>
				<?= Html::input('text', 'txMulta', $model->multa, ['class' => 'form-control','id'=>'txMulta', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
				<label style='width:49px'>Total</label>
				<?= Html::input('text', 'txTotal', $model->capital, ['class' => 'form-control','id'=>'txTotal', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?>
			</td>
			<td width='10px'></td>
			<td>
				<label style='width:55px'>Financiac</label>
				<?= Html::input('text', 'txFinanc', $model->financia, ['class' => 'form-control','id'=>'txFinanc', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
				<label style='width:55px'>Sellado</label>
				<?= Html::input('text', 'txSellado', $model->sellado, ['class' => 'form-control','id'=>'txSellado', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
				<label style='width:55px'>A Pagar</label>
				<?= Html::input('text', 'txAPagar', ($model->capital+$model->financia), ['class' => 'form-control','id'=>'txAPagar', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
				<label style='width:55px'>Anticipo</label>
				<?= Html::input('text', 'txAnticipo', $model->anticipo, ['class' => 'form-control','id'=>'txAnticipo', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?>
			</td>
		</tr>
		</table>
	</div>
	<div class="form" style='padding:5px 10px; margin-bottom:5px;float:left; margin-left:5px'>
		<label><u>Quita e Interes</u></label><br>
		<label style='width:49px'>Nominal</label>
		<?= Html::input('text', 'txDescNominal', $model->descnominal, ['class' => 'form-control','id'=>'txDescNominal', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		<label style='width:49px'>Accesor</label>
		<?= Html::input('text', 'txDescAcc', $model->descinteres, ['class' => 'form-control','id'=>'txDescAcc', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		<label style='width:49px'>Multa</label>
		<?= Html::input('text', 'txDescMulta', $model->descmulta, ['class' => 'form-control','id'=>'txDescMulta', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?><br>
		<label style='width:49px'>Interes</label>
		<?= Html::input('text', 'txDescInteres', $model->interes, ['class' => 'form-control','id'=>'txDescInteres', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?>
	</div>
	<div class="form" style='padding:5px 10px; margin-bottom:5px;float:left; margin-left:5px'>
		<label><u>Resumen de Cuotas</u></label><br>
		<table>
		<tr>
			<td><label>Cantidad</label></td>
			<td></td>
			<td><?= Html::input('text', 'txCtasCant', $model->cuotas, ['class' => 'form-control','id'=>'txCtasCant', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?></td>
		</tr>
		<tr>
			<td><label>Valor por Cuota</label></td>
			<td></td>
			<td><?= Html::input('text', 'txCtasValor', $model->montocuo, ['class' => 'form-control','id'=>'txCtasValor', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?></td>
		</tr>	
		<tr>
			<td>
				<label>Pagado</label>
				<?= Html::input('text', 'txCtasPagas', $model->cuotaspagas, ['class' => 'form-control','id'=>'txCtasPagas', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?>
			</td>
			<td width='13px' align='center'>$</td>
			<td><?= Html::input('text', 'txPagado', $model->pagado, ['class' => 'form-control','id'=>'txPagado', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?></td>
		</tr>
		<tr>
			<td>
				<label style='width:43px'>Saldo</label>
				<?= Html::input('text', 'txCtasFalta', $model->cuotasfalta, ['class' => 'form-control','id'=>'txCtasFalta', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?>
			</td>
			<td width='13px' align='center'>$</td>
			<td><?= Html::input('text', 'txSaldo', $model->saldo, ['class' => 'form-control','id'=>'txSaldo', 'disabled'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?></td>
		</tr>	
		</table>
	</div>
	<br><div id="errorplan" style="display:none;overflow:hidden" class="alert alert-danger alert-dismissable"></div>
	<?php
		
	// muestro tab de datos
	echo Tabs :: widget ([ 
 		'id' => 'TabPlan',
		'items' => [ 
 			['label' => 'Cuota', 
 			'content' =>  GridView::widget([
							'dataProvider' => $cuotas,
							'id' => 'TabPlanGrillaCuota',
							'headerRowOptions' => ['class' => 'grilla'],
							'rowOptions' => function ($model,$key,$index,$grid) 
											{
												return ['onclick' => 'cuotaselect('.$index.');$.pjax.reload({container:"#DivSelectCuota",' .
																		'data:{cuota:"'.$model['cuota'].'"},method:"POST"})'];
											},
        					'columns' => [
            					['attribute'=>'cuota_nom','header' => 'Cuota','contentOptions'=>['class' => 'grilla','style'=>'text-align:center']],
            					['attribute'=>'capital','header' => 'Capital','contentOptions'=>['class' => 'grilla','style'=>'text-align:right']],
            					['attribute'=>'financia','header' => 'Financia','contentOptions'=>['class' => 'grilla','style'=>'text-align:right']],
            					['attribute'=>'sellado','header' => 'Sellado','contentOptions'=>['class' => 'grilla','style'=>'text-align:right']],
            					['attribute'=>'total','header' => 'Total','contentOptions'=>['class' => 'grilla','style'=>'text-align:right']],
            					['attribute'=>'fchvenc_format','header' => 'Vencimiento','contentOptions'=>['class' => 'grilla','style'=>'text-align:center']],
            					['attribute'=>'est','header' => 'Est','contentOptions'=>['class' => 'grilla','style'=>'text-align:center']],
            					['attribute'=>'fchpago_format','header' => 'Pago','contentOptions'=>['class' => 'grilla','style'=>'text-align:center']],
            					['attribute'=>'caja_id','header' => 'Caja','contentOptions'=>['class' => 'grilla','style'=>'text-align:center']],
            					['attribute'=>'deb_est','header' => 'Est. Deb.','contentOptions'=>['class' => 'grilla','style'=>'text-align:center']],
								
								['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:20px;padding:1px 10px'],'template' => '{update}',
									'buttons'=>[
										'update' => function($url,$model,$key)
												  {
													if ($model['est'] == 'D' and utb::getExisteProceso(3354))
														return Html::button('<span class="glyphicon glyphicon-pencil"></span>',
																	['style' => 'font-size:8px','onclick' => 'ModificarVencCuota('.$model['ctacte_id'].",".$model['plan_id'].",".$model['cuota'].',"'.$model['fchvenc_format'].'")','class'=>'bt-buscar-label']
																);
												  }
									]
								],
							],
   						 ]),
 			'options' => ['class'=>'tabItem']
 			],
 			['label' => 'Periodos' , 
 			'content' => GridView::widget([
							'dataProvider' => $periodos,
							'headerRowOptions' => ['class' => 'grilla'],
							'rowOptions' => ['class' => 'grilla'],
        					'columns' => [
            					['attribute'=>'trib_nom','header' => 'Tributo'],
            					['attribute'=>'obj_id','header' => 'Objeto','contentOptions'=>['style'=>'text-align:center']],
            					['attribute'=>'subcta','header' => 'Cta','contentOptions'=>['style'=>'text-align:center']],
            					['attribute'=>'anio','header' => 'Año','contentOptions'=>['style'=>'text-align:center']],
            					['attribute'=>'cuota','header' => 'Cuota','contentOptions'=>['style'=>'text-align:center']],
            					['attribute'=>'est','header' => 'Est','contentOptions'=>['style'=>'text-align:center']],
            					['attribute'=>'nominal','header' => 'Nominal','contentOptions'=>['style'=>'text-align:right']],
            					['attribute'=>'accesor','header' => 'Accesor','contentOptions'=>['style'=>'text-align:right']],
            					['attribute'=>'multa','header' => 'Multa','contentOptions'=>['style'=>'text-align:right']],
            					['attribute'=>'qnom','header' => 'Q.Nom','contentOptions'=>['style'=>'text-align:right']],
            					['attribute'=>'qacc','header' => 'Q.Acc','contentOptions'=>['style'=>'text-align:right']],
            					['attribute'=>'qmul','header' => 'Q.Mul','contentOptions'=>['style'=>'text-align:right']],
            					['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'text-align:right']],
					        ],
   						 ]),
 			'options' => ['class'=>'tabItem'],
 			],
 			
 			['label' => 'Observaciones' , 
 			'content' => Html::checkbox('ckIncObs',false,['id'=>'ckIncObs','disabled'  => 'true', 'label'=>'Incluir Observación en el texto del contrato'])."<br>". 
 						 Html::textarea('txObs', $model->obs, 
 							['class' => 'form-control','id'=>'txObs', 'disabled'=>'true','maxlength'=>'500',
								'style'=>'width:600px; height:100px; resize:none']),
 			'options' => ['class'=>'tabItem'],
 			'active' => $consulta == 3 ? true : false,
 			]
 		]
	]);  
	
	if(isset($error) and $error !== '')
	{  
		echo '<div class="error-summary" style="overflow:hidden">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
	}
	
	if ($consulta == 3)
	{
		Pjax::begin(['id' => 'DivBtn']);
			$tcaja = (isset($_POST['tcaja']) ? $_POST['tcaja'] : 0);
			echo "<br>";
			echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'btAceptarClick('.$tcaja.')']);
			echo "&nbsp;&nbsp;";
			echo Html::a('Cancelar', ['plan', 'id' => $model->plan_id], [
         			'class' => 'btn btn-primary',
        		]);
        Pjax::end();
	}
	
	ActiveForm::end();
	
	if ($consulta == 3)
	{
		echo "<script>";
		echo '$("#dlTPago").attr("disabled",false);';
		echo '$("#dlResTDoc").attr("disabled",false);';
		echo '$("#txResNDoc").attr("disabled",false);';
		echo '$("#txResNombre").attr("disabled",false);';
		echo '$("#dlDistrib").attr("disabled",false);';
		echo '$("#btBuscarDomi").attr("disabled",false);';
		echo '$("#txResTel").attr("disabled",false);';
		echo '$("#ckIncObs").attr("disabled",false);';
		echo '$("#txObs").attr("disabled",false);';
		echo '$("#TabPlan li").eq(0).css("pointer-events","none");';
		echo '$("#TabPlan li").eq(1).css("pointer-events","none");';
		echo "</script>";
	}
	?>
</div>

<?php 
echo $this->render('_edicionvenccuota');
?>

<script>
SelectTPago();

function SelectTPago()
{
	$('#dlCaja').val("<?= $model->caja_id ?>");
	$('#dlCaja').attr('disabled',($('#dlTPago').val() != 3))
	<?php if ($consulta == 1 or $consulta == 2){ ?>
		$('#dlCaja').attr('disabled',true)
	<?php } ?>	
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

function btAceptarClick(tcaja)
{
	error = '';
	
	if ($('#dlTPago').val() == 3 && $('#dlCaja').val() == '') error += '<li>La Caja es obligatoria según Forma de Pago</li>';
	if ($('#dlTPago').val() != 3)
	{
		$('#dlCaja').val('');
		SelectCaja();
		$('#txSuc').val('');
		$('#dlTCta').val('');
		$('#txNCta').val('');
		$('#txNroTarj').val('');
		$('#dlEmp').val('');
		$('#txArea').val('');
		$('#txLegajo').val('');
	}
	if ($('#dlTPago').val() == 3)
	{
		if (tcaja == 3)
		{
			//if ($('#txSuc_Nom').val() == '') error += '<li>Ingrese una Sucursal válida</li>';
			//if ($('#dlTCta').val() == '') error += '<li>Debe seleccionar el Tipo de Cuenta</li>';
			if ($('#txNCta').val() == '') error += '<li>Ingrese un Número de Cuenta</li>';
		}
		if (tcaja == 4)
		{
			if ($('#txNroTarj').val() == '') error += '<li>Ingrese el Nº Tarjeta de Crédito</li>';
		}
		if (tcaja == 5)
		{
			if ($('#dlEmp').val() == '') error += '<li>Debe seleccionar el Tipo de Empleado</li>';
			if ($('#txArea').val() == '') error += '<li>Ingrese el Area del Empleado</li>';
		}
	}
	
	if (error == '')
	{
		$("#formPlan").submit();
	}else {
		$("#errorplan").html(error);
		$("#errorplan").css("display", "block");
	}
}

function ModificarVencCuota(cc,p,c,fv)
{
	$("#ctacte_id").val(cc);
	$("#plan_id").val(p);
	$("#cuota").val(c);
	$("#fchvenc").val(fv);
	$("#ModalEditarVencCuota").modal("show");
}
</script>