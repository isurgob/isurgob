<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use app\utils\db\utb;

?>

<style>

.labelPersona {
	margin: 2px 0px 0px 2px;
}
</style>

<!-- INICIO Div Principal -->
<div id="comer_form_divPrincipal">

<?php
	$form = ActiveForm::begin([
		'id'	=> 'comer_form',
	]);
?>

<?= Html::input( 'hidden', 'txActionForm', $action ); ?>

<!-- INICIO Div Mensaje -->
<div id="comer_form_divMensaje" class="mensaje alert-success" style="display:none; margin-right: 15px">
</div>
<!-- FIN Div Mensaje -->

<!-- INICIO Div Responsable Principal -->
<div id="comer_form_divResponsablePrincipal" class="form-panel <?= $action == 0 ? "hidden" : "" ?>">

	<h3><label>Responsable Principal</label></h3>

	<table>
		<tr>
			<td><label>Persona:</label></td>
			<td>
				<?=
					Html::label( $responsablePrincipal[ 'obj_id' ], null, [
						'class'		=> 'form-control solo-lectura labelPersona',
						'style'		=> 'width:80px;text-align: center',
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label>Nombre:</label></td>
			<td colspan="4">
				<?=
					Html::label( $responsablePrincipal[ 'nombre' ], null, [
						'class'		=> 'form-control solo-lectura labelPersona',
						'style'		=> 'width:286px;text-align: left',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Sit. IVA:</label></td>
			<td>
				<?=
					Html::label( $responsablePrincipal[ 'iva_nom' ], null, [
						'class'		=> 'form-control solo-lectura labelPersona',
						'style'		=> 'width:110px;text-align: center',
					]);
				?>
			</td>
			<td></td>
			<td><label>CUIT:</label></td>
			<td>
				<?=
					Html::label( $responsablePrincipal[ 'cuit' ], null, [
						'class'		=> 'form-control solo-lectura labelPersona',
						'style'		=> 'width:100px;text-align: center',
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label>Ing. Brutos:</label></td>
			<td>
				<?=
					Html::label( $responsablePrincipal[ 'ib' ], null, [
						'class'		=> 'form-control solo-lectura labelPersona',
						'style'		=> 'width:100px;text-align: center',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Liquidación:</label></td>
			<td>
				<?=
					Html::label( $responsablePrincipal[ 'tipoliq_nom' ], null, [
						'class'		=> 'form-control solo-lectura labelPersona',
						'style'		=> 'width:110px;text-align: center',
					]);
				?>
			</td>
			<td></td>
			<td><label>Org. Juri.:</label></td>
			<td colspan="4">
				<?=
					Html::label( $responsablePrincipal[ 'orgjuri_nom' ], null, [
						'class'		=> 'form-control solo-lectura labelPersona',
						'style'		=> 'width:99%;text-align: left',
					]);
				?>
			</td>
		</tr>
	</table>
</div>
<!-- FIN Div Responsable Principal -->

<!-- INICIO Div Habilitación -->
<div id="comer_form_divHabilitacion" class="form-panel">

	<h3><label>Datos Habilitación</label></h3>

	<table border="0">
		<tr>
			<td width="70px"><label>Obj:</label></td>
			<td>
				<?=
					Html::activeInput( 'text', $modelObjeto, 'obj_id', [
						'id'		=> 'comer_form_txObjID',
						'class'		=> 'form-control solo-lectura',
						'style'		=> 'width:90px;text-align: center',
						'tabIndex'	=> '-1',
					]);
				?>
			</td>
			<td width="5px"></td>
			<td><label>Nom. Fantasía:</label></td>
			<td colspan="4">
				<?=
					Html::activeInput( 'text', $modelObjeto, 'nombre', [
						'id'		=> 'comer_form_txObjNom',
						'style'		=> 'width:99%',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'maxlength'	=> '50',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' )
					]);
				?>
			</td>
			<td width="5px"></td>
			<td><label>Est.:</label></td>
			<td>
				<?=
					Html::label( $modelObjeto->est_nom, null, [
						'class'		=> 'form-control solo-lectura' . ( $modelObjeto->est == 'B' ? ' baja' : '' ),
						'style'		=> 'width:80px; text-align: center',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Legajo:</label></td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'legajo', [
						'id'		=> 'comer_form_txLegajo',
						'style'		=> 'width:90px',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'maxlength'	=> '10',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
			<td></td>
			<td><label>Tipo:</label></td>
			<td colspan="3">
				<?=
					Html::activeDropDownList( $model, 'tipo', $tipoComercio, [
						'id'		=> 'comer_form_dlTipoComercio',
						'style'		=> 'width:99%',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Tipo Hab.:</label></td>
			<td>
				<?=
					Html::activeDropDownList( $model, 'thab', $tipoHabilitacion, [
						'id'		=> 'comer_form_dlTipoHabilitacion',
						'style'		=> 'width:90px',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
						'onchange'	=> 'f_cambiaTipoHabilitacion()',
					]);
				?>
			</td>
			<td></td>
			<td><label>Habilitación:</label></td>
			<td>
				<?=
					DatePicker::widget([
						'model' => $model,
						'attribute' => 'fchhab',
						'dateFormat' => 'php:d/m/Y',
						'options' => [
							'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
							'style' => 'width:80px;margin-right: 5px; text-align: center;',
							'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
						],
					]);
				?>
			</td>
			<td></td>
			<td colspan="2"><label>Vencimiento:</label></td>
			<td colspan="3">
				<?=
					DatePicker::widget([
						'model' => $model,
						'attribute' => 'fchvenchab',
						'dateFormat' => 'php:d/m/Y',
						'options' => [
							'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
							'style' => 'width:80px;text-align: center',
							'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
						],
					]);
				?>
			</td>
		</tr>

		<tr class="comercial <?= $model->thab == 'C' ? '' : 'hidden' ?>">
			<td><label>Sup. Cub.:</label></td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'supcub', [
						'id'		=> 'comer_form_txSuperficieCubierta',
						'style'		=> 'width:90px;text-align:center',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'maxlength'	=> '10',
						'onkeypress'	=> 'return justDecimal( $( this ).val(), event )',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
			<td></td>
			<td><label>Sup. Semi.:</label></td>
			<td colspan="2">
				<?=
					Html::activeInput( 'text', $model, 'supsemi', [
						'id'		=> 'comer_form_txSuperficieSemicubierta',
						'style'		=> 'width:80px;text-align:center',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'maxlength'	=> '10',
						'onkeypress'	=> 'return justDecimal( $( this ).val(), event )',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
			<td colspan="2"><label>Sup. Descubierta:</label></td>
			<td colspan="3">
				<?=
					Html::activeInput( 'text', $model, 'supdes', [
						'id'		=> 'comer_form_txSuperficieDescubierta',
						'style'		=> 'width:80px;text-align:center',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'maxlength'	=> '10',
						'onkeypress'	=> 'return justDecimal( $( this ).val(), event )',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
			<td></td>
		</tr>

		<tr class="comercial <?= $model->thab == 'C' ? '' : 'hidden' ?>">
			<td><label>Empleados:</label></td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'cantemple', [
						'id'		=> 'comer_form_txCantidadEmpleados',
						'style'		=> 'width:90px;text-align:center',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'maxlength'	=> '6',
						'onkeypress'	=> 'return justNumbers( event )',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
			<td></td>
			<td colspan="1">
				<?=
					Html::activecheckbox( $model, 'alquila', [
						'id'		=> 'comer_form_ckAlquila',
						'label'		=> 'Alquila',
						'uncheck'	=> '0',
						'disabled'	=> ( in_array( $action, [ 0, 3 ] ) ? false : true ),
					]);
				?>
			</td>
			<td>
				<?=
					Html::activecheckbox( $model, 'pi', [
						'id'		=> 'comer_form_ckAlquila',
						'label'		=> 'Promoción Ind.',
						'uncheck'	=> '0',
						'disabled'	=> ( in_array( $action, [ 0, 3 ] ) ? false : true ),
					]);
				?>
			</td>
		</tr>

		<tr class="comercial <?= $model->thab == 'C' ? '' : 'hidden' ?>">

			<td><label>Inmueble:</label></td>
			<td colspan="7">

				<?php Pjax::begin([ 'id' => 'comer_form_pjaxCambiaInmueble', 'enableReplaceState' => false, 'enablePushState' => false ]); ?>
				<?=
					Html::activeInput( 'text', $model, 'inmueble', [
						'id'		=> 'comer_form_txInmueble',
						'style'		=> 'width:90px',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'onchange'	=> 'f_comer_cambiaInmueble()',
						'maxlength'	=> '6',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
				<?=
					Html::button('<span class="glyphicon glyphicon-search"></span>',[
						'id'=>'btCargar1',
						'class' => 'bt-buscar',
						'onclick'=> 'f_comer_btBuscarInmueble()',
						'disabled'	=> ( in_array( $action, [ 0, 3 ] ) ? false : true ),
					]);
				?>
				<?=
					Html::input( 'text', 'txInmuebleNom', $model->inmueble_nom, [
						'id'		=> 'comer_form_txInmuebleNom',
						'style'		=> 'width:290px',
						'class'		=> 'form-control solo-lectura',
						'onkeypress'	=> 'return justNumbers( event )',
						'tabIndex'	=> '-1',
					]);
				?>

				<?php if( $error != '' ){ ?>
					<script>mostrarErrores( ["<?= $error ?>"], '#comer_form_errorSummary' );</script>
				<?php } ?>

				<?php Pjax::end(); ?>
			</td>
			<td></td>
			<td><label>Zona:</label></td>
			<td>
				<?=
					Html::activeDropDownList( $model, 'zona', $arrayZonas, [
						'id'		=> 'comer_form_dlTipoHabilitacion',
						'style'		=> 'width:80px',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
		</tr>

		<tr class="nocomercial <?= $model->thab == 'C' ? 'hidden' : '' ?>">

			<td><label>Rodado:</label></td>
			<td colspan="7">
				<?php Pjax::begin([ 'id' => 'comer_form_pjaxCambiaRodados', 'enableReplaceState' => false, 'enablePushState' => false ]); ?>
				<?=
					Html::activeInput( 'text', $model, 'rodados', [
						'id'		=> 'comer_form_txRodados',
						'style'		=> 'width:90px',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'onchange'	=> 'f_comer_cambiaRodado()',
						'maxlength'	=> '6',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
				<?=
					Html::button('<span class="glyphicon glyphicon-search"></span>',[
						'id'		=>'btCargar1',
						'class' 	=> 'bt-buscar',
						'onclick'	=> 'f_comer_btBuscarRodado()',
						'disabled'	=> ( in_array( $action, [ 0, 3 ] ) ? false : true ),
					]);
				?>
				<?=
					Html::input( 'text', 'txRodadosNom', $model->rodados_nom, [
						'id'		=> 'comer_form_txRodadosNom',
						'style'		=> 'width:270px',
						'class'		=> 'form-control solo-lectura',
						'onkeypress'	=> 'return justNumbers( event )',
						'tabIndex'	=> '-1',
					]);
				?>
				<?php if( $error != '' ){ ?>
					<script>mostrarErrores( ["<?= $error ?>"], '#comer_form_errorSummary' );</script>
				<?php } ?>
				<?php Pjax::end(); ?>
			</td>
		</tr>

		<tr>
			<td><label>Correo:</label></td>
			<td colspan="4">
				<?=
					Html::activeInput( 'text', $model, 'mail', [
						'id'		=> 'comer_form_txMail',
						'style'		=> 'width:99%',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'maxlength'	=> '50',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
			<td width="5px"></td>
			<td colspan="4">
				<label>Tel:</label>
				<?=
					Html::activeInput( 'text', $model, 'tel', [
						'id'		=> 'comer_form_txTelefono',
						'style'		=> 'width:140px',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'maxlength'	=> '15',
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
		</tr>
		<tr>
			<td><label>Distribución:</label></td>
			<td colspan='3'>
				<?=
					Html::activeDropDownList( $modelObjeto, 'tdistrib', utb::getAux('objeto_tdistrib'), [
						'id'		=> 'comer_form_dlTipoDistribucion',
						'style'		=> 'width:90%',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
			<td colspan='8' align='right'>
				<label>Distribuidor:</label>
				<?=
					Html::activeDropDownList( $modelObjeto, 'distrib', utb::getAux('sam.sis_usuario', 'usr_id', 'apenom', 1, 'distrib=1'), [
						'id'		=> 'comer_form_dlDistribuidor',
						'style'		=> 'width:75%',
						'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
						'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
					]);
				?>
			</td>
		</tr>
	</table>

	<?php if ( $modelObjeto->existemisc > 0 ) { ?>
		<div class='glyphicon glyphicon-comment' style="color:#337ab7;margin-top: 8px">
			<?= Html::a('<b>Existen Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
		</div>
	<?php } ?>

</div>
<!-- FIN Div Habilitación -->

<!-- INICIO Div Fechas -->
<div id="persona_ib_divFechas" class="form-panel" style='padding:5px' align='right'>

	<table border='0' align='center'>
		<tr>
			<td><label>Alta:</label></td>
			<td>
				<?=
					DatePicker::widget([
						'model' => $modelObjeto,
						'attribute' => 'fchalta',
						'dateFormat' => 'php:d/m/Y',
						'options' => [
							'class'		=> 'form-control' . ( in_array( $action, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
							'style' => 'width:80px;text-align: center',
							'tabIndex'	=> ( in_array( $action, [ 0, 3 ] ) ? '0' : '-1' ),
						],
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label>Baja:</label></td>
			<td>
				<?=
				 	Html::label( $modelObjeto->baja, null, [
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width:200px',
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label>Modif.:</label></td>
			<td>
				<?=
				 	Html::label( $modelObjeto->modif, null, [
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width:200px',
					]);
				?>
			</td>
		</tr>
	</table>

</div>
<!-- FIN Div Fechas -->

<!-- INICIO Div Baja -->
<?php if ( $action == 2 ){ ?>

<div id="comer_form_divBaja" class="form-panel">

	<h4><label><u>Información sobre la Baja</u></label></h4>

	<table border='0'>
		<tr>
			<td><label>Motivo de baja</label></td>
			<td>
				<?=
					Html::activeDropDownList( $modelObjeto, 'tbaja', utb::getAux('objeto_tbaja','cod','nombre',0,'tobj=2'), [
						'prompt'=>'Seleccionar...',
						'class'	=> 'form-control',
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label>Fecha Baja:</label></td>
			<td>
				<?=
					DatePicker::widget([
						'model' => $modelObjeto,
						'attribute' => 'fchbaja',
						'dateFormat' => 'php:d/m/Y',
						'options' => [
							'class'		=> 'form-control',
							'style' => 'width:80px;text-align: center',
						],
					]);
				?>
			</td>

		</tr>

		<tr>
			<td colspan="5">
				<?=
					Html::activeCheckbox( $modelObjeto, 'elimobjcondeuda', [
						'label' => 'Eliminar Objeto aún con Deuda o Saldo a favor',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td colspan="5">
				<?=
					Html::activeCheckbox( $modelObjeto, 'elimobjconadhe');
				?>
			</td>
		</tr>
	</table>

</div>

<?php } ?>
<!-- FIN Div Baja -->

<!-- INICIO Div Ventanas -->
<div id="comer_form_divVentanas" style="margin-right: 15px">

	<?php Pjax::begin([ 'id' => 'comer_form_pjaxTab', 'enableReplaceState' => false, 'enablePushState' => false ]); ?>

	<?= Tabs::widget([
		'items' => [
			[
				'label' => 'Titulares',
				'content' => $this->render('_titulares', [
					'modelObjeto' 	=> $modelObjeto,
					'action' 		=> $action,
					'dataProvider'	=> $dataProviders['dpTitulares'],
					'form' 			=> $form,
					'tab' 			=> 1,

				]),
				'options' => ['class' => 'tabItem'],
				'active' => $action != 2,
			],
			[
				'label' => 'Domicilio',
				'content' => $this->render('domicilio', [
					'modelDomicilioPostal' 	=> $modelDomicilioPostal,
					'modelDomicilioParcelario' 	=> $modelDomicilioParcelario,
					'action' 		=> $action,
					'tab' 			=> 2,

				]),
				'options' => ['class' => 'tabItem'],
			],
			[
				'label' => 'Rubro',
				'content' => $this->render('//objeto/rubro/rubro', [
					'pjaxAActualizar'		=> 'comer_rubro_pjaxGrilla',
					'tipoObjeto'			=> 2,	// Objeto de Tipo Comercio
					'modelObjeto'			=> $modelObjeto,
					'mostrarModalRubros'	=> $mostrarModalRubros,
					'modelRubroTemporal'	=> $modelRubroTemporal,
					'action' 		=> $action,
					'dataProvider'	=> $dataProviders['dpRubros'],
					'arrayRubros'	=> $arrayRubros,
					'dadosDeBaja' 			=> $dadosDeBaja,
					'soloVigentes'          => $soloVigentes,
				]),
				'options' => ['class' => 'tabItem'],
				'active' => false,
			],
			[
				'label' => 'Asignaciones',
				'content' => $this->render('//objeto/objetoasignacioneslist',[
					'modelobjeto' => $modelObjeto,
					'tab' => 4,
				]),
				'options' => ['class'=>'tabItem'],
				'active' => false,
			],
			[
				'label' => 'Acciones',
				'content' => $this->render('//objeto/objetoaccioneslist',[
					'modelobjeto' => $modelObjeto,
					'tab' => 5,
				]),
				'options' => ['class'=>'tabItem'],
				'active' => false,
			],
			[
				'label' => 'Inscrip.',
				'content' => $this->render('//objeto/objetotributos', [
					'modelObjeto' => $modelObjeto,
					'tab' => 6,
				]),
				'options' => ['class' => 'tabItem'],
				'active' => false,
			],
			[
				'label' => 'Observaciones' ,
				'content' => Html::activeTextarea( $modelObjeto, 'obs', [
					'id' => 'comercioObservacion',
					'class' => 'form-control',
					'maxlength' => 1000,
					'style' => 'width:600px;height:100px; max-width:600px; max-height:150px;',
					'onblur' => 'VariablesBaja()',
					'readonly' => $action == 1,
				]),
				'options' => ['class'=>'tabItem'],
				'active' => $action == 2,
			]
		],
	]);
	?>

	<?php Pjax::end(); ?>
</div>

<?php ActiveForm::end(); ?>

<!-- INICIO Div Botones -->
<div id="comer_form_divBotones" style="margin-top: 8px">

	<?php if( $action != 1 ){ ?>

		<?=
			Html::button( 'Grabar', [
				'id'	=> 'comer_form_btGrabar',
				'class'	=> ( $action == 2 ? 'btn btn-danger' : 'btn btn-success' ),
				'onclick'	=> 'f_comer_btGrabar()',
			]);
		?>

		&nbsp;&nbsp;

		<?=
			Html::a( 'Cancelar', [ 'view', 'id' => $modelObjeto->obj_id	], [
				'id'	=> 'comer_form_btCancelar',
				'class'	=> 'btn btn-primary',
			]);
		?>
	<?php } ?>

</div>
<!-- FIN Div Botones -->

<!-- INICIO Div Errores -->
<?=	 $form->errorSummary( $model, [
		'id'	=> 'comer_form_errorSummary',
		'style'	=> 'margin-top: 8px',
	]);
?>
<!-- FIN Div Errores -->
</div>
<!-- FIN Div Principal -->

<?php
//INICIO Pjax Camcia Domicilio
Pjax::begin(['id' => 'CargarModeloDomi', 'enablePushState' => false, 'enableReplaceState' => false]);

$tor = Yii::$app->request->post( 'tor', '' );

if( $tor === 'COM'){

	$modelDomicilioParcelario->torigen 	= 'COM';
	$modelDomicilioParcelario->obj_id 	= $model->obj_id;
	$modelDomicilioParcelario->id 		= 0;
	$modelDomicilioParcelario->prov_id 	= Yii::$app->request->post( 'prov_id', 0 );
	$modelDomicilioParcelario->loc_id 	= Yii::$app->request->post( 'loc_id', 0 );
	$modelDomicilioParcelario->cp 		= Yii::$app->request->post( 'cp', '' );
	$modelDomicilioParcelario->barr_id 	= Yii::$app->request->post( 'barr_id', 0 );
	$modelDomicilioParcelario->calle_id = Yii::$app->request->post( 'calle_id', 0 );
	$modelDomicilioParcelario->nomcalle = Yii::$app->request->post( 'nomcalle', '' );
	$modelDomicilioParcelario->puerta 	= Yii::$app->request->post( 'puerta', '' );
	$modelDomicilioParcelario->det 		= Yii::$app->request->post( 'det', '' );
	$modelDomicilioParcelario->piso 	= Yii::$app->request->post( 'piso', '' );
	$modelDomicilioParcelario->dpto 	= Yii::$app->request->post( 'dpto', '' );

	echo '<script>$("#comer_form_txDomicilioParcelario").val("'.$modelDomicilioParcelario->armarDescripcion().'")</script>';
	echo '<script>$("#arrayDomicilioParcelario").val("'.urlencode( serialize( $modelDomicilioParcelario ) ).'")</script>';
}

if( $tor === 'OBJ'){

	$modelDomicilioPostal->torigen 	= 'OBJ';
	$modelDomicilioPostal->obj_id 	= $model->obj_id;
	$modelDomicilioPostal->id 		= 0;
	$modelDomicilioPostal->prov_id 	= Yii::$app->request->post( 'prov_id', 0 );
	$modelDomicilioPostal->loc_id 	= Yii::$app->request->post( 'loc_id', 0 );
	$modelDomicilioPostal->cp 		= Yii::$app->request->post( 'cp', '' );
	$modelDomicilioPostal->barr_id 	= Yii::$app->request->post( 'barr_id', 0 );
	$modelDomicilioPostal->calle_id = Yii::$app->request->post( 'calle_id', 0 );
	$modelDomicilioPostal->nomcalle = Yii::$app->request->post( 'nomcalle', '' );
	$modelDomicilioPostal->puerta 	= Yii::$app->request->post( 'puerta', '' );
	$modelDomicilioPostal->det 		= Yii::$app->request->post( 'det', '' );
	$modelDomicilioPostal->piso 	= Yii::$app->request->post( 'piso', '' );
	$modelDomicilioPostal->dpto 	= Yii::$app->request->post( 'dpto', '' );

	echo '<script>$("#comer_form_txDomicilioPostal").val("'.$modelDomicilioPostal->armarDescripcion().'")</script>';
	echo '<script>$("#arrayDomicilioPostal").val("'.urlencode( serialize( $modelDomicilioPostal ) ).'")</script>';

}

Pjax::end();

//INICIO Ventana Modal Inmueble
Modal::begin([
	'id' => 'comer_form_modalInmueble',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
	'size' => 'modal-lg',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
]);

	echo $this->render('//objeto/objetobuscarav',[
			'idpx' 			=> 'buscaInmueble',
			'tobjeto'		=> '1',
			'id' 			=> 'comer_form_buscarInmueble',
			'txCod' 		=> 'comer_form_txInmueble',
			'txNom' 		=> 'comer_form_txInmuebleNom',
			'selectorModal' => '#comer_form_modalInmueble',
		]);

Modal::end();
//FIN Ventana Modal Inmueble

//INICIO Ventana Modal rodados
Modal::begin([
	'id' => 'comer_form_modalRodado',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
	'size' => 'modal-lg',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
]);

	echo $this->render('//objeto/objetobuscarav',[
			'idpx' 			=> 'buscaRodado',
			'tobjeto'		=> '5',
			'id' 			=> 'comer_form_buscarRodado',
			'txCod' 		=> 'comer_form_txRodados',
			'txNom' 		=> 'comer_form_txRodadosNom',
			'selectorModal' => '#comer_form_modalRodado',
		]);

Modal::end();
//FIN Ventana Modal rodados

?>

<?php if( $mensaje != '' ){ ?>

	<script>
		mostrarMensaje( "<?= $mensaje ?>", "#comer_form_divMensaje" );
	</script>

<?php } ?>

<?php if( $error != '' ){ ?>
	<script>mostrarErrores( ["<?= $error ?>"], '#comer_form_errorSummary' );</script>
<?php } ?>

<script>

function f_comer_btGrabar(){

	$( "#comer_form" ).submit();
}

function f_cambiaTipoHabilitacion(){

	var tipo = $( "#comer_form_dlTipoHabilitacion option:selected" ).val();

	$( ".comercial" ).toggleClass( "hidden", tipo != 'C' );
	$( ".nocomercial" ).toggleClass( "hidden", tipo == 'C' );

}

function f_comer_cambiaInmueble(){

	var obj_id = $( "#comer_form_txInmueble" ).val();

	ocultarErrores( "#comer_form_errorSummary" );

	if( obj_id == '' ){

		$( "#comer_form_txInmuebleNom" ).val( "" );

	} else {
		$.pjax.reload({

			container	: "#comer_form_pjaxCambiaInmueble",
			type		: "GET",
			replace		: false,
			push		: false,
			timeout 	: 1000000,
			data:{

				"obj_id"	:	obj_id,
			},
		});
	}

}

function f_comer_cambiaRodado(){

	var obj_id = $( "#comer_form_txRodados" ).val();

	if( obj_id == '' ){

		$( "#comer_form_txRodadosNom" ).val( "" );

	} else {
		$.pjax.reload({

			container	: "#comer_form_pjaxCambiaRodados",
			type		: "GET",
			replace		: false,
			push		: false,
			timeout 	: 1000000,
			data:{

				"obj_id"	:	obj_id,
			},
		});
	}
}

function f_comer_btBuscarInmueble(){

	$( "#comer_form_modalInmueble" ).modal( "show" );
}

function f_comer_btBuscarRodado(){

	$( "#comer_form_modalRodado" ).modal( "show" );
}

</script>
