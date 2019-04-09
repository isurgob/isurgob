<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;
use yii\bootstrap\Alert;

use app\utils\db\utb;

$this->params['breadcrumbs'][]= ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][]= 'Tributos';
?>

<?php
if(array_key_exists('mensaje', $extras) && $extras['mensaje'] != ''){

	Alert::begin([
		'options' => [
			'class' => 'alert alert-success'
		],
		'id' => 'alertMensaje'
	]);

	echo $extras['mensaje'];

	Alert::end();

	?>
	<script type="text/javascript">
	$(document).ready(function(){
		setTimeout(function(){$("#alertMensaje").fadeOut();}, 5000);
	});
	</script>
	<?php
}
?>

<div class="tributos">
	<?php if (utb::getExisteProceso(3011)) echo Html::a('Nuevo', ['create'], ['class' => 'btn btn-success pull-right']); ?>
	<h1><b>Tributos</b></h1>

	<div style="border-bottom:1px solid #DDDDDD; margin-top:15px;"></div>

	<div style="margin-top:5px;">

		<table width="100%" border="0">
			<tr>
				<td><b>Nombre:</b></td>
				<td width="5px"></td>
				<td><?= Html::textInput(null, null, ['class' => 'form-control', 'id' => 'filtroNombre', 'style' => 'width:300px;text-transform: uppercase;', 'onchange' => 'filtrar();']); ?></td>
				<td width="10px"></td>
				<td><b>Tipo tributo:</b></td>
				<td width="5px"></td>
				<td><?= Html::dropDownList(null, null, utb::getAux('trib_tipo', 'cod', 'nombre', 0), ['class' => 'form-control', 'id' => 'filtroTipoTributo', 'prompt' => '', 'onchange' => 'filtrar();']); ?></td>
				<td width="10px"></td>
				<td><b>Tipo objeto:</b></td>
				<td width="5px"></td>
				<td><?= Html::dropDownList(null, null, utb::getAux('objeto_tipo'), ['class' => 'form-control', 'id' => 'filtroTipoObjeto', 'prompt' => '', 'onchange' => 'filtrar();']); ?></td>
			</tr>
		</table>
	</div>

	<div style="margin-top:5px;">
		<table width="100%" border="0">
			<tr>
				<td width="250px" valign="top">
					<div style="padding:5px;">
						<?php
						Pjax::begin(['id' => 'pjaxTributos', 'enableReplaceState' => false, 'enablePushState' => false]);

						echo GridView::widget([
							'dataProvider' => $extras['dpTributos'],
							'summary' => false,
							'headerRowOptions' => ['class' => 'grillaGrande'],
							'rowOptions' => function($model){
								return [
									'onclick' => 'cargarTributo(' . $model['trib_id'] . ')',
									'class' => 'grillaGrande'
								];
							},
							'columns' => [
								['attribute' => 'trib_id', 'label' => 'Cód', 'options' => ['style' => 'width:1%;'], 'contentOptions' => [ 'style' => 'text-align: center']],
								['attribute' => 'nombre_redu', 'label' => 'Nombre'],
								['attribute' => 'est', 'label' => 'Est.', 'contentOptions' => ['style' => 'text-align: center' ]],
								['class' => '\yii\grid\ActionColumn', 'template' => (utb::getExisteProceso(3011) ? '{update}&nbsp;{delete}{activar}' : '')
									.(utb::getExisteProceso(3010) ? '{imprimir}' : ''),
								'buttons' => [
									'update' => function($url, $model){

										if($model['est'] === 'A')
											return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);

										return null;
									},

									'delete' => function($url, $model){

										if($model['est'] === 'A' && !$model['interno'])
											return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);

										return null;
									},

									'imprimir' => function($url, $model){

										if($model['est'] === 'A')
											return '&nbsp;'.Html::a('<span class="glyphicon glyphicon-print"></span>', ['imprimir','id'=>$model['trib_id']],['target'=>'_black','data-pjax' => "0"]);

										return null;
									},

									'activar' => function($url, $model){

										if($model['est'] === 'B')
											return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url);

										return null;
									}
								]
								]
							]
						]);

						Pjax::end();
						?>
					</div>
				</td>
				<td valign="top">
					<div style="padding:5px;">
						<?php
						Pjax::begin(['id' => 'pjaxDetalle', 'enableReplaceState' => false, 'enablePushState' => false]);

						echo Tabs::widget([

							'items' => [
								[
								'label' => 'Datos',
								'content' => $this->render('_form_consulta', ['model' => $extras['model']]),
								'active' => true,
								'options' => ['style' => 'padding:10px; border:1px solid #DDDDDD; border-top:none; border-radius:0px 0px 8px 8px;;']
								],

								[
								'label' => 'Items',
								'content' => $this->render('items', ['model' => $extras['model'], 'dpItems' => $extras['dpItems']]),
								'active' => false
								],

								[
								'label' => 'Vencimientos',
								'content' => $this->render('vencimientos', ['model' => $extras['model'], 'dpVencimientos' => $extras['dpVencimientos']]),
								'active' => false
								]
							]
						]);


						Pjax::end();
						?>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>

<script type="text/javascript">
function cargarTributo(codigo){

	codigo= parseInt(codigo);

	if(isNaN(codigo) || codigo <= 0) return;

	$.pjax.reload({

		container: "#pjaxDetalle",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"trib_id": codigo
		},
		scrollTo: 0
	});
}

function filtrar(){

	var nombre= $("#filtroNombre").val();
	var tipo= parseInt($("#filtroTipoTributo").val());
	var objeto= $("#filtroTipoObjeto").val();

	if(isNaN(tipo) || (tipo === 0 && $("#filtroTipoTributo option:selected").text() == '')) tipo= -1;

	$.pjax.reload({

		container: "#pjaxTributos",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"filtroNombre": nombre,
			"filtroTipoTributo": tipo,
			"filtroTipoObjeto": objeto
		}
	});
}
</script>
