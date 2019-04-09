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
	 if (!isset($accion)) $accion = '';
	 
	 $form = ActiveForm::begin([
								'id'=>'menuDerecho-regPagoAnt',
								'action' => ['pagoant']]);
								
	Pjax::begin(['id'=>'datos-regpagoant']);
					
		if (isset($_POST['consulta'])) $consulta = $_POST['consulta'];
		if (isset($_POST['accion'])) $accion = $_POST['accion'];
		
		if (isset($_POST['enviar']) && $_POST['enviar'] == 1)
		{
			echo Html::input('hidden','recibomanual-id',$id,['id'=>'recibomanual-id']);
			echo Html::input('hidden','recibomanual-consulta',$consulta,['id'=>'recibomanual-consulta']);
			echo Html::input('hidden','recibomanual-accion',$accion,['id'=>'recibomanual-accion']);
			
			echo '<script>$("#menuDerecho-regPagoAnt").submit()</script>';
		}
	
	Pjax::end();			
		 
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
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>',['pagoant','consulta'=> 0], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::button('<b>Eliminar</b>', [
																'class' => 'bt-buscar-label',
																'id' => 'pagoAnt_menuDerecho_btEliminar',
																'onclick' => '$("#ModalEmiminar").modal("show")',
															]);
														?></li>
	<li id='liListado' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Listado</b>', ['//caja/listadopagosant/index'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
</ul>

<?php 

if ( ( ( $id == '' || $id == null ) && $consulta == 1 ) )
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
	
}else
{
	if ( $consulta !== 1 )
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
	}
}

?>