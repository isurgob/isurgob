<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Registro';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($this->title) ?></h1></td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>

    <p>Complete sus datos para acceder al sistema:</p>

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
				<td width='120px' align='left'><b>Municipio:<b></td>
				<td align='left'> 
					<?php
						Pjax::begin(['id' => 'PjaxMuni']);
							$muni = isset($_POST['muni']) ? $_POST['muni'] : 1;
							Yii::$app->session['muni'] = $muni;
							
							echo Html::dropDownList('muni', $muni, $municipios, [
								'class' => 'form-control','id'=>'muni',
								'onchange' => "$.pjax.reload({
													container: '#PjaxMuni', 
													data: {
														muni: $('#muni').val(),
													},
													type: 'POST',
													replace:false,
													push:false
												});"
							]); 
						Pjax::end();
					?>
				</td>
			</tr> 
			<tr>
				<?= $form->field($model, 'nombre', ['template' => $t])->textInput(['maxlength' => 15,'style' => 'width:120px;']) ?>
			</tr>

			<tr>
				<?= $form->field($model, 'clave', ['template' => $t])->passwordInput(['maxlength' => 15,'style' => 'width:120px;']) ?>
			</tr>
	
			<tr>
				<td width='40px'>&nbsp;</td>
				<td colspan="2"><br><br>
				<?= $form->errorSummary($model); ?>

				<div class="form-group">
					<?= Html::submitButton('Ingresar', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
				</div>				
				</td>
			</tr>
	
		</table>		

		<br><br>


		<?php ActiveForm::end(); ?>

	</div>

</div>
