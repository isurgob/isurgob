<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = 'Contacto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($this->title) ?></h1></td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        Gracias por contactarse. Le responderemos a la brevedad.
    </div>

    <p>
         Note que si se enciende el depurador Yii, usted deber&iacute;a ser capaz
         para ver el mensaje de correo electr&oacute;nico en el panel del depurador..
        <?php if (Yii::$app->mailer->useFileTransport): ?>
        Debido a que la aplicaci&oacute;n se encuentra en el modo de desarrollo, el correo electr&oacute;nico no se env&acute;a, pero guarda como
         un archivo bajo <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
        Por favor, configurar el <code> useFileTransport </code> del <code> mail </code>
         componente de aplicaci&oacute;n que es falso para permitir el env&iacute;o de correo electr&oacute;nico.
        <?php endif; ?>
    </p>

    <?php else: ?>

    <p>
        Si tiene consultas sobre el uso del sistemas u otras sugerencias, por favor, llene el siguiente formulario para contactarse. Gracias.
    </p>

	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<?php $form = ActiveForm::begin([
						'id' => 'contact-form',
						'fieldConfig' => [
							'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
							'labelOptions' => ['class' => 'col-lg-2 control-label'],
							] 
					]);
			$param = Yii::$app->params;	
		?>

		<table border='0'>
			<tr>
				<?= $form->field($model, 'name', ['template' => $param['T_TAB_COL4']])->textInput(['maxlength' => 50,'style' => 'width:260px;']); ?>
			</tr>
			<tr>
				<?= $form->field($model, 'email', ['template' => $param['T_TAB_COL4']])->textInput(['maxlength' => 100,'style' => 'width:380px;']); ?>
			</tr>
			<tr>
				<?= $form->field($model, 'subject', ['template' => $param['T_TAB_COL4']])->textInput(['maxlength' => 50,'style' => 'width:260px;']); ?>
			</tr>
			<tr>
				<?= $form->field($model, 'body', ['template' => $param['T_TAB_COL4']])->textArea(['rows' => 8, 'cols' => 70]); ?>
			</tr>
			<tr>
                <?= $form->field($model, 'verifyCode', ['template' => "<td><b>Verificaci&oacute;n</b></td><td align='left' colspan='4'>{input}</td>"])->widget(Captcha::className()) ?>
			</tr>
		</table>		

		<br><br>

		<?= $form->errorSummary($model); ?>

		<div class="form-group">
			<?= Html::submitButton('Enviar', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>
	

    <?php endif; ?>
</div>
