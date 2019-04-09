<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Alert;
use yii\jui\DatePicker;

use app\utils\db\utb;

/**
 * $selectorChecks Selector para obtener todos los checkbox que estan seleccionados y los cuales contienen el codigo del objeto
 * $selectorModal Selector del modal para poder ocultarlo
 * $selectorMensajeExito Selector del contenedor del mensaje de exito (No se le inserta ninguna mensaje, solamente se lo muestra y oculta a los 5 segundos).
 */
?>

<script type="text/javascript">
//graba las entregas
function grabar(){
	
	var codigos = [];
	var idLote = $("<?= $selectorLote?>").val();
	var fecha = $("#fechaEntrega").val();
	var resultado = $("#resultadoEntrega").val();
	var distribuidor = $("#distribuidorEntrega").val();
	
	//se obtienen los checkbox que estan seleccionados
	$("<?= $selectorChecks?>").each(function(){
		codigos.push($(this).val());
	});
	
	
	
	$.pjax.reload({
		container : '#pjaxResultado',
		replace : false,
		push : false,
		type : "POST",
		data : {
			'lote_id' : idLote,
			'codigos' : codigos,
			'fecha' : fecha,
			'resultado' : resultado,
			'distribuidor' : distribuidor
		}
	});
}

function cerrarModal(){
	$("<?= $selectorModal ?>").modal("hide");
}
</script>

<div class="modalEntregas">
	<label>Se actualizarán <span id="cantidad">#</span> elementos</label>
	<table width="100%">
		<tr>
			<td>
				<label>Fecha: </label>
				<?= DatePicker::widget(['name' => null, 'dateFormat' => 'dd/MM/yyyy', 'id' => 'fechaEntrega', 'value' => Date("d/m/Y"), 'options' => ['class' => 'form-control', 'style' => 'width:80px;']]); ?>
			</td>
			
			<td>
				<label>Resultado: </label>
				<?= Html::dropDownList(null, null, utb::getAux('intima_tresult'), ['prompt' => '', 'class' => 'form-control', 'id' => 'resultadoEntrega']); ?>
			</td>
			
			<td colspan="2">
				<label>Distribuidor: </label>
				<?= Html::dropDownList(null, null, utb::getAux('sam.sis_usuario', 'usr_id', 'nombre', 0, 'distrib=1'), ['prompt' => '', 'class' => 'form-control', 'id' => 'distribuidorEntrega']); ?>
			</td>
		</tr>
	</table>
	
	<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'grabar()']); ?>
	<?= '&nbsp;&nbsp;' ?>		
	<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'cerrarModal()']) ?>

<div style="margin-top:5px;">
	<?php
	Pjax::begin(['id' => 'pjaxResultado', 'enableReplaceState' => false, 'enablePushState' => false]);
	
	if(isset($extras['error'])){
		
		//resultado con true significa que lo cambios se realizacion correctamente
		if(count($extras['error']) == 0){
			
			?>			
			<script type="text/javascript">			
			$("<?= $selectorModal?>").modal("hide");
			
			$("<?= $selectorMensajeExito?>").removeClass("hidden");
			
			setTimeout(function(){$("<?= $selectorMensajeExito?>").fadeOut();}, 5000);
			
			</script>
			<?php
		} else{
			
			 Alert::begin([
			 	'options' => [
			 		'id' => 'alertEntregasError', 
					'class' => 'alert-danger alert-dissmissible hidden'
				]
			]);
			
			echo 'Por favor, corrija los siguientes errores: <ul>';
			
			if(is_array($extras['error'])){
				for($i = 0; $i < count($extras['error']); $i++)
					echo '<li>' . $extras['error'][$i] . '</li>';
			} else echo '<li>' . $extras['error'] . '</li>';
			
			
			
			echo '</ul>';
			
			Alert::end();
			
			?>
			<script type="text/javascript">
			$("#alertEntregasError").removeClass("hidden");
			
			setTimeout(function(){$("#alertEntregasError").fadeOut();}, 5000);
			</script>
			<?php
		}
			
	}
	
	Pjax::end();
	?>
</div>

<script>
$("<?= $selectorModal?>").on('show.bs.modal', function(e) {

    var cant = $("<?= $selectorChecks?>").length;
    
    $("#cantidad").text(cant);
});
</script>
</div>