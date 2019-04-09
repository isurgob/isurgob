<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use yii\web\Session;
use app\models\ctacte\Fiscaliza;
use yii\data\ArrayDataProvider;

/**
 * Forma que se dibuja para consultar, dar de alta, modificar y eliminar fiscalizaciones.
 * Recibo:
 * 			=> $model -> Modelo
 *			=> $consulta es una variable que:
 *			 		=> $consulta == 1 => El formulario se dibuja en el index
 *			  		=> $consulta == 0 => El formulario se dibuja en el create
 *			  		=> $consulta == 3 => El formulario se dibuja en el update
 *			  		=> $consulta == 2 => El formulario se dibuja en el delete
 *
 *			=> $fiscaliza -> Por defecto es 0.
 */


$session = new Session;

$form = ActiveForm::begin([
	'id'=>'frmFiscaliza',
	]);

 
 //INICIO Bloque actualiza los códigos de objeto
Pjax::begin(['id' => 'PjaxCambiaComercio', 'enableReplaceState' => false, 'enablePushState' => false]);

	$comer_id = Yii::$app->request->get('comer_id','');
	
	if ($comer_id != '')
	{
		if (strlen($comer_id) < 8 && $comer_id != '')	//Competo el nombre del objeto
		{
			$comer_id = utb::GetObjeto(2,(int)$comer_id);
			echo '<script>$("#fiscaliza_txComercioID").val("'.$comer_id.'")</script>';
		}
		
		//Validar que exisa el comercio
		$existe = utb::verificarExistencia('comer',"obj_id = '" . $comer_id . "'");
		
		//Si existe el comercio
		if ($existe == 1)
		{
			//Buscar el nombre de comercio y asignarlo en el Edit correspondiente
			$comer_nom = utb::getNombObj("'".$comer_id."'");
			
			?>
			
				<script>
					$("#fiscaliza_txComercioNom").val("<?=$comer_nom; ?>");
					
					$("#PjaxCambiaComercio").on("pjax:end",function() {
						$("#fiscaliza_dlEstado").focus();
					});
				</script>
			
			<?php
		} else //Borro el dato ingresado 
		{
			?>
			
				<script>
					$("#fiscaliza_txComercioID").val("");
					$("#fiscaliza_txComercioNom").val("");
					
					$("#PjaxCambiaComercio").on("pjax:end",function() {
						$("#fiscaliza_txComercioID").focus();
					});
				</script>
			
			<?php
		}
		
	}
	
Pjax::end();
//FIN Bloque actualiza los códigos de objeto
 
?>

<style>
#ModalMovimientos .modal-content{
	
	width: 70% !important;
	margin-left: 15%;
	
}
</style>

<div class="fiscaliza_form">

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 

			Pjax::begin(['id'=>'errorFormFiscalizacion','enablePushState' => false, 'enableReplaceState' => false]);
			
				$mensaje = Yii::$app->request->get('mensaje',$alert);
				$m = Yii::$app->request->get('m',$m);
			
				if($mensaje != "")
				{ 
			
			    	Alert::begin([
			    		'id' => 'AlertaFormFiscalizacion',
						'options' => [
			        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
			        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
			    		],
					]);
			
					echo $mensaje;
							
					Alert::end();
					
					echo "<script>window.setTimeout(function() { $('#AlertaFormFiscalizacion').alert('close'); }, 5000)</script>";
				 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<div class="form-panel" style="padding-right:2px">
<table>
	<tr>
		<td width="50px" ><label for="codigo">Código:</label></td>
		<td>
			<?= Html::input('text','Fiscaliza[fisca_id]',$model->fisca_id,[
					'id'=>'fiscaliza_txCod',
					'class'=>'form-control solo-lectura',
					'style'=>'width:40px;text-align:center',
					'tabIndex' => -1]) ?>
		</td>
		<td width="10px"></td>
		<td><label for="fiscaliza_txExpe">Expediente:</label></td>
		<td>
			<?= Html::input('text','Fiscaliza[expe]',$model->expe,[
					'id'=>'fiscaliza_txExpe',
					'class'=>'form-control',
					'style'=>'width:90px;text-align:center',
					'maxlength' => 12,
					'tabIndex' => 0]) ?>
		</td>
		<td width="10px"></td>
		<td width="62px"><label for="fiscaliza_txComercioID">Comercio:</label></td>
		<td>
			<?= Html::input('text','Fiscaliza[obj_id]',$model->obj_id,[
					'id'=>'fiscaliza_txComercioID',
					'class'=>'form-control',
					'style'=>'width:65px;text-align:center',
					'maxlength' => 8,
					'onchange' => '$.pjax.reload({container:"#PjaxCambiaComercio",type:"GET",replace:false,ùsh:false,data:{comer_id:$(this).val()}})',
					'tabIndex' => 0]) ?>
		</td>
		<!-- INICIO Botón Búsqueda Comercio -->
		<td>
		<?php
			//INICIO Modal Busca Objeto
			Modal::begin([
			'id' => 'BuscaObj',
			'size' => 'modal-lg',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Comercio</h2>',
			'toggleButton' => [
				'label' => '<i class="glyphicon glyphicon-search"></i>',
				'class' => 'bt-buscar',
				'id'=>'fiscaliza_btAgregaComercio'
			],
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			],
			 ]);
										
			echo $this->render('//objeto/objetobuscarav',[
										'idpx' => 'buscaFiscaliza','id' => 'fiscalizaaltaBuscar', 'txCod' => 'fiscaliza_txComercioID', 'txNom' => 'fiscaliza_txComercioNom', 'tobjeto' => 2, 'selectorModal' => '#BuscaObj'
					        		]);
			
			Modal::end();
			//FIN Modal Busca Objeto
			
		?>
		</td>
		<!-- FIN Botón Búsqueda Comercio -->
		<td width="195px">
			<?= Html::input('text','txComercioNom',utb::getCampo('v_comer',"obj_id = '" . $model->obj_id . "'",'nombre'),[
					'id'=>'fiscaliza_txComercioNom',
					'class'=>'form-control solo-lectura',
					'style'=>'width:100%;text-align:left',
					'tabindex' => '-1']);
			?>
		</td>
	</tr>
		<td width="50px"><label for="fiscaliza_dlEstado">Estado:</label></td>
		<td colspan="4">			
			<?= Html::dropDownList('Fiscaliza[est]', $model->est, utb::getAux('fiscaliza_test'), 
			[	'style' => 'width:100%',
				'class' => 'form-control',
				'id'=>'fiscaliza_dlEstado',
			]); ?>
		</td>
		<td width="20px"></td>
		<td width="50px"><label for="fiscaliza_dlInspector">Inspector:</label></td>
		<td colspan="3">			
			<?= Html::dropDownList('Fiscaliza[inspector]', $model->inspector, utb::getAux('sam.sis_usuario','usr_id','apenom',0,'inspec_comer <> 0'), 
			[	'style' => 'width:100%',
				'class' => 'form-control',
				'id'=>'fiscaliza_dlInspector',
			]); ?>
		</td>
	</tr>
		<td width="50px"><label for="fiscaliza_txObs">Obs:</label></td>
		<td colspan="9">			
			<?= Html::textarea('Fiscaliza[obs]', $model->obs, [
					'style' => 'width:100%;max-width:582px;height:60px;max-height:120px',
					'class' => 'form-control',
					'id'=> 'fiscaliza_txObs',
					'maxlength' => 250,
				]); 
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td><label>Fecha Alta:</label></td>
		<td width="80px">
			<?= Html::input('text','txFechaAlta',Fecha::bdToUsuario($model->fchalta),[
					'id' => 'fiscaliza_txFechaAlta',
					'class' => 'form-control solo-lectura',
					'style' => 'width:100%;text-align:center;',
					'tabIndex' => -1,
				]);
			?>
		</td>
		<td width="20px"></td>
		<td><label>Fecha Baja:</label></td>
		<td width="80px">
			<?= Html::input('text','txFechaBaja',Fecha::bdToUsuario($model->fchbaja),[
					'id' => 'fiscaliza_txFechaBaja',
					'class' => 'form-control solo-lectura',
					'style' => 'width:100%;text-align:center;',
					'tabIndex' => -1,
				]);
			?>
		</td>
		<td width="20px"></td>
		<td><label>Modificación:</label></td>
		<td width="222px">
			<?= Html::input('text','txFechaAlta',($model->usrmod != '' ? utb::getCampo('sam.sis_usuario','usr_id = ' . $model->usrmod, 'nombre') . ' - ' . Fecha::bdToUsuario($model->fchmod) : ''),[
					'id' => 'fiscaliza_txFechaAlta',
					'class' => 'form-control solo-lectura',
					'style' => 'width:100%;text-align:center;',
					'tabIndex' => -1,
				]);
			?>
		</td>
	</tr>
</table>
		
<ul class='menu_derecho' style="padding-right:8px;margin-bottom:0px">
	<li><hr style="color: #ddd; margin:1px" /></li>
</ul>

<!-- INICIO Grilla Rubros -->
<div style="padding-right:8px;padding-bottom:8px">
<table width="100%">
	<tr>
		<td align="left"><h3><label>Rubros:</label></h3></td>
		<td align="right">
			<?php
				
				if ($consulta == 0 || $consulta == 3)	
					echo Html::button('Agregar Rubro',['class'=>'btn btn-success','onclick'=>'$.pjax.reload({container:"#manejadorRubro",method:"POST",replace:false,push:false,data:{action:1}});']); 
				
			?>
		</td>
	</tr>
</table>
<?php

	Pjax::begin(['id' => 'PjaxGrillaRubrosFiscaliza', 'enableReplaceState' => false, 'enablePushState' => false]);
	
		//El arreglo con la información de rubros lo voy a mantener en sesión.
		
		$session = new Session;
		$session->open();
		
		$arrayRubros = $session->get('arregloRubros',[]);

		$dataProvRubros = new ArrayDataProvider(['models' => $arrayRubros]); 
			
		echo GridView::widget([
				'id' => 'GrillaRubrosFiscaliza',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProvRubros,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'subcta','header' => 'Suc', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'trib_nom','header' => 'Tributo', 'contentOptions'=>['style'=>'text-align:left','width'=>'60px']],
						['attribute'=>'rubro_nom','header' => 'Rubro', 'contentOptions'=>['style'=>'text-align:left','width'=>'80px']],
						['attribute'=>'obs','header' => 'Descripción', 'contentOptions'=>['style'=>'text-align:left','width'=>'120px']],
						['attribute'=>'perdesde','header' => 'Desde', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'cant','header' => 'Cant.', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						[
							'class' => 'yii\grid\ActionColumn',
							'contentOptions'=>['style'=>'width:6px'],
							'template' =>'{update}{delete}',
							'buttons'=>[	
							
								'update' =>  function($url, $model, $key) use ( $consulta )
											{
												if ( $consulta == 0 || $consulta == 3 )
													return Html::Button('<span class="glyphicon glyphicon-pencil"></span>', [
															'class'=>'bt-buscar-label',
															'style'=>'color:#337ab7',
															'onclick'=>'$.pjax.reload({container:"#manejadorRubro",method:"POST",replace:false,push:false,data:{id_rubro:"r'.$model["rubro_id"].'",action:2}})']);
												else
													return false;
											},
													
								'delete' =>  function($url, $model, $key) use ( $consulta )
											{
												if ( $consulta == 0 || $consulta == 3 )
													return Html::Button('<span class="glyphicon glyphicon-trash"></span>', [
															'class'=>'bt-buscar-label',
															'style'=>'color:#337ab7',
															'onclick'=>'$.pjax.reload({container:"#manejadorRubro",method:"POST",replace:false,push:false,data:{id_rubro:"r'.$model["rubro_id"].'",action:3}})']);
												else
													return false;
											},
							]
						]	
		        	],
			]);
		
		$session->close();
	Pjax::end(); 
?>
</div>
<!-- FIN Grilla Rubros -->

</div>
<!-- FIN DIV FORM -->


<!-- INICIO Botones -->
<?php

if ($consulta != 1)
{

	echo Html::button('Aceptar',[
			'id' => 'fiscaliza_btAceptar',
			'class' => 'btn btn-success',
			'onclick' => 'grabarDatos()',
			
		]);

	echo '&nbsp;&nbsp;&nbsp;';
	
	echo Html::a('Cancelar',['view', 'id' => $model->fisca_id, 'elarr' => 1, 'reiniciar' => 1],[
			'id' => 'fiscaliza_btCAncelar',
			'class' => 'btn btn-primary',
		]);
}

?>
<!-- FIN Botones -->

<!-- INICIO DIV ERRORES -->
<div id="fiscaliza_errorSummary" class="error-summary" style="display:none;margin-top: 8px;margin-right: 15px">

	<ul>
	</ul>

</div>
<!-- FIN DIV ERRORES -->

</div>
<?php
	
	ActiveForm::end();
	
	
	/*
	 * Si $consulta = 1 ó $consulta = 2 => Deshabilitar toda la vista
	 * 
	 */
	 if ($consulta == 1 || $consulta == 2)
	 {
	 	echo "<script>DesactivarFormPost('frmFiscaliza');</script>";	//Deshabilito el formulario
	 	echo "<script>$('#fiscaliza_btAgregaComercio').attr('disabled','disabled');</script>";	//Deshabilito el botón "Agregar Comercio"
	 	echo "<script>$('#GrillaRubrosFiscaliza').attr('class','disabled');</script>";	//Deshabilito la grilla de "Rubros"
	 }
	 
	 Pjax::begin(['id' => 'manejadorRubro', 'enableReplaceState' => false, 'enablePushState' => false]);
			
		$id = Yii::$app->request->post('id_rubro','');
		$action = Yii::$app->request->post('action',0);

		$rubro = $model->cargarUnicoRubro($id); //Cargo en la variable $rubro un objeto ComerRubro con los datos del rubro seleccionado.
		
		switch ($action)
		{
			case 0:
			case 1:
				
				$titulo = 'Nuevo Rubro';
				break;
			
			case 2:
			
				$titulo = 'Modificar Rubro';
				break;
				
			case 3:
				
				$titulo = 'Eliminar Rubro';
				break;
				
		}
		
		 //INICIO Ventana Modal para "Rubros"
		 Modal::begin([
			'id' => 'ModalRubro',
			'header'=> '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">'.$titulo.'</h2>',
			'toggleButton' => false,
			
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			]
		]);
		
			echo $this->render('rubros', ['rubro' => $rubro, 'action' => intval($action)]);
		
		Modal::end();
		//FIN Ventana Modal para "Rubros"
		 
	Pjax::end();
	
	//INICIO Bloque de código para mostrar modal "Copiar CC - Mover DJ"
	Pjax::begin(['id' => 'manejadorMovimiento', 'enableReplaceState' => false, 'enablePushState' => false]);
			
		$action = Yii::$app->request->get('actionMovimiento',0);

		switch ($action)
		{
			case 0:
			case 1:
				
				$titulo = 'Copiar CC';
				break;
			
			case 2:
			
				$titulo = 'Mover DJ';
				break;
		}
		
		 //INICIO Ventana Modal para "Copiar CC - Movimientos DDJJ"
		 Modal::begin([
			'id' => 'ModalMovimientos',
			'header'=> '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">'.$titulo.'</h2>',
			'toggleButton' => false,
			
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			]
		]);
		
			echo $this->render('movimientoCCDDJJ', ['model' => $model, 'action' => intval($action)]);
		
		Modal::end();
		//FIN Ventana Modal para "Copiar CC - Movimientos DDJJ"
		 
	Pjax::end();
	//FIN Bloque de código para mostrar modal "Copiar CC - Mover DJ"
	
	//INICIO Bloque de código que se ejecuta cuando se acepta "Copiar CC"
	Pjax::begin(['id' => 'PjaxCopiarCC']);
		
		$ejecutar = Yii::$app->request->post('ejecutarCC',0);
		
		if ($ejecutar != 0)
		{
			$model = new Fiscaliza();
			
			$datos = Yii::$app->request->post('datos',[]);
			
			//Copiar CC
			$res = $model->fiscalizaCopiarCtaCte($datos['trib_id'],$datos['obj_id'],$datos['perdesde'],$datos['perhasta']);
			
			if ($res['return'] == 1)
			{
				echo Html::a('',['view', 'men' => 1, 'id' => $datos['fisca_id']],['id' => 'a']);
				echo '<script>$("#a").click();</script>;';
				
			} else 
			{
				?>
					
					<script>
						mostrarErrores( ["No se pudo copiar la CC."], "#fiscaliza_errorSummary" );
					</script>
				
				<?php	
			}
		}	
		
	Pjax::end();
	//FIN Bloque de código que se ejecuta cuando se acepta "Copiar CC"
	
	//INICIO Bloque de código que se ejecuta cuando se acepta "Mover DJ"
	Pjax::begin(['id' => 'PjaxMoverDJ']);
	
		$ejecutar = Yii::$app->request->post('ejecutarDJ',0);
		
		if ($ejecutar != 0)
		{
			$model = new Fiscaliza();
			
			$datos = Yii::$app->request->post('datos',[]);
			
			//Mover DJ
			$res = $model->fiscalizaMoverDJ($datos['trib_id'],$datos['obj_id'],$datos['perdesde'],$datos['perhasta']);
			
			if ($res['return'] == 1)
			{
				echo Html::a('',['view', 'men' => 1, 'id' => $datos['fisca_id']],['id' => 'a']);
				echo '<script>$("#a").click();</script>;';
				
			} else 
			{
				?>
					
					<script>
						mostrarErrores( ["No se pudieron mover las DDJJ."], "#fiscaliza_errorSummary" );
					</script>
				
				<?php	
			}
		}
		
	Pjax::end();	
	//INICIO Bloque de código que se ejecuta cuando se acepta "Mover DJ"
?>
<script>
function grabarDatos()
{
	var expe = $("#fiscaliza_txExpe").val(),
		comer = $("#fiscaliza_txComercioID").val(),
		obs = $("#fiscaliza_txObs").val(),
		error = new Array();
	
	if (expe == '')
		error.push( "Ingrese un expediente." );
		
	if (comer == '')
		error.push( "Ingrese un comercio." );
	
	if (error == '')
		$("#frmFiscaliza").submit();
	else
		mostrarErrores( error, "#fiscaliza_errorSummary" );
}

$("#manejadorRubro").on("pjax:end",function() {
	
	$("#ModalRubro").modal("show");
});
</script>