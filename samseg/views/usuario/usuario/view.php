<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\rete */

$title = 'Seguridad';
$this->params['breadcrumbs'][] = $title;

?>
<div class="intima-view">
	<h1><?= Html::encode($title) ?></h1>
	<table width="100%">
		<tr>
			<td valign="top">
				<div style="margin-right: 8px">
					<?php
		    			if (isset($alert) == null) $alert = '';
		    			Alert::begin([
		    				'id' => 'AlertaSeguridad',
							'options' => [
		        			'class' => ($m == 1) ? 'alert-success' : 'alert-info',
		        			'style' => $alert !== '' ? 'display:block' : 'display:none'
		    				],
						]);

						if ($alert !== '') echo $alert;

						Alert::end();

						if ($alert !== '') echo "<script>window.setTimeout(function() { $('#AlertaSeguridad').alert('close'); }, 5000)</script>";
					?>
				</div>
				<div>
				<?php
					echo $this->render('_form',[
	        			'model' => $model,
	        			'grupos' => $grupos,
						'oficinas' => $oficinas,
						'dataProviderUsuario' => $dataProviderUsuario
					]);
				?>
				</div>
			</td>
			<td width="100px" valign="top" align="right">
				<?= $this->render('menu_derecho', [
						'model'=>$model,
					]);
				?>
			</td>
		</tr>
	</table>
</div>
