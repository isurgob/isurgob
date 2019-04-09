<?php
/**
 * Forma que se dibuja como ventana modal.
 * Se encarga de la "Boleto".
 * 
 */
 
use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use yii\web\Session;
use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;

Pjax::begin(['id'=>'PjaxBoleto']);

$reiniciar = Yii::$app->request->post('reiniciar',0);

if ($reiniciar == 1)
{
	$model->dataProviderItemTemp = [];
	$model->ticket_montoboleto= 0;
}
	
?>

<div class="form_boleto">
<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 

			Pjax::begin(['id'=>'errorBoletoCaja']);
			
			$mensaje = '';
			
			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];
		
			if($mensaje != "")
			{ 
		
		    	Alert::begin([
		    		'id' => 'AlertaBoletoCaja',
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
		    		],
				]);
		
				echo $mensaje;
						
				Alert::end();
				
				echo "<script>window.setTimeout(function() { $('#AlertaBoletoCaja').alert('close'); }, 5000)</script>";
			 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<table>
	<tr>
		<td width="55px">
			<label>Tributo:</label>
		</td>
		<td>
			<?= Html::dropDownList('dlTrib','',utb::getAux('trib','trib_id','nombre',0,'tipo = 7'),[
							'id'=>'boletoCaja_dlTrib',
							'class'=>'form-control',
							'style'=>'width:250px',
							'onchange' => 'f_boleto_cambiaTributo()']); ?>
		</td>
	</tr>
</table>

<?php

	Pjax::begin(['id' => 'PjaxCambiaItemBoleto']);
		
		$trib = Yii::$app->request->post('cambia_trib',utb::getCampo('trib','tipo = 7','trib_id'));
?>	
		<table>
			<tr>
				<td width="55px">
					<label>Ítem:</label>
				</td>
				<td>	
					<?= Html::dropDownList('dlItem',0,utb::getAux('item','item_id','nombre',1,'trib_id = ' . $trib . ' AND tipo = 1'),[
											'id'=>'boletoCaja_dlItem',
											'style'=>'width:250px',
											'class'=>'form-control',
											'onchange' => '$("#boletoCaja_txMontoBoleto").val("0.00");calculaBoleto();']);  ?>
				</td>
				<td width="20px"></td>
				<td>
					<label>Cant:</label>
					<?= Html::input('text','txCant','',[
									'id' => 'boletoCaja_txCantBoleto',
									'class'=>'form-control',				
									'style'=>'width:40px;text-align:center;',
									'maxlength' => 3,
									'onkeypress' => 'return justNumbers( event )',
									'onchange'=>'calculaBoleto();']) ?>
				</td>
				<td>	
					<?= Html::input('text','txMontoBoleto','0.00',[
							'id' => 'boletoCaja_txMontoBoleto',
							'class'=>'form-control solo-lectura',
							'style'=>'width:80px;text-align:right;',
							'tabindex' => -1,
						]); ?>
				</td>
				<td width="20px"></td>
				<td><?= Html::button('<span class="glyphicon glyphicon-plus"></span>',['class'=>'btn btn-primary','onclick'=>"agregarItem()"]); ?></td>
			</tr>
		</table>

<ul class='menu_derecho'>
<li><hr style="color: #FFF; margin:1px" /></li>
</ul>

<?php
						
	Pjax::end();

	Pjax::begin(['id' => 'PjaxGrillaBoleto']);
	
		$eliminaBoleto = Yii::$app->request->post('eliminaBoleto_id',0);
		
		if ($eliminaBoleto != 0)
		{
			//Ejecuto el método para eliminar un Ítem.
			$res = $model->quitoItem($eliminaBoleto);
			
		}
	
		echo GridView::widget([
			'id' => 'GrillaBoleto',
			//'headerRowOptions' => ['class' => 'grilla'],
			//'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => new ArrayDataProvider(['allModels' => $model->dataProviderItemTemp, 'key' => 'item_id']),
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'item_id','header' => 'Ítem ID', 'contentOptions'=>['style'=>'text-align:center','width'=>'50px']],
					['attribute'=>'item_nom','header' => 'Ítem Nom.', 'contentOptions'=>['style'=>'text-align:left','width'=>'300px']],
					['attribute'=>'cant','header' => 'Cant.', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
					['attribute'=>'pu','header' => 'PU', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
					['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'40px']],
					[
						'class' => 'yii\grid\ActionColumn',
						'contentOptions'=>['style'=>'width:20px'],
						'buttons'=>[			
							'view' =>  function()
										{
											return null;
										},
									
							'update' => function()
										{            							
											           							
											return null;
										},
							'delete' => function ($url, $model, $key)
										{
											return Html::button('<span class="glyphicon glyphicon-trash"></span>',['class'=>'bt-buscar-label','style'=>'color:#337ab7',
												'onclick' => '$.pjax.reload({container:"#PjaxGrillaBoleto",method:"POST",data:{eliminaBoleto_id:'.$model["item_id"].'}});']);
										},
						]
					]
	        	],
		]);
	
?>

<ul class='menu_derecho' style="margin-bottom:1px">
<li><hr style="color: #FFF; margin:1px" /></li>
</ul>

<table width="100%">
	<tr>
		<td align="right">	
			<label>Total:</label>
			<?= Html::input('text','txTotal',number_format($model->ticket_montoboleto,2,'.',''),[
					'id'=>'boletoCaja_txTotal',
					'class'=>'form-control solo-lectura',
					'style'=>'width:80px;text-align:right',
					'tabindex' => -1,
				]);
			?>
		</td>
	</tr>
</table>
	
	<script>
	
		$("#boletoCaja_btAceptar").toggleClass("disabled",parseFloat($("#boletoCaja_txTotal").val()) <= parseFloat(0));
		
	</script>
<?php

Pjax::end();

?>

<table width="100%">
	<tr>
		<td width="40px"><label>Obs:</label></td>
		<td width="92%" align="right">
			<?= Html::textarea('txObs','',[
					'id'=>'boletoCaja_txObs',
					'class'=>'form-control',
					'style'=>'width:100%;max-width:535px;min-width:535px;min-height:60px;max-height:120px',
				]);
			?>
		</td>
	</tr>
</table>
		
<table style="margin-left:10px;margin-top:20px">
	<tr>
		<td>
			<?= Html::button('Aceptar',[
					'id' => 'boletoCaja_btAceptar',
					'class'=>'btn btn-success disabled',
					'onclick'=>"btAceptarBoleto()",
				]);
			 ?>
		</td>
		<td width="25px"></td>
		<td><?= Html::button('Cancelar',['class'=>'btn btn-primary','onclick'=>"cerrarVentanaBoleto()"]); ?></td>
	</tr>
</table>
</div>

<?php
//INICIO Bloque de código que realiza el cálculo de un Ítem.
Pjax::begin(['id'=>'PjaxCalculaMontoBoleto']);
 		
	$total = number_format(0,2,'.','');
	
	$item_id = Yii::$app->request->post('item_calcula_monto',0);
	$cant = Yii::$app->request->post('cant_calcula_monto',1);
	
	if ($item_id != 0)
	{
		$total = number_format(floatval($model->calculaItem($item_id,$cant)),2,'.','');
	}
	
	echo '<script>$("#boletoCaja_txMontoBoleto").val("' . $total . '")</script>';
	
Pjax::end();
//FIN Bloque de código que realiza el cálculo de un Ítem.

Pjax::begin(['id' => 'PjaxAgregoItemBoleto']);
	
	$trib_id = Yii::$app->request->post('tribBoleto',0);
	$item_id = Yii::$app->request->post('itemBoleto',0);
	$cant = Yii::$app->request->post('cantBoleto',0);
	$monto = number_format($model->calculaItem($item_id,$cant),2,'.','');
	
	if ($trib_id != 0 && $item_id != 0)
	{
		$res = $model->agregoItem($trib_id,$item_id,$cant,$monto);
		
		if ($res['return'] == 1)
			echo '<script>$.pjax.reload({container:"#PjaxGrillaBoleto",method:"POST"});</script>';
		else
			echo '<script>$.pjax.reload({container:"#errorBoletoCaja",method:"POST",data:{mensaje:"<li>'.$res['mensaje'].'</li>"}});</script>';
	}
	
Pjax::end();

Pjax::begin(['id' => 'PjaxAgregarBoletoTicket']);
	
	$trib = Yii::$app->request->post('tribBoleto',0);
	$item = 0;
	$total = Yii::$app->request->post('totalBoleto',0);
	$obs = Yii::$app->request->post('obsBoleto','');
	
	if ($trib != 0 && $total != 0)
	{
		$res = $model->nuevoSellado($trib,$item,$total,$obs);	
		
		//if ($res['return'] == 1)
			echo '<script>$.pjax.reload({container:"#PjaxNuevoTicket",method:"POST",data:{ejecuta:1}});' .
				'$("#ModalBoleto").modal("hide");</script>';
		//else
		//	echo '<script>$.pjax.reload({container:"#errorBoletoCaja",method:"POST",data:{mensaje:"<li>'.$res['mensaje'].'</li>"}});</script>';
		
	}
	
Pjax::end();

Pjax::end();
?>

<script>
function f_boleto_cambiaTributo()
{
	$.pjax.reload({
		container:"#PjaxCambiaItemBoleto",
		method:"POST",
		data:{
			cambia_trib:$(boletoCaja_dlTrib).val(),
		}
	});
}

function calculaBoleto()
{
	var error = '';
	
	var item = $("#boletoCaja_dlItem").val();
	var cant = $("#boletoCaja_txCantBoleto").val();
	
	if (item == 0)
		error += "Ingrese un Ítem.</li>";
		
	if (cant == '')
		cant = 1;
	
	
	if (error == '')
		$.pjax.reload({	container:"#PjaxCalculaMontoBoleto",
						method:"POST",
						data:{
							item_calcula_monto:item,
							cant_calcula_monto:cant,
					}});
				
}

function agregarItem()
{
	trib = $("#boletoCaja_dlTrib").val();
	item = $("#boletoCaja_dlItem").val();
	cant = $("#boletoCaja_txCantBoleto").val();
	
	error = '';
	
	if (trib == 0)
		error += "<li>Ingrese un Tributo.</li>";
	
	if (item == 0)
		error += "<li>Ingrese un Ítem.</li>";
		
	if (cant == 0)
		error += "<li>Ingrese una cantidad.</li>";
	
	if ( error == '' )
	{
		//Limpio los edit y Lists
		$("#boletoCaja_dlItem").val(0);
		$("#boletoCaja_txCantBoleto").val("");
		$("#boletoCaja_txMontoBoleto").val("0.00");
		
		$.pjax.reload({
			container:"#PjaxAgregoItemBoleto",
			method:"POST",
			data:{
				tribBoleto:trib,
				itemBoleto:item,
				cantBoleto:cant,
			}
		});

	}
		
	else
		$.pjax.reload({container:"#errorBoletoCaja",method:"POST",data:{mensaje:error}});
}

function btAceptarBoleto()
{
	var error = '';
	
	var trib = $("#boletoCaja_dlTrib").val();
	var total = parseFloat($("#boletoCaja_txTotal").val());
	var obs = $("#boletoCaja_txObs").val();
	
	if (trib == 0)
		error += "<li>Ingrese un Tributo.</li>";
	
	if ( total <= parseFloat(0) )
		error += "<li>El Monto Total no es válido.</li>";
	
	if (error == '')
		$.pjax.reload({
			container:"#PjaxAgregarBoletoTicket",
			method:"POST",
			data:{
				tribBoleto:trib,
				totalBoleto:total,
				obsBoleto:obs
			}
		});
	
	else
		$.pjax.reload({container:"#errorBoletoCaja",method:"POST",data:{mensaje:error}});
}

function cerrarVentanaBoleto()
{
	$("#ModalBoleto").modal("hide");
}

$(document).ready(function(){
	
	f_boleto_cambiaTributo();
	
});
</script>