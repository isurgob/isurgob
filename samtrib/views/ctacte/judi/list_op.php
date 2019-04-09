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
$this->params['breadcrumbs'][] = ['label' => 'Administración de Apremio', 'url' => ['view']];
$this->params['breadcrumbs'][] = ['label' => 'Listado'];
$this->params['breadcrumbs'][] = 'Opciones';
?>
<div class="listadoApremio-view" style="width:75%">	
	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'><?= Html::a('Volver', ['view'],['class' => 'btn btn-primary']) ?></td>
		</tr>
	</table>

	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>

	<?php
	
	$form = ActiveForm::begin([
		'id' => 'form-listadoApremio-list',
		'action' => ['listado']
	
	]);
	
	echo "<input type='text' name='txCriterio' id='apremio-txCriterio' style='display:none'>";
	echo "<input type='text' name='txDescripcion' id='apremio-txDescripcion' style='display:none'>";
	
	Pjax::begin(['id'=>'NUMResp']);
		
		$datos = Yii::$app->request->post( 'datos', [] );
		
		if ( count( $datos ) > 0 )
		{
			$cod = $datos[ 'cod' ];
			$objDesde = $datos[ 'objetoDesde' ];
			$objHasta = $datos[ 'objetoHasta' ];
			
			if ( strlen($objDesde) < 8 and $objDesde != "" )
			{
				$objDesde = utb::GetObjeto($cod,$objDesde);
			}
			
			if ( strlen($objHasta) < 8 and $objHasta != "" )
			{
				$objHasta = utb::GetObjeto($cod,$objHasta);
			}
			
			echo '<script>$("#apremio-txObjetoDesde").val("'.$objDesde.'")</script>';
			echo '<script>$("#apremio-txObjetoHasta").val("'.$objHasta.'")</script>';		
		}
			
	Pjax::end();

		
	
?>

	
	<table border="0">
		<tr>
			<td width="155px"><?= Html::checkbox('ckJudi',false, ['id' => 'apremio-ckJudi','label' => 'Judi:', 'onchange' => 'filtrosApremio()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= Html::input('text', 'txJudiDesde', null, ['id' => 'apremio-txJudiDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
			<td width="5px"></<td>
			<td><label>Hasta&nbsp;</label></td>
			<td><?= Html::input('text', 'txJudiHasta', null, ['id' => 'apremio-txJudiHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckObjeto',false, ['id' => 'apremio-ckObjeto','label' => 'Objeto:', 'onchange' => 'filtrosApremio()']); ?></td>
			<td colspan="2">
				<?= Html::dropDownList('dlObjeto', null, utb::getAux('objeto_tipo','cod','nombre',0,"est='A'"), [
						'id'=>'apremio-dlObjeto',
						'style'=>'width:125px',
						'class' => 'form-control',
						]); ?>
			</td>
			<td></<td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= Html::input('text', 'txObjetoDesde', null, ['id' => 'apremio-txObjetoDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px']); ?></td>
			<td width="5px"></<td>
			<td><label>Hasta&nbsp;</label></td>
			<td><?= Html::input('text', 'txObjetoHasta', null, ['id' => 'apremio-txObjetoHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px']); ?></td>
		</tr>
	
		<tr>
			<td><?= Html::checkbox('ckExpediente',false, ['id' => 'apremio-ckExpediente', 'label' => 'Expediente','onchange' => 'filtrosApremio()']); ?></td>
			<td colspan="4"><?= Html::input('text', 'txExpediente', null, ['id' => 'apremio-txExpediente','style'=>'width:125px; text-transform: uppercase', 'maxlength'=>'30', 'class' => 'form-control']); ?></td>
		</tr>	

		<tr>
			<td><?= Html::checkbox('ckCaratula',false, ['id' => 'apremio-ckCaratula', 'label' => 'Carátula','onchange' => 'filtrosApremio()']); ?></td>
			<td colspan="4"><?= Html::input('text', 'txCaratula', null, ['id' => 'apremio-txCaratula','style'=>'width:125px; text-transform: uppercase', 'maxlength'=>'50', 'class' => 'form-control']); ?></td>
		</tr>	

		<tr>
			<td><?= Html::checkbox('ckMontoDeuda',false, ['id' => 'apremio-ckMontoDeuda','label' => 'Deuda','onchange' => 'filtrosApremio()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= Html::input('text', 'txMontoDeudaDesde', null, ['id' => 'apremio-txMontoDeudaDesde', 'style'=>'width:80px', 'class' => 'form-control']); ?></td>
			<td></<td>
			<td><label>Hasta&nbsp;</label></td>
			<td><?= Html::input('text', 'txMontoDeudaHasta', null, ['id' => 'apremio-txMontoDeudaHasta', 'style'=>'width:80px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckGastos',false, ['id' => 'apremio-ckGastos','label' => 'Gastos:','onchange' => 'filtrosApremio()']); ?></td>
			<td><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txGastosDesde', null, ['id' => 'apremio-txGastosDesde', 'style'=>'width:80px', 'class' => 'form-control']); ?></td>
			<td></<td>
			<td><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txGastosHasta', null, ['id' => 'apremio-txGastosHasta', 'style'=>'width:80px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckHonorarios',false, ['id' => 'apremio-ckHonorarios','label' => 'Honorarios:','onchange' => 'filtrosApremio()']); ?></td>
			<td><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txHonorariosDesde', null, ['id' => 'apremio-txHonorariosDesde', 'style'=>'width:80px', 'class' => 'form-control']); ?></td>
			<td></<td>
			<td><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txHonorariosHasta', null, ['id' => 'apremio-txHonorariosHasta', 'style'=>'width:80px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckReparticion',false, ['id' => 'apremio-ckReparticion','label' => 'Repartición','onchange' => 'filtrosApremio()']); ?></td>
			<td colspan="5"><?= Html::dropDownList('dlReparticion', null, utb::getAux('judi_trep'), ['id'=>'apremio-dlReparticion', 'style'=>'width:255px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckEstado',false, ['id' => 'apremio-ckEstado','label' => 'Estado:','onchange' => 'filtrosApremio()']); ?></td>
			<td colspan="5"><?= Html::dropDownList('dlEstado', null, utb::getAux('judi_test'), ['id'=>'apremio-dlEstado', 'style'=>'width:255px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckProcurador',false, ['id' => 'apremio-ckProcurador','label' => 'Procurador:','onchange' => 'filtrosApremio()']); ?></td>
			<td colspan="5"><?= Html::dropDownList('dlProcurador', null, utb::getAux('sam.sis_usuario','usr_id','apenom', 0, 'abogado=1'), ['id'=>'apremio-dlProcurador', 'style'=>'width:255px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckJuzgado',false, ['id' => 'apremio-ckJuzgado','label' => 'Juzgado:','onchange' => 'filtrosApremio()']); ?></td>
			<td colspan="5"><?= Html::dropDownList('dlJuzgado', null, utb::getAux('judi_juzgado'), ['id'=>'apremio-dlJuzgado', 'style'=>'width:255px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckEtapa',false, ['id' => 'apremio-ckEtapa','label' => 'Etapa:','onchange' => 'filtrosApremio()']); ?></td>
			<td colspan="5"><?= Html::dropDownList('dlEtapa', null, utb::getAux('judi_tetapa'), ['id'=>'apremio-dlEtapa', 'style'=>'width:255px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckMotivoDev',false, ['id' => 'apremio-ckMotivoDev','label' => 'Motivo Devolución:','onchange' => 'filtrosApremio()']); ?></td>
			<td colspan="5"><?= Html::dropDownList('dlMotivoDev', null, utb::getAux('judi_tdev'), ['id'=>'apremio-dlMotivoDev', 'style'=>'width:255px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckFechaMovimiento',false, ['id' => 'apremio-ckFechaMovimiento','label' => 'Fecha de Movimiento:','onchange' => 'filtrosApremio()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'apremio-txFechaMovimientoDesde',
										'name' => 'txFechaMovimientoDesde',
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
										'id' => 'apremio-txFechaMovimientoHasta',
										'name' => 'txFechaMovimientoHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
		<tr>
			<td><?= Html::checkbox('ckFechaModificacion',false, ['id' => 'apremio-ckFechaModificacion','label' => 'Fecha de Modificación:','onchange' => 'filtrosApremio()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?= 
								DatePicker::widget(
									[
										'id' => 'apremio-txFechaModificacionDesde',
										'name' => 'txFechaModificacionDesde',
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
										'id' => 'apremio-txFechaModificacionHasta',
										'name' => 'txFechaModificacionHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
	</table>
	
	</div>
	
	<table width="100%">
		<tr>
			<td><?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'validaFiltros();']) ?></td>
		</tr>
	</table>
	
	<?php
	
	ActiveForm::end();
	?>
	
	<!-- INICIO Mensajes Error -->	
	<div id="apremio_list_errorSummary" class="error-summary" style="display:none;margin-top: 8px; margin-right: 0px">
		
		<ul>
		</ul>
		
	</div>
	<!-- FIN Mensajes Error -->
	
</div>

<script type="text/javascript">

function objeto()
{
	$("#apremio-txObjetoDesde").val('');
	$("#apremio-txObjetoHasta").val('');
}

function filtrosApremio()
{
	if ($("#apremio-ckJudi").prop("checked"))
	{
		$("#apremio-txJudiDesde").removeAttr('disabled');
		$("#apremio-txJudiHasta").removeAttr('disabled');
	} else {
		$("#apremio-txJudiDesde").attr('disabled', true);
		$("#apremio-txJudiHasta").attr('disabled',true);
	}

	if ($("#apremio-ckObjeto").prop("checked"))
	{
		$("#apremio-dlObjeto").removeAttr('disabled');
		$("#apremio-txObjetoDesde").removeAttr('disabled');
		$("#apremio-txObjetoHasta").removeAttr('disabled');
	} else {
		$("#apremio-dlObjeto").attr('disabled', true);
		$("#apremio-txObjetoDesde").attr('disabled', true);
		$("#apremio-txObjetoHasta").attr('disabled',true);
	}
	
	if ($("#apremio-ckExpediente").prop("checked"))
	{
		$("#apremio-txExpediente").removeAttr('disabled');
	} else {
		$("#apremio-txExpediente").attr('disabled', true);
	}
	
	if ($("#apremio-ckCaratula").prop("checked"))
	{
		$("#apremio-txCaratula").removeAttr('disabled');
	} else {
		$("#apremio-txCaratula").attr('disabled', true);
	}
		
	if ($("#apremio-ckMontoDeuda").prop("checked"))
	{
		$("#apremio-txMontoDeudaDesde").removeAttr('disabled');
		$("#apremio-txMontoDeudaHasta").removeAttr('disabled');
	} else {
		$("#apremio-txMontoDeudaDesde").attr('disabled', true);
		$("#apremio-txMontoDeudaHasta").attr('disabled',true);
	}
	
	if ($("#apremio-ckGastos").prop("checked"))
	{
		$("#apremio-txGastosDesde").removeAttr('disabled');
		$("#apremio-txGastosHasta").removeAttr('disabled');
	} else {
		$("#apremio-txGastosDesde").attr('disabled', true);
		$("#apremio-txGastosHasta").attr('disabled',true);
	}
	
	if ($("#apremio-ckHonorarios").prop("checked"))
	{
		$("#apremio-txHonorariosDesde").removeAttr('disabled');
		$("#apremio-txHonorariosHasta").removeAttr('disabled');
	} else {
		$("#apremio-txHonorariosDesde").attr('disabled', true);
		$("#apremio-txHonorariosHasta").attr('disabled',true);
	}
	
	if ($("#apremio-ckReparticion").prop("checked"))
	{
		$("#apremio-dlReparticion").removeAttr('disabled');
	} else {
		$("#apremio-dlReparticion").attr('disabled', true);
	}
	
	if ($("#apremio-ckEstado").prop("checked"))
	{
		$("#apremio-dlEstado").removeAttr('disabled');
	} else {
		$("#apremio-dlEstado").attr('disabled', true);
	}
	
	if ($("#apremio-ckProcurador").prop("checked"))
	{
		$("#apremio-dlProcurador").removeAttr('disabled');
	} else {
		$("#apremio-dlProcurador").attr('disabled', true);
	}
	
	if ($("#apremio-ckJuzgado").prop("checked"))
	{
		$("#apremio-dlJuzgado").removeAttr('disabled');
	} else {
		$("#apremio-dlJuzgado").attr('disabled', true);
	}
	
	if ($("#apremio-ckEtapa").prop("checked"))
	{
		$("#apremio-dlEtapa").removeAttr('disabled');
	} else {
		$("#apremio-dlEtapa").attr('disabled', true);
	}
	
	if ($("#apremio-ckMotivoDev").prop("checked"))
	{
		$("#apremio-dlMotivoDev").removeAttr('disabled');
	} else {
		$("#apremio-dlMotivoDev").attr('disabled', true);
	}

	if ($("#apremio-ckFechaMovimiento").prop("checked"))
	{
		$("#apremio-txFechaMovimientoDesde").removeAttr('disabled');
		$("#apremio-txFechaMovimientoHasta").removeAttr('disabled');
	} else {
		$("#apremio-txFechaMovimientoDesde").attr('disabled', true);
		$("#apremio-txFechaMovimientoHasta").attr('disabled',true);
	}
	
	if ($("#apremio-ckFechaModificacion").prop("checked"))
	{
		$("#apremio-txFechaModificacionDesde").removeAttr('disabled');
		$("#apremio-txFechaModificacionHasta").removeAttr('disabled');
	} else {
		$("#apremio-txFechaModificacionDesde").attr('disabled', true);
		$("#apremio-txFechaModificacionHasta").attr('disabled',true);
	}
}

function validaFiltros()
{
	
	var error = new Array(),  /* Esta variable almacenará los errores */
		datos = {},
		criterio = '',
		descr = '';
	
	datos.objetoDesde = $("#apremio-txObjetoDesde").val();
	datos.objetoHasta = $("#apremio-txObjetoHasta").val();
	datos.cod = $("#apremio-dlObjeto").val();
	
	//se cargan los códigos de objetos antes de enviar los datos
	$.pjax.reload(
	{
		container:"#NUMResp",
		method:"POST",
		data:{
			datos: datos,
		},
		
	});
	
	//cuando se terminan de cargar los códigos, se realizan las validaciones
	$("#NUMResp").on("pjax:end", function() {
	
		if ($("#apremio-ckJudi").prop("checked"))
		{
			if ($("#apremio-txJudiDesde").val() == "" || $("#apremio-txJudiHasta").val() == "")
			{
				error.push( "Debe ingresar el Rango de Nº de Judi." );
			} else if ($("#apremio-txJudiDesde").val() > $("#apremio-txJudiHasta").val()){
				
				error.push( "Rango de Nº de Judi mal ingresado." );
			} else {	
				if (criterio !== "") criterio += " and ";
				criterio += " judi_id >="+$("#apremio-txJudiDesde").val()+" and judi_id <= "+$("#apremio-txJudiHasta").val();	
				descr += " - Nº de Judi: Desde "+$("#apremio-txJudiDesde").val()+" - Hasta "+$("#apremio-txJudiHasta").val();
			}
		} 
		
		if ($("#apremio-ckObjeto").prop("checked"))
		{
			if ($("#apremio-txObjetoDesde").val() == "" || $("#apremio-txObjetoHasta").val() == "")
			{
				error.push( "Debe ingresar el Rango de Objeto." );
			} else if ($("#apremio-txObjetoDesde").val() > $("#apremio-txObjetoHasta").val()){
				
				error.push( "Rango de Objeto mal ingresado." );
			} else {	
				if (criterio !== "") criterio += " and ";
				criterio += "obj_id >='"+$("#apremio-txObjetoDesde").val()+"' AND obj_id <='"+$("#apremio-txObjetoHasta").val()+"'";	
				descr += " - El tipo de Objeto es "+($("#apremio-dlObjeto option[value="+$("#apremio-dlObjeto").val()+"]").text())+" - Código de Objeto: Desde "+$("#apremio-txObjetoDesde").val()+" - Hasta "+$("#apremio-txObjetoHasta").val();
			}
		}
		
		if ($("#apremio-ckExpediente").prop("checked"))
		{
			if ($("#apremio-txExpediente").val() == "")
			{
				error.push( "Ingrese un Expediente válido." );				
			} else {			
					if (criterio!=="") criterio += " and ";
					criterio += "expe like '%"+($("#apremio-txExpediente").val()).toUpperCase()+"%'";	
					descr += " - El nombre de Expediente Contiene: " + ($("#apremio-txExpediente").val()).toUpperCase();
			}
		} 
		
		if ($("#apremio-ckCaratula").prop("checked"))
		{
			if ($("#apremio-txCaratula").val() == "")
			{
				error.push( "Ingrese una Carátula válida." );				
			} else {			
					if (criterio!=="") criterio += " and ";
					criterio += " caratula like '%"+($("#apremio-txCaratula").val()).toUpperCase()+"%'";	
					descr += " - La Carátula Contiene: " + ($("#apremio-txCaratula").val()).toUpperCase();
			}
		} 
		
		if ($("#apremio-ckMontoDeuda").prop("checked"))
		{
			if ($("#apremio-txMontoDeudaDesde").val() == "" || $("#apremio-txMontoDeudaHasta").val() == "")
			{
				error.push( "Debe ingresar el Rango de MontoDeuda." );
			} else if ($("#apremio-txMontoDeudaDesde").val() > $("#apremio-txMontoDeudaHasta").val()){
				
				error.push( "Rango de Monto de Deuda mal ingresado." );
			} else {	
				if (criterio !== "") criterio += " and ";
				criterio += " nominal+accesor+multa+multa_omi >='"+$("#apremio-txMontoDeudaDesde").val()+"' AND nominal+accesor+multa+multa_omi <='"+$("#apremio-txMontoDeudaHasta").val()+"'";	
				descr += " - Monto de Deuda: Desde "+$("#apremio-txMontoDeudaDesde").val()+" - Hasta "+$("#apremio-txMontoDeudaHasta").val();
			}
		} 
		
		if ($("#apremio-ckGastos").prop("checked"))
		{
			if ($("#apremio-txGastosDesde").val() == "" || $("#apremio-txGastosHasta").val() == "")
			{
				error.push( "Debe ingresar el Rango de Gastos." );
			} else if ($("#apremio-txGastosDesde").val() > $("#apremio-txGastosHasta").val()){
				
				error.push( "Rango de Gastos mal ingresado." );
			} else {	
				if (criterio !== "") criterio += " and ";
				criterio += " gastos_jud >='"+$("#apremio-txGastosDesde").val()+"' AND gastos_jud <='"+$("#apremio-txGastosHasta").val()+"'";	
				descr += " - Gastos: Desde "+$("#apremio-txGastosDesde").val()+" - Hasta "+$("#apremio-txGastosHasta").val();
			}
		} 
		
		if ($("#apremio-ckHonorarios").prop("checked"))
		{
			if ($("#apremio-txHonorariosDesde").val() == "" || $("#apremio-txHonorariosHasta").val() == "")
			{
				error.push( "Debe ingresar el Rango de Honorarios." );
			} else if ($("#apremio-txHonorariosDesde").val() > $("#apremio-txHonorariosHasta").val()){
				
				error.push( "Rango de Honorarios mal ingresado." );
			} else {	
				if (criterio !== "") criterio += " and ";
				criterio += " hono_jud >='"+$("#apremio-txHonorariosDesde").val()+"' AND hono_jud <='"+$("#apremio-txHonorariosHasta").val()+"'";	
				descr += " - Honorarios: Desde "+$("#apremio-txHonorariosDesde").val()+" - Hasta "+$("#apremio-txHonorariosHasta").val();
			}
		} 
		
		if ($("#apremio-ckReparticion").prop("checked"))
		{
			if ($("#apremio-dlReparticion").val() == "")
			{
				error.push( "Ingrese una Repartición válida." );		
			} else {	
					if (criterio!=="") criterio += " and ";
					criterio += " rep = '"+$("#apremio-dlReparticion").val() + "'";	
					descr += " - La Reparticion es " + ($("#apremio-dlReparticion option[value="+$("#apremio-dlReparticion").val()+"]").text());
			}
		}
		
		if ($("#apremio-ckEstado").prop("checked"))
		{
			if ($("#apremio-dlEstado").val() == "")
			{
				error.push( "Ingrese un estado válido." );		
				
			} else {
					
					if (criterio!=="") criterio += " and ";
					criterio += " est ='"+$("#apremio-dlEstado").val()+"'";	
					descr += " - El estado es: " + ($("#apremio-dlEstado option[value="+$("#apremio-dlEstado").val()+"]").text());
			}
		}
		
		if ($("#apremio-ckProcurador").prop("checked"))
		{
			if ($("#apremio-dlProcurador").val() == "")
			{
				error.push( "Ingrese un procurador válido." );		
				
			} else {
					
					if (criterio!=="") criterio += " and ";
					criterio += " procurador ="+$("#apremio-dlProcurador").val();	
					descr += " - El Procurador es: " + ($("#apremio-dlProcurador option[value="+$("#apremio-dlProcurador").val()+"]").text());
			}
		}
		
		if ($("#apremio-ckJuzgado").prop("checked"))
		{
			if ($("#apremio-dlJuzgado").val() == "")
			{
				error.push( "Ingrese un juzgado válido." );		
				
			} else {
					
					if (criterio!=="") criterio += " and ";
					criterio += " juzgado ="+$("#apremio-dlJuzgado").val();	
					descr += " - El juzgado es: " + ($("#apremio-dlJuzgado option[value="+$("#apremio-dlJuzgado").val()+"]").text());
			}
		}
		
		if ($("#apremio-ckEtapa").prop("checked"))
		{
			if ($("#apremio-dlEtapa").val() == "")
			{
				error.push( "Ingrese una etapa válida." );		
				
			} else {
					
					if (criterio!=="") criterio += " AND ";
					criterio += " judi_id in (select judi_id from judi_etapa where etapa="+$("#apremio-dlEtapa").val()+")";	
					descr += " - La etapa es: " + ($("#apremio-dlEtapa option[value="+$("#apremio-dlEtapa").val()+"]").text());
			}
		}
		
		if ($("#apremio-ckMotivoDev").prop("checked"))
		{
			if ($("#apremio-dlMotivoDev").val() == "")
			{
				error.push( "Ingrese un motivo de devolución válido." );		
				
			} else {
					
					if (criterio!=="") criterio += " and ";
					criterio += " motivo_dev ="+$("#apremio-dlMotivoDev").val();	
					descr += " - El motivo de devolución es: " + ($("#apremio-dlMotivoDev option[value="+$("#apremio-dlMotivoDev").val()+"]").text());
			}
		}
		
		if ( $("#apremio-ckFechaMovimiento").prop("checked") )
		{
			if ( $("#apremio-txFechaMovimientoDesde").val() == "" || $("#apremio-txFechaMovimientoHasta").val() == "" )
			{
				error.push( "Complete los rangos de Fecha de Movimiento" );
			
			} else if (ValidarRangoFechaJs( $("#apremio-txFechaMovimientoDesde").val(), $("#apremio-txFechaMovimientoHasta").val() ) == 1 )
			{
				error.push( "Rango de Fecha de Movimiento mal ingresado" );
				
			} else {
				if (criterio!=="") criterio += " and ";
				criterio += " judi_id in (select judi_id from judi_etapa where fchmod::date between '"+$("#apremio-txFechaMovimientoDesde").val()+"' and '"+$("#apremio-txFechaMovimientoHasta").val()+"')";
				descr += " -Fecha de Movimiento desde "+$("#apremio-txFechaMovimientoDesde").val()+" hasta "+$("#apremio-txFechaMovimientoHasta").val();	
			}	
		}
		
		if ($("#apremio-ckFechaModificacion").prop("checked"))
		{
			if ($("#apremio-txFechaModificacionDesde").val() == "" || $("#apremio-txFechaModificacionHasta").val() == "")
			{
				error.push( "Complete los rangos de Fecha de Vencimiento" );
			
			} else if (ValidarRangoFechaJs($("#apremio-txFechaModificacionDesde").val(), $("#apremio-txFechaModificacionHasta").val()) == 1)
			{
				error.push( "Rango de Fecha de Modificación mal ingresado" );
			} else {
				if (criterio!=="") criterio += " and ";
				criterio += " fchmod::date between '"+$("#apremio-txFechaModificacionDesde").val()+"' AND '"+$("#apremio-txFechaModificacionHasta").val()+"'";
				descr += " -Fecha de Modificación desde "+$("#apremio-txFechaModificacionDesde").val()+" hasta "+$("#apremio-txFechaModificacionHasta").val();	
			}	
		}
	
		if ( criterio == '' && error.length == 0 ) 
			error.push( "No se encontraron condiciones de búsqueda." );
		
		if ( error.length == 0 )
		{
				$("#apremio-txCriterio").val(criterio);
				$("#apremio-txDescripcion").val(descr);
				$("#form-listadoApremio-list").submit();
		} else {
			
			mostrarErrores( error, "#apremio_list_errorSummary" );
		}
		
	});
		
}

filtrosApremio();

</script>