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

$title = 'Listado de Compensaciones';
$this->params['breadcrumbs'][] = $title;
?>
<div class="listadoCompensa-view" style='width:75%;'>
	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'><?= Html::a('Volver', ['view'],['class' => 'btn btn-primary']) ?></td>
		</tr>
	</table>

	<?php

	$form = ActiveForm::begin([
		'id' => 'form-listadoCompensa-list',
		'action' => ['listado']

	]);



	Pjax::begin(['id'=>'NUMResp']);

		//Obtengo los datos
		$cod = Yii::$app->request->post('cod','');
		$obj = Yii::$app->request->post('objeto','');

		if (strlen($obj) < 8 && $obj != '')
		{
			$obj = utb::GetObjeto($cod,$obj);
			echo '<script>$("#compensa_txObjeto").val("'.$obj.'")</script>';
		} else
		{
			echo '<script>$("#compensa_txObjeto").val("'.$obj.'")</script>';
		}

	Pjax::end();

?>

	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>

	<input type='text' name='txCriterio' id='compensa_txCriterio' style='display:none'>
	<input type='text' name='txDescripcion' id='compensa_txDescripcion' style='display:none'>


	<table border="0">
		<tr>
			<td width="100px"><?= Html::checkbox('ckCodCompensa',false, ['id' => 'compensa_ckCodCompensa','label' => 'Código:', 'onchange' => 'filtrosCompensa()']); ?></td>
			<td width="45px"><label>Desde&nbsp;</label></td>
			<td><?= Html::input('text', 'txNumCompensaDesde', null, ['id' => 'compensa_txNumCompensaDesde', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
			<td width="5px"></<td>
			<td width="45px"><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txNumCompensaHasta', null, ['id' => 'compensa_txNumCompensaHasta','maxlength'=>'8', 'class' => 'form-control', 'style'=>'width:80px','onkeypress'=>'return justNumbers(event)']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckExpe',false, ['id' => 'compensa_ckExpe', 'label' => 'Expediente:','onchange' => 'filtrosCompensa()']); ?></td>
			<td colspan="2"><?= Html::input('text', 'txExpe', null, ['id' => 'compensa_txExpe','style'=>'width:125px; text-transform: uppercase', 'maxlength'=>'50', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckTipo',false, ['id' => 'compensa_ckTipo','label' => 'Tipo', 'onchange' => 'filtrosCompensa()']); ?></td>
			<td colspan="2"><?= Html::dropDownList('dlTipo', null, utb::getAux('comp_tipo'), ['id'=>'compensa_dlTipo', 'style'=>'width:125px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckTributo',false, ['id' => 'compensa_ckTributo','label' => 'Tributo:', 'onchange' => 'filtrosCompensa()']); ?></td>
			<td colspan="5"><?= Html::dropDownList('dlTributo', null, utb::getAux('trib','trib_id','nombre',0,"est='A' AND compensa = 1"), ['id'=>'compensa_dlTributo', 'style'=>'width:200px', 'class' => 'form-control','onchange'=>'objeto()']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckObjeto',false, ['id' => 'compensa_ckObjeto','label' => 'Objeto:', 'onchange' => 'filtrosCompensa()']); ?></td>
			<td colspan="2">
			<label>Tipo&nbsp;</label>
			<?= Html::dropDownList('dlObjeto', null, utb::getAux('objeto_tipo','cod','nombre',0,"est='A'"), ['id'=>'compensa_dlObjeto', 'style'=>'width:93px', 'class' => 'form-control','onchange'=>'objeto()']); ?>
			</td>
			<td></td>
			<td><label>Objeto&nbsp;</label></td><td><?= Html::input('text', 'txObjeto', null, ['id' => 'compensa_txObjeto', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px','onchange'=>'codObjeto()']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckEstado',false, ['id' => 'compensa_ckEstado','label' => 'Estado:', 'onchange' => 'filtrosCompensa()']); ?></td>
			<td colspan="2"><?= Html::dropDownList('dlEstado', null, utb::getAux('comp_test'), ['id'=>'compensa_dlEstado', 'style'=>'width:125px', 'class' => 'form-control']); ?></td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckMonto',false, ['id' => 'compensa_ckMonto','label' => 'Monto:','onchange' => 'filtrosCompensa()']); ?></td>
			<td><label>Desde&nbsp;</label></td><td><?= Html::input('text', 'txMontoDesde', null, ['id' => 'compensa_txMontoDesde', 'style'=>'width:80px', 'class' => 'form-control']); ?></td>
			<td></<td>
			<td><label>Hasta&nbsp;</label></td><td><?= Html::input('text', 'txMontoHasta', null, ['id' => 'compensa_txMontoHasta', 'style'=>'width:80px', 'class' => 'form-control']); ?></td><td></<td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckFechaAlta',false, ['id' => 'compensa_ckFechaAlta','label' => 'Fecha Alta:','onchange' => 'filtrosCompensa()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?=
								DatePicker::widget(
									[
										'id' => 'compensa_txFechaAltaDesde',
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
										'id' => 'compensa_txFechaAltaHasta',
										'name' => 'txFechaAltaHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px;', 'class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckFechaAplica',false, ['id' => 'compensa_ckFechaAplica','label' => 'Fecha Aplica:','onchange' => 'filtrosCompensa()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?=
								DatePicker::widget(
									[
										'id' => 'compensa_txFechaAplicaDesde',
										'name' => 'txFechaAplicaDesde',
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
										'id' => 'compensa_txFechaAplicaHasta',
										'name' => 'txFechaAplicaHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>

		<tr>
			<td><?= Html::checkbox('ckFechaBaja',false, ['id' => 'compensa_ckFechaBaja','label' => 'Fecha Baja:','onchange' => 'filtrosCompensa()']); ?></td>
			<td><label>Desde&nbsp;</label></td>
			<td><?=
								DatePicker::widget(
									[
										'id' => 'compensa_txFechaBajaDesde',
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
										'id' => 'compensa_txFechaBajaHasta',
										'name' => 'txFechaBajaHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'options' => ['disabled'=>'true', 'style' => 'width:80px; align:right','class' => 'form-control'],
									]
								);
							?>
			</td>
		</tr>
	</table>

	</div>

	<?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'validaFiltros();']); ?>

	<div id="compensa_errorSummary" class="error-summary" style="display:none;margin-top: 8px;">

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
	$("#compensa_txObjeto").val('');
}

function codObjeto()
{
	$.pjax.reload(
		{
			container:"#NUMResp",
			data:{
					objeto:$("#compensa_txObjeto").val(),
					cod:$("#compensa_dlObjeto").val()},
			method:"POST",
		}
	)
};


function filtrosCompensa()
{
	if ($("#compensa_ckCodCompensa").prop("checked"))
	{
		$("#compensa_txNumCompensaDesde").removeAttr('disabled');
		$("#compensa_txNumCompensaHasta").removeAttr('disabled');
	} else {
		$("#compensa_txNumCompensaDesde").attr('disabled', true);
		$("#compensa_txNumCompensaHasta").attr('disabled',true);
	}

	if ($("#compensa_ckExpe").prop("checked"))
	{
		$("#compensa_txExpe").removeAttr('disabled');
	} else {
		$("#compensa_txExpe").attr('disabled', true);
	}

	if ($("#compensa_ckTipo").prop("checked"))
	{
		$("#compensa_dlTipo").removeAttr('disabled');
	} else {
		$("#compensa_dlTipo").attr('disabled', true);
	}

	if ($("#compensa_ckTributo").prop("checked"))
	{
		$("#compensa_dlTributo").removeAttr('disabled');
	} else {
		$("#compensa_dlTributo").attr('disabled', true);
	}

	if ($("#compensa_ckObjeto").prop("checked"))
	{
		$("#compensa_dlObjeto").removeAttr('disabled');
		$("#compensa_txObjeto").removeAttr('disabled');
	} else {
		$("#compensa_dlObjeto").attr('disabled', true);
		$("#compensa_txObjeto").attr('disabled', true);
	}

	if ($("#compensa_ckEstado").prop("checked"))
	{
		$("#compensa_dlEstado").removeAttr('disabled');
	} else {
		$("#compensa_dlEstado").attr('disabled', true);
	}

	if ($("#compensa_ckMonto").prop("checked"))
	{
		$("#compensa_txMontoDesde").removeAttr('disabled');
		$("#compensa_txMontoHasta").removeAttr('disabled');
	} else {
		$("#compensa_txMontoDesde").attr('disabled', true);
		$("#compensa_txMontoHasta").attr('disabled',true);
	}

	if ($("#compensa_ckFechaAlta").prop("checked"))
	{
		$("#compensa_txFechaAltaDesde").removeAttr('disabled');
		$("#compensa_txFechaAltaHasta").removeAttr('disabled');
	} else {
		$("#compensa_txFechaAltaDesde").attr('disabled', true);
		$("#compensa_txFechaAltaHasta").attr('disabled',true);
	}

	if ($("#compensa_ckFechaAplica").prop("checked"))
	{
		$("#compensa_txFechaAplicaDesde").removeAttr('disabled');
		$("#compensa_txFechaAplicaHasta").removeAttr('disabled');
	} else {
		$("#compensa_txFechaAplicaDesde").attr('disabled', true);
		$("#compensa_txFechaAplicaHasta").attr('disabled',true);
	}

	if ($("#compensa_ckFechaBaja").prop("checked"))
	{
		$("#compensa_txFechaBajaDesde").removeAttr('disabled');
		$("#compensa_txFechaBajaHasta").removeAttr('disabled');
	} else {
		$("#compensa_txFechaBajaDesde").attr('disabled', true);
		$("#compensa_txFechaBajaHasta").attr('disabled',true);
	}
}

function validaFiltros()
{

	var error = new Array(),  /* Esta variable almacenará los errores */
		criterio = '',
		descr = '';

	if ($("#compensa_ckCodCompensa").prop("checked"))
	{
		if ($("#compensa_txNumCompensaDesde").val() == "" || $("#compensa_txNumCompensaHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Código." );
		} else if ($("#compensa_txNumCompensaDesde").val() > $("#compensa_txNumCompensaHasta").val()){

			error.push( "Rango de Código mal ingresado." );
		} else {
			if (criterio !== "") criterio += " and ";
			criterio += " comp_id >="+$("#compensa_txNumCompensaDesde").val()+" AND comp_id <= "+$("#compensa_txNumCompensaHasta").val();
			descr += " - Código: Desde "+$("#compensa_txNumCompensaDesde").val()+" - Hasta "+$("#compensa_txNumCompensaHasta").val();
		}
	}

	if ($("#compensa_ckExpe").prop("checked"))
	{
		if ($("#compensa_txExpe").val() == "")
		{
			error.push( "Ingrese un Expediente válido." );
		} else {
				if (criterio!=="") criterio += " and ";
				criterio += " expe like '%"+($("#compensa_txExpe").val()).toUpperCase()+"%'";
				descr += " - El Expediente Contiene: " + ($("#compensa_txExpe").val()).toUpperCase();
		}
	}

	if ($("#compensa_ckTipo").prop("checked"))
	{
		if ($("#compensa_dlTipo").val() == "")
		{
			error.push( "Ingrese un tipo válido." );

		} else {

				if (criterio!=="") criterio += " and ";
				criterio += " tipo ='"+$("#compensa_dlTipo").val()+"'";
				descr += " - El tipo es: " + ($("#compensa_dlTipo option[value="+$("#compensa_dlTipo").val()+"]").text());
		}
	}

	if ($("#compensa_ckTributo").prop("checked"))
	{
		if (criterio!=="") criterio += " and ";
		criterio += " trib_ori = "+($("#compensa_dlTributo").val()) + " OR trib_dest = "+ ($("#compensa_dlTributo").val());
		descr += " - El tributo es: " + ($("#compensa_dlTributo option[value="+$("#compensa_dlTributo").val()+"]").text());
	}

	if ($("#compensa_ckObjeto").prop("checked"))
	{
		if ($("#compensa_txObjeto").val() == "")
		{
			error.push( "Debe ingresar un Objeto." );
		} else {
			if (criterio !== "") criterio += " and ";
			//obj_nom = '"+($("#compensa_dlObjeto option[value="+$("#compensa_dlObjeto").val()+"]").text())+"' AND
			criterio += "obj_ori ='"+$("#compensa_txObjeto").val()+"' OR obj_dest ='"+$("#compensa_txObjeto").val()+"'";
			descr += " - El tipo de Objeto es "+($("#compensa_dlObjeto option[value="+$("#compensa_dlObjeto").val()+"]").text())+" - Objeto: "+$("#compensa_txObjeto").val();
		}
	}

	if ($("#compensa_ckEstado").prop("checked"))
	{
		if ($("#compensa_dlEstado").val() == "")
		{
			error.push( "Ingrese un estado válido." );

		} else {

				if (criterio!=="") criterio += " and ";
				criterio += " est ='"+$("#compensa_dlEstado").val()+"'";
				descr += " - El estado es: " + ($("#compensa_dlEstado option[value="+$("#compensa_dlEstado").val()+"]").text());
		}
	}

	if ($("#compensa_ckMonto").prop("checked"))
	{
		if ($("#compensa_txMontoDesde").val() == "" || $("#compensa_txMontoHasta").val() == "")
		{
			error.push( "Debe ingresar el Rango de Monto." );
		} else if ($("#compensa_txMontoDesde").val() > $("#compensa_txMontoHasta").val()){

			error.push( "Rango de Monto mal ingresado." );
		} else {
			if (criterio !== "") criterio += " and ";
			criterio += " monto >='"+$("#compensa_txMontoDesde").val()+"' AND monto <='"+$("#compensa_txMontoHasta").val()+"'";
			descr += " - Monto: Desde "+$("#compensa_txMontoDesde").val()+" - Hasta "+$("#compensa_txMontoHasta").val();
		}
	}

	if ($("#compensa_ckFechaAlta").prop("checked"))
	{
		if (ValidarRangoFechaJs($("#compensa_txFechaAltaDesde").val(), $("#compensa_txFechaAltaHasta").val()) == 1)
		{
			error.push( "Rango de Fecha Alta mal ingresado" );
		}else if ($("#compensa_txFechaAltaDesde").val()== "" || $("#compensa_txFechaAltaHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha Alta" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchalta::date BETWEEN '"+$("#compensa_txFechaAltaDesde").val()+"' AND '"+$("#compensa_txFechaAltaHasta").val()+"'";
			descr += " -Fecha Alta desde "+$("#compensa_txFechaAltaDesde").val()+" hasta "+$("#compensa_txFechaAltaHasta").val();
		}
	}

	if ($("#compensa_ckFechaAplica").prop("checked"))
	{
		if (ValidarRangoFechaJs($("#compensa_txFechaAplicaDesde").val(), $("#compensa_txFechaAplicaHasta").val()) == 1)
		{
			error.push( "Rango de Fecha Aplica mal ingresado" );
		}else if ($("#compensa_txFechaAplicaDesde").val()== "" || $("#compensa_txFechaAplicaHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha Aplica" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchaplic::date between '"+$("#compensa_txFechaAplicaDesde").val()+"' AND '"+$("#compensa_txFechaAplicaHasta").val()+"'";
			descr += " -Fecha Aplica desde "+$("#compensa_txFechaAplicaDesde").val()+" hasta "+$("#compensa_txFechaAplicaHasta").val();
		}
	}

	if ($("#compensa_ckFechaBaja").prop("checked"))
	{
		if (ValidarRangoFechaJs($("#compensa_txFechaBajaDesde").val(), $("#compensa_txFechaBajaHasta").val()) == 1)
		{
			error.push( "Rango de Fecha Baja mal ingresado" );
		}else if ($("#compensa_txFechaBajaDesde").val()== "" || $("#compensa_txFechaBajaHasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha Baja" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " fchbaja::date between '"+$("#compensa_txFechaBajaDesde").val()+"' AND '"+$("#compensa_txFechaBajaHasta").val()+"'";
			descr += " -Fecha Baja desde "+$("#compensa_txFechaBajaDesde").val()+" hasta "+$("#compensa_txFechaBajaHasta").val();
		}
	}

	if (criterio == '' && error == '')
		error.push( "No se encontraron condiciones de búsqueda." );

	if (error=='')
	{
			$("#compensa_txCriterio").val(criterio);
			$("#compensa_txDescripcion").val(descr);
			$("#form-listadoCompensa-list").submit();
	} else {

		mostrarErrores( error, "#compensa_errorSummary" );
	}

}

filtrosCompensa();

</script>
