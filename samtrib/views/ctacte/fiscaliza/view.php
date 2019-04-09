<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/*
 * View del módulo "Fiscalización".
 */

switch ($consulta)
{
	case 0:
	
		$title = 'Nueva Fiscalización';
		break;
		
	case 1:
		
		$title = 'Fiscalización';
		break;
		
	case 2:
		
		$title = 'Eliminar Fiscalización';
		break;
		
	case 3:
		
		$title = 'Modificar Fiscalización';
		break;
}

$this->params['breadcrumbs'][] = $title;

?>
<div class="fiscaliza-view">
	<h1><?= Html::encode($title) ?></h1>
	<table width="100%">
		<tr>
			<td valign="top">
				<?php
					echo $this->render('_form',[
	        			'model' => $model,
	        			'consulta'=>$consulta,
	        			'alert' => $alert,
	        			'm' => $m,
					]); 
				?>
			</td>
			<td width="110px" valign="top" align="right">
				<?= $this->render('menu_derecho', [
						'model'=>$model,
						'consulta'=>$consulta,
					]); 
				?>
			</td>
		</tr>
	</table>
</div>
