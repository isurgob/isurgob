<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\utils\db\utb;
?>
<style>.asd td{border:1px solid;}</style>

<div class="form-frentistas">
	<div class="form" style="padding:5px;">
		<table width="100%" border="0">
			<tr>
				<td><b>Obra:</b></td>
				<td width="5px"></td>
				<td><?= Html::dropDownList(null, $filtroObra, utb::getAux('mej_obra', 'obra_id', 'nombre', 0, "est='A'"), ['class' => 'form-control', 'prompt' => '', 'id' => substr($selectorObra, 1), 'onchange' => 'cambiaObra($(this).val());']) ?></td>
				<td width="10px"></td>
				<td><b>Cuadra:</b></td>
				<td width="5px"></td>
				<td><?php
				
				Pjax::begin(['id' => 'pjaxObra', 'enableReplaceState' => false, 'enablePushState' => false]);
				$codigoObra = intval(Yii::$app->request->get('obra', $filtroObra));
				$cuadras = [];
				
				if($codigoObra > 0)
					$cuadras = utb::getAux('v_mej_cuadra', 'cuadra_id', "calle_nom || ' - ' || ncm", 0, "obra_id = $codigoObra");

				
				$cantidad = count($cuadras);
				
				
					?>
					<script type="text/javascript">
					$(document).ready(function(){
						<?= $funcionHabilitarGenerar ?>(<?= $cantidad == 0 ? 'false' : 'true' ?>);
					});
					</script>
					<?php
				
				
				echo Html::dropDownList(null, $filtroCuadra, $cuadras, ['class' => 'form-control', 'prompt' => ($cantidad === 0 && $codigoObra <= 0 ? 'Seleccione una obra' : ''), 'id' => substr($selectorCuadra, 1), 'disabled' => count($cuadras) == 0, 'style' => 'width:203px;']);
				Pjax::end(); 
				?>
				</td>
				<td width="10px"></td>
				<td><?= Html::button('Cargar', ['class' => 'btn btn-primary', 'onclick' => 'filtrar();']); ?></td>
			</tr>
			
			
			<tr>
				<td colspan="9">
					<?php
					Pjax::begin(['id' => 'pjaxGrillaFrentistas', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'margin-top:10px;']]);
					
					echo GridView::widget([
						'dataProvider' => $dpFrentistas,
						'headerRowOptions' => ['class' => 'grillaGrande'],
						'rowOptions' => ['class' => 'grillaGrande'],
						'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
						'columns' => [
							['attribute' => 'plan_id', 'label' => 'Plan'],
							['attribute' => 'obj_id', 'label' => 'Frentista'],
							['attribute' => 'obj_nom', 'label' => 'Nombre'],
							['attribute' => 'monto', 'label' => 'Monto'],
							['attribute' => 'fchalta', 'label' => 'Alta', 'format' => ['date', 'php:d/m/Y']],
							['attribute' => 'fchbaja', 'label' => 'Baja', 'format' => ['date', 'php:d/m/Y']],
							['attribute' => 'fchdesaf', 'label' => 'Desaf.', 'format' => ['date', 'php:d/m/Y']],
							['attribute' => 'est_nom', 'label' => 'Est.'],
							
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:1%;text-align:center'],
								'template' => '{view}',
								'buttons'=>[

									'view' => function($url, $model, $key)
									{
										return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/mejoraplan/index', 'id' => $model['plan_id']],[
											'class'=>'bt-buscar-label'
										]);
									},
								]
							]
						]
					]);
					
						if ( count($dpFrentistas->getModels()) > 0 ) 
							echo "<script> HabilitarImprimirExportar ( true ) </script>";
						else 	
							echo "<script> HabilitarImprimirExportar ( false ) </script>";
					
					Pjax::end();
					?>
				</td>
			</tr>
		</table>
	</div>
</div>

<script type="text/javascript">
function cambiaObra(nuevo){
	
	nuevo = parseInt(nuevo);
	
	if(isNaN(nuevo) || nuevo <= 0){
		
		$("#filtroCuadra").val("");
		$("#filtroCuadra").prop("disabled", true);
	}
	
	$.pjax.reload({
		
		container : "#pjaxObra",
		type : "GET",
		replace : false,
		push : false,
		data : {
			obra : nuevo
		}
	});
}

function filtrar(){
	
	var obra = $("<?= $selectorObra ?>").val();
	var cuadra = $("<?= $selectorCuadra ?>").val();
	
	$.pjax.reload({
		container : "#pjaxGrillaFrentistas",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"filtrar" : true,
			"obra" : obra,
			"cuadra" : cuadra
		}
	});
}

$(document).ready(function(){
	
	
});
</script>