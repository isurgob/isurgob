<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use app\models\objeto\Inm;
use \yii\widgets\Pjax;
use yii\web\Session;
use app\utils\db\utb;

/*
 * Cuando la vista se dibuja para realizar una transferencia, recibo una variable (transferencia = 1)
 * que me va a obligar a grabar los datos en una variable de sesión diferente.
 *
 * $transferencia = 0 ==> Corresponde al modo por defecto, el titular se agrega desde el formulario
 * $transferencia = 1 ==> Corresponde al modo para realizar transferencia, el titular se agrega desde transferencia
 */


$session = new Session;
$session->open();

 if (isset($transferencia) && $transferencia == 1)
 {
 	$session['banderaTitulares'] = 2; //banderaTitulares = 2 implica que cuando se vuelva a dibujar el view, se debe actualizar el listado de titulares
 }

$session->close();

//La variable action identificará el tipo de acción que se debe realizar en el modal
$action = '';

//<!-- INICIO Modal de titular -->
Modal::begin([
		'id' => 'modal-titular',
		'header'=>'<h2>Titulares</h2>',
		'toggleButton' => [
			'label' => 'Nuevo',
			'class' => 'btn btn-success pull-rigth',
			'style' => 'display:none',
		],
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		],
		'size' => 'modal-lg'
	]);

	echo $this->render('//objeto/nuevoTitular', ['modelObjeto' => $modelObjeto, 'condRelacion' => (utb::getExisteProceso(3104) ? 'tobj IN (0,1)' : 'cod=2 or cod=5' )]);

	Modal::end();
//<!-- FIN Modal de titular -->


?>
<div class="form-titular">
	<table width="100%">
		<tr>
			<td width="120px" align="center"><label>Tipo Matrícula</label>
			<?= $form->field($model, 'tmatric')->dropDownList(utb::getAux('inm_tmatric'), [ 'style' => 'width:100%','class' => 'form-control'])->label(false) ?></td>
			<td width="30px"></td>
			<td width="100px" align="center"><label>Matrícula</label><?= $form->field($model, 'matric')->textInput([ 'style' => 'width:100%','class' => 'form-control','maxlength'=>'15'])->label(false) ?></td>
			<td width="30px"></td>
			<td width="80px" align="center"><label>Fecha</label><?= $form->field($model, 'fchmatric')->widget(DatePicker::classname(), ['dateFormat' => 'dd/MM/yyyy','value' => $model->fchmatric, 'options' => ['style' => 'width:100%; align:right','class' => 'form-control'],])->label(false);?></td>
			<td width="30px"></td>
			<td width="40px" align="center"><label>Año</label><?= $form->field($model, 'anio')->textInput([ 'style' => 'width:100%','class' => 'form-control','onkeypress'=>'return justNumbers(event)','maxlength'=>'4'])->label(false) ?></td>
			<td width="100px"></td>
			<td align="right"><?= Html::button('Nuevo', [
    													'class' => 'btn btn-success',
    													'onclick'=>'$.pjax.reload({container:"#nuevoTitular",method:"POST",data:{action:1}});']); ?>
			</td>
		</tr>
	</table>

<?php
	Pjax::begin(['id'=>'tit-actualizaGrilla']);

	$session = new Session;
	$session->open();

	$modelObjeto->arregloTitulares = $session['arregloTitulares'];
	if (isset($_GET['tit'])) $modelObjeto->arregloTitulares = unserialize( urldecode( stripslashes( $_GET['tit'] ) ) );

	$session->close();
	
	echo '<input type="hidden" name="arrayTitulares" id="arrayTitulares" value="'.urlencode( serialize( $modelObjeto->arregloTitulares ) ).'">';
	
		echo GridView::widget([
		    'dataProvider' => $modelObjeto->CargarTitulares($modelObjeto->arregloTitulares),
		    'rowOptions' => ['class' => 'grilla'],
		    'headerRowOptions' => ['class' => 'grilla'],
		    'columns' => [

	            ['attribute'=>'num','header' =>'Código'],
	            ['attribute'=>'apenom','header' => 'Apellido y Nombre'],
	            ['attribute'=>'tdoc','header' => 'TDoc'],
	            ['attribute'=>'ndoc','header' => 'NroDoc'],
	            ['attribute'=>'tvinc_nom','header' => 'Relac.'],
	            ['attribute'=>'porc','header' => 'Porc'],
	            ['attribute'=>'est','header' => 'Est'],
	            ['attribute'=>'princ','header' => ''],


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
											'onclick'=>'$.pjax.reload({container:"#nuevoTitular",' .
																		'method:"POST",' .
																		'data:{' .
																			'action:2,' .
																			'codigo:"'.$model['num'].'",' .
																			'nombre:"'.$model['apenom'].'",' .
																			'porc:"'.$model['porc'].'",' .
																			'relac:"'.$model['tvinc_nom'].'",' .
																			'tvinc:"'.$model['tvinc'].'",' .
																			'princ:"'.$model['princ'].'",' .
																			'est:"'.$model['est'].'",' .
																			'BD:"'.$model['BD'].'",' .
																			'"tit" : $("#arrayTitulares").val()' .
																		'}});']) ;
								},

	    			'delete' => function($url, $model, $key) use ( $consulta )
	    						{
	    							if ( $consulta == 0 || $consulta == 3 )
	    							return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
											'class' => 'bt-buscar-label',
											'style' => 'color:#337ab7',
											'onclick'=>'borrarTitular("' . $model['num'] . '")']) ;
								},
	    			]
	    		]
	    	]
	    ]);

		Pjax::end();

		Pjax::begin(['id'=>'nuevoTitular']);

			if(isset($_POST['action'])) $action = $_POST['action'];


	    	if ($action != '')
	    	{

				$session = new Session;
				$session->open();

				/*$session['action'] = Yii::$app->request->post( 'action', 1 );
				$session['codigo'] = Yii::$app->request->post( 'codigo', '' );
				$session['nombre'] = Yii::$app->request->post( 'nombre', '' );
				$session['porc'] = Yii::$app->request->post( 'porc', 100 );
				$session['relac'] = Yii::$app->request->post( 'relac', '' );
				$session['tvinc'] = Yii::$app->request->post( 'tvinc',  1 );
				$session['princ'] = Yii::$app->request->post( 'princ', '' );
				$session['est'] = Yii::$app->request->post( 'est', 'C' );
				$session['BD'] = Yii::$app->request->post( 'BD', 1 );
				*/
				
				$action = intval(Yii::$app->request->post('action', 1));
				$arreglo['codigo'] = Yii::$app->request->post('codigo', '');
				$arreglo['apenom'] = Yii::$app->request->post('nombre', '');
				$arreglo['porc'] = Yii::$app->request->post('porc', 100);
				$arreglo['relac'] = Yii::$app->request->post('relac', '');
				$arreglo['tvinc'] = Yii::$app->request->post('tvinc', 1);
				$arreglo['princ'] = Yii::$app->request->post('princ', '');
				$arreglo['est'] = Yii::$app->request->post('est', 'C');
				$arreglo['BD'] = Yii::$app->request->post('BD', 1);

				$session->close();

				?>

                <script>
            	/*	Se encarga de actualizar los datos de modal	*/
            	$.pjax.reload({container:"#form-nuevoTitular",method:"GET", replace : false, push : false, data:{"action":<?=$action?>,"tituno" : "<?= urlencode( serialize($arreglo) ) ?>" } } );

            	/*	Se encarga de mostrar el modal	*/
            	$("#form-nuevoTitular").off("pjax:end");

            	$("#form-nuevoTitular").on("pjax:end", function(){
            		$("#modal-titular").modal("show");
            	});
            	</script>

		<?php

	    	}

		Pjax::end();

		?>
</div>

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
