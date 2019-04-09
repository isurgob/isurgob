<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'][] = ['label' => 'Listado de Ajustes de Cuenta Corriente'];
$this->params['breadcrumbs'][] = 'Opciones ';

$criterio = "";
?>

<div class="persona-index">
<?php 
	if (utb::getExisteProceso(3308))
	echo Html::a('Nuevo', ['create'], ['class' => 'btn btn-success pull-right']);
 ?>
	<h1>Opciones</h1>
	

<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px; margin-top:20px;'>
<?php 
	$form = ActiveForm::begin(['action' => ['listado'],'id' => 'form-cem-list']);
	
	echo '<input type="text" name="txcriterio" id="txcriterio" style="display:none">';
	echo '<input type="text" name="txdescr" id="txdescr" style="display:none">';
?>
			
<table border='0'>

	<tr>
		<td><?= Html::checkbox(null, false, ['label' => 'Tributo:', 'id' => 'ckTributo', 'class' => 'check', 'data-target' => '#dlTributo']) ?></td>
		<td width="10px"></td>
		<td colspan="7"><?= Html::dropDownList(null, null, utb::getAux('v_trib', 'trib_id', 'nombre', 0, "est = 'A' And tipo Not In (6, 7)"), 
		['class' => 'form-control', 'id' => 'dlTributo', 'disabled' => true, 'onchange' => 'cambiaTributo($(this).val());', 'style' => 'width:99%;', 'prompt' => '']) ?></td>
	</tr>
	<tr>
		<td><?= Html::checkbox(null, false, ['label' => 'Objeto', 'id' => 'checkObjeto', 'class' => 'check', 'data-target' => '#txCodigoObjeto, #botonObjeto', 'disabled' => true]) ?></td>
		<td></td>
		<td><?= Html::textInput(null, null, ['class' => 'form-control solo-lectura', 'id' => 'txTipoObjeto', 'disabled' => true, 'tabindex' => -1, 'style' => 'width:115px;']) ?></td>
		<td></td>
		<td><?= Html::textInput(null, null, ['class' => 'form-control', 'id' => 'txCodigoObjeto', 'disabled' => true, 'onchange' => 'cambiaObjeto($(this).val());', 'maxlength' => 8, 'style' => 'width:80px;']); ?></td>
		<td></td>
		<td>
		
			<?php
			Modal::begin([
				'id' => 'modalObjeto',
				'closeButton' => ['class' => 'btn btn-danger btn-sm pull-right'],
				'header' => '<h1>Búsqueda de objeto</h1>',
				'toggleButton' => [
            		'label' => '<i class="glyphicon glyphicon-search"></i>',
                	'class' => 'bt-buscar',
                	'disabled' => true,
                	'id' => 'botonObjeto'
            	],
            	'size' => 'modal-lg'
			]);
			
			Pjax::begin(['id' => 'pjaxBusquedaObjeto', 'enableReplaceState' => false, 'enablePushState' => false]);
			
			$tobj = intval(Yii::$app->request->get('tipoObjeto', 0));
			
			
			echo $this->render('//objeto/objetobuscarav', ['txCod' => 'txCodigoObjeto', 'txNom' => 'txNombreObjeto', 'tobjeto' => $tobj, 'id' => '1', 'selectorModal' => '#modalObjeto']);
				
			Pjax::end();
			
			Modal::end();
			
			echo Html::textInput(null, null, ['class' => 'form-control solo-lectura', 'id' => 'txNombreObjeto', 'disabled' => true, 'tabindex' => -1, 'style' => 'width:250px;']);
			?>
		</td>
	</tr>
	<tr>
		<td><?= Html::checkbox(null, false, ['label' => 'Año:', 'id' => 'ckAnio', 'class' => 'check', 'data-target' => '#txAnio']); ?></td>
		<td width="10px"></td>
		<td><?= Html::textInput(null, null, ['id' => 'txAnio', 'class' => 'form-control', 'disabled' => true, 'style' => 'width:50px;', 'maxlength' => 4]) ?></td>
	</tr>
	<tr>
		<td><?= Html::checkbox(null, false, ['label' => 'Cuota:', 'id' => 'ckCuota', 'class' => 'check', 'data-target' => '#txCuota']); ?></td>
		<td width="10px"></td>
		<td><?= Html::textInput(null, null, ['id' => 'txCuota', 'class' => 'form-control', 'disabled' => true, 'style' => 'width:50px;', 'maxlength' => 4]) ?></td>
	</tr>
	<tr>
		<td><?= Html::checkbox(null, false, ['label' => 'Expediente:', 'id' => 'ckExpediente', 'class' => 'check', 'data-target' => '#txExpediente']); ?></td>
		<td width="10px"></td>
		<td colspan="5"><?= Html::textInput(null, null, ['id' => 'txExpediente', 'class' => 'form-control', 'disabled' => true, 'style' => 'width:250px;']) ?></td>
	</tr>
	<tr>
		<td><?= Html::checkbox(null, false, ['label' => 'Fecha:', 'id' => 'ckFecha', 'class' => 'check', 'data-target' => '#txFechaDesde, #txFechaHasta']); ?></td>
		<td width="10px"></td>
		<td><b>Desde:</b>
		<?= DatePicker::widget(['name' => 'desde', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'disabled' => true, 'id' => 'txFechaDesde', 'style' => 'width:70px;']]); ?></td>
		<td width="10px"></td>
		<td colspan="3"><b>Hasta:</b>
		<?= DatePicker::widget(['name' => 'hasta', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'disabled' => true, 'id' => 'txFechaHasta', 'style' => 'width:80px;']]); ?></td>
	</tr>
	<tr>
		<td><?= Html::checkbox(null, false, ['label' => 'Usuario', 'id' => 'ckUsuario', 'class' => 'check', 'data-target' => '#dlUsuario']) ?></td>
		<td></td>
		<td colspan="7"><?= Html::dropDownList(null, null, utb::getAux('sam.sis_usuario', 'usr_id', 'apenom'), ['id' => 'dlUsuario', 'class' => 'form-control', 'disabled' => true, 'style' => 'width:250px;', 'prompt' => '']); ?></td>
		
	</tr>
</table>

<?php
Pjax::begin(['id' => 'pjaxTributo', 'enableReplaceState' => false, 'enablePushState' => false]);

$trib_id = intval(Yii::$app->request->get('tributo', 0));

if($trib_id > 0){
	
	$datos = utb::getVariosCampos('v_trib', "trib_id = $trib_id", 'tobj, tobj_nom');
	
	if($datos !== false){
		
		?>
		<script type="text/javascript">
		$(document).ready(function(){
			$("#txTipoObjeto").val("<?= $datos['tobj_nom'] ?>");
			
			$.pjax.reload({
				container : "#pjaxBusquedaObjeto",
				type : "GET",
				replace : false,
				push : false,
				data : {
					"tipoObjeto" : "<?= $datos['tobj'] ?>"
				}
			});
		});
		</script>
		<?php
	}
}

echo Html::input('hidden', null, null);
Pjax::end();
?>

<?php
	ActiveForm::end(); 
?>
</div>
</div>

<div style="margin-top:5px;">

	<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'controlesList("btAceptar");'])?>
</div>

<div id="errorlistcem" style="display:none; margin-top:5px;" class="error-summary"></div>

<script>
function cambiaTributo(nuevo){
	
	nuevo = parseInt(nuevo);
	
	$("#txTipoObjeto").val("");
	$("#txCodigoObjeto").val("");
	$("#txNombreObjeto").val("");
	var $checkObjeto= $("#checkObjeto");
	
	if(isNaN(nuevo) || nuevo <= 0){

		if($checkObjeto.is(":checked"))
			$checkObjeto.click();
			
		$checkObjeto.prop("disabled", true);

		return;
	}
	
	$checkObjeto.prop("disabled", false);
	
	$.pjax.reload({
		container : "#pjaxTributo",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"tributo" : nuevo
		}
	});
}

function cambiaObjeto(nuevo){
	
}

function controlesList(control)
{		
	if (control=="btAceptar"){
			
		var error,criterio,descr;
		error =[];
		criterio='';
		descr='';
			
		
		if ($("#ckTributo").is(":checked"))
		{
			if($("#dlTributo option:selected").text() == ""){
				error.push("Elija un tributo");
			}
			else {
				if (criterio!=="") criterio += " and ";
				criterio += " trib_id="+$("#dlTributo").val();	
				descr += " -Tributo: "+$('#dlTributo option:selected').text();	
			}
		}
		
		if ($("#checkObjeto").is(":checked"))
		{
			if ($("#txCodigoObjeto").val() == "")
			{
				error.push("Elija un objeto");
			}else {
				if (criterio!=="") criterio += " and ";
				criterio += " obj_id = '" + $("#txCodigoObjeto").val() + "'";
				descr += " -Objeto = " + $("#txNombreObjeto").val().toUpperCase();
			}	
		}
		
		if ($("#ckAnio").is(":checked"))
		{
			if($("#txAnio").val() == ""){
				error.push("Ingrese un año");
			} else {
				
				if (criterio!=="") criterio += " and ";
				criterio += " anio = " + $("#txAnio").val();
				descr += " -Año= " + $("#txAnio").val();
			}
		}
		
		if ($("#ckCuota").is(":checked"))
		{
			if($("#txCuota").val() == ""){
				error.push("Ingrese una cuota");
			} else {
				
				if (criterio!=="") criterio += " and ";
				criterio += " cuota = " + $("#txCuota").val();
				descr += " -Cuota= " + $("#txCuota").val();
			}
		}
		
		if ($("#ckExpediente").is(":checked"))
		{
			if($("#txExpediente").val() == ""){
				error.push("Ingrese un expediente");
			} else {
				
				if (criterio!=="") criterio += " and ";
				criterio += " expe = '" + $("#txExpediente").val() + "'";
				descr += " -Expediente= " + $("#txExpediente").val();
			}
		}
		
		
		if ($("#ckFecha").is(":checked"))
		{
			if ($("#txFechaDesde").val()== "" || $("#txFechaHasta").val() == "")
			{
				error.push("Complete los rangos de Fecha");
				
			} else if (ValidarRangoFechaJs($("#txFechaDesde").val(), $("#txFechaHasta").val()) == 1)	{
				error.push("Rango de Fecha mal ingresado");
			} else {
				if (criterio!=="") criterio += " and ";
				criterio += " fchmod::date between '" + $("#txFechaDesde").val() + "' And '" + $("#txFechaHasta").val() + "'";
				descr += " -Fecha desde "+$("#txFechaDesde").val()+" hasta "+$("#txFechaHasta").val();	
			}	
		}
		
		if ($("#ckUsuario").is(":checked"))
		{
			if ($("#dlUsuario").val() == "")
			{
				error.push("Elija un Usuario");
			}else {
				if (criterio!=="") criterio += " and ";
				criterio += " usrmod = " + $("#dlUsuario").val();
				descr += " -Usuario = " + $("#dlUsuario option:selected").text();
			}	
		}
		
		if (criterio=='' && error.length == 0) error.push("No se encontraron condiciones de búsqueda");
		
		if (error=='')
		{
			$("#txcriterio").val(criterio);
			$("#txdescr").val(descr);
			$("#form-cem-list").submit();
		}else {
			
			mostrarErrores(error, $("#errorlistcem"));
		}
	}
	
}

//habilita o deshabilita los controles asignados para el elemento con clase "check". los id de los controles deben estar en el atributo "data-target", en forma de string y separados por coma 
$(".check").click(function(){
	
	var targets = $(this).data("target").split(",");
	checked = $(this).is(":checked");
	
	targets.forEach(function(el){
		$(el.trim()).prop("disabled", !checked);
	});
	
	if($(this).attr("id") == "ckTributo" && $(this).is(":checked") == false){
	
		var $checkObjeto= $("#checkObjeto");
		
		if($checkObjeto.is(":checked")) $checkObjeto.click();
		
		$checkObjeto.prop("disabled", true);	
	}
});
</script>

<script>controlesList("")</script>