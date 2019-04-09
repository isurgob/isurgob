<?php

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

use app\utils\db\utb;

$titulo= 'Consulta ' . $model->trib_id;

switch($consulta){

	case 0: $titulo= 'Nuevo'; break;
	case 2: $titulo= 'Eliminar ' . $model->trib_id; break;
	case 3: $titulo= 'Modificar ' . $model->trib_id; break;
}

$this->params['breadcrumbs'][]= ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][]= ['label' => 'Tributos', 'url' => ['index']];
$this->params['breadcrumbs'][]= $titulo;

$puedeModificar= $model->trib_id > 20 || $model->isNewRecord;
?>

<div class="form-trib">

	<h1>Formulario de tributos</h1>


	<?php
	if(isset($mensaje) && trim($mensaje) != ''){

		echo Alert::widget([
			'options' => ['class' => 'alert alert-success'],
			'id' => 'alertMensaje',
			'body' => $mensaje
		]);

		?>
		<script type="text/javascript">
		$(document).ready(function(){
			setTimeout(function(){$("#alertMensaje").fadeOut();}, 5000);
		});
		</script>
		<?php
	}
	?>

	<?php
	$form= ActiveForm::begin(['fieldConfig' => ['template' => '{label}{input}'], 'id' => 'formTributo', 'validateOnSubmit' => false]);

	echo Html::input('hidden', 'grabar', true);
	?>
	<div class="form" style="padding:10px;">
		<table width="100%" border="0">

			<tr>
				<td width='10%'><b>Código:</b></td>
				<td colspan='3'>
					<?= $form->field($model, 'trib_id', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:50%;', 'class' => 'form-control solo-lectura', 'tabindex' => -1])->label(false); ?>
				</td>
				<td><b>Estado:</b></td>
				<td>
					<?php
					$strEstado= '';
					$estados= ['A' => 'Activo', 'B' => 'Baja', 'N' => 'Nuevo'];

					if(array_key_exists($model->est, $estados)) $strEstado= $estados[$model->est];

					echo $form->field($model, 'est', ['options' => ['style' => 'display:inline-block;']])
					->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:100%;', 'value' => $strEstado])->label(false);
					?>
				</td>
			</tr>

			<tr>
				<td><b>Nombre:</b></td>

				<td width='35%'><?= $form->field($model, 'nombre')->textInput(['style' => 'width:98%;', 'maxlength' => 50, 'disabled' => !$puedeModificar])->label(false); ?></td>
				<td width='12%'><b>Reducido:</b></td>
				<td><?= $form->field($model, 'nombre_redu')->textInput(['style' => 'width:98%;', 'maxlength' => 15, 'disabled' => !$puedeModificar])->label(false) ?></td>

				<td width='9%'><b>H. Bank:</b></td>
				<td>
					<?= $form->field($model, 'nombre_reduhbank', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:100%;', 'disabled' => !$requerido('nombre_reduhbank'), 'id' => 'nombreHomeBanking', 'maxlength' => 5])->label(false); ?>
				</td>
			</tr>
			<tr>
				<td><b>Tipo de trib.:</b></td>
				<td>
					<?php
					$condicion= ($consulta === 3 && $model->esInterno) ? 'cod >= 0' : 'cod > 0';

					echo $form->field($model, 'tipo')
					->dropDownList(utb::getAux('trib_tipo', 'cod', 'nombre', 0, $condicion), ['prompt' => '', 'onchange' => 'cambiaTipo($(this).val());', 'style' => 'width:98%;', 'id' => 'tipoTributo', 'disabled' => !$puedeModificar])
					->label(false);
					?>
				</td>
				<td><b>Tipo objeto:</b></td>
				<td>
				<?= $form->field($model, 'tobj')
				->dropDownList(utb::getAux('objeto_tipo'), ['disabled' => !$requerido('tobj'), 'prompt' => '', 'style' => 'width:98%;', 'onchange' => 'cambiaTipoObjeto($(this).val());', 'id' => 'tipoObjeto', 'disabled' => !$puedeModificar])
				->label(false);
				?>
				</td>
			</tr>
			<tr>
				<td><b>Texto:</b></td>
				<td><?= $form->field($model, 'texto_id')->dropDownList(utb::getAux('texto', 'texto_id'), ['disabled' => !$requerido('texto_id'), 'prompt' => '', 'style' => 'width:98%;', 'id' => 'texto'])->label(false); ?></td>
				<td style='display:none'><b>DJ. Trib. Princ.:</b></td>
				<td style='display:none'>
					<?= $form->field($model, 'dj_tribprinc')
					->dropDownList(utb::getAux('trib', 'trib_id', 'nombre', 0, 'tipo = 2'), ['prompt' => '', 'disabled' => !$requerido('dj_tribprinc'), 'style' => 'width:98%;', 'id' => 'tributoPrincipal'])
					->label(false);
					?>
				</td>
				<td><b>Valor UCM:</b></td>
				<td>
					<?php
					$items= [0 => 'Sin UCM', 1 => 'Valor UCM 1', 2 => 'Valor UCM 2', 3 => 'Módulo Actual'];

					echo $form->field($model, 'ucm', ['options' => ['style' => 'display:inline-block;']])->dropDownList($items, ['prompt' => '', 'style' => 'width:100%;'])->label(false);
					?>
				 </td>
			</tr>
			<tr>
				<td colspan='6'>
					<b>Porc. quita facilidad:</b>

					<?= $form->field($model, 'quitafaci', ['options' => ['style' => 'display:inline-block']])
					->textInput(['style' => 'width:50%;', 'disabled' => !$requerido('quitafaci'), 'id' => 'quitaFacilidad', 'maxlength' => 5])->label(false)
					?>
				</td>
			</tr>
			<tr>
				<td colspan='6'>
					<table width='100%'>
						<tr>
							<td>
							<?= $form->field($model, 'calc_rec')
							->checkbox(['disabled' => !$requerido('calc_rec'), 'value' => 1, 'unckeck' => 0, 'label' => 'Calcula interés', 'class' => 'checkHabilitador', 'data_inverso' => 'false',
							'data-target' => '#codigoCuentaRecargo, #botonModalCuentaRecargo, #tasaRecargo', 'id' => 'checkCalculoRecargo']);
							?>
							</td>

							<td><?= $form->field($model, 'genestcta')->checkbox(['value' => 1, 'unckeck' => 0, 'label' => 'Genera estado cta cte.', 'disabled' => !$requerido('genestcta'), 'id' => 'checkGeneraEstadoCuentaCorriente']); ?></td>
							<td><?= $form->field($model, 'compensa')->checkbox(['value' => 1, 'unckeck' => 0, 'label' => 'Permite compensar', 'disabled' => !$requerido('compensa'), 'id' => 'checkCompensa']); ?></td>

							<td><?= $form->field($model, 'bol_domimuni')
							->checkbox(['value' => 1, 'unckeck' => 0, 'label' => 'Usar domic. general', 'onclick' => 'usaDomicilioGeneral($(this).is(":checked"));', 'class' => 'checkHabilitador',
							'data-target' => '#domicilio, #telefono, #mail', 'data-inverso' => 'true']); ?></td>
						</tr>
						<tr>
							<td><?= $form->field($model, 'uso_subcta')->checkbox(['value' => 1, 'unckeck' => 0, 'label' => 'Usa subcuenta', 'disabled' => !$requerido('uso_subcta'), 'id' => 'checkUsaSubCuenta']); ?></td>

							<td><?= $form->field($model, 'uso_mm')->checkbox(['value' => 1, 'unckeck' => 0, 'label' => 'Usa módulos municipales']); ?></td>

							<td><?= $form->field($model, 'inscrip_req')
							->checkbox(['value' => 1, 'unckeck' => 0, 'label' => 'Inscripción requerida', 'disabled' => !$requerido('inscrip_req'), 'data-inverso' => 'false',
							'class' => 'checkHabilitador', 'data-target' => '#checkInscripcionAutomatica, #checkInscripcionIncompatible', 'checkInscripcionRequerida']); ?></td>

							<td><?= $form->field($model, 'inscrip_auto')->checkbox(['value' => 1, 'unckeck' => 0, 'label' => 'Inscripción auto.', 'id' => 'inscripcionAutomatica', 'disabled' => !$requerido('inscrip_auto')]); ?></td>
						</tr>
						<tr>
							<td><?= $form->field($model, 'calc_act')->checkbox(['value' => 1, 'unckeck' => 0, 'label' => 'Calcula Acualización Tributo', 'id' => 'checkCalcActTrib']); ?></td>

							<td><?= $form->field($model, 'calc_act_faci')->checkbox(['value' => 1, 'unckeck' => 0, 'label' => 'Calcula Acualización en Facilidades']); ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan='6'>
				<?= Html::checkbox(null, false, ['value' => 1, 'unckeck' => 0, 'disabled' => true, 'label' => 'Inscripción incompatible:', 'data-inverso' => 'false',
				'class' => 'checkHabilitador', 'data-target' => '#inscripcionIncompatible', 'id' => 'checkInscripcionIncompatible']); ?>

				<?php



				Pjax::begin(['id' => 'pjaxTipoObjeto', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);

				$codigoTipoTributo= intval(Yii::$app->request->get('tipoTributo', -1));
				$codigoTipoObjeto= intval(Yii::$app->request->get('tipoObjeto', 0));
				$items= [];

				if($codigoTipoTributo > -1 && $codigoTipoObjeto > 0)
					$items= utb::getAux('trib', 'trib_id', 'nombre', 0, "tipo = $codigoTipoTributo And tobj = $codigoTipoObjeto");


				echo $form->field($model, 'inscrip_incomp', ['options' => ['style' => 'display:inline-block;']])
				->dropDownList($items, ['id' => 'inscripcionIncompatible', 'disabled' => count($items) === 0, 'style' => 'width:375px;', 'prompt' => ''])
				->label(false);

				echo Html::input('hidden', null, null);
				Pjax::end();

				?>
				</td>
			</tr>
			<tr>
				<td><b>Domicilio:</b></td>
				<td><?= $form->field($model, 'bol_domi')->textInput(['id' => 'domicilio', 'disabled' => !$requerido('bol_domi'), 'style' => 'width:98%;', 'maxlength' => 30])->label(false); ?></td>
				<td><b>Teléfono.:</b></td>
				<td><?= $form->field($model, 'bol_tel', ['options' => ['style' => 'display:inline-block;']])->textInput(['id' => 'telefono', 'disabled' => !$requerido('bol_tel'), 'style' => 'width:98%;', 'maxlength' => 30])->label(false); ?></td>
				<td><b>Mail:</b></td>
				<td><?= $form->field($model, 'bol_mail', ['options' => ['style' => 'display:inline-block;']])->textInput(['id' => 'mail', 'disabled' => !$requerido('bol_mail'), 'style' => 'width:100%;', 'maxlength' => 30])->label(false); ?></td>
			</tr>
		</table>
		<table width="100%" border="0">
			<tr>
				<td width='15%'><b>Tasa recargo:</b></td>
				<td colspan='2'><?= $form->field($model, 'calc_rec_tasa')->textInput(['style' => 'width:70px;', 'id' => 'tasaRecargo', 'disabled' => !$requerido('calc_rec_tasa'), 'maclength' => 8])->label(false); ?></td>
				<td colspan='3' align='right'><b>Recargo 2º vencimiento:</b>
					<?= Html::radio('rbRecargo',$model->rec_venc2 != -1,['id'=>'rbRecargo','label'=>'Fijo:','onchange' => 'rbRecargoClick()'])?>
					<?= $form->field($model, 'rec_venc2', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:70px;', 'id' => 'tasaRecargo2', 'readonly' => ($requerido('rec_venc2') && $model->rec_venc2 != -1 ? false : true), 'maxlength' => 8])->label(false); ?>
					<?= Html::radio('rbRecargo',$model->rec_venc2 == -1,['id'=>'rbSegunSegVenc','label'=>'Según 2do. Venc.','onchange' => 'rbSegunSegVencClick()'])?>
				</td>
			</tr>
			<tr>
				<td> <b>Cuenta recargo:</b> </td>
				<td colspan='5'>

				<?php

				echo $form->field($model, 'cta_id_rec',
				['options' => ['style' => 'display:inline-block;']])->textInput(['id' => 'codigoCuentaRecargo', 'style' => 'width:50px;', 'disabled' => !$requerido('cta_id_rec'), 'maxlength' => 5, 'onchange' => 'cambiaCuenta($(this).val(), "#pjaxNombreCuentaRecargo");'])->label(false);


				Modal::begin([
					'id' => 'modalCuentaRecargo',
					'size' => 'modal-normal',
					'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cuenta Recargo</h2>',
					'closeButton' => [
					  'label' => '<b>X</b>',
					  'class' => 'btn btn-danger btn-sm pull-right',
					 ],
					'toggleButton' => [
						'label' => '<span class="glyphicon glyphicon-search"></span>',
						'id' => 'botonModalCuentaRecargo',
						'class' => 'bt-buscar',
						'disabled' => !$requerido('cta_id_req')
					],

				]);

				Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);

				echo $this->render('//taux/auxbusca', [
					'tabla' => 'cuenta',
					'campocod' => 'cta_id',
					'camponombre' => 'nombre',
					'idcampocod' => 'codigoCuentaRecargo',
					'idcamponombre' => 'nombreCuentaRecargo',
					'boton_id' => 'botonModalCuentaRecargo',
					'idModal' => 'modalCuentaRecargo',
					'criterio' => 'tcta = 3',
					'idAux' => 'crc'
				]);

				Pjax::end();
				Modal::end();

				Pjax::begin(['id' => 'pjaxNombreCuentaRecargo', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);

				$codigoCuenta= intval(Yii::$app->request->get('codigoCuenta', 0));

				if($codigoCuenta > 0){
					$model->cta_nom_rec= utb::getCampo('cuenta', "cta_id = $codigoCuenta and tcta=3", 'nombre');
					if($model->cta_nom_rec === false) $model->cta_nom_rec= null;
				}

				echo $form->field($model, 'cta_nom_rec', ['options' => ['style' => 'display:inline-block;']])
				->textInput(['id' => 'nombreCuentaRecargo', 'class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:365px;'])
				->label(false);

				Pjax::end();
				?>

				</td>
			</tr>
			<tr>
				<td> <b>Cuenta descuento:</b> </td>
				<td colspan='5'>

				<?php

				// echo $form->field($model, 'cta_id_desc',
				// ['options' => ['style' => 'display:inline-block;']])->textInput(['id' => 'codigoCuentaDescuento', 'style' => 'width:50px;', 'maxlength' => 5, 'onchange' => 'cambiaCuenta($(this).val(), "#pjaxNombreCuentaDescuento");'])->label(false);


				Modal::begin([
					'id' => 'modalCuentaDescuento',
					'size' => 'modal-normal',
					'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cuenta Descuento</h2>',
					'closeButton' => [
					  'label' => '<b>X</b>',
					  'class' => 'btn btn-danger btn-sm pull-right',
					 ],
					'toggleButton' => [
						'label' => '<span class="glyphicon glyphicon-search"></span>',
						'id' => 'botonModalCuentaDescuento',
						'class' => 'bt-buscar'
					],

				]);

				Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);

				echo $this->render('//taux/auxbusca', [
					'tabla' => 'cuenta',
					'campocod' => 'cta_id',
					'camponombre' => 'nombre',
					'idcampocod' => 'codigoCuentaDescuento',
					'idcamponombre' => 'nombreCuentaDescuento',
					'boton_id' => 'botonModalCuentaDescuento',
					'idModal' => 'modalCuentaDescuento',
					'criterio' => 'tcta = 2',
					'idAux' => 'crde'
				]);

				Pjax::end();
				Modal::end();

				Pjax::begin(['id' => 'pjaxNombreCuentaDescuento', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);

				$codigoCuenta= intval(Yii::$app->request->get('codigoCuenta', 0));

				if($codigoCuenta > 0){
					$model->cta_nom_desc= utb::getCampo('cuenta', "cta_id = $codigoCuenta and tcta=2", 'nombre');
					if($model->cta_nom_desc === false) $model->cta_nom_desc= null;
				}

				// echo $form->field($model, 'cta_nom_desc', ['options' => ['style' => 'display:inline-block;']])
				// ->textInput(['id' => 'nombreCuentaDescuento', 'class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:365px;'])
				// ->label(false);

				Pjax::end();
				?>

				</td>
			</tr>

			<tr>
				<td> <b>Cuenta redondeo:</b> </td>
				<td colspan='5'>
				<?php

				echo $form->field($model, 'cta_id_redon',
				['options' => ['style' => 'display:inline-block;']])->textInput(['id' => 'codigoCuentaRedondeo', 'style' => 'width:50px;', 'maxlength' => 5, 'onchange' => 'cambiaCuenta($(this).val(), "#pjaxNombreCuentaRedondeo");'])->label(false);


				Modal::begin([
					'id' => 'modalCuentaRedondeo',
					'size' => 'modal-normal',
					'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cuenta Redondeo</h2>',
					'toggleButton' => [
						'label' => '<span class="glyphicon glyphicon-search"></span>',
						'id' => 'botonModalCuentaRedondeo',
						'class' => 'bt-buscar'
					],
					'closeButton' => [
					  'label' => '<b>X</b>',
					  'class' => 'btn btn-danger btn-sm pull-right',
					 ],
				]);

				Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);

				echo $this->render('//taux/auxbusca', [
					'tabla' => 'cuenta',
					'campocod' => 'cta_id',
					'camponombre' => 'nombre',
					'idcampocod' => 'codigoCuentaRedondeo',
					'idcamponombre' => 'nombreCuentaRedondeo',
					'boton_id' => 'botonModalCuentaRedondeo',
					'idModal' => 'modalCuentaRedondeo',
					'criterio' => 'tcta = 2',
					'idAux' => 'crd'
				]);

				Pjax::end();
				Modal::end();

				Pjax::begin(['id' => 'pjaxNombreCuentaRedondeo', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);

				$codigoCuenta= intval(Yii::$app->request->get('codigoCuenta', 0));

				if($codigoCuenta > 0){
					$model->cta_nom_redon= utb::getCampo('cuenta', "cta_id = $codigoCuenta and tcta=1", 'nombre');
					if($model->cta_nom_redon === false) $model->cta_nom_redon= null;
				}

				echo $form->field($model, 'cta_nom_redon', ['options' => ['style' => 'display:inline-block;']])
				->textInput(['id' => 'nombreCuentaRedondeo', 'class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:365px;'])
				->label(false);

				Pjax::end();
				?>

				</td>
			</tr>
			
			<tr>
				<td> <b>Cuenta Act. Deuda:</b> </td>
				<td colspan='5'>
				<?php

				echo $form->field($model, 'cta_id_act',
				['options' => ['style' => 'display:inline-block;']])->textInput(['id' => 'codigoCuentaDeuda', 'style' => 'width:50px;', 'maxlength' => 5, 'onchange' => 'cambiaCuenta($(this).val(), "#pjaxNombreCuentaDeuda");'])->label(false);


				Modal::begin([
					'id' => 'modalCuentaDeuda',
					'size' => 'modal-normal',
					'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cuenta Act. Deuda</h2>',
					'toggleButton' => [
						'label' => '<span class="glyphicon glyphicon-search"></span>',
						'id' => 'botonModalCuentaDeuda',
						'class' => 'bt-buscar'
					],
					'closeButton' => [
					  'label' => '<b>X</b>',
					  'class' => 'btn btn-danger btn-sm pull-right',
					 ],
				]);

				Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);

				echo $this->render('//taux/auxbusca', [
					'tabla' => 'cuenta',
					'campocod' => 'cta_id',
					'camponombre' => 'nombre',
					'idcampocod' => 'codigoCuentaDeuda',
					'idcamponombre' => 'nombreCuentaDeuda',
					'boton_id' => 'botonModalCuentaDeuda',
					'idModal' => 'modalCuentaDeuda',
					'criterio' => 'tcta = 2',
					'idAux' => 'crd'
				]);

				Pjax::end();
				Modal::end();

				Pjax::begin(['id' => 'pjaxNombreCuentaDeuda', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block;']]);

				$codigoCuenta= intval(Yii::$app->request->get('codigoCuenta', 0));

				if($codigoCuenta > 0){
					$model->cta_nom_act= utb::getCampo('cuenta', "cta_id = $codigoCuenta ", 'nombre');
					if($model->cta_nom_act === false) $model->cta_nom_act= null;
				}

				echo $form->field($model, 'cta_nom_act', ['options' => ['style' => 'display:inline-block;']])
				->textInput(['id' => 'nombreCuentaDeuda', 'class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:365px;'])
				->label(false);

				Pjax::end();
				?>

				</td>
			</tr>
			<tr>
				<td><b>A&ntilde;os de prescrip.:</b></td>

				<td colspan='5'><?= $form->field($model, 'prescrip')->textInput(['style' => 'width:50px;', 'maxlength' => 2])->label(false); ?></td>
			</tr>

			<tr>
				<td><?= Html::checkbox(null, $requerido('oficina'), ['value' => 1, 'uncheck' => 0, 'label' => 'Oficina:', 'class' => 'checkHabilitador', 'data-target' => '#oficina', 'data-inverso' => 'false']); ?></td>

				<td colspan='5'><?= $form->field($model, 'oficina')->dropDownList(utb::getAux('sam.muni_oficina', 'ofi_id'), ['id' => 'oficina', 'prompt' => '', 'disabled' => !$requerido('oficina')])->label(false); ?></td>
			</tr>

			<tr>
				<td colspan='6'><?= $form->field($model, 'tipo_descripcion')->textarea(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'max-height:72pox; width:100%;'])->label(false); ?></td>
			</tr>

			<tr>
				<td><b>Modificación:</b></td>
				<td colspan="5"><?= $form->field($model, 'modif')->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:300px;'])->label(false); ?></td>
			</tr>
		</table>

	</div>

	<div style="margin-top:5px;">
	<?php

	if(in_array($consulta, [0, 2, 3, 4])){

		if($consulta === 2) echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger']);
		else echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);

		echo '&nbsp;';
		echo Html::a('Cancelar', ['view', 'id' => $model->trib_id], ['class' => 'btn btn-primary']);
	} else if($model->est === 'A'){

		echo Html::a('Nuevo', ['create'], ['class' => 'btn btn-success']);
		echo '&nbsp;';
		echo Html::a('Modificar', ['update', 'id' => $model->trib_id], ['class' => 'btn btn-primary']);
		echo '&nbsp;';
		echo Html::a('Eliminar', ['delete', 'id' => $model->trib_id], ['class' => 'btn btn-danger']);
	} else if($model->est === 'B'){
		echo Html::a('Activar', ['activar', 'id' => $model->trib_id], ['class' => 'btn btn-primary']);
	}
	?>
	</div>

	<?php
	ActiveForm::end();

	echo $form->errorSummary($model);
	?>

</div>

<?php
Pjax::begin(['id' => 'pjaxTipoTributo', 'enableReplaceState' => false, 'enablePushState' => false]);

$codigoTipoTributo = intval(Yii::$app->request->get('codigoTipoTributo', -1));
$calc_rec= filter_var(Yii::$app->request->get('calcularIntereses', false), FILTER_VALIDATE_BOOLEAN);


if($codigoTipoTributo >= 0){
	?>
	<script>
	$("#nombreHomeBanking").prop("disabled", <?= $requerido('nombre_reduhbank') ? 'false' : 'true'; ?>);
	$("#tipoObjeto").prop("disabled", <?= ($requerido('tobj') ? 'false' : 'true') ?>);

	if(<?= ($model->esSellado || $model->esBoleto ? 'true' : 'false') ?>) $("#tipoObjeto").val(0);

	$("#tipoPago").prop("disabled", <?= $requerido('tpago') ? 'false' : 'true'; ?>);
	$("#texto").prop("disabled", <?= $requerido('texto_id') ? 'false' : 'true'; ?>);
	$("#tributoPrincipal").prop("disabled", <?= $requerido('dj_tribprinc') ? 'false' : 'true'; ?>);
	$("#quitaFacilidad").prop("disabled", <?= $requerido('quitafaci') ? 'false' : 'true'; ?>);
	$("#checkCalculoRecargo").prop("disabled", <?= $requerido('calc_rec') ? 'false' : 'true'; ?>);
	$("#checkGeneraEstadoCuentaCorriente").prop("disabled", <?= $requerido('genestcta') ? 'false' : 'true'; ?>);
	$("#checkCompensa").prop("disabled", <?= $requerido('compensa') ? 'false' : 'true'; ?>);
	$("#checkUsaSubCuenta").prop("disabled", <?= $requerido('uso_subcta') ? 'false' : 'true'; ?>);
	$("#checkInscripcionRequerida").prop("disabled", <?= $requerido('inscrip_req') ? 'false' : 'true'; ?>);
	console.log(<?= $requerido('cta_id_rec') ?>);
	$("#tasaRecargo").prop("disabled", <?= $requerido('calc_rec_tasa', $calc_rec) ? 'false' : 'true'; ?>);
	$("#tasaRecargo2").prop("disabled", <?= $requerido('rec_venc2') ? 'false' : 'true'; ?>);
	$("#codigoCuentaRecargo").prop("disabled", <?= $requerido('cta_id_rec', $calc_rec) ? 'false' : 'true'; ?>);
	$("#botonModalCuentaRecargo").prop("disabled", <?= $requerido('cta_id_rec', $calc_rec) ? 'false' : 'true'; ?>);
	</script>
	<?php
}

echo Html::input('hidden', null, null);
Pjax::end();
?>

<script type="text/javascript">
function cambiaTipo(nuevo){

	var calcularIntereses= $("#checkCalculoRecargo").is(":checked");

	$.pjax.reload({
		container: "#pjaxTipoTributo",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"id": "<?= $model->trib_id ?>",
			"codigoTipoTributo": nuevo,
			"calcularIntereses": calcularIntereses
		}
	});

	$("#pjaxTipoTributo").on("pjax:complete", function(){

		var codigoTipoObjeto= parseInt($("#tipoObjeto").val());

		if(codigoTipoObjeto > 0) cambiaTipoObjeto(codigoTipoObjeto);
		else cambiaTipoObjeto(0);

		$("#pjaxTipoTributo").off("pjax:complete");
	});
}

function cambiaTipoObjeto(nuevo){

	var tipoTributo= parseInt($("#tipoTributo").val());
	nuevo= parseInt(nuevo);

	if(isNaN(tipoTributo) || tipoTributo < 0 || isNaN(nuevo) || nuevo <= 0){

		$("#checkInscripcionIncompatible").prop("disabled", true);
		$("#checkInscripcionIncompatible").prop("checked", false);
		$("#inscripcionIncompatible").prop("disabled", true);
		$("#inscripcionIncompatible").empty();
		return;
	}

	$("#checkInscripcionIncompatible").prop("disabled", false);

	$.pjax.reload({
		container: "#pjaxTipoObjeto",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"codigoTipoTributo": tipoTributo,
			"tipoTributo": tipoTributo,
			"tipoObjeto": nuevo
		}
	});

	$("#pjaxTipoObjeto").on("pjax:complete", function(){

		var habilitar= $("#checkInscripcionIncompatible").is(":checked");

		$("#inscripcionIncompatible").prop("disabled", !habilitar);

		$("#pjaxTipoObjeto").off("pjax:complete");
	});
}

function cambiaCuenta(codigoCuenta, selectorPjax){

	$.pjax.reload({
		container: selectorPjax,
		type: "GET",
		replace: false,
		push: false,
		data: {
			"codigoCuenta": codigoCuenta
		}
	});
}

$(document).ready(function(){

	<?php
	if(in_array($consulta, [0, 3])){
		?>
		cambiaTipo(<?= $model->tipo ?>);
		<?php
	} else{
		?>
		DesactivarFormPost("formTributo");
		<?php
	}
	?>
});


$(".checkHabilitador").click(function(){

	var targets = $(this).data("target").split(",");
	var inverso = $(this).data("inverso");

	for(t in targets){

		if(inverso)
			$(targets[t].trim()).prop("disabled", $(this).is(":checked"));
		else
			$(targets[t].trim()).prop("disabled", !$(this).is(":checked"));
	}


});

function rbRecargoClick(){
	$("#tasaRecargo2").val("0");
	$("#tasaRecargo2").attr("readonly",false);
}

function rbSegunSegVencClick(){
	$("#tasaRecargo2").val("-1");
	$("#tasaRecargo2").attr("readonly",true);
}

</script>
