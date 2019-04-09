<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ctacte\CalcDesc;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;
use yii\data\ArrayDataProvider;

use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\widgets\ActiveForm */

$title = 'Descuentos';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

$model = new CalcDesc();

$request = Yii::$app->request;

?>

<style type="text/css">

.form-control{
	
	width : 100%;
}

.calc-desc-index div.row{
	height : auto;
	min-height: 17px !important;
}

#div_grilla {
	
	margin-bottom: 8px;
}

</style>

<div class="calc-desc-index" >

	<table border="0" width="100%" style='border-bottom:1px solid #ddd'>
		<tr>
			<td><h1><?= Html::encode($title) ?></h1></td>
			<td align="right"><?= Html::a('Nuevo Descuento', ['create'], ['class' => 'btn btn-success']) ?></td>
		</tr>
	</table>
	 	
	<?php	
   
	    //Bloque de comienzo de filtrar datos
	    $form = ActiveForm::begin(['id' => 'formFiltraDatos']);
    	
    	?>
    	
    	<div class="row" style="margin-top:5px">
    		<div class="col-xs-2">
    			<label>Filtrar por Tributo</label>
    		</div>
    		<div class="col-xs-6">
    	
		    	<?php
		    	
		    	//dropDownList que mostrará todos los tributos y filtrará la lista por tributo seleccionado
		    	echo $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib', 'trib_id', 'nombre', 0, "trib_id In (Select trib_id From calc_desc)"), [
					'id' => 'selectTrib', 
					'onchange'=>'ConsultaGrilla(this.value,"perdesde","asc")'])->label(false);
				?>
			</div>

		</div>
		
		<?php
   		ActiveForm::end();// fin bloque de form
    
	    Pjax::begin(['id' => 'idGrid']); //Comienza bloque de grilla
	    	
		 //Inicializo las variables que serán usadas para ordenar el GridView
		 $orden = Yii::$app->request->post('orden', 'trib_id, perdesde'); //Nombre de los campos según los cuales se ordenará la lista
		 $tr = Yii::$app->request->post('tr',0);
		 $or = Yii::$app->request->post('or',''); //Define el orden. Ascende (asc) o descendente (desc)
		 $criterio = " d.trib_id=".$tr;

     ?>
    	<script>$("#selectTrib option[value=<?=$tr?>]").attr("selected",true)</script>
   
   <!-- INICIO Div Grilla -->
   <div id="div_grilla">
    
	<?php	
	$dataProvider = new ArrayDataProvider(['allModels' => []]);
	
	$cargar = filter_var(Yii::$app->request->post('cargar', false), FILTER_VALIDATE_BOOLEAN);
	
	if($cargar) 
		$dataProvider = $model->buscarDescuento($criterio, $orden);
	
	echo GridView::widget([
	    'dataProvider' => $dataProvider,
	    'rowOptions' => function ($model,$key,$index,$grid) {return EventosGrilla($model);},
	    'columns' => [
	    //['class' => 'yii\grid\SerialColumn'],
	    
	    /**
	     * Determino las opciones de los campos de la BD que se ver�n en el index
	     * 'attribute' => '...' ATRIBUTO AL CUAL ME REFIERO
	     * 'label' => '...'  	NOMBRE CON EL QUE SE VERÁ EN EL INDEX
	     * 'raw' => '...'
	     * 'options' => '[...]' 
	     * 
	    */
	    
	            ['attribute'=>'anual','header' => Html::a('Anual','#',['onclick'=>'ConsultaGrilla("'.$tr.'","anual","'.$or.'")'])],
	            ['attribute'=>'perdesde','header' => Html::a('PerDesde','#',['onclick'=>'ConsultaGrilla("'.$tr.'","perdesde","'.$or.'")'])],
	            ['attribute'=>'perhasta','header' => Html::a('PerHasta','#',['onclick'=>'ConsultaGrilla("'.$tr.'","perhasta","'.$or.'")'])],
	            ['attribute'=>'cta_nom','header' => Html::a('Cuenta','#',['onclick'=>'ConsultaGrilla("'.$tr.'","cta_nom","'.$or.'")'])],
	            ['attribute'=>'desc1','header' => Html::a('Desc1','#',['onclick'=>'ConsultaGrilla("'.$tr.'","desc1","'.$or.'")'])],
	            ['attribute'=>'desc2','header' => Html::a('Desc2','#',['onclick'=>'ConsultaGrilla("'.$tr.'","desc2","'.$or.'")'])],
				['attribute'=>'modif','header' => Html::a('Modificación','#',['onclick'=>'ConsultaGrilla("'.$tr.'","modif","'.$or.'")'])],
	
	
	           ['class' => 'yii\grid\ActionColumn',
	            'buttons' => [	    
		            'view' => function($url, $model)
	            			{
	            				return null;
	            			},					
	    			'update' => function($url, $model, $key)
	    						{
	    							//$url .= '&desc_id=' . $model['desc_id'];
	    							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
	    						},
	    					
	    			'delete' => function($url, $model, $key)
	    						{            							
	    							$url = $url. '&accion=0';            							
	    							return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
	    							
	    						}
	    			]
	    		]
	    	]
	    ]);
				
	Pjax::end(); // fin bloque de la grilla
	
	?>
	
	</div>
	<!-- FIN Div Grilla -->
	
	
	<?php    

	//Bloque de comienzo de muestra de datos
	Pjax::begin(['id' => 'formDatos']);

		//Inicializo las variables
		$tab = Yii::$app->request->post( 'tab', 0 );
		$trib = Yii::$app->request->post( 'trib_id', 0 );
		$desc_id = Yii::$app->request->post( 'desc_id', 0 );
		
		$model = CalcDesc::findOne($desc_id);	//Le paso el id que será usado por el modelo para recuperar los datos
	    	
		if ($model == null) 
			$model = new CalcDesc();	//En caso de que el modelo no se haya instanciado
	  
	    //Seteo los valores de aniodesde, cuotadesde, aniohasta y cuotahasta
	    $model->aniodesde = substr($model->perdesde,0,4);
		$model->cuotadesde = substr($model->perdesde,4,2);
		        	
		$model->aniohasta = substr($model->perhasta,0,4);
		$model->cuotahasta = substr($model->perhasta,4,2);
	
		//Comienzo de código que separa en pestañas las diferentes vistas
	    echo Tabs :: widget ([
	    
    	 	'id' => 'TabDescuento',
			'items' => [ 
 				['label' => 'Datos', 
 				'content' => $this->render('_form',['model' => $model, 'consulta' => 1]),
 				'active' => ($tab==0) ?  true : false,
 				'options' => ['class'=>'tabItem']
 				],
 				['label' => 'Calcular' , 
 				'content' => $this->render('calcular',['model' => $model,'trib_id' => $trib]),
 				'active' => ($tab==1) ?  true : false,
 				'options' => ['class'=>'tabItem']
 				]
 			]
	    ]);

	Pjax::end();// fin bloque de form
 	
?>

</div>

<div style="margin-top: 8px">
<?php 
			
	if($mensaje != "")
	{ 
		
    	Alert::begin([
    		'id' => 'AlertaMensaje',
			'options' => [
        	'class' => 'alert-success',// : 'alert-info',
        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
    		],
		]);

		echo $mensaje;
				
		Alert::end();
				
		echo "<script>window.setTimeout(function() { $('#AlertaMensaje').alert('close'); }, 5000)</script>";
	 }

?>

</div>

<?php
 		
	//Función que carga los datos 
	function EventosGrilla ($m) 
	{
      $par = "desc_id:".$m['desc_id'].",trib_id:".$m['trib_id'];      
      
      return ['onclick' => '$.pjax.reload({container:"#formDatos",data:{'.$par.',tab:$("#TabDescuento li.active").index()},method:"POST"})'];
      
    }//Fin función que carga los datos

 ?>

<script type="text/javascript">
$(document).ready(function(){
	
	$("#selectTrib").trigger("change");
});
</script>