<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

use app\utils\db\utb;

$titulo = '';

switch($consulta){

	case 0: $titulo = 'Nuevo'; break;
	case 1: $titulo = 'Consulta'; break;
	case 2: $titulo = 'Eliminar'; break;
	case 3: $titulo = 'Modificar'; break;
}

$this->params['breadcrumbs'][] = ['label' => 'listado', 'url' => ['//site/auxedit', 't' => 49]];
$this->params['breadcrumbs'][] = ['label' => 'Aforo ' . $model->aforo_id, 'url' => ['aforo', 'id' => $model->aforo_id, 'c' => 1]];
$this->params['breadcrumbs'][] = $titulo;

?>

<div class="rodado-aforo">

	<h1>Edici&oacute;n Manual de Aforos</h1>
	<div class="separador-horizontal"></div>

	<?php
	if(isset($mensaje) && $mensaje !== null){

		echo Alert::widget([

			'id' => 'alertMensaje',
			'body' => $mensaje,
			'options' => ['class' => 'alert alert-success']
		]);

		?>
		<script type="text/javascript">
		setTimeout(function(){

			$("#alertMensaje").addClass("hidden");
		}, 5000);
		</script>
		<?php
	}
	?>

	<!-- INICIO Div Filtro Búsqueda -->
	<div class="form-panel" style=>

		<div class="pull-left">

			<label>Año:</label>
			<?=
				Html::input( 'text', 'txAnio', $model->anioSeleccionado, [
					'id'	=> 'form_aforo_txAnio',
					'class'	=> 'form-control' . ( !in_array( $consulta, [ 1 ] ) ? ' solo-lectura' : '' ),
					'style'	=> 'width:60px; text-align: center',
					'maxlength'		=> '4',
					'onkeypress'	=> 'return justNumbers( event )',

				]);
			?>

			&nbsp;&nbsp;&nbsp;&nbsp;

		</div>

		<div class="pull-left">
			<?php Pjax::begin(['id' => 'form_aforo_pjaxCambiaAnio', 'enablePushState' => false, 'enableReplaceState' => false ]); ?>


				<?=
					Html::button( 'Buscar', [
						'class'		=> 'btn btn-primary' . ( $consulta == 1 ? '' : ' hidden' ),
						'onclick'	=> 'f_cambiaAnio()',
					]);
				?>

			<?php Pjax::end(); ?>
		</div>

		<div class="clearfix"></div>
	</div>
	<!-- FIN Div Filtro Búsqueda -->
	<?php
	$form = ActiveForm::begin(['id' => 'formAforo', 'fieldConfig' => ['template' => '{input}'], 'validateOnSubmit' => false]);
	?>

	<?=
		Html::activeInput( 'hidden', $model, 'anioSeleccionado' );
	?>

	<div class="form-panel" style="padding:5px;">

		<table border="0">
			<tr>
				<td><b>ID:</b></td>
				<td width="5px"></td>
				<td colspan="13"><?= $form->field($model, 'aforo_id')->textInput(['class' => 'form-control', 'style' => 'width:80px;', 'onchange' => 'cambiaCodigo($(this).val());', 'maxlength' => 8, 'tabindex' => 1])->label(false); ?></td>
			</tr>

			<tr>
				<td><b>F&aacute;brica:</b></td>
				<td></td>
				<td><?= $form->field($model, 'fabr')->textInput(['style' => 'width:50px;', 'tabindex' => 2, 'disabled' => strlen($model->aforo_id) !== 8, 'id' => 'codigoFabrica', 'maxlength' => 3])->label(false); ?></td>
				<td width="10px"></td>
				<td><b>Origen:</b></td>
				<td></td>
				<td><?= $form->field($model, 'origen')->dropDownList(utb::getAux('rodado_torigen'), ['style' => 'width:100px;', 'tabindex' => 3])->label(false); ?></td>
				<td width="10px"></td>
				<td><b>Tipo Vehic.:</b></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'tvehic')->textInput(['style' => 'width:50px;', 'tabindex' => 4, 'maxlength' => 1])->label(false); ?></td>
			</tr>

			<tr>
				<td><b>Marca:</b></td>
				<td></td>
				<td colspan="9">
					<?= $form->field($model, 'marca', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:50px;', 'tabindex' => 5, 'disabled' => true, 'id' => 'codigoMarca', 'maxlength' => 3])->label(false); ?>
					-
					<?= $form->field($model, 'marca_nom', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:280px;', 'tabindex' => 6, 'id' => 'nombreMarca', 'maxlength' => 25])->label(false); ?>
				</td>
				<td width="10px"></td>
			</tr>
			<tr>
				<td><b>Tipo:</b></td>
				<td></td>
				<td colspan="9">
					<?= $form->field($model, 'tipo', ['options' => ['style' => 'display:inline-block;']])
					->textInput(['style' => 'width:50px;', 'tabindex' => 7, 'disabled' => strlen($model->aforo_id) !== 7, 'id' => 'codigoTipo', 'onchange' => 'cambiaTipo($(this).val());', 'maxlength' => 2])
					->label(false); ?>
					-
					<?= $form->field($model, 'tipo_nom', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:280px;', 'tabindex' => 8, 'id' => 'nombreTipo', 'maxlength' => 35])->label(false); ?>
				</td>
				<td width="10px"></td>
			</tr>
			<tr>
				<td><b>Modelo:</b></td>
				<td></td>
				<td colspan="9">
					<?= $form->field($model, 'modelo', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:50px;', 'tabindex' => 9, 'disabled' => true, 'id' => 'codigoModelo', 'maxlength' => 3])->label(false); ?>
					-
					<?= $form->field($model, 'modelo_nom', ['options' => ['style' => 'display:inline-block;']])->textInput(['style' => 'width:280px;', 'tabindex' => 10, 'maxlength' => 50])->label(false); ?>
				</td>
			</tr>
		</table>
	</div>
	<div class="form-panel" style="padding:5px; margin-top:5px;">
		<?php
		$actual = intval(date('Y')) + 1;
		$indice = 0;
		$tabindex = 11;
		$insertados = 0;
		?>

		<h3><label>Valores en Año: <?= $model->anioSeleccionado ?></label></h3>

		<table width="100%" border="0">

			<?php
			foreach( $model->valores as $valor ){

				if($insertados == 0) echo '<tr>';
				?>

					<td><b>
						<?=
							$form->field( $model, "valores[$indice][anio]" )->hiddenInput([
								'style' => 'width:70px; text-align:right;',
								'maxlength' => 10, 'tabindex' => $tabindex + $indice
							])->label(false);
						?>

						<?= $valor['anio'] ?>:
					</b></td>
					<td width="5px"></td>
					<td>
						<?=
							$form->field( $model, "valores[$indice][valor]" )->textInput([
								'style' => 'width:70px; text-align:right;',
								'maxlength' => 10, 'tabindex' => $tabindex + $indice
							])->label(false);
						?>
					</td>


					<?= $insertados < 4 ? '<td width="30px"></td>' : '<td width="50px"></td>' ?>
				<?php
				$insertados++;
				$indice++;

				if( $insertados == 5 ){
					 echo '</tr>';
					 $insertados = 0;
				}
			}
			?>
		</table>
	</div>

	<div style="margin-top:5px;">
	<?php
	if(in_array($consulta, [0, 2, 3])){


		 echo ($consulta === 2 ? Html::button('Eliminar', ['class' => 'btn btn-danger', 'onclick' => 'eliminar(false);']) : Html::submitButton('Grabar', ['class' => 'btn btn-success']));
		 echo '&nbsp;&nbsp;';
		 if($consulta == 0)
		 	echo Html::a('Cancelar', ['//site/auxedit', 't' => 49], ['class' => 'btn btn-primary']);
		 else echo Html::a('Cancelar', ['aforo', 'id' => $model->aforo_id, 'c' => 1], ['class' => 'btn btn-primary']);

	} else if($consulta === 1) {

		echo Html::a('Nuevo', ['aforo', 'c' => 0], ['class' => 'btn btn-success']);
		echo '&nbsp;&nbsp;';
		echo Html::a('Modificar', ['aforo', 'id' => $model->aforo_id, 'c' => 3, 'anio' => $model->anioSeleccionado], ['class' => 'btn btn-primary']);
		echo '&nbsp;&nbsp;';
		echo Html::a('Eliminar', ['aforo', 'id' => $model->aforo_id, 'c' => 2], ['class' => 'btn btn-danger']);
		echo '&nbsp;&nbsp;';
		echo Html::a('Listado', ['//site/auxedit', 't' => 49], ['class' => 'btn btn-primary']);
		echo '&nbsp;&nbsp;';
		echo Html::a('Imprimir', ['imprimiraforo', 'id' => $model->aforo_id], ['target'=>'_black', 'class' => 'btn btn-success']);
	}
	?>

	<?php
	if($consulta === 2 && $model->existenRodados){

		Modal::begin([
			'id' => 'modalConfirmacion',
			'header' => '<h1>Eliminar aforo</h1>',
			'toggleButton' => false,
			'closeButton' => [
				'label' => '&times;',
				'class' => 'btn btn-danger btn-sm pull-right',
				'onclick' => '$("#modalConfirmacion").modal("hide");'
			]
		]);
		?>
		<p><b>Existen rodados vínculados con el aforo, ¿Desea proceder de todas formas?</b></p>

		<div class="clearfix">
			<span class="pull-right">
				<?= Html::button('Si', ['class' => 'btn btn-danger', 'onclick' => 'eliminar(true);']); ?>
				&nbsp;
				&nbsp;
				<?= Html::button('No', ['class' => 'btn btn-primary', 'onclick' => '$("#modalConfirmacion").modal("hide")M']) ?>
			</span>
		</div>
		<?php
		Modal::end();
	}
	?>
	</div>

	<?php
	ActiveForm::end();

	echo $form->errorSummary($model);
	?>
</div>

<?php
Pjax::begin(['id' => 'pjaxCodigo', 'enableReplaceState' => false, 'enablePushState' => false]);

$codigo = trim(Yii::$app->request->get('codigoAforo', ''));

?>
<script type="text/javascript">
<?php

if(strlen($codigo) == 7){
	//solamente se carga la marca

	$marca = substr($codigo, 3, 2);
	$nombreMarca = utb::getCampo('rodado_aforo', "marca = '$marca'", "Coalesce(Max(marca_nom), '')");

	?>
	$("#nombreMarca").val("<?= $nombreMarca ?>");;
	<?php

} else if(strlen($codigo) == 8){

	//se carga la marca y el tipo
	$marca = substr($codigo, 0, 3);
	$tipo = substr($codigo, 3, 2);

	$datos = utb::getVariosCampos('rodado_aforo', "marca = '$marca' And tipo = '$tipo'", "Coalesce(Max(marca_nom), '') As marca_nom, Coalesce(Max(tipo_nom), '') As tipo_nom");

	if($datos !== false){

		?>
		$("#nombreMarca").val("<?= $datos['marca_nom'] ?>");;
		$("#nombreTipo").val("<?= $datos['tipo_nom'] ?>");
		<?php

	}
}

?>
</script>
<?php

echo Html::input('hidden', null, null);
Pjax::end();


Pjax::begin(['id' => 'pjaxTipo', 'enableReplaceState' => false, 'enablePushState' => false]);

$codigoTipo = Yii::$app->request->get('tipo', '');

?>
<script type="text/javascript">
<?php

if(strlen($codigoTipo) > 0){

	$dato = utb::getCampo('rodado_aforo', "tipo = '$codigoTipo'", 'tipo_nom');

	if($dato !== false){0
	?>
	$("#nombreTipo").val("<?= $dato ?>");
	$("#nombreTipo").setFocus();
	<?php
	}
}
?>
</script>
<?php

Pjax::end();
?>

<script type="text/javascript">
function cambiaCodigo(nuevo){


	if(nuevo.length == 7){

		$("#codigoTipo").prop("disabled", false);
		$("#nombreTipo").val("");
		$("#codigoFabrica").prop("disabled", true);

		$("#codigoFabrica").val(nuevo.substr(0, 3));
		$("#codigoMarca").val(nuevo.substr(3, 2));
		$("#codigoModelo").val(nuevo.substr(5, 2));
		$("#codigoTipo").val("");

	} else if(nuevo.length == 8){

		$("#codigoTipo").prop("disabled", true);
		$("#codigoFabrica").prop("disabled", false);

		$("#codigoMarca").val(nuevo.substr(0, 3));
		$("#codigoTipo").val(nuevo.substr(3, 2));
		$("#codigoModelo").val(nuevo.substr(5, 3));
		$("#codigoFabrica").val("");

	} else {

		$("#codigoFabrica").val("");
		$("#codigoMarca").val("");
		$("#codigoModelo").val("");
		$("#codigoTipo").val("");

		$("#nombreMarca").val("");
		$("#nombreTipo").val("");
	}


	$.pjax.reload({
		container : "#pjaxCodigo",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"codigoAforo" : nuevo
		}
	});
}

function cambiaTipo(nuevo){

	$.pjax.reload({
		container : "#pjaxTipo",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"tipo" : nuevo
		}

	});
}

function eliminar(confirmado){

	<?php
	if($model->existenRodados){
		?>

		if(!confirmado)
			$("#modalConfirmacion").modal("show");
		else
			$("#formAforo").submit();


		<?php
	} else {
		?>
		$("#formAforo").submit();
		<?php
	}
	?>
}

$(document).ready(function(){

	<?php
	if($consulta !== 0 && $consulta !== 3){
		?>
		DesactivarFormPost('formAforo');
		<?php
	}
	?>

});

function f_cambiaAnio(){

	var anio = $( "#form_aforo_txAnio" ).val();

	$.pjax.reload({
		container	: "#form_aforo_pjaxCambiaAnio",
		type		: "GET",
		replace		: false,
		push		: false,
		data:{
			"anio"	: anio,
		},
	});
}
</script>
