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

/**
 * Forma que se dibuja cuando se llega a Débito Automático
 * Recibo:
 * 			=> $model -> Modelo de Débito
 */
 
/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */

//INICIO Bloque actualiza los códigos de objeto
Pjax::begin(['id' => 'PjaxObjNombre']);
	$trib = Yii::$app->request->post('trib',0);
	$tobj = Yii::$app->request->post('tobj',utb::GetTObj($model->obj_id));
	if ( $tobj == "" ) $tobj = utb::getTObjTrib($trib);
	$objeto_id = Yii::$app->request->post('pjaxobj',$model->obj_id);
    
	echo '<script>$("#debito_txObjetoID").val("")</script>';
	
	if (strlen($objeto_id) < 8)
	{
		$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
		echo '<script>$("#debito_txObjetoID").val("'.$objeto_id.'")</script>';
	}
	
	if (utb::GetTObj($objeto_id) == $tobj)
	{
		$objeto_nom = utb::getNombObj("'".$objeto_id."'");
		echo '<script>$("#debito_txObjetoID").val("'.$objeto_id.'")</script>';
		echo '<script>$("#debito_txObjetoNom").val("'.$objeto_nom.'")</script>';	
			
	} else 
	{
		echo '<script>$("#debito_txObjetoID").val("")</script>';
		echo '<script>$("#debito_txObjetoNom ").val("")</script>';
	}
Pjax::end();

Pjax::begin(['id' => 'PjaxNumNombre']);
	$num = Yii::$app->request->post('pjaxnum',"");
    
	echo '<script>$("#debito_txNum").val("")</script>';
	
	if (strlen($num) < 8)
	{
		$num = utb::GetObjeto(3,(int)$num);
		echo '<script>$("#debito_txNum").val("'.$num.'")</script>';
	}
	
	if (utb::GetTObj($num) == 3)
	{
		$num_nom = utb::getNombObj("'".$num."'");
		echo '<script>$("#debito_txNum").val("'.$num.'")</script>';
		echo '<script>$("#debito_txNumNom").val("'.$num_nom.'")</script>';	
			
	} else 
	{
		echo '<script>$("#debito_txNum").val("")</script>';
		echo '<script>$("#debito_txNumNom ").val("")</script>';
	}
Pjax::end();

Pjax::begin(['id' => 'pjaxTributo', 'enableReplaceState' => false, 'enablePushState' => false]);

	$trib_id = Yii::$app->request->post('trib_id',0);
	
	if($trib_id > 0){
		
		$tobj= utb::getTObjTrib($trib_id);
		$tobjNombre= utb::getCampo('objeto_tipo', "cod = $tobj", 'nombre');
		
		?>
		<script type="text/javascript">
		$(document).ready(function(){
			
			$("#debito_TObj").val("<?= $tobjNombre; ?>");
		});
		</script>
		<?php
	}

	//INICIO Modal Busca Objeto
	Modal::begin([
		'id' => 'BuscaObj',
		'size' => 'modal-lg',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
		'closeButton' => [
		  'label' => '<b>&times</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		],
		 ]);
									
		echo $this->render('//objeto/objetobuscarav',[
									'id' => 'debito_altaBuscar', 'txCod' => 'debito_txObjetoID', 'txNom' => 'debito_txObjetoNom', 'selectorModal' => '#BuscaObj', 'tobjeto' => (int)$tobj
				        		]);
		
	Modal::end();
	//FIN Modal Busca Objeto
	
Pjax::end();
//FIN Bloque actualiza los códigos de objeto


//INICIO Modal Busca Persona
Modal::begin([
	'id' => 'BuscaNum',
	'size' => 'modal-lg',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Persona</h2>',
	'closeButton' => [
	  'label' => '<b>&times</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
	 ]);
								
	echo $this->render('//objeto/objetobuscarav',[
								'id' => 'debito_BuscarPersona', 'txCod' => 'debito_txNum', 'txNom' => 'debito_txNumNom', 'selectorModal' => '#BuscaNum', 'tobjeto' => 3
							]);
	
Modal::end();
//FIN Modal Busca Persona
?>


<style>
#ModalGenerarDeb .modal-content
{
    width: 60%;
    margin-left: 20%;
}
</style>


<div class="debito_info">


<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 

			Pjax::begin(['id'=>'errorDebito']);
			
				$mensaje = Yii::$app->request->post('mensaje','');
				$m = Yii::$app->request->post('m',2);
			
				if($mensaje != "")
				{ 
			
			    	Alert::begin([
			    		'id' => 'AlertaMensajeDebito',
						'options' => [
			        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
			        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
			    		],
					]);
			
					echo $mensaje;
							
					Alert::end();
					
					echo "<script>window.setTimeout(function() { $('#AlertaMensajeDebito').alert('close'); }, 5000);</script>";
				 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<div class="form-panel" style="padding-right:8px">
<table width="100%">
	<tr>
		<td width="80px"><label>Caja:</label></td>
		<td width="150px">
			<?= Html::dropDownList('dlDebito',null,utb::getAux('caja','caja_id','nombre',2,"tipo IN (3,4,5) AND est='A'"),[
					'id'=>'debito_dlDebito',
					'class'=>'form-control',
					'style'=>'width:100%;',
					'onchange'=>'activaDebitoAutomatico();$.pjax.reload({container:"#PjaxModalGen",method:"POST",data:{caja:$(this).val()}})',
				]);
			?>
		</td>
		<td width="15px"></td>
		<td width="50px"><label>Tributo:</label></td>
		<td colspan="3">			
			<?= Html::dropDownList('dlTrib', null, utb::getAux('trib','trib_id','nombre',2,"tipo = 1"), 
			[	'style' => 'width:200px',
				'class' => 'form-control',
				'id'=>'debito_dlTrib',
				'onchange' => 'f_cambiaTributo()',
			]); ?>
		</td>
	</tr>
	<tr>
		<td width="80px"><label>Tipo Objeto:</label></td>
		<td>
			<?= Html::textInput(null, utb::getTObjNom($model->obj_id), ['id' => 'debito_TObj', 'class' => 'form-control solo-lectura', 'tabindex' => -1]); ?>
			
		</td>
		<td width="15px"></td>
		<td width="50px"><label>Objeto:</label></td>
		<td><?= Html::input('text','txObjetoID',$model->obj_id,['id'=>'debito_txObjetoID','class'=>'form-control','style'=>'width:80px;text-align:center', 'disabled' => true,
							'onchange'=>'f_cambiaObjeto()']); ?>
		</td>
		<td>
		<!-- botón de búsqueda modal -->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
					'class' => 'bt-buscar',
					'id'=>'debito_btBuscaObj',
					'onclick' => '$("#BuscaObj").modal("show");',
					'disabled' => true
				]);
			?>
		
		<!-- fin de botón de búsqueda modal -->
		</td>
		<td width="250px" colspan="2"><?= Html::input('text','txObjetoNom',null,['id'=>'debito_txObjetoNom','class'=>'form-control','style'=>'width:100%;text-align:left','readOnly'=>true]) ?></td>	
			
	</tr>
	<tr>
		<td width="80px"><label>Contribuyente:</label></td>
		<td colspan='7'>
			<?= Html::input('text','txNum',null,['id'=>'debito_txNum','class'=>'form-control','style'=>'width:80px;text-align:center', 
							'onchange'=>'f_cambiaObjetoNum()']); ?>
		
			<!-- botón de búsqueda modal -->
			<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
					'class' => 'bt-buscar',
					'id'=>'debito_btBuscaNum',
					'onclick' => '$("#BuscaNum").modal("show");'
				]);
			?>
		
			<!-- fin de botón de búsqueda modal -->
			<?= Html::input('text','txNumNom',null,['id'=>'debito_txNumNom','class'=>'form-control solo-lectura','style'=>'width:60%;text-align:left']) ?>
		</td>	
			
	</tr>
</table>	
	
<table>
	<tr>
		<td><?= Html::checkbox('ckIncluirBaja',true,['id'=>'debito_ckIncluirBaja','label'=>'Incluir Bajas y Convenios no Vigentes'])?></td>
	</tr>
</table>		

<table>
	<tr>
		<td width="35px"><label>Año:</label></td>
		<td><?= Html::input('text','txAnio',null,['id'=>'debito_txAnio','class'=>'form-control','style'=>'width:40px;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>4]) ?></td>	
		<td width="20px"></td>
		<td width="35px"><label>Mes:</label></td>
		<td><?= Html::input('text','txMes',null,['id'=>'debito_txMes','class'=>'form-control','style'=>'width:30px;text-align:center','onkeypress'=>'return justNumbers(event)','maxlength'=>2]) ?></td>
	</tr>
</table>
</div>

<div style="padding-right:15px">
<?php

$tab = 0;

//INICIO Tab con Adhesiones y Convenios
Pjax::begin(['id' => 'pjaxDebito_Tab']);
	
	$dataProviderAdhe = new ArrayDataProvider(['allModels' => []]);	//Creo un dataProvider para adhesiones vacío.
	$dataProviderConv = new ArrayDataProvider(['allModels' => []]); //Creo un dataProvider para convenios vacío.

	if (isset($_POST['caja']) && $_POST['caja'] != '')	//Obtengo los datos que viajan por POST.
	{
		$caja = Yii::$app->request->post('caja',0);
		$trib_id = Yii::$app->request->post('trib',0);
		$obj_id = Yii::$app->request->post('obj_id','');
		$num = Yii::$app->request->post('num','');
		$baja = Yii::$app->request->post('baja',1);
		$anio = Yii::$app->request->post('anio',0);
		$mes = Yii::$app->request->post('mes',0);
		
		$dataProviderAdhe = $model->listarAdhe($caja,$trib_id,$obj_id,$anio,$mes,$baja,$num);	//Cargo los datos de adhesiones en el dataProvider
		$dataProviderConv = $model->listarPlanes($caja,$obj_id,$anio,$mes,$baja,$num);	//Cargo los datos de convenios en el dataProvider.
		
		//Verificar que se hayan encontrado datos
		if ( !( count( $dataProviderAdhe->getmodels() ) > 0 || count( $dataProviderConv->getmodels()) > 0 ) )
		{
			//Mostrar mensaje
			echo '<script>mostrarError( "<li>No se encontraron datos.</li>" );</script>';
		}
	}
	
	//Dibujo un TAB con Adhesiones y Convenio
	echo Tabs :: widget ([
	    
	    	 	'id' => 'TabAdheConv',
				'items' => [ 
	 				['label' => 'Adhesiones', 
	 				'content' => $this->render('adhesiones', ['dataProvider' => $dataProviderAdhe,]),
	 				'active' => ($tab==0) ?  true : false,
	 				'options' => ['class'=>'tabItem'],			
	 				],
	 				['label' => 'Convenios' , 
	 				'content' => $this->render('convenios', ['dataProvider' => $dataProviderConv,]),
	 				'active' => ($tab==1) ?  true : false,
	 				'options' => ['class'=>'tabItem']
	 				]
	 			]]);

Pjax::end();
//FIN Tab con Adhesiones y Convenios

//INICIO Modal generar Deb
Modal::begin([
		'id' => 'ModalGenerarDeb',
		'size' => 'modal-normal',
		'header' => '<h4><b>Generar Débito Automático</b></h4>',
		'closeButton' => [
			'label' => '<b>X</b>',
			'class' => 'btn btn-danger btn-sm pull-right',
			'id' => 'btCancelarModalElim'
			],
	]);
								
	echo $this->render('generarDeb',['model' => $model]);
	
Modal::end();
//FIN Modal generar Deb

?>
</div>


</div>

<script>
function f_cambiaObjeto()
{
	$.pjax.reload({
		container:"#PjaxObjNombre",
		method:"POST",
		data:{	
			pjaxobj:$("#debito_txObjetoID").val(),
			trib: $("#debito_dlTrib").val()
		}
	});
}

function f_cambiaObjetoNum()
{
	$.pjax.reload({
		container:"#PjaxNumNombre",
		method:"POST",
		data:{	
			pjaxnum:$("#debito_txNum").val()
		}
	});
}

function buscar()
{
	var inclBaja = 0,
		caja = $("#debito_dlDebito").val(),
		trib = $("#debito_dlTrib").val(),
		tobj = $("#debito_dlTObj").val(),
		objID = $("#debito_txObjetoID").val(),
		objNom = $("#debito_txObjetoNom").val(),
		numID = $("#debito_txNum").val(),
		numNom = $("#debito_txNumNom").val(),
		anio = $("#debito_txAnio").val(),
		mes = $("#debito_txMes").val(),
		error = "";
		
	if ($("#debito_ckIncluirBaja").is(":checked"))
		inclBaja = 1;
		
	/* Caja no puede ser vacío */
	/*if (caja == '' || caja == 0)
		error += "<li>Ingrese una caja.</li>";*/
	
	/* Cuando se filtra por año y por mes, se debe ingresar un tributo */	
	if ((anio != '' || mes != '') && (trib == '' || trib == 0))
		error += "<li>Para filtrar por año y por mes, es necesario que se indique un tributo.</li>";
	
	/* Cuando se filtra por objeto */
	if (objID != '' && objNom == '')
		error = "<li>Ingrese un objeto válido.</li>";
		
	/* Cuando se filtra por objeto */
	if (numID != '' && numNom == '')
		error = "<li>Ingrese un contribuyente válido.</li>";	
		
	
	if (error == "")
	{
		$.pjax.reload({
			container:"#pjaxDebito_Tab",
			method:"POST",
			data:{	
				caja:caja,
				trib:trib,
				obj_id:objID,
				num:numID,
				baja:inclBaja,
				anio:anio,
				mes:mes,
			}
		});
	} else 
	{
		mostrarError( error );
	}
}

function mostrarError( error )
{
	$.pjax.reload({
			container:"#errorDebito",
			method:"POST",
			data:{
				mensaje:error,
			}
		});
}

function f_cambiaTributo()
{
	var trib = $("#debito_dlTrib").val();
	
	<?php if ($model->obj_id == '') { ?>
		$("#debito_TObj").val("");
		$("#debito_txObjetoID").val("");
		$("#debito_txObjetoID").prop("disabled", true);
		$("#debito_btBuscaObj").prop("disabled", true);
		$("#txObjetoNom").val("");
	<?php } ?>
	
	if ( trib == 0 ){
		
		// Limpiar año y fecha
		$("#debito_txAnio").val("");
		$("#debito_txMes").val("");
	} else {
		
		$.pjax.reload({
			container: "#pjaxTributo",
			type: "POST",
			replace: false,
			push: false,
			data: {
				"trib_id": trib
			}
		});
	}
}

$(document).ready(function(){
	
	$("#pjaxTributo").on("pjax:complete", function(){
		
		<?php if ($model->obj_id == '') { ?>
			if($("#debito_TObj").val() != ""){
				
				$("#debito_txObjetoID").prop("disabled", false);
				$("#debito_btBuscaObj").prop("disabled", false);
			}
		<?php } ?>	
	});
	
	<?php 
		if ($model->obj_id != '') {
	?>
			f_cambiaObjeto();
			$("#PjaxObjNombre").on("pjax:complete", function(){
				buscar();
			});
	<?php	
		}
	?>
});

</script>
