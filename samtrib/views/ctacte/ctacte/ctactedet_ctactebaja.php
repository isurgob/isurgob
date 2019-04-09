<?php
use yii\grid\GridView;
use yii\helpers\Html;
use \yii\widgets\Pjax;
use app\models\ctacte\Ctacte;

$model= new CtaCte();

	echo GridView::widget([
		'dataProvider' => $dataProviderCtaCteBaja,
		'id' => 'GrillaCtacteBaja',
		'summary' => false,
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => function ($model,$key,$index,$grid) 
        					{
        						return 
        						[
									'onclick' => '$.pjax.reload({container:"#ActLiqBaja",' .
											'data:{ctacte_id:'.$model['ctacte_id'].',orden:'.$model['orden'].'},' .
											'method:"POST"})'
													
        						];
        					},
		'columns' => [

				['attribute'=>'orden','contentOptions'=>['style'=>'width:40px;text-align:center','class' => 'grilla'],'header' => 'Orden'],
				['attribute'=>'tipo','contentOptions'=>['style'=>'width:90px;','class' => 'grilla'],'header' => 'Tipo'],
           		['attribute'=>'ucm','contentOptions'=>['style'=>'width:60px; text-align:center','class' => 'grilla'],'header' => 'UCM'],
           		['attribute'=>'nominal', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:70px;text-align:right','class' => 'grilla'],'header' => 'Nominal'],
           		['attribute'=>'fchemi', 'format' => ['date', 'php:d/m/Y'], 'contentOptions'=>['style'=>'width:60px;text-align:center','class' => 'grilla'],'header' => 'FchEmi'],
           		['attribute'=>'expe','contentOptions'=>['style'=>'width:60px;','class' => 'grilla'],'header' => 'Expe'],
           		['attribute'=>'obs','contentOptions'=>['style'=>'width:200px;','class' => 'grilla'],'header' => 'Observación'],
           		['attribute'=>'baja', 'contentOptions'=>['style'=>'width:100px;','class' => 'grilla'],'header' => 'Usr y Fecha'],
    		]
    		
    	]); 
    	
    ?>
    <h4><b>Detalle Liquidaci&oacute;n</b></h4>
    <?php
    
    	
    Pjax::begin(['id' => 'ActLiqBaja']);
    
    $ctacte_id = 0;
    $orden = 0;
    if (isset($_POST['ctacte_id'])) $ctacte_id = $_POST['ctacte_id'];
    if (isset($_POST['orden'])) $orden = $_POST['orden'];
    
    echo GridView::widget([
		'dataProvider' => $model->CtaCteLiqBaja($ctacte_id,$orden),
		'id' => 'GrillaCtacteLiqBaja',
		'summary' => false,
		'headerRowOptions' => ['class' => 'grilla'],
		'columns' => [

				['attribute'=>'item_id','contentOptions'=>['style'=>'width:40px;text-align:center','class' => 'grilla'],'header' => 'Cod.'],
				['attribute'=>'item_nom','contentOptions'=>['style'=>'width:200px;','class' => 'grilla'],'header' => 'Item'],
           		['attribute'=>'item_monto', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:80px; text-align:right','class' => 'grilla'],'header' => 'Monto'],
           		['attribute'=>'detalle','contentOptions'=>['style'=>'width:300px;','class' => 'grilla'],'header' => 'Detalle'],
           		
    		]
    		
    	]); 
    Pjax::end();		
?>    		