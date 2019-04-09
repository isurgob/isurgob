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
$this->params['breadcrumbs'][] = ['label' => 'Fiscalización', 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Listado';
$this->params['breadcrumbs'][] = $title;

?>
<div class="listadoFiscalizacion-view" style="width:75%;">	
	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'><?= Html::a('Volver', ['view'],['class' => 'btn btn-primary']) ?></td>
		</tr>
	</table>

	

	<?php
	
	$form = ActiveForm::begin([
		'id' => 'form-listadoFiscalizacion-list',
		'action' => ['listado']
	
	]);
	
	echo "<input type='text' name='txCriterio' id='fiscalizacion_txCriterio' style='display:none'>";
	echo "<input type='text' name='txDescripcion' id='fiscalizacion_txDescripcion' style='display:none'>";
	
	Pjax::begin(['id'=>'NUMResp']);
	
		$objDesde = "";
		$objHasta = "";
		
		if (isset($_POST['objetoDesde'])) $objDesde = $_POST['objetoDesde'];
		if (isset($_POST['objetoHasta'])) $objHasta = $_POST['objetoHasta'];
		
		if (strlen($objDesde) < 8 and $objDesde != "")
		{
			$objDesde = utb::GetObjeto(2,$objDesde);
			echo '<script>$("#fiscalizacion_txObjetoDesde").val("'.$objDesde.'")</script>';	
		}
		
		if (strlen($objHasta) < 8 and $objHasta != "")
		{
			$objHasta = utb::GetObjeto(2,$objHasta);
			echo '<script>$("#fiscalizacion_txObjetoHasta").val("'.$objHasta.'")</script>';	
		}
			
	Pjax::end();

		
	
?>

<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>	
	<table border="0">
		<tr>
			<td><?= Html::checkbox('ckNumFiscalizacion',false, ['id' => 'fiscalizacion_ckNumFiscalizacion','label' => 'ID:', 'onchange' => 'filtrosFiscalizacion()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= Html::input('text', 'txNumFiscalizacionDesde', null, ['id' => 'fiscalizacion_txNumFiscalizacionDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
			<td width="5px"></<td>
			<td><label>Hasta&nbsp;</label></td>
			<td><?= Html::input('text', 'txNumFiscalizacionHasta', null, ['id' => 'fiscalizacion_txNumFiscalizacionHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
		</tr>
		<tr>
			<td><?= Html::checkbox('ckObjeto',false, ['id' => 'fiscalizacion_ckObjeto','label' => 'Objeto:', 'onchange' => 'filtrosFiscalizacion()']); ?></td>
			<td><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txObjetoDesde', null, ['id' => 'fiscalizacion_txObjetoDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onchange'=>'codObjeto()']); ?></td>
			<td></<td>
			<td><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txObjetoHasta', null, ['id' => 'fiscalizacion_txObjetoHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px','onchange'=>'codObjeto()']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckEstado',false, ['id' => 'fiscalizacion_ckEstado','label' => 'Estado DJ:', 'onchange' => 'filtrosFiscalizacion()']); ?></td>
			<td colspan="2"><?= Html::dropDownList('dlEstado', null, utb::getAux('fiscaliza_test'), ['id'=>'fiscalizacion_dlEstado', 'style'=>'width:125px', 'class' => 'form-control']); ?></td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckExpediente',false, ['id' => 'fiscalizacion_ckExpediente', 'label' => 'Expediente:','onchange' => 'filtrosFiscalizacion()']); ?></td>
			<td colspan="2"><?= Html::input('text', 'txExpediente', null, ['id' => 'fiscalizacion_txExpediente','style'=>'width:125px;; text-transform: uppercase', 'maxlength'=>'12', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckInspector',false, ['id' => 'fiscalizacion_ckInspector','label' => 'Inspector Principal', 'onchange' => 'filtrosFiscalizacion()']); ?></td>
			<td colspan="6">
				<?= Html::dropDownList('dlInspector', null, utb::getAux('sam.sis_usuario','usr_id','apenom',0,"inspec_comer <> 0"), [
						'id'=>'fiscalizacion_dlInspector',
						'style'=>'width:250px',
						'class' => 'form-control',
					]); 
				?>
			</td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckFechaAlta',false, ['id' => 'fiscalizacion_ckFechaAlta','label' => 'Fecha Alta:','onchange' => 'filtrosFiscalizacion()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'fiscalizacion_txFechaAltaDesde',
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
										'id' => 'fiscalizacion_txFechaAltaHasta',
										'name' => 'txFechaAltaHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px;', 'class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckFechaBaja',false, ['id' => 'fiscalizacion_ckFechaBaja','label' => 'Fecha Baja:','onchange' => 'filtrosFiscalizacion()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'fiscalizacion_txFechaBajaDesde',
										'name' => 'txFechaBajaDesde',
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
										'id' => 'fiscalizacion_txFechaBajaHasta',
										'name' => 'txFechaBajaHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px;', 'class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
	</table>
</div>

<div style="margin-top: 8px; margin-bottom: 8px">	

	<?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'validaFiltros();']) ?>
	
</div>

<div id="fiscaliza_errorSummary" class="error-summary" style="display:none">

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
	$("#fiscalizacion_txObjetoDesde").val('');
	$("#fiscalizacion_txObjetoHasta").val('');
}

function codObjeto()
{
	$.pjax.reload(
		{
			container:"#NUMResp",
			data:{
					objetoDesde:$("#fiscalizacion_txObjetoDesde").val(),
					objetoHasta:$("#fiscalizacion_txObjetoHasta").val(),
				},
			method:"POST",
		}
	)
};
	
	
function filtrosFiscalizacion()
{
	if ($("#fiscalizacion_ckNumFiscalizacion").prop("checked"))
	{
		$("#fiscalizacion_txNumFiscalizacionDesde").removeAttr('disabled');
		$("#fiscalizacion_txNumFiscalizacionHasta").removeAttr('disabled');
	} else {
		$("#fiscalizacion_txNumFiscalizacionDesde").attr('disabled', true);
		$("#fiscalizacion_txNumFiscalizacionHasta").attr('disabled',true);
	}
	
	if ($("#fiscalizacion_ckObjeto").prop("checked"))
	{
		$("#fiscalizacion_txObjetoDesde").removeAttr('disabled');
		$("#fiscalizacion_txObjetoHasta").removeAttr('disabled');
	} else {
		$("#fiscalizacion_txObjetoDesde").attr('disabled', true);
		$("#fiscalizacion_txObjetoHasta").attr('disabled',true);
	}
		
	if ($("#fiscalizacion_ckExpediente").prop("checked"))
	{
		$("#fiscalizacion_txExpediente").removeAttr('disabled');
	} else {
		$("#fiscalizacion_txExpediente").attr('disabled', true);
	}	
		
	if ($("#fiscalizacion_ckInspector").prop("checked"))
	{
		$("#fiscalizacion_dlInspector").removeAttr('disabled');
	} else {
		$("#fiscalizacion_dlInspector").attr('disabled', true);
	}
		
	if ($("#fiscalizacion_ckEstado").prop("checked"))
	{
		$("#fiscalizacion_dlEstado").removeAttr('disabled');
	} else {
		$("#fiscalizacion_dlEstado").attr('disabled', true);
	}
	
	if ($("#fiscalizacion_ckFechaAlta").prop("checked"))
	{
		$("#fiscalizacion_txFechaAltaDesde").removeAttr('disabled');
		$("#fiscalizacion_txFechaAltaHasta").removeAttr('disabled');
	} else {
		$("#fiscalizacion_txFechaAltaDesde").attr('disabled', true);
		$("#fiscalizacion_txFechaAltaHasta").attr('disabled',true);
	}	
	
	if ($("#fiscalizacion_ckFechaBaja").prop("checked"))
	{
		$("#fiscalizacion_txFechaBajaDesde").removeAttr('disabled');
		$("#fiscalizacion_txFechaBajaHasta").removeAttr('disabled');
	} else {
		$("#fiscalizacion_txFechaBajaDesde").attr('disabled', true);
		$("#fiscalizacion_txFechaBajaHasta").attr('disabled',true);
	}	
		
}

function validaFiltros()
{
	
	var error = new Array(),  /* Esta variable almacenará los errores */
		criterio = '',
		descr = '';
	
	if ($("#fiscalizacion_ckNumFiscalizacion").prop("checked"))
	{
		if ($("#fiscalizacion_txNumFiscalizacionDesde").val() == "" || $("#fiscalizacion_txNumFiscalizacionHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Nº de Fiscalizacion." );
		} else if ($("#fiscalizacion_txNumFiscalizacionDesde").val() > $("#fiscalizacion_txNumFiscalizacionHasta").val()){
			
			error.push( "Rango de Nº de Fiscalizacion mal ingresado." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += " fisca_id >="+$("#fiscalizacion_txNumFiscalizacionDesde").val()+" AND fisca_id <= "+$("#fiscalizacion_txNumFiscalizacionHasta").val();	
			descr += " - Nº de Fiscalizacion: Desde "+$("#fiscalizacion_txNumFiscalizacionDesde").val()+" - Hasta "+$("#fiscalizacion_txNumFiscalizacionHasta").val();
		}
	} 

	if ($("#fiscalizacion_ckObjeto").prop("checked"))
	{
		if ($("#fiscalizacion_txObjetoDesde").val() == "" || $("#fiscalizacion_txObjetoHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Objeto." );
		} else if ($("#fiscalizacion_txObjetoDesde").val() > $("#fiscalizacion_txObjetoHasta").val()){
			
			error.push( "Rango de Objeto mal ingresado." );
		} else {	
			if (criterio !== "") criterio += " and ";
			criterio += " obj_id >='"+$("#fiscalizacion_txObjetoDesde").val()+"' AND obj_id <='"+$("#fiscalizacion_txObjetoHasta").val()+"'";	
			descr += "Código de Comercio: Desde "+$("#fiscalizacion_txObjetoDesde").val()+" - Hasta "+$("#fiscalizacion_txObjetoHasta").val();
		}
	} 
	
	if ($("#fiscalizacion_ckExpediente").prop("checked"))
	{
		if ($("#fiscalizacion_txExpediente").val() == "")
		{
			error.push( "Ingrese un nombre de Expediente." );				
		} else {			
				if (criterio!=="") criterio += " and ";
				criterio += " expe like '%"+($("#fiscalizacion_txExpediente").val()).toUpperCase()+"%'";	
				descr += " - El nombre de Expediente Contiene: " + ($("#fiscalizacion_txExpediente").val()).toUpperCase();
		}
	}
	
	if ($("#fiscalizacion_ckInspector").prop("checked"))
	{
		if (criterio!=="") criterio += " and ";
		criterio += " inspector = "+($("#fiscalizacion_dlInspector").val());	
		descr += " - El inspector es: " + ($("#fiscalizacion_dlInspector option[value="+$("#fiscalizacion_dlInspector").val()+"]").text());
	}
	
	if ($("#fiscalizacion_ckEstado").prop("checked"))
	{
		if ($("#fiscalizacion_dlEstado").val() == "")
		{
			error.push( "Ingrese un estado válido." );		
			
		} else {
				
				if (criterio!=="") criterio += " and ";
				criterio += " est ='"+$("#fiscalizacion_dlEstado").val()+"'";	
				descr += " - El estado es: " + ($("#fiscalizacion_dlEstado option[value="+$("#fiscalizacion_dlEstado").val()+"]").text());
		}
	}
	
	if ($("#fiscalizacion_ckFechaAlta").prop("checked"))
	{
		if ($("#fiscalizacion_txFechaAltaDesde").val()== "" || $("#fiscalizacion_txFechaAltaHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Alta." );
		
		} else if (ValidarRangoFechaJs($("#fiscalizacion_txFechaAltaDesde").val(), $("#fiscalizacion_txFechaAltaHasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Alta mal ingresado." );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchalta::date between '"+$("#fiscalizacion_txFechaAltaDesde").val()+"' AND '"+$("#fiscalizacion_txFechaAltaHasta").val()+"'";
			descr += " -Fecha de Presentación desde "+$("#fiscalizacion_txFechaAltaDesde").val()+" hasta "+$("#fiscalizacion_txFechaAltaHasta").val();
		}	
	}
	
	if ($("#fiscalizacion_ckFechaBaja").prop("checked"))
	{
		if ($("#fiscalizacion_txFechaBajaHasta").val()== "" || $("#fiscalizacion_txFechaBajaHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Baja." );
		}else if (ValidarRangoFechaJs($("#fiscalizacion_txFechaBajaDesde").val(), $("#fiscalizacion_txFechaBajaHasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Baja mal ingresado." );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchbaja::date between '"+$("#fiscalizacion_txFechaBajaDesde").val()+"' AND '"+$("#fiscalizacion_txFechaBajaHasta").val()+"'";
			descr += " -Fecha de Baja desde "+$("#fiscalizacion_txFechaBajaDesde").val()+" hasta "+$("#fiscalizacion_txFechaBajaHasta").val();
		}	
	}
	
	if ( criterio == '' && error.length == 0 ) 
		error.push( "No se encontraron condiciones de búsqueda." );
	
	if ( error.length == 0 )
	{
			$("#fiscalizacion_txCriterio").val(criterio);
			$("#fiscalizacion_txDescripcion").val(descr);
			$("#form-listadoFiscalizacion-list").submit();
	} else {
		
		mostrarErrores( error, "#fiscaliza_errorSummary" );
	}
	
}

filtrosFiscalizacion();

</script>