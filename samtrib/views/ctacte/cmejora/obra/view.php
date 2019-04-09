<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

$titulo = "Administraci&oacute;n de Obras de Mejoras";

if ( $consulta == 0 )
	$titulo = "Nueva Obra de Mejora";
	
if ( $consulta == 3 )
	$titulo = "Modificar Obra de Mejora";	
	
if ( $consulta == 2 )
	$titulo = "Eliminar Obra de Mejora";	
	
$this->params['breadcrumbs'][] = ['label' => 'Tributos'];
$this->params['breadcrumbs'][] = ['label' => 'Obras de Mejoras'];	

?>
<div class="rodado-view">

    <table border='0' width="100%">
	<tr> <td> <br> </td> </tr>
	<tr>
		<td valign='middle'>
			<h1 id='h1titulo' class="pull-left" > <?= $titulo ?> </h1>
	
			<div class="pull-right">
			
				<?php 
					echo Html::a( 'Volver', ['//ctacte/mejoraplan/index'], [
						'id' => 'btVolver',
						'class' => 'btn btn-primary'
					]);
				?>
				
			</div>	
		</td>
	</tr>
	<tr>
    	<td valign="top">
    		<?php 
    			if (!isset($mensaje) || trim($mensaje) == '') $mensaje = trim( Yii::$app->request->get('m_text', '') );
    			else $mensaje = trim($mensaje);

    			Alert::begin([
    				'id' => 'alertaMejObra',
					'options' => [
        			'class' => ( ( intval( Yii::$app->request->get('m', 0) ) !== 10) ? 'alert-success' : 'alert-info'),
        			'style' => ($mensaje != '' ? 'display:block; width:640px;' : 'display:none') 
    				],
				]);

				if ($mensaje != '') echo $mensaje;
				
				Alert::end();
				
				if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#alertaMejObra').alert('close'); }, 5000)</script>";
    			
    			    				    				
    				// muestro formulario de edicion
    				echo $this->render('_form', [
								'model' => $model, 
								'consulta' => $consulta,
								'dataProviderCuadras' => $dataProviderCuadras,
								'cuadra' => $cuadra,
								'modelCuadra' => $modelCuadra
							]) ;
    		
    		?>
		</td>
		
		<td align='right' valign='top' width="15%">
    		<?php
    		 echo $this->render('menu_derecho', ['model' => $model, 'consulta' => $consulta, 'dpObras' => $dpObras]) 
    		?>
    	</td>
	</tr>
    </table>

</div>