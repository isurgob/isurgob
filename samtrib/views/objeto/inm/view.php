<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use app\models\objeto\Inm;
use yii\bootstrap\Alert;
use yii\web\Session;

$title = 'Administración de Inmuebles ';
$this->params['breadcrumbs'][] = ['label' => 'Inmuebles'];
$this->params['breadcrumbs'][] = $title;

	// $arregloLabels contiene un arreglo con los valores para mostrar cuando se habilita NC
    // $codigoLocalidad contiene el código de la localidad donde se usa la aplicación
    

?>
	
    <h1 id='h1titulo'><?= $title ?></h1>
	<table>
		<tr>
			<td>
    		<?php 
    		
    			$arregloComputados = utb::getComputados();
				
				//INICIO Mensaje de advertencia
				$session = new Session;
				$session->open();
				
				if(isset($session['advertenciaRegimen']) and $session['advertenciaRegimen'] != '' and count($model->errors)==0)
				{
					Alert::begin([
    				'id' => 'AlertaInmueblesAdvertencia',
					'options' => [
        			'class' => 'alert-warning',
        			'style' => $session['advertenciaRegimen'] !== '' ? 'display:block' : 'display:none' 
    				],
				]);
				

				if ($session['advertenciaRegimen'] !== '') echo $session['advertenciaRegimen'];
				
				Alert::end();
				
				if ($session['advertenciaRegimen'] !== '') echo "<script>window.setTimeout(function() { $('#AlertaInmueblesAdvertencia').alert('close'); }, 5000)</script>";
				
				
				$session['advertenciaRegimen'] = '';
				$session->close();
				}
				
				//FIN Mensaje de advertencia
  		
    			if (isset($mensaje) == null) $mensaje = '';
    			Alert::begin([
    				'id' => 'AlertaInmuebles',
					'options' => [
        			'class' => (isset($_GET['m']) and $_GET['m'] == 1) ? 'alert-success' : 'alert-info',
        			'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
    				],
				]);	

				if ($mensaje !== '') echo $mensaje;
				
				Alert::end();
				
				if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaInmuebles').alert('close'); }, 5000)</script>";
    			
    			if (isset($consulta)==null) $consulta=1;
    			
    			if 	($consulta==0) echo '<script>$("#h1titulo").html("Nuevo Inmueble")</script>';
    			if 	($consulta==3) echo '<script>$("#h1titulo").html("Modificar Inmueble")</script>';
    			if 	($consulta==2) echo '<script>$("#h1titulo").html("Eliminar Inmueble")</script>';
    			
    			//Se dibjaen caso de haber dado de a
    			if (isset($id))
    			{
    				// muestro formulario de edicion
					echo $this->render('_form',
									[	'arregloLabels' => $arregloLabels,
										'arregloMejoras' => $arregloMejoras,
										'arregloComputados' => $arregloComputados,
										'codigoLocalidad' => $codigoLocalidad,	
										'consulta' => $consulta,
										'model' => $model,
										'modelObjeto' => $modelObjeto,
										'modelodomipar' => $modelodomipar,
										'modelodomipost' => $modelodomipost,
										'id' => $id,
									]);
    				
    			} else {
    			    				    				
	    			// muestro formulario de edicion
					echo $this->render('_form',
									[	'arregloLabels' => $arregloLabels,
										'arregloMejoras' => $arregloMejoras,
										'arregloComputados' => $arregloComputados,
										'codigoLocalidad' => $codigoLocalidad,	
										'consulta' => $consulta,
										'model' => $model,
										'modelObjeto' => $modelObjeto,
										'modelodomipar' => $modelodomipar,
										'modelodomipost' => $modelodomipost,
										
									]);
    			}
			
			?>
			</td>
			<td width="15%" valign="top">
	    		<?= $this->render('menu_derecho',['modelObjeto' => $modelObjeto,'model' => $model,'consulta' => $consulta]) ?>
	    	</td>
		</tr>
	</table>