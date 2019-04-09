<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use yii\web\Session;
use yii\bootstrap\Alert;
use app\utils\db\Fecha;
use yii\data\ArrayDataProvider;

/**
 * Forma que se dibuja como ventana modal y que se utiliza para
 * agregar o modificar una partida presupuesaria.
 *
 * Recibo:
 *
 * 		+ $model => Modelo de "Partida PResupuestaria"
 */

$form = ActiveForm::begin(['id' => 'formEditaPartida']);


if ($consulta == 0)
	$title = 'Nueva Partida';

if ($consulta == 3)
	$title = 'Modificar Partida';

$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Partidas Presupuestarias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;

?>

<div class="editapartida">

<h1><?= Html::encode($title) ?></h1>

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php
			Pjax::begin(['id'=>'PjaxErrorEditaPartida']);

				$mensaje = '';

				$mensaje = Yii::$app->request->get('mensajeEditaPartida','');
				$m = Yii::$app->request->get('m',2);

				if (isset($alert) && $alert != '')
				{
					$mensaje = $alert;
					$alert = '';
				}

				if($mensaje != "")
				{

			    	Alert::begin([
			    		'id' => 'AlertaMensajeEditaPartida',
						'options' => [
			        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
			        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
			    		],
					]);

					echo $mensaje;

					Alert::end();

					echo "<script>window.setTimeout(function() { $('#AlertaMensajeEditaPartida').alert('close'); }, 5000)</script>";
				 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<?= HTml::input('hidden','CuentaPartida[padre]',$model->padre); ?>

<div class="form-panel" style="padding-right:5px">
<table>
	<tr>
		<td width="120px"><h3><label>Partida Padre:</label></h3></td>
		<td width="53px"><label>Nombre:</label></td>
		<td width="350px">
			<?= Html::input('text','txPartidaPadreNombre',utb::getCampo( 'cuenta_partida', 'part_id = ' . $model->padre, 'nombre' ), [
					'class' => 'form-control solo-lectura',
					'style' => 'width:350px',
				]);
			?>
		</td>
		<td width="20px"></td>
		<td><label>Formato:</label></td>
		<td>
			<?= Html::input('text','txPartidaPadreNombre',utb::getCampo( 'cuenta_partida', 'part_id = ' . $model->padre, 'formatoaux' ), [
					'class' => 'form-control solo-lectura',
					'style' => 'width:113px',
				]);
			?>
		</td>
	</tr>
</table>

<div class="separador-horizontal">
</div>

<table>
	<tr>
		<td width="55px"><label for="id">Id:</label></td>
		<td><?= Html::input('text','CuentaPartida[part_id]',$model->part_id,[
					'id' => 'editapartida_txId',
					'class' => 'form-control solo-lectura',
					'style' => 'width:35px;text-align:center',
					'tabIndex' => -1,
					]); ?>
		</td>
		<td width="15px"></td>
		<td width="65px"><label for="nivel">Nivel:</label></td>
		<td><?= Html::input('text','CuentaPartida[nivel]',$model->nivel,[
					'id' => 'editapartida_txNivel',
					'class' => 'form-control solo-lectura',
					'style' => 'width:35px;text-align:center',
					'tabIndex' => -1,
					]); ?>
		</td>
		<td width="15px"></td>
		<td width="45px"><label for="estado">Estado:</label></td>
		<td><?= Html::input('text','CuentaPartida[est]',$model->est,[
					'id' => 'editapartida_txEst',
					'class' => 'form-control solo-lectura',
					'style' => 'width:35px;text-align:center',
					'tabIndex' => -1,
					]); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="55px"><label for="grupo">Grupo:</label></td>
		<td><?= Html::input('text','CuentaPartida[grupo]',$model->grupo,[
					'id' => 'editapartida_txGrupo',
					'class' => 'form-control ' . ($model->grupo != '' ? 'solo-lectura' : ''),
					'style' => 'width:35px;text-align:center',
					'max-length' => 1,
					'tabIndex' => ($model->grupo != '' ? -1 : 0),
					'onkeypress' => 'return justNumbers(event)',
					]); ?>
		</td>
		<td width="15px"></td>
		<td width="65px"><label for="subGrupo">SubGrupo:</label></td>
		<td><?= Html::input('text','CuentaPartida[subgrupo]',$model->subgrupo,[
					'id' => 'editapartida_txSubGrupo',
					'class' => 'form-control ' . ($model->subgrupo != '' ? 'solo-lectura' : ''),
					'style' => 'width:35px;text-align:center',
					'max-length' => 1,
					'tabIndex' => ($model->subgrupo != '' ? -1 : 0),
					'onkeypress' => 'return justNumbers(event)',
					]); ?>
		</td>
		<td width="15px"></td>
		<td width="45px"><label for="rubro">Rubro:</label></td>
		<td><?= Html::input('text','CuentaPartida[rubro]',$model->rubro,[
					'id' => 'editapartida_txRubro',
					'class' => 'form-control ' . ($model->rubro != '' || $model->subgrupo == '' || $consulta == 3 ? 'solo-lectura' : ''),
					'style' => 'width:35px;text-align:center',
					'max-length' => 1,
					'tabIndex' => ($model->rubro != '' || $model->subgrupo == '' ? -1 : 0),
					'onkeypress' => 'return justNumbers(event)',
					]); ?>
		</td>
		<td width="15px"></td>
		<td><label for="cuenta">Cuenta:</label></td>
		<td><?= Html::input('text','CuentaPartida[cuenta]',$model->cuenta,[
					'id' => 'editapartida_txCuenta',
					'class' => 'form-control ' . ($model->cuenta != '' || $model->rubro == '' || $consulta == 3 ? 'solo-lectura' : ''),
					'style' => 'width:35px;text-align:center',
					'max-length' => 1,
					'tabIndex' => ($model->cuenta != '' || $model->rubro == '' ? -1 : 0),
					'onkeypress' => 'return justNumbers(event)',
					]); ?>
		</td>
		<td width="15px"></td>
		<td><label for="subCuenta">SubCuenta:</label></td>
		<td><?= Html::input('text','CuentaPartida[subcuenta]',$model->subcuenta,[
					'id' => 'editapartida_txSubCuenta',
					'class' => 'form-control',
					'style' => 'width:35px;text-align:center',
					'max-length' => 2,
					'onkeypress' => 'return justNumbers(event)',
					]); ?>
		</td>
		<td width="15px"></td>
		<td><label for="conc">Conc:</label></td>
		<td><?= Html::input('text','CuentaPartida[conc]',$model->conc,[
					'id' => 'editapartida_txConc',
					'class' => 'form-control ' . ($model->conc != '' || $model->subcuenta == '' || $consulta == 3 ? 'solo-lectura' : ''),
					'style' => 'width:35px;text-align:center',
					'max-length' => 1,
					'tabIndex' => ($model->conc != '' || $model->subcuenta == '' ? -1 : 0),
					'onkeypress' => 'return justNumbers(event)',
					]); ?>
		</td>
		<td width="15px"></td>
		<td><label for="subConc">SubConc:</label></td>
		<td><?= Html::input('text','CuentaPartida[subconc]',$model->subconc,[
					'id' => 'editapartida_txSubConc',
					'class' => 'form-control ' . ($model->subconc != '' || $model->conc == '' || $consulta == 3 ? 'solo-lectura' : ''),
					'style' => 'width:35px;text-align:center',
					'max-length' => 1,
					'tabIndex' => ($model->subconc != '' || $model->conc == '' || $consulta == 3 ? -1 : 0),
					'onkeypress' => 'return justNumbers(event)',
					]); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="55px"><label for="nombre">Nombre:</label></td>
		<td>
			<?= Html::input('text','CuentaPartida[nombre]',$model->nombre,[
					'id' => 'editapartida_txNombre',
					'class' => 'form-control',
					'style' => 'width:465px',
					]); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="100px"><label for="cuentaBancaria">Cuenta Bancaria:</label></td>
		<td>
			<?= Html::dropDownList('CuentaPartida[bcocta_id]',$model->bcocta_id,utb::getAux('banco_cuenta','bcocta_id',"titular || ' - ' || coalesce(cbu,'')",1),[
					'id' => 'editapartida_dlCuentaBancaria',
					'class' => 'form-control',
					'style' => 'width:420px',
					//'onchange' => '$.pjax.reload({container:"#PjaxCambiaRadio",method:"POST",data:{ctaPartida:$(this).val()}})',
					]); ?>
		</td>
	</tr>
</table>

</div>

<table>
	<tr>
		<td>
			<?= Html::button('Aceptar',[
					'class' => 'btn btn-success',
					'id' => 'editapartida_btAceptar',
					'onclick' => 'editapartidabtAceptar()',
					]); ?>
		</td>
		<td width="20px"></td>
		<td>
			<?= Html::a('Cancelar',['index'],[
					'class' => 'btn btn-primary',
					'id' => 'editapartida_btCancelar',
					]); ?>
		</td>
	</tr>
</table>

</div>


<?php

echo $form->errorSummary( $model, [ 'id' => 'pp_partida_errorSummary', 'style' => 'margin-top: 8px; margin-right: 15px']);

ActiveForm::end();

?>

<script>
function editapartidabtAceptar()
{
	var error = new Array(),
		nombre = $("#editapartida_txNombre").val(),
		nivel = parseInt($("#editapartida_txNivel").val());

	switch (nivel)
	{
		case 2:
			if ($("#editapartida_txSubGrupo").val() == 0 || $("#editapartida_txSubGrupo").val() == '')
				error.push( "Ingrese SubGrupo." );

			break;

		case 3:
			if ($("#editapartida_txRubro").val() == 0 || $("#editapartida_txRubro").val() == '')
				error.push( "Ingrese Rubro." );

			break;

		case 4:
			if ($("#editapartida_txCuenta").val() == 0 || $("#editapartida_txCuenta").val() == '')
				error.push( "Ingrese Cuenta." );

			break;

		case 5:
			if ($("#editapartida_txSubCuenta").val() == 0 || $("#editapartida_txSubCuenta").val() == '')
				error.push( "Ingrese SubCuenta." );

			break;

		case 6:
			if ($("#editapartida_txConc").val() == 0 || $("#editapartida_txConc").val() == '')
				error.push( "Ingrese Concepto." );

			break;

		case 7:
			if ($("#editapartida_txSubConc").val() == 0 || $("#editapartida_txSubConc").val() == '')
				error.push( "Ingrese SubConcepto." );

			break;
	}

	if(nombre == '')
		error.push( "Ingrese un nombre." );

	if ( error.length == 0 )
		$("#formEditaPartida").submit();
	else
		mostrarErrores( error, "#pp_partida_errorSummary" );

}

function editapartidabtCancelar()
{
	$("#ModalCuenta").modal("hide");
}

function limpiarElementosEditCuenta()
{
	$("#editapartida_txCod").val("");
	$("#editapartida_txNomRedu").val("");
	$("#editapartida_txNombre").val("");
	$("#editapartida_txPartidaID").val("");
	$("#editapartida_txPartidaNom").val("");
	$("#editapartida_dlTCta").val("");
}

$(document).ready(function() {

	$("#ModalCuenta").on("hidden.bs.modal",function() {

		limpiarElementosEditCuenta();
	});
});

</script>
