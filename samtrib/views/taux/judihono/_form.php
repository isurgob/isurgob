<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl;

?>

<style>

.dropdown{
    display: inline-block;
    margin-left: 15px;
}

</style>

<!-- INICIO Div Principal -->
<div id="form_divPrincipal">

    <!-- INICIO DIV Datos -->
    <div id="form_datos">

        <?php $form = ActiveForm::begin([ 'id' => 'judihono_form', 'options' => [ 'data-pjax' => '0' ] ]); ?>

            <?= Html::input( 'hidden', 'txAction', $action ); ?>

            <table border="0">

                <tr>
                    <td width="15%"><label>Cód.:</label></td>
                    <td>
                        <?=
                            Html::activeDropDownList( $model, 'est', $arrayEstado, [
                                'id'    => 'gru_id',
                                'class' => 'form-control' . ( in_array( $action, [ 0 ] ) ? '' : ' solo-lectura' ),
                                'style' => 'width:80%; text-align: center',
                                'tabIndex'  => ( in_array( $action, [ 0 ] ) ? '0' : '-1' )
                            ]);
                        ?>
                    </td>
                    <td width="2%"></td>
                    <td width="15%"><label>Supuesto:</label></td>
                    <td>
                        <?=
                            Html::activeInput( 'text', $model, 'supuesto', [
                                'id'    => 'supuesto',
                                'class' => 'form-control' . ( in_array( $action, [ 0 ] ) ? '' : ' solo-lectura' ),
                                'style' => 'width:80%;text-align:right',
                                'tabIndex'  => ( in_array( $action, [ 0 ] ) ? '0' : '-1' ),
                                'maxlength' => '6',
                                'onkeypress' => "return justNumber(event);"
                            ]);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><label>Deuda desde:</label></td>
                    <td>
                        <?=
                            Html::activeInput( 'text', $model, 'deuda_desde', [
                                'id'    => 'deuda_desde',
                                'class' => 'form-control' . ( in_array( $action, [ 0 ] ) ? '' : ' solo-lectura' ),
                                'style' => 'width:30%;text-align:right',
                                'tabIndex'  => ( in_array( $action, [ 0 ] ) ? '0' : '-1' ),
                                'maxlength' => '9',
                                'onkeypress' => "return justDecimal(this.value,event);"
                            ]);
                        ?>
                    </td>
                    <td></td>
                    <td><label>Deuda hasta:</label></td>
                    <td>
                        <?=
                            Html::activeInput( 'text', $model, 'deuda_hasta', [
                                'id'    => 'deuda_desde',
                                'class' => 'form-control' . ( in_array( $action, [ 0 ] ) ? '' : ' solo-lectura' ),
                                'style' => 'width:80%;text-align:right',
                                'tabIndex'  => ( in_array( $action, [ 0 ] ) ? '0' : '-1' ),
                                'maxlength' => '9',
                                'onkeypress' => "return justDecimal(this.value,event);"
                            ]);
                        ?>
                    </td>
                </tr>
				<tr>
                    <td><label>Mínimo:</label></td>
                    <td colspan="2">
                        <?=
                            Html::activeInput( 'text', $model, 'hono_min', [
                                'id'    => 'hono_min',
                                'class' => 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
                                'style' => 'width:30%;text-align:right',
                                'tabIndex'  => ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
                                'maxlength' => '9',
								'onkeypress' => "return justDecimal(this.value,event);"
                            ]);
                        ?>
                        <label>Porcentaje:</label>
                        <?=
                            Html::activeInput( 'text', $model, 'hono_porc', [
                                'id'    => 'hono_porc',
                                'class' => 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
                                'style' => 'width:30%;text-align:right',
                                'tabIndex'  => ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
                                'maxlength' => '9',
								'onkeypress' => "return justDecimal(this.value,event);"
                            ]);
                        ?>
					</td>
					<td> <label>Gastos:</label> </td>
					<td>
                        <?=
                            Html::activeInput( 'text', $model, 'gastos', [
                                'id'    => 'gastos',
                                'class' => 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
                                'style' => 'width:80%;text-align:right',
                                'tabIndex'  => ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
                                'maxlength' => '9',
								'onkeypress' => "return justDecimal(this.value,event);"
                            ]);
                        ?>
                    </td>
                </tr>

            </table>

        <?php ActiveForm::end(); ?>

    </div>
    <!-- FIN Div Datos -->

    <!-- INICIO Div Botones -->
    <div id="form_divBotones" class="text-center" style="margin-top: 8px;">

        <?php if( $action != 1 ){ ?>
            <?=
                Html::button( ( $action == 2 ? 'Eliminar' : 'Aceptar' ), [
                    'id'    => 'form_btAceptar',
                    'class' => ( $action == 2 ? 'btn btn-danger' : 'btn btn-success' ),
                    'style' => 'margin-right: 15px',
                    'onclick'   => 'f_btAceptar()',
                ]);
            ?>
        <?php } ?>

        <?=
            Html::button( ( $action == 1 ? 'Volver' : 'Cancelar' ), [
                'id'    => 'form_btCancelar',
                'class' => 'btn btn-primary',
                'onclick'   => 'f_btCancelar()',
            ]);
        ?>

    </div>
    <!-- FIN Div Botones -->

    <!-- INCICIO Div Errores -->
    <?=
        $form->errorSummary( $model, [
            'id'    => 'funcion_form_errorSummary',
            'style' => 'margin-top: 8px;',
        ]);
    ?>
    <!-- FIN Div Errores -->

</div>
<!-- FIN Div Principal -->

<script>

function f_btAceptar(){

    $( "#judihono_form" ).submit();
}

function f_btCancelar(){

    $( ".modal" ).modal( "hide" );
}

</script>
