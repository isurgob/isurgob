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
$this->params['breadcrumbs'][] = ['label' => 'Declaraciones Juradas', 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Listado';
$this->params['breadcrumbs'][] = $title;

?>
<div class="listadoDDJJ-view" style="width: 75%">
	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'><?= Html::a('Volver', ['view'],['class' => 'btn btn-primary']) ?></td>
		</tr>
	</table>

	<?php

	$form = ActiveForm::begin([
		'id' => 'form-listadoDDJJ-list',
		'action' => ['listado']

	]);

	echo "<input type='text' name='txCriterio' id='ddjj_txCriterio' style='display:none'>";
	echo "<input type='text' name='txDescripcion' id='ddjj_txDescripcion' style='display:none'>";

	//Obtener los códigos de objetos
	Pjax::begin(['id'=>'NUMResp']);

		$datos = Yii::$app->request->post( 'datos', [] );

		if ( count( $datos ) > 0 )
		{
			$cod = $datos['cod'];
			$objDesde = $datos['objetoDesde'];
			$objHasta = $datos['objetoHasta'];

			if (strlen($objDesde) < 8 and $objDesde != "")
			{
				$objDesde = utb::GetObjeto($cod,$objDesde);
			}

			if (strlen($objHasta) < 8 and $objHasta != "")
			{
				$objHasta = utb::GetObjeto($cod,$objHasta);
			}

			echo '<script>$("#ddjj_txObjetoDesde").val("'.$objDesde.'")</script>';
			echo '<script>$("#ddjj_txObjetoHasta").val("'.$objHasta.'")</script>';
		}

	Pjax::end();

?>

<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
	<table border="0">
		<tr>
			<td width="150px"><?= Html::checkbox('ckNumDDJJ',false, ['id' => 'ddjj_ckNumDDJJ','label' => 'Nº DDJJ:', 'onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txNumDDJJDesde', null, ['id' => 'ddjj_txNumDDJJDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
			<td width="20px"></<td>
			<td width="45px"><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txNumDDJJHasta', null, ['id' => 'ddjj_txNumDDJJHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckObjeto',false, ['id' => 'ddjj_ckObjeto','label' => 'Objeto:', 'onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="120px"><?= Html::dropDownList('dlObjeto', null, utb::getAux('objeto_tipo','cod','nombre',0,"est='A'"), ['id'=>'ddjj_dlObjeto', 'style'=>'width:125px', 'class' => 'form-control','onchange'=>'objeto()']); ?></td>
			<td width="20px"></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td>
				<?= Html::input('text', 'txObjetoDesde', null, [
						'id' => 'ddjj_txObjetoDesde',
						'maxlength'=>'8',
						'class' => 'form-control',
						'style'=>'width:80px',
					]);
				?>
			</td>
			<td width="5px"></<td>
			<td width="45px"><label>Hasta&nbsp;</label></td>
			<td>
				<?= Html::input('text', 'txObjetoHasta', null, [
						'id' => 'ddjj_txObjetoHasta',
						'maxlength'=>'8',
						'class' => 'form-control',
						'style'=>'width:80px',
					]);
				?>
			</td>
		</tr>
	</table>

	<table border="0">
		<tr>
			<td width="150px"><?= Html::checkbox('ckBaseImp',false, ['id' => 'ddjj_ckBaseImp','label' => 'Base Imponible:', 'onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txBaseImpDesde', null, ['id' => 'ddjj_txBaseImpDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
			<td width="20px"></<td>
			<td width="45px"><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txBaseImpHasta', null, ['id' => 'ddjj_txBaseImpHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckMonto',false, ['id' => 'ddjj_ckMonto','label' => 'Monto:','onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txMontoDesde', null, ['id' => 'ddjj_txMontoDesde', 'style'=>'width:80px', 'class' => 'form-control']); ?></td><td></<td>
			<td width="20px"></<td>
			<td width="45px"><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txMontoHasta', null, ['id' => 'ddjj_txMontoHasta', 'style'=>'width:80px', 'class' => 'form-control']); ?></td><td></<td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckPeriodo',false, ['id' => 'ddjj_ckPeriodo','label' => 'Per&iacute;odos:','onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td>
				<?= Html::input('text', 'txAnioDesde', null, ['id' => 'ddjj_txAnioDesde', 'style'=>'width:45px', 'class' => 'form-control']); ?>
				<?= Html::input('text', 'txCuotaDesde', null, ['id' => 'ddjj_txCuotaDesde', 'style'=>'width:30px', 'class' => 'form-control']); ?>
			<td>
			<td width="20px"></td>
			<td width="45px"><label>Hasta&nbsp;</label></td>
			<td>
				<?= Html::input('text', 'txAnioHasta', null, ['id' => 'ddjj_txAnioHasta', 'style'=>'width:45px', 'class' => 'form-control']); ?>
				<?= Html::input('text', 'txCuotaHasta', null, ['id' => 'ddjj_txCuotaHasta', 'style'=>'width:30px', 'class' => 'form-control']); ?>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckComercio',false, ['id' => 'ddjj_ckComercio', 'label' => 'Nombre Comercio:','onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="400px"><?= Html::input('text', 'txComercio', null, ['id' => 'ddjj_txComercio','style'=>'width:275px; text-transform: uppercase', 'maxlength'=>'50', 'class' => 'form-control']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckTitComercio',false, ['id' => 'ddjj_ckTitComercio', 'label' => 'Titular Comercio:','onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="400px"><?= Html::input('text', 'txTitComercio', null, ['id' => 'ddjj_txTitComercio','style'=>'width:275px; text-transform: uppercase', 'maxlength'=>'50', 'class' => 'form-control']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckTributo',false, ['id' => 'ddjj_ckTributo','label' => 'Tributo Principal:', 'onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="120px">
				<?= Html::dropDownList('dlTributo', null, utb::getAux('trib','trib_id','nombre',0,"tipo = 2 AND dj_tribprinc = trib_id AND est = 'A'"), [
						'id'=>'ddjj_dlTributo', 'style'=>'width:275px', 'class' => 'form-control','onchange'=>'objeto()']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckContador',false, ['id' => 'ddjj_ckContador','label' => 'Contador Comercio:', 'onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="120px"><?= Html::dropDownList('dlContador', null, utb::getAux('comer_tcontador'), ['id'=>'ddjj_dlContador', 'style'=>'width:125px', 'class' => 'form-control','onchange'=>'objeto()']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckFechaPresentacion',false, ['id' => 'ddjj_ckFechaPresentacion','label' => 'Fecha Presentación:','onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td width="80px"><?=
								DatePicker::widget(
									[
										'id' => 'ddjj_txFechaPresentacionDesde',
										'name' => 'txFechaPresentacionDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
									]
								);
							?></td>
			<td width="20px"></td>
			<td width="45px"><label>Hasta&nbsp;</label></td>
			<td width="80px"><?=
								DatePicker::widget(
									[
										'id' => 'ddjj_txFechaPresentacionHasta',
										'name' => 'txFechaPresentacionHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px;', 'class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckTipo',false, ['id' => 'ddjj_ckTipo','label' => 'Tipo:', 'onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="120px">
				<?= Html::dropDownList('dlTipo', null, utb::getAux('ddjj_tipo'), [
						'id'=>'ddjj_dlTipo',
						'style'=>'width:125px',
						'class' => 'form-control',
					]);
				?>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckEstado',false, ['id' => 'ddjj_ckEstado','label' => 'Estado DJ:', 'onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="120px"><?= Html::dropDownList('dlEstado', null, utb::getAux('ddjj_test'), ['id'=>'ddjj_dlEstado', 'style'=>'width:125px', 'class' => 'form-control']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckEstadoCtaCte',false, ['id' => 'ddjj_ckEstadoCtaCte','label' => 'Estado CtaCte:', 'onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="120px"><?= Html::dropDownList('dlEstadoCtaCte', null, utb::getAux('ctacte_test'), ['id'=>'ddjj_dlEstadoCtaCte', 'style'=>'width:125px', 'class' => 'form-control']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td width="150px"><?= Html::checkbox('ckFechaPago',false, ['id' => 'ddjj_ckFechaPago','label' => 'Fecha de Pago','onchange' => 'filtrosDDJJ()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td width="80px"><?=
								DatePicker::widget(
									[
										'id' => 'ddjj_txFechaPagoDesde',
										'name' => 'txFechaPagoDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
									]
								);
							?></td>
			<td width="20px"></td>
			<td width="45px"><label>Hasta&nbsp;</label></td>
			<td width="80px"><?=
								DatePicker::widget(
									[
										'id' => 'ddjj_txFechaPagoHasta',
										'name' => 'txFechaPagoHasta',
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
			<td><?= Html::checkbox('ckFiscaliza',false, ['id' => 'ddjj_ckFiscaliza','label' => 'Fiscalizada','onchange' => 'filtrosDDJJ()']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td><?= Html::checkbox('ckAtrasada',false, ['id' => 'ddjj_ckAtrasada','label' => 'Presentación atrasada','onchange' => 'filtrosDDJJ()']); ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td><?= Html::checkbox('ckBonificada',false, ['id' => 'ddjj_ckBonificada','label' => 'Con bonificación','onchange' => 'filtrosDDJJ()']); ?></td>
		</tr>
	</table>

	</div>

	<table width="100%">
		<tr>
			<td><?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'ejecutarPjax();']) ?></td>
		</tr>
	</table>

	<?php

	ActiveForm::end();
	?>



	<div id="listadoDDJJ_errorSummary" class="error-summary" style="display:none;margin-top: 8px">

		<ul>
		</ul>

	</div>

</div>

<script type="text/javascript">

function objeto()
{
	$("#ddjj_txObjetoDesde").val('');
	$("#ddjj_txObjetoHasta").val('');
}

function filtrosDDJJ()
{
	if ($("#ddjj_ckNumDDJJ").prop("checked"))
	{
		$("#ddjj_txNumDDJJDesde").removeAttr('disabled');
		$("#ddjj_txNumDDJJHasta").removeAttr('disabled');
	} else {
		$("#ddjj_txNumDDJJDesde").attr('disabled', true);
		$("#ddjj_txNumDDJJHasta").attr('disabled',true);
	}

	if ($("#ddjj_ckObjeto").prop("checked"))
	{
		$("#ddjj_dlObjeto").removeAttr('disabled');
		$("#ddjj_txObjetoDesde").removeAttr('disabled');
		$("#ddjj_txObjetoHasta").removeAttr('disabled');
	} else {
		$("#ddjj_dlObjeto").attr('disabled', true);
		$("#ddjj_txObjetoDesde").attr('disabled', true);
		$("#ddjj_txObjetoHasta").attr('disabled',true);
	}

	if ($("#ddjj_ckBaseImp").prop("checked"))
	{
		$("#ddjj_txBaseImpDesde").removeAttr('disabled');
		$("#ddjj_txBaseImpHasta").removeAttr('disabled');
	} else {
		$("#ddjj_txBaseImpDesde").attr('disabled', true);
		$("#ddjj_txBaseImpHasta").attr('disabled',true);
	}

	if ($("#ddjj_ckMonto").prop("checked"))
	{
		$("#ddjj_txMontoDesde").removeAttr('disabled');
		$("#ddjj_txMontoHasta").removeAttr('disabled');
	} else {
		$("#ddjj_txMontoDesde").attr('disabled', true);
		$("#ddjj_txMontoHasta").attr('disabled',true);
	}

	if ($("#ddjj_ckPeriodo").prop("checked"))
	{
		$("#ddjj_txAnioDesde").removeAttr('disabled');
		$("#ddjj_txCuotaDesde").removeAttr('disabled');
		$("#ddjj_txCuotaHasta").removeAttr('disabled');
		$("#ddjj_txAnioHasta").removeAttr('disabled');
	} else {
		$("#ddjj_txAnioDesde").attr('disabled', true);
		$("#ddjj_txCuotaDesde").attr('disabled',true);
		$("#ddjj_txCuotaHasta").attr('disabled', true);
		$("#ddjj_txAnioHasta").attr('disabled',true);
	}

	if ($("#ddjj_ckComercio").prop("checked"))
	{
		$("#ddjj_txComercio").removeAttr('disabled');
	} else {
		$("#ddjj_txComercio").attr('disabled', true);
	}

	if ($("#ddjj_ckTitComercio").prop("checked"))
	{
		$("#ddjj_txTitComercio").removeAttr('disabled');
	} else {
		$("#ddjj_txTitComercio").attr('disabled', true);
	}

	if ($("#ddjj_ckTributo").prop("checked"))
	{
		$("#ddjj_dlTributo").removeAttr('disabled');
	} else {
		$("#ddjj_dlTributo").attr('disabled', true);
	}

	if ($("#ddjj_ckContador").prop("checked"))
	{
		$("#ddjj_dlContador").removeAttr('disabled');
	} else {
		$("#ddjj_dlContador").attr('disabled', true);
	}

	if ($("#ddjj_ckFechaPresentacion").prop("checked"))
	{
		$("#ddjj_txFechaPresentacionDesde").removeAttr('disabled');
		$("#ddjj_txFechaPresentacionHasta").removeAttr('disabled');
	} else {
		$("#ddjj_txFechaPresentacionDesde").attr('disabled', true);
		$("#ddjj_txFechaPresentacionHasta").attr('disabled',true);
	}

	if ($("#ddjj_ckTipo").prop("checked"))
	{
		$("#ddjj_dlTipo").removeAttr('disabled');
	} else {
		$("#ddjj_dlTipo").attr('disabled', true);
	}

	if ($("#ddjj_ckEstado").prop("checked"))
	{
		$("#ddjj_dlEstado").removeAttr('disabled');
	} else {
		$("#ddjj_dlEstado").attr('disabled', true);
	}

	if ($("#ddjj_ckEstadoCtaCte").prop("checked"))
	{
		$("#ddjj_dlEstadoCtaCte").removeAttr('disabled');
	} else {
		$("#ddjj_dlEstadoCtaCte").attr('disabled', true);
	}

	if ($("#ddjj_ckFechaPago").prop("checked"))
	{
		$("#ddjj_txFechaPagoDesde").removeAttr('disabled');
		$("#ddjj_txFechaPagoHasta").removeAttr('disabled');
	} else {
		$("#ddjj_txFechaPagoDesde").attr('disabled', true);
		$("#ddjj_txFechaPagoHasta").attr('disabled',true);
	}
}

function ejecutarPjax()
{
	var datos = {};

	datos.objetoDesde = $("#ddjj_txObjetoDesde").val();
	datos.objetoHasta = $("#ddjj_txObjetoHasta").val();
	datos.cod = $("#ddjj_dlObjeto").val();

	//se cargan los códigos de objetos antes de enviar los datos
	$.pjax.reload({
		container: "#NUMResp",
		method: "POST",
		data: {
			datos: datos,
		},
	});

}

//cuando se terminan de cargar los códigos, se realizan las validaciones.
$("#NUMResp").on("pjax:end", function() {

	validaFiltros()

});

function validaFiltros()
{

	var error = '',  /* Esta variable almacenará los errores */
		criterio = '',
		descr = '',
		error = new Array();

	if ($("#ddjj_ckNumDDJJ").prop("checked"))
	{
		if ($("#ddjj_txNumDDJJDesde").val() == "" || $("#ddjj_txNumDDJJHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Nº de DDJJ." );
		} else if ($("#ddjj_txNumDDJJDesde").val() > $("#ddjj_txNumDDJJHasta").val()){

			error.push( "Rango de Nº de DDJJ mal ingresado." );
		} else {
			if (criterio !== "") criterio += " and ";
			criterio += " dj_id >="+$("#ddjj_txNumDDJJDesde").val()+" AND dj_id <= "+$("#ddjj_txNumDDJJHasta").val();
			descr += " - Nº de DDJJ: Desde "+$("#ddjj_txNumDDJJDesde").val()+" - Hasta "+$("#ddjj_txNumDDJJHasta").val();
		}
	}

	if ($("#ddjj_ckObjeto").prop("checked"))
	{
		if ($("#ddjj_txObjetoDesde").val() == "" || $("#ddjj_txObjetoHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Objeto." );
		} else if ($("#ddjj_txObjetoDesde").val() > $("#ddjj_txObjetoHasta").val()){

			error.push( "Rango de Objeto mal ingresado." );
		} else {
			if (criterio !== "") criterio += " and ";
			criterio += "obj_id BETWEEN'"+$("#ddjj_txObjetoDesde").val()+"' AND '"+$("#ddjj_txObjetoHasta").val()+"'";
			descr += " - El tipo de Objeto es "+($("#ddjj_dlObjeto option[value="+$("#ddjj_dlObjeto").val()+"]").text())+" - Código de Objeto: Desde "+$("#ddjj_txObjetoDesde").val()+" - Hasta "+$("#ddjj_txObjetoHasta").val();
		}
	}

	if ($("#ddjj_ckBaseImp").prop("checked"))
	{
		if ($("#ddjj_txBaseImpDesde").val() == "" || $("#ddjj_txBaseImpHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Base Imponible." );
		} else if ($("#ddjj_txBaseImpDesde").val() > $("#ddjj_txBaseImpHasta").val()){

			error.push( "Rango de Base Imponible mal ingresado." );
		} else {
			if (criterio !== "") criterio += " and ";
			criterio += " base >="+$("#ddjj_txBaseImpDesde").val()+" AND base <= "+$("#ddjj_txBaseImpHasta").val();
			descr += " - Base Imponible: Desde "+$("#ddjj_txBaseImpDesde").val()+" - Hasta "+$("#ddjj_txBaseImpHasta").val();
		}
	}

	if ($("#ddjj_ckMonto").prop("checked"))
	{
		if ($("#ddjj_txMontoDesde").val() == "" || $("#ddjj_txMontoHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Monto." );
		} else if ($("#ddjj_txMontoDesde").val() > $("#ddjj_txMontoHasta").val()){

			error.push( "Rango de Monto mal ingresado." );
		} else {
			if (criterio !== "") criterio += " and ";
			criterio += " monto >='"+$("#ddjj_txMontoDesde").val()+"' AND monto <='"+$("#ddjj_txMontoHasta").val()+"'";
			descr += " - Monto: Desde "+$("#ddjj_txMontoDesde").val()+" - Hasta "+$("#ddjj_txMontoHasta").val();
		}
	}

	if ($("#ddjj_ckPeriodo").prop("checked"))
	{
		if ($("#ddjj_txAnioDesde").val() == '' || $("#ddjj_txCuotaDesde").val() == '' || $("#ddjj_txAnioHasta").val() == '' || $("#ddjj_txCuotaHasta").val() == '')
			error += 'Complete los valores para período.';

		var perdesde = (parseInt($("#ddjj_txAnioDesde").val()) * 1000) + parseInt($("#ddjj_txCuotaDesde").val());
		var perhasta = (parseInt($("#ddjj_txAnioHasta").val()) * 1000) + parseInt($("#ddjj_txCuotaHasta").val());

		if (perdesde > perhasta)
		{
			error.push( "Período mal ingresado" );
		} else
		{
			if (criterio!=="") criterio += " and ";
			criterio += " anio*1000+cuota >='"+perdesde+"' AND anio*1000+cuota <='"+perhasta+"'";
			descr += " -Período desde "+perdesde+" hasta "+perhasta;
		}
	}

	if ($("#ddjj_ckComercio").prop("checked"))
	{
		if ($("#ddjj_txComercio").val() == "")
		{
			error.push( "Ingrese un Comercio válido." );
		} else {
				if (criterio!=="") criterio += " and ";
				criterio += " obj_nom like '%"+($("#ddjj_txComercio").val()).toUpperCase()+"%'";
				descr += " - El nombre de Comercio Contiene: " + ($("#ddjj_txComercio").val()).toUpperCase();
		}
	}

	if ($("#ddjj_ckTitComercio").prop("checked"))
	{
		if ($("#ddjj_txTitComercio").val() == "")
		{
			error.push( "Ingrese un Titutal de Comercio válido." );
		} else {
				if (criterio!=="") criterio += " and ";
				criterio += " num_nom like '%"+($("#ddjj_txTitComercio").val()).toUpperCase()+"%'";
				descr += " - El nombre de Titular de Comercio Contiene: " + ($("#ddjj_txTitComercio").val()).toUpperCase();
		}
	}

	if ($("#ddjj_ckTributo").prop("checked"))
	{
		if (criterio!=="") criterio += " and ";
		criterio += " trib_id = "+($("#ddjj_dlTributo").val());
		descr += " - El tributo es: " + ($("#ddjj_dlTributo option[value="+$("#ddjj_dlTributo").val()+"]").text());
	}

	if ($("#ddjj_ckContador").prop("checked"))
	{
		if (criterio!=="") criterio += " and ";
		criterio += " trib_id = "+($("#ddjj_dlContador").val());
		descr += " - El contador es: " + ($("#ddjj_dlContador option[value="+$("#ddjj_dlContador").val()+"]").text());
	}

	/* Rubro */


	if ($("#ddjj_ckFechaPresentacion").prop("checked"))
	{
		if ( $("#ddjj_txFechaPresentacionDesde").val() == "" || $("#ddjj_txFechaPresentacionHasta").val() == "" )
		{
			error.push( "Complete los rangos de Fecha de Presentación" );

		} else if (ValidarRangoFechaJs($("#ddjj_txFechaPresentacionDesde").val(), $("#ddjj_txFechaPresentacionHasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Presentación mal ingresado" );

		} else {
			if (criterio!=="")
				criterio += " and ";

			criterio += " fchpresenta::date BETWEEN '"+$("#ddjj_txFechaPresentacionDesde").val()+"' AND '"+$("#ddjj_txFechaPresentacionHasta").val()+"'";
			descr += " -Fecha de Presentación desde "+$("#ddjj_txFechaPresentacionDesde").val()+" hasta "+$("#ddjj_txFechaPresentacionHasta").val();
		}
	}

	if ($("#ddjj_ckFiscaliza").prop("checked"))
	{
		if (criterio!=="") criterio += " and ";
			criterio += " fiscaliza=1";
			descr += " Fisaliza";
	}

	if ($("#ddjj_ckAtrasada").prop("checked"))
	{
		if (criterio!=="") criterio += " and ";
			criterio += " fchpresenta > fchvenc";
			descr += " Presentación Atrasada";
	}

	if ($("#ddjj_ckBonificada").prop("checked"))
	{
		if (criterio!=="") criterio += " and ";
			criterio += " ctacte_id IN (select ctacte_id from ctacte_det c inner join cuenta t ON t.cta_id = c.cta_id WHERE t.tcta = 2)";
			descr += "Con bonificación";
	}

	if ($("#ddjj_ckTipo").prop("checked"))
	{
		if ($("#ddjj_dlTipo").val() == "")
		{
			error.push( "Ingrese un tipo válido." );

		} else {

				if (criterio!=="") criterio += " and ";
				criterio += " tipo ='"+$("#ddjj_dlTipo").val()+"'";
				descr += " - El tipo es: " + ($("#ddjj_dlTipo option[value="+$("#ddjj_dlTipo").val()+"]").text());
		}
	}

	if ($("#ddjj_ckEstado").prop("checked"))
	{
		if ($("#ddjj_dlEstado").val() == "")
		{
			error.push( "Ingrese un estado válido." );

		} else {

				if (criterio!=="") criterio += " and ";
				criterio += " est ='"+$("#ddjj_dlEstado").val()+"'";
				descr += " - El estado es: " + ($("#ddjj_dlEstado option[value="+$("#ddjj_dlEstado").val()+"]").text());
		}
	}

	if ($("#ddjj_ckEstadoCtaCte").prop("checked"))
	{
		if ($("#ddjj_dlEstado").val() == "")
		{
			error.push( "Ingrese un estado válido." );

		} else {

				if (criterio!=="") criterio += " and ";
				criterio += " estctacte ='"+$("#ddjj_dlEstadoCtaCte").val()+"'";
				descr += " - El estado de la Cta Cte es: " + ($("#ddjj_dlEstadoCtaCte option[value="+$("#ddjj_dlEstadoCtaCte").val()+"]").text());
		}
	}

	if ($("#ddjj_ckFechaPago").prop("checked"))
	{
		if ( $("#ddjj_txFechaPagoDesde").val() == "" || $("#ddjj_txFechaPagoHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Pago" );

		} else if ( ValidarRangoFechaJs($("#ddjj_txFechaPagoDesde").val(), $("#ddjj_txFechaPagoHasta").val()) == 1 )
		{
			error.push( "Rango de Fecha de Pago mal ingresado" );

		} else {
			if (criterio!=="")
				criterio += " and ";

			criterio += " fchimputa::date BETWEEN '"+$("#ddjj_txFechaPagoDesde").val()+"' AND '"+$("#ddjj_txFechaPagoHasta").val()+"'";
			descr += " -Fecha de Pago desde "+$("#ddjj_txFechaPagoDesde").val()+" hasta "+$("#ddjj_txFechaPagoHasta").val();
		}
	}

	if ( criterio == '' && error.length == 0 )
		error.push( "No se encontraron condiciones de búsqueda." );

	if ( error.length == 0 )
	{
		$("#ddjj_txCriterio").val(criterio);
		$("#ddjj_txDescripcion").val(descr);
		$("#form-listadoDDJJ-list").submit();

	} else {

		mostrarErrores( error, "#listadoDDJJ_errorSummary" );
	}
}

$(document).ready(function() {

	filtrosDDJJ();

});


</script>
