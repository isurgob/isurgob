<?php

use yii\helpers\html;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseUrl;

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;

use yii\bootstrap\Alert;


use app\models\ctacte\ItemVigencia;
use app\utils\db\utb;

$consulta = isset($consulta) ? $consulta : 0;
$model = isset($model) ? $model : new ItemVigencia();
$action = BaseUrl::to(['//ctacte/itemvigencia/create', 'selectorModal' => $selectorModal]);

if(isset($consulta))
{
	if($consulta == 2)
		$action = BaseUrl::to(['//ctacte/itemvigencia/delete', 'selectorModal' => $selectorModal]);

	if($consulta == 3)
		$action = BaseUrl::to(['//ctacte/itemvigencia/update', 'selectorModal' => $selectorModal]);
}

$templateParametro = '{label}{input}';

$formulas = utb::getAux('item_tfcalculo');

$comparacion = ['=' => '=', '<' => '<', '>' => '>', '<=' => '<=', '>=' => '>=', '<>' => '<>'];
?>


<script type="text/javascript">

function mostrarCampo(selectorContenedor, selectorElemento){

	$(selectorContenedor).removeClass("hidden");
}

function ocultarCampo(selectorContenedor, selectorElemento){

	$(selectorContenedor).addClass("hidden");
	$(selectorElemento).val('');
}

function cambiarFormula(valor, uri){

	var item_id = $("#itemVigenciaItemId").val();
	var adesde = $("#itemVigenciadesde").val();1
	var cdesde = $("#itemVigenciaCuotadesde").val();

	$.pjax.reload({container : "#pjaxItemVigenciaFormCampos", url : uri, replace : false, push : false, method : "GET", data : {"tcalculo" : valor, "item_id" : item_id, "adesde" : adesde, "cdesde" : cdesde } } );
};
</script>


<style type="text/css" rel="stylesheet">
.item-vigencia-form,
.item-vigencia-form .row{
	height : auto;
	min-height : 17px;
}

.item-vigencia-form .form-control{
	width :100%;
}
</style>


<div class="item-vigencia-form">

<?php

$claseError = $consulta == 0 ? '' : 'has-error';

Pjax::begin(['id' => 'pjaxFormItemVigencia', 'formSelector' => '#formItemVigencia', 'enablePushState' => false, 'enableReplaceState' => false]);

$form = ActiveForm::begin(['errorCssClass' => $claseError, 'id' => 'formItemVigencia', 'action' => $action, 'fieldConfig' => ['template' => '{label}<br>{input}'], 'options' => ['data-pjax' => 'true'] ] );

?>

<?= html::input( 'hidden', 'accion', 1 ); ?>

<table>
	<tr>
		<td width="45px"><label>Ítem:</label></td>
		<td>
			<?=
			 	$form->field($model, 'item_id', ['template' => '{label}{input}'])->textInput([
					'class' => 'form-control',
					'id' => 'itemVigenciaItemId',
					'readonly' => 'readonly',
					'style' => 'width:50px; font-weight: bold; text-align: center',
				])->label( false );
			?>
		</td>
	</tr>

	<tr>
		<td><label>Desde:</label></td>
		<td>
			<?=
				$form->field($model, 'adesde', ['template' => '{label}{input}'])->textInput([
					'id' => 'itemVigenciadesde',
					'style' => 'width:60px; text-align: center',
					'maxlength' => 4,
					'onkeypress' => 'return justNumbers( event )',
				])->label( false );
			?>
		</td>
		<td>
			<?=
				$form->field($model, 'cdesde', ['template' => '{label}{input}'])->textInput([
					'id' => 'itemVigenciaCuotadesde',
					'style' => 'width:40px; text-align: center',
					'maxlength' => 3,
					'onkeypress' => 'return justNumbers( event )',
				])->label( false );
			?>
		</td>
		<td width="30px"></td>
		<td width="40px"><label>Hasta:</label></td>
		<td>
			<?=
				$form->field($model, 'ahasta', ['template' => '{label}{input}'])->textInput([
					'id' => 'itemVigenciahasta',
					'style' => 'width:60px; text-align: center',
					'maxlength' => 4,
					'onkeypress' => 'return justNumbers( event )',
				])->label( false );
			?>
		</td>
		<td>
			<?=
				$form->field($model, 'chasta', ['template' => '{label}{input}'])->textInput([
					'id' => 'itemVigenciaCuotahasta',
					'style' => 'width:40px; text-align: center',
					'maxlength' => 3,
					'onkeypress' => 'return justNumbers( event )',
				])->label( false );
			?>
		</td>
	</tr>

	<tr>
		<td colspan="7">
			<label for="itemVigenciaFormula">Fórmula de Cálculo</label>
			<?php

				$uri = '//ctacte/itemvigencia/create';

				if($consulta == 3)
					$uri = '//ctacte/itemvigencia/delete';
				else if($consulta == 2)
					$uri = '//ctacte/itemvigencia/update';

				$seleccion = $model->tcalculo != null ? $model->tcalculo : 0;

				echo Html::DropDownList('temVigencia[tcalculo]', $seleccion, $formulas, [
						'id' => 'itemVigenciaFormula',
						'class' => 'form-control',
						'onchange' => 'cambiarFormula($(this).val(), "'. BaseUrl::toRoute($uri) .'");'
					]);
			?>
		</td>
	</tr>

</table>

<?php
Pjax::begin(['id' => 'pjaxItemVigenciaFormCampos', 'enablePushState' => false, 'enableReplaceState' => false]);

$tcalculo = Yii::$app->request->get('tcalculo', 0);

if($tcalculo == 0 && $model->tcalculo != null)
	$tcalculo = $model->tcalculo;

$item_id = Yii::$app->request->get('item_id', 0);
$adesde = Yii::$app->request->get('adesde', 0);
$cdesde = Yii::$app->request->get('cdesde', 0);

?>

<div class="row" style="margin-top:10px;">

	<div class="col-xs-3">

				<div class="row itemVigenciaMinimo">

					<div class="col-xs-12">

							<?=
								$form->field($model, 'minimo', ['template' => $templateParametro])->textInput([
									'id' => 'itemVigenciaMinimo',
									'class' => 'form-control',
									'style' => 'text-align:right',
									'onkeypress' => 'return justDecimalAndMenos( event , $(this).val() )',
									'maxlength' => 7,
								])->label(  );
							?>

					</div>
				</div>

			<?php
			if($tcalculo != null && array_key_exists($tcalculo, $formulas) && stripos( $formulas[$tcalculo] , 'monto') !== false)
			{
			?>

				<div class="row itemVigenciaMonto">
					<div class="col-xs-12">

						<?=
							$form->field($model, 'monto', ['template' => $templateParametro])->textInput([
								'id' => 'itemVigenciaMonto',
								'class' => 'form-control',
								'style' => 'text-align:right',
								'onkeypress' => 'return justDecimalAndMenos( event , $(this).val() )',
								'maxlength' => 7,
							]);
						?>

					</div>
				</div>

			<?php
			}

			if($tcalculo != null && array_key_exists($tcalculo, $formulas) && strpos(strtolower( $formulas[$tcalculo] ), 'porc') !== false)
			{
			?>

				<div class="row itemVigenciaPorcentaje">
					<div class="col-xs-12">
						<?=
							$form->field($model, 'porc', ['template' => $templateParametro])->textInput([
								'id' => 'itemVigenciaPorcentaje',
								'class' => 'form-control',
								'style' => 'text-align:right',
								'onkeypress' => 'return justDecimalAndMenos( event , $(this).val() )',
								'maxlength' => 7,
							]);
						?>
					</div>
				</div>

			<?php
			}
			?>

	</div>

	<!-- parametros -->
	<div class="col-xs-5">


			<?php
			if($tcalculo != null && array_key_exists($tcalculo, $formulas) && (stripos( $formulas[$tcalculo] , 'param1') !== false || stripos( $formulas[$tcalculo] , 'tabla') !== false))
			{
			?>
			<!-- Parametro 1 -->
			<div class="row">
				<div class="col-xs-12">

					<div class="row itemVigenciaParam1">
						<div class="col-xs-7">

							<?=
								$form->field($model, 'paramnombre1', ['template' => $templateParametro])->textInput([
									'id' => 'itemVigenciaParam1',
									'class' => 'form-control',
									'maxlength' => 15,
								]);
							?>
						</div>

						<div class="col-xs-3 <?= stripos( $formulas[$tcalculo], 'tabla' ) !== false ? '' : 'hidden' ?>" style="padding-left:0px;">
							<br>
							<?=
								$form->field($model, 'paramcomp1', ['template' => '{input}'])->dropDownList($comparacion, [	'prompt' => '', 'id' => 'itemVigenciaComp1']);
							?>
						</div>
					</div>

				</div>

			</div>
			<?php
			}
			?>

			<?php
			if($tcalculo != null && array_key_exists($tcalculo, $formulas) && (stripos($formulas[$tcalculo] , 'param2') !== false || stripos( $formulas[$tcalculo] , 'tabla') !== false))
			{
			?>
			<!-- Parametro 2 -->
			<div class="row">
				<div class="col-xs-12">

					<div class="row itemVigenciaParam2">
						<div class="col-xs-7">

							<?=
								$form->field($model, 'paramnombre2', ['template' => $templateParametro])->textInput([
									'id' => 'itemVigenciaParam2',
									'class' => 'form-control',
									'maxlength' => 15
								]);
							?>
						</div>

						<div class="col-xs-3 <?= stripos( $formulas[$tcalculo], 'tabla' ) !== false ? '' : 'hidden' ?>" style="padding-left:0px;">
							<br>
							<?php
							echo $form->field($model, 'paramcomp2', ['template' => '{input}'])->dropDownList($comparacion, ['prompt' => '', 'id' => 'itemVigenciaComp2']);
							?>
						</div>
					</div>

				</div>
			</div>
			<?php
			}
			?>

			<?php
			if($tcalculo != null && array_key_exists($tcalculo, $formulas) && (stripos( $formulas[$tcalculo] , 'param3') !== false || stripos( $formulas[$tcalculo] , 'tabla') !== false))
			{
			?>
			<!-- Parametro 3 -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row itemVigenciaParam3">
						<div class="col-xs-7">

							<?=
								$form->field($model, 'paramnombre3', ['template' => $templateParametro])->textInput([
									'id' => 'itemVigenciaParam3',
									'class' => 'form-control',
									'maxlength' => 15,
									'onkeypress' => 'return justNumbers( event )',
								]);
							?>

						</div>

						<div class="col-xs-3 <?= stripos( $formulas[$tcalculo], 'tabla' ) !== false ? '' : 'hidden' ?>" style="padding-left:0px;">
							<br>
							<?php
							echo $form->field($model, 'paramcomp3', ['template' => '{input}'])->dropDownList($comparacion, ['prompt' => '', 'id' => 'itemVigenciaComp3']);
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
			?>

			<?php
			if($tcalculo != null && array_key_exists($tcalculo, $formulas) && (stripos( $formulas[$tcalculo] , 'param4') !== false || stripos( $formulas[$tcalculo] , 'tabla') !== false))
			{
			?>
			<!-- Parametro 4 -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row itemVigenciaParam4">
						<div class="col-xs-7">

							<?=
								$form->field($model, 'paramnombre4', ['template' => $templateParametro ])->textInput([
									'id' => 'itemVigenciaParam4',
									'class' => 'form-control',
									'maxlength' => 15,
									'onkeypress' => 'return justNumbers( event )',
								]);
							?>
						</div>

						<div class="col-xs-3 <?= stripos( $formulas[$tcalculo], 'tabla' ) !== false ? '' : 'hidden' ?>" style="padding-left:0px;">
							<br>
							<?php
							echo $form->field($model, 'paramcomp4', ['template' => '{input}'])->dropDownList($comparacion, ['prompt' => '', 'id' => 'itemVigenciaComp4']);
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
			?>

	</div>
	<!-- fin parametros -->

</div>


	<?php

	Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false, 'clientOptions' => ['type' => 'POST']]);
	if(stripos( $formulas[$tcalculo] , 'tabla') !== false && $consulta == 3)
		echo $this->render('//ctacte/itemvigencia/_asoc', ['model' => $model, 'extras' => $extras, 'item_id' => $item_id, 'adesde' => $adesde, 'cdesde' => $cdesde, 'form' => $form, 'consulta' => $consulta]);
	Pjax::end();



	?>

<?php
Pjax::end();
?>

<?php
if($consulta != 0)
{
?>
<div class="row">
	<div class="col-xs-12">
		<label>Modificaci&oacute;n: <?php
					if($model->usrmod != null && $model->fchmod != null)
						echo Html::textInput(null, utb::getFormatoModif($model->usrmod, $model->fchmod), ['style' => 'background-color:#E6E6FA; width : auto;', 'class' => 'form-control']); ?></label>
	</div>
</div>
<?php
}
?>

<div class="row">
	<div class="col-xs-12">
	<?php

	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 */
	 	if ($consulta == 1 || $consulta == 2 || $consulta == 3)
    	{
    		echo '<script type="text/javascript">';
			echo 'DesactivarFormPost("formItemVigencia");';
			echo '</script>';


    	}

    	if($consulta == 0 or $consulta == 2 or $consulta == 3) { ?>

	 		<div class="form-group">

	    		<?= Html::button('Grabar', ['class' => ( $consulta == 2 ? 'btn btn-danger' : 'btn btn-success' ), 'id' => 'itemVigenciaFormBoton', 'onclick' => 'grabar()']); ?>
	    		&nbsp;&nbsp;
	    		<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => $selectorModal]); ?>

		   	</div>

	    <?php

		}


		//le saca el atributo disable y readonly a los elementos que se tienen que enviar al modificar
		if($consulta == 3)
		{
		?>
			<script type="text/javascript">
			$("#itemVigenciaFormula").removeAttr("disabled");
			$("#itemVigenciaFormula").removeAttr("readonly");

			$("#itemVigenciahasta").removeAttr("readonly");
			$("#itemVigenciaCuotahasta").removeAttr("readonly");

			$("#itemVigenciaMinimo").removeAttr("readonly");

			$("#itemVigenciaMonto").removeAttr("readonly");

			$("#itemVigenciaPorcentaje").removeAttr("readonly");

			$("#itemVigenciaParam1").removeAttr("readonly");
			$("#itemVigenciaParam2").removeAttr("readonly");
			$("#itemVigenciaParam3").removeAttr("readonly");
			$("#itemVigenciaParam4").removeAttr("readonly");

			$("#itemVigenciaComp1").removeAttr("readonly");
			$("#itemVigenciaComp2").removeAttr("readonly");
			$("#itemVigenciaComp3").removeAttr("readonly");
			$("#itemVigenciaComp4").removeAttr("readonly");

			$("#itemVigenciaComp1").removeAttr("disabled");
			$("#itemVigenciaComp2").removeAttr("disabled");
			$("#itemVigenciaComp3").removeAttr("disabled");
			$("#itemVigenciaComp4").removeAttr("disabled");

			$("#montoCompNuevo").removeAttr("readonly");
			$("#paramCompNuevo1").removeAttr("readonly");
			$("#paramCompNuevo2").removeAttr("readonly");
			$("#paramCompNuevo3").removeAttr("readonly");
			$("#paramCompNuevo4").removeAttr("readonly");
			</script>
		<?php
		}
		?>



	</div>
</div>

<?php

ActiveForm::end();

if($consulta != 1)
	echo $form->errorSummary($model);

Pjax::end();
?>

</div><!-- fin .item-vigencia-form -->

<script>
function grabar(){

	var datos= obtenerCampos();

	$.pjax.reload({
		container: "#pjaxFormItemVigencia",
		url: "<?= $action; ?>",
		type: "POST",
		replace: false,
		push: false,
		data: {
			"ItemVigencia" : datos
		}
	});

	$("#pjaxFormItemVigencia").on("pjax:complete", function(){

		console.log("terminada la consulta");
	});
}

function obtenerCampos(){

	var ret= {};

	ret.item_id= $("#itemVigenciaItemId").val();
	ret.adesde= $("#itemVigenciadesde").val();
	ret.cdesde= $("#itemVigenciaCuotadesde").val();
	ret.ahasta= $("#itemVigenciahasta").val();
	ret.chasta= $("#itemVigenciaCuotahasta").val();
	ret.tcalculo= $("#itemVigenciaFormula").val();
	ret.minimo= $("#itemVigenciaMinimo").val();
	ret.monto= $("#itemVigenciaMonto").val();;
	ret.porc= $("#itemVigenciaPorcentaje").val();

	ret.paramnombre1= $("#itemVigenciaParam1").val();
	ret.paramnombre2= $("#itemVigenciaParam2").val();
	ret.paramnombre3= $("#itemVigenciaParam3").val();
	ret.paramnombre4= $("#itemVigenciaParam4").val();
	ret.paramcomp1= $("#itemVigenciaComp1").val();
	ret.paramcomp2= $("#itemVigenciaComp2").val();
	ret.paramcomp3= $("#itemVigenciaComp3").val();
	ret.paramcomp4= $("#itemVigenciaComp4").val();

	return ret;
}
</script>
