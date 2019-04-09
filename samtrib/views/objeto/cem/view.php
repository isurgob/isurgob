<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\objeto\persona\Persona */


$this->params['breadcrumbs'][] = ['label' => 'Cementerio'];
$this->params['breadcrumbs'][] = 'Administración de Cementerio';

?>
<div class="cem-view">

    <h1 id='h1titulo'>Administraci&oacute;n de Cementerio</h1>

    <table border='0' width="100%">
    <tr>
    	<td valign="top">
    		<?php 
    			if (isset($mensaje) == null) $mensaje = '';
    			Alert::begin([
    				'id' => 'alertaCem',
					'options' => [
        			'class' => ((isset($_GET['m']) && $_GET['m'] == 1) || isset($_GET['m_text'])) ? 'alert-success' : 'alert-info',
        			'style' => $mensaje !== '' ? 'display:block; width:640px;' : 'display:none' 
    				],
				]);

				if ($mensaje != '') echo $mensaje;
				else if(isset($_GET['m_text']) && $_GET['m_text'] != '') echo $_GET['m_text'];
				
				Alert::end();
				
				if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#alertaCem').alert('close'); }, 5000)</script>";
    			
    			if (isset($consulta)==null) $consulta=1;
    			
    			if 	($consulta==0) echo '<script>$("#h1titulo").html("Nuevo Cementerio")</script>';
    			if 	($consulta==3) echo '<script>$("#h1titulo").html("Modificar Cementerio")</script>';
    			if 	($consulta==2) echo '<script>$("#h1titulo").html("Eliminar Cementerio")</script>';
    			    				    				
    				// muestro formulario de edicion
    				echo $this->render('_form', [
        				'model' => $model,
        				'modelObjeto' => $modelObjeto,
        				'modelDomicilio' => $modelDomicilio,
						'consulta' => $consulta,
						'error' => isset($error) ? $error : '',
						'extras' => $extras
    					]) ;	
    		
    		?>
		</td>
		
		<td align='right' valign='top' width="15%">
    		<?= $this->render('menu_derecho',['model' => $model, 'modelObjeto' => $modelObjeto, 'consulta' => $consulta, 'extras' => $extras]) ?>
    	</td>
	</tr>
    </table>

</div>