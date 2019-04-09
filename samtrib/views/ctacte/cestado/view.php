<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/*
 * View del módulo "Fiscalización".
 */

$title = 'Cuenta Corriente - Cambiar Estado';

$this->params['breadcrumbs'][] = $title;

?>
<div class="fiscaliza-view">
	<h1><?= Html::encode($title) ?></h1>
	<table width="100%">
		<tr>
		<!-- INICIO Mensaje -->
			<td>
			<?php
				Alert::begin([
		    		'id' => 'AlertaMensaje',
					'options' => [
		        	'class' => 'alert-success',// : 'alert-info',
		        	'style' => $alert !== '' ? 'display:block' : 'display:none' 
		    		],
				]);
		
					echo $alert;
						
				Alert::end();
						
				echo "<script>window.setTimeout(function() { $('#AlertaMensaje').alert('close'); }, 5000)</script>";
				
			?>
			</td>
		<!-- FIN Mensaje -->
		</tr>
		<tr>
			<td valign="top">
				<?php
					echo $this->render('_form',[
	        			'model' => $model,
	        			'consulta'=>$consulta
					]); 
				?>
			</td>
			<td width="270px" valign="top" align="right">
				<?= $this->render('menu_derecho', [
						'model'=>$model,
						'consulta'=>$consulta,
					]); 
				?>
			</td>
		</tr>
	</table>
</div>
