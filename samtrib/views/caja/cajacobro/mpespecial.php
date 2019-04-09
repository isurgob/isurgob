<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\web\Session;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;

/**
 * Recibo como parámetros:
 * 	@param string $mdp_nom Nombre del medio de pago
 * 	@param integer $mdp_tipo Tipo del medio de pago
 * 	@param double $monto Monto del medio de pago
 */

?>

<div class="form_anulacion">
<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorMPECaja']);

			$mensaje = '';

			if (isset($_POST['mensajeMPE'])) $mensaje = $_POST['mensajeMPE'];

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaMPECaja',
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none',
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaMPECaja').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<?php

	Pjax::begin(['id' => 'PjaxMDP']);

		$mdp_num = Yii::$app->request->post('mdp_num',0);
		$mdp_tipo = Yii::$app->request->post('mdp_tipo','');
		$monto = number_format( Yii::$app->request->post('mdp_monto',0), 2, '.', '' );
		$tipoMDP = Yii::$app->request->post('mdp_tipoMDP','');

		$mdp_nom = utb::getCampo('caja_mdp','mdp = ' . $mdp_num,'nombre');
		$cotiza = floatVal( utb::getCampo('caja_mdp','mdp = ' . $mdp_num,'cotiza') );

		$monto_final = ($monto * $cotiza);

		echo Html::input('hidden','txTipo',$tipoMDP,['id'=>'mpeCaja_txTipo']);
		echo Html::input('hidden','txNum',$mdp_num,['id'=>'mpeCaja_txNum']);
		echo Html::input('hidden','txMonto',$monto,['id'=>'mpeCaja_txMonto']);

?>
<table width="100%">
	<tr>
		<td width="100px">
			<label>Medio de Pago</label>
			<?= Html::input('text','txMDP',$mdp_nom,
					['id'=>'mpeCaja_txMDP','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:center;']); ?>
		</td>
		<td width="10px"></td>
		<td width="65px">
			<label>Cant</label>
			<?= Html::input('text','txCant',$monto,
						['id'=>'mpeCaja_txCant','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:right;']); ?>
		</td>
		<td width="10px"></td>
		<td width="65px">
			<label>Monto</label>
			<?= Html::input('text','txMonto',number_format($monto_final,2,'.',''),
						['id'=>'mpeCaja_txMonto','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:right;']); ?>
		</td>
	</tr>
</table>

<ul class='menu_derecho'>
<li><hr style="color: #FFF; margin:1px" /></li>
</ul>

<div id="banco" style="display:none">
<table>
	<tr>
		<td width="65px"><label>Banco:</label></td>
		<td>
			<?= Html::dropDownList('dlBanco',1,utb::getAux('banco_entidad','bco_ent','nombre',0),[
						'id'=>'mpeCaja_dlBanco',
						'class'=>'form-control',
						'style'=>'width:200px;',
						'onchange'=>'$.pjax.reload({container:"#PjaxMDPCambiaBanco",method:"POST",data:{mdp_banco:$(this).val()}})']) ?>
		</td>
	</tr>
</table>
</div>

<div id="sucursal" style="display:none">
<table>
	<tr>
		<td width="65px"><label>Sucursal:</label></td>
		<td>
	<?php

		Pjax::begin(['id' => 'PjaxMDPCambiaBanco']);

			$banco = Yii::$app->request->post('mdp_banco',0);

			echo Html::dropDownList('dlSuc',1,utb::getAux('banco','bco_suc','nombre',3,'bco_ent = ' . $banco),[
						'id'=>'mpeCaja_dlSuc',
						'class'=>'form-control',
						'style'=>'width:200px']);

		Pjax::end();
	?>
		</td>
	</tr>
</table>
</div>

<div id="cuenta" style="display:none">
<table>
	<tr>
		<td width="65px"><label>Cuenta:</label></td>
		<td>
			<?= Html::input('text','txCuenta','',[
					'id'=>'mpeCaja_txCuenta',
					'class'=>'form-control',
					'style'=>'width:100px;text-align:center;',
					'maxlength' => 11,
				]);
			?>
		</td>
	</tr>
	<tr>
		<td><label>Tipo:</label></td>
		<td>
			<?= Html::dropDownList('dlTCta',1,utb::getAux('banco_cuenta_tipo','cod','nombre',0),[
					'id'=>'mpeCaja_dlTCta',
					'class'=>'form-control',
					'style'=>'width:100px',
				]);
			?>
		</td>
	</tr>
</table>
</div>

<div id="titular" style="display:none">
<table>
	<tr>
		<td width="65px"><label>Titular:</label></td>
		<td>
			<?= Html::input('text','txTitular','',[
					'id'=>'mpeCaja_txTitular',
					'class'=>'form-control',
					'style'=>'width:200px;text-align:left;',
					'maxlength' => 50,
				]);
			?>
		</td>
	</tr>
</table>
</div>

<div id="comprob" style="display:none">
<table>
	<tr>
		<td width="65px"><label>Comprob.:</label></td>
		<td>
			<?= Html::input('text','txComprobante','',[
					'id'=>'mpeCaja_txComprobante',
					'class'=>'form-control',
					'style'=>'width:200px;text-align:center;',
					'maxlength' => 15,
				]);
			?>
		</td>
	</tr>
</table>
</div>

<div id="fchcobro" style="display:none">
<table>
	<tr>
		<td width="65px"><label>Fch.Cobro:</label></td>
		<td>
			<?= DatePicker::widget([
							'id' => 'mpeCaja_txFchCobro',
							'name' => 'txFchCobro',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['class' => 'form-control', 'style' => 'width:80px;text-align:center',],
							'value' => '',
						]);
			?>
		</td>
	</tr>
</table>
</div>

<table style="margin-top:8px">
	<tr>
		<td width="40px"></td>
		<td><?= Html::button('Aceptar',['class'=>'btn btn-success','onclick'=>"btAceptarMPE()"]); ?></td>
		<td width="25px"></td>
		<td><?= Html::button('Cancelar',['class'=>'btn btn-primary','onclick'=>"cerrarVentanaMPE()"]); ?></td>
	</tr>
</table>
</div>

<!-- Inicio Div Errores -->
<div id="mpespecial_errorSummary" class="error-summary" style="display: none; margin-top: 8px">

</div>
<!-- Fin Div Errores -->

<?php

	//Habilitar o deshabilitar banco
	if($mdp_tipo == 'CH' || $mdp_tipo == 'TC' || $mdp_tipo == 'TD' || $mdp_tipo == 'DE' || $mdp_tipo == 'TR')
		echo '<script>$("#banco").css("display","block");</script>';

	//Habilitar o deshabilitar sucursal
	if($mdp_tipo == 'CH' || $mdp_tipo == 'DE' || $mdp_tipo == 'TR')
		echo '<script>$("#sucursal").css("display","block");</script>';

	//Habilitar o deshabilitar cuenta
	if($mdp_tipo == 'DE' || $mdp_tipo == 'TR')
		echo '<script>$("#cuenta").css("display","block");</script>';

	//Habilitar o deshabilitar titular
	if($mdp_tipo == 'CH' || $mdp_tipo == 'DE' || $mdp_tipo == 'TR')
		echo '<script>$("#titular").css("display","block");</script>';

	//Habilitar o deshabilitar comprob
	if($mdp_tipo == 'CH' || $mdp_tipo == 'TC' || $mdp_tipo == 'TD' || $mdp_tipo == 'DE' || $mdp_tipo == 'TR' || $mdp_tipo == 'HA' || $mdp_tipo == 'NC' || $mdp_tipo == 'OT')
		echo '<script>$("#comprob").css("display","block");</script>';

	//Habilitar o deshabilitar fchcobro
	if($mdp_tipo == 'CH')
		echo '<script>$("#fchcobro").css("display","block");</script>';

Pjax::end();


Pjax::begin(['id'=>'PjaxGrabarMDPEspecial']);

	$mdp_banco = Yii::$app->request->post('banco',0);
	$mdp_sucursal = Yii::$app->request->post('sucursal',1);
	$mdp_comprobante = Yii::$app->request->post('comprobante',0);
	$mdp_titular = Yii::$app->request->post('titular','');
	$mdp_cuenta = Yii::$app->request->post('cuenta',0);
	$mdp_fchcobro = Yii::$app->request->post('fchcobro','');
	$mdp_tcta = Yii::$app->request->post('tcta',0);
	$mdp_numero = Yii::$app->request->post('numero',0);
	$mdp_monto = number_format(floatval(Yii::$app->request->post('monto','')),2,'.','');
	$mdp_tipo = Yii::$app->request->post('tipo','');

	$ejecutar = Yii::$app->request->post('agregarMDP',0);

	if ( $ejecutar == 1 )
	{

		$res = $model->agregoMDPEspecial($mdp_tipo,$mdp_numero,$mdp_monto,$mdp_comprobante,$mdp_banco,$mdp_sucursal,$mdp_cuenta,$mdp_titular,$mdp_tcta,$mdp_fchcobro);

		if ($res['return'] == 1)
		{
			if ($mdp_tipo == 1)
				echo '<script>$.pjax.reload({container:"#PjaxGrillaIMdp",method:"POST"});</script>';
			else
				echo '<script>$.pjax.reload({container:"#PjaxGrillaEMdp",method:"POST"});</script>';

			//Cierro la ventana Modal
			echo '<script>$("#ModalMDPEspecial").modal("hide");</script>';
		}
		else
		{
			if ($mdp_tipo == 2)
				echo '<script>$("#cajaCobro_txTotalEgreso").val("'.$model->opera_sobrante.'")</script>';

			echo '<script>$.pjax.reload({container:"#errorCajaCobro",method:"POST",data:{mensaje:"'.$res['mensaje'].'"}});</script>';

			//Cierro la ventana Modal
			echo '<script>$("#ModalMDPEspecial").modal("hide");</script>';
		}
	}

Pjax::end();

?>

<script>
function cerrarVentanaMPE()
{
	$("#mpeCaja_txComprob").val('');
	$("#mpeCaja_txMotivo").val('');
	$("#mpeCaja_txTipo1").prop("checked",true);
	$("#ModalMDPEspecial").modal("hide");
}

function btAceptarMPE()
{
	var error = new Array(),
		banco = $("#mpeCaja_dlBanco").val(),
		sucursal = $("#mpeCaja_dlSuc").val(),
		comprobante = $("#mpeCaja_txComprobante").val(),
		titular = $("#mpeCaja_txTitular").val(),
		cuenta = $("#mpeCaja_txCuenta").val(),
		fchcobro = $("#mpeCaja_txFchCobro").val(),
		tcta = $("#mpeCaja_dlTCta").val(),
		tipo = $("#mpeCaja_txTipo").val(),
		monto = parseFloat( $("#mpeCaja_txMonto").val() ),
		numero = $("#mpeCaja_txNum").val();

	if ($("#banco").css("display") == "block" && (banco == '' || banco == 0))
		error.push( "Ingrese un banco." );

	if ($("#sucursal").css("display") == "block")
	{
		if (sucursal == '' || sucursal == 0 || sucursal == null)
			error.push( "Ingrese una sucursal." );

	} else
	{
		sucursal = 0;

	}

	if ($("#cuenta").css("display") == "none")
	{
		cuenta = 0;
		tcta = 0;
	}

	if ($("#comprob").css("display") == "block" && (comprobante == '' || comprobante == 0))
		error.push( "Ingrese un comprobante." );

	if ($("#titular").css("display") == "block")
	{
		if (titular == '' || titular == 0)
			error.push( "Ingrese un titular." );
	}

	if ( error.length == 0 )
	{
		//Ocultar div errores
		$( "#mpespecial_errorSummary" ).css( "display", "none" );

		$.pjax.reload({
			container:"#PjaxGrabarMDPEspecial",
			method:"POST",
			data:{
				banco:banco,
				sucursal:sucursal,
				comprobante:comprobante,
				titular:titular,
				cuenta:cuenta,
				fchcobro:fchcobro,
				tcta:tcta,
				tipo:tipo,
				monto:monto,
				numero:numero,
				agregarMDP:1,
		}});

	} else
		mostrarErrores( error, "#mpespecial_errorSummary" );
}
</script>
