<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\grid\GridView;

use app\utils\db\utb;

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\BaseUrl;

/* @var $extras array */

//$cuentaPrincipalNombre = isset($extras['cuentaPrincipalNombre']) ? $extras['cuentaPrincipalNombre'] : null;
//$cuentaSelladoNombre = isset($extras['cuentaSelladoNombre']) ? $extras['cuentaSelladoNombre'] : null;
//$cuentaRecargoNombre = isset($extras['cuentaRecargoNombre']) ? $extras['cuentaRecargoNombre'] : null;
//$cuentaMultaNombre = isset($extras['cuentaMultaNombre']) ? $extras['cuentaMultaNombre'] : null;


$iguales = ['cta_id_rec' => 'recargo', 'cta_id_sellado' => 'sellado', 'cta_id_multa' => 'multa'];

?>


<style type="text/css" rel="stylesheet">
.planConfigCuentas .row{
	height:auto;
	min-height:5px;
}

.planConfigCuentas > .row{
	padding : 3px;
	margin-top : 0px;
}

.planConfigCuentas > .row *{
	margin : 0px;
}


</style>

<div class="planConfigCuentas">

	<div class="row">
		<div class="col-xs-12">
			<?php
				echo $form->field($model, 'usarctaper')->checkbox(['label' => 'Usar cuenta de períodos afectados', 'uncheck' => 0, 'value' => 1, 'id' => 'planConfigFormUsarCtaPer',
																	'onclick' => 'if( $(this).is(":checked") ) $("#textoCuentaFinanciacion").removeClass("hidden"); else $("#textoCuentaFinanciacion").addClass("hidden");'
																	]);

			?>
		</div>
	</div>


	<div class="row">

		<div class="col-xs-3">
			<label>Cuenta <span class="<?= $model->usarctaper == 1 ? '' : 'hidden' ?>" id="textoCuentaFinanciacion">financiaci&oacute;n</span></label>
		</div>


		<div class="col-xs-9">

			<div style="width:10%; display:inline-block; margin-left:0px; margin-right:0px;">
			<?php
			Pjax::Begin();

			echo $form->field($model, 'cta_id', ['template' => '{input}'])->textInput([
					'maxlength' => 3,
					'id' 		=> 'cuentaPrincipal',
					'style' 	=> 'margin:0px;',
					'onchange' 	=> '$.pjax.reload({container: "#pjaxNombreCuentaPrincipal", method: "POST", data : {"cuenta" : $(this).val(), "retorno" : "cuentaNombrePrincipal"} } );',
				]);

			Pjax::end();
			?>
			</div>

            <div style="width:70%; max-width:85%; display:inline-block; margin-left : 0px; margin-right : 0px;">
            <?php
            Pjax::begin(['id' => 'pjaxNombreCuentaPrincipal', 'enablePushState' => false, 'enableReplaceState' => false]);

            if(!isset($extras['cuentaNombrePrincipal']))
            	$extras['cuentaNombrePrincipal'] = '';

			if (isset($_POST['cuenta'])) $model->cta_id = $_POST['cuenta'];

			echo AutoComplete::widget([
					'name' => 'cuentaNombrePrincipal',
					'id' => 'cuentaNombrePrincipal',
					'value' => ($model->cta_id == null || $model->cta_id <= 0 ? null : utb::getCampo('cuenta', 'tcta=1 and cta_id = ' . $model->cta_id)),
					'clientOptions' => [
						'source' => BaseUrl::toRoute(['//ctacte/planconfig/sugerenciacuenta','tcta'=>1]),
						'select' => new JsExpression("function(event, ui){codigocuentaCompletar(event, ui,'Principal')}")
					],
					'options' => [
						'class' => 'form-control',
						'style' => 'margin:0px; width:430px;'
					]
				]);

            Pjax::End();
			?>
			</div>
		</div>
	</div>

	<?php
	$ms = 1000;

	foreach( $iguales as $cta => $str )
	{
	?>

	<div class="row">

		<div class="col-xs-3">
			<label>Cuenta <?= $str ?></label>
		</div>


		<div class="col-xs-9">

			<div style="width:10%; display:inline-block; margin-left:0px; margin-right:0px;">
			<?php
			Pjax::Begin();

			echo $form->field($model, $cta, ['template' => '{input}'])->textInput([
					'maxlength' => 3,
					'id' => 'cuenta' . $str,
					'style' => 'margin:0px;',
					'onchange' => '$.pjax.reload({container: "#pjaxNombreCuenta' . $str . '", method: "POST", data : {"cuenta" : $(this).val(), "retorno" : "cuentaNombre' . $str . '"} } );',
				]);

			Pjax::end();
			?>
			</div>

            <div style="width:70%; max-width:85%; display:inline-block; margin-left : 0px; margin-right : 0px;">
            <?php
            Pjax::begin(['id' => 'pjaxNombreCuenta' . $str, 'enablePushState' => false, 'enableReplaceState' => false]);

            if(!isset($extras['cuentaNombre' . $str]))
            	$extras['cuentaNombre' . $str] = '';

            if (isset($_POST['cuenta'])) $model->$cta = $_POST['cuenta'];

			$tipo = 1; 
			if ($str == 'recargo') $tipo = 1;
			if ($str == 'multa') $tipo = 4;

			echo AutoComplete::widget([
					'name' => 'cuentaNombre' . $str,
					'id' => 'cuentaNombre' . $str,
					'value' => ($model->$cta == null || $model->$cta <= 0 ? null : utb::getCampo('cuenta', 'cta_id = ' . $model->$cta . " and tcta = " . $tipo ) ),
					'clientOptions' => [
						'source' => BaseUrl::toRoute(['//ctacte/planconfig/sugerenciacuenta','tcta'=>$tipo]),
						'select' => new JsExpression("function(event, ui){codigocuentaCompletar(event, ui,'$str')}")
					],
					'options' => [
						'class' => 'form-control',
						'style' => 'margin:0px; width:430px;'
					]
				]);

            Pjax::End();
			?>
			</div>
		</div>
	</div>

	<?php } ?>


	<script type="text/javascript">
	//valida las cuentas antes de enviar el formulario al servidor
	$(document).ready(function(){

		var $formulario = $("#<?= $form->id?>");

		$formulario.submit(function(e){

			var nombre = $("#cuentaNombrePrincipal").val();

			if($("#cuentaPrincipal").val() != 0 && !$.trim(nombre))
			{

				var intermedio = $("#planConfigFormUsarCtaPer").is(":checked") ? "de financiacion" : "";

				alert("La cuenta " + intermedio + " no existe.");
				e.preventDefault();
			}

			//validacion de la cuenta principal



			//validacion de otras cuentas
			<?php
				foreach($iguales as $key => $value)
				{
			?>

				nombre = $("#cuentaNombre<?= $value?>").val();

				if($("#cuenta<?= $value?>").val() != 0 && !$.trim(nombre))
				{
					alert("La cuenta <?= $value?> no existe.");
					e.preventDefault();
				}

			<?php
				}
			?>
		});
	});

	</script>

</div>

<script>
function codigocuentaCompletar(event, ui,str)
{
	var cuenta_nom = ui.item.value;

	$.get("<?= BaseUrl::toRoute('//ctacte/planconfig/codigocuenta');?>&nombre=" + cuenta_nom)
	.success(function(data){
		$("#cuenta"+str).val(data);
	});
}
</script>
