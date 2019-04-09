<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;
use app\utils\db\utb;
use yii\helpers\BaseUrl;
use yii\data\ArrayDataProvider;

/**
 * Forma que se dibuja cuando se llega a "Seguridad".
 * Recibo:
 * 			=> $model -> Modelo de Usuario
 *
 * Con esta forma, se mostrarán los grupos de usuarios y usuarios
 */

?>

<!-- INICIO DIV Seguridad -->
<div id="div_seguridad" style="display:block;margin:0;padding:0px;">

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorFormSeguridad']);

			$mensaje = Yii::$app->request->post('mensaje','');
			$m = Yii::$app->request->post('m',2);

			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaFormSeguridad',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaFormSeguridad').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<div id="div_grupos" class="form-panel" style="display:block;width:99%;height:100%;padding-bottom:5px;margin-bottom:10px">
<table>
	<tr>
		<td>
			<label for="seguridad_dlGrupo">Grupo:</label>
		</td>
		<td>
			<?= Html::dropDownList('dlGrupo','', $grupos,[
					'id'=>'seguridad_dlGrupo',
					'class'=>'form-control',
					'style'=>'width:200px',
					'onchange' => 'actualizaGrupo()']);?>
		</td>
		<td width="30px"></td>
		<td> 
			<label id='lbAdministradores' >Administradores: </label>
		</td>
	</tr>
	<tr>
		<td>
			<label for="seguridad_dlOficina">Oficina:</label>
		</td>
		<td>
			<?= Html::dropDownList('dlOficina','', $oficinas,[
					'id'=>'seguridad_dlOficina',
					'class'=>'form-control',
					'style'=>'width:200px',
					'onchange' => 'actualizaGrupo()']);?>
		</td>
	</tr>

</table>

</div>
<!-- FIN DIV Grupo -->

<!-- INICIO DIV Usuarios -->
<div id="div_usuarios" style="padding:10px;width:30%;height:100%;margin-right: 0px" class="form pull-left" >
	<h2><strong>Usuarios:</strong></h2>

	<!-- INICIO Grilla EMdp -->
	<div class="div_grilla">
			<table>
				<tr>
					<td width="55px"><label>Estado:</label></td>
					<td>
						<?=
							Html::dropDownList('dlFiltroEst', null, ['A'=>'Activo','B'=>'Baja'],[
								'id'=>'seguridad_dlFiltroEst',
								'class'=>'form-control',
								'style'=>'width:120px',
								'onchange' => 'actualizaGrupo()',
							]);
						?>
					</td>
				</tr>
				<tr>
					<td><label>Nombre:</label></td>
					<td>
						<?=
							Html::input('text','txFiltroNombre', null, [
								'id'=>'seguridad_txFiltroNombre',
								'class'=>'form-control',
								'style'=>'width:120px;',
								'maxlength' => 50,
								'onchange' => 'actualizaGrupo()',
							]);
						?>
					</td>
				</tr>
			</table>

            <?php
			
			echo Html::input('hidden','txCodUsuario',null, ['id' => 'seguridad_txCodUsuario']);

			Pjax::begin([ 'id' => 'pjaxUsuario' ]);
				
				echo GridView::widget([
					'id' => 'GrillaSeguridadUsuarios',
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => function ($model,$key,$index,$grid) {
										$adm = utb::getCampo( 'sam.sis_grupo', 'usradm1 = ' . $model['usr_id'] . ' or usradm2 = ' . $model['usr_id'], 'count(*)' );
										return [ 'onclick' => 'seleccionUsuario(' . $model['usr_id'] . ')', 'class' => 'grilla','style'=> $adm > 0 ? 'font-weight:bold' : 'font-weight:normal'];
									},
					'dataProvider' => $dataProviderUsuario,
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [

							['attribute'=>'usr_id','label' => 'Cod.', 'contentOptions'=>['style'=>'text-align:center','width'=>'30px']],
							['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'70px']],
							['attribute'=>'est','label' => 'Est.', 'contentOptions'=>['style'=>'text-align:center','width'=>'30px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:30px'],
								'template' =>'{viewusuario}{update}',
								'buttons'=>[
									'viewusuario' =>  function($url, $model2, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
												},

									'update' => function($url, $model2, $key) use ( $model )
												{
													if ( utb::getExisteProceso(1021) || utb::getExisteProceso(1024) || $model->usrAdminGrupo( $model2['usr_id'] ) == 1 )
														return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
												},
								]
							]
						],
				]);
			pjax::end();	

		?>
	</div>
	<!-- FIN Grilla EMdp -->

</div>
<!-- FIN DIV Usuarios -->

<!-- INICIO DIV Procesos -->
<div id="div_procesos" style="width:67%;height:100%;margin-right: 8px" class="form-panel pull-right">

	<table border="0">
		<tr>
			<td width="150px">
				<h2><strong>Procesos:</strong></h2>
			</td>
			<td>
				<h4> <strong id='stUsuario'></strong> </h4>
				<h6> <strong id='stGrupo'></strong> </h6>
			</td>
		</tr>
	</table>
	<table>
		<tr>
			<td><label for="seguridad_dlSistema">Sistema:</label></td>
			<td>
				<?= Html::dropDownList('dlSistema','',utb::getAux('sam.sis_sistema','sis_id','nombre',2),[
						'id'=>'seguridad_dlSistema',
						'class'=>'form-control',
						'style'=>'width:150px',
						'onchange' => 'cargarModulos()'
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label for="seguridad_dlModulo">Módulo:</label></td>
			<td>
				<?php

				//INICIO Bloque de código que dibuja el combobox de Módulo
				Pjax::begin(['id'=>'PjaxActualizaModulo','enablePushState' => false, 'enableReplaceState' => false]);

					$sistema = Yii::$app->request->get('seg_sistema',0);

					echo Html::dropDownList('dlModulo','',utb::getAux('sam.sis_modulo','mod_id','nombre',2,'sis_id = ' . $sistema),[
						'id'=>'seguridad_dlModulo',
						'class'=>'form-control',
						'style'=>'width:150px',
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

			$usuario_id = Yii::$app->request->get('seguridad_usuario', 0); 
			$sistema_id = Yii::$app->request->get('seguridad_sistema', 0); 
			$modulo_id = Yii::$app->request->get('seguridad_modulo', 0);
			
			echo Html::input('hidden','Usuario[usr_id]',$usuario_id);
			echo Html::input('hidden','Usuario[sistema]',$sistema_id);
			echo Html::input('hidden','Usuario[modulo]',$modulo_id);

			$arrayProcesos = $model->getProcesosAsignados( $usuario_id, $sistema_id, $modulo_id );
			
			$dataProviderProcesos = new ArrayDataProvider([
				'allModels' => $arrayProcesos,
				'pagination' => [
					'pageSize' => count($arrayProcesos),
				],
				'sort' => [
					'attributes' => [
						'pro_id',
						'sistema' => [
							'asc' => ['sistema' => SORT_ASC, 'modulo' => SORT_ASC, 'nombre' => SORT_ASC],
							'desc' => ['sistema' => SORT_DESC, 'modulo' => SORT_DESC, 'nombre' => SORT_DESC],
									],

					],
				]]);

		 	echo GridView::widget([
				'id' => 'GrillaSeguridadProcesos',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProviderProcesos,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
							
						['attribute'=>'pro_id','label' => 'Cod.', 'contentOptions'=>['style'=>'text-align:center','width'=>'30px']],
						['attribute'=>'sistema','label' => 'Sistema', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						['attribute'=>'modulo','label' => 'Módulo', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
						['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
		        ],
			]);

		Pjax::end();

	ActiveForm::end();

	//Actualizar el usuario en el menú derecho
	?>

	</div>
	<!-- FIN Grilla Procesos -->

</div>
<!-- FIN DIV Procesos -->

<?php 
Pjax::begin(['id' => 'limpiaClave', 'enablePushState' => false, 'enableReplaceState' => false]);

	$usuario = Yii::$app->request->post('limpiaClave_usuario',0);

	if ($usuario != 0)
	{
		$res = $model->claveLimpiar($usuario);

		if ($res == 1)
		{
			echo '<script>$.pjax.reload({container:"#errorFormSeguridad",method:"POST",data:{m:1,mensaje:"La clave se limpió correctamente."}});</script>';
		} else
		{
			echo '<script>$.pjax.reload({container:"#errorFormSeguridad",method:"POST",data:{m:2,mensaje:"Ocurrió un error al limpiar la clave."}});</script>';
		}
	}

Pjax::end();
?>

<script>

function actualizaGrupo(){

	var grupo = $("#seguridad_dlGrupo").val();
	
	
	$.post( "<?= BaseUrl::toRoute('obteneradministradores');?>", { "grupo": grupo } 
	).success(function(data){
		datos = jQuery.parseJSON(data); 
		
		$("#lbAdministradores").html( datos.administradores );
		
		cargarUsuarios();
		
	});
}

function cargarUsuarios(){

	var grupo = $("#seguridad_dlGrupo").val(),
		oficina = $("#seguridad_dlOficina").val(),
		est = $("#seguridad_dlFiltroEst").val(),
		usrnom = $("#seguridad_txFiltroNombre").val();
	
	$.pjax.reload({
		container:"#pjaxUsuario",
		type:"GET",
		replace:false,
		push:false,
		data:{
			id_grupo: grupo,
			usr_nom: usrnom,
			estado: est,
			oficina	: oficina
		},
	});
}

function seleccionUsuario( usuario ){

	$("#seguridad_txCodUsuario").val( usuario );
	cargarProcesos();
	
	$("#PjaxGrillaProcesos").on("pjax:end",function(){

		$.post( "<?= BaseUrl::toRoute('obtenerdatosusuario');?>", { "usuario": usuario } 
		).success(function(data){
			datos = jQuery.parseJSON(data); 
			
			$("#stUsuario").html( datos.usuario_desc );
			$("#stGrupo").html( datos.grupo_desc );		
			
			actualizarMenuDerecho( usuario, datos.usuario_gru, 0 );
			
		});

		$("#PjaxGrillaProcesos").off("pjax:end");

	});
	
}

function actualizarMenuDerecho( usuario, grupo, editaproceso ){

	$.pjax.reload({
		container:"#PjaxMenuDerecho",
		method:"POST",
		replace:false,
		push:false,
		data:{
			"id_grupo" : grupo,
			"id_usuario" : usuario,
			"editaProceso" : editaproceso
		}
	});
}

function cargarProcesos(){

	var sistema = $("#seguridad_dlSistema").val();
	var modulo = $("#seguridad_dlModulo").val();
	var usuario = $("#seguridad_txCodUsuario").val();
	
	$.pjax.reload({
		container:"#PjaxGrillaProcesos",
		type:"GET",
		replace:false,
		push:false,
		data:{
			"seguridad_sistema" : sistema,
			"seguridad_modulo" : modulo,
			"seguridad_usuario" : usuario
		}
	});
}

function cargarModulos(){

	var sistema = $("#seguridad_dlSistema").val();
	
	$.pjax.reload({
		container:"#PjaxActualizaModulo",
		type:"GET",
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

function actualizaAltura()
{
	var alt_usuario = parseFloat( $("#div_usuarios").css("height") );
	var alt_proceso = parseFloat( $("#div_procesos").css("height") );

	if ( alt_usuario > alt_proceso )
		$("#div_procesos").css("height", alt_usuario );
	else
		$("#div_usuarios").css("height", alt_proceso );

}

$(document).on("pjax:complete", function() {

	actualizaAltura();

});

$(document).ready(function() {

	actualizaAltura();
});
</script>
