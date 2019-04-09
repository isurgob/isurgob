<?php

use yii\helpers\Html;
use yii\Widgets\ActiveForm;
use app\models\ctacte\CalcMulta;
use yii\jui\DatePicker;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\utils\db\utb;

$model = new CalcMulta;

Pjax::begin(['id' => 'ActControles']); // inicio bloque de calculo

$anio=0;
$cuota=0;
$objeto_id='';
$objeto_nom='';
$monto=0;
$fchpago='';
$control='';
$total=0;
$trib_id = $trib_id;

if (isset($_POST['control'])) $control=$_POST['control'];
if (isset($_POST['anio'])) $anio=$_POST['anio'];
if (isset($_POST['cuota'])) $cuota=$_POST['cuota'];
if (isset($_POST['objeto_id'])) $objeto_id=$_POST['objeto_id'];
if (isset($_POST['monto'])) $monto=$_POST['monto'];
if (isset($_POST['fchpago'])) $fchpago=$_POST['fchpago'];
if (isset($_POST['trib_id'])) $trib_id=$_POST['trib_id'];

if ($control=='obj_id' or $control=='total')
{
	echo '<script>$("#obj_nom").val("")</script>';
	if (strlen($objeto_id) < 8)
	{
		$objeto_id = utb::GetObjeto((int)utb::GetTObjTrib($trib_id),(int)$objeto_id);
		echo '<script>$("#obj_id").val("'.$objeto_id.'")</script>';
	}
	
	if (utb::GetTObj($objeto_id)==utb::GetTObjTrib($trib_id))
	{
		$objeto_nom = utb::getNombObj("'".$objeto_id."'");
	
		echo '<script>$("#obj_nom").val("'.$objeto_nom.'")</script>';		
	}

}
 
if ($control=='total')
{
	$periodo = $anio*1000+$cuota;
	
	$error = '';
	if ($trib_id == 0) $error .='Seleccione un tributo de la grilla <br>';
	if ($objeto_nom == '') $error .='Ingrese un Objeto v�lido <br>';
	if ($periodo == 0) $error .='Periodo mal ingresado <br>';
	if ($fchpago == '') $error .='Seleccione una fecha de pago <br>';
			
	if ($error == '')
	{
		$total = $model->CalcularMulta($trib_id,"'".$objeto_id."'",$periodo,$monto,"'".$fchpago."'");
		echo '<script>$("#total").val('.$total.')</script>';	
		echo '<script>$("#error").css("display", "none")</script>';
	}else {
		echo '<script>$("#error").html("'.$error.'")</script>';
		echo '<script>$("#error").css("display", "block")</script>';
	}
		
}

Pjax::end();// fin bloque de calculo

if ($trib_id==0)
{
	echo '<script>$("#info").css("display", "block")</script>';
}else echo '<script>$("#info").css("display", "none")</script>';
?>

<table border='0' width='100%'>
	<tr>
		<td colspan='2'>
			<div id='info' class="alert alert-warning">
				Antes de realizar el cálculo debe seleccionar un elemento de la grilla
			</div>
		</td>
	</tr>	
	<tr>
		<td><label class="control-label">Tributo:</label></td>
		<td>
			<?= Html::input('text', 'trib_id', $trib_id, ['class' => 'form-control','id'=>'trib_id','style'=>'width:80px;background:#E6E6FA;','disabled'=>'true']); ?>
			<?= Html::input('text', 'trib_nom', utb::getNombTrib($trib_id), ['class' => 'form-control','id'=>'trib_nom','style'=>'width:370px;background:#E6E6FA;','disabled'=>'true']); ?>
		</td>
	</tr>
	<tr>
		<td><label class="control-label">Objeto:</label></td>
		<td>
			<?= Html::input('text', 'obj_id', $objeto_id, ['class' => 'form-control','id'=>'obj_id','style'=>'width:80px','maxlength'=>'8','onchange'=>'ObjNombre("obj_id")']); ?>
			<!-- boton de b�squeda modal -->
			<?php
			echo '<script>$.pjax.reload({container:"#PjaxObjBusAv",data:{tobjeto:'.utb::GetTObjTrib($trib_id).'},method:"POST"});</script>';
			
			Modal::begin([
                'id' => 'BuscaObjmul',
				'toggleButton' => [
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar'
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
                'size' => 'modal-lg',
            ]);
          
            echo $this->render('//objeto/objetobuscarav',[
							'id' => 'mul', 'txCod' => 'obj_id', 'txNom' => 'obj_nom'
            					]);
           
            Modal::end();
            ?>
            <!-- fin de boton de b�squeda modal -->
			<?= Html::input('text', 'obj_nom', $objeto_nom, ['class' => 'form-control','id'=>'obj_nom','style'=>'width:340px;background:#E6E6FA;','disabled'=>'true']); ?>
		</td>
	</tr>
	<tr>
		<td><label class="control-label">Periodo:</label></td>
		<td>
			<?= Html::input('text', 'anio', $anio, ['class' => 'form-control','id'=>'anio','style'=>'width:40px','maxlength'=>'4']); ?>
			<?= Html::input('text', 'cuota', $cuota, ['class' => 'form-control','id'=>'cuota','style'=>'width:35px','maxlength'=>'3']); ?>
				
			<label class="control-label">Monto:</label>
			<?= Html::input('text', 'monto', $monto, ['class' => 'form-control','id'=>'monto','style'=>'width:70px','maxlength'=>'8']); ?>
				
			<label class="control-label">Fecha Pago:</label>
			<?= 
				DatePicker::widget(
					[
						'id' => 'fchpago',
						'name' => 'fchpago',
						'dateFormat' => 'dd/MM/yyyy',
						'value' => $fchpago,
					]
				);
			?>
		</td>
	</tr>
	<tr><td>&nbsp;</td><td></td></tr>
	<tr>
		<td></td>		
		<td>
			<?= Html::Button('Calcular',['class' => 'btn btn-primary', 'onClick' => 'Calcular("total")']) ?>
			<label class="control-label">Total:</label>
			<?= Html::input('text', 'total', $total, ['class' => 'form-control','id' => 'total','disabled'=>'true','style' => 'width:90px;background:#E6E6FA; text-align:right']); ?>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<br><div id="error" style="display:none;" class="alert alert-danger alert-dismissable"></div>
		</td>
	</tr>		
</table>

<script>
function Calcular(ctrl)
{
	$.pjax.reload(
		{container:"#ActControles",
		 data:{objeto_id:$("#obj_id").val(),anio:$("#anio").val(),cuota:$("#cuota").val(),
		 	   monto:$("#monto").val(),fchpago:$("#fchpago").val(),trib_id:$("#trib_id").val(),control:ctrl
		 	  },
		 method:"POST"})
}

function ObjNombre(ctrl)
{
	$.pjax.reload(
		{
			container:"#ActControles",
			data:{objeto_id:$("#obj_id").val(),trib_id:$("#trib_id").val(),control:ctrl},
			method:"POST"
		}
	)
}

</script>
