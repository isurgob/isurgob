<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use app\utils\db\utb;
use \yii\widgets\Pjax;

?>

<?php
Pjax::begin(['id' => 'pjaxReliquidar', 'enableReplaceState' => false, 'enablePushState' => false]);

$trib_id= isset($codigoTributo) ? $codigoTributo : 0;
$porNum= isset($porNum) ? $porNum : false;
$anioDesde= isset($anioDesde) ? $anioDesde : 0;
$cuotaDesde= isset($cuotaDesde) ? $cuotaDesde : 0;
$anioHasta= isset($anioHasta) ? $anioHasta : date('Y');
$cuotaHasta= isset($cuotaHasta) ? $cuotaHasta : 999;
$reliquidarPeriodosPagos= isset($reliquidarPeriodosPagos) ? $reliquidarPeriodosPagos : false;
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
				Pjax::begin(['id' => 'pjaxEmisionTributo', 'enableReplaceState' => false, 'enablePushState' => false]);
				
				$cond = "(Tipo in (1,5) or (tipo=2 and trib_id in (select trib_id from v_resol where ttrib=1)))";
				if (Yii::$app->request->get('obj_id', '') != "")  $cond .= " and tobj = " . utb::getTObj(Yii::$app->request->get('obj_id', ''));
				
				?>
		    	<?= Html::dropDownList(null, $trib_id, utb::getAux('trib','trib_id','nombre',3,$cond), ['class' => 'form-control','id'=>'emisionCodigoTributo', 'onchange'=>'emisionCambiaTributo();']); ?>
		    	&nbsp;<?= Html::checkbox(null, $porNum, ['id'=>'emisionPorNum','label'=>'Por NUM'])?>
		    	
		    	<?php
		    	Pjax::end();
		    	?>
			</td>
		</tr>
		<tr>
			<td><label>Objeto: </label></td>
			<td>
	    		<?= Html::input('text', null, $obj_id, ['class' => 'form-control','id'=>'emisionCodigoObjeto','style'=>'width:80px','maxlength'=>'8', 'disabled' => true]); ?>
					
	        	<?= Html::input('text', null, utb::getNombObj($obj_id, false), ['class' => 'form-control','id'=>'emisionNombreObjeto','style'=>'width:340px','disabled'=>'true']); ?>
	        	&nbsp;
	        	<?=Html::input('text', null, $subcta, ['class' => 'form-control','id'=>'emisionSubCuenta','style'=>'width:40px','maxlength'=>'2', 'readonly' => false]);?>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<label>Periodo desde: </label>
				<?=Html::input('text', null, $anioDesde, ['class' => 'form-control','id'=>'emisionAnioDesde','style'=>'width:60px','maxlength'=>'4']);?>
				<?=Html::input('text', null, $cuotaDesde, ['class' => 'form-control','id'=>'emisionCuotaDesde','style'=>'width:50px','maxlength'=>'3']);?>
				<label>hasta: </label>
				<?=Html::input('text', null, $anioHasta, ['class' => 'form-control','id'=>'emisionAnioHasta','style'=>'width:60px','maxlength'=>'4']);?>
				<?=Html::input('text', null, $cuotaHasta, ['class' => 'form-control','id'=>'emisionCuotaHasta','style'=>'width:50px','maxlength'=>'3']);?>
			</td>
		</tr>
		<tr>
			<td colspan='2'><?= Html::checkbox(null, $reliquidarPeriodosPagos,['id'=>'emisionReliquidarPeriodosPagos','label'=>'Reliquidar Períodos Pagos'])?></td>
		</tr>
	</table>

	<div class="text-center" style="margin-top:5px;">
		<?php
			echo Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'emisionReliquidar();', 'style'=>'margin-right:15px']);
			echo Html::Button('Cancelar',['class' => 'btn btn-primary', 'onClick' => "$('$selectorModal').modal('hide')"]); 
		?>
	</div>
	
	<div id="emisionContenedorErrores" class="error-summary" style="display:none; margin-top:5px;"></div>
	
	<?php
	if(isset($errorReliquidar)){
		
		?>
		<script type="text/javascript">
		$(document).ready(function(){
			mostrarErrores(["<?= $errorReliquidar; ?>"], $("#emisionContenedorErrores"));
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

Pjax::begin(['id'=>'pjaxTributo', 'enableReplaceState' => false, 'enablePushState' => false]);
	
	$porNum= isset($porNum) ? $porNum : false;
	$codigoTributo= isset($codigoTributo) ? $codigoTributo : 0;
	$codigoObjeto= isset($obj_id) ? $obj_id : '';
	$tobj= 0;
	
	?>
	<script type="text/javascript">
	<?php
	if ($porNum)
	{
		$tobj = 3;
	}else {
		$tobj = utb::getTObjTrib($codigoTributo);
	}
	
	if ($tobj == 1){
		?>
		$('#txEmiLiqCuotaDesde').prop('readonly',false);
		$('#txEmiLiqCuotaHasta').prop('readonly',false);
		<?php
	}else {
		?>
		$('#txEmiLiqCuotaDesde').prop('readonly',true);
		$('#txEmiLiqCuotaHasta').prop('readonly',true);
		$('#txEmiLiqCuotaDesde').val(0);
		$('#txEmiLiqCuotaHasta').val(999);
		<?php
	}
	
	$uso_subcta = utb::getCampo('trib','trib_id='.$codigoTributo,'uso_subcta');
	if ($uso_subcta==1) echo "$('#emisionSubCuenta').prop('readonly',false);";
	if ($uso_subcta==0) echo "$('#emisionSubCuenta').prop('readonly',true);";
	
	if ($tobj != utb::getTObj($codigoObjeto))
	{
		?>
		$('#txEmiLiqObj').val('');
		$('#txEmiLiqObjNom').val('');
		<?php
	}
	
	?>
	</script>

<?php
Pjax::end();
?>
<!-- fin bloque pjax -->
<script>
function emisionCambiaTributo(){
	
	var codigoObjeto= $("#emisionCodigoObjeto").val();
	var codigoTributo= $("#emisionCodigoTributo").val();
	var porNum= $("#emisionPorNum").is(":checked");
	
	$.pjax.reload({
		container: "#pjaxTributo",
		replace: false,
		push: false,
		url: "<?= BaseUrl::toRoute(['//ctacte/ctacte/reliquidar']); ?>",
		data: {
			"o": codigoObjeto,
			"t": codigoTributo,
			"pn": porNum
		},
		method:"GET"
	});
}

function emisionReliquidar(){
	
	var datos= emisionObtenerDatos();
	datos.selectorModal= "<?= $selectorModal; ?>";
	
	$.pjax.reload({
		container: "#pjaxReliquidar",
		url: "<?= BaseUrl::toRoute(['//ctacte/ctacte/reliquidar', 'o' => $obj_id]); ?>",
		type: "POST",
		replace: false,
		push: false,
		data: datos			
	});
}

function emisionObtenerDatos(){
	
	var datos= {};
	
	datos.codigoObjeto= $("#emisionCodigoObjeto").val();
	datos.codigoTributo= $("#emisionCodigoTributo").val();
	datos.porNum= $("#emisionPorNum").is(":checked");
	datos.subCuenta= $("#emisionSubCuenta").val();
	
	datos.anioDesde= parseInt($("#emisionAnioDesde").val());
	datos.cuotaDesde= parseInt($("#emisionCuotaDesde").val());
	datos.anioHasta= parseInt($("#emisionAnioHasta").val());
	datos.cuotaHasta= parseInt($("#emisionCuotaHasta").val());
	
	if(isNaN(datos.anioDesde) || datos.anioDesde < 0) datos.anioDesde= 0;
	if(isNaN(datos.cuotaDesde) || datos.cuotaDesde < 0) datos.cuotaDesde= 0;
	if(isNaN(datos.anioHasta) || datos.anioHasta < 0) datos.anioHasta= 0;
	if(isNaN(datos.cuotaHasta) || datos.cuotaHasta < 0) datos.cuotaHasta= 0;
	
	datos.periodoDesde= datos.anioDesde * 1000 + datos.cuotaDesde;
	datos.periodoHasta= datos.anioHasta * 1000 + datos.cuotaHasta;
	
	datos.reliquidarPeriodosPagos= $("#emisionReliquidarPeriodosPagos").is(":checked");
	
	return datos;
}

$("<?= $selectorModal; ?>").on("show.bs.modal", function(){
	
	$modal= $("<?= $selectorModal; ?>");
	
	var codigoObjeto= $modal.data("codigo-objeto")
	$("#emisionCodigoObjeto").val(codigoObjeto);
	$("#emisionNombreObjeto").val($modal.data("nombre-objeto"));
	$("#emisionSubCuenta").val($modal.data("subcuenta"));
	$("#emisionAnioDesde").val($modal.data("anio"));
	$("#emisionCuotaDesde").val($modal.data("cuota"));
	
	if ( $("#emisionAnioDesde").val() > $("#emisionAnioHasta").val() )
		$("#emisionAnioHasta").val( $("#emisionAnioDesde").val() );
	else 
		$("#emisionAnioHasta").val( <?= date('Y') ?> );
	
	$.pjax.reload({
		container: "#pjaxEmisionTributo",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"obj_id": codigoObjeto
		}
	});
	
	$("#pjaxEmisionTributo").on("pjax:complete", function(){
		
		$("#emisionCodigoTributo").val($modal.data("codigo-tributo"));
		$("#pjaxEmisionTributo").off("pjax:complete");
	});
});
</script>