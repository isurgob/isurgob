<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Tabs;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Alert;

/**
 * Forma que se dibuja cuando se llega a Débito Automático
 * Recibo:
 * 			=> $model -> Modelo de Débito
 */
 
/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */
 
Pjax::begin(['id'=>'PjaxGeneraDebAut']);

	$cajaGen = Yii::$app->request->post('caja','');
	$anioGen = Yii::$app->request->post('anio','');
	$mesGen = Yii::$app->request->post('mes','');
	$arregloGen = Yii::$app->request->post('arreglo',[]);
	
	if ($cajaGen != '')
	{
		//Tengo que recorrer el arreglo e ir generando los débitos
		
		$result = $model->generarDebito($cajaGen,$arregloGen,$anioGen,$mesGen);
		
		//Envío un mensaje dependiendo del resultado de la operación
		if ($result == '')
		{
			echo '<script>$.pjax.reload({container:"#errorDebito",method:"POST",data:{mensaje:"Los datos se guardaron correctamene.",m:1}});</script>';

		} else {
			
			echo '<script>$.pjax.reload({container:"#errorDebito",method:"POST",data:{mensaje:"Hubo un error al grabar los datos.",m:2}});</script>';
		}
		
		//Cierro la ventana modal
		echo '<script>$("#DebitoGen_txAnio").val("");</script>';
		echo '<script>$("#DebitoGen_txMes").val("");</script>';
		echo '<script>$("#ModalGenerarDeb").modal("hide");</script>';
	}
	
Pjax::end();

Pjax::begin(['id'=>'PjaxModalGen']);

$caja = Yii::$app->request->post('caja','');

?>

<div class="DebitoGen_info">

<!-- INICIO Mensajes de error -->
<table width="98%">
	<tr>
		<td width="100%">
				
			<?php 

			Pjax::begin(['id'=>'errorDebitoGen']);
			
			$mensaje = '';
			
			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];
		
			if($mensaje != "")
			{ 
		
		    	Alert::begin([
		    		'id' => 'AlertaMensajeDebitoGen',
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
		    		],
				]);
		
				echo $mensaje;
						
				Alert::end();
				
				echo "<script>window.setTimeout(function() { $('#AlertaMensajeDebitoGen').alert('close'); }, 5000)</script>";
			 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<div class="form-panel" style="padding-right:8px">
<table>
	<tr>
		<td width="40px"><label>Caja:</label></td>
		<td colspan="4">
			<?= Html::dropDownList('dlDebitoGen',$caja,utb::getAux('caja','caja_id','nombre',1,"tipo IN (3,4,5) AND est='A'"),[
					'id'=>'DebitoGen_dlDebitoGen',
					'class'=>'form-control solo-lectura',
					'style'=>'width:100%;text-align:center',
				]);
			?>
		</td>
	</tr>
	<tr>
		<td><label>Año:</label></td>
		<td width="40px" align="left"><?= Html::input('text','txAnio',null,['id'=>'DebitoGen_txAnio','class'=>'form-control','style'=>'width:40px;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>4]) ?></td>	
		<td width="20px" align="left"></td>
		<td width="35px" align="left"><label>Mes:</label></td>
		<td width="35px" align="left"><?= Html::input('text','txMes',null,['id'=>'DebitoGen_txMes','class'=>'form-control','style'=>'width:35px;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>2]) ?></td>
	</tr>
</table>

</div>

<!-- INICIO Grilla -->
<div class="form-panel" style="padding-right:10px;padding-bottom:10px;margin-top:10px">

<?php
Pjax::begin();
	
	$dataProvider = $model->listarTributosDebAut();
	
	echo GridView::widget([
			'id' => 'GrillaDebitosGen',
			'dataProvider' => $dataProvider,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [ 
					['content'=> function($model, $key, $index, $column) {return Html::checkbox('DebitoGen_ckActivo[]',1,[
																		'id' => 'DebitoGen_ckPeriodo'.$model['trib_id'], 'value'=>$model['trib_id']]);},
																		'contentOptions'=>['style'=>'width:2px','class'=>'simple'],
					'header' => Html::checkBox('selection_all', false, [
							'id' => 'DebitoGen_ckActivoHeader',
					        'onchange'=>'cambiarChecks()',
					    ]),
					],
					['attribute'=>'nombre','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
		        	],
			]);
		
Pjax::end();

?>
</div>
<!-- FIN Grilla -->
<?= Html::Button('Generar',['id' => 'Debitoliquidacion_btBuscar','class'=>'btn btn-primary','onclick'=>'generar()']); ?></td>

</div>

<?php

Pjax::end()

?>

<script>
function cambiarChecks()
{
	var checks = $('#GrillaDebitosGen input[type="checkbox"]');
	
	if ($("#DebitoGen_ckActivoHeader").is(":checked"))
	{
		checks.each(function() {
	
			checks.prop('checked','checked');
		
		});
	} else
	{
		checks.each(function() {
	
			checks.prop('checked','');
			
		});
	}
}

function generar()
{
	var caja = $("#DebitoGen_dlDebitoGen").val();
	var anio = $("#DebitoGen_txAnio").val();
	var mes = $("#DebitoGen_txMes").val();
	
	var checks = $('#GrillaDebitosGen input:checked').not("#DebitoGen_ckActivoHeader");
	
	/* En arreglo obtengo un arreglo con el ID de los tributos seleccionados */
	var arreglo = [];
	
	checks.each(function() {
	
		arreglo.push($(this).val());
	});
	
	var error = "";

	/* Cuando se filtra por año y por mes, se debe ingresar un tributo */	
	if (anio == '') 
		error += "<li>Ingrese un año.</li>";

 	if (mes == '')
		error += "<li>Ingrese un mes.</li>";
	
	if (arreglo.length == 0)
		error += "<li>No hay ningún tributo seleccionado.</li>";

	if (error == "")
	{
		$.pjax.reload({container:"#PjaxGeneraDebAut",method:"POST",data:{	
																		caja:caja,
																		anio:anio,
																		mes:mes,
																		arreglo:arreglo
																				}});
	} else 
	{
		$.pjax.reload({container:"#errorDebitoGen",method:"POST",data:{mensaje:error}});
	}
}
</script>