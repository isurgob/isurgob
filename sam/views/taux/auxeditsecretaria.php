<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\taux\tablaAux;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\web\Session;
use yii\data\ArrayDataProvider;

$title = 'Secretarías';
$this->params['breadcrumbs'][] = ['label' => 'Configuración','url' => ['config']];
$this->params['breadcrumbs'][] = $title;

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

if (isset($consulta) == null) $consulta = 1;
if (isset($_GET['mensaje']) == null) $_GET['mensaje'] = '';

?>
<div class="site-auxedit">
	<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
    	<td align='right'>
    		<?php
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoClick();'])
    		?>
    	</td>
    </tr>
	<tr>
    	<td colspan='2'></td>
    </tr>
    </table>

	<?php
	Pjax::begin(['id'=>'PjaxBuscarTAux']);

		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $tabla,
			'headerRowOptions' => ['class' => 'grilla'],
			'sorter' => 'false',
			'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
			'rowOptions' => function ($model,$key,$index,$grid)
							{
								return [
									'onclick' => 'CargarControles('.$model['cod'].',"'.
																	$model['nombre'].'","'.
																	$model['formatoaux'].'","'.
																	$model['part_nom'].'","'.
																	$model['formatoaux2'].'","'.
																	$model['part_nom2'].'","'.
																	$model['formatoaux3'].'","'.
																	$model['part_nom3'].'","'.
																	$model['modif'].'");'
								];
							},
			'columns' => [
            		['attribute'=>'cod','label' => 'Cod', 'contentOptions'=>['style'=>'width:1%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'nombre','label' => 'Nombre' ,'contentOptions'=>['style'=>'text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'formatoaux','header' => 'Partida1', 'contentOptions'=>['style'=>'text-align:center;', 'class' => 'grilla']],
					['attribute'=>'part_nom','header' => 'Nombre1', 'contentOptions'=>['style'=>'text-align:left;', 'class' => 'grilla']],
					['attribute'=>'formatoaux2','header' => 'Partida2', 'contentOptions'=>['style'=>'text-align:center;', 'class' => 'grilla']],
					['attribute'=>'part_nom2','header' => 'Nombre2', 'contentOptions'=>['style'=>'text-align:left;', 'class' => 'grilla']],
					['attribute'=>'formatoaux3','header' => 'Partida3', 'contentOptions'=>['style'=>'text-align:center;', 'class' => 'grilla']],
					['attribute'=>'part_nom3','header' => 'Nombre3', 'contentOptions'=>['style'=>'text-align:left;', 'class' => 'grilla']],

            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:35px;text-align:center;','class' => 'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'event.stopPropagation();' .
																	 'btnModificarClick('.$model['cod'].',"'.
																					 $model['nombre'].'","'.
																					 $model['part_id'].'","'.
																					 $model['formatoaux'].'","'.
																					 $model['part_nom'].'","'.
																					 $model['part_id2'].'","'.
																					 $model['formatoaux2'].'","'.
																					 $model['part_nom2'].'","'.
																					 $model['part_id3'].'","'.
																					 $model['formatoaux3'].'","'.
																					 $model['part_nom3'].'","'.
																					 $model['modif'].'");'
													]
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'event.stopPropagation();' .
																	 'btnEliminarClick('.$model['cod'].',"'.
																					 $model['nombre'].'","'.
																					 $model['part_id'].'","'.
																					 $model['formatoaux'].'","'.
																					 $model['part_nom'].'","'.
																					 $model['formatoaux2'].'","'.
																					 $model['part_nom2'].'","'.
																					 $model['formatoaux3'].'","'.
																					 $model['part_nom3'].'","'.
																					 $model['modif'].'");'
													]
            									);
		            						}
						    			]
						    	   ],
								],
							]);
	Pjax::end();

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => '139'],'id'=>'frmSecretaria']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin:10px 0px'>
		<table border='0'>
		<tr height='35px'>
			<td width='55px'><label>Código:</label></td>
			<td>
				<?= Html::input('text', 'txCod', $cod, ['class' => 'form-control','id'=>'txCod','maxlength'=> '10','style'=>'width:70px;', 'readonly' => true]); ?>
			</td>
			<td> </td>
			<td align='left'><label style='margin-left:7px;'>Nombre:</label></td>
			<td align='right'>
			  <?= Html::input('text', 'txNombre',$nombre,['id' => 'txNombre','class' => 'form-control','maxlength'=>'35' ,'style' => 'width:350px;', 'disabled' => true ]); ?>
			</td>
		</tr>

		<tr height='35px'>
			<td><label>Partida 1:</label></td>
			<td>
			   <?= Html::input( 'text', 'part_id', $part_id,[
								'id'    => 'txPartId',
								'class' => 'hidden'
							]);
				?>
			   <?= Html::input( 'text', 'part_formatoaux', null,[
								'id'    => 'txPartFormatoAux',
								'class' => 'form-control',
								'style' => 'width:100px; text-align: center;',
								'onchange'  => 'f_cambiaPartida( $( this ).val() );',
								'disabled' => true 
							]);
				?>
			</td>
			<td width='35px' align='center'>
				<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
								'id'        => 'btPartida',
								'class'     => 'bt-buscar',
								'style' => 'margin-top:1px;',
								'onclick'   => 'f_btPartida()',
								'disabled' => true 
							]);
				?>
			</td>
			<td colspan='2'>
				<?= Html::input( 'text', 'part_nom', $part_nom, [
								'id'    => 'txPartNom',
								'class' => 'form-control solo-lectura',
								'style' => 'width:100%;',
								'tabIndex'  => '-1',
							]);
				?>
			</td>
		</tr>
		<tr height='35px'>
			<td><label>Partida 2:</label></td>
			<td>
			   <?= Html::input( 'text', 'part_id2', $part_id2,[
								'id'    => 'txPartId2',
								'class' => 'hidden'
							]);
				?>
			   <?= Html::input( 'text', 'part_formatoaux2', null,[
								'id'    => 'txPartFormatoAux2',
								'class' => 'form-control',
								'style' => 'width:100px; text-align: center;',
								'onchange'  => 'f_cambiaPartida2( $( this ).val() );',
								'disabled' => true 
							]);
				?>
			</td>
			<td width='35px' align='center'>
				<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
								'id'        => 'btPartida2',
								'class'     => 'bt-buscar',
								'style' => 'margin-top:1px;',
								'onclick'   => 'f_btPartida2()',
								'disabled' => true 
							]);
				?>
			</td>
			<td colspan='2'>
				<?= Html::input( 'text', 'part_nom2', $part_nom2, [
								'id'    => 'txPartNom2',
								'class' => 'form-control solo-lectura',
								'style' => 'width:100%;',
								'tabIndex'  => '-1',
							]);
				?>
			</td>
		</tr>
		<tr height='35px'>
			<td><label>Partida 3:</label></td>
			<td>
			   <?= Html::input( 'text', 'part_id3', $part_id3,[
								'id'    => 'txPartId3',
								'class' => 'hidden'
							]);
				?>
			   <?= Html::input( 'text', 'part_formatoaux3', null,[
								'id'    => 'txPartFormatoAux3',
								'class' => 'form-control',
								'style' => 'width:100px; text-align: center;',
								'onchange'  => 'f_cambiaPartida3( $( this ).val() );',
								'disabled' => true 
							]);
				?>
			</td>
			<td width='35px' align='center'>
				<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
								'id'        => 'btPartida3',
								'class'     => 'bt-buscar',
								'style' => 'margin-top:1px;',
								'onclick'   => 'f_btPartida3()',
								'disabled' => true 
							]);
				?>
			</td>
			<td colspan='2'>
				<?= Html::input( 'text', 'part_nom3', $part_nom3, [
								'id'    => 'txPartNom3',
								'class' => 'form-control solo-lectura',
								'style' => 'width:100%;',
								'tabIndex'  => '-1',
							]);
				?>
			</td>
		</tr>
		</table>
		<table>
		<tr>
			<td id='labelModif' width='600px' align='right'><label>Modificación:</label></td>
			<td>
				<?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModif','style'=>'width:150px;background:#E6E6FA;float:right;', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>

	        <div class="form-group" id='form_botones' style='display:none'>

			<?php

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btGrabarClick()']);
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarClick()']);
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>

			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']);
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarClick()']);
	    	?>
	        </div>
    	</div>
    	<?php

			if($consulta==0){
			?>
				<script>
					$('#txAccion').val(0);
					$('#form_botones').css('display','block');
					$('#form_botones_delete').css('display','none');

					$('#txCod').prop('readOnly',true);
					$('#txNombre').prop('disabled',false);
					
					$("#labelModif").css('display','none');
					$("#txModif").css('display','none');

					$('#btnNuevo').css("pointer-events", "none");
					$('#btnNuevo').css("opacity", 0.5);
					$('#GrillaTablaAux').css("pointer-events", "none");
					$('#GrillaTablaAux').css("color", "#ccc");
					$('#GrillaTablaAux Button').css("color", "#ccc");
					$('#GrillaTablaAux a').css("color", "#ccc");

				</script>

			<?php
		}else if($consulta==3){
			?>
			<script>
					$('#txAccion').val(3);
					$('#form_botones').css('display','block');
					$('#form_botones_delete').css('display','none');

					$('#txCod').prop('readOnly',true);
					$('#txNombre').prop('disabled',false);
					$('#txPartFormatoAux').prop('disabled',false);
					$('#txPartFormatoAux2').prop('disabled',false);
					$('#txPartFormatoAux3').prop('disabled',false);
					
					$("#labelModif").css('display','none');
					$("#txModif").css('display','none');

					$('#btnNuevo').css("pointer-events", "none");
					$('#btnNuevo').css("opacity", 0.5);
					$('#GrillaTablaAux').css("pointer-events", "none");
					$('#GrillaTablaAux').css("color", "#ccc");
					$('#GrillaTablaAux Button').css("color", "#ccc");
					$('#GrillaTablaAux a').css("color", "#ccc");

			</script>
			<?php
		}
		//--------------------------Mensaje-------------------------------


		if(isset($_GET['mensaje']) and $_GET['mensaje'] != ''){

			switch ($_GET['mensaje'])
			{
					case 'grabado' : $_GET['mensaje'] = 'Datos Grabados.'; break;
					case 'delete' : $_GET['mensaje'] = 'Datos Borrados.'; break;
					default : $_GET['mensaje'] = '';
			}

		}

		Alert::begin([
			'id' => 'MensajeInfoOFI',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none'
			],
		]);

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];

		Alert::end();

		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoOFI').alert('close');}, 5000)</script>";

		//-------------------------seccion de error-----------------------

		 	Pjax::begin(['id' => 'divError']);

				if(isset($_POST['error']) and $_POST['error'] != '') {
					echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_POST['error'] . '</ul></div>';
					echo "<script> $(document).ready(function(){ ActualizarPartidas('" . $part_id . "','" . $part_id2 . "','" . $part_id3 . "');  }) </script>";
					}

				if(isset($error) and $error != '') {
					echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
					echo "<script> $(document).ready(function(){ ActualizarPartidas('" . $part_id . "','" . $part_id2 . "','" . $part_id3 . "');  }) </script>";
				}

			Pjax::end();

		 //------------------------------------------------------------------------------------------------------------------------

    	ActiveForm::end();

    ?>

</div><!-- site-auxedit -->

<?php 
//INICIO Pjax Cambia Partida
Pjax::begin([ 'id' => 'pjaxCambiaPartida', 'enableReplaceState' => false, 'enablePushState' => false ]);

	echo '<script>$( "#txPartId" ).val("' . $part_id . '");</script>';
    echo '<script>$( "#txPartFormatoAux" ).val("' . $nropart . '");</script>';
    echo '<script>$( "#txPartNom" ).val("' . $part_nom . '");</script>';

Pjax::end();

Pjax::begin([ 'id' => 'pjaxCambiaPartida2', 'enableReplaceState' => false, 'enablePushState' => false ]);

	echo '<script>$( "#txPartId2" ).val("' . $part_id2 . '");</script>';
    echo '<script>$( "#txPartFormatoAux2" ).val("' . $nropart2 . '");</script>';
    echo '<script>$( "#txPartNom2" ).val("' . $part_nom2 . '");</script>';

Pjax::end();

Pjax::begin([ 'id' => 'pjaxCambiaPartida3', 'enableReplaceState' => false, 'enablePushState' => false ]);

	echo '<script>$( "#txPartId3" ).val("' . $part_id3 . '");</script>';
    echo '<script>$( "#txPartFormatoAux3" ).val("' . $nropart3 . '");</script>';
    echo '<script>$( "#txPartNom3" ).val("' . $part_nom3 . '");</script>';

Pjax::end();
//FIN Pjax Cambia Partida
?>

<!-- INICIO Modal Partidas -->
<?php

Pjax::begin(['id' => 'pjaxModalPartida', 'enablePushState' => false, 'enableReplaceState' => false]);

    Modal::begin([
		'id' => 'modalBuscarPartida',
		'size' => 'modal-normal',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Partida</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		 ],
	]);

    $parametros = [
        'nropart'   => 'Código',
        'nombre'    => 'Nombre',
        'formatoaux'    => 'Formato',
    ];

    $sort = [
        'attributes' => [
            'formatoaux',
        ],
        'defaultOrder' => [
            'formatoaux' => SORT_ASC,
        ],
    ];

	echo $this->render('//taux/auxbuscavarios', [
            'parametros'     => $parametros,
            'idcamponombre'  => "txPartNom",
			'idcampocod'     => "txPartFormatoAux",
			'idModal'        => "#modalBuscarPartida",
            'idAux'          => "partida",
			'campocod'       => 'formatoaux',
            'camponombre'    => 'nombre',
            'camposExtra'    => ['formato'],
			'tabla'          => 'fin.v_part',
            'criterio'       => 'tiene_hijo=false AND anio = ' . date('Y'),
			'claseGrilla'	 => 'grilla',
			'cantmostrar'	 => '20',
			'boton_id'		 => 'part',
            'sort'           => $sort,
		]);

    Modal::end();
?>	
	<script>
	$('#modalBuscarPartida').on('hidden.bs.modal', function () {
		f_cambiaPartida( $("#txPartFormatoAux").val() );
	})
	</script>
<?php 
Pjax::end();

Pjax::begin(['id' => 'pjaxModalPartida2', 'enablePushState' => false, 'enableReplaceState' => false]);

    Modal::begin([
		'id' => 'modalBuscarPartida2',
		'size' => 'modal-normal',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Partida</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		 ],
	]);

    $parametros = [
        'nropart'   => 'Código',
        'nombre'    => 'Nombre',
        'formatoaux'    => 'Formato',
    ];

    $sort = [
        'attributes' => [
            'formatoaux',
        ],
        'defaultOrder' => [
            'formatoaux' => SORT_ASC,
        ],
    ];

	echo $this->render('//taux/auxbuscavarios', [
            'parametros'     => $parametros,
            'idcamponombre'  => "txPartNom2",
			'idcampocod'     => "txPartFormatoAux2",
			'idModal'        => "#modalBuscarPartida2",
            'idAux'          => "partida2",
			'campocod'       => 'formatoaux',
            'camponombre'    => 'nombre',
            'camposExtra'    => ['formato'],
			'tabla'          => 'fin.v_part',
            'criterio'       => 'tiene_hijo=false AND anio = ' . date('Y'),
			'claseGrilla'	 => 'grilla',
			'cantmostrar'	 => '20',
			'boton_id'		 => 'part2',
            'sort'           => $sort,
		]);

    Modal::end();
?>	
	<script>
	$('#modalBuscarPartida2').on('hidden.bs.modal', function () {
		f_cambiaPartida2( $("#txPartFormatoAux2").val() );
	})
	</script>
<?php 
Pjax::end();

Pjax::begin(['id' => 'pjaxModalPartida3', 'enablePushState' => false, 'enableReplaceState' => false]);

    Modal::begin([
		'id' => 'modalBuscarPartida3',
		'size' => 'modal-normal',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Partida</h2>',
		'closeButton' => [
		  'label' => '<b>X</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		 ],
	]);

    $parametros = [
        'nropart'   => 'Código',
        'nombre'    => 'Nombre',
        'formatoaux'    => 'Formato',
    ];

    $sort = [
        'attributes' => [
            'formatoaux',
        ],
        'defaultOrder' => [
            'formatoaux' => SORT_ASC,
        ],
    ];

	echo $this->render('//taux/auxbuscavarios', [
            'parametros'     => $parametros,
            'idcamponombre'  => "txPartNom3",
			'idcampocod'     => "txPartFormatoAux3",
			'idModal'        => "#modalBuscarPartida3",
            'idAux'          => "partida3",
			'campocod'       => 'formatoaux',
            'camponombre'    => 'nombre',
            'camposExtra'    => ['formato'],
			'tabla'          => 'fin.v_part',
            'criterio'       => 'tiene_hijo=false AND anio = ' . date('Y'),
			'claseGrilla'	 => 'grilla',
			'cantmostrar'	 => '20',
			'boton_id'		 => 'part3',
            'sort'           => $sort,
		]);

    Modal::end();
?>	
	<script>
	$('#modalBuscarPartida3').on('hidden.bs.modal', function () {
		f_cambiaPartida3( $("#txPartFormatoAux3").val() );
	})
	</script>
<?php 
//Pjax::end();
?>
<!-- FIN Modal Partidas -->

<script type="text/javascript">

	function CargarControles(cod,nombre,nropart,part_nom,nropart2,part_nom2,nropart3,part_nom3,modif)
	{
			//event.stopPropagation();
			$("#txCod").val(cod);
			$("#txNombre").val(nombre);
			$("#txPartFormatoAux").val(nropart);
			$("#txPartNom").val(part_nom);
			$("#txPartFormatoAux2").val(nropart2);
			$("#txPartNom2").val(part_nom2);
			$("#txPartFormatoAux3").val(nropart3);
			$("#txPartNom3").val(part_nom3);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	}

	function btnNuevoClick(){

			$('#txAccion').val(0);
			$('#form_botones').css('display','block');
			$('#form_botones_delete').css('display','none');

			$('#txCod').prop('readOnly',true);
			$('#txNombre').prop('disabled',false);
			$('#txPartFormatoAux').prop('disabled',false);
			$('#btPartida').prop('disabled',false);
			$('#txPartFormatoAux2').prop('disabled',false);
			$('#btPartida2').prop('disabled',false);
			$('#txPartFormatoAux3').prop('disabled',false);
			$('#btPartida3').prop('disabled',false);
			
			$("#txCod").val('');
			$("#txNombre").val('');
			
			$('#txModif').val('');
			$("#labelModif").css('display','none');
			$("#txModif").css('display','none');

			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");

	}

	function btnModificarClick(cod,nombre,part_id,part_formatoaux,part_nom,part_id2,part_formatoaux2,part_nom2,part_id3,part_formatoaux3,part_nom3,modif){

			//event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');
			$('#form_botones_delete').css('display','none');

			$('#txCod').val(cod);
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val(nombre);
			$('#txNombre').prop('disabled',false);
			$('#txPartId').val(part_id);
			$('#txPartFormatoAux').val(part_formatoaux);
			$('#txPartFormatoAux').prop('disabled',false);
			$('#btPartida').prop('disabled',false);
			$('#txPartNom').val(part_nom);
			$('#txPartId2').val(part_id2);
			$('#txPartFormatoAux2').val(part_formatoaux2);
			$('#txPartFormatoAux2').prop('disabled',false);
			$('#btPartida2').prop('disabled',false);
			$('#txPartNom2').val(part_nom2);
			$('#txPartId3').val(part_id3);
			$('#txPartFormatoAux3').val(part_formatoaux3);
			$('#txPartFormatoAux3').prop('disabled',false);
			$('#btPartida3').prop('disabled',false);
			$('#txPartNom3').val(part_nom3);
			
			$("#labelModif").css('display','none');
			$('#txModif').val('');
			$("#txModif").css('display','none');

			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");

	}

	function btnEliminarClick(cod,nombre,part_id,part_formatoaux,part_nom,part_id2,part_formatoaux2,part_nom2,part_id3,part_formatoaux3,part_nom3,modif){

			//event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','block');

			$("#txCod").val(cod);
			$("#txNombre").val(nombre);
			$('#txPartId').val(part_id);
			$('#txPartFormatoAux').val(part_formatoaux);
			$('#txPartNom').val(part_nom);
			$('#txPartId2').val(part_id2);
			$('#txPartFormatoAux2').val(part_formatoaux2);
			$('#txPartNom2').val(part_nom2);
			$('#txPartId3').val(part_id3);
			$('#txPartFormatoAux3').val(part_formatoaux3);
			$('#txPartNom3').val(part_nom3);
			
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');

			$('#btnNuevo').css("pointer-events", "none");
			$('#btnNuevo').css("opacity", 0.5);
			$('#GrillaTablaAux').css("pointer-events", "none");
			$('#GrillaTablaAux').css("color", "#ccc");
			$('#GrillaTablaAux Button').css("color", "#ccc");
			$('#GrillaTablaAux a').css("color", "#ccc");

	}

	function btnCancelarClick(){

			$('.error-summary').css('display','none');
			$('#GrillaTablaAux').css("pointer-events", "all");
			$('#GrillaTablaAux').css("color", "#111111");
			$('#GrillaTablaAux a').css("color", "#337ab7");
			$('#GrillaTablaAux Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );

			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','none');
			$('#txCod').val('');
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val('');
			$('#txNombre').prop('disabled',true);
			$('#txPartFormatoAux').val('');
			$('#txPartFormatoAux').prop('disabled',true);
			$('#btPartida').prop('disabled',true);
			$('#txPartNom').val('');
			$('#txPartFormatoAux2').val('');
			$('#txPartFormatoAux2').prop('disabled',true);
			$('#btPartida2').prop('disabled',true);
			$('#txPartNom2').val('');
			$('#txPartFormatoAux3').val('');
			$('#txPartFormatoAux3').prop('disabled',true);
			$('#btPartida3').prop('disabled',true);
			$('#txPartNom3').val('');
			
			
			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
	}


	function btGrabarClick(){

			err = "";

			if ($("#txNombre").val()=="")
			{
				err += "<li>Ingrese un Nombre</li>";
			}

			if (err == "")
			{
				$("#frmSecretaria").submit();
			}else {
				$.pjax.reload(
				{
					container:"#divError",
					data:{
							error:err
						},
					method:"POST"
				});
			}
	}
	
	function f_cambiaPartida( part_id ){
		
		$.pjax.reload({
			container   : "#pjaxCambiaPartida",
			type        : "GET",
			replace     : false,
			push        : false,
			data:{
				"partida"   : part_id,
			},
		});
		
		$("#pjaxCambiaPartida").on("pjax:complete", function(){
			
			$("#pjaxCambiaPartida").off("pjax:complete");	
		});
		
	}
	
	function f_cambiaPartida2( part_id2 ){

		$.pjax.reload({
			container   : "#pjaxCambiaPartida2",
			type        : "GET",
			replace     : false,
			push        : false,
			data:{
				"partida2"   : part_id2,
			},
		});
		
		$("#pjaxCambiaPartida2").on("pjax:complete", function(){
			
			$("#pjaxCambiaPartida2").off("pjax:complete");	
		});
		
	}
	
	function f_cambiaPartida3( part_id3 ){

		$.pjax.reload({
			container   : "#pjaxCambiaPartida3",
			type        : "GET",
			replace     : false,
			push        : false,
			data:{
				"partida3"   : part_id3,
			},
		});
		
		$("#pjaxCambiaPartida3").on("pjax:complete", function(){
			
			$("#pjaxCambiaPartida3").off("pjax:complete");	
		});
		
	}
	
	function ActualizarPartidas( part_id, part_id2, part_id3 ){

		f_cambiaPartida( part_id );
		
		$("#pjaxCambiaPartida").on("pjax:complete", function(){
			
			f_cambiaPartida2( part_id2 );
			$("#pjaxCambiaPartida").off("pjax:complete");	
		});
		
		$("#pjaxCambiaPartida2").on("pjax:complete", function(){
			
			f_cambiaPartida3( part_id3 );
			$("#pjaxCambiaPartida2").off("pjax:complete");	
		});
		
	}

	function f_btPartida(){

		$( "#modalBuscarPartida" ).modal( "toggle" );
	}
	
	function f_btPartida2(){

		$( "#modalBuscarPartida2" ).modal( "toggle" );
	}
	
	function f_btPartida3(){

		$( "#modalBuscarPartida3" ).modal( "toggle" );
	}

	$(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
	});

</script>
