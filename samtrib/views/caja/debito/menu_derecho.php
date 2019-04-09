<?php
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\widgets\Pjax;

	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 * 
	 * @param $model
	 */
	 
	 if (!isset($consulta)) $consulta = 1;
	 if (!isset($accion)) $accion = '';
	 if (!isset($id)) $id = '';
						
?>
<style>
#debitoBuscar_btBuscar {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3460)) { ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'> <?= Html::button('<b>Buscar</b>',['class'=>'bt-buscar-label','onclick'=>'buscar()', 'id' => 'debitoBuscar_btBuscar']); ?> </li>
	<li id='liLiquida' class='glyphicon glyphicon-print'> <?= Html::a('<b>Liquidaciones</b>', ['liquidacion','consulta'=>1], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)) { ?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index','obj_id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300) or utb::getExisteProceso(3460)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3462)) { ?>
	<li id='liGenDeb' class='glyphicon glyphicon-comment'> <?= Html::button('<b>Generar Deb</b>',['class'=>'bt-buscar-label','onclick'=>'$("#ModalGenerarDeb").modal("show")', 'id' => 'debitoBuscar_btBuscar']); ?> </li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3460)) { ?>
	<li id='liExpDeb' class='glyphicon glyphicon-retweet'> Exportar Deb.</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3462)) { ?>
	<li id='liImpNov' class='glyphicon glyphicon-paperclip'> Importar Nov.</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3462) or utb::getExisteProceso(3460)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3460)) { ?>
	<li id='liExpAdhe' class='glyphicon glyphicon-calendar'> Exp. Adhe.</li>
	<li id='liExpCon' class='glyphicon glyphicon-home'> Exp. Conv.</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3460)) { ?>
	<li id='liImprimirEnt' class='glyphicon glyphicon-print'> <?= Html::button('<b>Impr. Entidad</b>',['class'=>'bt-buscar-label','onclick'=>'btImprimirEntidadClick()']); ?></li>
	<li id='liGenerarReporte' class='glyphicon glyphicon-print'> <?= Html::a('<b>Generar Reporte</b>', ['generarreporte'], ['class'=>'bt-buscar-label']); ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
</ul>

<?php 

//La variable baja indica si el recibo que se muestra se encuentra dado de baja
if (($id == '' || $id == null) && $consulta == 1)
{
	// dashabilito todas las opciones 
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	
	// y luego solo habilito buscar y liquidación
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar a").css("color", "#337ab7");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liLiquida").css("pointer-events", "all");</script>';
	echo '<script>$("#liLiquida a").css("color", "#337ab7");</script>';
	echo '<script>$("#liLiquida").css("color", "#337ab7");</script>';
	echo '<script>$("#liGenDeb").css("pointer-events", "all");</script>';
	echo '<script>$("#liGenDeb a").css("color", "#337ab7");</script>';
	echo '<script>$("#liGenDeb").css("color", "#337ab7");</script>';
	
	echo '<script>$("#liGenerarReporte").css("pointer-events", "all");</script>';
	echo '<script>$("#liGenerarReporte a").css("color", "#337ab7");</script>';
	echo '<script>$("#liGenerarReporte").css("color", "#337ab7");</script>';
	
} else
{
	if ($consulta != 1)
	{
		// si se esta creado, modificando o eliminando => deshabilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	} else 
	{	// habilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';
	}
}

Pjax::begin(['id'=>'PjaxImpEnt']);
	if (isset($_POST['impent'])){
		$caja = ($_POST['caja'] != '' ? $_POST['caja'] : 0);
		$trib = ($_POST['trib'] != '' ? $_POST['trib'] : 0);
		$anio = ($_POST['anio'] != '' ? $_POST['anio'] : 0);
		$mes = ($_POST['mes'] != '' ? $_POST['mes'] : 0);
		
		$sql = "Select * From v_debito_entidad b Where caja_id=" . $caja;
        if ($trib > 0) $sql .= " and Trib_Id = " . $trib;
        $sql .= " and anio = " . $anio . " and mes = " . $mes . " Order By obj_id";
        
        $desc = '-Caja: '.utb::getCampo('caja','caja_id='.$caja);
        $desc .= '<br>-Tributo: '.($trib > 0 ? utb::getCampo('trib','trib_id='.$trib) : 'Todos');
        $desc .= '<br>-Año: '.$anio.' - Cuota: '.$mes;
        
        $tcaja = utb::getCampo('caja','caja_id='.$caja,'tipo');
        if ($tcaja == 3) { 
		    $dato = "Sucursal y Cuenta";
        }else if ($caja == 4) { 
		    $dato = "Nro Tarjeta";
        }else if ($caja == 5) { 
		    $dato = "Tipo de Empleado - Legajo";
		}
        
        $datos = utb::ArrayGeneralCons($sql);
        $cant = count($datos);
        
        $total = 0;
        for ($i=0; $i<$cant; $i++){
        	$total += $datos[$i]['monto'];
        }
        
        
        Yii::$app->session['titulo'] = "Debitos - Listado de Liquidaión";
		Yii::$app->session['condicion'] = $desc; 
		Yii::$app->session['sql'] = $sql;
		Yii::$app->session['proceso_asig'] = 3320;
		Yii::$app->session['columns'] = [
			['attribute'=>'resp','label' => 'Titular','contentOptions'=>['style'=>'width:250px;text-align:left']],
        	['attribute'=>'resp_ndoc','label' => 'Documento','contentOptions'=>['style'=>'width:80px;']],
        	['attribute'=>'tpago_dato','label' => $dato,'contentOptions'=>['style'=>'width:150px;text-align:left']],
        	['attribute'=>'trib_nom','label' => 'Tributo','contentOptions'=>['style'=>'width:250px']],
        	['attribute'=>'periodo','label' => 'Período','contentOptions'=>['style'=>'width:60px;text-align:center'],
        		'footer' => 'Cant.:','footerOptions'=>['style'=>'border-top:1px solid #000; font-weight:bold;']
        	],
            ['attribute'=>'obj_id','label' => 'Objeto','contentOptions'=>['style'=>'width:60px; text-align:center'],
            	'footer' => $cant,'footerOptions'=>['style'=>'border-top:1px solid #000; font-weight:bold;']
            ],
        	['attribute'=>'subcta','label' => 'Cta.','contentOptions'=>['style'=>'width:40px;text-align:center'],
        		'footer' => 'Total:','footerOptions'=>['style'=>'border-top:1px solid #000; font-weight:bold']
        	],
        	['attribute'=>'monto','label' => 'Monto','contentOptions'=>['style'=>'width:70px; text-align:right'],
        		'footer' => $total,'footerOptions'=>['style'=>'border-top:1px solid #000; font-weight:bold;text-align:right']
        	],
        	
        ];
        
       echo "<script>window.open('index.php?r=site/pdflist', '_blank');</script>"; 
	}
Pjax::end();

?>

<script>
function btImprimirEntidadClick()
{
	$.pjax.reload(
	{
		container:"#PjaxImpEnt",
		data:{
			impent:1,
			caja:$("#debito_dlDebito").val(),
			trib:$("#debito_dlTrib").val(),
			anio:$("#debito_txAnio").val(),
			mes:$("#debito_txMes").val()
		},
		method:"POST"
	})
}

function activaDebitoAutomatico()
{
	var caja = $("#debito_dlDebito").val();
	
	if (caja != '' && caja != 0)
	{
		$("#liGenDeb").css("pointer-events", "all");
		$("#liGenDeb").css("color", "#337ab7");
		$("#liGenDeb a").css("color", "#337ab7");
		
		$("#liImprimirEnt").css("pointer-events", "all");
		$("#liImprimirEnt").css("color", "#337ab7");
		$("#liImprimirEnt buttom").css("color", "#337ab7");
	} else 
	{
		$("#liGenDeb").css("pointer-events", "none");
		$("#liGenDeb").css("color", "#ccc");
		$("#liGenDeb a").css("color", "#ccc");
		
		$("#liImprimirEnt").css("pointer-events", "none");
		$("#liImprimirEnt").css("color", "#ccc");
		$("#liImprimirEnt buttom").css("color", "#ccc");
	}
}

activaDebitoAutomatico();

</script>