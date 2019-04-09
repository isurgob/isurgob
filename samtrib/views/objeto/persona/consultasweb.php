<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;

/* @var $this yii\web\View */

$title = 'Consultas Web de Contribuyente';
$this->params['breadcrumbs'][] = $title;

?>
<div class="intima-view">
	<h1><?= Html::encode($title) ?></h1>
	<?php
		if (isset($alert) == null) $alert = '';
		Alert::begin([
			'id' => 'AlertaConsMail',
			'options' => [
			'class' => ($m == 1) ? 'alert-success' : 'alert-info',
			'style' => $alert !== '' ? 'display:block' : 'display:none'
			],
		]);

		if ($alert !== '') echo $alert;

		Alert::end();

		if ($alert !== '') echo "<script>window.setTimeout(function() { $('#AlertaConsMail').alert('close'); }, 5000)</script>";
	?>
	<div class="form-panel">
		<label><u>Filtro</u></label>
		<table width='100%' cellpadding='5'>
			<tr>
				<td width='50px'><label>Fecha:</label></td>
				<td width='110px'>
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
				</td>
				<td width='30px' align="left"><label>a</label></td>
				<td colspan="3">
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
			</tr>
			<tr>
				<td><label>Estado:</label></td>
				<td>
					<?= 
						Html::dropDownList('dlFiltroEstado', 'P', utb::getAux('sam.cons_test'), [
							'class' => 'form-control','id'=>'dlFiltroEstado'
						]); 
					?>
				</td>
				<td><label>Tema:</label></td>
				<td>
					<?= 
						Html::dropDownList('dlFiltroTema', null, utb::getAux('sam.cons_tema'), [
							'class' => 'form-control','id'=>'dlFiltroTema'
						]); 
					?>
				</td>
				<td width='50px'><label>Documento:</label></td>
				<td>
					<?= 
						Html::input('text', 'txDoc', null, [
							'class' => 'form-control','id'=>'txDoc','maxlength'=>'11'
						]); 
					?>
				</td>
			</tr>
			<tr>
				<td colspan='7'>
					<hr/>
				</td>
			</tr>
			<tr>
				<td colspan='7'>
					<?= Html::Button('Buscar',['class' => 'btn btn-primary', 'id' => 'btBuscarConsWeb']) ?>
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
					'id' => 'GrillaConsWeb',
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'dataProvider' => $dataProvider,
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [

							['attribute'=>'fecha','label' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center','width'=>'20px']],
							['attribute'=>'cons_id','label' => 'Cons.', 'contentOptions'=>['style'=>'text-align:center','width'=>'20px']],
							['attribute'=>'ndoc','label' => 'Documento', 'contentOptions'=>['style'=>'text-align:left','width'=>'50px']],
							['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
							['attribute'=>'tema_nom','label' => 'Tema', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
							['attribute'=>'est_nom','label' => 'Estado', 'contentOptions'=>['style'=>'text-align:center;width:30px']],
							['attribute'=>'modif','label' => 'Modificación', 'contentOptions'=>['style'=>'text-align:left;width:150px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:40px','align'=>'center'],
								'template' =>'{view}{update}{delete}',
								'buttons'=>[
									'view' =>  function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-eye-open"></span>&nbsp;', ['consultawebedit','id' => $model['cons_id'], 'consulta' => '1']);
												},

									'update' => function($url, $model, $key)
												{
													if ($model['est'] != 'B')
														return Html::a('<span class="glyphicon glyphicon-pencil"></span>&nbsp;', ['consultawebedit','id' => $model['cons_id'], 'consulta' => '3']);
												},
									'delete' => function($url, $model, $key)
												{
													if ($model['est'] != 'B')
														return Html::a('<span class="glyphicon glyphicon-trash"></span>&nbsp;', ['consultawebedit','id' => $model['cons_id'], 'consulta' => '2']);
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
$("#btBuscarConsWeb").click(function(){
	$.pjax.reload({
		container: '#PjaxGrilla', 
		data: {
			fechadesde: $('#filtrofchdesde').val(),
			fechahasta: $('#filtrofchhasta').val(),
			estado: $('#dlFiltroEstado').val(),
			tema: $('#dlFiltroTema').val(),
			documento: $('#txDoc').val()
		},
		type: 'POST',
		replace:false,
		push:false
	});
});
</script>