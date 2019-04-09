<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;

/**
 * Llegan:
 * 	
 * 		$dataProvider => Resultado de la búsqueda.
 */
//Grilla para adhesiones

?>

<table width="100%" >
	<tr>
		<td align="right">
			<?= Html::a('Nuevo',['adhesion','consulta'=>0],['id' => 'adhe_btNuevo','class'=>'btn btn-primary']); ?>
		</td>
	</tr>
</table>

<div style="margin-top:5px;margin-bottom:5px">
<?php

Pjax::begin();

	echo GridView::widget([
			'id' => 'GrillaAdhesiones',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProvider,
			'summaryOptions' => ['class' => 'hidden'],
			'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
			'columns' => [
					['attribute'=>'caja_id','header' => 'Caja', 'contentOptions'=>['style'=>'text-align:left','width'=>'40px']],
					['attribute'=>'trib_nom','header' => 'Tributo', 'contentOptions'=>['style'=>'text-align:left','width'=>'40px']],
					['attribute'=>'obj_id','header' => 'Ojeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'subcta','header' => 'Cta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'resp','header' => 'Responsable', 'contentOptions'=>['style'=>'text-align:left','width'=>'120px']],
					['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'perdesde','header' => 'Desde', 'contentOptions'=>['style'=>'text-align:right','width'=>'50px']],
					['attribute'=>'perhasta','header' => 'Hasta', 'contentOptions'=>['style'=>'text-align:right','width'=>'50px']],
					['attribute'=>'fchalta','header' => 'Alta', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'fchbaja','header' => 'Baja', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['class' => 'yii\grid\ActionColumn','template'=>'{print}{view}{update}{delete}',
						'contentOptions'=>['style'=>'text-align:left','width'=>'60px'],
			            'buttons' => [	    
				            'print' => function($url, $model, $key)
			            			{
			            				return Html::a('<span class="glyphicon glyphicon-print"></span>&nbsp;&nbsp;&nbsp;', ['imprimiradhe','id'=>$model['adh_id']],['target'=>'_black','data-pjax' => "0"]);
			            			},	
							'view' => function($url, $model, $key)
			            			{
			            				return Html::a('<span class="glyphicon glyphicon-eye-open"></span>&nbsp;', ['adhesion','id'=>$model['adh_id'],'consulta'=>1]);
			            			},					
			    			'update' => function($url, $model, $key)
			    						{
			    							if ($model['est'] == 'A')
			    							return Html::a('<span class="glyphicon glyphicon-pencil"></span>&nbsp;', ['adhesion','id'=>$model['adh_id'],'consulta'=>3]);
			    						},
			    					
			    			'delete' => function($url, $model, $key)
			    						{   
			    							if ($model['est'] == 'A')         							
			    								return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['adhesion','id'=>$model['adh_id'],'consulta'=>2]);
			    							
			    						}
			    			]
		    		]	
	        	],
		]);
		
Pjax::end();

?>

</div>

<?php
$array = $dataProvider->getModels();
$activas = 0;
$bajas = 0;

foreach ($array as $arreglo)
{
	if ($arreglo['est'] == 'A')
		$activas++;
	else
		$bajas++;
}
	
?> 

<table width="100%">
	<tr>
		<td align="right">
			<label>Cantidad Activas</label>
			<?= Html::input('text','txCantActivas',$activas,['id'=>'adhesion_txCantActivas','disabled'=>true,'style'=>'background:#E6E6FA;text-align:right;width:50px','class'=>'form-control']); ?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<label>Cantidad Bajas</label>
			<?= Html::input('text','txCantBajas',$bajas,['id'=>'adhesion_txCantBajas','disabled'=>true,'style'=>'background:#E6E6FA;text-align:right;width:50px','class'=>'form-control']); ?>
		</td>
	</tr>
</table>