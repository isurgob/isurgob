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
 * agregar o modificar una cuenta a una partida presupuestaria.
 *
 * Recibo:
 *
 * 		+ $model => Modelo de "Partida Presupuestaria"
 * 		+ $consulta => Variable que identifica el propósito con el cual se dibuja la forma.
 * 			$consulta = 0 => Crear una nueva cuenta.
 * 			$consulta = 3 => Actualizar una cuenta existente.
 *
 * 		+ $cuenta => Variable que indica si se accede a la ventana desde una partida o desde la edición de cuentas.
 * 			$cuenta = 1 => Se accede desde cuentas. (La modificación de "Partida" debe estar habilitada).
 * 			$cuenta = 0 => Se accede desde partidas presupuestarias.
 */

$form = ActiveForm::begin(['id' => 'formEditaCuenta'.$consulta]);

//Se crea un Pjax para poder actualizar la vista modal al momento de actualizar un registro.
Pjax::begin(['id' => 'PjaxEditaCuenta'.$consulta]);

	$cuenta_id = Yii::$app->request->get('cuenta_id',0);

	if ( $cuenta_id != 0 )
		$model->cargarCuenta( $cuenta_id );

echo Html::input('hidden','CuentaPartida[part_id]',$model->part_id);
?>

<div class="editacuenta">

<div class="form-panel" style="padding-right:5px">

<table>
	<tr>
		<td width="55px"><label>Partida:</label></td>
		<td colspan="3">
			<?= Html::input('text','CuentaPartida[cta_part_id]',$model->cta_part_id,[
					'id' => 'editacuenta_txPartidaID'.$consulta,
					'class' => 'form-control' . ($cuenta == 0 ? ' solo-lectura' : ''),
					'style' => 'width:150px;text-align:center',
					'tabIndex' => ($cuenta == 0 ? -1 : 0),
					'onchange' => '$.pjax.reload({container:"#PjaxCambiaPartida'. $consulta .'",type:"POST",replace:false,push:false,data:{cuenta_part_id:$(this).val()}})',
				]);
			?>
		</td>
		<td><label> - </label></td>
		<td>
			<?php

				Pjax::begin(['id' => 'PjaxCambiaPartida'.$consulta]);

					//Ocultar el mensaje de error
					echo '<script>$( "#pp_cuenta_errorSummary" ).css( "display", "none" );</script>';

					$part_id = Yii::$app->request->post( 'cuenta_part_id', $model->cta_part_id );

					if ( $part_id == '' )
					{
						$part_id = 0;
					}

					//Verificar que la partida seleccionada exista
					if ( stripos( $part_id, '.' ) )	//Verificar según formato y formatoaux
					{
						$res = Yii::$app->db->createCommand("SELECT EXISTS (SELECT 1 FROM cuenta_partida WHERE formato = '" . $part_id . "' OR formatoaux = '" . $part_id . "')")->queryScalar();

						if ( $res )	//Si existe la cuenta, obtener el número de cuenta
							$part_id = utb::getCampo( 'cuenta_partida', "formato = '" . $part_id . "' OR formatoaux = '" . $part_id . "'", 'part_id' );
						else
							$part_id = 0;

					} else	//Verificar según número de partida
					{
						$res = Yii::$app->db->createCommand("SELECT EXISTS (SELECT 1 FROM cuenta_partida WHERE part_id = " . $part_id . ")")->queryScalar();
					}

					echo Html::input('text','CuentaPartida[cta_part_nom]',utb::getCampo('cuenta_partida','part_id = ' . $part_id,'nombre'),[
									'id' => 'editacuenta_txPartidaNom'.$consulta,
									'class' => 'form-control solo-lectura',
									'style' => 'width:300px;text-align:left',
									'tabIndex' => -1,
									]);

					if ($res != 1)
					{
						echo '<script>$("#editacuenta_txPartidaID'.$consulta.'").val("");</script>';
						?>
						<script>
						$("#PjaxCambiaPartida<?= $consulta ?>").on("pjax:end",function() {

							$("#editacuenta_txPartidaID<?= $consulta ?>").focus();

							$("#PjaxCambiaPartida<?= $consulta ?>").off("pjax:end");
						});
						</script>

						<?php
					} else
					{
					?>
					<script>
					$("#PjaxCambiaPartida<?= $consulta ?>").on("pjax:end",function() {

						$("#editacuenta_txNomRedu<?= $consulta ?>").focus();

						$("#PjaxCambiaPartida<?= $consulta ?>").off("pjax:end");
					});
					</script>

					<?php
					}

					//Validar si la partida seleccionada no tiene hijos
					$res = $model->validarEstadoPadre( $part_id );

					if ( $res == 1 )
					{
						echo '<script>$("#editacuenta_txPartidaID'.$consulta.'").val("");</script>';
						echo '<script>$("#editacuenta_txPartidaNom'.$consulta.'").val("");</script>';
						?>
						<script>
						$("#PjaxCambiaPartida<?= $consulta ?>").on("pjax:end",function() {

							mostrarErrores( ["La partida seleccionada tiene hijos."], "#pp_cuenta_errorSummary" );

							$("#editacuenta_txPartidaID<?= $consulta ?>").focus();

							$("#PjaxCambiaPartida<?= $consulta ?>").off("pjax:end");
						});
						</script>

						<?php
					} else
					{
						// Introducir el número de partida en el campo de partida
						echo '<script>$("#editacuenta_txPartidaID'.$consulta.'").val("' .$part_id . '");</script>';
					}

				Pjax::end();
			?>
		</td>
	</tr>
	<tr>
		<td width="55px"><label for="código">Cód:</label></td>
		<td><?= Html::input('text','CuentaPartida[cta_id]',$model->cta_id,[
					'id' => 'editacuenta_txCod'.$consulta,
					'class' => 'form-control solo-lectura',
					'style' => 'width:40px;text-align:center',
					'tabIndex' =>-1,
					]); ?>
		</td>
		<td width="20px"></td>
		<td><label for="editacuenta_txNomRedu">Nombre Redu:</label></td>
		<td></td>
		<td><?= Html::input('text','CuentaPartida[cta_nombre_redu]',$model->cta_nombre_redu,[
					'id' => 'editacuenta_txNomRedu'.$consulta,
					'class' => 'form-control',
					'style' => 'width:300px;',
					'maxlength'	=> '25',
					]); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="55px"><label for="editacuenta_txNombre">Nombre:</label></td>
		<td><?= Html::input('text','CuentaPartida[cta_nombre]',$model->cta_nombre,[
					'id' => 'editacuenta_txNombre'.$consulta,
					'class' => 'form-control',
					'style' => 'width:350px;',
					'maxlength'	=> '40',
					]); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="55px"><label for="editacuenta_dlTCta">Tipo:</label></td>
		<td><?= Html::dropDownList('CuentaPartida[cta_tcta]',$model->cta_tcta,utb::getAux('ctacte_tcta','cod','nombre',0),[
					'id' => 'editacuenta_dlTCta'.$consulta,
					'class' => 'form-control',
					'style' => 'width:150px',
					]); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="115px"><label for="cuentaAtrasada">Cuenta Atrasada</label></td>
	</tr>
	<tr>
		<td width="115px" style="padding-left:10px">
			<?=
				Html::radio('CuentaPartida[cta_id_atras]',$model->cta_id == $model->cta_id_atras,[
					'id' => 'editacuenta_mismaCuenta'.$consulta,
					'label' => 'Misma Cuenta',
					'value' => ( $model->cta_id != '' || $model->cta_id != null ? $model->cta_id : 0 ),
					'onchange' => 'cambiaRadio'.$consulta.'()',
				]);
			?>

		</td>
	</tr>
</table>
<?php
	/*
	 * Cuando se tiene activado el radio "Otra Cuenta" y se modifica la cuenta,
	 * se ejecuta este Pjax que le asigna el valor de la cuenta a "Otra Cuenta".
	 */
	Pjax::begin(['id' => 'PjaxCambiaRadio'.$consulta]);

		$ctaPartida = Yii::$app->request->post('ctaPartida',$model->cta_id_atras);

?>
<table>
	<tr>
		<td width="115px" style="padding-left:10px">
			<?= Html::radio('CuentaPartida[cta_id_atras]',$model->cta_id != $ctaPartida,[
					'id' => 'editacuenta_otraCuenta'.$consulta,
					'label' => 'Otra',
					'value' => $ctaPartida,
					'onchange' => 'cambiaRadio'.$consulta.'()',
					]); ?>
		</td>
		<td><?= Html::dropDownList('dlOtraCuenta',($ctaPartida == $model->cta_id ? 0 : $ctaPartida),utb::getAux('cuenta_partida','part_id','nombre',1),[
					'id' => 'editacuenta_dlOtraCuenta'.$consulta,
					'class' => 'form-control ' . ($ctaPartida == $model->cta_id ? 'solo-lectura' : ''),
					'style' => 'width:288px',
					'onchange' => '$.pjax.reload({container:"#PjaxCambiaRadio'. $consulta .'",method:"POST",data:{ctaPartida:$(this).val()}})',
					]); ?>
		</td>
	</tr>
</table>

	<?php

		Pjax::end();

	?>

</div>

<div class="text-center">
	<?= Html::button('Aceptar',[
			'class' => 'btn btn-success',
			'id' => 'editacuenta_btAceptar'.$consulta,
			'onclick' => 'editacuentabtAceptar'.$consulta.'()',
		]);
	?>
	&nbsp;&nbsp;
	<?= Html::button('Cancelar',[
			'class' => 'btn btn-primary',
			'id' => 'editacuenta_btCancelar'.$consulta,
			'onclick' => 'editacuentabtCancelar'.$consulta.'()',
		]);
	?>

</div>
</div>


<?php

Pjax::end();

echo $form->errorSummary( $model, ['id' => 'pp_cuenta_errorSummary', 'style' => 'margin-top: 8px;margin-right: 15px']);

ActiveForm::end();

?>


<script>
function cambiaRadio<?= $consulta ?>()
{
	if ($("#editacuenta_otraCuenta<?= $consulta ?>").is(":checked"))
		$("#editacuenta_dlOtraCuenta<?= $consulta ?>").prop("class","form-control");
	else
	{
		$("#editacuenta_dlOtraCuenta<?= $consulta ?>").prop("class","form-control solo-lectura");
		$("#editacuenta_dlOtraCuenta<?= $consulta ?>").val(0);
	}

}

function editacuentabtAceptar<?= $consulta ?>()
{
	var partida = $("#editacuenta_txPartidaID<?= $consulta ?>").val(),
		nombreredu = $("#editacuenta_txNomRedu<?= $consulta ?>").val(),
		nombre = $("#editacuenta_txNombre<?= $consulta ?>").val(),
		otraCuenta = $("#editacuenta_dlOtraCuenta<?= $consulta ?>").val(),
		error = new Array();

	if ($("#editacuenta_txPartidaID<?= $consulta ?>").prop("class") == "form-control")
	{
		if (partida == '')
			error.push( "Ingrese una partida." );
	}

	if (nombreredu == '')
		error.push( "Ingrese un nombre reducido." );

	if (nombre == '')
		error.push( "Ingrese un nombre." );

	if( $("#editacuenta_otraCuenta<?= $consulta ?>").is(":checked") )
	{
		if( otraCuenta == '' || otraCuenta == 0 )
			error.push( "Ingrese una cuenta." );
	}

	if (error == '')
		$("#formEditaCuenta<?= $consulta ?>").submit();
	else
		mostrarErrores( error, "#pp_cuenta_errorSummary" );

}

function editacuentabtCancelar<?= $consulta ?>()
{
	if (<?= $consulta ?> == 0)
		$("#ModalNuevaCuenta").modal("hide");
	else
		$("#ModalEditaCuenta").modal("hide");
}

function limpiarElementosEditCuenta<?= $consulta ?>()
{
	$("#editacuenta_txCod<?= $consulta ?>").val("");
	$("#editacuenta_txNomRedu<?= $consulta ?>").val("");
	$("#editacuenta_txNombre<?= $consulta ?>").val("");
	$("#editacuenta_txPartidaID<?= $consulta ?>").val("");
	$("#editacuenta_txPartidaNom<?= $consulta ?>").val("");
	$("#editacuenta_dlTCta<?= $consulta ?>").val("");
}

$(document).ready(function()
{
	if (<?= $consulta ?> == 0)
	{
		$("#ModalNuevaCuenta").on("hidden.bs.modal",function() {

			limpiarElementosEditCuenta<?= $consulta ?>();
		});
	} else
	{
		$("#ModalEditaCuenta").on("hidden.bs.modal",function() {

			limpiarElementosEditCuenta<?= $consulta ?>();
		});
	}

});

</script>
