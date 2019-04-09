<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use yii\bootstrap\Alert;
use yii\bootstrap\Modal;

use yii\grid\GridView;

use app\models\ctacte\Item;
use app\models\ctacte\ItemVigencia;
use app\controllers\ctacte\ItemvigenciaController;

use app\utils\db\utb;

use yii\jui\AutoComplete;
use yii\web\JsExpression;

$vigencia = new ItemVigencia();
$dataProvider = null;
$cuentaNombre = isset($cuentaNombre) ? $cuentaNombre : '';


$consulta = isset($consulta) ? $consulta : 1;
$tributoSeleccionado= $model->trib_id <= 0 ? Yii::$app->request->get('trib', -1) : $model->trib_id;
$model->trib_id= $tributoSeleccionado;
?>


<style type="text/css">
.form-item-abm div.row{
	height : auto;
	min-height : 17px;
}

.form-item-abm > div.row{
	margin-top : 10px;
}

.form-item-abm > div.row:first-of-type{
	margin-top : 0px;
}

.form-control{
	width : 100%;
}

.form-item-abm .form{
	padding : 15px;
}

.form-item-abm #inputModificacion {
	background-color : #E6E6FA;
	display : inline-block;
	width : auto;
}
</style>


<div class="form-item-abm">


	<?php
	$form = ActiveForm::begin(['id' => 'itemForm', 'method' => 'POST', 'fieldConfig' => ['template' => '{label}{input}']]);
	?>

<div class="form" style="padding:10px;">
	<table border="0">
		<tr>
			<td><label>C&oacute;digo: </label></td>
			<td width="5px"></td>
			<td>
				<?=
					$form->field($model, 'item_id')->textInput([
						'class' => 'form-control solo-lectura',
						'tabIndex' => '-1',
						'id' => 'itemFormItemId',
						'style' => 'width:50px;text-align: center;font-weight: bold',
					])->label(false);
				?>
			</td>
			<td width="10px"></td>
			<td><label>Nombre: </label></td>
			<td width="5px"></td>
			<td><?= $form->field($model, 'nombre')->textInput(['class' => 'form-control', 'id' => 'itemFormNombre', 'maxlength' => 40, 'style' => 'width:280px;'])->label(false); ?></td>
		</tr>

		<tr>
			<td><label>Tipo de ítem:</label></td>
			<td></td>
			<td><?= $form->field($model, 'tipo')->dropDownList($model->getNombreTipoItem(), ['class' => 'form-control', 'prompt' => 'Seleccionar...', 'id' => 'itemFormTipo', 'style' => 'width:140px;'])->label(false); ?></td>
			<td></td>
			<td><label>Tributo:</label></td>
			<td></td>
			<td><?= $form->field($model, 'trib_id')->dropDownList($model->getNombreTributos(), ['class' => 'form-control', 'prompt' => 'Seleccionar...', 'id' => 'itemFormTrib', 'style' => 'width:280px;'])->label(false); ?></td>
		</tr>

		<tr>
			<td colspan="1">
				<label>Cuenta:</label>
			</td>
			<td></td>
			<td colspan="5">
			<?php
			Pjax::begin(['options' => ['style' => 'display:inline-block;']]);

			echo $form->field($model, 'cta_id', ['template' => '{label}{input}', 'options' => ['style' => 'display:inline-block;']])->textInput([
					'maxlength' => 3,
					'id' => 'cuenta',
					'style' => 'margin:0px; width:60px;text-align:center',
					'onchange' => '$.pjax.reload({container: "#pjaxNombreCuenta", method: "POST", data : {"cuenta" : $(this).val(), "retorno" : "cuentaNombre"} } );',
				])->label(false);

			Pjax::end();
			?>

            <?php
            Pjax::begin(['id' => 'pjaxNombreCuenta', 'enablePushState' => false, 'enableReplaceState' => false, 'options' => ['style' => 'display:inline-block;']]);
				echo AutoComplete::widget([
					'name' => 'cuentaNombre',
					'id' => 'cuentaNombre',
					'value' => $extras['cuentaNombre'],
					'clientOptions' => [
						'source' => BaseUrl::toRoute('//ctacte/item/sugerenciaitem'),
						'select' => new JsExpression("function(event, ui){codigoitemCompletar(event, ui)}")
					],
					'options' => [
						'class' => 'form-control',
						'style' => 'margin:0px; width:430px;'
					]
				]);
            Pjax::end();
			?>
			</td>
		</tr>

		<tr>
			<td colspan="7">
				<?=
					$form->field($model, 'obs', ['template' => '{label}<br>{input}'])->textArea([
						'class' => 'form-control',
						'id' => 'itemFormObs',
						'rows' => 5,
						'style' => 'min-width: 567px; width:567px; max-width:567px; min-height: 60px; max-height:150px;', 'maxlength' => 500]);
				?>
			</td>
		</tr>

		<?php if($consulta != 0) { ?>
		<tr>
			<td colspan="7" align="right">
				<label for="inputModificacion">Modificaci&oacute;n:</label>
				<?= Html::textInput(null, utb::getFormatoModif($model->usrmod, $model->fchmod), ['id' => 'inputModificacion', 'class' => 'form-control'] ); ?>
			</td>
		</tr>
		<?php } ?>
	</table>

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
			echo 'DesactivarFormPost("itemForm");';
			echo '</script>';

    	}

		//le saca el atributo disable a los elementos que se tienen que enviar al modificar
		if($consulta == 3)
		{
			echo '<script type="text/javascript">' .
					'$("#itemFormNombre").prop("disabled", false);' .
					'$("#itemFormNombre").prop("readonly", false);' .

					'$("#itemFormTrib").prop("disabled", false);' .
					'$("#itemFormTrib").prop("readonly", false);' .

					'$("#itemFormTipo").prop("disabled", false);' .
					'$("#itemFormTipo").prop("readonly", false);' .

					'$("#itemFormCuenta").prop("disabled", false);' .
					'$("#itemFormCuenta").prop("readonly", false);' .

					'$("#itemFormObs").prop("disabled", false);' .
					'$("#itemFormObs").prop("readonly", false);' .

					'$("#cuenta").prop("disabled", false);' .
					'$("#cuenta").prop("readonly", false);' .



					'</script>';
		}

		//le saca el atributo disable a los elementos que se deben ejecuar y a los que se deben enviar como parametros
		if ($consulta == 3 || $consulta == 2) {

    		echo '<script type="text/javascript">';
    		echo 'document.getElementById("itemFormItemId").disabled = false;';
    		echo '$("#itemFormBoton").prop("disabled", false);';
    		echo '$("#itemFormBoton").prop("readonly", false);';
    		echo '</script>';

    	}
	    ?>


</div>

<div style="margin-top:10px;">

		<?php
		if($consulta == 0 || $consulta == 2 || $consulta == 3) { ?>


        		<?php

        			if ($consulta == 0 || $consulta == 3)
        			{
        				echo Html::submitButton('Grabar', ['class' => 'btn btn-success', 'id' => 'itemFormBoton']);
        			} else {
						echo Html::submitButton('Grabar', ['class' => 'btn btn-danger', 'id' => 'itemFormBoton']);
        			}

        			echo '&nbsp;&nbsp;';
        			echo Html::a('Cancelar', ['index'], ['class' => 'btn btn-primary']);

        		?>


    	<?php

		}
		?>



</div>
	<!-- fin campos del Item -->

	<?php


	ActiveForm::end();

	if($consulta != 1)
		echo $form->errorSummary($model);
	?>

	<!-- vigencias del item seleccionado -->


	<?php


	$dataProviderVigencia = $vigencia->getDP($model->item_id);

	$form = ActiveForm::begin(['id' => 'formVigencias', 'options' => ['data-pjax' => 'true'] ] );

	if($consulta == 3 || $consulta == 2)
	{
	?>
	<div class="form" style="margin-top:15px;">
		<div class="row">
			<div class="col-xs-12">
				<h3 style="display:inline-block"><label>Vigencias</label></h3>

				<?php
					//el item de crear una nueva vigencia se crea solamente cuando se va a modificar un item
					if($consulta == 3)
						echo Html::a('Nueva', null, [
							'class' => 'btn btn-success pull-right',
							'onclick' => '$.pjax.reload({container : "#pjaxModalFormVigencia", method : "GET", replace : false, push : false, data : {"action" : "vigenciaCreate", "item_id" : "' . $model->item_id . '" } } );',
										]
									);
				?>
			</div>

			<div class="col-xs-12">

			<?php

			if($dataProviderVigencia != null)
				echo GridView::widget([
    	    		'dataProvider' => $dataProviderVigencia,
					'rowOptions' => ['class' => 'grilla'],
					'headerRowOptions' => ['class' => 'grilla'],
					'summaryOptions' => ['class' => 'hidden' ],
    	    		'columns' => [

							['attribute' => 'per_desde', 'label' => 'Desde', 'contentOptions' =>  [ 'style' => 'text-align:center', 'width' => '1px']],
							['attribute' => 'per_hasta', 'label' => 'Hasta', 'contentOptions' =>  [ 'style' => 'text-align:center', 'width' => '1px']],
							['attribute' => 'tcalculo', 'label' => 'T. Cálculo', 'contentOptions' =>  [ 'style' => 'text-align:center', 'width' => '1px']],
							['attribute' => 'monto', 'label' => 'Monto', 'contentOptions' =>  [ 'style' => 'text-align:right', 'width' => '1px']],
							['attribute' => 'porc', 'label' => 'Porc', 'contentOptions' =>  [ 'style' => 'text-align:right', 'width' => '1px']],
							['attribute' => 'minimo', 'label' => 'Min', 'contentOptions' =>  [ 'style' => 'text-align:right', 'width' => '1px']],
							['attribute' => 'paramnombre1', 'label' => 'Param1', 'contentOptions' =>  [ 'style' => 'text-align:center', 'width' => '1px']],
							['attribute' => 'paramnombre2', 'label' => 'Param2', 'contentOptions' =>  [ 'style' => 'text-align:center', 'width' => '1px']],
							['attribute' => 'modificacion', 'label' => 'Modif.', 'contentOptions' =>  [ 'style' => 'text-align:left', 'width' => '100px']],

            				['class' => 'yii\grid\ActionColumn',
            					'headerOptions' => ['style' => 'max-width:7%; min-width:7%; width:7%; padding-left:4px; padding-right:4px'],
					            'buttons' => $consulta != 3 ?
					            				//se ocultan los botones si no es modificar la consulta
					            				['view' => function(){return null;}, 'update' => function(){return null;}, 'delete' => function(){return null;}]
					            				:
					            				//sse muestran los botones cuando la consulta es para modificar
					            				[

						        		    	'view' => function(){return  null;},

            									'update' => function($url, $modelV, $key){

        		    							return Html::a(
																'<span class="glyphicon glyphicon-pencil"></span>',
																null,
																[
'onclick' => '$.pjax.reload({container : "#pjaxModalFormVigencia", method : "GET", replace : false, push : false, data : {"action" : "vigenciaUpdate", "item_id" : "' . $modelV['item_id'] . '", "adesde" : "' . $modelV['adesde'] . '", "cdesde" : "' . $modelV['cdesde'] . '" } } );',

																'encode' => false,
																'style' => 'cursor:pointer;',
																]
															);
            									},

		            							'delete' => function($url, $modelV, $key){
            									return Html::a(
																'<span class="glyphicon glyphicon-trash"></span>',
																null,
																[
'onclick' => '$.pjax.reload({container : "#pjaxModalFormVigencia", method : "GET", push : false, replace : false, data : {"action" : "vigenciaDelete", "item_id" : "' . $modelV['item_id'] . '", "adesde" : "' . $modelV['adesde'] . '", "cdesde" : "' . $modelV['cdesde'] . '" } } );',

																'encode' => false,
																'style' => 'cursor:pointer;',
																]
																);


			           							}
            									],
				            ],
			        ],
			    ] );
    		?>
			</div>
		</div>
	</div>



	<?php
	}

	ActiveForm::end();
	
	?>




<script type="text/javascript">
function elegirCuenta(idCuenta, nombre)
{
	$("#itemFormCuenta").val(idCuenta);
	$("#nombreCuenta").val(nombre);
}
</script>

<?php
Pjax::begin(['id' => 'pjaxModalFormVigencia', 'enablePushState' => false, 'enableReplaceState' => false]);
/**
 *
 * MODAL
 *
 *
 */


 $accion = isset($_GET['action']) ? $_GET['action'] : null;

$consulta = 0;
$scenario = 'select';

if($accion != null)
{

 $url = '//ctacte/itemvigencia/create';
 $titulo = 'Nueva vigencia';
 $scenario = 'create';

if($accion != null)
{
	if($accion == 'vigenciaUpdate')
	{
		$url = '//ctacte/itemvigencia/update';
 		$titulo = "Modificar vigencia";
 		$consulta = 3;
 		$scenario = 'update';
	}

	if($accion == 'vigenciaDelete')
	{
		$url = '//ctacte/itemvigencia/delete';
 		$titulo = "Borrar vigencia";
 		$consulta = 2;
 		$scenario = 'delete';
	}
}

$modelVigencia = new ItemVigencia();

$params = [];
$params['ItemVigencia'] = $_GET;
$modelVigencia->scenario = $scenario;

if( $accion != 'vigenciaCreate' && $modelVigencia->load( $params ) )
	$modelVigencia = $modelVigencia->buscarUno();
else if( isset($_GET['item_id']) )
	$modelVigencia->item_id = $_GET['item_id'];

Modal::begin([
	'header' => '<h1>' . $titulo . '</h1>',
	'footer' => null,
	'toggleButton' => ['class' => 'hidden'],
	'id' => 'modalFormVigencia',
	'closeButton' => [
		'label' => '<b>X</b>',
		'class' => 'btn btn-danger btn-sm pull-right',
	],
]);

echo $this->render($url, ['consulta' => $consulta, 'model' => $modelVigencia, 'extras' => ItemvigenciaController::getDefaultExtras(), 'selectorModal' => '#modalFormVigencia']);

Modal::end();
 ?>
 <script type="text/javascript">
 	$("#modalFormVigencia").modal("show");
 </script>
 <?php
 }
 /**
  *
  * fin MODAL
  *
  */?>



  <?php
  Pjax::end();
  ?>

<?php
$resultado = isset($_GET['a']) ? $_GET['a'] : '';
$mensaje = null;

switch($resultado)
{
	case 'create' :
	case 'update' :
	case 'delete' : $mensaje = "Datos grabados."; break;
}

if($mensaje != null)
{
	echo Alert::widget([
 		'options' => ['class' => 'alert-success alert-dissmisible', 'id' => 'alertItemForm'],
		'body' => $mensaje
	]);

	echo "<script>window.setTimeout(function() { $('#alertItemForm').alert('close'); }, 5000)</script>";
}
?>


</div>

<script type="text/javascript">

function codigoitemCompletar(event, ui)
{
	var nombre = ui.item.value;
	
	$.get("<?= BaseUrl::toRoute('//ctacte/item/codigoitem');?>&nombre=" + nombre)
	.success(function(data){
		$("#cuenta").val(data);
	});
}

$(document).ready(function(){
	$("#cuenta").trigger("change");
});

</script>
