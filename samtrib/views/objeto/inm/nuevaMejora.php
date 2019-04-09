<?php
/**
 * Vista que será mostrada como ventana modal de Mejora.
 * Permitirá seleccionar una actualización de polígonos.
 * 
 */
 
/**
 * Recibo:
 * 		$arregloMejoras es un arreglo con los valores de items activos
 * 		
 * 		$session En sesión recibo los datos correspondientes a la modificación que se 
 * 			actualizará o eliminará.
 */
 
use yii\helpers\Html;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use yii\web\Session;
use yii\bootstrap\Alert;


Pjax::begin(['id'=>'form-nuevaMejora']);

	/*
	 * INICIO Crear variables que se mostrarán en la vista
	 */	
	$session = new Session;
	$session->open();
	
	$consulta = $session['actionMEJORA'];
	
	$pol = $session['pol'];
	$perdesde = $session['perdesde'];
	$perdesde_nom = $session['perdesde_nom'];
	$perhasta = $session['perhasta'];
	$tori = $session['tori'];
	$tori_nom = $session['tori_nom'];
	$tform = $session['tform'];
	$nivel = $session['nivel'];
	$tdest = $session['tdest'];
	$tdest_nom = $session['tdest_nom'];
	$tobra = $session['tobra'];
	$tobra_nom = $session['tobra_nom'];
	$anio = $session['anio'];
	$est = $session['est'];
	$est_nom = $session['est_nom'];
	$supcub = $session['supcub'];
	$supsemi = $session['supsemi'];
	$plantas = $session['plantas'];
	$cat = $session['cat'];
	$item01 = $session['item01'];
	$item02 = $session['item02'];
	$item03 = $session['item03'];
	$item04 = $session['item04'];
	$item05 = $session['item05'];
	$item06 = $session['item06'];
	$item07 = $session['item07'];
	$item08 = $session['item08'];
	$item09 = $session['item09'];
	$item10 = $session['item10'];
	$item11 = $session['item11'];
	$item12 = $session['item12'];
	$item13 = $session['item13'];
	$item14 = $session['item14'];
	$item15 = $session['item15'];
	$estado = $session['estado'];	
	$bd = $session['BDMejora'];

	$session->close();	
		
	//Variable que se usará para determinar si se presionó el botón aceptar
	$envio = 0;	

	//Crear variables para manejar periodos
	$anioDesde = substr($perdesde,0,4);
	$cuotaDesde = substr($perdesde,4,3);
	$anioHasta = substr($perhasta,0,4);
	$cuotaHasta = substr($perhasta,4,3);
	
	/*
	 * FIN Crear variables que se mostrarán en la vista
	 */	
	 
	 // INICIO Bloque de código que se encarga de crear, actualizar o eliminar los elementos del arreglo de frentes
	Pjax::begin(['id'=>'manejoMejoras']);
		
		//Obtengo los datos correspondientes a la nueva mejora o a la actualización de una ya existente.
		if(isset($_POST['envio'])) $envio = $_POST['envio'];
		
	 	if(isset($_POST['pol'])) $pol = round($_POST['pol']);
	    if(isset($_POST['perdesde'])) $perdesde = $_POST['perdesde'];
	    if(isset($_POST['perdesde_nom'])) $perdesde_nom = $_POST['perdesde_nom'];
	    if(isset($_POST['perhasta'])) $perhasta = $_POST['perhasta'];
	    if(isset($_POST['tori'])) $tori = $_POST['tori'];
	    if(isset($_POST['tori_nom'])) $tori_nom = $_POST['tori_nom'];
	    if(isset($_POST['tform'])) $tform = $_POST['tform'];
	    if(isset($_POST['nivel'])) $nivel = (double)$_POST['nivel'];
	    if(isset($_POST['tdest'])) $tdest = $_POST['tdest'];
	    if(isset($_POST['tdest_nom'])) $tdest_nom = $_POST['tdest_nom'];
	    if(isset($_POST['tobra'])) $tobra = $_POST['tobra'];
	    if(isset($_POST['tobra_nom'])) $tobra_nom = $_POST['tobra_nom'];
	    if(isset($_POST['anio'])) $anio = (integer)$_POST['anio'];
	    if(isset($_POST['est'])) $est = (integer)$_POST['est'];
	    if(isset($_POST['est_nom'])) $est_nom = $_POST['est_nom'];
	    if(isset($_POST['supcub'])) $supcub = (double)$_POST['supcub'];
	    if(isset($_POST['supsemi'])) $supsemi = (double)$_POST['supsemi'];
	    if(isset($_POST['plantas'])) $plantas = (double)$_POST['plantas'] == 0 ? 1 : (double)$_POST['plantas'];
	    if(isset($_POST['cat'])) $cat = $_POST['cat'];
	    if(isset($_POST['item01'])) $item01 = $_POST['item01'];
	    if(isset($_POST['item02'])) $item02 = $_POST['item02'];
	    if(isset($_POST['item03'])) $item03 = $_POST['item03'];
	    if(isset($_POST['item04'])) $item04 = $_POST['item04'];
	    if(isset($_POST['item05'])) $item05 = $_POST['item05'];
	    if(isset($_POST['item06'])) $item06 = $_POST['item06'];
	    if(isset($_POST['item07'])) $item07 = $_POST['item07'];
	    if(isset($_POST['item08'])) $item08 = $_POST['item08'];
	    if(isset($_POST['item09'])) $item09 = $_POST['item09'];
	    if(isset($_POST['item10'])) $item10 = $_POST['item10'];
	    if(isset($_POST['item11'])) $item11 = $_POST['item11'];
	    if(isset($_POST['item12'])) $item12 = $_POST['item12'];
	    if(isset($_POST['item13'])) $item13 = $_POST['item13'];
	    if(isset($_POST['item14'])) $item14 = $_POST['item14'];
	    if(isset($_POST['item15'])) $item15 = $_POST['item15'];
	    if(isset($_POST['estado'])) $estado = $_POST['estado'];
		
		
		if ((string)$envio == '1' and (string)$pol != '' and (string)$perdesde != '')
		{
			//Le agrego 0 si es menor a 10 para que quede un string de 2 caracteres,
			//ya que de esa manera se identifican las key en el array de modificación.
			if ($pol < 10)
			{
				$poligono = '0' . $pol;
			} else {
				
				$poligono = "'$pol'";
			}
					
				
				$session = new Session;
				$session->open();
				
				switch( $consulta )
				{
					//Se agrega un nuevo elemento al array
					case 1:
										
						$arregloTemporal = [
								'poligono' => $poligono,
							 	'pol' =>  $pol,
							    'perdesde' =>  $perdesde,
							    'perdesde_nom' =>  $perdesde_nom,
							    'perhasta' =>  $perhasta,
							    'tori' =>  $tori,
							    'tori_nom' =>  $tori_nom,
							    'tform' =>  $tform,
							    'nivel' =>  $nivel,
							    'tdest' =>  $tdest,
							    'tdest_nom' =>  $tdest_nom,
							    'tobra' =>  $tobra,
							    'tobra_nom' =>  $tobra_nom,
							    'anio' =>  $anio,
							    'est' =>  $est,
							    'est_nom' =>  $est_nom,
							    'supcub' =>  $supcub,
							    'supsemi' =>  $supsemi,
							    'plantas' =>  $plantas,
							    'cat' =>  $cat,
							    'item01' =>  $item01,
							    'item02' =>  $item02,
							    'item03' =>  $item03,
							    'item04' =>  $item04,
							    'item05' =>  $item05,
							    'item06' =>  $item06,
							    'item07' =>  $item07,
							    'item08' =>  $item08,
							    'item09' =>  $item09,
							    'item10' =>  $item10,
							    'item11' =>  $item11,
							    'item12' =>  $item12,
							    'item13' =>  $item13,
							    'item14' =>  $item14,
							    'item15' =>  $item15,
							    'estado' =>  'A',
							    'BD' => '0',
							    
						];
										
						$array = [];
						
						$array[$poligono] = $arregloTemporal;
						
						//Verifica si el código de calle no se encuentra en el arreglo
						//array_key_exists devuelve true si encuentra la key en el arreglo
						if (array_key_exists($poligono, $session['arregloMejoras']) and $session['arregloMejoras'][$poligono]['est'] == 'A')
						{
						
						echo '<script>$.pjax.reload({' .
								'container:"#errorMejora",' .
								'data:{' .
									'mensaje:"La mejora que intenta agregar ya se ingresó."},' .
									'method:"POST"' .
									'});</script>';
		
							
						} else {
							
							$session['arregloMejoras'] = array_merge($session['arregloMejoras'], $array);
							
							echo '<script>$.pjax.reload({container:"#mejora-actualizaGrilla",method:"POST"});' .
									'$("#modal-nuevaMejora, .window").modal("hide");</script>';
						}
					
						break;
					
					//Se modifica un elemento del array
					case 2:

							//Obtengo el arreglo que se encuentra en sesión
							$arreglo = $session['arregloMejoras'];
						
							//Actualizo los datos
						   $arreglo[$poligono]['perdesde'] =  $perdesde;
						   $arreglo[$poligono]['perdesde_nom'] =  $perdesde_nom;
						   $arreglo[$poligono]['perhasta'] =  $perhasta;
						   $arreglo[$poligono]['tori'] =  $tori;
						   $arreglo[$poligono]['tori_nom'] =  $tori_nom;
						   $arreglo[$poligono]['tform'] =  $tform;
						   $arreglo[$poligono]['nivel'] =  $nivel;
						   $arreglo[$poligono]['tdest'] =  $tdest;
						   $arreglo[$poligono]['tdest_nom'] =  $tdest_nom;
						   $arreglo[$poligono]['tobra'] =  $tobra;
						   $arreglo[$poligono]['tobra_nom'] =  $tobra_nom;
						   $arreglo[$poligono]['anio'] =  $anio;
						   $arreglo[$poligono]['est'] =  $est;
						   $arreglo[$poligono]['est_nom'] =  $est_nom;
						   $arreglo[$poligono]['supcub'] =  $supcub;
						   $arreglo[$poligono]['supsemi'] =  $supsemi;
						   $arreglo[$poligono]['plantas'] =  $plantas;
						   $arreglo[$poligono]['cat'] =  $cat;
						   $arreglo[$poligono]['item01'] =  $item01;
						   $arreglo[$poligono]['item02'] =  $item02;
						   $arreglo[$poligono]['item03'] =  $item03;
						   $arreglo[$poligono]['item04'] =  $item04;
						   $arreglo[$poligono]['item05'] =  $item05;
						   $arreglo[$poligono]['item06'] =  $item06;
						   $arreglo[$poligono]['item07'] =  $item07;
						   $arreglo[$poligono]['item08'] =  $item08;
						   $arreglo[$poligono]['item09'] =  $item09;
						   $arreglo[$poligono]['item10'] =  $item10;
						   $arreglo[$poligono]['item11'] =  $item11;
						   $arreglo[$poligono]['item12'] =  $item12;
						   $arreglo[$poligono]['item13'] =  $item13;
						   $arreglo[$poligono]['item14'] =  $item14;
						   $arreglo[$poligono]['item15'] =  $item15;
						   $arreglo[$poligono]['estado'] =  'A';
						   $arreglo[$poligono]['BD'] = '0';
							
							//Vuelvo a guardar el arreglo en sesión
							$session['arregloMejoras'] = $arreglo;
							
				
							
							echo '<script>$.pjax.reload({container:"#mejora-actualizaGrilla",method:"POST"});' .
									'$("#modal-nuevaMejora, .window").modal("hide");</script>';
				
						break;
						
					//Se elimina un elemento del array
					case 3:
						$arreglo = $session['arregloMejoras'];
						
						if($arreglo[$poligono]['BD'] == '1')
							$arreglo[$poligono]['estado'] =  'B';
						else 
							unset($arreglo[$poligono]);
						$session['arregloMejoras'] = $arreglo;
						
						echo '<script>$.pjax.reload({container:"#mejora-actualizaGrilla",method:"POST"});' .
									'$("#modal-nuevaMejora, .window").modal("hide");</script>';
									
						break;
					
					$session->close();			
	
				}
						
			}
			
		
		
	Pjax::end();
?>

<div class="form" style="padding-bottom: 8px">
<table>
	<tr>
		<td width="60px"><label>Objeto</label>
		<td><?= Html::input('text','mej-obj_id',$model->obj_id,['id'=>'mej-obj_id','class'=>'form-control', 'style'=>'background:#E6E6FA; width:60px;', 'disabled'=>'true']) ?></td>
		<td width="30px"></td>
		<td><label>Rango Períodos:</td>
		<td><?= Html::input('text','mej-anioDesde',$anioDesde,['id'=>'mej-anioDesde', 'class'=>'form-control', 'style'=>'width:40px','maxlength'=>'4','onkeypress'=>'return justNumbers( event )']); ?></td>
		<td><?= Html::input('text','mej-cuotaDesde',$cuotaDesde,['id'=>'mej-cuotaDesde', 'class'=>'form-control', 'style'=>'width:35px','maxlength'=>'3','onkeypress'=>'return justNumbers( event )']); ?></td>
		<td width="10px"></td>
		<td><?= Html::input('text','mej-anioHasta',$anioHasta,['id'=>'mej-anioHasta', 'class'=>'form-control', 'style'=>'width:40px','maxlength'=>'4','onkeypress'=>'return justNumbers( event )']); ?></td>
		<td><?= Html::input('text','mej-cuotaHasta',$cuotaHasta,['id'=>'mej-cuotaHasta', 'class'=>'form-control', 'style'=>'width:35px','maxlength'=>'3','onkeypress'=>'return justNumbers( event )']); ?></td>
	</tr>
	
</table>
<br />

<table>
	<tr>
		<td width="60px"><label>Polígono</label></td>
		<td><?= Html::input('text','mej-poligono',$pol,['id'=>'mej-poligono', 'class'=>'form-control', 'style'=>'width:30px','maxlength'=>'4','onkeypress'=>'return justNumbers( event )']); ?></td>
		<td width="30px"></td>
		<td><label>Año</label></td>
		<td><?= Html::input('text','mej-anio',$anio,['id'=>'mej-anio', 'class'=>'form-control', 'style'=>'width:40px','maxlength'=>'4','onkeypress'=>'return justNumbers( event )']); ?></td>
		<td width="65px"></td>
		<td width="80px"><label>Origen</label></td>
		<td><?= Html::dropDownList('mej-origen', $tori, utb::getAux('inm_mej_tori'), ['id'=>'mej-origen', 'class'=>'form-control', 'style'=>'width:120px'])?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Form.</label></td>
		<td><?= Html::dropDownList('mej-form', $tform, utb::getAux('inm_mej_tform'), ['id'=>'mej-form', 'class'=>'form-control', 'style'=>'width:160px'])?></td>
		<td width="30px"></td>
		<td width="80px"><label>Nivel</label></td>
		<td><?= Html::input('text','mej-nivel',$nivel,['id'=>'mej-nivel', 'class'=>'form-control', 'style'=>'width:60px']); ?></td>
	</tr>

	<tr>
		<td><label>Destino</label></td>
		<td><?= Html::dropDownList('mej-dest', $tdest, utb::getAux('inm_mej_tdest'), ['id'=>'mej-dest', 'class'=>'form-control', 'style'=>'width:160px'])?></td>
		<td></td>
		<td><label>Plantas</label></td>
		<td><?= Html::input('text','mej-plantas',$plantas,['id'=>'mej-plantas', 'class'=>'form-control', 'style'=>'width:60px']); ?></td>
	</tr>
	
	<tr>
		<td><label>Obra</label></td>
		<td><?= Html::dropDownList('mej-obra', $tobra, utb::getAux('inm_mej_tobra'), ['id'=>'mej-obra', 'class'=>'form-control', 'style'=>'width:160px', 'prompt' => '<Seleccionar>'])?></td>
		<td></td>
		<td><label>Sup. Cubierta</label></td>
		<td><?= Html::input('text','mej-supcub',$supcub,['id'=>'mej-supcub', 'class'=>'form-control', 'style'=>'width:60px']); ?></td>
	</tr>
	
	<tr>
		<td><label>Estado</label></td>
		<td><?= Html::dropDownList('mej-est', $est, utb::getAux('inm_mej_test'), ['id'=>'mej-est', 'class'=>'form-control', 'style'=>'width:160px'])?></td>
		<td></td>
		<td><label>Sup. Semicub</label></td>
		<td><?= Html::input('text','mej-supsemi',$supsemi,['id'=>'mej-supsemi', 'class'=>'form-control', 'style'=>'width:60px']); ?></td>
	</tr>
	
</table>

<table>
	<tr>

	<?php
	
	
	if (isset ($arregloMejoras)){
		
		$contador = 1;
		
		while ($nombre = current($arregloMejoras)){
			if ($contador < 10)
				$strVar = 'item0' . $contador;
			else
				$strVar = 'item' . $contador;
				
			?>
						
			<td width='50px' align="center"><label><?= $nombre ?></label>
			<?= Html::input('text','mejora_'.key($arregloMejoras),$$strVar,['id'=>'mej-'.$strVar, 'class'=>'form-control', 'style'=>'width:100%','onkeypress'=>'return justNumbers( event )','maxlength'=>'1']); ?>
			<td width="10px"></td>

			<?php
			if ($contador == 8){
			?>
						
			</tr>
			<tr>

			<?php
			}
			$contador++;
			next($arregloMejoras);
		}	
		
		while($contador < 16)
		{
			if ($contador < 10)
				$strVar = 'item0' . $contador;
			else
				$strVar = 'item' . $contador;
				
			?>
						
			<td width='50px' align="center"><label><?= $nombre ?></label>
			<?= Html::input('text','mejora_'.key($arregloMejoras),$$strVar,['id'=>'mej-'.$strVar, 'class'=>'form-control', 'style'=>'width:100%','onkeypress'=>'return justNumbers( event )','maxlength'=>'1', 'hidden'=>true]); ?>
			<td width="10px"></td>

			<?php
			$contador++;
		}
		
	}
	?>
	
	</tr>
</table>
<br />
<br />

<table>
	<tr>
		<td width="60px"><label>Categoría</label></td>
		<td>
			<?php if ( $model->existe_inm_mej_tcat ) { ?>
				
				<?= Html::dropDownList('mej-categoria', $cat, utb::getAux('inm_mej_tcat'), ['id'=>'mej-categoria', 'class'=>'form-control', 'style'=>'width:100%'])?>
			<?php }else { ?>
				
				<?= Html::input('text','mej-categoria',$cat,['id'=>'mej-categoria', 'class'=>'form-control', 'style'=>'width:30px', 'maxlength'=>'2']); ?>
			<?php } ?>	
		</td>
	</tr>
</table>

</div>

<br />
<table width="100%">
		<tr>
			<td width="100%">
					
				<?php 
				
				Pjax::begin(['id'=>'errorMejora']);
				
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

	<div class="text-center">
	<?php
		if (utb::getExisteProceso(3071))
		{
			switch ( $consulta )
			{
				case 1:
					echo Html::Button('Grabar',['class' => 'btn btn-success', 'onClick' => 'btAceptarFrente();']);
					break;
					
				case 2:
					echo Html::Button('Grabar',['class' => 'btn btn-success', 'onClick' => 'btAceptarFrente();']);
					break;
				
				case 3:
					echo Html::Button('Eliminar',['class' => 'btn btn-danger', 'onClick' => 'btAceptarFrente();']);
					break;
			}
		}
		
		?>
		
		&nbsp;&nbsp;
		<?= Html::button( ($consulta == 0 ? 'Aceptar' : 'Cancelar' ), [
				'class' => 'btn btn-primary',
				'onclick' => '$("#modal-nuevaMejora").modal("hide")',
			]);
		?>
		
		</div>
		
		<!-- INICIO Mensajes Error -->	
		<div id="inm_mejoras_errorSummary" class="error-summary" style="display:none;margin-top: 8px; margin-right: 0px">
			
			<ul>
			</ul>
			
		</div>
		<!-- FIN Mensajes Error -->
		
		<?php

	if( $consulta == 2)
	{
		?>
		<script>
			$("#mej-poligono").attr('disabled','disabled');
		</script>
		
		<?php
	}

	//Cuando se dibuja el form en delete o para consultar datos
	if ( $consulta == 3 || $consulta == 0 ) 
	{
		?>
		
		<script>
		$("#mej-poligono").attr('disabled','disabled');
		$("#mej-anioDesde").attr('disabled','disabled');
		$("#mej-cuotaDesde").attr('disabled','disabled');
		$("#mej-anioHasta").attr('disabled','disabled');
		$("#mej-cuotaHasta").attr('disabled','disabled');
	    $("#mej-origen").attr('disabled','disabled');					    
	    $("#mej-form").attr('disabled','disabled');
	    $("#mej-nivel").attr('disabled','disabled');
	    $("#mej-dest").attr('disabled','disabled');					    
	    $("#mej-obra").attr('disabled','disabled');				   
	    $("#mej-anio").attr('disabled','disabled');
	    $("#mej-est").attr('disabled','disabled');				   
	    $("#mej-supcub").attr('disabled','disabled');
	    $("#mej-supsemi").attr('disabled','disabled');
	    $("#mej-plantas").attr('disabled','disabled');
	    $("#mej-categoria").attr('disabled','disabled');
	    $("#mej-item01").attr('disabled','disabled');
	    $("#mej-item02").attr('disabled','disabled');
	    $("#mej-item03").attr('disabled','disabled');
	    $("#mej-item04").attr('disabled','disabled');
	    $("#mej-item05").attr('disabled','disabled');
	    $("#mej-item06").attr('disabled','disabled');
	    $("#mej-item07").attr('disabled','disabled');
	    $("#mej-item08").attr('disabled','disabled');
	    $("#mej-item09").attr('disabled','disabled');
	    $("#mej-item10").attr('disabled','disabled');
	    $("#mej-item11").attr('disabled','disabled');
	    $("#mej-item12").attr('disabled','disabled');
	    $("#mej-item13").attr('disabled','disabled');
	    $("#mej-item14").attr('disabled','disabled');
	    $("#mej-item15").attr('disabled','disabled');
		</script>
		
		<?php
	}
	
	?>
	<script>
	function btAceptarFrente()
	{
		var fecha = new Date(),
			poligono = $("#mej-poligono").val(),
			anioDesde = $("#mej-anioDesde").val(),
			cuotaDesde = $("#mej-cuotaDesde").val(),
			anioHasta = $("#mej-anioHasta").val(),
			cuotaHasta = $("#mej-cuotaHasta").val(),
			supcub = $("#mej-supcub").val(),
			semicub = $("#mej-semicub").val(),
			obra = $("#mej-obra option:selected").val(),
			destino = $("#mej-dest option:selected").val(), 
			anio = $("#mej-anio").val(),
			nivel = $("#mej-nivel").val(),
			error = new Array();
		
		if ( poligono == "" || poligono == 0)
		{
			error.push( "Ingrese un Polígono." );
		}
		if ( poligono < 1 || poligono > 99 )
			error.push( "Polígono debe ser de 1 a 99." );
			
		if ( anio.length < 4 )	
			error.push( "Año mal ingresado." );
			
		if ( ( anioDesde == 0 || cuotaDesde == 0 ) || ( anioHasta == 0 || cuotaHasta == 0 ) )
		{
			
			if ( anioDesde == 0 || cuotaDesde == 0 )
			{
				error.push( "Ingrese el Rango de Períodos Desde." );
			} 
			
			if ( anioHasta == 0 || cuotaHasta == 0 )
			{
				error.push( "Ingrese el Rango de Períodos Hasta." );
			} 
		
		} else 
		{
			if ( anioDesde.length < 4 )
			{
				$("#mej-anioDesde").val('');
				
				error.push( "El Rango de Períodos Desde es incorrecto." );
			}
			
			if ( anioHasta.length < 4 )
			{
				$("#mej-anioHasta").val('');
				
				error.push( "El Rango de Períodos Hasta es incorrecto." );
					
			} 
			
			if ( cuotaDesde < 0 || cuotaDesde > 12 )
				error.push( "La cuota del Periodo desde debe ser de 0 a 12." );
				
			if ( cuotaHasta < 0 || cuotaHasta > 999 )
				error.push( "La cuota del Periodo hasta debe ser de 0 a 99." );	
			
			if ( ( anioDesde*1000 + cuotaDesde ) > ( anioHasta*1000 + cuotaHasta ) )
			{	
				$("#mej-anioHasta").val('');
				$("#mej-cuotaHasta").val('');
				
				error.push( "El Rango de Períodos es incorrecto." );
			}
			
			if ( ( destino <= 10 && obra < 3 ) && ( anio < 1800 || anio > fecha.getFullYear() ) )
			{		
				$("#mej-anio").val('');
				
				error.push( "El Año de Construcción es obligatorio." );
			} 
			
			if ((destino <= 10 && obra < 3 ) && (nivel < -5 || nivel > 999))
			{
				$("#mej-nivel").val('');
				
				error.push( "El Nivel del Polígono es incorrecto." );
			
			} 
		}
			
		if ( obra == 0 || obra == '' || obra == null )
		{
			error.push( "El Tipo de Obra es obligatorio." );
		} 
		
		if ( parseInt( supcub ) < 0 || parseInt( supcub ) > 9999999 )
		{
			$("#mej-supcub").val('');
			
			error.push( "La Superficie Cubierta es incorrecta." );
		}

		if ( parseInt( semicub ) < 0 || parseInt( semicub ) > 9999999 )
		{
			$("#mej-semicub").val('');
			
			error.push( "La Superficie Semicubierta es incorrecta." );
		} 
		
		if ( error.length == 0 ) 
		{
			$.pjax.reload({	
				container:"#manejoMejoras",
				method:"POST",
				data:{	
					pol:$("#mej-poligono").val(),
				    perdesde:parseInt(anioDesde)*1000 + parseInt(cuotaDesde),
				    perdesde_nom:anioDesde + '-' + cuotaDesde,
				    perhasta:(parseInt(anioHasta)*1000 + parseInt(cuotaHasta)),
				    tori:$("#mej-origen").val(),
				    tori_nom:"",
				    tform:$("#mej-form option:selected").val(),
				    nivel:$("#mej-nivel").val(),
				    tdest:$("#mej-dest option:selected").val(),
				    tdest_nom:$("#mej-dest option:selected").html(),
				    tobra:$("#mej-obra option:selected").val(),
				    tobra_nom:$("#mej-obra option:selected").html(),
				    anio:$("#mej-anio").val(),
				    est:$("#mej-est option:selected").val(),
				    est_nom:$("#mej-est option:selected").html(),
				    supcub:$("#mej-supcub").val(),
				    supsemi:$("#mej-supsemi").val(),
				    plantas:$("#mej-plantas").val(),
				    cat:$("#mej-categoria").val(),
				    item01:$("#mej-item01").val(),
				    item02:$("#mej-item02").val(),
				    item03:$("#mej-item03").val(),
				    item04:$("#mej-item04").val(),
				    item05:$("#mej-item05").val(),
				    item06:$("#mej-item06").val(),
				    item07:$("#mej-item07").val(),
				    item08:$("#mej-item08").val(),
				    item09:$("#mej-item09").val(),
				    item10:$("#mej-item10").val(),
				    item11:$("#mej-item11").val(),
				    item12:$("#mej-item12").val(),
				    item13:$("#mej-item13").val(),
				    item14:$("#mej-item14").val(),
				    item15:$("#mej-item15").val(),
					envio:1,
				},
			});
						
		} else 
		{
			mostrarErrores( error, "#inm_mejoras_errorSummary" );
		}
	}
	</script>

<?php

Pjax::end();

?>
