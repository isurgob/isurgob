<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\web\Session;

?>

<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td width="60px"><label>Cuenta: </td>
		<td><?= Html::dropDownList('dlCuenta',0,$model->consultaCuenta( $model->trib_id ),
			[	'id'=>'pagocta_dlCuenta',
				'class'=>'form-control',
				'style'=>'width:200px;text-align:left',
			]) ?>
		</td>
	</tr>
	<tr>
		<td><label>Monto:</label></td>
		<td width="60px"><?= Html::input('text','txMonto','',['id'=>'pagoctaBuscar_txMonto','class'=>'form-control','style'=>'width:80px;text-align:right']) ?></td>
</table>

<table style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td>
			<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'id' => 'btBuscarPagoctaAceptar', 'onclick' => 'ControlesBuscar()'])?>
		</td>
	</tr>
</table>

<div id="pagocta_agregarCuenta_errorSummary" class="error-summary" style="display:none; margin-top: 8px">
</div>

<script>
function ControlesBuscar()
{
	var error 		= new Array(),
		cuenta_id 	= $( "#pagocta_dlCuenta" ).val(),
		cuenta_nom 	= $( "#pagocta_dlCuenta option:selected" ).html(),
		monto 		= $( "#pagoctaBuscar_txMonto" ).val();

	if (cuenta_id == '')
		error.push( "Ingrese una cuenta." );

	if ( error.length == 0 )
	{
		//Antes de ejecutar el Pjax, pongo los valores por defecto
		$("#pagoctaBuscar_txMonto").val("0");
		ocultarErrores( "#pagocta_agregarCuenta_errorSummary" );

		$.pjax.reload({
			container	: "#pagocta_pjaxGrillaCuenta",
			method		: "GET",
			replace		: false,
			push		: false,
			data:{
				"cuenta_id"		: cuenta_id,
				"cuenta_nom"	: cuenta_nom,
				"monto"			: monto,
				"action"		: 0,
			},
		});

		$("#AgregaCuentaPagocta").modal("hide");

	} else
	{
		mostrarErrores( error, "#pagocta_agregarCuenta_errorSummary" );

	}
}

</script>
