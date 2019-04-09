<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use yii\web\Session;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;


/**
 * Forma que se dibuja cuando se quiere agregar un rubro a una DJ
 *
 * Recibo:
 * 			=> $tobj 		-> Tipo de objeto
 * 			=> $obj_id 		-> Id del objeto
 * 			=> $anio		-> Año
 * 			=> $cuota		-> Cuota
 * 			=> $fiscaliza	-> Fiscaliza
 * 			=> $fchpresenta	-> Fecha de Presentación
 */

if (!isset($obj_id)) $obj_id = '';
if (!isset($fchpresenta)) $fchpresenta = '';
if (!isset($anio)) $anio = '';
if (!isset($cuota)) $cuota = '';

if (!isset($tobj)) $tobj = '';
if (!isset($subcta)) $subcta = '';

$session = new Session;

$session->open();

$fiscaliza = $session->get('FiscalizaActiva',0);

$session->close();

echo Html::input('hidden','txObj_id',$obj_id,['id'=>'txRubroObj_id']); //Almaceno el código del objeto
echo Html::input('hidden','txFchpresenta',$fchpresenta,['id'=>'txRubroFchpresenta']); //Almaceno la fecha de presentación
echo Html::input('hidden','txAnio',$anio,['id'=>'txRubroAnio']); //Almaceno el año
echo Html::input('hidden','txCuota',$cuota,['id'=>'txRubroCuota']); //Almaceno la cuota
echo Html::input('hidden','txFiscaliza',$fiscaliza,['id'=>'txRubroFiscaliza']); //Almaceno la variable fiscaliza
echo Html::input('hidden','txTObj',$tobj,['id'=>'txRubroTObj']); //Almaceno el tipo de objeto

Pjax::begin(['id'=>'formModalAgregaRubro']);

	$tobj = Yii::$app->request->post('tobj', '');
	$obj_id = Yii::$app->request->post('obj_id','');
	$anio = Yii::$app->request->post('anio','');
	$cuota = Yii::$app->request->post('cuota','');
	$fiscaliza = Yii::$app->request->post('fiscaliza','');
	$fchpresenta = Yii::$app->request->post('fchpresenta','');

	$condTributo = "tipo = 2 AND tobj = " . $tobj . " AND trib_id IN " .
		"(SELECT distinct r.Trib_id " .
		"FROM Objeto o " .
		"LEFT Join Objeto_Rubro cr ON o.obj_id = cr.obj_id AND cr.est= 'A' " .
		"INNER Join Rubro r ON cr.Rubro_id = r.Rubro_id " .
		"INNER Join objeto_test te ON o.est = te.cod " .
		"WHERE o.Obj_id = '" . $obj_id . "' AND " . $anio . "*1000+".$cuota." between cr.perdesde AND cr.perhasta AND te.estgral='A')";

    echo $condTributo;

//INICIO Bloque código calcular rubro
Pjax::begin(['id' => 'agregarRubro_calcularRubro']);

	if (isset($_POST['rubro_rubro']) && $_POST['rubro_rubro'] != 0)
	{
		//Obtengo los parámetros
		$trib_id = Yii::$app->request->post('trib_rubro',0);
		$rubro_id = Yii::$app->request->post('rubro_rubro',0);
		$obj_id = Yii::$app->request->post('obj_id','');
		$anio = Yii::$app->request->post('anio','');
		$cuota = Yii::$app->request->post('cuota','');
		$fiscaliza = Yii::$app->request->post('fiscaliza','');
		$fchpresenta = Yii::$app->request->post('fchpresenta','');
		$subcta = Yii::$app->request->post('subcta',0);
		$base = Yii::$app->request->post('base',0);
		$cant = Yii::$app->request->post('cant',0);

		var_dump( $_POST );

		echo $cant;
		echo $base;

		//Realizo el cálculo del rubro
		$array = $model->calculaRubro($rubro_id,$base,$cant,$anio,$cuota,$fchpresenta,$obj_id,$subcta);

		//Obtengo los datos de la consulta
		$formCalculo = $array['dataRubro']['tcalculo_nom'];	//Fórmula de Cálculo
		$tminimo = $array['dataRubro']['tminimo'];
		$alicuota = ($array['dataRubro']['alicuota']) * 100;
		$minimo = $array['dataRubro']['minimo'];
		$monto = $array['dataMonto']['montorubro'];
		$monto = number_format( round($monto * 100) / 100 , 2, '.', '' );

		echo '<script>$("#ddjj_rubro_txFormulaCalculo").val("'.$formCalculo.'")</script>'; //Seteo Fórmula de Cálculo
		if ($tminimo == 3 || $tminimo == 4 || $tminimo == 8) //Habilito o deshabilito cantidad
		{
			//Deshabilitar Cant.
			echo '<script>$("#ddjj_rubro_txCantidad").removeAttr("readOnly")</script>';
		} else
		{
			echo '<script>$("#ddjj_rubro_txCantidad").attr("readOnly",true)</script>';
		}
		echo '<script>$("#ddjj_rubro_txAlicuota").val("'.$alicuota.'")</script>'; //Seteo Alícuota
		echo '<script>$("#ddjj_rubro_txMinimo").val("'.$minimo.'")</script>'; //Seteo Mínimo
		echo '<script>$("#ddjj_rubro_txMonto").val("'.$monto.'")</script>'; //Seteo Monto
		echo '<script>$("#txRubroObj_id").val("'.$obj_id.'")</script>'; //Seteo Objeto ID
		echo '<script>$("#txRubroFchpresenta").val("'.$fchpresenta.'")</script>'; //Seteo Fecha presentación
		echo '<script>$("#txRubroAnio").val("'.$anio.'")</script>'; //Seteo Año
		echo '<script>$("#txRubroCuota").val("'.$cuota.'")</script>'; //Seteo Cuota
		echo '<script>$("#txRubroFiscaliza").val("'.$fiscaliza.'")</script>'; //Seteo Fiscaliza
	}

	if (isset($_POST['rubro_rubro']) && $_POST['rubro_rubro'] == 0)
	{
		echo '<script>limpiarEdit()</script>';
	}


Pjax::end();
//FIN Bloque código calcular rubro

//INICIO Bloque código cargar rubro
Pjax::begin(['id'=>'PjaxCargarRubro']);

	//Código para agregar un rubro al arreglo en sesión

	if(isset($_POST['crTrib_id']) && $_POST['crTrib_id'] != '')
	{
		//Obtengo los datos que viajan por POST
		$crTrib_id = $_POST['crTrib_id'];
		$crTrib_nom = $_POST['crTrib_nom'];
		$crRubro_id = intval($_POST['crRubro_id']);
		$crRubro_nom = $_POST['crRubro_nom'];
		$crBase = $_POST['crBase'];
		$crCant = $_POST['crCant'];
		$crAlicuota = $_POST['crAlicuota'];
		$crMinimo = $_POST['crMinimo'];
		$crMonto = $_POST['crMonto'];
		$crObj_id = $_POST['crObj_id'];
		$crFchpresenta = $_POST['crFchpresenta'];
		$crAnio = $_POST['crAnio'];
		$crCuota = $_POST['crCuota'];
		$crFiscaliza = $_POST['crFiscaliza'];

		$tipo = $model->getTipoRubroObjeto($crObj_id,$crRubro_id);
		$subcta = utb::getCampo('trib','trib_id = ' . $crTrib_id,'uso_subcta');

		//Realizo el cálculo del rubro nuevamente
		$arreglo = $model->calculaRubro($crRubro_id,$crBase,$crCant,$crAnio,$crCuota,$crFchpresenta,$crObj_id,$subcta);

		//Obtengo los datos de la consulta
		$crAlicuota = ($arreglo['dataRubro']['alicuota']) * 100;
		$crMinimo = $arreglo['dataRubro']['minimo'];
		$crMonto = $arreglo['dataMonto']['montorubro'];
		$crMonto = round($crMonto * 100) / 100;

		//Obtengo el arreglo actual de memoria
		$arrayMemory = $session['DDJJArregloRubros'];

		//Valido que el ID de tributo que se desea ingresar no se encuentre ingresado en memoria
		if (array_key_exists($crRubro_id, $arrayMemory))
		{
			$error = "El Rubro ingresado ya existe en la Declaración.";
			echo '<script>mostrarErrores( ["'.$error.'"], "#ddjjRubro_errorSummary" );</script>';

		} else
		{
			//Creo un arreglo temporal
			$arregloTemporal = ['trib_id' => $crTrib_id,
								'trib_nom' => utb::getCampo('trib','trib_id = ' . $crTrib_id,'nombre_redu'),
								'rubro_id' => $crRubro_id,
								'rubro_nom' => $crRubro_nom,
								'base' => number_format( $crBase, 2, '.', '' ),
								'cant' => $crCant,
								'tipo' => $tipo,
								'alicuota' => $crAlicuota,
								'minimo' => number_format( $crMinimo, 2, '.', '' ),
								'monto' => number_format( $crMonto, 2, '.', '' ),
								];

			$array[$crRubro_id] = $arregloTemporal;

			//Actualizo los datos de rubros en sesión
			$session['DDJJArregloRubros'] = $session['DDJJArregloRubros'] + $array;

			$array = $session['DDJJArregloRubros'];

			//Obtengo un nuevo ID de DDJJ en caso de no existir
			if (!isset($session['DDJJNextDJRubro_id']) || $session['DDJJNextDJRubro_id'] == '')
			{
				$session['DDJJNextDJRubro_id'] = $model->getNextDjRub_id();
			}

			//Grabo todo lo que tengo en el arreglo temporal en la tabla TEMP
			$model->grabarRubrosTemp( $session['DDJJNextDJRubro_id'], $array );

			//Calculo la declaración jurada
			$session['DDJJArregloItems'] = $model->calcularDJ($crTrib_id,$crObj_id,$crFiscaliza,$crAnio,$crCuota,$crFchpresenta,$session['DDJJNextDJRubro_id']);

			//Borro todo lo que tengo en la tabla TEMP de la BD
			$model->borrarRubrosTemp( $session['DDJJNextDJRubro_id'] ); //Le paso el ID de la tabla TEMP que se está usando en esta sesión

			//Seteo los valores por defecto para la ventana modal
			?>
			<script>
				$("#ddjj_rubro_dlTrib").val("0");
				$("#ddjj_rubro_dlRubro").val("0");
				limpiarEdit();

				agregaRubro();	//Habilito el botón grabar
				$.pjax.reload("#manejadorGrillas");
				$("#ModalAgregarRubroDDJJ").modal("hide"); //Cierro la ventana modal
			</script>
			<?php

		}
	}

Pjax::end();
//FIN Bloque código cargar rubro

?>

<div class="ddjj_rubro_info">

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">
			<?php
				Pjax::begin(['id'=>'errorNuevoRubroDDJJ']);

					$mensaje = Yii::$app->request->post('mensajeRubro','');

					if($mensaje != "")
					{
				    	Alert::begin([
				    		'id' => 'AlertaMensajeRubroDDJJ',
							'options' => [
				        	'class' => 'alert-danger',
				        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
				    		],
						]);

						echo $mensaje;

						Alert::end();

						echo "<script>window.setTimeout(function() { $('#AlertaMensajeRubroDDJJ').alert('close'); }, 5000)</script>";

					 }

				 Pjax::end();
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->
<div class="form-panel" style="padding-right:8px">

<table width="100%" border="0px">
	<tr>
		<td><label>Trib.:</label></td>
		<td><?= Html::dropDownList('dlTrib', null, utb::getAux('trib','trib_id','nombre',3,$condTributo),
			[	'style' => 'width:220px',
				'class' => 'form-control',
				'id' => 'ddjj_rubro_dlTrib',
				'onchange' => 'limpiarEdit();$.pjax.reload({container:"#agregarRubro_filtrarBusqueda",method:"POST",data:{' .
												'trib_rubro:$(this).val(),' .
												'obj_id:"'.$obj_id.'",' .
												'fiscaliza:'.$fiscaliza.',' .
												'anio:'.$anio.',' .
												'cuota:'.$cuota.',' .
												'fchpresenta:"'.$fchpresenta.'"}})'
			]); ?>
		</td>
		<td width="20px"></td>
		<td><label>Rubro:</label></td>
		<td>

			<?php

				$trib_id = ''; 		//Variable que almacenará el código de tributo seleccionado

				Pjax::begin(['id' => 'agregarRubro_filtrarBusqueda']);

					$condRubro = []; 	//Arreglo que almacenará los códigos y nombres para los rubros

					if (isset($_POST['trib_rubro']) && $_POST['trib_rubro'] != 0)
					{
						//Obtengo los parámetros
						$trib_id = Yii::$app->request->post('trib_rubro',0);
						$obj_id = Yii::$app->request->post('obj_id','');
						$anio = Yii::$app->request->post('anio','');
						$cuota = Yii::$app->request->post('cuota','');
						$fiscaliza = Yii::$app->request->post('fiscaliza','');
						$fchpresenta = Yii::$app->request->post('fchpresenta','');

						//Obtengo subcta
						$subcta = utb::getCampo('trib','trib_id = ' . $trib_id,'uso_subcta');

						$sql = "SELECT rubro_id as cod, rubro_nom as nombre FROM sam.uf_objeto_rubro(".$trib_id.",'".$obj_id."',".$subcta.",".$fiscaliza.",".$anio.",".$cuota.")";
						$condRubro = Yii::$app->db->createCommand($sql)->queryAll();
						$condRubro = array_merge(['0' => ['cod' => 0, 'nombre' => '<Ninguno>']], $condRubro);
						$condRubro = ArrayHelper::map($condRubro, 'cod', 'nombre');
					}
					//var_dump( $condRubro );

					echo Html::dropDownList('dlRubro', null, $condRubro,
					[	'style' => 'width:190px',
						'class' => 'form-control',
						'id'=>'ddjj_rubro_dlRubro',
						'onchange' => '$.pjax.reload({container:"#agregarRubro_calcularRubro",method:"POST",data:{' .
												'trib_rubro:'.$trib_id.',' .
												'rubro_rubro:$(this).val(),' .
												'obj_id:"'.$obj_id.'",' .
												'fiscaliza:'.$fiscaliza.',' .
												'anio:'.$anio.',' .
												'cuota:'.$cuota.',' .
												'fchpresenta:"'.$fchpresenta.'",' .
												'subcta:'.$subcta.'}})'
					]);

				Pjax::end();
			?>
		</td>
	</tr>
</table>


<table>
	<tr>
		<td width="120px">
			<label>Fórmula de Cálculo</label>
			<?= Html::input('text','txFormulaCalculo',null,['id'=>'ddjj_rubro_txFormulaCalculo','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:center','disabled' => true]); ?>
		</td>
		<td width="15px"></td>
		<td width="110px">
			<label>Base Imponible</label>
			<?= Html::input('text','txBaseImponible',0,['id'=>'ddjj_rubro_txBaseImponible','class'=>'form-control','style'=>'width:100%;text-align:right']); ?>
		</td>
		<td width="15px"></td>
		<td width="30px">
			<label>Cantidad</label>
			<?= Html::input('text','txCantidad',0,['id'=>'ddjj_rubro_txCantidad','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:center','disabled' => true]); ?>
		</td>
		<td width="15px"></td>
		<td width="30px">
			<label>Alícuota</label>
			<?= Html::input('text','txAlicuota',null,['id'=>'ddjj_rubro_txAlicuota','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:right','disabled' => true]); ?>
		</td>
		<td width="15px"></td>
		<td width="30px">
			<label>Mínimo</label>
			<?= Html::input('text','txMinimo',null,['id'=>'ddjj_rubro_txMinimo','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:right','disabled' => true]); ?>
		</td>
		<td width="15px"></td>
		<td width="50px">
			<label>Monto</label>
			<?= Html::input('text','txMonto',null,['id'=>'ddjj_rubro_txMonto','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:right','disabled' => true]); ?>
		</td>
	</tr>
</table>
</div>

<div class="text-center" style="margin-bottom: 8px;">

	<?= Html::button('Aceptar',['id'=>'btModalDDJJRubroCargar','class' => 'btn btn-success','onclick'=>'cargarRubro()']); ?>
	&nbsp;&nbsp;
	<?= Html::button('Cancelar',['id'=>'btModalDDJJRubroCancelar','class' => 'btn btn-primary','onclick'=>'$("#ModalAgregarRubroDDJJ").modal("hide")']); ?>
</div>

<div id="ddjjRubro_errorSummary" class="error-summary" style="display:none">

	<ul>
	</ul>

</div>

</div>
<script>
function limpiarEdit()
{
	$("#ddjj_rubro_txFormulaCalculo").val("");
	$("#ddjj_rubro_txCantidad").attr("readOnly",true);
	$("#ddjj_rubro_txAlicuota").val("");
	$("#ddjj_rubro_txMinimo").val("");
	$("#ddjj_rubro_txMonto").val("");
	$("#ddjj_rubro_txBaseImponible").val("0");
}

function cargarRubro()
{
	var trib_id = $("#ddjj_rubro_dlTrib").val(),
		trib_nom = $("#ddjj_rubro_dlTrib option:selected").text(),
		rubro_id = $("#ddjj_rubro_dlRubro").val(),
		rubro_nom = $("#ddjj_rubro_dlRubro option:selected").text(),
		base = $("#ddjj_rubro_txBaseImponible").val(),
		cant = $("#ddjj_rubro_txCantidad").val(),
		alicuota = $("#ddjj_rubro_txAlicuota").val(),
		minimo = $("#ddjj_rubro_txMinimo").val(),
		monto = $("#ddjj_rubro_txMonto").val(),
		obj_id = $("#txRubroObj_id").val(),
		fchpresenta = $("#txRubroFchpresenta").val(),
		anio = $("#txRubroAnio").val(),
		cuota = $("#txRubroCuota").val(),
		fiscaliza = $("#txRubroFiscaliza").val(),
		error = new Array();

	if ( trib_id == '' || trib_id == 0 )
	{
		error.push(  "Ingrese un tributo." );
	}

	if ( rubro_id == '' || rubro_id == 0 )
	{
		error.push(  "Ingrese un rubro." );
	}

	if ( base == '' )
	{
		error.push(  "Ingrese una base imponible." );
	}

	if ( error.length > 0 )
	{
		mostrarErrores( error, "#ddjjRubro_errorSummary" );
	} else
	{
		$.pjax.reload({
			container:"#PjaxCargarRubro",
			method:"POST",
			data:{
				crTrib_id:trib_id,
				crTrib_nom:trib_nom,
				crRubro_id:rubro_id,
				crRubro_nom:rubro_nom,
				crBase:base,
				crCant:cant,
				crAlicuota:alicuota,
				crMinimo:minimo,
				crMonto:monto,
				crObj_id:obj_id,
				crFchpresenta:fchpresenta,
				crAnio:anio,
				crCuota:cuota,
				crFiscaliza:fiscaliza,
			}
		});
	}

}
</script>

<?php

Pjax::end();

?>

<script>
$('#ModalAgregarRubroDDJJ').on('shown.bs.modal', function() {

	//Oculto los mensajes de error
	$("#ddjjRubro_errorSummary").css("display","none");

    //selecciono el tributo del formulario de agregarRubro
    $("#ddjj_rubro_dlTrib").val($("#ddjj_dlTrib").val());

    $("#ddjj_rubro_dlTrib").trigger("change");

});
</script>
