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
	 */
	 
	 $reiniciar = 0;
	 if (!isset($consulta)) $consulta = 1;
		 
?>

<style>
#ulMenuDer button {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>

<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3497)) { ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>',['createrecibomanual', 'reiniciar' => 1], [
																'id'=>'reciboManual_md_nuevo',
																'class' => 'bt-buscar-label',
															]);
														?></li>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>',['updaterecibomanual', 'reiniciar' => 0, 'consulta' => 3, 'accion' => 1, 'id' => $id],[
																'id'=>'reciboManual_md_modif',
																'class' => 'bt-buscar-label',
															]);
														?></li>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>',['deleterecibomanual', 'reiniciar' => 0, 'consulta' => 2, 'accion' => 0, 'id' => $id],[
																'id'=>'reciboManual_md_elim',
																'class' => 'bt-buscar-label',
															]);
														?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liActivar' class='glyphicon glyphicon-ok'> <?= Html::a('<b>Activar</b>',['viewrecibomanual', 'accion' => 3, 'id' => $id],[
																'id'=>'reciboManual_md_activar',
																'class' => 'bt-buscar-label',
															]);
														?></li> 
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3496)) { ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', ['imprimirrecibomanual','id'=>$id], ['class' => 'bt-buscar-label', 'target' => '_black','data-pjax' => "0"]) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liListado' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Listado</b>', ['id'=>'reciboManual_md_listado','recibomanual_list'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
</ul>

<?php 

//La variable baja indica si el recibo que se muestra se encuentra dado de baja
if ((( $id == '' || $id == null) && $consulta==1) || $baja )
{
	// dashabilito todas las opciones 
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	
	// y luego solo habilito buscar, nuevo y listado
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liListado").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado a").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado").css("color", "#337ab7");</script>';
	
	if ($baja)
	{
		echo '<script>$("#liActivar").css("pointer-events", "all");</script>';
		echo '<script>$("#liActivar a").css("color", "#337ab7");</script>';
		echo '<script>$("#liActivar").css("color", "#337ab7");</script>';
	}
	
}else
{
	if ($consulta !== 1)
	{
		// si se esta creado, modificando o eliminando => deshabilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	}else {
		// habilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';
		
		//Deshabilito baja
		echo '<script>$("#liActivar").css("pointer-events", "none");</script>';
		echo '<script>$("#liActivar a").css("color", "#ccc");</script>';
		echo '<script>$("#liActivar").css("color", "#ccc");</script>';
	}
}

?>