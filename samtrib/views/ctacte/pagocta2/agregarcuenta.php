<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\web\Session;


Pjax::begin(['id'=>'PjaxModalCuentas']);

$trib_id = Yii::$app->request->post('trib_id',1);

?>

<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td width="60px"><label>Cuenta: </td>
		<td><?= Html::dropDownList('dlCuenta',0,$model->consultaCuenta($trib_id),
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

<table>
	<tr>
		<td>
			<br><div id="errorbuscapagocta" style="display:none;" class="alert alert-danger alert-dismissable"></div>
		</td>
	</tr>
</table>
<table style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">	
	<tr>
		<td>
			<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'id' => 'btBuscarPagoctaAceptar', 'onclick' => 'ControlesBuscar()'])?>
		</td>
	</tr>
</table>

<?php

Pjax::end();

?>
<script>
function ControlesBuscar()
{
	var error = '';
	var cuenta_id = $("#pagocta_dlCuenta").val();
	var cuenta_nom = $("#pagocta_dlCuenta option:selected").html();
	var monto = $("#pagoctaBuscar_txMonto").val();
	
	if (cuenta_id == '')
		error = 'Ingrese una cuenta.';
	
	if (error=='')
	{
		//Antes de ejecutar el Pjax, pongo los valores por defecto
		$("#pagoctaBuscar_txMonto").val("0");
		
		$.pjax.reload({	container:"#PjaxGrillaCuenta",
						method:"POST",
						data:{
							cuenta_id:cuenta_id,
							cuenta_nom:cuenta_nom,
							monto:monto,
						}});
		$("#AgregaCuentaPagocta").modal("hide");
		
	} else 
	{
		$("#errorbuscapagocta").html(error);
		$("#errorbuscapagocta").css("display", "block");
	}
}

</script>