<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Tabs;
use yii\web\Session;
use yii\bootstrap\Alert;
use yii\data\ArrayDataProvider;
use app\models\caja\CajaCobro;

$cod_usuario = Yii::$app->request->post('usuario_id','');
/**
 * Forma que se dibuja cuando se edita un usuario.
 * Recibo:
 * 			=> $model -> Modelo de Usuario
 *
 *
 */

 /**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */

 # Establecemos el título de la forma según $consulta
 switch ( $consulta )
 {
 	case 0:

 		$title = 'Nuevo Usuario';
 		break;

 	case 1:

 		$title = 'Consulta Usuario';
 		break;

 	case 2:
 	case 3:

 		$title = 'Modificar Usuario';
 		break;
 }

$this->params['breadcrumbs'][] = ['label' => 'Seguridad', 'url' => ['view']];
$this->params['breadcrumbs'][] = $title;

$form = ActiveForm::begin([
			'id' => 'formSeguridadUsuario',
			'fieldConfig' => ['template' => '{input}']
		]);

?>

<style>
#div_responsabilidades
{
	width: 50%;
	height: 100%;
	padding: 5px;
}

#div_tesorerias
{
	width: 40%;
	height: 100%;
	padding: 5px;
}

.form-group
{
	margin:0px;
}

.check_grillaTesoreria
{
	width: 12px;
    height: 12px;
    padding: 1px;
}

</style>

<h1><?= Html::encode($title) ?></h1>

<table>
	<tr>
    	<td>
<!-- INICIO DIV Seguridad -->
<div id="div_seguridad_editaUsuario_editausuario" style="display:block;margin:0px;width:625px;padding-bottom: 8px" class="form-panel">



<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorFormSeguridad_editaUsuario']);

			$mensaje = Yii::$app->request->post('mensaje','');
			$m = Yii::$app->request->post('m',2);

			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaFormSeguridad_editaUsuario',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaFormSeguridad_editaUsuario').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->


<table>
	<tr>
		<td width="60px"><label>Código</label></td>
		<td width="40px"><?= Html::input('text','Usuario[usr_id]',$model->usr_id,['id'=>'seguridad_editaUsuario_Usuario[usr_id]','class'=>'form-control solo-lectura','style'=>'width:40px;text-align:center','tabindex'=>-1]); ?></td>
		<td width="20px"></td>
		<td ><label>Usuario:</label></td>
		<td><?= Html::input('text','Usuario[nombre]',$model->nombre,[
					'id'=>'seguridad_editaUsuario_Usuario[nombre]',
					'class'=>'form-control ' . ($consulta == 3 ? 'solo-lectura' : ''),
					'style'=>'width:100px;text-align:center',
					'maxlength' => 15,
					]); ?></td>
		<td width="20px"></td>
		<td width="70px"><label>Estado:</label></td>
		<td width="100px"><?= Html::radio('Usuario[est]',$model->est == 'A' || $model->est == null ? true : false,['value'=>'A','id'=>'seguridad_editaUsuario_rbActivo','label'=>'Activo']);?></td>
		<td width="100px"><?= Html::radio('Usuario[est]',$model->est == 'B' ? true : false,['value'=>'B','id'=>'seguridad_editaUsuario_rbBaja','label'=>'Baja']);?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Nombre:</label></td>
		<td><?= Html::input('text','Usuario[apenom]',$model->apenom,['id'=>'seguridad_editaUsuario_Usuario[apenom]','class'=>'form-control','style'=>'width:210px;text-align:left','maxlength' => 40]); ?></td>
		<td width="20px"></td>
		<td width="70px"><label>Documento</label></td>
		<td>
			<?= Html::dropDownList('Usuario[tdoc]',$model->tdoc,utb::getAux('persona_tdoc'),[
					'id'=>'seguridad_editaUsuario_Usuario[tdoc]',
					'class'=>'form-control',
					'style'=>'width:96px']);?>
		</td>
		<td><?= Html::input('text','Usuario[ndoc]',$model->ndoc,['id'=>'seguridad_editaUsuario_Usuario[ndoc]','class'=>'form-control','style'=>'width:100px','onkeypress'=>'return justNumbers(event)']); ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Domicilio:</label></td>
		<td><?= Html::input('text','Usuario[domi]',$model->domi,['id'=>'seguridad_editaUsuario_Usuario[domi]','class'=>'form-control','style'=>'width:500px','maxlength' => 40]); ?></td>
	</tr>
</table>
<table>
	<tr>
		<td width="60px"><label>Oficina:</label></td>
		<td>
			<?= Html::dropDownList('Usuario[oficina]',$model->oficina,utb::getAux('sam.muni_oficina','ofi_id','nombre',1),[
					'id'=>'seguridad_editaUsuario_Usuario[oficina]',
					'class'=>'form-control select',
					'style'=>'width:200px',
					'onchange' => '$.pjax.reload({container:"#PjaxActualizaListaUsuarios",method:"POST",data:{id_grupo:$(this).val()}})']);?>
		</td>
		<td width="20px"></td>
		<td width="50px"><label>Grupo:</label></td>
		<td>
			<?= Html::dropDownList('Usuario[grupo]',$model->grupo,utb::getAux('sam.sis_grupo','gru_id','nombre'),[
					'id'=>'seguridad_editaUsuario_Usuario[grupo]',
					'class'=>'form-control',
					'style'=>'width:228px']);?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Cargo:</label></td>
		<td><?= Html::input('text','Usuario[cargo]',$model->cargo,['id'=>'seguridad_editaUsuario_Usuario[cargo]','class'=>'form-control','style'=>'width:200px','maxlength' => 30]); ?></td>
		<td width="20px"></td>
		<td width="50px"><label>Legajo:</label></td>
		<td><?= Html::input('text','Usuario[legajo]',$model->legajo,['id'=>'seguridad_editaUsuario_Usuario[legajo]','class'=>'form-control','style'=>'width:75px']); ?></td>
		<td width="20px"></td>
		<td><label>Matrícula:</label></td>
		<td><?= Html::input('text','Usuario[matricula]',$model->matricula,['id'=>'seguridad_editaUsuario_Usuario[matricula]','class'=>'form-control','style'=>'width:75px']); ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Telefono:</label></td>
		<td><?= Html::input('text','Usuario[tel]',$model->tel,['id'=>'seguridad_editaUsuario_Usuario[tel]','class'=>'form-control','style'=>'width:200px','maxlength' => 20]); ?></td>
		<td width="20px"></td>
		<td width="50px"><label>Cel:</label></td>
		<td><?= Html::input('text','Usuario[cel]',$model->cel,['id'=>'seguridad_editaUsuario_Usuario[cel]','class'=>'form-control','style'=>'width:228px','maxlength' => 20]); ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>e-mail:</label></td>
		<td><?= Html::input('text','Usuario[mail]',$model->mail,['id'=>'seguridad_editaUsuario_Usuario[mail]','class'=>'form-control','style'=>'width:500px','maxlength' => 50]); ?></td>
	</tr>
</table>

<div style="margin-top: 8px">
<div id="div_responsabilidades" class="form-panel pull-left" style="width: 50%">
<h3><strong>Responsabilidades</strong></h3>
<table>
	<tr>
		<td><?= Html::checkbox('Usuario[inspec_op]',$model->inspec_op,['id'=>'seguridad_editaUsuario_Usuario[inspec_op]','label'=>'Inspector Obras Privadas','uncheck'=>0]); ?></td>
		<td><?= Html::checkbox('Usuario[inspec_juz]',$model->inspec_juz,['id'=>'seguridad_editaUsuario_Usuario[inspec_juz]','label'=>'Inspector Juzgado','uncheck'=>0]); ?></td>
	</tr>
	<tr>
		<td><?= Html::checkbox('Usuario[inspec_inm]',$model->inspec_inm,['id'=>'seguridad_editaUsuario_Usuario[inspec_inm]','label'=>'Inspector Inmuebles','uncheck'=>0]); ?></td>
		<td><?= Html::checkbox('Usuario[distrib]',$model->distrib,['id'=>'seguridad_editaUsuario_Usuario[distrib]','label'=>'Distribuidor','uncheck'=>0]); ?></td>
	</tr>
	<tr>
		<td><?= Html::checkbox('Usuario[inspec_comer]',$model->inspec_comer,['id'=>'seguridad_editaUsuario_Usuario[inspec_comer]','label'=>'Inspector Comercios','uncheck'=>0]); ?></td>
		<td><?= Html::checkbox('Usuario[cajero]',$model->cajero,['id'=>'seguridad_editaUsuario_Usuario[cajero]','label'=>'Cajero','uncheck'=>0,'onchange'=>'CajeroClick(this.checked)']); ?></td>
	</tr>
	<tr>
		<td><?= Html::checkbox('Usuario[inspec_recl]',$model->inspec_recl,['id'=>'seguridad_editaUsuario_Usuario[inspec_recl]','label'=>'Inspector Reclamos','uncheck'=>0]); ?></td>
		<td><?= Html::checkbox('Usuario[abogado]',$model->abogado,['id'=>'seguridad_editaUsuario_Usuario[abogado]','label'=>'Abogado','uncheck'=>0]); ?></td>
	</tr>
</table>


</div>

<div id="div_tesorerias" class="form-panel pull-left" style="width: 40%">
<h3><strong>Tesorerías Asignadas</strong></h3>

<?php

Pjax::begin(['id' => 'PjaxGrillaTesoreria']);


	$dataProviderTesoreria = new ArrayDataProvider([
		'allModels' => $model->getTesoreriasUsuario(),
		'pagination' => [
			'pageSize' => 1000,
		],]);

	$modelo = $model;

 	echo GridView::widget([
		'id' => 'GrillaUsuarioTesoreria',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $dataProviderTesoreria,
		'summaryOptions' => ['class' => 'hidden'],
		'columns' => [
				['content'=> function($model, $key, $index, $column) {return Html::checkbox('Usuario[tesoreria]['.$model['teso_id'].']',$model['existe'] == 1 ? 1 : 0,
																		[
																			'style'=>'margin:0px',
																			'id' => 'seguridad_ckTesoreria'.$model['teso_id'],
																			'class'=>'check_grillaTesoreria',
																			'uncheck' => 0,
																			'onchange' => 'cambiarCheck()'
																		]
																	);},
																		'contentOptions'=>['style'=>'width:4px'],
																		'header' => Html::checkBox('selection_all', ($consulta == 3 ? true : false),
																		[
																			'id' => 'seguridad_ckTesoreriaTodos',
																	        'onchange'=>'cambiarChecksGrillaUsuarioTesoreria()',
																	        'class' => ($consulta == 2 ? 'disabled' : ''),
																	   	]),
				],
				['attribute'=>'teso_id','header' => 'ID', 'contentOptions'=>['style'=>'text-align:center','width'=>'4px']],
				['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'text-align:center','width'=>'300px']],
        ],
	]);

Pjax::end();
?>
</div>

<div id="div_tributos" class="form-panel pull-left" style="width: 100%">
<h3><strong>Tributos Asignados</strong></h3>

<?php

Pjax::begin(['id' => 'PjaxGrillaTributo']);


	$dataProviderTributo = new ArrayDataProvider([
		'allModels' => $model->getTributoUsuario(),
		'pagination' => [
			'pageSize' => 1000,
		],]);

	$modelo = $model;

 	echo GridView::widget([
		'id' => 'GrillaUsuarioTributo',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $dataProviderTributo,
		'summaryOptions' => ['class' => 'hidden'],
		'columns' => [
				['content'=> function($model, $key, $index, $column) {return Html::checkbox('Usuario[tributo]['.$model['trib_id'].']',$model['existe'] == 1 ? 1 : 0,
																		[
																			'style'=>'margin:0px',
																			'id' => 'seguridad_ckTributo'.$model['trib_id'],
																			'class'=>'check_grillaTributo',
																			'uncheck' => 0,
																			'onchange' => 'cambiarCheckTrib()'
																		]
																	);},
																		'contentOptions'=>['style'=>'width:4px'],
																		'header' => Html::checkBox('selection_alltrib', ($consulta == 3 ? true : false),
																		[
																			'id' => 'seguridad_ckTributoTodos',
																	        'onchange'=>'cambiarChecksGrillaUsuarioTributo()',
																	        'class' => ($consulta == 2 ? 'disabled' : ''),
																	   	]),
				],
				['attribute'=>'trib_id','header' => 'ID', 'contentOptions'=>['style'=>'text-align:center','width'=>'4px']],
				['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'300px']],
        ],
	]);

Pjax::end();
?>
</div>

</div>

<table>
	<tr>
		<td><label>Fecha Alta:</label></td>
		<td><?= Html::input('text','Usuario[fchalta]',Fecha::bdToUsuario($model->fchalta),['id'=>'seguridad_editaUsuario_Usuario[fchalta]','class'=>'form-control solo-lectura','style'=>'width:80px;text-align:center']); ?></td>
		<td width="20px"></td>
		<td><label>Fecha Baja:</label></td>
		<td><?= Html::input('text','Usuario[fchbaja]',Fecha::bdToUsuario($model->fchbaja),['id'=>'seguridad_editaUsuario_Usuario[fchbaja]','class'=>'form-control solo-lectura','style'=>'width:80px;text-align:center']); ?></td>
		<td width="20px"></td>
		<td><label>Modificación:</label></td>
		<td>
			<?= Html::input('text','Usuario[fchmod]',($model->usrmod != '' ? utb::getCampo('sam.sis_usuario','usr_id = ' . $model->usrmod, 'nombre') . ' - ' . Fecha::bdToUsuario($model->fchmod) : ''),[
					'id'=>'seguridad_editaUsuario_Usuario[fchmod]',
					'class'=>'form-control solo-lectura',
					'style'=>'width:140px;text-align:center'
				]);
			?>
		</td>
	</tr>
</table>

</div>

<table style="margin-top:10px;margin-bottom:5px">
	<tr>
		<?php
			if($consulta == 1)
			{
			?>

			<td><?= Html::a('Aceptar',['view'],['class' => 'btn btn-primary']); ?></td>

			<?php
			} else {

			?>
			<td><?= Html::submitButton('Aceptar',['class' => 'btn btn-success']); ?></td>
			<td width="20px"></td>
			<td><?= Html::a('Cancelar',['view'],['class' => 'btn btn-primary']); ?></td>
			<?php
			}
			?>

	</tr>
</table>

		</td>
		<td width="100px" valign="top" align="right">
			<?= $this->render('menu_derecho_editaUsuario', [
					'model' => $model,
					'consulta' => $consulta,
				]);
			?>
		</td>
	</tr>
</table>

<?php

echo $form->errorSummary($model);

ActiveForm::end();

if ($consulta == 1)
    	{
    		echo "<script>";
			echo "DesactivarForm('formSeguridadUsuario');";
			echo "</script>";

    	}

?>

<script>
function cambiarCheck()
{
	$("#seguridad_ckTesoreriaTodos").prop('checked','');
}

function cambiarCheckTrib()
{
	$("#seguridad_ckTributoTodos").prop('checked','');
}

function cambiarChecksGrillaUsuarioTesoreria()
{
	var checks = $('#GrillaUsuarioTesoreria input[type="checkbox"]');

	if ($("#seguridad_ckTesoreriaTodos").is(":checked"))
	{
		checks.each(function() {

			checks.prop('checked','checked');

		});
	} else
	{
		checks.each(function() {

			checks.prop('checked','');

		});
	}
}

function cambiarChecksGrillaUsuarioTributo()
{
	var checks = $('#GrillaUsuarioTributo input[type="checkbox"]');

	if ($("#seguridad_ckTributoTodos").is(":checked"))
	{
		checks.each(function() {

			checks.prop('checked','checked');

		});
	} else
	{
		checks.each(function() {

			checks.prop('checked','');

		});
	}
}

function igualaAltura()
{
	var alt_respon = parseFloat( $("#div_responsabilidades").css("height") );
	var alt_tesor = parseFloat( $("#div_tesorerias").css("height") );


	if ( alt_respon > alt_tesor )
		$("#div_resorerias").css("height", alt_respon );
	else
		$("#div_responsabilidades").css("height", alt_tesor );
}

$( document ).ready( function()
{

	igualaAltura();

});

function CajeroClick(value)
{

	$('#GrillaUsuarioTesoreria input[type=checkbox]').each(function(){
		this.checked = false;
	});

    $( "#seguridad_ckTesoreriaTodos" ).css("pointer-events", !value ? 'none' : 'all');
	$("#GrillaUsuarioTesoreria").css("pointer-events", !value ? 'none' : 'all');
}

</script>
