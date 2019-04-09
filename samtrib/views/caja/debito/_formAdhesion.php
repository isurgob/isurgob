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
use yii\data\ArrayDataProvider;
use yii\bootstrap\Alert;

ini_set("display_errors", "on");
error_reporting(E_ALL);
/**
 * Forma que se dibuja cuando se llega a Débito Automático
 * Recibo:
 * 			=> $model -> Modelo de Débito, con los atributos para adhesión
 */

/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 *
 * @param $mensj => Mensaje de error que llega desde el modelo al intentar grabar, actualizar ó eliminar.
 */

$title = 'Adhesión';
$this->params['breadcrumbs'][] = ['label' => 'Débitos Automáticos', 'url' => ['caja/debito/view']];
$this->params['breadcrumbs'][] = $title;

?>

<script>
/* Función que se ejecuta cuando se modifica el tipo de caja */
function cambioCaja(caja)
{
	/* Oculto todos los divs */
	$("#debito_bancario").css('display','none');
	$("#tarjeta").css('display','none');
	$("#haberes").css('display','none');

	if (caja == 3)
		$("#debito_bancario").css('display','block');

	if (caja == 4)
		$("#tarjeta").css('display','block');

	if (caja == 5)
		$("#haberes").css('display','block');

}
</script>

<div class="debito_adhesion_info" style="margin-right:140px">
<h1><?= Html::encode($title) ?></h1>

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorDebito_adhesion']);

			$mensaje = '';

			if (isset($mensj) && $mensj != '')
			{
				$mensaje = $mensj;
				$mensj = '';
			}
			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaMensajeDebitoAdhesion',
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaMensajeDebitoAdhesion').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<?php

$form = ActiveForm::begin(['id' => 'frmDebitoAdhesion']);

//Creo un edit oculto que almacenará el valor de consulta
echo Html::input('hidden','txConsulta',$consulta,['id' => 'debito_adhesion_txConsulta']);


//INICIO Bloque actualiza los códigos de objeto
Pjax::begin(['id' => 'ObjNombre']);

	$tobj = Yii::$app->request->post('tobj',0);
	$objeto_id = Yii::$app->request->post('objeto_id',0);

	if (strlen($objeto_id) < 8)
	{
		$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
		echo '<script>$("#debito_adhesion_txObjetoID").val("'.$objeto_id.'")</script>';

	}

	$distrib = utb::getCampo("objeto","obj_id='".$objeto_id."'","distrib");

	if (utb::GetTObj($objeto_id) == $tobj)
	{
		$objeto_nom = utb::getNombObj("'".$objeto_id."'");

		echo '<script>$("#debito_adhesion_txObjetoNom").val("'.$objeto_nom.'")</script>';
		echo '<script>$("#debito_adhesion_dlDistrib").val("'.$distrib.'")</script>';

	} else
	{
		echo '<script>$("#debito_adhesion_txObjetoID").val("")</script>';
		echo '<script>$("#debito_adhesion_txObjetoNom ").val("")</script>';
	}
Pjax::end();
//FIN Bloque actualiza los códigos de objeto
?>

<div class="form-panel" style="padding-right:80px:margin-right:80px">
<table>
	<tr>
		<td width="60px"><label>Nro:</label></td>
		<td width="30px" colspan="3">
			<?= Html::input('text','txNumero',$model->adhesion_id,[
					'id'=>'debito_adhesion_txNumero',
					'class'=>'form-control solo-lectura',
					'style'=>'width:60px;text-align:center',
					'tabindex' => -1,
				]);
			?>
		</td>
		<td align="right">
			<label>Estado:</label>
			<?= Html::input('text','txEstado',$model->adhesion_estNom,[
					'id'=>'debito_adhesion_txEstado',
					'class'=>'form-control solo-lectura',
					'style'=>'width:100px;text-align:left',
					'tabindex' => -1,
				]);
			?>
			<?= Html::input('hidden','txEst',$model->adhesion_est,['id' => 'debito_adhesion_txEst'])?>
		</td>
	</tr>
	<tr>
		<td><label>Tributo:</label></td>
		<td colspan="3">
			<?php

				//INICIO Bloque de código para actualizar el código de Objeto
				Pjax::begin(['id' => 'PjaxTObj']);

//					$tobj = $model->adhesion_tobj;
//
//					if (isset($_GET['debito_tobj']) && $_GET['debito_tobj'] != '') $tobj = $_GET['debito_tobj'];
//
//					if ($tobj == '') $tobj = 0;
//
//					if ($tobj == 1)
//						$cond = "tobj = " . $tobj . " AND est = 'A' AND (tipo = 1 OR trib_id = 3)";
//					else
//						$cond = "tobj = " . $tobj . " AND est = 'A' AND tipo = 1";

					echo Html::dropDownList('dlTrib', $model->adhesion_trib_id, utb::getAux('trib','trib_id','nombre',3, 'tipo = 1'),//$cond),
						[	'style' => 'width:100%',
							'class' => 'form-control',
							'id'=>'debito_adhesion_dlTrib',
							'onchange'=>'cambiaTributo();',
						]);

					//INICIO Bloque de código para activar o desactivar subcta
					Pjax::begin(['id' => 'PjaxTribID']);
						$tipoObjeto= 0;
						if ($model->adhesion_trib_id == '') $model->adhesion_trib_id = 0;

						if (isset($_GET['trib_id']) && $_GET['trib_id'] != ''){
							$model->adhesion_trib_id = intval($_GET['trib_id']);

							if($model->adhesion_trib_id > 0){

								$tipoObjeto= utb::getTObjTrib($model->adhesion_trib_id);
								$nombreTipoObjeto= utb::getCampo('objeto_tipo', "cod = $tipoObjeto", 'nombre');

								?>
								<script type="text/javascript">
								$(document).ready(function(){
									$("#debito_adhesion_TObj").val("<?= $nombreTipoObjeto; ?>");
									$("#debito_adhesion_txObjetoID").prop("disabled", false);
									$("#debito_adhesion_btBuscaObj").prop("disabled", false);
								});
								</script>
								<?php
//								echo '<script type="text/javascript">$("#debito_adhesion_TObj").val("' . $nombreTipoObjeto . '");</script>';
							}
						}

						echo Html::hiddenInput(null, $tipoObjeto, ['id' => 'debito_adhesion_txTObj']);

						//Obtengo el valor de subcta para el trib seleccionado
						$usaSubcta = utb::getCampo('trib','trib_id = ' . $model->adhesion_trib_id,'uso_subcta');

						echo '<script type="text/javascript">$("#debito_adhesion_txCta").prop("readonly", ' . ($usaSubcta == 1 ? 'false' : 'true') . ');</script>';
//						if ($usaSubcta == 1) 	//Habilito el edit para subcuenta
//							echo '<script>$("#debito_adhesion_txCta").attr("readOnly",false);</script>';
//						else 	//Deshabilito el edit para subcuenta
//							echo '<script>$("#debito_adhesion_txCta").attr("readOnly",true);</script>';

						//INICIO Modal Busca Objeto
						Modal::begin([
							'id' => 'BuscaObjAdhesion',
							'size' => 'modal-lg',
							'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
							'closeButton' => [
							  'label' => '<b>X</b>',
							  'class' => 'btn btn-danger btn-sm pull-right',
							],
							 ]);

							echo $this->render('//objeto/objetobuscarav',[
														'id' => 'debito_adhesion_altaBuscar',
														'txCod' => 'debito_adhesion_txObjetoID',
														'txNom' => 'debito_adhesion_txObjetoNom',
														'selectorModal' => '#BuscaObjAdhesion',
														'tobjeto' => (int)$tipoObjeto,
									        		]);

						Modal::end();
						//FIN Modal Busca Objeto

					Pjax::end();
					//FIN Bloque de código para activar o desactivar subcta

				Pjax::end();
				//FIN Bloque de código para actualizar el código de Objeto
			?>
		</td>
		<td align="right">
			<label>Cta</label>
			<?= Html::input('text','txCta',$model->adhesion_subcta,['id'=>'debito_adhesion_txCta','class'=>'form-control','style'=>'width:40px;text-align:center','readOnly'=>true]) ?>
		</td>
	</tr>
	<tr>
		<td><label for="debito_adhesion_dlTObj">Objeto:</label></td>
		<td width="120px">
			<?= Html::textInput('dlTObj', $model->adhesion_tobj, ['id' => 'debito_adhesion_TObj', 'class' => 'form-control solo-lectura', 'tabindex'=>-1,'style' => 'width:97%; text-align:center;']); ?>

		</td>
		<td width="20px">
		<!-- botón de búsqueda modal -->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
					'class' => 'bt-buscar',// . ( $model->adhesion_tobj != '' ? ' disabled' : '' ),
					'id'=>'debito_adhesion_btBuscaObj',
					'onclick' => '$("#BuscaObjAdhesion").modal("show");',
					'disabled' => true
				]);
			?>

			<!-- fin de botón de búsqueda modal -->
		</td>
		<td width="80px">
			<?= Html::input('text','txObjetoID',$model->adhesion_obj_id,[
					'id'=>'debito_adhesion_txObjetoID',
					'class'=>'form-control' . ( $model->adhesion_obj_id != '' ? ' solo-lectura' : '' ),
					'style'=>'width:100%;text-align:center',
					'onchange'=>'cambiaObjeto();',
					'tabindex' => ( $model->adhesion_obj_id != '' ? -1 : 0 )
				]);
			?>
		</td>
		<td width="310px" align="left">
			<?= Html::input('text','txObjetoNom',$model->adhesion_obj_nom,[
					'id'=>'debito_adhesion_txObjetoNom',
					'class'=>'form-control solo-lectura',
					'style'=>'width:100%;text-align:left',
					'tabindex' => -1,
				]);
			?>
		</td>
	</tr>

	<tr>
		<td><label>Texto:</label></td>
		<td colspan="3">
			<?= Html::dropDownList('dlTexto', $model->adhesion_texto, utb::getAux('texto','texto_id','nombre',3,"tuso=16"),
			[	'style' => 'width:100%',
				'class' => 'form-control',
				'id'=>'debito_adhesion_dlTexto',
			]); ?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="110px"><label>Plan/Cuota Desde:</label></td>
		<td width="40px">
			<?= Html::input('text','txPlanAnioDesde',$model->adhesion_plan_anioDesde,[
					'id'=>'debito_adhesion_txPlanAnioDesde',
					'class'=>'form-control',
					'style'=>'width:40px;text-align:center',
					'onkeypress'=>'return justNumbers(event)',
					'maxlength'=>4,
				]);
			?>
		</td>
		<td width="40px"><?= Html::input('text','txPlanCuotaDesde',$model->adhesion_plan_cuotaDesde,['id'=>'debito_adhesion_txPlanCuotaDesde','class'=>'form-control','style'=>'width:35px;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>3]) ?></td>
		<td width="10px"></td>
		<td width="35px"><label>Hasta:</label></td>
		<td width="40px"><?= Html::input('text','txPlanAnioHasta',$model->adhesion_plan_anioHasta,['id'=>'debito_adhesion_txPlanAnioHasta','class'=>'form-control','style'=>'width:40px;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>4]) ?></td>
		<td width="40px"><?= Html::input('text','txPlanCuotaHasta',$model->adhesion_plan_cuotaHasta,['id'=>'debito_adhesion_txPlanCuotaHasta','class'=>'form-control','style'=>'width:35px;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>3]) ?></td>

		<td align="right" width="278px">
			<label>Modificación:</label>
			<?= Html::input('text','txModif',$model->adhesion_usrmod,[
					'id'=>'debito_adhesion_txModif',
					'class'=>'form-control solo-lectura',
					'style'=>'width:160px;text-align:left',
					'tabindex' => -1,
				]);
			?>
		</td>
	</tr>
	<tr>
		<td><label>Tipo Distribución:</label></td>
		<td colspan='6'>
			<?= Html::dropDownList('dlTDistrib', $model->adhesion_tdistrib, utb::getAux('objeto_tdistrib'),
			[	'style' => 'width:100%',
				'class' => 'form-control',
				'id'=>'debito_adhesion_dlTDistrib',
			]); ?>
		</td>
		<td align='right'>
			<label>Distribuidor:</label>
			<?= Html::dropDownList('dlDistrib', $model->adhesion_distrib, utb::getAux('Sam.Sis_Usuario','usr_id','apenom',1,'Distrib=1'),
			[	'style' => 'width:70%',
				'class' => 'form-control',
				'id'=>'debito_adhesion_dlDistrib',
			]); ?>
		</td>
	</tr>
</table>

<table width="100%">
	<tr>
		<td width="60px"><label>Obs:</label></td>
		<td>
			<?= Html::textarea('txObs',null,['id' => 'debito_adhesion_txObs','class'=>'form-control','style' => 'width:540px;max-width:540px;height:60px;max-height:120px']) ?>
		</td>
	</tr>
</table>
</table>
</div>

<div class="form-panel" style="padding-right:8px;padding-bottom:8px">
<h3><strong>Datos del Responsable</strong></h3>
<table>
	<tr>
		<td width="80px"><label>Documento:</label></td>
		<td width="120px">
			<?= Html::dropDownList('dlTDoc', $model->adhesion_responsable_tdoc, utb::getAux('persona_tdoc'),
			[	'style' => 'width:120px',
				'class' => 'form-control',
				'id'=>'debito_adhesion_dlTDoc',
			]); ?>
		</td>
		<td><?= Html::input('text','txNDoc',$model->adhesion_responsable_ndoc,['id'=>'debito_adhesion_txNDoc','class'=>'form-control','style'=>'width:100px;text-align:left','onkeypress'=>'return justNumbers(event)']) ?></td>
		<td width="30px"></td>
		<td><label>Sexo:</label></td>
		<td>
			<?= Html::dropDownList('dlSexo', $model->adhesion_responsable_sexo, utb::getAux('persona_tsexo'),
			[	'style' => 'width:120px',
				'class' => 'form-control',
				'id'=>'debito_adhesion_dlSexo',
			]); ?>
		</td>
	</tr>
	<tr>
		<td width="80px"><label>Nombre:</label></td>
		<td colspan="5"><?= Html::input('text','txNombreResp',$model->adhesion_responsable_nombre,['id'=>'debito_adhesion_txNombreResp','class'=>'form-control','style'=>'width:100%;text-align:left;text-transform:uppercase']) ?></td>
	</tr>
</table>
</div>

<div class="form-panel" style="padding-right:8px;padding-bottom:8px">
<h3><strong>Forma de Pago</strong></h3>
<table>
	<tr>
		<td width="50px"><label>Caja:</label></td>
		<td width="200px" colspan="3">
			<?= Html::dropDownList('dlDebito',$model->adhesion_pago_caja_id,utb::getAux('caja','caja_id','nombre',3,"tipo IN (3,4,5) AND est='A'"),[
					'id'=>'debito_adhesion_dlDebito',
					'onchange' => '$.pjax.reload({container:"#PjaxTipoCaja",method:"GET",push:false,replace:false,data:{caja_id:$(this).val()}})',
					'class'=>'form-control',
					'style'=>'width:100%;text-align:center',
				]);
			?>
		</td>
	</tr>
</table>

<?php

//INICIO Bloque de código para actualizar sucursal
Pjax::begin(['id' => 'PjaxSuc']);

	$sucID = '';
	$sucNom = '';
	$banco = 0;

	if (isset($_GET['bco_ent']) && $_GET['bco_ent'] != '')
		$banco = $_GET['bco_ent'];

	if (isset($_GET['debito_suc']) && $_GET['debito_suc'] != '')
	{
		$sucID = $_GET['debito_suc'];

		//Buscar el nombre para la sucursal correspondiene. Si es vacío, indica que la sucursal no es del banco de la caja
		$sucNom = utb::getCampo('banco','bco_ent = ' . $banco . ' AND bco_suc = ' . $sucID,'nombre');

		if ($sucNom == '')
			$sucID = '';

		//Actualizo los edits
		echo '<script>$("#debito_adhesion_txSucID").val("' . $sucID . '");</script>';
		echo '<script>$("#debito_adhesion_txSucNom").val("' . $sucNom . '");</script>';
	}



Pjax::end();
//FIN Bloque de código para actualizar sucursal

//INICIO Bloque de código para obtener tipo,ext_bco_ent de la tabla caja
Pjax::begin((['id' => 'PjaxTipoCaja']));

	$caja = 0;
	$tcaja = $model->adhesion_pago_caja_tipo;
	$bco_ent = 0;
	$caja = intVal($model->adhesion_pago_caja_id);

	if (isset($_GET['caja_id']) && $_GET['caja_id'] != '')
		$caja = $_GET['caja_id'];

	//Obtengo el tipo de caja para habilitar el bloque div correspondiente
	$tcaja = utb::getCampo('caja','caja_id = ' . $caja,'tipo');
	if ($tcaja == '') $tcaja = 0;

	//Obtengo el
	$bco_ent = utb::getCampo('caja','caja_id = ' . $caja,'ext_bco_ent');
	if ($bco_ent == '') $bco_ent = 0;

	//Creo un edit oculto que almacenará el tipo de caja
	echo Html::input('hidden','txTCaja',$tcaja,['id' => 'debito_adhesion_txTCaja']);

?>
<!-- INICIO Bloque HTML en caso de que el tipo de caja sea "DEBITOS" -->
<div id="debito_bancario" style="display:none">
<table>
	<tr>
		<td width="50px"><label>Suc:</label></td>
		<td width="50px"><?= Html::input('text','txSucID',$model->adhesion_pago_sucID,['id'=>'debito_adhesion_txSucID',
																						'class'=>'form-control',
																						'style'=>'width:50px;text-align:center',
																						'onkeypress'=>'return justNumbers(event)',
																						'onchange' => '$.pjax.reload({container:"#PjaxSuc",method:"GET",replace:false,push:false,data:{debito_suc:$(this).val(),bco_ent:' . $bco_ent . '}})',]) ?></td>
		<td width="20px">
		<!-- botón de búsqueda modal -->
			<?php
			//INICIO Modal Busca Objeto
			Modal::begin([
			'id' => 'BuscaSuc',
			'size' => 'modal-normal',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Sucursal</h2>',
			'toggleButton' => [
				'label' => '<i class="glyphicon glyphicon-search"></i>',
				'class' => 'bt-buscar',
				'id'=>'debito_adhesion_btBuscaSuc',
			],
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			],
			 ]);

			echo $this->render('//taux/auxbusca', [	'boton_id' => 'debito_adhesion_btBuscaSuc',
													'tabla' => 'banco',
            										'campocod'=>'bco_suc',
            										'idcampocod'=>'debito_adhesion_txSucID',
            										'idcamponombre'=>'debito_adhesion_txSucNom',
            										'criterio' => 'bco_ent = ' . $bco_ent,
            									]);

			Modal::end();
			//FIN Modal Busca Objeto
			?>
			<!-- fin de botón de búsqueda modal -->
		</td>
		<td width="150px"><?= Html::input('text','txSucNom',$model->adhesion_pago_sucNom,['id'=>'debito_adhesion_txSucNom','class'=>'form-control solo-lectura','style'=>'width:150px;text-align:left;text-transform:uppercase']) ?></td>
		<td width="20px"></td>
		<td width="40px"><label>Tipo</label></td>
		<td><?= Html::dropDownList('dlTipoSuc',$model->adhesion_pago_tipo,utb::getAux('banco_tcuenta'),['id'=>'debito_adhesion_dlTipoSuc','class'=>'form-control','style'=>'width:100%;text-align:center']) ?></td>
		<td width="20px"></td>
		<td width="40px"><label>Nº Cta</label></td>
		<td><?= Html::input('text','txNumCta',$model->adhesion_pago_numCta,['id'=>'debito_adhesion_txNumCta','class'=>'form-control','style'=>'width:100px;text-align:left','onkeypress'=>'return justNumbers(event)','maxlength' => '20']) ?></td>
	</tr>
	<tr>
		<td width="50px"><label>CBU:</label></td>
		<td colspan='9'>
			<?= Html::input('text','txCbu',$model->adhesion_pago_cbu,['id'=>'debito_adhesion_txCbu','class'=>'form-control','style'=>'width:228px;text-align:left','onkeypress'=>'return justNumbers(event)','maxlength' => '22']) ?>
		</td>
	</tr>
</table>
</div>
<!-- FIN Bloque HTML en caso de que el tipo de caja sea "DEBITOS" -->

<!-- INICIO Bloque HTML en caso de que el tipo de caja sea "TARJETA DE CRÉDITO" -->
<div id="tarjeta" style="display:none">
<table>
	<tr>
		<td width="80px"><label>Nº Tarjeta:</label></td>
		<td width="150px"><?= Html::input('text','txNTarjeta',$model->adhesion_pago_numTarjeta,['id'=>'debito_adhesion_txNTarjeta','class'=>'form-control','style'=>'width:150px;text-align:center','onkeypress'=>'return justNumbers(event)']) ?></td>
	</tr>
</table>
</div>
<!-- FIN Bloque HTML en caso de que el tipo de caja sea "TARJETA DE CRÉDITO" -->

<!-- INICIO Bloque HTML en caso de que el tipo de caja sea "HABERES" -->
<div id="haberes" style="display:none">
<table>
	<tr>
		<td width="100px"><label>Tipo Empleado:</label></td>
		<td width="110px">
			<?= Html::dropDownList('dlTEmpleado',$model->adhesion_pago_templeado,utb::getAux('plan_temple','cod','nombre',3,'caja_id = ' . $caja),[
					'id'=>'debito_adhesion_dlTEmpleado',
					'class'=>'form-control',
					'style'=>'width:100%;text-align:center',
				]);
			?>
		</td>
		<td width="20px">
		<td width="40px"><label>Área</label></td>
		<td width="120px"><?= Html::input('text','txArea',$model->adhesion_pago_area,['id'=>'debito_adhesion_txArea','class'=>'form-control','style'=>'width:100%;text-align:center','onkeypress'=>'return justNumbers(event)']) ?></td>
		<td width="20px"></td>
		<td width="50px"><label>Legajo</label></td>
		<td width="120px"><?= Html::input('text','txLegajo',$model->adhesion_pago_legajo,['id'=>'debito_adhesion_txLegajo','class'=>'form-control','style'=>'width:100%;text-align:left','onkeypress'=>'return justNumbers(event)']) ?></td>
	</tr>
</table>
</div>
<!-- FIN Bloque HTML en caso de que el tipo de caja sea "HABERES" -->
<?php

	echo '<script>cambioCaja('.$tcaja.');</script>';

Pjax::end();

ActiveForm::end();
if ($consulta == 1 || $consulta == 2) //En modo consulta
{

	echo "<script>";
	echo "DesactivarForm('frmDebitoAdhesion');";
	echo "</script>";

}

?>

</div>
<?php
	if ($consulta == 1)
	{
		echo Html::a('Volver',['view','consulta' => 1],['class'=>'btn btn-primary']);
		echo '&nbsp;';
		echo Html::button('Certificado', ['class'=>'btn btn-success', 'onclick' => '$("#ModalTexto").modal("show")']);

	} else
	{
		if ($consulta == 2)
			echo Html::a('Eliminar',['adhesion','id' => $model->adhesion_id, 'action' => 2],['class' => 'btn btn-danger']);
		else
			echo Html::button(($consulta == 3 ? 'Modificar' : 'Aceptar'), ['class'=> 'btn btn-success','onclick' => 'validarDatos()']);
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		echo Html::a('Cancelar',['view'],['class'=>'btn btn-primary']);
	}
 ?>

<div id="debito_adhesion_errorSummary" class="error-summary" style="display:none;margin-top:10px">

<ul>
</ul>

</div>

</div>

<?php

Modal::begin([
	'id' => 'ModalTexto',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Certificados</h2>',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
]);

		$form = ActiveForm::begin(['id' => 'formCertificado', 'action' => ['certificado'], 'method' => 'get', 'options' => ['target' => '_black'] ] );
			echo "<center style=font-family:Helvetica Neue, Helvetica, Arial, sans-serif;'>";
			echo Html::input('hidden', 'adhe_id', $model->adhesion_id);
			echo "<label style='color:#333'> Seleccione Certificado: &nbsp;</label>" ;
			echo Html::dropDownList('texto', null, utb::getAux('texto','texto_id','nombre',0,'tuso=16'), ['class' => 'form-control']);
			echo "<br><br>";
            if ($model->verifTextoCertificado(16) > 0)
			   echo Html::submitButton('Imprimir', ['class' => 'btn btn-success']);
            else
               echo "<p style='color:#258'>Debe definir un texto para tipo de uso: <br>Adhesión Débito</p>" ;
			echo "</center>";
		ActiveForm::end();

Modal::end();

?>

<script>
function f_mostrarError( error )
{
	var $contenedor= $("#debito_adhesion_errorSummary");
	var $lista= $("#debito_adhesion_errorSummary ul");

	$lista.empty();

	$contenedor.css("display", "block");

	for (e in error)
	{
		$el= $("<li />");
		$el.text(error[e]);
		$el.appendTo($lista);
	}
}

function habilitaObjeto()
{
	var tobj = $("#debito_adhesion_dlTObj").val();

	$("#debito_adhesion_txObjetoID").val("");
	$("#debito_adhesion_txObjetoNom").val("");

	if ( tobj == 0 )
	{
		$("#debito_adhesion_txObjetoID").addClass('solo-lectura');
		$("#debito_adhesion_btBuscaObj").attr('disabled',true);
		$("#debito_adhesion_txObjetoID").attr('readOnly',true);

	} else
	{
		$("#debito_adhesion_txObjetoID").removeClass('solo-lectura');
		$("#debito_adhesion_btBuscaObj").removeAttr('disabled');
		$("#debito_adhesion_txObjetoID").removeAttr('readOnly');
	}

	$.pjax.reload({
		container:"#ObjNombre",
		method:"POST",
		replace:false,
		push:false,
		data:{
			tobj:tobj,
		}
	});

	$("#ObjNombre").on("pjax:end",function() {

		$.pjax.reload({
			container: "#PjaxTObj",
			type: "GET",
			replace: false,
			push: false,
			data: {
				debito_tobj: tobj,
			}
		});

		$("#ObjNombre").off("pjax:end");

	});

}

function validarDatos()
{
	var objID = $("#debito_adhesion_txObjetoID").val(),
		objNom = $("#debito_adhesion_txObjetoNom").val(),
		trib_id = $("#debito_adhesion_dlTrib").val(),
		perdesde_anio = $("#debito_adhesion_txPlanAnioDesde").val(),
		perdesde_cuota = $("#debito_adhesion_txPlanCuotaDesde").val(),
		perhasta_anio = $("#debito_adhesion_txPlanAnioHasta").val(),
		perhasta_cuota = $("#debito_adhesion_txPlanCuotaHasta").val(),
		resp = $("#debito_adhesion_txNombreResp").val(),
		resp_sex = $("#debito_adhesion_dlSexo").val(),
		doc = $("#debito_adhesion_txNDoc").val(),
		tdoc = $("#debito_adhesion_dlTDoc").val(),
		caja = $("#debito_adhesion_dlDebito").val(),
		error = new Array();

	/* Validar que se ingrese un objeto válido */
	if (objID == '')
		error.push('Ingrese un Objeto');
	else if (objNom == '')
		error.push('Ingrese un Objeto válido.');

	/* Validar que se ingrese un tributo válido */
	if (trib_id == '' || trib_id == 0)
		error.push('Elija un tributo.');

	/* Validar perdesde y perhasta */
	if ( perdesde_anio == "" || perdesde_cuota == "" )
		error.push('Ingrese un período desde.');

	if ( perhasta_anio == "" || perhasta_cuota == "" )
		error.push('Ingrese un período hasta.');

	if ( perdesde_anio.length < 4 )
		error.push('Ingrese un período desde correcto.');

	if ( perhasta_anio.length < 4 )
		error.push('Ingrese un período hasta correcto.');

	/* Validar que se ingrese tipo y número de documento */
	/* Validar que se ingrese un nombre de responsable y sexo */
	if (tdoc == 0 || tdoc == '')
		error.push('Ingrese tipo de documento del responsable.');
	else if (doc == 0 || doc == '')
		error.push('Ingrese el número de documento del responsable.');

	if (resp_sex == '' || resp_sex == 0)
		error.push('Ingrese sexo del responsable.');

	if (resp == '')
		error.push('Ingrese un nombre de responsable.');

	/* Validar que se ingrese una caja */
	if (caja == 0 || caja == '')
		error.push('Elija una caja.');
	else
	{
		/* Obtengo el tipo de caja del edit oculto */
		var tCaja = parseInt($("#debito_adhesion_txTCaja").val());

		/* Validar según el tipo de caja */
		switch (tCaja)
		{
			case 3:

				var sucID = $("#debito_adhesion_txSucID").val();
				var sucNom = $("#debito_adhesion_txSucNom").val();
				var tipoCuenta = $("#debito_adhesion_dlTipoSuc").val();
				var numCuenta = $("#debito_adhesion_txNumCta").val();

				/* Validar que se ingrese una sucursal */
				/*if (sucID == '')
					error.push('Ingrese una sucursal.');
				else if (sucNom == '')	// Validar que se ingrese una sucursal válida
					error.push('Ingrese una sucursal válida.');*/

				/* Validar que se seleccione un tipo de cuenta */
				if (tipoCuenta == 0 || tipoCuenta == '')
					error.push('Ingrese un tipo de cuenta.');

				/* Validar que se ingrese una cuenta y que se encuentre entre 4 y 11 caracteres */
				if (numCuenta == '')
					error.push('Ingrese una cuenta.');
				else if (numCuenta.length < 4 || numCuenta.length > 16)
					error.push('Ingrese una cuenta válida.');

				break;

			case 4:

				var numTarjeta = $("#debito_adhesion_txNTarjeta").val();

				/* Validar que se ingrese el número de tarjeta */
				if (numTarjeta == '')
					error.push('Ingrese una tarjeta.');

				break;

			case 5:

				var area = $("#debito_adhesion_txArea").val();

				/* Validar que se ingrese el área del empleado */
				if (area == '')
					error.push('Ingrese un área del empleado.');

				break;
		}

	}

	if ( error.length == 0 )
		$("#frmDebitoAdhesion").submit();
	else
		mostrarErrores(error, $("#debito_adhesion_errorSummary"));
}

//function buscar()
//{
//	var inclBaja = 0;
//	var error= [];
//
//	var caja = $("#debito_adhesion_dlDebito").val();
//	var trib = $("#debito_adhesion_dlTrib").val();
//	var tobj = $("#debito_adhesion_dlTObj").val();
//	var objID = $("#debito_adhesion_txObjetoID").val();
//	var objNom = $("#debito_adhesion_txObjetoNom").val();
//	if ($("#debito_adhesion_ckIncluirBaja").is(":checked"))
//		inclBaja = 1;
//
//	var anio = $("#debito_adhesion_txAnio").val();
//	var mes = $("#debito_adhesion_txMes").val();
//
//	/* Caja no puede ser vacío */
//	if (caja == '' || caja == 0)
//		error.push("Ingrese una caja.");
//
//	/* Cuando se filtra por año y por mes, se debe ingresar un tributo */
//	if ((anio != '' || mes != '') && (trib == '' || trib == 0))
//		error.push("Para filtrar por año y por mes, es necesario que se indique un tributo.");
//
//	/* Cuando se filtra por objeto */
//	if (objID != '' && objNom == '')
//		error.push("Ingrese un objeto válido.");
//
//
//
//	if (error == "")
//	{
//		$.pjax.reload({container:"#pjaxDebito_Tab",method:"POST",data:{	caja:caja,
//																		trib:trib,
//																		obj_id:objID,
//																		baja:inclBaja,
//																		anio:anio,
//																		mes:mes
//																				}});
//	} else
//	{
//		mostrarErrores(error, $("#debito_adhesion_errorSummary"));
//		//$.pjax.reload({container:"#errorDebito_adhesion",method:"POST",data:{mensaje:error}});
//	}
//}

function cambiaTributo(){

	$("#debito_adhesion_TObj").val("");
	$("#debito_adhesion_txObjetoID").prop("disabled", true);
	$("#debito_adhesion_txObjetoID").val("");
	$("#debito_adhesion_txObjetoNom").val("");
	$("#debito_adhesion_btBuscaObj").prop("disabled", true);

	$.pjax.reload({
		container:"#PjaxTribID",
		method:"GET",
		replace:false,
		push:false,
		data:{
			trib_id:$("#debito_adhesion_dlTrib").val()
		}
	});
}

function cambiaObjeto(){

	$("#debito_adhesion_txObjetoNom").val("");

	$.pjax.reload({
		container:"#ObjNombre",
		data:{
			objeto_id:$("#debito_adhesion_txObjetoID").val(),
			tobj:$("#debito_adhesion_txTObj").val()
		},
		type:"POST"
	});
}
</script>
