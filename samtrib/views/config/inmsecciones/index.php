<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ButtonDropdown;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use app\controllers\config\InmseccionesController;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inmuebles S1/S2/S3';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones','url' => ['//site/config']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="judi-hono-index">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<div class="separador-horizontal"></div>

	<div id="form_divMensaje" class="mensaje alert-success" style="margin: 10px 0px; display: none"></div>
	
	<table width='100%'>
		<tr>
			<td width='9%'><label>Secci&oacute;n 1:</label></td>
			<td width='30%'>
				<?= Html::dropDownList('dlS1', 0, $arrayS1, [
						'id' => 'dlS1', 
						'class'=>'form-control', 
						'style'=>'width:98%',
						'onchange' => 'f_cabiarS1()',
						 'prompt' => '<Todos>'
					]);
				?>	
			</td>
			<td align='right' width='11%'>
				<?=
				 ButtonDropdown::widget([
					'label' => 'Acciones',
					'id' => 'acciones_S1',
					'dropdown' => [
						'items' => [
							['label' => 'Nuevo', 'url' => '#', 'linkOptions' => ['onclick' => 'f_abmSeccion( "", "", "nuevos1" )']],
							['label' => 'Eliminar', 'url' => '#', 'linkOptions' => ['onclick' => 'f_abmSeccion( "", "", "eliminars1" )', 'class' => 'disableded']],
						]
					],
					'options' => [
						'class' => 'btn btn-primary'
					]
					]);
				?>
			</td>	
		</tr>
		<tr><td colspan='6'> <div style='margin:5px 0px' class="separador-horizontal"></div> </td></tr>
		<tr>
			<td colspan='2'><label> Secci&oacute;n 2 </label> </td>
			<td align='right'>
				<?=
					Html::button( 'Nuevo', [
						'id' => 'btNuevoS2',
						'class' => 'btn btn-primary',
						'onclick' => 'f_abmSeccion( "", "", "nuevos2" )',
					]);
				?>
			</td>
			<td width='1%'></td>
			<td><label> Secci&oacute;n 3 </label> </td>
			<td align='right'>
				<?=
					Html::button( 'Nuevo', [
						'id' => 'btNuevoS3',
						'class' => 'btn btn-primary',
						'onclick' => 'f_abmSeccion( "", "", "nuevos3" )',
					]);
				?>
			</td>
		</tr>
		<tr><td colspan='6'> <div style='margin:5px 0px'></div> </td></tr>
		<tr>
			<td colspan='3' valign='top'>
				<!-- INICIO Grilla S2 -->
				<?php

					Pjax::begin([ 'id' => 'PjaxGrillaS2', 'enablePushState' => false, 'enableReplaceState' => false ]);
						
						echo GridView::Widget([
							'dataProvider' => $dataProviderS2,
							'id' => 'grilla_S2',
							'headerRowOptions' => [ 'class' => 'grilla' ],
							'summaryOptions' => ['style' => 'display:none'],
							'rowOptions' => function ($model) { return [
								'class' => 'grilla seleccionable',
								'onclick' => 'f_cabiarS2( "' . $model['s2'] . '", $(this) )'];},
							'columns' => [
								
								[ 'attribute' => 's1', 'label' => 'S1', 'value' => function($model, $key, $index, $widget) {
																			          return $model['s1'] == '' ? '<Todos>' : $model['s1'];
																			        }
								],
								[ 'attribute' => 's2', 'label' => 'S2', 'value' => function($model, $key, $index, $widget) {
																			          return $model['s2'] == '' ? '<Todos>' : $model['s2'];
																			        }
								],
								[
									'class' => 'yii\grid\ActionColumn',
									'contentOptions'=>['style'=>'width:2%', 'align' => 'center'],
									'template' => '{delete}',
									'buttons' => [

										'delete' => function( $url, $model, $key )
													{
														if ( $model['s2'] != '' )
															return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
																'class'=>'bt-buscar-label',
																'style'=>'color:#337ab7',
																'onclick' => "f_abmSeccion( '" . $model['s2'] . "', '', 'eliminars2' );event.stopPropagation();",
																'data-pjax' => 0 
															]);
													},
									]
								]
							],
						]);

					Pjax::end();
				?>
				<!-- FIN Grilla S2 -->
			</td>
			<td></td>
			<td colspan='2' valign='top'>
				<!-- INICIO Grilla S3 -->
				<?php

					Pjax::begin([ 'id' => 'PjaxGrillaS3', 'enablePushState' => false, 'enableReplaceState' => false ]);
						
						echo GridView::Widget([
							'dataProvider' => $dataProviderS3,
							'id' => 'grilla_S3',
							'headerRowOptions' => [ 'class' => 'grilla' ],
							'summaryOptions' => ['style' => 'display:none'],
							'rowOptions' => function ($model) { return [
								'class' => 'grilla seleccionable',
								'onclick' => 'f_info_cambiaRubro( ' . $model['s2'] . ', "#mat_info_grillaRubro", $(this) )'];},
							'columns' => [
								
								[ 'attribute' => 's1', 'label' => 'S1', 'value' => function($model, $key, $index, $widget) {
																			          return $model['s1'] == '' ? '<Todos>' : $model['s1'];
																			        }
								],
								[ 'attribute' => 's2', 'label' => 'S2', 'value' => function($model, $key, $index, $widget) {
																			          return $model['s1'] == '' ? '<Todos>' : $model['s1'];
																			        }
								],
								[ 'attribute' => 's3', 'label' => 'S3'],
								[
									'class' => 'yii\grid\ActionColumn',
									'contentOptions'=>['style'=>'width:2%', 'align' => 'center'],
									'template' => '{delete}',
									'buttons' => [

										'delete' => function( $url, $model, $key )
													{
														return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
															'class'=>'bt-buscar-label',
															'style'=>'color:#337ab7',
															'onclick' => "f_abmSeccion( '" . $model['s2'] . "', '" . $model['s3'] . "', 'eliminars3' );event.stopPropagation();",
															'data-pjax' => 0 
														]);
													},
									]
								]
							],
						]);

					Pjax::end();
				?>
				<!-- FIN Grilla S2 -->
			</td>
		</tr>
	</table>

</div>

<?php

    Pjax::begin([ 'id' => 'pjaxModal', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

        $titulo = 'Consultar Secciones';		

        if ( $scenario == 'nuevos1' ) $titulo = "Nuevo S1";
        if ( $scenario == 'eliminars1' ) $titulo = "Eliminar S1";
		
		if ( $scenario == 'nuevos2' ) $titulo = "Nuevo S2";
		if ( $scenario == 'eliminars2' ) $titulo = "Eliminar S2";
		
		if ( $scenario == 'nuevos3' ) $titulo = "Nuevo S3";
		if ( $scenario == 'eliminars3' ) $titulo = "Eliminar S3";
		
		Modal::begin([
                'id' => 'ModalSecciones',
                'class' => 'container',
                'size' => 'modal-sm',
                'header' => '<h2><b>' . $titulo . '</b></h2>',
                'closeButton' => [
                    'label' => '<b>X</b>',
                    'class' => 'btn btn-danger btn-sm pull-right'
                    ],
            ]);
			
           echo InmseccionesController::viewSecciones( $model->s1, $model->s2, $model->s3, $scenario, "#ModalSecciones", "#pjaxGrillas" );

        Modal::end();

    Pjax::end();
?>

<?php if ( $mensaje != '' ) { //Si existen mensajes ?>
	<script>

	$( document ).ready(function() {

		mostrarMensaje( "<?= $mensaje; ?>", "#form_divMensaje" );

	});

	</script>
<?php }  ?>

<script>

function f_cabiarS1(){

	$.pjax.reload({
        container: '#PjaxGrillaS2',
        type: 'POST',
        replace: false,
        push: false,
        data : {
            "s1": $("#dlS1").val()
        }
    });
	
	$( "#PjaxGrillaS2" ).on( "pjax:end", function() {

        f_cabiarS2( 0, '' );

    });
}

function f_cabiarS2( s2, $fila ){

	if ( $fila != '' )
		marcarFilaGrilla( "#grilla_S2", $fila );
	
	$.pjax.reload({
        container: '#PjaxGrillaS3',
        type: 'POST',
        replace: false,
        push: false,
        data : {
            "s2": s2,
			"s1": $("#dlS1").val()
        }
    });
}

function f_abmSeccion( s2, s3, scenario ){

	var s1 = $("#dlS1").val();
	
	$.pjax.reload({
        container: '#pjaxModal',
        type: 'POST',
        replace: false,
        push: false,
        data : {
            "s1": s1,
            "s2": s2,
            "s3": s3,
            "scenario": scenario
        }
    });

    $( "#pjaxModal" ).on( "pjax:end", function() {

        $("#ModalSecciones").modal( "show" );

    });
}
	

</script>