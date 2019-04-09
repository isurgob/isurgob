<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;

use app\utils\db\utb;
use app\models\objeto\Cem;

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscar']);


echo Html::input('hidden', 'opcion', 1, ['id' => 'cemBuscarOpcion']);
?>

<?php
Pjax::begin(['id' => 'pjaxBusqueda', 'enableReplaceState' => false, 'enablePushState' => false]);

$elegido = Yii::$app->request->get('elegido', 1);

$cuadro_id = Yii::$app->request->get('cuadro_id', null);
$piso = Yii::$app->request->get('piso', null);
$fila = Yii::$app->request->get('fila', null);
$nume = Yii::$app->request->get('nume', null);
$tipo = Yii::$app->request->get('tipo', null);
$bis = Yii::$app->request->get('bis', null);


$cuerpo = null;
$tienePiso = 0;
$tieneFila = 0;
$tieneNume = 0;
$tieneTipo = 0;
$tieneBIS = 0;

if($cuadro_id != null){
	
	$res = Cem::getCuadro($cuadro_id);
	
	$tienePiso 	= $res['piso'];
	$tieneFila 	= $res['fila'];
	$tieneNume 	= $res['nume'];
	$tieneTipo	= $res['tipo'] != '';
	$tipo		= $res['tipo'];
	$tieneBIS	= $res['bis'];

}

$cuerpos = [];
		
if($cuadro_id != null)
	$cuerpos = utb::getAux('cem_cuerpo', 'cuerpo_id', 'nombre', 0, "cuadro_id = '$cuadro_id' Or trim(both ' ' from cuadro_id) = ''");

//contiene el codigo del obeto completo en caso de que hayan ingresado algo
$objid = isset($_GET['bObjid']) ? utb::getObjeto(4, $_GET['bObjid']) : null;
?>
<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td><?= Html::radio('cemBuscar', $elegido == 1,['id'=>'rbObjeto','label'=>'Código de Objeto:','onchange' => 'ControlesBuscarCem("rbObjeto")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txObjeto', $objid, ['disabled' => $elegido != 1,'class' => 'form-control','id'=>'txObjeto','maxlength'=>'7','style'=>'width:100px', 'onchange' => 'cambiaObjid($(this).val())']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::radio('cemBuscar', $elegido == 2, ['id'=>'rbCodAnt', 'label'=>'Código Anterior:', 'onchange' => 'ControlesBuscarCem("rbCodAnt")'])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txCodAnt', null, ['class' => 'form-control', 'disabled' => true, 'id'=>'txCodAnt','maxlength'=>'15','style'=>'width:100px']); ?>
	</td>
</tr>

<tr>
	<td valign="top"><?= Html::radio('cemBuscar', $elegido == 3, ['id'=>'rbNomeclatura', 'label'=>'Nomenclatura:', 'onchange' => 'ControlesBuscarCem("rbNomeclatura")'])?> </td>
</tr>

<tr>
	<td><label class="pull-right">Cuadro: </label></td>
	<td></td>
	<td>
		<?= Html::dropDownList('dlCuadro', $cuadro_id, utb::getAux('cem_cuadro', 'cua_id'), ['prompt' => '','class' => 'form-control', 'disabled' => $elegido != 3, 'id'=>'dlCuadro', 'style'=>'width:100px', 'onchange' => 'cambiaCuadro($(this).val())']); ?>
	</td>
</tr>
<tr>
	<td><label class="pull-right">Cuerpo: </label></td>
	<td></td>
	<td>
		<?= Html::dropDownList('dlCuerpo', null, $cuerpos, ['prompt' => '','class' => 'form-control', 'disabled' => ($cuerpos  == null || count($cuerpos) == 0), 'id'=>'dlCuerpo', 'style'=>'width:100px']); ?>
	</td>
</tr>
<tr>
	<td><label class="pull-right">Tipo: </label></td>
	<td></td>
	<td>
		<?= Html::dropDownList('dlTipo', $tipo, utb::getAux('cem_tipo'), ['prompt' => '', 'class' => 'form-control', 'disabled' => $tieneTipo, 'id'=>'dlTipo', 'style'=>'width:100px']); ?>
	</td>
</tr>
<tr>
	<td><label class="pull-right">Piso: </label></td>
	<td></td>
	<td>
		<?= Html::input('text', 'txPiso', $piso, ['class' => 'form-control pull-left', 'disabled' => $tienePiso == 0, 'id'=>'txPiso','maxlength'=>'3','style'=>'width:50px']); ?>
	</td>
</tr>
<tr>
	<td><label class="pull-right">Fila: </label></td>
	<td></td>
	<td>
		<?= Html::input('text', 'txFila', $fila, ['class' => 'form-control pull-left', 'disabled' => $tieneFila == 0, 'id'=>'txFila','maxlength'=>'3','style'=>'width:50px']); ?>
	</td>
</tr>
<tr>
	<td><label class="pull-right">Nume: </label></td>
	<td></td>
	<td>
		<?= Html::input('text', 'txNume', $nume, ['class' => 'form-control pull-left', 'disabled' => $tieneNume == 0, 'id'=>'txNume','maxlength'=>'3','style'=>'width:50px']); ?>
	</td>
</tr>

<tr>
	<td><label class="pull-right">BIS: </label></td>
	<td></td>
	<td>
		<?= Html::input('text', 'txBIS', $bis, ['class' => 'form-control pull-left', 'disabled' => $tieneBIS == 0, 'id'=>'txBIS','maxlength'=>'3','style'=>'width:50px']); ?>
	</td>
</tr>
		

<tr>
	<td colspan='3'>
		<br><div id="errorbuscacem" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='3'>
		<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'ControlesBuscarCem("btAceptar");'])?>
	</td>
</tr>
</table>
<?php
Pjax::end();
?>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscarCem(control){
	
	var checkObj;
	
	switch(control){
		
		case "rbObjeto" :
		
			$("#txObjeto").val("");
			$("#txObjeto").prop("disabled", false);
			$("#txCodAnt").prop("disabled", true);
			
			$("#dlCuadro").prop("disabled", true);
			$("#dlCuerpo").prop("disabled", true);
			$("#dlTipo").prop("disabled", true);
			$("#txPiso").prop("disabled", true);
			$("#txFila").prop("disabled", true);
			$("#txNume").prop("disabled", true);
			$("#txBIS").prop("disabled", true);
			
			$("#cemBuscarOpcion").val(1);
			
			break;
			
		case "rbCodAnt" : 
		
			$("#txCodAnt").val("");
			$("#txCodAnt").prop("disabled", false);
			$("#txObjeto").prop("disabled", true);
			
			$("#dlCuadro").prop("disabled", true);
			$("#dlCuerpo").prop("disabled", true);
			$("#dlTipo").prop("disabled", true);
			$("#txPiso").prop("disabled", true);
			$("#txFila").prop("disabled", true);
			$("#txNume").prop("disabled", true);
			$("#txBIS").prop("disabled", true);
			
			$("#cemBuscarOpcion").val(3);
			break;
			
		case "rbNomeclatura" :
			
			$("#txObjeto").prop("disabled", true);
			$("#txCodAnt").prop("disabled", true);
			
			$("#dlTipo").val("");
			$("#dlTipo").prop("disabled", false);
			
			$("#dlCuadro").val("");
			$("#dlCuadro").prop("disabled", false);
			
			$("#dlCuerpo").val("");			
			$("#txPiso").val("");			
			$("#txFila").val("");			
			$("#txNume").val("");
			$("#txBIS").val("");
			
			$("#cemBuscarOpcion").val(2);
			break;	
		
		case "btAceptar" :
		
			var error;
			error ='';
			
			if ($('#rbObjeto').is(':checked') && $("#txObjeto").val()=='') error = 'Ingrese un Objeto';			
			if ($('#rbCodAnt').is(':checked') && $("#txCodAnt").val()=='') error = 'Ingrese un Código Anterior';
			//if ($('input:radio[name=rbObjeto]:checked').val()==1 && $("#txObjeto").val()=='') error = 'Ingrese un Objeto';
			
			if (
				error=='' && 
				$("#txObjeto").val() == '' && 
				$("#txCodAnt").val() == '' &&
				$("#dlTipo option:selected").text() == '' &&
				$("#dlCuadro option:selected").text() == '' &&
				$("#dlCuerpo option:selected").text() == '' &&
				$("#txPiso").val() == '' &&
				$("#txFila").val() == '' &&
				$("#txNume").val() == '' &&
				$("#txBIS").val() == ''
				
				) error = 'No se encontraron condiciones de b�squeda';			
			
			if (error=='')
			{
				$("#frmBuscar").submit();
			}else {
				$("#errorbuscacem").html(error);
				$("#errorbuscacem").css("display", "block");
			}
			
			break;
		
	}
}

function cambiaObjid(objid){
	
	$.pjax.reload({container : '#pjaxBusqueda', replace : false, push : false, type : "GET", data : {"bObjid" : objid} } );
}

function cambiaTipo(){
	
	$("#dlCuadro").val("");
			
	$("#dlCuerpo").val("");
	$("#dlCuerpo").prop("disabled", true);
	
	$("#txPiso").val("");
	$("#txPiso").prop("disabled", true);
	
	$("#txFila").val("");
	$("#txFila").prop("disabled", true);
	
	$("#txNume").val("");
	$("#txNume").prop("disabled", true);
	
	$("#txBIS").val("");
	$("#txBIS").prop("disabled", true);
}

function cambiaCuadro(){
	
	$("#dlTipo").val("");
	
	$("#dlCuerpo").val("");
	$("#dlCuerpo").prop("disabled", false);
	
	$("#txPiso").val("");
	$("#txPiso").prop("disabled", false);
	
	$("#txFila").val("");
	$("#txFila").prop("disabled", false);
	
	$("#txNume").val("");
	$("#txNume").prop("disabled", false);
	
	$("#txBIS").val("");
	$("#txBIS").prop("disabled", false);
	
	$.pjax.reload({
		container : '#pjaxBusqueda',
		type : 'GET',
		replace : false,
		push : false,
		data : {
			'cuadro_id' : $("#dlCuadro").val(),
			'piso' : $("#txPiso").val(),
			'fila' : $("#txFila").val(),
			'nume' : $("#txNume").val(),
			'bis' : $("#txBIS").val(),
			'elegido' : 3
		}
	});
}

</script>