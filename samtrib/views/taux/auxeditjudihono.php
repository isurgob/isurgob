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

$title = 'Honorarios Judiciales';
$this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares','url' => ['taux']];
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
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxJudiHono();'])
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>

	<?php

		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $tabla,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid)
							{
								return [
									'onclick' => 'CargarControles("'.$model['est'].'",'.$model['supuesto'].','.$model['deuda_desde'].','.$model['deuda_hasta'].','.$model['hono_min'].','.$model['hono_porc'].','.$model['gastos'].',"'. utb::getFormatoModif($model['usrmod'], $model['fchmod']) .'")'
								];
							},
			'columns' => [
            		['attribute'=>'est','header' => 'Estado', 'contentOptions'=>['style'=>'width:10%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'supuesto','header' => 'Supuesto' ,'value' => function($data) { return (utb::getCampo('judi_tsupuesto','cod='.$data['supuesto'])); },
					'contentOptions'=>['style'=>'width:32%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'deuda_desde','header' => 'Deuda Desde', 'contentOptions'=>['style'=>'width:10%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'deuda_hasta','header' => 'Deuda hasta' ,'contentOptions'=>['style'=>'width:10%;text-align:center;', 'class' => 'grilla']],
                    ['attribute'=>'hono_min','header' => 'Honorario Minimo', 'contentOptions'=>['style'=>'width:10%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'hono_porc','header' => 'Porc. Calculo Honorarios' ,'contentOptions'=>['style'=>'width:10%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'gastos','header' => 'Gastos Judiciales', 'contentOptions'=>['style'=>'width:10%;text-align:center;', 'class' => 'grilla']],

            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:18%;text-align:center;','class'=>'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxJudiHono("'.$model['est'].'",'.$model['supuesto'].','.$model['deuda_desde'].','.$model['deuda_hasta'].','.$model['hono_min'].','.$model['hono_porc'].','.$model['gastos'].',"'. utb::getFormatoModif($model['usrmod'], $model['fchmod']) .'");'
													]
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxJudiHono("'.$model['est'].'",'.$model['supuesto'].','.$model['deuda_desde'].','.$model['deuda_hasta'].','.$model['hono_min'].','.$model['hono_porc'].','.$model['gastos'].',"'. utb::getFormatoModif($model['usrmod'], $model['fchmod']) . '");'
													]
            									);
	            						}
				    			]
				    	   ],

					],
				]);

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmJudiHono']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin:10px 0px'>
		<table border='0' width='95%' align='center'>
		<tr>
			<td width='115px'><label>Estado: </label></td>
			<td>
				<?= Html::input('text', 'est', $est, ['class' => 'form-control','id'=>'est','maxlength'=> '1','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
			<td width='110px'><label>Supuesto: </label></td>
			<td>
			   <?=  Html::dropDownList('supuesto',$supuesto,utb::getAux('judi_tsupuesto','cod','nombre',0),['id'=>'supuesto','style'=>'width:148px;','class' => 'form-control', 'disabled' => true]);?>
			</td>
		</tr>
		<tr>
			<td><label>Deuda Desde: </label></td>
			<td>
			  <?= Html::input('text', 'deuda_desde',$deuda_desde,['id' => 'deuda_desde','class' => 'form-control','maxlength'=>'13' ,'style' => 'width:100px;', 'readonly' => true ]); ?>
			</td>
			<td><label>Deuda Hasta: </label></td>
			<td>
				<?= Html::input('text', 'deuda_hasta',$deuda_hasta,['id' => 'deuda_hasta','class' => 'form-control','maxlength'=>'13' ,'style' => 'width:100px;', 'readonly' => true ]); ?>
			</td>
		</tr>
		<tr>
			<td><label>Honorario Minimo: </label></td>
			<td>
				<?= Html::input('text', 'hono_min', $hono_min, ['class' => 'form-control','id'=>'hono_min','maxlength'=> '13','style'=>'width:100px;', 'disabled' => true]); ?>
			</td>
			<td><label>Porc. Honorarios: </label></td>
			<td>
			   <?= Html::input('text', 'hono_porc',$hono_porc,['id' => 'hono_porc','class' => 'form-control','maxlength'=>'5' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		<tr>
			<td><label>Gastos Judiciales: </label></td>
			<td>
				<?= Html::input('text', 'gastos',$gastos,['id' => 'gastos','class' => 'form-control','maxlength'=>'13' ,'style' => 'width:100px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		<tr>
			<td></td><td></td>
			<td id='labelModif'><label>Modificación:</label></td>
			<td>
				<?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModif','style'=>'width:150px;background:#E6E6FA;', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>

	        <div class="form-group" id='form_botones' style='display:none'>

			<?php

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btGrabarClick()']);
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxJudiHono()']);
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>

			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']);
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxJudiHono()']);
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

					$('#est').prop('readOnly',false);
					$('#supuesto').prop('disabled',false);
					$('#deuda_desde').prop('readOnly',false);
					$('#deuda_hasta').prop('readOnly',false);
					$('#hono_min').prop('disabled',false);
					$('#hono_porc').prop('disabled',false);
					$('#gastos').prop('disabled',false);
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

					$('#est').prop('readOnly',true);
					$('#supuesto').prop('disabled',true);
					$('#deuda_desde').prop('readOnly',true);
					$('#deuda_hasta').prop('readOnly',true);
					$('#hono_min').prop('disabled',false);
					$('#hono_porc').prop('disabled',false);
					$('#gastos').prop('disabled',false);
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
			'id' => 'MensajeInfoJH',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none'
			],
		]);

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];

		Alert::end();

		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoJH').alert('close');}, 5000)</script>";

		//-------------------------seccion de error-----------------------

		 	Pjax::begin(['id' => 'divError']);

				if(isset($_POST['error']) and $_POST['error'] != '') {
					echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_POST['error'] . '</ul></div>';
					}

				if(isset($error) and $error != '') {
				echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
				}

			Pjax::end();

		 //------------------------------------------------------------------------------------------------------------------------

    	ActiveForm::end();

    ?>

</div><!-- site-auxedit -->

<script>

	function CargarControles(est,supuesto,deuda_desde,deuda_hasta,hono_min,hono_porc,gastos,modif)
	{
			event.stopPropagation();
			$("#est").val(est);
			$("#supuesto").val(supuesto);
			$("#deuda_desde").val(deuda_desde);
			$("#deuda_hasta").val(deuda_hasta);
			$("#hono_min").val(hono_min);
			$("#hono_porc").val(hono_porc);
			$("#gastos").val(gastos);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');

	}

	function btnNuevoAuxJudiHono(){

			$('#txAccion').val(0);
			$('#form_botones').css('display','block');
			$('#form_botones_delete').css('display','none');

			$('#est').prop('readOnly',false);
			$('#supuesto').prop('disabled',false);
			$('#deuda_desde').prop('readOnly',false);
			$('#deuda_hasta').prop('readOnly',false);
			$('#hono_min').prop('disabled',false);
			$('#hono_porc').prop('disabled',false);
			$('#gastos').prop('disabled',false);

			$("#est").val('');
			$("#supuesto option:first-of-type").attr("selected", "selected");
			$("#deuda_desde").val('');
			$("#deuda_hasta").val('');
			$("#hono_min").val('');
			$("#hono_porc").val('');
			$("#gastos").val('');

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

	function btnModificarAuxJudiHono(est,supuesto,deuda_desde,deuda_hasta,hono_min,hono_porc,gastos,modif){

			event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');
			$('#form_botones_delete').css('display','none');

			$('#est').val(est);
			$('#est').prop('readOnly',true);
			$('#supuesto').val(supuesto);
			$('#supuesto').prop('disabled',true);
			$('#deuda_desde').val(deuda_desde);
			$('#deuda_desde').prop('readOnly',true);
			$('#deuda_hasta').val(deuda_hasta);
			$('#deuda_hasta').prop('readOnly',true);
			$("#hono_min").val(hono_min);
			$('#hono_min').prop('disabled',false);
			$("#hono_porc").val(hono_porc);
			$('#hono_porc').prop('disabled',false);
			$("#gastos").val(gastos);
			$('#gastos').prop('disabled',false);

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

	function btnEliminarAuxJudiHono(est,supuesto,deuda_desde,deuda_hasta,hono_min,hono_porc,gastos,modif){

			event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','block');

			$("#est").val(est);
			$("#supuesto").val(supuesto);
			$("#deuda_desde").val(deuda_desde);
			$("#deuda_hasta").val(deuda_hasta);
			$("#hono_min").val(hono_min);
			$("#hono_porc").val(hono_porc);
			$("#gastos").val(gastos);
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

	function btnCancelarAuxJudiHono(){

			$('.error-summary').css('display','none');
			$('#GrillaTablaAux').css("pointer-events", "all");
			$('#GrillaTablaAux').css("color", "#111111");
			$('#GrillaTablaAux a').css("color", "#337ab7");
			$('#GrillaTablaAux Button').css("color", "#337ab7");
			$('#btnNuevo').css("pointer-events", "all");
			$('#btnNuevo').css("opacity", 1 );

			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','none');
			$('#est').val('');
			$('#est').prop('readOnly',true);
			$("#supuesto option:first-of-type").attr("selected", "selected");
			$('#supuesto').prop('disabled',true);
			$('#deuda_desde').val('');
			$('#deuda_desde').prop('readOnly',true);
			$('#deuda_hasta').val('');
			$('#deuda_hasta').prop('readOnly',true);
			$('#hono_min').val('');
			$('#hono_min').prop('disabled',true);
			$('#hono_porc').val('');
			$('#hono_porc').prop('disabled',true);
			$('#gastos').val('');
			$('#gastos').prop('disabled',true);
			$('#txModif').val('');
			$('#txModif').prop('disabled',true);
			$('#txModif').css('display','block');
			$("#labelModif").css('display','block');
	}


	function btGrabarClick(){

			err = "";

			if ($("#est").val()=="")
			{
				err += "<li>Ingrese un Estado</li>";
			}
			if ($("#deuda_desde").val()=="")
			{
				err += "<li>Ingrese un monto de deuda desde</li>";
			}
			if ($("#deuda_hasta").val()=="")
			{
				err += "<li>Ingrese un monto de deuda hasta</li>";
			}
			if ($("#hono_min").val()=="")
			{
				err += "<li>Ingrese un honorario minimo</li>";
			}
			if ($("#hono_porc").val()=="")
			{
				err += "<li>Ingrese un porcensaje para el calculo de honorarios</li>";
			}
			if ($("#gastos").val()=="")
			{
				err += "<li>Ingrese un gasto judicial</li>";
			}
			if (!(isNaN($("#est").val())))
			{
				err += "<li>El campo estado debe ser un caracter</li>";
			}
			if (isNaN($("#deuda_desde").val()))
			{
				err += "<li>El campo deuda desde debe ser un numero</li>";
			}
			if (isNaN($("#deuda_hasta").val()))
			{
				err += "<li>El campo deuda hasta debe ser un numero</li>";
			}
			if (isNaN($("#hono_min").val()))
			{
				err += "<li>El campo honorarios minimos debe ser un numero</li>";
			}
			if($("#hono_min").val() > Math.pow(10, 11) - 1)
				err += "<li>El campo honorarios misnimos desde debe ser menor o igual a " + (Math.pow(10, 11) - 1) + "</li>";

			if (isNaN($("#hono_porc").val()))
			{
				err += "<li>El campo porcentaje para el calculo de honorarios debe ser un numero</li>";
			}
			if (isNaN($("#gastos").val()))
			{
				err += "<li>El campo gastos debe ser un numero</li>";
			}
			if($("#gastos").val() > Math.pow(10, 11) - 1)
				err += "<li>El campo gastos judiciales desde debe ser menor o igual a " + (Math.pow(10, 11) - 1) + "</li>";

			if ($("#deuda_desde").val() < 0)
			{
				err += "<li>El campo deuda desde debe ser mayor o igual a cero</li>";
			}
			if($("#deuda_desde").val() > Math.pow(10, 11) - 1)
				err += "<li>El campo deuda desde debe ser menor o igual a " + (Math.pow(10, 11) - 1) + "</li>";
			if ($("#deuda_hasta").val() < 0)
			{
				err += "<li>El campo deuda hasta debe ser mayor o igual a cero</li>";
			}
			if($("#deuda_hasta").val() > Math.pow(10, 11) - 1)
				err += "<li>El campo deuda hasta debe ser menor o igual a " + (Math.pow(10, 11) - 1) + "</li>";
			if ($("#deuda_desde").val() > $("#deuda_hasta").val())
			{
				err += "<li>Deuda desde debe ser menor o igual a deuda hasta</li>";
			}
			if ($("#hono_min").val() < 0)
			{
				err += "<li>El campo honoraios minimos debe ser mayor a cero</li>";
			}
			if ($("#hono_porc").val() < 0 || $("#hono_porc").val() > 100)
			{
				err += "<li>El campo porcentaje para el calculo de honoraios debe ser mayor a 0 (cero) y menor a 100 (cien)</li>";
			}
			if ($("#gastos").val() < 0)
			{
				err += "<li>El campo gastos judiciales debe ser mayor o igual a cero</li>";
			}

			if (err == "")
			{
				$('#supuesto').prop('disabled',false);
				$("#frmJudiHono").submit();
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

	$(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
	});

</script>
