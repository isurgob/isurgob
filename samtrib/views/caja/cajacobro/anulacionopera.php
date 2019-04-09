<?php
/**
 * Forma que se dibuja como ventana modal.
 * Se encarga de la "Anulación".
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

?>

<div class="form_anulacion">


<table>
	<tr>
		<td width="50px"><label>Caja:</label><td>
		<td width="40px"><?= Html::input('text','txCaja',$model->caja_caja_id,
				['id'=>'anulaCajaOpera_txCaja','class'=>'form-control solo-lectura','style'=>'width:40px;text-align:center;font-weight:bold;']); ?></td>
		<td width="20px"></td>
		<td width="50px"><label>Cajero:</label><td>
		<td width="100px"><?= Html::input('text','txCajero',utb::getCampo('sam.sis_usuario','usr_id = ' . Yii::$app->user->id,'nombre'),
										['id'=>'anulaCajaOpera_txCajero','class'=>'form-control solo-lectura','style'=>'width:100px;text-align:center']); ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Fecha:</label><td>
		<td width="100px"><?= Html::input('text','txFecha',Fecha::getDiaActual(),
				['id'=>'anulaCajaOpera_txFecha','class'=>'form-control solo-lectura','style'=>'width:100px;text-align:center;font-weight:bold;']); ?>
		</td>
	</tr>
</table>

<ul class='menu_derecho'>
<li><hr style="color: #FFF; margin:1px" /></li>
</ul>

<table>
	<tr>
		<td width="55px"><label>Comprob</label></td>
		<td>
			<?= Html::input('text','txComprob','',['id'=>'anulaCajaOpera_txComprob','class'=>'form-control','style'=>'width:80px']); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="55px"><label>Motivo:</label></td>
		<td>
			<?= Html::input('text','txMotivo','',['id'=>'anulaCajaOpera_txMotivo','class'=>'form-control','style'=>'width:200px']); ?>
		</td>
	</tr>
</table>

<table style="margin-left:25px;margin-top:20px">
	<tr>
		<td width="15px"></td>
		<td><?= Html::button('Aceptar',['class'=>'btn btn-success','onclick'=>"btAceptarAnulacionOpera()"]); ?></td>
		<td width="25px"></td>
		<td><?= Html::button('Cancelar',['class'=>'btn btn-primary','onclick'=>"cerrarVentanaAnulacionOpera()"]); ?></td>
	</tr>
</table>
</div>

<!-- INICIO Mensajes de error -->
<div id="anulaOpera_errorSummary" class="error-summary" style="display:none; margin-top: 8px">

</div>
<!-- FIN Mensajes de error -->

<?php

Pjax::begin(['id'=>'PjaxAceptaAnulacionOpera']);

	$comprob = Yii::$app->request->post('comprob',0);
	$motivo = Yii::$app->request->post('motivo',0);

	if ($comprob != 0)	//Eliminación de Operación
	{
		$res = $model->anulaOperacion($comprob,$motivo);

		if ($res['return'] == 1)	//Si se eliminó correctamente
		{
            //Oculto el div de errores
            echo '<script>$( "#anulaOpera_errorSummary" ).css( "display", "none" );</script>';

			//Cierro la ventana modal y Muestro el mensaje en la forma principal
			echo '<script>cerrarVentanaAnulacionOpera();</script>';
			echo '<script>$.pjax.reload({container:"#mensajesCajaCobro",data:{mensaje:"La solicitud de anulación de Operación se realizó correctamente.",m:1},method:"POST"});</script>';

		} else
		{
			echo '<script>mostrarErrores( [ "'.$res['mensaje'].'"], "#anulaOpera_errorSummary" );</script>';
		}
	}

Pjax::end();

?>

<script>
function cerrarVentanaAnulacionOpera()
{
	$("#anulaCajaOpera_txComprob").val('');
	$("#anulaCajaOpera_txMotivo").val('');
	$("#anulaCajaOpera_txTipo1").prop("checked",true);
	$("#ModalAnulacionOpera").modal("hide");
}

function btAceptarAnulacionOpera()
{
	var error = new Array(),
        comprob = $("#anulaCajaOpera_txComprob").val(),
        motivo = $("#anulaCajaOpera_txMotivo").val();

	if (comprob == '')
		error.push( "Ingrese un comprobante." );

	if (motivo == '')
		error.push( "Ingrese un motivo." );

	if (error.length == 0)
    {
        //Ocultar div de errores
        $( "#anulaOpera_errorSummary" ).css( "display", "none" );

        $.pjax.reload({
			container:"#PjaxAceptaAnulacionOpera",
			method:"POST",
			data:{
				comprob:comprob,
				motivo:motivo,
		}});
    } else
		mostrarErrores( error, "#anulaOpera_errorSummary" );
}

$( "#ModalAnulacionOpera" ).on( "shown.bs.modal", function() {
    $( "#anulaOpera_errorSummary" ).css( "display", "none" );
});

</script>
