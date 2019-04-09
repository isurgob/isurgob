<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\web\Session;

//no se muestra para consulta y delete
$mostrarActions = !($consulta == 1 || $consulta == 2);
$condRelacion = 'tobj In (0,4)';
?>
<table border="0" width="600px">
	<tr>		
		<td width="100px">
			
			<?php
			Modal::begin([
				'id' => 'modal-titular',
				'header'=>'<h2>Titulares</h2>',
				'toggleButton' => [
					'label' => 'Nuevo',
					'class' => 'btn btn-success pull-right',
					'style' => 'display:none'
				],
				'closeButton' => [
				  'label' => '<b>X</b>',
				  'class' => 'btn btn-danger btn-sm pull-right',
				],
				'size' => 'modal-lg'
			]);
					  
			echo $this->render('//objeto/nuevoTitular', ['modelObjeto' => $modelObjeto, 'condRelacion' => $condRelacion]);
		
			Modal::end();
			?>
		</td>
		
		<td align="right" width="500px">
			<?php
			$clase = 'btn btn-success';
			
			if(!$mostrarActions) $clase .= ' hidden';
			
			echo Html::button('Nuevo', [
    								'class' => $clase,
    								'onclick'=>'$.pjax.reload({container:"#pjaxCargarModal", push : false, replace : false, type:"POST",data:{"action":1,"consulta" : 1, "abrirModal" : true}});'
    								]);

			 
    		?>
		</td>
	</tr>
</table>
<?php

Pjax::begin(['id' => 'pjaxCargarModal', 'enablePushState' => false, 'enableReplaceState' => false]);

$session = Yii::$app->session;
$session->open();

if(isset($_POST['action'])){
	
	
	$session['action'] = intval(Yii::$app->request->post('action', 1));
	$session['codigo'] = Yii::$app->request->post('codigo', '');
	$session['nombre'] = Yii::$app->request->post('nombre', '');
	$session['porc'] = Yii::$app->request->post('porc', 100);
	$session['relac'] = Yii::$app->request->post('relac', '');	
	$session['tvinc'] = Yii::$app->request->post('tvinc', 1);
	$session['princ'] = Yii::$app->request->post('princ', '');
	$session['est'] = Yii::$app->request->post('est', 'C');
	$session['BD'] = Yii::$app->request->post('BD', 1);	
	$session['consulta'] = Yii::$app->request->post('consulta', 0);	
}

if(isset($_POST['abrirModal'])){	
?>
	<script>
	/*	Se encarga de actualizar los datos de modal	*/
	$.pjax.reload({container:"#form-nuevoTitular",method:"GET", data:{"action":<?=$_SESSION['action']?>,"consulta":<?=$_SESSION['consulta']?> } } );
	
	/*	Se encarga de mostrar el modal	*/
	window.setTimeout(function() { $("#modal-titular").modal("show"); }, 300);
	
	</script>
<?php
}
$session->close();

Pjax::end();
?>

<?php
Pjax::begin(['id' => 'tit-actualizaGrilla', 'enablePushState' => false, 'enableReplaceState' => false]);

$session = new Session;
$session->open();

if(count($modelObjeto->arregloTitulares) == 0)
	$modelObjeto->arregloTitulares = $session->get('arregloTitulares', []);

$session->close();	
?>
<table border="0" width="600px">
	<tr>
		<td align="left" width="600px">
			<?php
			
				echo GridView::widget([
					'dataProvider' => $modelObjeto->CargarTitulares($modelObjeto->arregloTitulares),
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'columns' => [
					
						['attribute' => 'num', 'label' => 'Código'],
						['attribute' => 'apenom', 'label' => 'Apellido y nombre'],
						['attribute' => 'tdoc', 'label' => 'TDoc'],
						['attribute' => 'ndoc', 'label' => 'NroDoc'],
						['attribute' => 'tvinc_nom', 'label' => 'Relac.'],
						['attribute' => 'porc', 'label' => 'Porc'],
						['attribute' => 'est', 'label' => 'Est'],
						['attribute'=>'princ','header' => ''],
						
						[
						'class' => 'yii\grid\ActionColumn', 'options' => ['style' => $mostrarActions ? 'width:50px;' : 'width:0px;'],
						'template' => $mostrarActions ? '{update}{delete}' : '',
						'buttons' => [
								'view' => function(){return null;},
								
								'update' => function($url, $model, $key){
									
											return Html::a(
												'<span class="glyphicon glyphicon-pencil"></span>', 
												null, 
												[
												'onclick' => '$.pjax.reload({' .
															'container : "#pjaxCargarModal", ' .
															'replace : false, ' .
															'push : false, ' .
															'type : "POST", ' .
															'data : {' .
																'"action" : 2, ' .
																'"est" : "' . $model['est'] . '",' .
																'"abrirModal" : "true",' .
																'"codigo" : "' . $model['num'] . '",' .
																'"nombre" : "'.$model['apenom'].'",' .
																'"porc" : "'.$model['porc'].'",' .
																'"relac" : "'.$model['tvinc_nom'].'",' .
																'"tvinc" : "'.$model['tvinc'].'",' .
																'"princ" : "'.$model['princ'].'",' .
																'"est" : "'.$model['est'].'",' .
																'"BD" : "'.$model['BD'].'",' .
																'"consulta" : "2"' .
																	'}' .
															'});'
														]);
											},
								
								'delete' => function($url, $model, $key){
									
										return Html::a(
											'<span class="glyphicon glyphicon-trash"></span>', 
											null, 
											[
											'onclick' => 'borrarTitular("' . $model['num'] . '")'
											]);
										}
								]
						]
					],
				]);
			?>
		</td>
	</tr>
</table>
<?php
Pjax::end();
?>

<script type="text/javascript">
function borrarTitular(codigo){
	
	$.pjax.reload({
		container : "#pjaxBorrarTitular",
		type : "GET",
		push : false,
		replace : false,
		data : {
			"num" : codigo
		} 
	});
}
</script>

<?php
Pjax::begin(['id' => 'pjaxBorrarTitular', 'enableReplaceState' => false, 'enablePushState' => false]);

$session = Yii::$app->session;
$session->open();

$eliminar = Yii::$app->request->get('num', 0);
$titulares = $session->get('arregloTitulares', []);

if(array_key_exists($eliminar, $titulares)){

	if($titulares[$eliminar]['BD'] == '1'){
		$titulares[$eliminar]['est'] = 'B';
		$titulares[$eliminar]['princ'] = '';
	}
	else{
		unset($titulares[$eliminar]);
	}
	
	
	$session->set('arregloTitulares', $titulares);
	$session->close();
	?>
	<script>
	
	$("#pjaxBorrarTitular").on("pjax:complete", function(){

		$("#pjaxBorrarTitular").off("pjax:complete");

		$.pjax.reload({
			container : "#tit-actualizaGrilla",
			push : false,
			replace : false,
			type : "GET"
		});
	});
	</script>
	<?php
}
$session->close();
Pjax::end();
?>