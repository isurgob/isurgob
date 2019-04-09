<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;

use app\utils\db\utb;
ini_set("display_errors", "on");
error_reporting(E_ALL);
/* @var $this yii\web\View */
/* @var $model app\models\config\Rubro */
/* @var $form yii\widgets\ActiveForm */


$titulo = 'Nuevo Rubro';

switch($consulta){
	
	case 1: $titulo= "Consulta rubro " . $model->rubro_id; break;
	case 2: $titulo= "Eliminar rubro " . $model->rubro_id; break;
	case 3: $titulo= "Modificar rubro " . $model->rubro_id; break;
}


$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => '/samtest/index.php?r=site/config'];
$this->params['breadcrumbs'][] = ['label' => 'Rubros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $titulo;

?>
<h1><?= $titulo; ?></h1>
<div class="separador-horizontal"></div>

<?php
if(isset($mensaje) && !empty(trim($mensaje))){
	
	echo Alert::widget([
		'id' => 'alertMensaje',
		'options' => ['class' => 'alert alert-success'],
		'body' => $mensaje
	]);
	
	?>
	<script type="text/javascript">
	setTimeout(function(){$("#alertMensaje").fadeOut()}, 5000);
	</script>
	<?php
}
?>

<div class="rubro-form">

	<?php $form = ActiveForm::begin(['id'=>'formRubro', 'fieldConfig' => ['template' => '{input}', 'options' => ['style' => 'display:inline-block;']]]); ?>
	
	<div class="form" style="padding:5px;">
		<table border='0' width="100%">
			<tr>
				<td><label>Código:</label></td>
				<td width="5px">
				<td><?= $form->field($model, 'rubro_id')->textInput(['class' => 'form-control'.($consulta!=0 ? ' solo-lectura' : ''), 'maxlength' => 7, 'style' => 'width:100px;', 'onKeyPress' => 'return justNumbers(event);']); ?></td>
			</tr>			
				
			<tr>
				<td><label>Nombre:</label></td>
				<td></td>
				<td colspan="9"><?= $form->field($model, 'nombre')->textInput(['maxlength' => 100, 'id' => 'nombre', 'style' => 'width:700px;']);; ?></td>
			</tr>

			<tr>
				<td><label>Nomeclador:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'nomen_id')->dropDownList(utb::getAux( 'rubro_tnomen', 'nomen_id' ), ['class' => 'form-control'.($consulta!=0 ? ' solo-lectura' : ''),'style' => 'width:235px', 'prompt' => '', 'id' => 'nomen_id']); ?></td>
				<td width="10px">
				<td><label>Grupo:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'grupo')->dropDownList(utb::getAux('rubro_grupo'), ['id' => 'grupo', 'style' => 'width:155px;']); ?></td>
				<td width="10px"></td>
				<td><label>Tipo de Unidad:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'tunidad')->textInput(['maxlength' => 8, 'id' => 'tunidad', 'style' => 'width:70px;']); ?></td>
			</tr>
		</table>
	</div>
	
	      
	<?php

	 ActiveForm::end(); 
	 ?>
</div>


<?php if($consulta !== 0){ ?>
	
	<div class="rubro-form form" style="padding:5px; margin-top:5px;">
	
	
		
		<table width='98%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
			<tr>
				<td>
			    	<h3><label>Vigencias</label></h3>
			    </td>
				<td align='right'>
    				<?php 
    				if($consulta === 3)
    					echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo',
						'onclick' => 'mostrarFormularioVigencia(0, "' . $model->rubro_id . '")'
    				]) ?> 
    			</td>
		    </tr>
		</table>
		
 <?php	echo GridView::widget([
        'dataProvider' => $dpVigencias,// $rubroVigencia->listaRubroVigencia($model->rubro_id),
		'options' => ['style'=>'width:750px;'],
		'rowOptions' => ['class' => 'grilla'],
		'headerRowOptions' => ['class' => 'grilla'],			
        'columns' => [
            
			['attribute' => 'perdesde', 'label' => 'Desde','value'=>function($data){$anioDesde=substr($data['perdesde'],0,-3);
																					$cuotaDesde=substr($data['perdesde'],4,3);
																					return $anioDesde." - ".$cuotaDesde;}
			,'options' => ['style' => 'width:8%']],
			['attribute' => 'perhasta', 'label' => 'Hasta','value'=>function($data){$anioHasta=substr($data['perhasta'],0,-3);
																					$cuotaHasta=substr($data['perhasta'],4,3);
																					return $anioHasta." - ".$cuotaHasta;} 
			,'options' => ['style' => 'width:8%']],
			['attribute' => 'tcalculo', 'label' => 'Formula Cálculo','value' => function($data) { return (utb::getCampo('rubro_tfcalculo','cod='.$data['tcalculo'])); },
			'options' => ['style' => 'width:13%']],
			['attribute' => 'tminimo', 'label' => 'Tipo Mínimo','value' => function($data) { return (utb::getCampo('rubro_tminimo','cod='.$data['tminimo'])); }, 
			'options' => ['style' => 'width:12%']],
			['attribute' => 'alicuota', 'label' => 'Alícuota', 'options' => ['style' => 'width:7%']],
			['attribute' => 'alicuota_atras', 'label' => 'Alícuota atras', 'options' => ['style' => 'width:11%']],
			['attribute' => 'minimo', 'label' => 'Mínimo','options' => ['style' => 'width:6%']],
			['attribute' => 'minalta', 'label' => 'Mínimo T.A','options' => ['style' => 'width:9%']],
			['attribute' => 'fijo', 'label' => 'Fijo', 'options' => ['style' => 'width:5%']],
			['attribute' => 'canthasta', 'label' => 'Cant. Hasta', 'options' => ['style' => 'width:9%']],
			['attribute' => 'porc', 'label' => 'Porc.', 'options' => ['style' => 'width:5%']],
            ['class' => 'yii\grid\ActionColumn', 'options'=>['style'=>'width:5%'], 'template' => '{update}{delete}',        
            'buttons' => [   
	    			'update' => function($url,$model,$key){
	    						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', null, ['onclick' => 'mostrarFormularioVigencia(3, ' . $model['perdesde'] . ', ' . $model['perhasta'] . ');']);
							},									    							
						
	    			'delete' => function($url,$model,$key){   
								return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['onclick' => 'mostrarFormularioVigencia(2, ' . $model['perdesde'] . ', ' . $model['perhasta'] . ');']);	
							}
						]
					],
				],
			]);
		
		
				
		?>
	</div>		
<?php }	?>



	<div class="form-group" id='form_botones' style='margin-top:5px;'>	
	<?php 
		if($consulta == 0 || $consulta == 3){
			echo Html::button('Grabar', ['class' => 'btn btn-success', 'onclick' => '$("#formRubro").submit();']); 
			echo "&nbsp;&nbsp;";
			echo Html::a('Cancelar', ['index'], ['class' => 'btn btn-primary',]);
		} 
		?>
	</div> 
	<div class="form-group" id='form_botones_delete' style='margin-top:5px;'>
	<?php
		if($consulta == 2){
			echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick' => '$("#formRubro").submit();']); 
			echo "&nbsp;&nbsp;";
			echo Html::a('Cancelar', ['index'], ['class' => 'btn btn-primary',]);
		}
	?>
	</div>


<?php
	Pjax::begin(['id' => 'pjaxModalFormVigencia', 'enablePushState' => false, 'enableReplaceState' => false]);
		
		$accion= intval(Yii::$app->request->get('accion', -1));
		$codigoRubro= intval(Yii::$app->request->get('rubro_id', 0));
		$perdesde= intval(Yii::$app->request->get('perdesde', 0));
		$perhasta= intval(Yii::$app->request->get('perhasta', 0));
		$titulo= '';
		$url= '';
		$mostrar= true;
		
		switch($accion){
			
			case 0:
				$titulo= 'Nueva vigencia';
				$url= BaseUrl::toRoute(['//config/rubrovigencia/create']);
				break;
				
			case 2:
				$titulo= 'Eliminar vigencia';
				$url= BaseUrl::toRoute(['//config/rubrovigencia/delete', 'id' => $codigoRubro, 'perdesde' => $perdesde, 'perhasta' => $perhasta]);
				break;
				
			case 3:
				$titulo= 'Modificar vigencia';
				$url= BaseUrl::toRoute(['//config/rubrovigencia/update', 'id' => $codigoRubro, 'perdesde' => $perdesde, 'perhasta' => $perhasta]);
				break;
			
			default:
				$mostrar= false;
		}
		
		if($mostrar && $codigoRubro >= 0){
			
			Modal::begin([
				'header' => '<h1>' . $titulo . '</h1>',
				'footer' => null,
				'toggleButton' => ['class' => 'hidden'],
				'id' => 'modalFormVigencia',
				'closeButton' => [
					'label' => '<b>X</b>',
					'class' => 'btn btn-danger btn-sm pull-right',
				],
			]);
			
			echo $this->render('//config/rubrovigencia/_form', ['model' => $modelVigencia, 'consulta' => $accion, 'rubro_id' => $codigoRubro, 'selectorModal' => '#modalFormVigencia']);
			 
			Modal::end();
		}
		
	Pjax::end();
?>

<?php
echo $form->errorSummary($model);
?>

<script type="text/javascript">
function mostrarFormularioVigencia(consulta, perdesde, perhasta){

	var datos= {
		"accion": consulta,
		"cargarVigencia": true,
		"id": "<?= $model->rubro_id ?>"
	}
	
	if(consulta === 2 || consulta === 3){
		
		datos.perdesde= perdesde;
		datos.perhasta= perhasta;
	}
	
	switch(consulta){
		
		case 0: datos.url= "<?= BaseUrl::toRoute(['//config/rubrovigencia/create']); ?>"; break;
		case 2: datos.url= "<?= BaseUrl::toRoute(['//config/rubrovigencia/delete']); ?>"; break;
		case 3: datos.url= "<?= BaseUrl::toRoute(['//config/rubrovigencia/update']); ?>"; break;
	}
	
	$.pjax.reload({
		
		container: "#pjaxModalFormVigencia",
		type: "GET",
		replace: false,
		push: false,
		data: datos
	});
	
	$("#pjaxModalFormVigencia").on("pjax:complete", function(){
		
		$("#modalFormVigencia").modal("show");
		
		$("#pjaxModalFormVigencia").off("pjax:complete");
	});
}

$(document).ready(function(){
	
	
	<?php
	if(in_array($consulta, [1, 2])){
		?>
		DesactivarFormPost("formRubro");
		<?php
	}
	?>	
});
</script>