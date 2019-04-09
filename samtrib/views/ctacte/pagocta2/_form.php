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

$session = new Session;

//INICIO Modal Busca Objeto Origen
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

	echo $this->render('agregarcuenta',['model'=>$model]);

	Modal::end();
//FIN Modal Agregar Cuenta


//INICIO Bloque actualiza los códigos de objeto
Pjax::begin(['id' => 'ObjNombre']);

	if (isset($_POST['trib_id']))
	{
		//Obtengo los datos y seteo variables
		$tobj = Yii::$app->request->post('tobj',0); //Código de tipo objeto
		$trib_id = Yii::$app->request->post('trib_id',0);	//Código de tributo
		$objeto_id = Yii::$app->request->post('objeto_id','');	//Código de objeto

		//Obtengo el tipo de objeto según el tributo
		$tobjtrib = utb::getTObjTrib($trib_id);

		if ($tobj == 0) {
			$tobj = $tobjtrib;
			echo "<script>";
			echo '$("#dlTObjeto").val('.$tobj.');';
			if ($tobj != 0)
				echo '$("#dlTObjeto").attr("disabled",true);';
			else
				echo '$("#dlTObjeto").attr("disabled",false);';
			echo "</script>";
		}else {
			echo "<script>";
			echo '$("#dlTObjeto").val('.$tobj.');';
			if ($tobjtrib == 0) echo '$("#dlTObjeto").attr("disabled",false);';
			echo "</script>";
		}


		//Si trib_id = 0 indica que no se ingresó ningún tributo, por lo que el tipo de tributo es ""
		if ( $trib_id == 0 )
		{
			$objeto_id = '';
			echo '<script>$("#pagocta_txTipo").val("")</script>';
		}

		//Obtengo el tipo de tributo
		$tipo = utb::getCampo('trib','trib_id = ' . $trib_id,'tipo');
		echo '<script>$("#pagocta_txTipo").val('.$tipo.')</script>';

		echo '<script>$("#pagocta_txObjID").val("")</script>';
		echo '<script>$("#pagocta_txObjNom").val("")</script>';

		//Completo el nombre del objeto en caso de que no se ingrese completo
		if (strlen($objeto_id) < 8 && $objeto_id != '')
		{
			$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
			echo '<script>$("#pagocta_txObjID").val("'.$objeto_id.'")</script>';
		}

		//Comparo el tipo de objeto que se completó recién con el tipo de objeto calculado anteriormente
		//Si son iguales, obtengo el nombre del objeto.
		if (utb::getTObj($objeto_id) == $tobj)
		{
			$objeto_nom = utb::getNombObj("'".$objeto_id."'");

			echo '<script>$("#pagocta_txObjID").val("'.$objeto_id.'")</script>';
			echo '<script>$("#pagocta_txObjNom").val("'.$objeto_nom.'")</script>';

		} else
		{
			echo '<script>$("#pagocta_txObjID").val("")</script>';
			echo '<script>$("#pagocta_txObjNom ").val("")</script>';
		}

		if ($tobj == '')	//Actualiza el tipo de objeto para la búsqueda de objeto
			$tobj = 0;

		$subcta = utb::getCampo('trib','trib_id = ' . $trib_id,'uso_subcta');

		//Habilitar sucursal si trib.uso_subcta = 1
		if ($subcta == 1)
			echo '<script>$("#pagocta_txSubcta").removeAttr("readOnly");</script>';
		else
			echo '<script>$("#pagocta_txSubcta").attr("readOnly",true);</script>';
			echo '<script>$("#pagocta_txSubcta").val("0");</script>';

		?>
		 <script>
			$.pjax.reload({container:"#PjaxModalCuentas",data:{trib_id:<?= $trib_id ?>},method:"POST",push:false});

			$("#PjaxModalCuentas").on("pjax:end", function(){

				$.pjax.reload({container:"#PjaxObjBusAvBusca",data:{tobjeto:<?= $tobj ?>},method:"POST",push:false});
				$("#PjaxModalCuentas").off("pjax:end");
			});

			$("#PjaxObjBusAvBusca").on("pjax:end", function(){

				verificaExistencia();
				$("#PjaxObjBusAvBusca").off("pjax:end");

			});

		</script>

		<?php

	}

	echo Html::hiddenInput(null, null);
Pjax::end();
//FIN Bloque actualiza los códigos de objeto

//INICIO Bloque de código que se encarga de activar o desactivar monto, dependiendo del tipo de tributo
Pjax::begin(['id'=>'verificarExistencia']);

	$tipo = Yii::$app->request->post('tipo',-1);
	$trib = Yii::$app->request->post('trib',0);
	$anio = Yii::$app->request->post('anio',0);
	$cuota = Yii::$app->request->post('cuota',0);
	$obj_id = Yii::$app->request->post('objeto','');

	/**
	 * Tipo IN ( 1, 2, 4 )
	 * 		Validar que el período exista en la Tabla de Vencimiento.
	 *			+ SI -> Validar que exista el período en la CtaCte.
	 *						+ SI -> Pago Parcial
	 *						+ NO -> ERROR
	 *			+ NO -> Pago a Cuenta
	 *
	 * Tipo NOT IN ( 1, 2, 4 )
	 *		Validar que exista el período en la CtaCte
	 *			+ SI -> Pago Parcial
	 *			+ NO -> ERROR
	 */


	//Verifico que se hayan enviado datos
	if ( $tipo != -1 && $trib != 0 && $anio != 0 && $cuota != 0 && $obj_id != '')
	{
		/*
		 * La variable existe determinará si es "Pago Parcial" o "Pago a Cuenta".
		 * $existe = 2 => "Pago a Cuenta".
		 * $existe = 1 => "Pago Parcial".
		 * $existe = 0 => ERROR.
		 */
		$existe = 0;

		//Si tipo es emisión, declarativo o periódico => Pago a Cuenta o Pago Parcial
		if ( $tipo == 1 || $tipo == 2 || $tipo == 4 )
		{
			//Verificar que exista el período en la tabla de Vencimientos
			$res = $model->validarPeriodoVenc( $trib, $anio, $cuota );

			if( !$res ){
				$existe = 2;	//Pago a Cuenta
			} else {

				//Verificar que el período ingresado exista en la tabla de cuenta corriente.
				$res = $model->validarPeriodoCtaCte( $trib, $obj_id, $anio, $cuota );

				if( $res ){
					$existe = 1;	//Pago Parcial
				} else {
					$existe = 0;	//Error
				}
			}

		} else
		{
			//Verificar que el período ingresado exista en la tabla de cuenta corriente
			$res = $model->validarPeriodoCtaCte( $trib, $obj_id, $anio, $cuota );

			if( $res ){
				$existe = 1;	//Pago Parcial
			} else {
				$existe = 0;	//Error
			}
		}

		if ( !$existe )	//ERROR
		{
			?>
			<script>
				//Deshabilito el botón "Aceptar"
				cambioEdit();

				$("#verificarExistencia").on("pjax:end", function() {

					//Mensaje de error
					mostrarErrores( ["El Período ingresado no es válido."], "#pagocta_errorSummary" );

					$.pjax.reload({container:"#reiniciaSesion",method:"POST",data:{reiniciar:"1"}});
				});

			</script>
			<?php

		} else
		{
			if ( $existe == 1 ) //Pago Parcial
			{
				//Habilitar el edit de monto
				echo '<script>$("#pagocta_txMonto").removeAttr("readOnly")</script>';

				//Mostrar el Edit de Pago Parcial
				echo '<script>$("#pagoParcial").css("display","block")</script>';
				echo '<script>$("#pagoCuenta").css("display","none")</script>';

			} else {	//Pago a Cuenta

				//Deshabilitar el edit de monto y ponerlo en 0
				echo '<script>$("#pagocta_txMonto").attr("readOnly","true")</script>';
				echo '<script>$("#pagocta_txMonto").val("")</script>';

				//Mostrar el Edit de Pago a Cuenta
				echo '<script>$("#pagoCuenta").css("display","block")</script>';
				echo '<script>$("#pagoParcial").css("display","none")</script>';
			}

			//Actualizo la grilla

			?>
			<script>
			$("#verificarExistencia").on("pjax:end", function() {

				$.pjax.reload({container:"#reiniciaSesion",method:"POST",data:{reiniciar:"1"}});
				$("#verificarExistencia").off("pjax:end");
			});

			</script>
			<?php
		}
	}

	echo Html::hiddenInput(null, null);
Pjax::end();

//INICIO Bloque que se ejecuta al presionar el botón aceptar
Pjax::begin(['id'=>'PjaxBtCargar']);

	$montoTotal = 0; //Variable que almacenará la suma de los montos

	//Obtengo los datos
	$tipo = Yii::$app->request->post('tipo',0);
	$trib = Yii::$app->request->post('trib',0);
	$obj_id = Yii::$app->request->post('obj_id','');
	$obj_nom = Yii::$app->request->post('obj_nom','');
	$subcta = Yii::$app->request->post('subcta',0);
	$anio = Yii::$app->request->post('anio',0);
	$cuota = Yii::$app->request->post('cuota',0);
	$monto = Yii::$app->request->post('monto',0);
	$fchcaja = Yii::$app->request->post('limite','');

	//Verifico que se hayan enviado datos
	if ($tipo != 0 )
	{
		/*
		 * La variable existe determinará si es "Pago Parcial" o "Pago a Cuenta".
		 * $existe = 0 => ERROR.
		 * $existe = 1 => "Pago Parcial".
		 * $existe = 2 => "Pago a Cuenta".
		 */
		$existe = 0;

		//Si tipo es emisión, declarativo o periódico
		if ($tipo == 1 || $tipo == 2 || $tipo == 4 )
		{
			//Verificar que el período ingresado exista en la tabla de cuenta corriente.
			//Si no existe, verificar en la tabla de vencimiento
			$res = $model->validarPeriodoCtaCte($trib,$obj_id,$anio,$cuota);

			if ($res == 1)	//Existe el Período en la Cuenta Corriente ==> "Pago Parcial"
			{
				$existe = 1;
			} else
			{
				//Verificar que el período ingresado exista en la tabla de vencimientos
				$res = $model->validarPeriodoVenc($trib,$anio,$cuota);

				if ($res == 1) //Existe el Período en la tablade Vencimiento ==> "Pago A Cuenta"
				{
					$existe = 2;

				} else	//ERROR
				{
					$existe = 0;
				}
			}

		} else
		{
			//Verificar que el período ingresado exista en la tabla de cuenta corriente
			$res = $model->validarPeriodoCtaCte($trib,$obj_id,$anio,$cuota);

			if ($res == 1)	//Existe el Período en la Cuenta Corriente ==> "Pago Parcial"
			{
				$existe = 1;

			} else	//ERROR
			{
				$existe = 0;
			}
		}

		if ($existe == 0)	//ERROR
		{
			?>
			<script>
				//Deshabilito el botón "Aceptar"
				cambioEdit();

				//Mensaje de error
				mostrarErrores( ["El Período ingresado no es válido."], "#pagocta_errorSummary" );

			</script>
			<?php

		} else
		{
			if ($existe == 1)	//"Pago Parcial"
			{
				//Inhabilitar los botones "Agregar" y "Quitar". Los Botones "Quitar" se deshabilitan en la grilla
				echo '<script>$("#btAgregarCuenta").attr("disabled","true")</script>';

				//Ctacte.est IN ('T','D')
				$cond = "trib_id = " . $trib . " AND obj_id = '" . $obj_id . "' AND subcta = " . $subcta . " AND anio = " . $anio . " AND cuota = " . $cuota;

				$estCtacte = utb::getCampo('ctacte',$cond,'est');

				if ($estCtacte == 'T' || $estCtacte == 'D')
				{
					//Obtengo la fecha de venc de la ctacte
					$fchvenc = utb::getCampo('ctacte',$cond,"to_char(fchvenc,'dd/MM/yyyy') as fchvenc");

					//Cargar las cuentas
					$montoTotal = $model->cargarCuenta($trib,$obj_id,$anio,$cuota,$monto,$fchvenc,$fchcaja);

					//Seteo el valor a monto total
					echo '<script>$("#pagocta_txMontoTotal").val("'.number_format($montoTotal,2,',','.') .'");</script>';

					//Habilito el botón "Aceptar"
					echo '<script>$("#btAceptarPagocta").removeAttr("disabled")</script>';

					//Actualizo la grilla
					echo '<script>actualizarGrilla(1);</script>';

					//Deshabilito el div superior
					echo '<script>$("#div_superior").prop("class","form-panel disabled");</script>';


				} else
				{
				?>
				<script>
					//Deshabilito el botón "Aceptar"
					cambioEdit();

					//Mensaje de error
					mostrarErrores( ["El estado del período no permite un Pago Parcial."], "#pagocta_errorSummary" );

				</script>
				<?php

				}

			} else	//Pago a Cuenta
			{
			?>
			<script>

				//Habilitar los botones "Agregar" y "Quitar"
				$("#btAgregarCuenta").removeAttr("disabled");

				//Deshabilitar Div Superior
				$("#div_superior").prop("class","form-panel disabled");

				//Reinicio la grilla
				$("#PjaxBtCargar").on("pjax:end",function(){
					$.pjax.reload({container:"#reiniciaSesion",method:"POST",data:{reiniciar:"1",}});
					$("#PjaxBtCargar").off("pjax:end");
				});

				//Deshabilito el div superior
				$("#div_superior").prop("class","form-panel disabled");

			</script>
			<?php
			}

			//Habilito el botón "Aceptar"
			echo '<script>$("#btAceptarPagocta").removeAttr("disabled");</script>';
		}
	}

	echo Html::hiddenInput(null, null);
Pjax::end();
//FIN Bloque que se ejecuta al presionar el botón aceptar


$title = 'Nuevo Pago a Cuenta';
$this->params['breadcrumbs'][] = $title;

$form = ActiveForm::begin([
			'id'=>'frmPagocta',
			'action'=>['create'],]);

?>
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
	else if ($("#pagocta_txMonto").attr("readonly") != "readonly" && $("#pagocta_txMonto").val() == '')
		$("#pagocta_txMonto").focus();
	else
		$("#btCargarDatos").focus();
}
</script>

<div class="pagocta_from">
	<h1><?= Html::encode($title) ?></h1>

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php
			Pjax::begin(['id'=>'errorPagoctaForm']);

			$mensaje = '';

			$mensaje = Yii::$app->request->post('mensaje','');
			$m = Yii::$app->request->post('m',2);

			if (isset($alert) && $alert != '')
			{
				$mensaje = $alert;
				$alert = '';
			}

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaMensajePagoctaForm',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaMensajePagoctaForm').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<!-- Dibujo un input oculto que almacenará el tipo de tributo -->
<?= Html::input('hidden','txTipo','',['id'=>'pagocta_txTipo']) ?>

<div id="div_superior" class="form-panel" style="padding-right:10px;padding-bottom:5px;margin-right:120px">
<table border="0">
	<tr>
		<td width="50px"><label>Tributo:</label></td>
		<td>
            <?=
                Html::dropDownList('dlTrib',$model->trib_id,utb::getAux('trib','trib_id','nombre',1,'tipo IN (1,2,3,4) OR trib_id = 1 OR trib_id = 3'),[
                    'id'=>'pagocta_dlTrib',
					'class'=>'form-control',
					'style'=>'width:100	%;text-align:left',
					'onchange'=>'$.pjax.reload({container:"#ObjNombre",method:"POST",data:{trib_id:$(this).val()}});',
				]);
            ?>
        </td>
		<td id="pagoCuenta" style="display:none" valign="top"><h3><strong>Pago a Cuenta</strong></h3></td>
		<td id="pagoParcial" style="display:none" valign="top"><h3><strong>Pago Parcial</strong></h3></td>
	</tr>
	<tr>
		<td width="50px"><label>Objeto:</label></td>
		<td colspan="3">
			<?= Html::dropDownList('dlTObjeto',null,utb::getAux('objeto_tipo'),
					[	'id'=>'dlTObjeto',
						'class'=>'form-control',
						'style'=>'width:100	%;text-align:left',
						'onchange'=>'$.pjax.reload({container:"#ObjNombre",method:"POST",data:{tobj:$(this).val(),trib_id:$("#pagocta_dlTrib").val()}});',
					]) ?>
			<?= Html::input('text','txObjID',$model->obj_id,[
					'id' => 'pagocta_txObjID',
					'class' => 'form-control',
					'style' => 'width:70px;text-align:center',
					'maxlength' => '8',
					'onchange' => 'cambioEdit();$.pjax.reload({container:"#ObjNombre",method:"POST",data:{objeto_id:$(this).val(),tobj:$("#dlTObjeto").val(),trib_id:$("#pagocta_dlTrib").val()}})',
				]);
			?>
			<!-- botón de búsqueda modal -->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',['class' => 'bt-buscar','id'=>'pagocta_btBuscaObj','onclick'=>'$("#BuscaObjpagocta_btOrigenBusca, .window").modal("show")']); ?>
			<!-- fin de botón de búsqueda modal -->
			<?= Html::input('text','txObjNom',$model->obj_nom,['id'=>'pagocta_txObjNom','class'=>'form-control solo-lectura','style'=>'width:380px']); ?>
		</td>
	</tr>
</table>

<table border="0">
	<tr>
		<td width="50px"><label>SubCta:</label></td>
		<td width="30px"><?= Html::input('text','txSubcta',$model->subcta,['id'=>'pagocta_txSubcta','class'=>'form-control','style'=>'width:100%;text-align:center','onchange'=>'cambioEdit();','readOnly'=>true]) ?></td>
		<td width="10px"></td>
		<td>
			<label>Año/Plan:</label>
			<?= Html::input('text','txAnio',$model->anio,[
								'id'=>'pagocta_txAnio',
								'class'=>'form-control',
								'style'=>'width:40px;text-align:center',
								'onchange'=>'verificaExistencia();']) ?>
		</td>
		<td width="10px"></td>
		<td>
			<label>Cuota:</label>
			<?= Html::input('text','txCuota',$model->cuota,['id'=>'pagocta_txCuota','class'=>'form-control','style'=>'width:30px;text-align:center','onchange'=>'verificaExistencia()']) ?>
		</td>
		<td width="10px"></td>
		<td>
			<label>Monto:</label>
			<?=
				Html::input('text','txMonto',$model->monto,[
					'id'=>'pagocta_txMonto',
					'class'=>'form-control',
					'style'=>'width:80px;text-align:right',
					'onkeypress'	=> 'return justDecimal( $( this ).val(), event )',
					'onchange'=>'cambioEdit();',
					'readOnly'=>true,
				]);
			?>
		</td>
		<td width="10px"></td>
		<td>
			<label>Límite:</label>
			<?= DatePicker::widget(['id'=>'pagocta_txFchlimite',
									'name'=>'txFchlimite',
									'dateFormat' => 'dd/MM/yyyy',
									'value' => Fecha::usuarioToDatePicker(Fecha::getDiaActual()),
									'options' => [	'class'=>'form-control',
													'style'=>'width:80px;text-align:center',
													'onchange'=>'cambioEdit()']]); ?>
		</td>
		<td width="30px"></td>
		<td><?= Html::Button('Cargar',['id'=>'btCargarDatos','class'=>'btn btn-primary','onclick'=>'btCargar()']); ?></td>
	</tr>
</table>
</div>

<div class="form-panel" style="padding-right:10px;margin-right:120px">
<table>
	<tr>
		<td width="50px"><label>Obs:</label></td>
		<td><?= Html::textarea('txObs',$model->obs,['id'=>'pagocta_txObs','class'=>'form-control','style'=>'width:580px;max-width:580px;height:40px;max-height:120px']) ?> </td>
	</tr>
</table>
</div>

<?php

ActiveForm::end();

?>

<!-- INICIO Grilla Cuentas -->
<div class="form-panel" style="padding-right:10px;margin-right:120px">
<table width="100%">
	<tr>
		<td width="40px"><label>Cuentas:</label></td>
		<td align="right">
			<?= Html::button('<span class="glyphicon glyphicon-plus"></span>',[
					'id' => 'btAgregarCuenta',
					'class' => 'btn btn-primary',
					'onclick' => '$("#AgregaCuentaPagocta, .window").modal("show")',
					'disabled' => true,
				]);
			?>
		</td>
	</tr>
</table>

<?php

Pjax::begin(['id'=>'PjaxGrillaCuenta']);

	$session->open();

	$cod = Yii::$app->request->post('cod',0);	//Variable que habilita o deshabilita el eliminar las cuentas en sesión

	$cuenta_id = Yii::$app->request->post('cuenta_id','');
	$cuenta_nom = Yii::$app->request->post('cuenta_nom','');
	$monto = Yii::$app->request->post('monto','');

	//Si se envian datos por POST desde la ventana modal
	if ($cuenta_id != '')
	{
		//CReo un arreglo temporal
		$arregloTemporal = [
				'cta_id' => $cuenta_id,
				'cta_nom' => $cuenta_nom,
				'monto' => $monto,
		];

		$array = [];

		$array[$cuenta_id] = $arregloTemporal;
		$arreglo = $session['arregloCuentaPagocta'];

		//Elimino si el elemento que intento agregar ya se encuentra en sesión
		unset($arreglo[$cuenta_id]);

		$arreglo = $arreglo + $array;
		$session['arregloCuentaPagocta'] = $arreglo;
	}

	$dataProvider = new ArrayDataProvider(['allModels' => $session['arregloCuentaPagocta']]);

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

	    			'delete' => function($url, $model, $key) use ($cod)
	    						{
	    							if ($cod == 0)
	    							{
	    								return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
											'class' => 'bt-buscar-label',
											'style' => 'color:#337ab7',
											'onclick'=>'$.pjax.reload({	container:"#eliminarCuenta",' .
																		'method:"POST",' .
																		'data:{codigo:"'.$model['cta_id'].'",' .
																		'}});']) ;

	    							}	else
	    								return null;

								},
	    			]
	    		]
        	],
		]);

	//Actualizo el monto Total
	$montoTotal = 0;

	$array = $session['arregloCuentaPagocta'];
	foreach ($array as $ar)
	{
		$montoTotal += $ar['monto'];
	}

	//Seteo el valor a monto total
	echo '<script>$("#pagocta_txMontoTotal").val("'.number_format($montoTotal,2,'.','').'");</script>';

	$session->close();

	//Pongo el foco en el elemento que corresponda
	echo '<script>setFocus();</script>';

Pjax::end();

?>
<table width="100%">
	<tr>
		<td align="right">
			<label>Monto Total:</label>
			<?= Html::input('text','txMontoTotal',number_format(0, 2, '.', ''), ['id'=>'pagocta_txMontoTotal','class'=>'form-control solo-lectura','style'=>'width:80px;text-align:right']) ?>
		</td>
	</tr>
</table>
</div>
<!-- FIN Grilla Cuentas -->

<?= Html::button('Aceptar',['id'=>'btAceptarPagocta','class'=>'btn btn-success','onclick' => 'btAceptar()','disabled'=>true]); ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?= Html::a('Cancelar',['view','id'=>$model->pago_id],['class'=>'btn btn-primary']); ?>

<div id="pagocta_errorSummary" class="error-summary" style="display:none;margin-top:8px;margin-right:120px">

	<ul>
	</ul>

</div>

</div>


<?php

Pjax::begin(['id'=>'reiniciaSesion']);

	$reiniciar = Yii::$app->request->post('reiniciar',0);

	if ($reiniciar == 1)
	{

		$session->open();
		$session['arregloCuentaPagocta'] = [];
		$session->close();

		echo '<script>$("#reiniciaSesion").on("pjax:end", function () {' .
				'actualizarGrilla(0);' .
				'$("#reiniciaSesion").off("pjax:end");' .
				'});</script>';

	}

	echo Html::hiddenInput(null, null);

Pjax::end();

//INICIO Bloque de código para eliminar una cuenta
Pjax::begin(['id'=>'eliminarCuenta']);

	$codigo = Yii::$app->request->post('codigo','');

	if ($codigo != '')
	{
		$session->open();
		$arreglo = $session['arregloCuentaPagocta'];

		//Elimino si el elemento que intento agregar ya se encuentra en sesión
		unset($arreglo[$codigo]);

		$session['arregloCuentaPagocta'] = $arreglo;
		$session->close();

		//Si la cantidad de elementos del arreglo es 0, se debe Deshabilitar el Botón "Aceptar"
		if (count($arreglo) == 0)
			echo '<script>$("#btAceptarPagocta").attr("disabled","true");</script>';

		//Actualizo la grilla

		echo '<script>$("#eliminarCuenta").on("pjax:end",function(){actualizarGrilla(0);$("#eliminarCuenta").off("pjax:end");})</script>';
	}

Pjax::end();
//FIN Bloque de código para eliminar una cuenta

//INICIO Bloque de código para grabar una cuenta
Pjax::begin(['id'=>'PjaxGrabarPago']);

	//Obtengo los datos
	$tipo = Yii::$app->request->post('tipo',0);
	$trib = Yii::$app->request->post('trib',0);
	$obj_id = Yii::$app->request->post('obj_id','');
	$obj_nom = Yii::$app->request->post('obj_nom','');
	$subcta = Yii::$app->request->post('subcta',0);
	$anio = Yii::$app->request->post('anio',0);
	$cuota = Yii::$app->request->post('cuota',0);
	$monto = Yii::$app->request->post('monto',0);
	$fchcaja = Yii::$app->request->post('limite','');
	$grabar = Yii::$app->request->post('grabar',0);

	//Verifico que se hayan enviado datos
	if ($grabar == 1)
	{
		//Verificar que no exista emisión DJ
		if ($model->existeEmiDJ($trib,$obj_id,$anio,$cuota) == 1)	//Existe Emisión DJ
		{
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

			echo "<center>";
			echo "<p><label>Existe una liquidación para el período.<br /> ¿Desea continuar?</label></p><br />";

			echo Html::button('Aceptar',['class' => 'btn btn-success','onclick'=>'$("#frmPagocta").submit()']);

			echo "&nbsp;&nbsp;";
	 		echo Html::button('Cancelar',['class' => 'btn btn-primary','onclick'=>'$("#ModalExisteEmisionDDJJ").modal("hide")']);
	 		echo "</center>";

	 		Modal::end();
			//FIN Modal Confirmación Existencia Emisión DDJJ

			echo '<script>$("#ModalExisteEmisionDDJJ").modal("show");</script>';
		} else
		{
			//Envio el formulario
			echo '<script>$("#frmPagocta").submit();</script>';
		}

	}

Pjax::end();
//FIN Bloque de código para grabar una cuenta

?>

<script>
function ocultarLabelsPagos()
{
	//Oculto los edit de Pago Parcial y Pago a Cuenta
	$("#pagoParcial").css("display","none");
	$("#pagoCuenta").css("display","none");
}

function verificaExistencia()
{
	/*
	 * Función que se ejecuta cuando se realiza un cambio en algún edit.
	 * Deshabilita el botón "Aceptar".
	 * Deshabilita el botón para "Agregar Cuenta".
	 * Oculto los edit de Pago Parcial y Pago a Cuenta
	 */

	cambioEdit();

	//Oculto los edit de Pago Parcial y Pago a Cuenta
	ocultarLabelsPagos();

	//Desactivo el edit de monto y lo pongo en ""
	$("#pagocta_txMonto").attr("readOnly","true");
	$("#pagocta_txMonto").val("");

	var tipo = $("#pagocta_txTipo").val();
	var trib = parseInt($("#pagocta_dlTrib").val());
	var anio = parseInt($("#pagocta_txAnio").val());
	var cuota = parseInt($("#pagocta_txCuota").val());
	var objeto = $("#pagocta_txObjID").val();

	if (!isNaN(trib) && trib > 0 && !isNaN(anio) && anio > 1970 && anio < 10000 && !isNaN(cuota) && cuota > 0 && objeto.length == 8)
	{
		$.pjax.reload({	container:"#verificarExistencia",
					method:"POST",
					push:false,
					replace:false,
					data:{
						tipo:tipo,
						trib:trib,
						anio:anio,
						cuota:cuota,
						objeto:objeto,
					}});

	} else {
		$.pjax.reload({container:"#reiniciaSesion",method:"POST",data:{reiniciar:"1"}});
	}

}

/* Función que deshabilita los controles al modificar un edit */
function cambioEdit()
{
	//Desabilita el botón "Aceptar"
	$("#btAceptarPagocta").attr("disabled","true");

	//Deshabilita el botón para "Agregar Cuenta"
	$("#btAgregarCuenta").attr("disabled","true");

}

function btCargar()
{
	ocultarErrores( "#pagocta_errorSummary" );

	var tipo = $("#pagocta_txTipo").val(),
		trib = $("#pagocta_dlTrib").val(),
		obj_id = $("#pagocta_txObjID").val(),
		obj_nom = $("#pagocta_txObjNom").val(),
		subcta = $("#pagocta_txSubcta").val(),
		anio = $("#pagocta_txAnio").val(),
		cuota = $("#pagocta_txCuota").val(),
		monto = $("#pagocta_txMonto").val(),
		limite = $("#pagocta_txFchlimite").val(),
		error = new Array();

	//Validar que se ingrese un tributo.
	if (trib == 0 || trib == '')
		error.push( "Ingrese un tributo." );

	//Validar que se ingrese un objeto.
	if (obj_id == '')
		error.push( "Ingrese un objeto." );

	if (obj_id != '' && obj_nom == '')
		error.push( "Ingrese un objeto válido." );

	//Setear valor a subcta en caso de que venga vacío.
	if (subcta == '')
		subcta = 0;

    //Validar que se ingrese un año
	if ( anio == '' ){
        error.push( "Ingrese un año." );
    } else if ( trib != 1 ){

        if ( anio.length < 4 ) //Validar que se ingrese un año válido
    		error.push( "Ingrese un año válido." );
    }

	//Validar que se ingrese una cuota
	if (cuota == '')
		error.push( "Ingrese una cuota." );

	//Validar que se ingrese un monto en caso de que el edit esté activado
	if ($("#pagocta_txMonto").attr("readOnly") != "readonly" && monto == '')
		error.push( "Ingrese un monto." );

	if ( error.length == 0 )
		$.pjax.reload({	container:"#PjaxBtCargar",
						method:"POST",
						data:{
							tipo:tipo,
							trib:trib,
							obj_id:obj_id,
							obj_nom:obj_nom,
							subcta:subcta,
							anio:anio,
							cuota:cuota,
							monto:monto,
							limite:limite,
						}});
	else
		mostrarErrores( error, "#pagocta_errorSummary" );
}

function actualizarGrilla(cod)
{
	//Actualizo la grilla
	$.pjax.reload({
		container:"#PjaxGrillaCuenta",
		method:"POST",
		push:false,
		replace:false,
		data:{
			cod:cod,
		}});
}

function btAceptar()
{
	var tipo = $("#pagocta_txTipo").val(),
		trib = $("#pagocta_dlTrib").val(),
		obj_id = $("#pagocta_txObjID").val(),
		obj_nom = $("#pagocta_txObjNom").val(),
		subcta = $("#pagocta_txSubcta").val(),
		anio = $("#pagocta_txAnio").val(),
		cuota = $("#pagocta_txCuota").val(),
		monto = $("#pagocta_txMonto").val(),
		limite = $("#pagocta_txFchlimite").val(),

		error = new Array();

	//Validar que se ingrese un tributo.
	if (trib == 0 || trib == '')
		error.push( "Ingrese un tributo." );

	//Validar que se ingrese un objeto.
	if (obj_id == '')
		error.push( "Ingrese un objeto." );

	if (obj_id != '' && obj_nom == '')
		error.push( "Ingrese un objeto válido." );

	//Setear valor a subcta en caso de que venga vacío.
	if (subcta == '')
		subcta = 0;

	//Validar que se ingrese un año
	if ( anio == '' ){
        error.push( "Ingrese un año." );
    } else if ( trib != 1 ){

        if ( anio.length < 4 ) //Validar que se ingrese un año válido
    		error.push( "Ingrese un año válido." );
    }

	//Validar que se ingrese una cuota
	if (cuota == '')
		error.push( "Ingrese una cuota." );

	//Validar que se ingrese un monto en caso de que el edit esté activado
	if ($("#pagocta_txMonto").attr("readOnly") != "readonly" && monto == '')
		error.push( "Ingrese un monto." );

	if ( error.length == 0 )
		$.pjax.reload({	container:"#PjaxGrabarPago",
						method:"POST",
						data:{
							tipo:tipo,
							trib:trib,
							obj_id:obj_id,
							obj_nom:obj_nom,
							subcta:subcta,
							anio:anio,
							cuota:cuota,
							monto:monto,
							limite:limite,
							grabar:1,
						}});
	else
		mostrarErrores( error, "#pagocta_errorSummary" );
}
</script>
