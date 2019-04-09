<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use yii\widgets\MaskedInput;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

use app\models\objeto\Domi;
use app\utils\db\Fecha;
use app\utils\db\utb;


$this->params['breadcrumbs'][] = ['label' => 'Rodado ' . $extras['modelObjeto']->obj_id, 'url' => ['view', 'id' => $extras['modelObjeto']->obj_id]];
$this->params['breadcrumbs'][] = 'Cambio';

$strNro = 'Nº Chasis: ';
$titulo = 'Cambio de Chasis';
$campoNro = 'nrochasis';
$campoMarca = 'marcachasis';

switch($extras['model']->taccion){
	
	case 10 : 
		$strNro = 'Nº Chasis: '; 
		$titulo = 'Cambio de Chasis';
		$campoNro = 'nrochasis'; 
		$campoMarca = 'marcachasis';
		break;
		
	case 11 : 
		$strNro = 'Nº Motor: ';
		$titulo = 'Cambio de Motor'; 
		$campoNro = 'nromotor';
		$campoMarca = 'marcamotor'; 
		break;	
}
?>

<div class="form-cambio">
	<h1><label><?= $titulo ?></label></h1>
	<?php
	$form = ActiveForm::begin(['fieldConfig' => ['template' => '{label}{input}'], 'validateOnSubmit' => false]);
	
	echo $form->field($extras['model'], 'taccion', ['options' => ['class' => 'hidden']])->input('hidden')->label(false);
	
	?>
	<div class="form" style="padding:10px;">
		<table border="0" width="100%">
			<tr>
				<td align="left">
					<?= $form->field($extras['model'], 'obj_id', ['options' => ['class' => 'hidden']])->input('hidden')->label(false) ?>
					<?= $form->field($extras['modelObjeto'], 'obj_id', ['template' => '{label}<br>{input}'])->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:80px;'])->label('Objeto'); ?>
				</td>
				
				<td align="left" width="250px">
					<?= $form->field($extras['modelObjeto'], 'nombre', ['template' => '{label}<br>{input}'])
						->textInput(['maxlength' => 50, 'style' => 'width:600px', 'class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'rodadoFormNombre'])
						->label('Nombre') ?>
				</td>
				<td width="5px"></td>
				<td align="left" width="90px">
					<?= $form->field($extras['modelObjeto'], 'est_nom', ['template' => '{label}<br>{input}'])->textInput(['maxlength' => 20, 'class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:75px'])->label('Estado') ?>
				</td>
				<td width="10px"></td>
			</tr>
		</table>
	</div>
	
	<style rel="stylesheet">
	/*#asd td{border:1px solid;}*/
	</style>
	
	<div class="form" style="padding:10px; margin-top:5px;">
		<h3><label>Datos Actuales</label></h3>
		
		<table border="0" width="100%" id="asd">
			<tr>
				<td><label>Marca: </label></td>
				<td width="190px"><?= Html::textInput(null, utb::getCampo('rodado_marca', 'cod = ' . $extras['model']->getOldAttribute($campoMarca)), ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:100%;']); ?></td>
				<td width="10px"></td>
				<td><label><?= $strNro ?></label><?= Html::textInput(null, $extras['model']->getOldAttribute($campoNro), ['class' => 'form-control solo-lectura', 'tabindex' => -1]); ?></td>
				<td width="100px"></td>
			</tr>
		</table>
	</div>
	
	<div class="form" style="padding:10px; margin-top:5px;">
		<h3><label>Datos Nuevos</label></h3>
		
		<table border="0" width="100%" id="asd">
			<tr>
				<td><label>Marca: </label></td>
				<td width="190px"><?= $form->field($extras['model'], $campoMarca, ['options' => ['style' => 'display:inline-block;']])->dropDownList(utb::getAux('rodado_marca'))->label(false);?></td>
				<td width="10px"></td>
				<td><label><?= $strNro ?></label><?= $form->field($extras['model'], $campoNro, ['options' => ['style' => 'display:inline-block;']])->textInput()->label(false);?></td>
				<td width="100px"></td>
			</tr>
		</table>
	</div>
	
	<div class="form" style="padding:10px; margin-top:5px;">
		<table border="0" width="100%" id="asd">
			<tr>
				<td><label>Expediente: </label></td>
				<td><?= $form->field($extras['model'], 'expe')->textInput(['style' => 'width:290px;'])->label(false) ?></td>
				
			</tr>
			
			<tr>
				<td valign="top"><label>Observaciones: </label></td>
				<td colspan="2"><?= $form->field($extras['model'], 'obs')->textarea(['rows' => 5, 'style' => 'width:350px; max-width:350px; max-height:100px;'])->label(false) ?></td>
				<td width="300px"></td>
			</tr>
		</table>
	</div>
	
	<div style="margin-top:5px;">
		<button type="submit" class="btn btn-success">Grabar</button>
		<?= Html::a('Cancelar', ['view', 'id' => $extras['modelObjeto']->obj_id], ['class' => 'btn btn-primary']); ?>
	</div>
	<?php
	ActiveForm::end();
	
	echo $form->errorSummary([$extras['model'], $extras['modelObjeto']], ['style' => 'margin-top:5px;']);
	?>
</div>