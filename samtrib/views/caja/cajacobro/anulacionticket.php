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
use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;

?>

<div class="form_anulacion">

<table>
	<tr>
		<td width="50px"><label>Caja:</label><td>
		<td width="40px"><?= Html::input('text','txCaja',$model->caja_caja_id,
				['id'=>'anulaTicketCaja_txCaja','class'=>'form-control solo-lectura','style'=>'width:40px;text-align:center;font-weight:bold;']); ?></td>
		<td width="20px"></td>
		<td width="50px"><label>Cajero:</label><td>
		<td width="100px"><?= Html::input('text','txCajero',utb::getCampo('sam.sis_usuario','usr_id = ' . Yii::$app->user->id,'nombre'),
										['id'=>'anulaTicketCaja_txCajero','class'=>'form-control solo-lectura','style'=>'width:100px;text-align:center']); ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Fecha:</label><td>
		<td width="100px"><?= Html::input('text','txFecha',Fecha::getDiaActual(),
				['id'=>'anulaTicketCaja_txFecha','class'=>'form-control solo-lectura','style'=>'width:100px;text-align:center;font-weight:bold;']); ?>
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
			<?= Html::input('text','txComprob','',['id'=>'anulaTicketCaja_txComprob','class'=>'form-control','style'=>'width:80PX']); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="55px"><label>Motivo:</label></td>
		<td>
			<?= Html::input('text','txMotivo','',['id'=>'anulaTicketCaja_txMotivo','class'=>'form-control','style'=>'width:200px']); ?>
		</td>
	</tr>
</table>

<table style="margin-left:25px;margin-top:20px">
	<tr>
		<td width="15px"></td>
		<td><?= Html::button('Aceptar',['class'=>'btn btn-success','onclick'=>"btAceptarAnulacionTicket()"]); ?></td>
		<td width="25px"></td>
		<td><?= Html::button('Cancelar',['class'=>'btn btn-primary','onclick'=>"cerrarVentanaAnulacionTicket()"]); ?></td>
	</tr>
</table>
</div>

<!-- INICIO Mensajes de error -->
<div id="anulaTicket_errorSummary" class="error-summary" style="display:none; margin-top: 8px">

</div>
<!-- FIN Mensajes de error -->

<?php

Pjax::begin(['id'=>'PjaxAceptaAnulacionTicket']);

	$comprob = Yii::$app->request->post('anulaTicket_comprob',0);
	$motivo = Yii::$app->request->post('anulaTicket_motivo',0);

	if ( $comprob != 0 )	//Eliminación de Ticket
	{
		$res = $model->anulaTicket( $comprob, $motivo );

		if ( $res['return'] == 1 )	//Si se eliminó correctamente
		{
            //Oculto el div de errores
            echo '<script>$( "#anulaTicket_errorSummary" ).css( "display", "none" );</script>';

			//Cierro la ventana modal y Muestro el mensaje en la forma principal
			echo '<script>cerrarVentanaAnulacionTicket();</script>';
			echo '<script>$.pjax.reload({container:"#mensajesCajaCobro",data:{mensaje:"La solicitud de anulación de Ticket se realizó correctamente.",m:1},method:"POST"});</script>';

		} else
		{
            echo '<script>mostrarErrores( [ "'.$res['mensaje'].'"], "#anulaTicket_errorSummary" );</script>';
		}
	}

Pjax::end();

?>

<script>
function cerrarVentanaAnulacionTicket()
{
	$("#anulaTicketCaja_txComprob").val('');
	$("#anulaTicketCaja_txMotivo").val('');
	$("#anulaTicketCaja_txTipo1").prop("checked",true);
	$("#ModalAnulacionTicket").modal("hide");
}

function btAceptarAnulacionTicket()
{
	var error = new Array(),
        comprob = $("#anulaTicketCaja_txComprob").val(),
        motivo = $("#anulaTicketCaja_txMotivo").val();

	if (comprob == '')
		error.push( "Ingrese un comprobante." );

	if (motivo == '')
		error.push( "Ingrese un motivo." );

	if (error.length == 0)
    {
        //Ocultar div de errores
        $( "#anulaTicket_errorSummary" ).css( "display", "none" );

        $.pjax.reload({
            container:"#PjaxAceptaAnulacionTicket",
            method:"POST",
            data:{
                anulaTicket_comprob:comprob,
                anulaTicket_motivo:motivo,
            },
        });
    } else
		mostrarErrores( error, "#anulaTicket_errorSummary" );
}

$( "#ModalAnulacionTicket" ).on( "shown.bs.modal", function() {
    $( "#anulaTicket_errorSummary" ).css( "display", "none" );
});

</script>
