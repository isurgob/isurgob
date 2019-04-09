<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use app\utils\db\utb;
use \yii\widgets\Pjax;

?>

<?php
Pjax::begin(['id' => 'pjaxDDJJFaltante', 'enableReplaceState' => false, 'enablePushState' => false]);

$codigoTributo= isset($codigoTributo) ? $codigoTributo : 0;
$anioDesde= isset($anioDesde) ? $anioDesde : 0;
$cuotaDesde= isset($cuotaDesde) ? $cuotaDesde : 0;
$anioHasta= isset($anioHasta) ? $anioHasta : date('Y');
$cuotaHasta= isset($cuotaHasta) ? $cuotaHasta : 999;
$selectorModal= isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '');
$obj_id = trim(Yii::$app->request->post('codigoObjeto', $obj_id));
$subcta = trim(Yii::$app->request->post('subCuenta', $subcta));
?>
<div>
	<table>
		<tr>
			<td><label>Tributo: </label></td>
			<td>
				<?php
				Pjax::begin(['id' => 'pjaxDDJJFaltanteTributo', 'enableReplaceState' => false, 'enablePushState' => false]);
				
				$cond = "Tipo=2 and dj_tribprinc<>0";				
				?>
		    	<?= Html::dropDownList(null, $codigoTributo, utb::getAux('trib','trib_id','nombre',3,$cond), ['class' => 'form-control','id'=>'djfaltanteCodigoTributo', 'onchange'=>'djfaltanteCambiaTributo();']); ?>
		    			    	
		    	<?php
		    	Pjax::end();
		    	?>
			</td>
		</tr>
		<tr>
			<td><label>Objeto: </label></td>
			<td>
	    		<?= Html::input('text', null, $obj_id, ['class' => 'form-control','id'=>'ddjjFaltanteCodigoObjeto','style'=>'width:80px','maxlength'=>'8','readonly' => true]); ?>
					
	        	<?= Html::input('text', null, utb::getNombObj($obj_id, false), ['class' => 'form-control','id'=>'ddjjFaltanteNombreObjeto','style'=>'width:340px','disabled'=>'true']); ?>
	        	&nbsp;
	        	<?=Html::input('text', null, $subcta, ['class' => 'form-control','id'=>'ddjjFaltanteSubCuenta','style'=>'width:40px','maxlength'=>'2']);?>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<label>Periodo desde: </label>
				<?=Html::input('text', null, $anioDesde, ['class' => 'form-control','id'=>'ddjjFaltanteAnioDesde','style'=>'width:60px','maxlength'=>'4','readonly' => true]);?>
				<?=Html::input('text', null, $cuotaDesde, ['class' => 'form-control','id'=>'ddjjFaltanteCuotaDesde','style'=>'width:50px','maxlength'=>'3','readonly' => true]);?>
				<label>hasta: </label>
				<?=Html::input('text', null, $anioHasta, ['class' => 'form-control','id'=>'ddjjFaltanteAnioHasta','style'=>'width:60px','maxlength'=>'4','readonly' => true]);?>
				<?=Html::input('text', null, $cuotaHasta, ['class' => 'form-control','id'=>'ddjjFaltanteCuotaHasta','style'=>'width:50px','maxlength'=>'3','readonly' => true]);?>
			</td>
		</tr>
	</table>

	<div class="text-center" style="margin-top:5px;">
		<?php
			echo Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'ddjjFaltanteEliminar();', 'style'=>'margin-right:15px']);
			echo Html::Button('Cancelar',['class' => 'btn btn-primary', 'onClick' => "$('$selectorModal').modal('hide')"]); 
		?>
	</div>
	
	<div id="ddjjFaltanteContenedorErrores" class="error-summary" style="display:none; margin-top:5px;"></div>
	
	<?php
	if(isset($errorddjjFaltante)){
		
		?>
		<script type="text/javascript">
		$(document).ready(function(){
			mostrarErrores(["<?= $errorddjjFaltante; ?>"], $("#ddjjFaltanteContenedorErrores"));
		});
		</script>
		<?php
	}
	?>
</div>
<?php
Pjax::end();
?>

<!-- bloque pjax -->
<?php

Pjax::begin(['id'=>'pjaxTributoSelect', 'enableReplaceState' => false, 'enablePushState' => false]);
	
	$codigoTributo= isset($codigoTributo) ? $codigoTributo : 0;
	$codigoObjeto= isset($obj_id) ? $obj_id : '';
	$tobj= 0;
	
	$tobj = utb::getTObjTrib($codigoTributo);
	?>
	<script type="text/javascript">
	<?php
	$uso_subcta = utb::getCampo('trib','trib_id='.$codigoTributo,'uso_subcta');
	if ($uso_subcta==1) echo "$('#ddjjFaltanteSubCuenta').prop('readonly',false);";
	if ($uso_subcta==0) echo "$('#ddjjFaltanteSubCuenta').prop('readonly',true);";
	
	?>
	</script>

<?php
Pjax::end();
?>
<!-- fin bloque pjax -->
<script>
function djfaltanteCambiaTributo(){
	
	var codigoObjeto= $("#ddjjFaltanteCodigoObjeto").val();
	var codigoTributo= $("#djfaltanteCodigoTributo").val();
		
	$.pjax.reload({
		container: "#pjaxTributoSelect",
		replace: false,
		push: false,
		url: "<?= BaseUrl::toRoute(['//ctacte/ctacte/ddjjfaltante']); ?>",
		data: {
			"o": codigoObjeto,
			"t": codigoTributo
		},
		method:"GET"
	});
}

function ddjjFaltanteEliminar(){
	
	var datos= djFaltanteObtenerDatos();
	datos.selectorModal= "<?= $selectorModal; ?>";
	
	$.pjax.reload({
		container: "#pjaxDDJJFaltante",
		url: "<?= BaseUrl::toRoute(['//ctacte/ctacte/ddjjfaltante', 'o' => $obj_id]); ?>",
		type: "POST",
		replace: false,
		push: false,
		data: datos			
	});
}

function djFaltanteObtenerDatos(){
	
	var datos= {};
	
	datos.codigoObjeto= $("#ddjjFaltanteCodigoObjeto").val();
	datos.codigoTributo= $("#djfaltanteCodigoTributo").val();
	datos.subCuenta= $("#ddjjFaltanteSubCuenta").val();
	
	datos.anioDesde= parseInt($("#ddjjFaltanteAnioDesde").val());
	datos.cuotaDesde= parseInt($("#ddjjFaltanteCuotaDesde").val());
	datos.anioHasta= parseInt($("#ddjjFaltanteAnioHasta").val());
	datos.cuotaHasta= parseInt($("#ddjjFaltanteCuotaHasta").val());
	
	if(isNaN(datos.anioDesde) || datos.anioDesde < 0) datos.anioDesde= 0;
	if(isNaN(datos.cuotaDesde) || datos.cuotaDesde < 0) datos.cuotaDesde= 0;
	if(isNaN(datos.anioHasta) || datos.anioHasta < 0) datos.anioHasta= 0;
	if(isNaN(datos.cuotaHasta) || datos.cuotaHasta < 0) datos.cuotaHasta= 0;
	
	datos.periodoDesde= datos.anioDesde * 1000 + datos.cuotaDesde;
	datos.periodoHasta= datos.anioHasta * 1000 + datos.cuotaHasta;
		
	return datos;
}


$("<?= $selectorModal; ?>").on("show.bs.modal", function(){
	
	$modal= $("<?= $selectorModal; ?>");
	
	var codigoObjeto= $modal.data("codigo-objeto")
	$("#ddjjFaltanteCodigoObjeto").val(codigoObjeto);
	$("#ddjjFaltanteNombreObjeto").val($modal.data("nombre-objeto"));
	$("#ddjjFaltanteSubCuenta").val($modal.data("subcuenta"));
	$("#ddjjFaltanteAnioDesde").val($modal.data("anio"));
	$("#ddjjFaltanteCuotaDesde").val($modal.data("cuota"));
	$("#ddjjFaltanteAnioHasta").val($modal.data("anio"));
	$("#ddjjFaltanteCuotaHasta").val($modal.data("cuota"));
	
	$.pjax.reload({
		container: "#pjaxDDJJFaltanteTributo",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"obj_id": codigoObjeto
		}
	});
	
	$("#pjaxDDJJFaltanteTributo").on("pjax:complete", function(){
		
		$("#djfaltanteCodigoTributo").val($modal.data("codigo-tributo"));
		$("#pjaxDDJJFaltanteTributo").off("pjax:complete");
	});
});
</script>