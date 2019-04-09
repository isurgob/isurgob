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

$title = 'Cuadros y Cuerpos';
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
    	<td><h1><?= "Cuadros" ?></h1></td>
    	<td align='right'>
    		<?php
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevoCemCuadro', 'onclick' => 'btnNuevoAuxCemCuadro();'])
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>

<table width='100%' border='0'>
<tr>
<td valign='top'>
<?php
	echo GridView::widget([
			'id' => 'GrillaTablaAuxCemCuadro',
     	    'dataProvider' => $tabla,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid)
							{
								return [
									'onclick' => 'CargarControlesCemCuadro("'.$model['cuadro_id'].'","'.
																			  $model['nombre'].'","'.
																			  $model['tipo'].'",'.
																			  $model['piso'].','.
																			  $model['fila'].','.
																			  $model['nume'].',"'.
																			  $model['modif'].'"),$.pjax.reload({container:"#idGrid",data:{cuadro:$("#txCod").val()},method:"POST"})'
								];
							},
			'columns' => [
            		['attribute'=>'cuadro_id','header' => 'Cod', 'contentOptions'=>['style'=>'width:10%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'nombre','header' => 'Nombre' ,'contentOptions'=>['style'=>'width:75%;text-align:left;', 'class' => 'grilla']],

            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:15%;text-align:center;','class'=>'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxCemCuadro("'.$model['cuadro_id'].'","'.
																								  $model['nombre'].'","'.
																								  $model['tipo'].'",'.
																								  $model['piso'].','.
																								  $model['fila'].','.
																								  $model['nume'].',"'.
																								  $model['modif'].'");'
													]
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxCemCuadro("'.$model['cuadro_id'].'","'.
																								 $model['nombre'].'","'.
																								 $model['tipo'].'",'.
																								 $model['piso'].','.
																								 $model['fila'].','.
																								 $model['nume'].',"'.
																								 $model['modif'].'");'
																								]
											            									);
													            						}
																    				]
																    		   ],

																		],
																	]);
		?>
		</td>
		<td valign='top'>
		<?php
	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmCemCuadro']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);
	 	echo Html::input('hidden', 'txForm',"", ['id'=>'txForm']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px;margin-top:16px;margin-left:5px;'>
		<table border='0'>
		<tr>
			<td width='45px'><label>Codigo: </label></td>
			<td>
				<?= Html::input('text', 'txCod', $cuadro_id, ['class' => 'form-control','id'=>'txCod','maxlength'=> '3','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
			<td width='70px' align='right'><label>Nombre: </label></td>
			<td>
			   <?= Html::input('text', 'txNombre',$nombre,['id' => 'txNombre','class' => 'form-control','maxlength'=>'20' ,'style' => 'width:258px;', 'disabled' => true ]); ?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr>
			<td width='45px' align='left'><label>Tipo: </label></td>
			<td>
			  <?=  Html::dropDownList('tipo',$tipo,utb::getAux('cem_tipo','cod','nombre',0),['id'=>'tipoCemCuadro','class' => 'form-control', 'style'=>'width:171px','disabled' => true]);?>
			</td>
			<td width='50px' align='right'><label>Piso </label></td>
			<td>
				<?= Html::checkbox('piso',$piso,['class' => 'form-control','id'=>'piso','disabled' => true]); ?>
			</td>
			<td width='50px' align='right'><label>Fila </label></td>
			<td>
				<?= Html::checkbox('fila',$fila,['class' => 'form-control','id'=>'fila','disabled' => true]); ?>
			</td>
			<td width='60px' align='right'><label>Numero </label></td>
			<td>
				<?= Html::checkbox('nume',$nume,['class' => 'form-control','id'=>'nume','disabled' => true]); ?>
			</td>
		</tr>
		</table>

		<table border='0'>
		<tr>
			<td id='labelModifCemCuadro' width='265px' align='right'><label>Modificación:</label></td>
			<td>
				<?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModifCemCuadro','style'=>'width:150px;background:#E6E6FA;float:right;', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>

	        <div class="form-group" id='form_botonesCemCuadro' style='display:none'>

			<?php

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btGrabarClickCemCuadro()']);
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxCemCuadro()']);
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_deleteCemCuadro' style='display:none'>

			<?php
				echo Html::submitButton('Eliminar', ['class' => 'btn btn-danger']);
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxCemCuadro()']);
	    	?>
	        </div>
    	</div>

			<?php
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
			'id' => 'MensajeInfoAUXT',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none'
			],
		]);

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];

		Alert::end();

		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoAUXT').alert('close');}, 5000)</script>";

		//-------------------------seccion de error-----------------------

		 	Pjax::begin(['id' => 'divErrorCemCuadro']);

				if(isset($_POST['error']) and $_POST['error'] != '') {
					echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_POST['error'] . '</ul></div>';
					}

				if(isset($error) and $error != '') {
				echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
				}

			Pjax::end();

			//------------------------------------------------------------------------------------------------------------------------

		 	Pjax::begin(['id' => 'divValidarCemCuadro']);

		 		if(isset($_GET['idCuadro'])){
		 		$err = $model->validarTablaAuxCemCuadro($_GET['consulta'],$_GET['idCuadro'],$_GET['nombre']);
		 		if($err!=""){
			 		echo "<script>$.pjax.reload(
					{
						container:'#divErrorCemCuadro',
						data:{
								error:'".$err."'
							},
						method:'POST'
					});</script>";

		 		}else{
		 			echo "<script>$('#frmCemCuadro').submit();</script>";
		 		}}
			Pjax::end();

		//--------------------------------------------------------------------------------------------------------------------

    	ActiveForm::end();
    ?>
    </td>
    </tr>
	</table>
</div><!-- site-auxedit -->

<script>

	function CargarControlesCemCuadro(cuadro_id,nombre,tipo,piso,fila,nume,modif)
	{
			event.stopPropagation();
			$("#frmCemCuadro :input[id=txCod]").val(cuadro_id);
			$("#frmCemCuadro :input[id=txNombre]").val(nombre);
			$("#tipoCemCuadro").val(tipo);
			if(piso==1){$('#piso').prop('checked',true);}else{$('#piso').prop('checked',false);}
			if(fila==1){$('#fila').prop('checked',true);}else{$('#fila').prop('checked',false);}
			if(nume==1){$('#nume').prop('checked',true);}else{$('#nume').prop('checked',false);}
			$("#txModifCemCuadro").val(modif);
			$("#txModifCemCuadro").prop('display','block');
			$("#labelModifCemCuadro").css('display','block');

	}

	function btnNuevoAuxCemCuadro(){

			$('#form_botonesCemCuadro').css('display','block');
			$('#form_botones_deleteCemCuadro').css('display','none');
			$("#frmCemCuadro :hidden[id=txForm]").val(0);
			$("#frmCemCuadro :hidden[id=txAccion]").val(0);

			$('#txCod').val('');
			$('#txCod').prop('readOnly',false);
			$('#txNombre').val('');
			$('#txNombre').prop('disabled',false);
			$("#tipoCemCuadro option:first-of-type").attr("selected", "selected");
			$('#tipoCemCuadro').prop('disabled',false);
			$('#piso').prop('disabled',false);
			$('#piso').prop("checked","");
			$('#fila').prop('disabled',false);
			$('#fila').prop("checked","");
			$('#nume').prop('disabled',false);
			$('#nume').prop("checked","");
			$('#txModifCemCuadro').val('');
			$("#labelModifCemCuadro").css('display','none');
			$("#txModifCemCuadro").css('display','none');

			$('#btnNuevoCemCuadro').css("pointer-events", "none");
			$('#btnNuevoCemCuadro').css("opacity", 0.5);
			$('#GrillaTablaAuxCemCuadro').css("pointer-events", "none");
			$('#GrillaTablaAuxCemCuadro').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuadro Button').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuadro a').css("color", "#ccc");
	}

	function btnModificarAuxCemCuadro(cuadro_id,nombre,tipo,piso,fila,nume,modif){
			event.stopPropagation();
			$('#form_botonesCemCuadro').css('display','block');
			$('#form_botones_deleteCemCuadro').css('display','none');
			$("#frmCemCuadro :hidden[id=txForm]").val(0);
			$("#frmCemCuadro :hidden[id=txAccion]").val(3);

			$("#frmCemCuadro :input[id=txCod]").val(cuadro_id);
			$('#txCod').prop('readOnly',true);
			$("#frmCemCuadro :input[id=txNombre]").val(nombre);
			$('#txNombre').prop('disabled',false);
			$("#tipoCemCuadro").val(tipo);
			$('#tipoCemCuadro').prop('disabled',false);
			$('#piso').prop('disabled',false);
			if(piso==1){$('#piso').prop('checked',true);}else{$('#piso').prop('checked',false);}
			$('#fila').prop('disabled',false);
			if(fila==1){$('#fila').prop('checked',true);}else{$('#fila').prop('checked',false);}
			$('#nume').prop('disabled',false);
			if(nume==1){$('#nume').prop('checked',true);}else{$('#nume').prop('checked',false);}
			$("#labelModifCemCuadro").css('display','none');
			$('#txModifCemCuadro').val('');
			$("#txModifCemCuadro").css('display','none');

			$('#btnNuevoCemCuadro').css("pointer-events", "none");
			$('#btnNuevoCemCuadro').css("opacity", 0.5);
			$('#GrillaTablaAuxCemCuadro').css("pointer-events", "none");
			$('#GrillaTablaAuxCemCuadro').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuadro Button').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuadro a').css("color", "#ccc");
	}

	function btnEliminarAuxCemCuadro(cuadro_id,nombre,tipo,piso,fila,nume,modif){
			event.stopPropagation();
			$('#form_botonesCemCuadro').css('display','none');
			$('#form_botones_deleteCemCuadro').css('display','block');
			$("#frmCemCuadro :hidden[id=txForm]").val(0);
			$("#frmCemCuadro :hidden[id=txAccion]").val(2);


			$("#frmCemCuadro :input[id=txCod]").val(cuadro_id);
			$("#frmCemCuadro :input[id=txNombre]").val(nombre);
			$("#tipoCemCuadro").val(tipo);
			if(piso==1){$('#piso').prop('checked',true);}else{$('#piso').prop('checked',false);}
			if(fila==1){$('#fila').prop('checked',true);}else{$('#fila').prop('checked',false);}
			if(nume==1){$('#nume').prop('checked',true);}else{$('#nume').prop('checked',false);}
			$("#txModifCemCuadro").val(modif);
			$("#txModifCemCuadro").prop('display','block');
			$("#labelModifCemCuadro").css('display','block');

			$('#btnNuevoCemCuadro').css("pointer-events", "none");
			$('#btnNuevoCemCuadro').css("opacity", 0.5);
			$('#GrillaTablaAuxCemCuadro').css("pointer-events", "none");
			$('#GrillaTablaAuxCemCuadro').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuadro Button').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuadro a').css("color", "#ccc");
	}

	function btnCancelarAuxCemCuadro(){

			$('.error-summary').css('display','none');
			$('#GrillaTablaAuxCemCuadro').css("pointer-events", "all");
			$('#GrillaTablaAuxCemCuadro').css("color", "#111111");
			$('#GrillaTablaAuxCemCuadro a').css("color", "#337ab7");
			$('#GrillaTablaAuxCemCuadro Button').css("color", "#337ab7");
			$('#btnNuevoCemCuadro').css("pointer-events", "all");
			$('#btnNuevoCemCuadro').css("opacity", 1 );

			$('#form_botonesCemCuadro').css('display','none');
			$('#form_botones_deleteCemCuadro').css('display','none');
			$('#txCod').val('');
			$('#txCod').prop('readOnly',true);
			$('#txNombre').val('');
			$('#txNombre').prop('disabled',true);
			$("#tipoCemCuadro option:first-of-type").attr("selected", "selected");
			$('#tipoCemCuadro').prop('disabled',true);
			$('#piso').prop('disabled',true);
			$('#piso').prop("checked","");
			$('#fila').prop('disabled',true);
			$('#fila').prop("checked","");
			$('#nume').prop('disabled',true);
			$('#nume').prop("checked","");
			$('#txModifCemCuadro').val('');
			$('#txModifCemCuadro').prop('disabled',true);
			$('#txModifCemCuadro').css('display','block');
			$("#labelModifCemCuadro").css('display','block');
	}


	function btGrabarClickCemCuadro(){

			err="";
			if($('#txCod').val()==""){
				err += "<li>Ingrese un Codigo</li>";
			}
			if($("#frmCemCuadro :input[id=txNombre]").val()==""){
				err += "<li>Ingrese un Nombre</li>";
			}

			if(err == ""){

			$.pjax.reload(
			{
				container:'#divValidarCemCuadro',
				data:{
					idCuadro:$("#frmCemCuadro :input[id=txCod]").val(),
					nombre:$("#frmCemCuadro :input[id=txNombre]").val(),
					consulta:$("#frmCemCuadro :hidden[id=txAccion]").val()
					},
				method:'GET'
			});
			}else{

			$.pjax.reload(
				{
					container:'#divErrorCemCuadro',
					data:{
							error:err
						},
					method:'POST'
				});
		      }
	       }

	$(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
	});

</script>

    <table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px;margin-top:50px;'>
    <tr>
    	<td><h1><?= "Cuerpos" ?></h1></td>
    	<td align='right'>
    		<?php
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevoCemCuerpo', 'onclick' => 'btnNuevoAuxCemCuerpo();'])
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>

    <table width='100%' border='0'>
    <tr>
    <td width='40%' valign='top'>
	<?php

    	Pjax::begin(['id' => 'idGrid']);// comienza bloque de grilla

    	$criterio = "";

    	echo "<script> $(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
		}); </script>";

    	 if (isset($_POST['cuadro']) and $_POST['cuadro'] !== null and $_POST['cuadro'] !=='')
    	 {
    	 	$criterio = "cem_cuerpo.cuadro_id='".$_POST['cuadro']."'";
    	 }

		 echo GridView::widget([
			'id' => 'GrillaTablaAuxCemCuerpo',
     	    'dataProvider' => $model->cargarCemCuepos($criterio),
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid)
							{
								return [
									'onclick' => 'CargarControlesCemCuerpo("'.$model['cuadro_id'].'","'.
																			  $model['cuerpo_id'].'","'.
																			  $model['nombre'].'","'.
																			  $model['tipo'].'","'.
																			  $model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'cuerpo_id','header' => 'Cod', 'contentOptions'=>['style'=>'width:10%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'nombre','header' => 'Nombre' ,'contentOptions'=>['style'=>'width:42%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'cuadro_id','header' => 'Cuadro' ,'value' => function($data) { return (utb::getCampo("cem_cuadro","cuadro_id='".$data["cuadro_id"]."'")); } ,
            		'contentOptions'=>['style'=>'width:33%;text-align:left;', 'class' => 'grilla']],
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:15%;text-align:center;','class'=>'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnModificarAuxCemCuerpo("'.$model['cuadro_id'].'","'.
																								  $model['cuerpo_id'].'","'.
																								  $model['nombre'].'","'.
																								  $model['tipo'].'","'.
																								  $model['modif'].'");'
													]
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7',
														'onclick' => 'btnEliminarAuxCemCuerpo("'.$model['cuadro_id'].'","'.
																							     $model['cuerpo_id'].'","'.
																							     $model['nombre'].'","'.
																							     $model['tipo'].'","'.
																							     $model['modif'].'");'
																								]
											            									);
												            						}
															    			]
															    	   ],

																],
															]);
		Pjax::end(); // fin bloque de la grilla
		?>
		</td>
		<td width='60%'>
		<?php
	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmCemCuerpo']);

	 	echo Html::input('hidden', 'txAccion',"", ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);
	 	echo Html::input('hidden', 'txForm',"", ['id'=>'txForm']);

	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px;margin-top:16px;margin-left:5px;'>
		<table border='0'>
		<tr>
			<td width='50px'><label>Codigo: </label></td>
			<td>
				<?= Html::input('text', 'cuerpo_id', $cuerpo_id, ['class' => 'form-control','id'=>'cuerpo_id','maxlength'=> '3','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
			<td width='55px' align='right'><label>Cuadro: </label></td>
			<td>
			  <?=  Html::dropDownList('cuadro_id',$cuadro_id,utb::getAux('cem_cuadro','cuadro_id','nombre',0),['id'=>'cuadro_id','class' => 'form-control', 'style'=>'width:95px','disabled' => true]);?>
			</td>
		</tr>
		</table>
		<table border='0'>
		<tr>
			<td width='50px' align='left'><label>Nombre: </label></td>
			<td>
			   <?= Html::input('text', 'txNombre',$nombre,['id' => 'txNombre','class' => 'form-control','maxlength'=>'20' ,'style' => 'width:195px;', 'disabled' => true ]); ?>
			</td>
			<td width='45px' align='right'><label>Tipo: </label></td>
			<td>
			  <?=  Html::dropDownList('tipo',$tipo,utb::getAux('cem_tipo','cod','nombre',0),['id'=>'tipoCemCuerpo','class' => 'form-control', 'style'=>'width:171px','disabled' => true]);?>
			</td>
		</tr>
		</table>

		<table border='0'>
		<tr>
			<td id='labelModifCemCuerpo' width='298px' align='right'><label>Modificación:</label></td>
			<td>
				<?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModifCemCuerpo','style'=>'width:150px;background:#E6E6FA;float:right;', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>

	        <div class="form-group" id='form_botonesCemCuerpo' style='display:none'>

			<?php

				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick'=>'btGrabarClickCemCuerpo()']);
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxCemCuerpo()']);
	    	?>
	        </div>
	        <div class="form-group" id='form_botones_deleteCemCuerpo' style='display:none'>

			<?php
				echo Html::Button('Eliminar', ['class' => 'btn btn-danger', 'onclick'=>'btGrabarClickCemCuerpo()']);
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick'=>'btnCancelarAuxCemCuerpo()']);
	    	?>
	    	</div>
    	</div>

		<?php
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
			'id' => 'MensajeInfoAUXT',
			'options' => [
			'class' => 'alert-info',
			'style' => $_GET['mensaje'] != '' ? 'display:block' : 'display:none'
			],
		]);

		if ($_GET['mensaje'] != '') echo $_GET['mensaje'];

		Alert::end();

		if ($_GET['mensaje'] != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoAUXT').alert('close');}, 5000)</script>";

		//-------------------------seccion de error------------------------------------------------------------------------------

		 	Pjax::begin(['id' => 'divErrorCemCuerpo']);

				if(isset($_POST['error']) and $_POST['error'] != '') {
					echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_POST['error'] . '</ul></div>';
					}

				if(isset($error) and $error != '') {
				echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
				}

			Pjax::end();

		 //------------------------------------------------------------------------------------------------------------------------

		 	Pjax::begin(['id' => 'divValidarCemCuerpo']);

		 		$err="";
		 		if(isset($_POST['cuerpo'])){
		 		$err = $model->validarTablaAuxCemCuerpo($_POST['consulta'],$_POST['cuadro'],$_POST['cuerpo']);
		 		if($err!=""){
			 		echo "<script>$.pjax.reload(
					{
						container:'#divErrorCemCuerpo',
						data:{
								error:'".$err."'
							},
						method:'POST'
					});</script>";

		 		}else{
		 			echo "<script>$('#frmCemCuerpo').submit();</script>";
		 		}}
			Pjax::end();

		//--------------------------------------------------------------------------------------------------------------------

    	ActiveForm::end();

    ?>
	</td>
	</tr>
	</table>
</div><!-- site-auxedit -->

<script>

	function CargarControlesCemCuerpo(cuadro_id,cuerpo_id,nombre,tipo,modif)
	{
			event.stopPropagation();
			$("#cuerpo_id").val(cuerpo_id);
			$("#cuadro_id").val(cuadro_id);
			$("#frmCemCuerpo :input[id=txNombre]").val(nombre);
			$("#tipoCemCuerpo").val(tipo);
			$("#txModifCemCuerpo").val(modif);
			$("#txModifCemCuerpo").prop('display','block');
			$("#labelModifCemCuerpo").css('display','block');

	}

	function btnNuevoAuxCemCuerpo(){
			$("#frmCemCuerpo :hidden[id=txForm]").val(1);
			$("#frmCemCuerpo :hidden[id=txAccion]").val(0);
			$('#form_botonesCemCuerpo').css('display','block');
			$('#form_botones_deleteCemCuerpo').css('display','none');

			$('#cuerpo_id').val('');
			$('#cuerpo_id').prop('readOnly',false);
			$("#cuadro_id option:first-of-type").attr("selected", "selected");
			$('#cuadro_id').prop('disabled',false);
			$("#frmCemCuerpo :input[id=txNombre]").val('');
			$("#frmCemCuerpo :input[id=txNombre]").prop('disabled',false);
			$("#tipoCemCuerpo option:first-of-type").attr("selected", "selected");
			$('#tipoCemCuerpo').prop('disabled',false);
			$('#txModifCemCuerpo').val('');
			$("#labelModifCemCuerpo").css('display','none');
			$("#txModifCemCuerpo").css('display','none');

			$('#btnNuevoCemCuerpo').css("pointer-events", "none");
			$('#btnNuevoCemCuerpo').css("opacity", 0.5);
			$('#GrillaTablaAuxCemCuerpo').css("pointer-events", "none");
			$('#GrillaTablaAuxCemCuerpo').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuerpo Button').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuerpo a').css("color", "#ccc");

	}

	function btnModificarAuxCemCuerpo(cuadro_id,cuerpo_id,nombre,tipo,modif){

			event.stopPropagation();
			$('#form_botonesCemCuerpo').css('display','block');
			$('#form_botones_deleteCemCuerpo').css('display','none');
			$("#frmCemCuerpo :hidden[id=txForm]").val(1);
			$("#frmCemCuerpo :hidden[id=txAccion]").val(3);

			$('#cuerpo_id').val(cuerpo_id);
			$('#cuerpo_id').prop('readOnly',true);
			$('#cuadro_id').val(cuadro_id);
			$('#cuadro_id').prop('disabled',true);
			$("#frmCemCuerpo :input[id=txNombre]").val(nombre);
			$("#frmCemCuerpo :input[id=txNombre]").prop('disabled',false);
			$("#tipoCemCuerpo").val(tipo);
			$('#tipoCemCuerpo').prop('disabled',false);
			$("#labelModifCemCuerpo").css('display','none');
			$('#txModifCemCuerpo').val('');
			$("#txModifCemCuerpo").css('display','none');

			$('#btnNuevoCemCuerpo').css("pointer-events", "none");
			$('#btnNuevoCemCuerpo').css("opacity", 0.5);
			$('#GrillaTablaAuxCemCuerpo').css("pointer-events", "none");
			$('#GrillaTablaAuxCemCuerpo').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuerpo Button').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuerpo a').css("color", "#ccc");

	}

	function btnEliminarAuxCemCuerpo(cuadro_id,cuerpo_id,nombre,tipo,modif){

			event.stopPropagation();
			$('#form_botonesCemCuerpo').css('display','none');
			$('#form_botones_deleteCemCuerpo').css('display','block');
			$("#frmCemCuerpo :hidden[id=txForm]").val(1);
			$("#frmCemCuerpo :hidden[id=txAccion]").val(2);

			$('#cuerpo_id').val(cuerpo_id);
			$('#cuerpo_id').prop('readOnly',true);
			$('#cuadro_id').val(cuadro_id);
			$('#cuadro_id').prop('disabled',true);
			$("#frmCemCuerpo :input[id=txNombre]").val(nombre);
			$("#frmCemCuerpo :input[id=txNombre]").prop('disabled',true);
			$("#tipoCemCuerpo").val(tipo);
			$('#tipoCemCuerpo').prop('disabled',true);
			$("#txModifCemCuerpo").val(modif);
			$("#txModifCemCuerpo").prop('display','block');
			$("#labelModifCemCuerpo").css('display','block');

			$('#btnNuevoCemCuerpo').css("pointer-events", "none");
			$('#btnNuevoCemCuerpo').css("opacity", 0.5);
			$('#GrillaTablaAuxCemCuerpo').css("pointer-events", "none");
			$('#GrillaTablaAuxCemCuerpo').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuerpo Button').css("color", "#ccc");
			$('#GrillaTablaAuxCemCuerpo a').css("color", "#ccc");

	}

	function btnCancelarAuxCemCuerpo(){

			$('.error-summary').css('display','none');
			$('#GrillaTablaAuxCemCuerpo').css("pointer-events", "all");
			$('#GrillaTablaAuxCemCuerpo').css("color", "#111111");
			$('#GrillaTablaAuxCemCuerpo a').css("color", "#337ab7");
			$('#GrillaTablaAuxCemCuerpo Button').css("color", "#337ab7");
			$('#btnNuevoCemCuerpo').css("pointer-events", "all");
			$('#btnNuevoCemCuerpo').css("opacity", 1 );

			$('#form_botonesCemCuerpo').css('display','none');
			$('#form_botones_deleteCemCuerpo').css('display','none');
			$('#cuerpo_id').val('');
			$('#cuerpo_id').prop('readOnly',true);
			$("#cuadro_id option:first-of-type").attr("selected", "selected");
			$('#cuadro_id').prop('disabled',true);
			$("#frmCemCuerpo :input[id=txNombre]").val('');
			$("#frmCemCuerpo :input[id=txNombre]").prop('disabled',true);
			$("#tipoCemCuerpo option:first-of-type").attr("selected", "selected");
			$('#tipoCemCuerpo').prop('disabled',true);
			$('#txModifCemCuerpo').val('');
			$('#txModifCemCuerpo').prop('disabled',true);
			$('#txModifCemCuerpo').css('display','block');
			$("#labelModifCemCuerpo").css('display','block');
	}


	function btGrabarClickCemCuerpo(){

			err="";
			if($('#cuerpo_id').val()==""){
				err += "<li>Ingrese un Codigo</li>";
			}
			if($("#frmCemCuerpo :input[id=txNombre]").val()==""){
				err += "<li>Ingrese un Nombre</li>";
			}
			if(err == ""){
				$('#cuadro_id').prop('disabled',false);
			$.pjax.reload(
			{
				container:'#divValidarCemCuerpo',
				data:{
					cuadro:$("#cuadro_id").val(),
					cuerpo:$("#frmCemCuerpo :input[id=cuerpo_id]").val(),
					consulta:$("#frmCemCuerpo :hidden[id=txAccion]").val()
					},
				method:'POST'
			});

			}else{

			$.pjax.reload(
				{
					container:'#divErrorCemCuerpo',
					data:{
							error:err
						},
					method:'POST'
				});
		   }
	   }

	$(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
	});

</script>
