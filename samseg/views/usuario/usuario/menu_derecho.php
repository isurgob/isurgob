<?php
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\widgets\Pjax;


	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 *
	 * @param $model
	 */

	 if (!isset($consulta)) $consulta = 1;
	 if (!isset($accion)) $accion = '';
	 if (!isset($id)) $id = '';


	Pjax::begin(['id'=>'PjaxMenuDerecho']);

	$grupo = Yii::$app->request->post( 'id_grupo', 0 );
	$usuario = Yii::$app->request->post('id_usuario',0);
	$editaProceso = Yii::$app->request->post('editaProceso',0);

?>
<style>
#menuDerecho_btEditarProc, #menuDerecho_btBlanqueo {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>
<?php if (utb::getExisteProceso(1021)) { ?>
<table align="left" width="100%">
	<tr>
		<td align="center" style="color:black;font-weight: bolder;><h4><br">Grupo</br></h4></td>
	</tr>
</table>

<ul id='ulMenuDer1' class='menu_derecho'>
	<li id='liNuevoGrupo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>',['grupocreate'], ['class' => 'bt-buscar-label']); ?></li>
	<li id='liModificarGrupo' class='glyphicon glyphicon-pencil'>  <?= Html::a('<b>Modificar</b>',['grupoupdate','id' => $grupo], ['class' => 'bt-buscar-label']); ?></li>
	<li id='liEliminarGrupo' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>',['grupodelete','id' => $grupo], ['class' => 'bt-buscar-label']); ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
</ul>
<?php } ?>
<?php if ( utb::getExisteProceso(1021) || utb::getExisteProceso(1022) || utb::getExisteProceso(1023) || utb::getExisteProceso(1024) ) { ?>
<table align="left" width="100%">
	<tr>
		<td align="center" style="color:black;font-weight: bolder;><h4><br">Usuario</br></h4></td>
	</tr>
</table>
<?php } ?>
<ul id='ulMenuDer2' class='menu_derecho'>
	<?php if ( utb::getExisteProceso(1021) || utb::getExisteProceso(1024) ) { ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>',['create'], ['class' => 'bt-buscar-label']); ?></li>
	<li id='liEditarProc' class='glyphicon glyphicon-pencil'> <?= Html::a('Editar Proc.', ['usuarioproceso', 'id' => $usuario], [
																		'id' => 'menuDerecho_btEditarProc',
																	]);
																?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(1022)) { ?>
	<li id='liBlanqueo' class='glyphicon glyphicon-trash'> <?= Html::button('Blanqueo',[
																'id' => 'menuDerecho_btBlanqueo',
																'onclick' => '$.pjax.reload({' .
																				'container:"#limpiaClave",' .
																				'method:"POST",' .
																				'data:{' .
																					'limpiaClave_usuario:'.$usuario.'}});']);
															?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(1023)) { ?>
	<li id='liAccesos' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Accesos</b>',['acceso', 'id' => $usuario], ['class' => 'bt-buscar-label']); ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(1021) || utb::getExisteProceso(1022) || utb::getExisteProceso(1023)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(1020)) { ?>
	<li id='liProcesos' class='glyphicon glyphicon-cog'> <?= Html::a('<b>Procesos</b>',['procesosistema'], ['class' => 'bt-buscar-label']); ?></li>
	<?php } ?>
</ul>

<?php

		//Deshabilito todas las opciones
		echo '<script>$("#ulMenuDer1").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer1 li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer1 li a").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer2").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer2 li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer2 li a").css("color", "#ccc");</script>';

		//Si no se están editando los procesos de un usuario.
		if ( $editaProceso != 1 )
		{
			//Habilito Procesos
			echo '<script>$("#liProcesos").css("pointer-events", "all");</script>';
			echo '<script>$("#liProcesos a").css("color", "#337ab7");</script>';
			echo '<script>$("#liProcesos").css("color", "#337ab7");</script>';

			//Si dispone de los permisos para GRUPO y Nuevo, Editar y Editar Proc.
			if (utb::getExisteProceso(1021) == 1)
			{
				/* GRUPO */
				echo '<script>$("#liNuevoGrupo").css("pointer-events", "all");</script>';
				echo '<script>$("#liNuevoGrupo a").css("color", "#337ab7");</script>';
				echo '<script>$("#liNuevoGrupo").css("color", "#337ab7");</script>';

				if ( $grupo != 0 )
				{
					echo '<script>$("#liModificarGrupo").css("pointer-events", "all");</script>';
					echo '<script>$("#liModificarGrupo a").css("color", "#337ab7");</script>';
					echo '<script>$("#liModificarGrupo").css("color", "#337ab7");</script>';

					//Validar que el grupo no tenga usuarios asociados
					if ($model->existeUsuarioAsociado($grupo) == 0)
					{
						echo '<script>$("#liEliminarGrupo").css("pointer-events", "all");</script>';
						echo '<script>$("#liEliminarGrupo a").css("color", "#337ab7");</script>';
						echo '<script>$("#liEliminarGrupo").css("color", "#337ab7");</script>';

					}
				}

				echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
				echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
				echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';

				//Si se seleccionó un usuario y su estado es distinto de baja, habilitar la edición de procesos
				if ( $usuario != 0 && $model->est == 'A')
				{
					echo '<script>$("#liEditarProc").css("pointer-events", "all");</script>';
					echo '<script>$("#liEditarProc a").css("color", "#337ab7");</script>';
					echo '<script>$("#liEditarProc").css("color", "#337ab7");</script>';
				}

			}

			//Si el usuario seleccionado es Administrador del grupo, se habilita la edición de procesos.
			//Si el usuario seleccionado dispone del permiso "1024", también se habilita la edición de procesos
			if ( ( $usuario != 0 && ( $model->usrAdminGrupo($usuario) == 1 || utb::getExisteProceso(1024) ) ) )
			{
				echo '<script>$("#liEditarProc").css("pointer-events", "all");</script>';
				echo '<script>$("#liEditarProc a").css("color", "#337ab7");</script>';
				echo '<script>$("#liEditarProc").css("color", "#337ab7");</script>';
			}

			if ( utb::getExisteProceso(1022) == 1 && $usuario != 0 )
			{
				echo '<script>$("#liBlanqueo").css("pointer-events", "all");</script>';
				echo '<script>$("#liBlanqueo a").css("color", "#337ab7");</script>';
				echo '<script>$("#liBlanqueo").css("color", "#337ab7");</script>';
			}

			//Permiso para eliminar el último acceso de un usuario
			if ( utb::getExisteProceso(1023) == 1 && $usuario != 0 )
			{
				echo '<script>$("#liAccesos").css("pointer-events", "all");</script>';
				echo '<script>$("#liAccesos a").css("color", "#337ab7");</script>';
				echo '<script>$("#liAccesos").css("color", "#337ab7");</script>';
			}

		}

	Pjax::end();

?>
