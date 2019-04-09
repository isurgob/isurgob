<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\models\ctacte\CalcInteres;
use yii\bootstrap\Alert;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



/* @var $tipoMensaje - Si existe:
 * 								- 'error': Si se ha producido un error y se debe obtener el mensaje desde $mensaje.
 * 								- 'exito': Si se ejecutó todo correctamente. */


 
/* @var $model - modelo CalcInteres en caso de que alguna vista del tab necesite un modelo para ejecutarse */

//se declaran e inicializan con null todas las variables necesarias

$title = 'Definición de Intereses';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = $title;

$model = isset($model) ? $model : new CalcInteres();

$tipoMensaje = isset($_GET['tipoMensaje']) ? $_GET['tipoMensaje'] : "";

?>

<style type="text/css">
.calc-interes-index .row{
	height : auto;
	min-height : 17px;
}

.grid td,
.grid th{
	width : auto;
}

.grid th,
.grid tr > td:last-of-type{
	text-align : center;
}


.grid table{
	width :auto;
}

.grid table tbody tr:hover{
	cursor : pointer;
}
</style>

<div class="calc-interes-index">
	
	<div class="row">
		<div class="col-xs-12">
	
			<h1 style="display:inline;">
    			<?php echo Html::encode($title); ?>
    		</h1>

    
			<?php echo Html::a('Nuevo interes', ['create'], ['class' => 'btn btn-success pull-right']); ?>
			
		</div>
	</div>
    
 	<div class="row" style="margin-top:25px;">
 		<div class="col-xs-12">
 	
 			<?php  
 	
			 	echo GridView::widget([
    				'dataProvider' => $dataProvider,
        
			        'options' => ['class' => 'grid'],
       
    				'columns' => [
        	
    					['attribute' => 'fchdesde', 'label' => 'Fecha desde'],
    					['attribute' => 'fchhasta', 'label' => 'Fecha hasta'],
    					['attribute' => 'indice', 'label' => 'Índice'],
    					['attribute' => 'modificacion', 'label' => 'Modificación'],
        	
    					['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:60px'],
       						'buttons' => [
      						//dejar para que no muestre nada por defecto
       						'view' => function()
       						{
       						return null;
       						},
       						'update' => function($url, $model, $key)
       						{
       							$url .= '&fchdesde=' . $model['fchdesde'] . '&fchhasta=' . $model['fchhasta'];
      							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
       						},
            					
       						'delete' => function($url, $model, $key)
       						{            							
       							$url .= '&fchdesde=' . $model['fchdesde'] . '&fchhasta=' . $model['fchhasta'];            							
       							return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
            							
       						}
       						]//fin buttons
    					],//fin class
    				]//fin columns
    			]);
	             
    		?>
    	</div>
    </div>
   
	
	<div class="row">
		<div class="col-xs-12">
    
    		<?php
        
    			Pjax::begin(['id' => 'formDatos']);
    
			   	if(isset($_POST['fchdesde']))
    				if(isset($_POST['fchhasta']))
    					$model = $model->findOne($_POST['fchdesde'], $_POST['fchhasta']);
    
    
	    		Tabs::begin([
	    
	    			'items' => [
	    				['label' => 'Calcular', 'active' => true, 'content' => $this->render('calcular')],
	    				['label' => 'Mínimo', 'active' => false, 'content' => $this->render('minimo')]
	    			],//fin items
	    	
	    			'itemOptions' => ['style' => 'border : 1px solid #DDD; border-radius : 0px 0px 8px 8px; padding : 15px; border-top : none;']
	    		]);
    
    			Tabs::end();
    
    			Pjax::end();
    
    		?>
 		</div>
	</div>

	<?php

 	//seccion de mensajes
	if($tipoMensaje != ''){
	
		switch($tipoMensaje)
		{
			case 'create' : $mensaje = 'El registro se ha creado correctamente.'; break;
			case 'update' : $mensaje = 'El registro se ha modificado correctamente.'; break;
			case 'delete' : $mensaje = 'El registro se ha eliminado correctamente.'; break;
			case 'minimo' : $mensaje = 'El mínimo de interes se ha guardado correctamente'; break;
			default : $mensaje = 'La acción se ha realizado con exito.';
		}
		
			
		
		echo Alert::widget([
			'options' => ['class' => 'alert-success alert-dissmisible'],
			'body' => $mensaje,
		]);	

	}
	?>
</div>
