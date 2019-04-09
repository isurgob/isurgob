<?php

use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'Actividad Económica'];
$this->params['breadcrumbs'][] = 'Administración de Actividad económica';

/**
 * Forma que se dibuja para mostrar los datos de comercio.
 * Recibe:
 *      + $model    => Modelo de comercio ( comer ).
 *      + $action   => Tipo de acción que se ejecuta.
 */

?>
<div class="cem-view">

    <h1 id='h1titulo'>Administraci&oacute;n de Actividad económica</h1>

    <table border='0' width="100%">
    <tr>
    	<td valign="top">
    		<?php

    			if ( !isset( $consulta ) ){
                    $consulta = 1;
                }

    			// if 	($extras['consulta'] === 0) echo '<script>$("#h1titulo").html("Nueva Actividad Económica")</script>';
    			// if 	($extras['consulta'] === 3) echo '<script>$("#h1titulo").html("Modificar Actividad Económica")</script>';
    			// if 	($extras['consulta'] === 2) echo '<script>$("#h1titulo").html("Eliminar Actividad Económica")</script>';

    				// muestro formulario de edicion
    				echo $this->render('_form', [

                        'mensaje'       => $mensaje,
                        'error'         => $error,
        				'model'         => $model,
                        'modelObjeto'   => $modelObjeto,
                        'responsablePrincipal'      => $responsablePrincipal,
                        'modelDomicilioPostal'      => $modelDomicilioPostal,
                        'modelDomicilioParcelario'  => $modelDomicilioParcelario,

                        //Especiales
                        'modelRubroTemporal'        => $modelRubroTemporal,
                        'mostrarModalRubros'        => $mostrarModalRubros,

                        'dadosDeBaja'               => $dadosDeBaja,
                        'soloVigentes'              => $soloVigentes,

                        'action'        => $action,

                        'dataProviders'     => $dataProviders,
                        'arrayRubros'       => $arrayRubros,

                        'tab'               => $tab,

                        'tipoHabilitacion'  => $tipoHabilitacion,
                        'tipoComercio'      => $tipoComercio,
                        'arrayZonas'        => $arrayZonas,
                        'arrayTipoVinculo'  => $arrayTipoVinculo,
                        'arrayIB'           => $arrayIB,
					]);

    		?>
		</td>

		<td align='right' valign='top' width="15%">
            <div align="left">
        		<?=
                    $this->render('menu_derecho',[
                        'model'     => $model,
                        'estado'    => $modelObjeto->est,
                        'action'    => $action,
                        'realizaDDJJ'   => $realizaDDJJ,
                    ]);
        		 ?>
             </div>
    	</td>
	</tr>
    </table>

</div>
