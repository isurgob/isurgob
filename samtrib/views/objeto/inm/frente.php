<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\objeto\Inm;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\web\Session;
use app\utils\db\utb;

 /*
  * Me llegan:
  * 
  * 	$model == El modelo de inmueble
  * 	$action == Identificador para conocer si se llega desde create, update o delete.
  * 		$action = 1 => Create
  * 		$action = 2 => Update
  * 		$action = 3 => Delete
  *		$calle_id == Código de la calle que se seleccionó
  *		$calle_nom == Nombre de la calle que se seleccionó
  *		$medida == Medida del frente
  *
  */

	/*
	 * El pjax form-nuevoFrente se encarga de recargar toda la página.
	 * Se usa para que se actualicen los datos de la sesión ya que el modal se dibuja junto
	 * con la vista que lo crea y no se dibuja cada vez que se lo invoca.
	 * De este modo, los datos se recargan con los valores enviados desde "domic_frente"
	 */

Pjax::begin(['id'=>'form-nuevoFrente']);


	$tor = 'FRENTE';
	
	/*
	 * INICIO Crear variables que se mostrarán en la vista
	 */	
	
		$session = new Session;
		$session->open();
	 					    							
		$consulta = $session['actionFRENTE'];
		$calle_id = $session['calle_id'];
		$calle_nom= $session['calle_nom'];
		$medida = $session['medida'];
		
		$session->close();	
		
		//Variable que se usará para determinar si se presionó el botón aceptar
		$envio = 0;	
	/*
	 * FIN Crear variables que se mostrarán en la vista
	 */	
	
	// INICIO Bloque de código que se encarga de crear, actualizar o eliminar los elementos del arreglo de frentes
	Pjax::begin(['id'=>'manejoFrentes']);

		//Obtengo los datos correspondientes al nuevo frente o a la actualización de uno ya existente
		if (isset($_POST['calle_id'])) $calle_id = $_POST['calle_id'];
		if (isset($_POST['calle_nom'])) $calle_nom = $_POST['calle_nom'];
		if (isset($_POST['medida'])) $medida =  $_POST['medida'];
		if (isset($_POST['envio'])) $envio = $_POST['envio'];
		
		if ($envio == '1' and $calle_id != '' and $calle_nom != '' )
		{
			//Validar que el valor de medida sea distinto de 0
			if ($medida > 0){
					
				$session = new Session;
				$session->open();
				
				switch($consulta)
				{
					//Se agrega un nuevo elemento al array
					case 1:
										
						$arregloTemporal = [
							'calle_id' => $calle_id,
							'calle_nom' => $calle_nom,
							'medida' => $medida,
							];
										
						$array = [];
						
						$array[$calle_id] = $arregloTemporal;
						
						//Verifica si el código de calle no se encuentra en el arreglo
						//array_key_exists devuelve true si encuentra la key en el arreglo
						if (array_key_exists($calle_id, $session['arregloFrentes']))
						{
						
						echo '<script>$.pjax.reload({' .
								'container:"#errorFrente",' .
								'data:{' .
									'mensaje:"La calle que intenta agregar ya se ingresó."},' .
									'method:"POST"' .
									'});</script>';
		
							
						} else {
							
							$session['arregloFrentes'] = $session['arregloFrentes'] + $array;
							
							echo '<script>$.pjax.reload({container:"#frente-actualizaGrilla",method:"POST",data:{act:1}});' .
									'$("#modal-frente, .window").modal("hide");</script>';
						}
					
						break;
					
					//Se modifica un elemento del array
					case 2:

							//Obtengo el arreglo que se encuentra en sesión
							$arreglo = $session['arregloFrentes'];
							
							//Actualizo los datos
							$arreglo[$calle_id]['medida'] = $medida;
							
							//Vuelvo a guardar el arreglo en sesión
							$session['arregloFrentes'] = $arreglo;
							
							echo '<script>$.pjax.reload({container:"#frente-actualizaGrilla",method:"POST",data:{act:1}});' .
									'$("#modal-frente, .window").modal("hide");</script>';
					
						break;
						
					//Se elimina un elemento del array
					case 3:
						$arreglo = $session['arregloFrentes'];
						unset($arreglo[$calle_id]);
						$session['arregloFrentes'] = $arreglo;
						
						echo '<script>$.pjax.reload({container:"#frente-actualizaGrilla",method:"POST",data:{act:1}});' .
									'$("#modal-frente, .window").modal("hide");</script>';
									
						break;
					
					$session->close();			
	
				}
			} else {//La medida ingresada es incorrecta
						
							echo '<script>$.pjax.reload({' .
								'container:"#errorFrente",' .
								'data:{' .
									'mensaje:"Ingrese un valor de medida correcto."},' .
									'method:"POST"' .
									'});</script>';
			}
			
		}
		
	Pjax::end();
	
	//Código que actualiza el nombre de la calle cuando ingresa el código de calle
	Pjax::begin(['id'=>'actualizaNombreCalle']);
	
		if(isset($_POST['calle_id'])){
			
			$calle_id = $_POST['calle_id'];
	
			$calle_nom = utb::getCampo('domi_calle','calle_id = ' . $calle_id);
			
			echo '<script>$("#calle_nomFRENTE").val("'.$calle_nom.'")</script>';		
	
		}
	
	Pjax::end();

?>

	<table border="0">
		<tr>
			<td width="60px"><label>Objeto:</label></td>
		<td><?= Html::input('text', 'inm-frente_obj_id'.$tor, $model->obj_id, ['id' => 'inm-frente_obj_id', 'class'=>'form-control', 'style'=>'background:#E6E6FA; width:60px;','disabled'=>'true']) ?></td>
		</tr>
	</table>
	<table>
		<tr>
			<td width="60px"><label>Calle:</label></td>
			<td><?= Html::input('text', 'calle_id'.$tor, $calle_id, ['id' => 'calle_id'.$tor, 'style' => 'width:60px','class' => 'form-control', 'onchange'=>'$.pjax.reload({container:"#actualizaNombreCalle",method:"POST",data:{calle_id:$(this).val()}})']) ?></td>
			<td width="5px"></td>
			<td><?= Html::Button("<i class='glyphicon glyphicon-search'></i>",['id'=>'boton-calles','class' => 'bt-buscar', 'disabled' => (intval($consulta) != 1), 'onClick' => '$("#BuscaCalle' . $tor . '").toggleClass("hidden");'])?></td>
			<td width="5px"></td>
			<td><?= Html::input('text','calle_nom'.$tor, $calle_nom, ['id' => 'calle_nom'.$tor,'class' => 'form-control', 'style'=>'width:300px; background:#E6E6FA;','disabled'=>'true'])?></td>
		</tr>
	</table>


	<table>
		<tr>
			<td width="60px"><label>Medida:</label></td>
			<td><?= Html::input('text', 'inm-frente_medida'.$tor, $medida, ['id' => 'inm-frente_medida', 'style' => 'width:60px','class' => 'form-control', 'onkeypress'=>'return justDecimal($(this).val(),event)']) ?></td>
		</tr>
	</table>
	
	<br />
	
	<table width="100%">
		<tr>
			<td width="100%">
					
				<?php 
				
				Pjax::begin(['id'=>'errorFrente']);
				
				$mensaje = '';
				
				if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];
				
			
				if($mensaje != ""){ 
			
			    	Alert::begin([
			    		'id' => 'AlertaMensaje',
						'options' => [
			        	'class' => 'alert-danger',
			        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
			    		],
					]);
			
					echo $mensaje;
							
					Alert::end();
					
					echo "<script>window.setTimeout(function() { $('#AlertaMensaje').alert('close'); }, 5000)</script>";
				 }
				 
				 Pjax::end();
			
				?>
			</td>
		</tr>
	</table>
	
	<table>
		<tr>
			<td>
			<?php
				if (utb::getExisteProceso(3071)){
					switch ($consulta)
					{
						case 1:
							echo Html::Button('Grabar',['class' => 'btn btn-success', 'onClick' => 'btAceptarFrente();', 'style'=>'margin-bottom:15px']);
							break;
							
						case 2:
							echo Html::Button('Grabar',['class' => 'btn btn-success', 'onClick' => 'btAceptarFrente();', 'style'=>'margin-bottom:15px']);
							break;
						
						case 3:
							echo Html::Button('Grabar',['class' => 'btn btn-danger', 'onClick' => 'btAceptarFrente();', 'style'=>'margin-bottom:15px']);
							break;
					}
				}
			?>
						
			</td>
		</tr>
	</table>

		<div id='BuscaCalle<?=$tor ?>' style='margin:0px; margin-top:5px; padding:5px;' class="form hidden">
			<div>
			<?php
																				
			Pjax::begin(['id' => 'FormBuscarCalle'.$tor]);
				$loc = "";
				if (isset($_POST['loc_id'.$tor])) $loc = $_POST['loc_id'.$tor];
				
				echo $this->render('//taux/auxbusca', [
						'tabla' => 'domi_calle', 'campocod' => 'calle_id',
						'idcampocod'=>'calle_id'.$tor,
            			'idcamponombre'=>'calle_nom'.$tor,
            			'cantmostrar' => 7,
            			'criterio' => ($loc == "" ? "" : 'loc_id='.$loc),
            			'idAux' => $tor 
					]);
				
			Pjax::end();
			?>
			</div>
		</div>
	<?php
	


	if($consulta == 2)
	{
		?>
		<script>
			$("#calle_idFRENTE").attr('disabled','disabled');
			$("#calle_nomFRENTE").attr('disabled','disabled');
			$("#boton-personas").attr('disabled','disabled');
		</script>
		
		<?php
	}

	//Si la consulta el cuando se dibuja el form en delete
	if ($consulta == 3) 
	{
		?>
		
		<script>
		$("#calle_idFRENTE").attr('disabled','disabled');
		$("#calle_nomFRENTE").attr('disabled','disabled');
		$("#boton-calles").attr('disabled','disabled');
		$("#inm-frente_medida").attr('disabled','disabled');
		</script>
		
		<?php
	}
	
	?>
	
<script>
	function cargaDatos(cod,nombre)
	{
		$("#inm-frente_calle_id").val(cod);
		$("#inm-frente_nombreCalle").val(nombre);
	}

	function Nombre()
	{
		$.pjax.reload(
			{
				container:"#grillaFiltrCalles",
				data:{nombre:$("#filtrocalles-txnombre").val()},
				method:"POST"
			}
		)
	}
	

	function btAceptarFrente()
	{
		
		var calle_id = $("#calle_idFRENTE").val();
		var calle_nom = $("#calle_nomFRENTE").val();
		var medida = $("#inm-frente_medida").val();

		if (calle_id == "")
		{
			$.pjax.reload(
				{	
					container:"#errorFrente",
					data:{	
						mensaje:'Ingrese una calle.'},
					method:"POST"
				}
			);
			
		} else if (calle_nom == "")
		{
			
			$.pjax.reload(
				{	
					container:"#errorFrente",
					data:{	
						mensaje:'Ingrese una calle válida.'},
					method:"POST"
				}
			);
			
		} else if (medida == '')
		{	
			$.pjax.reload(
				{	
					container:"#errorFrente",
					data:{	
						mensaje:'Ingrese una medida.'},
					method:"POST"
				}
			);
		} else 
		{
			$.pjax.reload(
				{	
					container:"#manejoFrentes",
					data:{	
						calle_id:$("#calle_idFRENTE").val(),
						calle_nom:$("#calle_nomFRENTE").val(),
						medida:medida,
						envio:1,},
					method:"POST"
				}
			);
						
		}
		

	}
</script>

<?php
	Pjax::end();
	?>