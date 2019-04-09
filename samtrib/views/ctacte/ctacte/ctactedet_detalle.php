<?php
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\helpers\Html;
use yii\helpers\BaseUrl;


//Pjax::begin(['id' => $selectorPjax, 'enableReplaceState' => false, 'enablePushState' => false]);
	echo Html::checkbox('ckBajas',$arrayCtaCteDet['baja'],[
						'id'=>'ckBajas',
						'label'=>'Visualizar los Conceptos dados de Baja',
						'onchange' => 'ControlesCtaCteDet("ckBajas")'
						]);
												
	echo GridView::widget([
		'dataProvider' => $dataProvider,
		'id' => 'GrillaDetalle',
		'summary' => false,
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => function ($model,$key,$index,$grid) 
        					{
        						$url = BaseUrl::toRoute(['//ctacte/ctacte/topera', 'top' => $model['topera'], 'comp' => $model['comprob']]);
        						
        						return 
        						[
        							'onclick' => 'cargarDetalle(' . $model['comprob'] . ', ' . $model['topera'] . ', "' . $model['operacion'] . '");',
									'ondblclick' => 'window.location = "'.$url.'"'
													
        						];
        					},
		'columns' => [

				['attribute'=>'fecha_format','contentOptions'=>['style'=>'width:40px;','class' => 'grilla'],'header' => 'Fecha'],
				['attribute'=>'operacion','contentOptions'=>['style'=>'width:80px;','class' => 'grilla'],'header' => 'Operacion'],
           		['attribute'=>'cta_nom','contentOptions'=>['style'=>'display:'.($accion == 'Desagrupar' ? 'block' : 'none'), 'class' => 'grilla'],'header' => 'Cuenta','headerOptions'=>['style'=>'display:'.($accion == 'Desagrupar' ? 'block' : 'none')]],
           		['attribute'=>'comprob','contentOptions'=>['style'=>'width:60px; text-align:center','class' => 'grilla'],'header' => 'Comprobante'],
           		['attribute'=>'debe', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:60px; text-align:right','class' => 'grilla'],'header' => 'Debe'],
           		['attribute'=>'haber', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:60px; text-align:right','class' => 'grilla'],'header' => 'Haber'],
           		['attribute'=>'est','contentOptions'=>['align'=>'center','style'=>'width:30px; text-align:center','class' => 'grilla'],'header' => 'Est.'],
           		['attribute'=>'modif','contentOptions'=>['style'=>'width:150px;','class' => 'grilla'],'header' => 'Modificación'],
    		]
    		
    	]); 

	Pjax::begin(['id' => 'pjaxDetalle', 'enableReplaceState' => false, 'enablePushState' => false]);
		echo Html::input('text', 'txTOpera', $arrayCtaCteDet['descripcionTipoOperacion'], ['class' => 'form-control solo-lectura','id'=>'txTOpera','style'=>'width:200px;','disabled'=>'true']); 
		echo Html::textarea('txDetalle', $arrayCtaCteDet['descripcionDetalle'], ['class' => 'form-control solo-lectura','id'=>'txDetalle','style'=>'width:750px;resize:none;height:50px;','disabled'=>'true']);
		
		
		if($arrayCtaCteDet['descripcionTipoOperacion'] !== '' && $model->caja_id > 0)
			echo Html::a('Operación', ['topera','top' => $arrayCtaCteDet['descripcionTipoOperacion'], 'comp' => $arrayCtaCteDet['descripcionComprobante']], ['class' => 'btn btn-primary']);
		
		
	Pjax::end();
//Pjax::end();
?>

<script type="text/javascript">
function cargarDetalle(comprobante, tipoOperacion, operacion){
	
	setTimeout(function(c, to, o){
		
		$.pjax.reload({
			container: "#pjaxDetalle",
			replace: false,
			push: false,
			type: "GET",
			data: {
				"comprob": c,
				"topera": to,
				"operacion": o,
				"cargarDescripcion": true
			}
		});
	},
	1000,
	comprobante,
	tipoOperacion,
	operacion
	);
}
</script>