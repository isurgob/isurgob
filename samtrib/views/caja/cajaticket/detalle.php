<?php

use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\helpers\BaseUrl;

/*
 * Llegan:
 *
 *	$model => Modelo de Ticket
 *	$edita => Variable que indica si se deben editar los Tickets
 */

$dataProvider = new ArrayDataProvider(['allModels' => $model->CargarDetalle(), 'key' => 'cta_id']);

if ( ! $edita )
{
	Pjax::begin(['id' => 'PjaxGrillaDetalleTicket']);

	echo GridView::widget([
			'id' => 'GrilaDetalle',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProvider,
			'columns' => [

				['attribute'=>'cta_id','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Nº Cuenta'],
				['attribute'=>'cta_nom','header' => 'Cuenta'],
				['attribute'=>'monto','contentOptions'=>['align'=>'right',],'header' => 'Monto'],

			],
		]);

	Pjax::end();

} else
{

	//INICIO Bloque de edición de Tickets
	Pjax::begin(['id' => 'PjaxGrillaDetalleTicketEdicion', 'enableReplaceState' => false, 'enablePushState' => false]);

		$arreglo_sesion = Yii::$app->session->get( 'arreglo_ticket', []);

		//Calcular Monto
		$monto_actual = 0;

		foreach( $arreglo_sesion as $array )
			$monto_actual += $array['monto'];

		//Calcula la diferencia entre el monto dl ticket y el monto actual de las cuentas
		$monto_diferencia = ( $monto_actual > $model->monto ? 0 : $model->monto - $monto_actual );

		//Boton para dar de alta una nueva cuenta
		?>
			<div class="text-right">
				<?=
					Html::Button('Nuevo', [
						'class' => 'btn btn-success',
						'onclick'=>'f_editaTicket( 0, 0, '.$monto_diferencia.' )',
					]);
				?>
			</div>
		<?php

		echo GridView::widget([
				'id' => 'GrilaDetalle',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => new ArrayDataProvider([
					'allModels' => $arreglo_sesion,
					'key' => 'cta_id',
				]),
				'columns' => [

					['attribute'=>'cta_id','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Nº Cuenta'],
					['attribute'=>'cta_nom','header' => 'Cuenta'],
					['attribute'=>'monto','contentOptions'=>['align'=>'right',],'header' => 'Monto'],
					[
						//Agrego los botones de Edición y Eliminación
						'class' => 'yii\grid\ActionColumn',
						'contentOptions'=>['style'=>'width:30px'],
						'template' =>'{update}{delete}',
						'buttons'=>[
							'update' =>  function($url, $model, $key)
										{

											return Html::Button('<span class="glyphicon glyphicon-pencil"></span>', [
													'class'=>'bt-buscar-label',
													'style'=>'color:#337ab7',
													'onclick'=>'f_editaTicket('.$model["cta_id"].',3,0)',
													]);
										},
							'delete' =>  function($url, $model, $key)
										{
											return Html::Button('<span class="glyphicon glyphicon-trash"></span>', [
													'class'=>'bt-buscar-label',
													'style'=>'color:#337ab7',
													'onclick'=>'f_editaTicket('.$model["cta_id"].',2,0)',
													]);
										},
						]
					]
				],
			]);

		?>
			<div class="separador-horizontal" style="margin-top:8px"></div>

			<div id="editaTicket">
				<table width="100%" border="0">
					<tr>
						<td>
							<label>Monto Ticket:</label>
							<?=
								Html::input('text','montoTicket',$model->monto, [
									'id' => 'modificaTicket_montoTicket',
									'class' => 'form-control solo-lectura',
									'style' => 'text-align:right;width:80px',
									'tabIndex' => -1,
								]);
							?>
						</td>
						<td align="right">
							<label>Monto Actual:</label>
							<?=
								Html::input('text','montoTicketActual',number_format( $monto_actual, 2, '.', ''), [
									'id' => 'modificaTicket_montoTicketActual',
									'class' => 'form-control solo-lectura',
									'style' => 'text-align:right;width:80px',
									'tabIndex' => -1,
								]);
							?>
						</td>
					</tr>
				</table>
			</div>
		<?php

	Pjax::end();
	//FIN Bloque de edición de Tickets

	//INICIO Bloque de edición de cuentas de Tickets
	Pjax::begin([ 'id' => 'PjaxEditaCuentaTicket', 'enablePushState' => false, 'enableReplaceState' => false, 'timeout' => 100000 ]);

		$cta_id 	= Yii::$app->request->get('editaCuentaTicket_cta_id', 0 );
		$action 	= Yii::$app->request->get('editaCuentaTicket_action', 1 );
		$monto_dif 	= Yii::$app->request->get('editaCuentaTicket_monto_diferencia', 0 );

		$titleModal = '';	//Variable que guardará el título de la ventana modal

		//Declaro el arreglo que contendrá los datos de la cuenta seleccionada
		$arreglo = [
			'cta_id' 	=> '',
			'cta_nom' 	=> '',
			'monto' 	=> $monto_dif,
		];

		if ( $cta_id != 0 ){

			//Obtengo los datos del arreglo
			$arreglo_sesion = Yii::$app->session->get( 'arreglo_ticket', [] );

			$arreglo = $arreglo_sesion[$cta_id];	//Seteo el arreglo

		}

		switch( $action ){

			case 0:

				$titleModal = 'Nueva Cuenta';
				break;

			case 1:

				$titleModal = 'Error';
				break;

			case 2:

				$titleModal = 'Eliminar Cuenta';
				break;

			case 3:

				$titleModal = 'Modificar Cuenta';
				break;
		}

		//Creo la ventana modal para mostrar los datos
		Modal::begin([
			'id' 		=> 'ModalEditaCuentaTicket',
			'size' 		=> 'modal-normal',
			'header'	=> '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">' . $titleModal . '</h2>',
			'closeButton' => [

				'label' => '<b>X</b>',
				'class' => 'btn btn-danger btn-sm pull-right',

			],
		]);

		?>

		<?=
			Html::input('hidden','editaCuentaTicket_action', $action, [
				'id' => 'editaCuentaTicket_action',
				]);
		?>
		<div id="editaCuentaTicket_modal">
			<table>
				<tr>
					<td>
						<label>Cuenta:</label>
					</td>
					<td>
						<?=
							Html::input( 'text', 'txCuentaID', $arreglo['cta_id'], [
								'id' 		=> 'editaCuentaTicket_cta_id',
								'class' 	=> 'form-control' . ( $action != 0 ? ' solo-lectura' : ''),
								'style' 	=> 'width:60px',
								'onchange'	=> 'f_cambiaCuenta( $( this ).val() )',
							]);
						?>
						<?=
							Html::input( 'text', 'txCuentaNombre', $arreglo['cta_nom'], [
								'id' 		=> 'editaCuentaTicket_cta_nom',
								'class' 	=> 'form-control' . ( $action != 0 ? ' solo-lectura' : ''),
								'style' 	=> 'width:260px',
							]);
						?>
					</td>
				</tr>

				<tr>
					<td>
						<label>Monto:</label>
					</td>
					<td>
						<?= Html::input('text', 'editaCuentaTicket_monto', number_format( $arreglo['monto'], 2, '.', '' ),[
								'id' => 'editaCuentaTicket_monto',
								'class' => 'form-control' . ( $action == 2 ? ' solo-lectura' : '' ),
								'style' => 'text-align:right; width:80px',
								'maxlength' => '10',
								'onkeypress' => 'return justDecimalAndMenos( $(this).val(), event )'
							]);
						?>
					</td>
				</tr>
			</table>

			<div class="text-center" style="margin-top: 8px">
				<?= Html::button( $action == 2 ? 'Eliminar' : 'Aceptar', [
						'class' => $action == 2 ? 'btn btn-danger' : 'btn btn-success',
						'onclick' => 'f_modalEditaCuentaTicket_btAceptar()',
					]);
				 ?>
				&nbsp;&nbsp;
				<?= Html::button( 'Cancelar', [
						'class' => 'btn btn-primary',
						'onclick' => 'f_modalEditaCuentaTicket_btCancelar()',
					]);
				?>
			</div>
		</div>

		<div id="modalEditaCuentaTicket_errorSummary" class="error-summary" style="display:none;margin-top: 8px">

		</div>

		<?php
		Modal::end();

		if ( $action != 1 )
			echo '<script>$( "#ModalEditaCuentaTicket" ).modal( "show" );</script>';

	Pjax::end();
	//FIN Bloque de edición de cuentas de Tickets

	//INICIO Bloque de edición de actualización de cuentas
	Pjax::begin(['id' => 'PjaxActualizaCuentas', 'enablePushState' => false, 'enableReplaceState' => false]);

		$action = Yii::$app->request->get( 'cuenta_action', 1 );
		$cuenta_id = Yii::$app->request->get( 'cuenta_cuenta_id', 1 );
		$cuenta_nom = Yii::$app->request->get( 'cuenta_cuenta_nom', '' );
		$monto = Yii::$app->request->get( 'cuenta_monto', 1 );

		$arreglo_temporal = [
			'cta_id' => $cuenta_id,
			'cta_nom' => $cuenta_nom,
			'monto' => number_format( $monto, 2, '.', '' ),
		];

		if ( $action != 1 )
		{
			$ok = 1;	//Variable que indicará que se agregó, modificó o elimino una cuenta

			//Obtengo el arreglo de cuentas presente en sesión
			$arreglo_sesion = Yii::$app->session->get( 'arreglo_ticket', []);

			//Oculto div de errores
			echo '<script>$( "#modalEditaCuentaTicket_errorSummary" ).css( "display", "none" );</script>';

			switch ( $action )
			{
				case 0:

					//Validar que no exista la cuenta ingresada
					if( array_key_exists( $cuenta_id, $arreglo_sesion ) )
					{
						$ok = 0;

						//Mostrar error
						echo '<script>mostrarErrores( ["La cuenta ya se encuentra ingresada."], "#modalEditaCuentaTicket_errorSummary" );</script>';

					} else
					{
						//Insertar una cuenta
						$arreglo_sesion[$cuenta_id] = $arreglo_temporal;
					}

					break;


				case 2:

					//Eliminar una cuenta
					unset($arreglo_sesion[$cuenta_id]);
					break;

				case 3:

					//Modificar una cuenta
					$arreglo_sesion[$cuenta_id] = $arreglo_temporal;
					break;
			}

			//Actualizo el arreglo de cuentas en sesión
			Yii::$app->session->set( 'arreglo_ticket', $arreglo_sesion );


			if ( $ok )
			{
				echo '<script>$( "#ModalEditaCuentaTicket" ).modal( "hide" );</script>';
			}

		}

	Pjax::end();
	//FIN Bloque de edición de actualización de cuentas
}
?>

<script>
//Función para ejecutar el Pjax de edición de cuentas
function f_editaTicket( cuenta, action, monto_diferencia )
{
	var datos = {};

	datos.editaCuentaTicket_cta_id = cuenta;
	datos.editaCuentaTicket_action = action;
	datos.editaCuentaTicket_monto_diferencia = monto_diferencia;

	$.pjax.reload({
		container	: "#PjaxEditaCuentaTicket",
		type		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data:datos,
	});
}

function f_modalEditaCuentaTicket_btAceptar()
{
	var action = $( "#editaCuentaTicket_action" ).val(),
		cuenta = $( "#editaCuentaTicket_cta_id" ).val(),
		cuenta_nom = $( "#editaCuentaTicket_cta_id option:selected" ).text(),
		monto = parseFloat( $( "#editaCuentaTicket_monto" ).val() ),
		error = new Array(),
		datos = {};

	if ( cuenta == 0 )
		error.push( "Ingrese una cuenta." );

	if ( monto == parseFloat( 0 ) )
		error.push( "Ingrese un monto mayor a 0." );

	if ( error.length == 0 )
	{
		//Ocultar div de errores
		$( "#modalEditaCuentaTicket_errorSummary" ).css( "display", "none" );

		datos.cuenta_action = action;
		datos.cuenta_cuenta_id = cuenta;
		datos.cuenta_cuenta_nom = cuenta_nom;
		datos.cuenta_monto = monto;

		$.pjax.reload({
			container: "#PjaxActualizaCuentas",
			type: "GET",
			replace: false,
			push: false,
			data:datos,
		})
	} else
	{
		mostrarErrores( error, "#modalEditaCuentaTicket_errorSummary" );
	}
}

function f_modalEditaCuentaTicket_btCancelar()
{
	$( "#ModalEditaCuentaTicket" ).modal( "hide" );
}



$( "#PjaxEditaCuentaTicket" ).on( "pjax:end", function(){

	if (window.jQuery) {

		alert( "12345" );
		//convierte el input en un elemento de autocompletado
		$( "#editaCuentaTicket_cta_nom" ).autocomplete({

			source: "<?= BaseUrl::toRoute('//caja/cajaticket/sugerenciacuenta'); ?>",
			select: function(event, ui){	//Cuando se selecciona un elemento del autocomplete

				var nombre = ui.item.value;

				$.get("<?= BaseUrl::toRoute('//caja/cajaticket/codigocuenta');?>&nombre=" + nombre)
				.success(function( data ){
					$("#editaCuentaTicket_cta_id").val( data );
				});
			}
		});

		//habilita que se vea el listado de autocompletado en el modal
		$(".ui-autocomplete").css("z-index", "5000");

	}

});

$( "#PjaxActualizaCuentas" ).on( "pjax:end", function(){

	$.pjax.reload( "#PjaxGrillaDetalleTicketEdicion" );

});

</script>
