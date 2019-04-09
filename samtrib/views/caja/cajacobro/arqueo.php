<?php

/**
 * Forma que se dibuja como ventana modal.
 * Se encarga del "Arqueo".
 *
 * Si se accede a este formulario desde una caja abierta, se evalúa si no existe un arqueo ingresado previamente
 * para el ID de caja y fecha de la caja desde la cual se accede.
 * En caso de encontrarse un arqueo ya ingresado, se deben cargar los datos de este.
 *
 * Si se accede a este formulario desde una caja cerrada, se debe verificar que quien accede, disponga de los permisos
 * necesarios para ingresar.
 * En caso de poseer los permisos necesarios, se debe ingresar el ID de caja y fecha para la cuál se desea consultar el arqueo.
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
use app\models\caja\CajaCobro;
use yii\helpers\BaseUrl;

Pjax::begin(['id'=> 'PjaxArqueoCaja']);

/*
 * Cuando se accede a este formulario desde la caja del usuario, los datos de caja se cargan, y se impide que se modifique
 * el ID de caja y la fecha.
 *
 * Si se accede sin que se encuentre abierta una caja, se habilitan las opciones para ingresar la caja!
 */

//Obtengo los datos de caja, en caso de que se envíen.
$caja_id = Yii::$app->request->post('arqueo_caja_id','');	//Identificador de la caja.
$fecha = Yii::$app->request->post('arqueo_caja_fecha','');	//Fecha de la caja.
$reiniciarVariables = Yii::$app->request->post('arqueo_reiniciaVariables',0);	//Variable que utilizo para reiniciar las variables de Arqueo del modelo o para Cargar un arqueo ya guardado

if ($reiniciarVariables == 1)
	$model->reiniciaVariablesArqueo();

$arqueo = Yii::$app->request->post('arqueo',0);	//Variable que determina si el pjax se ejecuta desde el mismo form o es ejecutado externamente. 0. Externo - 1. Local

$habilitaPrecargar = Yii::$app->request->post('arqueo_habilita_precargar',1);	//Variable que habilita o deshabilita el botón "Precargar". 0. Deshabilitado - 1. Habilitado
$precargar = Yii::$app->request->post('precargar',0);	//Variable que verifica si se presionó el botón "Precargar". 0. No se presionó - 1. Se presionó
$caja_nom = '';	//Variable que almacenará el nombre del usuario de la caja.


if ($model->caja_caja_id != '' && $model->caja_fecha != '')	//Si la caja está abierta
{
	$caja_id = $model->caja_caja_id;	//Identificador de la caja.
	$fecha = $model->caja_fecha;

	//Verifico si no se ingresó un arqueo para la fecha y caja actual
	$res = $model->verificarArqueo($caja_id,$fecha);

	if ($res == 1)	//Si hay un arqueo ingresado
	{

		if ($reiniciarVariables == 1)
			$model->getArqueo($model->caja_caja_id,$model->caja_fecha);
	}

}

if ($caja_id != '' && $fecha != '')	//Si se accede desde una caja o se ingresaron datos de una caja
{

	//Obtengo el nombre del usuario de la caja
	$caja_nom = utb::getCampo('caja','caja_id = ' . $caja_id,'nombre');

	if ($arqueo == 1)	//Si se apretó alguno de los botones de la forma
	{
		//Verifico si no se ingresó un arqueo para la fecha y caja actual
		$res = $model->verificarArqueo($caja_id,$fecha);

		if ($res == 1)	//Si hay un arqueo ingresado
		{
			$model->getArqueo($caja_id,$fecha);
		} else
		{
			$caja_id = '';
			$fecha = '';
			$caja_nom = '';

			if ($arqueo == 1)
				echo '<script>$.pjax.reload({container:"#errorArqueoCaja",method:"POST",data:{mensajeArqueo:"No se encontró ningún arqueo para los datos ingresados."}});</script>';

		}

	}

}

$form = ActiveForm::begin([ 'id' => 'formCajaArqueo',
							'action' => ['grabararqueo']]);

?>

<div class="form_arqueo">
<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorArqueoCaja']);

			$mensaje = '';

			if (isset($_POST['mensajeArqueo'])) $mensaje = $_POST['mensajeArqueo'];

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaArqueoCaja',
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaArqueoCaja').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<div class="form-panel" style="padding-bottom:5px">
<h3><strong>Datos de la Caja</strong></h3>
<table width="100%">
	<tr>
		<td><label>Caja</label></td>
		<td>
			<?=
				Html::input('text','txCajaID',$caja_id,[
					'id'=>'arqueoCaja_txCajaID',
					'class'=>'form-control ' . ($caja_id == '' ? 'false' : 'solo-lectura'),
					'style'=>'width:50px;text-align:center',
					'tabIndex'=>($caja_id == '' ? '0' : '-1'),
				]);
			?>
		</td>
		<td>
			<?=
				Html::input('text','txCajaNom',$caja_nom,[
					'id'=>'arqueoCaja_txCajaNom',
					'class'=>'form-control solo-lectura',
					'style'=>'width:150px;text-align:left',
					'tabIndex'=> '-1',
				]);
			?>
		</td>
		<td width="20px"></td>
		<td><label>Fecha:</label><td>
		<td><?= DatePicker::widget([
							'id' => 'arqueoCaja_txFecha',
							'name' => 'txFecha',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['class' => 'form-control ' . ($fecha == '' ? 'false' : 'solo-lectura'), 'style' => 'width:80px;text-align:center','tabIndex'=>($fecha == '' ? '0' : '-1')],
							'value' => ($fecha != '' ? Fecha::usuarioToDatePicker($fecha) : Fecha::usuarioToDatePicker(Fecha::getDiaActual())),
						]);
			?>
		</td>
		<td width="40px"></td>
		<?php

			if ($caja_id == '' && $fecha == '')
			{
		?>
		<td align="right" style="padding-right:10px"><?= Html::button('Calcular',['id'=>'btArqueoCaja','class'=>'btn btn-success','onclick'=>'arqueoCaja()']); ?></td>

		<?php
			}
		?>
	</tr>
</table>
</div>

<table width="100%">
	<tr>
		<td width="390px">
<div id="divArqueoRecuento" class="form-panel" style="display:none;margin-right:5px">
<h3><strong>Recuento de Efectivo y Valores</strong></h3>
<table>
 <tr>
  <td>
	<table>
		<tr>
			<td width='80px'><label>$ 1000.00</label></td>
			<td width="40px"><?= Html::input('text','tx1000',$model->arqueo_billete1000,['id'=>'arqueoCaja_tx1000','class'=>'form-control','style'=>'width:50px;text-align:right',
							'onchange'=>"actualizaMontos($(this).val(),1000,'arqueoCaja_tx1000Total','arqueoCaja_tx500')",
							'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx1000Total',number_format(($model->arqueo_billete1000 * 1000),2,'.',''),['id'=>'arqueoCaja_tx1000Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 500.00</label></td>
			<td width="40px"><?= Html::input('text','tx500',$model->arqueo_billete500,['id'=>'arqueoCaja_tx500','class'=>'form-control','style'=>'width:50px;text-align:right',
							'onchange'=>"actualizaMontos($(this).val(),500,'arqueoCaja_tx500Total','arqueoCaja_tx200')",
							'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx500Total',number_format(($model->arqueo_billete500 * 500),2,'.',''),['id'=>'arqueoCaja_tx500Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 200.00</label></td>
			<td width="40px"><?= Html::input('text','tx200',$model->arqueo_billete200,['id'=>'arqueoCaja_tx200','class'=>'form-control','style'=>'width:50px;text-align:right',
							'onchange'=>"actualizaMontos($(this).val(),200,'arqueoCaja_tx200Total','arqueoCaja_tx100')",
							'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx200Total',number_format(($model->arqueo_billete200 * 200),2,'.',''),['id'=>'arqueoCaja_tx200Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 100.00</label></td>
			<td width="40px"><?= Html::input('text','tx100',$model->arqueo_billete100,['id'=>'arqueoCaja_tx100','class'=>'form-control','style'=>'width:50px;text-align:right',
							'onchange'=>"actualizaMontos($(this).val(),100,'arqueoCaja_tx100Total','arqueoCaja_tx50')",
							'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx100Total',number_format(($model->arqueo_billete100 * 100),2,'.',''),['id'=>'arqueoCaja_tx100Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 50.00</label></td>
			<td><?= Html::input('text','tx50',$model->arqueo_billete050,['id'=>'arqueoCaja_tx50','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),50,'arqueoCaja_tx50Total','arqueoCaja_tx20')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx50Total',number_format(($model->arqueo_billete050 * 50),2,'.',''),['id'=>'arqueoCaja_tx50Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 20.00</label></td>
			<td><?= Html::input('text','tx20',$model->arqueo_billete020,['id'=>'arqueoCaja_tx20','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),20,'arqueoCaja_tx20Total','arqueoCaja_tx10')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx20Total',number_format(($model->arqueo_billete020 * 20),2,'.',''),['id'=>'arqueoCaja_tx20Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 10.00</label></td>
			<td><?= Html::input('text','tx10',$model->arqueo_billete010,['id'=>'arqueoCaja_tx10','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),10,'arqueoCaja_tx10Total','arqueoCaja_tx5')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx10Total',number_format(($model->arqueo_billete010 * 10),2,'.',''),['id'=>'arqueoCaja_tx10Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 5.00</label></td>
			<td><?= Html::input('text','tx5',$model->arqueo_billete005,['id'=>'arqueoCaja_tx5','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),5,'arqueoCaja_tx5Total','arqueoCaja_tx2')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx5Total',number_format(($model->arqueo_billete005 * 5),2,'.',''),['id'=>'arqueoCaja_tx5Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 2.00</label></td>
			<td><?= Html::input('text','tx2',$model->arqueo_billete002,['id'=>'arqueoCaja_tx2','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),2,'arqueoCaja_tx2Total','arqueoCaja_tx1')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx2Total',number_format(($model->arqueo_billete002 * 2),2,'.',''),['id'=>'arqueoCaja_tx2Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 1.00</label></td>
			<td><?= Html::input('text','tx1',$model->arqueo_moneda100,['id'=>'arqueoCaja_tx1','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),1,'arqueoCaja_tx1Total','arqueoCaja_tx050')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx1Total',number_format(($model->arqueo_moneda100),2,'.',''),['id'=>'arqueoCaja_tx1Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 0.50</label></td>
			<td><?= Html::input('text','tx050',$model->arqueo_moneda050,['id'=>'arqueoCaja_tx050','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),0.5,'arqueoCaja_tx050Total','arqueoCaja_tx025')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx050Total',number_format(($model->arqueo_moneda050 * 0.5),2,'.',''),['id'=>'arqueoCaja_tx050Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 0.25</label></td>
			<td><?= Html::input('text','tx025',$model->arqueo_moneda025,['id'=>'arqueoCaja_tx025','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),0.25,'arqueoCaja_tx025Total','arqueoCaja_tx010')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx025Total',number_format(($model->arqueo_moneda025 * 0.25),2,'.',''),['id'=>'arqueoCaja_tx025Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 0.10</label></td>
			<td><?= Html::input('text','tx010',$model->arqueo_moneda010,['id'=>'arqueoCaja_tx010','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),0.1,'arqueoCaja_tx010Total','arqueoCaja_tx005')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx010Total',number_format(($model->arqueo_moneda010 * 0.1),2,'.',''),['id'=>'arqueoCaja_tx010Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 0.05</label></td>
			<td><?= Html::input('text','tx005',$model->arqueo_moneda005,['id'=>'arqueoCaja_tx005','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),0.05,'arqueoCaja_tx005Total','arqueoCaja_tx001')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx005Total',number_format(($model->arqueo_moneda005 * 0.05),2,'.',''),['id'=>'arqueoCaja_tx005Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
		<tr>
			<td><label>$ 0.01</label></td>
			<td><?= Html::input('text','tx001',$model->arqueo_moneda001,['id'=>'arqueoCaja_tx001','class'=>'form-control','style'=>'width:50px;text-align:right',
						'onchange'=>"actualizaMontos($(this).val(),0.01,'arqueoCaja_tx001Total','arqueoCaja_txCheque')",
						'onkeypress'=>'return justNumbers(event)','maxlength'=>4]); ?></td>
			<td><?= Html::input('text','tx001Total',number_format(($model->arqueo_moneda001 * 0.01),2,'.',''),['id'=>'arqueoCaja_tx001Total','class'=>'form-control solo-lectura','style'=>'width:70px;text-align:right','disabled'=>true]); ?></td>
		</tr>
	</table>
  </td>
  <td width="15px"></td>

  <td valign='top'>

  <?php

  	Pjax::begin(['id' => 'PjaxPrecargarArqueo']);

	$precargar = Yii::$app->request->post('precargar',0);	//Variable que verifica si se presionó el botón "Precargar". 0. No se presionó - 1. Se presionó
	$actualizar = Yii::$app->request->post('actualizar',0);	//Variable que verifica si se presionó el botón "Precargar". 0. No se presionó - 1. Se presionó

	$model->arqueo_cheque = number_format((double) Yii::$app->request->post('arqueoCheque',$model->arqueo_cheque),2,'.','');
	$model->arqueo_tarjetacredito = number_format((double) Yii::$app->request->post('arqueoTarjetaC',$model->arqueo_tarjetacredito),2,'.','');
	$model->arqueo_tarjetadebito = number_format((double) Yii::$app->request->post('arqueoTarjetaD',$model->arqueo_tarjetadebito),2,'.','');
	$model->arqueo_deposito = number_format((double) Yii::$app->request->post('arqueoDeposito',$model->arqueo_deposito),2,'.','');
	$model->arqueo_transferencia = number_format((double) Yii::$app->request->post('arqueoTransferencia',$model->arqueo_transferencia),2,'.','');
	$model->arqueo_notacredito = number_format((double) Yii::$app->request->post('arqueoCredito',$model->arqueo_notacredito),2,'.','');
	$model->arqueo_bonos = number_format((double) Yii::$app->request->post('arqueoBonos',$model->arqueo_bonos),2,'.','');
	$model->arqueo_haberes = number_format((double) Yii::$app->request->post('arqueoCesionHaberes',$model->arqueo_haberes),2,'.','');
	$model->arqueo_otros = number_format((double) Yii::$app->request->post('arqueoOtros',$model->arqueo_otros),2,'.','');
	$model->arqueo_recuento = number_format($model->arqueo_recuento,2,'.','');

  	if ($precargar == 1)	//Si se apretó el botón para precargar datos
	{
		$model->getMDPCobrado($model->caja_caja_id,$model->caja_fecha);

	}

  ?>
	<table>
		<tr>
			<td>
			<?php

			if ($caja_id != '' && $fecha != '' && $habilitaPrecargar == 1)
			{
			?>
			<td colspan="2" align="right"><?= Html::button('Precargar',['id'=>'arqueoCaja_btPrecargar','class'=>'btn btn-primary','onclick'=>'precargarArqueoCaja()']); ?></td>

			<?php
			}
			?>
			</td>
		</tr>
		<tr>
			<td><label>Retiro</label></td>
			<td>
			<?php
				echo Html::input('text','txRetiro',$model->arqueo_cant_retiro,[
									'id'=>'arqueoCaja_txRetiro',
									'class'=>'form-control solo-lectura',
									'style'=>'width:70px;text-align:right',
									]
								);	
			?>
			</td>
		</tr>
		<tr>
			<td><label>Efectivo</label></td>
			<td>
			<?php

				//INICIO Bloque de código que se encarga de actualizar el valor de efectivo en el modelo
				Pjax::begin(['id'=>'PjaxActualizaEfectivoArqueo']);

					$efectivo = Yii::$app->request->post('efectivoArqueo','');

					if ($efectivo != '')
					{
						$model->arqueo_efectivo = number_format($efectivo,2,'.','');
						?>
						<script>
							$("#PjaxActualizaEfectivoArqueo").on("pjax:end", function(){

								$.pjax.reload({container:"#PjaxRecargaTotales",method:"POST",data:{recargar:1}});
								$("#PjaxActualizaEfectivoArqueo").off("pjax:end");
							});
						</script>

						<?php
					}

					echo Html::input('text','txEfectivo',$model->arqueo_efectivo,[
									'id'=>'arqueoCaja_txEfectivo',
									'class'=>'form-control solo-lectura',
									'style'=>'width:70px;text-align:right',
									'readOnly'=>true,
									'tabIndex'=>-1]
								);


				Pjax::end();
			?>
			</td>
		</tr>
		<tr>
			<td><label>Cheque</label></td>
			<td><?= Html::input('text','txCheque',$model->arqueo_cheque,[
									'id'=>'arqueoCaja_txCheque',
									'class'=>'form-control',
									'style'=>'width:70px;text-align:right',
									'onchange'=>'actualizaValores(1,"arqueoCaja_txTarjetaC")',
									'onkeypress' => 'return justDecimalAndMenos(event,$(this).val())']); ?></td>
		</tr>
		<tr>
			<td><label>Tarjeta Crédito</label></td>
			<td><?= Html::input('text','txTarjetaC',$model->arqueo_tarjetacredito,[
									'id'=>'arqueoCaja_txTarjetaC',
									'class'=>'form-control',
									'style'=>'width:70px;text-align:right',
									'onkeypress' => 'return justDecimalAndMenos(event,$(this).val())',
									'onchange'=>'actualizaValores(1,"arqueoCaja_txDebito")',]); ?></td>
		</tr>
		<tr>
			<td><label>Tarjeta Débito</label></td>
			<td><?= Html::input('text','txDebito',$model->arqueo_tarjetadebito,[
									'id'=>'arqueoCaja_txDebito',
									'class'=>'form-control',
									'style'=>'width:70px;text-align:right',
									'onchange'=>'actualizaValores(1,"arqueoCaja_txDeposito")',
									'onkeypress' => 'return justDecimalAndMenos(event,$(this).val())']); ?></td>
		</tr>
		<tr>
			<td><label>Depósito</label></td>
			<td><?= Html::input('text','txDeposito',$model->arqueo_deposito,[
									'id'=>'arqueoCaja_txDeposito',
									'class'=>'form-control',
									'style'=>'width:70px;text-align:right',
									'onchange'=>'actualizaValores(1,"arqueoCaja_txTransferencia")',
									'onkeypress' => 'return justDecimalAndMenos(event,$(this).val())']); ?></td>
		</tr>
		<tr>
			<td><label>Transferencia</label></td>
			<td><?= Html::input('text','txTransferencia',$model->arqueo_transferencia,[
									'id'=>'arqueoCaja_txTransferencia',
									'class'=>'form-control',
									'style'=>'width:70px;text-align:right',
									'onchange'=>'actualizaValores(1,"arqueoCaja_txNotaCredito")',
									'onkeypress' => 'return justDecimalAndMenos(event,$(this).val())']); ?></td>
		</tr>
		<tr>
			<td><label>Nota de Crédito</label></td>
			<td><?= Html::input('text','txNotaCredito',$model->arqueo_notacredito,[
									'id'=>'arqueoCaja_txNotaCredito',
									'class'=>'form-control',
									'style'=>'width:70px;text-align:right',
									'onchange'=>'actualizaValores(1,"arqueoCaja_txBonos")',
									'onkeypress' => 'return justDecimalAndMenos(event,$(this).val())']); ?></td>
		</tr>
		<tr>
			<td><label>Bonos</label></td>
			<td><?= Html::input('text','txBonos',$model->arqueo_bonos,[
									'id'=>'arqueoCaja_txBonos',
									'class'=>'form-control',
									'style'=>'width:70px;text-align:right',
									'onchange'=>'actualizaValores(1,"arqueoCaja_txCesionHaberes")',
									'onkeypress' => 'return justDecimalAndMenos(event,$(this).val())']); ?></td>
		</tr>
		<tr>
			<td><label>Cesión Haberes</label></td>
			<td><?= Html::input('text','txCesionHaberes',$model->arqueo_haberes,[
									'id'=>'arqueoCaja_txCesionHaberes',
									'class'=>'form-control',
									'style'=>'width:70px;text-align:right',
									'onchange'=>'actualizaValores(1,"arqueoCaja_txOtros")',
									'onkeypress' => 'return justDecimalAndMenos(event,$(this).val())']); ?></td>
		</tr>
		<tr>
			<td><label>Otros</label></td>
			<td><?= Html::input('text','txOtros',$model->arqueo_otros,[
									'id'=>'arqueoCaja_txOtros',
									'class'=>'form-control',
									'style'=>'width:70px;text-align:right',
									'onchange'=>'actualizaValores(1,"arqueoCaja_txFondoCambioTotal")',
									'onkeypress' => 'return justDecimalAndMenos(event,$(this).val())']); ?></td>
		</tr>
		<tr>
			<td><label><strong>Recuento</strong></label></td>
			<td><?= Html::input('text','txRecuento',$model->arqueo_recuento,[
									'id'=>'arqueoCaja_txRecuento',
									'class'=>'form-control solo-lectura',
									'tabIndex' => -1,
									'style'=>'width:70px;text-align:right']); ?></td>
		</tr>
	</table>

	<?php
	if ($precargar == 1 || $actualizar == 1)	//Si se apretó el botón para precargar datos
	{
		?>
		<script>

		$("#PjaxPrecargarArqueo").on("pjax:end", function(){

			calculaEfectivo(1);

			$("#PjaxPrecargarArqueo").off("pjax:end");
		});
		</script>

		<?php
	}

  	Pjax::end();

  	?>
  </td>
 </tr>
</table>

</div>

		</td>
		<td valign="top">

<style>
#ArqueoTablaTotales td {
	 padding-bottom: 20px;
}


</style>

<div id="divArqueoTotales" class="form-panel" style="display:none">
<h3><strong>Totales Ingresados</strong></h3>
<?php

Pjax::begin(['id'=>'PjaxRecargaTotales']);

	/*
	 * Debería obtener los datos del recuento y actualizar la info.
	 */

	 $model->calcularTotales();

?>

<table id="ArqueoTablaTotales">
	<tr>
		<td width="80px" style="padding-top:10px">
			<label>Efectivo</label>
			<?= Html::input('text','txEfectivoTotal',$model->arqueo_total_efectivo,['id'=>'arqueoCaja_txEfectivoTotal','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:right','readOnly'=>true, 'tabIndex'=>-1]); ?>
		</td>
	</tr>
	<tr>
		<td width="80px">
			<label>Otros Valores</label>
			<?= Html::input('text','txOtrosValoresTotal',number_format( $model->arqueo_total_otros, 2, '.', '' ),['id'=>'arqueoCaja_txOtrosValoresTotal','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:right','readOnly'=>true, 'tabIndex'=>-1]); ?>
		</td>
	</tr>
	<tr>
		<td width="80px">
			<label>Fondo/Cambio</label>
			<?php

				//INICIO Bloque de código que se encarga de actualizar el valor de efectivo en el modelo
				Pjax::begin(['id'=>'PjaxActualizaFondoArqueo']);

					$fondo = Yii::$app->request->post('fondoArqueo','');

					if ($fondo != '')
					{
						$model->arqueo_total_fondo = number_format($fondo,2,'.','');
						?>
						<script>
							$("#PjaxActualizaFondoArqueo").on("pjax:end", function(){

								$.pjax.reload({container:"#PjaxRecargaTotales",method:"POST",data:{recargar:1}});
								$("#PjaxActualizaFondoArqueo").off("pjax:end");
							});
						</script>

						<?php
					}


					echo Html::input('text','txFondoCambioTotal',$model->arqueo_total_fondo,[
									'id'=>'arqueoCaja_txFondoCambioTotal',
									'class'=>'form-control',
									'style'=>'width:100%;text-align:right',
									'onchange'=>'$.pjax.reload({container:"#PjaxActualizaFondoArqueo",method:"POST",data:{fondoArqueo:$(this).val()}})']
								);


				Pjax::end();
			?>
		</td>
	</tr>
	<tr>
		<td width="80px">
			<label><strong>Total</strong></label>
			<?= Html::input('text','txTotal',$model->arqueo_total_total,[
									'id'=>'arqueoCaja_txTotal',
									'class'=>'form-control solo-lectura',
									'style'=>'width:100%;text-align:right',
									'readOnly'=>true,
									'tabIndex'=>-1]);
			?>
		</td>
	</tr>
	<tr>
		<td width="80px">
			<label>Sobrante/Falta</label>
			<?= Html::input('text','txSobranteFaltaTotal',$model->arqueo_total_sobrante,[
									'id'=>'arqueoCaja_txSobranteFaltaTotal',
									'class'=>'form-control solo-lectura',
									'style'=>'width:100%;text-align:right',
									'readOnly'=>true,
									'tabIndex'=>-1]);
			?>
		</td>
	</tr>
</table>

<?php
	//Actualizo Recuento
	echo '<script>$("#arqueoCaja_txRecuento").val("'.$model->arqueo_recuento.'");</script>';

Pjax::end();

?>
</div>
		</td>
	</tr>
</table>

<div id="botonesArqueo">
<table>
	<tr>
		<td>
			<?php
				if($caja_id != '')
				{
			?>
			<?= Html::button('Aceptar',['id'=>'arqueoCaja_btAceptar','class'=>'btn btn-success','onclick'=> 'aceptarArqueo()']); ?>
		</td>
		<td width="15px"></td>
		<td>
			<?php
				}
			?>
			<?= Html::button('Cancelar',['id'=>'arqueoCaja_btCancelar','class'=>'btn btn-primary','onclick'=> '$("#ModalArqueo").modal("hide")']); ?>
		</td>
	</tr>
</table>
</div>

</div>

<?php

if ($caja_id != '' && $fecha != '')
{	//Habilito los div que están ocultos
	echo '<script>$("#divArqueoRecuento").css("display","block");</script>';
	echo '<script>$("#divArqueoTotales").css("display","block");</script>';
}

ActiveForm::end();

if ($model->caja_caja_id == '' && $caja_id != '')	//Desactivo el formulario
{
	?>
	<script>
		$("#botonesArqueo").css("display","none");
		DesactivarForm('formCajaArqueo');

		var frm = document.getElementById('formCajaArqueo');
		for (i=0;i<frm.elements.length;i++)
		{
			frm.elements[i].style.background = "#E6E6FA";
		}
		
		function btImprimirClick()
		{
			$("#ModalArqueo").modal("hide");
			window.open("<?=BaseUrl::toRoute(['imprimirarqueo','caja'=>$caja_id,'fecha'=>$fecha])?>"); 
		}
	</script>

	<table>
		<tr>
			<td>
				<?= Html::button('Aceptar',['id'=>'arqueoCaja_btCancelar','class'=>'btn btn-primary','onclick'=> '$("#ModalArqueo").modal("hide")']); ?>
			</td>
            <td></td>
            <td>
				<?=  Html::button('Imprimir',['id'=>'arqueoCaja_btImprimir','class'=>'btn btn-success', 'onclick' => 'btImprimirClick()']); ?>
			</td>
		</tr>
	</table>

<?php
}

Pjax::end();

?>

<script>
function aceptarArqueo()
{
	$("#formCajaArqueo").submit();

}

function precargarArqueoCaja()
{
	$.pjax.reload({
		container:"#PjaxPrecargarArqueo",
		method:"POST",
		data:{
			precargar:1,
	}});


}

function arqueoCaja()
{
	var caja = $("#arqueoCaja_txCajaID").val();
	var fecha = $("#arqueoCaja_txFecha").val();

	var error = '';

	if (caja == '')
		error += '<li>Ingrese una caja.</li>';

	if (fecha == '')
		error += '<li>Ingrese una fecha.</li>';

	if (error == '')
	{
		$.pjax.reload({
			container:"#PjaxArqueoCaja",
			method:"POST",
			data:{
				arqueo_caja_id:caja,
				arqueo_caja_fecha:fecha,
				arqueo_habilita_precargar:0,
				precargar:0,
				arqueo:1,
		}});

		$("#PjaxArqueoCaja").on("pjax:end", function(){

			$("#ModalArqueo").modal("show");
			$("#PjaxArqueoCaja").off("pjax:end");

		});

	} else
	{
		$.pjax.reload({
			container:"#errorArqueoCaja",
			method:"POST",
			data:{
				mensajeArqueo:error,
			}});
	}

}

/*
	Función que actualiza los totates de los distintos tipos de efectivo.
	Recibe: 	* cant Cantidad de billetes.
				* valor Valor del efectivo
				* id Id del edit en el que hay que actualizar
*/

function actualizaMontos(cant,valor,id,foco)
{
	if (cant == 0)
		$("#"+id).val(0);
	else
	{
		var total = parseFloat(cant) * parseFloat(valor);

		$("#"+id).val(total.toFixed( 2 ));
	}

	calculaEfectivo(1);

	$("#PjaxRecargaTotales").on("pjax:end", function () {

		$("#"+foco).focus();
		$("#PjaxRecargaTotales").off("pjax:end");
	});

}

function calculaEfectivo(enviar)
{
	var total = 0;

	total += parseFloat($("#arqueoCaja_tx1000Total").val());
	total += parseFloat($("#arqueoCaja_tx500Total").val());
	total += parseFloat($("#arqueoCaja_tx200Total").val());
	total += parseFloat($("#arqueoCaja_tx100Total").val());
	total += parseFloat($("#arqueoCaja_tx50Total").val());
	total += parseFloat($("#arqueoCaja_tx20Total").val());
	total += parseFloat($("#arqueoCaja_tx10Total").val());
	total += parseFloat($("#arqueoCaja_tx5Total").val());
	total += parseFloat($("#arqueoCaja_tx2Total").val());
	total += parseFloat($("#arqueoCaja_tx1Total").val());
	total += parseFloat($("#arqueoCaja_tx050Total").val());
	total += parseFloat($("#arqueoCaja_tx025Total").val());
	total += parseFloat($("#arqueoCaja_tx010Total").val());
	total += parseFloat($("#arqueoCaja_tx005Total").val());
	total += parseFloat($("#arqueoCaja_tx001Total").val());

	if (enviar == 1)
		$.pjax.reload({
			container:"#PjaxActualizaEfectivoArqueo",
			method:"POST",
			data:{
				efectivoArqueo:total,
			}
		});

}

function actualizaValores(enviar,foco)
{
	var cheque = $("#arqueoCaja_txCheque").val();

	var tarjetaC = $("#arqueoCaja_txTarjetaC").val();

	var tarjetaD = $("#arqueoCaja_txDebito").val();

	var deposito = $("#arqueoCaja_txDeposito").val();

	var transferencia = $("#arqueoCaja_txTransferencia").val();

	var notaCredito = $("#arqueoCaja_txNotaCredito").val();

	var bonos = $("#arqueoCaja_txBonos").val();

	var cesionHaberes = $("#arqueoCaja_txCesionHaberes").val();

	var otros = $("#arqueoCaja_txOtros").val();

	if (enviar == 1)
		$.pjax.reload({
			container:"#PjaxPrecargarArqueo",
			method:"POST",
			data:{
				arqueoCheque:cheque,
				arqueoTarjetaC:tarjetaC,
				arqueoTarjetaD:tarjetaD,
				arqueoDeposito:deposito,
				arqueoTransferencia:transferencia,
				arqueoCredito:notaCredito,
				arqueoBonos:bonos,
				arqueoCesionHaberes:cesionHaberes,
				arqueoOtros:otros,
				actualizar:1,
			}
		});


	$("#PjaxRecargaTotales").on("pjax:end", function () {

		$("#"+foco).focus();
		$("#PjaxRecargaTotales").off("pjax:end");
	});

}

function calculaTotales()
{
	$("#arqueoCaja_tx1000Total").val($("#arqueoCaja_tx1000").val() * 1000);
	$("#arqueoCaja_tx500Total").val($("#arqueoCaja_tx500").val() * 500);
	$("#arqueoCaja_tx200Total").val($("#arqueoCaja_tx200").val() * 200);
	$("#arqueoCaja_tx100Total").val($("#arqueoCaja_tx100").val() * 100);
	$("#arqueoCaja_tx50Total").val($("#arqueoCaja_tx50").val() * 50);
	$("#arqueoCaja_tx20Total").val($("#arqueoCaja_tx20").val() * 20);
	$("#arqueoCaja_tx10Total").val($("#arqueoCaja_tx10").val() * 10);
	$("#arqueoCaja_tx5Total").val($("#arqueoCaja_tx5").val() * 5);
	$("#arqueoCaja_tx2Total").val($("#arqueoCaja_tx2").val() * 2);
	$("#arqueoCaja_tx1Total").val($("#arqueoCaja_tx1").val() * 1);
	$("#arqueoCaja_tx050Total").val($("#arqueoCaja_tx050").val() * 0.5);
	$("#arqueoCaja_tx025Total").val($("#arqueoCaja_tx025").val() * 0.25);
	$("#arqueoCaja_tx010Total").val($("#arqueoCaja_tx010").val() * 0.1);
	$("#arqueoCaja_tx005Total").val($("#arqueoCaja_tx005").val() * 0.05);
	$("#arqueoCaja_tx001Total").val($("#arqueoCaja_tx001").val() * 0.01);

	calculaEfectivo();
}

calculaTotales();

</script>
