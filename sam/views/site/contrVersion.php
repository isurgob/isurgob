<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\ControlVersion;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\web\Session;

$title = 'Control de Versíon';

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */
if (isset($model) == null) $model = new ControlVersion();

?>

</style>

<div class="site-auxedit">
	<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode('Control de Versión') ?></h1></td>
		<td align='right'> <?php echo Html::a('Volver', ['//site/index'], ['class' => 'btn btn-primary']); ?> </td>
    </tr>
    <tr>
    	<td colspan='2'> <hr style='margin:5px 0px;border:0px;' /> </td>
    </tr>
    </table>
    
    <table width='100%' border='0'>
		<tr>
			<td width='20%' valign='top'>
		    <?php      	 	 
				echo GridView::widget([
					'id' => 'GrillaTablaAux',
		     	    //'dataProvider' => $model->listarVersiones(Yii::$app->session['sis_id']),
					'dataProvider' => $model->listarVersiones(10),
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => function ($model,$key,$index,$grid) 
									{
										return ['onclick' => '$.pjax.reload({container:"#novedades",data:{version:"'.$model['version'].'"},method:"POST"})'];
									},
					'columns' => [
		            		['attribute'=>'version','header' => 'Version', 'contentOptions'=>['style'=>'width:33%;text-align:left','class' => 'grilla']],
		            		['attribute'=>'fecha','header' => 'Fecha','contentOptions'=>['style'=>'width:33%;text-align:left;', 'class' => 'grilla']], 
		            		['attribute'=>'hora','header' => 'Hora','contentOptions'=>['style'=>'width:33%;text-align:left;', 'class' => 'grilla']]        	          	   
		        	],
		    	]);    
			 ?>
				
		   </td>
		   <td valign='top' width='80%'>
			   <?php $form = ActiveForm::begin(); ?>
				   <table border='0' width='97%' style='margin-left:10px;margin-top:10px;'>
						<tr>
							<td width='10%' height='40px'><label>Version</label></td>
							<td width='90%'>
								<?= Html::input('text', 'version',"",['class' => 'form-control','id'=>'version','style'=>'width:20%;text-align:center','readOnly'=>true]); ?>
								<?= Html::a('Ver Instructivo', null, ['id' => 'linkVerPdf', 'style' => 'visibility:hidden;margin-left:20px;', 'target' => '_black']); ?>
							<td>
						</tr>
						<tr>
							<td colspan='2'>
								<?= $form->field($model, 'novedades')->textArea(['id'=>'novedades','rows'=>20,'style'=>'width:100%;max-width:100%;height:100%;resize:none','maxlength' => 500,'disabled'=>'disabled']); ?> 																										
							</td>
						</tr>
				   </table>
			   <?php ActiveForm::end();  ?>
		   </td>
	   </tr>
   </table> 
    	
</div><!-- site-auxedit -->

<?php
Pjax::begin(['id' => 'novedades']);// comienza bloque de grilla

	 if (isset($_POST['version']) and $_POST['version'] !== null and $_POST['version'] !=='') {
	
		$novedades = $model->buscarNovedad(10,$_POST['version']);
		
		$archivoVersion = "versiones/Versión " . $_POST['version'] . ".pdf";
		if(file_exists($archivoVersion)){
			echo "<script>$('#linkVerPdf').css('visibility', 'visible')</script>";
			echo "<script>$('#linkVerPdf').attr('href', '" . $archivoVersion . "')</script>";
		}else{
			echo "<script>$('#linkVerPdf').css('visibility', 'hidden')</script>";
			echo "<script>$('#linkVerPdf').attr('href', null)</script>";
		}	
		
		// obtengo el alto del control.
		$cad=wordwrap($novedades, 780, "\r\n", 1);  
		$lineas=substr_count($cad,"\r\n")+5;
		if ( $lineas > 20 )
			echo "<script> $('#novedades').attr( 'rows', '$lineas' ); </script>";
		else
			echo "<script> $('#novedades').attr( 'rows', '20' ); </script>";
		
		echo $novedades;
		echo "<script> $('#version').val('".$_POST['version']."'); </script>";
		echo "<script> $('#novedades').val('".$novedades."'); </script>";

	 }

Pjax::end(); // fin bloque de la grilla	
?>
