<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\Session;
use app\models\caja\CajaTicket;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;

$title = 'Listado Registro de Pagos Anteriores';
$this->params['breadcrumbs'][] = 'Resultado';
		
Pjax::begin(['id'=>'actBusquedaObjeto']);
	
	$datos = Yii::$app->request->post('listado_datos',[]);
	$obj_nom= '';
	
	if ( count( $datos ) > 0 )
	{
		$tobj = $datos['tobj'];
		$obj_id = $datos['obj_id'];
		
	} else 
	{
		$tobj = 0;
		$obj_id = '';
		$obj_nom = '';
	}
	
	if ( $tobj != 0 )
	{
		//Para actualizar el código de objeto
			
		if ( $obj_id != "" && strlen($obj_id) < 8 )
		{
			$obj_id = utb::GetObjeto( (int)$tobj, $obj_id );
		}
		
		//Verificar la existencia del objeto
		if ( utb::verificarExistenciaObjeto( (int)$tobj, "'" . $obj_id . "'" ) == 0 )
			$obj_id = "";
		
		//Obtengo el nombre del objeto
		$obj_nom = utb::getNombObj("'".$obj_id."'");
		
	}
	
	echo '<script>$("#pagoAnt-txObjetoID").val("' . $obj_id . '")</script>';
	echo '<script>$("#pagoAnt-txObjetoNom").val("' . $obj_nom . '")</script>';
				
	
	//INICIO Modal Busca Objeto Origen
	Modal::begin([
        'id' => 'BuscaObjRegPagoAnt_btBuscaObj',
        'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
        'closeButton' => [
          'label' => '<b>X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ],
    ]);
    
        echo $this->render('//objeto/objetobuscarav', [
				'id' => 'RegPagoAnt',
				'txCod' => 'formPagoAnt-txObjetoID',
				'txNom' => 'formPagoAnt-txObjetoNom',
				'txDoc' => 'doc_origen',
				'selectorModal' => '#BuscaObjRegPagoAnt',
				'tobjeto' => $tobj,
			]);
        
    Modal::end();
	//FIN Modal Busca Objeto Origen
					
Pjax::end();

?>

<style>
#div_grilla {
	
	margin-top:10px;
	padding-right: 8px;
	padding-bottom: 8px;
	display:none;
}

</style>

		<table width="100%">
			<tr>
				<td><h1><?= Html::encode($title) ?></h1></td>
				<td align="right">
					<?= Html::a('Imprimir', ['//site/pdflist', 'format' => 'A4-L'], [
							'id' => 'pagoAnt_listado_btImprimir',
							'class' => 'btn btn-success disabled',
							'target' => '_black',
						]);
					?>
			    	<?php
						Modal::begin([
				            'id' => 'Exportar', 
							'header' => '<h2>Exportar Datos</h2>',
							'toggleButton' => [
								'id' => 'pagoAnt_listado_btExportar',
				                'label' => 'Exportar',
				                'class' => 'btn btn-success disabled',
				                
				            ],
				            'closeButton' => [
				              'label' => '<b>X</b>',
				              'class' => 'btn btn-danger btn-sm pull-right',
				            ]
				        ]);
				        
				        	echo $this->render('//site/exportar',['titulo'=>'Listado de Pagos Anteriores','desc'=>'','grilla'=>'Exportar']);
				        	
				        Modal::end();
			        ?>
					<?= Html::a('Volver', ['pagoant', 'consulta'=>1],['class' => 'btn btn-primary']); ?>
				</td>
			</tr>
							
		</table>

		
<div class="form" style="padding-bottom: 8px">

<table>
	<tr>
		<td width="100px"><?= Html::checkbox('ckTributo',false,['id'=>'ckTributo','label'=>'Tributo:','onchange'=>'validarCheck()']) ?></td>
		<td colspan="5">
			<?= Html::dropDownList('pagoAnt-tributo', $tributo, utb::getAux('trib','trib_id','nombre',3,"est='A' and trib_id<>6"), [
					'id'=>'pagoAnt-tributo',
					'style'=>'width:245px',
					'class' => 'form-control',
				]);
			?>
		</td>
	</tr>
	<tr>
		<td width="100px"><?= Html::checkbox('ckObjeto',false,['id'=>'ckObjeto','label'=>'Objeto:','onchange'=>'validarCheck()']) ?></td>
		<td colspan="2">
			<?= Html::dropDownList('pagoAnt-dlObjeto', $objeto, utb::getAux('objeto_tipo','cod','nombre',3,"est='A'"), [
					'id'=>'pagoAnt-dlObjeto',
					'style'=>'width:120px',
					'class' => 'form-control',
					'onchange'=>'filtraObjeto();validarCheck()',
					'disabled' => true
				]);
			?>
		</td>
		<td width="5px"></td>
		<td>
			<!-- INICIO Botón Búsqueda Objeto -->
			<?= Html::Button('<i class="glyphicon glyphicon-search"></i>',[
					'class'=>'bt-buscar',
					'disabled' => true,
					'id'=>'pagoAnt-btObjetoID',
					'onclick'=>'$("#BuscaObjRegPagoAnt_btBuscaObj, .window").modal("show")',
				]);
			?>						
			<!-- FIN Botón Búsqueda Objeto-->
		</td>
		<td width="60px">
			<?= Html::input('text','pagoAnt-txObjetoID',null,[
					'id'=>'pagoAnt-txObjetoID',
					'disabled' => true,
					'class'=>'form-control',
					'style'=>'width:80px',
					//'onchange'=>'filtraObjeto()',
				]);
			?>
		</td>
		<td width="60px">
			<?= Html::input('text','pagoAnt-txObjetoNom',null,[
					'id'=>'pagoAnt-txObjetoNom',
					'class'=>'form-control',
					'disabled' => true,
					'style'=>'width:280px;',
				]);
			?>
		</td>
	</tr>

	<tr>
		<td width="100px"><?= Html::checkbox('ckFecha',false,['id'=>'ckFecha','label'=>'Fecha Pago:','onchange'=>'validarCheck()']) ?></td>
		<td><label>Desde&nbsp;</label></td>
		<td width="80px"><?= 
					DatePicker::widget(
						[
							'id' => 'pagoAnt-txFechaDesde',
							'name' => 'txFechaDesde',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['style' => 'width:80px','class' => 'form-control', 'diabled' => true],
							'value'=> Fecha::usuarioToDatePicker( Fecha::getDiaActual() )
						]
					);
				?></td>
		<td width="5px"></td>
		<td><label>Hasta&nbsp;</label></td>
		<td width="80px"><?= 
					DatePicker::widget(
						[
							'id' => 'pagoAnt-txFechaHasta',
							'name' => 'txFechaDesde',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['style' => 'width:80px','class' => 'form-control','disabled' => true],
							'value'=> Fecha::usuarioToDatePicker( Fecha::getDiaActual() )
						]
					);
				?></td>
	</tr>
	<tr>
		<td width="100px"><?= Html::checkbox('ckFechaCarga',false,['id'=>'ckFechaCarga','label'=>'Fecha Carga','onchange'=>'validarCheck()']) ?></td>
		<td><label>Desde&nbsp;</label></td>
		<td width="80px"><?= 
					DatePicker::widget(
						[
							'id' => 'pagoAnt-txFechaCargaDesde',
							'name' => 'txFechaCargaDesde',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['style' => 'width:80px','class' => 'form-control', 'disabled' => true],
							'value'=> Fecha::usuarioToDatePicker( Fecha::getDiaActual() )
						]
					);
				?></td>
		<td></td>
		<td><label>Hasta&nbsp;</label></td>
		<td width="80px"><?= 
					DatePicker::widget(
						[
							'id' => 'pagoAnt-txFechaCargaHasta',
							'name' => 'txFechaCargaHasta',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => ['style' => 'width:80px','class' => 'form-control', 'disabled' => true],
							'value'=> Fecha::usuarioToDatePicker( Fecha::getDiaActual() ),
						]
					);
				?></td>
	</tr>
</table>

</div>

<div style="margin-top: 8px; margin-bottom: 8px">
	<?= Html::button('Aceptar',['id'=>'bt-BuscaDatos','onclick'=>'buscarDatos()', 'class'=>'btn btn-success']); ?>
</div>

<div id="div_grilla" class="form">

<h3><b>Resultados:</b></h3>
<?php

	Pjax::begin(['id'=>'grilla_regpagoant']);

		$cond = Yii::$app->request->post( 'condicion', '' );		
		if ( $cond !== '' )
		{
			$dataprovider = $model->listarPagosOld($cond);
			
			//Si la cantidad de elementos obtenidos es 0, deshabilitar los botones de imprimir y exportar
			if ( count( $dataprovider->getModels() ) == 0 )
			{
				echo '<script>$("#pagoAnt_listado_btImprimir").addClass("disabled");</script>';
				echo '<script>$("#pagoAnt_listado_btExportar").addClass("disabled");</script>';
				
				//Limpio los datos en sesión
				Yii::$app->session['titulo'] = "";
				Yii::$app->session['condicion'] = ''; 
				Yii::$app->session['sql'] = "";				
				Yii::$app->session['columns'] = [];
		        Yii::$app->session['proceso_asig'] = 0;
			
			} else 
			{
				Yii::$app->session['titulo'] = "Listado de Pagos anteriores";
				Yii::$app->session['condicion'] = ''; 
				Yii::$app->session['sql'] = " select c.pago_id, c.obj_id,o.nombre obj_nom,t.nombre trib_nom,c.anio,c.cuota,c.fchpago,c.comprob,u.nombre || ' - ' || to_char(c.fchmod,'dd/mm/yyyy') modif from caja_pagoold c " .
						'inner join objeto o on o.obj_id = c.obj_id ' .
						'inner join Trib t on t.trib_id = c.trib_id ' .
						'LEFT JOIN sam.sis_usuario u ON c.usrmod = u.usr_id'.($cond != '' ? ' where '.$cond : ''); 
				
				Yii::$app->session['columns'] = [
					['attribute'=>'pago_id','label' =>'Cod'],
		            ['attribute'=>'obj_id','label' => 'Objeto'],
		            ['attribute'=>'obj_nom','label' => 'Nombre'],
		            ['attribute'=>'trib_nom','label' => 'Tributo'],
		            ['attribute'=>'anio','label' => 'Año'],
		            ['attribute'=>'cuota','label' => 'Cuota'],
	                ['attribute'=>'fchpago','label' => 'Fch.Pao'],
	                ['attribute'=>'comprob','label' => 'Comprob.'],
	                ['attribute'=>'modif','label' => 'Modif.'],	
		    		    
		        ];
		        Yii::$app->session['proceso_asig'] = 3418;
			} 
		
			 echo GridView::widget([
							    'dataProvider' => $dataprovider,
							    'headerRowOptions' => ['class' => 'grilla'],
								'rowOptions' => ['class' => 'grilla'],
							    'columns' => [
						            ['attribute'=>'pago_id','header' =>'Cod'],
						            ['attribute'=>'obj_id','header' => 'Objeto'],
						            ['attribute'=>'obj_nom','header' => 'Nombre'],
						            ['attribute'=>'trib_nom','header' => 'Tribuo'],
						            ['attribute'=>'anio','header' => 'Año'],
						            ['attribute'=>'cuota','header' => 'Cuota'],
					                ['attribute'=>'fchpago','header' => 'Fch.Pao'],
					                ['attribute'=>'comprob','header' => 'Comprob.'],
					                ['attribute'=>'modif','header' => 'Modif.'],	
									[
										'class' => 'yii\grid\ActionColumn',
										'contentOptions'=>['style'=>'width:6px'],
										'template' =>'{pagoant}',
										'buttons'=>[			
											'pagoant' =>  function($url, $model, $key)
														{
															return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
															
														},
										],
									]
									]]);
		}
		
	Pjax::end();
						
?>
</div>


<div id="pagoAnt_listado_errorSummary" class="error-summary" style="display:none;margin-top:10px">

<ul>

</ul>

</div>

			
<script>
//
//function mostrarError( error )
//{
//	var $contenedor= $("#pagoAnt_listado_errorSummary"),
//		$lista= $("#pagoAnt_listado_errorSummary ul");
//	
//	$lista.empty();
//	
//	$contenedor.css("display", "block");
//	
//	for (e in error)
//	{
//		$el= $("<li />");
//		$el.text(error[e]);
//		$el.appendTo($lista);
//	}
//}

function validarCheck()
{
	var ckTributo = $("#ckTributo").is(":checked"),
		ckObjeto = $("#ckObjeto").is(":checked"),
		ckFecha = $("#ckFecha").is(":checked"),
		ckFechaCarga = $("#ckFechaCarga").is(":checked"),
		dlTObj = $("#pagoAnt-dlObjeto").val();
		
	
	$("#pagoAnt-tributo").toggleClass('read-only',!ckTributo);
		
	$("#pagoAnt-dlObjeto").prop("disabled",!ckObjeto);
	$("#pagoAnt-txObjetoID").prop("disabled",!ckObjeto || dlTObj == 0);
	$("#pagoAnt-btObjetoID").prop("disabled",!ckObjeto || dlTObj == 0);
	
	if ( !ckObjeto )
	{
		$("#pagoAnt-dlObjeto").val( 0 );
		$("#pagoAnt-txObjetoID").val( "" );
		$("#pagoAnt-txObjetoNom").val( "" );
	}
	
	
	$("#pagoAnt-txFechaDesde").prop("disabled",!ckFecha);
	$("#pagoAnt-txFechaHasta").prop("disabled",!ckFecha);

	$("#pagoAnt-txFechaCargaDesde").prop("disabled",!ckFechaCarga);
	$("#pagoAnt-txFechaCargaHasta").prop("disabled",!ckFechaCarga);
}


/* Función que actualiza los datos del filtro de Objeto */
function filtraObjeto()
{
	var objeto = $("#pagoAnt-dlObjeto").val(),
		cod_obj = $("#pagoAnt-txObjetoID").val(),
		datos = {};
	
	datos.tobj =  	objeto;
	datos.obj_id = 	cod_obj;
	
	$.pjax.reload({
		container:"#actBusquedaObjeto",
		method:"POST",
		data:{
			
			listado_datos: datos,			
		}
	});
}

function buscarDatos()
{
	var datos = {},
		objeto = $("#pagoAnt-dlObjeto").val(),
		cod_obj = $("#pagoAnt-txObjetoID").val();
	
	datos.tobj =  	objeto;
	datos.obj_id = 	cod_obj;
	
	$.pjax.reload({
		container:"#actBusquedaObjeto",
		method:"POST",
		data:{
			
			listado_datos: datos,			
		}
	});
	
	$("#actBusquedaObjeto").on("pjax:end",function() {
			
		var cond = '',
			error = new Array(),
			objeto = $("#pagoAnt-dlObjeto").val(),
			tributo = $("#pagoAnt-tributo").val(),
			tobj = $("#pagoAnt-dlObjeto").val(),
			cod_obj = $("#pagoAnt-txObjetoID").val(),
			fechaCargaDesde = $("#pagoAnt-txFechaCargaDesde").val(),
			fechaCargaHasta = $("#pagoAnt-txFechaCargaHasta").val(),
			fechaDesde = $("#pagoAnt-txFechaDesde").val(),
			fechaHasta = $("#pagoAnt-txFechaHasta").val();
				
		
		if ($("#ckTributo").is(":checked"))
		{
			if ( tributo == 0 )
				error.push( "Ingrese un tributo." );
			else
				cond += "c.trib_id = " + tributo;
						
		} 
	
		if ($("#ckObjeto").is(":checked"))
		{
			if ( tobj == 0 )
				error.push( "Ingrese un tipo de objeto." );
			
			if (cod_obj.length != 8)
				error.push( "Ingrese un objeto." );
			else 
			{
				if (cond != '')
					cond += " AND ";
				
				cond += "c.obj_id = '"+cod_obj+"'";
				
			}
		}
		
		if ($("#ckFechaCarga").is(":checked"))
		{
			if (ValidarRangoFechaJs(fechaCargaDesde,fechaCargaHasta) == 0)
			{
				if (cond != '')
					cond += " AND ";
				
				cond += "c.fchmod::date between '"+fechaCargaDesde+"' AND '"+fechaCargaHasta+"'";
				
			} else 
				error.push( "Ingrese una fecha de carga válida." );
	
		} 
		
		if ($("#ckFecha").is(":checked"))
		{
			if (ValidarRangoFechaJs(fechaDesde,fechaHasta) == 0)
			{
				if (cond != '')
					cond += " AND ";
				
				cond += "c.fchpago::date between '"+fechaDesde+"' AND '"+fechaHasta+"'";
				
			} else 
				error.push( "Ingrese una fecha válida." );
	
		} 
		
		if(cond == '' && error.length == 0) error.push("No se encontraron condiciones de búsqueda.");
		
		if ( error.length > 0 )
		{
			//Oculto la grilla
			$("#div_grilla").css("display","none");
			
			//Deshabilito los botones de "Imprimir" y "Exportar"
			$("#pagoAnt_listado_btImprimir").addClass("disabled");
			$("#pagoAnt_listado_btExportar").addClass("disabled");
			
			mostrarErrores( error, $("#pagoAnt_listado_errorSummary") );
		}
		
		if (cond != '')
		{
			//Muestro la grilla
			$("#div_grilla").css("display","block");
			
			//Oculto el div de errores en caso de haber ocurrido alguno
			$("#pagoAnt_listado_errorSummary").css("display","none");
			
			//Activo los botones de "Imprimir" y "Exportar"
			$("#pagoAnt_listado_btImprimir").removeClass("disabled");
			$("#pagoAnt_listado_btExportar").removeClass("disabled");
			
			$.pjax.reload({
				container:"#grilla_regpagoant",
				method:"POST",
				data:{
					condicion:cond
				}
			});
		}
			
		
		$("#actBusquedaObjeto").off("pjax:end");
	})
	
		
}

$(document).ready(function() {
	
	validarCheck();
	
});
</script>
			