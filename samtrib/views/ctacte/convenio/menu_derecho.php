<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use yii\web\Session;
use yii\widgets\ActiveForm;
?>
<style>
.btn-success{ color:#fff !important; }
</style>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3340)){ ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php

		 	Modal::begin([
    		'id' => 'opcBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Convenio</h2>',
			'toggleButton' => [
                    'label' => '<b>Buscar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
			]);

			echo $this->render('buscar');

			Modal::end();
		?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3341)){ ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['plannuevo'], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3349)){ ?>
	<li id='liNuevoAnt' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo Manual</b>', ['plannuevoant'], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3341)){ ?>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>', ['modificarplan', 'id' => $model->plan_id], ['class' => 'bt-buscar-label']) ?></li>
	<li id='liElim' class='glyphicon glyphicon-trash'>
		<?php
		 	Modal::begin([
    		'id' => 'opcEliminar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Emiminar Convenio</h2>',
			'toggleButton' => [
                    'label' => '<b>Eliminar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo "<center style='font-family:Helvetica Neue, Helvetica, Arial, sans-serif; color:#000; font-size:12px'>";
        	echo "<p><label>¿Esta seguro de dar de Baja el convenio?</label></p><br>";

			echo Html::a('Aceptar', ['borrarplan',
							'id' => $model->plan_id],
							[
            				'class' => 'btn btn-success',
            				'data' => [
                				'method' => 'post',
            					]
        			]);

        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEliminarPlan','onClick' => '$("#opcEliminar, .window").modal("toggle");']);
			echo "</center>";

			Modal::end();
		?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3343)){ ?>
	<li id='liImp' class='glyphicon glyphicon-share-alt'>
		<?php
		 	Modal::begin([
    		'id' => 'opcImputar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Imputar Convenio</h2>',
			'toggleButton' => [
                    'label' => '<b>Imputar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo "<center style='font-family:Helvetica Neue, Helvetica, Arial, sans-serif; color:#000; font-size:12px'>";
        	echo "<p><label>¿Esta seguro de Imputar el convenio?</label></p><br>";

			echo Html::a('Aceptar', ['imputarplan',
							'id' => $model->plan_id],
							[
            				'class' => 'btn btn-success',
            				'data' => [
                				'method' => 'post',
            					],
            				'style' => 'color:#fff !important'

        			]);

        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btImputarPlan','onClick' => '$("#opcImputar, .window").modal("toggle");']);
			echo "</center>";

			Modal::end();
		?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3344)){ ?>
	<li id='liDec' class='glyphicon glyphicon-arrow-down'>
		<?php
		 	Modal::begin([
    		'id' => 'opcDecae',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Decaer Convenio</h2>',
			'toggleButton' => [
                    'label' => '<b>Decaer</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo "<center style='font-family:Helvetica Neue, Helvetica, Arial, sans-serif; color:#000; font-size:12px'>";
        	echo "<p><label>¿Esta seguro de Decaer el convenio?</label></p><br>";

			echo Html::a('Aceptar', ['decaerplan',
							'id' => $model->plan_id],
							[
            				'class' => 'btn btn-success',
            				'data' => [
                				'method' => 'post',
            					],
            				'style' => 'color:#fff !important'

        			]);

        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btDecaerPlan','onClick' => '$("#opcDecae, .window").modal("toggle");']);
			echo "</center>";

			Modal::end();
		?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3345)){ ?>
	<li id='liAnula' class='glyphicon glyphicon-remove'>
		<?php
		 	Modal::begin([
    		'id' => 'opcAnulaImpDec',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Imputar Convenio</h2>',
			'toggleButton' => [
                    'label' => '<b>Anular</b>',
                    'class' => 'bt-buscar-label'
				],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo "<center style='font-family:Helvetica Neue, Helvetica, Arial, sans-serif; color:#000; font-size:12px'>";
        	echo "<p><label>¿Esta seguro de Anular el Decaimiento/Imputación?</label></p><br>";

			echo Html::a('Aceptar', ['anulaimputadecaeplan',
							'imputar'=>1,
							'id' => $model->plan_id],
							[
            				'class' => 'btn btn-success',
            				'data' => [
                				'method' => 'post',
            					],
            				'style' => 'color:#fff !important'

        			]);

        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btAnulaImputaDecaePlan','onClick' => '$("#opcAnulaImpDec, .window").modal("toggle");']);
			echo "</center>";

			Modal::end();
		?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3341) or utb::getExisteProceso(3349) or utb::getExisteProceso(3343) or utb::getExisteProceso(3344) or utb::getExisteProceso(3345)){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3341)){ ?>
	<li id='liAdelCuota' class='glyphicon glyphicon-ok-circle'>
		<?php
		 	$cantctasperm = $model->AdelantaPlanVer();

		 	Modal::begin([
    		'id' => 'opcAdelanta',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Adelantar Cuotas</h2>',
			'options' => ['style' => 'font-family:Helvetica Neue, Helvetica, Arial, sans-serif; color:#333;'],
			'toggleButton' => [
                    'label' => '<b>Adelantar</b>',
                    'class' => 'bt-buscar-label', 'data-pjax' => "0"],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
             'size' => (($cantctasperm == 0) ? 'modal-sm' : '' ),

			]);

			if ($cantctasperm > 0){
				echo $this->render('planadelanta', ['model' => $model, 'mejoras' => false,'cantctasperm' => $cantctasperm]);
			}else {
				echo "<center style='font-family:Helvetica Neue, Helvetica, Arial, sans-serif; color:#000; font-size:12px'>";
        		echo "<p><label>No se puede adelantar cuotas</label></p><br>";
        		echo Html::Button('Salir', ['class' => 'btn btn-primary', 'onClick' => '$("#opcAdelanta, .window").modal("toggle");']);
				echo "</center>";
			}

			Modal::end();
		?>
	</li>
	<li id='liElimAdelCuota' class='glyphicon glyphicon-remove-circle'>
		<?php
			Modal::begin([
    		'id' => 'opcEliminarAdelanto',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Eliminar Adelanto de cuota</h2>',
			'toggleButton' => [
                    'label' => '<b>Eliminar Adelanto</b>',
                    'class' => 'bt-buscar-label'
                ],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			Pjax::begin(['id' => 'DivSelectCuota']);
    			$cuotaselect = (isset($_POST['cuota']) ? $_POST['cuota'] : '');
				echo "<center style='font-family:Helvetica Neue, Helvetica, Arial, sans-serif; color:#000; font-size:12px'>";
	        	if ($cuotaselect == '')
	        	{
	        		echo "<p><label>Posiciónese sobre la cuota adelantada a eliminar</label></p><br>";
	        	}else {

	        		echo "<p><label>¿Esta seguro de Eliminar el adelanto de la cuota ".$cuotaselect."?</label></p><br>";

					echo Html::a('Aceptar', ['eliminaradelantacuota',
								'cuota'=>$cuotaselect,
								'id' => $model->plan_id],
								[
	            				'class' => 'btn btn-success',
	            				'data' => [
	                				'method' => 'post',
	            					],
	            				'style' => 'color:#fff !important'

	        				]);

	        		echo "&nbsp;&nbsp;";
				}
				echo Html::Button(($cuotaselect == '' ? 'Aceptar' : 'Cancelar'),
						['class' => ($cuotaselect == '' ? 'btn btn-success' : 'btn btn-primary'),
							'onClick' => '$("#opcEliminarAdelanto, .window").modal("toggle");']);
				echo "</center>";
			Pjax::end();
			Modal::end();
		?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3340)){ ?>
	<li id='liImprimirContrato' class='glyphicon glyphicon-print'>
		<?= Html::a('<b>Contrato</b>', ['imprimircontrato','id' => $model->plan_id],
				['class' => 'bt-buscar-label', 'data-pjax' => "0"]) ?>

	</li>
	<li id='liImprimirCuotas' class='glyphicon glyphicon-print'>
		<?php
			echo Html::a('<b>Cuotas</b>', null,
					['class' => 'bt-buscar-label','data-pjax' => "0", 'style' => 'cursor:pointer',
						'onclick' => '$.pjax.reload({container:"#ImpCuotas",data:{cta:1}, method:"POST"})']);

			Pjax::begin(['id' => 'ImpCuotas']);
			if (isset($_POST['cta'])){
				$session = new Session;
    			$session->open();
				$session['proceso_asig'] = 3340;
				$session['titulo'] = "Convenios - Listado de Cuotas";
				$session['condicion'] = "Convenio de Pago N°: " . $model->plan_id . " - Objeto: " .  utb::getCampo('objeto',"obj_id='".($model->obj_id =='' ? 0 : $model->obj_id)."'") . " - ";
				$session['condicion'] .= (utb::getTObj($model->obj_id) == 1 or utb::getTObj($model->obj_id) == 4 ? "Nomenclatura" : (utb::getTObj($model->obj_id) == 5 or utb::getTObj($model->obj_id) == 6 ? "Dominio" : (utb::getTObj($model->obj_id) == 3 ? "Documento" : (utb::getTObj($model->obj_id) == 2 ? "CUIT" : ""))));
				$session['condicion'] .= ": " . utb::getCampo('objeto',"obj_id='".($model->obj_id =='' ? 0 : $model->obj_id)."'",'obj_dato');
				$session['condicion'] .= "<br> Tipo Plan: " . utb::getCampo('plan_config','cod='.($model->tplan =='' ? 0 : $model->tplan)) . " - Fecha Consolidación: " . ($model->fchconsolida != '' ? date_format(date_create($model->fchconsolida),'d/m/Y') : '') . "<br>";
        		if(Trim($model->num) <> "") $session['condicion'] .= "Contribuyente: " . utb::getCampo('objeto',"obj_id='".$model->num."'");
        		if(Trim($model->resp) <> "") $session['condicion'] .= " - Responsable: " . $model->resp;

				$session['sql'] = 'Select * From V_Plan_Cuota where plan_id='.$model->plan_id. ' Order By Cuota ';
				$session['columns'] = [
					['attribute'=>'cuota_nom','header' => 'Cuota','contentOptions'=>['style'=>'width:60px;text-align:center']],
            		['attribute'=>'capital','header' => 'Capital','contentOptions'=>['style'=>'width:90px;text-align:right']],
            		['attribute'=>'financia','header' => 'Financia','contentOptions'=>['style'=>'width:90px;text-align:right']],
            		['attribute'=>'sellado','header' => 'Sellado','contentOptions'=>['style'=>'width:90px;text-align:right']],
            		['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'width:90px;text-align:right']],
            		['attribute'=>'fchvenc_format','header' => 'Vencimiento','contentOptions'=>['style'=>'width:80px;text-align:center']],
            		['attribute'=>'est','header' => 'Est','contentOptions'=>['style'=>'width:50px;text-align:center']],
            		['attribute'=>'fchpago_format','header' => 'Pago','contentOptions'=>['style'=>'width:80px;text-align:center']],
            		['attribute'=>'caja_id','header' => 'Caja','contentOptions'=>['style'=>'width:50px;text-align:center']],
            		['attribute'=>'deb_est','header' => 'Est. Deb.','contentOptions'=>['style'=>'width:100px;text-align:center']],
        		];

				$session->close();

				echo "<script>window.open('index.php?r=site/pdflist', '_blank');</script>";
			}
			Pjax::end();
		?>
	</li>
	<li id='liImprimirPeriodos' class='glyphicon glyphicon-print'>
		<?php
			echo Html::a('<b>Periodos</b>', null,
					['class' => 'bt-buscar-label', 'data-pjax' => "0", 'style' => 'cursor:pointer',
						'onclick' => '$.pjax.reload({container:"#ImpPeriodos", data:{periodo:1}, method:"POST"})']);

			Pjax::begin(['id' => 'ImpPeriodos']);
			if (isset($_POST['periodo'])){
				$session = new Session;
    			$session->open();
				$session['titulo'] = "Convenios - Listado de Periodos";
				$session['condicion'] = "Convenio de Pago N°: " . $model->plan_id . " - Objeto: " .  utb::getCampo('objeto',"obj_id='".($model->obj_id =='' ? 0 : $model->obj_id)."'") . " - ";
				$session['condicion'] .= (utb::getTObj($model->obj_id) == 1 or utb::getTObj($model->obj_id) == 4 ? "Nomenclatura" : (utb::getTObj($model->obj_id) == 5 or utb::getTObj($model->obj_id) == 6 ? "Dominio" : (utb::getTObj($model->obj_id) == 3 ? "Documento" : (utb::getTObj($model->obj_id) == 2 ? "CUIT" : ""))));
				$session['condicion'] .= ": " . utb::getCampo('objeto',"obj_id='".($model->obj_id =='' ? 0 : $model->obj_id)."'",'obj_dato');
				$session['condicion'] .= "<br> Tipo Plan: " . utb::getCampo('plan_config','cod='.($model->tplan =='' ? 0 : $model->tplan)) . " - Fecha Consolidación: " . ($model->fchconsolida != '' ? date_format(date_create($model->fchconsolida),'d/m/Y') : '') . "<br>";
        		if(Trim($model->num) <> "") $session['condicion'] .= "Contribuyente: " . utb::getCampo('objeto',"obj_id='".$model->num."'");
        		if(Trim($model->resp) <> "") $session['condicion'] .= " - Responsable: " . $model->resp;

				$sql = "Select CtaCte_Id, 'X' Marca, Trib_id, Trib_nom, Obj_id, SubCta, Anio, Cuota, EstAnt Est,";
		        $sql .= "cast(Nominal as decimal(12,2)), cast(Accesor as decimal(12,2)), cast(Multa as decimal(12,2)), cast(Total as decimal(12,2)),";
		        $sql .= " NominalReal-Nominal QNom, AccesorReal-Accesor QAcc, MultaReal-Multa QMul,";
		        $sql .= " NominalReal, AccesorReal, MultaReal,TotalReal, FchVenc, EstAnt, ";
		        $sql .= " cast(NominalCub as decimal(12,2)), cast(AccesorCub as decimal(12,2)), cast(MultaCub as decimal(12,2)),";
		        $sql .= " cast(TotalCub as decimal(12,2)), Saldo From V_Plan_Periodo where plan_id=".$model->plan_id;
		        $sql .= " Order By Trib_id, Anio, Cuota ";
				$session['sql'] = $sql;

				$session['dataprovider'] = $periodos->getModels();
				$nomreal = '';
				$accreal = '';
				$mulreal = '';
				$totreal = '';
				$nom = '';
				$acc = '';
				$mul = '';
				$tot = '';
				if (count($session['dataprovider'])  > 0)
				{
					$nomreal = 0;
					for ($i=0; $i < count($session['dataprovider']); $i++)
					{
						$nomreal += $session['dataprovider'][$i]['nominalreal'];
						$accreal += $session['dataprovider'][$i]['accesorreal'];
						$mulreal += $session['dataprovider'][$i]['multareal'];
						$totreal += $session['dataprovider'][$i]['totalreal'];
						$nom += $session['dataprovider'][$i]['nominal'];
						$acc += $session['dataprovider'][$i]['accesor'];
						$mul += $session['dataprovider'][$i]['multa'];
						$tot += $session['dataprovider'][$i]['total'];
					}
				}

				$session['columns'] = [
					['attribute'=>'obj_id','header' => 'Objeto','contentOptions'=>['style'=>'text-align:center']],
            		['attribute'=>'trib_nom','header' => 'Tributo','contentOptions'=>['style'=>'width:150px']],
            		['attribute'=>'subcta','header' => 'Cta','contentOptions'=>['style'=>'text-align:center']],
            		['attribute'=>'anio','header' => 'Año','contentOptions'=>['style'=>'text-align:center']],
            		['attribute'=>'cuota','header' => 'Cuota','contentOptions'=>['style'=>'text-align:center']],
            		['attribute'=>'nominalreal','header' => 'Nom. Real',
						'footer' => $nomreal,
						'footerOptions' => ['style' => 'text-align:right;border-top:1px solid #000'],
						'contentOptions'=>['style'=>'width:80px;text-align:right']
					],
            		['attribute'=>'accesorreal','header' => 'Acc. Real','footer' => $accreal,'footerOptions' => ['style' => 'text-align:right;border-top:1px solid #000'],'contentOptions'=>['style'=>'width:80px;text-align:right']],
            		['attribute'=>'multareal','header' => 'Mul. Real','footer' => $mulreal,'footerOptions' => ['style' => 'text-align:right;border-top:1px solid #000'],'contentOptions'=>['style'=>'width:80px;text-align:right']],
            		['attribute'=>'totalreal','header' => 'Tot. Real','footer' => $totreal,'footerOptions' => ['style' => 'text-align:right;border-top:1px solid #000'],'contentOptions'=>['width:80px;style'=>'text-align:right']],
            		['attribute'=>'nominal','header' => 'Nominal','footer' => $nom,'footerOptions' => ['style' => 'text-align:right;border-top:1px solid #000'],'contentOptions'=>['style'=>'text-align:right']],
            		['attribute'=>'accesor','header' => 'Accesor','footer' => number_format($acc,2),'footerOptions' => ['style' => 'text-align:right;border-top:1px solid #000'],'contentOptions'=>['style'=>'text-align:right']],
            		['attribute'=>'multa','header' => 'Multa','footer' => $mul,'footerOptions' => ['style' => 'text-align:right;border-top:1px solid #000'],'contentOptions'=>['style'=>'text-align:right']],
            		['attribute'=>'total','header' => 'Total','footer' => $tot,'footerOptions' => ['style' => 'text-align:right;border-top:1px solid #000'],'contentOptions'=>['style'=>'text-align:right']],
        		];

				$session->close();

				echo "<script>window.open('index.php?r=site/pdflist', '_blank');</script>";
			}
			Pjax::end();
		?>
	</li>
	<li id='liImprimir' class='glyphicon glyphicon-print'>
		<?php
			Modal::begin([
    		'id' => 'opcImprimirComprob',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Impresión de Chequeras</h2>',
			'toggleButton' => [
                    'label' => '<b>Comprobante</b>',
                    'class' => 'bt-buscar-label',
                ],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			$form = ActiveForm::begin(['action' => ['imprimircomprobantevalida', 'id' => $model->plan_id],'id' => 'formPlanImpComp',
    								'fieldConfig' => [
        										'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
        										],
        						]);
			echo "<div style='font-family:Helvetica Neue, Helvetica, Arial, sans-serif; padding:10px 20px; color:#000; font-size:12px'>";
        	echo "<p><label>Cuota Desde:</label>&nbsp;".Html::input('text', 'txCtaDesde', null, ['class' => 'form-control','id'=>'txCtaDesde','maxlength'=>'3', 'style'=>'width:40px;'])."</p>";
        	echo "<p><label>Cuota Hasta:</label>&nbsp;".Html::input('text', 'txCtaHasta', null, ['class' => 'form-control','id'=>'txCtaHasta','maxlength'=>'3', 'style'=>'width:40px;'])."</p>";

			echo Html::Button('Imprimir', ['class' => 'btn btn-success','id' => 'btImpComp', 'onclick' => 'btImpCompClick()', 'style' => 'color:#fff !important']);
        	echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar',['class' => 'btn btn-primary','onClick' => '$("#opcImprimirComprob, .window").modal("toggle");']);

			echo '<br><br><div id="errorplanimpcomp" style="display:none;overflow:hidden" class="alert alert-danger alert-dismissable"></div>';

			echo "</div>";
			ActiveForm::end();
			Modal::end();
		?>
	</li>
	<li id='liImprimirResumen' class='glyphicon glyphicon-print'>
		<?= Html::a('<b>Resumen</b>', ['imprimirresumen','id' => $model->plan_id],
				['class' => 'bt-buscar-label', 'target' => '_black', 'data-pjax' => "0"]) ?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)){ ?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index','obj_id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<li id='liMisc' class='glyphicon glyphicon-comment'> <?= Html::a('<b>Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php if (utb::getExisteProceso(3340)){ ?>
	<li id='liListado' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Listado</b>', ['//ctacte/listadoconveniodepago/index'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
</ul>

<?php
if (($model->plan_id == '' or $model->plan_id == 0) and $consulta == 1)
{
	// dashabilito todas las opciones
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';

	// y luego solo habilito buscar, nuevo y listado
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevoAnt").css("pointer-events", "all");</script>';
	echo '<script>$("#liListado").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevoAnt a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevoAnt").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado a").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado").css("color", "#337ab7");</script>';
}else
{
	if ($consulta !== 1)
	{
		// si se esta creado, modificando o eliminando => deshabilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	}else {
		// habilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';
		// si no esta vigente no se puede modificar, eliminar, imputar ni decaer el plan
		if ($model->est != 1) {
			echo '<script>$("#liModif").css("pointer-events", "none");</script>';
			echo '<script>$("#liModif a").css("color", "#ccc");</script>';
			echo '<script>$("#liElim").css("pointer-events", "none");</script>';
			echo '<script>$("#liImp").css("pointer-events", "none");</script>';
			echo '<script>$("#liDec").css("pointer-events", "none");</script>';
			echo '<script>$("#liModif").css("color", "#ccc");</script>';
			echo '<script>$("#liElim").css("color", "#ccc");</script>';
			echo '<script>$("#liImp").css("color", "#ccc");</script>';
			echo '<script>$("#liDec").css("color", "#ccc");</script>';
		}
		// si el plan esta decaído o imputado se muestra la opción para anular dichas acciones
		if ($model->est != 3 and $model->est != 5){
			echo '<script>$("#liAnula").css("pointer-events", "none");</script>';
			echo '<script>$("#liAnula").css("color", "#ccc");</script>';
		}
		// si el plan esta dado de baja no habilito la opción de adelanto
		if ($model->est == 2){
			echo '<script>$("#liAdelCuota").css("pointer-events", "none");</script>';
			echo '<script>$("#liAdelCuota").css("color", "#ccc");</script>';
		}
	}
}

?>

<script>
function btImpCompClick()
{
	error = "";
	if ($("#txCtaDesde").val() == "") error += "Ingrese la Cuota Desde<br>";
	if ($("#txCtaHasta").val() == "") error += "Ingrese la Cuota Hasta<br>";
	if (parseInt($("#txCtaDesde").val()) > parseInt($("#txCtaHasta").val())) error += "Rango de cuotas mal ingresado";

	if (error == '')
	{
		$("#formPlanImpComp").submit();
		$("#opcImprimirComprob, .window").modal("toggle");
	}else {
		$("#errorplanimpcomp").html(error);
		$("#errorplanimpcomp").css("display", "block");
	}
}

function cuotaselect(id)
{
	$("#TabPlanGrillaCuota tr").removeClass("success");

	n=0;
	$('#TabPlanGrillaCuota tr').each(function() {
	   if (n == id+1)
		  $(this).addClass("success");
	   n++;
	});
}
</script>
