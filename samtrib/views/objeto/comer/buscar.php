<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;

use app\utils\db\utb;

//Pjax::begin(['id' => 'pjaxBusqueda', 'enableReplaceState' => false, 'enablePushState' => false, 'formSelector' => '#frmBuscarComer']);

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscarComer']);

//contiene el codigo del obeto completo en caso de que hayan ingresado algo
$objid = (isset($_GET['bObjid']) && strlen($_GET['bObjid']) < 8) ? utb::getObjeto(2, $_GET['bObjid']) : Yii::$app->request->get('bObjid', '');

?>

<table>
	<tr>
		<td>
			<?=
				Html::radio('opcion', true,[
					'id'=>'rbCodigoComercio',
					'label'=>'Código de Comercio:',
					'onchange' => 'cambiaCheck()',
					'value' => 1,
				]);
			?>
		</td>
		<td width='5px'></td>
		<td>
			<?=
				Html::input('text', 'txCodigoComercio', $objid, [
					'disabled' 	=> false,
					'class' 	=> 'form-control',
					'id'		=> 'comer_buscar_txCodigoComercio',
					'maxlength'	=>'8',
					'style'		=>'width:100px',
				]);
			?>
		</td>
	</tr>

	<tr>
		<td>
			<?=
				Html::radio('opcion', false, [
					'id'=>'rbCuit',
					'label'=>'C.U.I.T.:',
					'onchange' => 'cambiaCheck()',
					'value' => 2,
				]);
			?>
		</td>
		<td width='5px'></td>
		<td>
			<?=
				MaskedInput::widget([
					'mask' => '99-99999999-9',
					'id' => 'txCuit',
					'name' => 'txCuit',
					'options' => [
						'maxlength'=>'13',
						'style'=>'width:100px',
						'disabled'=>true,
						'class' => 'form-control'
					],
				]);
			?>
		</td>
	</tr>

	<tr>
		<td>
			<?=
				Html::radio('opcion', false, [
					'id'=>'rbLegajo',
					'label'=>'Legajo:',
					'onchange' => 'cambiaCheck()',
					'value' => 3,
				]);
			?>
		</td>
		<td width='5px'></td>
		<td>
			<?=
				Html::input('text', 'txLegajo', null, [
					'class' => 'form-control',
					'disabled' => true,
					'id'=>'txLegajo',
					'maxlength'=>'10',
					'style'=>'width:100px',
				]);
			?>
		</td>
	</tr>

	<tr>
		<td colspan='3'>
			<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'controlesBuscarComer();'])?>
		</td>
	</tr>

</table>
<?php ActiveForm::end(); ?>
<?php //Pjax::end(); ?>

<div id="comer_buscar_errorSummary" class="error-summary" style="margin-top: 8px; display: none">
</div>

<script>
function cambiaCheck(){

	$("#comer_buscar_txCodigoComercio").prop("disabled", !$("#rbCodigoComercio").is(":checked"));
	$("#txCuit").prop("disabled", !$("#rbCuit").is(":checked"));
	$("#txLegajo").prop("disabled", !$("#rbLegajo").is(":checked"));
}

function controlesBuscarComer(){

	var error = new Array();

	if ($('#rbCodigoComercio').is(':checked') && $("#comer_buscar_txCodigoComercio").val()=='') error.push( 'Ingrese un Comercio.' );
	if ($('#rbCuit').is(':checked') && $("#txCuit").val()=='') error.push( 'Ingrese un C.U.I.T.' );
	if ($('#rbLegajo').is(':checked') && $("#txLegajo").val()=='') error.push( 'Ingrese un Legajo.' );

	if (
		error.length == 0 &&
		$("#comer_buscar_txCodigoComercio").val() == '' &&
		$("#txCuit").val() == '' &&
		$("#txLegajo").val() == ''
	) error.push( 'No se encontraron condiciones de búsqueda.' );

	if ( error.length == 0 )
	{
		$("#frmBuscarComer").submit();

	} else {
		mostrarErrores( error, "#comer_buscar_errorSummary" );
	}
}

</script>
