<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\config\RodadoVal;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\web\Session;
use yii\bootstrap\Alert;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$title = 'Rodado Valores';
$this->params['breadcrumbs'][] = ['label' => 'Configuración','url' => ['//site/config']];
$this->params['breadcrumbs'][] = $title;

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

if (isset($model) == null) $model = new RodadoVal();
?>

<style type="text/css">

.asterisco{
	color:red;
	float:left;
	margin-left:100px;
}

</style>

<div class="site-auxedit">
	<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
    	<td align='right'>
    		<?php			
    			if (utb::getExisteProceso(3286))
    				echo Html::button('Nuevo', ['class' => 'btn btn-success','onclick'=>'MostrarModalValorEdit(0,0,0,0,0,0)']) 								 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'>
    	<?php
    		if (isset($mensaje) == null) $mensaje = '';
    			Alert::begin([
    				'id' => 'AlertaValorEdit',
					'options' => [
        			'class' => (isset($_GET['m']) and $_GET['m'] == 1) ? 'alert-success' : 'alert-info',
        			'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
    				],
				]);

				if ($mensaje !== '') echo $mensaje;
				
				Alert::end();
				
				if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaValorEdit').alert('close'); }, 5000)</script>";
    	?>	
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    
    <?php  $form = ActiveForm::begin(); ?>
    <table border='0'>
	    <tr>
	    	<td width='90px'><label>Año Valuatorio:</label></td>
		    <td width='55px'><?= Html::input('text', 'anio_val',"",['id' => 'anio_val','class' => 'form-control','maxlength'=>'4' ,'style' => 'width:50px;', 'disabled' => false]); ?> </td>
		    <td width='70px' align='right'><label>Categoria:</label></td>
		    <td width='120px'><?=  Html::dropDownList('selectCat',"",utb::getAux('rodado_tcat','cod',"(nombre || ' (Grupo:' || gru::varchar || ')')"),['id'=>'cat','class' => 'form-control', 'prompt' => '']);?></td>
		    <td width='40px' align='right'><label>Año:</label></td>
		    <td width='55px'><?= Html::input('text', 'anio',"",['id' => 'anio','class' => 'form-control','maxlength'=>'4' ,'style' => 'width:50px;', 'disabled' => false]); ?> </td>
			<td width='70px'><?php     
				echo Html::Button('Buscar', ['class' => 'btn btn-primary', 'id' => 'btnBuscar','style'=>'float:right;',
				'onclick' => '$.pjax.reload({container:"#idGrid",' .
						'						  data:{' .
		'												anio_val:$("#anio_val").val(),' .												
		'												cat:$("#cat").val(),' .
		'												anio:$("#anio").val(),' .
		'												bandera:1' .
		'												},' .
		'												method:"POST"' .
		'									})']) 
	    	?></td>		
		</tr>
    </table>
	<br/>
     <?php ActiveForm::end(); 
    
	Pjax::begin(['id' => 'idGrid']);// comienza bloque de grilla
			
		if(isset($_POST['bandera'])){			
			$_SESSION['anio_val'] = $_POST['anio_val'];
		 	$_SESSION['cat'] = $_POST['cat'];
		 	$_SESSION['anio'] = $_POST['anio'];	 	
		 }else{
			$_SESSION['anio_val'] = '2015';
		 	$_SESSION['cat'] = '';
		 	$_SESSION['anio'] = '';		 	
		 }

		echo "<script> $(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
		}); </script>"; 
		
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $model->buscarRodadoVal($_SESSION['anio_val'],$_SESSION['cat'],$_SESSION['anio']),
			'headerRowOptions' => ['class' => 'grilla'],
			'pager' => false,
			'columns' => [
			        ['attribute'=>'anioval','label' => 'Año Valuatorio' ,'contentOptions'=>['style'=>'width:12%;text-align:left;', 'class' => 'grilla']],  
            		['attribute'=>'gru','label' => 'Grupo','contentOptions'=>['style'=>'width:8%;text-align:left;','class' => 'grilla']],
					['attribute'=>'anio','label' => 'Año' ,'contentOptions'=>['style'=>'width:8%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'pesodesde','label' => 'Peso Desde' ,'contentOptions'=>['style'=>'width:13%;text-align:right;', 'class' => 'grilla']],           		
            		['attribute'=>'pesohasta','label' => 'Peso Hasta', 'contentOptions'=>['style'=>'width:13%;text-align:right;', 'class' => 'grilla']],
            		['attribute'=>'valor','label' => 'Valor' ,'contentOptions'=>['style'=>'width:13%;text-align:right;','class' => 'grilla']],
            		['attribute'=>'fchmod','label' => 'Modificacion', 'contentOptions'=>['style'=>'width:27%;text-align:right;', 'class' => 'grilla']],   
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:6%;text-align:center;','class'=>'grilla'],'template' => '{view} '.(utb::getExisteProceso(3286) ? '{update} {delete}' : ''),
            			'buttons'=>[
            				'view' => function($url,$model,$key)
            						{
            							return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',null,
            								['onclick'=>'MostrarModalValorEdit(1,'.$model['anioval'].','.$model['gru'].','.$model['anio'].','.$model['pesodesde'].','.$model['pesohasta'].')']);
            						},
							'update' => function($url,$model,$key)
            						{
            							return Html::a('<span class="glyphicon glyphicon-pencil"></span>',null,
            								['onclick'=>'MostrarModalValorEdit(3,'.$model['anioval'].','.$model['gru'].','.$model['anio'].','.$model['pesodesde'].','.$model['pesohasta'].')']);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::a('<span class="glyphicon glyphicon-trash"></span>',
            								['delete','anioval'=>$model['anioval'],'gru'=>$model['gru'],'anio'=>$model['anio'],'pesodesde'=>$model['pesodesde'],'pesohasta'=>$model['pesohasta']]);
									}
						]
					],
			],
		]); 	 
														
		 Pjax::end(); // fin bloque de la grilla
	?>
</div><!-- site-auxedit -->


<?php
Modal::begin([
	'id' => 'ModalValorEdit',
	'header' => '<h2>Rodado Valores</h2>',
	'closeButton' => [
          'label' => '<b>&times;</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ],
	'size' => 'modal-sm'
	]);
	
	Pjax::begin(['id'=>'PjaxModelEdit']);
		$anioval = (isset($_POST['anioval']) ? $_POST['anioval'] : 0);
		$gru = (isset($_POST['gru']) ? $_POST['gru'] : 0);
		$anio = (isset($_POST['anio']) ? $_POST['anio'] : 0);
		$pesodesde = (isset($_POST['pesodesde']) ? $_POST['pesodesde'] : 0);
		$pesohasta = (isset($_POST['pesohasta']) ? $_POST['pesohasta'] : 0);
		$consulta = (isset($_POST['consulta']) ? $_POST['consulta'] : 0);
		
		if ($consulta == 0) 
			$model = new RodadoVal();
		else
			$model = RodadoVal::findOne(['anioval'=>$anioval,'gru'=>$gru,'anio'=>$anio,'pesodesde'=>$pesodesde,'pesohasta'=>$pesohasta]);
		
		echo $this->render('valoredit',['model'=>$model,'consulta'=>$consulta]);
	Pjax::end();
	
Modal::end();	
?>

<script>
function MostrarModalValorEdit(c,av,g,a,pd,ph)
{
	$.pjax.reload({container:"#PjaxModelEdit",
					data:{
							consulta:c,
							anioval:av,
							gru:g,
							anio:a,
							pesodesde:pd,
							pesohasta:ph
						},
					method:"POST"});
					
	$("#PjaxModelEdit").on("pjax:end", function(){ 
		$("#ModalValorEdit").modal();
		$("#PjaxModelEdit").off("pjax:end");
	});
}
</script>
