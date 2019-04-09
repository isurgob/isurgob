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
use yii\data\ArrayDataProvider;
use yii\bootstrap\Alert;

/**
 * Forma que se dibuja cuando se llega a Débito Automático
 * Recibo:
 * 			=> $model -> Modelo de Débito
 */
 
/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */

//INICIO Bloque actualiza los códigos de objeto
Pjax::begin(['id' => 'ObjNombre']);
	
	$tobj = Yii::$app->request->post('tobj',0);
	$objeto_id = Yii::$app->request->post('objeto_id',0);
	
	echo '<script>$("#Debitoliquidacion_txObjetoID").val("")</script>';
	
	if ( $tobj != 0 )
	{
		if (strlen($objeto_id) < 8)
		{
			$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
			echo '<script>$("#Debitoliquidacion_txObjetoID").val("'.$objeto_id.'")</script>';
			
		}
		
		if (utb::GetTObj($objeto_id) == $tobj)
		{
			$objeto_nom = utb::getNombObj("'".$objeto_id."'");
		
			echo '<script>$("#Debitoliquidacion_txObjetoNom").val("'.$objeto_nom.'")</script>';	
				
		} else 
		{
			echo '<script>$("#Debitoliquidacion_txObjetoID").val("")</script>';
			echo '<script>$("#Debitoliquidacion_txObjetoNom ").val("")</script>';
		}
	}
	
	//INICIO Modal Busca Objeto
	Modal::begin([
		'id' => 'BuscaObjLiquidacion',
		'size' => 'modal-lg',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		],
		 ]);
									
		echo $this->render('//objeto/objetobuscarav',[
									'id' => 'debito_liquidacion_altaBuscar',
									'txCod' => 'Debitoliquidacion_txObjetoID',
									'txNom' => 'Debitoliquidacion_txObjetoNom',
									'selectorModal' => '#BuscaObjLiquidacion',
									'tobjeto' => (int)$tobj,
				        		]);
		
	Modal::end();
	//FIN Modal Busca Objeto
	
Pjax::end();
//FIN Bloque actualiza los códigos de objeto

$title = 'Liquidaciones';
$this->params['breadcrumbs'][] = ['label' => 'Débitos Automáticos', 'url' => ['caja/debito/view']];
$this->params['breadcrumbs'][] = $title;

?>

<div class="Debitoliquidacion_info" style="margin-right:140px">

<table width="100%">
	<tr>
		<td align="left">
			<h1><?= Html::encode($title) ?></h1>
		</td>
		<td align="right" style="padding-right:20px">
			<?= Html::a('Volver',['view'],['class'=>'btn btn-primary']); ?>
		</td>
	</tr>
</table>

<!-- INICIO Mensajes de error -->
<table width="98%">
	<tr>
		<td width="100%">
				
			<?php 

			Pjax::begin(['id'=>'errorDebitoliquidacion']);
			
			$mensaje = '';
			
			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];
		
			if($mensaje != "")
			{ 
		
		    	Alert::begin([
		    		'id' => 'AlertaMensajeDebitoliquidacion',
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
		    		],
				]);
		
				echo $mensaje;
						
				Alert::end();
				
				echo "<script>window.setTimeout(function() { $('#AlertaMensajeDebitoliquidacion').alert('close'); }, 5000)</script>";
			 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<div class="form-panel" style="padding-right:8px">
<table width="100%">
	<tr>
		<td width="80px"><label>Caja:</label></td>
		<td width="150px"><?= Html::dropDownList('dlDebitoliquidacion',null,utb::getAux('caja','caja_id','nombre',1,"tipo IN (3,4,5) AND est='A'"),['id'=>'Debitoliquidacion_dlDebitoliquidacion','class'=>'form-control','style'=>'width:100%;text-align:center']) ?></td>
		<td width="15px"></td>
		<td width="50px"><label>Tributo:</label></td>
		<td colspan="3">			
			<?= Html::dropDownList('dlTrib', null, utb::getAux('trib','trib_id','nombre',1,"tipo = 1 OR trib_id IN (1,2)"), 
			[	'style' => 'width:200px',
				'class'=>'form-control',
				'id'=>'Debitoliquidacion_dlTrib',
			]); ?>
		</td>
	</tr>
	<tr>
		<td width="80px"><label>Tipo Objeto:</label></td>
		<td><?= Html::dropDownList('dlTObj',null,utb::getAux('objeto_tipo','cod','nombre',1,"est='A'"),['id'=>'Debitoliquidacion_dlTObj',
																											'class'=>'form-control',
	    																									'onchange'=>'habilitaObjeto()',
																											'style'=>'width:100%;text-align:center']) ?></td>
		<td width="15px"></td>
		<td width="50px"><label>Objeto:</label></td>
		<td><?= Html::input('text','txObjetoID',null,['id'=>'Debitoliquidacion_txObjetoID','class'=>'form-control','style'=>'width:90px;text-align:center',
							'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{objeto_id:$(this).val(),tobj:$("#Debitoliquidacion_dlTObj").val()},method:"POST"})']); ?>
		</td>
		<td>
		<!-- botón de búsqueda modal -->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
					'class' => 'bt-buscar',
					'id'=>'debito_liquidacion_btBuscaObj',
					'onclick' => '$("#BuscaObjLiquidacion").modal("show");',
				]);
			?>
		
			<!-- fin de botón de búsqueda modal -->
		</td>
		<td width="250px" colspan="2"><?= Html::input('text','txObjetoNom',null,['id'=>'Debitoliquidacion_txObjetoNom','class'=>'form-control','style'=>'width:100%;text-align:left','readOnly'=>true]) ?></td>	
	</tr>
</table>	

<table width="100%">
	<tr>
		<td width="35px" align="left"><label>Año:</label></td>
		<td width="40px" align="left"><?= Html::input('text','txAnio',null,['id'=>'Debitoliquidacion_txAnio','class'=>'form-control','style'=>'width:40px;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>4]) ?></td>	
		<td width="20px" align="left"></td>
		<td width="35px" align="left"><label>Mes:</label></td>
		<td width="30px" align="left"><?= Html::input('text','txMes',null,['id'=>'Debitoliquidacion_txMes','class'=>'form-control','style'=>'width:30px;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>2]) ?></td>
		<td align="right"><?= html::button('Buscar',['id' => 'Debitoliquidacion_btBuscar','class'=>'btn btn-primary','onclick'=>'btBuscar()']); ?></td>
	</tr>
</table>

<!-- INICIO Grilla -->
<div style="padding-right:0px;margin-top:10px;margin-bottom:10px">

<?php

Pjax::begin(['id'=>'PjaxGrillaDebitoLiq']);
	
	$dataProvider = new ArrayDataProvider(['allModels' => []]);
	
	if (isset($_POST['caja']) && $_POST['caja'] != '')	//Obtengo los datos que viajan por POST.
	{
		$caja = Yii::$app->request->post('caja',0);
		$trib_id = Yii::$app->request->post('trib',0);
		$obj_id = Yii::$app->request->post('obj_id','');
		$anio = Yii::$app->request->post('anio',0);
		$mes = Yii::$app->request->post('mes',0);
		
		$datos = $model->listarDebitoLiq($caja,$trib_id,$obj_id,$anio,$mes);	//Obtengo el arreglo con los datos de fechas y el dataProvider
		
		$fechas = $datos['fechas'];
		$dataProvider = $datos['dataProvider']; 
		
		$arreglo = $dataProvider->getModels();
		
		$cant = 0;
		$montoDebito = 0;
		
		foreach ($arreglo as $array)
		{
			$cant++;
			$montoDebito += $array['monto'];
		}
		
		//Seteo los valores para la fecha
		if ( count($fechas) > 0 ){
			echo '<script>$("#Debitoliquidacion_txGenerado").val("'.$fechas[0]['fchgenerado'].'")</script>';
			echo '<script>$("#Debitoliquidacion_txEnviado").val("'.$fechas[0]['fchenvio'].'")</script>';
			echo '<script>$("#Debitoliquidacion_txRecepcion").val("'.$fechas[0]['fchrecep'].'")</script>';
			echo '<script>$("#Debitoliquidacion_txImputado").val("'.$fechas[0]['fchimputa'].'")</script>';
		}	
		
		//Seteo los valores para cantidad
		echo '<script>$("#Debitoliquidacion_txCant").val("'.$cant.'")</script>';
		echo '<script>$("#Debitoliquidacion_txtxMonto").val("'.$montoDebito.'")</script>';
		
	}

	echo GridView::widget([
			'id' => 'GrillaDebitosLiq',
			'headerRowOptions' => ['class' => 'grilla'],
	        'rowOptions' => function ($model,$key,$index,$grid) { return array_merge(EventosGrilla($model),['class' => 'grilla']);},
			'dataProvider' => $dataProvider,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [ 
					['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
					['attribute'=>'subcta','header' => 'Cta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'resp','header' => 'Responsable', 'contentOptions'=>['style'=>'text-align:center','width'=>'150px']],
					['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'mes','header' => 'Mes', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'trib_nom','header' => 'Tributo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'periodo','header' => 'Período', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'montodeb','header' => 'Débito', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],	
	        	],
		]);
	
	echo "<br>";
	
	if ( isset($cant) and $cant > 0 ) {
		Modal::begin([
			'id' => 'Exportar',
			'header' => '<h2>Exportar Datos</h2>',
			'toggleButton' => [
				'label' => 'Exportar',
				'class' => 'btn btn-success',
			],
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			]
		]);

			echo $this->render('//site/exportar',['titulo'=>'Listado de Personas','desc'=>Yii::$app->session['condicion'],'grilla'=>'Exportar']);

		Modal::end();
	}	
		
Pjax::end();

?>
</div>
<!-- FIN Grilla -->

<div class="form-panel" style="margin-right:0px">

<?php

Pjax::begin(['id'=>'PjaxDatosLiq']);

	$obj = Yii::$app->request->post('obj_id','');
	$liq = Yii::$app->request->post('liq','');
	$deb = Yii::$app->request->post('deb','');
	$fchdeb = Yii::$app->request->post('fchdeb','');
	$trechazo = Yii::$app->request->post('trechazo','');
	$rechazo = Yii::$app->request->post('rechazo','');
	$obs = Yii::$app->request->post('obs','');
	
?>
<table>
	<tr>
		<td width="60px"><label>Objeto:</label></td>
		<td width="60px"><?= Html::input('text','txObjeto',$obj,['id'=>'Debitoliquidacion_txObjeto','class'=>'form-control','readOnly' => true,'style'=>'width:70px;text-align:center']) ?></td>
		<td width="15px"></td>
		<td width="60px"><label>Liquidado:</label></td>
		<td width="60px"><?= Html::input('text','txLiquidado',$liq,['id'=>'Debitoliquidacion_txLiquidado','class'=>'form-control','readOnly' => true,'style'=>'width:50px;text-align:right']) ?></td>
		<td width="15px"></td>
		<td width="60px"><label>Debitado:</label></td>
		<td width="60px"><?= Html::input('text','txDebitado',$deb,['id'=>'Debitoliquidacion_txDebitado','class'=>'form-control','readOnly' => true,'style'=>'width:50px;text-align:right']) ?></td>
		<td width="15px"></td>
		<td width="80px"><label>Fecha Débito:</label></td>
		<td width="60px"><?= Html::input('text','txFchDeb',$fchdeb,['id'=>'Debitoliquidacion_txFchDeb','class'=>'form-control','readOnly' => true,'style'=>'width:60px;text-align:center']) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Rechazo:</label></td>
		<td width="500px">
			<?= Html::input('text','txRechazoID',$trechazo,['id'=>'Debitoliquidacion_txRechazoID','class'=>'form-control','readOnly' => true,'style'=>'width:40px;text-align:center']) ?>
			<?= Html::input('text','txRechazoNom',$rechazo,['id'=>'Debitoliquidacion_txRechazoNom','class'=>'form-control','readOnly' => true,'style'=>'width:452px;text-align:center']) ?>
		</td>
	</tr>
	<tr>
		<td width="60px"><label>Obs:</label></td>
		<td width="500px">
			<?= Html::textarea('taObs',$obs,[
					'id'=>'Debitoliquidacion_taObs',
					'class'=>'form-control',
					'readOnly' => true,
					'style'=>'width:498px;max-width:498px;height:60px;max-height:120px;text-align:center',
				]);
			?>
		</td>
	</tr>
</table>

<?php

Pjax::end();

Pjax::begin(['id'=>'PjaxEliminaLiq']);

	$id = Yii::$app->request->post('id','');
	
	if ($id != '')
	{
		//Elimino el elemento
		try
		{
			$result = $model->eliminarLiquidacion($id);	
		} catch (Exception $e)
		{
			$result = 'Ocurrió una excepción';
		}
			
		
		
		if ($result != '')
		{
			echo '<script>$.pjax.reload({container:"#errorDebitoliquidacion",method:"POST",data:{mensaje:'.$result.'}});</script>';
		}
		
		//Ejecuto la función buscar nuevamente
		echo '<script>btBuscar();</script>';
	}

Pjax::end();

?>

</div>

<div class="form-panel" style="margin-right:0px">
<table width="100%">
	<tr>
		<td width="60px"><label>Cantidad:</label></td>
		<td width="70px">
			<?= Html::input('text','txCant',null,[
					'id'=>'Debitoliquidacion_txCant',
					'class'=>'form-control solo-lectura',
					'style'=>'width:70px;text-align:center',
					'tabindex' => -1,
				]);
			?>
		</td>
		<td width="5px"></td>
		<td width="70px"><label>Monto Total:</label></td>
		<td width="70px">
			<?= Html::input('text','txMonto',null,[
					'id'=>'Debitoliquidacion_txtxMonto',
					'class'=>'form-control solo-lectura',
					'style'=>'width:70px;text-align:right',
					'tabindex' => -1,
				]);
			?>
		</td>
	</tr>
	<tr>
		<td width="60px"><label>Generado:</label></td>
		<td width="70px">
			<?= Html::input('text','txGenerado',null,[
					'id'=>'Debitoliquidacion_txGenerado',
					'class'=>'form-control solo-lectura',
					'style'=>'width:70px;text-align:center',
					'tabindex' => -1,
				]);
			?>
		</td>
		<td width="5px"></td>
		<td width="70px"><label>Enviado:</label></td>
		<td width="70px">
			<?= Html::input('text','txEnviado',null,[
					'id'=>'Debitoliquidacion_txEnviado',
					'class'=>'form-control solo-lectura',
					'style'=>'width:70px;text-align:center',
					'tabindex' => -1,
				]);
			?>
		</td>
		<td width="5px"></td>
		<td width="70px"><label>Recepción:</label></td>
		<td width="70px">
			<?= Html::input('text','txRecepcion',null,[
					'id'=>'Debitoliquidacion_txRecepcion',
					'class'=>'form-control solo-lectura',
					'style'=>'width:70px;text-align:center',
					'tabindex' => -1,
				]);
			?>
		</td>
		<td width="5px"></td>
		<td width="60px"><label>Imputado:</label></td>
		<td width="70px">
			<?= Html::input('text','txImputado',null,[
					'id'=>'Debitoliquidacion_txImputado',
					'class'=>'form-control solo-lectura',
					'style'=>'width:70px;text-align:center',
					'tabindex' => -1,
				]);
			?>
		</td>
	</tr>
</table>
</div>
</div>
</div>
<script>
function eliminarLiq(e, id)
{
	
	event.stopPropagation();
	
	$.pjax.reload({
		container:"#PjaxEliminaLiq",
		method:"POST",
		data:{
			"id": id,
		}
	});
}

function habilitaObjeto()
{
	if ($("#Debitoliquidacion_dlTObj").val() == 0)
	{
		$("#Debitoliquidacion_txObjetoID").attr('readOnly',true);
		$("#Debitoliquidacion_txObjetoID").val("");
		$("#Debitoliquidacion_txObjetoNom").val("");
		$("#debito_liquidacion_btBuscaObj").attr('disabled',true);
		
	} else 
	{
		$("#Debitoliquidacion_txObjetoID").removeAttr('readOnly');
		$("#debito_liquidacion_btBuscaObj").removeAttr('disabled');
	}
	
	$.pjax.reload({
		container:"#ObjNombre",
		method:"POST",
		data:{
			"tobj": $("#Debitoliquidacion_dlTObj").val(),
			
		}
	});
	
}


function btBuscar()
{
	limpiarEdit();
	
	var caja = $("#Debitoliquidacion_dlDebitoliquidacion").val();
	var trib = $("#Debitoliquidacion_dlTrib").val();
	var tobj = $("#Debitoliquidacion_dlTObj").val();
	var objID = $("#Debitoliquidacion_txObjetoID").val();
	var objNom = $("#Debitoliquidacion_txObjetoNom").val();
		
	var anio = $("#Debitoliquidacion_txAnio").val();
	var mes = $("#Debitoliquidacion_txMes").val();
	
	var error = "";
	
	/* Caja no puede ser vacío */
	if (caja == '' || caja == 0)
		error += "<li>Ingrese una caja.</li>";
	
	/* Cuando se filtra por año y por mes, se debe ingresar un tributo */	
	if (anio == '') 
		error += "<li>Ingrese un año.</li>";

 	if (mes == '')
		error += "<li>Ingrese un mes.</li>";
	
	/* Cuando se filtra por objeto */
	if (objID != '' && objNom == '')
		error = "<li>Ingrese un objeto válido.</li>";
		
	
	
	if (error == "")
	{
		$.pjax.reload({
			container:"#PjaxGrillaDebitoLiq",
			method:"POST",
			data:{	
				caja:caja,
				trib:trib,
				obj_id:objID,
				anio:anio,
				mes:mes
			}
		});
	} else 
	{
		$.pjax.reload({container:"#errorDebitoliquidacion",method:"POST",data:{mensaje:error}});
	}
}

function limpiarEdit()
{
		$("#Debitoliquidacion_txGenerado").val("");
		$("#Debitoliquidacion_txEnviado").val("");
		$("#Debitoliquidacion_txRecepcion").val("");
		$("#Debitoliquidacion_txImputado").val("");
		$("#Debitoliquidacion_txCant").val("");
		$("#Debitoliquidacion_txtxMonto").val("");
		
		$("#Debitoliquidacion_txObjeto").val("");
		$("#Debitoliquidacion_txLiquidado").val("");
		$("#Debitoliquidacion_txDebitado").val("");
		$("#Debitoliquidacion_txFchDeb").val("");
		$("#Debitoliquidacion_txRechazoID").val("");
		$("#Debitoliquidacion_txRechazoNom").val("");
		$("#Debitoliquidacion_taObs").val("");
}

$(document).ready(function() {
	
	habilitaObjeto();
	
});
</script>

<?php
 		
	//Función que carga los datos 
	function EventosGrilla ($m) 
	{
	  $datos = "obj_id:'" . $m['obj_id'] . "',liq:" . $m['monto'] . ",deb:" . $m['montodeb'] . ",fchdeb:'" . $m['fchdeb'] . "',trechazo:" . $m['trechazo'] . ",rechazo:'" . $m['rechazo'] . "',obs:'" . $m['obs'] . "'";
      return ['onclick' => '$.pjax.reload({container:"#PjaxDatosLiq",data:{'.$datos.'},method:"POST"})'];
      
    }//Fin función que carga los datos

 	
 ?>
 
