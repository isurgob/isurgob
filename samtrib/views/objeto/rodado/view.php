<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\objeto\persona\Persona */


$this->params['breadcrumbs'][] = ['label' => 'Rodado'];
$this->params['breadcrumbs'][] = 'Administración de Rodado';

?>
<div class="rodado-view">

    <h1 id='h1titulo'>Administraci&oacute;n de Rodado</h1>

    <table border='0' width="100%">
    <tr>
    	<td valign="top">
    		<?php 
    			if (!isset($mensaje) || trim($mensaje) == '') $mensaje = trim( Yii::$app->request->get('m_text', '') );
    			else $mensaje = trim($mensaje);

    			Alert::begin([
    				'id' => 'alertaRodado',
					'options' => [
        			'class' => ( ( intval( Yii::$app->request->get('m', 0) ) === 1) ? 'alert-success' : 'alert-info'),
        			'style' => ($mensaje != '' ? 'display:block; width:640px;' : 'display:none') 
    				],
				]);

				if ($mensaje != '') echo $mensaje;
				
				Alert::end();
				
				if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#alertaRodado').alert('close'); }, 5000)</script>";
    			
    			if (!isset($extras['consulta'])) $extras['consulta'] = 1;
    			
    			if 	($extras['consulta'] === 0) echo '<script>$("#h1titulo").html("Nuevo Rodado")</script>';
    			if 	($extras['consulta'] === 3) echo '<script>$("#h1titulo").html("Modificar Rodado")</script>';
    			if 	($extras['consulta'] === 2) echo '<script>$("#h1titulo").html("Eliminar Rodado")</script>';
    			    				    				
    				// muestro formulario de edicion
    				echo $this->render('_form', [
        				'extras' => $extras
    					]) ;	
    		
    		?>
		</td>
		
		<td align='right' valign='top' width="15%">
    		<?php
    		 echo $this->render('menu_derecho',['extras' => $extras]) 
    		 ?>
    	</td>
	</tr>
    </table>

</div>