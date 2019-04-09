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
	 
	
	 if (!isset($consulta)) $consulta = 1;
	 if (!isset($accion)) $accion = '';
	 
	 $form = ActiveForm::begin([
								'id'=>'menuDerecho-chequecartera',
								'action' => ['chequecartera']]);		 
?>

<style>
#ulMenuDer button {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
}
</style>

<ul id='ulMenuDer' class='menu_derecho'>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>',['chequecartera','consulta'=> 0], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>',['chequecartera','consulta'=> 3, 'id'=>$model->cart_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>',['chequecartera','consulta'=> 2,'id'=>$model->cart_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<li id='liListado' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Listado</b>', ['//caja/listadochequecartera/index'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
</ul>

<?php 

//LA variable baja indica si el recibo que se muestra se encuentra dado de baja
if (($id == '' || $id == null) && $consulta == 1)
{
	// dashabilito todas las opciones 
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	
	// y luego solo habilito nuevo y listado
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liListado").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado a").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado").css("color", "#337ab7");</script>';
	
} else
{
	if ($consulta != 1)
	{
		// si se esta creado, modificando o eliminando => deshabilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
		
	} else {
		// habilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';	

	}
}

//Si estado != C, no se puede modificar ni eliminar
if ($model->cheque_cartera_est != 'C')
{ //Deshabilito update y delete
		echo '<script>$("#liModif").css("pointer-events", "none");</script>';
		echo '<script>$("#liModif").css("color", "#ccc");</script>';
		echo '<script>$("#liModif a").css("color", "#ccc");</script>';
		echo '<script>$("#liElim").css("pointer-events", "none");</script>';
		echo '<script>$("#liElim").css("color", "#ccc");</script>';
		echo '<script>$("#liElim a").css("color", "#ccc");</script>';
}
	ActiveForm::end();

?>