<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;

/**
 * Llegan:
 * 	
 * 		$dataProvider => Resultado de la búsqueda.
 */
//Grilla para convenios

?>

<div style="margin-top:5px;margin-bottom:5px">

<?php

Pjax::begin();

/* trib_nom,obj_id,subcta,respndoc,est,per_desde,per_hasta,fchalta,fchbaja*/
	echo GridView::widget([
			'id' => 'GrillaConvenios',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProvider,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'resp','header' => 'Responsable', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
					['attribute'=>'plan_id','header' => 'Plan', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'est_nom','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'cuotas','header' => 'Cuotas', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'fchalta','header' => 'Alta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'ultvenc','header' => 'Ult.Venc', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
	        	],
		]);
		
Pjax::end();
	
$array = $dataProvider->getModels();
$vigentes = 0;
$novigentes = 0;

foreach ($array as $arreglo)
{
	if ($arreglo['est'] == 1)
		$vigentes++;
	else
		$novigentes++;
}

?> 

</div>
<table width="100%">
	<tr>
		<td align="right">
			<label>Cantidad Vigentes</label>
			<?= Html::input('text','txCantVigentes',$vigentes,['id'=>'convenio_txCantVigentes','disabled'=>true,'style'=>'background:#E6E6FA;text-align:right;width:50px','class'=>'form-control']); ?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<label>Cantidad No Vigentes</label>
			<?= Html::input('text','txCantNoVigentes',$novigentes,['id'=>'convenio_txCantNoVigentes','disabled'=>true,'style'=>'background:#E6E6FA;text-align:right;width:50px','class'=>'form-control']); ?>
		</td>
	</tr>
</table>
