<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\models\ctacte\CalcMm;
use \yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use app\utils\db\Fecha;
use yii\bootstrap\Alert;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Módulos Municipales';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;
?>

<style>

.div_grilla {
	
	margin-top: 5px;
	margin-bottom: 8px;
}
</style>

<div class="calc-mm-index">
	
	<table border="0" width="100%" style='border-bottom:1px solid #ddd'>
		<tr>
			<td align="left">
				<h1><?= Html::encode($title) ?></h1>
			</td>
			<td align="right">
				<?= Html::a('Nuevo', ['create'], ['class' => 'btn btn-success']); ?>
			</td>
		</tr>
	</table>
	
	   
    <!--GRILLA DE DATOS-->
    <div class="div_grilla">
    
   <?php
       
       Pjax::begin();
       
       echo GridView::widget([
	        'dataProvider' => $dataProvider,
	        'columns' => [
	            
	            ['attribute'=>'fchdesde','contentOptions'=>['style'=>'width:70px;'],'label' => 'Desde'],
	            ['attribute'=>'fchhasta','contentOptions'=>['style'=>'width:70px;'],'label' => 'Hasta'],
	            ['attribute'=>'valor','contentOptions'=>['style'=>'width:70px;'],'label' => 'Valor'],
	            ['attribute'=>'fchmod','contentOptions'=>['style'=>'width:70px;'],'label' => 'Modificación'],
	            [
					'class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:20px'],'template' => '{update} {delete}',
	            	'buttons'=>
	            	[
							'update' => function($url,$model,$key)
	            						{     
	            							$url = $url."&fchdesde=".$model['fchdesde']."&fchhasta=".$model['fchhasta'];
	            							return Html::a('<span class="glyphicon glyphicon-pencil"></span>',$url);
	            						},
	            			'delete' => function($url,$model,$key)
	            						{
	            							$url = $url."&fchdesde=".$model['fchdesde']."&fchhasta=".$model['fchhasta']."&valor=".$model['valor']."&consulta=2";
	            							return Html::a('<span class="glyphicon glyphicon-trash"></span>',$url,['data-confirm'=>"Esta seguro que desea eliminar ?"]);
	            						}
        			]
	            ]
            ]
    	]); 
    	
    	Pjax::end();
    	
    ?>
    
    </div>
    
    <?php
   
		//seccion de mensajes
		//$mensaje = Yii::$app->request->post( 'mensaje', '' );
	
		Alert::begin([
			'id' => 'MensajeInfoMm',
			'options' => [
				'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
				'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
			],
		]);	

		echo $mensaje;
		
		Alert::end();
		
		echo "<script>window.setTimeout(function() { $('#MensajeInfoMm').alert('close'); }, 5000)</script>"; 	

	?>
	</div>
