<?php

use yii\helpers\Html;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\models\ctacte\Plan;
use yii\grid\GridView;

$PlaAnt = isset($PlaAnt) ? $PlaAnt : 0;
?>

<table border='0'>
<tr>
	<td>
		<?= Html::checkbox('ckIncPerVenc',false,['id'=>'ckIncPerVenc','label'=>'Incluir períodos no vencidos']) ?>
	</td>
	<td width='5'></td>
	<td>
		<?= Html::checkbox('ckIncPerJui',false,['id'=>'ckIncPerJui', 'disabled' =>($origen != 3 ? 'true' : 'false'), 'label'=>'Incluir sólo períodos en Juicio']) ?>
	</td>
</tr>
<tr>
	<td>
		<?= Html::checkbox('ckJudiDeuda',false,['id'=>'ckJudiDeuda','disabled' => ($origen != 3 ? 'true' : 'false'),'label'=>'Recuperar la deuda consolidada en el CDF']) ?>
	</td>
	<td width='5'></td>
	<td>
		<?php
			echo Html::checkbox('ckJudiId',false,['id'=>'ckJudiId','disabled' => ($origen != 3 ? 'true' : 'false'),'label'=>'Incluir sólo períodos del CDF indicado']);
			echo "&nbsp;";
			echo Html::Button('<i class="glyphicon glyphicon-cog"></i>',
					['class' => 'bt-buscar', 'onclick' => 'btPeridosAvClick()']);

			Modal::begin([
            	'id' => 'PeriodosAV',
            	'header' => '<h4><b>Períodos agrupados por tributo</b></h4>',
				'closeButton' => [
                	'label' => '<b>X</b>',
                  	'class' => 'btn btn-danger btn-sm pull-right',
                	],
                'size' => 'modal-sm',
            ]);

				Pjax::begin(['id' => 'DivModalPerAv']);

					$obj_id = Yii::$app->request->post('obj', '');
					$tplan = Yii::$app->request->post('tplan', 0);
					$desde = Yii::$app->request->post('desde', 0);
					$hasta = Yii::$app->request->post('hasta', 0);
					$novencido = Yii::$app->request->post('novenc', false);
					$origen = Yii::$app->request->post('origen', 0);
					$judi_id = Yii::$app->request->post('judi', 0);
					$solojuicio = Yii::$app->request->post('solojuicio', false);
					$solojudiid  = Yii::$app->request->post('solojudi', false);
					$fecha = Yii::$app->request->post('fecha', '');

					$periodo = (new Plan)->BuscarPeriodos($obj_id, $tplan,$desde,$hasta,$novencido,$origen,$judi_id,$solojuicio,$solojudiid,$fecha);
					$periodo->pagination->pageSize=50;
					?>

					<div>
						<?= GridView::widget([
								'dataProvider' => $periodo,
								'id' => 'GrillaPerAv',
								'headerRowOptions' => ['class' => 'grilla'],
								'rowOptions' => ['class' => 'grilla'],
								'summary' => false,
		        				'columns' => [
		        						['class' => '\yii\grid\CheckboxColumn','checkboxOptions' => ['class' => 'simple']],
		        						['attribute'=>'obj_id','header' => 'Objeto','contentOptions'=>['style'=>'text-align:left']],
		        						['attribute'=>'trib_nom','header' => 'Tributo','contentOptions'=>['style'=>'text-align:left']],
		            					['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'text-align:right']],
							   	],
		   					]);
		   				?>

	   				</div>
	   				<div class="text-center" style="margin-top: 8px">

	   					<?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'btAceptarPerAvClick()']); ?>
	   					&nbsp;&nbsp;
						<?= Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onClick' => '$("#PeriodosAV, .window").modal("toggle");']); ?>

					</div>

					<script>
					$("#DivModalPerAv").on("pjax:end", function() {

						$.pjax.reload("#DivProcesar");

						$("#DivModalPerAv").off("pjax:end");

					});
					</script>

					<?php
	            Pjax::end();

            Modal::end();
		?>
	</td>
</tr>
<tr>
	<td>
		<label>Interés Judicial:</label>
		<?= Html::dropDownList('dlIntJud', null, utb::getAux('item','item_id','nombre',0,'trib_id=5'),
    					['prompt'=>'Seleccionar...','disabled' => ($origen != 3 ? 'true' : 'false'),'class' => 'form-control', 'id'=>'dlIntJud']); ?>
    </td>
    <td width='5'></td>
    <td>
    	<label>Monto:</label>
    	<?= Html::input('text', 'txJudiInteres', null, ['class' => 'form-control','id'=>'txJudiInteres',
							'maxlength'=>'20','disabled' => ($origen != 3 ? 'true' : 'false'),'style'=>'width:60px;']); ?>
	</td>
</tr>
<tr>
	<td>
		<?= Html::radioList('rbTodosRango',0,['Todos los períodos','Según Rango'],['onchange'=>'rbTodosRango()']) ?>
	</td>
	<td width='5'></td>
    <td>
		<label>Desde:</label>
    	<?= Html::input('text', 'txAnioDesde', null, [
				'class' => 'form-control',
				'id'=>'txAnioDesde',
				'disabled' => 'true',
				'maxlength'=>'4',
				//'onkeypress' => 'return justNumbers( event )',
				'style'=>'width:60px;',
				//'onchange' => '$.pjax.reload({container:"#DivProcesar",method:"POST"})',
			]);
		?>
		<?= Html::input('text', 'txCuotaDesde', null, [
				'class' => 'form-control',
				'id'=>'txCuotaDesde',
				'disabled' => 'true',
				'maxlength'=>'3',
				//'onkeypress' => 'return justNumbers( event )',
				'style'=>'width:50px;',
				//'onchange' => '$.pjax.reload({container:"#DivProcesar",method:"POST"})'
				]);
		?>
		<label>Hasta:</label>
    	<?= Html::input('text', 'txAnioHasta', null, [
    			'class' => 'form-control',
    			'id'=>'txAnioHasta',
    			'disabled' => 'true',
				'maxlength'=>'4',
				//'onkeypress' => 'return justNumbers( event )',
				'style'=>'width:60px;',
				//'onchange' => '$.pjax.reload({container:"#DivProcesar",method:"POST"})'
				]);
		?>
		<?= Html::input('text', 'txCuotaHasta', null, [
				'class' => 'form-control',
				'id'=>'txCuotaHasta',
				'disabled' => 'true',
				'maxlength'=>'3',
				//'onkeypress' => 'return justNumbers( event )',
				'style'=>'width:50px;',
				//'onchange' => '$.pjax.reload({container:"#DivProcesar",method:"POST"})',
			]);
		?>
		<?php
			echo "&nbsp;";
			echo Html::Button('<i class="glyphicon glyphicon-play"></i>',
					['class' => 'bt-buscar', 'onclick' => 'btProcesarClick()']);
		?>
	</td>
</tr>
</table>
<?php
Pjax::begin(['id' => 'DivProcesar']);

	$obj_id = (isset($_POST['obj']) ? $_POST['obj'] : "");
	$tplan = (isset($_POST['tplan']) ? $_POST['tplan'] : 0);
	$desde = (isset($_POST['desde']) ? $_POST['desde'] : 0);
	$hasta = (isset($_POST['hasta']) ? $_POST['hasta'] : 0);
	$novencido = (isset($_POST['novenc']) ? $_POST['novenc'] : false);
	$origen = (isset($_POST['origen']) ? $_POST['origen'] : 0);
	$judi_id = (isset($_POST['judi']) && $_POST['judi'] != '' ? $_POST['judi'] : 0);
	$solojuicio = (isset($_POST['solojuicio']) ? $_POST['solojuicio'] : false);
	$solojudiid  = (isset($_POST['solojudiid']) ? $_POST['solojudiid'] : false);
	$fecha = (isset($_POST['fecha']) ? $_POST['fecha'] : "");
	$judideuda = (isset($_POST['judideuda']) ? $_POST['judideuda'] : false);
	$num = (isset($_POST['num']) ? $_POST['num'] : "");
	$judiitem = (isset($_POST['judiitem']) ? $_POST['judiitem'] : 0);
	$judiinteres = (isset($_POST['judiinteres']) ? $_POST['judiinteres'] : 0);
	$rango = (isset($_POST['rango']) ? $_POST['rango'] : 0);
    $cond = (isset($_POST['condplannuevo']) ? $_POST['condplannuevo'] : "");
    $TipoPlan = (isset($_POST['arrayPlanConfig']) ? unserialize(urldecode(stripslashes($_POST['arrayPlanConfig']))) : null);

    $nominal = 0;
	$accesor = 0;
	$multa = 0;
	$capital = 0;

	$arrayfecha = explode("/",$fecha);

	if (count($arrayfecha) > 1) $fecha = $arrayfecha[1]."/".$arrayfecha[0]."/".$arrayfecha[2];

    if ($rango == 1 and utb::samConfig()['per_plan_decaido'])
    {
    	echo '<script>$("#txMensaje").css("display","block")</script>';
    }else {
    	echo '<script>$("#txMensaje").css("display","none")</script>';
    }

	$error = "";
	$deudadetalle = (new Plan)->DeudaDetalle($error,$TipoPlan,$obj_id, $tplan,$desde,$hasta,$novencido,$origen,$judi_id,$solojuicio,$solojudiid,$fecha,$cond,
    						$judideuda,$num,$array,$nominal,$accesor,$multa,$capital,$judiitem,$judiinteres);

	if( isset($error) && $error !== '' )
	{
		?>
		<script>
		var error = new Array();
		<?php
		foreach( $error as $array )
		{
			?>
			error.push("<?= $array; ?>");
			<?php
		}
		?>

			mostrarErrores( error; "#plannuevo_errorSummary" );
			SelectTipo();
			dlOrigenSelect();

		</script>

		<?php

		$error = '';
	}

	echo '<script>$("#arrayPeriodo").val("'.urlencode(serialize($array)).'")</script>';

	echo  GridView::widget([
			'dataProvider' => $deudadetalle,
			'id' => 'GrillaDetalleDeuda',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
        	'columns' => [
        					['class' => '\yii\grid\CheckboxColumn','headerOptions' => ['onchange' => 'MostrarControles()'], 'checkboxOptions' => ['checked' => 'true','onchange' => 'MostrarControles()']],
        					['attribute'=>'trib_id','header' => 'Trib','contentOptions'=>['style'=>'text-align:center;vertical-align:middle']],
            				['attribute'=>'trib_nom','header' => 'Tributo','contentOptions'=>['style'=>'text-align:left;vertical-align:middle']],
            				['attribute'=>'obj_id','header' => 'Objeto','contentOptions'=>['style'=>'text-align:left;vertical-align:middle']],
            				['attribute'=>'subcta','header' => 'SubCta','contentOptions'=>['style'=>'text-align:center;vertical-align:middle']],
            				['attribute'=>'anio','header' => 'Año','contentOptions'=>['style'=>'text-align:center;vertical-align:middle']],
            				['attribute'=>'cuota','header' => 'Cuota','contentOptions'=>['style'=>'text-align:center;vertical-align:middle']],
            				['attribute'=>'est','header' => 'Est','contentOptions'=>['style'=>'text-align:center;vertical-align:middle']],
            				['attribute'=>'nominal','header' => 'Nominal','contentOptions'=>['style'=>'text-align:right;vertical-align:middle']],
            				['attribute'=>'accesor','header' => 'Accesor','contentOptions'=>['style'=>'text-align:right;vertical-align:middle']],
            				['attribute'=>'multa','header' => 'Multa','contentOptions'=>['style'=>'text-align:right;vertical-align:middle']],
            				['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'text-align:right;vertical-align:middle']],
					   ],
   			]);

   	echo "<script>";
   	echo "$('#txNominal').val(".$nominal.");";
   	echo "$('#txAccesor').val(".$accesor.");";
   	echo "$('#txMulta').val(".$multa.");";
   	echo "$('#txSubTotal').val(".$capital.");";
   	echo "</script>";

   	echo "<script>";
	echo '$("#btCalcular").css("display","inline");';
	echo '$("#btAceptar").css("display","none");';
	echo '$("#btImpCtas").css("display","none");';
	echo '$("#btImpDetCuota").css("display","none");';
	echo "</script>";

Pjax::end();

echo '<input type="text" name="arrayPeriodo" id="arrayPeriodo" value="'.urlencode(serialize($array)).'" style="display:none">';

echo Html::input('text', 'txMensaje', "Se incluyen períodos anteriores al rango si estuvieron en convenios",
			['class' => 'form-control','id'=>'txMensaje', 'disabled'=>'true','style'=>'width:400px;font-weight:bold; display:none; background:#ffffee;']);
?>

<script>
function btPeridosAvClick()
{

	if (ValidarPeriodos() != "") return "";

	$.pjax.reload({
		container:"#DivModalPerAv",
		data:{
			obj:$("#txObj_Id").val(),
			tplan:$("#dlTipo").val(),
			desde:parseInt($("#txAnioDesde").val()*1000)+parseInt($("#txCuotaDesde").val()),
			hasta:parseInt($("#txAnioHasta").val()*1000)+parseInt($("#txCuotaHasta").val()),
			novenc:$('input:checkbox[name=ckIncPerVenc]:checked').val(),
			origen:$("#dlOrigen").val(),
			judi:$("#txJudiNum").val(),
			solojuicio:$('input:checkbox[name=ckIncPerJui]:checked').val(),
			solojudi:$('input:checkbox[name=ckJudiId]:checked').val(),
			fecha:$("#fchalta").val(),
		},
		method:"POST",
	});

	$("#PeriodosAV").modal();
}

function btAceptarPerAvClick()
{
	var keys = $('#GrillaPerAv').yiiGridView('getSelectedRows');
	condicion = "";

	if (keys.length > 0)
	{
		for (i=0; i < keys.length; i++)
		{
			if (condicion != "") condicion += " or ";
			condicion += "(obj_id='"+keys[i].substring(0,8)+"' and trib_id="+keys[i].substring(9)+")";
		}
	}

	$.pjax.reload({
		container:"#DivProcesar",
		data:{
			obj:$("#txObj_Id").val(),
			tplan:$("#dlTipo").val(),
			desde:parseInt($("#txAnioDesde").val()*1000)+parseInt($("#txCuotaDesde").val()),
			hasta:parseInt($("#txAnioHasta").val()*1000)+parseInt($("#txCuotaHasta").val()),
			novenc:($('input:checkbox[name=ckIncPerVenc]:checked').val() == 1 ? 1 : 0),
			origen:$("#dlOrigen").val(),
			judi:$("#txJudiNum").val(),
			solojuicio:($('input:checkbox[name=ckIncPerJui]:checked').val() == 1 ? 1 : 0),
			solojudiid:($('input:checkbox[name=ckJudiId]:checked').val() == 1 ? 1 : 0),
			fecha:$("#fchalta").val(),
			judideuda:($('input:checkbox[name=ckJudiDeuda]:checked').val() == 1 ? 1 : 0),
			num:$("#txContrib").val(),
			judiitem:$("#dlIntJud").val(),
			judiinteres:$("#txJudiInteres").val(),
			rango:$('input:radio[name=rbTodosRango]:checked').val(),
			condplannuevo:condicion,
			arrayPlanConfig:$("#arrayPlanConfig").val()
		},
		method:"POST"
	});

	$("#PeriodosAV, .window").modal("toggle");
}

function btProcesarClick(){
	ocultarErrores ("#plannuevo_errorSummary");

	if (ValidarPeriodos() != "") return "";

	$.pjax.reload({
		container:"#DivProcesar",
		data:{
			obj:$("#txObj_Id").val(),
			tplan:$("#dlTipo").val(),
			desde:parseInt($("#txAnioDesde").val()*1000)+parseInt($("#txCuotaDesde").val()),
			hasta:parseInt($("#txAnioHasta").val()*1000)+parseInt($("#txCuotaHasta").val()),
			novenc:($('input:checkbox[name=ckIncPerVenc]:checked').val() == 1 ? 1 : 0),
			origen:$("#dlOrigen").val(),
			judi:$("#txJudiNum").val(),
			solojuicio:($('input:checkbox[name=ckIncPerJui]:checked').val() == 1 ? 1 : 0),
			solojudiid:($('input:checkbox[name=ckJudiId]:checked').val() == 1 ? 1 : 0),
			fecha:$("#fchalta").val(),
			judideuda:($('input:checkbox[name=ckJudiDeuda]:checked').val() == 1 ? 1 : 0),
			num:$("#txContrib").val(),
			judiitem:$("#dlIntJud").val(),
			judiinteres:$("#txJudiInteres").val(),
			rango:$('input:radio[name=rbTodosRango]:checked').val(),
			arrayPlanConfig:$("#arrayPlanConfig").val()
		},
		method:"POST"
	});
}

function rbTodosRango(){
	$("#txAnioDesde").attr("disabled",($('input:radio[name=rbTodosRango]:checked').val()==0));
	$("#txCuotaDesde").attr("disabled",($('input:radio[name=rbTodosRango]:checked').val()==0));
	$("#txAnioHasta").attr("disabled",($('input:radio[name=rbTodosRango]:checked').val()==0));
	$("#txCuotaHasta").attr("disabled",($('input:radio[name=rbTodosRango]:checked').val()==0));
	$("#txAnioDesde").val("");
	$("#txCuotaDesde").val("");
	$("#txAnioHasta").val("");
	$("#txCuotaHasta").val("");

	$.pjax.reload({container:"#DivProcesar",method:"POST"});
}

function ValidarPeriodos(){

	error = new Array();

	if ($("#txObj_Id").val() == ""){
		error.push( "Debe ingresar el Nro. de Objeto" );
	}
	<?php if ($PlaAnt == 0) { ?>
		if ($("#dlTipo").val() == "")
		{
			error.push( "Debe seleccionar el Tipo de Convenio" );
		}
		if ($('#dlTipo option').size()==0)
		{
			error.push( "Debe seleccionar el Tipo de Convenio" );
		}
	<?php } ?>

    if ( $('input:radio[name=rbTodosRango]:checked').val() == 1 )
    {
    	if ($("#txAnioDesde").val() < 1900 || $("#txAnioDesde").val() > ((new Date).getFullYear() + 2))
    	{
    		error.push( "Rango de Períodos incorrecto" );

    	} else if ($("#txCuotaDesde").val() <= 0)
    	{
    		error.push( "Rango de Períodos incorrecto" );

    	} else if ($("#txAnioHasta").val() < 1900 || $("#txAnioHasta").val() > ((new Date).getFullYear() + 2))
    	{
    		error.push( "Rango de Períodos incorrecto" );

    	} else if ($("#txCuotaHasta").val() <= 0)
    	{
    		error.push( "Rango de Períodos incorrecto" );
    	}
    }

    if ($("#dlOrigen").val() == 3 && ($('input:checkbox[name=ckJudiDeuda]:checked').val()==1 || $('input:checkbox[name=ckJudiId]:checked').val()==1) && $("#txJudiNum").val()<=0)
    {
    	error.push( "Si el Origen es Judicial debe indicar el número de CDF" );
    }

    if ( error.length > 0 )
    {
    	mostrarErrores( error, "plannuevo_errorSummary" );

    	return "error";
    }

    return "";

}

function MostrarControles()
{
	$("#btCalcular").css("display","inline");
	$("#btAceptar").css("display","none");
	$("#btImpCtas").css("display","none");
	$("#btImpDetCuota").css("display","none");
}
</script>
