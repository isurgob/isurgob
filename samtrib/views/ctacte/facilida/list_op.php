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
$this->params['breadcrumbs'][] = ['label' => 'Facilidad de Pago', 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Listado';
$this->params['breadcrumbs'][] = $title;

	
$form = ActiveForm::begin([
	'id' => 'form-listadoFacilida-list',
	'action' => ['listado']

]);
	
?>
<div class="listadoFacilida-view" style='width:75%;'>	

	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'><?= Html::a('Volver', ['view'],['class' => 'btn btn-primary']) ?></td>
		</tr>
	</table>

	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>

	<?php
	
	echo "<input type='text' name='txCriterio' id='facilida-txCriterio' style='display:none'>";
	echo "<input type='text' name='txDescripcion' id='facilida-txDescripcion' style='display:none'>";
	
	Pjax::begin(['id'=>'NUMResp']);
	
		$cod = "";
		$objDesde = "";
		$objHasta = "";
		
		if (isset($_POST['cod'])) $cod = $_POST['cod'];
		if (isset($_POST['objetoDesde'])) $objDesde = $_POST['objetoDesde'];
		if (isset($_POST['objetoHasta'])) $objHasta = $_POST['objetoHasta'];
		
		if (strlen($objDesde) < 8 and $objDesde != "")
		{
		$objDesde = utb::GetObjeto($cod,$objDesde);
		echo '<script>$("#facilida-txObjetoDesde").val("'.$objDesde.'")</script>';	
		}
		if (strlen($objHasta) < 8 and $objHasta != "")
		{
		$objHasta = utb::GetObjeto($cod,$objHasta);
		echo '<script>$("#facilida-txObjetoHasta").val("'.$objHasta.'")</script>';	
		}
			
	Pjax::end();

		
	
?>

	
	<table border="0">
		<tr>
			<td width="150px"><?= Html::checkbox('ckNFacilidad',false, ['id' => 'facilida-ckNFacilidad','label' => 'Nº Facilidad:', 'onchange' => 'filtrosFacilida()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txFacilidadDesde', null, ['id' => 'facilida-txFacilidadDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
			<td width="5px"></<td>
			<td width="45px"><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txFacilidadHasta', null, ['id' => 'facilida-txFacilidadHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
		</tr>
	</table>
	
	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckTributo',false, ['id' => 'facilida-ckTributo','label' => 'Tributo:','onchange' => 'filtrosFacilida()']); ?></td>
			<td width="258px"><?= Html::dropDownList('dlTributo', null, utb::getAux('trib','trib_id','nombre',0,"trib_id not in (1,2,4,6,12)"), ['id'=>'facilida-dlTributo', 'style'=>'width:258px', 'class' => 'form-control']); ?></td>
		</tr>
	</table>
	
	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckObjeto',false, ['id' => 'facilida-ckObjeto','label' => 'Objeto:', 'onchange' => 'filtrosFacilida()']); ?></td>
			<td width="125px"><?= Html::dropDownList('dlObjeto', null, utb::getAux('objeto_tipo','cod','nombre',0,"est='A'"), ['id'=>'facilida-dlObjeto', 'style'=>'width:125px', 'class' => 'form-control','onchange'=>'objeto()']); ?></td>
			<td width="5px"></td>
			<td width="45px"><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txObjetoDesde', null, ['id' => 'facilida-txObjetoDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onchange'=>'codObjeto()']); ?></td>
			<td width="5px"></<td>
			<td width="45px"><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txObjetoHasta', null, ['id' => 'facilida-txObjetoHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px','onchange'=>'codObjeto()']); ?></td>
		</tr>
	</table>
	
	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckEstado',false, ['id' => 'facilida-ckEstado','label' => 'Estado:', 'onchange' => 'filtrosFacilida()']); ?></td>
			<td width="125px"><?= Html::dropDownList('dlEstado', null, utb::getAux('plan_test','cod','nombre',0,"cod in (1,2,5)"), ['id'=>'facilida-dlEstado', 'style'=>'width:125px', 'class' => 'form-control']); ?></td>
		</tr>
	</table>
	
	<table>	
		<tr>
			<td width="150px"><?= Html::checkbox('ckContribuyente',false, ['id' => 'facilida-ckContribuyente', 'label' => 'Contribuyente:','onchange' => 'filtrosFacilida()']); ?></td>
			<td width="400px"><?= Html::input('text', 'txContribuyente', null, ['id' => 'facilida-txContribuyente','style'=>'width:257px; text-transform: uppercase', 'maxlength'=>'50', 'class' => 'form-control']); ?></td>
		</tr>	
	</table>
	
	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckMontoDeuda',false, ['id' => 'facilida-ckMontoDeuda','label' => 'Monto de Deuda:','onchange' => 'filtrosFacilida()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txMontoDeudaDesde', null, ['id' => 'facilida-txMontoDeudaDesde', 'style'=>'width:80px', 'class' => 'form-control']); ?></td><td></<td>
			<td width="5px"></<td>
			<td width="45px"><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txMontoDeudaHasta', null, ['id' => 'facilida-txMontoDeudaHasta', 'style'=>'width:80px', 'class' => 'form-control']); ?></td><td></<td>
		</tr>
	</table>
	
	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckFechaAlta',false, ['id' => 'facilida-ckFechaAlta','label' => 'Fecha de Alta:','onchange' => 'filtrosFacilida()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td width="80px"><?= 
								DatePicker::widget(
									[
										'id' => 'facilida-txFechaAltaDesde',
										'name' => 'txFechaAltaDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
									]
								);
							?></td>
			<td width="5px"></td>
			<td width="45px"><label>Hasta&nbsp;</label></td>
			<td width="80px"><?= 
								DatePicker::widget(
									[
										'id' => 'facilida-txFechaAltaHasta',
										'name' => 'txFechaAltaHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
		<tr>
			<td width="150px"><?= Html::checkbox('ckFechaVenc',false, ['id' => 'facilida-ckFechaVenc','label' => 'Fecha de Vencimiento:','onchange' => 'filtrosFacilida()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td width="80px"><?= 
								DatePicker::widget(
									[
										'id' => 'facilida-txFechaVencDesde',
										'name' => 'txFechaVencDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
									]
								);
							?></td>
			<td></td>
			<td width="45px"><label>Hasta&nbsp;</label></td>
			<td width="80px"><?= 
								DatePicker::widget(
									[
										'id' => 'facilida-txFechaVencHasta',
										'name' => 'txFechaVencHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
		<tr>
			<td width="150px"><?= Html::checkbox('ckFechaImputacion',false, ['id' => 'facilida-ckFechaImputacion','label' => 'Fecha de Imputación:','onchange' => 'filtrosFacilida()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td width="80px"><?= 
								DatePicker::widget(
									[
										'id' => 'facilida-txFechaImputacionDesde',
										'name' => 'txFechaImputacionDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
									]
								);
							?></td>
			<td></td>
			<td width="45px"><label>Hasta&nbsp;</label></td>
			<td width="80px"><?= 
								DatePicker::widget(
									[
										'id' => 'facilida-txFechaImputacionHasta',
										'name' => 'txFechaImputacionHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
		<tr>
			<td width="150px"><?= Html::checkbox('ckFechaBaja',false, ['id' => 'facilida-ckFechaBaja','label' => 'Fecha de Baja:','onchange' => 'filtrosFacilida()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td width="80px"><?= 
								DatePicker::widget(
									[
										'id' => 'facilida-txFechaBajaDesde',
										'name' => 'txFechaBajaDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
									]
								);
							?></td>
			<td></td>
			<td width="45px"><label>Hasta&nbsp;</label></td>
			<td width="80px"><?= 
								DatePicker::widget(
									[
										'id' => 'facilida-txFechaBajaHasta',
										'name' => 'txFechaBajaHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
	</table>
	<table>
		<tr>
			<td><?= Html::checkbox('ckBajaAutom',false, ['id' => 'facilida-ckBajaAutom','label' => 'No se dan de baja automáticamente','onchange' => 'filtrosFacilida()']); ?></td>
		</tr>
	</table>

	</div>
	
	
	<table width="100%">
		<tr>
			<td><?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'validaFiltros();']) ?></td>
		</tr>
	</table>
	
	<div id="facilida_listado_errorSummary" class="error-summary" style="display:none;margin-top: 8px">
		
		<ul>
		</ul>
	
	</div>
	
</div>
		
<?php

	ActiveForm::end();
	
?>


	<script type="text/javascript">
	
function objeto()
{
	$("#facilida-txObjetoDesde").val('');
	$("#facilida-txObjetoHasta").val('');
}

function codObjeto()
{
	$.pjax.reload(
		{
			container:"#NUMResp",
			data:{
					objetoDesde:$("#facilida-txObjetoDesde").val(),
					objetoHasta:$("#facilida-txObjetoHasta").val(),
					cod:$("#facilida-dlObjeto").val()},
			method:"POST",
		}
	)
};
	
	
function filtrosFacilida()
{
	if ($("#facilida-ckNFacilidad").prop("checked"))
	{
		$("#facilida-txFacilidadDesde").removeAttr('disabled');
		$("#facilida-txFacilidadHasta").removeAttr('disabled');
	} else {
		$("#facilida-txFacilidadDesde").attr('disabled', true);
		$("#facilida-txFacilidadHasta").attr('disabled',true);
	}
	
	if ($("#facilida-ckTributo").prop("checked"))
	{
		$("#facilida-dlTributo").removeAttr('disabled');
	} else {
		$("#facilida-dlTributo").attr('disabled', true);
	}
		
	if ($("#facilida-ckObjeto").prop("checked"))
	{
		$("#facilida-dlObjeto").removeAttr('disabled');
		$("#facilida-txObjetoDesde").removeAttr('disabled');
		$("#facilida-txObjetoHasta").removeAttr('disabled');
	} else {
		$("#facilida-dlObjeto").attr('disabled', true);
		$("#facilida-txObjetoDesde").attr('disabled', true);
		$("#facilida-txObjetoHasta").attr('disabled',true);
	}
	
	if ($("#facilida-ckEstado").prop("checked"))
	{
		$("#facilida-dlEstado").removeAttr('disabled');
	} else {
		$("#facilida-dlEstado").attr('disabled', true);
	}
	
	if ($("#facilida-ckContribuyente").prop("checked"))
	{
		$("#facilida-txContribuyente").removeAttr('disabled');
	} else {
		$("#facilida-txContribuyente").attr('disabled', true);
	}
	
	if ($("#facilida-ckMontoDeuda").prop("checked"))
	{
		$("#facilida-txMontoDeudaDesde").removeAttr('disabled');
		$("#facilida-txMontoDeudaHasta").removeAttr('disabled');
	} else {
		$("#facilida-txMontoDeudaDesde").attr('disabled', true);
		$("#facilida-txMontoDeudaHasta").attr('disabled',true);
	}
	
	if ($("#facilida-ckFechaAlta").prop("checked"))
	{
		$("#facilida-txFechaAltaDesde").removeAttr('disabled');
		$("#facilida-txFechaAltaHasta").removeAttr('disabled');
	} else {
		$("#facilida-txFechaAltaDesde").attr('disabled', true);
		$("#facilida-txFechaAltaHasta").attr('disabled',true);
	}
	
		if ($("#facilida-ckFechaVenc").prop("checked"))
	{
		$("#facilida-txFechaVencDesde").removeAttr('disabled');
		$("#facilida-txFechaVencHasta").removeAttr('disabled');
	} else {
		$("#facilida-txFechaVencDesde").attr('disabled', true);
		$("#facilida-txFechaVencHasta").attr('disabled',true);
	}
	
		if ($("#facilida-ckFechaImputacion").prop("checked"))
	{
		$("#facilida-txFechaImputacionDesde").removeAttr('disabled');
		$("#facilida-txFechaImputacionHasta").removeAttr('disabled');
	} else {
		$("#facilida-txFechaImputacionDesde").attr('disabled', true);
		$("#facilida-txFechaImputacionHasta").attr('disabled',true);
	}
	
		if ($("#facilida-ckFechaBaja").prop("checked"))
	{
		$("#facilida-txFechaBajaDesde").removeAttr('disabled');
		$("#facilida-txFechaBajaHasta").removeAttr('disabled');
	} else {
		$("#facilida-txFechaBajaDesde").attr('disabled', true);
		$("#facilida-txFechaBajaHasta").attr('disabled',true);
	}
}

function validaFiltros()
{
	
	var error = new Array(),  /* Esta variable almacenará los errores */
		criterio = '',
		descr = '';
	
	if ($("#facilida-ckNFacilidad").prop("checked"))
	{
		if ($("#facilida-txFacilidadDesde").val() == "" || $("#facilida-txFacilidadHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Nº de Facilidad." );
		} else if ($("#facilida-txFacilidadDesde").val() > $("#facilida-txFacilidadHasta").val()){
			
			error.push( "Rango de  Nº de Facilidad mal ingresado." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += " faci_id >="+$("#facilida-txFacilidadDesde").val()+" and faci_id <= "+$("#facilida-txFacilidadHasta").val();	
			descr += " - Nº de Facilidad: Desde "+$("#facilida-txFacilidadDesde").val()+" - Hasta "+$("#facilida-txFacilidadHasta").val();
		}
	} 
	
	if ($("#facilida-ckTributo").prop("checked"))
	{
		if ($("#facilida-dlTributo").val() == "")
		{
			error.push( "Ingrese un Tributo válido." );		
		} else {	
				if (criterio!=="") criterio += " and ";
				criterio += " trib_id ="+$("#facilida-dlTributo").val();	
				descr += " - El Tributo es " + ($("#facilida-dlTributo option[value="+$("#facilida-dlTributo").val()+"]").text());
		}
	}
	
	if ($("#facilida-ckObjeto").prop("checked"))
	{
		if ($("#facilida-txObjetoDesde").val() == "" || $("#facilida-txObjetoHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Objeto." );
		} else if ($("#facilida-txObjetoDesde").val() > $("#facilida-txObjetoHasta").val()){
			
			error.push( "Rango de Objeto mal ingresado." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += "tobj_nom = '"+($("#facilida-dlObjeto option[value="+$("#facilida-dlObjeto").val()+"]").text())+"' AND obj_id >='"+$("#facilida-txObjetoDesde").val()+"' AND obj_id <='"+$("#facilida-txObjetoHasta").val()+"'";	
			descr += " - EL tipo de Objeto es "+($("#facilida-dlObjeto option[value="+$("#facilida-dlObjeto").val()+"]").text())+" - Código de Objeto: Desde "+$("#facilida-txObjetoDesde").val()+" - Hasta "+$("#facilida-txObjetoHasta").val();
		}
	} 
	
	if ($("#facilida-ckEstado").prop("checked"))
	{
		if ($("#facilida-dlEstado").val() == "")
		{
			error.push( "Ingrese un estado válido." );		
			
		} else {
				
				if (criterio!=="") criterio += " and ";
				criterio += " est ='"+$("#facilida-dlEstado").val()+"'";	
				descr += " - El estado es: " + ($("#facilida-dlEstado option[value="+$("#facilida-dlEstado").val()+"]").text());
		}
	}
	
	if ($("#facilida-ckContribuyente").prop("checked"))
	{
		if ($("#facilida-txContribuyente").val() == "")
		{
			error.push( "Ingrese un Contribuyente válido." );				
		} else {			
				if (criterio!=="") criterio += " and ";
				criterio += " num_nom like '% "+($("#facilida-txContribuyente").val()).toUpperCase()+"%'";	
				descr += " - El nombre de Contribuyente Contiene: " + ($("#facilida-txContribuyente").val()).toUpperCase();
		}
	}
	
	if ($("#facilida-ckMontoDeuda").prop("checked"))
	{
		if ($("#facilida-txMontoDeudaDesde").val() == "" || $("#facilida-txMontoDeudaHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de MontoDeuda." );
		} else if ($("#facilida-txMontoDeudaDesde").val() > $("#facilida-txMontoDeudaHasta").val()){
			
			error.push( "Rango de Monto de Deuda mal ingresado." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += " total >='"+$("#facilida-txMontoDeudaDesde").val()+"' AND total <='"+$("#facilida-txMontoDeudaHasta").val()+"'";	
			descr += " - Monto de Deuda: Desde "+$("#facilida-txMontoDeudaDesde").val()+" - Hasta "+$("#facilida-txMontoDeudaHasta").val();
		}
	} 
	
	if ($("#facilida-ckFechaAlta").prop("checked"))
	{
		if (ValidarRangoFechaJs($("#facilida-txFechaAltaDesde").val(), $("#facilida-txFechaAltaHasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Alta mal ingresado" );
		}else if ($("#facilida-txFechaAltaDesde").val()== "" || $("#facilida-txFechaAltaHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Alta" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchalta::date between '"+$("#facilida-txFechaAltaDesde").val()+"' AND '"+$("#facilida-txFechaAltaHasta").val()+"'";
			descr += " -Fecha de Alta desde "+$("#facilida-txFechaAltaDesde").val()+" hasta "+$("#facilida-txFechaAltaHasta").val();	
		}	
	}
	
	if ($("#facilida-ckFechaVenc").prop("checked"))
	{
		if (ValidarRangoFechaJs($("#facilida-txFechaVencDesde").val(), $("#facilida-txFechaVencHasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Vencimiento mal ingresado" );
		}else if ($("#facilida-txFechaVencDesde").val()== "" || $("#facilida-txFechaVencHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Vencimiento" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchvenc::date between '"+$("#facilida-txFechaVencDesde").val()+"' AND '"+$("#facilida-txFechaVencHasta").val()+"'";
			descr += " -Fecha de Vencimiento desde "+$("#facilida-txFechaVencDesde").val()+" hasta "+$("#facilida-txFechaVencHasta").val();	
		}	
	}
	
	if ($("#facilida-ckFechaImputacion").prop("checked"))
	{
		if (ValidarRangoFechaJs($("#facilida-txFechaImputacionDesde").val(), $("#facilida-txFechaImputacionHasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Imputación mal ingresado" );
		}else if ($("#facilida-txFechaImputacionDesde").val()== "" || $("#facilida-txFechaImputacionHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Imputación" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchimputa::date between '"+$("#facilida-txFechaImputacionDesde").val()+"' AND '"+$("#facilida-txFechaImputacionHasta").val()+"'";
			descr += " -Fecha de Imputación desde "+$("#facilida-txFechaImputacionDesde").val()+" hasta "+$("#facilida-txFechaImputacionHasta").val();	
		}	
	}
	
	if ($("#facilida-ckFechaBaja").prop("checked"))
	{
		if (ValidarRangoFechaJs($("#facilida-txFechaBajaDesde").val(), $("#facilida-txFechaBajaHasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Baja mal ingresado" );
		}else if ($("#facilida-txFechaBajaDesde").val()== "" || $("#facilida-txFechaBajaHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Baja" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchbaja::date between '"+$("#facilida-txFechaBajaDesde").val()+"' AND '"+$("#facilida-txFechaBajaHasta").val()+"'";
			descr += " -Fecha de Baja desde "+$("#facilida-txFechaBajaDesde").val()+" hasta "+$("#facilida-txFechaBajaHasta").val();	
		}	
	}
	
	if ($("#facilida-ckBajaAutom").prop("checked"))
	{
		if (criterio!=="") criterio += " and ";
		criterio += " baja_auto=0 ";
		descr += " -No se dan de baja automáticamente";		
	}

	if (criterio == '' && error == '') 
		error.push( "No se encontraron condiciones de búsqueda." );
	
	if (error=='')
	{
			$("#facilida-txCriterio").val(criterio);
			$("#facilida-txDescripcion").val(descr);
			$("#form-listadoFacilida-list").submit();
	} else {
		
		mostrarErrores( error, "#facilida_listado_errorSummary" );
	}
	
}

filtrosFacilida();

</script>