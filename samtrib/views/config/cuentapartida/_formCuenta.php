<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use yii\web\Session;
use yii\bootstrap\Alert;
use app\utils\db\Fecha;
use yii\data\ArrayDataProvider;

/**
 * Forma que se encarga de mostrar un buscador para "Cuentas".
 *
 * Recibo:
 *
 * 		+ $model => Modelo de "Partida Presupuestaria"
 */

$title = 'Cuentas';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;
?>

<style>

.div_grilla
{
	padding: 5px 0px 5px 0px;
}
</style>

<div class="cuentaingreso-form">

<h1><?= Html::encode($title) ?></h1>

<!-- INICIO DIV Panel -->
<div class="form-panel" style="padding-right:5px">
<table>
	<tr>
		<td><h3><label for="cuentaingreso_txNombre">Filtro:</label></h3></td>
		<td width="20px"></td>
		<td><label for="subgrupo">Nombre:</label></td>
		<td>
			<?= Html::input('text','txNombre','',[
					'id' => 'cuentaingreso_txNombre',
					'class' => 'form-control',
					'style' => 'width:200px;text-transform:uppercase;',
				]);
			?>
		</td>
		<td width="20px"></td>
		<td align="right" width="100px">
			<?= Html::button('Buscar',[
					'id' => 'cuentaingreso_btBuscar',
					'class' => 'btn btn-primary',
					'onclick' => 'btBuscarClick()',
				]);
			?>
		</td>
	</tr>
</table>

<!-- INICIO DIV Cuentas -->
<div id="cuentas" >

<?php

	//INICIO Pjax para grilla de partidas presupuestarias
	Pjax::begin(['id' => 'PjaxGrillaCuentaIngreso', 'enablePushState' => false, 'enableReplaceState' => false]);

		$nombre = Yii::$app->request->get('g_nombre',0);
		$cargar = Yii::$app->request->get('g_cargar',0);

		?>

		<table width="100%">
			<tr>
				<td align="left">
					<h3><label for="cuentas">Cuentas</label></h3>
				</td>
				<td align="right">
				<?php
					if (utb::getExisteProceso(3041))
						echo Html::button('Nuevo',[
								'id' => 'viewpartida_btAgregar',
								'class' => 'btn btn-success',
								'onclick' => 'nuevaCuenta()',
							]);
				?>
				</td>
			</tr>
		</table>

		<!-- INICIO Grilla Partida Presupuestarias -->
		<div class="div_grilla">

		<?php


		//Genero el dataProvider para la grilla
		$dataProvider = new ArrayDataProvider([
							'allModels' => $model->getCuentasIngreso( $nombre ),
							'key' => 'cta_id',
							'pagination' => false,
							'sort' => [
								'attributes' => [
									'cta_id',
									'formato_aux',
									'nombre',
									'part_nom',
									'tcta_nom',
								]
							]
						]);

		Pjax::begin(['id' => 'PjaxGrilla']);

			echo GridView::widget([
				'id' => 'cuentaingreso_GrillaCuentaIngreso',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProvider,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'formato_aux','label' => 'Formato', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'part_nom','label' => 'Partida Nom.', 'contentOptions'=>['style'=>'text-align:left','width'=>'250px']],
						['attribute'=>'cta_id','label' => 'Nº Cuenta', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'250px']],
						['attribute'=>'tcta_nom','label' => 'Tipo', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'est','label' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						[
							'class' => 'yii\grid\ActionColumn',
							'contentOptions'=>['style'=>'width:40px'],
							'template' =>(utb::getExisteProceso(3041) ? '{update}{delete}' : ''),
							'buttons'=>[

								'update' => function($url, $model, $key)
											{
												if ($model['est'] != 'B')
													return Html::button('<span class="glyphicon glyphicon-pencil" style="margin: 0px 1px"></span>', [
																'class'=>'bt-buscar-label',
																'style'=>'color:#337ab7',
																'onclick' => 'editarCuenta('.$model["cta_id"].')',
																]);
											},

								'delete' => function($url, $model, $key)
											{
												if ($model['est'] != 'B')
													return Html::button('<span class="glyphicon glyphicon-trash" style="margin: 0px 1px"></span>', [
																'class'=>'bt-buscar-label','style'=>'color:#337ab7',
																'onclick' => 'eliminarCuenta('.$model["cta_id"].')',
																]);
											},

							]
						]
		        	],
			]);

		Pjax::end();


		?>

		</div>
		<!-- FIN Grilla Partida Presupuestarias -->

	<?php

	Pjax::end();
	//FIN Pjax para grilla de partidas presupuestarias
?>

</div>
<!-- FIN DIV Cuentas -->

</div>
<!-- FIN DIV Panel -->


<!-- INICIO Mensajes de error -->
<div style="margin-right: 15px">

	<?php
	Pjax::begin(['id'=>'PjaxErrorCuentaIngreso']);

		$mensaje = '';

		$mensaje = Yii::$app->request->post('mensaje','');
		$m = Yii::$app->request->post('m',$m);

		if (isset($alert) && $alert != '')
		{
			$mensaje = $alert;
			$alert = '';
		}

		if( $mensaje != '' )
		{
	    	Alert::begin([
	    		'id' => 'AlertaMensajeCuentaIngreso',
				'options' => [
	        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
	        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
	    		],
			]);

			echo $mensaje;

			Alert::end();

			echo "<script>window.setTimeout(function() { $('#AlertaMensajeCuentaIngreso').alert('close'); }, 5000)</script>";
		 }

	 Pjax::end();

	?>
</div>
<!-- FIN Mensajes de error -->

<!-- INICIO Mensajes "Error Summary" -->
<?php

$form = ActiveForm::begin();

echo $form->errorSummary( $model, ['id' => 'formcuenta_errorSummary', 'style' => 'display:none;margin-right: 15px; margin-top: 8px']);

ActiveForm::end();

?>
<!-- FIN Mensajes "Error Summary" -->

</div>

<?php

//INICIO Formulario Modal "Nueva Cuenta"
	Modal::begin([
			'id' => 'ModalNuevaCuenta',
			'size' => 'modal-normal',
			'header' => '<h4><b>Nueva Cuenta</b></h4>',
			'closeButton' => [
				'label' => '<b>X</b>',
				'class' => 'btn btn-danger btn-sm pull-right',
				'id' => 'btCancelarModalNuevaCuenta'
				],
		]);

		echo $this->render('editaCuenta',['model' => $model, 'consulta' => 0, 'cuenta' => 1]);

	Modal::end();
	//FIN Formulario Modal "Nueva Cuenta"

	//INICIO Formulario Modal "Editar Cuenta"
	Modal::begin([
			'id' => 'ModalEditaCuenta',
			'size' => 'modal-normal',
			'header' => '<h4><b>Modificar Cuenta</b></h4>',
			'closeButton' => [
				'label' => '<b>X</b>',
				'class' => 'btn btn-danger btn-sm pull-right',
				'id' => 'btCancelarModalEditaCuenta'
				],
		]);

		echo $this->render('editaCuenta',['model' => $model, 'consulta' => 3, 'cuenta' => 1]);

	Modal::end();
	//FIN Formulario Modal "Editar Cuenta"

	//INICIO Formulario Modal "Eliminar Cuenta"
	Modal::begin([
			'id' => 'ModalEliminarCuenta',
			'size' => 'modal-sm',
			'header' => '<h4><b>Eliminar Cuenta</b></h4>',
			'closeButton' => [
				'label' => '<b>X</b>',
				'class' => 'btn btn-danger btn-sm pull-right',
				'id' => 'btCancelarModalEliminarCuenta'
				],
		]);

		Pjax::begin(['id' => 'PjaxEliminaCuenta']);

			$cuenta_id = Yii::$app->request->get('eliminaCuenta',0);
		?>
		<div class="text-center">

			<label>Se eliminará la cuenta. ¿Desea continuar?</label></td>
			<br />
			<br />
			<?= Html::button('Aceptar',[
					'id' => 'viewpartida_btAceptar',
					'class'=>'btn btn-success',
					'onclick' => 'eliminarCuentaPartida('.$cuenta_id.')',
				]);
			?>
			&nbsp;&nbsp;
			<?= Html::button('Cancelar',[
					'id' => 'viewpartida_btCancelar',
					'class'=>'btn btn-primary',
					'onclick' => '$("#ModalEliminarCuenta").modal("hide")',
				]);
			?>

		</div>
		<?php

		Pjax::end();
	Modal::end();
	//FIN Formulario Modal "Eliminar Cuenta"

	//INICIO Bloque de código que realiza la baja lógica de una cuenta.
	Pjax::begin(['id' => 'PjaxEliminaCuentaPartida']);

		$cuenta_id = Yii::$app->request->get( 'eliminaCuentaPartida', 0 );

		if ( $cuenta_id != 0 )
		{
			echo '<script>$("#ModalEliminarCuenta").modal("hide")</script>';

			if ( $model->eliminarCuenta( $cuenta_id ) )
			{
				?>

					<script>

						$("#PjaxEliminaCuentaPartida").on("pjax:end",function()
						{

							$.pjax.reload({
								container:"#PjaxErrorCuentaIngreso",
								method:"POST",
								data:{
									mensaje:"Los datos se modificaron correctamente.",
									m:1,
								}
							});

							$("#PjaxErrorCuentaIngreso").on("pjax:end",function() {

								//Recargar la grilla
								$.pjax.reload("#PjaxGrilla");

								$("#PjaxErrorCuentaIngreso").off("pjax:end");
							});

							$("#PjaxEliminaCuentaPartida").off("pjax:end");
						});

					</script>

				<?php
			} else
			{
				$errores = $model->getErrors( null );

				echo '<script> var array = new Array();</script>';

				foreach( $errores[0] as $array )
				{
					echo '<script>array.push("'.$array.'");</script>';
				}

				echo '<script>$("#div_grilla").css("display","none");</script>';

				echo '<script>mostrarErrores( array, "#formcuenta_errorSummary" );</script>';
			}
		}

	Pjax::end();
	//FIN Bloque de código que realiza la baja lógica de una cuenta.
?>

<script>
function btBuscarClick()
{
    var datos     = {},
        error     = new Array();
		nombre    = $("#cuentaingreso_txNombre").val();

	datos.g_nombre = nombre;

	//Oculto los mensajes de error
	$("#formcuenta_errorSummary").css("display","none");

	$("#div_grilla").css("display","block");

	if ( error.length > 0 )
	{
		mostrarErrores( error, "#formcuenta_errorSummary" );
	}

	$.pjax.reload({
		container:"#PjaxGrillaCuentaIngreso",
		method:"GET",
		replace:false,
		push:false,
		data:datos
	});

}

function nuevaCuenta()
{
	$.pjax.reload({
		container:"#PjaxEditaCuenta0",
		type:"GET",
		replace:false,
		push:false,
	});

	$("#PjaxEditaCuenta0").on("pjax:end", function () {

		$("#ModalNuevaCuenta").modal("show");

		$("#PjaxEditaCuenta0").off("pjax:end");

	});
}

function editarCuenta(id)
{
	$.pjax.reload({
		container:"#PjaxEditaCuenta3",
		type:"GET",
		replace:false,
		push:false,
		data:{
			cuenta_id:id,
		}});

	$("#PjaxEditaCuenta3").on("pjax:end", function () {

		$("#ModalEditaCuenta").modal("show");

		$("#PjaxEditaCuenta3").off("pjax:end");

	});
}

function eliminarCuenta(id)
{
	$.pjax.reload({
		container:"#PjaxEliminaCuenta",
		type:"GET",
		replace:false,
		push:false,
		data:{
			eliminaCuenta:id,
		}});

	$("#ModalEliminarCuenta").modal("show");
}

function eliminarCuentaPartida(id)
{
	$.pjax.reload({
		container:"#PjaxEliminaCuentaPartida",
		type:"GET",
		replace:false,
		push:false,
		data:{
			eliminaCuentaPartida:id,
		}});
}

$(document).ready(function() {

	$("#div_grilla").css("display","none");
});

</script>
