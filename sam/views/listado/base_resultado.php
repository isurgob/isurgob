<?php
use yii;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use yii\helpers\BaseUrl;
use app\controllers\ExportarController;

if ( !is_array($urlOpciones) )
	$urlOpciones = [$urlOpciones];
	
foreach($breadcrumbs as $dato)
	$this->params['breadcrumbs'][]= $dato;

	
if ( isset($exportar) ){
	
	echo Html::button('Exportar', ['class' => 'btn btn-success pull-right', 'onclick' => 'ResultadoAccion(3)'] );
	
	Modal::begin([
		'id' => 'modalExportar',
		'header' => '<h2>Exportar Datos</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		]
	]);
		
		echo ExportarController::exportar( $titulo, $descripcion, 'exportar', json_encode($model) );

	Modal::end();
	
}	
	
if(isset($imprimir))
	echo Html::button('Imprimir', ['class' => 'btn btn-success pull-right', 'onclick' => 'ResultadoAccion(2)','style' => 'margin:0 5px'] );
	

if(isset($nuevo))
	echo Html::a('Nuevo', [$nuevo], ['class' => 'btn btn-success pull-right', 'style' => 'margin:0 5px'] );	
	
if(isset($modelModal)){
	Modal::begin([
		'id' => $nombreModal, 
		'header' => '<h2>' . $tituloModal. '</h2>',
		'toggleButton' => [
			'label' => $botonModal,
			'class' => 'btn btn-success'
		],
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		]
	]);

		echo $this->render( $vistaModal, [
				'model' => $modelModal,
				'cant' => count($dataProviderResultados->getModels())
			]);
		
	Modal::end();	
}
	
?>

<?= Html::button('Volver', ['class' => 'btn btn-success pull-right','onclick' => 'ResultadoAccion(1)']); ?>


<h1>Listado: Resultado</h1>

<div>
	<textarea class="form-control" style="width:100%; resize:none;" rows="3" disabled><?= $descripcion; ?></textarea>
</div>

<div>
	<?php
	$form = ActiveForm::begin(['options' => ['class' => 'hidden', 'id' => 'formResultado']]);
		echo Html::hiddenInput('model', urlencode(serialize($model)),['id' => 'model']);
	ActiveForm::end();
	?>

	<?php
	Pjax::begin(['id' => 'pjaxGrillaResultado', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

		echo GridView::widget([
			'headerRowOptions'	=> ['class' => 'grilla'],
			'rowOptions' 		=> ['class' => 'grilla'],
			'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
			'dataProvider' 		=> $dataProviderResultados,
			'columns' 			=> $columnas,
			]);

	Pjax::end();
	
	?>

</div>

<script>
	/* 
	Función que define que hacer con los resultados una vez obtenidos.
	Si accion = 1 => Ejecuta el botón volver
	Si accion = 2 => Ejecuta Imprimir
	Si accion = 3 => Ejecuta Exportar
	*/
	function ResultadoAccion(accion){
		if (accion == 3){
			$("#modalExportar").modal("show");
			
			return false;
			
		}else if (accion == 2){
			<?php if ( isset($imprimir) ){ ?>
				$("#formResultado").attr("action","<?=BaseUrl::toRoute($imprimir)?>");
				$("#formResultado").attr("target","_black");
			<?php } ?>	
		}else 	{
			$("#formResultado").attr("action","<?=BaseUrl::toRoute($urlOpciones)?>");	
			$("#formResultado").attr("target","");
		}
		$("#formResultado").submit();
	}
</script>