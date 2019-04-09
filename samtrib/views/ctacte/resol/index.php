<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;

use app\utils\db\utb;

$title = 'Resoluciones';
$this->params['breadcrumbs'][] = ['label' => 'Configuración', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = $title;
?>

<div class="site-auxedit">
	
    <h1 style="display:inline-block;"><?= $title; ?></h1>
    <?= Html::a('Nuevo', ['create'], ['class' => 'btn btn-success pull-right']) ?>								 	
	<div class="separador-horizontal"></div>

 		
	<?php
		Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);
	
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $dataProvider,
			'headerRowOptions' => ['class' => 'grilla'],
			'columns' => [
			        ['attribute'=>'resol_id','label' => 'ID' ,'contentOptions'=>['style'=>'width:3%;text-align:center;', 'class' => 'grilla']],  
            		['attribute'=>'nombre','label' => 'Nombre','contentOptions'=>['style'=>'width:23%;text-align:left;','class' => 'grilla']],
            		['attribute'=>'trib_id','label' => 'Tributo','contentOptions'=>['style'=>'width:5%;text-align:center;','class' => 'grilla']],
            		['label' => 'Nombre Redu','value'=>function($data){return utb::getCampo('trib','trib_id='.$data['trib_id'],'nombre_redu');}
            		,'contentOptions'=>['style'=>'width:10%;text-align:left;','class' => 'grilla']],
					['attribute'=>'perdesde','label' => 'Desde' ,'value'=>function($data){$anioDesde=substr($data['perdesde'],0,-3);
																					$cuotaDesde=substr($data['perdesde'],4,3);
																					return $anioDesde." - ".$cuotaDesde;}
					,'contentOptions'=>['style'=>'width:8%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'perhasta','label' => 'Hasta' ,'value'=>function($data){$anioHasta=substr($data['perhasta'],0,-3);
																					$cuotaHasta=substr($data['perhasta'],4,3);
																					return $anioHasta." - ".$cuotaHasta;}
            		,'contentOptions'=>['style'=>'width:8%;text-align:left;', 'class' => 'grilla']],           		
            		['attribute'=>'funcion','label' => 'Funcion', 'contentOptions'=>['style'=>'width:15%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'anual','label' => 'Anual' ,'contentOptions'=>['style'=>'width:4%;text-align:center;','class' => 'grilla']],
            		['attribute'=>'modif','label' => 'Modificacion', 'contentOptions'=>['style'=>'width:20%;text-align:right;', 'class' => 'grilla']],   
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:5%;text-align:center;','class'=>'grilla'],'template' => '{view} {update}',
            			'buttons'=>[
							'view' => function($url,$model,$key)
            						{	
            							
            							return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',$url);
            						},
            						
            				'update' => function($url){
            							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
            							}
						]
					],
			],
		]);
		
		Pjax::end();
	?>
</div>

<div style="margin-top:5px;">
<?php
if(isset($mensaje) && $mensaje != ''){
	
	echo Alert::widget([
		'id' => 'alertMensaje',
		'options' => ['class' => 'alert alert-success alert-dissmissible'],
		'body' => $mensaje
	]);
	
	?>
	<script type="text/javascript">
	setTimeout(function(){$("#alertMensaje").fadeOut();}, 5000);
	</script>
	<?php
}
?>
</div>