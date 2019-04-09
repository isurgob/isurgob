<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\MaskedInput;

use app\utils\db\utb;

Pjax::begin(['id' => 'pjaxBusqueda', 'enableReplaceState' => false, 'enablePushState' => false, 'formSelector' => '#frmBuscarRodado']);
$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscarRodado']);
?>

<?php
//se busca el objeto
$o = false;

//contiene el codigo del obeto completo en caso de que hayan ingresado algo
$objid = isset($_GET['bObjid']) ? utb::getObjeto(5, $_GET['bObjid']) : null;

if($objid !== null) $o = true;
?>
<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>

<tr>
	<td><?= Html::radio('opcion', $objid == null, ['id'=>'rbDominio', 'label'=>'Dominio:', 'onchange' => 'controlesBuscarRodado("")', 'value' => 1])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txDominio', null, ['class' => 'form-control', 'disabled' => $o, 'id'=>'txDominio','maxlength'=>'9','style'=>'width:100px; text-transform:uppercase;']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::radio('opcion', $o,['id'=>'rbCodigoObjeto','label'=>'Código de Objeto:','onchange' => 'controlesBuscarRodado("")', 'value' => 2])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txCodigoObjeto', $objid, ['disabled' => !$o,'class' => 'form-control','id'=>'txCodigoObjeto','maxlength'=>'8','style'=>'width:100px']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::radio('opcion', false, ['id'=>'rbDni', 'label'=>'DNI:', 'onchange' => 'controlesBuscarRodado("")', 'value' => 3])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txDni', null, ['class' => 'form-control', 'disabled' => true, 'id'=>'txDni','maxlength'=>'11','style'=>'width:100px']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::radio('opcion', false, ['id'=>'rbCUIT', 'label'=>'CUIT:', 'onchange' => 'controlesBuscarRodado("")', 'value' => 4])?> </td>
	<td width='5px'></td>
	<td>
		<?= MaskedInput::widget([
				'name' => 'txCUIT',
				'id' => 'txCUIT',
				'mask' => '99-99999999-9',
				'options' => [
					'disabled' => true,
					'class' => 'form-control',
					'style' => 'width:100px',
				],
			]);
		?>
	</td>
</tr>

<tr>
	<td colspan='3'>
		<br><div id="errorBuscaRodado" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='3'>
		<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'controlesBuscarRodado("btAceptar");'])?>
	</td>
</tr>

</table>
<?php ActiveForm::end(); ?>

<?php
Pjax::end();
?>

<script>
function controlesBuscarRodado(control){
	
	if(control !== "btAceptar"){
		
		
		$("#txDominio").prop("disabled", !$("#rbDominio").is(":checked"));
		$("#txCodigoObjeto").prop("disabled", !$("#rbCodigoObjeto").is(":checked"));
		$("#txDni").prop("disabled", !$("#rbDni").is(":checked"));
		$("#txCUIT").prop("disabled", !$("#rbCUIT").is(":checked"));
		
	} else if(control === "btAceptar"){
		
			var error;
			error ='';
						
			if ($('#rbDominio').is(':checked') && $("#txDominio").val()=='') error = 'Ingrese un Dominio';
			if ($('#rbCodigoObjeto').is(':checked') && $("#txCodigoObjeto").val()=='') error = 'Ingrese un Objeto';
			if ($('#rbDni').is(':checked') && $("#txDni").val()=='') error = 'Ingrese un DNI';
			if ($('#rbCUIT').is(':checked') && $("#txCUIT").val()=='') error = 'Ingrese un CUIT';
			
			
			if (
				error=='' && 
				$("#txCodigoObjeto").val() == '' && 
				$("#txDominio").val() == '' &&
				$("#txDni").val() == ''	&& 
				$("#txCUIT").val() == ''		
				) error = 'No se encontraron condiciones de b�squeda';			
			
			if (error=='')
			{
				$("#frmBuscarRodado").submit();
			}else {
				$("#errorBuscaRodado").html(error);
				$("#errorBuscaRodado").css("display", "block");
			}		
	}
}

function cambiaObjid(objid){
	
	$.pjax.reload({container : '#pjaxBusqueda', replace : false, push : false, type : "GET", data : {"bObjid" : objid} } );
}
</script>