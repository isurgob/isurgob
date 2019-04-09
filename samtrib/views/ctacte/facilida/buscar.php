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
	$tobj = 0;
	
	if (isset($_POST['objeto_id']))
	{
		$objeto_id=$_POST['objeto_id'];
	
		if (isset($_POST['tobj'])) 
			$tobj=$_POST['tobj'];
		
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
				
		} else 
		{
			echo '<script>$("#txObj_Id").val("")</script>';
		}
		
		echo '<script>$.pjax.reload({container:"#PjaxObjBusAvBuscador",data:{tobjeto:'.$tobj.'},method:"POST"});</script>';
	} 
		
	
Pjax::end();
//FIN Bloque actualiza los códigos de objeto

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscarFacilida']);
?>
<div style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">

<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td width="65px"><?= Html::radio('rbCodigo',true,['id'=>'rbCodigo','label'=>'Código:','onchange' => 'ControlesBuscarFacilida("txCodigo")'])?></td>
		<td width="100px">
			<?= Html::input('text','txCodigoFacilida',null,['id'=>'txCodigoFacilida','class'=>'form-control','style'=>'width:100px;','onkeypress'=>'return justNumbers(event)','maxlength'=>'8']); ?>
		</td>
	</tr>
</table>

<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td width="65px"><?= Html::radio('rbBuscaObj',false,['id'=>'rbBuscaObj','label'=>'Objeto:','onchange' => 'ControlesBuscarFacilida("rbBuscaObj")'])?></td>
		<td>
			<?= Html::dropDownList('dlTObjeto', null, utb::getAux('objeto_tipo','cod','nombre',0), [
						'disabled' => true,
						'class' => 'form-control',
						'id'=>'dlTObjeto',
						'style' => 'width:100px',
						'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{objeto_id:$("#txObj_Id").val(),tobj:$("#dlTObjeto").val()},method:"POST"})']);
			?> 
	    </td>
	    <td><?= Html::input('text', 'txObj_Id', null, [
						'disabled' => true, 
						'class' => 'form-control', 
						'id'=>'txObj_Id','style'=>'width:80px',
						'maxlength'=>'8',
						'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{objeto_id:$("#txObj_Id").val(),tobj:$("#dlTObjeto").val()},method:"POST"})']);
			?>
		</td>
		<td><?= Html::Button("<i class='glyphicon glyphicon-search'></i>",[
					'class' => 'bt-buscar',
					'id' => 'btBuscarObj',
					'disabled' => true,
					'onclick' => '$("#BuscaObjFacilidaBuscar").toggle();',
				]);
			?>
		</td>
		<td>
			<?= Html::input('text', 'txObjNom', null, ['class' => 'form-control','id'=>'txObjNom','style'=>'width:300px','disabled'=>'true']); ?>
		</td>
	</tr>
</table>

<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px;margin-top:10px">
	<tr>
		<td colspan='3'>
			<?= Html::Button('Buscar',['class' => 'btn btn-primary', 'id' => 'btBuscarPlanAceptar', 'onClick' => 'ControlesBuscarFacilida("btAceptar");'])?>
		</td>
	</tr>
</table>

<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td>
			<div id='BuscaObjFacilidaBuscar' style='display:none;margin:20px 0px;'>
				<div align='right'>
					<?php //Html::Button("x",['class' => 'bt-rojo', 'onclick' => '$("#BuscaObjFacilidaBuscar").css("display", "none");']); ?>
				</div>
				<div id='BuscaFacilidaObjPag'>
					<?php
						echo $this->render('//objeto/objetobuscarav',[
								'idpx' => 'Buscador','id' => 'facilidabuscar', 'txCod' => 'txObj_Id', 'txNom' => 'txObjNom'
			        		]);
					?>
		     	</div>
		    </div>
		</td>
	</tr>
</table>

<div id="buscarFacilidad_errorSummary" class="error-summary" style="display:none;margin-top:8px">

<ul>
</ul>

</div>

</div>

<?php ActiveForm::end(); ?>

<script>
function ControlesBuscarFacilida(control)
{
	$("#BuscaObjFacilidaBuscar").css("display", "none");
	
	if ( control == "rbBuscaObj" || control == "txCodigo" ) 
	{
		$("#txObj_Id").val("");
		$("#ObjNombre").val("");
		$("#txCodigoFacilida").val("");
		
		checkObj = control=="rbBuscaObj" && $('input:radio[name=rbBuscaObj]:checked').val()==1 ? true : false;
		checkNroConv = control=="txCodigo" && $('input:radio[name=rbCodigo]:checked').val() ==1 ? true : false; 
								
		$("#dlTObjeto").prop("disabled",!checkObj);
		$("#btBuscarObj").prop("disabled",!checkObj);
		$("#txObj_Id").prop("disabled",!checkObj);
		$("#txCodigoFacilida").prop("disabled",!checkNroConv);
		
		$("#rbBuscaObj").prop("checked",checkObj);
		$("#rbCodigo").prop("checked",checkNroConv);
	}
	
	if ( control == "btAceptar" )
	{
		var error = new Array();
		
		if ( $("#txCodigoFacilida").val() =='' && $('input:radio[name=rbCodigo]:checked').val() == 1 ) 
			error.push ( "Ingrese un Código de Facilidad." );
			
		if ( $("#txObjNom").val() == "" && $("#txObj_Id").val() == "" && $('input:radio[name=rbBuscaObj]:checked').val() == 1 ) 
			error.push ( "Ingrese un Objeto." );
				
		if ( error.length == 0 )
		{
			$("#frmBuscarFacilida").submit();
		} else 
		{
			mostrarErrores( error, "#buscarFacilidad_errorSummary" );
		}
	}
}
</script>