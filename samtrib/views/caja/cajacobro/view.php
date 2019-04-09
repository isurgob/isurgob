<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
use yii\helpers\BaseUrl;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\rete */

$title = 'Caja Cobro';
$this->params['breadcrumbs'][] = $title;

?>
<div class="intima-view">
	<h1><?= Html::encode($title) ?></h1>
	<table width="100%" border="0">
		<tr>
	    	<td valign="top">
				<div id="alert">
		    		<?php
		    			if (isset($alert) == null) $alert = '';
		    			Alert::begin([
		    				'id' => 'AlertaCajaCobro',
							'options' => [
		        			'class' => ($m == 1) ? 'alert-success' : 'alert-info',
		        			'style' => $alert !== '' ? 'display:block' : 'display:none'
		    				],
						]);

						if ($alert !== '') echo $alert;

						Alert::end();

						if ($alert !== '') echo "<script>window.setTimeout(function() { $('#AlertaCajaCobro').alert('close'); }, 5000)</script>";
					?>
				</div>
				<div id="_form">
					<?php
						echo $this->render('_form',[
							'id' 			=> $id,
		        			'model' 		=> $model,
		        			'consulta' 		=> $consulta,
							'mediosPago'	=> $mediosPago,
						]);
					?>
				</div>
			</td>
			<td rowspan="2" width="120px" valign="top" align="right">
				<?= $this->render('menu_derecho', [
						'model'=>$model,
						'id'=>$id,
						'consulta'=>$consulta,
					]);
				?>
			</td>
		</tr>
	</table>
</div>
<?php 
	if (isset(Yii::$app->session['imparqueo_caja'])) { 
		$caja = Yii::$app->session->getFlash('imparqueo_caja');
		$fecha = Yii::$app->session->getFlash('imparqueo_fecha');
?> 
		<script>
			window.open("<?=BaseUrl::toRoute(['imprimirarqueo','caja'=>$caja,'fecha'=>$fecha])?>"); 
		</script>
<?php } ?>