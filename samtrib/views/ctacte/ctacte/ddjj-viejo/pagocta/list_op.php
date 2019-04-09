<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

/* 	
 * Se validan todos los campos que se encuentren activos, y si se produce algún error no se realiza el submit y se muestra el error en cuestión.
 * Si se realiza el submit, el controlador tomará los valores de criterio y cond y generará la consulta a la BD y dibujará la forma list_res
 * 
 **/

$title = 'Opciones';
$this->params['breadcrumbs'][] = ['label' => 'Pago a Cuenta', 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Listado';
$this->params['breadcrumbs'][] = $title;
?>
<div class="listadoPagocta-view" style="width:75%">	
	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'><?= Html::a('Volver', ['view'],['class' => 'btn btn-primary']) ?></td>
		</tr>
	</table>

	<?php
	
	$form = ActiveForm::begin([
		'id' => 'form-listadoPagocta-list',
		'action' => ['listado']
	
	]);
	
	echo "<input type='text' name='txCriterio' id='pagocta_txCriterio' style='display:none'>";
	echo "<input type='text' name='txDescripcion' id='pagocta_txDescripcion' style='display:none'>";
	
	Pjax::begin(['id'=>'NUMResp']);
	
		//Obtengo los datos
		$cod = Yii::$app->request->post('cod','');
		$obj = Yii::$app->request->post('objeto','');
		
		if (strlen($obj) < 8 && $obj != '')
		{
			$obj = utb::GetObjeto($cod,$obj);
			echo '<script>$("#pagocta_txObjeto").val("'.$obj.'")</script>';	
		} else 
		{
			echo '<script>$("#pagocta_txObjeto").val("'.$obj.'")</script>';	
		}
		
	Pjax::end();
	
?>

<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>	
	<table border="0">
		<tr>
			<td width="100px"><?= Html::checkbox('ckCodPagocta',false, ['id' => 'pagocta_ckCodPagocta','label' => 'Código:', 'onchange' => 'filtrosPagocta()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td><?= Html::input('text', 'txNumPagoctaDesde', null, ['id' => 'pagocta_txNumPagoctaDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
			<td width="5px"></<td>
			<td width="45px"><label>Hasta&nbsp;</label></td>
			<td><?= Html::input('text', 'txNumPagoctaHasta', null, ['id' => 'pagocta_txNumPagoctaHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckObjeto',false, ['id' => 'pagocta_ckObjeto','label' => 'Objeto:', 'onchange' => 'filtrosPagocta()']); ?></td>
			<td><label>Tipo&nbsp;</label></td>
			<td><?= Html::dropDownList('dlObjeto', null, utb::getAux('objeto_tipo','cod','nombre',0,"est='A'"), ['id'=>'pagocta_dlObjeto', 'style'=>'width:80px', 'class' => 'form-control','onchange'=>'objeto()']); ?></td>
			<td></td>
			<td><label>Objeto&nbsp;</label></td>
			<td><?= Html::input('text', 'txObjeto', null, ['id' => 'pagocta_txObjeto', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onchange'=>'codObjeto()']); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckMonto',false, ['id' => 'pagocta_ckMonto','label' => 'Monto:','onchange' => 'filtrosPagocta()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= Html::input('text', 'txMontoDesde', null, ['id' => 'pagocta_txMontoDesde', 'style'=>'width:80px', 'class' => 'form-control']); ?></td>
			<td></<td>
			<td><label>Hasta&nbsp;</label></td>
			<td><?= Html::input('text', 'txMontoHasta', null, ['id' => 'pagocta_txMontoHasta', 'style'=>'width:80px', 'class' => 'form-control']); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckTributo',false, ['id' => 'pagocta_ckTributo','label' => 'Tributo:', 'onchange' => 'filtrosPagocta()']); ?></td>
			<td colspan="6"><?= Html::dropDownList('dlTributo', null, utb::getAux('trib','trib_id','nombre',0,"tipo IN (1,2,3,4) OR trib_id = 1 OR trib_id = 3"), ['id'=>'pagocta_dlTributo', 'style'=>'width:280px', 'class' => 'form-control','onchange'=>'objeto()']); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckPeriodo',false, ['id' => 'pagocta_ckPeriodo','label' => 'Período:','onchange' => 'filtrosPagocta()']); ?></td>
			<td><label>Año&nbsp;</label></td><td><?= Html::input('text', 'txAnio', null, [
								'id' => 'pagocta_txAnio', 
								'style'=>'width:45px', 
								'class' => 'form-control',
								'maxlenght' => 4,
								'onkeypress'=>'return justNumbers(event)']); ?></td>
			<td></<td>
			<td><label>Cuota&nbsp;</label></td><td><?= Html::input('text', 'txCuota', null, [
								'id' => 'pagocta_txCuota', 
								'style'=>'width:30px', 
								'class' => 'form-control',
								'maxlenght' => 3,
								'onkeypress'=>'return justNumbers(event)']); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckFechaLimite',false, ['id' => 'pagocta_ckFechaLimite','label' => 'Fecha Límite:','onchange' => 'filtrosPagocta()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'pagocta_txFechaLimiteDesde',
										'name' => 'txFechaLimiteDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
									]
								);
							?></td>
			<td></td>
			<td><label>Hasta&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'pagocta_txFechaLimiteHasta',
										'name' => 'txFechaLimiteHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckFechaPago',false, ['id' => 'pagocta_ckFechaPago','label' => 'Fecha Pago:','onchange' => 'filtrosPagocta()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'pagocta_txFechaPagoDesde',
										'name' => 'txFechaPagoDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
									]
								);
							?></td>
			<td></td>
			<td><label>Hasta&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'pagocta_txFechaPagoHasta',
										'name' => 'txFechaPagoHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckFechaAlta',false, ['id' => 'pagocta_ckFechaAlta','label' => 'Fecha Alta:','onchange' => 'filtrosPagocta()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'pagocta_txFechaAltaDesde',
										'name' => 'txFechaAltaDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
									]
								);
							?></td>
			<td></td>
			<td><label>Hasta&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'pagocta_txFechaAltaHasta',
										'name' => 'txFechaAltaHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px;', 'class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckEstado',false, ['id' => 'pagocta_ckEstado','label' => 'Estado:', 'onchange' => 'filtrosPagocta()']); ?></td>
			<td colspan="2"><?= Html::dropDownList('dlEstado', null, utb::getAux('ctacte_test','cod','nombre',0,"cod IN ('D','B','P')"), ['id'=>'pagocta_dlEstado', 'style'=>'width:125px', 'class' => 'form-control']); ?></td>
		</tr>
	</table>

</div>

<div style="margin-top: 8px; margin-bottom: 8px">	

	<?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'validaFiltros();']) ?>
	
</div>

<div id="pagocta_errorSummary" class="error-summary" style="display:none">

	<ul>
	</ul>

</div>	
	
	<?php
	
	ActiveForm::end();
	?>

	
</div>
	
	<script type="text/javascript">
	
function objeto()
{
	$("#pagocta_txObjeto").val('');
}

function codObjeto()
{
	$.pjax.reload(
		{
			container:"#NUMResp",
			data:{
					objeto:$("#pagocta_txObjeto").val(),
					cod:$("#pagocta_dlObjeto").val()},
			method:"POST",
		}
	)
};
	
	
function filtrosPagocta()
{
	if ($("#pagocta_ckCodPagocta").prop("checked"))
	{
		$("#pagocta_txNumPagoctaDesde").removeAttr('disabled');
		$("#pagocta_txNumPagoctaHasta").removeAttr('disabled');
	} else {
		$("#pagocta_txNumPagoctaDesde").attr('disabled', true);
		$("#pagocta_txNumPagoctaHasta").attr('disabled',true);
	}

	if ($("#pagocta_ckObjeto").prop("checked"))
	{
		$("#pagocta_dlObjeto").removeAttr('disabled');
		$("#pagocta_txObjeto").removeAttr('disabled');
	} else {
		$("#pagocta_dlObjeto").attr('disabled', true);
		$("#pagocta_txObjeto").attr('disabled', true);
	}
	
	if ($("#pagocta_ckMonto").prop("checked"))
	{
		$("#pagocta_txMontoDesde").removeAttr('disabled');
		$("#pagocta_txMontoHasta").removeAttr('disabled');
	} else {
		$("#pagocta_txMontoDesde").attr('disabled', true);
		$("#pagocta_txMontoHasta").attr('disabled',true);
	}	
	
	if ($("#pagocta_ckTributo").prop("checked"))
	{
		$("#pagocta_dlTributo").removeAttr('disabled');
	} else {
		$("#pagocta_dlTributo").attr('disabled', true);
	}
	
	if ($("#pagocta_ckPeriodo").prop("checked"))
	{
		$("#pagocta_txAnio").removeAttr('disabled');
		$("#pagocta_txCuota").removeAttr('disabled');
	} else {
		$("#pagocta_txAnio").attr('disabled', true);
		$("#pagocta_txCuota").attr('disabled',true);
	}
	
	if ($("#pagocta_ckFechaLimite").prop("checked"))
	{
		$("#pagocta_txFechaLimiteDesde").removeAttr('disabled');
		$("#pagocta_txFechaLimiteHasta").removeAttr('disabled');
	} else {
		$("#pagocta_txFechaLimiteDesde").attr('disabled', true);
		$("#pagocta_txFechaLimiteHasta").attr('disabled',true);
	}
	
	if ($("#pagocta_ckFechaPago").prop("checked"))
	{
		$("#pagocta_txFechaPagoDesde").removeAttr('disabled');
		$("#pagocta_txFechaPagoHasta").removeAttr('disabled');
	} else {
		$("#pagocta_txFechaPagoDesde").attr('disabled', true);
		$("#pagocta_txFechaPagoHasta").attr('disabled',true);
	}
	
	if ($("#pagocta_ckFechaAlta").prop("checked"))
	{
		$("#pagocta_txFechaAltaDesde").removeAttr('disabled');
		$("#pagocta_txFechaAltaHasta").removeAttr('disabled');
	} else {
		$("#pagocta_txFechaAltaDesde").attr('disabled', true);
		$("#pagocta_txFechaAltaHasta").attr('disabled',true);
	}
		
	if ($("#pagocta_ckEstado").prop("checked"))
	{
		$("#pagocta_dlEstado").removeAttr('disabled');
	} else {
		$("#pagocta_dlEstado").attr('disabled', true);
	}	
		
}

function validaFiltros()
{
	
	var error = new Array(),  /* Esta variable almacenará los errores */
		criterio = '',
		descr = '';
	
	if ($("#pagocta_ckCodPagocta").prop("checked"))
	{
		if ($("#pagocta_txNumPagoctaDesde").val() == "" || $("#pagocta_txNumPagoctaHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Código de Pago." );
		} else if ($("#pagocta_txNumPagoctaDesde").val() > $("#pagocta_txNumPagoctaHasta").val()){
			
			error.push( "Rango de Código de Pago mal ingresado." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += " pago_id >="+$("#pagocta_txNumPagoctaDesde").val()+" AND pago_id <= "+$("#pagocta_txNumPagoctaHasta").val();	
			descr += " - Código de Pago: Desde "+$("#pagocta_txNumPagoctaDesde").val()+" - Hasta "+$("#pagocta_txNumPagoctaHasta").val();
		}
	} 
	
	if ($("#pagocta_ckObjeto").prop("checked"))
	{
		if ($("#pagocta_txObjeto").val() == "")
		{
			error.push( "Debe ingresar un Objeto." );
		} else {	
			if (criterio !== "") criterio += " and ";
			//obj_nom = '"+($("#pagocta_dlObjeto option[value="+$("#pagocta_dlObjeto").val()+"]").text())+"' AND 
			criterio += "obj_id ='"+$("#pagocta_txObjeto").val()+"'";	
			descr += " - El tipo de Objeto es "+($("#pagocta_dlObjeto option[value="+$("#pagocta_dlObjeto").val()+"]").text())+" - Objeto: "+$("#pagocta_txObjeto").val();
		}
	} 
	
	if ($("#pagocta_ckMonto").prop("checked"))
	{
		if ($("#pagocta_txMontoDesde").val() == "" || $("#pagocta_txMontoHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Monto." );
		} else if ($("#pagocta_txMontoDesde").val() > $("#pagocta_txMontoHasta").val()){
			
			error.push( "Rango de Monto mal ingresado." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += " monto >='"+$("#pagocta_txMontoDesde").val()+"' AND monto <='"+$("#pagocta_txMontoHasta").val()+"'";	
			descr += " - Monto: Desde "+$("#pagocta_txMontoDesde").val()+" - Hasta "+$("#pagocta_txMontoHasta").val();
		}
	} 	
	
	if ($("#pagocta_ckTributo").prop("checked"))
	{
		if (criterio!=="") criterio += " and ";
		criterio += " trib_id = "+($("#pagocta_dlTributo").val());	
		descr += " - El tributo es: " + ($("#pagocta_dlTributo option[value="+$("#pagocta_dlTributo").val()+"]").text());
	}
	
	if ($("#pagocta_ckPeriodo").prop("checked"))
	{
		if ($("#pagocta_txAnio").val() == "" || $("#pagocta_txCuota").val() == "")
		{
			error.push( "Debe ingresar el Rango de Período." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += " anio ="+$("#pagocta_txAnio").val()+" AND cuota ="+$("#pagocta_txCuota").val();	
			descr += " - Período: Año "+$("#pagocta_txAnio").val()+" - Cuota "+$("#pagocta_txCuota").val();
		}
	} 
	
	if ($("#pagocta_ckFechaLimite").prop("checked"))
	{
		if ($("#pagocta_txFechaLimiteDesde").val()== "" || $("#pagocta_txFechaLimiteHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha Límite." );
		}else if (ValidarRangoFechaJs($("#pagocta_txFechaLimiteDesde").val(), $("#pagocta_txFechaLimiteHasta").val()) == 1)
		{
			error.push( "Rango de Fecha Límite mal ingresado." );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchlimite::date between '"+$("#pagocta_txFechaLimiteDesde").val()+"' AND '"+$("#pagocta_txFechaLimiteHasta").val()+"'";
			descr += " -Fecha Límite desde "+$("#pagocta_txFechaLimiteDesde").val()+" hasta "+$("#pagocta_txFechaLimiteHasta").val();	
		}	
	}
	
	if ($("#pagocta_ckFechaPago").prop("checked"))
	{
		if ($("#pagocta_txFechaPagoDesde").val()== "" || $("#pagocta_txFechaPagoHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha Pago." );
		}else if (ValidarRangoFechaJs($("#pagocta_txFechaPagoDesde").val(), $("#pagocta_txFechaPagoHasta").val()) == 1)
		{
			error.push( "Rango de Fecha Pago mal ingresado." );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchpago::date between '"+$("#pagocta_txFechaPagoDesde").val()+"' AND '"+$("#pagocta_txFechaPagoHasta").val()+"'";
			descr += " -Fecha Pago desde "+$("#pagocta_txFechaPagoDesde").val()+" hasta "+$("#pagocta_txFechaPagoHasta").val();	
		}	
	}
	
	if ($("#pagocta_ckFechaAlta").prop("checked"))
	{
		if ($("#pagocta_txFechaAltaDesde").val()== "" || $("#pagocta_txFechaAltaHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha Alta." );
		}else if (ValidarRangoFechaJs($("#pagocta_txFechaAltaDesde").val(), $("#pagocta_txFechaAltaHasta").val()) == 1)
		{
			error.push( "Rango de Fecha Alta mal ingresado." );
		}else  {
			if (criterio!=="") criterio += " and ";
			criterio += " fchmod::date between '"+$("#pagocta_txFechaAltaDesde").val()+"' AND '"+$("#pagocta_txFechaAltaHasta").val()+"'";
			descr += " -Fecha Alta desde "+$("#pagocta_txFechaAltaDesde").val()+" hasta "+$("#pagocta_txFechaAltaHasta").val();
		}	
	}

	if ($("#pagocta_ckEstado").prop("checked"))
	{
		if ($("#pagocta_dlEstado").val() == "")
		{
			error.push( "Ingrese un estado válido." );		
			
		} else {
				
				if (criterio!=="") criterio += " and ";
				criterio += " est ='"+$("#pagocta_dlEstado").val()+"'";	
				descr += " - El estado es: " + ($("#pagocta_dlEstado option[value="+$("#pagocta_dlEstado").val()+"]").text());
		}
	}
	
	if ( criterio == '' && error.length == 0 ) 
		error.push( "No se encontraron condiciones de búsqueda." );
	
	if ( error.length == 0 )
	{
			$("#pagocta_txCriterio").val(criterio);
			$("#pagocta_txDescripcion").val(descr);
			$("#form-listadoPagocta-list").submit();
	} else {
		
		mostrarErrores( error, "#pagocta_errorSummary" );
	}
	
}

filtrosPagocta();

</script>