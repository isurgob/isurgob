<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;
use app\models\objeto\Rodado;
use yii\jui\DatePicker;
use app\utils\db\utb;
use yii\web\Session;
use yii\data\ArrayDataProvider;


$model = new Rodado();
$criterio = "";

$id = isset($id) ? $id : 'rodado'; 
$txCod = isset($txCod) ? $txCod : 'txCod'; 
$txNom = isset($txNom) ? $txNom : 'txNom';  

$session = new Session;
$session->open();
$session['id'] = $id;
$session->close();

$fechaHoy = date('Y/m/d');
?>
<div id="buscarAvRodado">

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>

<tr>
	<td><?= Html::radio('rb'.$id, false, ['id' => 'rbNombreResponsable' . $id, 'label' => 'Nombre responsable:', 'class' => 'check' . $id]) ?></td>
	<td></td>
	<td colspan="3"><?= Html::textInput(null, null, ['id' => 'txNombreResponsable'.$id, 'class' => 'form-control', 'maxlength' => 50, 'disabled' => true, 'style' => 'width:100%;']); ?></td>
	<td width="20px"></td>
	<td><?= Html::radio('rb'.$id, false, ['id' => 'rbNumeroMotor' . $id, 'label' => 'Nº Motor:', 'class' => 'check' . $id]) ?></td>
	<td></td>
	<td colspan="3"><?= Html::textInput(null, null, ['id' => 'txNumeroMotor'.$id, 'class' => 'form-control', 'maxlength' => 30, 'disabled' => true, 'style' => 'width:100%;']); ?></td>
</tr>

<tr>
	<td><?= Html::radio('rb'.$id, false, ['id' => 'rbDominio' . $id, 'label' => 'Dominio:', 'class' => 'check' . $id]) ?></td>
	<td></td>
	<td colspan="3"><?= Html::textInput(null, null, ['id' => 'txDominio'.$id, 'class' => 'form-control', 'maxlength' => 9, 'disabled' => true, 'style' => 'width:100%;']); ?></td>
	<td width="20px"></td>
	<td><?= Html::radio('rb'.$id, false, ['id' => 'rbNumeroChasis' . $id, 'label' => 'Nº Chasis:', 'class' => 'check' . $id]) ?></td>
	<td></td>
	<td colspan="3"><?= Html::textInput(null, null, ['id' => 'txNumeroChasis'.$id, 'class' => 'form-control', 'maxlength' => 30, 'disabled' => true, 'style' => 'width:100%;']); ?></td>
</tr>

<tr>
	<td><?= Html::radio('rb'.$id,false,['id'=>'rbFechaCompra'.$id,'label'=>'Fecha Compra:', 'class' => 'check' . $id])?> </td>
	<td width='5px'></td>
	<td>
		<label>Desde &nbsp;</label>
		<?= DatePicker::widget(['name' => 'compraDesde'.$id, 'id' => 'txFechaCompraDesde' . $id, 'dateFormat' => 'dd/MM/yyyy', 'value' => $fechaHoy, 'options' => ['class' => 'form-control', 'disabled' => true, 'style' => 'width:70px;', 'maxlength' => 10]]); ?>
	</td>
	<td></td>
	<td>
		<label>Hasta &nbsp;</label>
		<?= DatePicker::widget(['name' => 'compraHasta'.$id, 'id' => 'txFechaCompraHasta' . $id, 'dateFormat' => 'dd/MM/yyyy', 'value' => $fechaHoy, 'options' => ['class' => 'form-control', 'disabled' => true, 'style' => 'width:70px;', 'maxlength' => 10]]); ?>
	</td>
</tr>

<tr>
	<td colspan='7'>
		<br><div id="errorbuscaavrodado<?=$id?>" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='7'>
		<?= Html::Button('Buscar',['class' => 'btn btn-primary', 'onClick' => 'controlesBuscarAvRodado("btAceptar'.$id.'","'.$id.'");'])?>
	</td>
</tr>
</table>
</div>
<?php
//cantidad de elementos a listar
$cantidad = isset($cantidad) ? $cantidad : 40;

Pjax::begin(['id' => 'grillaBuscarAvRodado'.$id, 'enableReplaceState' => false, 'enablePushState' => false]);

$criterio =  !isset($criterio) ? "" : $criterio;
 
$session = new Session;
$session->open();

if (isset($_POST['cond'])) 
{
	if ($criterio !== "") $criterio .= " and ";
	$criterio .= $_POST['cond'];
	$session->set('cavr', $criterio);
	
}else {
	$criterio= $session->get('cavr', '');
}

$session->close();

$cargar = filter_var(Yii::$app->request->post('cargar' . $id, false), FILTER_VALIDATE_BOOLEAN);

$dp = new ArrayDataProvider(['allModels' => []]);

if($cargar || $criterio != '')
	$dp = $model->BuscarRodadoAv($criterio, 'nombre', $cantidad);
?>
<div style="margin-top:10px;">
<?php
echo GridView::widget([
	'id' => 'GrillaDatosRodados'.$id,
	'dataProvider' => $dp,
	'headerRowOptions' => ['class' => 'grilla'],
	'rowOptions' => function ($model,$key,$index,$grid) use($id) 
        				{							
        					return 
        					[
								'ondblclick'=>'btnObjBuscarDbl'.$id.'("'.$model['obj_id'].'","'.$model['num_nom'].'")',
								'onclick'=>'btnObjBuscar'.$id.'("'.$model['obj_id'].'","'.$model['num_nom'].'")'
							];
        				},
    'columns' => 		
    	[
        	['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'num_nom','header' => 'Responsable', 'contentOptions'=>['style'=>'width:200px;', 'class' => 'grilla']],
            ['attribute'=>'cat_nom','header' => 'Categoría', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'marca_nom','header' => 'Marca', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'modelo_nom','header' => 'Modelo', 'contentOptions'=>['style'=>'width:160px;', 'class' => 'grilla']],
            ['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'dominio','header' => 'Dominio', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'nromotor','header' => 'Nº Motor', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'nrochasis','header' => 'Nº Chasis', 'contentOptions'=>['style'=>'', 'class' => 'grilla']]
         ],
    ]); 
?>
</div>
<?php

Pjax::end();  
?>

<script>
function btnObjBuscar<?= $id ?>(cod,nom)
{
	$("#<?= $txCod ?>").val(cod);
	$("#<?= $txNom ?>").val(nom);
}

function btnObjBuscarDbl<?= $id ?>(cod,nom)
{
	$("#<?= $txCod ?>").val(cod);
	$("#<?= $txNom ?>").val(nom);
	$("#BuscaObj<?= $id ?>").modal('hide');
}

function controlesBuscarAvRodado(control,id)
{
			
	var criterio = error = "";
	
	
	$("#txNombreResponsable<?= $id ?>").prop("disabled", !$("#rbNombreResponsable<?= $id ?>").is(":checked"));
	$("#txDominio<?= $id ?>").prop("disabled", !$("#rbDominio<?= $id ?>").is(":checked"));
	$("#txNumeroMotor<?= $id ?>").prop("disabled", !$("#rbNumeroMotor<?= $id ?>").is(":checked"));
	$("#txNumeroChasis<?= $id ?>").prop("disabled", !$("#rbNumeroChasis<?= $id ?>").is(":checked"));
	$("#txFechaCompraDesde<?= $id ?>").prop("disabled", !$("#rbFechaCompra<?= $id ?>").is(":checked"));
	$("#txFechaCompraHasta<?= $id ?>").prop("disabled", !$("#rbDechaCompra<?= $id ?>").is(":checked"));

			
	if (control=="btAceptar<?= $id ?>")
	{
		if($("#rbNombreResponsable<?= $id ?>").is(":checked")){
			
			if($("#txNombreResponsable<?= $id ?>").val() == '')
				error = "Ingrese un nombre de responsable";
			else{
				var c = " upper(num_nom) like upper('%" + $("#txNombreResponsable<?= $id ?>").val()+"%')";
					
			 	criterio = criterio != '' ? " And " + c : c;
			}
		}
		
		if($("#rbDominio<?= $id ?>").is(":checked")){
			
			if($("#txDominio<?= $id ?>").val() == "")
				error = "Ingrese un dominio";
			else{
				var c = " dominio = '" + $("#txDominio<?= $id ?>").val() + "'";
			}
			 criterio = criterio != '' ? " And " + c : c;
		}
		
		if($("#rbNumeroMotor<?= $id ?>").is(":checked")){
			
			if($("#txNumeroMotor<?= $id ?>").val() == "")
				error = "Ingrese un número de motor";
			else{
				var c = " nromotor = '" + $("#txNumeroMotor<?= $id ?>").val() + "'";
			}
			 criterio = criterio != '' ? " And " + c : c;
		}
		
		if($("#rbNumeroChasis<?= $id ?>").is(":checked")){
			
			if($("#txNumeroChasis<?= $id ?>").val() == "")
				error = "Ingrese un número de chasis";
			else{
				var c = " nrochasis = '" + $("#txNumeroChasis<?= $id ?>").val() + "'";
			}
			 criterio = criterio != '' ? " And " + c : c;
		}
		
		if ($("#rbFechaCompra<?= $id ?>").is(":checked"))
		{
			if (ValidarRangoFechaJs($("#txFechaCompraDesde<?= $id ?>").val(), $("#txFechaCompraHasta<?= $id ?>").val()) == 1)
			{
				error += '<li>Rango de fecha de compra mal ingresado</li>';
			}else if ($("#txFechaCompraDesde<?= $id ?>").val()== "" || $("#txFechaCompraHasta<?= $id ?>").val() == "")
			{
				error += '<li>Complete los rangos de fecha de compra</li>';
			}else {
				var c = " fchcompra between '"+$("#txFechaCompraDesde<?= $id ?>").val()+"' And '"+$("#txFechaCompraHasta<?= $id ?>").val()+"'";
				criterio = criterio != '' ? ' And ' + c : c;
			}	
		}
		
		if (criterio=='' && error=='') error = 'No se encontraron condiciones de búsqueda';
		
		if (criterio != '')
		{
			$("#errorbuscaavrodado<?= $id ?>").html("");
			$("#errorbuscaavrodado<?= $id ?>").css("display", "none");
			
			criterio += " And est = 'A'";
			
			$.pjax.reload({
				container:"#grillaBuscarAvRodado<?= $id ?>",
				replace : false,
				push : false,
				data:{cond:criterio, "cargar<?= $id ?>" : true},
				method:"POST"
			});

		}else {
			$("#errorbuscaavrodado<?= $id ?>").html(error);
			$("#errorbuscaavrodado<?= $id ?>").css("display", "block");
		}
	}
}

$(".check<?= $id ?>").change(function(){controlesBuscarAvRodado($(this).attr("id"));});
</script>