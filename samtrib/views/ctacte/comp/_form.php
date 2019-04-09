<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;
use yii\data\ArrayDataProvider;


/**
 * Forma que se dibuja cuando se llega a Compensaciones
 * Recibo:
 * 			=> $model -> Modelo de Comp
 */

/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */

if ($consulta == 0)
	$title = 'Nueva Compensación';

if ($consulta == 3)
	$title = 'Modificar Compensación';

$this->params['breadcrumbs'][] = ['label' => 'Compensaciones', 'url' => ['view', 'id' => $model->comp_id]];
$this->params['breadcrumbs'][] = $title;

?>

<style>

.div_grilla_origen
{
	width: 400px;
	margin-left:150px;
	margin-top: 5px;
	margin-bottom: 5px;
}

.div_grilla_destino
{
	width: 600px;
	margin-left:50px;
	margin-top: 5px;
	margin-bottom: 5px;
}

</style>


<div class="compensacion_nuevo_info">
	<h1><?= Html::encode($title) ?></h1>

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorCompensaForm']);

			$mensaje = '';

			$mensaje = Yii::$app->request->post('mensaje','');
			$m = Yii::$app->request->post('m',2);


			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaMensajeCompensaForm',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaMensajeCompensaForm').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->
<div class="form-panel" style="padding-right:8px">
<table>
	<tr>
		<td width="50px"><label>Código:</label></td>
		<td width="50px">
			<?= Html::input('text','txCodigo',$model->comp_id,[
					'id'=>'compensacion_nuevo_txCodigo',
					'class'=>'form-control solo-lectura',
					'style'=>'width:100%;text-align:center',
					'tabindex' => -1,
				]);
			?>
		</td>
		<td width="15px"></td>
		<td width="35px"><label>Tipo:</label></td>
		<td width="140px">
			<?= Html::dropDownList('dlTipo',$model->tipo,utb::getAux('comp_tipo'),[
					'id'=>'compensacion_nuevo_dlTipo',
					'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
					'style'=>'width:100%;text-align:left',
					'onchange'=>'cambioTipo()',
					'tabindex' => ($consulta == 3 ? -1 : 0),
				]);
			?>
		</td>
		<td width="15px"></td>
		<td width="40px"><label>Expe:</label></td>
		<td width="60px">
			<?= Html::input('text','txExpe',$model->expe,[
					'id'=>'compensacion_nuevo_txExpe',
					'class'=>'form-control',
					'style'=>'width:80px;text-align:left',
					'maxlength' => 12,
				]);
			?>
		</td>
		<td width="15px"></td>
		<td width="60px"><label>Consolida:</label></td>
		<td>
			<?= DatePicker::widget([
					'id'=>'compensacion_nuevo_txFchconsolida',
					'name'=>'txFchconsolida',
					'dateFormat' => 'dd/MM/yyyy',
					'value' => Fecha::usuarioToDatePicker(Fecha::getDiaActual()),
					'options' => [
						'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
						'style'=>'width:80px;text-align:center',
						'tabindex' => ($consulta == 3 ? -1 : 0),
					],
				]);
			?>
		</td>
	</tr>
	<tr>
		<td width="50px" colspan="3"><label>Montos:</label></td>
		<td width="60px"><label>Origen:</label></td>
		<td>
			<?= Html::input('text','txOrigen',$model->monto,[
					'id'=>'compensacion_nuevo_txOrigen',
					'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
					'style'=>'width:100px;text-align:right',
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
					'maxlength' => 13,
					'tabindex' => ($consulta == 3 ? -1 : 0),
				]);
			?>
		</td>
		<td width="15px"></td>
		<td width="60px"><label>Aplicado:</label></td>
		<td>
			<?= Html::input('text','txAplicado',$model->monto_aplic,[
					'id'=>'compensacion_nuevo_txAplicado',
					'class'=>'form-control solo-lectura',
					'style'=>'width:80px;text-align:right',
					'tabindex' => -1,
				]);
			?>
		</td>
		<td width="15px"></td>
		<td width="50px"><label>Saldo:</label></td>
		<td>
			<?= Html::input('text','txSaldo',$model->saldo,[
					'id'=>'compensacion_nuevo_txSaldo',
					'class'=>'form-control solo-lectura',
					'style'=>'width:80px;text-align:right',
					'tabindex' => ($consulta == 3 ? -1 : 0),
				]);
			?>
		</td>
	</tr>
</table>
</div>

<div class="form-panel" style="padding-right:8px">
<table>
	<tr>
		<td width="40px"><label>Obs:</label></td>
		<td><?= Html::textarea('txObs',$model->obs,['id'=>'compensacion_nuevo_txObs','class'=>'form-control','style'=>'width:590px;max-width:590px;height:40px;max-height:120px']) ?> </td>
	</tr>
</table>
</div>

<!-- INICIO Grilla Origen -->
<?php

	if ($consulta == 3 && ($model->trib_ori != '' || $model->trib_ori != 0))
		echo '<div class="form-panel" style="padding-right:8px" id="grilla_origen">';
	else
		echo '<div class="form-panel" style="padding-right:8px;display:none" id="grilla_origen">';



	Pjax::begin(['id'=>'PjaxGrillaOrigen']);

		$cond = 'compensa = 1 ';

		//Obtengo los datos que llegan por post
		$origen_tipo = Yii::$app->request->post('origen_tipo',0);
		$origen_trib_id = Yii::$app->request->post('origen_trib_id',0);
		$origen_plan = Yii::$app->request->post('origen_plan',0);
		$origen_objID = Yii::$app->request->post('origen_objID','');
		$origen_anioDesde = Yii::$app->request->post('origen_anioDesde','');
		$origen_cuotaDesde = Yii::$app->request->post('origen_cuotaDesde','');
		$origen_anioHasta = Yii::$app->request->post('origen_anioHasta','');
		$origen_cuotaHasta = Yii::$app->request->post('origen_cuotaHasta','');

		if ( $origen_tipo != 0 )
		{
			if ($origen_tipo == 3)
			{
				//BuscarPerSaldos
				$arreglo = $model->buscarPerSaldos($origen_objID,$origen_trib_id,$origen_plan,$origen_anioDesde,$origen_cuotaDesde,$origen_anioHasta,$origen_cuotaHasta);	//Llegan dataProvider y monto

				$dataProviderOrigen = $arreglo['dataProvider'];
				$monto = $arreglo['monto'];

			} else if ($origen_tipo == 4)
			{
				//BuscarPerPagos
				$arreglo = $model->buscarPerPagos($origen_objID,$origen_trib_id,$origen_plan,$origen_anioDesde,$origen_cuotaDesde,$origen_anioHasta,$origen_cuotaHasta);	//Llegan dataProvider y monto

				$dataProviderOrigen = $arreglo['dataProvider'];
				$monto = $arreglo['monto'];
			}
		}

?>

<table>
	<tr>
		<td width="60px"><h3><label>Origen:</label></h3></td>
		<td width="30px"></td>
		<td width="40px"><label>Tributo:</label></td>
		<td width="200px"><?= Html::dropDownList('dlTribOrigen',$model->trib_ori,utb::getAux('trib','trib_id','nombre',1,$cond),
								[	'id'=>'compensacion_nuevo_dlTribOrigen',
									'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
									'style'=>'width:100%;text-align:left',
									'onchange'=>'cambioTribOrigen($(this).val());' .
									'limpiarEditOrigen();' .
									'$.pjax.reload({container:"#ObjOrigenNombre",method:"POST",data:{objeto_id:"",trib_id:$(this).val()}})']); ?></td>
		<td width="20px"></td>
		<td id="ObjetoOrigen" style="display:block">
			<label>Objeto:</label>
			<?= Html::input('text','txOrigenObjCod',$model->obj_ori,[
					'id' => 'compensacion_nuevo_txOrigenObjCod',
					'class' => 'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
					'style' => 'width:70px;text-align:center',
					'maxlength' => 8,
					'onchange' => '$.pjax.reload({container:"#ObjOrigenNombre",method:"POST",data:{objeto_id:$(this).val(),trib_id:$("#compensacion_nuevo_dlTribOrigen").val()}})',
				]);
			?>
			<!-- botón de búsqueda modal -->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',['class' => 'bt-buscar '.($consulta == 3 ? 'disabled' : ''),'id'=>'compensacion_nuevo_btBuscaObjOrigen','onclick'=>'$("#BuscaObjcompensacion_nuevo_btOrigenBusca, .window").modal("show")']); ?>
			<!-- fin de botón de búsqueda modal -->
			<?= Html::input('text','txOrigenObjNom',$model->obj_ori_nom,['id'=>'compensacion_nuevo_txOrigenObjNom','class'=>'form-control solo-lectura','style'=>'width:150px']); ?>
		</td>
		<td id="PlanOrigen" style="display:none">
			<label>Plan:</label>
			<?= Html::input('text','txPlanOrigen',$model->plan_origen,[
				 	'id'=>'compensacion_nuevo_txPlanOrigen',
				 	'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
				 	'style'=>'width:120px',
				]);
			?>
		</td>
		<td width="30px"></td>
		<td width="150px" id="botonCargarOrigen" style="display:none"><?= Html::button('Cargar Origen',['id'=>'btCargarOrigen','class'=>'btn btn-primary','onclick'=>'CargarOrigen()', 'disabled' => ($consulta == 3 ? 'disabled' : false)]) ?></td>
	</tr>
</table>
<table id="PeriodoOrigen" style="display:block">
	<tr>
		<td width="40px"><label>Desde:</label></td>
		<td>
			<?= Html::input('text','txOrigenAnioDesde',$model->aniodesde_origen,[
					'id'=>'compensacion_nuevo_txOrigenAnioDesde',
					'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
					'style'=>'width:50px',
					'maxlength' => 4,
					'onkeypress' => 'return justNumbers( event )',
				]);
			?>
		</td>
		<td>
			<?= Html::input('text','txOrigenCuotaDesde',$model->cuotadesde_origen,[
					'id'=>'compensacion_nuevo_txOrigenCuotaDesde',
					'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
					'style'=>'width:40px',
					'maxlength' => 3,
					'onkeypress' => 'return justNumbers( event )',
				]);
			?>
		</td>
		<td width="30px"></td>
		<td width="40px"><label>Hasta:</label></td>
		<td>
			<?= Html::input('text','txOrigenAnioHasta',$model->aniohasta_origen,[
					'id'=>'compensacion_nuevo_txOrigenAnioHasta',
					'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
					'style'=>'width:50px',
					'maxlength' => 4,
					'onkeypress' => 'return justNumbers( event )',
				]);
			?>
		</td>
		<td>
			<?= Html::input('text','txOrigenCuotaHasta',$model->cuotahasta_origen,[
					'id'=>'compensacion_nuevo_txOrigenCuotaHasta',
					'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
					'style'=>'width:40px',
					'maxlength' => 3,
					'onkeypress' => 'return justNumbers( event )',
				]);
			?>
		</td>
		<td align="right" width="350px"><?= Html::button('Cargar Origen',['id'=>'btCargarOrigen','class'=>'btn btn-primary','onclick'=>'CargarOrigen()',  'disabled' => ($consulta == 3 ? 'disabled' : false)]) ?></td>
	</tr>
</table>

<div class="div_grilla_origen">
<?php
	//Si se apreta el botón cargar alguna vez, se debe verificar si se muestra origen o plan
	if ($origen_tipo != 0)
		echo '<script>cambioTribOrigen($("#compensacion_nuevo_dlTribOrigen").val())</script>';

			echo GridView::widget([
				'id' => 'GrillaCompensacionOrigen',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProviderOrigen,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['content'=> function($model, $key, $index, $column) use ($consulta){return Html::checkbox('compensacion_nuevo_ckCompensacionOrigen[]',($consulta == 3 ? true : false),
																			['value'=> $model['ctacte_id'] . '-' . $model['saldo'],'id' => 'listadoCobranza-ckTicket'.$model['ctacte_id']]);},
																			'contentOptions'=>['style'=>'width:4px;text-align:center',
																			'class' => ($consulta == 3 ? 'disabled' : ''),
																			'style' => 'margin:0px;padding:0px;height:12px;width:12px'],
						'header' => Html::checkBox('selection_all', ($consulta == 3 ? true : false), [
							'id' => 'compensacion_nuevo_ckCompensacionOrigen',
					        'onchange'=>'cambiarChecksOrigen()',
					        'class' => ($consulta == 3 ? 'disabled' : ''),
					        'style' => 'margin:0px;padding:0px;height:12px;width:12px'
					   	]),
						],
						['attribute'=>'ctacte_id','header' => 'Cta', 'contentOptions'=>['style'=>'text-align:left','width'=>'15px']],
						['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'35px']],
						['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'saldo','header' => 'Pago', 'contentOptions'=>['style'=>'text-align:right','width'=>'50px']],
		        	],
			]);
?>

</div>

<?php

	Pjax::end();
?>
</div>
<!-- FIN Grilla Origen -->

<!-- INICIO Grilla Destino -->
<div class="form-panel" style="padding-right:8px" id="grilla_destino">
<?php
Pjax::begin(['id'=>'PjaxDestino']);

	$cond = Yii::$app->request->post('cond','');

	Pjax::begin(['id'=>'PjaxGrillaDestino']);

		$dataProviderDestino = new ArrayDataProvider(['allModels' => []]);

		//Obtengo los datos que llegan por post
		$destino_tipo = Yii::$app->request->post('destino_tipo',0);
		$destino_trib_id = Yii::$app->request->post('destino_trib_id',0);
		$destino_plan = Yii::$app->request->post('destino_plan',0);
		$destino_objID = Yii::$app->request->post('destino_objID','');
		$destino_anioDesde = Yii::$app->request->post('destino_anioDesde','');
		$destino_cuotaDesde = Yii::$app->request->post('destino_cuotaDesde','');
		$destino_anioHasta = Yii::$app->request->post('destino_anioHasta','');
		$destino_cuotaHasta = Yii::$app->request->post('destino_cuotaHasta','');
		$destino_aplicaContribuyente = Yii::$app->request->post('destino_aplicaContribuyente',0);
		$destino_periodos = Yii::$app->request->post('destino_periodos',0);
		$destino_anual = Yii::$app->request->post('destino_anual',0);
		$destino_semestre = Yii::$app->request->post('destino_semestre',0);
		$destino_fchconsolidacion = Yii::$app->request->post('destino_fchconsolidacion','');

		if ( $destino_tipo != 0 )
		{
			$arreglo = $model->buscarPerAplica($destino_objID,$destino_trib_id,$destino_plan,$destino_fchconsolidacion,$destino_anioDesde,$destino_cuotaDesde,$destino_anioHasta,$destino_cuotaHasta,$destino_anual,$destino_semestre,$destino_aplicaContribuyente);	//Llegan dataProvider y error

			if ( count ( $arreglo['error'] ) == 0 )
				$dataProviderDestino = $arreglo['dataProvider'];
			else
			{
				echo '<script>var array = new Array();</script>';
				//Mostrar errores
				foreach ( $arreglo['error'] as $array )
				{
					echo '<script>array.push("' . $array . '");</script>';
				}

				echo '<script>mostrarErrores( array, "#compensaForm_errorSummary" )</script>';
			}
		}

?>

<table>
	<tr>
		<td width="60px"><h3><label>Destino:</label></h3></td>
		<td width="30px"></td>
		<td width="40px"><label>Tributo:</label></td>
		<td width="200px"><?= Html::dropDownList('dlTribDestino',$model->trib_dest,utb::getAux('trib','trib_id','nombre',1,$cond),[
								'id'=>'compensacion_nuevo_dlTribDestino',
								'class'=>'form-control',
								'style'=>'width:100%;text-align:left',
								'onchange'=>'limpiarEditDestino();cambioTribDestino( $(this).val() )',
							]); ?></td>
		<td width="20px"></td>
		<td id="ObjetoDestino" style="display:block">
			<label>Objeto:</label>
			<?= Html::input('text','txDestinoObjCod',$model->obj_dest,[
					'id' => 'compensacion_nuevo_txDestinoObjCod',
					'class' => 'form-control',
					'style' => 'width:70px;text-align:center',
					'maxlength' => 8,
					'onchange' => '$.pjax.reload({container:"#ObjDestinoNombre",method:"POST",data:{objeto_id:$(this).val(),trib_id:$("#compensacion_nuevo_dlTribDestino").val()}})',
				]);
			?>
			<!-- botón de búsqueda modal -->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',['class' => 'bt-buscar','id'=>'compensacion_nuevo_btBuscaObjDestino','onclick'=>'$("#BuscaObjcompensacion_nuevo_btDestinoBusca, .window").modal("show")']); ?>
			<!-- fin de botón de búsqueda modal -->
			<?= Html::input('text','txDestinoObjNom',$model->obj_dest_nom,['id'=>'compensacion_nuevo_txDestinoObjNom','class'=>'form-control solo-lectura','style'=>'width:150px']) ?>
		</td>
		<td id="PlanDestino" style="display:none">
			<label>Plan:</label>
			<?= Html::input('text','txPlanDestino',$model->plan_destino,['id'=>'compensacion_nuevo_txPlanDestino','class'=>'form-control','style'=>'width:120px']); ?>
		</td>
		<td width="30px"></td>
		<td width="150px" id="botonCargarDestino" style="display:none"><?= Html::button('Cargar Destino',['id'=>'btCargarDestino','class'=>'btn btn-primary','onclick'=>'CargarDestino()']) ?></td>
	</tr>
</table>
<table id="OpcionesDestino" style="display:block">
	<tr>
		<td width="150px"><?= Html::checkbox('ckAplicaContr',$model->dest_aplica,['id'=>'compensacion_nueva_ckAplicaContr','label'=>'Aplica Contribuyente']); ?></td>
		<td width="40px"></td>
		<td width="40px"><label>Traer:</label></td>
		<td width="100px"><?= Html::radio('rbDatos',$model->dest_periodo,['id'=>'compensacion_nueva_datos_periodos','label'=>'Períodos']);?></td>
		<td width="100px"><?= Html::radio('rbDatos',$model->dest_anual,['id'=>'compensacion_nueva_datos_anual','label'=>'Anual']);?></td>
		<td width="100px"><?= Html::radio('rbDatos',$model->dest_semestral,['id'=>'compensacion_nueva_datos_sem','label'=>'Sem./Trim']);?></td>
	</tr>
</table>
<table id="PeriodoDestino" style="display:block">
	<tr>
		<td width="40px"><label>Desde:</label></td>
		<td>
			<?= Html::input('text','txDestinoAnioDesde',$model->aniodesde_destino,[
					'id'=>'compensacion_nuevo_txDestinoAnioDesde',
					'class'=>'form-control',
					'style'=>'width:50px',
					'maxlength' => 4,
					'onkeypress' => 'return justNumbers( event )',
				]);
			?>
		</td>
		<td>
			<?= Html::input('text','txDestinoCuotaDesde',$model->cuotadesde_destino,[
					'id'=>'compensacion_nuevo_txDestinoCuotaDesde',
					'class'=>'form-control',
					'style'=>'width:40px',
					'maxlength' => 3,
					'onkeypress' => 'return justNumbers( event )',
				]);
			?>
		</td>
		<td width="30px"></td>
		<td width="40px"><label>Hasta:</label></td>
		<td>
			<?= Html::input('text','txDestinoAnioHasta',$model->aniohasta_destino,[
					'id'=>'compensacion_nuevo_txDestinoAnioHasta',
					'class'=>'form-control',
					'style'=>'width:50px',
					'maxlength' => 4,
					'onkeypress' => 'return justNumbers( event )',
				]);
			?>
		</td>
		<td>
			<?= Html::input('text','txDestinoCuotaHasta',$model->cuotahasta_destino,[
					'id'=>'compensacion_nuevo_txDestinoCuotaHasta',
					'class'=>'form-control',
					'style'=>'width:40px',
					'maxlength' => 3,
					'onkeypress' => 'return justNumbers( event )',
				]);
			?>
		</td>
		<td align="right" width="350px"><?= Html::button('Cargar Destino',['id'=>'btCargarDestino','class'=>'btn btn-primary','onclick'=>'CargarDestino()']) ?></td>
		<td>
	</tr>
</table>

<div class="div_grilla_destino">
<?php

	Pjax::begin(['id' => 'PjaxGrillaGrilla']);

		echo GridView::widget([
			'id' => 'GrillaCompensacionDestino',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProviderDestino,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
				['content'=> function($model, $key, $index, $column) {return Html::checkbox('compensacion_nuevo_ckCompensacionDestino[]',false,
																		['value'=> $model['ctacte_id'] . '-' . $model['saldo'],
																		 'id' => 'listadoCobranza-ckTicket'.$model['ctacte_id'],
																		 'style' => 'margin:0px;padding:0px;height:12px;width:12px',
																		 ]);},
																		'contentOptions'=>['style'=>'width:4px;text-align:center',
																		],
					'header' => Html::checkBox('selection_all', false, [
						'id' => 'compensacion_nuevo_ckCompensacionDestino',
				        'onchange'=>'cambiarChecksDestino()',
				        'style' => 'margin:0px;padding:0px;height:12px;width:12px',
				   ]),
				],
				['attribute'=>'trib_nom','header' => 'Tributo', 'contentOptions'=>['style'=>'text-align:left','width'=>'40px']],
				['attribute'=>'obj_id','header' => 'Ojeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
				['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
				['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
				['attribute'=>'saldo','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
				['attribute'=>'venc','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
	    	],
		]);

	Pjax::end();
?>

</div>

<?php

	Pjax::end();

Pjax::end();

if ( $model->comp_id != '' && $model->comp_id != null )
{
	?>

	<script>

		$(document).on("pjax:end", function() {

			CargarDestino();

			$(document).off("pjax:end");
		});

	</script>

	<?php
}

Pjax::begin(['id'=>'PjaxGrabarDatos']);
	//Obtengo los datos que viajan por POST para grabar la Compensación

	 $tipo = Yii::$app->request->post('tipo',0);

	 if ($tipo != 0)
	 {
	 	 $codigo = Yii::$app->request->post('codigo','');

	 	 if ($codigo == 0 || $codigo == '')
	 	 	$nuevo = 1;
	 	 else
	 	 	$nuevo = 0;

	 	 $fchconsolidacion = Yii::$app->request->post('fchconsolidacion','');
		 $monto = Yii::$app->request->post('monto',0);
		 $expe = Yii::$app->request->post('expe',0);
		 $objIDOri = Yii::$app->request->post('objIDOri','');
		 $tribOri = Yii::$app->request->post('tribOri',0);
		 $planOri = Yii::$app->request->post('planOri',0);
		 $objIDDes = Yii::$app->request->post('objIDDes','');
		 $tribDes = Yii::$app->request->post('tribDes',0);
		 $planDes = Yii::$app->request->post('planDes',0);
		 $arregloDes = Yii::$app->request->post('arregloDes',[]);
		 $arregloOri = Yii::$app->request->post('arregloOri',[]);
		 $aplicaContribuyente = Yii::$app->request->post('aplicaContribuyente',0);
		 $periodos = Yii::$app->request->post('periodos',0);
		 $anual = Yii::$app->request->post('anual',0);
		 $semestre = Yii::$app->request->post('semestre',0);
		 $obs = Yii::$app->request->post('obs',0);

		 if ($periodos == 1)
		 	$act_ctacte = 1;

		 if ($anual == 1)
		 	$act_ctacte = 12;

		 if ($semestre == 1)
		 	$act_ctacte = 6;

		 //Código == 0 => Inserto || Código == 1 => Modifico
         $resultado = $model->grabarComp($nuevo,$codigo,$arregloOri,$arregloDes,$act_ctacte,$expe,$tipo,$aplicaContribuyente,$fchconsolidacion,$tribOri,$objIDOri,$tribDes,$objIDDes,$monto,$obs);

		 if ($resultado != '')	//Los datos se grabaron bien
		 {
		 	echo Html::a('',['view','m'=>1,'alert','','id'=>$resultado],['id'=>'btRedirigirCompensa', 'style' => 'display:none']);
		 	echo '<script>$("#btRedirigirCompensa").click();</script>';
		 } else
		 {
		 	echo '<script>mostrarErrores( ["Ocurrió un error al grabar los datos."], "#compensaForm_errorSummary");</script>';
		 }
	 } else
	 {
	 	echo '<script>mostrarErrores( ["Ocurrió un error al grabar los datos."], "#compensaForm_errorSummary");</script>';
	 }

Pjax::end();
?>
</div>
<!-- FIN Grilla Destino -->

<?= Html::button('Aceptar',[
		'id' => 'compensaForm_btAceptar',
		'class'=>'btn btn-success disabled',
		'onclick' => 'btAceptar()']); ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?= Html::a('Cancelar',['view', 'id' => $model->comp_id],['class'=>'btn btn-primary']); ?>

<div id="compensaForm_errorSummary" class="error-summary" style="display:none; margin-top: 8px; margin-right: 15px">

	<ul>
	</ul>
</div>

</div>

<?php

//INICIO Modal Busca Objeto Origen
	Modal::begin([
	'id' => 'BuscaObjcompensacion_nuevo_btOrigenBusca',
	'size' => 'modal-lg',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
	 ]);

	echo $this->render('//objeto/objetobuscarav',[
								'idpx' => 'origenBusca', 'id' => 'compensacion_nuevo_btOrigenBusca', 'txCod' => 'compensacion_nuevo_txOrigenObjCod', 'txNom' => 'compensacion_nuevo_txOrigenObjNom'
			        		]);

	Modal::end();
//FIN Modal Busca Objeto Origen

//INICIO Modal Busca Objeto Destino
	Modal::begin([
	'id' => 'BuscaObjcompensacion_nuevo_btDestinoBusca',
	'size' => 'modal-lg',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
	 ]);

	echo $this->render('//objeto/objetobuscarav',[
								'idpx' => 'destinoBusca', 'id' => 'compensacion_nuevo_btDestinoBusca', 'txCod' => 'compensacion_nuevo_txDestinoObjCod', 'txNom' => 'compensacion_nuevo_txDestinoObjNom'
			        		]);

	Modal::end();
//FIN Modal Busca Objeto Destino

//INICIO Bloque actualiza los códigos de objeto de Origen
Pjax::begin(['id' => 'ObjOrigenNombre']);

	if (isset($_POST['trib_id']))
	{
		$tobj = '';
		$trib_id = Yii::$app->request->post('trib_id',0);
		$objeto_id = Yii::$app->request->post('objeto_id','');

		$tobj = utb::getTObjTrib( $trib_id );

		//echo '<script>alert("Tipo de Objeto = '.$tobj.'")</script>';
		echo '<script>$("#compensacion_nuevo_txOrigenObjCod").val("")</script>';

		if (strlen($objeto_id) < 8 && $objeto_id != '')
		{
			$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);

			if ( ! ( utb::verificarExistenciaObjeto( (int)$tobj, "'" . $objeto_id . "'" ) ) )
				$objeto_id = '';
		}

		if (utb::getTObj($objeto_id) == $tobj)
		{
			$objeto_nom = utb::getNombObj("'".$objeto_id."'");

			echo '<script>$("#compensacion_nuevo_txOrigenObjCod").val("'.$objeto_id.'")</script>';
			echo '<script>$("#compensacion_nuevo_txOrigenObjNom").val("'.$objeto_nom.'")</script>';

		} else
		{
			echo '<script>$("#compensacion_nuevo_txOrigenObjCod").val("")</script>';
			echo '<script>$("#compensacion_nuevo_txOrigenObjNom ").val("")</script>';
		}

		if ($tobj == '') //Actualiza el tipo de objeto para la búsqueda de objeto
			$tobj = 0;

		echo '<script>activarBtBuscaObj();</script>';

		echo '<script>$(document).ready(function() {$.pjax.reload({container:"#PjaxObjBusAvorigenBusca",data:{tobjeto:'.$tobj.'},method:"POST"});});</script>';
	}

Pjax::end();
//FIN Bloque actualiza los códigos de objeto de Origen

//INICIO Bloque actualiza los códigos de objeto de Destino
Pjax::begin(['id' => 'ObjDestinoNombre']);

	if ( isset( $_POST['trib_id'] ) )
	{
		$tobj = '';
		$trib_id = Yii::$app->request->post( 'trib_id', 0 );
		$objeto_id = Yii::$app->request->post( 'objeto_id', '' );
		$tobj = utb::getTObjTrib( $trib_id );

		//echo '<script>alert("Tipo de Objeto = '.$tobj.'")</script>';
		echo '<script>$("#compensacion_nuevo_txDestinoObjCod").val("")</script>';

		if ( strlen( $objeto_id ) < 8 && $objeto_id != '' )
		{
			$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
			echo '<script>$("#compensacion_nuevo_txDestinoObjCod").val("'.$objeto_id.'")</script>';
		}

		if (utb::getTObj($objeto_id) == $tobj)
		{
			$objeto_nom = utb::getNombObj("'".$objeto_id."'");

			echo '<script>$("#compensacion_nuevo_txDestinoObjCod").val("'.$objeto_id.'")</script>';
			echo '<script>$("#compensacion_nuevo_txDestinoObjNom").val("'.$objeto_nom.'")</script>';

		} else
		{
			echo '<script>$("#compensacion_nuevo_txDestinoObjCod").val("")</script>';
			echo '<script>$("#compensacion_nuevo_txDestinoObjNom ").val("")</script>';
		}

		if ( $tobj == '' ) //Actualiza el tipo de objeto para la búsqueda de objeto
			$tobj = 0;

		echo '<script>activarBtBuscaObj();</script>';

		echo '<script>$(document).ready(function() {$.pjax.reload({container:"#PjaxObjBusAvdestinoBusca",data:{tobjeto:'.$tobj.'},method:"POST"});});</script>';
	}

Pjax::end();
//FIN Bloque actualiza los códigos de objeto de Destino

?>

<script>
/* Función que habilita o deshabilita los botones de búsqueda de objeto */
function activarBtBuscaObj()
{
	//Remuevo la propiedad disabled a ambos botones de búsqueda
	$("#compensacion_nuevo_btBuscaObjOrigen").removeAttr("disabled");
	$("#compensacion_nuevo_btBuscaObjDestino").removeAttr("disabled");

	var tribOrigen = $("#compensacion_nuevo_dlTribOrigen").val(),
		tribDestino = $("#compensacion_nuevo_dlTribDestino").val();

	if ( tribOrigen == '' || tribOrigen == 0 )
		$("#compensacion_nuevo_btBuscaObjOrigen").attr("disabled",true);

	if ( tribDestino == '' || tribDestino == 0 )
		$("#compensacion_nuevo_btBuscaObjDestino").attr("disabled",true);

}

/* Función que limpia los edit de Origen */
function limpiarEditOrigen()
{
	$("#compensacion_nuevo_txOrigenObjCod").val("");
	$("#compensacion_nuevo_txOrigenObjNom").val("");
	$("#compensacion_nuevo_txOrigenAnioDesde").val("");
	$("#compensacion_nuevo_txOrigenCuotaDesde").val("");
	$("#compensacion_nuevo_txOrigenAnioHasta").val("");
	$("#compensacion_nuevo_txOrigenCuotaHasta").val("");
}

/* Función que habilita o deshabilita Plan en Origen dependiendo el código de Tributo */
function cambioTribOrigen(trib)
{
	if ( trib == 1 || trib == 2 || trib == 3 )
	{
		$("#ObjetoOrigen").css("display","none");
		$("#PlanOrigen").css("display","inline");
		$("#PeriodoOrigen").css("display","none");
		$("#botonCargarOrigen").css("display","inline");

	} else
	{
		$("#ObjetoOrigen").css("display","block");
		$("#PlanOrigen").css("display","none");
		$("#PeriodoOrigen").css("display","block");
		$("#botonCargarOrigen").css("display","none");
	}

}

/* Función que limpia los edit de Destino */
function limpiarEditDestino()
{
	$("#compensacion_nuevo_txDestinoObjCod").val("");
	$("#compensacion_nuevo_txDestinoObjNom").val("");
	$("#compensacion_nuevo_txDestinoAnioDesde").val("");
	$("#compensacion_nuevo_txDestinoCuotaDesde").val("");
	$("#compensacion_nuevo_txDestinoAnioHasta").val("");
}

/* Función que habilita o deshabilita Plan en Destino dependiendo el código de Tributo */
function cambioTribDestino( trib )
{
	if ( trib == 1 || trib == 2 || trib == 3 )
	{
		$("#ObjetoDestino").css("display","none");
		$("#PlanDestino").css("display","inline");
		$("#OpcionesDestino").css("display","none");
		$("#PeriodoDestino").css("display","none");
		$("#botonCargarDestino").css("display","inline");

	} else
	{
		$("#ObjetoDestino").css("display","block");
		$("#PlanDestino").css("display","none");
		$("#OpcionesDestino").css("display","block");
		$("#PeriodoDestino").css("display","block");
		$("#botonCargarDestino").css("display","none");
	}

	/*$("#compensacion_nuevo_txDestinoObjCod").toggleClass( "read-only", trib == 0 );

	//Limpio la grilla destino
	$.pjax.reload("#PjaxGrillaGrilla");

	$("#PjaxGrillaGrilla").on("pjax:end", function() {

		$.pjax.reload({
			container:"#ObjDestinoNombre",
			method:"POST",
			data:{
				trib_id: $("#compensacion_nuevo_dlTribDestino").val(),
				objeto_id: $("#compensacion_nuevo_txDestinoObjCod").val(),
			},
		});

		$("#ObjDestinoNombre").on("pjax:end", function() {

			$("#PjaxGrillaGrilla").off("pjax:end");

			$("#ObjDestinoNombre").off("pjax:end");
		});

	});*/
}

/* Función que se ejecuta cuando se cambia el tipo de la Compensación */
function cambioTipo()
{
	var tipo = $("#compensacion_nuevo_dlTipo").val(),
		cond = 'compensa = 1 ';

	if ( tipo == 3 || tipo == 4 )
	{
		$("#compensacion_nuevo_txOrigen").attr('readOnly',true);
		$("#grilla_origen").css("display","block");
	}
	else
	{
		$("#compensacion_nuevo_txOrigen").removeAttr('readOnly');
		$("#grilla_origen").css("display","none");
	}

	/* Si tipo = 1, el destino solo puede ser un comercio */
	if (tipo == 1)
		cond += "AND tobj = 2";

	$.pjax.reload({container:"#PjaxDestino",method:"POST",data:{cond:cond}});
}

/* Función que se ejecuta al momento de presionar el botón "Cargar Origen" */
function CargarOrigen()
{
	//Declaración de variables
	var error = new Array(),
		objID = $("#compensacion_nuevo_txOrigenObjCod").val(),
		objNom = $("#compensacion_nuevo_txOrigenObjNom").val(),
		anioDesde = $("#compensacion_nuevo_txOrigenAnioDesde").val(),
		cuotaDesde = $("#compensacion_nuevo_txOrigenCuotaDesde").val(),
		anioHasta = $("#compensacion_nuevo_txOrigenAnioHasta").val(),
		cuotaHasta = $("#compensacion_nuevo_txOrigenCuotaHasta").val(),
		trib = $("#compensacion_nuevo_dlTribOrigen").val(),
		tipo = $("#compensacion_nuevo_dlTipo").val(),
		plan = $("#compensacion_nuevo_txPlanOrigen").val();

	//Verificar que se haya seleccionado un tributo
	if (trib == '')
		error.push( "Ingrese un tributo origen." );

	//Verificar que se haya ingresado un plan
	if ( trib == 1 || trib == 2 || trib == 3 )
	{
		if ( plan == '' )
			error.push( "Ingrese un plan." );
	} else
	{
		plan = 0;

		if ( objID == '' )
			error.push( "Ingrese un Objeto origen." );
		else if ( objNom == '' )
			error.push( "Ingrese un Objeto origen válido." );

	}

	/* Mensaje de error */
	if ( error.length > 0 )
		mostrarErrores( error, "#compensaForm_errorSummary" );
	else
		$.pjax.reload({
			container:"#PjaxGrillaOrigen",
			method:"POST",
			data:{
				origen_tipo:tipo,
				origen_trib_id:trib,
				origen_plan:plan,
				origen_objID:objID,
				origen_anioDesde:anioDesde,
				origen_cuotaDesde:cuotaDesde,
				origen_anioHasta:anioHasta,
				origen_cuotaHasta:cuotaHasta,
			},
		});

}

/* Función que se ejecuta al momento de presionar el botón "Cargar Destino" */
function CargarDestino()
{
	//Declaración de variables
	var error = new Array(),
		objID = $("#compensacion_nuevo_txDestinoObjCod").val(),
		objNom = $("#compensacion_nuevo_txDestinoObjNom").val(),
		anioDesde = $("#compensacion_nuevo_txDestinoAnioDesde").val(),
		cuotaDesde = $("#compensacion_nuevo_txDestinoCuotaDesde").val(),
		anioHasta = $("#compensacion_nuevo_txDestinoAnioHasta").val(),
		cuotaHasta = $("#compensacion_nuevo_txDestinoCuotaHasta").val(),
		trib = $("#compensacion_nuevo_dlTribDestino").val(),
		tipo = $("#compensacion_nuevo_dlTipo").val(),
		plan = $("#compensacion_nuevo_txPlanDestino").val(),
		fchconsolidacion = $("#compensacion_nuevo_txFchconsolida").val(),

		aplicaContribuyente = 0,
		periodos = 0,
		anual = 0,
		semestre = 0;

	//Verificar que se haya seleccionado un tributo
	if ( trib == '' || trib == 0 )
		error.push( "Ingrese un tributo destino." );

	//Verificar que se haya ingresado un plan
	if ( trib == 1 || trib == 2 || trib == 3 )
	{
		if ( plan == '' )
			error.push( "Ingrese un plan." );
	} else
	{
		plan = 0;

		if ($("#compensacion_nueva_ckAplicaContr").is(":checked"))
			aplicaContribuyente = 1;

		if ($("#compensacion_nueva_datos_periodos").is(":checked"))
			periodos = 1;

		if ($("#compensacion_nueva_datos_anual").is(":checked"))
			anual = 1;

		if ($("#compensacion_nueva_datos_sem").is(":checked"))
			semestre = 1;

		if ( objID == '' )
			error.push( "Ingrese un Objeto destino." );
		else if ( objNom == '' )
			error.push( "Ingrese un Objeto destino válido." );

	}

	/* Mensaje de error */
	if ( error.length > 0 )
		mostrarErrores( error, "#compensaForm_errorSummary" );
	else
	{
		//Habilitar el botón "Aceptar"
		$("#compensaForm_btAceptar").removeClass("disabled");

		//Ocultar mensajes de error
		$("#compensaForm_errorSummary").css("display","none");

		$.pjax.reload({
			container:"#PjaxGrillaDestino",
			method:"POST",
			data:{
				destino_tipo:tipo,
				destino_trib_id:trib,
				destino_plan:plan,
				destino_objID:objID,
				destino_anioDesde:anioDesde,
				destino_cuotaDesde:cuotaDesde,
				destino_anioHasta:anioHasta,
				destino_cuotaHasta:cuotaHasta,
				destino_aplicaContribuyente:aplicaContribuyente,
				destino_periodos:periodos,
				destino_anual:anual,
				destino_semestre:semestre,
				destino_fchconsolidacion:fchconsolidacion,
			},
		});

		$("#PjaxGrillaDestino").on("pjax:end",function() {

			cambioTribDestino( trib );

			$("#PjaxGrillaDestino").off("pjax:end");
		});
	}
}

/* Función que se ejecuta al presionar el botón "Aceptar" */
function btAceptar()
{
	//Declaración de variables.
	var error = new Array(),
		codigo = $("#compensacion_nuevo_txCodigo").val(),
		tipo = $("#compensacion_nuevo_dlTipo").val(),
		fchconsolidacion = $("#compensacion_nuevo_txFchconsolida").val(),
		monto = $("#compensacion_nuevo_txOrigen").val(),
		expe = $("#compensacion_nuevo_txExpe").val(),
		objIDOri = $("#compensacion_nuevo_txOrigenObjCod").val(),
		objNomOri = $("#compensacion_nuevo_txOrigenObjNom").val(),
		tribOri = $("#compensacion_nuevo_dlTribOrigen").val(),
		planOri = $("#compensacion_nuevo_txPlanOrigen").val(),
		objIDDes = $("#compensacion_nuevo_txDestinoObjCod").val(),
		objNomDes = $("#compensacion_nuevo_txDestinoObjNom").val(),
		tribDes = $("#compensacion_nuevo_dlTribDestino").val(),
		planDes = $("#compensacion_nuevo_txPlanDestino").val(),
		obs = $("#compensacion_nuevo_txObs").val(),

		aplicaContribuyente = 0,
		periodos = 0,
		anual = 0,
		semestre = 0,

		/* En los arreglos obtengo ctacte_id y pago */
		arregloOri = new Array(),
		arregloDes = new Array(),

		checksOrigen = $('#GrillaCompensacionOrigen input:checked').not("#compensacion_nuevo_ckCompensacionOrigen"),
		checksDestino = $('#GrillaCompensacionDestino input:checked').not("#compensacion_nuevo_ckCompensacionDestino");

	if (codigo == '' )
		codigo = 0;

		if ($("#compensacion_nueva_ckAplicaContr").is(":checked"))
			aplicaContribuyente = 1;

		if ($("#compensacion_nueva_datos_periodos").is(":checked"))
			periodos = 1;

		if ($("#compensacion_nueva_datos_anual").is(":checked"))
			anual = 1;

		if ($("#compensacion_nueva_datos_sem").is(":checked"))
			semestre = 1;

	checksOrigen.each(function() {

		arregloOri.push( $(this).val() );
	});

	checksDestino.each(function() {

		arregloDes.push( $(this).val() );
	});

	//Verificar que se haya seleccionado un tributo
	if ( tipo == 1 || tipo == 2 )
	{
		//Monto debe existir y ser mayor a 0
		if ( monto == '' )
			error.push( "Ingrese monto origen." );
		else if (monto == 0)
			error.push( "Monto origen debe ser mayor a 0." );
	} else
	{
		monto = 0;
	}

	if (expe == '')
		error.push( "Debe ingresar un expediente." );

	//Se debe seleccionar origen
	if ( tipo == 3 || tipo == 4 )
	{
		if ( tribOri == '' || tribOri == 0 )
			error.push( "Debe seleccionar tributo origen." );

		if ( objIDOri == '' )
			error.push( "Debe seleccionar un objeto origen." );

		if ( tribOri != 1 && tribOri != 3 && objIDOri != '' && objNomOri == '' )
			error.push( "Debe seleccionar un objeto origen válido." );

		if ( (tribOri == 1 || tribOri == 3) && $("#compensacion_nuevo_txPlanOrigen").val() == '' )
			error.push( "Debe un Plan válido." );
	}

	//Se debe seleccionar tributo y objeto destino
	if ( tribDes == '' || tribDes == 0 )
		error.push( "Debe seleccionar tributo destino." );

	if ( ! ( tribDes == 1 || tribDes == 2 || tribDes == 3 ) )
	{
		if ( objIDDes == '' )
			error.push( "Debe seleccionar un objeto destino." );

		if ( objIDDes != '' && objNomDes == '' )
			error.push( "Debe seleccionar un objeto destino válido." );
	}

	/* Mensaje de error */
	if ( error.length > 0 )
		mostrarErrores( error, "#compensaForm_errorSummary" );
	else
		$.pjax.reload({
			container:"#PjaxGrabarDatos",
			method:"POST",
			data:{
				codigo:codigo,
				tipo:tipo,
				fchconsolidacion:fchconsolidacion,
				monto:monto,
				expe:expe,
				objIDOri:objIDOri,
				tribOri:tribOri,
				planOri:planOri,
				objIDDes:objIDDes,
				tribDes:tribDes,
				planDes:planDes,
				arregloOri:arregloOri,
				arregloDes:arregloDes,
				aplicaContribuyente:aplicaContribuyente,
				periodos:periodos,
				anual:anual,
				semestre:semestre,
				obs:obs,
			},
		});
}

function cambiarChecksOrigen()
{
	var checks = $('#GrillaCompensacionOrigen input[type="checkbox"]');

	if ($("#compensacion_nuevo_ckCompensacionOrigen").is(":checked"))
	{
		checks.each(function() {

			checks.prop('checked','checked');

		});
	} else
	{
		checks.each(function() {

			checks.prop('checked','');

		});
	}
}

function cambiarChecksDestino()
{
	var checks = $('#GrillaCompensacionDestino input[type="checkbox"]');

	if ($("#compensacion_nuevo_ckCompensacionDestino").is(":checked"))
	{
		checks.each(function() {

			checks.prop('checked','checked');

		});
	} else
	{
		checks.each(function() {

			checks.prop('checked','');

		});
	}
}

$(document).ready(function() {

	var trib = $("#compensacion_nuevo_dlTribDestino").val();

	activarBtBuscaObj();
	cambioTribDestino( trib );

});
</script>
