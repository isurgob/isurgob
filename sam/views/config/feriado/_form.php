<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use \yii\widgets\Pjax;
use app\models\config\Feriado;
use app\utils\db\utb;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Feriado */
/* @var $form yii\widgets\ActiveForm */

Pjax::begin(['id'=>'DatosFeriado']);
$id = (isset($_POST['valor']) ? $_POST['valor'] : 0);
$consulta = (isset($_POST['consulta']) ? $_POST['consulta'] : $consulta);
if ($id != 0) $fecha = substr($id,0,2).'/'.substr($id,2,2).'/'.date('Y');

$feriado = Feriado::findOne($fecha);
if ($feriado != null)
{
	$fecha = $feriado->fecha;
	$detalle = $feriado->detalle;
	$modif = utb::getFormatoModif($feriado->usrmod,$feriado->fchmod);
}else {
	$fecha = ($fecha != '' && $consulta == 0 ? $fecha : null);
	$detalle = '';
	$modif = '';
}

$action = 'index';
if ($consulta == 0) $action = Yii::$app->param->urlsam.'config/feriado/create';
if ($consulta == 3) $action = Yii::$app->param->urlsam.'config/feriado/update';
if ($consulta == 2) $action = Yii::$app->param->urlsam.'config/feriado/delete';
$form = ActiveForm::begin(['id' => 'frmFeriado','action'=> $action]);
?>
<div class="form" style='padding:5px;clear:left;width:710px;float:left;margin-right:2px;margin-bottom:5px'>
	<table>
		<tr>
			<td><label>Fecha: &nbsp;</label></td>
			<td>
			<?= 
				DatePicker::widget([
					'id' => 'Fecha',
					'name' => 'Fecha',
					'dateFormat' => 'dd/MM/yyyy',
					'value' => $fecha,
					'options' => ['class'=>'form-control','style'=>'width:70px']
				]);
				
			?>
			</td>
		</tr>
		<tr>
			<td valign="top"><label>Detalle: &nbsp;</label></td>
			<td> 
            <?= Html::textarea('txDetalle', $detalle, ['class' => 'form-control','id'=>'txDetalle','maxlength'=>'250','style'=>'width:600px;height:100px; max-width:600px; max-height:100px;']); ?>
			</td>
		</tr>
		<tr>
			<td><label> Modificación: &nbsp;</label></td>
			<td>
			<?= Html::input('text', 'txModif', $modif, ['class' => 'form-control','id'=>'txModif', 'disabled'=>'true','style'=>'width:250px; background:#E6E6FA;']); ?>
			</td>
		</tr>
	</table>
</div>
<div style='float:left'>
	<?php if (utb::getExisteProceso(3054)) { ?>
		<?= Html::Button('<i class="glyphicon glyphicon-plus"></i>',['id'=>'btNuevo', 'class' => 'bt-buscar','style'=>'margin-bottom:2px', 'onClick' => 'MostrarFeriado(0,0)']) ?><br>
		<?= Html::Button('<i class="glyphicon glyphicon-pencil"></i>',['id'=>'btModif','class' => 'bt-buscar','style'=>'margin-bottom:2px', 'onClick' => 'MostrarFeriado("'.$id.'",3)']) ?><br>
		<?= Html::Button('<i class="glyphicon glyphicon-trash"></i>',['id'=>'btElim','class' => 'bt-buscar', 'onClick' => 'MostrarFeriado("'.$id.'",2)']) ?>
	<?php } ?>
</div>
<div id='DivBtGrabarCancelar' style='clear:left;display:none'>
	<?php
		echo Html::submitButton($consulta == 2 ? 'Eliminar' : 'Grabar', ['class' => $consulta == 2 ? 'btn btn-danger' : 'btn btn-success','id'=>'btGrabar']);
		echo "&nbsp;&nbsp;";
		echo Html::a('Cancelar', ['index'],['class' => 'btn btn-primary']);
    ?>
</div>
<?php 
		if(isset(Yii::$app->session['error']) and Yii::$app->session['error'] !== '')
		{  
			echo '<div class="error-summary" style="clear:both;margin-top:5px">Por favor corrija los siguientes errores:<br/><ul>' . Yii::$app->session['error'] . '</ul></div>';
			echo '<script>$("html, body").animate({scrollTop: $(".calc-feriado-index").height()}, 1000);</script>';
		} 
		Yii::$app->session['error'] = '';
        Yii::$app->session['msj'] = '';
	ActiveForm::end();
	
	// Habilito Controles Segun estado de consulta
	if ($consulta == 1 or $consulta == 2) // Si es Consulta o Eliminacion
	{
		echo "<script>";
		echo "$('#Fecha').css('pointer-events','none');";
		echo "$('#Fecha').attr('readonly',true);";
		echo "$('#txDetalle').attr('readonly',true);";
		echo "</script>";
	} 
	if ($consulta == 0) // Si es Nuevo
	{
		echo "<script>";
		echo "$('#Fecha').css('pointer-events','all');";
		echo "$('#Fecha').attr('readonly',false);";
		echo "$('#txDetalle').attr('readonly',false);";
		echo "</script>";
	} 
	if ($consulta == 3) // Si es Modificacion
	{
		echo "<script>";
		echo "$('#Fecha').css('pointer-events','none');";
		echo "$('#Fecha').attr('readonly',true);";
		echo "$('#txDetalle').attr('readonly',false);";
		echo "</script>";
	}
	// Habilito botones según estado de consulta
	
	if ($consulta == 1) // Si es Consulta solo habilito el boton Nuevo
	{
		echo "<script>";
		echo "$('#btNuevo').css('display','block');";
		if ($fecha != null and $fecha != '') // Si existe el feriado
		{		
			echo "$('#btModif').css('display','block');";
			echo "$('#btElim').css('display','block');";	
		}else {
			echo "$('#btModif').css('display','none');";
			echo "$('#btElim').css('display','none');";
		}
		echo "$('#DivBtGrabarCancelar').css('display','none');";
		echo "$('#DivCalen').css('pointer-events','all');";
		echo "</script>";
	}else {
		echo "<script>";
		echo "$('#btNuevo').css('display','block');";
		echo "$('#btModif').css('display','block');";
		echo "$('#btElim').css('display','block');";
		echo "$('#DivBtGrabarCancelar').css('display','block');";
		echo "$('#DivCalen').css('pointer-events','none');";
		echo "</script>";
	}
Pjax::end(); 
?>

<script>
function MostrarFeriado(id,c)
{
	$.pjax.reload({
		container:"#DatosFeriado",
		data:{valor:id,consulta:c},
		method:"POST"
	});
	
	$('html, body').animate({scrollTop: $(".calc-feriado-index").height()}, 1000); //mueve escroll a la parte de los datos
}
</script>