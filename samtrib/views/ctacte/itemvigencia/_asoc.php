<?php
use yii\widgets\Pjax;
use yii\grid\GridView;

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;

use yii\bootstrap\Alert;

use app\models\ctacte\ItemVigencia;	
?>
	
	<script type="text/javascript">
		function grabarAsoc(){
			
			var comp1 = $("#paramCompNuevo1").val();
			var comp2 = $("#paramCompNuevo2").val();
			var comp3 = $("#paramCompNuevo3").val();
			var comp4 = $("#paramCompNuevo4").val();
			var monto = $("#montoCompNuevo").val();
			var item_id = $("#itemVigenciaItemId").val();
			var adesde = $("#itemVigenciadesde").val();
			var cdesde = $("#itemVigenciaCuotadesde").val();
			var ahasta = $("#itemVigenciahasta").val();
			var chasta = $("#itemVigenciaCuotahasta").val();
			var consulta = $("#itemVigenciaAsocConsulta").val();
			
			$.pjax.reload({
				
							container : "#pjaxNuevoValorAsoc",
							url : "<?= BaseUrl::toRoute('//ctacte/itemvigencia/asoc')?>",
							type : "POST",
							push : false,
							replace : false,
							data : {
								"ItemVigencia" : {
								"p1asoc" : comp1,
								"p2asoc" : comp2,
								"p3asoc" : comp3,
								"p4asoc" : comp4,
								"masoc" : monto,
								
								"item_id" : item_id,
								"adesde" : adesde,
								"cdesde" : cdesde,
								"ahasta" : ahasta,
								"chasta" : chasta
								},
								
								"consulta" : consulta
							}
			});
		}
	</script>
	
	
	<?php
	Pjax::begin(['id' => 'pjaxNuevoValorAsoc', 'enablePushState' => false, 'enableReplaceState' => false, 'clientOptions' => ['type' => 'POST']]);

	$consulta = isset($extras['consultaAsoc']) ? $extras['consultaAsoc'] : $consulta;
		
	if(!isset($model) || $model->item_id <= 0)
	{
		$item_id = isset($item_id) ? $item_id : 0;
		$adesde = isset($adesde) ? $adesde : 0;
		$cdesde = isset($cdesde) ? $cdesde : 0;
		
		$model = new ItemVigencia();
		$model->item_id = $item_id;
		$model->adesde = $adesde;
		$model->cdesde = $cdesde;
		
		
		$model = $model->buscarUno();
		
		
	}

	echo Html::input('hidden', null, $consulta, ['id' => 'itemVigenciaAsocConsulta']);
	?>
	
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1" style="border-bottom:1px solid #DDDDDD; margin-top:10px;">
		</div>
	</div>
	<h4>Valores</h4>
	
	<div class="row">
		<div class="col-xs-3" style="padding-right:0px;">
			<label for="montoCompNuevo">Monto: </label>
			<?php
				echo Html::textInput(null, $model->masoc, ['id' => 'montoCompNuevo', 'class' => 'form-control', 'style' => 'width:55%;', 'maxlength' => 7]);
			?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-2" style="padding-right:1px;">
			<label for="paramCompNuevo1">P1: </label>
			<?php
				echo Html::input('text', null, $model->p1asoc, ['id' => 'paramCompNuevo1', 'class' => 'form-control', 'style' => 'width:60%;', 'maxlength' => 7]);
			?>
		</div>
		
		<div class="col-xs-2" style="padding-left:1px; padding-right:1px;">
			<label for="paramCompNuevo2">P2: </label>
			<?php
				echo Html::input('text', null, $model->p2asoc, ['id' => 'paramCompNuevo2', 'class' => 'form-control', 'style' => 'width:60%;', 'maxlength' => 7]);
			?>
		</div>
		
		<div class="col-xs-2" style="padding-left:1px; padding-right:1px;">
			<label for="paramCompNuevo3">P3: </label>
			<?php
				echo Html::input('text', null, $model->p3asoc, ['id' => 'paramCompNuevo3', 'class' => 'form-control', 'style' => 'width:60%;', 'maxlength' => 7]);
			?>
		</div>
		
		<div class="col-xs-2" style="padding-left:1px; padding-right:1px;">
			<label for="paramCompNuevo4">P4: </label>
			<?php
				echo Html::input('text', null, $model->p4asoc, ['id' => 'paramCompNuevo4', 'class' => 'form-control', 'style' => 'width:60%;', 'maxlength' => 7]);
			?>
		</div>
		
		
		<?php
		if($consulta == 3)
		{
		?>
		<div class="col-xs-2" style="padding-left:0px;">
			<?php
				echo Html::a('Grabar valores', null, ['class' => 'btn btn-success', 
										'style' =>  'cursos:pointer', 
										'onclick' => 'grabarAsoc();'
										]);	
			?>
		</div>
		<?php
		}
		?>
		
		
	</div>
	
	<script type="text/javascript">
	function eliminarAsoc(item_id, adesde, cdesde, ahasta, chasta, p1, p2, p3, p4)
	{
		var consulta = $("#itemVigenciaAsocConsulta").val();
	
		$.pjax.reload({
					container : "#pjaxNuevoValorAsoc",
					url : "<?= BaseUrl::toRoute('//ctacte/itemvigencia/asocdelete') ?>",
					type : "POST",
					push : false,
					replace : false,
					data : {
						"ItemVigencia" : {
										"item_id" : item_id,
										"adesde" : adesde,
										"cdesde" : cdesde,
										"ahasta" : ahasta,
										"chasta" : chasta,
										"p1asoc" : p1,
										"p2asoc" : p2,
										"p3asoc" : p3,
										"p4asoc" : p4
										},
						"consulta" : consulta
						}
					});
	}
	</script>
	
	<div class="row">
		<div class="col-xs-12" style="overflow:auto;">
			<?php	
				Pjax::begin(['enablePushState' => false, 'enableReplaceState' => false, 'id' => 'pjaxGrillaAsoc', 'clientOptions' => ['type' => 'POST']]);
			
				echo GridView::widget([
					'dataProvider' => $model->getValoresAsoc(),
					'id' => 'asocGrilla',
					'columns' => [
						['attribute' => 'monto', 'label' => 'Monto'],
						['attribute' => 'param1', 'label' => 'P1'],
						['attribute' => 'param2', 'label' => 'P2'],
						['attribute' => 'param3', 'label' => 'P3'],
						['attribute' => 'param4', 'label' => 'P4'],
						
						['class' => 'yii\grid\ActionColumn', 'buttons' => [
							
							'view' => function(){return false;},
							'update' => function(){return false;},
							'delete' => function($url, $model, $key) use($consulta){
								
								if($consulta != 3)
									return null;
								
								$adesde = intval($model['perdesde'] / 1000);
								$cdesde = $model['perdesde'] % 1000;
								
								$ahasta = intval($model['perhasta'] / 1000);
								$chasta = $model['perhasta'] % 1000;
								
								return Html::a(
											'<span class="glyphicon glyphicon-trash"></span>', 
											null,
											[
											'data-confirm' => '¿Está seguro que desea eliminar el registro?.',
											'onclick' => "eliminarAsoc(" . $model['item_id'] . ", $adesde, $cdesde, $ahasta, $chasta," . $model['param1'] . "," . $model['param2'] . "," . $model['param3'] . "," . $model['param4'] . ");"
											]);
							}
						]
						]
					],
					
					
					
				]);
				
				Pjax::end();
			?>
		</div>
	</div>
	
		

	<?php
	
	if( array_key_exists('mensaje', $extras) )
	{
	
	$clase = stripos($extras['mensaje'], 'correctamente') != false ? 'alert-success' : 'alert-danger';
	
	echo Alert::widget([
		'options' => ['class' => 'alert-dismissible ' . $clase, 'id' => 'alertItemVigenciaAsoc'],
		'body' => $extras['mensaje'] 
	]);
	
	echo "<script>window.setTimeout(function() { $('#alertItemVigenciaAsoc').alert('close'); }, 5000)</script>";

	}
	
	
	Pjax::end();
	?>
