<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Intima */

$title = 'Gestión de Incumplimiento';
$this->params['breadcrumbs'][] = $title;

?>
<div class="intima-view">
	<h1><?= Html::encode($title) ?></h1>
	<table width="100%">
		<tr>
	    	<td width='690'>
	    		<?php
	    			if (isset($alert) == null) $alert = '';
	    			Alert::begin([
	    				'id' => 'AlertaIntima',
						'options' => [
	        			'class' => ($m == 1) ? 'alert-success' : 'alert-info',
	        			'style' => $alert !== '' ? 'display:block' : 'display:none' 
	    				],
					]);
	
					if ($alert !== '') echo $alert;
					
					Alert::end();
					
					if ($alert !== '') echo "<script>window.setTimeout(function() { $('#AlertaIntima').alert('close'); }, 5000)</script>";
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?= $this->render('info',[
						'id'=>$id,
	        			'model'=>$model,
	        			'dataProvider'=>$dataProvider,
	        			'consulta'=>$consulta,
						
						]); ?>
			</td>
			<td width="110px" valign="top" align="right">
				<?= $this->render('menu_derecho', [
						'model'=>$model,
						'id'=>$id,
						'consulta'=>$consulta
						]); ?>
			</td>
		</tr>
	</table>
</div>
