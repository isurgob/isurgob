<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use \yii\widgets\Pjax;

$title = 'Asignar Procesos al Usuario';

$this->params['breadcrumbs'][] = ['label' => 'Seguridad', 'url' => ['view']];
$this->params['breadcrumbs'][] = $title;

?>

<table width="100%">
	<tr>
		<td align="left"><h1><?= Html::encode($title) ?></h1></td>
	</tr>
</table>

<div class="form-panel" style="padding:5px 10px;">
	<table width='100%'>
		<tr>
			<td width='7%'> <label> Usuario: </label> </td>
			<td>
				<?=
					Html::input('text','txUsuario', $model->nombre, [
						'id'=>'seguridad_txUsuario',
						'class'=>'form-control solo-lectura',
						'style'=>'width:90%;'
					]);
				?>
			</td>
			<td width='7%'> <label> Nombre: </label> </td>
			<td>
				<?=
					Html::input('text','txNombre', $model->apenom, [
						'id'=>'seguridad_txNombre',
						'class'=>'form-control solo-lectura',
						'style'=>'width:100%;'
					]);
				?>
			</td>
		</tr>
		<tr>
			<td> <label> Grupo: </label> </td>
			<td>
				<?=
					Html::input('text','txGrupo', $usuario_gru_nom, [
						'id'=>'seguridad_txGrupo',
						'class'=>'form-control solo-lectura',
						'style'=>'width:90%;'
					]);
				?>
			</td>
			<td> <label> Oficina: </label> </td>
			<td>
				<?=
					Html::input('text','txOficina', $usuario_oficina_nom, [
						'id'=>'seguridad_txOficina',
						'class'=>'form-control solo-lectura',
						'style'=>'width:100%;'
					]);
				?>
			</td>
		</tr>
	</table>
</div>

<div class="form-panel" style="padding:5px 10px;">
	<table width='100%'>
		<tr>
			<td width='7%'><label>Sistema:</label></td>
			<td width='45%'>
				<?= Html::dropDownList('dlSistema','',utb::getAux('sam.sis_sistema','sis_id','nombre',2),[
						'id'=>'seguridad_dlSistema',
						'class'=>'form-control',
						'style'=>'width:90%',
						'onchange' => 'cargarModulos()'
					]);
				?>
			</td>
			<td width='7%'><label>Módulo:</label></td>
			<td>
				<?php

				//INICIO Bloque de código que dibuja el combobox de Módulo
				Pjax::begin(['id'=>'PjaxActualizaModulo','enablePushState' => false, 'enableReplaceState' => false]);

					$sistema = Yii::$app->request->post('seg_sistema',0);

					echo Html::dropDownList('dlModulo','',utb::getAux('sam.sis_modulo','mod_id','nombre',2,'sis_id = ' . $sistema),[
						'id'=>'seguridad_dlModulo',
						'class'=>'form-control',
						'style'=>'width:100%',
						'onchange' => 'cargarProcesos()'
					]);

				Pjax::end();
				//FIN Bloque de código que dibuja el combobox de Módulo
				?>
			</td>
		</tr>
	</table>

	<!-- INICIO Grilla Procesos -->
	<div class="div_grilla">

	<?php

	$form = ActiveForm::begin(['id' => 'form-grillaProcesos','action' => ['procesos']]);


		Pjax::begin(['id' => 'PjaxGrillaProcesos', 'enablePushState' => false, 'enableReplaceState' => false]);

		?>

			<table style="padding:15px;margin-top:10px;margin-bottom:10px">
				<tr>
					<td><?= Html::submitButton('Aceptar',['class'=>'btn btn-success']); ?></td>
					<td width="20px"></td>
					<td><?= Html::a('Cancelar',['index'],['class'=>'btn btn-primary']); ?></td>
				</tr>
			</table>

		<?php

			echo Html::input('hidden','Usuario[usr_id]',$model->usr_id,['id' => 'seguridad_txCodUsuario']);
			echo Html::input('hidden','Usuario[sistema]',$sistema_id);
			echo Html::input('hidden','Usuario[modulo]',$modulo_id);

			echo GridView::widget([
				'id' => 'GrillaSeguridadProcesos',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProviderProcesos,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						[
							'class' => '\yii\grid\CheckboxColumn', 'name' => 'Usuario[permisos]',
							'checkboxOptions' => function($model, $key, $index, $column) {
													 return [ 
															'checked' => $model['existe'], 
															'class' => 'check_grilla',
															'value' => $model['pro_id']
														];
												 }
						],	
						['attribute'=>'pro_id','label' => 'Cod.', 'contentOptions'=>['style'=>'text-align:center','width'=>'7%']],
						['attribute'=>'sistema','label' => 'Sistema'],
						['attribute'=>'modulo','label' => 'Módulo'],
						['attribute'=>'nombre','label' => 'Nombre'],
				],
			]);

		Pjax::end();

	ActiveForm::end();

	//Actualizar el usuario en el menú derecho
	?>

	</div>
	<!-- FIN Grilla Procesos -->
</div>

<script>

function cargarModulos(){

	var sistema = $("#seguridad_dlSistema").val();
	
	$.pjax.reload({
		container:"#PjaxActualizaModulo",
		type:"POST",
		replace:false,
		push:false, 
		data:{
				seg_sistema: sistema
			}
	});
	
	$("#PjaxActualizaModulo").on("pjax:end",function(){

		cargarProcesos();

		$("#PjaxActualizaModulo").off("pjax:end");

	});
}

function cargarProcesos(){

	var sistema = $("#seguridad_dlSistema").val();
	var modulo = $("#seguridad_dlModulo").val();
	var usuario = $("#seguridad_txCodUsuario").val();
	
	$.pjax.reload({
		container:"#PjaxGrillaProcesos",
		type:"POST",
		replace:false,
		push:false,
		data:{
			"seguridad_sistema" : sistema,
			"seguridad_modulo" : modulo,
			"seguridad_usuario" : usuario
		}
	});
}

</script>