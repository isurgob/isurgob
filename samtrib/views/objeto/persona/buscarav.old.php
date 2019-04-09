<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;
use app\models\objeto\Persona;
use yii\jui\DatePicker;
use app\utils\db\utb;
use yii\web\Session;
use yii\data\ArrayDataProvider;

$modelPers = new Persona();
$criterio = "";

if (isset($id) == null) $id = ''; 
if (isset($txCod) == null) $txCod = 'txCod'; 
if (isset($txNom) == null) $txNom = 'txNom'; 
if (isset($txDoc) == null) $txDoc = ''; 
if (isset($txEst) == null) $txEst = ''; 

$session = new Session;
$session->open();
$session['id'] = $id;
$session->close();
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td><?= Html::radio('rb'.$id,false,['id'=>'rbNombre'.$id,'label'=>'Nombre:', 'class' => 'check' . $id])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txNombrePers'.$id, null, ['class' => 'form-control','id'=>'txNombre'.$id,'maxlength'=>'50','style'=>'width:100%', 'disabled' => true]); ?>
	</td>
	<td width="20px"></td>
	<td valign="top"><?= Html::radio('rb'.$id,false,['id'=>'rbDocumento'.$id,'label'=>'Doc./Cuit:', 'class' => 'check' . $id])?> </td>
	<td width='5px'></td>
	<td>
		<?= Html::input('text', 'txDocPers'.$id, null, ['class' => 'form-control','id'=>'txDocumento'.$id,'maxlength'=>'11','style'=>'width:100px','disabled'=>'true']); ?>
	</td>
</tr>
<tr>
	<td valign="top"><?= Html::radio('rb'.$id,false,['id'=>'rbFechaAlta'.$id,'label'=>'Fecha Alta:', 'class' => 'check' . $id])?> </td>
	<td width='5px'></td>
	<td>
		<label>Desde &nbsp;</label>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fechaAltaDesde'.$id,
					'name' => 'fchaltadesdePers'.$id,
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
		<label>Hasta &nbsp;</label>
		<?= 
			DatePicker::widget(
				[
					'id' => 'fechaAltaHasta'.$id,
					'name' => 'fchaltahastaPers'.$id,
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['disabled'=>'true', 'style' => 'width:80px','class' => 'form-control'],
				]
			);
		?>
	</td>
</tr>
<tr>
	<td colspan='7'>
		<br><div id="errorbuscaavpersona<?=$id?>" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='7'>
		<?= Html::Button('Buscar',['class' => 'btn btn-primary','id' => 'btAceptarPers'.$id, 'onClick' => 'ControlesBuscarAvPersona' . $id . '("btAceptarPers'.$id.'","'.$id.'");'])?>
	</td>
</tr>
</table>

<div style="margin-top:10px;">
<?php

//cantidad de elementos a listar
$cantidad = isset($cantidad) ? $cantidad : 40;

Pjax::begin(['id' => 'GrillaBuscarAvPersona'.$id]);

if (!isset($criterio)) $criterio = "";
 
$session = new Session;
$session->open();

//$condicion = Yii::$app->request->post('condpers');

if (isset($_POST['condpers'])) 
{
	if ($criterio !== "") $criterio .= " and ";
	$criterio .= $_POST['condpers'];
	$session->set('cavp', $criterio);
}else {
	$criterio= $session->get('cavp', '');
}

$session->close();

$cargar = filter_var(Yii::$app->request->post('cargar' . $id, false), FILTER_VALIDATE_BOOLEAN);

if($cargar || $criterio != '')
	$dp = $modelPers->BuscarPersonaAv($criterio, 'nombre', $cantidad);
else
	$dp = new ArrayDataProvider(['allModels' => []]);


echo GridView::widget([
	'id' => 'GrillaDatosPers'.$id,
	'dataProvider' => $dp,
	'headerRowOptions' => ['class' => 'grilla'],
	'rowOptions' => function ($model,$key,$index,$grid) 
        				{
        					$session = new Session;
							$session->open();
							$id = $session['id'];
							$session->close();
        					return 
        					[
								'ondblclick'=>'btnObjBuscarDblPers'.$id.'("'.$model['obj_id'].'","'.$model['nombre'].'","'.$model['ndoc'].'","'.$model['est'].'")',
								'onclick'=>'btnObjBuscarPers'.$id.'("'.$model['obj_id'].'","'.$model['nombre'].'","'.$model['ndoc'].'","'.$model['est'].'")'
							];
        				},
    'columns' => 		
    	[
        	['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'width:70px', 'class' => 'grilla']],
            ['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'width:200px', 'class' => 'grilla']],
            ['attribute'=>'documento','header' => 'DNI/Cuit', 'contentOptions'=>['style'=>'width:80px', 'class' => 'grilla']],
            ['attribute'=>'dompos_dir','header' => 'Domicilio Postal', 'contentOptions'=>['style'=>'width:250px', 'class' => 'grilla']],
         ],
    ]); 

Pjax::end(); 
?>
</div>

<script type="text/javascript" language="javascript">

function btnObjBuscarPers<?= $id ?>(cod,nom,doc,est)
{
	$("#<?= $txCod ?>").val(cod);
	$("#<?= $txNom ?>").val(nom);
	if (<?= ($txDoc !== '' ? '1' : '2') ?> == 1) $("#<?= $txDoc ?>").val(doc); 
	if (<?= ($txEst !== '' ? '1' : '2') ?> == 1) $("#<?= $txEst ?>").val(est); 
}

function btnObjBuscarDblPers<?= $id ?>(cod,nom,doc,est)
{
	$("#<?= $txCod ?>").val(cod);
	$("#<?= $txNom ?>").val(nom);
	if (<?= ($txDoc !== '' ? '1' : '2') ?> == 1) $("#<?= $txDoc ?>").val(doc);
	if (<?= ($txEst !== '' ? '1' : '2') ?> == 1) $("#<?= $txEst ?>").val(est);
	$("#BuscaObj<?= $id ?>, .window").modal('toggle');
}

function ControlesBuscarAvPersona<?= $id ?>(control,id)
{
	var checkNom,checkDoc,checkFchAlta;
							
	$("#txNombre<?= $id ?>").prop("disabled", !$("#rbNombre<?= $id ?>").is(":checked"));
	$("#txDocumento<?= $id ?>").prop("disabled", !$("#rbDocumento<?= $id ?>").is(":checked"));
	$("#fechaAltaDesde<?= $id ?>").prop("disabled", !$("#rbFechaAlta<?= $id ?>").is(":checked"));
	$("#fechaAltaHasta<?= $id ?>").prop("disabled", !$("#rbFechaAlta<?= $id ?>").is(":checked"));
	
			
	if (control=="btAceptarPers"+id)
	{
		var error;
		error ='';
		
		if ($("#rbNombre<?= $id ?>").is(":checked") && $("#txNombre<?= $id ?>").val()=='') error = 'Ingrese un Nombre';
		if ($("#rbDocumento<?= $id ?>").is(":checked") && $("#txDocumento<?= $id ?>").val()=='') error = 'Ingrese un Documento o Cuit';
		if ($("#rbFechaAlta<?= $id ?>").is(":checked") && $("#fechaAltaDesde<?= $id ?>").val()=='') error = 'Seleccione una Fecha Desde';
		if ($("#rbFechaAlta<?= $id ?>").is(":checked") && $("#fechaAltaHasta").val()=='') error = 'Seleccione una Fecha Hasta';
		
		criterio = '';
		if (error=='' && $("#rbNombre<?= $id ?>").is(":checked"))
		{
			criterio = " upper(Nombre) Like upper('%"+$("#txNombre<?= $id ?>").val()+"%')";	
		}
		if (error=='' && $("#rbDocumento<?= $id ?>").is(":checked"))
		{
			criterio = " NDoc="+$("#txDocumento<?= $id ?>").val()+" or cuit='"+$("#txDocumento<?= $id ?>").val()+"'";	
		}
		if (error=='' && $("#rbFechaAlta<?= $id ?>").is(":checked"))
		{
			if ($("#fechaAltaDesde<?= $id ?>").val() > $("#fechaAltaHasta<?= $id ?>").val())
			{
				error = 'Rango de Fecha mal ingresado';
			}else {
				criterio = " FchAlta>='"+$("#fechaAltaDesde<?= $id ?>").val()+"' and fchalta <='"+$("#echaAltaHasta").val()+"'";	
			}		
		}
		
		if (criterio=='' && error=='') error = 'No se encontraron condiciones de búsqueda';
		criterio +=" and est='A'";
		if (criterio != '')
		{
			$("#errorbuscaavpersona<?= $id ?>").html("");
			$("#errorbuscaavpersona<?= $id ?>").css("display", "none");
			
			$.pjax.reload(
			{
				container:"#GrillaBuscarAvPersona<?= $id ?>",
				data:{
					"condpers" : criterio,
					"cargar<?= $id ?>" : true
					},
				type:"POST"
			});
		}else {
			$("#errorbuscaavpersona<?= $id ?>").html(error);
			$("#errorbuscaavpersona<?= $id ?>").css("display", "block");
		}
	}
	
}

$(".check<?= $id ?>").change(function(){ControlesBuscarAvPersona<?= $id ?>($(this).attr("id"));});
</script>
