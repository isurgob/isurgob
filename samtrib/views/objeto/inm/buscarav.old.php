<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\models\objeto\Inm;
use yii\jui\DatePicker;
use app\utils\db\utb;
use yii\web\Session;
use yii\data\ArrayDataProvider;

$model = new Inm();




$id = isset($id) ? $id : 'inm';
$txCod = isset($txCod) ? $txCod : 'txCod';
$txNom = isset($txNom) ? $txNom : 'txNom';
$txDoc = isset($txDoc) ? $txDoc : '';
$txEst = isset($txEst) ? $txEst : '';


$session = new Session;
$session->open();
$session->set('id', $id);
$session->close();

$criterio = "";

$datos= utb::getAuxVarios(['sam.config_inm_nc'], ['campo', 'aplica', 'nombre', 'max_largo'], [], 0, "campo in ('manz', 's1', 's2', 's3')");
	
$s1= $s2= $s3= $manz= ['campo' => '', 'aplica' => false, 'nombre' => '', 'max_largo' => 1];

if($datos !== false){
	foreach($datos as $d){
		
		switch($d['campo']){
			
			case 's1': $s1= $d; break;
			case 's2': $s2= $d; break;
			case 's3': $s3= $d; break;
			case 'manz': $manz= $d; break;
		}
	}
}
?>
<table border='0'>
	<tr>
		<td><?= Html::radio('rb'.$id,false,['id'=>'rbNombre'.$id,'label'=>'Nombre:', 'class' => 'check' . $id])?> </td>
		<td width='5px'></td>
		<td>
			<?= Html::input('text', 'txNombre'.$id, null, ['class' => 'form-control','id'=>'txNombre'.$id,'maxlength'=>'50','style'=>'width:100%','disabled'=>true]); ?>
		</td>
		<td width="20px"></td>
		<td><?= Html::radio('rb'.$id,false,['id'=>'rbDocumento'.$id,'label'=>'DNI/CUIT:', 'class' => 'check' . $id])?> </td>
		<td width='5px'></td>
		<td>
			<?= Html::input('text', 'txDoc'.$id, null, ['class' => 'form-control','id'=>'txDocumento'.$id,'maxlength'=>'8','style'=>'width:100px','onkeypress'=>'return justNumbers(event)', 'disabled' => true]); ?>
		</td>
	</tr>

	<tr>
		<td><?= Html::radio('rb'.$id, false, ['id' => 'rbNomenclatura' . $id, 'label' => 'Nomenclatura:', 'class' => 'check' . $id]); ?></td>
		<td></td>
		<td>
			<table>
				<tr>
					<td>
						<label style="font-size:11px;"><?= $s1['nombre'] ?></label>
						<br>
						<?= Html::textInput(null, null, ['id' => 'txS1' . $id, 'class' => 'form-control', 'disabled' => true, 'maxlength' => $s1['max_largo'], 'style' => 'width:50px;']); ?>
					</td>
					<td width="5px"></td>
					<td>
						<label style="font-size:11px;"><?= $s2['nombre'] ?></label>
						<br>
						<?= Html::textInput(null, null, ['id' => 'txS2' . $id, 'class' => 'form-control', 'disabled' => true, 'maxlength' => $s2['max_largo'], 'style' => 'width:50px;']); ?>
					</td>
					<td width="5px"></td>
					<td>
						<label style="font-size:11px;"><?= $s3['nombre'] ?></label>
						<br>
						<?= Html::textInput(null, null, ['id' => 'txS3' . $id, 'class' => 'form-control', 'disabled' => true, 'maxlength' => $s3['max_largo'], 'style' => 'width:50px;']); ?>
					</td>
					<td width="5px"></td>
					<td>
						<label style="font-size:11px;">Manz</label>
						<br>
						<?= Html::textInput(null, null, ['id' => 'txManzana' . $id, 'class' => 'form-control', 'disabled' => true, 'maxlength' => $manz['max_largo'], 'style' => 'width:50px;']); ?>
					</td>
				</tr>
			</table>
		</td>
	
		<td></td>
		<td><?= Html::radio('rb'.$id,false,['id'=>'rbPartidaProvincial'.$id,'label'=>'Part. Prov.:', 'class' => 'check' . $id])?> </td>
		<td width='5px'></td>
		<td>
			<?= Html::input('text', 'txPartProv'.$id, null, ['class' => 'form-control','id'=>'txPartidaProvincial'.$id,'maxlength'=>'8','style'=>'width:100px','disabled'=>true]); ?>
		</td>
	</tr>
</table>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td colspan='3'>
		<br><div id="errorbuscaavinm<?=$id?>" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='3'>
		<?= Html::Button('Buscar',['class' => 'btn btn-primary', 'onClick' => 'ControlesBuscarInm' . $id . '("btAceptar'.$id.'","'.$id.'");'])?>
	</td>
</tr>
</table>

<div style="margin-top:10px;">
<?php
//cantidad de elementos a listar
$cantidad = isset($cantidad) ? $cantidad : 40;

Pjax::begin(['id' => 'GrillaBuscarInm'.$id, 'enableReplaceState' => false, 'enablePushState' => false]);

$criterio = isset($criterio) ? $criterio : '';
 
$session = new Session;
$session->open();

if (isset($_POST['cond'])) 
{
	if ($criterio !== "") $criterio .= " and ";
	$criterio .= $_POST['cond']; 
	$session->set('cavi', $criterio);
}else {
	$criterio= $session->get('cavi', '');
}

$id = $session->get('id', 'inm');

$session->close();

$cargar = filter_var(Yii::$app->request->post('cargar' . $id, false), FILTER_VALIDATE_BOOLEAN);

$dp = null;

if($cargar || $criterio != '')
	$dp = $model->buscarInm($criterio, 'obj_id', $cantidad);
else $dp = new ArrayDataProvider(['allModels' => []]);

echo GridView::widget([
	'id' => 'GrillaDatosInmuebles'.$id,
	'dataProvider' => $dp,
	'headerRowOptions' => ['class' => 'grilla'],
	'rowOptions' => function ($model,$key,$index,$grid) use($id) 
        				{							
        					return 
        					[
								'ondblclick'=>'btnObjBuscarDbl'.$id.'("'.$model['obj_id'].'","'.$model['nombre'].'")',
								'onclick'=>'btnObjBuscar'.$id.'("'.$model['obj_id'].'","'.$model['nombre'].'")'
							];
        				},
    'columns' => 		
    	[
        	['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'width:60px', 'class' => 'grilla']],
            ['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'width:220px', 'class' => 'grilla']],
            ['attribute'=>'nc','header' => 'Nomencl.', 'contentOptions'=>['style'=>'width:80px', 'class' => 'grilla']],
            ['attribute'=>'dompar_dir','header' => 'Dom. Parc.', 'contentOptions'=>['style'=>'width:250px', 'class' => 'grilla']],
            ['attribute'=>'ndoc','header' => 'DNI/CUIT', 'contentOptions'=>['style'=>'width:80px', 'class' => 'grilla']],
            ['attribute'=>'est_nom','header' => 'Estado', 'contentOptions'=>['style'=>'width:60px', 'class' => 'grilla']],
            
         ],
    ]); 


Pjax::end();
?>
</div>
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

function ControlesBuscarInm<?= $id ?>(control,id)
{
	var checkDoc,checkNom,checkObj,checkParP,checkManz, nc;
	
	
	$("#txNombre<?= $id ?>").prop("disabled", !$("#rbNombre<?= $id ?>").is(":checked"));
	$("#txDocumento<?= $id ?>").prop("disabled", !$("#rbDocumento<?= $id ?>").is(":checked"));
	$("#txPartidaProvincial<?= $id ?>").prop("disabled", !$("#rbPartidaProvincial<?= $id ?>").is(":checked"));
	
	nc = $("#rbNomenclatura<?= $id ?>").is(":checked");
	
	$("#txS1<?= $id ?>").prop("disabled", !nc);
	$("#txS2<?= $id ?>").prop("disabled", !nc);
	$("#txS3<?= $id ?>").prop("disabled", !nc);
	$("#txManzana<?= $id ?>").prop("disabled", !nc);
	
			
	if (control=="btAceptar"+id)
	{
		var error;
		error ='';
		
		if ($("#rbNombre<?= $id ?>").is(":checked") && $("#txNombre<?= $id ?>").val()=='') error = "Ingrese un Nombre";
		if ($("#rbDocumento<?= $id ?>").is(":checked") && $("#txDoc<?= $id ?>").val()=='') error = 'Ingrese un Documento';
		
		if ($("#rbPartidaProvincial<?= $id ?>").is(":checked") && $("#txPartProv<?= $id ?>").val()=='') error = 'Seleccione una Partida Provincial';
				
		
		criterio = '';
		if (error=='' && $("#rbNombre<?= $id ?>").is(":checked"))
		{
			criterio = " upper(Nombre) Like upper('%"+$("#txNombre"+id).val()+"%')";	
		}
		
		if (error=='' && $("#rbDocumento<?= $id ?>").is(":checked"))
		{
			criterio = " NDoc="+$("#txDoc"+id).val();	
		}
		
		if (error=='' && $("#rbPartidaProvincial<?= $id ?>").is(":checked"))
		{
				criterio = " parp='"+$("#txPartProv"+id).val()+"'";	
	
		}
		
		if($("#rbNomenclatura<?= $id ?>").is(":checked")){
			
			if($("#txS1<?= $id ?>").val() == '') error = "Ingrese <?= $s1['nombre']; ?>";
			if($("#txS2<?= $id ?>").val() == '') error = "Ingrese <?= $s2['nombre']; ?>";
			if($("#txS3<?= $id ?>").val() == '') error = "Ingrese <?= $s3['nombre']; ?>";
			if($("#txManzana<?= $id ?>").val() == '') error = "Ingrese Manzana";
			
			if(error.length == 0){
				
				var s1= $("#txS1<?= $id ?>").val();
				var s2= $("#txS2<?= $id ?>").val();
				var s3= $("#txS3<?= $id ?>").val();
				var manzana= $("#txManzana<?= $id ?>").val();
				
				c = "substr(nc, 0, <?= $s1['max_largo'] + $s2['max_largo'] + $s3['max_largo'] + $manz['max_largo'] ?>) Like sam.uf_inm_armar_nc('" + s1 + "', '" + s2 + "', '" + s3 + "', '" + manzana + "', '')";
				criterio = criterio == "" ? c : criterio + " And " + c;
				
				
			}
		}
		
		console.log(criterio);
		if (criterio=='' && error=='') error = 'No se encontraron condiciones de búsqueda';
		
		if (error=='')
		{
			$("#errorbuscaavinm<?= $id ?>").html("");
			$("#errorbuscaavinm<?= $id ?>").css("display", "none");
			
			criterio += " and est='A'";
			
			$.pjax.reload(
			{
				container:"#GrillaBuscarInm"+id,
				data:{cond:criterio, "cargar<?= $id ?>":true},
				method:"POST"
			})
		}else {
			$("#errorbuscaavinm<?= $id ?>").html(error);
			$("#errorbuscaavinm<?= $id ?>").css("display", "block");
		}
	}
	
}

$(".check<?= $id ?>").change(function(){ControlesBuscarInm<?= $id ?>($(this).attr("id"));});
</script>