<?php
/**
 * Forma que se dibuja como ventana modal.
 * Se encarga de la "Apertura", "Reapertura" y "Cierre" de cajas.
 *
 * Los datos que se deben enviar como parámetros son:
 * 		@param string $id 	=> Id de la ventana modal.
 * 		@param string $accion 	=> Tipo de acción que se debe ejecutar A. Apertura - R. Reapertura - C. Cierre
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


$form = ActiveForm::begin(['id' => 'formApertura'.$id,
					'action' => ['apertura'],
					'options' => [
						'autocomplete' => 'false',
					]]);
?>

<div class="form_abricaja">
<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorAbrirCaja'.$id]);

			$mensaje = '';

			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaAbrirCaja'.$id,
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaAbrirCaja". $id . "').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->
<?= Html::input('hidden','txAccion',$accion,['id'=>'abrircaja_txAccion'.$id]) ?>
<table style="margin-left:25px">
	<tr>
		<td width="100px"><label>Usuario:</label><td>
		<td width="100px">
			<?= Html::input('text','txUsuarioNom', utb::getCampo('sam.sis_usuario','usr_id = ' . Yii::$app->user->id,'nombre'), [
					'id'=>'abrircaja_txUsuarioNom'.$id,
					'class'=>'form-control solo-lectura',
					'style'=>'width:100px;text-align:center;font-weight:bold;',
					'autocomplete' => 'off',
				]);
			?>
		</td>
	</tr>
	<tr>
		<td width="100px"><label>Contraseña:</label><td>
		<td width="100px">
			<?= Html::input('password','txClaveID','',[
					'id'=>'abrircaja_txClaveID'.$id,
					'class'=>'form-control',
					'style'=>'width:100px;text-align:center',
					'autocomplete' => 'false',
				]);
			?>
		</td>
	</tr>
	<tr>
		<td width="100px"><label>Caja:</label><td>
		<td width="100px">
            <?= Html::input('text','txCaja','',[
                    'id'=>'abrircaja_txCaja'.$id,
                    'class'=>'form-control',
                    'style'=>'width:50px;text-align:center',
                    'onkeypress' => 'return justNumbers( event )',
                ]);
            ?>
        </td>
	</tr>
	<tr>
		<td width="100px"><label>Fecha:</label><td>
		<td><?= DatePicker::widget([
							'id' => 'abrircaja_txFecha'.$id,
							'name' => 'txFecha',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['class' => 'form-control', 'style' => 'width:80px;text-align:center',],
							'value' => Fecha::usuarioToDatePicker( Fecha::getDiaActual() ),
						]);
			?>
		</td>
	</tr>
</table>

<div class="text-center" style="margin-top:8px">
	<?= Html::button('Aceptar',['class'=>'btn btn-success','onclick'=>"btAceptar".$id."('".$id."')"]); ?>
	&nbsp;&nbsp;
	<?= Html::button('Cancelar',['class'=>'btn btn-primary','onclick'=>"cerrarVentana".$id."('".$id."')"]); ?>
</div>

<div id="abrircaja_errorSummary<?= $id ?>" class="error-summary" style="display:none;margin-top: 8px">

</div>

</div>
<?php

ActiveForm::end();

?>

<script>
function cerrarVentana<?= $id ?>(id)
{
	$("#abrircaja_txtxClaveID<?= $id ?>").val("");
	$("#abrircaja_txCaja<?= $id ?>").val("");
	$("#Modal"+id).modal("hide");
}

function btAceptar<?= $id ?>(id)
{
	var user = $("#abrircaja_txUsuarioNom<?= $id ?>").val(),
		clave = $("#abrircaja_txClaveID<?= $id ?>").val(),
		caja_id = $("#abrircaja_txCaja<?= $id ?>").val(),
		fecha = $("#abrircaja_txFecha<?= $id ?>").val(),
		error = new Array();

	if (clave == '')
		error.push( "Ingrese una clave." );

	if (caja_id == '')
		error.push( "Ingrese una caja." );

	if (fecha == '')
		error.push( "Ingrese una fecha." );

	if ( error.length == 0 )
	{
		$("#formApertura<?= $id ?>").submit();

	}
	else
		mostrarErrores( error, "#abrircaja_errorSummary<?= $id ?>" );
}

$(document).ready(function() {
	$('input,form').attr('autocomplete','off');
	$("#abrircaja_txClaveID<?= $id ?>").val("");

});
</script>
