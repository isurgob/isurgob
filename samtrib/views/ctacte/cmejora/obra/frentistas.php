<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->params['breadcrumbs'][] = ['label' => 'Definición de obras', 'url' => ['index', 'id' => $model->obra_id]];
$this->params['breadcrumbs'][] = 'Administración de frentistas';

$selectorObra = '#filtroObra';
$selectorCuadra = '#filtroCuadra';

$funcionHabilitarGenerar = 'habilitarGenerar';
?>
<div class="obra-frentistas">

    <h1 id='h1titulo'>Administraci&oacute;n de Frentistas</h1>

    <table border='0' width="100%">
    <tr>
    	<td valign="top">
    		<?php 
    			if (!isset($mensaje) || trim($mensaje) == '') $mensaje = trim( Yii::$app->request->get('m_text', '') );
    			else $mensaje = trim($mensaje);

    			Alert::begin([
    				'id' => 'alertaRodado',
					'options' => [
        			'class' => ( ( intval( Yii::$app->request->get('m', 0) ) !== 10) ? 'alert-success' : 'alert-info'),
        			'style' => ($mensaje != '' ? 'display:block; width:100%;' : 'display:none') 
    				],
				]);

				if ($mensaje != '') echo $mensaje;
				
				Alert::end();
				
				if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#alertaRodado').fadeOut(); }, 5000)</script>";
    			
    			if (!isset($consulta)) $consulta = 1;
    			    			    				    				
    			// muestro formulario de edicion
    			echo $this->render('_form_frentistas',  [
						'model' => $model, 
						'dpFrentistas' => $dpFrentistas,
						'filtroObra' => $filtroObra, 
						'filtroCuadra' => $filtroCuadra,
						'selectorObra' => $selectorObra, 
						'selectorCuadra' => $selectorCuadra, 
						'funcionHabilitarGenerar' => $funcionHabilitarGenerar
					]) ;	
    		
    		?>
		</td>
		
		<td align='right' valign='top' width="15%">
    		<?php
    		 echo $this->render('menu_derecho_frentistas', ['modelCuadra' => $modelCuadra, 'selectorObra' => $selectorObra, 'selectorCuadra' => $selectorCuadra, 'funcionHabilitarGenerar' => $funcionHabilitarGenerar]) 
    		 ?>
    	</td>
	</tr>
    </table>

</div>