<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\config\Rubro;
use app\models\config\RubroVigencia;
use app\utils\db\utb;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\config\Rubro */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form" style='width:310px; padding:10px;'>

	<?php $form = ActiveForm::begin(); ?>
	
		<table width='100%' border='0'>
			<tr>
				<td><label>Código</label></td>
				<td>
					<?= Html::input('text', 'rubro_id',$model->rubro_id, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'rubro_id','style'=>'width:100%']); ?>
				</td>
			</tr>
			<tr>
				<td><label>Nombre</label></td>
				<td>
					<?= Html::input('text', 'nombre',$model->nombre, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'nombre','style'=>'width:100%']); ?>
				</td>
			</tr>
			</table>
			<table width='100%' border='0'>
			<tr>
				<td><br></td>
			</tr>
			<tr>
				<td colspan='2'><label><u>Vigencia Actual</u></label></td>
			</tr>
			<tr>
				<td><label>Desde:</label>
					<?= Html::input('text', 'perdesde', $modelVigencia->adesde,['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'aniodesde','maxlength'=> '4','style'=>'width:40px; display:inline-block;']); ?>				  				
					<?= Html::input('text', 'cuotadesde', $modelVigencia->cdesde, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'cuotadesde','maxlength'=> '10','style'=>'width:30px; display:inline-block;']); ?>
				</td>
				<td width='56%'>
				<label>Hasta:</label>
				    <?= Html::input('text', 'perhasta', $modelVigencia->ahasta,['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'aniohasta','maxlength'=> '4','style'=>'width:40px; display:inline-block;']); ?>
					<?= Html::input('text', 'cuotahasta', $modelVigencia->chasta, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'cuotahasta','maxlength'=> '10','style'=>'width:30px; display:inline-block;']); ?>
				</td>
			</tr>
			</table>
			<table width='100%' border='0'>
			<tr>
			<td><label>Tipo de Cálculo</label></td>
				<td>
					<?= Html::input('text', 'tcalculo',$modelVigencia->tcalculo_nom, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'tcalculo','style'=>'width:125px']); ?>
				</td>
			<tr>
			</tr>
				<td><label>Tipo de Mínimo</label></td>	
				<td>
					<?= Html::input('text', 'tminimo', $modelVigencia->tminimo_nom, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'tminimo','style'=>'width:125px']); ?>
				</td>
			</tr>
			<tr>
			<td>
				<label>Alícuota</label>
			</td>
				<td>
					<?= Html::input('text', 'alicuota',$modelVigencia->alicuota, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'alicuota','style'=>'width:80px']); ?>
				</td>
			</tr>
			<tr>
			<td>
				<label>Alícuota atrasada</label>
			</td>
				<td>
					<?= Html::input('text', 'alicuota_atras',$modelVigencia->alicuota_atras, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'alicuota_atras','style'=>'width:80px']); ?>
				</td>
			</tr>
			<tr>
			<td>
				<label>Mínimo</label>
			</td>
				<td>
					<?= Html::input('text', 'minimo',$modelVigencia->minimo, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'minimo','style'=>'width:80px']); ?>
				</td>
			</tr>
			<tr>
			<td>
				<label>Mínimo Temp. Alta</label>
			</td>
				<td>
					<?= Html::input('text', 'minalta',$modelVigencia->minalta, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'minalta','style'=>'width:80px']); ?>
				</td>
			</tr>
			<tr>
			<td>
				<label>Fijo</label>
			</td>
				<td>
					<?= Html::input('text', 'fijo',$modelVigencia->fijo, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'fijo','style'=>'width:80px']); ?>
				</td>
			</tr>
			<tr>
			<td>
				<label>Porcentaje</label>
			</td>
				<td>
				<?= Html::input('text', 'porc',$modelVigencia->porc, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'porc','style'=>'width:80px']); ?>
				</td>
			</tr>
			<tr>
				<td><label>Cantidad Hasta</label></td>
				<td>
					<?= Html::input('text', 'canthasta',$modelVigencia->canthasta, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'canthasta','style'=>'width:80px']); ?>
				</td>
			</tr>
		</table>
		
		<?php
		ActiveForm::end(); 
		?>

</div>
