<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\Session;
use app\models\caja\CajaTicket;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use \yii\bootstrap\Modal;

$title = 'Listado Cheque Cartera';
$this->params['breadcrumbs'][] = 'Resultado';


$dia= date('Y/m/d');
?>

<table width="100%">
	<tr>
		<td><h1><?= Html::encode($title) ?></h1></td>
		<td align="right">
			<?php
				
				echo Html::a('Imprimir', ['//site/pdflist', 'format' => 'A4-L'], [
						'id' => 'listadoChequeCartera_btImprimir',
						'class' => 'btn btn-success disabled',
						'target' => '_black',
					]);
				
				Modal::begin([
		            'id' => 'Exportar', 
					'header' => '<h2>Exportar Datos</h2>',
					'toggleButton' => [
						'id' => 'listadoChequeCartera_btExportar',
		                'label' => 'Exportar',
		                'class' => 'btn btn-success disabled',
		            ],
		            'closeButton' => [
		              'label' => '<b>X</b>',
		              'class' => 'btn btn-danger btn-sm pull-right',
		            ]
		        ]);
		        
		        	echo $this->render('//site/exportar',['titulo'=>'Listado de Cheque Cartera','desc'=>'','grilla'=>'Exportar']);
		        	
		        Modal::end();
			
				echo Html::a('Volver', ['chequecartera', 'consulta'=>1],['class' => 'btn btn-primary']);
			?>
		</td>
	</tr>
							
</table>

<div class="form" style="padding-bottom: 8px">

<table>
	<tr>
		<td width="120px"><?= Html::checkbox('ckEstado',null,['id'=>'ckEstado','label'=>'Estado:','onchange'=>'validarCheck()']) ?></td>
		<td width="100px"><?= Html::radio('rEstado',true,['id'=>'chequeCartera_estado-cartera','label'=>'Cartera']); ?></td>
		<td width="100px"><?= Html::radio('rEstado',false,['id'=>'chequeCartera_estado-pagado','label'=>'Pagado']); ?></td>
		<td width="100px"><?= Html::radio('rEstado',false,['id'=>'chequeCartera_estado-baja','label'=>'Baja']); ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="120px"><?= Html::checkbox('ckPlan',null,['id'=>'ckPlan','label'=>'Plan:','onchange'=>'validarCheck()']) ?></td>
		<td width="60px"><?= Html::input('text','chequeCartera_txPlan',null,['id'=>'chequeCartera_txPlan','class'=>'form-control','disabled'=>true,'style'=>'width:120px', 'onkeypress'=>'return justNumbers(event)','maxlength'=>6]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="120px">
			<?= Html::checkbox('ckCheque',null,['id'=>'ckCheque','label'=>'Nro. Cheque:','onchange'=>'validarCheck()']) ?></td>
		<td width="100px">
			<?= Html::input('text','chequeCartera_txCheque',null,[
					'id'=>'chequeCartera_txCheque',
					'style' => 'width: 120px',
					'class'=>'form-control',
					'disabled'=>true,
					'maxlength' => 15,
				]);
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="120px"><?= Html::checkbox('ckFechaAlta',null,['id'=>'ckFechaAlta','label'=>'Fecha de Alta:','onchange'=>'validarCheck()']) ?></td>
		<td><label>Desde&nbsp;</label></td>
		<td width="80px"><?= 
					DatePicker::widget(
						[
							'id' => 'chequeCartera_txFechaAltaDesde',
							'name' => 'txFechaDesde',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
							'value'=> $dia,
						]
					);
				?></td>
		<td width="10px"></td>
		<td><label>Hasta&nbsp;</label></td>
		<td width="80px"><?= 
					DatePicker::widget(
						[
							'id' => 'chequeCartera_txFechaAltaHasta',
							'name' => 'txFechaDesde',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
							'value'=> $dia,
						]
					);
				?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="120px"><?= Html::checkbox('ckFechaCobro',null,['id'=>'ckFechaCobro','label'=>'Fecha de Cobro:','onchange'=>'validarCheck()']) ?></td>
		<td><label>Desde&nbsp;</label></td>
		<td width="80px"><?= 
					DatePicker::widget(
						[
							'id' => 'chequeCartera_txFechaCobroDesde',
							'name' => 'txFechaDesde',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
							'value'=> $dia,
						]
					);
				?></td>
		<td width="10px"></td>
		<td><label>Hasta&nbsp;</label></td>
		<td width="80px"><?= 
					DatePicker::widget(
						[
							'id' => 'chequeCartera_txFechaCobroHasta',
							'name' => 'txFechaDesde',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
							'value'=> $dia,
						]
					);
				?></td>
	</tr>
</table>



</div>

<div style="margin-top: 8px; margin-bottom: 8px">
	<?= Html::button('Aceptar',['id'=>'bt-BuscaDatos','onclick'=>'buscarDatos()', 'class'=>'btn btn-success']); ?>
</div>

<!-- INICIO Div Grilla -->

<div id="listadoChequeCartera_grilla" style="margin-top:8px">

<?php 

	Pjax::begin(['id'=>'grilla_ChequeCartera']);
				
		if (isset($_POST['condicion']) && $_POST['condicion'] !== '')
		{
			$cond = $_POST['condicion'];
			$dataprovider = $model->listarChequeCartera($cond);
			Yii::$app->session['titulo'] = "Listado de Personas";
			Yii::$app->session['sql'] = 'select * from v_caja_cheque_cartera '.($cond != '' ? ' where '.$cond : '');
			Yii::$app->session['columns'] = [
				['attribute'=>'cart_id','label' =>'Cod'],
	            ['attribute'=>'plan_id','label' => 'Conv.'],
	            ['attribute'=>'plan_id2','label' => 'Conv. 2'],
	            ['attribute'=>'nrocheque','label' => 'Cheque'],
	            ['attribute'=>'monto','label' => 'Monto'],
	            ['attribute'=>'bco_ent_nom','label' => 'Banco'],
                ['attribute'=>'bco_cta','label' => 'Cuenta'],
                ['attribute'=>'titular','label' => 'Titular'],
                ['attribute'=>'fchalta','label' => 'Alta'],
                ['attribute'=>'fchcobro','label' => 'Cobro'],
                ['attribute'=>'est','label' => 'Est'],	
	    		    
	        ];
	        
			echo GridView::widget([
							    'dataProvider' => $dataprovider,
							    'headerRowOptions' => ['class' => 'grilla'],
								'rowOptions' => ['class' => 'grilla'],
							    'columns' => [
						            ['attribute'=>'cart_id','label' =>'Cod'],
						            ['attribute'=>'plan_id','label' => 'Conv.'],
						            ['attribute'=>'plan_id2','label' => 'Conv. 2'],
						            ['attribute'=>'nrocheque','label' => 'Cheque'],
						            ['attribute'=>'monto','label' => 'Monto'],
						            ['attribute'=>'bco_ent_nom','label' => 'Banco'],
					                ['attribute'=>'bco_cta','label' => 'Cuenta'],
					                ['attribute'=>'titular','label' => 'Titular'],
					                ['attribute'=>'fchalta','label' => 'Alta'],
					                ['attribute'=>'fchcobro','label' => 'Cobro'],
					                ['attribute'=>'est','label' => 'Est'],	
									[
										'class' => 'yii\grid\ActionColumn',
										'contentOptions'=>['style'=>'width:6px'],
										'template' =>'{chequecartera}',
										'buttons'=>[			
											'chequecartera' =>  function($url, $model, $key)
														{
															return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
															
														},
										],
									]
								]
								]);
		}
		
	Pjax::end();
	
	
	
?>
</div>
<!-- FIN Div Grilla -->

<div id="listadoChequeCartera_errorSummary" class="error-summary" style="display:none;margin-top:8px">

	<ul>
	</ul>

</div>
			
<script>
function validarCheck()
{
	
	var ckEstado = $("#ckEstado").is(":checked");
	var ckPlan = $("#ckPlan").is(":checked");
	var ckCheque = $("#ckCheque").is(":checked");
	var ckFechaAlta = $("#ckFechaAlta").is(":checked");
	var ckFechaCobro = $("#ckFechaCobro").is(":checked"); 
	
	
	if (ckEstado)
	{

		$("#chequeCartera_estado-cartera").removeAttr('disabled');
		$("#chequeCartera_estado-pagado").removeAttr('disabled');
		$("#chequeCartera_estado-baja").removeAttr('disabled');
		
	} else 
	{
		$("#chequeCartera_estado-cartera").attr('disabled',true);
		$("#chequeCartera_estado-pagado").attr('disabled',true);
		$("#chequeCartera_estado-baja").attr('disabled',true);
	}
	
	if (ckPlan)
	{
		$("#chequeCartera_txPlan").removeAttr('disabled');
	} else
	{
		$("#chequeCartera_txPlan").attr('disabled',true);
	}

	if (ckCheque)
	{
		$("#chequeCartera_txCheque").removeAttr('disabled');
	} else
	{
		$("#chequeCartera_txCheque").attr('disabled',true);
	}
	
	if ($("#ckFechaAlta").is(":checked"))
	{
		$("#chequeCartera_txFechaAltaDesde").removeAttr('disabled');
		$("#chequeCartera_txFechaAltaHasta").removeAttr('disabled');
	} else 
	{
		$("#chequeCartera_txFechaAltaDesde").attr('disabled',true);
		$("#chequeCartera_txFechaAltaHasta").attr('disabled',true);
	}

	if ($("#ckFechaCobro").is(":checked"))
	{
		$("#chequeCartera_txFechaCobroDesde").removeAttr('disabled');
		$("#chequeCartera_txFechaCobroHasta").removeAttr('disabled');
	} else 
	{
		$("#chequeCartera_txFechaCobroDesde").attr('disabled',true);
		$("#chequeCartera_txFechaCobroHasta").attr('disabled',true);
	}	
	
}

validarCheck();


function buscarDatos()
{
	
	var ckEstado = $("#ckEstado").is(":checked"),
		ckPlan = $("#ckPlan").is(":checked"),
		ckCheque = $("#ckCheque").is(":checked"),
		ckFechaAlta = $("#ckFechaAlta").is(":checked"),
		ckFechaCobro = $("#ckFechaCobro").is(":checked"),
		cartera = $("#chequeCartera_estado-cartera").is(":checked"),
		pagado = $("#chequeCartera_estado-pagado").is(":checked"),
		baja = $("#chequeCartera_estado-baja").is(":checked"),
		
		plan = $("#chequeCartera_txPlan").val(),
		cheque = $("#chequeCartera_txCheque").val(),
		fechaAltaDesde = $("#chequeCartera_txFechaAltaDesde").val(),
		fechaAltaHasta = $("#chequeCartera_txFechaAltaHasta").val(),
		fechaCobroDesde = $("#chequeCartera_txFechaCobroDesde").val(),
		fechaCobroHasta = $("#chequeCartera_txFechaCobroHasta").val(),
		
		cond = '',
		error = new Array();
	
	if ( ckEstado )
	{
		if ( cond != '' ) cond += " AND ";
		
		if (cartera)
			cond +=	"est='C'";
		else if (pagado)
			cond += "est='P'";
		else if (baja)
			cond += "est='B'";	
	} 

	if ( ckPlan )
	{
		if ( plan.length < 1 || plan.length > 6 )
		{
			error.push("Ingrese un plan válido.");
			$("#chequeCartera_txPlan").val('');
		}
		
		if (cond != '') 
			cond += " AND ";
		
		cond += "plan_id = "+plan+" OR plan_id2 = "+plan;
	}
	
	if (ckCheque)
	{
		if (cheque.length < 1 || cheque.length > 60)
		{
			error.push("Ingrese un cheque válido.");
			$("#chequeCartera_txCheque").val('');
		} else
		{
			if (cond != '') cond += " AND ";
			cond += "nrocheque like '%"+cheque+"%'";
		}

	}
	
	if (ckFechaAlta)
	{
		if ( fechaAltaDesde == '' || fechaAltaHasta == '' )
		{
			error.push( "Ingrese una fecha de alta." );
		
		} else if ( ValidarRangoFechaJs( fechaAltaDesde, fechaAltaHasta ) == 0 )
		{
			if ( cond != '' ) cond += " AND ";
			cond += "fchalta::dae BETWEEN '"+fechaAltaDesde+"' AND '"+fechaAltaHasta+"'";
		} else 
		{
			error.push("Ingrese una fecha de alta válida.");
		}
	}
	
	if (ckFechaCobro)
	{
		
		if ( fechaCobroDesde == '' || fechaCobroHasta == '' )
		{
			error.push( "Ingrese una fecha de cobro." );
		
		} else if (ValidarRangoFechaJs(fechaCobroDesde,fechaCobroHasta) == 0)
		{
			if (cond != '') cond += " AND ";
			cond += "fchcobro::date BETWEEN '"+fechaCobroDesde+"' AND '"+fechaCobroHasta+"'";
		} else 
		{
			error.push("Ingrese una fecha de cobro válida.");
		}

	}
	
	if ( cond == '' && error.length == 0 )
		error.push( "Ingrese un criterio de búsqueda." );
		
	//Oculto el div de la grilla
	$("#listadoChequeCartera_grilla").css("display","none");
	
	//Deshabilitar los botones de "Imprimir" y "Exportar"
	$("#listadoChequeCartera_btImprimir").addClass("disabled");
	$("#listadoChequeCartera_btExportar").addClass("disabled");
	
	if ( error.length > 0 )
	{
		mostrarErrores( error, "#listadoChequeCartera_errorSummary" );
		
	} else
	{
		//Habilitar los botones de "Imprimir" y "Exportar"
		$("#listadoChequeCartera_btImprimir").removeClass("disabled");
		$("#listadoChequeCartera_btExportar").removeClass("disabled");
		
		//Oculto el div de errores	
		$("#listadoChequeCartera_errorSummary").css("display","none");
		
		//Muestro el div de la grilla
		$("#listadoChequeCartera_grilla").css("display","block");
		
		$.pjax.reload({
			container:"#grilla_ChequeCartera",
			method:"POST",
			data:{
				condicion:cond,
			}
		});
	}
}
</script>
			