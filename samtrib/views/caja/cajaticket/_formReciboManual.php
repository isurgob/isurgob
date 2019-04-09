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

    /**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 */

$form = ActiveForm::begin(['id' => 'formReciboManual']);

echo Html::input('hidden','txID',$id,['id' => 'reciboManual_txID']);

?>

<div class="reciboManual_form">

    <div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>

	<table border='0'>
		<tr>
			<td width='50px'>    
				<label>Recibo:</label>
			</td>
			<td>
	    		<?= Html::input('text', 'reciboManual_recibo', null, ['class' => 'form-control','id'=>'reciboManual_recibo','style'=>'width:100px','onkeypress'=>'return justNumbers(event)']); ?>
	    	</td>
	    	<td width="50px"></td>
	    	<td width='50px'><label>Fecha:</label></td>
	    	<td>
				<?= 
					DatePicker::widget(
					[
						'id' => 'reciboManual_fecha',
						'name' => 'reciboManual_fecha',
						'dateFormat' => 'dd/MM/yyyy',
						'options' => ['style' => 'width:70px;text-align:center','class' => 'form-control'],
						'value' => Fecha::usuarioToDatePicker( Fecha::getDiaActual() ),
					]
					);
				?> 
			</td>
		</tr>
		<tr>
			<td width='50px'>    
				<label>Acta:</label>
			</td>
			<td>
	    		<?= Html::input('text', 'reciboManual_acta', null, ['class' => 'form-control','id'=>'reciboManual_acta','style'=>'width:100px;','onkeypress'=>'return justNumbers(event)']); ?>
	    	</td>
	    	<td width="50px"></td>
	    	<td width='50px'><label>Monto:</label></td>
			<td>
	    		<?= Html::input('text', 'reciboManual_monto', null, [
						'class' => 'form-control',
						'id'=>'reciboManual_monto',
						'style'=>'width:70px;text-align:right',
						'onkeypress'=>'return justDecimal($(this).val(),event)',
					]);
				?>
	    	</td>
		</tr>
	</table>
	<table>
		<tr>
			<td width='50px'><label>Item:</label></td>
			<td><?= Html::dropDownList('reciboManual_item', null, utb::getAux('item','item_id','nombre',0,"trib_id = 12"),['id'=>'reciboManual_item','class'=>'form-control','style'=>'width:272px','onkeypress'=>'return justNumbers(event)']); ?></td>
		</tr>
		<tr>
			<td width='50px'><label>Área:</label></td>
			<td><?= Html::dropDownList('reciboManual_area', null, utb::getAux('sam.muni_oficina','ofi_id','nombre'),['id'=>'reciboManual_area','class'=>'form-control','style'=>'width:272px']); ?></td>
			<td width="30px"></td>
			<td align="right"><?= Html::button('<span class="glyphicon glyphicon-plus"></span>',['class'=>'btn btn-primary','onclick'=>'agregarRecibo()', 'id'=>'btAgregarReciboManual']) ?></td>
		</tr>
	</table>
	</div>
</div>

<?php
	
	$tab = 0;

	//INICIO Código que se ejecuta al agregar un nuevo Recibo
	//Debo obtener los datos enviados y agregarlos como un nuevo arreglo del arreglo de recibos en sesión
	Pjax::begin(['id'=> 'obtenerRecibo' ]);

		//Recibo los datos
		$recibo = Yii::$app->request->post('recibo','');
		$monto = Yii::$app->request->post('monto','');
		$fecha = Yii::$app->request->post('fecha','');
		$acta = Yii::$app->request->post('acta','');
		$area = Yii::$app->request->post('area','');
		$item = Yii::$app->request->post('item','');
		$area_nom = Yii::$app->request->post('area_nom','');
		
		$session = new Session;
		$session->open();
		
		if ( $recibo != '' )
		{

			//Creo un arreglo
			$arreglo = [
			
				'recibo' => $recibo,
				'monto' => number_format($monto,2,'.',''),
				'fecha' => $fecha,
				'acta' => $acta,
				'area' => $area,
				'item_id' => $item,
				'area_nom' => $area_nom,
				
			];
			
			$array = [];
						
			$array[$recibo] = $arreglo;
						
			//Verifica si el código de recibo no se encuentra en el arreglo
			//array_key_exists devuelve true si encuentra la key en el arreglo
			if (array_key_exists($recibo,  $session['arregloRecibosManual']))
			{
						
				echo '<script>mostrarError( ["El Recibo ingresado ya existe en el Comprobante."] );</script>';
			
							
			} else {
			
				if (count($session['arregloRecibosManual']) == 0) //En el caso que sea el primer elemento que se agrega al array
					$session['arregloRecibosManual'] = $array;
				else
					$session['arregloRecibosManual'] = $session['arregloRecibosManual'] + $array;
				
			}
			
			
		}
		
		//Se ejecuta si el usuario necesita eliminar algún recibo del comprobante
		if (isset($_POST['eliminar']) && $_POST['eliminar'] == 1)
		{

			$arreglo = $session['arregloRecibosManual'];
			unset($arreglo[$_POST['id']]);
			$session['arregloRecibosManual'] = $arreglo;

		}
		
		$array = $session['arregloRecibosManual'];
		
		if ( count($array) == 80 )
		{ 
			//Deshabilito el boton de agrear recibos
			echo '<script>$("#btAgregarReciboManual").addClass("disabled");mostrarError( ["A alcanzado el límite de recibos."] );';			
			
		} else 
		{
			echo '<script>$("#btAgregarReciboManual").removeClass("disabled");</script>';	
		}
		
		$session->close();
		
	Pjax::end();

	echo Tabs :: widget ([
	    
	    	 	'id' => 'TabReciboManual',
				'items' => [ 
	 				['label' => 'Recibos', 
	 				'content' => $this->render('grillaReciboManual', ['model' => $model, 'consulta' => $consulta]),
	 				'active' => ($tab==0) ?  true : false,
	 				'options' => ['class'=>'tabItem'],			
	 				],
	 				['label' => 'Observaciones' , 
	 				'content' => Html::activeTextarea($model, 'obs',[
									'id'=>'reciboManual_txObs', 
									'maxlength' => 1000,
									'style' => 'width:450px;height:100px;max-width:450px;max-height:200px',
									'class' => 'form-control',
								]),
	 				'active' => ($tab==1) ?  true : false,
	 				'options' => ['class'=>'tabItem']
	 				]
	 			]]);
		
		echo '<script>$("#reciboManual_recibo").focus();</script>';
		
	
	
	if ( $consulta != 1 )
	{
		?>
		
		<div style="margin-top:8px">
			
			<?php
				
				if ( $consulta == 0 || $consulta == 3 )
				{
					echo  Html::submitButton('Grabar',[
							'id' => 'reciboManual_btAceptar',
							'class' => 'btn btn-success',
						]);
				}
				
				if ( $consulta == 2 )
				{
					echo Html::a('Eliminar',['deleterecibomanual', 'id' => $id, 'action' => 2], [
							'id' => 'reciboManual_btAceptar',
							'class' => 'btn btn-danger',
						]);
				}
			?>
				
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
			<?= Html::a('Cancelar', ['viewrecibomanual', 'consulta'=>1, 'reiniciar'=>1, 'id' => ( isset( $id ) ? $id : 0 )],[ 
		 			'class' => 'btn btn-primary',
		 		]);
		 	?>
			
		</div>
		
		<?php
			 
	}
	
	echo $form->errorSummary( $model, [
			'id' => 'reciboManual_errorSummary', 
			'style' => 'margin-top:8px;',
		]);
		
	ActiveForm::end();
	
	if ( $consulta == 1 || $consulta == 2 ) 
	{
		echo "<script>";
		echo "DesactivarForm('formReciboManual');";
		echo "</script>";
	}
?>

<!-- INICIO Mensajes de alerta -->
<div style="margin-top:8px">
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 
			
			Pjax::begin(['id'=>'alertReciboManual']);
			
			if ($alert != '' && $m != '')
			{ 
		
		    	Alert::begin([
		    		'id' => 'AlertaMensajeReciboManual',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $m !== '' ? 'display:block' : 'display:none' 
		    		],
				]);
		
				echo $alert;
						
				Alert::end();
				
				echo "<script>window.setTimeout(function() { $('#AlertaMensajeReciboManual').alert('close'); }, 5000)</script>";
			 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
</div>
<!-- FIN Mensajes de alerta -->

<script>
function mostrarError ( error )
{
	var $contenedor = $("#reciboManual_errorSummary"),
		$lista = $("#reciboManual_errorSummary ul");
			
	$lista.empty();
	
	$contenedor.css("display","block");
	
	for (e in error)
	{
		$el = $("<li />");
		$el.text(error[e]);
		$el.appendTo($lista);
	}
}

function agregarRecibo()
{
	var recibo = $("#reciboManual_recibo").val(),
		monto = $("#reciboManual_monto").val(),
		fecha = $("#reciboManual_fecha").val(),
		acta = $("#reciboManual_acta").val(),
		area = $("#reciboManual_area option:selected").val(),
		item = $("#reciboManual_item option:selected").val(),
		area_nom = $("#reciboManual_area option:selected").text(),
		fechaActual = new Date(),
		hoy = ( fechaActual.getDate() < 10 ? '0' + fechaActual.getDate() : fechaActual.getDate() ) + '/' + (fechaActual.getMonth() + 1) + '/' + fechaActual.getFullYear();
		error = new Array();
		
	$("#reciboManual_errorSummary").css("display","none");
	
	if (recibo == '' )
	{
		error.push( "Ingrese número de Recibo." );
	}
	
	if (monto == '' )
	{
		error.push( "Ingrese un monto." );
	} 
	
	if ( fecha.length < 10)
	{
		error.push( "Ingrese una fecha válida." );
	
	} else if( ValidarRangoFechaJs( fecha, hoy ) == 1 )
 	{
 		error.push( "La fecha no puede ser superior a la fecha actual." );
 	} 
 	
 	if ( error.length == 0 )
	{
		
		$("#reciboManual_recibo").val('');
		$("#reciboManual_monto").val('');
		$("#reciboManual_acta").val('');
		$.pjax.reload({
			container:"#obtenerRecibo",
			method:"POST",
			data:{
				recibo:recibo,
				monto:monto,
				fecha:fecha,
				acta:acta,
				area:area,
				item:item,
				area_nom:area_nom,
			}
		});
	
	} else 
	{
		mostrarError( error );
	}
	
}

function btGrabar(tipo,id)
{
	$("#txTipo").val(tipo);
	$("#formReciboManual").submit();
}

$("#obtenerRecibo").on("pjax:end", function() {
	
	$.pjax.reload("#PjaxGrillaRecibo");
	
});

</script>