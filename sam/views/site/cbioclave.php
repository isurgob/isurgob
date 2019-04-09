<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\CbioclaveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\CbioclaveForm */

$this->title = 'Cambiar Clave';
$this->params['breadcrumbs'][] = $this->title;
if (!isset($model)) $model = new CbioclaveForm();
?>
<div class="site-cbioclave">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($this->title) ?></h1></td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>

	<?php if (Yii::$app->session['user_sinclave'] == 1) {
		echo "<p>Clave Vac&iacute;a.</p>";
	} ?>
    <p>Complete los datos para modificar la clave:</p>

	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<?php $form = ActiveForm::begin([
						'id' => 'cbioclave-form',
						'fieldConfig' => [
							'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
							'labelOptions' => ['class' => 'col-lg-2 control-label'],
							] 
					]);
			$param = Yii::$app->params;	
			$t = "<td width='40px'>&nbsp;</td><td align='left' width='120px'>{label}</td><td align='left'>{input}\n{hint}</td>";
		?>
	   
		<table border='0'>
			<tr>
				<td width='40px'>&nbsp;</td>
				<td><label>Usuario</label><br><br></td>
				<td><?= Html::input('text','nombre',Yii::$app->user->identity->nombre,['class'=>'form-control','style'=>'width:120px;background-color:#E6E6FA','readonly'=>true]) ?><br><br></td>
			</tr>

			<tr>
				<td width='40px'>&nbsp;</td>
				<td><label><i>Claves:</i></label></td>
			</tr>
			
			<tr>
				<?= $form->field($model, 'clave_old', ['template' => $t])->passwordInput(['maxlength' => 15,'style' => 'width:120px;']) ?>
			</tr>
	
			<tr>
				<?= $form->field($model, 'clave_new', ['template' => $t])->passwordInput(['maxlength' => 15,'style' => 'width:120px;']) ?>
			</tr>
	
			<tr>
				<?= $form->field($model, 'clave_newr', ['template' => $t])->passwordInput(['maxlength' => 15,'style' => 'width:120px;']) ?>
			</tr>
			
			<tr>
				<td width='40px'>&nbsp;</td>
				<td colspan="2"><br><br>
				<?= $form->errorSummary($model); ?>
				
				<div class="form-group">
					<?= Html::submitButton('Aceptar', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
				</div>				
				</td>
			</tr>
			
		</table>		

		<?php ActiveForm::end(); ?>

	</div>

</div>
