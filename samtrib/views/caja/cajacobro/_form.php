<?php

/**
 * Inicialmente no se muestra ningún "div" de esta vista.
 *
 * La caja tiene que estar abierta para que se visualize "div_superior", en el cual se puede ingresar un Código en el edit que se muestra.
 * Si se ingresa un código, se muestra una ventana modal con el detalle del mismo. Esto da 2 opciones:
 * 		=> Si se pone "Aceptar", el Ticket pasará a formar parte de los Tickets que se cobrarán.
 * 		=> Si se pone "Cancelar", el Ticket no pasará a formar parte de los Tickets que se cobrarán.
 *
 * Una vez que se haya ingresado el código de un Ticket, se hará visible "div_cobrar" en el cual se listarán los Tickets que se cobrarán
 * y el total de la operación.
 * Se debe dar la opción de eliminar Tickets ingresados.
 * Cuando se presione el botón "Aceptar", se mostrará "div_ingreso" y "div_superior" y "div_cobrar" se deshabilitan.
 *
 * En "div_ingreso" se seleccionan los Medios de Pago y se van agregando en el listado que se visualiza. Además se muestra el total cubierto con los
 * Medios de Pago seleccionados hasta el momento.
 * Se debe dar la opción de eliminar Medios de Pago ingresados.
 * Si los Medios de Pago seleccionados cubren el Total que se quiere cobrar, se habilita el botón "Aceptar".
 * Cuando se presione el botón "Aceptar", se mostrará "div_egreso" y "div_ingreso" se deshabilita.
 *
 * En "div_egreso" y en caso de que los ingresos sean mayores al Total que se desea cobrar, se mostrará un listado con los Medios de Pago
 * seleccionados para realizar la devolución al cliente.
 *
 * Una vez que se seleccionan los Medios de Pago para egresos, se puede finalizar la Operación.
 *
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Tabs;
use yii\web\Session;
use yii\bootstrap\Alert;
use yii\data\ArrayDataProvider;
use app\models\caja\CajaCobro;

//$model->reiniciaModelo();

/**
 * Forma que se dibuja cuando se llega a Cobros
 * Recibo:
 * 			=> $model -> Modelo de Pagocta
 */

/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */


echo $this->render('js_form.js');

$session = new Session;

?>

<style>
#cajaCobro_codBarra {
	height:30px;
	font-size:18px;
	font-weight:bold;

}

#ModalCajaDetalle .modal-content{

	width: 74%;
	margin-left: 13%;
}

.table {
    margin-bottom: 0px;
}

</style>

<!-- INICIO DIV Caja Cobro -->
<div id="div_cajacobro" style="display:block">

<!-- INICIO Mensajes de error -->
<div style="margin-rigth: 15px; margin-bottom: 8px">

	<?php

	Pjax::begin(['id'=>'mensajesCajaCobro', 'timeout' => 1000000 ]);

		$mensaje = Yii::$app->request->post('mensaje','');

		$m = Yii::$app->request->post('m',2);

		if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

		if($mensaje != "")
		{

	    	Alert::begin([
	    		'id' => 'AlertaFormCajaCobro',
				'options' => [
	        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
	        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
	    		],
			]);

			echo $mensaje;

			Alert::end();

			echo "<script>window.setTimeout(function() { $('#AlertaFormCajaCobro').alert('close'); }, 5000)</script>";
		 }

	 Pjax::end();

	?>
</div>
<!-- FIN Mensajes de error -->

<?php

//Bloque de código que se encargará de ocultar y mostrar los distintos DIVs.
Pjax::begin(['id'=>'manejadorDinamico', 'timeout' => 1000000 ]);

?>

<!-- INICIO DIV Superior -->
<div id="div_superior" style="display:none" class="form-panel">

	<table  width="100%">
		<tr>
			<td style="padding-right:20px" align="center">
				<?= Html::input('text','codBarra', '', [
						'id'=>'cajaCobro_codBarra',
						'class'=>'form-control',
						'style'=>'width:80%; margin-bottom:6px; text-align:center',
						'maxlength'=>'42',
						'disabled'=> false,
						'onkeypress'=>'return totalCobro(event,$(this).val())',
					]);
				?>
				<?php
					if (utb::samConfig()["ctaredondeo"] > 0) echo Html::checkbox('ckAplicaRed',true, ['label'=>'Aplica Redondeo','id'=>'cajaPrueba_ckAplicaRed']) ;
				?>
			</td>
		</tr>
	</table>
</div>
<!-- FIN DIV Superior -->



<!-- INICIO DIV Cobrar -->
<div id="div_cobrar" style="display:none" class="form-panel" >

<?php

	Pjax::begin(['id' => 'PjaxCobrar', 'timeout' => 1000000 ]);

		//Variable que almacenará el código de Ticket a eliminar.
		$eliminar = Yii::$app->request->post('eliminar_id',0);
		$faci_id = Yii::$app->request->post('faci_id',0);
		$anio = Yii::$app->request->post('anio',0);

		if ($eliminar != 0 or $faci_id != 0 or $anio != 0)
		{
			//Ejecuto el método para eliminar un Ticket
			$res = $model->borraTicket($eliminar,$faci_id,$anio);


			if (count($model->dataProviderTicket) == 0)
			{
				//Asignar a caja_posicion la posición 'A1'
		 		$model->caja_posicion = 'A1';
				echo '<script>cajaActiva();</script>';

			}
		}

?>
	<table  width="100%">
		<tr>
			<td><h3><strong>A cobrar:</strong></h3></td>
		</tr>
	</table>

	<!-- INICIO Grilla Cobro -->
	<div style="padding-right:20px;margin-bottom:8px">
 	<?php

	 	echo GridView::widget([
					'id' => 'GrillaCobro',
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'dataProvider' => new ArrayDataProvider(['allModels' => $model->dataProviderTicket, 'key' => 'ctacte_id']),
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [
							['attribute'=>'trib_id','header' => 'Tributo', 'contentOptions'=>['style'=>'text-align:center','width'=>'50px']],
							['attribute'=>'trib_nom','header' => 'Trib. Nom.', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
							['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
							['attribute'=>'subcta','header' => 'Cta.', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
							['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
							['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
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
														'onclick' => '$.pjax.reload({container:"#PjaxCobrar",method:"POST",data:{eliminar_id:'.$model["ctacte_id"].',faci_id:'.$model["faci_id"].',anio:'.$model["anio"].'}});']);
												},
								]
							]
			        	],
				]);

 	?>
 	</div>
	<!-- FIN Grilla Cobro -->

	<table width="100%" style="margin-bottom:5px">
		<tr>
			<td align="right" style="padding-right:20px">
				<?= Html::button('Cobrar',['id'=>'cajaCobro_btCobrar','class'=>'btn btn-primary',
									'onclick' =>'$.pjax.reload({container:"#PjaxBtCobrar",method:"POST",data:{cobrar:1}})']) ?>
				<label>Total:</label>
				<?= Html::input('text','txTotalCobro',$model->opera_montototal,[
										'id'=>'cajaCobro_txTotalCobro',
										'class'=>'form-control solo-lectura',
										'style'=>'width:70px;text-align:right',
										]) ?>
			</td>
		</tr>
	</table>

<?php
	Pjax::end();

?>


	<script>
	$( document ).ready(function() {

		$("#PjaxCobrar").on("pjax:end", function(){

			$.pjax.reload("#PjaxMenuDerecho");

		});

	});
	</script>

</div>
<!-- FIN DIV Cobrar -->



<!-- INICIO DIV Ingreso -->

<div id="div_ingreso" style="display:none" class="form-panel">

	<table>
		<tr>
			<td width="150px"><h3><strong>Ingreso Mdp:</strong></h3></td>
		</tr>
	</table>


	<?php

		Pjax::begin(['id' => 'PjaxGrillaIMdp', 'timeout' => 1000000 ]);

	?>

	<!-- INICIO Grilla IMdp -->
	<div style="padding-right:20px;margin-bottom:8px">

	<?php
        echo GridView::widget([
			'id' => 'GrillaImdp',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => new ArrayDataProvider(['allModels' => $model->dataProviderIMdp, 'key' => 'id']),
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'mdp','header' => 'Cod.', 'contentOptions'=>['style'=>'text-align:center','width'=>'20px']],
					['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'50px']],
					['attribute'=>'comprob','header' => 'Comprob.', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
					['attribute'=>'cant','header' => 'Cant.', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
					['attribute'=>'cotiza','header' => 'Cotiza', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
					['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'40px']],
					[
						'class' => 'yii\grid\ActionColumn',
						'contentOptions'=>['style'=>'width:10px;text-align:center'],
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
												'onclick' => '$.pjax.reload({container:"#PjaxEliminarMDP",method:"POST",data:{tipo:1,eliminarmdp_id:'.$model["id"].'}});']);
										},
						]
					]
	        	],
		]);

		$sobrante = number_format(($model->opera_montototal - $model->opera_entregado),2,'.','');
	?>
	</div>
	<!-- FIN Grilla IMdp -->

	<table width="100%" style="margin-bottom:5px">
		<tr>
			<td align="right">
				<label>Medio:</label>
				<?= Html::dropDownList('dlIMdp',1,$mediosPago,['id'=>'cajaCobro_dlIMdp','class'=>'form-control','style' => 'width:150px']) ?>
				<?= Html::input('text','txTotalIngreso',$sobrante > 0 ? $sobrante : '0.00',['id'=>'cajaCobro_txTotalIngreso','class'=>'form-control','style'=>'width:70px;text-align:right','onkeypress'=>'return justDecimal($(this).val(),event,2)']) ?>
				<!-- INICIO Botón Agregar Ingreso Mdp -->
				<?= Html::button('<span class="glyphicon glyphicon-play"></span>',['id'=>'btAgregarIMdp','class'=>'btn btn-success','onclick' => 'nuevoIMdp()']) ?>
				<!-- FIN Botón Agregar Ingreso Mdp -->
				<?= Html::input('text','txTotalImdp',$model->opera_entregado,['id'=>'cajaCobro_txTotalImdp','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right']) ?>
			</td>
			<td style="padding-right:20px" align="right">
				<?= Html::button('OK',['id'=>'btAceptarIMdp','class'=>'btn btn-primary','onclick'=> '$.pjax.reload({container:"#PjaxAceptaIMdp",method:"POST",data:{validarIMDp:1}})']) ?>
			</td>
		</tr>
	</table>

	<?php

		if ($sobrante > 0)
		{

	?>
			<script>
			//Poner el foco en el botón "AgregarMDP"
			document.getElementById("btAgregarIMdp").focus();
			</script>

	<?php
		} else
		{
	?>
			<script>
			//Poner el foco en el botón "OK"
			document.getElementById("btAceptarIMdp").focus();
			</script>

	<?php
		}

		Pjax::end();

	?>
</div>
<!-- FIN DIV Ingreso -->


<!-- INICIO DIV Egreso -->
<div id="div_egreso" style="display:none;padding-right:20px" class="form-panel">

	<table>
		<tr>
			<td width="150px"><h3><strong>Egreso Mdp:</strong></h3></td>
		</tr>
	</table>


	<?php

		Pjax::begin(['id' => 'PjaxGrillaEMdp', 'timeout' => 1000000 ]);

	?>
	<!-- INICIO Grilla EMdp -->
	<div style="margin-bottom:8px">

	<?php

		 	echo GridView::widget([
				'id' => 'GrillaEmdp',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => new ArrayDataProvider(['allModels' => $model->dataProviderEMdp, 'key' => 'id']),
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'mdp','header' => 'Cod.', 'contentOptions'=>['style'=>'text-align:center','width'=>'20px']],
						['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'50px']],
						['attribute'=>'comprob','header' => 'Comprob.', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						['attribute'=>'cant','header' => 'Cant.', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
						['attribute'=>'cotiza','header' => 'Cotiza', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
						['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'40px']],
						[
							'class' => 'yii\grid\ActionColumn',
							'contentOptions'=>['style'=>'width:10px;text-align:center'],
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
													'onclick' => '$.pjax.reload({container:"#PjaxEliminarMDP",method:"POST",data:{tipo:2,eliminarmdp_id:'.$model["id"].'}});']);
											},
							]
						]
		        	],
			]);

	?>
	</div>
	<!-- FIN Grilla EMdp -->

	<table  width="100%" style="margin-bottom:5px">
		<tr>
			<td align="right">
				<label>Medio:</label>
				<?= Html::dropDownList('dlEMdp',1,$mediosPago,['id'=>'cajaCobro_dlEMdp','class'=>'form-control','style' => 'width:150px']) ?>
				<?= Html::input('text','txTotalEgreso',$model->opera_sobrante,[
										'id'=>'cajaCobro_txTotalEgreso',
										'class'=>'form-control',
										'style'=>'width:70px;text-align:right',
										'onkeypress'=>'return justDecimal($(this).val(),event,2)']) ?>
				<!-- INICIO Botón Agregar Ingreso Mdp -->
				<?=
					Html::button('<span class="glyphicon glyphicon-play"></span>',[
						'id'=>'cajaCobro_btAgregarEMdp',
						'class'=>'btn btn-success',
						'onclick' => 'nuevoEMdp()',
					]);
				?>
				<!-- FIN Botón Agregar Ingreso Mdp -->
				<?= Html::input('text','txTotalEmdp',$model->opera_vuelto,['id'=>'cajaCobro_txTotalEmdp','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right']) ?>
			</td>
		</tr>
	</table>



	<?php

		if (intval($model->opera_sobrante) == 0)
		{
			?>

				<script>
					//Poner el foco en el botón "Aceptar"
					$(document).ready(function() {
				  		document.getElementById("cajaCobro_btAceptarCobro").focus();
					});


				</script>

			<?php

		} else
		{
			?>

				<script>
				//Poner el foco en el botón "AgregarEMDP"
				$(document).ready(function() {
				  		window.setTimeout(function() { document.getElementById("cajaCobro_btAgregarEMdp").focus(); },0);
					});


				</script>

			<?php
		}

		Pjax::end();

	?>

</div>
<!-- FIN DIV Egreso -->

<!-- INICIO DIV Inferior -->
<div id="div_inferior_final" style="display:none">
<?= Html::button('Aceptar',['id' => 'cajaCobro_btAceptarCobro','onclick'=>'$.pjax.reload({container:"#PjaxAceptaOperacion",method:"POST",data:{aceptaOperacion:1}})','class'=>'btn btn-primary']); ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?= Html::button('Cancelar',['onclick'=>'cajaCobro_btCancelar()','class'=>'btn btn-danger']); ?>
</div>
<!-- FIN DIV Inferior -->

<!-- INICIO DIV Inferior -->
<div id="div_inferior" style="display:none">

<?= Html::button('Cancelar',['onclick'=>'cajaCobro_btCancelar()','class'=>'btn btn-danger']); ?>
</div>
<!-- FIN DIV Inferior -->

</div>
<!-- FIN DIV Caja Cobro -->

<!-- Inicio Div Errores -->
<div id="caja_errorSummary" class="error-summary" style="display: none; margin-top: 8px; margin-right: 15px">

</div>
<!-- Fin Div Errores -->

<?php

	//Si la caja está cerrada, se ocultan todas las opciones.
	if ($model->caja_estado == 'C')
	{
		echo '<script>cajaCerrada();</script>';

	}

	if ($model->caja_estado == 'A')
	{
		echo '<script>cajaActiva();</script>';

		if ($model->caja_posicion == 'A2')
		{
			echo '<script>cajaConTicket();</script>';
		}

		if ($model->caja_posicion == 'A3')
		{
			echo '<script>cajaIMDP();</script>';
		}

		if ($model->caja_posicion == 'A4')
		{
			if (intval($model->opera_sobrante) == 0 && intval($model->opera_vuelto) == 0)
			{
				echo '<script>cajaInferior();</script>';
			} else
			{
				echo '<script>cajaEMDP();</script>';
			}
		}
	}

Pjax::end();

Pjax::begin(['id'=>'reiniciaModelo', 'timeout' => 1000000 ]);

$reinicia = Yii::$app->request->post('reiniciaModelo',0);

if ($reinicia == 1)
{
	$model->reiniciaModelo();

	?>
	<script>
		$.pjax.reload({container:"#manejadorDinamico",method:"POST"});

		$("#manejadorDinamico").on("pjax:end", function(){

			$.pjax.reload({container:"#PjaxMenuDerecho",method:"POST"});
			$("#manejadorDinamico").off("pjax:end");
		});
	</script>
	<?php
}


Pjax::end();

/*
 * INICIO Ventana Modal Apertura
 * Esta ventana se muestra cuando se desea hacer la Apertura de una Caja.
 */
 Modal::begin([
	'id' => 'ModalAperturaCaja',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Apertura de Caja</h2>',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
		'id' => 'btCancelarModalAperturaCaja'
		],
]);

	echo $this->render('abrircaja',['id' => 'AperturaCaja','accion' => 'A']);

Modal::end();
//FIN Ventana Modal Apertura

/*
 * INICIO Ventana Modal Reapertura
 * Esta ventana se muestra cuando se desea hacer la Reapertura de una Caja.
 */
 Modal::begin([
	'id' => 'ModalReaperturaCaja',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Reapertura de Caja</h2>',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
		'id' => 'btCancelarModalReaperturaCaja'
		],
]);

	echo $this->render('abrircaja',['id' => 'ReaperturaCaja','accion' => 'R']);

Modal::end();
//FIN Ventana Modal Reapertura

/*
 * INICIO Ventana Modal Cierre
 * Esta ventana se muestra cuando se desea hacer el cierre de una Caja.
 */
 Modal::begin([
	'id' => 'ModalCierreCaja',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Cierre de Caja</h2>',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
		'id' => 'btCancelarModalCierreCaja'
		],
]);

	echo $this->render('abrircaja',['id' => 'CierreCaja','accion' => 'C']);

Modal::end();
//FIN Ventana Modal Cierre

/*
 * INICIO Ventana Modal Anulación Operación
 * Esta ventana se muestra cuando se desea hacer una anulación de una operación.
 */
 Modal::begin([
	'id' => 'ModalAnulacionOpera',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Anular Operación</h2>',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
		'id' => 'btCancelarModalAnulacionOperaCaja'
		],
]);

	echo $this->render('anulacionopera',['model' => $model]);

Modal::end();
//FIN Ventana Modal Anulación Operación

/*
 * INICIO Ventana Modal Anulación Ticket
 * Esta ventana se muestra cuando se desea hacer una anulación de un ticket.
 */
 Modal::begin([
	'id' => 'ModalAnulacionTicket',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Anular Ticket</h2>',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
		'id' => 'btCancelarModalAnulacionTicketCaja'
		],
]);

	echo $this->render('anulacionticket',['model' => $model]);

Modal::end();
//FIN Ventana Modal Anulación


Pjax::begin(['id' => 'PjaxSellado', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 1000000 ]);

	/*
	 * INICIO Ventana Modal Sellado
	 * Esta ventana se muestra cuando se desea cobrar un sellado.
	 */
	 Modal::begin([
		'id' => 'ModalSellado',
		'size' => 'modal-sm',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Cobro Sellado</h2>',
		'closeButton' => [
			'label' => '<b>X</b>',
			'class' => 'btn btn-danger btn-sm pull-right',
			'id' => 'btCancelarModalSelladoCaja'
			],
	]);

		echo $this->render('sellado',['model' => $model]);

	Modal::end();
	//FIN Ventana Modal Sellado

Pjax::end();

/*
 * INICIO Ventana Modal Boleto
 * Esta ventana se muestra cuando se desea cobrar un boleto.
 */
 Modal::begin([
	'id' => 'ModalBoleto',
	'size' => 'modal-normal',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Cobro Boleto</h2>',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
		'id' => 'btCancelarModalSelladoCaja'
		],
]);

	echo $this->render('boleto',['model' => $model]);

Modal::end();
//FIN Ventana Modal Boleto

/*
 * INICIO Ventana Modal Cheque Cartera
 * Esta ventana se muestra cuando se ingresa como medio de pago un cheque cartera.
 */
 Modal::begin([
	'id' => 'ModalChequeCartera',
	'size' => 'modal-normal',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Medio Pago Especial</h2>',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
		'id' => 'btCancelarModalChequeCartera'
		],
]);

	echo $this->render('chequeCartera');

Modal::end();
//FIN Ventana Modal Arqueo

/*
 * INICIO Ventana Modal Medio de Pago Especial
 * Esta ventana se muestra cuando se ingresa como medio de pago un medio de pago especial.
 */
 Modal::begin([
	'id' => 'ModalMDPEspecial',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Medio Pago Especial</h2>',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
		'id' => 'btCancelarModalMDPEspecial'
		],
]);

	echo $this->render('mpespecial',['model' => $model]);

Modal::end();
//FIN Ventana Modal Arqueo

/*
 * INICIO Ventana Modal Arqueo
 * Esta ventana se muestra cuando se desea cargar el arqueo.
 */
 Modal::begin([
	'id' => 'ModalArqueo',
	'size' => 'modal-normal',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Arqueo de Caja</h2>',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
		'id' => 'btCancelarModalArqueoCaja'
		],
]);

	echo $this->render('arqueo',['model' => $model]);

Modal::end();
//FIN Ventana Modal Arqueo


//El listado de medios de pagos especiales se muestra en una nueva ventana

/*
 * INICIO Ventana Modal Mdp Especial
 * Esta ventana se muestra cuando se desean lsitar los medios de pago especiales utilizados en un día determinado.
 */
// Modal::begin([
//	'id' => 'ModalListaMDPE',
//	'size' => 'modal-lg',
//	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Medios de Pago Especiales</h2>',
//	'closeButton' => [
//		'label' => '<b>X</b>',
//		'class' => 'btn btn-danger btn-sm pull-right',
//		'id' => 'btCancelarModalListaMDPE'
//		],
//]);
//
//	echo $this->render('listamdpe',['model' => $model]);
//
//Modal::end();
//FIN Ventana Modal Mdp Especial

//INICIO Bloque de código que busca los datos del ticket
Pjax::begin(['id' => 'PjaxAgregaTicket', 'timeout' => 1000000 ]);

	//Obtengo el código de ticket
	$ticket = Yii::$app->request->post('ticket','');
	$redondeo = Yii::$app->request->post('redondeo',0);

	if ($ticket != '')
	{
		$res = $model->cargarTicket($ticket,$redondeo);

		if ($res['return'] == 1)
		{
            //Oculto div mensaje de errores
            echo '<script>$( "#caja_errorSummary" ).css( "display", "none" );</script>';

			/*
			 * INICIO Ventana Modal Caja Detalle
			 * Esta ventana se encarga de mostrar los datos correspondientes al código ingresado.
			 *
			 */
			Modal::begin([
				'id' => 'ModalCajaDetalle',
				'size' => 'modal-normal',
				'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Caja Detalle</h2>',
				'closeButton' => [
					'label' => '<b>X</b>',
					'class' => 'btn btn-danger btn-sm pull-right',
					'id' => 'btCancelarModalCajaDetalle'
					],
			]);

				echo $this->render('cajadetalle',['model' => $model]);

			Modal::end();
			//FIN Ventana Modal Caja Detalle

			echo '<script>$("#ModalCajaDetalle").modal("show");</script>';

			?>
			<?php


		} else //Muestro el error
		{
			?>
			<script>
			$( "#PjaxAgregaTicket" ).on( "pjax:end", function() {

				mostrarErrores( [ "<?= $res['mensaje'] ?>" ], "#caja_errorSummary" );
				$("#cajaCobro_codBarra").focus();
				$( "#PjaxAgregaTicket" ).off( "pjax:end" );
			});
			</script>
			<?php



		}
	}

Pjax::end();
//FIN Bloque de código que busca los datos del ticket

//INICIO Bloque de código que carga los datos del Ticket
Pjax::begin(['id' => 'PjaxNuevoTicket', 'timeout' => 1000000 ]);

	$ejecuta = Yii::$app->request->post('ejecuta',0);

	if ($ejecuta == 1)
	{

		$res = $model->nuevoTicket();

		if ($res['return'] == 1)
		{
            //Oculto div mensaje de errores
            echo '<script>$( "#caja_errorSummary" ).css( "display", "none" );</script>';

			?>
				<script>
					$("#PjaxNuevoTicket").on("pjax:end", function(){

						cajaConTicket();

						$.pjax.reload("#PjaxCobrar");

						$("#PjaxNuevoTicket").off("pjax:end");
					});

				</script>
			<?php

		} else
		{
            echo '<script>mostrarErrores( [ "'. $res['mensaje'] .'" ], "#caja_errorSummary" );</script>';
		}
	}

Pjax::end();
//FIN Bloque de código que carga los datos del Ticket

//INICIO Bloque de código que se ejecuta al presionar el botón "Cobrar"
Pjax::begin(['id' => 'PjaxBtCobrar', 'timeout' => 1000000 ]);

	$cobrar = Yii::$app->request->post('cobrar',0);

	if ($cobrar == 1)
	{
		$model->caja_estado_estado = 'P';

		//Asignar a caja_posicion la posición 'A3'
 		$model->caja_posicion = 'A3';

		echo '<script>btCobrar();</script>';

	}


Pjax::end();
//FIN Bloque de código que se ejecuta al presionar el botón "Cobrar"

//INICIO Bloque de código que inserta los medios de pago
Pjax::begin(['id' => 'PjaxMedioPago', 'timeout' => 1000000 ]);

	$tipoMDP = Yii::$app->request->post('tipoMDP',0);
	$medioMDP = Yii::$app->request->post('medioMDP',0);
	$cantMDP = Yii::$app->request->post('cantMDP',0);

	if ( $tipoMDP != 0 && $medioMDP != 0 && $cantMDP != 0 )
	{
		//Verificar si el medio de pago es especial o no. De ser especial, se debe mostrar la ventana modal
		$especial = $model->getTipoMDP( $medioMDP );

		if ($especial == 'EF' || $especial == 'BO' || $especial == 'CA')	//Agrego el medio de pago al DataProvider correspondiente
		{
			// Si el tipo de pago es CA = 'Cheque Cartera', se debe mostrar la ventana que permite ingresar el cheque cartera
			if ( $especial == 'CA' )
			{
				?>

				<script>

					$.pjax.reload({
						container:"#PjaxFormChequeCartera",
						method:"POST",
						data:{
							tipo:<?=  $tipoMDP ?>,
						}
					});

					$("#PjaxFormChequeCartera").on("pjax:end", function(){

						$("#ModalChequeCartera").modal("show");
						$("#PjaxFormChequeCartera").off("pjax:end");
					});

				</script>

				<?php
			} else
			{
				$res = $model->agregoMdp($tipoMDP,$medioMDP,$cantMDP);

				if ($res['return'] == 1)
				{
                    //Oculto div mensaje de errores
                    echo '<script>$( "#caja_errorSummary" ).css( "display", "none" );</script>';

					if ($tipoMDP == 1)
						echo '<script>$.pjax.reload({container:"#PjaxGrillaIMdp",method:"POST"});</script>';
					else
						echo '<script>$.pjax.reload({container:"#PjaxGrillaEMdp",method:"POST"});</script>';

                } else
				{
					if ($tipoMDP == 2)
						echo '<script>$("#cajaCobro_txTotalEgreso").val("'.$model->opera_sobrante.'")</script>';

                    echo '<script>mostrarErrores( [ "'. $res['mensaje'] .'" ], "#caja_errorSummary" );</script>';
				}
			}


		} else 	//Medio de pago especial. Muestro la ventana modal
		{
			?>

			<script>
				$.pjax.reload({
					container:"#PjaxMDP",
					method:"POST",
					data:{
						mdp_num:<?=  $medioMDP?>,
						mdp_tipo:"<?= $especial ?>",
						mdp_monto:<?= $cantMDP ?>,
						mdp_tipoMDP:<?= $tipoMDP ?>,
					}});

				$("#PjaxMDP").on("pjax:end", function(){

					$("#ModalMDPEspecial").modal("show");
					$("#PjaxMDP").off("pjax:end");
				});

			</script>

			<?php

		}

	}

Pjax::end();
//FIN Bloque de código que inserta los medios de pago

//INICIO Bloque de código que valida los medios de pago y la cantidad
Pjax::begin(['id' => 'PjaxAceptaIMdp', 'timeout' => 1000000 ]);

	$validar = Yii::$app->request->post('validarIMDp',0);

	if ($validar == 1)
	{
		$res = $model->validarIMdp();

		if ($res['return'] == 1)	//Habilito egreso medio de pago
		{
            //Oculto div mensaje de errores
            echo '<script>$( "#caja_errorSummary" ).css( "display", "none" );</script>';

			//Asignar a caja_posicion la posición 'A4'
	 		$model->caja_posicion = 'A4';

	 		if ($model->opera_sobrante > 0)
				echo '<script>muestraEMdp();</script>';
			else
				echo '<script>muestraInferior();</script>';

		} else	//muestro mensaje de error
            echo '<script>mostrarErrores( [ "'. $res['mensaje'] .'" ], "#caja_errorSummary" );</script>';
	}


Pjax::end();
//FIN Bloque de código que valida los medios de pago y la cantidad

//INICIO Bloque de código que eliminar un medio de pago
Pjax::begin(['id' => 'PjaxEliminarMDP', 'timeout' => 1000000 ]);

	$tipoMDP = Yii::$app->request->post('tipo',0);
	$eliminar_id = Yii::$app->request->post('eliminarmdp_id',0);

	if ($tipoMDP != 0 && $eliminar_id != 0)
	{
		$res = $model->eliminaMDP($tipoMDP,$eliminar_id);

		if ($res['return'] == 1)
		{
            //Oculto div mensaje de errores
            echo '<script>$( "#caja_errorSummary" ).css( "display", "none" );</script>';

			if ($tipoMDP == 1)
				echo '<script>$.pjax.reload({container:"#PjaxGrillaIMdp",method:"POST"});</script>';
			else
				echo '<script>$.pjax.reload({container:"#PjaxGrillaEMdp",method:"POST"});</script>';
		}
		else
            echo '<script>mostrarErrores( [ "'. $res['mensaje'] .'" ], "#caja_errorSummary" );</script>';
	}

Pjax::end();
//FIN Bloque de código que eliminar un medio de pago

Pjax::begin(['id' => 'PjaxAceptaOperacion', 'timeout' => 1000000 ]);

	$aceptar = Yii::$app->request->post('aceptaOperacion',0);

	if ($aceptar == 1) //Código para aceptar la operación
	{
		$res = $model->aceptarOperacion();

		if ($res['return'] == 1)
		{
            //Oculto div mensaje de errores
            echo '<script>$( "#caja_errorSummary" ).css( "display", "none" );</script>';

			//Asignar a caja_posicion la posición 'A1'
	 		$model->caja_posicion = 'A1';

	 		//Poner cajas en estado 'A'
	 		$model->caja_estado_estado = 'A';

			?>
				<script>
				$.pjax.reload( "#manejadorDinamico" );

				$("#manejadorDinamico").on("pjax:end", function(){

					$.pjax.reload({
						container:"#mensajesCajaCobro",
						data:{
							mensaje:"La Operación finalizó correctamente.",
							m:1,
						},
						method:"POST"});

					$("#mensajesCajaCobro").on("pjax:end", function(){

						$.pjax.reload( "#PjaxMenuDerecho" );
						$("#mensajesCajaCobro").off("pjax:end");
					});


					$("#manejadorDinamico").off("pjax:end");
				});

				</script>

			<?php

		}
		else
            echo '<script>mostrarErrores( [ "'. $res['mensaje'] .'" ], "#caja_errorSummary" );</script>';
	}

Pjax::end();
?>
