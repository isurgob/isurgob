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
	//width:450px !important;
}
</style>
<?php

//INICIO Bloque actualiza los códigos de objeto
Pjax::begin(['id' => 'ObjNombre']);

	$objeto_id = '';
	$trib = 28;
	
	if (isset($_POST['trib']))
		$trib = $_POST['trib'];
	
	if (isset($_POST['objeto_id'])) 
		$objeto_id = $_POST['objeto_id'];
		
	$tobj = utb::getTTrib($trib);

	echo '<script>$("#txObjNom").val("")</script>';
	
	if (strlen($objeto_id) < 8)
	{
		$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
		echo '<script>$("#txObj_Id").val("'.$objeto_id.'")</script>';
	}
	
	if (utb::GetTObj($objeto_id) == $tobj)
	{
		$objeto_nom = utb::getNombObj("'".$objeto_id."'");
	
		echo '<script>$("#txObjNom").val("'.$objeto_nom.'")</script>';	
			
	} else 
	{
		echo '<script>$("#txObj_Id").val("")</script>';
	}
	
	$subcta = utb::getCampo('trib','trib_id = ' . $trib,'uso_subcta');
	
	//Habilitar sucursal si trib.uso_subcta = 1
	if ($subcta == 1)
		echo '<script>$("#txSuc").removeAttr("disabled");</script>';
	else
		echo '<script>$("#txSuc").attr("disabled",true);</script>';

	
	//Actualiza el tipo de objeto para la búsqueda de objeto
//	if ($tobj != 0)
//		echo '<script>$(document).ready(function() {$.pjax.reload({container:"#PjaxObjBusAv",data:{tobjeto:'.$tobj.'},method:"POST"});});</script>';
	
Pjax::end();
//FIN Bloque actualiza los códigos de objeto

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscarDDJJ']);
?>

<!-- Tabla para Nº de DDJJ -->
<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td width="115px"><?= Html::radio('rbCodigo',true,['id'=>'rbCodigo','label'=>'Nº DJ:','onchange' => 'ControlesBuscarDDJJ("txCodigo")'])?></td>
		<td>
			<?= Html::input('text','txNumDDJJ',null,['id'=>'txNumDDJJ','class'=>'form-control','style'=>'width:100px;','onkeypress'=>'return justNumbers(event)','maxlength'=>'8']); ?>
		</td>
	</tr>
</table>

<!-- Tabla para Obj -->
<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td width="115px"><?= Html::radio('rbBuscaObj',false,['id'=>'rbBuscaObj','label'=>'Tributo y Objeto:','onchange' => 'ControlesBuscarDDJJ("rbBuscaObj")'])?></td>
		<td width="200px">
			<?= Html::dropDownList('dlTrib', null, utb::getAux('trib','trib_id','nombre',0,"tipo = 2 AND dj_tribprinc = trib_id AND est = 'A'"), 
						[	'style' => 'width:100%',
							'disabled' => true, 
							'class' => 'form-control',
							'id'=>'dlTrib',
	    					'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{objeto_id:$("#txObj_Id").val(),trib:$("#dlTrib").val()},method:"POST"})'
	    				]); ?>
	    </td>
	</tr>
</table>
		
<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td width="350px">
		   	<?= Html::input('text', 'txObj_Id', null, ['disabled' => true, 'class' => 'form-control','id'=>'txObj_Id','style'=>'width:80px','maxlength'=>'8',
				'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{objeto_id:$("#txObj_Id").val(),trib:$("#dlTrib").val()},method:"POST"})']); ?>
				
			<?= Html::Button("<i class='glyphicon glyphicon-search'></i>",['class' => 'bt-buscar', 'id' => 'btBuscarObj','disabled' => true, 'onClick' => '$("#BuscaObjddjjbuscar").css("display", "block");']); ?>
	
	   		<?= Html::input('text', 'txObjNom', null, ['class' => 'form-control','id'=>'txObjNom','style'=>'width:200px','disabled'=>true]); 	?>
	   	</td>
	   	<td width="40px"><label>Suc:</label></td>
	   	<td><?= Html::input('text', 'txSuc', null, ['class' => 'form-control','id'=>'txSuc','style'=>'width:50px','disabled'=>true]); 	?>
	</tr>
	<tr>
		<td colspan='3'>
			<div id='BuscaObjddjjbuscar' style='display:none;margin:20px 0px;'>
				<div align='right'>
					<?= Html::Button("x",['class' => 'bt-rojo', 'onClick' => '$("#BuscaObjddjjbuscar").css("display", "none");'])?>
				</div>
				
				<div id='BuscaPlanObjPag'>
						
				<?php
//					echo $this->render('//objeto/objetobuscarav',[
//							'id' => 'ddjjbuscar', 'txCod' => 'txObj_Id', 'txNom' => 'txObjNom'
//		        		]);
				?>
			     </div>
		     </div>
		</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan='3'>
			<br><div id="errorbuscafacilida" style="display:none;" class="alert alert-danger alert-dismissable"></div>
		</td>
	</tr>	
	<tr>
		<td colspan='3'>
			<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'id' => 'btBuscarPlanAceptar', 'onClick' => 'ControlesBuscarDDJJ("btAceptar");'])?>
		</td>
	</tr>
</table>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscarDDJJ(control)
{
	$("#BuscaObjddjjbuscar").css("display", "none");
	
	if (control=="rbBuscaObj" || control=="txCodigo")
	{
		$("#txObj_Id").val("");
		$("#ObjNombre").val("");
		$("#txNumDDJJ").val("");
		
		checkObj = control=="rbBuscaObj" && $('input:radio[name=rbBuscaObj]:checked').val()==1 ? true : false;
		checkNroConv = control=="txCodigo" && $('input:radio[name=rbCodigo]:checked').val() ==1 ? true : false; 
								
		$("#dlTrib").prop("disabled",!checkObj);
		$("#btBuscarObj").prop("disabled",!checkObj);
		$("#txObj_Id").prop("disabled",!checkObj);
		$("#txNumDDJJ").prop("disabled",!checkNroConv);
		
		$("#rbBuscaObj").prop("checked",checkObj);
		$("#rbCodigo").prop("checked",checkNroConv);
	}
	
	if (control=="btAceptar")
	{
		var error;
		error ='';
		
		if ($("#txNumDDJJ").val()=='' && $('input:radio[name=rbCodigo]:checked').val()==1) error += '<li>Ingrese un Código de Facilidad</li>';
		if ($("#txObjNom").val() == "" && $("#txObj_Id").val() == "" && $('input:radio[name=rbBuscaObj]:checked').val()==1) error += "<li>Ingrese un objeto</li>";
				
		if (error=='')
		{
			$("#frmBuscarDDJJ").submit();
		} else 
		{
			$("#errorbuscafacilida").html(error);
			$("#errorbuscafacilida").css("display", "block");
		}
	}
	
}

</script>