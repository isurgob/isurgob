<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Intima */

$title = 'Nueva Declaración Jurada';

$this->params['breadcrumbs'][] = [ 'label' => 'Declaraciones Juradas', 'url' => [ 'index' ]];
$this->params['breadcrumbs'][] = [ 'label' => $title ];

if( $model->fiscaliza ){
    $title .= ' (Fiscalización)';
}

?>

<div class="form-cuerpo">
	<div class="pull-left">
		<h1><?= Html::encode($title) ?></h1>
	</div>

	<div class="clearfix"></div>


	<table width="100%" border="0">
		<tr>
			<td valign="top">
                <?php

            		echo $this->render('_form',[

                        'mensaje'              => $mensaje,
                        'error'                => $error,

                        'obj_id'               => $obj_id,

                        'permiteRetenciones'   => $permiteRetenciones,
                        'tributos'             => $tributos,
                        'permiteModificarObj'  => $permiteModificarObj,
                        'tiposDDJJ'            => $tiposDDJJ,
            			'model'                => $model,
                        'fecha'                => $fecha,
                        'datosCargados'        => $datosCargados,
                        'verificaAdeuda'       => $verificaAdeuda,
                        'verificaExistencia'   => $verificaExistencia,
                        'habilitarPresentar'   => $habilitarPresentar,
                        'config_ddjj'	       => $config_ddjj,
                        'dataProvRubros'       => $dataProvRubros,
                        'dataProviderLiq'      => $dataProviderLiq,
                        // 'dataProviderAnt' => $dataProviderAnt,
                        'dataProviderRete'      => $dataProviderRete,
                        'aplicaBonificacion'   => $aplicaBonificacion,
                        'saldoAFavor'          => $saldoAFavor,
            		]);

            	?>
			</td>

			<td width="110px" valign="top" align="right">
				<?= $this->render('menu_derecho', [
						'model'		=> $model,
						'id' 		=> 0,
						'consulta'	=> 0,
					]);
				?>
			</td>
		</tr>
	</table>
</div>
