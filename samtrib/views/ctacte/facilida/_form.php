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

$form = ActiveForm::begin(['id' => 'formFacilida',
							'action' => ['view']]);

?>
<style>

#ModalBuscaObjFacilida .modal-content
{
	width: 90% !important;
	margin-left: 5%;
}

.div_grilla
{
	margin-bottom: 8px;
	margin-top: 8px;
}

</style>

<?php
$session = new Session;

$session->open();

Pjax::begin(['id'=>'grabarDatos']);

	if(isset($_POST['trib_id']) && isset($_POST['obj_id']) && isset($_POST['fchvenc']) && isset($_POST['fchconsolida']) && isset($_POST['obs']) && isset($_POST['bajaauto']))
	{

		$ttrib_id = $_POST['trib_id'];
		$tobj_id = $_POST['obj_id'];
		$tfchvenc = $_POST['fchvenc'];
		$tfchconsolida = $_POST['fchconsolida'];
		$tobs = $_POST['obs'];
		$tbajaauto = $_POST['bajaauto'];

		//Grabo la facilidad
		$grabar = $model->grabarFacilida($ttrib_id,$tobj_id,$tfchvenc,$tfchconsolida,$tobs,$tbajaauto);

		if ($grabar == '')
		{
			$grabar == 'Hubo un error al grabar los datos.';
			echo '<script>$.pjax.reload({container:"#errorRegPagoAnt",method:"POST",data:{mensaje:"'.$grabar.'."}});</script>';

		} else {

			//INICIO Modal Imprimir Comprobante
	  		Modal::begin([
				'id' => 'ModalImprmir',
				'size' => 'modal-sm',
				'header' => '<h4><b>Imprimir Comprobante</b></h4>',
				'closeButton' => [
    				'label' => '<b>X</b>',
        			'class' => 'btn btn-danger btn-sm pull-right',
        			'id' => 'btCancelarModalElim'
    				],
			]);

			echo "<center>";
			echo "<p><label>¿Desea imprimir un comprobante?</label></p><br>";

			echo Html::a('Aceptar', ['imprimir','id'=>$grabar],['id' => 'btImpComp','class' => 'btn btn-success','data-pjax'=>0, 'target'=>'_black', 'onclick' => 'cerrarModal()']);

			echo "&nbsp;&nbsp;";
	 		echo Html::a('Cancelar',['view','id'=>$grabar],['id' => 'btCancelarImpComp', 'class' => 'btn btn-primary']);
	 		echo "</center>";

			echo "<script>$('#ModalImprmir').on('hide.bs.modal', function () {location.href = $('#btCancelarImpComp').attr('href');})</script>";

	 		Modal::end();
			//FIN Modal Imprimir Comprobante

			echo '<script>$("#ModalImprmir, .window").modal("show");</script>';

		}
	}

Pjax::end();

$objeto = '';

//INICIO Bloque actualiza objeto y actualiza los valores en la vista
Pjax::begin(['id'=>'actualiza_tobj']);

	if ( $model->nuevo_obj_tipo == '' )
		$model->nuevo_obj_tipo = 0;

	if ( isset( $_POST['trib_id'] ) )
	{
		//Si se envía el tipo de tributo mediante POST
		$model->nuevo_trib_id = Yii::$app->request->post( 'trib_id', 0 );

		//Si se envía el tipo de objeto mediante POST
		$model->nuevo_obj_tipo = utb::getTObjTrib( $model->nuevo_trib_id );

		//Para actualizar el código de objeto
		$model->nuevo_obj_id = Yii::$app->request->post( 'objetoID', '' );

		if ( $model->nuevo_obj_id != '' )
		{
			//Si objeto tiene menos de 8 caracteres
			if (strlen($model->nuevo_obj_id) < 8)
			{
				$model->nuevo_obj_id = utb::GetObjeto((int)$model->nuevo_obj_tipo,(int)$model->nuevo_obj_id);

			} else //Validar que el id de objeto se corresponda con el tipo de Objeto
			{
				if ($model->nuevo_obj_tipo != utb::getTObj($model->nuevo_obj_id))
				{
					$model->nuevo_obj_id = '';
				}
			}

			//Verificar que exista el objeto obtenido
			if (utb::verificarExistenciaObjeto($model->nuevo_obj_tipo,"'".$model->nuevo_obj_id."'") == 0)
				$model->nuevo_obj_id = '';

			$model->nuevo_obj_nom = utb::getNombObj("'".$model->nuevo_obj_id."'");

			?>
			<script>
			$("#actualiza_tobj").on("pjax:end", function(){

				habilitarprocesar();

				$("#actualiza_tobj").off("pjax:end");
			});
			</script>
			<?php
		} else
		{
				?>
				<script>
				$("#actualiza_tobj").on("pjax:end", function(){

						habilitarprocesar();

					$("#actualiza_tobj").off("pjax:end");
				});
				</script>
				<?php
		}
	}

    //INICIO Modal Busca Objeto Origen
	Modal::begin([
		'id' => 'ModalBuscaObjFacilida',
		'size' => 'modal-lg',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		],
	]);

		echo $this->render('//objeto/objetobuscarav', [
			'id' => 'ObjFacilida',
			'txCod' => 'formFacilida_txObjetoID',
			'txNom' => 'formFacilida_txObjetoNom',
			'selectorModal' => '#ModalBuscaObjFacilida',
			'tobjeto' => $model->nuevo_obj_tipo,
		]);

	Modal::end();
	//FIN Modal Busca Objeto Origen

?>

<div class="form-panel" style="padding-bottom: 5px">

<table>
	<tr>
		<td width="70px"><label>Tributo</label></td>
		<td>
			<?= Html::dropDownList('formFacilida_tributo', $model->nuevo_trib_id, utb::getAux('trib','trib_id','nombre',3,"tobj > 0 And trib_id not in ".utb::getTribEsp()), [
					'id'=>'formFacilida_tributo',
					'style'=>'width:400px',
					'class' => 'form-control',
					'onchange'=>'$.pjax.reload({container:"#actualiza_tobj",method:"POST",data:{trib_id:$(this).val()}})',
				]);
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="70px"><label>T. Objeto</label></td>
		<td><?= Html::dropDownList('formFacilida_objeto', $model->nuevo_obj_tipo, utb::getAux('objeto_tipo','cod','nombre',1,"est='A'"), [
					'id'=>'formFacilida_objeto',
					'style'=>'width:120px',
					'class' => 'form-control read-only',
					'tabindex' => -1,
				]);
			?>
		</td>
		<td width="30px"></td>
				<td><label>Objeto</label></td>
		<td width="60px"><?= Html::input('text','formFacilida_txObjetoID',$model->nuevo_obj_id,[
									'id'=>'formFacilida_txObjetoID',
									'class'=>'form-control',
									'style'=>'width:75px;text-align:center',
									'onchange'=>'$.pjax.reload({' .
																'container:"#actualiza_tobj",' .
																'method:"POST",' .
																'data:{' .
																	'objetoID:$(this).val(),' .
																	'objeto:$("#formFacilida_objeto").val(),' .
																	'trib_id:$("#formFacilida_tributo").val()}})']); ?>
		<td>
			<!-- botón de búsqueda modal -->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',['class' => 'bt-buscar','id'=>'formFacilida_btBuscaObj','onclick'=>'$("#ModalBuscaObjFacilida, .window").modal("show")']); ?>
			<!-- fin de botón de búsqueda modal -->
		</td>
		<td width="60px">
			<?= Html::input('text','formFacilida_txObjetoNom',$model->nuevo_obj_nom,[
					'id'=>'formFacilida_txObjetoNom',
					'class'=>'form-control',
					'style'=>'width:250px;',
					'readOnly'=>true,
					'tabindex' => -1,
				]);
			?>
		</td>

	</tr>
</table>

<table>
	<tr>
		<td width="70px"><label>Fechas:</label></td>
		<td width="55px"><label>Alta:</label></td>
		<td width="80px"><?=  Html::input('text','formFacilida_fchalta',$dia,['id' => 'formFacilida_fchalta','class' => 'form-control', 'style' => 'width:80px;text-align:center', 'readonly'=>true,]);?>
		</td>
		<td width="25px"></td>
		<td width="55px"><label>Vencimiento:</label></td>
		<td width="80px"><?=  DatePicker::widget([	'id' => 'formFacilida_fchvenc',
													'name' => 'formFacilida_fchvenc',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => ['class' => 'form-control', 'style' => 'width:80px;text-align:center','onchange'=>'habilitarprocesar();',],
													'value' => Fecha::usuarioToDatePicker(($model->nuevo_fchvenc == '' ? $dia : $model->nuevo_fchvenc)),

												]);	?>
		<td width="24px"></td>
		<td width="55px"><label>Consolidación:</label></td>
		<td width="80px"><?=  DatePicker::widget([	'id' => 'formFacilida_fchconsolidacion',
													'name' => 'formFacilida_fchconsolidacion',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => ['class' => 'form-control', 'style' => 'width:80px;text-align:center',
													'onchange'=>'cambiaConsolidacion();habilitarprocesar();',],
													'value' => Fecha::usuarioToDatePicker(($model->nuevo_fchconsolida == '' ? $dia : $model->nuevo_fchconsolida)),

												]);	?>
		</td>

		</td>
	</tr>
</table>

<table>
	<tr>
		<td><?= Html::checkbox('formFacilida_ckBajaAutom',$model->nuevo_baja_automatica, ['id' => 'formFacilida_ckBajaAutom','label' => 'Baja Automática si está vencida']); ?></td>
	</tr>
</table>
</div>

<?php

	$obj_tipo = $model->nuevo_obj_tipo == '' ? 0 : $model->nuevo_obj_tipo;

	//Habilitar o deshabilitar la búsqueda de objeto según si se seleccionó un tributo
	echo '<script>$("#formFacilida_txObjetoID").toggleClass("read-only",'.$obj_tipo.' == 0 )</script>';
	echo '<script>$("#formFacilida_btBuscaObj").toggleClass("read-only",'.$obj_tipo.' == 0 )</script>';

	//Establecer el foco en objeto si el tributo != 0
	if ( $model->nuevo_trib_id != 0 )
	{
		if ( $model->nuevo_obj_id == '' )
		{
			?>
				<script>

					$( "#GrillaFormFacilida" ).on( "pjax:end", function() {

						$("#formFacilida_txObjetoID").focus();
						$( "#GrillaFormFacilida" ).off( "pjax:end" );
					});

				</script>
			<?php
		} else
		{
			?>
				<script>

					$( "#GrillaFormFacilida" ).on( "pjax:end", function() {

						$("#formFacilida_fchvenc").focus();
						$( "#GrillaFormFacilida" ).off( "pjax:end" );
					});

				</script>
			<?php
		}

	}


	Pjax::end();
?>

<div class="form-panel" style="padding-right:8px">
<h3><strong>Períodos</strong></h3>

<table>
	<tr>
		<td><?= Html::checkbox('formFacilida_ckPerVenc',$model->nuevo_periodo_vencido, ['id' => 'formFacilida_ckPerVenc','label' => 'Incluir Períodos no vencidos']); ?></td>
		<td width="25px"></td>
	</tr>
</table>

<table>
	<tr>
		<td><?= Html::radio('formFacilida_rdPeriodo',$model->nuevo_todos_periodos, ['id' => 'formFacilida_ckTodosPeriodos','label' => 'Todos los Períodos', 'onchange'=>'rbPeriodo()']); ?></td>
		<td width="25px"></td>
		<td><?= Html::radio('formFacilida_rdPeriodo',$model->nuevo_rango, ['id' => 'formFacilida_ckRangoPeriodos','label' => 'Según Rango', 'onchange'=>'rbPeriodo()']); ?></td>
		<td width="25px"></td>
		<td><label>Desde</label></td>
		<td>
			<?= Html::input('text','formFacilida_txPeriodoDesdeAnio',$model->nuevo_desdeanio,['id'=>'formFacilida_txPeriodoDesdeAnio','class'=>'form-control','style'=>'width:40px;','onkeypress'=>'return justNumbers(event)','maxlength'=>4,'readOnly'=>true]) ?>
			<?= Html::input('text','formFacilida_txPeriodoDesdeCuota',$model->nuevo_desdecuota,['id'=>'formFacilida_txPeriodoDesdeCuota','class'=>'form-control','style'=>'width:40px;','onkeypress'=>'return justNumbers(event)','maxlength'=>3,'readOnly'=>true]) ?>
		</td>
		<td width="25px"></td>
		<td><label>Hasta</label></td>
		<td>
			<?= Html::input('text','formFacilida_txPeriodoHastaAnio',$model->nuevo_hastaanio,['id'=>'formFacilida_txPeriodoHastaAnio','class'=>'form-control','style'=>'width:40px;','onkeypress'=>'return justNumbers(event)','maxlength'=>4,'readOnly'=>true]) ?>
			<?= Html::input('text','formFacilida_txPeriodoHastaCuota',$model->nuevo_hastacuota,['id'=>'formFacilida_txPeriodoHastaCuota','class'=>'form-control','style'=>'width:40px;','onkeypress'=>'return justNumbers(event)','maxlength'=>3,'readOnly'=>true]) ?>
		</td>
		<td width="20px"></td>
		<!-- BOTÓN Procesar -->
		<td><?= Html::button('Procesar',['id'=>'btProcesa','class' => 'btn btn-success','onclick'=>'btProcesar()']); ?></td>
	</tr>
</table>

<!-- INICIO GRILLA -->
<div class="div_grilla">
<?php

	//INICIO Bloque que se ejecuta al presionar el botón Procesar
	Pjax::begin(['id'=>'GrillaFormFacilida']);

		$session->open();

		if ($session['facilida-banderaDatos'] == 1)
			$session['arregloPeriodos'] = null;

		if (isset($_POST['limpiaSession']) && $_POST['limpiaSession'] == 1)
		{
			$session['arregloPeriodos'] = [];
		}

		if(isset($_POST['objetoID']))
		{
			 $objetoID = $_POST['objetoID'];
			 $tributo = $_POST['tributo'];
			 $perDesde = $_POST['perDesde'];
			 $perHasta = $_POST['perHasta'];
			 $noVencido = $_POST['noVencido'];
			 $fchvenc = $_POST['fchvenc'];
			 $fchconsolidacion = $_POST['fchconsolidacion'];


			 echo $model->cargarDeudaDetalle($objetoID,$tributo,$perDesde,$perHasta,$noVencido,$fchvenc,$fchconsolidacion);


			 $session['arregloPeriodos'] = $model->nuevo_periodos;
			 $session['facilida-banderaDatos'] = 0;

			 echo '<script>deshabilitarGrabar()</script>';	//Deshabilito el botón grabar en caso de que se encuentre activo
		}

		//INICIO Bloque calcular
		Pjax::begin(['id'=>'calcular']);

		//Si se recibe un arreglo con períodos checkeados
		if (isset($_POST['arregloCheck']))
		{
			$obj_id = $_POST['obj_id'];
			$trib_id = $_POST['trib_id'];

			$perFiltrados = [];

			$arrayCheck = $_POST['arregloCheck'];

			$calculo = $model->calcularFacilida($arrayCheck,$trib_id,$obj_id,$masiva = false);

			if ($calculo == '')
			{
				$model->nuevo_periodos = $session['arregloPeriodos'];

				$capital = floatval(Yii::$app->session->get('facilida-nuevo_capital',0));
				$nominal = floatval(Yii::$app->session->get('facilida-nuevo_nominal',0));
				$accesor = floatval(Yii::$app->session->get('facilida-nuevo_accesor',0));
				$multa = floatval(Yii::$app->session->get('facilida-nuevo_multa',0));
				$quita = floatval(Yii::$app->session->get('facilida-nuevo_quita',0));

				echo '<script>$("#formFacilida_txTotal").val("'.number_format($capital,2,'.','').'");</script>';
				echo '<script>$("#formFacilida_txNominal").val("'.number_format($nominal,2,'.','').'");</script>';
				echo '<script>$("#formFacilida_txAccesorios").val("'.number_format($accesor,2,'.','').'");</script>';
				echo '<script>$("#formFacilida_txMulta").val("'.number_format($multa,2,'.','').'");</script>';
				echo '<script>$("#formFacilida_txQuita").val("'.number_format($quita,2,'.','').'");</script>';
				echo '<script>$("#btGrabar").removeAttr("disabled")</script>';

			} else
			{
				echo '<script>$.pjax.reload({container:"#errorRegPagoAnt",method:"POST",data:{mensaje:"'.$calculo.'."}});</script>';
			}

		}

	     echo GridView::widget([
			'id' => 'GrillaPeriodoFacilida',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $model->obtenerDeudaDetalle(),
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [


					['content'=> function($model, $key, $index, $column) {return Html::checkbox('formFacilida_ckPeriodo[]',$model['activo'],[
																			'id' => 'formFacilida_ckPeriodo'.$model['ctacte_id'],
																			'value'=>$model['ctacte_id'],
																			 'onchange'=>'deshabilitarGrabar()',
																			 'style' => 'height:12px;width:12px;margin:0px']);},
																			'contentOptions'=>['style'=>'width:2px','class'=>'simple'],
					'header' => Html::checkBox('selection_all', false, [
							'id' => 'formFacilida_ckPeriodoHeader',
					        'onchange'=>'cambiarChecks()',
					    ]),
					],
					['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'subcta','header' => 'Subcta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'nominal','header' => 'Nominal', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'accesor','header' => 'Accesor', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'quita','header' => 'Quita', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'multa','header' => 'Multa', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'total','header' => 'Total', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
	        	],
		]);

	//FIN Bloque que se ejecuta al presionar el botón Procesar

	Pjax::end();

	$session->close();

Pjax::end();

?>
<!-- FIN GRILLA -->
</div>

</div>

<?php
Pjax::begin(['id'=>'datos']);

/**
 		$session->open();

     	$session['facilida-nuevo_capital'] = $capital;
    	$session['facilida-nuevo_nominal'] = $nominal;
    	$session['facilida-nuevo_accesor'] = $accesor;
    	$session['facilida-nuevo_multa'] = $multa;
    	$session['facilida-nuevo_quita']
 */
?>
<div class="form-panel" style="padding-right:8px; padding-bottom: 5px">
<h3><strong>Detalle de Deuda</strong></h3>
<table width="100%">
	<tr>
		<td width="45px"><label>Nominal:</label></td>
		<td><?= Html::input('text','formFacilida_txNominal',null,['id'=>'formFacilida_txNominal','class'=>'form-control','style'=>'width:80px;text-align:right','readOnly'=>true]) ?></td>
		<td width="5px"></td>
		<td width="45px"><label>Accesorios:</label></td>
		<td><?= Html::input('text','formFacilida_txAccesorios',null,['id'=>'formFacilida_txAccesorios','class'=>'form-control','style'=>'width:80px;text-align:right','readOnly'=>true]) ?></td>
		<td width="5px"></td>
		<td><label>Quita:</label><?= Html::input('text','formFacilida_txQuita',null,['id'=>'formFacilida_txQuita','class'=>'form-control','style'=>'width:60px;text-align:right','readOnly'=>true]) ?></td>
		<td width="5px"></td>
		<td><label>Multa:</label><?= Html::input('text','formFacilida_txMulta',null,['id'=>'formFacilida_txMulta','class'=>'form-control','style'=>'width:60px;text-align:right','readOnly'=>true]) ?></td>
		<td width="5px"></td>
		<td align="right"><label>Total:</label><?= Html::input('text','formFacilida_txTotal',null,['id'=>'formFacilida_txTotal','class'=>'form-control','style'=>'width:80px;text-align:right','readOnly'=>true]) ?></td>
	</tr>
</table>
</div>

<?php
Pjax::end();
?>
<div class="form-panel" style="padding-right:8px; padding-bottom: 8px">
<h3><strong>Observación</strong></h3>
<table width="99%">
	<tr>
		<td><?= Html::textarea('formFacilida_txObs',$model->nuevo_obs,['id'=>'formFacilida_txObs','class'=>'form-control','style'=>'width:624px;height:60px;max-width:624px;max-height:120px;resize:none']) ?></td>
	</tr>
</table>
</div>

<div class="text-center" style="margin-bottom: 8px">

	<?= Html::button('Calcular',['id'=>'btCalcular','class' => 'btn btn-success', 'method'=>'POST','onclick'=>'calcular()']); ?>
	&nbsp;&nbsp;

	<?= Html::button('Grabar',['id'=>'btGrabar','class' => 'btn btn-success', 'method'=>'POST','onclick'=>'grabarDatos()']); ?>
	&nbsp;&nbsp;

	<?= Html::a('Cancelar', ['view', 'consulta'=>1], ['class' => 'btn btn-primary']); ?>

</div>

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorRegPagoAnt']);

			$mensaje = '';

			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];


			if($mensaje != ""){

		    	Alert::begin([
		    		'id' => 'AlertaMensajeRegPagoAnt',
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaMensajeRegPagoAnt').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<?php

	echo $form->errorSummary( $model, ['id' => 'facilida_errorSummary']);

	ActiveForm::end();

	if ($consulta==1 or $consulta==2)
	{
		echo "<script>";
		echo "DesactivarForm('formRegPagoAnt');";
		echo "</script>";
	}

    if ($consulta==2) echo '<script>$("#btEliminarAcep").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#btEliminarCanc").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#ModalEmiminar").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#btCancelarModalElim").prop("disabled", false);</script>';

    if ($consulta == 0 or $consulta == 3)
    {
    	if (!utb::getExisteProceso(3442))
    		echo '<script>' .
    				'$("#formFacilida_fchconsolidacion").prop("readonly", true);' .
    				'$("#formFacilida_fchconsolidacion").css("pointer-events", "none");' .
    				'$("#formFacilida_fchconsolidacion").attr("tabindex", "-1");' .
    			 '</script>';
    }

?>

<script>
function cerrarModal()
{
	$("#ModalImprmir, .window").modal("hide");
}

function habilitarprocesar()
{
	valuesCons = $("#formFacilida_fchconsolidacion").val().split("/");
	valueVenc = $("#formFacilida_fchvenc").val().split("/");

	// Verificamos que la fecha no sea posterior a la actual
	var dateCons = new Date(valuesCons[2],(valuesCons[1]-1),valuesCons[0]);
	var dateVenc = new Date(valueVenc[2],(valueVenc[1]-1),valueVenc[0]);
	
	if ( dateCons > dateVenc )
		$("#formFacilida_fchvenc").val( $("#formFacilida_fchconsolidacion").val());

	$("#btCalcular").attr('disabled',true);
	$("#btGrabar").attr('disabled',true);

	$.pjax.reload({container:"#GrillaFormFacilida",method:"POST",limpiaSession:1});

}

function deshabilitarGrabar()
{
	$("#btGrabar").attr('disabled',true);
	$("#formFacilida_txTotal").val('');
	$("#formFacilida_txNominal").val('');
	$("#formFacilida_txAccesorios").val('');
	$("#formFacilida_txMulta").val('');
	$("#formFacilida_txQuita").val('');
}

function rbPeriodo()
{
	if ($("#formFacilida_ckTodosPeriodos").is(":checked"))
	{
		$("#formFacilida_txPeriodoDesdeAnio").attr('readOnly',true);
		$("#formFacilida_txPeriodoDesdeCuota").attr('readOnly',true);
		$("#formFacilida_txPeriodoHastaAnio").attr('readOnly',true);
		$("#formFacilida_txPeriodoHastaCuota").attr('readOnly',true);
	}

	if ($("#formFacilida_ckRangoPeriodos").is(":checked"))
	{
		$("#formFacilida_txPeriodoDesdeAnio").removeAttr('readOnly');
		$("#formFacilida_txPeriodoDesdeCuota").removeAttr('readOnly');
		$("#formFacilida_txPeriodoHastaAnio").removeAttr('readOnly');
		$("#formFacilida_txPeriodoHastaCuota").removeAttr('readOnly');
	}

}

rbPeriodo();

function btProcesar()
{
	var tributo = $("#formFacilida_tributo").val(),
		objetoID = $("#formFacilida_txObjetoID").val(),
		objetonom = $("#formFacilida_txObjetoNom").val(),
		tributo = $("#formFacilida_tributo").val(),
		perDesdeAnio = parseInt($("#formFacilida_txPeriodoDesdeAnio").val()),
		perDesdeCuota = parseInt($("#formFacilida_txPeriodoDesdeCuota").val()),
		perHastaAnio = parseInt($("#formFacilida_txPeriodoHastaAnio").val()),
		perHastaCuota = parseInt($("#formFacilida_txPeriodoHastaCuota").val()),
		fchvenc = $("#formFacilida_fchvenc").val(),
		fchconsolidacion = $("#formFacilida_fchconsolidacion").val(),

		noVencido = 0,
		todosPeriodos = 0,
		perDesde = 0,
		perHasta = 0,

		error = new Array();

	if ($("#formFacilida_ckPerVenc").is(":checked"))
	{
		noVencido = 1;
	} else
	{
		noVencido = 0;
	}

	if ($("#formFacilida_ckTodosPeriodos").is(":checked"))
	{
		todosPeriodos = 1;

		$("#formFacilida_txPeriodoDesdeAnio").val('');
		$("#formFacilida_txPeriodoDesdeCuota").val('');
		$("#formFacilida_txPeriodoHastaAnio").val('');
		$("#formFacilida_txPeriodoHastaCuota").val('');

	} else
	{
		todosPeriodos = 0;
	}

	if ( tributo == 0 || tributo == null )
		error.push( "Ingrese un tributo." );

	if ( objetoID == '' )
	{
		error.push( "Ingrese un objeto." );

	} else if (objetonom == '')
	{
		$("#formFacilida_txObjetoID").val(''); //Pongo en vacío el ID de objeto
		error.push( "Ingrese un objeto válido." );

	} else if (todosPeriodos == 0)
	{
		if (perDesdeAnio == '' || perDesdeCuota == '' || perHastaAnio == '' || perHastaCuota == '')
		{
			error.push( "Ingrese un rango válido." );

		} else if (((parseInt(perDesdeAnio) * 1000) + parseInt(perDesdeCuota) )>((parseInt(perHastaAnio) * 1000) + parseInt(perHastaCuota)))
		{
			error.push( "Ingrese un rango válido." );

		}
	}

	if(isNaN(perDesdeAnio) || perDesdeAnio < 0 || isNaN(perDesdeCuota) || perDesdeCuota < 0) perDesde= 0;
	if(isNaN(perHastaAnio) || perHastaAnio < 0 || isNaN(perHastaCuota) || perHastaCuota < 0) perHasta= 0;

	if(error.length > 0 )
	{
		mostrarErrores( error, "#facilida_errorSummary" );
	} else
	{
		// Oculto el mensaje de error
		$("#facilida_errorSummary").css("display","none");

		/* Habilito el Botón Calcular */
		$("#btCalcular").removeAttr('disabled');
		deshabilitarGrabar();

		perDesde = ((perDesdeAnio * 1000) + perDesdeCuota);
		perHasta = ((perHastaAnio * 1000) + perHastaCuota);

		$.pjax.reload({
			container:"#GrillaFormFacilida",
			method:"POST",
			data:{
				 objetoID:objetoID,
				 tributo:tributo,
				 perDesde:perDesde,
				 perHasta:perHasta,
				 noVencido:noVencido,
				 fchvenc:fchvenc,
				 fchconsolidacion:fchconsolidacion,
			}
		});
	}

}

/*
	Función que actualiza la fecha de vencimiento
*/
function cambiaConsolidacion()
{
	//$( "#formFacilida_fchvenc" ).val( $( "#formFacilida_fchconsolidacion" ).val() );
}

function filtraObjeto()
{
	var tributo = $("#formFacilida_tributo option:selected").val();

	$.pjax.reload({container:"#actualiza_tobj",method:"POST",data:{tributo:tributo}});
}

function grabarDatos()
{
	var tributo = $("#formFacilida_tributo option:selected").val(),
		obj_id = $("#formFacilida_txObjetoID").val(),
		fchvenc = $("#formFacilida_fchvenc").val(),
		fchconsolida = $("#formFacilida_fchconsolidacion").val(),
		obs = $("#formFacilida_txObs").val();

	if ($("#formFacilida_ckBajaAutom").is(":checked"))
	{
		var bajaauto = 1;
	} else
	{
		var bajaauto = 0;
	}

	var anio_actual = (new Date).getFullYear();

	$.pjax.reload({
		container:"#grabarDatos",
		method:"POST",
		data:{
			trib_id:tributo,
			obj_id:obj_id,
			fchvenc:fchvenc,
			fchconsolida:fchconsolida,
			obs:obs,
			bajaauto:bajaauto,
		}
	});


}

function cambiarChecks()
{
	var checks = $('#GrillaPeriodoFacilida input[type="checkbox"]');

	if ($("#formFacilida_ckPeriodoHeader").is(":checked"))
	{
		checks.each(function() {

			checks.prop('checked','checked');

		});
	} else
	{
		checks.each(function() {

			checks.prop('checked','');

		});
	}

	deshabilitarGrabar();
}



function calcular()
{
	var checks = $('#GrillaPeriodoFacilida input:checked').not("#formFacilida_ckPeriodoHeader"),
		objetoID = $("#formFacilida_txObjetoID").val(),
		tributo = $("#formFacilida_tributo").val(),
		arreglo = [];	/* En arreglo obtengo un arreglo con el año y cuota de cada periodo */

	checks.each(function() {

		arreglo.push(($(this).val()));

	});


	if (arreglo.length > 0)
	{
		// Oculto el mensaje de error
		$("#facilida_errorSummary").css("display","none");

		$.pjax.reload({container:"#calcular",method:"POST",data:{arregloCheck:arreglo,trib_id:tributo,obj_id:objetoID}});

	} else
	{
		mostrarErrores(["No hay ningún período seleccionado."], "#");
	}

}

$(document).ready(function() {

	deshabilitarGrabar();

});


</script>
