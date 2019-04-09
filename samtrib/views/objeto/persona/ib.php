<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use \yii\widgets\Pjax;
use yii\helpers\BaseUrl;
//use yii\jui\AutoComplete;
use yii\bootstrap\Tabs;
use \yii\bootstrap\Modal;
use app\utils\db\utb;

?>


<!-- INICIO Div Principal -->
<div id="persona_ib_divPrincipal">

    <div id="persona_ib_form_divMensaje" class="alert-success mensaje" style="display:none; margin-right: 15px">
    </div>

    <?php
        $form = ActiveForm::begin([
				'id'    => 'persona_ib_form',
			]);	

    ?>

    <?=
        Html::input( 'hidden', 'txActionFormIB', $action );
    ?>

    <!--INICIO Div Datos Persona -->
    <div id="persona_ib_divDatosPersona" class="form-panel" >

        <h3><label>Datos Persona</label></h3>
        <table>
            <tr>
                <td><label>Objeto:</label></td>
                <td>
                    <?=
                        Html::label( $model->obj_id, null, [
                            'class'     => 'form-control solo-lectura',
                            'style'     => 'text-align:center; width: 80px',
                        ]);
                    ?>
                    <?=
                        Html::activeInput( 'hidden', $model, 'obj_id' );
                    ?>
                </td>
                <td width="20px"></td>
                <td><label>Nombre:</label></td>
                <td>
                    <?=
                        Html::label( $modelObjeto->nombre, null, [
                            'class'     => 'form-control solo-lectura',
                            'style'     => 'text-align:left; width: 350px',
                        ]);
                    ?>
                </td>
            </tr>

            <tr>
                <td><label>Siv. Iva:</label></td>
                <td>
                    <?=
                        Html::label( $model->sit_iva_nom, null, [
                            'class'     => 'form-control solo-lectura',
                            'style'     => 'text-align:center; width: 120px',
                        ]);
                    ?>
                </td>
                <td width="20px"></td>
                <td><label>CUIT/CUIL:</label></td>
                <td>
                    <?=
                        Html::label( $model->cuit, null, [
                            'class'     => 'form-control solo-lectura',
                            'style'     => 'text-align:left; width: 120px',
                        ]);
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <!-- FIN Div Datos Persona -->

    <!-- INICIO Div Datos IB -->
    <div id="persona_ib_divDatosIB" class="form-panel">

        <table border="0">
            <tr>
				<td colspan="8">
                    <label>Nombre Fantasía:</label>
                    <?=
                        Html::activeInput( 'text', $model, 'nombre_fantasia', [
                            'id'        => 'persona_ib_txNombreFantasia',
                            'class'     => 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
                            'style'     => 'text-align:left; width: 80%;font-weight: bold',
                            'maxlength' => 50,
                            'tabIndex'  => ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
                        ]);
                    ?>
                </td>
			</tr>
			<tr>
                <td> <label>N° IB:</label> </td>
				<td>
                    <?=
                        Html::activeInput( 'text', $model, 'ib', [
                            'id'        => 'persona_ib_txIB',
                            'class'     => 'form-control' . ( in_array( $action, [ 0, 3 ] ) && ($configIB == 'M' || in_array($model->tipoliq,['AI','CM']) ) ? '' : ' solo-lectura' ),
                            'style'     => 'text-align:center; width: 108px;font-weight: bold',
                            'maxlength' => 11,
                            'tabIndex'  => ( in_array( $action, [ 0, 3 ] ) && $configIB == 'M' ? '0' : '-1' ),
                        ]);
                    ?>
                </td>
                <td width="20px"></td>
                <td><label>Org. Juri.</label></td>
                <td>
                    <?=
                        Html::ActiveDropDownList( $model, 'orgjuri', $organizacion, [
                            'id'        => 'persona_ib_dlOrgJuri',
                            'class'     => 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
                            'style'     => 'width:160px',
                            'tabIndex'  => ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
                        ]);
                    ?>
                </td>
                <td width="20px"></td>
                <td><label>Tipo. Liq.</label></td>
                <td>
                    <?=
                        Html::ActiveDropDownList( $model, 'tipoliq', $liquidacion, [
                            'id'        => 'persona_ib_dlTipoLiq',
                            'class'     => 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
                            'style'     => 'width:130px',
                            'tabIndex'  => ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
                        ]);
                    ?>
                </td>
            </tr>
		
            <tr>
                <td><label>Contador:</label></td>
                <td colspan="5">
                    <?=
                        Html::activeInput( 'text', $model, 'contador', [
                            'id'        => 'persona_ib_txContador',
                            'class'     => 'form-control solo-lectura',
                            'style'     => 'text-align:center; width: 80px',
                            'tabIndex'  => '-1',
                        ]);
                    ?>
                    <?=
                        Html::input( 'text', 'txContadorNom', $model->contador_nom, [
                            'id'        => 'persona_ib_txContadorNom',
                            'class'     => 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
                            'style'     => 'text-align:left; width: 260px',
                            'tabIndex'  => ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
                            //'onchange'  => 'f_cambiaContador( $( this ).val() )',
                        ]);
                    ?>
                </td>
                <td colspan="2">
                    <?=
                        Html::activeCheckBox( $model, 'contador_verdeuda', [
                            'label'     => 'Ver deuda',
                            'unchec'    => '0',
                            'value'     => 1,
                            'disabled'  => !in_array( $action, [ 0, 3 ] ),
                        ]);
                    ?>
                </td>
            </tr>

            <tr>
    			<td><label>Legal:</label> </td>
				<td  colspan="20">
    				<?=
    					Html::button( '<i class="glyphicon glyphicon-search"></i>', [
    						'id'		=> 'persona_ib_btDomicilioLegal',
    						'class' 	=> 'bt-buscar',
    						'disabled' 	=> in_array( $action, [ 1, 2 ] ),
    						'onclick'	=> 'f_btDomicilioLegal()',
    					]);
    				?>

    				<?=
    					Html::input( 'text', 'txDomicilioLegal', $modelodomileg->domicilio, [
    						'id'	=> 'persona_ib_txDomicilioLegal',
    						'class'	=> 'form-control solo-lectura',
    						'style'	=> 'width:500px',
    						'tabIndex'	=> '-1',
    					]);
    				?>

                	<input type="hidden" name="arrayDomiLeg" id="arrayDomiLeg" value="<?= urlencode( serialize( $modelodomileg ) ); ?>">
    			</td>
    		</tr>
		</table>
		
		<table border="0">
            <tr>
                <td colspan='5'><label>Fecha Alta:</label>
    				<?=
    					DatePicker::widget([
    						'model' => $model,
    						'attribute' => 'fchalta_ib',
    						'dateFormat' => 'dd/MM/yyyy',
    						'options' => [
    							'class'		=> 'form-control' . ( in_array( $action, [ 0, 3, 4 ] ) ? '' : ' solo-lectura' ),
    							'style' => 'width:70px;text-align: center',
    							'tabIndex'	=> ( in_array( $action, [ 0, 3, 4 ] ) ? '0' : '-1' ),
    						],
    					]);
    				?>
					<label>Baja:</label>
					<?=
    					DatePicker::widget([
    						'model' => $model,
    						'attribute' => 'fchbaja_ib',
    						'dateFormat' => 'dd/MM/yyyy',
    						'options' => [
    							'class'		=> 'form-control' . ( in_array( $action, [ 2 ] ) ? '' : ' solo-lectura' ),
    							'style' => 'width:70px;text-align: center',
    							'tabIndex'	=> ( in_array( $action, [ 2 ] ) ? '0' : '-1' ),
    						],
    					]);
    				?>
					<label>Tipo Baja:</label>
					<?=
                        Html::ActiveDropDownList( $model, 'tbaja_ib', $tbajaib, [
                            'id'        => 'persona_dlTBajaIB',
                            'class'     => 'form-control' . ( in_array( $action, [ 2 ] ) ? '' : ' solo-lectura' ),
							'tabIndex'  => ( in_array( $action, [ 2 ] ) ? '0' : '-1' ),
                        ]);
                    ?>
    			</td>
                <td></td>
                <td><label>Estado:</label></td>
                <td>
    				<?=
    					Html::label( $model->est_ib_nom, null, [
                            'class'     => 'form-control solo-lectura' . ( $model->est_ib == 'B' ? ' baja' : '' ),
                            'style'     => 'text-align:center; width: 80px',
                        ]);
    				?>
    			</td>
            </tr>
        </table>

    </div>
    <!-- FIN Div Datos IB -->

    <?php if( $model->requiereRubros ){ ?>

        <!-- INICIO Div Rubros -->
        <div id="persona_ib_divRubros" style="margin-right: 15px">
            <?php Pjax::begin([ 'id' => 'persona_ib_form_pjaxTab', 'enableReplaceState' => false, 'enablePushState' => false ]); ?>

        	<?= Tabs::widget([
        		'items' => [
        			[
        				'label' => 'Rubros',
        				'content' => $this->render('//objeto/rubro/rubro', [
                            'pjaxAActualizar'		=> 'persona_rubro_pjaxGrilla',
                            'tipoObjeto'			=> 3,
        					'modelObjeto'			=> $modelObjeto,
        					'mostrarModalRubros'	=> $mostrarModalRubros,
        					'modelRubroTemporal'	=> $modelRubroTemporal,
        					'action' 		=> $action,
        					'dataProvider'	=> $dpRubros,
        				]),
        				'options' => ['class' => 'tabItem'],
        				'active' => true,
        			],
        		],
        	]);
        	?>

        	<?php Pjax::end(); ?>
        </div>
        <!-- FIN Div Rubros -->

    <?php } ?>

    <?php ActiveForm::end(); ?>

    <!-- INICIO Div Botones -->
    <div id="persona_ib_form_divBotones" style="margin-top: 8px">

    	<?php if( $action != 1 ){ ?>

    		<?=
    			Html::button( 'Grabar', [
    				'id'	=> 'persona_ib_form_btGrabar',
    				'class'	=> ( $action == 2 ? 'btn btn-danger' : 'btn btn-success' ),
    				'onclick'	=> 'f_persona_ib_btGrabar()',
    			]);
    		?>

    		&nbsp;&nbsp;

    		<?=
    			Html::a( 'Cancelar', [ 'view', 'id' => $modelObjeto->obj_id	], [
    				'id'	=> 'persona_ib_form_btCancelar',
    				'class'	=> 'btn btn-primary',
    			]);
    		?>
    	<?php } ?>

    </div>
    <!-- FIN Div Botones -->

    <!-- INICIO Div Errores -->
    <?=	 $form->errorSummary( $model, [
    		'id'	=> 'persona_ib_form_errorSummary',
    		'style'	=> 'margin-top: 8px',
    	]);
    ?>
    <!-- FIN Div Errores -->

</div>
<!-- FIN Div Principal -->

<?php if( $mensaje != '' ){ ?>

    <script>
    mostrarMensaje( "<?= $mensaje ?>", "#persona_ib_form_divMensaje" );
    </script>

<?php } ?>

<?php if( $error != '' ){ ?>

    <script>
    mostrarErrores( ["<?= $mensaje ?>"], "#persona_ib_form_errorSummary" );
    </script>

<?php } ?>

<?php

Pjax::begin([ 'id' => 'comer_form_pjaxCopiarDomicilio', 'enablePushState' => false, 'enableReplaceState' => false ]);

	//INICIO Ventana Modal Domicilio Parcelario
	Modal::begin([
		'id' => 'BuscaDomiL',
		'header' => '<h2>Búsqueda de Domicilio</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right'
		]
	]);

	echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelodomileg, 'tor' => 'PLE']);

	Modal::end();
	//INICIO Ventana Modal Domicilio Parcelario

Pjax::end();

?>

<?php
//INICIO Pjax Camcia Domicilio
Pjax::begin(['id' => 'CargarModeloDomi', 'enablePushState' => false, 'enableReplaceState' => false]);

$tor = Yii::$app->request->post( 'tor', '' );

if( $tor === 'PLE'){

	$modelodomileg->torigen    = 'PLE';
	$modelodomileg->obj_id 	   = $model->obj_id;
    $modelodomileg->id 		   = 0;
	$modelodomileg->prov_id    = Yii::$app->request->post( 'prov_id', 0 );
	$modelodomileg->loc_id 	   = Yii::$app->request->post( 'loc_id', 0 );
    $modelodomileg->cp 		   = Yii::$app->request->post( 'cp', '' );
	$modelodomileg->barr_id    = Yii::$app->request->post( 'barr_id', 0 );
	$modelodomileg->calle_id   = Yii::$app->request->post( 'calle_id', 0 );
	$modelodomileg->nomcalle   = Yii::$app->request->post( 'nomcalle', '' );
	$modelodomileg->puerta 	   = Yii::$app->request->post( 'puerta', '' );
	$modelodomileg->det        = Yii::$app->request->post( 'det', '' );
    $modelodomileg->piso       = Yii::$app->request->post( 'piso', '' );
    $modelodomileg->dpto       = Yii::$app->request->post( 'dpto', '' );

	echo '<script>$("#persona_ib_txDomicilioLegal").val("'.$modelodomileg->armarDescripcion().'")</script>';
	echo '<script>$("#arrayDomiLeg").val("'.urlencode( serialize( $modelodomileg ) ).'")</script>';
}

Pjax::end();

?>

<script>

function f_persona_ib_btGrabar(){

    var error = new Array();

    if( error.length == 0 ){

        $( "#persona_ib_form" ).submit();
    }
}

function f_persona_cambiaContador( borrarDatos ){

	ocultarErrores( "#persona_ib_form_errorSummary" );

	if( borrarDatos ){

		$( "#persona_ib_txContador" ).val( '' );
		$( "#persona_ib_txContadorNom" ).val( '' );
	}

	$( "#persona_ib_txContadorNom" ).toggleClass( 'solo-lectura', <?= $action ?> != 0 );

	//se actualiza la URL del autocomplete
	$("#persona_form_txRubroNom").autocomplete({
		source: "<?= BaseUrl::toRoute('//objeto/persona/sugerenciacontador'); ?>",
	});

}

$( document ).ready( function() {

	//convierte el input en un elemento de autocompletado
	$( "#persona_ib_txContadorNom" ).autocomplete({

		source: "<?= BaseUrl::toRoute('//objeto/persona/sugerenciacontador'); ?>",
		select: function(event, ui){	//Cuando se selecciona un elemento del autocomplete

			var nombre = ui.item.value;

			$.get("<?= BaseUrl::toRoute('//objeto/persona/codigocontador');?>&nombre=" + nombre)
			.success(function( data ){
				$("#persona_ib_txContador").val( data );
			});
		}
	});

	//habilita que se vea el listado de autocompletado en el modal
	$(".ui-autocomplete").css("z-index", "5000");

	//f_persona_cambiaContador( false );

});

function f_cambiaContador( nombre ){

    if( nombre.length < 3 ){
        f_persona_cambiaContador( true );
    }
}


//INICIO Cargar Domicilio Parcelario
function f_btDomicilioLegal(){

	$( "#BuscaDomiL" ).modal( "show" );
}
//FIN Cargar Domicilio Parcelario

</script>
