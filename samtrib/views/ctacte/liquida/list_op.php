<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'][] = ['label' => 'Tributo'];
$this->params['breadcrumbs'][] = ['label' => 'Liquidaciones Eventuales','url' => ['view']];
$this->params['breadcrumbs'][] = ['label' => 'Listado'];

$criterio = "";

$eventual = (isset($eventual) ? $eventual : 1);

$condtrib = ($eventual == 1 ? "Tipo in (3,4) and not Trib_Id in (7,8)" : "tipo<>0 and tipo<>2 and tipo<>6 and Est='A'");
?>

<div class="liquida-index">
	<h1><?= Html::encode('Opciones ') ?></h1>

<?php 
	$form = ActiveForm::begin(['action' => ['list_op'],'id' => 'form-liquida-list']);
	
	echo '<input type="text" name="txcriterio" id="txcriterio" style="display:none">';
	echo '<input type="text" name="txcriterioimp" id="txcriterioimp" style="display:none">';
	echo '<input type="text" name="txdescr" id="txdescr" style="display:none">';
	echo '<input type="text" name="txeventual" id="txeventual" value="'.$eventual.'" style="display:none">';
?> 
   
<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
    						
<table border='0'>
<tr>
	<td width="160px"><?= Html::checkbox('ckCtaCte',false,['id'=>'ckCtaCte','label'=>'Referencia:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td><label>Desde &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txCtaCteDesde', null, ['class' => 'form-control','id'=>'txCtaCteDesde','maxlength'=>'11','style'=>'width:80px', 'disabled'=>'true']); ?>
	</td>
	<td width="10px"></td>
	<td><label>Hasta &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txCtaCteHasta', null, ['class' => 'form-control','id'=>'txCtaCteHasta','maxlength'=>'11','style'=>'width:80px', 'disabled'=>'true']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::checkbox('ckTributo',false,['id'=>'ckTributo','label'=>'Tributo:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td colspan='8'>
		<?= Html::dropDownList('dlTributo', null, utb::getAux('trib','trib_id','nombre',3,$condtrib), ['class' => 'form-control','id'=>'dlTributo','style'=>'width:263px;', 'disabled'=>'true','onchange' => 'ControlesListLiquida("dlTributo")' ]); ?>
	</td>
</tr>

<tr>
	<td><?= Html::checkbox('ckItem',false,['id'=>'ckItem','label'=>'Item:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td>
		<?= Html::input('text', 'txItemId', null, ['class' => 'form-control','id'=>'txItemId','maxlength'=>'4','style'=>'width:40px', 'disabled'=>'true', 'onchange' => 'ControlesListLiquida("txItemId")']); ?>
	</td>
	<td colspan="7">
		<!-- boton de b�squeda modal -->
			<?php
			Modal::begin([
                'id' => 'BuscaAuxItem',
				'header' => '<h2>Búsqueda de Items</h2>',
				'toggleButton' => [
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar',
                    'id' => 'btBuscaItem',
					'disabled'=>'true',
					'style' => 'margin-left: 3px'
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ]
            ]);
            
			Pjax::begin(['id'=>'pjModalAuxItem']);
				echo $this->render('//taux/auxbusca', [
									'tabla' => 'item', 'campocod' => 'item_id',
									'idcampocod'=>'txItemId',
            						'idcamponombre'=>'txItemNom',
            						'boton_id'=>'btBuscaItem',
            						'criterio' => 'trib_id=' . (isset($_POST['trib']) ? $_POST['trib'] : '0'),
									'order' => 'nombre',
									'claseGrilla' => 'grilla',
									'cantmostrar' => '25'
								]);
            Pjax::end();
			
            Modal::end();
            ?>
            <!-- fin de boton de b�squeda modal -->
		<?= Html::input('text', 'txItemNom', null, ['class' => 'form-control','id'=>'txItemNom','style'=>'width:320px;background:#E6E6FA;','disabled'=>'true']); ?>
	
	</td>
</tr>

<tr>
	<td><?= Html::checkbox('ckObjeto',false,['id'=>'ckObjeto','label'=>'Objeto:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td colspan="2">
		<?= Html::dropDownList('dlTObj', null, utb::getAux('objeto_tipo'), ['class' => 'form-control','id'=>'dlTObj','style'=>'width:98%', 'disabled'=>'true','onchange' => 'ControlesListLiquida("dlTObj")' ]); ?>
	</td>
	<td></td>
	<td><label>Desde &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txObjetoDesde', null, ['class' => 'form-control','id'=>'txObjetoDesde','maxlength'=>'8','style'=>'width:80px', 'disabled'=>'true', 'onchange' => 'ControlesListLiquida("txObjetoDesde")']); ?>
	</td>
	<td width="10px"></td>
	<td><label>Hasta &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txObjetoHasta', null, ['class' => 'form-control','id'=>'txObjetoHasta','maxlength'=>'8','style'=>'width:80px', 'disabled'=>'true', 'onchange' => 'ControlesListLiquida("txObjetoHasta")']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::checkbox('ckContrib',false,['id'=>'ckContrib','label'=>'Contribuyente:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td><label>Desde &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txContribDesde', null, ['class' => 'form-control','id'=>'txContribDesde','maxlength'=>'8','style'=>'width:80px', 'disabled'=>'true', 'onchange' => 'ControlesListLiquida("txContribDesde")']); ?>
	</td>
	<td></td>
	<td><label>Hasta &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txContribHasta', null, ['class' => 'form-control','id'=>'txContribHasta','maxlength'=>'8','style'=>'width:80px', 'disabled'=>'true', 'onchange' => 'ControlesListLiquida("txContribHasta")']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::checkbox('ckFchEmi',false,['id'=>'FchEmi','label'=>'Fecha de Emisión:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td><label>Desde &nbsp;</label></td>
	<td>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fchemidesde',
					'name' => 'fchemidesde',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
	</td>
	<td></td>
	<td><label>Hasta &nbsp;</label></td>
	<td>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fchemihasta',
					'name' => 'fchemihasta',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
	</td>
</tr>
<tr>
	<td><?= Html::checkbox('ckFchVenc',false,['id'=>'FchVenc','label'=>'Fecha de Vencimiento:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td><label>Desde &nbsp;</label></td>
	<td>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fchvencdesde',
					'name' => 'fchvencdesde',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
	</td>
	<td></td>
	<td><label>Hasta &nbsp;</label></td>
	<td>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fchvenchasta',
					'name' => 'fchvenchasta',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
	</td>
</tr>
<tr>
	<td><?= Html::checkbox('ckFchPago',false,['id'=>'FchPago','label'=>'Fecha de Pago:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td><label>Desde &nbsp;</label></td>
	<td>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fchpagodesde',
					'name' => 'fchpagodesde',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
	</td>
	<td></td>
	<td><label>Hasta &nbsp;</label></td>
	<td>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fchpagohasta',
					'name' => 'fchpagohasta',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
	</td>
</tr>

<tr>
	<td><?= Html::checkbox('ckPeriodo',false,['id'=>'ckPeriodo','label'=>'Período:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td><label>Desde &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txPerAnioDesde', null, ['class' => 'form-control','id'=>'txPerAnioDesde','maxlength'=>'4','style'=>'width:40px', 'disabled'=>'true']); ?>
		<?= Html::input('text', 'txPerCuotaDesde', null, ['class' => 'form-control','id'=>'txPerCuotaDesde','maxlength'=>'3','style'=>'width:35px', 'disabled'=>'true']); ?>
	</td>
	<td></td>
	<td><label>Hasta &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txPerAnioHasta', null, ['class' => 'form-control','id'=>'txPerAnioHasta','maxlength'=>'4','style'=>'width:40px', 'disabled'=>'true']); ?>
		<?= Html::input('text', 'txPerCuotaHasta', null, ['class' => 'form-control','id'=>'txPerCuotaHasta','maxlength'=>'3','style'=>'width:35px', 'disabled'=>'true']); ?>
	</td>
</tr>

<tr>
	<td><?= Html::checkbox('ckMonto',false,['id'=>'ckMonto','label'=>'Monto:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td><label>Desde &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txMontoDesde', null, [
				'class' => 'form-control',
				'id'=>'txMontoDesde',
				'maxlength'=>'8',
				'onkeypress' => 'return justDecimal( $(this).val(), event )',
				'style'=>'width:80px;',
				'disabled'=>'true',
			]);
		?>
	</td>
	<td></td>
	<td><label>Hasta &nbsp;</label></td>
	<td>
		<?= Html::input('text', 'txMontoHasta', null, [
				'class' => 'form-control',
				'id'=>'txMontoHasta',
				'maxlength'=>'8',
				'onkeypress' => 'return justDecimal( $(this).val(), event )',
				'style'=>'width:80px',
				'disabled'=>'true',
			]); 
		?>
	</td>
</tr>

<tr>
	<td><?= Html::checkbox('ckEstado',false,['id'=>'ckEstado','label'=>'Estado:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td colspan="2">
		<?= Html::dropDownList('dlEstado', null, utb::getAux('CtaCte_TEst'), ['class' => 'form-control','id'=>'dlEstado', 'style'=>'width:98%','disabled'=>'true' ]); ?>
	</td>
</tr>
<tr>
	<td><?= Html::checkbox('ckExpe',false,['id'=>'ckExpe','label'=>'Expediente:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td colspan="4">
		<?= Html::input('text', 'txExpe', null, ['class' => 'form-control','id'=>'txExpe','maxlength'=>'15','style'=>'width:98%', 'disabled'=>'true']); ?>
	</td>
</tr>
<tr>
	<td><?= Html::checkbox('ckUsuario',false,['id'=>'ckUsuario','label'=>'Usuario:','onchange' => 'ControlesListLiquida("")'])?> </td>
	<td colspan="4">
		<?= Html::dropDownList('dlUsuario', null, utb::getAux('sam.sis_usuario','usr_id','apenom',0,"est='A'"), ['class' => 'form-control','id'=>'dlUsuario','style'=>'width:98%', 'disabled'=>'true' ]); ?>
	</td>
</tr>

</table>

</div>

<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'ControlesListLiquida("btAceptar");']); ?>

<div id="liquida_errorSummary" class="error-summary" style="display:none;margin-top: 8px">

	<ul>
	</ul>

</div>

<?php
	ActiveForm::end(); 
	

Pjax::begin(['id' => 'CompletarControles']);
	$obj_desde = '';
	$obj_hasta = '';
	$tobj = 0;
	$num_desde = '';
	$num_hasta = '';
	$calle = '';
	if (isset($_POST['obj_desde'])) $obj_desde=$_POST['obj_desde'];
	if (isset($_POST['obj_hasta'])) $obj_hasta=$_POST['obj_hasta'];
	if (isset($_POST['tobj'])) $tobj=$_POST['tobj'];
	if (isset($_POST['num_desde'])) $num_desde=$_POST['num_desde'];
	if (isset($_POST['num_hasta'])) $num_hasta=$_POST['num_hasta'];
		
	if (strlen($obj_desde) < 8 && strlen($obj_desde) > 0)
	{
		$obj_desde = utb::GetObjeto($tobj,(int)$obj_desde);
		echo '<script>$("#txObjetoDesde").val("'.$obj_desde.'")</script>';
	}
	if (strlen($obj_hasta) < 8 && strlen($obj_hasta) > 0)
	{
		$obj_hasta = utb::GetObjeto($tobj,(int)$obj_hasta);
		echo '<script>$("#txObjetoHasta").val("'.$obj_hasta.'")</script>';
	}
	if (strlen($num_desde) < 8 && strlen($num_desde) > 0)
	{
		$num_desde = utb::GetObjeto(3,(int)$num_desde);
		echo '<script>$("#txContribDesde").val("'.$num_desde.'")</script>';
	}else {
		if (utb::getTObj($num_desde) !== 3) echo '<script>$("#txContribDesde").val("")</script>'; 
	}
	if (strlen($num_hasta) < 8 && strlen($num_hasta) > 0)
	{
		$num_hasta = utb::GetObjeto(3,(int)$num_hasta);
		echo '<script>$("#txContribHasta").val("'.$num_hasta.'")</script>';
	}else {
		if (utb::getTObj($num_hasta) !== 3) echo '<script>$("#txContribHasta").val("")</script>'; 
	}
	
	if (isset($_POST['item']))
	{
		$item = utb::getCampo('item','item_id='.$_POST['item']." and trib_id=".$_POST['trib']);
		echo '<script>$("#txItemNom").val("'.$item.'")</script>';
	}
Pjax::end(); 
?>
</div>

<script>

function completarCodigos(control){
	
	var trib_id = 0;
	var item_id = 0;
	var tobjeto = 0;
	
	if ($("#dlTributo").val() !== "") trib_id = $("#dlTributo").val();
	if ($("#txItemId").val() !== "") item_id = $("#txItemId").val();
	if ($("#dlTObj").val() !== "") tobjeto = $("#dlTObj").val();
	
	if (control=="dlTObj") $("#txObjetoDesde").val("");
	if (control=="dlTObj") $("#txObjetoHasta").val("");
	
	$.pjax.reload(
		{
			container:"#CompletarControles",
			data:{
				obj_desde:$("#txObjetoDesde").val(),
				obj_hasta:$("#txObjetoHasta").val(),
				tobj:tobjeto,
				num_desde:$("#txContribDesde").val(),
				num_hasta:$("#txContribHasta").val(),
				item:item_id,
				trib:trib_id},
			method:"POST"
		})
		
	$("#CompletarControles").on("pjax:end", function(){ 
		if (control=="dlTObj") $("#txObjetoDesde").focus();
		if (control=="txObjetoDesde") $("#txObjetoHasta").focus();
	});
}

function ControlesListLiquida(control)
{
	if (control=="")
	{
		$("#txCtaCteDesde").prop("disabled",!($('input:checkbox[name=ckCtaCte]:checked').val()==1));
		$("#txCtaCteHasta").prop("disabled",!($('input:checkbox[name=ckCtaCte]:checked').val()==1));
		$("#dlTributo").prop("disabled",!($('input:checkbox[name=ckTributo]:checked').val()==1));
		$("#ckItem").prop("disabled",!($('input:checkbox[name=ckTributo]:checked').val()==1));
		$("#ckItem").attr("checked",($('input:checkbox[name=ckTributo]:checked').val()==1));
		$("#dlTObj").prop("disabled",!($('input:checkbox[name=ckObjeto]:checked').val()==1));
		$("#txObjetoDesde").prop("disabled",!($('input:checkbox[name=ckObjeto]:checked').val()==1));
		$("#txObjetoHasta").prop("disabled",!($('input:checkbox[name=ckObjeto]:checked').val()==1));
		$("#txContribDesde").prop("disabled",!($('input:checkbox[name=ckContrib]:checked').val()==1));
		$("#txContribHasta").prop("disabled",!($('input:checkbox[name=ckContrib]:checked').val()==1));
		$("#fchemidesde").prop("disabled",!($('input:checkbox[name=ckFchEmi]:checked').val()==1));
		$("#fchemihasta").prop("disabled",!($('input:checkbox[name=ckFchEmi]:checked').val()==1));
		$("#fchvencdesde").prop("disabled",!($('input:checkbox[name=ckFchVenc]:checked').val()==1));
		$("#fchvenchasta").prop("disabled",!($('input:checkbox[name=ckFchVenc]:checked').val()==1));
		$("#fchpagodesde").prop("disabled",!($('input:checkbox[name=ckFchPago]:checked').val()==1));
		$("#fchpagohasta").prop("disabled",!($('input:checkbox[name=ckFchPago]:checked').val()==1));
		$("#txPerAnioDesde").prop("disabled",!($('input:checkbox[name=ckPeriodo]:checked').val()==1));
		$("#txPerCuotaDesde").prop("disabled",!($('input:checkbox[name=ckPeriodo]:checked').val()==1));
		$("#txPerAnioHasta").prop("disabled",!($('input:checkbox[name=ckPeriodo]:checked').val()==1));
		$("#txPerCuotaHasta").prop("disabled",!($('input:checkbox[name=ckPeriodo]:checked').val()==1));
		$("#txMontoDesde").prop("disabled",!($('input:checkbox[name=ckMonto]:checked').val()==1));
		$("#txMontoHasta").prop("disabled",!($('input:checkbox[name=ckMonto]:checked').val()==1));
		$("#txItemId").prop("disabled",!($('input:checkbox[name=ckItem]:checked').val()==1));
		$("#btBuscaItem").prop("disabled",!($('input:checkbox[name=ckItem]:checked').val()==1));
		$("#dlEstado").prop("disabled",!($('input:checkbox[name=ckEstado]:checked').val()==1));
		$("#txExpe").prop("disabled",!($('input:checkbox[name=ckExpe]:checked').val()==1));
		$("#dlUsuario").prop("disabled",!($('input:checkbox[name=ckUsuario]:checked').val()==1));
		
		if ( !$('input:checkbox[name=ckTributo]:checked').val()==1){
			$("#txItemId").val("");
			$("#txItemNom").val("");
		}
	}

	if (control=="txItemId")
	{
		completarCodigos(control);
	}
	
	if (control=="dlTributo")
	{
		$("#txItemId").val("");
		$("#txItemNom").val("");
		
		$.pjax.reload(
		{
			container:"#pjModalAuxItem",
			data:{
				trib:$("#dlTributo").val()
			},
			method:"POST"
		});
	}
	
	if (control=="btAceptar")
	{
		var error = new Array(),
			criterio = '',
			criterioimp = '',
			descr = '';
		
		if($("#ckObjeto").is(":checked") || $("#ckContrib").is(":checked")){
			
			completarCodigos(control);
			
			$("#CompletarControles").on("pjax:complete", function(){
				cargar();
			});
			
		} else cargar();
	}
	
}

function cargar(){
	
	var criterio= "", descr= "", error= "", criterioimp= "", error= new Array();
	
	if ($('input:checkbox[name=ckCtaCte]:checked').val()==1)
	{
		if ($("#txCtaCteDesde").val() > $("#txCtaCteHasta").val())
		{
			error.push( "Rango de Cta. Cte. mal ingresado" );
		}else if ($("#txCtaCteDesde").val() == "" || $("#txCtaCteHasta").val() == "")
		{
			error.push( "Complete los rangos de Cta. Cte." );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " c.ctacte_id >="+$("#txCtaCteDesde").val()+" And c.ctacte_id <="+$("#txCtaCteHasta").val();	
			descr += " -Cta.Cte. desde "+$("#txCtaCteDesde").val()+" hasta "+$("#txCtaCteHasta").val();
		}	
	}
	if ($('input:checkbox[name=ckTributo]:checked').val()==1)
	{
		if (criterio!=="") criterio += " and ";
		criterio += "c.trib_id='"+$("#dlTributo").val()+"'";	
		descr += " -Tributo: "+$('#dlTributo option:selected').text(); 
	}
	if ($('input:checkbox[name=ckObjeto]:checked').val()==1)
	{
		if ($("#txObjetoDesde").val()== "" || $("#txObjetoHasta").val() == "")
		{
			error.push( "Complete los rangos de objeto" );
		}else if ($("#txObjetoDesde").val() > $("#txObjetoHasta").val())
		{
			error.push( "Rango de Objeto mal ingresado" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " c.obj_id >='"+$("#txObjetoDesde").val()+"' And c.Obj_id <='"+$("#txObjetoHasta").val()+"'";	
			descr += " -Objeto desde "+$("#txObjetoDesde").val()+" hasta "+$("#txObjetoHasta").val();
		}	
	}
	if ($('input:checkbox[name=ckContrib]:checked').val()==1)
	{
		if ($("#txContribDesde").val()== "" || $("#txContribHasta").val() == "")
		{
			error.push( "Complete los rangos de contribuyentes" );
		}else if ($("#txContribDesde").val() > $("#txContribHasta").val())
		{
			error.push( "Rango de Contribuyentes mal ingresado" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " c.num >='"+$("#txContribDesde").val()+"' And c.num <='"+$("#txContribHasta").val()+"'";	
			descr += " -Contribuyente desde "+$("#txContribDesde").val()+" hasta "+$("#txContribHasta").val();
		}	
	}
	if ($('input:checkbox[name=ckFchEmi]:checked').val()==1)
	{
		if ($("#fchemidesde").val()== "" || $("#fchemihasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Emisión" );
		}else if (ValidarRangoFechaJs($("#fchemidesde").val(), $("#fchemihasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Emisión mal ingresado" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " c.fchemi between '"+$("#fchemidesde").val()+"' And '"+$("#fchemihasta").val()+"'";
			descr += " -Fecha Emisión desde "+$("#fchemidesde").val()+" hasta "+$("#fchemihasta").val();	
		}	
	}
	if ($('input:checkbox[name=ckFchVenc]:checked').val()==1)
	{
		if ($("#fchvencdesde").val()== "" || $("#fchvenchasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Vencimiento" );
		}else if (ValidarRangoFechaJs($("#fchvencdesde").val(), $("#fchvenchasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Vencimiento mal ingresado" );
		}else  {
			if (criterio!=="") criterio += " and ";
			criterio += " c.venc2 >='"+$("#fchvencdesde").val()+"' And c.venc2 <='"+$("#fchvenchasta").val()+"'";
			descr += " -Fecha Vencimiento desde "+$("#fchvencdesde").val()+" hasta "+$("#fchvenchasta").val();	
		}	
	}
	if ($('input:checkbox[name=ckFchPago]:checked').val()==1)
	{
		if ($("#fchpagodesde").val()== "" || $("#fchpagohasta").val() == "")
		{
			error.push( "Complete los rangos de Fecha de Pago" );
		} else if (ValidarRangoFechaJs($("#fchpagodesde").val(), $("#fchpagohasta").val()) == 1)
		{
			error.push( "Rango de Fecha de Pago mal ingresado" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " c.fchpago >='"+$("#fchpagodesde").val()+"' And c.fchpago <='"+$("#fchpagohasta").val()+"'";
			descr += " -Fecha Pago desde "+$("#fchpagodesde").val()+" hasta "+$("#fchpagohasta").val();	
		}	
	}
	
	if ($('input:checkbox[name=ckPeriodo]:checked').val()==1)
	{
		var desde = ( parseInt( $("#txPerAnioDesde").val()) * 1000) + parseInt( $("#txPerCuotaDesde").val() );
		var hasta = ( parseInt( $("#txPerAnioHasta").val()) * 1000) + parseInt( $("#txPerCuotaHasta").val() );
		
		if ( desde > hasta )
		{
			error.push( "Rango de Período mal ingresado" );
		}else if ($("#txPerAnioDesde").val() == "" || $("#txPerAnioHasta").val() == "" || $("#txPerCuotaDesde").val() == "" || $("#txPerCuotaHasta").val() == "")
		{
			error.push( "Complete los rangos de Período " );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " c.Anio*1000+c.Cuota between "+(parseInt($("#txPerAnioDesde").val())*1000+parseInt($("#txPerCuotaDesde").val()))+" And "+(parseInt($("#txPerAnioHasta").val())*1000+parseInt($("#txPerCuotaHasta").val()));	
			descr += " -Período desde "+(parseInt($("#txPerAnioDesde").val())*1000+parseInt($("#txPerCuotaDesde").val()))+" hasta "+(parseInt($("#txPerAnioHasta").val())*1000+parseInt($("#txPerCuotaHasta").val()));
		}	
	}
	if ($('input:checkbox[name=ckMonto]:checked').val()==1)
	{
		if ($("#txMontoDesde").val() > $("#txMontoHasta").val())
		{
			error.push( "Rango de monto mal ingresado" );
		}else if ($("#txMontoDesde").val() == "" || $("#txMontoHasta").val() == "")
		{
			error.push( "Complete los rangos de monto" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " c.monto >="+$("#txMontoDesde").val()+" And c.monto <="+$("#txMontoHasta").val();	
			descr += " -Monto desde "+$("#txMontoDesde").val()+" hasta "+$("#txMontoHasta").val();
		}	
	}
	if ($('input:checkbox[name=ckItem]:checked').val()==1)
	{
		if ($("#txItemNom").val() == "")
		{
			error.push( "Ingrese el Item a buscar" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " c.CtaCte_id in (Select CtaCte_id From CtaCte_Liq Where Item_id="+$("#txItemId").val()+")";
			descr += " -Item="+$("#txItemNom").val();
		}
	}
	if ($('input:checkbox[name=ckEstado]:checked').val()==1)
	{
		if (criterio!=="") criterio += " and ";
		criterio += " c.est='"+$("#dlEstado").val()+"'";	
		descr += " -Estado: "+$('#dlEstado option:selected').text(); 
	}
	if ($('input:checkbox[name=ckExpe]:checked').val()==1)
	{
		if ($("#txExpe").val() == "")
		{
			error.push( "Ingrese el  de Nro. Expediente a buscar" );
		}else {
			if (criterio!=="") criterio += " and ";
			criterio += " c.Expe like '"+$("#txExpe").val()+"%'";
			descr += " -El expediente comienza con: "+$("#txExpe").val();
		}	
	}
	if ($('input:checkbox[name=ckUsuario]:checked').val()==1)
	{
		if (criterio!=="") criterio += " and ";
		criterio += " upper(c.modif) like upper('%"+$('#dlUsuario option:selected').text()+"%')";	
		descr += " -Usuario: "+$('#dlUsuario option:selected').text(); 
	}
	
	if ( criterio=='' && criterioimp=='' && error.length == 0 ) 
		error.push( "No se encontraron condiciones de búsqueda." );
	
	if ( error.length == 0 )
	{
		criterio += " And trib_tipo in (3, 4)";
		$("#txcriterio").val(criterio);
		$("#txcriterioimp").val(criterioimp);
		$("#txdescr").val(descr);
		$("#form-liquida-list").submit();
	}else {
		
		mostrarErrores( error, "#liquida_errorSummary" );
	}
}
</script>

<script>ControlesListLiquida("");</script>