<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Intima */

$title = 'Declaraciones Juradas';

if( $model->fiscaliza ){
    $title .= ' (Fiscalización)';
}

?>
<div class="form-cuerpo">

	<div class="pull-left">
		<h1><?= Html::encode($title) ?></h1>
	</div>

	<div class="clearfix"></div>

	<table width="100%">

        <tr>
            <td>
                <div id="ddjj_view_mensaje" class="mensaje alert-success" style="display:none;margin-right: 15px">
                </div>
            </td>
        </tr>

		<tr>
			<td valign="top">
				<?php
						echo $this->render('info',[
							'id' => $id,
		        			'model' => $model,
							'dataProviderRubros' => $dataProviderRubros,
				            'dataProviderLiq' => $dataProviderLiq,
				            //'dataProviderAnt' => $dataProviderAnt,
							'dataProviderRete'=>$dataProviderRete,
		        			'dia' => $dia,
						]);

				?>
			</td>

			<td width="110px" valign="top" align="right" rowspan="2">
				<?= $this->render('menu_derecho', [
						'model'		=> $model,
						'id' 		=> $id,
						'consulta'	=> 1,
					]);
				?>
			</td>
		</tr>

        <tr>
            <td>
                <div id="ddjj_view_errorSummary" class="error-summary" style="display:none;margin-right: 15px">
                </div>
            </td>
        </tr>
	</table>
</div>

<?php if ( $mensaje != '' ){ //Si existen mensajes ?>

<script>

$( document ).ready(function() {
    mostrarMensaje( "<?= $mensaje; ?>", "#ddjj_view_mensaje" );
});

</script>

<?php } ?>

<?php if ( $error != '' ){ //Si existen errores ?>

<script>

$( document ).ready(function() {
    mostrarErrores( ["<?= $error; ?>"], "#ddjj_view_errorSummary" );
});

</script>

<?php } ?>
