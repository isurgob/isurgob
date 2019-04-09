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

$form = ActiveForm::begin(['id' => 'formRegPagoAnt',
							'action' => ['pagoant']]);

echo Html::input('hidden','txConsulta',$consulta,['id' => 'pagoant_txConsulta']);
echo Html::activeInput( 'hidden',$model, 'pagoant_pago_id');



?>
<style>

.form-panel {

	padding-bottom: 8px;
}

</style>

<!-- INICIO Mensajes de alerta -->
<div>
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'alertRegPagoAnt']);

			if ($alert != '' && $m != '')
			{

		    	Alert::begin([
		    		'id' => 'AlertaRegPagoAnterior',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $m !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $alert;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaRegPagoAnterior').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
</div>
<!-- FIN Mensajes de alerta -->

<!-- INICIO Div Objeto -->
<div class="form-panel">
<?php Pjax::begin([ 'id' => 'pagoant_pjax_actualizaTObjeto', 'enableReplaceState'   => false, 'enablePushState' => false ]); ?>

<table>
	<tr>
		<td width="350px" colspan="4">
			<label>Tributo</label>
			<?= Html::activeDropDownList( $model, 'pagoant_tributo', utb::getAux('trib','trib_id','nombre',3,"est='A' and trib_id<>6" ), [
					'id'=>'formPagoAnt-tributo',
					'style'=>'width:100%',
					'class' => 'form-control',
					'onchange'=>'filtraObjeto()',
				]);
			?>
		</td>
		<td width="30px"></td>
		<td width="105px">
			<label>Tipo Objeto</label>
			<?= Html::activeDropDownList( $model, 'pagoant_obj_tobj', utb::getAux('objeto_tipo','cod','nombre',1,"est='A'" ), [
					'id'=>'formPagoAnt-dlObjeto',
					'style'=>'width:100%',
					'class' => 'form-control solo-lectura',
					'tabindex' => -1,
				]);
			?>
		</td>
		<td>
	<tr>

	<tr>
		<td><label>Objeto</label></td>
		<td width="60px">
			<?= Html::activeInput('text', $model, 'pagoant_obj_id' ,[
					'id'=>'formPagoAnt-txObjetoID',
					'class'=>'form-control' . ( $model->pagoant_tributo == 0 || $model->pagoant_obj_tobj == 0 ? ' solo-lectura' : '' ),
					'style'=>'width:75px',
					'onchange'=>'cambiaObjeto()',
                    'tabIndex'  => ( $model->pagoant_tributo == 0 || $model->pagoant_obj_tobj == 0 ? '-1' : '0' ),
				]);
			?>
		</td>
		<td>
		<!-- INICIO Botón Búsqueda Objeto-->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
					'class' => 'bt-buscar' . ( $model->pagoant_tributo == 0 || $model->pagoant_obj_tobj == 0 ? ' disabled' : '' ),
                    'id' => 'btn-BuscaObj',
                    'onclick' => '$("#BuscaObjRegPagoAnt").modal("show")',
				]);
            ?>

		<!-- FIN Botón Búsqueda Objeto-->
		</td>
		<td width="60px">
			<?=
                Html::activeInput( 'text', $model, 'pagoant_obj_nom', [
					'id'=>'formPagoAnt-txObjetoNom',
					'class'=>'form-control solo-lectura',
					'style'=>'width:220px;',
					'tanindex' => -1,
				]);
			?>
		</td>

        <td></td>
        <td>
            <label>Sub. Cta.</label>
            <?=
                Html::activeInput( 'text', $model, 'pagoant_suc', [
					'id'=>'formPagoAnt-txSuc',
					'class'=>'form-control solo-lectura',
					'style'=>'width:50px;text-align:center',
                    'onkeypress'   => 'return justNumbers( event )',
					'tabIndex'     => '-1',
				]);
			?>
	</tr>
</table>

<?php
	Modal::begin([
        'id' => 'BuscaObjRegPagoAnt',
        'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
        'closeButton' => [
          'label' => '<b>X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ],
        'size' => 'modal-lg',
    ]);

        echo $this->render('//objeto/objetobuscarav', [
				'id' => 'RegPagoAnt',
				'txCod' => 'formPagoAnt-txObjetoID',
				'txNom' => 'formPagoAnt-txObjetoNom',
				//'txDoc' => 'doc_origen',
				'selectorModal' => '#BuscaObjRegPagoAnt',
				'tobjeto' => $model->pagoant_obj_tobj,
			]);

    Modal::end();

Pjax::end();
?>

</div>

<div class="form-panel">
<h3><strong>Detalle Pago</strong></h3>

<table>
	<tr>
		<td width="60px">
			<label>Año:</label>
			<?= Html::activeInput('text', $model, 'pagoant_anio', [
					'id'=>'formPagoAnt-txAnio',
					'class'=>'form-control',
					'style'=>'width:100%',
					'maxlength'=>6,
					'onkeypress'=>'return justNumbers(event)',
				]);
			?>
		</td>
		<td width="5px"></td>
		<td valign="bottom">
			<br>
			<label id="LabelCuotaDesde">Cuota desde</label>
			<?= Html::activeInput('text', $model, 'pagoant_cuotadesde', [
					'id'=>'formPagoAnt-txCuotaDesde',
					'class'=>'form-control',
					'style'=>'width:40px',
					'maxlength'=>3,
					'onkeypress'=>'return justNumbers(event)',
				]);
			?>
			<label id="LabelCuotaHasta">hasta</label>
			<?= Html::activeInput('text', $model, 'pagoant_cuotahasta', [
					'id'=>'formPagoAnt-txCuotaHasta',
					'class'=>'form-control',
					'style'=>'width:40px',
					'maxlength'=>3,
					'onkeypress'=>'return justNumbers(event)',
				]);
			?>
		</td>
		<td width="20px"></td>
		<td width="80px">
			<label>Fecha Pago:</label>
			<?=
						DatePicker::widget(
							[
								'model' => $model,
								'attribute' => 'pagoant_fecha',
								'id' => 'formPagoAnt-txFechaPago',
								'dateFormat' => 'dd/MM/yyyy',
								'options' => ['style' => 'width:100%','class' => 'form-control'],
								//'value' => ($model->pagoant_fecha != '' ? Fecha::usuarioToDatePicker( $model->pagoant_fecha ) : Fecha::usuarioToDatePicker( Fecha::getDiaActual() ) ) ,
							]
						);
			?>
		</td>
		<td width="20px"></td>
		<td width="80px">
			<label>Comprobante:</label>
			<?= Html::activeInput( 'text', $model, 'pagoant_comprob', [
					'id'=>'formPagoAnt-txComprobante',
					'class'=>'form-control',
					'style'=>'width:100%',
				]);
			?>
		</td>
	</tr>
</table>
<br />

<table width="100%">
	<tr>
		<td>
			<label>Observaciones:</label>
			<?= Html::activeTextarea( $model, 'pagoant_obs', [
					'id'=>'formPagoAnt-txObs',
					'maxlength' => 500,
					'style' => 'width:450px;height:60px;max-height:120px;max-width:450px',
					'class'=>'form-control',
				]);
			?>
		</td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td align="right" style="padding-right:12px">
			<label>Modif:</label>
			<?= Html::activeInput( 'text', $model, 'pagoant_modif',[
					'id'=>'formPagoAnt-txModif',
					'class'=>'form-control solo-lectura',
					'style'=>'width:220px;background-color:#E6E6FA;',
					'tabindex' => -1,
				]);
			?>
		</td>
	</tr>
</table>
</div>

<div style="padding-top:0px;margin-bottom:8px;">
<?php

	if ($consulta <> 1)
	{


		if ($consulta == 0)
		{
			 echo Html::button('Aceptar',['class' => 'btn btn-success', 'method'=>'POST','onclick'=>'validarDatos()']);

			 echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			 echo Html::a('Cancelar', ['pagoant', 'consulta'=>1],
			 					['class' => 'btn btn-primary']);

		} else if ($consulta == 2)
		{

			echo Html::Button('Eliminar', ['class' => 'btn btn-danger', 'id' => 'btEliminarAcep',
				'data' => [
							'toggle' => 'modal',
							'target' => '#ModalEmiminar',
						],]);
		}

	}

	//INICIO Ventana modal eliminar Pago
	Modal::begin([
			'id' => 'ModalEmiminar',
			'size' => 'modal-sm',
			'header' => '<h4><b>Confirmar Eliminación</b></h4>',
			'closeButton' => [
				'label' => '<b>X</b>',
    			'class' => 'btn btn-danger btn-sm pull-right',
    			'id' => 'btCancelarModalElim'
				],
		]);

		echo "<center>";
		echo "<p><label>¿Esta seguro que desea eliminar ?</label></p><br>";

		echo Html::a('Aceptar', ['deletepagoant', 'id' => Yii::$app->request->get('id',0)],[
				'class' => 'btn btn-success',
			]);

		echo "&nbsp;&nbsp;";
 		echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEliminarCanc','onClick' => '$("#ModalEmiminar, .window").modal("toggle");']);
 		echo "</center>";

 	Modal::end();
	//FIN Ventana modal eliminar Pago
?>

</div>

<?php

	echo $form->errorSummary( $model, [

			'id' => 'pagoant_errorSummary',

			'style' => 'margin-right: 15px;',
		] );

	ActiveForm::end();

	if ($consulta==1 or $consulta==2)
	{
		echo "<script>";
		echo "DesactivarFormPost('formRegPagoAnt');";
		echo "</script>";
	}


    if ($consulta==2) echo '<script>$("#btEliminarAcep").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#btEliminarCanc").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#ModalEmiminar").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#btCancelarModalElim").prop("disabled", false);</script>';

?>

<script>
<?php if ($consulta == 1) { ?>
	$("#LabelCuotaDesde").html("Cuota");
	$("#LabelCuotaHasta").css("display","none");
	$("#formPagoAnt-txCuotaHasta").css("visibility","hidden");
<?php } ?>

function mostrarError( error )
{
	var $contenedor= $("#pagoant_errorSummary"),
		$lista= $("#pagoant_errorSummary ul");

	$lista.empty();

	$contenedor.css("display", "block");

	for (e in error)
	{
		$el= $("<li />");
		$el.text(error[e]);
		$el.appendTo($lista);
	}
}

function filtraObjeto()
{
	var tributo = $("#formPagoAnt-tributo").val();

	$("#formPagoAnt-txObjetoID").toggleClass("solo-lectura",tributo == 0);
	$("#btn-BuscaObj").toggleClass("disabled",tributo == 0);

	$.pjax.reload({
		container   : "#pagoant_pjax_actualizaTObjeto",
		type        : "GET",
        replace     : false,
        push        : false,
		data:{
			tributo:tributo,
		}
	});
}

function cambiaObjeto()
{
	$.pjax.reload({
		container   : "#pagoant_pjax_actualizaTObjeto",
        replace     : false,
        push        : false,
		type        : "GET",
		data:{
			codigoObjeto : $("#formPagoAnt-txObjetoID").val(),
			tributo      : $("#formPagoAnt-tributo").val(),

		}
	});
}

function validarDatos()
{
	var tributo    = $("#formPagoAnt-tributo option:selected").val(),
        obj_id     = $("#formPagoAnt-txObjetoID").val(),
		anio       = $("#formPagoAnt-txAnio").val(),
		cuotadesde = $("#formPagoAnt-txCuotaDesde").val(),
		cuotahasta = $("#formPagoAnt-txCuotaHasta").val(),
		fecha      = $("#formPagoAnt-txFechaPago").val(),
		comprob    = $("#formPagoAnt-txComprobante").val(),
		tobj       = $( "#formPagoAnt-dlObjeto option:selected" ).val(),
        suc        = $( "#formPagoAnt-txSuc" ).val(),
        validaSuc  = $( "#formPagoAnt-txSuc" ).hasClass( "solo-lectura" ),
		anio_actual = (new Date).getFullYear(),
		error      = new Array();


	if ( tributo == 0 )
	{
		error.push( "Ingrese un tributo." );
	}

	if ( tobj != 0 && ( obj_id == "" || obj_id.length < 8 ) )
	{
		error.push("Ingrese un objeto.");
	}

    if( !validaSuc ){
        if( suc == 0 ){
            error.push( "Ingrese una sucursal." );
        }
    }

	if ( anio == "" )
	{
		error.push( "Ingrese un año." );

	//} else if ( ( tobj == 0 && ( anio.length < 4 || anio.length > 6 ) ) || ( tobj != 0 && anio.length != 4 ) || ( (tributo != 1) && (anio < (anio_actual - 10) || anio > anio_actual) ) )
	} else if ( ( tobj == 0 && ( anio.length < 1 || anio.length > 6 ) ) || ( tobj != 0 && anio.length != 4 ) || ( (tributo != 1) && (anio < (anio_actual - 20) || anio > anio_actual) ) )
	{
		error.push("Ingrese un año válido.");
	}

	if ( cuotadesde == '' )
	{

		error.push("Ingrese una cuota desde.");
	}

	if ( cuotahasta == '' )
	{

		error.push("Ingrese una cuota hasta.");
	}

	if ( cuotadesde < 0 )
	{
		error.push("Cuota Desde mal ingresada.");
	}

	if ( cuotahasta < 0 )
	{
		error.push("Cuota Hasta mal ingresada.");
	}


	if ( error.length == 0 )
	{
		$("#formRegPagoAnt").submit();

	} else
	{
		mostrarError( error );
	}

}

$( "#pagoant_pjax_actualizaTObjeto" ).on( "pjax:end", function() {

	var tributo = $( "#formPagoAnt-tributo option:selected").val(),
        tobj    = $( "#formPagoAnt-dlObjeto option:selected").val(),
		obj_id  = $( "#formPagoAnt-txObjetoID").val(),
        suc     = $( "#formPagoAnt-txSuc" ).hasClass( "solo-lectura" );

	if ( tributo == 0 )
		$("#formPagoAnt-tributo option:selected").focus();
	else if ( obj_id == '' && tobj != 0 )
		$("#formPagoAnt-txObjetoID").focus();
	else if ( !suc )
        $( "#formPagoAnt-txSuc" ).focus();
    else
		$("#formPagoAnt-txAnio").focus();



});

</script>
