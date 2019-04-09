<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\models\objeto\Domi;
use yii\jui\AutoComplete;

use app\utils\db\utb;

$usarCodigoCalle= utb::samConfig()['usar_codcalle_loc'];

if ($modelodomi == null) $modelodomi = new Domi();
	
if ($modelodomi->loc_id == "") $modelodomi->loc_id = utb::getCodLocalidad(); 
if ($modelodomi->prov_id == "") $modelodomi->prov_id = utb::getCampo("domi_localidad","loc_id=".$modelodomi->loc_id,"prov_id");
if ($modelodomi->pais_id == "") $modelodomi->pais_id = utb::getCampo("domi_provincia","prov_id=".$modelodomi->prov_id,"pais_id");
if ($modelodomi->cp == "") $modelodomi->cp = utb::getCampo("domi_localidad","loc_id=".$modelodomi->loc_id,"cp");  
?>

<table border='0'>	
<tr>
	<td><label class="control-label">Pais:</label></td>
	<td>
		<?= Html::dropDownList('pais'.$tor, $modelodomi->pais_id, utb::getAux('domi_pais','pais_id'),['disabled' => ($tor == 'INM' or $tor == 'COM' ? true : false), 'class' => 'form-control','id'=>'pais'.$tor,'style'=>'width:200px','prompt'=>'Seleccionar...','onchange' => '$.pjax.reload({container:"#EventosForm'.$tor.'",data:{combo'.$tor.':"pais'.$tor.'",valor'.$tor.':this.value},method:"POST"})']); ?>
	</td>
	<td width='15px'></td>
	<td><label class="control-label">Provincia:</label></td>
	<td>
		<?= Html::dropDownList('provincia'.$tor, $modelodomi->prov_id, utb::getAux('domi_provincia','prov_id'),['disabled' => ($tor == 'INM' or $tor == 'COM' ? true : false),'class' => 'form-control','id'=>'provincia'.$tor,'style'=>'width:200px','prompt'=>'Seleccionar...','onchange' => '$.pjax.reload({container:"#EventosForm'.$tor.'",data:{combo'.$tor.':"provincia'.$tor.'",valor'.$tor.':this.value},method:"POST"})']); ?>
	</td>
</tr>
<tr>
	<td><label class="control-label">Localidad:</label></td>
	<td>
		<?= Html::dropDownList('localidad'.$tor, $modelodomi->loc_id, utb::getAux('domi_localidad','loc_id'),['disabled' => ($tor == 'INM' or $tor == 'COM' ? true : false),'class' => 'form-control','id'=>'localidad'.$tor,'style'=>'width:200px','prompt'=>'Seleccionar...','onchange' => 'cambiaLocalidad' . $tor .'($(this).val());']); ?>
	</td>
	<td width='15px'></td>
	<td><label class="control-label">Barrio:</label></td>
	<td>
		<?= Html::dropDownList('barrio'.$tor, $modelodomi->barr_id, utb::getAux('domi_barrio','barr_id','nombre',0,'1=1'),['class' => 'form-control solo-lectura','id'=>'barrio'.$tor,'prompt'=>'Seleccionar...','style'=>'width:200px', 'tabindex' => -1, 'onchange' => '$.pjax.reload({container:"#EventosForm'.$tor.'",data:{combo'.$tor.':"barrio'.$tor.'",valor'.$tor.':this.value},method:"POST"})']); ?>
	</td>
</tr>
<tr>
	<td><label class="control-label">Calle:</label></td>
	<td colspan='4'>
		<?= Html::input('text', 'calle_id'.$tor, $modelodomi->calle_id, ['class' => 'form-control','id'=>'calle_id'.$tor, 'onkeypress'=>'return justNumbers(event);', 'maxlength' => 3, 'style'=>'width:60px','onchange' => '$.pjax.reload({container:"#EventosForm'.$tor.'",data:{text'.$tor.':"calle",calle_id'.$tor.':$("#calle_id'.$tor.'").val(),loc'.$tor.':$("#localidad'.$tor.'").val()},method:"POST"})']); ?>
		<?= Html::input('text', 'calle_nom'.$tor, $modelodomi->nomcalle, ['class' => 'form-control','id'=>'calle_nom'.$tor,'style'=>'width:400px;']); ?>
	</td>
</tr>
<tr>
	<td><label class="control-label">Puerta:</label></td>
	<td colspan='4'>
		<?= Html::input('text', 'puerta'.$tor, $modelodomi->puerta, ['class' => 'form-control','id'=>'puerta'.$tor,'maxlength' => 4, 'onkeypress'=>'return justNumbers(event);', 'style'=>'width:60px']); ?>
		
		<label style='margin-left:15px' class="control-label">Cod.Postal:</label>
		<?= Html::input('text', 'cp'.$tor, $modelodomi->cp, ['class' => 'form-control','id'=>'cp'.$tor,'style'=>'width:60px', 'maxlength' => 8]); ?>
		
		<label style='margin-left:15px' class="control-label">Piso:</label>
		<?= Html::input('text', 'piso'.$tor, $modelodomi->piso, ['class' => 'form-control','id'=>'piso'.$tor,'style'=>'width:60px', 'maxlength' => 5]); ?>
		
		<label style='margin-left:15px' class="control-label">Dpto./Casa:</label>
		<?= Html::input('text', 'dpto'.$tor, $modelodomi->dpto, ['class' => 'form-control','id'=>'dpto'.$tor,'style'=>'width:60px','maxlength' => 5]); ?>
	</td>
</tr>
<tr>
	<td><label class="control-label">Detalle:</label></td>
	<td colspan='4'>
		<?= Html::input('text', 'detalle'.$tor, $modelodomi->det, ['class' => 'form-control','id'=>'detalle'.$tor,'style'=>'width:475px','maxlength' => 50]); ?>
	</td>
</tr>
<tr>
	<td colspan='5' style='padding-top:8px'>
		<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'btAceptarDomi("'.$tor.'")']) ?>
		&nbsp;
		<?= Html::Button('Cancelar',['class' => 'btn btn-primary', 'onClick' => 'btCancelar("'.$tor.'")']) ?>
	</td>
</tr>
<tr>
	<td colspan='5' style='padding-top:8px'><div id="error<?=$tor?>" style="display:none;" class="alert alert-danger alert-dismissable"></div></td>
</tr>
</table>

<?php

// comienza bloque donde actualizan controles seg�n tributo seleccionado
Pjax::begin(['id' => 'EventosForm'.$tor]);
	$cond = '';
	
	if (isset($_POST['combo'.$tor])) 
	{	
		echo '<script>$("#calle_id'.$tor.'").val("");</script>';
		echo '<script>$("#calle_nom'.$tor.'").val("");</script>';
		
		if ($_POST['combo'.$tor]=='pais'.$tor)
		{
			$cond = ' pais_id='.($_POST['valor'.$tor] == '' ? 0 : $_POST['valor'.$tor]);
			$campo = 'prov_id';
			$tabla = 'domi_provincia';
			$subselect = 'provincia'.$tor;
		}
		if ($_POST['combo'.$tor]=='provincia'.$tor)
		{
			$cond = ' prov_id='.($_POST['valor'.$tor] == '' ? 0 : $_POST['valor'.$tor]);
			$campo = 'loc_id';
			$tabla = 'domi_localidad';
			$subselect = 'localidad'.$tor;
		}
		if ($_POST['combo'.$tor]=='localidad'.$tor)
		{
			echo '<script>$("#cp'.$tor.'").val('.utb::getCampo("domi_localidad","loc_id=".($_POST['valor'.$tor] == '' ? 0 : $_POST['valor'.$tor]),"cp").')</script>';
			echo '<script>$.pjax.reload({container:"#FormBuscarCalle'.$tor.'",data:{loc_id'.$tor.':'.($_POST['valor'.$tor] == '' ? 0 : $_POST['valor'.$tor]).'},method:"POST"})</script>';
			
		}
		
		if ($_POST['combo'.$tor]!=='localidad'.$tor and $_POST['combo'.$tor]!=='barrio'.$tor)
		{
			$items = utb::getAux($tabla,$campo,'nombre',0,$cond);
								
			echo '<script>$("#'.$subselect.'").empty().append("<option value=0 >Seleccione</option>");</script>';
				
			foreach ($items as $key => $value){
     			$options="<option value='".$key."'>".$value."</option>";
     			echo '<script>$("#'.$subselect.'").append("'.$options.'");</script>';
			}
			
		}
				
	}
	
	if (isset($_POST['text'.$tor]))
	{
		if ($_POST['text'.$tor]=='calle' and $_POST['calle_id'.$tor] != "" and $_POST['loc'.$tor] !== "")
		{
			$calle_nom = utb::getCampo('domi_calle','calle_id='.$_POST['calle_id'.$tor]);
			echo '<script>$("#calle_nom'.$tor.'").val("'.$calle_nom.'")</script>';
		}
	}
				
Pjax::end();// fin bloque combo tributo

?>

<script>
function cambiaLocalidad<?= $tor ?>(nuevaLocalidad){
	
	if(<?= utb::getCodLocalidad() ?> == nuevaLocalidad && <?= $usarCodigoCalle === false ? 'false' : 'true'; ?>){
		
		
		$("#barrio<?= $tor; ?>").removeClass("solo-lectura");
		$("#barrio<?= $tor; ?>").attr("tabindex", -1);
		$("#barrio<?= $tor; ?>").val("");
		$("#calle_id<?= $tor ?>").css("visibility", "visible");		
		
	} else {
		
		$("#barrio<?= $tor; ?>").addClass("solo-lectura");
		$("#barrio<?= $tor; ?>").removeAttr("tabindex");
		$("#barrio<?= $tor; ?>").val(-1);
		$("#calle_id<?= $tor ?>").css("visibility", "hidden");		
	}
	
		
//$.pjax.reload({container:"#EventosForm'.$tor.'",data:{combo'.$tor.':"localidad'.$tor.'",valor'.$tor.':this.value},method:"POST"})
}


function btAceptarDomi(torigen)
{
	var error;
	error='';
				
	if ($("#pais"+torigen).val()==0) error += "Seleccione un Pais<br>";
	if ($("#provincia"+torigen).val()==0) error += "Seleccione una Provincia<br>";
	if ($("#localidad"+torigen).val()==0) error += "Seleccione una Localidad<br>";
	if ($("#calle_nom"+torigen).val()=='') error += "La calle es obligatoria<br>";
	if ($("#cp"+torigen).val()=='') error += "El Codigo Postal es obligatorio<br>";
	
	if (error=='')
	{
		$("#error"+torigen).css("display", "none");
		$.pjax.reload({ container:"#CargarModeloDomi",
						data:{
							prov_id:$("#provincia"+torigen).val(),
							loc_id:$("#localidad"+torigen).val(),
 							cp:$("#cp"+torigen).val(),
 							barr_id:$("#barrio"+torigen).val(),
 							calle_id:$("#calle_id"+torigen).val(),
 							nomcalle:$("#calle_nom"+torigen).val(),
 							puerta:$("#puerta"+torigen).val(),
 							det:$("#detalle"+torigen).val(),
 							piso:$("#piso"+torigen).val(),
 							dpto:$("#dpto"+torigen).val(),
 							tor:torigen
							},
						method:"POST"
					 });
		
		if (torigen=="OBJ") $("#BuscaDomiP, .window").modal("toggle");
		if (torigen=="PLE")	$("#BuscaDomiL, .window").modal("toggle");
		if (torigen=="PRE")	$("#BuscaDomiR, .window").modal("toggle");
		if (torigen=="INM") $("#BuscaDomiI, .window").modal("toggle");
		if (torigen=="COM") $("#BuscaDomiC, .window").modal("toggle");
		if (torigen=="GENERAL") $("#BuscaDomiG, .window").modal("toggle");
		if (torigen=="PLA") $("#BuscaDomiPLA, .window").modal("toggle");
		
	}else {
		$("#error"+torigen).html(error);
		$("#error"+torigen).css("display", "block");
	}
}

function btCancelar(torigen)
{
	if (torigen=="OBJ") $("#BuscaDomiP, .window").modal("toggle");
	if (torigen=="PLE")	$("#BuscaDomiL, .window").modal("toggle");
	if (torigen=="PRE")	$("#BuscaDomiR, .window").modal("toggle");
	if (torigen=="INM") $("#BuscaDomiI, .window").modal("toggle");
	if (torigen=="GENERAL") $("#BuscaDomiG, .window").modal("toggle");
	if (torigen=="PLA") $("#BuscaDomiPLA, .window").modal("toggle");	
	if(torigen=="COM") $("#BuscaDomiC").modal("hide");
}

$('#btAceptarBuscaCalle<?=$tor?>').click(function () {
	$("#BuscaCalle<?=$tor?>").css("display", "none");
});

$('#GrillaAux<?=$tor?>').dblclick(function () {
	$("#BuscaCalle<?=$tor?>").css("display", "none");
});

$(document).ready(function(){
	cambiaLocalidad<?= $tor; ?>($("#localidad<?= $tor; ?>").val());

	<?php
	if($usarCodigoCalle){
	?>
	
		//convierte el input en un elemento de autocompletado
		$("#calle_nom<?= $tor; ?>").autocomplete({
			source: "<?= BaseUrl::toRoute('//objeto/objeto/sugerenciacalle'); ?>",
			select: function(event, ui){

				//console.log();
				var nombre= ui.item.value;//$("#calle_nom<?= $tor ?>").val();

				$.get("<?= BaseUrl::toRoute('//objeto/objeto/codigocalle');?>&nombre=" + nombre)
				.success(function(data){
					$("#calle_id<?= $tor; ?>").val(data);
				});
			}
		});

		//habilita que se vea el listado de autocompletado en el modal
		$(".ui-autocomplete").css("z-index", "5000");

	<?php
	}
	?>
});
</script>