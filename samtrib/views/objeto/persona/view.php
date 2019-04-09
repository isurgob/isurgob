<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\objeto\persona\Persona */

$this->title = 'Administración de Personas ';
$this->params['breadcrumbs'][] = ['label' => 'Personas'];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="persona-view">

    <h1 id='h1titulo'><?= $this->title ?></h1>

    <table border='0' whidt='100%'>
    <tr>
    	<td>
    		<?php
    			if (isset($mensaje) == null) $mensaje = '';
    			Alert::begin([
    				'id' => 'AlertaPersona',
					'options' => [
        			'class' => (isset($_GET['m']) and $_GET['m'] == 1) ? 'alert-success' : 'alert-info',
        			'style' => $mensaje !== '' ? 'display:block' : 'display:none'
    				],
				]);

				if ($mensaje !== '') echo $mensaje;

				Alert::end();

				if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaPersona').alert('close'); }, 5000)</script>";

    			if (isset($consulta)==null) $consulta=1;

    			if 	($consulta==0) echo '<script>$("#h1titulo").html("Nueva Persona")</script>';
    			if 	($consulta==3) echo '<script>$("#h1titulo").html("Modificar Persona")</script>';
    			if 	($consulta==2) echo '<script>$("#h1titulo").html("Eliminar Persona")</script>';

    				// muestro formulario de edicion
    				echo $this->render('_form', [
        				'model' => $model,
                        'modelobjeto'       => $modelobjeto,
                        'modelodomipost'    => $modelodomipost,
                        'modelodomileg'     => $modelodomileg,
                        'modelodomires'     => $modelodomires,
						'consulta' => $consulta,
						'error' => isset($error) ? $error : '',
						'dadosDeBaja'               => $dadosDeBaja,
    					]) ;

    		?>
		</td>
		<td align='right' valign='top'>
    		<?= $this->render('menu_derecho',[
                'model'     => $model,
                'estado'    => $modelobjeto->est,
                'consulta'  => $consulta]) ?>
    	</td>
	</tr>
    </table>

</div>
