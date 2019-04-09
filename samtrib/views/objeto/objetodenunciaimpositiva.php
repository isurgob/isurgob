<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\grid\GridView;

use \yii\widgets\Pjax;
use yii\widgets\ActiveForm;

use yii\web\Session;

use \yii\bootstrap\Modal;
use yii\bootstrap\Alert;

use yii\data\ArrayDataProvider;

use app\utils\db\utb;
use app\models\objeto\Domi;

$title = 'Denuncia impositiva';
$this->params['breadcrumbs'][] = ['label' => 'Objeto ' . $modelObjeto->obj_id, 'url' => ['view', 'id' => $modelObjeto->obj_id]];
$this->params['breadcrumbs'][] = $title;


//da como opcion solamente "comprador"
$condRelacion = "cod = 5";

$form = ActiveForm::begin(['fieldConfig' => ['template' => '{label}{input}'], 'id' => 'objeto-transferencia']);

echo Html::input('hidden', 'grabar', 1);
?>

<?php
Modal::begin([
	'id' => 'modal-titular',
	'header'=>'<h2>Titulares</h2>',
	'toggleButton' => false,
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
	'size' => 'modal-lg'
]);

Pjax::begin(['id' => 'pjaxModalTitular', 'enableReplaceState' => false, 'enablePushState' => false]);

$session = Yii::$app->session;
$session->open();

if(Yii::$app->request->get('action', -1) > -1){
	
	
	$session->set('action', intval(Yii::$app->request->get('action', 1)));
	$session->set('codigo', Yii::$app->request->get('codigo', ''));
	$session->set('nombre', Yii::$app->request->get('nombre', ''));
	$session->set('porc', Yii::$app->request->get('porc', 100));
	$session->set('relac', Yii::$app->request->get('relac', ''));	
	$session->set('tvinc', Yii::$app->request->get('tvinc', 1));
	$session->set('princ', Yii::$app->request->get('princ', ''));
	$session->set('est', Yii::$app->request->get('est', 'C'));
	$session->set('BD', Yii::$app->request->get('BD', 1));
}

$session->close();


	  
echo $this->render('//objeto/nuevoTitular', ['modelObjeto' => $modelObjeto, 'condRelacion' => $condRelacion, 'denunciaImpositiva' => 1]);
Pjax::end();

Modal::end();
?>
			


<script type="text/javascript">
function mostrarModal(action, est, codigo, nombre, porc, relac, tvinc, princ, bd){
	
	var datos = {"action" : action, "envio" : 0};
	
	if(action !== 0){
		
		datos.est = est;
		datos.codigo = codigo;
		datos.nombre = nombre;
		datos.porc = porc;
		datos.relac = relac;
		datos.tvinc = tvinc;
		datos.princ = princ;
		datos.BD = bd;
	}
	
	$.pjax.reload({
		container : "#pjaxModalTitular",
		replace : false,
		push : false,
		type : "GET",
		data : datos
	});
}

$(document).ready(function(){
	
	$("#pjaxModalTitular").on("pjax:end", function(){
		$("#modal-titular").modal("show");
	});	
});
</script>


<?php
if($poseeDeuda){
	Alert::begin([
		'options' => ['class' => 'alert alert-warning alert-dissmissible']
	]);
	
	echo '<span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;&nbsp;ATENCIÓN: El objeto posee deuda/s.';
	
	Alert::end();
}
?>

<div class="objeto->transferencia">
	<h1><?= $title . ' ' . $modelObjeto->obj_id ?></h1>
	
	<div class="form" style="padding:10px;">
		<h3><label>Datos actuales</label></h3>	
		
		<table border="0" width="640px">
			<tr>
				<td align="left">
					<?= $form->field($modelObjeto, 'obj_id', ['template' => '{label}<br>{input}', 'options' => ['style' => 'margin-bottom:0px;']])->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:80px;'])->label('Objeto'); ?>
					<?= $form->field($modelObjeto, 'obj_id', ['options' => ['style' => 'display:none;']])->input('hidden')->label(false) ?>
				</td>
				
				<td align="left" width="250px">
					<label>Nombre:</label>
					<?= Html::textInput(null, $modelObjeto->getOldAttribute('nombre'), ['style' => 'width:400px; margin-top:0px;', 'class' => 'form-control solo-lectura', 'tabindex' => -1]) 
					 ?>
				</td>
				<td width="5px">
					<?= $form->field($modelObjeto, 'obj_dato', ['options' => ['style' => 'margin-bottom:0px;']])->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1])->label('Dato') ?>
				</td>
				<td></td>
				<td width="5px"></td>
				<td align="left" width="90px">
					<?= $form->field($modelObjeto, 'est', ['template' => '{label}<br>{input}', 'options' => ['style' => 'margin-bottom:0px;']])->textInput(['maxlength' => 20, 'class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:75px'])->label('Estado') ?>
				</td>
				<td width="10px"></td>
			</tr>
		</table>
	</div>
	
	<div class="form" style="padding:10px; margin-top:5px;">
		<h3><label>Titulares actuales</label></h3>
		<?php
		
		echo GridView::widget([
			'dataProvider' => new ArrayDataProvider(['allModels' => $modelObjeto->arregloTitulares]),
			'summary' => false,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'columns' => [
			
				['attribute' => 'num', 'label' => 'Código', 'options' => ['style' => 'width:50px;']],
				['attribute' => 'apenom', 'label' => 'Apellido y nombre', 'options' => ['style' => 'width:250px;']],
				['attribute' => 'tdoc', 'label' => 'TDoc', 'options' => ['style' => 'width:50px;']],
				['attribute' => 'ndoc', 'label' => 'NroDoc', 'options' => ['style' => 'width:80px;']],
				['attribute' => 'tvinc_nom', 'label' => 'Relac.', 'options' => ['style' => 'width:50px;']],
				['attribute' => 'porc', 'label' => 'Porc', 'options' => ['style' => 'width:50px;']],
				['attribute' => 'est', 'label' => 'Est', 'options' => ['style' => 'width:30px;']],
				['attribute' => 'princ', 'label' => '', 'options' => ['style' => 'width:40px;']]
			]
		]);
		?>
	</div>
	
	<div class="form" style="padding:10px; margin-top:5px;">
	
		
	
		<h3 style="display:inline-block;"><label>Titulares nuevos</label></h3>

		<?= Html::button('Nuevo', ['class' => 'btn btn-success pull-right', 'onclick' => 'mostrarModal(1);']) ?>
		<?php
		Pjax::begin(['id' => 'tit-actualizaGrilla', 'enableReplaceState' => false, 'enablePushState' => false]);
		?>
		<?php
		echo GridView::widget([
			'dataProvider' => new ArrayDataProvider(['allModels' => $modelObjeto->arregloTitularesDenunciaImpositiva]),
			'summary' => false,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'columns' => [
			
				['attribute' => 'num', 'label' => 'Código', 'options' => ['style' => 'width:50px;']],
				['attribute' => 'apenom', 'label' => 'Apellido y nombre', 'options' => ['style' => 'width:250px;']],
				['attribute' => 'tdoc', 'label' => 'TDoc', 'options' => ['style' => 'width:50px;']],
				['attribute' => 'ndoc', 'label' => 'NroDoc', 'options' => ['style' => 'width:80px;']],
				['attribute' => 'tvinc_nom', 'label' => 'Relac.', 'options' => ['style' => 'width:50px;']],
				['attribute' => 'porc', 'label' => 'Porc', 'options' => ['style' => 'width:50px;']],
				['attribute' => 'est', 'label' => 'Est', 'options' => ['style' => 'width:30px;']],
				['attribute' => 'princ', 'label' => '', 'options' => ['style' => 'width:40px;']],
				[
						'class' => 'yii\grid\ActionColumn', 'options' => ['style' => 'width:30px;'],
						'template' => '{update}&nbsp;{delete}',
						'buttons' => [								
								'update' => function($url, $model, $key){
									
											return Html::a(
												'<span class="glyphicon glyphicon-pencil"></span>', 
												null, 
												[
												'onclick' => 'mostrarModal(2, "' . $model['est'] . '", "' . $model['num'] . '", "' . 
																		$model['apenom'].'", ' . $model['porc'] . ', "' . $model['tvinc_nom'] . '", ' . $model['tvinc'].', ' .
																		'"'. $model['princ']. '", ' . $model['BD']. ');'
														]);
											},
								
								'delete' => function($url, $model, $key){
									
										return Html::a(
											'<span class="glyphicon glyphicon-trash"></span>', 
											null, 
											[
											'onclick' => 'mostrarModal(3, "' . $model['est'] . '", "' . $model['num'] . '", "' . 
																		$model['apenom'].'", ' . $model['porc'] . ', "' . $model['tvinc_nom'] . '", ' . $model['tvinc'].', ' .
																		'"'. $model['princ']. '",' . $model['BD']. ');'
											]);
										}
								]
						]
			]
		]);
		
		$nuevoNombre = '';
		$porc = 0;
		
		foreach ($modelObjeto->arregloTitularesDenunciaImpositiva as $nuevo){
			
			if ( $nuevo['princ'] == 'Princ' ){
				$nuevoNombre = $nuevo['apenom'];
				break;
			}	
			
			if ( $nuevo['porc']	> $porc )
				$nuevoNombre = $nuevo['apenom'];
				
			$porc = $nuevo['porc'];	
		}
		
		echo "<script> $( document ).ready( function() { $('#nuevoNombre').val('$nuevoNombre'); });</script>";
		
		Pjax::end();
		?>

		<div>
			<table width="100%">
				
				<tr>
					<td>
					<label>Nuevo nombre:</label>
					<?= $form->field($modelObjeto, 'nombre', ['options' => ['style' => 'display:inline-block; width:78%;']])
						->textInput(['style' => 'width:100%;', 'readonly' => ($modelObjeto->tobj === 2), 'id' => 'nuevoNombre', 'maxlength' => 50])
						->label(false);?>
					</td>
				</tr>
				
				
				<tr>
					<td colspan="3">
					<label>Postal:</label> 					
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
		                  'class' => 'btn btn-danger btn-sm pull-right'
		                ]		                
		            ]);
		            
		            echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelDomicilio,'tor' => 'OBJ']);
		            
		            Modal::end();
			           					
					$modelDomicilio = isset($modelDomicilio) ? $modelDomicilio : new Domi();			
				
					echo Html::input('text', 'domi', $modelDomicilio->armarDescripcion(), ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'domicilio','style'=>'width:81%;']);
					echo '<input type="text" name="arrayDomicilio" id="arrayDomicilio" value="'.urlencode(serialize($modelDomicilio)).'" style="display:none">';
					?>
					</td>
				</tr>
				
				<tr>
					<td colspan="3"><?= ($modelObjeto->tobj === 2 ? Html::checkbox('cambiarNombre', false, ['label' => 'Actualizar nombre del comercio', 'onclick' => '$("#nuevoNombre").prop("readonly", !$(this).is(":checked"))']) : null) ?></td>
				</tr>
			</table>
			
			<?php
			Pjax::begin(['id' => 'CargarModeloDomi', 'enableReplaceState' => false, 'enablePushState' => false]);
			
			if(Yii::$app->request->post('tor', '') === 'OBJ'){
				
				$modelDomicilio->torigen = 'OBJ';
				$modelDomicilio->obj_id = $modelObjeto->obj_id;
				$modelDomicilio->id = 0;
				$modelDomicilio->prov_id = isset($_POST['prov_id']) ? $_POST['prov_id'] : 0;
				$modelDomicilio->loc_id = isset($_POST['loc_id']) ? $_POST['loc_id'] : 0;
				$modelDomicilio->cp = isset($_POST['cp']) ? $_POST['cp'] : "";
				$modelDomicilio->barr_id = isset($_POST['barr_id']) ? $_POST['barr_id'] : 0;
				$modelDomicilio->calle_id = isset($_POST['calle_id']) ? $_POST['calle_id'] : 0;
				$modelDomicilio->nomcalle = isset($_POST['nomcalle']) ? $_POST['nomcalle'] : "";
				$modelDomicilio->puerta = isset($_POST['puerta']) ? $_POST['puerta'] : "";
				$modelDomicilio->det = isset($_POST['det']) ? $_POST['det'] : "";
				$modelDomicilio->piso = isset($_POST['piso']) ? $_POST['piso'] : "";
				$modelDomicilio->dpto = isset($_POST['dpto']) ? $_POST['dpto'] : "";
				
				echo '<script>$("#domicilio").val("'.$modelDomicilio->armarDescripcion().'")</script>';
				echo '<script>$("#arrayDomicilio").val("'.urlencode(serialize($modelDomicilio)).'")</script>';
			}
			Pjax::end();
			?>
		</div>
	</div>
	
	<div style="padding:10px; margin-top:5px;">
		<?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
		<?= Html::a('Cancelar', ['view', 'id' => $modelObjeto->obj_id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Imprimir', ['imprimirdenunciaimpositiva', 'id' => $modelObjeto->obj_id], ['class' => 'btn btn-success','target' => '_black']) ?>
	</div>
</div>
<?php
ActiveForm::end();

echo $form->errorSummary([$modelObjeto, $modelDomicilio]);
?>