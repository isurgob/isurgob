<?php
/**
 * Forma que se dibuja como ventana modal.
 * Se encarga de la "Sellado".
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

$form = ActiveForm::begin(['id' => 'formSellado',
					'action' => ['sellado']]);
?>
<style>
#ModalSellado .modal-content{width:320px !important;}
</style>

<div class="form_sellado">
<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorSelladoCaja']);

			$mensaje = '';

			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaSelladoCaja',
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaSelladoCaja').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<table>
	<tr>
		<td width="45px">
			<label for="selladoCaja_dlTrib">Tributo:</label>
		</td>
		<td>
			<?= Html::dropDownList('dlTrib','',utb::getAux('trib','trib_id','nombre',1,'tipo = 6'),[
							'id'=>'selladoCaja_dlTrib',
							'class'=>'form-control',
							'onchange' => '$.pjax.reload({container:"#PjaxCambiaTributo",method:"POST",data:{trib:$(this).val()}})']); ?>
		</td>
	</tr>
</table>

<?php

	Pjax::begin(['id' => 'PjaxCambiaTributo']);
?>
<table>
	<tr>
		<td width="45px">
			<label>Ítem:</label>
		</td>
		<td>
			<?php

				$trib = Yii::$app->request->post('trib',0);

				echo Html::dropDownList('dlItem','',
					utb::getAux('item','item_id','nombre',0,'trib_id = ' . $trib . ' AND item_id IN (SELECT item_id FROM item_vigencia WHERE ' . utb::peractual($trib) . 'BETWEEN perdesde AND perhasta)'),[
										'id'=>'selladoCaja_dlItem',
										'class'=>'form-control',
										'onchange' => 'f_cambiaItem()']);

			?>
		</td>
	</tr>
</table>

<ul class='menu_derecho'>
<li><hr style="color: #FFF; margin:1px" /></li>
</ul>

<?php

	Pjax::begin(['id'=>'PjaxCambiaItem']);

		$item_id = Yii::$app->request->post('item_id',0);

		$data = [];

		if ($item_id != 0)
		{
			$data = $model->arregloSellado( $item_id );
		}

		if (count($data) > 0)
		{
			?>

			<table>
				<tr>

					<?php

						$i = 0;

						foreach($data as $dato)
						{
							if ($dato != '')
							{
								$i++;

								?>
								<td width="50px">
									<label><?= $dato ?></label>
									<?= Html::input('text','txPar'.$i,0,[
											'id'=>'selladoCaja_txPar'.$i,
											'class'=>'form-control',
											'style'=>'width:100%;text-align:right',
											'onkeypress'=>'$("#selladoCaja_btAceptar").addClass("disabled");return justDecimal($(this).val(),event,2);',
											'maxlength' => 10,
										]);
									?>
								</td>
								<td width="15px"></td>

								<?php


							}
						}

					?>
				</tr>
			</table>

		<?php

	}

		echo '<script>$("#selladoCaja_txTotal").val("0.00")</script>';

	Pjax::end();



Pjax::end();

?>

<table width="100%" style="padding-right:20px;margin-top:8px">
	<tr>
		<td align="left" width="100px"><?= Html::button('Calcular',['class'=>'btn btn-primary','onclick'=>"calcularSellado()"]); ?></td>
		<td align="right" width="90px">
			<label>Total:</label>
			<?= Html::input('text','txTotal',number_format(0,2,'.',''),['id' => 'selladoCaja_txTotal','class'=>'form-control solo-lectura','style'=>'width:80px;text-align:right']); ?>

		</td>
	</tr>
</table>

<ul class='menu_derecho'>
	<li><hr style="color: #FFF; margin:1px" /></li>
</ul>

<table width="100%">
	<tr><td width="30px"><label>Observación:</label></td></tr>
    <tr>
		<td><?= Html::textarea('txObs','',
										['id'=>'selladoCaja_txObs','class'=>'form-control','style'=>'width:290px;max-width:290px;height:100px;max-height:100px']); ?>
		</td>
	</tr>
</table>

<table style="margin-left:25px;margin-top:20px">
	<tr>
		<td width="15px"></td>
		<td>
			<?= Html::button('Aceptar',[
					'id' => 'selladoCaja_btAceptar',
					'class'=>'btn btn-success disabled',
					'onclick'=>"btAceptarSellado()",
				]);
			?>
		</td>
		<td width="25px"></td>
		<td><?= Html::button('Cancelar',['class'=>'btn btn-primary','onclick'=>"cerrarVentanaSellado()"]); ?></td>
	</tr>
</table>
</div>

<?php

ActiveForm::end();

//INICIO Bloque de código que realiza el cálculo de un Ítem.
Pjax::begin(['id'=>'PjaxCalculaSellado']);

	$trib = Yii::$app->request->post('trib',0);
	$item = Yii::$app->request->post('item',0);
	$par1 = Yii::$app->request->post('par1',0);
	$par2 = Yii::$app->request->post('par2',0);
	$par3 = Yii::$app->request->post('par3',0);
	$par4 = Yii::$app->request->post('par4',0);

	$total = $model->calculaItem($item,$par1,$par2,$par3,$par4);
	$total = number_format( $total, 2, '.', '' );

	//Habilitar el botón "Aceptar" en caso de que $total sea mayor a 0.
	echo '<script>$("#selladoCaja_btAceptar").toggleClass("disabled",'.$total.'<= 0)</script>';

	echo '<script>$("#selladoCaja_txTotal").val("' . $total . '")</script>';

Pjax::end();
//FIN Bloque de código que realiza el cálculo de un Ítem.

//INICIO Bloque de código que agrega un Ítem como Ticket.
Pjax::begin(['id'=>'PjaxAgregaSellado']);

	$trib = Yii::$app->request->post('trib',0);
	$item = Yii::$app->request->post('item',0);
	$total = Yii::$app->request->post('total',0);
	$obs = Yii::$app->request->post('obs','');

	if ($trib != 0)
	{
		$res = $model->nuevoSellado($trib,$item,$total,$obs);

		echo '<script>$.pjax.reload({container:"#PjaxNuevoTicket",method:"POST",data:{ejecuta:1}});' .
				'$("#ModalSellado").modal("hide");</script>';
	}

Pjax::end();
//FIN Bloque de código que agrega un Ítem como Ticket.

?>

<script>
function f_cambiaItem()
{
    var sellado = $("#selladoCaja_dlItem").val();

    if ( sellado == '' || sellado == null )
        sellado = 0;

	$.pjax.reload({
		container:"#PjaxCambiaItem",
		method:"POST",
		data:{
			item_id:sellado,
		}
	});

	$("#PjaxCambiaItem").on("pjax:end", function(){

		calcularSellado();

		$("#PjaxCambiaItem").off("pjax:end");

	});
}

function calcularSellado()
{
	var error = '';

	var trib = $("#selladoCaja_dlTrib").val(),
		item = $("#selladoCaja_dlItem").val(),
		par1 = $("#selladoCaja_txPar1").val(),
		par2 = $("#selladoCaja_txPar2").val(),
		par3 = $("#selladoCaja_txPar3").val(),
		par4 = $("#selladoCaja_txPar4").val();

	if (trib == 0)
		error += "<li>Ingrese un Tributo.</li>";

	if (item == 0)
		error += "<li>Ingrese un Ítem.</li>";

	if (par1 == '')
		par1 = 0;

	if (par2 == '')
		par2 = 0;

	if (par3 == '')
		par3 = 0;

	if (par4 == '')
		par4 = 0;

	if (error == '')
	{
		$.pjax.reload({
			container:"#PjaxCalculaSellado",
			method:"POST",
			data:{
				trib:trib,
				item:item,
				par1:par1,
				par2:par2,
				par3:par3,
				par4:par4,
			}
		});

		$("#PjaxCalculaSellado").on("pjax:end",function() {

			//$("#")

			$("#PjaxCalculaSellado").off("pjax:end");

		});
	}

}

function btAceptarSellado()
{
	var error = '';

	var trib = isNaN( $("#selladoCaja_dlTrib").val() ) ? 0 : $("#selladoCaja_dlTrib").val();
	var item = isNaN( $("#selladoCaja_dlItem").val() ) ? 0 : $("#selladoCaja_dlItem").val();
	var total = $("#selladoCaja_txTotal").val();
	var obs = $("#selladoCaja_txObs").val();

	if (trib == 0)
		error += "<li>Ingrese un Tributo.</li>";

	if (item == 0)
		error += "<li>Ingrese un Ítem.</li>";

	if (total < 0 || total == 0)
		error += "<li>El Monto Total no es válido.</li>";

	if (error == '')
	{
		$.pjax.reload({container:"#PjaxAgregaSellado",method:"POST",data:{
			trib:trib,
			item:item,
			total:total,
			obs:obs
		}});

	} else
		$.pjax.reload({container:"#errorSelladoCaja",method:"POST",data:{mensaje:error}});
}

function cerrarVentanaSellado()
{
	$("#selladoCaja_dlTrib").val(0);
	$("#selladoCaja_dlItem").val(0);
	$("#selladoCaja_txTotal").val("0.00");
	$("#selladoCaja_txObs").val("");

	$("#ModalSellado").modal("hide");
}

$("#ModalSellado").on("shown.bs.modal", function() {

	 f_cambiaItem();
});
</script>
