<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
?>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3320)) { ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'opcBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Liquidación</h2>',
			'toggleButton' => [
                    'label' => '<b>Buscar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo $this->render('buscar');

			Modal::end();
		?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3321)) { ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['create'], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3323)) { ?>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>', ['update', 'ctacte' => $ctacte_id, 'menu' => 1], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3322)) { ?>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>', ['delete','id' => $ctacte_id,'accion' => 0], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3321) or utb::getExisteProceso(3322)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3320)) { ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> 
		<?php 
			$est = utb::getCampo('ctacte','ctacte_id='.(isset($ctacte_id) ? $ctacte_id : 0),'est');
			if ($est != 'D' and $est != 'E'){
				 echo Html::Button('<b>Imprimir</b>',['class' => 'bt-buscar-label', 'data-pjax' => "0",
				 			'onclick' => 'Error("No podrá imprimir la liquidación. Verifique el estado")'
						]);
			}else {
				echo Html::a('<b>Imprimir</b>', ['imprimircomprobante','id' => $ctacte_id], 
					['class' => 'bt-buscar-label', 'target' => '_black', 'data-pjax' => "0"]);
			} 
		?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)) { ?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index', 'obj_id' => $obj_id], ['class' => 'bt-buscar-label']) ?></li> 
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3320)) { ?>
	<li id='liListado' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Listado</b>', ['ctacte/listadoliquida/index'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
</ul>

<?php 
if (($ctacte_id == '' or $ctacte_id == null) and $consulta==1)
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
	}
}

?>
<script>
function Error(error)
{
	$("#errorliqevent").html(error);
	$("#errorliqevent").css("display","block");
}
</script>