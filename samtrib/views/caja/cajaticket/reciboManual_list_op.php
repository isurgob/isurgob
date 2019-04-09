<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

/* 	
 * Se validan todos los campos que se encuentren activos, y si se produce algún error no se realiza el submit y se muestra el error en cuestión.
 * Si se realiza el submit, el controlador tomará los valores de criterio y cond y generará la consulta a la BD y dibujará la forma list_inm
 * 
 **/

$title = 'Listado Recibo Manual';
$this->params['breadcrumbs'][] = 'Opciones';
?>

<style>

.form-panel {
	
	margin-right: 0px;
}

</style>

<div class="listadoReciboManual-view" style='width:65%;padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
	<div>	
		<table width="100%">
			<tr>
				<td><h1><?= Html::encode($title) ?></h1></td>
				<td align="right"><?= Html::a('Volver', ['viewrecibomanual'],['class' => 'btn btn-primary', 'onClick' => 'validaFiltros();']) ?></td>
			</tr>
							
		</table>
	</div>
	
	<?php
	
		$form = ActiveForm::begin([
			'id' => 'form-listadoReciboManual-list',
			'action' => ['recibomanual_list','r'=>0]
		
		]);
		
		echo "<input type='text' name='txCriterio' id='listadoReciboManual-txCriterio' style='display:none'>";
		echo "<input type='text' name='txDescripcion' id='listadoReciboManual-txDescripcion' style='display:none'>";
		
	?>
			
	<div class="form-panel">
		<table>
			<tr>
				<td width="100px"><label>Filtrar por:</label></td>
				<td width="20px"></td>
				<td><?= Html::radio('rbFiltro', true, ['id' => 'listadoReciboManual-rbFiltroRecibo', 'label' => 'Recibo','value'=>'R']); ?></td>
				<td width="20px"></td>
				<td><?= Html::radio('rbFiltro', false, ['id' => 'listadoReciboManual-rbFiltroComprobante', 'label' => 'Comprobante','value'=>'C']); ?></td>
				</td>
			</tr>
		</table>	
	</div>
	
	<div class="form" style="margin-bottom:8px;padding-bottom:8px">
		<table border="0">
			<tr>
				<td width="140px"><?= Html::checkbox('ckNroRef',false, ['id' => 'listadoReciboManual-ckNroRef','label' => 'Nro. de Referencia', 'onchange' => 'filtrosCaja()']); ?></td>
				<td width="45px"><label>Desde:</label></td><td><?= Html::input('text', 'txNroRefDesde', null, ['id' => 'listadoReciboManual-txNroRefDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px']); ?></td>
				<td width="5px"></<td>
				<td width="45px"><label>Hasta:</label></td><td><?= Html::input('text', 'txNroRefHasta', null, ['id' => 'listadoReciboManual-txNroRefHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px']); ?></td>
			</tr>
			
			<tr>
				<td><?= Html::checkbox('ckNroRec',false, ['id' => 'listadoReciboManual-ckNroRec','label' => 'Nro. de Recibo','onchange' => 'filtrosCaja()']); ?></td>
				<td><label>Desde:</label></td><td><?= Html::input('text', 'txNroRecDesde', null, ['id' => 'listadoReciboManual-txNroRecDesde', 'style'=>'width:80px', 'class' => 'form-control']); ?></td><td></<td>
				<td><label>Hasta:</label></td><td><?= Html::input('text', 'txNroRecHasta', null, ['id' => 'listadoReciboManual-txNroRecHasta', 'style'=>'width:80px', 'class' => 'form-control']); ?></td><td></<td>
			</tr>
			
			<tr>
				<td width="100px"><?= Html::checkbox('ckFechaRec',false, ['id' => 'listadoReciboManual-ckFechaRec','label' => 'Fecha Recibo','onchange' => 'filtrosCaja()']); ?></td>
				<td width="45px"><label>Desde:</label></td>
				<td width="80px"><?= 
									DatePicker::widget(
										[
											'id' => 'listadoReciboManual-txFechaRecDesde',
											'name' => 'txFechaRecDesde',
											'dateFormat' => 'dd/MM/yyyy',
											'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
										]
									);
								?></td>
				<td></td>
				<td width="45px"><label>Hasta:</label></td>
				<td width="80px"><?= 
									DatePicker::widget(
										[
											'id' => 'listadoReciboManual-txFechaRecHasta',
											'name' => 'txFechaRecHasta',
											'dateFormat' => 'dd/MM/yyyy',
											'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
										]
									);
								?>
				</td>
			</tr>
			
			<tr>
				<td width="100px"><?= Html::checkbox('ckFechaIng',false, ['id' => 'listadoReciboManual-ckFechaIng','label' => 'Fecha Ingreso','onchange' => 'filtrosCaja()']); ?></td>
				<td width="45px"><label>Desde:</label></td>
				<td width="80px"><?= 
									DatePicker::widget(
										[
											'id' => 'listadoReciboManual-txFechaIngDesde',
											'name' => 'txFechaIngDesde',
											'dateFormat' => 'dd/MM/yyyy',
											'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
										]
									);
								?></td>
				<td></td>
				<td width="45px"><label>Hasta:</label></td>
				<td width="80px"><?= 
									DatePicker::widget(
										[
											'id' => 'listadoReciboManual-txFechaIngHasta',
											'name' => 'txFechaIngHasta',
											'dateFormat' => 'dd/MM/yyyy',
											'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
										]
									);
								?>
				</td>
			</tr>
			
			<tr>
				<td><?= Html::checkbox('ckActa',false, ['id' => 'listadoReciboManual-ckActa','label' => 'Acta','onchange' => 'filtrosCaja()']); ?></td>
				<td><label>Desde:</label></td><td><?= Html::input('text', 'txActaDesde', null, ['id' => 'listadoReciboManual-txActaDesde', 'style'=>'width:80px', 'class' => 'form-control']); ?></td><td></<td>
				<td><label>Hasta:</label></td><td><?= Html::input('text', 'txActaHasta', null, ['id' => 'listadoReciboManual-txActaHasta', 'style'=>'width:80px', 'class' => 'form-control']); ?></td><td></<td>
			</tr>
		</table>
		
		<table>		
			<tr>
				<td width="140px"><?= Html::checkbox('ckItem',false, ['id' => 'listadoReciboManual-ckItem','label' => 'Item','onchange' => 'filtrosCaja()']); ?></td>
				<td width="258px"><?= Html::dropDownList('dlItem', null, utb::getAux('item','item_id','nombre',0,"trib_id = 12"), ['id'=>'listadoReciboManual-dlItem', 'style'=>'width:258px', 'class' => 'form-control']); ?></td>
			</tr>
			
			<tr>
				<td><?= Html::checkbox('ckArea',false, ['id' => 'listadoReciboManual-ckArea','label' => 'Area','onchange' => 'filtrosCaja()']); ?></td>
				<td><?= Html::dropDownList('dlArea', null, utb::getAux('sam.muni_oficina','ofi_id','nombre'), ['id'=>'listadoReciboManual-dlArea', 'style'=>'width:258px', 'class' => 'form-control']); ?></td>
			</tr>
		</table>
		
		<table>
			<tr>
				<td width="140px"><?= Html::checkbox('ckEstado',false, ['id' => 'listadoReciboManual-ckEstado','label' => 'Estado','onchange' => 'filtrosCaja()']); ?></td>
				<td><?= Html::radio('rbEstado', false, ['id' => 'listadoReciboManual-rbPago', 'label' => 'Pago','onchange' => 'filtrosCaja()']); ?></td>
				<td width="20px"></td>
				<td><?= Html::radio('rbEstado', false, ['id' => 'listadoReciboManual-rbPendiente', 'label' => 'Pendiente','onchange' => 'filtrosCaja()']); ?></td>
				<td width="20px"></td>
				<td><?= Html::radio('rbEstado', false, ['id' => 'listadoReciboManual-rbBaja', 'label' => 'Baja','onchange' => 'filtrosCaja()']); ?></td>
				</td>
			</tr>
		</table>
		
		<table>
			<tr>
				<td width="140px"><?= Html::checkbox('ckTicket',false, ['id' => 'listadoReciboManual-ckTicket', 'label' => 'Ticket','onchange' => 'filtrosCaja()']); ?></td>
				<td width="45px"><?= Html::input('text', 'txTicket', null, ['id' => 'listadoReciboManual-txTicket','style'=>'width:80px', 'maxlength'=>'50', 'class' => 'form-control', 'onkeypress'=>'return justNumbers(event)']); ?></td>
			</tr>	
		</table>
	
	</div>

	<table>
		<tr>
			<td><?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onClick' => 'validaFiltros();']) ?></td>
		</tr>
	</table>

<?php

	ActiveForm::end();
	
?>
		
	<div id="rm_listado_errorSummary" class="error-summary" style="display:none;margin-top:8px">
	
		<ul>
		</ul>
		
	</div>

</div>
	
<script type="text/javascript">
function filtrosCaja()
{
	if ($("#listadoReciboManual-ckNroRef").prop("checked"))
	{
		$("#listadoReciboManual-txNroRefDesde").removeAttr('disabled');
		$("#listadoReciboManual-txNroRefHasta").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-txNroRefDesde").attr('disabled', true);
		$("#listadoReciboManual-txNroRefHasta").attr('disabled',true);
	}
	
	if ($("#listadoReciboManual-ckNroRec").prop("checked"))
	{
		$("#listadoReciboManual-txNroRecDesde").removeAttr('disabled');
		$("#listadoReciboManual-txNroRecHasta").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-txNroRecDesde").attr('disabled', true);
		$("#listadoReciboManual-txNroRecHasta").attr('disabled',true);
	}
	
	if ($("#listadoReciboManual-ckFechaRec").prop("checked"))
	{
		$("#listadoReciboManual-txFechaRecDesde").removeAttr('disabled');
		$("#listadoReciboManual-txFechaRecHasta").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-txFechaRecDesde").attr('disabled', true);
		$("#listadoReciboManual-txFechaRecHasta").attr('disabled',true);
	}
	
	if ($("#listadoReciboManual-ckFechaIng").prop("checked"))
	{
		$("#listadoReciboManual-txFechaIngDesde").removeAttr('disabled');
		$("#listadoReciboManual-txFechaIngHasta").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-txFechaIngDesde").attr('disabled', true);
		$("#listadoReciboManual-txFechaIngHasta").attr('disabled',true);
	}
	
	if ($("#listadoReciboManual-ckNroRec").prop("checked"))
	{
		$("#listadoReciboManual-txNroRecDesde").removeAttr('disabled');
		$("#listadoReciboManual-txNroRecHasta").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-txNroRecDesde").attr('disabled', true);
		$("#listadoReciboManual-txNroRecHasta").attr('disabled',true);
	}
	
	if ($("#listadoReciboManual-ckItem").prop("checked"))
	{
		$("#listadoReciboManual-dlItem").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-dlItem").attr('disabled', true);
	}
		
	if ($("#listadoReciboManual-ckArea").prop("checked"))
	{
		$("#listadoReciboManual-dlArea").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-dlArea").attr('disabled', true);
	}
	
	if ($("#listadoReciboManual-ckTicket").prop("checked"))
	{
		$("#listadoReciboManual-txTicket").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-txTicket").attr('disabled', true);
	}
	
	if ($("#listadoReciboManual-ckActa").prop("checked"))
	{
		$("#listadoReciboManual-txActaDesde").removeAttr('disabled');
		$("#listadoReciboManual-txActaHasta").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-txActaDesde").attr('disabled', true);
		$("#listadoReciboManual-txActaHasta").attr('disabled',true);
	}	
	
	if ($("#listadoReciboManual-ckEstado").prop("checked"))
	{
		$("#listadoReciboManual-rbPago").removeAttr('disabled');
		$("#listadoReciboManual-rbPendiente").removeAttr('disabled');
		$("#listadoReciboManual-rbBaja").removeAttr('disabled');
	} else {
		$("#listadoReciboManual-rbPago").attr('disabled', true);
		$("#listadoReciboManual-rbPendiente").attr('disabled', true);
		$("#listadoReciboManual-rbBaja").attr('disabled', true);
	}

}



function validaFiltros()
{
	
	var error = new Array(),  /* Esta variable almacenará los errores */
		criterio = '',
		descr = '',
		recibo = $("#listadoReciboManual-rbFiltroRecibo").is(":checked");
	
	if ($("#listadoReciboManual-ckNroRef").prop("checked"))
	{
		if ($("#listadoReciboManual-txNroRefDesde").val() == "" || $("#listadoReciboManual-txNroRefHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Nro. de Referencia." );
		} else if (parseInt($("#listadoReciboManual-txNroRefDesde").val()) > parseInt($("#listadoReciboManual-txNroRefHasta").val())){
			
			error.push( "Rango de Nro. de Referencia mal ingresado." );
		} else {	
			if (criterio !== "") criterio += " and ";
			
			if ( recibo )
				criterio += "ctacte_id";
			else
				criterio += "r.ctacte_id";
				
			criterio += " BETWEEN "+$("#listadoReciboManual-txNroRefDesde").val()+" and "+$("#listadoReciboManual-txNroRefHasta").val();	
			descr += " - Código de Nro. de Referencia: Desde "+$("#listadoReciboManual-txNroRefDesde").val()+" - Hasta "+$("#listadoReciboManual-txNroRefHasta").val();
		}

	} 
	
	if ($("#listadoReciboManual-ckNroRec").prop("checked"))
	{
		if ($("#listadoReciboManual-txNroRecDesde").val() == "" || $("#listadoReciboManual-txNroRecHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Nro. de Recibo." );
		} else if (parseInt($("#listadoReciboManual-txNroRecDesde").val()) > parseInt($("#listadoReciboManual-txNroRecHasta").val())){
			error += $("#listadoReciboManual-txNroRecDesde").val() + $("#listadoReciboManual-txNroRecHasta").val();
			error.push( "Rango de Nro. de Recibo mal ingresado." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += " recibo >='"+$("#listadoReciboManual-txNroRecDesde").val()+"' and recibo <= '"+$("#listadoReciboManual-txNroRecHasta").val()+"'";	
			descr += " - Código de Nro. de Recibo: Desde "+$("#listadoReciboManual-txNroRecDesde").val()+" - Hasta "+$("#listadoReciboManual-txNroRecHasta").val();
		}

	}

	if ($("#listadoReciboManual-ckFechaRec").prop("checked"))
	{
		if ( $("#listadoReciboManual-txFechaRecDesde").val() == '' || $("#listadoReciboManual-txFechaRecHasta").val() == '' )
		{
			error.push( "Ingrese Rango de Fecha de Recibo." );
		} else
		{
			if (ValidarRangoFechaJs($("#listadoReciboManual-txFechaRecDesde").val(), $("#listadoReciboManual-txFechaRecHasta").val()) == 1)
			{
				error.push( "Rango de Fecha de Recibo mal ingresado" );
			} else if ($("#listadoReciboManual-txFechaRecDesde").val()== "" || $("#listadoReciboManual-txFechaRecHasta").val() == "")
			{
				error.push( "Complete los rangos de Fecha de Recibo" );
			} else {
				if (criterio!=="") criterio += " and ";
				criterio += " fecha::date BETWEEN '"+$("#listadoReciboManual-txFechaRecDesde").val()+"' AND '"+$("#listadoReciboManual-txFechaRecHasta").val()+"'";
				descr += " -Fecha de Recibo desde "+$("#listadoReciboManual-txFechaRecDesde").val()+" hasta "+$("#listadoReciboManual-txFechaRecHasta").val();	
			}	
		}
			
	}
	
	if ($("#listadoReciboManual-ckFechaIng").prop("checked"))
	{
		if ( $("#listadoReciboManual-txFechaIngDesde").val() == '' || $("#listadoReciboManual-txFechaIngHasta").val() == '' )
		{
			error.push( "Ingrese Rango de Fecha de Ingreso." );
		} else
		{
			if (ValidarRangoFechaJs($("#listadoReciboManual-txFechaIngDesde").val(), $("#listadoReciboManual-txFechaIngHasta").val()) == 1)
			{
				error.push( "Rango de Fecha de Ingreso mal ingresado" );
			}else if ($("#listadoReciboManual-txFechaIngDesde").val()== "" || $("#listadoReciboManual-txFechaIngHasta").val() == "")
			{
				error.push( "Complete los rangos de Fecha de Ingreso" );
			}else {
				if (criterio!=="") criterio += " and ";
				criterio += " r.ctacte_id in (SELECT ctacte_id FROM caja_ticket WHERE Fecha::date between '"+$("#listadoReciboManual-txFechaIngDesde").val()+"' AND '"+$("#listadoReciboManual-txFechaIngHasta").val()+"')"; 
				descr += " -Fecha de Ingreso desde "+$("#listadoReciboManual-txFechaIngDesde").val()+" hasta "+$("#listadoReciboManual-txFechaIngHasta").val();	
			}	
		}
	}

	if ($("#listadoReciboManual-ckActa").prop("checked"))
	{
		if ($("#listadoReciboManual-txActaDesde").val() == "" || $("#listadoReciboManual-txActaHasta").val() == "")
		{
			error.push( "Debe ingresar el rango de actas." );
		} else if ($("#listadoReciboManual-txActaDesde").val() > $("#listadoReciboManual-txActaHasta").val()){
			
			error.push( "Acta mal ingresada." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += " acta >='"+$("#listadoReciboManual-txActaDesde").val()+"' and acta <= '"+$("#listadoReciboManual-txActaHasta").val()+"'";	
			descr += " - Acta: Desde "+$("#listadoReciboManual-txActaDesde").val()+" - Hasta "+$("#listadoReciboManual-txActaHasta").val();
		}

	}
	
	if ($("#listadoReciboManual-ckItem").prop("checked"))
	{
		if ($("#listadoReciboManual-dlItem").val() == "")
		{
			error.push( "Ingrese un Item válido." );		
			
		} else {
				
				if (criterio!=="") criterio += " and ";
				criterio += " item_id ='"+$("#listadoReciboManual-dlItem").val()+"'";	
				descr += " - El Item es: " + ($("#listadoReciboManual-dlItem option[value="+$("#listadoReciboManual-dlItem").val()+"]").text());
		}
	}
	
	if ($("#listadoReciboManual-ckArea").prop("checked"))
	{
		if ($("#listadoReciboManual-dlArea").val() == "")
		{
			error.push( "Ingrese una Área válida." );		
			
		} else {
				
				if (criterio!=="") criterio += " and ";
				criterio += " area ='"+$("#listadoReciboManual-dlArea").val()+"'";	
				descr += " - El Área es: " + ($("#listadoReciboManual-dlArea option[value="+$("#listadoReciboManual-dlArea").val()+"]").text());
		}
	}
		
	if ($("#listadoReciboManual-ckEstado").prop("checked"))
	{
		if ($("#listadoReciboManual-rbPago").is(":checked"))
		{
				
			if (criterio!=="") criterio += " and ";
			criterio += " r.ctacte_id in (SELECT ctacte_id FROM ctacte where est = 'P')";	
			descr += " - El Estado es: Pago";
			
		} else if ($("#listadoReciboManual-rbPendiente").is(":checked"))
		{
			if (criterio!=="") criterio += " and ";
			criterio += " r.ctacte_id in (SELECT ctacte_id FROM ctacte where est = 'D')";	
			descr += " - El Estado es: Pendiente";
		} else if ($("#listadoReciboManual-rbBaja").is(":checked"))
		{
			if (criterio!=="") criterio += " and ";
			criterio += " r.ctacte_id in (SELECT ctacte_id FROM ctacte where est = 'B')";	
			descr += " - El Estado es: Baja";
		} else {
			error.push( "Seleccione un Estado." );
		}
	}
	
	if ($("#listadoReciboManual-ckTicket").prop("checked"))
	{
		if ($("#listadoReciboManual-txTicket").val() == "")
		{
			error.push( "Ingrese un Ticket válido." );		
			
		} else {
				
				if (criterio!=="") criterio += " and ";
				criterio += " ticket = "+$("#listadoReciboManual-txTicket").val();	
				descr += " - El Ticket es: " + $("#listadoReciboManual-txTicket").val();
		}
	}
	
	if ( error.length == 0 && criterio == '' )
			error.push( "Ingrese un criterio de búsqueda." );
		
	if ( error.length == 0 )
	{
		$("#listadoReciboManual-txCriterio").val(criterio);
		$("#listadoReciboManual-txDescripcion").val(descr);
		$("#form-listadoReciboManual-list").submit();
	} else
		mostrarErrores( error, "#rm_listado_errorSummary" );
}

$(document).ready(function() {
	
	filtrosCaja();
	
});
</script>