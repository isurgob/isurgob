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
use yii\web\Session;
use yii\bootstrap\Alert;
use yii\data\ArrayDataProvider;

/**
 * Forma que se dibuja cuando se llega a Pagocta
 * Recibo:
 * 			=> $model -> Modelo de Pagocta
 */

/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */

 $title = 'Pago Parcial/Pago a Cuenta';

 $this->params['breadcrumbs'][] = $title;

 ?>

 <!-- INICIO Div Principal -->
 <div id="pagocta_divPrincipal" style="margin-right: 100px">

	 <h1><?= $title ?></h1>

<?php $form = ActiveForm::begin([ 'id' => 'pagocta_form' ]); ?>

<?php Pjax::begin([ 'id' => 'pagocta_pjaxEdicionCabecera', 'enableReplaceState' => false, 'enablePushState' => false ]); ?>

<?=
	Html::input( 'hidden', 'txTipoPago', $existe, [
		'id'	=> 'txTipoPago',
	]);
?>

<!-- INICIO Div Datos -->
<div id="pagocta_divDatos">

<!-- INICIO Div Cabecera -->
<div id="pagocta_divCabecera" class="form-panel">

	<table border="0">
		<tr>
			<td width="50px"><label>Tributo:</label></td>
			<td>
	            <?=
	                Html::dropDownList( 'dlTrib', $model->trib_id, $arrayTributo,[
	                    'id'=>'pagocta_dlTrib',
						'class'=>'form-control',
						'style'=>'width:100	%;text-align:left',
						'onchange' => 'f_modificaCabecera( 1, 0, 0 )',
					]);
	            ?>
	        </td>
			<td id="pagoCuenta" style="display:<?= ( $existe == 2 ? "block" : "none" ); ?>" valign="top"><h3><strong>Pago a Cuenta</strong></h3></td>
			<td id="pagoParcial" style="display:<?= ( $existe == 1 ? "block" : "none" ); ?>" valign="top"><h3><strong>Pago Parcial</strong></h3></td>
		</tr>
		<tr>
			<td width="50px"><label>Objeto:</label></td>
			<td colspan="3">
				<?=
					Html::dropDownList('dlTObjeto', $model->tobj, $arrayObjetos,[
						'id'		=> 'dlTObjeto',
						'class'		=> 'form-control' . ( $model->trib_id != '' && $model->trib_tobj == 0 ? '' : ' solo-lectura' ),
						'style'		=> 'width:100px; text-align:left',
						'onchange'	=> 'f_modificaCabecera( 1, 0, 0 )',
						'tabIndex'	=> ( $model->trib_id != '' && $model->trib_tobj == 0 ? '-1' : '0' ),
					]);
				?>
				<?= Html::input('text','txObjID',$model->obj_id,[
						'id' => 'pagocta_txObjID',
						'class'		=> 'form-control' . ( $model->trib_id != '' ? '' : ' solo-lectura' ),
						'style' => 'width:70px;text-align:center',
						'maxlength' => '8',
						'onchange'	=> 'f_modificaCabecera( 0, 0, 0 )',
						'tabIndex'	=> ( $model->trib_id != '' ? '0' : '-1' ),
					]);
				?>
				<!-- botón de búsqueda modal -->
				<?=
					Html::button('<i class="glyphicon glyphicon-search"></i>',[
						'class' 	=> 'bt-buscar',
						'id'		=> 'pagocta_btBuscaObj',
						'onclick'	=> '$("#BuscaObjpagocta_btOrigenBusca, .window").modal("show")',
						'disabled'	=> ( $model->trib_id != '' ? false : true ),
					]);
				?>
				<!-- fin de botón de búsqueda modal -->
				<?=
					Html::input('text','txObjNom',$model->obj_nom,[
						'id'=>'pagocta_txObjNom',
						'class'=>'form-control solo-lectura',
						'style'=>'width:360px',
					]);
				?>
			</td>
		</tr>
	</table>

	<table border="0">
		<tr>
			<td width="50px"><label>SubCta:</label></td>
			<td width="30px">
				<?=
					Html::input('text','txSubcta',$model->subcta,[
						'id'=>'pagocta_txSubcta',
						'class'=>'form-control' . ( $model->usa_subcta ? '' : ' solo-lectura' ),
						'style'=>'width:100%;text-align:center',
						'onchange'=> 'cambioEdit();',
						'tabIndex'	=> ( $model->usa_subcta ? '0' : '-1' ),
					]);
				?>
			</td>
			<td width="10px"></td>
			<td>
				<label>Año/Plan:</label>
				<?=
					Html::input('text','txAnio',$model->anio,[
						'id'		=> 'pagocta_txAnio',
						'class'		=> 'form-control',
						'style'		=> 'width:40px;text-align:center',
						'onchange'	=> 'f_modificaCabecera( 0, 0, 0 );',
					]);
				?>
			</td>
			<td width="10px"></td>
			<td>
				<label>Cuota:</label>
				<?=
					Html::input('text','txCuota',$model->cuota,[
						'id'=>'pagocta_txCuota',
						'class'=>'form-control',
						'style'=>'width:30px;text-align:center',
						'onchange'=> 'f_modificaCabecera( 0, 0, 0 );',
					]);
				?>
			</td>
			<td width="10px"></td>
			<td>
				<label>Monto:</label>
				<?=
					Html::input('text','txMonto',$model->monto,[
						'id'			=> 'pagocta_txMonto',
						'class'			=> 'form-control' . ( $existe != 1 ? ' solo-lectura' : '' ),
						'style'			=> 'width:80px;text-align:right',
						'onkeypress'	=> 'return justDecimal( $( this ).val(), event )',
						'onchange'		=> 'cambioEdit();',
						'tabIndex'		=> ( $existe != 1 ? '-1' : '0' ),
					]);
				?>
			</td>
			<td width="10px"></td>
			<td>
				<label>Límite:</label>
				<?=
					DatePicker::widget([
						'id'=>'pagocta_txFchlimite',
						'name'=>'txFchlimite',
						'dateFormat' => 'dd/MM/yyyy',
						'value' => Fecha::usuarioToDatePicker(Fecha::getDiaActual()),
						'options' => [
							'class'=>'form-control',
							'style'=>'width:80px;text-align:center',
							'onchange'=>'cambioEdit()',
						],
					]);
				?>
			</td>
			<td width="30px"></td>
			<td>
				<?=
					Html::Button('Cargar',[
						'id'		=> 'btCargarDatos',
						'class'		=> 'btn btn-primary' . ( $existe == 0 ? ' disabled' : '' ),
						'onclick'	=> 'f_btCargar()',
					]);
				?>
			</td>
		</tr>
	</table>

</div>
<!-- FIN Div Cabecera -->

<!-- INICIO Div Observaciones -->
<div id="pagocta_divObservaciones" class="form-panel">
	<table>
		<tr>
			<td width="50px"><label>Obs:</label></td>
			<td>
				<?=
					Html::textarea('txObs',$model->obs,[
						'id'=>'pagocta_txObs',
						'class'=>'form-control',
						'style'=>'width:580px;max-width:580px;height:40px;max-height:120px',
					]);
				?>
			</td>
		</tr>
	</table>
</div>
<!-- FIN Div Observaciones -->

</div>
<!-- FIN Div Datos -->


<!-- INICIO Div Cuentas -->
<div id="pagocta_divCuentas" class="form-panel" style="padding-right:10px">

	<div class="pull-left">
		<h3><label>Cuentas:</label></h3>
	</div>

	<div class="pull-right">
		<?= Html::button('<span class="glyphicon glyphicon-plus"></span>',[
				'id' => 'btAgregarCuenta',
				'class' => 'btn btn-primary' . ( $existe == 2 && $habilitarAceptar ? '' : ' disabled' ),
				'onclick' => '$("#AgregaCuentaPagocta, .window").modal("show")',
			]);
		?>
	</div>

	<div class="clearfix"></div>

<?php

Pjax::begin(['id'=>'pagocta_pjaxGrillaCuenta']);

	echo GridView::widget([
		'id' => 'GrillaPagoctaCuentas',
		'dataProvider' => $dataProvider,
		'summaryOptions' => ['class' => 'hidden'],
		'headerRowOptions' => ['class' => 'grilla'],
		'options' => ['style' => 'width:400px;margin:0px auto'],
		'columns' => [
				['attribute'=>'cta_id','header' => 'Cuenta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px','class' => 'grilla']],
				['attribute'=>'cta_nom','header' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px','class' => 'grilla']],
				['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px','class' => 'grilla']],
				['class' => 'yii\grid\ActionColumn',  'contentOptions'=>['style'=>'text-align:right','width'=>'15px','class' => 'grilla'],
	            'buttons' => [
		            'view' => function()
	            			{
	            				return null;
	            			},
	    			'update' => function()
	    						{
	    							return null;
								},

	    			'delete' => function($url, $model, $key) use ( $existe )
	    						{

									if( $existe == 2 ){
										return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
											'class' => 'bt-buscar-label',
											'style' => 'color:#337ab7',
											'onclick'=> 'f_eliminarCuenta(' . $model['cta_id'] . ')'
										]);
									}


								},
	    			]
	    		]
        	],
		]);

?>

<table width="100%">
	<tr>
		<td align="right">
			<label>Monto Total:</label>
			<?=
				Html::input('text','txMontoTotal', number_format( $montoTotal , 2, '.', ''), [
					'id'=>'pagocta_txMontoTotal',
					'class'=>'form-control solo-lectura',
					'style'=>'width:80px;text-align:right',
				]);
			?>
		</td>
	</tr>
</table>

<?php Pjax::end(); ?>

</div>
<!-- FIN Grilla Cuentas -->

<?=
	Html::button('Aceptar',[
		'id'=>'btAceptarPagocta',
		'class'=>'btn btn-success' . ( $habilitarAceptar ? '' : ' disabled' ),
		'onclick' => 'f_btGrabar( 1 )',
	]);
?>

&nbsp;&nbsp;&nbsp;&nbsp;

<?= Html::a('Cancelar',['view','id'=>$model->pago_id],['class'=>'btn btn-primary']); ?>

<div id="pagocta_errorSummary" class="error-summary" style="display:none;margin-top:8px;margin-right:120px">
</div>

<?php if( $error != '' ){	//Si existen errores ?>

	<script>
		mostrarErrores( ["<?= $error ?>"], "#pagocta_errorSummary" );
	</script>

<?php } ?>

<?php

//INICIO Modal Busca Objeto
Modal::begin([
	'id' => 'BuscaObjpagocta_btOrigenBusca',
	'size' => 'modal-lg',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
]);

	echo $this->render('//objeto/objetobuscarav',[
		'idpx' => 'Busca',
		'id' => 'pagocta_btOrigenBusca',
		'txCod' => 'pagocta_txObjID',
		'tobjeto'	=> $model->tobj,
		'txNom' => 'pagocta_txObjNom',
		'selectorModal' => '#BuscaObjpagocta_btOrigenBusca',
	]);

Modal::end();
//FIN Modal Busca Objeto Origen

//INICIO Modal Agregar Cuenta
Modal::begin([
	'id' => 'AgregaCuentaPagocta',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Agregar Cuenta</h2>',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
]);

	echo $this->render('agregarcuenta',[ 'model' => $model ]);

Modal::end();
//FIN Modal Agregar Cuenta


if( $mostrarModalLiquidacion ){

	echo '<script>$( "#ModalExisteEmisionDDJJ" ).modal( "show" );</script>';
}


?>

<?php Pjax::end(); ?>

<?php ActiveForm::end(); ?>



 </div>
 <!-- FIN Div Principal -->


<script>
function setFocus()
{
	if ($("#pagocta_dlTrib").val() == '' || $("#pagocta_dlTrib").val() == 0)
		$("#pagocta_dlTrib").focus();
	else if ($("#pagocta_txObjID").val() == '')
		$("#pagocta_txObjID").focus();
	else if ($("#pagocta_txSubcta").attr("readOnly") != "readonly" && $("#pagocta_txSubcta").val() != '')
		$("#pagocta_txSubcta").focus();
	else if ($("#pagocta_txAnio").val() == '')
		$("#pagocta_txAnio").focus();
	else if ($("#pagocta_txCuota").val() == '')
		$("#pagocta_txCuota").focus();
	else if ($("#pagocta_txMonto").hasClass( "solo-lectura") && $("#pagocta_txMonto").val() == '')
		$("#pagocta_txMonto").focus();
	else
		$("#btCargarDatos").focus();
}
</script>

<?php

	//INICIO Modal Confirmación Existencia Emisión DDJJ
	Modal::begin([
		'id' => 'ModalExisteEmisionDDJJ',
		'size' => 'modal-sm',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#337ab7">Existe Liquidación</h2>',
		'closeButton' => [
			'label' => '<b>X</b>',
			'class' => 'btn btn-danger btn-sm pull-right',
			'id' => 'btCancelarModalElim'
			],
	]);

	?>

	<center>
		<p><label>Existe una liquidación para el período.<br /> ¿Desea continuar?</label></p><br />

		<?= Html::button('Aceptar',['class' => 'btn btn-success','onclick'=> 'f_btGrabar( 0 )' ]); ?>

		&nbsp;&nbsp;

		<?= Html::button('Cancelar',['class' => 'btn btn-primary','onclick'=>'$("#ModalExisteEmisionDDJJ").modal("hide")']); ?>

	</center>


	<?php

	Modal::end();
	//FIN Modal Confirmación Existencia Emisión DDJJ

?>

<script>
function f_modificaCabecera( quitarObjeto, accion, validaLiquidacion ){

	var trib 	= $("#pagocta_dlTrib option:selected").val(),
		tobj	= $( "#dlTObjeto option:selected" ).val(),
		obj_id	= $( "#pagocta_txObjID" ).val(),
		obj_nom	= $( "#pagocta_txObjNom" ).val(),
		subcta	= $( "#pagocta_txSubcta" ).val(),
		anio	= $( "#pagocta_txAnio" ).val(),
		cuota	= $( "#pagocta_txCuota" ).val(),
		monto	= $( "#pagocta_txMonto" ).val(),
		obs		= $( "#pagocta_txObs" ).val(),
		limite	= $( "#pagocta_txFchlimite" ).val();

	ocultarErrores( "#pagocta_errorSummary" );

	$.pjax.reload({
		container	: "#pagocta_pjaxEdicionCabecera",
		method		: "GET",
		replace		: false,
		push		: false,
		data:{
			"Pagocta[trib_id]"		: trib,
			"Pagocta[tobj]"			: tobj,
			"Pagocta[obj_id]"		: obj_id,
			"Pagocta[obj_nom]"		: obj_nom,
			"Pagocta[subcta]"		: subcta,
			"Pagocta[anio]"			: anio,
			"Pagocta[cuota]"		: cuota,
			"Pagocta[monto]"		: monto,
			"Pagocta[obs]"			: obs,
			"Pagocta[fchlimite]"	: limite,
			"quitarObj"				: quitarObjeto,
			"accion"				: accion,
			"validaLiquidacion"		: validaLiquidacion,

		},
	});
}

function f_eliminarCuenta( cod ){

	$.pjax.reload({
		container	: "#pagocta_pjaxGrillaCuenta",
		method		: "GET",
		replace		: false,
		push		: false,
		data:{
			cuenta_id	: cod,
			action		: 2,
		},
	});
}

function ocultarLabelsPagos()
{
	//Oculto los edit de Pago Parcial y Pago a Cuenta
	$("#pagoParcial").css("display","none");
	$("#pagoCuenta").css("display","none");
}

/* Función que deshabilita los controles al modificar un edit */
function cambioEdit()
{
	//Desabilita el botón "Aceptar"
	$("#btAceptarPagocta").attr("disabled","true");

	//Deshabilita el botón para "Agregar Cuenta"
	$("#btAgregarCuenta").attr("disabled","true");

}

function f_btCargar()
{
	ocultarErrores( "#pagocta_errorSummary" );

	var monto 	= isNaN( parseFloat( $( "#pagocta_txMonto" ).val() ) ) ? 0 : parseFloat( $( "#pagocta_txMonto" ).val() ),
		existe	= $( "#txTipoPago" ).val(),
		error 	= new Array();

	if( existe == 1 ){
		if( !( monto > 0 ) ){
			error.push( "Ingrese un monto." );
		}
	}

	if ( error.length == 0 ){

		f_modificaCabecera( 0, 1, 0 );

	} else {

		mostrarErrores( error, "#pagocta_errorSummary" );
	}
}

function f_btGrabar( validaLiquidacion ){

	f_modificaCabecera( 0, 2, validaLiquidacion );
}

</script>
