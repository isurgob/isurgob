<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ctacte\CalcMulta;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View\ctacte */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Multas';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['//site/config']];
$this->params['breadcrumbs'][] = $title;

$model = new CalcMulta();
$criterio = "";
?>
<div class="multa-index">

    <table border='0' width='100%' style='border-bottom:1px solid #ddd'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
		<td align='right'><?= Html::a('Nueva Multa', ['create'], ['class' => 'btn btn-success']) ?></td>
    </tr>
    <tr>
    	<td colspan='2'>
    	<?php
    	if (isset($mensaje) == null) $mensaje = '';
    	Alert::begin([
    		'id' => 'AlertaMulta',
			'options' => [
        	'class' => (isset($_GET['m']) and $_GET['m'] == 1) ? 'alert-success' : 'alert-info',
        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
    		],
		]);

		if ($mensaje !== '') echo $mensaje;
		
		Alert::end();
				
		if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaMulta').alert('close'); }, 5000)</script>";
    		
    	?>
    	</td>
    </tr>
	</table>
	
	<?php 
    	$form = ActiveForm::begin(['id'=>'form-multa-filtro']);
    	    	
		echo $form->field($model, 'trib_id')->dropDownList(utb::getAux('trib','trib_id','nombre',0,'trib_id In (Select trib_id From calc_multa)'),[
			'id'=>'selectTrib',			
			'onchange'=>'ConsultaGrilla(this.value,"trib_nom","asc")'])->label('Filtrar por Tributo');
    	
    	ActiveForm::end();
    
    	Pjax::begin(['id' => 'idGrid']);// comienza bloque de grilla
    	 
    	 $criterio = "";
    	 $orden = "trib_id,PerDesde";
    	 $tr='';
    	 $or='';
    	     	  
    	 if (isset($_POST['tr']) and $_POST['tr'] !== null and $_POST['tr'] !=='') 
    	 {
    	 	$criterio = "trib_id=".$_POST['tr'];
    	 	$tr = $_POST['tr'];
    	 }
    	 if (isset($_POST['orden']) and  $_POST['orden'] !== null and $_POST['orden'] !=='') $orden = $_POST['orden'];
    	 if (isset($_POST['or']) and  $_POST['or'] !== null and $_POST['or'] !=='') $or = $_POST['or'];
    	
    ?>
    	<script>$("#selectTrib option[value=<?=$tr?>]").attr("selected",true)</script>
    
    <?php
    
    $cargar = filter_var(Yii::$app->request->post('cargar', false), FILTER_VALIDATE_BOOLEAN);
    
    $models = new ArrayDataProvider(['allModels' => []]);
    if($cargar) $models = $model->BuscarMulta($criterio,$orden);
    
    
    
    echo GridView::widget([
		'dataProvider' => $models,
        'rowOptions' => function ($model,$key,$index,$grid) {return EventosGrilla($model);},
		'columns' => [

			['attribute'=>'perdesde_format','contentOptions'=>['style'=>'width:70px;'],'header' => Html::a('Desde','#',['onclick'=>'ConsultaGrilla("'.$tr.'","perdesde","'.$or.'")'])],
            ['attribute'=>'perhasta_format','contentOptions'=>['style'=>'width:70px'],'header' => Html::a('Hasta','#',['onclick'=>'ConsultaGrilla("'.$tr.'","perhasta","'.$or.'")'])],
            ['attribute'=>'montodesde','contentOptions'=>['align'=>'right'],'header' => Html::a('Monto Desde','#',['onclick'=>'ConsultaGrilla("'.$tr.'","montodesde","'.$or.'")']), 'options' => ['style' => 'width:50px;']],
            ['attribute'=>'montohasta','contentOptions'=>['align'=>'right'],'header' => Html::a('Monto Hasta','#',['onclick'=>'ConsultaGrilla("'.$tr.'","montohasta","'.$or.'")']), 'options' => ['style' => 'width:50px;']],
            ['attribute'=>'tipo_nom','header' => Html::a('Tipo','#',['onclick'=>'ConsultaGrilla("'.$tr.'","tcomer_nom","'.$or.'")'])],
            ['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:45px'],'template' => '{update} {delete}',
            'buttons'=>[
						'update' => function($url,$model,$key)
            						{   //PASO SOLAMENTE EL ID DE LA TABLA (fchdesde) EN VEZ DE (perdesde)  
            							$url = $url."&trib_id=".$model['trib_id']."&perdesde=".$model['perdesde']."&perhasta=".$model['perhasta'];
            							$url = $url."&tipo=".$model['tipo']."&montodesde=".$model['montodesde']."&montohasta=".$model['montohasta'];
            							return Html::a('<span class="glyphicon glyphicon-pencil"></span>',$url);
            						},
            			'delete' => function($url,$model,$key)
            						{
            							$url = $url."&accion=0"."&trib_id=".$model['trib_id']."&perdesde=".$model['perdesde']."&perhasta=".$model['perhasta'];
            							$url = $url."&tipo=".$model['tipo']."&montodesde=".$model['montodesde']."&montohasta=".$model['montohasta'];
            							return Html::a('<span class="glyphicon glyphicon-trash"></span>',$url);
            						}
            			]
            ],
        ],
    ]); ?>
    	
    	
	<?php 
		Pjax::end(); // fin bloque de la grilla
		
		
		// Bloque que muestra los datos del formulario en modo de consulta 
    	Pjax::begin(['id' => 'formDatos']);
    	
    	$trib=0; $perd=0;$perh=0;$montod=0;$montoh=0;$tipo=0;$tab=0;
    	
    	if (isset($_POST['trib_id'])) $trib=$_POST['trib_id'];
    	if (isset($_POST['perdesde'])) $perd=$_POST['perdesde'];
    	if (isset($_POST['perhasta'])) $perh=$_POST['perhasta'];
    	if (isset($_POST['montodesde'])) $montod=$_POST['montodesde'];
    	if (isset($_POST['montohasta'])) $montoh=$_POST['montohasta'];
    	if (isset($_POST['tipo'])) $tipo=$_POST['tipo'];
    	if (isset($_POST['tab'])) $tab=$_POST['tab'];
    	
    	$model = CalcMulta::findOne(['trib_id' => $trib, 'perdesde' => $perd,'perhasta' => $perh, 'tipo' => $tipo, 
									'montodesde' => $montod,'montohasta' => $montoh]);
    	
    	if ($model == null) 
    	{
    		$model = new CalcMulta();	
    	}else {
    		$model->aniodesde = substr($model->perdesde,0,4);
        	$model->cuotadesde = substr($model->perdesde,4,3);
        	        	
        	$model->aniohasta = substr($model->perhasta,0,4);
        	$model->cuotahasta = substr($model->perhasta,4,3);	
        	
        	$model->modif = utb::getFormatoModif($model->usrmod,$model->fchmod);

    	}
    	   	      	
    	    	
    	echo Tabs :: widget ([ 
 			'id' => 'TabMulta',
			'items' => [ 
 				['label' => 'Datos', 
 				'content' => $this->render('_form',['model' => $model,'consulta' => 1]),
 				'active' => ($tab==0) ?  true : false,
 				'options' => ['class'=>'tabItem']
 				],
 				['label' => 'Calcular' , 
 				'content' => $this->render('calcular',['trib_id' => $trib]),
 				'active' => ($tab==1) ?  true : false,
 				'options' => ['class'=>'tabItem']
 				]
 			]]);   
 		
    	Pjax::end();// fin bloque de form

    ?>
		
</div>

<?php 
	function EventosGrilla ($m) 
	{
         //$trib_nom = $m->getTribNom($m['trib_id']);        
       
       $par = "trib_id:".$m['trib_id'].",perdesde:".$m['perdesde'].",perhasta:".$m['perhasta'].",tipo:".$m['tipo'].",montodesde:".$m['montodesde'].",montohasta:".$m['montohasta'];     
      
      return ['onclick' => '$.pjax.reload({container:"#formDatos",data:{'.$par.',tab:$("#TabMulta li.active").index()},method:"POST"})'];
                  
    }
?>
<script type="text/javascript">
$(document).ready(function(){
	$("#selectTrib").change();
});
</script>
