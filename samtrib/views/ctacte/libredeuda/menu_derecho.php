<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;

$op = !isset ( $op ) ? '' : $op ;

?>
<ul id='ulMenuDer' class='menu_derecho'>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['//ctacte/libredeuda/index','op'=>'N'], ['class' => 'bt-buscar-label']) ?>	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liBloquear' class='glyphicon glyphicon-remove-sign'> <?= Html::a('<b>Bloquear</b>', ['//ctacte/libredeuda/accion','acc'=>'bloq'], ['class' => 'bt-buscar-label']) ?></li>
	<li id='liAplicDesc' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Aplic. Desc.</b>', ['//ctacte/libredeuda/accion','acc'=>'desc'], ['class' => 'bt-buscar-label']) ?>	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liNuevo' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Listado</b>', ['//ctacte/listadolibredeuda/index'], ['class' => 'bt-buscar-label']) ?>	</li>
</ul>

<?php


if ( $op == 'N' )
{
	//Deshabilitar todas las opciones
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
}
?>

