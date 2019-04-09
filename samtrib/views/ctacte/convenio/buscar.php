<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

?>
<style>
#opcBuscar .modal-content{
	min-width:700px !important;
}
</style>
<?php
Pjax::begin(['id' => 'ObjNombre']);
	
	$objeto_id = '';
	$tobj = 3;
	if (isset($_POST['objeto_id'])) $objeto_id=$_POST['objeto_id'];
	if (isset($_POST['tobj'])) $tobj=$_POST['tobj'];
	
	echo '<script>$("#txObjNom").val("")</script>';
	if (strlen($objeto_id) < 8)
	{
		$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
		echo '<script>$("#txObj_Id").val("'.$objeto_id.'")</script>';
	}
	
	if (utb::GetTObj($objeto_id)==$tobj)
	{
		$objeto_nom = utb::getNombObj("'".$objeto_id."'");
	
		echo '<script>$("#txObjNom").val("'.$objeto_nom.'")</script>';		
	}else {
		echo '<script>$("#txObj_Id").val("")</script>';
	}
	
	?>
	<script>
	$("#ObjNombre").on("pjax:end", function() {
		
		$.pjax.reload({
			container: "#plannuevo_buscar",
			type: "GET",
			replace: false,
			push: false,
			data: {
				plan_tobj: <?= $tobj ?>,
			},
		});
		
		$("#ObjNombre").off("pjax:end");
	});
		
	</script>
	
	<?php
	
	
Pjax::end();

$form = ActiveForm::begin(['action' => ['buscarplan'],'id'=>'frmBuscarPlan']);
?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td><?= Html::radio('rbNroConv',true,['id'=>'rbNroConv','label'=>'Nro. Convenio:','onchange' => 'ControlesBuscarPlan("rbNroConv")'])?></td>
	<td width='5px'></td>
	<td colspan='2'>
		<?= Html::input('text', 'txNroConv', null, [
				'class' => 'form-control',
				'id'=>'txNroConv',
				'maxlength'=>'6',
				'onkeypress' => 'return justNumbers( event )',
				'style'=>'width:100px']); ?>
	</td>
</tr>
<tr>
	<td><?= Html::radio('rbNroConvAnt',false,['id'=>'rbNroConvAnt','label'=>'Convenio Anterior:','onchange' => 'ControlesBuscarPlan("rbNroConvAnt")'])?></td>
	<td width='5px'></td>
	<td colspan='2'>
		<?= Html::input('text', 'txNroConvAnt', null, [
				'class' => 'form-control',
				'id'=>'txNroConvAnt',
				'maxlength'=>'15',
				'disabled' => true,
				'style'=>'width:100px']); ?>
	</td>
</tr>
<tr>
	<td><?= Html::radio('rbBuscaObj',false,['id'=>'rbBuscaObj','label'=>'Objeto:','onchange' => 'ControlesBuscarPlan("rbBuscaObj")'])?></td>
	<td width='5px'></td>
	<td>
		<?php echo Html::dropDownList('dlTObjeto', null, utb::getAux('objeto_tipo'), ['disabled' => true, 'class' => 'form-control','id'=>'dlTObjeto',
    		'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{objeto_id:$("#txObj_Id").val(),tobj:$("#dlTObjeto").val()},method:"POST"})']); 
    		
    		echo Html::input('text', 'txObj_Id', null, ['disabled' => true, 'class' => 'form-control','id'=>'txObj_Id','style'=>'width:80px','maxlength'=>'8',
			'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{objeto_id:$("#txObj_Id").val(),tobj:$("#dlTObjeto").val()},method:"POST"})']);
			
			echo Html::Button("<i class='glyphicon glyphicon-search'></i>",[
					'class' => 'bt-buscar',
					'id' => 'btBuscarObj',
					'disabled' => true, 
					'onclick' => '$("#BuscaObjplanbuscar").toggle()',
				]);
		?>
	</td>
	<td>
		<?php	
			echo Html::input('text', 'txObjNom', null, ['class' => 'form-control','id'=>'txObjNom','style'=>'width:300px','disabled'=>'true']); 
    	?>
     </td>
</tr>
<tr>
	<td colspan='3'>
		<br><div id="errorbuscaplan" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>	
<tr>
	<td colspan='3'>
		<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'id' => 'btBuscarPlanAceptar', 'onClick' => 'ControlesBuscarPlan("btAceptar");'])?>
	</td>
</tr>
</table>

<table width="100%" border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
<tr>
	<td colspan='4'>
	<div id='BuscaObjplanbuscar' style='display:none;margin:20px 0px;'>
		<hr style="color: #ddd; margin:5px 0px"  /> 
		<div>
		<?php
			
			Pjax::begin(['id' => 'plannuevo_buscar', 'enablePushState' => false, 'enableReplaceState' => false]);
			    
			    $tobj = Yii::$app->request->get('plan_tobj', 4 );
			    
			    echo $this->render('//objeto/objetobuscarav',[
						'id' => 'cc', 'txCod' => 'txObj_Id', 'txNom' => 'txObjNom', 'tobjeto' => $tobj, 'selectorModal' => '#BuscaObjplanbuscar'
			    	]);
			       
		    Pjax::end();
		?>
     	</div>
     </div>
	</td>
</tr>
</table>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscarPlan(control)
{
	$("#BuscaObjplanbuscar").css("display", "none");
	if (control=="rbBuscaObj" || control=="rbNroConv" || control=="rbNroConvAnt")
	{
		$("#txObj_Id").val("");
		$("#ObjNombre").val("");
		$("#txNroConv").val("");
		$("#txNroConvAnt").val("");
		
		checkObj = control=="rbBuscaObj" && $('input:radio[name=rbBuscaObj]:checked').val()==1 ? true : false;
		checkNroConv = control=="rbNroConv" && $('input:radio[name=rbNroConv]:checked').val() ==1 ? true : false; 
		checkNroConvAnt = control=="rbNroConvAnt" && $('input:radio[name=rbNroConvAnt]:checked').val() ==1 ? true : false; 
								
		$("#dlTObjeto").prop("disabled",!checkObj);
		$("#btBuscarObj").prop("disabled",!checkObj);
		$("#txObj_Id").prop("disabled",!checkObj);
		$("#txNroConv").prop("disabled",!checkNroConv);
		$("#txNroConvAnt").prop("disabled",!checkNroConvAnt);
		
		$("#rbBuscaObj").prop("checked",checkObj);
		$("#rbNroConv").prop("checked",checkNroConv);
		$("#rbNroConvAnt").prop("checked",checkNroConvAnt);
	}
	
	if (control=="btAceptar")
	{
		var error;
		error ='';
		
		if ($("#txNroConv").val()=='' && $('input:radio[name=rbNroConv]:checked').val()==1) error += '<li>Ingrese un Nº de Convenio</li>';
		if ($("#txNroConvAnt").val()=='' && $('input:radio[name=rbNroConvAnt]:checked').val()==1) error += '<li>Ingrese un Nº de Convenio Anterior</li>';
		if ($("#txObjNom").val() == "" && $("#txObj_Id").val() == "" && $('input:radio[name=rbBuscaObj]:checked').val()==1) error += "<li>Ingrese un objeto</li>";
				
		if (error=='')
		{
			$("#frmBuscarPlan").submit();
		}else {
			$("#errorbuscaplan").html(error);
			$("#errorbuscaplan").css("display", "block");
		}
	}
	
}

</script>