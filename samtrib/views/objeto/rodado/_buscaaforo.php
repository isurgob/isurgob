<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

use app\utils\db\utb;
?>

<div class="rodado-buscaaforo">

	<script type="text/javascript">
	function rodadoBuscaAforoBuscar(){

		var codigoMarca 	= $("#rodadoBuscaAforoMarcaCodigo").val();
		var codigoTipo 		= $("#rodadoBuscaAforoTipoCodigo").val();
		var codigoModelo	= $("#rodadoBuscaAforoModeloCodigo").val();

		var nombreMarca 	= $("#rodadoBuscaAforoMarcaNombre").val();
		var nombreTipo 		= $("#rodadoBuscaAforoTipoNombre").val();
		var nombreModelo	= $("#rodadoBuscaAforoModeloNombre").val();

		var anio= <?= $anio; ?>;

		$("#rodadoBuscaAforoBotonBuscar").text("Buscando..");
		$("#rodadoBuscaAforoBotonBuscar").prop("disabled", true);

		$.pjax.reload({
			container	: "#pjaxRodadoBuscaAforo",
			replace 	: false,
			push 		: false,
			type 		: "GET",
			timeout: 100000,
			data : {
				"b"		: true,
				"mai"	: codigoMarca,
				"ti"	: codigoTipo,
				"moi"	: codigoModelo,
				"man"	: nombreMarca,
				"tn"	: nombreTipo,
				"mon"	: nombreModelo,
				"anio"	: anio
			},
		});

		$("#pjaxRodadoBuscaAforo").on("pjax:complete", function(){

			$("#rodadoBuscaAforoBotonBuscar").prop("disabled", false);
			$("#rodadoBuscaAforoBotonBuscar").text("Buscar");

			$("#pjaxRodadoBuscaAforo").off("pjax:complete");
		});

	}

	function cambiaorigen_id(nuevo){
		$("#rodadoBuscaAforoorigen_id").val(nuevo);
	}

	function clickFila($fila, origen_id, marca, tipo, modelo){

		$("#rodadoBuscaAforoValororigen_id").val(origen_id);
		$("#rodadoBuscaAforoValorMarca").val(marca);
		$("#rodadoBuscaAforoValorTipo").val(tipo);
		$("#rodadoBuscaAforoValorModelo").val(modelo);
	}

	function dblClickFila($fila, origen_id, marca, tipo, modelo, aforo_id){

		clickFila($fila, origen_id, marca, tipo, modelo);

		$.pjax.reload({
			container 	: "<?= $selectorPjax ?>",
			replace 	: false,
			push 		: false,
			type 		: "GET",
			timeout 	: 100000,
			data : {
				"aforo_id" : aforo_id,
				"cargarModeloAforo" : true,
				"esModal" : true,
				"anio" : <?= $anio ?>
			}
		});
	}
	</script>

	<div>
		<?php
		echo Html::input('hidden', null, null, ['id' => 'rodadoBuscaAforoValorMarca']);
		echo Html::input('hidden', null, null, ['id' => 'rodadoBuscaAforoValorTipo']);
		echo Html::input('hidden', null, null, ['id' => 'rodadoBuscaAforoValorModelo']);
		echo Html::input('hidden', null, $anio, ['id' => 'rodadoBuscaAforoAnio']);
		?>

		<table border="0" width="100%">

			<tr>
				<td><label>Marca:</label></td>
				<td></td>
				<td><label>Tipo:</label></td>
				<td colspan="2"></td>
				<td><label>Modelo:</label></td>
				<td></td>
			</tr>

			<tr>
				<td><?= Html::textInput(null, $extras['model']->marca_id, ['class' => 'form-control', 'id' => 'rodadoBuscaAforoMarcaCodigo', 'maxlength' => 3, 'style' => 'width:40px;']) ?></td>
				<td><?= Html::textInput(null, null, ['class' => 'form-control', 'id' => 'rodadoBuscaAforoMarcaNombre', 'maxlength' => 40, 'style' => 'width:200px;']) ?></td>
				<td><?= Html::textInput(null, $extras['model']->tipo_id, ['class' => 'form-control', 'id' => 'rodadoBuscaAforoTipoCodigo', 'maxlength' => 2, 'style' => 'width:40px;']) ?></td>
				<td><?= Html::textInput(null, null, ['class' => 'form-control', 'id' => 'rodadoBuscaAforoTipoNombre', 'maxlength' => 40, 'style' => 'width:200px;']) ?></td>
				<td></td>
				<td colspan="2"><?= Html::textInput(null, $extras['model']->modelo_id, ['class' => 'form-control', 'id' => 'rodadoBuscaAforoModeloCodigo', 'maxlength' => 3, 'style' => 'width:40px;']) ?>
				<?= Html::textInput(null, null, ['class' => 'form-control', 'id' => 'rodadoBuscaAforoModeloNombre', 'maxlength' => 40, 'style' => 'width:200px;']) ?></td>

				<td width="20px"></td>
				<td></td>
				<td algin="right"><?= Html::button('Buscar', ['class' => 'btn btn-primary pull-right', 'onclick' => 'rodadoBuscaAforoBuscar()', 'id' => 'rodadoBuscaAforoBotonBuscar']) ?></td>
			</tr>
		</table>
	</div>

	<div style="margin-top:5px;">
		<table border="0" width="100%">
			<tr>
				<td>
					<?php
					Pjax::begin(['id' => 'pjaxRodadoBuscaAforo', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);
					$resultados = [];

					$buscar = filter_var(Yii::$app->request->get('b', false), FILTER_VALIDATE_BOOLEAN);

					if($buscar){

						$marca_id 	= trim(Yii::$app->request->get('mai', ''));
						$marca_nom 	= trim(Yii::$app->request->get('man', ''));
						$tipo_id 	= trim(Yii::$app->request->get('ti', ''));
						$tipo_nom 	= trim(Yii::$app->request->get('tn', ''));
						$modelo_id 	= trim(Yii::$app->request->get('moi', ''));
						$modelo_nom	= trim(Yii::$app->request->get('mon', ''));
						$anio		= intval(Yii::$app->request->get('anio', 0));

						$cond	= '';

						if($marca_id != ''){
							$cond .= ($cond == '' ? "marca = '$marca_id'" : " And marca = '$marca_id'");
						}
						if($tipo_id != ''){
							$tipo_id = str_pad($tipo_id, 2, '0', STR_PAD_LEFT);
							$cond .= ($cond == '' ? "tipo Like '%$tipo_id'" : " And tipo Like '%$tipo_id'");
						}
						if($modelo_id != ''){
							$cond .= ($cond == '' ? "modelo = '$modelo_id'" : " And modelo = '$modelo_id'");
						}

						if($marca_nom != '') $cond .= ($cond == '' ? "marca_nom iLike '%$marca_nom%'" : " And marca_nom iLike '%$marca_nom%'");
						if($tipo_nom != '') $cond .= ($cond == '' ? "tipo_nom iLike '%$tipo_nom%'" : " And tipo_nom iLike '%$tipo_nom%'");
						if($modelo_nom != '') $cond .= ($cond == '' ? "modelo_nom iLike '%$modelo_nom%'" : " And modelo_nom iLike '%$modelo_nom%'");

						if($cond != ''){

							if($anio > 0){
								$c= "$anio Between anio_min And anio_max";
								$cond .= ($cond == '' ? $c : " And $c");
							}

						$resultados = utb::getAuxVarios(['v_rodado_aforo'],
									['aforo_id', 'origen', 'marca', 'tipo', 'modelo', 'marca_id_nom', 'tipo_id_nom', 'modelo_id_nom', 'anio_min', 'anio_max', 'valor_max'],
									[],
									2,
									$cond,
									'modelo_nom'
									);
						}
					}

					$dp = new ArrayDataProvider([
						'allModels' => $resultados,
						'pagination' => ['pageSize' => 15]
					]);

					echo GridView::widget([
						'dataProvider' => $dp,
						'headerRowOptions' => ['class' => 'grillaGrande'],
						'rowOptions' => function($model, $key, $index, $grid){

							return [
								'onclick' => 'clickFila($(this), "' . $model['origen'] . '", "' . $model['marca'] . '", "' . $model['tipo'] . '", "' . $model['modelo'] . '");',
								'ondblclick' => 'dblClickFila($(this), "' . $model['origen'] . '", "' . $model['marca'] . '", "' . $model['tipo'] . '", "' . $model['modelo'] . '", "' . $model['aforo_id'] . '");',
								'class' => 'grillaGrande'
								];
						},

						'columns' => [

							['attribute' => 'origen', 'label' => 'Ori', 'contentOptions' => ['class' => 'grillaGrande']],
							['attribute' => 'marca_id_nom', 'label' => 'Marca', 'contentOptions' => ['class' => 'grillaGrande']],
							['attribute' => 'tipo_id_nom', 'label' => 'Tipo', 'contentOptions' => ['class' => 'grillaGrande']],
							['attribute' => 'modelo_id_nom', 'label' => 'Modelo', 'contentOptions' => ['class' => 'grillaGrande']],
							['attribute' => 'anio_min', 'label' => 'Año Min.', 'contentOptions' => ['class' => 'grillaGrande']],
							['attribute' => 'anio_max', 'label' => 'Año Max.', 'contentOptions' => ['class' => 'grillaGrande']],
							['attribute' => 'valor_max', 'label' => 'Valor Max.', 'contentOptions' => ['class' => 'grillaGrande']]
						]
					]);

					Pjax::end();
					?>
				<td>
			</tr>
		</table>
	</div>
</div>
