<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use app\utils\db\utb;


$selectorModal= isset($selectorModal) ? $selectorModal : Yii::$app->request->post('selectorModal', '#modalDatos');

$model->tabla_id= $modelTabla->tabla_id;
?>


<?php
Pjax::begin(['id' => 'pjaxFormDato', 'enableReplaceState' => false, 'enablePushState' => false]);
$form = ActiveForm::begin(['id'=>'formResolDato', 'fieldConfig' => ['template' => '{input}'], 'validateOnSubmit' => false]);
?>			
			
	<?= $form->field($model, 'tabla_id')->hiddenInput(); ?>
	<table border="0">
		<tr>
			<td width='55px'><label>Desde:</label></td>
			<td><?= $form->field($model, 'adesde')->textInput(['maxlength' => 4, 'style' => 'width:50px;', 'id' => 'formDatoAdesde']) ?></td>				  				
			<td><?= $form->field($model, 'cdesde')->textInput(['maxlength' => 3, 'style' => 'width:40px;', 'id' => 'formDatoCdesde']); ?></td>
			<td  width='50px' align='right'><label>Hasta:</label></td>
			<td><?= $form->field($model, 'ahasta')->textInput(['maxlength' => 4, 'style' => 'width:50px;', 'id' => 'formDatoAhasta']) ?></td>				  				
			<td><?= $form->field($model, 'chasta')->textInput(['maxlength' => 3, 'style' => 'width:40px;', 'id' => 'formDatoChasta']); ?></td>
		</tr>
	</table>
	
	<table width='100%' border='0'>	
		<tr><td height='10px'></td></tr>
		
		<?php
		$mostrados= 0;
		$maximoMostrar= 3;
		
		foreach($modelTabla->columnas as $columna){
			
			if(!$columna->aplicable) continue;
			
			if($mostrados == 0) echo '<tr>';
			?>
			
			<td><label><?= $columna->nombre ?>:</label></td>
			<td><?= $form->field($model, $columna->param)->textInput(['style' => 'width:130px;', 'id' => $columna->param])->label(false); ?></td>
			
			<?php
			
			$mostrados++;
			
			if($mostrados == $maximoMostrar){
				
				echo '</tr>';
				$mostrados= 0;
			}
		}
		?>
	</table>

	<?php
	if($consulta === 2)
		echo Html::button('Eliminar', ['class' => 'btn btn-danger', 'onclick' => 'eliminarDato();']);
	else if($consulta !== 1) echo Html::button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'grabarDato();']);
	
	echo '&nbsp;&nbsp;';
	echo Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("' . $selectorModal . '").modal("hide");']);
	?>
<?php
ActiveForm::end();

echo $form->errorSummary($model);

Pjax::end();
?>

<script type="text/javascript">
function grabarDato(){
	
	<?php
	$action= BaseUrl::toRoute(['//ctacte/resoltabladato/create', 'resol_id' => $modelTabla->resol_id, 'tabla_id' => $modelTabla->tabla_id]);
	
	if($consulta === 3) $acion= BaseUrl::toRoute(['//ctacte/resoltabladato/update', 'resol_id' => $modelTabla->resol_id, 'tabla_id' => $modelTabla->tabla_id, 'dato_id' => $model->dato_id]);
	?>
	
	var datos= {"ResolTablaDato": {}, "selectorModal": "<?= $selectorModal; ?>"};
	var aux= {};
	
	aux.adesde= $("#formDatoAdesde").val();
	aux.cdesde= $("#formDatoCdesde").val();
	aux.ahasta= $("#formDatoAhasta").val();
	aux.chasta= $("#formDatoChasta").val();
	
	aux.paramstr= $("#paramstr").val();
	aux.param1= $("#param1").val();
	aux.param2= $("#param2").val();
	aux.param3= $("#param3").val();
	aux.param4= $("#param4").val();
	aux.param5= $("#param5").val();
	
	datos.ResolTablaDato= aux;
	
	$.pjax.reload({
		container: "#pjaxFormDato",
		url: "<?= $action; ?>",
		type: "POST",
		replace: false,
		push: false,
		data: datos
	});
}

function eliminarDato(){

	$.pjax.reload({
		container: "#pjaxFormDato",
		url: "<?= BaseUrl::toRoute(['//ctacte/resoltabladato/delete', 'resol_id' => $modelTabla->resol_id, 'tabla_id' => $modelTabla->tabla_id, 'dato_id' => $model->dato_id]); ?>",
		type: "POST",
		replace: false,
		push: false,
	});
}

<?php
if(!in_array($consulta, [0, 3])){
	?>
	DesactivarFormPost("formResolDato");
	<?php
}
?>
</script>