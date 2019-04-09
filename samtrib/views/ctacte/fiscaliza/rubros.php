<?php

/**
 * Vista que será mostrada como ventana modal para "Rubros" en "Fiscalización".
 * Permitirá agregar nuevos Rubros y modificar Rubros existentes.
 * 
 * @param $model Modelo correspondiente a ComerRubro
 * @param $action Variable que determina el origen de la llamada a la vista
 * 
 * Se puede seleccionar tributo solo cuando se inserta; al modificar y eliminar no se pueden modificar ni tributo ni rubro.
 */
 
use yii\helpers\Html;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use yii\web\Session;
use yii\bootstrap\Alert;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use app\models\objeto\ComerRubro;
		
 /*
  * Me llegan:
  * 
  * 	$rubro == Array con los datos del rubro seleccionado.
  * 	$action == Identificador para conocer si se llega desde create o update.
  * 		$action = 1 => Create
  * 		$action = 2 => Update
  * 		$action = 3 => Delete
  *		
  *		
  */
  
?>

<div id="div_rubro" class="form-panel">

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 

			Pjax::begin(['id'=>'errorFormFiscalizacionRubro','enablePushState' => false, 'enableReplaceState' => false]);
				
				$mensaje = Yii::$app->request->get('mensajeRubro','');
				$m = Yii::$app->request->get('m',2);
				
				if($mensaje != "")
				{ 
			
			    	Alert::begin([
			    		'id' => 'AlertaFormFiscalizacionRubro',
						'options' => [
			        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
			        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
			    		],
					]);
			
					echo $mensaje;
							
					Alert::end();
					
					echo "<script>window.setTimeout(function() { $('#AlertaFormFiscalizacionRubro').alert('close'); }, 5000)</script>";
				 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<?php

//INICIO Bloque de código para ingresar los datos en el arreglo en memoria
Pjax::begin(['id' => 'PjaxRubros']);
	
	$actionPjax = Yii::$app->request->post('txAction',0);

	/*
	 * $action == 1 => Inserta un nuevo rubro
	 * 
	 * $action == 2 => Actualiza un rubro
	 * 
	 * $action == 3 => Elimina un rubro
	 */
	 
	 $session = new Session;
	 $session->open();
	 
	 //Obtengo el arreglo de rubros en memoria
 	 $arrayMemoria = $session->get('arregloRubros',[]);
	 
	 $nuevoRubro = Yii::$app->request->post('datos',[]);
	 
	 if ($actionPjax == 1)
	 {
	 	/*
    	 * Para identificar cada elemento del arreglo, uno la lera 'r' con el ID de rubro. 
    	 */
    	$perdesde = (intval($nuevoRubro['adesde']) * 1000) + intval($nuevoRubro['cdesde']);
    	$perhasta = (intval($nuevoRubro['ahasta']) * 1000) + intval($nuevoRubro['chasta']);
    	 
	 	$arrayTemporal = [
		 	'subcta' => $nuevoRubro['subcta'],
			'trib_id' => $nuevoRubro['trib_id'],
			'trib_nom' => utb::getCampo('trib','trib_id = ' . $nuevoRubro['trib_id'], 'nombre_redu'),
			'rubro_id' => $nuevoRubro['rubro_id'],
			'rubro_nom' => utb::getCampo('rubro','rubro_id = ' . $nuevoRubro['rubro_id'], 'nombre'),
			'adesde' => $nuevoRubro['adesde'],
			'cdesde' => $nuevoRubro['cdesde'],
			'nperdesde' => $perdesde,
			'nperhasta' => $perhasta,
			'perdesde' => substr($perdesde,0,4) . '-' . substr($perdesde,4,3), 
			'perhasta' => substr($perhasta,0,4) . '-' . substr($perhasta,4,3),
			'ahasta' => $nuevoRubro['ahasta'],
			'chasta' => $nuevoRubro['chasta'],
			'cant' => $nuevoRubro['cant'],
			'porc' => $nuevoRubro['porc'],
			'expe' => $nuevoRubro['expe'],
			'obs' => $nuevoRubro['obs'],
			'est' => 'A',
		];
		
		/*
    	 * Para identificar cada elemento del arreglo, uno la letra 'r' con el ID de rubro. 
    	 */
    	 
		$clave = 'r'.$arrayTemporal['rubro_id'];	//Genero la clave. 
		$arregloTemporal[$clave] = $arrayTemporal;
    	
	 	if ( $arrayMemoria != [] )
	 	{
	 		//Verificar que el rubro que se quiere ingresar no se encuentre cargado en memoria
	 		//array_key_exists devuelve true si encuentra la key en el arreglo
			if ( count($arrayMemoria) > 0 && array_key_exists($clave, $arrayMemoria) )
			{
				?>
					<script>
						mostrarErrores( ["El rubro que intenta agregar ya se ingresó."], "#fiscalizaRubro_errorSummary" );
					</script>
	
				<?php
			} else 
			{
		    	$arrayMemoria = array_merge($arrayMemoria, $arregloTemporal);
		    	
		    	$session->set('arregloRubros',$arrayMemoria);
		    	$arrayMemoria = $session->get('arregloRubros',[]);
		    	
		    	//Actualizar la grilla y cerrar la ventana
	 			echo '<script>actualizaYCierra();</script>';
			}
	 	}
	 	
	 } else if ($actionPjax == 2) //Actualizar un rubro en memoria
	 {
	 	/*
    	 * Para identificar cada elemento del arreglo, uno la lera 'r' con el ID de rubro. 
    	 */
    	$perdesde = (intval($nuevoRubro['adesde']) * 1000) + intval($nuevoRubro['cdesde']);
    	$perhasta = (intval($nuevoRubro['ahasta']) * 1000) + intval($nuevoRubro['chasta']);
		 
	 	$arrayTemporal = [
		 	'subcta' => $nuevoRubro['subcta'],
			'trib_id' => $nuevoRubro['trib_id'],
			'trib_nom' => utb::getCampo('trib','trib_id = ' . $nuevoRubro['trib_id'], 'nombre_redu'),
			'rubro_id' => $nuevoRubro['rubro_id'],
			'rubro_nom' => utb::getCampo('rubro','rubro_id = ' . $nuevoRubro['rubro_id'], 'nombre'),
			'adesde' => $nuevoRubro['adesde'],
			'cdesde' => $nuevoRubro['cdesde'],
			'nperdesde' => $perdesde,
			'nperhasta' => $perhasta,
			'perdesde' => substr($perdesde,0,4) . '-' . substr($perdesde,4,3), 
			'perhasta' => substr($perhasta,0,4) . '-' . substr($perhasta,4,3),
			'ahasta' => $nuevoRubro['ahasta'],
			'chasta' => $nuevoRubro['chasta'],
			'cant' => $nuevoRubro['cant'],
			'porc' => $nuevoRubro['porc'],
			'expe' => $nuevoRubro['expe'],
			'obs' => $nuevoRubro['obs'],
			'est' => 'A',
		];
		
		$clave = 'r'.$arrayTemporal['rubro_id'];
		$arregloTemporal[$clave] = $arrayTemporal;
    	
    	$arrayMemoria = array_merge($arrayMemoria, $arregloTemporal);
    	
    	$session->set('arregloRubros',$arrayMemoria);
    	
    	//Actualizar la grilla y cerrar la ventana
		echo '<script>actualizaYCierra();</script>';
		
	 } else if ($actionPjax == 3)
	 {
				
		$clave = 'r'.$nuevoRubro['rubro_id'];
		
		unset($arrayMemoria[$clave]);
		
		$session->set('arregloRubros',$arrayMemoria);
			
		//Actualizar la grilla y cerrar la ventana
		echo '<script>actualizaYCierra();</script>';

	 }
	 
	 $session->close();
	 
Pjax::end();

echo Html::input('hidden','txAction',$action, ['id' => 'fiscalizaRubro_txAction']);
?>
<table>
	<tr>
		<td width="50px"><label for="subcta">Nº Suc.:</label></td>
		<td>
			<?php
				
				/*
				 * Si el el atributo uso_subcta de tributo es:
				 * 	
				 * 		0 => Se selecciona Subcuenta = 1
				 * 		
				 * 		1 => Se muestra un combobox donde se listan las sucursales del comercio
				 */ 
				Pjax::begin(['id' => 'PjaxTipoSucursal', 'enableReplaceState' => false, 'enablePushState' => false]);
				
					$tipo = Yii::$app->request->get('tipo_sucursal',0);
					
					if ($tipo == 0)
					{
						echo Html::input('text','ComerRubro[subcta]',($rubro['subcta'] != '' ? $rubro['subcta'] : 0),[
							'id' => 'fiscalizaRubro_txSubcta',
							'class' => 'form-control solo-lectura',
							'style' => 'width:40px;text-align:center',
							
						]); 
					} else 
					{
						echo Html::dropDownList('ComerRubro[subcta]', ($rubro['subcta'] != '' ? $rubro['subcta'] : 1), utb::getAux('comer_suc','subcta','subcta',0,'obj_id = ' . $rubro['obj_id']), [
								'class' => 'form-control', 
								'id' => 'filtroRubroSubCta',
							]); 
					}
					
				Pjax::end();
					
			?>
		</td>
		<td width="20px"></td>
		<td><label for="fiscalizaRubro_dlTributo">Tributo:</label></td>
		<td>
			<?= Html::dropDownList('ComerRubro[trib_id]',$rubro['trib_id'],utb::getAux('trib','trib_id','nombre',0,'tobj = 2 AND tipo = 2'),[
					'id' => 'fiscalizaRubro_dlTributo',
					'class' => 'form-control' . ($action != 1 ? ' solo-lectura' : ''),
					'style' => 'width:250px',
					'onchange' => 'fiscalizaRubro_cambiaTributo()',
				]); 
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
	<td width="50px"><label for="fiscalizaRubro_txRubroID">Rubro:</label></td>
		<td>
			<?= Html::input('text','ComerRubro[rubro_id]',$rubro['rubro_id'],[
					'id' => 'fiscalizaRubro_txRubroID',
					'class' => 'form-control' . ($action != 1 ? ' solo-lectura' : ''),
					'style' => 'width:60px;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
					'onchange' => 'fiscalizaRubro_cambiaRubro()',
				]); 
			?>
		</td>
		
		<!-- INICIO Botón Búsqueda Rubro -->
		<td width="20px">
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>', [
					'id' => 'fiscalizaRubro_btRubro',
					'class' => 'bt-buscar', 
					'disabled' => ($action !== 1),
					'onclick' => '$("#busquedaRubro").toggle();',
				]);
			?>
		</td>
		<!-- FIN Botón Búsqueda Rubro -->
		
		<td>
			<?= Html::input('text','txRubroNom',utb::getCampo('rubro','rubro_id = ' . ($rubro['rubro_id'] != '' ? $rubro['rubro_id'] : 0),'nombre'),[
					'id' => 'fiscalizaRubro_txRubroNom',
					'class' => 'form-control solo-lectura',
					'style' => 'width:300px;text-align:left',
				]); 
			?>
		</td>
	</tr>
</table>

<?php
	
	//INICIO Bloque código actualiza nombre rubro
	Pjax::begin(['id' => 'PjaxCambiaRubro', 'enableReplaceState' => false, 'enablePushState' => false]);
		
		$rubroID = Yii::$app->request->get('cambia_rubroID',0);
		
		if ($rubroID != 0)
		{
			$rubro_nom = utb::getCampo('rubro','rubro_id = ' . $rubroID,'nombre');
			
			if ($rubro_nom != '')
			{
				?>
					<script>
						$("#PjaxCambiaRubro").on("pjax:end", function() {
							$("#fiscalizaRubro_txRubroNom").val("<?= $rubro_nom ?>");
							$("#fiscalizaRubro_txCant").focus();
							$("#PjaxCambiaRubro").off("pjax:end");	
						});
						
					</script>
				
				<?php
			} else
			{
				?>
					<script>
						$("#PjaxCambiaRubro").on("pjax:end", function() {
							$("#fiscalizaRubro_txRubroID").val("");
							$("#fiscalizaRubro_txRubroNom").val("");
							$("#fiscalizaRubro_txRubroID").focus();
							$("#PjaxCambiaRubro").off("pjax:end");	
						});
						
					</script>
				
				<?php
			}
				
		}
		
	Pjax::end();
	//FIN Bloque código actualiza nombre rubro
?>
<table>
	<tr>
		<td width="50px"><label for="fiscalizaRubro_txCant">Cant:</label></td>
		<td>
			<?= Html::input('text','ComerRubro[cant]',$rubro['cant'],[
					'id' => 'fiscalizaRubro_txCant',
					'class' => 'form-control' . ( $action == 3 ? ' solo-lectura' : '' ),
					'style' => 'width:60px;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
				]); 
			?>
		</td>
		<td width="20px"></td>
		<td><label for="fiscalizaRubro_txPerdesdeAnio">Desde:</label></td>
		<td>
			<?= Html::input('text','ComerRubro[adesde]',$rubro['adesde'],[
					'id' => 'fiscalizaRubro_txPerdesdeAnio',
					'class' => 'form-control' . ( $action == 3 ? ' solo-lectura' : '' ),
					'style' => 'width:40px;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
					'maxlength' => 4,
				]); 
			?>
			<?= Html::input('text','ComerRubro[cdesde]',( $action != 1 ? intval( $rubro['cdesde'] ) : '' ),[
					'id' => 'fiscalizaRubro_txPerdesdeCuota',
					'class' => 'form-control' . ($action == 3 ? ' solo-lectura' : ''),
					'style' => 'width:30px;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
					'maxlength' => 3,
				]); 
			?>
		</td>
		<td width="20px"></td>
		<td><label for="fiscalizaRubro_txPerhastaAnio">Hasta:</label></td>
		<td>
			<?= Html::input('text','ComerRubro[ahasta]',$rubro['ahasta'],[
					'id' => 'fiscalizaRubro_txPerhastaAnio',
					'class' => 'form-control' . ( $action == 3 ? ' solo-lectura' : '' ),
					'style' => 'width:40px;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
					'maxlength' => 4,
				]); 
			?>
			<?= Html::input('text','ComerRubro[chasta]',( $action != 1 && intval( $rubro['chasta'] ) != 0 ? intval( $rubro['chasta'] ) : '' ),[
					'id' => 'fiscalizaRubro_txPerhastaCuota',
					'class' => 'form-control' . ($action == 3 ? ' solo-lectura' : ''),
					'style' => 'width:30px;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
					'maxlength' => 3,
				]); 
			?>
		</td>
		<td width="20px"></td>
		<td><label for="fiscalizaRubro_txPorc">Porc:</label></td>
		<td>
			<?= Html::input('text','ComerRubro[porc]',$rubro['porc'],[
					'id' => 'fiscalizaRubro_txPorc',
					'class' => 'form-control' . ($action == 3 ? ' solo-lectura' : ''),
					'style' => 'width:50px;text-align:center',
					'onkeypress' => 'return justDecimal($(this).val(),event)',
					'onchange' => 'validarPorcentaje()',
					'maxlenght' => 5,
				]); 
			?>
		</td>
	</tr>
</table>
<table>
	<tr>
		<td width="50px"><label for="fiscalizaRubro_txExpe">Expe:</label></td>
		<td>
			<?= Html::input('text','ComerRubro[expe]',$rubro['expe'],[
					'id' => 'fiscalizaRubro_txExpe',
					'class' => 'form-control' . ($action == 3 ? ' solo-lectura' : ''),
					'style' => 'width:100px;text-align:left',
					'maxlenght' => 12,
				]); 
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label for="fiscalizaRubro_txObs">Obs:</label></td>
		<td>
			<?= Html::textarea('ComerRubro[obs]',$rubro['obs'],[
					'id' => 'fiscalizaRubro_txObs',
					'class' => 'form-control' . ($action == 3 ? ' solo-lectura' : ''),
					'style' => 'width:480px;max-width:480px;height:60px;max-height:120px;',
				]);
			?>
		</td>
	</tr>
</table>

<!-- INICIO DIV Búsqueda Rubro -->
<div>
	<table width="100%">
		<tr>
			<td>
				<div class="form" style="padding:15px; display:none; margin-top:15px; margin-right: 10px" id="busquedaRubro">
					<?= Html::button('&times;', ['class' => 'btn btn-sm btn-danger pull-right', 'onclick' => '$("#fiscalizaRubro_btRubro").click();']); ?>
				
				<h3><label>Buscar Rubro</label></h3>
				
				<?php
				
				Pjax::begin(['id' => 'PjaxBuscadorRubro', 'enableReplaceState' => false, 'enablePushState' => false]);
				
					$trib = intval(Yii::$app->request->get("fiscaliza_tributo", 0));
					$opciones = [];
	
					if($trib > 0)
						$opciones = ['criterio' => "trib_id = $trib"];

					echo $this->render(
						'//taux/auxbusca',
						array_merge( 
						['id' => 'buscarRubro',
						'tabla' => 'v_rubro',
						'campocod' => 'rubro_id',
						'camponombre' => 'nombre',
						'idcampocod' => 'fiscalizaRubro_txRubroID',
						'idcamponombre' => 'fiscalizaRubro_txRubroNom',
						'claseGrilla' => 'grillaGrande'
						],
						$opciones
						)
					);	

				Pjax::end();
				
				?>
				</div>
			</td>
		</tr>
	</table>
</div>
<!-- FIN DIV Búsqueda Rubro -->


</div>

<!-- INICIO DIV Botones -->
<div id="div_botones" class="text-center">
	<?= Html::button('Aceptar',[
			'id' => 'fiscalizaRubro_btAceptar',
			'class' => 'btn btn-success',
			'onclick' => 'fiscalizaRubro_btAceptar()',
		]); 
	?>
	&nbsp;&nbsp;
	<?= Html::button('Cancelar', [
			'id' => 'fiscalizaRubro_btCancelar',
			'class' => 'btn btn-primary',
			'onclick' => 'fiscalizaRubro_btCancelar()',
		]); 
	?>
</div>
<!-- FIN DIV Botones -->

<!-- INICIO DIV Errores -->
<div id="fiscalizaRubro_errorSummary" class="error-summary", style="display:none;margin-top: 8px; margin-right: 15px">
		
	<ul>
	</ul>
	
</div>
<!-- FIN DIV Errores -->

<script>
function fiscalizaRubro_cambiaTributo()
{
	$("#fiscalizaRubro_txRubroID").val("");
	$("#fiscalizaRubro_txRubroNom").val("");
	
	var trib = $("#fiscalizaRubro_dlTributo").val();
	
	$.pjax.reload({
		container:"#PjaxBuscadorRubro",
		type:"GET",
		replace:false,
		push:false,
		data:{
			fiscaliza_tributo:trib,
	}});
}

function fiscalizaRubro_cambiaRubro()
{
	var rubros = $("#fiscalizaRubro_txRubroID").val();

	if (rubros == '' || rubros == null)
		$("#fiscalizaRubro_txRubroNom").val();
		
	else
		$.pjax.reload({
			container: "#PjaxCambiaRubro",
			type: "GET",
			replace: false,
			push: false,
			data:{
				cambia_rubroID:rubros,
		}});
}

function validarPorcentaje()
{
	var porc = parseFloat($("#fiscalizaRubro_txPorc").val()),
		error = new Array();
	
	if (porc > 100)
	{
		error.push( "Porcentaje mal ingresado." );
		$("#fiscalizaRubro_txPorc").val(0);
	}
	
	mostrarErrores( error, "#fiscalizaRubro_errorSummary" );
}

function fiscalizaRubro_btAceptar()
{
	var subcta = $("#fiscalizaRubro_txSubcta").val();
		trib = $("#fiscalizaRubro_dlTributo").val(),
		rubro = $("#fiscalizaRubro_txRubroID").val(),
		cant = $("#fiscalizaRubro_txCant").val(),
		adesde = $("#fiscalizaRubro_txPerdesdeAnio").val(),
		cdesde = $("#fiscalizaRubro_txPerdesdeCuota").val(),
		ahasta = $("#fiscalizaRubro_txPerhastaAnio").val(),
		chasta = $("#fiscalizaRubro_txPerhastaCuota").val(),
		porcentaje = $("#fiscalizaRubro_txPorc").val(),
		expe = $("#fiscalizaRubro_txExpe").val(),
		obs = $("#fiscalizaRubro_txObs").val(),
		action = $("#fiscalizaRubro_txAction").val(),
		
		datos = new Object(),
		error = new Array();
	
	if (rubro == '')
		error.push( "Ingrese un rubro." );
		
	if (cant == '')
		error.push( "Ingrese una cantidad." );
		
	if (adesde == '' && cdesde == '')
		error.push( "Ingrese un período desde." );
		
	if ((adesde != '' && cdesde == '') || (adesde == '' && cdesde != ''))
		error.push( "Ingrese un período desde válido." );
	
	if ( ( ahasta != '' && chasta == '' ) || ( ahasta == '' && chasta != '' ) )
		error.push( "Ingrese un período hasta válido." );
	
	if ( ahasta != '' && chasta != '' )
	{
		if (((parseInt(adesde) * 1000) + cdesde) > ((parseInt(ahasta) * 1000) + chasta))
			error.push( "Rangos de período mal ingresado." );
	} 
	
	if (porcentaje == '')
		error.push( "Ingrese un porcentaje." );
		
	if (porcentaje > 100)
		error.push( "Ingrese un porcentaje válido." );
		
	if ( error.length == 0 )
	{
		if ( ahasta == '' && chasta == '' )
		{
			$("#fiscalizaRubro_txPerhastaAnio").val(9999);
			$("#fiscalizaRubro_txPerhastaCuota").val(99);
		}
		
		datos.subcta = subcta;
		datos.trib_id = trib;
		datos.rubro_id = rubro;
		datos.cant = cant;
		datos.adesde = adesde;
		datos.cdesde = cdesde;
		datos.ahasta = ahasta;
		datos.chasta = chasta;
		datos.porc = porcentaje;
		datos.expe = expe;
		datos.obs = obs;
		
	
		$.pjax.reload({
			container: "#PjaxRubros",
			type: "POST",
			replace: false,
			push: false,
			data: {
				datos:datos,
				txAction:action,
			}
		})
	
	} else
		mostrarErrores( error, "#fiscalizaRubro_errorSummary" );
}

function actualizaYCierra()
{
	$.pjax.reload({
		container: "#PjaxGrillaRubrosFiscaliza",
		replace: false,
		type: "GET",
		push:false,
	});
		
	$("#PjaxGrillaRubrosFiscaliza").on("pjax:end", function () {
		
		$("#ModalRubro").toggle();
		$("#PjaxGrillaRubrosFiscaliza").off("pjax:end");
	});
}

function fiscalizaRubro_btCancelar()
{
	$("#ModalRubro").toggle();
}

$("#FormRubros").submit(function(event) {
	$.pjax.submit(event, "#PjaxRubros");
});
</script>
