<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;
use app\models\objeto\Cem;
use yii\jui\DatePicker;
use app\utils\db\utb;
use yii\web\Session;
use yii\data\ArrayDataProvider;

$model = new Cem();
$criterio = "";

$id = isset($id) ? $id : 'cementerio'; 
$txCod = isset($txCod) ? $txCod : 'txCod'; 
$txNom = isset($txNom) ? $txNom : 'txNom'; 
 

$session = new Session;
$session->open();
$session['id'] = $id;
$session->close();


Pjax::begin(['id' => 'pjaxBusqueda' . $id, 'enableReplaceState' => false, 'enablePushState' => false]);

$cuadro_id = Yii::$app->request->get('cuadro_id', null);

$tieneFila = 0;
$tieneNume = 0;

if($cuadro_id != null){
	
	$res = Cem::getCuadro($cuadro_id);
	
	$tieneFila = $res['fila'];
	$tieneNume = $res['nume'];

}

$cuerpos = [];
		
if($cuadro_id != null)
	$cuerpos = utb::getAux('cem_cuerpo', 'cuerpo_id', 'nombre', 0, "cuadro_id = '$cuadro_id' Or trim(both ' ' from cuadro_id) = ''");
else $cuerpos = utb::getAux('cem_cuerpo', 'cuerpo_id', 'nombre', 0, "trim(both ' ' from cuadro_id) = ''");


?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>

<tr>
	<td><?= Html::radio('rb'.$id, $cuadro_id != null, ['id' => 'rbNomeclatura'.$id, 'label' => 'Nomenclatura:', 'onchange' => 'controlesBuscarAvCementerio' . $id . '("rbNomeclatura' . $id . '", "' . $id . '")'])?></td>
	<td></td>
	
	<td>
		<label>Tipo:</label>
		<?= Html::dropDownList('dlTipo'.$id, null, utb::getAux('cem_tipo'), ['id' => 'dlTipo'.$id, 'prompt' => '', 'class' => 'form-control', 'disabled' => $cuadro_id == null, 'onchange' => "cambiaTipo$id();"]) ?>
	</td>
	<td width="5px"></td>
	<td>
		<label>Cuadro:</label>
		<?= Html::dropDownList('dlCuadro'.$id, $cuadro_id, utb::getAux('cem_cuadro', 'cuadro_id'), ['id' => 'dlCuadro'.$id, 'prompt' => '', 'class' =>'form-control', 'disabled' => $cuadro_id == null, 'onchange' => 'cambiaCuadro($(this).val());']) ?>
	</td>
	<td width="5px"></td>
	<td>
		<label>Cuerpo:</label>
		<?= Html::dropDownList('dlCuerpo'.$id, null, $cuerpos, ['id' => 'dlCuerpo'.$id, 'prompt' => '', 'class' =>'form-control', 'disabled' => count($cuerpos) < 2]) ?>
	</td>
	<td width="5px"></td>
	<td width="100px">
		<label>Fila:</label>
		<?= Html::textInput('txFila'.$id, null, ['class' => 'form-control', 'id' => 'txFila'.$id, 'disabled' => !$tieneFila, 'style' => 'width:46px;', 'maxlength' => 3]) ?>
	</td>
	<td width="5px"></td>
	<td width="100px">
		<label>Nume:</label>
		<?= Html::textInput('txNume'.$id, null, ['class' => 'form-control', 'id' => 'txNume'.$id, 'disabled' => !$tieneFila, 'style' => 'width:46px;', 'maxlength' => 3]) ?>
	</td>
</tr>

<tr>
	<td><?= Html::radio('rb'.$id, false, ['id' => 'rbFallecido' . $id, 'label' => 'Nombre fallecido:', 'onchange' => 'controlesBuscarAvCementerio' . $id . '("rbFallecido' . $id . '", "' . $id . '")']) ?></td>
	<td></td>
	<td><?= Html::textInput('txNombreFallecido', null, ['id' => 'txNombreFallecido' . $id, 'class' => 'form-control', 'maxlength' => 50, 'disabled' => true]); ?></td>
</tr>
<tr>
	<td><?= Html::radio('rb'.$id, false, ['id' => 'rbResponsable' . $id, 'label' => 'Nombre responsable:', 'onchange' => 'controlesBuscarAvCementerio' . $id . '("rbResponsable' . $id . '", "' . $id . '")']) ?></td>
	<td></td>
	<td><?= Html::textInput('txNombreResponsable', null, ['id' => 'txNombreResponsable'.$id, 'class' => 'form-control', 'maxlength' => 50, 'disabled' => true]); ?></td>
</tr>

<tr>
	<td colspan='7'>
		<br><div id="errorbuscaavcem<?=$id?>" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='7'>
		<?= Html::Button('Buscar',['class' => 'btn btn-primary', 'onClick' => 'controlesBuscarAvCementerio' . $id . '("btAceptar'.$id.'","'.$id.'");'])?>
	</td>
</tr>
</table>
<?php
Pjax::end();
?>

<div style="margin-top:10px;">
<?php

//cantidad de elementos a listar
$cantidad = isset($cantidad) ? $cantidad : 40;

Pjax::begin(['id' => 'GrillaBuscarAvCem'.$id]);

$criterio = isset($criterio) ? $criterio : "";

$session = new Session;
$session->open();

if (isset($_POST['cond' . $id])) 
{
	if ($criterio !== "") $criterio .= " and ";
	$criterio .= $_POST['cond' . $id];
	$session->set('cavc', $criterio);
}else {
	$session->get('cavc', '');
}


$cargar = filter_var(Yii::$app->request->post('cargar'.$id, Yii::$app->request->get('cargarc', false)), FILTER_VALIDATE_BOOLEAN);

$dp = new ArrayDataProvider();

if(Yii::$app->request->isGet && intval(Yii::$app->request->get('page', -1)) > -1){
	$cargar = true;	
}

$session->close();

if($cargar || $criterio != '')
	$dp = $model->BuscarCemAv($criterio, 'nombre', false, $cantidad);

echo GridView::widget([
	'id' => 'GrillaDatosCem'.$id,
	'dataProvider' => $dp,
	'headerRowOptions' => ['class' => 'grilla'],
    'columns' => 		
    	[
        	['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'cod_ant','header' => 'Cod. Ant.', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'cuit','header' => 'Cuit', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'cuadro_id','header' => 'Cuadro', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'cuerpo_id','header' => 'Cuerpo', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'tipo_nom','header' => 'Tipo', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'fila','header' => 'Fila', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'nume','header' => 'Nume', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'piso','header' => 'Piso', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'cat','header' => 'Cat', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'fchingreso','header' => 'Ingreso', 'format' => ['date', 'dd/MM/yyyy'], 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'fchvenc','header' => 'Vencim.', 'format' => ['date', 'dd/MM/yyyy'], 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
            ['attribute'=>'est','header' => 'Estado', 'contentOptions'=>['style'=>'', 'class' => 'grilla']],
         ],
         
	'rowOptions' => function($model, $key, $index, $grid) use($id){
		
					return [
						'ondblclick'=>'btnObjBuscarDbl'.$id.'("'.$model['obj_id'].'","'.$model['nombre'].'")',
						'onclick'=>'btnObjBuscar'.$id.'("'.$model['obj_id'].'","'.$model['nombre'].'")'
					];
					
					} 				
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
	btnObjBuscar<?= $id ?>(cod, nom);
	$("#BuscaObj<?= $id ?>").modal('hide');
}

function controlesBuscarAvCementerio<?= $id ?>(control,id)
{
	var checkNomeclatura, checkFallecido, checkResponsable, error = '', criterio = '';;
		
	
	
	if (control !== "btAceptar<?= $id ?>")
	{
		
		checkNomeclatura = $("#rbNomeclatura<?= $id ?>").is(":checked");
		checkFallecido = $("#rbFallecido<?= $id ?>").is(":checked");
		checkResponsable = $("#rbResponsable<?= $id ?>").is(":checked");
		
		$("#dlTipo<?= $id ?>").prop("disabled", !checkNomeclatura);
		$("#dlCuadro<?= $id ?>").prop("disabled", !checkNomeclatura);
		
		$("#txNombreFallecido<?= $id ?>").prop("disabled", !checkFallecido);
		
		$("#txNombreResponsable<?= $id ?>").prop("disabled", !checkResponsable);
	}
			
	if (control=="btAceptar<?= $id ?>")
	{
		var error = '';
		
		if(error == ''){
			
			if($("#rbNomeclatura<?= $id ?>").is(":checked")){
				
				if(
					$("#dlTipo<?= $id ?> option:selected").text() == '' &&
					$("#dlCuadro<?= $id ?> option:selected").text() == '' &&
					$("#dlCuerpo<?= $id ?> option:selected").text() == '' &&
					$("#txFila<?= $id ?>").val() == '' &&
					$("#txNume<?= $id ?>").val() == ''
					)
					error = 'Elija alguna de las opciones para la nomeclatura';
				else{
					
					var tipo = $("#dlTipo<?= $id ?>").val();
					
					if(tipo !== '')
						criterio += " tipo = '" + tipo + "'";
					else{
						
						var cuadro = $("#dlCuadro<?= $id ?> option:selected").text() !== '' ? $("#dlCuadro<?= $id ?>").val() : '';
						var cuerpo = $("#dlCuerpo<?= $id ?> option:selected").text() !== '' ? $("#dlCuerpo<?= $id ?>").val() : '';
						var fila = $("#txFila<?= $id ?>").val();
						var nume = $("#txNume<?= $id ?>").val();
						
						if(cuadro !== '') criterio = " cuadro_id = '" + cuadro + "'";
						
						if(cuerpo !== ''){
							
							var c = " cuerpo_id = '" + cuerpo + "'";
						
							if(criterio == '')
								criterio = c;
							else criterio += " And " + c;
						}
						
						if(fila !== ''){
							
							var c = " fila = '" + fila + "'";
						
							if(criterio == '')
								criterio = c;
							else criterio += " And " + c;
						}
						
						if(nume !== ''){
							
							var c = " nume = " + nume;
						
							if(criterio == '')
								criterio = c;
							else criterio += " And " + c;
						}
					}
				}
			}
			
			if($("#rbFallecido<?= $id ?>").is(":checked")){
				
				if($("#txNombreFallecido<?= $id ?>").val() == '')
					error = "Ingrese un nombre de fallecido";
				else{
					var c = "obj_id In (Select obj_id From v_cem_fall Where lower(apenom) Like lower('%" + $("#txNombreFallecido<?= $id ?>").val() + "%'))"
				 	criterio = criterio != '' ? " And " + c : c;
				}
			}

			if($("#rbResponsable<?= $id ?>").is(":checked")){
				
				if($("#txNombreResponsable<?= $id ?>").val() == '')
					error = "Ingrese un nombre de responsable";
				else{
					var c = "obj_id In (Select obj_id From v_cem_fall Where lower(apenom) Like lower('%" + $("#txNombreResponsable<?= $id ?>").val() + "%'))";
				 	criterio = criterio != '' ? " And " + c : c;
				}
			}
		}
		
		
		if (criterio=='' && error=='') error = 'No se encontraron condiciones de búsqueda';
		
		if (criterio != '')
		{
			$("#errorbuscaavcem<?= $id ?>").html("");
			$("#errorbuscaavcem<?= $id ?>").css("display", "none");
			
			console.log(criterio);
			$.pjax.reload(
			{
				container:"#GrillaBuscarAvCem<?= $id ?>",
				push : false,
				replace : false,
				data:{
					"cond<?= $id ?>" : criterio,
					"cargar<?= $id ?>" : true
				},
				method:"POST"
			})
		}else {
			$("#errorbuscaavcem<?= $id ?>").html(error);
			$("#errorbuscaavcem<?= $id ?>").css("display", "block");
		}
	}
}

function cambiaTipo<?= $id ?>(){
	
	$("#dlCuadro<?= $id ?>").val("");
	$("#dlCuerpo<?= $id ?>").val("");
	$("#txFila<?= $id ?>").val("");
	$("#txNume<?= $id ?>").val("");
	
	$("#dlCuerpo<?= $id ?>").prop("disabled", true);
	$("#txFila<?= $id ?>").prop("disabled", true);
	$("#txNume<?= $id ?>").prop("disabled", true);
}

function cambiaCuadro(valor){
	
	
	$.pjax.reload({
		container : "#pjaxBusqueda<?= $id ?>",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"cuadro_id" : valor,
			"cargar<?= $id ?>" : true
		}
	});
}
</script>