<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;


/* @var $this yii\web\View */

$title = '';

switch ( $op ) 
{
	case 'N': 
	
		$title = 'Nuevo Certificado Libre de Deuda';
		break;
		
	case 'A': 
		$title = ( $accion == 'desc' ? 'Aplicar Descuento' : 'Bloquear' ) . ' Libre de Deuda';
		break;
		
	default: 
		$title = '';
		break;
		
}

if ( $title != '' )
{
	$this->params['breadcrumbs'][] = ['label' => 'Libre Deuda', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $title;
	
} else 
{
	$this->params['breadcrumbs'][] = 'Libre Deuda';
	$title = 'Listado de Libre Deuda';
}

?>
<div class="ctacte-index">
<h1><?= Html::encode( $title ) ?></h1>
<table border='0' whidt='100%'>
    <tr>
    	<td valign='top'>
    		<?php 
    			if ( isset( $mensaje ) == null )
    				 $mensaje = '';
    				 
    			Alert::begin([
    				'id' => 'AlertaLDeuda',
					'options' => [
        			'class' => (isset($_GET['m']) and $_GET['m'] == 1) ? 'alert-success' : 'alert-info',
        			'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
    				],
				]);

					echo $mensaje;
				
				Alert::end();
				
				echo "<script>window.setTimeout(function() { $('#AlertaLDeuda').alert('close'); }, 5000)</script>";
			?>
		</td>
	</tr>
	<tr>
		<td>
			<?php
    			
    			$_GET['m'] = 0;
    			if ($op == 'N'){
    				echo $this->render('_form',['error' => $error ]);
    			}elseif ($op == 'A') {
    				echo $this->render('accion',['accion' => $accion,'error'=>$error]);
    			}else{
    				if (Yii::$app->session['cond'] == '') 
	    				echo $this->render('list_op');
	    			else	
	    				echo $this->render('list_res');
					
				}
    			
    		?>
		</td>
		<td align='right' valign='top'>
    		<?= $this->render('menu_derecho', ['op' => $op]); ?>
    	</td>
	</tr>
</table>

</div>
