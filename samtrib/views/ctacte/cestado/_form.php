<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\bootstrap\Alert;

/**
 * Forma que se dibuja para consultar, dar de alta, modificar y eliminar cestadociones.
 * Recibo:
 * 			=> $model -> Modelo
 *			=> $consulta es una variable que:
 *			 		=> $consulta == 1 => El formulario se dibuja en el index
 *			  		=> $consulta == 0 => El formulario se dibuja en el create
 *			  		=> $consulta == 3 => El formulario se dibuja en el update
 *			  		=> $consulta == 2 => El formulario se dibuja en el delete
 *
 *			=> $cestado -> Por defecto es 0.
 */

$form = ActiveForm::begin([
	'id'=>'frmCEstado',
	]);
	
	
/*
 * Si el formulario se envía y este parámetro viaja como "false", se cargarán los estados.
 * En su defecto, si viaja como true, se procederá a grabar la cambio de estado.
 */
echo Html::input('hidden', 'txAceptar', 0 , [ 'id' => 'cestado_txAceptar']);

 
 //INICIO Bloque actualiza el código de objeto
Pjax::begin(['id' => 'PjaxCambiaObjeto', 'enableReplaceState' => false, 'enablePushState' => false]);

	$trib_id = Yii::$app->request->get( 'trib_id', 0 );
	$obj_id = trim ( Yii::$app->request->get( 'obj_id', '' ) );
	$obj_nom = '';
	$uso_subcta = 0;
	
	# Obtengo el tipo de objeto según el tributo
	$tobj = utb::getTObjTrib( $trib_id );
	
	$tobj_nom = utb::getTObjNomTrib( $trib_id );
		
	if ( $trib_id != 0 )
	{
		
		# Verificar si hace uso de subcta
		$uso_subcta = utb::getCampo('trib','trib_id = ' . $trib_id,'uso_subcta');
		
		if ( strlen( $obj_id ) < 8 && $obj_id != '' )	//Competo el nombre del objeto
		{
			$obj_id = utb::GetObjeto( $tobj, (int) $obj_id );
		}
		
		if ( $tobj != utb::getTObj( $obj_id ) )
			$obj_id = '';
		
		# Validar que exisa el comercio
		if ( utb::verificarExistencia('objeto',"obj_id = '" . $obj_id . "'") )
		{
			//Buscar el nombre de comercio y asignarlo en el Edit correspondiente
			$obj_nom = utb::getNombObj("'".$obj_id."'");
			
		} else 
		{
			$obj_id = '';
		}
	
	} else 
	{
		$obj_id = '';
	}	
	
	?>
	
	<script>
		
		$("#PjaxCambiaObjeto").on("pjax:end",function() {
			
			// Si se cambia el tributo u objeto, se deshabilita el botón "Aceptar"
			$("#cestado_btAceptar").addClass("disabled"); 
			
			// Condición para activar uso_subcta
			$("#cestado_txSubCta").toggleClass( "solo-lectura", <?= $uso_subcta ?> == 0 );
			$("#cestado_txSubCta").val(0);
			
			// Actualizar el tipo, código y nombre de objeto
			$("#cestado_txTObj").val("<?= $tobj_nom ?>");
			$("#cestado_txObjID").val("<?= $obj_id ?>");
			$("#cestado_txObjNom").val("<?= $obj_nom ?>");
			
			// Actualizar la clase del botón de búsqueda de objeto y del input de objeto según trib_id
			$('#cestado_txObjID').toggleClass('solo-lectura',<?= $trib_id ?> == 0 );
			$('#cestado_btBuscaObj').toggleClass('disabled',<?= $trib_id ?> == 0 );
			
			
			$(document).ready(function() 
			{
				$.pjax.reload("#PjaxEstado");
				
				$("#PjaxEstado").on("pjax:end", function() {
					
					if ( <?= $trib_id ?> == 0 )
						$("#cestado_txTrib").focus();
					else if ( '<?= $obj_id ?>' == '' )
						$("#cestado_txObjID").focus();
					else
						$("#cestado_txAnio").focus();
						
					$("#cestado_txInfo").val("");	
					
					$("#PjaxEstado").off("pjax:end");
					
				});
				
				$("#PjaxCambiaObjeto").off("pjax:end");
				
			});	
		});
	
	</script>
	
	<?php
	
	//INICIO Modal Busca Objeto
	Modal::begin([
	'id' => 'BuscaObj',
	'size' => 'modal-lg',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
	 ]);
								
	echo $this->render('//objeto/objetobuscarav',[
		'idpx' => 'buscaFiscaliza',
		'id' => 'cestadoaltaBuscar',
		'txCod' => 'cestado_txObjID',
		'txNom' => 'cestado_txObjNom',
		'tobjeto' => $tobj,
		'selectorModal' => '#BuscaObj',
	]);
	
	Modal::end();
	//FIN Modal Busca Objeto
	
Pjax::end();
//FIN Bloque actualiza los códigos de objeto
 
?>

<div class="cestado_form">

<div class="form-panel" style="padding-right:2px;padding-bottom: 8px">
<table>
	<tr>
		<td width="50px"><label for="cestado_txTrib">Tributo:</label></td>
		<td>
			<?= Html::activeDropDownList($model,'trib_id',utb::getAux('trib','trib_id','nombre',3,"est = 'A'"),[
					'id'=>'cestado_txTrib',
					'class'=>'form-control',
					'style'=>'width:400px;',
					'onchange' => 'f_cambiaObjeto()',
				]);
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Objeto:</label></td>
		<td>
			<?= Html::activeInput('text',$model,'tobj',[
					'id'=>'cestado_txTObj',
					'class'=>'form-control solo-lectura',
					'style'=>'width:90px;text-align:center;',
					'tabIndex' => -1,
				]);
			?>
		</td>
		<td width="4px"></td>
		<td><label>-</label></td>
		<td width="4px"></td>
		<td>
			<?= Html::activeInput('text',$model,'obj_id',[
					'id'=>'cestado_txObjID',
					'class'=>'form-control solo-lectura',
					'style'=>'width:65px;text-align:center',
					'maxlength' => 8,
					'onchange' => 'f_cambiaObjeto()',
					'tabIndex' => 0,
				]);
			?>
		</td>
		<!-- INICIO Botón Búsqueda Comercio -->
		<td>
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>', [
					'id' => 'cestado_btBuscaObj',
					'class' => 'bt-buscar',
					'onclick' => '$("#BuscaObj").modal("show")',
				]);
			?>
		</td>
		<!-- FIN Botón Búsqueda Comercio -->
		<td width="203px">
			<?= Html::activeInput('text',$model,'obj_nom',[
					'id'=>'cestado_txObjNom',
					'class'=>'form-control solo-lectura',
					'style'=>'width:100%;text-align:left',
					'tabindex' => -1,
				]);
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>SubCta:</label></td>
		<td>
			<?= Html::activeInput('text',$model,'subcta',[
					'id' => 'cestado_txSubCta',
					'class' => 'form-control solo-lectura',
					'style' => 'text-align: center; width: 50px;',	
					'maxlength' => 4,
					'onkeypress' => 'return justNumbers( event )',
					'onchange' => 'deshabilitar_campos("cestado_txAnio")',	
				]);
			?>
		</td>
		<td width="18px"></td>
		<td width="30px"><label>Año:</label></td>
		<td>
			<?= Html::activeInput('text',$model,'anio',[
					'id' => 'cestado_txAnio',
					'class' => 'form-control',
					'style' => 'text-align: center; width: 50px;',	
					'maxlength' => 4,
					'onkeypress' => 'return justNumbers( event )',
					'onchange' => 'deshabilitar_campos("cestado_txCuota")',	
				]);
			?>
		</td>
		<td width="24px"></td>
		<td><label>Cuota:</label></td>
		<td>
			<?= Html::activeInput('text',$model,'cuota',[
					'id' => 'cestado_txCuota',
					'class' => 'form-control',
					'style' => 'text-align: center; width: 50px;',	
					'maxlength' => 3,
					'onkeypress' => 'return justNumbers( event )',
					'onchange' => 'deshabilitar_campos("cestado_btCargar")',	
				]);
			?>
		</td>
		<td width="75px"></td>
		<td>
			<?= Html::button('Cargar', [
					'id' => 'cestado_btCargar',
					'class' => 'btn btn-primary',
					'onclick' => 'f_cargar()',
				]);
			?>
	</tr>
</table>

<?php

	Pjax::begin(['id' => 'PjaxEstado', 'enableReplaceState' => false, 'enablePushState' => false]);

?>
		<table style="margin-top: 4px">
			<tr>
				<td width="90px"><label>Estado Origen:</label></td>
				<td width="110px">
					<?= Html::activeInput('hidden',$model,'est_orig'); ?>
					<?= Html::activeInput('text',$model,'est_orig_nom',[
							'id' => 'cestado_txEstOrigen',
							'class' => 'form-control solo-lectura',
							'style' => 'text-align: center; width: 100%;',	
							'tabindex' => -1,	
						]);
					?>
				</td>
				<td width="26px"></td>
				<td width="100px"><label>Estado Destino:</label></td>
				<td width="123px">
					<?= Html::activeDropDownList($model, 'est_dest', $model->array_est_destino, [
							'id' => 'cestado_txEstDestino',
							'class' => 'form-control',
							'style' => 'text-align: center; width: 100%;',	
						]);
					?>
				</td>
			</tr>
		</table>

<?php
	
	Pjax::end();
	
?>
<table>
	<tr>
		<td width="50px"><label>Expe:</label></td>
		<td>
			<?= Html::activeInput('text', $model, 'expe',  [
					'id' => 'cestado_txExpe',
					'class' => 'form-control',
					'style' => 'text-align: left; width: 150px',
					'maxlength' => 12,
				]);
			?>
		</td>
	</tr>
	<tr>
		<td width="50px"><label>Obs.:</label></td>
		<td>
			<?= Html::activeTextarea( $model, 'obs',  [
					'id' => 'cestado_txObs',
					'class' => 'form-control',
					'style' => 'width: 400px; max-width: 400px; height: 40px; max-height: 130px;',
					'maxlength' => 500,				
				]);
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?= Html::activeTextarea($model,'info', [
					'id' => 'cestado_txInfo',
					'class' => 'form-control disabled',
					'style' => 'width: 100%;height: 60px;background-color: #F5F6CE',
					'tabindex' => -1,
				]);
			?>
		</td>
	</tr>
</table>

</div>

<?php
	
	if ( $consulta == 0 )
	{

?>

		<!-- INICIO Botones -->	
		<div class="text-center" style="margin-bottom: 8px;">
			
			<?= Html::button('Aceptar',[
					'id' => 'cestado_btAceptar',
					'class' => 'btn btn-success disabled',
					'onclick' => 'f_grabarDatos()',
					
				]);
			?>
		
			&nbsp;&nbsp;&nbsp;
			
			<?= Html::a('Cancelar',['view', 'consulta' => 1],[
					'id' => 'cestado_btCAncelar',
					'class' => 'btn btn-primary',
				]);
			?>
		
		</div>
		<!-- FIN Botones -->
		
<?php
	
	}
	
?>

</div>

<?php
	
	echo $form->errorSummary( $model, ['id' => 'cestado_errorSummary']);
	
	ActiveForm::end();
	
	if ( $consulta == 1 ) 
	{
		echo "<script>DesactivarForm('frmCEstado');</script>";
	} 
	
	if ( count ( $model->array_est_destino ) > 0 )
	{
		?>
		<script>
			$(document).ready(function(){
				$("#cestado_btAceptar").removeClass("disabled");
				
			});
		</script>
		
		<?php
	}
	
?>

<script>
function deshabilitar_campos( selector )
{
	$("#cestado_txInfo").val("");
	$("#cestado_btAceptar").addClass("disabled");	
	$.pjax.reload("#PjaxEstado");	
	
	if ( selector != '' )
	{
		$("#PjaxEstado").on("pjax:end",function() {
			
			$("#"+selector).focus();
			$("#PjaxEstado").off("pjax:end");
		});
	}
}

function f_cambiaObjeto()
{
	var trib = $("#cestado_txTrib").val(),
		obj_id = $("#cestado_txObjID").val(),
		datos = {};
	
	datos.trib_id = trib;
	datos.obj_id = obj_id;
	
	$.pjax.reload({
		container: "#PjaxCambiaObjeto",
		type: "GET",
		replace: false,
		push: false,
		data: datos,
	});
}

function f_cargar()
{
	var error = new Array(),
		datos = {},
		trib = $("#cestado_txTrib").val(),
		obj_id = $("#cestado_txObjID").val(),
		uso_subcta = $("#cestado_txSubCta").val(),
		anio = $("#cestado_txAnio").val(),
		cuota = $("#cestado_txCuota").val();
		
	$("#cestado_txAceptar").val( 2 );
	
	if ( trib == '' || trib == 0 )
		error.push( "Ingrese un tributo." );
	
	if ( obj_id == '' )
		error.push( "Ingrese un objeto." );
	
	if ( ! $("#cestado_txSubCta").hasClass("solo-lectura") )
	{
		if ( uso_subcta == 0 || uso_subcta == '' )
			error.push( "Ingrese subcta.");
	}
	
	if ( anio == '' )
		error.push( "Ingrese año." );
	else if ( anio.length < 4 )
		error.push( "Ingrese un año válido." );
	
	if ( cuota == '' )
		error.push( "Ingrese cuota." );
		
	if ( error.length > 0 )
		mostrarErrores( error, "#cestado_errorSummary" );
	else
	{
		// Oculto los mensajes de error
		$("#cestado_errorSummary").css("display","none");
		
		$("#frmCEstado").submit();
	}
}

function f_grabarDatos()
{
	var error = new Array(),
		datos = {},
		trib = $("#cestado_txTrib").val(),
		obj_id = $("#cestado_txObjID").val(),
		uso_subcta = $("#cestado_txSubCta").val(),
		anio = $("#cestado_txAnio").val(),
		cuota = $("#cestado_txCuota").val(),
		destino = $("#cestado_txCuota").val(),
		expe = $("#cestado_txCuota").val(),
		obs = $("#cestado_txCuota").val();
	
	$("#cestado_txAceptar").val( 1 );
	
	if ( trib == '' || trib == 0 )
		error.push( "Ingrese un tributo." );
	
	if ( obj_id == '' )
		error.push( "Ingrese un objeto." );
	
	if ( ! $("#cestado_txSubCta").hasClass("solo-lectura") )
	{
		if ( uso_subcta == 0 || uso_subcta == '' )
			error.push( "Ingrese subcta.");
	}
	
	if ( anio == '' )
		error.push( "Ingrese año." );
	else if ( anio.length < 4 )
		error.push( "Ingrese un año válido." );
	
	if ( cuota == '' )
		error.push( "Ingrese cuota." );
	
	if ( destino == '' || destino == 0 )
		error.push( "Ingrese destino." );
		
	if ( error.length > 0 )
		mostrarErrores( error, "#cestado_errorSummary" );
	else
	{
		// Oculto los mensajes de error
		$("#cestado_errorSummary").css("display","none");
		
		$("#frmCEstado").submit();
	}
}

$(document).ready(function() {
	
	$.pjax.defaults.timeout = 1300;
	$("#cestado_txObjID").toggleClass( "solo-lectura",$("#cestado_txObjID").val() == '' );	
});
</script>