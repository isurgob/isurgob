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

$title = 'Oficinas';
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
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevo', 'onclick' => 'btnNuevoAuxOficina();'])
    		?>
    	</td>
    </tr>
	<tr>
		<td colspan='2'> 
			<label> Secretaria: </label> 
			<?=  Html::dropDownList('filtroSec',null,utb::getAux('sam.muni_sec','cod','nombre','2'),['id'=>'filtroSec','class' => 'form-control', 'onchange'=>'FiltroChange()','style' => 'width:150px;']);?> 
		</td>
	</tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>

	<?php
	Pjax::begin(['id'=>'PjaxBuscarTAux']);
		
		if (isset($_POST['condicion'])){
			$tabla = null;
		    $tabla = (new tablaAux())->CargarTablaOfocina($_POST['condicion']);
		}
		
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $tabla,
			'headerRowOptions' => ['class' => 'grilla'],
			'sorter' => 'false',
			'rowOptions' => function ($model,$key,$index,$grid)
							{
								return [
									'onclick' => 'CargarControles('.$model['ofi_id'].',"'.
																	$model['nombre'].'","'.
																	$model['resp'].'",'.
																	$model['sec_id'].',"'.
																	$model['modif'].'");'
								];
							},
			'columns' => [
            		['attribute'=>'ofi_id','label' => 'Cod', 'contentOptions'=>['style'=>'width:30px;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'nombre','label' => 'Nombre' ,'contentOptions'=>['style'=>'width:200px;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'resp','header' => 'Responsable', 'contentOptions'=>['style'=>'width:200px;text-align:left;', 'class' => 'grilla']],
					['attribute'=>'sec_nom','header' => 'Secretaría', 'contentOptions'=>['style'=>'width:200px;text-align:left;', 'class' => 'grilla']],

            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:35px;text-align:center;','class' => 'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'event.stopPropagation();' .
																	 'btnModificarAuxOficina('.$model['ofi_id'].',"'.
																							   $model['nombre'].'","'.
																							   $model['resp'].'",'.
																							   $model['sec_id'].',"'.
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
																	 'btnEliminarAuxOficina('.$model['ofi_id'].',"'.
																							  $model['nombre'].'","'.
																							  $model['resp'].'",'.
																							  $model['sec_id'].',"'.
																							  $model['modif'].'");'
													]
            									);
		            						}
						    			]
						    	   ],
								],
							]);
	Pjax::end();						

	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => '133'],'id'=>'frmOficina']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);

	 ?>
	<div class="form" style='padding:15px 5px; margin:10px 0px'>
		<table border='0'>
		<tr height='35px'>
			<td width='35px'><label>Oficina:</label></td>
			<td>
				<?= Html::input('text', 'txCod', $cod, ['class' => 'form-control','id'=>'txCod','maxlength'=> '10','style'=>'width:70px;', 'readonly' => true]); ?>
			</td>
			<td align='left'><label style='margin-left:7px;'>Nombre:</label></td>
			<td>
			  <?= Html::input('text', 'txNombre',$nombre,['id' => 'txNombre','class' => 'form-control','maxlength'=>'35' ,'style' => 'width:350px;', 'disabled' => true ]); ?>
			</td>
		</tr>

		<tr height='35px'>
			<td><label>Secretaria:</label></td>
			<td>
			   <?=  Html::dropDownList('sec_id',$sec_id,utb::getAux('sam.muni_sec','cod','nombre','1'),['id'=>'sec_id','class' => 'form-control', 'style' => 'width:150px;','disabled' => true]);?>
			</td>
			<td width='85px' align='right'><label>Responsable:</label></td>
			<td>
				<?= Html::input('text', 'resp',$resp,['id' => 'resp','class' => 'form-control','maxlength'=>'45' ,'style' => 'width:350px;', 'disabled' => true ]); ?>
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
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxOficina()']);
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_delete' style='display:none'>

			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClick()']);
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxOficina()']);
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
					$('#resp').prop('disabled',false);
					$('#sec_id').prop('disabled',false);

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
					$('#resp').prop('disabled',false);
					$('#sec_id').prop('disabled',false);

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
					}

				if(isset($error) and $error != '') {
				echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
				}

			Pjax::end();

		 //------------------------------------------------------------------------------------------------------------------------

    	ActiveForm::end();

    ?>

</div><!-- site-auxedit -->

<script type="text/javascript">

	function CargarControles(ofi_id,nombre,resp,sec_id,modif)
	{
			//event.stopPropagation();
			$("#txCod").val(ofi_id);
			$("#txNombre").val(nombre);
			$("#resp").val(resp);
			$("#sec_id").val(sec_id);
			$("#txModif").val(modif);
			$("#txModif").prop('display','block');
			$("#labelModif").css('display','block');
	}

	function btnNuevoAuxOficina(){

			$('#txAccion').val(0);
			$('#form_botones').css('display','block');
			$('#form_botones_delete').css('display','none');

			$('#txCod').prop('readOnly',true);
			$('#txNombre').prop('disabled',false);
			$('#resp').prop('disabled',false);
			$('#sec_id').prop('disabled',false);

			$("#txCod").val('');
			$("#sec_id option:first-of-type").attr("selected", "selected");
			$("#txNombre").val('');
			$("#resp").val('');

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

	function btnModificarAuxOficina(ofi_id,nombre,responsable,sec_id,modif){

			//event.stopPropagation();
			$('#txAccion').val(3);
			$('#form_botones').css('display','block');
			$('#form_botones_delete').css('display','none');

			$('#txCod').val(ofi_id);
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val(nombre);
			$('#txNombre').prop('disabled',false);
			$('#resp').val(responsable);
			$('#resp').prop('disabled',false);
			$('#sec_id').val(sec_id);
			$('#sec_id').prop('disabled',false);

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

	function btnEliminarAuxOficina(ofi_id,nombre,responsable,sec_id,modif){

			//event.stopPropagation();
			$('#txAccion').val(2);
			$('#form_botones').css('display','none');
			$('#form_botones_delete').css('display','block');

			$("#txCod").val(ofi_id);
			$("#txNombre").val(nombre);
			$("#resp").val(responsable);
			$("#sec_id").val(sec_id);
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

	function btnCancelarAuxOficina(){

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
			$("#sec_id option:first-of-type").attr("selected", "selected");
			$('#sec_id').prop('disabled',true);
			$('#txNombre').val('');
			$('#txNombre').prop('disabled',true);
			$('#resp').val('');
			$('#resp').prop('disabled',true);

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
				$('#sec_id').prop('disabled',false);
				$("#frmOficina").submit();
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
	
	function FiltroChange()
	{
		cond = '';
		if ($("#filtroSec").val() != 0) cond = "sec_id="+$("#filtroSec").val();
		
		$.pjax.reload(
		{
			container:"#PjaxBuscarTAux",
			data:{
					condicion:cond
				},
			method:"POST"
		});	
	}

	$(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
	});

</script>
