<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;

/* @var $this yii\web\View */

$title = 'Ajustes Web de Contribuyentes';
$this->params['breadcrumbs'][] = $title;

?>
<div class="intima-view">
	<h1><?= Html::encode($title) ?></h1>
	<?php
		if (isset($alert) == null) $alert = '';
		Alert::begin([
			'id' => 'AlertaAjustesWeb',
			'options' => [
			'class' => ($m == 1) ? 'alert-success' : 'alert-info',
			'style' => $alert !== '' ? 'display:block' : 'display:none'
			],
		]);

		if ($alert !== '') echo $alert;

		Alert::end();

		if ($alert !== '') echo "<script>window.setTimeout(function() { $('#AlertaAjustesWeb').alert('close'); }, 5000)</script>";
	?>
	<div class="form-panel">
		<label><u>Filtro</u></label>
		<table cellpadding='5'>
			<tr>
				<td>
					<label>Fecha:</label>
					<?= 
						DatePicker::widget(
							[
								'id' => 'filtrofchdesde',
								'name' => 'filtrofchdesde',
								'dateFormat' => 'dd/MM/yyyy',
								'options' => ['style' => 'width:80px','class' => 'form-control'],
							]
						);
					?>
					<label>a</label>
					<?= 
						DatePicker::widget(
							[
								'id' => 'filtrofchhasta',
								'name' => 'filtrofchhasta',
								'dateFormat' => 'dd/MM/yyyy',
								'options' => ['style' => 'width:80px','class' => 'form-control'],
							]
						);
					?>
				</td>
				<td width='30px'></td>
				<td>
					<label>Estado:</label>
					<?= 
						Html::dropDownList('dlFiltroEstado', 'P', ['P'=>'Pendiente','A'=>'Aprobado'], [
							'class' => 'form-control','id'=>'dlFiltroEstado'
						]); 
					?>
				</td>
				<td width='30px'></td>
				<td>
					<?= Html::Button('Buscar',['class' => 'btn btn-primary', 'id' => 'btBuscarAjuWeb']) ?>
				</td>
			</tr>
		</table>
	</div>	
	<div style="margin:10px 0px; width:98%">
		<?php
			Pjax::begin(['id' => 'PjaxGrilla']);
		?>
				<div style="<?= $error=='' ? "display:none;" : "display:block;"?>margin:10px 0px;" class="error-summary"><ul><li><?=$error?></li></ul></div>
		<?php
				echo GridView::widget([
					'id' => 'GrillaAjusteWeb',
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'dataProvider' => $dataProvider,
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [

							['attribute'=>'obj_id','label' => 'Persona', 'contentOptions'=>['style'=>'text-align:center','width'=>'60px']],
							['attribute'=>'ndoc','label' => 'Documento', 'contentOptions'=>['style'=>'text-align:left','width'=>'50px']],
							['attribute'=>'cuit','label' => 'CUIT', 'contentOptions'=>['style'=>'text-align:left','width'=>'50px']],
							['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
							['attribute'=>'domicilio','label' => 'Domicilio', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
							['attribute'=>'est_nom','label' => 'Estado', 'contentOptions'=>['style'=>'text-align:center;width:30px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:20px','align'=>'center'],
								'template' =>'{view}',
								'buttons'=>[
									'view' =>  function($url, $model, $key)
												{
													if ($model['est'] == 'P')
														return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view','id' => $model['obj_id'],'ajuste'=>$model['aju_id']],
																	['target' => '_black','data-pjax' => "0"]);
												},
								]
							]
						],
				]);
			Pjax::end();
		?>
	
	</div>
</div>

<script>
$("#btBuscarAjuWeb").click(function(){
	$.pjax.reload({
		container: '#PjaxGrilla', 
		data: {
			fechadesde: $('#filtrofchdesde').val(),
			fechahasta: $('#filtrofchhasta').val(),
			estado: $('#dlFiltroEstado').val()
		},
		type: 'POST',
		replace:false,
		push:false
	});
});
</script>