<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use yii\grid\GridView;
use app\models\objeto\Persona;

$this->title = 'Listado de Personas Reemplazadas';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="persona-view">	
	<table width='100%'>
	<tr>
		<td><h1 id='h1titulo'><?= $this->title ?></h1></td>
		<td align='right'><?= Html::a('Nuevo', ['reemplaza', 'list' => '0'], ['class' => 'btn btn-success']) ?></td>
	</tr>
	</table>
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
	<?php 
		if (isset($mensaje) == null) $mensaje = '';
    	Alert::begin([
    		'id' => 'AlertaPersona',
			'options' => [
        	'class' => (isset($_GET['m']) and $_GET['m'] == 1) ? 'alert-success' : 'alert-info',
        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
    		],
		]);

		if ($mensaje !== '') echo $mensaje;
				
		Alert::end();
				
		if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaPersona').alert('close'); }, 5000)</script>";
	?>      
	<table border='0'>
	<tr>
		<td><label>Objeto: </label><?= Html::input('text', 'txNum', null, ['class' => 'form-control','id'=>'txNum','maxlength'=>'8','style'=>'width:70px',]); ?></td>
		<td>&nbsp;&nbsp;<label>Nombre: </label><?= Html::input('text', 'txNombre', null, ['class' => 'form-control','id'=>'txNombre','maxlength'=>'50','style'=>'width:200px;text-transform:uppercase;']); ?></td>
		<td>&nbsp;&nbsp;<label>Documento: </label><?= Html::input('text', 'txDoc', null, ['class' => 'form-control','id'=>'txDoc','maxlength'=>'11','style'=>'width:100px']); ?></td>
		<td>&nbsp;&nbsp;<label>Cuit: </label><?= Html::input('text', 'txCuit', null, ['class' => 'form-control','id'=>'txCuit','maxlength'=>'11','style'=>'width:100px']); ?></td>
	</tr>
	<tr>
	<td colspan='4'>
		<?= Html::Button('Buscar',['class' => 'btn btn-success', 'onClick' => 'Buscar();'])?>
	</td>
</tr>
	</table>
	</div>
	
	<?php
	Pjax::begin(['id' => 'PjaxBuscar']);
    	$num = trim(Yii::$app->request->post('num', '')); 
    	$nombre = trim(Yii::$app->request->post('nombre', '')); 
    	$ndoc = intval(Yii::$app->request->post('ndoc', 0)); 
    	$ncuit = trim(Yii::$app->request->post('ncuit'));
    	$dataprovider = (new Persona)->PersonaReemplazaLis($num,$nombre,$ndoc,$ncuit);
		
		echo GridView::widget([
			'id' => 'GrillaListPers',
			'dataProvider' => $dataprovider,
			'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'columns' => [
	        		['attribute'=>'oldnum','label' => 'Obj.Ant', 'contentOptions'=>['style'=>'text-align:center;']],
	        		['attribute'=>'oldndoc','label' => 'NDoc Ant', 'contentOptions'=>['style'=>'text-align:right']],
	        		['attribute'=>'oldcuit','label' => 'CUIT Ant', 'contentOptions'=>['style'=>'text-align:right']],
	        		['attribute'=>'oldnombre','label' => 'Nombre Anterior', 'contentOptions'=>['style'=>'text-align:left; width:200px;']],
	        		['attribute'=>'newnum','label' => 'Obj.Act', 'contentOptions'=>['style'=>'text-align:center']],
	        		['attribute'=>'newndoc','label' => 'NDoc Act', 'contentOptions'=>['style'=>'text-align:right']],
	        		['attribute'=>'newcuit','label' => 'CUIT Act', 'contentOptions'=>['style'=>'text-align:right']],
	        		['attribute'=>'newnombre','label' => 'Nombre Actual', 'contentOptions'=>['style'=>'text-align:left; width:200px;']],
	        		['attribute'=>'modif','label' => 'Usuario-Fecha', 'contentOptions'=>['style'=>'text-align:left']],
	        		            		
	        		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:20px'],'template' => '{delete}',
			        		'buttons'=>[
									'delete' => function($url,$model,$key)
		            						{
		            							return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
		            									['reemplazaanula', 'oldnum' => $model['oldnum']], ['class' => 'bt-buscar-label']);
		            						}
		            			]
	        		],
	        		           		            		
	        	],
		]); 
	
	Pjax::end();
	
	if(isset($error) and $error !== '')
	{  
		echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
		$error = '';
	} 
	?>
</div>

<script>
function Buscar()
{
	$.pjax.reload({
		container:"#PjaxBuscar",
		data:{
				num:$("#txNum").val(),
				nombre:$("#txNombre").val(),
				ndoc:$("#txDoc").val(),
				ncuit:$("#txCuit").val()
			},
		method:"POST"
	});
}
</script>
