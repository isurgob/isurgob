<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$accion= isset($accion) ? $accion : 1;


$title = 'Coeficientes de Mejora';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

?>
<div class="val-mej-index">

    <h1 id='titulo'><?= Html::encode($title) ?></h1>
	
	<?php 
		if( isset(Yii::$app->session['mensaje']) )
		{

			Alert::begin([
				'id' => 'Mensaje',
				'options' => [
				'class' => 'alert-success',
				],
			]);	

				echo Yii::$app->session->getFlash('mensaje');
			
			Alert::end();
			
			echo "<script>window.setTimeout(function() { $('#Mensaje').alert('close'); }, 5000)</script>"; 

		}
	?>

    <p style='float:right;'>
        <?= Html::Button('Nuevo', ['id'=>'btnNuevo','class' => 'btn btn-success','onclick'=> 'Editar(0,0,0,0)']) ?>
    </p>

    <?= GridView::widget([
    	'id'=>'Grilla',
        'dataProvider' => $dataProvider,
        'summary' => false,
        'headerRowOptions' => ['class' => 'grilla'],
                'rowOptions' => function ($model,$key,$index,$grid){
                					return ['onclick' => 'Editar(1,"' . $model['cat'] . '","' . $model['est'] . '","' . $model['ant'] . '");'];
        					},	
        'columns' => [

			['attribute' => 'cat', 'header' => 'Categoria', 'contentOptions' => ['style' => 'width:60px','class'=>'grilla']],
        	['attribute' => 'est', 'header' => 'Estado', 'contentOptions' => ['style' => 'width:60px','class'=>'grilla']],
        	['attribute' => 'ant', 'header' => 'Antigüedad', 'contentOptions' => ['style' => 'width:80px','class'=>'grilla']],
        	['attribute' => 'coef', 'header' => 'Coeficiente', 'contentOptions' => ['style' => 'width:80px','class'=>'grilla']],

            ['class' => 'yii\grid\ActionColumn', 'contentOptions'=>['style'=>'width:50px;','align'=>'center','class'=>'grilla'], 'template' => '{update}{delete}',
            
            
            	'buttons' => [   
            	
	    			'update' => function($url,$model,$key)
							{
								return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
								[
									'class' => 'bt-buscar-label',
									'style' => 'color:#337ab7;',
									'onclick' => 'Editar(3,"' . $model['cat'] . '","' . $model['est'] . '","' . $model['ant'] . '");'
									]	
								);									
							},
	    							
	    			'delete' => function($url,$model,$key)
				  	 	    {   

								return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            				  	 			[
            				  	 					'class' => 'bt-buscar-label',
													'style' => 'color:#337ab7;',
													'onclick' => 'Editar(2,"' . $model['cat'] . '","' . $model['est'] . '","' . $model['ant'] . '");'
						
														]);	            														
									   				 }	
				            					],
			            
			            					],
			        					],
			   					 ]); ?>	

</div>

<?php 
$form = ActiveForm::begin(['action' => ['valcoefmejabm'], 'fieldConfig' => ['template' => "{label}{input}"]]);

Pjax::begin(['id' => 'PjaxABM']);
	echo Html::hiddenInput('txAccion', $accion, ['id' => 'txAccion']);
?>

<div class="form" style='padding:10px; margin-top:5px'>
		<table border='0'>
			<tr>
				<td> <label>Categoría:</label> </td>
				<td>
					<?= $form->field($model, 'cat',['options' => ['id' => 'cat']])->textInput(['maxlength' => 2,'style' => 'width:40px;text-transform:uppercase','readOnly'=>($accion != 0),'onkeypress'=>'return justNumbersAndStr(event);'])->label(false) ?>
				</td>
				<td width='20px'></td>
				<td> <label>Estado:</label> </td>
				<td>
					<?= $form->field($model, 'est',['options' => ['id' => 'est']])->textInput(['maxlength' => 4,'style' => 'width:90px;','readOnly'=>($accion != 0),'onkeypress'=>'return justNumbers(event);'])->label(false) ?>
				</td>
			</tr>
			<tr>
				<td> <label>Antigüedad:</label> </td>
				<td>	
				<?= $form->field($model, 'ant',['options' => ['id' => 'ant']])->textInput(['maxlength' => 4,'style' => 'width:40px;text-transform:uppercase','readOnly'=>($accion != 0),'onkeypress'=>'return justNumbers(event);'])->label(false) ?>
				</td>
				<td width='20px'></td>
				<td> <label>Coeficiente:</label> </td>
				<td>
					<?= $form->field($model, 'coef',['options' => ['id' => 'coef']])->textInput(['maxlength' => 12,'style' => 'width:90px;','readOnly'=>(in_array($accion,[1,2])),'onkeypress'=>'return justDecimalAndMenos(event,$(this).val())'])->label(false) ?>
				</td>
			</tr>
		</table>
</div>

<div class="form-group" style='display:<?= ($accion == 1 ? 'none' : 'block') ?>; margin-top:5px;' id="form_botones">		
	<?php	
		echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
    	echo "&nbsp;&nbsp;";
		echo Html::Button('Cancelar', ['class' => 'btn btn-primary','onclick'=>'Editar(1,0,0,0)']);		
	?>	    
</div>
	
<div style="margin-top:5px;">	
	<?php 
		echo $form->errorSummary($model);
	?>
</div>

<?php 
Pjax::end();
ActiveForm::end(); 
?>

<script>

function HabiltarControles(accion){
	
	$('#Grilla').css("pointer-events", (accion == 1 ? "all" : 'none') );
	$('#Grilla').css("color", (accion == 1 ? "#111111" : "#ccc"));
	$('#Grilla a').css("color", (accion == 1 ? "#337ab7" : "#ccc"));
	$('#Grilla Button').css("color", (accion == 1 ? "#337ab7" : "#ccc"));
	$('#btnNuevo').css("pointer-events", (accion == 1 ? "all" : 'none') );
	$('#btnNuevo').css("opacity", (accion == 1 ? 1 : 0.5 ) );

	if (accion == 1){
		$("#titulo").html("Coeficientes de Mejora");
		$('.error-summary').css('display','none');
		$('#form_botones').css('display','none');
	}
	if (accion == 0) {
		$("#titulo").html("Nuevo Coeficiente de Mejora");
	}
}

function Editar(accion,cat,est,ant){
	
	event.stopPropagation();
	
	HabiltarControles(accion);
	
	$.pjax.reload({
		container	:"#PjaxABM",
		type 		: "GET",
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data:{
			"accion"	: accion,
			"cat"       : cat,
			"est"       : est,
			"ant"       : ant
		},
	});
}

$( document ).ready( function() {

	HabiltarControles(<?= $accion ?>);
});

</script>
