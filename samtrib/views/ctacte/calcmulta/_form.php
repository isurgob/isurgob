<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use app\utils\db\utb;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcMulta */
/* @var $form yii\widgets\ActiveForm */

if (isset($consulta) == null) $consulta = 0; 
?>

<div class="multa-form">

    <?php 
    	
    	$form = ActiveForm::begin(['id'=>'form-multa', 
    							   'fieldConfig' => [
        										'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
        										],
        						  ]);
    	$param = Yii::$app->params;	 
    	
     
		// comienza bloque donde actualizan controles seg�n tributo seleccionado
		Pjax::begin(['id' => 'TribOnchange']);
			$cond = 'tipo=5';
			if (isset($_POST['trib_id']) && $consulta!==1) 
			{
				$cond .= ' and trib_id='.$_POST['trib_id'];
				$items = utb::getAux('item','item_id','nombre',0,$cond);
								
				echo '<script>$("#calcmulta-item_id").empty().append("<option value=0 >Seleccione</option>");</script>';
				
				foreach ($items as $key => $value){
     				$options="<option value='".$key."'>".$value."</option>";
     				echo '<script>$("#calcmulta-item_id").append("'.$options.'");</script>';
				}
					
				if ((int)utb::GetTObjTrib($_POST['trib_id']) !== 2) 
				{
					echo '<script>$("#calcmulta-tipo").prop("disabled", true);</script>';
				} else echo '<script>$("#calcmulta-tipo").prop("disabled", false);</script>';
			}
				
		Pjax::end();// fin bloque combo tributo
	?>

<table border='0'>
	<tr>
		<?= $form->field($model, 'trib_id',['template' => $param['T_TAB_COL1_LIN2']])->dropDownList(utb::getAux('trib','trib_id','nombre',0,'Tipo = 2 or trib_id= 4'),['prompt'=>'Seleccionar...','onchange' => '$.pjax.reload({container:"#TribOnchange",data:{trib_id:this.value},method:"POST"})']) ?>
		<td width='10'></td>
		<?= $form->field($model, 'aniodesde',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['maxlength'=>'4','style' => 'width:45px;text-align:center;']) ?>
		<?= $form->field($model, 'cuotadesde',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['maxlength'=>'3','style' => 'width:35px;text-align:center;']) ?>
		<td width='10'></td>
		<?= $form->field($model, 'aniohasta',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['maxlength'=>'4','style' => 'width:45px;text-align:center;']) ?>
		<?= $form->field($model, 'cuotahasta',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['maxlength'=>'3','style' => 'width:35px;text-align:center;']) ?>
		<td width='10'></td>
		<?= $form->field($model, 'montodesde',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['maxlength'=>'7','style' => 'width:80px;text-align:right;']) ?>
		<?= $form->field($model, 'montohasta',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['maxlength'=>'7','style' => 'width:80px;text-align:right;']) ?>
	</tr>
</table>

<table border='0'>	
	<tr>
		<?= $form->field($model, 'tipo',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('comer_tipo'),['prompt'=>'Seleccionar...']) ?>
		<td width='10'></td>
		<?= $form->field($model, 'item_id',['template' => $param['T_TAB_COL4']])->dropDownList(utb::getAux('item','item_id','nombre',0,$cond),['prompt'=>'Seleccionar...']); ?>
	</tr>
	<tr>
		<?= $form->field($model, 'valormaximo',['template' => $param['T_TAB_COL1']])->textInput(['maxlength'=>'7','style' => 'width:80px;text-align:right;']) ?>
		<td width='10'></td>
		<?= $form->field($model, 'tcalculo',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('calc_multa_tfcalculo'),['prompt'=>'Seleccionar...','onchange'=>'tcalculoOnChange(this.value)']) ?>
		<td width='10'></td>
		<?= $form->field($model, 'valor',['template' => $param['T_TAB_COL3']])->textInput(['maxlength'=>'7','style' => 'width:80px;text-align:right;']) ?>
	</tr>
	<tr>
		<?= $form->field($model, 'alicuota',['template' => $param['T_TAB_COL1']])->textInput(['maxlength'=>'6','style' => 'width:80px;text-align:right;']) ?>
		<td width='10'></td>
		<?= $form->field($model, 'quita',['template' => $param['T_TAB_COL1']])->textInput(['maxlength'=>'4','style' => 'width:80px;text-align:right;','onblur'=>'TextInputQuitaOnBlur(this.value)']) ?>
		<?= $form->field($model, 'finmes',['template' => $param['T_TAB_COL1']])->radioList(['Fin de Mes','Dias Venc.: '],['onchange'=>'finmesOnChange($("#calcmulta-finmes :radio:checked").val())']) ?>
		<?= $form->field($model, 'diasvenc',['template' => $param['T_TAB_COL1']])->textInput(['maxlength'=>'2','style' => 'width:40px;text-align:center;' ]) ?>
	</tr>
	<tr><td colspan='10' height='20'></td></tr>
	<tr>
		<td colspan='10' align='right'>
			<?= $form->field($model, 'modif',['template' => $param['T_DIV']])->textInput(['style' => 'width:200px;background:#E6E6FA;','disabled'=>true ]) ?>
		</td>
	</tr>
	<tr><td colspan='10' height='20'></td></tr>
</table>

<?php 
echo $form->errorSummary($model);
 
if(isset($error) and $error !== '')
{  
	echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
} 
?>

<?php if ($consulta==0 or $consulta==2){ ?>  
	<div class="form-group">
	<?php if ($consulta==2){ ?>  
		<?= Html::a('Grabar', ['delete', 'accion'=>1,'trib_id' => $model->trib_id, 'perdesde' => $model->perdesde, 'perhasta' => $model->perhasta, 'tipo' => $model->tipo, 'montodesde' => $model->montodesde, 'montohasta' => $model->montohasta], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro que desea eliminar ?',
                'method' => 'post',
            ],
        ]) ?>
     <?php }else { ?>  
		<?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
	<?php }?>  
	
	</div>

    <?php 
		}
    	ActiveForm::end();
    	
    	if ($consulta==1 or $consulta==2) 
    	{
    		echo "<script>";
			echo "DesactivarForm('form-multa');";
			echo "</script>";
    	}   
    	
    	if ($consulta<>1 && !$model->isNewRecord)
		{
			echo '<script>$("#calcmulta-trib_id").prop("disabled", true);</script>';
			echo '<script>$("#calcmulta-aniodesde").prop("disabled", true);</script>';
			echo '<script>$("#calcmulta-cuotadesde").prop("disabled", true);</script>';
			echo '<script>$("#calcmulta-aniohasta").prop("disabled", true);</script>';
			echo '<script>$("#calcmulta-cuotahasta").prop("disabled", true);</script>';
			echo '<script>$("#calcmulta-montodesde").prop("disabled", true);</script>';
			echo '<script>$("#calcmulta-montohasta").prop("disabled", true);</script>';
			echo '<script>$("#calcmulta-tipo").prop("disabled", true);</script>';
		}
    	
    ?>

</div>

<script>
function TextInputQuitaOnBlur(valor)
{
	if (Number(valor) < 1)
	{
		$("#calcmulta-finmes :radio").prop("checked", false);
		$("#calcmulta-finmes :radio").prop("disabled", true);
		$("#calcmulta-diasvenc").prop("disabled", true);
	}else {
			$("#calcmulta-diasvenc").prop("disabled", false);
			$("#calcmulta-finmes :radio").prop("disabled", false);
		}
}

function tcalculoOnChange(fc)
{
	$("#calcmulta-valor").val("");
	$("#calcmulta-alicuota").val("");
	$("#calcmulta-diasvenc").val("");
	
	if (fc==1)
	{
		$("label[for='calcmulta-valor']").html("Valor");	
	}else $("label[for='calcmulta-valor']").html("M�nimo");
	
	if (fc==5)
	{
		$("label[for='calcmulta-alicuota']").html("Alic.Exed.");	
	}else $("label[for='calcmulta-alicuota']").html("Porc.");
	
	if (fc==1 || fc==4 || fc==5)
	{
		$("#calcmulta-valor").prop("disabled", false);	
	}else $("#calcmulta-valor").prop("disabled", true);
	
	if (fc==3 || fc==4 || fc==5)
	{
		$("#calcmulta-alicuota").prop("disabled", false);	
	}else $("#calcmulta-alicuota").prop("disabled", true);
	 
}

function finmesOnChange(valor)
{
	if (valor==1) 
	{
		$("#calcmulta-diasvenc").prop("disabled", false);
	} else $("#calcmulta-diasvenc").prop("disabled", true);
}
</script>
