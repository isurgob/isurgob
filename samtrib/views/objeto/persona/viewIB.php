<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\objeto\persona\Persona */

$this->title = 'Inscripción Ingresos Brutos';
$this->params['breadcrumbs'][] = ['label' => 'Persona ' . $model->obj_id, 'url' => [ 'view', 'id' => $model->obj_id ] ];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="personaIB-view">

    <table border="0">

        <tr>
            <td>
                <div class="pull-left">

                    <h1 id='h1titulo'><?= $this->title ?></h1>

                </div>

                <div class="pull-right" style="margin-right: 15px">

                    <?=
                        Html::a( 'Volver', [ 'view', 'id' => $model->obj_id], [
                            'class' => 'btn btn-primary' . ( $consulta == 1 ? '' : ' hidden' ),
                        ]);
                    ?>

                </div>

                <div class="clearfix"></div>
            </td>
        </tr>

        <tr>
            <td width="85%" valign="top">
                <?=
                    $this->render('ib', [

                        'mensaje'       => $mensaje,
                        'error'         => $error,

                        'configIB'      => $configIB,
                        'model'         => $model,
                        'modelObjeto'   => $modelObjeto,
                        'modelodomileg' => $modelodomileg,
                        'action'        => $consulta,

                        'dpRubros'      => $dpRubros,

                        'permiteModificarDomiLeg'   => $permiteModificarDomiLeg,

                        //Especiales
                        'modelRubroTemporal'        => $modelRubroTemporal,
                        'mostrarModalRubros'        => $mostrarModalRubros,

                        'organizacion'  => $organizacion,
                        'liquidacion'   => $liquidacion,
						'tbajaib'       => $tbajaib
                    ]);
                ?>
            </td>

            <td valign="top" align="right">
                <?= $this->render('menu_derechoIB',['model' => $model,'consulta' => $consulta]) ?>
            </td>

        </tr>

    </table>

</div>
