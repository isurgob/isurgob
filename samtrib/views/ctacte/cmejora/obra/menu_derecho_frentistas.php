<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\Pjax;
use yii\helpers\BaseUrl;
?>

<div class="text-left">
<?php
 	Modal::begin([
	'id' => 'modalGenerar',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;" class="pull-left">Generar frentistas</h2><h2>&nbsp;</h2>',
	'toggleButton' => false,
    'closeButton' => [
          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ],
	'size' => 'modal-sm'
	]);

	echo $this->render('_form_generar', ['selectorObra' => $selectorObra, 'selectorCuadra' => $selectorCuadra, 'selectorModal' => '#modalGenerar', 'modelCuadra' => $modelCuadra]);

	Modal::end();
?>

<?php 
	Modal::begin([
		'id' => 'Exportar',
		'header' => '<h2>Exportar Datos</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		]
	]);
		Pjax::begin(['id' => 'pjaxExportar', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'margin-top:10px;']]);
			echo $this->render('//site/exportar',['titulo' => Yii::$app->session['titulo'], 'desc' => Yii::$app->session['condicion'], 'grilla' => 'Exportar']);
		Pjax::end();	

	Modal::end();
?>
</div>


<ul id='ulMenuDer' class='menu_derecho'>

	<li id='liGenerar' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Generar</b>', null, ['class' => 'bt-buscar-label', 'style' => 'cursor:pointer', 'data-toggle' => 'modal', 'data-target' => '#modalGenerar']) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', null, ['class' => 'bt-buscar-label', 'onclick' => 'ImprimirExportar( 0 )', 'style' => 'cursor:pointer']) ?></li>
	<li id='liExportar' class='glyphicon glyphicon-export'> <?= Html::a('<b>Exportar</b>', null, ['class' => 'bt-buscar-label', 'onclick' => 'ImprimirExportar( 1 )', 'style' => 'cursor:pointer']) ?></li>
</ul>

<script type="text/javascript">
function <?= $funcionHabilitarGenerar ?>(habilitar){
	
	if(habilitar){
		
		$("#liGenerar").css("pointer-events", "all");
		$("#liGenerar a").css("color", "#337ab7");
		$("#liGenerar").css("color", "#337ab7");	
		
	} else {
		
		$("#liGenerar").css("pointer-events", "none");
		$("#liGenerar a").css("color", "#ccc");
		$("#liGenerar").css("color", "#ccc");		
	}
}

function HabilitarImprimirExportar( habilitar ){
	
	if(habilitar){
		
		$("#liImprimir").css("pointer-events", "all");
		$("#liImprimir a").css("color", "#337ab7");
		$("#liImprimir").css("color", "#337ab7");

		$("#liExportar").css("pointer-events", "all");
		$("#liExportar a").css("color", "#337ab7");
		$("#liExportar").css("color", "#337ab7");	
		
	} else {
		
		$("#liImprimir").css("pointer-events", "none");
		$("#liImprimir a").css("color", "#ccc");
		$("#liImprimir").css("color", "#ccc");	

		$("#liExportar").css("pointer-events", "none");
		$("#liExportar a").css("color", "#ccc");
		$("#liExportar").css("color", "#ccc");		
	}
}

function ImprimirExportar( exportar ){

	var obra = $("#filtroObra").val();
	var cuadra = $("#filtroCuadra").val();
	
	if ( exportar == 0 )
		window.open("<?= BaseUrl::toRoute('imprimirexportarfrente');?>&obra=" + obra + "&cuadra=" + cuadra, '_blank');
	else {
	
		$.get( "<?= BaseUrl::toRoute('imprimirexportarfrente');?>&obra=" + obra + "&cuadra=" + cuadra + "&exportar=" + exportar
		).success(function(data){
			
			$.pjax.reload({ 
				container : "#pjaxExportar",
				replace : false,
				push : false
			});
			
			 $( "#pjaxExportar" ).on( "pjax:end", function() {

				$("#Exportar").modal("show");

			});
			
		});
		
	}	

}

$("#ulMenuDer").css("pointer-events", "none");
$("#ulMenuDer li").css("color", "#ccc");
$("#ulMenuDer li a").css("color", "#ccc");


</script>