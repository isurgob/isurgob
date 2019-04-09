<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
?>
<style>
#IntimaBuscar .modal-content{
	width:900px !important;
}
</style>
<?php
$form = ActiveForm::begin([
			'id'=>'frmBuscaLote',
			'action'=>['buscar'],
			'options' => ['onsubmit' => 'btAceptar($("input:radio:checked").val())']]);

		echo Html::input('hidden','txID',null,['id'=>'txID']);

?>

<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
	<tr>
		<td>
			<?= GridView::widget([
				'id' => 'GrillaInfoIntima',
				'dataProvider' => $model->getBuscar(),
				'summaryOptions' => ['class' => 'hidden'],
		        'rowOptions' => function ($model,$key,$index,$grid) 
				{					
					return 
					[
						'ondblclick'=>'$("#btnAceptarClick").click()',
						'onclick'=>'$(this).find("input:radio").prop("checked", true)',
					];
				},
				'columns' => [
						['attribute'=>'lote_id','header' => 'Lote', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'titulo','header' => 'Título','contentOptions'=>['width'=>'200px']],
						['attribute'=>'tipo_nom','header' => 'Tipo'],
						['attribute'=>'cant','header' => 'Cant.', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'deuda','header' => 'Deuda', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
						['attribute'=>'alta','header' => 'Alta', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
						['attribute'=>'aprobacion','header' => 'Aprobación', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
						[
							'content'=> function($model, $key, $index, $column) {
								return Html::radio('radioLista',false,[
									'class'=>'form-control',
									'id'=>'radioLista'.$model["lote_id"],
									'value'=>$model['lote_id'],
									'style' => 'margin:0px;height:15px']);}], 	
						
						
		        	],
			]); ?>
		</td>
	</tr>
</table>


<table style="margin-top:10px">
	<tr>
		<td>
			<?= Html::submitButton('Aceptar',['style'=>'color:#fff;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px','class'=>'btn btn-success', 'id'=>'btnAceptarClick']); ?>
			<?= Html::Button('Cancelar',['style'=>'color:#fff;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px','class'=>'btn btn-primary', 'onclick'=>'$("#IntimaBuscar").modal("hide")']); ?>
		</td>
	</tr>
</table>

<?php
	ActiveForm::end();
?>
<script>
function btAceptar(lote)
{
	if (lote != '')
	{
		$("#txID").val(lote);
		return true;
	}		
	
	return false;
}
</script>
