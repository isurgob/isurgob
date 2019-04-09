<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ctacte\CalcDesc;
use yii\jui\DatePicker;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\Fecha;
use app\utils\db\utb;

ini_set('display_errors','on');
	error_reporting(E_ALL);
	
$model = new CalcDesc;

Pjax::begin(['id' => 'ActControlesDescuento']); // inicio bloque de cálculo
	
	
	$datos = Yii::$app->request->post( 'datos', [] );
	$fchpago = Fecha::getDiaActual();	//Variable que almacenará la fecha
	$obj_id = '';
	$obj_nom = '';
	$anio = '';
	$cuota = '';
	$monto = 0;
	$total = 0;
	
	if ( count( $datos ) > 0 )
	{
		//Cargo los valores a las variables
		$control = $datos[ 'control' ];		//Variable que almacenará valores de control
		$anio = $datos[ 'anio' ]; 			//Variable que almacenará el año
		$cuota = $datos[ 'cuota' ];			//Variable que almacenará el número de cuotas 
		$fchpago = $datos[ 'fchpago' ]; 
		$obj_id = $datos[ 'obj_id' ];		//Variable que almacenará el código del objeto
		$trib_id = $datos[ 'trib_id' ];		//Variable que almacenará el código del tributo
		$monto = $datos[ 'monto' ];			//Variable que almacenará el monto que se desea calcular
		$total  = 0;
		$periodo = 0;
		$obj_nom = '';
		
		//Rellena con los valores aceptados por la BD a obj_id
		if ( $control == 'obj_id' || $control == 'total' )
		{
			if ( strlen( $obj_id ) < 8 && $obj_id != '' )
			{
				$obj_id = utb::GetObjeto( (int) utb::GetTObjTrib( $trib_id ), (int) $obj_id );
				
				//Verificar existencia del objeto
				if ( ! (utb::verificarExistenciaObjeto( (int) utb::GetTObjTrib( $trib_id ), "'" . $obj_id . "'" ) ) )
					$obj_id = '';
			}
			
			$obj_nom = utb::getNombObj("'".$obj_id."'");
			
			echo '<script>$("#obj_id").val("'.$obj_id.'")</script>';
			echo '<script>$("#obj_nom").val("'.$obj_nom.'")</script>';
		}
		
		if ( $control == 'total' )
		{
			$periodo = ( $anio * 1000 ) + $cuota;

			$total = $model->calcularDescuento($trib_id,$obj_id,$periodo,$monto,$fchpago);
			$total = number_format( floatval( $total ), 2, '.', '' );
				
			echo '<script>$("#total").val('.$total.')</script>';	
		}
		
	} 
	
	if ( $trib_id != 0 )
	{
		//INICIO Modal Busca Objeto Origen
		Modal::begin([
			'id' => 'ModalBuscaObjDescuentoCalcular',
			'size' => 'modal-lg',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			],
		]);
									
			echo $this->render('//objeto/objetobuscarav', [
				'id' => 'ObjFacilida',
				'txCod' => 'obj_id',
				'txNom' => 'obj_nom',
				'selectorModal' => '#ModalBuscaObjDescuentoCalcular',
				'tobjeto' => utb::getTObjTrib( $trib_id ),
			]);
		
		Modal::end();
		//FIN Modal Busca Objeto Origen
	}
	
	//Habilitar o deshabilitar la búsqueda de objeto
	echo '<script>$("#obj_id").toggleClass("read-only", '.$trib_id.' == 0 );</script>';
	echo '<script>$("#calcDesc_btBuscaObj").toggleClass("read-only", '.$trib_id.' == 0 );</script>';
	
Pjax::end(); // fin bloque de calculo

	if ( $trib_id == 0 || $trib_id == '' ) 
		echo '<script>$("#info").css("display", "block")</script>';
	else 
		echo '<script>$("#info").css("display", "none")</script>';
	
?>
<div class="row">
	<div class="col-xs-10 col-xs-offset-1">	
		<table id='info'>
			<tr>
				<td width="80px"></td>
				<td colspan='2'>
					<div class="alert alert-warning">
						Antes de realizar el cálculo debe seleccionar un elemento de la grilla.
					</div>
				</td>
			</tr>	
			<tr>
		</table>
		
		<table>
			<tr>
				<td style="width:50px">
					<label class="control-label">Tributo:</label>
				</td>
				<td><?= Html::input('text', 'trib_id', ( $trib_id == 0 || $trib_id == '' ? '' : $trib_id ), ['class' => 'form-control','id'=>'trib_id','style'=>'width:80px','disabled'=>'true', 'visible' => 'false']); ?></td>
				<td>
					<?= Html::input('text', 'trib_nom', utb::getNombTrib( $trib_id ), ['class' => 'form-control','id'=>'trib_nom','style'=>'width:370px','disabled'=>'true']); ?>
				</td>
			</tr>
		</table>
		
		<div class="row"></div>
		
		<table>
			<tr>
				<td style="width:50px">
				  	<label for="monto">Objeto:</label>
				</td>
				<td>
				  	<?= Html::input('text', 'obj_id', $obj_id, [
							'id' => 'obj_id',
							'class' => 'form-control read-only',
							'style' => 'width:80px',
							'maxlength'=>'8',
							'onchange'=>'ObjNombre("obj_id")',
						]);
					?>
				</td>
				<td>
					<!-- botón de búsqueda modal -->
					<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
							'class' => 'bt-buscar read-only',
							'id'=>'calcDesc_btBuscaObj',
							'onclick'=>'$("#ModalBuscaObjDescuentoCalcular, .window").modal("show")',
						]);
					?>
					<!-- fin de botón de búsqueda modal -->
				</td>
				<td>
					<?= Html::input('text', 'obj_nom', $obj_nom, ['class' => 'form-control','id'=>'obj_nom','style'=>'width:344px','disabled'=>'false']); ?>
				</td>
			</tr>
		</table>
		
		<div class="row"></div>
		
		<table>
			
			<tr>		
				<td style="width:50px">
					<label>Período:</label>
				</td>
				<td>
					<?= Html::input('text', 'anio', $anio, [
							'id' => 'anio',
							'class' => 'form-control',
							'style' => 'width:40px;',
							'maxlength'=>'4',
							'onkeypress' => 'return justNumbers( event )',
						]);
					?>
					<?= Html::input('text', 'cuota', $cuota, [
							'id' => 'cuota',
							'class' => 'form-control',
							'style' => 'width:35px;',
							'maxlength'=>'3',
							'onkeypress' => 'return justNumbers( event )',
						]);
					?>
				</td>	
				
				<td style="width:30px"></td>
				
				<td>
					<label>Monto</label>
				</td>
				<td style="width:5px"></td>
				<td>
					<?= Html::input('text', 'monto', number_format( floatval( $monto ), 2, '.', '' ), [
							'id' => 'monto',
							'class' => 'form-control',
							'style' => 'width:100px;text-align: right',
							'onkeypress' => 'return justDecimal( $(this).val(), event )',
						]);
					?>
				</td>
				<td style="width:30px"></td>
				<td>
					<label>Fecha pago</label>
				</td>
				<td style="width:5px"></td>
				
				<td>
					<?= DatePicker::widget([
							'id' => 'fchpago',
							'name' => 'fchpago',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => [
								'class' => 'form-control',
								'style' => 'width:97px; text-align: center',
							],	
							'value' => $fchpago,
						]);
					?>
				
				</td>		
			</tr>
		</table>
		
		<div class="row"></div>
		
		<table>
			<tr>
				<td style="width:130px"></td>
				<td>
					<?= Html::Button('Calcular',['class' => 'btn btn-primary', 'onClick' => 'Calcular("total")']) ?>
				</td>
				<td width="30px"></td>
				<td><label class="control-label">Total:</label></td>
				<td>
					<?= Html::input('text', 'Monto: ', number_format( floatval( $total ), 2, '.', '' ), [
							'class' => 'form-control',
							'id' => 'total',
							'style' => 'width: 100px; text-align: right',
							'readOnly' => 'readOnly',
							'disabled' => 'disabled',
						]);  
					?>
				</td>
			</tr>
		</table>
	</div>
	
	
</div>
<div id="descuentoCalcular_errorSummary" class="error-summary" style="display:none">
	
	<ul>
	</ul>
</div>


<script>
function Calcular( ctrl )
{
	var error = new Array(),
		datos = {},
		obj_id = $("#obj_id").val(),
	 	anio = $("#anio").val(),
	 	cuota = $("#cuota").val(),
	 	monto = $("#monto").val(),
	 	fchpago = $("#fchpago").val(),
	 	trib_id = $("#trib_id").val();
	
	if ( trib_id == '' || trib_id == null )
		error.push( "Seleccione un tributo de la grilla." );
	
	if ( obj_id == '' || obj_id == null )
		error.push( "Ingrese un objeto." );
	
	if ( anio == '' || cuota == '' || anio == 0 || cuota == 0 )
		error.push( "Ingrese un período." );
		
	if ( fchpago == '' || fchpago == null )
		error.push( "Ingrese una fecha." );
		
	if ( error.length == 0 )
	{
		//Ocultar los mensajes de error
		$("#descuentoCalcular_errorSummary").css("display","none");
		
		datos.obj_id = obj_id;
		datos.anio = anio;
		datos.cuota = cuota;
		datos.monto = monto;
		datos.fchpago = fchpago;
		datos.trib_id = trib_id; 
		datos.control = ctrl;
		
		$.pjax.reload({
			container: "#ActControlesDescuento",
			method: "POST",
			data: {
				datos: datos,
			}, 
		});
	
	} else
	{
		mostrarErrores( error, "#descuentoCalcular_errorSummary" );
	}		 	
		
}

function ObjNombre(ctrl)
{
	var datos = {},
		obj_id = $("#obj_id").val(),
	 	anio = $("#anio").val(),
	 	cuota = $("#cuota").val(),
	 	monto = $("#monto").val(),
	 	fchpago = $("#fchpago").val(),
	 	trib_id = $("#trib_id").val();
	 
	datos.obj_id = obj_id;
	datos.anio = anio;
	datos.cuota = cuota;
	datos.monto = monto;
	datos.fchpago = fchpago;
	datos.trib_id = trib_id; 
	datos.control = ctrl;
	
	$.pjax.reload({
		container: "#ActControlesDescuento",
		method: "POST",
		data: {
			datos: datos,
		},
	});
}

function btnObjetoBuscar(cod,nombre)
{
	$("#obj_id").val(cod);
	$("#obj_nom").val(nombre);
}

</script>		