<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\grid\GridView;
use app\models\objeto\Inm;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use yii\web\Session;

//La variable action identificará el tipo de acción que se debe realizar en el modal
$action = '';
$consulta = isset($consulta) ? $consulta : 1;

//Se actualiza el Pjax en caso de que se presione el botón copiar de domicilio
Pjax::begin(['id'=>'copiarDomi']);

    if (isset($_POST['arrayDomiPar'])) $modelodomipar=unserialize(urldecode(stripslashes($_POST['arrayDomiPar'])));
    if (isset($_POST['arrayDomiPost'])) $modelodomipost=unserialize(urldecode(stripslashes($_POST['arrayDomiPost'])));
    

Pjax::begin(['id'=>'pjax-modal']);
			
	Modal::begin([
		'id' => 'modal-frente',
		'header'=>'<h2>Frentes</h2>',
		'toggleButton' => [
			'label' => 'Nuevo',
			'class' => 'btn btn-success pull-right',
			'id'=>'inm-btnmodal-frente',
            'style'=>'display:none',
		],
		'closeButton' => [
		  'label' => '&times;',
		  'class' => 'btn btn-danger pull-right',
		],
		 ]);
			  
	
	echo $this->render('//objeto/inm/frente', ['model' => $model, 'consulta' => $consulta]);

	Modal::end();

Pjax::end();

?>


<div class="form-titular">
	
	<div id='consultaDomic' >
		<table border='0'>
		
			<tr>
				<td><label class="control-label">Parcelario:</label></td>
				<td width='5px'></td>
				<td>
					<!-- boton de búsqueda modal -->
						<?php
						Modal::begin([
							'id' => 'BuscaDomiI',
							'header' => '<h2>Búsqueda de Domicilio</h2>',
							'toggleButton' => [
								'label' => '<i class="glyphicon glyphicon-search"></i>',
								'class' => 'bt-buscar',
							],
							'closeButton' => [
							  'label' => '<b>X</b>',
							  'class' => 'btn btn-danger btn-sm pull-right',
							]
						]);
					   
						echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelodomipar, 'tor' => 'INM']);
						
						Modal::end();
						 
						?>
						<!-- fin de boton de búsqueda modal -->
					<?= Html::input('text', 'domi_parcelario',  (isset($modelodomipar->domicilio) ? $modelodomipar->domicilio : null), ['class' => 'form-control','id'=>'domi_parcelario','style'=>'width:440px;background:#E6E6FA;','disabled'=>'true']); ?>
				</td>
				<td><?= Html::Button('Copiar',['class' => 'btn btn-success', 'onClick' => 'btCopiarDomi();']) ?></td>
			</tr>
			<tr>
				<td><label class="control-label">Postal:</label></td>
				<td width='5px'></td>
				<td colspan="2">
					<!-- boton de búsqueda modal -->
						<?php
						Modal::begin([
							'id' => 'BuscaDomiP',
							'header' => '<h2>Búsqueda de Domicilio</h2>',
							'toggleButton' => [
								'label' => '<i class="glyphicon glyphicon-search"></i>',
								'class' => 'bt-buscar'
							],
							'closeButton' => [
							  'label' => '<b>X</b>',
							  'class' => 'btn btn-danger btn-sm pull-right',
							]
						]);
						
						echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelodomipost, 'tor' => 'OBJ']);
						
						Modal::end();
						?>
						<!-- fin de boton de búsqueda modal -->
					<?= Html::input('text', 'domi_postal', (isset($modelodomipost->domicilio) ? $modelodomipost->domicilio : null), ['class' => 'form-control','id'=>'domi_postal','style'=>'width:500px;background:#E6E6FA;','disabled'=>'true']); ?>
				</td>
			</tr>

		</table>
	</div>	

	<div id='consultaFreste' >
		<br/>
		<table width="600px">
			<tr>		
				<td align="left"><h3><b>Frentes</b></h3></td>	
				<td align="right"><?= Html::button('Nuevo', [
												'class' => 'btn btn-success',
												'onclick'=>'$.pjax.reload({container:"#nuevoFrente",method:"POST",data:{action:1}});']); ?>
				</td>
			</tr>
		</table>
		

		<table>
			<tr>
				<td width="600px">
					<?php
					
					
					Pjax::begin(['id' => 'frente-actualizaGrilla']);			
					
					$session = new Session;
					$session->open();
					
					$arrayFrentes = $session['arregloFrentes']; //Voy a usar arrayFrentes para calcular el valor de Frente en Valuaciones.
					
					$model->arrayFrente = $arrayFrentes;

					$session->close();

					
					
					echo GridView::widget([
						'dataProvider' => $model->CargarDomicFrente(),
						'rowOptions' => ['class' => 'grilla'],
						'headerRowOptions' => ['class' => 'grilla'],
						'columns' => [
												
							['attribute'=>'calle_id','header' =>'Calle'],
							['attribute'=>'calle_nom','header' => 'Nombre'],
							['attribute'=>'medida','header' => 'Medida'],
				
				
						   ['class' => 'yii\grid\ActionColumn',
							'buttons' => [	    
								'view' => function()
										{
											return null;
										},					
								'update' => function($url, $model, $key) use ( $consulta )
											{  
												if ( $consulta == 0 || $consulta == 3 )
												return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
														'class' => 'bt-buscar-label',
														'style' => 'color:#337ab7',
														'onclick'=>'$.pjax.reload({	container:"#nuevoFrente",' .
																					'method:"POST",' .
																					'data:{' .
																						'action:2,' .
																						'calle_id:"'.$model['calle_id'].'",' .
																						'calle_nom:"'.$model['calle_nom'].'",' .
																						'medida:"'.$model['medida'].'"' .
																					'}});']) ;		
											},
										
								'delete' => function($url, $model, $key) use ( $consulta )
											{  
												if ( $consulta == 0 || $consulta == 3 )          							
												return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
														'class' => 'bt-buscar-label',
														'style' => 'color:#337ab7',
														'onclick'=> 'borrarFrente("' . $model['calle_id'] . '");'
														]) ;		
											}
								]
							]
						]
					]);
					
					$suma = 0;
					foreach($arrayFrentes as $array)
					{
						$suma += (double)$array['medida'];
					}
									
					if ( count($arrayFrentes) > 0 ) 
						$model->frente = $suma;
						
					//Tengo que refrescar el view de valuaciones
					if (isset($_POST['act']))	//Si existe, se debe actualizar el valor de frente en valuaciones
						echo '<script>$.pjax.reload({container:"#pjax-valuaciones",method:"POST"})</script>';
					
					Pjax::end();
					
					Pjax::begin(['id'=>'nuevoFrente']);
						
						if(isset($_POST['action'])) $action = $_POST['action'];
						
						
						if ($action != '')
						{
					
							$session = new Session;
							$session->open(); 
							
							$session['actionFRENTE'] = '';
							$session['calle_id'] = '';
							$session['calle_nom'] = '';
							$session['medida'] = '';
																			
							if(isset($_POST['action'])) $session['actionFRENTE'] = $_POST['action'];
							if(isset($_POST['calle_id'])) $session['calle_id'] = $_POST['calle_id'];
							if(isset($_POST['calle_nom'])) $session['calle_nom'] = $_POST['calle_nom'];
							if(isset($_POST['medida'])) $session['medida'] = $_POST['medida'];
				
							$session->close();

							?>
							
							<script>
							/*	Se encarga de actualizar los datos de modal	*/
							$.pjax.reload({container:"#form-nuevoFrente",method:"POST"});
							
							/*	Se encarga de mostrar el modal	*/
							$("#form-nuevoFrente").on("pjax:complete", function(){
								
								$("#modal-frente").modal("show");
								$("#form-nuevoFrente").off("pjax:complete");
							});
							</script>
						
					<?php
					
						}
						
					Pjax::end();
						
					
					?>
			
				</td>
			</tr>
		</table>
	</div>	
</div>

<?php
Pjax::end();
?>
<script>
function btCopiarDomi()
{
	if ($("#domi_parcelario").val() !== "")
	{		
		$("#arrayDomiPost").val($("#arrayDomiPar").val());
		
		$.pjax.reload({container:"#copiarDomi",method:"POST",data:{arrayDomiPar:$("#arrayDomiPar").val(),arrayDomiPost:$("#arrayDomiPost").val()}})
	}
}

<?php
$consulta = intval($consulta);
if($consulta === 0 || $consulta === 3){
?>
function borrarFrente(calle_id){
	
	$.pjax.reload({
		container : "#pjaxBorrarFrente",
		type : "GET",
		replace : false,
		push : false,
		data : {
			"codigoCalle" : calle_id
		}
		
	});
}
<?php
}
?>
</script>

<?php
//borra un frente y carga nuevamente la grilla
Pjax::begin(['id' => 'pjaxBorrarFrente', 'enableReplaceState' => false, 'enablePushState' => false]);
$session = Yii::$app->session;
$session->open();

$calle_id = intval(Yii::$app->request->get('codigoCalle', 0));

$arreglo = $session->get('arregloFrentes', []);
if($calle_id > 0 && array_key_exists($calle_id, $arreglo)) unset($arreglo[$calle_id]);
$session->set('arregloFrentes', $arreglo);

						
$session->close();

?>
<script>
$(document).ready(function(){
	
	$.pjax.reload({container:"#frente-actualizaGrilla",method:"POST",data:{act:1}});
});
</script>
<?php
Pjax::end();
?>