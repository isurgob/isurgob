<?php
/**
 * Vista que será mostrada como ventana modal de Titular.
 * Permitirá seleccionar nuevos Titulares y modificar Titulares existentes.
 *
 * @param $modelObjeto Modelo correspondiente a objeto
 * @param $consulta Variable que determina el origen de la llamada a la vista
 *
 *
 */

use yii\helpers\Html;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use yii\web\Session;
use yii\bootstrap\Alert;
use yii\helpers\BaseUrl;

//use app\models\objeto\Objeto;


 /*
  * Me llegan:
  *
  * 	$modelObjeto == El modelo de objeto
  * 	$action == Identificador para conocer si se llega desde create, update o delete.
  * 		$action = 1 => Create
  * 		$action = 2 => Update
  * 		$action = 3 => Delete
  *		$codigo == Código del elemento que se seleccionó
  *		$nombre == Nombre del elemento que se seleccionó
  *		$porc == Porcentaje del elemento que se seleccionó
  *		$relac == Nombre del tipo de relación que se seleccionó
  *		$princ == Si el titular es el titular principal
  *		$tvinc == Código de vínculo
  *		$BD == Identificador del titular presente en la BD. BD = 1 => Presente en la BD.
  *															BD = 0 => Presente solo en memoria.
  *		$condRelacion == Condicion para obtener los tipos de relacion
  *		$transferencia == Si se encuentra en la transferencia de titulares. 1 = Si, 2 = No
  *		$denunciaImpositiva == Si se encuentra en una denuncia impositiva. 1 = Si.
  */

	/*
	 * El pjax form-nuevoTitular se encarga de recargar toda la página.
	 * Se usa para que se actualicen los datos de la sesión ya que el modal se dibuja junto
	 * con la vista que lo crea y no se dibuja cada vez que se lo invoca.
	 * De este modo, los datos se recargan con los valores enviados desde "titular"
	 */
	Pjax::begin(['id'=>'form-nuevoTitular', 'enableReplaceState' => false, 'enablePushState' => false]);

	/*
	 * INICIO Crear variables que se mostrarán en la vista
	 */
		//$consulta = isset($consulta) ? $consulta : intval(Yii::$app->request->get('consulta', 1));
        $consulta = isset($action) ? $action : intval(Yii::$app->request->get('action', 1));

		//Variable que se usará para determinar si se presionó el botón aceptar
		$envio = 0;

	/*
	 * FIN Crear variables que se mostrarán en la vista
	 */

	// INICIO Bloque de código que se encarga de crear, actualizar o eliminar los elementos del arreglo de titulares
	Pjax::begin(['id'=>'manejoTitulares', 'enableReplaceState' => false, 'enablePushState' => false]);

	 	$session = new Session();
		$session->open();

		//Obtener el código
		$codigo = isset($codigo) ? $codigo : '';

		$nom_contribuyente = isset($nombre) ? $nombre : '';//if (isset($nombre)) $nom_contribuyente = $nombre;
		$porcentaje = isset($porc) ? $porc : 100; //if (isset($porc)) $porcentaje = $porc;


		//Obtengo los datos correspondientes al nuevo titular o a la actualización de uno ya existente
		//$consulta = $session->get('action', $consulta);

		$cod_contribuyente = trim(Yii::$app->request->get('cod_contribuyente', $codigo));
		$nom_contribuyente = trim(Yii::$app->request->get('nom_contribuyente', $nom_contribuyente));
		$porcentaje = floatval(Yii::$app->request->get('porc', 100));
		$relacion = Yii::$app->request->get('relacion', '');
		$princ = trim(Yii::$app->request->get('princ', ''));
		$tvinc = intval(Yii::$app->request->get('tvinc', -1));
		$envio = intval(Yii::$app->request->get('envio', 0));
		$transferencia = isset($transferencia) ? $transferencia : intval(Yii::$app->request->get('transferencia', 0));
		$denunciaImpositiva= isset($denunciaImpositiva) ? $denunciaImpositiva : intval(Yii::$app->request->get('denunciaImpositiva', 0));
		$estado = Yii::$app->request->get('est', 'B');

		if ($transferencia == 1) {
			$estado = $session->get('est', 'B');
			$cod_contribuyente = $cod_contribuyente != '' ? $cod_contribuyente : $session->get('codigo', '');
			$nom_contribuyente = $nom_contribuyente != '' ? $nom_contribuyente : $session->get('nombre', '');
			$princ = $princ != '' ? $princ : $session->get('princ', '');
			$porcentaje = $porcentaje > -1 ? $porcentaje : $session->get('porc');
			$tvinc = $tvinc > -1 ? $tvinc : $session->get('tvinc', 0);
		}	
		
		if (isset($_GET['tituno'])) {
			$arr = unserialize(urldecode(stripslashes( Yii::$app->request->get( 'tituno' ))));
			$estado = $arr['est'];
			$cod_contribuyente = $cod_contribuyente != '' ? $cod_contribuyente : $arr['codigo'];
			$nom_contribuyente = $nom_contribuyente != '' ? $nom_contribuyente : $arr['apenom'];
			$princ = $princ != '' ? $princ : $arr['princ'];
			$porcentaje = $porcentaje > -1 ? $porcentaje : $arr['porc'];
			$tvinc = $tvinc > -1 ? $tvinc : $arr['tvinc'];
		}
		
		$titular = $princ != '';

		//almacena el arreglo donde se estan almacenando los titulares
		$arr = 'arregloTitulares';

		if($transferencia == 1)
			$arr = 'arregloTitularesTransferencia';
		else if($denunciaImpositiva == 1)
			$arr= 'arregloTitularesDenunciaImpositiva';
		
		if ($envio === 1 && $cod_contribuyente != '' && $nom_contribuyente != '')
		{
			if ($porcentaje <= 100 && $porcentaje > 0)
			{
				switch($consulta)
				{

					//Se agrega un nuevo elemento al array
					case 1:

						//En $datos obtengo los datos de la persona para poder completar la grilla
						$datos = $modelObjeto->obtenerDatosTitular($cod_contribuyente);

						$arregloTemporal = [
							'num' => $cod_contribuyente,
							'apenom' => $nom_contribuyente,
							'tdoc' => $datos[$cod_contribuyente]['tdoc'],
							'ndoc' => $datos[$cod_contribuyente]['ndoc'],
							'tvinc' => $tvinc,
							'tvinc_nom' => $relacion,
							'princ' => $princ,
							'porc' => $porcentaje,
							'est' => 'A',
							'BD' => '0',
							];

						$array = [];

						$array[$cod_contribuyente] = $arregloTemporal;
						$arregloTitulares = $session->get($arr, []);
						if (isset($_GET['tit'])) $arregloTitulares = unserialize(urldecode(stripslashes( Yii::$app->request->get( 'tit' ))));

						//Verifica si el código de persona no se encuentra en el arreglo
						//array_key_exists devuelve true si encuentra la key en el arreglo
						if (count($arregloTitulares) > 0 && array_key_exists($cod_contribuyente, $arregloTitulares))
						{
						?>
						<script>
						$.pjax.reload({
							container : "#error",
							push : false,
							replace : false,
							data : {
								"mensaje" : "La persona que intenta agregar ya se ingresó."
								},
							type : "GET"
						});
						</script>

						<?php
						} else {

							if (count($arregloTitulares) == 0){
								$array[$cod_contribuyente]['princ'] = 'Princ';	//establece que el primer titular que se agrega es el titular principal
								$arregloTitulares = $array;
							}
							else
								$arregloTitulares = array_merge($arregloTitulares, $array);

							$arreglo = $arregloTitulares;

							//Código que se ejecuta en caso de que se haya tildado la opción de titular
							if ($princ != '')
							{
								//Se debe eliminar la titularidad que pueda estar en algún otro titular
								$keys = array_keys($arregloTitulares);

								foreach ($keys as $clave)
								{
									$arreglo[$clave]['princ'] = '';
								}


								//Se debe setear al titular actual como principal
								$arreglo[$cod_contribuyente]['princ'] = $princ;
								$arregloTitulares= $arreglo;


								$modelObjeto->nombre = $arreglo[$cod_contribuyente]['apenom']; //Seteo el nombre del objeto como el nombre del titular principal
								echo '<script>$("#objeto-nombre").val("' . $modelObjeto->nombre . '")</script>';

							}

							$session->set($arr, $arregloTitulares);

							?>
							<script>
							$.pjax.reload({
								container : "#tit-actualizaGrilla",
								replace : false,
								push : false,
								type : "POST",
								data : {
									"tit" : "<?= urlencode( serialize($arregloTitulares) ) ?>"
								}
							});

							$("#objeto-nombre").val("<?= $arreglo[$cod_contribuyente]['apenom'] ?>");
							</script>
							<?php
						}

						break;

					//Se modifica un elemento del array
					case 2:


						//Obtengo el arreglo que se encuentra en sesión
						$arreglo = $session->get($arr, []);
						if (isset($_POST['tit'])) $arreglo = unserialize( urldecode( stripslashes( $_POST['tit'] ) ) );
												
						//Actualizo los datos
						if(array_key_exists($cod_contribuyente, $arreglo)){

							$arreglo[$cod_contribuyente]['tvinc'] = $tvinc;
							$arreglo[$cod_contribuyente]['tvinc_nom'] = $relacion;
							$arreglo[$cod_contribuyente]['porc'] = $porcentaje;
						}

						//Código que se ejecuta en caso de que se haya tildado la opción de titular
						if ($princ == '')
						{
							$arreglo[$cod_contribuyente]['princ'] = '';
						} else
						{
							//Se debe eliminar la titularidad que pueda estar en algún otro titular
							$keys = array_keys($session[$arr]);

							foreach ($keys as $clave)
							{
								$arreglo[$clave]['princ'] = '';
							}
							
							//Se debe setear al titular actual como principal
							$arreglo[$cod_contribuyente]['princ'] = $princ;
							$modelObjeto->nombre = $arreglo[$cod_contribuyente]['apenom']; //Seteo el nombre del objeto como el nombre del titular principal
						}

						$session->set($arr, $arreglo);

						?>
						<script>
						$.pjax.reload({
							container : "#tit-actualizaGrilla",
							type : "POST",
							replace : false,
							push : false,
							data : {
									"tit" : "<?= urlencode( serialize($arreglo) ) ?>"
								}
						});

						$("#objeto-nombre").val("<?= $arreglo[$cod_contribuyente]['apenom'] ?>");
						</script>
						<?php

						break;

					//Se elimina un elemento del array
					case 3:
						/*
						 * Los elementos que tengan BD = 0, serán eliminados del arreglo.
						 * Los elementos que tengan BD = 1, se mantendrán en el arreglo con estado = B.
						 */
						$arreglo = $session[$arr];

						if ($arreglo[$cod_contribuyente]['princ'] == 'Princ')
							$borra = '$("#objeto-nombre").val("");';
						else
							$borra = '';



						if($arreglo[$cod_contribuyente]['BD'] == '1')
						{
							$arreglo[$cod_contribuyente]['est'] = 'B';
							$arreglo[$cod_contribuyente]['princ'] = '';
						}
						else
						{
							unset($arreglo[$cod_contribuyente]);
						}

						$session[$arr] = $arreglo;

						?>
						<script>
						$.pjax.reload({
							container : "#tit-actualizaGrilla",
							push : false,
							replace : false,
							type : "POST",
							data : {
									"tit" : "<?= urlencode( serialize($arreglo) ) ?>"
								}
						});
						<?= $borra ?>
						</script>

						<?php
						break;
				}

			$session->close();

			} else
			{
			?> <script>
				$.pjax.reload({
					container:"#error",
					replace : false,
					push : false,
					data:{
						"mensaje" : "Ingrese un valor de porcentaje correcto."
						},
					type:"GET"
					});
				</script>
			<?php
			}

		}

	Pjax::end();

	//Código que actualiza el nombre de la persona cuando ingresa el código de persona
	Pjax::begin(['id'=>'actualizaNombre', 'enableReplaceState' => false, 'enablePushState' => false]);

		$cod = trim(Yii::$app->request->get('cod_contrib', ''));

		if(strlen($cod) > 0){

			if(strlen($cod) < 8)
				$cod = utb::GetObjeto(3, intval($cod));

			$nom = utb::getNombObj("'" . $cod . "'");
			?>
			<script type="text/javascript">
			$("#tit-cod_contribuyente").val("<?= $cod ?>");
			$("#tit-nom_contribuyente").val("<?= $nom ?>");
			</script>
			<?php
		}

	Pjax::end();

?>

<div class="nuevoTitular-view">
<div class="form-nuevoTitular">

<table width="100%">
	<tr>
		<td width="60px"><label>Objeto</label>
		<td><?= Html::input('text','tit-obj_id', $modelObjeto->obj_id,['id'=>'tit-obj_id','class'=>'form-control', 'style'=>'background:#E6E6FA; width:70px;']) ?></td>
		<td width="30px"></td>
		<td><label>Contribuyente</label></td>
		<td colspan="2">
		<?= Html::input('text','tit-cod_contribuyente',$cod_contribuyente,['id'=>'tit-cod_contribuyente', 'class'=>'form-control', 'style'=>'width:70px', 'onchange'=> 'cambiaCodContribuyente($(this).val());']); ?>
		<?= Html::Button("<i class='glyphicon glyphicon-search'></i>",['id'=>'boton-personas','class' => 'bt-buscar', 'onClick' => '$("#BuscaTitular").toggleClass("hidden");'])?>
		<?= Html::input('text','tit-nom_contribuyente',$nom_contribuyente,['id'=>'tit-nom_contribuyente', 'class'=>'form-control', 'style'=>'width:250px; background:#E6E6FA;', 'disabled'=>'disabled']); ?>
		</td>
	</tr>

	<tr>
		<td width="60px"><label>Relación</label>
		<td><?= Html::dropDownList('tit-relacion', $tvinc, utb::getAux('persona_tvinc','cod','nombre',0,$condRelacion), ['id'=>'tit-relacion', 'class'=>'form-control', 'style'=>'width:160px','onchange'=>'tipoRelacion($(this).val())'])?></td>
		<td width="30px"></td>
		<td width="100px"><label>Porcentaje</label></td>
		<td><?= Html::input('text','tit-porc',$porcentaje,['id'=>'tit-porc', 'class'=>'form-control', 'style'=>'width:70px;', 'onkeypress' => 'return justDecimal($(this).val(),event)']); ?></td>
		<td>

		<?php

		$session = Yii::$app->session;
		$session->open();
		$titulares = $session->get($arr, []);
		$session->close();

		if(count($titulares) == 0) $titular = true;

		echo Html::checkbox('inm-ckTitular',$titular, ['id' => 'inm-ckTitular','label' => 'Establecer como titular principal']);
		?>
		</td>
		<td width="130px"></td>
	</tr>
</table>

<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'error', 'enableReplaceState' => false, 'enablePushState' => false]);

			$mensaje = Yii::$app->request->get('mensaje', '');


			if($mensaje != ''){

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
<table style="margin-top:5px;">
	<tr>
		<td>
		<?php

			switch ($consulta)
			{
				case 1:
					echo Html::Button('Grabar',['class' => 'btn btn-success', 'onClick' => 'btAceptarTitular();', 'style'=>'margin-bottom:15px']);
					break;

				case 2:

					echo Html::Button(($estado == 'A' ? 'Grabar' : 'Aceptar'),['class' => 'btn btn-success', 'onClick' => 'btAceptarTitular();', 'style'=>'margin-bottom:15px']);
					break;

				case 3:
					echo Html::Button(($estado == 'A' ? 'Grabar' : 'Aceptar'),['class' => 'btn btn-danger', 'onClick' => 'btAceptarTitular();', 'style'=>'margin-bottom:15px']);
					break;
			}
				?>

		</td>
	</tr>
</table>
</div>
</div>

<div id='BuscaTitular' class="form hidden" style='margin:0px; margin-top:5px; padding:5px;'>

	<div>
	<?php

		Pjax::begin(['id' => 'FormBuscarTitular', 'enableReplaceState' => false, 'enablePushState' => false]);
			echo $this->render('//objeto/objetobuscarav', ['id' => 'tit', 'txCod' => 'tit-cod_contribuyente', 'txNom' => 'tit-nom_contribuyente', 'tobjeto' => 3]);
//			echo $this->render('//objeto/persona/buscarav', [
//															'id' =>'tit',
//															'txCod' => 'tit-cod_contribuyente',
//															'txNom' => 'tit-nom_contribuyente',
//															'cantidad' => 20
//															]);

		Pjax::end();

    ?>
    </div>
</div>

<script>


function btAceptarTitular()
{
	var princ = '';
	if ($("#inm-ckTitular").is(":checked"))
	{
		princ = 'Princ';
	} else {
		princ = '';
	}

	if ($("#tit-nom_contribuyente").val() == '')
	{
		$.pjax.reload({
			container : "#error",
			replace : false,
			push : false,
			data : {
				"mensaje" : "Debe ingresar una persona válida."
				},
			type : "GET"
		});
	} else {
		$.pjax.reload({
			container : "#form-nuevoTitular",
			replace : false,
			push : false,
			data : {
				"action" : <?= $consulta ?>,
				"cod_contribuyente" : $("#tit-cod_contribuyente").val(),
				"nom_contribuyente" : $("#tit-nom_contribuyente").val(),
				"porc" : $("#tit-porc").val(),
				"relacion" : $("#tit-relacion option:selected").text(),
				"tvinc" : $("#tit-relacion").val(),
				"princ" : princ,
				"envio" : 1,
				"tit" : $("#arrayTitulares").val()
			},
			type : "GET"
		});
	}
}

function tipoRelacion(cod)
{
	if (cod != 1 && cod != 6 && cod != 7)
	{
		$("#inm-ckTitular").attr('disabled',true);
		$("#inm-ckTitular").attr('checked',false);

	} else
	{
		$("#inm-ckTitular").removeAttr('disabled');
	}
}

function cambiaCodContribuyente(nuevo){

	$.pjax.reload({
			container : "#actualizaNombre",
			replace:false,
			push:false,
			type : "GET",
			data : {
				cod_contrib : nuevo
			}
	});
}

$("#tit-actualizaGrilla").off("pjax:end");

$("#tit-actualizaGrilla").on("pjax:end", function(){
	$("#modal-titular").modal("hide");
});
</script>

<?php
		if($consulta == 2)
		{
			?>
			<script>
				$("#tit-cod_contribuyente").attr('disabled','disabled');
				$("#tit-nom_contribuyente").attr('disabled','disabled');
				$("#boton-personas").attr('disabled','disabled');
			</script>

			<?php
		}

		//Si la consulta el cuando se dibuja el form en delete
		if ($consulta == 3 || ($consulta == 2 && $estado != 'A'))
    	{
    		?>

    		<script>
			$("#tit-cod_contribuyente").attr('disabled','disabled');
			$("#tit-nom_contribuyente").attr('disabled','disabled');
			$("#boton-personas").attr('disabled','disabled');
			$("#tit-relacion").attr('disabled','disabled');
			$("#tit-porc").attr('disabled','disabled');
			$("#inm-ckTitular").attr('disabled','disabled');
			</script>

    		<?php
    	}

	Pjax::end();
	// FIN Bloque de código que se encarga de crear, actualizar o eliminar los elementos del arreglo de titulares
?>
