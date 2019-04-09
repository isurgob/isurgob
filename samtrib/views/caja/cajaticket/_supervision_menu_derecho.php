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
	 if (!isset($id)) $id = '';
						
?>
<style>
#btEliminarVencidas {
	color: #FFF !important;
}
</style>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3415)) { ?>
	<li id='liNuevo' class='glyphicon glyphicon-off'> <?= Html::a('<b>Apertura-Cierre</b>', ['apertcie'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>																						
	<?php } ?>
	<?php if (utb::getExisteProceso(3412)) { ?>
	<li id='liElim' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Estado Caja</b>', ['estadocaja'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>																							
	<?php } ?>
	<?php if (utb::getExisteProceso(3416)) { ?>
	<li id='liAprobar' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Anular</b>', ['anula'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>
	<?php } ?>
</ul>