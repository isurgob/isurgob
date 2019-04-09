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
 * Forma que se encarga de mostrar los datos de una "Partidas Presupuestarias".
 * 
 * Recibo:
 * 		
 * 		+ $model => Modelo de "Partida PResupuestaria"
 * 		+ $dataProvider => Datos de las cuentas asociadas a una partida presupuesaria
 */
 
$title = 'Consulta Partida';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = ['label' => 'Partidas Presupuestarias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;


//Obtengo los datos de la cuenta bancaria
?>

<style>

.div_grilla 
{
	padding: 5px 0px 5px 0px;
}
</style>

<div class="viewpartida-form">

<h1><?= Html::encode($title) ?></h1>

<?php

$model->getErrors();

$model->cta_part_id = $model->part_id;

?>

<div class="form-panel" style="padding-right:5px"> 
<table>
	<tr>
		<td><label for="código">Cód:</label></td>
		<td><?= Html::input('text','txCod',$model->part_id,[
					'id' => 'viewpartida_txCod',
					'class' => 'form-control solo-lectura',
					'style' => 'width:40px;text-align:center',
					]); ?>
		</td>
		<td width="20px"></td>
		<td><label for="formato">Formato:</label></td>
		<td><?= Html::input('text','txFromato',$model->formato,[
					'id' => 'viewpartida_txFromato',
					'class' => 'form-control solo-lectura',
					'style' => 'width:120px',
					]); ?>
		</td>
		<td width="20px"></td>
		<td><label for="nombre">Nombre:</label></td>
		<td><?= Html::input('text','txNombre',$model->nombre,[
					'id' => 'viewpartida_txNombre',
					'class' => 'form-control solo-lectura',
					'style' => 'width:320px',
					]); ?>
		</td>
		<td width="20px"></td>
		<td><label for="nivel">Nivel:</label></td>
		<td>
			<?= Html::input('text','txNivel',$model->nivel,[
					'id' => 'viewpartida_txNivel',
					'class' => 'form-control solo-lectura',
					'style' => 'width:40px;text-align:center',
					]); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td><label for="cuentaBancaria">Cuenta Bancaria:</label></td>
		<td width="550px">
			<?= Html::input('text','txCuentaBancaria',$model->obtenerDatosCuentaBancaria($model->bcocta_id),[
					'id' => 'viewpartida_txCuentaBancaria',
					'class' => 'form-control solo-lectura',
					'style' => 'width:550px',
					]); ?>
		</td>
	</tr>
</table>

<ul id='ulMenuDer' class='menu_derecho' style="padding:0px;margin-bottom:2px">
<li><hr style="color: #ddd; margin:1px" /></li>
</ul>

<div id="cuentas">

<table width="100%">
	<tr>
		<td align="left">
			<h3><label for="cuentas">Cuentas</label></h3>
		</td>
		<td align="right">
		<?php 
			
			//Para poder ingresar una cuenta, la rama no debe tener hijos
			
			if ( ! ( $model->validarEstadoPadre( $model->part_id ) ) )
			{
				if (utb::getExisteProceso(3041))
					echo Html::button('Agregar',[
						'id' => 'viewpartida_btAgregar',
						'class' => 'btn btn-primary',
						'onclick' => 'nuevaCuenta()',
					]);
			}
				 
		?>
		</td>
	</tr>
</table>
	

<!-- INICIO Grilla Cuentas -->
<div class="div_grilla">

<?php

	//INICIO Pjax para grilla de cuentas
	Pjax::begin(['id' => 'PjaxGrillaCuentas']);
		
			echo GridView::widget([
				'id' => 'viewpartida_GrillaCuentas',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProvider,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'cta_id','label' => 'Cuenta', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						['attribute'=>'nombre_redu','label' => 'Nombre Redu.', 'contentOptions'=>['style'=>'text-align:left','width'=>'3px']],
						['attribute'=>'tcta_nom','label' => 'Tipo', 'contentOptions'=>['style'=>'text-align:left','width'=>'5px']],
						['attribute'=>'cta_id_atras_nom','label' => 'Cta. Atrás', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						['attribute'=>'modif','label' => 'Modificación', 'contentOptions'=>['style'=>'text-align:left','width'=>'1px']],
						['attribute'=>'est','label' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						[
							'class' => 'yii\grid\ActionColumn',
							'contentOptions'=>['style'=>'width:20px'],
							'template' => (utb::getExisteProceso(3041) ? '{update}{delete}' : ''),
							'buttons'=>[			
								
								'update' => function($url, $model, $key)
											{      
												if ($model['est'] != 'B')      							
													return Html::button('<span class="glyphicon glyphicon-pencil" style="margin: 0px 1px"></span>', [
																'class'=>'bt-buscar-label','style'=>'color:#337ab7',
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
	//FIN Pjax para grilla de partidas presupuestarias
?>

</div>
<!-- FIN Grilla Cuentas -->

</div>

</div>

<?php

$form = ActiveForm::begin();

	echo $form->errorSummary( $model, ['id' => 'pp_errorSummary', 'style' => 'margin-top: 8px; margin-right: 15px']);	
	
	$model->clearErrors();

ActiveForm::end();

?>

<!-- INICIO Mensajes de error -->
<div style="margin-top: 8px;margin-right: 15px" >

<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 
					
				Pjax::begin(['id' => 'PjaxErrorViewPartida']);
				
					$mensaje = Yii::$app->request->post('mensaje',$mensaje);	
				
					if($mensaje != "")
					{ 
				
				    	Alert::begin([
				    		'id' => 'AlertaFormFiscalizacion',
							'options' => [
				        	'class' => 'alert-success',
				        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
				    		],
						]);
				
						echo $mensaje;
								
						Alert::end();
						
						echo "<script>window.setTimeout(function() { $('#AlertaFormFiscalizacion').alert('close'); }, 5000)</script>";
					 }
					
				Pjax::end();
			 
			?>
		</td>
	</tr>
</table>

</div>
<!-- FIN Mensajes de error -->

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
									
		echo $this->render('editaCuenta',['model' => $model, 'consulta' => 0, 'cuenta' => 0]);
		
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
									
		echo $this->render('editaCuenta',['model' => $model, 'consulta' => 3, 'cuenta' => 0]);
		
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
		<label>Se eliminará la cuenta.<br />¿Desea continuar?</label>
		</div>
		
		<div class="text-center" style="margin-top: 8px">
			<?= Html::button('Aceptar',[
						'id' => 'viewpartida_btAceptar',
						'class'=>'btn btn-success',
						'onclick' => 'eliminarCuentaPartida('.$cuenta_id.')']); ?>
			&nbsp;&nbsp;
			<?= Html::button('Cancelar',[
						'id' => 'viewpartida_btCancelar',
						'class'=>'btn btn-primary',
						'onclick' => '$("#ModalEliminarCuenta").modal("hide")']); ?>
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
							
							f_datosEliminados();
							
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
				
				echo '<script>mostrarErrores( array, "#pp_errorSummary" );</script>';
			}
		}
		
	Pjax::end();
	//FIN Bloque de código que realiza la baja lógica de una cuenta.	
?>

<script>
function f_datosEliminados()
{
	$.pjax.reload("#PjaxGrillaCuentas");
	
	$("#PjaxGrillaCuentas").on("pjax:end", function() {
		
		$.pjax.reload({
			container:"#PjaxErrorViewPartida",
			method:"POST",
			data:{
				mensaje:"Los datos se modificaron correctamente.",
				m:1,
			}
		});
		
		$("#PjaxGrillaCuentas").off("pjax:end");
		
	});
}

function btBuscarClick()
{
	var datos = {},
		subgrupo = $("#viewpartida_dlSubGrupo").val(),
		rubro = $("#viewpartida_dlRubro").val(),
		error = new Array();
	
	if (subgrupo == 0 || subgrupo == '')
		error.push( "Ingrese un subgrupo." );
		
	datos.grilla_cargar = 1;
	datos.grilla_subgrupo = subgrupo;
	datos.grilla_rubro = rubro;
	
	if ( error.length == 0 )
		$.pjax.reload({container:"#PjaxGrillaCuentas",method:"POST",data:datos});
	else
		mostrarErrores( error, "#pp_errorSummary" );

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

</script>