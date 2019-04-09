<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;

$cod_usuario = Yii::$app->request->get('usuario_id','');
/**
 * Forma que se dibuja cuando se edita un usuario.
 * Recibo:
 * 			=> $model -> Modelo de Usuario
 *
 *
 */

$title = 'Usuarios y Grupos por Proceso';

$this->params['breadcrumbs'][] = ['label' => 'Seguridad', 'url' => ['view']];
$this->params['breadcrumbs'][] = ['label' => 'Procesos', 'url' => ['procesosistema']];
$this->params['breadcrumbs'][] = $title;


?>

<style>
.div_grilla {

	padding-right: 10px;
	padding-bottom: 10px;
}
</style>

<table width="100%">
	<tr>
		<td align="left"><h1><?= Html::encode($title) ?></h1></td>
	</tr>
</table>


<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorFormUsuarioUsuarioYGrupo']);

			$mensaje = Yii::$app->request->post('mensaje','');
			$m = Yii::$app->request->post('m',2);

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaFormUsuarioUsuarioYGrupo',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaFormUsuarioUsuarioYGrupo').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->


<!-- INICIO DIV usuarioYGrupo -->
<div id="div_seguridad_usuarioYGrupo" style="display:block;margin:0px">

<div class="form-panel">

<table width="100%">
	<tr>
		<td align="left"><h3><strong><label>Usuarios y Grupos por Proceso:</label></strong></h3></td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Cod:</label></td>
		<td><?= Html::input('text','txId',$model->permiso_id,['id'=>'seguridad_usuarioYGrupo_txId', 'class' => 'form-control solo-lectura', 'style' => 'width:50px']); ?></td>
		<td width="20px"></td>
		<td><label>Nombre:</label></td>
		<td><?= Html::input('text','txNombre',$model->permiso_nombre,['id'=>'seguridad_usuarioYGrupo_txNombre', 'class' => 'form-control solo-lectura', 'style' => 'width:140px']); ?></td>
		<td width="20px"></td>
		<td><label>Sistema:</label></td>
		<td><?= Html::input('text','txSistema',$model->permiso_sistema_nombre,['id'=>'seguridad_usuarioYGrupo_txSistema', 'class' => 'form-control solo-lectura', 'style' => 'width:140px']); ?></td>
		<td width="20px"></td>
		<td><label>Módulo:</label></td>
		<td><?= Html::input('text','txModulo',$model->permiso_modulo_nombre,['id'=>'seguridad_usuarioYGrupo_txModulo', 'class' => 'form-control solo-lectura', 'style' => 'width:140px']); ?></td>
	</tr>
</table>

<table>
	<tr>
		<td  width="50px"><label>Detalle:</label></td>
		<td><?= Html::input('text','txDetalle',$model->permiso_detalle,['id'=>'seguridad_usuarioYGrupo_txDetalle', 'class' => 'form-control solo-lectura', 'style' => 'width:682px']); ?></td>
	</tr>
</table>

</div>

<?php


Pjax::begin(['id'=>'PjaxGrupos']);

$grupo_id = Yii::$app->request->get('gru_id',0);
$proceso_id = Yii::$app->request->get('proceso_id',0);
$eliminar = Yii::$app->request->get('eliminarGrupo',0);

if ($grupo_id != 0 && $eliminar != 0)
{
    $res = $model->eliminarProcesoDeGrupo($grupo_id,$proceso_id);

	if ( $res['return'] == 1 )
	{
		?>
		<script>
		$("#PjaxGrupos").on("pjax:end",function() {

			$.pjax.reload({container:"#errorFormUsuarioUsuarioYGrupo",method:"POST",data:{mensaje:"Los datos se modificaron correctamente.",m:1}});
			$("#PjaxGrupos").off("pjax:end");
		});
		</script>

		<?php
	} else
	{
		?>
		<script>
		$("#PjaxGrupos").on("pjax:end",function() {

			$.pjax.reload({container:"#errorFormUsuarioUsuarioYGrupo",method:"POST",data:{mensaje:"<?= $res['mensaje'] ?>"}});
			$("#PjaxGrupos").off("pjax:end");
		});
		</script>

		<?php
	}
}

?>
<div style="display:table;width:98%">
<div style="display:table-row">

<div style="display:table-cell;width:48%;height:1px">

<!-- INICIO DIV Grupos -->
<div class="form-panel sin-margen" style="width:100%;height:100%;">

<h4><label>Grupos:</label></h4>

	<!-- INICIO Grilla Grupos -->
	<div class="div_grilla">

	<?php


		$dataProviderGrupos = new ArrayDataProvider(
		[
			'allModels' => $model->getProceso_grupos($model->permiso_id),
			'pagination' => [
						'pageSize' => 1000,
			],
			'sort' => [
				'attributes' => [
					'gru_id',
					'nombre',
				]
			]
		]);

		$pro_id = $model->permiso_id;

	 	echo GridView::widget([
			'id' => 'GrillaUsuarioYGrupos_Grupos',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProviderGrupos,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'gru_id','label' => 'Cód.', 'contentOptions'=>['style'=>'text-align:center','width'=>'4px']],
					['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'4px']],
					[
						'class' => 'yii\grid\ActionColumn',
						'contentOptions'=>['style'=>'width:6px'],
						'template' =>'{usuarioygrupo}',
						'buttons'=>[
							'usuarioygrupo' =>  function($url, $model, $key) use ($pro_id)
										{
											return Html::Button('<span class="glyphicon glyphicon-trash"></span>', [
												'class'=>'bt-buscar-label',
												'style'=>'color:#337ab7',
												'onclick'=>'$.pjax.reload({container:"#PjaxGrupos",method:"GET",replace:false,push:false,data:{gru_id:'.$model["gru_id"].',proceso_id:'.$pro_id.',eliminarGrupo:1}})',
											]);
										},
						]
					]
	        ],
		]);

	?>
	</div>
	<!-- FIN Grilla Grupos -->

</div>
<!-- FIN DIV Grupos -->

</div>

<div style="display:table-cell;width:1%;height:1px">
</div>

<div style="display:table-cell;width:48%;height:1px">

<!-- INICIO DIV Accesos Usuarios -->
<div style="width:100%;height:100%" class="form-panel sin-margen">
<h4><label>Usuarios:</label></h4>

	<!-- INICIO Grilla Accesos Usuarios -->
	<div class="div_grilla">
	<?php


		Pjax::begin(['id'=>'PjaxUsuarios']);

			$usuario_id = Yii::$app->request->get('usr_id',0);
			$proceso_id = Yii::$app->request->get('proceso_id',0);
			$eliminar = Yii::$app->request->get('eliminarUsuario',0);

			if ($usuario_id != 0 && $eliminar != 0)
			{
				$res = $model->eliminarProcesoDeUsuario($usuario_id,$proceso_id);

				if ($res['return'] == 1)
				{
					?>
					<script>
					$("#PjaxUsuarios").on("pjax:end",function()
					{

						$.pjax.reload({container:"#errorFormUsuarioUsuarioYGrupo",method:"POST",data:{mensaje:"Los datos se modificaron correctamente.",m:1}});
						$("#PjaxUsuarios").off("pjax:end");
					});
					</script>

					<?php
				} else
				{
					?>
					<script>
					$("#PjaxUsuarios").on("pjax:end",function()
					{

						$.pjax.reload({
							container:"#errorFormUsuarioUsuarioYGrupo",
							method:"POST",
							data:{
								mensaje:"Ocurrió un error al intentar modificar los datos."}});
						$("#PjaxUsuarios").off("pjax:end");
					});
					</script>

					<?php
				}
			}


			$dataProviderUsuarios = new ArrayDataProvider(
			[
				'allModels' => $model->getProceso_usuarios($model->permiso_id),
				'pagination' => [
							'pageSize' => 1000,
				],
				'sort' => [
					'attributes' => [
						'usr_id',
						'nombre',
					]
				]
			]);

			$pro_id = $model->permiso_id;

		 	echo GridView::widget([
				'id' => 'GrillaUsuarioYGrupos_Usuarios',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProviderUsuarios,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'usr_id','label' => 'Cód.', 'contentOptions'=> ['style'=>'text-align:center','width'=>'4px']],
						['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=> ['style'=>'text-align:left','width'=>'4px']],
						['attribute'=>'est','label' => 'est', 'contentOptions'=> ['style'=>'text-align:center','width'=>'4px']],
						[
							'class' => 'yii\grid\ActionColumn',
							'contentOptions'=>['style'=>'width:6px'],
							'template' =>'{usuarioygrupo}',
							'buttons'=>[
								'usuarioygrupo' =>  function($url, $model, $key) use ($pro_id)
											{
												return Html::Button('<span class="glyphicon glyphicon-trash"></span>', [
													'class'=>'bt-buscar-label',
													'style'=>'color:#337ab7',
													'onclick'=>'$.pjax.reload({container:"#PjaxUsuarios",method:"GET",replace:false,push:false,data:{usr_id:'.$model["usr_id"].',proceso_id:'.$pro_id.',eliminarUsuario:1}})',
												]);
											},
							]
						]
		        ],
			]);

		Pjax::end();
	?>

	</div>
	<!-- FIN Grilla Accesos Usuarios -->

</div>
<!-- FIN DIV Accesos Usuarios -->

</div>

</div>
</div>

<?php

Pjax::end();

?>

</div>
