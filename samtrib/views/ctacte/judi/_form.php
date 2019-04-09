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

$form = ActiveForm::begin(['id' => 'formApremio',
							'action' => ['create']]);
							
							
$capital = 0;

$session = new Session;

$session->open();

//INICIO Bloque actualiza los códigos de objeto de Origen
Pjax::begin(['id' => 'actualiza-tobj']);
	
	$model->nuevo_obj_tipo = Yii::$app->request->post('objeto',4);
	
	if (isset($_POST['objeto']))
	{
		$model->nuevo_obj_id = Yii::$app->request->post('objetoID','');
		
		echo '<script>armarStringExpe();</script>';
		
		echo '<script>$("#formApremio-txObjetoID").val("")</script>';
		
		if (strlen($model->nuevo_obj_id) < 8 && $model->nuevo_obj_id != '')
		{
			$model->nuevo_obj_id = utb::GetObjeto((int)$model->nuevo_obj_tipo,(int)$model->nuevo_obj_id);
			echo '<script>$("#formApremio-txObjetoID").val("'.$model->nuevo_obj_id.'")</script>';
		}
		
		if (utb::getTObj($model->nuevo_obj_id) == $model->nuevo_obj_tipo)
		{
			$objeto_nom = utb::getNombObj("'".$model->nuevo_obj_id."'");
			
			?>
			
			<script>
				$("#formApremio-txObjetoID").val("<?= $model->nuevo_obj_id ?>");
				$("#formApremio-txObjetoNom").val("<?= $objeto_nom ?>");	
				
				//Pongo el foco en el siguiente elemento
				$("#PjaxObjBusAvorigenBusca").on("pjax:end",function () {
					
					$("#formApremio-dlReparticion").focus();
					$("#PjaxObjBusAvorigenBusca").off("pjax:end");
				});
			
			</script>
			
			<?php
		} else 
		{
			?>
			
			<script>
				$("#formApremio-txObjetoID").val("");
				$("#formApremio-txObjetoNom").val("");	
				
				//Pongo el foco en el siguiente elemento
				$("#PjaxObjBusAvorigenBusca").on("pjax:end",function () {
					
					$("#formApremio-txObjetoID").focus();
					$("#PjaxObjBusAvorigenBusca").off("pjax:end");
				});
			
			</script>
			
			<?php
		}
		
	}
	
	//INICIO Modal Busca Objeto
	Modal::begin([
	'id' => 'BuscaObjApremio_nuevo_btOrigenBusca',
	'size' => 'modal-lg',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
	 ]);
								
	echo $this->render('//objeto/objetobuscarav',[
								'idpx' => 'origenBusca',
								'id' => 'Apremio_nuevo_btOrigenBusca',
								'txCod' => 'formApremio-txObjetoID',
								'txNom' => 'formApremio-txObjetoNom',
								'selectorModal' => '#BuscaObjApremio_nuevo_btOrigenBusca',
								'tobjeto' => $model->nuevo_obj_tipo,
			        		]);
	
	Modal::end();
	//FIN Modal Busca Objeto

Pjax::end();
//FIN Bloque actualiza los códigos de objeto de Origen
							
?>
<style>
.form-panel {
	
	padding-bottom: 8px;
}
</style>

<div class="form-panel" style="padding-right:8px">
<h3><strong>Datos del Apremio Judicial</strong></h3>

<table>
	<tr>
		<td width="250px">
			<label>Objeto:</label>
			<?= Html::dropDownList('dlObjeto',$model->nuevo_obj_tipo,utb::getAux('objeto_tipo','cod','nombre', 0, "est='A'"),[
					'id' => 'formApremio-dlObjeto', 
					'class' =>'form-control',
					'style' => 'width:100%;',
					'onchange'=>'$.pjax.reload({container:"#actualiza-tobj",method:"POST",data:{objeto:$(this).val()}})',
				]);
			?>
		</td>
		<td valign="bottom" style="padding-bottom:2px;">
			<!-- INICIO BOTON Buscar Objeto -->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
					'class' => 'bt-buscar '.($consulta == 3 ? 'disabled' : ''),
					'id'=>'formApremio-btBuscaObj',
					'onclick'=>'$("#BuscaObjApremio_nuevo_btOrigenBusca, .window").modal("show")',
				]);
			?>
			<!-- FIN BOTON Buscar Objeto -->
		</td>
		<td width="70px" valign="bottom">
			<?= Html::input('text','txObjetoID',$model->nuevo_obj_id,[
					'id'=>'formApremio-txObjetoID',
					'class'=>'form-control',
					'style'=>'width:70px;text-align:center',
					'maxlength' => 8,
					'onchange'=>
							'$.pjax.reload({container:"#actualiza-tobj",' .
							'method:"POST",' .
							'data:{	objetoID:$(this).val(),' .
							'objeto:$("#formApremio-dlObjeto").val()}})',
				]); 
			?>
		</td>
		<td width="280px">
			<label>Carátula:</label>
			<?= Html::input('text','txObjetoNom',$model->nuevo_obj_nom,[
					'id'=>'formApremio-txObjetoNom',
					'class'=>'form-control',
					'style'=>'width:100%',
					'maxlength' => 50,
				]);
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="80px"><label>Repartición:</label></td>
		<td width="200px">
			<?= Html::dropDownList('dlReparticion',$model->nuevo_reparticion,utb::getAux('judi_trep'),[
					'id' => 'formApremio-dlReparticion',
					'class' =>'form-control',
					'style' => 'width:100%;',
					'onchange'=>'armarStringExpe()',
				]);
			?>
		</td>
		<td width="70px"></td>		
		<td width="20px"><label>Nro:</label></td>
		<td width="100px">
			<?= Html::input('text','txNro',$model->nuevo_numero,[
					'id'=>'formApremio-txNro',
					'class'=>'form-control',
					'style'=>'width:100%;text-align:center',
					'maxlength' => 10,
					'onchange'=>'armarStringExpe()',
				]);
			?>
		</td>	
		<td width="30px"></td>
		<td width="30px"><label>Año:</label></td>
		<td width="50px"><?= Html::input('text','txAnio',($model->nuevo_anio != '' ? $model->nuevo_anio : date('Y')),['id'=>'formApremio-txAnio','class'=>'form-control','style'=>'width:100%;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>4,'onchange'=>'armarStringExpe()']) ?></td>	
	</tr>
</table>
<?= Html::input('hidden','txReparticionNom',null);?>
<table>
	<tr>
		<td width="80px"><label>Expediente:</label></td>
		<td width="200px"><?= Html::input('text','txExpe',$model->nuevo_expe,['id'=>'formApremio-txExpe','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:left;','readOnly'=>true,'tabIndex' => -1]) ?></td>	
		<td width="70px"></td>
		<td width="140px"><label>Fecha Consolidación:</label></td>
		<td width="95px"><?=  DatePicker::widget([	'id' => 'formApremio-fchconsolida',
													'name' => 'fchconsolida',
													'dateFormat' => 'dd/MM/yyyy',
													'options' => ['class' => 'form-control', 'style' => 'width:95px;text-align:center'],
													'value' => ($model->nuevo_fchconsolida != '' ? Fecha::usuarioToDatePicker($model->nuevo_fchconsolida) : Fecha::usuarioToDatePicker(Fecha::getDiaActual())),
												]);	?>
		</td>
	</tr>
</table>		
</div>

<div class="form-panel" style="padding-right:8px">
<h3><strong>Períodos a incluir</strong></h3>
<table>
	<tr>
		<td>
			<?= Html::radio('rdPeriodo',$model->nuevo_todos_periodos, [
					'id' => 'formApremio-ckTodosPeriodos',
					'label' => 'Todos los Períodos',
					'onchange'=>'rbPeriodo()',
				]);
			?>
		</td>
		<td width="25px"></td>
		<td>
			<?= Html::radio('rdPeriodo',$model->nuevo_rango, [
					'id' => 'formApremio-ckRangoPeriodos',
					'label' => 'Según Rango',
					'onchange'=>'rbPeriodo()']); ?></td>
		<td width="25px"></td>
		<td><label>Desde</label></td>
		<td>
			<?= Html::input('text','txPeriodoDesdeAnio',$model->nuevo_desdeanio,[
					'id'=>'formApremio-txPeriodoDesdeAnio',
					'class'=>'form-control',
					'style'=>'width:40px;',
					'onkeypress'=>'return justNumbers(event)', 
					'maxlength'=>4,
				]);
			?>
			<?= Html::input('text','txPeriodoDesdeCuota',$model->nuevo_desdecuota,[ 
					'id'=>'formApremio-txPeriodoDesdeCuota',
					'class'=>'form-control',
					'style'=>'width:40px;',
					'onkeypress'=>'return justNumbers(event)',
					'maxlength'=>3,
				]);
			?>
		</td>
		<td width="25px"></td>
		<td><label>Hasta</label></td>
		<td>
			<?= Html::input('text','txPeriodoHastaAnio',$model->nuevo_hastaanio,[
					'id'=>'formApremio-txPeriodoHastaAnio',
					'class'=>'form-control',
					'style'=>'width:40px;',
					'onkeypress'=>'return justNumbers(event)',
					'maxlength'=>4,
				]);
			?>
			<?= Html::input('text','txPeriodoHastaCuota',$model->nuevo_hastacuota,[
					'id'=>'formApremio-txPeriodoHastaCuota',
					'class'=>'form-control',
					'style'=>'width:40px;',
					'onkeypress'=>'return justNumbers(event)',
					'maxlength'=>3,
				]);
			?>
		</td>
		<td width="20px"></td>
		<!-- BOTÓN Procesar -->
		<td><?= Html::Button('<i class="glyphicon glyphicon-play-circle"></i>', [ 
					'id'=>'btProcesa',
					'class' => 'bt-buscar',
					'onclick' => 'btProcesar()',
					'title' => 'Procesar',
				]); 
			?>
		</td>
		<td width="5px"></td>
		<td>
			<?= Html::Button('<i class="glyphicon glyphicon-cog"></i>', [ 
					'id' => 'btBuscar',
					'class' => 'bt-buscar',
					'onclick' => 'btPeriodosAvClick()',
				]);
			?>
		</td>
	</tr>
</table>
		<?php
			
			Modal::begin([
            	'id' => 'PeriodosAV',
            	'header' => '<h4><b>Períodos agrupados por tributo</b></h4>',
				'closeButton' => [
                	'label' => '<b>X</b>',
                  	'class' => 'btn btn-danger btn-sm pull-right',
                	],
                'size' => 'modal-sm',
            ]);
            
				Pjax::begin(['id' => 'DivModalPerAv']);
				
					$obj_id = (isset($_POST['obj']) ? $_POST['obj'] : ""); 
					$desde = (isset($_POST['desde']) ? $_POST['desde'] : 0);
					$hasta = (isset($_POST['hasta']) ? $_POST['hasta'] : 0);
					$fecha = (isset($_POST['fecha']) ? Fecha::usuarioToBD($_POST['fecha']) : Fecha::usuarioToBD($dia));
									
					$periodo = $model->buscarPeriodo($obj_id,$desde,$hasta,$fecha);
					
					?>
					
					<div class="div_grilla" style="margin-bottom: 8px">
					
					<?php
					
					echo  GridView::widget([
							'dataProvider' => $periodo,
							'id' => 'GrillaPerAv',
							'headerRowOptions' => ['class' => 'grilla'],
							'rowOptions' => ['class' => 'grilla'],
	        				'columns' => [
	        						['content'=> function($model, $key, $index, $column) {return Html::checkbox('formApremio-ckPeriodo[]',true,[
																		'id' => 'formApremio-ckTributo'.$model['trib_id'], 
																		'value'=>$model['trib_id'], 
																		'style' => 'width:11px;height:11px;margin:0px',
																		'onchange'=>'deshabilitarGrabar()']);},
																		'contentOptions'=>['style'=>'width:2px'],],
	        						['attribute'=>'obj_id','header' => 'Objeto','contentOptions'=>['style'=>'text-align:left']],
	        						['attribute'=>'trib_nom','header' => 'Tributo','contentOptions'=>['style'=>'text-align:left']],
	            					['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'text-align:right']],
						   	],
	   				]);
	   				
	   				?>
	   				
	   				</div>
	   				
	   				<div class="text-center">
	   					
	   					<?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'btProcesar()']); ?>
	   					&nbsp;&nbsp;
						<?= Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("#PeriodosAV, .window").modal("hide");']); ?>
					
					</div>
					
					<?php
	            Pjax::end();
            
            Modal::end(); 
		?>

<br />
<!-- INICIO GRILLA -->
<?php

//INICIO Bloque que se ejecuta al presionar el botón Procesar
Pjax::begin(['id'=>'GrillaFormApremio']);
	
	$session->open();
	
	if ($session['formApremio-banderaDatos'] == 1) 
		$session['arregloPeriodosApremio'] = null;
		
	if(isset($_POST['objetoID']))
	{
		 $objetoID = $_POST['objetoID'];
		 $perDesde = $_POST['perDesde'];
		 $perHasta = $_POST['perHasta'];
		 $fchconsolidacion = $_POST['fchconsolidacion'];
		 $arregloTributo = Yii::$app->request->post('arregloTributo',[]);
		 
		 $cond = '';
		 
		 if (count($arregloTributo) > 0)
		 {
		 	$cond = 'trib_id IN(';
		 	foreach ($arregloTributo as $ar)
		 	{
		 		$cond .= $ar . ',';
		 	}
		 	
		 	$cond = substr($cond, 0, -1);
		 	$cond .= ')';
		 }

		
		 echo $model->cargarDeudaDetalle($objetoID,$perDesde,$perHasta,$fchconsolidacion,$cond);
		 
		
		 $session['arregloPeriodosApremio'] = $model->nuevo_periodos;
		 $session['formApremio-banderaDatos'] = 0;

		 echo '<script>deshabilitarGrabar()</script>';	//Deshabilito el botón grabar en caso de que se encuentre activo
	}
	
	//INICIO Bloque calcular
	Pjax::begin(['id'=>'calcular']);
	
	
		//Si se recibe un arreglo con períodos checkeados
		if (isset($_POST['arregloCheck']))
		{
			$obj_id = $_POST['obj_id'];
			
			$perFiltrados = [];
			
			$arrayCheck = $_POST['arregloCheck'];
	
			$calculo = $model->calcularApremio($arrayCheck,$obj_id,$masiva = false);
			
			if ($calculo == '')
			{
				$model->nuevo_periodos = $session['arregloPeriodosApremio'];
				
				$capital = number_format(floatval(Yii::$app->session->get('Apremio-nuevo_capital',0)),2,'.','');
				$nominal = number_format(floatval(Yii::$app->session->get('Apremio-nuevo_nominal',0)),2,'.','');
				$accesor = number_format(floatval(Yii::$app->session->get('Apremio-nuevo_accesor',0)),2,'.','');
				$multa = number_format(floatval(Yii::$app->session->get('Apremio-nuevo_multa',0)),2,'.','');
				
				echo '<script>$("#formApremio-txNominal").val("'.$nominal.'");</script>';
				echo '<script>$("#formApremio-txAccesor").val("'.$accesor.'");</script>';
				echo '<script>$("#formApremio-txMulta").val("'.$multa.'");</script>';
				echo '<script>$("#btGrabar").removeAttr("disabled")</script>';
				echo '<script>$("#formApremio-txTotal").val("'.$capital.'");</script>';
				echo '<script>$("#formApremio-txMultaOmis").removeAttr("readOnly")</script>';
				
			} else 
			{
				echo '<script>$.pjax.reload({container:"#errorApremioJudi",method:"POST",data:{mensaje:"'.$calculo.'."}});</script>';
			}
	
		}
	
	
		Pjax::begin(['id'=>'actualizaTotal']);
		
			if (isset($_POST['multaOms']))
			{
				$multaO = $_POST['multaOms'];
				$capital = $session['Apremio-nuevo_total'];
				
				$total = $capital + $multaO; 
				$session['Apremio-nuevo_capital'] = $total;
				
				echo '<script>$("#formApremio-txTotal").val('.$total.');</script>';
			}
			
			
		
		Pjax::end();
		
	
     echo GridView::widget([
		'id' => 'GrillaPeriodoApremio',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $model->obtenerDeudaDetalle(),
		'summaryOptions' => ['class' => 'hidden'],
		'columns' => [
				['content'=> function($model, $key, $index, $column) {return Html::checkbox('formApremio-ckPeriodo[]',$model['activo'],[
																		'id' => 'formApremio-ckPeriodo'.$model['ctacte_id'], 
																		'value'=>$model['ctacte_id'],
																		'style' => 'width:11px;height:11px;margin:0px',
																		'onchange'=>'deshabilitarGrabar()']);},
																		'contentOptions'=>['style'=>'width:2px'],
																		'header' => Html::checkBox('selection_all', false, [
																			'id' => 'formApremio_ckPeriodoHeader',
																	        'onchange'=>'cambiarChecks()',
																	    ]),
				],
				['attribute'=>'trib_nom','header' => 'Tributo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
				['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
				['attribute'=>'subcta','header' => 'Subcta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
				['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
				['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
				['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
				['attribute'=>'nominal','header' => 'Nominal', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
				['attribute'=>'accesor','header' => 'Accesor', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
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

<?php
Pjax::begin(['id'=>'datos']);

$session->open();

/**
 *     	$session['Apremio-nuevo_capital'] = $capital;
    	$session['Apremio-nuevo_nominal'] = $model->nominal;
    	$session['Apremio-nuevo_accesor'] = $accesor;
    	$session['Apremio-nuevo_multa'] = $multa;
 */
?>

<div class="form-panel" style="padding-right:8px">
<h3><strong>Detalle de Deuda</strong></h3>
<table align="center">
	<tr>
		<td width="75px">
			<label>Nominal:</label>
			<?= Html::input('text','txNominal',null,['id'=>'formApremio-txNominal','class'=>'form-control','style'=>'width:100%;text-align:right;background:#E6E6FA','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="75px">
			<label>Accesorios:</label>
			<?= Html::input('text','txAccesor',null,['id'=>'formApremio-txAccesor','class'=>'form-control','style'=>'width:100%;text-align:right;background:#E6E6FA','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="75px">
			<label>Multa:</label>
			<?= Html::input('text','txMulta',null,['id'=>'formApremio-txMulta','class'=>'form-control','style'=>'width:100%;text-align:right;background:#E6E6FA','readOnly'=>true]) ?></td>
		<td width="20px"></td>
		<td width="75px">
			<label>Multa Omis.:</label>
			<?= Html::input('text','txMultaOmis',0,[ 
					'id'=>'formApremio-txMultaOmis',
					'class'=>'form-control',
					'style'=>'width:100%;text-align:right',
					'maxlength' => 12,
					'readOnly'=>true,
					'onchange'=>'$.pjax.reload({container:"#actualizaTotal",method:"POST",data:{multaOms:$(this).val()}})',
				]);
			?>
		</td>
		<td width="20px"></td>
		<td width="75px">
			<label>Total:</label>
			<?= Html::input('text','txTotal',null,['id'=>'formApremio-txTotal','class'=>'form-control','style'=>'width:100%;text-align:right;background:#E6E6FA','readOnly'=>true]) ?></td>
	</tr>
</table>
</div>

<?php
Pjax::end();
?>

<div class="form-panel" style="padding-right:8px">
<h3><strong>Observación</strong></h3>
<table width="99%">
	<tr>
		<td><?= Html::textarea('txObs',$model->nuevo_obs,['id'=>'formApremio-txObs','class'=>'form-control','style'=>'width:624px;height:60px;max-width:624px;max-height:120px']) ?></td>
	</tr>
</table>
</div>

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 

			Pjax::begin(['id'=>'errorApremioJudi']);
			
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

<div class="text-center">

	<?= Html::button('Calcular',['id'=>'btCalcular','class' => 'btn btn-success', 'method'=>'POST','onclick'=>'calcular()']); ?>
	&nbsp;&nbsp;
	<?= Html::button('Grabar',['id'=>'btGrabar','class' => 'btn btn-success', 'method'=>'POST','onclick'=>'grabarDatos()']); ?>
	&nbsp;&nbsp;
	<?= Html::a('Cancelar', ['view', 'consulta'=>1], ['class' => 'btn btn-primary']); ?>

</div>

<?php		
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
	
?>
<!-- INICIO Mensajes de error -->
<div id="formApremio_errorSummary" class="error-summary" style="display:none;margin-top: 8px; margin-right: 15px">
	
	<ul>
	</ul>
	
</div>
<!-- FIN Mensajes de error -->

<script>

function btPeriodosAvClick()
{
	var error = new Array(),
		objetoID = $("#formApremio-txObjetoID").val(),
		objetonom = $("#formApremio-txObjetoNom").val(),
		perDesdeAnio = parseInt($("#formApremio-txPeriodoDesdeAnio").val()),
		perDesdeCuota = parseInt($("#formApremio-txPeriodoDesdeCuota").val()),
		perHastaAnio = parseInt($("#formApremio-txPeriodoHastaAnio").val()),
		perHastaCuota = parseInt($("#formApremio-txPeriodoHastaCuota").val()),
		fchconsolidacion = $("#formApremio-fchconsolida").val(),
		todosPeriodos = 1,
		perDesde = 0,
		perHasta = 0;
		
	if ($("#formApremio-ckTodosPeriodos").is(":checked"))
	{
		$("#formApremio-txPeriodoDesdeAnio").val('');
		$("#formApremio-txPeriodoDesdeCuota").val('');
		$("#formApremio-txPeriodoHastaAnio").val('');
		$("#formApremio-txPeriodoHastaCuota").val('');
	} else 
	{
		todosPeriodos = 0;
	}
	
	if ( objetoID == '' )
	{
		error.push( "Ingrese un objeto." );
			
	} else if ( objetonom == '')
	{
		$("#formApremio-txObjetoID").val(''); //Pongo en vacío el ID de objeto
		error.push( "Ingrese un objeto válido." );
		
	} 
	
	if ( todosPeriodos == 0 )
	{
		if ( perDesdeAnio == '' || perDesdeCuota == '' || perHastaAnio == '' || perHastaCuota == '' )
		{
			error.push( "Ingrese un rango válido." );
			
		} else if (((parseInt(perDesdeAnio) * 1000) + parseInt(perDesdeCuota) )>((parseInt(perHastaAnio) * 1000) + parseInt(perHastaCuota)))
		{
			error.push( "Ingrese un rango válido." );	
		} 
	} 

	if( error.length > 0 )
	{
		mostrarErrores( error, "#formApremio_errorSummary" );
	} else 
	{
		perDesde = ((perDesdeAnio * 1000) + perDesdeCuota);
		perHasta = ((perHastaAnio * 1000) + perHastaCuota);
		
		$.pjax.reload({
			container:"#DivModalPerAv",
			data:{
				obj:objetoID,
				desde:perDesde,
				hasta:perHasta,
				fecha:fchconsolidacion
			},
			method:"POST"
		});
	
		$("#PeriodosAV").modal();
	}
}

function armarStringExpe()
{
	var reparticion = $("#formApremio-dlReparticion option[value="+$("#formApremio-dlReparticion").val()+"]").text(),
		anio = $("#formApremio-txAnio").val(),
		numero = $("#formApremio-txNro").val(),
		expe = reparticion + ' - ' + numero + ' - ' + anio;
	
	$("#formApremio-txExpe").val(expe);
}

function cambiarChecks()
{
	var checks = $('#GrillaPeriodoApremio input[type="checkbox"]');
	
	if ($("#formApremio_ckPeriodoHeader").is(":checked"))
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

function habilitarprocesar()	/* Deshabilita los botones de calcular y grabar */
{
	$("#btCalcular").attr('disabled',true);
	$("#btGrabar").attr('disabled',true);
}

function deshabilitarGrabar()
{
	$("#btGrabar").attr('disabled',true);
	$("#formApremio-txTotal").val('');
	$("#formApremio-txNominal").val('');
	$("#formApremio-txAccesor").val('');
	$("#formApremio-txMulta").val('');
	$("#formApremio-txQuita").val('');
	$("#formApremio-txMultaOmis").val(0);
	$("#formApremio-txMultaOmis").attr('readOnly',true);
}

function rbPeriodo()
{
	if ($("#formApremio-ckTodosPeriodos").is(":checked"))
	{
		$("#formApremio-txPeriodoDesdeAnio").attr('readOnly',true);
		$("#formApremio-txPeriodoDesdeCuota").attr('readOnly',true);
		$("#formApremio-txPeriodoHastaAnio").attr('readOnly',true);
		$("#formApremio-txPeriodoHastaCuota").attr('readOnly',true);
	} 
	
	if ($("#formApremio-ckRangoPeriodos").is(":checked"))
	{
		$("#formApremio-txPeriodoDesdeAnio").removeAttr('readOnly');
		$("#formApremio-txPeriodoDesdeCuota").removeAttr('readOnly');
		$("#formApremio-txPeriodoHastaAnio").removeAttr('readOnly');
		$("#formApremio-txPeriodoHastaCuota").removeAttr('readOnly');
	}
	
}

rbPeriodo();

function btProcesar()
{	
	var error = new Array(),
		objetoID = $("#formApremio-txObjetoID").val(),
		objetonom = $("#formApremio-txObjetoNom").val(),
		perDesdeAnio = parseInt($("#formApremio-txPeriodoDesdeAnio").val()),
		perDesdeCuota = parseInt($("#formApremio-txPeriodoDesdeCuota").val()),
		perHastaAnio = parseInt($("#formApremio-txPeriodoHastaAnio").val()),
		perHastaCuota = parseInt($("#formApremio-txPeriodoHastaCuota").val()),
		fchconsolidacion = $("#formApremio-fchconsolida").val(),
		checksTributo = $('#GrillaPerAv input:checked'),
		arregloTributo = new Array()	/* En arreglo obtengo un arreglo con el año y cuota de cada periodo */
		todosPeriodos = 0,
		perDesde = 0,
		perHasta = 0;
	
	checksTributo.each(function() {
	
		arregloTributo.push(($(this).val()));
		
	});

	if ($("#formApremio-ckTodosPeriodos").is(":checked"))
	{
		todosPeriodos = 1;
		
		$("#formApremio-txPeriodoDesdeAnio").val('');
		$("#formApremio-txPeriodoDesdeCuota").val('');
		$("#formApremio-txPeriodoHastaAnio").val('');
		$("#formApremio-txPeriodoHastaCuota").val('');
		
	} else 
	{
		todosPeriodos = 0;
	}
	
	if (objetoID == '')
	{
		error.push( "Ingrese un objeto." );
			
	} else if (objetonom == '')
	{
		$("#formApremio-txObjetoID").val(''); //Pongo en vacío el ID de objeto
		error.push( "Ingrese un objeto válido." );
		
	} 
	
	if (todosPeriodos == 0)
	{
		if (perDesdeAnio == '' || perDesdeCuota == '' || perHastaAnio == '' || perHastaCuota == '')
		{
			error.push( "Ingrese un rango válido." );
			
		} else if (((parseInt(perDesdeAnio) * 1000) + parseInt(perDesdeCuota) )>((parseInt(perHastaAnio) * 1000) + parseInt(perHastaCuota)))
		{
			error.push( "Ingrese un rango válido." );	
		
		} 
	} 

	if( error.length > 0 )
	{
		mostrarErrores( error, "#formApremio_errorSummary" );
	} else 
	{
		
		/* Habilito el Botón Calcular */
		$("#btCalcular").removeAttr('disabled');
		deshabilitarGrabar();
		
		perDesde = ((perDesdeAnio * 1000) + perDesdeCuota);
		perHasta = ((perHastaAnio * 1000) + perHastaCuota);
		
		$("#PeriodosAV, .window").modal("hide");
		
		$.pjax.reload({
			container:"#GrillaFormApremio",
			method:"POST",
			data:{
				objetoID:objetoID,
				perDesde:perDesde,
				perHasta:perHasta,
				fchconsolidacion:fchconsolidacion,	
				arregloTributo:arregloTributo,											
			},
		});
	}
		
}

function filtraObjeto()
{
	var tributo = $("#formApremio-tributo option:selected").val();
	
	$.pjax.reload({container:"#actualiza-tobj",method:"POST",data:{tributo:tributo}});
}

function grabarDatos()
{
	var obj_id = $("#formApremio-txObjetoID").val(),
		fchvenc = $("#formApremio-fchvenc").val(),
		fchconsolida = $("#formApremio-fchconsolidacion").val(),
		obs = $("#formApremio-txObs").val(),
		bajaauto = 0,
		anio_actual = (new Date).getFullYear();
		
	if ($("#formApremio-ckBajaAutom").is(":checked"))
	{
		bajaauto = 1;	
	} else 
	{
		bajaauto = 0;
	}
	
	$("#txReparticionNom").val($("#formApremio-dlReparticion option[value="+$("#formApremio-dlReparticion").val()+"]").text());
	
	$("#formApremio").submit();
	
}

function calcular()
{
	var checks = $('#GrillaPeriodoApremio input:checked').not("#formApremio_ckPeriodoHeader"),
		objetoID = $("#formApremio-txObjetoID").val(),
		arreglo = new Array();
	
	/* En arreglo obtengo un arreglo con el año y cuota de cada periodo */
	checks.each(function() {
	
		arreglo.push(($(this).val()));
		
	});
	
	if ( arreglo.length > 0 )
	{
		$.pjax.reload({
			container:"#calcular",
			method:"POST",
			data:{
				arregloCheck:arreglo,
				obj_id:objetoID,
			},
		});
	} else
	{
		mostrarErrores( ["No hay ningún período checkeado."], "#formApremio_errorSummary" );
	}
	
	
}

$("#actualiza-tobj").on("pjax:end", function() {
	
	habilitarprocesar();
});

$(document).ready(function() {
	
	armarStringExpe();
	
	filtraObjeto();
});




</script>