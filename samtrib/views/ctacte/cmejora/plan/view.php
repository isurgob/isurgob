<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

$this->params['breadcrumbs'][] = ['label' => 'Tributos'];
$this->params['breadcrumbs'][] = ['label' => 'Planes de Mejoras'];

?>
<div class="rodado-view">

    <h1 id='h1titulo'>Administraci&oacute;n de Contribuci&oacute;n por Mejoras</h1>

    <table border='0' width="100%">
    <tr>
    	<td valign="top">
    		<?php 
    			if (!isset($mensaje) || trim($mensaje) == '') $mensaje = trim( Yii::$app->request->get('m_text', '') );
    			else $mensaje = trim($mensaje);

    			Alert::begin([
    				'id' => 'alertaMejImd',
					'options' => [
        			'class' => ( ( intval( Yii::$app->request->get('m', 0) ) !== 10) ? 'alert-success' : 'alert-info'),
        			'style' => ($mensaje != '' ? 'display:block; width:640px;' : 'display:none') 
    				],
				]);

				if ($mensaje != '') echo $mensaje;
				
				Alert::end();
				
				if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#alertaMejImd').alert('close'); }, 5000)</script>";
    			
    			    				    				
    				// muestro formulario de edicion
    				echo $this->render('_form', [
								'model' => $model, 
								'modelTCalculo' => $modelTCalculo, 
								'consulta' => $consulta,
								'dataProviderCuotas' => $dataProviderCuotas
							]) ;	
    		
    		?>
		</td>
		
		<td align='right' valign='top' width="15%">
    		<?php
    		 echo $this->render('menu_derecho', ['model' => $model, 'consulta' => $consulta]) 
    		 ?>
    	</td>
	</tr>
    </table>

</div>